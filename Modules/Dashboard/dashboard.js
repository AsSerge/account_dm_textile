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
			var outputString = "<table class='table table-sm pulse'>";
			
			outputString += "<tr><td>Всего администраторов:</td><td>" + writeUsersStatistic(data, 'adm') + "</td></tr>";
			outputString += "<tr><td>Всего менеджеров:</td><td>" + writeUsersStatistic(data, 'mgr') + "</td></tr>";
			outputString += "<tr><td>Всего клиентов:</td><td>" + writeUsersStatistic(data, 'kln') + "</td></tr>";
			outputString += "<tr><td>Всего логистов:</td><td>" + writeUsersStatistic(data, 'lgs') + "</td></tr>";
			outputString += "<tfoot><tr><td>Всего пользователей:</td><td>" + writeUsersStatistic(data, 'all') + "</td></tr></tfoot>";

			outputString += "</table'>";

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

	// Группы
	$.ajax({
		url: '/Modules/Dashboard/dashboard_statistic.php',
		type: 'post',
		data: {
			user_id: user_id,
			option: 'groups_statistic'
		},
		success: function (data) {
			$("#groups").html(data);
		}
	});

	// Оформление таблицы заказов  DataTable
	$('#ordersTable').DataTable({
		"responsive": true,
		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Все"]],
		"paging": true,//Отключаем пангинацию
		"bFilter": true,//Отключаем поиск
		"info": false,//Отключаем инфо панели
		"order": [[ 0, "desc" ]],
		"aoColumnDefs": [
			{
				'bSortable': false, //запрещаем сортировку по всем столбцам
				'aTargets': [1, 2, 3, 4, 5]
			}
		],
		"columnDefs": [
				{
				"targets": [0, 1, 2, 3, 4, 5], //Номер столбца 15, 16, 17 столбец - временно включен
				"visible": true //Видимость столбца
				}
			],
		//Настройка языка
		"language": {
			"lengthMenu": "Показывать _MENU_ записей на странице",
			"zeroRecords": "Извините - ничего не найдено",
			"info": "Показано _PAGE_ страниц из _PAGES_",
			"infoEmpty": "Нет подходящих записей",
			"infoFiltered": "(Отфильтровано из _MAX_ записей)",
			"sSearch": "Искать: ",
			"oPaginate": {
				"sFirst": "Первая",
				"sLast": "Последняя",
				"sNext": "Следующая",
				"sPrevious": "Предыдущая"
			}
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
