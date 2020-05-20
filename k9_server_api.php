<?php

$xmlrpc_methods['do_select_query'] = 'do_select_query';
$xmlrpc_methods['do_insert_query'] = 'do_insert_query';
$xmlrpc_methods['method_not_found'] = 'XMLRPC_method_not_found';

function do_select_query($params) {
global $mysql_link;

	$sql = html_entity_decode($params[0]);

	$result = mysql_query($sql,$mysql_link)  ;

	while ($row = mysql_fetch_assoc($result)) {
		$rows[]=$row;
	}
	return $rows;
}

function do_insert_query($params) {
global $mysql_link;

	$sql = html_entity_decode($params[0]);

	$result = mysql_query($sql,$mysql_link)  ;

} 

function XMLRPC_method_not_found($methodName) {
	return "XMLRPC_method_not_found:$methodName";
}
?>