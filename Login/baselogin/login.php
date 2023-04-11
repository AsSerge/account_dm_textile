<?php
// Страница авторизации

// Функция для генерации случайной строки
function generateCode($length=6) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
	$code = "";
	$clen = strlen($chars) - 1;
	while (strlen($code) < $length) {
			$code .= $chars[mt_rand(0,$clen)];
	}
	return $code;
}

// Соединямся с БД
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo

if(isset($_POST['submit']))
{
	// Вытаскиваем из БД запись, у которой логин равняеться введенному

	$user_login = ClearSQLString($_POST['login']);
	$user_login = $pdo->quote($user_login);
	
	$query = $pdo->prepare("SELECT * FROM users WHERE `user_login` = {$user_login}");
	$query->execute();
	$data = $query->fetch(PDO::FETCH_LAZY);

	// Сравниваем пароли
	// if($data['user_password'] === md5(md5($_POST['password'])))
	if(password_verify($_POST['password'], $data['user_password']))	
	{
		// Генерируем случайное число и шифруем его
		$hash = md5(generateCode(10));

		// Записываем в БД новый хеш авторизации и IP
		$query = $pdo->prepare("UPDATE users SET user_hash='".$hash."' WHERE user_id='".$data['user_id']."'");
		$query->execute();		

		// Ставим куки
		setcookie("id", $data['user_id'], time()+60*60*24*30, "/");
		setcookie("hash", $hash, time()+60*60*24*30, "/", null, null, true); // httponly !!!

		// Переадресовываем браузер на страницу проверки нашего скрипта
		header("Location: check.php"); exit();
	}
	else
	{
		echo "<div class='pass_error'><div>Вы ввели неправильный логин или пароль</div></div>";
	}
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
	<!-- <link rel="stylesheet" href="/css/datatables.min.css">	
	<link rel="stylesheet" href="/css/dataTables.bootstrap4.min.css">	 -->
	<link rel="stylesheet" href="/css/style.css">

	<title>Вход в систему</title>
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

.ihiden{
	opacity: 0;
	display: none;
}

.info-text{
	font-size: 0.8rem;
}
</style>
</head>
<body class="text-center">

<form class="form-signin" id="login" method="POST">
	<img class="mb-4" src="/images/brand/DMT_LOGO.svg" alt="logo" width="70">
	<h1 class="h4 mb-3 font-weight-normal">Авторизация</h1>
	<label for="inputEmail" class="sr-only">Email address</label>
	<input type="email" id="inputEmail" name="login" class="form-control" placeholder="Email адрес" required autofocus>
	<label for="inputPassword" class="sr-only">Password</label>
	<input type="password" id="inputPassword" name="password" class="form-control" placeholder="Пароль" required>
	<p class="mt-2 mb-2 text-muted"><a href = "#" id="ImissPass">Я забыл пароль</a></p>
	<button class="btn btn-md btn-primary btn-block" name="submit" type="submit">Вход</button>	
	<p class="mt-3 mb-2 text-muted"><a href = "http://b2b.dmtextile.ru" target="_blanc">&copy; Dmtextile <?php echo date('Y')?></a></p>
</form>


<form class="form-signin ihiden" id="pass-restore" method="POST">
	<img class="mb-4" src="/images/brand/DMT_LOGO.svg" alt="logo" width="70">
	<h1 class="h4 mb-3 font-weight-normal">Восстановление пароля</h1>
	<label for="inputEmail_" class="sr-only">Email address</label>
	<input type="email" id="inputEmail_" name="login_" class="form-control mb-2" placeholder="Email адрес" required autofocus>
	<div id="recovery-info"></div>	
	<button class="btn btn-md btn-primary btn-block" id="submit_" name="submit_" type="submit">Сменить пароль</button>
	<p class="mt-3 mb-2 text-muted"><a href = "http://b2b.dmtextile.ru" target="_blanc">&copy; Dmtextile <?php echo date('Y')?></a></p>
</form>


	
	<script src = "/js/jquery-3.6.0.min.js"></script>
	<script src = "/js/popper.min.js"></script>
	<script src = "/js/bootstrap.min.js"></script>
	<!-- <script src = "/js/datatables.min.js"></script> -->
	<!-- <script src = "/js/dataTables.bootstrap4.min.js"></script> -->
	<!-- <script src="/js/bootstrap-datepicker.min.js"></script> -->
	<script src = "/js/custom.js"></script>

	<script>
		$(document).ready(function () {
			"use strict";
			// $("form#pass-restore").hide();
			$('#ImissPass').on("click", function(e){
				e.preventDefault();
				$("form#login").hide();
				$("form#pass-restore").removeClass('ihiden', 5000);
			});
			$('form#pass-restore').on("submit", function(e){
				e.preventDefault();
				var login_rest = $('#inputEmail_').val();
				// console.log('Нажал ' + k);

				$.ajax({
				url: '/Login/baselogin/password_recovery.php',
				datatype: 'html',
				type: 'post',
				data: {
					login_rest: login_rest
				},
				success: function (data) {
					// console.log(data);
					if(data == 'false'){
						$("#recovery-info").html("<p class='text-danger info-text'>Нет такого пользователя</p>");
					}else{
						$('#pass-restore')[0].reset(); // Сбрасываем поля формы
						$("#recovery-info").html("<p class='text-success info-text'>Вам на почту отправлено письмо с инструкциями по смене пароля.</p>");
						$("#submit_").hide();
						$("#inputEmail_").hide();
					};
					// Идем домой
					// $(location).attr('href', '/');
				}
			});
			});
		});
	</script>
</body>
</html>
