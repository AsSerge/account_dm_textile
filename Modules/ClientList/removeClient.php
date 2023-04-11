<?php
// Соединямся с БД
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
$user_to_del = $_POST['user_id'];

$query = $pdo->prepare("DELETE FROM `users` WHERE `user_id` = ?");
$query->execute(array($user_to_del));

$queryDel = $pdo->prepare("DELETE FROM `file_access` WHERE `user_id` = ?");
$queryDel->execute(array($user_to_del));

echo $user_to_del;

?>