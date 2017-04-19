<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {

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
		$this->data["title"] = "Main Title";
		$this->data["css_scripts"] = $this->load->view("scripts/css","",true);
		$this->data["js_scripts"] = $this->load->view("scripts/js","",true);
		$this->data["meta_scripts"] = $this->load->view("scripts/meta","",true);
		$this->data["navbar_scripts"] = $this->load->view("layouts/navbar","",true);
		$this->data["modaljs_scripts"] = $this->load->view("layouts/modals","",true);
	}

	public function index($arg='')
	{
		// var_dump($this->data);
		$this->data["type_entry"] = $arg; 
		$this->load->view('students-entry',$this->data);
	}
	public function gate($arg='')
	{
		// var_dump($this->data);
		$this->data["type_entry"] = $arg; 
		$this->load->view('students-entry',$this->data);
	}
}
