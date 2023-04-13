<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Отправка предложения клиенту от логиста</title>
	<link rel="stylesheet" href="../css/styleOrders.css">
</head>
<body>
<div class="wrapper">	
<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); // Подключаем базу
// include_once($_SERVER['DOCUMENT_ROOT'].'/Assets/PHPMailer/PHPMailerFunction.php'); // Почтальен Печкин
include_once($_SERVER['DOCUMENT_ROOT'].'/OrderToWork/functions.php'); // Функции API
// Основные настройки для работы с api
$allowIPArray = ["31.173.222.2", "87.229.247.154", "213.27.12.29", "185.180.40.121", "5.167.52.86", "127.0.0.1"]; // Задаем список доступных адресов, с которых может работать логист

// Настройки доступа логиста
$access_line = $_GET['access_line']; // Получаем запрос из письма (строка доступа к ордеру)
$operator = $_GET['operator']; // Получаем оператора (mgr - менеджер или логист, kln - клиент)
$operation = $_GET['operation']; // Получаем тип операции
$uIp = $_SERVER['REMOTE_ADDR']; // Получаем адрес для логиста

extract(order($pdo, $access_line), EXTR_PREFIX_SAME, "temp");  // Достаем переменные из массива

// MAIN PROGRAMM (для Логистов)	
if(in_array($uIp, $allowIPArray) && $order_id){	
	if($operation == "taketowork"){
		// Если заказ впервые берется в работу (или логист назал эту кнопку)
		switch($state_type){
			case 0:
				// Добавляем информацию о новом заказа в базу
				setOrderState($pdo, $order_id, "1");  // Пишем информацию в базу (статус 1)
				getInfobutton("info", "Документ взят в работу!");break;
			case 1: getInfobutton("info", "Документ УЖЕ находится в работе!"); break;
			case 2: getInfobutton("info", "Документ находится на рассмотрении клиентом!"); break;
			case 3: getInfobutton("info", "Получен запрос на доработку"); break;
			case 4: getInfobutton("info", "Отправлено повторное предложение!"); break;
			case 5: getInfobutton("info", "Одобрен клиентом!"); break;
			case 6: getInfobutton("info", "Документ передан на формирование!"); break;
			case 7:	getInfobutton("info", "Заказ отменен!"); break;
		}	
	}else if ($operation == "sendfirstoffer"){
	// Если по заказу отсылается первое предложение
		switch($state_type){
			case 0:
				// Не забудь взять заказ в работу
				getInfobutton("danger", "Документ должен быть взят в работу!"); break; 
			case 1: sendOffer($access_line); break; // Открываем форму перврначальной отправки предложения клиенту => sendOffer.php
			case 2: //
			case 3: //
			case 4: //
			case 5: //
			case 6:	getInfobutton("info", "Заказ в работе или на стадии формирования!"); break;
			case 7:	getInfobutton("info", "Заказ отменен!"); break;
		}
	}else if ($operation == "sendnextoffer"){
		switch($state_type){
			case 0:
				// Не забудь взять заказ в работу
				getInfobutton("danger", "Документ должен быть взят в работу!"); break; 
			case 1: //
			case 2: //
			case 3: sendOffer($access_line); break; // Открываем форму перврначальной отправки предложения клиенту => sendOffer.php
			case 4: getInfobutton("info", "Заказ на рассмотрении у клиента!"); break;
			case 5: //
			case 6:	getInfobutton("info", "Заказ в работе или на стадии формирования!"); break;
			case 7:	getInfobutton("info", "Заказ отменен!"); break;
		}
	}else if ($operation == "towork"){
		switch($state_type){
			case 0:
				// Не забудь взять заказ в работу
				getInfobutton("danger", "Документ должен быть взят в работу!"); break; 
			case 1: // 
			case 2: //
			case 3: //
			case 4: getInfobutton("info", "Заказ в работе или на стадии формирования!"); break;
			case 5: 
				setOrderState($pdo, $order_id, "6");
				sendMailToClientEnd($user_login, $order_key, $order_type, $team_manager_mail, $team_name); // Отправляем клиенту инормацию о начале работы с заказом
				getInfobutton("info", "Заказ отправлен на формирование!"); break;
			case 6:	getInfobutton("info", "Заказ в работе или на стадии формирования!"); break;
			case 7:	getInfobutton("info", "Заказ отменен!"); break;
		}
	}else if ($operation == "cancelorder"){
		switch($state_type){
			case 0: sendCancel($access_line); break; // Открываем форму перврначальной отправки предложения клиенту => sendOffer.php
			case 1: //
			case 2: getInfobutton("info", "Заказ в работе или на стадии формирования!"); break;
			case 3: sendCancel($access_line); break; // Открываем форму перврначальной отправки предложения клиенту => sendOffer.php
			case 4: //
			case 5: //
			case 6:	getInfobutton("info", "Заказ в работе или на стадии формирования!"); break;
			case 7:	getInfobutton("info", "Заказ отменен!"); break;
		}

	}
}else{
	getInfobutton("danger", "Ошибка доступа или документ отсутствует в базе");	// Если посетитель не входит в доступный пулл IP адресов
}

?>
</div>
<script src="/OrderToWork/ordertowork.js"></script>
</body>
</html>
