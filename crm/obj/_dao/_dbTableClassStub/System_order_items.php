<?php
/**
 * Table Definition for system_order_items
 */
require_once 'DB/DataObject.php';

class DataObject_System_order_items extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'system_order_items';              // table name
    public $order_id;                        // string(20)  not_null primary_key
    public $product_code;                    // string(20)  not_null primary_key
    public $qty;                             // int(11)  not_null
    public $price;                           // int(11)  not_null
    public $qty_supplied;                    // int(11)  not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObject_System_order_items',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
