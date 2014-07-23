<?php

session_start();

include_once "../inc/constants.php";
include_once "../inc/class.room.list.php";

if(!empty($_POST['action'])
&& isset($_SESSION['LoggedIn'])
&& $_SESSION['LoggedIn']==1)
{
	$listObj = new DeviceListManager();
	switch($_POST['action'])
	{
		case 'add':
			echo $listObj->addNewRoom();
			break;
		case 'update':
			$listObj->updateRoom();
			break;
		/*case 'sort':
			$listObj->changeListItemPosition();
			break;
		case 'done':
			echo $listObj->toggleListItemDone();
			break;
		case 'delete':
			echo $listObj->deleteListItem();
			break;*/
		default:
			header("Location: /");
			break;
	}
}
else
{
	error_log("Tried to save room but not logged in.");
	header("Location: /");
	exit;
}

?>