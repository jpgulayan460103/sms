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

<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="row">
					<div class="col-sm-12">
						<?php echo form_open("sms/send",'id="send-sms" class="form-horizontal"');?>
						<h3 style="text-align: center;">Send SMS</h3>
						<div class="form-group">
							<label class="col-sm-3" for="recipient"></label>
							<div class="col-sm-9">
								<label class="radio-inline"><input type="radio" name="send_option" value="individual" checked>Individual</label>
								<label class="radio-inline"><input type="radio" name="send_option" value="all">All</label>
								<select style="margin-left:20px" name="selector" onchange="changeSendType()" id="select-type" >
							    	<option value="name" <?= ($type == "name" ? "selected" : "") ?> >Name</option>
							    	<option value="number" <?= ($type == "number" ? "selected" : "") ?> >Number</option>
							    </select>
							</div>
						</div>

							<div class="form-group">
							  <label class="col-sm-3" for="recipient">Recipient's Number:</label>
							  <div class="col-sm-9">
							    <select class="ui fluid search dropdown" multiple="" name="contact_id[]">
							    	<option value="">Select Number</option>
							    	<?php
							    	foreach ($contact_list as $contact_data) {
							    		echo '<option value="'.$contact_data->id.'">'.$contact_data->number.' - '.$contact_data->name.'</option>';
							    		// echo '<option value="'.$contact_data->id.'">'.$contact_data->name.' ['.$contact_data->number.']</option>';
							    	}
							    	?>
							    </select>
							    <p class="help-block" id="recipient"></p>
							  </div>
							</div>

							<div class="form-group">
							  <label class="col-sm-3" for="recipient">Message:</label>
							  <div class="col-sm-9">
							  	<textarea class="form-control" placeholder="Message" name="message" required></textarea>
							  	<p class="help-block" id="message"></p>
							  </div>
							</div>


							<div class="form-group">

								<label class="col-sm-3" for="recipient">
									
								</label>
								<div class="col-sm-9">
									<button class="btn btn-primary" type="submit" form="send-sms">Send</button>
									<?php

									echo '
									<img src="'.base_url("assets/images/loading.gif").'" style="width:3rem;height:3rem;display:none" class="loading" id="sms"></img>
									';?>
									<span class="btn btn-info" id="view-api-data">View SMS API Data</span>
								</div>
							</div>


							<div class="form-group">

								<label class="col-sm-3" for="MessagesLeft">
									<p class="help-block" id="MessagesLeft-label"></p>
								</label>
								<div class="col-sm-9">
									<p class="help-block" id="MessagesLeft"></p>
								</div>
							</div>

							<div class="form-group">

								<label class="col-sm-3" for="ExpiresOn">
									<p class="help-block" id="ExpiresOn-label"></p>
								</label>
								<div class="col-sm-9">
									<p class="help-block" id="ExpiresOn"></p>
								</div>
							</div>



						</form>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
					<?php echo form_open("sms/add",'id="add" class="form-horizontal"');?>
					<h3 style="text-align: center;">Add Contacts</h3>
						<div class="form-group">
						  <label class="col-sm-3" for="Name">Name:</label>
						  <div class="col-sm-9">
						  	<input type="text" name="name" class="form-control" placeholder="Name">
						  <p class="help-block" id="name"></p>
						  </div>
						</div>

						<div class="form-group">
						  <label class="col-sm-3" for="number">Number:</label>
						  <div class="col-sm-9">
						  	<input type="text" name="number" class="form-control" placeholder="Number" required>
						  <p class="help-block" id="number"></p>
						  </div>
						</div>

						<div class="form-group">
							<label class="col-sm-3" for="recipient"></label>
							<div class="col-sm-9">
								<button class="btn btn-primary" type="submit" form="add">Add Contact</button>
							</div>
						</div>


					</form>

					</div>
				</div>
			</div>
			

</div>
<?php echo $modaljs_scripts; ?>

<?php echo $js_scripts; ?>
<script>
function changeSendType() {
	let selector = document.getElementById('select-type');
	window.location.href = `?type=${selector.value}`;
	
}
$(document).on("click","#view-api-data",function(e) {
	$.ajax({
		type: "GET",
		url: "<?php echo base_url("sms/get_api_data"); ?>",
		data: "get=1",
		cache: false,
		dataType: "json",
		success: function(data) {
			console.log(data);
			$("#MessagesLeft-label").html("Messages Left");
			$("#MessagesLeft").html(data.MessagesLeft);
			$("#ExpiresOn-label").html("Expires On");
			$("#ExpiresOn").html(data.ExpiresOn);
		}
	});
});

