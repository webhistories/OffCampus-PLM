<?php
require 'dbconfig.php';

	
	if($_POST)
	{
		$id = $_POST['id'];
		$name = $_POST['name'];
	
		$grade = $_POST['grade'];
		
		
  $stmt2 = $db_con->prepare("SELECT * FROM remarks WHERE grade = '$grade'");
        $stmt2->execute();

        while($row1=$stmt2->fetch(PDO::FETCH_ASSOC))
        {

                $remarks = $row1['remarks'];

            }


		$stmt = $db_con->prepare("UPDATE students_grades SET grade=:ek, remarks =:re WHERE student_id=:id");
		$stmt->bindParam(":ek", $grade);
		$stmt->bindParam(":id", $id);
		$stmt->bindParam(":re", $remarks);
		
		if($stmt->execute())
		{
			echo "Successfully updated";
		}
		else{
			echo "Query Problem";
		}
	}


     
     
?>