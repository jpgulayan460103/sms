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
						<h3 style="text-align: center;">Send SMS</h3>
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
							    		echo '<option value="'.$contact_data->id.'">'.$contact_data->number.'</option>';
							    		// echo '<option value="'.$contact_data->id.'">'.$contact_data->name.' ['.$contact_data->number.']</option>';
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

								<label class="col-sm-2" for="recipient">
									
								</label>
								<div class="col-sm-10">
									<button class="btn btn-primary" type="submit" form="send-sms">Send</button>
									<?php

									echo '
									<img src="'.base_url("assets/images/loading.gif").'" style="width:3rem;height:3rem;display:none" class="loading" id="sms"></img>
									';?>
									<span class="btn btn-info" id="view-api-data">View SMS API Data</span>
								</div>
							</div>


							<div class="form-group">

								<label class="col-sm-2" for="MessagesLeft">
									<p class="help-block" id="MessagesLeft-label"></p>
								</label>
								<div class="col-sm-10">
									<p class="help-block" id="MessagesLeft"></p>
								</div>
							</div>

							<div class="form-group">

								<label class="col-sm-2" for="ExpiresOn">
									<p class="help-block" id="ExpiresOn-label"></p>
								</label>
								<div class="col-sm-10">
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
						  <label class="col-sm-2" for="Name">Name:</label>
						  <div class="col-sm-10">
						  	<input type="text" name="name" class="form-control" placeholder="Name">
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
				<div class="row">
					<div class="col-sm-12">
						<?php echo form_open("sms/send_email",'id="send-email" class="form-horizontal"');?>
						<h3 style="text-align: center;">Send Email</h3>
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
							    	<option value="">Select Email Address</option>
							    	<?php
							    	foreach ($email_contact_list as $email_contact_data) {
							    		echo '<option value="'.$email_contact_data->id.'">'.$email_contact_data->email_address.'</option>';
							    		// echo '<option value="'.$email_contact_data->id.'">'.$email_contact_data->name.' ['.$email_contact_data->number.']</option>';
							    	}
							    	?>
							    </select>
							    <p class="help-block" id="recipient_email"></p>
							  </div>
							</div>


							<div class="form-group">
							  <label class="col-sm-2" for="from_name">From:</label>
							  <div class="col-sm-10">
							  	<input type="text" class="form-control" placeholder="From" name="from_name" required>
							  	<p class="help-block" id="from_name"></p>
							  </div>
							</div>

							<div class="form-group">
							  <label class="col-sm-2" for="subject">Subject:</label>
							  <div class="col-sm-10">
							  	<input type="text" class="form-control" placeholder="Subject" name="subject" required></textarea>
							  	<p class="help-block" id="subject"></p>
							  </div>
							</div>

							<div class="form-group">
							  <label class="col-sm-2" for="message">Message:</label>
							  <div class="col-sm-10">
							  	<textarea class="form-control" placeholder="Message" name="message" required></textarea>
							  	<p class="help-block" id="message_email"></p>
							  </div>
							</div>


							<div class="form-group">

								<label class="col-sm-2" for="recipient">
									
								</label>
								<div class="col-sm-10">
									<button class="btn btn-primary" type="submit" form="send-email">Send</button>
									<?php

									echo '
									<img src="'.base_url("assets/images/loading.gif").'" style="width:3rem;height:3rem;display:none" class="loading" id="email"></img>
									';?>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
					<?php echo form_open("sms/add_email",'id="add_email" class="form-horizontal"');?>
					<h3 style="text-align: center;">Add Contacts</h3>
						<div class="form-group">
						  <label class="col-sm-2" for="Name">Name:</label>
						  <div class="col-sm-10">
						  	<input type="text" name="name" class="form-control" placeholder="Name">
						  <p class="help-block" id="name_email"></p>
						  </div>
						</div>

						<div class="form-group">
						  <label class="col-sm-2" for="email_address">Email Address:</label>
						  <div class="col-sm-10">
						  	<input type="text" name="email_address" class="form-control" placeholder="Email Address" required>
						  <p class="help-block" id="email_address"></p>
						  </div>
						</div>

						<div class="form-group">
							<label class="col-sm-2" for="recipient"></label>
							<div class="col-sm-10">
								<button class="btn btn-primary" type="submit" form="add_email">Add Contact</button>
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
						url: "<?php echo base_url("/sms/test"); ?>",
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
					// console.log(data.messages[i]);
				    // $('#select_student').append('<option value="'+data.messages[i].id+'">'+data.messages[i].full_name+'</option>');
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
						url: "<?php echo base_url("/sms/test_email"); ?>",
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