<?php
/**
 * Table Definition for category_image_index
 */
require_once 'DB/DataObject.php';

class DataObject_Category_image_index extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'category_image_index';            // table name
    public $cat_id;                          // int(11)  not_null
    public $image_id;                        // int(9)  not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObject_Category_image_index',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
