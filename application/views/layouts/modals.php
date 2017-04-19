

<?php

if($modals_sets=="admin"){



  echo '
  <!--RFID Scan to Add Student Modal -->
  <div id="rfid_scan_add_modal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-sm">

      <!-- Modal content-->
      <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="rfid_add_modal_title">Add studentsss</h4>
      </div>
        <div class="modal-body">
          <p>
          '.form_open("rfid_ajax/scan_add",'id="rfid_scan_add_form"').'
          <input type="hidden" name="type">
          <input type="hidden" name="id">

            <div class="form-group">

              <label for="rfid"></label>
              <div class="col-sm-12">
                <input type="text" class="form-control" name="rfid" placeholder="Scan RFID using RFID Reader..." autocomplete="off">
                <p class="help-block" id="rfid_scan_help-block"></p>
              </div>

            </div>

            <div class="form-group">

            <label class="col-sm-12" for="last_name">Valid Until:</label>

            </div>

             <div class="form-group">
              
              <div class="col-sm-12">
                <select class="" name="valid_m" required>
                  <option value="">MM</option>
                  ';
                  for ($i=1; $i <= 12; $i++) { 
                    echo '<option value="'.$i.'">'.sprintf("%02d",$i).'</option>';
                  }
                  echo '
                </select>
                /
                <select class="" name="valid_d" required>
                  <option value="">DD</option>
                  ';
                  for ($i=1; $i <= 31; $i++) { 
                    echo '<option value="'.$i.'">'.sprintf("%02d",$i).'</option>';
                  }
                  echo '
                </select>
                /
                <select class="" name="valid_y" required>
                  <option value="">YYYY</option>
                  ';
                  for ($i=date("Y"); $i <= (date("Y")+20); $i++) { 
                    echo '<option value="'.$i.'">'.sprintf("%04d",$i).'</option>';
                  }
                  echo '
                </select>
                <p class="help-block" id="rfid_valid_date_help-block"></p>
              </div>
            </div>


          </form>
          </p>
        </div>
        <div class="modal-footer">
         
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

  ';


  echo '
  <!--RFID Scan to Add Student Modal -->
  <div id="rfid_settings_modal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-sm">

      <!-- Modal content-->
      <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="rfid_add_modal_title">Add studentsss</h4>
      </div>
        <div class="modal-body">
          <p>
          '.form_open("rfid_ajax/scan_add",'id="rfid_scan_add_form"').'
          <input type="hidden" name="type">
          <input type="hidden" name="id">

            <div class="form-group">

              <label for="rfid"></label>
              <div class="col-sm-12">
                <input type="text" class="form-control" name="rfid" placeholder="Scan RFID using RFID Reader..." autocomplete="off">
                <p class="help-block" id="rfid_scan_help-block"></p>
              </div>

            </div>

            <div class="form-group">

            <label class="col-sm-12" for="last_name">Valid Until:</label>

            </div>

             <div class="form-group">
              
              <div class="col-sm-12">
                <select class="" name="valid_m" required>
                  <option value="">MM</option>
                  ';
                  for ($i=1; $i <= 12; $i++) { 
                    echo '<option value="'.$i.'">'.sprintf("%02d",$i).'</option>';
                  }
                  echo '
                </select>
                /
                <select class="" name="valid_d" required>
                  <option value="">DD</option>
                  ';
                  for ($i=1; $i <= 31; $i++) { 
                    echo '<option value="'.$i.'">'.sprintf("%02d",$i).'</option>';
                  }
                  echo '
                </select>
                /
                <select class="" name="valid_y" required>
                  <option value="">YYYY</option>
                  ';
                  for ($i=date("Y"); $i <= (date("Y")+20); $i++) { 
                    echo '<option value="'.$i.'">'.sprintf("%04d",$i).'</option>';
                  }
                  echo '
                </select>
                <p class="help-block" id="rfid_valid_date_help-block"></p>
              </div>
            </div>


          </form>
          </p>
        </div>
        <div class="modal-footer">
         
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

  ';

  echo '

  <!--Add Student Modal -->
  <div id="students_add_modal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Students</h4>
        </div>
        <div class="modal-body">
          <p>'.form_open_multipart("student_ajax/add",'id="student_add_form" class="form-horizontal"').'
            <!-- <input type="hidden" class="form-control rfid_scanned_add" name="rfid"> -->

            <div class="form-group">
              <label class="col-sm-2" for="last_name">Last Name:</label>
              <div class="col-sm-10"> 
                <input type="text" class="form-control" name="last_name" placeholder="Enter Last Name">
                <p class="help-block" id="student_last_name_help-block"></p>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2" for="first_name">First Name:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="first_name" placeholder="Enter First Name">
                <p class="help-block" id="student_first_name_help-block"></p>
              </div>
              
            </div>
            
            
            <div class="form-group">
              <label class="col-sm-2" for="middle_name">Middle Name:</label>
              <div class="col-sm-10"> 
                <input type="text" class="form-control" name="middle_name" placeholder="Enter Middle Name">
                <p class="help-block" id="student_middle_name_help-block"></p>
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-2" for="suffix">Suffix:</label>
              <div class="col-sm-10"> 
                <input type="text" class="form-control" name="suffix" placeholder="Enter Suffix (Jr. III etc.)">
                <p class="help-block" id="student_suffix_help-block"></p>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2" for="guardian_name">Gender:</label>
              <div class="col-sm-10"> 
                <select name="gender" class="form-control" required>
                  <option value="MALE">MALE</option>
                  <option value="FEMALE">FEMALE</option>
                </select>
                <p class="help-block" id="student_gender_help-block"></p>
              </div>
            </div>


            <div class="form-group">
              <label class="col-sm-2" for="last_name">Birth Date:</label>
              <div class="col-sm-10">
                <select class="" name="bday_m" required>
                  <option value="">MM</option>
                  ';
                  for ($i=1; $i <= 12; $i++) { 
                    echo '<option value="'.$i.'">'.sprintf("%02d",$i).'</option>';
                  }
                  echo '
                </select>
                /
                <select class="" name="bday_d" required>
                  <option value="">DD</option>
                  ';
                  for ($i=1; $i <= 31; $i++) { 
                    echo '<option value="'.$i.'">'.sprintf("%02d",$i).'</option>';
                  }
                  echo '
                </select>
                /
                <select class="" name="bday_y" required>
                  <option value="">YYYY</option>
                  ';
                  for ($i=1980; $i <= date("Y"); $i++) { 
                    echo '<option value="'.$i.'">'.sprintf("%04d",$i).'</option>';
                  }
                  echo '
                </select>
                <p class="help-block" id="student_bday_help-block"></p>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2" for="contact_number">Contact Number:</label>
              <div class="col-sm-10"> 
                <input type="text" class="form-control" name="contact_number" placeholder="Enter Contact Number">
                <p class="help-block" id="student_contact_number_help-block"></p>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2" for="address">Address:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="address" placeholder="Enter Address">
                <p class="help-block" id="student_address_help-block"></p>
              </div>
              
            </div>

            <div class="form-group">
              <label class="col-sm-2" for="guardian">Guardians Contact Number:</label>
              <div class="col-sm-8"> 
                <select class="ui search dropdown form-control" name="guardian_id">
                  <option value="">Select a Guardians Contact Number</option>
                  ';
                  foreach ($guardians_list["result"] as $guardian_data) {
                    echo '<option value="'.$guardian_data->id.'">'.$guardian_data->email_address.'</option>';
                  }

                  echo '
                </select>

                <p class="guardian_id_help-block"></p>
              </div>
              <div class="col-sm-2"> 
                <button type="button" class="btn btn-default btn-block" id="add_guardian">Add</button>
              </div>
            </div>


            <div class="form-group">
              <label class="col-sm-2" for="guardian_name">Father&apos;s Name:</label>
              <div class="col-sm-10"> 
                <input type="text" class="form-control" name="fathers_name" placeholder="Enter Father&apos;s Name">
                <p class="help-block" id="student_fathers_name_help-block"></p>
              </div>
            </div>



            <div class="form-group">
              <label class="col-sm-2" for="guardian_name">Mother&apos;s Name:</label>
              <div class="col-sm-10"> 
                <input type="text" class="form-control" name="mothers_name" placeholder="Enter Mother&apos;s Name">
                <p class="help-block" id="student_mothers_name_help-block"></p>
              </div>
            </div>



            <div class="form-group">
              <label class="col-sm-2" for="class_id">Class:</label>
              <div class="col-sm-10"> 
                <select class="ui search dropdown form-control" name="class_id" data-live-search="true">
                  <option value="">Select a Class</option>
                  ';
                  foreach ($classes_list["result"] as $class_data) {
                    echo '<option value="'.$class_data->id.'">'.$class_data->class_name.'</option>';
                  }

                  echo '
                </select>

                <p class="help-block" id="student_class_id_help-block"></p>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2" for="student_photo">Photo:</label>
              <div class="col-sm-10">
              <input type="file" name="student_photo" size="20" class="form-control">
                <p class="help-block" id="student_photo_help-block"></p>
              </div>
            </div>

          </form></p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" form="student_add_form">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

  ';



  echo '

  <!--Add Teachers Modal -->
  <div id="teachers_add_modal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add teachers</h4>
        </div>
        <div class="modal-body">
          <p>'.form_open_multipart("teacher_ajax/add",'id="teacher_add_form" class="form-horizontal"').'
            <input type="hidden" class="form-control rfid_scanned_add" name="rfid">

            <div class="form-group">
              <label class="col-sm-2" for="last_name">Last Name:</label>
              <div class="col-sm-10"> 
                <input type="text" class="form-control" name="last_name" placeholder="Enter Last Name">
                <p class="help-block" id="teacher_last_name_help-block"></p>
              </div>
            </div>

            
            <div class="form-group">
              <label class="col-sm-2" for="first_name">First Name:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="first_name" placeholder="Enter First Name">
                <p class="help-block" id="teacher_first_name_help-block"></p>
              </div>
              
            </div>
            

            
            <div class="form-group">
              <label class="col-sm-2" for="middle_name">Middle Name:</label>
              <div class="col-sm-10"> 
                <input type="text" class="form-control" name="middle_name" placeholder="Enter Middle Name">
                <p class="help-block" id="teacher_middle_name_help-block"></p>
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-2" for="suffix">Suffix:</label>
              <div class="col-sm-10"> 
                <input type="text" class="form-control" name="suffix" placeholder="Enter Suffix (Jr. III etc.)">
                <p class="help-block" id="teacher_suffix_help-block"></p>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2" for="guardian_name">Gender:</label>
              <div class="col-sm-10"> 
                <select name="gender" class="form-control" required>
                  <option value="MALE">MALE</option>
                  <option value="FEMALE">FEMALE</option>
                </select>
                <p class="help-block" id="teacher_gender_help-block"></p>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2" for="contact_number">Contact Number:</label>
              <div class="col-sm-10"> 
                <input type="text" class="form-control" name="contact_number" placeholder="Enter Contact Number">
                <p class="help-block" id="teacher_contact_number_help-block"></p>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2" for="address">Address:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="address" placeholder="Enter Address">
                <p class="help-block" id="teacher_address_help-block"></p>
              </div>
              
            </div>
            

            <div class="form-group">
              <label class="col-sm-2" for="last_name">Birth Date:</label>
              <div class="col-sm-10">
                <select class="" name="bday_m" required>
                  <option value="">MM</option>
                  ';
                  for ($i=1; $i <= 12; $i++) { 
                    echo '<option value="'.$i.'">'.sprintf("%02d",$i).'</option>';
                  }
                  echo '
                </select>
                /
                <select class="" name="bday_d" required>
                  <option value="">DD</option>
                  ';
                  for ($i=1; $i <= 31; $i++) { 
                    echo '<option value="'.$i.'">'.sprintf("%02d",$i).'</option>';
                  }
                  echo '
                </select>
                /
                <select class="" name="bday_y" required>
                  <option value="">YYYY</option>
                  ';
                  for ($i=1940; $i <= date("Y"); $i++) { 
                    echo '<option value="'.$i.'">'.sprintf("%04d",$i).'</option>';
                  }
                  echo '
                </select>
                <p class="help-block" id="teacher_bday_help-block"></p>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2" for="class_id">Class:</label>
              <div class="col-sm-8"> 
                <select class="ui search dropdown form-control" name="class_id" data-live-search="true">
                  <option value="">Select a Class</option>
                  ';
                  foreach ($classes_list["result"] as $class_data) {
                    echo '<option value="'.$class_data->id.'">'.$class_data->class_name.'</option>';
                  }

                  echo '
                </select>
                <p class="help-block" id="teacher_class_id_help-block"></p>
              </div>
              <div class="col-sm-2"> 
                <button class="btn btn-block btn-default" type="button" id="add-class">Add</button>
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
          <button type="submit" class="btn btn-primary" form="teacher_add_form">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

  ';




  echo '

  <!--Add Staffs Modal -->
  <div id="staffs_add_modal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add staff</h4>
        </div>
        <div class="modal-body">
          <p>'.form_open_multipart("staff_ajax/add",'id="staff_add_form" class="form-horizontal"').'
            <input type="hidden" class="form-control rfid_scanned_add" name="rfid">

            <div class="form-group">
              <label class="col-sm-2" for="position">Position:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="position" placeholder="Enter Position">
                <p class="help-block" id="staff_position_help-block"></p>
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-2" for="last_name">Last Name:</label>
              <div class="col-sm-10"> 
                <input type="text" class="form-control" name="last_name" placeholder="Enter Last Name">
                <p class="help-block" id="staff_last_name_help-block"></p>
              </div>
            </div>
            

            <div class="form-group">
              <label class="col-sm-2" for="first_name">First Name:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="first_name" placeholder="Enter First Name">
                <p class="help-block" id="staff_first_name_help-block"></p>
              </div>
              
            </div>

            <div class="form-group">
              <label class="col-sm-2" for="middle_name">Middle Name:</label>
              <div class="col-sm-10"> 
                <input type="text" class="form-control" name="middle_name" placeholder="Enter Middle Name">
                <p class="help-block" id="staff_middle_name_help-block"></p>
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-2" for="suffix">Suffix:</label>
              <div class="col-sm-10"> 
                <input type="text" class="form-control" name="suffix" placeholder="Enter Suffix (Jr. III etc.)">
                <p class="help-block" id="staff_suffix_help-block"></p>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2" for="gender">Gender:</label>
              <div class="col-sm-10"> 
                <select name="gender" class="form-control" required>
                  <option value="MALE">MALE</option>
                  <option value="FEMALE">FEMALE</option>
                </select>
                <p class="help-block" id="staff_gender_help-block"></p>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2" for="contact_number">Contact Number:</label>
              <div class="col-sm-10"> 
                <input type="text" class="form-control" name="contact_number" placeholder="Enter Contact Number">
                <p class="help-block" id="staff_contact_number_help-block"></p>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2" for="address">Address:</label>
              <div class="col-sm-10"> 
                <input type="text" class="form-control" name="address" placeholder="Enter Address">
                <p class="help-block" id="staff_address_help-block"></p>
              </div>
            </div>


            <div class="form-group">
              <label class="col-sm-2" for="last_name">Birth Date:</label>
              <div class="col-sm-10">
                <select class="" name="bday_m" required>
                  <option value="">MM</option>
                  ';
                  for ($i=1; $i <= 12; $i++) { 
                    echo '<option value="'.$i.'">'.sprintf("%02d",$i).'</option>';
                  }
                  echo '
                </select>
                /
                <select class="" name="bday_d" required>
                  <option value="">DD</option>
                  ';
                  for ($i=1; $i <= 31; $i++) { 
                    echo '<option value="'.$i.'">'.sprintf("%02d",$i).'</option>';
                  }
                  echo '
                </select>
                /
                <select class="" name="bday_y" required>
                  <option value="">YYYY</option>
                  ';
                  for ($i=1940; $i <= date("Y"); $i++) { 
                    echo '<option value="'.$i.'">'.sprintf("%04d",$i).'</option>';
                  }
                  echo '
                </select>
                <p class="help-block" id="staff_bday_help-block"></p>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2" for="staff_photo">Photo:</label>
              <div class="col-sm-10">
              <input type="file" name="staff_photo" size="20" class="form-control">
                <p class="help-block" id="staff_photo_help-block"></p>
              </div>
            </div>

          </form></p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" form="staff_add_form">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

  ';




  echo '
  <!--RFID Scan to Load up Student Modal -->
  <div id="rfid_scan_add_load_credits_modal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-sm">

      <!-- Modal content-->
      <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Load to a Student</h4>
      </div>
        <div class="modal-body">
          <p>
          '.form_open("rfid_ajax/rfid_scan_add_load_credit",'id="rfid_scan_add_load_credit_form"').'

            <div class="form-group">

              <label for="rfid_scan_add_load_credits"></label>
              <div class="col-sm-12">
                <input type="text" class="form-control" name="rfid" placeholder="Scan RFID using RFID Reader..." autocomplete="off">
                <p class="help-block" id="rfid_scan_add_load_credits_help-block"></p>
              </div>

            </div>


          </form>
          </p>
        </div>
        <div class="modal-footer">
         
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

  ';




  echo '
  <!-- Load up Student Modal -->
  <div id="add_load_credits_data_modal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Load to a Student</h4>
      </div>
        <div class="modal-body">
          <p>
          '.form_open("rfid/add_load_credits",'id="add_load_credits_form"').'
          <input name="rfid_id" type="hidden">
          <div class="row">

            <div class="col-sm-3">
              <img src="" class="img-responsive" alt="Student Photo" id="add_load_credits_display-photo">
            </div>
            <div class="col-sm-9">
                <table class="table">
                  <tbody>
                    <tr>
                      <th>Name:</th>
                      <td><span id="add_load_credits_full_name"></span></td>
                    </tr>
                    <tr>
                      <th>Remaining Load:</th>
                      <td><span id="add_load_credits_remaining_load"></span></td>
                    </tr>
                    <tr>
                      <th>Add Load:</th>
                      <td><input type="number" name="load_credits" class="form-control" step="0.01" min="1" placeholder="Enter Amount" required>
                      <p class="help-block" id="load_credits_help-block"></p>
                      </td>
                    </tr>
                  </tbody>
                </table>
            </div>

          </div>


          </form>
          </p>
        </div>
        <div class="modal-footer">
         
          <button type="submit" class="btn btn-primary" form="add_load_credits_form">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

  ';



  echo '
  <!-- Add Guardian Modal -->
  <div id="register_guardian_modal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Register A Guardian</h4>
        </div>
        <div class="modal-body">
          <p>'.form_open("guardian_ajax/register",'id="register_guardian_form" class="form-horizontal"');


          echo '

          <div class="form-group">
            <label class="col-sm-4" for="guardian_name">Guardian Name:</label>
            <div class="col-sm-8"> 
              <input type="text" class="form-control" name="guardian_name" placeholder="Enter Guardian Name">
              <p class="help-block" id="add_guardian_name_help-block"></p>
            </div>
          </div>


          <div class="form-group">
            <label class="col-sm-4" for="guardian_name">Guardian&apos;s Address:</label>
            <div class="col-sm-8"> 
              <input type="text" class="form-control" name="guardian_address" placeholder="Enter Guardian&apos;s Address">
              <p class="help-block" id="add_guardian_address_help-block"></p>
            </div>
          </div>




          <div class="form-group">
            <label class="col-sm-4" for="email_address">Email Address:</label>
            <div class="col-sm-8"> 
              <input type="text" class="form-control" name="email_address" placeholder="Enter Email Address">
              <p class="help-block" id="add_email_address_help-block"></p>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4" for="contact_number">Contact Number:</label>
            <div class="col-sm-8"> 
              <input type="text" class="form-control" name="contact_number" placeholder="Enter Contact Number">
              <p class="help-block" id="add_contact_number_help-block"></p>
            </div>
          </div>



          <div class="form-group">
            <label class="col-sm-4" for="email_subscription">Subscription:</label>
            <div class="col-sm-8"> 
              <div class="checkbox">
                <label><input type="checkbox" name="email_subscription" value="1"> Email Subscription</label>
              </div>
              <div class="checkbox">
                <label><input type="checkbox" name="sms_subscription" value="1"> SMS Subscription</label>
              </div>
              <p class="help-block" id="add_subscription_help-block"></p>
            </div>
          </div>
          ';

          echo '</form></p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" form="register_guardian_form">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

  ';


  echo '
  <!-- Add Canteen Modal -->
  <div id="add_canteen_modal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Canteen</h4>
      </div>
        <div class="modal-body">
          <p>
          '.form_open("canteen_ajax/add",'id="add_canteen_form" class="form-horizontal"').'

          <div class="form-group">
            <label class="col-sm-4" for="canteen_name">Canteen Name:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="canteen_name" placeholder="Enter Canteen Name" required>
              <p class="help-block" id="canteen_name_help-block"></p>
            </div>
            
          </div>

          <div class="form-group">
            <label class="col-sm-4" for="canteen_address">Address:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="canteen_address" placeholder="Enter Canteen Address" required>
              <p class="help-block" id="canteen_address_help-block"></p>
            </div>
            
          </div>

          <div class="form-group">
            <label class="col-sm-4" for="canteen_contact_number">Contact Number:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="canteen_contact_number" placeholder="Enter Canteen Contact Number" required>
              <p class="help-block" id="canteen_contact_number_help-block"></p>
            </div>
            
          </div>


          </form>
          </p>
        </div>
        <div class="modal-footer">
         
          <button type="submit" class="btn btn-primary" form="add_canteen_form">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

  ';

  echo '
  <!-- Add Canteen Users Modal -->
  <div id="add_canteen_users_modal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Canteen</h4>
      </div>
        <div class="modal-body">
          <p>
          '.form_open("canteen_ajax/add_users",'id="add_canteen_users_form" class="form-horizontal"').'

          <div class="form-group">
            <label class="col-sm-4" for="canteen_username">Username:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" name="canteen_username" placeholder="Enter Username" required>
              <p class="help-block" id="canteen_username_help-block"></p>
            </div>
          </div>


          <div class="form-group">
            <label class="col-sm-4" for="canteen_password">Password:</label>
            <div class="col-sm-8">
              <input type="password" class="form-control" name="canteen_password" placeholder="Enter Password" autocomplete="new-password" required>
              <p class="help-block" id="canteen_password_help-block"></p>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4" for="canteen_confirm_password">Confirm Password:</label>
            <div class="col-sm-8">
              <input type="password" class="form-control" name="canteen_confirm_password" placeholder="Confirm Password" autocomplete="new-password" required>
              <p class="help-block" id="canteen_confirm_password_help-block"></p>
            </div>
          </div>

          </form>
          </p>
        </div>
        <div class="modal-footer">
         
          <button type="submit" class="btn btn-primary" form="add_canteen_users_form">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

  ';


  echo '
  <!-- Add Class Modal -->
  <div id="class_add_modal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="rfid_add_modal_title">Add class</h4>
      </div>
        <div class="modal-body">
          <p>
          '.form_open("class_ajax/add",'id="class_add_form"  class="form-horizontal"').'

            <div class="form-group">
              <label class="col-sm-4" for="class_adviser">Class Adviser:</label>
              <div class="col-sm-8">
              ';
              echo '
                <select class="ui search dropdown form-control" name="class_adviser" data-live-search="true">
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
                <input type="text" class="form-control" name="class_name" placeholder="Enter Class Name">
                <p class="help-block" id="class_name_help-block"></p>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-4" for="grade">Grade or Year:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="grade" placeholder="Enter Grade or Year">
                <p class="help-block" id="grade_help-block"></p>
              </div>
            </div>


            <div class="form-group">
              <label class="col-sm-4" for="class_room">Classroom:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="class_room" placeholder="Enter Classroom">
                <p class="help-block" id="class_room_help-block"></p>
              </div>
            </div>


            <div class="form-group">
              <label class="col-sm-4" for="class_schedule">Class Schedule:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="class_schedule" placeholder="Enter Class Schedule">
                <p class="help-block" id="class_schedule_help-block"></p>
              </div>
            </div>

          </form>
          </p>
        </div>
        <div class="modal-footer">
         
          <button type="submit" class="btn btn-primary" form="class_add_form">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

  ';

  echo '
<!-- SMS Modal -->
<div id="sms-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send SMS</h4>
      </div>
      <div class="modal-body">';
      echo form_open("sms_ajax/send",'id="sms-form" class="form-horizontal"');
      echo '<input type="hidden" name="sender" value="admin">';
      echo '
      <div class="form-group">
        <label class="col-sm-4" for="type_recipient">Send to:</label>
        <div class="col-sm-8">
          <select name="type_recipient" class="form-control">
            <option value="teachers_students">Teachers and Students of the class</option>
            <option value="teachers">Teachers of the class</option>
            <option value="students">Students of the class</option>
            <option value="guardian">Students Guardian&apos;s of the class</option>
            <option value="members">All members of the class including Student&apos;s Guardian</option>
            <option value="all_teachers_students">All Students and Teachers</option>
            <option value="all_teachers">All Teachers</option>
            <option value="all_students">All Students</option>
            <option value="all_guardians">All Student&apos;s Guardians</option>
            <option value="all_members">All members including Student&apos;s Guardian</option>
          </select>
          <p class="help-block" id="type_recipient_help-block"></p>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4" for="message">Message:</label>
        <div class="col-sm-8">
          <textarea class="form-control" name="message" placeholder="Enter your message."></textarea>
          <p class="help-block" id="message_help-block"></p>
        </div>
      </div>


      <div class="form-group" id="send-to-container">
        <label class="col-sm-4" for="sms_recipient">Send to Class:</label>
        <div class="col-sm-8">
          <select class="ui fluid search dropdown" multiple="" name="class_id[]">
          </select>
          <p class="help-block" id="class_id_help-block"></p>
        </div>
      </div>

      ';

      echo '</form>';

      // echo '<span data-balloon="SMS has 500 Max Messages per Day and will reset in 12MN." data-balloon-pos="right" data-balloon-length="fit">SMS Remaining: <b>'.$sms_module_sms_left.'</b></span>';
      echo '</div>
      <div class="modal-footer">
        <img src="'.base_url("assets/images/loading.gif").'" style="width:3rem;height:3rem;display:none" class="loading"></img>
        <button type="submit" class="btn btn-primary" form="sms-form">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

';



echo '
<!-- SMS list Modal -->
<div id="sms-list-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">SMS Status of Message ID: <span id="message_id_txt"></span></h4>
      </div>
      <div class="modal-body">
        <table class="table table-hover sms_list_table">
          <thead>
            <tr>
              <th>Message</th>
              <th>Mobile Number</th>
              <th>Recipient</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

';



echo '
<!-- Change Password Modal -->
<div id="gate_change_password-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="change_password-modal-title">Change Gate Password</h4>
      </div>
      <div class="modal-body">
        <p>
        '.form_open("admin_ajax/gate_change_password",'id="gate_change_password-form" class="form-horizontal"').'
        <input type="hidden" name="id" value="1">
        <input type="hidden" name="type" value="app_config">
        <div class="form-group">
          <label class="col-sm-4" for="current_password">Current Password:</label>
          <div class="col-sm-8"> 
            <input type="password" class="form-control" name="current_password" placeholder="Enter Current Password">
            <p class="help-block" id="gate_current_password_help-block"></p>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-4" for="new_password">New Password:</label>
          <div class="col-sm-8"> 
            <input type="password" class="form-control" name="new_password" placeholder="Enter New Password">
            <p class="help-block" id="gate_new_password_help-block"></p>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-4" for="confirm_password">Confirm New Password:</label>
          <div class="col-sm-8"> 
            <input type="password" class="form-control" name="confirm_password" placeholder="Enter Confirm New Password">
            <p class="help-block" id="gate_confirm_password_help-block"></p>
          </div>
        </div>


        </form>
        </p>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" form="gate_change_password-form">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

';



}elseif ($modals_sets=="teacher") {
    echo '
  <!-- SMS Modal -->
  <div id="sms-modal-teacher" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Send SMS</h4>
        </div>
        <div class="modal-body">';
        echo form_open("sms_ajax/send",'id="sms-form" class="form-horizontal"');
        echo '<input type="hidden" name="sender" value="teacher">';
        echo '<input type="hidden" name="class_id[]" value="'.$teacher_data->class_id.'">';
        echo '
        <div class="form-group">
          <label class="col-sm-4" for="type_recipient">Send to:</label>
          <div class="col-sm-8">
            <select name="type_recipient" class="form-control">
              <option value="students">Students of the class</option>
              <option value="guardian">Students Guardian&apos;s of the class</option>
              <option value="members">All members of the class including Student&apos;s Guardian</option>
            </select>
            <p class="help-block" id="type_recipient_help-block"></p>
          </div>
        </div>


        <div class="form-group">
          <label class="col-sm-4" for="message">Message:</label>
          <div class="col-sm-8">
            <textarea class="form-control" name="message" placeholder="Enter your message."></textarea>
            <p class="help-block" id="message_help-block"></p>
          </div>
        </div>
        ';

        echo '</form>';

        // echo '<span data-balloon="SMS has 500 Max Messages per Day and will reset in 12MN." data-balloon-pos="right" data-balloon-length="fit">SMS Remaining: <b>'.$sms_module_sms_left.'</b></span>';
        echo '</div>
        <div class="modal-footer">
          <img src="'.base_url("assets/images/loading.gif").'" style="width:3rem;height:3rem;display:none" class="loading"></img>
          <button type="submit" class="btn btn-primary" form="sms-form">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

  ';


  echo '
  <!-- SMS list Modal -->
  <div id="sms-list-modal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">SMS Status of Message ID: <span id="message_id_txt"></span></h4>
        </div>
        <div class="modal-body">
          <table class="table table-hover sms_list_table">
            <thead>
              <tr>
                <th>Message</th>
                <th>Mobile Number</th>
                <th>Recipient</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

  ';
}elseif ($modals_sets=="home") {
  echo '
  <!-- Email Settings -->
  <div id="email_settings-modal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Email Settings</h4>
        </div>
        <div class="modal-body">
        '.form_open("guardian_ajax/email_settings",'id="guardian_email_settings_form" class="form-horizontal"').'
        <input type="hidden" name="id" value="'.$login_user_data->id.'">

          <div class="form-group">
            <label class="col-sm-4" for="email_address">Email Address:</label>
            <div class="col-sm-8"> 
              <input type="text" class="form-control" name="email_address" value="'.$login_user_data->email_address.'" placeholder="Enter Email Address">
              <p class="help-block" id="email_settings_email_address_help-block"></p>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4" for="email_subscription">Subscription:</label>
            <div class="col-sm-8"> 
              <div class="checkbox">
                <label><input type="checkbox" name="email_subscription" value="1" ';
                if($login_user_data->email_subscription=="1"){
                  echo "checked";
                }
                echo '> Email Subscription</label>
              </div>
              <p class="help-block"></p>
            </div>
          </div>

        </form>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" form="guardian_email_settings_form">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

  ';
}elseif ($modals_sets=="jbtech") {

  echo '
  <!-- Change Password Modal -->
  <div id="reset_admin_password-modal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="change_password-modal-title">admin password reset</h4>
        </div>
        <div class="modal-body">
          <p>
          '.form_open("admin_ajax/reset_password",'id="reset_admin_password-form" class="form-horizontal"').'

          <div class="form-group">
            <label class="col-sm-4" for="username">Email Address:</label>
            <div class="col-sm-8"> 
              <select class="ui search dropdown" name="id" id="select_admin_username">
                <option value="">Select Admin Username</option>
              </select>
              <p class="help-block" id="reset_admin_username_help-block"></p>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4" for="email_address">Email Address:</label>
            <div class="col-sm-8"> 
              <input type="text" class="form-control" name="email_address" placeholder="Enter Email Address">
              <p class="help-block" id="reset_admin_password_help-block"></p>
            </div>
          </div>


          </form>
          </p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" form="reset_admin_password-form">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

  ';


  echo '
  <!-- Change Password Modal -->
  <div id="add_admin-modal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="change_password-modal-title">add admin account</h4>
        </div>
        <div class="modal-body">
          <p>
          '.form_open("admin_ajax/add_account",'id="add_admin-form" class="form-horizontal"').'

          <div class="form-group">
            <label class="col-sm-4" for="username">Username:</label>
            <div class="col-sm-8"> 
              <input type="text" class="form-control" name="username" placeholder="Enter Email Address">
              <p class="help-block" id="add_admin_username_help-block"></p>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4" for="email_address">Email Address:</label>
            <div class="col-sm-8"> 
              <input type="text" class="form-control" name="email_address" placeholder="Enter Email Address">
              <p class="help-block" id="add_admin_email_address_help-block"></p>
            </div>
          </div>


          </form>
          </p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" form="add_admin-form">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

  ';

  # code...
}

