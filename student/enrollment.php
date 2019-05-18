<!DOCTYPE html>
<script src="../js/jquery-3.1.1.min.js"></script>
<script src="../js/sweetalert2.min.js"></script>
<?php 
    include 'studentDashboard.php';
    $flag = 0;
    $isPrintableSql = mysqli_query($connect, "SELECT count(class_id) FROM class_list WHERE student_id = '$studentid' AND status = 1");

    $isAllowedSql = mysqli_query($connect, "SELECT * FROM assessment_student WHERE student_id = '$studentid' AND balance_amount > 0 ORDER BY paid_date DESC");
    $isAllowed = mysqli_fetch_row($isAllowedSql);
    $disStatus = false;
    
    if(mysqli_num_rows($isAllowedSql) > 0){
        $text = "You have existing balance of <b>P ".number_format($isAllowed[20],2)."</b><br>You cannot proceed with enrollment.";
        $disStatus = true;
        echo '
                <script type="text/javascript">
                    $("#divOne").addClass("disabledbutton");
                    swal({
                        title: "Invalid!",
                        type: "error",
                        html:  "'.$text.'"
                    })
                </script>
             ';
    }
    
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>CRS | Enrollment</title>
    </head>

    <body style="padding-top: 80px">
        <div class="col-md-12" id="divOne">
            <div class="table-responsive" style="max-height: 80vh">
                <table class="table table-align-center" id="subjectsTable">
                    <thead>
                        <tr>
                            <td>Course Group</td>
                            <td>Subject Code</td>
                            <td>Subject Title</td>
                            <td>Units</td>
                            <td>Prerequsite/s</td>
                            <td>Day</td>
                            <td>Time</td>
                            <td>Room</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>                     
                        <?php                              

                            $classListQuery = "SELECT class_id FROM class_list WHERE student_id = '$studentid' AND status IN (0,1,2)";
                            $classListSql = mysqli_query($connect, $classListQuery);
                            $classListNumber = mysqli_num_rows($classListSql);

                            $classListArr = array();
                            for($i = 0; $i < $classListNumber; $i++) {
                                $classListArr[$i] = mysqli_fetch_row($classListSql)[0];
                            }

                            //mga tapos na subject
                            $finishedSubjectsSql = mysqli_query($connect, "SELECT s.subject_id, cu.units, cu.group_id
                                    FROM grades g
                                    JOIN classes c ON g.class_id = c.class_id 
                                    JOIN subjects s ON s.subject_id = c.subject_id
                                    JOIN curriculum cu ON cu.subject_id = s.subject_id
                                    WHERE g.student_id = '$studentid' AND cu.curriculum_id = '$studentInfo[13]' AND g.grade_value != 5");
                            $finishedSubjectsNumber = mysqli_num_rows($finishedSubjectsSql);
                            $finishedArr = array();
                            $finishedUnits = 0;

                            for($i=0;$i<$finishedSubjectsNumber;$i++){

                                $finishedSubjects = mysqli_fetch_row($finishedSubjectsSql) ;
                                $finishedArr[$i] = $finishedSubjects[0];
                                $finishedGroupArr[$i] = $finishedSubjects[2];
                                $finishedUnits += $finishedSubjects[1];
                            }

                            if ($finishedSubjectsNumber == 0)
                                $finishedGroupArr = array();
                            else
                                $finishedGroupArr = array_count_values($finishedGroupArr);

                            //mga subject sa curriculum
                            $curriculumSubjectsSql = mysqli_query($connect, "SELECT subject_id, units, group_id, required_subjects FROM curriculum WHERE curriculum_id = '$studentInfo[13]'");

                            $tempGroup = "";
                            $tempRequired = 0;
                            $tempCounter = 0;
                            $totalUnits = 0; 

                            while ($curriculumSubjects = mysqli_fetch_assoc($curriculumSubjectsSql)) {

                                    //compute total
                                    if($tempGroup != $curriculumSubjects['group_id']) {
                                        $tempGroup = $curriculumSubjects['group_id'];
                                        $totalUnits += $curriculumSubjects['units'];
                                        $tempRequired = $curriculumSubjects['required_subjects'];
                                        $tempCounter = $tempRequired - 1;
                                    }
                                    else {
                                        if($tempRequired != 0) {
                                            if($tempCounter != 0) {
                                                $totalUnits += $curriculumSubjects['units'];
                                                $tempCounter -= 1;
                                            }
                                        }
                                        else {
                                            $totalUnits += $curriculumSubjects['units'];
                                        }
                                    }
                            }

                            //kapag di pa tapos yung ibang subject
                            if($totalUnits - $finishedUnits != 6) {
                                $thesis = 'NOT LIKE "%thesis%"';

                            }
                            else {
                                $thesis = 'LIKE "%thesis"';
                            }

                            $courseSubjectsSql = mysqli_query($connect, "SELECT c.class_id, 
                                                s.subject_title,  
                                                GROUP_CONCAT(' ',c.schedule ), 
                                                CONCAT(s.subject_name, ' - ',c.section) as 'subject_code', 
                                                CASE WHEN cu.prerequisites IS NULL THEN '---' ELSE 
                                                    (SELECT sub.subject_name FROM subjects sub WHERE sub.subject_id = cu.prerequisites) END as 'prerequisites', 
                                                s.subject_id,
                                                GROUP_CONCAT(c.day) as 'day', 
                                                GROUP_CONCAT(CONCAT( time_format(c.timestart, '%h:%s %p'),' - ',time_format(c.timeend, '%h:%s %p'))) as 'time', 
                                                GROUP_CONCAT(c.room) as 'room', cg.group_title, cu.group_id, cu.required_subjects, c.unit
                                                    FROM subjects s 
                                                    JOIN curriculum cu ON s.subject_id = cu.subject_id
                                                    JOIN classes c ON c.subject_id = s.subject_id
                                                    LEFT JOIN faculty f ON f.faculty_id = c.faculty_id
                                                    JOIN course_group cg ON cu.group_id = cg.group_id
                                                    WHERE cu.curriculum_id = '$studentInfo[13]' AND 
                                                        SUBSTR(c.class_id,1,5) = '$aysemenrollment' AND s.subject_title $thesis AND c.status = 1 AND c.rem_slots != 0
                                                    GROUP BY c.class_id
                                                    ORDER BY c.class ASC");
                            
                                $courseSubjectsNumber = mysqli_num_rows($courseSubjectsSql);

                                for($i = 0; $i<$courseSubjectsNumber; $i++) {

                                    $courseSubjects = mysqli_fetch_assoc($courseSubjectsSql);

                                    if(in_array($courseSubjects['subject_id'], $finishedArr)) {
                                        continue;
                                    }

                                    if($finishedSubjectsNumber != 0 && array_key_exists($courseSubjects['group_id'], $finishedGroupArr)
                                        AND $finishedGroupArr[$courseSubjects['group_id']] >= $courseSubjects['required_subjects'] 
                                        AND $courseSubjects['required_subjects'] != 0) {
                                        continue;
                                    }


                                    $courseSubjects['day'] = str_replace(',', '<br>', $courseSubjects['day']);
                                    $courseSubjects['time'] = str_replace(',', '<br>', $courseSubjects['time']);
                                    $courseSubjects['room'] = str_replace(',', '<br>', $courseSubjects['room']);

                                    echo '                                                          <tr>
                                            <td><b>'.$courseSubjects['group_title'].'</b></td>
                                            <td>'.$courseSubjects['subject_code'].'</td>
                                            <td>'.$courseSubjects['subject_title'].'</td>
                                            <td>'.$courseSubjects['unit'].'</td>
                                            <td>'.$courseSubjects['prerequisites'].'</td>
                                            <td>'.$courseSubjects['day'].'</td>
                                            <td>'.$courseSubjects['time'].'</td>
                                            <td>'.$courseSubjects['room'].'</td>
                                     ';              

                                    $classid = $courseSubjects['class_id'];
                                    $statusSql = mysqli_query($connect, "SELECT status FROM class_list WHERE student_id = '$studentid' AND class_id = '$classid'");
                                    $status = mysqli_fetch_row($statusSql);

                                    if(in_array($classid, $classListArr)) {
                                        $flag++;
                                        echo "</span></td>
                                                    <td id='remove'>
                                                        <button type='button' 
                                                                class='btn btn-primary classesButton' 
                                                                id='".$classid."'";
                                        //if($status[0] == 2 || $disStatus == true) echo "disabled";
                                        
                                        echo ">
                                                            <i class='glyphicon glyphicon-minus'></i>  &nbsp Remove
                                                        </button>
                                                    </td>
                                                </tr>
                                             ";
                                    }
                                    else {
                                        echo "</span></td>
                                                    <td id='enlist'>
                                                        <button type='button' class='btn btn-success classesButton' id='".$classid."'>
                                                            <i class='glyphicon glyphicon-plus'></i> &nbsp Enlist
                                                        </button>
                                                    </td>
                                                </tr>
                                             ";   
                                    }                                          
                                }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Course Group</td>
                            <td>Subject Code</td>
                            <td>Subject Title</td>
                            <td>Units</td>
                            <td>Prerequsite/s</td>
                            <td>Day</td>
                            <td>Time</td>
                            <td>Room</td>
                            <td>Action</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-xs-12" id="<?php echo $studentid; ?>">
                <center>
                    <button class="btn btn-primary" id="printEAF">
                        <i class="glyphicon glyphicon-print"></i> Print EAF
                    </button>
                </center>
            </div>
        </div>

    <script src="../js/jquery-3.1.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap4.min.js"></script>
    <script src="../js/select2.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            var disStatus = '<?php echo $disStatus; ?>';

            if(disStatus == 1){
                $(".classesButton").attr('disabled', true);
            }
            
            var table = $("#subjectsTable").DataTable({
                "columnDefs": [
                    { "visible": false, "targets": 0 }
                ],
                "paging": false,
                "order": [[ 0, 'asc' ]],
                "drawCallback": function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;
         
                    api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                                '<tr class="group success"><td colspan="8">'+group+'</td></tr>'
                            );
         
                            last = group;
                        }
                    } );
                }
            });
        })

        function displaySwal(title, type, html){
            swal({
                title: title,
                html: html,
                type: type
            });
        };

        $(".classesButton").click(function(){
            var buttonId = $(this).attr("id"),
                classId = $(this).attr('id');

            if($(this).hasClass("btn-success")){
                $.ajax({
                    type: "POST",
                    url: "studentAjax.php",
                    data: "classid="+classId+"&action=Enlist",
                    success: function(msg){
                        // alert(msg);
                        if(msg){
                            displaySwal('Error!', 'error', '<b>'+msg+'</b>');
                        }
                        else{
                            $("#"+buttonId).html("<i class='glyphicon glyphicon-minus'></i>  &nbsp Remove");
                            $("#"+buttonId).removeClass('btn-success');
                            $("#"+buttonId).addClass('btn-primary');
                            window.setTimeout(function(){location.reload()},10);
                        }
                    }
                });
            }
            else{
                $.ajax({
                    type: "POST",
                    url: "studentAjax.php",
                    data: "classid="+classId+"&action=Remove",
                    success: function(msg){

                        if(msg){
                            $("#error").show();
                            $("#errorMsg").html(msg);
                        }
                        
                        else{
                            $("#"+buttonId).html("<i class='glyphicon glyphicon-plus'></i> &nbsp Enlist");
                            $("#"+buttonId).addClass('btn-success');
                            $("#"+buttonId).removeClass('btn-primary');
                            window.setTimeout(function(){location.reload()},10);
                        }
                    }

                });
            }    
        });

        $("#printEAF").click(function(){
            var flag = '<?php echo $flag; ?>';
            if(flag == 0){
                swal({
                    title: "Invalid",
                    type: "error",
                    html: "Enlist at least one (1) subject to enable printing."
                });
            }
            else{
                swal({
                    title: 'Certification',
                    input: 'checkbox',
                    inputValue: 0,
                    inputPlaceholder:
                        '  I hereby agree to abide by and conform with the pertinent academic policies, rules and regulations of the University, including those stipulated in the operative PLM Student Manual.',
                    confirmButtonText:
                        'Continue <i class="fa fa-arrow-right></i>',
                    inputValidator: function (result) {
                        return new Promise(function (resolve, reject) {
                            if (result) {
                                resolve()
                            } else {
                                reject('You need to agree to proceed with printing')
                            }
                        })
                    }
                }).then(function (result) {
                    window.open('../printables/printEAF.php','_blank');
                })
            }
        })
    </script>

    </body>
</html>
