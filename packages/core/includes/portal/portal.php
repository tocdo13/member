<?php
/******************************
WRITTEN BY vuonggialong
EDITED BY khoand
******************************/

class Portal{
	static $current = false;
	static $extra_header = '';
	static $extra_css = '';
	static $extra_js = '';
	static $footer_js = '';
	static $page_gen_time = 0;
	static $page = false;
	static $meta_keywords = '';
	static $meta_description = '';
	static $document_title = '';
	function Portal(){
	}
	function register_module($row_or_id, &$module){
		if(is_numeric($row_or_id)){
			$id=$row_or_id;
		}elseif(isset($row_or_id['id'])){
			$id = $row_or_id['id'];
		}else{
			System::halt();
		}
		if(is_numeric($row_or_id)){
			DB::query('
				SELECT
					ID, NAME, PACKAGE_ID
				FROM
					MODULE
				where
					ID = '.$row_or_id);
			$row = DB::fetch();
			if(!$row){
				System::halt();
			}
		}else{
			$row = $row_or_id;
		}
		require_once 'packages/core/includes/portal/package.php';
		$class_fn = get_package_path($row['package_id']).'module_'.$row['name'].'/class.php';

		require_once $class_fn;
		$module = new $row['name']($row);
		$module->package = &$GLOBALS['packages'][$row['package_id']];
	}
	function run(){
        check_expired_time(EXPIRE_DATE); 
		if(Session::is_set('portal') and Session::get('portal')){
			if($services = Portal::get_setting('services')){
				Portal::$current->services = array_flip(explode(',',$services));
			}else{
				Portal::$current->services = array();
			}if(isset($_REQUEST['page'])){
				$page_name = strtolower($_REQUEST['page']);
			}else{
				header('Location:?page=sign_in');
				exit();
			}
			$pages = DB::select_all('page','name=\''.addslashes($page_name).'\'','params DESC');
			if(sizeof($pages)==1){
				Portal::run_page(array_pop($pages),$page_name, false);
			}elseif(sizeof($pages)==0){
				header('Location:?page=sign_in');
				exit();
			}else{
				foreach($pages as $page){
					if($page['params']==''){
						Portal::run_page($page,$page_name, false);
						break;
					}else{
						$params = explode('&',$page['params']);
						$ok = true;
						foreach($params as $param){
							if($param = explode('=',$param)){
								if(sizeof($param)==1){
									if(!URL::check($param[0])){
										$ok = false;
										break;
									}
								}elseif($param[0]=='group'){
									if(!isset(User::$current->groups[$param[1]])){
										$ok = false;
										break;
									}
								}else{
									if($param[0]=='portal'){
										if('#'.$param[1] != Session::get('portal','id')){
											$ok = false;

											break;
										}
									}elseif(URL::get($param[0])!=$param[1]){
										$ok = false;
										break;
									}else{
										if($param[0]=='portal'){
											$portal = $param[1];
										}
									}
								}
							}
						}
						if($ok){
							Portal::run_page($page,$page_name, $page['params']);
							break;
						}
					}
				}
			}			
		}
		Session::end();
		DB::close();
	}
	function run_page($row, $page_name, $params=false){
		$postfix = $params?'.'.$params:'';
		$page_file = ROOT_PATH.'cache/page_layouts/'.$page_name.$postfix.'.cache.php';
		if(file_exists($page_file) and false){
			require_once $page_file;								
		}else{
			require_once 'packages/core/includes/portal/generate_page.php';
			$generate_page = new GeneratePage($row);
			$generate_page->generate();
			$page_name=$row['name'];
		}
		Portal::update_portal_hit_count($page_name,PORTAL_ID);		
	}
	static function update_portal_hit_count($page_name,$portal_id){
		if(Session::is_set('portal_visited')){
			$items=array_flip(explode(',',Session::get('portal_visited')));
		}else{
			$items=array();
		}			
		if(!isset($items[$portal_id]) and $item=DB::fetch('SELECT ID,HIT_COUNT  FROM PARTY WHERE TYPE=\'PORTAL\' AND PORTAL_ID=\''.$portal_id.'\'')){
			DB::update_id('PARTY',array('HIT_COUNT'=>$item['HIT_COUNT']+1),intval($item['id']));
			$items[$portal_id]=$portal_id;			
			Session::set('portal_visited', implode(',',array_keys($items)));
		}		
	}
	static function template($package_name){
		return 'packages/'.$package_name.'/skins/'.Portal::get_setting($package_name.'_template','default');
	}
	static function template_tcv($template_name=false){
		if($template_name){
			return 'packages/tcv/skins/'.$template_name.'/';
		}	
		return 'packages/tcv/skins/'.substr(PORTAL_ID,1).'/';
	}
	static function service($service_name){
		$services = Portal::get_setting('registered_services');
		return isset($services[$service_name]);
	}
	static function language($name=false)
	{
		if($name){
			if(isset($GLOBALS['all_words']['[[.'.$name.'.]]'])){
				return $GLOBALS['all_words']['[[.'.$name.'.]]'];
			}else{
				$languages = DB::select_all('language');
				$row = array();
				foreach($languages as $language)
				{
					$row['value_'.$language['id']] = ucfirst(str_replace('_',' ',$name));
				}
				if(!DB::exists('select id,package_id from word where id=\''.$name.'\' and package_id=\''.Module::$current->data['module']['package_id'].'\'')){
					DB::insert('word',$row + array(
						'id'=>$name,
						'package_id'=>Module::$current->data['module']['package_id']
					));
				}
				Portal::make_word_cache();
				return $name;
			}
		}
		/*if(Session::is_set('LANGUAGE_ID')){
			return Session::get('LANGUAGE_ID');
		}else{
			Session::set('LANGUAGE_ID', Portal::get_setting('LANGUAGE_ID',1));
			return Session::get('LANGUAGE_ID');
		}*/
		if($session_user = DB::fetch('SELECT id,language_id FROM session_user WHERE user_id = \''.Session::get('user_id').'\'')){
			return $session_user['language_id'];
		}else{
			return 1; //DEFAULT_LANGUAGE
		}
	}
	static function get_setting($name, $default=false, $user_id = false){
		if(!$user_id){
			if(isset(User::$current->settings[$name])){
				if(User::$current->settings[$name] == '@VERY_LARGE_INFORMATION'){
					if($setting = DB::select('account_setting','SETTING_ID=\''.DB::escape($name).'\' and ACCOUNT_ID=\''.User::id().'\'')){
						return $setting['value'];
					}
				}else{
					return User::$current->settings[$name];
				}
			}else
			if(isset(Portal::$current->settings[$name])){
				if(Portal::$current->settings[$name] == '@VERY_LARGE_INFORMATION'){
					if($setting = DB::select('account_setting','SETTING_ID=\''.DB::escape($name).'\' and ACCOUNT_ID=\''.PORTAL_ID.'\'')){
						return $setting['value'];
					}
				}else{
					return Portal::$current->settings[$name];
				}
			}
			
		}else{
			if($setting = DB::select('account_setting','SETTING_ID=\''.DB::escape($name).'\' and ACCOUNT_ID=\''.DB::escape($user_id).'\'')){
				return $setting['value'];
			}
			return $default;
		}
		if(isset($GLOBALS['default_settings'][$name])){
			if($GLOBALS['default_settings'][$name] == '@VERY_LARGE_INFORMATION'){
				if($setting = DB::select('account_setting','SETTING_ID=\''.DB::escape($name).'\' and ACCOUNT_ID=\''.PORTAL_ID.'\'')){
					return $setting['value'];
				}
			}else{
				return $GLOBALS['default_settings'][$name];
			}
		}
		return $default;
	}
	function use_service($name){
		return isset(Portal::$current->services[$name]);
	}
	function set_setting($name, $value,$user_id=false){
		if($setting = DB::select('setting','ID=\''.$name.'\'')){
			if($user_id==false){
				if($setting['account_type']=='USER'){
					$account_id = Session::get('user_id');
				}else{
					$account_id = Session::get('portal','id');
				}
			}else{
				$account_id = $user_id;
			}
			if(DB::select('account_setting','ACCOUNT_ID=\''.addslashes($account_id).'\' and SETTING_ID=\''.addslashes($name).'\'')){
				DB::update('account_setting',
					array(
						'value'=>$value
					),
					'ACCOUNT_ID=\''.addslashes($account_id).'\' and SETTING_ID=\''.addslashes($name).'\''
				);
			}else{
				DB::insert('account_setting',
					array(
						'ACCOUNT_ID'=>$account_id,
						'SETTING_ID'=>$name,
						'value'=>$value
					)
				);
			}
			DB::update('ACCOUNT',array('cache_setting'=>''),'ID=\''.$account_id.'\'');
			if($setting['account_type']=='PORTAL' and $account_id==PORTAL_ID){
				if(isset($_REQUEST['portal']) and $portal=DB::select_id('ACCOUNT',addslashes($_REQUEST['portal']))){
					Session::set('portal', $portal);
				}else{
					Session::set('portal', DB::select_id('ACCOUNT','#default'));
				}
			}
		}
	}
	function make_word_cache(){
		$languages = DB::select_all('language');
		foreach($languages as $language_id=>$row){
			$all_words = DB::fetch_all('
					SELECT 
						ID, value_'.$language_id.' as VALUE 
					FROM
						WORD 
				');
			$language_convert = array();
			foreach($all_words as $language)
			{
				$language_convert = $language_convert + 
					array('[[.'.$language['id'].'.]]'=>$language['value']);
			}
			if($language_id==Portal::language())
			{
				$GLOBALS['all_words'] = $language_convert;
			}
			$st = '<?php
			if(!isset($GLOBALS[\'all_words\']))
			{
				$GLOBALS[\'all_words\'] = '.var_export($language_convert,1).';
			}
			?>';
			$f = fopen('cache/language_'.$language_id.'.php','w+');
			fwrite($f,$st);
			fclose($f);
			$st = 'TCV.Portal.words = '.String::array2js($language_convert).';';
			$f = fopen('cache/language_'.$language_id.'.js','w+');
			fwrite($f,$st);
			fclose($f);
		}
	}
	function get_all_portal($cond=false){
		$portals = DB::fetch_all('
			SELECT 
				account.id,party.name_1 name 
			FROM 
				account 
				INNER JOIN party ON party.user_id = account.id
			WHERE 
				account.type=\'PORTAL\' 
				'.$cond.'
			ORDER BY 
				account.id');
		return $portals;		
	}
	function get_portal_list($user_portals=false)
    {
		$portals = DB::fetch_all('
			SELECT 
				account.id,
                party.name_1 name 
			FROM 
				account 
				INNER JOIN party ON party.user_id = account.id
			WHERE 
				account.type=\'PORTAL\' 
			ORDER BY 
				account.id');
		if(!User::is_admin())
        {
			$user_id = Url::get('user_id')?Url::get('user_id'):Session::get('user_id');
			if(!$user_portals)
            {
							
				$user_portals = DB::fetch_all('
					SELECT 
						ACCOUNT_PRIVILEGE_GROUP.portal_id as id
					FROM 
						ACCOUNT_PRIVILEGE_GROUP 
					WHERE 
						ACCOUNT_PRIVILEGE_GROUP.account_id=\''.$user_id.'\'
					');
                    
			}
		}
		if($user_portals)
        {
			foreach($portals as $key=>$value)
            {
				if(!isset($user_portals[$key]))
                {
					unset($portals[$key]);
				}
			}
		}
		if(empty($portals))
        {
			$portals = array(
				'#default'=>array(
				'id'=>'#default',
				'name'=> DB::fetch('select id,name_1 from party where user_id = \'#default\'','name_1'))
			);
		}
		return $portals;
	}
	static function get_module_id($name){
		if($row = DB::fetch('SELECT id,name FROM module WHERE name = \''.$name.'\'')){
			return $row['id'];
		}else{
			return false;
		}
	}	
}
Portal::$page_gen_time = new Timer();
Portal::$page_gen_time->start_timer();
require_once 'cache/language_'.Portal::language().'.php';
Portal::$current = new Portal();
?>