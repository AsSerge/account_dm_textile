<?php
// Получение списка почтовых ящиков для select при регистрации ПОЛЬЗОВАТЕЛЯ
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php");

$team_id = $_POST['user_team'];

$query = $pdo->prepare("SELECT team_mail_1, team_mail_2 FROM user_teams WHERE team_id = ?");
$query->execute([$team_id]);
$mail_data = $query->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($mail_data);


?>