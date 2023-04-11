<?php
// Соединямся с БД
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
// Страница регистрации нового пользователя
if(isset($_POST['submit']))
{
	$err = [];
	// проверям логин (Электронная почта)	

	if(!preg_match('/^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9А-Яа-я]{1}[-0-9А-я\.]{1,}[0-9А-Яа-я]{1}))@([-A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/u', $_POST['user_login']))
	{
		$err[] = "Логин - это адрес вашей элестронной почты";
	}
	if(strlen($_POST['user_login']) < 3 or strlen($_POST['user_login']) > 80)
	{
		$err[] = "Логин должен быть не меньше 3-х символов и не больше 80";
	}

	if(!preg_match("/[^а-я]+/msi", $_POST['user_name']))
	{
		$err[] = "Имя должно состоять из букв и цифр";
	}

	if(strlen($_POST['user_name']) < 3 or strlen($_POST['user_name']) > 80)
	{
		$err[] = "Имя должно быть не меньше 3-х символов и не больше 80";
	}

	if(!preg_match("/[^а-я]+/msi", $_POST['user_surname']))
	{
		$err[] = "Фамилия должна состоять из букв и цифр";
	}

	if(strlen($_POST['user_surname']) < 3 or strlen($_POST['user_surname']) > 80)
	{
		$err[] = "Фамилия должна быть не меньше 3-х символов и не больше 80";
	}

	if(strlen($_POST['user_role']) == "")
	{
		$err[] = "Необходимо добавить роль пользователя";
	}

	if(strlen($_POST['user_team']) == "")
	{
		$err[] = "Необходимо добавить команду для пользователя";
	}

	// проверяем, не сущестует ли пользователя с таким именем
	$user_login = ClearSQLString($_POST['user_login']);
	$user_login = $pdo->quote($user_login);
	$users = $pdo->prepare("SELECT * FROM users WHERE user_login={$user_login}");
	$users->execute();

	
	if(count($users->fetchAll(PDO::FETCH_ASSOC)) > 0)
	{
		$err[] = "Пользователь с таким логином уже существует в базе данных";
	}
	// Если нет ошибок, то добавляем в БД нового пользователя
	if(count($err) == 0)
	{
		// Убераем лишние пробелы, делаем двойное хеширование и добавляем пользователя в базу
		$user_name = ClearSQLString($_POST['user_name']);
		$user_surname = ClearSQLString($_POST['user_surname']);
		$user_role = $_POST['user_role'];
		$user_team = $_POST['user_team'];
		$team_manager_mail = $_POST['team_manager_mail'];

		// $user_password = md5(md5(trim($_POST['user_password'])));
		$user_password = password_hash(trim($_POST['user_password']), PASSWORD_BCRYPT);
		$stmt = $pdo->prepare("INSERT INTO users SET user_login=".$user_login.", user_password='".$user_password."', user_name='".$user_name."', user_surname='".$user_surname."', user_role='".$user_role."', user_team='".$user_team."', user_leader=0, team_manager_mail='".$team_manager_mail."'");
		$stmt->execute();

		// header("Location: login.php"); exit();
		$result = array(
			'name' => $user_name,
			'surname' => $user_surname,
			'mess'=> ""
		);
		// echo json_encode($result);
	}
	else
	{		
		$result = array(
			'name' => "",
			'surname' => "",
			'mess'=> $err
		);
		// echo json_encode($result);
	}
	echo json_encode($result);
}
else{
	echo "Иди ф жопу!";
}
?>