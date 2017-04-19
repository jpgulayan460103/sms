<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms extends CI_Controller {

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
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');
		$this->load->model("students_model");
		$this->load->model("rfid_model");
		$this->load->model("guardian_model");
		$this->load->model("teachers_model");
		$this->load->model("gate_logs_model");
		$this->load->model("classes_model");
		$this->load->model("sms_model");
		
		$this->data["title"] = "Send SMS";
		$this->data["css_scripts"] = $this->load->view("scripts/css","",true);
		$this->data["js_scripts"] = $this->load->view("scripts/js","",true);
		$this->data["meta_scripts"] = $this->load->view("scripts/meta","",true);
		
	}
	public function index()
	{
		$this->data["navbar_scripts"] = "";
		$this->data["modaljs_scripts"] = "";
		$this->db->where("deleted","0");
		$this->data["contact_list"] = $this->db->get("contacts")->result();
		
		$this->load->view('sms',$this->data);
	}

	public function send($value='')
	{
		$this->form_validation->set_rules('message', 'Message', 'required|max_length[320]|trim|htmlspecialchars');

		if($this->input->post("send_option")!="all"){
			$this->form_validation->set_rules('contact_id[]', 'Recipient', 'required|trim|htmlspecialchars');

		}



		if ($this->form_validation->run() == FALSE){
			$data["is_valid"] = FALSE;
			$data["contact_id"] = form_error("contact_id[]");
			$data["message"] = form_error("message");

		}else{
			$data["is_valid"] = TRUE;
			$data["contact_id"] = form_error("contact_id[]");
			$data["message"] = form_error("message");
			$message = $this->input->post("message");
			if($this->input->post("send_option")=="all"){
				$this->db->where("deleted","0");
				$contact_list = $this->db->get("contacts")->result();
				foreach ($contact_list as $contact_data) {
					$this->db->where("id",$contact_data->id);
					$status_code = send_sms($contact_data->number,$message);
					$status = sms_status($status_code);

					$insert["contact_id"] = $contact_data->id;
					$insert["message"] = $message;
					$insert["status_code"] = $status_code;
					$insert["status"] = $status;
					$insert["date_time"] = strtotime(date("m/d/Y h:i:s A"));
					$this->db->insert('sms', $insert);
				}
			}else{
				foreach ($this->input->post("contact_id") as $contact_id) {
					$this->db->where("id",$contact_id);
					$contact_data = $this->db->get("contacts")->row();
					$status_code = send_sms($contact_data->number,$message);
					$status = sms_status($status_code);

					$insert["contact_id"] = $contact_id;
					$insert["message"] = $message;
					$insert["status_code"] = $status_code;
					$insert["status"] = $status;
					$insert["date_time"] = strtotime(date("m/d/Y h:i:s A"));
					$this->db->insert('sms', $insert);
				}
			}

		}
		echo json_encode($data);
	}

	public function add($value='')
	{
		$this->form_validation->set_rules('name', 'Name', 'required|max_length[50]|trim|htmlspecialchars');
		$this->form_validation->set_rules('number', 'Number', 'required|numeric|max_length[11]|min_length[11]|trim|htmlspecialchars');

		if ($this->form_validation->run() == FALSE){
			$data["is_valid"] = FALSE;
			$data["name"] = form_error("name");
			$data["number"] = form_error("number");

		}else{
			$data["is_valid"] = TRUE;
			$data["name"] = form_error("name");
			$data["number"] = form_error("number");
			$insert = array(
			        'name' => $this->input->post("name"),
			        'number' => $this->input->post("number")
			);

			$this->db->insert('contacts', $insert);
		}
		echo json_encode($data);
	}

	public function get_sms($value='')
	{
		$page = $this->input->get("page");
		$maxitem = 50;
		$limit = ($page*$maxitem)-$maxitem;
		$this->db->limit($maxitem,$limit);
		$this->db->order_by('id', 'DESC');
		$message_list = $this->db->get("sms")->result();
		foreach ($message_list as $message_data) {
			$this->db->where("id",$message_data->contact_id);
			$contact_data = $this->db->get("contacts")->row();
			echo '
			<tr>
				<td>'.$message_data->message.'</td>
				<td>'.$contact_data->name.'</td>
				<td>'.$message_data->status.'</td>
				<td>'.date("m/d/Y h:i:s A",$message_data->date_time).'</td>
			</tr>
			';
		}
		$attrib["href"] = "#";
		$attrib["class"] = "paging";
		echo paging($page,$this->db->get("sms")->num_rows(),$maxitem,$attrib,'<tr><td colspan="20" style="text-align:center">','</td></tr>');	
	}
}
