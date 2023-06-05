<?php
//******************************************************/
// Подготовка и передача файла с шаблоном (цены) клиенту
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


		// Получаем переданный файл (имя и тип выдачи)
		$file = $_GET['file']; // Имя файла
		$filePath = $_SERVER['DOCUMENT_ROOT'].'/private_docs/'.$file;
		GetPrivateFile($filePath); // Запускаем функцию загрузки		
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