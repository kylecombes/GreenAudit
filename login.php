<?php
	include_once "common/base.php";
	$pageTitle = "Home";
	include_once "common/header.php";

	if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Email'])):
?>
		<h3>You are currently logged in.</h3>
		<p><a href="/logout">Log out</a></p>
<?php
	else:
		if(!empty($_POST['email']) && !empty($_POST['password'])):
			include_once 'inc/class.users.php';
			$users = new UserManager($db);
			if($users->accountLogin($_POST['email'], $_POST['password'])===TRUE):
				echo "<meta http-equiv='refresh' content='0;/'>";
				exit;
			else:
?>
		<h2>Login Failed</h2>
<?php
			endif;
		endif;
?>
		<table style="width:100%;margin:2em 0;"><tr>
		<td style="width:50%;padding:0 2em;border-right: 1px solid #cccccc;">
			<h1>Sign In</h1>
			<form method="POST" action="login" name="loginform" id="loginform">
				<div>
					<label for="email">Email</label><br>
					<input type="text" name="email" id="email" size="40"/>
					<br /><br />
					<label for="password">Password</label><br>
					<input type="password" name="password" id="password" size="40"/>
					<br /><br />
					<label><a href="/password.php">Did you forget your password?</a></label>
					<br /><br />
					<input type="submit" name="login" id="login" value="Login" />
				</div>
			</form>
			<p></p>
		</td>
		
		<td style="width:50%;padding:0 2em;">
			<h2>Not signed up yet?</h2>
			<p>Don't worry, it's easy and only takes a minute!</p>
			<a class="blue-button" style="float:right;" href="/sign-up">Sign up</a>
		</td></tr></table>
<?php
	endif;
	include_once "common/footer.php";
?>