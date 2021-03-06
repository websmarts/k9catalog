<?php
/**
 * Table Definition for system_orders
 */
require_once 'DB/DataObject.php';

class DataObject_System_orders extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'system_orders';                   // table name
    public $id;                              // int(11)  not_null primary_key auto_increment
    public $order_id;                        // string(20)  not_null
    public $status;                          // string(20)  not_null
    public $client_id;                       // int(11)  not_null
    public $instructions;                    // string(255)  not_null
    public $modified;                        // datetime(19)  binary
    public $reference_id;                    // string(20)  not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObject_System_orders',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
