<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Frames extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->auth->check_login(true);

        $this->load->model('frames_model');
        $this->load->library(array('session', 'image_lib'));
    }

    public function index() {
        $this->session->unset_userdata('type');
        $data['page_title'] = 'Frames Listing - ' . SITE_NM;

        //$data['language_list'] = get_language();

        $this->load->view('frames/frames', $data);
    }

    public function get_list() {
        $result = $this->frames_model->get_list();

        echo json_encode($result);
        exit;
    }

    public function add_frames() {
        $post = $this->input->post();
        
        $return = array('success' => false, 'msg' => 'Please fill the form properly');
        if (!empty($post)) {
            $this->form_validation->set_error_delimiters($prefix = '', $suffix = '.');
            $this->form_validation->set_rules('vTitle', 'Title', 'trim|required|xss_clean');

            if (empty($_FILES['vFrameImage']['name'])) {
                $this->form_validation->set_rules('vFrameImage', 'Frame Image', 'required');
            }

            if ($this->form_validation->run()) {

                $config['upload_path'] = FRAMES_UPLOAD_PATH;
                $config['allowed_types'] = "jpg|jpeg|png";
                $config['max_size'] = "5000000";
                $config['max_width'] = "3000";
                $config['max_height'] = "3000";
                $config['userfile'] = "vFrameImage";

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload($config['userfile'])) {
                    $return = array('success' => false, 'msg' => $this->upload->display_errors());
                } else {

                    $finfo = $this->upload->data();

                    $pathMain = FRAMES_PATH;

                    //vBackgroundThumbImage
                    $this->_createThumbnail($finfo['file_name'], "150", "150");

                    $img['img_name'] = $finfo['raw_name'] . $finfo['file_ext'];
                    $img['thumb_name'] = $finfo['raw_name'] . '_thumb' . $finfo['file_ext'];

                    $file = $_FILES;
                    foreach ($file as $key => $value) {

                        if ($key != 'vFrameImage') {
                            $config['upload_path'] = FRAMES_PATH.$key;
                            $config['allowed_types'] = "jpg|jpeg|png";
                            $config['max_size'] = "5000000";
                            $config['max_width'] = "3000";
                            $config['max_height'] = "3000";
                            $config['userfile'] = $key;
                            $config['file_name'] = $img['img_name'];
                            $this->upload->initialize($config);
                            $this->upload->do_upload($config['userfile']);
                        }
                    }



                    $data = array(
                        'frame_title' => $this->input->post('vTitle'),
                        'frame_description' => $this->input->post('vDescription'),
                        'frame_price' => $this->input->post('vPrice'),
                        'frame_image' => $img['img_name'],
                        'status' => $this->input->post('eStatus'),
                        'is_deleted' => $this->input->post('iIsDeleted'),
                        'created_date' => $this->input->post('dAddedDate')
                    );
                    
                    $result = $this->frames_model->add($data);
                    if ($result > 0)
                        $return = array('success' => true, 'msg' => 'Frame has been added successfully!');
                    else
                        $return = array('success' => false, 'msg' => 'Unable to add Frame. Please try again later');
                }
            } else {
                $error = validation_errors();
                $error = explode('.', $error);
                $return = array('success' => false, 'msg' => $error[0]);
            }
        }
        echo json_encode($return);
        die;
    }

    function delete($frame_id = null) {
        $status = delete($this->frames_model->frame_table, array('is_deleted' => DELETED_YES), array('frame_id' => $frame_id));
        header('Content-type: application/json');
        echo json_encode(array("success" => $status));
    }

    public function update_status($id = null) {
        $st = $this->input->post('status');
        change_status($this->frames_model->frame_table, array('status' => $st), array('frame_id' => $id));
        echo 1;
    }

    public function get_details($id = null) {
        $res = $this->frames_model->get_frame_details($id);
        echo json_encode($res->row());
        die;
    }

    public function edit($frame_id = null) {

        $post = $this->input->post();
        
        $this->data = $post;

        if (!empty($post)) {
            $this->form_validation->set_error_delimiters($prefix = '', $suffix = '.');
            /* $this->form_validation->set_rules('vTitle', 'Title', 'trim|required|xss_clean|is_unique[tbl_backgrounds.vTitle]'); */

            /* if (empty($_FILES['vBackgroundImage']['name'])) {
              $this->form_validation->set_rules('vBackgroundImage', 'Background Image', 'required');
              } */

            $existsImage = $this->frames_model->exist_image($frame_id);
            /* if ($this->form_validation->run()) { */
           
            if (!empty($_FILES['vFrameImage']['name'])) {

                $config['upload_path'] = FRAMES_UPLOAD_PATH;
                $config['allowed_types'] = "gif|jpg|jpeg|png";
                $config['max_size'] = "5000000";
                $config['max_width'] = "3000";
                $config['max_height'] = "3000";
                $config['userfile'] = "vFrameImage"; 

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload($config['userfile'])) {
                    
                    $return = array('success' => false, 'msg' => $this->upload->display_errors());
                } else {
                    
                    $finfo = $this->upload->data();
                    $unlinkImage = FRAMES_PATH . $existsImage[0]['frame_image'];

                    if ($existsImage[0]['frame_image'] != '' && file_exists($unlinkImage)) {
                        unlink($unlinkImage);
                    }

                    //$pathMain    = FCPATH. 'assets/uploads/backgrounds';
                    //vBackgroundThumbImage
                    $this->_createThumbnail($finfo['file_name'], "150", "150");

                    $img['img_name'] = $finfo['raw_name'] . $finfo['file_ext'];
                    $img['thumb_name'] = $finfo['raw_name'] . '_thumb' . $finfo['file_ext'];
                    $file = $_FILES;
                
                    foreach ($file as $key => $value) {
                            
                            $config['upload_path'] = FRAMES_PATH.$key;
                            $config['allowed_types'] = "jpg|jpeg|png";
                            $config['max_size'] = "5000000";
                            $config['max_width'] = "3000";
                            $config['max_height'] = "3000";
                            $config['userfile'] = $key;
                            $config['file_name'] = $img['img_name'];
                            $this->upload->initialize($config);
                            $this->upload->do_upload($config['userfile']);
                        
                    }
                }
            }

            /* } else {
              $error = validation_errors();
              $error = explode('.', $error);
              $return = array('success' => false, 'msg' => $error[0]);
              } */
            
            $data = array(
                'frame_title' => $this->input->post('vTitle'),
                'frame_image' => (empty($img['img_name']) ? $existsImage[0]["frame_image"] : $img['img_name']),
                'frame_description' => $this->input->post('vDescription'),
                'frame_price' => $this->input->post('vPrice'),
                'status' => $this->input->post('eStatus'),
                'is_deleted' => $this->input->post('iIsDeleted'),
                'created_date' => $this->input->post('dAddedDate'),
                'modified_date' => $this->input->post('dModifiedDate')
            );
         
            $result = $this->frames_model->edit_frame($frame_id, $data);
            if ($result > 0)
                $return = array('success' => true, 'msg' => 'Frame has been saved successfully!');
            else
                $return = array('success' => false, 'msg' => 'Unable to save Frame. Please try again later');
        } else {
            $error = validation_errors();
            $error = explode('.', $error);
            $return = array('success' => false, 'msg' => $error[0]);
        }
        echo json_encode($return);
        die;
    }

    //Create Thumbnail function
    function _createThumbnail($filename) {
        $this->image_lib->clear();
        $config['image_library'] = "gd2";
        $config['source_image'] = "./assets/uploads/backgrounds/" . $filename;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = "150";
        $config['height'] = "150";

        $this->image_lib->initialize($config);

        if ($this->image_lib->resize())
            return true;
        return false;
    }

    function resize_image($sourcePath, $desPath, $width, $height) {
        $this->image_lib->clear();
        $config['image_library'] = 'gd2';
        $config['source_image'] = $sourcePath;
        $config['new_image'] = $desPath;
        $config['quality'] = '100%';
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['thumb_marker'] = '';
        $config['width'] = $width;
        $config['height'] = $height;

        $this->image_lib->initialize($config);

        if ($this->image_lib->resize())
            return true;
        return false;
    }

    function checkframetitle() {

        $vTitle = strtolower($this->input->post('vTitle'));
        $result = $this->frames_model->checktitle($vTitle);
        
        if (!empty($result)) {
            if ($vTitle == strtolower($result->frame_title)) {
                $return_data = array('type' => '2');
                echo json_encode($return_data);
                exit;
            } else {
                $return_data = array('type' => '1');
                echo json_encode($return_data);
                exit;
            }
        }
    }

}
