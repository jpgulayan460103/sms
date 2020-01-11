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
		
		
		$this->data["title"] = "Send SMS";
		$this->data["css_scripts"] = $this->load->view("scripts/css","",true);
		$this->data["js_scripts"] = $this->load->view("scripts/js","",true);
		$this->data["meta_scripts"] = $this->load->view("scripts/meta","",true);
		$navbar_data["navbar_type"] = "sms";
		$this->data["navbar_scripts"] = $this->load->view("layouts/navbar",$navbar_data,true);
		
	}
	public function index()
	{
		$this->data["modaljs_scripts"] = "";
		$this->db->where("deleted","0");
		$this->db->order_by('number', 'ASC');
		$this->data["contact_list"] = $this->db->get("contacts")->result();
		$this->data["email_contact_list"] = $this->db->get("email_contacts")->result();
		$this->data["send_request"] = $this->db->get("sms_api")->row();
		
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
				$i = 0;
				foreach ($contact_list as $contact_data) {
					$this->db->where("id",$contact_data->id);
					$status_code = "";
					$status = "";

					$data["messages"][] = $contact_data->id;
					$insert["contact_id"] = $contact_data->id;
					$insert["message"] = $message;
					$insert["status_code"] = $status_code;
					$insert["status"] = $status;
					$insert["date_time"] = strtotime(date("m/d/Y h:i:s A"));
				}
			}else{
				$i = 0;
				foreach ($this->input->post("contact_id") as $contact_id) {
					$this->db->where("id",$contact_id);
					$contact_data = $this->db->get("contacts")->row();
					$data["messages"][] = $contact_id;
					$status_code = "";
					$status = "";
					$insert["contact_id"] = $contact_id;
					$insert["message"] = $message;
					$insert["status_code"] = $status_code;
					$insert["status"] = $status;
					$insert["date_time"] = strtotime(date("m/d/Y h:i:s A"));
				}
			}

		}
		echo json_encode($data);
	}

	public function send_email($value='')
	{
		$this->form_validation->set_rules('message', 'Message', 'required|max_length[320]|trim|htmlspecialchars');
		$this->form_validation->set_rules('subject', 'Subject', 'required|max_length[50]|trim|htmlspecialchars');
		$this->form_validation->set_rules('from_name', 'From', 'required|max_length[50]|trim|htmlspecialchars');

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
			$subject = $this->input->post("subject");
			$from_name = $this->input->post("from_name");
			if($this->input->post("send_option")=="all"){
				$this->db->where("deleted","0");
				$contact_list = $this->db->get("email_contacts")->result();
				$i = 0;
				foreach ($contact_list as $contact_data) {
					$this->db->where("id",$contact_data->id);
					$status_code = "";
					$status = "";

					$data["messages"][] = $contact_data->id;
					$insert["contact_id"] = $contact_data->id;
					$insert["message"] = $message;
					$insert["subject"] = $subject;
					$insert["from_name"] = $from_name;
					$insert["status_code"] = $status_code;
					$insert["status"] = $status;
					$insert["date_time"] = strtotime(date("m/d/Y h:i:s A"));
				}
			}else{
				$i = 0;
				foreach ($this->input->post("contact_id") as $contact_id) {
					$this->db->where("id",$contact_id);
					$contact_data = $this->db->get("email_contacts")->row();
					$data["messages"][] = $contact_id;
					$status_code = "";
					$status = "";
					$insert["contact_id"] = $contact_id;
					$insert["message"] = $message;
					$insert["subject"] = $subject;
					$insert["from_name"] = $from_name;
					$insert["status_code"] = $status_code;
					$insert["status"] = $status;
					$insert["date_time"] = strtotime(date("m/d/Y h:i:s A"));
				}
			}

		}
		echo json_encode($data);
	}

	public function add($value='')
	{
		$this->form_validation->set_rules('name', 'Name', 'max_length[50]|trim|htmlspecialchars');
		$this->form_validation->set_rules('number', 'Number', 'is_available[contacts.number]|required|numeric|max_length[11]|min_length[11]|trim|htmlspecialchars');

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

	public function setUrl($value='')
	{
		$update = array(
			'send_request' => $this->input->post("send_request"),
		);
		$this->db->where("id",1);
		$this->db->update("sms_api",$update);
		header("Location: ".$_SERVER['HTTP_REFERER']);
		die();
	}

	public function resend($value='')
	{
		$this->db->where("status_code","4");
		$data["messages"] = $this->db->get("sms")->result();
		echo json_encode($data);
	}

	public function get_sms($value='')
	{
		$page = $this->input->get("page");
		$maxitem = 100;
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
				<td>'.$contact_data->number.'</td>
				<td>'.$message_data->status.'</td>
				<td>'.date("m/d/Y h:i:s A",$message_data->date_time).'</td>
			</tr>
			';
		}
		$attrib["href"] = "#";
		$attrib["class"] = "paging";
		echo paging($page,$this->db->get("sms")->num_rows(),$maxitem,$attrib,'<tr><td colspan="20" style="text-align:center">','</td></tr>');	
	}

	public function get_emails($value='')
	{
		$page = $this->input->get("page");
		$maxitem = 100;
		$limit = ($page*$maxitem)-$maxitem;
		$this->db->limit($maxitem,$limit);
		$this->db->order_by('id', 'DESC');
		$message_list = $this->db->get("email")->result();
		foreach ($message_list as $message_data) {
			$this->db->where("id",$message_data->contact_id);
			$contact_data = $this->db->get("email_contacts")->row();
			echo '
			<tr>
				<td>'.$message_data->message.'</td>
				<td>'.$contact_data->email_address.'</td>
				<td>'.$message_data->status.'</td>
				<td>'.date("m/d/Y h:i:s A",$message_data->date_time).'</td>
			</tr>
			';
		}
		$attrib["href"] = "#";
		$attrib["class"] = "paging";
		echo paging($page,$this->db->get("email")->num_rows(),$maxitem,$attrib,'<tr><td colspan="20" style="text-align:center">','</td></tr>');	
	}


	public function request_send_sms($id='')
	{
		$message = $this->input->post("message");
		$this->db->where("id",$this->input->post("recipient"));
		$contact_data = $this->db->get("contacts")->row();
		$sms_api = $this->db->get('sms_api')->row();
		$status_code = send_sms($contact_data->number,$message,$sms_api->api_code,$sms_api->send_request);
		$status = sms_status($status_code);
		
		if($id==""){
			$insert["contact_id"] = $this->input->post("recipient");
			$insert["message"] = $message;
			$insert["status_code"] = $status_code;
			$insert["status"] = $status;
			$insert["date_time"] = strtotime(date("m/d/Y h:i:s A"));
			$this->db->insert('sms', $insert);
		}else{
			$update["status_code"] = $status_code;
			$update["status"] = $status;
			$insert["date_time"] = strtotime(date("m/d/Y h:i:s A"));
			$this->db->where("id",$id);
			$this->db->update("sms",$update);
		}
		echo $status_code;
	}

	public function request_send_email($value='')
	{
		$message = $this->input->post("message");
		$from_name = $this->input->post("from_name");
		$subject = $this->input->post("subject");
		$this->db->where("id",$this->input->post("recipient"));
		$contact_data = $this->db->get("email_contacts")->row();

		$this->load->library('email');

		$this->email->from($this->db->get('email_settings')->row()->email_sender, $from_name);
		$this->email->to($contact_data->email_address);

		$this->email->subject($subject);
		$this->email->message($message);

		$this->email->send();
		$status_code = 0;
		$insert["contact_id"] = $this->input->post("recipient");
		$insert["subject"] = $subject;
		$insert["from_name"] = $from_name;
		$insert["message"] = $message;
		$insert["status_code"] = 0;
		$insert["status"] = "Your Message has been sent.";
		$insert["date_time"] = strtotime(date("m/d/Y h:i:s A"));
		$this->db->insert('email', $insert);
		$data["email"] = $contact_data->email_address;
		$data["subject"] = $subject;
		$data["from_name"] = $from_name;
		$data["message"] = $message;
		echo json_encode($data);
	}

	public function get_api_data($value='')
	{
		$curl = curl_init();
		$sms_api = $this->db->get('sms_api')->row();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $sms_api->get_info.$sms_api->api_code,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_SSL_VERIFYPEER => FALSE,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "cache-control: no-cache"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		$response = json_decode($response,true);
		// $data["MessagesLeft"] = $response["Result "]["MessagesLeft"];
		// $data["ExpiresOn"] = $response["Result "]["ExpiresOn"];
		$data["ExpiresOn"] = $response["ok"];
		echo json_encode($data);
		curl_close($curl);
	}

	public function sms_sent($value='')
	{
		$this->data["modaljs_scripts"] = "";
		$this->load->view("sms-sent",$this->data);
	}

	public function email_sent($value='')
	{
		$this->data["modaljs_scripts"] = "";
		$this->load->view("email_sent",$this->data);
	}


	public function add_email($value='')
	{
		$this->form_validation->set_rules('name', 'Name', 'max_length[50]|trim|htmlspecialchars');
		$this->form_validation->set_rules('email_address', 'Email Address', 'valid_email|is_available[email_contacts.email_address]|required|max_length[100]|trim|htmlspecialchars');

		if ($this->form_validation->run() == FALSE){
			$data["is_valid"] = FALSE;
			$data["name"] = form_error("name");
			$data["email_address"] = form_error("email_address");

		}else{
			$data["is_valid"] = TRUE;
			$data["name"] = form_error("name");
			$data["email_address"] = form_error("email_address");
			$insert = array(
			        'name' => $this->input->post("name"),
			        'email_address' => $this->input->post("email_address")
			);

			$this->db->insert('email_contacts', $insert);
		}
		echo json_encode($data);
	}

	public function sms_contacts($value='')
	{
		$this->data["modaljs_scripts"] = "";
		$this->load->view("sms_contacts",$this->data);
	}

	public function get_contacts($value='')
	{
		$page = $this->input->get("page");
		$maxitem = 100;
		$limit = ($page*$maxitem)-$maxitem;
		$this->db->limit($maxitem,$limit);
		$this->db->where("deleted","0");
		$sms_contacts = $this->db->get("contacts")->result();
		foreach ($sms_contacts as $sms_contacts_data) {
			echo '
			<tr>
				<td>'.$sms_contacts_data->name.'</td>
				<td>'.$sms_contacts_data->number.'</td>
				<td><a href="#" class="delete-sms-contact" id="'.$sms_contacts_data->id.'" data-balloon="DELETE" data-balloon-pos="down">&times;</td>
			</tr>
			';
		}
		$this->db->where("deleted","0");
		$attrib["href"] = "#";
		$attrib["class"] = "sms_paging";
		echo paging($page,$this->db->get("contacts")->num_rows(),$maxitem,$attrib,'<tr><td colspan="20" style="text-align:center">','</td></tr>');

	}
	public function get_email_contacts($value='')
	{
		$page = $this->input->get("page");
		$maxitem = 100;
		$limit = ($page*$maxitem)-$maxitem;
		$this->db->limit($maxitem,$limit);
		$this->db->where("deleted","0");
		$sms_contacts = $this->db->get("email_contacts")->result();
		foreach ($sms_contacts as $sms_contacts_data) {
			echo '
			<tr>
				<td>'.$sms_contacts_data->name.'</td>
				<td>'.$sms_contacts_data->email_address.'</td>
				<td><a href="#" class="delete-email-contact" id="'.$sms_contacts_data->id.'" data-balloon="DELETE" data-balloon-pos="down">&times;</td>
			</tr>
			';
		}
		$this->db->where("deleted","0");
		$attrib["href"] = "#";
		$attrib["class"] = "email_paging";
		echo paging($page,$this->db->get("email_contacts")->num_rows(),$maxitem,$attrib,'<tr><td colspan="20" style="text-align:center">','</td></tr>');

	}

	public function delete_sms_contact($value='')
	{
		$this->db->where("id",$this->input->post("id"));
		$this->db->set("deleted","1");
		$this->db->update("contacts");
	}

	public function delete_email_contact($value='')
	{
		$this->db->where("id",$this->input->post("id"));
		$this->db->set("deleted","1");
		$this->db->update("contacts");
	}


}
