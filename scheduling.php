<!DOCTYPE html>
<?php 
    include 'facultyDashboard.php';
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRS | Viewing of Schedules</title>
    </head>

     <body style="80px
padding-top: 80px">

            <div class="container-fluid">
                          <center>
    <h2>
Viewing of Block Schedules
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

    <form method = "post" action = "scheduling_view.php">   
    <div class="row"> 
         <label> &emsp; Block ID</label>
        <input type="text" name="block_id" placeholder="ABC1" required />

<br><br>
      <label>  &emsp;&emsp; Term </label>
        <select name ="term" style="width:80px;" required > 

        <option value="1st"> 1st </option>
        <option value="2nd"> 2nd </option>
        <option value="3rd"> 3rd </option>
        <option value="4th"> 4th </option>

        </select>
<br><br>
         <label>  &emsp;&nbsp; Trimester</label>
            <select name ="sem" style="width:80px;" required  > 

        <option value="1st"> 1st </option>
        <option value="2nd"> 2nd </option>
        </select>
      


       
<br> <br>
       &emsp; &emsp; &emsp; &emsp; <button class="btn btn-success" id="classesButton" name="btnclassSubmit" >
                                    <i class="glyphicon glyphicon-search"></i> Submit
                                </button>
    </form>   </div> </form></div></div></div>



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

    </body>
</html>
