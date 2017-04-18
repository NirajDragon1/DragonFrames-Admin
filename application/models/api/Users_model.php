<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users_model extends CI_Model {
    var $table = 'users';

    function __construct() {
        parent::__construct();
    }

    /* get Login User Details */

    public function login_detail($access_token) {

        $login_array = array();
        $usertypeid = 0;
        $query = $this->db->select('user_id,emailaddress,first_name,last_name,address,phone,country,state,city,zipcode,profile_image')->from('users')->where(['token_id' => $access_token, 'isactive' => '1', 'isdelete' => '0', 'is_verified' => 'yes'])->get();
        
        $login['login_data'] = $query->result_array();
        //echo ABSUSERPROFILE;
        
        if (!empty($login['login_data'])) {
            $login['login_data'] = $login['login_data'][0];
            
            if(!empty($login['login_data']['profile_image']) && file_exists(USERPROFILE.$login['login_data']['user_id'].'/'.$login['login_data']['profile_image']))
            {
                $login['login_data']['profile_image'] = base_url().ABSUSERPROFILE.$login['login_data']['user_id'].'/'.$login['login_data']['profile_image'];
            }
            //print_r($login);die;
            $login_array = $login['login_data'];
        }
        
        return $login_array;
    }


    /* Update User Details */

    public function update($data) 
    {
        
        if(!empty($data))
        {
            $this->db->set('first_name', $data['first_name'])
            ->set('last_name', $data['last_name'])
            ->set('country', $data['country'])
            ->set('city', $data['city'])
            ->set('state', $data['state'])
            ->set('address', $data['address'])
            ->set('zipcode', $data['zipcode'])
            ->set('phone', $data['phone'])
            ->set('profile_image', $data['profile_image'])
            ->where(['user_id' => $data['user_id']])
            ->update($this->table);
            return true;
        }
        return false;
    }

     /* Get Profile Image */

    public function profile_image($access_token = null,$user_id = null) 
    {

        $login_array = array();
        $usertypeid = 0;
        $query = $this->db->select('profile_image')->from('users')->where(['token_id' => $access_token, 'user_id' => $user_id])->get();
        
        $login['login_data'] = $query->result_array();
        
        if (!empty($login['login_data'])) {
            $login['login_data'] = $login['login_data'][0];
            $login_array = json_decode(json_encode($login['login_data']),true);
        }
        return $login_array;
    }
    
    public function check_password($user_id, $old_pwd){
        $res = $this->db->select('password')->from('users')->where(['user_id' => $user_id])->get()->row();

        if(hash('sha256', ($old_pwd)) == $res->password){
            return true;
        }
        return FALSE;
    }
    
    public function change_password($reset_pwd,$confirm_pwd){
        
        if(hash('sha256',($reset_pwd)) == hash('sha256',($confirm_pwd))){
             $this->db->set('password', $reset_pwd)
            ->update($this->table);
            return true;
        }else {
            return false;
        }
    }
}