<h3>Заявки от клиентов</h3>
<?php
$stm = $pdo->prepare("SELECT 
order_id, order_date, order_key, order_type, order_hash, usr.user_id, file_name, usr.user_login, usr.user_name, usr.user_surname
FROM orders AS ord LEFT JOIN users AS usr ON (ord.user_id = usr.user_id) WHERE usr.user_team = ? AND usr.user_role = 'kln' ORDER BY ord.order_date DESC");
$stm->execute([$user_team_id]);
$ord = $stm->fetchAll(PDO::FETCH_ASSOC);
$status = new ordersInfo($pdo);  // Статус заказа
// Проверяем - есть ли работа для логиста?
if (count($ord) > 0){
	echo "<table class='table table-sm fileslist' id='oneTable'>";
	echo "<thead>";
	echo "<tr><th>Заказ</th><th>Дата заказа</th><th>Статус заказа</th><th>Заказчик</th><th>Файл заказа</th><th>Размер</th></tr>";
	echo "</thead>";
	echo "<tbody>";
	foreach($ord as $ord){
		echo "<tr>";
		echo "<td><a href='/index.php?module=LogisticianHome&ord=".$ord['order_hash']."'>". $ord['order_key']."_".$ord['order_type']."</a></td>";
		echo "<td>". date('d.m.Y H:i', strtotime ($ord['order_date']))."</td>";
		echo "<td>". $status->orderState($ord['order_id'])."</td>";
		echo "<td>". $ord['user_name']." ".$ord['user_surname']." [" . $ord['user_login']. "]</td>";
		echo "<td>";
		echo "<a href = '/Modules/LogisticianHome/action.php?file=".$ord['file_name']."&link_type=order'>" . $ord['file_name']. "</a>";
		echo "</td>";
		echo "<td>" . $status->getOrderFileSize($ord['order_id']). "</td>";
		echo "</tr>";
	}
	echo "</tbody>";
	echo "</table>";
}else{
	echo "<div class='alert alert-warning' role='alert'>Нет движения по группе!</div>";
}
?>