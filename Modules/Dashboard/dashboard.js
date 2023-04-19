$(document).ready(function () {
	"use strict";
	var user_id = getCookie('id');
	// Пользователи
	$.ajax({
		url: '/Modules/Dashboard/dashboard_statistic.php',
		type: 'post',
		data: {
			user_id: user_id,
			option: 'users_statistic'
		},
		success: function (data) {
			var outputString = "";
			outputString += "<div>Всего пользователей: " + writeUsersStatistic(data, 'all') + "</div>";
			outputString += "<div>Всего администраторов: " + writeUsersStatistic(data, 'adm') + "</div>";
			outputString += "<div>Всего менеджеров: " + writeUsersStatistic(data, 'mgr') + "</div>";
			outputString += "<div>Всего клиентов: " + writeUsersStatistic(data, 'kln') + "</div>";
			$("#usr").html(outputString);
		}
	});
	// Заказы
	$.ajax({
		url: '/Modules/Dashboard/dashboard_statistic.php',
		type: 'post',
		data: {
			user_id: user_id,
			option: 'orders_statistic'
		},
		success: function (data) {
			$("#ord").html(data);
		}
	});

});


// Получение Cookie
function getCookie(name) {
	var value = "; " + document.cookie;
	var parts = value.split("; " + name + "=");
	if (parts.length >= 2) return parts.pop().split(";").shift();
}

// Получение статистической информации по типам пользователей
function writeUsersStatistic(data, userType) { 
	var log_arr = jQuery.parseJSON(data);  // Декодируем массив
	var la = Object.entries(log_arr); // Преобразуем Объект в массив для перебора
	
	var len = la.length; 
	var i = 0;
	var count = 0;	
	for (i; i < len; ++i) {
		if (la[i][1]['user_role'] == userType) { 
			count++;
		};
	}
	return (userType == 'all') ? len : count;
}
