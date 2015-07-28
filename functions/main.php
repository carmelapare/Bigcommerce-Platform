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
  

<div id="page-wrap">

  <div class ="container">
    <div class = "row">
      <nav>
        <!-- Dropdown Structure -->
        <ul id="bigcommerce" class="dropdown-content">
          <li><a href="http://www.bigcommerce.com">Website</a></li>
          <li><a href="https://developer.bigcommerce.com/api">API Documentation</a></li>
          <li class="divider"></li>
          <li><a href="#!">three</a></li>
        </ul>

        <!-- Dropdown Structure -->
        <ul id="imonggo" class="dropdown-content">
          <li><a href="http://www.imonggo.com">Website</a></li>
          <li><a href="http://support.imonggo.com/help/kb/api/introduction-to-imonggo-api">API Documentation</a></li>
          <li class="divider"></li>
          <li><a href="#!">three</a></li>
        </ul>

        <a href="#" class="brand-logo left"><i class="medium material-icons">swap_vertical_circle</i></a>
        <ul class="right hide-on-med-and-down">
          <!-- Dropdown Trigger -->
          <li><a class="dropdown-button" href="#!" data-activates="bigcommerce">Bigcommerce</a></li>
          <li><a class="dropdown-button" href="#!" data-activates="imonggo">Imonggo</a></li>
          <li><a href="logout.php">Log Out</a></li>

          <li><a href="http://localhost/phpmyadmin">Database</a></li>
        </ul>

        <ul id="slide-out" class="side-nav">
          <li><a href="#!">First Sidebar Link</a></li>
          <li><a href="#!">Second Sidebar Link</a></li>
          <li><a href="#!">First Sidebar Link</a></li>
          <li><a href="#!">Second Sidebar Link</a></li>
        </ul>
        <a href="#" data-activates="slide-out" class="button-collapse"><i class="mdi-navigation-menu"></i></a>
      </nav>


      <ul class="collapsible" data-collapsible="accordion">

        <li>
          <div class="collapsible-header active black"><i class="material-icons">label</i>Add Product</div>
            <div class="collapsible-body">
              <div class ="container">
                <div class="row" id ="product">
                  <form method ="GET">
                    <div class="col s6">
                      <p>
                       <button class="btn waves-effect waves-light light-blue accent-3 btn" type="submit" name="post_all_products">Post All Products</button>
                      </p>
                      </div>

                    <div class="col s6">
                      <p>
                        <a class="modal-trigger waves-effect waves-light light-blue accent-3 btn-large" data-target="multiple_prod">Add Multiple Products</a>
                        <br><br>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. In efficitur quis urna et consequat. Ut ante turpis, condimentum non mattis quis, mattis eu lacus. Sed quis consequat nibh.
                      </p>

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
                                 echo '<input type="checkbox" class="filled-in" id="filled-in-box_select_all_tags" name="select_all_tags"  value ="select_all_tags" onchange="select_all_tags()"/><label for="filled-in-box_select_all_tags">Select all tags</label><br><br>';
                              ?>
                            </ul>
                        </div>
                        <div class="modal-footer">
                          <button class="btn waves-effect waves-light light-blue accent-3 btn" type="submit" name="post_products">Continue</button>
                        </div>
                      </div><!--end of modal-->

                      </div>
                  </form>
                </div>
            </div>
          </div>
        </li>

        <li>
          <div class="collapsible-header blue-grey darken-4"><i class="material-icons">contacts</i>Add Customer</div>
            <div class="collapsible-body">
              <div class ="container">
                <div class="row">
                  <form method ="GET">
                    <div class="col s6">
                      <p>
                        <button class="waves-effect waves-light light-blue accent-3 btn-large" type="submit" name="pull_customers">Add Customer</button>
                         <button class="waves-effect waves-light light-blue accent-3 btn-large" type="submit" name="update_customers">Update Customer</button>
                        <br><br>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. In efficitur quis urna et consequat. Ut ante turpis, condimentum non mattis quis, mattis eu lacus. Sed quis consequat nibh.
                      </p>
                    </div>

                    <div class="col s6">
                      <br><br>
                      <img src ="../assets/icons/customer.png">
                    </div>
                  </form>
                </div>
            </div>
          </div>
        </li>

        <li>
          <div class="collapsible-header blue-grey darken-1"><i class="material-icons">receipt</i>Post Invoice</div>
            <div class="collapsible-body">
              <div class ="container">
                <div class="row">
                  <form method ="GET">

                    <div class="col s6">
                      <br><br>
                      <img src ="../assets/icons/invoice.png">
                    </div>

                    <div class="col s6">
                      <p>
                        <button class="waves-effect waves-light light-blue accent-3 btn-large" type="submit" name="pull_invoices">Post Invoice</button>
                        <br><br>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. In efficitur quis urna et consequat. Ut ante turpis, condimentum non mattis quis, mattis eu lacus. Sed quis consequat nibh.

                      </p>
                    </div>

                  </form>
                </div>
            </div>
          </div>
        </li>
      </ul>
    </div><!--end of row-->
  </div><!--end of container-->

 <script>
  $(document).ready(function(){
    $('.modal-trigger').leanModal();
  });

  // Initialize collapse button
  $(".button-collapse").sideNav();
  // Initialize collapsible (uncomment the line below if you use the dropdown variation)
  //$('.collapsible').collapsible();

  </script>

  <script type="text/javascript">
  function select_all_tags() {
    alert("hahha");
    var x = document.getElementsByName("checkbox_name[]");
    var y = document.getElementsByName("select_all_tags");
    var i;
    if(y.checked === true){

       for (i = 0; i < x.length; i++) {
         x[i].checked = "checked";
       }
    }
  }
  </script>

  </body>
</html>