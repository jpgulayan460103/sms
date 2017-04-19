<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guardian_ajax extends CI_Controller {

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
	public function register($value='')
	{
		if($_POST){
			$this->form_validation->set_rules('guardian_address', 'Guardian Address', 'required|trim|htmlspecialchars|min_length[2]|max_length[50]');
			$this->form_validation->set_rules('email_address', 'Email Address', 'valid_email|trim|htmlspecialchars|min_length[2]|max_length[50]');
			$this->form_validation->set_rules('email_subscription', 'Email Address', 'email_subscription[email_address]');
			$this->form_validation->set_rules('guardian_name', 'Guardian Name', 'required|custom_alpha_dash|trim|htmlspecialchars|min_length[2]|max_length[50]');
			$this->form_validation->set_rules('contact_number', 'Contact Number', 'numeric|required|is_available[guardians.contact_number]|trim|htmlspecialchars|min_length[11]|max_length[11]');
			$this->form_validation->set_message('is_available', 'This %s is invalid or already taken');

			if ($this->form_validation->run() == FALSE)
			{
				$data["is_valid"] = FALSE;
				$data["guardian_name_error"] = form_error("guardian_name");
				$data["guardian_address_error"] = form_error("guardian_address");
				$data["email_address_error"] = form_error("email_address");
				$data["contact_number_error"] = form_error("contact_number");
				$data["subscription_error"] = form_error("email_subscription");
			}else{
				$data["is_valid"] = TRUE;
				$data["guardian_address_error"] = "";
				$data["guardian_name_error"] = "";
				$data["email_address_error"] = "";
				$data["contact_number_error"] = "";
				$data["subscription_error"] = "";

				($this->input->post("email_subscription")!=NULL?$email_subscription=1:$email_subscription=0);
				($this->input->post("sms_subscription")!=NULL?$sms_subscription=1:$sms_subscription=0);

				$guardian_data["name"] = $this->input->post("guardian_name");
				$guardian_data["email_address"] = $this->input->post("email_address");
				$guardian_data["guardian_address"] = $this->input->post("guardian_address");
				$guardian_data["contact_number"] = $this->input->post("contact_number");
				$guardian_data["sms_subscription"] = $sms_subscription;
				$guardian_data["email_subscription"] = $email_subscription;
				$password = random_string('alnum', 8);


				$message = "Your account details as guardian are:
Login: ".$this->input->post("contact_number")."
Password: ".$password."
You can login to ".base_url();
				send_sms($this->input->post("contact_number"),$message);


				$guardian_data["password"] = md5($password);
				$this->guardian_model->add($guardian_data);
			}
			echo json_encode($data);
		}
	}


	public function edit($value='')
	{
		if($_POST){
			$this->form_validation->set_rules('guardian_address', 'Guardian Address', 'required|trim|htmlspecialchars|min_length[2]|max_length[50]');
			$this->form_validation->set_rules('guardian_name', 'Guardian Name', 'required|custom_alpha_dash|trim|htmlspecialchars|min_length[2]|max_length[50]');
			$this->form_validation->set_rules('email_subscription', 'Email Address', 'email_subscription[email_address]');
			$this->form_validation->set_rules('guardian_id', 'Guardian', 'trim|htmlspecialchars|is_in_db[guardians.id]');
			$this->form_validation->set_rules('email_address', 'Email Address', 'valid_email|trim|htmlspecialchars|min_length[2]|max_length[50]');
			$this->form_validation->set_message('is_available', 'This Email is invalid or already taken');
			$this->form_validation->set_rules('contact_number', 'Contact Number', 'numeric|required|is_unique_edit[guardians.contact_number.guardian_id]"trim|htmlspecialchars|min_length[11]|max_length[11]');

			if ($this->form_validation->run() == FALSE)
			{
				$data["is_valid"] = FALSE;
				$data["guardian_name_error"] = form_error("guardian_name");
				$data["guardian_address_error"] = form_error("guardian_address");
				$data["email_address_error"] = form_error("email_address");
				$data["contact_number_error"] = form_error("contact_number");
				$data["subscription_error"] = form_error("email_subscription");
			}else{
				$data["is_valid"] = TRUE;
				$data["guardian_name_error"] = "";
				$data["guardian_address_error"] = "";
				$data["email_address_error"] = "";
				$data["contact_number_error"] = "";
				$data["subscription_error"] = "";

				($this->input->post("email_subscription")!=NULL?$email_subscription=1:$email_subscription=0);
				($this->input->post("sms_subscription")!=NULL?$sms_subscription=1:$sms_subscription=0);

				$guardian_id = $this->input->post("guardian_id");
				$guardian_data["name"] = $this->input->post("guardian_name");
				$guardian_data["email_address"] = $this->input->post("email_address");
				$guardian_data["guardian_address"] = $this->input->post("guardian_address");
				$guardian_data["contact_number"] = $this->input->post("contact_number");
				$guardian_data["sms_subscription"] = $sms_subscription;
				$guardian_data["email_subscription"] = $email_subscription;

				$get_data = array();
				$get_data["id"] = $this->input->post("guardian_id");
				$guardian_data_db = $this->guardian_model->get_data($get_data);

				if($guardian_data_db["contact_number"]!=$this->input->post("contact_number")){
					$password = random_string('alnum', 8);
					$message = "Your account details as guardian are:
Login: ".$this->input->post("contact_number")."
Password: ".$password."
You can login to ".base_url();
					$sms_code = send_sms($this->input->post("contact_number"),$message);
					$guardian_data["password"] = md5($password);

					if($sms_code==0){
						$this->guardian_model->edit_info($guardian_data,$guardian_id);
					}else{
						$data["is_valid"] = FALSE;
						$data["contact_number_error"] = sms_status($sms_code);
					}
				}else{
					$this->guardian_model->edit_info($guardian_data,$guardian_id);
				}

			}
			echo json_encode($data);
		}
	}

	public function applogin($value='')
	{
		if($_POST){
			$this->form_validation->set_rules('account', 'Account', 'required|min_length[5]|max_length[50]|is_in_db[guardians.contact_number]|trim|htmlspecialchars');
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
				$login_data["contact_number"] = $account_id = $this->input->post("account");
				$login_data["password"] = $account_password = $this->input->post("account_password");
				$login_data["deleted"] = 0;
				$login_data["password"] = md5($login_data["password"]);

				$data["is_valid"] = $this->guardian_model->login($login_data);
				$data["account_error"] = "";
				
				if($data["is_valid"]){
					$data["account_password_error"] = "";
					$data["redirect"] = base_url("home");
				}else{
					$data["account_password_error"] = "Incorrect Passord. Try Again.";
					$data["redirect"] = "";
				}
			}

			echo json_encode($data);
		}
	}

	public function get_data($value='')
	{
		$guardian_data["id"] = $this->input->get("guardian_id");
		$guardian_data = $this->guardian_model->get_data($guardian_data);
		($guardian_data["id"]==0?$guardian_data["id"]="":FALSE);
		echo json_encode($guardian_data);
	}

	public function get_list($arg='')
	{
		$data = $this->guardian_model->get_list();
		echo json_encode($data["result"]);
	}

	public function reset_password($arg='')
	{
		$guardian_id = $this->input->post("id");
		$get_data["id"] = $guardian_id;
		$guardian_data = $this->guardian_model->get_data($get_data);

		$password = random_string('alnum', 8);
		// echo "akjsndakjsdnjaksdnjkasndajkdansdasjnjkasdkj";
		$message = "Your account details as guardian are:
Login: ".$guardian_data["contact_number"]."
Password: ".$password."
You can login to ".base_url();

		$sms_status_code = send_sms($guardian_data["contact_number"],$message);
		if($sms_status_code=="0"){
			$update["password"] = md5($password);
			$data = $this->guardian_model->edit_info($update,$guardian_id);
			$data->is_successful = TRUE;
			echo json_encode($data);
		}else{
			$data["is_successful"] = FALSE;
			$data["error"] = sms_status($sms_status_code);
			echo json_encode($data);
		}
		
	}

	public function delete()
	{
		if($_POST){
			$data["deleted"] = 1;
			$this->guardian_model->edit_info($data,$this->input->post("id"));
		}
	}

	public function email_settings($arg='')
	{
		if($_POST){
			$this->form_validation->set_rules('email_address', 'Email Address', 'required|valid_email|trim|htmlspecialchars|min_length[2]|max_length[50]');
			$this->form_validation->set_rules('id', 'Guardian', 'trim|htmlspecialchars|is_in_db[guardians.id]');

			if ($this->form_validation->run() == FALSE)
			{
				$data["is_valid"] = FALSE;
				$data["email_address_error"] = form_error("email_address");
			}else{
				$data["is_valid"] = TRUE;
				$update_data["email_address"] = $this->input->post("email_address");
				$update_data["email_subscription"] = ($this->input->post("email_subscription")?1:0);
				$this->guardian_model->edit_info($update_data,$this->input->post("id"));
			}
			echo json_encode($data);
		}
	}
}