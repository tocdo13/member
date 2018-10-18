<?php
/******************************
WRITTEN BY vuonggialong
EDITED BY KHOAND
******************************/

session_start(); 
//define ('DEBUG',0); // huan sua 28/8 da exists trong library.php
//config header
define('DEBUG',1);
define('BLOCK_CREATE_FOLIO',429);
define('BLOCK_PAYMENT',428);
define('BLOCK_UPDATE_TRAVELLER',563);
header("Content-Type: text/html; charset=utf-8");   
ini_set ('zend.ze1_compatibility_mode','off');
// include kernel files
require_once 'cache/modules.php';
require_once 'packages/core/includes/system/database.php';
require_once 'packages/core/includes/portal/check_categories.php';
require_once 'packages/core/includes/system/database_session.php';
require_once 'packages/core/includes/system/system.php';
require_once 'packages/core/includes/system/url.php';
require_once 'packages/core/includes/system/id_structure.php';
require_once 'packages/core/includes/portal/types.php';
require_once 'packages/core/includes/portal/form.php';
require_once 'packages/core/includes/portal/portal.php';
init_portal();
require_once 'packages/core/includes/system/user.php';
require_once 'packages/core/includes/portal/module.php';
require_once 'packages/core/includes/system/night_audit.php';
//error report
error_reporting(E_ALL);
// Disable ALL magic_quote
ini_set('magic_quotes_runtime', 0);
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
if(!file_exists('cache/default_settings.php'))
{
	require_once 'packages/core/includes/system/make_default_settings.php';
	make_default_settings();
}
require_once 'cache/default_settings.php';
function init_portal(){
	if(!isset($_REQUEST['portal'])){
		if($item = DB::fetch('SELECT id,portal_id FROM session_user WHERE session_user.user_id = \''.Session::get('user_id').'\' and session_user.portal_id is not null')){
			$_REQUEST['portal'] = str_replace('#','',$item['portal_id']);
		}else{
			$_REQUEST['portal'] = 'default';
		}
	}
	if(isset($_REQUEST['portal']) 
			and ((!Session::is_set('portal') 
				or !Session::is_set('portal','id') 
				or ('#'.$_REQUEST['portal'])!=Session::get('portal','id')))
					and $portal=DB::fetch('SELECT * from account where id=\'#'.addslashes($_REQUEST['portal']).'\''))
	{
		Session::set('portal', $portal);
	}
	if(isset($_REQUEST['selected_portal_id'])){
		$portal= DB::fetch('select * from account where id=\''.$_REQUEST['selected_portal_id'].'\'');
		Session::set('portal', $portal);
	}
	if(Session::is_set('portal') and Session::get('portal'))
	{
		require_once 'packages/core/includes/system/make_account_setting_cache.php';
		make_account_setting_cache(Session::get('portal','id'));
		Session::set('portal', DB::select('ACCOUNT','ID=\''.Session::get('portal','id').'\''));
		define('PORTAL_PREFIX',str_replace('#','p_',Session::get('portal','id')).'_');
		define('PORTAL_ID',Session::get('portal','id'));
		DB::update('SESSION_USER',array('portal_id'=>PORTAL_ID),'user_id=\''.Session::get('user_id').'\'');
	}
	if(isset($_REQUEST['page'])){
		$page = $_REQUEST['page'];
	}else{
		$page = '?page=select_portal';
	}
}
require_once 'cache/portal/'.str_replace('#','',PORTAL_ID).'/config/config.php';
require_once 'cache/portal/'.str_replace('#','',PORTAL_ID).'/config/date.php';
function check_compatible_brower($checked=true){
	if($checked){
		$br = $_SERVER['HTTP_USER_AGENT'];
		//if(User::is_admin())
		{
			//echo $br;
		}
		if(!preg_match('/Chrome/',$br) and !preg_match('/Windows Phone OS/',$br) and !preg_match('/MSIE/',$br)) 
		{
			echo '<div style="color:#FF0000;font-family:arial;float:left;font-weight:bold;font-size:14px;padding:5px;text-align:center;width:100%;">
					<img src="'.HOTEL_BANNER.'"><br /><br />He thong chi chay tren trinh duyet GOOGLE CHROME, WINDOWS PHONE, IE>=7. Tran trong cam an quy khach!<br /><br />
					System runs only on GOOGLE CHROME, WINDOWS PHONE browser. Thanks and Warm Regards!
				</div>';
			exit();
		}
	}
}
function check_expired_time($date=false)
{
	if($date and Date_Time::to_time($date) <= Date_Time::to_time(date('d/m/Y',time()))+7*86400)
    {
		if(Url::get(date('dmY'))==date('dmY')) 
        { 
            
        } 
        else 
        {
			if($date and Date_Time::to_time($date) > Date_Time::to_time(date('d/m/Y',time())))
                echo '<div style="color:#FF0000;font-family:arial;float:left;font-weight:bold;font-size:14px;padding:5px;text-align:center;width:100%;">
    					Quy khach vui long lien he voi Newway truoc ngay '.$date.' de gia han su dung phan mem.<br><br /> Tran trong cam on!
    				</div>';
			else
            {
                echo '<div style="color:#FF0000;font-family:arial;float:left;font-weight:bold;font-size:14px;padding:5px;text-align:center;width:100%;">
    					<img src="'.HOTEL_BANNER.'"><br /><br />
    					Quy khach hang da het han su dung phan mem. <br />De duoc tiep tuc su dung phan mem quy khach hang hay lien he voi nha cung cap de gia han.<br><br /> Tran trong cam on!
    				</div>';
                    exit();
            }
		}
	}
}
function count_down_exprise($date){
	if($date and Date_Time::to_time($date) > Date_Time::to_time(date('d/m/Y',time()))){
		$remain = (Date_Time::to_time($date) - Date_Time::to_time(date('d/m/Y',time())))/(3600*24);
		if($remain>0){
			echo '	<div class="notice" style="width:200px;border:1px solid #0000ff;position:absolute;top:35px;left:800px;font-size:14px;background:#FFFFFF;">
						Hạn dùng thử phần mềm của quý khách đã hết.Quý khách vui lòng liên hệ với đơn vị phát triển để cài đặt bản chính thức!`.
					</div>';
		}
	}
}
function run_install(){
	if(is_dir('install')){
		if(file_exists('cache/config/config.php') and file_exists('cache/config/db.php')){
			@rename('install','install_');			
		}else{
			header('Location:install/install.php');
		}
	}	
}
?>