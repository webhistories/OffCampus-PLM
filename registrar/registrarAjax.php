<?php

	include '../config.php';

	$action = $_POST['action'];
	if ($action == "applicant"){
		$process = $_POST['process'];
		$applicantid = $_POST['applicantid'];
		
		if($process == 'session'){
			$_SESSION['applicantid'] = $applicantid;
			echo ''; exit;
		}
	}
	else if ($action == "student"){
		$process = $_POST['process'];
		$studentid = $_POST['studentid'];
		
		if($process == 'session'){
			$_SESSION['studentid'] = $studentid;
			echo ''; exit;
		}
	}
	else if ($action == 'saveDocu'){
		$checkArray = $_POST['checkArray'];
		$type = $_POST['type'];

		if ($type == 'applicant'){
			$applicantid = $_SESSION['applicantid'];

			if($checkArray != '')
				$checkArray = explode(',', $checkArray);

			//echo ($checkArray); exit;
			if($checkArray == ''){
				echo 'error'; 
				exit;
			}

			for($i=0;$i<count($checkArray);$i++){
				$updateDocuPassedSql = mysqli_query($connect, "UPDATE document_passed SET status = 1, date_submitted = curdate() WHERE applicant_id = '$applicantid' AND document_id = '".$checkArray[$i]."'");
			}

			//if(count($checkArray) > 1){
				$updateApplicantSql = mysqli_query($connect, "UPDATE applicant SET documents = 1 WHERE applicant_id = '$applicantid'");
			//}	
		}
		else{
			$studentid = $_SESSION['studentid'];

			if($checkArray != '')
				$checkArray = explode(',', $checkArray);

			//echo ($checkArray); exit;
			if($checkArray == ''){
				echo 'error'; 
				exit;
			}

			for($i=0;$i<count($checkArray);$i++){
				$updateDocuPassedSql = mysqli_query($connect, "UPDATE document_passed SET status = 1, date_submitted = curdate() WHERE student_id = '$studentid' AND document_id = '".$checkArray[$i]."'");
			}
		}
		
	}
	else if ($action == 'tagSubmitted'){
		$applicantid = $_SESSION['applicantid'];

		$updateApplicantSql = mysqli_query($connect, "UPDATE applicant SET documents = 1 WHERE applicant_id = '$applicantid'");
	}
?>