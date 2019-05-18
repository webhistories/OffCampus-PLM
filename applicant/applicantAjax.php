<?php
	include '../config.php';

	$action = $_POST['action'];

	if($action == 'save'){
		$applicantid = $_SESSION['applicantid'];

		$firstname = $_POST['firstname'];
      	$middlename = $_POST['middlename'];
      	$gender = $_POST['gender'];
      	$lastname = $_POST['lastname'];
      	$birthdate = $_POST['birthdate'];
      	$email = $_POST['email'];
      	$address = $_POST['address'];

      	$faname = $_POST['faname'];
      	$faaddress = $_POST['faaddress'];
      	$facontact = $_POST['facontact'];
      	$faoccupation = $_POST['faoccupation'];
      	$faoffice = $_POST['faoffice'];

      	$maname = $_POST['maname'];
      	$maaddress = $_POST['maaddress'];
      	$macontact = $_POST['macontact'];
      	$maoccupation = $_POST['maoccupation'];
      	$maoffice = $_POST['maoffice'];

      	$guname = $_POST['guname'];
      	$guaddress = $_POST['guaddress'];
      	$gucontact = $_POST['gucontact'];
      	$guoccupation = $_POST['guoccupation'];
      	$guoffice = $_POST['guoffice'];

      	$elschool = $_POST['elschool'];
      	$elentered = $_POST['elentered'];
      	$elgraduated = $_POST['elgraduated'];
      	$elaverage = $_POST['elaverage'];

      	$hischool = $_POST['hischool'];
      	$hientered = $_POST['hientered'];
      	$higraduated = $_POST['higraduated'];
      	$hiaverage = $_POST['hiaverage'];

      	$coschool = $_POST['coschool'];
      	$coentered = $_POST['coentered'];
      	$codegree = $_POST['codegree'];
      	$comajor = $_POST['comajor'];
      	$cograduated = $_POST['cograduated'];
      	$coaverage = $_POST['coaverage'];

      	if($elaverage == "")
			$elaverage = 0.00;
		
		if($hiaverage == "")
			$hiaverage = 0.00;
		
		if($coaverage == "")
			$coaverage = 0.00;

		if($birthdate == "")
			$birthdate = "0000-00-00";
		
      	$updatePersonal = mysqli_query($connect, "UPDATE applicant_personal 
      		SET firstname = '$firstname', middlename = '$middlename', lastname = '$lastname', gender = '$gender', 
      		    birthdate = '$birthdate', address = '$address' WHERE applicant_id = '$applicantid'");

      	$updateFamily = mysqli_query($connect, "UPDATE applicant_family
      		SET faname = '$faname', faaddress = '$faaddress', facontact = '$facontact', faoccupation = '$faoccupation', faoffice = '$faoffice', maname = '$maname', maaddress = '$maaddress', macontact = '$macontact', maoccupation = '$maoccupation', maoffice = '$maoffice', guname = '$guname', guaddress = '$guaddress', gucontact = '$gucontact', guoccupation = '$guoccupation', guoffice = '$guoffice' WHERE applicant_id = '$applicantid'");

      	$updateAcademic = mysqli_query($connect, "UPDATE applicant_academic
      		SET elSchool = '$elschool', elEntered = '$elentered', elGraduated = '$elgraduated', elAverage = '$elaverage',
      		    hiSchool = '$hischool', hiEntered = '$hientered', hiGraduated = '$higraduated', hiAverage = '$hiaverage',
      		    coSchool = '$coschool', coEntered = '$coentered', coGraduated = '$cograduated', coAverage = '$coaverage',
      		    coDegree = '$codegree', coMajor = '$comajor' WHERE applicant_id = '$applicantid'");
	}
?>