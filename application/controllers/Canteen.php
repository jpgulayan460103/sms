<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Canteen extends CI_Controller {

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
		$this->load->model("rfid_model");
		$this->load->model("gate_logs_model");
		$this->load->model("canteen_sales_model");
		$this->load->model("canteen_sale_items_model");
		$this->load->model("canteen_model");
		$this->load->model("canteen_items_model");
		$this->load->model("search_model");

		$this->data["title"] = "Main Title";
		$this->data["css_scripts"] = $this->load->view("scripts/css","",true);
		$this->data["js_scripts"] = $this->load->view("scripts/js","",true);
		$this->data["meta_scripts"] = $this->load->view("scripts/meta","",true);
		
		$modal_data["modals_sets"] = "canteen";
		$this->data["modaljs_scripts"] = $this->load->view("layouts/modals",$modal_data,true);
		
		$navbar_data["navbar_type"] = "canteen";
		if($this->session->userdata("canteen_sessions")){
			$this->data["canteen_user_data"] = $this->session->userdata("canteen_sessions");
			$navbar_data["navbar_is_logged_in"] = TRUE;
		}else{
			$navbar_data["navbar_is_logged_in"] = FALSE;
		}
		$this->data["navbar_scripts"] = $this->load->view("layouts/navbar",$navbar_data,true);
	}

	public function index($arg='')
	{

		$cart_data = $this->session->userdata("canteen_sales_cart");
		// $cart_data["customer"] = "asdasdasd";
		// $this->session->set_userdata("canteen_sales_cart",$cart_data);
		var_dump($cart_data);
		exit; 


		$this->session->unset_userdata("canteen_sales_cart");
		var_dump($this->session->userdata("canteen_sales_cart"));
		exit;
		$search_data["student_id"] = 45;
		$where["type"] = "exit";
		$order_by["student_id"] = "DESC";
		echo json_encode($this->search_model->autocomplete_with_value("gate_logs","student_id",$search_data,$where,"both",$order_by));
		exit;
		if(!$this->session->userdata("canteen_sessions")){
			redirect("canteen/login");
		}else{
			var_dump($this->data["canteen_user_data"]);
			$this->load->view('canteen_home',$this->data);
		}
		
	}

	public function items($arg='')
	{
		$this->load->view('canteen_items',$this->data);
	}
	public function login($value='')
	{
		# code...
		$this->data["login_type"] = "canteen";
		$this->load->view('app-login',$this->data);
	}
	public function logout($value='')
	{
		$this->session->unset_userdata('canteen_sessions');
		$this->session->unset_userdata('canteen_sales_cart');
	}

	public function sales_($arg='')
	{
		$canteen_sales_cart_data = $this->session->userdata("canteen_sales_cart");
		(isset($canteen_sales_cart_data["customer_data"])?$this->data["customer_data"] = $canteen_sales_cart_data["customer_data"]:$this->data["customer_data"]["customer_name"] = "");
		(isset($canteen_sales_cart_data["comments"])?$this->data["comments"] = $canteen_sales_cart_data["comments"]:$this->data["comments"] = "");

		if(isset($canteen_sales_cart_data["customer_data"]["rfid_data"]["id"])){
			$get_data["id"] = $canteen_sales_cart_data["customer_data"]["rfid_data"]["id"];

			$rfid_owner_data = $this->rfid_model->get_data($get_data,TRUE,TRUE);

			$canteen_sales_cart_data["customer_data"]["load_credits"] = ($rfid_owner_data["rfid_data"]["load_credits"]>=0?$rfid_owner_data["rfid_data"]["load_credits"]:0);
			$this->session->set_userdata("canteen_sales_cart",$canteen_sales_cart_data);
			$this->data["customer_data"] = $canteen_sales_cart_data["customer_data"];
		}else{
			$this->data["customer_data"]["comments"] = "";
		}

		$this->load->view('canteen_sales',$this->data);
	}

	public function sales_receipt($sale_id='')
	{
		$canteen_user_data = $this->session->userdata("canteen_sessions");
		$get_data["id"] = $canteen_user_data->canteen_id;
		$this->data["canteen_data"] = $this->canteen_model->get_canteens_data($get_data);
		$this->data["title"] = $this->canteen_model->get_canteens_data($get_data)->name;


		$get_data = array();
		$get_data["id"] = $sale_id;
		$get_data["canteen_id"] = $canteen_user_data->id;
		$this->data["sales_data"] = $this->canteen_sales_model->get_data($get_data);

		$get_data = array();
		$get_data["sale_id"] = $sale_id;
		$get_data["canteen_id"] = $canteen_user_data->id;
		$this->data["sales_items_list"] = $this->canteen_sale_items_model->sales_items_list($get_data);
		$this->load->view("sales_receipt",$this->data);
	}
}
