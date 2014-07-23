<?php
	include_once "common/base.php";
	$pageTitle = "Sign Up";
	include_once "common/header.php";

	if(!empty($_POST['email'])) {
		if (empty($_POST['password2'])) {
			$error = "Please verify your password.";
		} else if (strcmp($_POST['password1'], $_POST['password2']) !== 0) {
			$error = "Passwords don't match.";
		} else {
			include_once "inc/class.users.php";
			$users = new UserManager($db);
			echo $users->createAccount();
		}
	}
	
?>

	<h1>Sign Up</h1>
	<form action="sign-up" method="POST">
		Email address: <input type="text" name="email" size="40"><br>
		Password: <input type="password" name="password1" size="40"><br>
		<?php if (isset($error)):?><span class="error"><?php echo $error ?></span><br><?php endif; ?>
		Verify Password: <input type="password" name="password2" size="40"></br>
		<input type="submit" value="Sign Up">
	</form>

<?php
	include_once "common/footer.php";
?>