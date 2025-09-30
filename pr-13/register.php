<?php
require 'db.php';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // ---------- Validation ----------
    if (!$username || !$email || !$password || !$confirm_password) {
        $msg = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Invalid email format!";
    } elseif ($password !== $confirm_password) {
        $msg = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $msg = "Password must be at least 6 characters!";
    } else {
        // ---------- Hash password ----------
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // ---------- Insert into DB ----------
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$username, $email, $hashed_password]);
            $msg = "Registration successful!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                $msg = "Username or email already exists!";
            } else {
                $msg = "Error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - StudentHub</title>
<style>
body { font-family: Arial; background:#f4f4f4; margin:0; padding:0; }
.container { max-width:400px; margin:5rem auto; padding:2rem; background:#fff; border-radius:10px; }
h2 { text-align:center; }
form label { display:block; margin:0.5rem 0 0.2rem; }
form input, form button { width:100%; padding:0.7rem; margin-bottom:1rem; border:1px solid #ccc; border-radius:5px; }
form button { background:#0077cc; color:#fff; cursor:pointer; }
form button:hover { background:#005999; }
.msg { background:#f8d7da; color:#721c24; padding:0.5rem; border-radius:5px; margin-bottom:1rem; }
.success { background:#dff0d8; color:#3c763d; }
</style>
</head>
<body>
<div class="container">
<h2>Register</h2>
<?php if($msg) echo "<div class='msg ".(strpos($msg,'successful')!==false?'success':'')."'>$msg</div>"; ?>
<form method="POST">
    <label>Username:</label>
    <input type="text" name="username" required>
    <label>Email:</label>
    <input type="email" name="email" required>
    <label>Password:</label>
    <input type="password" name="password" required>
    <label>Confirm Password:</label>
    <input type="password" name="confirm_password" required>
    <button type="submit">Register</button>
</form>
</div>
</body>
</html>
