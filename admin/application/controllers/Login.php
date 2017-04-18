<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {

        if ($this->auth->check_login()) {
            redirect(base_url() . 'users');
        }
        $post = $this->input->post();
        $data['page_title'] = 'Sign in - ' . SITE_NM;
        if (!empty($post)) {
            $this->form_validation->set_rules('email', 'Username', 'trim|required|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            if ($this->form_validation->run()) {
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                $ret = $this->auth->login($email, $password);
                //echo $this->db->last_query();exit;
                set_flash_msg($ret['flsh_msg'], $ret['flsh_msg_type']);
            }
        }

        $this->load->view('login', $data);
    }

    public function logout() {
        $this->auth->logout();
    }

//    public function forgot_passowrd() { 
//        $response = ['status' => 0, 'message' => '', 'code' => ''];
////            if($this->auth->check_login()){
////                redirect(base_url().'users');
////            }
//        $oldpassword = $this->input->post('oldpassword');
//          
//        $user_id = $_SESSION['user_id'];
//        //$res = $this->auth->check_email();
//        $res = $this->auth->check_email();
//
//        $check_old_password = $this->auth->check_old_password($user_id,$oldpassword);
//        
//        if ($check_old_password) {
//
//            $response = ['status' => 0, 'message' => '', 'code' => ''];
//           $newpassword = $this->input->post('newpassword');
//           $confirmpassword = $this->input->post('confirmpassword');
//           
//            $change_password = $this->auth->change_password($newpassword, $confirmpassword,$user_id);
//            echo $change_password;exit;
//            if ($change_password) {
//                
//                 $response = 1;
//                
//            } else {
//                $response = 0;
//            }
//        } else {
//            $response = 0;
//              
//        }
//        return $response;
//    }
    
}
