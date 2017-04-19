<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

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
		$this->load->library('session');

		$this->load->model("students_model");
		$this->load->model("guardian_model");
		$this->load->model("gate_logs_model");

		$this->data["title"] = "Main Title";
		$this->data["css_scripts"] = $this->load->view("scripts/css","",true);
		$this->data["js_scripts"] = $this->load->view("scripts/js","",true);
		$this->data["meta_scripts"] = $this->load->view("scripts/meta","",true);

		if($this->session->userdata("guardian_sessions")){
			$modal_data["modals_sets"] = "home";
			$get_data = array();
			$get_data["id"] = $this->session->userdata("guardian_sessions")->id;
			$modal_data["login_user_data"] = $this->guardian_model->get_data($get_data,TRUE);
			$modal_data["rfid_scanned_addstudent"] = $this->session->userdata("rfid_scanned_addstudent");
			$this->data["modaljs_scripts"] = $this->load->view("layouts/modals",$modal_data,true);
		}
		

		
		$navbar_data["navbar_type"] = "home";
		($this->session->userdata("guardian_sessions")?$navbar_data["navbar_is_logged_in"] = TRUE:$navbar_data["navbar_is_logged_in"] = FALSE);
		$this->data["navbar_scripts"] = $this->load->view("layouts/navbar",$navbar_data,true);


		if(current_url()==base_url()||current_url()==base_url("home")){
			
		}else{
			if($this->session->userdata("guardian_sessions")==NULL){
				redirect(base_url());
			}
		}

	}

	public function index($arg='')
	{
		
		if(!$this->session->userdata("guardian_sessions")){
			$this->data["title"] = "Guardian Login";
			$this->data["login_type"] = "guardian";
			$this->load->view('app-login',$this->data);
		}else{
			$this->data["title"] = "My Students";
			$guardian_data = $this->session->userdata("guardian_sessions");
			$where["deleted"] = 0;
			$where["guardian_id"] = $guardian_data->id;
			$students_list = $this->students_model->get_list($where);
			// var_dump($students_list);
			$this->data["students_list"] = $this->students_model->get_list($where);
			$this->load->view('guardian_home',$this->data);
		}
		
	}

	public function logout($value='')
	{
		$this->session->sess_destroy();
		redirect("home");
	}

}
