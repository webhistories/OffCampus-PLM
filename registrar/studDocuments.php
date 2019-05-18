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

    <body style="80px
padding-top: 80px">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-hover table-align-center" id="studentsTable">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Graduate School</th>
                            <th>Program</th>
                            <th>Documents</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Graduate School</th>
                            <th>Program</th>
                            <th>Documents</th>
                        </tr>
                    </tfoot>
                    <?php
                        $sql = 'SELECT a.student_id, a.name, c.gradschool_title, b.program_name,(SELECT COUNT(*) FROM document_passed WHERE status = 1 AND student_id = a.student_id';
                        $studentsSql = mysqli_query($connect, $sql) as 'passed',
                                COUNT(d.applicant_id) as 'required'
                            FROM students a
                            JOIN programs b ON b.program_id = a.program_id
                            JOIN graduate_schools c ON c.gradschool_id = b.gradschool_id
                            JOIN document_passed d ON d.student_id = a.student_id
                            GROUP BY a.student_id
                            ORDER BY c.gradschool_title;
                        $i=1;
                        while($students = mysqli_fetch_assoc($studentsSql)){
                            echo '
                                    '<tr id='.$students['student_id'].'class="clickRow" style="cursor: pointer">'
                                        '<td>'.$i.'</td>'
                                        '<td>'.$students['student_id'].'</td>'
                                        '<td>'.ucwords(strtolower($students['name'])).'</td>'
                                        '<td>'.$students['gradschool_title'].'</td>'
                                        '<td>'.$students['program_name'].'</td>'
                                        '<td>'.$students['passed'].'/'.$students['required'].'</td>'
                                    '</tr>'
                                 ';
                            $i++;   
                        }
                    ?>
                </table>
            </div>
        </div>

    <script src="../js/jquery-3.1.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap4.min.js"></script>
    <script src="../js/select2.min.js"></script>
    <script src="../js/sweetalert.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#studentsTable').DataTable({
                "language": {
                  "emptyTable": "No students available."
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
            var studentid = this.id;

            $.ajax({
                type: "POST",
                async: true,
                cache: true,
                url: "registrarAjax.php",
                data: "studentid="+studentid+"&action=student"+"&process=session",
                success:
                    function(info, status, xhr){
                        if(info != "error"){
                            window.location = ('studDocumentsSub.php');
                        }
                    }
            });
        });
    </script>
    </body>
</html>
