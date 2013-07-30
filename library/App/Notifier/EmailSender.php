<?php

class App_Notifier_EmailSender 
{
    public function send($userEmail, $body, $subject)
    {
        $config = new Zend_Config_Ini('../application/configs/email.ini', 'production');
        $options = array( 
			'auth'     => $config->email->email_auth, 
			'username' => $config->email->email_username, 
			'password' => $config->email->email_password, 
			'ssl'      => $config->email->email_ssl, 
			'port' => $config->email->email_port 
		);

        $mailTransport = new Zend_Mail_Transport_Smtp($config->email->email_server, $options);
        Zend_Mail::setDefaultTransport($mailTransport);

        $mail = new Zend_Mail();
        $mail->addTo($userEmail);
        $mail->setFrom($config->email->email_from, $config->email->email_from);
        $mail->setSubject($subject);
        $mail->setBodyHtml($body);
        $mail->send();
    }
    
}