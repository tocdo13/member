<?php
/******************************
WRITTEN BY vuonggialong
EDITED BY khoand
******************************/

function make_account_setting_cache($id)
{
	$user_settings = DB::fetch_all('	
		SELECT 
			SETTING_ID as ID,
			SETTING.STYLE as VALUE 
		FROM 
			SETTING,ACCOUNT_SETTING
		WHERE
			ACCOUNT_SETTING.ACCOUNT_ID=\''.$id.'\' AND ACCOUNT_SETTING.SETTING_ID = SETTING.ID
	');
	$settings = array();
	foreach($user_settings as $user_setting)
	{
		$settings[$user_setting['id']]=$user_setting['value'];
	}
	/*$group_settings = DB::fetch_all('
		select  
			setting_id as id,
			value 
		from
			account_setting
			inner join account_related on account_id = parent_id
			inner join `account`on `account`.id=parent_id
		where 
			`child_id`="'.$id.'"
	');
	foreach($group_settings as $group_setting)
	{
		$settings[$group_setting['id']]=$group_setting['value'];
	}*/
	if(!empty($settings)){
		$code = var_export($settings,true).';';
	}else{
		$code = '';
	}
	DB::update('ACCOUNT',array('cache_setting'=>$code),'id=\''.$id.'\'');
	return $code;
}
?>