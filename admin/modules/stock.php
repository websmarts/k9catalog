<?php

  // stock module

  

  if($_POST){

      

      //echo dumper($_SERVER);

      //echo dumper($_POST);

      

      if(is_array($_POST['qty']) && count($_POST['qty']) > 0){

          foreach ($_POST['qty'] as $pid => $qty_to_add){

              $qty_to_add = (int) $qty_to_add;

              if($qty_to_add > 0){

                  $sql = 'update products set qty_instock = ( qty_instock + '.$qty_to_add.' ) where id='.$pid;

                  do_query($sql);

              }

          }

      }

      header('Location: '.$_SERVER['REQUEST_URI']);

      exit;

  }

  // exclude BOM items

  $sortby_options = array('product_code','description' );

  $sortorder_options = array('asc','desc');

  $sortby = in_array($_REQUEST['sortby'], $sortby_options) ? $_REQUEST['sortby'] : 'product_code';
  $sortorder = in_array($_REQUEST['sortorder'], $sortorder_options) ? $_REQUEST['sortorder'] : 'asc';

  

  

  $sql = "SELECT id,product_code, description,qty_instock from products where `status`='active' and qty_instock < low_stock_level and product_code NOT like '%-set' ";

  // $sql .= " order by product_code asc ";
  $sql .= "order by ".$sortby .' '.$sortorder. ' ';

  //echo $sql; die;

  

  $items = do_query($sql);

  

  // get list of customer order that are pending withthese items

  $orders = array();

  

      $sql = "  select soi.product_code, soi.qty,c.name from system_order_items as soi

                join system_orders as so on so.order_id = soi.order_id 

                join clients as c on c.client_id=so.client_id 

                WHERE so.status in('saved','printed')";

      $rs = do_query($sql);

      //echo dumper($rs);

      if(is_array($rs) && count($rs)){

          foreach($rs as $r){

              $orders[$r['product_code']][]=$r;

          }

          

      }

      

      

  

  

?>

