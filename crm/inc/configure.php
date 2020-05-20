<?php



#-- SCRIPT SECURITY CONST

define('IN_SCRIPT', true);






// If it looks like were using localhost then use the development settings
if (preg_match("/localhost/i",$_SERVER['HTTP_HOST'])) {
#--WEBSITE RELATED CONST

define('ROOT_PATH', '/wamp/www/dev_k9catalog/crm');

define('HOST', 'http://localhost/dev_k9catalog/crm');

define('HOST1', 'http://localhost');

define('INI_PATH', ''.ROOT_PATH.'/pear');

define('SITE_HOME_PATH','dev_k9catalog/crm/');

} else {  // production environment

define('ROOT_PATH', '/home2/ourloca/public_html/olr');

define('HOST', 'http://www.ourlocalrag.com.au/olr');

define('HOST1', 'http://www.ourlocalrag.com.au');

define('INI_PATH', '.:'.ROOT_PATH.'/pear');

define('SITE_HOME_PATH','olr/');
}

#-- SET INI PATH

ini_set('include_path', INI_PATH);



#--PATH RELATED CONST

define('OBJ_PATH',ROOT_PATH.'/obj');

define('TEMPLATE_PATH_CLIENT',ROOT_PATH.'/templates/client');

define('TEMPLATE_PATH_CLIENT_MAIN',ROOT_PATH.'/templates/client/main');

define('TEMPLATE_PATH_CLIENT_HEADER',ROOT_PATH.'/templates/client/header');

define('TEMPLATE_PATH_CLIENT_FOOTER',ROOT_PATH.'/templates/client/footer');

define('TEMPLATE_PATH_ADMIN',ROOT_PATH.'/templates/admin');

define('TEMPLATE_PATH_ADMIN_MAIN',ROOT_PATH.'/templates/admin/main');

define('TEMPLATE_PATH_ADMIN_HEADER',ROOT_PATH.'/templates/admin/header');

define('TEMPLATE_PATH_ADMIN_FOOTER',ROOT_PATH.'/templates/admin/footer');



#--SESSION RELATED CONST

define('SESSION_KEY_ADMIN_FORMS', 'admin_forms');

define('SESSION_TIMEOUT_ADMIN', 36000);  // (Admin timeout) seconds (60 = minute, 3600 = hour)  3600

define('SESSION_KEY_CLIENT_FORMS', 'client_forms');

define('SESSION_TIMEOUT_CLIENT', 36000);  // (Admin timeout) seconds (60 = minute, 3600 = hour)  3600


define ('ENABLE_CLASSIFIEDS', true);

?>
