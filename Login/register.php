<?php
include($_SERVER['DOCUMENT_ROOT']."/Login/baselogin/line_check.php");
// Страница регистрации нового пользователя
// Соединямся с БД
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
if(isset($_POST['submit']))
{
	$err = [];
	// проверям логин
	if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))
	{
		$err[] = "Логин может состоять только из букв английского алфавита и цифр";
	}
	if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)
	{
		$err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
	}
	// проверяем, не сущестует ли пользователя с таким именем
	$user_login = ClearSQLString($_POST['login']);
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
		// $user_password = md5(md5(trim($_POST['password'])));
		$user_password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
		$stmt = $pdo->prepare("INSERT INTO users SET user_login=".$user_login.", user_password='".$user_password."'");
		$stmt->execute();

		// header("Location: login.php"); exit();
		echo "Пользователь успешно добавлен";
	}
	else
	{
		print "<b>При регистрации произошли следующие ошибки:</b><br>";
		foreach($err AS $error)
		{
			print $error."<br>";
		}
	}
}

?>
<h1>Регистарция пользователей</h1>
<form method="POST">
Логин <input name="login" type="text" required><br>
Пароль <input name="password" type="password" required><br>
<input name="submit" type="submit" value="Зарегистрировать">
</form>