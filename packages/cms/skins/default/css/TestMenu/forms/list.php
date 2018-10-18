<?php
class TestMenuForm extends Form
{
	function TestMenuForm()
	{
		Form::Form('TestMenuForm');
		$this->link_js('packages/core/includes/js/jquery/jquery.dimensions.min.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.menu.js');
		$this->link_css(Portal::template('cms').'/css/test_menu.css');
	}
	function draw()
	{
		/*if(User::is_admin()){
			require 'cache/hotel/category.php';
		}else{
			if(file_exists('cache/portal/'.str_replace('#','',PORTAL_ID).'/category.php')){
				require 'cache/portal/'.str_replace('#','',PORTAL_ID).'/category.php';
			}else{
				$categories = array();
			}
		}*/
		require 'cache/hotel/category.php';
		require 'packages/core/includes/utils/category.php';
		$categories = check_categories($categories);
		$categories = String::array2tree($categories,'child');
		/*if(count($categories)>=2)
		{
			$layout = 'list';
		}else
		{
			$layout = 'vertical';
			$temp = current($categories);
			$categories = $temp['child'];
			//System::debug($categories);
		}*/
		$layout = 'list';
		$this->map['current_portal'] = DB::fetch('select account.id,party.name_1 from account inner join party on party.user_id = account.id where account.id=\''.PORTAL_ID.'\'','name_1');
		$this->map['categories'] = $categories;
		$this->parse_layout($layout,$this->map);		
	}
}
?>