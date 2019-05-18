<!DOCTYPE html>
<?php 
    include "registrarDashboard.php";
    include "server.php";
    session_start();
    $connect = mysqli_connect('localhost:3309', 'root', '123456', 'gp_test_copy');

?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>CRS | Documents</title>
    </head>

    <body style="80px
padding-top: 80px">

      <div class="col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    
                </div>

    <div class="panel-body">

    <form method = "get" action = "gradesDocuments.php">   
    <div class="row"> 
        <?php 

        echo '<div class="col-md-12">';
        $grades = 'SELECT * FROM subjects';
         $query = mysqli_query($connect, $grades);
         echo "<select name='dropdown1' value=''><option>Subject Name</option>";
             while($row = mysqli_fetch_array($query))
    {

         

         echo "<option>".$row['subject_title']."</option>";
      
        
}
        echo "</select>";
        ?>

        <?php 

        echo '<div class="col-md-12">';
        $grades = 'SELECT * FROM faculty';
         $query = mysqli_query($connect, $grades);
         echo "<select name='dropdown2' value=''><option>Faculty</option>";
             while($row = mysqli_fetch_array($query))
    {

         

         echo "<option>".$row['name']."</option>";
        
        
}
        echo "</select>";
        ?>

        <button class="btn btn-success" id="classesButton" name="gradesSubmit" >
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
