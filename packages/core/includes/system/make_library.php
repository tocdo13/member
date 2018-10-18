<?php
/******************************
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY vuonggialong
******************************/
$code = '<?php
define (\'DEBUG\',1);
Portal::$extra_header=\'\';
//config header
header("Content-Type: text/html; charset=utf-8");
ini_set (\'zend.ze1_compatibility_mode\',\'off\');
// include kernel files
require_once \'cache/modules.php\';
?>'.file_get_contents(ROOT_PATH.'packages/core/includes/system/system.php').'
'.file_get_contents(ROOT_PATH.'packages/core/includes/system/database.php').'
'.file_get_contents(ROOT_PATH.'packages/core/includes/system/session.php').'
'.file_get_contents(ROOT_PATH.'packages/core/includes/system/url.php').'
'.file_get_contents(ROOT_PATH.'packages/core/includes/system/id_structure.php').'
'.file_get_contents(ROOT_PATH.'packages/core/includes/portal/types.php').'
'.file_get_contents(ROOT_PATH.'packages/core/includes/portal/form.php').'
'.file_get_contents(ROOT_PATH.'packages/core/includes/system/user.php').'
'.file_get_contents(ROOT_PATH.'packages/core/includes/portal/module.php').'
'.file_get_contents(ROOT_PATH.'packages/core/includes/portal/portal.php').'<?php
//error report
error_reporting(E_ALL);
// Disable ALL magic_quote
set_magic_quotes_runtime(0);
if (get_magic_quotes_gpc())
{
	function stripslashes_deep($value)
	{
		$value = is_array($value) ? array_map(\'stripslashes_deep\', $value) : stripslashes($value);
		return $value;
	}
	$_REQUEST = array_map(\'stripslashes_deep\', $_REQUEST);
	$_COOKIE = array_map(\'stripslashes_deep\', $_COOKIE);
}
if(!file_exists(\'cache/default_settings.php\'))
{
	require_once \'packages/core/includes/system/make_default_settings.php\';
	make_default_settings();
}
require_once \'cache/default_settings.php\';
?>';
$fp = fopen(ROOT_PATH.'cache/library.php','w+');
fwrite($fp,$code);
fclose($fp);
?>