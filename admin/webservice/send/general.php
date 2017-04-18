<?php
/*
 * To get states,city category etc
 */
class WebService extends GeneralClass {

    public $settings = "";
    /**
     * constructor function
     */
    function __construct($settingobj) {
        parent::__construct($settingobj);
        $this->settings = $settingobj;
    }

    // Check device exist or not
    // Return device details if device found
    public function check_device($token = ''){
        return $this->db->count('devices', array('device_token' => $token)) > 0 ? $this->get_device_details($token) : array();
    }
    
    /*
     *  get device details
     *  @param device token
     */
    public function get_device_details($token = ''){
        return $this->db->select('devices', array('*'), array('device_token' => $token))->result();
        
    }

    /**
     * get all categories/books ws
     * 
     * @param type $requestParams
     * @return type
     */
    public function get_listing($requestParams = NULL) {
        
        $responseArr = array("status" => "Fail", 'data' => array(), "message" => 'No data found');
        $whrArr      = array('status' => 1);
        $srchChars   = isset($requestParams['search']) ? trim(strip_tags($requestParams['search'])) : '';
        
        if(isset($requestParams['type']) && in_array($requestParams['type'], array('books','categories'))){
            $type = $requestParams['type'];
            
            if($srchChars != ''){
                if($type == 'categories'){
                    $whrArr['category_name LIKE'] = "%$srchChars%";
                }else{
                    $whrArr['name LIKE'] = "%$srchChars%";
                }
            }
            $whrStr = implode(' AND ', $whrArr);
            $whrStr = ' AND '.$whrStr;
            $data[$type] = $this->db->select($requestParams['type'], array('*'), $whrArr)->results();
            
            $responseArr = array("status" => "Success", "message" => '', 'data' => $data);
        }else{
            if($srchChars != '')
                $whrArr['name LIKE'] = "%$srchChars%";
            $data['books'] = $this->db->select('books', array('*'), $whrArr)->results();

            if($srchChars != ''){            
                $whrArr = array('status' => 1);
                $whrArr['category_name LIKE'] = "%$srchChars%";
            }
            $data['categories'] = $this->db->select('categories', array('*'), $whrArr)->results();
            $responseArr = array("status" => "Success", "message" => '', 'data' => $data);
            
        }
        return $responseArr;
    }

    /**
     * get all verses ws
     * 
     * @param type $requestParams
     * @return type
     */
    public function get_verses($requestParams = NULL, $myFavourites = false) {

        $responseArr = array("status" => "Fail", 'data' => array(), "message" => 'No data found');
        
        $sortCols = 'id';
        $sortOrdr = 'desc';
        if(isset($requestParams['sort'])){
            $sort = (array)$requestParams['sort'];
            $sortCols = isset($sort['sort_column']) ? $sort['sort_column'] : $sortCols;
            $sortOrdr = isset($sort['sort_order']) ? $sort['sort_order'] : $sortOrdr;
        }
        
        $page_no = isset($requestParams['page_no']) ? $requestParams['page_no'] : 1;
        $limit   = isset($requestParams['page_limit']) ? $requestParams['page_limit'] : 10;        
        $offset  = ($page_no - 1) * $limit;

        $device_token  = isset($requestParams['device_token']) ? ($requestParams['device_token']) : '';
        $chkDevice     = $this->check_device($device_token);
        
        $verse_id      = isset($requestParams['verse_id']) ? (int)$requestParams['verse_id'] : 0;
        $category_id   = isset($requestParams['category_id']) ? (int)$requestParams['category_id'] : 0;
        $book_id       = isset($requestParams['book_id']) ? (int)$requestParams['book_id'] : 0;
        $srchChars     = isset($requestParams['search']) ? trim(strip_tags($requestParams['search'])) : '';

        $whr = $bindParam = array();
        if($srchChars != ''){
            $whr[] = " ( vrv.title LIKE ? OR vrv.description LIKE ? ) ";
            $bindParam = array("%$srchChars%", "%$srchChars%");
        }
        if($category_id > 0){
            $whr[] = " vrv.category_id = ? ";
            $bindParam = array_merge($bindParam, array($category_id));
        }
        if($book_id > 0){
            $whr[] = " vrv.book_id = ? ";
            $bindParam = array_merge($bindParam, array($book_id));
        }
        if($verse_id > 0){
            $whr[] = " vrv.id = ? ";
            $bindParam = array_merge($bindParam, array($verse_id));
        }
        
        $whrStr = implode(' AND ', $whr);
        $whrStr = $whrStr != '' ? ' AND '.$whrStr : '';
        $total = $this->db->pdoQuery("SELECT COUNT(vrv.id) AS total_rows "
                . "FROM verses AS vrv "
                . "LEFT JOIN categories AS cat ON vrv.category_id = cat.id AND cat.status = 1 "
                . "INNER JOIN books AS bk ON bk.id = vrv.book_id AND bk.status = 1 "
                . ($myFavourites == true ? 'INNER' : 'LEFT')." JOIN favourites AS fvr ON fvr.device_id = ".( isset($chkDevice['id']) ? ($chkDevice['id']) : 0 )." AND fvr.verse_id = vrv.id "
                . "WHERE vrv.status = 1 $whrStr", $bindParam)->result();
        
        $data['results'] = $this->db->pdoQuery("SELECT cat.category_name, bk.name, vrv.*, IF(fvr.id > 0, 1, 0) AS favourite "
                . "FROM verses AS vrv "
                . "LEFT JOIN categories AS cat ON vrv.category_id = cat.id AND cat.status = 1 "
                . "INNER JOIN books AS bk ON bk.id = vrv.book_id AND bk.status = 1 "
                . ($myFavourites == true ? 'INNER' : 'LEFT')." JOIN favourites AS fvr ON fvr.device_id = ".( isset($chkDevice['id']) ? ($chkDevice['id']) : 0 )." AND fvr.verse_id = vrv.id "
                . "WHERE vrv.status = 1 $whrStr"
                . "ORDER BY $sortCols $sortOrdr "
                . "LIMIT $offset, $limit", $bindParam)->results();
        $data['total'] = (int)$total['total_rows'];
        $responseArr = array("status" => "Success", "message" => '', 'data' => $data);
        return $responseArr;
    }
    
