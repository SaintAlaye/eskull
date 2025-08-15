<?php
	ob_start();
    session_start();
?>
<?php
chdir('../');
?>
<?php
	require_once("public/connection.php");
	require_once("public/functions.php");
?>
<?php
    // Start the session to retrieve the result ID
    session_start();

    // If 'print' is set in the URL, trigger the print page for PDF
    if(isset($_GET['print']) && $_GET['print'] == 'true'){
        echo "<script type='text/javascript'>
                window.print(); // Trigger the print dialog
            </script>";
    }
?>
<?php
	//////////////// DEFAULT NULL VALUES ///////////////
	$error = array();
	$date = date('d/M/Y');
	$select = "--select--";
	$time = date('h:m:s');
?>
<?php
// Step 1: Fetch result data using result ID from session
$result_reg_number = '';
$result_name = '';
if (!empty($_SESSION['result'])) {
    $stmt = $connection->prepare("SELECT * FROM results1 WHERE id = ?");
    $stmt->bind_param("s", $_SESSION['result']);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows === 1) {
        $row = $res->fetch_assoc();
        $result_name = $row['name'];
        $result_reg_number = $row['reg_number'];
        $result_class = $row['class'];
        $result_term = $row['term'];
        $result_session = $row['session'];
    }
}

// Step 2: Fetch student bio-data using reg_number
if (!empty($result_reg_number)) {
    $stmt = $connection->prepare("SELECT * FROM students WHERE reg_number = ?");
    $stmt->bind_param("s", $result_reg_number);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows === 1) {
        $row = $res->fetch_assoc();
        $result_passport = $row['passport'];
        $result_sex = $row['gender'];
        $result_height = $row['height'];
        $result_weight = $row['weight'];
        $result_club = $row['club'];
    }
}

// Step 3: Fetch behavioral analysis using name or reg_number
$puntuality = $neatness = $relationship_with_others = $regards_for_authority =
$attitude_towards_academics = $honesty = $initiative = $form_master_or_mistress = "N/A";

if (!empty($result_name) || !empty($result_reg_number)) {
    $term = "Third Term"; // Explicitly set the term
    $stmt = $connection->prepare("
        SELECT * FROM behavioral 
        WHERE (name = ? OR reg_number = ?) 
        AND term = ?
    ");
    $stmt->bind_param("sss", $result_name, $result_reg_number, $term);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $puntuality = $row['puntuality'];
        $neatness = $row['neatness'];
        $relationship_with_others = $row['relationship_with_others'];
        $regards_for_authority = $row['regards_for_authority'];
        $attitude_towards_academics = $row['attitude_towards_academics'];
        $honesty = $row['honesty'];
        $initiative = $row['initiative'];
        $form_master_or_mistress = $row['form_master_or_mistress'];
    }
}

?>

<?php
////////// POST ACTION TO VIEW ALL THE UPLOADED ADMINISTRATIVE INFORMATION ////////////
$query = " SELECT * FROM `administratives` ";
$run_query = mysqli_query($connection, $query);

if (mysqli_num_rows($run_query) > 0) {
    while ($result = mysqli_fetch_assoc($run_query)) {
        $administratives_id = $result['id'];
        $school_name = $result['school_name'];
        $school_motto = $result['school_motto'];
        $school_logo = $result['school_logo'];
        $school_address = $result['school_address'];
        $school_phone = $result['school_phone'];
        $school_mail = $result['school_mail'];
        $school_website = $result['school_website'];
        $school_whatsapp = $result['school_whatsapp'];
        $school_short_name = $result['school_short_name'];
        $school_favicon = $result['school_favicon'];
        $school_initial = $result['adm_no_initials'];
        $school_stamp = $result['school_stamp'];
        //echo "<img src='$admin_logo' />";
    }
}
?>
<?php
	$query = " SELECT * FROM `dates` ";
	$run_query = mysqli_query($connection, $query);
	
	if(mysqli_num_rows($run_query) == 1){
		while($result = mysqli_fetch_assoc($run_query)){
			$term_ended = $result['end_of_term'];
			$next_term_starts = $result['next_term_begins'];
		}
	}
