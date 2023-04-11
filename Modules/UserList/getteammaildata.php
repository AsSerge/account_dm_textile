<?php
// Соединямся с БД
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo

$team_id = $_POST['user_team'];

$query = $pdo->prepare("SELECT team_mail_1, team_mail_2 FROM user_teams WHERE `team_id` = ?");
$query->execute(array($team_id));
$user_teams_data = json_encode($query->fetchAll(PDO::FETCH_ASSOC));// Все записи
// $userdata = json_encode($query->fetch(PDO::FETCH_ASSOC)); // Одна запись
echo $user_teams_data;
?>