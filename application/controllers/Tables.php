<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tables extends CI_Controller {

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
		$this->load->model('staffs_model');
		$this->load->model('guardian_model');
		$this->load->model("admin_model");
		$this->load->model("classes_model");
		$this->load->model("teachers_model");
		$this->load->model("students_model");
		$this->load->model("gate_logs_model");
		$this->load->model("rfid_model");
		$this->load->model("canteen_model");
		$this->load->model("canteen_items_model");

		$this->load->library('form_validation');
		$this->load->library('session');
		
		$this->form_validation->set_error_delimiters('', '');
	}

	public function students($arg='',$arg_2='')
	{
		
		if($arg=="list"){

				$page = $this->input->get("page");
				$search = "";
				$where = "";

				if($this->input->get("search_last_name")){
					$search["search"] = "last_name";
					$search["value"] = $this->input->get("search_last_name");
				}

				if($this->input->get("owner_id")){
					$search = "";
					$where["id"] = $this->input->get("owner_id");
				}
				if($arg_2=="jbtech"){
					$where["rfid_status"] = 0;
				}

				if($this->input->get("class_id")){
					$where["class_id"] = $this->input->get("class_id");
				}

				$students_list_data = $this->students_model->get_list($where,$page,$this->config->item("max_item_perpage"),$search);

				// var_dump($this->input->get("owner_id"));
				// var_dump($students_list_data["query"]);
				// exit;
				foreach ($students_list_data["result"] as $student_data) {
					if($student_data->guardian_id!=0){
						$get_data = array();
						$get_data["id"] = $student_data->guardian_id;
						$student_data->guardian_data = $this->guardian_model->get_data($get_data,TRUE);
					}else{
						$student_data->guardian_data = new stdClass();
						$student_data->guardian_data->name = "";
					}

					$get_data = array();
					$get_data["ref_id"] = $student_data->id;
					$get_data["ref_table"] = "students";
					$rfid_data = $this->rfid_model->get_data($get_data);
					$get_data = array();
					$get_data["id"] = $student_data->class_id;
					$class_data = $this->classes_model->get_data($get_data,TRUE);
					$class_name = ($class_data!=NULL?$class_data->class_name:"");
					if($arg_2=="jbtech"){
						echo '
						<tr>
							<td>'.$student_data->first_name.'</td>
							<td>'.$student_data->middle_name.'</td>
							<td>'.$student_data->last_name.'</td>
							<td>'.date("m/d/Y",$student_data->birthdate).'</td>
							<td>'.$student_data->guardian_data->name.'</td>
							<td>'.$student_data->contact_number.'</td>
							<td>'.$class_name.'</td>
							<td><a href="#" class="view_student" id="'.$student_data->id.'">View</a></td>
						</tr>
						';

					}elseif ($arg_2=="teachers") {
						echo '
						<tr>
							<td>'.$student_data->last_name.'</td>
							<td>'.$student_data->first_name.'</td>
							<td>'.$student_data->middle_name.'</td>
							<td>'.$student_data->suffix.'</td>
							<td>'.$student_data->gender.'</td>
							<td>'.age($student_data->birthdate).'</td>
							<td>'.date("m/d/Y",$student_data->birthdate).'</td>
							<td>'.$student_data->contact_number.'</td>
						</tr>
						';
					}else{

						if($rfid_data->rfid==""){
							$rfid_status = '<a href="#" class="add_rfid_student" id="'.$student_data->id.'">Scan</a>';
						}else{
							$rfid_status = '<a href="#" class="delete_rfid_student" id="'.$student_data->id.'">'.$rfid_data->rfid.'</a>';
						}
						echo '
						<tr>
							<td>'.$rfid_status.'</td>
							<td>'.$student_data->last_name.'</td>
							<td>'.$student_data->first_name.'</td>
							<td>'.$student_data->middle_name.'</td>
							<td>'.$student_data->suffix.'</td>
							<td>'.$student_data->gender.'</td>
							<td>'.age($student_data->birthdate).'</td>
							<td>'.date("m/d/Y",$student_data->birthdate).'</td>
							<td>'.$student_data->guardian_data->name.'</td>
							<td>'.$student_data->contact_number.'</td>
							<td>'.$class_name.'</td>
							<td><a href="#" class="edit_student" id="'.$student_data->id.'">Edit info</a></td>
							<td><a href="#" class="delete_student" id="'.$student_data->id.'" data-balloon="Delete" data-balloon-pos="down">&times;</a></td>
						</tr>
						';
					}
				}
				$attrib["href"] = "#";
				$attrib["class"] = "paging";
				echo paging($page,$students_list_data["count"],$this->config->item("max_item_perpage"),$attrib,'<tr><td colspan="20" style="text-align:center">','</td></tr>');	
		}	
	}


	public function teachers($arg='',$arg_2='')
	{
		
		if($arg=="list"){
			$page = $this->input->get("page");
			$search = "";
			$where = "";

			if($this->input->get("search_last_name")){
				$search["search"] = "last_name";
				$search["value"] = $this->input->get("search_last_name");
			}

			if($this->input->get("owner_id")){
				$search = "";
				$where["id"] = $this->input->get("owner_id");
			}
			if($arg_2=="jbtech"){
				$where["rfid_status"] = 0;
			}

			$teachers_list_data = $this->teachers_model->get_list($where,$page,$this->config->item("max_item_perpage"),$search);

			// var_dump($teachers_list_data);
			// exit;
			foreach ($teachers_list_data["result"] as $teacher_data) {
				$get_data = array();
				$get_data["ref_id"] = $teacher_data->id;
				$get_data["ref_table"] = "teachers";
				$rfid_data = $this->rfid_model->get_data($get_data);
				if($arg_2=="jbtech"){
					echo '
					<tr>
						<td>'.$teacher_data->first_name.'</td>
						<td>'.$teacher_data->middle_name.'</td>
						<td>'.$teacher_data->last_name.'</td>
						<td>'.$teacher_data->suffix.'</td>
						<td>'.$teacher_data->gender.'</td>
						<td>'.date("m/d/Y",$teacher_data->birthdate).'</td>
						<td>'.$teacher_data->contact_number.'</td>
						<td>'.$teacher_data->class_data->class_name.'</td>
						<td><a href="#" class="view_teacher" id="'.$teacher_data->id.'">View</a></td>
					</tr>
					';
				}else{
					
					if($rfid_data->rfid==""){
						$rfid_status = '<a href="#" class="add_rfid_teacher" id="'.$teacher_data->id.'">Scan</a>';
					}else{
						$rfid_status = '<a href="#" class="delete_rfid_teacher" id="'.$teacher_data->id.'">'.$rfid_data->rfid.'</a>';
					}
					echo '
					<tr>
						<td>'.$rfid_status.'</td>
						<td>'.$teacher_data->first_name.'</td>
						<td>'.$teacher_data->middle_name.'</td>
						<td>'.$teacher_data->last_name.'</td>
						<td>'.$teacher_data->suffix.'</td>
						<td>'.$teacher_data->gender.'</td>
						<td>'.age($teacher_data->birthdate).'</td>
						<td>'.date("m/d/Y",$teacher_data->birthdate).'</td>
						<td>'.$teacher_data->contact_number.'</td>
						<td>'.$teacher_data->class_data->class_name.'</td>
						<td><a href="#" class="edit_teacher" id="'.$teacher_data->id.'">Edit info</a></td>
						<td><a href="#" class="reset_password_teacher" id="'.$teacher_data->id.'">Reset Password</a></td>
						<td><a href="#" class="delete_teacher" id="'.$teacher_data->id.'" data-balloon="Delete" data-balloon-pos="down">&times;</a></td>
					</tr>
					';
				}

			}
			$attrib["href"] = "#";
			$attrib["class"] = "paging";
			echo paging($page,$teachers_list_data["count"],$this->config->item("max_item_perpage"),$attrib,'<tr><td colspan="20" style="text-align:center">','</td></tr>');	
		}
	}


	public function staffs($arg='',$arg_2='')
	{
		
		if($arg=="list"){
			$page = $this->input->get("page");
			$search = "";
			$where = "";

			if($this->input->get("search_last_name")){
				$search["search"] = "last_name";
				$search["value"] = $this->input->get("search_last_name");
			}

			if($this->input->get("owner_id")){
				$search = "";
				$where["id"] = $this->input->get("owner_id");
			}
			if($arg_2=="jbtech"){
				$where["rfid_status"] = 0;
			}

			$staffs_list_data = $this->staffs_model->get_list($where,$page,$this->config->item("max_item_perpage"),$search);

			// var_dump($staffs_list_data);
			// exit;
			foreach ($staffs_list_data["result"] as $staff_data) {
				$get_data = array();
				$get_data["ref_id"] = $staff_data->id;
				$get_data["ref_table"] = "staffs";
				$rfid_data = $this->rfid_model->get_data($get_data);
				if($arg_2=="jbtech"){
					echo '
					<tr>
						<td>'.$staff_data->first_name.'</td>
						<td>'.$staff_data->middle_name.'</td>
						<td>'.$staff_data->last_name.'</td>
						<td>'.date("m/d/Y",$staff_data->birthdate).'</td>
						<td>'.$staff_data->contact_number.'</td>
						<td>'.$staff_data->position.'</td>
						<td><a href="#" class="view_staff" id="'.$staff_data->id.'">View</a></td>
					</tr>
					';
				}else{
					
					if($rfid_data->rfid==""){
						$rfid_status = '<a href="#" class="add_rfid_staff" id="'.$staff_data->id.'">Scan</a>';
					}else{
						$rfid_status = '<a href="#" class="delete_rfid_staff" id="'.$staff_data->id.'">'.$rfid_data->rfid.'</a>';
					}
					echo '
					<tr>
						<td>'.$rfid_status.'</td>
						<td>'.$staff_data->first_name.'</td>
						<td>'.$staff_data->middle_name.'</td>
						<td>'.$staff_data->last_name.'</td>
						<td>'.$staff_data->suffix.'</td>
						<td>'.$staff_data->gender.'</td>
						<td>'.age($staff_data->birthdate).'</td>
						<td>'.date("m/d/Y",$staff_data->birthdate).'</td>
						<td>'.$staff_data->contact_number.'</td>
						<td>'.$staff_data->position.'</td>
						<td><a href="#" class="edit_staff" id="'.$staff_data->id.'">Edit info</a></td>
						<td><a href="#" class="delete_staff" id="'.$staff_data->id.'" data-balloon="Delete" data-balloon-pos="down">&times;</a></td>
					</tr>
					';
				}

			}
			$attrib["href"] = "#";
			$attrib["class"] = "paging";
			echo paging($page,$staffs_list_data["count"],$this->config->item("max_item_perpage"),$attrib,'<tr><td colspan="20" style="text-align:center">','</td></tr>');	
		}
	}

	public function classes($arg='')
	{
		
		if($arg=="list"){
				$page = $this->input->get("page");
				$where = "";
				if($this->input->get("id")){
					$where["id"] = $this->input->get("id");
				}
				$classes_list_data = $this->classes_model->get_list($where,$page,$this->config->item("max_item_perpage"));

				// var_dump($classes_list_data["result"]);
				// exit;
				foreach ($classes_list_data["result"] as $class_data) {
					echo '
					<tr>
						<td>'.$class_data->class_name.'</td>
						<td>'.$class_data->grade.'</td>
						<td>'.$class_data->room.'</td>
						<td>'.$class_data->schedule.'</td>
						<td>'.$class_data->teacher_data->full_name.'</td>
						<td><a href="#" class="edit_class" id="'.$class_data->id.'">Edit info</a></td>
						<td><a href="#" class="delete_class" id="'.$class_data->id.'" data-balloon="Delete" data-balloon-pos="down">&times;</a></td>
					</tr>
					';
				}
				$attrib["href"] = "#";
				$attrib["class"] = "paging";
				echo paging($page,$classes_list_data["count"],$this->config->item("max_item_perpage"),$attrib,'<tr><td colspan="20" style="text-align:center">','</td></tr>');	
		}
	}

	public function guardians($arg='')
	{
		if($arg=="list"){

				$page = $this->input->get("page");
				$where = "";
				if($this->input->get("id")){
					$where["id"] = $this->input->get("id");
				}
				$guardians_list_data = $this->guardian_model->get_list($where,$page,$this->config->item("max_item_perpage"));

				// var_dump($guardians_list_data["query"]);
				// exit;
				foreach ($guardians_list_data["result"] as $guardian_data) {
					echo '
					<tr>
						<td>'.$guardian_data->name.'</td>
						<td>'.$guardian_data->contact_number.'</td>
						<td>'.$guardian_data->email_address.'</td>
						<td style="text-align: center">'.($guardian_data->email_subscription==1?"YES":"NO").'</td>
						<td style="text-align: center">'.($guardian_data->sms_subscription==1?"YES":"NO").'</td>
						<td><a href="#" class="edit_guardian" id="'.$guardian_data->id.'">Edit info</a></td>
						<td><a href="#" class="reset_password_guardian" id="'.$guardian_data->id.'">Reset Password</a></td>
						<td><a href="#" class="delete_guardian" id="'.$guardian_data->id.'" data-balloon="Delete" data-balloon-pos="down">&times;</a></td>
					</tr>
					';
				}
				$attrib["href"] = "#";
				$attrib["class"] = "paging";
				echo paging($page,$guardians_list_data["count"],$this->config->item("max_item_perpage"),$attrib,'<tr><td colspan="20" style="text-align:center">','</td></tr>');	
		}
	}


	public function gate_logs($arg='')
	{
		$where = "";
		$table = ((
			$this->input->get("ref_table") ||
			$this->input->get("ref_table")=="students" ||
			$this->input->get("ref_table")=="teachers"
			)?$this->input->get("ref_table"):"students");
		$page = $this->input->get("page");
		($this->input->get("ref_id")?$where["ref_id"]=$this->input->get("ref_id"):FALSE);
		($this->input->get("class_id")?$where["class_id"]=$this->input->get("class_id"):FALSE);
		($this->input->get("position")?$where["position"]=$this->input->get("position"):FALSE);
		if(!$this->input->get("ref_id")&&$this->input->get("search_last_name")){
			$this->db->where("last_name",$this->input->get("search_last_name"));
		}
		($this->input->get("date_from")?$date_from=strtotime($this->input->get("date_from")):$date_from=0);	
		($this->input->get("date_to")?$date_to=strtotime($this->input->get("date_to")):$date_to=strtotime(date("m/d/Y")));
		$between = "date BETWEEN '".$date_from."' AND '".$date_to."'";

		// $where["ref_id"]=1;
		// $where["ref_table"]="students";
		// if($this->input->get("search_last_name")){
		// 	$this->db->like('last_name', $this->input->get("search_last_name"));
		// }
		$for_guardian = ($this->input->get("for_guardian")?TRUE:FALSE);
		// var_dump($between);
		// exit;
		// var_dump($this->gate_logs_model->get_list($where,$between,$page,$this->config->item("max_item_perpage")));exit;
		// $students_log_data = $this->gate_logs_model->get_list($where,$between,$page,$this->config->item("max_item_perpage"));
		$gate_logs_data = $this->gate_logs_model->get_list($table,$where,$between,$page,$this->config->item("max_item_perpage"));
		// echo '
		// <tr>
		// 	<td>';
		// // echo $gate_logs_data["query"];
		// // echo "<br><br>";
		// // echo $gate_logs_data["count"];
		// echo $gate_logs_data["query"].'</td>
		// </tr>
		// ';

		// var_dump($gate_logs_data);
		// exit;
		foreach ($gate_logs_data["result"] as $gate_log_data) {
			if($gate_log_data->type=="exit"){
				$status = "danger";
			}else{
				$status = "success";
			}
			if($for_guardian){
				echo '
					<tr class="'.$status.'">
						<td>'.strtoupper($gate_log_data->type).'</td>
						<td>'.date("m/d/Y",$gate_log_data->date).'</td>
						<td>'.date("h:i:s A",$gate_log_data->date_time).'</td>
					</tr>
				';
			}else{
				echo '
					<tr class="'.$status.'">
						
						<td>'.$gate_log_data->owner_data->id.'</td>
						<td><a href="#" id="'.$gate_log_data->owner_data->id.'" class="gate_logs">'.$gate_log_data->owner_data->last_name.", ".$gate_log_data->owner_data->first_name." ".$gate_log_data->owner_data->middle_name[0].". ".$gate_log_data->owner_data->suffix.'</td>
						<td>'.date("m/d/Y",$gate_log_data->date).'</td>
						<td>'.date("h:i:s A",$gate_log_data->date_time).'</td>
						<td>'.strtoupper($gate_log_data->type).'</td>
					</tr>
				';
			}

		}
		$attrib["href"] = "#";
		$attrib["class"] = "paging";
		echo paging($page,$gate_logs_data["count"],$this->config->item("max_item_perpage"),$attrib,'<tr><td colspan="20" style="text-align:center">','</td></tr>');	
		// $attrib["href"] = "#";
		// $attrib["class"] = "paging";
		// echo paging($page,$students_log_data["count"],$this->config->item("max_item_perpage"),$attrib,'<tr><td colspan="20" style="text-align:center">','</td></tr>');
	}

	public function canteen($arg='')
	{
		$canteen_user_data = $this->session->userdata("canteen_sessions");
		if($arg=="item_list"){
			$page = $this->input->get("page");
			$get_list_where["deleted"] = 0;
			$get_list_where["canteen_id"] = $canteen_user_data->canteen_id;
			$item_list_data = $this->canteen_items_model->get_list($get_list_where,$page,$this->config->item("max_item_perpage"));
			foreach ($item_list_data["result"] as $item_data) {
				echo '
				<tr>
					<td>'.$item_data->category.'</td>
					<td>'.$item_data->item_name.'</td>
					<td style="text-align:right">'.number_format($item_data->cost_price,2).'</td>
					<td style="text-align:right">'.number_format($item_data->selling_price,2).'</td>
					<td style="text-align:center">'.$item_data->stocks.'</td>
				</tr>
				';
			}
			$attrib["href"] = "#";
			$attrib["class"] = "paging";
			echo paging($page,$item_list_data["count"],$this->config->item("max_item_perpage"),$attrib,'<tr><td colspan="20" style="text-align:center">','</td></tr>');

			// echo '<tr><td>';
			// var_dump($item_list_data);
			// echo '</td></tr>';
		}elseif ($arg=="sales_cart") {
			$cart_list = $this->session->userdata("canteen_sales_cart");
			if(isset($cart_list["items"])){
				// echo "<table>";
				$total = 0;
				foreach ($cart_list["items"] as $cart_data) {
					$get_data["id"] = $cart_data["id"];
					$item_data = $this->canteen_items_model->get_data($get_data,TRUE);
					$line_total = $item_data["selling_price"]*$cart_data["quantity"];
					$total += $line_total;
					echo '
					<tr>
						<td><a class="sales_cart-remove_item" href="#" title="Remove" id="item_'.$cart_data["id"].'">&times;</a></td>
						<td>'.$item_data["item_name"].'</td>
						<td style="text-align:center;">'.$item_data["stocks"].'</td>
						<td style="text-align:center;"><input type="number" class="sales_cart_quantity" name="quantity" min="1" max="'.$item_data["stocks"].'" value="'.$cart_data["quantity"].'" id="item_'.$cart_data["id"].'"></td>
						<td style="text-align:right;" id="item_'.$cart_data["id"].'">'.number_format($item_data["selling_price"],2).'</td>
						<td style="text-align:right;" id="item_'.$cart_data["id"].'" class="line_total">'.number_format($line_total,2).'</td>
					</tr>
					';
				}
				echo '
					<tr class="success">
						<th colspan="5" style="text-align:right;">TOTAL:</th>
						<th style="text-align:right;" class="cart_total">'.number_format($total,2).'</th>
					</tr>
				';
				// echo "</table>";
			}else{
				echo '
					<tr class="success">
						<th colspan="5" style="text-align:right;">TOTAL:</th>
						<th style="text-align:right;" class="cart_total">0.00</th>
					</tr>
				';
			}
			# code...
		}
	}

	public function sms($arg='')
	{
		$this->load->model("sms_model");
		if($arg=="threads_list"){
			$where ="";
			$page = $this->input->get("page");
			($this->input->get("sent_by_id")?$where["sent_by_id"]=$this->input->get("sent_by_id"):FALSE);
			($this->input->get("sent_by_table")?$where["sent_by_table"]=$this->input->get("sent_by_table"):FALSE);
			($this->input->get("date_from")?$date_from=strtotime($this->input->get("date_from")):$date_from=0);	
			($this->input->get("date_to")?$date_to=strtotime($this->input->get("date_to")):$date_to=strtotime(date("m/d/Y")));

			$between = "date BETWEEN '".$date_from."' AND '".$date_to."'";
			$messages_list = $this->sms_model->get_list($where,$between,$page,$this->config->item("max_item_perpage"));
			// var_dump($messages_list["	query"]);
			// exit;	

			foreach ($messages_list["result"] as $message_data) {
				$get_data = array();
				if($message_data->sent_by_table=="admins"){
					$get_data["id"] = $message_data->sent_by_id;
					$admin_data = $this->admin_model->get_data($get_data);
					$message_data->sender_data = new stdClass();
					$message_data->sender_data->name = $admin_data->username;
					$message_data->sender_data->type = "Admin";
				}elseif($message_data->sent_by_table=="teachers"){
					$get_data["id"] = $message_data->sent_by_id;
					$teachers_data = $this->teachers_model->get_data($get_data);
					$message_data->sender_data = new stdClass();
					$message_data->sender_data->name = $teachers_data["first_name"]." ".$teachers_data["middle_name"][0].". ".$teachers_data["last_name"];;
					$message_data->sender_data->type = "Teachers";
				}else{
					$message_data->sender_data = new stdClass();
					$message_data->sender_data->name = "";
					$message_data->sender_data->type = "";
				}

				
				if($this->sms_model->check_messages($message_data->id)){
					$threads_status = "SENT";
				}else{
					$threads_status = '<a href="#" class="resend_sms" id="'.$message_data->id.'">RESEND</a>';
				}
				$get_data = array();
				$get_data["sms_id"] = $message_data->id;
				$message_content = $this->sms_model->get_sms_data($get_data);
				if($message_content==array()){
					$message_content["message"] = "";
				}
				echo '
				<tr>
					<td><a href="#" class="message" id="'.$message_data->id.'">'.$message_data->id.'</a></td>
					<td>'.$message_content["message"].'</td>
					<td>'.date("m/d/Y",$message_data->date).'</td>
					<td>'.date("h:i:s A",$message_data->date_time).'</td>
					<td>'.$message_data->sender_data->name.'</td>
					<td>'.$message_data->sender_data->type.'</td>
					<td>'.$threads_status.'</td>
				</tr>
				';
			}

			$attrib["href"] = "#";
			$attrib["class"] = "paging";
			echo paging($page,$messages_list["count"],$this->config->item("max_item_perpage"),$attrib,'<tr><td colspan="20" style="text-align:center">','</td></tr>');
		}
	}
}