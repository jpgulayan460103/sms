<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App_config extends CI_Model {

    function __construct(){
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }

    function edit_info($data='')
    {
    	
    }

    function login($data='')
    {
    	$login_query = $this->db->get_where("app_config",$data);
    	$data = $login_query->row(0);
    	$this->session->set_userdata("gate_sessions",$data);
    	return ($login_query->num_rows()===1);
    }
}