<!DOCTYPE html>
<?php 
    include 'applicantDashboard.php';
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>CRS | Documents</title>
    </head>

    <body style="padding-top: 80px">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 style="color:white">
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
                                <input type="text" class="form-control" id="applicantid" value="<?php echo $applicantid; ?>" readonly>
                            </div>
                        </div>

                        <div class="col-xs-3">
                            <label>Program</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-user"></i>
                                </span>
                                <input type="text" class="form-control" value="<?php echo $applicantInfo['program_name']; ?>" readonly>
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
                                <input type="text" class="form-control" id="firstname" value="<?php echo $applicantInfo['firstname']; ?>">
                            </div>
                        </div>

                        <div class="col-xs-4">
                            <label>Middle Name</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-user"></i>
                                </span>
                                <input type="text" class="form-control" id="middlename" value="<?php echo $applicantInfo['middlename']; ?>">
                            </div>
                        </div>

                        <div class="col-xs-4">
                            <label>Last Name</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-user"></i>
                                </span>
                                <input type="text" class="form-control" id="lastname" value="<?php echo $applicantInfo['lastname']; ?>">
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
                                <input type="text" class="form-control" id="gender" value="<?php echo $applicantInfo['gender']; ?>">
                            </div>
                        </div>

                        <div class="col-xs-3">
                            <label>Birthdate</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-calendar"></i>
                                </span>
                                <input type="text" class="form-control" id="birthdate" value="<?php echo $applicantInfo['birthdate']; ?>">
                            </div>
                        </div>

                        <div class="col-xs-3">
                            <label>Email Address</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-user"></i>
                                </span>
                                <input type="text" class="form-control" value="<?php echo $applicantInfo['email']; ?>" readonly>
                            </div>
                        </div>

                        <div class="col-xs-4">
                            <label>Address</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="glyphicon glyphicon-home"></i>
                                </span>
                                <input type="text" class="form-control" id="address" value="<?php echo $applicantInfo['address']; ?>">
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
                                                <input type="text" class="form-control" id="faname" value="<?php echo $applicantInfo['faname']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-user"></i>
                                                </span>
                                                <input type="text" class="form-control" id="maname" value="<?php echo $applicantInfo['maname']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-user"></i>
                                                </span>
                                                <input type="text" class="form-control" id="guname" value="<?php echo $applicantInfo['guname']; ?>">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Address</th>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="faaddress">
                                                    <input type="checkbox" aria-label="..." id="facheck">
                                                </span>

                                                <input type="text" class="form-control" id="faaddress">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="maaddress">
                                                    <input type="checkbox" aria-label="..." id="macheck">
                                                </span>

                                                <input type="text" class="form-control" id="maaddress">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon" id="guaddress">
                                                    <input type="checkbox" aria-label="..." id="gucheck">
                                                </span>

                                                <input type="text" class="form-control" id="guaddress">
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
                                                <input type="number" class="form-control" id="facontact" value="<?php echo $applicantInfo['facontact']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-phone"></i>
                                                </span>
                                                <input type="number" class="form-control" id="macontact" value="<?php echo $applicantInfo['macontact']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-phone"></i>
                                                </span>
                                                <input type="number" class="form-control" id="gucontact" value="<?php echo $applicantInfo['gucontact']; ?>">
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
                                                <input type="text" class="form-control" id="faoccupation" value="<?php echo $applicantInfo['faoccupation']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-briefcase"></i>
                                                </span>
                                                <input type="text" class="form-control" id="maoccupation" value="<?php echo $applicantInfo['maoccupation']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-briefcase"></i>
                                                </span>
                                                <input type="text" class="form-control" id="guoccupation" value="<?php echo $applicantInfo['guoccupation']; ?>">
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
                                                <input type="text" class="form-control" id="faoffice" value="<?php echo $applicantInfo['faoffice']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-briefcase"></i>
                                                </span>
                                                <input type="text" class="form-control" id="maoffice" value="<?php echo $applicantInfo['maoffice']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-briefcase"></i>
                                                </span>
                                                <input type="text" class="form-control" id="guoffice" value="<?php echo $applicantInfo['guoffice']; ?>">
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
                                                <input type="text" class="form-control" id="elschool" value="<?php echo $applicantInfo['elSchool']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-user"></i>
                                                </span>
                                                <input type="text" class="form-control" id="hischool" value="<?php echo $applicantInfo['hiSchool']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-user"></i>
                                                </span>
                                                <input type="text" class="form-control" id="coschool" value="<?php echo $applicantInfo['coSchool']; ?>">
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
                                                <input type="number" class="form-control" id="elentered" value="<?php echo $applicantInfo['elEntered']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-calendar"></i>
                                                </span>
                                                <input type="number" class="form-control" id="hientered" value="<?php echo $applicantInfo['hiEntered']; ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-calendar"></i>
                                                </span>
                                                <input type="number" class="form-control" id="coentered" value="<?php echo $applicantInfo['coEntered']; ?>">
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
                                                <input type="number" class="form-control" id="elgraduated" value="<?php echo $applicantInfo['elGraduated']; ?>">
                                            </div>
                                        </td>  
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-book"></i>
                                                </span>
                                                <input type="number" class="form-control" id="higraduated" value="<?php echo $applicantInfo['hiGraduated']; ?>">
                                            </div>
                                        </td>   
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-book"></i>
                                                </span>
                                                <input type="number" class="form-control" id="cograduated" value="<?php echo $applicantInfo['coGraduated']; ?>">
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
                                                <input type="text" class="form-control" id="codegree" value="<?php echo $applicantInfo['coDegree']; ?>">
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
                                                <input type="text" class="form-control" id="comajor" value="<?php echo $applicantInfo['coMajor']; ?>">
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
                                                <input type="number" class="form-control" id="elaverage" value="<?php echo $applicantInfo['elAverage']; ?>">
                                            </div>
                                        </td>             
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-star"></i>
                                                </span>
                                                <input type="number" class="form-control" id="hiaverage" value="<?php echo $applicantInfo['hiAverage']; ?>">
                                            </div>
                                        </td>             
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-star"></i>
                                                </span>
                                                <input type="number" class="form-control" id="coaverage" value="<?php echo $applicantInfo['coAverage']; ?>">
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
                        <!-- <button class="btn btn-primary" id="printButton">
                            <i class="glyphicon glyphicon-print"></i>
                            &nbsp Print SPAR
                        </button> -->

                        <button class="btn btn-success" id="saveButton">
                            <i class="glyphicon glyphicon-save"></i>
                            &nbsp Save Changes
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
    	$("#facheck, #macheck, #gucheck").change(function(){
            var x = $(this).parent().attr("id");
            var address = $("input#address").val();
            var ischecked = this.checked ? $("input#"+x).val(address) : $("input#"+x).val('');
        });

        $("#printButton").click(function(){
            window.open('../printables/printSPAR.php','_blank');
        });

        $("#saveButton").click(function(){
    	 	var firstname = $("#firstname").val(),
                middlename = $("#middlename").val(),
                lastname = $("#lastname").val(),
                gender = $("#gender").val(),
                birthdate = $("#birthdate").val(),
                email = $("#email").val(),
                address = $("#address").val(),

                faname = $("#faname").val(),
                faaddress = $("#faaddress").val(),
                facontact = $("#facontact").val(),
                faoccupation = $("#faoccupation").val(),
                faoffice = $("#faoffice").val(),

                maname = $("#maname").val(),
                maaddress = $("#maaddress").val(),
                macontact = $("#macontact").val(),
                maoccupation = $("#maoccupation").val(),
                maoffice = $("#maoffice").val(),

                guname = $("#guname").val(),
                guaddress = $("#guaddress").val(),
                gucontact = $("#gucontact").val(),
                guoccupation = $("#guoccupation").val(),
                guoffice = $("#guoffice").val(),

                elschool = $("#elschool").val(),
                elentered = $("#elentered").val(),
                elgraduated = $("#elgraduated").val(),
                elaverage = $("#elaverage").val(),

                hischool = $("#hischool").val(),
                hientered = $("#hientered").val(),
                higraduated = $("#higraduated").val(),
                hiaverage = $("#hiaverage").val(),

                coschool = $("#coschool").val(),
                coentered = $("#coentered").val(),
                cograduated = $("#cograduated").val(),
                codegree = $("#codegree").val(),
                comajor = $("#comajor").val(),
                coaverage = $("#coaverage").val();

            $.ajax({
                type: "POST",
                async: true,
                cache: true,
                url: "applicantAjax.php",
                data: "firstname="+firstname+
                      "&middlename="+middlename+
                      "&lastname="+lastname+
                      "&gender="+gender+
                      "&birthdate="+birthdate+
                      "&email="+email+
                      "&address="+address+
                      "&faname="+faname+
                      "&faaddress="+faaddress+
                      "&facontact="+facontact+
                      "&faoccupation="+faoccupation+
                      "&faoffice="+faoffice+
                      "&maname="+maname+
                      "&maaddress="+maaddress+
                      "&macontact="+macontact+
                      "&maoccupation="+maoccupation+
                      "&maoffice="+maoffice+
                      "&guname="+guname+
                      "&guaddress="+guaddress+
                      "&gucontact="+gucontact+
                      "&guoccupation="+guoccupation+
                      "&guoffice="+guoffice+
                      "&elschool="+elschool+
                      "&elentered="+elentered+
                      "&elgraduated="+elgraduated+
                      "&elaverage="+elaverage+
                      "&hischool="+hischool+
                      "&hientered="+hientered+
                      "&higraduated="+higraduated+
                      "&hiaverage="+hiaverage+
                      "&coschool="+coschool+
                      "&coentered="+coentered+
                      "&cograduated="+cograduated+
                      "&coaverage="+coaverage+
                      "&codegree="+codegree+
                      "&comajor="+comajor+
                      "&action=save",
                success: 
                	function( data, status, xhr ){
                		swal({
							title: "Success!",
							html: "Successfully updated information!",
							type: "success"                			
                		}).then(function(){
                            window.setTimeout(function(){location.reload()},10);
                		});
                	}
            });
        });
    </script>
    </body>
</html>
