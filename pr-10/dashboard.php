<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - StudentHub</title>
<style>
body { font-family: Arial; background:#f4f4f4; margin:0; padding:0; }
header { background:#0077cc; color:#fff; padding:1rem; text-align:center; }
.container { max-width:800px; margin:2rem auto; padding:1rem; background:#fff; border-radius:10px; }
a.logout { display:inline-block; margin-top:1rem; background:#0077cc; color:#fff; padding:0.5rem 1rem; border-radius:5px; text-decoration:none; }
a.logout:hover { background:#005999; }
</style>
</head>
<body>

<header>
<h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
</header>

<div class="container">
    <p>This is a secure dashboard. Only logged-in users can see this.</p>
    <a class="logout" href="logout.php">Logout</a>
</div>

</body>
</html>
