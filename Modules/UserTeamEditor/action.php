<?php
//******************************************************/
// Подготовка и запись новой коммандной электронной почты 
//******************************************************/
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php");
// Отлавливаем HASH сессии (постоянно меняется)
// Если кэш соотвествует ID - отдаем файл на загрузку
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])){

	$query = $pdo->prepare("SELECT * FROM users WHERE user_id = '".intval($_COOKIE['id'])."' LIMIT 1");
	$query->execute();
	$userdata = $query->fetch(PDO::FETCH_LAZY);

	// Проверяем соответствие текущих данных табличным
	if(($userdata['user_hash'] !== $_COOKIE['hash']) and ($userdata['user_id'] !== $_COOKIE['id'])){
		header("Location: /"); exit;
	}else{

		$team_id = $_POST['team_id']; // ID комманды
		$mail_address = $_POST['mail_address']; // Новый адрес почты командв
		$mail_id = $_POST['mail_id']; // ID подкомманды (team_mail_1 или team_mail_2)

		if($mail_id == '1'){
			$stm = $pdo->prepare("UPDATE user_teams SET team_mail_1 = :mail_address WHERE team_id = :team_id");
		}else if ($mail_id == '2'){
			$stm = $pdo->prepare("UPDATE user_teams SET team_mail_2 = :mail_address WHERE team_id = :team_id");
		}

		$stm->execute([
			'mail_address' => $mail_address,
			'team_id' => $team_id 
		]);

		echo "Запись успешна! " , $mail_address ," В ", "team_mail",$mail_id, " Группа: ", $team_id ;
	}
}else{
	header("Location: /"); exit;
}
?>