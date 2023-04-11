"use strict";
$(document).ready(function () {
	GetUserList(); // Подгрузка данных из таблицы
	// Реакция на кнопку удаления пользователя
	$(document).on("click", ".userDelBtn", function (e) {
		e.preventDefault();
		var userToDel = $(this).attr("data-user-id");
		RemoveUser(userToDel);
		location.reload(); // Перезагрузка страницы
		GetUserList();
	});
	// Отправка формы из модального окна EditUser по кнопке SaveUser (изменение данных о пользователе)
	$(document).on("click", "#SaveUser", function (e) {
		e.preventDefault();
		var update_user = $("#update_user").val();

		var user_id = $("#user_id").val();
		var user_name = $("#user_name").val();
		var user_surname = $("#user_surname").val();
		var user_login = $("#user_login").val();
		var user_password = $("#user_password").val();
		var user_role = $("#user_role").val();
		var user_superior = $("#user_superior").val();

		$.ajax({
			url: '/Modules/UserList/user_update.php',
			type: 'POST',
			dataType: 'html',
			data: {
				update_user: update_user,
				user_id: user_id,
				user_name: user_name,
				user_surname: user_surname,
				user_login: user_login,
				user_password: user_password,
				user_role: user_role,
				user_superior: user_superior
			},
			success: function (data) {
				console.log(data);
				GetUserList();
			}
		});
	})


	// Формирование модального окна
	$('#EditUser').on('shown.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var user_id = button.data('whatever');

		$.ajax({
			url: '/Modules/ClientList/getoneclientdata.php',
			type: 'POST',
			data: {
				user_id: user_id
			},
			dataType: 'html',
			success: function (data) {

				var userEditForm = "";
				var role_block = "";
				var res = $.parseJSON(data);
				// console.log(res);


				var modal = $("#EditUser");
				modal.find('.modal-title').text("Редактор пользователя ID: " + res.user_id);

				// Проверка на значение по умолчанию
				var rolle_arr_name = ["Администратор", "Менеджер", "Клиент"];
				var rolle_arr = ["adm", "mgr", "kln"];
				var role = res.user_role;

				// Формирование списка ролей
				for (var i = 0; i <= 3; i++) {
					if (role == rolle_arr[i]) {
						role_block += '<option value="' + rolle_arr[i] + '" selected>' + rolle_arr_name[i] + '</option>';
					} else {
						role_block += '<option value="' + rolle_arr[i] + '">' + rolle_arr_name[i] + '</option>';
					}
				}

				// Формирование тела формы	
				userEditForm = `<form>\
				
				<div class="form-group">\
					<input type = "hidden" id = "update_user" value = "update_user">
					<input type = "hidden" id = "user_id" value = "${res.user_id}">
					<label for="user_name">Имя</label>
					<input type="text" class="form-control mb-2" id="user_name" value = "${res.user_name}" disabled>

					<label for="user_name">Фамилия</label>
					<input type="text" class="form-control mb-2" id="user_surname" value = "${res.user_surname}" disabled>

										
					<label for="user_login">Логин</label>
					<input type="email" class="form-control mb-2" id="user_login" value = "${res.user_login}" disabled>

					<label for="user_password">Новый пароль</label>
					<input type="text" class="form-control mb-2" id="user_password" value = "">

					<label for="user_role">Роль</label>
					<select class="form-control mb-2" id="user_role" name="user_role" disabled>
						${role_block}
					</select>`;

				userEditForm += `\
				</div>\
				<div style = "text-align: center">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
					<button type="submit" class="btn btn-danger" data-dismiss="modal" id="SaveUser">Сохранить</button>
				</div>
				</form>`;

				modal.find('.modal-body').html(userEditForm);
			}
		});
	});
});

// Получение информации об одном пользователе
function GrtOneUserData(iser_id) {

	$.ajax({
		url: '/Modules/ClientList/getoneclientdata.php',
		type: 'POST',
		data: {
			user_id: iser_id
		},
		dataType: 'html',
		success: function (data) {
			// console.log(data);
			res = $.parseJSON(data);
		}
	});
}


// Безвозвратное удаление пользователя из базы
function RemoveUser(userToDel) {

	$.ajax({
		url: '/Modules/ClientList/removeClient.php',
		type: 'POST',
		data: {
			user_id: userToDel
		},
		dataType: 'html',
		success: function (data) {
			// console.log(data);
		}
	});
}

// Выгрузка базы пользователей в виде таблицы
function GetUserList() {
	$.ajax({
		url: '/Modules/ClientList/getdata.php',
		type: "GET",
		data: {
			id: "12345"
		},
		dataType: 'html',
		success: function (data) {
			var res = $.parseJSON(data);
			var text_table = `<table class='table table-striped table-sm'><thead>\
			<tr><th>#</th><th>Пользователь</th><th>Логин</th><th>Роль</th><th>Команда</th><th>Действие</th></tr>\
			</thead>`;
			// Перебераем массив
			res.forEach(function (entry) {
				var user_role = "";
				switch (entry.user_role) {
					case "adm":
						user_role = "Администратор";
						break;
					case "mgr":
						user_role = "Менеджер";
						break;
					case "kln":
						user_role = "Клиент";
						break;
				}
				text_table += `<tr>\
				<td>${entry.user_id}</td>\
				<td><a href = '#' data-user-id= "${entry.user_id}"\
				
				class="EditUser" data-toggle="modal" data-target="#EditUser" data-whatever="${entry.user_id}"\

				>${entry.user_name} ${entry.user_surname}</a></td>\
				<td>${entry.user_login}</td>\
				<td>${user_role}</td>`;
				if (user_role == 'Менеджер' || user_role == 'Клиент') {
					text_table += `<td>${entry.team_name}</td>`;
				} else {
					text_table += `<td></td>`;
				}
				text_table += `<td><button type="button" class="btn btn-danger userDelBtn btn-sm" data-user-id= "${entry.user_id}"><i class="far fa-trash-alt"></i> Удалить</button></td>\
				</tr>`;
			});
			text_table += "</table>";
			$('#main').html(text_table);
		}
	});
}