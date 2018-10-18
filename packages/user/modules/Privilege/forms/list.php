<?php
class ListPrivilegeForm extends Form
{
	function ListPrivilegeForm()
	{
		Form::Form('ListPrivilegeForm');
		$this->link_css(Portal::template('core').'/css/crud.css');
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{
			foreach(URL::get('selected_ids') as $id)
			{
			}
			require_once 'detail.php';
			foreach(URL::get('selected_ids') as $id)
			{
				PrivilegeForm::delete($this,$id);
				if($this->is_error())
				{
					return;
				}
			}
			require_once 'packages/core/includes/system/update_privilege.php';
			make_privilege_cache();
			Url::redirect_current(array('package_id'=>isset($_GET['package_id'])?$_GET['package_id']:'', 
	));
		}
	}
	function draw()
	{
		$cond = '1 >0 '
				.(URL::get('package_id')?'
					and '.IDStructure::child_cond(DB::fetch('select structure_id from package where id="'. URL::get('package_id',1).'"','structure_id'),false,'package.').'
				':'') 
			.((URL::get('cmd')=='delete' and is_array(URL::get('selected_ids')))?' and privilege_group.id in ('.join(URL::get('selected_ids'),',').')':'')
		;
		$item_per_page = Module::$current->get_setting('item_per_page',50);
		$sql = '
			select 
				count(*) as total
			from 
				privilege_group
			where '.$cond.'
			'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'').'
			
		';
		$count = DB::fetch($sql,'total');
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count,$item_per_page);
		$sql = '
		SELECT * FROM
		(
			select 
				privilege_group.id
				,privilege_group.name_'.Portal::language().' as name
				,privilege_group.description_'.Portal::language().' as description
				,privilege_group.home_page
				,privilege_group.portal_id
				,package.name as package_name				
				,ROWNUM as rownumber
			from 
			 	privilege_group
				inner join package on package.id = privilege_group.package_id
			where 
				'.$cond.'
			'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'').'
		)
		WHERE
			 rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		$items = DB::fetch_all($sql);	
		DB::query('
			select
				id,name 
				,structure_id
			from
				package
			order by structure_id');
		$packages = DB::fetch_all();
		require_once 'packages/core/includes/utils/category.php';
		category_indent($packages);
		$i=1;
		foreach ($items as $key=>$value)
		{
			$items[$key]['i']=$i++;
		}
		$just_edited_id['just_edited_ids'] = array();
		if (UrL::get('selected_ids'))
		{
			if(is_string(UrL::get('selected_ids')))
			{
				if (strstr(UrL::get('selected_ids'),','))
				{
					$just_edited_id['just_edited_ids']=explode(',',UrL::get('selected_ids'));
				}
				else
				{
					$just_edited_id['just_edited_ids']=array('0'=>UrL::get('selected_ids'));
				}
			}
		}
		$this->parse_layout('list',$just_edited_id+
			array(
				'items'=>$items,
				'paging'=>$paging,
				'packages'=>$packages, 
			)
		);
	}
}
?>