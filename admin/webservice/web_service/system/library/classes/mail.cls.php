<?php
////////////////////////////////////////////////////////////

// Class is used for interactiong with email functionality. 

// Example for simple mail //

// $mailObj->send();

// Example of template usage while sending mail  //

// $tmppath = MAIL_TMPL_PATH."mailforgetpassword.html";	 // template path
// $set_replace_var['name'] = $firstName; // replace variables
// $mailObj->send($tmppath,$set_replace_var);


// Example of template usage and file attachment while sending mail  //

// $tmppath = MAIL_TMPL_PATH."mailforgetpassword.html";	// template path
// $set_replace_var['name'] = $firstName; // replace variables
// $atchfilename = "feedy.pdf";
// $atchfilepath = FILE PATH;
// $mailObj->sendWithAttachment($atchfilename, $atchfilepath , $tmppath, $set_tmp_replace_var);

////////////////////////////////////////////////////////////

require_once('class.phpmailer.php');
class NicMail{
	
    var $to 		= null;
    var $from 		= null;
    var $cc 		= null;
    var $from_name 	= null;
    var $to_name 	= null;
    var $subject 	= null;
    var $headers 	= null;
	var $body 		= null;

	 // Define initial mail variables
     function __construct(){
     	//define('ADMINEMAIL',"admin@admin.com");
		//$this->to 		= ($this->to == '' ? ADMINEMAIL : null);
		//$this->from 	= ($this->from == '' ? ADMINEMAIL : null);
	    $this->subject  =  "";
        $this->body     =  "";
        $this->headers  =  "";
        $this->to       =  "";
        $this->from     =  "";
        $this->to_name     =  "";
        $this->from_name   =  "";
        $this->cc     =  "";
        
        
    }
	
    // Send template base/simple mail.
	function send($tmppath='', $set_tmp_replace_var=''){	    
		global $ADMINEMAIL;
		
		$this->addHeader('MIME-Version: 1.0' . "\r\n");
		$this->addHeader('Content-type:text/html;charset=iso-8859-1' . "\r\n");
      	if($this->to_name != NULL and $this->to_name != ""){
			$this->addHeader("To: ".$this->to_name." <".$this->to."> \r\n");
		}
		
		if($this->from_name != NULL and $this->from_name != ""){
			$this->addHeader("From: ".$this->from_name." <".$this->from."> \r\n");
		}else{
			$this->addHeader("From: ".$this->from."\r\n");
		}
		//$this->addHeader('From: '.$this->from."\r\n");
        $this->addHeader('Reply-To: '.$this->from."\r\n");
        //$this->addHeader('Return-Path: '.$this->from."\r\n");
       // $this->addHeader('nic-mailer: nicmail 1.0'."\r\n");
		if($this->cc != NULL){
			$this->addHeader('Cc: '.$this->cc."\r\n");
		}
	
        if($tmppath != '')
        	$this->getTemplate($tmppath ,$set_tmp_replace_var);
        
        //	echo $this->headers."</br>";
        return $this->sendMail();
    }


    // Send template base/simple mail with attachment.
	function sendWithAttachment($atchfilename, $atchfilepath , $tmppath='', $set_tmp_replace_var='') {
		
		if($tmppath != '')
        	$this->getTemplate($tmppath ,$set_tmp_replace_var);
        	
		$file = $atchfilepath.$atchfilename;
		$file_size = filesize($file);
		$handle = fopen($file, "r");
		$content = fread($handle, $file_size);
		fclose($handle);
		$content = chunk_split(base64_encode($content));
		$uid = md5(uniqid(time()));
		$name = basename($file);
		
		$this->addHeader("From: ".$this->from."\r\n");
		$this->addHeader("Reply-To: ".$this->from."\r\n");
		$this->addHeader("MIME-Version: 1.0\r\n");
		$this->addHeader("Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n");
		$this->addHeader("This is a multi-part message in MIME format.\r\n");
		$this->addHeader("--".$uid."\r\n");
		$this->addHeader("Content-type:text/html; charset=iso-8859-1\r\n");
		$this->addHeader("Content-Transfer-Encoding: 7bit\r\n\r\n");
		$this->addHeader($this->body."\r\n\r\n");
		$this->addHeader("--".$uid."\r\n");
		$this->addHeader("Content-Type: application/octet-stream; name=\"".$atchfilename."\"\r\n"); // use different content types here
		$this->addHeader("Content-Transfer-Encoding: base64\r\n");
		$this->addHeader("Content-Disposition: attachment; filename=\"".$atchfilename."\"\r\n\r\n");
		$this->addHeader($content."\r\n\r\n");
		$this->addHeader("--".$uid."--");
		
		return $this->sendMail(); // Final mail sending
		
	}
	
	// Sending mail finally
	function sendMail()	{
		
		if (mail($this->to, $this->subject, $this->body, $this->headers)) {
			return 1;
		} else {
			return 0;
		}
		
	}
	
	// Replace template variable with set variables
	function getTemplate($tmppath , $set_tmp_replace_var){
		
		$this->body  = file_get_contents($tmppath);
		
		if(count($set_tmp_replace_var) > 0){
			foreach ($set_tmp_replace_var as $key => $val){
				$this->body = str_replace("#$key#", $val, $this->body);
			}
		}
		
	}
	
	// Adding header
    function addHeader($header){
        $this->headers .= $header;
    }
    
    // Send template base/simple mail.
	function sendmandrilmail($toEmail='', $ssContent, $fromMail){		
		$mail                = new PHPMailer();

        $body                = $ssContent;
        $body                = eregi_replace("[\]",'',$body);
        
        $mail->IsSMTP(); // telling the class to use SMTP
        //$mail->Host          = "smtp.mandrillapp.com";
        $mail->SMTPAuth      = true;                  // enable SMTP authentication
        $mail->SMTPKeepAlive = true;                  // SMTP connection will not close after each email sent
        $mail->Host          = MANDRIL_HOST; // sets the SMTP server
        $mail->Port          = MANDRIL_PORT;                    // set the SMTP port for the GMAIL server
        $mail->Username      = MANDRIL_USERNAME; // SMTP account username
        $mail->Password      = MANDRIL_PASSWORD;        // SMTP account password
        
        if($fromMail != '')
            $mail->SetFrom($fromMail);
        else
            $mail->SetFrom('admin@meramsg.com');
        
        $mail->AddAddress($toEmail);
        $mail->Subject       = "Your Survey Response";
        
        $mail->MsgHTML($body);
		
		if(!$mail->Send()) 
		{
			//echo "Mailer Error: " . $mail->ErrorInfo;
		} else 
		{
			return true;
		}		
    }	
}
?>