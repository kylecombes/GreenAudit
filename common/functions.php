<?php

function isLoggedIn() {
	return isset($_SESSION['LoggedIn']) && isset($_SESSION['Email']) && $_SESSION['LoggedIn']==1;
}

?>