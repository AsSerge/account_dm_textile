<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fa fa-cubes" style="font-size: 2.5rem;"></i></span>

			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Массовая рассылка</h6>
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
		color: white;
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
				<h5>Новая рассылка</h5>
				<div class="row">
					<div class="col-sm">
						<form id="sendMessageForm">
							<input type="hidden" name='mail_sender' id='mail_sender' value='<?=$user_id?>'>
							<?php
								if($user_role == "adm") {
									setGroupForAdmin($pdo, $user_id);
								}else if ($user_role == "mgr"){
									setGroupForManager($pdo, $user_id, $user_team_name);
								};
							?>
							<div class="form-group">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="" id="mail_sending_subgroup_managers">
									<label class="form-check-label" for="mail_sending_subgroup_managers">
										Менеджеры
									</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="" id="mail_sending_subgroup_clients">
									<label class="form-check-label" for="mail_sending_subgroup_clients">
										Клиенты
									</label>
								</div>
							</div>

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
// Формируем список групп для администратора
function setGroupForAdmin($pdo, $user_id){
	echo "<div class='form-group'>";
	echo "	<label for='mail_sending_group'>Группа рассылки</label>";
	echo "	<select multiple class='form-control' id='mail_sending_groups' name='mail_sending_group[]' size='5'>";
	echo "		<option value='1'>Восток</option>";
	echo "		<option value='2'>Сибирь</option>";
	echo "		<option value='3'>Центр</option>";
	echo "		<option value='4'>Урал</option>";
	echo "		<option value='5'>Юг</option>";
	echo "	</select>";
	echo "</div>";
};

// Формируем группу для менеджера
function setGroupForManager($pdo, $user_id, $user_team_name){
	echo "<p>Группа рассылки: <strong>{$user_team_name}</strong></p>";
};
?>