    /**
     * make favourite/unfavourite verses
     * 
     * @param type $requestParams
     * @return type
     */
    public function favourites($requestParams = NULL) {
        $responseArr = array("status" => "Fail", 'data' => array(), "message" => '');

        $device_token= isset($requestParams['device_token']) ? ($requestParams['device_token']) : '';
        $verses_id   = isset($requestParams['verse_id']) ? ($requestParams['verse_id']) : '';
        $favourite   = isset($requestParams['favourite']) && $requestParams['favourite'] == 1 ? 1 : 0;

        $chkDevice   = $this->check_device($device_token);
        
        if(!empty($chkDevice)){
            if($favourite){
                $chkFavourtie = $this->db->count('favourites', array('device_id' => $chkDevice['id'], 'verse_id' => $verses_id));
                if($chkFavourtie <= 0){
                    $this->db->insert('favourites', array('device_id' => $chkDevice['id'], 'verse_id' => $verses_id, 'created_date' => date('Y-m-d H:i:s')));
                }
                $responseArr = array("status" => "Success", 'data' => array(), "message" => 'You have successfully favortied the verse');
            }else{
                $this->db->delete('favourites', array('device_id' => $chkDevice['id'], 'verse_id' => $verses_id));
                $responseArr = array("status" => "Success", 'data' => array(), "message" => 'You have successfully unfavorited the verse');
            }
        }else{
            $responseArr['message'] = 'noDevice';
        }
        return $responseArr;
    }
    
    public function my_favourites($requestParams = NULL){
        return $this->get_verses($requestParams, true);
    }

 /* PUSH Notification.. */
    public function push_notification(){
        $devices = $this->db->select('devices', array('*'))->results();
        $apnPath = IOS_PEM_FILE;

        require_once(SERVER_ROOT_PATH."system/library/classes/iphonePushNotify.cls.php");
        $pushObj = new NicIphonePushNotify($apnPath);

        require_once(SERVER_ROOT_PATH."system/library/classes/androidPushNotify.class.php");
        $pushAndroObj = new NicAndroidPushNotify();

        $requestParams = array(
            'page_limit' => 1,
            'sort'       => array('sort_column' => 'RAND()')
        );
        $latest_verse = $this->get_verses($requestParams);
        $push_verse = $latest_verse['data']['results'][0]['description'];

        foreach ($devices as $d){
            $deviceToken = $d['device_token'];
            if ($d['device_type'] == "ios" && $deviceToken != "" && IOS_PEM_FILE != '') {
                $pushObj->send_direct($deviceToken, $push_verse,'', $latest_verse['data']['results'][0]['id']);
            } elseif ($d['device_type'] == "android" && GOOGLE_API_KEY_FOR_ANDROID_NOTIFICATION != '') {
                $pushAndroObj->LatestSendAndroidNotificaiton($deviceToken, $push_verse, GOOGLE_API_KEY_FOR_ANDROID_NOTIFICATION, $latest_verse['data']['results'][0]['id']);
            }
        }
        exit;
    }
    /* *** End of Notification code *** */    
}
?>