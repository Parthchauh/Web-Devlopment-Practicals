<?php
require 'db.php';

$msg = '';

// ---------- Handle Insert ----------
if (isset($_POST['submit_student'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $year = intval($_POST['year'] ?? 0);

    if ($name && $email && $course && $year) {
        $stmt = $pdo->prepare("INSERT INTO students (name, email, course, year) VALUES (?, ?, ?, ?)");
        try {
            $stmt->execute([$name, $email, $course, $year]);
            $msg = "Student added successfully!";
        } catch (PDOException $e) {
            $msg = "Error: " . $e->getMessage();
        }
    } else {
        $msg = "Please fill all fields!";
    }
}

// ---------- Handle Delete ----------
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $stmt = $pdo->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->execute([$id]);
    $msg = "Student deleted successfully!";
}

// ---------- Handle Search ----------
$search = trim($_GET['search'] ?? '');
if ($search) {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE name LIKE ?");
    $stmt->execute(["%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM students ORDER BY student_id DESC");
}
$students = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>StudentHub Portal - MySQL</title>
<style>
body { font-family: Arial; background:#f4f4f4; margin:0; padding:0; }
.container { max-width:800px; margin:2rem auto; padding:1rem; background:#fff; border-radius:10px; }
h2 { text-align:center; }
form label { display:block; margin:0.5rem 0 0.2rem; }
form input, form button { width:100%; padding:0.7rem; margin-bottom:1rem; border:1px solid #ccc; border-radius:5px; }
form button { background:#0077cc; color:#fff; cursor:pointer; }
form button:hover { background:#005999; }
.msg { margin:1rem 0; padding:0.5rem; background:#dff0d8; border-radius:5px; color:#3c763d; }
table { width:100%; border-collapse:collapse; margin-top:1rem; }
th, td { border:1px solid #ccc; padding:0.5rem; text-align:left; }
th { background:#0077cc; color:#fff; }
a.delete { color:red; text-decoration:none; }
a.delete:hover { text-decoration:underline; }
input.search { width:70%; display:inline-block; padding:0.5rem; }
button.search-btn { width:auto; padding:0.5rem 1rem; }
</style>
</head>
<body>

<div class="container">
<h2>StudentHub - MySQL CRUD</h2>

<?php if($msg) echo "<div class='msg'>$msg</div>"; ?>

<!-- Insert Form -->
<h3>Register Student</h3>
<form method="POST">
    <input type="hidden" name="submit_student" value="1">
    <label>Name:</label>
    <input type="text" name="name" required>
    <label>Email:</label>
    <input type="email" name="email" required>
    <label>Course:</label>
    <input type="text" name="course" required>
    <label>Year:</label>
    <input type="number" name="year" min="1" max="5" required>
    <button type="submit">Add Student</button>
</form>

<!-- Search -->
<h3>Search Students</h3>
<form method="GET">
    <input type="text" name="search" placeholder="Search by name" class="search" value="<?= htmlspecialchars($search) ?>">
    <button type="submit" class="search-btn">Search</button>
</form>

<!-- Display Students -->
<h3>Registered Students</h3>
<table>
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Course</th><th>Year</th><th>Action</th></tr>
    <?php foreach($students as $s): ?>
    <tr>
        <td><?= $s['student_id'] ?></td>
        <td><?= htmlspecialchars($s['name']) ?></td>
        <td><?= htmlspecialchars($s['email']) ?></td>
        <td><?= htmlspecialchars($s['course']) ?></td>
        <td><?= $s['year'] ?></td>
        <td><a class="delete" href="?delete_id=<?= $s['student_id'] ?>" onclick="return confirm('Delete this student?')">Delete</a></td>
    </tr>
    <?php endforeach; ?>
</table>

</div>

</body>
</html>
