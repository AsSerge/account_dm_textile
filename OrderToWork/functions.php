<?php
// ФУНКЦИИ API
include_once($_SERVER['DOCUMENT_ROOT'].'/Assets/PHPMailer/PHPMailerFunction.php'); // Почтальен Печкин
// Функция установки статуса ордера
function setOrderState($pdo, $order_id, $state_type, $message_body){
	// Добавляем информацию о статусе ордера в базу
	$stm = $pdo->prepare("INSERT INTO orders_states SET state_type = :state_type, order_id = :order_id, state_reason = :state_reason");
	$stm->execute(array(
		'order_id' => $order_id,
		'state_type' => $state_type,
		'state_reason' => $message_body
	));
}
// Функция получения расширенной информации по ордеру (Необходима для отправки почтового сообщения)
function order($pdo, $access_line){
	$stm = $pdo->prepare("SELECT 
	ST.state_type, ST.order_id, ORD.file_name, ORD.order_key, ORD.order_type, U.user_id, U.user_login, U.user_leader, U.user_name, U.user_surname
	FROM orders_states AS ST 
	LEFT JOIN orders AS ORD ON (ST.order_id = ORD.order_id) 
	LEFT JOIN users AS U ON (ORD.user_id = U.user_id) 
	WHERE ORD.order_hash = :order_hash 
	ORDER BY state_date DESC");
	$stm->execute(array(
		'order_hash' => $access_line
	));
	$order = $stm->fetch(PDO::FETCH_ASSOC); // Массив значений по ордеруВыборка одной (последняя по времени)
	// Получаем почту team_manager_mail для менеджера (необходимо для отправки обратного сообщения)
	$team_manager_id = $order['user_leader'];
	$stm = $pdo->prepare("SELECT 
	team_manager_mail, UT.team_name
	FROM users AS U
	LEFT JOIN user_teams AS UT ON (U.user_team = UT.team_id)
	WHERE user_id = ?");
	$stm->execute([$team_manager_id]);
	$team_info = $stm->fetch(PDO::FETCH_ASSOC); // Почтовый ящик отправителя
	$team_manager_mail = $team_info['team_manager_mail'];
	$team_manager_name = $team_info['team_name'];

	$order['team_manager_mail'] = $team_info['team_manager_mail'];
	$order['team_name'] = $team_info['team_name'];

	/*
	$order['state_type'] - Текущее состояние документа
	$order['order_id'] - ID ордера
	$order['order_key'] - Ключ ордера
	$order['order_type'] - Тип ордера
	$order['file_name'] - Имя файла - предложения
	$order['user_id'] - ID пользователя - необходимо для понимания - куда грузить файл предложения
	$order['user_login'] - Почта пользователя, для которого отправляется предложения
	$order['user_leader'] - ID отправителя
	$order['user_name'] - Имя отправителя
	$order['user_surname'] - Фамилия отправителя
	$order['team_manager_mail'] - Командная почта отправителя
	$order['team_name'] = Название команды отправителя	
	*/
	return $order; // Возвращаем расширенный масссив
}

// Функция отправки сообщения Логисту (Безусловное принятие прадложения по заказу)
function sendMailToLogist($mail, $order_key, $order_type, $user_login, $user_name, $user_surname, $user_id, $access_line){
	include_once($_SERVER['DOCUMENT_ROOT'].'/Layout/engineering.php'); // Блок тестирования
	$mail = ($testing_mode) ? $tester_mail : $team_manager_mail;
	// $mail = 'Tsvetkov-SA@grmp.ru';
	// $mail = $team_manager_mail; // Общая почта подргуппы для отправки заявки !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! Заменить!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

	// Получаем путь к файлу оффера (возможны только два расширения файлов xls и xlsx)
	if(file_exists($_SERVER['DOCUMENT_ROOT']."/uploaded_documents/{$user_id}/{$order_key}_{$order_type}_OFFER_.xlsx")){
		$target_file = $_SERVER['DOCUMENT_ROOT']."/uploaded_documents/{$user_id}/{$order_key}_{$order_type}_OFFER_.xlsx";
	}else if(file_exists($_SERVER['DOCUMENT_ROOT']."/uploaded_documents/{$user_id}/{$order_key}_{$order_type}_OFFER_.xls")){
		$target_file = $_SERVER['DOCUMENT_ROOT']."/uploaded_documents/{$user_id}/{$order_key}_{$order_type}_OFFER_.xls";
	}	
	
	$subject = $order_key ."_".$order_type."_ORDER_". "[СОГЛАСОВАН]";
	$message = "Добрый день!";
	$message .= "\n\rЗаказ ".$order_key." согласован";
	$message .= "\n\rСолгасованный файл во вложении.";
	$message .= "\n\r";
	$message .= "\n\r<br>". $user_name . " ". $user_surname;
	$server_adress = ($_SERVER['HTTPS']) ? "https://". $_SERVER['SERVER_NAME'] : "http://". $_SERVER['SERVER_NAME']; // Задаем адрес сервера с протоколом
	$message .= "\n\r<br>>> Начать формирование заказа: <a href='{$server_adress}/OrderToWork/?access_line={$access_line}&operator=mgr&operation=towork'>{$server_adress}/OrderToWork/?access_line={$access_line}&operator=mgr&operation=towork</a>";	
	$sender_mail = $user_login; // Внимание!!!!!!! Отправитель - Общий адрес комманды клиента
	$sender_name = $user_name . " " . $user_surname; // Внимание!!!!!!! Определить - кто является отправителем письма

	// Прикрепляем к письму согласованный оффер	
	SendMailGRMPAttachment($mail, $subject, $message, $sender_mail, $sender_name, $target_file);
	// SendMailGRMP($mail, $subject, $message, $sender_mail, $sender_name); // Отправляем почту 
}

// Функция отправки сообщения клиенту о начале формирования утвержденного заказа
function sendMailToClientEnd($mail, $order_key, $order_type, $team_manager_mail, $team_name){

	// Отправляем информационное письмо клиенту
	include_once($_SERVER['DOCUMENT_ROOT'].'/Layout/engineering.php'); // Блок тестирования
	$mail = ($testing_mode) ? $tester_mail : $order['user_login'];
	// $mail = 'Tsvetkov-SA@grmp.ru';
	// $mail = $order['user_login']; // КЛИЕНТ !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! Заменить!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	$subject = $order_key ."_".$order_type."_ORDER_". "[ФОРМИРОВАНИЕ ЗАКАЗА]";
	$message = "Добрый день!";
	$message .= "\n\rЗаказ ".$order_key." отправлен на формирование";
	$sender_mail = $team_manager_mail; // Внимание!!!!!!! Отправитель - Общий адрес комманды клиента
	$sender_name = $team_name; // Внимание!!!!!!! Определить - кто является отправителем письма
	// ВСТАВИТЬ УДАЛЕНИЕ ФАЙЛА ЗАКАЗА и СООТВЕТСВУЮЩЕГО ЕМУ ФАЙЛА ПРЕДЛОЖЕНИЯ????????????????????????????????????????????????????????
	SendMailGRMP($mail, $subject, $message, $sender_mail, $sender_name); // Отправляем информационное сообщение
}	
// Функция получение информационной кнопки
function getInfoButton($btnType, $btnMessage){
	echo "<div class='message {$btnType}' onClick='window.close()';>{$btnMessage}</div>";
}

// Функция формирования диалогового окна отправки оффера МЕНЕДЖЕР => КЛИЕНТ
function sendOffer($access_line){
	echo "<div class='offer'>\n\r";
	echo "<h3>Форма отправки предложения (офера)</h3>\n\r";
	echo "<form id='send_offer_form' class='sendoffer' enctype='multipart/form-data' method='POST' action ='/OrderToWork/sendOffer.php'>\n\r";

	echo "<input type='hidden' name='access_line' value='{$access_line}'>\n\r";

	echo "<div class='send_offer_field'>\n\r";
	echo "<label for='offer_file' class='avatar-field'>\n\r";

	echo "<input type='file' id='offer_file' name='offer_file' accept = '.xls, .xlsx'>\n\r";	
	echo "<img src='../images/brand/excel_snd.png' id='preview'>\n\r";
	
	echo "</label>\n\r";
	echo "<div class='file_info' id='msg_form'>Добавьте файл</div>\n\r";
	echo "</div>\n\r";

	echo "<div class='send_offer_field'>\n\r";
	echo "<textarea id='message_body' name='message_body' rows='6' cols='50' maxlength='500'></textarea>\n\r";	

	echo "<div class='message_info' id='message_info'></div>\n\r";

	echo "</div>\n\r";

	echo "<div class='send_offer_button'>\n\r";
	
	echo "<button type='submit' class='sub' id='submit' >Отправить</button>\n\r";
	echo "</div>\n\r";
	
	echo "</form>\n\r";	

	echo "</div>\n\r";
}


// Функция безусловной отмены заказа
function sendCancel($access_line){
	echo "<div class='offer'>\n\r";
	echo "<h3>Отмена заказа (опишите причину)</h3>\n\r";
	echo "<form id='cancel_odrder_form' class='sendoffer' enctype='multipart/form-data' method='POST' action ='/OrderToWork/cancelOrder.php'>\n\r";

	echo "<input type='hidden' name='access_line' value='{$access_line}'>\n\r";
	
	echo "<div class='send_offer_field'>\n\r";
	echo "<textarea id='message_body_cancel' name='message_body_cancel' rows='6' cols='50' maxlength='500'></textarea>\n\r";	

	echo "<div class='message_info' id='message_info'></div>\n\r";

	echo "</div>\n\r";

	echo "<div class='send_offer_button'>\n\r";
	
	echo "<button type='submit' class='sub' id='submit' >Отправить</button>\n\r";
	echo "</div>\n\r";
	
	echo "</form>\n\r";	

	echo "</div>\n\r";

}
?>