<?php


/*****************************************
 * start outright_send_email
 *      @description  : Sent Email 
 *      @Author       : Outright 
 *      Date          : 
 *      @param   string    ---> $to   : Recipient Email 
 *      @param   string    ---> $email_subject : Subject of the email 
 *      @param   string    ---> $email_body    : Message of the email 
 *      @since 1.0.0
 *          
 * ***************************************/
 

function outright_send_email($to,$email_subject,$email_body){
	
    require_once('include/SugarPHPMailer.php');
	$emailObj = new Email(); 
	$defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer(); 
	$mail->setMailerForSystem(); 
	$mail->From     = $defaults['email']; 
	$mail->FromName = $defaults['name']; 
	$mail->isHTML(true);
	$mail->Subject  = $email_subject; 
	$mail->Body     = $email_body;
	$mail->prepForOutbound(); 
	$mail->AddAddress($to);
	$mail->Send();
	$return = array();
	if(!$mail->Send()) {
		ob_clean();
		$return['status'] = false;
		$return['errorMessage'] =  $mail->ErrorInfo;
		return $return;
	} // if
	$return['status'] = true;
	return $return;
}/**End outright_send_email**/


/*****************************************
 * 
 *     @description : Update inbound email value of outlook.office365
 *     @Author      : Outright 
 *     Date         :  
 *     @param     string --->$email_address : Email address id                                                                
 *     @since 1.0.0
 * 
 * ***************************************/
 
function outright_create_update_inbound($email_address){
	  $ie = new InboundEmail();
	  $qry_fetch = "SELECT id FROM inbound_email WHERE email_user  = '".$email_address."' AND deleted=0";
	  $qry_fetch1 = $db->query($qry_fetch);

	   if($qry_fetch1->num_rows>0){
		   $res = $db->fetchByAssoc($qry_fetch1);
		   $ie->retrieve($res['id']);
	   }else{
			$ie->id = create_guid();
			$ie->new_with_id = true;
			$ie->name = $bean->email_address;
			$ie->status = 'Active';
			$ie->server_url = 'outlook.office365.com';
			$ie->port = '993';
			$ie->service = '::::::imap::novalidate-cert::notls::';
			$ie->mailbox = 'INBOX';
			$ie->mailbox_type= 'pick';
			$ie->is_personal = '1';
			$ie->email_user = $bean->email_address;
		 }
			$ie->email_password = $bean->email_password;
			$ie->group_id = $current_user->id;
			$ie->save();
}

/*****************************************
 * 
 *     @description : Update outbound email value of outlook.office365
 *     @Author      : Outright 
 *     Date         :  
 *     @param     string --->$email_address : Email address id                                                                
 *     @param     string --->$email_pass    : email password                                                                
 *     @param     obj    --->$bean          : parent bean object                                                               
 *     @since 1.0.0
 * 
 * ***************************************/

function outright_create_update_outbound($email_address,$email_pass,$bean){
		
		$qry_fetch = "SELECT id FROM outbound_email WHERE mail_smtpuser  = '".$email_address."' ";

		$outbound_found = outright_run_sql_one_row($qry_fetch);
		$emailSettings  = new OutboundEmail();
		if($outbound_found){
		  $emailSettings->retrieve($outbound_found['id']);
		}
		else{
		 $outbound_id = create_guid();
		 $emailSettings->new_with_id =1;
		 $emailSettings->id =$outbound_id;
		}
		$emailSettings->mail_smtpserver = 'smtp.office365.com';
		$emailSettings->mail_smtpport   = 587;
		$emailSettings->mail_smtpuser   = $email_address;
		$emailSettings->mail_smtppass   = $email_pass;
		$emailSettings->original_pass   =$email_pass;
		$emailSettings->mail_smtpauth_req = 1;
		$emailSettings->mail_smtpssl = 2;
		$emailSettings->type ='user';
		$emailSettings->name = $email_address;
		//$emailSettings->user_id = $current_user->id;
		$emailSettings->mail_sEndtype = 'SMTP';
		$emailSettings->user_id = $bean->id;
		$emailSettings->save();
}