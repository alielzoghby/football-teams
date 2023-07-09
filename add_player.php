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
// Check the number of players in the team
$stmt = $conn->prepare("SELECT COUNT(*) FROM players WHERE team_id = ?");
$stmt->execute([$teamId]);
$playerCount = $stmt->fetchColumn();

// Process the form submission and add the player to the team
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $playerName = $_POST['player_name'];
    
    if (empty($playerName)) {
        $error = "Please enter the player name.";
    } elseif ($playerCount >= 9) {
        $error = "Cannot add more players. Maximum limit reached.";
    } else {
        $stmt = $conn->prepare("INSERT INTO players (team_id, player_name) VALUES (?, ?)");
        $stmt->execute([$teamId, $playerName]);
        
        header("Location: team_details.php?team_id=$teamId");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Player</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        h1 {
            color: #333;
        }
        
        form {
            margin-top: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
        }
        
        input[type="text"] {
            padding: 5px;
            width: 200px;
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
    <h1>Add Player to <?php echo $team['team_name']; ?></h1>
    
    <?php if (!empty($error)) { ?>
        <div class="error"><?php echo $error; ?></div>
    <?php } ?>
    
    <form action="" method="POST">
        <label for="player_name">Player Name:</label>
        <input type="text" id="player_name" name="player_name" required>
        
        <button type="submit">Add Player</button>
    </form>
    
    <a href="team_details.php?team_id=<?php echo $teamId; ?>" class="button" style="margin-top:8px;">Team Details</a>
</body>
</html>
