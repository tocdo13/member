<?php
class ListZoneForm extends Form
{
	function ListZoneForm()
	{
		Form::Form('ListZoneForm');
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
				ZoneForm::delete($this,$id);
				if($this->is_error())
				{
					return;
				}
			}
			Url::redirect_current(array('name'=>isset($_GET['name'])?$_GET['name']:''));
		}
	}
	function draw()
	{
		$cond = /*IDStructure::direct_child_cond(ID_ROOT)*/IDStructure::child_cond(ID_ROOT)
			.(URL::get('name')?' and zone.name_'.Portal::language().' as name LIKE \'%'.URL::get('name').'%\'':'') 
			.((URL::get('cmd')=='delete' and is_array(URL::get('selected_ids')))?' and zone.id in (\''.join(URL::get('selected_ids'),'\',\'').'\')':'')
		;
		DB::query('
			select 
				zone.id
				,zone.structure_id
				,zone.name_1
				,zone.name_2				
				,zone.brief_name_1
				,zone.brief_name_2								
			from 
			 	zone
			where 
				'.$cond.'
			order by 
				zone.structure_id
		');
		$items = DB::fetch_all();
		require_once 'packages/core/includes/utils/category.php';
		category_indent($items);
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
			)
		);
	}
}
?>