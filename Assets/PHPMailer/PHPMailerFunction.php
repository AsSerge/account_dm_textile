<?php
//Функция отправки почты через сервер grmp
function SendMailGRMP($mail, $subject, $message, $sender_mail, $sender_name){
	$__smtp = array(
		"host" => 'mail.grmp.ru', // SMTP сервер
		"debug" => 0, // Уровень логирования
		"auth" => true, // Авторизация на сервере SMTP. Если ее нет - false
		"port" => '465', // Порт SMTP сервера
		"username" => 'm_help_shop', // Логин запрашиваемый при авторизации на SMTP сервере
		"usermail" => $sender_mail,  // Почта отправителя
		"password" => 'Kkkesmdjn4', // Пароль
		"addreply" => $sender_mail, // Почта для ответа
		"secure" => 'ssl', // Тип шифрования. Например ssl или tls
		"mail_title" => 'Акция', // Заголовок письма
		"mail_name" => $sender_name // Имя отправителя
	);	
	require_once ($_SERVER['DOCUMENT_ROOT'].'/Assets/PHPMailer/PHPMailerAutoload.php');
	
	$SendAdress = array(
		'name'=>$name,
		'mail'=>$mail,
		'subject'=>$subject,
		'message'=>$message
	);
	try{
		spl_autoload_register();
		$mail = new PHPMailer(true); // Создаем экземпляр класса PHPMailer

		$mail->IsSMTP(); // Указываем режим работы с SMTP сервером
		$mail->Host       = $__smtp['host'];  // Host SMTP сервера: ip или доменное имя
		$mail->SMTPDebug  = $__smtp['debug'];  // Уровень журнализации работы SMTP клиента PHPMailer
		$mail->SMTPAuth   = $__smtp['auth'];  // Наличие авторизации на SMTP сервере
		$mail->Port       = $__smtp['port'];  // Порт SMTP сервера
		$mail->SMTPSecure = $__smtp['secure'];  // Тип шифрования. Например ssl или tls 
		$mail->CharSet="UTF-8"; // Кодировка обмена сообщениями с SMTP сервером 
		$mail->Username = $__smtp['username']; // Имя пользователя на SMTP сервере 
		$mail->Password = $__smtp['password']; // Пароль от учетной записи на SMTP сервере    

		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);

		/* Прикрепление вложения к письму */

		$mail->AddAddress($SendAdress['mail'], $SendAdress['name']); // Адресат почтового сообщения 
		$mail->AddReplyTo($__smtp['addreply'], $__smtp['mail_name']) ; // Альтернативный адрес для ответа 
		$mail->SetFrom($__smtp['usermail'], $__smtp['mail_name']); // Адресант почтового сообщения
		$mail->Subject = htmlspecialchars($SendAdress['subject']); // Тема письма
		$mail->MsgHTML($SendAdress['message']); // Текст сообщения
		$mail->Send();
	//    return 1;    
	} 
	catch (phpmailerException $e){
	// return $e->errorMessage();
	}
}


//Функция отправки почты через сервер GRMP С ВЛОЖЕНИЕМ!!!!!
function SendMailGRMPAttachment($mail, $subject, $message, $sender_mail, $sender_name, $attachment){
	$__smtp = array(
		"host" => 'mail.grmp.ru', // SMTP сервер
		"debug" => 0, // Уровень логирования
		"auth" => true, // Авторизация на сервере SMTP. Если ее нет - false
		"port" => '465', // Порт SMTP сервера
		"username" => 'm_help_shop', // Логин запрашиваемый при авторизации на SMTP сервере
		"usermail" => $sender_mail,  // Почта отправителя
		"password" => 'Kkkesmdjn4', // Пароль
		"addreply" => $sender_mail, // Почта для ответа
		"secure" => 'ssl', // Тип шифрования. Например ssl или tls
		"mail_title" => 'Акция', // Заголовок письма
		"mail_name" => $sender_name // Имя отправителя
	);	
	require_once ($_SERVER['DOCUMENT_ROOT'].'/Assets/PHPMailer/PHPMailerAutoload.php');
	
	$SendAdress = array(
		'name'=>$name,
		'mail'=>$mail,
		'subject'=>$subject,
		'message'=>$message,
		"attachment" => $attachment // Вложенный файл
	);
	try{
		spl_autoload_register();
		$mail = new PHPMailer(true); // Создаем экземпляр класса PHPMailer

		$mail->IsSMTP(); // Указываем режим работы с SMTP сервером
		$mail->Host       = $__smtp['host'];  // Host SMTP сервера: ip или доменное имя
		$mail->SMTPDebug  = $__smtp['debug'];  // Уровень журнализации работы SMTP клиента PHPMailer
		$mail->SMTPAuth   = $__smtp['auth'];  // Наличие авторизации на SMTP сервере
		$mail->Port       = $__smtp['port'];  // Порт SMTP сервера
		$mail->SMTPSecure = $__smtp['secure'];  // Тип шифрования. Например ssl или tls 
		$mail->CharSet="UTF-8"; // Кодировка обмена сообщениями с SMTP сервером 
		$mail->Username = $__smtp['username']; // Имя пользователя на SMTP сервере 
		$mail->Password = $__smtp['password']; // Пароль от учетной записи на SMTP сервере    

		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);

		/* Прикрепление вложения к письму */
		$mail->AddAddress($SendAdress['mail'], $SendAdress['name']); // Адресат почтового сообщения 
		$mail->AddReplyTo($__smtp['addreply'], $__smtp['mail_name']) ; // Альтернативный адрес для ответа 
		$mail->SetFrom($__smtp['usermail'], $__smtp['mail_name']); // Адресант почтового сообщения
		$mail->Subject = htmlspecialchars($SendAdress['subject']); // Тема письма
		$mail->MsgHTML($SendAdress['message']); // Текст сообщения
		// $mail->addAttachment($_SERVER['DOCUMENT_ROOT'].'/uploaded_documents/'.$attachment);
		$mail->addAttachment($attachment);
		$mail->Send();
	//    return 1;    
	} 
	catch (phpmailerException $e){
	// return $e->errorMessage();
	}
}
?>