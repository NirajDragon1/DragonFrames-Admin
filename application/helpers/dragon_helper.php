<?php

function send_mail($data) {
    if ($data['flag'] == 'newsletter') {
        $verificationCode = $data['verificationCode'];
        $to = $data['to'];
        $sub = $data['subject'];
        $email_msg = "Dear User,
         <br><p-->
            Please click on below URL or paste into your browser to verify your Newsletters.<p></p>";
        $email_msg .= $data['subscribed_from_page'] . "?request_type=verify_token&token=" . $verificationCode;
        $email_msg .= "<p>Thanks,
                       <br> Support Team</p>";

        $CI = & get_instance();
        $CI->load->library('email');
        $CI->config->load('email', TRUE);
        $CI->email->set_newline("\r\n");
        $CI->email->from(FROM_EMAIL);
        $CI->email->to($to);
        $CI->email->subject($sub);
        $CI->email->message($email_msg);
        return $CI->email->send();
        
    } else if ($data['flag'] == 'registration') {

        $verificationCode   = $data['verificationCode'];
        $to                 = $data['to'];
        $sub                = $data['subject'];
        $email_msg          = "Dear " . $data['user'] . "," .
                                "<br><br>
                                <p-->
                                Please click on below URL or paste into your browser to verify your Registration.<p></p>";
        $email_msg          .= APP_URL . "api/registration/verify?request_type=verify_token&token=" . $verificationCode;
        $email_msg          .= "<p>Thank you,
                                <br><br>Dragon Frames Support Team</p>";

        $CI = & get_instance();
        $CI->load->library('email');
        $CI->config->load('email', TRUE);
        $CI->email->from(FROM_EMAIL);
        $CI->email->to($to);
        $CI->email->subject($sub);
        $CI->email->message($email_msg);
        $CI->email->set_newline("\r\n");
        return $CI->email->send();
    } else if ($data['flag'] == 'forgotpassword') {

        $to                     = $data['to'];
        $sub                    = $data['subject'];
        $email_msg              = "Dear " . $data['user'] . "," .
                                "<br><p -->
                                 Your New Password Is: ".$data['password']."</p>";
        $email_msg              .= "<p> Thank you,<br><br>Dragon Frames Support Team</p>";

        $CI = & get_instance();
        $CI->load->library('email');
        $CI->config->load('email', TRUE);
        $CI->email->set_newline("\r\n");
        $CI->email->from(FROM_EMAIL);
        $CI->email->to($to);
        $CI->email->subject($sub);
        $CI->email->message($email_msg);
        return $CI->email->send();
    } else if ($data['flag'] == 'invoice') {

        $success_flag = false;
        $to = $data['to'];
        $sub = $data['subject'];
        $email_msg = "Please find attached payment recepit";

        $CI = & get_instance();
        $CI->load->library('email');
        $CI->config->load('email', TRUE);
        $CI->email->set_newline("\r\n");
        $CI->email->from(FROM_EMAIL);
        $CI->email->to($to);
        $CI->email->subject($sub);
        $CI->email->message($email_msg);
        $CI->email->attach($data['attachment'], 'attachment', 'payment_receipt.pdf');
        //return $CI->email->send();
        if ($CI->email->send()) {
            $success_flag = true;
            return $success_flag;
        } else {
            return $success_flag;
        }
    }
}

if (!function_exists('display_date')) {

    /**
     * Generate display date for UI from system date
     * display_date() will return a formatted date as per given format
     * 
     * @access public
     * @param string $date Input date
     * @param boolean $time true/false true - time format is required
     * @param string $format Date display format
     * @param string $timeFormat Display Time format
     */
    function display_date($date = '', $time = false, $format = 'm/d/Y', $timeFormat = ' @H:i') {
        $retDate = '';
        if ($time) {
            $retDate = date($format . $timeFormat, strtotime($date));
        } else {
            $retDate = date($format, strtotime($date));
        }
        return $retDate;
    }

}

if (!function_exists('validate_access_token')) {

    function validate_access_token() {
        $CI = & get_instance();
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            $response['message'] = lang('invalid_access_token');
            $response['status'] = 0;
            $response['code'] = 105;
            $CI->response($response);
        }
        return $headers['Authorization'];
    }

}

