<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта
$user_login = $_POST['user_login'];
$user_password = $_POST['user_password'];

// Готовим пароль
$user_password = password_hash(trim($_POST['user_password']), PASSWORD_BCRYPT);
// Правим таблицу
$stmt = $pdo->prepare("UPDATE users SET user_password = :user_password WHERE user_login = :user_login");
$stmt->execute(array(
	'user_login' => $user_login,
	'user_password' => $user_password
));
echo "Поменяли пароль " . $user_login . " ".$user_password;
?>