<?php
// WRITTEN BY khoand
// Replace default session by database session
// Date: 24/01/2011
class Session{
	static $name;
	static $vars;
	static $init_vars;
	function getIP() {
		/*$ip; 
		if (getenv("HTTP_CLIENT_IP")) 
		$ip = getenv("HTTP_CLIENT_IP"); 
		else if(getenv("HTTP_X_FORWARDED_FOR")) 
		$ip = getenv("HTTP_X_FORWARDED_FOR"); 
		else if(getenv("REMOTE_ADDR")) 
		$ip = getenv("REMOTE_ADDR"); 
		else 
		$ip = "UNKNOWN";
		return $ip;*/
		//echo session_id();
		return session_id();
	}
	static function start(){
		//Session::$vars = array();
		Session::$init_vars = var_export(Session::$vars,true);
		if(!Session::$name){
			Session::$name = md5($_SERVER['REMOTE_ADDR'].'&'.Session::getIP());
		}
		if($vars = DB::fetch('SELECT VARS FROM SESSION_USER WHERE SESSION_ID=\''.addslashes(Session::$name).'\'','vars')){
			eval('Session::$vars = '.$vars.';');
		}else{
			if(Session::is_set('user_id')){
				$id = Session::get('user_id');
				Session::delete('user_id');
			}
		}
		
	}
	static function name($name = false){
		if($name){
			Session::$name = $name;
		}
		return Session::$name;
	}
	static function get($name, $field=false){
		if(isset(Session::$vars[$name])){
			if($field){
				if(isset(Session::$vars[$name][$field])){
					return Session::$vars[$name][$field];
				}
				return false;
			}
			return Session::$vars[$name];
		}
	}
	static function set($name,$value){
		Session::$vars[$name] = $value;
		Session::end();
	}
	static function is_set($name, $field=false){
		if($field){
			return isset(Session::$vars[$name]) and isset(Session::$vars[$name][$field]);
		}
		return isset(Session::$vars[$name]);
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
		Session::destroy();//DB::delete('session_user','SESSION_ID = \''.Session::$name.'\''); //khoand sua
	}
	static function end(){
		/*DB::query('
			DELETE FROM
				session_user
			WHERE
				(last_active_time IS NULL OR last_active_time < '.(time()-(0.5*3600)).')
		');*/
		$vars = var_export(Session::$vars,1);
		if(Session::$init_vars != $vars){
			if($session=DB::select('SESSION_USER','SESSION_ID=\''.addslashes(Session::$name).'\'')){
				DB::update('SESSION_USER',
					array(
						'VARS' => $vars,
						'LAST_ACTIVE_TIME'=>time()
					)
					,'SESSION_ID=\''.addslashes(Session::$name).'\''
				);
			}
		}
	}
	static function destroy(){
		DB::delete('SESSION_USER','SESSION_ID=\''.addslashes(Session::$name).'\'');
		DB::delete('SESSION_USER','USER_ID=\''.Session::get('user_id').'\'');
	}
}
Session::start();
?>