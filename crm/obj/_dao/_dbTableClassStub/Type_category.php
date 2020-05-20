<?php
/**
 * Table Definition for type_category
 */
require_once 'DB/DataObject.php';

class DataObject_Type_category extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'type_category';                   // table name
    public $catid;                           // int(11)  not_null primary_key
    public $typeid;                          // int(11)  not_null primary_key
    public $modified;                        // datetime(19)  binary

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObject_Type_category',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
