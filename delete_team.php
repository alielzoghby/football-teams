<?php
session_start();

$isAuthenticated = isset($_SESSION['name']);

if (!$isAuthenticated) {
    header('Location: login.php');
    exit();
}

// Database connection example using PDO
require_once "db.php";


if (!isset($_GET['team_id'])) {
    exit();
}

$teamId = $_GET['team_id'];

// Retrieve the team details from the database
$stmt = $conn->prepare("SELECT * FROM teams WHERE id = ?");
$stmt->execute([$teamId]);
$team = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$team) {
    exit();
}

// Process the delete operation and remove the team from the database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("DELETE FROM teams WHERE id = ?");
    $stmt->execute([$teamId]);
    
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Team</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        h1 {
            color: #333;
            text-align: center;
        }
        
        form {
            text-align: center;
            margin-top: 20px;
        }
        
        p {
            margin-bottom: 10px;
            text-align: center;
        }
        
        button[type="submit"],
        a.button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #dc3545;
            color: #fff;
            border: none;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        a.button {
            background-color: #007bff;
        }
    </style>
</head>
<body>
    <h1>Delete Team</h1>
    <form action="" method="POST">
        <p>Are you sure you want to delete this team?</p>
        <a href="team_details.php?team_id=<?php echo $teamId; ?>" class="button">Team Details</a>
        <button type="submit">Delete Team</button>
    </form>

</body>
</html>
