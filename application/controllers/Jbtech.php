<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jbtech extends CI_Controller {

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
		


		$modal_data["guardians_list"] = $this->guardian_model->get_list();
		$modal_data["login_user_data"] = $this->session->userdata("jbtech_sessions");
		$modal_data["teachers_list"] = $this->teachers_model->get_list();
		$modal_data["students_list"] = $this->students_model->get_list();
		$modal_data["staffs_list"] = $this->staffs_model->get_list();
		$modal_data["classes_list"] = $this->classes_model->get_list();

		$modal_data["modals_sets"] = "jbtech";
		$this->data["modaljs_scripts"] = $this->load->view("layouts/modals",$modal_data,true);
		
		$navbar_data["navbar_type"] = "jbtech";
		($this->session->userdata("jbtech_sessions")?$navbar_data["navbar_is_logged_in"] = TRUE:$navbar_data["navbar_is_logged_in"] = FALSE);
		$this->data["navbar_scripts"] = $this->load->view("layouts/navbar",$navbar_data,true);
	}

	public function index($student_id='')
	{
		$this->data["login_type"] = "jbtech";
		if($this->session->userdata("jbtech_sessions")){

			$this->load->view('jbtech-students',$this->data);
		}else{
			$this->load->view('app-login',$this->data);
		}
	}

	public function teachers($arg='')
	{

			$this->load->view('jbtech-teachers',$this->data);
	}

	public function students($arg='')
	{
			$this->load->view('jbtech-students',$this->data);

	}

	public function staffs($arg='')
	{
			$this->load->view('jbtech-staffs',$this->data);

	}

	public function logout($value='')
	{
		$this->session->sess_destroy();
		redirect("jbtech");		
		# code...
	}

}