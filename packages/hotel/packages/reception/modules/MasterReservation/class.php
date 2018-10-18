<?php 
class MasterReservation extends Module
{
	function MasterReservation($row)
	{
	    if(isset($_REQUEST['add_service']))
        {
            $reservation_room = DB::fetch('select * from reservation_room where id='.Url::get('reservation_room_id'));
            $data = array('status'=>1,'messenge'=>'');
            if($reservation_room['status']!='CHECKIN' and $reservation_room['status']!='BOOKED')
            {
                $data['status'] = 0;
                $data['messenge'] = 'Phòng checkout không được thêm dịch vụ '.Url::get('service');
            }
            else
            {
                if($reservation_room['status']=='BOOKED' and (Url::get('service')=='MINIBAR' OR Url::get('service')=='MINIBAR' OR Url::get('service')=='MINIBAR'))
                {
                    $data['status'] = 0;
                    $data['messenge'] = 'Phòng BOOKED không được thêm dịch vụ '.Url::get('service');
                }
            }
            echo json_encode($data);
            exit();
        }
        
	    if(isset($_REQUEST['fast_operation'])){
	       if(Url::get('operation')=='ASIGN_ROOM'){
	           $asign_room = array('status'=>1,'messenge'=>'','data'=>0);
	           if($reservation_room = DB::fetch('select room_id,status,reservation_id from reservation_room  where id='.Url::get('reservation_room_id'))){
	               if($reservation_room['room_id']!=''){
	                   $asign_room['status'] = 0;
                       $asign_room['messenge'] = 'không thực hiện được thao tác , đặt phòng đã được gán phòng trước đó';
	               }elseif($reservation_room['status']!='BOOKED'){
                        $asign_room['status'] = 0;
                        $asign_room['messenge'] = 'không thực hiện được thao tác , Đặt phòng đã được '.$reservation_room['status'].' trước đó';
	               }else{
	                   $asign_room['data'] = $reservation_room['reservation_id'];
	               }
	            }else{
	               $asign_room['status'] = 0;
                    $asign_room['messenge'] = 'không thực hiện được thao tác , phòng không tồn tại';
	            }
               echo json_encode($asign_room);
               exit();
	       }elseif(Url::get('operation')=='CHECKIN'){
	            if($reservation_room = DB::fetch('select room_id,status from reservation_room where id='.Url::get('reservation_room_id'))){
	               if($reservation_room['room_id']==''){
	                   $arr_can_not_checkin[1]=array(
                            'status'=>'room not asign',
                            'error' => 'không thực hiện được thao tác , Đặt phòng chưa gán phòng không thể CHECKIN'
                        );
                        echo json_encode($arr_can_not_checkin);
                        exit();
	               }elseif($reservation_room['status']!='BOOKED'){
	                   $arr_can_not_checkin[1]=array(
                            'status'=>'conflig',
                            'error' => 'không thực hiện được thao tác , Đặt phòng đã được '.$reservation_room['status'].' trước đó'
                        );
                        echo json_encode($arr_can_not_checkin);
                        exit();
	               }
                   
	            }
                
	            $rr_arr = array();
                $rr_arr = DB::fetch('select 
                            reservation_room.*, room_level.name as room_level_name, room.name as room_name
                        from reservation_room 
                        inner join room_level on reservation_room.room_level_id = room_level.id
                        inner join room on reservation_room.room_id = room.id
                        where 
                            reservation_room.id='.Url::get('reservation_room_id'));
                /** check conflig phong CI **/
                $cond = 'and room.portal_id = \''.PORTAL_ID.'\' AND r.status=\'CHECKIN\' AND R.room_id=\''.$rr_arr['room_id'].'\' ';
                $sql = '
    					SELECT 
    						R.id,R.reservation_id,room.name as room_name
    					FROM 
    						reservation_room R
    						INNER JOIN room ON room.id = R.room_id
    					WHERE 
    						R.id!='.Url::get('reservation_room_id').'
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
                if(DB::fetch('select * from room_status where house_status = \'DIRTY\' AND room_id = '.$rr_arr['room_id'].''))
                {
                    $arr_can_not_checkin[4]=array(
                    'status'=>'room_dirty',
                    'error' => 'Không thể checkin do phòng đang DIRTY'
                    );	
                    $check = true;
                }
                /** check phong clean **/
                if(DB::fetch('select * from room_status where house_status = \'CLEAN\' AND room_id = '.$rr_arr['room_id'].' AND in_date >= \''.$rr_arr['arrival_time'].'\' '))
                {
                    $arr_can_not_checkin[5]=array(
                    'status'=>'room_clean',
                    'error' => 'Không thể checkin do phòng đang CLEAN'
                    );	
                    $check = true;
                }
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
                    $r_r = DB::fetch('select TO_CHAR(arrival_time,\'DD/MM/YYYY\') as arrival_time, reservation_id from reservation_room where id='.Url::get('reservation_room_id'));
                    DB::update('reservation_room',array(
                                                    'status'=>'CHECKIN',
                                                    'confirm'=> '1',
                                                    'checked_in_user_id'=>User::id(),
                                                    'time_in'=>time(),
                                                    'arrival_time' =>Date_Time::to_orc_date(date('d/m/Y'))
                                                ),'id='.Url::get('reservation_room_id'));
                    $reservation_id = DB::fetch('select reservation_id from reservation_room where id='.Url::get('reservation_room_id'),'reservation_id');
                    DB::update('reservation',array('last_time'=>time(),'lastest_user_id'=>User::id()),'id='.$reservation_id);
                    /** update bang room_status **/
                    $log_id = System::log('Edit','Checkin Fast reservation room #'.Url::get('reservation_room_id')." arrival time ".$r_r['arrival_time']." =>".Date_Time::to_orc_date(date('d/m/Y'))." reservation #".$r_r['reservation_id']." UserID #" .Session::get('user_id'));
                    System::history_log('RECODE',$reservation_id,$log_id);
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
                                $auto_late_checkin_price = DB::fetch('select change_price from room_status where reservation_room_id = '.Url::get('reservation_room_id').' and in_date=\''.Date_Time::to_orc_date(date('d/m/Y')).'\'','change_price');
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
	       }elseif(Url::get('operation')=='CHECKOUT'){
	           if($reservation_room = DB::fetch('select room_id,status from reservation_room where id='.Url::get('reservation_room_id'))){
	               if($reservation_room['room_id']==''){
	                   $arr_can_not_checkout[1]=array(
                            'status'=>'room not asign',
                            'error' => 'không thực hiện được thao tác , Đặt phòng chưa gán phòng không thể CHECKOUT'
                        );
                        echo json_encode($arr_can_not_checkout);
                        exit();
	               }elseif($reservation_room['status']!='CHECKIN'){
	                   $arr_can_not_checkout[1]=array(
                            'status'=>'conflig',
                            'error' => 'không thực hiện được thao tác , Đặt phòng đã được '.$reservation_room['status'].' trước đó'
                        );
                        echo json_encode($arr_can_not_checkout);
                        exit();
	               }
	            }
                
	           /** START - Kiểm tra xem phòng đã được tạo hóa đơn chưa **/
                require_once 'packages/hotel/packages/reception/modules/CreateTravellerFolio/get_reservation_room.php';
                require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
                $reservation_room_check = DB::fetch('
                                    SELECT 
                                        reservation_room.*,
                                        room.name as room_name,
                                        room.id as room_id,
                                        mice_reservation.id as mice  
                                    FROM 
                                        reservation_room 
                                        inner join reservation on reservation.id=reservation_room.reservation_id
                                        left join mice_reservation on mice_reservation.id = reservation.mice_reservation_id
                                        INNER JOIN room on room.id = reservation_room.room_id 
                                    WHERE 
                                        reservation_room.id = '.Url::get('reservation_room_id')
                );
                    $r_id = $reservation_room_check['reservation_id'];
                    $room_id = $reservation_room_check['room_id'];
                    $rr_ids = Url::get('reservation_room_id').($reservation_room_check['change_room_from_rr']?",".$reservation_room_check['change_room_from_rr']:"");
                    $reservation_room_check = DB::fetch_all('SELECT reservation_room.*,room.name as room_name FROM reservation_room inner join room on room.id = reservation_room.room_id WHERE reservation_room.id in ('.$rr_ids.')');
                    $folios = DB::fetch_all('
                                        SELECT
        								    (traveller_folio.type || \'_\' || traveller_folio.invoice_id) as id,
                                            traveller_folio.type,
                                            traveller_folio.invoice_id,
                                            sum(traveller_folio.amount) as amount,
                                            sum(traveller_folio.total_amount) as total_amount,
                                            sum(traveller_folio.percent) as percent
        								FROM 
                                            traveller_folio
        								    INNER JOIN folio ON folio.id = traveller_folio.folio_id
        								WHERE 
                                            1>0 AND traveller_folio.reservation_room_id in ('.$rr_ids.')
        								GROUP BY
        								    traveller_folio.invoice_id,
                                            traveller_folio.type
                    ');
                    $arr_rr_ids = explode(',',$rr_ids);
                    $items = array();
                    foreach($arr_rr_ids as $k => $v)
                    {
                        if(DB::exists('SELECT * FROM reservation_room WHERE id = '.$v." and reservation_id = ".$r_id))
                            $items += get_reservation_room_detail($v,$folios);
                    }
                    $arr_can_not_checkout = array();
                    foreach($items as $k => $itm)
                    {
                        $cur_reservation_id = Url::get('reservation_room_id');
                        if($itm['status'] == 0 and !isset($arr_can_not_checkout[$itm['rr_id']]))
                        {
                            $arr_can_not_checkout[$cur_reservation_id] = array(
                                                                        'room_name'=>$reservation_room_check[$itm['rr_id']]['room_name'],
                                                                        'not_create_folio' => 1,
                                                                        'not_deposit_group' => 0,
                                                                        'folios_not_paid'=>array());
                        }
        			}
                    /** START - Kiểm tra xem phòng đã được tạo hóa đơn chưa **/
                    /** START - Kiểm tra xem phòng có phải là phòng checkout cuối của recode hay không và nếu là phòng cuối thì kiểm đã tạo hóa đơn đặt cọc nhóm chưa **/
                    $reservation_room_check_list = DB::fetch_all('
                                                    SELECT
                                                        reservation_room.*,
                                                        room.name as room_name
                                                    FROM 
                                                        reservation_room 
                                                        INNER JOIN room on room.id = reservation_room.room_id
                                                        INNER JOIN reservation on reservation_room.reservation_id = reservation.id 
                                                    WHERE 
                                                        reservation_room.reservation_id ='.$r_id.'
                                                        and reservation_room.id not in ('.$rr_ids.')
                                                        and reservation_room.status  != \'CHECKOUT\'
                    ');
                    if(empty($reservation_room_check_list))
                    {
                        $cur_reservation_id = Url::get('reservation_room_id');
                        $deposit_group=DB::fetch('
                                                SELECT 
                                                    deposit
                                                FROM 
                                                    reservation 
                                                WHERE id ='.$r_id
                        ,'deposit');
                        $detail_dps_group=DB::fetch('
                                                SELECT
                                                    sum(amount) as total 
                                                FROM 
                                                    traveller_folio 
                                                WHERE 
                                                    traveller_folio.type=\'DEPOSIT_GROUP\' 
                                                    and traveller_folio.reservation_id ='.$r_id
                        ,'total');
                        if($deposit_group > $detail_dps_group)
                        {
                            foreach($items as $k => $itm)
                            {
                                $arr_can_not_checkout[$cur_reservation_id] = array(
                                                                                'room_name'=>$reservation_room_check[$itm['rr_id']]['room_name'],
                                                                                'not_create_folio' => 0,
                                                                                'not_deposit_group' => 1,
                                                                                'folios_not_paid'=>array());
                			}
                        }                                    
                    }
                    /** START - Kiểm tra xem phòng có phải là phòng checkout cuối của recode hay không và đã tạo hóa đơn đặt cọc nhóm chưa **/
                    /** START - Kiểm tra xem phòng đã tạo thanh toan chưa **/
                    $bill = DB::fetch_all(' SELECT folio.id ||\'_\'||traveller_folio.reservation_room_id as id,
                                                folio.id as folio_id,
                                                folio.total,
                                                folio.reservation_traveller_id,
                                                case when traveller_folio.add_payment=1
                                                then reservation_room.id
                                                else traveller_folio.reservation_room_id
                                                end reservation_room_id
                                            FROM folio
                                                inner join traveller_folio on traveller_folio.folio_id = folio.id 
                                                left join reservation_traveller on reservation_traveller.id = folio.reservation_traveller_id
                                                left join reservation_room on reservation_room.id = reservation_traveller.reservation_room_id
                                            WHERE (
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
                            $res_tre_room = DB::fetch('SELECT id, reservation_room_id,reservation_id FROM reservation_traveller WHERE id='.$value['reservation_traveller_id']);
                        }
                        if($value['reservation_traveller_id']=='' or (isset($res_tre_room['reservation_room_id']) and $res_tre_room['reservation_room_id']==Url::get('reservation_room_id')) or (isset($res_tre_room['reservation_id']) and ($res_tre_room['reservation_room_id'] == $value['reservation_room_id'])and $res_tre_room['reservation_id']==Url::get('reservation_room_id')))
                        {
                           
                            $cur_reservation_id = Url::get('reservation_room_id');
                            /** END truong hop doi phong thi lay id la phong chang cuoi de co the chuyen trang thai phong ve CHECKIN khi chua the CHECKOUT**/
                            if(!DB::exists('SELECT id FROM payment WHERE folio_id ='.$value['folio_id']))
                            {
                                if(!isset($arr_can_not_checkout[$cur_reservation_id]))
                                {
                                    $arr_can_not_checkout[$cur_reservation_id] = array(
                                                                                'room_name'=>$reservation_room_check[$value['reservation_room_id']]['room_name'],
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
                                $payment = DB::fetch('SELECT sum(amount*exchange_rate) as amount FROM payment WHERE folio_id ='.$value['folio_id']);
                                if( (HOTEL_CURRENCY == 'VND' and ($value['total'] - $payment['amount']) > 1000) or (HOTEL_CURRENCY == 'USD' and ($value['total'] - $payment['amount']) > 0.1))
                                {
                                    if(!isset($arr_can_not_checkout[$cur_reservation_id]))
                                    {
                                        $arr_can_not_checkout[$cur_reservation_id] = array(
                                                                                    'room_name'=>$reservation_room_check[$value['reservation_room_id']]['room_name'],
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
                    
                    $bill_k = DB::fetch_all('
                                            SELECT 
                                                folio.id ||\'_\'||traveller_folio.reservation_room_id as id,
                                                folio.id as folio_id,
                                                folio.total,
                                                folio.reservation_traveller_id,
                                                reservation_room.id as reservation_room_id
                                            FROM folio 
                                                inner join traveller_folio on traveller_folio.folio_id = folio.id 
                                                inner join reservation_traveller on reservation_traveller.id = folio.reservation_traveller_id
                                                inner join traveller on traveller.id= reservation_traveller.traveller_id
                                                inner join reservation_room on traveller.id=reservation_room.traveller_id
                                            WHERE (
                                                    folio.reservation_room_id is null and reservation_room.id in ('.$rr_ids.') and reservation_traveller.reservation_room_id in ('.$rr_ids.')
                                                )
                                                and folio.total != 0');                   
                    if($bill_k)
                    {
                        foreach($bill_k as $key=>$value)
                        {
                            $cur_reservation_id = Url::get('reservation_room_id');
                            /** END truong hop doi phong thi lay id la phong chang cuoi de co the chuyen trang thai phong ve CHECKIN khi chua the CHECKOUT**/
                            if(!DB::exists('SELECT id FROM payment WHERE folio_id ='.$value['folio_id']))
                            {
                                if(!isset($arr_can_not_checkout[$cur_reservation_id]))
                                {
                                    $arr_can_not_checkout[$cur_reservation_id] = array(
                                                                                'room_name' => $reservation_room_check[$value['reservation_room_id']]['room_name'],
                                                                                'not_create_folio' => 0,
                                                                                'not_deposit_group' => 0,
                                                                                'folios_not_paid' =>array($value['folio_id']=>array('payment'=>0,'id'=>$value['folio_id'])));
                                }else
                                {
                                    $arr_can_not_checkout[$cur_reservation_id]['folios_not_paid'][$value['folio_id']]=array('payment'=>0,'id'=>$value['folio_id']);
                                }
                            }else
                            {
                                $payment = DB::fetch('SELECT sum(amount*exchange_rate) as amount FROM payment WHERE folio_id ='.$value['folio_id']);
                                if( (HOTEL_CURRENCY == 'VND' and ($value['total'] - $payment['amount']) > 1000) or (HOTEL_CURRENCY == 'USD' and ($value['total'] - $payment['amount']) > 0.1))
                                {
                                    if(!isset($arr_can_not_checkout[$cur_reservation_id]))
                                    {
                                        $arr_can_not_checkout[$cur_reservation_id] = array(
                                                                                    'room_name'=>$reservation_room_check[$value['reservation_room_id']]['room_name'],
                                                                                    'not_create_folio' => 0,
                                                                                    'not_deposit_group' => 0,
                                                                                    'folios_not_paid'=>array($value['folio_id']=>array('payment'=>1,'id'=>$value['folio_id'])));
                                    }else
                                    {
                                        $arr_can_not_checkout[$cur_reservation_id]['folios_not_paid'][$value['folio_id']]=array('payment'=>1,'id'=>$value['folio_id']);
                                    }
                                }
                            }
                        }
                    }
                if(DB::exists('select reservation_room.id from reservation_room inner join reservation on reservation.id=reservation_room.reservation_id inner join mice_reservation on mice_reservation.id=reservation.mice_reservation_id where reservation_room.id='.Url::get('reservation_room_id'))){
                    $arr_can_not_checkout = array();
                }
                /** START - Kiểm tra xem phòng đã tạo thanh toan chưa **/
                if($arr_can_not_checkout)
                {
                    echo json_encode($arr_can_not_checkout);
                }
                else
                {
                    $reservation_room  = DB::fetch('SELECT reservation_room.* FROM reservation_room WHERE reservation_room.id ='.Url::get('reservation_room_id').'');
                    $from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['arrival_time']  ,'/'));
                    $to   = Date_Time::to_time(date('d/m/Y', time())); 
                    $to_old = Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['departure_time']  ,'/'));
                    $d = $from;
                    $status = 'CHECKOUT';
                   	$house_status = 'DIRTY';
                    while($d>=$from and $d<=$to_old)
                	{
                        if($d>=$from and $d<=$to)
                        {
                            $sql = 'SELECT * FROM room_status WHERE in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$d)).'\' and reservation_room_id='.Url::get('reservation_room_id').'';
                            if($room_status = DB::fetch($sql))
                    		{
                    			 DB::update_id('room_status',
                    				(($status=="CHECKOUT" and $d==$to)?array('house_status'=>$house_status):array())+
                                    (($status=="CHECKOUT" and $d==$to)?array('change_price'=>0):array())+
                    				(($status=="CHECKOUT" and $reservation_room['arrival_time'] == $reservation_room['departure_time'])?array('closed_time'=>time()):array())+
                    				array(
                    				'room_id'=>$room_id,
                    				'status'=>'OCCUPIED',
                    				'reservation_id'=>$r_id,
                    				'house_status'=>$house_status,
                    				'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
                    				),$room_status['id']
                    			);
                    		}
                        }
                        else
                        {
                            DB::delete('room_status', 'reservation_room_id =\''.Url::get('reservation_room_id').'\' and in_date =\''.Date_Time::to_orc_date(date('d/m/Y',$d)).'\'');
                        }
                        $d=$d+(3600*24);
                    }
                    DB::update("room_status",array("house_status"=>"DIRTY")," room_id = ".$room_id." AND in_date='".Date_Time::convert_time_to_ora_date(time())."'");
                    $update = array(
                                'time_out' => time(),
                                'departure_time' => Date_Time::convert_time_to_ora_date(time()),
                                'status' => $status
                    );
                    DB::update('reservation_room',$update,'id='.Url::get('reservation_room_id'));
                    DB::update('reservation',array('last_time'=>time(),'lastest_user_id'=>User::id()),'id='.$r_id);
                    $log_id = System::log('Edit','Checkout Fast reservation room #'.Url::get('reservation_room_id')." departure_time ".$reservation_room['departure_time']." =>".Date_Time::convert_time_to_ora_date(time())." reservation #".$r_id." UserID #" .Session::get('user_id'));
                    System::history_log('RECODE',$r_id,$log_id);
                    echo '';
                }
                exit();
	       }
           exit();
	    }
	    if(isset($_REQUEST['search_data'])){
           $cond = '1=1';
           if(Url::get('reservation_id'))
                $cond .= ' AND reservation.id='.Url::get('reservation_id');
            if(Url::get('reservation_room_id'))
                $cond .= ' AND reservation_room.id='.Url::get('reservation_room_id');
            if(Url::get('from_date'))
                $cond .= ' AND reservation_room.departure_time>=\''.Date_Time::to_orc_date(Url::get('from_date')).'\'';
            if(Url::get('to_date'))
                $cond .= ' AND reservation_room.arrival_time<=\''.Date_Time::to_orc_date(Url::get('to_date')).'\'';
            if(Url::get('status'))
                $cond .= ' AND reservation_room.status=\''.Url::get('status').'\'';
            
            $reservation_room = DB::fetch_all('
                                    SELECT
                                        reservation_room.id
                                        ,reservation_room.time_in
                                        ,reservation_room.time_out
                                        ,reservation_room.confirm
                                        ,reservation_room.reservation_id
                                        ,reservation_room.foc
                                        ,reservation_room.foc_all
                                        ,reservation_room.room_id
                                        ,reservation_room.status
                                        ,room.name as room_name
                                        ,room_level.name as room_level_name
                                        ,customer.name as customer_name
                                        ,customer.id as customer_id
                                        ,mice_reservation.id as mice
                                    FROM
                                        reservation_room
                                        inner join reservation on reservation_room.reservation_id=reservation.id
                                        inner join customer on customer.id=reservation.customer_id
                                        inner join room_level on reservation_room.room_level_id=room_level.id
                                        left join room on room.id=reservation_room.room_id
                                        left join mice_reservation on mice_reservation.id=reservation.mice_reservation_id
                                    WHERE
                                        '.$cond.'
                                        AND (reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'BOOKED\' OR reservation_room.status=\'CHECKOUT\')
                                    ORDER BY
                                        reservation_room.reservation_id
                                        ,customer.name
                                        ,room.name
                                        ,room_level.name
                                        ,reservation_room.time_in
                                        ,reservation_room.time_out
                                    ');
            $reservation_traveller = DB::fetch_all('
                                                    SELECT
                                                        reservation_traveller.id
                                                        ,reservation_traveller.reservation_room_id
                                                        ,reservation_traveller.arrival_time as time_in
                                                        ,reservation_traveller.departure_time as time_out
                                                        ,traveller.id as traveller_id
                                                        ,traveller.first_name || \' \' || traveller.last_name as traveller_name
                                                        ,traveller.passport
                                                        ,traveller.address
                                                        ,traveller.competence
                                                        ,traveller.nationality_id as nationality
                                                        ,country.name_2 as country
                                                        ,CASE
                                                            WHEN reservation_traveller.arrival_date=reservation_traveller.departure_date
                                                            THEN 1
                                                            ELSE reservation_traveller.departure_date - reservation_traveller.arrival_date
                                                        END night
                                                    FROM
                                                        reservation_traveller
                                                        inner join traveller on traveller.id=reservation_traveller.traveller_id
                                                        inner join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
                                                        inner join reservation on reservation.id=reservation_room.reservation_id
                                                        inner join country on country.id=traveller.nationality_id
                                                    WHERE
                                                        '.$cond.'
                                                        AND (reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'BOOKED\' OR reservation_room.status=\'CHECKOUT\')
                                                    ORDER BY
                                                        reservation_traveller.reservation_room_id,traveller.first_name,traveller.last_name
                                                    ');
            foreach($reservation_traveller as $key=>$value) {
                if(isset($reservation_room[$value['reservation_room_id']])){
                    $value['time_in'] = date('H:i d/m/Y',$value['time_in']);
                    $value['time_out'] = date('H:i d/m/Y',$value['time_out']);
                    if($value['address']=='')
                        $value['address'] = ' ';
                    if($value['passport']=='')
                        $value['passport'] = ' ';
                    if($value['competence']=='')
                        $value['competence'] = ' ';
                    $reservation_room[$value['reservation_room_id']]['child'][$value['id']] = $value;
                }
            }
            foreach($reservation_room as $key=>$value){
                if(!isset($reservation_room[$key]['child'])){
                    $reservation_room[$key]['child'] = array();
                    $reservation_room[$key]['count_child'] = 0;
                }else{
                    $reservation_room[$key]['count_child'] = sizeof($reservation_room[$key]['child']);
                }
                    
                
                $reservation_room[$key]['time_in'] = date('H:i d/m/Y',$value['time_in']);
                $reservation_room[$key]['time_out'] = date('H:i d/m/Y',$value['time_out']);
                $reservation_room[$key]['description'] = ' ';
                $reservation_room[$key]['add_service_option'] = ' ';
                $reservation_room[$key]['fast_operation_option'] = ' ';
                $reservation_room[$key]['fast_status_option'] = ' ';
                if($value['status']=='BOOKED'){
                    if($value['room_id']==''){
                        $reservation_room[$key]['room_name']  = ' ';
                        $reservation_room[$key]['description'] .= '<p>'.Portal::language('not_asign_room').'</p>';
                        $reservation_room[$key]['fast_status_option'] .= '<option value="ASIGN_ROOM">'.Portal::language('asign_room').'</option>';
                    }else{
                        $reservation_room[$key]['fast_status_option'] .= '<option value="CHECKIN">'.Portal::language('checkin').'</option>';
                        if($value['mice']==''){
                            if(sizeof($reservation_room[$key]['child'])>0)
                                $reservation_room[$key]['fast_operation_option'] .= '<option value="FOLIO_ROOM">'.Portal::language('create_folio').' '.Portal::language('room').'</option>';
                            $reservation_room[$key]['fast_operation_option'] .= '<option value="FOLIO_GROUP">'.Portal::language('create_folio').' '.Portal::language('group').'</option>';
                        }else{
                            $reservation_room[$key]['fast_operation_option'] .= '<option value="PAYMENT_MICE">'.Portal::language('payment').' MICE'.'</option>';
                        }
                    }
                    $reservation_room[$key]['description'] .= '<p>'.($value['confirm']==1?Portal::language('booking_is_confirm'):Portal::language('booking_un_confirm')).'</p>';
                    $reservation_room[$key]['add_service_option'] .= '<option value="EXTRA_ROOM">Phụ trội tiền phòng</option>';
                    $reservation_room[$key]['add_service_option'] .= '<option value="EXTRA_SERVICE">Dịch vụ mở rộng</option>';
                }else if($value['status']=='CHECKIN'){
                    if($value['mice']==''){
                        if(sizeof($reservation_room[$key]['child'])>0)
                            $reservation_room[$key]['fast_operation_option'] .= '<option value="FOLIO_ROOM">'.Portal::language('create_folio').' '.Portal::language('room').'</option>';
                        $reservation_room[$key]['fast_operation_option'] .= '<option value="FOLIO_GROUP">'.Portal::language('create_folio').' '.Portal::language('group').'</option>';
                    }else{
                        $reservation_room[$key]['fast_operation_option'] .= '<option value="PAYMENT_MICE">'.Portal::language('payment').' MICE'.'</option>';
                    }
                    $reservation_room[$key]['fast_status_option'] .= '<option value="CHECKOUT">'.Portal::language('checkout').'</option>';
                    
                    $reservation_room[$key]['add_service_option'] .= '<option value="EXTRA_ROOM">Phụ trội tiền phòng</option>'; //Portal::language('extra_charge_room')
                    $reservation_room[$key]['add_service_option'] .= '<option value="EXTRA_SERVICE">Dịch vụ mở rộng</option>';
                    $reservation_room[$key]['add_service_option'] .= '<option value="MINIBAR">'.Portal::language('add').' MINIBAR</option>';
                    $reservation_room[$key]['add_service_option'] .= '<option value="LAUNDRY">'.Portal::language('add').' Giặt là</option>';
                    $reservation_room[$key]['add_service_option'] .= '<option value="EQUIPMENT">'.Portal::language('add').' Đền bù</option>';
                }
                if($value['foc_all']==1){
                    $reservation_room[$key]['foc_all'] = '<i class="fa fa-fw fa-check"></i>';
                    $reservation_room[$key]['foc'] = ' ';
                }elseif($value['foc']!=''){
                    $reservation_room[$key]['foc'] = '<i class="fa fa-fw fa-check"></i>';
                    $reservation_room[$key]['foc_all']  = ' ';
                }else{
                    $reservation_room[$key]['foc'] = ' ';
                    $reservation_room[$key]['foc_all']  = ' ';
                }
                
            }
            
            $items['time'] = time();
            $items['date'] = date('H:i d/m/Y');
            $items['data'] = $reservation_room;
            echo json_encode($items);
            exit();
	    }
        
        
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
                if(Url::get('from_date'))
                    $cond .= ' AND reservation_traveller.departure_date>=\''.Date_Time::to_orc_date(Url::get('from_date')).'\'';
                if(Url::get('to_date'))
                    $cond .= ' AND reservation_traveller.arrival_date<=\''.Date_Time::to_orc_date(Url::get('to_date')).'\'';
                
                if(Url::get('type')=='TRAVELLER_NAME'){
                    $cond .= ' AND (
                                        UPPER(FN_CONVERT_TO_VN(traveller.first_name)) like \'%'.convert_utf8_to_latin(strtoupper(URL::sget('q'))).'%\'
                                        OR UPPER(FN_CONVERT_TO_VN(traveller.last_name)) like \'%'.convert_utf8_to_latin(strtoupper(URL::sget('q'))).'%\'
                                        OR UPPER(FN_CONVERT_TO_VN(traveller.first_name || \' \' || traveller.last_name)) like \'%'.convert_utf8_to_latin(strtoupper(URL::sget('q'))).'%\'
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
                                                    AND (reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'BOOKED\')
                                				order by
                                					traveller.first_name, traveller.last_name
                                			');
                }else{
                    $cond .= ' AND UPPER(FN_CONVERT_TO_VN(traveller.passport)) like \'%'.convert_utf8_to_latin(strtoupper(URL::sget('q'))).'%\'';
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
                                                    AND (reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'BOOKED\')
                                				order by
                                					traveller.first_name, traveller.last_name
                                			');
                }
                
                foreach($items as $key=>$value)
                {
                    echo $value['name'].'|'.$value['id']."\n";
                }
            }elseif(Url::get('type')=='ROOM_NAME'){
                if(Url::get('from_date'))
                    $cond .= ' AND reservation_room.departure_time>=\''.Date_Time::to_orc_date(Url::get('from_date')).'\'';
                if(Url::get('to_date'))
                    $cond .= ' AND reservation_room.arrival_time<=\''.Date_Time::to_orc_date(Url::get('to_date')).'\'';
                
                $cond .= ' AND UPPER(FN_CONVERT_TO_VN(room.name)) like \'%'.convert_utf8_to_latin(strtoupper(URL::sget('q'))).'%\'';
                
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
                                                    AND (reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'BOOKED\')
                                				order by
                                					room.name
                                			');
                foreach($items as $key=>$value)
                {
                    echo $value['name'].'|'.$value['id']."\n";
                }
            }elseif(Url::get('type')=='RESERVATION_ID'){
                
                $cond .= ' AND UPPER(FN_CONVERT_TO_VN(reservation.id)) like \'%'.convert_utf8_to_latin(strtoupper(URL::sget('q'))).'%\'';
                
                $items = DB::fetch_all('
                                				select 
                                					reservation.id
                                                    ,rownum
                                				from
                                					reservation
                                				where
                                                    '.$cond.'
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
        
		Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY)){
			require_once 'forms/list.php';
			$this->add_form(new MasterReservationForm());
		}else{
			Url::access_denied();
		}
	}
}
?>