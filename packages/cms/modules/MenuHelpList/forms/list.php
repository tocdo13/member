<?php
class MenuHelpListForm extends Form
{
	function MenuHelpListForm()
	{
		Form::Form('MenuHelpListForm');
		$this->link_css('packages/core/skins/default/css/jquery/jquery.treeview.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.treeview.js');
	}
	function draw()
	{
		$menu = '';
		$help_list = MenuHelpListDB::get_help_list($cond ='1 =1 AND help_content.status=\'SHOW\'',$structure_id = ID_ROOT);
        //System::debug($help_list);
		$this->parse_layout('list',array(
			'items'=>$help_list
		));
	}
}
?>
