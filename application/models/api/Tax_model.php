<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tax_model extends CI_Model 
{
    var $table_name = 'taxes';

    function __construct() 
    {
        parent::__construct();
    }

    function get_taxes() 
    {
        $tax_array = array();
        $query = $this->db->select('title,value')
                ->from($this->table_name)->where(['taxes_id' => '1'])->get();
        
        $data = $query->result_array();
        if (!empty($data)) {
            $data_return=[$data[0]['title']=>$data[0]['value']];
             return $data_return;
        }
    }
}