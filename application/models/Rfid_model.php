<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rfid_model extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function add($data)
    {
    	$this->db->insert("rfid",$data);
    }

    function get_data($data,$get_specific_data=FALSE,$to_array=FALSE)
    {
        if($get_specific_data){
            if($to_array){
                $rfid_data = $this->db->get_where("rfid",$data)->row_array();
                $get_data["id"] = $rfid_data["ref_id"];
                $owner_data = $this->db->get_where($rfid_data["ref_table"],$get_data)->row_array();
                $owner_data["rfid_data"] = $rfid_data;
            }else{
                $rfid_data = $this->db->get_where("rfid",$data)->row();
                $get_data["id"] = $rfid_data->ref_id;
                $owner_data = $this->db->get_where($rfid_data->ref_table,$get_data)->row();
                $owner_data->rfid_data = $rfid_data;
            }
            return $owner_data;
        }else{
        	return $this->db->get_where("rfid",$data)->row();
        }
    }

    function get_list($data='')
    {
    	# code...
    }


    function edit_info($data='',$where){
        $this->db->where($where);
        $this->db->update('rfid', $data);
        
        $this->db->where($where);
        return $this->db->get_where("rfid",$data)->row();
        # code...
    }


    function load_credits($data,$load_credits)
    {
    	$rfid_data = $this->db->get_where("rfid",$data)->row_array();

    	$new_load = $rfid_data["load_credits"]+$load_credits;
    	$new_load_data["load_credits"] = $new_load;

    	$this->db->where($data);
    	$this->db->update('rfid', $new_load_data);
    	return $this->db->get_where("rfid",$data)->row_array();

    }

}