<!DOCTYPE html>
<?php
    include 'adminDashboard.php';
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRS | Faculty Management</title>
    </head>

    <body style="padding-top: 80px">
        <div class="col-md-12">
            <div class="panel with-nav-tabs panel-primary">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#listFaculty" data-toggle="tab"><b>List of Faculty</b></a></li>
                        <li class=""><a href="#addFaculty" data-toggle="tab"><b>Add New Faculty</b></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="listFaculty">
                            <div class="table-responsive">
                                <table class="table table-hover table-align-center" id="facultyTable">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Faculty ID</th>
                                            <th>Name</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th>Faculty ID</th>
                                            <th>Name</th>
                                        </tr>                                        
                                    </tfoot>
                                    <?php
                                        $facultySql = mysqli_query($connect, "SELECT * FROM faculty");
                                        $i=1;
                                        while($faculty = mysqli_fetch_assoc($facultySql)){
                                            echo '
                                                    <tr>
                                                        <td>'.$i.'</td>
                                                        <td>'.$faculty['faculty_id'].'</td>
                                                        <td>'.ucwords(strtolower($faculty['name'])).'</td>
                                                    </tr>
                                                 ';
                                        $i++;   
                                        }
                                    ?>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade " id="addFaculty">
                            <div class="row">
                                <div class="col-lg-4">
                                    <label>Faculty ID</label>
                                    
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-user"></i>
                                        </span>
                                        <input type="text" class="form-control" id="facultyid">
                                    </div>
                                </div>

                                <div class="col-lg-5">
                                    <label>Graduate Program</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-book"></i>
                                        </span>
                                        <select style="width: 100%" id="graduateschool">
                                            <option value="">Choose One</option>
                                            <?php
                                                $gradschoolSql = mysqli_query($connect, "SELECT * FROM graduate_schools");

                                                while($gradschool = mysqli_fetch_assoc($gradschoolSql)){
                                                    echo '
                                                            <option value="'.$gradschool['gradschool_id'].'">
                                                            '.$gradschool['gradschool_name'].'
                                                            </option>
                                                        ';
                                                }
                                            ?>
                                        </select>
                                    </div>          
                                </div>

                                <div class="col-xs-3">
                                    <label>Designation</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-book"></i>
                                        </span>
                                        <select id="designationid" style="width:100%">
                                            <option value=" ">Choose Designation</option>
                                            <?php 
                                                $designationSql = mysqli_query($connect, "SELECT * FROM designation");

                                                while($designation = mysqli_fetch_assoc($designationSql)){
                                                    echo '
                                                            <option value="'.$designation['designation_id'].'">
                                                            '.$designation['designation_title'].'
                                                            </option>
                                                         ';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <br/>

                            <div class="row">
                                <div class="col-lg-4">
                                    <label>First Name</label>

                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-user"></i>
                                        </span>
                                        <input type="text" class="form-control" id="firstname">
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label>Middle Name</label>
                                    
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-user"></i>
                                        </span>
                                        <input type="text" class="form-control" id="middlename">
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label>Last Name</label>
                                    
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-user"></i>
                                        </span>
                                        <input type="text" class="form-control" id="lastname">
                                    </div>
                                </div>
                            </div>
                            
                            <br>

                            <div class="row">
                                <div class="col-lg-4">
                                    <label>Gender</label>
                                    
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-user"></i>
                                        </span>

                                        <select id="gender" style="width: 100%">
                                            <option value="M">Male</option>
                                            <option value="F">Female</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label>Birthdate</label>
                                    
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-calendar"></i>
                                        </span>
                                        
                                        <input type="text" id="birthdate" class="form-control">
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label>Email Address</label>
                                    
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-envelope"></i>
                                        </span>
                                        
                                        <input type="text" id="email" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-lg-4">
                                    <label>Contact Number</label>
                                    
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-phone"></i>
                                        </span>
                                        <input type="number" class="form-control" id="contactno">
                                    </div>
                                </div>

                                <div class="col-lg-8">
                                    <label>Address</label>
                                    
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-home"></i>
                                        </span>
                                        <input type="text" class="form-control" id="address">
                                    </div>
                                </div>
                            </div>

                            <br/>

                            <div class="row">
                                <div class="col-lg-4">
                                    <label>Username</label>

                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-user"></i>
                                        </span>
                                        <input type="text" class="form-control" id="login">
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label>Password</label>

                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="glyphicon glyphicon-user"></i>
                                        </span>
                                        <input type="text" class="form-control" id="password">
                                    </div>
                                </div>
                            </div>

                            <br/>

                            <div class="row">
                                <div class="col-xs-12">
                                    <center>
                                        <button class="btn btn-success" id="saveCreate">
                                        <i class="glyphicon glyphicon-save"></i> Save Changes</button>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap4.min.js"></script>
    <script src="../js/select2.min.js"></script>
    <script src="../js/sweetalert2.min.js"></script>
    <script src="../js/bootstrap-datepicker.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#facultyTable').DataTable({
                "language": {
                  "emptyTable": "No faculty available."
                },
                "bDeferRender": true
            });
            
            $("select").select2({
                theme: "bootstrap"
            });

            $("#birthdate").datepicker({
                format: "yyyy-mm-dd"
            });
        });

        $("#saveCreate").click(function(){
            var facultyid = $("#facultyid").val(),
                graduateschool = $("#graduateschool").val(),
                firstname = $("#firstname").val(),
                middlename = $("#middlename").val(),
                lastname = $("#lastname").val(),
                gender = $("#gender").val(),
                birthdate = $("#birthdate").val(),
                email = $("#email").val(),
                contactno = $("#contactno").val(),
                address = $("#address").val(),
                designationid = $("#designationid").val(),
                login = $("#login").val(),
                password = $("#password").val();


            var errorText = "", errorType, error = false;

            if(email != ""){
                if (email.indexOf("@") < 0){
                    error = true;
                    swal({
                        title: "Error!",
                        text: "Invalid Email Address.",
                        type: "error",
                        confirmButtonClass: "btn-danger"
                    });
                }
            }

            if(!error){
                $.ajax({
                    type: "POST",
                    async: true,
                    cache: true,
                    url: "adminAjax.php",
                    data: "facultyid="+facultyid+
                          "&graduateschool="+graduateschool+
                          "&firstname="+firstname+
                          "&middlename="+middlename+
                          "&lastname="+lastname+
                          "&gender="+gender+
                          "&birthdate="+birthdate+
                          "&email="+email+
                          "&contactno="+contactno+
                          "&address="+address+
                          "&designationid="+designationid+
                          "&login="+login+
                          "&password="+password+
                          "&action=addFaculty",
                    success:
						function( data, status, xhr ) { 
                            if(data){
                                swal({
                                    title: "Error!",
                                    text: data,
                                    type: "error",
                                    confirmButtonClass: "btn-danger"
                                });
							}
                            else {
                                swal({
									title: "Success!",
                                    text: "Insertion of faculty is successful",
                                    type: "success"
								}).then(function(){
                                    window.setTimeout(function(){location.reload()},1000);	
                                })
                            }
                        }
                });
            }
        });
    </script>

    </body>
</html>