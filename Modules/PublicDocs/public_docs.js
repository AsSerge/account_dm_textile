$(document).ready(function () {
	"use strict";
	$('button[type=submit]').attr("disabled", "enable");// Делаем кнопку отправки НЕ активной, пока не выбран файл для отправки	
	$('#sendOrderErrors').hide();
	
	// Оформление таблицы Загруженных документов DataTable
	$('#oneTable').DataTable({
		responsive: true,
		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Все"]],
		paging: false,//Отключаем пангинацию
		"bFilter": false,//Отключаем поиск
		"info": false,//Отключаем инфо панели
		"order": [[ 2, "desc" ]],
		"aoColumnDefs": [
			{
				'bSortable': false, //запрещаем сортировку по всем столбцам
				'aTargets': [0, 1, 3]
			}
		],
		"columnDefs": [
				{
				"targets": [0, 1, 2, 3], //Номер столбца 15, 16, 17 столбец - временно включен
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
	var allowExtension = ['xls', 'xlsx', 'pdf', 'doc', 'docx', 'txt']; // Допустимые разрешения
	var allowSize = 15000000; // Максимальный размер файла (15 М)

	if (size > allowSize) { 
		errorType += 'Мы не принимаем файлы больше 15M<br>\n\r';
	}
	if ($.inArray(name.split('.').pop().toLowerCase(), allowExtension) == -1) {
		errorType += 'Мы принимаем только файлы Excell, Word, PDF, TXT<br>\n\r';
	}

	if (errorType.length > 0) {
		$('#sendOrderErrors').show('slow');
		$('#sendOrderErrors').html(errorType);
		$('button[type=submit]').attr("disabled", "enable");
		$('#imgInfo').text('Выбрать файл');
	} else {
		// Меняем картинку для файла
		$("#preview").attr('src', '../images/brand/document.png');
		$('#imgInfo').html(name + " (<strong>" + GetFileSize(size) + "</srrong>)");
		$('button[type=submit]').removeAttr('disabled');
		$('#sendOrderErrors').hide('slow');
	}
});

// Блоки ввода текста 
$('#message_body').on("keyup", function () { 
	var message_lenght = $('#message_body').val();
	var ost = 100 - message_lenght.length;
	$('#message_lenght').text(' ' + message_lenght.length + ' из 100, осталось ' + ost);
});

// Проверяем кнопку Reset для основной формы
$('button[type=reset]').on("click", function () {
	$('#sendOrderErrors').hide('slow');
	$('#sendOrderErrors').text('');
	$('#message_lenght').text('');
	$('#imgInfo').html('Выбрать файл');
	$("#preview").attr('src', '../images/brand/document_snd.png');
	$('button[type=submit]').attr("disabled", "enable")// Делаем кнопку отправки НЕ активной, пока не выбран файл для отправки
});

