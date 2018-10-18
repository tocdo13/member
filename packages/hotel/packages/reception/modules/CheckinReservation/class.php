<?php 
class CheckinReservation extends Module
{
	function CheckinReservation($row)
	{
        if(isset($_REQUEST['get_autocomplete']))
        {
            require_once 'packages/core/includes/utils/vn_code.php';
            $cond = '1=1';
            if(Url::get('type')=='TRAVELLER_NAME' OR Url::get('type')=='PASSPORT')
            {
                if(Url::get('reservation_id'))
                    $cond .= ' AND reservation.id='.Url::get('reservation_id');
                if(Url::get('reservation_room_id'))
                    $cond .= ' AND reservation_room.id='.Url::get('reservation_room_id');
                
                if(Url::get('type')=='TRAVELLER_NAME'){
                    $cond .= ' AND (
                                        LOWER(FN_CONVERT_TO_VN(traveller.first_name)) like \'%'.convert_utf8_to_latin(mb_strtolower(URL::sget('q'))).'%\'
                                        OR LOWER(FN_CONVERT_TO_VN(traveller.last_name)) like \'%'.convert_utf8_to_latin(mb_strtolower(URL::sget('q'))).'%\'
                                        OR LOWER(FN_CONVERT_TO_VN(traveller.first_name || \' \' || traveller.last_name)) like \'%'.convert_utf8_to_latin(mb_strtolower(URL::sget('q'))).'%\'
                                    )';
                    $items = DB::fetch_all('
                                				select 
                                					reservation_traveller.id
                                                    ,rownum
                                                    ,traveller.first_name || \' \' || traveller.last_name || \' - ( Phòng: \' || room.name || \'-\' || reservation_room.status || \' )\' as name
                                                    ,reservation_room.id as reservation_room_id
                                                    ,reservation.id as reservation_id
                                				from
                                					reservation_traveller
                                                    inner join traveller on reservation_traveller.traveller_id=traveller.id
                                                    inner join reservation_room on reservation_traveller.reservation_room_id=reservation_room.id
                                                    inner join room on room.id=reservation_room.room_id
                                                    inner join reservation on reservation.id=reservation_room.reservation_id
                                				where
                                                    '.$cond.'
                                					AND (rownum > 0 AND rownum <= 1000)
                                                    AND (reservation_room.status=\'BOOKED\')
                                				order by
                                					traveller.first_name, traveller.last_name
                                			');
                }else{
                    $cond .= ' AND LOWER(FN_CONVERT_TO_VN(traveller.passport)) like \'%'.convert_utf8_to_latin(mb_strtolower(URL::sget('q'))).'%\'';
                    $items = DB::fetch_all('
                                				select 
                                					reservation_traveller.id
                                                    ,rownum
                                                    ,traveller.passport || \' \' || traveller.first_name || \' \' || traveller.last_name || \' - ( Phòng: \' || room.name || \'-\' || reservation_room.status || \' )\' as name
                                                    ,reservation_room.id as reservation_room_id
                                                    ,reservation.id as reservation_id
                                				from
                                					reservation_traveller
                                                    inner join traveller on reservation_traveller.traveller_id=traveller.id
                                                    inner join reservation_room on reservation_traveller.reservation_room_id=reservation_room.id
                                                    inner join room on room.id=reservation_room.room_id
                                                    inner join reservation on reservation.id=reservation_room.reservation_id
                                				where
                                                    '.$cond.'
                                					AND (rownum > 0 AND rownum <= 1000)
                                                    AND (reservation_room.status=\'BOOKED\')
                                				order by
                                					traveller.first_name, traveller.last_name
                                			');
                }
                
                foreach($items as $key=>$value)
                {
                    echo $value['name'].'|'.$value['id']."\n";
                }
            }elseif(Url::get('type')=='ROOM_NAME'){
                
                $cond .= ' AND LOWER(FN_CONVERT_TO_VN(room.name)) like \'%'.convert_utf8_to_latin(mb_strtolower(URL::sget('q'))).'%\'';
                
                $items = DB::fetch_all('
                                				select 
                                					reservation_room.id
                                                    ,rownum
                                                    ,room.name || \'-\' || reservation_room.status as name
                                                    ,reservation_room.reservation_id 
                                				from
                                					reservation_room
                                                    inner join room on room.id=reservation_room.room_id
                                				where
                                                    '.$cond.'
                                					AND (rownum > 0 AND rownum <= 1000)
                                                    AND (reservation_room.status=\'BOOKED\')
                                				order by
                                					room.name
                                			');
                foreach($items as $key=>$value)
                {
                    echo $value['name'].'|'.$value['id']."\n";
                }
            }elseif(Url::get('type')=='RESERVATION_ID'){
                
                $cond .= ' AND LOWER(FN_CONVERT_TO_VN(reservation.id)) like \'%'.convert_utf8_to_latin(mb_strtolower(URL::sget('q'))).'%\'';
                
                $items = DB::fetch_all('
                                				select 
                                					reservation.id
                                                    ,rownum
                                				from
                                					reservation
                                				where
                                                    '.$cond.'
                                                    and reservation.id in (select reservation_id from reservation_room inner join room on reservation_room.room_id = room.id where status = \'BOOKED\' )
                                					AND (rownum > 0 AND rownum <= 1000)
                                			');
                foreach($items as $key=>$value)
                {
                    echo $value['id'].'|'.'Recode:#'.$value['id']."\n";
                }
            }
            
            exit();
        }
        if(isset($_REQUEST['find_data']))
        {
	       $cond = '1=1';
           if(Url::get('type')=='TRAVELLER_NAME' OR Url::get('type')=='PASSPORT'){
                
                $items = DB::fetch('
                                    SELECT 
                                        reservation.id as reservation_id
                                        ,reservation_room.id as reservation_room_id
                                        ,room.name as room_name
                                        ,traveller.first_name || \' \' || traveller.last_name as traveller_name
                                        ,traveller.passport
                                        ,reservation_traveller.id as reservation_traveller_id
                                    FROM
                                        reservation_traveller
                                        inner join traveller on reservation_traveller.traveller_id=traveller.id
                                        inner join reservation_room on reservation_traveller.reservation_room_id=reservation_room.id
                                        inner join room on room.id=reservation_room.room_id
                                        inner join reservation on reservation.id=reservation_room.reservation_id
                                    WHERE
                                        reservation_traveller.id='.Url::get('reservation_traveller_id').'
                                    ');
                echo json_encode($items);
                
           }elseif(Url::get('type')=='ROOM_NAME'){
                $items = DB::fetch('
                                    SELECT 
                                        reservation.id as reservation_id
                                        ,reservation_room.id as reservation_room_id
                                        ,room.name as room_name
                                    FROM
                                        reservation_room
                                        inner join room on room.id=reservation_room.room_id
                                        inner join reservation on reservation.id=reservation_room.reservation_id
                                    WHERE
                                        reservation_room.id='.Url::get('reservation_room_id').'
                                    ');
                $items['list_traveller'] = DB::fetch_all('
                                                SELECT 
                                                    traveller.first_name || \' \' || traveller.last_name as traveller_name
                                                    ,traveller.passport
                                                    ,reservation_traveller.id
                                                FROM
                                                    reservation_traveller
                                                    inner join traveller on reservation_traveller.traveller_id=traveller.id
                                                    inner join reservation_room on reservation_traveller.reservation_room_id=reservation_room.id
                                                WHERE
                                                    reservation_room.id='.Url::get('reservation_room_id').'
                                                ');
                echo json_encode($items);
           }elseif(Url::get('type')=='RESERVATION_ID'){
                $items = DB::fetch_all('
                                    SELECT
                                        reservation_room.id as id
                                    FROM
                                        reservation_room
                                        inner join reservation on reservation.id=reservation_room.reservation_id
                                    WHERE
                                        reservation.id='.Url::get('reservation_id').'
                                    ');
                echo json_encode($items);
           }
           
           exit();
	    }
        /** thao tac CI nhanh **/
        if(isset($_REQUEST['status'])=='CHECKIN')
        {
            $rr_arr = array();
            $sql = 'select 
                        reservation_room.*,
                        room_level.name as room_level_name,
                        room.name as room_name
                    from reservation_room 
                    inner join room_level on reservation_room.room_level_id = room_level.id
                    inner join room on reservation_room.room_id = room.id
                    where 
                        reservation_room.id='.$_REQUEST['id'];
            $rr_arr = DB::fetch($sql);
            /** check conflig phong CI **/
            $cond = 'and room.portal_id = \''.PORTAL_ID.'\' AND r.status=\'CHECKIN\'
						AND R.room_id=\''.$rr_arr['room_id'].'\' ';
            $sql = '
					SELECT 
						R.id,R.reservation_id,
                        room.name as room_name
					FROM 
						reservation_room R
						INNER JOIN room ON room.id = R.room_id
					WHERE 
						R.id!='.$_REQUEST['id'].'
	        ';
            $arr_can_not_checkin = array();
            $check = false;
            if($reservation_room = DB::fetch($sql.' '.$cond))
            {
                $arr_can_not_checkin[1]=array(
                    'status'=>'conflig',
                    'error' => Portal::language('conflig_room').' '.$reservation_room['room_name'].' #Recode '.$reservation_room['reservation_id']
                );
                $check = true;
            }
            /** check hang phong **/
            if (!OVER_BOOK)
            {
                require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
                $items = room_level_check_conflict(array($rr_arr['room_level_id'],$rr_arr['time_in'],$rr_arr['time_out'],$rr_arr['id']));
				if(!isset($arr_level[$rr_arr['room_level_id']]))
                {
                    $arr_level[$rr_arr['room_level_id']] = $items;	
				}
                else
                {
                    $arr_level[$rr_arr['room_level_id']] = $items - 1;
				}
				if(!$items || $arr_level[$rr_arr['room_level_id']] <0 )
                {
                    $arr_can_not_checkin[2]=array(
                    'status'=>'conflig_room_level',
                    'error' => Portal::language('Room_level').': '.$rr_arr['room_level_name'].' '.Portal::language('is_out_of_from').' '.date('d/m/Y',$rr_arr['time_in']).' - '.date('d/m/Y',$rr_arr['time_out'])
                    );	
                    $check = true;
				}
            }
            /** check phong repair **/
            if(DB::fetch('select * from room_status where house_status = \'REPAIR\' AND room_id = '.$rr_arr['room_id'].' AND ((in_date >= \''.$rr_arr['arrival_time'].'\''.' AND in_date < \''.$rr_arr['departure_time'].'\') or (in_date = \''.$rr_arr['departure_time'].'\' and TO_CHAR(in_date, \'DD-MON-YYYY\') != TO_CHAR(start_date, \'DD-MON-YYYY\')))'))
            {
                $arr_can_not_checkin[3]=array(
                'status'=>'room_repair',
                'error' => Portal::language('room_'.$rr_arr['room_name'].' repair')
                );	
                $check = true;
            }
            /** check phong dirty **/
            /**if(DB::fetch('select * from room_status where house_status = \'DIRTY\' AND room_id = '.$rr_arr['room_id'].''))
            {
                $arr_can_not_checkin[4]=array(
                'status'=>'room_dirty',
                'error' => Portal::language('room_'.$rr_arr['room_name'].' room_dirty')
                );	
                $check = true;
            }**/
            /** timein khong duoc nho hon timeout **/
            if(time()>$rr_arr['time_out'])
            {
                $arr_can_not_checkin[5]=array(
                'status'=>'time_in',
                'error' => Portal::language('time_out_is_less_than_time_in')
                );	
                $check = true;
            }
            if($check==false)
            {
                
                /** update bang reservation_room **/
                DB::update('reservation_room',array(
                                                'status'=>'CHECKIN',
                                                'checked_in_user_id'=>User::id(),
                                                'time_in'=>time(),
                                                'arrival_time' =>Date_Time::to_orc_date(date('d/m/Y'))
                                            ),'id='.$_REQUEST['id']);
                /** update bang room_status **/
                $from_day = Date_Time::to_time(date('d/m/Y'));
                $from   = Date_Time::to_time(date('d/m/Y',$rr_arr['time_in']));
            	$to   = Date_Time::to_time(date('d/m/Y',$rr_arr['time_out']));
            	$d = $from;
           		$status='OCCUPIED';
            	while($d>=$from and $d<=$to)
            	{
           		   
                   if($d>=$from_day and $d<=$to)
                   {
                        $sql = 'select * from room_status where in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$d)).'\' and reservation_room_id='.$rr_arr['id'].'';
                	   if($room_status = DB::fetch($sql))
                		{
                		    DB::update_id('room_status',
                				array(
                				'status'=>$status,
                				),$room_status['id']
                			);
                		}
                		else
                		{
                  		     DB::insert('room_status',
                				array(
                					'room_id'=>$rr_arr['room_id'],
                					'status'=>$status,
                					'reservation_id'=>$rr_arr['reservation_id'],
                					'change_price'=>$rr_arr['price'],
                					'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
                					'reservation_room_id'=>$rr_arr['id']
                				)
                			);
                		}
                   }
                   else
                   {
                        DB::delete('room_status', 'reservation_room_id =\''.$rr_arr['id'].'\' and in_date =\''.Date_Time::to_orc_date(date('d/m/Y',$d)).'\'');
                   }
            		$d=$d+(3600*24);
            	}
                /** kiem tra xem co ton tai Li thi them vao **/
                if(Date_time::to_time(date('d/m/Y'))>Date_time::to_time(date('d/m/Y',$rr_arr['time_in'])))
                {
                    if(LATE_CHECKIN_AUTO == 1)
                    {
                        $time_check = (date('H')*3600 + date('i')*60);
                        $li_start_time = explode(':',AUTO_LI_START_TIME);
                        $start_time_li = $li_start_time[0]*3600 + $li_start_time[1]*60;
                        $li_end_time = explode(':',AUTO_LI_END_TIME);
                        $end_time_li = $li_end_time[0]*3600 + $li_end_time[1]*60;
                        if($time_check >= $start_time_li and $time_check <= $end_time_li)
                        {
                            $time = Date_Time::to_time(date('d/m/Y',time()))-86400;
            				$quantity = 1;
            				$arr_late_in['in_date'] = Date_Time::to_orc_date(date('d/m/Y',$time));
                            $auto_late_checkin_price = $rr_arr['price'];
                            $auto_late_checkin_price = DB::fetch('select change_price from room_status where reservation_room_id = '.$_REQUEST['id'].' and in_date=\''.Date_Time::to_orc_date(date('d/m/Y')).'\'','change_price');
                            //echo 'select change_price from room_status where reservation_room_id = '.$_REQUEST['id'].' and in_date=\''.Date_Time::to_orc_date(date('d/m/Y')).'\'';
                            if($auto_late_checkin_price!='' AND $rr_arr['net_price'] == 1){
                                $auto_late_checkin_price = round($auto_late_checkin_price/(1+($rr_arr['tax_rate']*0.01) + ($rr_arr['service_rate']*0.01) + (($rr_arr['tax_rate']*0.01)*($rr_arr['service_rate']*0.01))),4);
                            }
                            $arr_late_in['price'] = $auto_late_checkin_price;
                            $services = DB::fetch('select * from extra_service where code=\'LATE_CHECKIN\'');
            				$name_service = $services['name'];
                            $late_checkin_id = $services['id'];
                            $invoice = array(
								'reservation_room_id'=>$rr_arr['id'],
								'user_id'=>($rr_arr['checked_in_user_id']==''?$rr_arr['booked_user_id']:$rr_arr['checked_in_user_id']),
								'portal_id'=>PORTAL_ID,
								'payment_type'=>'ROOM',
								'time'=>$rr_arr['time'],
								'tax_rate'=>$rr_arr['tax_rate'],
								'service_rate'=>$rr_arr['service_rate']
							);
            				$arr_3 = $arr_late_in + array('time'=>$time,'quantity'=>$quantity,'service_id' =>$late_checkin_id,'name' =>$name_service);
            				$invoice_3 = $invoice + array('late_checkin'=>1,'type'=>'ROOM','time'=>$time,'note'=>' Add automatic late checkin','total_before_tax'=>($arr_late_in['price']*$quantity),'total_amount'=>(($arr_late_in['price']*$quantity) + (($arr_late_in['price']*$quantity)*$rr_arr['service_rate']*0.01) + (($arr_late_in['price']*$quantity) + (($arr_late_in['price']*$quantity)*$rr_arr['service_rate']*0.01))*$rr_arr['tax_rate']*0.01));
                            $invoice_id = DB::insert('extra_service_invoice',$invoice_3);
                            $table_id=DB::insert('extra_service_invoice_table',array('from_date'=>$arr_late_in['in_date'],'to_date'=>$arr_late_in['in_date'],'invoice_id'=>$invoice_id));
        					DB::insert('extra_service_invoice_detail',$arr_3 + array('invoice_id'=>$invoice_id,'table_id'=>$table_id,'used'=>1));  
        					if(strlen($invoice_id)==1)
                            {
        						$invoice_id = '0'.$invoice_id;
        					}
        					DB::update('extra_service_invoice',array('bill_number'=>'ES'.$invoice_id),'id='.$invoice_id);
                            /** th li ma dayused thi up date luoc do gia **/
                            if(date('d/m/Y')==date('d/m/Y',$rr_arr['time_out']))
                            {
                                $li = DB::fetch_all('select * from extra_service_invoice_detail
                                            inner join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id 
                                            inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                                            where 
                                                extra_service.code = \'LATE_CHECKIN\'
                                                and extra_service_invoice.reservation_room_id = '.$rr_arr['id'].'  
                                            ');
                                if(sizeof($li)>0)
                                {
                                    DB::update('room_status',array('change_price'=>0),'reservation_room_id='.$rr_arr['id'].'');
                                }
                            }
                            /** end th li ma dayused thi up date luoc do gia **/
                        }
                    }
                }
             }
            echo json_encode($arr_can_not_checkin);
            exit();
        }
        /** end thao tac CI nhanh **/
		Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY)){
			require_once 'forms/list.php';
			$this->add_form(new CheckinReservationForm());
		}else{
			Url::access_denied();
		}
	}
}
?>