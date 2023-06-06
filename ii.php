<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<h1>Задержка в загрузке</h1>
	<?php
	$file = "test.xlsx";

	echo GetAge($file) . " " . GetAgeUNIX($file);
	echo "<br>";
	echo time();
	echo "<br>";
	echo "Возраст файла " . GetAgeUNIXDiff($file, 24);


	function GetAge($file){
	$dir = $_SERVER['DOCUMENT_ROOT'].'/';
	return date("d.m.Y H:i", filemtime($dir.$file));	
	}

	function GetAgeUNIX($file){
	$dir = $_SERVER['DOCUMENT_ROOT'].'/';
	return filemtime($dir.$file);	
	}

	function GetAgeUNIXDiff($file, $h){
		$dir = $_SERVER['DOCUMENT_ROOT'].'/';
		$fileAge = time() - filemtime($dir.$file);

		$years = floor($fileAge / 31536000);
		$fileAge %= 31536000;

		$days = floor($fileAge / 86400);
		$fileAge %= 86400;

		$hours = floor($fileAge / 3600);
		$fileAge %= 3600;

		$minutes = floor($fileAge / 60);
		$fileAge %= 60;

		if($hours > $h){
			return "> 48 часов";
		}
	}
	?>

</body>
</html>