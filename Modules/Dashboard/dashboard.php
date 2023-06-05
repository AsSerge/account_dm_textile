<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-window-restore" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Testing_Dashboard</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description."]";?></small>
			</div>
</div>

<?php include_once($_SERVER['DOCUMENT_ROOT']."/Modules/Dashboard/dashboard_classes.php");?>

<style>
.one-block{	
	padding-bottom: 1rem;
}
.dataTables_filter, .dataTables_length, .pagination{
	font-size: 0.9rem;
}
.pulse{
	font-size: 0.8rem;
}
.pulse th, td {
	vertical-align: middle !important;	
}
.pulse thead, tfoot{
	background-color: #DADADA;
	font-weight: 600;	
}

.superTitle{
	display: flex;
	justify-content: space-between;
	flex-flow: row nowrap;	
}
#clientsTable th:first-child{
	width: 20%;
}
#clientsTable th:not(:first-child){
	width: 8%;
}
#tableHistory td{
	font-size: 0.8rem;
	padding: 0.5rem 1rem;
}
.dropdn-file{
	display: flex;
	flex-flow: row nowrap;
	justify-content: space-between;
}

.dropdn-file a{
	padding: 1rem;
}


</style>

<div class="my-3 p-3 bg-white rounded box-shadow">	
	<div class='container-fluid'>
		<div class="row">
			<div class="col-12 col-md-3 col-sm-12 one-block">
				<h5>Лидеры по заявкам</h5>
				<div id='leaders'>
				<?php echo getTopClients($pdo, '1');?>
				</div>
			</div>	
			<div class="col-12 col-md-3 col-sm-12 one-block">
				<h5>Группы</h5>
				<div id='groups'></div>
			</div>
			<div class="col-12 col-md-3 col-sm-12 one-block">
				<h5>Пользователи</h5>
				<div id='usr'></div>
			</div>

			<div class="col-12 col-md-3 col-sm-12 one-block">
				<h5>Заказы</h5>
				<div id='ord'></div>
			</div>
		</div>
	</div>
</div>


<div class="my-3 p-3 bg-white rounded box-shadow">
<div class='container-fluid'>
		<div class="row">
			<div class="col-12 col-md-12 col-sm-12 one-block">
				<h5>Статистика по клиентам</h5>
				<table class='table table-sm pulse' id='clientsTable'>
					<?php
					$headers = ['Клиент', 'Группа', 'Поступило заявок', 'Взят в работу', 'Отправлено первое предложение', 'Отменено', 'Принято', 'Формируется'];
					echo "<thead><tr>";
					foreach ($headers as $key => $h){
						echo "<th>{$h}</th>";
					}
					echo "</tr>\n\r</thead>\n\r<tbody>\n\r";
					$stm = $pdo->prepare("SELECT user_id, user_login, user_name, user_surname, user_team, team_name FROM users AS US LEFT JOIN user_teams AS UST ON (US.user_team = UST.team_id) WHERE US.user_role = 'kln'");
						$stm->execute();
						$user = $stm->fetchAll(PDO::FETCH_ASSOC);
						foreach($user as $usr){
							echo "<tr>";
							echo "<td>" . $usr['user_name'] . " ". $usr['user_surname'] . " [" . $usr['user_login'] . "]</td>";
							echo "<td>" . $usr['team_name'] . "</td>";
							echo "<td>" . getAllOrdersCount($pdo, $usr['user_id'], '0') . "</td>";
							echo "<td>" . getAllOrdersCount($pdo, $usr['user_id'], '1') . "</td>";
							echo "<td>" . getAllOrdersCount($pdo, $usr['user_id'], '2') . "</td>";
							echo "<td>" . getAllOrdersCount($pdo, $usr['user_id'], '7') . "</td>";
							echo "<td>" . getAllOrdersCount($pdo, $usr['user_id'], '5') . "</td>";
							echo "<td>" . getAllOrdersCount($pdo, $usr['user_id'], '6') . "</td>";

							echo"</tr>\n\r";
						}
					echo "</tbody>\n\r";
					?>
					<tfoot></tfoot>
				</table>
			</div>
		</div>
	</div>
</div>


