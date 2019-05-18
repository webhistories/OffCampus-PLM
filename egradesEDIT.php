<?php 
    include "facultyDashboard.php";
    session_start();
    $connect = mysqli_connect('localhost:3309', 'root', '123456', 'gp_test_copy');



?>

<!DOCTYPE html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CRS | EGrades</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="../css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <link href="../css/select2.min.css" rel="stylesheet">
        <link href="../css/select2-bootstrap.min.css" rel="stylesheet">
        <link href="../css/sweetalert2.min.css" rel="stylesheet">
        <link href="../css/custom.css" rel="stylesheet">
        <link href="../css/custom2.css" rel="stylesheet">



<script type="text/javascript" src="assets/jquery-1.11.3-jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    
    $("#btn-view").hide();
    
    $("#btn-add").click(function(){
        $(".content-loader").fadeOut('slow', function()
        {
            $(".content-loader").fadeIn('slow');
            $(".content-loader").load('add_form.php');
            $("#btn-add").hide();
            $("#btn-view").show();
        });
    });
    
    $("#btn-view").click(function(){
        
        $("body").fadeOut('slow', function()
        {
            $("body").load('egradesEDIT.php');
            $("body").fadeIn('slow');
            window.location.href="egradesEDIT.php";
        });
    });
    
});
</script>

</head>

<body>
    


    <div class="container">



        
<?php 
            if(isset($_POST['btnclassSubmit']))
    {   
        $block_id = mysqli_real_escape_string($connect, $_POST['block_id']);
        $subject_code = mysqli_real_escape_string($connect, $_POST['subjDropdown']);
        $fac_id = mysqli_real_escape_string($connect, $_POST['fac_id']);


        $_SESSION['subject_code'] = $subject_code;
        $_SESSION['block_id'] = $block_id;
        $_SESSION['fac_id'] = $fac_id;

?>   



        <h2 class="form-signin-heading">
 <center>
    <h2>
Viewing/Editing of Grades
 </h2></center>
 <?php

        require 'dbconfig2.php';
        
       $stmt = $db_con->prepare("SELECT * FROM blocks WHERE block_id = '$block_id'");
        $stmt->execute();

        while($row=$stmt->fetch(PDO::FETCH_ASSOC))
        {

                $starttime = $row['starttime'];
                $endtime = $row['endtime'];
                $syear = $row['syear'];
                $day = $row['day'];
                $agency = $row['agency'];


            }?>

             <?php
        require 'dbconfig2.php';
        
       $stmt2 = $db_con->prepare("SELECT * FROM classes WHERE subj_code = '$subject_code'");
        $stmt2->execute();

        while($row=$stmt2->fetch(PDO::FETCH_ASSOC))
        {

                $subj_title = $row['subj_title'];


            }?>




        
        <?php echo "$subj_title"; ?> - <?php echo "$subject_code"; ?>
        <br>
        <?php echo "$agency"; ?> - <?php echo "$block_id"; ?> <br>
        <?php echo "$day"; ?> / <?php echo date('h:i a', strtotime($starttime)) ?>-<?php echo date('h:i a', strtotime($endtime)) ?> <br> 

        School Year <?php echo "$syear"; ?>
        </h2><hr />

     <?php echo'       
       
          <div class="content-loader">
        
        <table cellspacing="0" width="100%" id="example" class="table table-striped table-hover table-responsive">
        <thead>
        <tr>
        <th>Student Number</th>
        <th>Student Name</th>
        <th>Semester/<br>Trimester</th>
        <th>Term</th>
        <th>Remarks</th>
        <th>Grade</th>
        
        <th>ADD/EDIT</th>
     <!--   <th>delete</th>-->
        </tr>
        </thead>
        <tbody>'
        ?>
        <?php
        require_once 'dbconfig2.php';
        
       $stmt = $db_con->prepare("SELECT * FROM students_grades WHERE subj_code = '$subject_code' AND faculty_id = '$fac_id' AND block_id = '$block_id' ORDER BY name ASC");
        $stmt->execute();
        while($row=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            ?>
            <tr>
            <td><?php echo $row['student_id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['semester']; ?></td>
            <td><?php echo $row['term']; ?></td>
            <td><?php echo $row['remarks']; ?></td>
            <td><?php echo $row['grade']; ?></td>
    
            <td align="center">
            <a id="<?php echo $row['student_id']; ?>" class="edit-link" href="#" title="Edit">
            <img src="edit.png" width="20px" />
            </a></td>
            

            <!-- THIS IS TO DELETE RECORDS
            <td align="center"><a id="<?php //echo $row['student_id']; ?>" class="delete-link" href="#" title="Delete">
            <img src="delete.png" width="20px" />
            </a></td> --> 
            </tr>

            


            <?php
        }
        ?>
        </tbody>
        </table>
        

        </div>

    </div>
    

    <br />
    
<?php  }  ?>

          <center><a href="/gpcrs.icto.plm/ROG.php" class="btn btn-primary"> <span class="glyphicon glyphicon-print"></span> Print Report of Grades </a></center> 
    
<script src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/datatables.min.js"></script>
<script type="text/javascript" src="crud.js"></script>

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
    $('#example').DataTable();

    $('#example')
    .removeClass( 'display' )
    .addClass('table table-bordered');
});
</script>

    <script src="../js/jquery-3.1.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap4.min.js"></script>
    <script src="../js/select2.min.js"></script>
    <script src="../js/sweetalert.min.js"></script>
     <script type="text/javascript">
        $(document).ready(function(){
            $("#mainNav").addClass("hidden");
            $("#subNav").removeClass("hidden");

            $('#classesTable').DataTable({
                "language": {
                  "emptyTable": "No classes available."
                },
                "bDeferRender": true
            });

            $("select").select2();
        });

        $("#year, #term").change(function(){
            var year = $("#year").val(), term = $("#term").val();

            // if(year != null && term != null){
            //     $("#classesButton").attr("disabled", false);
            // }
            // else{
            //     $("#classesButton").attr("disabled", true);
            // }
        })

        $(".clickRow").click(function(){
            var id = this.id;

            $.ajax({
                type: "POST",
                url: "facultyAjax.php",
                data: "classid="+id+"&action=sessionClass",
                success:
                    function(msg){
                        window.location = ("egradesSub.php");
                    }
            })
        });
    </script> 


</body>
</html>