<!DOCTYPE html>
<?php 
    include 'facultyDashboard.php';
    $classid = $_SESSION['classid'];
    $aysem = SUBSTR($classid,0,5); 

    $startYear = 2017;
    $curYear = date( "Y" );

    $termsSql = mysqli_query($connect, "SELECT terms FROM graduate_schools WHERE gradschool_id = $gradschoolid");
    $terms = mysqli_fetch_row($termsSql);

    if($terms[0] == 3)
        $term = array('', '1st Trimester', '2nd Trimester', '3rd Trimester');
    else
        $term = array('', '1st Semester', '2nd Semester', 'Summer');

    $subjectInfoSql = mysqli_query($connect, "SELECT CONCAT(a.class,' - ',b.subject_title) as 'class', b.subject_title, GROUP_CONCAT(a.schedule) as 'schedule' FROM classes a JOIN subjects b ON b.subject_id = a.subject_id WHERE class_id = $classid");
    $subjectInfo = mysqli_fetch_assoc($subjectInfoSql);

    $subjectInfo['schedule'] = str_replace(',', "<br>".str_repeat('&nbsp;', 24), $subjectInfo['schedule']);

    $changeCodeSql = mysqli_query($connect, "SELECT changecode FROM classes WHERE class_id = $classid AND changecode != ''");
    $changeCode = mysqli_fetch_row($changeCodeSql)[0];
    $changeClass = '';
    $removeDisabled = '';

    if(mysqli_num_rows($changeCodeSql) == 0){
        $changeClass = 'hidden';
        $removeDisabled = 'disabled';
    }
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRS | E-Grades</title>
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

        <div class="col-xs-8 col-xs-offset-2">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 style="color: white"><?php echo $subjectInfo['class']; ?></h4>
                    <h4 style="color: white"><?php echo $subjectInfo['schedule']; ?></h4>
                    <h4 style="color: white" class="<?php echo $changeClass; ?>">** Change Code: <?php echo $changeCode; ?> **</h4>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">

                        <div class="alert alert-danger alert-dismissible" role="alert" id="errorDiv">
                            
                            <strong>Note!</strong> 
                            <span>Students with asterisk (*) would not receive their final grade due to incompliance of balance/s</span>
                        </div>

                        <table class="table table-align-center table-hover" id="studentsTable">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Temporary Grade</th>
                                    <th>Final Grade</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Temporary Grade</th>
                                    <th>Final Grade</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php
                                    $studentsSql = mysqli_query($connect, "SELECT a.student_id, b.name, a.grade, a.grade_value FROM grades a JOIN students b ON b.student_id = a.student_id WHERE a.class_id = '$classid'");

                                    while($students = mysqli_fetch_assoc($studentsSql)){
                                        $status = '';
                                        $studentid = $students['student_id'];

                                        $checkBalanceSql = mysqli_query($connect, "SELECT balance_amount FROM assessment_student WHERE student_id = $studentid AND aysem = $aysem AND balance_amount != 0");
                                        $x = mysqli_num_rows($checkBalanceSql);

                                        if($x > 0)
                                            $studentDisplay = '* '.$students['student_id'];
                                        else
                                            $studentDisplay = $students['student_id'];

                                        echo '
                                                <tr>
                                                    <td>'.$studentDisplay.'</td>
                                                    <td>'.ucwords(strtolower($students['name'])).'</td>

                                                    <td>
                                                        <select class="selectGrade" id="'.$students['student_id'].'" style="width:100%" disabled>
                                                        <option value="0" selected>---</option>
                                             ';

                                             $gradesSql = mysqli_query($connect, "SELECT * FROM gradevalue");

                                                while($grades = mysqli_fetch_row($gradesSql)){
                                                    $status = '';

                                                    if($students['grade'] == $grades[0]){
                                                        $status = 'selected';
                                                    }

                                                    echo '
                                                            <option value="'.$grades[0].'" '.$status.'>'.$grades[0].'</option>
                                                         ';
                                                }

                                        echo '
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <select style="width:100%" disabled class="finalGradeSelect" id="'.$students['student_id'].'">
                                                        <option value="0" selected>---</option>
                                             ';

                                             $gradesSql = mysqli_query($connect, "SELECT * FROM gradevalue");

                                                while($grades = mysqli_fetch_row($gradesSql)){
                                                    $status = '';

                                                    if($students['grade_value'] == $grades[0]){
                                                        $status = 'selected';
                                                    }

                                                    echo '
                                                            <option value="'.$grades[0].'" '.$status.'>'.$grades[0].'</option>
                                                         ';
                                                }

                                             '
                                                        </select>
                                                    </td>
                                             ';
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <center>
                        <button class="btn btn-success" id="allowButton">
                            <i class="glyphicon glyphicon-check"></i> Allow Changes
                        </button>
                        <button class="btn btn-primary" id="removeButton" <?php echo $removeDisabled; ?>>
                            <i class="glyphicon glyphicon-remove"></i> Remove code for changes
                        </button>
                        <button class="btn btn-primary" id="printButton">
                            <i class="glyphicon glyphicon-print"></i> Print Grades
                        </button>
                    </center>
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
            $("#mainNav").addClass("hidden");

            $('#studentsTable').DataTable({
                "language": {
                  "emptyTable": "No students available."
                },
                "bDeferRender": true
            });

            $("select").select2();
        });

        $("#backButton").click(function(){
            window.location = ('gradesPerClass.php');
        });

        $("#allowButton").click(function(){
            swal({
                title: 'Changing of Grades',
                type: "question",
                html: "Enter code",
                showCancelButton: true,
                showLoaderOnConfirm: true,
                reverseButtons: true,
                input: "text",
                inputClass: "input-center",
                preConfirm: function (result) {
                    return new Promise(function (resolve, reject) {
                        setTimeout(function() {
                            if (result) {
                                resolve()
                            } else {
                                reject('Do not leave field blank.')
                            }
                        }, 500)
                    })
                }
            }).then(function(code){
                $.ajax({
                    type: "POST",
                    url: "facultyAjax.php",
                    data: "changeCode="+code+"&action=allowCode",
                    success: 
                        function(msg){
                            swal({
                                type: "success",
                                title: "Updated!",
                                text: "Allowed changes for this class."
                            }).then(function(){
                                window.location.reload();
                            })
                        }
                })
            })
        });

        $("#removeButton").click(function(){
            swal({
                title: "Confirm",
                type: "question",
                text: "Are you sure to remove code for changes of grades?",
                showCancelButton: true
            }).then(function(){
                $.ajax({
                    type: "POST",
                    url: "facultyAjax.php",
                    data: "action=removeAllow",
                    success:
                        function(msg){
                           swal({
                                type: "success",
                                title: "Updated!",
                                text: "Changes for this class has been disabled."
                            }).then(function(){
                                window.location.reload();
                            }) 
                        }
                })
            })
        });
    </script>

    </body>
</html>
