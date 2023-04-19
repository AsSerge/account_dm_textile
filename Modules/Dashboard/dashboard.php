<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-drafting-compass" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Dashboard</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description." - ".$user_team_name."]";?></small>
			</div>
</div>

<style>
.one-block{	
	padding-bottom: 1rem;
}
</style>

<div class="my-3 p-3 bg-white rounded box-shadow">	
	<div class='container-fluid'>
		<div class="row">
			<div class="col-12 col-md-4 col-sm-12 one-block">
				<h5>Пользователи</h5>
				<div id='usr'></div>
			</div>

			<div class="col-12 col-md-4 col-sm-12 one-block">
				<h5>Заказы</h5>
				<div id='ord'></div>
			</div>

			<div class="col-12 col-md-4 col-sm-12 one-block">
				<h5>Группы</h5>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
			</div>

		</div>
	</div>
</div>


