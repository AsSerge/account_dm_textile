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
			return ($order_end) ? $this->getDifferencePrivate($order_start, $order_end) : "-";
		}else if($order_end != '' AND $order_start != ''){
			return (int)strtotime($order_end) - (int)strtotime($order_start);
		}else{
			return 0;
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


		// $dif_string .= ($diff->y) ? $diff->y . ":" : "";
		// $dif_string .= ($diff->m) ? $diff->m . ":" : "";
		// $dif_string .= ($diff->d) ? $diff->d . ":" : "";
		// $dif_string .= ($diff->h) ? $diff->h . ":" : "";
		// $dif_string .= ($diff->i) ? $diff->i . ":" : "";
		// $dif_string .= ($diff->s) ? $diff->s . "" : "";


		return  $dif_string;
	}
	// Функция выводаинформации о заказе
	public function getUserInfo($order_id){		
		$stmt = $this->pdo->prepare("SELECT US.user_id, user_name, user_surname, team_name, team_id FROM users AS US LEFT JOIN orders AS ORD ON (US.user_id = ORD.user_id) LEFT JOIN user_teams AS UT ON (US.user_team = UT.team_id) WHERE ORD.order_id = ?");

		$stmt->execute([$order_id]);
		$u = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$user_string = "<a href = '/?user_id=".$u['user_id']."'>".$u['user_name'] ."&nbsp" . $u['user_surname'] . "</a> <strong><a href='/?team_id=".$u['team_id']."'>[".$u['team_name']."]</a></strong>";

		return  $user_string;
	}

}

// Считаем среднее время на выполнение операции
function getAverageTime($arr){
	$cnt = 0; // Считаем только не нулевые значения
	if(gettype($arr) == 'array'){	
		foreach ($arr as $item){
			if ($item !== 0){
				$cnt++;
			}
		}
		$seconds = ($cnt) ? array_sum($arr) / $cnt : array_sum($arr); // Подсчет среднего количества секунд 
		$dif_string =  "";
		$dif_string .= (floor($seconds / (60 * 60 * 24))) ? floor($seconds / (60 * 60 * 24)) . " дн. " : "";
		$dif_string .= (floor(($seconds / (60 * 60)) % 24)) ? floor(($seconds / (60 * 60)) % 24) . " ч. " : "";
		$dif_string .= (floor(($seconds / 60) % 60)) ?  floor(($seconds / 60) % 60) . " мин. " : "";
		$dif_string .= ($seconds % 60) ?  $seconds % 60 . " сек. " : "";

		if(array_sum($arr) > 0){
			return $dif_string; // вывод результата
		}else{
			return '-';
		}
	}
	else{
		return '-';
	}	

}

// Функция получения общего количества заказов у пользователя
function getAllOrdersCount($pdo, $user_id, $state_type){
	$stm = $pdo->prepare("SELECT COUNT(ORD.order_id) FROM orders AS ORD LEFT JOIN orders_states AS ORDST ON (ORD.order_id = ORDST.order_id) WHERE ORD.user_id = :user_id AND ORDST.state_type = :state_type");
	$stm->execute([
		'user_id' => $user_id,
		'state_type' => $state_type
	]);
	$orders_count = $stm->fetch(PDO::FETCH_COLUMN);	
	return $orders_count;
}

// Функция вывода клиентов-топов по поступившим заявкам
function getTopClients($pdo, $state_type){
	$stm = $pdo->prepare("SELECT ORD.user_id, US.user_name, US.user_surname, COUNT(order_key) AS count FROM orders AS ORD LEFT JOIN users AS US ON (ORD.user_id = US.user_id) GROUP BY user_id ASC LIMIT 3");
	$stm->execute();
	$topClients = $stm->fetchAll(PDO::FETCH_ASSOC);

	$topClientsStr = "<table class = 'table table-sm pulse'>";
	foreach ($topClients as $client) {	
		$topClientsStr .= "<tr><td>".$client['user_name']."&nbsp".$client['user_surname']."</td><td>".$client['count']."</td></tr>";
	}
	$topClientsStr .= "</table>";

	return $topClientsStr;
}
