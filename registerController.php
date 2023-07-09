<?php

session_start();

require_once "db.php";

// ---- Start Helper Functions ---- 
function EmailValidation($email) {
    $pattern = "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix";
    if (preg_match($pattern, $email)) {
        return true;
    }
    $_SESSION['email_error'] = "Invalid Email";
    return false;
}

function validatePassword($password, $confirmPassword) {
    if ($password == $confirmPassword) {
        if (strlen($password) >= 3) {
            // Password and confirm password match, and password length is at least 3
            return true;
        }
        $_SESSION['password_error'] = "Password must be at least 3 characters long.";
        return false;
    }
    $_SESSION['password_error'] = "Passwords don't match";
    return false;
}

// ---- End Helper Functions -----

if (isset($_REQUEST['registration'])) {
    unset($_SESSION['error']); // Clear the error message

    $name = $_REQUEST['name'] ?? "";
    $email = $_REQUEST['email'] ?? "";
    $password = $_REQUEST['password'] ?? "";
    $confirmPassword = $_REQUEST['confirmPassword'] ?? "";

    $missingData = false;
    foreach ($_REQUEST as $key => $value) {
        if (!empty($value)) {
            $_SESSION[$key] = $value;
        } else if (empty($value)) {
            $missingData = true;
        }
    }

    if (!$missingData || !emailValidation($email) || !validatePassword($password, $confirmPassword)) {
        $_SESSION['error'] = "Invalid data provided.";
        header("Location: registration.php");
        exit();
    }

    // Check if the email is already registered
    $query = $conn->prepare("SELECT COUNT(*) FROM students WHERE email = ?");
    $query->execute([$email]);
    $count = $query->fetchColumn();

    if ($count > 0) {
        $_SESSION['dublecat_Email'] = "Email is already registered.";
        header("Location: registration.php");
        exit();
    }

    if ($password) {
        $password = hash("sha256", $password);
    }

    $query = $conn->prepare("INSERT INTO students(name, email, password) VALUES (?, ?, ?)");
    $query->execute([$name, $email, $password]);

    session_destroy();
    header("Location: login.php");
    
}else if (isset($_REQUEST['login'])) {
    unset($_SESSION['error']); // Clear the error message

    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];
    $loginSuccessfully = false;

    $userDataKeys = ["name", "email"];


    $password = hash('sha256', $password);
    $query = $conn->prepare("select * from students where email=? and password=?");
    $query->execute([
        $email,
        $password
    ]);

    $data = $query->fetch(PDO::FETCH_ASSOC);

    //check lentgh in data messing error code 
    if ($data) {
        foreach ($data as $key => $value)
            if ($key != "password")
                $_SESSION[$key] = $value;
        $loginSuccessfully = true;
    }


    if ($loginSuccessfully) {
        header("Location: dashboard.php");
    } else {
        $_SESSION['error'] = "The email, or password you entered is incorrect. Please try again.";
        header("Location: login.php");
    }
} 