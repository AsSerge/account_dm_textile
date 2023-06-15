$(document).ready(function () {
	"use strict";	
	changeMail();	
});

// Открываем псевдоинформационное окно и закрываем его через 2 секунды
$("#btnSaveChanges").on("click", function () {
	$(this).blur();
	console.log("Псевдосохранение");
	$("#infoBar").slideDown(500);
	setTimeout(function() {
		$("#infoBar").slideUp(500);
	}, 2000);
	setTimeout(function() {
		location.reload(); // Перегружаем страницу
	}, 3000);
	
})

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