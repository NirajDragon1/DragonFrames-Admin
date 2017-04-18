<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Registration extends REST_Controller {

    function __construct() {
        // Construct the parent class
        parent::__construct();
        //load the library
        $this->load->library("authex");
        $this->load->model('api/registration_model');
        $this->load->helper('string');
    }

    /* function for registration  */

    public function signup_post($data = array()) {
        $response = ['status' => 0, 'message' => '', 'code' => ''];
      
        $rand = $this->random();
        $verificationCode = base64_encode($rand);
        $this->form_validation->set_error_delimiters($prefix = '', $suffix = '');
        $this->form_validation->set_rules('email_address', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]|max_length[20]');
        $this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
        
        $post = $this->input->post;

        if (empty($post)) {
            if ($this->form_validation->run() == FALSE) {
                $response = [
                    'code'    => parent::HTTP_BAD_REQUEST,
                    'message' => validation_errors()
                ];
            } else {
                $data = array(
                    'emailaddress'      => $this->input->post('email_address'),
                    'password'          => $this->input->post('password'),
                    'first_name'        => $this->input->post('first_name'),
                    'last_name'         => $this->input->post('last_name'),
                    'verification_key'  => $verificationCode,
                );

                $checkemailaddress = check_delete_email($data['emailaddress']);
                if (sizeof($checkemailaddress) > 0) {
                    $this->response([
                        'status'        => 1,
                        'message'       => $this->lang->line('Email_already_register'),
                        'code'          => VALIDATION_ERR,
                    ]);
                } else {
                    $to = $this->input->post('email_address');
                    $name = $this->input->post('first_name');
                    
                    $registration                       = array();
                    $registration['to']                 = $to;
                    $registration['subject']            = 'Registration verification';
                    $registration['user']               = $name;
                    $registration['verificationCode']   = $verificationCode;
                    $registration['flag']               = 'registration';
                   
                    $user_id   = $this->registration_model->signup($data);
                   
                    if ($user_id) {
                        send_mail($registration);
                        $response = [
                            'status' => 1,
                            'code' => parent::HTTP_OK,
                            'message' => $this->lang->line('success_regisration'),
                            'data' => $user_id
                        ];
                    } else {
                        $response = [
                            'status' => 0,
                            'code' => parent::HTTP_BAD_REQUEST,
                            'message' => $this->lang->line('fail_regisration')
                        ];
                    }
                }
            }
        }
        $this->response($response);
    }

    /* function to generate random key for varifiation */

    public function random() {
        $seed = str_split('abcdefghijklmnopqrstuvwxyz' . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . '0123456789!@#$%^&*');
        shuffle($seed);
        $rand = '';
        foreach (array_rand($seed, 16) as $k)
        $rand .= $seed[$k];
        return $rand;
    }

    /* function to verfiy user registration */

    public function verify_get() {
        $response = ['status' => 0, 'message' => '', 'code' => ''];
        $token        = $this->input->get('token');
        if(preg_match("/(android|webos|avantgo|iphone|ipad|ipod|blackberry|iemobile|bolt|bo‌ost|cricket|docomo|fone|hiptop|mini|opera mini|kitkat|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]))

            {
                
                header('Location:DragonFrame://'); die();
            }
            
        $noOfRecords  = $this->registration_model->verifyEmailAddress($token);
        if ($noOfRecords > 0) {
            
            
           // header('Location:'. APP_URL.'api/registration/appredirect');
           // exit;
            $this->load->view('redirect');
            
        } else {
            $response = [
                /*'status'  => 0,
                'code'    => parent::HTTP_BAD_REQUEST,*/
                'message' => $this->lang->line('registration_unable_verified')
            ];
            $this->response($response);
        }
        
    }
    
    public function appredirect_get()
	{
		$this->load->view('redirect');
	}

    /* function to list user type */

    public function list_user_type_post() {
        $response = ['status' => 0, 'message' => '', 'code' => ''];
        $user_type_details = $this->registration_model->get_user_type();
        if (!empty($user_type_details)) {
            $temp_data = array();
            foreach ($user_type_details as $user_type) {
                $rdata['user_type_id'] = $user_type['usertype_id'];
                $rdata['user_type_name'] = $user_type['usertypename'];
                $temp_data[] = $rdata;
            }
            $response = [
                'status' => 1,
                'message' => $this->lang->line('success_record'),
                'code' => parent::HTTP_OK,
                'data' => $temp_data,
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

    public function password_check($str) {
        if (preg_match('#[0-9]#', $str) && preg_match('#[a-zA-Z]#', $str)) {
            return TRUE;
        }
        return FALSE;
    }

    /* function for user login */

    public function login_post() {
        $response = ['status' => 0, 'message' => '', 'code' => ''];
        $this->form_validation->set_error_delimiters($prefix = '', $suffix = '');
        $this->form_validation->set_rules('email_address', 'Email', 'required|valid_email', array('required' => $this->lang->line('empty_email'), 'valid_email' => $this->lang->line('invalid_email')));
        $this->form_validation->set_rules('password', 'Password', 'required', array('required' => $this->lang->line('empty_password')));
        if ($this->form_validation->run() == TRUE) {
            $email = $this->input->post('email_address');
            $this->load->library('encrypt');
            $password = hash('sha256', $this->input->post('password', TRUE));
            $check_login = $this->authex->login($email, $password);
            if ($check_login['status'] == 1) {
                $response = [ 
                    'status' => 1,
                    'code' => parent::HTTP_OK,
                    'message' => $this->lang->line('login_success'),
                    'data' => $check_login
                ];
            } else {
                $errorMsg = [
                    'Invalid login credentials',
                    '-1' => 'Email address has not been verified, please check email for verification notice',
                    '-2' => 'Account is deactived. Please contact with admin',
                    '-3' => 'You are not allow to login with admin credentials',
                ];
                $response['message'] = $errorMsg[$check_login['status']];
                $response['status'] = $check_login['status'];
            }
           /* if ($check_login['status'] == 1) {
                $insert_user_id = $this->registration_model->update_user_id($check_login['user_id'], $flag);
            }*/
        } else {
            $response = [
                'code' => parent::HTTP_BAD_REQUEST,
                'message' => validation_errors()
            ];
        }
        $this->response($response);
    }

    /* function for verify email address while registration */

    public function verify_email_post() {
        $response = ['status' => 0, 'message' => '', 'code' => ''];
        $this->form_validation->set_error_delimiters($prefix = '', $suffix = '');
        $this->form_validation->set_rules('email_address', 'Email', 'required|valid_email', array('required' => $this->lang->line('empty_email'), 'valid_email' => $this->lang->line('invalid_email')));

        $rand = $this->random();
        $verificationCode = base64_encode($rand);

        if ($this->form_validation->run() == TRUE) {
            $email          = $this->input->post('email_address');
            $user_details   = $this->registration_model->verify_email($email);
            $user           = $user_details->first_name;
            $registration   = array();
            $registration['to']                 = $email;
            $registration['subject']            = 'Registration verification';
            $registration['user']               = $user;
            $registration['verificationCode']   = $verificationCode;
            $registration['flag']               = 'registration';

            $update_verificationcode = $this->registration_model->update_code($email, $verificationCode);
            if (send_mail($registration)) {
                $response = [
                    'status' => 1,
                    'code' => parent::HTTP_OK,
                    'message' => $this->lang->line('email_send_successfully'),
                ];
            } else {
                $response = [
                    'status'  => 0,
                    'code'    => parent::HTTP_BAD_REQUEST,
                    'message' => $this->lang->line('email_send_fail'),
                ];
            }
        } else {
            $response = [
                'code'    => parent::HTTP_BAD_REQUEST,
                'message' => validation_errors()
            ];
        }
        $this->response($response);
    }

    /* function for forgotpassword  */

    public function forgotpassword_post() {
        $response = ['status' => 0, 'message' => '', 'code' => ''];
        $this->form_validation->set_error_delimiters($prefix = '', $suffix = '');
        $this->form_validation->set_rules('email_address', 'Email', 'required|valid_email', array('required' => $this->lang->line('empty_email'), 'valid_email' => $this->lang->line('invalid_email')));
        if ($this->form_validation->run() == TRUE) {
            
            $email_address = $this->input->post('email_address');
            $check_email = $this->registration_model->verify_email($email_address);
            if (empty($check_email)) {
                $response = [
                    'code'    => parent::HTTP_BAD_REQUEST,
                    'message' => $this->lang->line('email_not_exists'),
                ];
            } else if ($check_email !== false && (isset($check_email->emailaddress))) {
                $email              = $check_email->emailaddress;
                $username           = $check_email->first_name ;
                $password           = $check_email->password ;

                $password_varify                      = array();
                $password_varify['to']                = $email;
                $password_varify['user']              = $username;
                $password_varify['subject']           = 'Forgot Password';
                $password_varify['flag']              = 'forgotpassword';
                $password_varify['password']          = $password;
                send_mail($password_varify);
                $response = [
                    'status'  => 1,
                    'code'    => parent::HTTP_OK,
                    'message' => $this->lang->line('reset_password')
                ];
            } else {
                $response = [
                    'status'  => 0,
                    'code'    => parent::HTTP_BAD_REQUEST,
                    'message' => $this->lang->line('provide_valid_email'),
                ];
            }
        } else {
            $response = [
                'code'    => parent::HTTP_BAD_REQUEST,
                'message' => validation_errors()
            ];
        }
        $this->response($response);
    }

    /* function for restet password */

    public function reset_password_post() {
        
        $response       = ['status' => 0, 'message' => '', 'code' => ''];
        $this->form_validation->set_error_delimiters($prefix = '', $suffix = '');
        $this->form_validation->set_rules('access_token', 'Access Token', 'trim|required');
        $this->form_validation->set_rules('new_password', 'Password', 'trim|required');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required');

        if( $this->form_validation->run() == TRUE )
        { 
            $access_token   = $this->input->post('access_token');
            $new_password   = $this->input->post('new_password');
            $cf_password    = $this->input->post('confirm_password');

            if ($cf_password != $new_password) {
              $response = [
                  'code' => parent::HTTP_BAD_REQUEST,
                  'message' => $this->lang->line('pwd_miss_match')
              ];
              $this->response($response);
            }

            $user_info = $this->verify_reset_pwd_token_post(false);
            $password = hash('sha256', $new_password);
            $this->db->set('is_verified', 'yes')
                    ->set('pwd_reset_req_date', NULL)
                    ->set('pwd_reset_token', time())
                    ->set('password', $password)
                    ->where(['user_id' => $user_info->user_id])
                    ->update('users');

            $cnt = $this->db->affected_rows();
            $response = [
                'status' => 1,
                'code' => parent::HTTP_OK,
                'message' => $this->lang->line('pwd_reset_success')
            ];

        }else {
            $response = [
                'code'    => parent::HTTP_BAD_REQUEST,
                'message' => validation_errors()
            ];
        }

        
        $this->response($response);
    }

    

    /* function to verify restet password to check valid access token and return true or false */

    public function verify_reset_pwd_token_post($api = true) {
        //token
         $response = ['status' => 0, 'message' => '', 'code' => ''];
        $access_token = $this->input->post('access_token');
        
        if (!empty($access_token)) {
            $response = $this->db->select('*')->from('users')->where(['token_id' => $access_token])->get()->row();
            
            if (!empty($response)) {
                return $response;
            }
        }
        $response = [
            'message' => $this->lang->line('inv_req'),
            'code' => parent::HTTP_OK,
        ];
        $this->response($response); // code is expired
    }


    /* function for user logout from logged in profile */

    public function logout_post() {
        $response = ['status' => 0, 'message' => '', 'code' => ''];
        $this->form_validation->set_rules('access_token', 'access Token', 'trim|required');
        if ($this->form_validation->run() == TRUE) {
            $access_token = $this->input->post('access_token');
            $logout = $this->authex->logout($access_token);
            $response = [
                'status' => 1,
                'code' => parent::HTTP_OK,
                'message' => $this->lang->line('logout_success')
            ];
        } else {
            $response = [
                'status' => 0,
                'code' => parent::HTTP_BAD_REQUEST,
                'message' => $this->lang->line('invalid_request')
            ];
        }
        $this->response($response);
    }

    /* function for get current data and time */

    public function current_date_time_post() {

        $response = ['status' => 0, 'message' => '', 'code' => ''];
        $date_time = $this->registration_model->get_date_time();

        if (!empty($date_time)) {
            $response = [
                'status' => 1,
                'code' => parent::HTTP_OK,
                'message' => $this->lang->line('date_time_found'),
                'data' => $date_time,
            ];
        } else {
            $response = [
                'status' => 0,
                'code' => parent::HTTP_NOT_FOUND,
                'message' => $this->lang->line('not_found_date_time')
            ];
        }
        $this->response($response);
    }


    /* Social media login */

    public function social_media_login_post()
    {
        $this->form_validation->set_error_delimiters($prefix = '', $suffix = '');
        $this->form_validation->set_rules('email_address', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('social_id', 'Social ID', 'required|trim');
        //$this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('social_type', 'Social Type', 'required|trim');
       
        if ($this->form_validation->run() == TRUE) 
        {
            $post = $this->input->post();
            $login_result = $this->registration_model->social_login($post);
            if(!empty($login_result))
            {
                $response = [
                    'status' => 1,
                    'code' => parent::HTTP_OK,
                    'data' => $login_result
                ];
            }else
            {
                $response = [
                    'status' => 0,
                    'code' => parent::HTTP_NOT_FOUND,
                    'message' => $this->lang->line('add_information_error')
                ];
            }
        } else 
        {
            $response = [
                'status' => 0,
                'code' => parent::HTTP_BAD_REQUEST,
                'message' => validation_errors()
            ];
        }
        $this->response($response);
    }
     
}