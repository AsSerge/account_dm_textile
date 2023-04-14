<?php
//******************************************************/
//* 		Удаление документа из базы и сервера
//******************************************************/
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php");
// Если кэш соотвествует ID - удаляем файл
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])){

	$query = $pdo->prepare("SELECT * FROM users WHERE user_id = '".intval($_COOKIE['id'])."' LIMIT 1");
	$query->execute();
	$userdata = $query->fetch(PDO::FETCH_LAZY);


	// Проверяем соответствие текущих данных табличным
	if(($userdata['user_hash'] !== $_COOKIE['hash']) && ($userdata['user_id'] !== $_COOKIE['id'])){
		header("Location: /"); exit;
	}else if ($userdata['user_role'] == 'adm'){
		// Получаем переданный файл (имя и тип выдачи)
				
		$document_id = $_POST['document_id']; // ID файла
		if ($document_id){
			DeletePublicFile($pdo, $document_id); // Запускаем функцию загрузки
			echo "Документ удален";
		}else{
			echo "Ошибка.";
		}
	}
}else{
	header("Location: /"); exit;
}
// Удаление общедоступного документа по ID
function DeletePublicFile($pdo, $document_id){
	$stm = $pdo->prepare("SELECT document_file FROM public_docs WHERE document_id = :document_id");
	$stm->execute([
		'document_id' => $document_id
	]);
	$file = $stm->fetch(PDO::FETCH_COLUMN);
	// Удаляем файл с диска
	$filePath = unlink($_SERVER['DOCUMENT_ROOT'].'/public_docs/'.$file);
	// Чистим запись в таблице
	$stmd = $pdo->prepare("DELETE FROM public_docs WHERE document_id = :document_id");
	$stmd->execute([
		'document_id' => $document_id
	]);
}
?>