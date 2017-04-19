<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Class_ajax extends CI_Controller {

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
		$this->load->helper('string');


		//models
		$this->load->model('guardian_model');
		$this->load->model("admin_model");
		$this->load->model("students_model");
		$this->load->model("gate_logs_model");
		$this->load->model("canteen_model");
		$this->load->model("classes_model");
		$this->load->model("canteen_items_model");

		$this->load->library('form_validation');
		$this->load->library('session');
		
		$this->form_validation->set_error_delimiters('', '');
	}

	public function add($arg='')
	{
		$this->form_validation->set_rules('class_adviser', 'Class Adviser', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('class_name', 'Class Name', 'required|is_available[classes.class_name]|max_length[50]|trim|htmlspecialchars');
		$this->form_validation->set_rules('grade', 'Grade or Year', 'required|max_length[50]|trim|htmlspecialchars');
		$this->form_validation->set_rules('class_room', 'Classroom', 'max_length[50]|trim|htmlspecialchars');
		$this->form_validation->set_rules('class_schedule', 'Class Schedule', 'max_length[50]|trim|htmlspecialchars');
		$this->form_validation->set_message('is_in_db', 'This Teacher is invalid');
		$this->form_validation->set_message('is_available', 'This %s is already existed.');

		$data["class_adviser_error"] = "";
		$data["class_name_error"] = "";
		$data["grade_error"] = "";
		$data["class_room_error"] = "";
		$data["class_schedule_error"] = "";
		if ($this->form_validation->run() == FALSE){
			$data["is_valid"] = FALSE;
			$data["class_adviser_error"] = form_error("class_adviser");
			$data["class_name_error"] = form_error("class_name");
			$data["grade_error"] = form_error("grade");
			$data["class_room_error"] = form_error("class_room");
			$data["class_schedule_error"] = form_error("class_schedule");
		}else{
			$insert_data["class_name"] = $this->input->post("class_name");
			$insert_data["grade"] = $this->input->post("grade");
			$insert_data["teacher_id"] = $this->input->post("class_adviser");
			$insert_data["schedule"] = $this->input->post("class_schedule");
			$insert_data["room"] = $this->input->post("class_room");
			$data["is_valid"] = TRUE;
			$class_data = $this->classes_model->add($insert_data);

			$teacher_id = ($this->input->post("class_adviser")?$this->input->post("class_adviser"):"0");
			$class_id = $class_data->id;
			$this->classes_model->change_class($teacher_id,$class_id);
		}
		echo json_encode($data);
	}

	public function edit($arg='')
	{
		$this->form_validation->set_rules('class_adviser', 'Class Adviser', 'is_valid[teachers.id]|trim|htmlspecialchars');
		$this->form_validation->set_rules('grade', 'Grade or Year', 'required|max_length[50]|trim|htmlspecialchars');
		$this->form_validation->set_rules('class_room', 'Classroom', 'max_length[50]|trim|htmlspecialchars');
		$this->form_validation->set_rules('class_schedule', 'Class Schedule', 'max_length[50]|trim|htmlspecialchars');
		$this->form_validation->set_rules('class_id', 'Class', 'required|is_in_db[classes.id]|trim|htmlspecialchars');
		$this->form_validation->set_rules('class_name', 'Class Name', 'required|is_unique_edit[classes.class_name.class_id]|max_length[50]|trim|htmlspecialchars');
		$this->form_validation->set_message('is_in_db', 'This %s is invalid');
		$this->form_validation->set_message('is_unique_edit', 'This %s is already existed.');

		$data["class_adviser_error"] = "";
		$data["class_name_error"] = "";
		$data["grade_error"] = "";
		$data["class_room_error"] = "";
		$data["class_schedule_error"] = "";
		$data["class_id_error"] = "";
		if ($this->form_validation->run() == FALSE){
			$data["is_valid"] = FALSE;
			$data["class_adviser_error"] = form_error("class_adviser");
			$data["class_name_error"] = form_error("class_name");
			$data["grade_error"] = form_error("grade");
			$data["class_room_error"] = form_error("class_room");
			$data["class_schedule_error"] = form_error("class_schedule");
			$data["class_id_error"] = form_error("class_id");
		}else{
			$update_data["class_name"] = $this->input->post("class_name");
			$update_data["grade"] = $this->input->post("grade");
			// $update_data["teacher_id"] = $this->input->post("class_adviser");
			$update_data["schedule"] = $this->input->post("class_schedule");
			$update_data["room"] = $this->input->post("class_room");
			$class_id = $this->input->post("class_id");
			$data["is_valid"] = $this->classes_model->edit($update_data,$class_id);

			$teacher_id = ($this->input->post("class_adviser")?$this->input->post("class_adviser"):"0");
			$class_id = ($this->input->post("class_id")?$this->input->post("class_id"):"0");
			$this->classes_model->change_class($teacher_id,$class_id);
		}
		echo json_encode($data);
	}

	public function get_data($value='')
	{
		$class_data["id"] = $this->input->get("class_id");
		$class_data = $this->classes_model->get_data($class_data);
		($class_data["id"]==0?$class_data["id"]="":FALSE);
		($class_data["teacher_id"]==0?$class_data["teacher_id"]="":FALSE);
		echo json_encode($class_data);
	}

	public function get_list($arg='')
	{
		$data = $this->classes_model->get_list();
		echo json_encode($data["result"]);
	}

	public function delete()
	{
		if($_POST){
			$data["deleted"] = 1;
			$data["teacher_id"] = 0;
			$this->classes_model->delete($data,$this->input->post("id"));
		}
	}

}