<?php

	include '../config.php';

	$action = $_POST['action'];
	if($action == '1'){

	}
	else if($action == 'aysemSession'){
		$aysem = $_POST['result'];
		$_SESSION['classaysem'] = $aysem;
	}
	// start create classes ajax
	else if ($action == "changeSubjTitle") {

		$subjectTitle = $_POST['subjectTitle'];
		$newSectionSql = mysqli_query($connect, "SELECT COUNT(c.class_id)+1 FROM classes c
			JOIN subjects a ON a.subject_id = c.subject_id
			JOIN activities b ON b.gradschool_id = a.gradschool_id AND b.activity_id = 6
			WHERE c.subject_id = $subjectTitle AND SUBSTR(class_id,1,5) = b.aysem");
		$newSection = mysqli_fetch_row($newSectionSql);

		$unitsSql = mysqli_query($connect, "SELECT units FROM subjects WHERE subject_id = '$subjectTitle'");
		$units = mysqli_fetch_row($unitsSql)[0];
		echo $newSection[0]."|".$units[0];
	}
	else if($action == 'changeSection'){
		$section = $_POST['section'];
    	$currentsem = $_SESSION['aysem'];
		$subjectTitle = $_POST['subjectTitle'];
		$classid = $_POST['classid'];

		$isAllowedSql = mysqli_query($connect, "SELECT * FROM classes WHERE subject_id = $subjectTitle AND section = $section AND SUBSTR(class_id,1,5) = $currentsem AND status = 1");
		
		if(mysqli_num_rows($isAllowedSql) > 0){
			echo 'error';
		}
	}

	// start edit classes ajax //
	else if ($action == "changeFaculty") {
		$faculty = $_POST['faculty'];
		$units = $_POST['units'];
		$classid = $_POST['classid'];
		$aysem = $_SESSION['aysem'];
		$gradschoolid = $_SESSION['gradschoolid'];
		$totalUnits = 0;

		if (empty($units)) {
			$units = 0;
		}

		if ($faculty != " ") {
			$facultyUnitsSql = mysqli_query($connect, "SELECT DISTINCT(class_id), unit FROM classes WHERE SUBSTR(class_id,1,5) = '$aysem' AND faculty_id = '$faculty' AND class_id != '$classid' AND status = 1");

			while($facultyUnits = mysqli_fetch_row($facultyUnitsSql)) {
				$totalUnits += $facultyUnits[1];
			}

			
			$maxUnitsSql = mysqli_query($connect, "SELECT faculty_units FROM graduate_schools WHERE gradschool_id = '$gradschoolid'");
			$maxUnits = mysqli_fetch_row($maxUnitsSql)[0];

			if($totalUnits + $units > $maxUnits) {
				$sumUnits = $totalUnits + $units;

				if ($units != 0)
					echo "This faculty will have ".($sumUnits - $maxUnits)." units overload!";
				else
					echo "This faculty have ".($sumUnits - $maxUnits)." units overload!";
			}
		}
	}
	// else if($action == 'changeSection'){
	// 	$section = $_POST['section'];
	// 	$subjectTitle = $_POST['subjectTitle'];
	// 	$classid = $_POST['classid'];

	// 	$isAllowedSql = mysqli_query($connect, "SELECT * FROM classes WHERE subject_id = $subjectTitle AND section = $section AND class_id != $classid AND status = 1");
	// 	echo "SELECT * FROM classes WHERE subject_id = $subjectTitle AND section = $section AND class_id != $classid AND status = 1"; exit;
	// 	// if(mysqli_num_rows($isAllowedSql) > 0){
	// 	// 	echo 'error';
	// 	// }
	// }
	else if ($action == "createClass") {

		$classid = $_POST['classid'];
    	$currentsem = $_SESSION['aysem'];
		//$bypass = $_POST['bypass'];
		$error = new Class{};
		$error->msg = '';

		$errorArray = array();
		$errorCtr = 0;

		$daysName = array('Mon' => 'Monday', 'Tue' => 'Tuesday', 'Wed' => 'Wednesday', 'Thu' => 'Thursday', 'Fri' => 'Friday', 'Sat' => 'Saturday', 'Sun' => 'Sunday');
		
		$subject = $_POST['subject'];

		// $classAllowSql = mysqli_query($connect, "SELECT * FROM subjects WHERE subject_id = '$subject' AND 
		// 	subject")
		//Section Problem
		//Checking of subject code for allowing schedules : allowed prefix (DBA, DGM, GME)
		if (empty($_SESSION['classAllow']))
			$_SESSION['classAllow'] = 0;
		$classAllowSql = mysqli_query($connect, "SELECT * FROM subjects WHERE subject_id = '$subject' AND 
			(subject_name LIKE 'DBA%' OR subject_name LIKE 'DGM%' OR subject_name LIKE 'GME%')");
		$classAllow = mysqli_num_rows($classAllowSql);

		$section = $_POST['section'];
		$sectionSql = mysqli_query($connect, "SELECT * FROM classes WHERE subject_id = '$subject' AND section = '$section' AND status = 1 AND SUBSTR(class_id,1,5) = $currentsem AND class_id != '$classid'");

		// $error->title = 'SECTION_PROB';
		// $error->msg = "SELECT * FROM classes WHERE subject_id = '$subject' AND section = '$section' AND status = 1 AND class_id != '$classid'";
		// $error = json_encode($error);
		// echo $error;
		// exit();
		
		if(mysqli_num_rows($sectionSql) > 0){
			$error->title = 'SECTION_PROB';
			$error->msg = "Selected section for this subject is taken. \nPlease change the section.";
			$error = json_encode($error);

			echo $error;
			exit();
		}
		//time range prob
		if ($_POST['days'] == '') {
			$days = array();
			$timestart = array();
			$timeend = array();
		}
		else {
			$days = explode(",", $_POST['days']);
			$timestart = explode(",", $_POST['timestart']);
			$timeend = explode(",", $_POST['timeend']);
		}
			
		$text = "";
		$text1 = "Time start and Time end must not be equal.\n";
		$text2 = "Time end must be greater than Time start.";
		$len = count($days);

		for($i = 0; $i < $len; $i++) {
			if (substr_count($timestart[$i], ":") == 1)
				$timestart[$i] .= ":00";
			if (substr_count($timeend[$i], ":") == 1)
				//echo substr_count($timeend[$i], ":"); exit;
				$timeend[$i] .= ":00";

			$x = $y = null;

			if($timestart[$i] != null)
				$x = new DateTime($timestart[$i]);

			if($timeend[$i] != null)
				$y = new DateTime($timeend[$i]);

			if($x == $y && ($x != null)) {
				if (strpos($text, $text1) === false) 
					$text .= $text1;
				$errorArray[$errorCtr++] = $days[$i];
			}
			else if($x > $y && ($x != null && $y != null)) {
				if (strpos($text, $text2) === false) 
					$text .= $text2;
				$errorArray[$errorCtr++] = $days[$i];
				// $diff = $x->diff($y);	
				// $total = ($diff->h * 60) + $diff->i + ($diff->s/60);
				// echo $total;
			}
		}

		if (count($errorArray)) {
			$error->title = 'TIME';
			$error->msg = $text;
			$error->data = $errorArray;
			$error = json_encode($error);

			echo $error;
			exit();
		}

		//faculty time prob
		$text = "";
		$faculty = $_POST['faculty'];
		$grad = $_SESSION['grad'];
		$aysemSql = mysqli_query($connect, "SELECT aysem FROM activities WHERE gradschool_id = '$grad' AND activity_id = 7");
		$aysem = mysqli_fetch_row($aysemSql)[0];

		if ($faculty != " ") {
			for($i = 0; $i < $len; $i++) {

				$ts = $timestart[$i];
				$te = $timeend[$i];
				$day = $days[$i];

				$lapSql = mysqli_query($connect, "SELECT class_id FROM classes
					WHERE faculty_id = '$faculty' AND SUBSTR(class_id,1,5) = '$aysem' AND
						((timestart >= '$ts' AND timestart < '$te') OR
						(timestart <= '$ts' AND  timeend > '$ts'))
						AND day = '$day' AND class_id != '$classid' AND status = 1");
				$lapNum = mysqli_num_rows($lapSql);
				
				if($lapNum > 0) {
					if (strpos($text, $days[$i]) === false) {
						if ($text != "")
							$text .= ",";
						$text .= ' '.$daysName[ $days[$i] ];
					}
				}

			}
		}
		// if ($text && $bypass == 0) {
		if ($text && $_SESSION['classAllow'] == 0) {
			if ($classAllow > 0) {
				$error->title = 'CLASS_ALLOW';
				$_SESSION['classAllow'] = 1;
				$error->subtitle = 'PROF_SCHED';
			}
			else
				$error->title = 'PROF_SCHED';
			$error->msg = "There is a conflict on schedule of Professor. (" . $text. " )";
			$error = json_encode($error);

			echo $error;
			exit();
		}

		//time, room and day prob
		$room = explode(",", $_POST['room']);
		$text = "";
		
		$days_num = array();
		$week = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");
		for($i = 0; $i < $len; $i++) {
			$days_num[$i] = array_search($days[$i], $week)+1;
		}

		for($i = 0; $i < $len; $i++) {
			$ts = $timestart[$i];
			$te = $timeend[$i];
			$d = $days_num[$i];
			$r = $room[$i];

			$r = $room[$i] = strToUpper($r);

			if (!empty($r) && $r != " ") {
				$lapSql = mysqli_query($connect, "SELECT class_id FROM classes
					WHERE SUBSTR(class_id,1,5) = '$aysem' AND day_id = '$d' AND room = '$r' AND 
						((timestart >= '$ts' AND timestart < '$te') OR
						(timestart <= '$ts' AND  timeend > '$ts'))
						AND class_id != '$classid' AND status = 1");
				$lapNum = mysqli_num_rows($lapSql);
			}
			else {
				$lapNum = 0;
			}
			
			if($lapNum > 0) {
				if (strpos($text, $days[$i]) === false) {
					if ($text != "")
						$text .= ",";
					$text .= ' '.$daysName[ $days[$i] ];
					$errorArray[$errorCtr++] = $days[$i];
				}
			}
		}

		// if (count($errorArray) && $bypass == 0) {
		if (count($errorArray) && $_SESSION['classAllow'] == 0) {
			if ($classAllow > 0) {
				$error->title = 'CLASS_ALLOW';
				$_SESSION['classAllow'] = 1;
				$error->subtitle = 'CLASS_SCHED';
			}
			else
				$error->title = 'CLASS_SCHED';
			
			$error->msg = "There is a conflict between other class/es. ( " . $text . " )";
			$error->data = $errorArray;
			$error = json_encode($error);

			echo $error;
			exit();
		}

		//insert OR edit
		$maxSlots = $_POST['maxSlots'];

		if($classid == "") { //create
			$nextClassSql = mysqli_query($connect, "SELECT MAX(class_id)+1 FROM classes WHERE SUBSTR(class_id,1,5) = '$aysem'");
			$nextClass = mysqli_fetch_row($nextClassSql)[0];
			if($nextClass == "")
				$nextClass = $aysem.'00001';

			$takenslots = 0;
			$remslots = $maxSlots;
			if($remslots == 0){
				$error->title = 'SLOTS';
				$error->msg = "Do not leave Max Slots field empty";
				$error = json_encode($error);

				echo $error;
				exit();
			}
		}
		else {
			$nextClass = $classid;

			$slotsSql = mysqli_query($connect, "SELECT taken_slots, rem_slots FROM classes WHERE class_id = '$classid'");
			$slotsx = mysqli_fetch_row($slotsSql);

			$takenslots = $slotsx[0];
			$remslots = $slotsx[1];
			$deleteSql = mysqli_query($connect, "DELETE FROM classes WHERE class_id = '$classid'");
		}

		for($i = 0; $i < $len; $i++) {

			$ts = $timestart[$i];
			$te = $timeend[$i];
			$d = $days_num[$i];
			$day = $days[$i];
			$r = $room[$i];
			$text = "";
			if ($r == " ")
				$r = "TBA";

			if ($ts == null)
				$text .= "null, ";
			else 
				$text .= "'$ts', ";

			if ($te == null)
				$text .= "null";
			else 
				$text .= "'$te'";

			$insertSql = mysqli_query($connect, "INSERT INTO classes (class_id, faculty_id, subject_id, section, max_slots, rem_slots, taken_slots, day, timestart, timeend, room, day_id, status) VALUES ('$nextClass', '$faculty', '$subject', '$section', '$maxSlots', '$remslots', '$takenslots', '$day', $text, '$r', '$d', 1)");

			$scheduleSql = mysqli_query($connect, "UPDATE classes SET schedule = 
				CONCAT(CASE WHEN timestart is null THEN 'TBA' 
						ELSE DATE_FORMAT(timestart, '%h:%i %p') END, '-', 
						CASE WHEN timeend is null THEN 'TBA' 
						ELSE DATE_FORMAT(timeend, '%h:%i %p') END, ' ', '$day', ' ', room), 
				class = (SELECT a.subject_name FROM subjects a WHERE a.subject_id = '$subject'),
				unit = (SELECT a.units FROM subjects a WHERE a.subject_id = '$subject') WHERE class_id = '$nextClass' AND day_id = '$d'");
		}	
		
		if ($len == 0) {
			if($room[0] == '')
				$room = 'TBA';
			else
				$room = $room[0];
			$insertSql = mysqli_query($connect, "INSERT INTO classes (class_id, faculty_id, subject_id, section, max_slots, rem_slots, taken_slots, status, room)	VALUES ('$nextClass', '$faculty', '$subject', '$section', '$maxSlots', '$remslots', '$takenslots', 1, '$room')");
			$classSql = mysqli_query($connect, "UPDATE classes SET 
				class = (SELECT a.subject_name FROM subjects a WHERE a.subject_id = '$subject'),
				unit = (SELECT a.units FROM subjects a WHERE a.subject_id = '$subject') WHERE class_id = '$nextClass'");
		}
	}
	else if ($action == "classInfo") {
		$classid = $_POST['classid'];

		$enrolleesNumSql = mysqli_query($connect, "SELECT COUNT(student_id) FROM class_list WHERE class_id = '$classid'");
		$enrolleesNum = mysqli_fetch_row($enrolleesNumSql)[0];

		$studentsNumSql = mysqli_query($connect, "SELECT COUNT(student_id) FROM grades WHERE class_id = '$classid'");
		$studentsNum = mysqli_fetch_row($studentsNumSql)[0];

		$text = "";


		if ($studentsNum > 0)
			$text = "There are total of ".$studentsNum." student/s enrolled in this class. <br/>";
		if ($enrolleesNum > 0)
			$text .= "There are total of ".$enrolleesNum." enrollee/s that currently enrolling this class.";

		if ($text == "") 
			$text = "There is no student or enrollee under this class.";

		echo $text;
	}
	else if ($action == "deleteClass") {
		$classid = $_POST['classid'];

		//$deleteClass = mysqli_query($connect, "DELETE FROM classes WHERE class_id = '$classid'");

		$deleteClass = mysqli_query($connect, "UPDATE classes SET status = 0 WHERE class_id = '$classid'");
		$deleteClassList = mysqli_query($connect, "DELETE FROM class_list WHERE class_id = $classid");
		//$deleteGrades = mysqli_query($connect, "DELETE FROM grades WHERE class_id = $classid");
	}

	// start edit subjects ajax //
	else if ($action == "deleteSubject") {
		$subjectCode = $_POST['subjectCode'];

		$deleteSubjectSql = mysqli_query($connect, "DELETE FROM subjects WHERE subject_name = '$subjectCode'");

		//echo "DELETE FROM subjects WHERE subject_name = '$subjectCode'";
	}
	else if ($action == "saveSubject") {
		$subjectCode = $_POST['subjectCode'];
		$subjectName = $_POST['subjectName'];
		$subjectUnits = $_POST['subjectUnits'];
		$gradschoolid = $_SESSION['gradschoolid'];
		$facultyid = $_SESSION['facultyid'];
		$subjectID = $_POST['subjectID'];

		$subjectCode = strToUpper($subjectCode);
		$subjectCode = str_replace(' ', '', $subjectCode);

		$count = strlen($subjectCode);

		for($i=0;$i<$count;$i++){
			if(is_numeric($subjectCode[$i])){
				$part1 = substr($subjectCode, 0, $i);
				$part2 = substr($subjectCode, $i);
				$subjectCode = $part1.' '.$part2;
				break;
			}
		}
		
		$isSubjectSql = mysqli_query($connect, "SELECT * FROM subjects WHERE subject_name = '$subjectCode' AND subject_id != '$subjectID'");

		if(mysqli_num_rows($isSubjectSql) > 0){
			$isSubject = mysqli_fetch_assoc($isSubjectSql);

			$string = $subjectCode.' is currently existing with a subject name of: '.ucwords(strtolower($isSubject['subject_title']));

			echo $string; exit;
		}

		if ($subjectUnits == '') {
			$subjectUnits = 0;
		}
		$subjectName = strtoupper($subjectName);

		if ($subjectID == 0) { //create
			$insertSubjectSql = mysqli_query($connect, "INSERT INTO subjects (subject_name, subject_title, insertedby, insertedon, units, gradschool_id) VALUES
				('$subjectCode', '$subjectName', '$facultyid', NOW(), '$subjectUnits', '$gradschoolid')");
		}
		else { //edit
			$insertSubjectSql = mysqli_query($connect, "UPDATE subjects SET
				subject_name = '$subjectCode',
				subject_title = '$subjectName',
				units = '$subjectUnits'
				WHERE subject_id = $subjectID");
				
			//Update the class 
			$updateClassSql = mysqli_query($connect, "UPDATE classes SET class = '$subjectCode' WHERE subject_id = $subjectID");
		}
	}

	// start edit faculty ajax //
	else if ($action == "selectDesignationFaculty") {
		$facultyid = $_POST['facultyid'];
		$designationid = $_POST['designationid'];
		$gradschoolid = $_SESSION['gradschoolid'];

		$isExistingSql = mysqli_query($connect, "SELECT * FROM faculty_designation WHERE faculty_id = '$facultyid' AND designation_id = '$designationid' AND gradschool_id = '$gradschoolid'");

		// $x = "SELECT * FROM faculty_designation WHERE faculty_id = '$facultyid' AND designation_id = '$designationid' AND gradschool_id = '$gradschoolid'";
		// echo $x; exit;

		if(mysqli_num_rows($isExistingSql) > 0){
			echo 'error'; exit;
		}
	}
	else if ($action == 'selectFaculty') {
		$facultyid = $_POST['facultyid'];

		$facultyInfoSql = mysqli_query($connect, "SELECT * FROM faculty WHERE faculty_id = '$facultyid'");
		$facultyInfo = mysqli_fetch_assoc($facultyInfoSql);

		echo $facultyInfo['lastname'].'|'.$facultyInfo['firstname'].'|'.$facultyInfo['middlename'];
	}
	else if ($action == 'createFaculty') {
		$facultyid = $_POST['facultyid'];
		$designationid = $_POST['designationid'];
		$gradschoolid = $_SESSION['gradschoolid'];

		$insertInFacDesignationSql = mysqli_query($connect, "INSERT INTO faculty_designation (faculty_id, designation_id, gradschool_id) VALUES ('$facultyid', '$designationid', '$gradschoolid')");
	}

	// applicants //
	else if ($action == "applicants"){
		$process = $_POST['process'];
		$applicantid = $_POST['applicantid'];
		
		if($process == 'session'){
			$_SESSION['applicantid'] = $applicantid;
		}
	}
	else if ($action == 'acceptApplicant'){
		$applicantid = $_SESSION['applicantid'];
		$facultyId = $_SESSION['facultyid'];
		$appGender = $_SESSION['appGender'];

		$getGradSchoolSql = mysqli_query($connect, "SELECT graduate_school FROM applicant WHERE applicant_id = $applicantid");
		$getGradSchool = mysqli_fetch_row($getGradSchoolSql);

		$updateApplicant = mysqli_query($connect, "UPDATE applicant SET status = 1 WHERE applicant_id = '$applicantid'");

		$docuRequiredSql = mysqli_query($connect, "SELECT DISTINCT document_id FROM document_required WHERE gradschool_id = $getGradSchool[0] OR gradschool_id is null");

		while($docuRequired = mysqli_fetch_row($docuRequiredSql)){
			if($appGender == 'M' && $docuRequired[0] == 2)
				continue;
			$insertDocuPassed = mysqli_query($connect, "INSERT INTO document_passed (applicant_id, document_id) VALUES ('$applicantid', $docuRequired[0])");
		}
	}
	else if ($action == "generateNumber"){
		$applicantid = $_SESSION['applicantid'];
		$facultyId = $_SESSION['facultyid'];
		$process = $_POST['process'];

		if ($process == 'generate'){
			$prefixSql = mysqli_query($connect, "SELECT CONCAT(SUBSTR(b.aysem,1,4), d.prefix), b.aysem
			 					FROM applicant a
			 					JOIN activities b ON b.gradschool_id = a.graduate_school
			 					JOIN graduate_schools c ON c.gradschool_id = b.gradschool_id
			 					JOIN programs e ON e.program_id = a.program
			 					JOIN graduate_schools_prefix d ON d.gradschool_id = c.gradschool_id AND d.program_type = e.program_type
			 					WHERE a.applicant_id = '$applicantid' AND activity_id = 1");
			$prefixRow = mysqli_fetch_row($prefixSql);
			$prefix = $prefixRow[0];
			$aysem = $prefixRow[1];

			$selectMaxStudentSql = mysqli_query($connect, "SELECT MAX(student_id) FROM students WHERE 
				SUBSTR(student_id,1,6) = '$prefix'");
			$selectMaxStudent = mysqli_fetch_row($selectMaxStudentSql);
			$nextStudent = intval($selectMaxStudent[0])+1;

			if($nextStudent == 1)
				$nextStudent = $prefix.'001';
			$_SESSION['aysem'] = $aysem;
			$_SESSION['nextStudent'] = $nextStudent;
			echo $nextStudent; exit;	
		}

		else if ($process == 'save'){
			$nextStudent = $_SESSION['nextStudent'];
			$aysem = $_SESSION['aysem'];
			$applicantInfoSql = mysqli_query($connect, "SELECT * FROM applicant_personal WHERE applicant_id = '$applicantid'");
			$applicantInfo = mysqli_fetch_assoc($applicantInfoSql);
			$firstname = $applicantInfo['firstname'];
			extract($applicantInfo);
			$programSql = mysqli_query($connect, "SELECT program FROM applicant WHERE applicant_id = '$applicantid'");
			$program = mysqli_fetch_row($programSql)[0];

			$curriculumSql = mysqli_query($connect, "SELECT curriculum_id, gradschool_id FROM programs WHERE program_id = '$program'");
			$curriculum = mysqli_fetch_row($curriculumSql);

			$name = strtoupper($lastname.', '.$firstname.' '.substr($middlename,0,1).'.');

			$newStudentSql = mysqli_query($connect, "INSERT INTO students
			 					(student_id, name, firstname, lastname, middlename, gender, contactno, address,
			 					email, program_id, current_sem, registration, curriculum_id) VALUES
		 						('$nextStudent', '$name', '$firstname', '$lastname', '$middlename', 
		 						'$gender', '$contact', '$address', '$email', '$program', '$aysem', 'N', '$curriculum[0]')");

			$newStudentTermsSql = mysqli_query($connect, "INSERT INTO studentterms (student_id, aysem, gradschool_id, student_type, insertedby, insertedon) VALUES ('$nextStudent', '$aysem', '$curriculum[1]', 'N', '$facultyId', now())");

			$updateApplicant = mysqli_query($connect, "UPDATE applicant SET student_id = $nextStudent WHERE applicant_id = '$applicantid'");

			$updateUser = mysqli_query($connect, "UPDATE users SET user_id = '$nextStudent', user_level = 2, login = '$nextStudent', pword = '$nextStudent' WHERE user_id = '$applicantid'");

			$updateDocuPassedSql = mysqli_query($connect, "UPDATE document_passed SET student_id = '$nextStudent' WHERE applicant_id = '$applicantid'");
			
			echo $nextStudent; exit;
		}
	}

	// students //
	else if ($action == "students"){
		$process = $_POST['process'];
		$studentid = $_POST['studentid'];
		
		if($process == 'session'){
			$_SESSION['studentid'] = $studentid;
		}
	}
	else if ($action == "updatePayType"){
		$studentid = $_SESSION['studentid'];
		$paytype = $_POST['paymentType'];

		$updateSql = mysqli_query($connect, "UPDATE applicant SET paytype = $paytype WHERE student_id = $studentid");

		if(mysqli_error($connect)){
			echo 'error';
			exit;
		}
	}
	else if ($action == "acceptEnrollee"){
		$studentid = $_SESSION['studentid'];
		$typeOfPayment = $_POST['typeOfPayment'];
		$additional = $_POST['addFee'];
    	$currentsem = $_SESSION['aysem'];
		$facultyid = $_SESSION['facultyid'];
		//$disp_amount 

		$totalUnitsSql = mysqli_query($connect, "SELECT sum(c.unit) from class_list cl join classes c on cl.class_id = c.class_id where cl.student_id = '$studentid'");
		$totalUnits = mysqli_fetch_row($totalUnitsSql);

		$assessmentExistSql = mysqli_query($connect, "SELECT * FROM assessment_student WHERE aysem = '$currentsem' AND student_id = '$studentid'");
		$assessmentExistNumber = mysqli_num_rows($assessmentExistSql);

		$assessmentBalance = mysqli_fetch_assoc($assessmentExistSql);

		if($assessmentExistNumber > 0){
			$additionalBalance = $assessmentBalance['balance_amount'] + $additional;
			$newTotalAmount = $assessmentBalance['total_amount'] + $additional;
		}

		if($assessmentBalance['paid_status'] != 'NP' && $assessmentExistNumber > 0){
			$additionalBalance = $assessmentBalance['balance_amount'] + $additional;
			$newTotalAmount = $assessmentBalance['total_amount'] + $additional;
		}

		if($assessmentExistNumber > 0){
			$updateBalanceSql = mysqli_query($connect, "UPDATE assessment_student SET balance_amount = $additionalBalance, total_amount = $newTotalAmount");
		}

		$updateInfoQuery = "UPDATE class_list SET status='1', validatedon = now(), validatedby = '$facultyid' WHERE student_id = '$studentid' AND status = 0";
		$updateInfo = mysqli_query($connect, $updateInfoQuery);


		if ($assessmentExistNumber == 0) {

			$registrationSql = mysqli_query($connect, "SELECT * FROM students a JOIN programs b ON a.program_id = b.program_id WHERE a.student_id = '$studentid'");
			$registration = mysqli_fetch_assoc($registrationSql);
			
			if($registration['program_type'] == 'M'){
				$tuitionSql = mysqli_query($connect, "SELECT masteral_amount FROM allfees WHERE feetype='T'");
				$tuition = mysqli_fetch_row($tuitionSql);
				
				if($registration['registration'] == 'O')
					$miscSql = mysqli_query($connect, "SELECT sum(masteral_amount) FROM allfees WHERE feetype = 'M'");

				else if($registration['registration'] == 'N')
					$miscSql = mysqli_query($connect, "SELECT sum(masteral_amount) FROM allfees WHERE feetype LIKE '%M%'");

				$misc = mysqli_fetch_row($miscSql);

				$otherSql = mysqli_query($connect, "SELECT masteral_amount FROM allfees WHERE feetype='O'");
				$other = mysqli_fetch_row($otherSql);
				$otherFee = $other[0]+$additional;

				$tuitionFee = $tuition[0]*$totalUnits[0];

				$totalFees = $tuitionFee+$misc[0]+$otherFee;

				$insertAssessmentSql = mysqli_query($connect, "INSERT INTO assessment_student SET student_id='$studentid', total_amount='$totalFees', paid_status='NP', assessedby='$facultyid', aysem='$currentsem', tuition_amount='$tuitionFee', misc_amount='$misc[0]', other_amount='$otherFee', units='$totalUnits[0]', paid_installment = 1");	

				$GetIDSql = mysqli_query($connect, "SELECT id FROM assessment_student WHERE student_id='$studentid' AND aysem='$currentsem'");
				$GetID = mysqli_fetch_row($GetIDSql);
				$ID= 'PLM'.$GetID[0];
				$UpdateAssessmentSql = mysqli_query($connect, "UPDATE assessment_student SET assessment_id='$ID' WHERE id = '$GetID[0]'");
			}
		}

		$updateSql = mysqli_query($connect, "UPDATE assessment_student SET paytype = $typeOfPayment WHERE student_id = $studentid");
	}
	else if ($action == 'printSession'){
		$result = $_POST['result'];
		$_SESSION['result'] = $result;
		echo $result;
	}
	else if ($action == "defaultClassAllow") {
		$_SESSION['classAllow'] = 0;
	}

	// activities //
	else if ($action == 'saveActivity'){
		$activityArray = $_POST['activityArray'];
		$timestartArray = $_POST['timestartArray'];
		$timeendArray = $_POST['timeendArray'];
		$gradschoolid = $_SESSION['gradschoolid'];
		$aysem = $_POST['aysem'];

		$activityArray = explode(',', $activityArray);
		$timestartArray = explode(',', $timestartArray);
		$timeendArray = explode(',', $timeendArray);

		$activityNameArray = array('', 'Application (for new students)', 'Change of Grades', 'Enrollment', 'Encoding of Grades', 'Late Enrollment', 'Add/Drop');

		$ctr = count($activityArray);

		for($i=0;$i<$ctr;$i++){

			$activityid = $activityArray[$i];
			$activityname = $activityNameArray[$activityid];
			$timestart = $timestartArray[$i];
			$timeend = $timeendArray[$i];

			if ($timestart == ""){
				$timestart = "0000-00-00";
			}
			if ($timeend == ""){
				$timeend = "0000-00-00";
			}

			$deleteActivitiesSql = mysqli_query($connect, "DELETE FROM activities WHERE activity_id = '$activityid' AND gradschool_id = $gradschoolid AND aysem = '$aysem'");
			$insertActivitesSql = mysqli_query($connect, "INSERT INTO activities (activity_id, activity_name, aysem, datestart, dateend, gradschool_id) VALUES ('$activityid', '$activityname', '$aysem', '$timestart', '$timeend', '$gradschoolid')");
		}
	}
	else if ($action == 'viewActivity'){
		$aysem = $_POST['aysem'];
		$gradschoolid = $_SESSION['gradschoolid'];

		$activityArray = array();
		$timestartArray = array();
		$timeendArray = array();

		$activitiesSql = mysqli_query($connect, "SELECT * FROM activities WHERE gradschool_id = $gradschoolid AND aysem = '$aysem' ORDER BY activity_id");

		while ($activities = mysqli_fetch_assoc($activitiesSql)){

			array_push($activityArray, $activities['activity_id']);
			array_push($timestartArray, $activities['datestart']);
			array_push($timeendArray, $activities['dateend']);
		}

		echo implode(',',$activityArray).'|'.implode(',',$timestartArray).'|'.implode(',',$timeendArray);
	}
	else if($action == 'saveNewCourseGroup'){
		$courseGroup = $_POST['courseGroup'];
		$process = $_POST['process'];

		if($process == 0){ // old course group
			

		}
		else if($process == 1){
			$newGroupCode = $_POST['newGroupCode'];
			$newGroupTitle = $_POST['newGroupTitle'];

			$isExistingSql = mysqli_query($connect, "SELECT * FROM course_group WHERE group_id = UCASE('$newGroupCode')");

			echo "SELECT * FROM course_group WHERE group_id = UCASE('$newGroupCode')"; exit;
		}

		$courseGroupNameSql = mysqli_query($connect, "SELECT group_title FROM course_group WHERE group_id = '$courseGroup'");
		$coruseGroupName = mysqli_fetch_row($courseGroupNameSql)[0];

		if(isset($_SESSION['courseGroup'])){
			$courseGroupArray = $_SESSION['courseGroupArray'];
			$courseGroupnameArray = $_SESSION['courseGroupNameArray'];
			array_push($courseGroupArray, $courseGroup);
			array_push($courseGroupNameArray, $courseGroupName);

			$_SESSION['courseGroupArray'] = $courseGroupArray;				
		}
		else{
			$courseGroupArray = array();
			$courseGroupNameArray = array();
			array_push($courseGroupArray, $courseGroup);
			array_push($courseGroupNameArray, $courseGroupName);

			$_SESSION['courseGroupArray'] = $courseGroupArray;
		}
		print_r($courseGroupArray); exit;
	}
	else if($action == 'sessionClass'){
		$classid = $_POST['classid'];
		$_SESSION['classid'] = $classid;

		exit;
	}
	else if($action == 'encodeGrade'){
		$classid = $_SESSION['classid'];
		$studentid = $_POST['studentid'];
		$grade = $_POST['grade'];

		$studentid = explode(',', $studentid);
		$studentidSize = sizeof($studentid);

		$grade = explode(',', $grade);

		//echo "UPDATE grades SET grade = '$grade[1]' WHERE class_id = $classid AND student_id = 201761002";
		//echo $grade[1]; exit;
		for($i=0;$i<$studentidSize;$i++){
			
			if($grade[$i] == 0 && $grade[$i] != 'INC' && $grade[$i] != 'DU' && $grade[$i] != 'DO')
				$tGrade = null;
			else
				$tGrade = $grade[$i];
				
			$updateGradeSql = mysqli_query($connect, "UPDATE grades SET grade = '$tGrade' WHERE class_id = $classid AND student_id = $studentid[$i]");
			// 	echo "UPDATE grades SET grade = '$grade[$i]' WHERE class_id = $classid AND student_id = $studentid[$i]";
		}
	}
	else if($action == 'finalGrade'){
		$classid = $_SESSION['classid'];
		$aysem = SUBSTR($classid,0,5); 
		$studentid = $_POST['studentid'];
		$grade = $_POST['grade'];

		$studentid = explode(',', $studentid);
		$studentidSize = sizeof($studentid);

		$grade = explode(',', $grade);

		for($i=0;$i<$studentidSize;$i++){

			$checkBalanceSql = mysqli_query($connect, "SELECT balance_amount FROM assessment_student WHERE student_id = $studentid[$i] AND aysem = $aysem AND balance_amount != 0");
			$x = mysqli_num_rows($checkBalanceSql);
			
			if($grade[$i] == null || $x > 0)
				$tGrade = null;
			else if($grade[$i] == 'INC' || $grade[$i] == 'DU' || $grade[$i] == 'DO')
				$tGrade = 5;
			else
				$tGrade = $grade[$i];
				
			$updateGradeSql = mysqli_query($connect, "UPDATE grades SET grade_value = '$tGrade' WHERE class_id = $classid AND student_id = $studentid[$i]");
			// 	echo "UPDATE grades SET grade = '$grade[$i]' WHERE class_id = $classid AND student_id = $studentid[$i]";
		}
	}
	else if($action == 'checkCode'){
		$classid = $_SESSION['classid'];

		$checkSql = mysqli_query($connect, "SELECT * FROM classes WHERE class_id = $classid AND changecode is null");
		//echo "SELECT * FROM grades WHERE class_id = $classid AND changecode == null"; exit;

		if(mysqli_num_rows($checkSql) > 0){
			echo 'error'; exit;
		}

		echo 'success'; exit;
	}
	else if($action == 'changeCode'){
		$classid = $_SESSION['classid'];
		$changeCode = $_POST['changeCode'];

		$checkSql = mysqli_query($connect, "SELECT * FROM grades a JOIN classes b ON b.class_id = a.class_id WHERE b.class_id = $classid AND a.changecode != '$changeCode'");

		if(mysqli_num_rows($checkSql)){
			echo 'error'; exit;
		}
	}
	else if($action == 'allowCode'){
		$classid = $_SESSION['classid'];
		$changeCode = $_POST['changeCode'];

		$updateSql = mysqli_query($connect, "UPDATE classes SET changecode = '$changeCode' WHERE class_id = '$classid'");
	}
	else if($action == 'removeAllow'){
		$classid = $_SESSION['classid'];

		$updateSql = mysqli_query($connect, "UPDATE classes SET changecode = null WHERE class_id = '$classid'");
	}
?>
