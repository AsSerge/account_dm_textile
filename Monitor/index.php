<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Счетчик</title>
</head>
<body>
	<h1>Тестируем</h1>
	<div id="one"></div>

<script src = "/js/jquery-3.6.0.min.js"></script>
	<script src = "/js/popper.min.js"></script>	
	<script src = "/js/bootstrap.min.js"></script>	
	<script src = "/js/datatables.min.js"></script>
	<script src = "/js/dataTables.bootstrap4.min.js"></script>
	<script src = "/js/bootstrap-datepicker.min.js"></script>
	<!-- Кастомизация интерфейса -->
	<script src = "/js/custom.js"></script>	
	<script src = '/js/jquery.form.min.js'></script>
<script>

$(document).ready(function () {
	
	var source = new EventSource("monitor.php?id=8");
	source.onmessage = function(event){
		$("#one").text("Новое число: " + event.data);
	}

});
</script>
</body>
</html>