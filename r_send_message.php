<?php
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
	require_once 'packages/core/includes/system/url.php';
	if(URL::get('user_id') and URL::get('message'))
	{
		require_once 'packages/core/includes/system/database.php';
		require_once 'packages/core/includes/system/database_session.php';
		set_magic_quotes_runtime(0);
		if (get_magic_quotes_gpc())
		{
			function stripslashes_deep($value)
			{
				$value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
				return $value;
			}
			$_REQUEST = array_map('stripslashes_deep', $_REQUEST);
			$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
		}
		if($last_message = DB::fetch('select id,time from chat where user_id=\''.Url::get('user_id').'\' order by time desc','time') and (time()-$last_message)>10)
		{
			DB::insert('CHAT',array('user_id'=>Url::get('user_id'),'MESSAGE'=>str_replace(';percent;','%',URL::get('message')),'TIME'=>time()));			
			echo '<strong><font color="blue">'.URL::get('user_id').'</font></strong>: '.str_replace(';percent;','%',URL::get('message')).'<br>';
		}
		else
		{
			echo '<script type="text/javascript">alert(\'Fast message!\')</script>';
		}
		DB::close();
	}
?>