<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта
$user_login = $_POST['login_rest'];

// Проверяем количество вхождений записи в таблице пользователей для изменения пароля - Должно быть 1 ()
$stmt = $pdo->prepare("SELECT COUNT(user_id) FROM users WHERE user_login = ?");
$stmt->execute(array($user_login ));
$user_id_count = $stmt->fetchColumn();

if($user_id_count > 0){	
	// Генерируем хэш
	$hash_restore = password_hash($user_login, PASSWORD_BCRYPT);

	// Записываем пару логин/хэш в таблицу восстановления паролей (Если нету записи - создаем - если есть - перезаписываем)
	$stmt = $pdo->prepare("SELECT COUNT(id) FROM pass_recovery WHERE recovery_login = ?");	
	$stmt->execute(array($user_login ));
	$user_recovery_count = $stmt->fetchColumn(); // Количество вхождений

	if($user_recovery_count == 0){
		$stmt = $pdo->prepare("INSERT INTO pass_recovery SET recovery_login = :recovery_login, recovery_hash = :recovery_hash");
	}else{
		$stmt = $pdo->prepare("UPDATE pass_recovery SET recovery_hash = :recovery_hash WHERE recovery_login = :recovery_login");
	}
	$stmt->execute(array(
		'recovery_login'=>$user_login,
		'recovery_hash'=>$hash_restore 
	));	
	// Формируем ссылку для пользователя	
	$server_adress = ($_SERVER['HTTPS']) ? "https://". $_SERVER['SERVER_NAME'] : "http://". $_SERVER['SERVER_NAME']; // Задаем адрес сервера с протоколом
	$recovery_url = $server_adress. "/Login/baselogin/prc.php?prh=".$hash_restore;
	
	// Отправляем ссылку на почту пользователю
	include_once($_SERVER['DOCUMENT_ROOT'].'/Assets/PHPMailer/PHPMailerFunction.php');
	
	$mail = $user_login; // $mail - Адрес получателя
	$subject = 'Восстановление пароля'; // $subject - Тема сообщения
	$message = "Для восстановление пароля перейдите по ссылке<br>\n\r";
	$message .= "<a href = '".$recovery_url."'>".$recovery_url."</a><br>\n\r";
	$message .= "и введите новый пароль."; // $message - Сообщение
	$sender_mail = 'Tsvetkov-SA@grmp.ru'; // $sender_mail - Почта отправителя
	$sender_name = 'Администратор'; // $sender_name - Имя отправителя

	SendMailGRMP($mail, $subject, $message, $sender_mail, $sender_name);

	// Возвращаем TRUE в скрипт
	echo "true";
}else{
	echo "false";
	// Возвращаем FALSE в скрипт
}
?>