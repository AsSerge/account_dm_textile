<?php include($_SERVER['DOCUMENT_ROOT']."/Login/baselogin/line_check.php");?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Layout/header.php')?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Layout/mainmenu.php')?>
<!------------------------------------------------- Основной контент ------------------------------------------------->

	<main role="main" class="container-fluid">	
		<?php
			// Распределение ролей
			// require_once($_SERVER['DOCUMENT_ROOT'].'/Layout/roles.php');
			require_once($_SERVER['DOCUMENT_ROOT'].'/Layout/roles_new.php');
			// Подключение контента (скрипты в футер грузим по необходимости)
			include($_SERVER['DOCUMENT_ROOT'].$link);
		?>
	</main>
	
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Layout/footer.php')?>