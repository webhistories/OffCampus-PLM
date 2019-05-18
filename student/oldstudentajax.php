<?php

	include '../config.php';

	$classid = $_POST['classid'];
	$action = $_POST['action'];
	$curriculum = $_SESSION['curriculum'];
	$studentid = $_SESSION['studentid'];
	$gradschoolid = $_SESSION['gradschoolid'];

	if($action == 'Enlist'){
		$aysemSql = mysqli_query($connect, "SELECT c.aysem FROM students a JOIN programs b ON a.program_id = b.program_id
											JOIN activities c ON c.gradschool_id = b.gradschool_id 
											WHERE a.student_id = '$studentid' AND c.activity_id = 3");
		$aysem = mysqli_fetch_row($aysemSql)[0];

		$checkSql = mysqli_query($connect, "SELECT * FROM class_list WHERE status = '-1' AND student_id = '$studentid' AND SUBSTR(class_id,1,5) = '$aysem'");
		$check = mysqli_num_rows($checkSql);

		$approveClassesSql = mysqli_query($connect, "SELECT * FROM class_list WHERE status = 1 AND student_id = '$studentid' AND SUBSTR(class_id,1,5) = '$aysem'");
		$approveClassesNum = mysqli_num_rows($approveClassesSql);

		if($check > 0){
			$resetSql = mysqli_query($connect, "DELETE FROM class_list WHERE student_id = '$studentid' AND status = '-1' AND SUBSTR(class_id,1,5) = '$aysem'");
		}

		if ($approveClassesNum > 0) {
			$resetSql = mysqli_query($connect, "UPDATE assessment_student SET status = 0 WHERE student_id = '$studentid' AND status = 1 AND SUBSTR(class_id,1,5) = '$aysem'");
        	$deleteAssessmentSql = mysqli_query($connect, "DELETE FROM assessment_student WHERE student_id = '$studentid' AND aysem = '$aysem'");
		}

		//error1//
		$result = mysqli_query($connect, 
						"SELECT s.subject_title FROM classes c 
							JOIN curriculum cu ON c.subject_id = cu.subject_id
							JOIN subjects s ON s.subject_id = cu.prerequisites
                            WHERE c.class_id = '$classid' 
	                            AND cu.curriculum_id = '$curriculum' 
	                            AND (cu.prerequisites NOT IN 
	                            	(SELECT classes.subject_id FROM grades                         
										JOIN classes ON grades.class_id = classes.class_id                    
										WHERE grades.student_id = '$studentid' AND grades.remarks = 'PASSED'))");
		$x = mysqli_num_rows($result);
		if($x){
			echo "A prerequsite subject is not yet finished";
			exit;
		}

		//error2//
		$subjectIdQuery = "SELECT subject_id FROM classes WHERE class_id = '$classid'";
		$subjectIdSql = mysqli_query($connect, $subjectIdQuery);
		$subjectId = mysqli_fetch_row($subjectIdSql);
		$subjectid = $subjectId[0];
		$sameSubjectQuery = "SELECT c.class_id
							FROM class_list cl
							JOIN classes c ON cl.class_id = c.class_id 
							WHERE c.subject_id = '$subjectId[0]' AND cl.student_id = '$studentid' AND cl.status != 4";
		$sameSubjectSql = mysqli_query($connect, $sameSubjectQuery);
		$sameSubjectNumber = mysqli_num_rows($sameSubjectSql);

		if($sameSubjectNumber){
			echo "A class is enlisted under the same subject";
			exit;
		}

		//error3//
		$unitsSql = mysqli_query($connect, "SELECT c.unit
						FROM class_list cl
						JOIN classes c ON cl.class_id = c.class_id
						WHERE cl.student_id = '$studentid' AND cl.status IN ('0','1')
						GROUP BY c.class_id");
		$unitsNumber = mysqli_num_rows($unitsSql);

		$totalUnitsHave = 0;
		for($i = 0; $i < $unitsNumber; $i++) {
			$units = mysqli_fetch_row($unitsSql)[0];	
			$totalUnitsHave += $units;
		}

		$maxUnitsQuery = "SELECT student_units FROM graduate_schools WHERE gradschool_id = '$gradschoolid'";
		$maxUnitsSql = mysqli_query($connect, $maxUnitsQuery);
		$maxUnits = mysqli_fetch_row($maxUnitsSql);

		$currentUnitsQuery = "SELECT c.unit FROM classes c WHERE c.class_id = '$classid'";
		$currentUnitsSql = mysqli_query($connect, $currentUnitsQuery);
		$currentUnits = mysqli_fetch_row($currentUnitsSql);

		if(($totalUnitsHave + $currentUnits[0]) > $maxUnits[0]){
			echo "Exceeded Max Units";
			exit;
		}
		//error4//
			$currentScheduleQuery = "SELECT class_id, day, timestart, timeend FROM classes WHERE class_id = '$classid'";
			$currentScheduleSql = mysqli_query($connect, $currentScheduleQuery);
			$currentScheduleNumber = mysqli_num_rows($currentScheduleSql);

			$aysem = $_SESSION['aysem'];

			for($i=0;$i<$currentScheduleNumber;$i++){
				$currentSchedule = mysqli_fetch_row($currentScheduleSql);

				$conflictScheduleQuery = "SELECT cl.class_id, s.subject_title, c.timestart, c.timeend, c.day
											FROM class_list cl
											JOIN classes c ON cl.class_id = c.class_id
											JOIN subjects s ON s.subject_id = c.subject_id
											WHERE cl.student_id = '$studentid' AND SUBSTR(cl.class_id,1,5) = '$aysem' AND  
												((c.timestart <= '$currentSchedule[2]' AND c.timeend > '$currentSchedule[2]') OR
												(c.timestart < '$currentSchedule[3]' AND c.timeend >= '$currentSchedule[3]') 
													OR
												(c.timestart >= '$currentSchedule[2]' AND c.timeend <= '$currentSchedule[3]'))
												AND c.day = '$currentSchedule[1]'";

				$conflictScheduleSql = mysqli_query($connect, $conflictScheduleQuery);
				$conflictScheduleNumber = mysqli_num_rows($conflictScheduleSql);

				if($conflictScheduleNumber){
					echo "There is a conflict in schedule.";
					exit;
				}
			}

		//error5//
			$courseGroupSql = mysqli_query($connect, "SELECT cu.group_id, required_subjects, cg.group_title FROM curriculum cu
				JOIN course_group cg ON cg.group_id = cu.group_id
				WHERE curriculum_id = '$curriculum' AND subject_id = '$subjectid'");
			$courseGroup = mysqli_fetch_row($courseGroupSql);

			if($courseGroup[1]) {
				$classListExistSql = mysqli_query($connect, "SELECT COUNT(c.subject_id) FROM class_list cl 
					JOIN classes c ON c.class_id = cl.class_id 
					JOIN curriculum cu ON cu.subject_id = c.subject_id
					WHERE cl.student_id = '$studentid' AND cu.curriculum_id = '$curriculum' AND cu.group_id = '$courseGroup[0]'");
				$classListExist = mysqli_fetch_row($classListExistSql);

				$gradesExistSql = mysqli_query($connect, "SELECT COUNT(c.subject_id) FROM grades g
					JOIN classes c ON c.class_id = g.class_id
					JOIN curriculum cu ON cu.subject_id = c.subject_id
					WHERE g.student_id = '$studentid' AND cu.curriculum_id = '$curriculum' AND cu.group_id = '$courseGroup[0]' AND g.grade_value != 5");
				$gradesExist = mysqli_fetch_row($gradesExistSql);

				$total = $classListExist[0] + $gradesExist[0];

				if($total >= $courseGroup[1]){
					echo "Exceeded number of subjects in $courseGroup[2] group.";
					exit;
				}
			}
		
		$approveQuery = "SELECT status FROM class_list WHERE student_id = '$studentid'";
        $approveSql = mysqli_query($connect, $approveQuery);
        $approve = mysqli_fetch_row($approveSql);

        if($approve[0] == 1){
        	$updateApprovedQuery = "UPDATE class_list SET status = 0 WHERE student_id = '$studentid'";
        	$updateApprovedSql = mysqli_query($connect, $updateApprovedQuery);
        }
        
		$insertClassListQuery = "INSERT INTO class_list (class_id, student_id, status, insertedon)
										VALUES ('$classid', '$studentid', 0, now())";
		$inserClassList = mysqli_query($connect, $insertClassListQuery);	
		
		$subjectCodeSql = mysqli_query($connect, "SELECT CONCAT(class,'-',section) FROM classes WHERE class_id = $classid");
		$subjectCode = mysqli_fetch_row($subjectCodeSql);

		//echo $subjectCode[0]; exit;

		compute();
	}
	else if($action == 'Remove'){
		$approveQuery = "SELECT status, class_id FROM class_list WHERE student_id = '$studentid'";
        $approveSql = mysqli_query($connect, $approveQuery);
        $approve = mysqli_fetch_row($approveSql);

        if($approve[0] == 1){
        	$updateApprovedQuery = "UPDATE class_list SET status = 0 WHERE student_id = '$studentid' AND status = 1";
        	$updateApprovedSql = mysqli_query($connect, $updateApprovedQuery);

        	$aysem = SUBSTR($approve[1],0,5);
        	$deleteAssessmentSql = mysqli_query($connect, "DELETE FROM assessment_student WHERE student_id = '$studentid' AND aysem = '$aysem'");
        }
    	$removeClassQuery = "DELETE FROM class_list WHERE class_id = '$classid' AND student_id = '$studentid'";
    	$removeClassSql = mysqli_query($connect, $removeClassQuery);

    	compute();
	}
	else if($action == 'Drop'){
		$updateQuery = "UPDATE class_list SET status=3, droppedon=now() WHERE student_id = '$studentid' AND class_id = '$classid'";
		$updateSql = mysqli_query($connect, $updateQuery);
	}

	function compute(){
		$studentid = $_SESSION['studentid'];
		//$typeOfPayment = $_POST['typeOfPayment'];
		//$additional = $_POST['addFee'];
    	$currentsem = $_SESSION['aysem'];
		//$facultyid = $_SESSION['facultyid'];
		//$disp_amount 
		$connect = mysqli_connect('localhost', 'root', 'password', 'gp_test_copy');
		$additional = 0;

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

		// $updateInfoQuery = "UPDATE class_list SET status='1', validatedon = now(), validatedby = '$facultyid' WHERE student_id = '$studentid' AND status = 0";
		$updateInfoQuery = "UPDATE class_list SET status='1', validatedon = now(), WHERE student_id = '$studentid' AND status = 0";
		$updateInfo = mysqli_query($connect, $updateInfoQuery);


		if ($assessmentExistNumber == 0) {
			$registrationSql = mysqli_query($connect, "SELECT * FROM students a JOIN programs b ON a.program_id = b.program_id WHERE a.student_id = '$studentid'");

			$registration = mysqli_fetch_assoc($registrationSql);
			
			if($registration['program_type'] == 'M'){
				$tuitionSql = mysqli_query($connect, "SELECT masteral_amount FROM allfees WHERE feetype='T'");
				//echo "SELECT masteral_amount FROM allfees WHERE feetype='T'"; exit;
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
				//echo $tuitionFee; exit;
				$totalFees = $tuitionFee+$misc[0]+$otherFee;

				$insertAssessmentSql = mysqli_query($connect, "INSERT INTO assessment_student SET student_id='$studentid', total_amount='$totalFees', paid_status='NP', aysem='$currentsem', tuition_amount='$tuitionFee', misc_amount='$misc[0]', other_amount='$otherFee', units='$totalUnits[0]', paid_installment = 1");	

				// $insertAssessmentSql = mysqli_query($connect, "INSERT INTO assessment_student SET student_id='$studentid', total_amount='$totalFees', paid_status='NP', assessedby='$facultyid', aysem='$currentsem', tuition_amount='$tuitionFee', misc_amount='$misc[0]', other_amount='$otherFee', units='$totalUnits[0]', paid_installment = 1");

				$GetIDSql = mysqli_query($connect, "SELECT id FROM assessment_student WHERE student_id='$studentid' AND aysem='$currentsem'");
				$GetID = mysqli_fetch_row($GetIDSql);
				$ID= 'PLM'.$GetID[0];
				$UpdateAssessmentSql = mysqli_query($connect, "UPDATE assessment_student SET assessment_id='$ID' WHERE id = '$GetID[0]'");
			}
		}

		// $updateSql = mysqli_query($connect, "UPDATE assessment_student SET paytype = $typeOfPayment WHERE student_id = $studentid");
	}
?>