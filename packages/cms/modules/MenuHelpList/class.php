<?php
class MenuHelpList extends Module
{
	function MenuHelpList($row)
	{
		Module::Module($row);
		require_once 'db.php';
		if(User::is_login())
		{
			switch(Url::get('cmd'))
			{			
				case 'content':
					$this->get_content();
					break;
				default:	
					require_once 'forms/list.php';
					$this->add_form(new MenuHelpListForm());
					break;
			}	
		}else
		{
			Url::redirect('sign_in');
		}
	}
	function get_content()
	{
		if(Url::get('id') and $item = DB::fetch('select 
				help_list.*,
				name_'.Portal::language().' as name
				,description_'.Portal::language().' as description				
			 from 
			 	help_list 
			where 
				id=\''.Url::get('id').'\'
			order by 
				structure_id'))
		{
			$content = '<div class="help-name">'.Portal::language('help_list').'> '.$item['name'].'</div>';
			$content .= '<div class="help-description">'.$item['description'].'</div>';
			echo $content;
		}
		else
		{
			echo  '<div class="no-data">'.Portal::language('no_data').'</div>';
		}
	}
}
?>
