<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
$stmt = $pdo->prepare("SELECT * FROM orders WHERE 1");
$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC); 
echo json_encode($logs);
?>