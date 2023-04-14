<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php");

$stm1 = $pdo->prepare("SELECT user_id, user_role FROM users WHERE 1"); 
$stm1->execute();
$users = $stm1->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($users);  // Кодируем массив в Json
// echo "Выводим данные для " . $_POST['user_id'] . " " . $_POST['option'];
?>