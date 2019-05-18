<!DOCTYPE html>
<?php 
    include 'facultyDashboard.php';
    global $block_id;
global $term;
global $sem;
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRS | Viewing of Schedules</title>
    </head>


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
			$("body").load('scheduling_view.php');
			$("body").fadeIn('slow');
			window.location.href="scheduling_view.php";
		});
	});
	
});
</script>

</head>
   

     <body style="80px
padding-top: 50px; padding-right: 200px;">

      <center>
    <h1>
 Block Schedule
 </h1></center>


        
<?php 

        	if(isset($_POST['btnclassSubmit']))
	{	
		$block_id = mysqli_real_escape_string($connect, $_POST['block_id']);
		$term = mysqli_real_escape_string($connect, $_POST['term']);
		$semester = mysqli_real_escape_string($connect, $_POST['sem']);

-

		$_SESSION['block_id'] = $block_id;
		$_SESSION['term'] = $term;
		$_SESSION['sem'] = $semester;

?>   
      <center> <h2 class="form-signin-heading">
        <?php  $syear1 = "SELECT * FROM blocks WHERE block_id = '$block_id'";
         $query1 = mysqli_query($connect, $syear1);
         while($row1 = mysqli_fetch_array($query1))
    {

        echo $row1['agency']; } ?> - <?php echo $block_id; ?><br>

        <?php   echo "1st"; ?> Trimester, SY 2017-2018

         <?php  $syear = "SELECT * FROM blocks WHERE block_id = '$block_id'";
         $query = mysqli_query($connect, $syear);
         while($row = mysqli_fetch_array($query))
    {

        ?> <?php echo $row['syear']; ?>, <?php echo $term; ?> Term <br> <?php } echo '</h2><hr />' ?>

</center> 

     <?php echo'       
       
 
          <div class="content-loader">
        

        <table cellspacing="0" width="100%" id="" class="table table-striped table-hover table-responsive">


        <thead>
        <tr>
        <th>Course Code</th>
        <th>Course Title</th>
        <th>Schedule</th>
        <th>Day&Time </th>
        <th>Room</th>
        <th>Faculty</th>
        <th>Max<br>Slots</th>
        <th>Taken<br>Slots</th>
        <th>EDIT</th>
        <th>DELETE</th>
        
        
        </tr>
        </thead>
        <tbody>'
        ?>
        <?php
        require 'dbconfig.php';
         $stmt2 = $db_con->prepare("SELECT * FROM curriculum WHERE curriculum_id = '$block_id'");
        $stmt2->execute();while($row1=$stmt2->fetch(PDO::FETCH_ASSOC))
        {
        $subject_code = $row1['subject_code'];}

         $stmt3 = $db_con->prepare("SELECT * FROM subject WHERE subj_code = '$subject_code'");
        $stmt3->execute();while($row2=$stmt3->fetch(PDO::FETCH_ASSOC))
        {
        $subj_title = $row2['subj_title'];}

       $stmt = $db_con->prepare("SELECT * FROM classes WHERE block_id = '$block_id' AND trimester = '$semester' AND term = '$term'");
        $stmt->execute();

		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			
			$fac_id = " "; 
			$fac_name= " "; ?>
			<tr>
			<td><?php echo $subject_code; ?></td>
			<td><?php echo $subj_title; ?></td>
			<td><?php echo $row['dates']; ?></td>
<td>  <?php echo $row['day'] ?> / <?php echo date('h:i a', strtotime($row['timestart']));?>-<?php echo date('h:i a', strtotime($row['timeend']))  ?></td>
			<td><?php echo $row['room']; ?></td>

			<?php $fac_id = $row['faculty_id']; ?>
			                  		<?php
     session_start();
        $connect = mysqli_connect('localhost:3309', 'root', '123456', 'offcampus');
        $subj = "SELECT * FROM faculty WHERE  faculty_id = $fac_id";
         $query = mysqli_query($connect, $subj);


                     while($row1 = mysqli_fetch_array($query))
        { 

        	$fac_name =  $row1['faculty_name']; 
        }

?>   <td> <?php echo $fac_name;  ?> </td>

			<td><?php echo $row['max_slots']; ?></td>
			<td><?php echo $row['taken_slots']; ?></td>			
			
	
				<td align="center">
			<a id="<?php echo $row['subj_code']; ?>" class="edit-link" href="#" title="Edit">
			<img src="edit.png" width="20px" />
            </a></td>

           
      

			<td align="center"><a id="<?php echo $row['subj_code']; ?>" class="delete-link" href="#" title="Delete">
			<img src="delete.png" width="20px" /> 
            </a></td>


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

    

<div class="col-xs-12">

<p align="center">
     <a href="/gpcrs.icto.plm/blockschedule.php" class="btn btn-primary"> <span class="glyphicon glyphicon-print"></span> Print Block Schedule </a>
 
</p>

<script type="text/javascript">
function gotoPage(select){
    window.location = select.value;
}
</script>

    
            </div>

    <script src="../js/jquery-3.1.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap4.min.js"></script>
    <script src="../js/select2.min.js"></script>
    <script src="../js/sweetalert2.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#applicantsTable').DataTable({
                "language": {
                  "emptyTable": "No applicants available."
                },
                "bDeferRender": true,
                initComplete: function () {
                    this.api().columns([4,5,6]).every( function () {
                        var column = this;
                        var select = $('<select><option value="">Show All</option></select>')
                            .appendTo( $(column.footer()).empty() )
                            .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
 
                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );
 
                        column.data().unique().sort().each( function ( d, j ) {
                            if(column.search() === '^'+d+'$'){
                                select.append( '<option value="'+d+'" selected="selected">'+d+'</option>' )
                            } else {
                                select.append( '<option value="'+d+'">'+d+'</option>' )
                            }
                        } );
                    } );
                }   
            });

            $("select").select2();
        });

        $(".clickrow").click(function(){
            var applicantid = $(this).attr('id');

            $.ajax({
                type: "POST",
                async: true,
                cache: true,
                url: "facultyAjax.php",
                data: "applicantid="+applicantid+"&action=applicants"+"&process=session",
                success:
                    function(info, status, xhr){
                        if(info != "error"){
                            window.location = ('evalApplicantsSub.php');
                        }
                    }
            });
        });

        var firstFilterOptions = new Promise(function (resolve) {
             resolve({
                '1': 'Print all',
                '2': 'Print accepted applicants'
            })
        });

        var secondFilterOptions = new Promise(function (resolve) {
             resolve({
                '1': 'By Alpha (Last name)',
                '2': 'By Program'
            })
        });

        $("#printButton").click(function(){
            swal({
                type: 'question',
                html: 'Print the list of applicants for this year',
                input: 'radio',

                confirmButtonText: 'Continue',

                inputOptions: firstFilterOptions,
                inputValidator: function (firstResult) {
                    return new Promise(function (resolve, reject) {
                        if (firstResult) {
                            resolve()
                        } else {
                            reject('You need to select something!')
                        }
                    })
                }
            }).then(function(firstResult){
                swal({
                    type: 'question',
                    html: 'Filter the printing',
                    input: 'radio',

                    confirmButtonText: 'Continue Printing',
                    // showCancelButton: true,
                    // cancelButtonText: 'Back',

                    inputOptions: secondFilterOptions,
                    inputValidator: function (secondResult) {
                        return new Promise(function (resolve, reject) {
                            if (secondResult) {
                                resolve()
                            } else {
                                reject('You need to select something!')
                            }
                        })
                    }
                }).then(function(secondResult){
                    var result = firstResult+','+secondResult;
                    $.ajax({
                        type: "POST",
                        async: true,
                        cache: true,
                        url: "facultyAjax.php",
                        data: "result="+result+"&action=printSession",
                        success:
                            function( data, status, xhr ) { 
                                window.open('../printables/listApplicants.php','_blank');
                            }
                    });
                })
                // $.ajax({
                //     type: "POST",
                //     async: true,
                //     cache: true,
                //     url: "facultyAjax.php",
                //     data: "result="+result+"&action=printSession",
                //     success:
                //         function( data, status, xhr ) { 
                //             window.open('../printables/classListpCl.php','_blank');
                //         }
                // });
            })
        });
    </script>


<script type="text/javascript" src="classJS.js"></script>

    </body>
</html>