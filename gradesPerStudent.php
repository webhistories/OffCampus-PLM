<!DOCTYPE html>
<?php 
    include 'facultyDashboard.php';

    $startYear = 2017;
    $curYear = date( "Y" );

    $termsSql = mysqli_query($connect, "SELECT terms FROM graduate_schools WHERE gradschool_id = $gradschoolid");
    $terms = mysqli_fetch_row($termsSql);

    if($terms[0] == 3)
        $term = array('', '1st Trimester', '2nd Trimester', '3rd Trimester');
    else
        $term = array('', '1st Semester', '2nd Semester', 'Summer');

?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRS | Grades</title>
    </head>

    <body style="padding-top: 80px">
        <div class="col-xs-12">
            <div class="table-responsive">
                <table class="table table-hover table-align-center" id="studentsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Program</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Program</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                            $studentsSql = mysqli_query($connect, "SELECT a.student_id, a.name, b.program_name FROM students a JOIN programs b ON b.program_id = a.program_id WHERE b.gradschool_id = $gradschoolid");
                            $i=1;
                            while($students = mysqli_fetch_assoc($studentsSql)){
                                echo '
                                        <tr style="cursor: pointer" class="clickRow" id="'.$students['student_id'].'">
                                            <td>'.$i.'</td>
                                            <td>'.$students['student_id'].'</td>
                                            <td>'.$students['name'].'</td>
                                            <td>'.$students['program_name'].'</td>
                                        </tr>
                                     ';
                                $i++;
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    <script src="../js/jquery-3.1.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap4.min.js"></script>
    <script src="../js/select2.min.js"></script>
    <script src="../js/sweetalert2.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#studentsTable').DataTable({
                "language": {
                  "emptyTable": "No students available."
                },
                "bDeferRender": true 
            });

            $("select").select2();
        });

        $(".clickRow").click(function(){
            var studentid=this.id;

            $.ajax({
                type: "POST",
                async: true,
                cache: true,
                url: "facultyAjax.php",
                data: "studentid="+studentid+"&action=students"+"&process=session",
                success:
                    function(info, status, xhr){
                        if(info != "error"){
                            window.location = ('gradesPerStudentSub.php');
                        }
                    }
            });
        });

    </script>

    </body>
</html>