if (!function_exists('get_random')) {

    /**
     * Generate a random. 
     * 
     * get_random() will return a random password with length 6-8 of lowercase letters only.
     *
     * @access    public
     * @param    $chars_min the minimum length of password (optional, default 6)
     * @param    $chars_max the maximum length of password (optional, default 8)
     * @param    $use_upper_case boolean use upper case for letters, means stronger password (optional, default false)
     * @param    $include_numbers boolean include numbers, means stronger password (optional, default false)
     * @param    $include_special_chars include special characters, means stronger password (optional, default false)
     *
     * @return    string containing a random password 
     */
    function get_random($chars_min = 6, $chars_max = 8, $use_upper_case = true, $include_numbers = true, $include_special_chars = true) {
        $length = rand($chars_min, $chars_max);
        $selection = 'aeuoyibcdfghjklmnpqrstvwxz';
        if ($include_numbers) {
            $selection .= "1234567890";
        }
        if ($include_special_chars) {
            $selection .= "!@\"#$%&[]{}?|";
        }

        $password = "";
        for ($i = 0; $i < $length; $i++) {
            $current_letter = $use_upper_case ? (rand(0, 1) ? strtoupper($selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))];
            $password .= $current_letter;
        }

        return $password;
    }

}

function check_exist($email, $user_id) {

    $this->db->select('emailaddress');
    $this->db->from($this->user_table . ' as usr');
    $this->db->where('usr.user_id <> ' . $user_id);
    $this->db->where('usr.emailaddress =' . "'" . $email . "'");
    $this->db->where('usr.isdelete <> 1');
    return $this->db->get()->result();
}

function check_delete_email($email) {
    $CI = & get_instance();
    $CI->db->select('emailaddress');
    $CI->db->from('users');
    $CI->db->where('emailaddress ', $email);
    $CI->db->where('isdelete !=', 1);

    return $CI->db->get()->result();
}

function check_email_varified($email) {
    $CI = & get_instance();
    $CI->db->select('emailaddress');
    $CI->db->from('users');
    $CI->db->where('is_verified ', 'no');

    return $CI->db->get()->result();
}

function get_insurance_patient_id($user_id) {

    /* $CI = & get_instance();
      $CI->db->select('patient_id,buyer_insurance_name,buyer_insurance_id');
      $CI->db->from('patients');
      $CI->db->join('buyer_insurance','buyer_insurance.user_id ='. $user_id,'left');
      $CI->db->or_where(array('patients.user_id' => $user_id,'buyer_insurance.user_id' => $user_id));
      $CI->db->order_by('patient_id','desc');
      $CI->db->order_by('buyer_insurance_id','desc');
      $CI->db->limit(1);
      return $CI->db->get()->row(); */


    $CI = & get_instance();
    $CI->db->select('appointment_search_id');
    $CI->db->where(array('user_id' => $user_id));
    $CI->db->order_by('appointment_search_id','desc');
    $CI->db->from('appointment_search');
    $appointment_search_id = $CI->db->get()->row();
   
    $CI = & get_instance();
    $CI->db->select('*');
    $CI->db->where(array('appointment_search_id' => $appointment_search_id->appointment_search_id));
    $CI->db->from('appointment_search_insurance_relationship');
    $buyer_insurance_realationship = $CI->db->get()->row();
   
    return $buyer_insurance_realationship;
}

function check_access_token($id, $token) {

    $CI = & get_instance();
    $CI->db->select('user_id,token_id');
    $CI->db->where(array('user_id' => $id, 'token_id' => $token));
    $CI->db->from('users');
    $user_data = $CI->db->get()->row();
    $flag = false;
    if (!empty($user_data)) {
        return true;
    } else {
        return false;
    }
}

function get_user_record($id) {
    $CI = & get_instance();
    $CI->db->select('*');
    $CI->db->where(array('user_id' => $id));
    $CI->db->from('users');
    $user_data = $CI->db->get()->row();

    if (!empty($user_data)) {
        return $user_data;
    } else {
        return false;
    }
}

function pr($data = array())
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die;
}
