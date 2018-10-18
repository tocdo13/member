<?php
class Reservation extends Module
{
	function Reservation($row)
	{
	    /** manh them allotment **/
        if(Url::get('status')=='SETUP_ALLOTMENT'){
            $data = DB::fetch_all('
                                    select
                                        room_allotment_avail_rate.rate,
                                        to_char(room_allotment_avail_rate.in_date,\'DD/MM/YYYY\') as id
                                    FROM
                                        room_allotment_avail_rate
                                        inner join room_allotment on room_allotment_avail_rate.room_allotment_id=room_allotment.id
                                    WHERE
                                        room_allotment.customer_id='.Url::get('customer_id').'
                                        and room_allotment.room_level_id='.Url::get('room_level_id').'
                                        and room_allotment_avail_rate.in_date>=\''.Date_Time::to_orc_date(Url::get('arrival_time')).'\'
                                        and room_allotment_avail_rate.in_date<=\''.Date_Time::to_orc_date(Url::get('departure_time')).'\'
                                    ');
            echo json_encode($data);
            exit();
        }
        /** manh them allotment **/
	    /** manh: check last time **/
        if(Url::get('check_last_time')){
            $data = array('status'=>'','user'=>'','time'=>'');
            $last_time = DB::fetch('select last_time,lastest_user_id as user_id from reservation where id='.Url::get('reservation_id'));
            if($last_time['last_time']!=0 and $last_time['last_time']>Url::get('last_time')){
                $data = array('status'=>'error','user'=>$last_time['user_id'],'time'=>date('H:i:s d/m/Y',$last_time['last_time']));
            }
            echo json_encode($data);
            exit();
        }
        /** end manh **/
        /** Start: KID gan phong **/
        if(Url::get('reservation_id'))
        {
            
            $reservation_rooms = DB::fetch_all(
                                'SELECT
									reservation_room.id
									,reservation_room.reservation_id
                                    ,reservation_room.arrival_time
									,reservation_room.departure_time
									,reservation_room.time_in
									,reservation_room.time_out
                                    ,reservation_room.room_level_id
                                    ,reservation_room.price
                                FROM 
                                    reservation_room
                                    INNER JOIN reservation ON reservation_room.reservation_id = reservation.id
                                WHERE 
                                    reservation_room.reservation_id ='.Url::get('reservation_id').' 
                                    AND reservation.portal_id = \''.PORTAL_ID.'\' 
                                    AND reservation_room.status != \'CANCEL\' 
                                    AND reservation_room.room_id is null
                                ORDER BY 
                                    reservation.id');
            
            $cond_level = '';
			$min_time = ''; $max_time = '';
			foreach($reservation_rooms as $k => $reser)
            {
				$cond_level .= ($cond_level=='')?' room_level.id='.$reser['room_level_id'].'':' OR room_level.id='.$reser['room_level_id'].'';
				if($min_time=='' || $reser['time_in']<$min_time){
					$min_time = $reser['time_in'];
				}
				if($max_time=='' || $reser['time_out']>$max_time){
					$max_time = $reser['time_out'];
				}
			}
			if($cond_level==''){
				$cond_level = '1>0';
			}
			$cond_date = '';
			for($i=$min_time; $i<=$max_time;$i=$i+86400)
            {
				$cond_date .= ($cond_date=='')?' rs.in_date=\''.Date_Time::to_orc_date(date('d/m/y',$i)).'\'':' OR rs.in_date=\''.Date_Time::to_orc_date(date('d/m/y',$i)).'\'';
			}
			if($cond_date==''){
				$cond_date = '1>0';
			}
            // Lấy ra danh sách phòng trống
			$room_levels = DB::fetch_all('
				SELECT
					room.id as id
					,room.name as room_name
                    ,room_level.id as room_level_id
                    ,0 as checked
				FROM
					room
					INNER JOIN room_level on room_level.id = room.room_level_id
				WHERE
					room_level.portal_id = \''.PORTAL_ID.'\' 
                    AND (room_level.is_virtual IS NULL OR room_level.is_virtual = 0) 
                ORDER BY 
                    room.floor,
                    room.room_level_id
				');
			$sql_status = '
				SELECT
					r.portal_id,
                    rs.id,
                    rr.time_in,
                    rr.time_out,
                    TO_CHAR(rs.in_date,\'DD/MM/YYYY\') AS in_date,
                    rr.arrival_time,
                    rr.departure_time,
                    rr.room_id
				FROM
					room_status rs
					INNER JOIN reservation_room rr ON rs.reservation_room_id = rr.id
					INNER JOIN reservation r ON rr.reservation_id = r.id
					INNER JOIN room_level  ON rr.room_level_id = room_level.id
					INNER JOIN room ON room.id = rr.room_id
				WHERE
					r.portal_id = \''.PORTAL_ID.'\' AND ('.$cond_date.') AND rs.status <> \'CANCEL\'
				ORDER BY
					rr.room_level_id
				';
			$room_status = DB::fetch_all($sql_status);
            $sql_status2 = '
				SELECT
					r.portal_id,rs.id,rs.room_id
				FROM
					room_status rs
					INNER JOIN room r ON r.id = rs.room_id
				WHERE
					r.portal_id = \''.PORTAL_ID.'\' AND ('.$cond_date.') AND rs.status <> \'CANCEL\'
                    AND rs.status <> \'OCCUPIED\' AND rs.house_status = \'REPAIR\'
				';
			$room_status2 = DB::fetch_all($sql_status2);
            foreach($room_levels as $k=>$room)
            {
				$t=0;
				foreach($room_status as $key=>$status)
                {
					if( $room['id']==$status['room_id'] && date('d/m/Y',$status['time_out']) != $status['in_date'])
                    {
						$t = 1;
					}
				}
                foreach($room_status2 as $ke=>$status2)
                {
					if( $room['id']==$status2['room_id'])
                    {
						$t = 1;
					}
				}
				if($t==0)
                {
					$rooms[$k] = $room;	
				}
			}
            // gan phong
			foreach($reservation_rooms as $a => $r_r)
            {
				$n=0;
				foreach($rooms as $r => $room)
                {
					if($r_r['room_level_id'] == $room['room_level_id'] && $room['checked']==0)
                    {
						DB::update('reservation_room',array('room_id' =>$room['id']),'id='.$a);
                        $from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($r_r['arrival_time'],'/'));
						$to   = Date_Time::to_time(Date_Time::convert_orc_date_to_date($r_r['departure_time'],'/'));
						$d = $from;
						while($d>=$from and $d<=$to)
                        {
							$sql = 'select * from room_status where in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$d)).'\' and reservation_room_id='.$a.'';
                            if($r_status = DB::fetch($sql))
							{
								DB::update('room_status',array('room_id'=>$room['id']),'id='.$r_status['id']);
							}
							else
							{
								/**
                                DB::insert('room_status',
									array(
										'room_id'=>$room['room_id'],
                                        'status'=>$status,
										'reservation_id'=>Url::get('reservation_id'),
										'change_price'=>$r_r['price'],
										'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
										'reservation_room_id'=>$a
									)
								);
                                **/
							}
							$d=$d+(3600*24);
						}
                        //$reservation_rooms[$a]['room_id'] = $room['id'];
                        $rooms[$r]['checked'] = 1;
						$n = 1;
						break;
					}
				}
				if($n==0)
                {
					echo('error');
				}
			}
            exit();
        }
        /** end: KID gan phong **/
        
        
        
        /** START: check folio**/
        /** luu y voi truong hop doi phong can check tat ca cac phong chang cu**/
        if(Url::get('status_edit') == 'CHECKOUT')
        {
            /** START - Check tao hoa don **/
            require_once 'packages/hotel/packages/reception/modules/CreateTravellerFolio/get_reservation_room.php';
            if(!Url::get('is_reservation'))
            {
                $reservation_room_check = DB::fetch('select reservation_room.*,room.name as room_name from reservation_room inner join room on room.id = reservation_room.room_id where reservation_room.id = '.Url::get('id_check'));
                $r_id = $reservation_room_check['reservation_id'];
                $rr_ids = Url::get('id_check').($reservation_room_check['change_room_from_rr']?",".$reservation_room_check['change_room_from_rr']:"");
                $reservation_room_check = DB::fetch_all('select reservation_room.*,room.name as room_name from reservation_room inner join room on room.id = reservation_room.room_id where reservation_room.id in ('.$rr_ids.')');
            }
            else
            {
                $reservation_room_check = DB::fetch_all('select reservation_room.*,room.name as room_name from reservation_room inner join room on room.id = reservation_room.room_id where reservation_room.status != \'CANCEL\' and reservation_room.reservation_id = '.Url::get('id_check'));
                $r_id = Url::get('id_check');
                $rr_ids = '0';
                foreach($reservation_room_check as $key => $value)
                {
                    $rr_ids .= ",".$value['id'];
                }
            }
            $arr_can_not_checkout = array();
            /** Kimtan kiem tra dat coc nhom da duoc tao hoa don het chua**/
            $deposit_group=DB::fetch('select sum(amount*exchange_rate) as deposit from payment where type_dps = \'GROUP\' and type = \'RESERVATION\' and reservation_id ='.$r_id,'deposit');
            $detail_dps_group=DB::fetch('select sum(amount) as total from traveller_folio where traveller_folio.type=\'DEPOSIT_GROUP\' and traveller_folio.reservation_id ='.$r_id,'total');
            $check = '';
            if(abs($deposit_group-$detail_dps_group)>100 and $deposit_group>0)
            {
                $check = 'NOT_CHECKOUT';
            }
            /** end Kimtan kiem tra dat coc nhom da duoc tao hoa don het chua**/
            $folios = DB::fetch_all('SELECT
										(traveller_folio.type || \'_\' || traveller_folio.invoice_id) as id
										,traveller_folio.type
										,traveller_folio.invoice_id
										,sum(traveller_folio.amount) as amount
                                        ,sum(traveller_folio.total_amount) as total_amount
										,sum(traveller_folio.percent) as percent
									FROM traveller_folio
										inner join folio ON folio.id = traveller_folio.folio_id
									WHERE 1>0 AND traveller_folio.reservation_room_id in ('.$rr_ids.')
									GROUP BY
										traveller_folio.invoice_id
										,traveller_folio.type');
			
            $arr_rr_ids = explode(',',$rr_ids);
            $items = array();
            foreach($arr_rr_ids as $k => $v)
            {
                if(DB::exists('select * from reservation_room where id = '.$v." and reservation_id = ".$r_id))
                    $items += get_reservation_room_detail($v,$folios);
            }
            $arr_can_not_checkout = array();
            foreach($items as $k => $itm)
            {
                /** START truong hop doi phong thi lay id la phong chang cuoi de co the chuyen trang thai phong ve CHECKIN khi chua the CHECKOUT**/
                if(Url::get('is_reservation'))
                {
                    $cur_reservation_id = $itm['rr_id'];
                }
                else
                {
                    $cur_reservation_id = Url::get('id_check');
                }
                /** END truong hop doi phong thi lay id la phong chang cuoi de co the chuyen trang thai phong ve CHECKIN khi chua the CHECKOUT**/
                if($itm['status'] == 0 and !isset($arr_can_not_checkout[$itm['rr_id']]))
                {
                    $arr_can_not_checkout[$cur_reservation_id] = array('room_name'=>$reservation_room_check[$itm['rr_id']]['room_name'],
                                                                'not_create_folio' => 1,
                                                                'not_deposit_group' => 0,
                                                                'folios_not_paid'=>array());
                }
			}
            /** END - Check tao hoa don **/
            
            /** START - Check thanh toan **/
            $bill = DB::fetch_all(' select folio.id ||\'_\'||traveller_folio.reservation_room_id as id,
                                        folio.id as folio_id,
                                        folio.total,
                                        folio.reservation_traveller_id,
                                        case when traveller_folio.add_payment=1
                                        then reservation_room.id
                                        else traveller_folio.reservation_room_id
                                        end reservation_room_id
                                    from folio
                                        inner join traveller_folio on traveller_folio.folio_id = folio.id 
                                        left join reservation_traveller on reservation_traveller.id = folio.reservation_traveller_id
                                        left join reservation_room on reservation_room.id = reservation_traveller.reservation_room_id
                                    where (
                                            (reservation_traveller.reservation_room_id in ('.$rr_ids.') and folio.customer_id is null)
                                            or
                                            (traveller_folio.reservation_room_id in ('.$rr_ids.') and folio.customer_id is not null)
                                        )
                                        and folio.total != 0');                         
            foreach($bill as $key=>$value)
            {
                /** START truong hop doi phong thi lay id la phong chang cuoi de co the chuyen trang thai phong ve CHECKIN khi chua the CHECKOUT**/
                if($value['reservation_traveller_id'] !='')
                {
                    $res_tre_room = DB::fetch('select id, reservation_room_id,reservation_id from reservation_traveller where id='.$value['reservation_traveller_id']);
                }
                if($value['reservation_traveller_id']=='' or (isset($res_tre_room['reservation_room_id']) and $res_tre_room['reservation_room_id']==Url::get('id_check')) or (isset($res_tre_room['reservation_id']) and ($res_tre_room['reservation_room_id'] == $value['reservation_room_id'])and $res_tre_room['reservation_id']==Url::get('id_check')))
                {
                   
                    if(Url::get('is_reservation'))
                    {
                        $cur_reservation_id = $value['reservation_room_id'];  
                    }
                    else
                    {
                        $cur_reservation_id = Url::get('id_check');    
                    }
                    /** END truong hop doi phong thi lay id la phong chang cuoi de co the chuyen trang thai phong ve CHECKIN khi chua the CHECKOUT**/
                    if(!DB::exists('select id from payment where folio_id ='.$value['folio_id']))
                    {
                        if(!isset($arr_can_not_checkout[$cur_reservation_id]))
                        {
                            $arr_can_not_checkout[$cur_reservation_id] = array('room_name'=>$reservation_room_check[$value['reservation_room_id']]['room_name'],
                                                                        'not_create_folio' => 0,
                                                                        'folios_not_paid'=>array($value['folio_id']=>array('payment'=>0,'id'=>$value['folio_id'])));
                        }
                        else
                        {
                            $arr_can_not_checkout[$cur_reservation_id]['folios_not_paid'][$value['folio_id']]=array('payment'=>0,'id'=>$value['folio_id']);
                        }
                    }
                    else
                    {
                        $payment = DB::fetch('select sum(amount*exchange_rate) as amount from payment where folio_id ='.$value['folio_id']);
                        if( (HOTEL_CURRENCY == 'VND' and ($value['total'] - $payment['amount']) > 1000) or (HOTEL_CURRENCY == 'USD' and ($value['total'] - $payment['amount']) > 0.1))
                        {
                            if(!isset($arr_can_not_checkout[$cur_reservation_id]))
                            {
                                $arr_can_not_checkout[$cur_reservation_id] = array('room_name'=>$reservation_room_check[$value['reservation_room_id']]['room_name'],
                                                                            'not_create_folio' => 0,
                                                                            'not_deposit_group' => 0,
                                                                            'folios_not_paid'=>array($value['folio_id']=>array('payment'=>1,'id'=>$value['folio_id'])));
                            }
                            else
                            {
                                $arr_can_not_checkout[$cur_reservation_id]['folios_not_paid'][$value['folio_id']]=array('payment'=>1,'id'=>$value['folio_id']);
                            }
                        }
                    }
                }
            }
            
            $bill_k = DB::fetch_all(' select folio.id ||\'_\'||traveller_folio.reservation_room_id as id,
                                        folio.id as folio_id,
                                        folio.total,
                                        folio.reservation_traveller_id,
                                        reservation_room.id as reservation_room_id
                                    from folio 
                                        inner join traveller_folio on traveller_folio.folio_id = folio.id 
                                        inner join reservation_traveller on reservation_traveller.id = folio.reservation_traveller_id
                                        inner join traveller on traveller.id= reservation_traveller.traveller_id
                                        inner join reservation_room on traveller.id=reservation_room.traveller_id
                                    where (
                                            folio.reservation_room_id is null and reservation_room.id in ('.$rr_ids.') and reservation_traveller.reservation_room_id in ('.$rr_ids.')
                                        )
                                        and folio.total != 0');                   
            if($bill_k)
            {
                foreach($bill_k as $key=>$value)
                {
                    if(Url::get('is_reservation'))
                    {
                        $cur_reservation_id = $value['reservation_room_id'];  
                    }
                    else
                    {
                        $cur_reservation_id = Url::get('id_check');    
                    }
                    /** END truong hop doi phong thi lay id la phong chang cuoi de co the chuyen trang thai phong ve CHECKIN khi chua the CHECKOUT**/
                    if(!DB::exists('select id from payment where folio_id ='.$value['folio_id']))
                    {
                        if(!isset($arr_can_not_checkout[$cur_reservation_id]))
                        {
                            $arr_can_not_checkout[$cur_reservation_id] = array('room_name'=>$reservation_room_check[$value['reservation_room_id']]['room_name'],
                                                                        'not_create_folio' => 0,
                                                                        'not_deposit_group' => 0,
                                                                        'folios_not_paid'=>array($value['folio_id']=>array('payment'=>0,'id'=>$value['folio_id'])));
                        }
                        else
                        {
                            $arr_can_not_checkout[$cur_reservation_id]['folios_not_paid'][$value['folio_id']]=array('payment'=>0,'id'=>$value['folio_id']);
                        }
                    }
                    else
                    {
                        $payment = DB::fetch('select sum(amount*exchange_rate) as amount from payment where folio_id ='.$value['folio_id']);
                        if( (HOTEL_CURRENCY == 'VND' and ($value['total'] - $payment['amount']) > 1000) or (HOTEL_CURRENCY == 'USD' and ($value['total'] - $payment['amount']) > 0.1))
                        {
                            if(!isset($arr_can_not_checkout[$cur_reservation_id]))
                            {
                                $arr_can_not_checkout[$cur_reservation_id] = array('room_name'=>$reservation_room_check[$value['reservation_room_id']]['room_name'],
                                                                            'not_create_folio' => 0,
                                                                            'not_deposit_group' => 0,
                                                                            'folios_not_paid'=>array($value['folio_id']=>array('payment'=>1,'id'=>$value['folio_id'])));
                            }
                            else
                            {
                                $arr_can_not_checkout[$cur_reservation_id]['folios_not_paid'][$value['folio_id']]=array('payment'=>1,'id'=>$value['folio_id']);
                            }
                        }
                    }
                }
            }
 
            /** END - Check thanh toan **/
            
            if($arr_can_not_checkout)
                echo json_encode($arr_can_not_checkout);
            else
                echo $check;
            exit();
        }
        if(Url::get('status_edit') == 'CANCEL')
        {
             require_once 'packages/hotel/packages/reception/modules/CreateTravellerFolio/get_reservation_room.php';
            if(!Url::get('is_reservation'))
            {
                $reservation_room_check = DB::fetch('select reservation_room.*,room.name as room_name from reservation_room inner join room on room.id = reservation_room.room_id where reservation_room.id = '.Url::get('id_check'));
                $r_id = $reservation_room_check['reservation_id'];
            }
            else
            {
                $r_id = Url::get('id_check');
            }
            $check = "";
            $deposit_group=DB::fetch('select sum(amount*exchange_rate) as deposit from payment where type_dps = \'GROUP\' and type = \'RESERVATION\' and reservation_id ='.$r_id,'deposit');
            $detail_dps_group=DB::fetch('select sum(amount) as total from traveller_folio where traveller_folio.type=\'DEPOSIT_GROUP\' and traveller_folio.reservation_id ='.$r_id,'total');
            if(abs($deposit_group-$detail_dps_group)>100 and $deposit_group>0)
            {
                $check = 'NOT_CANCEL';
            }
            echo $check;
            exit();
        }   
        /** END: check folio**/
	    if(Url::get('res_r_id'))
        {
            $time = (substr(Url::get('time_in'),0,2)*3600)+ Date_time::to_time(Url::get('arrival_time'));
            
            if(Url::get('res_r_id') !='' && Url::get('arrival_time')!='' && Url::get('departure_time') !='')
            {
           	    $sql = '
    				SELECT
    					reservation_traveller.id
    					,reservation_traveller.arrival_time
    				FROM
    					reservation_traveller
    					inner join reservation_room on reservation_room.id = reservation_traveller.reservation_room_id
    				WHERE
    					reservation_room_id = '.Url::get('res_r_id').'
    			';
                $old_travellers = DB::fetch_all($sql);
                foreach($old_travellers as $ke => $val)
                {
                    
                    
                    if($time < $val['arrival_time'])
                    {
                        echo 'sucess';
                        break;
                    }
                    if($time >= $val['arrival_time'])
                    {
                        break;
                    }
                }
            
            }
            exit();
        }   
	    if(Url::get('rr_id_trevaller'))
        {
            if(Url::get('rr_id_trevaller') !='' && Url::get('arrival_date')!='' && Url::get('departure_date') !='')
            {
           	    DB::update('reservation_traveller',array(
				'arrival_time'=>(substr(Url::get('arrival_time'),0,2)*3600) + (substr(Url::get('arrival_time'),3,2)*60)+ Date_time::to_time(Url::get('arrival_date')),
				'arrival_date'=>Date_time::to_orc_date(Url::get('arrival_date')),
				'departure_time'=>(substr(Url::get('departure_time'),0,2)*3600) + (substr(Url::get('departure_time'),3,2)*60) +Date_time::to_time(Url::get('departure_date')) ,  
				'departure_date'=>Date_time::to_orc_date(Url::get('departure_date')),
				'status'=>'CHECKIN'
		        ),' reservation_room_id = '.Url::get('rr_id_trevaller').'');
            }
        }
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			if(URL::check(array('delete_selected','selected_ids')) and is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0 and User::can_delete(false,ANY_CATEGORY))
			{
				if(sizeof(URL::get('selected_ids'))>1)
				{
					require_once 'forms/delete_selected.php';
					$this->add_form(new DeleteSelectedReservationForm());
				}
				else
				{
					$ids = URL::get('selected_ids');
					$_REQUEST['id'] = $ids[0];
					require_once 'forms/delete.php';
					$this->add_form(new DeleteReservationForm());
				}
			}
			else
			if(
				(
					Url::check('id') and $reservation = DB::exists_id('reservation',Url::iget('id')) and
					(
						(
							URL::check(array('cmd'=>'delete'))
							and User::can_delete(false,ANY_CATEGORY)
						)
						or
						(
							URL::check(array('cmd'=>'edit'))
							and User::can_edit(false,ANY_CATEGORY)
						)
					)
				)
				or
				(URL::check(array('cmd'=>'add')) and User::can_add(false,ANY_CATEGORY))
                or
				(URL::check(array('cmd'=>'booking_confirm')) and User::can_add(false,ANY_CATEGORY))
                or
                (URL::check(array('cmd'=>'booking_confirm_1')) and User::can_add(false,ANY_CATEGORY))
                or
				(URL::check(array('cmd'=>'group_registration')) and User::can_add(false,ANY_CATEGORY))
				or
				(URL::check(array('cmd'=>'invoice')) and User::can_view(false,ANY_CATEGORY))
				or
				(URL::check(array('cmd'=>'tour_invoice')) and User::can_view(false,ANY_CATEGORY))
				or
				(URL::check(array('cmd'=>'group_invoice')) and User::can_view(false,ANY_CATEGORY))
				or
				(URL::check(array('cmd'=>'check_availability')) and User::can_view(false,ANY_CATEGORY))
				or
				(URL::check(array('cmd'=>'rooming_list')) and User::can_view(false,ANY_CATEGORY))
				or
				(URL::check(array('cmd'=>'guest_registration_card')) and User::can_view(false,ANY_CATEGORY))
				or
                (URL::check(array('cmd'=>'guest_registration_card_new')) and User::can_view(false,ANY_CATEGORY))
				or
				(URL::check(array('cmd'=>'pay_by_currency')) and User::can_edit(false,ANY_CATEGORY))
				or !URL::check('cmd')
				or
				(URL::check(array('cmd'=>'change_guest')) and User::can_edit(false,ANY_CATEGORY))
				or
				(URL::check(array('cmd'=>'checkout_guest')) and User::can_edit(false,ANY_CATEGORY))
				or
				(URL::check(array('cmd'=>'waiting_list')) and User::can_edit(false,ANY_CATEGORY))
				or
				(URL::check(array('cmd'=>'asign_room')) and User::can_edit(false,ANY_CATEGORY))
                or
				(URL::check(array('cmd'=>'print')) and User::can_edit(false,ANY_CATEGORY))
                or
				(URL::check(array('cmd'=>'invoice_information')) and User::can_view(false,ANY_CATEGORY))
                or
				(URL::check(array('cmd'=>'consolidated_invoice')) and User::can_view(false,ANY_CATEGORY))
				or
				URL::check(array('cmd'=>'import_traveller'))
                or
				(URL::check(array('cmd'=>'thank_letter')) and User::can_add(false,ANY_CATEGORY))
			)
			{
			
				switch(URL::get('cmd'))
				{ 
                case 'booking_confirm_1':
					require_once 'forms/book_confirm_1.php';
                    $this->add_form(new BookingConfirm1Form());break;
                case 'booking_confirm':                                                          
					require_once 'forms/book_confirm.php';
                    $this->add_form(new BookingConfirmForm());break;         
					require_once 'forms/book_confirm.php';
                    $this->add_form(new BookingConfirmForm());break;
                case 'group_registration':
					require_once 'forms/group_regist.php';
                    $this->add_form(new GroupRegistForm());break;
                case 'thank_letter':                                                          
					require_once 'forms/thank_letter.php';
                    $this->add_form(new ThankLetterForm());break;       
                    
				case 'delete':
					require_once 'forms/delete.php';
					$this->add_form(new DeleteReservationForm());break;
				case 'edit':
					if(Url::get('r_r_id') && Url::get('traveller_id') && Url::get('id') && Url::get('act')=='checkout_guest')
                    {
						DB::update('reservation_traveller',array('status'=>'CHECKOUT','departure_time'=>time(),'departure_date'=>Date_Time::to_orc_date(date('d/m/y'))),' id='.Url::get('traveller_id').'');
						DB::query('update reservation_room set reservation_room.adult=reservation_room.adult-1 where id='.Url::get('r_r_id').'');
						URL::redirect_current(array('cmd'=>'edit','id'=>Url::get('id'),'r_r_id'=>Url::get('r_r_id')));
					}
					require_once 'forms/edit.php';
					$this->add_form(new EditReservationForm());break;
				case 'add':
					require_once 'forms/add.php';
					$this->add_form(new AddReservationForm());break;
				case 'import_traveller':
					require_once 'forms/import.php';
					$this->add_form(new ImportTravellerForm());break;		
				case 'check_availability':
					require_once 'forms/check_availability.php';
					$this->add_form(new CheckAvailabilityForm());break;
				case 'rooming_list':
					require_once 'forms/rooming_list.php';
					$this->add_form(new RoomingListForm());break;
				case 'guest_registration_card':
					require_once 'forms/guest_registration_card.php';
					$this->add_form(new GuestRegistrationCardForm());break;
                case 'guest_registration_card_new':
					require_once 'forms/guest_registration_card_new.php';
					$this->add_form(new GuestRegistrationCardFormNew());break;
				case 'asign_room':
					require_once 'forms/asign_room.php';
					$this->add_form(new AsignReservationForm());break;
				case 'invoice':
					if(Url::get('id'))
					{
						$this->update_phone();
						require_once 'forms/invoice.php';
						$this->add_form(new InvoiceReservationForm());break;
					}
					else
					{
						Url::redirect_current();
					}
				case 'pay_by_currency':
					if(Url::get('id'))
					{
						$this->update_phone();
						require_once 'forms/pay_by_currency.php';
						$this->add_form(new PayByCurrencyReservationForm());break;
					}
					else
					{
						Url::redirect_current();
					}
				case 'tour_invoice':
					if(Url::get('id'))
					{
						$this->update_phone();
						require_once 'forms/tour_invoice.php';
						$this->add_form(new TourInvoiceReservationForm());break;
					}
					else
					{
						Url::redirect_current();
					}
				case 'group_invoice':
					if(Url::get('id'))
					{
						$this->update_phone();
						require_once 'forms/group_invoice.php';
						$this->add_form(new GroupInvoiceReservationForm());break;
					}
					else
					{
						Url::redirect_current();
					}
				case 'change_guest':
					if(Url::get('rr_id')){
						require_once 'forms/change_traveller.php';
						$this->add_form(new ChangeTravellerReservationForm());break;
					}else{
						Url::redirect_current();
					}
                case 'invoice_information':
                    if(Url::get('id'))
                    {
						require_once 'forms/invoice_information.php';
						$this->add_form(new InvoiceInformationForm());
                        break;
					}
                    else
						Url::redirect_current();  
                case 'consolidated_invoice':
                    if(Url::get('id'))
                    {
						require_once 'forms/consolidated_invoice.php';
						$this->add_form(new ConsolidatedInvoiceForm());
                        break;
					}
                    else
						Url::redirect_current();         
				case 'waiting_list':
					//if(Url::get('rr_id')){
						require_once 'forms/waiting_list.php';
						$this->add_form(new WaitingListReservationForm());break;
					//}else{
						Url::redirect_current();
					//}
                case 'print':
					if(Url::get('id') && Url::get('r_r_id'))
                    {
						require_once 'forms/print.php';
						$this->add_form(new PrintReservationForm());
                        break;
					}
                    else
						Url::redirect_current();
				default:
					{
						if(Url::get('page')=='change_room'){
							require_once 'forms/change_room.php';
							$this->add_form(new ChangeRoomForm());break;
						}else{
                           
							require_once 'forms/list.php';
							$this->add_form(new ListReservationForm());
						}
					}
					break;
				}
			}
			else
			{
				Url::redirect_current();
			}
		}
		else
		{
			URL::access_denied();
		}
	}
	function update_phone(){
		//--------------------------------------------------------
		/*require_once 'packages/hotel/packages/reception/modules/TelephoneList/db.php';
		$file_name = 'http://letan01/DATA/2009/'.date('dmY',time()).'.001';
		TelephoneListDB::update_telephone_daily($file_name);*/
		//--------------------------------------------------------
	}
}
?>