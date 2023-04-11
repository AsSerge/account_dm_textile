<?php
// Настройка доступов
if($user_role == 'adm'){
	$module = $_GET['module'];
	switch($module){
		case 'UserList':
				$link = '/Modules/UserList/user_list.php';
				$js_local_source = '/Modules/UserList/user_list.js';
				break;
		case 'UserRegistration':
				$link = '/Modules/UserRegistration/user_registeration.php';
				$js_local_source = '/Modules/UserRegistration/user_registeration.js';
				break;
		case 'HelpDesk':
				$link = '/Modules/HelpDesk/help_desk.php';
				$js_local_source = '/Modules/HelpDesk/help_desk.js';
				break;
		case 'Dashboard':
				$link = '/Modules/Dashboard/dashboard.php';
				$js_local_source = '/Modules/Dashboard/dashboard.js';
				break;	
		default:
			$link = '/Modules/Dashboard/dashboard.php';
			$js_local_source = '/Modules/Dashboard/dashboard.js';
	}
}elseif ($user_role == 'mgr'){
	$module = $_GET['module'];
	switch($module){
		case 'TaskList':
				$link = '/Modules/TaskList/task_list.php';
				$js_local_source = '/Modules/TaskList/task_list.js';
				break;
		case 'CustomerList':
				$link = '/Modules/CustomerList/customer_list.php';
				$js_local_source = '/Modules/CustomerList/customer_list.js';
				break;
		case 'TaskEdit':
				$link = '/Modules/TaskEdit/task_edit.php';
				$js_local_source = '/Modules/TaskEdit/task_edit.js';
				break;
		case 'CreativeApprovalList':
				$link = '/Modules/CreativeApprovalList/creative_approval_list.php';
				$js_local_source = '/Modules/CreativeApprovalList/creative_approval_list.js';
				break;
		case 'CreativeApprovalEdit':
				$link = '/Modules/CreativeApprovalEdit/creative_approval_edit.php';
				$js_local_source = '/Modules/CreativeApprovalEdit/creative_approval_edit.js';
				break;
		case 'CreativeListView':
				$link = '/Modules/CreativeListView/creative_list_view.php';
				$js_local_source = '/Modules/CreativeListView/creative_list_view.js';
				break;
		case 'HelpDesk':
				$link = '/Modules/HelpDesk/help_desk.php';
				$js_local_source = '/Modules/HelpDesk/help_desk.js';
				break;
		case 'LibraryList':
				$link = '/Modules/LibraryList/library_list.php';
				$js_local_source = '/Modules/LibraryList/library_list.js';
				break;
		case 'Dashboard':
				$link = '/Modules/Dashboard/dashboard.php';
				$js_local_source = '/Modules/Dashboard/dashboard.js';
				break;	
		default:
			$link = '/Modules/Dashboard/dashboard.php';
			$js_local_source = '/Modules/Dashboard/dashboard.js';
	}
}elseif ($user_role == 'kln'){
	$module = $_GET['module'];
	switch($module){
		case 'RatingEdit':
				$link = '/Modules/RatingEdit/rating_edit.php';
				$js_local_source = '/Modules/RatingEdit/rating_edit.js';
				break;
		case 'RatingList':
				$link = '/Modules/RatingList/rating_list.php';
				$js_local_source = '/Modules/RatingList/rating_list.js';
				break;
		default:
			$link = '/Modules/RatingList/rating_list.php';
			$js_local_source = '/Modules/RatingList/rating_list.js';
	}
}
?>