<?php

include 'general_functions.php';
include 'API_keys.php';

//=======================================PARSE ALL PRODUCTS==================================
//This function posts products on Bigcommerce without tag filters
  function parse_all_products($url, $xml_file,$username, $pw,$inventories){

  foreach($xml_file->product as $product){

   //Checks if there is product available on-hand(based on inventory)
    if(in_array($product->id, $inventories)){

      //1. Checks if product does not exist in Bigcommerce
      //Gets product ID from database
      $query = "SELECT bigcommerce_id FROM product_invoice where imonggo_id='$product->id'";
      $result = mysql_query($query);
      $row = mysql_fetch_array($result);

      if($row[0] == NULL){
        //1.1 Checks if product on imonggo is not deleted 
        //If deleted, nothing will be posted on Bigcommerce
        if($product->status!='D'){
              
        //Posts product on bigcommerce
          $xml_product_content =
          '<?xml version="1.0" encoding="UTF-8"?>
          <product>
            <name>'.(string)$product->name.'</name>
            <type>physical</type>
            <description>'.(string)$product->description.'</description>
            <price>'.(string)$product->retail_price.'</price>
            <is_visible>true</is_visible>
            <categories>
              <value>2</value>
            </categories>
            <availability>available</availability>
            <weight>0.0</weight>
          </product>';

        //Adds product to database for order mapping on invoice
          $post_result = post_file($url, $xml_product_content,$username, $pw);
          post_to_db(simplexml_load_string($post_result),$product->id);
        }
      }
      //2. Product already exists in Bigcommerce
      else{
        //2.1 Checks if product is not deleted on Imonggo
        if($product->status!="D"){
        //Updates product on bigcommerce
          $xml_product_content =
          '<?xml version="1.0" encoding="UTF-8"?>
          <product>
            <name>'.(string)$product->name.'</name>
            <type>physical</type>
            <description>'.(string)$product->description.'</description>
            <price>'.(string)$product->retail_price.'</price>
            <is_visible>true</is_visible>
            <categories>
              <value>2</value>
            </categories>
            <availability>available</availability>
            <weight>0.0</weight>
          </product>';

          //Gets product ID from database
          $query = "SELECT bigcommerce_id FROM product_invoice where imonggo_id='$product->id'";
          $result = mysql_query($query);
          $row = mysql_fetch_array($result);

          $url_put=$GLOBALS['bigcommerce_URL'].'/api/v2/products/'.$row[0];

          $put_result = put_file($url_put, $xml_product_content,$username, $pw);
        }

        //If deleted, hide product from Bigcommerce Store regardless the tag of the product
        else{

        //Updates product on bigcommerce
        $xml_product_content =

        '<?xml version="1.0" encoding="UTF-8"?>
          <product>
            <name>'.(string)$product->name.'</name>
            <type>physical</type>
            <description>'.(string)$product->description.'</description>
            <price>'.(string)$product->retail_price.'</price>
            <is_visible>false</is_visible>
            <categories>
              <value>2</value>
            </categories>
            <availability>available</availability>
            <weight>0.0</weight>
          </product>';

          //Gets product id from database
          $query = "SELECT bigcommerce_id FROM product_invoice where imonggo_id='$product->id'";
          $result = mysql_query($query);
          $row = mysql_fetch_array($result);

          $url_put=$GLOBALS['bigcommerce_URL'].'/api/v2/products/'.$row[0];

          $put_result = put_file($url_put, $xml_product_content,$username, $pw);
        }
      }
    }
  }
}

