<?php
class ListShopInvoiceForm extends Form
{
	function ListShopInvoiceForm()
	{
		Form::Form('ListShopInvoiceForm');
	}
	function draw()
	{
		$cond = '
				1>0 '
				.(URL::get('agent_name')!=''?' and lower(shop_invoice.agent_name) LIKE \'%'.strtolower(addslashes(URL::get('agent_name'))).'%\'':'') 
				.(URL::get('shop_id')!=''?' and shop_id=\''.URL::get('shop_id').'\'':'')
				.(Url::get('total_from')!=''?' and total>='.intval(Url::get('total_from')):'')
				.(Url::get('total_to')!=''?' and total<='.intval(Url::get('total_to')):'')
		;
		$item_per_page = 50;
	
		DB::query('
			select count(*) as acount
			from 
				shop_invoice
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page);
		DB::query('
		select * from (
			select 
				shop_invoice.id, 
				shop_invoice.agent_name, 
				shop_invoice.total,
				shop.name as shop_name,shop_invoice.time,
				ROWNUM as rownumber
			from 
				shop_invoice
				inner join shop on shop.id = shop_invoice.shop_id
			where '.$cond.' 
			'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'order by id desc').' 
		)
		where
			rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'				
		');
		$items = DB::fetch_all();

		DB::query('select id,name from shop');
		$shop = DB::fetch_all();
		
		$this->parse_layout('list',array(
			'items'=>$items,
			'paging'=>$paging,
			'shop_id_list'=>String::get_list($shop)
		));
	}
}
?>