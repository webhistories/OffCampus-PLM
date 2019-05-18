<?php include("db.php"); ?>
<?php
if(isset($_POST['c_id'])) {
	$sql = "select * from `program` where `program_id`=".mysqli_real_escape_string($con, $_POST['c_id']);
	$res = mysqli_query($con, $sql);
	if(mysqli_num_rows($res) > 0) {
		echo "<option value=''>------- Select --------</option>";
		while($row = mysqli_fetch_object($res)) {
			echo "<option value='".$row->id."' data-value='".$row->subj_title."'>".$row->subj_code."</option> </tr>";
		}
	}
} else {
	header('location: ./');
}
?>
