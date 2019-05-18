<!DOCTYPE html>
<?php 
    include 'facultyDashboard.php';
    $studentid = $_SESSION['studentid'];

    $studentInfoSql = mysqli_query($connect, "SELECT * FROM students WHERE student_id = '$studentid'");
    $studentInfo = mysqli_fetch_row($studentInfoSql);
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRS | Enrollees</title>
        <style>
            .panel-heading h5{
                color: white;
            }
        </style>
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

        <div class="col-md-12">
            <?php 
                $enrolleesInfoQuery = "SELECT s.name, p.program_name, s.graduating, p.program_title, p.curriculum_id
                                    FROM students s 
                                    JOIN programs p ON s.program_id = p.program_id
                                    WHERE s.student_id = '$studentid'";
                $enrolleesInfoSql = mysqli_query($connect, $enrolleesInfoQuery);
                $enrolleesInfo = mysqli_fetch_row($enrolleesInfoSql);
            ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-6">
                            <h5><b>Student Name: </b><?php echo $enrolleesInfo[0]; ?> </h5>
                            <h5><b>Student ID: </b><?php echo $studentid; ?></h5>
                        </div>

                        <div class="col-xs-6">
                            <h5><b>Program: </b><?php echo $enrolleesInfo[1]; ?> </h5>
                            <h5><b>Graduating: </b><?php echo $enrolleesInfo[2]; ?> </h5>
                        </div>

                        <div class="col-xs-12">
                            <h5 data-toggle="modal" data-target="#modal" style="cursor:pointer">
                                View Curriculum of this Student
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="panel-body" style="max-height: 70vh; overflow-y: auto">
                    <div class="col-xs-3">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table" style="border: 1px solid gray">
                                    <tr>
                                        <td class="warning" style="width:25%"></td>
                                        <td style="width:25%">Assessed</td>
                                        <td class="success" style="width:25%"></td>
                                        <td style="width:25%">Paid</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div class="col-xs-12">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-align-center" id="classTable">
                                    <thead class="text-danger">
                                        <th>Subject Code</th>
                                        <th>Subject</th>
                                        <th>Professor</th>
                                        <th>Day</th>
                                        <th>Time</th>
                                        <th>Room</th>
                                        <th>Unit</th>
                                        <th>Max. Slots</th>
                                        <th>No. of Students</th>
                                    </thead>
                                    <?php
                                        $subjectsQuery = "SELECT DISTINCT c.class_id, s.subject_name, s.subject_title, f.lastname, GROUP_CONCAT(c.day), 
                                            GROUP_CONCAT(CONCAT( time_format(c.timestart, '%h:%s %p'),' - ',time_format(c.timeend, '%h:%s %p'))), 
                                            GROUP_CONCAT(c.room), c.unit, c.max_slots, c.taken_slots, 
                                                CASE WHEN cl.status = 1 THEN 'warning' 
                                                     WHEN cl.status = 2 THEN 'success'
                                                     ELSE '' END
                                                            FROM class_list cl 
                                                            JOIN classes c ON cl.class_id = c.class_id
                                                            JOIN subjects s ON s.subject_id = c.subject_id
                                                            JOIN faculty f ON c.faculty_id = f.faculty_id
                                                            WHERE cl.student_id = '$studentid' AND cl.status != 4
                                                            GROUP BY c.class_id";
                                        $subjectsSql = mysqli_query($connect, $subjectsQuery);
                                        $subjectsNumber = mysqli_num_rows($subjectsSql);

                                        $approveQuery = "SELECT CASE WHEN status = '1' THEN 'Yes' ELSE 'No' END FROM class_list WHERE student_id = '$studentid'";
                                        $approveSql = mysqli_query($connect, $approveQuery);
                                        $approve = mysqli_fetch_row($approveSql);

                                        for($i=0;$i<$subjectsNumber;$i++){
                                            $subjects = mysqli_fetch_row($subjectsSql);

                                            $subjects[4] = str_replace(",", "<br>", $subjects[4]);
                                            $subjects[5] = str_replace(",", "<br>", $subjects[5]);
                                            $subjects[6] = str_replace(",", "<br>", $subjects[6]);

                                            if(empty($subjects[9]))
                                                $subjects[9] = 0;

                                            echo '
                                                    <tr class="'.$subjects[10].'">
                                                        <td>'.$subjects[1].'</td>
                                                        <td>'.$subjects[2].'</td>
                                                        <td>'.$subjects[3].'</td>
                                                        <td>'.$subjects[4].'</td>
                                                        <td>'.$subjects[5].'</td>
                                                        <td>'.$subjects[6].'</td>
                                                        <td>'.$subjects[7].'</td>
                                                        <td>'.$subjects[8].'</td>
                                                        <td>'.$subjects[9].'</td>
                                                    </tr>
                                                 ';
                                        }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-footer" id="<?php echo $studentid; ?>">
                    <center>
                        <button class="btn btn-primary" id="printEAF">
                            <i class="glyphicon glyphicon-print"></i> Print EAF
                        </button>
                        <button class="btn btn-primary" id="printSER">
                            <i class="glyphicon glyphicon-print"></i> Print SER
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

            $('#classTable').DataTable({
                "bDeferRender": true
            });
        });

        $("#backButton").click(function(){
            window.location = ('evalEnrollees.php');
        });

        function firstSwal(){
            swal({
                title: 'Print Assessment Form',
                type: "question",
                html: "Printing this form would <b>ACCEPT</b> this applicant. <br><br>"+
                      "Enter additional fee (if any). <br>Click 'Next' if no additional fee.<br>",
                showCancelButton: true,
                confirmButtonText: 'Next <i class="glyphicon glyphicon-chevron-right"></i>',
                showLoaderOnConfirm: true,
                reverseButtons: true,
                input: "number",
                inputPlaceholder: "0",
                inputValue: "0",
                inputAttributes: {
                    'min': 0
                },
                inputClass: "input-center"
            }).then(function (result) {
                if(result == "" || result < 0){
                    swal({
                        title: "Invalid",
                        type: "error",
                        html: "Enter valid value."

                    }).then(function(){
                        firstSwal();
                    })
                }
                else{
                    var fee = result;
                    secondSwal(fee);
                }
            })
        };

        function secondSwal(addFee){
            var fee = addFee;
            var inputOptions = new Promise(function (resolve) {
                resolve({
                    '1': 'Full Payment',
                    '2': 'Partial Payment'
                })
            })

            swal({
                title: 'Print Assessment Form',
                type: "question",
                html: "Printing this form would <b>ACCEPT</b> this applicant. <br><br>"+
                      "Choose the payment type (<i>Shown in PRF</i>).",
                showCancelButton: true,
                reverseButtons: true,
                cancelButtonText: '<i class="glyphicon glyphicon-chevron-left"></i> Previous ',
                allowOutsideClick: true,
                input: 'radio',
                inputOptions: inputOptions,
                confirmButtonText: '<i class="glyphicon glyphicon-print"></i> Print',
                showLoaderOnConfirm: true,
                preConfirm: function (result) {
                    return new Promise(function (resolve, reject) {
                        setTimeout(function() {
                            if (result) {
                                resolve()
                            } else {
                                reject('Choose the type of payment.')
                            }
                        }, 1000)
                    })
                }
            }).then(function (result) {
                //alert(fee+' '+result);
                $.ajax({
                    type: "POST",
                    async: true,
                    cache: true,
                    url: "facultyAjax.php",
                    data: "typeOfPayment="+result+"&addFee="+fee+"&action=acceptEnrollee",
                    success:
                        function(data){
                            if(data == 'error'){
                                swal({
                                    title: "Oops!",
                                    type: "danger",
                                    text: "Something went wrong. Please try again later."
                                });
                            }
                            else
                                window.open('../printables/printEAF.php','_blank');
                        }
                });
            }, function (dismiss){
                firstSwal();
            })
        };

        $("#printEAF").click(function(){
            firstSwal();
        });

        $("#printPRF").click(function(){
            var id = $(this).parent().parent().attr("id");

            window.open('../printables/printPRF.php','_blank');
        });

        $("#printSER").click(function(){
            var id = $(this).parent().parent().attr("id");

            window.open('../printables/printSER.php','_blank');
        })

    </script>
</html>
