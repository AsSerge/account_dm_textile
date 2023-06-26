<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-users-cog" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Редактор групп</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description."]";?></small>
			</div>
</div>
<style>
	.pulse{
		font-size: 0.8rem;
}
	.pulse th:first-child, td:first-child {
		font-weight: 700;
	}
	.pulse th, td {
		vertical-align: middle !important;	
}
	.pulse thead, tfoot{
		background-color: #DADADA;
		font-weight: 600;
}
	.pulse input{
		width: 100%;
		border: none;
		/* border: 1px solid #DADADA; */
		outline: 0;
		outline-offset: 0;
		padding:  0.3rem;
		cursor: pointer;
	}
	.pulse input:FOCUS{
		outline: 1px solid #7544BB;
		outline-offset: 0;
		border-radius: 2px;
	}
	
</style>

<div class="my-3 p-3 bg-white rounded box-shadow">	
	<div class='container-fluid'>
		<div class="row">
			<div class="col-12 col-md-6 col-sm-12">
				<h5>Группы клиентов</h5>
				
					<table class='table table-sm pulse' id='clientGroupsTable'>
					<thead><tr><td>Команда</td><td>Логист</td><td>Почта 1</td><td>Почта 2</td></tr></thead>
					<tbody>
					<?php
					$stm = $pdo->prepare("SELECT * FROM user_teams WHERE 1");
					$stm->execute();
					$group_mail = $stm->fetchAll(PDO::FETCH_ASSOC);

					foreach($group_mail AS $dm){
						echo "<tr>";
						echo "<td>".$dm['team_name']."</td>";
						echo "<td>".getLogistName($pdo, $dm['team_id'])."</td>";
						echo "<td><input type='text' class='team_mail' data-mail-id='1' data-team-id='".$dm['team_id']."' value='".$dm['team_mail_1']."'></td>";
						echo "<td><input type='text' class='team_mail' data-mail-id='2' data-team-id='".$dm['team_id']."' value='".$dm['team_mail_2']."'></td>";
						echo "</tr>";
					}

					?>
					</tbody>
					<tfoot></tfoot>
					</table>
					<div style="text-align: center;">
						<hr>
						<button type="button" class="btn btn-success btn-sm" id="btnSaveChanges">Сохранить изменения</button>
					</div>
				
			</div>


			<div class="col-12 col-md-6 col-sm-12">
				<h5>Помощь</h5>
				
				<p>
					В этом разделе вы можете изменить почтовые адреса логистов для взаимодействия с клиентами.
				</p>
				<p>
					Все клиенты разбиты на 5 команд по регионам. Каждая команда разбита на две подгруппы. Каждая подгруппа имеет свой адрес электронной почты и своего менеджера.
				</p>
				<p>
					Логист обслуживает всю команду. У одной команды - 1 логист.
				</p>
				
			</div>	

		</div>
	</div>
</div>

<style>
/* .svgin svg{
	border: 1px dotted #6F42C1;	 */
}
</style>

<div class="my-3 p-3 bg-white rounded box-shadow">	
	<div class='container-fluid'>
		<div class="row">
			<div class="col-12 col-md-6 col-sm-12 svgin" id="diagramFather">
					<h5>Холст, масло... JQuery</h5>
					<!-- <svg xmlns="http://www.w3.org/2000/svg" id="svg-container"></svg> -->
			</div>
		</div>
	</div>
</div>



<div class="my-3 p-3 bg-white rounded box-shadow">	
	<div class='container-fluid'>
		<div class="row">
			<div class="col-12 col-md-6 col-sm-12 svgin">
					<h5>Холст, масло... PHP + SVG</h5>
					<?php
					$products = [7, 190, 15, 65, 40, 50, 60, 70, 80, 450, 15, 140, 90, 195, 70, 90, 60, 193, 100, 100, 205];
					drawDiagramm($products, 400, 13, '#6F42C1', false, 2);
					?>
			</div>
			<div class="col-12 col-md-6 col-sm-12 svgin">
					<h5>Холст, масло... PHP + SVG</h5>
					<?php
					$big_data = [
						'Сергей' => 1220,
						'Дмитрий' => 800,
						'Svetlana' => 1500,
						'Найк' => 100,
						'Омега' => 1501,
						'Всилек' => 1110,
						'Фиалка' => 112,
						'Рододендрон' => 1170,
					];

					drawDiagrammFull($big_data, 400, 16, '#176B87', false, 1);
					?>
			</div>

		</div>
	</div>
