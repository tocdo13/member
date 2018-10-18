<?php
class ListShopForm extends Form
{
	function ListShopForm()
	{
		Form::Form('ListShopForm');
	}
	function draw()
	{
		$cond = '
				1>0 '
			

				.(URL::get('code')?' and lower(shop.code) LIKE \'%'.strtolower(addslashes(URL::get('code'))).'%\'':'') 

				.(URL::get('name')?' and lower(shop.name) LIKE \'%'.strtolower(addslashes(URL::get('name'))).'%\'':'') 
		
		;
		$item_per_page = 50;
		DB::query('
			select count(*) as acount
			from 
				shop
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page);
		DB::query('
			select * from(
				select 
					shop.id,shop.code,
					shop.name,ROWNUM as rownumber
				from 
					shop
				where '.$cond.'
				'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'').'
			)
			where
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
				
		');
		$items = DB::fetch_all();
		$i=1;
		foreach ($items as $key=>$value)
		{
			$items[$key]['i']=$i++;
			if($this->check_delete($key))
			{
				$items[$key]['can_delete'] = true;
			}
			else
			{
				$items[$key]['can_delete'] = false;
			}
		}
		$just_edited_id['just_edited_ids'] = array();
		if (UrL::get('selected_ids'))
		{
			if (strstr(UrL::get('selected_ids'),','))
			{
				$just_edited_id['just_edited_ids']=explode(',',UrL::get('selected_ids'));
			}
			else
			{
				$just_edited_id['just_edited_ids']=array('0'=>UrL::get('selected_ids'));
			}
		}
		$this->parse_layout('list',$just_edited_id+
			array(
				'items'=>$items,
				'paging'=>$paging,
			)
		);
	}
	function check_delete($id)
	{
		if(DB::select('shop_invoice_detail','shop_id='.$id))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}
?>