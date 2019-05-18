<!DOCTYPE html>
<?php
    include 'cashierDashboard.php';
    $studentid = $_SESSION['studentid'];
    
    $currentSemSql = mysqli_query($connect, "SELECT current_sem FROM students WHERE student_id = $studentid");
    $currentSem = mysqli_fetch_row($currentSemSql)[0];

    $_SESSION['currentSem'] = $currentSem;

    $programTypeSql = mysqli_query($connect, "SELECT program_type FROM programs a JOIN students b ON b.program_id = a.program_id WHERE b.student_id = $studentid");
    $programType = mysqli_fetch_row($programTypeSql)[0];

    $assessmentSql = mysqli_query($connect, "SELECT * FROM assessment_student WHERE student_id = $studentid AND aysem = $currentSem ORDER BY paid_date DESC");
    $assesment = mysqli_fetch_assoc($assessmentSql);

    $addClass = '';

    if(mysqli_num_rows($assessmentSql) == 0){
        $addClass = 'hidden';
    }

    if($programType == 'M'){
        $getAmount = 'masteral_amount';
    }
    else if($programType == 'D'){
        $getAmount = 'doctoral_amount';
    }
    else if($programType == 'L'){
        $getAmount = 'law_amount';
    }

    $regisTypeSql = mysqli_query($connect, "SELECT registration FROM students WHERE student_id = $studentid");
    $regisType = mysqli_fetch_row($regisTypeSql)[0];

    if($regisType == 'N'){
        $tuitionFeeSql = mysqli_query($connect, "SELECT $getAmount FROM allfees WHERE feetype = 'T'");
        $tuitionFee = mysqli_fetch_row($tuitionFeeSql)[0];

        $miscFeeSql = mysqli_query($connect, "SELECT name, $getAmount FROM allfees WHERE feetype IN ('M', 'M1')");

        $otherFeeSql = mysqli_query($connect, "SELECT name, $getAmount FROM allfees WHERE feetype IN ('O')");
    }

    if($assesment['paid_status'] == 'NP'){
        $amountToBePaid = $assesment['total_amount'];
    }
    else if($assesment['paid_status'] == 'PP'){
        $amountToBePaid = $assesment['balance_amount'];
    }
    else
        $amountToBePaid = 0.00;

    $lastPaidSql = mysqli_query($connect, "SELECT SUM(paid_amount), date_format(paid_date, '%b %d %Y, %I:%i %p') FROM assessment_student WHERE student_id = $studentid AND aysem = $currentSem ORDER BY paid_date DESC");
    $lastPaid = mysqli_fetch_row($lastPaidSql)[0];
    $_SESSION['lastPaid'] = $lastPaid;

    $lastDateSql = mysqli_query($connect, "SELECT date_format(paid_date, '%b %d %Y, %I:%i %p') FROM assessment_student WHERE student_id = $studentid AND aysem = $currentSem ORDER BY paid_date DESC");
    $lastDate = mysqli_fetch_row($lastDateSql)[0];
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRS | Printing of Receipt</title>
    </head>

    <body style="padding-top: 80px" id="<?php echo $addClass; ?>">
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
            <div class="panel with-nav-tabs panel-primary">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#paymentTab" data-toggle="tab"><b>Payments</b></a></li>
                        <li class=""><a href="#historyTab" data-toggle="tab"><b>Histrory of Transactions</b></a></li>
                    </ul>
                </div>

                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active <?php echo $addClass; ?>" id="paymentTab">
                            <?php
                                $infoSql = mysqli_query($connect, "SELECT * from students a join programs b on a.program_id = b.program_id join graduate_schools c on c.gradschool_id = b.gradschool_id where student_id = '$studentid'");
                                $infoNum = mysqli_num_rows($infoSql);
                                $info = mysqli_fetch_assoc($infoSql);
                            ?>

                            <div class="row">
                                <div class="col-xs-12">
                                    <h5><b>Student Name: </b> <span id="getName"><?php echo $info['name']; ?></span></h5>
                                </div>  
                                <div class="col-xs-12">
                                    <h5><b>Student ID: </b> <span id="getID"><?php echo $studentid; ?></span></h5>
                                </div>
                                <div class="col-xs-12">
                                    <h5><b>Graduate School: </b> <?php echo $info['gradschool_name']; ?></h5>
                                </div>
                                <div class="col-xs-12">
                                    <h5><b>Progam: </b> <?php echo $info['program_title']; ?></h5>
                                </div>
                                <div class="col-xs-12">
                                    <h5><b>Aysem: </b> <span id="getAysem"><?php echo $currentSem; ?></span></h5>
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-xs-7">
                                    <div class="panel panel-danger">
                                        <div class="panel-heading">
                                            <h5 style="color: white">Summary of Fees</h5>
                                        </div>

                                        <div class="panel-body">
                                            <table class="table">
                                                <tr>
                                                    <td>
                                                        Tuition Fee (Per unit): 
                                                    </td>
                                                    <td>
                                                        <b>P <?php echo number_format($tuitionFee,2); ?></b>
                                                    </td>
                                                    <td>
                                                        Tuition Fee (<?php echo $assesment['units']; ?> units): 
                                                    </td>
                                                    <td>
                                                        <b>P <?php echo number_format($assesment['tuition_amount'],2); ?></b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Miscellaenous Fees: 
                                                    </td>
                                                    <td>
                                                        <b>P <?php echo number_format($assesment['misc_amount'],2); ?></b>
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <?php
                                                    while($miscFee = mysqli_fetch_row($miscFeeSql)){
                                                        echo '

                                                                <tr>
                                                                    <td></td>
                                                                    <td>'.$miscFee[0].'</td>
                                                                    <td>P '.number_format($miscFee[1],2).'</td>
                                                                    <td></td>
                                                                </tr>
                                                             ';
                                                    } 
                                                ?>
                                                <tr>
                                                    <td>
                                                        Other Fees: 
                                                    </td>
                                                    <td>
                                                        <b>P <?php echo number_format($assesment['other_amount'],2); ?></b>
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <?php
                                                    while($otherFee = mysqli_fetch_row($otherFeeSql)){
                                                        echo '

                                                                <tr>
                                                                    <td></td>
                                                                    <td>'.$otherFee[0].'</td>
                                                                    <td>P '.number_format($otherFee[1],2).'</td>
                                                                    <td></td>

                                                             ';
                                                    }
                                                ?>
                                                <tr>
                                                    <td>Total Amount: </td>
                                                    <td>
                                                        <b>P <?php echo number_format($assesment['total_amount'],2); ?></b>
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>Last Paid Amount: </td>
                                                    <td>
                                                        <b>P <?php echo number_format($lastPaid,2);?></b>
                                                    </td>
                                                    <td>
                                                        <b><?php echo $lastDate; ?></b>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>Amount to be Paid: </td>
                                                    <td>
                                                        <b>P <span id="getAmountToBePaid"><?php echo $amountToBePaid; ?></span></b>
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <!-- <tr>
                                                    <td>blank</td>
                                                    <td>
                                                        Title nung misc
                                                    </td>
                                                    <td>
                                                        amount nung misc
                                                    </td>
                                                </tr> -->
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-5">
                                    <div class="panel panel-danger">
                                        <div class="panel-heading">
                                            <h5 style="color: white">Payment Transaction</h5>
                                        </div>

                                        <div class="panel-body" id="transactionDiv">
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <label id="labelpaymentMethod">
                                                        Payment Method
                                                    </label>

                                                    <select class="form-control" id="paymentMethod" style="width:100%">
                                                        <option disabled selected>Choose One</option>
                                                        <option value="Ca">Cash</option>
                                                        <option value="Ba">Bank</option>
                                                        <option value="Ch">Check</option>
                                                    </select>
                                                </div>

                                                <div class="col-xs-6">
                                                    <label>
                                                        Payment Type
                                                    </label>

                                                    <select class="form-control" id="paymentType" style="width:100%">
                                                        <option disabled selected>Choose One</option>
                                                        <option value="FP" <?php if($assesment['paytype'] == 1) echo 'selected'; ?>>Full Payment</option>
                                                        <option value="PP" <?php if($assesment['paytype'] == 2) echo 'selected'; ?>>Partial Payment</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <br>

                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <input type="number" class="form-control" placeholder="Enter OR #" min=0 id="inputOR">
                                                </div>
                                            </div>

                                            <br>

                                            <div class="row">                                                
                                                <div class="col-xs-6">
                                                    <input type="number" class="form-control" placeholder="Cash Tendered" id="cashTendered" min=0 step="0.01">
                                                </div>
                                                
                                                <div class="col-xs-6">
                                                    <input type="number" class="form-control" placeholder="Change (if any)" id="cashChange" readonly>
                                                </div>
                                            </div>

                                            <br clear="bankRow">

                                            <div class="row bankRow">                                                
                                                <div class="col-xs-12">
                                                    <label>
                                                        Bank Name
                                                    </label>

                                                    <select class="form-control" id="bankName" style="width:100%">
                                                        <option value="" selected disabled>Choose One</option>
                                                        <option>Asia United Bank Corporation</option>
                                                        <option>Bank of Commerce</option>
                                                        <option>Bank of the Philippine Islands</option>
                                                        <option>BDO Private Bank, Inc.</option>
                                                        <option>BDO Unibank, Inc.</option>
                                                        <option>China Banking Corporation</option>
                                                        <option>East West Banking Corporation</option>
                                                        <option>Land Bank of the Philippines</option>
                                                        <option>Metropolitan Bank and Trust Company</option>
                                                        <option>Philippine Bank of Communication</option>
                                                        <option>Philippine National Bank</option>
                                                        <option>Philippine Trust Company</option>
                                                        <option>Philippine Veterans Bank</option>
                                                        <option>Rizal Commercial Banking Corporation</option>
                                                        <option>Robinsons Bank Corporation</option>
                                                        <option>Security Bank Corporation</option>
                                                        <option>Union Bank of the Philippines</option>
                                                        <option>United Coconut Planters Bank</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <br clear="bankRow">

                                            <div class="row bankRow">
                                                <div class="col-xs-6">
                                                    <input type="number" class="form-control" placeholder="Check #" id="checkNumber">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel-footer">
                                            <center>
                                                <button class="btn btn-success" id="payButton"> 
                                                    <i class="glyphicon glyphicon-money"></i> Pay
                                                </button>
                                            </center>
                                        </div> 
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="modal1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="panel panel-danger">
                                        <div class="panel-heading">
                                            <h5 style="color: white">Continue Transaction?</h5>
                                        </div>

                                        <div class="panel-body">
                                            <b>Student: </b>
                                                <span id="spanID"></span>
                                                (<span id="spanName"></span>)
                                            <br>

                                            <b>Aysem: </b>
                                                <span id="spanAysem"></span>

                                            <br><br>

                                            <b>Payment Method: </b>
                                                <span id="spanMethod"></span>

                                            <br>

                                            <b>Payment Type: </b>
                                                <span id="spanType"></span>

                                            <br>

                                            <b>Official Receipt #: </b>
                                                <span id="spanOR"></span>

                                            <br><br>

                                            <b>Cash Tendered: </b>
                                                P <span id="spanCash"></span>

                                            <div id="ifBankDiv" class="hidden">
                                                <br>

                                                <b>Bank Name: </b>
                                                    <span id="spanBank"></span>

                                                <br>

                                                <b>Check Number:</b>
                                                    <span id="spanCheck"></span>
                                            </div>

                                            <br>

                                            <b>Change (if any): </b>
                                                P <span id="spanChange"></span>
                                        </div>

                                        <div class="panel-footer">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <button class="btn btn-primary" id="cancelButton" data-dismiss="modal">
                                                        <i class="glyphicon glyphicon-remove"></i> Cancel
                                                    </button>
                                                    <button class="btn btn-success pull-right" id="continueButton">
                                                        Continue <i class="glyphicon glyphicon-chevron-right"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane fade " id="historyTab">
                            <div class="table-responsive">
                                <table class="table table-align-center table-hover" id="historyTable">
                                    <thead>
                                        <tr>
                                            <th>Assessment ID</th>
                                            <th>Aysem</th>
                                            <th>Amount to be Paid</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>OR #</th>
                                            <th>Balance Remaining</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Assessment ID</th>
                                            <th>Aysem</th>
                                            <th>Amount to be Paid</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>OR #</th>
                                            <th>Balance Remaining</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php 
                                            $historySql = mysqli_query($connect, "SELECT * FROM assessment_student WHERE student_id = $studentid AND paid_date IS NOT NULL ORDER BY aysem DESC");

                                            while($history = mysqli_fetch_assoc($historySql)){
                                                echo '
                                                        <tr>
                                                            <td>'.$history['assessment_id'].'</td>
                                                            <td>'.$history['aysem'].'</td>
                                                            <td>P '.number_format($history['total_amount'],2).'</td>
                                                            <td>'.$history['paid_status'].'</td>
                                                            <td>P '.number_format($history['paid_amount'],2).'</td>
                                                            <td>'.$history['paid_date'].'</td>
                                                            <td>'.$history['or_number'].'</td>
                                                            <td>P '.number_format($history['balance_amount'],2).'</td>
                                                        </tr>
                                                     ';
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div> 
                        </div>
                    </div>
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
            $("select").select2();

            var bodyid = $('body').attr('id');

            if(bodyid == 'hidden'){
                swal({
                    title: "No record for current sem",
                    type: "error",
                    html: "Check history of transactions of student <br>for further information"
                })
            }

            $(".bankRow").addClass("hidden");

            $('#historyTable').DataTable({
                "language": {
                  "emptyTable": "No transactions available."
                },
                "bDeferRender": true
            });
        });

        $("#backButton").click(function(){
            window.location = ('studentid.php');
        });

        $("#paymentMethod").change(function(){
            var val = $(this).val();

            if(val == 'Ba'){
                $(".bankRow").removeClass("hidden");
            }
            else{
                $(".bankRow").addClass("hidden");
            }
        });

        function displayError(){
            swal({
                title: "Error",
                type: "error",
                html: "Do not leave necessary fields."
            });
        }

        $("#payButton").click(function(){
            $("#ifBankDiv").addClass("hidden");
            var paymentMethod = $("#paymentMethod").val(), // null
                paymentType = $("#paymentType").val(),
                inputOR = $("#inputOR").val(), // ''
                cashTendered = $("#cashTendered").val(), // ''

                methodText = $("#paymentMethod option:selected").text(),
                typeText = $("#paymentType option:selected").text(),
                amountToBePaid = $("#getAmountToBePaid").html(),
                amountToBePaid = parseFloat(amountToBePaid);

                //alert(amountToBePaid);

                // if(cashTendered < amountToBePaid)
                //     alert(1);
                // else if(cashTendered > amountToBePaid)
                //     alert(2);
                // else if(cashTendered == amountToBePaid)
                //     alert(3);
                // return;

            if(paymentType == 'FP' && cashTendered < amountToBePaid){
                swal({
                    title: "Insufficient Amount.",
                    type: "error",
                    html: "Please enter valid amount. <br>Amount should be greater than <b>P "+amountToBePaid+"</b>"
                })

                return;
            }
            else if(cashTendered > amountToBePaid){
                changeAmount = cashTendered - amountToBePaid;
                changeAmount = parseFloat(changeAmount);
                $("#spanChange").html(changeAmount);
                $("#cashChange").val(changeAmount);
            }

            if(paymentMethod == null || inputOR == '' || cashTendered == '' || paymentType == null){
                displayError();
                return;
            }

            if(paymentMethod == 'Ba'){
                var bankName = $("#bankName").val(),
                    checkNumber = $("#checkNumber").val(),
                    bankText = $("#bankName option:selected").text();

                if(bankName == null || checkNumber == ''){
                    displayError();
                    return;
                }

                $("#ifBankDiv").removeClass("hidden");
                $("#spanBank").html(bankText);
                $("#spanCheck").html(checkNumber);

            }

            $("#spanID").html($("#getID").html());
            $("#spanName").html($("#getName").html());
            $("#spanAysem").html($("#getAysem").html());
            $("#spanType").html(typeText);
            $("#spanMethod").html(methodText);
            $("#spanOR").html(inputOR);
            $("#spanCash").html(cashTendered);


            $("#modal1").modal("show");
        });

        $("#continueButton").click(function(){
            var paymentMethod = $("#paymentMethod").val(),
                paymentType = $("#paymentType").val()
                inputOR = $("#inputOR").val()
                cashTendered = $("#cashTendered").val()
                amountToBePaid = $("#getAmountToBePaid").html();


            $.ajax({
                type: "POST",
                async: true,
                cache: true,
                url: "cashierAjax.php",
                data: "paymentMethod="+paymentMethod+
                      "&paymentType="+paymentType+
                      "&inputOR="+inputOR+
                      "&cashTendered="+cashTendered+
                      "&amountToBePaid="+amountToBePaid+
                      "&action=printOR",
                success:
                    function( data, status, xhr ) { 
                        if(data == 'orError'){
                            swal({
                                title: "Invalid",
                                type: "error",
                                html: "OR already existing."
                            });
                        }
                        else{
                            window.open('../printables/receipt.php','_blank');
                            swal({
                                title: "Success",
                                type: "success"
                            }).then(function(){
                                window.setTimeout(function(){location.reload()},10);
                            })
                        }
                    }
            });
        })

    </script>
</html>
