<?php
session_start();

if (isset($_REQUEST['reset'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
}


$isAuthenticated = isset($_SESSION['loginSuccessfully']);

if ($isAuthenticated) {
    header('Location: dashboard.php');
    exit();
}

$displayError = false;
if (count($_SESSION) > 0)
    $displayError = true;


$name = $_SESSION['name'] ?? "";
$email = $_SESSION['email'] ?? "";
$password = $_SESSION['password'] ?? "";
$confirmPassword = $_SESSION['confirmPassword'] ?? "";

$passwordError = $_SESSION['password_error'] ?? "";
$emailError = $_SESSION['email_error'] ?? "";
$dublecatEmail = $_SESSION['dublecat_Email'] ?? "";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
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

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .form-control.error {
            border-color: #dc3545;
        }

        .alert {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #dc3545;
            color: #fff;
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
        
    </style>
</head>

<body>
    <header>
        <h1>Registration</h1>
        <button class="btn"><a href="welcomePage.php" style="color: #fff; text-decoration: none;">Home</a></button>
    </header>
    <div class="container">
        <form  method="POST" enctype="multipart/form-data" action="registerController.php">
            <div class="form-row">
                <div class="form-group">
                    <label for="inputName">Name</label>
                    <input type="text" class="form-control <?= empty($name) && $displayError ? 'error' : '' ?>"
                        id="inputName" placeholder="Name" name="name" value="<?= $name ?>">
                </div>
                <div class="form-group">
                    <label for="inputEmail4">Email</label>
                    <input type="email"
                        class="form-control <?= (empty($email) || $emailError) && $displayError ? 'error' : '' ?>"
                        id="inputEmail4" placeholder="Email" name="email" value="<?= $email ?>">
                    <?php
                    if ($emailError && $displayError) {
                        echo "<div class='alert'>$emailError</div>";
                    }
                    ?>
                </div>
                <div class="form-group">
                    <label for="inputPassword4">Password</label>
                    <input type="password"
                        class="form-control <?= (empty($password) || $passwordError) && $displayError ? 'error' : '' ?>"
                        id="inputPassword4" placeholder="Password" name="password">
                </div>
                <div class="form-group">
                    <label for="inputConfirmPassword">Confirm Password</label>
                    <input type="password"
                        class="form-control <?= (empty($confirmPassword) || $passwordError) && $displayError ? 'error' : '' ?>"
                        id="inputConfirmPassword" placeholder="Confirm Password" name="confirmPassword">
                    <?php
                    if ($passwordError && $displayError) {
                        echo "<div class='alert'>$passwordError</div>";
                    }
                    ?>
                </div>
            </div>
            <?php
                    if ($dublecatEmail && $displayError) {
                        echo "<div class='alert'>$dublecatEmail</div>";
                    }
            ?>
            <button type="submit" class="btn" name="registration">Register</button>
        </form>
    </div>
</body>

</html>
