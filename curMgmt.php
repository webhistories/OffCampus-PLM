<!DOCTYPE html>
<?php 
    include 'facultyDashboard.php';
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRS | Curriculum Management</title>
    </head>

    <body style="padding-top: 80px">
        <div class="col-xs-12">
            <div class="panel with-nav-tabs panel-primary">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#listCurriculum" data-toggle="tab"><b>List of Curriculum</b></a></li>
                        <li class=""><a href="#addCurriculum" data-toggle="tab"><b>Add New Curriculum</b></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade" id="listCurriculum">
                            <div class="table-responsive">
                                <table class="table table-hover table-align-center" id="curTable" style="cursor: pointer">
                                    <thead class="text-danger">
                                        <tr>
                                            <th>#</th>
                                            <th>Curriculum Name</th>
                                            <th>Programs Affected</th>
                                        </tr>
                                    </thead>
                                    <tfoot class="text-danger">
                                        <tr>
                                            <th>#</th>
                                            <th>Curriculum Name</th>
                                            <th>Programs Affected</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                            $curriculumSql = mysqli_query($connect, "SELECT DISTINCT a.curriculum_id
                                                FROM curriculum a
                                                LEFT JOIN programs b ON b.curriculum_id = a.curriculum_id
                                                JOIN subjects c ON c.subject_id = a.subject_id
                                                WHERE c.gradschool_id = $gradschoolid");
                                            $i=1;

                                            while($curriculum = mysqli_fetch_row($curriculumSql)){
                                                $programsSql = mysqli_query($connect, "SELECT count(program_name) FROM programs WHERE curriculum_id = '$curriculum[0]'");
                                                $programs = mysqli_fetch_row($programsSql);

                                                echo '
                                                        <tr data-toggle="modal" data-target="#'.$i.'Modal" 
                                                        id="'.$curriculum[0].'"  style="cursor: pointer">
                                                            <td>'.$i.'</td>
                                                            <td>'.$curriculum[0].'</td>
                                                            <td>'.$programs[0].'</td>
                                                        </tr>
                                                     ';
                                                $i++;
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php 
                                $curriculumSql = mysqli_query($connect, "SELECT DISTINCT a.curriculum_id
                                    FROM curriculum a
                                    LEFT JOIN programs b ON b.curriculum_id = a.curriculum_id
                                    JOIN subjects c ON c.subject_id = a.subject_id
                                    WHERE c.gradschool_id = $gradschoolid");
                                $i=1;

                                while($curriculum = mysqli_fetch_row($curriculumSql)){
                            ?>
                                <div class="modal fade" id="<?php echo $i.'Modal';?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="panel panel-primary">
                                            <div class="modal-header panel-heading">
                                                <span> <?php echo $curriculum[0]; ?> </span>
                                                <span class="pull-right" style='cursor:pointer;' data-dismiss="modal">×</span>
                                            </div>

                                            <div class="modal-body panel-body">
                                                <div class="row">
                                                    <div class="col-xs-12">            
                                                        <label>Affected Program/s</label> 
                                                        
                                                        <select id="programs" multiple style="width: 100%">
                                                            <?php
                                                                $programSql = mysqli_query($connect, "SELECT program_name, 
                                                                    CASE WHEN curriculum_id = '$curriculum[0]' THEN 'selected' ELSE '' END FROM programs WHERE gradschool_id = $gradschoolid");

                                                                for($ctr=0; $ctr<mysqli_num_rows($programSql); $ctr++){
                                                                    $program = mysqli_fetch_row($programSql);
                                                                    echo '  <option '.$program[1].'>
                                                                                '.$program[0].'
                                                                            </option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-align-center">
                                                                <thead class="text-danger">
                                                                    <tr>
                                                                        <td>Course Group</td>
                                                                        <td>Course Code</td>
                                                                        <td>Course Title</td>
                                                                        <td>Units</td>
                                                                        <td>Prerequsite/s</td>
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            <?php
                                    $i++;
                                }
                            ?>
                        </div>

                        <div class="tab-pane fade in active" id="addCurriculum">
                            <div class="row">
                                <div class="col-xs-5">
                                    <label>Curriculum Name</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <br>

                            <button class="btn btn-success" id="addCourseButton" data-toggle="modal" data-target="#courseGroupModal">
                                <i class="glyphicon glyphicon-plus"></i> Add Course Group
                            </button>

                            <br><br>

                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-align-center" id="subjectsTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Course Group</th>
                                                <th>Subject Name</th>
                                                <th>Pre requisite</th>
                                                <th>Units</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Course Group</th>
                                                <th>Subject Name</th>
                                                <th>Pre requisite</th>
                                                <th>Units</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                                if(isset($_SESSION['courseGroupArray'])){

                                                    $courseGroupArray = $_SESSION['courseGroupArray'];
                                                    for($i = 0; $i < count($courseGroupArray); $i++){

                                                        echo '  
                                                                <tr>
                                                                    <td></td>
                                                                    <td>'.$courseGroupNameArray[ $i ].'</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                             ';
                                                    }

                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="modal fade" id="courseGroupModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="panel panel-primary">
                                        <div class="modal-header panel-heading">
                                            <span>Add Course Group</span>
                                            <span class="pull-right" style='cursor:pointer;' data-dismiss="modal">×</span>
                                        </div>

                                        <div class="modal-body panel-body">
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <label>Course Groups</label> 
                                                    <select id="courseGroupSelect" style="width: 100%">
                                                        <option value="" disabled selected>Choose One</option>
                                                        <?php
                                                            $courseGroupsSql = mysqli_query($connect, "SELECT * FROM course_group WHERE group_title IS NOT NULL");
                                                            while($courseGroup = mysqli_fetch_assoc($courseGroupsSql)){
                                                                echo '
                                                                        <option value="'.$courseGroup['group_id'].'">'.$courseGroup['group_title'].'</option>
                                                                     ';
                                                            }
                                                            echo '<option value="NONE">No Course Group</option>';
                                                            echo '<option value="addNew">Other/Add New</option>';
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row newRow hidden">
                                                <br>

                                                <div class="col-xs-3">
                                                    <label>Group ID/Code</label>
                                                    <input type="text" class="form-control" id="newGroupCode">
                                                </div>

                                                <div class="col-xs-5">
                                                    <label>Group Title</label>
                                                    <input type="text" class="form-control" id="newGroupTitle">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer panel-footer">
                                            <button class="btn btn-success" id="saveNewCourseGroup">
                                                <i class="glyphicon glyphicon-plus"></i> Add
                                            </button>
                                    </div>
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
            $('#curTable').DataTable({
                "language": {
                  "emptyTable": "No curriculum available."
                },
                "bDeferRender": true
            });

            $('#subjectsTable').DataTable({
                "language": {
                  "emptyTable": "No subjects added."
                },
                "bDeferRender": true
            });

            $("select").select2();
        });

        $(".clickRow").click(function(){
            var id = this.id;

            alert(id);
        });

        $("#courseGroupSelect").change(function(){
            var val = $(this).val();

            if(val == 'addNew'){
                $(".newRow").removeClass('hidden');
            }
            else
                $(".newRow").addClass("hidden");
        });

        $("#saveNewCourseGroup").click(function(){
            var courseGroup = $("#courseGroupSelect").val(),
                newGroupCode = '', newGroupTitle = '', flag = 0;


            if(courseGroup == 'addNew'){
                var newGroupCode = $("#newGroupCode").val(),
                    newGroupTitle = $("#newGroupTitle").val();

                flag = 1;
            }

            // if(flag == 0)
            //     alert('13123sadasdasd');
            // else
            //     alert(flag);
            
             $.ajax({
                    type: "POST",
                    async: true,
                    cache: true,
                    url: "facultyAjax.php",
                    data: "courseGroup="+courseGroup+"&newGroupCode="+newGroupCode+"&newGroupTitle="+newGroupTitle+"&process="+flag+"&action=saveNewCourseGroup",
                    success: 
                        function(msg){
                            alert(msg);
                        }
            });



        });
    </script>

    </body>
</html>
