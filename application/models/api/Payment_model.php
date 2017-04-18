<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payment_model extends CI_Model 
{
    //var $table = 'payment';

    function __construct() 
    {
        parent::__construct();
    }

    function payment($data = array()) 
    {
        $table_name = 'payment';
        $this->db->insert($table_name, $data);
        $last_insert_id = $this->db->insert_id();
        
        $this->db->where('payment_id', $last_insert_id);
        $this->db->update($table_name,['order_id'=>$last_insert_id]);
        if (empty($last_insert_id)) {
            return false;
        } else {
             $return_data = array('payment_id'=>$last_insert_id);
            return $return_data;
        }
        return $return_data;
    }
}