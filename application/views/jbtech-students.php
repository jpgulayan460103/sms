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
    <?php echo form_open("tables/students/list/jbtech",'id="student-list-form"');?>
    <label>Class:</label>
    <?php
    echo '
    <select class="ui search dropdown" id="select_class" name="class_id">
      <option value="">All Class</option>
      ';
      foreach ($classes_list["result"] as $class_data) {
        echo '<option value="'.$class_data->id.'">'.$class_data->class_name.'</option>';
      }

      echo '
    </select>
    ';
    ?>
    <label>Search Last Name</label>
    <select class="ui search dropdown" name="owner_id" id="select_student">
      <option value="">Select Student's Last Name</option>
    </select>

    <button class="btn btn-primary" type="submit">Search</button>
    <button class="btn btn-danger" type="button" id="reset">Reset</button>
    </form>

			<table class="table table-hover" id="student-list-table">
			  <thead>
			    <tr>
			      <th>First Name</th>
			      <th>Middle Name</th>
			      <th>Last Name</th>
			      <th>Birthday</th>
			      <th>Guardian</th>
			      <th>Contact Number</th>
			      <th>Class</th>
			      <th></th>
			    </tr>
			  </thead>
			  <tbody>

			  </tbody>
			</table>
		</div>
	</div>

</div>
<?php echo $modaljs_scripts;
echo '

