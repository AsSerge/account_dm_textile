<?php
//*************************************/
// Подготовка и передача файла логисту
//*************************************/
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
		$link_type = $_GET['link_type']; // Тип выдачи

		if($link_type == "order"){

			$stmu = $pdo->prepare("SELECT user_id FROM orders WHERE file_name = ?");
			$stmu->execute([$file]);
			$user_dir = $stmu->fetch(PDO::FETCH_COLUMN);
			
			$filePath = $_SERVER['DOCUMENT_ROOT'].'/uploaded_documents/'.$user_dir.'/'.$file; // Путь к файлам заказов и оферов
			GetPrivateFile($filePath); // Запускаем функцию загрузки

		}else if($link_type='offer'){
			$filePath = $_SERVER['DOCUMENT_ROOT'].'/uploaded_documents/'.$_GET['user_id'].'/'.$file; // Путь к файлам заказов и оферов
			GetPrivateFile($filePath); // Запускаем функцию загрузки
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