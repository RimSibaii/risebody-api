<?php
$user_id = 1; // Replace with session or actual ID if needed

$data = ['user_id' => $user_id];

$options = [
  'http' => [
    'header'  => "Content-Type: application/json",
    'method'  => 'POST',
    'content' => json_encode($data),
  ],
];

$context = stream_context_create($options);
$response = file_get_contents('http://127.0.0.1:5000/generate-plan', false, $context);


echo "<h3>AI Plan Generation Result:</h3>";

if ($response === FALSE) {
  echo "<strong style='color:red;'>API error.</strong>";
} else {
  echo "<pre style='background:#f0f0f0;padding:15px;border-radius:6px;'>";
  print_r(json_decode($response, true));
  echo "</pre>";
}
?>
