<?php
// Скрипт линейной проверки
// Соединямся с БД
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
	
	// $query = $pdo->prepare("SELECT * FROM users WHERE user_id = '".intval($_COOKIE['id'])."' LIMIT 1");
	$query = $pdo->prepare("SELECT * FROM users AS U LEFT JOIN user_teams AS UT ON (U.user_team = UT.team_id) WHERE U.user_id = '".intval($_COOKIE['id'])."'");

	$query->execute();
	$userdata = $query->fetch(PDO::FETCH_LAZY);

	$user_id = $userdata['user_id']; // Роль пользователя
	$user_role = $userdata['user_role']; // Роль пользователя
	$user_name = $userdata['user_name']; // Имя пользователя
	$user_surname = $userdata['user_surname']; // Фамилия пользователя
	$user_team_id = $userdata['user_team']; // Имя Комманды
	$user_team_name = $userdata['team_name']; // Имя Комманды
	$user_leader = $userdata['user_leader']; // ID руководителя части команды
	

	switch ($user_role){
		case "adm":
			$user_role_description = "Администратор";
			break;
		case "mgr":
			$user_role_description = "Менеджер";
			break;
		case "kln":
			$user_role_description = "Клиент";
			break;
		case "lgs":
			$user_role_description = "Логист";
			break;
	}
	
	if(($userdata['user_hash'] !== $_COOKIE['hash']) and ($userdata['user_id'] !== $_COOKIE['id']))
	{
		setcookie("id", "", time() - 3600*24*30*12, "/");
		setcookie("hash", "", time() - 3600*24*30*12, "/", null, null, true); // httponly !!!
		
		// Переадресовываем браузер на страницу логирования
		header("Location: /"); exit;

	}
}else{
	header("Location: ../Login/baselogin/login.php"); exit;
}
?>