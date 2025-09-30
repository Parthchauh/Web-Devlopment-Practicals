<?php
require 'db.php';
require 'student_actions.php'; // Handles POST requests

// Fetch students
try {
    $stmt = $pdo->query("SELECT * FROM students ORDER BY student_id");
    $students = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error fetching students: " . htmlspecialchars($e->getMessage());
    $students = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>StudentHub Portal</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>StudentHub Portal</h1>
</header>

<div class="container">

<?php if(!empty($msg)) echo "<p>$msg</p>"; ?>

<h2>Register / Update Student</h2>
<form method="POST">
    <input type="hidden" name="student_id" value="<?= $_GET['edit'] ?? '' ?>">
    <label>Name:</label>
    <input type="text" name="name" required>
    <label>Email:</label>
    <input type="email" name="email" required>
    <label>Course:</label>
    <input type="text" name="course" required>
    <label>Year:</label>
    <input type="number" name="year" min="1" max="5" required>
    <button type="submit">Submit</button>
</form>

<h2>Student List</h2>
<table border="1" cellpadding="5" cellspacing="0">
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Course</th><th>Year</th><th>Action</th></tr>
    <?php foreach($students as $s): ?>
        <tr>
            <td><?= htmlspecialchars($s['student_id']) ?></td>
            <td><?= htmlspecialchars($s['name']) ?></td>
            <td><?= htmlspecialchars($s['email']) ?></td>
            <td><?= htmlspecialchars($s['course']) ?></td>
            <td><?= htmlspecialchars($s['year']) ?></td>
            <td><a href="?edit=<?= $s['student_id'] ?>">Edit</a></td>
        </tr>
    <?php endforeach; ?>
</table>

</div>
</body>
</html>
