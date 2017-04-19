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
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="table-responsive">
      <?php echo form_open("tables/classes/list",'id="class-list-form"');?>
      <label>Search Class Name</label>
      <select class="ui search dropdown" name="id">
        <option value="">Select Class Name</option>
        <?php
          foreach ($classes_list["result"] as $classe_data) {
            echo '<option value="'.$classe_data->id.'">'.$classe_data->class_name.'</option>';
          }
        ?>
      </select>
      <button class="btn btn-primary" type="submit">Search</button>
      <button class="btn btn-danger" type="button" id="reset">Reset</button>
      </form>
				<table class="table table-hover" id="class-list-table">
					<thead>
						<tr>
							<th>Class Name</th>
              <th>Grade or Year</th>
							<th>Classroom</th>
              <th>Schedule</th>
              <th>Class Adviser</th>
							<th></th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>

<?php

echo '
<!-- Edit Class Modal -->
<div id="class_edit_modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title" id="rfid_add_modal_title">Add students</h4>
    </div>
      <div class="modal-body">
        <p>
        '.form_open("class_ajax/edit",'id="class_edit_form"  class="form-horizontal"').'
          <input type="hidden" name="class_id" class="edit_field">
          <div class="form-group">
            <label class="col-sm-4" for="class_adviser">Class Adviser:</label>
            <div class="col-sm-8">
            ';
            // var_dump($teachers_list);
            echo '
              <select name="class_adviser" class="ui search dropdown form-control edit_field" id="edit-class_adviser">
                <option value="">Select a Class Adviser</option>
                ';
                foreach ($teachers_list["result"] as $teacher_data) {
                  echo '<option value="'.$teacher_data->id.'">'.$teacher_data->full_name.'</option>';
                }

                echo '
              </select>
              <p class="help-block" id="class_adviser_help-block"></p>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4" for="class_name">Class Name:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control edit_field" name="class_name" placeholder="Enter Class Name">
              <p class="help-block" id="class_class_name_help-block"></p>
            </div>
          </div>


          <div class="form-group">
            <label class="col-sm-4" for="grade">Grade or Year:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control edit_field" name="grade" placeholder="Enter Grade or Year">
              <p class="help-block" id="class_grade_help-block"></p>
            </div>
          </div>


          <div class="form-group">
            <label class="col-sm-4" for="class_room">Classroom:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control edit_field" name="class_room" placeholder="Enter Classroom">
              <p class="help-block" id="class_room_help-block"></p>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4" for="class_schedule">Class Schedule:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control edit_field" name="class_schedule" placeholder="Enter Class Schedule">
              <p class="help-block" id="class_schedule_help-block"></p>
            </div>
          </div>

        </form>
        </p>
      </div>
      <div class="modal-footer">
       
        <button type="submit" class="btn btn-primary" form="class_edit_form">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

';

?>
<?php echo $modaljs_scripts; ?>
<?php echo $js_scripts; ?>
<script>


$(document).on("click",".edit_class",function(e) {
    var id = e.target.id;
    // alert(id);
    show_class_data(id);
});

$(document).on("click",".delete_class",function(e) {
  var datastr = "id="+e.target.id;
  if(confirm("Are you sure you want to delete this class? This acton is irreversible.")){
    $.ajax({
      type: "POST",
      url: "<?php echo base_url("class_ajax/delete"); ?>",
      data: datastr,
      cache: false,
      success: function(data) {
        show_class_list();
      }
    });
  }
});

function show_class_data(id) {
  $.ajax({
    type: "GET",
    url: "<?php echo base_url("class_ajax/get_data"); ?>",
    data: "class_id="+id,
    cache: false,
    dataType: "json",
    success: function(data) {
      // alert(data);
      if(data.teacher_id!=""){
	      $('#edit-class_adviser').dropdown('set value',data.teacher_id);
      }else{
	      $('#edit-class_adviser').dropdown('clear');
      }
      $('input[name="class_name"].edit_field').val(data.class_name);
      $('input[name="grade"].edit_field').val(data.grade);
      $('input[name="class_room"].edit_field').val(data.room);
      $('input[name="class_schedule"].edit_field').val(data.schedule);
      $('input[name="class_id"]').val(id);
      $("#class_edit_modal").modal("show");
    }
  });
}

$(document).on("submit","#class_edit_form",function(e) {
	e.preventDefault();
	$('button[form="class_edit_form"]').prop('disabled',true);
		$.ajax({
		type: "POST",
		url: $("#class_edit_form").attr("action"),
		data: $("#class_edit_form").serialize(),
		cache: false,
		dataType: "json",
		success: function(data) {
			$('button[form="class_edit_form"]').prop('disabled',false);
			if(data.is_valid){
				$(".help-block").html("");
				$("#alert-modal-title").html("Edit Class");
				$("#alert-modal-body p").html("You have successfully the class information.");
				$("#class_edit_modal").modal("hide");
				$("#alert-modal").modal("show")
				show_class_list();
			}else{
				$("#class_adviser_help-block").html(data.class_adviser_error);
				$("#class_class_name_help-block").html(data.class_name_error);
				$("#class_grade_help-block").html(data.grade_error);
				$("#class_room_help-block").html(data.class_room_error);
				$("#class_schedule_help-block").html(data.class_schedule_error);
				$("#class_schedule_help-block").html(data.class_schedule_error);
			}
		}
		});
	});
$(document).on("click",".paging",function(e) {
	show_class_list(e.target.id);
});

$(document).on("submit","#class-list-form",function(e) {
  e.preventDefault();
  show_class_list();  
});

$(document).on("click","#reset",function(e) {
  $(".ui").dropdown("clear");
  show_class_list();
});



$("#search_last_name").autocomplete({
	source: "<?php echo base_url("search/classes/list"); ?>",
	select: function(event, ui){
		show_class_data(ui.item.data);
		$("#search_last_name").val("");
	}
});

show_class_list();
function show_class_list(page='1') {
  var datastr = $("#class-list-form").serialize();
	$.ajax({
		type: "GET",
    url: $("#class-list-form").attr("action"),
		data: datastr+"&page="+page,
		cache: false,
		success: function(data) {
			$("#class-list-table tbody").html(data);
		}
	});
}
</script>
</body>
</html>