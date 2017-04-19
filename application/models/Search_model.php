<?php

class Search_model extends CI_Model {
    public function __construct()
    {
            // Call the CI_Model constructor
            parent::__construct();
    }

    public function autocomplete($table='',$data='',$where='',$distinct=FALSE)
    {
        foreach ($data as $key => $value) {
            $this->db->select($key);
        }
        if($where!=""){
            $where=array("deleted"=>0);
            $this->db->where($where);
        }

        if($distinct){
            $this->db->distinct();
        }
        $this->db->limit(5);

        $this->db->like($data);
        $query = $this->db->get($table);
        return $query->result();
        // return $this->db->last_query();
    }

    public function autocomplete_with_value($table='',$select='',$label_value,$data_value,$data='',$where='',$wildcard='both',$order='',$distinct=FALSE)
    {
        $this->db->select($select);

        if($where!=""){
            $this->db->where($where);
        }

        if($order!=""){
            foreach ($order as $order_by => $value) {
                $this->db->order_by($order_by,$value);
            }
        }


        if($distinct){
            $this->db->distinct();
        }

        $this->db->limit(5);
        $this->db->like($data,'',$wildcard);
        $query = $this->db->get($table);
        // var_dump($this->db->last_query());
        // return $this->db->last_query();
        // $data = $query->result_array();
        $results = $query->result_array();
        $result_data = array();
        if($results){
            foreach ($results as $data) {
                $result_data[] = array("label"=>$data[$label_value],"data"=>$data[$data_value]);
            }
        }
        return json_encode($result_data);
    }

    public function rfid_owner_list($table='',$select='',$label_value,$data_value,$data='',$where='',$wildcard='both',$order='',$distinct=FALSE)
    {
        $this->db->select($select);

        if($where!=""){
            $this->db->where($where);
        }

        if($order!=""){
            foreach ($order as $order_by => $value) {
                $this->db->order_by($order_by,$value);
            }
        }


        if($distinct){
            $this->db->distinct();
        }

        $this->db->limit(5);
        $this->db->like($data,'',$wildcard);
        $query = $this->db->get($table);
        // var_dump($this->db->last_query());
        // return $this->db->last_query();
        // $data = $query->result_array();
        $results = $query->result_array();
        $result_data = array();
        if($results){
            foreach ($results as $data) {
                $data_label = $data["last_name"].", ".$data["first_name"]." ".$data["middle_name"][0].". ".$data["suffix"];
                $result_data[] = array(
                    "label"=>$data_label,
                    "data"=>$data[$data_value],
                    "value"=> ""
                    );
            }
        }
        return json_encode($result_data);
    }
}