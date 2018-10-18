<?php
/***
*** tieu chi lay : tien phong + ei/lo theo khoảng thời gian, portal
1, tinh truoc thue, sau thue
2, nhan voi giam gia phan tram
3, tru giam gia so tien vao ngay dau tien
4, tien phong thi tru phong ao
5, tien phong vaf extra_service tru phong foc, focall
***/
class RoomRevenueComparingBarChartReportForm extends Form{
	function RoomRevenueComparingBarChartReportForm(){
		Form::Form('RoomRevenueComparingBarChartReportForm');
		$this->link_js('packages/core/includes/js/jquery/chart/highcharts.src.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/jquery/chart/exporting.js');
	}
	function get_amout_room($date_from,$date_end,$cond)
    {
		$sql1 = '
				SELECT 
					rs.change_price,
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
				'.$cond.' ORDER BY rs.in_date';
		$room_totals = DB::fetch_all($sql1);
        //System::debug($room_totals);
        $orcl = '
            SELECT
                esid.id,
                esid.in_date,
                esid.quantity,
                esi.tax_rate,
                esi.service_rate,
                esi.net_price,
                customer_group.id as reservation_type_id,
                esid.price as change_price,
                esid.percentage_discount,
                esid.amount_discount,
                esi.payment_type,
                es.code,
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
                AND esid.in_date <= \''.$date_end.'\' '.$cond.'
            ORDER BY esid.in_date
            ';
        $extra_ser_room = DB::fetch_all($orcl);
        $result = array();
        foreach($room_totals as $key => $value)
        {
            if(!isset($result[$value['in_date']."_".$value['reservation_type_id']]))
            {
                $result[$value['in_date']."_".$value['reservation_type_id']] = array('date'=>$value['in_date'],'amount'=>0,'reservation_type_id'=>$value['reservation_type_id']);
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
            $result[$value['in_date']."_".$value['reservation_type_id']]['amount'] += $amount;
        }
        
        //CONG THEM TIEN DICH VU MO RONG
        foreach($extra_ser_room as $key => $value)
        {
            if(!isset($result[$value['in_date']."_".$value['reservation_type_id']]))
            {
                $result[$value['in_date']."_".$value['reservation_type_id']] = array('date'=>$value['in_date'],'amount'=>0,'reservation_type_id'=>$value['reservation_type_id']);
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
            $result[$value['in_date']."_".$value['reservation_type_id']]['amount'] += $amount;
            
        }
        foreach($result as $key_re=>$value_re)
        {
            $result[$key_re]['amount'] = number_format($value_re['amount']);
            $result[$key_re]['amount'] = System::calculate_number($result[$key_re]['amount']);
            if($value_re['amount']==0)
            {
                unset($result[$key_re]);
            }
            
        }
		return $result;
    }
	function get_amout_room_month($date_from,$date_end,$cond,$check)
    {
		$sql1 = '
				SELECT 
					rs.change_price,
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
				'.$cond.' ORDER BY rs.in_date';
		$room_totals = DB::fetch_all($sql1);
        //System::debug($room_totals);
        $orcl = '
            SELECT
                esid.id,
                esid.in_date,
                esid.quantity,
                esi.tax_rate,
                esi.service_rate,
                esi.net_price,
                customer_group.id as reservation_type_id,
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
                AND esid.in_date <= \''.$date_end.'\' '.$cond.'
            ORDER BY esid.in_date
            ';
        $extra_ser_room = DB::fetch_all($orcl);
        $result = array();
        //lay tien phong
        foreach($room_totals as $key => $value)
        {
            $time = Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['in_date'],'/'));
            $month = date('m',$time);
            if(!isset($result[$month."_".$value['reservation_type_id']]))
            {
                $result[$month."_".$value['reservation_type_id']] = array('month'=>$month,'amount'=>0,'reservation_type_id'=>$value['reservation_type_id']);
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
            $result[$month."_".$value['reservation_type_id']]['amount'] += $amount;
        }
        //CONG THEM TIEN DICH VU MO RONG
        foreach($extra_ser_room as $key => $value)
        {
            $time = Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['in_date'],'/'));
            $month = date('m',$time);
            if(!isset($result[$month."_".$value['reservation_type_id']]))
            {
                $result[$month."_".$value['reservation_type_id']] = array('month'=>$month,'amount'=>0,'reservation_type_id'=>$value['reservation_type_id']);
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
            $result[$month."_".$value['reservation_type_id']]['amount'] += $amount;
        }
        foreach($result as $key_re=>$value_re)
        {
            $result[$key_re]['amount'] = number_format($value_re['amount']);
            $result[$key_re]['amount'] = System::calculate_number($result[$key_re]['amount']);
            if($value_re['amount']==0)
            {
                unset($result[$key_re]);
            }
            
        }
        //System::Debug($result);
		return $result;
	}
	function draw()
    {
		 $from_day =(Url::get('from_date'))?Date_Time::to_orc_date(Url::get('from_date')):'1-'.date('M').'-'.date('Y');
		 $from_day2 = Url::get('to_date')?'01-JAN-'.date('y',Date_Time::to_time(Url::get('to_date'))):'01-JAN-'.date('y');
         $end = date('t').'-'.date('M').'-'.date('Y');
		 $time_end =date('t').'/'.date('m').'/'.date('Y');
		 $end_day = (Url::get('to_date'))?Date_Time::to_orc_date(Url::get('to_date')):$end;
		 $time_from_day =(Url::get('from_date'))?Date_Time::to_time(Url::get('from_date')):(Date_Time::to_time('1'.'/'.date('m').'/'.date('Y')));
		 $time_from_day2 =(Date_Time::to_time(Url::get('to_date')?'01/01/'.date('Y',Date_Time::to_time(Url::get('to_date'))):'01/01/'.date('Y')));
         $time_to_day =(Url::get('to_date'))?Date_Time::to_time(Url::get('to_date')):(Date_Time::to_time($time_end));
		 $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list(false,'#albawater'));
         $check = Url::get('check_tax')?Url::get('check_tax'):'yes_tax';
         //echo $check;
         if(Url::get('portal_id'))
         {
            $portal_id = Url::get('portal_id');
         }
         else
         {
            $portal_id = PORTAL_ID;
            $_REQUEST['portal_id'] = PORTAL_ID;                       
         }
         $cond = ' AND 1 = 1';
         if($portal_id != 'ALL')
         {
            $cond .=' AND r.portal_id = \''.$portal_id.'\' '; 
         }
         //////////////////////////////////////
         /// Tính toán theo ngày
         ////////////////////////////////
         $amount_room = $this->get_amout_room($from_day,$end_day,$cond);
         
