<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-users-cog" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Редактор групп</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description."]";?></small>
			</div>
</div>
<style>
	.pulse{
		font-size: 0.8rem;
}
	.pulse th:first-child, td:first-child {
		font-weight: 700;
	}
	.pulse th, td {
		vertical-align: middle !important;	
}
	.pulse thead, tfoot{
		background-color: #DADADA;
		font-weight: 600;
}
	.pulse input{
		width: 100%;
		border: none;
		/* border: 1px solid #DADADA; */
		outline: 0;
		outline-offset: 0;
		padding:  0.3rem;
		cursor: pointer;
	}
	.pulse input:FOCUS{
		outline: 1px solid #7544BB;
		outline-offset: 0;
		border-radius: 2px;
	}
	
</style>

<div class="my-3 p-3 bg-white rounded box-shadow">	
	<div class='container-fluid'>
		<div class="row">
			<div class="col-12 col-md-6 col-sm-12">
				<h5>Группы клиентов</h5>
				
					<table class='table table-sm pulse' id='clientGroupsTable'>
					<thead><tr><td>Команда</td><td>Логист</td><td>Почта 1</td><td>Почта 2</td></tr></thead>
					<tbody>
					<?php
					$stm = $pdo->prepare("SELECT * FROM user_teams WHERE 1");
					$stm->execute();
					$group_mail = $stm->fetchAll(PDO::FETCH_ASSOC);

					foreach($group_mail AS $dm){
						echo "<tr>";
						echo "<td>".$dm['team_name']."</td>";
						echo "<td>".getLogistName($pdo, $dm['team_id'])."</td>";
						echo "<td><input type='text' class='team_mail' data-mail-id='1' data-team-id='".$dm['team_id']."' value='".$dm['team_mail_1']."'></td>";
						echo "<td><input type='text' class='team_mail' data-mail-id='2' data-team-id='".$dm['team_id']."' value='".$dm['team_mail_2']."'></td>";
						echo "</tr>";
					}

					?>
					</tbody>
					<tfoot></tfoot>
					</table>
					<div style="text-align: center;">
						<hr>
						<button type="button" class="btn btn-success btn-sm" id="btnSaveChanges">Сохранить изменения</button>
					</div>
				
			</div>


			<div class="col-12 col-md-6 col-sm-12">
				<h5>Помощь</h5>
				
				<p>
					В этом разделе вы можете изменить почтовые адреса логистов для взаимодействия с клиентами.
				</p>
				<p>
					Все клиенты разбиты на 5 команд по регионам. Каждая команда разбита на две подгруппы. Каждая подгруппа имеет свой адрес электронной почты и своего менеджера.
				</p>
				<p>
					Логист обслуживает всю команду. У одной команды - 1 логист.
				</p>
				
			</div>	

		</div>
	</div>
</div>
<style>
	#infoBar{
		position: fixed;
		bottom: 0px;
		left: 50%;
		z-index: 10000;
		background: #E8A833;
		display: none;
		height: 30px;
		transform: translateX(-50%);
		border-radius: 8px 8px 0 0;
	}
	.infoBar{
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.infoBar span{
		margin: 0.2rem 1rem;
	}

</style>
<div id="infoBar">
	<div class="infoBar">
		<span>Изменения сохранены!</span>
	</div>
</div>



<?php
// Получаем информацию о логисте группы
function getLogistName($pdo, $team_id){
	$stm = $pdo->prepare("SELECT user_name, user_surname FROM users WHERE user_role = 'lgs' AND user_team = :user_team");
	$stm->execute(['user_team' => $team_id]);
	$logist = $stm->fetch(PDO::FETCH_ASSOC);

	return $logist['user_name'] . " " . $logist['user_surname'];
}

?>