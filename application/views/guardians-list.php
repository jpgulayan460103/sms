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
      <?php echo form_open("tables/guardians/list",'id="guardian-list-form"');?>
      <label>Search Name</label>
      <select class="ui search dropdown" name="id">
        <option value="">Select Guardian's Name</option>
        <?php
          foreach ($guardians_list["result"] as $guardian_data) {
            echo '<option value="'.$guardian_data->id.'">'.$guardian_data->name.'</option>';
          }
        ?>
      </select>
      <button class="btn btn-primary" type="submit">Search</button>
      <button class="btn btn-danger" type="button" id="reset">Reset</button>
      </form>
				<table class="table table-hover" id="guardian-list-table">
					<thead>
						<tr>
							<th>Guardian Name</th>
							<th>Contact Number</th>
              <th>Email Address</th>
              <th style="text-align: center">Email Notification</th>
              <th style="text-align: center">SMS Notification</th>
              <th>Edit</th>
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
  <!-- Edit Guardian Modal -->
  <div id="guardian_edit_modal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">edit A Guardian</h4>
        </div>
        <div class="modal-body">
          <p>'.form_open("guardian_ajax/edit",'id="guardian_edit_form" class="form-horizontal"');
          echo '<input type="hidden" name="guardian_id">';         

          echo '

          <div class="form-group">
            <label class="col-sm-4" for="guardian_name">Guardian Name:</label>
            <div class="col-sm-8"> 
              <input type="text" class="form-control edit_field" name="guardian_name" placeholder="Enter Guardian Name">
              <p class="help-block" id="guardian_name_help-block"></p>
            </div>
          </div>


          <div class="form-group">
            <label class="col-sm-4" for="guardian_name">Guardian&apos;s Address:</label>
            <div class="col-sm-8"> 
              <input type="text" class="form-control edit_field" name="guardian_address" placeholder="Enter Guardian&apos;s Address">
              <p class="help-block" id="guardian_address_help-block"></p>
            </div>
          </div>


          <div class="form-group">
            <label class="col-sm-4" for="email_address">Email Address:</label>
            <div class="col-sm-8"> 
              <input type="text" class="form-control edit_field" name="email_address" placeholder="Enter Email Address">
              <p class="help-block" id="email_address_help-block"></p>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4" for="contact_number">Contact Number:</label>
            <div class="col-sm-8"> 
              <input type="text" class="form-control edit_field" name="contact_number" placeholder="Enter Contact Number">
              <p class="help-block" id="contact_number_help-block"></p>
            </div>
          </div>



          <div class="form-group">
            <label class="col-sm-4" for="email_subscription">Subscription:</label>
            <div class="col-sm-8"> 
              <div class="checkbox">
                <label><input type="checkbox" class="edit_field" name="email_subscription" value="1"> Email Subscription</label>
              </div>
              <div class="checkbox">
                <label><input type="checkbox" class="edit_field" name="sms_subscription" value="1"> SMS Subscription</label>
              </div>
              <p class="help-block" id="subscription_help-block"></p>
            </div>
          </div>
          ';

          echo '</form></p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" form="guardian_edit_form">Submit</button>
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

$(document).on("click",".reset_password_guardian",function(e) {
  var datastr = "id="+e.target.id;
  if(confirm("Are you sure you want to reset the password of this guardian? This action is irreversible.")){
    $.ajax({
      type: "POST",
      url: "<?php echo base_url("guardian_ajax/reset_password"); ?>",
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


$(document).on("click",".delete_guardian",function(e) {
  var datastr = "id="+e.target.id;
  if(confirm("Are you sure you want to delete this guardian? This acton is irreversible.")){
    $.ajax({
      type: "POST",
      url: "<?php echo base_url("guardian_ajax/delete"); ?>",
      data: datastr,
      cache: false,
      success: function(data) {
        show_guardian_list();
      }
    });
  }
});

$(document).on("click","#reset",function(e) {
  $(".ui").dropdown("clear");
  show_guardian_list();
});

$(document).on("click",".edit_guardian",function(e) {
    var id = e.target.id;
    show_guardian_data(id);
});



function show_guardian_data(id) {
  $.ajax({
    type: "GET",
    url: "<?php echo base_url("guardian_ajax/get_data"); ?>",
    data: "guardian_id="+id,
    cache: false,
    dataType: "json",
    success: function(data) {
      // alert(data.email_subscription);
      $('input[name="guardian_id"]').val(id);
      $('input[name="guardian_name"].edit_field').val(data.name);
      $('input[name="email_address"].edit_field').val(data.email_address);
      $('input[name="contact_number"].edit_field').val(data.contact_number);
      $('input[name="guardian_address"].edit_field').val(data.guardian_address);
      if(data.email_subscription=="1"){
        $('input[name="email_subscription"].edit_field').attr('checked', true);
      }
      if(data.sms_subscription=="1"){
        $('input[name="sms_subscription"].edit_field').attr('checked', true);
      }
      
      $("#guardian_edit_modal").modal("show");
    }
  });
}

$(document).on("submit","#guardian_edit_form",function(e) {
  e.preventDefault();
  $('button[form="guardian_edit_form"]').prop('disabled', true);
  $.ajax({
    url: $(this).attr('action'),
    data: new FormData(this),
    processData: false,
    contentType: false,
    method:"POST",
    dataType: "json",
    success: function(data) {
      $('button[form="guardian_edit_form"]').prop('disabled', false);
      $("#guardian_id_help-block").html(data.guardian_id_error);
      $("#guardian_name_help-block").html(data.guardian_name_error);
      $("#guardian_address_help-block").html(data.guardian_address_error);
      $("#email_address_help-block").html(data.email_address_error);
      $("#contact_number_help-block").html(data.contact_number_error);
			$("#subscription_help-block").html(data.subscription_error);
			if(data.is_valid){
				$("#guardian_edit_modal").modal("hide");
				$("#alert-modal").modal("show");
				$("#alert-modal-title").html("Edit guardian Information");
				$("#alert-modal-body p").html("You have successfully editted a guardian's information.");
        show_guardian_list();
			}
		}
	});
});
$(document).on("click",".paging",function(e) {
	show_guardian_list(e.target.id);
});

$("#search_last_name").autocomplete({
  source: "<?php echo base_url("search/guardians/list"); ?>",
  select: function(event, ui){
      show_guardian_data(ui.item.data);
      $("#search_last_name").val("");
      // alert(data);
    // window.location='item?s='+ui.item.data;
  }
});
$(document).on("submit","#guardian-list-form",function(e) {
  e.preventDefault();
  show_guardian_list();  
});


show_guardian_list();
function show_guardian_list(page='1') {
  var datastr = $("#guardian-list-form").serialize();
	$.ajax({
		type: "GET",
    url: $("#guardian-list-form").attr("action"),
		data: datastr+"&page="+page,
		cache: false,
		success: function(data) {
			$("#guardian-list-table tbody").html(data);
		}
	});
}
</script>
</body>
</html>