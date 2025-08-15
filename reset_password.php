<?php
session_start();
require_once("public/connection.php");

// Fetch school info (copy from your existing code)
$school_name = " ";
$school_logo = " ";
$school_favicon = " ";

$stmt = $conn->query("SELECT * FROM administratives LIMIT 1");
if ($stmt && $stmt->num_rows > 0) {
    $adminData = $stmt->fetch_assoc();
    $school_name = $adminData['school_name'];
    $school_logo = $adminData['school_logo'];
    $school_favicon = $adminData['school_favicon'];
}

$token = isset($_GET['token']) ? $_GET['token'] : '';
$error = '';
$success = '';

// Handle token verification and form submission
if ($token) {
    // Find the reset token
    $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = ? AND expiry > NOW() LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $reset = $result->fetch_assoc();

    if (!$reset) {
        $error = "Invalid or expired reset link.";
    } else {
        // Handle password reset submission
        if (isset($_POST['reset_password_btn'])) {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if ($new_password !== $confirm_password) {
                $error = "Passwords do not match.";
            } elseif (strlen($new_password) < 6) {
                $error = "Password must be at least 6 characters.";
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update password based on user_type
                if ($reset['user_type'] === 'staff') {
                    $stmt = $conn->prepare("UPDATE staff SET password = ? WHERE id = ?");
                } else {
                    $stmt = $conn->prepare("UPDATE students SET gen_password = ? WHERE id = ?");
                }
                $stmt->bind_param("si", $hashed_password, $reset['user_id']);
                $stmt->execute();

                // Delete the used token
                $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
                $stmt->bind_param("s", $token);
                $stmt->execute();

                $success = "Password reset successfully. <a href='index.php'>Login here</a>.";
            }
        }
    }
} else {
    $error = "No reset token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="assets/SweetAlerts/sweetalert.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/loader/waitMe.css">
    <link rel="icon" href="../<?php echo $school_favicon; ?>" type="image/png">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
           display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }

        .login-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 20px;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .login-box:hover {
            transform: translateY(-5px);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            max-width: 120px;
            height: auto;
            border-radius: 10px;
        }

        .login-head {
            font-size: 1.8rem;
            font-weight: 600;
            color: #1e3a8a;
            text-align: center;
            margin-bottom: 10px;
        }

        .text-center {
            font-size: 1rem;
            color: #4b5e8e;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 500;
            color: #1e3a8a;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #4e73df;
            box-shadow: 0 0 5px rgba(78, 115, 223, 0.3);
        }

        .links-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .links-container a {
            color: #4e73df;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .links-container a:hover {
            color: #1e3a8a;
            text-decoration: underline;
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            background: #4e73df;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .btn-primary:hover {
            background: #1e3a8a;
            transform: translateY(-2px);
        }

        .btn-primary i {
            margin-right: 8px;
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 0.85rem;
            color: #666;
        }
        .footer-text a {
            color: #007bff;
            text-decoration: none;
        }
        .footer-text a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .login-box {
                padding: 20px;
                max-width: 90%;
            }

            .login-head {
                font-size: 1.5rem;
            }

            .form-control {
                padding: 10px;
            }

            .btn-primary {
                padding: 10px;
            }
        }

        /* Added for flip functionality */
        .login-form, .forget-form {
            transition: all 0.5s ease;
        }
        .forget-form {
            display: none;
        }
        .login-box.flipped .login-form {
            display: none;
        }
        .login-box.flipped .forget-form {
            display: block;
        }
    </style>
    <title><?php echo htmlspecialchars($school_name); ?> - Reset Password</title>
</head>
<body>
    <section class="login-content">
        <div class="login-box">
            <form action="" method="POST" autocomplete="off">
                <div class="logo-container">
                    <?php if (!empty($school_logo)) : ?>
                        <img src="../<?php echo $school_logo; ?>" alt="School Logo">
                    <?php endif; ?>
                </div>
                <h4 class="login-head"><?php echo htmlspecialchars($school_name); ?></h4>
                <h5 class="text-center">Reset Password</h5>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php else: ?>
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input class="form-control" type="password" placeholder="Enter new password" required name="new_password">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input class="form-control" type="password" placeholder="Confirm new password" required name="confirm_password">
                    </div>
                    <button type="submit" name="reset_password_btn" class="btn btn-primary">
                        <i class="fas fa-lock me-2"></i>Reset Password
                    </button>
                <?php endif; ?>
            </form>
        </div>
        <div class="footer-text">
            Developed by <a href="https://wa.me/2349061643031">Elite Tech Solutions</a><br>09061643031
        </div>
    </section>
    
    <!-- Scripts -->
    <script src="assets/js/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/login.js"></script>
    <script src="assets/loader/waitMe.js"></script>
    <script src="assets/js/forms.js"></script>
    <script src="assets/SweetAlerts/sweetalert.js"></script>
    <script>
        $('.login-content [data-toggle="flip"]').click(function() {
            $('.login-box').toggleClass('flipped');
            return false;
        });
    </script>
    <?php require_once("public/alert.php"); ?>
    <!-- Copy scripts from your login page -->
</body>
</html>