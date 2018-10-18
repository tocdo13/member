<?php
class MonthlyRoomRevenueChartReportForm extends Form{
	function MonthlyRoomRevenueChartReportForm(){
		Form::Form('MonthlyRoomRevenueChartReportForm');
		$this->link_js('packages/core/includes/js/jquery/chart/highcharts.src.js');
		$this->link_js('packages/core/includes/js/jquery/chart/exporting.js');
	}
	function get_amout_room($month_from,$month_end,$year_from,$year_end){
		$sql1='	   
				SELECT 
					rs.change_price, rs.reservation_room_id, 
					rs.id,
					rr.tax_rate, rr.service_rate, 
					to_char(rs.in_date,\'MM\') as month, 
					to_char(rs.in_date,\'YYYY\') as year,
					rr.arrival_time,
					rr.departure_time,
					rr.price
				FROM room_status rs 
					INNER JOIN reservation_room rr ON rr.id = rs.reservation_room_id 
					INNER JOIN reservation r on rr.reservation_id = r.id
				WHERE rs.status =\'OCCUPIED\'
					AND to_char(rs.in_date,\'MM\') >= '.$month_from.'
					AND to_char(rs.in_date,\'MM\') <='.$month_end.'
					AND  to_char(rs.in_date,\'YYYY\') >= '.$year_from.'
					AND to_char(rs.in_date,\'YYYY\') <= '.$year_end.'';
		$room_totals = DB::fetch_all($sql1);
		$amount_room_month = array();
		foreach($room_totals as $key=>$value){
			$service_room = 0;
			$pay_room = 0;
			$tax_room = 0;
			$net = 0;
			if($value['arrival_time'] == $value['departure_time']){
				if(!$value['change_price']){
					$value['change_price'] =$value['price'];
				}
			}
			$service_room = ($value['change_price'] * $value['service_rate'])/100;
			$pay_room  = $value['change_price'] + $service_room ;	
			$tax_room = ($pay_room * $value['tax_rate'])/100;
			$net = $pay_room + $tax_room ;
			if(!isset($amount_room_month[$value['month'].$value['year']]['amount_total'])){
				$amount_room_month[$value['month'].$value['year']]['id']=$value['month'].$value['year'];
				$amount_room_month[$value['month'].$value['year']]['amount_total'] = $net/1.155;
				$amount_room_month[$value['month'].$value['year']]['month']=$value['month'];
				$amount_room_month[$value['month'].$value['year']]['year']=$value['year'];
			}else{
				$amount_room_month[$value['month'].$value['year']]['amount_total'] += $net;
			}
		}
		return $amount_room_month;
	}
	function draw(){	
			$from_month = (Url::get('month_from'))?Url::get('month_from'):1;
			$to_month = (Url::get('month_to'))?Url::get('month_to'):date('m');
			$from_year = (Url::get('year_from'))?Url::get('year_from'):date('Y');
			$to_year = (Url::get('year_to'))?Url::get('year_to'):date('Y');
			$amount_room = $this->get_amout_room($from_month,$to_month,$from_year,$to_year);
		 if($from_year<$to_year){
			$end = ($to_year - $from_year)*12 + ($to_month - $from_month )+1;
		 }else{
			 $end = $to_month ;
		 }
		 $items = array();
		 for($i=$from_month; $i <=$end; $i++){
			 $items[$i]['id'] = $i;
			 $items[$i]['amount'] = 0;
			 if($i <= 12){
			 	$items[$i]['month']=$i;
				$items[$i]['year']=$from_year;
			 }else{
				 if(($i%12 !=0)){
					 $items[$i]['month'] = ($i%12);
				 }else{
					 $items[$i]['month'] = 12;
				 }
				 $items[$i]['year'] = $from_year +floor(($i-1)/12);
			 }
			 foreach($amount_room as $key=>$value){
				 if(($value['month'] == $items[$i]['month']) && ($value['year'] == $items[$i]['year'])){
						$items[$i]['amount'] += $value['amount_total'];
				 }
			 }
		 }
		 $this->parse_layout('report',array('items'=>String::array2js($items)));
	}
}
?>