?>


<?php
    $present = "Present";
    $absent = "Absent";

    $query = "SELECT * FROM student_attendance WHERE `reg_number` = '{$result_reg_number}' AND term = 'Third Term' AND session = '{$result_session}' AND class = '{$result_class}' AND roll_call = '{$present}' ";
    $run_query = mysqli_query($connection, $query);

    $times_present = mysqli_num_rows($run_query);

    $query = "SELECT * FROM student_attendance WHERE `reg_number` = '{$result_reg_number}' AND term = 'Third Term' AND session = '{$result_session}' AND class = '{$result_class}' AND roll_call = '{$absent}' ";
    $run_query = mysqli_query($connection, $query);

    $times_absent = mysqli_num_rows($run_query);
?>

<?php
    $query = " SELECT * FROM fee_structure WHERE `class` = '{$result_class}' ";
    $run_query = mysqli_query($connection, $query);

    if(mysqli_num_rows($run_query) == 1){
        while($result = mysqli_fetch_assoc($run_query)){
            $next_term_fees = $result['amount'];
        }
    }
?>
<?php
    $query = " SELECT school_stamp FROM `administratives` ";
    $run_query = mysqli_query($connection, $query);

    if(mysqli_num_rows($run_query) == 1){
        while($result = mysqli_fetch_assoc($run_query)){
            $signature = $result['school_stamp'];
        }
    }
?>


<?php
// Get remarks from positions table (assuming this is how you fetch them)
$class_teacher_remark = '';
$head_teacher_remark = '';
$stmt = $connection->prepare("SELECT class_teacher_comment, head_teacher_comment FROM positions WHERE reg_number = ? AND class = ? AND term = ? AND session = ?");
$stmt->bind_param("ssss", $result_reg_number, $result_class, $result_term, $result_session);
$stmt->execute();
$remark_result = $stmt->get_result();
if ($remark_result && $remark_result->num_rows === 1) {
    $row = $remark_result->fetch_assoc();
    $class_teacher_remark = $row['class_teacher_comment'];
    $head_teacher_remark = $row['head_teacher_comment'];
}

// Get failing subjects
$failing_subjects = getFailingSubjects($connection, $result_reg_number, $result_class, $result_term, $result_session);
?>


