<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_ajax extends CI_Controller {

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
		$this->load->model("teachers_model");
		$this->load->model("sms_model");
		$this->load->model("gate_logs_model");
		$this->load->model("canteen_model");
		$this->load->model("canteen_items_model");

		$this->load->library('form_validation');
		$this->load->library('session');
		
		$this->form_validation->set_error_delimiters('', '');
	}

	public function send()
	{
		if($_POST){


			$type_recipient = $this->input->post("type_recipient");
			$message = $this->input->post("message");
			$sender = $this->input->post("sender");
			$classes = ($this->input->post("class_id")?$this->input->post("class_id"):NULL);

			$this->form_validation->set_rules('type_recipient', 'Recipient', 'required');
			$this->form_validation->set_rules('message', 'Message', 'required|max_length[300]trim|htmlspecialchars');
			if($type_recipient=="all_teachers"||$type_recipient=="all_teachers_students"||$type_recipient=="all_students"||$type_recipient=="all_members"||$type_recipient=="all_guardians"){
				
				$is_valid_class = TRUE;
			}else{
				if($classes===NULL){
					$is_valid_class = FALSE;
				}else{
					$is_valid_class = TRUE;
				}
			}

			if ($this->form_validation->run() == FALSE || $is_valid_class == FALSE)
			{
				$data["is_valid"] = FALSE;
				$data["type_recipient_error"] = form_error('type_recipient');
				$data["message_error"] = form_error('message');
				$data["class_id_error"] = ($is_valid_class?"":"The Class field is required.");
			}
			else
			{
				$data["is_valid"] = TRUE;
				$data["type_recipient_error"] = form_error('type_recipient');
				$data["message_error"] = form_error('message');
				$data["class_id_error"] = ($is_valid_class?"":"The Class field is required.");





				$valid_recipient = FALSE;

				switch ($type_recipient) {
					case 'teachers_students':
						foreach ($classes as $class) {
							$get_list["class_id"] = $class;
							$get_list["deleted"] = 0;
							$students_list = $this->students_model->get_list($get_list,1,$this->db->get("students")->num_rows());

							if($students_list["result"] != array()){
								foreach ($students_list["result"] as $students_data) {
									if($students_data->contact_number!=""){
										$valid_recipient = TRUE;
									}
								}
							}


							$get_list["class_id"] = $class;
							$get_list["deleted"] = 0;
							$teachers_list = $this->teachers_model->get_list($get_list,1,$this->db->get("teachers")->num_rows());

							if($teachers_list["result"] != array()){
								foreach ($teachers_list["result"] as $teachers_data) {
									if($teachers_data->contact_number!=""){
										$valid_recipient = TRUE;
									}
								}
							}

						}
						break;
					case 'teachers':
						foreach ($classes as $class) {
							$get_list["class_id"] = $class;
							$get_list["deleted"] = 0;
							$teachers_list = $this->teachers_model->get_list($get_list,1,$this->db->get("teachers")->num_rows());
							if($teachers_list["result"] != array()){
								foreach ($teachers_list["result"] as $teachers_data) {
									if($teachers_data->contact_number!=""){
										$valid_recipient = TRUE;
									}
								}
							}
						}
						# code...
						break;
					case 'students':
						foreach ($classes as $class) {
							$get_list["class_id"] = $class;
							$get_list["deleted"] = 0;
							$students_list = $this->students_model->get_list($get_list,1,$this->db->get("students")->num_rows());
							if($students_list["result"] != array()){
								foreach ($students_list["result"] as $students_data) {
									if($students_data->contact_number!=""){
										$valid_recipient = TRUE;
									}
								}
							}
						}
						# code...
						break;
					case 'guardian':
						foreach ($classes as $class) {
							$get_list["class_id"] = $class;
							$get_list["deleted"] = 0;
							$students_list = $this->students_model->get_list($get_list,1,$this->db->get("students")->num_rows());
							if($students_list["result"] != array()){
								foreach ($students_list["result"] as $students_data) {
									$get_data["id"] = $students_data->guardian_id;
									$guardian_data = $this->guardian_model->get_data($get_data,TRUE);
									if($guardian_data->contact_number!=""){
										$valid_recipient = TRUE;
									}
								}
							}

						}
						# code...
						break;
					case 'members':
						foreach ($classes as $class) {
							$get_list["class_id"] = $class;
							$get_list["deleted"] = 0;
							$students_list = $this->students_model->get_list($get_list,1,$this->db->get("students")->num_rows());

							if($students_list["result"] != array()){
								foreach ($students_list["result"] as $students_data) {
									if($students_data->contact_number!=""){
										$valid_recipient = TRUE;
									}
								}
							}

							$get_list["class_id"] = $class;
							$get_list["deleted"] = 0;
							$teachers_list = $this->teachers_model->get_list($get_list,1,$this->db->get("teachers")->num_rows());
							if($teachers_list["result"] != array()){
								foreach ($teachers_list["result"] as $teachers_data) {
									if($teachers_data->contact_number!=""){
										$valid_recipient = TRUE;
									}
								}
							}

							$get_list["class_id"] = $class;
							$get_list["deleted"] = 0;
							$students_list = $this->students_model->get_list($get_list,1,$this->db->get("students")->num_rows());
							if($students_list["result"] != array()){
								foreach ($students_list["result"] as $students_data) {
									$get_data["id"] = $students_data->guardian_id;
									$guardian_data = $this->guardian_model->get_data($get_data,TRUE);
									if($guardian_data->contact_number!=""){
										$valid_recipient = TRUE;
									}
								}
							}
						}
						# code...
						break;
					case 'all_teachers_students':
						$students_list = $this->students_model->get_list("",1,$this->db->get("students")->num_rows());

						if($students_list["result"] != array()){
							foreach ($students_list["result"] as $students_data) {
								if($students_data->contact_number!=""){
									$valid_recipient = TRUE;
								}
							}
						}


						$teachers_list = $this->teachers_model->get_list("",1,$this->db->get("teachers")->num_rows());
						if($teachers_list["result"] != array()){
							foreach ($teachers_list["result"] as $teachers_data) {
								if($teachers_data->contact_number!=""){
									$valid_recipient = TRUE;
								}
							}
						}


						# code...
						break;
					case 'all_teachers':
						$teachers_list = $this->teachers_model->get_list("",1,$this->db->get("teachers")->num_rows());
						if($teachers_list["result"] != array()){
							foreach ($teachers_list["result"] as $teachers_data) {
								if($teachers_data->contact_number!=""){
									$valid_recipient = TRUE;
								}
							}
						}
						# code...
						break;
					case 'all_students':
						$students_list = $this->students_model->get_list("",1,$this->db->get("students")->num_rows());
						if($students_list["result"] != array()){
							foreach ($students_list["result"] as $students_data) {
								if($students_data->contact_number!=""){
									$valid_recipient = TRUE;
								}
							}
						}
						# code...
						break;
					case 'all_guardians':
						$students_list = $this->students_model->get_list("",1,$this->db->get("students")->num_rows());
						if($students_list["result"] != array()){
							foreach ($students_list["result"] as $students_data) {
								$get_data["id"] = $students_data->guardian_id;
								$guardian_data = $this->guardian_model->get_data($get_data,TRUE);
								if($guardian_data->contact_number!=""){
									$valid_recipient = TRUE;
								}
							}
						}

						# code...
						break;
					case 'all_members':
						$students_list = $this->students_model->get_list("",1,$this->db->get("students")->num_rows());
						if($students_list["result"] != array()){
							foreach ($students_list["result"] as $students_data) {
								if($students_data->contact_number!=""){
									$valid_recipient = TRUE;
								}
							}
						}


						$teachers_list = $this->teachers_model->get_list("",1,$this->db->get("teachers")->num_rows());
						if($teachers_list["result"] != array()){
							foreach ($teachers_list["result"] as $teachers_data) {
								if($teachers_data->contact_number!=""){
									$valid_recipient = TRUE;
								}
							}
						}


						$students_list = $this->students_model->get_list("",1,$this->db->get("students")->num_rows());
						if($students_list["result"] != array()){
							foreach ($students_list["result"] as $students_data) {
								$get_data["id"] = $students_data->guardian_id;
								$guardian_data = $this->guardian_model->get_data($get_data,TRUE);
								if($guardian_data->contact_number!=""){
									$valid_recipient = TRUE;
								}
							}
						}

						# code...
						break;
					default:
						$data["is_valid"] = FALSE;
						# code...
						break;
				}





				if($valid_recipient){

					if($sender=="admin"){
						$sms_sender = $this->session->userdata("admin_sessions");
						$sms_sender->sender = "admins";
						$sms_db_data = $this->sms_model->add($sms_sender);
					}else{
						$sms_sender = $this->session->userdata("teacher_sessions");
						$sms_sender->sender = "teachers";
						$sms_db_data = $this->sms_model->add($sms_sender);
					}
					
					switch ($type_recipient) {
						case 'teachers_students':
							foreach ($classes as $class) {
								$get_list["class_id"] = $class;
								$get_list["deleted"] = 0;
								$students_list = $this->students_model->get_list($get_list,1,$this->db->get("students")->num_rows());
								foreach ($students_list["result"] as $students_data) {
									if($students_data->contact_number!=""){
										$sms_data["recipient"] = $students_data->last_name.", ".$students_data->first_name." ".$students_data->middle_name[0].". ".$students_data->suffix;
										$sms_data["mobile_number"] = $students_data->contact_number;
										$sms_data["message"] = $message;
										$sms_data["status_code"] = send_sms($students_data->contact_number,$message);
										$sms_data["status"] = sms_status($sms_data["status_code"]);
										$this->sms_model->send($sms_data,$sms_db_data);
									}
								}

								$get_list["class_id"] = $class;
								$get_list["deleted"] = 0;
								$teachers_list = $this->teachers_model->get_list($get_list,1,$this->db->get("teachers")->num_rows());
								foreach ($teachers_list["result"] as $teachers_data) {
									if($teachers_data->contact_number!=""){
										$sms_data["recipient"] = $teachers_data->last_name.", ".$teachers_data->first_name." ".$teachers_data->middle_name[0].". ".$teachers_data->suffix;
										$sms_data["mobile_number"] = $teachers_data->contact_number;
										$sms_data["message"] = $message;
										$sms_data["status_code"] = send_sms($teachers_data->contact_number,$message);
										$sms_data["status"] = sms_status($sms_data["status_code"]);
										$this->sms_model->send($sms_data,$sms_db_data);
									}
								}
							}
							break;
						case 'teachers':
							foreach ($classes as $class) {
								$get_list["class_id"] = $class;
								$get_list["deleted"] = 0;
								$teachers_list = $this->teachers_model->get_list($get_list,1,$this->db->get("teachers")->num_rows());
								foreach ($teachers_list["result"] as $teachers_data) {
									if($teachers_data->contact_number!=""){
										$sms_data["recipient"] = $teachers_data->last_name.", ".$teachers_data->first_name." ".$teachers_data->middle_name[0].". ".$teachers_data->suffix;
										$sms_data["mobile_number"] = $teachers_data->contact_number;
										$sms_data["message"] = $message;
										$sms_data["status_code"] = send_sms($teachers_data->contact_number,$message);
										$sms_data["status"] = sms_status($sms_data["status_code"]);
										$this->sms_model->send($sms_data,$sms_db_data);
									}
								}
							}
							# code...
							break;
						case 'students':
							foreach ($classes as $class) {
								$get_list["class_id"] = $class;
								$get_list["deleted"] = 0;
								$students_list = $this->students_model->get_list($get_list,1,$this->db->get("students")->num_rows());
								foreach ($students_list["result"] as $students_data) {
									if($students_data->contact_number!=""){
										$sms_data["recipient"] = $students_data->last_name.", ".$students_data->first_name." ".$students_data->middle_name[0].". ".$students_data->suffix;
										$sms_data["mobile_number"] = $students_data->contact_number;
										$sms_data["message"] = $message;
										$sms_data["status_code"] = send_sms($students_data->contact_number,$message);
										$sms_data["status"] = sms_status($sms_data["status_code"]);
										$this->sms_model->send($sms_data,$sms_db_data);
									}
								}
							}
							# code...
							break;
						case 'guardian':
							foreach ($classes as $class) {
								$get_list["class_id"] = $class;
								$get_list["deleted"] = 0;
								$students_list = $this->students_model->get_list($get_list,1,$this->db->get("students")->num_rows());
								foreach ($students_list["result"] as $students_data) {
									$get_data["id"] = $students_data->guardian_id;
									$guardian_data = $this->guardian_model->get_data($get_data,TRUE);
									if($guardian_data->contact_number!=""){
										$sms_data["recipient"] = $guardian_data->name;
										$sms_data["mobile_number"] = $guardian_data->contact_number;
										$sms_data["message"] = $message;
										$sms_data["status_code"] = send_sms($guardian_data->contact_number,$message);
										$sms_data["status"] = sms_status($sms_data["status_code"]);
										$this->sms_model->send($sms_data,$sms_db_data);
									}
								}
							}
							# code...
							break;
						case 'members':
							foreach ($classes as $class) {
								$get_list["class_id"] = $class;
								$get_list["deleted"] = 0;
								$students_list = $this->students_model->get_list($get_list,1,$this->db->get("students")->num_rows());
								foreach ($students_list["result"] as $students_data) {
									if($students_data->contact_number!=""){
										$sms_data["recipient"] = $students_data->last_name.", ".$students_data->first_name." ".$students_data->middle_name[0].". ".$students_data->suffix;
										$sms_data["mobile_number"] = $students_data->contact_number;
										$sms_data["message"] = $message;
										$sms_data["status_code"] = send_sms($students_data->contact_number,$message);
										$sms_data["status"] = sms_status($sms_data["status_code"]);
										$this->sms_model->send($sms_data,$sms_db_data);
									}
								}

								$get_list["class_id"] = $class;
								$get_list["deleted"] = 0;
								$teachers_list = $this->teachers_model->get_list($get_list,1,$this->db->get("teachers")->num_rows());
								foreach ($teachers_list["result"] as $teachers_data) {
									if($teachers_data->contact_number!=""){
										$sms_data["recipient"] = $teachers_data->last_name.", ".$teachers_data->first_name." ".$teachers_data->middle_name[0].". ".$teachers_data->suffix;
										$sms_data["mobile_number"] = $teachers_data->contact_number;
										$sms_data["message"] = $message;
										$sms_data["status_code"] = send_sms($teachers_data->contact_number,$message);
										$sms_data["status"] = sms_status($sms_data["status_code"]);
										$this->sms_model->send($sms_data,$sms_db_data);
									}
								}

								$get_list["class_id"] = $class;
								$get_list["deleted"] = 0;
								$students_list = $this->students_model->get_list($get_list,1,$this->db->get("students")->num_rows());
								foreach ($students_list["result"] as $students_data) {
									$get_data["id"] = $students_data->guardian_id;
									$guardian_data = $this->guardian_model->get_data($get_data,TRUE);
									if($guardian_data->contact_number!=""){
										$sms_data["recipient"] = $guardian_data->name;
										$sms_data["mobile_number"] = $guardian_data->contact_number;
										$sms_data["message"] = $message;
										$sms_data["status_code"] = send_sms($guardian_data->contact_number,$message);
										$sms_data["status"] = sms_status($sms_data["status_code"]);
										$this->sms_model->send($sms_data,$sms_db_data);
									}
								}
							}
							# code...
							break;
						case 'all_teachers_students':
							$students_list = $this->students_model->get_list("",1,$this->db->get("students")->num_rows());
							foreach ($students_list["result"] as $students_data) {
								if($students_data->contact_number!=""){
									$sms_data["recipient"] = $students_data->last_name.", ".$students_data->first_name." ".$students_data->middle_name[0].". ".$students_data->suffix;
									$sms_data["mobile_number"] = $students_data->contact_number;
									$sms_data["message"] = $message;
									$sms_data["status_code"] = send_sms($students_data->contact_number,$message);
									$sms_data["status"] = sms_status($sms_data["status_code"]);
									$this->sms_model->send($sms_data,$sms_db_data);
								}
							}

							$teachers_list = $this->teachers_model->get_list("",1,$this->db->get("teachers")->num_rows());
							foreach ($teachers_list["result"] as $teachers_data) {
								if($teachers_data->contact_number!=""){
									$sms_data["recipient"] = $teachers_data->last_name.", ".$teachers_data->first_name." ".$teachers_data->middle_name[0].". ".$teachers_data->suffix;
									$sms_data["mobile_number"] = $teachers_data->contact_number;
									$sms_data["message"] = $message;
									$sms_data["status_code"] = send_sms($teachers_data->contact_number,$message);
									$sms_data["status"] = sms_status($sms_data["status_code"]);
									$this->sms_model->send($sms_data,$sms_db_data);
								}
							}

							# code...
							break;
						case 'all_teachers':
							$teachers_list = $this->teachers_model->get_list("",1,$this->db->get("teachers")->num_rows());
							foreach ($teachers_list["result"] as $teachers_data) {
								if($teachers_data->contact_number!=""){
									$sms_data["recipient"] = $teachers_data->last_name.", ".$teachers_data->first_name." ".$teachers_data->middle_name[0].". ".$teachers_data->suffix;
									$sms_data["mobile_number"] = $teachers_data->contact_number;
									$sms_data["message"] = $message;
									$sms_data["status_code"] = send_sms($teachers_data->contact_number,$message);
									$sms_data["status"] = sms_status($sms_data["status_code"]);
									$this->sms_model->send($sms_data,$sms_db_data);
								}
							}
							# code...
							break;
						case 'all_students':
							$students_list = $this->students_model->get_list("",1,$this->db->get("students")->num_rows());
							foreach ($students_list["result"] as $students_data) {
								if($students_data->contact_number!=""){
									$sms_data["recipient"] = $students_data->last_name.", ".$students_data->first_name." ".$students_data->middle_name[0].". ".$students_data->suffix;
									$sms_data["mobile_number"] = $students_data->contact_number;
									$sms_data["message"] = $message;
									$sms_data["status_code"] = send_sms($students_data->contact_number,$message);
									$sms_data["status"] = sms_status($sms_data["status_code"]);
									$this->sms_model->send($sms_data,$sms_db_data);
								}
							}
							# code...
							break;
						case 'all_guardians':
							$students_list = $this->students_model->get_list("",1,$this->db->get("students")->num_rows());
							foreach ($students_list["result"] as $students_data) {
								$get_data["id"] = $students_data->guardian_id;
								$guardian_data = $this->guardian_model->get_data($get_data,TRUE);
								if($guardian_data->contact_number!=""){
									$sms_data["recipient"] = $guardian_data->name;
									$sms_data["mobile_number"] = $guardian_data->contact_number;
									$sms_data["message"] = $message;
									$sms_data["status_code"] = send_sms($guardian_data->contact_number,$message);
									$sms_data["status"] = sms_status($sms_data["status_code"]);
									$this->sms_model->send($sms_data,$sms_db_data);
								}
							}
							# code...
							break;
						case 'all_members':
							$students_list = $this->students_model->get_list("",1,$this->db->get("students")->num_rows());
							foreach ($students_list["result"] as $students_data) {
								if($students_data->contact_number!=""){
									$sms_data["recipient"] = $students_data->last_name.", ".$students_data->first_name." ".$students_data->middle_name[0].". ".$students_data->suffix;
									$sms_data["mobile_number"] = $students_data->contact_number;
									$sms_data["message"] = $message;
									$sms_data["status_code"] = send_sms($students_data->contact_number,$message);
									$sms_data["status"] = sms_status($sms_data["status_code"]);
									$this->sms_model->send($sms_data,$sms_db_data);
								}
							}

							$teachers_list = $this->teachers_model->get_list("",1,$this->db->get("teachers")->num_rows());
							foreach ($teachers_list["result"] as $teachers_data) {
								if($teachers_data->contact_number!=""){
									$sms_data["recipient"] = $teachers_data->last_name.", ".$teachers_data->first_name." ".$teachers_data->middle_name[0].". ".$teachers_data->suffix;
									$sms_data["mobile_number"] = $teachers_data->contact_number;
									$sms_data["message"] = $message;
									$sms_data["status_code"] = send_sms($teachers_data->contact_number,$message);
									$sms_data["status"] = sms_status($sms_data["status_code"]);
									$this->sms_model->send($sms_data,$sms_db_data);
								}
							}

							$students_list = $this->students_model->get_list("",1,$this->db->get("students")->num_rows());
							foreach ($students_list["result"] as $students_data) {
								$get_data["id"] = $students_data->guardian_id;
								$guardian_data = $this->guardian_model->get_data($get_data,TRUE);
								if($guardian_data->contact_number!=""){
									$sms_data["recipient"] = $guardian_data->name;
									$sms_data["mobile_number"] = $guardian_data->contact_number;
									$sms_data["message"] = $message;
									$sms_data["status_code"] = send_sms($guardian_data->contact_number,$message);
									$sms_data["status"] = sms_status($sms_data["status_code"]);
									$this->sms_model->send($sms_data,$sms_db_data);
								}
							}
							# code...
							break;
						default:
							$data["is_valid"] = FALSE;
							# code...
							break;
					}

					$get_data = array();
					$get_data["id"] = $sms_db_data->id;
					$data["sms_data"] = $this->sms_model->get_data($get_data);
					$get_data = array();
					$get_data["sms_id"] = $sms_db_data->id;
					$data["sms_list"] = $this->sms_model->get_sms_list($get_data);
				}else{
					$data["is_valid"] = FALSE;
					$data["message_error"] = "No valid Recipient.";
				}


			}

			echo json_encode($data);
			
		}
		# code...
	}

	public function get_data($arg='')
	{
		$sms_id = $this->input->get("sms_id");
		$get_data["sms_id"] = $sms_id;
		echo json_encode($this->sms_model->get_sms_list($get_data));
	}

	public function resend($value='')
	{
		$sms_id = $this->input->post("id");
		$get_data["sms_id"] = $sms_id;
		$sms_list = $this->sms_model->get_sms_list($get_data);
		$data["is_success"] = TRUE;
		foreach ($sms_list as $sms_data) {
			$resend_data["status_code"] = send_sms($sms_data["mobile_number"],$sms_data["message"]);
			$resend_data["status"] = sms_status($resend_data["status_code"]);
			$this->sms_model->resend($resend_data,$sms_data["id"]);

			if($resend_data["status"]!=0){
				$data["is_success"] = FALSE;
			}
		}
		echo json_encode($data);


	}
}
