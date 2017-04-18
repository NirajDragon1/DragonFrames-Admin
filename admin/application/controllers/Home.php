<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

class Home extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->auth->check_login(true);
        $this->load->model('appointmenttype_model');
        $this->load->library('session');
        $this->session->unset_userdata('type');
    }
    
    public function index()
    {
        $data['page_title'] = 'Dashboard - '.SITE_NM;
        $this->load->view('users/users', $data);
    }

    function user_details($user_id = null)
    {   
        $this->load->model('users_model');
        $result = $this->users_model->get_user_details($user_id);
        $result = json_decode(json_encode($result[0]),true);
        $result['enter_datetime'] = date('M d, Y',strtotime($result['enter_datetime']));
        echo json_encode($result);exit;
    }
}?>
