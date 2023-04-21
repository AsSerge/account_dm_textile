<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-drafting-compass" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Dashboard</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description." - ".$user_team_name."]";?></small>
			</div>
</div>

<style>
.one-block{	
	padding-bottom: 1rem;
}
</style>

<div class="my-3 p-3 bg-white rounded box-shadow">	
	<div class='container-fluid'>
		<div class="row">
			<div class="col-12 col-md-4 col-sm-12 one-block">
				<h5>Пользователи</h5>
				<div id='usr'></div>
			</div>

			<div class="col-12 col-md-4 col-sm-12 one-block">
				<h5>Заказы</h5>
				<div id='ord'></div>
			</div>

			<div class="col-12 col-md-4 col-sm-12 one-block">
				<h5>Группы</h5>
				<div id='groups'></div>
			</div>

		</div>
	</div>
</div>

<style>
.pulse{
	font-size: 0.9rem;
}	
.pulse thead, tfoot{
	background-color: #DADADA;
	font-weight: 500;
}
</style>

<div class="my-3 p-3 bg-white rounded box-shadow">	
	<div class='container-fluid'>
		<div class="row">
			<div class="col-12 col-md-12 col-sm-12 one-block">
				<h5>Пульс системы</h5>
				<?php
				include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php");
				$stat = new getStatistic($pdo);

				$stm = $pdo->prepare("SELECT order_id, order_key, order_type FROM orders WHERE 1");
				$stm->execute();
				$orders = $stm->fetchAll(PDO::FETCH_ASSOC);
				echo "<table class='table table-sm pulse'>";
				echo "<thead>";
				echo "<tr><th>Заказ</th><th>Принят в работу</th><th>Отправлено первое предложение</th><th>Время исполнения</th></tr>";
				echo "</thead>";
				echo "<tbody>";
				foreach($orders as $ord){
					echo "<tr><td>". $ord['order_key'] . "_".$ord['order_type']."</td><td>". $stat->getDifference($ord['order_id'],0,1,true) . "</td><td>" .$stat->getDifference($ord['order_id'],0,2,true). "</td><td>" .$stat->getDifference($ord['order_id'],0,5,true). "</td></tr>";
					$arr1[] = $stat->getDifference($ord['order_id'],0,1,false);
					$arr2[] = $stat->getDifference($ord['order_id'],0,2,false);
					$arr3[] = $stat->getDifference($ord['order_id'],0,5,false);
				}
				echo "</tbody>";
				echo "<tfoot>";
				echo "<tr>";
				echo "<td>Среднее время</td>";
				echo "<td>" .getAverageTime($arr1). "</td>";
				echo "<td>" .getAverageTime($arr2). "</td>";
				echo "<td>" .getAverageTime($arr3). "</td>";
				echo "</tr>";
				echo "</tfoot>";
				echo "</table>";
				?>
			</div>
		</div>
	</div>
</div>

<?php
class getStatistic{
	// Подключаем PDO
	private $pdo;
	public function __construct($pdo) {
		$this->pdo = $pdo;
	}
	private $order_start;
	private $order_end;
	// Функция определения разности между поступлением заявки и началом работы (использует приватную функцию getDifferencePrivate)
	public function getDifference($order_id, $start, $end, $output){
		$stmt = $this->pdo->prepare("SELECT state_date FROM orders_states WHERE order_id = ? AND state_type = ?");
		$stmt->execute([
			$order_id,
			$start
		]);
		$order_start = $stmt->fetch(PDO::FETCH_COLUMN);

		$stmt = $this->pdo->prepare("SELECT state_date FROM orders_states WHERE order_id = ? AND state_type = ?");
		$stmt->execute([
			$order_id,
			$end
		]);
		$order_end = $stmt->fetch(PDO::FETCH_COLUMN);

		// Выводим разницу между заданными временными метками
		if($output){
			return ($order_end) ? $this->getDifferencePrivate($order_start, $order_end) : " - ";
		}else{
			return (int)strtotime($order_end) - (int)strtotime($order_start);
		}
	}
	// Функция (приватная) определения разности между двумя датами
	private function getDifferencePrivate($start_string, $end_string){
		$start = new DateTime($start_string);
		$end = new DateTime($end_string);
		$diff = $start->diff($end); // Разница дат	

		$dif_string =  "";
		$dif_string .= ($diff->y) ? $diff->y . " год. " : "";
		$dif_string .= ($diff->m) ? $diff->m . " мес. " : "";
		$dif_string .= ($diff->d) ? $diff->d . " дн. " : "";
		$dif_string .= ($diff->h) ? $diff->h . " ч. " : "";
		$dif_string .= ($diff->i) ? $diff->i . " мин. " : "";
		$dif_string .= ($diff->s) ? $diff->s . " сек. " : "";

		return  $dif_string;
	}

}

// Считаем среднее время на выполнение операции
function getAverageTime($arr){
	$seconds = array_sum($arr) / count($arr); // Подсчет среднего количества секунд	
	$dif_string =  "";
	$dif_string .= (floor($seconds / (60 * 60 * 24))) ? floor($seconds / (60 * 60 * 24)) . " дн." : "";
	$dif_string .= (floor(($seconds / (60 * 60)) % 24)) ? floor(($seconds / (60 * 60)) % 24) . " ч." : "";
	$dif_string .= (floor(($seconds / 60) % 60)) ?  floor(($seconds / 60) % 60) . " мин." : "";
	$dif_string .= ($seconds % 60) ?  $seconds % 60 . " сек." : "";

	if(array_sum($arr) > 0){
		return $dif_string; // вывод результата
	}else{
		return '-';
	}	
}
?>

