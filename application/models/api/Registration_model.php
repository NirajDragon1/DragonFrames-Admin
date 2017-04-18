<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Registration_model extends CI_Model 
{
    var $table = 'users';

    function __construct() 
    {
        parent::__construct();
    }

    function signup($data = array()) 
    {
        $table_name         = 'users';
        $password           = hash('sha256', ($data['password']));
        $data['password']   = $password;
        $data['token_id']   = random_string('alnum', 16) . time();
       
        $this->db->insert($table_name, $data);
        $last_insert_id = $this->db->insert_id();
        
        if (empty($last_insert_id)) {
            return false;
        } else {
             $return_data = array('user_id'=>$last_insert_id,'access_token'=>$data['token_id']);
            return $return_data;
        }
    }

    /* function to varification newsletter subscribtion */

    public function verifyEmailAddress($verificationCode) 
    {

        $this->db->set('is_verified', 'yes')
                ->set('verification_key', time())
                ->where(['verification_key' => $verificationCode, 'is_verified' => 'no', 'isdelete' => '0'])
                ->update($this->table);

        $cnt = $this->db->affected_rows();
        return $cnt;
    }

    /* function to verify Email address for forgotpassword */

    public function verify_email($email) 
    {
        if (!empty($email)) {
            $this->db->select('user_id, emailaddress,first_name');
            $this->db->from($this->table);
            $this->db->where(array('emailaddress' => $email, 'isactive' => '1', 'isdelete' => '0', 'is_verified' => 'yes'));
            $query = $this->db->get()->row();
            if (empty($query)) {
                return $query;
            }
            
            $rand       = get_random();
            $password   = hash('sha256', ($rand));

            if (!empty($query)) {
                $this->db->set('password', $password);
                $this->db->where(['user_id' => (int) $query->user_id]);
                $this->db->update($this->table);

                $query->password = $rand;

                return $query;
            }
        }
        return false;
    }

    /* function to update verification key */

    public function update_code($email, $code) 
    {
        if (!empty($email)) {
            $insrtArr = array('verification_key' => $code);
            $this->db->set($insrtArr);
            $this->db->where(array('emailaddress' => $email));
            $this->db->update($this->table);
        }
    }

    /* Login User  Details*/

    public function login_detail($access_token) 
    {
        $login_array = array();
        $usertypeid = 0;
        $query = $this->db->select('user_id,emailaddress,first_name,country,state,city,profile_image')->from('users')->where(['token_id' => $access_token, 'isactive' => '1', 'isdelete' => '0', 'is_verified' => 'yes'])->get();
        $login['login_data'] = $query->result_array();
        if (!empty($login['login_data'])) {
            $login['login_data'] = $login['login_data'][0];
            $login_array = $login['login_data'];
        }
        return $login_array;
    }

    public function get_date_time() 
    {
        date_default_timezone_set('Asia/Kolkata');
        $date = date('m/d/Y h:i:s a', time());
        return $date;
    }

    /* Check Email */

    public function email_exist($email)
    {
        $this->db->select('user_id,social_type');
        $this->db->from($this->table);
        $this->db->where(array('emailaddress' => $email,'isdelete' => '0'));
        $query = $this->db->get()->row();
        return $query;

        
    }

    /* Social Media Login  */

    public function social_login($post = array())
    {   
        $insert_data    = array();
        $return_data    = array();
        if(!empty($post))
        {
            $check_email =  $this->email_exist($post['email_address']);
            $check_email = json_decode(json_encode($check_email),true);
            if(empty($check_email))
            {
                $insert_data['emailaddress']    = $post['email_address'];
                $insert_data['first_name']            = '';
                if(isset($post['name']))
                {
                    $insert_data['first_name']        = $post['name'];    
                }
                
                $insert_data['social_type']     = strtolower($post['social_type']);
                $insert_data['is_verified']     = 'yes';

                if( $insert_data['social_type'] == 'facebook' )
                {
                    $insert_data['facebook_id'] = $post['social_id'];
                }else if( $insert_data['social_type'] == 'google' )
                {
                    $insert_data['google_id']   = $post['social_id'];
                }
                $app_token = random_string('alnum', 16) . time();
                
                $insert_data['token_id']        = $app_token;

                $this->db->insert( $this->table, $insert_data );

                $return_data['token']       = $app_token;
                $return_data['user_id']     =  $this->db->insert_id();
                
                return $return_data;
            
            }else
            {
                if(empty($check_email['social_type']))
                {
                    $insert_data['social_type']     = strtolower($post['social_type']);

                    if( $insert_data['social_type'] == 'facebook' )
                    {
                        $insert_data['facebook_id'] = $post['social_id'];
                    }else if( $insert_data['social_type'] == 'google' )
                    {
                        $insert_data['google_id']   = $post['social_id'];
                    }
                    $app_token = random_string('alnum', 16) . time();
                    
                    $insert_data['token_id']        = $app_token;

                    $this->db->where('emailaddress',$post['email_address']);
                    $this->db->update( $this->table, $insert_data );

                    $return_data['token']       = $app_token;
                    $return_data['user_id']     = $check_email['user_id'];

                    return $return_data;
                
                }else 
                {
                    if( $check_email['social_type'] == 'facebook' && strtolower($post['social_type']) == 'facebook' )
                    {
                        $app_token                  = random_string('alnum', 16) . time();
                    
                        $insert_data['token_id']    = $app_token;
                        $insert_data['facebook_id'] = $post['social_id'];
                        $insert_data['social_type'] = strtolower($post['social_type']);

                        $this->db->where('emailaddress',$post['email_address']);
                        $this->db->update( $this->table, $insert_data );

                        $return_data['token']       = $app_token;
                        $return_data['user_id']     = $check_email['user_id'];

                        return $return_data;

                    }else if(  $check_email['social_type'] == 'facebook' && strtolower($post['social_type']) == 'google'  )
                    {
                        $app_token                  = random_string('alnum', 16) . time();
                    
                        $insert_data['token_id']    = $app_token;
                        $insert_data['google_id']   = $post['social_id'];
                        $insert_data['social_type'] = strtolower($post['social_type']);

                        $this->db->where('emailaddress',$post['email_address']);
                        $this->db->update( $this->table, $insert_data );

                        $return_data['token']       = $app_token;
                        $return_data['user_id']     = $check_email['user_id'];

                        return $return_data;

                    }else if(  $check_email['social_type'] == 'google' && strtolower($post['social_type']) == 'google'  )
                    {
                        $app_token                  = random_string('alnum', 16) . time();
                    
                        $insert_data['token_id']    = $app_token;
                        $insert_data['google_id']   = $post['social_id'];
                        $insert_data['social_type'] = strtolower($post['social_type']);

                        $this->db->where('emailaddress',$post['email_address']);
                        $this->db->update( $this->table, $insert_data );

                        $return_data['token']       = $app_token;
                        $return_data['user_id']     = $check_email['user_id'];

                        return $return_data;
                    }else if(  $check_email['social_type'] == 'google' && strtolower($post['social_type']) == 'facebook'  )
                    {
                        $app_token                  = random_string('alnum', 16) . time();
                    
                        $insert_data['token_id']    = $app_token;
                        $insert_data['facebook_id'] = $post['social_id'];
                        $insert_data['social_type'] = strtolower($post['social_type']);

                        $this->db->where('emailaddress',$post['email_address']);
                        $this->db->update( $this->table, $insert_data );

                        $return_data['token']       = $app_token;
                        $return_data['user_id']     = $check_email['user_id'];

                        return $return_data;

                    }
                } 
            }
        }
        
        return $return_data;
    }
}