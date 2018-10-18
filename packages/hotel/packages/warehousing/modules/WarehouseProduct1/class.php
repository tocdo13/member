<?php 
class WarehouseProduct extends Module
{
	public static $item = array();
	function WarehouseProduct($row)
	{
		Module::Module($row);
		switch (Url::get('cmd')){
			case 'add':
				if(User::can_add(false,ANY_CATEGORY)){
					require_once 'forms/edit.php';
					$this->add_form(new EditWarehouseProductForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'edit':
				if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and WarehouseProduct::$item = DB::select('wh_product','id = \''.Url::sget('id').'\'')){
					require_once 'forms/edit.php';
					$this->add_form(new EditWarehouseProductForm());
				}else{
					Url::access_denied();
				}
				break;
			case 'view':
				if(User::can_view(false,ANY_CATEGORY) and Url::get('id') and WarehouseProduct::$item = DB::select('wh_product','id = \''.Url::sget('id').'\'')){
					require_once 'forms/view.php';
					$this->add_form(new ViewWarehouseProductForm());
				}else{
					Url::access_denied();
				}
				break;	
			case 'delete':
				if(User::can_delete(false,ANY_CATEGORY)){
					if(Url::get('id') and $row = DB::fetch('SELECT * FROM wh_product WHERE id = \''.Url::sget('id').'\'')){
						$this->delete($row['id']);
						Url::redirect_current();
					}
					if(Url::get('item_check_box')){
						$arr = Url::get('item_check_box');	
						for($i=0;$i<sizeof($arr);$i++){
							$this->delete($arr[$i]);
						}
						//Url::redirect('notice',array('cmd'=>'delete','href'=>'?page='.Url::get('page')));
						Url::redirect_current();
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
					$this->add_form(new ListWarehouseProductForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}
	function delete($id){
		if($reservations=DB::fetch_all('SELECT wh_invoice_detail.*,wh_invoice.type from wh_invoice_detail inner join wh_invoice on wh_invoice.id = wh_invoice_detail.invoice_id WHERE wh_invoice_detail.product_id = \''.$id.'\'')){
			echo '<LINK rel=\'stylesheet\' href=\'packages/core/skins/default/css/global.css\' type=\'text/css\'>
				<LINK rel=\'stylesheet\' href=\'packages/hotel/skins/default/css/style.css\' type=\'text/css\'>
			';
			echo '<div class=\'warning-box\'>';
			echo '<div class=\'title\'>'.Portal::language('delete_confirm').'</div>';
			echo '<div class=\'content\'><h3>'.Portal::language('invoice_use_this_product').'</h3>';
			echo '<ul>';
			foreach($reservations as $key=>$value){
				echo '<li><a target=\'_blank\' href=\''.Url::build('warehouse_invoice',array('id'=>$value['invoice_id'],'cmd'=>'view','type'=>$value['type'])).'\'>'.Portal::language('view').'</a></li>';
			}
			echo '</ul>';
			echo '<div class=\'notice\'>'.Portal::language('are_you_sure').'? <a href=\''.Url::build_all().'&action=continue\'><strong>'.Portal::language('sure').'</strong></a> | <a href=\''.Url::build_current().'\'>'.Portal::language('back').'</a></div>';
			echo '</div>';
			echo '</div>';
			if(Url::get('action')!='continue'){
				exit();
			}
			DB::update('wh_invoice_detail',array('product_id'=>0),'product_id=\''.$id.'\'');
		}
		DB::delete('wh_product','id = \''.$id.'\'');
		//Url::redirect('notice',array('cmd'=>'delete','href'=>'?page='.Url::get('page')));
	}	
}
?>