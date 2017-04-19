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
      <?php echo form_open("tables/teachers/list",'id="teacher-list-form"');?>
      <label>Search Last Name</label>
      <select class="ui search dropdown" name="owner_id">
        <option value="">Select Teacher's Last Name</option>
        <?php
          foreach ($teachers_list["result"] as $teacher_data) {
            echo '<option value="'.$teacher_data->id.'">'.$teacher_data->full_name.'</option>';
          }
        ?>
      </select>
      <button class="btn btn-primary" type="submit">Search</button>
      <button class="btn btn-danger" type="button" id="reset">Reset</button>
      </form>
				<table class="table table-hover" id="teacher-list-table">
					<thead>
						<tr>
              <th>RFID</th>
              <th>First Name</th>
              <th>Middle Name</th>
              <th>Last Name</th>
              <th>Suffix</th>
              <th>Gender</th>
              <th>Age</th>
              <th>Birthday</th>
              <th>Contact Number</th>
              <th>Class</th>
              <th>Edit</th>
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

<!--Edit teacher Modal -->
<div id="teacher_edit_modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit teachers</h4>
      </div>
      <div class="modal-body">
        <p>'.form_open_multipart("teacher_ajax/edit",'id="teacher_edit_form" class="form-horizontal"').'
        <input type="hidden" name="teacher_id">

        
        <div class="form-group">
          <label class="col-sm-2" for="last_name">Last Name:</label>
          <div class="col-sm-10"> 
            <input type="text" class="form-control edit_field" name="last_name" placeholder="Enter Last Name">
            <p class="help-block" id="last_name_help-block"></p>
          </div>
        </div>

        
          <div class="form-group">
            <label class="col-sm-2" for="first_name">First Name:</label>
            <div class="col-sm-10">
              <input type="text" class="form-control edit_field" name="first_name" placeholder="Enter First Name">
              <p class="help-block" id="first_name_help-block"></p>
            </div>
            
          </div>

          
          <div class="form-group">
            <label class="col-sm-2" for="middle_name">Middle Name:</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control edit_field" name="middle_name" placeholder="Enter Middle Name">
              <p class="help-block" id="middle_name_help-block"></p>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2" for="suffix">Suffix:</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control edit_field" name="suffix" placeholder="Enter Suffix (Jr. III etc.)">
              <p class="help-block" id="suffix_help-block"></p>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2" for="guardian_name">Gender:</label>
            <div class="col-sm-10"> 
              <select name="gender" class="form-control edit_field" required>
                <option value="MALE">MALE</option>
                <option value="FEMALE">FEMALE</option>
              </select>
              <p class="help-block" id="gender_help-block"></p>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2" for="contact_number">Contact Number:</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control edit_field" name="contact_number" placeholder="Enter Contact Number">
              <p class="help-block" id="contact_number_help-block"></p>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2" for="address">Address:</label>
            <div class="col-sm-10"> 
              <input type="text" class="form-control edit_field" name="address" placeholder="Enter Address">
              <p class="help-block" id="address_help-block"></p>
            </div>
          </div>


          <div class="form-group">
            <label class="col-sm-2" for="last_name">Birth Date:</label>
            <div class="col-sm-10">
              <select class="edit_field" name="bday_m" required>
                <option value="">MM</option>
                ';
                for ($i=1; $i <= 12; $i++) { 
                  echo '<option value="'.$i.'">'.sprintf("%02d",$i).'</option>';
                }
                echo '
              </select>
              /
              <select class="edit_field" name="bday_d" required>
                <option value="">DD</option>
                ';
                for ($i=1; $i <= 31; $i++) { 
                  echo '<option value="'.$i.'">'.sprintf("%02d",$i).'</option>';
                }
                echo '
              </select>
              /
              <select class="edit_field" name="bday_y" required>
                <option value="">YYYY</option>
                ';
                for ($i=1940; $i <= date("Y"); $i++) { 
                  echo '<option value="'.$i.'">'.sprintf("%04d",$i).'</option>';
                }
                echo '
              </select>
              <p class="help-block" id="bday_help-block"></p>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2" for="class_id">Class:</label>
            <div class="col-sm-10"> 
              <select name="class_id" class="ui search dropdown form-control edit_field" id="edit-class_id">
                <option value="">Select a Class</option>
                ';
                foreach ($classes_list["result"] as $class_data) {
                  echo '<option value="'.$class_data->id.'">'.$class_data->class_name.'</option>';
                }

                echo '
              </select>

              <p class="help-block" id="teacher_class_id_help-block"></p>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2" for="teacher_photo">Photo:</label>
            <div class="col-sm-10">
            <input type="file" name="teacher_photo" size="20" class="form-control">
              <p class="help-block" id="teacher_photo_help-block"></p>
            </div>
          </div>

        </form></p>
      </div>
      <div class="modal-footer">
      <p class="help-block" id="teacher_id_help-block"></p>
        <button type="submit" class="btn btn-primary" form="teacher_edit_form">Submit</button>
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

