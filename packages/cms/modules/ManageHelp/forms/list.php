<?php
class ListManageHelpForm extends Form
{
	function ListManageHelpForm()
	{
		Form::Form('ListManageHelpForm');
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{ 
			$this->deleted_selected_ids();
		}else{
			$this->update();
		}
	}
	function draw()
	{
		$portal_id='#default';	
		$this->get_just_edited_id();
		$this->get_items($portal_id);
		$items=ManageHelpDB::check_categories($this->items);
		$this->map['items'] = $items;
		$this->parse_layout('list',$this->just_edited_id+$this->map);
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
			if($id and $category=DB::fetch('select id,structure_id from category where id='.intval($id)) and User::can_edit(false,$category['structure_id']))
			{
				DB::delete_id('category',$id);
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
		$items = DB::fetch_all('
			select 
				help_content.id 
				,help_content.structure_id
				,help_content.status
				,help_content.attachment_file
				,help_content.name_'.Portal::language().' as name 
				,help_content.description_'.Portal::language().' as description
				,help_content.check_privilege
			from 
			 	help_content
			where
				 '.$this->cond.'
			order by 
				help_content.structure_id
		',false);
		$this->items = $items;
		require_once 'packages/core/includes/utils/category.php';
		category_indent($this->items,false);		
	}
	function get_select_condition($portal_id)
	{
		$this->cond = '
				1=1'
			.((URL::get('cmd')=='delete' and is_array(URL::get('selected_ids')))?' and help_content.id in (\''.join(URL::get('selected_ids'),'\',\'').'\')':'')
		;
	}
	function update(){
		foreach($_REQUEST as $key=>$value){
			if(ereg("status_",$key)){
				$arr = explode('_',$key);
				DB::update('help_content',array('status'=>$value),'id='.$arr[1]);
			}
			if(ereg("module_",$key)){
				$arr = explode('_',$key);
				if($module = DB::fetch('select id from module where name=\''.$value.'\'')){
					DB::update('help_content',array('module_id'=>$module['id']),'id='.$arr[1]);
				}
			}
			if(ereg("groupname1_",$key)){
				$arr = explode('_',$key);
				DB::update('help_content',array('group_name_1'=>$value),'id='.$arr[1]);
			}
			if(ereg("groupname2_",$key)){
				$arr = explode('_',$key);
				DB::update('help_content',array('group_name_2'=>$value),'id='.$arr[1]);
			}
		}
		Url::redirect_current();
	}
}
?>