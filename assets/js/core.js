var base_url = "//localhost/rfid/";
$(document).on("submit", "#rfid_scan_add_load_credit_form", function(e) {
  e.preventDefault();
  $.ajax({
    type: "POST",
    url: $("#rfid_scan_add_load_credit_form").attr("action"),
    data: $("#rfid_scan_add_load_credit_form :input").serialize(),
    cache: false,
    dataType: "json",
    success: function(data) {
      $("#rfid_scan_add_load_credit_form")[0].reset();
      if (data.is_valid) {
        $("#rfid_scan_add_load_credits_modal").modal("hide");
        $('input[name="rfid_id"]').val(data.rfid_data.id);
        $("#add_load_credits_display-photo").attr("src", data.display_photo);
        $("#add_load_credits_full_name").html(data.full_name);
        $("#add_load_credits_remaining_load").html(data.rfid_data.load_credits);
        $("#add_load_credits_data_modal").modal("show");
        $("#rfid_scan_add_load_credits_form")[0].reset();
        $(".help-block").html("");
      } else {
        $('#rfid_scan_add_load_credits_help-block').html("RFID is invalid or available.");
      }
    }
  });
});
$(document).on("click", "#rfid_add_load_credits", function(e) {
  $("#rfid_scan_add_load_credits_modal").modal("show");
});
$(document).on("submit", "#add_load_credits_form", function(e) {
  e.preventDefault();
  $.ajax({
    type: "POST",
    url: $("#add_load_credits_form").attr("action"),
    data: $("#add_load_credits_form").serialize(),
    cache: false,
    dataType: "json",
    success: function(data) {
      $("#add_load_credits_form")[0].reset();
      $("#add_load_credits_data_modal").modal("hide");
      $("#alert-modal").modal("show");
      $("#alert-modal-title").html("Add Load to a Student");
      $("#alert-modal-body p").html("You have successfully added a load a student's load credits.");
    }
  });
});
$(document).on("click", "#register_guardian, #add_guardian", function(e) {
  $("#register_guardian_modal").modal("show");
});
$(document).on("click", ".change_password", function(e) {
  var type = e.target.id;
  $("#change_password_type").val(type);
  $("#change_password-modal").modal("show");
});
$(document).on("submit", "#change_password-form", function(e) {
  e.preventDefault();
  $('button[form="change_password-form"]').prop('disabled', true);
  $.ajax({
    type: "POST",
    url: $("#change_password-form").attr("action"),
    data: $("#change_password-form").serialize(),
    dataType: "json",
    cache: false,
    success: function(data) {
      $('button[form="change_password-form"]').prop('disabled', false);
      if (data.is_valid) {
        $(".help-block").html("");
        $("#alert-modal").modal("show");
        $("#change_password-modal").modal("hide");
        $("#alert-modal-title").html("change password");
        $("#alert-modal-body p").html("You have successfully changed your password.");
      } else {
        $("#current_password_help-block").html(data.current_password_error);
        $("#new_password_help-block").html(data.new_password_error);
        $("#confirm_password_help-block").html(data.confirm_password_error);
      }
    }
  });
});
$(document).on("submit", "#register_guardian_form", function(e) {
  e.preventDefault();
  $("button[form='register_guardian_form']").prop('disabled', true);
  $.ajax({
    type: "POST",
    url: $("#register_guardian_form").attr("action"),
    data: $("#register_guardian_form").serialize(),
    cache: false,
    dataType: "json",
    success: function(data) {
      $("button[form='register_guardian_form']").prop('disabled', false);
      $("#add_guardian_address_help-block").html(data.guardian_address_error);
      $("#add_guardian_name_help-block").html(data.guardian_name_error);
      $("#add_email_address_help-block").html(data.email_address_error);
      $("#add_contact_number_help-block").html(data.contact_number_error);
      $("#add_subscription_help-block").html(data.subscription_error);
      if (data.is_valid) {
        $("#register_guardian_form")[0].reset();
        $('.ui.dropdown').dropdown('clear');
        $(".help-block").html("");
        $("#alert-modal").modal("show");
        $("#register_guardian_modal").modal("hide");
        $("#alert-modal-title").html("Add Guardian");
        $("#alert-modal-body p").html("You have successfully registered a guardian.");
        update_select_options("guardian_id", base_url);
      } else {
        $("#add_guardian_name_help-block").html(data.guardian_name_error);
        $("#add_email_address_help-block").html(data.email_address_error);
        $("#add_contact_number_help-block").html(data.contact_number_error);
      }
    }
  });
});
$(".rfid_scan_add#students").click(function(e) {
  $("#students_add_modal").modal("show");
});
$(".rfid_scan_add#teachers").click(function(e) {
  $("#teachers_add_modal").modal("show");
});
$(".rfid_scan_add#staffs").click(function(e) {
  $("#staffs_add_modal").modal("show");
});
$(document).on("submit", "#student_add_form", function(e) {
  e.preventDefault();
  $("button[form='student_add_form']").prop('disabled', true);
  $.ajax({
    url: $(this).attr('action'),
    data: new FormData(this),
    processData: false,
    contentType: false,
    method: "POST",
    dataType: "json",
    success: function(data) {
      $("button[form='student_add_form']").prop('disabled', false);
      if (data.is_valid) {
        $("#student_add_form")[0].reset();
        $('.ui.dropdown').dropdown('clear');
        $(".help-block").html("");
        if (data.is_successful) {
          $("#students_add_modal").modal("hide");
          $("#alert-modal").modal("show");
          $("#alert-modal-title").html("Add Student");
          $("#alert-modal-body p").html("You have successfully added a student in the list.");
        }
      } else {
        $("#student_first_name_help-block").html(data.first_name_error);
        $("#student_gender_help-block").html(data.gender_error);
        $("#student_mothers_name_help-block").html(data.mothers_name_error);
        $("#student_fathers_name_help-block").html(data.fathers_name_error);
        $("#student_address_help-block").html(data.address_error);
        $("#student_last_name_help-block").html(data.last_name_error);
        $("#student_middle_name_help-block").html(data.middle_name_error);
        $("#student_suffix_help-block").html(data.suffix_error);
        $("#student_contact_number_help-block").html(data.contact_number_error);
        $("#student_bday_help-block").html(data.bday_error);
        $("#student_guardian_id_help-block").html(data.guardian_id_error);
        $("#student_class_id_help-block").html(data.class_id_error);
        $("#student_photo_help-block").html(data.photo_error);
      }
    }
  })
});
$(document).on("submit", "#teacher_add_form", function(e) {
  e.preventDefault();
  $("button[form='teacher_add_form']").prop('disabled', true);
  $.ajax({
    url: $(this).attr('action'),
    data: new FormData(this),
    processData: false,
    contentType: false,
    method: "POST",
    dataType: "json",
    success: function(data) {
      $("button[form='teacher_add_form']").prop('disabled', false);
      if (data.is_valid) {
        $("#teacher_add_form")[0].reset();
        $('.ui.dropdown').dropdown('clear');
        $(".help-block").html("");
        if (data.is_successful) {
          $("#teachers_add_modal").modal("hide");
          $("#alert-modal").modal("show");
          $("#alert-modal-title").html("Add teacher");
          $("#alert-modal-body p").html("You have successfully added a teacher in the list.");
          update_select_options("class_adviser", base_url);
        }
      } else {
        $("#teacher_address_help-block").html(data.address_error);
        $("#teacher_gender_help-block").html(data.gender_error);
        $("#teacher_first_name_help-block").html(data.first_name_error);
        $("#teacher_last_name_help-block").html(data.last_name_error);
        $("#teacher_middle_name_help-block").html(data.middle_name_error);
        $("#teacher_suffix_help-block").html(data.suffix_error);
        $("#teacher_contact_number_help-block").html(data.contact_number_error);
        $("#teacher_bday_help-block").html(data.bday_error);
        $("#teacher_guardian_id_help-block").html(data.guardian_id_error);
        $("#teacher_class_id_help-block").html(data.class_id_error);
        $("#teacher_photo_help-block").html(data.photo_error);
      }
    }
  })
});
$(document).on("submit", "#staff_add_form", function(e) {
  e.preventDefault();
  $("button[form='staff_add_form']").prop('disabled', true);
  $.ajax({
    url: $(this).attr('action'),
    data: new FormData(this),
    processData: false,
    contentType: false,
    method: "POST",
    dataType: "json",
    success: function(data) {
      $("button[form='staff_add_form']").prop('disabled', false);
      if (data.is_valid) {
        $("#staff_add_form")[0].reset();
        $('.ui.dropdown').dropdown('clear');
        $(".help-block").html("");
        if (data.is_successful) {
          $("#staffs_add_modal").modal("hide");
          $("#alert-modal").modal("show");
          $("#alert-modal-title").html("Add staff");
          $("#alert-modal-body p").html("You have successfully added a staff in the list.");
          update_select_options("class_adviser", base_url);
        }
      } else {
        $("#staff_gender_help-block").html(data.gender_error);
        $("#staff_address_help-block").html(data.address_error);
        $("#staff_position_help-block").html(data.position_error);
        $("#staff_first_name_help-block").html(data.first_name_error);
        $("#staff_last_name_help-block").html(data.last_name_error);
        $("#staff_middle_name_help-block").html(data.middle_name_error);
        $("#staff_suffix_help-block").html(data.suffix_error);
        $("#staff_contact_number_help-block").html(data.contact_number_error);
        $("#staff_bday_help-block").html(data.bday_error);
        $("#staff_guardian_id_help-block").html(data.guardian_id_error);
        $("#staff_class_id_help-block").html(data.class_id_error);
        $("#staff_photo_help-block").html(data.photo_error);
      }
    }
  })
});
$(document).on("submit", "#guard_add_form", function(e) {
  e.preventDefault();
  $("button[form='guard_add_form']").prop('disabled', true);
  $.ajax({
    url: $(this).attr('action'),
    data: new FormData(this),
    processData: false,
    contentType: false,
    method: "POST",
    dataType: "json",
    success: function(data) {
      $("button[form='guard_add_form']").prop('disabled', false);
      if (data.is_valid) {
        $("#guard_add_form")[0].reset();
        $('.ui.dropdown').dropdown('clear');
        $(".help-block").html("");
        if (data.is_successful) {
          $("#guards_add_modal").modal("hide");
          $("#alert-modal").modal("show");
          $("#alert-modal-title").html("Add guard");
          $("#alert-modal-body p").html("You have successfully added a guard in the list.");
        }
      } else {
        $("#guard_first_name_help-block").html(data.first_name_error);
        $("#guard_last_name_help-block").html(data.last_name_error);
        $("#guard_middle_name_help-block").html(data.middle_name_error);
        $("#guard_suffix_help-block").html(data.suffix_error);
        $("#guard_bday_help-block").html(data.bday_error);
        $("#guard_guardian_id_help-block").html(data.guardian_id_error);
        $("#guard_photo_help-block").html(data.photo_error);
      }
    }
  })
});
$(document).on("submit", "#app-login", function(e) {
  e.preventDefault();
  $('button[form="app-login"]').prop('disabled', true);
  $.ajax({
    type: "POST",
    url: $("#app-login").attr("action"),
    data: $("#app-login").serialize(),
    cache: false,
    dataType: "json",
    success: function(data) {
      $('button[form="app-login"]').prop('disabled', false);
      if (data.is_valid) {
        window.location = data.redirect;
      } else {
        $("#account_help-block").html(data.account_error);
        $("#account_password_help-block").html(data.account_password_error);
      }
    }
  });
});
$(document).on("click", "#add_canteen", function(e) {
  $("#add_canteen_modal").modal("show");
});
$(document).on("submit", "#add_canteen_form", function(e) {
  e.preventDefault();
  $('button[form="add_canteen_form"]').prop('disabled', true);
  $.ajax({
    type: "POST",
    url: $("#add_canteen_form").attr("action"),
    data: $("#add_canteen_form").serialize(),
    cache: false,
    dataType: "json",
    success: function(data) {
      $('button[form="add_canteen_form"]').prop('disabled', false);
      if (data.is_valid) {}
    }
  });
});
$(document).on("click", "#class_add", function(e) {
  $("#class_add_modal").modal("show");
});
$(document).on("submit", "#class_add_form", function(e) {
  e.preventDefault();
  $('button[form="class_add_form"]').prop('disabled', true);
  $.ajax({
    type: "POST",
    url: $("#class_add_form").attr("action"),
    data: $("#class_add_form").serialize(),
    cache: false,
    dataType: "json",
    success: function(data) {
      if (data.is_valid) {
        $("#class_add_form")[0].reset();
        $('.ui.dropdown').dropdown('clear');
        $("#alert-modal-title").html("Add Class");
        $("#alert-modal-body p").html("You have successfully added a class in the list.");
        $("#alert-modal").modal("show");
        $(".help-block").html("");
        update_select_options("class_id", base_url);
      } else {
        $("#class_adviser_help-block").html(data.class_adviser_error);
        $("#class_name_help-block").html(data.class_name_error);
        $("#grade_help-block").html(data.grade_error);
        $("#class_room_help-block").html(data.class_room_error);
        $("#class_schedule_help-block").html(data.class_schedule_error);
      }
      $('button[form="class_add_form"]').prop('disabled', false);
    }
  });
});
$("#send-sms-admin").on("click", function(e) {
  $("#sms-modal").modal("show");
});
$("#send-sms-teacher").on("click", function(e) {
  $("#sms-modal-teacher").modal("show");
});
$("#add-class").on("click", function(e) {
  $("#class_add_modal").modal("show");
});
$("#datepicker_from,#datepicker_to").datepicker();
$('.ui.dropdown').dropdown({
  forceSelection: false
});
$(document).on("change", 'select[name="type_recipient"]', function(e) {
  if (e.target.value == "all_teachers" || e.target.value == "all_teachers_students" || e.target.value ==
    "all_students" || e.target.value == "all_members" || e.target.value == "all_guardians") {
    $("#send-to-container").css("display", "none");
  } else {
    $("#send-to-container").css("display", "block");
  }
});
$(document).on("click", ".email_settings", function(e) {
  $("#email_settings-modal").modal("show");
});
$(document).on("submit", "#guardian_email_settings_form", function(e) {
  e.preventDefault();
  $('button[form="guardian_email_settings_form"]').prop('disabled', true);
  $.ajax({
    type: "POST",
    url: $("#guardian_email_settings_form").attr("action"),
    data: $("#guardian_email_settings_form").serialize(),
    cache: false,
    dataType: "json",
    success: function(data) {
      $('button[form="guardian_email_settings_form"]').prop('disabled', false);
      if (data.is_valid) {
        $(".help-block").html("");
        $("#email_settings-modal").modal("hide");
        $("#alert-modal-title").html("email settings");
        $("#alert-modal-body p").html("You have successfully changed your email settings.");
        $("#alert-modal").modal("show");
      } else {
        $("#email_settings_email_address_help-block").html(data.email_address_error);
      }
    }
  });
});
$(document).on("click", "#gate_change_password", function(e) {
  $("#gate_change_password-modal").modal("show");
});
$(document).on("submit", "#gate_change_password-form", function(e) {
  $('button[form="gate_change_password-form"]').prop('disabled', true);
  e.preventDefault();
  $.ajax({
    type: "POST",
    url: $("#gate_change_password-form").attr("action"),
    data: $("#gate_change_password-form").serialize(),
    cache: false,
    dataType: "json",
    success: function(data) {
      $('button[form="gate_change_password-form"]').prop('disabled', false);
      if (data.is_valid) {
        $("#gate_change_password-form")[0].reset();
        $(".help-block").html("");
        $("#alert-modal").modal("show");
        $("#change_password-modal").modal("hide");
        $("#alert-modal-title").html("change password");
        $("#alert-modal-body p").html("You have successfully changed your password.");
      } else {
        $("#gate_current_password_help-block").html(data.current_password_error);
        $("#gate_new_password_help-block").html(data.new_password_error);
        $("#gate_confirm_password_help-block").html(data.confirm_password_error);
      }
    }
  });
});
$(document).on("click", "#reset_admin_password", function(e) {
  $("#reset_admin_password-modal").modal("show");
  $('#select_admin_username').dropdown("clear");
  $('#select_admin_username').html("");
  $('#select_admin_username').append('<option value="">Select a Class</option>');
  $.ajax({
    type: "GET",
    url: base_url + "admin_ajax/get_list",
    data: "get=1",
    cache: false,
    dataType: "json",
    success: function(data) {
      $.each(data, function(i, item) {
        $('#select_admin_username').append('<option value="' + data[i].id + '">' + data[i].username +
          '</option>');
      });
    }
  });
});
$(document).on("submit", "#add_admin-form", function(e) {
  e.preventDefault();
  $('button[form="add_admin-form"]').prop('disabled', true);
  $.ajax({
    type: "POST",
    data: $("#add_admin-form").serialize(),
    url: $("#add_admin-form").attr("action"),
    cache: false,
    dataType: "json",
    success: function(data) {
      $('button[form="add_admin-form"]').prop('disabled', false);
      if (data.is_valid) {
        $(".help-block").html("");
        $("#alert-modal").modal("show");
        $("#add_admin-modal").modal("hide");
        $("#alert-modal-title").html("add admin account");
        $("#alert-modal-body p").html("You have successfully changed your password.");
      } else {
        $("#add_admin_username_help-block").html(data.username_error);
        $("#add_admin_email_address_help-block").html(data.email_address_error);
      }
    }
  });
});
$(document).on("submit", "#reset_admin_password-form", function(e) {
  e.preventDefault();
  $('button[form="reset_admin_password-form"]').prop('disabled', true);
  $.ajax({
    type: "POST",
    url: $("#reset_admin_password-form").attr("action"),
    data: $("#reset_admin_password-form").serialize(),
    cache: false,
    dataType: "json",
    success: function(data) {
      $('button[form="reset_admin_password-form"]').prop('disabled', false);
      if (data.is_valid) {
        $("#reset_admin_password_help-block").html(data.email_address_error);
        $(".help-block").html("");
        $("#reset_admin_password-modal").modal("hide");
        $("#alert-modal").modal("show");
        $("#alert-modal-title").html("admin password reset");
        $("#alert-modal-body p").html("The new admin password has been sent to");
      } else {
        $("#reset_admin_password_help-block").html(data.email_address_error);
      }
    }
  });
});
$(document).on("click", "#add_admin", function(e) {
  $("#add_admin-modal").modal("show");
});
var needToConfirm = false;
$(document).on("submit", "#sms-form", function(e) {
  e.preventDefault();
  needToConfirm = true;
  $('button[form="sms-form"]').prop('disabled', true);
  $('button[form="sms-form"]').html("Sending...");
  $('.loading').css("display", "initial");
  $.ajax({
    type: "POST",
    url: $("#sms-form").attr("action"),
    data: $("#sms-form").serialize(),
    cache: false,
    dataType: "json",
    success: function(data) {
      needToConfirm = false;
      $('.loading').css("display", "none");
      $('button[form="sms-form"]').html("Submit");
      $('button[form="sms-form"]').prop('disabled', false);
      if (data.is_valid) {
        $("#sms-form")[0].reset();
        $('.ui.dropdown').dropdown('clear');
        $(".help-block").html("");
        $("#sms-modal").modal("hide");
        $("#sms-modal-teacher").modal("hide");
        $("#sms-list-modal").modal("show");
        $("#message_id_txt").html(data.sms_data.id);
        $('.sms_list_table tbody').html("");
        $.each(data.sms_list, function(i, item) {
          $('.sms_list_table tbody').append('\
          	<tr>\
          	<td>' + data.sms_list[i].message + '</td>\
          	<td>' + data.sms_list[i].mobile_number + '</td>\
          	<td>' + data.sms_list[i].recipient + '</td>\
          	<td>' + data.sms_list[i].status + '</td>\
          	</tr>\
          	');
        });
      } else {
        $("#type_recipient_help-block").html(data.type_recipient_error);
        $("#message_help-block").html(data.message_error);
        $("#class_id_help-block").html(data.class_id_error);
      }
    }
  });
});
window.onbeforeunload = confirmExit;

