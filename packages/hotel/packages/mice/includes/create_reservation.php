<?php
/**
I.Ham tao dat phong credit_reservation($room_leves,$info,$type)
    1. $room_leves: la mang 2 chieu  co 
            id => id
            room_level_id => room_level_id,
            quantity => so luong phong can dat cua hang phong do,
            child => so luong tre em,
            adult => so luong nguoi lon,
            price => gia,
            usd_price => gia usd
            note => ghi chu
            time_in => (dinh dang time la cac chuoi so).
            time_out => (dinh dang time la cac chuoi so).
            net_price => gia net.
            tax_rate => thue.
            service_rate => phi dich vu.
            exchange_rate => ty gia
    2. $infor là mang 1 chieu
        $infor=array(
            $customer_id=>id cua customer,
            booker=>nguoi dat,
            phone_booker=> sdt nguoi dat
        );
    3.$type : $type = true thì cho luu giu lieu vào cac  bang , $type = false thi chi cho check du lieu
    4. $mice_reservation_id la id cua mice
**/
function credit_reservation($room_levels,$info,$type,$mice_reservation_id)
{
    $all_room_levels = DB::fetch_all('select id,price,name,brief_name from room_level where portal_id = \''.PORTAL_ID.'\' order by name');
    $error = array();
    $er = 1;
    
    if(isset($info['customer_id']) and $info['customer_id'] !='')
    {
        if($customer_name = DB::fetch('select customer.name  from customer where id=\''.$info['customer_id'].'\'','name'))
        {  
        }else{
            $error[$er]['note'] = Portal::language('undefined_customer');
            $er++;
        }
    }
    else
    {
        if($type)
        {
            $error[$er]['note'] = Portal::language('miss_customer');
            $er++;
        }
    }
    
    if(isset($room_levels))
    {
        $mi_reservation_room = array();
        $note_price = '';
        $note_arr_time = '';
        $count = 1;
        $check_price = false;
        $check_arrival_time = false;
        //System::debug($room_levels);
        foreach($room_levels as $key=>$value)
        {
			
            /** Kimtan check gia = 0 bao loi **/
            if($all_room_levels[$value['room_level_id']]['brief_name']!='PA')
            {
                if($value['price']>0)
                {

                }
                else
                {
                    $note_price .= $all_room_levels[$value['room_level_id']]['brief_name'].',';
                    $check_price = true;
                }
                    
            }
            /** Kimtan check ngay den khong duoc nho hon ngay hien tai **/
            if(Date_Time::to_time(date('d/m/Y',$value['time_in']))<Date_Time::to_time(Date('d/m/Y')) and !User::is_admin())
            {
                
                $note_arr_time .= $all_room_levels[$value['room_level_id']]['brief_name'].',';
                $check_arrival_time = true;
                $er++;
            }
            /** Kimtan check gio den khong duoc lon hon gio di **/
            if(isset($value['time_in']) and $value['time_in'] and isset($value['time_out']) and $value['time_out'])
            {
				if($value['time_out'] <= $value['time_in'])
                {
					$error[$er]['note'] = 'room_level '.$all_room_levels[$value['room_level_id']]['brief_name'].Portal::language('time_out_has_to_be_more_than_time_in');
                    $er++;
				}			
			}
            else
            {
				if($value['time_out'] < $value['time_in'])
                {
					$error[$er]['note'] = 'room_level '.$all_room_levels[$value['room_level_id']]['brief_name'].Portal::language('time_out_has_to_be_more_than_time_in');
                    $er++;
				}
			}
            for($i=1;$i<=$value['quantity'];$i++){
				    $mi_reservation_room[$count] = array(  
					'room_level_name'=>$all_room_levels[$value['room_level_id']]['brief_name'],
					'room_level_id'=>$all_room_levels[$value['room_level_id']]['id'],
					'room_id'=>'',
                    'status'=>'BOOKED',
					'room_name'=>'#'.$count,
					'adult'=>isset($value['adult'])?$value['adult']:$all_room_levels[$value['room_level_id']]['inum_people'],
					'child'=>$value['child'],
					'price'=>$value['price'],
                    'usd_price'=>$value['usd_price'],
					'note'=>$value['note'],
                    'net_price'=>$value['net_price'],
                    'tax_rate'=>$value['tax_rate'],
                    'service_rate'=>$value['service_rate'],
                    'breakfast'=>1,
                    'exchange_rate'=>$value['exchange_rate'],
					'arrival_time'=>date('d/m/Y',$value['time_in']),
					'departure_time'=>date('d/m/Y',$value['time_out']),
					'time_in'=>$value['time_in'],
					'time_out'=>$value['time_out'],
					'confirm'=>Url::get('confirm')?Url::get('confirm'):0
				);
				$count++;
			}
            
		}
        if($check_price==true)
        {
            $error[$er]['note'] = 'room_level '.$note_price.' have price=0';
            $er++;
        }
        if($check_arrival_time==true)
        {
            $error[$er]['note'] = ' '.$note_arr_time.' đặt phòng trong quá khứ';
            $er++;
        }
        $check_room_level = array();
        foreach($mi_reservation_room as $mi_id=>$mi_value)
        {
            /** Kimtan check hạng phòng còn trống **/
            if (!OVER_BOOK)
            {
                $items = room_level_check_conflict(array($mi_value['room_level_id'],$mi_value['time_in'],$mi_value['time_out'],0));
                if(!isset($arr_level[$mi_value['room_level_id']]))
                {
                    $arr_level[$mi_value['room_level_id']] = $items - 1; 
				}
                else
                {
                   $arr_level[$mi_value['room_level_id']] -= 1;
				}
				if(!$items || $arr_level[$mi_value['room_level_id']] < 0 )
                {
                    if(!isset($check_room_level[$mi_value['room_level_id'].'_'.$mi_value['arrival_time'].'_'.$mi_value['departure_time']]))
                    {
                        $check_room_level[$mi_value['room_level_id'].'_'.$mi_value['arrival_time'].'_'.$mi_value['departure_time']] = 1;
                        $error[$er]['note'] = Portal::language('Room_level').': '.$all_room_levels[$mi_value['room_level_id']]['brief_name'].' '.Portal::language('has_only ').$items.' rooms in '.$mi_value['arrival_time'].' - '.$mi_value['departure_time'];
                        $er++;
                    }
				}
            }
            /** END Kimtan check hạng phòng còn trống **/
        }
        //System::debug($mi_reservation_room); die;
        /** ---------------Kimtan doan code luu vao database--------- **/
        if(!$error and $type)
        {
            $id = DB::insert('reservation',
				array(
					'customer_id'=>$info['customer_id'],
                    'booker'=>$info['booker'],
                    'phone_booker'=>$info['phone_booker'],
					'tour_id',
					'note',
					'color',
                    'payment_type1',
					'user_id'=>Session::get('user_id'),
					'time'=>time(),
					'payment',
					'booking_code',
					'portal_id'=>PORTAL_ID,
                    'mice_reservation_id'=>$mice_reservation_id?$mice_reservation_id:'',
                    'is_rate_code'=>isset($_REQUEST['is_rate_code'])?1:0
				)
			);
            //System::debug($mi_reservation_room);
            foreach($mi_reservation_room as $key=>$record)
            {
                $record['confirm'] = 0;
                $record['extra_bed'] = 0;
				$record['extra_bed_from_date'] = '';
				$record['extra_bed_to_date'] = '';
				$record['extra_bed_rate'] = 0;
                
                $record['baby_cot'] = 0;
				$record['baby_cot_from_date'] = '';
				$record['baby_cot_to_date'] = '';
				$record['baby_cot_rate'] = 0;
                
                $record['discount_after_tax'] = 0;
                $record['closed'] = 0;
                $record['early_checkin'] = 0;
                $record['late_checkout'] = 0;
                $record['payment_type_id'] = 0;
                
                $record['price'] =	 str_replace(',','',$record['price']);
                $record['usd_price'] =	 str_replace(',','',$record['usd_price']);
    			
                $record['commission_rate'] = 0;
                $record['verify_dayuse'] = 0;
                $record['total_amount'] = 0;
                
                if(!$record['room_id']){
				$record['temp_room'] = $record['room_name'];
    			}else{
    				$record['temp_room'] = '';
    			}
                $record['room_id'] = '';
                unset($record['room_name']);
                unset($record['room_level_name']);
                $record['arrival_time']=Date_Time::to_orc_date($record['arrival_time']); 
			    $record['departure_time']=Date_Time::to_orc_date($record['departure_time']);
                $record['reservation_id'] = $id;
			    $record['foc_all'] = isset($record['foc_all'])?1:0;
                //$currency_id = (HOTEL_CURRENCY == 'VND')?'USD':'VND';
                //$record['exchange_rate'] = DB::fetch('select id,exchange from currency where id=\''.$currency_id.'\'','exchange');
                $record['booked_user_id'] = Session::get('user_id');
                $record['related_rr_id'] = 0;
                $record['user_id'] = Session::get('user_id');
				$record['time'] = time();
                $record['id'] = DB::insert('reservation_room',$record+array('customer_name'=>$customer_name));
				DB::update('reservation_room',array('bill_number'=>'RE'.$record['id']),'id='.$record['id']);
                reservation_update_room_map($id,$record);
            }
        }
        /** ---------------END Kimtan doan code luu vao database--------- **/
        
    }
    else
    {
        $error[$er]['note'] = Portal::language('miss_infor');
    }
    return $error;
}
function room_level_check_conflict($arr)
{
	$days = array();
    if($arr[1] > time())
    {
        $arr[1] = $arr[1];
    }
    else
    {
        $arr[1] = Date_Time::to_time(date('d/m/Y'));
    }
	for($i = $arr[1];$i < $arr[2];$i = $i + 24*3600)
    {
		$days[$i]['id'] = $i;
		$days[$i]['value'] = date('d/m',$i);
	}	
	$extra_cond = $arr[0]?' rl.id = \''.$arr[0].'\'':' 1>0';
	$room_level = DB::fetch('
		SELECT
			rl.portal_id,
            rl.id,
            rl.name,
            rl.price,
            0 AS min_room_quantity,
            rl.color,
			(SELECT COUNT(*) FROM room WHERE room_level_id = rl.id and room.close_room=1) room_quantity
		FROM	
			room_level rl
		WHERE
			'.$extra_cond.'
			AND rl.portal_id = \''.PORTAL_ID.'\'
		ORDER BY	
			rl.name
	');
    
    /** manh check them lich su hang phong **/
    $start_his = '';
    $end_his = '';
    if($his_in_date = DB::fetch('select max(in_date) as in_date from room_history where in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$arr[1])).'\' and portal_id=\''.PORTAL_ID.'\'','in_date'))
    {
        $start_his = $his_in_date;
    }
    elseif($his_in_date = DB::fetch('select min(in_date) as in_date from room_history where in_date\''.Date_Time::to_orc_date(date('d/m/Y',$arr[1])).'\' and portal_id=\''.PORTAL_ID.'\'','in_date'))
    {
        $start_his = $his_in_date;
    }
    if($his_in_date = DB::fetch('select max(in_date) as in_date from room_history where in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$arr[2])).'\' and portal_id=\''.PORTAL_ID.'\'','in_date'))
    {
        $end_his = $his_in_date;
    }
    elseif($his_in_date = DB::fetch('select min(in_date) as in_date from room_history where in_date\''.Date_Time::to_orc_date(date('d/m/Y',$arr[2])).'\' and portal_id=\''.PORTAL_ID.'\'','in_date'))
    {
        $end_his = $his_in_date;
    }
    if($start_his!='' AND $end_his!='')
    {
        $list_his = DB::fetch_all("SELECT id from room_history where in_date>='".$start_his."' and in_date<='".$end_his."' and portal_id='".PORTAL_ID."'");
        $check=0;
        foreach($list_his as $k_his=>$v_his)
        {
            $check++;
            $room_level_his = DB::fetch('
                                		SELECT
                                			rl.portal_id,
                                            rl.id,
                                            rl.name,
                                            rl.price,
                                            0 AS min_room_quantity,
                                            rl.color,
                                			(SELECT COUNT(rhd.room_id) FROM room_history_detail rhd WHERE rhd.room_level_id = rl.id and rhd.room_history_id='.$v_his['id'].' and rhd.close_room=1) room_quantity
                                		FROM	
                                			room_level rl
                                		WHERE
                                			'.$extra_cond.'
                                			AND rl.portal_id = \''.PORTAL_ID.'\'
                                		ORDER BY	
                                			rl.name
                                	');
            
            if($check==1)
            {
                $room_level = $room_level_his;
            }
            else
            {
                if($room_level['room_quantity']>$room_level_his['room_quantity'])
                {
                    $room_level = $room_level_his;
                }
            }
        }
    }
    
    /** end manh **/
    
	$room_status = array();
	if($room_level['id'])
	{
		$sql = '
			SELECT 
				r.portal_id,
                rs.id,
                rr.status,
                rr.time_in,
                rr.time_out,
                rr.arrival_time,
                rr.departure_time,
                rs.in_date,
                rr.room_level_id,
                rr.id as rr_id ,
                rs.house_status ,
                room.room_level_id as room_level
			FROM
				room_status rs
                LEFT OUTER JOIN room on room.id = rs.room_id
				LEFT OUTER JOIN reservation_room rr ON rs.reservation_room_id = rr.id '.($arr[3]?' AND rr.id<>'.$arr[3]:'').'
				LEFT OUTER JOIN reservation r ON rr.reservation_id = r.id 
			WHERE
				(
                    (
                    rr.status <> \'CANCEL\'
                    AND rr.status <> \'NOSHOW\'
                    AND rr.status <> \'CHECKOUT\'
                    )
                    or rs.house_status = \'REPAIR\'
                ) 
                AND (rr.room_level_id='.$room_level['id'].'  or room.room_level_id='.$room_level['id'].'  )
                AND rs.in_date>=\''.Date_Time::to_orc_date(date('d/m/Y',$arr[1])).'\' 
                and rs.in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$arr[2])).'\'
			ORDER BY
				rr.room_level_id
		';	
	   $room_status = DB::fetch_all($sql);
    }
	$min = 10000;
	foreach($days as $k=>$v)
    {
		$room_quantity = $room_level['room_quantity'];
		foreach($room_status as $kk=>$vv)
        {
			if($vv['room_level_id'] == $room_level['id'] and  Date_Time::convert_orc_date_to_date($vv['in_date'],'/') == date('d/m/Y',$k) and $vv['departure_time'] != $vv['in_date'])
            {
                if(date('d/m/Y',$k)==date('d/m/Y',$arr[2]))
                {
                    if($arr[2]>$vv['time_in'])
                        $room_quantity -= 1;
                }
                else
    				$room_quantity -= 1;
			}
            elseif($vv['room_level']==$room_level['id'] and Date_Time::convert_orc_date_to_date($vv['in_date'],'/') == date('d/m/Y',$k) and $vv['house_status']=='REPAIR'){
                $room_quantity -= 1;
            }
		}
		if($min > $room_quantity)
        {
			$min = $room_quantity;
		}
	}
    /** check inventory **/
    if(SITEMINDER_TWO_WAY and $room_level['id']){
        //foreach($min as $keyMin=>$valueMin){
            $cond_inventory = '1=1 ';
            $cond_inventory_rate = '1=1 ';
            if(date('d/m/Y',$arr[1])==date('d/m/Y',$arr[2])){
                $cond_inventory .= ' and siteminder_room_type_time.in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$arr[1])).'\'';
                $cond_inventory_rate .= ' and siteminder_rate_avail_time.in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$arr[1])).'\'';
            }else{
                $cond_inventory .= ' and siteminder_room_type_time.in_date>=\''.Date_Time::to_orc_date(date('d/m/Y',$arr[1])).'\' and siteminder_room_type_time.in_date<\''.Date_Time::to_orc_date(date('d/m/Y',$arr[2])).'\'';
                $cond_inventory_rate .= ' and siteminder_rate_avail_time.in_date>=\''.Date_Time::to_orc_date(date('d/m/Y',$arr[1])).'\' and siteminder_rate_avail_time.in_date<\''.Date_Time::to_orc_date(date('d/m/Y',$arr[2])).'\'';
            }
            $cond_inventory .= ' and siteminder_room_type.room_level_id='.$room_level['id'];
            $cond_inventory_rate .= ' and siteminder_room_type.room_level_id='.$room_level['id'];
            $avail_max = DB::fetch('
                                    SELECT
                                        max(siteminder_room_type_time.availability) as avail
                                    FROM
                                        siteminder_room_type_time
                                        inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_type_time.siteminder_room_type_id
                                    WHERE
                                        '.$cond_inventory.'
                                    ','avail');
            $avail_rate_max = DB::fetch('
                                        SELECT
                                            max(siteminder_rate_avail_time.availability) as avail
                                        FROM
                                            siteminder_rate_avail_time
                                            inner join siteminder_room_rate on siteminder_room_rate.id=siteminder_rate_avail_time.siteminder_room_rate_id
                                            inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_rate.siteminder_room_type_id
                                        WHERE
                                            '.$cond_inventory_rate.'
                                        ','avail');
            $avail_max = $avail_max?$avail_max+($avail_rate_max?$avail_rate_max:0):0+($avail_rate_max?$avail_rate_max:0);
            $min -= $avail_max;
        //}
    }
    /** end check inventory **/
    /** check allotment **/
    if(USE_ALLOTMENT and $room_level['id']){
        //foreach($min as $keyMin=>$valueMin){
            $cond_allotment = '1=1 ';
            if(date('d/m/Y',$arr[1])==date('d/m/Y',$arr[2])){
                $cond_allotment .= ' and room_allotment_avail_rate.in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$arr[1])).'\'';
            }else{
                $cond_allotment .= ' and room_allotment_avail_rate.in_date>=\''.Date_Time::to_orc_date(date('d/m/Y',$arr[1])).'\' and room_allotment_avail_rate.in_date<\''.Date_Time::to_orc_date(date('d/m/Y',$arr[2])).'\'';
            }
            $cond_allotment .= ' and room_allotment.room_level_id='.$room_level['id'];
            $avail_max = DB::fetch('
                                    SELECT
                                        max(room_allotment_avail_rate.availability) as avail
                                    FROM
                                        room_allotment_avail_rate
                                        inner join room_allotment on room_allotment.id=room_allotment_avail_rate.room_allotment_id
                                    WHERE
                                        '.$cond_allotment.'
                                    ','avail');
            $avail_max = $avail_max?$avail_max:0;
            $min -= $avail_max;
        //}
    }
    /** end check allotment **/
	return $min;
}
function reservation_update_room_map($id,$record)
{
	$from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($record['arrival_time']  ,'/'));
	$to   = Date_Time::to_time(Date_Time::convert_orc_date_to_date($record['departure_time'],'/'));
	$d = $from;
	while($d>=$from and $d<=$to)
	{
		$change_price = 0;
		if(date('d/m/Y',$d)!=Date_Time::convert_orc_date_to_date($record['departure_time'],'/'))
        {
			$change_price = $record['price'];
		}
        elseif($record['arrival_time']==$record['departure_time'])
        {
            $change_price = $record['price'];
        }
        DB::insert('room_status',
			(($record['status']=="CHECKOUT" and $change_status and $d==$to)?array('house_status'=>$house_status):array())+
			array(
				'room_id'=>$record['room_id'],
				'status'=>'BOOKED',
				'reservation_id'=>$id,
				'change_price'=>$change_price,
				'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
				'reservation_room_id'=>$record['id']
			)
		);
		$d=$d+(3600*24);
	}
}
?>
