<?php
$order_hash = $_GET['ord'];

$stm = $pdo->prepare("SELECT 
order_id, order_date, order_key, order_type, order_hash, usr.user_id, file_name, usr.user_login, usr.user_name, usr.user_surname
FROM orders AS ord LEFT JOIN users AS usr ON (ord.user_id = usr.user_id) WHERE order_hash = ?");
$stm->execute([$order_hash]);
$ord = $stm->fetch(PDO::FETCH_ASSOC);

$status = new ordersInfo($pdo);  // Статус заказа (класс)

echo "<div class='row'>";
	echo "<div class='col-12 col-md-6 col-sm-12'>";
	echo "<h4 class='mb-4 mt-2'>Заявка ".$ord['order_key']."_".$ord['order_type']."</h4>";
		echo "<table class='table table-sm fileslist' id='oneTable'>";
		echo "<tbody>";
		echo "<tr><td>Заказчик:</td><td>".$ord['user_name']." ".$ord['user_surname']."</td></tr>";
		echo "<tr><td>Дата заявки:</td><td>".date('d.m.Y H:i', strtotime ($ord['order_date']))."</td></tr>";
		echo "<tr><td>Статус заявки:</td><td>".$status->orderState($ord['order_id'])."</td></tr>";
		echo "<tr><td>Скачать файл заявки:</td>";
		echo "<td>";
		echo "<a href = '/Modules/LogisticianHome/action.php?file=".$ord['file_name']."&link_type=order'>" . $ord['file_name']. " [".$status->getOrderFileSize($ord['order_id'])."]</a>";
		echo "</td>";
		echo "</tr>";

		if ($status->getOtherFileInfo($ord['order_id'], "OFFER")['fn'] != ''){
			echo "<tr>";
			echo "<td>Скачать файл предложения:</td>";
			echo "<td>";
			echo "<a href = '/Modules/LogisticianHome/action.php?file=".$status->getOtherFileInfo($ord['order_id'], "OFFER")['fn']."&user_id=".$ord['user_id']."&link_type=offer'>";
			echo $status->getOtherFileInfo($ord['order_id'], "OFFER")['fn']. " [" . $status->getOtherFileInfo($ord['order_id'], "OFFER")['fz']."]";
			echo "</a>";
			echo "</td>";
			echo "</tr>";
		}

		if ($status->getOtherFileInfo($ord['order_id'], "REVISION")['fn'] != ''){
			echo "<tr>";
			echo "<td>Скачать файл запроса доработки:</td>";
			echo "<td>";
			echo "<a href = '/Modules/LogisticianHome/action.php?file=".$status->getOtherFileInfo($ord['order_id'], "REVISION")['fn']."&user_id=".$ord['user_id']."&link_type=offer'>";
			echo $status->getOtherFileInfo($ord['order_id'], "REVISION")['fn']. " [" . $status->getOtherFileInfo($ord['order_id'], "REVISION")['fz']."]";
			echo "</a>";
			echo "</td>";
			echo "</tr>";
		}		


		echo "<tbody>";
		echo "</table>";
		echo $status->printOrderButtons($ord['order_id'], $ord['order_hash']); 
	echo "</div>";

	echo "<div class='col-12 col-md-6 col-sm-12'>";
	echo "<h4 class='mb-4 mt-2'>История заявки</h4>";
		echo $status->orderHistory($ord['order_id']);
	echo "</div>";
echo "</div>";

echo "<hr>";

?>