<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('app_helper');

		$this->load->model("students_model");
		$this->load->model("rfid_model");
		$this->load->model("guardian_model");
		$this->load->model("teachers_model");
		$this->load->model("gate_logs_model");
		$this->load->model("staffs_model");
		$this->load->model("classes_model");
		$this->load->model("sms_model");

		$this->load->library('form_validation');
		$this->load->library('session');
		
		$this->data["title"] = "Main Title";
		$this->data["css_scripts"] = $this->load->view("scripts/css","",true);
		$this->data["js_scripts"] = $this->load->view("scripts/js","",true);
		$this->data["meta_scripts"] = $this->load->view("scripts/meta","",true);
		
		// $result = file_get_contents("https://www.itexmo.com/php_api/apicode_info.php?apicode=ST-ROMEO290433_3CTWI");
		// $sms_module_status = json_decode($result,true);


		
		$modal_data["guardians_list"] = $this->guardian_model->get_list();
		$modal_data["login_user_data"] = $this->session->userdata("admin_sessions");
		$modal_data["teachers_list"] = $this->teachers_model->get_list();
		$modal_data["students_list"] = $this->students_model->get_list();
		$modal_data["staffs_list"] = $this->staffs_model->get_list();
		$modal_data["classes_list"] = $this->classes_model->get_list();
		$modal_data["modals_sets"] = "admin";
		// $modal_data["sms_module_sms_left"] = $sms_module_status["Result "]["MessagesLeft"];
		$this->data["modaljs_scripts"] = $this->load->view("layouts/modals",$modal_data,true);
		

		$navbar_data["navbar_type"] = "admin";
		// $navbar_data["sms_module_sms_left"] = $sms_module_status["Result "]["MessagesLeft"];
		($this->session->userdata("admin_sessions")?$navbar_data["navbar_is_logged_in"] = TRUE:$navbar_data["navbar_is_logged_in"] = FALSE);
		$this->data["navbar_scripts"] = $this->load->view("layouts/navbar",$navbar_data,true);

		if(current_url()==base_url("admin")){
			
		}else{
			if($this->session->userdata("admin_sessions")==NULL){
				redirect(base_url("admin"));
			}
		}
	}

	public function index($student_id='')
	{


		$this->data["login_type"] = "admin";
		if($this->session->userdata("admin_sessions")){
			$where["type"] = "entry";
			$gate_log_data = $this->gate_logs_model->get_list();
			$this->data["title"] = "Students Gate Logs";
			$this->load->view('gate-logs-students',$this->data);
		}else{
			$this->data["title"] = "Admin Login";
			$this->load->view('app-login',$this->data);
		}
	}
	public function login($value='')
	{
		# code...
	}
	public function logout($value='')
	{
		$this->session->sess_destroy();
		redirect("admin");
	}

	public function students($value='')
	{
		$this->data["title"] = "Students List";
		$this->load->view("students-list",$this->data);
	}

	public function teachers($value='')
	{
		$this->data["title"] = "Teachers List";
		$this->load->view("teachers-list",$this->data);
	}

	public function staffs($value='')
	{
		$this->data["title"] = "Staffs List";
		$this->load->view("staffs-list",$this->data);
	}

	public function classes($value='')
	{
		$this->data["title"] = "Classes List";
		$this->load->view("classes-list",$this->data);
	}

	public function guardians($value='')
	{
		$this->data["title"] = "Guardians List";
		$this->load->view("guardians-list",$this->data);
	}
	public function sms($value='')
	{
		$this->data["title"] = "SMS Thread List";
		$this->load->view("sms",$this->data);
	}

	public function gatelogs($arg='')
	{
		if($arg=="students"){
			$this->data["title"] = "Students Gate Logs";
			$this->load->view('gate-logs-students',$this->data);
		}elseif ($arg=="teachers") {
			# code...
			$this->data["title"] = "Teachers Gate Logs";
			$this->load->view('gate-logs-teachers',$this->data);
		}elseif ($arg=="staffs") {
			$this->data["positions_list"] = $this->staffs_model->get_positions_list();
			$this->data["title"] = "Non-teaching Staffs Gate Logs";
			$this->load->view('gate-logs-staffs',$this->data);
		}
	}

}
