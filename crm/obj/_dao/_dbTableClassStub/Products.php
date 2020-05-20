<?php
/**
 * Table Definition for products
 */
require_once 'DB/DataObject.php';

class DataObject_Products extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'products';                        // table name
    public $id;                              // int(11)  not_null primary_key auto_increment
    public $description;                     // string(255)  not_null
    public $size;                            // string(80)  not_null
    public $price;                           // int(20)  not_null
    public $product_code;                    // string(20)  not_null unique_key
    public $typeid;                          // int(12)  not_null
    public $aus_made;                        // int(4)  not_null
    public $qty_break;                       // int(11)  not_null
    public $qty_discount;                    // int(11)  not_null
    public $qty_instock;                     // int(11)  not_null
    public $qty_ordered;                     // int(11)  not_null
    public $special;                         // int(6)  not_null
    public $clearance;                       // int(6)  not_null
    public $can_backorder;                   // string(1)  not_null
    public $status;                          // string(20)  not_null
    public $modified;                        // datetime(19)  binary
    public $cost;                            // int(11)  not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObject_Products',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
