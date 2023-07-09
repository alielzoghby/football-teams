<?php
session_start();

$isAuthenticated = isset($_SESSION['name']);

if ($isAuthenticated) {
  header('Location: dashboard.php');
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="style/welcomePage.css">
</head>
<body>
  <div class="container">
    <h1>Welcome!</h1>
    <div>  
      <button><a href="login.php">Login</a></button>
      <button><a href="registration.php">Register</a></button>
    </div>
  </div>
</body>
</html>