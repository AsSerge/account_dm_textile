$(document).ready(function () {
	"use strict";
	var user_id = getCookie('id');
	$.ajax({
		url: '/Modules/Dashboard/dashboard_all_statistic.php',
		datatype: 'html',
		type: 'post',
		data: {
			user_id: user_id,
			option: 'all_statistic'
		},
		success: function (data) {
			$("#all").html("Всего пользователей: " + writeStatistic(data, 'all'));
			$("#adm").html("Всего администраторов: " + writeStatistic(data, 'adm'));
			$("#mgr").html("Всего менеджеров: " + writeStatistic(data, 'mgr'));
			$("#kln").html("Всего клиентов: " + writeStatistic(data, 'kln'));
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
function writeStatistic(data, userType) { 
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