<?php
class ListRestaurantProductCategoryForm extends Form
{
	function ListRestaurantProductCategoryForm()
	{
		Form::Form('ListRestaurantProductCategoryForm');
		$this->link_css('skins/default/restaurant.css');
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
		require_once 'packages/hotel/includes/php/hotel.php';
		$portal_id = PORTAL_ID;
		$this->get_just_edited_id();
		$this->get_items($portal_id);
		$items = $this->items;
		$rows_list = Hotel::get_bar();
		$list_bar[0] = '-------';
		$list_bar = $list_bar+String::get_list($rows_list,'name');		

		if(Url::get('bar_id'))
		{
			$bar = DB::select('bar','id='.intval(Url::get('bar_id')));
		}

		$this->parse_layout('list',$this->just_edited_id+
			array(
				'bar_name'=>isset($bar['name'])?$bar['name']:'',
				'bar_id_list'=>$list_bar,
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
			if($id and $category=DB::fetch('select id,structure_id from res_product_category where id='.intval($id)) and User::can_edit(false,$category['structure_id']))
			{
				DB::delete_id('res_product_category',$id);
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
		$this->items = RestaurantProductCategoryDB::get_categories($this->cond);
		require_once 'packages/core/includes/utils/category.php';
		category_indent($this->items);		
	}
	function get_select_condition($portal_id)
	{
		$this->cond = '1>0 ' 
			.((URL::get('cmd')=='delete' and is_array(URL::get('selected_ids')))?' and res_product_category.id in (\''.join(URL::get('selected_ids'),'\',\'').'\')':'')
		;
	}
	
}
?>