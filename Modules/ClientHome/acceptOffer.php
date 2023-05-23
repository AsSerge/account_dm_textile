<?php
//*******************************************************************/
// Безусловное принятие предложения (оффера) через интерфейс клиента
//*******************************************************************/
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php");
// include_once($_SERVER['DOCUMENT_ROOT'].'/Assets/PHPMailer/PHPMailerFunction.php'); // Почтальен Печкин
include_once($_SERVER['DOCUMENT_ROOT'].'/OrderToWork/functions.php'); // Функции API

if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{

	$access_line = $_POST['access_line'];
	$operator = $_POST['operator'];
	$operation = $_POST['operation'];

	// Получаем  информацию об ордере по его хэшу
	extract(order($pdo, $access_line), EXTR_PREFIX_SAME, "temp");  // Достаем переменные из массива
	setOrderState($pdo, $order_id, "5", ''); // Пишем информацию в базу (стстус 5 - принятие предложения в первом рассмотреии)
	sendMailToLogist($team_manager_mail, $order_key, $order_type, $user_login, $user_name, $user_surname, $user_id, $access_line); // Отсылаем письмо менеджеру	

}else{
	header("Location: ../Login/baselogin/login.php"); exit;
}
?>