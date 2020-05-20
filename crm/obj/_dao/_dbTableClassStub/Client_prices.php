<?php
/**
 * Table Definition for client_prices
 */
require_once 'DB/DataObject.php';

class DataObject_Client_prices extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'client_prices';                   // table name
    public $client_id;                       // int(11)  not_null primary_key
    public $product_code;                    // string(20)  not_null primary_key
    public $client_price;                    // int(11)  not_null
    public $modified;                        // datetime(19)  binary

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObject_Client_prices',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
