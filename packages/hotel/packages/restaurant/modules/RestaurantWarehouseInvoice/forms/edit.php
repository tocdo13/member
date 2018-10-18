<?php
class EditRestaurantWarehouseInvoiceForm extends Form
{
	function EditRestaurantWarehouseInvoiceForm()
	{
		Form::Form('EditRestaurantWarehouseInvoiceForm');
		$this->link_css(Portal::template('warehousing').'/css/invoice.css');
		$this->link_css('packages/hotel/skins/default/css/suggestion.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		if(Url::get('cmd')=='add'){
			$this->add('bill_number',new UniqueType(true,'invalid_bill_number','res_wh_invoice','bill_number'));
		}
		$this->add('create_date',new DateType(true,'invalid_create_date'));
		$this->add('receiver_name',new TextType(false,'invalid_receiver_name',0,255));
		$this->add('wh_product.product_id',new TextType(true,'miss_code',0,50));
		$this->add('wh_product.num',new FloatType(true,'invalid_product_number',0,9999));
	}
	function on_submit(){
		if($this->check()){
			if(isset($_REQUEST['mi_product_group']))
			{
				if(Url::get('type')=='EXPORT'){
					$items = RestaurantWarehouseInvoiceDB::get_store_remain();
					$error = false;
					foreach($_REQUEST['mi_product_group'] as $id=>$value)
					{
						if(isset($items[$value['product_id']]['remain_number']) and $items[$value['product_id']]['remain_number']<str_replace(',','',$value['num']))
						{
							if($items[$value['product_id']]['remain_number']==0)
							{
								$this->error('num_'.$id,Portal::language('product').' '.$value['product_id'].' '.Portal::language('is_empty'));
							}
							else
							{
								$this->error('num_'.$id,Portal::language('You_only_export').' '.$value['product_id'].' '.Portal::language('is_smaller').' '.$items[$value['product_id']]['remain_number']);
							}
							$error = true;
						}
					}
					if($error)
					{
						return;
					}
				}
				$array = array(
					'bill_number',
					'type',
					'supplier_id',
					'deliver_name',
					'note',
					'receiver_name',
					'total_amount'=>str_replace(',','',Url::get('total_amount')),
					'create_date'=>Date_Time::to_orc_date(Url::get('create_date'))
				);
				if(Url::get('cmd')=='edit'){
					$id = Url::iget('id');
					DB::update('res_wh_invoice',$array+array('last_modify_user_id'=>Session::get('user_id'),'last_modify_time'=>time()),'id='.Url::iget('id'));
				}else{
					$id = DB::insert('res_wh_invoice',$array+array('user_id'=>Session::get('user_id'),'time'=>time()));
				}
				if(URl::get('group_deleted_ids'))
				{
					$group_deleted_ids = explode(',',URl::get('group_deleted_ids'));
					foreach($group_deleted_ids as $delete_id)
					{
						DB::delete_id('res_wh_invoice_detail',$delete_id);
					}
				}	
				if(isset($_REQUEST['mi_product_group']))
				{	
					foreach($_REQUEST['mi_product_group'] as $key=>$record)
					{
						$record['price']=str_replace(',','',$record['price']);
						$record['num']=str_replace(',','',$record['num']);
						unset($record['payment_price']);
						unset($record['name']);
						unset($record['unit']);
						$empty = true;
						foreach($record as $record_value)
						{
							if($record_value)
							{
								$empty = false;
							}
						}
						if(!$empty)
						{
							$record['invoice_id'] = $id;
							if($record['id'])
							{
								DB::update('res_wh_invoice_detail',$record,'id=\''.$record['id'].'\'');
							}
							else
							{
								unset($record['id']);
								if(DB::exists('SELECT id FROM wh_product WHERE id=\''.$record['product_id'].'\'')){
									DB::insert('res_wh_invoice_detail',$record);
								}
							}
						}
					}
				}
			}
			else
			{
				$this->error('product_code','miss_product_code');
				return;
			}
			Url::redirect_current(array('type'));
		}
	}
	function draw()
	{
		$this->map = array();
		$item = RestaurantWarehouseInvoice::$item;
		if($item){
			$item['create_date'] = str_replace('-','/',Date_Time::convert_orc_date_to_date($item['create_date']));
			$item['total_amount'] = number_format($item['total_amount']);
			//$item['create_date'] = Date_Time::to_common_date($item['create_date']);
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[$key] = $value;
				}
			}
			if(!isset($_REQUEST['mi_product_group']))
			{
				$sql = '
					SELECT
						res_wh_invoice_detail.*,
						(res_wh_invoice_detail.price*res_wh_invoice_detail.num) as payment_price,
						wh_product.name_'.Portal::language().' as name,
						unit.name_'.Portal::language().' as unit
					FROM
						res_wh_invoice_detail
						INNER JOIN wh_product ON wh_product.id = res_wh_invoice_detail.product_id
						INNER JOIN unit ON unit.id = wh_product.unit_id
					WHERE
						res_wh_invoice_detail.invoice_id=\''.$item['id'].'\'
				';
				$mi_product_group = DB::fetch_all($sql);
				foreach($mi_product_group as $k=>$v){
					$mi_product_group[$k]['price'] = System::display_number_report($v['price']);
					$mi_product_group[$k]['number'] = number_format($v['num']);
					$mi_product_group[$k]['payment_price'] = System::display_number_report($v['payment_price']);
				}
				$_REQUEST['mi_product_group'] = $mi_product_group;
			} 
		}else{
			if(!Url::get('create_date')){
				$_REQUEST['create_date'] = date('d/m/Y',time());
			}
			if(!Url::get('bill_number')){
				$lastest_item = DB::fetch('SELECT id,bill_number FROM res_wh_invoice where type=\''.Url::get('type').'\' ORDER BY bill_number DESC');
				if(Url::get('type')=='IMPORT')
				{
					$total = str_replace('PN','',$lastest_item['bill_number'])+1;					
					$_REQUEST['bill_number'] = 'PN'.$total;
				}
				else if(Url::get('type')=='EXPORT')
				{
					$total = str_replace('PX','',$lastest_item['bill_number'])+1;
					$_REQUEST['bill_number'] = 'PX'.$total;
				}
			}
		}
		$this->map['supplier_id_list'] = array(''=>Portal::language('no_supplier'))+String::get_list(DB::select_all('customer','group_id=\'SUPPLIER\''));
		$this->map['warehouse_id_list'] = String::get_list(DB::select_all('warehouse',IDStructure::child_cond(ID_ROOT,1),'structure_id'));
		$this->map['title'] = (Url::get('cmd')=='add')?((Url::get('type')=='IMPORT')?Portal::language('add_import_bill'):Portal::language('add_export_bill')):((Url::get('type')=='IMPORT')?Portal::language('edit_import_bill'):Portal::language('edit_export_bill'));
		$this->map['products'] = DB::fetch_all('SELECT wh_product.id,wh_product.name_'.Portal::language().' as name,unit.name_'.Portal::language().' as unit FROM wh_product INNER JOIN unit ON unit.id = wh_product.unit_id');
		$this->parse_layout('edit',$this->map);
	}	
}
?>