<?php
	include "registrarDashboard.php";
	include "config.php";

	if(isset($_GET['gradesSubmit']))
	{	
		$subject_name = mysqli_real_escape_string($connect, $_GET['dropdown1']);
		$faculty_name = mysqli_real_escape_string($connect, $_GET['dropdown2']);


		$_SESSION['subjectname'] = $subject_name;
		$_SESSION['facultyname'] = $faculty_name;

		$query1 = "SELECT * FROM students_grades WHERE subj_title = '$subject_name' AND faculty = '$faculty_name'";

		$results = mysqli_query($connect, $query1);

		echo '<div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-hover table-align-center" id="studentsTable">
                    <thead>
                        <tr>
                            <th>Name of Student</th>
                            <th>Student No.</th>
                            <th>Course</th>
                            <th>Grade</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>';
		while($row1 = mysqli_fetch_array($results))
		{

			echo '<tr>';
			echo '<td>'.$row1['name'].'</td>';
			echo '<td>'.$row1['student_id'].'</td>';
			echo '<td>'.$row1['curriculum_id'].'</td>';
			echo '<td>'.$row1['grade'].'</td>';
			echo '<td>'.$row1['remarks'].'</td>';
			echo '</tr>';
		}
		echo '</table>';
	}
?>