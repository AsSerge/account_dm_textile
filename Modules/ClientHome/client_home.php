<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-drafting-compass" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Домашняя страница</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description." - ".$user_team_name."]";?></small>
				
			</div>
</div>

<style>
	#AvailableDocuments{
		display: flex;
		justify-content: flex-start;
		flex-wrap: wrap;
	}

	a.bigbutton{
		font-size: 0.8rem;
		text-decoration: none;
		color: #6F42C1;
	}

	a.smallbutton{
		color: #6F42C1;
		text-decoration: underline;
	}
	.onefile{
		/* background-color: #dadada; */
		/* border: 1px solid #6F42C1; */
		border: 1px solid #DADADA;
		border-radius: 5px;
		box-shadow: 3px 3px 5px #dadada;
		cursor: pointer;
		text-align: center;
		margin: 5px;
		padding: 15px 3px;
		width: 130px;

	}
	.onefile:HOVER{
		box-shadow: 1px 1px 3px #dadada;
	}
	
	.onefile img{
		padding-bottom: 15px;
	}
	.fileage{
		font-size: 0.7rem;
	}
	.fileslist{
		font-size: 0.8rem;
	}

	.fileslist td{
		vertical-align: middle;
	}

	.onetablefile{
	}
	
	.offer{
		margin-left: 1rem;
		cursor: pointer;
	}
	.offer:HOVER{
		text-decoration: underline;
	}
	.offer:BEFORE{
		content: &#8990;
	}

	.table-button{
		font-size: 0.7rem;
		margin: 0 rem;
	}
	.docs{
		padding-top: 1rem;
		display: flex;
		justify-content: flex-start;
		flex-wrap: wrap;
		margin-bottom: 2rem;
	}
	.orderform{
		margin-bottom: 1rem;		
		border-top: 1px solid #DADADA;
		padding-left: none;
	}
	@media (min-width: 767.98px){	
		.orderform{
			border-top: none;
			padding-top: none;
			border-left: 1px solid #DADADA;
			padding-left: 2rem;
		}
	}
	@media (max-width: 767.98px){
		.orderform h4{
			margin-top: 1rem;
		}
		.sendingfiles{
			display: none;
		}
	}

	#tableHistory td{
		font-size: 0.8rem;
		padding: 0.5rem 1rem;
	}
	

</style>
<?php
// Получаем массив файлов, к которым есть доступ у пользователя НА ЧТЕНИЕ
$stm = $pdo->prepare("SELECT F.file_id, F.file_name, F.file_description FROM file_access AS FA LEFT JOIN files AS F ON (FA.file_id = F.file_id) WHERE FA.access_type = 1 AND FA.user_id = ? ORDER BY FA.file_id");
$stm->execute(array($user_id));
$user_access = $stm->fetchAll(PDO::FETCH_ASSOC);


// Получаем массив файлов, к которым есть доступ у пользователя НА ОТПРАВКУ
$stm = $pdo->prepare("SELECT F.file_id, F.file_name, F.file_description FROM file_access AS FA LEFT JOIN files AS F ON (FA.file_id = F.file_id) WHERE FA.sending_type = 1 AND FA.user_id = ? ORDER BY FA.file_id");
$stm->execute(array($user_id));
$user_sending = $stm->fetchAll(PDO::FETCH_ASSOC);


// Функция определения возраста файла
function GetAge($file){		
	$dir = $_SERVER['DOCUMENT_ROOT'].'/private_docs/';
	return date("d.m.Y H:i", filemtime($dir.$file));
}
// Функция передставления размера файла в человеческом виде
function human_filesize($bytes, $decimals = 2) {
	$factor = floor((strlen($bytes) - 1) / 3);
	if ($factor > 0) $sz = 'KMGT';
	return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor - 1] . 'B';
}

