<?php
/******************************
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY vuonggialong
******************************/
function make_module_cache()
{
	$st = '';
	$items = DB::fetch_all('SELECT ID, NAME FROM MODULE');
	foreach($items as $item)
	{
		$st .= 'define(\'MODULE_'.strtoupper($item['name']).'\','.$item['id'].');
';
	}
	$fp = fopen(ROOT_PATH.'cache/modules.php','w+');
	fwrite($fp, '<?php '.$st.'?>');
	fclose($fp);
}
?>