<?php
function dumper ($a) {
 	$html = '<pre>';
  $html .=print_r($a,true);
	$html .='</pre>';
	return $html;
}

/*
 * This function logs errors
 * in production these will be saved in log file or database
 */
 function log_error ($msg) {
 	
 	echo dumper($msg); 	
}

function log_fatal_error($msg) {
	log_error($msg);
	exit;	// We cant go on
}

/*
 * Encrypts passwords when saved to database
 */
function encrypt_password($password) {
	
	// should do MD5 here
	return $password;
}

/*
 * use to redirect the next view after a POSTed action
 */
function httpRedirect($query) {
	
	$url = HOST."/index.php?".$query;
	httpRedirectURL($url);

}
function httpRedirectURL($url) {
	
	header('HTTP/1.1 301 Moved Permanently');
	header("Location: " . $url);
	header('Connection: close');
	exit;	
}
/*
 *
 * Timing function - timingStart is done at the start of Request
 * then call this function any time to get the elapsed time
 *
 */
function get_current_time () {
	global $timingStart;
	$stop_time = explode(' ', microtime());
	$current = $stop_time[1] - $timingStart[1];
	$current += $stop_time[0] - $timingStart[0];
	return $current;
}
?>