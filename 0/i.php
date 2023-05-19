<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php");

// echo json_encode($orders);

if($_GET['name'] == "Data"){
	$stm = $pdo->prepare("SELECT order_id, order_key, order_type FROM orders WHERE 1");
	$stm->execute();
	$orders = $stm->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($orders);
}else if($_GET['name'] == "Contacts"){
	$stm = $pdo->prepare("SELECT * FROM users WHERE 1");
	$stm->execute();
	$contacts = $stm->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($contacts);
}
?>