<?php 
// Bảng thống kê
//TIÊU CHÍ BÁO CÁO:
//-- lẤY THEO CÁC LOẠI PHÒNG TRONG RESERVATION_TYPE.
//-- ỨNG VỚI MỖI RESERVATION_TYPE LẤY CÁC THÔNG SỐ: GIÁ TRUNG BÌNH , CÔNG SUẤT , TỔNG SỐ PHÒNG , TỔNG TIỀN.
//-- GIÁ TRUNG BÌNH: LÀ GIÁ BÌNH QUÂN CỦA TỪNG LOẠI ĐẶT PHÒNG, ĐƯỢC TÍNH BẰNG TỔNG TIỀN DOANH THU PHÒNG (BAO GỒM CẢ li,lo,ei) CHIA CHO TỔNG SỐ ĐÊM PHÒNG BÁN ĐƯỢC CỦA LOẠI ĐÓ.
//-- CÔNG SUẤT: LÀ % CỦA TỔNG SỐ ĐÊM PHÒNG BÁN ĐƯỢC CỦA LOẠI PHÒNG ĐÓ CHIA CHO TỔNG SỐ PHÒNG BÁN ĐƯỢC CỦA TẤT CẢ CÁC LOẠI.
//-- TỔNG SỐ PHÒNG: LÀ TỔNG SỐ ĐÊM PHÒNG CỦA LOẠI PHÒNG ĐÓ, TỈNH CẢ ĐÊM PHÒNG ĐÃ BÁN VÀ ĐẴ ĐẶT. (TRỪ PHÒNG CANCEL).
//-- TỔNG TIỀN: TỔNG DOANH THU CỦA TẤT CẢ ĐÊM PHÒNG ĐÃ BÁN VÀ ĐẶT TRỪ PHÒNG CANCEL BAO GỒM CẢ (EL,LI,LO).
//LẤY DỮ LIỆU: 
//-- DOANH THU PHÒNG DỰA VÀO BẢNG ROOM_STATUS LẤY THEO NGÀY VÀ THEO RESERVATION_ROOM_ID.
//-- DOANH THU EI,LI,LO DỰA VÀO BẢNG EXTRA_SERVICE_INVOICE LẤY THEO NGÀY VÀ THEO RESERVATION_ROOM_ID.
//ĐƯỜNG ĐI DỮ LIỆU:
//-- LẤY DOANH THU PHÒNG DỰA TRÊN LƯỢC ĐỒ GIÁ TỪNG NGÀY VÀ TỪNG RESERVATION_ROOM_ID --> CHECK GIÁ TRƯỚC THUẾ -> GIẢM GIÁ -> TÍNH GIÁ CÓ THUẾ
//-- LẤY DOANH THU LI,LO,EI DỰA TRÊN DỊCH VỤ TỪNG NGÀY VÀ TỪNG RESERVATON_ROOM_ID --> LẤY GIÁ CÓ THUẾ
//-- LẶP LỒNG 2 MẢNG TRÊN, DỰA VÀ ĐIỀU KIỆN NGÀY VÀ RESERVATION_ROOM_ID BẰNG NHAU THÌ LẤY TỔNG DOANH THU GÁN VÀO MẢNG DOANH THU PHÒNG.
?>
<?php
class StatisticsReportForm extends Form{
	function StatisticsReportForm(){
		Form::Form('StatisticsReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
	}
	function draw()
    {
        $this->map = array();
        $from_day =(Url::get('from_day'))?Date_Time::to_orc_date(Url::get('from_day')):Date_Time::to_orc_date(date('d/m/Y'));
        $end_day = (Url::get('to_day'))?Date_Time::to_orc_date(Url::get('to_day')):Date_Time::to_orc_date(date('d/m/Y',(Date_Time::to_time(date('d/m/Y')) + 7*24*3600)));
        $time_from_day =(Url::get('from_day'))?Date_Time::to_time(Url::get('from_day')):(Date_Time::to_time(date('d/m/Y')));
        $time_to_day =(Url::get('to_day'))?Date_Time::to_time(Url::get('to_day')):(Date_Time::to_time(date('d/m/Y')) + 7*24*3600);
        $portal_id = "";
        $portal_room = "";
        if(Url::get('portal_id'))
        {
            if(Url::get('portal_id')!='ALL')
            {
                $portal = Url::get('portal_id');
                $portal_id .= " AND r.portal_id = '$portal'";
                $portal_room .= "AND room.portal_id = '$portal'";
            }
        }
        //lay chi tieu trong reservation_type
        $list_reservation_type = DB::fetch_all("SELECT id,name FROM customer_group where id!='ROOT' AND id!='KNH' AND id!='SUPPLIER'");
        // tổng số đêm phòng
        
        //$room = DB::fetch("SELECT count(room.name) as room FROM room inner join room_level on room.room_level_id = room_level.id where nvl(room_level.is_virtual,0) = 0".$portal_room);
        //$j=1;
        
        $room['room'] = 0;
        $portal = Url::get('portal_id')?Url::get('portal_id'):'ALL';
        for($i=$time_from_day;$i<=$time_to_day;$i+=24*3600)
        {
            //$j++;
            /** manh tinh lai tong so phong theo lich su **/
            if($portal !='ALL')
            {
                if($his_in_date = DB::fetch('select max(in_date) as in_date from room_history where in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$i)).'\' and portal_id=\''.$portal.'\'','in_date'))
                {
                    $room['room'] += DB::fetch('select 
                                                count(rhd.room_id) as total_room 
                                            from
                                                room_history_detail rhd
                                                inner join room_history rh on rh.id=rhd.room_history_id
                                                inner join room_level on room_level.id = rhd.room_level_id
                                            where
                                                rh.in_date=\''.$his_in_date.'\'
                                                and rh.portal_id=\''.$portal.'\'
                                                and rhd.close_room=1
                                                and room_level.is_virtual = 0
                                                ','total_room');
                }
                elseif($his_in_date = DB::fetch('select min(in_date) as in_date from room_history where in_date>\''.Date_Time::to_orc_date(date('d/m/Y',$i)).'\' and portal_id=\''.$portal.'\'','in_date'))
                {
                    $room['room'] += DB::fetch('select 
                                                count(rhd.room_id) as total_room 
                                            from
                                                room_history_detail rhd
                                                inner join room_history rh on rh.id=rhd.room_history_id
                                                inner join room_level on room_level.id = rhd.room_level_id
                                            where
                                                rh.in_date=\''.$his_in_date.'\'
                                                and rh.portal_id=\''.$portal.'\'
                                                and rhd.close_room=1
                                                and room_level.is_virtual = 0
                                                ','total_room');
                }
            }
            else
            {
                $list_portal = Portal::get_portal_list();
                $total_room_all = 0;
                foreach($list_portal as $key=>$value)
                {
                    if($his_in_date = DB::fetch('select max(in_date) as in_date from room_history where in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$i)).'\' and portal_id=\''.$value['id'].'\'','in_date'))
                    {
                        $total_room_all += DB::fetch('select 
                                                    count(rhd.room_id) as total_room 
                                                from
                                                    room_history_detail rhd
                                                    inner join room_history rh on rh.id=rhd.room_history_id
                                                    inner join room_level on room_level.id = rhd.room_level_id
                                                where
                                                    rh.in_date=\''.$his_in_date.'\'
                                                    and rh.portal_id=\''.$value['id'].'\'
                                                    and rhd.close_room=1
                                                    and room_level.is_virtual = 0
                                                    ','total_room');
                    }
                    elseif($his_in_date = DB::fetch('select min(in_date) as in_date from room_history where in_date>\''.Date_Time::to_orc_date(date('d/m/Y',$i)).'\' and portal_id=\''.$value['id'].'\'','in_date'))
                    {
                        $total_room_all += DB::fetch('select 
                                                    count(rhd.room_id) as total_room 
                                                from
                                                    room_history_detail rhd
                                                    inner join room_history rh on rh.id=rhd.room_history_id
                                                    inner join room_level on room_level.id = rhd.room_level_id
                                                where
                                                    rh.in_date=\''.$his_in_date.'\'
                                                    and rh.portal_id=\''.$value['id'].'\'
                                                    and rhd.close_room=1
                                                    and room_level.is_virtual = 0
                                                    ','total_room');
                    }
                }
                if($total_room_all!=0)
                {
                    $room['room'] += $total_room_all;
                }
            }
            /** End Manh **/
        }
        
        //$room['room'] = $room['room']*$j;
        
        $room_dirty = DB::fetch("SELECT count(room_status.house_status) as room_dirty FROM room_status inner join room on room_status.room_id=room.id where room_status.house_status = 'REPAIR' AND room_status.in_date>='$from_day' AND room_status.in_date<='$end_day'".$portal_room); 
         // tổng số đêm khách sạn có
        $room['room']=$room['room']-$room_dirty['room_dirty'];
        
        $sql = '
				SELECT 
					rs.change_price,
					rs.reservation_room_id ,
                    rs.reservation_id ,
					rr.tax_rate, 
					rr.service_rate,
                    rr.net_price,
                    NVL(rr.adult,0) as adult,
                    NVL(rr.child,0) as child,
                    NVL(rr.old_arrival_time,0) as old_arrival_time,
					rs.in_date,
                    date_to_unix(rs.in_date) as time_in_date,                    
					rs.id,
					rr.arrival_time,
					rr.departure_time,
					rr.price,
                    rr.time_in,                    
                    customer.id as customer_id,
                    customer.name as customer_name,
					customer_group.id as reservation_type_id,
					customer_group.name as reservation_type_name,
                    rr.reduce_balance,
                    rr.reduce_amount,
                    rr.foc,
                        rr.foc_all,
                        nvl(rr.change_room_from_rr,0) as change_room_from_rr,
                        nvl(rr.change_room_to_rr,0) as change_room_to_rr,
                        from_unixtime(rr.old_arrival_time) as old_arival_date
				FROM 
					room_status rs 
    				INNER JOIN  reservation_room rr ON rr.id = rs.reservation_room_id 
    				INNER JOIN  reservation r on rr.reservation_id = r.id
                    INNER JOIN  customer on r.customer_id=customer.id
                    INNER JOIN  customer_group on customer.group_id=customer_group.id
    				left JOIN  room ON room.id = rr.room_id
    				left JOIN  room_level on room_level.id = rr.room_level_id
				WHERE 
					(rs.status =\'OCCUPIED\' OR rs.status =\'BOOKED\')
                    and customer.name != \'NOIBO\'
					AND (room_level.is_virtual is null or room_level.is_virtual = 0)
					AND rs.in_date >= \''.$from_day.'\' 
					AND rs.in_date <= \''.$end_day.'\'
				'.$portal_id.' ORDER BY customer_group.id,customer_id,rs.in_date';
            $room_totals = DB::fetch_all($sql);
            //System::debug($room_totals);
            $orcl = '
                SELECT
                    esid.id,
                    esid.in_date,
                    esid.quantity+nvl(esid.change_quantity,0) as quantity,
                    esi.tax_rate,
                    esi.service_rate,
                    esi.net_price,
                    customer.id as customer_id,
                    customer.name as customer_name,
                    customer_group.id as reservation_type_id,
                    customer_group.name as reservation_type_name,
                    esid.price as change_price,
                    esid.percentage_discount,
                    esid.amount_discount,
                    es.code,
                    esi.payment_type,
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
                    left JOIN  room ON room.id = rr.room_id
                    left JOIN  room_level on room_level.id = rr.room_level_id
                WHERE
                    (
                    es.code = \'LATE_CHECKIN\' or(es.code!=\'LATE_CHECKIN\' and es.code!=\'TRANSPORT\' and es.type=\'ROOM\')
                    )
                    and rr.status != \'CANCEL\' and rr.status != \'NOSHOW\'
                    AND (room_level.is_virtual is null or room_level.is_virtual = 0)
                    AND esid.in_date >= \''.$from_day.'\' 
                    AND esid.in_date <= \''.$end_day.'\' '.$portal_id.'
                ORDER BY customer_group.id,customer_id,esid.in_date
                ';
            $extra_ser_room = DB::fetch_all($orcl);
            //System::debug($extra_ser_room);
            $result = array();
            foreach($room_totals as $key => $value)
            {
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
                $amount = number_format($amount);
                $amount = System::calculate_number($amount);
                if($value['foc']!='' OR $value['foc_all']==1)
                {
                    $amount=0;
                }
                $night=1;
                if($value['arrival_time']==$value['departure_time'] AND $value['change_room_from_rr']==0 AND $value['change_room_to_rr']==0 AND $value['time_in'] < ($value['time_in_date']+(6*3600)) )
                {
                    $night = 0;
                }
                if($value['arrival_time']==$value['departure_time'] AND $value['change_room_from_rr']!=0 AND $value['change_room_to_rr']==0 AND $value['old_arrival_time'] < ($value['time_in_date']+(6*3600)) )
                {
                    $night = 0;
                }
                if($value['arrival_time']==$value['departure_time'] AND $value['change_room_to_rr']==0 AND $value['change_room_from_rr']!=0 AND $value['old_arival_date']!=$value['departure_time'])
                {
                    $night = 0;
                }
                /** doi phong trong ngay **/
                if($value['arrival_time']==$value['departure_time'] AND $value['change_room_to_rr']!=0)
                {
                    $night = 0;
                    $amount = 0;
                }
                /** ngay cuoi cung trong chang **/
                if( $value['arrival_time']!=$value['departure_time'] AND $value['in_date'] == $value['departure_time'])
                {
                    $night = 0;
                    $amount = 0;
                }
                if(!isset($result[$value['reservation_type_id']]))
                {
                    $result[$value['reservation_type_id']]['id'] = $value['reservation_type_id'];
                    $result[$value['reservation_type_id']]['total_amount'] = $amount;
                    $result[$value['reservation_type_id']]['total_room'] = $night; 
                }
                else
                {
                    $result[$value['reservation_type_id']]['total_amount'] += $amount;
                    $result[$value['reservation_type_id']]['total_room'] += $night; 
                }
            }
            //CONG THEM TIEN DICH VU MO RONG
            foreach($extra_ser_room as $key => $value)
            {
                //check net price
                if($value['net_price'])
                    $value['change_price'] = $value['change_price']/(1+$value['service_rate']/100)/(1+$value['tax_rate']/100);
                // giam gia %
                $value['change_price'] = $value['change_price'] - ($value['change_price']*$value['percentage_discount']/100);
                // giam gia so tien
                $value['change_price'] = $value['change_price'] - $value['amount_discount'];
                //check option thue 
                $amount = $value['change_price']*(1+$value['service_rate']/100)*(1+$value['tax_rate']/100);
                $amount = $amount*$value['quantity'];
                $amount = number_format($amount);
                $amount = System::calculate_number($amount);
                if($value['code']=='LATE_CHECKIN')
                $night = $value['quantity'];
                else
                $night = 0;
                if($value['foc_all']==1)
                {
                    $amount = 0;
                }
                if(!isset($result[$value['reservation_type_id']]))
                {
                    $result[$value['reservation_type_id']]['id'] = $value['reservation_type_id'];
                    $result[$value['reservation_type_id']]['total_amount'] = $amount;
                    $result[$value['reservation_type_id']]['total_room'] = $night; 
                }
                else
                {
                    $result[$value['reservation_type_id']]['total_amount'] += $amount;
                    $result[$value['reservation_type_id']]['total_room'] += $night; 
                }
            }
            $total_rooms = 0;
            $totals = 0;
            foreach($list_reservation_type as $key => $value)
            {
                if(isset($result[$key]))
                {
                    $total_rooms +=($result[$key]['total_room']);
                    $totals += ($result[$key]['total_amount']);
                }
            }
            //System::Debug($result);
//-----------------------------------------
        $this->parse_layout('report',array(
                                        'from_date'=>Date_Time::convert_orc_date_to_date($from_day,'/'),
                                        'to_date'=>Date_Time::convert_orc_date_to_date($end_day,'/'),
                                        'list_reservation_type'=>$list_reservation_type,
                                        'total_room'=>$room['room'],
                                        'portal_id_list'=>array('ALL'=>Portal::language('all')) + String::get_list(Portal::get_portal_list()),
                                        'items'=>$result,
                                        'total_rooms'=>$total_rooms,
                                        'totals' => $totals
                                        ));	
    }
}
?>
