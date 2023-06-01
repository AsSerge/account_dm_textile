<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php");
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])){

	$query = $pdo->prepare("SELECT * FROM users WHERE user_id = '".intval($_COOKIE['id'])."' LIMIT 1");
	$query->execute();
	$userdata = $query->fetch(PDO::FETCH_LAZY);

	// Проверяем соответствие текущих данных табличным
	if(($userdata['user_hash'] !== $_COOKIE['hash']) and ($userdata['user_id'] !== $_COOKIE['id'])){
		header("Location: /"); exit;
	}else{
		// Запрос статистики для карты 1
		if ($_POST['option'] == 'users_statistic'){
			$stm1 = $pdo->prepare("SELECT user_id, user_role FROM users WHERE 1"); 
			$stm1->execute();
			$users = $stm1->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($users);  // Кодируем массив в Json
		}elseif($_POST['option'] == 'orders_statistic'){
			$stm = $pdo->prepare("SELECT order_id FROM orders WHERE 1");
			$stm->execute();
			$orders = $stm->fetchAll(PDO::FETCH_ASSOC);

			$arr = []; // Массив ключей
			foreach($orders as $ord){
				$arr[] = getMaxState($pdo, $ord['order_id']); // Заполняем массив
			}
			$arr_states = array_count_values($arr); // Считаем количество каждого ключа в списке
			$return_string = "<table class='table table-sm pulse'>";
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
				$return_string .=  "<tr><td>" . $status_string . "</td><td>" . $value . "</td></tr>";
			}
			$return_string .=  "<tfoot><tr><td>Всего заказов:</td><td>" . count($arr) . "</td></tr></tfoot>";
			$return_string .= "</table>";
			echo $return_string;


		}elseif($_POST['option'] == 'groups_statistic'){
			$stm = $pdo->prepare("SELECT * FROM user_teams WHERE 1");
			$stm->execute();
			$teams = $stm->fetchAll(PDO::FETCH_ASSOC);
			$return_string = "<table class='table table-sm pulse'>"; 
			foreach($teams as $t){
				$t_name = $t['team_name'];
				$t_mail1 = $t['team_mail_1'];
				$t_mail2 = $t['team_mail_2'];
				$user_team_count = getUsersInTeam($pdo, $t['team_id']);
				$return_string .= "<tr><td>$t_name ($user_team_count)</td><td><a href='mailto:$t_mail1'>$t_mail1</a></td><td><a href='mailto:$t_mail2'>$t_mail2</a></td></tr>";
			}
			$return_string .= "</table>";
			echo $return_string;


		}elseif($_POST['option'] == 'clients_statistic'){
			$stm = $pdo->prepare("SELECT user_id, user_login, user_name, user_surname, user_team, team_name FROM users AS US LEFT JOIN user_teams AS UST ON (US.user_team = UST.team_id) WHERE US.user_role = 'kln'");
			$stm->execute();
			$user = $stm->fetchAll(PDO::FETCH_ASSOC);
			$return_string = "<table class='table table-sm pulse'>";

			$return_string .= "<thead><tr><th width='20%'>Клиент</th><th>Группа</th><th>Поступило заявок</th><th>Взят в работу</th><th>Отменено</th><th>Принято</th><th>Формируется</th></tr></thead>";

			foreach($user as $usr){
				$return_string .= "<tr>\n\r";

				$return_string .= "<td>" . $usr['user_name'] . " ". $usr['user_surname'] . " [" . $usr['user_login'] . "]</td>";
				$return_string .= "<td>" . $usr['team_name'] . "</td>";
				$return_string .= "<td>" . getAllOrdersCount($pdo, $usr['user_id'], '0') . "</td>";
				$return_string .= "<td>" . getAllOrdersCount($pdo, $usr['user_id'], '1') . "</td>";
				$return_string .= "<td>" . getAllOrdersCount($pdo, $usr['user_id'], '7') . "</td>";
				$return_string .= "<td>" . getAllOrdersCount($pdo, $usr['user_id'], '5') . "</td>";
				$return_string .= "<td>" . getAllOrdersCount($pdo, $usr['user_id'], '6') . "</td>";

			$return_string .= "</tr>\n\r";
			}

			$return_string .= "</tr>";
			echo $return_string;
		}
	}
}else{
	header("Location: /"); exit;
}

// Функция получения актуального статуса заказа
function getMaxState($pdo, $order_id){
	$stm = $pdo->prepare("SELECT MAX(state_type) FROM orders_states WHERE order_id = ?");
	$stm->execute([$order_id]);
	$order_state = $stm->fetch(PDO::FETCH_COLUMN);	
	return $order_state;
}

// Функция получения количества пользователей в команде
function getUsersInTeam($pdo, $user_team){
	$stm = $pdo->prepare("SELECT COUNT(user_id) FROM users WHERE user_team = ? AND user_role = 'kln'");
	$stm->execute([$user_team]);
	$user_team_count = $stm->fetch(PDO::FETCH_COLUMN);
	return $user_team_count;
}

// Функция получения общего количества заказов у пользователя
function getAllOrdersCount($pdo, $user_id, $state_type){
	if ($state_type == '1'){
		$stm = $pdo->prepare("SELECT COUNT(ORD.order_id) FROM orders AS ORD LEFT JOIN orders_states AS ORDST ON (ORD.order_id = ORDST.order_id) WHERE ORD.user_id = :user_id AND ORDST.state_type = :state_type");
	}else{	
		$stm = $pdo->prepare("SELECT COUNT(ORD.order_id) FROM orders AS ORD LEFT JOIN orders_states AS ORDST ON (ORD.order_id = ORDST.order_id) WHERE ORD.user_id = :user_id AND ORDST.state_type = :state_type");
	}	
	$stm->execute([
		'user_id' => $user_id,
		'state_type' => $state_type
	]);
	$orders_count = $stm->fetch(PDO::FETCH_COLUMN);	
	return $orders_count;
}

?>
