<?php
class ViewSingleReservationForm extends Form
{
	function ViewSingleReservationForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
	}
	function draw()
	{
		$this->map = array();
		$item = SingleReservation::$item;
		if($item){
			$item['total_amount'] = System::display_number_report($item['total_amount']);
			$item['extra_amount'] = System::display_number_report($item['extra_amount']);
			$item['arrival_time'] = Date_Time::convert_orc_date_to_date($item['arrival_time'],'/');
			$item['departure_time'] = Date_Time::convert_orc_date_to_date($item['departure_time'],'/');
			$item['customer_name'] = DB::fetch('SELECT id,name FROM customer WHERE id = '.$item['company_id'].'','name');
			$this->map += $item;
		}
		$this->map['title'] = Portal::language('view_tour_detail');
		$this->parse_layout('view',$this->map);
	}	
}
?>