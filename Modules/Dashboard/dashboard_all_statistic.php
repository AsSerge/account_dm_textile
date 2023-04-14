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
		if ($_POST['option'] == 'all_statistic'){
			$stm1 = $pdo->prepare("SELECT user_id, user_role FROM users WHERE 1"); 
			$stm1->execute();
			$users = $stm1->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($users);  // Кодируем массив в Json
		}
	}
}else{
	header("Location: /"); exit;
}
?>