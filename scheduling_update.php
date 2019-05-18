<?php
require_once 'dbconfig.php';

	
	if($_POST)
	{
		
		$subj_code = $_POST['subj_code'];
		$dates = $_POST['dates'];
		$faculty_name = $_POST['faculty_name'];

		$day = $_POST['day'];
		$timestart = $_POST['timestart'];
		$timeend = $_POST['timeend'];
		$room = $_POST['room'];

		$maxslots = $_POST['maxslots'];
	
	if(empty($subj_code)){
    	$subj_code = null;
		}
	else if(empty($dates)){
	    	$dates = null;
			}
	else if(empty($faculty_name)){
    	$faculty_name = null;
		}
	else if(empty($day)){
    	$day = null;
    }
    	else if(empty($timeend)){
    	$timeend = null;
		}
		else if(empty($timestart)){
    	$timestart = null;
		}
		else if(empty($room)){
    	$room = null;
		}
		else if(empty($maxslots)){
    	$maxslots= null;
		}

		
		?>
		

<?php		
		$stmt = $db_con->prepare("UPDATE classes SET faculty_id=:eh, dates=:em, timestart=:ts, timeend=:te, day=:dd, room=:rr, maxslots=:ms WHERE subj_code=:subj_code");

		$stmt->bindParam(":eh", $faculty_name);
		$stmt->bindParam(":em", $dates);
		$stmt->bindParam(":ts", $timestart);
		$stmt->bindParam(":te", $timeend);
		$stmt->bindParam(":dd", $day);
		$stmt->bindParam(":rr", $room);
		$stmt->bindParam(":ms", $maxslots);
		$stmt->bindParam(":subj_code", $subj_code);
		
		if($stmt->execute())
		{
			echo "Successfully updated";
		}
		else{
			echo "Query Problem";
		}
	}

?>