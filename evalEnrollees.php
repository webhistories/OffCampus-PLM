<!DOCTYPE html>
<?php 
    include 'facultyDashboard.php';
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRS | Enrollees</title>
    </head>

    <body style="padding-top: 80px">
        <div class="col-md-12">
			<div class="table-responsive">
				<table class="table table-hover table-align-center" id="enrolleesTable">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Program</th>
                            <th>Units Enlisted</th>
                            <th>Subjects Enlisted</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Program</th>
                            <th>Units Enlisted</th>
                            <th>Subjects Enlisted</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                            $enrolleesQuery = "SELECT cl.student_id, s.name, p.program_name, SUM(c.unit), count(cl.class_id),
                                            CASE WHEN cl.status = 0 THEN 'red' 
                                                 WHEN cl.status = 1 THEN 'green' END,
                                            CASE WHEN cl.status = 0 THEN 'remove' 
                                                 WHEN cl.status = 1 THEN 'ok' END
                                            FROM class_list cl 
                                            JOIN students s ON cl.student_id = s.student_id
                                            JOIN programs p ON s.program_id = p.program_id
                                            JOIN classes c ON c.class_id = cl.class_id
                                            WHERE p.gradschool_id = '$gradschoolid' AND (cl.status = 0 OR cl.status = 1)
                                            GROUP BY cl.student_id";
                            $enrolleesSql = mysqli_query($connect, $enrolleesQuery);
                            $enrolleesNum = mysqli_num_rows($enrolleesSql);

                            for($i=0;$i<$enrolleesNum;$i++){
                                $enrollees = mysqli_fetch_row($enrolleesSql);

                                echo '
                                        <tr style="cursor:pointer" class="enrolleeRow" id="'.$enrollees[0].'">
                                            <td>'.($i+1).'</td>
                                            <td>'.$enrollees[0].'</td>
                                            <td>'.$enrollees[1].'</td>
                                            <td>'.$enrollees[2].'</td>
                                            <td>'.$enrollees[3].'</td>
                                            <td>'.$enrollees[4].'</td>
                                        </tr>
                                     ';
                            }
                        ?>
                    </tbody>
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
            $('#enrolleesTable').DataTable({
                "language": {
                  "emptyTable": "No enrollees available."
                },
                "bDeferRender": true
            });

            $("select").select2();
        });

        $(".enrolleeRow").click(function(){
            var studentid = $(this).attr("id");

            $.ajax({
                type: "POST",
                async: true,
                cache: true,
                url: "facultyAjax.php",
                data: "studentid="+studentid+"&action=students"+"&process=session",
                success:
                    function(info, status, xhr){
                        if(info != "error"){
                            window.location = ('evalEnrolleesSub.php');
                        }
                    }
            });
        });

    </script>

    </body>
</html>
