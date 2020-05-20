<?php
/**
 * Table Definition for order_items
 */
require_once 'DB/DataObject.php';

class DataObject_Order_items extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'order_items';                     // table name
    public $order_id;                        // int(11)  not_null primary_key
    public $product_code;                    // string(20)  not_null primary_key
    public $qty;                             // int(11)  not_null
    public $price;                           // int(11)  not_null
    public $modified;                        // timestamp(19)  not_null unsigned zerofill binary timestamp

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObject_Order_items',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
