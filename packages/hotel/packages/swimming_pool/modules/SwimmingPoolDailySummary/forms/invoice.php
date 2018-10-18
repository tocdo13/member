<?php
class TennisInvoiceForm extends Form
{
	function TennisInvoiceForm()
	{
		Form::Form('TennisInvoiceForm');
		$this->link_css('packages/hotel/'.Portal::template('swimming_pool').'/css/style.css');
	}
	function draw()
	{
		$this->map = array(); 
		$this->map['swimming_pool_number'] = DB::fetch('SELECT NAME FROM swimming_pool WHERE ID = '.Url::iget('swimming_pool_id').'','name');
		$currency=DB::select('currency','id=\'VND\'');
		$exchange_rate=$currency['exchange'];
		$this->map['exchange_rate'] = $exchange_rate;
		$item = SwimmingPoolDailySummary::$item;
		if($item){
			if($item['hotel_reservation_room_id'] and $row = DB::fetch('select reservation_swimming_pool.id,swimming_pool.name from reservation_swimming_pool inner join swimming_pool on swimming_pool.id = reservation_swimming_pool.swimming_pool_id where reservation_swimming_pool.id = '.$item['hotel_reservation_room_id'].'')){
				$item['hotel_swimming_pool_number'] = $row['name'];
			}else{
				$item['hotel_swimming_pool_number'] = '';
			}
			$item['time_in'] = date('H:i',$item['time_in']);
			$item['time_out'] = date('H:i',$item['time_out']);
			{
				$sql = '
					SELECT
						swimming_pool_staff_pool.*,
						swimming_pool_staff.full_name
					FROM
						swimming_pool_staff_pool
						INNER JOIN swimming_pool_staff ON swimming_pool_staff.id = staff_id
					WHERE
						swimming_pool_staff_pool.reservation_pool_id=\''.$item['id'].'\'
				';
				$mi_staff_group = DB::fetch_all($sql);
				$this->map['staffs'] = $mi_staff_group;
			} 
			{
				$sql = '
					SELECT
						swimming_pool_product_consumed.*,(swimming_pool_product_consumed.price*swimming_pool_product_consumed.quantity) as amount,
						swimming_pool_product.name,swimming_pool_product.code
					FROM
						swimming_pool_product_consumed
						INNER JOIN swimming_pool_product ON swimming_pool_product.id = product_id
					WHERE
						swimming_pool_product_consumed.reservation_pool_id=\''.$item['id'].'\'
				';
				$mi_product_group = DB::fetch_all($sql);
				$i=1;
				foreach($mi_product_group as $key=>$value){
					$mi_product_group[$key]['no'] = $i++;
				}
				$this->map['products'] = $mi_product_group;
			} 
			{
				$sql = '
					SELECT
						swimming_pool_product_hired.*,(swimming_pool_product_hired.price*swimming_pool_product_hired.quantity) as amount,
						swimming_pool_product.name,swimming_pool_product.code
					FROM
						swimming_pool_product_hired
						INNER JOIN swimming_pool_product ON swimming_pool_product.id = product_id
					WHERE
						swimming_pool_product_hired.reservation_pool_id=\''.$item['id'].'\'
				';
				$mi_hire_product_group = DB::fetch_all($sql);
				$i=1;
				foreach($mi_hire_product_group as $key=>$value){
					$mi_hire_product_group[$key]['no'] = $i++;
				}
				$this->map['hired_products'] = $mi_hire_product_group;
			} 
		}
		$this->map += $item;
		$this->parse_layout('invoice',$this->map);
	}
}
?>