function confirmExit() {
  if (needToConfirm) return "sdasdasdasd";
}
update_select_options("guardian_id", base_url);
update_select_options("class_adviser", base_url);
update_select_options("class_id[]", base_url);
update_select_options("class_id", base_url);

function update_select_options(type, base_url) {
  if (type == "guardian_id") {
    $('select[name="' + type + '"]').html("");
    $('select[name="' + type + '"]').append('<option value="">Select a Guardians Email</option>');
    $.ajax({
      type: "GET",
      url: base_url + "guardian_ajax/get_list",
      cache: false,
      dataType: "json",
      success: function(data) {
        $.each(data, function(i, item) {
          $('select[name="' + type + '"]').append('<option value="' + data[i].id + '">' + data[i].contact_number +
            '</option>');
        })
      }
    });
  } else if (type == "class_adviser") {
    $('select[name="' + type + '"]').html("");
    $('select[name="' + type + '"]').append('<option value="">Select a Class Adviser</option>');
    $.ajax({
      type: "GET",
      url: base_url + "teacher_ajax/get_list/admin",
      cache: false,
      dataType: "json",
      success: function(data) {
        $.each(data, function(i, item) {
          $('select[name="' + type + '"]').append('<option value="' + data[i].id + '">' + data[i].full_name +
            '</option>');
        })
      }
    });
  } else if (type == "class_id[]" || type == "class_id") {
    $('select[name="' + type + '"]').html("");
    $('select[name="' + type + '"]').append('<option value="">Select a Class</option>');
    $.ajax({
      type: "GET",
      url: base_url + "class_ajax/get_list",
      cache: false,
      dataType: "json",
      success: function(data) {
        $.each(data, function(i, item) {
          $('select[name="' + type + '"]').append('<option value="' + data[i].id + '">' + data[i].class_name +
            '</option>');
        })
      }
    });
  }
}
