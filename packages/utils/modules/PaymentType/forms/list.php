<?php
class ListPaymentTypeForm extends Form
{
	function ListPaymentTypeForm()
	{
		Form::Form('ListPaymentTypeForm');
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
		$this->get_just_edited_id();
		$this->get_items();
		$items=PaymentTypeDB::check_categories($this->items);
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
			if($id and $payment_type=DB::fetch('select id,structure_id from payment_type where id='.intval($id)) and User::can_edit(false,$payment_type['structure_id']))
			{
				DB::delete_id('payment_type',$id);
			}	
			if($this->is_error())
			{
				return;
			}
		}
		Url::redirect_current(Module::$current->redirect_parameters);
	}
	function get_items()
	{
		$this->get_select_condition();
		$this->items = DB::fetch_all('
			select 
				payment_type.id 
				,payment_type.structure_id
				,payment_type.DEF_CODE 
				,payment_type.name_'.Portal::language().' as name 
			from 
			 	payment_type
			where
				 '.$this->cond.'			
			order by 
				payment_type.structure_id
		',false);
		require_once 'packages/core/includes/utils/category.php';
		category_indent($this->items);		
	}
	function get_select_condition()
	{
		$this->cond = '1=1	
			'.((URL::get('cmd')=='delete' and is_array(URL::get('selected_ids')))?' and payment_type.id in (\''.join(URL::get('selected_ids'),'\',\'').'\')':'')
		;
	}
	
}
?>