<?php
session_start();

$isAuthenticated = isset($_SESSION['name']);

if (!$isAuthenticated) {
    header('Location: login.php');
    exit();
}

// Database connection example using PDO
require_once "db.php";


// Retrieve teams from the database
$stmt = $conn->prepare("SELECT * FROM teams"); $stmt->execute(); $teams =
$stmt->fetchAll(PDO::FETCH_ASSOC); 
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Dashboard</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        background-color: #f5f5f5;
      }

      h1 {
        color: #333;
        /* text-align: center; */
      }

      p {
        margin-top: 10px;
        /* text-align: center; */
      }

      table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
      }

      th,
      td {
        padding: 8px;
        border: 1px solid #ccc;
        text-align: center;
      }

      th {
        background-color: #f2f2f2;
      }

      tr:nth-child(even) {
        background-color: #f9f9f9;
      }

      tr:hover {
        background-color: #e6e6e6;
      }

      a {
        text-decoration: none;
        color: #333;
      }

      .create-team-link {
        display: block;
        text-align: center;
        margin-top: 10px;
        padding: 10px 10px;
        color:white;
        background-color: #00B89F;
        border-radius: 2rem;
      }

      .logout-link {
        margin-top: 20px;
        background-color: #dc3545;
        color: #fff;
        padding: 5px 10px;
        border-radius: 5px;
      }
    </style>
  </head>
  <body>
    <h1>Dashboard</h1>
    <p>
      Welcome,
      <?php echo $_SESSION['name']; ?>
    </p>
    <!-- Logout link -->
    <a href="logout.php" class="logout-link">Logout</a>

    <!-- Display the table of teams -->
    <table>
      <thead>
        <tr>
          <th>Team Name</th>
          <th>Skill Level</th>
          <th>Players</th>
          <th>Game Day</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($teams as $team) { ?>
        <tr>
          <td>
            <a href="team_details.php?team_id=<?php echo $team['id']; ?>"
              ><?php echo $team['team_name']; ?></a
            >
          </td>
          <td><?php echo $team['skill_level']; ?></td>
          <td>
            <?php
            $stmt = $conn->prepare("SELECT COUNT(*) FROM players WHERE team_id = ?");
            $stmt->execute([$team['id']]); $playerCount =
            $stmt->fetchColumn(); echo $playerCount; ?> / 9
          </td>
          <td><?php echo $team['game_day']; ?></td>
          <td>
            <a
              href="add_player.php?team_id=<?php echo $team['id']; ?>"
              style="
                background-color: #007bff;
                color: #fff;
                padding: 5px 10px;
                border-radius: 5px;
              "
              >Add Player</a
            >
            <a
              href="edit_team.php?team_id=<?php echo $team['id']; ?>"
              style="
                background-color: #6c757d;
                color: #fff;
                padding: 5px 10px;
                border-radius: 5px;
              "
              >Edit Team</a
            >
            <a
              href="delete_team.php?team_id=<?php echo $team['id']; ?>"
              style="
                background-color: #dc3545;
                color: #fff;
                padding: 5px 10px;
                border-radius: 5px;
              "
              >Delete Team</a
            >
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>

    <a href="./create_team.php" class="create-team-link">Create Team</a>
  </body>
</html>