<!DOCTYPE>
<html>
	<head>
		<title><?php echo $result_term; ?> Result - <?php echo $result_name; ?></title>
		<meta charset='utf-8'>
        <meta charset='utf-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- CSS-->
        <link rel='stylesheet' type='text/css' href='../assets/css/main.css'>
		<link rel='stylesheet' type='text/css' href='../assets/css/defined.css'>
		<link rel="icon" href="../<?php echo $school_favicon; ?>" type="image/png">
		<!-- Font-icon css-->
		<link rel="stylesheet" href="../assets/css/font-awesome-4.7.0/css/font-awesome.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
		<script src="../assets/js/jquery-2.1.4.min.js"></script>
		<script src='../assets/js/bootstrap.js'></script>
		<script src='../assets/js/blink.js'></script>
		<style type="text/css">
		<style type="text/css">
			body {
				font-family: Arial, sans-serif;
				width: 1024px;
				margin: 0 auto;
				overflow-x: hidden;
				touch-action: none;				
				padding: 8px;
				background-color: #f9f9f9;
                visibility: hidden; /* Hide everything on the page */
			}
			.container {
				font-family: Arial, sans-serif;
				max-width: 1000px;
				margin: 0;
				background: white;
				padding: 8px;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
			}
			.header-row {
				display: flex;
				justify-content: space-between;
				align-items: center;
				margin-bottom: 20px;
			}
			.logo, .pic {
				width: 100px;
				height: 100px;
				border-radius: 20%;
			}
			.logo img, .pic img {
				width: 100%;
				height: 100%;
				border-radius: 20%;
			}
			.school-info {
				text-align: center;
				flex: 1;
				margin-left: 20px;
				margin-right: 20px;
			}
			.school-info h1 {
				color: #0066cc;
				font-size: 18px;
				margin: 0;
			}
			.school-info p {
				margin: 5px 0;
				font-size: 12px;
			}

			.left-align {
				text-align: left;
			}

			.right-align {
				text-align: right;
			}

			.center-align {
				text-align: center;
			}

			.justify-align {
				text-align: justify;
			}
			p {
				font-size: 14px;
			}
			#result td {
				border: 2px solid black;
				padding: 6px;
				font-size: 12px;
				border-style:solid;
				border-color: black;
                text-align: left;
                white-space: nowrap; /* Prevents line breaks */                
			}
			.perf {
				float: left;
				width: 70%;
			}
            #performance td {
				border: 2px solid black;
				text-align: left;
				font-size: 11px;
				padding: 3px;
			}
            #performance th {
                background-color: lightblue;
				border: 2px solid black;
				text-align: left;
				font-size: 11px;
				padding: 3px;
			}            
			#result th {
				border-style:solid black;
                background-color: lightblue;
				border-color: black;
				border: 2px solid ;
                text-align: left;
                white-space: nowrap; /* Prevents line breaks */                
				padding: 6px;
				font-size: 12px;
			}
			h5 {
				font-size: 8px;
			}

			.row {
			  display: flex;
			  margin-left:-5px;
			  margin-right:-5px;
			}

			.column {
			  flex: 100%;
			  padding: 1px;
			}

			table {
			  border-collapse: collapse;
			  border-style: solid black;			  
			  border-spacing: 0;
			  width: 100%;
			  border: 3px solid;
			}

			#attendance td, #affective td {
			    border: 2px solid black;	
			    text-align: left;
			    font-size: 10px;
			    padding: 6px;
			}
			#attendance th, #affective th {
                background-color: lightblue;
			    border: 2px solid black;	
			    text-align: left;
			    font-size: 10px;
			    padding: 6px;
			}            
			/* Left column */
			.leftcolumn {
			  float: left;
			  width: 70%;
			}

			/* Right column */
			.rightcolumn {
			  float: left;
			  width: 30%;
			  padding-left: 5px;
			}
			tr:nth-child(even) {
			  background-color: solid black;
			}
            .printable-section {
                position: absolute;
                top: 0;
                left: 0;
            }
		    @media print {
				@page {
					size: A4; /* Set the paper size to A4 */
					margin: 10mm; /* Adjust margins if necessary */
				}

				body {
					font-family: Arial, sans-serif; /* Example font */
					margin: 0; /* Remove default body margin */
				}
			}

			.grade-details, .remarks, .admin-signature {
				font-size: 12px;
				margin-top: 5px;
			}
			.remarks, .admin-signature {
                border: 3px solid black;
                font-size: 8px;
                text-align: left;
                font-size: 8px;
                padding: 5px;
			}
			.clear-fix::after {
				content: "";
				display: block;
				clear: both;
			}
			.left {
				float: left;
				width: 46%;
				text-align:left;
			}
			.center {
				float: left;
				width: 27%;
				text-align:center;
			}			
			.right {
				float: left;
				width: 27%;				
				text-align:right;
			}
			.stampC {
				position: relative;
				text-align: center;
				max-width: 300px;
				width: 300%;                
			}
			.stamp {
                position: center;
				text-align: center;
				max-width: 300px;
				width: 300%;
			}
            		
		}
		</style>
	</head>
    <body>
        <div class="printable-section">
            <div class='panel panel-primary'>
                <div class='panel-body'>
                    <div class="container">
                        <div class="header-row">
                            <div class="logo">
                                <img src="../<?php echo $school_logo; ?>" class="img-responsive" alt="School Logo" />
                            </div>
                            <div class="school-info">
                                <h1><?php echo $school_name; ?></h1>
                                <p><b><?php echo $school_motto; ?></b></p>
                                <p><i class="fa fa-map-marker"></i> <b><?php echo $school_address; ?></b></p>
								<p><i class="fa fa-phone"></i> <b>Tel: <?php echo $school_phone; ?></b></p>
								<p><i class="fa fa-envelope"></i> <b>E-mail: <?php echo $school_mail; ?></b></p>
								<p><i class="fa fa-globe"></i> <b>Website: <?php echo $school_website; ?></b></p>

                                
                            </div>
                            <div class="pic">
                                <img src="../<?php echo $result_passport; ?>" class="img-responsive" alt="Student Passport" />
                            </div>
                        </div>
                        <div class="clear-fix">
                            <div class="left">
                                <b>TERM:</b> <u><?php echo $result_term; ?></u>
                            </div>
                            <div class="center">
                                <b>SESSION:</b> <u><?php echo $result_session; ?></u>
                            </div>
                            <div class="right">
                                <b>CLASS:</b> <u><?php echo $result_class; ?></u>
                            </div>
                        </div>				

                        <div class="clear-fix">
                            <div class="left">
                                <b>NAME:</b> <u><?php echo strtoupper($result_name); ?></u>
                            </div>
                            <div class="center">
                                <b>ADM NO:</b> <u><?php echo $result_reg_number; ?></u>
                            </div>
                            <div class="right">
                                <b>GENDER:</b> <u><?php echo $result_sex; ?></u>
                            </div>
                        </div>
                        <div class="clear-fix">
                            <div class="left">
                                <b>WEIGHT:</b> <u><?php echo $result_weight; ?> kg</u>
                            </div>
                            <div class="center">
                                <b>HEIGHT:</b> <u><?php echo $result_height; ?> ft</u>
                            </div>
                            <div class="right">
                                <b>CLUB:</b> <u><?php echo $result_club; ?></u>
                            </div>
                        </div>
                        <?php
