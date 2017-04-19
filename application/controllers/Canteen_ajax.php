<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Canteen_ajax extends CI_Controller {

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
		$this->load->model("rfid_model");
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
	public function applogin($arg='')
	{
		$canteen_user_data = $this->session->userdata("canteen_sessions");
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
	}

	public function items($arg='')
	{
		$canteen_user_data = $this->session->userdata("canteen_sessions");
		if($arg=="add"){

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
	}

	public function sales($arg='')
	{
		$canteen_user_data = $this->session->userdata("canteen_sessions");
		if($arg=="add_items_to_cart"){
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
		}elseif ($arg=="delete_items_to_cart") {
			$index = $this->input->post("item_id");
			$canteen_sales_cart = $this->session->userdata("canteen_sales_cart");
			unset($canteen_sales_cart["items"][$index]);
			$this->session->set_userdata("canteen_sales_cart",$canteen_sales_cart);

		}elseif ($arg=="edit_quantity_items_to_cart") {
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
		}elseif ($arg=="submit") {
			$canteen_sales_cart = $this->session->userdata("canteen_sales_cart");
			$total = 0;
			if(!isset($canteen_sales_cart["items"]) || $canteen_sales_cart["items"]===array()){
				$data["is_valid"] = FALSE;
				$data["error"] = "The Sales Cart is empty.";
				var_dump($data);
				exit;
			}else{
				$data["is_valid"] = TRUE;
			}

			foreach ($canteen_sales_cart["items"] as $canteen_sales_cart_data) {
				$line_total = $canteen_sales_cart_data["selling_price"]*$canteen_sales_cart_data["quantity"];
				$total += $line_total;
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
				if(isset($canteen_sales_cart["customer_data"]["id"])){
					$edit_data["id"] = $canteen_sales_cart["customer_data"]["rfid_data"]["id"];
					$this->rfid_model->load_credits($edit_data,(0-$total));
				}
			}

			echo json_encode($sales_data);
		}elseif ($arg=="confirm_pin") {
			$canteen_user_data = $this->session->userdata("canteen_sessions");
			$canteen_sales_cart = $this->session->userdata("canteen_sales_cart");
			$get_data["id"] = $this->input->post("rfid_id");
			$entered_pin = $this->input->post("pin");

			$rfid_owner_data = $this->rfid_model->get_data($get_data,TRUE,TRUE);
			$rfid_owner_data["customer_name"] = $rfid_owner_data["last_name"].", ".$rfid_owner_data["first_name"][0].". ".$rfid_owner_data["middle_name"][0].". ".$rfid_owner_data["suffix"];
			$data["is_valid"] = FALSE;
			if($rfid_owner_data["rfid_data"]["pin"]==$entered_pin){
				$canteen_sales_cart["customer_data"] = $rfid_owner_data;
				$this->session->set_userdata("canteen_sales_cart",$canteen_sales_cart);
				$data["is_valid"] = TRUE;
			}
			echo json_encode($data);
		}elseif ($arg=="remove_customer") {
			$canteen_user_data = $this->session->userdata("canteen_sessions");
			$canteen_sales_cart = $this->session->userdata("canteen_sales_cart");
			unset($canteen_sales_cart["customer_data"]);
			$this->session->set_userdata("canteen_sales_cart",$canteen_sales_cart);
		}elseif ($arg=="add_customer") {
			$canteen_user_data = $this->session->userdata("canteen_sessions");
			$canteen_sales_cart = $this->session->userdata("canteen_sales_cart");
			$canteen_sales_cart["customer_data"]["customer_name"] = $this->input->post("customer_name");
			$this->session->set_userdata("canteen_sales_cart",$canteen_sales_cart);
		}elseif ($arg=="add_comments") {
			$canteen_user_data = $this->session->userdata("canteen_sessions");
			$canteen_sales_cart = $this->session->userdata("canteen_sales_cart");
			$canteen_sales_cart["comments"] = $this->input->post("comments");
			$this->session->set_userdata("canteen_sales_cart",$canteen_sales_cart);
		}
	}

	public function add($data='')
	{
		$this->form_validation->set_rules('canteen_name', 'Canteen Name', 'is_unique|required|max_length[50]|trim|htmlspecialchars');
		$this->form_validation->set_rules('canteen_address', 'Canteen Address', 'required|max_length[50]|trim|htmlspecialchars');
		$this->form_validation->set_rules('canteen_contact_number', 'Canteen Contact Number', 'required|max_length[50]|trim|htmlspecialchars');

		if ($this->form_validation->run() == FALSE)
		{
			$canteen_data["is_valid"] = FALSE;
			$canteen_data["canteen_name_error"] = form_error("canteen_name");
			$canteen_data["canteen_address_error"] = form_error("canteen_address");
			$canteen_data["canteen_contact_number_error"] = form_error("canteen_contact_number");
		}else{
			$canteen_add_data["name"] = $this->input->post("canteen_name");
			$canteen_add_data["address"] = $this->input->post("canteen_address");
			$canteen_add_data["contact_number"] = $this->input->post("canteen_contact_number");
			$this->db->insert("canteens",$canteen_add_data);
			$this->db->order_by('id', 'DESC');
			$this->db->limit(1);
			$canteen_data = $this->db->get("canteens")->row_array();

			$canteen_data["is_valid"] = TRUE;
			$canteen_data["canteen_name_error"] = "";
			$canteen_data["canteen_address_error"] = "";
			$canteen_data["canteen_contact_number_error"] = "";
		}
		echo json_encode($canteen_data);
	}
}