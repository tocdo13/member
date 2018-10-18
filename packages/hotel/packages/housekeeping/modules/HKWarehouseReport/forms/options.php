<?php
class HKWarehouseReportOptionsForm extends Form
{
	function HKWarehouseReportOptionsForm()
	{
		Form::Form('HKWarehouseReportOptionsForm');
		$this->link_css(Portal::template('hotel').'/css/report.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_css('packages/hotel/skins/default/css/suggestion.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');		
		$this->add('date_from',new DateType(true,'invalid_date_from'));
		$this->add('date_to',new DateType(true,'invalid_date_to',0,255));
	}
	function draw()
	{
		$this->map = array();
		if(Url::get('warehouse_id')){
			$this->map['warehouse'] = DB::fetch('select name from warehouse where id = '.Url::iget('warehouse_id').'','name');
		}else{
			$this->map['warehouse'] = Portal::language('All');
		}
		$this->map['total_payment'] = 0;
		$this->map['title'] = Portal::language('Report_options');
		if(Url::get('date_from')){
			$this->map['date_from'] = Url::get('date_from');
		}
		if(Url::get('date_to')){
			$this->map['date_to'] = Url::get('date_to');
		}
		$this->map['year'] = date('Y',time());
		$this->map['month'] = date('m',time());
		$this->map['day'] = date('d',time());
		$layout = 'options';
		if(Url::get('store_remain')){
			$layout = 'store_remain';
			$this->map['title'] = Portal::language('store_remain_report');
			$this->map['products'] = $this->get_store_remain_products();
		}elseif(Url::get('store_card')){
			$layout = 'store_card';
			$this->map['start_remain'] = 0;
			$this->map['end_remain'] = 0;
			$this->map['import_total'] = 0;
			$this->map['export_total'] = 0;
			$this->map['have_item'] = false;
			$this->map['products'] = $this->get_store_card();
			if(!$this->map['have_item']){
				echo '<div class=\'notice\'>'.Portal::language('has_no_item').'</div>';
				exit();
			}
		}elseif(Url::get('warehouse_export')){
			$layout = 'warehouse_export';
			$this->map['title'] = Portal::language('warehouse_export_report');
			$this->map['products'] = $this->get_warehouse_export();
		}
		$this->map['product_arr'] = DB::fetch_all('SELECT id,name_'.Portal::language().' as name FROM wh_product');
		$this->map['total_payment'] = number_format($this->map['total_payment']);
		$this->map['supplier_id_list'] = array(''=>Portal::language('all'))+String::get_list(DB::select_all('customer'));
		$this->map['warehouse_id_list'] = array(''=>'--------'.Portal::language('all').'---------')+String::get_list(DB::select_all('warehouse',IDStructure::child_cond(ID_ROOT,1),'structure_id'));
		if((Url::get('date_from') and Url::get('date_to')) or (!Url::get('store_remain') and !Url::get('store_card'))){
			$this->parse_layout($layout,$this->map);
		}else{
			echo '<div class=\'notice\'>'.Portal::language('has_no_date_duration').'</div>';
		}
	}
	function get_store_card(){
		if(Url::get('code') and $row = DB::select('wh_product','id = \''.Url::get('code').'\'')){
			$this->map['have_item'] =  true;
			$this->map['code'] = $row['id'];
			$this->map['name'] = $row['name_'.Portal::language()];
			$old_cond = 'hk_wh_invoice_detail.product_id = \''.$row['id'].'\'
					'.(Url::get('date_from')?' AND hk_wh_invoice.create_date <\''.Date_Time::to_orc_date(Url::get('date_from')).'\'':'').'
			';		
			$sql = '
				SELECT
					hk_wh_invoice_detail.id,hk_wh_invoice_detail.product_id,hk_wh_invoice_detail.num,hk_wh_invoice.type
				FROM
					hk_wh_invoice_detail
					INNER JOIN hk_wh_invoice ON hk_wh_invoice.id= hk_wh_invoice_detail.invoice_id
				WHERE
					'.$old_cond.'
				ORDER BY
					hk_wh_invoice_detail.product_id	
			';
			$items = DB::fetch_all($sql);
			$old_items = array();
			foreach($items as $key=>$value){
				$product_id = $value['product_id'];
				if($value['type']=='IMPORT'){
					if(isset($old_items[$product_id]['import_number'])){
						$old_items[$product_id]['import_number'] += $value['num'];
					}else{
						$old_items[$product_id]['import_number'] = $value['num'];
					}
				}else{
					if(isset($old_items[$product_id]['export_number'])){
						$old_items[$product_id]['export_number'] += $value['num'];
					}else{
						$old_items[$product_id]['export_number'] = $value['num'];
					}
				}
			}
			$sql = '
			SELECT
					wh_product.id,wh_product.start_term_quantity
				FROM
					wh_product
			';
			$products = DB::fetch_all($sql);
			if(isset($products[$row['id']])){
				$this->map['start_remain'] = 0;
			}
			$cond = '
				'.(Url::get('date_from')?' AND hk_wh_invoice.create_date >=\''.Date_Time::to_orc_date(Url::get('date_from')).'\'':'').'
				'.(Url::get('date_to')?' AND hk_wh_invoice.create_date <=\''.Date_Time::to_orc_date(Url::get('date_to')).'\'':'').'
				
