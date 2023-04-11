<div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded box-shadow">
	<span style="margin-right: 10px"><i class="fas fa-user-friends" style="font-size: 2.5rem;"></i></span>
	<div class="lh-100">
		<h6 class="mb-0 text-white lh-100">Список пользователей</h6>
		<small><?php echo $user_name." " .$user_surname. " [".$user_role_description."]";?></small>
	</div>
</div>
<div class="my-3 p-3 bg-white rounded box-shadow">

	<div id="main"></div>
	
<!-- Модальное окно Просмотр и удаление пользователей -->
	<div class="modal fade" id="EditUser" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Редактор пользователя</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
				</div>
			</div>
		</div>
	</div>
</div>



