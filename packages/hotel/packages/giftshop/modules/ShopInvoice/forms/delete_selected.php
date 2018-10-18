<?php
class DeleteSelectedShopInvoiceForm extends Form
{
	function DeleteSelectedShopInvoiceForm()
	{
		Form::Form("DeleteSelectedShopInvoiceForm");
		$this->add('confirm',new TextType(true,false,0, 20));
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{

			require_once 'delete.php';
			foreach(URL::get('selected_ids') as $id)
			{
				DeleteShopInvoiceForm::delete($id);
			}
			Url::redirect_current(array());
		}
	}
	function draw()
	{
		DB::query('
			select 
				shop_invoice.id,
				shop_invoice.agent_name ,shop_invoice.agent_address ,
				shop_invoice.time
			from 
			 	shop_invoice
				left outer join shop on shop.id=shop_invoice.shop_id 
			where shop_invoice.id in ('.join(URL::get('selected_ids'),',').')
		');
		$items = DB::fetch_all();
		foreach($items as $key=>$value)
		{
			$items[$key]['time'] = date('H:i d/m/Y',$value['time']);
		}
		DB::query('
			select
				id,name
			from
				shop
			');
		$bars = DB::fetch_all();
		$this->parse_layout('delete_selected',
			array(
				'items'=>$items
			)
		);
	}
}
?>