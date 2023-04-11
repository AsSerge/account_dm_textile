<?php
// Скрипт проверки
// Соединямся с БД
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo

if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
	$query = $pdo->prepare("SELECT * FROM users WHERE user_id = '".intval($_COOKIE['id'])."' LIMIT 1");
	$query->execute();
	$userdata = $query->fetch(PDO::FETCH_LAZY);
	
	if(($userdata['user_hash'] !== $_COOKIE['hash']) and ($userdata['user_id'] !== $_COOKIE['id']))
	{
		setcookie("id", "", time() - 3600*24*30*12, "/");
		setcookie("hash", "", time() - 3600*24*30*12, "/", null, null, true); // httponly !!!
		print "Хм, что-то не получилось";
	}
	else if($userdata['user_role'] == 'adm')
	{		
		header("Location: /"); exit;
	}
	else{
		header("Location: /"); exit;
	}
}
else
{
	print "Включите куки";
}
?>