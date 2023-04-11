$(document).ready(function () {
	"use strict";
	const maxValueTitle = 70;
	const maxValueMessage = 200;

	// Инфо-поля
	$("#buttonSet").append('\
	<div id="sendMessageErrors" class="p-2 mb-3 mt-3 bg-danger text-white"></div>\
	<div id="sendMessageInfo" class="p-2 mb-3 mt-3 bg-info text-white"></div>\
	');

	$('#sendMessageErrors').hide(); // Отчет об ошибках
	$('#sendMessageInfo').hide(); // Отчет об ошибках

	$('#title_lenght').html(`0 из ${maxValueTitle}, осталось ${maxValueTitle}`);
	$('#message_lenght').html(`0 из ${maxValueMessage}, осталось ${maxValueMessage}`);

	// Установка начальных значений
	$('button[type=submit]').prop("disabled", true);// Делаем кнопку отправки НЕ активной	

	// Разблокировка полей формы при нажатии Reset
	$('button[type=reset]').on("click", function () {
		$('button[type=submit]').prop("disabled", true);// Делаем кнопку отправки НЕ активной
		$('#title_lenght').html(`0 из ${maxValueTitle}, осталось  ${maxValueTitle}`);
		$('#message_lenght').html(`0 из ${maxValueMessage}, осталось  ${maxValueMessage}`);
		
	});
	// Проверяем поле E-MAIL
	$("#message_recipients_user").on("change", function () { 
		var user_email = $(this).val();
		if (user_email !== "") {
			$('button[type=submit]').prop("disabled", false);
		}
	});
	// Длина заголовка сообщения
	$('#message_title').on("keyup", function () {
		$("#sendMessageErrors").fadeOut(1500);
		var title_lenght = $('#message_title').val();
		var ost = maxValueTitle - title_lenght.length;
		$('#title_lenght').html(` ` + title_lenght.length + ` из ${maxValueTitle}, осталось ` + ost);
	});

	// Длина тела сообщения
	$('#message_body').on("keyup", function () { 
		$("#sendMessageErrors").fadeOut(1500);
		var message_lenght = $('#message_body').val();
		var ost = maxValueMessage - message_lenght.length;
		$('#message_lenght').html(` ` + message_lenght.length + ` из ${maxValueMessage}, осталось ` + ost);
	});

	$('button[type=submit]').on("click", function (event) {
		event.preventDefault();
		var recipients_user = $("#message_recipients_user").val();
		var message_title = $("#message_title").val();
		var message_body = $("#message_body").val();
		var mail_sender = $("#mail_sender").val();		
		var error_log = '';
		if (message_title.length == 0) error_log += "Вы забыли добавить заголовок сообщения";
		if (message_body.length == 0) error_log += "Вы забыли добавить текст сообщения";
		if (error_log.length > 0) {
			$("#sendMessageErrors").html(error_log);
			$("#sendMessageErrors").show();
		} else { 
			$("#sendMessageErrors").hide();
			$.ajax({
				url: '/Modules/SendMessages/action.php',
				type: 'POST',
				datatype: 'HTML',
				data: {
					user_id: mail_sender,
					recipients_user: recipients_user,
					message_title: message_title,
					message_body: message_body
				}, success: function (data) {
					
					console.log(data);

					setTimeout(function () {
						$("#sendMessageInfo").html("Ваше сообщение отправлено!");
						$("#sendMessageInfo").fadeIn(1500);
						$("#sendMessageInfo").fadeOut(2500);
						$('button[type=reset]').trigger('click');
					}, 500);
				}
			});			
		}
	});
});