		 $reservation_types= DB::fetch_all('select * from customer_group where id!=\'ROOT\' AND id!=\'KNH\' AND id!=\'SUPPLIER\' order by id');
		 $items= array();
		 for($i = $time_from_day ; $i <= $time_to_day; $i += 24*3600){ 
			$date_to_oracle = Date_Time::to_orc_date(date('d/m/y',$i));
			$items[date('d/m/Y',$i)]['date']= date('d',$i);
            
            foreach($reservation_types as $k_rt=>$v_rt){
                if(isset($amount_room[$date_to_oracle."_".$k_rt]))
                    $items[date('d/m/Y',$i)][$k_rt.'amount'] = round($amount_room[$date_to_oracle."_".$k_rt]['amount']);
                else 
                    $items[date('d/m/Y',$i)][$k_rt.'amount'] = 0;
            }
        }
          
           ///////////////////////////////////////
           /// Tính toán theo tháng
           ////////////////////////////////
         $time_date = (Url::get('to_date'))?Date_Time::to_time((Url::get('to_date'))):time();
         $end_day_of_year = date('t',Date_Time::to_time(date('1/12/y',$time_date)));
         $end_date_of_year = Date_Time::to_orc_date(date($end_day_of_year.'/12/y',$time_date));
         $end_time_of_year = Date_Time::to_time(date($end_day_of_year.'/12/y',$time_date));
         //$amount_room_month = $this->get_amout_room_month($from_day2,$end_day,$cond); dat- comment
         $amount_room_month = $this->get_amout_room_month($from_day2,$end_date_of_year,$cond,$check);
         
