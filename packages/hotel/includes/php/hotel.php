<?php
// Lop dung chung cho cac module
// Write by Khoand
class Hotel{
	static function get_reservation(){
		
	}
	static function get_reservation_room()
	{
		$sql = '
			select 
				reservation_room.id
				,concat(CONCAT(traveller.first_name,\' \'),traveller.last_name) as agent_name
				,reservation_room.room_id
				,room.name
			from 
				reservation_room 
				inner join room on room.id=reservation_room.room_id
				inner join room_status on room_status.RESERVATION_ID  =  RESERVATION_ROOM.RESERVATION_ID 
				LEFT OUTER JOIN traveller on traveller.id=reservation_room.traveller_id 
			where
				room.portal_id=\''.PORTAL_ID.'\' and reservation_room.status=\'CHECKIN\'
				and (reservation_room.closed is null or reservation_room.closed = 0)
				and room_status.status = \'OCCUPIED\'
				and room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
			order by
				room.name
		';
		return DB::fetch_all($sql);
	}
	static function get_reservation_guest()
	{
		return DB::fetch_all('
				select 
					traveller.id, reservation_room.id as reservation_room_id,
					CONCAT(room.name,CONCAT(\' - \',CONCAT(CONCAT(traveller.first_name,\' \'),traveller.last_name))) as name  
				from 
					reservation_room 
					inner join reservation on reservation.id = reservation_room.reservation_id 
					inner join room_status on room_status.RESERVATION_ID  =  RESERVATION_ROOM.RESERVATION_ID 
					left outer join room on room.id = reservation_room.room_id
					LEFT OUTER JOIN reservation_traveller ON reservation_traveller.reservation_room_id = reservation_room.id
					LEFT OUTER JOIN traveller on traveller.id=reservation_room.traveller_id 
				where 
					reservation.portal_id=\''.PORTAL_ID.'\' 
					and reservation_room.status=\'CHECKIN\'
					and (reservation_room.closed is null or reservation_room.closed = 0)
					and room_status.status = \'OCCUPIED\'
					and room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
					AND reservation_traveller.status=\'CHECKIN\'
				order by traveller.last_name
			');
	}
	static function get_reservation_traveller_guest()
	{
		return DB::fetch_all('
				select 
					reservation_traveller.id, reservation_room.id as reservation_room_id,
					CONCAT(room.name,CONCAT(\' - \',CONCAT(CONCAT(traveller.first_name,\' \'),traveller.last_name))) as name  
				from 
					reservation_room 
					inner join reservation on reservation.id = reservation_room.reservation_id 
					inner join room_status on room_status.RESERVATION_ID  =  RESERVATION_ROOM.RESERVATION_ID 
					left outer join room on room.id = reservation_room.room_id
					left outer join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
					LEFT OUTER JOIN traveller on traveller.id=reservation_traveller.traveller_id 
				where 
					reservation.portal_id=\''.PORTAL_ID.'\' 
					and reservation_room.status=\'CHECKIN\'
					and (reservation_room.closed is null or reservation_room.closed = 0)
					and room_status.status = \'OCCUPIED\' and reservation_traveller.status!=\'CHECKOUT\'
					and room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
					AND reservation_traveller.status=\'CHECKIN\'
				order by traveller.last_name
			');
	}
	static function get_reservation_room_guest()
	{
		return DB::fetch_all('
				select
					reservation_room.id, reservation_room.room_id as room_id,
					CONCAT(CONCAT(traveller.first_name,\' \'),traveller.last_name) as name 					
				from 
					reservation_room
					inner join reservation on reservation.id = reservation_room.reservation_id 
					inner join room_status on room_status.RESERVATION_ID  =  RESERVATION_ROOM.RESERVATION_ID 
					LEFT OUTER JOIN traveller on traveller.id=reservation_room.traveller_id 
				where 
					reservation.portal_id=\''.PORTAL_ID.'\'
					and reservation_room.status=\'CHECKIN\'
					and (reservation_room.closed is null or reservation_room.closed = 0)
					and room_status.status = \'OCCUPIED\'
					and room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
				order by
					reservation_room.arrival_time desc
			');
	}
	static function get_booked_room()
	{
		$sql = '
			select 
				reservation_room.id
				,concat(CONCAT(traveller.first_name,\' \'),traveller.last_name) as agent_name
				,reservation_room.room_id
				,room.name
			from 
				reservation_room 
				inner join room on room.id=reservation_room.room_id
				inner join room_status on room_status.RESERVATION_ROOM_id  =  RESERVATION_ROOM.id 
				LEFT OUTER JOIN traveller on traveller.id=reservation_room.traveller_id 
			where
				room.portal_id=\''.PORTAL_ID.'\' and reservation_room.status=\'BOOKED\'
				and (reservation_room.closed is null or reservation_room.closed = 0)
				and room_status.status = \'BOOKED\'
				and room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
			order by
				room.name
		';
		return DB::fetch_all($sql);
	}	
	static function get_available_room($cond=false){
		$rooms = DB::fetch_all('
			SELECT
				room.*
			FROM
				room
				inner join room_level on room_level.id = room.room_level_id
			WHERE
				room.portal_id = \''.PORTAL_ID.'\'
				AND (room_level.is_virtual is null OR room_level.is_virtual = 0)
				'.$cond.'
			ORDER BY
				room.name
		');
		return $rooms;
	}
	static function get_bar()
	{
		return DB::fetch_all('
			select
				*
			from
				bar
		');
	}
	static function get_new_bar($id=false)
	{
		$cond = '';
		if($id)
		{
			$cond = ' and id = '.$id;
		}
		return DB::fetch('
			select
				*
			from
				bar
			where
				1>0 '.$cond.'
			order by
				bar.id
		');
	}
    static function get_new_karaoke($id=false)
	{
		$cond = '';
		if($id)
		{
			$cond = ' and id = '.$id;
		}
		return DB::fetch('
			select
				*
			from
				karaoke
			where
				1>0 '.$cond.'
			order by
				karaoke.id
		');
	}
	static function delete_minibar($id){
		DB::delete('minibar','id=\''.$id.'\'');
		DB::delete('minibar_product','minibar_id=\''.$id.'\'');
		if($items = DB::select_all('housekeeping_invoice','minibar_id=\''.$id.'\'')){
			foreach($items as $value){
				DB::delete('housekeeping_invoice_detail','invoice_id='.$value['id'].'');
				DB::delete('housekeeping_invoice','id='.$value['id'].'');
			}
		}
	}
	static function log_off_user($user_id){//////////////////////log off nhung user dang su dung tren PMKS///////////////////////
		if(Session::get('user_id')!=$user_id){
			DB::delete('session_user','user_id = \''.$user_id.'\'');
			DB::update('account',array('last_online_time'=>time()),'id=\''.$user_id.'\'');
		}
	}
    //lay ve tong doanh thu cua mot reservation_room
    //bool = true : tra ve doanh thu phong luon, = false : kiem tra >= deposit thì return false
    static function get_reservation_room_revenue($id,$bool = false)
    {	
        
		$sql='select 
				reservation_room.*,
				traveller.first_name,
				traveller.last_name,
				traveller.nationality_id,
				traveller.id as traveller_id,
				reservation_type.show_price,
				reservation_type.name as reservation_type_name,
				room.name as room_name,
				customer.address,
				customer.name as customer_name,
				customer.id as customer_id
			from 
				reservation_room 
				inner join reservation ON reservation.id = reservation_room.reservation_id
				left join room on room.id=reservation_room.room_id
				left outer join customer on customer.id = reservation.customer_id
				left outer join reservation_type on reservation_type.id=reservation_room.reservation_type_id
				left outer join reservation_traveller on reservation_traveller.reservation_room_id=reservation_room.id
				left outer join traveller on reservation_traveller.traveller_id=traveller.id
				';
		//============================Thong tin hoa don moi-------------------------------//
		$row = DB::fetch($sql.' where reservation_room.id='.$id.'');
		//System::Debug($row);
		if(HOTEL_CURRENCY == 'VND'){
				$row['exchange_currency_id'] = 'USD';
			}else{
				$row['exchange_currency_id'] = 'VND';	
			}
			$row['exchange_rate'] = DB::fetch('select id,exchange from currency where id=\''.$row['exchange_currency_id'].'\'','exchange');
	//--------------------------------------------------------------------------------------------------		
			$row['discount_total']=0;
			$fromtime=Date_Time::to_time(date('d/m/Y',$row['time_in']));
			$totime=Date_Time::to_time(date('d/m/Y',$row['time_out']));
	//-------------------------------------------------------------------------------------------------------
			if($nationality=DB::exists_id('country',$row['nationality_id'])){
				$row['nationality']=$nationality['name_'.Portal::language()];
			}else{
				$row['nationality']='';
			}
	//-----------------------------------Ngay den va ngay di-------------------------------------------------		
			$arr_time = $row['arrival_time'];
			$dep_time = $row['departure_time'];
			$row['time_arrival']=date('d/m/Y',$fromtime);//str_replace('-','/',Date_Time::convert_orc_date_to_date($row['arrival_time']));
			$row['time_departure']=date('d/m/Y',$totime);
			$room_result = $row;
	//-------------------------------------------------------------------------------------------------------
			$row_number = 0;
			$total_room_price=0;
			$restaurant_total = 0;
			$minibar_total = 0;
			$laundry_total = 0;
			$compensated_total = 0;
			$phone_total = 0;			
			$service_total = 0;
			$total = 0;
			$tax_total = 0;
			$service_charge_total = 0;
			$total_items = 0;
			$condition = '1=1';
			$check=false;
			$items = array();
	//----------------------------------------Tien phong`----------------------------------------------------
			$row['currency_id'] = HOTEL_CURRENCY;
			$row['total_amount'] = 0;
			$row['room_price'] = System::display_number($row['price']);
			//if(defined('FULL_RATE')){
				//$row['room_price'] = $row['room_price']/(1.155);
			//}
			if($row['show_price'] == 0){
				$row['room_price'] = $row['reservation_type_name'];
			} 
			if($row['foc']){
				$row['room_price'] = 'FOC';
			} 
			if($row['foc_all']){
				$row['room_price'] = 'FOC';
			} 
			$row['reduce_balance'] = $row['reduce_balance'];
				$row['tax_rate'] = $row['tax_rate'];
				$row['service_rate'] = $row['service_rate'];
			//}
			$row['total_massage_amount'] = 0;
			$row['total_tennis_amount'] = 0;
			$row['total_swimming_pool_amount'] = 0;
			$row['total_karaoke_amount'] = 0;
			$row['total_shop_amount'] = 0;
			$day = array(); // lay danh sach ngay` o khach san
			$n = 1;
			$from = $fromtime;
			$to = $totime;
			$d=$from;
			$bar_charge = 0; //tong tien` su dung dich vu bar
			$sql = '
				SELECT 
					to_char(room_status.in_date,\'DD/MM\') as id
					,room_status.change_price
					,room.name as room_name
					,room_status.in_date
					,room_status.id as room_status_id
					,reservation_room.tax_rate
					,reservation_room.service_rate
					,reservation_room.id as rr_id
					,TO_CHAR(room_status.in_date,\'DD/MM/YYYY\') as convert_date
				FROM 
					room_status
					inner join room on room.id=room_status.room_id
					INNER JOIN reservation_room ON reservation_room.id = room_status.reservation_room_id
				WHERE 
					reservation_room_id=\''.$row['id'].'\' 
					AND reservation_room.room_id=\''.$row['room_id'].'\' AND room_status.change_price > 0
				ORDER BY 
					room.name,room_status.in_date';
			$room_statuses = DB::fetch_all($sql);//'.((USE_NIGHT_AUDIT==1)?'AND (room_status.closed_time > 0 OR reservation_room.arrival_time = reservation_room.departure_time)':'').'
			$j = 0;
			$holidays = DB::fetch_all('select id,name,charge,in_date from holiday');
			$holiday = array();
			foreach($holidays as $key=>$value){
				$k = Date_Time::convert_orc_date_to_date($value['in_date'],'/');
				$holiday[$k]['id'] = $k;
				$holiday[$k]['name'] = $value['name'];
				$holiday[$k]['charge'] = $value['charge'];
			}
            //tien phong
			foreach($room_statuses as $k=>$v)
            {
				if($row['net_price']==1){
					$param = (1+($row['tax_rate']*0.01) + ($row['service_rate']*0.01) + (($row['tax_rate']*0.01)*($row['service_rate']*0.01)));
					$v['change_price'] = round($v['change_price']/$param,2);	
				}
				if($row['foc_all'] ==1 || $row['foc'] !=''){
					//$v['change_price'] =  0;
				}
				$tt = ($row['reduce_balance']?(100 - $row['reduce_balance'])*$v['change_price']/100:$v['change_price']);
				if($row['reduce_balance']>0 && $row['reduce_balance']!=''){
					$room_statuses[$k]['note'] = '( Discounted '.$row['reduce_balance'].'%)';
				}else{
					$room_statuses[$k]['note'] = '';
				}
				$room_statuses[$k]['change_price'] = $tt;
				$percent = 100;$status = 0;
				$amount = $room_statuses[$k]['change_price'];
				$items['ROOM_'.$v['room_status_id']]['amount'] = System::display_number($amount);
                $items['ROOM_'.$v['room_status_id']]['service_rate'] = $v['service_rate'];
				$items['ROOM_'.$v['room_status_id']]['tax_rate'] = $v['tax_rate'];
			}
				$room_price = 0;
				$row['extra_services'] = DB::fetch_all('
					select 
						extra_service_invoice_detail.*,
						(extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) as amount,
                        DECODE(extra_service_invoice.tax_rate,\'\',0,extra_service_invoice.tax_rate) as tax_rate,
						DECODE(extra_service_invoice.service_rate,\'\',0,extra_service_invoice.service_rate) as service_rate
					from 
						extra_service_invoice_detail
						inner join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
					where 
						extra_service_invoice.reservation_room_id='.$id.'
						AND extra_service_invoice_detail.used = 1
						
				');
                //dich vu kjhac
				if(!empty($row['extra_services'])){	
					foreach($row['extra_services'] as $s_key=>$s_value){
						$amount = $s_value['amount'];
						$items['EXTRA_SERVICE_'.$s_key]['amount'] = System::display_number($amount);
                        $items['EXTRA_SERVICE_'.$s_key]['service_rate'] = $s_value['service_rate'];
						$items['EXTRA_SERVICE_'.$s_key]['tax_rate'] = $s_value['tax_rate'];
					}
				}
	//----------------------------------------/Tien phong-----------------------------------------------------			
	//----------------------------------------Tien dich vu----------------------------------------------------
				//if(URL::get('hk_invoice')){
					$sql_l='
						SELECT 
							housekeeping_invoice.*
						FROM 
							housekeeping_invoice
						WHERE 
							housekeeping_invoice.reservation_room_id='.$id.' 
							AND housekeeping_invoice.minibar_id=\''.$row['room_id'].'\'
							AND housekeeping_invoice.type=\'LAUNDRY\'
					';// chu y giat la va minibar khac nhau o minibar_id
					//AND (housekeeping_invoice.time>='.$d.' AND housekeeping_invoice.time<'.($d+24*3600).') 
					$sql_m='
						SELECT 
							housekeeping_invoice.*
						FROM 
							housekeeping_invoice
							inner join minibar on housekeeping_invoice.minibar_id = minibar.id
						WHERE 
							housekeeping_invoice.reservation_room_id='.$id.' AND
							minibar.room_id=\''.$row['room_id'].'\' AND
							type=\'MINIBAR\' 
					';//AND (housekeeping_invoice.time>='.$d.' and housekeeping_invoice.time<'.($d+24*3600).') 
					$sql_compensated_item='
						SELECT 
							housekeeping_invoice.*
						FROM 
							housekeeping_invoice
						WHERE
							housekeeping_invoice.reservation_room_id='.$id.' AND
							housekeeping_invoice.minibar_id=\''.$row['room_id'].'\' AND
							housekeeping_invoice.type=\'EQUIP\' 
					';// Voi truong hop cua hoa don den bu thi truong minibar_id tuong ung voi ID cua phong
				//}/AND (housekeeping_invoice.time>='.$d.' and housekeeping_invoice.time<'.($d+24*3600).') 
		//-----------------------------------------minibar------------------------------------------------------------
				//if(URL::get('hk_invoice')){
					$minibar_charge=0;
					$minibar_tax_rate=0;
					$minibar_express_rate=0;
					$minibar_discount=0;
					$minibar_total_before_tax=0;
					$minibar_total_tax=0;
					$minibar_total_service_charge=0;
					if($minibars = DB::fetch_all($sql_m)){
						foreach($minibars as $k=>$minibar){				
							$minibar_details = DB::fetch_all('
								SELECT 
									HK_I_D.id,HK_I_D.price,HK_I_D.quantity,
									product.name_1 as name,unit.name_1 AS unit_name,
									(HK_I_D.price * HK_I_D.quantity) AS amount
								FROM 
									housekeeping_invoice_detail HK_I_D 
									INNER JOIN product ON product.id = HK_I_D.product_id
									LEFT OUTER JOIN unit ON unit.id = product.unit_id
								WHERE 
									HK_I_D.invoice_id = '.$k.'
								ORDER BY 
									product.name_1');
							$percent = 100;$status = 0;
							$amount = $minibar['total_before_tax'];
							$items['MINIBAR_'.$k]['amount'] = System::display_number($amount);
                            $items['MINIBAR_'.$k]['service_rate'] = $minibar['fee_rate'];
							$items['MINIBAR_'.$k]['tax_rate'] = $minibar['tax_rate'];
						}
					}
		//--------------------------------------------laundry--------------------------------------------------------
					DB::query($sql_l);
					if($laundrys = DB::fetch_all()){
						foreach($laundrys as $k=>$laundry){	
							$laundry_details = DB::fetch_all('
								SELECT 
									HK_I_D.id,HK_I_D.price,HK_I_D.quantity,
									product.name_1 as name,unit.name_1 AS unit_name,
									(HK_I_D.price * HK_I_D.quantity) AS amount
								FROM 
									housekeeping_invoice_detail HK_I_D 
									INNER JOIN product ON product.id = HK_I_D.product_id
									LEFT OUTER JOIN unit ON unit.id = product.unit_id
								WHERE 
									HK_I_D.invoice_id = '.$k.'
								ORDER BY 
									product.name_1');
							$percent = 100;$status = 0;
							$amount = $laundry['total_before_tax'];
							$items['LAUNDRY_'.$k]['amount'] = System::display_number($amount);
                            $items['LAUNDRY_'.$k]['service_rate'] = $laundry['fee_rate'];
							$items['LAUNDRY_'.$k]['tax_rate'] = $laundry['tax_rate'];
						}
					}
	//--------------------------------------------/laundry--------------------------------------------------------
					DB::query($sql_compensated_item);
					if($compensated_items = DB::fetch_all($sql_compensated_item)){
						foreach($compensated_items as $k=>$compensated_item){
							$item_details = DB::fetch_all('
								SELECT 
									HK_I_D.id,HK_I_D.price,HK_I_D.quantity,
									product.name_1 as name,unit.name_1 AS unit_name,
									(HK_I_D.price * HK_I_D.quantity) AS amount 
								FROM 
									housekeeping_invoice_detail HK_I_D 
									INNER JOIN product ON product.id = HK_I_D.product_id
									LEFT OUTER JOIN unit ON unit.id = product.unit_id
								WHERE 
									HK_I_D.invoice_id = '.$k.'
								ORDER BY 
									product.name_1');
							$percent = 100;$status = 0;
							$amount = $compensated_item['total_before_tax'];
							$items['EQUIPMENT_'.$k]['amount'] = System::display_number($amount);
                            $items['EQUIPMENT_'.$k]['service_rate'] = $compensated_item['fee_rate'];
							$items['EQUIPMENT_'.$k]['tax_rate'] = $compensated_item['tax_rate'];
						}
					}
	//-----------------------------------------------------------------------------------------------------------			
					$sql = '
						select 
							bar_reservation.id, bar_reservation.payment_result, 
							bar_reservation.time_out, bar_reservation.total, bar_reservation.prepaid,
							bar_reservation.total_before_tax, bar_reservation.total_before_tax, bar_reservation.tax_rate,bar_reservation.bar_fee_rate,
							\''.(Portal::language('restaurant')).'\' AS bar_name ,
							bar_reservation.deposit as bar_deposit
						from 
							bar_reservation 
						where 
							reservation_room_id=\''.$id.'\' 
							 and (bar_reservation.status=\'CHECKOUT\' or bar_reservation.status=\'CHECKIN\')
					';//and (bar_reservation.ARRIVAL_TIME>='.$d.' and bar_reservation.ARRIVAL_TIME<='.($d+24*3600).') 
					if($bar_resrs = DB::fetch_all($sql)){
						foreach($bar_resrs as $bk=>$reser){
							$amount = $reser['total_before_tax'];
							$items['BAR_'.$bk]['amount'] = System::display_number($amount);
                            $items['BAR_'.$bk]['service_rate'] = $reser['bar_fee_rate'];
							$items['BAR_'.$bk]['tax_rate'] = $reser['tax_rate'];
						}
					}
	//-------------------------------------------------Other services----------------------------------------
			$all_services = DB::fetch_all('
				select 
					service_id as id,reservation_room_service.amount,reservation_room_id,service.name,service.type,
					0 as service_charge_amount, 0 as tax_amount,reservation_room_service.id as room_service_id
				from 
					reservation_room_service 
					inner join service on service.id = service_id 
				where 
					reservation_room_id= '.$id.'
			');
			$row['services'] = $all_services;
				foreach($row['services'] as $s_key=>$s_value){
					if($s_value['type']=='SERVICE'){
						$amount = $s_value['amount'];
						$items['SERVICE_'.$s_key]['amount'] = System::display_number($amount);
                        $items['SERVICE_'.$s_key]['service_rate'] = 0;
						$items['SERVICE_'.$s_key]['tax_rate'] = 0;
					}	
				}
				foreach($row['services'] as $s_key=>$s_value){
					if($s_value['type']=='ROOM'){
						$amount = $s_value['amount'];
						$items['ROOM_SERVICE_'.$s_key]['amount'] = System::display_number($amount);
                        $items['ROOM_SERVICE_'.$s_key]['service_rate'] = 0;
						$items['ROOM_SERVICE_'.$s_key]['tax_rate'] = 0;
					}	
				}
	//----------------------------------------/Other services------------------------------------------------		
			//if(URL::get('massage_invoice')){
				$sql_massage='
					SELECT 
						massage_reservation_room.hotel_reservation_room_id,sum(massage_reservation_room.total_amount) as total_amount
					FROM 
						massage_reservation_room
					WHERE
						massage_reservation_room.hotel_reservation_room_id='.$id.'
					GROUP BY
						massage_reservation_room.hotel_reservation_room_id
				';
				if($row['total_massage_amount'] = DB::fetch($sql_massage,'total_amount')){// and HAVE_MASSAGE
					$amount = $row['total_massage_amount'];
					$items['MASSAGE_'.$id]['amount'] = System::display_number($amount);
                    $items['MASSAGE_'.$id]['service_rate'] = 0;
					$items['MASSAGE_'.$id]['tax_rate'] = 0;
				}
			//}
			if(1==1){
				$sql_tennis='
					SELECT 
						tennis_reservation_court.hotel_reservation_room_id,sum(tennis_reservation_court.total_amount) as total_amount
					FROM 
						tennis_reservation_court
					WHERE
						tennis_reservation_court.hotel_reservation_room_id='.$id.'
					GROUP BY
						tennis_reservation_court.hotel_reservation_room_id
				';
				if($row['total_tennis_amount'] = DB::fetch($sql_tennis,'total_amount') and HAVE_TENNIS){
					$amount =$row['total_tennis_amount'];
					$items['TENNIS_'.$id]['amount'] = System::display_number($amount);
                    $items['TENNIS_'.$id]['service_rate'] = 0;
					$items['TENNIS_'.$id]['tax_rate'] = 0;
				}
			}
			if(1==1){
				$sql_swimming_pool='
					SELECT 
						swimming_pool_reservation_pool.hotel_reservation_room_id,sum(swimming_pool_reservation_pool.total_amount) as total_amount
					FROM 
						swimming_pool_reservation_pool
					WHERE
						swimming_pool_reservation_pool.hotel_reservation_room_id='.$id.'
					GROUP BY
						swimming_pool_reservation_pool.hotel_reservation_room_id
				';
				if($row['total_swimming_pool_amount'] = DB::fetch($sql_swimming_pool,'total_amount') and HAVE_SWIMMING){
					$amount =$row['total_swimming_pool_amount'];
					$items['SWIMMING_POOL_'.$id]['amount'] = System::display_number($amount);
                    $items['SWIMMING_POOL_'.$id]['service_rate'] = 0;
					$items['SWIMMING_POOL_'.$id]['tax_rate'] = 0;
				}
			}
			if(1==1){
				$sql_karaoke='
					SELECT 
						KA_RESERVATION.RESERVATION_ROOM_ID,sum(KA_RESERVATION.TOTAL) as total_amount
					FROM 
						KA_RESERVATION
					WHERE
						KA_RESERVATION.RESERVATION_ROOM_ID='.$id.'
					GROUP BY
						KA_RESERVATION.RESERVATION_ROOM_ID
				';
				if($row['total_karaoke_amount'] = DB::fetch($sql_karaoke,'total_amount') and HAVE_KARAOKE){
					$amount =$row['total_karaoke_amount'];
					$items['KARAOKE_'.$id]['amount'] = System::display_number($amount);
                    $items['KARAOKE_'.$id]['service_rate'] = 0;
					$items['KARAOKE_'.$id]['tax_rate'] = 0;
				}
			}
			if($row['room_id']){
				//----------------------------------------Tien dien thoai-------------------------------------------------
				$sql_p = '
					SELECT
						SUM(telephone_report_daily.total_before_tax) AS total
					FROM
						telephone_report_daily
						inner join telephone_number on telephone_number.phone_number = telephone_report_daily.phone_number_id
					WHERE
						telephone_report_daily.hdate >='.($row['time_in']).' and telephone_report_daily.hdate <= '.($row['time_out']).'
						AND telephone_number.room_id = '.$row['room_id'].'
					GROUP BY
						telephone_number.room_id
						
				';
				
				if($phone = DB::fetch($sql_p)){
						$amount =$phone['total'];//$row['exchange_rate'],2);
						$items['TELEPHONE_'.$id]['amount'] = System::display_number($amount);
                        $items['TELEPHONE_'.$id]['service_rate'] = 0;
						$items['TELEPHONE_'.$id]['tax_rate'] = (TELEPHONE_TAX_RATE)?TELEPHONE_TAX_RATE:0;
					}
				//}
			}
			//thong tin cuoi cung cua check out
			//$row['deposit'] = $row['deposit'];
			if($row['reduce_amount'] != '' && $row['reduce_amount']>0){
				$percent = 100;$status = 0;
				$amount = $row['reduce_amount'];
				$items['DISCOUNT_'.$id]['service_rate'] = 0;
				$items['DISCOUNT_'.$id]['tax_rate'] = 0;
				$items['DISCOUNT_'.$id]['amount'] = System::display_number($amount);
			}
            $reservation_room_revenue = 0;
            foreach($items as $key => $value)
            {
                $reservation_room_revenue += 
                    (System::calculate_number($value['amount'])*$value['service_rate']/100 + System::calculate_number($value['amount']))
                     * $value['tax_rate']/100 + (System::calculate_number($value['amount'])*$value['service_rate']/100 + System::calculate_number($value['amount']));
            }
            //echo $reservation_room_revenue . '<br />';
            //echo $row['deposit']. '<br />-';
            if($bool)
            {
                return $reservation_room_revenue;
            }
            else
            {
                if($row['deposit']<$reservation_room_revenue)
                { 
    				return false;
    			}
                else
                    return $reservation_room_revenue;
            }

	}
}
?>