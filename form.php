<?php
date_default_timezone_set('Asia/Saigon');
define('DEVELOPING',false);
define('BEGINNING_YEAR',2011);
define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
require_once ROOT_PATH.'packages/core/includes/system/config.php';
if(isset($_REQUEST['portal_id']) && $_REQUEST['portal_id'])
{
	Session::set('portal_id',$_REQUEST['portal_id']);
	if(!defined('PORTAL_ID'))
	{
		define('PORTAL_ID',$_REQUEST['portal_id']);
	}
}
else
{
	if(!defined('PORTAL_ID'))
	{	
		define('PORTAL_ID','#default');
	}
}
//define( 'WEB_ROOT','http://'.$_SERVER['HTTP_HOST'].'/');
if(URL::get('block_id') and $block = DB::select('block','id=\''.intval(URL::sget('block_id')).'\''))
{	
	$GLOBALS['root'] = Portal::get_setting('website_url_root','');
	$GLOBALS['current_page'] = DB::select('page',$block['page_id']);
	$GLOBALS['current_user'] = new User();
	$block_settings = String::get_list(DB::fetch_all('select setting_id as id, value as name from block_setting where block_id=\''.$block['id'].'\''),'name');
	$settings = String::get_list(DB::fetch_all('select id, default_value as name from module_setting where module_id=\''.$block['module_id'].'\''),'name');
	foreach($settings as $setting_id=>$value)
	{
		if(!isset($block_setting[$setting_id]))
		{
			$block_setting[$setting_id] = $value;
		}
	}
	$blocks = array(
		$block['id'] => $block + array (
			'settings' => $block_settings,
			'module' => DB::fetch('select id, name, path, type, use_dblclick,package_id from module where id=\''.$block['module_id'].'\'')
		)
	);
	
	require_once $blocks[$block['id']]['module']['path'].'class.php';
	$blocks[$block['id']]['object'] = new $blocks[$block['id']]['module']['name']($blocks[$block['id']]);
	if(URL::get('form_block_id')==$block['id'])
	{
		$blocks[$block['id']]['object']->submit();
	}
	if(!defined('SKIN_PATH'))
	{
		define ('SKIN_PATH',$GLOBALS['root'].'skins/default/');
	}
	require_once ROOT_PATH.'packages/core/includes/utils/draw.php';
	if(!Url::get('client'))
	{
		require_once ROOT_PATH.'packages/core/includes/portal/header_ajax.php';
	}
	//require_once 'packages/portal/includes/portal/header.php';
	$blocks[$block['id']]['object']->on_draw();
	//require_once 'packages/portal/includes/portal/footer.php';
}
else
{
	die('block_id not found in system!');
}
DB::close();
?>