<?php
require_once("public/auth.php");
require_once("public/school_config.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/SweetAlerts/sweetalert.css">
    <link rel="stylesheet" href="assets/loader/waitMe.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="icon" href="<?php echo $school_favicon; ?>" type="image/png">
    <title><?php echo htmlspecialchars($school_name); ?> - Login</title>
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
</head>
<body>
    <section class="login-content">
        <div class="login-box">
            <!-- Login Form -->
            <form id="loginForm" class="login-form app_frm" action="" method="POST" autocomplete="off">
                <div class="logo-container">
                    <?php if (!empty($school_logo)) : ?>
                        <img src="<?php echo $school_logo; ?>" alt="School Logo">
                    <?php endif; ?>
                </div>
                <h4 class="login-head"><?php echo htmlspecialchars($school_name); ?></h4>
                <div class="form-group">
                    <label class="form-label">Username/Reg No</label>
                    <input class="form-control" type="text" placeholder="Username or Registration Number" required name="login_id">
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input class="form-control" type="password" placeholder="Password" required name="login_password">
                </div>
                <!-- Role Quick-Select Buttons -->
                
                <div class="links-container">
                    <a href="cbt/" target="_blank">CBT Portal</a>
                    <a href="javascript:void(0);" data-toggle="flip">Forgot Password?</a>
                </div>
                <div class="btn-container">
                    <button type="submit" name="login_btn" class="btn btn-primary app_btn">
                        <i class="fas fa-sign-in-alt me-2"></i>Sign In
                    </button>
                </div>
            </form>
            <!-- Forgot Password Form -->
            <form class="forget-form app_frm" action="" method="POST" autocomplete="off">
                <div class="logo-container">
                    <?php if (!empty($school_logo)) : ?>
                        <img src="<?php echo $school_logo; ?>" alt="School Logo">
                    <?php endif; ?>
                </div>
                <h4 class="login-head"><?php echo htmlspecialchars($school_name); ?></h4>
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input class="form-control" type="text" placeholder="Email or Registration Number" required name="username">
                </div>
                <div class="btn-container">
                    <button type="submit" name="reset_btn" class="btn btn-primary app_btn">
                        <i class="fas fa-unlock me-2"></i>Reset Password
                    </button>
                </div>
                <div class="form-group">
                    <a href="javascript:void(0);" data-toggle="flip">
                        <i class="fas fa-chevron-left me-1"></i>Back to Login
                    </a>
                </div>
            </form>
        </div>
        <div class="footer-text">
            Developed by <a href="https://wa.me/2349061643031">Elite Tech Solutions</a><br>09061643031
        </div>
    </section>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/loader/waitMe.js"></script>
    <script src="assets/SweetAlerts/sweetalert.js"></script>
    <script src="assets/login_js/forms.js"></script>
    <script>
        $('.login-content [data-toggle="flip"]').click(function() {
            $('.login-box').toggleClass('flipped');
            return false;
        });

        
    </script>
    <?php
    require_once("public/alert.php");
    ?>
</body>
</html>
