<!DOCTYPE html>
<?php 
    include 'facultyDashboard.php';

    $startYear = 2017;
    $curYear = date( "Y" );

    $termsSql = mysqli_query($connect, "SELECT terms FROM graduate_schools WHERE gradschool_id = $gradschoolid");
    $terms = mysqli_fetch_row($termsSql);

    if($terms[0] == 3)
        $term = array('', '1st Trimester', '2nd Trimester', '3rd Trimester');
    else
        $term = array('', '1st Semester', '2nd Semester', 'Summer');

?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRS | Calendar Management</title>
    </head>

    <body style="padding-top: 80px">
        <div class="col-xs-8 col-xs-offset-2">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 style="color: white">Schedule of Activities for <?php echo $curYear.'-'.($curYear+1); ?></h4>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-2">
                            <label>
                                Year
                            </label>

                            <select id="activityYear" name="activityYear" class="form-control" style="width: 100%">
                                <option value=" " disabled selected>Choose One</option>
                                <?php 
                                    while($startYear <= $curYear){
                                        echo '<option value="'.$startYear.'">'.$startYear.'</option>';
                                        $startYear++;
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="col-xs-3">
                            <label>
                                Term
                            </label>

                            <select id="activityTerm" name="activityTerm" class="form-control" style="width: 100%">
                                <option value=" " disabled selected>Choose One</option>
                                <?php 
                                    for($i=1;$i<4;$i++){
                                        echo '<option value="'.$i.'">'.$term[$i].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-xs-3">  
                            <button class="btn btn-success" id="viewButton" name="viewButton">
                            <i class="glyphicon glyphicon-search"></i> View Schedule</button>
                        </div>
                    </div>

                    <br>

                    <div class="row hidden" id="tableClass">
                    <?php
                        $activitiesSql = mysqli_query($connect, "SELECT DISTINCT activity_id, activity_name FROM activities ORDER BY activity_id");

                        while($activities = mysqli_fetch_assoc($activitiesSql)){
                            echo '
                                    <div class="activityClass" id="'.$activities['activity_id'].'">
                                        <div class="col-xs-4">
                                            <h4>'.$activities['activity_name'].'</h4>
                                        </div>
                                        <div class="col-xs-4">
                                            <label>Date Start (yyyy-mm-dd)</label>
                                
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-calendar"></i>
                                                </span>
                                    
                                                <input type="text" id="'.$activities['activity_id'].'dateStart" class="form-control datepicker">
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <label>Date Start (yyyy-mm-dd)</label>
                                
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-calendar"></i>
                                                </span>
                                    
                                                <input type="text" id="'.$activities['activity_id'].'dateEnd"  class="form-control datepicker">
                                            </div>
                                        </div>
                                    </div>
                                 ';
                        }
                    ?>
                    </div>                    
                </div>

                <div class="panel-footer">
                    <button class="btn btn-success" id="saveButton">Save Changes</button>   
                </div>
            </div>
        </div>

    <script src="../js/jquery-3.1.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap4.min.js"></script>
    <script src="../js/select2.min.js"></script>
    <script src="../js/sweetalert2.min.js"></script>
    <script src="../js/bootstrap-datepicker.min.js"></script>


    <script type="text/javascript">
        $(document).ready(function(){
            $("select").select2();

            $(".datepicker").datepicker({
                format: "yyyy-mm-dd"
            });

            $("#saveButton").addClass("hidden");
        });

        $("#viewButton").click(function(){
            var year = $("#activityYear").val(), term = $("#activityTerm").val();

            if(year == null || term == null){
                swal({
                    title: 'Error',
                    type: 'error',
                    html: 'Choose year and/or term for scheduled dates.'
                });

                $("#tableClass").addClass("hidden");
                $("#saveButton").addClass("hidden");
            }
            else {
                aysem = year+term;

                $.ajax({
                    type: "POST",
                    async: true,
                    cache: true,
                    url: "facultyAjax.php",
                    data: "aysem="+aysem+"&action=viewActivity",
                    success:
                        function( data, status, xhr ) { 

                            if (data == "||") { //there is no such schedule for preferred aysem
                                swal({
                                    title: 'Error',
                                    type: 'question',
                                    html: 'There is no existing schedule. \nDo you want to create?',
                                    confirmButtonText: "Okay",
                                    showCancelButton: true
                                }).then(function(result){

                                    $("#tableClass").find("input[type='text']").attr('placeholder', '');
                                    $("#tableClass").find("input[type='text']").val('');

                                    $("#tableClass").removeClass("hidden");
                                    $("#saveButton").removeClass("hidden");
                                });
                            }
                            else {
                                data = data.split('|');
                                activityArray = data[0].split(',');
                                timestartArray = data[1].split(',');
                                timeendArray = data[2].split(',');

                                var ctr = activityArray.length;
                                
                                for (var i = 0; i < ctr; i++) {
                                    $("#" + activityArray[i]).find("input[type='text']").eq(0).attr('placeholder', timestartArray[i]);
                                    $("#" + activityArray[i]).find("input[type='text']").eq(0).val(timestartArray[i]);

                                    $("#" + activityArray[i]).find("input[type='text']").eq(1).attr('placeholder', timeendArray[i]);
                                    $("#" + activityArray[i]).find("input[type='text']").eq(1).val(timeendArray[i]);
                                }

                                $("#tableClass").removeClass("hidden");
                                $("#saveButton").removeClass("hidden");
                            }
                        }
                });
            }



        });

        $("#saveButton").click(function(){
            var year = $("#activityYear").val(), term = $("#activityTerm").val();

            if(year == null || term == null){
                swal({
                    title: 'Error',
                    type: 'error',
                    html: 'Choose year and/or term for scheduled dates.'
                });
            }
            else 
                aysem = year+term;

            var activity, timestart, timeend, activityArray = [], timestartArray = [], timeendArray = [], i = 0;

            $(".panel-body .activityClass").each(function(){
                activity = $(this).attr("id");
                timestart = $(this).find("input[type='text']").eq(0).val();
                timeend = $(this).find("input[type='text']").eq(1).val();

                // if (timestart == "") {
                //     timestart = $(this).find("input[type='text']").eq(0).attr('placeholder');
                // }

                // if (timeend == "") {
                //     timeend = $(this).find("input[type='text']").eq(1).attr('placeholder');
                // }
                activityArray.push(activity);
                timestartArray.push(timestart);
                timeendArray.push(timeend);
                
                
            });
            $.ajax({
                type: "POST",
                async: true,
                cache: true,
                url: "facultyAjax.php",
                data: "aysem="+aysem+"&activityArray="+activityArray+"&timestartArray="+timestartArray+"&timeendArray="+timeendArray+"&action=saveActivity",
                success:
                    function( data, status, xhr ) { 
                        swal({
                            title: 'Success!',
                            type: 'success',
                            html: 'Schedules has been successfully saved.'
                        });

                        $("#viewButton").click();
                    }
            });

        });

        $("#activityYear, #activityTerm").change(function(){
            $("#tableClass").addClass("hidden");
            $("#saveButton").addClass("hidden");
        });

        
    </script>

    </body>
</html>
