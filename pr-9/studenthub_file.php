<?php
/* ===========================================
   StudentHub Portal - Text File Submission
   Author: Parth Chauhan (ID: D25CE149)
   =========================================== */

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $name   = trim($_POST['name'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $year   = trim($_POST['year'] ?? '');

    // Simple validation
    if ($name && $email && $course && $year) {
        // Prepare line to write
        $line = "$name | $email | $course | $year" . PHP_EOL;

        // Append to students.txt
        $file = 'students.txt';
        if (file_put_contents($file, $line, FILE_APPEND | LOCK_EX)) {
            $msg = "Student data submitted successfully!";
        } else {
            $msg = "Error writing to file.";
        }
    } else {
        $msg = "Please fill all fields!";
    }
}

// Read existing data
$students = [];
if (file_exists('students.txt')) {
    $students = file('students.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>StudentHub - File Submission</title>
<style>
body { font-family: Arial, sans-serif; background:#f4f4f4; color:#222; margin:0; padding:0; }
header { background:#0077cc; color:#fff; padding:1rem; text-align:center; }
.container { max-width:600px; margin:2rem auto; padding:1rem; background:#fff; border-radius:10px; }
form label { display:block; margin:0.5rem 0 0.2rem; }
form input, form button { width:100%; padding:0.7rem; margin-bottom:1rem; border:1px solid #ccc; border-radius:5px; }
form button { background:#0077cc; color:#fff; cursor:pointer; }
form button:hover { background:#005999; }
.msg { margin:1rem 0; padding:0.5rem; background:#dff0d8; border-radius:5px; color:#3c763d; }
table { width:100%; border-collapse:collapse; margin-top:1rem; }
th, td { border:1px solid #ccc; padding:0.5rem; text-align:left; }
th { background:#0077cc; color:#fff; }
</style>
</head>
<body>

<header>
<h1>StudentHub - Submit Data to File</h1>
</header>

<div class="container">

<?php if($msg) echo "<div class='msg'>$msg</div>"; ?>

<h2>Register Student</h2>
<form method="POST">
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

<h2>Registered Students</h2>
<table>
<tr><th>Name</th><th>Email</th><th>Course</th><th>Year</th></tr>
<?php
foreach($students as $s) {
    $parts = explode('|', $s);
    echo "<tr>";
    foreach($parts as $p) echo "<td>" . htmlspecialchars(trim($p)) . "</td>";
    echo "</tr>";
}
?>
</table>

</div>
</body>
</html>
