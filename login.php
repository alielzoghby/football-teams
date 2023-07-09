<?php
session_start();

// if (isset($_REQUEST['reset'])) {
//     session_destroy();
//     header('Location: ' . $_SERVER['PHP_SELF']);
// }

$isAuthenticated = isset($_SESSION['name']);

if ($isAuthenticated) {
    header('Location: dashboard.php');
    exit();
}

$displayError = false;
if (count($_SESSION) > 0)
    $displayError = true;

$error = $_SESSION['error'] ?? "";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            padding: 20px;
            text-align: center;
        }

        h1 {
            color: #fff;
            margin: 0;
        }

        .container {
            max-width: 400px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            width: 300px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn {
            display: inline-block;
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 16px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #555;
        }

        .alert-danger {
            background-color: #dc3545;
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <header>
        <h1>Login</h1>
        <button class="btn"><a href="welcomePage.php" style="color: #fff; text-decoration: none;">Home</a></button>
    </header>
    <div class="container">
        <?php
        if ($displayError) {
            if ($error) echo "<div class='alert alert-danger' role='alert'>$error</div>";
        }
        ?>
        <form class="text-center" method="POST" action="registerController.php">
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" id="exampleInputEmail1" placeholder="Enter email" name="email">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" id="exampleInputPassword1" placeholder="Password" name="password">
            </div>
            <button type="submit" name="login" class="btn">Login</button>
        </form>
    </div>
</body>

</html>
