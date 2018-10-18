<?php
class RoomRevenueChartReportForm extends Form{
	function RoomRevenueChartReportForm(){
		Form::Form('RoomRevenueChartReportForm');
		$this->link_js('packages/core/includes/js/jquery/chart/highcharts.js');
		$this->link_js('packages/core/includes/js/jquery/chart/highcharts.src.js');
		$this->link_js('packages/core/includes/js/jquery/chart/excanvas.compiled.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function get_amout_room($date_from,$date_end){
		$sql1 = '
				SELECT 
					rs.change_price ,
					rs.reservation_room_id ,
					rr.tax_rate, 
					rr.service_rate,
					rs.in_date,
					rs.id,
					rr.arrival_time,
					rr.departure_time,
					rr.price
				FROM 
					room_status rs 
				INNER JOIN
					reservation_room rr ON rr.id = rs.reservation_room_id 
				INNER JOIN 
					reservation r on rr.reservation_id = r.id
				WHERE 
					rs.status =\'OCCUPIED\' 
					AND rs.in_date >= \''.$date_from.'\' 
					AND rs.in_date <= \''.$date_end.'\'
				 ';
		$room_totals = DB::fetch_all($sql1);
		$amount_room_days =array();
		/*
		$deposit= DB::fetch_all('
		 	SELECT 
				deposit as deposit,rr.id, deposit_date
			FROM 
				reservation_room rr 
			WHERE deposit_date <= \''.$date_end.'\'
			');
			$deposit_date= DB::fetch_all('
				SELECT 
					sum(deposit) as deposit,deposit_date as id
				FROM 
					reservation_room rr 
				WHERE 
					deposit_date <= \''.$date_end.'\' AND deposit_date >=\''.$date_from.'\'
				GROUP BY deposit_date
			');*/
			$tong_tru = 0;
		foreach($room_totals as $key=>$value){
			// tinh tien phong va phi dich phong thue cho tung phong rui cong thanh tog tien trong ngay
			$amount_room_days['id'] = $value['in_date'];
			$service_room = 0;
			$pay_room = 0;
			$tax_room = 0;
			$deposit_room = 0;
			$net=0;
			if($value['arrival_time'] == $value['departure_time']){
				if(!$value['change_price']){
					$value['change_price'] = $value['price'];
				}
			}
			$service_room =($value['change_price']  * $value['service_rate'])/100;
			// tien phong  + dv 1 phong
			$pay_room  = $value['change_price'] + $service_room ;	
			// tinh tien thue cua 1 phong 
			$tax_room = ($pay_room * $value['tax_rate'])/100;
			$net = $pay_room + $tax_room;	
			/*		
			foreach($deposit as $k_deposit=>$v_debopist){
				if($v_debopist['id'] == $value['reservation_room_id']){
					$date_deposit =Date_Time::convert_orc_date_to_date($v_debopist['deposit_date'],'/');
					$time_date_deposit=Date_Time::to_time($date_deposit);
					 $date_inday =Date_Time::convert_orc_date_to_date($value['in_date'],'/');
					$time_date_in_day =Date_Time::to_time($date_inday);
				if($time_date_deposit <= $time_date_in_day){
					  $deposit_room = $v_debopist['deposit'];
					  $tong_tru +=$v_debopist['deposit'];
					 unset($deposit[$k_deposit]);
				}	
			  }
			}*/
			$room_totals[$key]['room_total'] = $net - $deposit_room;
			if(!isset($amount_room_days[$value['in_date']]['amount_total'])){
				$amount_room_days[$value['in_date']]['amount_total'] = $room_totals[$key]['room_total'];
			}else{
				$amount_room_days[$value['in_date']]['amount_total'] += $room_totals[$key]['room_total'];
			}
			// cong tien deposit trong ngay
			/*
			if(isset($deposit_date[$value['in_date']])){
				$amount_room_days[$value['in_date']]['amount_total'] += $deposit_date[$value['in_date']]['deposit'];
				unset($deposit_date[$value['in_date']]);
			}*/	
		}
		return $amount_room_days;
	}
	function draw(){
		 $from_day =(Url::get('from_date'))?Date_Time::to_orc_date(Url::get('from_date')):'1-'.date('M').'-'.date('Y');
		 $end = date('t').'-'.date('M').'-'.date('Y');
		 $time_end =date('t').'/'.date('m').'/'.date('Y');
		 $end_day = (Url::get('to_date'))?Date_Time::to_orc_date(Url::get('to_date')):$end;
		 $time_from_day =(Url::get('from_date'))?Date_Time::to_time(Url::get('from_date')):(Date_Time::to_time('1'.'/'.date('m').'/'.date('Y')));
		 $time_to_day =(Url::get('to_date'))?Date_Time::to_time(Url::get('to_date')):(Date_Time::to_time($time_end));
		 $amount_room= $this->get_amout_room($from_day,$end_day);
		 $items= array();
		 $data=array();
		 $j=0;
		 for($i=$time_from_day ; $i<= $time_to_day; $i +=24*3600){ 
		 	$items[date('d/m/Y',$i)]['id']=date('d/m/Y',$i);
			$date_to_oracle = Date_Time::to_orc_date(date('d/m/y',$i));
			$items[date('d/m/Y',$i)]['room_amount']=0;
			$data[$j]['y']=0;
			$items[date('d/m/Y',$i)]['date']= date('d',$i);
			$items[date('d/m/Y',$i)]['CURRENCY']= HOTEL_CURRENCY?HOTEL_CURRENCY:'';
			if(isset($amount_room[$date_to_oracle])){
					if(HOTEL_CURRENCY =='VND'){
						 $items[date('d/m/Y',$i)]['room_amount'] = ($amount_room[$date_to_oracle]['amount_total']/1000);
					}else{
						 $items[date('d/m/Y',$i)]['room_amount'] =($amount_room[$date_to_oracle]['amount_total']);
					}
				}   
				$j++;
		   }
			$this->parse_layout('report',array('items'=>String::array2js($items)));
		}
}
?>