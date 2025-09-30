<?php
require 'db.php';

$msg = '';

// ---------- Add / Update Event ----------
if (isset($_POST['submit_event'])) {
    $id = intval($_POST['event_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $date = $_POST['event_date'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $status = $_POST['status'] ?? 'open';

    if ($title && $date && $location) {
        if ($id > 0) {
            // Update
            $stmt = $pdo->prepare("UPDATE events SET title=?, event_date=?, location=?, status=? WHERE event_id=?");
            $stmt->execute([$title, $date, $location, $status, $id]);
            $msg = "Event updated successfully!";
        } else {
            // Insert
            $stmt = $pdo->prepare("INSERT INTO events (title, event_date, location, status) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $date, $location, $status]);
            $msg = "Event added successfully!";
        }
    } else {
        $msg = "Please fill all fields!";
    }
}

// ---------- Delete Event ----------
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $stmt = $pdo->prepare("DELETE FROM events WHERE event_id=?");
    $stmt->execute([$id]);
    $msg = "Event deleted successfully!";
}

// ---------- Edit Event (Load for update) ----------
$edit_event = null;
if (isset($_GET['edit_id'])) {
    $id = intval($_GET['edit_id']);
    $stmt = $pdo->prepare("SELECT * FROM events WHERE event_id=?");
    $stmt->execute([$id]);
    $edit_event = $stmt->fetch();
}

// ---------- Fetch All Events ----------
$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date DESC");
$events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>StudentHub - Events Management</title>
<style>
body { font-family: Arial; background:#f4f4f4; margin:0; padding:0; }
.container { max-width:900px; margin:2rem auto; padding:1rem; background:#fff; border-radius:10px; }
h2,h3 { text-align:center; }
form label { display:block; margin:0.5rem 0 0.2rem; }
form input, form select, form button { width:100%; padding:0.7rem; margin-bottom:1rem; border:1px solid #ccc; border-radius:5px; }
form button { background:#0077cc; color:#fff; cursor:pointer; }
form button:hover { background:#005999; }
.msg { margin:1rem 0; padding:0.5rem; background:#dff0d8; border-radius:5px; color:#3c763d; }
table { width:100%; border-collapse:collapse; margin-top:1rem; }
th, td { border:1px solid #ccc; padding:0.5rem; text-align:left; }
th { background:#0077cc; color:#fff; }
a.edit { color:green; text-decoration:none; }
a.edit:hover { text-decoration:underline; }
a.delete { color:red; text-decoration:none; }
a.delete:hover { text-decoration:underline; }
</style>
</head>
<body>

<div class="container">
<h2>Events Management - StudentHub</h2>
<?php if($msg) echo "<div class='msg'>$msg</div>"; ?>

<!-- Event Form -->
<h3><?= $edit_event ? 'Edit Event' : 'Add New Event' ?></h3>
<form method="POST">
    <input type="hidden" name="event_id" value="<?= $edit_event['event_id'] ?? 0 ?>">
    <label>Title:</label>
    <input type="text" name="title" value="<?= htmlspecialchars($edit_event['title'] ?? '') ?>" required>
    <label>Date:</label>
    <input type="date" name="event_date" value="<?= $edit_event['event_date'] ?? '' ?>" required>
    <label>Location:</label>
    <input type="text" name="location" value="<?= htmlspecialchars($edit_event['location'] ?? '') ?>" required>
    <label>Status:</label>
    <select name="status">
        <option value="open" <?= (isset($edit_event['status']) && $edit_event['status']=='open') ? 'selected' : '' ?>>Open</option>
        <option value="closed" <?= (isset($edit_event['status']) && $edit_event['status']=='closed') ? 'selected' : '' ?>>Closed</option>
    </select>
    <button type="submit" name="submit_event"><?= $edit_event ? 'Update Event' : 'Add Event' ?></button>
</form>

<!-- Display Events -->
<h3>All Events</h3>
<table>
<tr>
<th>ID</th><th>Title</th><th>Date</th><th>Location</th><th>Status</th><th>Actions</th>
</tr>
<?php foreach($events as $e): ?>
<tr>
    <td><?= $e['event_id'] ?></td>
    <td><?= htmlspecialchars($e['title']) ?></td>
    <td><?= $e['event_date'] ?></td>
    <td><?= htmlspecialchars($e['location']) ?></td>
    <td><?= $e['status'] ?></td>
    <td>
        <a class="edit" href="?edit_id=<?= $e['event_id'] ?>">Edit</a> |
        <a class="delete" href="?delete_id=<?= $e['event_id'] ?>" onclick="return confirm('Delete this event?')">Delete</a>
    </td>
</tr>
<?php endforeach; ?>
</table>

</div>
</body>
</html>
