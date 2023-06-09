<?php
header("Content-Type: text/event-stream");
header("Cache-Control: no-cache");
header('Connection: keep-alive');

$id = $_GET['id'];

// Generate random data every 1 second
while (true) {
	$data = rand(1, 100);
	if($data >= 50){	
		echo "data: {$id} - {$data}\n\n";
	}else{
		echo "data: {$id} - {$data} - Маловато будет\n\n";
	}
	ob_flush();
	flush();
	sleep(2);
}
?>