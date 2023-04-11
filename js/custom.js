$(document).ready(function () {
	"use strict";

	// Рaзворачивание меню
	$('[data-toggle="offcanvas"]').on('click', function () {
		$('.offcanvas-collapse').toggleClass('open')
	});

	// Всплывающие подсказки
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	});

	// Интерактивные изображения в оверлее

	$('.oneimage').css({
		'cursor': 'pointer'
	});

	$('.popup').css({
		'opacity': 0,
		'visibility': 'hidden'
	});

	$(".oneimage").on("click", function () {
		console.log("Грузим картинку");
		var imgwidht = $(window).width() * 0.5 + "px"; // Здесь устнавливаем ширину картинки в зависимости от ширины окна
		var scrl = $(window).outerWidth() - $(window).width() + "px" // Ширина линейки прокрутки

		var wh = $(window).height() - 100; // Устанавливаем высоту большой картинки
		var wh = wh + "px";

		var activeimage = $(this).attr("big-image");
		$('body').css({
			"overflow": "hidden",
			"padding-right": scrl
		});

		$('.header').css({
			"padding-right": scrl
		});

		$('.popup').animate({
			'opacity': 1
		}, 700).css({
			'visibility': 'visible'
		});

		var loc = $(location).attr('href');
		$(".popup__close").html("<i class = 'fas fa-times'></i>");
		// $(".popup__image").html('<img src="' + activeimage + '" width="' + imgwidht + '">');
		$(".popup__image").html('<img src="' + activeimage + '" height="' + wh + '">');

	});
	$(".popup__close").on("click", function () {
		$('body').css({
			"overflow": "visible",
			"padding-right": "0"
		});
		$('.header').css({
			"padding-right": "0"
		});
		$('.popup').animate({
			'opacity': 0
		}, 200).css({
			'visibility': 'hidden'
		});
	});
});
