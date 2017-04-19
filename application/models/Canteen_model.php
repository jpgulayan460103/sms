<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Canteen_model extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        
    }

    function get_canteens_data($data='')
    {
        return $this->db->get_where("canteens",$data)->row();
    }

    function add($data=""){
        return ($this->db->insert("guardians",$data));
    }

    function edit_info($value=''){
    	# code...
    }

    function delete($value=''){
    	# code...
    }

    function get_list($data=''){
    	if($data==""){
            $query = $this->db->get_where("guardians",'deleted=0');
            return $query->result();
        }
    }

    function get_data($id){
        $this->db->where("id",$id);
    	$query = $this->db->get("guardians");
        return $query->row_array();
    }

    function login($data='')
    {
        $login_query = $this->db->get_where("canteen_users",$data);
        $data = $login_query->row(0);
        $this->session->set_userdata("canteen_sessions",$data);
        return ($login_query->num_rows()===1);
    }

    function get_user_data($data='')
    {
        $data["deleted"] = 0;
        $this->db->where($data);
        $query = $this->db->get("canteen_users");
        return $query->row();
    }

    function sale($cart_data='')
    {
        // return $cart_data;
        $canteen_user_data = $this->session->userdata("canteen_sessions");
        $total_sales = 0;
        $total_cost = 0;
        foreach ($cart_data["items"] as $item_cart_data) {
            $item_db_query = $this->db->get_where("canteen_items",array("id"=>$item_cart_data["id"]));
            $item_db_data = $item_db_query->row_array();

            $total_sales += $item_cart_data["selling_price"] * $item_cart_data["quantity"];
            $total_cost += $item_db_data["cost_price"] * $item_cart_data["quantity"];
            $canteen_id = $item_cart_data["canteen_id"];
        }

        $sales_data["customer_rfid_id"] = (isset($cart_data["customer_data"]["rfid_data"]["id"])?$cart_data["customer_data"]["rfid_data"]["id"]:0);
        $sales_data["customer_name"] = (isset($cart_data["customer_data"]["id"])?$cart_data["customer_data"]["customer_name"]:"");
        $sales_data["date"] = strtotime(date("m/d/Y"));
        $sales_data["date_time"] = strtotime(date("m/d/Y h:i:s A"));
        $sales_data["total_cost"] = $total_cost;
        $sales_data["total_sales"] = $total_sales;
        $sales_data["canteen_id"] = $canteen_id;
        $sales_data["comments"] = (isset($cart_data["comments"])?$cart_data["comments"]:"");
        $sales_data["canteen_user_id"] = $canteen_user_data->id;

        $this->db->insert("canteen_sales",$sales_data);
        $this->db->order_by("id","DESC");
        $this->db->limit(1);
        $new_sale_query = $this->db->get("canteen_sales");
        $new_sale_data = $new_sale_query->row();
        $sale_id = $new_sale_data->id;

        foreach ($cart_data["items"] as $key => $item_cart_data) {
            $item_db_query = $this->db->get_where("canteen_items",array("id"=>$item_cart_data["id"]));
            $item_db_data = $item_db_query->row_array();

            $total_sales += $item_cart_data["selling_price"] * $item_cart_data["quantity"];
            $total_cost += $item_db_data["cost_price"] * $item_cart_data["quantity"];
            $canteen_id = $item_cart_data["canteen_id"];

            $sale_items_data["sale_id"] = $sale_id;
            $sale_items_data["quantity"] = $item_cart_data["quantity"];
            $sale_items_data["item_id"] = $item_cart_data["id"];
            $sale_items_data["cost_price"] = $item_db_data["cost_price"];
            $sale_items_data["selling_price"] = $item_cart_data["selling_price"];
            $sale_items_data["canteen_user_id"] = $canteen_user_data->id;
            $sale_items_data["date"] = strtotime(date("m/d/Y"));
            $sale_items_data["canteen_id"] = $canteen_id;
            $sale_items_data["customer_rfid_id"] = (isset($cart_data["customer_data"]["rfid_data"]["id"])?$cart_data["customer_data"]["rfid_data"]["id"]:0);
            $this->db->insert("canteen_sale_items",$sale_items_data);

            $item_updata_data["stocks"] = $item_db_data["stocks"] - $item_cart_data["quantity"];
            $this->db->set($item_updata_data);
            $this->db->where('id', $item_cart_data["id"]);
            $this->db->update('canteen_items');
        }
        return $new_sale_data;
    }

    function load_credits($rfid,$total_purchase)
    {
        # code...
    }

    function sale_items_is_valid($items='')
    {
        foreach ($items as $item_cart_data) {
            $item_db_query = $this->db->get_where("canteen_items",array("id"=>$item_cart_data["id"]));
            $item_db_data = $item_db_query->row_array();
            if($item_cart_data["quantity"]<=$item_db_data["stocks"] && $item_cart_data["quantity"]>0){
                $i = TRUE;
            }else{
                $i = FALSE;
                break;
            }
        }
        return $i;
    }
}


?>