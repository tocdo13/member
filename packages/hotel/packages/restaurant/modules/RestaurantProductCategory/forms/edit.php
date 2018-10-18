<?php
class EditRestaurantProductCategoryForm extends Form
{
	function EditRestaurantProductCategoryForm()
	{
		Form::Form('EditRestaurantProductCategoryForm');
		$this->add('name',new TextType(true,'invalid_name',0,2000));
		$this->link_css('skins/default/restaurant.css');		
	}
	function on_submit()
	{
		//System::debug($_REQUEST);
		if($this->check() and URL::get('confirm_edit'))
		{
			if(URL::get('cmd')=='edit')
			{
				$this->old_value = DB::select('res_product_category','id=\''.addslashes($_REQUEST['id']).'\'');
			}
			$this->save_item();
			Url::redirect_current(Module::$current->redirect_parameters+array('just_edited_id'=>$this->id));
		}
	}	
	function draw()
	{
		require_once 'packages/hotel/includes/php/hotel.php';
		$this->init_edit_mode();
		$this->get_parents();
		$this->init_database_field_select();
		$rows_list = Hotel::get_bar();
		$list_bar[0] = '-------';
		$list_bar = $list_bar+String::get_list($rows_list,'name');
		if(Url::get('bar_id'))
		{
			$bar = DB::select('bar','id='.intval(Url::get('bar_id')));
		}
		
		$this->parse_layout('edit',
			($this->edit_mode?$this->init_value:array())+
			array(
			'bar_name'=>isset($bar['name'])?$bar['name']:'',
			'bar_id_list'=>$list_bar,
			'parent_id_list'=>String::get_list($this->parents),
			'parent_id'=>($this->edit_mode?si_parent_id('res_product_category',$this->init_value['structure_id']):1),
			'type_list'=>$this->type_list
			)
		);
	}
	function save_item()
	{
		$extra = array();
		$extra = $extra+array('name'=>Url::get('name')); 
		$new_row = $extra+array('code');
		//$new_row = $new_row+array('bar_id'=>Url::get('bar_id'));
		require_once 'packages/core/includes/utils/vn_code.php';
			
		if(URL::get('cmd')=='edit')
		{
			$this->id = $_REQUEST['id'];
			DB::update_id('res_product_category', $new_row,$this->id);
			if($this->old_value['structure_id']!=ID_ROOT)
			{
				if (Url::check(array('parent_id')))
				{
					$parent = DB::select('res_product_category',$_REQUEST['parent_id']);					
					if($parent['structure_id']==$this->old_value['structure_id'])
					{
						$this->error('id','invalid_parent');
					}
					else
					{
						require_once 'packages/core/includes/system/si_database.php';
						if(!si_move('res_product_category',$this->old_value['structure_id'],$parent['structure_id']))
						{
							$this->error('id','invalid_parent');
						}
					}
				}
			}
		}
		else
		{
			require_once 'packages/core/includes/system/si_database.php';
			if(isset($_REQUEST['parent_id']))
			{	
				$this->id = DB::insert('res_product_category', $new_row+array('structure_id'=>si_child('res_product_category',structure_id('res_product_category',$_REQUEST['parent_id']))));
			}
			else
			{
				$this->id = DB::insert('res_product_category', $new_row+array('structure_id'=>ID_ROOT));		
			}				
		}
	}
	function init_edit_mode()
	{
		if(URL::get('cmd')=='edit' and $this->init_value= DB::fetch('select * from res_product_category where id='.intval(URL::sget('id')).''))
		{		
			foreach($this->init_value as $key=>$value)
			{
				if(is_string($value) and !isset($_REQUEST[$key]))
				{
					$_REQUEST[$key] = $value;
				}
			}
			$this->edit_mode = true;
		}
		else
		{
			$this->edit_mode = false;
		}
	}
	function get_parents()
	{
		require_once 'packages/core/includes/system/si_database.php';
		$this->parents = RestaurantProductCategoryDB::get_categories('1>0');
	}
	function init_database_field_select()
	{
		$sql = 'select
					type.id,
					type.title_'.Portal::language().' as name
				from 
					portal_type,type
				where
					 type.typeoftype=\'ITEM\' 
					 and  portal_type.portal_id=\''.PORTAL_ID.'\'
					 and type.id(+)=portal_type.type
				'
			;
			$this->type_list = String::get_list(DB::fetch_all($sql,false)); 
	}	
}
?>