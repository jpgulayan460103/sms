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
	      <select class="ui search dropdown" id="students-select" name="owner_id">
	      <option value="">Select Student</option>
	      </select>
	      <input type="hidden" name="class_id" value="<?php echo $teacher_data->class_id; ?>">
	      <button class="btn btn-primary" type="submit" form="student-list-form">Search</button>
	      <span class="btn btn-danger" id="clear">Clear</span>
	      </form>
				<table class="table table-hover" id="student-list-table">
					<thead>
						<tr>
							<th>Last Name</th>
							<th>First Name</th>
							<th>Middle Name</th>
							<th>Suffix</th>
							<th>Gender</th>
							<th>Age</th>
							<th>Birthday</th>
							<th>Contact Number</th>
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

$(document).on("click","#clear",function(e) {
	$("#students-select").dropdown("clear");
	show_student_list();
});

$(document).on("change","#students-select",function(e) {
	show_student_list();
});

$(document).on("submit","#student-list-form",function(e) {
	e.preventDefault();
	show_student_list();
});

$(document).on("click",".paging",function(e) {
	show_student_list(e.target.id);
});
show_student_list();
populate_selection()
function show_student_list(page='1',clear=false) {
  var datastr = $("#student-list-form").serialize();
	$.ajax({
		type: "GET",
	    url: $("#student-list-form").attr("action"),
		data: datastr+"&page="+page,
		cache: false,
		success: function(data) {
      if(clear){
        $("#search_last_name").val("");
      }
			$("#student-list-table tbody").html(data);
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