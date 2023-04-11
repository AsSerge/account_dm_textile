"use strict";
$(document).ready(function () {
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
		var user_team = $("#user_team").val();
		var team_manager_mail = $("#team_manager_mail").val();

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
				user_team: user_team,
				team_manager_mail: team_manager_mail
			},
			success: function (data) {
				console.log(data);
				// GetUserList();
				location.reload();
			}
		});
	})


	// Формирование модального окна
	$('#EditUser').on('shown.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var user_id = button.data('whatever');

		$.ajax({
			url: '/Modules/UserList/getoneuserdata.php',
			type: 'POST',
			data: {
				user_id: user_id
			},
			dataType: 'html',
			success: function (data) {

				var userEditForm = "";
				var role_block = "";
				var team_block = "";
				var res = $.parseJSON(data);
				// console.log(res);
				var modal = $("#EditUser");			

				var titleModal = {
					"adm": "администратора",
					"mgr": "менеджера",
					"kln": "клиента"
				}
				modal.find('.modal-title').text("Редактор " + titleModal[res.user_role]);

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

				// Формирование списока команд
				var team_arr = ["", "Восток", "Сибирь", "Центр", "Урал", "Юг"]; 
				var team = res.user_team;
				for (var k = 1; k <= 5; k++) { 
					if (team == k) {
						team_block += '<option value="' + k + '" selected>' + team_arr[k] + '</option>';
					} else { 
						team_block += '<option value="' + k + '">' + team_arr[k] + '</option>';
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
				if (res.user_role == "mgr" || res.user_role == "kln") {
					userEditForm += `<label for="user_superior">Команда</label>
					<select class="form-control mb-2" id="user_team" name="user_team">
						${team_block}
					</select>`;
				}
				if (res.user_role == "mgr") {
				// Формирование списка доступной для Команды почты
					userEditForm += `<label for="team_manager_mail">Коммандная почта</label>
					<select class="form-control mb-2" id="team_manager_mail" name="team_manager_mail">
						${getTeamMail(res.user_team, res.team_manager_mail)}
					</select>`;
				}
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



// Получение информации о коммандной почте + формирование списока доступной почты
function getTeamMail(user_team, team_manager_mail) {	
	var result, checkselect1, checkselect2
	$.ajax({
		url: '/Modules/UserList/getteammaildata.php',
		type: 'POST',
		data: {
			user_team: user_team
		},
		dataType: 'html',
		success: function (data) {
			var res = $.parseJSON(data);

			checkselect1 = (res[0]['team_mail_1'] == team_manager_mail) ? "selected" : "";
			checkselect2 = (res[0]['team_mail_2'] == team_manager_mail) ? "selected" : "";

			result += `\
			<option value='${res[0]['team_mail_1']}' ${checkselect1}>${res[0]['team_mail_1']}</option>\
			<option value='${res[0]['team_mail_2']}' ${checkselect2}>${res[0]['team_mail_2']}</option>\
			`;
			$("#team_manager_mail").html(result);
		}
	});	
}

// Получение информации об одном пользователе
function GrtOneUserData(user_id) {

	$.ajax({
		url: '/Modules/UserList/getoneuserdata.php',
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
		url: '/Modules/UserList/removeUser.php',
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