//=======================================PARSE PRODUCTS======================================

  //This function parses product tags and the xml file to be posted on Imonggo or Bigcommerce.
	function parse_products($url, $xml_file,$username, $pw,$tags,$inventories){

		foreach($xml_file->product as $product){
      echo $product->status.'='.$product->name;
      echo '<br>';

      //If there are no selected tags, post all products
      if(count($tags)!=0){

        //Parses product's tags
        $product_tags=explode(",",preg_replace('/\s+/','', strtolower($product->tag_list)));
        $intersect_count=0;

        //Checks if at least one filter tag intersects with each product's tags
        if(count(array_intersect($product_tags,$tags))!=0){
          $intersect_count=1;
        }

        //Checks if there is product available on-hand(based on inventory)
        if(in_array($product->id, $inventories)){

          //1. Checks if product does not exist in Bigcommerce
          //Gets product ID from database
          $query = "SELECT bigcommerce_id FROM product_invoice where imonggo_id='$product->id'";
          $result = mysql_query($query);
          $row = mysql_fetch_array($result);


          if($row[0] == NULL){

            //1.1 Checks if product on imonggo is not deleted and contains the tag/s selected
            //If deleted, nothing will be posted on Bigcommerce
            if($product->status!='D' && $intersect_count==1){

              echo $product->name.':'.$product->status.'NOT D, NOT EX <br>';
              
              //Posts product on bigcommerce
              $xml_product_content =

                '<?xml version="1.0" encoding="UTF-8"?>
                  <product>
                    <name>'.(string)$product->name.'</name>
                    <type>physical</type>
                    <description>'.(string)$product->description.'</description>
                    <price>'.(string)$product->retail_price.'</price>
                    <is_visible>true</is_visible>
                    <categories>
                       <value>2</value>
                        </categories>
                    <availability>available</availability>
                    <weight>0.0</weight>
                  </product>';

              //Adds product to database for order mapping on invoice
              $post_result = post_file($url, $xml_product_content,$username, $pw);
              post_to_db(simplexml_load_string($post_result),$product->id);
            }
          }

          //2. Product already exists in Bigcommerce
          else{
            
            //2.1 Checks if product is not deleted on Imonggo and contains the tag/s selected
            if($product->status !='D' && $intersect_count==1){

               echo $product->name.':'.$product->status.'NOT D,  EX <br>';

              //Updates product on bigcommerce
              $xml_product_content =

                '<?xml version="1.0" encoding="UTF-8"?>
                  <product>
                    <name>'.(string)$product->name.'</name>
                    <type>physical</type>
                    <description>'.(string)$product->description.'</description>
                    <price>'.(string)$product->retail_price.'</price>
                    <is_visible>true</is_visible>
                    <categories>
                       <value>2</value>
                        </categories>
                    <availability>available</availability>
                    <weight>0.0</weight>
                  </product>';

              //Gets product ID from database
              $query = "SELECT bigcommerce_id FROM product_invoice where imonggo_id='$product->id'";
              $result = mysql_query($query);
              $row = mysql_fetch_array($result);

              $url_put=$GLOBALS['bigcommerce_URL'].'/api/v2/products/'.$row[0];

              $put_result = put_file($url_put, $xml_product_content,$username, $pw);
            }

            //If deleted, hide product from Bigcommerce Store regardless the tag of the product
            elseif($product->status =='D'){
               echo $product->status;
               echo $product->name.':'.$product->status.' D,  EX <br>';
              //Updates product on bigcommerce
              $xml_product_content =

                '<?xml version="1.0" encoding="UTF-8"?>
                  <product>
                    <name>'.(string)$product->name.'</name>
                    <type>physical</type>
                    <description>'.(string)$product->description.'</description>
                    <price>'.(string)$product->retail_price.'</price>
                    <is_visible>false</is_visible>
                    <categories>
                       <value>2</value>
                        </categories>
                    <availability>available</availability>
                    <weight>0.0</weight>
                  </product>';

              //Gets product id from database
              $query = "SELECT bigcommerce_id FROM product_invoice where imonggo_id='$product->id'";
              $result = mysql_query($query);
              $row = mysql_fetch_array($result);

              $url_put=$GLOBALS['bigcommerce_URL'].'/api/v2/products/'.$row[0];

              $put_result = put_file($url_put, $xml_product_content,$username, $pw);
            }
          }
        }
      }

    }
  }//End of parse products


//Maps Imonggo and Bigcommerce products
function post_to_db($result,$id){

  //checks if product is already in imonggo
  $query = "SELECT imonggo_id FROM product_invoice where imonggo_id='$id'";
  $result1 = mysql_query($query);
  $row = mysql_fetch_array($result1);

  //if product does not exist in imonggo, insert into database
  if($row[0] == NULL){

    $insert_to_product_invoice = mysql_query("INSERT INTO product_invoice (imonggo_id,bigcommerce_id) VALUES('$id','$result->id')");

  //if product already exists in imonggo, update product on database
  }else{
    $update_product_invoice = mysql_query("UPDATE product_invoice  SET bigcommerce_id= '$result->id' WHERE imonggo_id='$id'");   
  }
}


//=======================================PARSE CUSTOMERS======================================

  //This function parses customer fields to be posted on Imonggo or Bigcommerce.
	function parse_customers($url, $xml_file, $username, $pw){

    //If there are customers on Bigcommerce 
    if($xml_file != NULL){

  		foreach($xml_file->customer as $customer){

        //Gets product ID from database
        $query = "SELECT imonggo_id FROM customer where bigcommerce_id='$customer->id'";
        $result = mysql_query($query);
        $row = mysql_fetch_array($result);

        //1. Checks if customer does not exist in Imonggo
        if($row[0] == NULL){

              //Posts customer; duplication is handled by alternate_code
              $xml =  
                '<?xml version="1.0" encoding="UTF-8"?>
                <customer>
        
                  <alternate_code>'.(string)$customer->id.'</alternate_code>
                  <first_name>'.(string)$customer->first_name.'</first_name>
                  <last_name>'.(string)$customer->last_name.'</last_name>
                  <email>'.(string)$customer->email.'</email>
                </customer>';

                echo $xml;
              $result = post_file($url, $xml, $username, $pw);

              //Posts customer to database for mapping
              post_to_db_customer(simplexml_load_string($result),(string)$customer->id);
  		  }
      }
    }
	}

  //Maps Imonggo and Bigcommerce products
  function post_to_db_customer($result,$bigcommerce_id){

    $imonggo_id = (string)$result->id;

    //checks if product is already in imonggo
    $query = "SELECT imonggo_id FROM customer where imonggo_id='$imonggo_id'";
    $result1 = mysql_query($query);
    $row = mysql_fetch_array($result1);

    //if product does not exist in imonggo, insert into database
    if($row[0] == NULL){

      $insert_customer = mysql_query("INSERT INTO customer (imonggo_id,bigcommerce_id) VALUES('$imonggo_id','$bigcommerce_id')");

    //if product already exists in imonggo, update product on database
    }else{
      $update_customer = mysql_query("UPDATE customer  SET bigcommerce_id= '$bigcommerce_id' WHERE imonggo_id='$imonggo_id'");   
    }
  }


