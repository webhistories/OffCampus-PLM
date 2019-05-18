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
        <div class="col-md-12">
            <form method="POST">
                <div class="row">
                    <div class="col-xs-2">
                        <label>
                            Year
                        </label>

                        <select id="facLoadYear" name="facLoadYear" class="form-control" style="width: 100%">
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

                        <select id="facLoadTerm" name="facLoadTerm" class="form-control" style="width: 100%">
                            <option value="" disabled selected>Choose One</option>
                            <?php 
                                for($i=1;$i<4;$i++){
                                    echo '<option value="'.$i.'">'.$term[$i].'</option>';
                                }
                            ?>
                        </select>
                    </div>

                    <div class="col-xs-4">
                        <label>
                            Faculty
                        </label>

                        <select id="facLoadFaculty" name="facLoadFaculty" class="form-control" style="width:100%">
                            <option value=" " selected>Choose One</option>
                            <?php
                                $facultySql = mysqli_query($connect, "SELECT a.faculty_id, a.name FROM faculty a JOIN faculty_designation b ON a.faculty_id = b.faculty_id 
                                    WHERE b.gradschool_id = $gradschoolid AND b.designation_id = 1002");

                                while ($faculty = mysqli_fetch_assoc($facultySql)) {

                                    echo '
                                            <option name="createFaculty" value="'.$faculty['faculty_id'].'">'.ucwords(strtolower($faculty['name'])).'</option>
                                         ';
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <br>

                <div class="row">
                    <div class="col-xs-3">
                        <button class="btn btn-success" id="facLoadButton" name="facLoadButton">
                            <i class="glyphicon glyphicon-search"></i> View Faculty Loading
                        </button>
                    </div>
                </div>
            </form>

            <!-- <?php 
                if(isset($_POST['facLoadButton'])){
                    
                    echo '
                            <script type="text/javascript">
                                var facLoadYear = $("#facLoadYear").val(),
                                    facLoadTerm = $("#facLoadTerm").val(),
                                    facLoadFaculty = $("#facLoadFaculty").val();
                                alert(facLoadYear);

                            </script>
                         ';


                    $facLoadYear = $_POST['facLoadYear'];
                    $facLoadTerm = $_POST['facLoadTerm'];
                    $facLoadFaculty = $_POST['facLoadFaculty'];
                    $aysem = $facLoadYear.$facLoadTerm;

                    echo $facLoadFaculty;
                }
            ?> -->
        </div>

    <script src="../js/jquery-3.1.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap4.min.js"></script>
    <script src="../js/select2.min.js"></script>
    <script src="../js/sweetalert2.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $("select").select2();
        });

        $("#facLoadButton").click(function(){
            var facLoadYear = $("#facLoadYear").val(),
                facLoadTerm = $("#facLoadTerm").val(),
                facLoadFaculty = $("#facLoadFaculty").val();


        });
    </script>

    </body>
</html>
