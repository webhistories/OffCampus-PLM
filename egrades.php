<!DOCTYPE html>
<?php 
    include "facultyDashboard.php";
    session_start();
    $connect = mysqli_connect('localhost:3309', 'root', '123456', 'gp_test_copy');



?>



<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
      <script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
        <title>CRS | EGrades</title>
    </head>

    <body style="80px
padding-top: 80px">

            <div class="container-fluid">
   <center>
    <h2>
Viewing/Editing of Grades
 </h2></center>
                

      <div class="col-xs-12" style="width:500px;    position: fixed;
    top:10;
    bottom: 100;
    left: 0;
    right: 0;

    margin: auto;">
            <div class="panel panel-primary">
          <div class="panel-heading">
                     
                </div>

    <div class="panel-body">

    <form method = "post" action = "egradesEDIT.php">   
    <div class="row"> 


    <label> &emsp;Block ID</label>&emsp;
        <input type="text" name="block_id" placeholder="ABC1"/>

<br><br>

       

<?php
    include '../config.php';
    $facultyid = $_SESSION['facultyid'];
    $facultySql = mysqli_query($connect, "SELECT * FROM faculty WHERE faculty_id = '$facultyid'");
    $faculty = mysqli_fetch_assoc($facultySql);
    $currentsem = $_SESSION['aysem'];

    $_SESSION['gradschoolid'] = $faculty['gradschool_id'];
    $gradschoolid = $_SESSION['gradschoolid'];
?>




        <?php 

        echo '<div class="col-md-12">';
         $grades = "SELECT DISTINCT subj_code, subj_title from classes WHERE faculty_id='$facultyid';";

         $query = mysqli_query($connect, $grades);


         echo "<label>Course Code:</label>" ?> <select name='subjDropdown'  id='subjDropdown' value='' class="form-control" style="width:200px;">
            <option value="Invalid Course Input!"  data-value="You need to Choose a course!"> Course Code </option>
         <?php
             while($row = mysqli_fetch_array($query))
    {

         

         echo "<option data-value='".$row['subj_title']."'>".$row['subj_code']."</option>";
      
        
}
        echo "</select>";
        ?>
<br> 
       &emsp;&emsp;&emsp;&emsp;&emsp; <input type="text" value="" class="form-control" style="width:350px;" id="subjTitle" disabled="disabled"/>
<br><br>
 <input type="hidden" name = "fac_id" value= '<?php echo $facultyid; ?>'/>
          <button class="btn btn-success" id="classesButton" name="btnclassSubmit" >
                                    <i class="glyphicon glyphicon-search"></i> View Grades
                                </button>
    </form>   </div> </form></div></div></div>
<!--<div id="ifYes" style="display: none;">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-hover table-align-center" id="studentsTable">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name of Student</th>
                            <th>Student No.</th>
                            <th>Course</th>
                            <th>Grade</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
            <tfoot>
                    




                </table>
            </div>
        </div></tfoot></table></div> <!-- if yes closing tags





       <script>
    function yesnoCheck(that) {
        if (that.value == $row['']) {
            alert("check");
            document.getElementById("ifYes").style.display = "block";
        } else {
            document.getElementById("ifYes").style.display = "none";
        }
    }
</script> -->
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


       <script type='text/javascript'>//<![CDATA[
$(window).load(function(){
$(function () {
    $('#subjDropdown').change(function () {
        $('#subjTitle').val($('#subjDropdown option:selected').attr('data-value'));
    });
});
});//]]> 

</script>


    </body>
</html>
