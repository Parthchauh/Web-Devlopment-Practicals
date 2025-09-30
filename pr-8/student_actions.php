<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['student_id'] ?? null;
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $course = $_POST['course'] ?? '';
    $year = $_POST['year'] ?? '';

    if ($name && $email && $course && $year) {
        try {
            if ($id) {
                // Update existing student
                $stmt = $pdo->prepare("UPDATE students SET name=?, email=?, course=?, year=? WHERE student_id=?");
                $stmt->execute([$name, $email, $course, $year, $id]);
                $msg = "Student updated successfully!";
            } else {
                // Insert new student
                $stmt = $pdo->prepare("INSERT INTO students (name, email, course, year) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $email, $course, $year]);
                $msg = "Student added successfully!";
            }
        } catch (PDOException $e) {
            $msg = "Error: " . htmlspecialchars($e->getMessage());
        }
    } else {
        $msg = "Please fill all fields!";
    }
}
?>
