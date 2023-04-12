<?php
if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
	include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php");
	echo getOrderHistory($pdo, $_POST['order_id']);
}else{
	header("Location: /"); exit;	
}

// Функция получения истории заказа
function getOrderHistory($pdo, $order_id){
	$stm = $pdo->prepare("SELECT state_type, state_date FROM orders_states WHERE order_id = :order_id");
	$stm->execute([
		'order_id' => $order_id
	]);
	$steps = $stm->fetchAll(PDO::FETCH_ASSOC);
	$info_string = "";	
		$info_string .= "<table>";
		foreach ($steps as $st){
			switch ($st['state_type']){
				case 0: $status_string = "Сформирован запрос"; break;
				case 1: $status_string = "Взят в работу логистом"; break;
				case 2: $status_string = "Отправлено Первичное предложение"; break;
				case 3: $status_string = "Отправлен запрос на доработку"; break;
				case 4: $status_string = "Отправлено повторное предложение"; break;
				case 5: $status_string = "Предложение одобрено клиентом"; break;
				case 6: $status_string = "Заказ отправлен на формирование"; break;
				case 7: $status_string = "Заказ отменен логистом"; break;
			}	
			$date_string = date('d.m.Y H:i', strtotime ($st['state_date']));
			$info_string .= "<tr><td>{$status_string}</td><td>{$date_string}</td></tr>";
		}
		$info_string .= "</table>";

	return $info_string;
}

?>