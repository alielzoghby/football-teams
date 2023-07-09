<?php
session_start();

$isAuthenticated = isset($_SESSION['name']);

if (!$isAuthenticated) {
    header('Location: login.php');
    exit();
}

// Database connection example using PDO
require_once "db.php";

// Initialize error variable
$error = "";

// Process the form submission and add the new team to the database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teamName = $_POST['team_name'];
    $skillLevel = $_POST['skill_level'];
    $gameDay = $_POST['game_day'];
    
    if (empty($teamName) || empty($skillLevel) || empty($gameDay)) {
        $error = "Please fill in all fields.";

    }else if ($skillLevel < 1 || $skillLevel > 5) {
        $error = "Skill level must be between 1 and 5.";

    } else {
        $stmt = $conn->prepare("INSERT INTO teams (team_name, skill_level, game_day) VALUES (?, ?, ?)");
        $stmt->execute([$teamName, $skillLevel, $gameDay]);

        header('Location: dashboard.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create New Team</title>
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
        
        label {
            display: block;
            margin-bottom: 10px;
            text-align: left;
            font-weight: bold;
        }
        
        input[type="text"],
        select {
            padding: 10px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            margin-bottom: 15px;
        }
        
        select {
            appearance: none;
            -webkit-appearance: none;
            padding-right: 30px;
            background-image: url('dropdown-arrow.png');
            background-position: right 10px center;
            background-repeat: no-repeat;
        }
        button[type="submit"]{
            display: block;
            width:25%;
            margin-bottom:10px;

        }
        button[type="submit"],
        a.button {
            display: inline-block;
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .error {
            color: red;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Create New Team</h1>
    <a href="dashboard.php" class="button">Dashboard</a>

    <div style="color:red;">
        <?php
        if (!empty($error)) {
            echo "<p>Error: $error</p>";
        }
        ?>
    </div>
    <form action="" method="POST">
        <label for="team_name">Team Name:</label>
        <input type="text" id="team_name" name="team_name" required>
        
        <label for="skill_level">Skill Level:</label>
        <input type="text" id="skill_level" name="skill_level" required>
        
        <label for="game_day">Game Day:</label>
        <select id="game_day" name="game_day" required>
            <option value="">Select a day</option>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
            <option value="Saturday">Saturday</option>
            <option value="Sunday">Sunday</option>
        </select>
        
        <button type="submit">Create Team</button>
    </form>
    
</body>
</html>

