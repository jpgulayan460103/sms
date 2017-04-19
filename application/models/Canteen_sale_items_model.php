<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Canteen_sale_items_model extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        
    }

    function get_data($data='',$to_array=FALSE)
    {
        if($to_array){
            return $this->db->get_where("canteen_sale_items",$data)->row_array();
        }else{
            return $this->db->get_where("canteen_sale_items",$data)->row();
        }
    }

    function sales_items_list($data='',$to_array=FALSE)
    {
        if($to_array){
            return $this->db->get_where("canteen_sale_items",$data)->result_array();
        }else{
            return $this->db->get_where("canteen_sale_items",$data)->result();
        }
    }


}