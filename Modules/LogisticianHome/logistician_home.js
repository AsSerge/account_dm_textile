$(document).ready(function () {
	"use strict";
	$('[data-toggle="popover"]').popover({
		// trigger: 'focus'
	});
});

// Берем в работу
$('#btn0').on("click", function () { 
	var hash = $(this).data('hash');
	console.log('Берем в работу ' + hash);
	$.ajax({
	url: '/OrderToWork/',
	type: 'GET',
	data: {
		access_line: hash,
		operator: "mgr",
		operation: "taketowork"
	},
	dataType: 'html',
		success: function (data) { 
			$('#systemMessage').html(data);
			location.reload(); // Перегружаем страницу
		}
	});
	
});


// Отправка предложения
$('#btn1').on("click", function () { 
	var hash = $(this).data('hash');
	console.log('Отправляем предложение ' + hash);
	$('.modal-title').text('Отправляем предложение');
});

// Отправка предложения
$('#btn2').on("click", function () { 
	var hash = $(this).data('hash');
	console.log('Отправляем повторное предложение ' + hash);
	$('.modal-title').text('Отправляем повторное предложение');
});


$('#btn3').on("click", function () { 
	var hash = $(this).data('hash');
	console.log('Отправляем на формирование ' + hash);
	$.ajax({
	url: '/OrderToWork/',
	type: 'GET',
	data: {
		access_line: hash,
		operator: "mgr",
		operation: "towork"
	},
	dataType: 'html',
		success: function () { 
			location.reload(); // Перегружаем страницу
			// console.log("Взяли в работу");
		}
	});
});

// Возврат на предыдущую страницу
$('#btn4').on("click", function () {
	$('.modal-title').text('Отмена заказа логистом');
	console.log("Отмена");
	// window.location.reload();
	// window.history.back();	

});

// Возврат на предыдущую страницу
$('#btn5').on("click", function () {
	window.location.reload();
	window.history.back();	
});



// Vanilla javascript
const maxValue = 200;

let send_offer_form = document.getElementById('send_offer_form'); // Форма отправки предложения
let cancel_odrder_form = document.getElementById('cancel_odrder_form'); // Форма отмены заказа

let offer_file = document.getElementById('offer_file'); // Поле файлап редложения ЛОГИСТ => КЛИЕННТ
let offer_file_revision = document.getElementById('offer_file_revision'); // Поле файла запроса на изменения КЛИЕННТ => ЛОГИСТ

let answer = document.getElementById('msg_form');
let submit_btn = document.getElementById('submit');
let submit_btn_cancel = document.getElementById('submit_cancel');
let preview = document.getElementById('preview');
let message_info = document.getElementById('message_info');
let message_info_cancel = document.getElementById('message_info_cancel');

let message_body = document.getElementById('message_body');
let message_body_cancel = document.getElementById('message_body_cancel');
let message_body_revision = document.getElementById('message_body_revision');

message_info.innerHTML = "Напишите сообщение. Не более " + maxValue + " символов";
message_info_cancel.innerHTML = "Напишите сообщение. Не более " + maxValue + " символов";



var check_file = false; // Признак добавления файла 
submit_btn.disabled = true; // Отключаем кнопку отправки
submit_btn.classList.add('des'); // Впеменно отклюаем вид кнопки отправить

submit_btn_cancel.disabled = true; // Отключаем кнопку отправки
submit_btn_cancel.classList.add('des'); // Впеменно отклюаем вид кнопки отправить

if (offer_file) {
	// Поле ввода файла
	offer_file.addEventListener("change", function () {
		// Если поле не пустое
		if (this.value) {
			let allowExtension = ['xls', 'xlsx']; // Допустимые разрешения
			let allowSize = 15000000; // Максимальный размер файла
			let errorType = ''; // Ошибки отправки

			let f = offer_file;
			let f_size = f.files[0].size;
			let f_extension = f.files[0].name.split('.').pop().toLowerCase();
			// Проверка размера
			if (f_size > allowSize) {
				errorType += 'Мы не принимаем файлы<br>больше 15M<br>\n\r';
			}
			// Проверка расширения
			if (!allowExtension.includes(f_extension)) {
				errorType += 'Мы принимаем только<br>файлы Excell<br>\n\r';
			}
			// Проверка на ошибки
			if (errorType.length == 0) {
				answer.innerHTML = 'Файл ' + f.files[0].name + '<br> Добавлен и готов к отправке';
				submit_btn.disabled = false;
				check_file = true;
				submit_btn.classList.remove('des');
				preview.src = '../images/brand/excel.png';
			} else {
				answer.innerHTML = errorType; // Список ошибок
			}
		} else {
			console.log("Файл не выбран");
		}
	});
}	
if (message_body) {
	// Поле воода сообщение
	message_body.addEventListener("keyup", function () {
		if (this.value.length > 0 && this.value.length <= maxValue) {
			message_info.innerHTML = "Сообщение: " + this.value.length + " / " + maxValue;
		} else if (this.value.length >= maxValue) {
			this.value = this.value.substr(0, maxValue);
			message_info.innerHTML = "Достигнут максимум";
		} else {
			message_info.innerHTML = "Напишите сообщение. Не более " + maxValue + " символов";
		}

	});
}

// Новый блок (Отмена заказа)
if (message_body_cancel) {	
	// Поле воода сообщение
	message_body_cancel.addEventListener("keyup", function () {		
		if (this.value.length > 0 && this.value.length <= maxValue) {
			submit_btn.classList.remove('des');

				submit_btn_cancel.disabled = false;
				check_file = true;
				submit_btn_cancel.classList.remove('des');

			message_info_cancel.innerHTML = "Сообщение: " + this.value.length + " / " + maxValue;
		} else if (this.value.length >= maxValue) {
			this.value = this.value.substr(0, maxValue);
			message_info.innerHTML = "Достигнут максимум";
		} else {
			submit_btn_cancel.disabled = true;
			check_file = false;
			submit_btn_cancel.classList.add('des');
			message_info_cancel.innerHTML = "Напишите сообщение. Не более " + maxValue + " символов";
		}
	});
}

// Кнопка отправки
send_offer_form.addEventListener("submit", function(){
	// e.preventDefault();
	if(check_file){
		answer.innerText = 'Файл отправлен';
	}else{
		answer.innerText = 'Ты забыл прикрепить файл';
	}
});
