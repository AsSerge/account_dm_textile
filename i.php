<?php

$team_manager_mail = "Goroshko-IA@dmtextile.ru";
include_once($_SERVER['DOCUMENT_ROOT'].'/Layout/engineering.php'); // Блок тестирования
$mail = ($testing_mode) ? $tester_mail : $team_manager_mail;
echo $mail;

?>