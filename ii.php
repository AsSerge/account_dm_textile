<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<h1>Ht</h1>
	<p>eere</p>
	<?php
	
	function arg ($word){
		$d = preg_match_all("/\d{2,}/", $word, $matches);
		return $matches;
	}

	print_r(arg("Hello3423 3434 324gdf dgsdgdf"));
	
	?>
</body>
</html>