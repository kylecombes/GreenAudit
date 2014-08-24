<?php
	include_once "common/base.php";
	include_once "inc/class.users.php";
	
	if(!empty($_POST['email']) && !empty($_POST['pass'])) {
		
		$users = new UserManager($db);
		$email = $_POST['email'];
		$pass = $_POST['pass'];
		$result = $users->createAccount($email, $pass);
		if ($result == "success") {
			$users->accountLogin($email, $pass);
			print "";
		} else {
			error($result);
		}
	} else {
		error("illegal-args");
	}
	
	function error($result) {
		header('HTTP/1.1 500 Internal Server Error');
		header('Content-Type: application/json; charset=UTF-8');
		die($result);
	}
?>