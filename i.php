<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); // Подключаем базу

// Считаем количество повторных предложений (если они есть)

$stmt = $pdo->prepare("SELECT COUNT(state_type) FROM orders_states WHERE order_id = :order_id AND state_type = 3");
$stmt->execute([
	'order_id' => 4
]);
$result = $stmt->fetch(PDO::FETCH_COLUMN);
echo $result;

echo "<br>";

/* Это изменения для внесения на улаленный репозиторий 11.04.2023 17:19*/

/* Работает отправка

(Отправка Офера - предложения)
Sender	: 	GRMP
Reciver	:	Любой

(Отправка Офера - предложения)
Sender	: 	GRMP
Reciver	:	GRMP

(Отправка ЗАКАЗА, рассылка по списку Только если один из участников в GRMP)
Sender	: 	Любой
Reciver	:	GRMP


(Рассылка по списку, если хотя бы один из участников НЕ в GRMP)
Не работает отправка
Sender	: 	Любой
Reciver	:	Любой

*/
?>