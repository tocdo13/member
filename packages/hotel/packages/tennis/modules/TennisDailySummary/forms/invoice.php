<?php
class TennisInvoiceForm extends Form
{
	function TennisInvoiceForm()
	{
		Form::Form('TennisInvoiceForm');
		$this->link_css('packages/hotel/'.Portal::template('tennis').'/css/style.css');
	}
	function draw()
	{
		$this->map = array(); 
		$this->map['court_number'] = DB::fetch('SELECT NAME FROM tennis_court WHERE ID = '.Url::iget('court_id').'','name');
		$currency=DB::select('currency','id=\'VND\'');
		$exchange_rate=$currency['exchange'];
		$this->map['exchange_rate'] = $exchange_rate;
		$item = TennisDailySummary::$item;
		if($item){
			if($item['hotel_reservation_room_id'] and $row = DB::fetch('select reservation_court.id,court.name from reservation_court inner join court on court.id = reservation_court.court_id where reservation_court.id = '.$item['hotel_reservation_room_id'].'')){
				$item['hotel_court_number'] = $row['name'];
			}else{
				$item['hotel_court_number'] = '';
			}
			$item['time_in'] = date('H:i',$item['time_in']);
			$item['time_out'] = date('H:i',$item['time_out']);
			{
				$sql = '
					SELECT
						tennis_staff_court.*,
						tennis_staff.full_name
					FROM
						tennis_staff_court
						INNER JOIN tennis_staff ON tennis_staff.id = staff_id
					WHERE
						tennis_staff_court.reservation_court_id=\''.$item['id'].'\'
				';
				$mi_staff_group = DB::fetch_all($sql);
				$this->map['staffs'] = $mi_staff_group;
			} 
			{
				$sql = '
					SELECT
						tennis_product_consumed.*,(tennis_product_consumed.price*tennis_product_consumed.quantity) as amount,
						tennis_product.name,tennis_product.code
					FROM
						tennis_product_consumed
						INNER JOIN tennis_product ON tennis_product.id = product_id
					WHERE
						tennis_product_consumed.reservation_court_id=\''.$item['id'].'\'
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
						tennis_product_hired.*,(tennis_product_hired.price*tennis_product_hired.quantity) as amount,
						tennis_product.name,tennis_product.code
					FROM
						tennis_product_hired
						INNER JOIN tennis_product ON tennis_product.id = product_id
					WHERE
						tennis_product_hired.reservation_court_id=\''.$item['id'].'\'
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