<!--Edit Student Modal -->
<div id="student_edit_modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Student Data</h4>
      </div>
      <div class="modal-body">
        <p>
        <form class="form-horizontal">

          <div class="form-group">
              <label class="col-sm-4">ID Number</label>
              <div class="input-group col-sm-7">
                <input id="id" type="text" class="form-control" name="id" readonly>
                <span class="input-group-addon btn btn-default" data-clipboard-target="#id" data-balloon="Copy to clipboard" data-balloon-pos="down"><i class="fa fa-files-o" aria-hidden="true"></i></span>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-4">full_name</label>
              <div class="input-group col-sm-7">
                <input id="full_name" type="text" class="form-control" name="full_name" readonly>
                <span class="input-group-addon btn btn-default" data-clipboard-target="#full_name" data-balloon="Copy to clipboard" data-balloon-pos="down"><i class="fa fa-files-o" aria-hidden="true"></i></span>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-4">last_name</label>
              <div class="input-group col-sm-7">
                <input id="last_name" type="text" class="form-control" name="last_name" readonly>
                <span class="input-group-addon btn btn-default" data-clipboard-target="#last_name" data-balloon="Copy to clipboard" data-balloon-pos="down"><i class="fa fa-files-o" aria-hidden="true"></i></span>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-4">first_name</label>
              <div class="input-group col-sm-7">
                <input id="first_name" type="text" class="form-control" name="first_name" readonly>
                <span class="input-group-addon btn btn-default" data-clipboard-target="#first_name" data-balloon="Copy to clipboard" data-balloon-pos="down"><i class="fa fa-files-o" aria-hidden="true"></i></span>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-4">middle_name</label>
              <div class="input-group col-sm-7">
                <input id="middle_name" type="text" class="form-control" name="middle_name" readonly>
                <span class="input-group-addon btn btn-default" data-clipboard-target="#middle_name" data-balloon="Copy to clipboard" data-balloon-pos="down"><i class="fa fa-files-o" aria-hidden="true"></i></span>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-4">suffix</label>
              <div class="input-group col-sm-7">
                <input id="suffix" type="text" class="form-control" name="suffix" readonly>
                <span class="input-group-addon btn btn-default" data-clipboard-target="#suffix" data-balloon="Copy to clipboard" data-balloon-pos="down"><i class="fa fa-files-o" aria-hidden="true"></i></span>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-4">gender</label>
              <div class="input-group col-sm-7">
                <input id="gender" type="text" class="form-control" name="gender" readonly>
                <span class="input-group-addon btn btn-default" data-clipboard-target="#gender" data-balloon="Copy to clipboard" data-balloon-pos="down"><i class="fa fa-files-o" aria-hidden="true"></i></span>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-4">birthday</label>
              <div class="input-group col-sm-7">
                <input id="birthday" type="text" class="form-control" name="birthday" readonly>
                <span class="input-group-addon btn btn-default" data-clipboard-target="#birthday" data-balloon="Copy to clipboard" data-balloon-pos="down"><i class="fa fa-files-o" aria-hidden="true"></i></span>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-4">age</label>
              <div class="input-group col-sm-7">
                <input id="age" type="text" class="form-control" name="age" readonly>
                <span class="input-group-addon btn btn-default" data-clipboard-target="#age" data-balloon="Copy to clipboard" data-balloon-pos="down"><i class="fa fa-files-o" aria-hidden="true"></i></span>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-4">contact_number</label>
              <div class="input-group col-sm-7">
                <input id="contact_number" type="text" class="form-control" name="contact_number" readonly>
                <span class="input-group-addon btn btn-default" data-clipboard-target="#contact_number" data-balloon="Copy to clipboard" data-balloon-pos="down"><i class="fa fa-files-o" aria-hidden="true"></i></span>
              </div>
          </div>
          <div class="form-group">
              <label class="col-sm-4">address</label>
              <div class="input-group col-sm-7">
                <input id="address" type="text" class="form-control" name="address" readonly>
                <span class="input-group-addon btn btn-default" data-clipboard-target="#address" data-balloon="Copy to clipboard" data-balloon-pos="down"><i class="fa fa-files-o" aria-hidden="true"></i></span>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-4">fathers_name</label>
              <div class="input-group col-sm-7">
                <input id="fathers_name" type="text" class="form-control" name="fathers_name" readonly>
                <span class="input-group-addon btn btn-default" data-clipboard-target="#fathers_name" data-balloon="Copy to clipboard" data-balloon-pos="down"><i class="fa fa-files-o" aria-hidden="true"></i></span>
              </div>
          </div>
          <div class="form-group">
              <label class="col-sm-4">mothers_name</label>
              <div class="input-group col-sm-7">
                <input id="mothers_name" type="text" class="form-control" name="mothers_name" readonly>
                <span class="input-group-addon btn btn-default" data-clipboard-target="#mothers_name" data-balloon="Copy to clipboard" data-balloon-pos="down"><i class="fa fa-files-o" aria-hidden="true"></i></span>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-4">guardian_name</label>
              <div class="input-group col-sm-7">
                <input id="guardian_name" type="text" class="form-control" name="guardian_name" readonly>
                <span class="input-group-addon btn btn-default" data-clipboard-target="#guardian_name" data-balloon="Copy to clipboard" data-balloon-pos="down"><i class="fa fa-files-o" aria-hidden="true"></i></span>
              </div>
          </div>
          <div class="form-group">
              <label class="col-sm-4">guardian_address</label>
              <div class="input-group col-sm-7">
                <input id="guardian_address" type="text" class="form-control" name="guardian_address" readonly>
                <span class="input-group-addon btn btn-default" data-clipboard-target="#guardian_address" data-balloon="Copy to clipboard" data-balloon-pos="down"><i class="fa fa-files-o" aria-hidden="true"></i></span>
              </div>
          </div>
          <div class="form-group">
              <label class="col-sm-4">guardian_contact_number</label>
              <div class="input-group col-sm-7">
                <input id="guardian_contact_number" type="text" class="form-control" name="guardian_contact_number" readonly>
                <span class="input-group-addon btn btn-default" data-clipboard-target="#guardian_contact_number" data-balloon="Copy to clipboard" data-balloon-pos="down"><i class="fa fa-files-o" aria-hidden="true"></i></span>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-4">class_name</label>
              <div class="input-group col-sm-7">
                <input id="class_name" type="text" class="form-control" name="class_name" readonly>
                <span class="input-group-addon btn btn-default" data-clipboard-target="#class_name" data-balloon="Copy to clipboard" data-balloon-pos="down"><i class="fa fa-files-o" aria-hidden="true"></i></span>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-4">grade</label>
              <div class="input-group col-sm-7">
                <input id="grade" type="text" class="form-control" name="grade" readonly>
                <span class="input-group-addon btn btn-default" data-clipboard-target="#grade" data-balloon="Copy to clipboard" data-balloon-pos="down"><i class="fa fa-files-o" aria-hidden="true"></i></span>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-4">class_adviser</label>
              <div class="input-group col-sm-7">
                <input id="class_adviser" type="text" class="form-control" name="class_adviser" readonly>
                <span class="input-group-addon btn btn-default" data-clipboard-target="#class_adviser" data-balloon="Copy to clipboard" data-balloon-pos="down"><i class="fa fa-files-o" aria-hidden="true"></i></span>
              </div>
          </div>




        </form>
        </p>
      </div>
      <div class="modal-footer">
      <p class="help-block"></p>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

