<?php
// =========================================
// StudentHub Registration Storage
// Author: Parth Chauhan (ID: D25CE149)
// =========================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form values safely
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']); // For demo only; ideally hash it
    $course = htmlspecialchars($_POST['course']);

    // Prepare data line
    $line = "Name: $name | Email: $email | Password: $password | Course: $course\n";

    // File to store registrations
    $file = "registrations.txt";

    // Append data to file
    if(file_put_contents($file, $line, FILE_APPEND | LOCK_EX)) {
        echo "<h2>Registration Successful!</h2>";
        echo "<p>Thank you, $name. Your registration for $course is recorded.</p>";
        echo "<p><a href='register.html'>Back to Registration</a></p>";
    } else {
        echo "<h2>Error!</h2><p>Could not save registration. Please try again.</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>
