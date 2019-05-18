<!DOCTYPE html>
<?php 
    include 'registrarDashboard.php';
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>CRS | Documents</title>
    </head>

    <body style="padding-top: 80px">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-hover table-align-center" id="applicantsTable">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Applicant ID</th>
                            <th>Applicant Name</th>
                            <th>Graduate School</th>
                            <th>Program</th>
                            <th>Documents</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>Applicant ID</th>
                            <th>Applicant Name</th>
                            <th>Graduate School</th>
                            <th>Program</th>
                            <th>Documents</th>
                        </tr>
                    </tfoot>
                    <?php
                        $applicantsSql = mysqli_query($connect, "SELECT b.gradschool_title, CONCAT(e.lastname,', ',e.firstname) as 'name', c.program_name, a.applicant_id, 
                            (SELECT COUNT(*) FROM document_passed WHERE status = 1 AND applicant_id = a.applicant_id) as 'passed',
                            COUNT(d.applicant_id) as 'required'
                            FROM applicant a 
                            JOIN graduate_schools b ON b.gradschool_id = a.graduate_school
                            JOIN programs c ON c.program_id = a.program
                            JOIN document_passed d ON d.applicant_id = a.applicant_id
                            JOIN applicant_personal e ON e.applicant_id = a.applicant_id
                            WHERE a.student_id IS NULL
                            GROUP BY a.applicant_id
                            ORDER BY b.gradschool_title");
                        $i=1;
                        while($applicants = mysqli_fetch_assoc($applicantsSql)){
                            echo '
                                    <tr id="'.$applicants['applicant_id'].'" class="clickRow" style="cursor: pointer">
                                        <td>'.$i.'</td>
                                        <td>'.$applicants['applicant_id'].'</td>
                                        <td>'.$applicants['name'].'</td>
                                        <td>'.$applicants['gradschool_title'].'</td>
                                        <td>'.$applicants['program_name'].'</td>
                                        <td>'.$applicants['passed'].'/'.$applicants['required'].'</td>
                                    </tr>
                                 ';
                            $i++;   
                        }
                    ?>
                </table>
            </div>
        </div>

    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap4.min.js"></script>
    <script src="../js/select2.min.js"></script>
    <script src="../js/sweetalert2.min.js"></script>
    <script src="../js/bootstrap-datepicker.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#applicantsTable').DataTable({
                "language": {
                  "emptyTable": "No applicants available."
                },
                "bDeferRender": true,
                initComplete: function () {
                    this.api().columns([3,4,5]).every( function () {
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

        $(".clickRow").click(function(){
            var applicantid = this.id;

            $.ajax({
                type: "POST",
                async: true,
                cache: true,
                url: "registrarAjax.php",
                data: "applicantid="+applicantid+"&action=applicant"+"&process=session",
                success:
                    function(info, status, xhr){
                        if(info != "error"){
                            window.location = ('appDocumentsSub.php');
                        }
                    }
            });
        });
    </script>
    </body>
</html>