</div>

<?php
// Рисовалка диаграмм по ассоциативному массиву (Ключ => Значение)

function drawDiagrammFull($data, $container_width, $bar_height, $color='#6F42C1', $sorting=false, $sepatator=1){
	// При необходимости - сортируем массив
	// asort($data);
	// Делим массив на две части
	$keys = [];
	$values = [];
	foreach($data AS $key=>$value){
		$keys[] = $key;  // Ключи
		$values[] = $value; // Значения
	}

	$array_count = count($data); // Размер массива
	$get_numbrers = 12; // При значениях высоты линейки ниже этого числа - подписи к значениям не выводятся и линейка занимает всю ширину контейнера

	// Левая часть
	$keys_max_length = array_map('mb_strlen', $keys);
	$keys_max = $keys[array_search(max($keys_max_length), $keys_max_length)]; // Максимальное значение названия 	
	$keys_max_length = count(preg_split('//u', $keys_max, -1, PREG_SPLIT_NO_EMPTY)); // Длина максимального названия !!!! с  учетом кодировки

	// Правая часть
	$values_max = max($values); // Максимальное значение
	$values_max_length = strlen($values_max); // Длина максимального числа
	

	// Параметры ячеек диаграммы
	$container_height = $array_count * $bar_height; // Высота SVG контейнера

	// Ширина виртуального контейнера для размещения цифровой информации (зависит от размера шрифта и длины максимального числа) 
	if($bar_height >= $get_numbrers){
		$container_width_virtual = $container_width - ($values_max_length * $bar_height);
	}else{
		$container_width_virtual = $container_width;
	}	
	$container_width_ratio = round(($container_width_virtual / $values_max * 0.85 ), 2); // Коэффициент увеличения / уменьшения ячеек диаграммы для вписки в контейнер
	// $number_ofset = // Смещение диаграммы для размещения подписей данных

	// Параметры размещения текста
	$font_size = round($bar_height * 0.7);  // Размер шрифта зависит от высоты ячейки
	$text_x_offset = $font_size * 0.3;  // Смещение по x (по горизонтали)
	$text_y_offset = $font_size;  // Смещение по y приравнено к размеру шрифта (по вертикали)
	$font_family = 'Montserrat, sans-serif';

	$main_offset = $keys_max_length * $bar_height * 0.5;  // расстояние до начала формирования диаграммы

	echo "<svg xmlns='http://www.w3.org/2000/svg' width='{$container_width}' height='{$container_height}'>";

	echo "
	<style>
	@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;700&family=Roboto+Mono:ital,wght@0,100;0,200;1,100&display=swap');
    html {font-family: 'Montserrat', sans-serif !important;}
	a:HOVER {text-decoration: none; font-weight: 500;}
	</style>	
	";

	for ($i =0 ; $i <= $array_count; $i++){
		$state_color = ($values[$i] == $values_max) ? '#DD0000' : $color; // Устанавливаем альтернативный цвет для наибольшего значения ячейки
		echo "<rect x='{$main_offset}' y='". ($i * $bar_height) . "' width='". ($values[$i] * $container_width_ratio) ."' fill='{$state_color}' height='".($bar_height - $sepatator)."'></rect>";

		if($bar_height >= $get_numbrers){
			echo "<text
			x='". ($values[$i] * $container_width_ratio + $text_x_offset + $main_offset) ."' 
			y='". ($i * $bar_height + $text_y_offset) . "' 
			fill='{$state_color}' 
			font-size='{$font_size}'
			font-family='{$font_family}'>
			{$values[$i]}
			</text>";
		}
		if($bar_height >= $get_numbrers){
			echo "<text
			x='0' 
			y='". ($i * $bar_height + $text_y_offset) . "' 
			fill='{$state_color}' 
			font-size='{$font_size}'
			font-family='{$font_family}'>
			<a href='#'>{$keys[$i]}</a>
			</text>";
		}	
		
	}
	echo "</svg>";	
}




