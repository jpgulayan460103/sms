<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gate_logs_model extends CI_Model {

    function __construct(){
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }

    function add_log($data=""){
        
        $get_data["rfid_id"] = $data["rfid_id"];
        $get_data["date"] = $data["date"];
        $this->db->order_by("id","DESC");
        $this->db->limit(1);
        $get_log_data = $this->db->get_where("gate_logs",$get_data)->row();
        if($get_log_data==NULL){
            $data["type"] = "entry";
            $this->db->insert("gate_logs",$data);
            $this->db->limit(1);
            $this->db->order_by("id","DESC");
            $data["is_valid"] = TRUE;
            $data["gate_logs_data"] = $this->db->get("gate_logs")->row();
            return $data;
            exit;
        }elseif ($get_log_data->type=="exit") {
            $data["type"] = "entry";
        }else{
            $data["type"] = "exit";
        }
        $limit_date = strtotime(date("m/d/Y h:i:s A", strtotime("+1 min",$get_log_data->date_time)));
        if($get_log_data->date_time<=$limit_date&&$limit_date<=$data["date_time"]){
            $this->db->insert("gate_logs",$data);
            $this->db->limit(1);
            $this->db->order_by("id","DESC");
            $data["is_valid"] = TRUE;
            $data["gate_logs_data"] = $this->db->get("gate_logs")->row();
        }else{
            $data["is_valid"] = FALSE;
            $data["gate_logs_data"] = FALSE;
        }
        return $data;
    }

    function get_list($table="students",$where='',$between='',$page=1,$maxitem=50)
    {

    
        $limit = ($page*$maxitem)-$maxitem;
        $this->db->limit($maxitem,$limit);

        //start repeat
        $select[] = 'gate_logs.*';
        if($table=="students"){
            $select[] = 'classes.class_name';
            $select[] = $table.'.class_id';
        }
        $select[] = $table.'.first_name';
        $select[] = $table.'.last_name';
        $select[] = $table.'.first_name';

        $this->db->where("ref_table",$table);
        ($where!=""?$this->db->where($where):false);
        ($between!=""?$this->db->where($between):false);

        $this->db->order_by("id","DESC");
        $this->db->select($select);
        $this->db->from('gate_logs');
        $this->db->join($table, $table.'.id = gate_logs.ref_id');
        if($table=="students"){
            $this->db->join("classes", $table.'.class_id = classes.id');
        }
        $gate_logs_query = $this->db->get();
        //end repeat

        // $gate_logs_data = $this->db->get("gate_logs")->result();
        $data["query"] = $this->db->last_query();



        // return $data;
        // exit;
        $gate_logs_data = $gate_logs_query->result();
        foreach ($gate_logs_data as $gate_log_data) {
            $get_rfid_data = array();
            $get_rfid_data["id"] = $gate_log_data->rfid_id;
            $gate_log_data->rfid_data = $this->db->get_where("rfid",$get_rfid_data)->row();
            $get_owner_data = array();
            $get_owner_data["id"] = $gate_log_data->ref_id;
            $gate_log_data->owner_data = $this->db->get_where($gate_log_data->ref_table,$get_owner_data)->row();
            # code...
        }

        $data["result"] = $gate_logs_data;


        // $this->db->select('*, gate_logs.id as gate_logs_id');
        // $this->db->from('gate_logs');
        // $this->db->join('students', 'students.id=gate_logs.student_id');
        // $this->db->order_by("gate_logs_id","DESC");
        // $query = $this->db->get();
        
        // if($where!=""){
        //     $this->db->where($where);
        // }
        // if($between!=""){
        //     $this->db->where($between);
        // }

        // $limit = ($page*$maxitem)-$maxitem;
        // $this->db->select('*, gate_logs.id as gate_logs_id');
        // $this->db->from('gate_logs');
        // $this->db->join('students', 'students.id=gate_logs.student_id');
        // $this->db->order_by("gate_logs_id","DESC");
        // $this->db->limit($maxitem,$limit);
        // $query = $this->db->get();
        // $data["result"] = $query->result();
        // $data["query"] = $this->db->last_query();

        //start repeat
        $select[] = 'gate_logs.*';
        if($table=="students"||$table=="teachers"){
            $select[] = 'classes.class_name';
            $select[] = $table.'.class_id';
        }
        $select[] = $table.'.first_name';
        $select[] = $table.'.last_name';
        $select[] = $table.'.first_name';

        $this->db->where("ref_table",$table);
        ($where!=""?$this->db->where($where):false);
        ($between!=""?$this->db->where($between):false);

        $this->db->order_by("id","DESC");
        $this->db->select($select);
        $this->db->from('gate_logs');
        $this->db->join($table, $table.'.id = gate_logs.ref_id');
        if($table=="students"||$table=="teachers"){
            $this->db->join("classes", $table.'.class_id = classes.id');
        }
        $gate_logs_query = $this->db->get();
        //end repeat
        $data["count"] = $gate_logs_query->num_rows();
        return $data;
    }

}


?>