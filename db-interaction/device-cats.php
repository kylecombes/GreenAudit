<?php

session_start();

include_once "../inc/constants.php";
include_once "../inc/class.devices.categories.php";

if(!empty($_GET['action'])
&& isset($_SESSION['LoggedIn'])
&& $_SESSION['LoggedIn']==1)
{
	$dc = new DevCats();
	switch($_GET['action'])
	{
		case 'get-children':
			$id = $_GET['id'];
			if (empty($id)) break;
			$res = $dc->getCategories($id, true);
			echo $res;
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