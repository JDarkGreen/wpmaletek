<?php 
function assingParamMail($oMail,$plugin,$fromMail,$fromName){
	$oMail->PluginDir 		= $plugin;
	//$oMail->Mailer 			= "smtp";
	$oMail->Mailer 			= "mail";
	// Agregado---------------------------------------------
	//$oMail->Helo 			= "www.tigertech.net";
	//$oMail->Port 			= 587;
	//------------------------------------------------------
	$oMail->Host 			= MAILHOST;
	$oMail->SMTPAuth 		= true;
	$oMail->Username 		= USER_MAIL; 
	$oMail->Password 		= PASSWD_MAIL;
	$oMail->From 			= $fromMail;
	$oMail->FromName 		= $fromName;
	$oMail->Timeout			= 30;
	$oMail->IsHTML(true);
}
?>