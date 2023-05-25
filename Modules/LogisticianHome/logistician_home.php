<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-drafting-compass" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Домашняя страница</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description." - ".$user_team_name."]";?></small>				
			</div>
</div>
<link rel="stylesheet" href="../css/styleOrdersInteface.css">
<style>
	.fileslist{
		font-size: 0.8rem;
	}
	.fileslist td{
		vertical-align: middle;
	}
</style>
<div class="my-3 p-3 bg-white rounded box-shadow">
<?php
// Подключаем класс для работы со страницей логиста
include_once($_SERVER['DOCUMENT_ROOT']."/Modules/LogisticianHome/logistician_classes.php");
// Переключатель вида таблицы (все заказы или один)
if(!$_GET['ord']){
	require_once($_SERVER['DOCUMENT_ROOT']."/Modules/LogisticianHome/infoAllOrders.php");
}else{
	require_once($_SERVER['DOCUMENT_ROOT']."/Modules/LogisticianHome/infoOneOrder.php");
}	
?>
</div>


<!-- Модальное окно отправки первоначального предложения -->
<div class="modal fade" data-backdrop="static" id="SetFirstOffer" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

			<?php

				echo "<div class='offer'>\n\r";	
				echo "<form id='send_offer_form' class='sendoffer' enctype='multipart/form-data' method='POST' action ='/OrderToWork/sendOffer.php'>\n\r";

				echo "<input type='hidden' name='access_line' value='" . $_GET['ord'] . "'>\n\r";

				echo "<div class='send_offer_field'>\n\r";
				echo "<label for='offer_file' class='avatar-field'>\n\r";

				echo "<input type='file' id='offer_file' name='offer_file' accept = '.xls, .xlsx'>\n\r";	
				echo "<img src='../images/brand/excel_snd.png' id='preview'>\n\r";
				
				echo "</label>\n\r";
				echo "<div class='file_info' id='msg_form'>Добавьте файл</div>\n\r";
				echo "</div>\n\r";

				echo "<div class='send_offer_field'>\n\r";
				echo "<textarea id='message_body' name='message_body' rows='6' cols='50' maxlength='500'></textarea>\n\r";	

				echo "<div class='message_info' id='message_info'></div>\n\r";

				echo "</div>\n\r";

				echo "<div class='send_offer_button'>\n\r";
				
				echo "<button type='submit' class='sub' id='submit' >Отправить</button>\n\r";
				echo "</div>\n\r";
				
				echo "</form>\n\r";

				echo "</div>\n\r";
			
			?>

			</div>
		</div>
	</div>
</div>

<!-- Модальное окно пояснения об отмене заявки -->
<div class="modal fade" data-backdrop="static" id="CancelOrder" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
			<?php
				echo "<div class='offer'>\n\r";
				echo "<p>Опишите причину отмены заказа</p>\n\r";
				echo "<form id='cancel_odrder_form' class='sendoffer' enctype='multipart/form-data' method='POST' action ='/OrderToWork/cancelOrder.php'>\n\r";

				echo "<input type='hidden' name='access_line' value='".$_GET['ord']."'>\n\r";
				
				echo "<div class='send_offer_field'>\n\r";
				echo "<textarea id='message_body_cancel' name='message_body_cancel' rows='6' cols='50' maxlength='500'></textarea>\n\r";	

				echo "<div class='message_info' id='message_info_cancel'></div>\n\r";

				echo "</div>\n\r";

				echo "<div class='send_offer_button'>\n\r";
				
				echo "<button type='submit' class='sub' id='submit_cancel' >Отправить</button>\n\r";
				echo "</div>\n\r";
				
				echo "</form>\n\r";	

				echo "</div>\n\r";
			?>
			</div>
		</div>
	</div>
</div>



