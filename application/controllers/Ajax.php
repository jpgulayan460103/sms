<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {

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
		$this->load->model("students_model");
		$this->load->model("gate_logs_model");
		$this->load->model("canteen_model");
		$this->load->model("canteen_items_model");

		$this->load->library('form_validation');
		$this->load->library('session');
		
		$this->form_validation->set_error_delimiters('', '');
	}

	public function index()
	{
		show_404();
	}
	/* admin ajax*/
	public function admin($arg='')
	{/*
		if($arg=="applogin"){
			if($_POST){
				$this->form_validation->set_rules('account', 'Account', 'required|min_length[5]|max_length[12]|is_in_db[admins.username]|trim|htmlspecialchars');
				$this->form_validation->set_rules('account_password', 'Password', 'required|min_length[5]|max_length[12]|trim|htmlspecialchars');
				$this->form_validation->set_message('is_in_db', 'This account is invalid');

				if ($this->form_validation->run() == FALSE)
				{
					$data["is_valid"] = FALSE;
					$data["account_error"] = form_error('account');
					$data["account_password_error"] = form_error('account_password');
				}
				else
				{
					$data["username"] = $account_id = $this->input->post("account");
					$data["password"] = md5($account_password = $this->input->post("account_password"));
					// $data["var_dump"] = $this->admin_model->login($data);
					$data["is_valid"] = $this->admin_model->login($data);
					$data["account_error"] = "";
					
					if($data["is_valid"]){
						$data["account_password_error"] = "";
						$data["redirect"] = base_url("Admin");


					}else{
						$data["account_password_error"] = "Incorrect Passord. Try Again.";
						$data["redirect"] = "";
					}
				}

				echo json_encode($data);
			}
		}
	*/}

	/* student ajax */
	public function student($arg='')
	{/*
		if($arg=="add"){
			if($_POST){

				// $data["guardian_id"] = $this->input->post("guardian_id");
				// $data["class_id"] = $this->input->post("class_id");

				$this->form_validation->set_rules('first_name', 'First Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
				$this->form_validation->set_rules('last_name', 'Last Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
				$this->form_validation->set_rules('middle_name', 'Middle Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
				$this->form_validation->set_rules('suffix', 'Suffix', 'custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
				$this->form_validation->set_rules('bday_m', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
				$this->form_validation->set_rules('bday_d', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
				$this->form_validation->set_rules('bday_y', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
				$this->form_validation->set_rules('guardian_id', 'Guardian', 'is_in_db[guardians.id]|trim|htmlspecialchars');

				$this->form_validation->set_message('is_in_db', 'This account is invalid');




				//uploads files
				if($_FILES['student_photo']["error"]==0){

					$filename_first_name_array = explode(" ", $this->input->post("first_name"));
					$filename_first_name = implode("-", $filename_first_name_array);

					$filename_middle_name_array = explode(" ", $this->input->post("middle_name"));
					$filename_middle_name = implode("-", $filename_middle_name_array);

					$filename_last_name_array = explode(" ", $this->input->post("last_name"));
					$filename_last_name = implode("-", $filename_last_name_array);

					$filename_suffix_array = explode(" ", $this->input->post("suffix"));
					$filename_suffix = implode("-", $filename_suffix_array);

					$filename_full_name = $filename_last_name."_".$filename_first_name."_".$filename_middle_name."_".$filename_suffix;

					$filename = $this->session->userdata("rfid_scanned_addstudent")."_".$filename_full_name;



					$config['overwrite'] = TRUE;
					$config['upload_path'] = './assets/images/student_photo/';
					$config['allowed_types'] = 'gif|jpg|png';
					$config['file_name'] = $filename;
					$config['max_size']	= '20480';
					$this->load->library('upload', $config);
					if ( ! $this->upload->do_upload("student_photo"))
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
					$data["guardian_id_error"] = form_error('guardian_id');
						$config['create_thumb'] = FALSE;
						$config['new_image'] = $filename;
						$config['maintain_ratio'] = TRUE;
						$config['width']     = 360;
						$config['height']   = 360;
						$this->load->library('image_lib', $config); 
						$this->image_lib->resize();
						$this->image_lib->clear();
					}
				}else{
					$data["is_valid_photo"] = TRUE;
					$filename = "empty.jpg";				
				}

				if ($this->form_validation->run() == FALSE|| $data["is_valid_photo"] == FALSE)
				{
					$data["is_valid"] = FALSE;
					$data["first_name_error"] = form_error('first_name');
					$data["last_name_error"] = form_error('last_name');
					$data["middle_name_error"] = form_error('middle_name');
					$data["suffix_error"] = form_error('suffix');
					$data["bday_error"] = form_error('bday_m');
					$data["guardian_id_error"] = form_error('guardian_id');
				}
				else
				{
					$data["is_valid"] = TRUE;
					$data["first_name_error"] = "";
					$data["last_name_error"] = "";
					$data["middle_name_error"] = "";
					$data["suffix_error"] = "";
					$data["bday_error"] = "";
					$data["guardian_id_error"] = "";

					$student_data["first_name"] = $this->input->post("first_name");
					$student_data["last_name"] = $this->input->post("last_name");
					$student_data["middle_name"] = $this->input->post("middle_name");
					$student_data["suffix"] = $this->input->post("suffix");
					$student_data["display_photo"] = $filename;
					// $student_data["display_photo_type"] = $new_image_data['file_type'];
					$bday_m = sprintf("%02d",$this->input->post("bday_m"));
					$bday_d = sprintf("%02d",$this->input->post("bday_d"));
					$bday_y = sprintf("%04d",$this->input->post("bday_y"));
					$birthdate_str = $bday_m."/".$bday_d."/".$bday_y;
					$student_data["birthdate"] = strtotime($birthdate_str);

					$student_data["rfid"] = $this->session->userdata("rfid_scanned_addstudent");
					($this->students_model->add($student_data)?$data["is_successful"] = TRUE:$data["is_successful"] = FALSE);

				}

				echo json_encode($data);
			}
		}elseif ($arg=="edit") {
			if($_POST){


				// $data["guardian_id"] = $this->input->post("guardian_id");
				// $data["class_id"] = $this->input->post("class_id");

				$this->form_validation->set_rules('student_id', 'First Name', 'required|trim|htmlspecialchars|is_in_db[students.id]');
				$this->form_validation->set_rules('first_name', 'First Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
				$this->form_validation->set_rules('last_name', 'Last Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
				$this->form_validation->set_rules('middle_name', 'Middle Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
				$this->form_validation->set_rules('suffix', 'Suffix', 'custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
				$this->form_validation->set_rules('bday_m', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
				$this->form_validation->set_rules('bday_d', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
				$this->form_validation->set_rules('bday_y', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
				$this->form_validation->set_rules('guardian_id', 'Guardian', 'is_in_db[guardians.id]|trim|htmlspecialchars');

				$this->form_validation->set_message('is_in_db', 'An Error has occured please refresh the page and try again.');




				//uploads files
				if($_FILES['student_photo']["error"]==0){

					$filename_first_name_array = explode(" ", $this->input->post("first_name"));
					$filename_first_name = implode("-", $filename_first_name_array);

					$filename_middle_name_array = explode(" ", $this->input->post("middle_name"));
					$filename_middle_name = implode("-", $filename_middle_name_array);

					$filename_last_name_array = explode(" ", $this->input->post("last_name"));
					$filename_last_name = implode("-", $filename_last_name_array);

					$filename_suffix_array = explode(" ", $this->input->post("suffix"));
					$filename_suffix = implode("-", $filename_suffix_array);

					$filename_full_name = $filename_last_name."_".$filename_first_name."_".$filename_middle_name."_".$filename_suffix;

					$filename = $this->session->userdata("rfid_scanned_addstudent")."_".$filename_full_name;



					$config['overwrite'] = TRUE;
					$config['upload_path'] = './assets/images/student_photo/';
					$config['allowed_types'] = 'gif|jpg|png';
					$config['file_name'] = $filename;
					$config['max_size']	= '20480';
					$this->load->library('upload', $config);
					if ( ! $this->upload->do_upload("student_photo"))
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
					$data["guardian_id_error"] = form_error('guardian_id');
						$config['create_thumb'] = FALSE;
						$config['new_image'] = $filename;
						$config['maintain_ratio'] = TRUE;
						$config['width']     = 360;
						$config['height']   = 360;
						$this->load->library('image_lib', $config); 
						$this->image_lib->resize();
						$this->image_lib->clear();
					}
				}else{
					$data["is_valid_photo"] = TRUE;
					$filename = "empty.jpg";
					$get_data["id"] = $this->input->post("student_id");
					$student_data_db = $this->students_model->get_data($get_data);
					$filename = $student_data_db["display_photo"];
				}

				if ($this->form_validation->run() == FALSE|| $data["is_valid_photo"] == FALSE)
				{
					$data["is_valid"] = FALSE;
					$data["first_name_error"] = form_error('first_name');
					$data["last_name_error"] = form_error('last_name');
					$data["middle_name_error"] = form_error('middle_name');
					$data["suffix_error"] = form_error('suffix');
					$data["bday_error"] = form_error('bday_m');
					$data["guardian_id_error"] = form_error('guardian_id');
					$data["student_id_error"] = form_error('student_id');
					// echo json_encode($data);
				}
				else
				{
					$data["is_valid"] = TRUE;
					$data["first_name_error"] = "";
					$data["last_name_error"] = "";
					$data["middle_name_error"] = "";
					$data["suffix_error"] = "";
					$data["bday_error"] = "";
					$data["guardian_id_error"] = "";

					$student_data["first_name"] = $this->input->post("first_name");
					$student_data["last_name"] = $this->input->post("last_name");
					$student_data["middle_name"] = $this->input->post("middle_name");
					$student_data["suffix"] = $this->input->post("suffix");
					$student_data["guardian_id"] = $this->input->post("guardian_id");
					$student_data["display_photo"] = $filename;
					// $student_data["display_photo_type"] = $new_image_data['file_type'];
					$bday_m = sprintf("%02d",$this->input->post("bday_m"));
					$bday_d = sprintf("%02d",$this->input->post("bday_d"));
					$bday_y = sprintf("%04d",$this->input->post("bday_y"));
					$birthdate_str = $bday_m."/".$bday_d."/".$bday_y;
					$student_data["birthdate"] = strtotime($birthdate_str);

					($this->students_model->edit_info($student_data,$this->input->post("student_id"))?$data["is_successful"] = TRUE:$data["is_successful"] = FALSE);

				}
				// var_dump($this->students_model->edit_info($student_data,$this->input->post("student_id")));
				echo json_encode($data);

				
			}

		}elseif ($arg=="get_data") {
			if($_POST){
				$student_data["id"] = $_POST["student_id"];
				$student_data = $this->students_model->get_data($student_data);
				$student_data["bday_m"] = date("n",$student_data["birthdate"]);
				$student_data["bday_d"] = date("d",$student_data["birthdate"]);
				$student_data["bday_y"] = date("Y",$student_data["birthdate"]);
				($student_data["guardian_id"]==0?$student_data["guardian_id"]="":FALSE);
				echo json_encode($student_data);
			}
		}elseif ($arg=="addloadstudent") {
			$data["id"] = $this->input->post("addloadstudent_student_id");
			$data["load_credits"] = $this->input->post("load_credits");
			echo json_encode($this->students_model->load_credits($data));
		}

	*/}


	/* for rfid ajax */
	public function rfid($arg='',$arg2='')
	{/*
		if($arg=="addstudent"){
			if($_POST){
				$rfid_scan_addstudent = $this->input->post("rfid_scan_addstudent");

				$this->form_validation->set_rules('rfid_scan_addstudent', 'RFID', 'required|numeric|is_available[students.rfid]|trim|htmlspecialchars');

				if ($this->form_validation->run() == FALSE)
				{
					$data["is_valid"] = FALSE;
					$data["rfid_scanned_addstudent"] = "";
				}else{
					$data["is_valid"] = TRUE;
					$data["rfid_scanned_addstudent"] = $rfid_scan_addstudent;
					$this->session->set_userdata("rfid_scanned_addstudent",$rfid_scan_addstudent);
				}
				echo json_encode($data);
			}
		}elseif ($arg=="scangate") {
			if($_POST){
				$this->form_validation->set_rules('rfid_scan_gate', 'RFID', 'required|numeric|trim|htmlspecialchars|is_valid_rfid');

				if ($this->form_validation->run() == FALSE)
				{
					$student_data["is_valid"] = FALSE;
					$student_data["display_photo"] = base_url("assets/images/empty.jpg");
				}else{
					$get_data_rules["rfid"] = $this->input->post("rfid_scan_gate"); 
					$get_data_rules["deleted"] = 0; 
					$student_data = $this->students_model->get_data($get_data_rules);
					$student_data["is_valid"] = TRUE;
					$student_data["display_photo"] = base_url("assets/images/student_photo/".$student_data["display_photo"]);
					$student_data["full_name"] = $student_data["first_name"]." ".$student_data["middle_name"][0].". ".$student_data["last_name"];
					$student_data["birthdate"] = date("m/d/Y",$student_data["birthdate"]);

					$student_log_data["student_id"] = $student_data["id"];
					$student_log_data["type"] = $arg2;
					$student_log_data["rfid"] = $this->input->post("rfid_scan_gate");
					$student_log_data["date_time"] = strtotime(date("m/d/Y H:i:s A"));

					$this->gate_logs_model->add($student_log_data);
				}
				echo json_encode($student_data);
			}
		}elseif ($arg=="addloadstudent") {
			if($_POST){
				$this->form_validation->set_rules('rfid_scan_addloadstudent', 'RFID', 'required|numeric|trim|htmlspecialchars|is_valid_rfid');
				if ($this->form_validation->run() == FALSE)
				{
					$student_data["is_valid"] = FALSE;
				}else{
					$get_data["rfid"] = $this->input->post("rfid_scan_addloadstudent");
					$get_data["valid"] = 1;
					$get_data["deleted"] = 0;
					$student_data = $this->students_model->get_data($get_data);
					$student_data["is_valid"] = TRUE;
					$student_data["load_credits"] = number_format($student_data["load_credits"],2);
					$student_data["display_photo"] = base_url("assets/images/student_photo/".$student_data["display_photo"]);
					$student_data["full_name"] = $student_data["last_name"].", ".$student_data["first_name"]." ".$student_data["middle_name"][0].". ".$student_data["suffix"];
					$student_data["guardian_data"] = $this->guardian_model->get_data($student_data["guardian_id"]);
				}
				echo json_encode($student_data);
			}
		}elseif ($arg=="canteen") {
			$canteen_sales_cart = $this->session->userdata("canteen_sales_cart");
			$canteen_user_data = $this->session->userdata("canteen_sessions");
			if($arg2=="sales"){
				$this->form_validation->set_rules('rfid_scan', 'RFID', 'required|numeric|trim|htmlspecialchars|is_valid_rfid');
				if ($this->form_validation->run() == FALSE)
				{
					$student_data["is_valid"] = FALSE;
				}else{
					$get_data["rfid"] = $this->input->post("rfid_scan");
					$get_data["valid"] = 1;
					$get_data["deleted"] = 0;
					$student_data = $this->students_model->get_data($get_data);
					$student_data["is_valid"] = TRUE;
					$student_data["load_credits"] = number_format($student_data["load_credits"],2);
					$student_data["display_photo"] = base_url("assets/images/student_photo/".$student_data["display_photo"]);
					$student_data["full_name"] = $student_data["last_name"].", ".$student_data["first_name"]." ".$student_data["middle_name"][0].". ".$student_data["suffix"];
					$student_data["guardian_data"] = $this->guardian_model->get_data($student_data["guardian_id"]);
				}
				echo json_encode($student_data);
			}
		}
	*/}

	/* guardian ajax */
	public function guardian($arg='')
	{/*


		if($arg=="register"){
			if($_POST){
				$this->form_validation->set_rules('email_address', 'Email Address', 'required|valid_email|trim|htmlspecialchars|is_available[guardians.email_address]|min_length[2]|max_length[50]');
				$this->form_validation->set_rules('guardian_name', 'Guardian Name', 'required|custom_alpha_dash|trim|htmlspecialchars|min_length[2]|max_length[50]');
				$this->form_validation->set_rules('contact_number', 'Contact Number', 'trim|htmlspecialchars|min_length[2]|max_length[50]');
				$this->form_validation->set_message('is_available', 'This Email is invalid or already taken');

				if ($this->form_validation->run() == FALSE)
				{
					$data["is_valid"] = FALSE;
					$data["guardian_name_error"] = form_error("guardian_name");
					$data["email_address_error"] = form_error("email_address");
					$data["contact_number_error"] = form_error("contact_number");
				}else{
					$data["is_valid"] = TRUE;
					$data["guardian_name_error"] = "";
					$data["email_address_error"] = "";
					$data["contact_number_error"] = "";

					($this->input->post("email_subscription")!=NULL?$email_subscription=1:$email_subscription=0);
					($this->input->post("sms_subscription")!=NULL?$sms_subscription=1:$sms_subscription=0);

					$guardian_data["name"] = $this->input->post("guardian_name");
					$guardian_data["email_address"] = $this->input->post("email_address");
					$guardian_data["contact_number"] = $this->input->post("contact_number");
					$guardian_data["sms_subscription"] = $sms_subscription;
					$guardian_data["email_subscription"] = $email_subscription;
					$guardian_data["password"] = random_string('alnum', 8);
					$this->guardian_model->add($guardian_data);



				}
				echo json_encode($data);
			}
		}elseif ($arg=="applogin") {
			if($_POST){
				$this->form_validation->set_rules('account', 'Account', 'required|min_length[5]|max_length[50]|is_in_db[guardians.email_address]|trim|htmlspecialchars');
				$this->form_validation->set_rules('account_password', 'Password', 'required|min_length[5]|max_length[50]|trim|htmlspecialchars');
				$this->form_validation->set_message('is_in_db', 'This account is invalid');

				if ($this->form_validation->run() == FALSE)
				{
					$data["is_valid"] = FALSE;
					$data["account_error"] = form_error('account');
					$data["account_password_error"] = form_error('account_password');
				}
				else
				{
					$login_data["email_address"] = $account_id = $this->input->post("account");
					$login_data["password"] = $account_password = $this->input->post("account_password");
					$login_data["deleted"] = 0;

					$data["is_valid"] = $this->guardian_model->login($login_data);
					$data["account_error"] = "";
					
					if($data["is_valid"]){
						$data["account_password_error"] = "";
						$data["redirect"] = base_url("home");
					}else{
						$data["account_password_error"] = "Incorrect Passord. Try Again.";
						$data["redirect"] = "";
					}
				}

				echo json_encode($data);
			}
		}
	*/}

	/* canteen ajax */
	public function canteen($arg='',$arg2='')
	{/*

		$canteen_user_data = $this->session->userdata("canteen_sessions");
		if($arg=="applogin"){
			$this->form_validation->set_rules('account', 'Account', 'required|min_length[5]|max_length[50]|is_in_db[canteen_users.username]|trim|htmlspecialchars');
			$this->form_validation->set_rules('account_password', 'Password', 'required|min_length[5]|max_length[50]|trim|htmlspecialchars');
			$this->form_validation->set_message('is_in_db', 'This account is invalid');

			if ($this->form_validation->run() == FALSE)
			{
				$data["is_valid"] = FALSE;
				$data["account_error"] = form_error('account');
				$data["account_password_error"] = form_error('account_password');
			}
			else
			{
				$login_data["username"] = $account_id = $this->input->post("account");
				$login_data["password"] = $account_password = $this->input->post("account_password");
				$login_data["deleted"] = 0;

				$data["is_valid"] = $this->canteen_model->login($login_data);
				$data["account_error"] = "";
				
				if($data["is_valid"]){
					$data["account_password_error"] = "";
					$data["redirect"] = base_url("canteen");
				}else{
					$data["account_password_error"] = "Incorrect Passord. Try Again.";
					$data["redirect"] = "";
				}
			}

			echo json_encode($data);
		}elseif ($arg=="items") {
			if($arg2=="add"){

				$this->form_validation->set_rules('category', 'Category', 'required|min_length[2]|max_length[50]|trim|htmlspecialchars');
				$this->form_validation->set_rules('item_name', 'Item Name', 'required|min_length[2]|max_length[50]|trim|htmlspecialchars');
				$this->form_validation->set_rules('cost_price', 'Cost Price', 'required|max_length[50]|trim|htmlspecialchars');
				$this->form_validation->set_rules('selling_price', 'Selling Price', 'required|max_length[50]|trim|htmlspecialchars');


				if ($this->form_validation->run() == FALSE)
				{
					$data["is_valid"] = FALSE;
					$data["category_error"] = form_error('category');
					$data["item_name_error"] = form_error('item_name');
					$data["cost_price_error"] = form_error('cost_price');
					$data["selling_price_error"] = form_error('selling_price');
				}
				else
				{
					$data["is_valid"] = TRUE;
					$data["category_error"] = form_error('category');
					$data["item_name_error"] = form_error('item_name');
					$data["cost_price_error"] = form_error('cost_price');
					$data["selling_price_error"] = form_error('selling_price');


					$item_data["category"] = $this->input->post("category");
					$item_data["item_name"] = $this->input->post("item_name");
					$item_data["cost_price"] = $this->input->post("cost_price");
					$item_data["selling_price"] = $this->input->post("selling_price");
					$item_data["canteen_id"] = $canteen_user_data->canteen_id;
					$this->canteen_items_model->add($item_data);



				}
				echo json_encode($data);
			}
		}elseif ($arg=="sales") {
			if($arg2=="add_items_to_cart"){
				$index = $this->input->post("item_id");
				$get_data["id"] = $index;
				$canteen_sales_data["items"]["item_".$index] = $this->canteen_items_model->get_data($get_data,TRUE);
				$canteen_sales_data["items"]["item_".$index]["quantity"] = 1;
				$canteen_sales_cart = $this->session->userdata("canteen_sales_cart");
				if(isset($canteen_sales_cart["items"])){
					$canteen_sales_cart = $this->session->userdata("canteen_sales_cart");
					if(isset($canteen_sales_cart["items"]["item_".$index])){
						$canteen_sales_cart["items"]["item_".$index]["quantity"]++;
					}
					$canteen_sales_cart["items"] = array_merge($canteen_sales_data["items"],$canteen_sales_cart["items"]);
					$this->session->set_userdata("canteen_sales_cart",$canteen_sales_cart);
				}else{
					$this->session->set_userdata("canteen_sales_cart",$canteen_sales_data);
				}
			}elseif ($arg2=="delete_items_to_cart") {
				$index = $this->input->post("item_id");
				$canteen_sales_cart = $this->session->userdata("canteen_sales_cart");
				unset($canteen_sales_cart["items"][$index]);
				$this->session->set_userdata("canteen_sales_cart",$canteen_sales_cart);

			}elseif ($arg2=="edit_quantity_items_to_cart") {
				$index = $this->input->post("item_id");
				$canteen_sales_cart = $this->session->userdata("canteen_sales_cart");
				if(isset($canteen_sales_cart["items"][$index])){
					$canteen_sales_cart["items"][$index]["quantity"] = $this->input->post("quantity");
					$data["line_total"] = number_format($canteen_sales_cart["items"][$index]["quantity"]*$canteen_sales_cart["items"][$index]["selling_price"],2);
					$this->session->set_userdata("canteen_sales_cart",$canteen_sales_cart);
					$total = 0;
					foreach ($canteen_sales_cart["items"] as $canteen_sales_data) {
						$line_total = $canteen_sales_data["quantity"]*$canteen_sales_data["selling_price"];
						$total += $line_total;
					}
					$data["total"] = number_format($total,2);
					echo json_encode($data);
				}
			}elseif ($arg2=="submit") {
				$canteen_sales_cart = $this->session->userdata("canteen_sales_cart");
				$total = 0;
				if($canteen_sales_cart["items"]===array()){
					$data["is_valid"] = FALSE;
					$data["error"] = "The Sales Cart is empty.";
					var_dump($data);
					exit;
				}
				foreach ($canteen_sales_cart["items"] as $canteen_sales_cart_data) {
					$line_total = $canteen_sales_cart_data["selling_price"]*$canteen_sales_cart_data["quantity"];
					$total += $line_total;
				}
				if(isset($canteen_sales_cart["customer_data"]["id"])){
					$get_data["id"] = $canteen_sales_cart["customer_data"]["id"];
					$student_data = $this->students_model->get_data($get_data);

					if($student_data["load_credits"]>=$total){
						$data["is_valid"] = TRUE;
						$data["error"] = "";

					}else{
						$data["is_valid"] = FALSE;
						$data["error"] = "This student does not have sufficient load credits to purchase these items.";
					}
				}else{
					$data["is_valid"] = TRUE;
					$data["error"] = "";
					
				}
				$data["is_sale_items_valid"] = $this->canteen_model->sale_items_is_valid($canteen_sales_cart["items"]);

				$this->form_validation->set_rules('customer_name', 'Customer Name', 'required|max_length[50]|trim|htmlspecialchars');
				$this->form_validation->set_rules('comments', 'Comments', 'trim|htmlspecialchars');

				if ($this->form_validation->run() == FALSE || $data["is_valid"] == FALSE || $data["is_sale_items_valid"] == FALSE)
				{
					$data["is_valid"] = FALSE;
					$data["error"] = form_error("customer_name");
					if($data["is_sale_items_valid"] == FALSE){
						$data["error"] = "The quantity of items must not exceed the remaining stocks available.";
					}
				}else{
					$data["is_valid"] = TRUE;
					$data["error"] = form_error("comments");
					$sales_data = $this->canteen_model->sale($canteen_sales_cart);

				}

				var_dump($sales_data);
			}elseif ($arg2=="confirm_pin") {
				$canteen_sales_cart = $this->session->userdata("canteen_sales_cart");
				$get_data["id"] = $this->input->post("student_id");
				$entered_pin = $this->input->post("pin");
				$student_data = $this->students_model->get_data($get_data);
				$student_data["customer_name"] = $student_data["last_name"].", ".$student_data["first_name"][0].". ".$student_data["middle_name"][0].". ".$student_data["suffix"];
				$data["is_valid"] = FALSE;
				if($student_data["pin"]==$entered_pin){
					$canteen_sales_cart["customer_data"] = $student_data;
					// var_dump($canteen_sales_cart);
					$this->session->set_userdata("canteen_sales_cart",$canteen_sales_cart);
					$data["is_valid"] = TRUE;
				}
				echo json_encode($data);
			}elseif ($arg2=="remove_customer") {
				$canteen_sales_cart = $this->session->userdata("canteen_sales_cart");
				unset($canteen_sales_cart["customer_data"]);
				$this->session->set_userdata("canteen_sales_cart",$canteen_sales_cart);
			}elseif ($arg2=="add_customer") {
				$canteen_sales_cart = $this->session->userdata("canteen_sales_cart");
				$canteen_sales_cart["customer_data"]["customer_name"] = $this->input->post("customer_name");
				$this->session->set_userdata("canteen_sales_cart",$canteen_sales_cart);
			}elseif ($arg2=="add_comments") {
				$canteen_sales_cart = $this->session->userdata("canteen_sales_cart");
				$canteen_sales_cart["comments"] = $this->input->post("comments");
				$this->session->set_userdata("canteen_sales_cart",$canteen_sales_cart);
			}
		}
	*/}

}