// Функция проверки статуса Ордера, привязанного к документу. Выводим Последний доступный статус максимальный по времени
function ost($pdo, $file_name){	
	$stm = $pdo->prepare("SELECT state_type, state_date, O.order_key, O.order_type, O.user_id, O.order_id, O.order_hash FROM orders_states AS ST LEFT JOIN orders AS O ON (ST.order_id = O.order_id) WHERE O.file_name = :file_name ORDER BY ST.state_date DESC");
	$stm->execute([
		'file_name' => $file_name
	]);
	// $status = $stm->fetch(PDO::FETCH_COLUMN);
	$status = $stm->fetch(PDO::FETCH_ASSOC);
	// Определяем статус заказа
	switch ($status['state_type']){
		case 0: $status_string = "Новый заказ"; break;
		case 1: $status_string = "Заказ взят в работу"; break;
		case 2: $status_string = "Скачать первичное предложение"; break;
		case 3: $status_string = "Запрос на доработку"; break;
		case 4: $status_string = "Скачать повторное предложение"; break;
		case 5: $status_string = "Одобрен клиентом"; break;
		case 6: $status_string = "Заказ на формировании"; break;
		case 7: $status_string = "Заказ отменен"; break;
	}	
	
	// Считаем количество повторных предложений (если они есть)	
	$stmt = $pdo->prepare("SELECT COUNT(state_type) FROM orders_states WHERE order_id = :order_id AND state_type = 4");
	$stmt->execute([
		'order_id' => $status['order_id']
	]);
	$result = $stmt->fetch(PDO::FETCH_COLUMN);
	$lastStatus['revision_count'] = $result;	
	

	$lastStatus['status'] = $status_string;
	$lastStatus['state_type'] = $status['state_type'];
	$lastStatus['date'] = $status['state_date'];
	$lastStatus['offer_file'] = "{$status['order_key']}_{$status['order_type']}_OFFER_";
	$lastStatus['order_id'] = $status['order_id'];
	$lastStatus['order_name'] = "{$status['order_key']}_{$status['order_type']}";
	$lastStatus['order_access_line'] = $status['order_hash'];
	return $lastStatus;
}



?>
<div class="my-3 p-3 bg-white rounded box-shadow">	
	<div class='container-fluid'>

		<div class="row" id='AvailableDocuments'>
			<div class="col-12 col-md-6 col-sm-12">
				<h4>Бланки заказа для загрузки</h4>
				<div class="docs">
				<?php
				foreach($user_access AS $file){
						echo "<a href='/Modules/ClientHome/action.php?file={$file['file_name']}&link_type=bz' class='bigbutton'>";
						echo "<div class='onefile' data-file = '{$file['file_name']}'>";
						echo "<img src='/images/brand/excel.png'>";
						echo "<div>{$file['file_description']}</div>";
						echo "<div class='fileage'>".GetAge($file['file_name'])."</div>";
						echo "</div>";
						echo "</a>";
						// $fda[$file['file_id']] = $file['file_description']; // Заполняем массив данными для вывода в select
				}
				?>
				</div>
			</div>
			<style>
				.avatar-field{
					/* width: 100px;
					header: 100px; */
					border-radius: 10px;
					cursor: pointer;
					display: flex;
					justify-content: center;
					align-items: center;
					overflow: hidden;
				}
				.avatar-field input{
					display: none;
				}
				.input__wrapper{
					border: 1px solid #DADADA;
					border-radius: 5px;
					box-shadow: 3px 3px 5px #dadada;
					cursor: pointer;
					text-align: center;
					margin: 0 0 20px 0;
					padding: 15px;
					/* width: 130px; */
				}
				.input__wrapper:HOVER{
					box-shadow: 1px 1px 3px #dadada;
					border: 1px solid #ccc;
				}
				#imgInfo{
					font-size: 0.8rem;
				}
			</style>
			<div class="col-12 col-md-6 col-sm-12 orderform">
					<h4>Отправка бланка заказа (заявка)</h4>
					<form enctype="multipart/form-data" id="sendOrder" method="POST" action="/Modules/ClientHome/sendOrder.php">
						<div class="form-group input__wrapper">

							<label for="upload_file" class="avatar-field">
								<input type="file" class="form-control-file" id="upload_file" name="upload_file" class="form-control-file" accept = ".xls, .xlsx" required>
								<img src="../images/brand/excel_snd.png" id="preview">
							</label>
							<span id="imgInfo">Выбрать файл</span>

						</div>
						<?php
						// Выводим список доступных описаний для отпраляемых файлов (если они есть)
						if(count($user_sending) > 0){
							echo "<div class='form-group'>";
							echo "<label for='file_description'>Тип документа для загрузки</label>";
							echo "<select id='file_description' class='form-control' name='file_description' required>";
							echo "<option value='' selected readonly>Выбрать...</option>";
							foreach($user_sending as $file){
								echo "<option value='{$file['file_id']}'>{$file['file_description']}</option>";
							}
							echo "</select>";
							echo "</div>";
						}
						?>
						<div class="form-group">
							<label for="message_body">Дополнительная информация (текст не более 500 символов)</label>
							<textarea class="form-control" id="message_body" name="message_body" rows="4" maxlength='500'></textarea>
							<span id="message_lenght" class = 'fileage'></span>
						</div>
						<div class="form-group" style='text-align: center'>
							<button type="reset" class="btn btn-warning">Очистить</button>
							<button type="submit" class="btn btn-primary">Отправить</button>
						</div>
						<div id="sendOrderErrors" class="p-2 mb-1 bg-danger text-white"></div>

						<div id="result"></div>
					</form>

			</div>

		</div>

	</div>
