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
			<div class="col-sm-6">
				<h3 style="text-align: center;">SMS Contacts</h3>
				<table class="table table-hover" id="sms-contact-table">
					<thead>
						<tr>
							<th>Name</th>
							<th>Contact Number</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			</div>
			<div class="col-sm-6">
				<h3 style="text-align: center;">Submitted Messages</h3>
				<table class="table table-hover" id="sms-email-contact-table">
					<thead>
						<tr>
							<th>Name</th>
							<th>Email Address</th>
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
	$('.loading').css("display", "initial");
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
						url: "//localhost/sms/sms/test",
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
								show_contact_list();

								$('button[form="send-sms"]').prop("disabled",false);
								$('.loading').css("display", "none");
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
$(document).on("click",".sms_paging",function(e) {
	show_contact_list(e.target.id);
});

show_contact_list();
function show_contact_list(page=1) {
		
	$.ajax({
		type: "GET",
		url: "<?php echo base_url("sms/get_contacts"); ?>",
		data: "page="+page,
		cache: false,
		success: function(data) {
			$("#sms-contact-table tbody").html(data);
		}
	});
}
$(document).on("click",".email_paging",function(e) {
	show_email_contact_list(e.target.id);
});

show_email_contact_list();
function show_email_contact_list(page=1) {
		
	$.ajax({
		type: "GET",
		url: "<?php echo base_url("sms/get_email_contacts"); ?>",
		data: "page="+page,
		cache: false,
		success: function(data) {
			$("#sms-email-contact-table tbody").html(data);
		}
	});
}


$(document).on("click",".delete-sms-contact",function(e) {
	var data_str = "id="+e.target.id;
	if(confirm("Are you sure you want to delete the selected contact? This action is irrevisible.")){
		$.ajax({
				type: "POST",
				url: "<?php echo base_url("sms/delete_sms_contact");?>",
				cache: false,
				data: data_str,
				success: function(data) {
					show_contact_list();
				}
			});
	}
})
$(document).on("click",".delete-email-contact",function(e) {
	var data_str = "id="+e.target.id;
	if(confirm("Are you sure you want to delete the selected email? This action is irrevisible.")){
		$.ajax({
				type: "POST",
				url: "<?php echo base_url("sms/delete_email_contact");?>",
				cache: false,
				data: data_str,
				success: function(data) {
					show_email_contact_list();
				}
			});
	}
})

</script>
</body>
</html>



