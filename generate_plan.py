# generate_plan.py
from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from flask_cors import CORS
from openai import OpenAI
import datetime
import json
import re

app = Flask(__name__)
app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://root:@localhost/risebody'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
CORS(app, supports_credentials=True)
db = SQLAlchemy(app)

import os
from dotenv import load_dotenv
load_dotenv()

from openai import OpenAI
client = OpenAI(api_key=os.getenv("OPENAI_API_KEY"))
print("✅ OpenAI client initialized")

# ----------------------- DATABASE MODELS -----------------------
class User(db.Model):
    __tablename__ = 'users'
    user_id = db.Column(db.Integer, primary_key=True)
    full_name = db.Column(db.String(100))
    email = db.Column(db.String(100))
    gender = db.Column(db.Enum('male', 'female'))

class Assessment(db.Model):
    __tablename__ = 'assessment'
    assessment_id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer)
    scan_date = db.Column(db.Date)
    body_shape = db.Column(db.String(100))
    fat_distribution = db.Column(db.String(100))
    focus_area = db.Column(db.String(255))
    meal_structure = db.Column(db.Text)
    training_days_per_week = db.Column(db.Integer)
    fitness_level = db.Column(db.String(100))
    metabolism_type = db.Column(db.String(100))
    height_cm = db.Column(db.Numeric(5, 2))
    weight_kg = db.Column(db.Numeric(5, 2))

class Meal(db.Model):
    __tablename__ = 'meal'
    meal_id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer)
    assessment_id = db.Column(db.Integer)
    meal_plan_title = db.Column(db.String(255))
    created_at = db.Column(db.DateTime, default=datetime.datetime.utcnow)

class MealPlan(db.Model):
    __tablename__ = 'meal_plan'
    id = db.Column(db.Integer, primary_key=True)
    meal_id = db.Column(db.Integer)
    day = db.Column(db.String(20))
    meal_type = db.Column(db.String(50))
    meal = db.Column(db.String(255))
    calories = db.Column(db.String(50))
    description = db.Column(db.Text)

class Workout(db.Model):
    __tablename__ = 'workout'
    workout_id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer)
    assessment_id = db.Column(db.Integer)
    workout_title = db.Column(db.String(255))
    created_at = db.Column(db.DateTime, default=datetime.datetime.utcnow)

class WorkoutPlan(db.Model):
    __tablename__ = 'workout_plan'
    id = db.Column(db.Integer, primary_key=True)
    workout_id = db.Column(db.Integer)
    day = db.Column(db.String(20))
    exercise = db.Column(db.String(255))
    duration = db.Column(db.String(50))
    focus_area = db.Column(db.String(255))
    description = db.Column(db.Text)
    sets = db.Column(db.String(50))
    rounds = db.Column(db.String(50))

# ----------------------- ROUTE -----------------------
@app.route('/generate-plan', methods=['POST'])
def generate_plan():
    print("🚀 /generate-plan route hit")
    user_id = request.json.get('user_id')
    print(f"📦 Received user_id: {user_id}")

    user = User.query.filter_by(user_id=user_id).first()
    assessment = Assessment.query.filter_by(user_id=user_id).order_by(Assessment.assessment_id.desc()).first()

    if not user or not assessment:
        print("❌ User or assessment not found")
        return jsonify({'error': 'User or assessment not found'}), 404

    user_input = f"""
    User is a {user.gender}.
    Height: {assessment.height_cm} cm
    Weight: {assessment.weight_kg} kg
    Focus Area: {assessment.focus_area}
    Body Shape: {assessment.body_shape}
    Fat Distribution: {assessment.fat_distribution}
    Fitness Level: {assessment.fitness_level}
    Trains {assessment.training_days_per_week} days/week
    Meal Structure: {assessment.meal_structure}
    Metabolism: {assessment.metabolism_type}
    """

    try:
        print("🧠 Calling OpenAI API now...")
        response = client.chat.completions.create(
            model="gpt-3.5-turbo",
            messages=[
                {
                    "role": "system",
                    "content": f"You are a nutritionist and trainer. Create a meal plan as a dictionary with 7 days (day_1 to day_7) as keys. Each day contains breakfast, lunch, dinner, and snacks. Each meal must be an object with keys: meal, calories, description. Also generate a workout plan with {assessment.training_days_per_week} days (day_1, day_2, etc.) where each day is a key and the value is a list of exercises. Each exercise should have: exercise, duration, sets, rounds, description. Return only JSON."
                },
                {"role": "user", "content": user_input.strip()}
            ]
        )
        content = response.choices[0].message.content
        print("🧾 Raw GPT content:\n", content)

        clean_content = re.sub(r"```(json)?", "", content).strip()
        data = json.loads(clean_content)
    except Exception as e:
        print("❌ GPT error:", e)
        return jsonify({"error": f"GPT error: {str(e)}"}), 500

    now = datetime.datetime.now().strftime("%Y%m%d%H%M")
    workout_title = f"{assessment.focus_area.title()} Boost"
    meal_title = f"Smart Meals for {assessment.focus_area.title()}"

    workout = Workout(user_id=user_id, assessment_id=assessment.assessment_id, workout_title=workout_title)
    db.session.add(workout)
    db.session.commit()

    workout_data = data.get("workout_plan") or data.get("workoutPlan")
    if isinstance(workout_data, dict):
        for day, exercises in workout_data.items():
            if isinstance(exercises, list):
                for entry in exercises:
                    db.session.add(WorkoutPlan(
                        workout_id=workout.workout_id,
                        day=day,
                        exercise=entry.get('exercise', ''),
                        duration=entry.get('duration', ''),
                        focus_area=assessment.focus_area,
                        description=entry.get('description', ''),
                        sets=str(entry.get('sets', '')),
                        rounds=str(entry.get('rounds', ''))
                    ))

    meal = Meal(user_id=user_id, assessment_id=assessment.assessment_id, meal_plan_title=meal_title)
    db.session.add(meal)
    db.session.commit()

    meal_data = data.get("meal_plan") or data.get("mealPlan")
    if isinstance(meal_data, dict):
        for day, meals in meal_data.items():
            for meal_type, entry in meals.items():
                if isinstance(entry, dict):
                    meal_text = entry.get('meal', '')
                    calories = str(entry.get('calories', ''))
                    description = entry.get('description', '')
                else:
                    meal_text = entry
                    calories = ''
                    description = ''
                db.session.add(MealPlan(
                    meal_id=meal.meal_id,
                    day=day,
                    meal_type=meal_type.strip().capitalize(),  # Just "Breakfast", "Lunch", etc.
                    meal=meal_text,
                    calories=calories,
                    description=description
                ))

    try:
        db.session.flush()
    except Exception as db_err:
        print("❌ DB error while flushing MealPlan:", db_err)
        return jsonify({"error": "MealPlan insert failed"}), 500

    db.session.commit()

    return jsonify({
        "message": "Plan saved successfully!",
        "workout_title": workout_title,
        "meal_title": meal_title
    })

# if __name__ == '__main__':
#     app.run(debug=True, port=5000)
