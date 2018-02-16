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
			<div class="col-sm-12">
				<h3 style="text-align: center;">Submitted Messages</h3>
				<?php
				$this->db->where("status_code","4");
				if($this->db->get("sms")->num_rows()!=0){
					echo '<button class="btn btn-primary pull-right" id="resend">Resend Failed Messages <span id="messages"></span></button>';
				}

				?>
				<table class="table table-hover" id="sms-list-table">
					<thead>
						<tr>
							<th>Message</th>
							<th>Recipient</th>
							<th>Status</th>
							<th>Date Time</th>
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

$(document).on("click","#resend",function(e) {
	$("#resend").prop("disabled",true);
	$.ajax({
		url: "<?php echo base_url("sms/resend");?>",
		type: "GET",
		data: "get=1",
		cache: false,
		dataType: "json",
		success: function(data) {
			var count = 0;
			var count_1 = 0;
			$.each(data.messages, function(i, item) {
				// console.log(data.messages[i]);
				// count_1++;
				$.ajax({
					type: "POST",
					url: "<?php echo base_url("sms/test/"); ?>"+data.messages[i].id,
					// data: "count=0",
					data: "recipient="+data.messages[i].id+"&message="+data.messages[i].message,
					cache: false,
					success: function(new_response) {
						console.log(new_response);
						$("#messages").html(++count+"/"+data.messages.length);
						if(data.messages.length==count){
							// console.log("complete");
							location.reload();
							$(".help-block").html("");
							$(".dropdown").dropdown("clear");
							$(".help-block#message").html("Your message has been submitted.");
							show_sms_list();

							$('button[form="send-sms"]').prop("disabled",false);
							$('.loading').css("display", "none");
							$('button[form="send-sms"]').html("Send");
						}
					}
				})
				// console.log(data.messages[i]);
			    // $('#select_student').append('<option value="'+data.messages[i].id+'">'+data.messages[i].full_name+'</option>');
			});
			
		}
	});
});

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
								show_sms_list();

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