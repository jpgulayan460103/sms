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
		<?php echo form_open("tables/students/list/teachers",'id="student-list-form"');?>
		<input type="hidden" name="class_id" value="<?php echo $teacher_data->class_id; ?>">
		</form>


		<?php echo form_open("tables/gate_logs", 'id="gate_logs-form"'); ?>
		<input type="hidden" name="ref_table" id="ref_table" value="students">
		<select class="ui search dropdown" id="students-select" name="ref_id">
		<option value="">Select Student</option>
		</select>
		<input type="hidden" name="class_id" value="<?php echo $teacher_data->class_id; ?>">
		<label>Date From:</label>
		<input type="text" name="date_from" id="datepicker_from" value="<?php echo date("m/d/Y");?>" readonly>
		<label>Date To:</label>
		<input type="text" name="date_to" id="datepicker_to" value="<?php echo date("m/d/Y");?>" readonly>
		<button type="submit" class="btn btn-primary" form="gate_logs-form">Search</button>
		<span class="btn btn-danger" id="gate_logs-reset_search">Reset</span>
		</form>
		<table class="table table-hover" id="gatelogs-table">
			<thead>
				<tr>
					<th>Full Name</th>
					<th>RFID</th>
					<th>Date</th>
					<th>Time</th>
					<th>Type</th>
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
$(document).on("submit","#gate_logs-form",function(e) {
	e.preventDefault();
	$('input[name="ref_id"]').removeAttr("value");
	show_gatelogs();
});
$(document).on("click",".gate_logs",function(e) {
	$('input[name="ref_id"]').val(e.target.id);
	show_gatelogs();
});

$(document).on("change","#ref_table",function(e) {
	$('input[name="ref_id"]').val("");
	show_gatelogs();
});
$(document).on("change",'select[name="class_id"]',function(e) {
	show_gatelogs();
});
$(document).on("change","#datepicker_from,#datepicker_to",function(e) {
	show_gatelogs();
});
$(document).on("click","#gate_logs-reset_search",function(e) {
	$("#gate_logs-form")[0].reset();
	$('input[name="ref_id"]').val("");
	$("#students-select").dropdown("clear");
	show_gatelogs();
});

$(document).on("click",".paging",function(e) {
	show_gatelogs(e.target.id);
});

show_gatelogs();
populate_selection()
function show_gatelogs(page=1,clear) {
	var data_str = $("#gate_logs-form").serialize();
	$.ajax({
		type: "GET",
		url: $("#gate_logs-form").attr("action"),
		data: data_str+"&page="+page,
		cache: false,
		success: function(data) {
			$("#gatelogs-table tbody").html(data);
			if(clear){
				$("#search_last_name").val("");
			}
			
		}
	});
}

function populate_selection() {
	$("#students-select").html("");
	$.ajax({
		type: "GET",
		url: "<?php echo base_url("student_ajax/get_list/teachers"); ?>",
		data: $("#student-list-form").serialize(),
		cache: false,
		dataType: "json",
		success: function(data) {
			$.each(data, function(i, item) {
			    $("#students-select").append('<option value="'+data[i].id+'">'+data[i].full_name+'</option>');
			})
		}
	});
}

</script>
</body>
</html>