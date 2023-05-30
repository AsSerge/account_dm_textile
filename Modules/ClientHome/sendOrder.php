<?php
//******************************************/
// Отправка подготовленного ордера с файлом
//******************************************/
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php");
if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{

	$query = $pdo->prepare("SELECT * FROM users AS U LEFT JOIN user_teams AS UT ON (U.user_team = UT.team_id) WHERE U.user_id = '".intval($_COOKIE['id'])."'");

	$query->execute();
	$userdata = $query->fetch(PDO::FETCH_LAZY);

	$user_id = $userdata['user_id']; // Роль пользователя
	$user_login = $userdata['user_login']; // Роль пользователя
	$user_role = $userdata['user_role']; // Роль пользователя
	$user_name = $userdata['user_name']; // Имя пользователя
	$user_surname = $userdata['user_surname']; // Фамилия пользователя
	$user_team_id = $userdata['user_team']; // Имя Комманды
	$user_team_name = $userdata['team_name']; // Имя Комманды
	$user_leader = $userdata['user_leader']; // ID литера команды
	

	switch ($user_role){
		case "adm":
			$user_role_description = "Администратор";
			break;
		case "mgr":
			$user_role_description = "Менеджер";
			break;
		case "kln":
			$user_role_description = "Клиент";
			break;
	}
	
	if(($userdata['user_hash'] !== $_COOKIE['hash']) and ($userdata['user_id'] !== $_COOKIE['id']))
	{
		setcookie("id", "", time() - 3600*24*30*12, "/");
		setcookie("hash", "", time() - 3600*24*30*12, "/", null, null, true); // httponly !!!
		
		// Переадресовываем браузер на страницу логирования
		header("Location: /"); exit;
	}else{


		// Функция очистки строки
		function ClearMessageString($string){
			$eitem = array('strong','lt','gt','sub','&','amp');
			$eitemAllowed = array("\r\n", "\n", "\r");
			$string = htmlentities(htmlspecialchars($string));
			$string = str_replace($eitem, "", $string);
			$string = str_replace($eitemAllowed, "<br>", $string);
			$string = trim($string);
			return $string;
		}

		// Получаем почтовый адрес для отправки заявки: team_manager_mail
		$stm = $pdo->prepare("SELECT team_manager_mail FROM users WHERE user_id = :user_id");
		$stm->execute([
			'user_id' => $user_leader
		]);
		$team_manager_mail = $stm->fetch(PDO::FETCH_COLUMN); // Почтивый адрес тимледера


		// Получаем информацию по загруженному файлу для формирования тела письма
		$message_body = ClearMessageString($_POST['message_body']);// Чистим полученную от пользователя информацию

		switch ($_POST['file_description']){

			case "1": $order_type = "ОПТ_1"; break;
			case "2": $order_type = "ОПТ_2"; break;
			case "3": $order_type = "ОПТ_3"; break;
			case "4": $order_type = "ОПТ_4"; break;
			case "5": $order_type = "ПРОЧИЕ_ОПТ_1"; break;
			case "6": $order_type = "ПРОЧИЕ_ОПТ_2"; break;
			case "7": $order_type = "ПРОЧИЕ_ОПТ_3"; break;
			case "8": $order_type = "ПРОЧИЕ_ОПТ_4"; break;
			case "9": $order_type = "УЦЕНКА"; break;
		}

		// Получаем файл и закидываем его в каталог пользователя
		// Определяем реальное имя файла (от пользователя)
		$filename = $_FILES['upload_file']['name']; // Реальное имя файла

		// Определяем - существует ли папка пользователя - если нет - создаем
		$user_dir = $_SERVER['DOCUMENT_ROOT'].'/uploaded_documents/' . $user_id;
		if (!is_dir($user_dir)){
			mkdir($user_dir, 0777, true);
		}

		// Формирование ключа ордера и имени файла для сохренения на сервере и отправке по почте (ORDER - файл заказа)
		$order_key = time();
		$file_prefix = $order_key."_".$order_type."_ORDER_";
		$file_in =  $file_prefix. $filename;
		$target_file =  $user_dir . "/" . $file_in; // Результирующий файл
		$access_line = password_hash(time(), PASSWORD_BCRYPT); // Формируем строку доступа к файлу из почты
		
		move_uploaded_file($_FILES['upload_file']['tmp_name'], $target_file); // Кладем нужный файл в папку клиента

		// Добавляем Ордер в базу orders [order_id, order_date, order_hash, user_id, file_name] 

			$stm = $pdo->prepare("INSERT INTO orders SET order_key = :order_key, order_type = :order_type, order_hash = :order_hash, user_id = :user_id, file_name = :file_name");
			$stm->execute([
				'order_key' => $order_key,
				'order_type' => $order_type,
				'order_hash' => $access_line,
				'user_id' => $user_id,
				'file_name' => $file_in
			]);

		// Добавляем Статус	Ордера в базу Статусов (Первоначальный статус Ордера = 0 - Новый)
			$lastInsertId = $pdo->lastInsertId(); // ID последней записи
			$stm = $pdo->prepare("INSERT INTO orders_states SET order_id = :order_id,  state_reason = :state_reason, state_type = 0");
			$stm->execute([
				'order_id' => $lastInsertId,
				'state_reason' => $message_body
			]);

		include_once($_SERVER['DOCUMENT_ROOT'].'/Assets/PHPMailer/PHPMailerFunction.php');	

		/* Отправка заказа на почту логисту **********************************************************************************************************************/
	
		// $mail - Адрес получателя
		// $subject - Тема сообщения
		// $message - Сообщение
		// $sender_mail - Почта отправителя
		// $sender_name - Имя отправителя

		include_once($_SERVER['DOCUMENT_ROOT'].'/Layout/engineering.php'); // Блок тестирования
		$mail = ($testing_mode) ? $tester_mail : $team_manager_mail;
		// $mail = 'Tsvetkov-SA@grmp.ru';
		// $mail = $team_manager_mail; // Общая почта подргуппы для отпрвки заявки !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! Заменить!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		$subject = $file_prefix.' [ЗАКАЗ]';
		$message = "Добрый день. Заявка {$order_type} во вложении";
		if($message_body != ""){
			$message .= "\n\r<br><strong>Комментарий: </strong>";
			$message .= "{$message_body}";
		}
		
		$server_adress = ($_SERVER['HTTPS']) ? "https://". $_SERVER['SERVER_NAME'] : "http://". $_SERVER['SERVER_NAME']; // Задаем адрес сервера с протоколом

		$message .= "\n\r<br>Для просмотра заявок от клиентов необходимо перейти по <a href = '{$server_adress}'>адресу: {$server_adress}</a>";

		// $message .= "\n\r<br>>> Взять в работу: <a href='{$server_adress}/OrderToWork/?access_line={$access_line}&operator=mgr&operation=taketowork'>{$server_adress}/OrderToWork/?access_line={$access_line}&operator=mgr&operation=taketowork</a>";
		// $message .= "\n\r<br>>> Отправить предложение: <a href='{$server_adress}/OrderToWork/?access_line={$access_line}&operator=mgr&operation=sendfirstoffer'>{$server_adress}/OrderToWork/?access_line={$access_line}&operator=mgr&operation=sendfirstoffer</a>";
		// $message .= "\n\r<br>>> Отменить зкакз: <a href='{$server_adress}/OrderToWork/?access_line={$access_line}&operator=mgr&operation=cancelorder'>{$server_adress}/OrderToWork/?access_line={$access_line}&operator=mgr&operation=cancelorder</a>";

		$sender_mail = $user_login;
		$sender_name = $user_name ." ". $user_surname ;
		
		SendMailGRMPAttachment($mail, $subject, $message, $sender_mail, $sender_name, $target_file);


		/* Отправка копии заказа на почту клиенту **********************************************************************************************************************/
		$mail = ($testing_mode) ? $tester_mail : $user_login;
		// $mail = 'Tsvetkov-SA@grmp.ru';
		// $mail = $user_login;
		$subject = $file_prefix.' [ЗАКАЗ]';
		$message = "Добрый день. Вы отправили заявку {$file_prefix}. Файл заявки во вложении.";
		if($message_body != ""){
			$message .= "\n\r<br><strong>Комментарий: </strong>";
			$message .= "{$message_body}";
		}
		$server_adress = ($_SERVER['HTTPS']) ? "https://". $_SERVER['SERVER_NAME'] : "http://". $_SERVER['SERVER_NAME']; // Задаем адрес сервера с протоколом
		$message .= "\n\r<br>Для просмотра ваших заявок необходимо перейти по <a href = '{$server_adress}'>адресу: {$server_adress}</a>";

		$sender_mail = $team_manager_mail;
		$sender_name = $user_team_name;
		
		SendMailGRMPAttachment($mail, $subject, $message, $sender_mail, $sender_name, $target_file);
		/************************************************************************************************************************/
		header("Location: /"); exit;
	}
}else{
	header("Location: ../Login/baselogin/login.php"); exit;
}
?>