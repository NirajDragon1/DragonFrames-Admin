<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true ");
header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Depth, User-Agent, X-File-Size,	X-Requested-With, If-Modified-Since, X-File-Name, Cache-Control");
header("Content-type: application/x-www-form-urlencoded");

require_once 'system/config/configuration.php';  // include configuration file here


//require_once('../web_service/system/library/classes/dbclass.php');
require_once('../web_service/system/library/classes/database/class.pdohelper.php');
require_once('../web_service/system/library/classes/database/class.pdowrapper.php');


/********** settings variable *********/
set_include_path(implode(PATH_SEPARATOR, array(realpath(APPLICATION_ROOT_PATH . 'library'), get_include_path())));

$frontendOptions = array(
    'lifetime' => NULL, // cache lifetime of 2 hours
    'automatic_serialization' => true
);

$backendOptions = array(
    'cache_dir' => APPLICATION_ROOT_PATH."data".DS."cache".DS."settings" // Directory where to put the cache files
);

//require_once('../../library/NIC/Mobile/AndroidGCM.php');
//require_once('../../library/NIC/Mobile/Iphone.php');

class GeneralClass {

    protected $db;
    private $_cacheObj;
    public $currencies;
    public $settings;
    public $params = array();

    function __construct($settingObj, $zenddb = NULL) {
        $this->settings = $settingObj;
        
        if (isset($_POST['data'])) {
            $postData = json_decode($_POST['data']);
        } else {
            $postData = json_decode($_POST['data_']);
        }

        // Define DB level object
        //$this->db = new dbclass();
        $dbConfig = array("host"=>DB_SERVER,"dbname"=>DB_DATABASE,"username"=>DB_USERNAME,"password"=>DB_PASSWORD);
        $this->db = new PdoWrapper($dbConfig);
        $this->helper = new PDOHelper();
        $this->db->setErrorLog(true);


        $this->_initTranslate($postData->request->language_code);
    }

    /**
     * Translate initialise
     *
     */
    protected function _initTranslate($strLangCode = 'de') {

        @extract($this->getLanguageDetail($strLangCode));
        if (empty($language_name)) {
            $language_name = 'english';
        }
        //$dataArr = require_once('../../data/language/' . strtolower($language_name) . '.php');
        $dataArr = require_once('system/language/english.php');
        if (isset($dataArr) and !empty($dataArr)) {
            foreach ($dataArr as $key => $value) {
                define($key, $value);
            }
        }
    }

    /**
     * Fetch a template file
     *
     * @param string $template
     * @return string
     */
    public function fetchTemplate($template) {

        $strTemplate = TEMPLATE_PATH . $template;
        $template = file_get_contents($strTemplate);
        return ($template) ? $template : false;
    }

    /**
     * Translate
     *
     * @param unknown_type $TranslateText
     * @return unknown
     */
    protected function translate($TranslateText) {

        return !defined($TranslateText) ? $TranslateText : constant($TranslateText);
    }

    /**
     * function to check the user access for mobile application...
     * @param int $intUserId
     * @param string $strToken
     * @param int $intSchoolId
     * @return boolean
     */
    public function authorizationRights($intUserId, $strToken, $strDeviceId = NULL) {
       
    }

    /**
     * Function to get the language name based on language id passed in argument...
     * @param int $intLangId
     * @return array
     */
    protected function getLanguageDetail($strLangCode) {

       
    }

    protected function arrKeyRemove($arr, $keyValue) {
        $arrTemp = array();
        foreach ($arr as $key => $value) {
            unset($value[$keyValue]);
            $arrTemp[] = $value;
        }
        return $arrTemp;
    }

    protected function getUniqueCode($length = "") {
        $code = md5(uniqid(rand(), true));
        return ($length != "") ? substr($code, 0, $length) : $code;
    }

    function get_setting_value($name) {
       
    }
    
    public static function sendMails($to, $subject, $from_name, $from_mail, $content, $is_smtp = false){
        
     $config = array(
            'auth' => 'login',
            'username' => 'test.indianic1',
            'password' => 'indianic',
            'ssl' => 'ssl',
            'port' => 465
        );
 
        $transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
            $logo =  PUBLIC_URL_PATH.'front/img/logo.jpg'; 
            $replacevars = array("(!LOGO!)" => $logo);
            $contentheader = self::prepareMailBody("header",$replacevars);
          
            $social =  PUBLIC_URL_PATH.'front/img/social.jpg';
            $bottom =  PUBLIC_URL_PATH.'front/img/bottom.jpg';
            
          $IMPRESSUM = PUBLIC_URL_PATH . "front/page.php?page=impressum";  
          $WIDERRUFSRECHT =  PUBLIC_URL_PATH . "front/page.php?page=wiederrufsbelehrung";
          $HOME = PUBLIC_URL_PATH.'front';
          $AGB =  PUBLIC_URL_PATH . "front/page.php?page=agb";
          $UNS = PUBLIC_URL_PATH . "front/page.php?page=so_funktioniert_feedy";
            
            
            
            $replacevars = array("(!SOCIAL_IMG!)" => $social,"(!BOTTOM!)"=>$bottom,"(!IMPRESSUM!)" => $IMPRESSUM,"(!WIDERRUFSRECHT!)"=>$WIDERRUFSRECHT,"(!AGB!)" => $AGB,"(!UNS!)"=>$UNS,"(!HOME!)" => $HOME);
            $contentfooter = self::prepareMailBody("footer",$replacevars);
         
       
        $mail = new Zend_Mail('UTF-8');
      
       
        $header = $contentheader['body']; 
        $footer = $contentfooter['body'];
        $content = $header.$content.$footer;
      //  echo $contenthtml; exit;
        $mail->setBodyHtml($content);
     
        
        $mail->setFrom($from_mail, $from_name);
        
        $topart = explode(",", $to);
        foreach ($topart as $toreceipent){
            if(trim($toreceipent) != ""){
                $mail->addTo(trim($toreceipent));
            }
        }
        
        $mail->setSubject($subject);
        
        if($is_smtp == "true"){
            $mail->send($transport);
        }else{
            $mail->send();
        }        
    }
    
    public static function prepareMailBody($mailslug, $replacevars){
       
        $db = new dbclass();
        $result = $db->select("SELECT * FROM email_template where slug = '".$mailslug."'");
       
        $replacekey = array();
        $replaceval = array();
        if(!empty($replacevars)){
         
            foreach ($replacevars as $key => $value) { 
                array_push($replacekey, $key);
                array_push($replaceval, $value);
            }
           
            $body = str_replace($replacekey, $replaceval, $result[0]['content']);
            
        }else{
            
            $body = str_replace($replacekey, $replaceval, $result[0]['content']);
        }
       
        return array("body"=>$body,"subject"=>$result[0]['subject']);
        
    }
      public function responseArray($ResponseData) {
        return $ResponseData;
	}
    
    
}

