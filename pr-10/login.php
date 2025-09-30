<?php
session_start();

/* Sample Users for demo (all keys lowercase) */
$users = [
    'admin' => 'admin123',
    'parth' => 'parth2025'
];

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trim and normalize inputs
    $username = strtolower(trim($_POST['username'] ?? ''));
    $password = trim($_POST['password'] ?? '');

    // Check credentials
    if (isset($users[$username]) && $users[$username] === $password) {
        // Valid login
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        $msg = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - StudentHub</title>
<style>
body { font-family: Arial; background:#f4f4f4; margin:0; padding:0; }
.container { max-width:400px; margin:5rem auto; padding:2rem; background:#fff; border-radius:10px; }
h2 { text-align:center; }
form label { display:block; margin:0.5rem 0 0.2rem; }
form input, form button { width:100%; padding:0.7rem; margin-bottom:1rem; border:1px solid #ccc; border-radius:5px; }
form button { background:#0077cc; color:#fff; cursor:pointer; }
form button:hover { background:#005999; }
.msg { background:#f8d7da; color:#721c24; padding:0.5rem; border-radius:5px; margin-bottom:1rem; }
</style>
</head>
<body>

<div class="container">
<h2>StudentHub Login</h2>
<?php if($msg) echo "<div class='msg'>$msg</div>"; ?>
<form method="POST">
    <label>Username:</label>
    <input type="text" name="username" required>
    <label>Password:</label>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>
</div>

</body>
</html>
