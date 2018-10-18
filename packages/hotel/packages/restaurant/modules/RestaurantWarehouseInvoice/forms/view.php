<?php
class ViewRestaurantWarehouseInvoiceForm extends Form
{
	function ViewRestaurantWarehouseInvoiceForm()
	{
		Form::Form('ViewRestaurantWarehouseInvoiceForm');
		$this->link_css(Portal::template('warehouse').'/css/invoice.css');
	}
	function draw()
	{
		require_once 'packages/core/includes/utils/currency.php';
		$this->map = array();
		$this->map['total_debit'] = 0;
		$this->map['total'] = 0;
		$this->map['total_discount'] = 0;
		$item = RestaurantWarehouseInvoice::$item;
		if($item){
			if(Url::get('type')=='IMPORT'){
				$cond = 'warehouse_invoice.create_date <= \''.$item['create_date'].'\' AND warehouse_invoice.supplier_id = '.$item['supplier_id'].'';
				$group_by = 'warehouse_invoice.supplier_id';
				$this->map['title'] = Portal::language('import_invoice');				
			}else{
				$cond = 'warehouse_invoice.create_date <= \''.$item['create_date'].'\' AND warehouse_invoice.warehouse_id = '.$item['warehouse_id'].'';
				$group_by = 'warehouse_invoice.warehouse_id';
				$this->map['title'] = Portal::language('export_invoice');
			}
			$sql1 = '
				SELECT 
					warehouse_invoice.id AS id,
					SUM(res_wh_invoice_detail.price*res_wh_invoice_detail.num) as total_amount
				FROM
					res_wh_invoice_detail
					INNER JOIN warehouse_invoice ON warehouse_invoice.id = res_wh_invoice_detail.invoice_id
				WHERE
					'.$cond.'
				GROUP BY 
					'.$group_by.'
			';
			$total_amount = DB::fetch($sql1,'total_amount');
			$this->map['supplier_name'] = DB::fetch('SELECT id,name FROM customer WHERE id=\''.$item['supplier_id'].'\'','name');
			$this->map['warehouse_name'] = DB::fetch('SELECT id,name FROM warehouse WHERE id=\''.$item['warehouse_id'].'\'','name');
			$this->map['staff_name'] = DB::fetch('SELECT id,name_1 FROM party WHERE user_id=\''.$item['user_id'].'\'','name_1');
			$item['create_date'] = Date_Time::convert_orc_date_to_date($item['create_date'],'/');
			$arr = explode('/',$item['create_date']);
			$this->map['year'] = $arr[2];
			$this->map['month'] = $arr[1];
			$this->map['day'] = $arr[0];
			$item['create_date'] = Date_Time::to_common_date($item['create_date']);
			$products = DB::fetch_all('
				SELECT
					res_wh_invoice_detail.*,res_wh_invoice_detail.price*res_wh_invoice_detail.num as payment_price,wh_product.name_'.Portal::language().' as name
				FROM
					res_wh_invoice_detail
					INNER JOIN wh_product ON wh_product.id = res_wh_invoice_detail.product_id
				WHERE
					res_wh_invoice_detail.invoice_id=\''.$item['id'].'\'
			'
			);
			$i=0;
			foreach($products as $k=>$v){
				$products[$k]['i'] = ++$i;
				$payment_amount = $v['price']*$v['num'];
				$this->map['total'] += $payment_amount;
				$products[$k]['payment_amount'] = System::display_number_report($payment_amount);
				$products[$k]['price'] = System::display_number_report($v['price']);
				$products[$k]['number'] = number_format($v['num']);
				$products[$k]['payment_price'] = System::display_number_report($v['payment_price']);
			}
			$this->map['products'] = $products;
		}
		$currency = DB::select('currency','id=\'VND\'');
		$this->map['total_by_letter'] = currency_to_text($this->map['total']).' '.$currency['name'];
		$this->map['total'] = System::display_number_report($this->map['total']);
		$this->map += $item;
		$this->parse_layout('view',$this->map);
	}	
}
?>