// Step 1: Get all distinct subjects the student has taken
$subject_query = "SELECT DISTINCT `subjects` FROM `results1` 
                  WHERE `reg_number` = '{$result_reg_number}' 
                  AND `class` = '{$result_class}' 
                  AND `session` = '{$result_session}'";

$subject_result = mysqli_query($connection, $subject_query);

// Start table
echo "
<div class='row'>
    <div class='leftcolumn'>
        <table class='text-info' id='result' style='width: 100%; border-collapse: collapse;'>
            <thead>
                <tr>
                    <th colspan='7' style='text-align:center'><b>ANNUAL SUBJECT TOTALS</b></th>
                </tr>
                <tr class='info'>
                    <th style='width: 5%;'>S/N</th>
                    <th style='width: 30%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>Subject</th>
                    <th style='width: 11%; text-align:center;'>1st</th>
                    <th style='width: 11%; text-align:center;'>2nd</th>
                    <th style='width: 11%; text-align:center;'>3rd</th>
                    <th style='width: 11%; text-align:center;'>Tot</th>
                    <th style='width: 11%; text-align:center;'>Avg</th>
                </tr>
            </thead>
            <tbody>
";

$i = 0;
if (mysqli_num_rows($subject_result) > 0) {
    while ($subject_row = mysqli_fetch_assoc($subject_result)) {
        $subject = $subject_row['subjects'];
        $i++;

        // Fetch term totals
        $scores = [
            'First Term' => 0,
            'Second Term' => 0,
            'Third Term' => 0
        ];

        foreach ($scores as $term => &$score) {
            $term_query = "SELECT `subject_total` FROM `results1` 
                           WHERE `reg_number` = '{$result_reg_number}' 
                           AND `class` = '{$result_class}' 
                           AND `session` = '{$result_session}' 
                           AND `term` = '{$term}' 
                           AND `subjects` = '{$subject}' 
                           LIMIT 1";
            $term_result = mysqli_query($connection, $term_query);
            if ($term_row = mysqli_fetch_assoc($term_result)) {
                $score = floatval($term_row['subject_total']);
            } else {
                $score = 0;
            }
        }

        $total = $scores['First Term'] + $scores['Second Term'] + $scores['Third Term'];
        $average = round($total / 3, 2);

        echo "
        <tr>
            <td>{$i}</td>
            <td style='max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>{$subject}</td>
            <td style='text-align:center'>{$scores['First Term']}</td>
            <td style='text-align:center'>{$scores['Second Term']}</td>
            <td style='text-align:center'>{$scores['Third Term']}</td>
            <td style='text-align:center'>{$total}</td>
            <td style='text-align:center'>{$average}</td>
        </tr>
        ";
    }
}

