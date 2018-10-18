<?php
class ManageHelpForm extends Form
{
	function ManageHelpForm()
	{
		Form::Form("ManageHelpForm");
		$this->add('id',new IDType(true,'object_not_exists','category'));
		$this->link_css(Portal::template('core').'/css/help_content.css');
	}
	function on_submit()
	{
		if(Url::get('id') and $category=DB::fetch('select id,structure_id from category where id='.intval(Url::get('id'))) and User::can_edit(false,$category['structure_id']))
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
		$this->item_data = DB::select('help_content',$id);
		 if(file_exists($this->item_data['attachment_file']))
		{
			@unlink($this->item_data['attachment_file']);
		} 
		DB::delete_id('help_content', $id);
	}
	function load_data()
	{
		DB::query('
			select 
				help_content.id
				,help_content.structure_id
				,help_content.name_'.Portal::language().' as name
				,help_content.description_'.Portal::language().' as description 
			from 
			 	help_content
			where
				help_content.id = \''.URL::sget('id').'\'');
		if($this->item_data = DB::fetch())
		{
		}
	}
	function load_multiple_items()
	{
	}
}
?>