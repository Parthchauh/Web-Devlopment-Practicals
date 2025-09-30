<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$msg = '';

// ---------- Add / Update User ----------
if (isset($_POST['submit_user'])) {
    $id = intval($_POST['user_id'] ?? 0);
    $username = htmlspecialchars(trim($_POST['username'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if ($username && $email) {
        if ($password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($id > 0) {
            // Update
            if ($password) {
                $stmt = $pdo->prepare("UPDATE users SET username=?, email=?, password=? WHERE user_id=?");
                $stmt->execute([$username, $email, $hashed_password, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET username=?, email=? WHERE user_id=?");
                $stmt->execute([$username, $email, $id]);
            }
            $msg = "User updated successfully!";
        } else {
            // Insert
            $stmt = $pdo->prepare("INSERT INTO users (username,email,password) VALUES(?,?,?)");
            $stmt->execute([$username,$email,$hashed_password]);
            $msg = "User added successfully!";
        }
    } else {
        $msg = "Fill all fields!";
    }
}

// ---------- Delete User ----------
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $stmt = $pdo->prepare("DELETE FROM users WHERE user_id=?");
    $stmt->execute([$id]);
    $msg = "User deleted successfully!";
}

// ---------- Edit User ----------
$edit_user = null;
if (isset($_GET['edit_id'])) {
    $id = intval($_GET['edit_id']);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id=?");
    $stmt->execute([$id]);
    $edit_user = $stmt->fetch();
}

// ---------- Fetch All Users ----------
$users = $pdo->query("SELECT * FROM users ORDER BY username ASC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard - Users</title>
<style>
body { font-family: Arial; background:#f4f4f4; }
.container { max-width:800px; margin:2rem auto; padding:1rem; background:#fff; border-radius:10px; }
table { width:100%; border-collapse:collapse; margin-top:1rem; }
th, td { border:1px solid #ccc; padding:0.5rem; text-align:left; }
th { background:#0077cc; color:#fff; }
a.edit { color:green; } a.delete { color:red; }
form input, form button { padding:0.5rem; margin:0.3rem 0; width:100%; }
form button { background:#0077cc; color:#fff; border:none; cursor:pointer; }
.msg { color:green; }
</style>
</head>
<body>
<div class="container">
<h2>Admin Dashboard - Users</h2>
<p>Logged in as: <?= htmlspecialchars($_SESSION['username']) ?> | <a href="logout.php">Logout</a></p>

<?php if($msg) echo "<p class='msg'>$msg</p>"; ?>

<h3><?= $edit_user ? 'Edit User' : 'Add New User' ?></h3>
<form method="POST">
    <input type="hidden" name="user_id" value="<?= $edit_user['user_id'] ?? 0 ?>">
    <label>Username:</label>
    <input type="text" name="username" value="<?= $edit_user['username'] ?? '' ?>" required>
    <label>Email:</label>
    <input type="email" name="email" value="<?= $edit_user['email'] ?? '' ?>" required>
    <label>Password: <?= $edit_user ? '(leave blank to keep unchanged)' : '' ?></label>
    <input type="password" name="password">
    <button type="submit" name="submit_user"><?= $edit_user ? 'Update User' : 'Add User' ?></button>
</form>

<h3>All Users</h3>
<table>
<tr><th>ID</th><th>Username</th><th>Email</th><th>Actions</th></tr>
<?php foreach($users as $u): ?>
<tr>
<td><?= $u['user_id'] ?></td>
<td><?= htmlspecialchars($u['username']) ?></td>
<td><?= htmlspecialchars($u['email']) ?></td>
<td>
<a class="edit" href="?edit_id=<?= $u['user_id'] ?>">Edit</a> |
<a class="delete" href="?delete_id=<?= $u['user_id'] ?>" onclick="return confirm('Delete user?')">Delete</a>
</td>
</tr>
<?php endforeach; ?>
</table>
</div>
</body>
</html>
