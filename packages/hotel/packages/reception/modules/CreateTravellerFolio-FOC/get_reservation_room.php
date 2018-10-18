<?php
	function get_reservation_room_detail($id,$folio_other){	
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
				inner join room on room.id=reservation_room.room_id
				left outer join customer on customer.id = reservation.customer_id
				left outer join reservation_type on reservation_type.id=reservation_room.reservation_type_id
				left outer join reservation_traveller on reservation_traveller.reservation_room_id=reservation_room.id
				left outer join traveller on reservation_traveller.traveller_id=traveller.id
				';
		//============================Thong tin hoa don moi-------------------------------//
		$row = DB::fetch($sql.' where reservation_room.id='.$id.'');
		if(HOTEL_CURRENCY == 'VND'){
				$row['exchange_currency_id'] = 'USD';
			}else{
				$row['exchange_currency_id'] = 'VND';	
			}
			$row['exchange_rate'] = DB::fetch('select id,exchange from currency where id=\''.$row['exchange_currency_id'].'\'','exchange');
	//--------------------------------------------------------------------------------------------------		
			$row['discount_total']=0;
			$fromtime=Url::get('arrival_time')?Date_Time::to_time(Url::get('arrival_time')):Date_Time::to_time(date('d/m/Y',$row['time_in']));
			$totime=Url::get('departure_time')?Date_Time::to_time(Url::get('departure_time')):Date_Time::to_time(date('d/m/Y',$row['time_out']));
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
			$row['room_price'] = Url::get('price')?System::display_number(Url::get('price')):System::display_number($row['price']);
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
			//if(Url::get('included_deposit')){
			$row['deposit'] = Url::get('deposit')?Url::get('deposit'):$row['deposit'];
			//}else{
				//$row['deposit'] = 0;
			//}
			$row['reduce_balance'] = Url::get('reduce_balance')?floatval(Url::get('reduce_balance')):$row['reduce_balance'];
			//if(defined('FULL_RATE')){
				//$row['tax_rate'] = 10;
				//$row['service_rate'] = 5;
			//}else{
				$row['tax_rate'] = Url::get('tax_rate')?Url::get('tax_rate'):$row['tax_rate'];
				$row['service_rate'] = Url::get('service_rate')?Url::get('service_rate'):$row['service_rate'];
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
			foreach($room_statuses as $k=>$v){
				if($row['net_price']==1){
					$v['change_price'] = round($v['change_price']/1.155,2);	
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
				$items['ROOM_'.$v['room_status_id']]['net_amount'] = System::display_number($amount);
				if(isset($folio_other['ROOM_'.$v['room_status_id']])){
					if($folio_other['ROOM_'.$v['room_status_id']]['percent']==100 || $folio_other['ROOM_'.$v['room_status_id']]['amount'] ==$room_statuses[$k]['change_price'] ){
						$status = 1;
					}else{
						$percent = 100 - $folio_other['ROOM_'.$v['room_status_id']]['percent'];
						$amount = $amount - $folio_other['ROOM_'.$v['room_status_id']]['amount'];
					}
				}
				$items['ROOM_'.$v['room_status_id']]['id'] = $v['room_status_id'];
				$items['ROOM_'.$v['room_status_id']]['type'] = 'ROOM';
				$items['ROOM_'.$v['room_status_id']]['service_rate'] = $v['service_rate'];
				$items['ROOM_'.$v['room_status_id']]['tax_rate'] = $v['tax_rate'];
				$items['ROOM_'.$v['room_status_id']]['rr_id'] = $v['rr_id'];
				$items['ROOM_'.$v['room_status_id']]['status'] = $status;
				$items['ROOM_'.$v['room_status_id']]['date'] = $v['id'];
				$items['ROOM_'.$v['room_status_id']]['percent'] = $percent;
				$items['ROOM_'.$v['room_status_id']]['amount'] = System::display_number($amount);
				$items['ROOM_'.$v['room_status_id']]['description'] =  $row['room_name'].' '.Portal::language('room_charge').' '.$room_statuses[$k]['note'];
				if($row['foc'] !=''){
					$items['ROOM_'.$v['room_status_id']]['description'] .= '(FOC)';
				}
			}
			$i=0;
			$holidays = DB::fetch_all('select id,name,charge,in_date from holiday');
			$holiday = array();
			foreach($holidays as $key=>$value){
				$k = Date_Time::convert_orc_date_to_date($value['in_date'],'/');
				$holiday[$k]['id'] = $k;
				$holiday[$k]['name'] = $value['name'];
				$holiday[$k]['charge'] = $value['charge'];
			}
			//while($d>=$from and $d<=$to){
				//$ni = 3;
				//$day[$n]['date'] = date('d/m',$d);
				/*if($row['foc_all']){
					$day[$n]['foc_all'] = 'FOC';
				}else{
					$day[$n]['foc_all'] = 0;	
				}*/
				$room_price = 0;
				$row['extra_services'] = DB::fetch_all('
					select 
						extra_service_invoice_detail.*,
						(extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) as amount,
						0 as service_charge_amount,
						0 as tax_amount,
						DECODE(extra_service_invoice.tax_rate,\'\',0,extra_service_invoice.tax_rate) as tax_rate,
						DECODE(extra_service_invoice.service_rate,\'\',0,extra_service_invoice.service_rate) as service_rate,
						to_char(extra_service_invoice_detail.in_date,\'DD/MM\') as in_date
					from 
						extra_service_invoice_detail
						inner join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
					where 
						extra_service_invoice.reservation_room_id='.$id.'
						AND extra_service_invoice_detail.used = 1
						
				');//AND extra_service_invoice_detail.in_date = \''.Date_Time::to_orc_date(date('d/m/Y',$d)).'\'
				//if(Url::get('extra_service_invoice')){
				//AND extra_service_invoice.time >= \''.Date_Time::to_time(date('d/m/Y',$d)).'\'
				//AND extra_service_invoice.time <= \''.(Date_Time::to_time(date('d/m/Y',$d))+24*3600).'\'
				if(!empty($row['extra_services'])){	
					foreach($row['extra_services'] as $s_key=>$s_value){
						$percent = 100;$status = 0;
						$amount = $s_value['amount'];
						$items['EXTRA_SERVICE_'.$s_key]['net_amount'] = System::display_number($amount);
						if(isset($folio_other['EXTRA_SERVICE_'.$s_key])){
							if($folio_other['EXTRA_SERVICE_'.$s_key]['percent']==100 || $folio_other['EXTRA_SERVICE_'.$s_key]['amount'] ==$amount){
								$status = 1;
							}else{
								$percent = 100 - $folio_other['EXTRA_SERVICE_'.$s_key]['percent'];
								$amount = $amount - $folio_other['EXTRA_SERVICE_'.$s_key]['amount'];
							}
						}
						$items['EXTRA_SERVICE_'.$s_key]['id'] = $s_key;
						$items['EXTRA_SERVICE_'.$s_key]['type'] = 'EXTRA_SERVICE';
						$items['EXTRA_SERVICE_'.$s_key]['service_rate'] = $s_value['service_rate'];
						$items['EXTRA_SERVICE_'.$s_key]['tax_rate'] = $s_value['tax_rate'];
						$items['EXTRA_SERVICE_'.$s_key]['rr_id'] = $row['id'];
						$items['EXTRA_SERVICE_'.$s_key]['date'] = $s_value['in_date'];
						$items['EXTRA_SERVICE_'.$s_key]['percent'] = $percent;
						$items['EXTRA_SERVICE_'.$s_key]['status'] = $status;
						$items['EXTRA_SERVICE_'.$s_key]['amount'] = System::display_number($amount);
						$items['EXTRA_SERVICE_'.$s_key]['description'] = $row['room_name'].' '.$s_value['name'];
					}
				}
				//if(Url::get('room_invoice')){
					/*if(($d<=$to) or ($from==$to)){
						if(isset($room_statuses[date('d/m/Y',$d)]['change_price'])){
							$room_price += $room_statuses[date('d/m/Y',$d)]['change_price'];
							$day[$n]['full_price'] = ($room_statuses[date('d/m/Y',$d)]['full_price']);
							$day[$n]['change_price'] = ($room_statuses[date('d/m/Y',$d)]['change_price']);
							$day[$n]['room_status_id'] = $room_statuses[date('d/m/Y',$d)]['room_status_id'];
							$day[$n]['note'] = $room_statuses[date('d/m/Y',$d)]['note'];
						}
						if($room_price>0){
							if($row['show_price'] == 0){
								$room_price_label = $row['reservation_type_name'];
							}else{
								$room_price_label = System::display_number($room_price);
							}
							if($row['foc']){
								$room_price_label = 'FOC';
							}
							if($row['foc_all']){
								$room_price_label = 'FOC';
							}
							$day[$n]['room_price'] = $room_price_label;
							$day[$n]['full_price'] = System::display_number($day[$n]['full_price']);
							$day[$n]['change_price'] = System::display_number($day[$n]['change_price']);
							$day[$n]['tax_rate'] = $row['tax_rate'];
							$day[$n]['service_rate'] = $row['service_rate'];
							//$day[$n]['room_reduce_balance']=(str_replace(',','',$room_price)*($row['reduce_balance']/100));
							//$day[$n]['room_service_rate']=(str_replace(',','',$room_price-$day[$n]['room_reduce_balance'])*($row['service_rate']/100));
							$day[$n]['service_charge_amount'] = $room_price*($row['service_rate']/100);
							$tax_amount = ($room_price + $day[$n]['service_charge_amount'])*($row['tax_rate']/100);
							$day[$n]['tax_amount'] = round($tax_amount,2);
							if($row['show_price'] == 1 and !$row['foc']){
								$total_room_price+=$room_price;
							}
						}  */ 
					//}
				//}
				if($total_room_price==0 and Url::get('total_amount')){
					//$total_room_price = Url::get('total_amount');
				}
				//$row['total_room_price_before_discount']=System::display_number($total_room_price);
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
							$items['MINIBAR_'.$k]['net_amount'] = System::display_number($amount);
							if(isset($folio_other['MINIBAR_'.$k])){
								if($folio_other['MINIBAR_'.$k]['percent']==100 || $folio_other['MINIBAR_'.$k]['amount'] == $amount){
									$status = 1;
								}else{
									$percent = 100 - $folio_other['MINIBAR_'.$k]['percent'];
									$amount = $amount - $folio_other['MINIBAR_'.$k]['amount'];
								}
							}
							$items['MINIBAR_'.$k]['id'] = $k;
							$items['MINIBAR_'.$k]['type'] = 'MINIBAR';
							$items['MINIBAR_'.$k]['date'] = date('d/m',$minibar['time']);
							$items['MINIBAR_'.$k]['service_rate'] = $minibar['fee_rate'];
							$items['MINIBAR_'.$k]['tax_rate'] = $minibar['tax_rate'];
							$items['MINIBAR_'.$k]['percent'] = $percent;
							$items['MINIBAR_'.$k]['status'] = $status;
							$items['MINIBAR_'.$k]['rr_id'] = $id;
							$items['MINIBAR_'.$k]['amount'] = System::display_number($amount);
							$items['MINIBAR_'.$k]['description'] =  $row['room_name'].' '.Portal::language('minibar');
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
							$items['LAUNDRY_'.$k]['net_amount'] = System::display_number($amount);
							if(isset($folio_other['LAUNDRY_'.$k])){
								if($folio_other['LAUNDRY_'.$k]['percent']==100 || $folio_other['LAUNDRY_'.$k]['amount'] ==$amount){
									$status = 1;
								}else{
									$percent = 100 - $folio_other['LAUNDRY_'.$k]['percent'];
									$amount = $amount - $folio_other['LAUNDRY_'.$k]['amount'];
								}
							}
							$items['LAUNDRY_'.$k]['id'] = $k;
							$items['LAUNDRY_'.$k]['type'] = 'LAUNDRY';
							$items['LAUNDRY_'.$k]['date'] = date('d/m',$laundry['time']);
							$items['LAUNDRY_'.$k]['service_rate'] = $laundry['fee_rate'];
							$items['LAUNDRY_'.$k]['tax_rate'] = $laundry['tax_rate'];
							$items['LAUNDRY_'.$k]['percent'] = $percent;
							$items['LAUNDRY_'.$k]['status'] = $status;
							$items['LAUNDRY_'.$k]['rr_id'] = $id;
							$items['LAUNDRY_'.$k]['amount'] = System::display_number($amount);
							$items['LAUNDRY_'.$k]['description'] = $row['room_name'].' '.Portal::language('laundry');
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
							$items['EQUIPMENT_'.$k]['net_amount'] = System::display_number($amount);
							if(isset($folio_other['EQUIPMENT_'.$k])){
								if($folio_other['EQUIPMENT_'.$k]['percent']==100 || $folio_other['EQUIPMENT_'.$k]['amount'] ==$amount){
									$status = 1;
								}else{
									$percent = 100 - $folio_other['EQUIPMENT_'.$k]['percent'];
									$amount = $amount - $folio_other['EQUIPMENT_'.$k]['amount'];
								}
							}
							$items['EQUIPMENT_'.$k]['id'] = $k;
							$items['EQUIPMENT_'.$k]['type'] = 'EQUIPMENT';
							$items['EQUIPMENT_'.$k]['service_rate'] = $compensated_item['fee_rate'];
							$items['EQUIPMENT_'.$k]['tax_rate'] = $compensated_item['tax_rate'];
							$items['EQUIPMENT_'.$k]['percent'] = $percent;
							$items['EQUIPMENT_'.$k]['status'] = $status;
							$items['EQUIPMENT_'.$k]['rr_id'] = $id;
							$items['EQUIPMENT_'.$k]['date'] = date('d/m',$compensated_item['time']);
							$items['EQUIPMENT_'.$k]['amount'] = System::display_number($amount);
							$items['EQUIPMENT_'.$k]['description'] = $row['room_name'].' '.Portal::language('equipment');
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
							$percent = 100;$status = 0;
							$amount = $reser['total_before_tax'];
							$items['BAR_'.$bk]['net_amount'] = System::display_number($amount);
							if(isset($folio_other['BAR_'.$bk])){
								if($folio_other['BAR_'.$bk]['percent']==100 || $folio_other['BAR_'.$bk]['amount'] ==$amount){
									$status = 1;
								}else{
									$percent = 100 - $folio_other['BAR_'.$bk]['percent'];
									$amount = $amount - $folio_other['BAR_'.$bk]['amount'];
								}
							}
							$items['BAR_'.$bk]['id'] = $bk;
							$items['BAR_'.$bk]['type'] = 'BAR';
							$items['BAR_'.$bk]['date'] = date('d/m',$reser['time_out']);
							$items['BAR_'.$bk]['service_rate'] = $reser['bar_fee_rate'];
							$items['BAR_'.$bk]['tax_rate'] = $reser['tax_rate'];
							$items['BAR_'.$bk]['percent'] = $percent;
							$items['BAR_'.$bk]['status'] = $status;
							$items['BAR_'.$bk]['rr_id'] = $id;
							$items['BAR_'.$bk]['amount'] = System::display_number($amount);
							$items['BAR_'.$bk]['description'] = $row['room_name'].' '.Portal::language('restaurant');
							$row['deposit'] += $reser['bar_deposit'];
						}
					}
				//$d=$d+(3600*24);
			//}
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
						$percent = 100;$status = 0;
						$amount = $s_value['amount'];
						$items['SERVICE_'.$s_key]['net_amount'] = System::display_number($amount);
						if(isset($folio_other['SERVICE_'.$s_key])){
							if($folio_other['SERVICE_'.$s_key]['percent']==100 || $folio_other['SERVICE_'.$s_key]['amount'] ==$amount){
								$status = 1;
							}else{
								$percent = 100 - $folio_other['SERVICE_'.$s_key]['percent'];
								$amount = $amount - $folio_other['SERVICE_'.$s_key]['amount'];
							}
						}
						$items['SERVICE_'.$s_key]['id'] = $s_key;
						$items['SERVICE_'.$s_key]['type'] = 'SERVICE';
						$items['SERVICE_'.$s_key]['date'] = '';
						$items['SERVICE_'.$s_key]['service_rate'] = 0;
						$items['SERVICE_'.$s_key]['tax_rate'] = 0;
						$items['SERVICE_'.$s_key]['percent'] = $percent;
						$items['SERVICE_'.$s_key]['status'] = $status;
						$items['SERVICE_'.$s_key]['rr_id'] = $id;
						$items['SERVICE_'.$s_key]['amount'] = System::display_number($amount);
						$items['SERVICE_'.$s_key]['description'] = $row['room_name'].' '.Portal::language('service');
					}	
				}
				foreach($row['services'] as $s_key=>$s_value){
					if($s_value['type']=='ROOM'){
						$percent = 100;$status = 0;
						$amount = $s_value['amount'];
						$items['ROOM_SERVICE_'.$s_key]['net_amount'] = System::display_number($amount);
						if(isset($folio_other['ROOM_SERVICE_'.$s_key])){
							if($folio_other['ROOM_SERVICE_'.$s_key]['percent']==100 || $folio_other['ROOM_SERVICE_'.$s_key]['amount'] ==$amount){
								$status = 1;
							}else{
								$percent = 100 - $folio_other['ROOM_SERVICE_'.$s_key]['percent'];
								$amount = $amount - $folio_other['ROOM_SERVICE_'.$s_key]['amount'];
							}
						}
						$items['ROOM_SERVICE_'.$s_key]['id'] = $s_key;
						$items['ROOM_SERVICE_'.$s_key]['type'] = 'ROOM_SERVICE';
						$items['ROOM_SERVICE_'.$s_key]['date'] = '';
						$items['ROOM_SERVICE_'.$s_key]['service_rate'] = 0;
						$items['ROOM_SERVICE_'.$s_key]['tax_rate'] = 0;
						$items['ROOM_SERVICE_'.$s_key]['rr_id'] = $id;
						$items['ROOM_SERVICE_'.$s_key]['percent'] = $percent;
						$items['ROOM_SERVICE_'.$s_key]['status'] = $status;
						$items['ROOM_SERVICE_'.$s_key]['amount'] = System::display_number($amount);
						$items['ROOM_SERVICE_'.$s_key]['description'] = $row['room_name'].' '.Portal::language('room_service');
					}	
				}
	//----------------------------------------/Other services------------------------------------------------		
			if(URL::get('massage_invoice')){
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
				if($row['total_massage_amount'] = DB::fetch($sql_massage,'total_amount') and HAVE_MASSAGE){
					$percent = 100;$status = 0;
					$amount = $row['total_massage_amount'];
					$items['MASSAGE_'.$id]['net_amount'] = System::display_number($amount);
					if(isset($folio_other['MASSAGE_'.$id])){
						if($folio_other['MASSAGE_'.$id]['percent']==100 || $folio_other['MASSAGE_'.$id]['amount'] ==$amount){
							$status = 1;
						}else{
							$percent = 100 - $folio_other['MASSAGE_'.$id]['percent'];
							$amount = $amount - $folio_other['MASSAGE_'.$id]['amount'];
						}
					}
					$items['MASSAGE_'.$id]['id'] = $id;
					$items['MASSAGE_'.$id]['type'] = 'MASSAGE';
					$items['MASSAGE_'.$id]['service_rate'] = 0;
					$items['MASSAGE_'.$id]['tax_rate'] = 0;
					$items['MASSAGE_'.$id]['date'] = '';
					$items['MASSAGE_'.$id]['rr_id'] = $id;
					$items['MASSAGE_'.$id]['percent'] = $percent;
					$items['MASSAGE_'.$id]['status'] = $status;
					$items['MASSAGE_'.$id]['amount'] = System::display_number($amount);
					$items['MASSAGE_'.$id]['description'] = $row['room_name'].' '.Portal::language('massage');
				}
			}
			if(URL::get('tennis_invoice')){
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
					$percent = 100;$status = 0;
					$amount =$row['total_tennis_amount'];
					$items['TENNIS_'.$id]['net_amount'] = System::display_number($amount);
					if(isset($folio_other['TENNIS_'.$id])){
						if($folio_other['TENNIS_'.$id]['percent']==100 || $folio_other['TENNIS_'.$id]['amount'] ==$amount){
							$status = 1;
						}else{
							$percent = 100 - $folio_other['TENNIS_'.$id]['percent'];
							$amount = $amount - $folio_other['TENNIS_'.$id]['amount'];
						}
					}
					$items['TENNIS_'.$id]['id'] = $id;
					$items['TENNIS_'.$id]['type'] = 'TENNIS';
					$items['TENNIS_'.$id]['service_rate'] = 0;
					$items['TENNIS_'.$id]['tax_rate'] = 0;
					$items['TENNIS_'.$id]['date'] = '';
					$items['TENNIS_'.$id]['rr_id'] = $id;
					$items['TENNIS_'.$id]['percent'] = $percent;
					$items['TENNIS_'.$id]['status'] = $status;
					$items['TENNIS_'.$id]['amount'] = System::display_number($amount);
					$items['TENNIS_'.$id]['description'] = $row['room_name'].' '.Portal::language('tennis');
				}
			}
			if(URL::get('swimming_pool_invoice')){
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
					$percent = 100;$status = 0;
					$amount =$row['total_swimming_pool_amount'];
					$items['SWIMMING_POOL_'.$id]['net_amount'] = System::display_number($amount);
					if(isset($folio_other['SWIMMING_POOL_'.$id])){
						if($folio_other['SWIMMING_POOL_'.$id]['percent']==100 || $folio_other['SWIMMING_POOL_'.$id]['amount'] ==$amount){
							$status = 1;
						}else{
							$percent = 100 - $folio_other['SWIMMING_POOL_'.$id]['percent'];
							$amount = $amount - $folio_other['SWIMMING_POOL_'.$id]['amount'];
						}
					}
					$items['SWIMMING_POOL_'.$id]['id'] = $id;
					$items['SWIMMING_POOL_'.$id]['type'] = 'SWIMMING_POOL';
					$items['SWIMMING_POOL_'.$id]['service_rate'] = 0;
					$items['SWIMMING_POOL_'.$id]['tax_rate'] = 0;
					$items['SWIMMING_POOL_'.$id]['date'] = '';
					$items['SWIMMING_POOL_'.$id]['rr_id'] = $id;
					$items['SWIMMING_POOL_'.$id]['percent'] = $percent;
					$items['SWIMMING_POOL_'.$id]['status'] = $status;
					$items['SWIMMING_POOL_'.$id]['amount'] = System::display_number($amount);
					$items['SWIMMING_POOL_'.$id]['description'] = $row['room_name'].' '.Portal::language('swimming_pool');
				}
			}
			if(URL::get('karaoke_invoice')){
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
					$percent = 100;$status = 0;
					$amount =$row['total_karaoke_amount'];
					$items['KARAOKE_'.$id]['net_amount'] = System::display_number($amount);
					if(isset($folio_other['KARAOKE_'.$id])){
						if($folio_other['KARAOKE_'.$id]['percent']==100 || $folio_other['KARAOKE_'.$id]['amount'] ==$amount){
							$status = 1;
						}else{
							$percent = 100 - $folio_other['KARAOKE_'.$id]['percent'];
							$amount = $amount - $folio_other['KARAOKE_'.$id]['amount'];
						}
					}
					$items['KARAOKE_'.$id]['id'] = $id;
					$items['KARAOKE_'.$id]['type'] = 'KARAOKE';
					$items['KARAOKE_'.$id]['service_rate'] = 0;
					$items['KARAOKE_'.$id]['tax_rate'] = 0;
					$items['KARAOKE_'.$id]['date'] = '';
					$items['KARAOKE_'.$id]['rr_id'] = $id;
					$items['KARAOKE_'.$id]['percent'] = $percent;
					$items['KARAOKE_'.$id]['status'] = $status;
					$items['KARAOKE_'.$id]['amount'] = System::display_number($amount);
					$items['KARAOKE_'.$id]['description'] = $row['room_name'].' '.Portal::language('karaoke');
				}
			}
			if(URL::get('shop_invoice')){
				$sql_shop='
					SELECT 
						shop_invoice.RESERVATION_ROOM_ID,sum(shop_invoice.TOTAL) as total_amount
					FROM 
						shop_invoice
					WHERE
						shop_invoice.RESERVATION_ROOM_ID='.$id.'
					GROUP BY
						shop_invoice.RESERVATION_ROOM_ID
				';
				if($row['total_shop_amount'] = DB::fetch($sql_shop,'total_amount')){
					$percent = 100;$status = 0;
					$amount =$row['total_shop_amount'];
					$items['SHOP_'.$id]['net_amount'] = System::display_number($amount);
					if(isset($folio_other['SHOP_'.$id])){
						if($folio_other['SHOP_'.$id]['percent']==100 || $folio_other['SHOP_'.$id]['amount'] ==$amount){
							$status = 1;
						}else{
							$percent = 100 - $folio_other['SHOP_'.$id]['percent'];
							$amount = $amount - $folio_other['SHOP_'.$id]['amount'];
						}
					}
					$items['SHOP_'.$id]['id'] = $id;
					$items['SHOP_'.$id]['type'] = 'SHOP';
					$items['SHOP_'.$id]['service_rate'] = 0;
					$items['SHOP_'.$id]['tax_rate'] = 0;
					$items['SHOP_'.$id]['date'] = '';
					$items['SHOP_'.$id]['rr_id'] = $id;
					$items['SHOP_'.$id]['percent'] = $percent;
					$items['SHOP_'.$id]['status'] = $status;
					$items['SHOP_'.$id]['amount'] = System::display_number($amount);
					$items['SHOP_'.$id]['description'] = $row['room_name'].' '.Portal::language('shop');
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
					//if($row['exchange_currency_id']=='VND'){
						$percent = 100;$status = 0;
						$amount =$phone['total'];//$row['exchange_rate'],2);
						$items['TELEPHONE_'.$id]['net_amount'] = System::display_number($amount);
						if(isset($folio_other['TELEPHONE_'.$id])){
							if($folio_other['TELEPHONE_'.$id]['percent']==100 || $folio_other['TELEPHONE_'.$id]['amount'] ==$amount){
								$status = 1;
							}else{
								$percent = 100 - $folio_other['TELEPHONE_'.$id]['percent'];
								$amount = $amount - $folio_other['TELEPHONE_'.$id]['amount'];
							}
						}
						$items['TELEPHONE_'.$id]['id'] = $id;
						$items['TELEPHONE_'.$id]['type'] = 'TELEPHONE';
						$items['TELEPHONE_'.$id]['service_rate'] = 0;
						$items['TELEPHONE_'.$id]['tax_rate'] = (TELEPHONE_TAX_RATE)?TELEPHONE_TAX_RATE:0;
						$items['TELEPHONE_'.$id]['date'] = '';
						$items['TELEPHONE_'.$id]['rr_id'] = $id;
						$items['TELEPHONE_'.$id]['percent'] = $percent;
						$items['TELEPHONE_'.$id]['status'] = $status;
						$items['TELEPHONE_'.$id]['amount'] = System::display_number($amount);
						$items['TELEPHONE_'.$id]['description'] = $row['room_name'].' '.Portal::language('telephone');
					}
				//}
			}
			//thong tin cuoi cung cua check out
			$row['deposit'] = $row['deposit'];
			if($row['reduce_amount'] != '' && $row['reduce_amount']>0){
				$percent = 100;$status = 0;
				$amount = $row['reduce_amount'];
				$items['DISCOUNT_'.$id]['net_amount'] = System::display_number($amount);
				if(isset($folio_other['DISCOUNT_'.$id])){
					if($folio_other['DISCOUNT_'.$id]['percent']==100 || $folio_other['DISCOUNT_'.$id]['amount'] ==$amount){
						$status = 1;
					}else{
						$percent = 100 - $folio_other['DISCOUNT_'.$id]['percent'];
						$amount = $amount - $folio_other['DISCOUNT_'.$id]['amount'];
					}
				}
				$items['DISCOUNT_'.$id]['id'] = $id;
				$items['DISCOUNT_'.$id]['type'] = 'DISCOUNT';
				$items['DISCOUNT_'.$id]['service_rate'] = 0;
				$items['DISCOUNT_'.$id]['tax_rate'] = 0;
				$items['DISCOUNT_'.$id]['date'] = '';
				$items['DISCOUNT_'.$id]['rr_id'] = $id;
				$items['DISCOUNT_'.$id]['percent'] = $percent;
				$items['DISCOUNT_'.$id]['status'] = $status;
				$items['DISCOUNT_'.$id]['amount'] = System::display_number($amount);
				$items['DISCOUNT_'.$id]['description'] = $row['room_name'].' '.Portal::language('discount_room');
			}
			if($row['deposit']>0){
				$percent = 100;$status = 0;
				$amount =$row['deposit'];
				$items['DEPOSIT_'.$id]['net_amount'] = System::display_number($amount);
				if(isset($folio_other['DEPOSIT_'.$id])){
					if($folio_other['DEPOSIT_'.$id]['percent']==100 || $folio_other['DEPOSIT_'.$id]['amount'] ==$amount){
						$status = 1;
					}else{
						$percent = 100 - $folio_other['DEPOSIT_'.$id]['percent'];
						$amount = $amount - $folio_other['DEPOSIT_'.$id]['amount'];
					}
				}
				$items['DEPOSIT_'.$id]['id'] = $id;
				$items['DEPOSIT_'.$id]['type'] = 'DEPOSIT';
				$items['DEPOSIT_'.$id]['service_rate'] = 0;
				$items['DEPOSIT_'.$id]['tax_rate'] = 0;
				$items['DEPOSIT_'.$id]['date'] = '';
				$items['DEPOSIT_'.$id]['rr_id'] = $id;
				$items['DEPOSIT_'.$id]['percent'] = $percent;
				$items['DEPOSIT_'.$id]['status'] = $status;
				$items['DEPOSIT_'.$id]['amount'] = System::display_number($amount);
				$items['DEPOSIT_'.$id]['description'] = $row['room_name'].' '.Portal::language('deposit_room');
			}
			return $items;
		}
?>