<?php
require 'dbconfig.php';


	
	if($_POST)
	{
	

		$agency = $_POST['agency'];
		$syear = $_POST['syear'];
		$trimester = $_POST['semester'];
		$term = $_POST['term'];
		$country = $_POST['country'];
		//$unit = $_POST['unit'];
		$block_id = $_POST['id'];
		$faculty_name = $_POST['faculty_name'];
		$day = $_POST['day'];
		$timestart = $_POST['timestart'];
		$timeend = $_POST['timeend'];

		$dates = implode(",", $_POST['dates'] );

		$room = $_POST['room'];
		$maxslots = $_POST['maxslots'];
		$state = $_POST['state'];


$curriculum_id = $block_id;

	 if(empty($dates)){
    	$dates = null;
		}
		else if(empty($maxslots)){
    	$maxslots = null;
		}
		else if(empty($timestart)){
    	$timestart = null;
		}
		else if(empty($timeend)){
    	$timeend = null;
		}
		else if(empty($room)){
    	$room = null;
		}
     ?>

<?php

        session_start();
        $connect = mysqli_connect('localhost:3309', 'root', '123456', 'gp_test_copy');
        $subj = "SELECT * FROM program_name WHERE  id = $country";
         $query = mysqli_query($connect, $subj);


                     while($row1 = mysqli_fetch_array($query))
        { 

        	$program_code =  $row1['program_code'];
        	$program_title = $row1['program_title']; }
        ?>


        <?php

        session_start();
        $connect1 = mysqli_connect('localhost:3309', 'root', '123456', 'gp_test_copy');
        $subj1 = "SELECT * FROM program WHERE  id = $state";
         $query1 = mysqli_query($connect1, $subj1);


                     while($row1 = mysqli_fetch_array($query1))
        { 

        	$subj_code =  $row1['subj_code'];
        	$subj_title = $row1['subj_title'];
        	 }
        ?>


     <?php
		try{
			
			$stmt = $db_con->prepare("INSERT INTO classes(syear, trimester, term, block_id, faculty_id, day, timestart, timeend, dates, room, max_slots) VALUES(:c_syear, :c_trimester, :c_term,:c_block_id, :c_faculty_id, :c_day, :c_timestart, :c_timeend, :c_dates, :c_room, :c_maxslots)");

			
			//$stmt->bindParam(":c_agency", $agency);
			$stmt->bindParam(":c_syear", $syear);
			$stmt->bindParam(":c_trimester", $trimester);
			$stmt->bindParam(":c_term", $term);

			//$stmt->bindParam(":c_program_code", $program_code);
			//$stmt->bindParam(":c_program_title", $program_title);
			//$stmt->bindParam(":c_unit", $unit);
			$stmt->bindParam(":c_block_id", $block_id);
			
			$stmt->bindParam(":c_day", $day);
			$stmt->bindParam(":c_timestart", $timestart);

			$stmt->bindParam(":c_timeend", $timeend);
			$stmt->bindParam(":c_dates", $dates);
			$stmt->bindParam(":c_room", $room);
			$stmt->bindParam(":c_maxslots", $maxslots);
			//$stmt->bindParam(":c_subj_code", $subj_code);
			//$stmt->bindParam(":c_subj_title", $subj_title);

			$stmt->bindParam(":c_faculty_id", $faculty_name);
			//$stmt->bindParam(":c_units", $unit);

			/**$stmt2 = $db_con->prepare("REPLACE INTO blocks(block_id, agency, syear) VALUES (:b_block_id, :b_agency, :b_syear)");


			$stmt2->bindParam(":b_block_id", $block_id);
			$stmt2->bindParam(":b_agency", $agency);
			$stmt2->bindParam(":b_syear", $syear);**/
			
			$stmt3 = $db_con->prepare("INSERT INTO curriculum (program_code, subject_code, curriculum_id) VALUES (:b_programcode, :b_subjectcode, :b_curriculum_id)");


			$stmt3->bindParam(":b_programcode", $program_code);
			$stmt3->bindParam(":b_subjectcode", $subj_code);
			$stmt3->bindParam(":b_curriculum_id", $curriculum_id);

			
			$stmt2 = $db_con->prepare("INSERT INTO blocks(block_id, agency, curriculum_id) VALUES (:d_blockid, :d_agency, :d_curriculum_id)");


			$stmt2->bindParam(":d_blockid", $block_id);
			$stmt2->bindParam(":d_agency", $agency);
			//$stmt2->bindParam(":d_syear", $syear);
			$stmt2->bindParam(":d_curriculum_id", $curriculum_id);

			
			
			if($stmt->execute() && $stmt2->execute() && $stmt3->execute())
			{
				echo "Successfully Added ";
	
			}
			else{
				echo "Query Problem";
			}	
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}


?>