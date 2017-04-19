<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends CI_Controller {

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
		$this->load->library('session');

		$this->load->model("students_model");
		$this->load->model("rfid_model");
		$this->load->model("guardian_model");
		$this->load->model("teachers_model");
		$this->load->model("gate_logs_model");
		$this->load->model("classes_model");
		$this->load->model("sms_model");
		
		$this->data["title"] = "Main Title";
		$this->data["css_scripts"] = $this->load->view("scripts/css","",true);
		$this->data["js_scripts"] = $this->load->view("scripts/js","",true);
		$this->data["meta_scripts"] = $this->load->view("scripts/meta","",true);
		
		$modal_data["guardians_list"] = $this->guardian_model->get_list();
		$modal_data["teachers_list"] = $this->teachers_model->get_list();
		$modal_data["classes_list"] = $this->classes_model->get_list();
		$modal_data["login_user_data"] = $this->session->userdata("teacher_sessions");
		$modal_data["modals_sets"] = "teacher";
		$modal_data["teacher_data"] = $this->session->userdata("teacher_sessions");
		$this->data["modaljs_scripts"] = $this->load->view("layouts/modals",$modal_data,true);
		
		$navbar_data["navbar_type"] = "teacher";
		($this->session->userdata("teacher_sessions")?$navbar_data["navbar_is_logged_in"] = TRUE:$navbar_data["navbar_is_logged_in"] = FALSE);
		$this->data["navbar_scripts"] = $this->load->view("layouts/navbar",$navbar_data,true);
		$this->data["teacher_data"] = $this->session->userdata("teacher_sessions");

		if(current_url()==base_url("teacher")){
			
		}else{
			if($this->session->userdata("teacher_sessions")==NULL){
				redirect(base_url("teacher"));
			}
		}
	}

	public function index($student_id='')
	{
		$this->data["login_type"] = "teacher";
		if($this->session->userdata("teacher_sessions")){
			$this->data["title"] = "My Students";
			$this->load->view('teacher',$this->data);
		}else{
			$this->data["title"] = "Teachers Login";
			$this->load->view('app-login',$this->data);
		}
	}

	public function sms($arg='')
	{
		$this->data["title"] = "My SMS Threads";
		$this->load->view('teacher-sms',$this->data);
	}

	public function gatelogs($arg='')
	{
		$this->data["title"] = "My Student's Gate logs";
		$this->data["teacher_data"] = $this->session->userdata("teacher_sessions");
		$this->load->view("teacher-gatelogs",$this->data);
	}

	public function logout($value='')
	{
		$this->data["title"] = "Logout";
		$this->session->sess_destroy();
		redirect("teacher");		
		# code...
	}

}