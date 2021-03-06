<?

// 1. Initialize DAO Configuration Properties
/**
$config = parse_ini_file( DAO_PATH . '/__ini/db_dataobject.ini',TRUE);
foreach($config as $class=>$values) {
	$options = &PEAR::getStaticProperty($class,'options');
	$options = $values;
}
**/

    // this  the code used to load and store DataObjects Configuration. 
    $options = &PEAR::getStaticProperty('DB_DataObject','options');
    
    // the simple examples use parse_ini_file, which is fast and efficient.
    // however you could as easily use wddx, xml or your own configuration array.
    $config = parse_ini_file( OBJ_PATH . '/_dao/__ini/db_dataobject.ini' ,TRUE);
    
    // because PEAR::getstaticProperty was called with and & (get by reference)
    // this will actually set the variable inside that method (a quasi static variable)
    $options = $config['DB_DataObject'];
?>