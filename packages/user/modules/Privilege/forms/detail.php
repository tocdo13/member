<?php
class PrivilegeForm extends Form
{
	function PrivilegeForm()
	{
		Form::Form("PrivilegeForm");
		//$this->add('id',new IDType(true,'object_not_exists','privilege'));
	}
	function on_submit()
	{
		if(URL::get('confirm') and Url::get('id'))
		{
			$this->delete($this,$_REQUEST['id']);
			require_once 'packages/core/includes/system/update_privilege.php';
			make_privilege_cache();
			Url::redirect_current(array('package_id'=>isset($_GET['package_id'])?$_GET['package_id']:'', 
	));
		}
	}
	function draw()
	{
		$sql = '
			select 
				privilege_group.id
				,privilege_group.name_'.Portal::language().' as title
				,privilege_group.description_'.Portal::language().' as description
				,package.name as package_id
			from 
			 	privilege_group
				left outer join package on package.id = privilege_group.package_id
			where
				privilege_group.id = \''.URL::sget('id').'\'
				';
		$row = DB::fetch($sql);
		DB::query('
			select
				account_privilege_group.id
				,account_id as group_id_name 
			from
				account_privilege_group
			where
				account_privilege_group.group_privilege_id=\''.$_REQUEST['id'].'\'
			'
		);
		$row['group_privilege_items'] = DB::fetch_all(); 
		$this->parse_layout('detail',$row);
	}
	function delete(&$form,$id)
	{
		DB::delete('account_privilege_group', 'group_privilege_id=\''.$id.'\''); 
		DB::delete('privilege_group', 'id=\''.$id.'\'');
		DB::delete('privilege_group_detail', 'privilege_group_id=\''.$id.'\'');
	}
}
?>