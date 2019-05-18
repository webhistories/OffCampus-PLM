$(document).ready(function() {
	$("#country").change(function() {
		var program_id = $(this).val();
		if(program_id != "") {
			$.ajax({
				url:"get-states.php",
				data:{c_id:program_id},
				type:'POST',
				success:function(response) {
					var resp = $.trim(response);
					$("#state").html(resp);
				}
			});
		} else {
			$("#state").html("<option value=''>------- Select --------</option>");
		}
	});
});
