<?php

class Payment_model extends CI_Model {

    var $payment_table = 'payment';
     
    public function __construct() {
        parent::__construct();
    }

    public function get_list() {
        $this->load->helper('text');
        $post = $this->input->post();
        //echo "<pre>";print_r($post);
        $srchChars = $this->db->escape_like_str($post['sSearch']);
        $sortCols = $post['iSortTitle_0'];
        $sortOrdr = $post['sSortDir_0'];
        $offset = $post['iDisplayStart'];
        $limit = $post['iDisplayLength'];
        $sEcho = $post['sEcho'];

        $whr = '';
        if ($srchChars != '') {
             $whr = " WHERE ( pmt.payment_id LIKE '%$srchChars%' OR pmt.emailaddress LIKE '%$srchChars%' OR usr.first_name LIKE '%$srchChars%' OR usr.last_name LIKE '%$srchChars%'
                     OR usr.address LIKE '%$srchChars%'OR usr.city LIKE '%$srchChars%' OR usr.state LIKE '%$srchChars%' OR usr.zipcode LIKE '%$srchChars%' OR usr.phone LIKE '%$srchChars%'
                     OR usr.is_verified LIKE '%$srchChars%') AND usr.isdelete = '0'";
        } else {
           $whr = "";
        }

        $total = $this->db->query("SELECT COUNT(pmt.payment_id) AS total_rows "
                . " FROM ".$this->payment_table." AS pmt "
                . "$whr")->row();

        $q  = $this->db->query("SELECT pmt.*,u.first_name,u.last_name,u.emailaddress,u.phone "
                . " FROM ".$this->payment_table." AS pmt LEFT JOIN users AS u ON u.user_id = pmt.user_id "
                . "$whr"
                . "ORDER BY $sortCols $sortOrdr "
                . "LIMIT $offset, $limit");
        
        $results = array();
       
        if ($q->num_rows() > 0) {
    
            foreach ($q->result() as $row) {
                $photo_img='';
                $photo_img2='';
                if ($row->photo != '') {
                    
                    if(!empty($row->photo) && file_exists(PHOTOIMAGE.$row->photo))
                    {
                      // $row->profile_image = base_url().ABSUSERPROFILE.$row->id.'/'.$row->profile_image;                       
                        $photo_img = '<img src="' . ABSPHOTOIMAGE .'/'.$row->photo . '" alt="photo" height="50" width="50">';
                    }
                } else {
                    $photo_img = '<img src="' . base_url() . SITE_IMG . 'avatar.png" alt="Default Photo Image" height="80" width="80">';
                }
                 if ($row->photo2 != '') {
                    
                    if(!empty($row->photo2) && file_exists(PHOTOIMAGE2.$row->photo2))
                    {
                        $photo_img2 = '<img src="' . ABSPHOTOIMAGE2 .'/'.$row->photo2 . '" alt="photo" height="50" width="50">';
                    }
                } else {
                    $photo_img2 = '<img src="' . base_url() . SITE_IMG . 'avatar.png" alt="Default Photo Image" height="80" width="80">';
                }
                
                   $results[] = array(
                    $row->order_id,
                    $photo_img,     
                    $photo_img2,     
                    $row->first_name,   
                    $row->last_name,
                    $row->emailaddress,
                    $row->phone,   
                    $row->transaction_id,
                    $row->frametitle,   
                    '$'.$row->price,
                );
            }
        }
        $result = array(
            "sEcho" => $sEcho,
            "iTotalRecords" => $total->total_rows,
            "iTotalDisplayRecords" => $total->total_rows,
            "aaData" => $results
        );
        return $result;
    }
    
    public function get_count_payment(){
      return $this->db->select('count(payment_id) as totalPayment')->from('payment')->get()->result_array();  
    }
}