<?php
// Initial setup //
require_once 'catalog/adodb_lite/adodb.inc.php';
include_once('catalog/lib/db.inc');
include_once('catalog/lib/common.inc');

$sql = "update clients set login_pass='', login_user='', online_status='', online_validation_key='',online_contact='' where login_user = 'tim@k9homes.com.au' ";

$res = do_query($sql);

echo 'Done';
	
?>