<div class="my-3 p-3 bg-white rounded box-shadow">	
	<div class='container-fluid'>
		<div class="row">
			<div class="col-12 col-md-12 col-sm-12 one-block">
				
				<?php
					$user_id_go = $_GET['user_id']; // Id пользователя, по которому надо сформировать таблицу
					$team_id_go = $_GET['team_id']; // Id пользователя, по которому надо сформировать таблицу

					if($user_id_go != '' OR $team_id_go !=''){
						echo "<div class='superTitle'><h5>Скорость реакции на заявку</h5><a href='/'><span class='badge badge-primary'>Показать всех</span></a></div>";
					}else{
						echo "<div class='superTitle'><h5>Скорость реакции на заявку</h5></div>";
					}

					$stat = new getStatistic($pdo);

					// Проверяем - нет ли задачи по получению информацти об одном юзере или об определенной команде
					if ($user_id_go != ''){
						
						$stm = $pdo->prepare("SELECT order_id, order_key, order_type, order_date FROM orders WHERE user_id =?");
						$stm->execute([$user_id_go]);

					}else if($team_id_go !=''){

						$stm = $pdo->prepare("SELECT order_id, order_key, order_type, order_date, US.user_team FROM orders AS ORD LEFT JOIN users AS US ON (ORD.user_id = US.user_id) WHERE US.user_team = ?");
						$stm->execute([$team_id_go]);

					}else{
						$stm = $pdo->prepare("SELECT order_id, order_key, order_type, order_date FROM orders WHERE 1");
						$stm->execute();
					}
					$orders = $stm->fetchAll(PDO::FETCH_ASSOC);

					echo "<table class='table table-sm pulse' id='ordersTable'>";
					echo "<thead>";
					echo "<tr><th>Заказ</th><th>Открыт</th><th>Клиент</th><th>Заказ принят<br>в работу</th><th>Отправлено первое<br>предложение</th><th>Заказ одобрен<br>клиентом</th><th>Отправлен<br>на формирование</th><th>Заказ<br>общее время</th></tr>";
					echo "</thead>";
					echo "<tbody>";
					foreach($orders as $ord){

						echo "<tr>";
						echo "<td><a href ='#' class='orderHistory' data-toggle='modal' data-order-name='".$ord['order_key']."_".$ord['order_type']."'data-target='#orderHistory' data-state-id='".$ord['order_id']."'>". $ord['order_key'] . "_".$ord['order_type'] . "</a></td>";
						echo "<td>".date('d.m.Y H:i', strtotime ($ord['order_date']))."</td>";
						echo "<td>" . $stat->getUserInfo($ord['order_id']) . "</td>";
						echo "<td>" . $stat->getDifference($ord['order_id'],0,1,true) . "</td>";
						echo "<td>" . $stat->getDifference($ord['order_id'],1,2,true) . "</td>";
						echo "<td>" . $stat->getDifference($ord['order_id'],2,5,true) . "</td>";
						echo "<td>" . $stat->getDifference($ord['order_id'],5,6,true) . "</td>";
						echo "<td>" . $stat->getDifference($ord['order_id'],0,6,true) . "</td>";
						echo "</tr>";

						$arr1[] = $stat->getDifference($ord['order_id'],0,1,false);
						$arr2[] = $stat->getDifference($ord['order_id'],1,2,false);
						$arr3[] = $stat->getDifference($ord['order_id'],2,5,false);
						$arr4[] = $stat->getDifference($ord['order_id'],5,6,false);
						$arr5[] = $stat->getDifference($ord['order_id'],0,6,false);
					}
					echo "</tbody>";
					echo "<tfoot>";
					echo "<tr>";
					echo "<td>Среднее время обработки</td>";
					echo "<td></td>";
					echo "<td></td>";
					echo "<td>" .getAverageTime($arr1). "</td>";
					echo "<td>" .getAverageTime($arr2). "</td>";
					echo "<td>" .getAverageTime($arr3). "</td>";
					echo "<td>" .getAverageTime($arr4). "</td>";
					echo "<td>" .getAverageTime($arr5). "</td>";
					echo "</tr>";
					echo "</tfoot>";
					echo "</table>";
				?>
			</div>
		</div>
	</div>
</div>

<!-- История заказа (всплывающее окно) -->
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
