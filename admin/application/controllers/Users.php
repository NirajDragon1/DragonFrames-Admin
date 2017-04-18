<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->auth->check_login(true);

        $this->load->model('users_model');
        $this->load->library('session');
    }

    public function index() {
        $this->session->unset_userdata('type');
        $totalUser = $this->users_model->get_count_user();
        //$totalContact = $this->users_model->get_count_contact();
        $data['page_title'] = 'User Listing - ' . SITE_NM;
        $data['user_count'] = $totalUser[0]['totalUser'];
        //$data['contact_count'] = $totalContact[0]['totalContact'];
        $this->load->view('users/users', $data);
    }

    public function get_list() {
        $result = $this->users_model->get_list();

        echo json_encode($result);
        exit;
    }
    
    public function count_user(){
        
         $this->load->view('users/users', $result);
    }
    public function loadData() {
        $loadType = $_POST['loadType'];
        $loadId = $_POST['loadId'];

        $result = $this->users_model->getData($loadType, $loadId);
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

//    public function add_users() {
//        $post = $this->input->post();
//        $return = array('success' => false, 'msg' => 'Please fill the form properly');
//        if (!empty($post)) {
//            $this->form_validation->set_error_delimiters($prefix = '', $suffix = '.');
//            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|xss_clean');
//            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|xss_clean');
//            $this->form_validation->set_rules('emailaddress', 'Email', 'trim|required|valid_email');
//            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[20]');
//            $this->form_validation->set_rules('ConfirmPassword', 'Confirm Password', 'required|trim|matches[password]');
//            $this->form_validation->set_rules('phone', 'Mobile', 'trim|required|numeric|max_length[15]');
//            // $this->form_validation->set_rules('user_type_id', 'User Type Id', 'trim|required|xss_clean');
//            $this->form_validation->set_rules('is_verified', 'Is Verified', 'trim|required|xss_clean');
//            $this->form_validation->set_rules('isactive', 'Is Active', 'trim|required|xss_clean');
//
//            if ($this->form_validation->run()) {
//
//                $data = array(
//                    'first_name' => $this->input->post('first_name'),
//                    'last_name' => $this->input->post('last_name'),
//                    'emailaddress' => $this->input->post('emailaddress'),
//                    'password' => md5($this->input->post('password')),
//                    'phone' => $this->input->post('phone'),
//                    'address' => $this->input->post('address'),
//                    'zipcode' => $this->input->post('zipcode'),
//                    'country' => $this->input->post('country'),
//                    'state' => $this->input->post('state'),
//                    'city' => $this->input->post('city'),
//                    'user_type_id' => $this->input->post('user_type_id'),
//                    'isactive' => $this->input->post('isactive'),
//                    'isdelete' => $this->input->post('isdelete'),
//                    'added_date' => date('Y-m-d H:i:s'),
//                );
//                $result = $this->users_model->add($data);
//
//                $filename = '';
//                if (!empty($_FILES)) {
//
//                    $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
//
//                    //Create the thumbnail
//                    $newThumb = $this->CroppedThumbnail($_FILES['profile_image']['tmp_name'], DEFAULT_PROFILE_WIDTH, DEFAULT_PROFILE_HEIGHT, $ext);
//                    // And display the image...
//                    $old = umask(0);
//
//                    if (!is_dir(USERPROFILE)) {
//                        mkdir(USERPROFILE, 0777);
//                    }
//                    if (!is_dir(USERPROFILE . $result)) {
//                        mkdir(USERPROFILE . $result, 0777);
//                    }
//                    
//                    if (!is_dir(USERPROFILE . $result.'/original')) {
//                        mkdir(USERPROFILE . $result.'/original', 0777);
//                    }
//                    
//                    $filename = 'profile_' . substr(time(), -4) . '.' . $ext;
//                    $upload_path = USERPROFILE . $result . '/' . $filename;
//
//                    $originalimage_upload_path = USERPROFILE . $result .'/original/'.$filename;
//                    
//                    header('Content-type: image/jpeg');
//                    @imagejpeg($newThumb, $upload_path, 100);
//                    @move_uploaded_file($_FILES['profile_image']['tmp_name'], $originalimage_upload_path);
//                    
//                    umask($old);
//
//                    if (file_exists($upload_path)) {
//                        $new_data = array(
//                            'user_id' => $result,
//                            'profile_image' => $filename,
//                        );
//                        $result = $this->users_model->update_profile_image($new_data);
//                    }
//                }
//                if ($result > 0)
//                    $return = array('success' => true, 'msg' => 'User has been added successfully!');
//                else
//                    $return = array('success' => false, 'msg' => 'Unable to add User. Please try again later');
//            } else {
//                $error = validation_errors();
//                $error = explode('.', $error);
//                $return = array('success' => false, 'msg' => $error[0]);
//            }
//        }
//        echo json_encode($return);
//        die;
//    }

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

    function delete($user_id = null) {
        $status = delete($this->users_model->user_table, array('isdelete' => DELETED_YES), array('user_id' => $user_id));
        header('Content-type: application/json');
        echo json_encode(array("success" => $status));
    }

    public function update_status($id = null) {
        $st = $this->input->post('status');
        change_status($this->users_model->user_table, array('isactive' => $st), array('user_id' => $id));
        echo 1;
    }

    public function get_details($id = null) {
        $res = $this->users_model->get_user_details($id);
        
        echo json_encode($res->row());
        die;
    }

    /*public function edit($user_id = null) {

        $post = $this->input->post();
        $this->data = $post;

        if (!empty($post)) {
            $this->form_validation->set_error_delimiters($prefix = '', $suffix = '.');
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('user_type_id', 'User Type Id', 'trim|required|xss_clean');
            $this->form_validation->set_rules('phone', 'Mobile', 'trim|required|numeric|max_length[15]');
            $this->form_validation->set_rules('is_verified', 'Is Verified', 'trim|required|xss_clean');
            $this->form_validation->set_rules('isactive', 'Is Active', 'trim|required|xss_clean');
            if ($this->form_validation->run()) {

                $existsImage = $this->users_model->exist_image($user_id);

                if (!empty($_FILES['profile_image']['name'])) {
                        
                        if(isset($existsImage[0]['profile_image']) && !empty($existsImage[0]['profile_image']))
                        {
                            $unlinkImage =  USERPROFILE . $user_id.'/' . $existsImage[0]['profile_image'];
                            $unlinkOriginalImage = USERPROFILE . $user_id.'/original/' . $existsImage[0]['profile_image'];
                            
                            if ($existsImage[0]['profile_image'] != '' && file_exists($unlinkImage)) {
                                unlink($unlinkImage);
                            }
                            if ($existsImage[0]['profile_image'] != '' && file_exists($unlinkOriginalImage)) {
                                unlink($unlinkOriginalImage);
                            }  
                        }
                        
                         $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);

                    //Create the thumbnail
                    $newThumb = $this->CroppedThumbnail($_FILES['profile_image']['tmp_name'], DEFAULT_PROFILE_WIDTH, DEFAULT_PROFILE_HEIGHT, $ext);
                    // And display the image...
                    $old = umask(0);

                    if (!is_dir(USERPROFILE)) {
                        mkdir(USERPROFILE, 0777);
                    }
                    if (!is_dir(USERPROFILE . $user_id)) {
                        mkdir(USERPROFILE . $user_id, 0777);
                    }
                    
                    if (!is_dir(USERPROFILE . $user_id.'/original')) {
                        mkdir(USERPROFILE . $user_id.'/original', 0777);
                    }
                    
                    $filename = 'profile_' . substr(time(), -4) . '.' . $ext;
                    $upload_path = USERPROFILE . $user_id . '/' . $filename;

                    $originalimage_upload_path = USERPROFILE . $user_id .'/original/'.$filename;
                    
                    header('Content-type: image/jpeg');
                    @imagejpeg($newThumb, $upload_path, 100);
                    @move_uploaded_file($_FILES['profile_image']['tmp_name'], $originalimage_upload_path);
                    
                    umask($old);

                    if (file_exists($upload_path)) {
                        $new_data = array(
                            'user_id' => $user_id,
                            'profile_image' => $filename,
                        );
                        $user_id = $this->users_model->update_profile_image($new_data);
                    }
                    
                    } 
                

                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'emailaddress' => $this->input->post('emailaddress'),
                    'phone' => $this->input->post('phone'),
                    'address' => $this->input->post('address'),
                    'zipcode' => $this->input->post('zipcode'),
                   // 'vProfile' => isset($img['img_name']) ? $img['img_name'] : $existsImage[0]['vProfile'],
                   // 'vThumbProfile' => isset($img['thumb_name']) ? $img['thumb_name'] : $existsImage[0]['vThumbProfile'],
                    'country' => $this->input->post('country'),
                    'state' => $this->input->post('state'),
                    'city' => $this->input->post('city'),
                    'is_verified' => $this->input->post('is_verified'),
                    'user_type_id' => $this->input->post('user_type_id'),
                    'isactive' => $this->input->post('isactive'),
                    'modified_date' => date("Y-m-d H:i:s"),
                );

                $result = $this->users_model->edit_user($user_id, $data);
                if ($result > 0)
                    $return = array('success' => true, 'msg' => 'User has been saved successfully!');
                else
                    $return = array('success' => false, 'msg' => 'Unable to save User. Please try again later');
            } else {
                $error = validation_errors();
                $error = explode('.', $error);
                $return = array('success' => false, 'msg' => $error[0]);
            }
        } else {
            $error = validation_errors();
            $error = explode('.', $error);
            $return = array('success' => false, 'msg' => $error[0]);
        }
        echo json_encode($return);
        die;
    }

    public function _password_check($conpassword, $password) {
        if (!empty($conpassword) || !empty($password)) {
            if ($conpassword != $password) {
                return false;
            }
        }
        return true;
    }*/

    //Create Thumbnail function
    function _createThumbnail($filename) {

        $config['image_library'] = "gd2";

        $config['source_image'] = "../assets/images/" . $filename;

        $config['create_thumb'] = TRUE;

        $config['maintain_ratio'] = TRUE;

        $config['width'] = "150";

        $config['height'] = "150";

        $this->load->library('image_lib', $config);

        if (!$this->image_lib->resize()) {
            echo $this->image_lib->display_errors();
        }
    }

    public function my_account() {
        $data['page_title'] = 'My Account - ' . SITE_NM;
        $data['my_account'] = $this->users_model->get_user_details($this->session->userdata('user_id'))->row();
        $this->load->view('users/my_account', $data);
    }

    /*public function edit_admin($user_id = null) {

        $post = $this->input->post();
        $this->data = $post;

        if (!empty($post)) {
            $this->form_validation->set_error_delimiters($prefix = '', $suffix = '.');
            $this->form_validation->set_rules('vFirstName', 'First Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('vLastName', 'Last Name', 'trim|required|xss_clean');

            if ($this->input->post('vEmail') != $this->input->post('vOldEmailId')) {
                $isEmail_unique = '|is_unique[tbl_users.vEmail]';
            } else {
                $isEmail_unique = '';
            }

            $this->form_validation->set_rules('vEmail', 'Email', 'trim|required|valid_email' . $isEmail_unique);

            if ($this->input->post('vPassword') != "") {
                $this->form_validation->set_rules('vPassword', 'Password', 'trim|required|min_length[6]|max_length[20]');
                $this->form_validation->set_rules('vConfirmPassword', 'Confirm Password', 'required|trim|matches[vPassword]');
            }

            if ($this->form_validation->run()) {
                $data = array(
                    'vFirstName' => $this->input->post('vFirstName'),
                    'vLastName' => $this->input->post('vLastName'),
                    'vEmail' => $this->input->post('vEmail'),
                    'vPassword' => hash('sha256', ($this->input->post('vPassword'))),
                    'dModifiedDate' => date("Y-m-d H:i:s")
                );

                $result = $this->users_model->edit_user($user_id, $data);
                if ($result > 0)
                    $return = array('success' => true, 'msg' => 'User has been updated successfully!');
                else
                    $return = array('success' => false, 'msg' => 'Unable to update User. Please try again later');
            } else {
                $error = validation_errors();
                $error = explode('.', $error);
                $return = array('success' => false, 'msg' => $error[0]);
            }
        } else {
            $return = array('success' => false, 'msg' => 'Unable to update User. Please try again later');
        }
        echo json_encode($return);
        die;
    }*/

    public function checkEmailExistence() {
        $vEmail = $this->input->post('vEmail');
        $user_info = $this->users_model->checkEmailExistence($vEmail);

        if ($user_info != NULL) {
            if ($user_info[0]['iIsDeleted'] == '1') {
                echo 'true';
            } else {
                echo 'false';
            }
        } else {
            echo 'true';
        }
    }

}