$(document).on("click",".add_rfid_teacher",function(e) {
  var id = e.target.id;
  $('input[name="type"]').val("teachers");
  $('input[name="id"]').val(id);
  $("#rfid_add_modal_title").html("scan teacher&apos;s rfid");
  $("#rfid_scan_add_modal").modal("show");
});




$(document).on("submit","#rfid_scan_add_form",function(e) {
  e.preventDefault();
  $.ajax({
    type: "POST",
    url: $("#rfid_scan_add_form").attr("action"),
    data: $("#rfid_scan_add_form :input").serialize(),
    cache: false,
    dataType: "json",
    success: function(data) {
      $('input[name="rfid"]').val("");
      
      
      if(data.is_valid){
        $("#rfid_scan_add_modal").modal("hide");
        $(".help-block").html("");

        $("#alert-modal").modal("show");
        $("#alert-modal-title").html("scan teacher&apos;s rfid");
        $("#alert-modal-body p").html("You have successfully added the rfid of the teacher.");
        show_teacher_list();
      }else{
        $("#rfid_scan_help-block").html(data.error);
        $("#rfid_valid_date_help-block").html(data.date_error);
      }
    }
  });
});

$(document).on("click",".edit_teacher",function(e) {
    var id = e.target.id;
    show_teacher_data(id);
});



$(document).on("click",".delete_rfid_teacher",function(e) {
  if(confirm("Are you sure you want to remove the rfid of this teacher? This action is irreversible.")){
    var datastr = "id="+e.target.id+"&type=teachers";
    $.ajax({
      type: "POST",
      url: "<?php echo base_url("rfid_ajax/delete"); ?>",
      data: datastr,
      cache: false,
      success: function(data) {
        show_teacher_list();
      }
    });
  }
});


$(document).on("click",".delete_teacher",function(e) {
  var datastr = "id="+e.target.id;
  if(confirm("Are you sure you want to delete this teacher? This acton is irreversible.")){
    $.ajax({
      type: "POST",
      url: "<?php echo base_url("teacher_ajax/delete"); ?>",
      data: datastr,
      cache: false,
      success: function(data) {
        show_teacher_list();
      }
    });
  }
});
function show_teacher_data(id) {
  $.ajax({
    type: "GET",
    url: "<?php echo base_url("teacher_ajax/get_data"); ?>",
    data: "teacher_id="+id,
    cache: false,
    dataType: "json",
    success: function(data) {
      $('input[name="teacher_id"]').val(id);
      $('input[name="first_name"].edit_field').val(data.first_name);
      $('input[name="last_name"].edit_field').val(data.last_name);
      $('input[name="address"].edit_field').val(data.address);
      $('input[name="middle_name"].edit_field').val(data.middle_name);
      $('input[name="suffix"].edit_field').val(data.suffix);
      $('input[name="contact_number"].edit_field').val(data.contact_number);
      $('select[name="bday_m"].edit_field').val(data.bday_m);
      $('select[name="gender"].edit_field').val(data.gender);
      $('select[name="bday_d"].edit_field').val(data.bday_d);
      $('select[name="bday_y"].edit_field').val(data.bday_y);
      $('select[name="guardian_id"].edit_field').val(data.guardian_id);
      if(data.class_id!=""){
        $('#edit-class_id').dropdown('set value',data.class_id);
      }else{
        $('#edit-class_id').dropdown('clear');
      }
      $("#teacher_edit_modal").modal("show");
    }
  });
}
$(document).on("click","#reset",function(e) {
  $(".ui").dropdown("clear");
  show_teacher_list();
});


