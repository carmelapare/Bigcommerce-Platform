<?php

include 'general_functions.php';
include 'API_keys.php';


//=======================================PARSE PRODUCTS======================================

  //This function parses product tags and the xml file to be posted on Imonggo or Bigcommerce.
	function parse_products($url, $xml_file,$username, $pw,$tags,$inventories){

		foreach($xml_file->product as $product){

      echo '<br>';
      echo $product->name;
      echo '<br>';

      //if there are no selected tags, post all products
      if(count($tags)!=0){

        //parses product's tags
        $product_tags=explode(",",preg_replace('/\s+/','', strtolower($product->tag_list)));
        $intersect_count=0;

        //checks if at least one filter tag intersects with each product's tags
        if(count(array_intersect($product_tags,$tags))!=0){
          $intersect_count=1;
        }

        //checks if product tags intersect with selected tags and if there is product available on-hand(based on inventory)
        if(($intersect_count==1) && in_array($product->id, $inventories)){

          //1. checks if product does not exist in Bigcommerce

          //get product id from database
          $query = "SELECT bigcommerce_id FROM product_invoice where imonggo_id='$product->id'";
          $result = mysql_query($query);
          $row = mysql_fetch_array($result);

          if(get_file($GLOBALS['bigcommerce_URL'].'/api/v2/products/'.$row[0], $username, $pw)==NULL){
            
            //1.1 checks if product on imonggo is not deleted
            //if deleted, nothing will be posted on Bigcommerce
            if($product->status!='D'){

              echo 'NOT D, NOT EX';
              
              //posts product on bigcommerce
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

                  echo $product->id;

              //adds product to database for order mapping on invoice
              $post_result = post_file($url, $xml_product_content,$username, $pw);
              post_to_db(simplexml_load_string($post_result),$product->id);
            }
          }

          //2. product already exists in Bigcommerce
          else{

            //2.1 checks if product on imonggo is not deleted
            if($product->status!='D'){

               echo 'NOT D, EX';

              //updates product on bigcommerce
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

              //get product id from database
              $query = "SELECT bigcommerce_id FROM product_invoice where imonggo_id='$product->id'";
              $result = mysql_query($query);
              $row = mysql_fetch_array($result);

              $url_put=$GLOBALS['bigcommerce_URL'].'/api/v2/products/'.$row[0];

              echo $url_put;

              $put_result = put_file($url_put, $xml_product_content,$username, $pw);
            }

            //if deleted, hide product from Bigcommerce Store
            else{

              echo 'D, EX';

              //updates product on bigcommerce
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

              //get product id from database
              $query = "SELECT bigcommerce_id FROM product_invoice where imonggo_id='$product->id'";
              $result = mysql_query($query);
              $row = mysql_fetch_array($result);

              $url_put=$GLOBALS['bigcommerce_URL'].'/api/v2/products/'.$row[0];

              echo $url_put;

              $put_result = put_file($url_put, $xml_product_content,$username, $pw);
            }
          }
        }
      }

      //if no tags are indicated for all products, post all products
      else{
        echo "HAHAHAHA";
        //checks for duplication, product inventory level and posts product
        if(get_file($GLOBALS['bigcommerce_URL'].'/api/v2/products?name='.$product->name, $username, $pw)==NULL  && in_array($product->id, $inventories)){

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

          $post_result = post_file($url, $xml_product_content,$username, $pw);
          post_to_db(simplexml_load_string($post_result),$product->id);
        }
      }
    }
  }//end of parse products


//for mapping of imonggo and bigcommerce products
function post_to_db($result,$id){

  echo 'result:'.$result->id;
  echo 'enter1';
  //checks if product is already in imonggo
  $query = "SELECT imonggo_id FROM product_invoice where imonggo_id='$id'";
  $result1 = mysql_query($query);
  $row = mysql_fetch_array($result1);

  //if product does not exist in imonggo, insert into database
  if($row[0] == NULL){

    echo 'enter2';
    $insert_to_product_invoice = mysql_query("INSERT INTO product_invoice (imonggo_id,bigcommerce_id) VALUES('$id','$result->id')");

  //if product already exists in imonggo, update product on database
  }else{
    echo 'enter3' . $row[0] . '';
    $update_product_invoice = mysql_query("UPDATE product_invoice  SET bigcommerce_id= '$result->id' WHERE imonggo_id='$id'");   
  }
}


//=======================================PARSE CUSTOMERS======================================

	function parse_customers($url, $xml_file, $username, $pw){

		//check if each of the products is available online and create json file for posting
		foreach($xml_file->customer as $customer){

            $xml =  
              '<?xml version="1.0" encoding="UTF-8"?>
              <customer>
                <name>'.(string)$customer->first_name.'</name>
                <alternate_code>'.(string)$customer->id.'</alternate_code>
                <first_name>'.(string)$customer->first_name.'</first_name>
                <last_name>'.(string)$customer->last_name.'</last_name>
                <email>'.(string)$customer->email.'</email>
              </customer>
              ';

            $x = post_file($url, $xml, $username, $pw);
		}
	}

//=======================================PARSE INVOICES======================================

	function parse_invoices($url, $xml_file, $username, $pw){

    $results = array();


		//check if each of the products is available online
		foreach($xml_file->order as $order){


      //10 is bigcommerce's status_id for completed order
     if($order->status_id==10){


       $xml_part1=  '<?xml version="1.0" encoding="UTF-8"?>
                    <invoice>
                      <invoice_date>'.$order->date_shipped.'</invoice_date>
                      <reference>'.$order->id.'</reference>
                      <invoice_lines type="array">';

      $xml_part2=   get_order_products($order->products->link);

           
      $xml_part3=   '</invoice_lines>
                      <payments type="array">
                      <payment>
                        <amount>'.$order->total_inc_tax.'</amount>
                      </payment>
                      </payments>
                    </invoice>';

      $xml = $xml_part1.$xml_part2.$xml_part3;
      

      //product is not available in imonggo
       if($xml_part2 !==0){
          $result = post_file($url, $xml, $username, $pw);
          array_push($results, $result);
       }
      }
		}//end of foreach

    return $results;
	}


  function get_order_products($link){

    //pull products in an invoice from bigcommmerce
    $username=$GLOBALS['bigcommerce_username'];
    $pw = $GLOBALS['bigcommerce_api_key'];

    $xml='';
    $xml_file = get_file($GLOBALS['bigcommerce_URL'].'/api/v2'.$link,$username,$pw);

    foreach($xml_file->product as $product){

      //check if product exists from imonggo
      $query = "SELECT imonggo_id FROM product_invoice where bigcommerce_id='$product->id'";
      $result = mysql_query($query);
      $row = mysql_fetch_array($result);


      //if product does not exist in imonggo
      if(!row){
        return 0;
      }else{
        $product_content= '<invoice_line>
                              <product_id>'.$product->product_id.'</product_id>
                              <quantity>'.$product->quantity.'</quantity>
                              <retail_price>'.$product->price_inc_tax.'</retail_price>
                            </invoice_line>';

        $xml = $xml.$product_content;

        return $xml;
      }
     }//end of foreach
    }

?>

