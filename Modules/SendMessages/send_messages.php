<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fa fa-cubes" style="font-size: 2.5rem;"></i></span>

			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Отправка сообщения</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description."]";?></small>
			</div>
</div>
<style>
	#message_list{
		width: 100%;
		/* height: 620px; */
		height: 90%;
		border: 1px solid #DADADA;
	}
	.btn-center{
		text-align: center;
	}
	option[disabled]{
		font-weight: 600;
		color: black;
		background-color: #DADADA;
	}
	.mgr{
		font-weight: 600;
		color: black;		
	}
	.smalltext{
		font-size: 0.7rem;
		text-align: right;
	}
</style>

<div class="my-3 p-3 bg-white rounded box-shadow" id="SendMessages">	
	<div class='container-fluid'>
		<div class="row">
			<div class="col-sm p-2">
				<h5>Все сообщения системы</h5>
				<div id="message_list">

				</div>
			</div>
			<div class="col-sm p-2 mx-3">
				<h5>Новое сообщение</h5>
				<div class="row">
					<div class="col-sm">
						<form id="sendMessageForm">
							<input type="hidden" name='mail_sender' id='mail_sender' value='<?=$user_id?>'>
							<?php
							// Формирование списка отправки одного сообщения
							setOneMail($pdo, $user_id, $user_role, $user_team_id, $user_leader);
							?>

							<div class="form-group">
								<label for="message_title">Тема сообщения</label>
								<input type="text" class="form-control" id="message_title" maxlength='70' required>
							</div>
							<div id="title_lenght" class="my-3 smalltext"></div>

							<div class="form-group">
								<label for="message_body">Текст сообщения</label>
								<textarea class="form-control" id="message_body" rows="6" maxlength='200' required></textarea>
							</div>
							<div id="message_lenght" class="my-3 smalltext"></div>
							<div class="btn-center" id="buttonSet">
								<button type="reset" class="btn btn-warning">Очистить</button>
								<button type="submit" class="btn btn-primary">Отправить</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
// Установки
// Одиночное сообщение
function setOneMail($pdo, $user_id, $user_role, $user_team_id, $user_leader){
// Формируем список пользователей

if ($user_role == "adm"){
	$stm = $pdo->prepare("SELECT user_id, user_login, user_name, user_surname, user_role, UT.team_name FROM users AS U LEFT JOIN user_teams AS UT ON (U.user_team = UT.team_id) WHERE 1");
	$stm->execute();
	$recipients = $stm->fetchAll(PDO::FETCH_ASSOC);
}else if($user_role == "mgr"){
	$stm = $pdo->prepare("SELECT user_id, user_login, user_name, user_surname, user_role, UT.team_name FROM users AS U LEFT JOIN user_teams AS UT ON (U.user_team = UT.team_id) WHERE user_leader = :user_leader");
	$stm->execute([
		'user_leader' => $user_id
	]);
	$recipients = $stm->fetchAll(PDO::FETCH_ASSOC);
}else if ($user_role == "kln"){
	$stm = $pdo->prepare("SELECT user_id, user_login, user_name, user_surname, user_role, UT.team_name FROM users AS U LEFT JOIN user_teams AS UT ON (U.user_team = UT.team_id) WHERE user_id = :user_id OR user_leader = :user_leader");
	$stm->execute([
		'user_id' => $user_leader,
		'user_leader' => $user_leader
	]);
	$recipients = $stm->fetchAll(PDO::FETCH_ASSOC);
}	
$user_mails = [];  // Создаем и заполняем массив клиентов
foreach ($recipients as $rcp) {
	if($rcp['user_role'] != "adm" && $rcp['user_id'] != $user_id){
		if($rcp['user_role'] == "mgr"){
			$user_mails[$rcp['team_name']][] = "<option class='mgr' value='{$rcp['user_id']}'>{$rcp['user_name']} {$rcp['user_surname']} [{$rcp['user_login']}]</option>";
		}else{
			$user_mails[$rcp['team_name']][] = "<option value='{$rcp['user_id']}'>{$rcp['user_name']} {$rcp['user_surname']} [{$rcp['user_login']}]</option>";
		}	
	}
}
echo "<div class='form-group'>";
echo "	<label for='message_recipients_user'>Получатель сообщения</label>";
echo "	<select class='form-control' id='message_recipients_user' name='message_recipients_user'>";
echo "		<option disabled selected>Выбрать...</option>";
foreach ($user_mails as $key => $ut ){
	echo "<option disabled>{$key}</option>";
	foreach($ut as $us){
		echo $us;
	}
}
echo "	</select>";
echo "</div>";
}
?>