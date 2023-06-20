<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
$stmt = $pdo->prepare("SELECT order_id FROM orders WHERE 1");
$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_COLUMN);// Массив значений БЕЗ заголовков  типа [2,3,4,5,6,7]
echo json_encode($logs); // Кодирование в JSON
?>