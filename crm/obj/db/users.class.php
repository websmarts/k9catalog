<?php
require_once( OBJ_PATH . '/db/_BaseDAO.php');

class user extends BaseDAO
{
	/** CONSTRUCTOR - Initialize the Data Object for this table **/
	function user() {
		$this->_dao = DB_DataObject::factory('Users');
	}
	
	/** GET Business LIST  **/
	function &getuserList() {
		$this->_dao->orderBy('firstName');
		$this->_dao->find();
		return $this->_dao;
	}
}
?>