// var_dump();

echo '
<!-- Change Password Modal -->
<div id="change_password-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="change_password-modal-title">Change Password</h4>
      </div>
      <div class="modal-body">
        <p>
        '.form_open("accounts/change_password",'id="change_password-form" class="form-horizontal"').'
        <input type="hidden" name="type" id="change_password_type">
        <input type="hidden" name="id" value="'.$login_user_data->id.'">

        <div class="form-group">
          <label class="col-sm-4" for="current_password">Current Password:</label>
          <div class="col-sm-8"> 
            <input type="password" class="form-control" name="current_password" placeholder="Enter Current Password">
            <p class="help-block" id="current_password_help-block"></p>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-4" for="new_password">New Password:</label>
          <div class="col-sm-8"> 
            <input type="password" class="form-control" name="new_password" placeholder="Enter New Password">
            <p class="help-block" id="new_password_help-block"></p>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-4" for="confirm_password">Confirm New Password:</label>
          <div class="col-sm-8"> 
            <input type="password" class="form-control" name="confirm_password" placeholder="Enter Confirm New Password">
            <p class="help-block" id="confirm_password_help-block"></p>
          </div>
        </div>


        </form>
        </p>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" form="change_password-form">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

';



echo '
<!-- Alert Modal -->
<div id="alert-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="alert-modal-title">Modal Header</h4>
      </div>
      <div class="modal-body" id="alert-modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

';




?>