<?php

//Creates new session
session_start();

include 'general_functions.php';

if(isset($_SESSION['imonggo_api_token'])){
  header('Location:main.php');
}

if(isset($_POST['login'])){
  
  //Sets necessary credentials from Imonggo and Bigcommerce 
  $_SESSION['acct_id']=$_POST['acct_id'];
  $_SESSION['email']=$_POST['email'];
  $_SESSION['password']=$_POST['password'];
  $_SESSION['username']=$_POST['username'];
  $_SESSION['token_api']=$_POST['token_api'];
  $_SESSION['url']=$_POST['url'];

  //Verifies credentials from Imonggo
  $url = 'https://'.$_SESSION['acct_id'].'.c3.imonggo.com/api/tokens.xml?email='.$_SESSION['email'].'&password='.$_SESSION['password'];
  $result = (string)get_token($url);

  if($result != NULL){
      
    $_SESSION['imonggo_api_token']= $result;
    header('Location:main.php'); 

  }else{
    //Prompts error message
  }
} 

?>

<!DOCTYPE html>
<html lang="en" class="no-js">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

    <link type="text/css" rel="stylesheet" href="../assets/css/login_form.css"  media="screen,projection"/>
     <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="../assets/css/materialize.min.css"  media="screen,projection"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  </head>

  <body >

  <!--Import jQuery before materialize.js-->
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="../assets/js/materialize.min.js"></script>

  <div class="section no-pad-bot" id="index-banner">
    <div class ="container">
      <div class ="row">
        <div class="col s3 offset-s1">
          <img class="responsive-img" src="../assets/icons/bg.png" style="position:fixed">
        </div>
        <div class="col s5 offset-s7">
           <a class="modal-trigger waves-effect waves-light  waves-light yellow accent-4 btn-large" id="login_btn" data-target="login_form">LOGIN</a>
        </div>
      </div>
    </div>

    <div class="row center">
      Made by <a class="orange-text text-lighten-3" href="http://materializecss.com">Materialize</a>
    </div>   
  </div>

  <form method="POST">
  <div id="login_form" class="modal modal-fixed-footer">
    <div class="modal-content" style="overflow:auto">
      <h5>IMONGGO ACCOUNT</h5>
        <p>
          <div class="row" id="input_fields">
            <div class="row">
              <div class="input-field col s12">
                <input id="acct_id" name="acct_id" type="text" class="validate" required>
                <label for="acct_id">Account ID<i class="tiny material-icons left">account_box</i></label>
              </div>
            </div>

           <div class="row">
              <div class="input-field col s12">
                <input id="email" name="email" type="email" class="validate" required>
                <label for="email" data-error="Invalid" data-success="right">Email<i class="tiny material-icons left">email</i></label>
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12">
                <input id="password" name="password" type="password" class="validate" required>
                <label for="password">Password<i class="tiny material-icons left">lock</i></label>
              </div>
            </div>

            <h5>BIGCOMMERCE ACCOUNT</h5>

            <div class="row">
              <div class="input-field col s12">
                <input id="username" name="username" type="text" class="validate" required>
                <label for="username">Username<i class="tiny material-icons left">account_box</i></label>
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12">
                <input id="token_api" name="token_api" type="password" class="validate" required>
                <label for="token_api">API TOKEN<i class="tiny material-icons left">lock</i></label>
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12">
                <input id="url" name="url" type="text" class="validate" required>
                <label for="url">Store ID<i class="tiny material-icons left">store</i></label>
              </div>
            </div>

          </div>
        </p>
    </div>
    <div class="modal-footer">
      <button class="btn waves-effect waves-light yellow accent-4" type="submit" name="login">SUBMIT<i class="small material-icons right">send</i></button>
    </div>
  </div>
  </div>
  </form>

   <script>
    $(document).ready(function(){
      $('.modal-trigger').leanModal();
    });
  </script>

  </body>
</html>