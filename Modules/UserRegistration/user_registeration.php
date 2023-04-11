<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
	<span style="margin-right: 10px"><i class="fas fa-user-friends" style="font-size: 2.5rem;"></i></span>
	<div class="lh-100">
		<h6 class="mb-0 text-white lh-100">Регистрация пользователя</h6>
		<small><?php echo $user_name." " .$user_surname. " [".$user_role_description."]";?></small>
	</div>
</div>
<div class="my-3 p-3 bg-white rounded box-shadow">

<form id="UserRegistration" method="POST">
	<input type="hidden" name="submit">
	<div class="form-row">
		<div class="form-group col-md-2 col-sm-12">
			<label for="inputState">Имя</label>
			<input type="text" class="form-control" placeholder="Имя" name="user_name" required>
		</div>
		<div class="form-group col-md-2 col-sm-12">
			<label for="inputState">Фамилия</label>
			<input type="text" class="form-control" placeholder="Фамилия" name="user_surname" required>
		</div>
		<div class="form-group col-md-2 col-sm-12">
			<label for="inputState">Логин</label>
			<input type="text" class="form-control" placeholder="Логин (e-mail адрес)" name="user_login" required>
		</div>
		<div class="form-group col-md-2 col-sm-12">
			<label for="inputState">Пароль</label>
			<input type="text" class="form-control" placeholder="Пароль" name="user_password" required>
		</div>

		<div class="form-group col-md-1 col-sm-12">
			<label for="user_role">Роль</label>
			<select id="user_role" class="form-control" name="user_role" required>
				<option value=""selected disabled>Выбрать...</option>
				<option value="adm">Администратор</option>
				<option value="mgr">Менеджер</option>
				<option value="kln">Клиент</option>
			</select>
		</div>
		<div class="form-group col-md-1 col-sm-12">
			<label for="user_team">Команда</label>
			<select id="user_team" class="form-control" name="user_team" required>
				<option value="" selected disabled>Выбрать...</option>
				<option value="1">Восток</option>
				<option value="2">Сибирь</option>
				<option value="3">Центр</option>
				<option value="4">Урал</option>
				<option value="5">Юг</option>
			</select>
		</div>
		<div class="form-group col-md-2 col-sm-12">
			<label for="team_manager_mail">Командная почта</label>
			<select id="team_manager_mail" class="form-control" name="team_manager_mail" required>
				<option value="" selected disabled>Выбрать...</option>
				<option value="m1">m1</option>
				<option value="m2">m2</option>
			</select>

		
		</div>
	</div>
	<div class="form-row">
		<div class="col form-group" style = "text-align: center;">
			<button type="reset" class="btn btn-outline-warning" id="btn_reset">Сброс</button>
			<button type="submit" class="btn btn-outline-success" id="btn_registr">Регистрация</button>
		</div>
	</div>
</form>
<br>
<div id="ResultForm" role="alert"></div> 
</div>



