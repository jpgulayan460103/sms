<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation{

    //this function returns true if
    public function custom_alpha_dash($str) {
    	$str = trim($str);
    	$str_array = explode(" ", $str); 
    	foreach ($str_array as $str) {
            for ($count=0; $count < strlen($str); $count++) { 
                if($this->alpha($str[$count])||preg_match("/[-.]/i", $str[$count])||preg_match("/[ñÑ]/i", $str[$count])){
                    $i = TRUE;
                }else{
                    $i = FALSE;
                    break;
                }
            }
            if($i==FALSE){
                break;
            }
    	}
    	return $i;
    }

    public function is_in_db($str,$field)
    {
        sscanf($field, '%[^.].%[^.]', $table, $field);
        return isset($this->CI->db)
            ? ($this->CI->db->get_where($table, array($field => $str))->num_rows()===1)
            : FALSE;
    }

    public function is_available($str,$field)
    {
        sscanf($field, '%[^.].%[^.]', $table, $field);
        $this->CI->db->where(array($field => $str));
        $this->CI->db->where(array("deleted" => 0));
        return isset($this->CI->db)
            ? ($this->CI->db->get($table)->num_rows()===0)
            : FALSE;
    }

    public function is_valid_date($str, $field)
    {
        sscanf($field, '%[^.].%[^.].%[^.]', $m, $d, $y);
        return isset($this->_field_data[$m], $this->_field_data[$m]['postdata'],$this->_field_data[$d], $this->_field_data[$d]['postdata'],$this->_field_data[$y], $this->_field_data[$y]['postdata'])
            ? checkdate($this->_field_data[$m]['postdata'],$this->_field_data[$d]['postdata'],$this->_field_data[$y]['postdata'])
            : FALSE;
    }

    public function is_valid_rfid($rfid)
    {
        $this->CI->db->where(array("rfid" => $rfid));
        $this->CI->db->where(array("valid" => 1));
        $this->CI->db->where(array("deleted" => 0));
        $this->CI->db->where("valid_date >=",strtotime(date("m/d/Y")));
        return isset($this->CI->db)
            ? ($this->CI->db->get("rfid")->num_rows()===1)
            : FALSE;
    }

    public function is_valid($str,$field)
    {
        sscanf($field, '%[^.].%[^.]', $table, $field);
        $this->CI->db->where(array($field => $str));
        $this->CI->db->where(array("deleted" => 0));
        return isset($this->CI->db)
            ? ($this->CI->db->get($table)->num_rows()===1)
            : FALSE;
    }

    public function is_unique_edit($str,$field)
    {
        sscanf($field, '%[^.].%[^.].%[^.]', $table, $field, $id);
        $this->CI->db->where(array($field => $str));
        $this->CI->db->where(array("deleted" => 0));
        $this->CI->db->where("id !=",$this->_field_data[$id]['postdata']);
        return isset($this->CI->db)
            ? ($this->CI->db->get($table)->num_rows()===0)
            : FALSE;
    }

    public function is_valid_current_password($str,$field)
    {
        sscanf($field, '%[^.].%[^.]', $table, $id);
        $this->CI->db->where("deleted",0);
        $this->CI->db->where("password",md5($str));
        $this->CI->db->where("id",$this->_field_data[$id]['postdata']);

        return isset($this->CI->db)
            ? ($this->CI->db->get($this->_field_data[$table]['postdata'])->num_rows()===1)
            : FALSE;
    }


    public function email_subscription($str,$email_address)
    {
        if(isset($this->_field_data[$str], $this->_field_data[$str]['postdata'])){
            return TRUE;
        }else{
            return is_array($this->_field_data[$email_address]['postdata'])
                ? (empty($this->_field_data[$email_address]['postdata']) === FALSE)
                : (trim($this->_field_data[$email_address]['postdata']) !== '');
        }
    }
}
?>