<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php");
include_once($_SERVER['DOCUMENT_ROOT'].'/Assets/PHPMailer/PHPMailerFunction.php'); // Почтальен Печкин
include_once($_SERVER['DOCUMENT_ROOT'].'/OrderToWork/functions.php'); // Функции API
$access_line = $_POST['access_line']; // Получаем ХЭШ
$message_body = ClearMessageString($_POST['message_body_cancel']); // Получаем сообщение

// Получаем  информацию об ордере по его хэшу
extract(order($pdo, $access_line), EXTR_PREFIX_SAME, "temp");  // Достаем переменные из массива

// Работаем с ордером, который существует
if($order_id){
	if($state_type == 0 || $state_type == 3){
		// Закрываем заказ (присваиваем состояние 7)

		sendOfferToClientBase($pdo, $order_id, '7', $message_body);  // Пишем в базу

		//************************ Отправка письма ЛОГИСТ => КЛИЕНТ ****************************// 
		// Формирование файла офера (предложения) для клиента
		$offer_key = $order_key."_".$order_type; // Оффер-кей соответствует его ключу ордера
		$file_prefix = $offer_key."_ORDER_";

		include_once($_SERVER['DOCUMENT_ROOT'].'/Layout/engineering.php'); // Блок тестирования
		$mail = ($testing_mode) ? $tester_mail : $user_login;
		// $mail = 'Tsvetkov-SA@grmp.ru'; // Получатель сообщения
		// $mail = $user_login'; // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! Заменить!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

		$subject = $file_prefix.' [ЗАКАЗ ОТМЕНЕН]';
		
		$message = "Добрый день. Заказ {$offer_key} отменен отделом логистики!";
		if($message_body != ""){
			$message .= "\n\r<br><strong>Комментарий: </strong>";
			$message .= "{$message_body}";
		}
		$server_adress = ($_SERVER['HTTPS']) ? "https://". $_SERVER['SERVER_NAME'] : "http://". $_SERVER['SERVER_NAME']; // Задаем адрес сервера с протоколом
		$message .= "\n\r<br>Для просмотра ваших заявок и предложений необходимо перейти по <a href = '{$server_adress}'>адресу: {$server_adress}</a>";

		$sender_mail = $team_manager_mail; // Внимание!!!!!!! Отправитель - Общий адрес комманды клиента
		$sender_name = $team_name; // Внимание!!!!!!! Определить - кто является отправителем письма
		
		SendMailGRMP($mail, $subject, $message, $sender_mail, $sender_name); // Отправляем почту с вложением

		header("Location: /"); exit; // Возврат к списку заявок

		// getInfoButton("info", "Заказ отменен"); // Сообщение об отмене заказа

	}else{
		getInfoButton("danger", "Предложение на рассмотрении клиентом. Или заказ на формировании");
	}

}else{
	getInfoButton("danger", "Документ отсутствует в базе!");
}

// ФУНКЦИИ API 
// Функция добавления (обновления статуса ордера)
function sendOfferToClientBase($pdo, $order_id, $state_type = '2', $message_body){
	// Добавляем информацию об операции в таблицу orders_states (первое предложение)
	$stm = $pdo->prepare("INSERT INTO orders_states SET order_id = :order_id, state_type = :state_type, state_reason = :state_reason");
	$stm->execute([
		'order_id' => $order_id,
		'state_type' => $state_type,
		'state_reason' => $message_body
	]);
}
// Функция очистки текстового поля
function ClearMessageString($string){
	$eitem = array('strong','lt','gt','sub','&','amp');
	$eitemAllowed = array("\r\n", "\n", "\r");
	$string = htmlentities(htmlspecialchars($string));
	$string = str_replace($eitem, "", $string);
	$string = str_replace($eitemAllowed, "<br>", $string);
	$string = trim($string);
	return $string;
}
?>
