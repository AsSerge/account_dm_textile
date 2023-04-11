<?php
// Получение списка пользователей для менеджера. Менеджер видит только себя и членов своей команды. Определяем комманду по ID
// Соединямся с БД 
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])){
	$query = $pdo->prepare("SELECT * FROM users WHERE user_id = '".intval($_COOKIE['id'])."' LIMIT 1");
	$query->execute();
	$userdata = $query->fetch(PDO::FETCH_LAZY);

	// Проверяем соответствие текущих данных табличным
	if(($userdata['user_hash'] !== $_COOKIE['hash']) and ($userdata['user_id'] !== $_COOKIE['id'])){
		header("Location: /"); exit;
	}else{

		// Получаем команду менеджера, к которой у него есть доступ (руководитель)
		$smt = $pdo->prepare("SELECT user_team FROM users WHERE user_id = :user_id");
		$smt->execute(array(
			'user_id' => $userdata['user_id']
		));
		$team_id = $smt->fetch(PDO::FETCH_COLUMN);

		// $query = $pdo->prepare("SELECT * FROM users AS U LEFT JOIN user_teams AS UT ON (U.user_team = UT.team_id) WHERE U.user_id != 1 AND U.user_id != :user_id AND U.user_team = :user_team");

		$query = $pdo->prepare("SELECT * FROM users AS U LEFT JOIN user_teams AS UT ON (U.user_team = UT.team_id) WHERE U.user_role != 'adm' AND U.user_role != 'mgr' AND U.user_team = :user_team AND user_leader = :user_id");

		$query->execute(array(
			'user_team' => $team_id,
			'user_id' => $userdata['user_id']
		));
		$userdata = json_encode($query->fetchAll(PDO::FETCH_ASSOC));
		echo $userdata;
	}
}else{
	header("Location: /"); exit;
}
?>