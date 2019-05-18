<?php
	include '../config.php';
    $studentid = $_SESSION['studentid'];
    $studentInfoSql = mysqli_query($connect, "SELECT * FROM students WHERE student_id = $studentid");
    $studentInfo = mysqli_fetch_row($studentInfoSql);
    if($_SESSION['enrollment'] == 1){
        $aysemenrollment = $_SESSION['aysemenrollment'];    
    }

    $gradschoolSql = mysqli_query($connect, "SELECT g.gradschool_id FROM graduate_schools g
                                        JOIN programs p ON g.gradschool_id = p.gradschool_id
                                        JOIN students s ON s.program_id = p.program_id
                                        WHERE s.student_id = '$studentid'");
    $gradschool = mysqli_Fetch_row($gradschoolSql);
    $_SESSION['gradschoolid'] = $gradschool[0];

    $_SESSION['curriculum'] = $studentInfo[13];
?>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="../css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <link href="../css/select2.min.css" rel="stylesheet">
        <link href="../css/select2-bootstrap.min.css" rel="stylesheet">
        <link href="../css/sweetalert2.min.css" rel="stylesheet">
        <link href="../css/custom.css" rel="stylesheet">
        <link href="../css/custom2.css" rel="stylesheet">
    </head>

    <body style="padding-top: 80px">
    
        <nav class="navbar navbar-inverse navbar-fixed-top" id="mainNav">
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
                        Welcome, <?php echo $studentInfo[2] ?>
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="enrollment.php"> Enrollment</a>
                        </li>

                        <li>
                            <a href="../logout.php"> Enlistment</a>
                        </li>
                        <li>
                            <a href="../logout.php"> Curriculum Checklist</a>
                        </li>

                        <li>
                            <a href="../logout.php"> Grades</a>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a id="changePassword"> Change Password</a></li>
                                <li><a href="../logout.php"> Logout</a></li>
                            </ul>
                        </li>
                     </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        
        <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="changePasswordLabel">Change Password</h3>
                    </div>
                    
                    <div class="modal-body">
                        <div class="alert alert-danger alert-dismissible hidden" role="alert" id="errorPasswordDiv">
                            <strong>Incorrect!</strong> 
                            <span id="errorPasswordMsg">Better check yourself, you're not looking too good.</span>
                        </div>

                        <div class="alert alert-success alert-dismissible hidden" role="alert" id="successPasswordDiv">
                            <strong>Success!</strong> 
                            <span id="successPasswordMsg">Better check yourself, you're not looking too good.</span>
                        </div>

                        <form>
                            <div class="form-group">
                                <label for="login" class="form-control-label">Old Password:</label>
                                <input type="password" class="form-control" id="oldPassword">
                            </div>
                            
                            <div class="form-group">
                                <label for="pword" class="form-control-label">New Password:</label>
                                <input type="password" class="form-control" id="newPassword">
                            </div>

                            <div class="form-group">
                                <label for="pword" class="form-control-label">Confirm Password:</label>
                                <input type="password" class="form-control" id="confirmPassword">
                            </div>
                        </form>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeChangePassword">Close</button>
                        <button type="button" class="btn btn-success" id="saveChangePassword">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>

    <script src="../js/jquery-3.1.1.min.js"></script>

    <script>
        var path = window.location.href.substr(window.location.href.lastIndexOf("/")+1);
        path = path.replace("Sub", "");
        $(".nav").find("a").each(function(){
            if($(this).attr("href") == path)
                $(this).addClass("active");
        });

        $("#changePassword").click(function(){
            $("#changePasswordModal").modal("show");
        });

        $("#saveChangePassword").click(function(){

            var newPassword = $("#newPassword").val(),
                confirmPassword = $("#confirmPassword").val(),
                oldPassword = $("#oldPassword").val(),
                flagExit = 0;

                $.ajax({
                    type: "POST",
                    async: true,
                    cache: true,
                    url: "../changePassword.php",
                    data: "oldPassword="+oldPassword+"&newPassword="+newPassword+"&confirmPassword="+confirmPassword+"&action=savePassword",
                    success: 
                        function(msg){
                            if (msg){
                                swal({
                                    title: "Invalid",
                                    type: "error",
                                    html: msg
                                });
                            }
                            else{
                                swal({
                                    title: "Success",
                                    type: "success",
                                    html: "Password updated successfully."
                                }).then(function(){
                                    window.setTimeout(function(){location.reload()},0);                              
                                })

                            }
                        }
                });
        });
    </script>
    </body>
</html>
