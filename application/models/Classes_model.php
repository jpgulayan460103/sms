<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Classes_model extends CI_Model {

    function __construct(){
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }

    function add($data=""){
        $this->db->insert("classes",$data);
        $this->db->limit(1);
        $this->db->order_by("id","DESC");
        return $this->db->get("classes")->row();
    }

    function get_list($where='',$page=1,$maxitem=50,$search=""){
        if($where==""){
            $this->db->where('deleted=0');
        }else{
            $this->db->where($where);
        }
        if($search!=""){
            $this->db->like($search["search"],$search["value"]);
        }
            $limit = ($page*$maxitem)-$maxitem;
            $this->db->limit($maxitem,$limit);
            $query = $this->db->get("classes");
            $data["query"] = $this->db->last_query();
            $data["count"] = $this->db->count_all_results("classes");
            $classes_data = $query->result();
            foreach ($classes_data as $class_data) {
            	if($class_data->teacher_id != 0){
            		$get_data["id"] = $class_data->teacher_id;
            		$teacher_query = $this->db->get_where("teachers",$get_data);
            		$class_data->teacher_data = $teacher_query->row();
            		$class_data->teacher_data->full_name = $class_data->teacher_data->last_name.", ".$class_data->teacher_data->first_name." ".$class_data->teacher_data->middle_name[0].". ".$class_data->teacher_data->suffix;
            	}else{
            		$teacher_data = new stdClass();
            		$teacher_data->id = 0;
            		$teacher_data->full_name = "";

            		$class_data->teacher_data = $teacher_data;
            	}

            }
            $data["result"] = $classes_data;

            
            return $data;
    }

    function get_data($where='',$to_object=FALSE){
        $query = $this->db->get_where("classes",$where);
        if($to_object){
            return $query->row();
        }else{
            return $query->row_array();
        }
    }

    function edit($data="",$class_id=""){
    	$this->db->where("id",$class_id);
    	$this->db->set($data);
    	// $this->db->insert('classes'); 
        return $this->db->update('classes');
        // return $this->db->get("classes")->last_row();
    }

    function change_class($teacher_id='',$class_id="")
    {

        $this->db->where("id",$class_id);
        $class_data = $this->db->get("classes")->row();

        $this->db->where("id",$teacher_id);
        $teacher_data = $this->db->get("teachers")->row();

        //clear all assignments
        if($class_id!=0){
        	$this->db->set("class_id","0");
        	$this->db->where("id",$class_data->teacher_id);
        	$this->db->update('teachers');

        	$this->db->set("teacher_id","0");
        	$this->db->where("id",$class_data->id);
        	$this->db->update('classes');
        }

        if($teacher_id!=0){
        	$this->db->set("teacher_id","0");
        	$this->db->where("id",$teacher_data->class_id);
        	$this->db->update('classes');

        	$this->db->set("class_id","0");
        	$this->db->where("id",$teacher_data->id);
        	$this->db->update('teachers');

        }
        //reassign teachers and classes
        $this->db->set("class_id",$class_id);
        $this->db->where("id",$teacher_id);
        $this->db->update('teachers');

        $this->db->set("teacher_id",$teacher_id);
        $this->db->where("id",$class_id);
        $this->db->update('classes');

    }

    function delete($data='',$id=''){
            $this->db->where('id', $id);
            $this->db->update('classes', $data);
            
            $this->db->where("class_id",$id);
            $this->db->set("class_id",0);
            $this->db->update('teachers');

            $this->db->where('id', $id);
            return $this->db->get("classes")->row();
            # code...
    }

}