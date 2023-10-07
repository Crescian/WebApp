<?php

include 'mail_setting.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
	$email_address = 'manalo_justine24@yahoo.com';
	$mail->SMTPDebug = SMTP::DEBUG_SERVER;
	$mail->isSMTP();

	$mail->Host       	= $mail_host;
	$mail->Port       	= $mail_port;
	$mail->SMTPAuth   	= $mail_smtpauth;
	$mail->SMTPSecure 	= $mail_smtpsecure;
	$mail->Username   	= $mail_username;
	$mail->Password   	= $mail_password;
	$mail->From 		= $mail_from;
	$mail->FromName 	= $mail_fromname;

	$mail->addAddress($email_address);

	$mail->isHTML(true);
	$mail->Subject = 'Email Verification';
	$mail->Body    = '
	<div style="box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);transition: 0.3s;width: 60%;border-radius: 5px;">
		<div style="padding: 25px 16px;">
			<p style="font-family:verdana;font-size:30px;">Verify your e-mail to finish signing up</p>
			<p style="font-family:verdana;font-size:15px;">Thank you for choosing Banner Plasticard Inc.</p>
			<p style="font-family:verdana;font-size:15px;">Please confirm that <b>manalo_justine24@yahoo.com</b> is your e-mail address by clicking on the button below or use this link <a href="http://localhost/BannerApplicant/email_verification.php">http://localhost/BannerApplicant/email_verification.php</a></p>
			<div style="display: flex;justify-content: center;align-items: center;">
				<button type="button" style="border:none;color:white;padding:15px 32px;text-align:center;text-decoration: none;display: inline-block;font-size: 16px;cursor: pointer;background-color: #4CAF50;width: 75%;">Verify</button>
			</div>
		</div>
	</div>';

	$mail->send();
	echo 'Message has been sent';
} catch (Exception $e) {
	echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
