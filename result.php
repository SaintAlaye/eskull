<?php
require_once("public/connection.php");
require_once("public/functions.php");
require_once("check_result_model.php");
?>
<?php
	include_once("sweet_alert.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONLINE RESULT CHECKER</title>
    <link rel="shortcut icon" type="image/x-icon" href="images/adams.jpg" />
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2d3748;
            color: white;
            padding: 25px 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        header h1 {
            margin: 0;
            font-size: 28px;
        }

        header p {
            font-size: 15px;
            color: #d1d5db;
            margin-top: 5px;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 25px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
        }

        .instructions {
            background-color: #f0f8ff;
            padding: 15px;
            border-left: 5px solid #4CAF50;
            margin-bottom: 25px;
            border-radius: 5px;
            color: #333;
        }
       
        .instructions ol {
            margin: 10px 0 0 20px;
            padding-left: 20px;
        }
        
        .instructions ol li {
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .card h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        
        .input-group {
            display: flex;
            flex-direction: column;
            width: 100%;
            box-sizing: border-box;
        }
        
        .input-group input,
        .input-group select {
            box-sizing: border-box;
            font-size: 16px;
        }
        
        form {
            display: flex;
            flex-direction: column;
            gap: 15px; /* consistent spacing between groups */
        }


        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .input-group input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .input-group input[type="submit"]:hover {
            background-color: #45a049;
        }
        
        .input-group input[type="submit"] {
            width: 100%;
        }

        .alert {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        footer {
            background-color: #2d3748;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    
<!-- Header -->
<header>
    <div style="display: flex; align-items: center; justify-content: center; gap: 15px;">
        <img src="images/adams.jpg" alt="Adams Logo" style="height: 50px;" />
        <div>
            <h1 style="margin: 0;">Online Result Checker</h1>
            <p style="margin: 5px 0; font-size: 15px; color: #d1d5db;">
                Quickly access your term results using your details and result card pin
            </p>
        </div>
    </div>
</header>


<!-- Main Container -->
<div class="container">
    <div class="instructions">
        <p><strong>Instructions:</strong></p>
        <ol>
            <li>Please enter your Admission Number correctly <strong>(e.g ASC/20/001).</strong></li>
            <li>Your name and class will be automatically filled if your admission number is valid.</li>
            <li>Select the correct <strong>Term</strong> on the pin slip.</li>
            <li>Input the result pin to check your result.</li>
            <li>You are allowed a maximum of <strong>5 uses per result pin</strong>.</li>
            <li>If your result is unavailable, please contact the school administrator.</li>
        </ol>
    </div>


    <div class="card">
        <form method="POST" action="">
            <div class="input-group">
                <label for="result_regno">Admission Number</label>
                <input type="text" id="result_regno" name="result_regno" placeholder="Enter Your Admission Number" onchange="fetchStudentDetails(this.value)" />
            </div>

            <div class="input-group">
                <label for="result_name">Student Name</label>
                <input type="text" id="result_name" name="result_name" placeholder="Your Name" readonly />
            </div>

            <div class="input-group">
                <label for="result_class">Class</label>
                <input type="text" id="result_class" name="result_class" placeholder="Your Class" readonly />
            </div>

            <div class="input-group">
                <label for="result_term">Term</label>
                <select id="result_term" name="result_term" required>
                    <option selected><?php echo $select; ?></option>
                    <?php
                        $term_array = array("First Term", "Second Term", "Third Term");
                        foreach ($term_array as $term) echo "<option>{$term}</option>";
                    ?>
                </select>
            </div>

            <div class="input-group">
                <label for="result_session">Session</label>
                <select id="result_session" name="result_session">
                    <?php
                		///////////// POST ACTION TO DISPLAY CURRENT SESSION //////////////
                		$query = " SELECT * FROM `current_season` ";
                		$run_query = mysqli_query($connection, $query);
                		if(mysqli_num_rows($run_query) == 1){
                			while($result = mysqli_fetch_assoc($run_query)){
                				$show_current_session = $result['current_session'];
                				echo"
                					<option>{$show_current_session}</option>
                				";
                			}
                		}
                	?>
                </select>
            </div>

            <div class="input-group" style="display: <?php echo $css_display; ?>">
                <label for="result_pin">Result Pin</label>
                <input type="text" id="result_pin" name="result_pin" placeholder="Enter Card Pin" />
            </div>

            <div class="input-group">
                <input type="submit" name="check_result_btn" value="Check Result" />
            </div>
        </form>
    </div>
</div>

<!-- Footer -->
<footer>
    &copy; <?php echo date("Y"); ?> Adamspring College. All rights reserved.
</footer>


<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function fetchStudentDetails(regNumber) {
    if (regNumber !== "") {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "fetch_student_details.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);

                if (response.status === "success") {
                    document.getElementById("result_name").value = response.fullname;
                    document.getElementById("result_class").value = response.class;
                } else {
                    // Use SweetAlert for errors or warnings
                    Swal.fire({
                        icon: response.status, // "error" or "warning"
                        title: response.message,
                        showConfirmButton: false,
                        timer: 3000
                    });

                    // Clear the fields
                    document.getElementById("result_name").value = "";
                    document.getElementById("result_class").value = "";
                }
            }
        };

        xhr.send("reg_number=" + encodeURIComponent(regNumber));
    } else {
        Swal.fire({
            icon: "warning",
            title: "Please enter a registration number.",
            showConfirmButton: false,
            timer: 3000
        });
        document.getElementById("result_name").value = "";
        document.getElementById("result_class").value = "";
    }
}
</script>



<!-- SweetAlert Trigger -->
<script>
    <?php if(isset($msg)) { ?>
        Swal.fire({
            position: 'center', // this puts it in the center of the screen (middle + center)
            icon: '<?php echo $alert_type; ?>', // e.g., 'success', 'error', 'warning'
            title: '<?php echo $msg; ?>',
            showConfirmButton: false,
            timer: 3000
        }).then(() => {
            <?php if($redirect_url) { ?>
                window.location.href = '<?php echo $redirect_url; ?>';
            <?php } ?>
        });
    <?php } ?>
</script>


</body>
</html>
