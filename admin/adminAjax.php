<?php

	include '../config.php';

	$action = $_POST['action'];
    if($action == 'addFaculty'){
        $facultyid = $_POST['facultyid'];
      	$firstname = $_POST['firstname'];
      	$middlename = $_POST['middlename'];
      	$gender = $_POST['gender'];
      	$lastname = $_POST['lastname'];
      	$birthdate = $_POST['birthdate'];
      	$email = $_POST['email'];
      	$contactno = $_POST['contactno'];
      	$address = $_POST['address'];
      	$designationid = $_POST['designationid'];
      	$login = $_POST['login'];
      	$password = $_POST['password'];
      	$graduateschool = $_POST['graduateschool'];
		
		if($birthdate == "")
			$birthdate = "0000-00-00";

		$facultySql = mysqli_query($connect,"SELECT * FROM faculty WHERE faculty_id = '$facultyid'");
		if (mysqli_num_rows($facultySql) > 0) {
			echo "Faculty ID is already existing.";
			exit();
		}

		$loginSql = mysqli_query($connect, "SELECT * FROM users WHERE login = '$login'");
		if (mysqli_num_rows($loginSql) > 0) {
			echo "Username is already existing.";
			exit();
		}

		$name = $firstname." ".$middlename;
		$fullName = $lastname.', '.$firstname.' '.$middlename;
		
		$insertedby = $_SESSION['facultyid'];

		
		if ($graduateschool == '')
			$graduateschool = 'null';

		
        $insertFacultySql = mysqli_query($connect, "INSERT INTO faculty (faculty_id, name, firstname, middlename, lastname, gender, birthdate, email, contactno, address, gradschool_id) VALUES ('$facultyid', '$fullName' ,'$firstname', '$middlename', '$lastname', '$gender', '$birthdate', '$email', '$contactno', '$address', $graduateschool)");
        $insertUserSql = mysqli_query($connect, "INSERT INTO users (user_id, firstname, user_level, login, pword, insertedby, insertedon) VALUES ('$facultyid', '$firstname', 1, '$login', '$password', '$insertedby', NOW())");	

        if ($designationid != " ") {
        		$insertDesignationSql = mysqli_query($connect, "INSERT INTO faculty_designation (faculty_id, designation_id, gradschool_id) VALUES ('$facultyid', '$designationid', $graduateschool)");
        }


	}
?>