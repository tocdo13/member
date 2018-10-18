<?php 
class RestaurantWarehouseInvoice extends Module
{
	public static $item = array();
	function RestaurantWarehouseInvoice($row)
	{
		require_once 'db.php';
		Module::Module($row);
		if(!Url::get('type')){
			Url::redirect_current(array('type'=>'IMPORT'));
		}
		switch (Url::get('cmd')){
			case 'view':
				if(User::can_view(false,ANY_CATEGORY)  and Url::get('id') and RestaurantWarehouseInvoice::$item = DB::select('res_wh_invoice','id ='.Url::iget('id'))){
					require_once 'forms/view.php';
					$this->add_form(new ViewRestaurantWarehouseInvoiceForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'add':
				if(User::can_add(false,ANY_CATEGORY)){
					require_once 'forms/edit.php';
					$this->add_form(new EditRestaurantWarehouseInvoiceForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and RestaurantWarehouseInvoice::$item = DB::select('res_wh_invoice','id ='.Url::iget('id').' and type=\'EXPORT\'')){
					require_once 'forms/edit.php';
					$this->add_form(new EditRestaurantWarehouseInvoiceForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'delete':
				if(User::can_delete(false,ANY_CATEGORY)){
					if(Url::get('id') and DB::exists('SELECT id FROM res_wh_invoice WHERE id = '.Url::iget('id').'')){
						DB::delete('res_wh_invoice_detail','invoice_id= '.Url::iget('id'));
						DB::delete('res_wh_invoice','id= '.Url::iget('id'));
						Url::redirect('notice',array('cmd'=>'delete','href'=>'?page='.Url::get('page').'&type='.Url::get('type')));
					}
					if(Url::get('item_check_box')){
						$arr = Url::get('item_check_box');	
						for($i=0;$i<sizeof($arr);$i++){
							DB::delete('res_wh_invoice','id = '.$arr[$i]);
							DB::delete('res_wh_invoice_detail','invoice_id= '.$arr[$i]);
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
					$this->add_form(new ListRestaurantWarehouseInvoiceForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}	
}
?>