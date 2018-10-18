<?php 
class CheckoutFast extends Module
{
	function CheckoutFast($row)
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
                                				SELECT 
                                					reservation_traveller.id
                                                    ,rownum
                                                    ,traveller.first_name || \' \' || traveller.last_name || \' - ( Phòng: \' || room.name || \'-\' || reservation_room.status || \' )\' as name
                                                    ,reservation_room.id as reservation_room_id
                                                    ,reservation.id as reservation_id
                                				FROM
                                					reservation_traveller
                                                    inner join traveller on reservation_traveller.traveller_id=traveller.id
                                                    inner join reservation_room on reservation_traveller.reservation_room_id=reservation_room.id
                                                    inner join room on room.id=reservation_room.room_id
                                                    inner join reservation on reservation.id=reservation_room.reservation_id
                                				WHERE
                                                    '.$cond.'
                                					AND (rownum > 0 AND rownum <= 1000)
                                                    AND (reservation_room.status=\'CHECKIN\')
                                				order by
                                					traveller.first_name, traveller.last_name
                                			');
                }else{
                    $cond .= ' AND LOWER(FN_CONVERT_TO_VN(traveller.passport)) like \'%'.convert_utf8_to_latin(mb_strtolower(URL::sget('q'))).'%\'';
                    $items = DB::fetch_all('
                                				SELECT 
                                					reservation_traveller.id
                                                    ,rownum
                                                    ,traveller.passport || \' \' || traveller.first_name || \' \' || traveller.last_name || \' - ( Phòng: \' || room.name || \'-\' || reservation_room.status || \' )\' as name
                                                    ,reservation_room.id as reservation_room_id
                                                    ,reservation.id as reservation_id
                                				FROM
                                					reservation_traveller
                                                    inner join traveller on reservation_traveller.traveller_id=traveller.id
                                                    inner join reservation_room on reservation_traveller.reservation_room_id=reservation_room.id
                                                    inner join room on room.id=reservation_room.room_id
                                                    inner join reservation on reservation.id=reservation_room.reservation_id
                                				WHERE
                                                    '.$cond.'
                                					AND (rownum > 0 AND rownum <= 1000)
                                                    AND (reservation_room.status=\'CHECKIN\')
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
                                				SELECT 
                                					reservation_room.id
                                                    ,rownum
                                                    ,room.name || \'-\' || reservation_room.status as name
                                                    ,reservation_room.reservation_id 
                                				FROM
                                					reservation_room
                                                    inner join room on room.id=reservation_room.room_id
                                				WHERE
                                                    '.$cond.'
                                					AND (rownum > 0 AND rownum <= 1000)
                                                    AND (reservation_room.status=\'CHECKIN\')
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
                                				SELECT 
                                					reservation.id
                                                    ,rownum
                                				FROM
                                					reservation
                                				WHERE
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
                                        and reservation_room.status=\'CHECKIN\'
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
                                        and reservation_room.status=\'CHECKIN\'
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
                                                    and reservation_room.status=\'CHECKIN\'
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
                                        and reservation_room.status=\'CHECKIN\'
                                    ');
                echo json_encode($items);
           }
           
           exit();
	    }
        
        /** START: Kiểm tra folio **/
        if(Url::get('status') == 'CHECKOUT')
        {
            /** START - Kiểm tra xem phòng đã được tạo hóa đơn chưa **/
            require_once 'packages/hotel/packages/reception/modules/CreateTravellerFolio/get_reservation_room.php';
            require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
            $reservation_room_check = DB::fetch('
                                SELECT 
                                    reservation_room.*,
                                    room.name as room_name,
                                    room.id as room_id  
                                FROM 
                                    reservation_room 
                                    INNER JOIN room on room.id = reservation_room.room_id 
                                WHERE 
                                    reservation_room.id = '.Url::get('id_check')
            );
            $r_id = $reservation_room_check['reservation_id'];
            $room_id = $reservation_room_check['room_id'];
            $rr_ids = Url::get('id_check').($reservation_room_check['change_room_from_rr']?",".$reservation_room_check['change_room_from_rr']:"");
            $reservation_room_check = DB::fetch_all('SELECT reservation_room.*,room.name as room_name FROM reservation_room inner join room on room.id = reservation_room.room_id WHERE reservation_room.id in ('.$rr_ids.')');
            $arr_can_not_checkout = array();
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
                $cur_reservation_id = Url::get('id_check');
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
                $cur_reservation_id = Url::get('id_check');
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
                if($value['reservation_traveller_id']=='' or (isset($res_tre_room['reservation_room_id']) and $res_tre_room['reservation_room_id']==Url::get('id_check')) or (isset($res_tre_room['reservation_id']) and ($res_tre_room['reservation_room_id'] == $value['reservation_room_id'])and $res_tre_room['reservation_id']==Url::get('id_check')))
                {
                   
                    $cur_reservation_id = Url::get('id_check');
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
                    $cur_reservation_id = Url::get('id_check');
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
 
            /** START - Kiểm tra xem phòng đã tạo thanh toan chưa **/
            if($arr_can_not_checkout)
            {
                echo json_encode($arr_can_not_checkout);
            }else
            {
                $reservation_room  = DB::fetch('SELECT reservation_room.* FROM reservation_room WHERE reservation_room.id ='.Url::get('id_check').'');
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
                        $sql = 'SELECT * FROM room_status WHERE in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$d)).'\' and reservation_room_id='.Url::get('id_check').'';
                        if($room_status = DB::fetch($sql))
                		{
                			 DB::update_id('room_status',
                				(($status=="CHECKOUT" and $d==$to)?array('house_status'=>$house_status):array())+
                				(($status=="CHECKOUT" and $reservation_room['arrival_time'] == $reservation_room['departure_time'])?array('closed_time'=>time()):array())+
                				array(
                				'room_id'=>$room_id,
                				'status'=>$status,
                				'reservation_id'=>$r_id,
                				'house_status'=>$house_status,
                				'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
                				),$room_status['id']
                			);
                		}
                    }else
                    {
                        DB::delete('room_status', 'reservation_room_id =\''.Url::get('id_check').'\' and in_date =\''.Date_Time::to_orc_date(date('d/m/Y',$d)).'\'');
                    }
                    $d=$d+(3600*24);
                }
                $update = array(
                            'time_out' => time(),
                            'departure_time' => Date_Time::convert_time_to_ora_date(time()),
                            'status' => $status
                );
                DB::update('reservation_room',$update,'id='.Url::get('id_check'));
                System::log('Edit','Checkout Fast reservation room #'.Url::get('id_check')." departure_time ".$reservation_room['departure_time']." =>".Date_Time::convert_time_to_ora_date(time())." reservation #".$r_id." UserID #" .Session::get('user_id'));

                echo '';
            }
            exit();
        }
        
		Module::Module($row);
        if(User::can_view(false,ANY_CATEGORY)){
			require_once 'forms/list.php';
			$this->add_form(new CheckoutFastForm());
		}else{
			Url::access_denied();
		}
	}
}
?>