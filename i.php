<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php");

// Функция получения актуального статуса заказа
function getMaxState($pdo, $order_id){
	$stm = $pdo->prepare("SELECT MAX(state_type) FROM orders_states WHERE order_id = ?");
	$stm->execute([$order_id]);
	$order_state = $stm->fetch(PDO::FETCH_COLUMN);	
	return $order_state;
}

$stm = $pdo->prepare("SELECT order_id FROM orders WHERE 1");
$stm->execute();
$orders = $stm->fetchAll(PDO::FETCH_ASSOC);

$arr = []; // Массив ключей
foreach($orders as $ord){
	echo $ord['order_id'] . " - " .getMaxState($pdo, $ord['order_id']) . "<br>";
	$arr[] = getMaxState($pdo, $ord['order_id']); // Заполняем массив
}

$arr_states = array_count_values($arr); // Считаем количество каждого ключа в списке

// Перебираем все заказы
foreach ($arr_states as $key => $value){
	switch ($key){
		case 0: $status_string = "Новый заказ"; break;
		case 1: $status_string = "Заказ взят в работу"; break;
		case 2: $status_string = "Отправлено первичное предложение"; break;
		case 3: $status_string = "Получен запрос на доработку"; break;
		case 4: $status_string = "Отправлено повторное предложение"; break;
		case 5: $status_string = "Одобрен клиентом"; break;
		case 6: $status_string = "Заказ на формировании"; break;
		case 7: $status_string = "Заказ отменен"; break;
	}
	echo $status_string . "(". $key . ") : " . $value . "<br>";
}

?>