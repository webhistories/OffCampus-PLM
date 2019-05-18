<!DOCTYPE html>
<?php 
    include 'facultyDashboard.php';
    $applicantid = $_SESSION['applicantid'];

    $applicantInfoSql = mysqli_query($connect, "SELECT * FROM applicant a JOIN applicant_personal b ON b.applicant_id = a.applicant_id JOIN applicant_family c ON c.applicant_id = a.applicant_id JOIN applicant_academic d ON d.applicant_id = a.applicant_id JOIN programs e ON e.program_id = a.program WHERE a.applicant_id = $applicantid ");

    $applicantInfo = mysqli_fetch_assoc($applicantInfoSql);
    $_SESSION['appGender'] = $applicantInfo['gender'];
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRS | Applicants</title>
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

        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 style="color: white">
                        <?php echo $applicantInfo['lastname'].', '.$applicantInfo['firstname'].' '.$applicantInfo['middlename'];
                        ?>
                    </h4>
                </div>

                <div class="panel-body" style="max-height: 70vh; overflow-y: auto">
                    <div class="row">
                        <div class="col-xs-3">
                            <label>Applicant ID</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-user"></i>
                                </span>
                                <input type="text" class="form-control" value="<?php echo $applicantid; ?>">
                            </div>
                        </div>

                        <div class="col-xs-3 <?php if($applicantInfo['student_id'] == '') echo 'hidden'; ?> ">
                            <label>Student ID</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-user"></i>
                                </span>
                                <input type="text" class="form-control" value="<?php echo $applicantInfo['student_id']; ?>">
                            </div>
                        </div>

                        <div class="col-xs-3">
                            <label>Program</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-user"></i>
                                </span>
                                <input type="text" class="form-control" value="<?php echo $applicantInfo['program_name']; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <br>

                    <div class="row">
                        <div class="col-xs-4">
                            <label>First Name</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-user"></i>
                                </span>
                                <input type="text" class="form-control" value="<?php echo $applicantInfo['firstname']; ?>">
                            </div>
                        </div>

                        <div class="col-xs-4">
                            <label>Middle Name</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-user"></i>
                                </span>
                                <input type="text" class="form-control" value="<?php echo $applicantInfo['middlename']; ?>">
                            </div>
                        </div>

                        <div class="col-xs-4">
                            <label>Last Name</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-user"></i>
                                </span>
                                <input type="text" class="form-control" value="<?php echo $applicantInfo['lastname']; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <br>

                    <div class="row">
                        <div class="col-xs-2">
                            <label>Gender</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-user"></i>
                                </span>
                                <input type="text" class="form-control" value="<?php echo $applicantInfo['gender']; ?>">
                            </div>
                        </div>

                        <div class="col-xs-3">
                            <label>Birthdate</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-calendar"></i>
                                </span>
                                <input type="text" class="form-control" value="<?php echo $applicantInfo['birthdate']; ?>">
                            </div>
                        </div>

                        <div class="col-xs-3">
                            <label>Email Address</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-user"></i>
                                </span>
                                <input type="text" class="form-control" value="<?php echo $applicantInfo['email']; ?>">
                            </div>
                        </div>

                        <div class="col-xs-4">
                            <label>Address</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-home"></i>
                                </span>
                                <input type="text" class="form-control" value="<?php echo $applicantInfo['address']; ?>">
                            </div>
                        </div>
                    </div>

                    <br><br>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-align-center">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Father</th>
                                            <th>Mother</th>
                                            <th>Guardian</th>
                                        </tr>
                                    </thead>

                                    <tr>
                                        <th>Name</th>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-user"></i>
                                                </span>
                                                <input type="text" class="form-control" value="<?php echo $applicantInfo['faname']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-user"></i>
                                                </span>
                                                <input type="text" class="form-control" value="<?php echo $applicantInfo['maname']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-user"></i>
                                                </span>
                                                <input type="text" class="form-control" value="<?php echo $applicantInfo['guname']; ?>">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Address</th>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-home"></i>
                                                </span>

                                                <input type="text" class="form-control" value="<?php echo $applicantInfo['faaddress']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-home"></i>
                                                </span>

                                                <input type="text" class="form-control" value="<?php echo $applicantInfo['maaddress']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-home"></i>
                                                </span>

                                                <input type="text" class="form-control" value="<?php echo $applicantInfo['guaddress']; ?>">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Contact No</th>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-phone"></i>
                                                </span>
                                                <input type="number" class="form-control" value="<?php echo $applicantInfo['facontact']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-phone"></i>
                                                </span>
                                                <input type="number" class="form-control" value="<?php echo $applicantInfo['macontact']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-phone"></i>
                                                </span>
                                                <input type="number" class="form-control" value="<?php echo $applicantInfo['gucontact']; ?>">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Occupation</th>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-briefcase"></i>
                                                </span>
                                                <input type="text" class="form-control" value="<?php echo $applicantInfo['faoccupation']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-briefcase"></i>
                                                </span>
                                                <input type="text" class="form-control" value="<?php echo $applicantInfo['maoccupation']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-briefcase"></i>
                                                </span>
                                                <input type="text" class="form-control" value="<?php echo $applicantInfo['guoccupation']; ?>">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Office/Employer</th>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-briefcase"></i>
                                                </span>
                                                <input type="text" class="form-control" value="<?php echo $applicantInfo['faoffice']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-briefcase"></i>
                                                </span>
                                                <input type="text" class="form-control" value="<?php echo $applicantInfo['maoffice']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-briefcase"></i>
                                                </span>
                                                <input type="text" class="form-control" value="<?php echo $applicantInfo['guoffice']; ?>">
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <br><br>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-align-center">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Elementary</th>
                                            <th>High School</th>
                                            <th>College/University</th>
                                        </tr>
                                    </thead>

                                    <tr>
                                        <th><b>School Attended</b></th>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-user"></i>
                                                </span>
                                                <input type="text" class="form-control" value="<?php echo $applicantInfo['elSchool']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-user"></i>
                                                </span>
                                                <input type="text" class="form-control" value="<?php echo $applicantInfo['hiSchool']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-user"></i>
                                                </span>
                                                <input type="text" class="form-control" value="<?php echo $applicantInfo['coSchool']; ?>">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th><b>Year Entered</b></th>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-calendar"></i>
                                                </span>
                                                <input type="number" class="form-control" value="<?php echo $applicantInfo['elEntered']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-calendar"></i>
                                                </span>
                                                <input type="number" class="form-control" value="<?php echo $applicantInfo['hiEntered']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-calendar"></i>
                                                </span>
                                                <input type="number" class="form-control" value="<?php echo $applicantInfo['coEntered']; ?>">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th><b>Year Graduated</b></th>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-book"></i>
                                                </span>
                                                <input type="number" class="form-control" value="<?php echo $applicantInfo['elGraduated']; ?>">
                                            </div>
                                        </td>  
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-book"></i>
                                                </span>
                                                <input type="number" class="form-control" value="<?php echo $applicantInfo['hiGraduated']; ?>">
                                            </div>
                                        </td>   
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-book"></i>
                                                </span>
                                                <input type="number" class="form-control" value="<?php echo $applicantInfo['coGraduated']; ?>">
                                            </div>
                                        </td>   
                                    </tr>

                                    <tr>
                                        <th><b>Degree</b></th>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-star"></i>
                                                </span>
                                                <input type="text" class="form-control" value="<?php echo $applicantInfo['coDegree']; ?>">
                                            </div>
                                        </td> 
                                    </tr>

                                    <tr>
                                        <th><b>Major</b></th>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-star"></i>
                                                </span>
                                                <input type="text" class="form-control" value="<?php echo $applicantInfo['coMajor']; ?>">
                                            </div>
                                        </td> 
                                    </tr>
                                    
                                    <tr>
                                        <th><b>General Average</b></th>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-star"></i>
                                                </span>
                                                <input type="number" class="form-control" value="<?php echo $applicantInfo['elAverage']; ?>">
                                            </div>
                                        </td>             
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-star"></i>
                                                </span>
                                                <input type="number" class="form-control" value="<?php echo $applicantInfo['hiAverage']; ?>">
                                            </div>
                                        </td>             
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-star"></i>
                                                </span>
                                                <input type="number" class="form-control" value="<?php echo $applicantInfo['coAverage']; ?>">
                                            </div>
                                        </td>                                                       
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-footer">  
                    <center>
                        <button class="btn btn-primary" id="printButton">
                            <i class="glyphicon glyphicon-print"></i>
                            &nbsp Print SPAR
                        </button>
                        <button class="btn btn-success" id="acceptButton" <?php if($applicantInfo['status'] != 0) echo 'disabled'; ?>>
                            <i class="glyphicon glyphicon-ok"></i>
                            &nbsp Accept applicant
                        </button>

                        <button class="btn btn-primary" id="generateButton" <?php if($applicantInfo['student_id'] != '' || $applicantInfo['documents'] == 0) echo 'disabled'; ?>>
                            <i class="glyphicon glyphicon-refresh"></i>
                            Generate Student Number
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

            $("select").select2();

            $(".panel-body input").each(function(){
                $(this).attr("readonly", true).css("background", "white").addClass("input-center");
            });
        });

        $("#backButton").click(function(){
            window.location = ('evalApplicants.php');
        });

        $("#printButton").click(function(){
            window.open('../printables/printSPAR.php','_blank');
        });

        $("#acceptButton").click(function(){
            swal({
                title: "Accept this applicant?",
                type: "question",
                confirmButtonText: "Yes",
                showCancelButton: true,
                showLoaderOnConfirm: true
            }).then(function(){
                $.ajax({
                    type: "POST",
                    async: true,
                    cache: true,
                    url: "facultyAjax.php",
                    data: "action=acceptApplicant",
                    success: 
                        function(msg){
                            swal({
                                title: "Applicant Accepted!",
                                type: "success"
                            }).then(function(){
                                window.location = "evalApplicants.php";
                            })
                        }
                });
            })
        });

        $("#generateButton").click(function(){
            $.ajax({
                type: "POST",
                async: true,
                cache: true,
                url: "facultyAjax.php",
                data: "action=generateNumber"+"&process=generate",
                success: 
                    function(msg){
                        swal({
                            title: "Confirm",
                            type: "question",
                            html: "Generate Student Number: <b>"+msg+"</b> for this student?",
                            showCancelButton: true
                        }).then(function(){
                            $.ajax({
                                type: "POST",
                                async: true,
                                cache: true,
                                url: "facultyAjax.php",
                                data: "action=generateNumber"+"&process=save",
                                success: 
                                    function(msg){
                                        swal({
                                            title: "Success!",
                                            type: "success",
                                            html: "Successfully generated <b>"+msg+"</b> for this student"
                                        }).then(function(){
                                            window.location = "evalApplicants.php";
                                        })
                                    }
                            });
                        })
                    }
            });
        });
    </script>

    </body>
</html>

