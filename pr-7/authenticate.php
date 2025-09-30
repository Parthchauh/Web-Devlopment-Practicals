<?php
session_start();
include 'users.php'; // Load users array

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    $authenticated = false;
    $user_name = "";

    // Check credentials
    foreach ($users as $user) {
        if ($user['email'] === $email && $user['password'] === $password) {
            $authenticated = true;
            $user_name = $user['name'];
            break;
        }
    }

    if ($authenticated) {
        // Set session
        $_SESSION['user'] = $user_name;

        // Set cookie if "Remember Me" checked (expires in 7 days)
        if ($remember) {
            setcookie('user', $user_name, time() + (7 * 24 * 60 * 60), "/");
        }

        header("Location: dashboard.php");
        exit;
    } else {
        echo "<p>Invalid email or password. <a href='login.html'>Try Again</a></p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>
