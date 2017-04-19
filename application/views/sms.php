<!DOCTYPE html>
<html>
<head>
<?php echo '<title>'.$title.'</title>'.$meta_scripts.$css_scripts; ?>
<style>
textarea { resize:vertical; }
</style>
</head>

<?php echo $navbar_scripts; ?>
<body>

<div class="container-fluid">
		<div class="row">
			<div class="col-sm-5">
				<div class="row">
					<div class="col-sm-12">
						<?php echo form_open("sms/send",'id="send-sms" class="form-horizontal"');?>

						<div class="form-group">
							<label class="col-sm-2" for="recipient"></label>
							<div class="col-sm-10">
								<label class="radio-inline"><input type="radio" name="send_option" value="individual" checked>Individual</label>
								<label class="radio-inline"><input type="radio" name="send_option" value="all">All</label>
							</div>
						</div>

							<div class="form-group">
							  <label class="col-sm-2" for="recipient">Recipient:</label>
							  <div class="col-sm-10">
							    <select class="ui fluid search dropdown" multiple="" name="contact_id[]">
							    	<option value="">Select Name</option>
							    	<?php
							    	foreach ($contact_list as $contact_data) {
							    		echo '<option value="'.$contact_data->id.'">'.$contact_data->name.' ['.$contact_data->number.']</option>';
							    	}
							    	?>
							    </select>
							    <p class="help-block" id="recipient"></p>
							  </div>
							</div>

							<div class="form-group">
							  <label class="col-sm-2" for="recipient">Message:</label>
							  <div class="col-sm-10">
							  	<textarea class="form-control" placeholder="Message" name="message" required></textarea>
							  	<p class="help-block" id="message"></p>
							  </div>
							</div>


							<div class="form-group">
								<label class="col-sm-2" for="recipient"></label>
								<div class="col-sm-10">
									<button class="btn btn-primary" type="submit" form="send-sms">Send</button>
									<?php

									echo '
									<img src="'.base_url("assets/images/loading.gif").'" style="width:3rem;height:3rem;display:none" class="loading"></img>
									';?>
								</div>
							</div>

						</form>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
					<?php echo form_open("sms/add",'id="add" class="form-horizontal"');?>

						<div class="form-group">
						  <label class="col-sm-2" for="Name">Name:</label>
						  <div class="col-sm-10">
						  	<input type="text" name="name" class="form-control" placeholder="Name" required>
						  <p class="help-block" id="name"></p>
						  </div>
						</div>

						<div class="form-group">
						  <label class="col-sm-2" for="number">Number:</label>
						  <div class="col-sm-10">
						  	<input type="text" name="number" class="form-control" placeholder="Number" required>
						  <p class="help-block" id="number"></p>
						  </div>
						</div>

						<div class="form-group">
							<label class="col-sm-2" for="recipient"></label>
							<div class="col-sm-10">
								<button class="btn btn-primary" type="submit" form="add">Add Contact</button>
							</div>
						</div>


					</form>

					</div>
				</div>
			</div>
			<div class="col-sm-7">
				<table class="table table-hover" id="sms-list-table">
					<thead>
						<tr>
							<th>Message</th>
							<th>Recipient</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			</div>
		</div>

</div>
<?php echo $modaljs_scripts; ?>

<?php echo $js_scripts; ?>
<script>
var needToConfirm = false;
$(document).on("submit","#send-sms",function(e) {
	e.preventDefault();
	needToConfirm = true;
	$('button[form="send-sms"]').prop("disabled",true);
	$('button[form="send-sms"]').html("Sending...");
	$('.loading').css("display", "initial");
	$.ajax({
		type: "POST",
		data: $("#send-sms").serialize(),
		url: $("#send-sms").attr("action"),
		cache: false,
		dataType: "json",
		success: function(data) {
			needToConfirm = false;
			console.log(data);
			$('button[form="send-sms"]').prop("disabled",false);
			$('.loading').css("display", "none");
			$('button[form="send-sms"]').html("Send");
			if(data.is_valid){
				$("#send-sms")[0].reset();
				$(".help-block").html("");
				$(".dropdown").dropdown("clear");
				$(".help-block#message").html("Your message has been submitted.");
				show_sms_list();
			}else{
				$(".help-block#recipient").html(data.contact_id);
				$(".help-block#message").html(data.message);
			}
		}
	});
});
window.onbeforeunload = confirmExit;
function confirmExit() {
  if (needToConfirm) return "sdasdasdasd";
}
$(document).on("submit","#add",function(e) {
	e.preventDefault();
	$('button[form="add"]').prop("disabled",true);
	$.ajax({
		type: "POST",
		data: $("#add").serialize(),
		url: $("#add").attr("action"),
		cache: false,
		dataType: "json",
		success: function(data) {
			console.log(data);
			$('button[form="add"]').prop("disabled",false);
			if(data.is_valid){
				$("#add")[0].reset();
				$(".help-block").html("");
				$(".help-block#name").html("Successfully added.");
			}else{
				$(".help-block#name").html(data.name);
				$(".help-block#number").html(data.number);
			}
		}
	});
});
show_sms_list();
function show_sms_list(page=1) {
		
	$.ajax({
		type: "GET",
		url: "<?php echo base_url("sms/get_sms"); ?>",
		data: "page="+page,
		cache: false,
		success: function(data) {
			$("#sms-list-table tbody").html(data);
		}
	});
}


</script>
</body>
</html>