<?php
require_once("public/connection.php");
require_once("public/functions.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentid = $_POST['reg_number'];

    if (!empty($studentid)) {
        $query = "SELECT firstname, othername, lastname, reg_number, class FROM students WHERE reg_number = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("s", $studentid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $student = $result->fetch_assoc();
            $fullname = $student['firstname'] . ' ' . $student['othername'] . ' ' . $student['lastname'];

            echo json_encode([
                "status" => "success",
                "fullname" => $fullname,
                "result_regno" => $student['reg_number'],
                "class" => $student['class'],                
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "No student found with this Admission Number."
            ]);
        }
    } else {
        echo json_encode([
            "status" => "warning",
            "message" => "Please provide a Admission Number."
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method."
    ]);
}
?>
