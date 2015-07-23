<?php

include 'general_functions.php';
include 'API_keys.php';

//=======================================PARSE XML FILE======================================

//=======================================PARSE PRODUCTS======================================


	function parse_products($url, $xml_file,$username, $pw,$tags,$inventories){

		foreach($xml_file->product as $product){

      //if there are no selected tags, post all products
      if(count($tags)!=0){

        echo $tags;

        //===============================FOR PRODUCTS TAGS===============================
        $product_tags = explode(",",preg_replace('/\s+/','', strtolower($product->tag_list)));
        $intersect_count =0;

        //check if at least one filter tag exists to each product's tags
        if(count(array_intersect($product_tags,$tags)) != 0){
          $intersect_count = 1;
        }

        //check for duplication, product status, tags and if there is product available on-hand(based on inventory)
        if(get_file($GLOBALS['bigcommerce_URL'].'/api/v2/products?name='.$product->name, $username, $pw)==NULL && ($intersect_count==1) && in_array($product->id, $inventories)){

              
                  $xml_product_content = 
                    '<?xml version="1.0" encoding="UTF-8"?>
                    <product>
                        <name>'.(string)$product->name.'</name>
                        <type>physical</type>
                        <description>'.(string)$product->description.'</description>
                        <price>'.(string)$product->retail_price.'</price>
                        <is_visible>true</is_visible>
                        <is_featured>true</is_featured>
                        <categories>
                          <value>2</value>
                        </categories>
                        <availability>available</availability>
                        <weight>0.0</weight>
                    </product>';
          $post_result = post_file($url, $xml_product_content,$username, $pw);
          post_to_db(simplexml_load_string($post_result),$product->id,$product->name);
        }

      }else{
        //post all products
        if(get_file($GLOBALS['bigcommerce_URL'].'/api/v2/products?name='.$product->name, $username, $pw)==NULL  && in_array($product->id, $inventories)){

              
                  $xml_product_content = 
                    '<?xml version="1.0" encoding="UTF-8"?>
                    <product>
                        <name>'.(string)$product->name.'</name>
                        <type>physical</type>
                        <description>'.(string)$product->description.'</description>
                        <price>'.(string)$product->retail_price.'</price>
                        <categories>
                          <value>1</value>
                        </categories>
                        <availability>available</availability>
                        <weight>0.0</weight>
                        <is_visible>true</is_visible>
                        <is_featured>true</is_featured>
                    </product>';

          $post_result = post_file($url, $xml_product_content,$username, $pw);
          post_to_db(simplexml_load_string($post_result),$product->id,$product->name);
        }
      }

     

    }//end of foreach 
  }

//for mapping of imonggo and bigcommerce products
function post_to_db($result,$id,$name){

      $insert_to_product_invoice = mysql_query("INSERT INTO product_invoice (imonggo_id,product_name,bigcommerce_id) VALUES('$id', '$name', '$result->id') ");  
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

