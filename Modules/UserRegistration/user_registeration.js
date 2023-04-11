"use strict";
$(document).ready(function () {
	$('#ResultForm').hide();
	$("#btn_registr").click(
		function () {
			sendAjaxForm('ResultForm', 'UserRegistration', '/Modules/UserRegistration/action.php');
			return false;
		}
	);
	$("#btn_reset").click(
		function () {
			clearForm('ResultForm', 'UserRegistration');
			return false;
		}
	);

	// Предварительная настройка доступности полей Коменда и Командная почта
	$("#user_team").prop('disabled', true);
	$("#team_manager_mail").prop('disabled', true);
});

var user_role = ''; // Устаннавливаем роль пользователя
$("#user_role").on("change", function () {
	user_role = $("#user_role").val();
	if (user_role == "adm" || user_role == "") {
		$("#user_team").prop('disabled', true);
		$("#team_manager_mail").prop('disabled', true);

	} else if (user_role == "kln") { 
		$("#user_team").prop('disabled', false);
		$("#team_manager_mail").prop('disabled', true);

	} else if (user_role == "mgr") {
		$("#user_team").prop('disabled', false);

	}
});

$("#user_team").on("change", function () {	
	var user_team = $("#user_team").val();	
	if (user_role == "mgr" && user_team != "") { 
		// Получаем варианты командной почты для менеджеров
		$.ajax({
			url: '/Modules/UserRegistration/getinfo.php',
			type: "POST", //метод отправки
			dataType: "html", //формат данных
			data: {
				user_team: user_team
			},
			success: function (data) {
				$("#team_manager_mail").prop('disabled', false);
				var mails = JSON.parse(data);
				var innerSelect = '';
				innerSelect += "<option value='' selected disabled>Выбрать...</option>\n\r";
				innerSelect += `<option value="${mails[0]['team_mail_1']}">${mails[0]['team_mail_1']}</option>\n\r`;
				innerSelect += `<option value="${mails[0]['team_mail_2']}">${mails[0]['team_mail_2']}</option>\n\r`;
				$("#team_manager_mail").html(innerSelect);
			}
		});
	}	
});

// Очистка формы
function clearForm(result_form, ajax_form) {
	$("#" + result_form).hide();
	$("#" + ajax_form)[0].reset();
}
// Регистрация пользователя
function sendAjaxForm(result_form, ajax_form, url) {
	$.ajax({
		url: url, //url страницы (action_ajax_form.php)
		type: "POST", //метод отправки
		dataType: "html", //формат данных
		data: $("#" + ajax_form).serialize(), // Сеарилизуем объект
		success: function (response) { //Данные отправлены успешно

			$('#ResultForm').show();
			$('#ResultForm').removeClass('alert alert-primary');
			$('#ResultForm').removeClass('alert alert-danger');

			var result = $.parseJSON(response);
			if (result.mess == "") {
				$("#" + ajax_form)[0].reset();
				$('#ResultForm').addClass('alert alert-primary');
				$('#ResultForm').html(result.name + ' ' + result.surname + ' успешно добавлен!');
				$('#ResultForm').append("<p>Смотри <a href = '/index.php?module=UserList'>список зарегестрированных пользователей</a></p>");
			} else {
				$('#ResultForm').addClass('alert alert-danger');
				// Формируем массив ошибок
				var FormError = "";
				result.mess.forEach(function (e) {
					FormError += e + "<br>";
				});
				$('#ResultForm').html('<h5>Ошибки:</h5>' + FormError);
			}
			// $('#ResultForm').hide(8000);
		},
		error: function (response) { // Данные не отправлены
			// var result = $.parseJSON(response);
			$('#ResultForm').html('Ошибка. Данные не отправлены.');
		}
	});
}