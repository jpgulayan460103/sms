<!DOCTYPE html>
<html>
<head>
<?php echo '<title>'.$title.'</title>'.$meta_scripts.$css_scripts; ?>
<style>

</style>
</head>

<?php echo $navbar_scripts; ?>
<body>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<?php echo form_open("tables/sms/threads_list",'id="sms_list_form"'); ?>
			<input type="hidden" name="sent_by_id" value="<?php echo $teacher_data->id; ?>">
			<input type="hidden" name="sent_by_table" value="teachers">
			<label>Date From:</label>
			<input type="text" name="date_from" id="datepicker_from" value="<?php echo date("m/d/Y");?>" readonly>
			<label>Date To:</label>
			<input type="text" name="date_to" id="datepicker_to" value="<?php echo date("m/d/Y");?>" readonly>
			<button type="submit" class="btn btn-primary" form="sms_list_form">Search</button>
			</form>
			<div class="table-responsive">
				<table class="table table-hover" id="sms_threads_table">
					<thead>
						<tr>
							<th>Message ID:</th>
							<th>Messages:</th>
							<th>Date</th>
							<th>Time</th>
							<th>Sender</th>
							<th>Sender</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>
<?php echo $modaljs_scripts; ?>

<?php echo $js_scripts; ?>
<script>
$(document).on("click",".message",function(e) {
	var id = $(this).attr("id");
	$.ajax({
		type: "GET",
		data: "sms_id="+id,
		url: "<?php echo base_url("sms_ajax/get_data"); ?>",
		cache: false,
		dataType: "json",
		success: function(data) {
			$("#sms-list-modal").modal("show");
			$("#message_id_txt").html(id);
		    $('.sms_list_table tbody').html("");

			$.each(data, function(i, item) {
			    $('.sms_list_table tbody').append('\
			    	<tr>\
			    	<td>'+data[i].message+'</td>\
			    	<td>'+data[i].mobile_number+'</td>\
			    	<td>'+data[i].recipient+'</td>\
			    	<td>'+data[i].status+'</td>\
			    	</tr>\
			    	');
			});
		}
	});
});

$(document).on("submit","#sms_list_form",function(e) {
	e.preventDefault();
	show_sms_threads();
});

var needToConfirm = false;
$(document).on("click",".resend_sms",function(e) {
	var dataStr = "id="+e.target.id;
	needToConfirm = true;
	$.ajax({
		type: "POST",
		url: "<?php echo base_url("sms_ajax/resend");?>",
		data: dataStr,
		cache: false,
		dataType: "json",
		success: function(data) {
			needToConfirm = false;
			
			if(data.is_success){
				$("#alert-modal").modal("show");
				$("#alert-modal-title").html("RESEND MESSAGES");
				$("#alert-modal-body p").html("You have successfully resent the message.");
			}else{
				$("#alert-modal").modal("show");
				$("#alert-modal-title").html("ERRROR RESENDING MESSAGES");
				$("#alert-modal-body p").html("Please Check the message status.");
			}
				show_sms_threads();
			// body...
		}
	});
});

window.onbeforeunload = confirmExit;
function confirmExit()
{
  if (needToConfirm)
    return "sdasdasdasd";
}
$(document).on("click",".paging",function(e) {
	show_sms_threads(e.target.id);
});

show_sms_threads();
function show_sms_threads(page=1) {
	$.ajax({
		type: "GET",
		url: $("#sms_list_form").attr("action"),
		data: $("#sms_list_form").serialize()+"&page="+page,
		cache: false,
		success: function(data) {
			// alert(data);
			$("#sms_threads_table tbody").html(data);
		}
	});
}
</script>
</body>
</html>