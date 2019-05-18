<!DOCTYPE html>
<?php 
    include 'facultyDashboard.php';
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRS | Subject Management</title>
    </head>

    <body style="padding-top: 80px">
        <div class="col-md-12">
            <div class="panel with-nav-tabs panel-primary">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#listSubjects" data-toggle="tab"><b>List of Subjects</b></a></li>
                        <li class=""><a href="#createSubjects" data-toggle="tab"><b>Create New Subject</b></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="listSubjects">
                            <div class="table-responsive">
                                <table class="table table-hover table-align-center" id="classTable">
                                    <thead class="text-danger">
                                        <tr>
                                            <th></th>
                                            <th>Subject Code</th>
                                            <th>Subject Name</th>
                                            <th>Units</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="text-danger">
                                        <tr>
                                            <th></th>
                                            <th>Subject Code</th>
                                            <th>Subject Name</th>
                                            <th>Units</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                            $subjectsSql = mysqli_query($connect, "SELECT * FROM subjects WHERE gradschool_id = $gradschoolid");
                                            $im=1;
                                            while( $subjects = mysqli_fetch_assoc($subjectsSql) ){
                                            
                                                echo '
                                                        <tr class="open-modal" data-target="#'.$im.'Modal" data-toggle="modal" style="cursor:pointer">    
                                                            <td>'.$im.'</td>
                                                            <td>'.$subjects['subject_name'].'</td>
                                                            <td>'.$subjects['subject_title'].'</td>
                                                            <td>'.number_format($subjects['units'],1).'</td>
                                                        </tr>
                                                     ';

                                                $modalID = $im.'Modal';
                                        ?>
                                            <div class="modal fade" id="<?php echo $modalID;?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                        <div class="panel panel-primary">

                                                            <div class="modal-header panel-heading">
                                                                <span> <?php echo $subjects['subject_title']; ?> </span>
                                                                <span class="pull-right" style='cursor:pointer;' data-dismiss="modal">×</span>
                                                            </div>

                                                            <div class="modal-body panel-body">
                                                                <div class="row">
                                                                    <div class="col-lg-4">
                                                                        <label>Subject Code</label>
                                                                        <input type="text" 
                                                                               value="<?php echo $subjects['subject_name']; ?>" class="eSubjectCode form-control">
                                                                        <input type="hidden" 
                                                                               value="<?php echo $subjects['subject_id']; ?>" class="eSubjectID form-control">
                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <label>Subject Units</label>
                                                                        <input type="number" 
                                                                               value="<?php echo $subjects['units']; ?>" 
                                                                               class="eSubjectUnits form-control">
                                                                    </div>
                                                                </div>

                                                                <br>

                                                                <div class="row">
                                                                    <div class="col-lg-10">
                                                                        <label>Subject Name</label>
                                                                        <input type="text" 
                                                                               value="<?php echo ucwords(strtolower($subjects['subject_title'])); ?>" 
                                                                               class="eSubjectName form-control">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer panel-footer">
                                                                <center>
                                                                    <button class="btn btn-primary eDelete <?php echo $modalID; ?>">
                                                                    <i class="glyphicon glyphicon-remove"></i> Remove</button> 
                                                                    <button class="btn btn-success eSave <?php echo $modalID; ?>">
                                                                    <i class="glyphicon glyphicon-save"></i> Save Changes</button>
                                                                </center>
                                                            </div>

                                                        </div>
                                                </div>
                                            </div>
                                        <?php
                                            $im++;
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade " id="createSubjects">
                            <div class="row">
                                <div class="col-xs-3">
                                    <label>Subject Code</label>
                                    <input type="text" id="subjectCode" class="form-control">
                                </div>

                                <div class="col-xs-3">
                                    <label>Subject Units</label>
                                    <input type="number" id="subjectUnits" min=0 max=6 class="form-control">
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-xs-10">
                                    <label>Subject Name</label>
                                    <input type="text" name="subjectName" id="subjectName" class="form-control">
                                </div>
                            </div>
                            <br>
                            <div class="col-xs-10">
                                <center>
                                    <button class="btn btn-success" id="saveNewSubject">
                                    <i class="glyphicon glyphicon-save"></i> Save Changes</button>
                                </center>
                            </div>
                        </div>
                    </div>
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
            $('#classTable').DataTable({
                "language": {
                  "emptyTable": "No subjects available."
                },
                "bDeferRender": true
            });

            $("select").select2();
        });

        $(".eDelete").click(function(){

            var classes = $(this).attr("class");
            var classessArray = [], size = 0, modalID = '';

            if(classes && classes.length && classes.split) {
                classes = jQuery.trim(classes); 
                classes = classes.replace(/\s+/g,' ');
                classessArray = classes.split(' ');
                size = classessArray.length;
            }

            for (i = 0; i < size; i++) {
                if (classessArray[i].indexOf("Modal") >= 0)
                    modalID = classessArray[i];
            }

            var subjectCode = $("#" + modalID + " .eSubjectCode").val();
            //alert(subjectCode);

            swal({
                title: "Confirm Action",
                text: "Delete this subject?",
                type: "error",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }).then(function(){
                $.ajax({
                    type: "POST",
                    url: "facultyAjax.php",
                    data: "subjectCode="+subjectCode+"&action=deleteSubject",
                    success:
                        function(){
                            setTimeout(function () {
                                swal({
                                    title: "Deleted!",
                                    text: "This subject has been deleted successfully",
                                    type: "success"
                                },
                                function(){
                                    window.setTimeout(function(){location.reload()},100);
                                });

                                
                            }, 1000);  
                        }
                });
            });
        });

        $(".eSave").click(function(){
            var classes = $(this).attr("class");
            var classessArray = [], size = 0, modalID = '';

            if(classes && classes.length && classes.split) {
                classes = jQuery.trim(classes); 
                classes = classes.replace(/\s+/g,' ');
                classessArray = classes.split(' ');
                size = classessArray.length;
            }

            for (i = 0; i < size; i++) {
                if (classessArray[i].indexOf("Modal") >= 0)
                    modalID = classessArray[i];
            }

            var subjectCode = $("#" + modalID + " .eSubjectCode").val(),
                subjectName = $("#" + modalID + " .eSubjectName").val(),
                subjectUnits = $("#" + modalID + " .eSubjectUnits").val(),
                subjectID = $("#" + modalID + " .eSubjectID").val();

            if(subjectCode == ''){
                swal({
                    title: "Error!",
                    text: "Fill out Subject Code.",
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });
            }                
            else
            {
                $.ajax({
                    type: "POST",
                    url: "facultyAjax.php",
                    data: "subjectID="+subjectID+"&subjectCode="+subjectCode+"&subjectName="+subjectName+"&subjectUnits="+subjectUnits+"&action=saveSubject",
                    success:
                        function(msg) {
                            if (msg) {
                                swal({
                                    title: "Error!",
                                    text: msg,
                                    type: "error",
                                    confirmButtonClass: "btn-danger"
                                });
                            }
                            else {        
                                swal({
                                    title: "Success!",
                                    html: "This class has been successfully updated.",
                                    type: "success"
                                }).then(function(){
                                    window.setTimeout(function(){location.reload()},10);
                                })
                            }
                        }
                }); 
            }
        });

        $("#saveNewSubject").click(function(){
            var subjectCode = $("#subjectCode").val(),
                subjectName = $("#subjectName").val(),
                subjectUnits = $("#subjectUnits").val();


            if(subjectName == '' || subjectName == ' ' || subjectCode == '' || subjectCode == ' '){
                swal({
                    title: "Error",
                    type: "error",
                    html: "Fill out all fields"
                })
            }                
            else
            {
                $.ajax({
                    type: "POST",
                    url: "facultyAjax.php",
                    data: "subjectCode="+subjectCode+"&subjectName="+subjectName+"&subjectUnits="+subjectUnits+"&action=saveSubject"+"&subjectID=0",
                    success:
                        function(msg) {
                            if (msg) {
                                swal({
                                    title: "Error",
                                    type: "error",
                                    html: msg
                                })
                            }
                            else {              
                                swal({
                                    title: "Success!",
                                    type: "success",
                                    html: "Subject has been successfully added"
                                }).then(function(){
                                    window.setTimeout(function(){location.reload()},10);
                                })
                            }
                        }
                }); 
            }   
        });
    </script>

    </body>
</html>
