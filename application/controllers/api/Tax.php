<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Tax extends REST_Controller {

    function __construct() {
        
        // Construct the parent class
        parent::__construct();
        //load the library
        $this->load->model('api/tax_model');
    }

    /* function for payment  */

    public function get_tax_post()
     {
        $tax_result = $this->tax_model->get_taxes();
        if(!empty($tax_result))
        {
            $response = [
                'status' => 1,
                'code' => parent::HTTP_OK,
                'data' => $tax_result
            ];
        }else
        {
            $response = [
                'status' => 0,
                'code' => parent::HTTP_NOT_FOUND,
                'message' => $this->lang->line('tax_information_error')
            ];
        }
        $this->response($response);
     }
}