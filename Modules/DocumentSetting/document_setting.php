<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-tasks" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Доступ к документам</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description." - ".$user_team_name."]";?></small>
			</div>
</div>
<style>
	#DocumentSetting table{
		font-size: 0.7rem;
	}
	#DocumentSetting table th{
		padding: 0.5rem;
	}

	#DocumentSetting table th[scope=col]:not(:first-child){
		width: 12%;
		text-align: center;		
	}
	#DocumentSetting table th[scope=col]{
		color: white;
		background-color: #6F42C1;
		vertical-align: middle;
	}

	#DocumentSetting table th[scope=subcol]{
		color: white;
		background-color: #6F42C1;
		vertical-align: middle;
		text-align: center;
		width: 4%;
	}

	#DocumentSetting table td{
		text-align: center;
		cursor: pointer;
	}
	#DocumentSetting table tr:HOVER{
		background-color: #DEDEDE;
	}
	.wr{
		background-color: #DFDFDF;
	}
	.wr:HOVER{
		background-color: #D0D0D0;
	}

	.table_info{
		font-size: 0.7rem;
	}

	
</style>
<div class="col-md-12" id="DocumentSetting">

	<?php
	if ($user_role == 'mgr'){echo "<h3>Комманда {$user_team_name}</h3>";}
	else if ($user_role == 'adm'){echo "<h3>Клиенты (доступ к приватным документам)</h3>";}
	?>	
	<table class="table table-bordered">
	<thead>
	<tr>
		<th scope="col" rowspan="2">Клиент</th>
		<th scope="col" colspan='2'>ОПТ&nbsp;1<br>Осн.</th>
		<th scope="col" colspan='2'>ОПТ&nbsp;2<br>Осн.</th>
		<th scope="col" colspan='2'>ОПТ&nbsp;3<br>Осн.</th>
		<th scope="col" colspan='2'>ОПТ&nbsp;4<br>Осн.</th>

		<th scope="col" colspan='2'>ОПТ&nbsp;1<br>Проч.</th>
		<th scope="col" colspan='2'>ОПТ&nbsp;2<br>Проч.</th>
		<th scope="col" colspan='2'>ОПТ&nbsp;3<br>Проч.</th>
		<th scope="col" colspan='2'>ОПТ&nbsp;4<br>Проч.</th>

		<th scope="col" colspan='2' width=20%>УЦЕНКА</th>
	</tr>
	<tr>
		<th scope="subcol">Чт</th><th scope="subcol">Зп</th>
		<th scope="subcol">Чт</th><th scope="subcol">Зп</th>
		<th scope="subcol">Чт</th><th scope="subcol">Зп</th>
		<th scope="subcol">Чт</th><th scope="subcol">Зп</th>
		<th scope="subcol">Чт</th><th scope="subcol">Зп</th>
		<th scope="subcol">Чт</th><th scope="subcol">Зп</th>
		<th scope="subcol">Чт</th><th scope="subcol">Зп</th>
		<th scope="subcol">Чт</th><th scope="subcol">Зп</th>
		<th scope="subcol">Чт</th><th scope="subcol">Зп</th>
	</tr>
	</thead>
	<tbody>

	<?php	

	if ($user_role == 'mgr'){
		$stm = $pdo->prepare("SELECT * FROM users WHERE user_role != 'mgr' AND user_team = :user_team AND user_leader = :user_leader");
		$stm->execute(array(
			'user_team' => $user_team_id,
			'user_leader' => $user_id
		));
	} else if ($user_role == 'adm'){
		$stm = $pdo->prepare("SELECT * FROM users WHERE user_role != 'mgr' AND user_role != 'adm'");
		$stm->execute();
	}

	$klients = $stm->fetchAll(PDO::FETCH_ASSOC);
	
	foreach ($klients AS $klient){
		echo "<tr data-userid='{$klient['user_id']}'>";
		echo "<th scope='row'><a href='#'>[{$klient['user_id']}] {$klient['user_name']}&nbsp;{$klient['user_surname']}</a></th>\n";

		for($i = 1; $i <= 9; $i++){
			echo "<td><input type='checkbox' data-fileid={$i} data-access=access_type ".setAtr($pdo, $klient['user_id'], $i, 'access_type')."></td><td class='wr'><input type='checkbox' data-fileid={$i} data-access=sending_type ".setAtr($pdo, $klient['user_id'], $i, 'sending_type')."></td>\n";
		}
		echo "</tr>\n";
	}
	
	// Функция первоначальной установки атрибута доступа к файлу
	function setAtr($pdo, $user_id_table, $file_id_table, $a_type){
		$stm = $pdo->prepare("SELECT access_type, sending_type FROM file_access WHERE user_id = :user_id AND file_id = :file_id");
		$stm->execute(array(
			'user_id' => $user_id_table,
			'file_id' => $file_id_table
		));
		$access = $stm->fetch(PDO::FETCH_ASSOC);
		$access_type_table = (($access['access_type'] && $a_type == "access_type") || ($access['sending_type']) && $a_type == "sending_type") ? 'checked' : '';		
		return $access_type_table;
	}
	?>
	</tbody>
</table>
<div class="table_info"><strong>Чт</strong> - Права на чтение (загрузку файла с сервера). <strong>Зп</strong> - Права на отправку заполненного файла</div>

</div>


<style>
	.AvailableDocuments{
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
	.fileage, .filesize{
		font-size: 0.7rem;
	}
	.delay{
		color: red;
	}

</style>	

<div class="my-3 p-3 bg-white rounded box-shadow">	
	<div class='container-fluid'>
		<div class="row">
			<h5>Загруженные документы (Бланки заказа)</h5>
			<div class="col-12 col-md-12 col-sm-12 AvailableDocuments">
				<?php
				$stm = $pdo->prepare("SELECT * FROM files WHERE 1");
				$stm->execute();
				$documents = $stm->fetchAll(PDO::FETCH_ASSOC);
				foreach($documents as $file){

					echo "<a href='/Modules/DocumentSetting/document.php?file={$file['file_name']}' class='bigbutton'>";

					echo "<div class='onefile' data-file = '{$file['file_name']}'>";
					echo "<img src='/images/brand/excel.png'>";
					echo "<div>{$file['file_description']}</div>";
					echo "<div class='fileage'>".GetAge($file['file_name'])."</div>";
					echo "<div class='filesize'>".human_filesize(GetFileSize($file['file_name']))."</div>";
					echo "<div class='filesize delay'>".GetDelay($file['file_name'], 2)."</div>";
					echo "</div>";
					echo "</a>";
				}	
				?>
			</div>
		</div>
	</div>
</div>

<?php
// Функция определения возраста файла
function GetAge($file){		
	$dir = $_SERVER['DOCUMENT_ROOT'].'/private_docs/';
	return date("d.m.Y H:i", filemtime($dir.$file));
}

// Функция получения задержки
function GetDelay($file, $delay = 2){
	$dir = $_SERVER['DOCUMENT_ROOT'].'/private_docs/';
	$date_delay  = time() - date(filemtime($dir.$file));
	if ($date_delay > 86400 * $delay){
		return "> ". 24 * $delay . " часов";
	}	
}


// Функуция получения размера файла
function GetFileSize($file){
	$dir = $_SERVER['DOCUMENT_ROOT'].'/private_docs/';
	return filesize($dir.$file);
}

// Функция передставления размера файла в человеческом виде
function human_filesize($bytes, $decimals = 2) {
	$factor = floor((strlen($bytes) - 1) / 3);
	if ($factor > 0) $sz = 'KMGT';
	return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor - 1] . 'B';
}

?>