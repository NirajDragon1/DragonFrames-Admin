<?php

class Users_model extends CI_Model {

    var $user_table = 'users';
    var $usertype_table = 'user_type';
    var $contact_table = 'contact';
    var $model_user_data = NULL;
     
    public function __construct() {
        parent::__construct();
    }

    public function get_list() {
        $type = $this->db->select('*')->from($this->usertype_table)->get()->result();
        $user_type = array();
        foreach ($type as $key => $value) {
            $user_type[$value->user_type_id] = $value->user_type_name;
        }
        
        $this->load->helper('text');
        $post = $this->input->post();
        $srchChars = $this->db->escape_like_str($post['sSearch']);
        $sortCols = $post['iSortTitle_0'];
        $sortOrdr = $post['sSortDir_0'];
        $offset = $post['iDisplayStart'];
        $limit = $post['iDisplayLength'];
        $sEcho = $post['sEcho'];

        $whr = '';
        if ($srchChars != '') {
             $whr = " WHERE ( usr.user_id LIKE '%$srchChars%' OR usr.emailaddress LIKE '%$srchChars%' OR usr.first_name LIKE '%$srchChars%' OR usr.last_name LIKE '%$srchChars%'
                     OR usr.address LIKE '%$srchChars%'OR usr.city LIKE '%$srchChars%' OR usr.state LIKE '%$srchChars%' OR usr.zipcode LIKE '%$srchChars%' OR usr.phone LIKE '%$srchChars%'
                     OR usr.is_verified LIKE '%$srchChars%') AND usr.isdelete = '0'";
        } else {
           $whr = " WHERE ( usr.isdelete = '0') ";
        }

        $total = $this->db->query("SELECT COUNT(usr.user_id) AS total_rows "
                . " FROM ".$this->user_table." AS usr "
                . "$whr")->row();

        $q  = $this->db->query("SELECT usr.user_id, usr.user_type_id, usr.emailaddress,usr.first_name, usr.last_name, usr.address, usr.city, 
                        usr.state, usr.zipcode, usr.phone,usr.profile_image, usr.isactive, usr.is_verified "
                . " FROM ".$this->user_table." AS usr "
                . "$whr"
                . "ORDER BY $sortCols $sortOrdr "
                . "LIMIT $offset, $limit");
        
        $results = array();
       
