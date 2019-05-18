<!DOCTYPE html>
<?php
    include 'cashierDashboard.php';
    $receipt = $_SESSION['receipt'];
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRS | Printing of Receipt</title>
    </head>

    <body style="padding-top: 80px">
        <div class="col-md-12">
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

            swal({
                html: "Enter Receipt",
                type: "info",
                input: "number",
                inputClass: "input-center",
                confirmButtonText: "<i class='glyphicon glyphicon-search'></i> Search",
                inputValidator: function (value) {
                    return new Promise(function (resolve, reject) {
                        if (value > 0) {
                            resolve()
                        } else {
                            reject('Enter Receipt')
                        }
                    })
                }
            }).then(function(result){
                $.ajax({
                    type: "POST",
                    async: true,
                    cache: true,
                    url: "cashierAjax.php",
                    data: "receipt="+result+"&action=sessionOR",
                    success:
                        function(msg){
                            if(msg == 'error'){
                                swal({
                                    title: "No Receipt Found!",
                                    type: "error"
                                }).then(function(){
                                    window.setTimeout(function(){location.reload()},0)
                                });
                            }
                            else{
                                window.location = ("cancelOR.php");
                            }
                        }
                }); 
            })
        });
    </script>
</html>
