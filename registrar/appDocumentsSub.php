<!DOCTYPE html>
<?php 
    include 'registrarDashboard.php';
    $applicantid = $_SESSION['applicantid'];

    $applicantInfoSql = mysqli_query($connect, "SELECT * FROM applicant a JOIN applicant_personal b ON b.applicant_id = a.applicant_id JOIN applicant_family c ON c.applicant_id = a.applicant_id JOIN applicant_academic d ON d.applicant_id = a.applicant_id JOIN programs e ON e.program_id = a.program WHERE a.applicant_id = $applicantid ");

    $applicantInfo = mysqli_fetch_assoc($applicantInfoSql);
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRS | Documents</title>
    </head>

    <body style="padding-top: 80px">
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a class="navbar-brand" href="#">
                        Welcome, <?php echo $faculty['firstname']; ?>
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a id="backButton"> Back</a>
                        </li>
                     </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>

        <div class="col-xs-8 col-xs-offset-2">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 style="color: white">
                        <?php echo $applicantInfo['lastname'].', '.$applicantInfo['firstname'].' '.$applicantInfo['middlename'];
                        ?>
                    </h4>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-align-center" id="docuTable">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Particular</th>
                                    <th>Date Submitted</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Action</th>
                                    <th>Particular</th>
                                    <th>Date Submitted</th>
                                </tr>
                            </tfoot>
                            <?php
                                $docuRequiredSql = mysqli_query($connect, "SELECT a.document_id, CASE WHEN b.status = 1 THEN 'checked disabled' ELSE '' END, a.document, 
                                        CASE WHEN b.date_submitted IS NULL THEN '---' ELSE b.date_submitted END
                                        FROM document a 
                                        JOIN document_passed b ON b.document_id = a.document_id
                                        WHERE b.applicant_id = $applicantid");

                                while($docuRequired = mysqli_fetch_row($docuRequiredSql)){
                                    echo '
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="checkbox" aria-label="..." id="'.$docuRequired[0].'" '.$docuRequired[1].' style="cursor:pointer">
                                                </td>
                                                <td>'.$docuRequired[2].'</td>
                                                <td>'.$docuRequired[3].'</td>
                                            </tr>
                                         ';
                                }
                            ?>
                        </table>
                    </div>
                </div>

                <div class="panel-footer">  
                    <center>
                        <button class="btn btn-primary" id="printButton">
                            <i class="glyphicon glyphicon-print"></i> &nbsp Print SPAR
                        </button>
                        <button class="btn btn-primary" id="tagButton">
                            <i class="glyphicon glyphicon-ok"></i> &nbsp Tag as 'submitted'
                        </button> 
                        <button class="btn btn-success" id="saveButton">
                            <i class="glyphicon glyphicon-ok"></i> &nbsp Save Changes
                        </button>
                    </center>
                </div>
            </div>
        </div>
    </body>

    <script src="../js/jquery-3.1.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap4.min.js"></script>
    <script src="../js/select2.min.js"></script>
    <script src="../js/sweetalert2.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $("#mainNav").addClass("hidden");

            $("select").select2();

            $('#docuTable').DataTable({
                "bDeferRender": true,
                "bLengthChange": false,
                "bPaginate": false
            });
        });


        $("#backButton").click(function(){
            window.location = ('appDocuments.php');
        });

        $("#printButton").click(function(){
            window.open('../printables/printSPAR.php','_blank');
        });

        $("#saveButton").click(function(){
            var checkArray = [];

            $("#docuTable .checkbox:checkbox:checked").each(function(){
                checkArray.push(this.id);
            });

            $.ajax({
                type: "POST",
                async: true,
                cache: true,
                url: "registrarAjax.php",
                data: "checkArray="+checkArray+"&action=saveDocu"+"&type=applicant",
                success:
                    function(info, status, xhr){
                        if(info != "error"){
                            swal({
                                title: "Success!",
                                type: "success",
                                html: "Successfully updated documents!"
                            }).then(function(){
                                window.setTimeout(function(){location.reload()},10);
                            })
                        }
                    }
            });
        });

        $("#tagButton").click(function(){
            $.ajax({
                type: "POST",
                async: true,
                cache: true,
                url: "registrarAjax.php",
                data: "&action=tagSubmitted",
                success:
                    function(info, status, xhr){
                        if(info != "error"){
                            swal({
                                title: "Success!",
                                type: "success",
                                html: "Successfully tagged applicant as submitted!"
                            }).then(function(){
                                window.setTimeout(function(){location.reload()},10);
                            })
                        }
                    }
            });
        })
    </script>
</html>
