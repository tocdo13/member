<?php
function set_default_value()
{
	$tables = DB::get_all_tables();
	$table_system =  array(
		'ACCOUNT' =>'ACCOUNT'
		,'ACCOUNT_PRIVILEGE'=>'ACCOUNT_PRIVILEGE'
		,'ACCOUNT_RELATED'=>'ACCOUNT_RELATED'
		,'ACCOUNT_SETTING'=>'ACCOUNT_SETTING'
		,'ADMIN_TABLE'=>'ADMIN_TABLE'
		,'ADVERTISMENT'=>'ADVERTISMENT'
		,'BLOCK'=>'BLOCK'
		,'BLOCK_SETTING'=>'BLOCK_SETTING'
		,'CATEGORY'=>'CATEGORY'
		,'CRAWLER'=>'CRAWLER'
		,'CRAWLER_PARAMETER'=>'CRAWLER_PARAMETER'
		,'CURRENCY'=>'CURRENCY'
		,'LANGUAGE'=>'LANGUAGE'
		,'LOG'=>'LOG'
		,'MENU'=>'MENU'
		,'MENU_ITEM'=>'MENU_ITEM'
		,'MODULE'=>'MODULE'
		,'MODULE_SETTING'=>'MODULE_SETTING'
		,'MODULE_TABLE'=>'MODULE_TABLE'
		,'PACKAGE'=>'PACKAGE'
		,'PACKAGE_WORD'=>'PACKAGE_WORD'
		,'PAGE'=>'PAGE'
		,'PAGE_ACTION'=>'PAGE_ACTION'
		,'PAGE_EFFECT'=>'PAGE_EFFECT'
		,'PANEL'=>'PANEL'
		,'PARTY'=>'PARTY'
		,'PORTAL_PACKAGE'=>'PORTAL_PACKAGE'
		,'PORTAL_PRIVILEGE'=>'PORTAL_PRIVILEGE'
		,'PORTAL_TYPE'=>'PORTAL_TYPE'
		,'PRIVILEGE'=>'PRIVILEGE'
		,'PRIVILEGE_MODERATOR'=>'PRIVILEGE_MODERATOR'
		,'PRIVILEGE_MODULE'=>'PRIVILEGE_MODULE'
		,'SESSION_USER'=>'SESSION_USER'
		,'SETTING'=>'SETTING'
		,'SETTING_CATEGORY'=>'SETTING_CATEGORY'
		,'WORD'=>'WORD'
		,'WORD_HOTEL'=>'WORD_HOTEL'
		,'ZONE'=>'ZONE'
		,'TYPE'=>'TYPE'
		,'UNIT'=>'UNIT'
	);
	foreach($tables as $id=>$table)
	{
		if(!isset($table_system[strtoupper($id)]))
		{
			if(DB::query('Truncate table '.$id) and $fields = DB::get_fields($id))
			{
				foreach($fields as $key=>$value)
				{
					if($value['type']=='NUMBER')
					{
						DB::query('ALTER TABLE '.$id.'  MODIFY '.$value['name'].' DEFAULT 0');
					}
				}
			}
		}
	}
}
?>