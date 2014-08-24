<?php
	include_once "common/base.php";
	$pageTitle = "Sign Up";
	include_once "common/header.php";
?>
	<script type="text/javascript">
		function validateForm() {
			$('.error').remove();
			$('.invalid').removeClass('invalid');
			var error = false;
			var emailField = $('#email');
			var x = emailField.val();
			var atpos = x.indexOf("@");
			var dotpos = x.lastIndexOf(".");
			if (atpos< 1 || dotpos<atpos+2 || dotpos+2>=x.length) {
				emailField.addClass('invalid');
				emailField.after( $('<label class="error">Field cannot be left blank</label>') );
				error = true;
			}
			var pass1field = $('#password1');
			var pass2field = $('#password2');
			if ( pass1field.val().trim().length == 0 && pass1field.val().trim().length == 0 ) {
				pass1field.addClass('invalid');
				pass1field.after( $('<label class="error">Field cannot be left blank</label>') );
				error = true;
			} else if ( pass1field.val() !== pass2field.val() ) {
				pass2field.addClass('invalid');
				pass2field.after( $("<label class=\"error\">Passwords don't match</label>") );
				error = true;
			}
			if (error == false) {
				$(this).val("Submitting...");
				$.ajax({
					type: "POST",
					url: '/process-sign-up.php',
					data: "email=" + emailField.val() + "&pass=" + pass1field.val(),
					success: success,
					error: failure
				});
			}
		}
		
		function success(response) {
			window.location = '/';
		}
		
		function failure(response) {
			$('#submit').val('Submit');
			$('#password1').val('');
			$('#password2').val('');
			var error = response.responseText;
			if (error == 'used-email') {
				var emailField = $('#email');
				emailField.addClass('invalid');
				emailField.after( $('<label class="error">Email address is already in use. ' +
					'Forget your password?</label>') );
			} else {
				$('#sign-up-form').append( $('<p class="error">Error: ' + error + '</p>') );
			}
		}
	</script>
	<div id="padded-container">
	<h1>Sign Up</h1>
	<p>All you need is an email address and a password (not the password for your email).</p>
	<div id="sign-up-form">
		<div class="form-element">
		<label for="email">Email address</label>
		<input type="email" name="email" id="email" size="40">
		</div>
		<div class="form-element">
		<label for="password">Password</label>
		<input type="password" name="password1" id="password1" size="40">
		</div>
		<div class="form-element">
		<label for="password2">Verify Password</label>
		<input type="password" name="password2" id="password2" size="40">
		</div>
		<button id="submit" onClick="validateForm()">Sign Up</button>
	</div>
	</div>
<?php
	include_once "common/footer.php";
?>