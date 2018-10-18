<?php
class ListHelpListForm extends Form
{
	function ListHelpListForm()
	{
		Form::Form('ListHelpListForm');
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{
			$this->deleted_selected_ids();
		}
	}
	function draw()
	{
		$portal_id=PORTAL_ID;	
		$this->get_just_edited_id();
		$this->get_items($portal_id);
		$items=HelpListDB::check_categories($this->items);
		$this->parse_layout('list',$this->just_edited_id+
			array(
				'items'=>$items,
			)
		);
	}	
	function get_just_edited_id()
	{
		$this->just_edited_id['just_edited_ids'] = array();
		if (UrL::get('selected_ids'))
		{
			if(is_string(UrL::get('selected_ids')))
			{
				if (strstr(UrL::get('selected_ids'),','))
				{
					$this->just_edited_id['just_edited_ids']=explode(',',UrL::get('selected_ids'));
				}
				else
				{
					$this->just_edited_id['just_edited_ids']=array('0'=>UrL::get('selected_ids'));
				}
			}
		}
	}
	function deleted_selected_ids()
	{
		foreach(URL::get('selected_ids') as $id)
		{
		}
		require_once 'detail.php';
		foreach(URL::get('selected_ids') as $id)
		{
			if($id and $help_list=DB::fetch('select id,structure_id from help_list where id='.intval($id)) and User::can_edit(false,$help_list['structure_id']))
			{
				DB::delete_id('help_list',$id);
			}	
			if($this->is_error())
			{
				return;
			}
		}
		Url::redirect_current(Module::$current->redirect_parameters);
	}
	function get_items($portal_id)
	{
		$this->get_select_condition($portal_id);
		$this->items = DB::fetch_all('
			select 
				help_list.id 
				,help_list.structure_id
				,help_list.status 
				,help_list.icon_url
				,help_list.type 
				,help_list.name_'.Portal::language().' as name 
				,help_list.description_'.Portal::language().' as description	
			from 
			 	help_list
			where
				 '.$this->cond.'			
			order by 
				help_list.structure_id ASC
		',false);
		require_once 'packages/core/includes/utils/category.php';
		category_indent($this->items);		
	}
	function get_select_condition($portal_id)
	{
		$this->cond = '
				help_list.type!=\'PORTAL\'' 
			.((URL::get('cmd')=='delete' and is_array(URL::get('selected_ids')))?' and help_list.id in (\''.join(URL::get('selected_ids'),'\',\'').'\')':'')
		;
	}
	
}
?>