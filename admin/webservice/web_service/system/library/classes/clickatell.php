<?php
/**
 * Simple PHP ClickATell Library
 * 
 * This library will send text message 
 * using ClickATell's Service
 * 
 * @author https://github.com/omarusman
 *
 */
class ClickATell
{
	private $clickatell_gateway;
	private $api_id;
	private $user;
	private $password;
	private $session_id;
	private $error = '';
	
	/**
	 * Add your ClickATell Credentials here
	 */
	public function __construct($api_id = '', $user = '', $password = '')
	{
		$this->clickatell_gateway = 'http://api.clickatell.com/http';
		$this->api_id = $api_id;
		$this->user = $user;
		$this->password = $password;
		$this->error = '';
	}
	
	/**
	 * Get session_id
	 * @return string|boolean
	 */
	public function get_session_id()
	{
		//If has session_id already
		if( $this->session_id )
		{
			//Ping the session_id
			$ch = curl_init();
			
			$url = $this->clickatell_gateway . '/ping?session_id='. $this->session_id;
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			curl_close($ch);
			
			if( strstr($output, 'OK:') )
			{
				return $this->session_id;
			}
		}
		
		//Get new session_id
		$ch = curl_init();
		
		$url = $this->clickatell_gateway . '/auth?api_id='. $this->api_id .'&user='. $this->user .'&password='. $this->password;
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		if( strstr($output, 'OK: ') )
		{
			$this->session_id = trim(str_replace('OK: ', '', $output));
			return $this->session_id;
		}
		$this->error = trim(str_replace('ERR: ', '', $output));
		//$output = ERR: Error number, error description 
		return false;
	}
	
	/**
	 * Send text message
	 * @param string $to
	 * @param string $msg
	 * @return string|boolean
	 */
	public function send($to = '', $msg = '')
	{
		$session_id = $this->get_session_id();
		if( $session_id )
		{
			$session_id = trim($session_id); // remove any whitespace
	        $url = "$this->clickatell_gateway/sendmsg?session_id=$session_id&to=$to&text=$msg";
	 
	        // do sendmsg call
	        $ret = file($url);
	        $send = explode(":",$ret[0]);
	        if ($send[0] == "ID") {
	            return array("status" => 0, "message" => $send[1]);
	        }
			return array("status" => 1, "message" => $send[1]);
		}
		
		//$output = ERR: Error number, error description 
		return array("status" => 1, "message" => $this->error);
	}
}
#END OF PHP FILE