<?php
// Настройка доступов

$module = $_GET['module'];

// Разрешения
$role_arr = array();
$role_arr['adm'] = ['UserList', 'UserRegistration', 'TaskList', 'CustomerList', 'TaskEdit', 'LibraryEdit', 'LibraryList', 'CreativeListView', 'Dashboard', 'HelpDesk', 'DocumentSetting', 'SendMessages', 'SendMailSending'];
$role_arr['mgr'] = ['ClientList', 'ClientRegistration', 'DocumentSetting', 'HelpDesk', 'SendMessages', 'SendMailSending'];
$role_arr['kln'] = ['Dashboard', 'HelpDesk', 'ClientHome', 'SendMessages'];


// Состав модулей
$module_arr = array();
$module_arr['CreativeApprovalEdit'] = ['/Modules/CreativeApprovalEdit/creative_approval_edit.php', '/Modules/CreativeApprovalEdit/creative_approval_edit.js'];
$module_arr['CreativeApprovalList'] = ['/Modules/CreativeApprovalList/creative_approval_list.php', '/Modules/CreativeApprovalList/creative_approval_list.js'];
$module_arr['CreativeEdit'] = ['/Modules/CreativeEdit/сreative_edit.php', '/Modules/CreativeEdit/creative_edit.js'];
$module_arr['CreativeList'] = ['/Modules/CreativeList/сreative_list.php', '/Modules/CreativeList/сreative_list.js'];
$module_arr['CreativeListView'] = ['/Modules/CreativeListView/creative_list_view.php', '/Modules/CreativeListView/creative_list_view.js'];
$module_arr['Dashboard'] = ['/Modules/Dashboard/dashboard.php', '/Modules/Dashboard/dashboard.js'];
$module_arr['HelpDesk'] = ['/Modules/HelpDesk/help_desk.php', '/Modules/HelpDesk/help_desk.js'];
$module_arr['LibraryEdit'] = ['/Modules/LibraryEdit/library_edit.php', '/Modules/LibraryEdit/library_edit.js'];
$module_arr['LibraryList'] = ['/Modules/LibraryList/library_list.php', '/Modules/LibraryList/library_list.js'];
$module_arr['RatingEdit'] = ['/Modules/RatingEdit/rating_edit.php', '/Modules/RatingEdit/rating_edit.js'];
$module_arr['RatingList'] = ['/Modules/RatingList/rating_list.php', '/Modules/RatingList/rating_list.js'];
$module_arr['TaskEdit'] = ['/Modules/TaskEdit/task_edit.php', '/Modules/TaskEdit/task_edit.js'];
$module_arr['TaskList'] = ['/Modules/TaskList/task_list.php', '/Modules/TaskList/task_list.js'];
$module_arr['TaskListDesigner'] = ['/Modules/TaskListDesigner/task_list_dsigner.php', '/Modules/TaskListDesigner/task_list_dsigner.js'];
$module_arr['UserList'] = ['/Modules/UserList/user_list.php', '/Modules/UserList/user_list.js'];
$module_arr['UserRegistration'] = ['/Modules/UserRegistration/user_registeration.php', '/Modules/UserRegistration/user_registeration.js'];
$module_arr['CustomerList'] = ['/Modules/CustomerList/customer_list.php', '/Modules/CustomerList/customer_list.js'];
// Состав новых модулей
$module_arr['DocumentSetting'] = ['/Modules/DocumentSetting/document_setting.php', '/Modules/DocumentSetting/document_setting.js'];
$module_arr['ClientList'] = ['/Modules/ClientList/client_list.php', '/Modules/ClientList/client_list.js'];
$module_arr['ClientRegistration'] = ['/Modules/ClientRegistration/client_registration.php', '/Modules/ClientRegistration/client_registration.js'];
$module_arr['ClientClientHome'] = ['/Modules/ClientHome/client_home.php', '/Modules/ClientHome/client_home.js'];
$module_arr['SendMessages'] = ['/Modules/SendMessages/send_messages.php', '/Modules/SendMessages/send_messages.js'];
$module_arr['SendMailSending'] = ['/Modules/SendMailSending/send_mail_sending.php', '/Modules/SendMailSending/send_mail_sending.js'];




// Дефолтные подключения
$def_arr = array();
$def_arr['adm'] = ['/Modules/Dashboard/dashboard.php', '/Modules/Dashboard/dashboard.js'];
$def_arr['mgr'] = ['/Modules/DocumentSetting/document_setting.php', '/Modules/DocumentSetting/document_setting.js'];
$def_arr['kln'] = ['/Modules/ClientHome/client_home.php', '/Modules/ClientHome/client_home.js'];
// $def_arr['kln'] = ['/Modules/Dashboard/dashboard.php', '/Modules/Dashboard/dashboard.js'];


// Вывод разрешенных значений
if(in_array($module, $role_arr[$user_role])){
	$link = $module_arr[$module][0];
	$js_local_source = $module_arr[$module][1];
}else{
	$link = $def_arr[$user_role][0];
	$js_local_source = $def_arr[$user_role][1];	
}

?>