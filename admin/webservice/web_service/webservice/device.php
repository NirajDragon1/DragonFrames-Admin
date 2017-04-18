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

    /**
     * Register a device ws
     * 
     * @param type $requestParams
     * @return type
     */
    public function register_device($requestParams = NULL) {
        
        $responseArr = array("status" => "Fail", 'data' => array(), "message" => 'Unable to register a device.');
        
        $token      = isset($requestParams['device_token']) ? $requestParams['device_token'] : '';
        $device_id  = isset($requestParams['device_id']) ? $requestParams['device_id'] : '';
        $device_type= isset($requestParams['device_type']) ? $requestParams['device_type'] : '';
        
        if($token != '' && $device_id != '' && $device_type != ''){
            $chkDevice = $this->db->select('devices', array('id'), array('device_token' => $token, 'device_id' => $device_id, 'device_type' => $device_type))->result();
            if(empty($chkDevice)){
                $this->db->insert('devices', array('device_type'=>$device_type, 'device_token' => $token, 'device_id' => $device_id, 'created_date' => date('Y-m-d H:i:s')));
                $responseArr = array("status" => "Success", 'data' => array('registered' => true, 'exists' => false), "message" => '');
            }else{
                $responseArr = array("status" => "Success", 'data' => array('registered' => true, 'exists' => true), "message" => '');            
            }
        }
        return $responseArr;
    }

}
?>
