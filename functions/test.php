<?php

session_start();

if(!isset($_SESSION['imonggo_api_token'])){
  header('Location:login.php');
}

include 'post_get.php';

//Gets products available on-hand
$inventories = array_filter(get_inventories());

//Gets tags for checkbox creation
$get_output = get_products();
$response = $get_output[0];
$tags = $get_output[1];
 

if(isset($_GET['post_products'])){

  if(!empty($inventories)){

    $post_tags = $_GET['checkbox_name'];
    post_products($response, $post_tags,$inventories);
      
  }else{

    $error_msg="Error: No product available";
    echo $error_msg;
  }


if(isset($_GET['post_all_products'])){

  if(!empty($inventories)){

    get_all_products($inventories);
      
  }else{

    $error_msg="Error: No product available";
    echo $error_msg;
  }  
}
       
  
}elseif(isset($_GET['pull_customers'])){

  get_customers();

}elseif(isset($_GET['update_customers'])){

  update_get_customers();

}elseif(isset($_GET['pull_invoices'])){

  if(!empty($inventories)){
    $results = get_invoices($inventories);
      
  }else{

    $error_msg="Error: No product available";
    echo $error_msg;
  }
  

}elseif(isset($_GET['update_inventory'])){

  get_inventory();

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
     <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="../assets/css/materialize.min.css"  media="screen,projection"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  </head>

  <body>

  <!--Import jQuery before materialize.js-->
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="../assets/js/materialize.min.js"></script>
  
  <div class ="container">
    <nav class ="black">
        <!-- Dropdown Structure -->
        <ul id="bigcommerce" class="dropdown-content">
          <li><a href="http://www.bigcommerce.com">Website</a></li>
          <li><a href="https://developer.bigcommerce.com/api">API</a></li>
        </ul>

        <!-- Dropdown Structure -->
        <ul id="imonggo" class="dropdown-content">
          <li><a href="http://www.imonggo.com">Website</a></li>
          <li><a href="http://support.imonggo.com/help/kb/api/introduction-to-imonggo-api">API</a></li>
        </ul>

        <a href="#" class="brand-logo left"><img id="logo" class ="responsive-img" src ="../assets/icons/logo.png"></img></a>
        <ul class="right hide-on-med-and-down">
          <!-- Dropdown Trigger -->
          <li><a class="dropdown-button" href="#!" data-activates="bigcommerce">Bigcommerce</a></li>
          <li><a class="dropdown-button" href="#!" data-activates="imonggo">Imonggo</a></li>
          <li><a href="http://localhost/phpmyadmin">Database</a></li>
          <li><a href="logout.php">Log Out</a></li>
        </ul>

        <ul id="slide-out" class="side-nav">
          <li><a href="#!">First Sidebar Link</a></li>
          <li><a href="#!">Second Sidebar Link</a></li>
          <li><a href="#!">First Sidebar Link</a></li>
          <li><a href="#!">Second Sidebar Link</a></li>
        </ul>
        <a href="#" data-activates="slide-out" class="button-collapse"><i class="mdi-navigation-menu"></i></a>
    </nav>


    <div class ="row">
      <div class="col s3 offset-s1" id="main_bg">
        <img class="responsive-img" id="main_bg" src="../assets/icons/main_bg.png" style="position:fixed">
      </div>

      <form method ="GET">
        <div class ="row" id="product_row">
          <div class="col s6">
            <p>
              <button class="btn waves-effect waves-light amber accent-3 btn-large" type="submit" name="post_all_products">Post All Products</button>
            </p>
          </div>

          <div class="col s8">
            <p>
              <a class="modal-trigger waves-effect waves-light amber accent-3 btn-large" data-target="multiple_prod">Post Multiple Products</a>
            </p>
          </div>

          <!-- Modal Structure -->
          <div id="multiple_prod" class="modal modal-fixed-footer">
            <div class="modal-content">
              <h4>ADD MULTIPLE PRODUCTS</h4>
                <hr>
                Select tags to filter products that will be posted
                <br><br>
                <ul>
                  <?php
                    $name=0;
                     if(count($tags) != 0){
                      foreach ($tags as $tag){
                        echo '<input type="checkbox" class="filled-in" id="filled-in-box'.$name.'" name="checkbox_name[]"  value ="'.$tag.'"/><label for="filled-in-box'.$name.'">'.$tag.'</label><br><br>';
                        $name ++;
                      }
                     }else{
                       echo '<h2>No tags available</h2>';
                     }
                  ?>
                </ul>
            </div>
            <div class="modal-footer">
              <button class="btn waves-effect waves-light black btn" type="submit" name="post_products">Continue</button>
            </div>
          </div><!--end of modal-->
        </div><!--end of product row-->

        <div class ="row" id="customer_row">
           <div class="col s12">
            <p>
              <button id="cust"class="waves-effect waves-light black btn-large" type="submit" name="pull_customers">Post Customers</button>
            </p>
          </div>
          <div class="col s12">
            <p>
              <button class=" waves-effect waves-light black btn-large" type="submit" name="update_customers">Update Customers</button>
            </p>
          </div>
        </div><!--end of customer row-->

        <div class ="row" id="invoice_row">
           <div class="col s6">
            <p>
               <button class="waves-effect waves-light amber accent-3 btn-large" type="submit" name="pull_invoices">Post Invoices</button>
            </p>
          </div>
        </div><!--end of customer row-->

      </form>
    </div>
  </div>
          



 <script>
  $(document).ready(function(){
    $('.modal-trigger').leanModal();
  });


  // Initialize collapse button
  $(".button-collapse").sideNav();
  // Initialize collapsible (uncomment the line below if you use the dropdown variation)
  //$('.collapsible').collapsible();

  </script>



  </body>
</html>