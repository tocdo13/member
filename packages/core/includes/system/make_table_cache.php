<?php
/******************************
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY vuonggialong
******************************/

function make_table_cache($table)
{
	DB::query('SELECT * FROM '.$table.' '.DB::$db_cache_tables[$table]['order']);
	$cache_var = '$cache_'.$table;
	$code='global $cache_'.$table.';'."\n".'$cache_'.$table.'=array('."\n";
	$i = 0;
	while($row=DB::fetch())
	{
		if($i!=0)
		{
			$code .= ",\n";
		}
		$i++;
		$code.="\t'".$row[DB::$db_cache_tables[$table]['is_name']].'\'=>array(';
		$j=0;
		foreach($row as $key=>$value)
		{
			if($j!=0)
			{
				$code .= ",";
			}
			$j++;
			$code .= '\''.$key.'\'=>\''.strtr($value,array('\''=>'\\\'')).'\'';
		}
		$code .= ')';
	}
	$code .= "\n);";
	eval($code);
	$fp = fopen(ROOT_PATH.'cache/tables/'.$table.'.cache.php', 'w+');
	fwrite ($fp, '<?php' . "\n" . $code . "\n?" . '>');
	fclose($fp);
}
?>