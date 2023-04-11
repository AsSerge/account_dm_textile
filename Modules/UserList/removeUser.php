<?php
// Соединямся с БД
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
$user_to_del = $_POST['user_id'];
// Удаляем клиента из базы
$query = $pdo->prepare("DELETE FROM `users` WHERE `user_id` = ?");
$query->execute(array($user_to_del));
// Очищаем список разрешений для клиента
$queryDel = $pdo->prepare("DELETE FROM `file_access` WHERE `user_id` = ?");
$queryDel->execute(array($user_to_del));
// Удаляем каталог клиента с заказами
$dirToDel = $_SERVER['DOCUMENT_ROOT']. '/uploaded_documents/' . $user_to_del;
delDir($dirToDel);
// Функция очистки каталога с подкаталогами
function delDir($dir){
	$files = array_diff(scandir($dir), ['.', '..']);
	foreach ($files as $file) {
		(is_dir($dir.'/'.$file)) ? delDir($dir.'/'.$file) : unlink($dir.'/'.$file);
	}
	return rmdir($dir);
}



echo $user_to_del;

?>