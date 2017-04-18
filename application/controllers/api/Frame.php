<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Frame extends REST_Controller {

    public $user_id;

    function __construct() {
        // Construct the parent class
        parent::__construct();
        //load the library
        $this->load->library("authex");
        $this->load->model('api/frame_model');
        $this->load->helper('string');
    }

    /* fucntion to get login user details */

    public function frames_details_post() {

        $response = ['status' => 0, 'message' => '', 'code' => ''];
        $frame_data = $this->frame_model->get_frame_details();
        
        if (!empty($frame_data)) {
            $response = [
                'status' => 1,
                'code' => parent::HTTP_OK,
                'message' => $this->lang->line('success_record'),
                'data' => $frame_data
            ];
        } else {
            $response = [
                'status' => 0,
                'code' => parent::HTTP_NOT_FOUND,
                'message' => $this->lang->line('no_record_found')
            ];
        }
        $this->response($response);
    }
}
