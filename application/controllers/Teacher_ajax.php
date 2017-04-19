<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher_ajax extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('app_helper');


		//models
		$this->load->helper('string');
		$this->load->model('guardian_model');
		$this->load->model("admin_model");
		$this->load->model("teachers_model");
		$this->load->model("teachers_model");
		$this->load->model("gate_logs_model");
		$this->load->model("rfid_model");
		$this->load->model("canteen_model");
		$this->load->model("classes_model");
		$this->load->model("canteen_items_model");

		$this->load->library('form_validation');
		$this->load->library('session');
		
		$this->form_validation->set_error_delimiters('', '');
	}

	public function index()
	{
		show_404();
	}
	public function add($arg='')
	{
		
		if($_POST){

			// $data["guardian_id"] = $this->input->post("guardian_id");
			// $data["class_id"] = $this->input->post("class_id");
			$this->form_validation->set_rules('gender', 'Gender', 'required|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('address', 'Address', 'required|min_length[2]|max_length[100]trim|htmlspecialchars');
			$this->form_validation->set_rules('first_name', 'First Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('middle_name', 'Middle Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('suffix', 'Suffix', 'custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('bday_m', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
			$this->form_validation->set_rules('bday_d', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
			$this->form_validation->set_rules('bday_y', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
			$this->form_validation->set_rules('class_id', 'Class', 'trim|htmlspecialchars');
			$this->form_validation->set_rules('contact_number', 'Contact Number', 'required|is_available[teachers.contact_number]|numeric|min_length[11]|max_length[11]trim|htmlspecialchars');

			$this->form_validation->set_message('is_in_db', 'This account is invalid');



			$has_uploaded_pic = FALSE;
			//uploads files
			if($_FILES['teacher_photo']["error"]==0){

				$filename_first_name_array = explode(" ", $this->input->post("first_name"));
				$filename_first_name = implode("-", $filename_first_name_array);

				$filename_middle_name_array = explode(" ", $this->input->post("middle_name"));
				$filename_middle_name = implode("-", $filename_middle_name_array);

				$filename_last_name_array = explode(" ", $this->input->post("last_name"));
				$filename_last_name = implode("-", $filename_last_name_array);

				$filename_suffix_array = explode(" ", $this->input->post("suffix"));
				$filename_suffix = implode("-", $filename_suffix_array);

				$filename_full_name = $filename_last_name."_".$filename_first_name."_".$filename_middle_name."_".$filename_suffix;

				$filename = $filename_full_name;



				$config['overwrite'] = TRUE;
				$config['upload_path'] = './assets/images/teacher_photo/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['file_name'] = $filename;
				$config['max_size']	= '20480';
				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload("teacher_photo"))
				{
					$data["is_valid_photo"] = FALSE;
					$data["photo_error"] = $this->upload->display_errors("","");
				}
				else
				{
					$data["photo_error"] = "";
					$data["is_valid_photo"] = TRUE;
					$image_data = $this->upload->data();
					$filename = $filename.$image_data["file_ext"];
					$image_data = $this->upload->data();
					$config['image_library'] = 'gd2';
					$config['source_image'] = $image_data["full_path"];
					$full_path = $image_data["full_path"];
					$file_path = $image_data["file_path"];
					$file_name = $image_data["file_name"];
					$config['create_thumb'] = FALSE;
					$config['new_image'] = $filename;
					$config['maintain_ratio'] = TRUE;
					$config['width']     = 360;
					$config['height']   = 360;
					$this->load->library('image_lib', $config); 
					$this->image_lib->resize();
					$this->image_lib->clear();

					$has_uploaded_pic = TRUE;
				}
			}else{
				$data["is_valid_photo"] = TRUE;
				$filename = "empty.jpg";				
			}

			if ($this->form_validation->run() == FALSE|| $data["is_valid_photo"] == FALSE)
			{
				$data["is_valid"] = FALSE;
				$data["gender_error"] = form_error('gender');
				$data["first_name_error"] = form_error('first_name');
				$data["last_name_error"] = form_error('last_name');
				$data["address_error"] = form_error('address');
				$data["middle_name_error"] = form_error('middle_name');
				$data["suffix_error"] = form_error('suffix');
				$data["contact_number_error"] = form_error('contact_number');
				$data["class_id_error"] = form_error('class_id');
				$data["bday_error"] = form_error('bday_m');
			}
			else
			{
				$data["is_valid"] = TRUE;
				$data["gender_error"] = "";
				$data["first_name_error"] = "";
				$data["last_name_error"] = "";
				$data["address_error"] = "";
				$data["middle_name_error"] = "";
				$data["suffix_error"] = "";
				$data["contact_numbererror"] = "";
				$data["class_id_error"] = "";
				$data["bday_error"] = "";

				$teacher_data["first_name"] = $this->input->post("first_name");
				$teacher_data["gender"] = $this->input->post("gender");
				$teacher_data["address"] = $this->input->post("address");
				$teacher_data["last_name"] = $this->input->post("last_name");
				$teacher_data["middle_name"] = $this->input->post("middle_name");
				$teacher_data["suffix"] = $this->input->post("suffix");
				$teacher_data["contact_number"] = $this->input->post("contact_number");
				// $teacher_data["class_id"] = $this->input->post("class_id");
				$teacher_data["display_photo"] = $filename;
				// $teacher_data["display_photo_type"] = $new_image_data['file_type'];
				$bday_m = sprintf("%02d",$this->input->post("bday_m"));
				$bday_d = sprintf("%02d",$this->input->post("bday_d"));
				$bday_y = sprintf("%04d",$this->input->post("bday_y"));
				$birthdate_str = $bday_m."/".$bday_d."/".$bday_y;
				$teacher_data["birthdate"] = strtotime($birthdate_str);
				$password = random_string('alnum', 8);

				$message = "Your account details as teacher are:
Login: ".$teacher_data["contact_number"]."
Password: ".$password."
You can login to ".base_url("teacher");


				send_sms($this->input->post("contact_number"),$message);
				$teacher_data["password"] = md5($password);

				// $teacher_data["rfid"] = $this->input->post("rfid");
				$data["is_successful"] = TRUE;
				$teacher_data = $this->teachers_model->add($teacher_data);

				if($has_uploaded_pic){
					rename($full_path,$file_path.$teacher_data->id."_".$file_name);
					$edit_data = array();
					$edit_data["display_photo"] = $teacher_data->id."_".$file_name;
					$this->teachers_model->edit_info($edit_data,$teacher_data->id);
				}


				// $rfid_data["rfid"] = $this->input->post("rfid");
				$rfid_data["ref_id"] = $teacher_data->id;
				$rfid_data["ref_table"] = "teachers";
				$rfid_data["valid"] = 1;
				$this->rfid_model->add($rfid_data);


				$teacher_id = $teacher_data->id;
				$class_id = ($this->input->post("class_id")?$this->input->post("class_id"):"0");
				$this->classes_model->change_class($teacher_id,$class_id);

			}

			echo json_encode($data);
		}
		
	}

	public function edit($arg='')
	{
		if($_POST){


			// $data["guardian_id"] = $this->input->post("guardian_id");
			// $data["class_id"] = $this->input->post("class_id");
			$this->form_validation->set_rules('gender', 'Gender', 'required|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('teacher_id', 'First Name', 'required|trim|htmlspecialchars|is_in_db[teachers.id]');
			$this->form_validation->set_rules('first_name', 'First Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('address', 'Address', 'required|min_length[2]|max_length[100]trim|htmlspecialchars');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('middle_name', 'Middle Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('suffix', 'Suffix', 'custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('bday_m', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
			$this->form_validation->set_rules('bday_d', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
			$this->form_validation->set_rules('bday_y', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
			$this->form_validation->set_rules('guardian_id', 'Guardian', 'is_in_db[guardians.id]|trim|htmlspecialchars');
			$this->form_validation->set_rules('class_id', 'Class', 'is_valid[classes.id]|trim|htmlspecialchars');
			$this->form_validation->set_rules('contact_number', 'Contact Number', 'required|is_unique_edit[teachers.contact_number.teacher_id]|numeric|min_length[11]|max_length[11]trim|htmlspecialchars');

			$this->form_validation->set_message('is_in_db', 'An Error has occured please refresh the page and try again.');



			$has_uploaded_pic = FALSE;
			//uploads files
			if($_FILES['teacher_photo']["error"]==0){

				$filename_first_name_array = explode(" ", $this->input->post("first_name"));
				$filename_first_name = implode("-", $filename_first_name_array);

				$filename_middle_name_array = explode(" ", $this->input->post("middle_name"));
				$filename_middle_name = implode("-", $filename_middle_name_array);

				$filename_last_name_array = explode(" ", $this->input->post("last_name"));
				$filename_last_name = implode("-", $filename_last_name_array);

				$filename_suffix_array = explode(" ", $this->input->post("suffix"));
				$filename_suffix = implode("-", $filename_suffix_array);

				$filename_full_name = $filename_last_name."_".$filename_first_name."_".$filename_middle_name."_".$filename_suffix;

				$get_data = array();
				$get_data["ref_id"] = $this->input->post("teacher_id");
				$get_data["ref_table"] = "teachers";
				$rfid_data = $this->rfid_model->get_data($get_data);
				

				$filename = $filename_full_name;



				$config['overwrite'] = TRUE;
				$config['upload_path'] = './assets/images/teacher_photo/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['file_name'] = $filename;
				$config['max_size']	= '20480';
				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload("teacher_photo"))
				{
					$data["is_valid_photo"] = FALSE;
					$data["photo_error"] = $this->upload->display_errors("","");
				}
				else
				{
					$data["photo_error"] = "";
					$data["is_valid_photo"] = TRUE;
					$image_data = $this->upload->data();
					$filename = $filename.$image_data["file_ext"];
					$image_data = $this->upload->data();
					$config['image_library'] = 'gd2';
					$config['source_image'] = $image_data["full_path"]; 
					$full_path = $image_data["full_path"];
					$file_path = $image_data["file_path"];
					$file_name = $image_data["file_name"];
					$config['create_thumb'] = FALSE;
					$config['new_image'] = $filename;
					$config['maintain_ratio'] = TRUE;
					$config['width']     = 360;
					$config['height']   = 360;
					$this->load->library('image_lib', $config); 
					$this->image_lib->resize();
					$this->image_lib->clear();

					$has_uploaded_pic = TRUE;
				}
			}else{
				$data["is_valid_photo"] = TRUE;
				$filename = "empty.jpg";

				$get_data = array();
				$get_data["id"] = $this->input->post("teacher_id");
				$teacher_data_db = $this->teachers_model->get_data($get_data);
				$filename = $teacher_data_db["display_photo"];
			}



			if ($this->form_validation->run() == FALSE|| $data["is_valid_photo"] == FALSE)
			{
				$data["is_valid"] = FALSE;
				$data["first_name_error"] = form_error('first_name');
				$data["gender_error"] = form_error('gender');
				$data["last_name_error"] = form_error('last_name');
				$data["address_error"] = form_error('address');
				$data["middle_name_error"] = form_error('middle_name');
				$data["suffix_error"] = form_error('suffix');
				$data["contact_number_error"] = form_error('contact_number');
				$data["bday_error"] = form_error('bday_m');
				$data["guardian_id_error"] = form_error('guardian_id');
				$data["class_id_error"] = form_error('class_id');
				$data["teacher_id_error"] = form_error('teacher_id');
				// echo json_encode($data);
			}
			else
			{
				$data["is_valid"] = TRUE;
				$data["gender_error"] = "";
				$data["first_name_error"] = "";
				$data["address_error"] = "";
				$data["last_name_error"] = "";
				$data["middle_name_error"] = "";
				$data["suffix_error"] = "";
				$data["contact_number_error"] = "";
				$data["bday_error"] = "";
				$data["guardian_id_error"] = "";
				$data["class_id_error"] = "";

				$teacher_data["gender"] = $this->input->post("gender");
				$teacher_data["first_name"] = $this->input->post("first_name");
				$teacher_data["address"] = $this->input->post("address");
				$teacher_data["last_name"] = $this->input->post("last_name");
				$teacher_data["middle_name"] = $this->input->post("middle_name");
				$teacher_data["suffix"] = $this->input->post("suffix");
				$teacher_data["contact_number"] = $this->input->post("contact_number");
				// $teacher_data["class_id"] = $this->input->post("class_id");
				$teacher_data["guardian_id"] = $this->input->post("guardian_id");
				$teacher_data["display_photo"] = $filename;
				// $teacher_data["display_photo_type"] = $new_image_data['file_type'];
				$bday_m = sprintf("%02d",$this->input->post("bday_m"));
				$bday_d = sprintf("%02d",$this->input->post("bday_d"));
				$bday_y = sprintf("%04d",$this->input->post("bday_y"));
				$birthdate_str = $bday_m."/".$bday_d."/".$bday_y;
				$teacher_data["birthdate"] = strtotime($birthdate_str);


				$get_data = array();
				$get_data["id"] = $this->input->post("teacher_id");
				$teacher_data_db = $this->teachers_model->get_data($get_data);

				if($teacher_data_db["contact_number"]!=$this->input->post("contact_number")){
					$password = random_string('alnum', 8);
					$message = "Your account details as teacher are:
Login: ".$teacher_data["contact_number"]."
Password: ".$password."
You can login to ".base_url("teacher");
					$sms_code = send_sms($this->input->post("contact_number"),$message);
					$teacher_data["password"] = md5($password);
					if($sms_code==0){
						$this->teachers_model->edit_info($teacher_data,$this->input->post("teacher_id"));
					}else{
						$data["is_successful"] = FALSE;
						$data["is_valid"] = FALSE;
						$data["contact_number_error"] = sms_status($sms_code);
					}
				}else{
					$this->teachers_model->edit_info($teacher_data,$this->input->post("teacher_id"));
				}


				

				if($has_uploaded_pic){
					$teacher_id = $this->input->post("teacher_id");
					rename($full_path,$file_path.$teacher_id."_".$file_name);
					$edit_data = array();
					$edit_data["display_photo"] = $teacher_id."_".$file_name;
					$this->teachers_model->edit_info($edit_data,$teacher_id);
				}

				$teacher_id = ($this->input->post("teacher_id")?$this->input->post("teacher_id"):"0");
				$class_id = ($this->input->post("class_id")?$this->input->post("class_id"):"0");
				$this->classes_model->change_class($teacher_id,$class_id);

			}
			// var_dump($this->teachers_model->edit_info($teacher_data,$this->input->post("teacher_id")));
			// var_dump($data);
			// exit;
			echo json_encode($data);

			
		}

	}

	public function get_data($arg='')
	{
		if($arg=="jbtech"){
			$teacher_data["id"] = $this->input->get("teacher_id");
			$teacher_data = $this->teachers_model->get_data($teacher_data);
			$teacher_data["id"] = sprintf("%03d",$this->input->get("teacher_id"));
			$teacher_data["birthday"] = date("m/d/Y",$teacher_data["birthdate"]);
			$teacher_data["age"] = age($teacher_data["birthdate"]);
			$teacher_data["full_name"] = $teacher_data["first_name"]." ".$teacher_data["middle_name"][0].". ".$teacher_data["last_name"]." ".$teacher_data["suffix"];

			if($teacher_data["class_id"] != 0){
				$get_data = array();
				$get_data["id"] = $teacher_data["class_id"];
				$teacher_data["class_data"] = $this->classes_model->get_data($get_data);
				$teacher_data["class_name"] = $teacher_data["class_data"]["class_name"];
				// $teacher_data["grade"] = $teacher_data["class_data"]["grade"];
			}else{
				$teacher_data["class_name"] = "";
				// $teacher_data["grade"] = "";
			}


			echo json_encode($teacher_data);
		}else{
			$teacher_data["id"] = $this->input->get("teacher_id");
			$teacher_data = $this->teachers_model->get_data($teacher_data);
			$teacher_data["bday_m"] = date("n",$teacher_data["birthdate"]);
			$teacher_data["bday_d"] = date("j",$teacher_data["birthdate"]);
			$teacher_data["bday_y"] = date("Y",$teacher_data["birthdate"]);
			($teacher_data["class_id"]==0?$teacher_data["class_id"]="":FALSE);
			echo json_encode($teacher_data);
		}
	}

	public function get_list($arg='')
	{
		if($arg=="admin"){
			$data = $this->teachers_model->get_list();
			echo json_encode($data["result"]);
		}elseif ($arg=="jbtech") {
			$where["deleted"] = 0;
			$where["rfid_status"] = 0;
			$data = $this->teachers_model->get_list($where);
			echo json_encode($data["result"]);
		}
	}

	public function delete()
	{
		if($_POST){
			$data = array();
			$data["deleted"] = 1;
			$data["class_id"] = 0;
			$this->teachers_model->delete($data,$this->input->post("id"));

			$data = array();
			$data["deleted"] = 1;
			$edit_data["ref_id"] = $this->input->post("id");
			$edit_data["ref_table"] = "teachers";
			$this->rfid_model->edit_info($data,$edit_data);


		}
	}

	public function applogin($arg='')
	{
		if($_POST){
			$this->form_validation->set_rules('account', 'Account', 'required|min_length[5]|max_length[12]|is_valid[teachers.contact_number]|trim|htmlspecialchars');
			$this->form_validation->set_rules('account_password', 'Password', 'required|min_length[5]|max_length[12]|trim|htmlspecialchars');
			$this->form_validation->set_message('is_valid', 'This account is invalid');

			if ($this->form_validation->run() == FALSE)
			{
				$data["is_valid"] = FALSE;
				$data["account_error"] = form_error('account');
				$data["account_password_error"] = form_error('account_password');
			}
			else
			{
				$data["contact_number"] = $account_id = $this->input->post("account");
				$data["password"] = md5($account_password = $this->input->post("account_password"));
				// $data["var_dump"] = $this->teachers_model->login($data);
				$data["is_valid"] = $this->teachers_model->login($data);
				$data["account_error"] = "";
				
				if($data["is_valid"]){
					$data["account_password_error"] = "";
					$data["redirect"] = base_url("teacher");


				}else{
					$data["account_password_error"] = "Incorrect Passord. Try Again.";
					$data["redirect"] = "";
				}
			}

			echo json_encode($data);
		}
	}


	public function reset_password($arg='')
	{
		$teacher_id = $this->input->post("id");
		$get_data["id"] = $teacher_id;
		$teacher_data = $this->teachers_model->get_data($get_data);

		$password = random_string('alnum', 8);
		// echo "akjsndakjsdnjaksdnjkasndajkdansdasjnjkasdkj";
		$message = "Your account details as teacher are:
Login: ".$teacher_data["contact_number"]."
Password: ".$password."
You can login to ".base_url("teacher");
		$sms_status_code = send_sms($teacher_data["contact_number"],$message);
		if($sms_status_code=="0"){
			$update["password"] = md5($password);
			$data = $this->teachers_model->edit_info($update,$teacher_id);
			$data->is_successful = TRUE;
			echo json_encode($data);
		}else{
			$data["is_successful"] = FALSE;
			$data["error"] = sms_status($sms_status_code);
			echo json_encode($data);
		}
	}


}