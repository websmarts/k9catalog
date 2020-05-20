<?php
/**
 * Table Definition for user_clients
 */
require_once 'DB/DataObject.php';

class DataObject_User_clients extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'user_clients';                    // table name
    public $id;                              // int(11)  not_null
    public $client_id;                       // int(11)  not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObject_User_clients',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