echo "
            </tbody>
        </table>
";
?>


                        
                            <h6></h6>
                            
                            <h6></h6>
<?php
// Step 1: Count how many students have annual summaries in this class & session
$query = "SELECT * FROM `annual_result_summary` 
          WHERE `class` = '{$result_class}' 
          AND `session` = '{$result_session}'";
$run_query = mysqli_query($connection, $query);
$number_in_class = mysqli_num_rows($run_query);

// Step 2: Get number of subjects taken by the student
$subject_count_query = "SELECT COUNT(DISTINCT `subjects`) AS subject_count FROM `results1`
                        WHERE `reg_number` = '{$result_reg_number}' 
                        AND `class` = '{$result_class}' 
                        AND `session` = '{$result_session}'";
$subject_result = mysqli_query($connection, $subject_count_query);
$subject_count_row = mysqli_fetch_assoc($subject_result);
$number_of_subjects = $subject_count_row['subject_count'] ?? 0;

// Marks obtainable: subjects × 3 terms × 100
$marks_obtainable = $number_of_subjects * 3 * 100;

// Step 3: Get the current student’s annual result summary
$query = "SELECT * FROM `annual_result_summary` 
          WHERE `reg_number` = '{$result_reg_number}' 
          AND `class` = '{$result_class}' 
          AND `session` = '{$result_session}'";
$run_query = mysqli_query($connection, $query);

if (mysqli_num_rows($run_query) == 1) {
    while ($result = mysqli_fetch_assoc($run_query)) {
        $student_total = $result['total_annual_scores'];
        $total_average = $result['total_annual_average'];
        $student_average = round($result['overall_avg_marks'], 2);
        $position_in_class = $result['position'];
        $ordinal_position = getOrdinal($position_in_class); // Converts 1 to 1st, 2 to 2nd, etc.
    }
}
?>

<table class='text-info' id='result'>
    <thead>
        <tr><th colspan="6" style='text-align:center'><b>PERFORMANCE SUMMARY</b></th></tr>
        <tr>
            <td style="text-align:center; font-size:10px;"><b>MARKS<br />OBTAINABLE</b></td>
            <td style="text-align:center; font-size:10px;"><b>MARKS<br />OBTAINED</b></td>
            <td style="text-align:center; font-size:10px;"><b>TOTAL<br />AVERAGE</b></td>
            <td style="text-align:center; font-size:10px;"><b>OVERALL<br />AVERAGE</b></td>
            <td style="text-align:center; font-size:10px;"><b>NO IN CLASS</b></td>
            <td style="text-align:center; font-size:10px;"><b>POSITION</b></td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style='text-align:center'><b><?php echo $marks_obtainable; ?></b></td>
            <td style='text-align:center'><b><?php echo $student_total; ?></b></td>
            <td style='text-align:center'><b><?php echo $total_average; ?>%</b></td>
            <td style='text-align:center'><b><?php echo $student_average; ?>%</b></td>
            <td style='text-align:center'><b><?php echo $number_in_class; ?></b></td>
            <td style='text-align:center'><b><?php echo $ordinal_position; ?></b></td>
        </tr>
    </tbody>
</table>


                        <?php
                            if($result_term === "Third Term" && $result_status === "Pass"){
                                $third_term_promotion = "Promoted";
                            }
                            elseif($result_term === "Third Term" && $result_status === "Fail"){
                                $third_term_promotion = "Repeat";
                            }else{
                                $third_term_promotion = $result_status;
                            }
                        ?>
						<!-- CLASS TEACHER REMARK -->
						<div class="remarks">
							<p><b>Form Master/Mistress:</b> <?= htmlspecialchars($class_teacher_remark) ?></p>
						</div>

						<!-- HEAD TEACHER REMARK -->
						<div class="admin-signature">
							<p><b>Head Teacher:</b> <?= htmlspecialchars($head_teacher_remark) ?></p>
						</div>
