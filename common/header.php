<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
				<li><a href="/">Home</a></li>
				<li><a href="/sign-up">Sign Up</a></li>
			</ul></nav>
			<div id="session-info">
				<?php if(isset($_SESSION['LoggedIn']) && isset($_SESSION['Email'])
					&& $_SESSION['LoggedIn']==1): ?>
					<span id="user-id"><?php echo $_SESSION['Email'] ?></span><span id="logout"><a href="/logout">Log out</a></span>
				<?php else: ?>
					<div id="sign-in"><a href="/login">Sign Up/Sign In</a></div>
				<?php endif; ?>
			</div>
		</header>
		<div id="content-container">