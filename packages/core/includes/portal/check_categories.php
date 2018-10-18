<?php
/******************************
WRITTEN BY THEDEATH
EDITED BY KHOAND
******************************/
function check_categories($categories)
{
	$category_arr = DB::fetch_all('
		select
			id,
            category_id
		from
			account_privilege
		where
			account_id=\''.Session::get('user_id').'\' AND portal_id=\''.PORTAL_ID.'\' order by id');
	
    //edited by langdd
    $category_arr = DB::fetch_all('
			SELECT
				privilege.*,
				privilege_module.*,
				privilege.category_id as id,
				privilege.id as privilege_id
			FROM
				privilege
				inner join privilege_module on 	privilege_module.privilege_id = privilege.id
			WHERE
				privilege.account_id=\''.Session::get('user_id').'\'
				AND privilege_module.portal_id = \''.PORTAL_ID.'\'
		');
    //end edited
    $checked_categories = array();
	foreach($category_arr as $key=>$value)
    {
		$checked_categories[$value['category_id']] = $value['category_id'];
	}
	$group_name = 'group_name_'.Portal::language();
	$category_groups = DB::fetch_all('
		SELECT
			privilege_group_detail.id,
			privilege_group_detail.category_id
		FROM
			privilege_group_detail
			INNER JOIN 
            account_privilege_group 
            on 
            account_privilege_group.group_privilege_id = privilege_group_detail.privilege_group_id
		WHERE
			account_privilege_group.account_id=\''.Session::get('user_id').'\' AND account_privilege_group.portal_id=\''.PORTAL_ID.'\' order by id
	');
	foreach($category_groups as $key=>$value)
    {
		if(!isset($checked_categories[$value['category_id']]))
		{
			$checked_categories[$value['category_id']] = $value['category_id'];
		}
	}
	foreach($categories as $id=>$category)
	{
		$categories[$id]['group_name'] = $category[$group_name];
		if(!isset($checked_categories[$category['id']]) and !User::is_admin() and !User::is_deploy())
		{
			unset($categories[$id]);
		}
	}
	return $categories;
}
?>