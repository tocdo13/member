<?php 
class HKWarehouseInvoice extends Module
{
	public static $item = array();
	function HKWarehouseInvoice($row)
	{
		require_once 'db.php';
		Module::Module($row);
		if(!Url::get('type')){
			Url::redirect_current(array('type'=>'IMPORT'));
		}
		switch (Url::get('cmd')){
			case 'view':
				if(User::can_view(false,ANY_CATEGORY)  and Url::get('id') and HKWarehouseInvoice::$item = DB::select('hk_wh_invoice','id ='.Url::iget('id'))){
					require_once 'forms/view.php';
					$this->add_form(new ViewHKWarehouseInvoiceForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'add':
				if(User::can_add(false,ANY_CATEGORY) and Url::get('type')=='EXPORT'){
					require_once 'forms/edit.php';
					$this->add_form(new EditHKWarehouseInvoiceForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and HKWarehouseInvoice::$item = DB::select('hk_wh_invoice','id ='.Url::iget('id').' and type=\'EXPORT\'')){
					require_once 'forms/edit.php';
					$this->add_form(new EditHKWarehouseInvoiceForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'delete':
				if(User::can_delete(false,ANY_CATEGORY)){
					if(Url::get('id') and DB::exists('SELECT id FROM hk_wh_invoice WHERE id = '.Url::iget('id').'')){
						DB::delete('hk_wh_invoice_detail','invoice_id= '.Url::iget('id'));
						DB::delete('hk_wh_invoice','id= '.Url::iget('id'));
						Url::redirect('notice',array('cmd'=>'delete','href'=>'?page='.Url::get('page').'&type='.Url::get('type')));
					}
					if(Url::get('item_check_box')){
						$arr = Url::get('item_check_box');	
						for($i=0;$i<sizeof($arr);$i++){
							DB::delete('hk_wh_invoice','id = '.$arr[$i]);
							DB::delete('hk_wh_invoice_detail','invoice_id= '.$arr[$i]);
						}
						Url::redirect('notice',array('cmd'=>'delete','href'=>'?page='.Url::get('page').'&type='.Url::get('type')));
					}else{
						Url::redirect_current();
					}
				}else{
					Url::access_denied();
				}
				break;
			default:
				if(User::can_view(false,ANY_CATEGORY)){
					require_once 'forms/list.php';
					$this->add_form(new ListHKWarehouseInvoiceForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}	
}
?>