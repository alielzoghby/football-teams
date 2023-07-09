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

// Retrieve the selected team's information from the database
$stmt = $conn->prepare("SELECT * FROM teams WHERE id = ?");
$stmt->execute([$teamId]);
$team = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$team) {
    exit();
}

// Retrieve the players in the team from the database
$stmt = $conn->prepare("SELECT * FROM players WHERE team_id = ?");
$stmt->execute([$teamId]);
$players = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle player edit and delete operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_player'])) {
        $playerId = $_POST['player_id'];
        $playerName = $_POST['player_name'];

        if (empty($playerName)) {
            $error = "Please enter the player name.";
        } else {
            $stmt = $conn->prepare("UPDATE players SET player_name = ? WHERE id = ?");
            $stmt->execute([$playerName, $playerId]);

            header("Location: team_details.php?team_id=$teamId");
            exit();
        }
    }

    if (isset($_POST['delete_player'])) {
        $playerId = $_POST['player_id'];

        $stmt = $conn->prepare("DELETE FROM players WHERE id = ?");
        $stmt->execute([$playerId]);

        header("Location: team_details.php?team_id=$teamId");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Team Details</title>
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
        
        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        
        p {
            margin: 10px 0;
        }
        
        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: center;

        }
        
        th {
            background-color: #f2f2f2;
        
        }
        
        form {
            display: inline-block;
            margin-right: 10px;
        }
        
        input[type="text"] {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        
        button[type="submit"],.edit-button {
            padding: 5px 10px;
            background-color: #28a745;
        }
        
        .player-list {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        
        .player-list th:first-child,
        .player-list td:first-child {
            width: 5%;
        }
        
        .player-list th:nth-child(2),
        .player-list td:nth-child(2) {
            width: 55%;
        }
        
        .player-list th:last-child,
        .player-list td:last-child {
            white-space: nowrap;
        }
        
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
<h1>Team Details</h1>

<a href="dashboard.php">Dashboard</a>

<p>Team Name: <?php echo $team['team_name']; ?></p>
<p>Skill Level: <?php echo $team['skill_level']; ?></p>
<p>Game Day: <?php echo $team['game_day']; ?></p>

<h2>Players</h2>
<?php if (count($players) > 0) { ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Player Name</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($players as $index => $player) { ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td>
                        <span class="player-name"><?php echo $player['player_name']; ?></span>
                        
                    </td>
                    <td>
                        <form action="" method="POST">
                            <input  class="hidden" type="hidden" name="player_id" value="<?php echo $player['id']; ?>">
                            <input type="text" name="player_name" value="<?php echo $player['player_name']; ?>">
                            <button id="edit_player" type="submit" name="edit_player">Update</button>
                        </form>
                    </td>
                    <td>
                        <form action="" method="POST">
                            <input type="hidden" name="player_id" value="<?php echo $player['id']; ?>">
                            <button type="submit" name="delete_player">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    <p>No players in this team.</p>
<?php } ?>

<a href="add_player.php?team_id=<?php echo $teamId; ?>">Add Player</a>
<a href="edit_team.php?team_id=<?php echo $teamId; ?>">Edit Team</a>
<a href="delete_team.php?team_id=<?php echo $teamId; ?>">Delete Team</a>
<a href="add_player.php?team_id=<?php echo $teamId; ?>">Add Player</a>

</body>
</html>
