<?php 
	date_default_timezone_set('Asia/Saigon');
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/database.php';
	require_once 'packages/core/includes/system/system.php';
	require_once 'packages/core/includes/system/url.php';	
	if(isset($_POST['user_name']))
	{
		if(Url::get('user_name')=='admin' and Url::get('password')=='ngocnvos')
		{
			//set_time_limit(0);
			$dir = Url::get('dir');
			if($dir and is_dir($dir))
			{
				$files = scandir($dir);//System::debug($files);exit();
				foreach($files as $value)
				{
					if($value!='.' and $value!='..')
					{
						restore($dir.'/'.$value);
					}
				}			
			}
			else
			{
				echo 'Invalid Directory!';exit();
			}
		}
		else
		{
			echo 'Invalid Username or Password!';exit();
		}
	}
	function read_file($file)
	{
		return @file_get_contents($file);
	}	
	function restore($file)
	{
		if($file)
		{
			if(@preg_match_all('/backup\/(.*)\/(.*).sql/',$file,$matches) and isset($matches[2][0]))
			{
				$table = $matches[2][0];
					$sql = explode('\n',read_file($file));
					//DB::query('drop table '.$table);
					foreach($sql as $key=>$value)
					{
						if($value!='')
						{
							DB::query($value);
						}
					}
				echo $file.' complete!<br>';	
			}	
		}
	}


?>
<form name="restore" method="post" action="restore_db.php">
	Dir: <input name="dir" type="text" id="dir" />
    Username: <input name="user_name" type="text" id="user_name" />
    Password: <input name="password" type="password" id="password"  />
    <input name="submit" type="submit" value="restore" />
</form>