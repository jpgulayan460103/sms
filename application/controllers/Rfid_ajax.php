<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rfid_ajax extends CI_Controller {

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
		$this->load->model("rfid_model");
		$this->load->model("gate_logs_model");
		$this->load->model("guardian_model");
		$this->load->model("students_model");
		$this->load->model("staffs_model");
		$this->load->model("teachers_model");

		$this->load->library('form_validation');
		$this->load->library('session');
		
		$this->form_validation->set_error_delimiters('', '');
	}

	public function index()
	{
		show_404();
	}
	
	public function scan_add($value='')
	{
		if($_POST){
			$rfid = $this->input->post("rfid");
			$type = $this->input->post("type");
			$id = $this->input->post("id");

			(($type=="teachers" || $type=="students" || $type=="staffs")?false:$type="students");

			$this->form_validation->set_rules('rfid', 'RFID', 'required|numeric|is_available[rfid.rfid]|max_length[50]|trim|htmlspecialchars');
			$this->form_validation->set_rules('valid_m', 'Valid Until', 'required|is_valid_date[valid_m.valid_d.valid_y]|trim|htmlspecialchars');
			$this->form_validation->set_rules('valid_d', 'Valid Until', 'required|is_valid_date[valid_m.valid_d.valid_y]|trim|htmlspecialchars');
			$this->form_validation->set_rules('valid_y', 'Valid Until', 'required|is_valid_date[valid_m.valid_d.valid_y]|trim|htmlspecialchars');

			if ($this->form_validation->run() == FALSE)
			{
				$data["is_valid"] = FALSE;
				$data["error"] = form_error("rfid");
				$data["date_error"] = form_error("valid_m");
			}else{
				$data["is_valid"] = TRUE;
				$data["error"] = "";
				$data["date_error"] = "";
				$get_data = array();
				$get_data["ref_table"] = $type;
				$get_data["ref_id"] = $id;
				$update_data = array();
				$update_data["rfid"] = $rfid;


				$valid_m = sprintf("%02d",$this->input->post("valid_m"));
				$valid_d = sprintf("%02d",$this->input->post("valid_d"));
				$valid_y = sprintf("%04d",$this->input->post("valid_y"));
				$valid_date_str = $valid_m."/".$valid_d."/".$valid_y;

				$update_data["valid_date"] = strtotime($valid_date_str);


				$this->rfid_model->edit_info($update_data,$get_data);

				$update_data = array();
				$update_data["rfid_status"] = 1;

				$model = $type."_model";
				$this->$model->edit_info($update_data,$id);
			}
			echo json_encode($data);
		}
	}

	public function scangate($arg='')
	{
		if($_POST){
			$this->form_validation->set_rules('gate_rfid_scan', 'RFID', 'required|numeric|trim|htmlspecialchars|is_valid_rfid');

			if ($this->form_validation->run() == FALSE)
			{
				$rfid_owner_data["is_valid"] = FALSE;
				$rfid_owner_data["display_photo"] = base_url("assets/images/empty.jpg");
				$rfid_owner_data["full_name"] = "";
				$rfid_owner_data["last_name"] = "";
				$rfid_owner_data["first_name"] = "";
				$rfid_owner_data["message"] = "";
				$rfid_owner_data["middle_name"] = "";
				$rfid_owner_data["suffix"] = "";
				
			}else{
				$get_data["rfid"] = $this->input->post("gate_rfid_scan"); 
				$get_data["deleted"] = 0;
				$get_data["valid"] = 1;
				$rfid_owner_data = $this->rfid_model->get_data($get_data,TRUE,TRUE);
				$rfid_owner_data["is_valid"] = TRUE;
				if($rfid_owner_data["rfid_data"]["ref_table"]=="students"){
					$dir = "student_photo";
				}elseif ($rfid_owner_data["rfid_data"]["ref_table"]=="teachers") {
					$dir = "teacher_photo";
				}elseif ($rfid_owner_data["rfid_data"]["ref_table"]=="staffs") {
					$dir = "staff_photo";
				}
				$rfid_owner_data["display_photo"] = base_url("assets/images/".$dir."/".$rfid_owner_data["display_photo"]);
				$rfid_owner_data["full_name"] = $rfid_owner_data["first_name"]." ".$rfid_owner_data["middle_name"][0].". ".$rfid_owner_data["last_name"];
				$rfid_owner_data["birthdate"] = date("m/d/Y",$rfid_owner_data["birthdate"]);

				$rfid_owner_log_data["rfid_id"] = $rfid_owner_data["rfid_data"]["id"];
				$rfid_owner_log_data["ref_table"] = $rfid_owner_data["rfid_data"]["ref_table"];
				$rfid_owner_log_data["ref_id"] = $rfid_owner_data["rfid_data"]["ref_id"];
				$rfid_owner_log_data["date_time"] = strtotime(date("m/d/Y h:i:s A"));
				$rfid_owner_log_data["date"] = strtotime(date("m/d/Y"));

				$rfid_owner_data["gate_logs_data"] = $this->gate_logs_model->add_log($rfid_owner_log_data);
				if($rfid_owner_data["gate_logs_data"]["is_valid"]){
					if($rfid_owner_data["gate_logs_data"]["gate_logs_data"]->type=="entry"){
						$type_status = "enters";
					}else{
						$type_status = "exits";
					}
					
					if($rfid_owner_log_data["ref_table"]=="students"){
						$rfid_owner_data["message"] = $rfid_owner_data["full_name"].' '.$type_status.' the school premises on '.date("m/d/Y h:i:s A").'.';
						if($rfid_owner_data["guardian_id"]!="0"){
							$get_data = array();
							$get_data["id"] = $rfid_owner_data["guardian_id"];

							$guardian_data = $this->guardian_model->get_data($get_data);
							if($guardian_data["sms_subscription"]=="1"){
								$rfid_owner_data["sms_status"] = send_sms($guardian_data["contact_number"],$rfid_owner_data["message"]);
							}

							if($guardian_data["email_subscription"]=="1"){
								$this->load->library('email');

								$this->email->from('no-reply@rfid-ph.net', 'Gate Notifications Demo');
								$this->email->to($guardian_data["email_address"]);

								$this->email->subject('Gate Notification');
								$this->email->message($rfid_owner_data["message"]);

								$this->email->send();
							}


						}
					}
				
				}else{
					$rfid_owner_data["gate_logs_data"]["is_valid"] = FALSE;
				}

			}
			// var_dump($rfid_owner_data["gate_logs_data"]);
			// exit;
			echo json_encode($rfid_owner_data);
			
		}
	}

	public function addloadstudent($arg='')
	{

	/*
		if($_POST){
			$this->form_validation->set_rules('rfid_scan_addloadstudent', 'RFID', 'required|numeric|trim|htmlspecialchars|is_valid_rfid');
			if ($this->form_validation->run() == FALSE)
			{
				$student_data["is_valid"] = FALSE;
			}else{
				$get_data["rfid"] = $this->input->post("rfid_scan_addloadstudent");
				$get_data["valid"] = 1;
				$get_data["deleted"] = 0;
				$rfid_data = $this->rfid_model->get_data($get_data);
				$get_data = array();
				$get_data["id"] = $rfid_data->id;
				$student_data = $this->students_model->get_data($get_data);
				$student_data["is_valid"] = TRUE;
				$student_data["load_credits"] = number_format($rfid_data->load_credits,2);
				$student_data["display_photo"] = base_url("assets/images/student_photo/".$student_data["display_photo"]);
				$student_data["full_name"] = $student_data["last_name"].", ".$student_data["first_name"]." ".$student_data["middle_name"][0].". ".$student_data["suffix"];
				$student_data["guardian_data"] = $this->guardian_model->get_data($student_data["guardian_id"]);
			}
			echo json_encode($student_data);
		}
	*/}

	public function delete($arg='')
	{
		$id = $this->input->post("id");
		$type = $this->input->post("type");
		$update_data = array();
		$update_data["rfid_status"] = 0;
		$model = $type."_model";
		$this->$model->edit_info($update_data,$id);

		$update_data = array();
		$update_data["rfid"] = "";
		$get_data = array();
		$get_data["ref_id"] = $id;
		$get_data["ref_table"] = $type;
		$this->rfid_model->edit_info($update_data,$get_data);
	}


	public function rfid_scan_add_load_credit($arg='')
	{
		if($_POST){
			$this->form_validation->set_rules('rfid', 'RFID', 'required|numeric|trim|htmlspecialchars|is_valid_rfid');
			if ($this->form_validation->run() == FALSE)
			{
				$rfid_owner_data["is_valid"] = FALSE;
			}else{
				$get_data["rfid"] = $this->input->post("rfid");
				$get_data["valid"] = 1;
				$get_data["deleted"] = 0;
				$rfid_owner_data = $this->rfid_model->get_data($get_data,TRUE);

				$rfid_owner_data->rfid_data->load_credits = number_format($rfid_owner_data->rfid_data->load_credits,2);
				$rfid_owner_data->display_photo = base_url("assets/images/student_photo/".$rfid_owner_data->display_photo);
				$rfid_owner_data->full_name = $rfid_owner_data->last_name.", ".$rfid_owner_data->first_name." ".$rfid_owner_data->middle_name[0].". ".$rfid_owner_data->suffix;

				$rfid_owner_data->is_valid = TRUE;

			}
			// var_dump($rfid_owner_data);
			echo json_encode($rfid_owner_data);
		}
	}

	public function canteen($arg='')
	{
		$canteen_sales_cart = $this->session->userdata("canteen_sales_cart");
		$canteen_user_data = $this->session->userdata("canteen_sessions");
		if($arg=="sales"){
			$this->form_validation->set_rules('rfid_scan', 'RFID', 'required|numeric|trim|htmlspecialchars|is_valid_rfid');
			if ($this->form_validation->run() == FALSE)
			{
				$student_data["is_valid"] = FALSE;
			}else{
				$get_data["rfid"] = $this->input->post("rfid_scan");
				$get_data["valid"] = 1;
				$get_data["deleted"] = 0;
				// var_dump($this->rfid_model->get_data($get_data,TRUE));
				// exit;
				$rfid_owner_data = $this->rfid_model->get_data($get_data,TRUE);
				$rfid_owner_data->is_valid = TRUE;
				$rfid_owner_data->load_credits = number_format($rfid_owner_data->rfid_data->load_credits,2);
				$rfid_owner_data->display_photo = base_url("assets/images/student_photo/".$rfid_owner_data->display_photo);
				$rfid_owner_data->full_name = $rfid_owner_data->last_name.", ".$rfid_owner_data->first_name." ".$rfid_owner_data->middle_name[0].". ".$rfid_owner_data->suffix;
			}
			// var_dump($rfid_owner_data);
			echo json_encode($rfid_owner_data);
		}
	}
}