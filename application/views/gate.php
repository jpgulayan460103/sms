<!DOCTYPE html>
<html>
<head>
<?php echo '<title>'.$title.'</title>'.$meta_scripts.$css_scripts; ?>
<style>
#display-photo{
	display: block;
	margin-left: auto;
	margin-right: auto
}
</style>
</head>

<?php echo $navbar_scripts; ?>
<body id="has-logo">
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12">
			<?php echo form_open("rfid_ajax/scangate",'id="gate_rfid_scan"'); ?>
				<center>
				<input type="text" name="gate_rfid_scan" autocomplete="off" autofocus>
				</center>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div id="display-photo-container">
				<img class="img-responsive" id="display-photo" src="<?php echo base_url("assets/images/empty.jpg");?>">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-md-push-3">
			<div class="table-responsive" style="background-color: white;">
				<table class="table table-hover">
					<thead>
						<tr>
							<th colspan="2" class="table-header" style="text-transform: uppercase;"><span id="rfid_owner"></span> Information</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th>Last Name:</th>
							<td><span id="gate_rfid_last_name"></span></td>
						</tr>
						<tr>
							<th>First Name:</th>
							<td><span id="gate_rfid_first_name"></span></td>
						</tr>
						<tr>
							<th>Middle Name:</th>
							<td><span id="gate_rfid_middle_name"></span></td>
						</tr>
						<tr>
							<th>Suffix:</th>
							<td><span id="gate_rfid_suffix"></span></td>
						</tr>
						<tr>
							<th colspan="2" id="gate_status" class="table-header"></th>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php echo $modaljs_scripts; ?>
<?php echo $js_scripts; ?>
<script>
// $("#gate_status").addClass("danger");
$(document).on("submit","#gate_rfid_scan", function(e) {
	e.preventDefault();
	$.ajax({
		type: "POST",
		url: $("#gate_rfid_scan").attr("action"),
		data: $("#gate_rfid_scan :input").serialize(),
		cache: false,
		dataType: "json",
		success: function(data) {
			$("#gate_rfid_scan")[0].reset();

			$("#gate_rfid_last_name").html(data.last_name);
			$("#gate_rfid_first_name").html(data.first_name);
			$("#gate_rfid_middle_name").html(data.middle_name);
			$("#gate_rfid_suffix").html(data.suffix);
			$("#gate_status").removeClass( "danger success" );
			$("#gate_status").html("");
			if(data.is_valid){
				$("#rfid_scan").val("");
				$("#display-photo").attr("src",data.display_photo);
				$("#rfid_owner").html(data.rfid_data.ref_table + "'s");

				if(data.gate_logs_data.is_valid){
					$("#gate_status").html(data.sms_status);
					$("#gate_status").html("Success!");
					$("#gate_status").removeClass( "danger success" ).addClass("success");
				}else{
					$("#gate_status").html("Error!");
					$("#gate_status").removeClass( "danger success" ).addClass("danger");
				}
				$(".help-block").html("");
			}else{
				$("#display-photo").attr("src",data.display_photo);
				$("#rfid_scan").val("");
			}
		}
	});

});
</script>
</body>
</html>