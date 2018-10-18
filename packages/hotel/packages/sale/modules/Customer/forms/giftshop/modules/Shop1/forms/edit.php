<?php
class EditShopForm extends Form
{
	function EditShopForm()
	{
		Form::Form('EditShopForm');		

		$this->add('shop.code',new UniqueType(false,'invalid_code','shop','code')); 

		$this->add('shop.name',new TextType(true,'invalid_name',0,255));
	}
	function on_submit()
	{
		if($this->check())
		{
			if(URL::get('selected_ids'))
			{
				if(strpos(URL::get('selected_ids'),','))
				{
					$old_items = DB::select_all('shop','id in ('.URL::get('selected_ids').')');
				}
				else
				{
					$old_item = DB::select('shop',URL::get('selected_ids'));
					$old_items = array($old_item['id']=>$old_item);
				}
			}
			else
			{
				$old_items = array();
			}
			if(isset($_REQUEST['mi_shop']))
			{
				foreach($_REQUEST['mi_shop'] as $key=>$record)
				{
					if($record['id'] and isset($old_items[$record['id']]))
					{
						DB::update('shop',$record,'id='.$record['id']);
						if(isset($old_items[$record['id']]))
						{
							$old_items[$record['id']]['not_delete'] = true;
						}
					}
					else
					{
						unset($record['id']);
						DB::insert('shop',$record); //huan them $ids = 
					}
				}
			}
			foreach($old_items as $item)
			{
				if(!isset($item['not_delete']))
				{
					DB::delete_id('shop',$item['id']);
				}
			}
				Url::redirect_current(array('selected_ids')+array(
	'shop_code', 'shop_name', 
	));
		}
	}	
	function draw()
	{
		require_once 'list.php';
		if(URL::get('selected_ids'))
		{
			if(is_string(URL::get('selected_ids')))
			{
				$selected_ids = explode(',',URL::get('selected_ids'));
			}
			else
			{
				$selected_ids = URL::get('selected_ids');
			}
			if(sizeof($selected_ids)>0)
			{
				if(!isset($_REQUEST['mi_shop']))
				{
					DB::query('
						select 
							*
						from 
							shop
						where
							'.((sizeof($selected_ids)>1)?'id in ('.join(URL::get('selected_ids'),',').')':'id='.reset($selected_ids)).'
					');
					$mi_shop = DB::fetch_all();
					$_REQUEST['mi_shop'] = $mi_shop;
					$_REQUEST['selected_ids'] = join($selected_ids,',');
				}
			}
		}
		$this->parse_layout('edit',
			array(
			)
		);
	}
}
?>