         $data = array();
         for($i = $time_from_day2 ; $i <= $end_time_of_year; $i += 24*3600)
         { 
			$month = date('m',$i);
            if(isset($data[$month])) continue;
			$data[$month]['date']= $month;
            
            foreach($reservation_types as $k_rt=>$v_rt){
                if(isset($amount_room_month[$month."_".$k_rt]))
                    $data[$month][$k_rt.'amount'] = round($amount_room_month[$month."_".$k_rt]['amount']);
                else 
                    $data[$month][$k_rt.'amount'] = 0;
            }
        }
        $reservation_types_arr = array(0=>''); $stt_r_t = 0;
        foreach($reservation_types as $k_rt=>$v_rt)
        {
            $stt_r_t++;
            $reservation_types_arr += array($stt_r_t=>$v_rt['name']);
        }
        $layout = 'report';
        if(Url::get('export_month'))
        {
            $data_arr = array(0=>$reservation_types_arr);
            $stt = 0;
            foreach($items as $id=>$content)
            {
                $stt++;
                $stt_r_t = 0; $arr = array();
                foreach($content as $value)
                {
                    $arr[$stt_r_t] = $value;
                    $stt_r_t++;
                }
                $data_arr += array($stt=>$arr);
            }
            require_once ROOT_PATH.'exportexcel/Tests/room_revenue_comparing_bar_chart_in_month.php';
            $name_chart = "container month detail";
            export($name_chart,$data_arr);
            echo "<script>window.open('http://".$_SERVER['HTTP_HOST']."/".Url::$root."exportexcel/Tests/room_revenue_comparing_bar_chart_in_month.xlsx');</script>";
        }
        elseif(Url::get('export_year'))
        {
            $data_arr = array(0=>$reservation_types_arr);
            $stt = 0;
            foreach($data as $id=>$content)
            {
                $stt++;
                $stt_r_t = 0; $arr = array();
                foreach($content as $value)
                {
                    $arr[$stt_r_t] = $value;
                    $stt_r_t++;
                }
                $data_arr += array($stt=>$arr);
            }
            require_once ROOT_PATH.'exportexcel/Tests/room_revenue_comparing_bar_chart_in_month.php';
            $name_chart = "container year detail";
            export($name_chart,$data_arr);
            echo "<script>window.open('http://".$_SERVER['HTTP_HOST']."/".Url::$root."exportexcel/Tests/room_revenue_comparing_bar_chart_in_month.xlsx');</script>";
        }
        $this->parse_layout($layout,array('items_month_detail'=>String::array2js($items),'items_months'=>String::array2js($data),'type'=>String::array2js($reservation_types),"tax_check"=>$check)+$this->map);
	}
}
?>
<!--
comment cách lấy dữ liệu và tính toán:
//Dựa vào bảng room_status để lấy tiền phòng theo từng ngày theo ngày nhập vào.
//Liên kết đến các bảng: reservation_room, reservation, room_level, để lấy dữ liệu về: giá net, giá foc, foc_all, loại trừ phòng ảo, lấy giảm giá theo
phần trăm và theo số tiền, thuế phí phòng, theo potal.
//dựa vào bảng extra_service_invoice_detail để lấy dữ liệu về dịch vụ phòng như EI, LO cho các phòng đã list ở trên
TRÌNH TỰ TÍNH TOÁN GIÁ PHÒNG
1. CHECK GIÁ NET(1). HOẶC KHÔNG NET(0);
2. TRỪ GIẢM GIÁ THEO % ĐỐI VỚI TẤT CẢ CÁC NGÀY(reduce_balance). HOẶC GIẢM GIÁ THEO SỐ TIỀN ĐỐI VỚI NGÀY ĐẦU TIÊN(reduce_amount);
3. KIỂM TRA NÚT CHỌN XEM CÓ THUẾ HOẶC XEM KHÔNG THUẾ.
4. CỘNG THÊM CÁC KHOẢN DỊCH VỤ MỞ RỘNG (*).
(*) - CÁCH TÍNH CÁC KHOẢN DỊCH VỤ MỞ RỘNG CÔNG THÊM TƯƠNG TỰ QUY TRÌNH TÍNH GIÁ PHÒNG.
-->
