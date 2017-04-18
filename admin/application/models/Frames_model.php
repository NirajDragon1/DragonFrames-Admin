<?php

class Frames_model extends CI_Model {

    var $frame_table = 'frames';
    
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
            $whr = " WHERE ( (fm.frame_title LIKE '%$srchChars%' ) AND fm.is_deleted = '0' ) ";
        } else {
            $whr = " WHERE ( fm.is_deleted = '0' ) ";
        }

        $total = $this->db->query("SELECT COUNT(fm.frame_id) AS total_rows "
                        . " FROM " . $this->frame_table . " AS fm "
                        . "$whr")->row();

        $q = $this->db->query(" SELECT 
                        fm.frame_id,
                        fm.frame_title AS Title,
                        fm.frame_image,
                        fm.frame_description,
                        fm.frame_price,
                        fm.status,
                        fm.is_deleted,
                        fm.created_date "
                . " FROM " . $this->frame_table . " AS fm "
                . "$whr"
                . "ORDER BY $sortCols $sortOrdr "
                . "LIMIT $offset, $limit");

        $results = array();
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
               //echo "<pre>";print_r($row);exit;
                $status = '<input type="checkbox" name="my-checkbox" class="make-switch" ' . ($row->status == '1' ? 'checked' : '') . ' data-action="' . base_url('frames/update_status/' . $row->frame_id) . '">';

                $actions = anchor(base_url('frames/get_details/' . $row->frame_id . '/'), '<i class="fa fa-pencil"></i>', 'title="Edit" class="text-light-blue background-edit-modal"')
                        . anchor(base_url('frames/delete/' . $row->frame_id . '/'), '<i class="fa fa-trash-o"></i>', 'title="Delete" class="lnk-delete"');

                $img_url=base_url(FRAMES_PATH);
                $img_url=str_replace('/admin','',$img_url);
                if ($row->frame_image != '') {
                    //$frame_img = '<img src="' . base_url() . SITE_UPLOADS . 'frames/' . $row->frame_image . '" alt="' . $row->Title . '" height="80" width="80">';
                    $frame_img = '<img src="' .$img_url . '/'.$row->frame_image . '" alt="' . $row->Title . '" height="80" width="80">';
                } else {
                    $frame_img = '<img src="' . base_url() . SITE_IMG . 'blur-background09.png" alt="Default Background Image" height="80" width="80">';
                }

                $results[] = array(
                    $row->frame_id,
                    $row->Title,
                    $frame_img,
                    $row->frame_description,
                    $row->frame_price,
                    $row->created_date,
                    $status,
                    $actions
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

    public function add($data) {
        $this->db->set($data);
        $this->db->insert($this->frame_table);

        return $this->db->insert_id();
    }

    public function get_frame_details($frame_id = null) {
      
        //return $this->db->select('*')->from($this->frame_table)->where(array('frame_id' => $frame_id))->get();
         return $this->db->select('frame_id,frame_title,frame_price,frame_description,status')
                ->select('CONCAT("'.SITE_URL.FRAMES_PATH.'",frame_image) as frame_image',false)
                ->from($this->frame_table)->where(['status' => '1', 'is_deleted' => '0','frame_id' => $frame_id])->get();
    }

    public function edit_frame($frame_id, $data) {
        $this->db->set($data);
        $this->db->where(array('frame_id' => $frame_id));
        $this->db->update($this->frame_table);
        return 1;
    }

    function exist_image($id) {
        $this->db->select('frame_image');
        $this->db->from('frames');
        $this->db->where('frame_id', $id);
        return $this->db->get()->result_array();
    }
    
    function checktitle($vTitle){
        $this->db->select('frame_title');
        $this->db->where(array('frame_title' => $vTitle, 'is_deleted' => '0'));
        $this->db->from($this->frame_table);
        $data = $this->db->get()->row();
        return $data;
    }
}