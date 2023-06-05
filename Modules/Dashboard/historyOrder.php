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
	$stm = $pdo->prepare("SELECT state_type, state_date, state_reason FROM orders_states WHERE order_id = :order_id");
	$stm->execute([
		'order_id' => $order_id
	]);
	$steps = $stm->fetchAll(PDO::FETCH_ASSOC);
	$info_string = "";	
		$info_string .= "<table class='table table-striped table-sm' id='tableHistory'>";
		$info_string .= "<tr><th scope='col' width='40%'>Действие</th><th scope='col' width='20%'>Дата и время</th><th scope='col' >Комментарий</th></tr>";
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
			$state_reason = $st['state_reason'];
			$info_string .= "<tr><td>{$status_string}</td><td>{$date_string}</td><td>{$state_reason}</td></tr>";
		}
		$info_string .= "</tr>";
		$info_string .= "</table>";

		$info_string .= "<hr>";

		// Ищем файл заявки и файл последнего предложения

		$stmt = $pdo->prepare("SELECT order_key, order_type, file_name, user_id FROM orders WHERE order_id = :order_id");
		$stmt->execute([
			'order_id' => $order_id
		]);
		$fls = $stmt->fetch(PDO::FETCH_ASSOC);

		$user_id = $fls['user_id'];	// ID пользователя (каталог)
		$file_path = $_SERVER['DOCUMENT_ROOT']."/uploaded_documents/".$user_id."/"; // Путь к файлам пользователя

		$order_file_name = $fls['file_name']; // Имя файла запроса

		if(file_exists($file_path . $fls['order_key'] . "_" .  $fls['order_type'] . "_OFFER_.xlsx")){
			$offer_file_name = $fls['order_key'] . "_" .  $fls['order_type'] . "_OFFER_.xlsx";
			$offer_view = true;
		}else if(file_exists($file_path . $fls['order_key'] . "_" .  $fls['order_type'] . "_OFFER_.xls")){
			$offer_file_name = $fls['order_key'] . "_" .  $fls['order_type'] . "_OFFER_.xls";
			$offer_view = true;
		}else{
			$offer_view = false;
		}	
		$info_string .= "<div class='dropdn-file'>";
		$info_string .= "<p class='pulse'><a href='/Modules/Dashboard/action.php?file={$order_file_name}&link_type=order'>Скачать файл запроса</a></p>";
		$info_string .= ($offer_view) ? "<p class='pulse'><a href='/Modules/Dashboard/action.php?file={$offer_file_name}&user_id={$user_id}&link_type=offer'>Скачать файл текущего предложения</a></p>" : "<p class='pulse'>Фаил текущего предложения не найден</p>";
		$info_string .= "</div>";

	return $info_string;
}
?>