// Рисовалка SVG диаграм по цифровым значениям
function drawDiagramm($data, $container_width, $bar_height, $color='#6F42C1', $sorting=false, $sepatator=1){
	// $data - мвссив данных
	// $container_width - общая ширина контейнера
	// $bar_height - высота линейки
	// $color - цвет линейки
	// $sorting - сортировка массива
	// $sepatator - ширина разделителя
	if($sorting) sort($data); // При необходимости - сортируем данные
	$array_max = max($data); // Максимальное число
	$array_max_length = strlen($array_max); // Длина максимального числа
	$array_count = count($data); // Размер массива
	$get_numbrers = 12; // При значениях высоты линейки ниже этого числа - подписи к значениям не выводятся и линейка занимает всю ширину контейнера
	
	// Переметры ячеек диаграммы
	$container_height = $array_count * $bar_height; // Высота SVG контейнера
	
	 // Ширина виртуального контейнера для размещения цифровой информации (зависит от размера шрифта и длины максимального числа) 
	if($bar_height >= $get_numbrers){
		$container_width_virtual = $container_width - ($array_max_length * $bar_height);
	}else{
		$container_width_virtual = $container_width;
	}	
	$container_width_ratio = round(($container_width_virtual / $array_max), 2); // Коэффициент увеличения / уменьшения ячеек диаграммы для вписки в контейнер

	// Параметры размещения текста
	$font_size = round($bar_height * 0.7);  // Размер шрифта зависит от высоты ячейки
	$text_x_offset = $font_size * 0.3;  // Смещение по x (по горизонтали)
	$text_y_offset = $font_size;  // Смещение по y приравнено к размеру шрифта (по вертикали)
	$font_family = 'sans-serif Tahoma';

	echo "<svg xmlns='http://www.w3.org/2000/svg' width='{$container_width}' height='{$container_height}'>";
	for ($i =0 ; $i <= $array_count; $i++){
		$state_color = ($data[$i] == $array_max) ? '#DD0000' : $color; // Устанавливаем альтернативный цвет для наибольшего значения ячейки
		echo "<rect x='0' y='". ($i * $bar_height) . "' width='". ($data[$i] * $container_width_ratio) ."' fill='{$state_color}' height='".($bar_height - $sepatator)."'></rect>";
		if($bar_height >= $get_numbrers){
			echo "<text
			x='". ($data[$i] * $container_width_ratio + $text_x_offset) ."' 
			y='". ($i * $bar_height + $text_y_offset) . "' 
			fill='{$state_color}' 
			font-size='{$font_size}'
			font-family='{$font_family}'>
			{$data[$i]}
			</text>";
		}
	}
	echo "</svg>";
}
?>

<style>
	#infoBar{
		position: fixed;
		bottom: 0px;
		left: 50%;
		z-index: 10000;
		background: #E8A833;
		display: none;
		height: 30px;
		transform: translateX(-50%);
		border-radius: 8px 8px 0 0;
	}
	.infoBar{
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.infoBar span{
		margin: 0.2rem 1rem;
	}

</style>
<div id="infoBar">
	<div class="infoBar">
		<span>Изменения сохранены!</span>
	</div>
</div>



<?php
// Получаем информацию о логисте группы
function getLogistName($pdo, $team_id){
	$stm = $pdo->prepare("SELECT user_name, user_surname FROM users WHERE user_role = 'lgs' AND user_team = :user_team");
	$stm->execute(['user_team' => $team_id]);
	$logist = $stm->fetch(PDO::FETCH_ASSOC);

	return $logist['user_name'] . " " . $logist['user_surname'];
}

?>