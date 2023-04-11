"use strict";
$(document).ready(function () {
	$('#ResultForm').hide();
	$("#btn_registr").click(
		function () {
			sendAjaxForm('ResultForm', 'UserRegistration', '/Modules/ClientRegistration/action.php');
			return false;
		}
	);
	$("#btn_reset").click(
		function () {
			clearForm('ResultForm', 'UserRegistration');
			return false;
		}
	);
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