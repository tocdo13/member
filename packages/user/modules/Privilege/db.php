<?php
class PrivilegeDB{
	function update_moderator($user_id,$privilege_id,$category_id=false,$portal_id)
	{
		if(!$category_id or DB::select('category','id=\''.$category_id.'\''))
		{
			$row = array(
				'account_id'=>$user_id,
				'category_id'=>$category_id,
				'portal_id'=>$portal_id,
				'privilege_id'=>$privilege_id
			);				
			if(DB::fetch('select id from account_privilege where portal_id = \''.$portal_id.'\' and account_id=\''.$user_id.'\' and privilege_id='.$privilege_id))
			{
				DB::update('account_privilege',$row,'portal_id = \''.$portal_id.'\' and account_id=\''.$user_id.'\' and privilege_id='.$privilege_id);
			}
			else
			{
				DB::insert('account_privilege',$row);
			}
			DB::update('account',array('cache_privilege'=>''),'id=\''.$user_id.'\'');
		}
	}
	function have_privilege($privilege_id, $portal_id)
	{
		if(User::is_admin())
		{
			return true;
		}
		if(!DB::select('account_privilege','account_id=\''.Session::get('user_id').'\' and portal_id=\''.$portal_id.'\''))
		{
			return $privilege_id==1;			
		}
		return false;
	}
	function get_privileges()
	{
		if(User::is_admin())
		{
			return DB::fetch_all('
				select 
					id,title_'.Portal::language().' as title 
				from 
					privilege
				order by
					title_1 ASC
			');
		}
		else
		if(User::can_admin(false,ANY_CATEGORY))
		{
			return DB::fetch_all('
				select 
					id,title_'.Portal::language().' as title 
				from 
					privilege 
				where
					id not in (16,17,18)');
		}
		else
		{
			return array();
		}
	}
	function get_users()
	{
		return DB::fetch_all('
			SELECT
				account.id, party.full_name as name
			FROM
				account
				left outer join party on party.user_id = account.id
			WHERE 
				account.type=\'USER\'
			ORDER
				 by account.id
		');
	}
	function get_categories($parent_id)
	{
		$function = DB::fetch_all('
			SELECT
				category.*,
				category.name_'.Portal::language().' as name 
			FROM
				category
			WHERE
				'.IDStructure::child_cond(DB::structure_id('category',$parent_id),true).'		
				and category.status <> \'HIDE\'
			ORDER BY
				category.structure_id 
		');		//id,category.structure_id,category.module_id
		foreach($function as $key=>$value)
		{
			$function[$key] = $value;
			
			$function[$key]['have_child'] = 0;
			if(IDStructure::level($value['structure_id'])==2)
			{
				$parent = IDStructure::parent($value['structure_id']);
			}
			elseif(IDStructure::level($value['structure_id'])==3)
			{
				$parent = IDStructure::parent(IDStructure::parent($value['structure_id']));
			}
			elseif(IDStructure::level($value['structure_id'])==4)
			{
				$parent = IDStructure::parent(IDStructure::parent(IDStructure::parent($value['structure_id'])));
			}
			else
			{
				$parent = ID_ROOT;
			}
			$function[$key]['parent'] = DB::fetch('select id from category where structure_id='.$parent,'id');
			if(IDStructure::have_child('category',$value['structure_id']))
			{
				$function[$key]['have_child'] = 1;
			}
		}
		return $function;
	}
	function get_item_count($cond)
	{
		return DB::fetch('
			select
				 count(*) as acount
			from 
				account
				inner join privilege on privilege.account_id = account.id
				left outer join party on party.user_id = account.id
				inner join category on category.id = privilege.category_id
			where 
				'.$cond.'
			group by
				account.id
		','acount');
	}
	function get_items($cond, $item_per_page)
	{
		return DB::fetch_all('
			SELECT 
				distinct account.id				
				,party.full_name
				,party.email
				,party.phone
			FROM 
			 	account
				inner join privilege on privilege.account_id = account.id
				left outer join party on party.user_id = account.id
				inner join category on category.id = privilege.category_id
			WHERE 
				'.$cond.'
			ORDER BY
				id ASC
		');		
	}
	function get_privilege($privilege_group_id,$portal_id)
	{
		$sql = '
			SELECT
				privilege_group.*,
				privilege_group_detail.*,
				privilege_group_detail.category_id as id,
				privilege_group.id as privilege_id
			FROM
				privilege_group
				inner join privilege_group_detail on 	privilege_group_detail.privilege_group_id = privilege_group.id
			WHERE
				privilege_group.id=\''.$privilege_group_id.'\'
				AND privilege_group_detail.portal_id = \''.$portal_id.'\'
		';
		$data =  DB::fetch_all($sql);
		return $data;
	}
}
?>