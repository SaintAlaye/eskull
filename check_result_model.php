<?php
session_start();
require_once("public/connection.php");
require_once("public/functions.php");
include_once("sweet_alert.php");
?>


<?php
        //Check if scratch is enabled or disabled
        $query = "SELECT use_scratch_card FROM settings LIMIT 1";
        $run_query = mysqli_query($connection, $query);
    
        if(mysqli_num_rows($run_query) > 0){
            while($result = mysqli_fetch_assoc($run_query)){
                $status = $result['use_scratch_card'];
            }
        }
        if($status == 1){
            $css_display = "";
        }else{
            $css_display = "none";
        }
    
        ////////////////////// POST ACTION TO CHECK STUDENTS RESULT IF THE CHECK RESULT BUTTON IS CLICKED //////////////////
        if(isset($_POST['check_result_btn'])){
            $result_name = inject_checker($connection, $_POST['result_name']);
            $result_regno = inject_checker($connection, $_POST['result_regno']);
            $result_class = inject_checker($connection, $_POST['result_class']);
            $result_term = inject_checker($connection, $_POST['result_term']);
            $result_session = inject_checker($connection, $_POST['result_session']);
            $result_pin = inject_checker($connection, $_POST['result_pin']);
            $used_pin_count = 1;
    
            //////////////// ERROR CHECKING FOR EMPTY FIELDS /////////////////
            if(empty($result_regno)){
                $msg = "Registration Number is Required";
                $alert_type = "error";
            }
            elseif($result_class == $select){
                $msg = "Please Select Class to check Result";
                $alert_type = "error";
            }
            elseif($result_term == $select){
                $msg = "Please Select the term for the Result";
                $alert_type = "error";
            }
            elseif($status == 1){
    
                if(empty($result_pin)){
                    $msg = "Please Type in Card Pin to Access your Result";
                    $alert_type = "error";
                }else{
                    if($result_term == ucwords("first term")){
                        $query = " SELECT * FROM `pin1` WHERE `first_term_pin` = '{$result_pin}' ";
                    }
                    elseif($result_term == ucwords("second term")){
                        $query = " SELECT * FROM `pin2` WHERE `second_term_pin` = '{$result_pin}' ";
                    }
                    elseif($result_term == ucwords("third term")){
                        $query = " SELECT * FROM `pin3` WHERE `third_term_pin` = '{$result_pin}' ";
                    }
    
                    $run_query = mysqli_query($connection, $query);
    
                    if(mysqli_num_rows($run_query) == 1){
    
                        $query = " SELECT * FROM `used_pins` WHERE `used_pins` = '$result_pin' AND `user_class` = '{$result_class}' AND `used_term` = '{$result_term}' AND `used_session` = '{$result_session}' ";
                        $run_query = mysqli_query($connection, $query);
    
                        if(mysqli_num_rows($run_query) > 0){
                            while($result = mysqli_fetch_assoc($run_query)){
                                $first_used_reg_number = $result['user_reg_number'];
                            }
                            if($result_regno !== $first_used_reg_number){
                                $msg = "This Pin Has Already Been Used by Another Student!!!";
                                $alert_type = "error";
                            }else{
                                $query = " SELECT `used_count` FROM `used_pins` WHERE `used_pins` = '$result_pin' AND `user_reg_number` = '{$result_regno}' AND `user_class` = '{$result_class}' AND `used_term` = '{$result_term}' AND `used_session` = '{$result_session}' ";
                                $run_query = mysqli_query($connection, $query);
    
                                while($result = mysqli_fetch_assoc($run_query)){
                                    $pin_usage_count = $result['used_count'];
                                }
                                if($pin_usage_count == 5){
                                    $msg = "Your Have Exhausted Your Times Usage Validity!!!";
                                    $alert_type = "error";
                                }else{
                                    $pin_usage_count++;
                                    $query = " UPDATE `used_pins` SET `used_count` = '{$pin_usage_count}' WHERE `used_pins` = '{$result_pin}' AND `user_class` = '{$result_class}' AND `used_term` = '{$result_term}' AND `used_session` = '{$result_session}' ";
                                    $run_query = mysqli_query($connection, $query);
    
                                    if($run_query == true){
                                        $query = " SELECT * FROM `results1` WHERE `reg_number` = '{$result_regno}' AND `class` = '{$result_class}' AND `term` = '{$result_term}' AND `session` = '{$result_session}' ";
                                        $run_query = mysqli_query($connection, $query);
    
                                        if($run_query == true){
                                            if(mysqli_num_rows($run_query) > 0){
                                                while($result = mysqli_fetch_assoc($run_query)){
                                                    $result_id = $result['id'];
                                                    $_SESSION['result'] = $result_id;
                                                    // Redirect to the result page where the PDF is generated
                                                    echo "<script type='text/javascript'>
                                                            window.location.href = 'termly_result.php?print=true';
                                                        </script>";
                                                }
                                            }else{
                                                $msg = "No Result Records Found!!!";
                                                $alert_type = "error";
                                            }
                                        }else{
                                            $msg = "Result Checking Failed!!!";
                                            $alert_type = "error";
                                        }
                                    }else{
                                        $msg = "Result Checking Encountered Error!!!";
                                        $alert_type = "error";
                                    }
                                }
                            }
                        }else{
                            $query = " SELECT * FROM `used_pins` WHERE `used_pins` = '$result_pin' AND `user_class` = '{$result_class}' AND `used_term` = '{$result_term}' AND `used_session` = '{$result_session}' ";
                            $run_query = mysqli_query($connection, $query);
    
                            if(mysqli_num_rows($run_query) < 1){
                                $query = " SELECT * FROM `used_pins` WHERE `used_pins` = '$result_pin' AND `user_reg_number` = '{$result_regno}' AND `user_class` = '{$result_class}' AND `used_term` = '{$result_term}' AND `used_session` = '{$result_session}' ";
                                $run_query = mysqli_query($connection, $query);
    
                                $query = " INSERT INTO `used_pins`(`used_pins`, `user_name`, `user_reg_number`, `used_count`, `user_class`, `used_term`, `used_session`, `date_used`)
                                                VALUES('$result_pin', '$result_name', '$result_regno', '$used_pin_count', '$result_class', '$result_term', '$result_session', '$date')";
                                $run_query = mysqli_query($connection, $query);
    
                                if($run_query == true){
                                    $query = " DELETE FROM `unused_pins` WHERE `unused_pins` = '{$result_pin}' ";
                                    $run_query = mysqli_query($connection, $query);
    
                                    if($run_query == true){
                                        $query = " SELECT * FROM `results1` WHERE `reg_number` = '{$result_regno}' AND `class` = '{$result_class}' AND `term` = '{$result_term}' AND `session` = '{$result_session}' ";
                                        $run_query = mysqli_query($connection, $query);
    
                                        if($run_query == true){
                                            if(mysqli_num_rows($run_query) > 0){
                                                while($result = mysqli_fetch_assoc($run_query)){
                                                    $result_id = $result['id'];
                                                    $_SESSION['result'] = $result_id;
                                                    // Redirect to the result page where the PDF is generated
                                                    echo "<script type='text/javascript'>
                                                            window.location.href = 'termly_result.php?print=true';
                                                        </script>";
                                                }
                                            }else{
                                                $msg = "No Result Records Found!!!";
                                                $alert_type = "error";
                                            }
                                        }else{
                                            $msg = "Result Checking Failed!!!";
                                            $alert_type = "error";
                                        }
                                    }
                                }else{
                                    $msg = "Result Checking Encountered Error!!!";
                                    $alert_type = "error";
                                }
                            }
                        }   
                    }else{
                        $msg = "Incorrect Pin !!!";
                        $alert_type = "error";
                    }
                }
            }else{
                $query = " SELECT * FROM `results1` WHERE `reg_number` = '{$result_regno}' AND `class` = '{$result_class}' AND `term` = '{$result_term}' AND `session` = '{$result_session}' ";
                $run_query = mysqli_query($connection, $query);
    
                if($run_query == true){
                    if(mysqli_num_rows($run_query) > 0){
                        while($result = mysqli_fetch_assoc($run_query)){
                            $result_id = $result['id'];
                            $_SESSION['result'] = $result_id;
                            // If term is Third Term, check annual_result_summary
                            if (strtolower($result_term) == "third term") {
                                $check_annual = mysqli_query($connection, "
                                    SELECT 1 FROM annual_result_summary 
                                    WHERE reg_number = '{$result_regno}' 
                                    AND class = '{$result_class}' 
                                    AND session = '{$result_session}'
                                    LIMIT 1
                                ");
                                
                                if (mysqli_num_rows($check_annual) > 0) {
                                    echo "<script type='text/javascript'>
                                        window.location.href = 'annual_result.php?print=true';
                                    </script>";
                                } else {
                                    echo "<script type='text/javascript'>
                                        window.location.href = 'termly_result.php?print=true';
                                    </script>";
                                }
                            } else {
                                echo "<script type='text/javascript'>
                                    window.location.href = 'termly_result.php?print=true';
                                </script>";
                            }
                        }
                    }else{
                        $msg = "No Result Records Found !!!";
                        $alert_type = "error";
                    }
                }else{
                    $msg = "Result Checking Failed !!!";
                    $alert_type = "error";
                } 
            }
            // Ensure that the message is safely escaped for JavaScript
        $msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
        }
    ?>  