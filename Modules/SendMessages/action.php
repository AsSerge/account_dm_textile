<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php");
$user_id = $_POST['user_id']; // Отправитель сообщения
$recipients_id = $_POST['recipients_user'];  // Получатель сообщения (ID)
$subject = $_POST['message_title']; // Заголовок сообщения
$message = $_POST['message_body']; // Тескст сообщения

// Фильтры
$subject = htmlspecialchars(strip_tags($subject));
$message = htmlspecialchars(strip_tags($message));

// Признак рассылки (0 - персональное сообщение, 1 - рассылка по фильтру, 2 - общая рассылка )
$isMailSanding = "0"; // В этом action нет рассылки

if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
	$query = $pdo->prepare("SELECT user_id, user_hash, user_name, user_surname, user_login FROM users WHERE user_id = '".intval($_COOKIE['id'])."' LIMIT 1");
	$query->execute();
	$userdata = $query->fetch(PDO::FETCH_LAZY);

	if(($user_id == intval($_COOKIE['id'])) and ($userdata['user_hash'] == $_COOKIE['hash']) and ($userdata['user_id'] == $_COOKIE['id'])){
		// Добавляем сообщение в базу

		$addtobase = $pdo->prepare("INSERT INTO messages SET sender_user_id = :sender_user_id, recipient_id = :recipient_id, message_type = :message_type, message_subject = :message_subject, message_text = :message_text");
		$addtobase->execute([
			'sender_user_id' => $user_id,
			'recipient_id' => $recipients_id,
			'message_type' => $isMailSanding,
			'message_subject' => $subject,
			'message_text' => $message
		]);

		// Формируем письмо и отправляю по адресу
		include_once($_SERVER['DOCUMENT_ROOT'].'/Assets/PHPMailer/PHPMailerFunction.php');

		// Получаем по $recipients_id почту получателя
		$smt = $pdo->prepare("SELECT user_login FROM users WHERE user_id = :user_id");
		$smt->execute(['user_id'=>$recipients_id]);
		$mail = $smt->fetch(PDO::FETCH_COLUMN); // Адрес получателя
		$sender_name = "{$userdata['user_name']} {$userdata['user_surname']}"; // Имя отправителя
		$sender_mail = $userdata['user_login']; // Почта отправителя
	
		// $mail - Адрес получателя
		// $subject - Тема сообщения
		// $message - Сообщение
		// $sender_mail - Почта отправителя
		// $sender_name - Имя отправителя

		// echo $mail, $subject, $message, $sender_mail, $sender_name;
		// SendMailGRMP($mail, $subject, $message, $sender_mail, $sender_name);

		echo "Сообщение отправлено";
	}else{
	
		echo "Ошибка";	
		// header("Location: /"); exit;
	}	
}else{	
	header("Location: ../Login/baselogin/login.php"); exit;
}
?>