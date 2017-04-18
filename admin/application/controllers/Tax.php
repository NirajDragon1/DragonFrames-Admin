<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tax extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->auth->check_login(true);

        $this->load->model('tax_model');
        $this->load->library('session');
    }

    public function index() {
        //$this->session->unset_userdata('type');
        $totalTax = $this->tax_model->get_count_tax();
        $data['page_title'] = 'Tax Listing - ' . SITE_NM;
        $data['tax_count'] = $totalTax[0]['totalTax'];
        $this->load->view('tax/tax', $data);
    }

    public function get_list() {
        $result = $this->tax_model->get_list();
        echo json_encode($result);
        exit;
    }
    
    public function count_tax(){
         $this->load->view('tax/tax', $result);
    }
     public function get_details($id = null) {
        $res = $this->tax_model->get_tax_details($id);
        echo json_encode($res->row());
        die;
    }
    public function loadData() {
        $loadType = $_POST['loadType'];
        $loadId = $_POST['loadId'];

        $result = $this->tax_model->getData($loadType, $loadId);
        $count = $result->num_rows();
        $result_arr = $result->result();

        $HTML = "";

        if ($count > 0) {
            foreach ($result_arr as $list) {
                $HTML.="<option value='" . $list->iId . "'>" . $list->vName . "</option>";
            }
        }
        echo $HTML;
    }
    function delete($tax_id = null) {
        $status = delete($this->tax_model->tax_table,null, array('taxes_id' => $tax_id));
        header('Content-type: application/json');
        echo json_encode(array("success" => $status));
    }
    public function edit($tax_id = null) {
        $post = $this->input->post();
        $this->data = $post;
        if (!empty($post)) {
            $this->form_validation->set_error_delimiters($prefix = '', $suffix = '.');
            $data = array(
                'title' => $this->input->post('vTitle'),
                'value' => $this->input->post('vValue'),
            );
         
            $result = $this->tax_model->edit_tax($tax_id, $data);
            if ($result > 0)
                $return = array('success' => true, 'msg' => 'Tax has been saved successfully!');
            else
                $return = array('success' => false, 'msg' => 'Unable to save Tax. Please try again later');
        } else {
            $error = validation_errors();
            $error = explode('.', $error);
            $return = array('success' => false, 'msg' => $error[0]);
        }
        
        echo json_encode($return);
        die;
    }
}