<?php

/**
 * Handles user interactions within the app
 *
 * @author Kyle Combes
 * @copyright 2014 Kyle Combes
 */

class UserManager {
	
	/**
	 * Checks for a database object and creates one if none is found
	 *
	 * @param object $db
	 * @return void
	 */
	public function __construct($db=NULL) {
		if (is_object($db)) {
			$this->_db = $db;
		} else {
			$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
			$this->_db = new PDO($dsn, DB_USER, DB_PASS);
		}
	}
	
	/**
	 * Checks and inserts a new account email into the database
	 *
	 * @return string    a message indicating the action status
	 */
	public function createAccount()
	{
		$u = trim($_POST['email']);
		$p = $_POST['password1'];
		$v = sha1(time());

		$sql = "SELECT COUNT(Email) AS theCount
				FROM users
				WHERE Email=:email";
		if($stmt = $this->_db->prepare($sql)) {
			$stmt->bindParam(":email", $u, PDO::PARAM_STR);
			$stmt->execute();
			$row = $stmt->fetch();
			if($row['theCount']!=0) {
				return "<h2> Error </h2>"
					. "<p> Sorry, that email is already in use. "
					. "Please try again. </p>";
			}
			/*if(!$this->sendVerificationEmail($u, $v)) {
				return "<h2> Error </h2>"
					. "<p> There was an error sending your"
					. " verification email. Please "
					. "<a href="mailto:help@coloredlists.com">contact "
					. "us</a> for support. We apologize for the "
					. "inconvenience. </p>";
			}*/
			$stmt->closeCursor();
		}

		$sql = "INSERT INTO users(Email, Password, ver_code)
				VALUES(:email, MD5(:password), :ver)";
		if($stmt = $this->_db->prepare($sql)) {
			$stmt->bindParam(":email", $u, PDO::PARAM_STR);
			$stmt->bindParam(":password", $p, PDO::PARAM_STR);
			$stmt->bindParam(":ver", $v, PDO::PARAM_STR);
			$stmt->execute();
			$stmt->closeCursor();

		} else {
			return "<h2> Error </h2><p> Couldn't insert the "
				. "user information into the database. </p>";
		}
	}
	
	
	/**
	 * Checks credentials and logs in the user
	 *
	 * @return boolean    TRUE on success and FALSE on failure
	 */
	public function accountLogin()
	{
		$sql = "SELECT Email
				FROM users
				WHERE Email=:email
				AND Password=MD5(:pass)
				LIMIT 1";
		try
		{
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
			$stmt->bindParam(':pass', $_POST['password'], PDO::PARAM_STR);
			$stmt->execute();
			if($stmt->rowCount()==1)
			{
				$_SESSION['Email'] = htmlentities($_POST['email'], ENT_QUOTES);
				$_SESSION['LoggedIn'] = 1;
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		catch(PDOException $e)
		{
			return FALSE;
		}
	}
}

?>