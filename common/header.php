<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
	include_once "common/functions.php";
	
	if (isset($requiresLogin) && $requiresLogin == false && !isLoggedIn()) {
		header('Location: /login');
	}
?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $pageTitle ?> | GreenAudit</title>
    <link rel="stylesheet" href="/common/style.css" type="text/css" />
    <script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js?ver=1.3.2'></script>
</head>

<body>
	<div id="top-bar"></div>
	<div id="main-container">
		<header>
			<nav><ul>
				<?php if (isLoggedIn()): ?>
					<li><a href="/">My Rooms</a></li>
					<li><a id="user-id"><?php echo $_SESSION['Email'] ?></a>
						<ul>
							<li><a href="/logout">Log out</a></li>
						</ul>
					</li>
				<?php else: ?>
					<li><a href="/login">Login</a></li>
				<?php endif; ?>
			</ul></nav>
			<div id="session-info">
			</div>
		</header>
		<div id="content-container">