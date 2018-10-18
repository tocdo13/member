<?php
function save_file($file,$content,$portal_id)
{
	$handler = fopen('cache/portal/'.str_replace('#','',$portal_id).'/config/'.$file.'.php','w+');
	fwrite($handler,$content);
	fclose($handler);
}
function empty_all_dir($name, $remove = false)
{
	if(is_dir($name))
	{
		if($dir = opendir($name))
		{
			$files = array();
			while($file=readdir($dir))
			{
				if($file!='..' and $file!='.')
				{
					$files[]=$file;
				}
			}
			closedir($dir);
			foreach($files as $file)
			{
				if(is_dir($name.'/'.$file))
				{
					empty_all_dir($name.'/'.$file, $remove);
				}
				else
				{
					@unlink($name.'/'.$file);
				}
			}
			if($remove)
			{
				@rmdir($name);
			}
		}		
	}
}
?>
