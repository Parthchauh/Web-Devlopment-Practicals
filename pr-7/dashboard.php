<?php
session_start();

// Check session or cookie
if (isset($_SESSION['user'])) {
    $user_name = $_SESSION['user'];
} elseif (isset($_COOKIE['user'])) {
    $user_name = $_COOKIE['user'];
    $_SESSION['user'] = $user_name; // restore session from cookie
} else {
    header("Location: login.html");
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
  body { font-family: Arial, sans-serif; background: #f4f4f4; margin:0; padding:0; }
  .container { max-width: 600px; margin: 5rem auto; background:#fff; padding:2rem; border-radius:10px; text-align:center; box-shadow:0 2px 8px rgba(0,0,0,0.1);}
  a { display:inline-block; margin-top:1rem; padding:0.5rem 1rem; background:#0077cc; color:#fff; border-radius:5px; text-decoration:none;}
  a:hover { background:#005999; }
</style>
</head>
<body>

<div class="container">
  <h2>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h2>
  <p>You have successfully logged in to StudentHub.</p>
  <a href="logout.php">Logout</a>
</div>

</body>
</html>
