$(document).ready(function () {
	"use strict";	
	changeMail(); // Перезапись электронной почты	
	// Рисуем диаграмуу
	$.ajax({
		url: "/Modules/UserTeamEditor/data_diagram.php",
		success: function (data) {
			var prr = []; 
			var log_arr = jQuery.parseJSON(data);  // Декодирование из JSON
			log_arr.forEach((el) => {
				prr.push(el);  // Формирование массива для отображения диаграммы
			});
			drowDiagramm(prr); // Отображение диаграммы
		}
	});
});


$(window).on("resize", function () {
	location.reload(); // Перегружаем страницу
});



// Функция рисования диаграммы
function drowDiagramm(data) { 
	var products = [3, 15, 65, 40, 50, 60, 70, 80, 45, 15, 140, 90, 190, 70, 90, 60, 190, 105];
	// var products = data;
	var blockFather = $("#diagramFather").width();	

	products = products.sort((a, b) => b - a); // Сортируеи массив	
	
	console.log(Math.max.apply(null, products));
	console.log(Math.min.apply(null, products));
	console.log("Ширина блока: " + blockFather);

	// var k = Math.floor(blockFather / Math.max.apply(null, products)) * 0.95;  // Округляем
	// var k = Math.round(blockFather / Math.max.apply(null, products)).toFixed(2) * 0.80;  // Округляем
	var k = ((Math.max.apply(null, products) / blockFather).toFixed(2)) * 1.01;
	console.log("Волшебный коэеффициент: " + k);
	
	let container = {
		height: products.length * 20,
		width: '100%'
	}
	let diagram = {
		bar_data_height: 20,
		bar_data_space: 1,
		bar_fill: '#E8A833',
		text_fill: '#E8A833',
		font_size: '10'
	}

	var svg = $('#svg-container'); // Определяем контейнер для диаграммы
	svg.attr('width', container.width).attr('height', container.height); // уснанавливаем размеры контейнера в зависомости от количества записей в массиве

	// Выводим записи и шкалы
	for (var i = 0; i < products.length; i++) {
		var square = $(document.createElementNS('http://www.w3.org/2000/svg', 'rect'));
		square
			.attr('x', 0)
			.attr('y', i * diagram.bar_data_height)
			.attr('width', products[i] / k)
			.attr('height', diagram.bar_data_height - diagram.bar_data_space)
			.attr('fill', diagram.bar_fill);
		var txt = $(document.createElementNS('http://www.w3.org/2000/svg', 'text'));
				
		if (products[i] / k <= 30) {
			txt.attr('x', products[i] / k + diagram.font_size * .5);
			txt.attr('fill', diagram.text_fill);
		} else { 
			txt.attr('x', products[i] / k + diagram.font_size * .5 - 30);
			txt.attr('fill', '#FFF');
		}
		txt		
			.attr('y', (i * diagram.bar_data_height) + diagram.font_size * 1.30)
			.attr('font-size', diagram.font_size)
			.text(products[i]);
		svg.append(square, txt);
	}
}


// Открываем псевдоинформационное окно и закрываем его через 2 секунды
$("#btnSaveChanges").on("click", function () {
	$(this).blur();
	console.log("Псевдосохранение");
	$("#infoBar").slideDown(500);
	setTimeout(function () {
		$("#infoBar").slideUp(500);
	}, 2000);
	setTimeout(function () {
		location.reload(); // Перегружаем страницу
	}, 3000);
	
});



// Перезапись электронной почты ()
function changeMail() {
	$('.team_mail').on("blur", function () {
		var mail_id = $(this).data('mail-id');
		var team_id = $(this).data('team-id');
		var mail_address = $(this).val();
		$.ajax({
			url: "/Modules/UserTeamEditor/action.php",
			type: "POST",
			datatype: 'html',
			data: {
				team_id: team_id,
				mail_address: mail_address,
				mail_id: mail_id
			},
			success: function (data) { 
			console.log(data);
			}
		});
	});
}

