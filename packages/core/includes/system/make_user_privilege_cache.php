<?php
/******************************
WRITTEN BY vuonggialong
MODIFIED BY khoand
******************************/
function get_value($value)
{
	if($value!=1)
	{
		return 0;
	}
	return $value;
}
function make_user_privilege_cache($id,$portal_id)
{
	$sql = '	
		SELECT
			privilege_module.id,
			privilege_module.module_id,
			account_privilege.portal_id,
			can_view,
			can_view_detail,
			can_add,
			can_edit,
			can_delete,
			can_special,
			can_admin,
			can_reserve
		FROM
			account_privilege
			,privilege_module
		WHERE 
			category_id = 0
			AND (account_privilege.account_id=\''.$id.'\' or account_privilege.account_id=\'guest\')
			AND account_privilege.privilege_id=privilege_module.privilege_id
			AND privilege_module.portal_id = \''.$portal_id.'\'
	';
	
	$user_actions = DB::fetch_all($sql);	
	$actions = array();
	foreach($user_actions as $user_action)
	{
		if($byte_cache = bindec(get_value($user_action['can_view']).get_value($user_action['can_view_detail']).get_value($user_action['can_add']).get_value($user_action['can_edit']).get_value($user_action['can_delete']).get_value($user_action['can_special']).get_value($user_action['can_reserve']).get_value($user_action['can_admin'])))
		{
			$actions[$user_action['portal_id']][$user_action['module_id']][0] = $byte_cache;
		}
	}
	$sql = '	
		SELECT
			privilege_module.id,
			privilege_module.module_id,
			account_privilege.portal_id,
			category.structure_id,
			can_view,
			can_view_detail,
			can_add,
			can_edit,
			can_delete,
			can_special,
			can_admin,
			can_reserve,
			category.name_1
			
		FROM
			privilege_module
			,account_privilege
			,category 
		WHERE 
			category_id <> 0 and category.status<>\'HIDE\'
			and account_privilege.privilege_id=privilege_module.privilege_id
			AND (account_privilege.account_id=\''.$id.'\' or account_privilege.account_id=\'guest\')
			and  account_privilege.category_id = category.id
			AND privilege_module.portal_id = \''.$portal_id.'\'
		
	';
	$user_actions = DB::fetch_all($sql);	
	foreach($user_actions as $user_action)
	{
		if($byte_cache = bindec(get_value($user_action['can_view']).get_value($user_action['can_view_detail']).get_value($user_action['can_add']).get_value($user_action['can_edit']).get_value($user_action['can_delete']).get_value($user_action['can_special']).get_value($user_action['can_reserve']).get_value($user_action['can_admin'])))
		{
			$actions[$user_action['portal_id']][$user_action['module_id']][$user_action['structure_id']] = $byte_cache;
		}		
	}
	$sql = '	
		SELECT 
			account_privilege.id,
			privilege_module.module_id,
			account_privilege.portal_id,
			can_view,
			can_view_detail,
			can_add,
			can_edit,
			can_delete,
			can_special,
			can_admin,
			can_reserve
		FROM 
			privilege_module
			,account_privilege 
			,account_related
		WHERE
			category_id=0
			and account_privilege.privilege_id=privilege_module.privilege_id
			and (account_related.parent_id=account_privilege.account_id AND account_related.child_id=\''.$id.'\')
			AND privilege_module.portal_id = \''.$portal_id.'\'
	';
	
	$group_actions = DB::fetch_all($sql);
	foreach($group_actions as $group_action)
	{
		$actions[$group_action['portal_id']][$group_action['module_id']][0]=
		(isset($actions[$group_action['portal_id']][$group_action['module_id']])?$actions[$group_action['portal_id']][$group_action['module_id']]:0) | bindec(get_value($user_action['can_view']).get_value($user_action['can_view_detail']).get_value($user_action['can_add']).get_value($user_action['can_edit']).get_value($user_action['can_delete']).get_value($user_action['can_special']).get_value($user_action['can_reserve']).get_value($user_action['can_admin']));
	}
	
    $actions = array();
	DB::query('	
		SELECT 
            privilege_group_detail.id,
			privilege_group_detail.module_id,
			account_privilege_group.portal_id,
			category.structure_id,
			can_view,
			can_view_detail,
			can_add,
			can_edit,
			can_delete,
			can_special,
			can_admin,
			can_reserve
		FROM 
			privilege_group
			inner join privilege_group_detail on privilege_group_detail.privilege_group_id = privilege_group.id
			inner join category on category.id = privilege_group_detail.category_id
			INNER JOIN account_privilege_group on account_privilege_group.group_privilege_id = privilege_group.id
		WHERE
			account_privilege_group.account_id=\''.$id.'\' 
            and account_privilege_group.portal_id=\''.$portal_id.'\'
            and privilege_group_detail.portal_id=\''.$portal_id.'\'
	');
	$group_actions = DB::fetch_all();
	foreach($group_actions as $user_action)
	{
		if($byte_cache = bindec(get_value($user_action['can_view']).get_value($user_action['can_view_detail']).get_value($user_action['can_add']).get_value($user_action['can_edit']).get_value($user_action['can_delete']).get_value($user_action['can_special']).get_value($user_action['can_reserve']).get_value($user_action['can_admin'])))
		{
            if(isset($actions[$user_action['portal_id']][$user_action['module_id']][$user_action['structure_id']]))
            {
                $actions[$user_action['portal_id']][$user_action['module_id']][$user_action['structure_id']] |= $byte_cache;
            }
            else
            {
                $actions[$user_action['portal_id']][$user_action['module_id']][$user_action['structure_id']] = $byte_cache;
            } 
		}		
	}
	$groups = DB::fetch_all('
		SELECT
			parent_id as id
		FROM
			account_related
		WHERE	
			child_id=\''.$id.'\'
	');
	$code = '$this->groups='.str_replace("'",'"',var_export($groups,true)).';'.
		'$this->actions='.str_replace("'",'"',var_export($actions,true)).';';
	// cache quyen vao file thay cho database vi gioi han 4000 cua truong varchar2
	$file_path = 'cache/portal/'.str_replace('#','',$portal_id).'/user/'.$id.'.php';
	$file = fopen($file_path,'w+');
	fwrite($file,$code);
	fclose($file);
	//DB::update('account',array('cache_privilege'=>$code),'id=\''.$id.'\'');
	return $code;
}
?>