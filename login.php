<?php

include 'general_functions.php';

session_start(); // Starting Session

$error=''; // Variable To Store Error Message

if(isset($_POST['login'])){
	if (empty($_POST['acct_id']) || empty($_POST['email']) || empty($_POST['password'])) {
	$error = "Account ID /Email/ Password is invalid";
	}else{
		// Define $username and $password
		$acct_id=$_POST['acct_id'];
		$email=$_POST['email'];
		$password=$_POST['password'];
		
		//for Security purpose
		$acct_id = stripslashes($acct_id);
		$email = stripslashes($email);
		$password = stripslashes($password);

		$acct_id = mysql_real_escape_string($acct_id);
		$email = mysql_real_escape_string($email);
		$password = mysql_real_escape_string($password);

		//Verify credentials from Imonggo
		$url = 'https://'.$acct_id.'imonggo.com/api/tokens.xml?email='.$email.'&password='.$password;
		$result = get_file($url, $acct_id, $password);	

		echo $result."haha";
		if($result->error === 'Not Found'){
			$error = "Account ID /Email/ Password is invalid";
		}else{
			echo 'check';
			$_SESSION['login_user']=$acct_id; // Initializing Session
			header("location: main.php"); // Redirecting To Other Page
		}
	}
}
?>