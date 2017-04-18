<?php
ob_start();
session_start();

require_once('GeneralClass.php');

//GeneralClass::sendMails("", "hello", "Sameer", "vishal.kacha@feedy.com", "This is test",array(),$objSettingsCache['IS_SMTP']);

//require_once APPLICATION_ROOT_PATH."library".DS."functions".DS."GeneralFunctions.php";

$path = APPLICATION_ROOT_PATH."public".DS."web_service".DS."lib".DS."php".DS;
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

if (isset($_POST['data'])) {

    $postdata = json_decode($_POST['data']);
} else {
    $postdata = json_decode($_POST['data_']);
}


$arrPostData = $postdata;
$arrRequestedData = $arrPostData->request;
$strServiceName = $arrPostData->service;
$strMethodName = $arrPostData->method;
$strJsonResponseData = '';

//variables declaration [end]..
//require_once(LIBRARY_PATH.'NIC/Mobile/AndroidGCM.php');
//require_once(LIBRARY_PATH.'NIC/Mobile/Iphone.php');
//echo '<pre>';



if (file_exists("webservice/" . $strServiceName . ".php")) {
    require_once("webservice/" . $strServiceName . ".php");
    $objWebService = new WebService($objSettingsCache, $zendb);
} else {

    $strJsonResponseData = array("webservice_services_link" => array(
            "response" => array(),
            "service" => $strServiceName,
            "method" => $strMethodName,
            "action" => $arrPostData->action,
            "serviceStatus" => array(
                "status" => 0,
                "message" => "Invalid WS request call."
            )
    ));
}




//$sampleData = array("username"=>"nilesh.gosai@feedy.com", "password"=>"feedy");
//$objUser->signin((object)$sampleData);
if (method_exists($objWebService, $strMethodName)) {

    $strJsonResponseData = $objWebService->$strMethodName((array) $arrRequestedData);
}


echo indent(json_encode($strJsonResponseData));

if (!isset($arrRequestedData->user_id)) {
    $user_id = 0;
} else {
    $user_id = $arrRequestedData->user_id;
}

$db = new dbclass();
//$insert = $db->insert("INSERT INTO ws_log(user_id,request_origin,device_type,request_data,response_data) values($user_id,'http://" . $_SERVER['REMOTE_ADDR'] . $_SERVER['REQUEST_URI'] . "','" . getDeviceType() . "','" . mysql_real_escape_string(indent(json_encode($postdata))) . "','" . mysql_real_escape_string(indent(json_encode($strJsonResponseData))) . "')");

/**
 * Indents a flat JSON string to make it more human-readable.
 *
 * @param string $json The original JSON string to process.
 *
 * @return string Indented version of the original JSON string.
 */
function indent($json) {

    $result = '';
    $pos = 0;
    $strLen = strlen($json);
    $indentStr = '  ';
    $newLine = "\n";
    $prevChar = '';
    $outOfQuotes = true;

    for ($i = 0; $i <= $strLen; $i++) {

        // Grab the next character in the string.
        $char = substr($json, $i, 1);

        // Are we inside a quoted string?
        if ($char == '"' && $prevChar != '\\') {
            $outOfQuotes = !$outOfQuotes;

            // If this character is the end of an element, 
            // output a new line and indent the next line.
        } else if (($char == '}' || $char == ']') && $outOfQuotes) {
            $result .= $newLine;
            $pos--;
            for ($j = 0; $j < $pos; $j++) {
                $result .= $indentStr;
            }
        }

        // Add the character to the result string.
        $result .= $char;

        // If the last character was the beginning of an element, 
        // output a new line and indent the next line.
        if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
            $result .= $newLine;
            if ($char == '{' || $char == '[') {
                $pos++;
            }

            for ($j = 0; $j < $pos; $j++) {
                $result .= $indentStr;
            }
        }

        $prevChar = $char;
    }

    return $result;
}

?>