        if ($q->num_rows() > 0) {
    
            foreach ($q->result() as $row) {
                
                $status = '<input type="checkbox" name="my-checkbox" class="make-switch" ' . ($row->isactive == '1' ? 'checked' : '') . ' data-action="' . base_url('users/update_status/' . $row->user_id) . '">';
                //$contactdetails  =   anchor(base_url('contact/details/'.$row->user_id.'/'),'('.$row->total_contact_count.') Contacts', 'title="Contacts Details" class="lnkdetails" ');
                $contactdetails  =   anchor(base_url('contact/details/'.$row->user_id.'/'),'Contacts Details', 'title="Contacts Details" class="lnkdetails" ');

                $actions = anchor(base_url('users/get_details/' . $row->user_id . '/'), '<i class="fa fa-pencil"></i>', 'title="Edit" class="text-light-blue user-edit-modal"')
                        . anchor(base_url('users/delete/' . $row->user_id . '/'), '<i class="fa fa-trash-o"></i>', 'title="Delete" class="lnk-delete"');

                if ($row->profile_image != '') {
                    
                    if(!empty($row->profile_image) && file_exists(USERPROFILE.$row->user_id.'/'.$row->profile_image))
                    {
                      // $row->profile_image = base_url().ABSUSERPROFILE.$row->id.'/'.$row->profile_image;
                       
                    $profile_img = '<img src="' . ABSUSERPROFILE.$row->user_id.'/'.$row->profile_image . '" alt="' . $row->first_name . ' ' . $row->last_name . '" height="80" width="80">';
                    
                    }
                } else {
                    $profile_img = '<img src="' . base_url() . SITE_IMG . 'avatar.png" alt="Default User Profile Image" height="80" width="80">';
                }
                
                   $results[] = array(
                    $row->user_id,
                    //$profile_img,     
                    $row->emailaddress,   
                    $row->first_name,
                    $row->last_name,
                    $row->address,   
                    $row->city,
                    $row->state,   
                    $row->zipcode,
                    $row->phone,
                    //$contactdetails,
                    //$status,
                   // $actions
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
    
     public function update_profile_image($data) {
         
        $this->db->set('profile_image',$data['profile_image']);
        $this->db->where(array('user_id' => $data['user_id']));
        $this->db->update($this->user_table);
        return 1;
    }

    function exist_image($user_id) {
        $this->db->select('profile_image');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);
        return $this->db->get()->result_array();
    }
    
        function getData($loadType, $loadId) {
        if ($loadType == "state") {
            $fieldList = 'iStateId AS iId,vState AS vName';
            $table = 'mod_state';
            $fieldName = 'iCountryId';
            $orderByField = 'vState';
        } else if ($loadType == "city") {
            $fieldList = 'iCityId AS iId,vCity AS vName';
            $table = 'mod_city';
            $fieldName = 'iStateId';
            $orderByField = 'vCity';
        } else if ($loadType == "designation") {
            $fieldList = 'iDesignationId AS iId,vName AS vName';
            $table = 'tbl_designations';
            $fieldName = 'iIndustryId';
            $orderByField = 'vName';
        }
        $this->db->select($fieldList);
        $this->db->from($table);
        $this->db->where($fieldName, $loadId);
        $this->db->order_by($orderByField, 'ASC');
        $query = $this->db->get();
        return $query;
    }

    public function add($data) {
        $this->db->set($data);
        $this->db->insert($this->user_table);

        return $this->db->insert_id();
    }

    public function get_user_details($user_id = null) {
        
        return $this->db->select('*')->from($this->user_table)->where(array('user_id' => $user_id))->get()->result_array();
    }

    //get all users details
    public function get_all_user_details() {
        return $this->db->select('*')->from($this->user_table)->where(array('iIsDeleted' => '0', 'eStatus' => '1', 'eUserType' => 'User'))->get();
    }
    
    public function edit_user($user_id, $data) {
        $this->db->set($data);
        $this->db->where(array('user_id' => $user_id));
        $this->db->update($this->user_table);
        return 1;
    }

   public function get_all_userlist() {
        return $this->db->select('*')->from($this->user_table)->where(array('eUserType' => "User"))->get()->result_array();
    }
    
    public function get_count_user(){
      return $this->db->select('count(user_id) as totalUser')->from('users')->where(array('isdelete' => '0'))->get()->result_array();  
    }
    
    public function get_count_contact(){
      return $this->db->select('count(contact_id) as totalContact')->from('contact')->get()->result_array();  
    }

    public function checkEmailExistence($email) {
        
        $user_info = false;
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where(array('emailaddress' => $email, 'user_type_id' => '2'));
        $user_info = $this->db->get()->result_array();
        return $user_info;
    }
    
    function set_user_status($user_id, $status) {
        $this->db->select('*');
        $this->db->from($this->user_table);
        $this->db->where(array('user_id' => $user_id, 'user_type_id' => '2', 'isdelete' => '0'));
        $user_info = $this->db->get()->row();
        $this->model_user_data = $user_info;
        $app_user_flag = 0;
        $email_flag = false;
        if (!empty($user_info)) {
            $length = 6;
            $chars = "0123456789";
            $new_password = substr(str_shuffle($chars), 0, $length);
            if ($user_info->app_user_flag == '1' && $status == 1) {
                $app_user_flag = '2';
                $email_flag = true;
            }
            $update_fields = array('isactive' => $status);
            if ($email_flag) {
                $update_fields['app_user_flag']=$app_user_flag;
                $update_fields['password'] = md5($new_password);
            }
            $this->model_user_data->password=$new_password;
            $this->db->set($update_fields);
            $this->db->where(array('user_id' => $user_id));
            $this->db->update($this->user_table);
            
        }
        return $email_flag;
    }
}