<?php
class DeleteShopInvoiceForm extends Form
{
	function DeleteShopInvoiceForm()
	{
		Form::Form("DeleteShopInvoiceForm");
		$this->add('id',new IDType(true,'object_not_exists','shop_invoice'));
	}
	function on_submit()
	{
		if($this->check() and URL::get('confirm') and $product = DB::select('shop_invoice','id=\''.$_REQUEST['id'].'\''))
		{
			//DB::update_id('shop_invoice',array('cancel_note'),Url::get('id'));
			$this->delete($_REQUEST['id']);
			Url::redirect_current(array());
		}
	}
	function draw()
	{
		DB::query('
			select 
				shop_invoice.id
				,shop_invoice.time 
				,shop_invoice.agent_name 
				,shop_invoice.agent_address 
			 from 
			 	shop_invoice
			where
				shop_invoice.id = '.URL::get('id'));
		if($row = DB::fetch())
		{
			$this->parse_layout('delete',$row);
		}
	}
	function delete($id)
	{
		DB::delete_id('shop_invoice', $id);
		DB::delete('shop_invoice_detail', 'shop_invoice_id='.$id);
	}
}
?>