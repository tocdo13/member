<!--
TI?N PH?NG: D?A V�O B?NG ROOM_STATUS �? L?Y D? LI?U V? TI?N PH?NG THEO T?NG NG�Y.
//LI�N K?T �?N RESERVATION_ROOM, RESERVATION, ROOM_LEVEL �? KI?M TRA GI� NET, FOC, FOC_ALL, PH?NG ?O, V� POTAL.
TI?N D?CH V?: D?A V�O B?NG extra_service_invoice_detail �? L?Y D? LI?U V? C�C KHO?N DV EI,LO,LI. T��NG ?NG V?I C�C
NG�Y ��?C CH?N. LI�N K?T �?N C�C B?NG RESERVATION_ROOM, RESERVATION, ROOM_LEVEL �? KI?M TRA GI� NET, FOC, FOC_ALL, PH?NG ?O, V� POTAL.
----------
QUY TR?NH T�NH TO�N:
+ C?NG: DOANH THU PH?NG L?Y ? L�?C �? GI� C?A T?NG NG�Y. L�U ?: TH PH?NG DAYUSE V?N C?NG B?NH TH�?NG

+ C?NG: KHO?N LI, EI, LO ( DVMR THANH TO�N V?I PH?NG) - USEDATE L� NG�Y N�O TH? C?NG V�O NG�Y ��, VD NG�Y �I 24/05  NG�Y 23/05 CH?N +0.5 LO  TI?N LO V�O BI?U �? NG�Y 24/05 

+ TR?: KHO?N GI?M GI� THEO PH?N TR�M, GI?M GI� THEO S? TI?N( TR? V�O L�?C �? GI� NG�Y �?U TI�N)

