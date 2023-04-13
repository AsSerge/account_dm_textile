<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/Layout/engineering.php');
$team_manager_mail = "Goroshko-IA@dmtextile.ru";
$mail = ($testing) ? $tester_mail : $team_manager_mail;
echo $mail;

?>