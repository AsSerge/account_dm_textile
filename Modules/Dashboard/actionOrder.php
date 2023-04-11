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
		// Удаляем загруженный файл
		
		$FileToDelete = $_POST['file']; // Получаем файл для удаления
		unlink ($FileToDelete); // Удаляем файл

		// Чистим базу от мусора
		$fileToDelName = basename($FileToDelete); // Получаем имя файла (ID берем из COOKIE)
		$SearchFile = preg_match("/^.+\d+__/", $fileToDelName, $found);
		$SearchFile = $found[0];

		// Получаем ID документа для удаления по имени файла и ID клиента
		$stm = $pdo->prepare("SELECT order_id FROM orders WHERE user_id = :user_id AND file_name LIKE :file_name");
		$stm->execute([
			'user_id' => $_COOKIE['id'],
			'file_name' => $SearchFile."%"
		]);
		$order_id = $stm->fetch(PDO::FETCH_COLUMN); //ID Ордера для удаления записей из базы

		// Чистим orders
		$stm1 = $pdo->prepare("DELETE FROM orders WHERE order_id = :order_id");
		$stm1->execute([
			'order_id' => $order_id
		]);
		// Чистим orders_states
		$stm2 = $pdo->prepare("DELETE FROM orders_states WHERE order_id = :order_id");
		$stm2->execute([
			'order_id' => $order_id
		]);		
	}
}else{
	header("Location: /"); exit;
}

?>