<?php
include_once 'dbconfig.php';

if($_GET['edit_id'])
{
    $id = $_GET['edit_id']; 
    $stmt=$db_con->prepare("SELECT * FROM students_grades WHERE student_id=:id");
    $stmt->execute(array(':id'=>$id));  
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
}


if($row['remarks']=="NOT PAID")
{
    ?> <h2 class="form-signin-heading"> You will not be able to add grade to the student until the payment is fulfilled. </h2>
    <br> <button onclick="myFunction()" class="btn btn-primary"> Back </button>

<?php }

else { ?>
<style type="text/css">
#dis{
    display:none;
}
</style>


    
    
    <div id="dis">
    
    </div>
        
    
     <form method='post' id='emp-UpdateForm' action='#'>
 
    <table class='table table-bordered'>



        <input type='hidden' name='id' value='<?php echo $row['student_id']; ?>' />
        <tr>
            <td>Student Name</td>
            <td><input type='text' name='name' class='form-control' value='<?php echo $row['name']; ?>' readonly/></td>
        </tr>
 
      <!--  <tr>
            <td>Remarks</td>

            <td>

            <input type='text' name='remarks' class='form-control' value='<?php echo $row['remarks']; ?>' readonly/></td>
        </tr>-->
 
        <tr>
            <td>Grades</td>
            <td>
<select name ="grade" class = "form-control" required> 

        <option value="<?php echo $row['grade']; ?>"> <?php echo $row['grade']; ?> </option>
        <option value="1.00"> 1.00 </option>
        <option value="1.25"> 1.25 </option>
        <option value="1.50"> 1.50 </option>
        <option value="1.75"> 1.75 </option>
        <option value="2.00"> 2.00 </option>
        <option value="2.25"> 2.25 </option>
        <option value="2.50"> 2.50 </option>
        <option value="2.75"> 2.75 </option>
        <option value="3.00"> 3.00 </option>
        <option value="5.00"> 5.00 </option>

        </select>  
            </td>
        </tr>
 
        <tr>
            <td colspan="2">
            <button type="submit" class="btn btn-primary" name="btn-update" id="btn-update">
            <span class="glyphicon glyphicon-plus"></span> Save Updates
            </button>
            </td>
        </tr>
 
    </table>
</form>
     
<?php } ?>

<script>
function myFunction() {
    location.reload();
}
</script>