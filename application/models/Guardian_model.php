<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guardian_model extends CI_Model {

    function __construct(){
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        
    }

    function add($data=""){
        return ($this->db->insert("guardians",$data));
    }


    function delete($value=''){
    	# code...
    }

    function get_list($where='',$page=1,$maxitem=50){
    	if($where==""){
            $this->db->where("deleted",0);
        }else{
            $this->db->where($where);
        }
        $limit = ($page*$maxitem)-$maxitem;
        $this->db->limit($maxitem,$limit);
        $query = $this->db->get("guardians");
        $data["query"] = $this->db->last_query();
        $data["result"] = $query->result();
        $data["count"] = $this->db->count_all_results("guardians");
        return $data;
    }

    function get_data($where,$to_object=FALSE){
        $query = $this->db->get_where("guardians",$where);
        if($to_object){
            return $query->row();
        }else{
            return $query->row_array();
        }
    }

    function login($data='')
    {
        $login_query = $this->db->get_where("guardians",$data);
        $data = $login_query->row(0);
        $this->session->set_userdata("guardian_sessions",$data);
        return ($login_query->num_rows()===1);
    }

    function edit_info($data='',$id=''){
        $this->db->where('id', $id);
        $this->db->update('guardians', $data);
        $this->db->where('id', $id);
        return $this->db->get("guardians")->row();
        # code...
    }
}


?>