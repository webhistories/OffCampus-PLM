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
        <title>CRS | Faculty Loading</title>
    </head>

    <body style="padding-top: 80px">
        <div class="col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    
                </div>

                <div class="panel-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-xs-2">
                                <label>
                                    Year
                                </label>

                                <select id="year" name="year" class="form-control" style="width: 100%">
                                    <option value="" disabled selected>Choose One</option>
                                    <?php 
                                        while($startYear <= $curYear){
                                            echo '<option value="'.$startYear.'">'.$startYear.'</option>';
                                            $startYear++;
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="col-xs-3">
                                <label>
                                    Term
                                </label>

                                <select id="term" name="term" class="form-control" style="width: 100%">
                                    <option value="" disabled selected>Choose One</option>
                                    <?php 
                                        for($i=1;$i<4;$i++){
                                            echo '<option value="'.$i.'">'.$term[$i].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <br>

                        <div class="row">
                            <div class="col-xs-3">
                                <button class="btn btn-success" id="classesButton" name="classesButton" >
                                    <i class="glyphicon glyphicon-search"></i> View Classes
                                </button>
                            </div>
                        </div>
                    </form>
                    <br>
                    <?php 
                        if(isset($_POST['classesButton'])){
                            $year = $_POST['year'];
                            $term = $_POST['term'];
                            $aysem = $year.$term;
                    ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-align-center table-hover" id="classesTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Subject Code</th>
                                            <th>Subject Title</th>
                                            <th>Faculty</th>
                                            <th>Day</th>
                                            <th>Time</th>
                                            <th>Room</th>
                                            <th>No of students</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Subject Code</th>
                                            <th>Subject Title</th>
                                            <th>Faculty</th>
                                            <th>Days</th>
                                            <th>Time</th>
                                            <th>Room</th>
                                            <th>No of students</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php

                                            $classesSql = mysqli_query($connect, "SELECT a.class_id, CONCAT(b.subject_name,'-',a.section) as 'class', b.subject_title, GROUP_CONCAT(a.day) as 'day', c.lastname, GROUP_CONCAT(CONCAT( time_format(a.timestart, '%h:%s %p'),' - ',time_format(a.timeend, '%h:%s %p'))) as 'time', GROUP_CONCAT(a.room) as 'room' FROM classes a JOIN subjects b ON b.subject_id = a.subject_id JOIN faculty c ON c.faculty_id = a.faculty_id WHERE SUBSTR(a.class_id,1,5) = '$aysem' AND b.gradschool_id = $gradschoolid GROUP BY a.class_id ORDER BY b.subject_id");
                                            $i=1;
                                            while($classes = mysqli_fetch_assoc($classesSql)){
                                                    $classes['day'] = str_replace(",", "<br>", $classes['day']);
                                                    $classes['time'] = str_replace(",", "<br>", $classes['time']);
                                                    $classes['room'] = str_replace(",", "<br>", $classes['room']);
                                                    echo '
                                                            <tr class="clickRow" style="cursor: pointer" id="'.$classes['class_id'].'">
                                                                <td>'.$i.'</td>
                                                                <td>'.$classes['class'].'</td>
                                                                <td>'.$classes['subject_title'].'</td>
                                                                <td>'.$classes['lastname'].'</td>
                                                                <td>'.$classes['day'].'</td>
                                                                <td>'.$classes['time'].'</td>
                                                                <td>'.$classes['room'].'</td>
                                                         ';

                                                        $classid = $classes['class_id'];

                                                        $studentsNumSql = mysqli_query($connect, "SELECT COUNT(student_id) FROM grades WHERE class_id = '$classid'");
                                                        $studentsNum = mysqli_fetch_row($studentsNumSql)[0];

                                                    echo '
                                                                <td>'.$studentsNum.'</td>
                                                            </tr>
                                                         ';
                                                $i++;
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                </div>
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
            $('#classesTable').DataTable({
                "language": {
                  "emptyTable": "No classes available."
                },
                "bDeferRender": true 
            });

            $("select").select2();
        });

        $(".clickRow").click(function(){
            var id = this.id;

            $.ajax({
                type: "POST",
                url: "facultyAjax.php",
                data: "classid="+id+"&action=sessionClass",
                success:
                    function(msg){
                        window.location = ("gradesPerClassSub.php");
                    }
            })
        });

    </script>

    </body>
</html>
