<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {

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
		$this->load->model("canteen_model");
		$this->load->model("search_model");

		$this->canteen_user_data = $this->session->userdata("canteen_sessions");

	}

	public function canteen($type='')
	{
		if($type=="items_to_cart"){
			$search_data["item_name"] = $this->input->get("term");
			$where["deleted"] = "0";
			// $where["stocks >"] = "0";
			$where["canteen_id"] = $this->canteen_user_data->canteen_id;
			$order_by["item_name"] = "ASC";
			$label_value = "item_name";
			$data_value = "id";
			echo $this->search_model->autocomplete_with_value("canteen_items","*",$label_value,$data_value,$search_data,$where,"both",$order_by);
		}
	}

	public function students($type='')
	{
		if($type=="gate_logs"){
			$search_data["last_name"] = $this->input->get("term");
			$where["deleted"] = "0";
			// $where["stocks >"] = "0";
			// $where["canteen_id"] = $this->canteen_user_data->canteen_id;
			$order_by["last_name"] = "ASC";
			$label_value = "last_name";
			$data_value = "id";
			echo $this->search_model->rfid_owner_list($this->input->get("ref_table"),"*",$label_value,$data_value,$search_data,$where,"both",$order_by);
		}elseif ($type=="list") {
			
			$search_data["last_name"] = $this->input->get("term");
			$where["deleted"] = "0";
			// $where["stocks >"] = "0";
			// $where["canteen_id"] = $this->canteen_user_data->canteen_id;
			$order_by["last_name"] = "ASC";
			$label_value = "last_name";
			$data_value = "id";
			echo $this->search_model->rfid_owner_list("students","*",$label_value,$data_value,$search_data,$where,"both",$order_by);
			
		}
	}

	public function teachers($type='')
	{
		if($type=="gate_logs"){
			$search_data["last_name"] = $this->input->get("term");
			$where["deleted"] = "0";
			// $where["stocks >"] = "0";
			// $where["canteen_id"] = $this->canteen_user_data->canteen_id;
			$order_by["last_name"] = "ASC";
			$label_value = "last_name";
			$data_value = "id";
			echo $this->search_model->rfid_owner_list($this->input->get("ref_table"),"*",$label_value,$data_value,$search_data,$where,"both",$order_by);
		}elseif ($type=="list") {
			
			$search_data["last_name"] = $this->input->get("term");
			$where["deleted"] = "0";
			// $where["stocks >"] = "0";
			// $where["canteen_id"] = $this->canteen_user_data->canteen_id;
			$order_by["last_name"] = "ASC";
			$label_value = "last_name";
			$data_value = "id";
			echo $this->search_model->rfid_owner_list("teachers","*",$label_value,$data_value,$search_data,$where,"both",$order_by);
			
		}
	}

}
