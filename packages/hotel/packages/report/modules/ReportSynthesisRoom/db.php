<?php
    class ReportSynthesisRoomDB
    {
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
				inner join room on room.id=reservation_room.room_id
				left outer join customer on customer.id = reservation.customer_id
				left outer join reservation_type on reservation_type.id=reservation_room.reservation_type_id
				left outer join reservation_traveller on reservation_traveller.reservation_room_id=reservation_room.id
				left outer join traveller on reservation_traveller.traveller_id=traveller.id
				';
		//============================Thong tin hoa don moi-------------------------------//
		$row = DB::fetch($sql.' where reservation_room.id='.$id.'');
		//System::Debug($row);
		if(HOTEL_CURRENCY == 'VND')
        {
			$row['exchange_currency_id'] = 'USD';
		}
        else
        {
			$row['exchange_currency_id'] = 'VND';	
		}
			$row['exchange_rate'] = DB::fetch('select id,exchange from currency where id=\''.$row['exchange_currency_id'].'\'','exchange');
	//--------------------------------------------------------------------------------------------------		
			$row['discount_total']=0;
            //echo $id.'===========';
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
			if($row['foc_all'])
            {
				$row['room_price'] = 'FOC';
			} 
            if(Url::get('included_deposit'))
            {
			$row['deposit'] = Url::get('deposit')?Url::get('deposit'):$row['deposit'];
            }else
            {
			$row['deposit'] = 0;
            }
			$row['reduce_balance'] = $row['reduce_balance'];
				$row['tax_rate'] = $row['tax_rate'];
				$row['service_rate'] = $row['service_rate'];
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
			//System::debug($room_statuses);
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
            $items['room'] = 0;
			foreach($room_statuses as $k=>$v)
            {
				if($row['net_price']==1)
                {
                    if($row['service_rate'] == '')
                    {
					$row['service_rate'] = 0;
				    }
					$param = (1+($row['tax_rate']*0.01) + ($row['service_rate']*0.01)+ (($row['tax_rate']*0.01)*($row['service_rate']*0.01)));
					$v['change_price'] = round($v['change_price']/$param,2);	
				}
				if($row['foc_all'] ==1 || $row['foc'] !=''){
					$v['change_price'] =  'FOC';
				}
                //$row['discount_total'] += ($row['reduce_balance']?($row['reduce_balance'])*$room_status['change_price']/100:0);
				$tt = ($row['reduce_balance']?(100 - $row['reduce_balance'])*$v['change_price']/100:$v['change_price']);
				if($row['reduce_balance']>0 && $row['reduce_balance']!='')
                {
					$room_statuses[$k]['note'] = '( Discounted '.$row['reduce_balance'].'%)';
				}
                else
                {
					$room_statuses[$k]['note'] = '';
				}
				$room_statuses[$k]['change_price'] = $tt;
				$percent = 100;$status = 0;
				$amount = $room_statuses[$k]['change_price'];
                $items['room'] += ($amount*$v['service_rate']/100 + $amount)*(1 + $v['tax_rate']/100);
			}
            //___________________________________________Tinh EI LI______________________________________
				$row['extra_services_EI_LO'] = DB::fetch_all('
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
                        AND (extra_service_invoice_detail.name = \'Early checkin\'
                            or extra_service_invoice_detail.name = \'Late checkout\')	
				');
                //System::debug($row['extra_services_EI_LO']);
                //dich vu kjhac
                $items['extra_services_EI_LO'] = 0;
				if(!empty($row['extra_services_EI_LO']))
                {	
					foreach($row['extra_services_EI_LO'] as $s_key=>$s_value)
                    {
						if($row['foc_all'] ==1){
            					$s_value['amount'] =  'FOC';
            				}
                        $amount = $s_value['amount'];
                        $items['extra_services_EI_LO'] += ($amount*$s_value['service_rate']/100 + $amount)*(1 + $s_value['tax_rate']/100);
					}
				}
            //___________________________________________/Tinh EI LI______________________________________
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
                        AND extra_service_invoice_detail.name != \'Early checkin\'
                        AND extra_service_invoice_detail.name != \'Late checkout\'	
				');
                //System::debug($row['extra_services']);
                //dich vu kjhac
                $items['extra_services'] = 0;
				if(!empty($row['extra_services']))
                {	
					foreach($row['extra_services'] as $s_key=>$s_value)
                    {
						if($row['foc_all'] ==1)
                        {
            				$s_value['amount'] =  'FOC';
                        }
                        $amount = $s_value['amount'];
                        $items['extra_services'] += ($amount*$s_value['service_rate']/100 + $amount)*(1 + $s_value['tax_rate']/100);
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
                    $items['minibar'] = 0;
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
                            if($row['foc_all'] ==1){
            					$minibar['total_before_tax'] =  'FOC';
            				}
							$percent = 100;$status = 0;
							$amount = $minibar['total_before_tax'];
                            $items['minibar'] += ($amount*$minibar['fee_rate']/100 + $amount)*(1 + $minibar['tax_rate']/100);
							$items['MINIBAR_'.$k]['amount'] = System::display_number($amount);
						}
					}
                    
		//--------------------------------------------laundry--------------------------------------------------------
					DB::query($sql_l);
                    $items['laundry'] = 0; 
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
                            if($row['foc_all'] ==1 ){
            					$laundry['total_before_tax'] =  'FOC';
            				}
							$percent = 100;$status = 0;
							$amount = $laundry['total_before_tax'];
                            $items['laundry'] += ($amount*$laundry['fee_rate']/100 + $amount)*(1 + $laundry['tax_rate']/100);
							$items['LAUNDRY_'.$k]['amount'] = System::display_number($amount);
                            }
					}
	//--------------------------------------------/laundry--------------------------------------------------------
					DB::query($sql_compensated_item);
                    $items['equipment'] = 0;
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
                            if($row['foc_all'] ==1){
            					$compensated_item['total_before_tax'] =  'FOC';
            				}
							$percent = 100;$status = 0;
							$amount = $compensated_item['total_before_tax'];
							$items['equipment'] += ($amount*$compensated_item['fee_rate']/100 + $amount)*(1 + $compensated_item['tax_rate']/100);
							$items['EQIPMENT_'.$k]['amount'] = System::display_number($amount);
						}
					}
	////-------------------------------------------------Other services----------------------------------------
