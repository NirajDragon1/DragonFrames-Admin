<?php
class Auth {

    var $CI;
    var $_username;
    var $_table = array(
        'users' => 'users',
        'admins' => 'users'
    );

    function __construct() {
        $this->CI = & get_instance();
    }

    function Auth() {
        self::__construct();
    }

    function login($username, $password, $redirect_to = NULL, $set_cookie = false) {
        $query = $this->CI->db->get_where('users', array(
                    'emailaddress'  => $username,
                    'password'      => hash('sha256',($password)),
                    'isactive'      => ACTIVE,
                    'user_type_id'    => ADMIN,
                    'isdelete'      => DELETED
                ), 1
        );
        
        
        if ($query->num_rows() === 1) {
            $row = $query->row();
            $data = array(
                'logged_in' => TRUE,
                'user_id'   => $row->user_id,
                'type'      => $row->type     
            );
            $this->CI->session->set_userdata($data);

            if($set_cookie){
                
            }
            if($redirect_to !== NULL)
                redirect($redirect_to);
            else {
                $loginRedirect = $this->CI->session->userdata('loginRedirect');
                if($loginRedirect != ''){
                    $this->CI->session->unset_userdata('loginRedirect');
                    redirect($loginRedirect);
                }
                redirect(base_url().'users');
            }
        } else {
            return array('flsh_msg_type' => 'error', 'flsh_msg' => 'Invalid email or passowrd');
        }
    }

    function check_login($redirect = false) {
        if($redirect && !$this->CI->session->userdata('logged_in')){
             $this->CI->session->set_userdata('loginRedirect', base_url().$this->CI->uri->uri_string());
             redirect(base_url().'login');
        }
        return $this->CI->session->userdata('logged_in');
    }

    function logout($redirect_to = NULL) {
        $this->CI->session->sess_destroy();
        if ($redirect_to != NULL) {
            redirect($redirect_to);
        }else{
            redirect(base_url().'login');
        }
    }

    function check_email(){
        $return = false;
        $email = $this->CI->input->post('email');
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $query = $this->CI->db->select('vEmail')->from($this->_table['admins'])->where(array('vEmail'=> $email))->get();
            if($query->num_rows() > 0){
                
                $newPassword = random_string();
                $data = array('vPassword' => hash('sha256',($newPassword)));
                $this->CI->db->where('vEmail', $email);
                $this->CI->db->update($this->_table['admins'], $data);

                $row = $query->row();
                $return = $newPassword;
            }
        }
        return $return;
    }
    
    function check_old_password($user_id,$old_pwd){
        
         $res = $this->CI->db->select('password')->from('users')->where(['user_id' => $user_id])->get()->row();

        if(hash('sha256', ($old_pwd)) == $res->password){
            
            return true;
        }
        return FALSE;
    }
    
    
    function change_password($reset_pwd,$confirm_pwd,$user_id){
        
        //if(hash('sha256',($reset_pwd)) == hash('sha256',($confirm_pwd))){
        if($reset_pwd == $confirm_pwd){
          
            $reset_password = hash('sha256',$reset_pwd);
            $data = array('password'=>$reset_password);
            $this->CI->db->where('user_id',$user_id);
            $this->CI->db->update($this->_table['users'],$data);
            
           
            /* $this->CI->db->where('user_id',$user_id);
             $this->CI->db->set('password', $reset_pwd)
            ->update($this->_table['users']); */
            return true;
        }else {
             
            return false;
        }
    }
}

/* End of file Auth.php */
/* Location: ./application/libraries/Auth.php */
