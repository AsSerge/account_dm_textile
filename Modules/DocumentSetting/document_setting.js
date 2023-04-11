(function () {
	$(document).ready(function () {
		"use strict";
		$('input').on('change', function(){

			var iuser = $(this).parent().parent().data('userid'); // ID пользователя
			var idocument = $(this).data('fileid'); // ID документа
			var adocument = $(this).data('access'); // Тип доступа документа
			var ichecked = 0;
			if ($(this).is(':checked')){
				ichecked = 1
			}else{
				ichecked = 0
			}

			// var par_type = $(this).parent().prev().children().data('access');  // Находим тип предыдущего чек-бокса
			// if (par_type == 'access_type') {
			// 	var check_triger = $(this).parent().prev().children().prop("checked"); // Определяем состояние предыдущего элемента на момент клика
				
			// 	if (!check_triger) {
			// 		$(this).parent().prev().children().prop("checked", false).trigger('click');
			// 		// console.log(check_triger);
			// 	} 
			// }

			$.ajax({
				url: '/Modules/DocumentSetting/action.php',
				datatype: 'html',
				type: 'post',
				data: {
					user_id: iuser,
					file_id: idocument,
					access_type: ichecked,
					access_column: adocument 
				},
				success: function (data) {
					// console.log('Записано');
					// Идем домой
					// $(location).attr('href', '/index.php?module=CreativeApprovalList');
					// $(location).attr('href', '/');
				}
			});

		});
		
	});
})();