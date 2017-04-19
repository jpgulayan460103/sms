<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Canteen_items_model extends CI_Model {

    function __construct(){
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        
    }

    function add($data=""){
        return ($this->db->insert("canteen_items",$data));
    }

    function edit_info($value=''){
    	# code...
    }

    function delete($value=''){
    	# code...
    }

    function get_list($where='',$page='1',$maxitem='50'){
        $limit = ($page*$maxitem)-$maxitem;
        $this->db->limit($maxitem,$limit);
        $this->db->where($where);
        $query = $this->db->get("canteen_items");
        $data["query"] = $this->db->last_query();
        $data["result"] = $query->result();
        $data["count"] = $this->db->count_all_results("canteen_items");
        return $data;
    }

    function get_data($data,$to_array=FALSE){
        $this->db->where($data);
    	$query = $this->db->get("canteen_items");
        if($to_array){
            return $query->row_array();
        }else{
            return $query->row();
        }
    }

    function is_valid_item_to_sale($item_id='')
    {
        # code...
    }
}


?>