var needToConfirm = false;
$(document).on("submit","#send-sms",function(e) {
	e.preventDefault();
	needToConfirm = true;
	$('button[form="send-sms"]').prop("disabled",true);
	$('button[form="send-sms"]').html("Sending...");
	$('#sms.loading').css("display", "initial");
	$.ajax({
		type: "POST",
		data: $("#send-sms").serialize(),
		url: $("#send-sms").attr("action"),
		cache: false,
		dataType: "json",
		success: function(data) {
			needToConfirm = false;
			// console.log(data);
			if(data.is_valid){
				var count = 0;
				var count_1 = 0;
				$.each(data.messages, function(i, item) {
					// console.log(data.messages[i]);
					// count_1++;
					$.ajax({
						type: "POST",
						url: "<?php echo base_url("/sms/request_send_sms"); ?>",
						// data: "count=0",
						data: $("#send-sms").serialize()+"&recipient="+data.messages[i],
						cache: false,
						success: function(new_response) {
							// console.log(new_response);
							$(".help-block#message").html(++count+"/"+data.messages.length);
							if(data.messages.length==count){
								console.log("complete");
								$("#send-sms")[0].reset();
								$(".help-block").html("");
								$(".dropdown").dropdown("clear");
								$(".help-block#message").html("Your message has been submitted.");
								show_sms_list();

								$('button[form="send-sms"]').prop("disabled",false);
								$('#sms.loading').css("display", "none");
								$('button[form="send-sms"]').html("Send");
							}
						}
					})
				});
			}else{
				$(".help-block#recipient").html(data.contact_id);
				$(".help-block#message").html(data.message);

				$('button[form="send-sms"]').prop("disabled",false);
				$('.loading').css("display", "none");
				$('button[form="send-sms"]').html("Send");
			}
		}
	});
});


var needToConfirm = false;
$(document).on("submit","#send-email",function(e) {
	e.preventDefault();
	needToConfirm = true;
	$('button[form="send-email"]').prop("disabled",true);
	$('button[form="send-email"]').html("Sending...");
	$('#email.loading').css("display", "initial");
	$.ajax({
		type: "POST",
		data: $("#send-email").serialize(),
		url: $("#send-email").attr("action"),
		cache: false,
		dataType: "json",
		success: function(data) {
			needToConfirm = false;
			console.log(data);
			if(data.is_valid){
				var count = 0;
				var count_1 = 0;
				$.each(data.messages, function(i, item) {
					$.ajax({
						type: "POST",
						url: "<?php echo base_url("/sms/request_send_email"); ?>",
						data: $("#send-email").serialize()+"&recipient="+data.messages[i],
						cache: false,
						success: function(new_response) {
							console.log(new_response);
							$(".help-block#message_email").html(++count+"/"+data.messages.length);
							if(data.messages.length==count){
								console.log("complete");
								$("#send-email")[0].reset();
								$(".help-block").html("");
								$(".dropdown").dropdown("clear");
								$(".help-block#message_email").html("Your message has been submitted.");
								show_sms_list();

								$('button[form="send-email"]').prop("disabled",false);
								$('#email.loading').css("display", "none");
								$('button[form="send-email"]').html("Send");
							}
						}
					})
				});
			}else{
				$(".help-block#recipient").html(data.contact_id);
				$(".help-block#message").html(data.message);

				$('button[form="send-sms"]').prop("disabled",false);
				$('.loading').css("display", "none");
				$('button[form="send-sms"]').html("Send");
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
			$(".help-block").html("");
			if(data.is_valid){
				$("#add")[0].reset();
				$(".help-block#name").html("Successfully added.");
			}else{
				$(".help-block#name").html(data.name);
				$(".help-block#number").html(data.number);
			}
		}
	});
});

$(document).on("submit","#add_email",function(e) {
	e.preventDefault();
	$('button[form="add_email"]').prop("disabled",true);
	$.ajax({
		type: "POST",
		data: $("#add_email").serialize(),
		url: $("#add_email").attr("action"),
		cache: false,
		dataType: "json",
		success: function(data) {
			console.log(data);
			$('button[form="add_email"]').prop("disabled",false);
			$(".help-block").html("");
			if(data.is_valid){
				$("#add_email")[0].reset();
				$(".help-block#name_email").html("Successfully added.");
			}else{
				$(".help-block#name_email").html(data.name_email);
				$(".help-block#email_address").html(data.email_address);
			}
		}
	});
});

$(document).on("click",".paging",function(e) {
	show_sms_list(e.target.id);
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