<?php

session_start();

include_once "../inc/constants.php";
include_once "../inc/class.room.manage.php";

if(!empty($_POST['action'])
&& isset($_SESSION['LoggedIn'])
&& $_SESSION['LoggedIn']==1)
{
	$rm = new RoomManager();
	switch($_POST['action'])
	{
		case 'add':
			echo $rm->addNewRoom();
			break;
		case 'update':
			$rm->updateRoom();
			break;
		/*case 'sort':
			$rm->changeListItemPosition();
			break;
		case 'done':
			echo $rm->toggleListItemDone();
			break;*/
		case 'delete':
			echo $rm->deleteRoom();
			break;
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