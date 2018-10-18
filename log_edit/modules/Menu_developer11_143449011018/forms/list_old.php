<?php
class MenuThuyForm extends Form
{
	function MenuThuyForm()
	{
		Form::Form('MenuThuyForm');
        if(Url::get('page') == 'reality_room_map'){
            $this->link_js('packages/core/includes/js/jquery/ui/jquery.min.js');
            $this->link_js('packages/core/includes/js/jquery/ui/jquery-ui.min.js');
        }
		$this->link_js('packages/core/includes/js/jquery/jquery.dimensions.min.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.MenuThuy.js');
		$this->link_css(Portal::template('cms').'/css/MenuThuy.css');
	}
	function draw()
	{
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
		$this->parse_layout($layout,array('categories'=>$categories));		
	}
}
?>