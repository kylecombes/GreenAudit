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
			if($users->accountLogin()===TRUE):
				echo "<meta http-equiv='refresh' content='0;/'>";
				exit;
			else:
?>
		<h2>Login Failed</h2>
<?php
			endif;
		endif;
?>
		<div class="half-width" style="border-right: 1px solid #cccccc;">
			<h2>Sign In</h2>
			<form method="POST" action="login" name="loginform" id="loginform">
				<div>
					<label for="email">Email</label><br>
					<input type="text" name="email" id="email" size="40"/>
					<br /><br />
					<label for="password">Password</label><br>
					<input type="password" name="password" id="password" size="40"/>
					<br /><br />
					<input type="submit" name="login" id="login" value="Login" class="button" />
				</div>
			</form><br /><br />
			<p><a href="/password.php">Did you forget your password?</a></p>
		</div>
		
		<div class="half-width">
			<h2>Sign Up</h2>
			<form action="sign-up" method="POST">
				Email address<br><input type="text" name="email" size="40"><br><br>
				Password<br><input type="password" name="password1" size="40"><br><br>
				<?php if (isset($error)):?><span class="error"><?php echo $error ?></span><br><?php endif; ?>
				Verify Password<br><input type="password" name="password2" size="40"></br><br>
				<input type="submit" value="Sign Up">
			</form>
		</div>
<?php
	endif;
	include_once "common/footer.php";
?>