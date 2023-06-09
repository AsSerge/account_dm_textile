<?php
// УСТАНОВКИ САЙТА

// Функции сайта

//Преобразуем дату в правильный MySql формат
function date_to_mysql($date){
	$date_tmp = explode(".",$date);
	$dete_new = $date_tmp[2]."-".$date_tmp[1]."-".$date_tmp[0];
	return $dete_new;
}

function mysql_to_date($date){
	$date_tmp = explode("-",$date);
	$dete_new = $date_tmp[2].".".$date_tmp[1].".".$date_tmp[0];
	return $dete_new;
}

// Перевод размерности файлов
function get_file_size($bytes){
	if ( $bytes < 1000 * 1024 ) {
		return number_format( $bytes / 1024, 2 ) . " KB";
	}
	elseif ( $bytes < 1000 * 1048576 ) {
		return number_format( $bytes / 1048576, 2 ) . " MB";
	}
	elseif ( $bytes < 1000 * 1073741824 ) {
		return number_format( $bytes / 1073741824, 2 ) . " GB";
	}
	else {
		return number_format( $bytes / 1099511627776, 2 ) . " TB";
	}
}

// Запись логов
function WriteLog($pdo, $creative_id, $user_id, $log_content){
	$stmt = $pdo->prepare("INSERT INTO base_logs SET creative_id = :creative_id, user_id = :user_id, log_content = :log_content");
	$stmt->execute(array(
		'creative_id'=>$creative_id,
		'user_id'=>$user_id,
		'log_content'=>$log_content
	));
}
?>