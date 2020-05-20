<?php
/**
 * Table Definition for type_options
 */
require_once 'DB/DataObject.php';

class DataObject_Type_options extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'type_options';                    // table name
    public $typeid;                          // int(11)  not_null primary_key
    public $opt_code;                        // string(20)  not_null primary_key
    public $opt_desc;                        // string(255)  not_null
    public $opt_class;                       // string(255)  not_null
    public $modified;                        // datetime(19)  binary

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObject_Type_options',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
