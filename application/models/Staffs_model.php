<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staffs_model extends CI_Model {

    function __construct(){
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }

    function add($staffs_data=""){
        $this->db->insert("staffs",$staffs_data);
        return $this->db->get("staffs")->last_row();
    }

    function edit_info($data='',$id=''){
        $this->db->where('id', $id);
        $this->db->update('staffs', $data);
        
        $this->db->where('id', $id);
        return $this->db->get("staffs")->row();
    	# code...
    }

    function delete($data='',$id=''){
    	    $this->db->where('id', $id);
            $this->db->update('staffs', $data);

            $this->db->where('id', $id);
            return $this->db->get("staffs")->row();
            # code...
    }

    function get_list($where='',$page=1,$maxitem=50,$search=""){
        if($where==""){
            $this->db->where('deleted="0"');
        }else{
            $this->db->where($where);
        }
        if($search!=""){
            $this->db->like($search["search"],$search["value"]);
        }
            $limit = ($page*$maxitem)-$maxitem;
            $this->db->limit($maxitem,$limit);
            $query = $this->db->get("staffs");
            $data["query"] = $this->db->last_query();
            $staffs_data = $query->result();
            foreach ($staffs_data as $staff_data) {
                $staff_data->full_name = $staff_data->last_name.", ".$staff_data->first_name." ".$staff_data->middle_name[0].". ".$staff_data->suffix;
                $get_data = array();
                $get_data["ref_id"] = $staff_data->id;
                $get_data["ref_table"] = "staffs";
                $staff_data->rfid_data = $this->db->get_where("rfid",$get_data)->row();
            }


            $data["result"] = $staffs_data;



            if($where==""){
                $this->db->where('deleted="0"');
            }else{
                $this->db->where($where);
            }
            $query = $this->db->get("staffs");
            $data["count"] = $query->num_rows();
            
            return $data;
    }

    function get_positions_list($data='')
    {
        $this->db->where("deleted","0");
        $this->db->select("position");
        $this->db->distinct();
        return $this->db->get("staffs")->result();
        # code...
    }

    function get_data($where=''){
        $query = $this->db->get_where("staffs",$where);
        return $query->row_array();
    }

    function scangate($data='')
    {
        $query = $this->db->get_where("staffs",$data);
    }

    function add_load_credits($data='')
    {
        $query = $this->db->get_where("staffs",'id="'.$data["id"].'"');
        $student_data = $query->row();

        $new_load = $student_data->load_credits+$data["load_credits"];
        $new_load_data["load_credits"] = $new_load;
        $this->db->where('id="'.$data["id"].'"');
        $this->db->update('staffs', $new_load_data);

        $query = $this->db->get_where("staffs",'id="'.$data["id"].'"');
        return $query->row_array();
    }

    function login($data='')
    {
        $login_query = $this->db->get_where("staffs",$data);
        $data = $login_query->row(0);
        $this->session->set_userdata("staff_sessions",$data);
        return ($login_query->num_rows()===1);
    }

}


?>