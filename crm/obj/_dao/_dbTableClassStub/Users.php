<?php
/**
 * Table Definition for users
 */
require_once 'DB/DataObject.php';

class DataObject_Users extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'users';                           // table name
    public $id;                              // int(11)  not_null
    public $name;                            // string(80)  not_null
    public $pass;                            // string(80)  not_null
    public $role;                            // string(10)  not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObject_Users',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
