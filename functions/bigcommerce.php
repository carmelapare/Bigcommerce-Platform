<?php

//Creates new session
session_start();

include 'general_functions.php';

if(isset($_SESSION['imonggo_api_token'])){
  header('Location:main.php');
}

if(isset($_POST['login'])){
  
  $_SESSION['acct_id']=$_POST['acct_id'];
  $_SESSION['email']=$_POST['email'];
  $_SESSION['password']=$_POST['password'];

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

    <link type="text/css" rel="stylesheet" href="../assets/css/imonggo.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="../assets/css/login.css"  media="screen,projection"/> 
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Resource style -->

    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="../assets/css/materialize.min.css"  media="screen,projection"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  </head>

  <body>

  <!--Import jQuery before materialize.js-->
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="../assets/js/materialize.min.js"></script>
  <script src="../assets/js/modernizr.js"></script> <!-- Modernizr -->
  <script src="../assets/js/main.js"></script> <!-- Resource jQuery -->


  <div class="section no-pad-bot" id="index-banner">
    <div class="container">
      <div class="row center">
        <img src="../assets/icons/logo.png"></img>
        <br><br><br><br><br><br>
        <section class="cd-intro" style="margin-left:-40%;">
          <h1 class="cd-headline rotate-1">
            <span class="cd-words-wrapper">
              <b class="is-visible"style="letter-spacing:5px;">Manage products</b>
              <b style="letter-spacing:5px;">Update customers</b>
              <b style="letter-spacing:5px;">Transfer invoices</b>
              <b style="letter-spacing:5px;">Track inventories</b>
            </span>
          </h1>
          </section> <!-- cd-intro --> 
      </div>

      <div class="row center">
          <a class="modal-trigger waves-effect waves-light  waves-light orange accent-4 btn-large" data-target="login_form">LOGIN</a>
      </div>
      <br><br>
    </div>

    <div class="footer-copyright">
      <div class="row center">
      Made by <a class="orange-text text-lighten-3" href="http://materializecss.com">Materialize</a>
      </div>
    </div>
  </div>

  <!-- Modal Structure -->
  <form method="POST">
  <div id="login_form" class="modal modal-fixed-footer">
    <div class="modal-content" style="overflow:hidden">
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
          </div>
        </p>
    </div>
    <div class="modal-footer">
      <button class="btn waves-effect waves-light orange accent-4" type="submit" name="login">SUBMIT<i class="small material-icons right">send</i></button>
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