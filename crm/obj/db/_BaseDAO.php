<?php
require_once 'DB.php';

// add the following line because when using the zend optimiser DATA_OBJECT may segfault
define('DB_DATAOBJECT_NO_OVERLOAD',true);

require_once 'DB/DataObject.php';
require_once( OBJ_PATH . '/_dao/_configOptions/configOptions.php');

class BaseDAO extends  DB_DataObject
{
	var $_ID;
	var $_dao;
	var $_permitted = 'false';
	
	/** CONSTRUCTOR **/
	function BaseDAO()
	{	
	}	
	/** GET DAO **/
	function &getDAO() {
		return $this->_dao;
	}
	
	/** GET DAO FETCHED **/
	function &getDAOFetched() {
		$dao = $this->_dao;
		$dao->fetch();
		return $dao;
	}
	
	/** GET ONE RECORD **/
	function &getOneRecord($id)
	{
		// get uses the primary key as a default	
		$this->_dao->get($id);
		return $this->_dao;
	}
	
	/** GET ONE RECORD FETCHED **/
	function &getOneRecordFetched($id)
	{
		// get uses the primary key as a default	
		$this->_dao->get($id);
		$this->_dao->fetch();
		return $this->_dao;
	}
	
	/** GET ONE RECORD ARRAY **/
	function &getOneRecordArray($id)
	{
		// get uses the primary key as a default	
		$this->_dao->get($id);
		$res =& $this->_dao->toArray();
		return  $res;
	}
	
	/** GET PERMITTED **/
	function  &getPermitted()
	{
		return $this->_permitted;
	}

	/** INSERT RECORD **/
	function  &insertRecord(&$values) {	
		// created_TS / updated_TS --- add current time stamp to array
		$this->_dao->setFrom($values);
		// return the id of the last row inserted
		// uses mysql_next_id()
		return $this->_dao->insert();
	}
	
	/** UPDATE RECORD **/
	function &updateRecord($_ID, &$values) {
		// get uses the primary key as a default	
		$this->_dao->get($_ID);
		$this->_dao->setFrom($values);
		$this->_dao->update();	
	}
	
	/** DELETE RECORD **/
	function &deleteRecord($_ID) {
		// get uses the primary key as a default
		$this->_dao->get($_ID);
		$this->_dao->delete();
	}
	
	/** GET RECORDS FROM QUERY **/	
	function &getRecordsFromQuery($query) {
		$this->_dao->query($query);
		return $this->_dao;
	}

	/** EXECUTE QUERY **/	
	function &executeQuery($query) {
		$this->_dao->query($query);
	}
	
}
?>