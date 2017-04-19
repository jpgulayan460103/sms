<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    function __construct(){
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        
    }

    function add($data=""){

    }

    function edit_info($value=''){
    	# code...
    }

    function delete($value=''){
    	# code...
    }

    function get_list($value=''){
        $this->db->where("deleted",0);
    	return $this->db->get("admins")->result();
    }

    function get_data($data=''){
    	$this->db->where($data);
        return $this->db->get("admins")->row();
    }

    function login($data='')
    {
        $login_query = $this->db->get_where("admins",$data);
        $data = $login_query->row(0);
        $this->session->set_userdata("admin_sessions",$data);
        return ($login_query->num_rows()===1);
    }

    

}


?>