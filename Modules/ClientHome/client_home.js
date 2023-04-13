$(document).ready(function () {
	"use strict";
	$('button[type=submit]').attr("disabled", "enable");// Делаем кнопку отправки НЕ активной, пока не выбран файл для отправки
	$('#sendRevision').attr("disabled", "enable");// Делаем кнопку отправки НЕ активной, пока не выбран файл для отправки
	$('#sendOrderErrors').hide();
	$('#sendRevisionErrors').hide();

	// Оформление таблицы заказов DataTable
	$('#oneTable').DataTable({
		responsive: true,
		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Все"]],
		paging: false,//Отключаем пангинацию
		"bFilter": false,//Отключаем поиск
		"info": false,//Отключаем инфо панели
		"order": [[ 4, "desc" ]],
		"aoColumnDefs": [
			{
				'bSortable': false, //запрещаем сортировку по всем столбцам
				'aTargets': [0, 1, 3]
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
	// getMyTable();
	// setInterval(getMyTable, 500);
	// setInterval(()=>location.reload(), 5000);
});


// Выполнение запроса по таймеру (режим тестирования)
function getMyTable() { 
	LongLine = '<table width="100%">';
	$.ajax({
		url: "/Modules/ClientHome/_get_my_table.php",
		success: function (data) {
			var log_arr = jQuery.parseJSON(data);
			var log_array = Object.entries(log_arr); // Преобразуем Объект в массив для перебора
	
			if (log_array.length >= 1) {
					log_array.forEach(function (item) {
						LongLine += "<tr>";
						LongLine += "<td>" + item[1]['order_date'] + "</td>";
						LongLine += "<td>" + item[1]['order_key'] + "_" + item[1]['order_type'] + "</td>";
						LongLine += "<td>" + item[1]['user_id'] + "</td>";
						LongLine += "<td>" + item[1]['file_name'] + "</td>";
						LongLine += "</tr>";
					});
			}
			LongLine += '</table>';
			$('#MyTestBar').html(LongLine);	
		}
	});
	// console.log("Данные");
// location.reload(); // Перегружаем страницу
}

var errorType = ''; // Ошибки

// Функция форматирования строки
function GetFileSize(nbr) { 
	var formatter = new Intl.NumberFormat('ru');
	return formatter.format(nbr); // 1 000 000	
}

// Делаем кнопку отправки активной, если файл выбран для основного модуля отправки
$('input[name=upload_file]').on("change", function () {	
	var size = this.files[0].size; // размер в байтах
	var name = this.files[0].name; // имя файла	
	var allowExtension = ['xls', 'xlsx']; // Допустимые разрешения
	var allowSize = 15000000; // Максимальный размер файла

	if (size > allowSize) { 
		errorType += 'Мы не принимаем файлы больше 15M<br>\n\r';
	}
	if ($.inArray(name.split('.').pop().toLowerCase(), allowExtension) == -1) {
		errorType += 'Мы принимаем только файлы Excell<br>\n\r';
	}

	if (errorType.length > 0) {
		$('#sendOrderErrors').show('slow');
		$('#sendOrderErrors').html(errorType);
		$('button[type=submit]').attr("disabled", "enable");
		$('#imgInfo').text('Выбрать файл');
	} else {
		// Меняем картинку для файла
		$("#preview").attr('src', '../images/brand/excel.png');
		$('#imgInfo').html(name + " (<strong>" + GetFileSize(size) + "</srrong>)");
		$('button[type=submit]').removeAttr('disabled');
		$('#sendOrderErrors').hide('slow');
	}
});


// Делаем кнопку отправки активной, если файл выбран (для модального окна)
$('input[name=upload_file_rev]').on("change", function () {	
	var size = this.files[0].size; // размер в байтах
	var name = this.files[0].name; // имя файла	
	var allowExtension = ['xls', 'xlsx']; // Допустимые разрешения
	var allowSize = 15000000; // Максимальный размер файла

	if (size > allowSize) { 
		errorType += 'Мы не принимаем файлы больше 15M<br>\n\r';
	}
	if ($.inArray(name.split('.').pop().toLowerCase(), allowExtension) == -1) {
		errorType += 'Мы принимаем только файлы Excell<br>\n\r';
	}

	console.log("Ошибки" + errorType);

	if (errorType.length > 0) {
		$('#sendRevisionErrors').show('slow');
		$('#sendRevisionErrors').html(errorType);
		$('#sendRevision').attr("disabled", "enable");
		$('#imgInfoRev').text('Выбрать файл');
	} else {
		// Меняем картинку для файла
		$("#previewRev").attr('src', '../images/brand/excel.png');
		$('#imgInfoRev').html(name + " (<strong>" + GetFileSize(size) + "</srrong>)");
		$('#sendRevision').removeAttr('disabled');		
		$('#sendRevisionErrors').hide('slow');
	}
});


// Блоки ввода текста 
$('#message_body').on("keyup", function () { 
	var message_lenght = $('#message_body').val();
	var ost = 500 - message_lenght.length;
	$('#message_lenght').text(' ' + message_lenght.length + ' из 500, осталось ' + ost);	
});

$('#message_body_rev').on("keyup", function () { 	
	var message_lenght = $('#message_body_rev').val();
	var ost = 500 - message_lenght.length;
	$('#message_lenght_rev').text(' ' + message_lenght.length + ' из 500, осталось ' + ost);
	if (message_lenght.length > 0 && errorType.length == 0) {
		$('#sendRevision').removeAttr('disabled');
	} else { 
		$('#sendRevision').attr("disabled", "enable");// Делаем кнопку отправки НЕ активной, пока не выбран файл для отправки
	}
});


// Проверяем кнопку Reset для основной формы
$('button[type=reset]').on("click", function () {
	$('#sendOrderErrors').hide('slow');
	$('#sendOrderErrors').text('');
	$('#message_lenght').text('');
	$('#imgInfo').html('Выбрать файл');
	$("#preview").attr('src', '../images/brand/excel_snd.png');
	$('button[type=submit]').attr("disabled", "enable")// Делаем кнопку отправки НЕ активной, пока не выбран файл для отправки
});

// Проверяем кнопку Reset для формы запроса на изменение в модальном окне
$('#resetRevision').on("click", function () {
	$('#sendRevisionErrors').hide('slow');
	$('#sendRevisionErrors').text('');
	$('#message_lenght').text('');
	$('#imgInfoRev').html('Выбрать файл');
	$("#previewRev").attr('src', '../images/brand/excel_snd.png');
	$('#sendRevision').attr("disabled", "enable");// Делаем кнопку отправки НЕ активной, пока не выбран файл для отправки
	
});

// Проверяем кнопку Закрыть для формы запроса на изменение в модальном окне
$('#orderRevision').on("hidden.bs.modal", function () {
	$('#resetRevision').trigger('click'); // Здесь нажимаем на Reset
	$('#sendRevisionErrors').hide('slow');
	$('#sendRevisionErrors').text('');
	$('#message_lenght').text('');	
	$('#imgInfoRev').html('Выбрать файл');
	$("#previewRev").attr('src', '../images/brand/excel_snd.png');
	$('#sendRevision').attr("disabled", "enable");// Делаем кнопку отправки НЕ активной, пока не выбран файл для отправки	
});


// Проверяем отправку почты (загрузку файла)
$('#sendOrder').on("submit", function () {
	$('#result').text('Отправка документа...');
});


// Проверяем отправку почты из модального окна (загрузку файла)
$('#sendRevisionForm').on("submit", function () {
	$('#resultRev').text('Отправка документа...');
});



// Удаление или чтение бланка-заказа в таблице
$('.deletefile').on("click", function () {	
	var filedir = $(this).data('dir');
	$.ajax({
		url: "/Modules/ClientHome/deleteOrder.php",
		type: "POST",
		datatype: 'html',
		data: {
			file: filedir,
			action: 'deletefile'
		},
		success: function (data) { 
			// console.log(data);
			location.reload(); // Перегружаем страницу
		}
	});
});

// Кнопка История заказа
$('.orderHistory').on("click", function () { 
	var order_id = $(this).data('state-id');  // Получаем id ордера для формировании истории
	var order_name = $(this).data('order-name');  // Получаем имя ордера для формировании заголовка
	$.ajax({
		url: "/Modules/ClientHome/historyOrder.php",
		type: "POST",
		datatype: 'html',
		data: {
			order_id: order_id
		},
		success: function (data) { 
			$("#ModalLabel").html("Заказ " + order_name);
			$("#tableHistory").html(data);
		}
	});	
});



// Кнопка ПРИНЯТЬ предложение
$('.acceptoffer').on("click", function () {	
	var access_line = $(this).data('access-line');

	$.ajax({
		url: "/Modules/ClientHome/acceptOffer.php",
		type: "POST",
		datatype: 'html',
		data: {
			access_line: access_line,
			operator: 'kln',
			operation: 'accept'
		},
		success: function (data) { 
			// console.log(data);
			location.reload(); // Перегружаем страницу
		}
	});

});

// Кнопка ДОРАБОТАТЬ предложение
$('.revisionoffer').on("click", function () {	
	var access_line = $(this).data('access-line');
	$("#access_line_rev").val(access_line);
	// console.log(access_line);
});