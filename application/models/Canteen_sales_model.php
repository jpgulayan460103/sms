<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Canteen_sales_model extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        
    }

    function get_data($data='',$to_array=FALSE)
    {
        if($to_array){
            return $this->db->get_where("canteen_sales",$data)->row_array();
        }else{
            return $this->db->get_where("canteen_sales",$data)->row();
        }
    }

    function add($data=""){
        return ($this->db->insert("guardians",$data));
    }

    function edit_info($value=''){
    	# code...
    }
}