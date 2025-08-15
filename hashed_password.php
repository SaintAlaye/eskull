<?php
require_once('public/connection.php'); // Adjust path if needed
	ini_set('display_errors', 1);
error_reporting(E_ALL);

// 3. Hash Student Passwords
$student_query = "SELECT id, gen_password FROM students";
$student_result = mysqli_query($connection, $student_query);
while ($student = mysqli_fetch_assoc($student_result)) {
    $id = $student['id'];
    $plain_password = $student['gen_password'];
    $hashed = password_hash($plain_password, PASSWORD_DEFAULT);
    mysqli_query($connection, "UPDATE students SET gen_password = '$hashed' WHERE id = $id");
}

echo "All passwords have been hashed successfully.";
?>