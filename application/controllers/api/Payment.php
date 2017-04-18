<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Payment extends REST_Controller {

    function __construct() {
        
        // Construct the parent class
        parent::__construct();
        //load the library
        $this->load->library("authex");
        $this->load->model('api/payment_model');
        $this->load->helper('string');
    }

    /* function for payment  */

    public function payment_post($data = array()) {
        $response = ['status' => 0, 'message' => '', 'code' => ''];
      
        $this->form_validation->set_error_delimiters($prefix = '', $suffix = '');
        //$this->form_validation->set_rules('order_id', 'order id', 'required|trim');
        $this->form_validation->set_rules('user_id', 'user id', 'required|trim');
        $this->form_validation->set_rules('transaction_id', 'transaction id', 'required|trim');
        $this->form_validation->set_rules('price', 'price', 'required|trim');
        $this->form_validation->set_rules('qty', 'quentity', 'required|trim');
        $this->form_validation->set_rules('frametitle', 'frame title', 'required|trim');
        
        $post = $this->input->post;
        
        if (empty($post)) {
            if ($this->form_validation->run() == FALSE) {
                $response = [
                    'code'    => parent::HTTP_BAD_REQUEST,
                    'message' => validation_errors()
                ];
            } else {
                $filename = '';
                $filename2 = '';
                if (!empty($_FILES)) {
                    if (!empty($_FILES['photo'])) {
                        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);

                        //Create the thumbnail
                        //$newThumb = $this->CroppedThumbnail($_FILES['photo']['tmp_name'], DEFAULT_PROFILE_WIDTH, DEFAULT_PROFILE_HEIGHT, $ext);

                        // And display the image...
                        $old = umask(0);

                        if (!is_dir(PHOTOIMAGES)) {                       
                            mkdir(PHOTOIMAGES, 0777);
                        }

                        if (!is_dir(PHOTOIMAGES . $post['user_id'])) {
                            mkdir(PHOTOIMAGES . $post['user_id'], 0777);
                        }

                        $filename = 'photo_' . substr(time(), -4). '.' . $ext;
                        $upload_path = PHOTOIMAGES .  '/' . $filename;
                        move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path);

                        umask($old);

                    }
                    if (!empty($_FILES['photo2'])) {
                        $ext = pathinfo($_FILES['photo2']['name'], PATHINFO_EXTENSION);

                        //Create the thumbnail
                        //$newThumb = $this->CroppedThumbnail($_FILES['photo']['tmp_name'], DEFAULT_PROFILE_WIDTH, DEFAULT_PROFILE_HEIGHT, $ext);

                        // And display the image...
                        $old = umask(0);

                        if (!is_dir(PHOTOIMAGES2)) {                       
                            mkdir(PHOTOIMAGES2, 0777);
                        }

                        if (!is_dir(PHOTOIMAGES2 . $post['user_id'])) {
                            mkdir(PHOTOIMAGES2 . $post['user_id'], 0777);
                        }

                        $filename2 = 'photo_' . substr(time(), -4). '.' . $ext;
                        $upload_path = PHOTOIMAGES2 .  '/' . $filename2;
                        move_uploaded_file($_FILES['photo2']['tmp_name'], $upload_path);                   
                        umask($old);
                    }
                }
                $data1 = array(
                    'user_id'  => $this->input->post('user_id'),
                    'transaction_id' => $this->input->post('transaction_id'),
                    'frametitle' => $this->input->post('frametitle'),
                    'description' => $this->input->post('description'),
                    'price' => $this->input->post('price'),
                    'qty' => $this->input->post('qty'),
                    'created_date' => date('Y-m-d H:i:s'),
                    'photo' => $filename,  
                    'photo2' => $filename2,  
                );
               
                $payment_id   = $this->payment_model->payment($data1);
                   
                    if ($payment_id) {
                        $response = [
                            'status' => 1,
                            'code' => parent::HTTP_OK,
                            'message' => $this->lang->line('success_payment'),
                            'data' => $payment_id
                        ];
                    } else {
                        $response = [
                            'status' => 0,
                            'code' => parent::HTTP_BAD_REQUEST,
                            'message' => $this->lang->line('fail_payment')
                        ];
                    }   
            }
        }
        $this->response($response);
    }
//    public function CroppedThumbnail($imgSrc, $thumbnail_width, $thumbnail_height, $ext) {
//        
//        //$imgSrc is a FILE - Returns an image resource.
//        //getting the image dimensions  
//        list($width_orig, $height_orig) = getimagesize($imgSrc);
//        if (strtolower($ext) == 'jpg' || strtolower($ext) == 'jpg') {
//            $myImage = imagecreatefromjpeg($imgSrc);
//        } else if (strtolower($ext) == 'png') {
//            $myImage = imagecreatefrompng($imgSrc);
//        }
//        
//        $ratio_orig = $width_orig / $height_orig;
//        
//        if ($thumbnail_width / $thumbnail_height > $ratio_orig) {
//            $new_height = $thumbnail_width / $ratio_orig;
//            $new_width = $thumbnail_width;
//        } else {
//            $new_width = $thumbnail_height * $ratio_orig;
//            $new_height = $thumbnail_height;
//        }
//
//        $x_mid = $new_width / 2;  //horizontal middle
//        $y_mid = $new_height / 2; //vertical middle
//
//        $process = imagecreatetruecolor(round($new_width), round($new_height));
//
//        imagecopyresampled($process, $myImage, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);
//        $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
//        imagecopyresampled($thumb, $process, 0, 0, ($x_mid - ($thumbnail_width / 2)), ($y_mid - ($thumbnail_height / 2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);
//
//        imagedestroy($process);
//        imagedestroy($myImage);
//        return $thumb;
//    }
}