<!DOCTYPE html>
<?php 
    include 'facultyDashboard.php';
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
                                    <thead class="text-danger">
                                        <tr>
                                            <th></th>
                                            <th>Faculty</th>
                                            <th>Designation</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="text-danger">
                                        <tr>
                                            <th></th>
                                            <th>Faculty</th>
                                            <th>Designation</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                            $facultySql = mysqli_query($connect, "SELECT * 
                                                        FROM faculty a 
                                                        JOIN faculty_designation b ON b.faculty_id = a.faculty_id 
                                                        JOIN designation c ON c.designation_id = b.designation_id 
                                                        JOIN users d ON d.user_id = a.faculty_id
                                                        WHERE b.gradschool_id = $gradschoolid AND d.active = '1'");
                                            $i=1;
                                            while($faculty = mysqli_fetch_assoc($facultySql)){
                                                echo '
                                                        <tr data-target="#'.$i.'Modal" id="'.$faculty['faculty_id'].'" style="cursor: pointer" data-toggle="modal">
                                                            <td>'.$i.'</td>
                                                            <td>'.ucwords(strtolower($faculty['name'])).'</td>
                                                            <td>'.$faculty['designation_title'].'</td>
                                                     ';
                                             $modalID = $i.'Modal';
                                        ?>
                                            <div class="modal fade" id="<?php echo $i;?>Modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="panel panel-primary">
                                                        <div class="modal-header panel-heading">
                                                            <span><?php echo $faculty['name']; ?></span>   
                                                            <span class="pull-right" style='cursor:pointer;' data-dismiss="modal">×</span>
                                                        </div>

                                                        <div class="modal-body panel-body">
                                                            <div class="row">
                                                                <div class="col-xs-6">
                                                                    <label for="text">Faculty ID</label>

                                                                    <!-- <select class="eFacultyid <?php echo $modalID; ?>" style="width:100%">
                                                                        <option value="" disabled>Choose Faculty</option>
                                                                        <?php
                                                                            $facultyOptionSql = mysqli_query($connect, "SELECT a.faculty_id, a.name 
                                                                                FROM faculty a 
                                                                                LEFT JOIN faculty_designation b ON b.faculty_id = a.faculty_id");


                                                                            while($facultyOption = mysqli_fetch_row($facultyOptionSql)){

                                                                                $string = $facultyOption[0].' - '.ucwords(strtolower($facultyOption[1]));
                                                                                if($facultyOption[0] == $faculty['faculty_id'])
                                                                                    echo '
                                                                                            <option value="'.$facultyOption[0].'" selected>
                                                                                            '.$string.'
                                                                                            </option>
                                                                                         ';   
                                                                                else
                                                                                    echo '
                                                                                            <option value="'.$facultyOption[0].'">
                                                                                            '.$string.'
                                                                                            </option>
                                                                                         ';   
                                                                            }
                                                                        ?>
                                                                    </select> -->
                                                                    <input type="text" 
                                                                           value="<?php echo $faculty['faculty_id']; ?>" class="center eFacultyid form-control" readonly>
                                                                </div>

                                                                <div class="col-xs-6">
                                                                    <label>Designation</label>
                                                                    <select class="eDesignation <?php echo $modalID; ?>" style="width:100%">
                                                                        <option value="" disabled>Choose Designation</option>
                                                                        <?php 
                                                                            $designationSql = mysqli_query($connect, "SELECT * FROM designation WHERE designation_id IN (1002,1003,1004)");

                                                                            while($designation = mysqli_fetch_assoc($designationSql)){
                                                                                if($designation['designation_id'] == $faculty['designation_id'])
                                                                                    echo '
                                                                                            <option value="'.$designation['designation_id'].'" selected>
                                                                                            '.$designation['designation_title'].'
                                                                                            </option>
                                                                                         ';
                                                                                else
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

                                                            <br>

                                                            <div class="row">
                                                                <div class="col-xs-4">
                                                                    <label>First Name</label>
                                                                    <input type="text" value="<?php echo $faculty['firstname']; ?>" class="center eFname form-control" readonly>
                                                                </div>

                                                                <div class="col-xs-4">
                                                                    <label>Middle Name</label>
                                                                    <input type="text" 
                                                                           value="<?php echo $faculty['middlename']; ?>" class="center eMname form-control" readonly>
                                                                </div>

                                                                <div class="col-xs-4">
                                                                    <label>Last Name</label>
                                                                    <input type="text" value="<?php echo $faculty['lastname']; ?>" class="center eLname form-control" readonly>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer panel-footer">
                                                            <center>
                                                                <button class="btn btn-primary eDelete <?php echo $modalID; ?>">    <i class="glyphicon glyphicon-remove"></i> Remove</button> 
                                                                <button class="btn btn-success eSave <?php echo $modalID; ?>">
                                                                <i class="glyphicon glyphicon-save"></i> Save Changes</button>
                                                            </center>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                            $i++;
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade " id="addFaculty">
                            <div class="row">
                                <div class="col-xs-4">
                                    <labeL>Faculty</labeL>
                                    <select id="facultyid" style="width:100%">
                                        <option value=" ">Choose Faculty</option>
                                        <?php
                                            $facultyOptionSql = mysqli_query($connect, "SELECT DISTINCT a.faculty_id, a.name 
                                                FROM faculty a 
                                                LEFT JOIN faculty_designation b ON b.faculty_id = a.faculty_id ORDER BY a.faculty_id ASC");


                                            while($facultyOption = mysqli_fetch_row($facultyOptionSql)){

                                                $string = $facultyOption[0].' - '.ucwords(strtolower($facultyOption[1]));
                                                echo '
                                                        <option value="'.$facultyOption[0].'">
                                                        '.$string.'
                                                        </option>
                                                     ';   
                                            }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-xs-4">
                                    <label>Designation</label>
                                    <select id="designationid" style="width:100%">
                                        <option value=" ">Choose Designation</option>
                                        <?php 
                                            $designationSql = mysqli_query($connect, "SELECT * FROM designation WHERE designation_id IN (1002,1003,1004)");

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

                            <br>

                            <div class="row">
                                <div class="col-xs-3">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control" readonly id="lastname">
                                </div>

                                <div class="col-xs-3">
                                    <label>First Name</label>
                                    <input type="text" class="form-control" readonly id="firstname">
                                </div>

                                <div class="col-xs-3">
                                    <label>Middle Name</label>
                                    <input type="text" class="form-control" readonly id="middlename">
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-xs-12">
                                    <center>
                                        <button class="btn btn-success" id="saveCreate" disabled>
                                        <i class="glyphicon glyphicon-save"></i> Save Changes</button>
                                    </center>
                                </div>
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
            $('#facultyTable').DataTable({
                "language": {
                  "emptyTable": "No subjects available."
                },
                "bDeferRender": true
            });

            $("select").select2();
        });

        // $(".eFacultyid").change(function(){
        //     var classes = $(this).attr("class");
        //     var classessArray = [], size = 0, modalID = '';

        //     if(classes && classes.length && classes.split) {
        //         classes = jQuery.trim(classes); 
        //         classes = classes.replace(/\s+/g,' ');
        //         classessArray = classes.split(' ');
        //         size = classessArray.length;
        //     }

        //     for (i = 0; i < size; i++) {
        //         if (classessArray[i].indexOf("Modal") >= 0)
        //             modalID = classessArray[i];
        //     }

        //     var facultyid = $("#" + modalID + " .eFacultyid").val();
        //     var designationid = $("#" + modalID + " .eDesignation").val();

        //     //alert(facultyid);
        //     $.ajax({
        //         type: "POST",
        //         url: "facultyAjax.php",
        //         data: "facultyid="+facultyid+"&designationid="+designationid+"&action=selectFaculty",
        //         success:
        //             function(data, status, xhr){
        //                 if(data == 'error'){
        //                     swal({
        //                         text: "Error!",
        //                         title: "Chosen "
        //                     });
        //                 }
        //             }
        //     });
        // });

        $("#facultyid").change(function(){
            var facultyid = $(this).val();

            $("#designationid").change();

            $.ajax({
                type: "POST",
                url: "facultyAjax.php",
                data: "facultyid="+facultyid+"&action=selectFaculty",
                success:
                    function(data, status, xhr){
                        data = data.split("|");
                        $("#lastname").val(data[0]);
                        $("#firstname").val(data[1]);
                        $("#middlename").val(data[2]);
                    }
            });
        });

        $("#designationid").change(function(){
            var facultyid = $("#facultyid").val(),
                designationid = $(this).val();

            if(facultyid != ' ' && designationid != ' '){
                $("#saveCreate").attr("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "facultyAjax.php",
                    data: "facultyid="+facultyid+"&designationid="+designationid+"&action=selectDesignationFaculty",
                    success:
                        function(data, status, xhr){
                            if(data == 'error'){
                                swal({
                                    title: "Error!",
                                    html: "Chosen faculty and designation is already existing. <br>Edit one or both fields",
                                    type: "error",
                                    confirmButtonClass: "btn-danger"
                                });
                            }
                            else
                                $("#saveCreate").attr("disabled", false);
                        }
                });
            }
        });

        $("#saveCreate").click(function(){
            var facultyid = $("#facultyid").val(),
                designationid = $("#designationid").val();

            $.ajax({
                type: "POST",
                url: "facultyAjax.php",
                data: "facultyid="+facultyid+"&designationid="+designationid+"&action=createFaculty",
                success:
                    function(data, status, xhr){
                        if(data == 'error'){
                            swal({
                                title: "Error!",
                                text: "Something went wrong. Please try again.",
                                type: "error",
                                confirmButtonClass: "btn-danger"
                            });
                        }
                        else{
                            swal({
                                title: "Success!",
                                text: "The faculty is succesfully added",
                                type: "success",
                                confirmButtonClass: "btn-success"
                            }).then(function(){
                                window.setTimeout(function(){location.reload()},10);
                            })
                        }   
                    }
                        
            });
        });
    </script>

    </body>
</html>