</div>

<div class="my-3 p-3 bg-white rounded box-shadow sendingfiles">
	<h3>Отправленные документы</h3>
	<?php
		// Получаем массив файлов, к которым есть доступ у пользователя НА ОТПРАВКУ
		$stm = $pdo->prepare("SELECT file_name FROM orders WHERE user_id = ? ORDER BY order_date DESC");
		$stm->execute([$user_id]);
		$ord = $stm->fetchAll(PDO::FETCH_COLUMN);

		$u_dir = $_SERVER['DOCUMENT_ROOT'].'/uploaded_documents' . "/". $user_id;
		
		// Проверяем, существует ли каталог и есть ли в нем файлы отличные от . и ..
		if(is_dir($u_dir) && count(scandir($u_dir)) > 2){
			echo "<table class='table table-sm fileslist' id='oneTable'>";
			echo "<thead>";			
			echo "<tr><th>Файл</th><th>Размер</th><th>Отправлен</th><th>Заказ</th><th>Статус заказа</th><th>Дата</th><th>Действия</th></tr>";
			echo "</thead>";
			echo "<tbody>";
			foreach($ord as $file){

				if($file != '.' && $file != '..' && !mb_stripos($file, '_OFFER_')  && !mb_stripos($file, '_REVISION_')){

					if (ost($pdo, $file)['state_type'] == 7){
						echo "<tr style = 'background-color: #ffdddd'>";
					} else if(ost($pdo, $file)['state_type'] == 6){
						echo "<tr style = 'background-color: #daffda'>";
					}else{
						echo "<tr>";
					}
					echo "<td><span class='onetablefile'>" . $file . "</span></td>";
					echo "<td>" . human_filesize(filesize($u_dir."/".$file), 2)."</td>";
					echo "<td>" . date("d.m.Y H:i", filemtime($u_dir."/".$file)) . "</td>";
					echo "<td>".ost($pdo, $file)['order_name']."_ORDER</td>";
					// Выводим ссылку на файл оффера по заказу
					if (ost($pdo, $file)['state_type'] == 2 || ost($pdo, $file)['state_type'] == 4){

						// Получаем путь к файлу оффера (фозможны только два расширения файлов xls и xlsx)
						if(file_exists($_SERVER['DOCUMENT_ROOT']."/uploaded_documents/{$user_id}/".ost($pdo, $file)['offer_file'].".xls")){

							$offer_file = ost($pdo, $file)['offer_file'].".xls";
							echo "<td><a href='/Modules/ClientHome/action.php?file={$offer_file}&link_type=offer' class='smallbutton'>".ost($pdo, $file)['status']."</a></td>";

						}else if(file_exists($_SERVER['DOCUMENT_ROOT']."/uploaded_documents/{$user_id}/".ost($pdo, $file)['offer_file'].".xlsx")){

							$offer_file = ost($pdo, $file)['offer_file'].".xlsx";
							echo "<td><a href='/Modules/ClientHome/action.php?file={$offer_file}&link_type=offer' class='smallbutton'>".ost($pdo, $file)['status']."</a></td>";

						}else{
							echo "<td>" . ost($pdo, $file)['status'] . "</td>";
						}

					}else{
						echo "<td>" . ost($pdo, $file)['status'] . "</td>";
					}
					echo "<td>" . date('d.m.Y H:i', strtotime (ost($pdo, $file)['date'])) . "</td>";

					switch (ost($pdo, $file)['state_type']){
						case 0: echo "<td><button type='button' data-dir='".$user_id."/".$file."' class='btn btn-danger btn-sm table-button deletefile'>Удалить заявку</button></td>"; break;
						case 1: echo "<td><button type='button' data-dir='".$user_id."/".$file."' class='btn btn-warning btn-sm table-button deletefile' disabled>Заявка в работе</button></td>"; break;
						case 2:
						echo "<td>";
						echo "<button type='button' data-access-line='".ost($pdo, $file)['order_access_line']."' class='btn btn-success btn-sm table-button acceptoffer'>Принять</button>&nbsp;";
						echo "<button type='button' data-access-line='".ost($pdo, $file)['order_access_line']."' class='btn btn-danger btn-sm table-button revisionoffer' data-toggle='modal' data-target='#orderRevision'>Доработать</button>";
						echo "</td>";
						break;
						case 3:
						echo "<td><button type='button' data-order-name='".ost($pdo, $file)['order_name']."' data-state-id='".ost($pdo, $file)['order_id']."' class='btn btn-info btn-sm table-button orderHistory' data-toggle='modal' data-target='#orderHistory'>История заказа</button></td>";
						break;
						case 4:
						echo "<td>";
						echo "<button type='button' data-access-line='".ost($pdo, $file)['order_access_line']."' class='btn btn-success btn-sm table-button acceptoffer'>Принять</button>&nbsp;";
						echo "<button type='button' data-access-line='".ost($pdo, $file)['order_access_line']."' class='btn btn-danger btn-sm table-button revisionoffer' data-toggle='modal' data-target='#orderRevision'>Доработать</button>";
						echo "</td>";
						break;
						case 5:
						case 6:
						case 7:
						echo "<td><button type='button' data-order-name='".ost($pdo, $file)['order_name']."' data-state-id='".ost($pdo, $file)['order_id']."' class='btn btn-info btn-sm table-button orderHistory' data-toggle='modal' data-target='#orderHistory'>История заказа</button></td>";
						break;
					}

					echo "</tr>\n";
				}
			}
			echo "</tbody>";
			echo "</table>";
		}else{
			echo "<div class='alert alert-warning' role='alert'>У вас нет загруженных файлов!</div>";
		}
	?>
