<?php
require_once("public/connection.php");	
require_once("public/functions.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appnumber'])) {
    $application_number = mysqli_real_escape_string($connection, $_POST['appnumber']);
    $query_status = "SELECT `status` FROM `applications` WHERE `application_number` = '$application_number'";
    $result_status = mysqli_query($connection, $query_status);

    if (mysqli_num_rows($result_status) > 0) {
        $row = mysqli_fetch_assoc($result_status);
        $status = $row['status'];

        if ($status === 'APPROVED') {
            $msg = ['type' => 'success', 'text' => 'Your admission is APPROVED! Kindly reprint your Application Form.'];
        } elseif ($status === 'PENDING') {
            $msg = ['type' => 'info', 'text' => 'Your application status is still PENDING.'];
        } elseif ($status === 'REJECTED') {
            $msg = ['type' => 'error', 'text' => 'Your application has been REJECTED.'];
        }
    } else {
        $msg = ['type' => 'warning', 'text' => 'Application number not found. Please check and try again.'];
    }
} else {
    $msg = ['type' => 'error', 'text' => 'Invalid request.'];
}
?>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    icon: '<?= $msg['type'] ?>',
    title: 'Admission Status',
    text: '<?= $msg['text'] ?>',
    confirmButtonText: 'OK'
}).then(() => {
    window.location.href = 'index.php'; // Redirect after OK
});
</script>