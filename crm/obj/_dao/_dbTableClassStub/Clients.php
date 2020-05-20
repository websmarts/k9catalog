<?php
/**
 * Table Definition for clients
 */
require_once 'DB/DataObject.php';

class DataObject_Clients extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'clients';                         // table name
    public $client_id;                       // int(11)  not_null primary_key auto_increment
    public $name;                            // string(80)  not_null
    public $status;                          // string(20)  not_null
    public $modified;                        // datetime(19)  binary

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObject_Clients',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
