<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-window-restore" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Testing_Dashboard</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description." - ".$user_team_name."]";?></small>
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
</style>

<div class="my-3 p-3 bg-white rounded box-shadow">	
	<div class='container-fluid'>
		<div class="row">
			<div class="col-12 col-md-3 col-sm-12 one-block">
				<h5>Часы</h5>
				<div id='clock'></div>
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


<!--

<style>
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
</style>

<div class="my-3 p-3 bg-white rounded box-shadow">	
	<div class='container-fluid'>
		<div class="row">
			<div class="col-12 col-md-12 col-sm-12 one-block">
				
				<?php
				$user_id_go = $_GET['user_id']; // Id пользователя, по которому надо сформировать таблицу

				if($user_id_go != ''){
					echo "<div class='superTitle'><h5>Пульс системы</h5><a href='/'><span class='badge badge-primary'>Показать всех</span></a></div>";
				}else{
					echo "<div class='superTitle'><h5>Пульс системы</h5></div>";
				}

				include_once($_SERVER['DOCUMENT_ROOT']."/Login/classes/dbconnect.php");
				$stat = new getStatistic($pdo);

 				// Проверяем - нет ли задачи по получению информацтт об одном юзере
				if ($user_id_go != ''){
					$stm = $pdo->prepare("SELECT order_id, order_key, order_type FROM orders WHERE user_id =?");
					$stm->execute([$user_id_go]);
				}else{
					$stm = $pdo->prepare("SELECT order_id, order_key, order_type FROM orders WHERE 1");
					$stm->execute();
				}				
				$orders = $stm->fetchAll(PDO::FETCH_ASSOC);

				echo "<table class='table table-sm pulse' id='ordersTable'>";
				echo "<thead>";
				echo "<tr><th>Заказ</th><th>Клиент</th><th>Заказ принят<br>в работу</th><th>Отправлено первое<br>предложение</th><th>Заказ одобрен<br>клиентом</th><th>Отправлен<br>на формирование</th><th>Заказ<br>общее время</th></tr>";
				echo "</thead>";
				echo "<tbody>";
				foreach($orders as $ord){

					echo "<tr>";
					echo "<td><a href ='#' class='orderHistory'>". $ord['order_key'] . "_".$ord['order_type'] . "</a></td>";
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

-->