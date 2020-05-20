<?php
/**
 * Table Definition for sessions
 */
require_once 'DB/DataObject.php';

class DataObject_Sessions extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'sessions';                        // table name
    public $ID;                              // int(11)  not_null primary_key auto_increment
    public $SessionID;                       // string(64)  multiple_key
    public $session_data;                    // blob(65535)  blob
    public $expiry;                          // int(11)  multiple_key
    public $expireref;                       // string(64)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObject_Sessions',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
