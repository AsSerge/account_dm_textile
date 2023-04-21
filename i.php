<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php");
echo "<h1>Время...</h1>";


$stat = new getStatistic($pdo);

$stm = $pdo->prepare("SELECT order_id, order_key,  FROM orders WHERE 1");
$stm->execute();
$orders = $stm->fetchAll(PDO::FETCH_ASSOC);
echo "<table>";
echo "<tr><th>Заказ</th><th>Принят в работу</th><th>Отправлено первое предложение</th><th>Время исполнения</th></tr>";
foreach($orders as $ord){
	echo "<tr><td>". $ord['order_key'] . "</td><td>". $stat->getDifference($ord['order_id'],0,1,true) . "</td><td>" .$stat->getDifference($ord['order_id'],0,2,true). "</td><td>" .$stat->getDifference($ord['order_id'],0,5,true). "</td></tr>";
	$arr1[] = $stat->getDifference($ord['order_id'],0,1,false);
	$arr2[] = $stat->getDifference($ord['order_id'],0,2,false);
	$arr3[] = $stat->getDifference($ord['order_id'],0,5,false);
}

echo "<tr>";
echo "<td>Среднее время</td>";
echo "<td>" .getAverageTime($arr1). "</td>";
echo "<td>" .getAverageTime($arr2). "</td>";
echo "<td>" .getAverageTime($arr3). "</td>";
echo "<tr>";
echo "</table>";








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