<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class User extends REST_Controller {

    public $user_id;

    function __construct() {
        // Construct the parent class
        parent::__construct();
        //load the library
        $this->load->library("authex");
        $this->load->model('api/users_model');
        $this->load->helper('string');
    }

    /* fucntion to get login user details */

    public function login_details_post() {

        $response = ['status' => 0, 'message' => '', 'code' => ''];
        $access_token = $this->input->post('access_token');
        $login_details = $this->users_model->login_detail($access_token);
        if (!empty($login_details)) {
            $response = [
                'status' => 1,
                'code' => parent::HTTP_OK,
                'message' => $this->lang->line('success_record'),
                'data' => $login_details
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

    /* change user password */

    public function change_password_post() {
        $this->load->library('authex');
        $response = ['status' => 0, 'message' => '', 'code' => ''];
        $this->user_id = $this->authex->verify_tokenid();
        $this->form_validation->set_error_delimiters($prefix = '', $suffix = '');
        $this->form_validation->set_rules('password', 'PassWord', 'required|trim|callback__check_old_password');
        $this->form_validation->set_rules('reset_password', 'Reset PassWord', 'required|trim');
        $this->form_validation->set_rules('confirm_password', 'Confirm PassWord', 'required|trim');

        if ($this->form_validation->run() == TRUE) {
            $rest_password = $this->input->post('reset_password');
            $confirm_password = $this->input->post('confirm_password');

            $change_password = $this->users_model->change_password($rest_password, $confirm_password);
        } else {
            $response = [
                'status' => 0,
                'code' => parent::HTTP_BAD_REQUEST,
                'message' => validation_errors()
            ];
        }

        $this->response($response);
    }

    public function _check_old_password($old_pwd) {

        $verified = $this->users_model->check_password($this->user_id, $old_pwd);

        if ($verified) {
            return true;
        } else {
            $this->form_validation->set_message('_check_old_password', 'Old password is incorrect');
            return false;
        }
    }

    /* Edit User Profile */

   public function edit_profile_post() {
        
        $response = ['status' => 0, 'message' => '', 'code' => ''];
        $this->form_validation->set_error_delimiters($prefix = '', $suffix = '');
        $this->form_validation->set_rules('first_name', 'Name', 'required|trim');
       /* $this->form_validation->set_rules('country', 'Country', 'required|trim');
        $this->form_validation->set_rules('city', 'City', 'required|trim');
        $this->form_validation->set_rules('state', 'State', 'required|trim');
        $this->form_validation->set_rules('address', 'Address', 'required|trim');
        $this->form_validation->set_rules('phone', 'Phone', 'required|trim'); */
        $this->form_validation->set_rules('access_token', 'Token', 'required|trim');
        $this->form_validation->set_rules('user_id', 'User ID', 'required|trim');
        
        $user_id = $this->input->post('user_id');

        if ($this->form_validation->run() == TRUE) {
            $access_token = $this->input->post('access_token');
            $user_id = $this->input->post('user_id');
            $valid = check_access_token($user_id, $access_token);

            if ($valid) {
                $filename = '';
                if (!empty($_FILES)) {
                    
                   
                    $ext = pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);
                    
                    //Create the thumbnail
                    $newThumb = $this->CroppedThumbnail($_FILES['userfile']['tmp_name'], DEFAULT_PROFILE_WIDTH, DEFAULT_PROFILE_HEIGHT, $ext);
                
                    // And display the image...
                    $old = umask(0);
                    if (!is_dir(USERPROFILE)) {
                        mkdir(USERPROFILE, 0777);
                    }

                    if (!is_dir(USERPROFILE . $user_id)) {
                        mkdir(USERPROFILE . $user_id, 0777);
                    }

                    $filename = 'profile_' . substr(time(), -4) . '.' . $ext;
                    $upload_path = USERPROFILE . $user_id . '/' . $filename;

                    header('Content-type: image/jpeg');
                    @imagejpeg($newThumb, $upload_path, 100);

                    $existing_profile = $this->users_model->profile_image($access_token, $user_id);
                    if (isset($existing_profile['profile_image']) && !empty($existing_profile['profile_image'])) {
                        @unlink(USERPROFILE . $user_id . '/' . $existing_profile['profile_image']);
                    }
                    umask($old);

                }

                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'country' => $this->input->post('country'),
                    'city' => $this->input->post('city'),
                    'state' => $this->input->post('state'),
                    'address' => $this->input->post('address'),
                    'zipcode' => $this->input->post('zipcode'),
                    'phone' => $this->input->post('phone'),
                    'access_token' => $this->input->post('access_token'),
                    'user_id' => $this->input->post('user_id'),
                    'profile_image' => $filename
                );
                
                $update = $this->users_model->update($data);
                if ($update) {
                    $response = [

                        'status' => 1,
                        'code' => parent::HTTP_OK,
                        'message' => $this->lang->line('user_profile_updated'),
                        'data' => array('access_token' => $access_token, 'user_id' => $user_id)
                    ];
                } else {
                    $response = [
                        'status' => 0,
                        'code' => parent::HTTP_BAD_REQUEST,
                        'message' => $this->lang->line('fail_to_save_user_info')
                    ];
                }
            } else {
                $response = [
                    'status' => 0,
                    'code' => parent::HTTP_BAD_REQUEST,
                    'message' => $this->lang->line('invalid_access_token')
                ];
            }
        } else {
            $response = [
                'status' => 0,
                'code' => parent::HTTP_BAD_REQUEST,
                'message' => validation_errors()
            ];
        }

        $this->response($response);
    } 
    
   /* public function _edit_profile_post() {
         
        $response = ['status' => 0, 'message' => '', 'code' => ''];
        $this->form_validation->set_error_delimiters($prefix = '', $suffix = '');
        $this->form_validation->set_rules('first_name', 'Name', 'required|trim');
        $this->form_validation->set_rules('last_name', 'Name', 'required|trim');
        $this->form_validation->set_rules('country', 'Country', 'required|trim');
        $this->form_validation->set_rules('city', 'City', 'required|trim');
        $this->form_validation->set_rules('state', 'State', 'required|trim');
        $this->form_validation->set_rules('address', 'Address', 'required|trim');
        $this->form_validation->set_rules('phone', 'Phone', 'required|trim');
        $this->form_validation->set_rules('access_token', 'Token', 'required|trim');
        $this->form_validation->set_rules('user_id', 'User ID', 'required|trim');
        
        
        $user_id = $this->input->post('user_id');

        if ($this->form_validation->run() == TRUE) {
            $access_token = $this->input->post('access_token');
            $user_id = $this->input->post('user_id');
            $valid = check_access_token($user_id, $access_token);
            
            if ($valid) {
                $filename = '';
                $filename = 'profile_' . substr(time(), -4);
                    
                    //Create the thumbnail
                    $newThumb = $this->CroppedThumbnail($img_file, DEFAULT_PROFILE_WIDTH, DEFAULT_PROFILE_HEIGHT, $ext);

                    // And display the image...
                    $old = umask(0);
                    if (!is_dir(USERPROFILE)) {
                        mkdir(USERPROFILE, 0777);
                    }

                    if (!is_dir(USERPROFILE . $user_id)) {
                        mkdir(USERPROFILE . $user_id, 0777);
                    }

                    $upload_path = USERPROFILE . $user_id . '/' . $img_file;

                    header('Content-type: image/jpeg');
                    @imagejpeg($newThumb, $upload_path, 100);

                    $existing_profile = $this->users_model->profile_image($access_token, $user_id);
                    if (isset($existing_profile['profile_image']) && !empty($existing_profile['profile_image'])) {
                        @unlink(USERPROFILE . $user_id . '/' . $existing_profile['profile_image']);
                    }
                    umask($old);

                $data = array(
                    'userfile' => $this->input->post('userfile'),
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'country' => $this->input->post('country'),
                    'city' => $this->input->post('city'),
                    'state' => $this->input->post('state'),
                    'address' => $this->input->post('address'),
                    'phone' => $this->input->post('phone'),
                    'access_token' => $this->input->post('access_token'),
                    'user_id' => $this->input->post('user_id'),
                    'profile_image' => $img_file
                );
              
                $update = $this->users_model->update($data);
                if ($update) {
                    $response = [

                        'status' => 1,
                        'code' => parent::HTTP_OK,
                        'message' => $this->lang->line('user_profile_updated'),
                        'data' => array('access_token' => $access_token, 'user_id' => $user_id)
                    ];
                } else {
                    $response = [
                        'status' => 0,
                        'code' => parent::HTTP_BAD_REQUEST,
                        'message' => $this->lang->line('fail_to_save_user_info')
                    ];
                }
            } else {
                $response = [
                    'status' => 0,
                    'code' => parent::HTTP_BAD_REQUEST,
                    'message' => $this->lang->line('invalid_access_token')
                ];
            }
        } else {
            $response = [
                'status' => 0,
                'code' => parent::HTTP_BAD_REQUEST,
                'message' => validation_errors()
            ];
        }

        $this->response($response);
    } */

    public function CroppedThumbnail($imgSrc, $thumbnail_width, $thumbnail_height, $ext) {
        
        //$imgSrc is a FILE - Returns an image resource.
        //getting the image dimensions  
        list($width_orig, $height_orig) = getimagesize($imgSrc);
        if (strtolower($ext) == 'jpg' || strtolower($ext) == 'jpg') {
            $myImage = imagecreatefromjpeg($imgSrc);
        } else if (strtolower($ext) == 'png') {
            $myImage = imagecreatefrompng($imgSrc);
        }
        
        $ratio_orig = $width_orig / $height_orig;
        
        if ($thumbnail_width / $thumbnail_height > $ratio_orig) {
            $new_height = $thumbnail_width / $ratio_orig;
            $new_width = $thumbnail_width;
        } else {
            $new_width = $thumbnail_height * $ratio_orig;
            $new_height = $thumbnail_height;
        }

        $x_mid = $new_width / 2;  //horizontal middle
        $y_mid = $new_height / 2; //vertical middle

        $process = imagecreatetruecolor(round($new_width), round($new_height));

        imagecopyresampled($process, $myImage, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);
        $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
        imagecopyresampled($thumb, $process, 0, 0, ($x_mid - ($thumbnail_width / 2)), ($y_mid - ($thumbnail_height / 2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);

        imagedestroy($process);
        imagedestroy($myImage);
        return $thumb;
    }
}
