<?php
class HelpListForm extends Form
{
	function HelpListForm()
	{
		Form::Form("HelpListForm");
		$this->add('id',new IDType(true,'object_not_exists','help_list'));
		$this->link_css(Portal::template('core').'/css/help_list.css');
	}
	function on_submit()
	{
		if(Url::get('id') and $help_list=DB::fetch('select id,structure_id from help_list where id='.intval(Url::get('id'))) and User::can_edit(false,$help_list['structure_id']))
		{
			$this->delete($this,$_REQUEST['id']);
			Url::redirect_current(Module::$current->redirect_parameters);
		}
		else
		{
			Url::redirect_current(Module::$current->redirect_parameters);
		}
	}
	function draw()
	{
		$this->load_data();
		$languages = DB::select_all('language');
		$this->parse_layout('detail',$this->item_data+array('languages'=>$languages));
	}
	function delete(&$form,$id)
	{
		$this->item_data = DB::select('help_list',$id);
		 if(file_exists($this->item_data['icon_url']))
		{
			@unlink($this->item_data['icon_url']);
		} 
		DB::delete_id('help_list', $id);
	}
	function load_data()
	{
		DB::query('
			select 
				`help_list`.id
				,`help_list`.structure_id
				,`help_list`.`is_visible` ,`help_list`.`icon_url` 
				

				,`help_list`.name_'.Portal::language().' as name 

				,`help_list`.description_'.Portal::language().' as description 
				

				,`type`.`id` as type 
			from 
			 	`help_list`
				

				left outer join `type` on `type`.id=`help_list`.type 
			where
				`help_list`.id = "'.URL::sget('id').'"');
		if($this->item_data = DB::fetch())
		{
		}
	}
	function load_multiple_items()
	{
	}
}
?>