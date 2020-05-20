<?php
  
  if($_POST){
      
      // save any special prioces
      //echo dumper($req);
      // do any deletes first
      if( isSet($req['delete']) && is_array($req['delete']) && count($req['delete']) ){
          foreach($req['delete'] as $product_code => $v){
              if($v =='on'){
                  do_query('delete from client_prices where client_id='.$S->client['client_id'] .' AND product_code="'.$product_code.'"');
                  
              }
          }
      }
      
      if(isSet($req['new_product_code']) && !empty($req['new_product_code']) ){
          $price = isSet($req['price']) ? 100 * (float)$req['price'] : ''; 
          if(!empty($price)){
              // delete any existing special price for this product before we insert a new entry
              $sql = '  DELETE from client_prices where client_id='.$S->client['client_id'].' and product_code="'.$req['new_product_code'].'"';
              do_query($sql);
              
              
              
              $sql = '  insert into client_prices 
                        (client_id,product_code,client_price,modified) 
                        VALUES 
                        ('.$S->client['client_id'].',
                        "'.$req['new_product_code'].'",
                        '.$price.',
                        "'.date('Y-m-d').'")';
              
              //echo dumper($sql);
              do_query($sql);
              
          }
      }
      //echo dumper($req);
      // check for BULK import
      if(isSet($req['bulk']) && !empty($req['bulk'])){
          $lines = explode("\n",$req['bulk']);
          //echo dumper($lines);
          if(is_array($lines) && count($lines)){
              foreach($lines as $line){
                  $line = trim($line); //remove EOL
                  $item = preg_split('/\s+/',$line);
                  
                  $product_code=trim($item[0]);
                  $price = trim($item[1]); // should be cents
                  
                  if(strstr($price,'.')){
                      $floatprice=(float) $price;
                      $floatcents = 100 * $floatprice;
                      $pricecents = (int)$floatcents;
                      
                  } else {
                      $pricecents =(int) $price;
                  }
                  
                  
                  
                  
                  
                  
                 
                  
                  //echo dumper($item);
                  //echo dumper(count($item));
                  if(count($item) ==2 && !empty($product_code) && $pricecents > 0){
                      // delete any existing special price for this product before we insert a new entry
                      $sql = '  DELETE from client_prices where client_id='.$S->client['client_id'].' and product_code="'.$product_code.'"';
                      do_query($sql);
                      
                      $sql = '  INSERT into client_prices 
                                (client_id,product_code,client_price,modified)
                                VALUES 
                                ('.$S->client['client_id'].',
                                "'.$product_code.'",
                                '.$pricecents.',
                                "'.date('Y-m-d').'")';
                                do_query($sql);
                                
                               
                  }
                  
                  
              }
          }
      }
      
      
  }
  
  // Delete any special prices if the product is inactive (pending or active is kosha)
  $sql ='   DELETE `cp` FROM client_prices as cp
            JOIN products as p on p.product_code=cp.product_code
            WHERE p.`status`="inactive" and cp.client_id='.$S->client['client_id'];
            
  //echo dumper($sql);
  do_query($sql);
  
  // Get handler
  $clientprices=array();
  if($S->client['client_id'] > 0){
      $sql = '  SELECT  * from client_prices as cp 
                JOIN products as p on cp.product_code=p.product_code
                where cp.client_id='.$S->client['client_id'] .' ORDER BY cp.product_code ASC ';
      $clientprices = do_query($sql);
  }
  $sql = '  Select p.product_code,p.description,p.price from products as p
            where p.`status`="active" ORDER BY product_code ASC ';
  $products = do_query($sql);         
?>
