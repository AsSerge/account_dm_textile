<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-drafting-compass" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Домашняя страница</h6>
				<small><?php echo $user_id ." > ". $user_name." " .$user_surname. " [".$user_role_description." - ".$user_team_name."]";?></small>				
			</div>
</div>
<style>
	.fileslist{
		font-size: 0.8rem;
	}
	.fileslist td{
		vertical-align: middle;
	}
</style>	
<div class="my-3 p-3 bg-white rounded box-shadow">
	<h3>Заявки от клиентов</h3>

	<?php
	$stm = $pdo->prepare("SELECT 
	order_id, order_date, order_key, order_type, usr.user_id, file_name, usr.user_login, usr.user_name, usr.user_surname
	FROM orders AS ord LEFT JOIN users AS usr ON (ord.user_id = usr.user_id) WHERE usr.user_team = ? AND usr.user_role = 'kln' ORDER BY ord.order_date DESC");

	$stm->execute([$user_team_id]);
	$ord = $stm->fetchAll(PDO::FETCH_ASSOC);

	$status = new ordersInfo($pdo);  // Статус заказа

	// Проверяем - есть ли работа для логиста?
	if (count($ord) > 0){
		echo "<table class='table table-sm fileslist' id='oneTable'>";
		echo "<thead>";
		echo "<tr><th>Заказ</th><th>Дата заказа</th><th>Статус заказа</th><th>Заказчик</th><th>Файл</th><th>Размер</th></tr>";
		echo "</thead>";
		echo "<tbody>";

		foreach($ord as $ord){
			echo "<tr>";
			echo "<td>". $ord['order_key']."_".$ord['order_type']."</td>";
			echo "<td>". date('d.m.Y H:i', strtotime ($ord['order_date']))."</td>";
			echo "<td>". $status->orderState($ord['order_id'])."</td>";
			echo "<td>". $ord['user_name']." ".$ord['user_surname']."</td>";
			echo "<td>";
			echo "<a href = '/Modules/LogisticianHome/action.php?file=".$ord['file_name']."&link_type=logist'>" . $ord['file_name']. "</a>";
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
</div>

<?php
// Класс для получения информации о доступных для логиста ордерах
class ordersInfo{
	private $pdo;
	public function __construct($pdo) {
		$this->pdo = $pdo;
	}
	public function orderState($order_id){
		$stmt = $this->pdo->prepare("SELECT MAX(state_type) FROM orders_states WHERE order_id = ? ORDER BY state_date DESC");
		$stmt->execute([$order_id]);
		$order_state = $stmt->fetch(PDO::FETCH_COLUMN);

		switch ($order_state){
			case 0: $status_string = "Новый заказ"; break;
			case 1: $status_string = "Заказ взят в работу"; break;
			case 2: $status_string = "Скачать первичное предложение"; break;
			case 3: $status_string = "Запрос на доработку"; break;
			case 4: $status_string = "Скачать повторное предложение"; break;
			case 5: $status_string = "Одобрен клиентом"; break;
			case 6: $status_string = "Заказ на формировании"; break;
			case 7: $status_string = "Заказ отменен"; break;
		}
		return $status_string;
		
	}
	public function getOrderFileSize($order_id){
		$stmt = $this->pdo->prepare("SELECT user_id, file_name FROM orders WHERE order_id = ?");
		$stmt->execute([$order_id]);
		$targetFile = $stmt->fetch(PDO::FETCH_ASSOC);

		$fileSize_string = '100 M' . $targetFile['user_id'] . "/" . $targetFile['file_name'];
		
		return $fileSize_string;
	}
}
?>
