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
function createReservation()
{
    $reservation=DB::fetch_all('select * from hotellink_reservation where status=\'MODIFIED\'');
    if($reservation){
        foreach($reservation as $key=>$value){
            $info['customer_id']=$value['customer_id'];
            $info['booker']=$value['phone'];
            $info['email_booker']=$value['email'];
            $info['note']=$value['comments'];
            $info['payment_type1']=$value['payment_method']=='OTA Payment'?'OTA Payment':'By the guest';
            $info['user_id']='hotellink';
            $info['time']=time();
            $info['hotellink_reservation_id']=$value['id'];
            $info['portal_id']='#default';            
            $info['booking_code']=$value['booking_id'];
            if($id = DB::insert('reservation',$info)){
                DB::update('hotellink_reservation',array('status'=>''),'id='.$value['id'].'');
                $reservation_room=DB::fetch_all('select * from hotellink_reservation_room where hotellink_reservation_id ='.$value['id'].'');
                $exchange=DB::fetch('select exchange as id from currency where id =\'USD\'','id');
                if($reservation_room){
                    foreach($reservation_room as $k=>$v){
                        for($i=1;$i<=$v['quantity'];$i++){
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
                            if($value['currency']=='VND'){
                                $record['price'] =	 $value['amount'];
                                $record['usd_price'] =	round($value['amount']/$exchange,2);
                            }else{
                                $record['price'] =	 round($value['amount']*$exchange,2);
                                $record['usd_price'] =	 $value['amount'];
                            }
                            $record['commission_rate'] = 0;
                            $record['verify_dayuse'] = 0;
                            $record['total_amount'] = 0;
                            $record['adult'] = $v['adult'];
                            $record['child'] = $v['child'];
                       		$record['temp_room'] = '';
                            $record['room_id'] = '';
                            $record['room_level_id']=$v['room_id'];                            
                            $record['arrival_time']=$value['check_in'];
                            $record['time_in']=Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['check_in'])) + 5*3600;  
                            $record['departure_time']=$value['check_out'];
                            $record['time_out']=Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['check_out'])) + 7*3600; 
                            $record['reservation_id'] = $id;
                            $record['foc_all'] = 0;
                            $record['status']='BOOKED';                            
                            $record['booked_user_id'] = 'hotellink';
                            $record['related_rr_id'] = 0;
                            $record['customer_name']='Hotel Link Solution'; 
                            $record['user_id'] = 'hotellink';
                            $record['total_amount'] = $record['price'];
                        	$record['time'] = time();
                            $record['id']=DB::insert('reservation_room',$record);
                        	DB::update('reservation_room',array('bill_number'=>'RE'.$record['id']),'id='.$record['id']);
                            update_room_map($id,$record);
                            unset($record['id']);
                        }
                    }
                }
            }
        }
    }
}
function update_room_map($id,$record)
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
				'room_id'=>'',
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
