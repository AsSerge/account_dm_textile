<?php
include($_SERVER['DOCUMENT_ROOT']."/Login/baselogin/line_check.php");
echo "<br>Это страница администратора";
?>
<br>
<a href = '../Login/baselogin/logout.php'>Выйти из системы</a>
<br>
<?php if ($user_role == 'adm'){?>	
	
	<a href = '../Login/register.php'>Регистрация нового пользователя</a>
<?php }?>