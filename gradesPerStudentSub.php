<!DOCTYPE html>
<?php 
    include 'facultyDashboard.php';
    $studentid = $_SESSION['studentid'];

    $studentInfoSql = mysqli_query($connect, "SELECT * FROM students WHERE student_id = '$studentid'");
    $studentInfo = mysqli_fetch_row($studentInfoSql);

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
        <style>
            .panel-heading h5{
                color: white;
            }
        </style>
    </head>

    <body style="padding-top: 80px">
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a class="navbar-brand" href="#">
                        Welcome, <?php echo $faculty['firstname']; ?>
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a id="backButton"> Back</a>
                        </li>
                     </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>

        <div class="col-xs-10 col-xs-offset-1">
            <?php 
                $studentInfoSql = mysqli_query($connect, "SELECT s.name, p.program_name, s.graduating, p.program_title, p.curriculum_id
                                    FROM Students s 
                                    JOIN programs p ON s.program_id = p.program_id
                                    WHERE s.student_id = '$studentid'");
                $studentInfo = mysqli_fetch_row($studentInfoSql);
            ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-6">
                            <h5><b>Student Name: </b><?php echo $studentInfo[0]; ?> </h5>
                            <h5><b>Student ID: </b><?php echo $studentid; ?></h5>
                        </div>

                        <div class="col-xs-6">
                            <h5><b>Program: </b><?php echo $studentInfo[1]; ?> </h5>
                            <h5><b>Graduating: </b><?php echo $studentInfo[2]; ?> </h5>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">   
                        <?php
                            $aysemSql = mysqli_query($connect, "SELECT DISTINCT(SUBSTR(class_id,1,5)) FROM grades WHERE student_id = $studentid");

                            while($aysem = mysqli_fetch_row($aysemSql)){
                                $labelSql = mysqli_query($connect, "SELECT CASE WHEN b.terms = 3 THEN CONCAT(SUBSTR($aysem[0],1,4),'-',SUBSTR($aysem[0],1,4)+1,CASE WHEN SUBSTR($aysem[0],5,1) = 1 THEN ' 1ST TRIMESTER' WHEN SUBSTR($aysem[0],5,1) = 2 THEN ' 2ND TRIMESTER' ELSE ' 3RD TRIMESTER' END) ELSE CONCAT(SUBSTR($aysem[0],1,4),'-',SUBSTR($aysem[0],1,4)+1, CASE WHEN SUBSTR($aysem[0],5,1) = 1 THEN ' 1ST SEMESTER' WHEN SUBSTR($aysem[0],5,1) = 2 THEN ' 2ND SEMESTER' ELSE ' SUMMER' END ) END FROM graduate_schools b WHERE b.gradschool_id = $gradschoolid");
                                $label = mysqli_fetch_row($labelSql);

                                echo '
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th colspan=6>'.$label[0].'</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Subject Code</td>
                                                    <td>Subject Title</td>
                                                    <td>Faculty</td>
                                                    <td align="center">Units</td>
                                                    <td align="center">Initial Grade</td>
                                                    <td align="center">Completion Grade</td>
                                                    <td align="center">Remarks</td>
                                                </tr>
                                     ';

                                        $gradesSql = mysqli_query($connect, "SELECT CONCAT(b.class,'-',b.section), c.subject_title, b.unit,
                                            CASE WHEN a.grade = 'INC' THEN 'INC'
                                                 WHEN a.grade_value = 0 THEN '---' ELSE a.grade_value END, 
                                            CASE WHEN a.grade = 'INC' THEN a.grade_value ELSE '---' END,
                                            a.remarks, d.lastname
                                            FROM grades a 
                                            JOIN classes b ON b.class_id = a.class_id
                                            JOIN subjects c ON c.subject_id = b.subject_id
                                            JOIN faculty d ON d.faculty_id = b.faculty_id
                                            WHERE a.student_id = $studentid AND SUBSTR(a.class_id,1,5) = $aysem[0]");

                                        while($grades = mysqli_fetch_row($gradesSql)){

                                            echo '
                                                    <tr>
                                                        <td>'.$grades[0].'</td>
                                                        <td>'.$grades[1].'</td>
                                                        <td>'.$grades[6].'</td>
                                                        <td align="center">'.$grades[2].'</td>
                                                        <td align="center">'.$grades[3].'</td>
                                                        <td align="center">'.$grades[4].'</td>
                                                        <td align="center">'.$grades[5].'</td>
                                                    </tr>
                                                 ';

                                        }

                                echo '
                                            </tbody>
                                        </table>
                                     ';

                            }
                        ?>
                    </div>
                </div>

                <div class="panel-footer" id="<?php echo $studentid; ?>">
                    <center>
                        <button class="btn btn-primary" id="printButton">
                            <i class="glyphicon glyphicon-print"></i> Print
                        </button>
                    </center>
                </div>
            </div>
        </div>
    </body>

    <script src="../js/jquery-3.1.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap4.min.js"></script>
    <script src="../js/select2.min.js"></script>
    <script src="../js/sweetalert2.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $("#mainNav").addClass("hidden");

            $("select").select2();

            $('#gradesTable').DataTable({
                "language": {
                  "emptyTable": "No grades yet."
                },
                "bDeferRender": true 
            });
        });

        $("#backButton").click(function(){
            window.location = ('gradesPerStudent.php');
        });

    </script>
</html>