			';
			$sql = '
				SELECT
					hk_wh_invoice.*,
					DECODE(hk_wh_invoice.type,\'IMPORT\',hk_wh_invoice.bill_number,\'\') AS import_invoice_code,
					DECODE(hk_wh_invoice.type,\'EXPORT\',hk_wh_invoice.bill_number,\'\') AS export_invoice_code,
					DECODE(hk_wh_invoice.type,\'IMPORT\',hk_wh_invoice_detail.num,0) AS import_number,
					DECODE(hk_wh_invoice.type,\'EXPORT\',hk_wh_invoice_detail.num,0) AS export_number
				FROM
					hk_wh_invoice
					INNER JOIN hk_wh_invoice_detail ON hk_wh_invoice_detail.invoice_id = hk_wh_invoice.id
				WHERE
					hk_wh_invoice_detail.product_id = \''.$row['id'].'\' '.$cond.'
				ORDER BY
					hk_wh_invoice.create_date,hk_wh_invoice.id
			';
			$items = DB::fetch_all($sql);
			$remain = $this->map['start_remain'];
			foreach($items as $key=>$value){
				$items[$key]['create_date'] = Date_Time::convert_orc_date_to_date($value['create_date'],'/');
				$this->map['end_remain'] += $value['import_number'] - $value['export_number'];
				$remain = $remain + $value['import_number'] - $value['export_number'].'<br />';
				$items[$key]['remain'] = $remain;
				$this->map['import_total'] += $value['import_number'];
				$this->map['export_total'] += $value['export_number'];
			}
			if(isset($old_items[$row['id']])){
				$this->map['start_remain'] += $old_items[$row['id']]['import_number']-$old_items[$row['id']]['export_number'];
			}
			$this->map['end_remain'] += $this->map['start_remain'];
			return $items;
		}else{
			
		}
	}
	function get_store_remain_products(){
		$cond = '1=1
				'.(Url::get('date_from')?' AND hk_wh_invoice.create_date >=\''.Date_Time::to_orc_date(Url::get('date_from')).'\'':'').'
				'.(Url::get('date_to')?' AND hk_wh_invoice.create_date <=\''.Date_Time::to_orc_date(Url::get('date_to')).'\'':'').'
		';
		//				'.(Url::get('warehouse_id')?' AND '.IDStructure::child_cond(DB::structure_id('warehouse',Url::iget('warehouse_id'))).'':'').'
		$old_cond = '1=1
				'.(Url::get('date_from')?' AND hk_wh_invoice.create_date <\''.Date_Time::to_orc_date(Url::get('date_from')).'\'':'').'
		';		
		$sql = '
			SELECT
				hk_wh_invoice_detail.id,hk_wh_invoice_detail.product_id,hk_wh_invoice_detail.num,hk_wh_invoice.type
			FROM
				hk_wh_invoice_detail
				INNER JOIN hk_wh_invoice ON hk_wh_invoice.id= hk_wh_invoice_detail.invoice_id
			WHERE
				'.$old_cond.'
			ORDER BY
				hk_wh_invoice_detail.product_id	
		';
		$items = DB::fetch_all($sql);
		$old_items = array();
		if(is_array($items))
		{
			foreach($items as $key=>$value){
				$product_id = $value['product_id'];
				if(isset($old_items[$product_id])){
					if($value['type']=='IMPORT'){
						$old_items[$product_id]['import_number'] += $value['num'];
					}else{
						if(isset($old_items[$product_id]['export_number']))
						{
							$old_items[$product_id]['export_number'] += $value['num'];
						}
						else
						{
							$old_items[$product_id]['export_number'] = $value['num'];
						}
					}
				}else{
					if($value['type']=='IMPORT'){
						$old_items[$product_id]['import_number'] = $value['num'];
					}else{
						$old_items[$product_id]['export_number'] = $value['num'];
					}
				}
			}
		}
		$sql = '
			SELECT
				hk_wh_invoice_detail.id,hk_wh_invoice_detail.product_id,wh_product.name_'.Portal::language().' as name,unit.name_'.Portal::language().' as unit,hk_wh_invoice_detail.num,hk_wh_invoice.type,wh_product.start_term_quantity
			FROM
				hk_wh_invoice_detail
				INNER JOIN wh_product ON wh_product.id = hk_wh_invoice_detail.product_id
				INNER JOIN unit ON unit.id = wh_product.unit_id
				INNER JOIN hk_wh_invoice ON hk_wh_invoice.id= hk_wh_invoice_detail.invoice_id
				LEFT OUTER JOIN  warehouse ON warehouse.id = hk_wh_invoice.warehouse_id
			WHERE
				'.$cond.'
			ORDER BY
				hk_wh_invoice_detail.product_id	
		';
		$items = DB::fetch_all($sql);
		$sql = '
			SELECT
				wh_product.id,wh_product.id as product_id,
				wh_product.name_'.Portal::language().' as name,
				unit.name_'.Portal::language().' as unit,wh_product.start_term_quantity,
				wh_product.start_term_quantity as remain_number,0 as import_number,0 as export_number
			FROM
				wh_product
				INNER JOIN unit ON unit.id = wh_product.unit_id	
			ORDER BY
				wh_product.category_id,wh_product.id
		';
		$products = DB::fetch_all($sql);
		$i = 0;
		foreach($products as $key=>$value){
			$products[$key]['start_term_quantity'] = 0;
			$products[$key]['remain_number'] = 0;
		}
		$new_products = $products;//array();
		foreach($items as $key=>$value){
			$product_id = $value['product_id'];
			if(isset($new_products[$product_id]['id'])){
				if($value['type']=='IMPORT'){
					$new_products[$product_id]['import_number'] += $value['num'];
				}else{
					$new_products[$product_id]['export_number'] += $value['num'];
				}
				$new_products[$product_id]['remain_number'] = $new_products[$product_id]['import_number'] - $new_products[$product_id]['export_number'];
				$new_products[$product_id]['remain_number'] += $new_products[$product_id]['start_term_quantity'];
			}else{
				$new_products[$product_id]['id'] = $product_id;
				$new_products[$product_id]['product_id'] = $product_id;
				$new_products[$product_id]['unit'] = $value['unit'];
				$new_products[$product_id]['name'] = $value['name'];
				$new_products[$product_id]['import_number'] = 0;
				$new_products[$product_id]['export_number'] = 0;
				if($value['type']=='IMPORT'){
					$new_products[$product_id]['import_number'] = $value['num'];
				}else{
					$new_products[$product_id]['export_number'] = $value['num'];
				}
				$new_products[$product_id]['remain_number'] = $new_products[$product_id]['import_number'] - $new_products[$product_id]['export_number'];
			}
		}
		foreach($new_products as $key=>$value){
			$product_id = $value['product_id'];
			if(isset($old_items[$product_id]['import_number'])){
				$new_products[$product_id]['start_term_quantity'] += $old_items[$product_id]['import_number'];
				$new_products[$product_id]['remain_number'] += $old_items[$product_id]['import_number'];
			}
			if(isset($old_items[$product_id]['export_number'])){
				$new_products[$product_id]['start_term_quantity'] -= $old_items[$product_id]['export_number'];
				$new_products[$product_id]['remain_number'] -= $old_items[$product_id]['export_number'];
			}
		}
		return $new_products;
	}	
	function get_warehouse_export(){
		$old_cond = ' hk_wh_invoice.type = \'EXPORT\' and hk_wh_invoice.warehouse_id = '.Url::get('warehouse_id').'
				'.(Url::get('date_from')?' AND hk_wh_invoice.create_date <\''.Date_Time::to_orc_date(Url::get('date_from')).'\'':'').'
		';		
		$sql = '
			SELECT
				hk_wh_invoice_detail.id,hk_wh_invoice_detail.product_id,hk_wh_invoice_detail.num,hk_wh_invoice.type
			FROM
				hk_wh_invoice_detail
				INNER JOIN hk_wh_invoice ON hk_wh_invoice.id= hk_wh_invoice_detail.invoice_id
			WHERE
				'.$old_cond.'
			ORDER BY
				hk_wh_invoice_detail.product_id	
		';
		$items = DB::fetch_all($sql);
		$old_items = array();
		foreach($items as $key=>$value){
			$product_id = $value['product_id'];
			if(isset($old_items[$product_id])){
				if(!isset($old_items[$product_id]['import_number'])){
					$old_items[$product_id]['import_number'] = 0;
				}
				if(!isset($old_items[$product_id]['export_number'])){
					$old_items[$product_id]['export_number'] = 0;
				}
				if($value['type']=='IMPORT'){
					$old_items[$product_id]['import_number'] += $value['num'];
				}else{
					$old_items[$product_id]['export_number'] += $value['num'];
				}
			}else{
				if($value['type']=='IMPORT'){
					$old_items[$product_id]['import_number'] = $value['num'];
				}else{
					$old_items[$product_id]['export_number'] = $value['num'];
				}
			}
		}
		$sql = '
		SELECT
				wh_product.id,wh_product.start_term_quantity,wh_product.start_term_quantity as start_remain
			FROM
				wh_product
		';
		$products = DB::fetch_all($sql);
		$cond = ' hk_wh_invoice.type = \'EXPORT\' and hk_wh_invoice.warehouse_id = '.Url::get('warehouse_id').'
			'.(Url::get('date_from')?' AND hk_wh_invoice.create_date >=\''.Date_Time::to_orc_date(Url::get('date_from')).'\'':'').'
			'.(Url::get('date_to')?' AND hk_wh_invoice.create_date <=\''.Date_Time::to_orc_date(Url::get('date_to')).'\'':'').'
			
		';
		$sql = '
			SELECT
				hk_wh_invoice.*,
				DECODE(hk_wh_invoice.type,\'EXPORT\',hk_wh_invoice.bill_number,\'\') AS export_invoice_code,
				DECODE(hk_wh_invoice.type,\'EXPORT\',hk_wh_invoice_detail.num,0) AS export_number
			FROM
				hk_wh_invoice
				INNER JOIN hk_wh_invoice_detail ON hk_wh_invoice_detail.invoice_id = hk_wh_invoice.id
			WHERE
				'.$cond.'
			ORDER BY
				hk_wh_invoice.create_date,hk_wh_invoice.id
		';
		$items = DB::fetch_all($sql);
		$this->map['export_total'] = 0;
		foreach($items as $key=>$value){
			$items[$key]['create_date'] = Date_Time::convert_orc_date_to_date($value['create_date'],'/');
			$this->map['export_total'] += $value['export_number'];
		}
		return $items;
	}	
}
?>