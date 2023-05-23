<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Предложение отправлено</title>
	<link rel="stylesheet" href="../css/styleOrders.css">	
</head>
<body>
<div class="wrapper">
<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php");
include_once($_SERVER['DOCUMENT_ROOT'].'/Assets/PHPMailer/PHPMailerFunction.php'); // Почтальен Печкин
include_once($_SERVER['DOCUMENT_ROOT'].'/OrderToWork/functions.php'); // Функции API
$access_line = $_POST['access_line']; // Получаем ХЭШ
$message_body = ClearMessageString($_POST['message_body']); // Получаем сообщение

// Получаем  информацию об ордере по его хэшу
extract(order($pdo, $access_line), EXTR_PREFIX_SAME, "temp");  // Достаем переменные из массива

// Работаем с ордером, который существует
if($order_id){
	if($state_type == 0){
		// Забыли взять документ в работу
		getInfoButton("danger", "Документ необходимо взять в работу!");
	}else if($state_type == 1 || $state_type == 3){
	// Если ордер взят в работу, но предложение еще не отправлено - формируем предложение, прикрепляем к нему файл и отправляем клиенту

		// Устанавливаем статус заказа: для нового предложения в 2, для повторного в 4. Внимение: статус повторного заказа может понижаться с 4 до 3
		switch($state_type){
			case 1: sendOfferToClientBase($pdo, $order_id, '2', $message_body); break;
			case 3: sendOfferToClientBase($pdo, $order_id, '4', $message_body); break;
		}

		//**************************** Загрузка файла ПЕРВОГО ПРЕДЛОЖЕНИЯ ЛОГИСТ => КЛИЕНТ ****************************// 
		// Получаем файл от ЛОГИСТА и закидываем его в каталог пользователя
		$filename = $_FILES['offer_file']['name']; // Реальное имя файла Офера

		// Определяем части файла для переименования
		$file_string_out = preg_match('/(.+)\.(xls|xlsx)$/', $filename, $parts_matches); //$parts_matches[1] - имя, $parts_matches[2] - расширение

		// Помещаем файл предложения OFFER в ту же папку, где лежит файл заказа ORDER
		$user_dir = $_SERVER['DOCUMENT_ROOT'].'/uploaded_documents/' . $user_id;

		// Формирование файла офера (предложения) для клиента
		$offer_key = $order_key."_".$order_type; // Оффер-кей соответствует его ключу ордера
		$file_prefix = $offer_key."_OFFER_";
		$file_in =  $file_prefix . "." . $parts_matches[2]; // Переименовываем файл офера (убираем имя файла - оставляем расширение)
		$target_file =  $user_dir . "/" . $file_in; // Результирующий файл
		
		move_uploaded_file($_FILES['offer_file']['tmp_name'], $target_file); // Кладем нужный файл в папку клиента


		//************************ Отправка письма ЛОГИСТ => КЛИЕНТ ****************************// 
		include_once($_SERVER['DOCUMENT_ROOT'].'/Layout/engineering.php'); // Блок тестирования
		$mail = ($testing_mode) ? $tester_mail : $user_login;

		// $mail = 'Tsvetkov-SA@grmp.ru'; // Получатель сообщения
		// $mail = $user_login'; // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! Заменить!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

		$subject = ($state_type == 3) ? $file_prefix.' [ПОВТОРНОЕ ПРЕДЛОЖЕНИЕ]' : $file_prefix.' [ПРЕДЛОЖЕНИЕ]';

		// $subject = $file_prefix.' [ПРЕДЛОЖЕНИЕ]';
		$message = "Добрый день. Предложение по заявке {$offer_key} во вложении";
		if($message_body != ""){
			$message .= "\n\r<br><strong>Комментарий: </strong>";
			$message .= "{$message_body}";
		}
		$server_adress = ($_SERVER['HTTPS']) ? "https://". $_SERVER['SERVER_NAME'] : "http://". $_SERVER['SERVER_NAME']; // Задаем адрес сервера с протоколом
		$message .= "\n\r<br>Для просмотра ваших заявок и предложений необходимо перейти по <a href = '{$server_adress}'>адресу: {$server_adress}</a>";

		// $sender_mail = $user_login; // Внимание!!!!!!! Определить - кто является отправителем письма
		// $sender_name = $user_name ." ". $user_surname ; // Внимание!!!!!!! Определить - кто является отправителем письма


		$sender_mail = $team_manager_mail; // Внимание!!!!!!! Отправитель - Общий адрес комманды клиента
		$sender_name = $team_name; // Внимание!!!!!!! Определить - кто является отправителем письма
		
		SendMailGRMPAttachment($mail, $subject, $message, $sender_mail, $sender_name, $target_file); // Отправляем почту с вложением

		getInfoButton("info", "Предложение для клиента отправлено");

	}else if($state_type > 4 || $state_type <= 6 ){
		getInfoButton("danger", "Предложение на рассмотрении клиентом. Или заказ на формировании");
	}

}else{
	getInfoButton("danger", "Документ отсутствует в базе!");
}

// ФУНКЦИИ API 
// Функция получение информационной кнопки
// function getInfoButton($btnType, $btnMessage){
// 	echo "<div class='message {$btnType}' onClick='window.close()';>{$btnMessage}</div>";
// }
// Функция добавления (обновления статуса ордера). Сюда добавляем запись сообщения от логиста к клиенту
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
</div>
</body>
</html>
