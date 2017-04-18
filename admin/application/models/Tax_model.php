<?php

class Tax_model extends CI_Model {

    var $tax_table = 'taxes';
     
    public function __construct() {
        parent::__construct();
    }

    public function get_list() {
        $this->load->helper('text');
        $post = $this->input->post();

        $q = $this->db->query("SELECT * "
                . " FROM ".$this->tax_table." AS t ");
        
        $results = array();
       
        if ($q->num_rows() > 0) {
    
            foreach ($q->result() as $row) {
                 $actions = anchor(base_url('tax/get_details/' . $row->taxes_id . '/'), '<i class="fa fa-pencil"></i>', 'title="Edit" class="text-light-blue background-edit-modal"')
                        . anchor(base_url('tax/delete/' . $row->taxes_id . '/'), '<i class="fa fa-trash-o"></i>', 'title="Delete" class="lnk-delete"');

                   $results[] = array(
                    $row->taxes_id,
                    $row->title,   
                    $row->value,
                    $actions
                );
            }
        }
        $result = array(
            "iTotalRecords" => $q->num_rows(),
            "iTotalDisplayRecords" => $q->num_rows(),
            "aaData" => $results
        );
        return $result;
    }
    
    public function get_count_tax(){
      return $this->db->select('count(taxes_id) as totalTax')->from($this->tax_table)->get()->result_array();  
    }
     public function get_tax_details($tax_id = null) {
      
         return $this->db->select('taxes_id,title,value')
                ->from($this->tax_table)->where(['taxes_id' => $tax_id])->get();
    }
     public function edit_tax($tax_id, $data) {
        $this->db->set($data);
        $this->db->where(array('taxes_id' => $tax_id));
        $this->db->update($this->tax_table);
        return 1;
    }
}