+ TR?: KHO?N MI?N PH�: MI?N PH� TI?N PH?NG, MI?N PH� T?T C?  V�O BI?U �? C?T L� 0
-->
<?php
class PieChartRoomRevenueReportForm extends Form{
	function PieChartRoomRevenueReportForm(){
		Form::Form('PieChartRoomRevenueReportForm');
		$this->link_js('packages/core/includes/js/jquery/chart/highcharts.src.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/jquery/chart/exporting.js');
	}
	function get_amout_room($date_from,$date_end){
	   $cond_potal = " and 1=1 ";
        if(Url::get('portal_id'))
            $cond_potal .= "and room.portal_id = '".Url::get('portal_id')."'";
       
		$sql1 = '
				SELECT 
					rs.change_price ,
					rs.reservation_room_id ,
					rr.tax_rate, 
					rr.service_rate,
                    rr.net_price,
					rs.in_date,
					rs.id,
					rr.arrival_time,
					rr.departure_time,
					rr.price,
					customer_group.id as reservation_type_id,
					customer_group.name as reservation_type_name,
                    rr.reduce_balance,
                    rr.reduce_amount,
                    rr.foc,
                    rr.foc_all
				FROM 
					room_status rs 
    				INNER JOIN  reservation_room rr ON rr.id = rs.reservation_room_id 
    				INNER JOIN  reservation r on rr.reservation_id = r.id
                    INNER JOIN  customer on r.customer_id=customer.id
                    INNER JOIN  customer_group on customer.group_id=customer_group.id
    				left JOIN  room ON room.id = rr.room_id
    				left JOIN  room_level on room_level.id = room.room_level_id
				WHERE 
					(rs.status =\'OCCUPIED\' OR rs.status =\'BOOKED\')
					AND (room_level.is_virtual is null or room_level.is_virtual = 0)
					AND rs.in_date >= \''.$date_from.'\' 
					AND rs.in_date <= \''.$date_end.'\'
				 ';
		$room_totals = DB::fetch_all($sql1.$cond_potal);
        //System::debug($room_totals);
        $orcl = '
            SELECT
                esid.id,
                esid.in_date,
                esid.quantity,
                esi.tax_rate,
                esi.service_rate,
                esi.net_price,
                esi.reservation_room_id,
                customer_group.id as reservation_type_id,
                customer_group.name as reservation_type_name,
                esid.price as change_price,
                esid.percentage_discount,
                esid.amount_discount,
                rr.foc,
                rr.foc_all
            FROM
                extra_service_invoice_detail esid
                INNER JOIN  extra_service_invoice esi ON esid.invoice_id=esi.id
                INNER JOIN  reservation_room rr ON rr.id = esi.reservation_room_id
                INNER JOIN  reservation r on rr.reservation_id = r.id
                INNER JOIN  customer on r.customer_id=customer.id
                INNER JOIN  customer_group on customer.group_id=customer_group.id
                INNER JOIN  extra_service es ON es.id = esid.service_id
            WHERE
                (es.code = \'LATE_CHECKIN\' 
                OR es.code = \'EARLY_CHECKIN\' 
                OR es.code=\'LATE_CHECKOUT\'
                OR esi.payment_type = \'ROOM\')
                AND esid.in_date >= \''.$date_from.'\' 
                AND esid.in_date <= \''.$date_end.'\'
            ';
        $extra_ser_room = DB::fetch_all($orcl);
        //System::debug($extra_ser_room);      
		$result = array();
        foreach($room_totals as $key => $value)
        {
            if(!isset($result[$value['reservation_type_id']]))
            {
                $result[$value['reservation_type_id']] = array('id'=>$value['reservation_type_id'],'amount_total'=>0,'name'=>$value['reservation_type_name']);
            }
            
            //check net price
            if($value['net_price'])
                $value['change_price'] = $value['change_price']/(1+$value['service_rate']/100)/(1+$value['tax_rate']/100);
            //GIAM GIA %
            $value['change_price'] *= (1-$value['reduce_balance']/100);
            //GIAM GIA SOTIEN
            if($value['in_date'] == $value['arrival_time'])
                $value['change_price'] -= $value['reduce_amount'];
            //check option thue 
            $amount = $value['change_price']*(1+$value['service_rate']/100)*(1+$value['tax_rate']/100);
            if($value['foc']!='' OR $value['foc_all']==1)
            {
                $amount=0;
            }
            $result[$value['reservation_type_id']]['amount_total'] += $amount;
        }
        //CONG THEM TIEN DICH VU MO RONG
        foreach($extra_ser_room as $key => $value)
        {
            if(!isset($result[$value['reservation_type_id']]))
            {
                $result[$value['reservation_type_id']] = array('id'=>$value['reservation_type_id'],'amount_total'=>0,'name'=>$value['reservation_type_name']);
            }
            //check net price
            if($value['net_price'])
                $value['change_price'] = $value['change_price']/(1+$value['service_rate']/100)/(1+$value['tax_rate']/100);
            // giam gia %
            $value['change_price'] = $value['change_price'] - ($value['change_price']*$value['percentage_discount']/100);
            // giam gia so tien
            $value['change_price'] = $value['change_price'] - $value['amount_discount'];
            //check option thue 
            $amount = $value['change_price']*(1+$value['service_rate']/100)*(1+$value['tax_rate']/100);
            if($value['foc_all']==1)
            {
                $amount = 0;
            }
            $amount = $amount*$value['quantity'];
            $result[$value['reservation_type_id']]['amount_total'] += $amount;
        }
        foreach($result as $key_re=>$value_re)
        {
            $result[$key_re]['amount_total'] = number_format($value_re['amount_total']);
            $result[$key_re]['amount_total'] = System::calculate_number($result[$key_re]['amount_total']);
            if($value_re['amount_total']==0)
            {
                unset($result[$key_re]);
            }
            
        }
		return $result;
	}
	function draw(){
		 $from_day =(Url::get('from_date'))?Date_Time::to_orc_date(Url::get('from_date')):'1-'.date('M').'-'.date('Y');
		 $end = date('t').'-'.date('M').'-'.date('Y');
		 $time_end =date('t').'/'.date('m').'/'.date('Y');
		 $end_day = (Url::get('to_date'))?Date_Time::to_orc_date(Url::get('to_date')):$end;
		 $amount_room = $this->get_amout_room($from_day,$end_day);
         //System::debug($amount_room);
		 $this->parse_layout('report',array('items'=>String::array2js($amount_room),'portal_id_list'=>array(''=>Portal::language('all')) + String::get_list(Portal::get_portal_list(false,'#albawater'))));
		}
}
?>
