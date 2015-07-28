<?php

	session_start();

	//Ends session and redirects user to logout page
	session_unset();
	session_destroy();

	header('Location: login.php');
?>