//			$all_services = DB::fetch_all('
//				select 
//					service_id as id,reservation_room_service.amount,reservation_room_id,service.name,service.type,
//					0 as service_charge_amount, 0 as tax_amount,reservation_room_service.id as room_service_id
//				from 
//					reservation_room_service 
//					inner join service on service.id = service_id 
//				where 
//					reservation_room_id= '.$id.'
//			');
//			$row['services'] = $all_services;
//				foreach($row['services'] as $s_key=>$s_value){
//					if($s_value['type']=='SERVICE'){
//						$amount = $s_value['amount'];
//						$items['SERVICE_'.$s_key]['amount'] = System::display_number($amount);
//                        $items['SERVICE_'.$s_key]['service_rate'] = 0;
//						$items['SERVICE_'.$s_key]['tax_rate'] = 0;
//					}	
//				}
//				foreach($row['services'] as $s_key=>$s_value){
//					if($s_value['type']=='ROOM'){
//						$amount = $s_value['amount'];
//						$items['ROOM_SERVICE_'.$s_key]['amount'] = System::display_number($amount);
//                        $items['ROOM_SERVICE_'.$s_key]['service_rate'] = 0;
//						$items['ROOM_SERVICE_'.$s_key]['tax_rate'] = 0;
//					}	
//				}
//	//----------------------------------------/Other services------------------------------------------------		
			
			//thong tin cuoi cung cua check out
			//$row['deposit'] = $row['deposit'];
			if($row['reduce_amount'] != '' && $row['reduce_amount']>0)
            {
				$percent = 100;$status = 0;
				$amount = $row['reduce_amount'];
				$items['DISCOUNT']['service_rate'] = 0;
				$items['DISCOUNT']['tax_rate'] = 0;
				$items['DISCOUNT']['amount'] = System::display_number($amount);
                $items['room'] -= $amount;
			}
            return $items;
            if($row['foc_all'])
            {
			
			}
	}
    }
?>

					