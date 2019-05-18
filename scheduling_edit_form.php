<!DOCTYPE html>

<?php 
    include 'facultyDashboard.php';
    include_once 'dbconfig.php';

if($_GET['edit_id'])
{
    $id = $_GET['edit_id']; 
    $stmt=$db_con->prepare("SELECT * FROM classes WHERE subj_code=:id");
    $stmt->execute(array(':id'=>$id));  
    $row=$stmt->fetch(PDO::FETCH_ASSOC);

}

?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRS | Editing of Schedule </title>


        <script type="text/javascript" src="assets/jquery-1.11.3-jquery.min.js"></script>



</head>
   <body>
    

<style type="text/css">
#dis{
    display:none;
}
</style>


    
    
    <div id="dis">

    </div>
        
        


     <form method='post' id='class-UpdateForm' action='#'>
  <div class="content-loader" style="width:250px; position: absolute; left: 26%;

    " >
        
        <table cellspacing="0" id="example" class="table table-striped table-hover table-responsive table-align-center table-bordered" >


  <input type="hidden" name="subj_code" value="<?php echo $id; ?>"/>
      

         <tr>
            <td>Course Code</td>
            
            <td><?php echo $row['subj_code']; ?> 
      
        <tr>

         <tr>
            <td>Course Title</td>
            
            <td><?php echo $row['subj_title']; ?> 
      
        <tr>
         <tr>
            <td>Units</td>
            
            <td><?php echo $row['unit']; ?> 
      
        <tr>

        <tr>
            <td>Schedule</td>
            
            <td><input type="dates" class = 'form-control' name="dates" value="<?php echo $row['dates']; ?>"  style="width:500px;"/>
      
        <tr>

     <tr>
            <td>Room</td>
            
            <td><input type="text" class = 'form-control' name="room" value="<?php echo $row['room']; ?>"  style="width:500px;"/>
      
        <tr>
<script type="text/javascript">
$(document).ready(function() {
    $("form-control").select2();
});
</script>

         <tr>
            <td>Professor</td>
            <td><select name="faculty_name" class="form-control" style="width:500px;">
         <?php $fac_id = $row['faculty_id']; ?>
                                    <?php
     session_start();
        $connect = mysqli_connect('localhost:3309', 'root', '123456', 'gp_test_copy');
        $subj = "SELECT * FROM faculty WHERE  faculty_id = $fac_id";
         $query = mysqli_query($connect, $subj);


                     while($row1 = mysqli_fetch_array($query))
        { 

            $fac_name =  $row1['name'];
        }

?>   <option> <?php echo $fac_name;  ?></option> 
         <?php

        session_start();
        $connect = mysqli_connect('localhost:3309', 'root', '123456', 'gp_test_copy');
        $subj = 'SELECT * FROM faculty ORDER BY name ASC';
         $query = mysqli_query($connect, $subj);


                     while($row1 = mysqli_fetch_array($query))
        {  
                echo "<option value='".$row1['faculty_id']."'>".$row1['name']."</option>";
        }

        ?>
        
        </select>
        </td></div>
        </tr>

         <tr>
            <td>Max Slots</td>
            
            <td><input type="number" class = 'form-control' name="maxslots" value="<?php echo $row['maxslots']; ?>"  style="width:500px;"/> </td>
      
        <tr>
         <tr>
            <td>Taken Slots</td>
            
            <td><?php echo $row['taken_slots']; ?>
      
        <tr>


               <tr>
            <td>Class Day</td>
            <td><select name='day' class='form-control'  style="width:500px;">
            <option value="Sat"> Saturday </option>
         <option value="Sun"> Sunday </option>

        </select>
        
        <tr>

       
        <tr>
            <td>Time Start</td>
            <td> <input type='time' name='timestart' class = 'form-control' value='<?php echo $row['timestart']; ?>'  style="width:500px;">  
        <tr>

         
        <tr>
            <td>Time End</td>
            <td><input type='time' name='timeend' class = 'form-control' value='<?php echo $row['timeend']; ?>'  style="width:500px;">  
        <tr>


           
            <td colspan="2">
            <button type="submit" class="btn btn-primary" name="btn-update" id="btn-update">
            <span class="glyphicon glyphicon-plus"></span> Save Updates
            </button>
            </td>
        </tr>
 </div>
    </table>
</form>

    <script src="../js/jquery-3.1.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap4.min.js"></script>
    <script src="../js/select2.min.js"></script>
    <script src="../js/sweetalert2.min.js"></script>


    </body>
</html>

    </body>
</html>
