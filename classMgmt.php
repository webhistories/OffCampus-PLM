<!DOCTYPE html>
<?php 
    include 'facultyDashboard.php';
    $_SESSION['classAllow'] = 0;
    $whereString = "SUBSTR(a.class_id,1,5) LIKE '%%' ";
    if(isset($_SESSION['classaysem'])){
        $classaysem = $_SESSION['classaysem'];
        if($classaysem == ' '){
            $whereString = "SUBSTR(a.class_id,1,5) LIKE '%%' ";
        }
        else{
            $whereString = "SUBSTR(a.class_id,1,5) = '".$classaysem."' ";
        }
    }

?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
         <!--  jQuery -->
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

<!-- Isolated Version of Bootstrap, not needed if your site already uses Bootstrap -->
<link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />

<!-- Bootstrap Date-Picker Plugin -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

        <title>CRS | Class Management</title>

    </head>

    <body style="padding-top: 80px">
        <div class="col-md-12">
            <div class="panel with-nav-tabs panel-primary">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#listClasses" data-toggle="tab"><b>List of Classes</b></a></li>
                        <li class=""><a href="#createClasses" data-toggle="tab"><b>Create New Class</b></a></li>
                    </ul>
                </div>
                <div class="panel-body" >
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="listClasses">
                            <div class="row">
                                <div class="col-xs-4">
                                    <label>
                                        Display Classes For
                                    </label>
                                   <select class="form-control" id="classaysem">
                                        <option value=" ">Display All</option> 
                                      <?php 
                                       $optionsSql = mysqli_query($connect, "SELECT * FROM classes WHERE gradschool_id = $gradschoolid");

                                            $termsSql = mysqli_query($connect, "SELECT terms FROM graduate_schools WHERE gradschool_id = $gradschoolid");
                                            $terms = mysqli_fetch_row($termsSql);

                                            if($terms[0] == 3)
                                                $term = array('', '1st Trimester', '2nd Trimester', '3rd Trimester');
                                            else
                                                $term = array('', '1st Semester', '2nd Semester', 'Summer');

                                            while($options = mysqli_fetch_row($optionsSql)){
                                                for($i=1;$i<4;$i++){
                                                    if($_SESSION['classaysem'] == $options[0].$i)
                                                        $selected = 'selected';
                                                    else
                                                        $selected = '';

                                                    echo '
                                                            <option value="'.$options[0].$i.'" '.$selected.'>
                                                            '.$options[0].' '.$term[$i].'
                                                            </option>
                                                         ';

                                                }
                                            }
                                        ?>
                                    </select> 
                                </div>
                            </div>

                            <br><br>

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-align-center" id="classTable">
                                            <thead class="text-danger">
                                                <tr>
                                                    <th># </th>
                                                    <th>Class ID</th>
                                                    <th>Subject Code</th>
                                                    <th>Subject Name</th>
                                                    <th>Professor</th>
                                                    <th>Day/s</th>
                                                    <th>Time</th>
                                                    <th>Room</th>
                                                    <th>Max Slots</th>
                                                    <th>Taken Slots</th>
                                                </tr>
                                            </thead>
                                            <tfoot class="text-danger">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Class ID</th>
                                                    <th>Subject Code</th>
                                                    <th>Subject Name</th>
                                                    <th>Professor</th>
                                                    <th>Day/s</th>
                                                    <th>Time</th>
                                                    <th>Room</th>
                                                    <th>Max Slots</th>
                                                    <th>Taken Slots</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <?php

                                                    $query = "SELECT * FROM classes";
                                                    $query1 = "SELECT name FROM faculty
                                                                        JOIN classes on faculty.faculty_id = classes.faculty_id";
                                                    $classesSql = mysqli_query($connect, $query);

                                                    $im=1;
                                                    $week = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");
                                                    $ctr=0;

                                                    $results = mysqli_query($connect, $query);
                                                    $results1 = mysqli_query($connect, $query1);


                                                    while($classes = mysqli_fetch_assoc($results) ){

                                                       /* $schedules = explode(", ", $classes['schedule']);
                                                        $timestart = explode(",", $classes['timestart']);
                                                        $timeend = explode(",", $classes['timeend']);
                                                        $day = explode(",", $classes['day']);
                                                        $room = explode(",", $classes['room']);

                                                        $classes["day"] = str_replace(",", "<br>", $classes["day"]);
                                                        $classes["time"] = str_replace(",", "<br>", $classes["time"]);
                                                        $classes["room"] = str_replace(",", "<br>", $classes["room"]);*/
                                                        echo '

                                                                <tr class="classrow open-modal" 
                                                                data-toggle="modal" data-target="#'.$im.'Modal" 
                                                                id="'.$classes['class_id'].'"  style="cursor: pointer">
                                                                    <td>'.$im.' </td>
                                                                    <td>'.$classes['class_id'].'</td>
                                                                    <td>'.$classes['class_code'].'</td>
                                                                    <td>'.$classes['subject_title'].'</td>
                                                                    <td>'.$classes['name'].'</td>
                                                                    <td>'.$classes['lastname'].'</td>
                                                                    <td>'.$classes['day'].'</td>
                                                                    <td>'.$classes['time'].'</td>
                                                                    <td>'.$classes['room'].'</td>
                                                                    <td>'.$classes['max_slots'].'</td>
                                                                    <td>'.$classes['taken_slots'].'</td>
                                                                </tr>
                                                             ';
                                                        
                                                        $modalID = $im."Modal";
                                                ?>

                                                    <div class="modal fade" id="<?php echo $modalID;?>" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="panel panel-primary">

                                                                <div class="modal-header panel-heading">
                                                                    <span> <?php echo $classes['subject_title']; ?> </span>
                                                                    <span class="pull-right" style='cursor:pointer;' data-dismiss="modal">×</span>
                                                                </div>

                                                                <div class="modal-body panel-body">
                                                                    <input class="hidden eClassid" value="<?php echo $classes['class_id']; ?>">
                                                                    <input class="hidden eSubjectid" value="<?php echo $classes['subject_id']; ?>">

                                                                    <div class="alert alert-danger alert-with-icon eDangerEdit" data-notify="container" style="display:none;">
                                                                        <button type="button" aria-hidden="true" class="close" id="eDangerClose">×</button>
                                                                        <i data-notify="icon" class="material-icons">warning</i>
                                                                        <span data-notify="message" class="eDangerMsg"></span>
                                                                    </div>

                                                                    <div class="alert alert-success alert-with-icon hidden eSuccessEdit" data-notify="container">
                                                                        <button type="button" aria-hidden="true" class="close">×</button>
                                                                        <i data-notify="icon" class="material-icons">check</i>
                                                                        <span data-notify="message" class="eSuccessMsg"></span>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-lg-5 col-xs-6">
                                                                            <label>
                                                                                Faculty
                                                                            </label>

                                                                            <select class="eFaculty <?php echo $modalID; ?> form-control" id="selectFaculty" style="width:100%">
                                                                                <option value=" " selected>--Select Professor--</option>
                                                                                <?php 
                                                                                    $facultySql = mysqli_query($connect, "SELECT a.faculty_id, a.name FROM faculty a JOIN faculty_designation b ON a.faculty_id = b.faculty_id 
                                                                                        WHERE b.gradschool_id = $gradschoolid AND b.designation_id = 1002");

                                                                                    while ($faculty = mysqli_fetch_assoc($facultySql)) {

                                                                                        if($classes['faculty_id'] == $faculty['faculty_id'])
                                                                                            echo '<option name="'.$classes['class_id'].'Faculty" value="'.$faculty['faculty_id'].'" selected>'.ucwords(strtolower($faculty['name'])).'</option>';
                                                                                        else
                                                                                            echo '<option name="'.$classes['class_id'].'Faculty" value="'.$faculty['faculty_id'].'">'.ucwords(strtolower($faculty['name'])).'</option>';
                                                                                    }
                                                                                ?>
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-xs-3">
                                                                            <label>
                                                                                Section
                                                                            </label>

                                                                            <select class="eSection <?php echo $modalID; ?> form-control js-states" style="width:100%">
                                                                            <?php
                                                                                $i=1;
                                                                                while ($i < 11) {
                                                                                    if($classes['section'] == $i)
                                                                                        echo '<option selected> '.$i.' </option>';
                                                                                    else
                                                                                        echo '<option> '.$i.' </option>';
                                                                                    $i++;   
                                                                                }
                                                                            ?>
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-xs-2">
                                                                            <label>Units</label>
                                                                            <input type="number" value="<?php echo $classes['unit']; ?>" class="center eUnits form-control" readonly>
                                                                        </div>
                                                                    </div>

                                                                    <br>

                                                                    <div class="row">
                                                                        <div class="col-xs-10">            
                                                                            <label>Class Day</label> 
                                                                            <select class="eDays <?php echo $modalID; ?> form-control js-example-basic-multiple" style="width:100%" multiple="multiple">
                                                                                <!-- <option value='' <?php if(count($day) == 0 || in_array("", $day)) echo "selected"; ?> >Choose day/s</option> -->
                                                                                <option value="Mon" <?php if(in_array("Mon", $day)) echo "selected"; ?>>Monday</option>
                                                                                <option value="Tue" <?php if(in_array("Tue", $day)) echo "selected"; ?>>Tuesday</option>
                                                                                <option value="Wed" <?php if(in_array("Wed", $day)) echo "selected"; ?>>Wednesday</option>
                                                                                <option value="Thu" <?php if(in_array("Thu", $day)) echo "selected"; ?>>Thursday</option>
                                                                                <option value="Fri" <?php if(in_array("Fri", $day)) echo "selected"; ?>>Friday</option>
                                                                                <option value="Sat" <?php if(in_array("Sat", $day)) echo "selected"; ?>>Saturday</option>
                                                                                <option value="Sun" <?php if(in_array("Sun", $day)) echo "selected"; ?>>Sunday</option>
                                                                            </select>
                                                                        </div>

                                                                        <?php
                                                                            $roomLen = count($room);
                                                                            $max = 0;
                                                                            for($i = 0; $i < $roomLen; $i++) {
                                                                                $roomx = $room[$i];
                                                                                $maxSlotsSql = mysqli_query($connect, "SELECT slots FROM rooms WHERE room = '$roomx'");
                                                                                $maxSlots = mysqli_fetch_row($maxSlotsSql)[0];

                                                                                if ($max < $maxSlots) 
                                                                                    $max = $maxSlots;
                                                                            }
                                                                        ?>

                                                                        <div class="col-xs-2">
                                                                            <label>Max Slots</label>
                                                                            <input type="number" value="<?php echo $classes['max_slots']; ?>" min=0 max=<?php echo $max; ?> class="center eMaxSlots form-control <?php echo $modalID; ?> ">
                                                                        </div>
                                                                    </div>

                                                                    <?php 
                                                                        $tsArr = array();
                                                                        $roomArr = array();
                                                                        $teArr = array();
                                                                        $exist = array();

                                                                        for($i = 0; $i < 7; $i++) {
                                                                            if(in_array($week[$i], $day)) {
                                                                                $ind = array_search($week[$i], $day);
                                                                                $tsArr[$i] = $timestart[$ind];
                                                                                $teArr[$i] = $timeend[$ind];
                                                                                $exist[$i] = 1;

                                                                                if ($room[$ind] == " ") {
                                                                                    $roomArr[$i] = "";
                                                                                }
                                                                                else {
                                                                                    $roomArr[$i] = $room[$ind];
                                                                                }
                                                                            }
                                                                            else {
                                                                                $tsArr[$i] = null;
                                                                                $teArr[$i] = null;
                                                                                $roomArr[$i] = null;
                                                                                $exist[$i] = 0;
                                                                            }
                                                                        }
                                                                    ?>


                                                                    <div class="row erowMon <?php if($exist[0] == 0) echo 'hidden'; ?>">
                                                                        <br>
                                                                        <div class="col-lg-3">
                                                                            <label for="text">Day</label>
                                                                            <input type = "text" value="Mon" readonly class="form-control">
                                                                        </div>

                                                                        <div class="col-lg-3">
                                                                            <label>Time Start</label>
                                                                            <input type="time" value="<?php echo $tsArr[0]; ?>" class="center <?php echo $modalID; ?> form-control">
                                                                        </div>

                                                                        <div class="col-lg-3">
                                                                            <label>Time End</label>
                                                                            <input type="time" value="<?php echo $teArr[0]; ?>" class="center <?php echo $modalID; ?> form-control">
                                                                        </div>

                                                                        <div class="input-field col-lg-3">
                                                                            <label>Room</label>
                                                                            <input type="text" class="eRoomSelect <?php echo $modalID; ?> form-control" value="<?php echo $roomArr[0]; ?>">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row erowTue <?php if($exist[1] == 0) echo 'hidden'; ?>">
                                                                        <br>
                                                                        <div class="input-field col-lg-3">
                                                                            <label for="text">Day</label>
                                                                            <input type = "text" value="Tue" readonly class="form-control">
                                                                        </div>

                                                                        <div class="col-lg-3">
                                                                            <label>Time Start</label>
                                                                            <input type="time" value="<?php echo $tsArr[1]; ?>" class="center <?php echo $modalID; ?> form-control">
                                                                        </div>

                                                                        <div class="col-lg-3">
                                                                            <label>Time End</label>
                                                                            <input type="time" value="<?php echo $teArr[1]; ?>" class="center <?php echo $modalID; ?> form-control">
                                                                        </div>

                                                                        <div class="input-field col-lg-3">
                                                                            <label>Room</label>
                                                                            <input type="text" class="eRoomSelect <?php echo $modalID; ?> form-control" value="<?php echo $roomArr[1]; ?>">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row erowWed <?php if($exist[2] == 0) echo 'hidden'; ?>">
                                                                        <br>
                                                                        <div class="input-field col-lg-3">
                                                                            <label for="text">Day</label>
                                                                            <input type = "text" value="Wed" readonly class="form-control">
                                                                        </div>

                                                                        <div class="col-lg-3">
                                                                            <label>Time Start</label>
                                                                            <input type="time" value="<?php echo $tsArr[2]; ?>" class="center <?php echo $modalID; ?> form-control">
                                                                        </div>

                                                                        <div class="col-lg-3">
                                                                            <label>Time End</label>
                                                                            <input type="time" value="<?php echo $teArr[2]; ?>" class="center <?php echo $modalID; ?> form-control">
                                                                        </div>

                                                                        <div class="input-field col-lg-3">
                                                                            <label>Room</label>
                                                                            <input type="text" class="eRoomSelect <?php echo $modalID; ?> form-control" value="<?php echo $roomArr[2]; ?>">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row erowThu <?php if($exist[3] == 0) echo 'hidden'; ?>">
                                                                        <br>
                                                                        <div class="input-field col-lg-3">
                                                                            <label for="text">Day</label>
                                                                            <input type = "text" value="Thu" readonly class="form-control">
                                                                        </div>

                                                                        <div class="col-lg-3">
                                                                            <label>Time Start</label>
                                                                            <input type="time" value="<?php echo $tsArr[3]; ?>" class="center <?php echo $modalID; ?> form-control">
                                                                        </div>

                                                                        <div class="col-lg-3">
                                                                            <label>Time End</label>
                                                                            <input type="time" value="<?php echo $teArr[3]; ?>" class="center <?php echo $modalID; ?> form-control">
                                                                        </div>

                                                                        <div class="input-field col-lg-3">
                                                                            <label>Room</label>
                                                                            <input type="text" class="eRoomSelect <?php echo $modalID; ?> form-control" value="<?php echo $roomArr[3]; ?>">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row erowFri <?php if($exist[4] == 0) echo 'hidden'; ?>">
                                                                        <br>
                                                                        <div class="input-field col-lg-3">
                                                                            <label for="text">Day</label>
                                                                            <input type = "text" value="Fri" readonly class="form-control">
                                                                        </div>

                                                                        <div class="col-lg-3">
                                                                            <label>Time Start</label>
                                                                            <input type="time" value="<?php echo $tsArr[4]; ?>" class="center <?php echo $modalID; ?> form-control">
                                                                        </div>

                                                                        <div class="col-lg-3">
                                                                            <label>Time End</label>
                                                                            <input type="time" value="<?php echo $teArr[4]; ?>" class="center <?php echo $modalID; ?> form-control">
                                                                        </div>

                                                                        <div class="input-field col-lg-3">
                                                                            <label>Room</label>
                                                                            <input type="text" class="eRoomSelect <?php echo $modalID; ?> form-control" value="<?php echo $roomArr[4]; ?>">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row erowSat <?php if($exist[5] == 0) echo 'hidden'; ?>">
                                                                        <br>
                                                                        <div class="input-field col-lg-3">
                                                                            <label for="text">Day</label>
                                                                            <input type = "text" value="Sat" readonly class="form-control">
                                                                        </div>

                                                                        <div class="col-lg-3">
                                                                            <label>Time Start</label>
                                                                            <input type="time" value="<?php echo $tsArr[5]; ?>" class="center <?php echo $modalID; ?> form-control">
                                                                        </div>

                                                                        <div class="col-lg-3">
                                                                            <label>Time End</label>
                                                                            <input type="time" value="<?php echo $teArr[5]; ?>" class="center <?php echo $modalID; ?> form-control">
                                                                        </div>

                                                                        <div class="input-field col-lg-3">
                                                                            <label>Room</label>
                                                                            <input type="text" class="eRoomSelect <?php echo $modalID; ?> form-control" value="<?php echo $roomArr[5]; ?>">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row erowSun <?php if($exist[6] == 0) echo 'hidden'; ?>">
                                                                        <br>
                                                                        <div class="input-field col-lg-3">
                                                                            <label for="text">Day</label>
                                                                            <input type = "text" value="Sun" readonly class="form-control">
                                                                        </div>

                                                                        <div class="col-lg-3">
                                                                            <label>Time Start</label>
                                                                            <input type="time" value="<?php echo $tsArr[6]; ?>" class="center <?php echo $modalID; ?> form-control">
                                                                        </div>

                                                                        <div class="col-lg-3">
                                                                            <label>Time End</label>
                                                                            <input type="time" value="<?php echo $teArr[6]; ?>" class="center <?php echo $modalID; ?> form-control">
                                                                        </div>

                                                                        <div class="input-field col-lg-3">
                                                                            <label>Room</label>
                                                                            <input type="text" class="eRoomSelect <?php echo $modalID; ?> form-control" value="<?php echo $roomArr[6]; ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-footer panel-footer">
                                                                    <center>
                                                                        <button class="btn btn-primary eDelete <?php echo $modalID; ?>">
                                                                        <i class="glyphicon glyphicon-remove"></i>
                                                                        Remove</button> 
                                                                        <button class="btn btn-success eSave <?php echo $modalID; ?>" disabled>
                                                                        <i class="glyphicon glyphicon-save"></i> Save Changes</button>
                                                                    </center>
                                                                </div>

                                                            </div>  
                                                        </div>
                                                    </div>
                                                        
                                                <?php
                                                    $im++;
                                                    }

                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <br>

                            <center>
                                <button class="btn btn-primary" id="printButton">
                                    <i class="glyphicon glyphicon-print"></i>&nbsp Print
                                </button>
                            </center>
                        </div>

                        <div class="tab-pane fade" id="createClasses">
                            <div class="row">
                                <div class="col-xs-6">
                                    <label>
                                        Subject Title
                                    </label>

                                    <select id="subjectTitle" data-maxOptions="10" class="form-control" style="width: 100%" required>
                                        <option value="" disabled selected>Choose One</option>
                                        <?php 
                                            $subjectsSql = mysqli_query($connect, "SELECT * FROM subjects WHERE gradschool_id = $gradschoolid ORDER BY subject_title ASC");

                                            while($subjects = mysqli_fetch_assoc($subjectsSql)){

                                                echo '
                                                        <option value="'.$subjects['subject_id'].'">'.$subjects['subject_name'].' - '.ucwords(strtolower($subjects['subject_title'])).'</option>
                                                     ';

                                            }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-xs-2">
                                    <label>
                                        Section
                                    </label>

                                    <select id="section" class="form-control" style="width: 100%">
                                        <?php
                                            $i=1;
                                            while ($i < 11) {
                                                if($classes['section'] == $i)
                                                    echo '<option selected> '.$i.' </option>';
                                                else
                                                    echo '<option> '.$i.' </option>';
                                                $i++;   
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-xs-6">
                                    <label>
                                        Faculty
                                    </label>

                                    <select id="faculty" class="form-control" style="width:100%">
                                        <option value=" " selected>Choose One</option>
                                        <?php
                                            $facultySql = mysqli_query($connect, "SELECT a.faculty_id, a.name FROM faculty a JOIN faculty_designation b ON a.faculty_id = b.faculty_id 
                                                WHERE b.gradschool_id = $gradschoolid AND b.designation_id = 1002");

                                            while ($faculty = mysqli_fetch_assoc($facultySql)) {

                                                echo '
                                                        <option name="createFaculty" value="'.$faculty['faculty_id'].'">'.ucwords(strtolower($faculty['name'])).'</option>
                                                     ';
                                            }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-xs-2">
                                    <label>
                                        Max Slots
                                    </label>
                                    <input type="number" style="text-align: center; width: 100%" 
                                        min="0" maxlength="2" id="maxSlots" value = 40 class="form-control">
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-xs-6">
                                    <label>Class Day/s</label>
                                    <select id="days" class="form-control" style="width:100%">
                                        <!-- <option value="">Choose Day/s</option> -->
                                        <option value="Mon">Monday</option>
                                        <option value="Tue">Tuesday</option>
                                        <option value="Wed">Wednesday</option>
                                        <option value="Thu">Thursday</option>
                                        <option value="Fri">Friday</option>
                                        <option value="Sat">Saturday</option>
                                        <option value="Sun">Sunday</option>
                                    </select>
                                </div>

                                <div class="col-xs-2">
                                    <label>
                                        Units
                                    </label>
                                    <input type="number" style="text-align: center" min=0 max=10 id="units" readonly class="form-control">
                                </div>
                            </div>

                            <div class="row hidden" id="trowMon">
                                <br>

                                <div class="col-xs-2">
                                    <div class="input-field">
                                        
                                </div>



                                <div class="col-xs-2">
                                    <div class="input-field">
                                        <label>Day</label>
                                        <input type="text" value="Sunday" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-2">
                                    <label>Time Start</label>
                                    <input type="time" class="form-control">
                                </div>
                                <div class="col-xs-2">
                                    <label>Time End</label>
                                    <input type="time" class="form-control">
                                </div>
                                <div class="col-xs-2">
                                    <div class="input-field">
                                        <label>Room</label>
                                        <input type="text" class="roomSelect autocomplete form-control">
                                    </div>
                                </div>
                            </div>



                            <br>

                            <div class="row">
                                <div class="col-xs-8">
                                    <center>
                                        <button class="btn btn-success" id="saveCreate" disabled>
                                        <i class="glyphicon glyphicon-save"></i> Save Changes</button>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <script src="../js/jquery-3.1.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap4.min.js"></script>
    <script src="../js/select2.min.js"></script>
    <script src="../js/sweetalert2.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#classTable').DataTable({
                "language": {
                  "emptyTable": "No classes available."
                },
                "bDeferRender": true,
                initComplete: function () {
                    this.api().columns([2,4,5,7]).every( function () {
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

        $("select#classaysem").change(function(){
            var classaysem = $(this).val();
            $.ajax({
                type: "POST",
                async: true,
                cache: true,
                url: "facultyAjax.php",
                data: "result="+classaysem+"&action=aysemSession",
                success:
                    function( data, status, xhr ) { 
                        window.location.reload();
                    }
            });
        })

        var inputOptions = new Promise(function (resolve) {
             resolve({
                '1': 'Per Class',
                '2': 'Per Room'
            })
        });

        $("#printButton").click(function(){
            swal({
                type: 'question',
                html: 'Print the class list of your graduate school',
                input: 'radio',

                confirmButtonText: 'Continue Printing',

                inputOptions: inputOptions,
                inputValidator: function (result) {
                    return new Promise(function (resolve, reject) {
                        if (result) {
                            resolve()
                        } else {
                            reject('You need to select something!')
                        }
                    })
                }
            }).then(function(result){
                $.ajax({
                    type: "POST",
                    async: true,
                    cache: true,
                    url: "facultyAjax.php",
                    data: "result="+result+"&action=printSession",
                    success:
                        function( data, status, xhr ) { 
                            window.open('../printables/classListpCl.php','_blank');
                        }
                });
            })
        });

        function findModalID(classes) {

            var classessArray = [], size = 0, modalID = '';

            if(classes && classes.length && classes.split) {
                classes = jQuery.trim(classes); 
                classes = classes.replace(/\s+/g,' ');
                classessArray = classes.split(' ');
                size = classessArray.length;
            }

            for (i = 0; i < size; i++) {
                if (classessArray[i].indexOf("Modal") >= 0)
                    modalID = classessArray[i];
            }

            return modalID;
        }

        function isEmpty(variable) {
            if (variable == null || variable == '') 
                return true;
            else
                return false;
        }

        function dispMsg(variable){

        }

        $(".close").click(function(){
            $(this).parent().fadeOut(200);
        });

        $("select.eFaculty").change(function(e){

            var modalid = findModalID( $(this).attr("class") );
            var units = $("#" + modalid + " .eUnits").val();
            var classid = $("#" + modalid + " .eClassid").val();

            $('#' + modalid + ' .eSave').attr('disabled', false);
            var faculty = $(this).val();
            $.ajax({
                type: "POST",
                async: true,
                cache: true,
                url: "facultyAjax.php",
                data: "faculty="+faculty+"&units="+units+"&classid="+classid+"&action=changeFaculty",
                success:
                    function( data, status, xhr ) { 
                        if(data) {  
                            swal({
                                title: "Warning",
                                text: data,
                                type: "warning",
                                confirmButtonClass: "btn-warning",
                            });
                        }
                    } 
            });
        });

        $(".eSection").change(function(){
            var modalid = findModalID( $(this).attr("class") );
            
            var section = $(this).val();
            var classid = $("#" + modalid + " .eClassid").val();
            var subjectid = $("#" + modalid + " .eSubjectid").val();

            $(this).parent().removeClass("invalid text-danger");
            $.ajax({
                type: "POST",
                async: true,
                cache: true,
                url: "facultyAjax.php",
                data: "section="+section+"&subjectTitle="+subjectid+"&classid="+classid+"&action=changeSection",
                success:
                    function( data, status, xhr ) { 
                        //alert(data);
                        if(data == 'error'){
                            $("#" + modalid + " .eSection").parent().addClass("invalid text-danger");
                            swal({
                                title: "Invalid selection",
                                text: "Selected section for this subject is taken. \nPlease change the section",
                                type: "warning",
                                confirmButtonClass: "btn-warning",
                            });
                        }
                }
            });

            $("#" + modalid + " .eSave").attr("disabled", false);
        });

        $(".eMaxSlots, #maxSlots").change(function(){
            var modalid = findModalID( $(this).attr("class") );
            if ($(this).val() == '' || $(this).val() < 0) {
                $(this).val(0).change();
            }
            else
                $("#" + modalid + " .eSave").attr("disabled", false);            
        });

        $(".eDays").each(function(){
            $(this).data("pre", $(this).val());
        });

        $(".eRoomSelect").change(function(){
            var modalid = findModalID( $(this).attr("class") );
            $("#" + modalid + " .eSave").attr("disabled", false);
        });

        $(".eDays").change(function(){
            var modalid = findModalID( $(this).attr("class") );
            var x = $(this).val();
            var currentlySelected = x,
                beforeSelected = $(this).data("pre");

            if( $.inArray("", currentlySelected) != -1) {   //If the default option is selected
                if ($.inArray("", beforeSelected) == -1 && beforeSelected != "") {  //If the new selected option is "" or Default option
                    //remove all selected option except the first one
                    $(this).val("");
                    $(this).find("option[value='']").attr("selected", true);
                    x = "";
                }
                else {
                    //remove the first selected option
                    x.shift();
                    $(this).val(x);
                    $(this).find("option[value='']").attr("selected", false);
                }
            }
            else {
                $(this).find("option[value='']").attr("selected", false);
            }

            $("#" + modalid + " div[class*='erow']").each(function(){

                y = $(this).attr("class").split(" ")[1].substr(4);

                if($.inArray(y, x) != -1) {
                    $(this).removeClass("hidden");
                }
                else {
                    $(this).addClass ("hidden");
                }
            });

            $('#' + modalid + ' .eSave').attr('disabled', false);
            $(this).data("pre", $(this).val());
            //$("#" + modalid + " .eRoomSelect").change();
        });

        $("input[type='time']").change(function(){
            var modalid = findModalID( $(this).attr("class") );
            $('#' + modalid + ' .eSave').attr('disabled', false);                
        });

        $(".eSave").click(function(){

            var modalid = findModalID( $(this).attr("class") );

            var id = $("#" + modalid + " .eClassid").val();         //classid
            var a = $("#" + modalid + " .eSubjectid").val();        //subjectid
            var b = $("#" + modalid + " select.eSection").val();    //section
            var c = $("#" + modalid + " select.eFaculty").val();    //faculty
            var d = $("#" + modalid + " .eMaxSlots").val();         //slots
            var e = $("#" + modalid + " select.eDays").val();       //days

            var f = []; //timestart
            var g = []; //timeend
            var h = []; //room
            var y, i = 0;
            var unfilled = false;

            if (!isEmpty(e)) {
                $("#" + modalid + " div[class*='erow']").each(function(){
                    y = $(this).attr("class").split(" ")[1].substr(4);

                    if($.inArray(y, e) != -1) {
                        f[i] = $(this).find("input").eq(1).val();
                        g[i] = $(this).find("input").eq(2).val();
                        h[i] = $(this).find("input").eq(3).val();

                        // if (isEmpty(f[i])){
                        //     $(this).find("input").eq(1).parent().addClass('invalid text-danger');
                        //     unfilled = true;
                        // } 
                        // else
                        //     $(this).find("input").eq(1).parent().removeClass('invalid text-danger');

                        // if (isEmpty(g[i])) {
                        //     $(this).find("input").eq(2).parent().addClass('invalid text-danger');  
                        //     unfilled = true; 
                        // }
                        // else
                        //     $(this).find("input").eq(2).parent().removeClass('invalid text-danger');

                        if (isEmpty(h[i])) {
                            h[i] = " ";
                        }
                        i++;
                    }
                });
            }
            if (isEmpty(d)) {
                $("#" + modalid + " .eMaxSlots").parent().addClass('invalid text-danger');                
                unfilled = true;
            }
            else {
                $("#" + modalid + " .eMaxSlots").parent().removeClass('invalid text-danger');
            }

            if (b == "") {
                $("#" + modalid + " .eSection").parent().removeClass('invalid text-danger');
                unfilled = true;
            }


            if (unfilled) {
                swal({
                    title: "Invalid",
                    text: "Dont leave any blank fields.",
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });
            }
            else {
                $.ajax({
                    type: "POST",
                    async: true,
                    cache: true,
                    url: "facultyAjax.php",
                    data: "classid="+id+"&subject="+a+"&section="+b+"&faculty="+c+"&maxSlots="+d+"&days="+e+"&timestart="+f+"&timeend="+g+"&room="+h+"&action=createClass",
                    success:
                        function( info, status, xhr ) {
                            //alert(info);
                            if (info) {
                                var obj = $.parseJSON(info);
                                
                                switch (obj.title) {
                                    case 'SECTION_PROB':
                                        $("#" + modalid +" .eSection").parent().addClass('invalid text-danger');
                                    break;
                                    case 'TIME': 

                                        var len = obj.data.length;
                                        for (i = 0; i < len; i++) {
                                            $("#" + modalid +" .erow" + obj.data[i] + " input[type='time']").parent().addClass('invalid text-danger');
                                        }
                                    break;
                                    case 'PROF_SCHED':
                                        $("#" + modalid +" select.eFaculty").parent().addClass('invalid text-danger');
                                    break;
                                    case 'CLASS_SCHED':

                                        var len = obj.data.length;
                                        for (i = 0; i < len; i++) {
                                            $("#" + modalid +" .erow" + obj.data[i]).find("input").parent().addClass('invalid text-danger');
                                        }
                                    break;
                                    case 'CLASS_ALLOW':

                                        if (obj.subtitle == "PROF_SCHED") {
                                            $("#" + modalid +" select.eFaculty").parent().addClass('invalid text-danger');
                                        }
                                        else if (obj.subtitle == "CLASS_SHED") {
                                            var len = obj.data.length;
                                            for (i = 0; i < len; i++) {
                                                $("#" + modalid +" .erow" + obj.data[i]).find("input").parent().addClass('invalid text-danger');
                                            }
                                        }

                                    break;
                                }
                                if (obj.title == 'CLASS_ALLOW') {
                                    swal({
                                        title: "Allow creation anyway?",
                                        text: obj.msg,
                                        type: "question",
                                        showCancelButton: true
                                    }).then(function(){
                                        $("#" + modalid + " .eSave").click();
                                    },  function(dismiss) {
                                            if (dismiss == "cancel") {
                                                $.ajax({
                                                    type: "POST",
                                                    async: true,
                                                    cache: true,
                                                    url: "facultyAjax.php",
                                                    data: "action=defaultClassAllow"
                                                });
                                            }
                                        }
                                    );
                                }
                                else {
                                     swal({
                                        title: "Error!",
                                        text: obj.msg,
                                        type: "error",
                                        confirmButtonClass: "btn-danger"
                                    });
                                }
                            }
                            else {
                                swal({
                                    title: "Success!",
                                    text: "The class is succesfully updated",
                                    type: "success",
                                    confirmButtonClass: "btn-success"
                                }).then(function(){
                                    window.setTimeout(function(){location.reload()},10);
                                })
                            }
                        }
                });
            }
        });

        $(".eDelete").click(function(){
            var modalid = findModalID( $(this).attr("class") );
            var classid = $("#" + modalid + " .eClassid").val();
            
            swal({
                title: "Confirm Action",
                text: "Delete this class?",
                type: "error",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                closeOnConfirm: false,
                reverseButtons: true,
                showLoaderOnConfirm: true
            }).then(function(){
                $.ajax({
                    type: "POST",
                    async: true,
                    cache: true,
                    url: "facultyAjax.php",
                    data: "classid="+classid+"&action=deleteClass",
                    success:
                        function(){
                            setTimeout(function () {
                                swal({
                                    title: "Deleted!",
                                    text: "This class has been deleted successfully",
                                    type: "success"
                                }).then(function(){
                                    window.setTimeout(function(){location.reload()},10);
                                });

                                
                            }, 10);  
                        }
                });
            })
        });


        //$("#days").data("pre", $(this).val());

        $('#createClasses select, #createClasses input[type="number"]').each(function(){
            $(this).attr("disabled", true);
        });

        $("#subjectTitle").attr("disabled", false);

        $("#subjectTitle").change(function() {
            var x = $(this).val();

            $.ajax({
                type: "POST",
                async: true,
                cache: true,
                url: "facultyAjax.php",
                data: "subjectTitle="+x+"&action=changeSubjTitle",
                success:
                    function( data, status, xhr ) { 
                        data = data.split("|");
                        $("#units").val(data[1]);
                        if ($("#faculty").val() != null)
                            $("#faculty").change();
                    }
            });

            $("#createClasses select").each(function(){
                $(this).attr("disabled", false);
            })

            $("#section").change();
            $("#maxSlots").attr("disabled", false);
            $("#saveCreate").attr("disabled", false);
        });

        $("#section").change(function(){
            var x = $(this).val(),
                subjectTitle = $("#subjectTitle").val(),
                classid = 0;

            $("#section").parent().removeClass("invalid text-danger");
            $.ajax({
                type: "POST",
                async: true,
                cache: true,
                url: "facultyAjax.php",
                data: "section="+x+"&subjectTitle="+subjectTitle+"&classid="+classid+"&action=changeSection",
                success:
                    function( data, status, xhr ) { 
                        //alert(data);
                        if (data == 'error') {
                            $("#section").parent().addClass("invalid text-danger");
                            swal({
                                title: "Invalid selection",
                                text: "Selected section for this subject is taken. \nPlease change the section",
                                type: "warning",
                                confirmButtonClass: "btn-warning",
                            });
                        }
                    }
            });
        });

        $("#faculty").change(function(e){
            var faculty = $(this).val();
            var units = $("#units").val();
            var classid = "";

      
            $.ajax({
                type: "POST",
                async: true,
                cache: true,
                url: "facultyAjax.php",
                data: "faculty="+faculty+"&units="+units+"&classid="+classid+"&action=changeFaculty",
                success:
                    function( data, status, xhr ) { 
                        if(data) {

                            // $("#dangerMsg").html(data);
                            // $("#dangerCreate").css("display", "block");
                            // $('body .main-panel').animate({ scrollTop: 0 }, 'slow');
                           // setTimeout(function(){ $("#dangerCreate").addClass("hidden"); }, 2000);  
                        }
                    } 
            });
        });

        $("#days").change(function(e){
            var x = $(this).val();
            
            var y;
            var currentlySelected = x,
                beforeSelected = $(this).data("pre");

            if( $.inArray("", currentlySelected) != -1) {   //If the default option is selected
                if ($.inArray("", beforeSelected) == -1 && beforeSelected != "") {  //If the new selected option is "" or Default option
                    //remove all selected option except the first one
                    $(this).val("");
                    $(this).find("option[value='']").attr("selected", true);
                    x = "";
                }
                else {
                    //remove the first selected option
                    x.shift();
                    $(this).val(x);
                    $(this).find("option[value='']").attr("selected", false);
                }
            }
            else {
                $(this).find("option[value='']").attr("selected", false);
            }

            //Hiding and Showing Time and room row 
            $("div[id^='trow']").each(function(){
                y = $(this).attr("id").substr(4);

                if($.inArray(y, x) != -1) {
                    $(this).removeClass("hidden");
                }
                else {
                    $(this).removeClass("hidden");
                    $(this).addClass("hidden");
                }
            });

            $("#days").data("pre", $(this).val());
            //$(".roomSelect").change();
        });

        $("#saveCreate").click(function(){
            var id = "";                        //classid
            var a = $("#subjectTitle").val();   //subject
            var b = $("#section").val();        //section
            var c = $("#faculty").val();        //faculty
            var d = $("#maxSlots").val();       //slots
            var e = $("#days").val();           //days  

            var f = []; //timestart
            var g = []; //timeend
            var h = []; //room
            var y, i = 0;
            var unfilled = false;

            //Checking of time and room if they are empty
            if (!isEmpty(e)) {
                $("div[id^='trow']").each(function(){
                    y = $(this).attr("id").substr(4);

                    if($.inArray(y, e) != -1) {
                        f[i] = $(this).find("input").eq(1).val();
                        g[i] = $(this).find("input").eq(2).val();
                        h[i] = $(this).find("input").eq(3).val();

                        // if (isEmpty(f[i])) 
                        //     // $(this).find("input").eq(1).addClass('invalid text-danger');
                        //     swal("Error!", "Don't leave any blank fields. (Time Start/s)", "error");
                        // if (isEmpty(g[i])) 
                        //     swal("Error!", "Don't leave any blank fields. (Time End/s)", "error");
                        if (isEmpty(h[i])) {
                            h[i] = " ";
                        }
                        i++;
                    }
                });
            }

            if (isEmpty(a)) {
                $('select#subjectTitle').parent().addClass('invalid text-danger');
                unfilled = true;
            }

            if (isEmpty(d)) {
                $("#maxSlots").parent().addClass('invalid text-danger');
                unfilled = true;
            }

            if (b == "") {
                $("#section").parent().addClass('invalid text-danger');
                unfilled = true;
            }
            
            if (unfilled) {
                swal("Error!", "Don't leave any blank fields", "error");

                // $("#dangerMsg").html("Don't leave any blank fields.");
                // $("#dangerCreate").css("display", "block");
                // $('body .main-panel').animate({ scrollTop: 0 }, 'slow');
                //setTimeout(function(){ $("#dangerCreate").addClass("hidden"); }, 2000);
            }
            else {
                $.ajax({
                    type: "POST",
                    async: true,
                    cache: true,
                    url: "facultyAjax.php",
                    data: "classid="+id+"&subject="+a+"&section="+b+"&faculty="+c+"&maxSlots="+d+"&days="+e+"&timestart="+f+"&timeend="+g+"&room="+h+"&action=createClass",
                    success:
                        function( info, status, xhr ) { 

                            if (info) {
                                var obj = $.parseJSON(info);
                                
                                switch (obj.title) {
                                    case 'SECTION_PROB':
                                        $("#section").parent().addClass('invalid text-danger');
                                        $("#section").siblings().addClass('invalid text-danger');
                                    break;
                                    case 'TIME': 

                                        var len = obj.data.length;
                                        for (i = 0; i < len; i++) {
                                            $("#trow" + obj.data[i] + " input[type='time']").addClass('invalid text-danger');
                                        }
                                    break;
                                    case 'PROF_SCHED':
                                        $("#Faculty").parent().addClass('invalid text-danger');
                                        $("#Faculty").siblings().addClass('invalid text-danger');
                                    break;
                                    case 'CLASS_SCHED':

                                        var len = obj.data.length;
                                        for (i = 0; i < len; i++) {
                                            $("#trow" + obj.data[i]).find("input, select").addClass('invalid text-danger');
                                        }
                                    break;
                                    case 'CLASS_ALLOW':

                                        if (obj.subtitle == "PROF_SCHED") {
                                            $("#Faculty").parent().addClass('invalid text-danger');
                                            $("#Faculty").siblings().addClass('invalid text-danger');
                                        }
                                        else if (obj.subtitle == "CLASS_SHED") {
                                            var len = obj.data.length;
                                            for (i = 0; i < len; i++) {
                                                $("#trow" + obj.data[i]).find("input, select").addClass('invalid text-danger');
                                            }
                                        }

                                    break;
                                }

                                if (obj.title == 'CLASS_ALLOW') {
                                    swal({
                                        title: "Allow creation anyway?",
                                        text: obj.msg,
                                        type: "question",
                                        showCancelButton: true
                                    }).then(function(){
                                        $("#saveCreate").click();
                                    },  function(dismiss) {
                                            if (dismiss == "cancel") {
                                                $.ajax({
                                                    type: "POST",
                                                    async: true,
                                                    cache: true,
                                                    url: "facultyAjax.php",
                                                    data: "action=defaultClassAllow"
                                                });
                                            }
                                        }
                                    );
                                }
                                else {
                                    swal({
                                        title: "Error!",
                                        text: obj.msg,
                                        type: "error",
                                        confirmButtonClass: "btn-danger"
                                    });
                                }
                            }
                            else {
                                swal({
                                    title: "Success!",
                                    text: "The class is succesfully added",
                                    type: "success",
                                    confirmButtonClass: "btn-success"
                                }).then(function(){
                                    window.setTimeout(function(){location.reload()},10);
                                })
                            }
                        }
                });
            }

        });

    </script>


    <script>
    $(document).ready(function(){
      var date_input=$('input[name="date"]'); //our date input has the name "date"
      var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
      var options={
        format: 'mm/dd/yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
      };
      date_input.datepicker(options);
    })
</script>

    </body>
</html>
