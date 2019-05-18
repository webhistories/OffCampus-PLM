<?php
	include '../config.php';
    $applicantid = $_SESSION['applicantid'];

    $applicantInfoSql = mysqli_query($connect, "SELECT * FROM applicant a JOIN applicant_personal b ON b.applicant_id = a.applicant_id JOIN applicant_family c ON c.applicant_id = a.applicant_id JOIN applicant_academic d ON d.applicant_id = a.applicant_id JOIN programs e ON e.program_id = a.program WHERE a.applicant_id = $applicantid ");

    $applicantInfo = mysqli_fetch_assoc($applicantInfoSql);
?>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="../css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <link href="../css/select2.min.css" rel="stylesheet">
        <link href="../css/select2-bootstrap.min.css" rel="stylesheet">
        <link href="../css/sweetalert2.min.css" rel="stylesheet">
        <link href="../css/custom.css" rel="stylesheet">
        <link href="../css/custom2.css" rel="stylesheet">
    </head>

    <body style="padding-top: 80px">
    
        <nav class="navbar navbar-inverse navbar-fixed-top" id="mainNav">
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
                        Welcome, <?php echo $applicantInfo['firstname']; ?>
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    
                    <ul class="nav navbar-nav navbar-right">
		        		<li>
		        			<a href="../logout.php"> Logout</a>
		        		</li>
                     </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        
    <script src="../js/jquery-3.1.1.min.js"></script>

    <script>
        var path = window.location.href.substr(window.location.href.lastIndexOf("/")+1);
        path = path.replace("Sub", "");
        $(".nav").find("a").each(function(){
            if($(this).attr("href") == path)
                $(this).addClass("active");
        });
    </script>
    </body>
</html>
