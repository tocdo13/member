<?php
class MonthlyRevenueChartForm extends Form{
	function MonthlyRevenueChartForm(){
		Form::Form('MonthlyRevenueChartForm');
		$this->link_js('packages/core/includes/js/jquery/chart/highcharts.js');
		$this->link_js('packages/core/includes/js/jquery/chart/highcharts.src.js');
		$this->link_js('packages/core/includes/js/jquery/chart/excanvas.compiled.js');
	}
	function get_phone($time_in,$time_out,$room_id){
		$sql_p = '
					SELECT
						SUM(telephone_report_daily.price) AS total
					FROM
						telephone_report_daily
						inner join telephone_number on telephone_number.phone_number = telephone_report_daily.phone_number_id
					WHERE
						telephone_report_daily.hdate >= '.$time_in.' and telephone_report_daily.hdate <= '.$time_out.'
						AND telephone_number.room_id = '.$room_id.'
					GROUP BY
						telephone_number.room_id ';
			$phone = DB::fetch($sql_p,'total');
			return $phone;
	}
	function get_revenue_room($year){
		$cond ='rr.status =\'CHECKOUT\' and  to_char (departure_time,\'YYYY\') = \''.$year.'\'';
		$sql='
			Select 
							rs.id, 
							rs.reservation_id,
							rs.room_id,
							rs.in_date,
							rs.change_price,
							rs.reservation_room_id,
							rr.price,
							rr.total_amount,
							rr.exchange_rate,
							rr.time_in,
							rr.time_out,
							TO_CHAR(rr.arrival_time,\'DD/MM/YYYY\') as arrival_time,
							TO_CHAR(rr.departure_time, \'DD/MM/YYYY\') as departure_time,
							TO_CHAR(departure_time,\'YYYY\') as year,
							TO_CHAR(departure_time,\'MM\') as month,
							room.name as room_name,
							DECODE(rr.reduce_balance,null,0,rr.reduce_balance) as reduce_balance,
							DECODE(rr.reduce_amount,null,0,rr.reduce_amount) as reduce_amount,						
							DECODE(rr.tax_rate,null,0,rr.tax_rate) as tax_rate,
							DECODE(rr.service_rate,null,0,rr.service_rate) as service_rate,
							DECODE(rr.deposit,null,0,rr.deposit) as deposit,
							rr.foc,
							rr.foc_all,
							rr.reservation_type_id,
							rr.payment_type_id,
							payment_type.def_code
					from 
					    room_status rs
				     inner join reservation_room rr on rs.reservation_room_id = rr.id
					 INNER JOIN room ON room.id = rr.room_id 
					 inner join payment_type on rr.reservation_type_id = payment_type.id
					 where '.$cond.'
					 order by rr.id ';
		$items = DB::fetch_all($sql);
		$month_amount = array();
		$deposit= array();
		$foc = array();
		$reduce_amount = array();
		$sql_deposit ='
						SELECT 
							sum(deposit) as deposit, to_char(deposit_date,\'MM\') as id
						FROM 
							reservation_room rr 
						WHERE
							 (1=1) and to_char(deposit_date,\'YYYY\') =\''.$year.'\'
					   Group by to_char(deposit_date,\'MM\')';
		$deposit_year = DB::fetch_all($sql_deposit);
		$phone = array();
		foreach($items as $k=>$v){
			if($v['arrival_time']== $v['departure_time']){
				$v['change_price'] = $v['price'];
				}
				$net=0;
				$sv = $v['change_price']*$v['service_rate']*0.01;
				$tax = $v['tax_rate'] *($sv + $v['change_price'])*0.01;
				$net = $v['change_price'] + $sv + $tax;
				$reduce_balance = $net*$v['reduce_balance']*0.01;
				if(!isset($reduce_amount[$v['reduce_amount']])){
					$reduce_amount[$v['reduce_amount']]['id'] = $v['reservation_room_id'];
					$net = $net - $v['reduce_amount'];
				}
				$net = $net - $reduce_balance;
				if(($v['foc'])||($v['foc_all'])){
					if(isset($foc['room'])){ $foc['room']['amount'] += $net; }
					else{ $foc['room']['amount'] = $net; }
					$net =0;
				}
				if(!isset($phone[$v['reservation_room_id']])){
					$phone[$v['reservation_room_id']]['id'] = $v['reservation_room_id'];
					$phone_amount = ($this->get_phone($v['time_in'],$v['time_out'],$v['room_id']))?$this->get_phone($v['time_in'],$v['time_out'],$v['room_id']):0;
					if($v['foc_all']){
						if(isset($foc['room'])){ $foc['room']['amount'] += $phone_amount; }
						else{ $foc['room']['amount'] = $phone_amount; }
					   $phone_amount = 0;
					}
					$net = $net + $phone_amount;
				}
				if(!isset($deposit[$v['reservation_room_id']])){
					$deposit[$v['reservation_room_id']]['id'] = $v['reservation_room_id'];
					$net = $net - $v['deposit'];
				}
				if(isset($month_amount[$v['month']])){
					$month_amount[$v['month']]['amount'] +=$net;
					if(isset($deposit_year[$v['month']])){
						$month_amount[$v['month']]['amount'] +=$deposit_year[$v['month']]['deposit'];
						unset($deposit_year[$v['month']]);
					}
				}else{
					$month_amount[$v['month']]['amount'] = $net;
					$month_amount[$v['month']]['id'] = $v['month'];
					if(isset($deposit_year[$v['month']])){
						$month_amount[$v['month']]['amount'] += $deposit_year[$v['month']]['deposit'];
						unset($deposit_year[$v['month']]);
					}
				}
		}
		foreach($deposit_year as $id=>$v){
			if(isset($month_amount[$id])){
				$month_amount[$id]['amount'] += $v['deposit'];
			}else{
				$month_amount[$id]['amount'] = $v['deposit'];
				$month_amount[$id]['id'] = $v['deposit'];
			}
		}
		return $month_amount;
	}
	function get_service($year){
		$cond ='rr.status =\'CHECKOUT\' and  to_char (departure_time,\'YYYY\') = \''.$year.'\'';
		$sql_serive = '
							SELECT 
									sum (rrs.amount) as total_service , to_char(rr.departure_time,\'MM\') as month,
									rrs.reservation_room_id as id, rr.foc_all
							FROM 
									reservation_room_service rrs 
									INNER JOIN reservation_room rr ON rrs.reservation_room_id = rr.id 
							WHERE 	
							      '.$cond.'
						   GROUP BY 
						    	rrs.reservation_room_id ,rr.foc_all,
						   		to_char(rr.departure_time,\'MM\') ';
		$services = DB::fetch_all($sql_serive);
		$foc =0;;
		$service_month = array();
		foreach($services as $id=>$v){
			if($v['foc_all']){
				$foc +=$v['total_service'];
				$v['total_service'] =0;
			}
			if(isset($service_month[$v['month']])){
				$service_month[$v['month']]['amount'] +=$v['total_service'];
			}else{
				$service_month[$v['month']]['amount'] =$v['total_service'];
			}
		}
		return $service_month;
	}
	function get_amount_extra_service($year){
			$extra_cond = 'rr.status =\'CHECKOUT\' AND  to_char (departure_time,\'YYYY\') = \''.$year.'\' ';
		$sql = '
					SELECT
						SUM(esid.quantity * esid.price) total,esid.invoice_id as id , rr.foc_all,
						to_char(rr.departure_time,\'MM\') as month
						
					FROM
                        extra_service_invoice_detail esid
                        INNER JOIN extra_service_invoice esi ON esi.id = esid.invoice_id
                        INNER JOIN reservation_room rr ON esi.reservation_room_id = rr.id
					WHERE '.$extra_cond.' AND esid.used = 1
                    GROUP BY  to_char(rr.departure_time,\'MM\'), esid.invoice_id,rr.foc_all
                    '; 
		$extra_service = DB::fetch_all($sql);
        $extra_month = array();
		$foc =0;
		foreach($extra_service as $k=>$v){
			if($v['foc_all']){
				$foc +=$v['total'];
				$v['total'] =0;
			}
			if(isset($extra_month[$v['month']])){
				$extra_month[$v['month']]['amount'] +=$v['total'];
			}else{
				$extra_month[$v['month']]['amount'] =$v['total'];
			}
		}
        return $extra_month;
    }
	function get_hk($year){
			$cond = 'rr.status =\'CHECKOUT\' AND to_char(rr.departure_time,\'YYYY\') =\''.$year.'\'';
			$sql = '
						SELECT
							hk.id,
							hk.total,rr.foc_all,to_char(rr.departure_time,\'MM\') as month,
							hk.reservation_id
						FROM
							housekeeping_invoice hk
							INNER JOIN reservation_room rr ON rr.id = hk.reservation_id
						WHERE (hk.type = \'LAUNDRY\' or hk.type = \'MINIBAR\') and '.$cond.'
			'; 
			$hk = DB::fetch_all($sql);
			$hk_month = array();
			$foc = 0;
			foreach($hk as $id=>$v){
				if($v['foc_all']){
					$foc += $v['total'];
					$v['total'] =0;
				}
				if(isset($hk_month[$v['month']])){
					$hk_month[$v['month']]['amount'] +=$v['total'];
				}else{
					$hk_month[$v['month']]['amount'] =$v['total'];
				}
			}
			return $hk_month;
	}
    function get_bar($year){
        $sql = '   
		 SELECT          br.id,
						 br.total,
						 br.reservation_room_id, 
						 rr.foc_all,
                         EXTRACT(MONTH FROM FROM_UNIXTIME(br.departure_time)) month
					FROM
						bar_reservation br
						left outer join reservation_room rr on br.reservation_room_id = rr.id
					WHERE  
						br.status=\'CHECKOUT\' 
                        AND EXTRACT(YEAR FROM FROM_UNIXTIME(br.departure_time)) = \''.$year.'\'
					order by br.id
            ';
        $bar = DB::fetch_all($sql);
        $bar_month = array();
		$foc =0;
        foreach($bar as $id=>$v){
            if($v['foc_all']){
				$foc = $v['total'];
				$v['total'] =0;
			}
			if(isset($bar_month[$v['month']])){
				$bar_month[$v['month']]['amount'] +=$v['total'];
			}else{
				$bar_month[$v['month']]['amount'] =$v['total'];
			}
        }
		return $bar_month;
    }
    function get_spa($year){
        $sql ='
					SELECT 
                        SUM(massage_reservation_room.total_amount) total
                        ,EXTRACT(MONTH FROM FROM_UNIXTIME(time)) id
					FROM 
						massage_reservation_room
					WHERE
                        status=\'CHECKOUT\' 
                        AND EXTRACT(YEAR FROM FROM_UNIXTIME(time)) =\''.$year.'\'
                    GROUP BY EXTRACT(MONTH FROM FROM_UNIXTIME(time))
                    ';    
		$spa = DB::fetch_all($sql);
		$amount = array();
		foreach($spa as $id=>$v){
			if(isset($amount[$v['id']])){
				$amount[$v['id']]['amount'] += $v['total'];
			}else{
					$amount[$v['id']]['amount'] = $v['total'];
			}
		}
        return $amount;
    }
    function draw(){
        $data = false;
        $months = array(1,2,3,4,5,6,7,8,9,10,11,12);
        $year = Url::get('selected_year', date('Y',time()));
        $room_revenue = $this->get_revenue_room($year);
		$other_service = $this->get_service($year);
        $extra_service =$this->get_amount_extra_service($year);
		$hk = $this->get_hk($year);
        $bar = $this->get_bar($year);
		$spa = $this->get_spa($year);
		ksort($room_revenue);
		ksort($other_service);
        ksort($extra_service);
		ksort($hk);
        ksort($bar);
		ksort($spa);
		for($i=1;$i<=12;$i++){
			$k=$i;
			if($i<10){
				$k ='0'.$i;
			}
			if(!isset($room_revenue[$k])){
				$room_revenue[$k]['amount'] =0;
				if(isset($other_service[$k])){
					$room_revenue[$k]['amount']+= $other_service[$k]['amount'];
				}
			}else{
				if(isset($other_service[$k])){
						$room_revenue[$k]['amount']+= $other_service[$k]['amount'];
				};
			}
			if(!isset($extra_service[$k])){
				$extra_service[$k]['amount'] =0;
			}
			if(!isset($hk[$k])){
				$hk[$k]['amount'] =0;
			}
			if(!isset($bar[$k])){
				if(isset($bar[$i])){
					$bar[$k] = $bar[$i];
					unset($bar[$i]);
				}else{
					$bar[$k]['amount'] =0;
				}
			}
			if(!isset($spa[$k])){
				if(isset($spa[$i])){
					$spa[$k]=$spa[$i];
					unset($spa[$i]);
				}else{
					$spa[$k]['amount'] =0;
				}
			}
		}
        $this->parse_layout('report', array(
            'list_month' => json_encode($months),
            'room' =>String::array2js($room_revenue),
			'bar'=>String::array2js($bar),
			'hk'=>String::array2js($hk),
			'extra_service'=>String::array2js($extra_service),
			'spa'=>String::array2js($spa),
            'year' => $year));
    }
}
?>