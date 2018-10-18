<?php
function make_default_settings()
{
	$settings = DB::fetch_all('
		SELECT
			ID,
			DEFAULT_VALUE
		FROM
			SETTING
		WHERE
			STYLE <> 2
	');
	$text = '<?php $GLOBALS[\'default_settings\'] = array(';
	foreach($settings as $id=>$setting)
	{
		$text .= '
	\''.$id.'\'=>\''.addslashes($setting['default_value']).'\',';
	}
	$text .= ');?>';
	$f = fopen('cache/default_settings.php','w+');
	fwrite($f,$text);
	fclose($f);
}
?>