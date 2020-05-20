<?php
/**
 * Table Definition for category
 */
require_once 'DB/DataObject.php';

class DataObject_Category extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'category';                        // table name
    public $id;                              // int(11)  not_null primary_key auto_increment
    public $name;                            // string(255)  not_null
    public $description;                     // string(255)  not_null
    public $parent_id;                       // int(11)  not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObject_Category',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
