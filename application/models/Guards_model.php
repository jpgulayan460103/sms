<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guards_model extends CI_Model {

    function __construct(){
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }

    function add($students_data=""){
        $this->db->insert("guards",$students_data);
        return $this->db->get("guards")->last_row();
    }

    function edit_info($data='',$id=''){
        $this->db->where('id', $id);
        $this->db->update('guards', $data);
        return $this->db->last_query();
    	# code...
    }

    function delete($value=''){
    	# code...
    }

    function get_list($where='',$page=1,$maxitem=50){
        if($where==""){
            $this->db->where('deleted="0"');
        }else{
            $this->db->where($where);
        }
            $limit = ($page*$maxitem)-$maxitem;
            $this->db->limit($maxitem,$limit);
            $query = $this->db->get("guards");
            $data["query"] = $this->db->last_query();
            $data["result"] = $query->result();
            $data["count"] = $this->db->count_all_results("guards");
            return $data;
    }

    function get_data($where=''){
    	$query = $this->db->get_where("guards",$where);
        return $query->row_array();
    }

    function scangate($data='')
    {
        $query = $this->db->get_where("guards",$data);
    }

    function add_load_credits($data='')
    {
        $query = $this->db->get_where("guards",'id="'.$data["id"].'"');
        $student_data = $query->row();

        $new_load = $student_data->load_credits+$data["load_credits"];
        $new_load_data["load_credits"] = $new_load;
        $this->db->where('id="'.$data["id"].'"');
        $this->db->update('guards', $new_load_data);

        $query = $this->db->get_where("guards",'id="'.$data["id"].'"');
        return $query->row_array();
    }

}


?>