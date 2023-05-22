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
				<h5 class="modal-title">Отправка первоначального предложения</h5>
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





<?php
// Класс для получения информации о доступных для логиста ордерах
class ordersInfo{
	private $pdo;
	public function __construct($pdo) {
		$this->pdo = $pdo;
	}
	// Определение стстуса заказа (public)
	public function orderState($order_id){
		$stmt = $this->pdo->prepare("SELECT MAX(state_type) FROM orders_states WHERE order_id = ? ORDER BY state_date DESC");
		$stmt->execute([$order_id]);
		$order_state = $stmt->fetch(PDO::FETCH_COLUMN);

		switch ($order_state){
			case 0: $status_string = "Новый заказ"; break;
			case 1: $status_string = "Заказ взят в работу"; break;
			case 2: $status_string = "Ожидаем реакции на предложение"; break;
			case 3: $status_string = "Получен запрос на доработку"; break;
			case 4: $status_string = "Ожидаем реакции на повторное предложение"; break;
			case 5: $status_string = "Предложение одобрено клиентом"; break;
			case 6: $status_string = "Заказ на формировании"; break;
			case 7: $status_string = "Заказ отменен"; break;
		}
		return $status_string;
		
	}

	// Получение истории заказа
	public function orderHistory($order_id){
		$stm = $this->pdo->prepare("SELECT state_type, state_date FROM orders_states WHERE order_id = :order_id");
		$stm->execute([
			'order_id' => $order_id
		]);
		$steps = $stm->fetchAll(PDO::FETCH_ASSOC);
		$info_string = "";	
			$info_string .= "<table class='table table-sm fileslist table-striped'>";
			foreach ($steps as $st){
				switch ($st['state_type']){
					case 0: $status_string = "Сформирован запрос"; break;
					case 1: $status_string = "Взят в работу логистом"; break;
					case 2: $status_string = "Отправлено первичное предложение"; break;
					case 3: $status_string = "Отправлен запрос на доработку"; break;
					case 4: $status_string = "Отправлено повторное предложение"; break;
					case 5: $status_string = "Предложение одобрено клиентом"; break;
					case 6: $status_string = "Заказ отправлен на формирование"; break;
					case 7: $status_string = "Заказ отменен логистом"; break;
				}	
				$date_string = date('d.m.Y H:i', strtotime ($st['state_date']));
				$info_string .= "<tr><td>{$status_string}</td><td>{$date_string}</td></tr>";
			}
			$info_string .= "</table>";

		return $info_string;
	}
	// Получение списка кнопок для карточки заявки
	public function printOrderButtons($order_id, $order_hash){
		$stmt = $this->pdo->prepare("SELECT MAX(state_type) FROM orders_states WHERE order_id = ? ORDER BY state_date DESC");
		$stmt->execute([$order_id]);
		$order_state = $stmt->fetch(PDO::FETCH_COLUMN);

		
		$status_array = [];
		$btn_array = [

		
		"<button type='button' id='btn0' class='btn btn-secondary btn-sm' data-hash='{$order_hash}'>Взять в работу</button>",
		"<button type='button' id='btn1' class='btn btn-secondary btn-sm' data-toggle='modal' data-target='#SetFirstOffer' data-hash='{$order_hash}'>Отправить предложение</button>",
		"<button type='button' id='btn2' class='btn btn-secondary btn-sm' data-hash='{$order_hash}'>Отправить повторное предложение</button>",
		"<button type='button' id='btn3' class='btn btn-secondary btn-sm' data-hash='{$order_hash}'>Отправить на формирование</button>",
		"<button type='button' id='btn4' class='btn btn-danger btn-sm' data-hash='{$order_hash}'>Отменить заявку</button>",
		];

		switch ($order_state){
			case 0: $status_array = [1,0,0,0,1]; break;  // Получен новый заказ (можно взять в работу или сразу отменить)
			case 1: $status_array = [0,1,0,0,0]; break;  // Заказ взять в работу (можно отправить предложение или отменить)
			case 2: $status_array = [0,0,0,0,0]; break;  // Предложение отправлено (ждем реакции - нет кнопок)
			case 3: $status_array = [0,0,1,0,1]; break;  // Получен запрос на доработку (можно отправить повторное предложение или отменить)
			case 4: $status_array = [0,0,0,0,0]; break;  // Повторное педложение отправлено (ждем реакции - нет кнопок)
			case 5: $status_array = [0,0,0,1,0]; break;  // Предложение одобрено клиентом (можем только отправить на формирование);
			case 6: $status_array = [0,0,0,0,0]; break;  // Заказ на формировании 
			case 7: $status_array = [0,0,0,0,0]; break;  // Заказ отменен (ну штож - нет кнопок)
		}

		if(array_sum($status_array)){
			$buttons_string = "<div class='row' style='text-align: center'>";
			$buttons_string .= "<div class='col-12 col-md-12 col-sm-12'>";
			$buttons_string .= "<div class='btn-group' role='group' aria-label='Basic example'>";

			for($i=0;$i<=count($status_array); $i++){
				if($status_array[$i] === 1){
					$buttons_string .= $btn_array[$i];
				}
			}
			$buttons_string .= "</div></div></div>";

			return $buttons_string;
		}
		
	}

	// Определение размера файла (public)
	public function getOrderFileSize($order_id){
		$stmt = $this->pdo->prepare("SELECT user_id, file_name FROM orders WHERE order_id = ?");
		$stmt->execute([$order_id]);
		$targetFile = $stmt->fetch(PDO::FETCH_ASSOC);	

		$fileSize_path = $_SERVER['DOCUMENT_ROOT']."/uploaded_documents/" . $targetFile['user_id'] . "/" . $targetFile['file_name'];

		$fileSize_string = $this->human_filesize(filesize($fileSize_path), 2); // Обращение к внутренней функции
		
		return $fileSize_string;
	}
	
	// Получение информации о дополнительных файлах (OFFER от логиста)
	public function getOtherFileInfo($order_id){
		$stmt = $this->pdo->prepare("SELECT user_id, order_key, order_type FROM orders WHERE order_id = ?");
		$stmt->execute([$order_id]);
		$targetFile = $stmt->fetch(PDO::FETCH_ASSOC);

		$ar = [];

		$filePath = $_SERVER['DOCUMENT_ROOT'] . "/uploaded_documents/" . $targetFile['user_id'] . "/" . $targetFile['order_key'] . "_" . $targetFile['order_type'];

		if(file_exists($filePath . "_OFFER_.xlsx")){
			$offerFileName = $filePath . "_OFFER_.xlsx";
			$offerFileNameShort = $targetFile['order_key'] . "_" . $targetFile['order_type'] . "_OFFER_.xlsx";
		}else if (file_exists($filePath . "_OFFER_.xls")){
			$offerFileName = $filePath . "_OFFER_.xls";
			$offerFileNameShort = $targetFile['order_key'] . "_" . $targetFile['order_type'] . "_OFFER_.xls";
		}else{
			$offerFileName = '';
		}

		if ($offerFileName != ''){
			$ar['fz'] = $this->human_filesize(filesize($offerFileName));
			$ar['fn'] = $offerFileNameShort;
			return $ar;
		}else{
			return $ar;
		}
	}


	// Переведение размера в человеческий вид (private)
	private function human_filesize($bytes, $decimals = 2){
		$factor = floor((strlen($bytes) - 1) / 3);
		if ($factor > 0) $sz = 'KMGT';
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor - 1] . 'B';
	}
}
?>
