<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class payment extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->auth->check_login(true);

        $this->load->model('payment_model');
        $this->load->library('session');
    }

    public function index() {
        $totalPayment = $this->payment_model->get_count_payment();
        //$totalContact = $this->users_model->get_count_contact();
        $data['page_title'] = 'Transation Listing - ' . SITE_NM;
        $data['payment_count'] = $totalPayment[0]['totalPayment'];
        //$data['contact_count'] = $totalContact[0]['totalContact'];
        $this->load->view('payment/payment', $data);
    }

    public function get_list() {
        $result = $this->payment_model->get_list();

        echo json_encode($result);
        exit;
    }
    
    public function count_payment(){
         $this->load->view('payment/payment', $result);
    }
    public function loadData() {
        $loadType = $_POST['loadType'];
        $loadId = $_POST['loadId'];

        $result = $this->payment_model->getData($loadType, $loadId);
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
}