<div class="admin-signature">
    <p>
        <?php if (!empty($failing_subjects)): ?>
            Put more effort in 
            <b><?= implode(', ', array_map('htmlspecialchars', $failing_subjects)) ?></b>
        <?php else: ?>
            You did well in all subjects.
        <?php endif; ?>
    </p>
</div>
                                  
                        </div>
                        <div class="rightcolumn">
                            <table id="affective">
                            <thead>
                                <th style='text-align:center' colspan="2"><b>AFFECTIVE/PSYCHOMOTOR DOMAIN</b></th>
                                <tr>
                                    <td>PUNCTUALITY</td>
                                    <td><?php echo $puntuality; ?></td>
                                </tr>
                                <tr>
                                    <td>NEATNESS</td>
                                    <td><?php echo $neatness; ?></td>
                                </tr>
                                <tr>
                                    <td>RELATIONSHIP WITH OTHERS</td>
                                    <td><?php echo $relationship_with_others; ?></td>
                                </tr>
                                <tr>
                                    <td>REGARDS FOR AUTHORITY</td>
                                    <td><?php echo $regards_for_authority; ?></td>
                                </tr>
                                <tr>
                                    <td>ATTITUDE TOWARDS ACADEMIC</td>
                                    <td><?php echo $attitude_towards_academics; ?></td>
                                </tr>
                                <tr>
                                    <td>HONESTY</td>
                                    <td><?php echo $honesty; ?></td>
                                </tr>
                                                        <tr>
                                    <td>INITIATIVE</td>
                                    <td><?php echo $initiative; ?></td>
                                </tr>
                                <tr>
                                    <td>FORM MASTER/MISTRESS</td>
                                    <td><?php echo $form_master_or_mistress; ?></td>
                                </tr>
                                <th style='text-align:center;' colspan="2">
                                <b>GRADE CODE</br>(A=5, B=4, C=3, D=2, F=1)</b>
                                </th>						
                            </table>

                            <h6></h6>
                            <table id="attendance">
                            <th style='text-align:center' colspan="2"><b>ATTENDANCE SUMMARY</b></th>
                                <tr>
                                    <td>NO OF DAYS SCHOOL OPENED</td>
                                    <td><?php echo $times_absent + $times_present; ?></td>
                                </tr>
                                <tr>
                                    <td>NO OF TIME PRESENT</td>
                                    <td><?php echo $times_present; ?></td>
                                </tr>
                                <tr>
                                    <td>NO OF TIME ABSENT</td>
                                    <td><?php echo $times_absent ?></td>
                                </tr>
                            </table>

                            <h6></h6>
                            <table id="attendance">
                                <th style='text-align:center' colspan="2"><b>TERM SCHEDULE AND FEES</b></th>					   
                                <tr>
                                    <td><b>SCHOOL CLOSE</b></td>
                                    <td style="text-align:center; font-size:12px;"><b><?php echo $term_ended; ?></b></td>
                                </tr>
                                <tr>
                                    <td><b>NEXT TERM RESUMES</b></td>
                                    <td style="text-align:center; font-size:12px;"><b><?php echo $next_term_starts; ?></b></td>
                                </tr>
                                <tr>
                                    <td><b>NEXT TERM FEES</b></td>
                                    <td style="text-align:center; font-size:12px;"><b>₦<?php echo $next_term_fees; ?></b></td>
                                </tr>
                            </table>
                            <h5></h5>
                            <div style="display: flex; justify-content: center;">
                                <img src="../<?php echo $school_stamp;?>" alt="Signature" id="signature" class="stamp" style="width: 200px; height: auto;" />
                            </div> 
                        </div>
                    </div>
                                <button type='button' class='btn btn-primary btn-sm noprint' onclick='window.print()' value='print a div!'><i class='fa fa-print' style='text-align:center' ></i> Print </button>
						<?php
			$previous = $_SERVER['HTTP_REFERER'] ?? 'view_published_result';
			?>
			<a href="<?= htmlspecialchars($previous) ?>" class='btn btn-danger btn-sm noprint'>
				<i class='fa fa-close'></i> Cancel
			</a>

                </div>	
            </div>
        </div>
    </div>
</div>
            <br />
            <br />
        </div>
        </div>
        </div>
	</body>
</html>
