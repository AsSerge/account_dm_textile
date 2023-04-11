<?php
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
include_once($_SERVER['DOCUMENT_ROOT']."/Layout/settings.php"); // Функции сайта
$recovery_hash = $_GET['prh'];
// Получаем почту для восстановления пароля. Если ее нет - пропускаем данную страницу
$stmt = $pdo->prepare("SELECT recovery_login FROM pass_recovery WHERE recovery_hash = ?");
$stmt->execute(array($recovery_hash));
$recovery_login = $stmt->fetchColumn();

if($recovery_login ==""){
	// Переадресовываем браузер на страницу логирования
	header("Location: /"); exit;
}

?>
<!doctype html>
<html lang="ru">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="/css/bootstrap.min.css">
	<link rel="stylesheet" href="/css/style.css">

	<title>Восстановление пароля</title>
<style>
html,
body {
	height: 100%;
}

body {
	display: -ms-flexbox;
	display: flex;
	-ms-flex-align: center;
	align-items: center;
	padding-top: 40px;
	padding-bottom: 40px;
	background-color: #f8f9fa;
}

.pass_error{
    width: 100%;
    height: 10%;
    position: absolute;
    top: 0;
    left: 0;
    overflow: auto;
    background: none;
    border: none;
    outline: none;
}

.pass_error div{
	display: inline-block;
	font-weight: 600;
	color: red;
}

.form-signin {
	width: 100%;
	max-width: 330px;
	padding: 15px;
	margin: auto;
}
.form-signin .checkbox {
	font-weight: 400;
}
.form-signin .form-control {
	position: relative;
	box-sizing: border-box;
	height: auto;
	padding: 10px;
	font-size: 16px;
}
.form-signin .form-control:focus {
	z-index: 2;
}
.form-signin input[type="email"] {
	margin-bottom: -1px;
	border-bottom-right-radius: 0;
	border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
	margin-bottom: 10px;
	border-top-left-radius: 0;
	border-top-right-radius: 0;
}

.info-text{
	font-size: 0.8rem;
}
</style>
</head>
<body class="text-center">


<form class="form-signin" id="pass-recovery" method="POST">
	<img class="mb-4" src="/images/brand/DMT_LOGO.svg" alt="logo" width="70">
	<h1 class="h4 mb-3 font-weight-normal">Восстановление пароля</h1>

	<input type="hidden" value="<?=$recovery_login?>" id="user_login">

	<label for="pass_first" class="sr-only">PassFirst</label>
	<input type="password" id="pass_first" name="pass_first" class="form-control mb-2" placeholder="Новый пароль" required autofocus>

	<label for="pass_second" class="sr-only">PassSecond</label>
	<input type="password" id="pass_second" name="pass_second" class="form-control mb-2" placeholder="Повторите пароль" required autofocus>
	

	<div id="recovery-info"></div>	
	<!-- <button class="btn btn-md btn-primary btn-block" id="submit_" name="submit_" type="submit">Сохранить пароль</button> -->
	<button class="btn btn-md btn-primary btn-block" id="submit_pass" name="submit_pass" type="button">Сохранить пароль</button>

	<p class="mt-3 mb-2 text-muted"><a href = "http://www.dmtextile.ru" target="_blanc">&copy; Dmtextile 2021</a></p>
</form>	
	<script src = "/js/jquery-3.6.0.min.js"></script>
	<script src = "/js/popper.min.js"></script>
	<script src = "/js/bootstrap.min.js"></script>
	<script src = "/js/custom.js"></script>
	<script>
		"use strict";
		$("#submit_pass").attr("disabled","disabled");
		var pass_lengh = 6;

		$("#pass_first").on("keyup", function(){
			var pass_first = $(this).val();
			var pass_second = $("#pass_second").val();
			if(pass_first != pass_second){
				$("#recovery-info").html("<p class='text-danger info-text'>Пароли не совпадают</p>");
				$("#submit_pass").attr("disabled","disabled");
			}else{
				$("#recovery-info").html("");
				$("#submit_pass").removeAttr("disabled");
			}
		});

		$("#pass_second").on("keyup", function(){
			var pass_first = $("#pass_first").val();
			var pass_second = $(this).val();
			if(pass_first != pass_second){
				$("#recovery-info").html("<p class='text-danger info-text'>Пароли не совпадают</p>");
				$("#submit_pass").attr("disabled","disabled");
			}else{
				$("#recovery-info").html("");
				$("#submit_pass").removeAttr("disabled");
			}
		});

		$("#submit_pass").on("click", function(){
			var pass_first = $("#pass_first").val();
			var pass_second = $("#pass_second").val();
			var user_login = $("#user_login").val();
			if(pass_first.length < pass_lengh){
				$("#recovery-info").html("<p class='text-danger info-text'>Пароль не должен быть короче 6 символов</p>");
				$("#pass_first").val("");
				$("#pass_second").val("");
				$("#submit_pass").attr("disabled","disabled");
			}else{
				$.ajax({
				url: '/Login/baselogin/password_set_new.php',
				datatype: 'html',
				type: 'post',
				data: {
					user_login: user_login,
					user_password:pass_first
				},
				success: function (data) {					
					// Идем домой
					$(location).attr('href', '/');
				}
			});
			}
		});

	</script>
</body>
</html>
