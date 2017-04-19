<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student_ajax extends CI_Controller {

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
		$this->load->model("classes_model");
		$this->load->model("teachers_model");
		$this->load->model("students_model");
		$this->load->model("gate_logs_model");
		$this->load->model("rfid_model");
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
	public function add($arg='')
	{
		
		if($_POST){

			$this->form_validation->set_rules('address', 'Address', 'required|min_length[2]|max_length[100]trim|htmlspecialchars');
			$this->form_validation->set_rules('gender', 'Gender', 'required|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('mothers_name', 'Mother&apos;s Name', 'max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('fathers_name', 'Father&apos;s Name', 'max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('first_name', 'First Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('middle_name', 'Middle Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('suffix', 'Suffix', 'custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('contact_number', 'Contact Number', 'numeric|min_length[11]|max_length[11]trim|htmlspecialchars');
			$this->form_validation->set_rules('bday_m', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
			$this->form_validation->set_rules('bday_d', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
			$this->form_validation->set_rules('bday_y', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
			$this->form_validation->set_rules('guardian_id', 'Guardian', 'is_in_db[guardians.id]|trim|htmlspecialchars');
			$this->form_validation->set_rules('class_id', 'Class', 'required|is_valid[classes.id]|trim|htmlspecialchars');

			$this->form_validation->set_message('is_in_db', 'This account is invalid');
			$has_uploaded_pic = FALSE;
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

				$filename = $filename_full_name;



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
				$data["address_error"] = form_error('address');
				$data["gender_error"] = form_error('gender');
				$data["mothers_name_error"] = form_error('mothers_name');
				$data["fathers_name_error"] = form_error('fathers_name');
				$data["first_name_error"] = form_error('first_name');
				$data["last_name_error"] = form_error('last_name');
				$data["middle_name_error"] = form_error('middle_name');
				$data["suffix_error"] = form_error('suffix');
				$data["bday_error"] = form_error('bday_m');
				$data["guardian_id_error"] = form_error('guardian_id');
				$data["class_id_error"] = form_error('class_id');
				$data["contact_number_error"] = form_error('contact_number');
			}
			else
			{
				$data["is_valid"] = TRUE;
				$data["address_error"] = "";
				$data["gender_error"] = "";
				$data["mothers_name_error"] = "";
				$data["fathers_name_error"] = "";
				$data["first_name_error"] = "";
				$data["last_name_error"] = "";
				$data["middle_name_error"] = "";
				$data["suffix_error"] = "";
				$data["bday_error"] = "";
				$data["guardian_id_error"] = "";
				$data["class_id_error"] = "";
				$data["contact_number_error"] = "";

				$student_data["first_name"] = $this->input->post("first_name");
				$student_data["mothers_name"] = $this->input->post("mothers_name");
				$student_data["fathers_name"] = $this->input->post("fathers_name");
				$student_data["last_name"] = $this->input->post("last_name");
				$student_data["address"] = $this->input->post("address");
				$student_data["gender"] = $this->input->post("gender");
				$student_data["middle_name"] = $this->input->post("middle_name");
				$student_data["suffix"] = $this->input->post("suffix");
				$student_data["contact_number"] = $this->input->post("contact_number");
				$student_data["guardian_id"] = $this->input->post("guardian_id");
				$student_data["class_id"] = $this->input->post("class_id");
				$student_data["display_photo"] = $filename;
				// $student_data["display_photo_type"] = $new_image_data['file_type'];
				$bday_m = sprintf("%02d",$this->input->post("bday_m"));
				$bday_d = sprintf("%02d",$this->input->post("bday_d"));
				$bday_y = sprintf("%04d",$this->input->post("bday_y"));
				$birthdate_str = $bday_m."/".$bday_d."/".$bday_y;
				$student_data["birthdate"] = strtotime($birthdate_str);

				// $student_data["rfid"] = $this->session->userdata("rfid_scanned_add");
				$data["is_successful"] = TRUE;
				$student_data = $this->students_model->add($student_data);

				if($has_uploaded_pic){
					rename($full_path,$file_path.$student_data->id."_".$file_name);
					$edit_data = array();
					$edit_data["display_photo"] = $student_data->id."_".$file_name;
					$this->students_model->edit_info($edit_data,$student_data->id);
				}


				// $rfid_data["rfid"] = $this->input->post("rfid");
				$rfid_data["ref_id"] = $student_data->id;
				$rfid_data["ref_table"] = "students";
				$rfid_data["valid"] = 1;
				$this->rfid_model->add($rfid_data);

			}

			echo json_encode($data);
		}
		
	}

	public function edit($arg='')
	{
		if($_POST){


			// $data["guardian_id"] = $this->input->post("guardian_id");
			// $data["class_id"] = $this->input->post("class_id");
			// var_dump($this->input->post("class_id"));
			// exit;

			$this->form_validation->set_rules('student_id', 'First Name', 'required|trim|htmlspecialchars|is_in_db[students.id]');
			$this->form_validation->set_rules('first_name', 'First Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('gender', 'Gender', 'required|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('address', 'Address', 'required|min_length[2]|max_length[100]trim|htmlspecialchars');
			$this->form_validation->set_rules('mothers_name', 'Mother&apos;s Name', 'max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('fathers_name', 'Father&apos;s Name', 'max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('middle_name', 'Middle Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('suffix', 'Suffix', 'custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('bday_m', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
			$this->form_validation->set_rules('bday_d', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
			$this->form_validation->set_rules('bday_y', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
			$this->form_validation->set_rules('contact_number', 'Contact Number', 'numeric|min_length[11]|max_length[11]trim|htmlspecialchars');
			$this->form_validation->set_rules('guardian_id', 'Guardian', 'is_in_db[guardians.id]|trim|htmlspecialchars');
			$this->form_validation->set_rules('class_id', 'Class', 'required|is_valid[classes.id]|trim|htmlspecialchars');

			$this->form_validation->set_message('is_in_db', 'An Error has occured please refresh the page and try again.');



			$has_uploaded_pic = FALSE;
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

				$get_data = array();
				$get_data["ref_id"] = $this->input->post("student_id");
				$get_data["ref_table"] = "students";
				$rfid_data = $this->rfid_model->get_data($get_data);
				

				$filename = $filename_full_name;



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
				$get_data["id"] = $this->input->post("student_id");
				$student_data_db = $this->students_model->get_data($get_data);
				$filename = $student_data_db["display_photo"];
			}

			if ($this->form_validation->run() == FALSE|| $data["is_valid_photo"] == FALSE)
			{
				$data["is_valid"] = FALSE;
				$data["address_error"] = form_error('address');
				$data["gender_error"] = form_error('gender');
				$data["mothers_name_error"] = form_error('mothers_name');
				$data["fathers_name_error"] = form_error('fathers_name');
				$data["first_name_error"] = form_error('first_name');
				$data["last_name_error"] = form_error('last_name');
				$data["middle_name_error"] = form_error('middle_name');
				$data["suffix_error"] = form_error('suffix');
				$data["contact_number_error"] = form_error('contact_number');
				$data["bday_error"] = form_error('bday_m');
				$data["guardian_id_error"] = form_error('guardian_id');
				$data["class_id_error"] = form_error('class_id');
				$data["student_id_error"] = form_error('student_id');
				// echo json_encode($data);
			}
			else
			{
				$data["is_valid"] = TRUE;
				$data["first_name_error"] = "";
				$data["address_error"] = "";
				$data["gender_error"] = "";
				$data["mothers_name_error"] = "";
				$data["fathers_name_error"] = "";
				$data["last_name_error"] = "";
				$data["middle_name_error"] = "";
				$data["suffix_error"] = "";
				$data["contact_number_error"] = "";
				$data["bday_error"] = "";
				$data["guardian_id_error"] = "";
				$data["class_id_error"] = "";

				$student_data["first_name"] = $this->input->post("first_name");
				$student_data["address"] = $this->input->post("address");
				$student_data["gender"] = $this->input->post("gender");
				$student_data["mothers_name"] = $this->input->post("mothers_name");
				$student_data["fathers_name"] = $this->input->post("fathers_name");
				$student_data["last_name"] = $this->input->post("last_name");
				$student_data["middle_name"] = $this->input->post("middle_name");
				$student_data["suffix"] = $this->input->post("suffix");
				$student_data["contact_number"] = $this->input->post("contact_number");
				$student_data["guardian_id"] = $this->input->post("guardian_id");
				$student_data["class_id"] = $this->input->post("class_id");
				$student_data["display_photo"] = $filename;
				// $student_data["display_photo_type"] = $new_image_data['file_type'];
				$bday_m = sprintf("%02d",$this->input->post("bday_m"));
				$bday_d = sprintf("%02d",$this->input->post("bday_d"));
				$bday_y = sprintf("%04d",$this->input->post("bday_y"));
				$birthdate_str = $bday_m."/".$bday_d."/".$bday_y;
				$student_data["birthdate"] = strtotime($birthdate_str);

				($this->students_model->edit_info($student_data,$this->input->post("student_id"))?$data["is_successful"] = TRUE:$data["is_successful"] = FALSE);

				if($has_uploaded_pic){
					$student_id = $this->input->post("student_id");
					rename($full_path,$file_path.$student_id."_".$file_name);
					$edit_data = array();
					$edit_data["display_photo"] = $student_id."_".$file_name;
					$this->students_model->edit_info($edit_data,$student_id);
				}

			}
			// var_dump($this->students_model->edit_info($student_data,$this->input->post("student_id")));
			echo json_encode($data);

			
		}

	}

	public function get_data($arg='')
	{


		if($arg=="jbtech"){
			
			$student_data["id"] = $this->input->get("student_id");
			$student_data = $this->students_model->get_data($student_data);
			$student_data["id"] = sprintf("%03d",$this->input->get("student_id"));
			$student_data["birthday"] = date("m/d/Y",$student_data["birthdate"]);
			$student_data["full_name"] = $student_data["first_name"]." ".$student_data["middle_name"][0].". ".$student_data["last_name"]." ".$student_data["suffix"];;
			$student_data["age"] = age($student_data["birthdate"]);

			if($student_data["guardian_id"] != 0){
				$get_data = array();
				$get_data["id"] = $student_data["guardian_id"];
				$student_data["guardian_data"] = $this->guardian_model->get_data($get_data);
				$student_data["guardian_name"] = $student_data["guardian_data"]["name"];
				$student_data["guardian_address"] = $student_data["guardian_data"]["guardian_address"];
				$student_data["guardian_contact_number"] = $student_data["guardian_data"]["contact_number"];
			}else{
				$student_data["guardian_name"] = "";
				$student_data["guardian_address"] = "";
				$student_data["guardian_contact_number"] = "";
			}

			if($student_data["class_id"] != 0){
				$get_data = array();
				$get_data["id"] = $student_data["class_id"];
				$student_data["class_data"] = $this->classes_model->get_data($get_data);
				$student_data["class_name"] = $student_data["class_data"]["class_name"];
				$student_data["grade"] = $student_data["class_data"]["grade"];

				if($student_data["class_data"]["teacher_id"] != 0){
					$get_data = array();
					$get_data["id"] = $student_data["class_data"]["teacher_id"];
					$student_data["class_data"]["teacher_data"] = $this->teachers_model->get_data($get_data);
					$student_data["class_adviser"] = $student_data["class_data"]["teacher_data"]["first_name"]." ".$student_data["class_data"]["teacher_data"]["middle_name"][0].". ".$student_data["class_data"]["teacher_data"]["last_name"]." ".$student_data["class_data"]["teacher_data"]["suffix"];
				}else{
					$student_data["class_adviser"] = "";
				}

			}else{
				$student_data["class_name"] = "";
				$student_data["grade"] = "";
				$student_data["class_adviser"] = "";
			}




			echo json_encode($student_data);





			// last_name
			// first_name
			// middle_name
			// suffix
			// gender
			// birthday
			// contact_number
			// address
			// guardian_name
			// guardian_address
			// guardian_contact_number
			// fathers_name
			// mothers_name
			// class_name
			// grade
			// class_adviser


		}else{

			$student_data["id"] = $this->input->get("student_id");
			$student_data = $this->students_model->get_data($student_data);
			$student_data["bday_m"] = date("n",$student_data["birthdate"]);
			$student_data["bday_d"] = date("j",$student_data["birthdate"]);
			$student_data["bday_y"] = date("Y",$student_data["birthdate"]);
			($student_data["guardian_id"]==0?$student_data["guardian_id"]="":FALSE);
			($student_data["class_id"]==0?$student_data["class_id"]="":FALSE);
			echo json_encode($student_data);
		}
	}

	public function delete()
	{
		if($_POST){
			$data["deleted"] = 1;
			$this->students_model->edit_info($data,$this->input->post("id"));

			$edit_data["ref_id"] = $this->input->post("id");
			$edit_data["ref_table"] = "students";
			$this->rfid_model->edit_info($data,$edit_data); 
		}
	}


	public function get_list($arg='')
	{
		$where = "";
		if($arg=="teachers"){
			$where["class_id"] = $this->input->get("class_id");
			$where["deleted"] = 0;
			$data = $this->students_model->get_list($where,1,$this->db->get_where("students",$where)->num_rows());

			foreach ($data["result"] as $student_data) {
				$student_data->full_name = $student_data->last_name.", ".$student_data->first_name." ".$student_data->middle_name[0].". ".$student_data->suffix;
			}
			echo json_encode($data["result"]);
		}elseif ($arg=="admin") {

			if($this->input->get("class_id")){
				$where["class_id"] = $this->input->get("class_id");
			}
			$where["deleted"] = 0;
			$data = $this->students_model->get_list($where,1,$this->db->get_where("students",$where)->num_rows());

			foreach ($data["result"] as $student_data) {
				$student_data->full_name = $student_data->last_name.", ".$student_data->first_name." ".$student_data->middle_name[0].". ".$student_data->suffix;
			}
			echo json_encode($data["result"]);
			
		}elseif ($arg=="jbtech") {

			if($this->input->get("class_id")){
				$where["class_id"] = $this->input->get("class_id");
			}
			$where["deleted"] = 0;
			$where["rfid_status"] = 0;
			$data = $this->students_model->get_list($where,1,$this->db->get_where("students",$where)->num_rows());

			foreach ($data["result"] as $student_data) {
				$student_data->full_name = $student_data->last_name.", ".$student_data->first_name." ".$student_data->middle_name[0].". ".$student_data->suffix;
			}
			echo json_encode($data["result"]);
			
		}
	}

	public function download($arg='')
	{
		$list = array (
		    array('aaa', 'bbb', 'ccc', 'dddd'),
		    array('123', '456', '789'),
		    array('"aaa"', '"bbb"')
		);

		$fp = fopen('file.csv', 'w');

		foreach ($list as $fields) {
		    fputcsv($fp, $fields);
		}

		fclose($fp);
		
	}

}