</div>



<!-- История заказа: Вертикально выравненное модальное окно -->
<div class="modal fade" id="orderHistory" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<h5 class="modal-title" id="ModalLabel"></h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body" id="tableHistory"></div>

		<div class="form-row">
			<div class="col form-group" style = "text-align: center;">
				<button type="button" class="btn btn-info" data-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
	</div>
</div>


<!-- Запрос на корректировку заказа: Вертикально выравненное модальное окно -->
<div class="modal fade" id="orderRevision" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			<h5 class="modal-title" id="">Корректировка заказа</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body" id="">


			<form enctype="multipart/form-data" id="sendRevisionForm" method="POST" action="/Modules/ClientHome/revisionOffer.php">
				<input type="hidden" id="access_line_rev" name="access_line_rev" value="">
				<div class="form-group input__wrapper">
					<label for="upload_file_rev" class="avatar-field">
						<input type="file" class="form-control-file" id="upload_file_rev" name="upload_file_rev" class="form-control-file" accept = ".xls, .xlsx">
						<img src="../images/brand/excel_snd.png" id="previewRev">
					</label>
					<span id="imgInfoRev">Выбрать файл</span>
				</div>
				<div class="form-group">
					<label for="message_body_rev">Дополнительная информация (текст не более 500 символов)</label>
					<textarea class="form-control" id="message_body_rev" name="message_body_rev" rows="4" maxlength='500'></textarea>
					<span id="message_lenght_rev" class = 'fileage'></span>
				</div>
				<div class="form-group" style='text-align: center'>
					<button type="button" class="btn btn-info" data-dismiss="modal" id="closeRevisionForm">Закрыть</button>
					<button type="reset" class="btn btn-warning" id="resetRevision">Очистить</button>
					<button type="submit" class="btn btn-primary" id="sendRevision">Отправить</button>
				</div>
				<div id="sendRevisionErrors" class="p-2 mb-1 bg-danger text-white"></div>
				<div id="resultRev"></div>
			</form>


		</div>
	</div>
</div>
</div>