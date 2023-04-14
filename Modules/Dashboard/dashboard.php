<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
			<span style="margin-right: 10px"><i class="fas fa-drafting-compass" style="font-size: 2.5rem;"></i></span>
			<div class="lh-100">
				<h6 class="mb-0 text-white lh-100">Dashboard</h6>
				<small><?php echo $user_name." " .$user_surname. " [".$user_role_description." - ".$user_team_name."]";?></small>
			</div>
</div>

<div class="my-3 p-3 bg-white rounded box-shadow">	
	<div class='container-fluid'>
		<h4>Общая статистика</h4>
		<div id='all'></div>
		<div id='adm'></div>
		<div id='mgr'></div>
		<div id='kln'></div>
	</div>
</div>


