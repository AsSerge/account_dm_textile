<?php
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
		// Получаем переданный файл (имя)
		$file = $_GET['file'];
		$filePath = $_SERVER['DOCUMENT_ROOT'].'/private_docs/'.$file;	

		// Получаем массив файлов, к которым есть доступ у пользователя
		$stmu = $pdo->prepare("SELECT F.file_name FROM file_access AS FA LEFT JOIN files AS F ON (FA.file_id = F.file_id) WHERE FA.access_type = 1 AND FA.user_id = ?");
		$stmu->execute(array($userdata['user_id']));
		$user_access = $stmu->fetchAll(PDO::FETCH_COLUMN);

		if(in_array($file, $user_access)){
			GetPrivateFile($filePath); // Запускаем функцию загрузки
		}else{
			header("Location: /"); exit; // Отправляем на рагистрацию
		}

	}
}else{
	header("Location: /"); exit;
}

// Функция загрузки файла 
function GetPrivateFile($filePath){

	if(file_exists($filePath)) {
		//Определение информации заголовка
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: 0");
		header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
		header('Content-Length: '. filesize($filePath));
		header('Pragma: public');

		//Очистить выходной буфер системы
		flush();

		//Считайте размер файла
		readfile($filePath);

		//Завершить работу со скриптом
		die();

		echo "FILE Download";
	}else{
		echo "NO FILE";
	}
}

?>