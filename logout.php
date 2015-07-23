<?php
session_start();

// Destroying all sessions
if(session_destroy()) {
	// Redirecting To Home Page
	header("Location: index.php");
}

?>