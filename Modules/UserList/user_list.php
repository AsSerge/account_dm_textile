<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
	<span style="margin-right: 10px"><i class="fas fa-user-friends" style="font-size: 2.5rem;"></i></span>
	<div class="lh-100">
		<h6 class="mb-0 text-white lh-100">Список пользователей</h6>
		<small><?php echo $user_name." " .$user_surname. " [".$user_role_description."]";?></small>
	</div>
</div>

<?php

// Соединямся с БД
include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php"); //$pdo
$query = $pdo->prepare("SELECT * FROM users AS U LEFT JOIN user_teams AS UT ON (U.user_team = UT.team_id) WHERE 1 ORDER BY user_id");

$query->execute();
$userdata = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="my-3 p-3 bg-white rounded box-shadow">
	<div id="main">
		<table class='table table-striped table-sm'>
		<thead><tr><th>#</th><th>Пользователь</th><th>Логин</th><th>Роль</th><th>Команда</th><th>Командная почта</th><th>Действие</th></tr></thead>

		<?php
			forEach($userdata as $u){
			switch ($u['user_role']) {
				case "adm":
					$user_role = "Администратор";
					break;
				case "mgr":
					$user_role = "Менеджер";
					break;
				case "kln":
					$user_role = "Клиент";
					break;
				case "lgs":
					$user_role = "Логист";
					break;
			}
			$user_link = "<a href = '#' data-user-id='{$u['user_id']}' class='EditUser' data-toggle='modal' data-target='#EditUser' data-whatever='{$u['user_id']}'>{$u['user_name']} {$u['user_surname']}</a>";
			echo "<tr>";
			echo "<td>{$u['user_id']}</td>";
			echo "<td>{$user_link}</td>";
			echo "<td>{$u['user_login']}</td>";
			echo "<td>{$user_role}</td>";
			echo "<td>{$u['team_name']}</td>";
			echo "<td>{$u['team_manager_mail']}</td>";
			echo "<td><button type='button' class='btn btn-danger userDelBtn btn-sm' data-user-id= '{$u['user_id']}'><i class='far fa-trash-alt'></i> Удалить</button></td>";
			echo "</tr>";
		}
		
		?>
		</table>
	</div>

<!-- Модальное окно Просмотр и удаление пользователей -->
	<div class="modal fade" id="EditUser" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Редактор пользователя</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
				</div>
			</div>
		</div>
	</div>
</div>
