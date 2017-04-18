<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Frame_model extends CI_Model {
    var $table = 'frames';

    function __construct() {
        parent::__construct();
    }

    /* get Login User Details */

    public function get_frame_details() {

        $frame_array = array();
        $usertypeid = 0; 
        $query = $this->db->select('frame_id,frame_title,frame_price,frame_description,status')
                ->select('CONCAT("'.base_url(FRAMES_PATH).'/",frame_image) as frame_image',false)
                
                ->from('frames')->where(['status' => '1', 'is_deleted' => '0'])->get();
        
        $data['frame_data'] = $query->result_array();
        
        if (!empty($data['frame_data'])) {
              
//            $data['frame_data'] = $data['frame_data'][0];
//            
//            if(!empty($data['login_data']['profile_image']) && file_exists(USERPROFILE.$data['login_data']['user_id'].'/'.$data['login_data']['profile_image']))
//            {
//                $data['login_data']['profile_image'] = base_url().ABSUSERPROFILE.$data['login_data']['user_id'].'/'.$data['login_data']['profile_image'];
//            }
//            
//            $frame_array = $data['login_data'];
             return $data;
        }
        
       // return $frame_array;
    }
}