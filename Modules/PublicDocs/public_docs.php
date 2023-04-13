<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa fa-download" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Документы для загрузки</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description." - ".$user_team_name."]";?></small>
			</div>
</div>
<style>
.icons-set a{
	margin: 0 10px;
}
.icons-set i.fa-trash{
	color: red; 
}
</style>
<div class="my-3 p-3 bg-white rounded box-shadow">	
	<div class='container-fluid'>
		<table class="table table-sm">
			<thead><tr><th>Файл</th><th>Описание</th><th>Дата</th><th>Действие</th></tr></thead>
			<tbody>
				<tr>
					<td><img src='/images/brand/excel.png' height='20px'></td>
					<td>Ведомость раздачи слонов</td>
					<td>23.04.2023</td>
					<td class='icons-set'>
						<a href='#'><i class='fas fa fa-download'></i></a>
						<a href='#'><i class='fas fa fa-trash'></i></a>
					</td>
				</tr>
				
			</tbody>
		</table>
	</div>
</div>


