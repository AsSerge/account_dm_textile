<!------------------------------------------------- Главное меню ------------------------------------------------->
<nav class="navbar navbar-expand-md fixed-top navbar-dark bg-dark">
		<a class="navbar-brand" href="/"><img src="/images/brand/DMT_LOGO_menu.svg" alt="ДМ Текстиль ЛОГО" ></a>
		<button class="navbar-toggler p-0 border-0" type="button" data-toggle="offcanvas">
		<span class="navbar-toggler-icon"></span>
		</button>

		<div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
		<ul class="navbar-nav mr-auto">

			<?php if($user_role == 'mgr'){?>

			<li class="nav-item">
				<!-- <a class="nav-link" href="/index.php?module=CreativeApprovalList">На рассмотрении</a> -->
			</li>
			<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Клиенты</a>
					<div class="dropdown-menu" aria-labelledby="dropdown01">
						<a class="dropdown-item" href="/index.php?module=ClientRegistration">Добавить клиента</a>
						<a class="dropdown-item" href="/index.php?module=ClientList">Список клиентов</a>
					</div>
			</li>
			<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Сообщения</a>
					<div class="dropdown-menu" aria-labelledby="dropdown01">
					<a  class="dropdown-item" href="/index.php?module=SendMessages">Сообщение</a>
					<a  class="dropdown-item" href="/index.php?module=SendMailSending">Рассылка</a>
					</div>
			</li>

			<li class="nav-item">
				<a class="nav-link" href="/index.php?module=PublicDocs">Документы</a>
			</li>

			<li class="nav-item">
				<a class="nav-link" href="/index.php?module=HelpDesk">Помощь</a>
			</li>



			<?php } ?>

			<?php if($user_role == 'kln'){?>
			<li class="nav-item">
				<a class="nav-link" href="/index.php?module=ClientHome">Заказы</a>
			</li>

			<li class="nav-item">
				<a class="nav-link" href="/index.php?module=SendMessages">Сообщения</a>
			</li>

			<li class="nav-item">
				<a class="nav-link" href="/index.php?module=PublicDocs">Документы</a>
			</li>

			<li class="nav-item">
				<a class="nav-link" href="/index.php?module=HelpDesk">Помощь</a>
			</li>
			<?php } ?>


			

			<?php if($user_role == 'lgs'){?>
			<li class="nav-item">
				<a class="nav-link" href="/index.php?module=LigisticianHome">Заявки</a>
			</li>

			<li class="nav-item">
				<a class="nav-link" href="/index.php?module=PublicDocs">Документы</a>
			</li>

			<li class="nav-item">
				<a class="nav-link" href="/index.php?module=HelpDesk">Помощь</a>
			</li>
			<?php } ?>



			<?php if($user_role == 'adm'){?>

				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Администратор</a>
					<div class="dropdown-menu" aria-labelledby="dropdown01">
					<a class="dropdown-item" href="/index.php?module=UserList">Список пользователей</a>	
					<a class="dropdown-item" href="/index.php?module=UserRegistration">Регистрация пользователя</a>
					<a class="dropdown-item" href="/index.php?module=DocumentSetting">Приватные документы (БЗ)</a>
					<a class="dropdown-item" href="/index.php?module=PublicDocs"">Общие документы</a>
					</div>
				</li>

				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Работа с базой</a>
					<div class="dropdown-menu" aria-labelledby="dropdown01">
					<a class="dropdown-item" href="/Modules/SystemAdmin/clearsystem.php">Очистка базы</a>	
					</div>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Сообщения</a>
					<div class="dropdown-menu" aria-labelledby="dropdown01">
					<a  class="dropdown-item" href="/index.php?module=SendMessages">Сообщение</a>
					<a  class="dropdown-item" href="/index.php?module=SendMailSending">Рассылка</a>
					</div>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/index.php?module=HelpDesk">Помощь</a>
				</li>
			<?php } ?>
			</ul>
			<ul class='navbar-nav mt-2 mt-md-0'>
				<li class="nav-item">
					<a class="nav-link" href="/Login/baselogin/logout.php"><i class="fas fa-door-open"></i> Выход</a>
				</li>
			</ul>


		</div>
</nav>