';

?>





<?php echo $js_scripts; ?>
<script>
var clipboard = new Clipboard('.btn');

clipboard.on('success', function(e) {
    console.info('Action:', e.action);
    console.info('Text:', e.text);
    console.info('Trigger:', e.trigger);

    e.clearSelection();
});

clipboard.on('error', function(e) {
    console.error('Action:', e.action);
    console.error('Trigger:', e.trigger);
});


$(document).on("click",".paging",function(e) {
	show_student_list(e.target.id);
});

$("#search_last_name").autocomplete({
  source: "<?php echo base_url("search/students/list"); ?>",
  select: function(event, ui){
      $('input[name="owner_id"]').val(ui.item.data);
      show_student_list(1,true);
  }
});

$(document).on("submit","#student-list-form",function(e) {
  e.preventDefault();
  $('input[name="owner_id"]').removeAttr('value');
  show_student_list();
});

$(document).on("change",'input[name="has_rfid"]',function(e) {
	show_student_list();
});

$(document).on("click",".view_student",function(e) {
  var id = e.target.id;
  show_student_data(id);	
});


$(document).on("change",'#select_class',function(e) {
  // show_gatelogs();
  $('#select_student').dropdown("clear");
  $('#select_student').html("");
  $('#select_student').append('<option value="">Select a Class</option>');
  var datastr = "class_id="+e.target.value;
  $.ajax({
    type: "GET",
    url: "<?php echo base_url("student_ajax/get_list/jbtech"); ?>",
    data: datastr,
    cache: false,
    dataType: "json",
    success: function(data) {
      $.each(data, function(i, item) {
          $('#select_student').append('<option value="'+data[i].id+'">'+data[i].full_name+'</option>');
      });
    }
  });
});


$('#select_student').dropdown("clear");
$('#select_student').html("");
$('#select_student').append('<option value="">Select a Class</option>');
$.ajax({
  type: "GET",
  url: "<?php echo base_url("student_ajax/get_list/jbtech"); ?>",
  data: "get=1",
  cache: false,
  dataType: "json",
  success: function(data) {
    $.each(data, function(i, item) {
        $('#select_student').append('<option value="'+data[i].id+'">'+data[i].full_name+'</option>');
    });
  }
});

$(document).on("click","#reset",function(e) {
  $(".ui").dropdown("clear");
  show_student_list();
});


show_student_list();
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

function show_student_data(id) {
  $.ajax({
    type: "GET",
    url: "<?php echo base_url("student_ajax/get_data/jbtech"); ?>",
    data: "student_id="+id,
    cache: false,
    dataType: "json",
    success: function(data) {
      console.log(data);
      $('#id').val(data.id);
      $('#last_name').val(data.last_name);
      $('#age').val(data.age);
      $('#full_name').val(data.full_name);
      $('#first_name').val(data.first_name);
      $('#middle_name').val(data.middle_name);
      $('#suffix').val(data.suffix);
      $('#gender').val(data.gender);
      $('#birthday').val(data.birthday);
      $('#contact_number').val(data.contact_number);
      $('#address').val(data.address);
      $('#guardian_name').val(data.guardian_name);
      $('#guardian_address').val(data.guardian_address);
      $('#guardian_contact_number').val(data.guardian_contact_number);
      $('#fathers_name').val(data.fathers_name);
      $('#mothers_name').val(data.mothers_name);
      $('#class_name').val(data.class_name);
      $('#grade').val(data.grade);
      $('#class_adviser').val(data.class_adviser);
      if(data.guardian_id!=""){
        $('#edit-guardian_id').dropdown('set value',data.guardian_id);
      }else{
        $('#edit-guardian_id').dropdown('clear');
      }
      if(data.class_id!=""){
        $('#edit-class_id').dropdown('set value',data.class_id);
      }else{
        $('#edit-class_id').dropdown('clear');
      }
      $("#student_edit_modal").modal("show");
    }
  });
}




</script>
</body>
</html>