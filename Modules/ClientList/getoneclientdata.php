<?php
// Соединямся с БД
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo

$user_id = $_POST['user_id'];

$query = $pdo->prepare("SELECT * FROM users WHERE `user_id` = ?");
$query->execute(array($user_id));
// $userdata = json_encode($query->fetchAll(PDO::FETCH_ASSOC));// Все записи
$userdata = json_encode($query->fetch(PDO::FETCH_ASSOC)); // Одна запись
echo $userdata;
?>