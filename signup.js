function toggleCustomMealInput() {
  const select = document.getElementById('meal_type');
  const customInput = document.getElementById('custom_meal_type');
  if (select.value === 'custom') {
    customInput.style.display = 'block';
    customInput.required = true;
  } else {
    customInput.style.display = 'none';
    customInput.required = false;
  }
}


document.querySelector("form").addEventListener("submit", function(e) {
    const password = document.querySelector('input[name="password"]').value;
    const confirm = document.querySelector('input[name="confirm_password"]').value;

    const strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

    if (!strongRegex.test(password)) {
        alert("Password must be at least 8 characters and include uppercase, lowercase, number, and special character.");
        e.preventDefault();
    } else if (password !== confirm) {
        alert("Passwords do not match.");
        e.preventDefault();
    }
});


window.addEventListener("load", function () {
  const preloader = document.getElementById("preloader");
  if (preloader) {
    setTimeout(() => {
      preloader.classList.add("hidden");
    }, 1200); // 1.2s delay to show logo & spinner
  }
});

