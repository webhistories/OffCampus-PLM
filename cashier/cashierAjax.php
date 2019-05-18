<?php

	include '../config.php';

	$action = $_POST['action'];

	if($action == 'sessionID'){
		$studentid = $_POST['studentid'];
		$_SESSION['studentid'] = $studentid;

		$studentSql = mysqli_query($connect, "SELECT * FROM students WHERE student_id = $studentid");
		if(mysqli_num_rows($studentSql) == 0){
			echo 'error'; exit;
		}

	}
	else if($action == 'sessionOR'){
		$receipt = $_POST['receipt'];
		$_SESSION['receipt'] = $receipt;

		$isReceiptExistingSql = mysqli_query($connect, "SELECT * FROM receipt WHERE or_number = '$receipt'");

		if(mysqli_num_rows($isReceiptExistingSql) == 0){
			echo 'error'; exit;
		}
	}
	else if($action == 'printOR'){
		$studentid = $_SESSION['studentid'];
		$currentSem = $_SESSION['currentSem'];
		$facultyid = $_SESSION['facultyid'];

		$paymentMethod = $_POST['paymentMethod'];
		$paymentType = $_POST['paymentType'];
		$inputOR = $_POST['inputOR'];
		$cashTendered = $_POST['cashTendered'];
		$amountToBePaid = $_POST['amountToBePaid'];
		$flag = false;

		$isReceiptExistingSql = mysqli_query($connect, "SELECT * FROM receipt WHERE or_number = '$inputOR'");
		
		if(mysqli_num_rows($isReceiptExistingSql) > 0){
			echo 'orError'; exit;
		}

		if($paymentType == 'PP'){
			if($cashTendered > $amountToBePaid){
				$paymentType = 'FP';
			}			
		}

		if($paymentType == 'FP'){
			$getAssessmentSql = mysqli_query($connect, "SELECT * FROM assessment_student WHERE student_id = $studentid AND aysem = $currentSem AND paid_date IS NULL");

			$getAssessment = mysqli_fetch_row($getAssessmentSql);
			$total = $getAssessment[4]-$_SESSION['lastPaid'];
			$updateAssessmentSql = mysqli_query($connect, "UPDATE assessment_student SET paid_status = 'FP', paid_amount = $total, paid_date = now(), paid_type = '$paymentMethod', or_number = '$inputOR', balance_amount = 0 WHERE id = $getAssessment[0]");


			$insertNewReceiptSql = mysqli_query($connect, "INSERT INTO receipt SET or_number = $inputOR, printedby = '$facultyid', printedon = now(), assessment_id = '$getAssessment[1]'");

			$flag = true;
		}

		if($paymentType == 'PP'){
			$getAssessmentSql = mysqli_query($connect, "SELECT * FROM assessment_student WHERE student_id = $studentid AND aysem = $currentSem AND paid_date IS NULL");

			$getAssessment = mysqli_fetch_row($getAssessmentSql);

			$balanceAmount = $amountToBePaid - $cashTendered;

			$updateAssessmentSql = mysqli_query($connect, "UPDATE assessment_student SET paid_status = 'PP', paid_amount = $cashTendered, paid_date = now(), paid_type = '$paymentMethod', or_number = '$inputOR', balance_amount = '$balanceAmount' WHERE student_id = $studentid AND aysem = $currentSem AND paid_date IS NULL");

			$insertNewReceiptSql = mysqli_query($connect, "INSERT INTO receipt SET or_number = $inputOR, printedby = '$facultyid', printedon = now(), assessment_id = '$getAssessment[1]'");

			// $getAssessmentDetailsSql = mysqli_query($connect)
			if($getAssessment[9] == '')
				$adddrop_amount = 0.00;
			else
				$adddrop_amount = $getAssessment[9];

			if($getAssessment[10] == '')
				$disp_amount = 0.00;
			else
				$disp_amount = $getAssessment[10];

			if($getAssessment[11] == '')
				$disc_amount = 0.00;
			else
				$disc_amount = $getAssessment[11];

			$nextAssessment = intval($getAssessment[0])+1;
			$nextInstallment = intval($getAssessment[13])+1;

			$insertAssessmentSql = mysqli_query($connect, "INSERT INTO assessment_student 
					SET assessment_id = CONCAT('PLM','$nextAssessment'),
						student_id = $studentid,
						aysem = $currentSem,
						total_amount = '$getAssessment[4]',
						tuition_amount = '$getAssessment[5]',
						units = '$getAssessment[6]',
						misc_amount = '$getAssessment[7]',
						other_amount = '$getAssessment[8]',
						adddrop_amount = '$adddrop_amount',
						disp_amount = '$disp_amount',
						disc_amount = '$disc_amount',
						paid_installment = '$nextInstallment',
						balance_amount = $balanceAmount
				");

			$flag = true;
		}

		if($flag){
			$getClassListSql = mysqli_query($connect, "SELECT a.class_id FROM class_list a LEFT JOIN grades b ON b.class_id = a.class_id WHERE b.class_id IS NULL AND a.student_id = '$studentid' AND SUBSTR(a.class_id,1,5) = $currentSem");

			while($getClassList = mysqli_fetch_row($getClassListSql)){
				$insertNewGradeSql = mysqli_query($connect, "INSERT INTO grades SET student_id = '$studentid', class_id = '$getClassList[0]'");

				$updateClassesSql = mysqli_query($connect, "UPDATE classes SET taken_slots = (taken_slots+1), rem_slots = (rem_slots-1) WHERE class_id = '$getClassList[0]'");
			}
		}
	}
?>
