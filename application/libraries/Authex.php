<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Authex {

    function Authex() {
        $CI = & get_instance();

        //load libraries
        $CI->load->database();
        $CI->load->library("session");
        $this->table_name = 'users';
    }

    function verify_tokenid($data = []) {

        $CI = & get_instance();
        if (empty($data)) {
            $token = $CI->input->post('access_token');
            $data = ['token_id' => $token];
        }

        $query = $CI->db->get_where($this->table_name, $data);

        if ($query->num_rows() !== 1) {
            $CI->response([
                'status' => 0,
                'message' => 'Invalid token',
                'code' => 101
            ]);
        } else {
            $result_data = $query->result_array();
            if ($result_data[0]['isactive'] != "1") {
                $CI->response([
                    'status' => 0,
                    'message' => 'Sorry, your account is suspended, please contact to administrator',
                    'code' => 102
                ]);
            } else {
                return $result_data[0]['user_id'];
            }
        }
    }

    function logged_in() {
        $CI = & get_instance();
        return ($CI->session->userdata("user_id")) ? true : false;
    }

    function login($email, $password) {
        $CI = & get_instance();

        $DataArray = array(
            "emailaddress" => $email,
            "password" => $password
        );
        $queryRecord = $CI->db->get_where($this->table_name, $DataArray);
        
        $lastRecord = $queryRecord->last_row();
        
        if(!empty($lastRecord)){
                 if(!empty($lastRecord->profile_image) && file_exists(USERPROFILE.$lastRecord->user_id.'/'.$lastRecord->profile_image))
            {
                $lastRecord->profile_image = base_url().ABSUSERPROFILE.$lastRecord->user_id.'/'.$lastRecord->profile_image;
            }
           }
        if (empty($lastRecord)) {
            $return_array = ['status' => 0];
            return $return_array;
        } else if ($lastRecord->isactive == "0" || $lastRecord->isdelete == "1") {
            $return_array = ['status' => -2];
            return $return_array;
        }  else if ($lastRecord->is_verified == 'no') {
            $return_array = ['status' => -1];
            return $return_array;
        } else {
            $app_token = random_string('alnum', 16) . time();
            $CI->db->update($this->table_name, [ "token_id" => $app_token], [ 'user_id' => $lastRecord->user_id]);

            //store app token in the session
            $return_array = [
                'status'    => 1,
                'token'     => $app_token,
                'user_id'   => $lastRecord->user_id,
                'name'      => $lastRecord->first_name,
                'lname'      => $lastRecord->last_name,
                'email'     => $lastRecord->emailaddress,
                'address'   => $lastRecord->address,
                'phone'   => $lastRecord->phone,
                'city'      => $lastRecord->city,
                'state'     => $lastRecord->state,
                'country'   => $lastRecord->country,
                'zipcode'      => $lastRecord->zipcode,
                'profile_image' => $lastRecord->profile_image,
            ];
            return $return_array;
        }
    }

    function admin_loged_in($admin_id, $user_id, $admin_password) {
        $CI = & get_instance();

        if ($admin_id != $user_id) {
            $password = hash('sha256', $admin_password);
            $data = array(
                "user_id" => $admin_id,
                "password" => $password,
                'usertypeid' => "3",
            );

            $query = $CI->db->get_where($this->table_name, $data);
            $result = $query->row();

            if (!empty($result)) {

                $query = $CI->db->get_where($this->table_name, [ "user_id" => $user_id]);
                $user_result = $query->row();

                if ($user_result) {
                    $return_array = [
                        'status' => 1,
                        'token' => $user_result->token_id,
                        'user_id' => $user_result->user_id,
                        'firstname' => $user_result->firstname,
                        'lastname' => $user_result->lastname,
                        'email' => $user_result->emailaddress,
                        'user_type' => get_user_type($user_result->usertypeid)
                    ];
                } else {
                    $return_array = ['status' => 0, 'message' => 'User not exists'];
                }
            } else {
                $return_array = ['status' => -1];
            }
        } else {
            $return_array = ['status' => -2];
        }
        return $return_array;
    }

    function logout($token) {

        $CI = & get_instance();
        $app_token = random_string('alnum', 16) . time();
        $CI->db->where(['token_id' => $token]);
        $CI->db->update($this->table_name, ['token_id' => $app_token]);

        $return_array = array(
            'status' => 1
        );
        return $return_array;
    }

}
