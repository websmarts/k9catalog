<?php
/**
 * Table Definition for orders
 */
require_once 'DB/DataObject.php';

class DataObject_Orders extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'orders';                          // table name
    public $order_id;                        // int(11)  not_null primary_key auto_increment
    public $status;                          // string(12)  not_null
    public $client_id;                       // int(11)  not_null primary_key
    public $instructions;                    // string(255)  not_null
    public $modified;                        // timestamp(19)  not_null unsigned zerofill binary timestamp

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObject_Orders',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
