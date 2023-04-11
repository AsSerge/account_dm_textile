<?php
// Изменени состояния переключателя доступа
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php");
// Проверяем павомочность пользователя
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])){
	$query = $pdo->prepare("SELECT * FROM users WHERE user_id = '".intval($_COOKIE['id'])."' LIMIT 1");
	$query->execute();
	$userdata = $query->fetch(PDO::FETCH_LAZY);

	// Проверяем соответствие текущих данных табличным
	if(($userdata['user_hash'] !== $_COOKIE['hash']) and ($userdata['user_id'] !== $_COOKIE['id'])){
		header("Location: /"); exit;
	}else{
		// Получаем переданный файл (имя)
		$user_id = $_POST['user_id'];
		$file_id = $_POST['file_id'];
		$access_type = $_POST['access_type'];
		$access_column = $_POST['access_column'];
		// Проверяем существование записи доступа
		$stm = $pdo->prepare("SELECT * FROM file_access WHERE file_id = :file_id AND user_id = :user_id");
		$stm->execute(array(
			'file_id' => $file_id,
			'user_id' => $user_id
		));
		$access = $stm->fetch(PDO::FETCH_COLUMN);
		// Проверка наличия установок доступа
		if($access != 0 && $access_column == 'access_type'){
			$stmu = $pdo->prepare("UPDATE file_access SET setter_id = :setter_id, access_type = :access_type WHERE file_id = :file_id AND user_id = :user_id");
			$stmu->execute(array(
				'setter_id' => $userdata['user_id'],
				'file_id' => $file_id,
				'user_id' => $user_id,
				'access_type' => $access_type
			));
		}else if ($access_column == 'access_type'){
			$stma = $pdo->prepare("INSERT INTO file_access SET setter_id = :setter_id, file_id = :file_id, user_id = :user_id, access_type = :access_type");
			$stma->execute(array(
				'setter_id' => $userdata['user_id'],
				'file_id' => $file_id,
				'user_id' => $user_id,
				'access_type' => $access_type
			));
		}else if($access != 0 && $access_column == 'sending_type'){
			$stmu = $pdo->prepare("UPDATE file_access SET setter_id = :setter_id, sending_type = :access_type WHERE file_id = :file_id AND user_id = :user_id");
			$stmu->execute(array(
				'setter_id' => $userdata['user_id'],
				'file_id' => $file_id,
				'user_id' => $user_id,
				'access_type' => $access_type
			));

		}else if($access_column == 'sending_type'){
			$stma = $pdo->prepare("INSERT INTO file_access SET setter_id = :setter_id, file_id = :file_id, user_id = :user_id, sending_type = :access_type");
			$stma->execute(array(
				'setter_id' => $userdata['user_id'],
				'file_id' => $file_id,
				'user_id' => $user_id,
				'access_type' => $access_type
			));
		}
	}
}else{
	header("Location: /"); exit;
}

?>