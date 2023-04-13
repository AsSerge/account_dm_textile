<?php
// *************************************************************
// **      Загрузка общих документов администратором          **
// *************************************************************
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php");  // Подключаемся к базе

if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])){

	$query = $pdo->prepare("SELECT * FROM users WHERE user_id = '".intval($_COOKIE['id'])."' LIMIT 1");
	$query->execute();
	$userdata = $query->fetch(PDO::FETCH_LAZY);

	// Проверяем соответствие текущих данных табличным
	if(($userdata['user_hash'] !== $_COOKIE['hash']) and ($userdata['user_id'] !== $_COOKIE['id'])){
		header("Location: /"); exit;
	}else{
		// Записываем загруженный файл в папку
		$document_description = ClearMessageString($_POST['message_body']);
		$filename = $_FILES['upload_file']['name']; // Реальное имя файла
		$filedir = $_SERVER['DOCUMENT_ROOT'].'/public_docs/';

		if(file_exists($filedir.$filename)){
			$newfilename = SafeFile($filedir.$filename); // Получаем ПУТЬ к новому файлу
			move_uploaded_file($_FILES['upload_file']['tmp_name'], $newfilename); // Кладем нужный файл в папку клиента
			$document_file = basename($newfilename);
			writeFileInfo($pdo, $document_file, $document_description); //Пишем информацию в базу
			header('location: ' . $_SERVER['HTTP_REFERER']); exit;
		}else{
			move_uploaded_file($_FILES['upload_file']['tmp_name'], $filedir.$filename); // Кладем нужный файл в папку клиента
			$document_file = basename($filedir.$filename);
			writeFileInfo($pdo, $document_file, $document_description); //Пишем информацию в базу
			header('location: ' . $_SERVER['HTTP_REFERER']); exit;
		}
	}
}else{
	header("Location: /"); exit;
}

// Функция записи информации о файле в базу

function writeFileInfo($pdo, $document_file, $document_description){
	$query = $pdo->prepare("INSERT INTO public_docs SET document_file = :document_file, document_description = :document_description");
	$query->execute([
		'document_file' => $document_file,
		'document_description' => $document_description
	]);

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

// Функция безопасного сохранения файла (без перезаписи)
function SafeFile($filename){
	$dir = dirname($filename);
	$info = pathinfo($filename);
	$name = $dir . '/' . $info['filename']; 
	$prefix = '';
	$ext = (empty($info['extension'])) ? '' : '.' . $info['extension'];

	if (is_file($name . $ext)) {
		$i = 1;
		$prefix = '_' . $i;
		while (is_file($name . $prefix . $ext)) {
			$prefix = '_' . ++$i;
		}
	}
	return $name . $prefix . $ext;
}

?>