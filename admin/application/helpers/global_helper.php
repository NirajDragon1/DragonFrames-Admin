<?php

function set_flash_msg($msg = '', $type = 'success') {
    $CI = &get_instance();
    $CI->session->set_userdata(array('flsh_msg' => $msg, 'flsh_msg_type' => $type));
}

function unset_flash_msg() {
    $CI = &get_instance();
    $CI->session->unset_userdata(array('flsh_msg', 'flsh_msg_type'));
}

function get_lft_sidebar() {
    $CI = &get_instance();
    $query = $CI->db->get_where('admins', array('id' => $CI->session->userdata('user_id')), 1);

    $rw = $query->row();
    if ($rw->permissions != '' || $rw->type == 0) {
        $whr = '';
        if ($rw->type != 0)
            $whr = ' WHERE id IN (' . $rw->permissions . ') ';
        $query = $CI->db->query("SELECT * FROM admin_menus $whr ORDER BY orders");
        $menus = array();
        $i = 0;
        foreach ($query->result_array() as $row) {
            foreach ($row as $k => $v) {
                $menus[$i][$k] = $v;
            }
            $i++;
        }
        $menus = format_tree($menus);
        return $menus;
    }
}

function format_tree($tree, $parent = 0) {
    $tree2 = array();
    foreach ($tree as $i => $item) {
        if ($item['parent_id'] == $parent) {
            $tree2[$item['id']] = $item;
            $tree2[$item['id']]['submenu'] = format_tree($tree, $item['id']);
        }
    }
    return $tree2;
}

if (!function_exists('delete')) {

    function delete($tbl = '', $set = null, $whr = null) {
        $CI = &get_instance();
        if (!empty($set)) {
            return $CI->db->update($tbl, $set, $whr);
        } else {
            return $CI->db->delete($tbl, $whr);
        }
    }

}

if (!function_exists('delete_feedback')) {

    function delete_feedback($tbl = '', $whr = null) {
        $CI = &get_instance();
        $CI->db->delete('feedback_details', $whr);
        return $CI->db->delete($tbl, $whr);
    }

}

if (!function_exists('change_status')) {

    function change_status($tbl = '', $set = null, $whr = null) {
        $CI = &get_instance();
        $CI->db->update($tbl, $set, $whr);
        // echo $CI->db->last_query();
        return 1;
    }

}

function get_industry() {
    $CI = &get_instance();
    $CI->db->distinct();
    $CI->db->select('iIndustryId, vName');
    $CI->db->where('iIsDeleted', '0');
    $CI->db->where('eStatus', '1');
    $CI->db->from('tbl_industries');
    $query = $CI->db->get();
    $ind = $query->result_array();
    $industry = array();

    foreach ($ind as $key => $value) {
        if (!empty($value['vName'])) {
            $industry[$value['iIndustryId']] = $value['vName'];
        }
    }
    return $industry;
}

function get_template_category() {
    $CI = &get_instance();
    $CI->db->distinct();
    $CI->db->select('iTemplateCategoryId, vTitle');
    $CI->db->from('tbl_template_categories');
    $CI->db->where('iIsDeleted', '0');
    $CI->db->where('eStatus', '1');
    $query = $CI->db->get();
    $tc = $query->result_array();
    $category = array();

    foreach ($tc as $key => $value) {
        if (!empty($value['vTitle'])) {
            $category[$value['iTemplateCategoryId']] = $value['vTitle'];
        }
    }

    return $category;
}

function get_language() {
    $CI = &get_instance();
    $CI->db->distinct();
    $CI->db->select('iLanguageId, vName');
    $CI->db->from('mod_language');
    $where = array('eStatus' => '1', 'IisDeleted' => '0');
    $CI->db->where($where);
    $query = $CI->db->get();
    $lng = $query->result_array();
    $language = array();

    foreach ($lng as $key => $value) {
        if (!empty($value['vName'])) {
            $language[$value['iLanguageId']] = $value['vName'];
        }
    }
    return $language;
}

function pr($data = array()) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die;
}

function get_template($alias = null) {
    $CI = &get_instance();
    $CI->db->select('*');
    $CI->db->from('email_template');
    $CI->db->where(array('alias' => $alias));
    $query = $CI->db->get();
    return $query->result_array();
}

function send_email($data = array()) {


    $to = $data['to'];
    $sub = $data['subject'];
    $email_msg = $data['message'];
    $from_email = $data['from_address'];
    $CI = & get_instance();
    $CI->load->library('email');
    $CI->config->load('email', TRUE);
    $CI->email->set_newline("\r\n");
    $CI->email->from($from_email);
    $CI->email->to($to);
    $CI->email->subject($sub);
    $CI->email->message($email_msg);
   // return $CI->email->send();/*
   $header = "From: ".$from_email." <".$from_email."> \r\n";
	$header .= "Reply-To: ". $from_email . "\r\n";
	$header .= "MIME-Version: 1.0\r\n";
	$header .= "Content-type: text/html\r\n";
   //ini_set("sendmail_from", $siteOwnersEmail); // for windows server
	$retval = mail ($to,$sub,$email_msg,$header);
}