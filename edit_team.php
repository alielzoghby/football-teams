<?php
session_start();

$isAuthenticated = isset($_SESSION['name']);

if (!$isAuthenticated) {
    header('Location: login.php');
    exit();
}

// Database connection example using PDO
require_once "db.php";


// Retrieve the team ID from the query string parameter
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

$error = "";

// Process the form submission and update the team information in the database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teamName = $_POST['team_name'];
    $skillLevel = $_POST['skill_level'];
    $gameDay = $_POST['game_day'];
    
    if (empty($teamName) || empty($skillLevel) || empty($gameDay)) {
        $error = "Please fill in all fields.";
    } else if ($skillLevel < 1 || $skillLevel > 5) {
        $error = "Skill level must be between 1 and 5.";

    }else {
        $stmt = $conn->prepare("UPDATE teams SET team_name = ?, skill_level = ?, game_day = ? WHERE id = ?");
        $stmt->execute([$teamName, $skillLevel, $gameDay, $teamId]);

        header("Location: dashboard.php");

        // header("Location: team_details.php?team_id=$teamId");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Team</title>
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
            width:100%;
            margin-bottom:10px;

        }
        button[type="submit"],
        a.button {
            display: inline-block;
            padding: 10px 20px;
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
    <h1>Edit Team</h1>
    <div style="color:red;">
        <?php
        if (!empty($error)) {
            echo "<p class='error'>Error: $error</p>";
        }
        ?>
    </div>
    <!-- Create a form with pre-filled values for team information -->
    <form action="" method="POST">
        <label for="team_name">Team Name:</label>
        <input type="text" id="team_name" name="team_name" value="<?php echo $team['team_name']; ?>" required>
        
        <label for="skill_level">Skill Level:</label>
        <input type="text" id="skill_level" name="skill_level" value="<?php echo $team['skill_level']; ?>" required>
        
        <label for="game_day">Game Day:</label>
        <select id="game_day" name="game_day" required>
            <option value="">Select a day</option>
            <option value="Monday" <?php if ($team['game_day'] === 'Monday') echo 'selected'; ?>>Monday</option>
            <option value="Tuesday" <?php if ($team['game_day'] === 'Tuesday') echo 'selected'; ?>>Tuesday</option>
            <option value="Wednesday" <?php if ($team['game_day'] === 'Wednesday') echo 'selected'; ?>>Wednesday</option>
            <option value="Thursday" <?php if ($team['game_day'] === 'Thursday') echo 'selected'; ?>>Thursday</option>
            <option value="Friday" <?php if ($team['game_day'] === 'Friday') echo 'selected'; ?>>Friday</option>
            <option value="Saturday" <?php if ($team['game_day'] === 'Saturday') echo 'selected'; ?>>Saturday</option>
            <option value="Sunday" <?php if ($team['game_day'] === 'Sunday') echo 'selected'; ?>>Sunday</option>
        </select>
        
        <button type="submit">Update Team</button>
    </form>
    
    <a href="team_details.php?team_id=<?php echo $teamId; ?>" class="button">Team Details</a>
    <a href="delete_team.php?team_id=<?php echo $teamId; ?>" class="button">Delete Team</a>
</body>
</html>
