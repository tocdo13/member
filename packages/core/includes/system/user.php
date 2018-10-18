<?php
/******************************
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY vuonggialong
EDITED BY Khoand
******************************/
define('CURRENT_CATEGORY',1);
define('ANY_CATEGORY',2);
class User
{
	var $groups = array();
	var $privilege = array();
	var $actions = array();
	var $settings = array();
	static $current=false;
	function User($id=false)
	{
		if(!$id)
		{		
			if(!Session::is_set('user_id'))
			{
				Session::set('user_id','guest');
			}
			if($this->data=DB::fetch('SELECT * FROM ACCOUNT WHERE ID=\''.Session::get('user_id').'\''))
			{
				if(!file_exists('cache/portal/'.str_replace('#','',PORTAL_ID).'/user/'.$this->data['id'].'.php'))
				{
					require_once 'packages/core/includes/system/make_user_privilege_cache.php';
					eval(make_user_privilege_cache(Session::get('user_id'),PORTAL_ID));
				}
				else
				{
					$file_path = 'cache/portal/'.str_replace('#','',PORTAL_ID).'/user/'.$this->data['id'].'.php';
					$privilege_user_file_content = file_get_contents($file_path);
					eval($privilege_user_file_content);
				}				
				if(!$this->data['cache_setting'])
				{
					//require_once 'packages/core/includes/system/make_account_setting_cache.php';
					//echo $code = make_account_setting_cache(Session::get('user_id'));
					//eval('$this->settings='.$code);
				}
				else
				{
					//eval('$this->settings='.$this->data['cache_setting']);
				}
			}
		}
	}
	function is_login()
	{
		if((Session::is_set('user_id') and DB::exists_id('ACCOUNT',Session::get('user_id')) and DB::exists('SELECT id FROM session_user WHERE user_id = \''.Session::get('user_id').'\'') and Session::get('user_id')!='guest')){
			return true;
		}else{
			return false;
		}
	}
	
	function is_online($id)
	{
		$row=DB::select('ACCOUNT', 'ID=\''.$id.'\' and LAST_ONLINE_TIME>'.(time()-600));
		if ($row)
		{
			return true;
		}
		return false;
	}
	function encode_password($password)
	{
		return md5($password.'thedeath');
	}
	function is_in_group($user_id,$group_id)
	{
		$row=DB::select('USER_GROUP',' USER_ID=\''.$user_id.'\' and GROUP_ID=\''.$group_id.'\'');
		if ($row or User::is_admin())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function groups()
	{	
		return $this->groups;
	}
	function home_page()
	{
		if(User::$current and User::$current->groups)
		{
			$group = reset(User::$current->groups);
			if(!isset($group['home_page']))
			{
				$group['home_page'] = URL::build('home');
			}
			return $group['home_page'];
		}
		return URL::build('home');
	}
	
	function is_admin_user()
	{
		return isset($this->groups[3]);
	}
	function is_admin()
	{
		if(isset(User::$current))
		{
			return User::$current->is_admin_user();
		}
	}
    function is_deploy_user() {
        return isset($this->groups[4]);
    }
    function is_deploy() {
        if(isset(User::$current))
		{
			return User::$current->is_deploy_user();
		}
    }
	function can_do_action($action,$pos,$module_id=false, $structure_id = 0, $portal_id = false)
	{
		if(!$portal_id)
		{
			$portal_id = PORTAL_ID;
		}
		if(User::is_admin())
		{
			return true;
		}
        if(User::is_deploy())
		{
			return true;
		}
		if(!$module_id)
		{
			if(isset(Module::$current->data))
			{
				$module_id = Module::$current->data['module']['id'];
				//$is_service = Module::$current->data['module']['type']=='SERVICE';
			}
			else
			{
				$module_id=false;
			}			
		}
		if(!$module_id)
		{
			return;
		}
		if($structure_id)
		{
			if($structure_id==CURRENT_CATEGORY)
			{
				$structure_id=0;
				if(URL::sget('category_id'))
				{
					$structure_id=DB::structure_id('CATEGORY',URL::sget('category_id'));
				}
				if(!$structure_id)
				{
					$structure_id = ID_ROOT;
				}
			}
			if(isset(User::$current->actions[$portal_id][$module_id][0]))
			{
				return User::$current->actions[$portal_id][$module_id][0]&(1 << (7-$pos));
			}			
			if($structure_id==ANY_CATEGORY)
			{
				if(isset(User::$current->actions[$portal_id]) and isset(User::$current->actions[$portal_id][$module_id]))
				{
					foreach(User::$current->actions[$portal_id][$module_id] as $category_privilege)
					{	
						if($category_privilege&(1 << (7-$pos)))
						{
							return true;
						}
					}
				}
				return false;
			}
			else
			{
				while(1)
				{
					if(isset(User::$current->actions[$portal_id][$module_id][$structure_id]))
					{
						return User::$current->actions[$portal_id][$module_id][$structure_id]&(1 << (7-$pos));
					}
					else
					if($structure_id <= ID_ROOT)
					{
						break;
					}
					else
					{
						$structure_id = IDStructure::parent($structure_id);
					}
				}
			}
			return false;
		}
		else
		{
			return isset(User::$current->actions[$portal_id][$module_id][0]) and (User::$current->actions[$portal_id][$module_id][0]&(1 << (7-$pos)));
		}
	}
	function can_view($module_id=false, $structure_id = 0, $portal_id = false)
	{
		return USER::can_do_action('view',0,$module_id, $structure_id);
	}
	function can_view_detail($module_id=false, $structure_id = 0, $portal_id = false)
	{
		return USER::can_do_action('view_detail',1,$module_id, $structure_id);
	}
	function can_add($module_id=false, $structure_id = 0, $portal_id = false)
	{
		return USER::can_do_action('add',2,$module_id, $structure_id);
	}
	function can_edit($module_id=false, $structure_id = 0, $portal_id = false)
	{
		return USER::can_do_action('edit',3,$module_id, $structure_id);
	}
	function can_delete($module_id=false, $structure_id = 0, $portal_id = false)
	{
		return USER::can_do_action('delete',4,$module_id, $structure_id);
	}	
	function can_moderator($module_id=false, $structure_id = 0, $portal_id = false)
	{
		return USER::can_do_action('moderator',5,$module_id, $structure_id);
	}
	function can_reserve($module_id=false, $structure_id = 0, $portal_id = false)
	{
		return USER::can_do_action('reserve',6,$module_id, $structure_id);
	}
	function can_admin($module_id=false, $structure_id = 0, $portal_id = false)
	{
		return USER::can_do_action('admin',7,$module_id, $structure_id);
	}	
	function can_special($module_id=false, $structure_id = 0, $portal_id = false)
	{
		return USER::can_do_action('special',8,$module_id, $structure_id);
	}
	function id()
	{
		if(Url::get('blog_id'))
		{
			$user_id=Url::get('blog_id');
		}
		else 
		if(Session::is_set('user_id'))
		{
			$user_id=Session::get('user_id');
		}
		else
		{
			return false;
		}
		return $user_id;
	}	
	function get_setting($name,$default='')
	{
		return Portal::get_setting($name,$default, User::id());
	}
	function set_setting($name, $value,$user_id=false)
	{
		if(!$user_id)
		{
			$user_id = Session::get('user_id');
		}
		Portal::set_setting($name, $value,$user_id);
	}
}
User::$current = new User();
if(!Session::is_set('user_id') and isset($_COOKIE['user_id'])and $_COOKIE['user_id'])
{
	setcookie('user_id',"",time()-3600);
}
?>