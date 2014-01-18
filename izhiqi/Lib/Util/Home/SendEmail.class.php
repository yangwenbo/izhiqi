<?php
/**
 * 发送邮件类
 */
class SendEmail{
	/**
	 * 发送简单邮件 单邮件
	 * @param string $mailto 收件人
	 * @param string $subject 标题
	 * @param string $body 正文支持html
	 */
	static public function send_simple_mail($tomail, $subject, $body){

		Vendor('Swift.swift_required');

		$smtpInfo = C('SMTP_SERVER');
		if (!$tomail || !$smtpInfo) {
			return false;
		}
		$ms = 0;
		try{
			$transport = Swift_SmtpTransport::newInstance($smtpInfo['server'], $smtpInfo['port'])
						->setUsername($smtpInfo['user'])
	  					->setPassword($smtpInfo['password']);
			
			$mailer = Swift_Mailer::newInstance($transport);
			
			$sendTo = array($tomail=>$tomail);
			
			$message = Swift_Message::newInstance($subject)
			->setCharset('UTF-8')
			->setFrom(array($smtpInfo['server'] => $smtpInfo['displayname']))
			->setTo( $sendTo )
			->setBody($body, 'text/html', 'UTF-8')
			;
			$numSent = $mailer->send($message);
			$ms = 1;
		}
		catch(Exception $e){
			$ms = 0;
		}
// 		Loghelper::sendMail($tomail, $subject, $body, $ms);
		return $ms;
	}
}