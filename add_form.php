
<!DOCTYPE html>
<?php 
    include 'facultyDashboard.php';
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script type="text/javascript" src="jquery.min.js"></script>
        <script type="text/javascript" src="js.js"></script>
          <script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
        <title>CRS | Data Entry</title>



    </head>

     <body style="80px
padding-top: 80px">



	
    
    <div id="dis">
    <!-- here message will be displayed -->
	</div>
        
 	
    <center>
    <h2>
Data Entry: Block Schedule </h2>
 <br> <em>* - indicates a required field</em></center>

	 <form method='post' id='class-SaveForm' action="#">
  <div class="content-loader" style="width:250px; position: absolute; left: 26%;

    " >
        
        <table cellspacing="0" id="example" class="table table-striped table-hover table-responsive table-align-center table-bordered" >

        <tr>
            <td> Agency*</td>
            <td><input type='text' name='agency' class = 'form-control' placeholder="Agency Name" style="width:500px;" required/>  </td>
        </tr>

         <tr>
            <td>School Year*</td>
            <td><input type='text' name='syear' class = 'form-control' style="width:500px;" required/>  </td>
        </tr>

         <tr>
            <td>Semester/Trimester*</td>
            <td>    <select name ="semester" class = "form-control" style="width:80px;"> 

        <option value="1st"> 1st </option>
        <option value="2nd"> 2nd </option>
        </select> </td>
        </tr>

         <tr>
            <td>Term*</td>
            <td>    <select name ="term" class = "form-control" style="width:80px;" > 

        <option value="1st"> 1st </option>
        <option value="2nd"> 2nd </option>
        <option value="3rd"> 3rd </option>
        <option value="4th"> 4th </option>

        </select>  </td>
        </tr>


        <div class="">
        <tr>
        <td>Program* </td>
        <td><select name="country" id="country" class="form-control" style="width:500px;" required>
            <option value=''>------- Select --------</option>
            <?php 
            session_start();

             include "db.php";
            $sql = "select * from `program_name`";
            $res = mysqli_query($con, $sql);
            if(mysqli_num_rows($res) > 0) {
                while($row = mysqli_fetch_object($res)) {
                    echo "<option value='".$row->id."' data-value='".$row->program_title."'>".$row->program_code."</option>";
                }
            }
            ?>
        </select> 

        <input type="text" value="" class="form-control" style="width:500px;" id="progTitle" disabled="disabled"/>

        </td>

        </tr>
        <tr>
        <td>Course Code* </td>
        <td><select name="state" id="state" class="form-control" style="width:500px;" required><option>------- Select --------</option>

        </select> <input type="text" value="" class="form-control" style="width:500px;" id="subjTitle" disabled="disabled"/> </td>

         <tr>
            <td>Units</td>
            <td><input type='number' name='unit' class = 'form-control' min="1" max="5" style="width:500px;" />  </td>
        </tr>

    </div>
        
          <tr>
            <td>Block ID*</td>
            <td><input type='text' name='id' class = 'form-control' style="width:500px;" required/>  </td>
        </tr>

        <tr>
            <td>Professor</td>
            <td><select name='faculty_name' class='form-control' style="width:500px;">
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
        </td>
        </tr>


               <tr>
            <td>Class Day</td>
            <td><select name='day' class='form-control' style="width:500px;">
            <option value="Sat"> Saturday </option>
         <option value="Sun"> Sunday </option>

        </select>
        </td>
        </tr>

       
        <tr>
            <td>Time Start</td>
            <td> <input type='time' name='timestart' class = 'form-control' value='' style="width:500px;" >  </td>
        </tr>

         
        <tr>
            <td>Time End</td>
            <td><input type='time' name='timeend' class = 'form-control' value='' style="width:500px;" >  </td>
        </tr>

     <tr>
            <td>Schedules</td>
            <td> 
                <div class="form-dates">  
                     <form name="add_date" id="add_date">  
                          <div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr>  
                                         <td><input type="date" name="dates[]" placeholder="Add Dates" class="form-control date_list" /></td>  
                                         <td><button type="button" name="add" id="add" class="btn btn-success">Add Dates</button></td>
                                    </tr>  
                               </table>  
                               <!--<input type="button" name="submit" id="submit" class="btn btn-dates" value="Submit" />  -->
                          </div>  
                     </form>  
                </div>  
          
            </td>
    
<script>  
        $(document).ready(function(){  
        var i=1;  
        $('#add').click(function(){  
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><input type="date" name="dates[]" placeholder="Add Dates" class="form-control name_list" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
        });  
        $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
        });  
        $('#submit').click(function(){            
            $.ajax({  
                url:"create.php",  
                method:"POST",  
                data:$('#add_date').serialize(),  
                success:function(data)  
                {  
                     alert(data);  
                     $('#add_date')[0].reset();  
                }  
            });  
        });  
        });  
        </script>

          
         
        
        <tr>
            <td>Room</td>
            <td><input type='text' name='room' class = 'form-control' style="width:500px;"/>  </td>
        </tr>

        
           <tr>
            <td>Max Slots</td>
            <td><input type='number' name='maxslots' class = 'form-control' style="width:500px;" />  </td>
        </tr>
       
        <tr>
            <td>Taken Slots</td>
            <td> <input type='number' name='taken_slots' class = 'form-control' style="width:500px;" readonly /> </td>
        </tr>

<!-- <tr>
            <td>Status</td>
            <td> <select name="status" class="form-control">
             <option value="petitioned"> Petitioned </option>
            <option value="dissolved"> Dissolved </option>
            <option value="closed"> Closed</option>
            </td>
        </tr> -->

 
        <tr>
            <td colspan="2">
            <button type="submit" class="btn btn-primary" name="btn-save" id="btn-save">
    		<span class="glyphicon glyphicon-plus"></span> Save this Record
			</button>  
            </td>
        </tr>
 </tr>
 </div>
    </table>
</form>
     
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap4.min.js"></script>
    <script src="../js/select2.min.js"></script>
    <script src="../js/sweetalert2.min.js"></script>
  <script type="text/javascript" src="classJS.js"></script> <!-- for editing -->


        <script type='text/javascript'>//<![CDATA[
$(window).load(function(){
$(function () {
    $('#country').change(function () {
        $('#progTitle').val($('#country option:selected').attr('data-value'));
    });
});
});//]]> 

</script>

 <script type='text/javascript'>//<![CDATA[
$(window).load(function(){
$(function () {
    $('#state').change(function () {
        $('#subjTitle').val($('#state option:selected').attr('data-value'));
    });
});
});//]]> 

</script>

    </body>
</html>