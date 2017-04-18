<?php

//////////////////////////////////////
//GENERAL SETTINGS/
//////////////////////////////////////
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);   // display all errors except notices
@ini_set('display_errors', '1');   // display all errors
@ini_set('register_globals', 'Off'); // make globals off runtime
@ini_set('magic_quotes_runtime', 'Off');
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
define('IOS_PEM_FILE','apns-dist.pem');
define('GOOGLE_API_KEY_FOR_ANDROID_NOTIFICATION', 'AIzaSyARSYaG7feaxv5-Ka7sI5W1hZ4PNIS92LI');

/////////////////////////////////////////
// SITE CONFIGURATION/
/////////////////////////////////////////

$path_http1 = pathinfo('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
$arrDirPath1 = explode("/", $path_http1["dirname"]);
$serverPath1 = $arrDirPath1;
define("SERVER_ROOT_PATH_NEW", substr(getcwd(), 0, (strlen(getcwd()) - strlen($arrDirPath1[count($arrDirPath1) - 1]))));
array_pop($serverPath1);
$serverUrl1 = implode("/", $serverPath1);
define("SERVER_URL_PATH", $serverUrl1 . "/");
define("SERVER_PATH_NEW", $serverUrl1 . "/");


$path_http = pathinfo('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
//define("SERVER_PATH", $path_http["dirname"]."/"); 								// server path is deined here
$arrDirPath = explode("/", $path_http["dirname"]);
if ($arrDirPath[count($arrDirPath) - 1] == "admin") {
    define("SERVER_ROOT_PATH", substr(getcwd(), 0, (strlen(getcwd()) - strlen($arrDirPath[count($arrDirPath) - 1])))); // server root path is deined here
    $serverPath = $arrDirPath;
    array_pop($serverPath);
    $serverUrl = implode("/", $serverPath);
    define("SERVER_PATH", $serverUrl . "/");         // server path is deined here
} else {
    define("SERVER_ROOT_PATH", getcwd() . DS);       // server root path is deined here
    $serverUrl = implode("/", $arrDirPath);
    define("SERVER_PATH", $serverUrl . "/");         // server path is deined here
    $path_https = pathinfo('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}

$path_https = pathinfo('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);

define("SERVER_SSL_PATH", $path_https["dirname"] . "/");  // server https path is deined here


$serverparts = explode("/", SERVER_PATH);
array_pop($serverparts);
array_pop($serverparts);
defined('PUBLIC_URL_PATH') ? null : define('PUBLIC_URL_PATH', implode("/", $serverparts) . "/");


//////////////////////////////////////////////
// DATABASE CONFIGURATION/
//////////////////////////////////////////////
if ($_SERVER['HTTP_HOST'] == "localhost") {
    define("DB_SERVER", "localhost");     // server name set here
    define("DB_USERNAME", "root");      // server username set here
    define("DB_PASSWORD", "");     // server password set here
    define("DB_DATABASE", "usbible");     // server database set here
} else {
    define("DB_SERVER", "10.2.1.68");     // server name set here
    define("DB_USERNAME", "usbible");      // server username set here
    define("DB_PASSWORD", "usbible");     // server password set here
    define("DB_DATABASE", "usbible");     // server database set here
}
///////////////////////////////////////////////////
// ALL SITE VARIABLE SET HERE		   	        //
///////////////////////////////////////////////////
define("ADMIN_PATH", SERVER_ROOT_PATH . 'admin/');      // admin path set here
define("CLASS_PATH", SERVER_ROOT_PATH . 'system/library/classes/');  // class path set here
define("SCRIPT_PATH", SERVER_ROOT_PATH . 'scripts/');      // script path set here	
define("JAVASCRIPT_PATH", SERVER_PATH . 'js/');       // java script path set here	
define("FUNCTION_PATH", SERVER_ROOT_PATH . 'system/library/functions/'); // function script path set here	
define("STYLE_PATH", SERVER_PATH . 'css/');        // style path set here
define("CONFIG_PATH", SERVER_ROOT_PATH . 'system/config/');    // config path set here
define("IMG_FRONT_URL", SERVER_PATH . 'img/');    // Image url
define("IMG_FRONT_PATH", SERVER_ROOT_PATH . 'img' . DS);    // Image path
define("ZEND_APPLICATION_PATH", SERVER_ROOT_PATH . ".." . DS . ".." . DS . "application" . DS);      // ZEND Application Path
define("ZEND_DATA_PATH", SERVER_ROOT_PATH . ".." . DS . ".." . DS . "data" . DS);      // ZEND Data Path
define("APPLICATION_ROOT_PATH", SERVER_ROOT_PATH . ".." . DS . ".." . DS);      // APPLICATION root path
define("PUBLIC_ROOT_PATH", SERVER_ROOT_PATH . ".." . DS);      // public root path

define("API_URL", PUBLIC_URL_PATH . "web_service/client.php");      // APPLICATION root path
define('ROOT_PATH_EMAIL_CONTENT', SERVER_ROOT_PATH . 'mailTemplates' . DIRECTORY_SEPARATOR);
