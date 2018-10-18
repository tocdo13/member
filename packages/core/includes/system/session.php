<?php
class Session
{
	static $name;
	static $vars;
	static $init_vars;
	static function start()
	{
		Session::$vars = array();
		Session::$init_vars = var_export(Session::$vars,true);
		if(!Session::$name)
		{
			Session::$name = md5($_SERVER['REMOTE_ADDR']);
		}
		if($vars = DB::fetch('
			SELECT
				VARS
			FROM
				SESSION_USER
			WHERE
				SESSION_ID=\''.addslashes(Session::$name).'\'
		','VARS'))
		{
			Session::$init_vars = $vars;
			eval('Session::$vars = '.$vars.';');
		}
		
	}
	static function end()
	{
		$vars = var_export(Session::$vars,1);
		if(Session::$init_vars != $vars)
		{
			if($session=DB::select('SESSION','id=\''.addslashes(Session::$name).'\''))
			{
				DB::update('SESSION',
					array(
						'VARS' => $vars,
						'LAST_ACTIVE_TIME'=>time()
					)
					,'ID=\''.addslashes(Session::$name).'\''
				);
			}
			else
			{
				DB::insert('SESSION',
					array(
						'ID'=>Session::$name,
						'VARS'=>$vars,
						'TIME'=>time(),
						'LAST_ACTIVE_TIME'=>time()
					)
				);
			}
		}
	}
	static function destroy()
	{
		DB::delete('SESSION','id=\''.addslashes(Session::$name).'\'');
	}
	static function name($name = false)
	{
		if($name)
		{
			Session::$name = $name;
		}
		return Session::$name;
	}
	static function delete($name, $field=false)
	{
		if($field)
		{
			if(isset(Session::$vars[$name][$field]))
			{
				unset(Session::$vars[$name][$field]);
			}
		}
		else
		{
			if(isset(Session::$vars[$name]))
			{
				unset(Session::$vars[$name]);
			}
		}
	}
	static function get($name, $field=false)
	{
		if(isset(Session::$vars[$name]))
		{
			if($field)
			{
				if(isset(Session::$vars[$name][$field]))
				{
					return Session::$vars[$name][$field];
				}
				return false;
			}
			return Session::$vars[$name];
		}
	}
	static function set($name,$value)
	{
		Session::$vars[$name] = $value;
	}
	static function is_set($name, $field=false)
	{
		if($field)
		{
			return isset(Session::$vars[$name]) and isset(Session::$vars[$name][$field]);
		}
		return isset(Session::$vars[$name]);
	}
}
Session::start();
?>