$(document).on("submit","#teacher_edit_form",function(e) {
	e.preventDefault();
  $('button[form="teacher_edit_form"]').prop('disabled', true);
  $.ajax({
    url: $(this).attr('action'),
    data: new FormData(this),
    processData: false,
    contentType: false,
    method:"POST",
    dataType: "json",
    success: function(data) {
      $('button[form="teacher_edit_form"]').prop('disabled', false);
			$("#first_name_help-block").html(data.first_name_error);
      $("#last_name_help-block").html(data.last_name_error);
      $("#gender_help-block").html(data.gender_error);
			$("#address_help-block").html(data.address_error);
			$("#middle_name_help-block").html(data.middle_name_error);
      $("#suffix_help-block").html(data.suffix_error);
			$("#contact_number_help-block").html(data.contact_number_error);
			$("#bday_help-block").html(data.bday_error);
      $("#guardian_id_help-block").html(data.guardian_id_error);
			$("#teacher_class_id_help-block").html(data.class_id_error);
			$("#teacher_photo_help-block").html(data.teacher_photo_error);
			$("#teacher_id_help-block").html(data.teacher_id_error);
			if(data.is_valid){
				$("#teacher_edit_modal").modal("hide");
				$("#alert-modal").modal("show");
				$("#alert-modal-title").html("Edit teacher Information");
				$("#alert-modal-body p").html("You have successfully editted a teacher's information.");
        show_teacher_list();
			}
		}
	});
});
$(document).on("click",".paging",function(e) {
	show_teacher_list(e.target.id);
});

$("#search_last_name").autocomplete({
  source: "<?php echo base_url("search/teachers/list"); ?>",
  select: function(event, ui){
      $('input[name="owner_id"]').val(ui.item.data);
      show_teacher_list(1,true);
  }
});

$(document).on("click",".reset_password_teacher",function(e) {
  var datastr = "id="+e.target.id;
  if(confirm("Are you sure you want to reset the password of this teacher? This action is irreversible.")){
    $.ajax({
      type: "POST",
      url: "<?php echo base_url("teacher_ajax/reset_password"); ?>",
      data: datastr,
      dataType: "json",
      cache: false,
      success: function(data) {
        
        if(data.is_successful){
          $("#alert-modal-title").html("Reset Password");
          $("#alert-modal-body p").html("You have sent the new password to "+ data.contact_number);
          $("#alert-modal").modal("show");         
        }else{
          $("#alert-modal-title").html("Reset Password");
          $("#alert-modal-body p").html(data.error);
          $("#alert-modal").modal("show");    
        }
 
      }
    });
  }
});


$(document).on("submit","#teacher-list-form",function(e) {
  e.preventDefault();
  $('input[name="owner_id"]').removeAttr('value');
  show_teacher_list();
});


show_teacher_list();
function show_teacher_list(page='1',clear=false) {
  var datastr = $("#teacher-list-form").serialize();
	$.ajax({
		type: "GET",
    url: $("#teacher-list-form").attr("action"),
		data: datastr+"&page="+page,
		cache: false,
		success: function(data) {
      if(clear){
        $("#search_last_name").val("");
      }
			$("#teacher-list-table tbody").html(data);
		}
	});
}
</script>
</body>
</html>