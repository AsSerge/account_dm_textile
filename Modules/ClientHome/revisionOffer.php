<?php
//*******************************************************************/
// Безусловное запрос на регенерацию предложения (оффера) через интерфейс клиента
//*******************************************************************/
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php");
include_once($_SERVER['DOCUMENT_ROOT'].'/Assets/PHPMailer/PHPMailerFunction.php'); // Почтальен Печкин
include_once($_SERVER['DOCUMENT_ROOT'].'/OrderToWork/functions.php'); // Функции API

if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
	$access_line = $_POST['access_line_rev'];  // Получили хэш ордера из формы
	$message_body = ClearMessageString($_POST['message_body_rev']);  // Получили сообщение для отправки

	// Получаем  информацию об ордере по его хэшу
	extract(order($pdo, $access_line), EXTR_PREFIX_SAME, "temp");  // Достаем переменные из массива

	// Проверяем, получили ли мы файл
	if($_FILES['upload_file_rev']['tmp_name']){
		$filename = $_FILES['upload_file_rev']['name']; // Реальное имя файла запроса на изменение

		// Определяем части файла для переименования
		$file_string_out = preg_match('/(.+)\.(xls|xlsx)$/', $filename, $parts_matches); //$parts_matches[1] - имя, $parts_matches[2] - расширение

		// Помещаем файл запроса REVISION в ту же папку, где лежат файлы заказа ORDER и файлы предложения OFFER		
		$user_dir = $_SERVER['DOCUMENT_ROOT'].'/uploaded_documents/' . $user_id;

		// Формирование файла Запроса на изменение 
		$revision_key = $order_key."_".$order_type; // Ревизион-кей соответствует его ключу ордера
		$file_prefix = $revision_key."_REVISION_";
		$file_in =  $file_prefix . "." . $parts_matches[2]; // Переименовываем файл запроса (убираем имя файла - оставляем расширение)
		$target_file =  $user_dir . "/" . $file_in; // Результирующий файл
		
		move_uploaded_file($_FILES['upload_file_rev']['tmp_name'], $target_file); // Кладем нужный файл в папку клиента
	}

	// Отправляем сообщение ЛОГИСТУ ОТ КЛИЕНТА с запросом на изменение Оффера
	include_once($_SERVER['DOCUMENT_ROOT'].'/Layout/engineering.php'); // Блок тестирования
	$mail = ($testing_mode) ? $tester_mail : $team_manager_mail;
	// $mail = 'Tsvetkov-SA@grmp.ru'; // !!!!!!!!!!!!!!!!!!!!!!!!!! ЗАМЕНИТЬ  !!!!!!!!!!!!!!!!!!!!!!!!!! 
	// $mail = $team_manager_mail;
	$subject = $order_key ."_".$order_type."_ORDER_". "[ЗАПРОС НА ИЗМЕНЕНИЕ]";	
	$message = "Добрый день!";
	$message .= "\n\rПо заказу ".$order_key." необходимы правки!";
	if($message_body != ""){
			$message .= "\n\r<br><strong>Комментарий: </strong>";
			$message .= "{$message_body}";
	}
	$server_adress = ($_SERVER['HTTPS']) ? "https://". $_SERVER['SERVER_NAME'] : "http://". $_SERVER['SERVER_NAME']; // Задаем адрес сервера с протоколом
	$message .= "\n\r<br>>> Отправить новое предложение: <a href='{$server_adress}/OrderToWork/?access_line={$access_line}&operator=mgr&operation=sendnextoffer'>{$server_adress}/OrderToWork/?access_line={$access_line}&operator=mgr&operation=sendnextoffer</a>";
	$message .= "\n\r<br>>> Отменить зкакз: <a href='{$server_adress}/OrderToWork/?access_line={$access_line}&operator=mgr&operation=cancelorder'>{$server_adress}/OrderToWork/?access_line={$access_line}&operator=mgr&operation=cancelorder</a>";
	$sender_mail = $user_login; // Заказчик почта
	$sender_name = $user_name . " " . $user_surname; // Заказчик ФИО
	if($_FILES['upload_file_rev']['tmp_name']){
		// Отправляем письмо с вложением
		SendMailGRMPAttachment($mail, $subject, $message, $sender_mail, $sender_name, $target_file);
	}else{
		// Отправляем письмо без вложения
		SendMailGRMP($mail, $subject, $message, $sender_mail, $sender_name);
	}

	// Устанавливаем статус заказа	
	setOrderState($pdo, $order_id, "3", $message_body); // Пишем информацию в базу (статус 3 - Запрос доработки)	
	
	// Возвращаемся на страницу вызова
	header("Location: /"); exit; // Возврат на страницу вызова

}else{
	header("Location: ../Login/baselogin/login.php"); exit;
}




// Функция очистки строки
function ClearMessageString($string){
	$eitem = array('strong','lt','gt','sub','&','amp');
	$eitemAllowed = array("\r\n", "\n", "\r");
	$string = htmlentities(htmlspecialchars($string));
	$string = str_replace($eitem, "", $string);
	$string = str_replace($eitemAllowed, "<br>", $string);
	$string = trim($string);
	return $string;
}
?>