//=======================================UPDATE CUSTOMERS====================================
  //This function updates Bigcommerce customers given that the customers were posted on Imonggo  
  function parse_update_customers($xml_file,$username,$pw){

    //Checks if there are any customers on Imonggo
    if($xml_file != NULL){

      foreach($xml_file->customer as $customer){

        //Checks if customer is posted on Imonggo through database
        $query = "SELECT bigcommerce_id FROM customer where imonggo_id='$customer->id'";
        $result1 = mysql_query($query);
        $row = mysql_fetch_array($result1);

        //If customer exists in Bigcommerce, check its status
        if($row[0] == NULL){

        }else{
    
          //Checks if customer is not deleted on Imonggo
          if($customer->status != "D"){

          //Parses customer name and posts customer updates on Bigcommerce
          $customer_name = explode(" ", $customer->name);

          $xml_content =
           '<?xml version="1.0" encoding="UTF-8"?>
              <customer>
                <first_name>'.(string)$customer_name[0].'</first_name>
                <last_name>'.(string)$customer_name[1].'</last_name>
                <email>'.(string)$customer->email.'</email>
              </customer>';

            $url_put=$GLOBALS['bigcommerce_URL'].'/api/v2/customers/'.$row[0];
            $put_result = put_file($url_put, $xml_content,$username, $pw);
          }
        }
      }
    }
  }

//=======================================PARSE INVOICES======================================
  //This function pull invoices from Imonggo and posts them on Bigcommerce
	function parse_invoices($url, $xml_file, $username, $pw,$inventories){

    if($xml_file != NULL){

      $results = array();


  		//Checks if each of the products is available online
  		foreach($xml_file->order as $order){

      //Bigcommerce's status for completed order is '10'
       if($order->status_id==10){

        echo "order_id:".$order->id;

         $xml_part1=  '<?xml version="1.0" encoding="UTF-8"?>
                      <invoice>
                        <invoice_date>'.$order->date_shipped.'</invoice_date>
                        <reference>'.$order->id.'</reference>
                        <invoice_lines type="array">';

        //Gets products included on invoice
        //Uses product list link
        $xml_part2=   get_order_products($order->products->link, $inventories);
             
        $xml_part3=   '</invoice_lines>
                        <payments type="array">
                        <payment>
                          <amount>'.$order->total_inc_tax.'</amount>
                        </payment>
                        </payments>
                      </invoice>';

        //Complete xml_file to be posted
        $xml = $xml_part1.$xml_part2.$xml_part3;
        
        //Checks if product is available in Imonggo
         if($xml_part2 !==0){
            $result = post_file($url, $xml, $username, $pw);
         }
        }
  		}//end of foreach
    } 
	}

  //This function checks if a product has been posted on the Bigcommerce store
  function get_order_products($link,$inventories){

   $username=$GLOBALS['bigcommerce_username'];
    $pw = $GLOBALS['bigcommerce_api_key'];

    $xml='';

    //Pulls products in an invoice from bigcommmerce
    $xml_file = get_file($GLOBALS['bigcommerce_URL'].'/api/v2'.$link,$username,$pw);

    foreach($xml_file->product as $product){
 
      $query = "SELECT imonggo_id FROM product_invoice where bigcommerce_id='$product->product_id'";
      $result = mysql_query($query);
      $row = mysql_fetch_array($result);

      //Checks if product is posted on the store and is available on-hand   
      if($row[0] != NULL && in_array($row[0], $inventories)){

        //Returns xml_file of products included in invoice
        $xml_file = get_file($GLOBALS['imonggo_URL'].'/api/products.xml',$GLOBALS['$imonggo'],$pw);

        $product_content=
        '<invoice_line>
          <product_id>'.$row[0].'</product_id>
          <quantity>'.$product->quantity.'</quantity>
          <retail_price>'.$product->price_inc_tax.'</retail_price>
        </invoice_line>';

        $xml = $xml.$product_content;
        return $xml;
        
      }else{
        return 0;
      }
     }
    }

?>

