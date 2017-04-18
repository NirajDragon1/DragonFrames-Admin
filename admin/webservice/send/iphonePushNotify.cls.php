<?php
//iphonePushNotify.cls
//////////////////////////////////////////////////////////
 
# This Class will be use to send push notification to iPhone device.

//////////////////////////////////////////////////////////

class NicIphonePushNotify {

	private $apnPath;
	private $authId;
	function __construct($apnPath=''){
		if(!empty($apnPath)){
			$this->apnPath=$apnPath;
		} else {
			$this->apnPath=$_SERVER['DOCUMENT_ROOT'].'/pircleme/web_services/PircleMe_APNS_Development.pem';
		}
	}
	///STACK FLOW EXAMPLE CODE
	function authenticate() {}
	function sendMessageToPhone($device_token, $message) {
 		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert',$this->apnPath);		
		$fp = stream_socket_client("ssl://gateway.push.apple.com:2195", $error, $errorString, 60, STREAM_CLIENT_CONNECT, $ctx);

		if(!$fp){}
		else
		{
			$message="{\"aps\": {\"badge\":1,\"sound\":\"deault\",\"alert\": \" ".$message.".\"}}";
			$msg = chr(0).pack("n",32).pack('H*',$device_token).pack("n",strlen($message)).$message;
			$fwrite = fwrite($fp, $msg);
			fclose($fp);
		}

	}

	public function send_direct($tokenid,$message,$title='', $verse_id = 0){
		
		
		#!/usr/bin/env php
		$deviceToken = $tokenid;  // masked for security reason
		
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $this->apnPath);
		// assume the private key passphase was removed.
		//stream_context_set_option($ctx, 'ssl', 'passphrase', 'indianic');

                $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 3600, STREAM_CLIENT_CONNECT, $ctx);
		                
                $message="{\"aps\": {\"badge\":1,\"sound\":\"default\",\"alert\": \" ".$message.".\",\"title\": \" ".$title.".\"}, \"verse_id\": $verse_id}";
                $msg = chr(0).pack("n",32).pack('H*',$deviceToken).pack("n",strlen($message)).$message;
                $fwrite = fwrite($fp, $msg);
                
                //echo '<pre>';print_r($fwrite);die;

                
		fclose($fp);
                
	}

}
