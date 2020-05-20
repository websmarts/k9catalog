<?php
/**
 * Table Definition for type
 */
require_once 'DB/DataObject.php';

class DataObject_Type extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'type';                            // table name
    public $typeid;                          // int(11)  not_null primary_key auto_increment
    public $name;                            // string(255)  not_null
    public $image;                           // string(255)  not_null
    public $display_format;                  // string(1)  not_null
    public $aus_made;                        // int(4)  not_null
    public $type_description;                // string(255)  not_null
    public $modified;                        // datetime(19)  binary

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObject_Type',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
