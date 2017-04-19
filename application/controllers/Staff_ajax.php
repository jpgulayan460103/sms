<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff_ajax extends CI_Controller {

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
		$this->load->model("staffs_model");
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

			// $data["class_id"] = $this->input->post("class_id");

			$this->form_validation->set_rules('position', 'Position', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('address', 'Address', 'required|min_length[2]|max_length[100]trim|htmlspecialchars');
			$this->form_validation->set_rules('first_name', 'First Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('middle_name', 'Middle Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('suffix', 'Suffix', 'custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('bday_m', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
			$this->form_validation->set_rules('bday_d', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
			$this->form_validation->set_rules('bday_y', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
			$this->form_validation->set_rules('contact_number', 'Contact Number', 'numeric|min_length[11]|max_length[11]trim|htmlspecialchars');
			$this->form_validation->set_rules('gender', 'Gender', 'required|max_length[50]trim|htmlspecialchars');



			$has_uploaded_pic = FALSE;
			//uploads files
			if($_FILES['staff_photo']["error"]==0){

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
				$config['upload_path'] = './assets/images/staff_photo/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['file_name'] = $filename;
				$config['max_size']	= '20480';
				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload("staff_photo"))
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
				$data["position_error"] = form_error('position');
				$data["first_name_error"] = form_error('first_name');
				$data["last_name_error"] = form_error('last_name');
				$data["middle_name_error"] = form_error('middle_name');
				$data["suffix_error"] = form_error('suffix');
				$data["contact_number_error"] = form_error('contact_number');
				$data["bday_error"] = form_error('bday_m');
			}
			else
			{
				$data["is_valid"] = TRUE;
				$data["address_error"] = form_error('address');
				$data["gender_error"] = form_error('gender');
				$data["position_error"] = form_error('position');
				$data["first_name_error"] = form_error('first_name');
				$data["last_name_error"] = form_error('last_name');
				$data["middle_name_error"] = form_error('middle_name');
				$data["suffix_error"] = form_error('suffix');
				$data["contact_number_error"] = form_error('contact_number');
				$data["bday_error"] = form_error('bday_m');

				$staff_data["first_name"] = $this->input->post("first_name");
				$staff_data["gender"] = $this->input->post("gender");
				$staff_data["address"] = $this->input->post("address");
				$staff_data["last_name"] = $this->input->post("last_name");
				$staff_data["middle_name"] = $this->input->post("middle_name");
				$staff_data["suffix"] = $this->input->post("suffix");
				$staff_data["position"] = $this->input->post("position");
				$staff_data["contact_number"] = $this->input->post("contact_number");
				$staff_data["display_photo"] = $filename;
				$bday_m = sprintf("%02d",$this->input->post("bday_m"));
				$bday_d = sprintf("%02d",$this->input->post("bday_d"));
				$bday_y = sprintf("%04d",$this->input->post("bday_y"));
				$birthdate_str = $bday_m."/".$bday_d."/".$bday_y;
				$staff_data["birthdate"] = strtotime($birthdate_str);
				// $staff_data["rfid"] = $this->input->post("rfid");
				$data["is_successful"] = TRUE;
				$staff_data = $this->staffs_model->add($staff_data);

				if($has_uploaded_pic){
					rename($full_path,$file_path.$staff_data->id."_".$file_name);
					$edit_data = array();
					$edit_data["display_photo"] = $staff_data->id."_".$file_name;
					$this->staffs_model->edit_info($edit_data,$staff_data->id);
				}


				// $rfid_data["rfid"] = $this->input->post("rfid");
				$rfid_data["ref_id"] = $staff_data->id;
				$rfid_data["ref_table"] = "staffs";
				$rfid_data["valid"] = 1;
				$this->rfid_model->add($rfid_data);

			}

			echo json_encode($data);
		}
		
	}

	public function edit($arg='')
	{
		if($_POST){


			// $data["class_id"] = $this->input->post("class_id");

			$this->form_validation->set_rules('staff_id', 'First Name', 'required|trim|htmlspecialchars|is_in_db[staffs.id]');
			$this->form_validation->set_rules('address', 'Address', 'required|min_length[2]|max_length[100]trim|htmlspecialchars');
			$this->form_validation->set_rules('position', 'Position', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('first_name', 'First Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('middle_name', 'Middle Name', 'required|custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('suffix', 'Suffix', 'custom_alpha_dash|min_length[2]|max_length[50]trim|htmlspecialchars');
			$this->form_validation->set_rules('bday_m', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
			$this->form_validation->set_rules('bday_d', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
			$this->form_validation->set_rules('bday_y', 'Birth Date', 'required|is_valid_date[bday_m.bday_d.bday_y]|trim|htmlspecialchars');
			$this->form_validation->set_rules('contact_number', 'Contact Number', 'numeric|min_length[11]|max_length[11]trim|htmlspecialchars');
			$this->form_validation->set_rules('gender', 'Gender', 'required|max_length[50]trim|htmlspecialchars');

			$this->form_validation->set_message('is_in_db', 'An Error has occured please refresh the page and try again.');



			$has_uploaded_pic = FALSE;
			//uploads files
			if($_FILES['staff_photo']["error"]==0){

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
				$get_data["ref_id"] = $this->input->post("staff_id");
				$get_data["ref_table"] = "staffs";
				$rfid_data = $this->rfid_model->get_data($get_data);
				

				$filename = $filename_full_name;



				$config['overwrite'] = TRUE;
				$config['upload_path'] = './assets/images/staff_photo/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['file_name'] = $filename;
				$config['max_size']	= '20480';
				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload("staff_photo"))
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
				$get_data["id"] = $this->input->post("staff_id");
				$staff_data_db = $this->staffs_model->get_data($get_data);
				$filename = $staff_data_db["display_photo"];
			}



			if ($this->form_validation->run() == FALSE|| $data["is_valid_photo"] == FALSE)
			{
				$data["is_valid"] = FALSE;
				$data["gender_error"] = form_error('gender');
				$data["address_error"] = form_error('address');
				$data["position_error"] = form_error('position');
				$data["first_name_error"] = form_error('first_name');
				$data["last_name_error"] = form_error('last_name');
				$data["middle_name_error"] = form_error('middle_name');
				$data["suffix_error"] = form_error('suffix');
				$data["contact_number_error"] = form_error('contact_number');
				$data["bday_error"] = form_error('bday_m');
				$data["staff_id_error"] = form_error('staff_id');
				// echo json_encode($data);
			}
			else
			{
				$data["is_valid"] = TRUE;
				$data["gender_error"] = form_error('gender');
				$data["address_error"] = form_error('address');
				$data["position_error"] = form_error('position');
				$data["first_name_error"] = form_error('first_name');
				$data["last_name_error"] = form_error('last_name');
				$data["middle_name_error"] = form_error('middle_name');
				$data["suffix_error"] = form_error('suffix');
				$data["contact_number_error"] = form_error('contact_number');
				$data["bday_error"] = form_error('bday_m');
				$data["class_id_error"] = "";

				$staff_data["first_name"] = $this->input->post("first_name");
				$staff_data["address"] = $this->input->post("address");
				$staff_data["position"] = $this->input->post("position");
				$staff_data["gender"] = $this->input->post("gender");
				$staff_data["last_name"] = $this->input->post("last_name");
				$staff_data["middle_name"] = $this->input->post("middle_name");
				$staff_data["suffix"] = $this->input->post("suffix");
				$staff_data["contact_number"] = $this->input->post("contact_number");
				// $staff_data["class_id"] = $this->input->post("class_id");
				$staff_data["display_photo"] = $filename;
				// $staff_data["display_photo_type"] = $new_image_data['file_type'];
				$bday_m = sprintf("%02d",$this->input->post("bday_m"));
				$bday_d = sprintf("%02d",$this->input->post("bday_d"));
				$bday_y = sprintf("%04d",$this->input->post("bday_y"));
				$birthdate_str = $bday_m."/".$bday_d."/".$bday_y;
				$staff_data["birthdate"] = strtotime($birthdate_str);


				$get_data = array();
				$get_data["id"] = $this->input->post("staff_id");
				$staff_data_db = $this->staffs_model->get_data($get_data);

				
				$this->staffs_model->edit_info($staff_data,$this->input->post("staff_id"));


				

				if($has_uploaded_pic){
					$staff_id = $this->input->post("staff_id");
					rename($full_path,$file_path.$staff_id."_".$file_name);
					$edit_data = array();
					$edit_data["display_photo"] = $staff_id."_".$file_name;
					$this->staffs_model->edit_info($edit_data,$staff_id);
				}

			}
			// var_dump($this->staffs_model->edit_info($staff_data,$this->input->post("staff_id")));
			// var_dump($data);
			// exit;
			echo json_encode($data);

			
		}

	}

	public function get_data($arg='')
	{
		if($arg=="jbtech"){
			$staff_data["id"] = $this->input->get("staff_id");
			$staff_data = $this->staffs_model->get_data($staff_data);
			$staff_data["id"] = sprintf("%03d",$this->input->get("staff_id"));
			$staff_data["birthday"] = date("m/d/Y",$staff_data["birthdate"]);
			$staff_data["age"] = age($staff_data["birthdate"]);
			$staff_data["full_name"] = $staff_data["first_name"]." ".$staff_data["middle_name"][0].". ".$staff_data["last_name"]." ".$staff_data["suffix"];
			echo json_encode($staff_data);
		}else{
			$staff_data["id"] = $this->input->get("staff_id");
			$staff_data = $this->staffs_model->get_data($staff_data);
			$staff_data["bday_m"] = date("n",$staff_data["birthdate"]);
			$staff_data["bday_d"] = date("j",$staff_data["birthdate"]);
			$staff_data["bday_y"] = date("Y",$staff_data["birthdate"]);
			echo json_encode($staff_data);
		}
	}

	public function get_list($arg='')
	{
		if($arg=="admin"){
			$where = "";
			if($this->input->get("position")){
				$where["position"] = $this->input->get("position");
				$where["deleted"] = 0;
			}
			$data = $this->staffs_model->get_list($where);
			echo json_encode($data["result"]);
		}elseif ($arg=="jbtech") {
			$where["rfid_status"] = 0;
			$where["deleted"] = 0;
			if($this->input->get("position")){
				$where["position"] = $this->input->get("position");
			}
			$data = $this->staffs_model->get_list($where);
			echo json_encode($data["result"]);
		}
	}

	public function delete()
	{
		if($_POST){
			$data = array();
			$data["deleted"] = 1;
			$this->staffs_model->delete($data,$this->input->post("id"));

			$data = array();
			$data["deleted"] = 1;
			$edit_data["ref_id"] = $this->input->post("id");
			$edit_data["ref_table"] = "staffs";
			$this->rfid_model->edit_info($data,$edit_data);

		}
	}

	public function applogin($arg='')
	{
		if($_POST){
			$this->form_validation->set_rules('account', 'Account', 'required|min_length[5]|max_length[12]|is_valid[staffs.contact_number]|trim|htmlspecialchars');
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
				// $data["var_dump"] = $this->staffs_model->login($data);
				$data["is_valid"] = $this->staffs_model->login($data);
				$data["account_error"] = "";
				
				if($data["is_valid"]){
					$data["account_password_error"] = "";
					$data["redirect"] = base_url("staff");


				}else{
					$data["account_password_error"] = "Incorrect Passord. Try Again.";
					$data["redirect"] = "";
				}
			}

			echo json_encode($data);
		}
	}

}