<?php
function update_reservation_room(&$form, $id, &$title, &$description, &$customer_name, &$change_status,$old_reservation_room)
{	
	$currencies = DB::select_all('currency','allow_payment=1');
	$services = DB::select_all('service');
	$min_arr_date='';
	if(isset($_REQUEST['mi_reservation_room']))
	{
		$change_status = array();
		$change_price_arr = array();
		$currency_arr = array();
		foreach($_REQUEST['mi_reservation_room'] as $key=>$record)
		{
            unset($record['house_status']);		  
			unset($record['reservation_traveller_id']);
			unset($record['deposit']);
			unset($record['deposit_date']);
			unset($record['late_checkout_amount']);
			unset($record['late_checkout_date']);
			unset($record['early_checkin_amount']);
			unset($record['early_checkin_date']);
            
            $auto_late_checkin_price = $record['auto_late_checkin_price'];
            unset($record['auto_late_checkin_price']);
            //giap.ln add 
            unset($record['total_key']);
            //end
            /** manh check luoc do gia extra_bed **/
            if(isset($record['change_price_extra_bed_arr']))
            {
                $change_price_extra_bed_arr = $record['change_price_extra_bed_arr'];
                unset($record['change_price_extra_bed_arr']);
            }
            /** end manh **/
			if(isset($record['net_price'])){
				$record['net_price'] = 1;
			}else{
				$record['net_price'] = 0;
			}  
            if(isset($record['do_not_move']))
            {
				$record['do_not_move'] = 1;
                if( (isset($old_reservation_room[$record['id']]['do_not_move']) AND $old_reservation_room[$record['id']]['do_not_move']==0) OR (!isset($old_reservation_room[$record['id']]['do_not_move'])) )
                {
                    $record['user_do_not_move'] = User::id();
                }
			}
            else
            {
				$record['do_not_move'] = 0;
                $record['user_do_not_move'] = '';
			} 
            if(isset($old_reservation_room[$record['id']]['time_in']))
            {
                $old_ci_time = $old_reservation_room[$record['id']]['time_in'];
            }
            else
            {
                $old_ci_time = '';
            }           
			if(!isset($record['status']) and isset($record['old_status'])){
				$record['status'] = $record['old_status'];
			}
			if(isset($record['confirm']) or $record['status']=='CHECKIN'){
				$record['confirm'] = 1;
			}else{
				$record['confirm'] = 0;
			}
            if(isset($record['allotment']))
                $record['allotment'] = 1;
            else
                $record['allotment'] = 0;
            if(isset($record['breakfast'])){
				$record['breakfast'] = 1;
			}else{
				$record['breakfast'] = 0;
			}
			if(isset($record['extra_bed'])){
				$record['extra_bed'] = 1;
			}else{
				$record['extra_bed'] = 0;
				$record['extra_bed_from_date'] = '';
				$record['extra_bed_to_date'] = '';
				$record['extra_bed_rate'] = 0;				
			}
			if(isset($record['baby_cot'])){
				$record['baby_cot'] = 1;
			}else{
				$record['baby_cot'] = 0;
				$record['baby_cot_from_date'] = '';
				$record['baby_cot_to_date'] = '';
				$record['baby_cot_rate'] = 0;
			}								
			if(isset($record['discount_after_tax'])){
				$record['discount_after_tax'] = 1;
			}else{
				$record['discount_after_tax'] = 0;
			}
			if(isset($record['closed']) or $record['status']=='CHECKOUT'){
				$record['closed'] = 1;
			}
			if(isset($record['closed']) or $record['status']=='CHECKOUT'){
				$record['closed'] = 1;
			}else{
				$record['closed'] = 0;
			}
			if(!isset($record['early_checkin'])){
				$record['early_checkin'] = 0;
			}
			if(!isset($record['late_checkout'])){
				$record['late_checkout'] = 0;
			}
			$record['verify_dayuse'] = $record['early_checkin'] + $record['late_checkout'];
			if(isset($record['total_payment'])){
				unset($record['total_payment']);
			}
			if(isset($record['remain_amount'])){
				unset($record['remain_amount']);
			}
			if(isset($record['paid_amount'])){
				unset($record['paid_amount']);
			}
			foreach($currencies as $c_value){
				if(isset($record['currency_'.$c_value['id']])){
					$currency_arr[$c_value['id']]['id'] = $c_value['id'];
					$currency_arr[$c_value['id']]['value'] = str_replace(',','',$record['currency_'.$c_value['id']]);
					$currency_arr[$c_value['id']]['exchange_rate'] = $c_value['exchange'];
					unset($record['currency_'.$c_value['id']]);
				}
			}
			if(isset($record['change_price_arr']))
			{
				$change_price_arr = System::calculate_number($record['change_price_arr']);
				unset($record['change_price_arr']);
			}
			unset($record['room_level_name']);
			$def_code = '';
			if(isset($record['def_code']) and $record['def_code'] and $payment_type_id = DB::fetch('select id,def_code from payment_type where def_code =\''.$record['def_code'].'\'','id')){
				$def_code = $record['def_code'];
				$record['payment_type_id'] = $payment_type_id;
			}
			unset($record['def_code']);
			/////////////////////////////////Update room log//////////////////////////////////////////
            
			update_room_log($old_reservation_room,$record,$description);
			$record['total_amount'] = (isset($record['total_amount']) and $record['total_amount'])?str_replace(',','',$record['total_amount']):0;
			$record['price'] =	 str_replace(',','',$record['price']);
            $record['usd_price'] =	 str_replace(',','',$record['usd_price']);
			if($record['time_in'])
			{
				$arr = explode(':',$record['time_in']);
				$record['time_in']= Date_Time::to_time($record['arrival_time'])+ intval($arr[0])*3600+intval($arr[1])*60; 
			}else{
				$record['time_in']= Date_Time::to_time($record['arrival_time']);
			}
			if($record['time_out'])
			{
				$arr = explode(':',$record['time_out']);
				$record['time_out']= Date_Time::to_time($record['departure_time'])+ intval($arr[0])*3600+intval($arr[1])*60; 
			}else{
				$record['time_out'] = Date_Time::to_time($record['departure_time']);
			}
            if($record['commission_rate'])
			{
				$record['commission_rate']= System::calculate_number($record['commission_rate']);
			}else{
				$record['commission_rate'] = 0;
			}
			if(!$record['room_id']){
				$record['temp_room'] = $record['room_name'];
			}else{
				$record['temp_room'] = '';
			}
			unset($record['room_name']);
			unset($record['room_name_old']);
			unset($record['room_id_old']);
			unset($record['departure_time_old']);
			if(isset($record['early_arrival_time']) and $record['early_arrival_time']){
				$record['early_arrival_time'] = Date_Time::to_orc_date($record['early_arrival_time']);
			}
			if(Date_Time::to_time($min_arr_date) > Date_Time::to_time($record['arrival_time']) || $min_arr_date=='')
            {
				$min_arr_date = $record['arrival_time'];
			}
			$record['arrival_time']=Date_Time::to_orc_date($record['arrival_time']); 
			$record['departure_time']=Date_Time::to_orc_date($record['departure_time']);
			$record['extra_bed_from_date'] = Date_Time::to_orc_date($record['extra_bed_from_date']); 
			$record['extra_bed_to_date'] = Date_Time::to_orc_date($record['extra_bed_to_date']);
			$record['baby_cot_from_date'] = Date_Time::to_orc_date($record['baby_cot_from_date']); 
			$record['baby_cot_to_date'] = Date_Time::to_orc_date($record['baby_cot_to_date']);			
			$record['extra_bed_rate'] = System::calculate_number($record['extra_bed_rate']);
			$record['baby_cot_rate'] = System::calculate_number($record['baby_cot_rate']);			
			$record['reservation_id'] = $id;
			$record['foc_all'] = isset($record['foc_all'])?1:0;
			///////////////////////////////////////////EXCHANGE RATE////////////////////////////////////////////////
			$old_status = $record['old_status'];
			unset($record['old_status']);
			$currency_id = (HOTEL_CURRENCY == 'VND')?'USD':'VND';
			if($old_status!='CHECKOUT')
            {
				$record['exchange_rate'] = DB::fetch('select id,exchange from currency where id=\''.$currency_id.'\'','exchange');
			}
			///////////////////////////////////////////CHECKED IN USER ID///////////////////////////////////////////
			if(($old_status=='BOOKED' or !$old_status) and $record['status']=='CHECKIN'){
				$record['checked_in_user_id'] = Session::get('user_id');
			}
			if(($old_status=='CHECKIN') and $record['status']=='CHECKOUT'){
				$record['checked_out_user_id'] = Session::get('user_id');
			}
			///////////////////////////////////////////BOOKED USER ID///////////////////////////////////////////////
			if(!$old_status and $record['status']=='BOOKED'){
				$record['booked_user_id'] = Session::get('user_id');
			}
			////////////////////////////////////////////////////////////////////////////////////////////////////////
            //tai khoan cancel
            if($old_status=='BOOKED' and $record['status']=='CANCEL'){
				$record['cancel_user_id'] = Session::get('user_id');
                $record['time_cancel'] = time();
			}
            //////////////////
			if($def_code == 'DEBIT'){
				$record['related_rr_id'] = ($record['related_rr_id'] and DB::exists('select id from reservation_room where id = \''.$record['related_rr_id'].'\''))?$record['related_rr_id']:0;
			}else{
				$record['related_rr_id'] = 0;
			}
			$old_change_price_arr = array();
			$check = false;
			////////////////////////////////////////Truong hop edit//////////////////////////////////////////////
			if($record['id'] and isset($old_reservation_room[$record['id']]))
			{
			     /** manh them dong nay de tu dong check latecheckin **/
                //update_verify_dayuse_invoice($record['id']);
				//-----Update phong neu co REPAIR-----------------------//
				if($old_reservation_room[$record['id']]['room_id']!=$record['room_id'])
                {
					$room_status = DB::fetch_all('select * from room_status where reservation_room_id = '.$record['id'].' AND house_status=\'REPAIR\'');	
					if(!empty($room_status))
                    {
						foreach($room_status as $rs => $status)
                        {
							$status['reservation_room_id'] = 0;
							$status['reservation_id'] = 0;
							$status['change_price'] = 0;
							$status['status'] = 'AVAILABLE';
							unset($status['id']);
							DB::insert('room_status',$status);
						}
						$check = true;
					}

				}
                // kid 
                $sql = '
    				SELECT
    					reservation_traveller.id
    					,reservation_traveller.arrival_date
    				FROM
    					reservation_traveller
    					inner join reservation_room on reservation_room.id = reservation_traveller.reservation_room_id
    				WHERE
    					reservation_room_id = '.$record['id'].'
    			';
                $old_travellers = DB::fetch_all($sql);
                //Date_time::convert_orc_date_to_date();
                foreach($old_travellers as $ke => $val)
                {
                    if(Date_time::to_time(Date_time::convert_orc_date_to_date($val['arrival_date'],"/")) < Date_time::to_time(Date_time::convert_orc_date_to_date($record['arrival_time'],"/")))
                    {
                        
                        DB::update('reservation_traveller',array(
							'arrival_time'=>$record['time_in'],
							'arrival_date'=>$record['arrival_time'],
					   ),' reservation_room_id = '.$record['id'].'and id ='.$val['id'].' ');
                    }
                }
                
				// Update gio den va di cua khach
				//if($old_status=='BOOKED' and $record['status']=='CHECKIN'){
//				    
//					DB::update('reservation_traveller',array(
//							'arrival_time'=>$record['time_in'],
//							'arrival_date'=>$record['arrival_time'],
//							'departure_time'=>$record['time_out'],  
//							'departure_date'=>$record['departure_time'],
//							'status'=>'CHECKIN'
//					),' reservation_room_id = '.$record['id'].' AND status = \'CHECKIN\'');
//				}else{
					DB::update('reservation_traveller',array(
							'departure_time'=>$record['time_out'],  
							'departure_date'=>$record['departure_time'],
					),' reservation_room_id = '.$record['id'].' AND status = \'CHECKIN\'');	
				//}
				if($old_status=='CHECKIN' and $record['status']=='CHECKOUT'){
					DB::update('reservation_traveller',array(
							'departure_time'=>$record['time_out'],  
							'departure_date'=>$record['departure_time'],
							'status'=>'CHECKOUT'
					),' reservation_room_id = '.$record['id'].' AND status = \'CHECKIN\'');
				}
				// END
				/////////////////////////////////////Update change price log/////////////////////////////////////////
				$old_change_price_arr = DB::fetch_all('SELECT to_char(in_date,\'DD/MM/YYYY\') as id,in_date,change_price FROM room_status WHERE reservation_room_id = '.$record['id'].'');
				update_change_price_log($record['id'],$old_change_price_arr,$change_price_arr,$description);
				if($record['status']!=$old_reservation_room[$record['id']]['status'] and $record['room_id'])// sua them truong hop room_id co the de trong
				{
					$change_status[$record['room_id']] = true;
				}
				if($old_status!='CHECKOUT' or $old_status!='CANCEL'){
					$record['lastest_edited_user_id'] = Session::get('user_id');
					$record['lastest_edited_time'] = time();
				}
				$record_ = $record;               
				foreach($currency_arr as $c_a_key=>$c_a_value){
					if(isset($record_['pay_by_'.strtolower($c_a_key)])){
						unset($record_['pay_by_'.strtolower($c_a_key)]);
					}
				}
				$record_['bill_number'] = 'RE'.$record['id'];
				$record_['reduce_amount'] = System::calculate_number($record['reduce_amount']);
                //giap.ln truong hop thay doi package_sale_id
                if(isset($record_['package_sale_id']) && $record_['package_sale_id']!='')
                    unset($record_['package_sale_id']); 
                //end 
				DB::update('reservation_room',$record_+array('customer_name'=>$customer_name),'id='.$record['id']);
			}
			else ////////////////////////////////Truong hop them moi////////////////////////////////
			{
				unset($record['id']);
				$record['user_id'] = Session::get('user_id');
				$record['time'] = time();
                $record['confirm'] = 1;
				$record['reduce_amount'] = System::calculate_number($record['reduce_amount']);
				$record['id'] = DB::insert('reservation_room',$record+array('customer_name'=>$customer_name));
				DB::update('reservation_room',array('bill_number'=>'RE'.$record['id']),'id='.$record['id']);
				if($record['room_id']){// sua them truong hop room_id co the de trong
					$change_status[$record['room_id']] = true;
				}
                /** manh them dong nay de tu dong check latecheckin **/
                //update_verify_dayuse_invoice($record['id']);
			}
			if(isset($record['extra_bed']))
			{
			    if(!isset($change_price_extra_bed_arr))
                    $change_price_extra_bed_arr = array(); 
				//update_extra_bed_invoice($record['id'],$change_price_extra_bed_arr);
			}
			if(isset($record['baby_cot']))
			{
				//update_baby_cot_invoice($record['id']);
			}
			
            
			//----------------------Update currencies--------------------------------
			/*if($def_code=='CASH'){
				foreach($currency_arr as $c_a_key=>$c_a_value){
					if($c_a_value['value']){
						if($row=DB::fetch('select * from pay_by_currency where bill_id='.$record['id'].' and currency_id=\''.$c_a_value['id'].'\' and type=\'RESERVATION\'')){
							DB::update('pay_by_currency',array('exchange_rate'=>$c_a_value['exchange_rate'],'amount'=>str_replace(',','',$c_a_value['value'])),'id='.$row['id']);
						}else{
							DB::insert('pay_by_currency',array('bill_id'=>$record['id'],'currency_id'=>$c_a_value['id'],'amount'=>str_replace(',','',$c_a_value['value']),'exchange_rate'=>$c_a_value['exchange_rate'],'type'=>'RESERVATION'));
						}
					}
					else
					{
						DB::delete('pay_by_currency','bill_id='.$record['id'].' and currency_id=\''.$c_a_value['id'].'\' and type=\'RESERVATION\'');
					}
				}
			}else{
				DB::delete('pay_by_currency','bill_id='.$record['id']);
			}*/
            
			reservation_update_room_map($form, $id, $record,isset($change_status[$record['room_id']])?$change_status[$record['room_id']]:'',$change_price_arr,$old_reservation_room,$check,$old_status);
            
            //giap.ln thay doi luoc do gia khi co su dung goi package tien phong
            
            if(isset($record['package_sale_id']) && $record['package_sale_id']!='')
            {
                update_change_price_using_package($record);
            }
            else
            {
                $rows = DB::fetch_all("SELECT * FROM room_status WHERE reservation_room_id=".$record['id']." AND is_package_room=1");
                
                if(empty($rows)==false)
                {
                    
                    DB::update('room_status',array('is_package_room'=>0,'change_price'=>$record['price']),'reservation_room_id='.$record['id'].' AND is_package_room=1');
                }
            }
            
            //end giap.ln 
			
            update_verify_dayuse_invoice($record['id'],$auto_late_checkin_price,$old_status,$record['status'],$old_ci_time,$old_reservation_room);
            /** 07/08/17 dayused co Li thi luoc do gia =0 **/
            if($record['arrival_time']==$record['departure_time'])
            {
                $li = DB::fetch_all('select * from extra_service_invoice_detail
                            inner join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id 
                            inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                            where 
                                extra_service.code = \'LATE_CHECKIN\'
                                and extra_service_invoice.reservation_room_id = '.$record['id'].'  
                            ');
                if(sizeof($li)>0)
                {
                    DB::update('room_status',array('change_price'=>0),'reservation_room_id='.$record['id'].'');
                }
                
            }
            /** end 07/08/17 dayused co Li thi luoc do gia =0 **/
            
            if(($old_status=='BOOKED' or !$old_status) and $record['status']=='CHECKIN')
            {
                
				if(TELEPHONE_CONFIG)
				{
				    
					//config_telephone('CHECKIN',$record['room_id'],$record['id']);
                    
				}				
			}
			if(($old_status=='CHECKIN') and $record['status']=='CHECKOUT')
            {
				if(TELEPHONE_CONFIG)
				{
					//config_telephone('CHECKOUT',$record['room_id'],$record['id']);
				}				
			}
            			
		}
	}
    //exit();
	if(Url::get('cut_of_date') && Date_Time::to_time($min_arr_date)>Date_Time::to_time(Url::get('cut_of_date')))
    {
		$min_arr_date = Url::get('cut_of_date');	
	}
	DB::update('reservation',array('cut_of_date'=>''.Date_Time::to_orc_date($min_arr_date).''),' id='.$id);   
	/////////////////////////////////Fix room status/////////////////////////////////////////////////
	
    $items = DB::fetch_all('SELECT room_status.id FROM room_status INNER JOIN reservation_room on reservation_room.id = reservation_room_id WHERE room_status.reservation_id = '.$id.' AND (in_date > departure_time or in_date < arrival_time)');
	foreach($items as $key=>$value)
    {
		DB::delete('room_status','id='.$value['id']);
	}
	/*-------------------------------update total_payment----------------------------
	if(isset($_REQUEST['mi_reservation_room']))
	{
		require_once 'packages/hotel/packages/reception/modules/includes/get_reservation.php';
		foreach($_REQUEST['mi_reservation_room'] as $key=>$record)
		{
			if($record['id'] and isset($old_reservation_room[$record['id']])){
				$reservation_arr = get_reservation($id,$record['id']);
				if(isset($reservation_arr['items'][$record['id']])){
					DB::update('reservation_room',array('total_payment'=>$reservation_arr['items'][$record['id']]['total_amount']),'id='.$record['id']);
				}
			}
		}
	}*/
	//------------------------------/update total_payment----------------------------
	if(Url::get('tour_id')){
		update_tour(Url::get('tour_id'),$id);
	}
}
//giap.ln truong hop su dung package tien phong => cap nhat lai change_price
function update_change_price_using_package($record)
{
    //kiem tra co tien phong package ko?
    $package_sale_id = $record['package_sale_id'];
    $sql="SELECT package_sale_detail.* 
            FROM package_sale_detail 
            INNER JOIN package_service ON package_service.id=package_sale_detail.service_id
            WHERE package_sale_detail.package_sale_id=".$package_sale_id." 
            AND package_service.code='ROOM'
            ORDER BY package_sale_detail.id";
    $arr_package_sale = DB::fetch_all($sql);
    if(empty($arr_package_sale)==false)
    {
        //lay ra cac thong so can thiet de thay doi trong change_price trong khoang thoi gian nao
        $arrival_time = Date_Time::to_time(Date_Time::convert_orc_date_to_date($record['arrival_time'],'/')); 
        $departure_time = Date_Time::to_time(Date_Time::convert_orc_date_to_date($record['departure_time'],'/'));
        //reset nhung package cu da the hien
        DB::update('room_status',array('change_price'=>$record['price'],'is_package_room'=>0),'reservation_room_id='.$record['id'].' AND is_package_room=1');
        //lam lai package moi 
        foreach($arr_package_sale as $key=>$value)
        {
            if($arrival_time>=$departure_time)
            {
                break;
            }
            $num_night = $value['quantity'];
            $price = $value['price'];
            while($num_night>0)
            {
                if($arrival_time>=$departure_time)
                {
                    break;
                }
                DB::update('room_status',array('change_price'=>$price,'is_package_room'=>1),' reservation_room_id='.$record['id'].' AND reservation_id='.$record['reservation_id'].' AND date_to_unix(in_date)='.$arrival_time);
                $arrival_time +=86400;
                $num_night--;
            }
        }
        
        DB::update('reservation_room',array('package_sale_id'=>$package_sale_id),'id='.$record['id']);
        
    }
    else //khong chua package tien phong 
    {
        DB::update('room_status',array('change_price'=>$record['price'],'is_package_room'=>0),'reservation_room_id='.$record['id'].' AND is_package_room=1');
        DB::update('reservation_room',array('package_sale_id'=>$package_sale_id),'id='.$record['id']);
    }
} 
//end giap.ln
function update_reservation_traveller(&$form, $id,$rr_id, $old_travellers, &$title, &$description, &$customer_name, &$change_status)
{
	$rt_id = 0;
      
	if(isset($_REQUEST['mi_traveller']) or $old_travellers)
	{  
		$i=0;
		$count_travellers = array();
        $total_adult = array();
        $total_child = array();
		if(isset($_REQUEST['mi_traveller']))
		{
			$traveller_id = 0;
			//System::Debug($_REQUEST['mi_traveller']);exit();
			foreach($_REQUEST['mi_traveller'] as $key=>$record)  
			{
				$traveller_id = $record['traveller_id_'];
				$reservation_id = $id;
                $reservation_room_id = $rr_id;
                $check_adult = 0;
                $check_child = 0;
				$special_request = str_replace('"',' ',$record['note']);
				$to_judge = $record['to_judge'];
                unset($record['traveller_id_']);
				$i++;
				if($record['traveller_room_id']){
                    $temp_arr = explode('-',$record['traveller_room_id']);
					//Kimtan::phong chua gan phong thi gan $room_id
                    if($temp_arr[0]=='')
                    {
                        $room_id = $record['mi_traveller_room_name'];
                    }
                    else{
                        $room_id = $temp_arr[0];
                    }
                    //end Kimtan::phong chua gan phong thi gan $room_id
					$departure_time = $temp_arr[1];
					$temp_room = '';
					
				}else{
					//System::Debug($_REQUEST['mi_traveller']); exit();  
					$temp_room = $record['mi_traveller_room_name'];
					$room_id = $record['mi_traveller_room_name'];
					$departure_time = '01/01/1970';
				}
                
				/////////////////////////////////////////////////////////////////////////////////////////////////////
				$record['birth_date']= Date_Time::to_orc_date($record['birth_date']);
			    unset($record['nationality_name']);
				$record['nationality_id'] = DB::fetch('select id from country where code_1 = \''.trim($record['nationality_id']).'\'','id',534);
				$payment = false;
				$visa = mb_strtoupper($record['visa'],'utf-8');
				$expire_date_of_visa = $record['expire_date_of_visa'];
				$payment = isset($record['traveller_id'])?true:false;
				$pa18 = isset($record['pa18'])?1:0;
				$traveller_arrival_time = 0;
				$traveller_departure_time = 0;
				$flight_arrival_time = 0;
				$flight_departure_time = 0;
				$traveller_arrival_date = '';
				$traveller_departure_date = '';
				$reservation_room = array();
                /** START - DAT_1662 sua truong hop them khach **/
			  
                /** START - Binh sua truong hop them khach **/
                if($room_id and is_numeric($room_id) and DB::select('room','id='.$room_id))
				{
                     
					$reservation_room = DB::select('reservation_room','reservation_id='.$id.' and room_id='.$room_id.' and departure_time = \''.Date_Time::to_orc_date($departure_time).'\' and status !=\'CANCEL\' and status !=\'NOSHOW\'');
				}
				else if(!is_numeric($room_id) && $room_id!='')  
				{
					$reservation_room = DB::select('reservation_room','reservation_id='.$id.' and temp_room=\''.$room_id.'\' and reservation_room.status !=\'CANCEL\' and reservation_room.status !=\'NOSHOW\'');
				}
                
               /** END - Binh sua truong hop them khach **/
                /** END - DAT_1662 sua truong hop them khach **/
				if($record['arrival_hour'] or $record['traveller_arrival_date'])
				{
					if(!$record['arrival_hour'])
					{
						$record['arrival_hour'] = '00:00';
					}
					$t_arr = explode(':',$record['arrival_hour']);
                   
					$traveller_arrival_time = ($record['traveller_arrival_date']!='')?(Date_Time::to_time($record['traveller_arrival_date'])+$t_arr[0]*3600+$t_arr[1]*60):(($reservation_room['time_in'])?$reservation_room['time_in']:'');
					$traveller_arrival_date = ($record['traveller_arrival_date']!='')?Date_Time::to_orc_date($record['traveller_arrival_date']):(($reservation_room['arrival_time'])?$reservation_room['arrival_time']:'');
				}
				elseif(isset($reservation_room['status']) and $reservation_room['status']=='CHECKIN')
				{
					$traveller_arrival_time = time();
					$traveller_arrival_date = Date_Time::to_orc_date(date('d/m/Y'));
				}
				if($record['departure_hour'] and $record['traveller_departure_date'])
				{
					if(!$record['departure_hour'])
					{
						$record['departure_hour'] = '00:00';
					}
					$t_dep = explode(':',$record['departure_hour']);
					$traveller_departure_time = ($record['traveller_departure_date']!='')?(Date_Time::to_time($record['traveller_departure_date'])+$t_dep[0]*3600+$t_dep[1]*60):(($reservation_room['time_out'])?$reservation_room['time_out']:'');
					$traveller_departure_date = ($record['traveller_departure_date']!='')?Date_Time::to_orc_date($record['traveller_departure_date']):(($reservation_room['departure_time'])?$reservation_room['departure_time']:'');					
				}
				if($record['flight_arrival_date'])//$record['flight_arrival_hour'] and 
				{
					if(!$record['flight_arrival_hour'])
					{
						$record['flight_arrival_hour'] = '00:00';
					}
					$f_arr = explode(':',$record['flight_arrival_hour']);
					$flight_arrival_time = Date_Time::to_time($record['flight_arrival_date'])+$f_arr[0]*3600+$f_arr[1]*60;
				}	
				if($record['flight_departure_date'])//$record['flight_departure_hour'] and 
				{
					if(!$record['flight_departure_hour'])
					{
						$record['flight_departure_hour'] = '00:00';
					}
					$f_dep = explode(':',$record['flight_departure_hour']);

					$flight_departure_time = Date_Time::to_time($record['flight_departure_date'])+$f_dep[0]*3600+$f_dep[1]*60;
				}						
				$traveller_arrival_time = ($traveller_arrival_time=='')?((isset($reservation_room['time_in']))?$reservation_room['time_in']:''):$traveller_arrival_time;
				$traveller_arrival_date = ($traveller_arrival_date=='')?((isset($reservation_room['arrival_time']))?$reservation_room['arrival_time']:''):$traveller_arrival_date;
				$traveller_departure_time = ($traveller_departure_time=='')?((isset($reservation_room['time_out']))?$reservation_room['time_out']:''):$traveller_departure_time;
			    $traveller_departure_date = ($traveller_departure_date=='')?((isset($reservation_room['departure_time']))?$reservation_room['departure_time']:''):$traveller_departure_date;		
				//End
				if(isset($record['pickup'])){
					$pickup = 1;
				}else{
					$pickup = 0;
				}
				if(isset($record['see_off'])){
					$see_off = 1;
				}else{
					$see_off = 0;
				}						
				if(isset($record['pickup_foc'])){
					$pickup_foc = 1;
				}else{
					$pickup_foc = 0;
				}
				if(isset($record['see_off_foc'])){
					$see_off_foc = 1;
				}else{
					$see_off_foc = 0;
				}
                $entry_target=$record['entry_target'];					
				$flight_code = mb_strtoupper($record['flight_code'],'utf-8');
				$flight_code_departure = mb_strtoupper($record['flight_code_departure'],'utf-8');
				$car_note_arrival = str_replace('"',' ',mb_strtoupper($record['car_note_arrival'],'utf-8'));
				$car_note_departure = str_replace('"',' ',mb_strtoupper($record['car_note_departure'],'utf-8'));
                $note = str_replace('"',' ',mb_strtoupper($record['note'],'utf-8'));
				if(isset($record['transit']) && $record['transit'] =='on'){
					$record['transit'] = 1;
				}else{
					$record['transit'] = 0;
				}
                unset($record['entry_target']);
				unset($record['reservation_traveller_id']);
				unset($record['arrival_hour']);
				unset($record['traveller_arrival_date']);
				unset($record['departure_hour']);
				unset($record['traveller_departure_date']);
				unset($record['flight_arrival_hour']);
				unset($record['flight_arrival_date']);				
				unset($record['flight_code']);
				unset($record['flight_departure_hour']);
				unset($record['flight_departure_date']);				
				unset($record['flight_code_departure']);				
				unset($record['car_note_arrival']);
				unset($record['car_note_departure']);
                unset($record['note']);
				unset($record['pickup']);
				unset($record['see_off']);
				unset($record['pickup_foc']);
				unset($record['see_off_foc']);				
				unset($record['traveller_id']);
				unset($record['traveller_room_id']);
				unset($record['mi_traveller_room_name']);
                unset($record['room_level']);
				unset($record['check_out']);
				unset($record['status']);
				$status_traveller = '';
				if(isset($record['status']) && $record['status']){
					$status_traveller = $record['status'];
				}
				unset($record['status']);
				unset($record['visa']);
				unset($record['expire_date_of_visa']);
				$record['first_name'] = mb_strtoupper(trim(str_replace('  ',' ',$record['first_name'])),'utf-8');
				$record['last_name'] = mb_strtoupper(trim(str_replace('  ',' ',$record['last_name'])),'utf-8');
				$record['passport'] = $record['passport']?mb_strtoupper($record['passport'],'utf-8'):'?';
				$record['address'] = $record['address']?mb_strtoupper($record['address'],'utf-8'):'';
				if(!empty($reservation_room))
				{	
					$reservation_room_id = $reservation_room['id'];
                    if(isset($record['is_child']) && ($record['is_child'] == 'on'))
                    {
                        $check_child = $reservation_room['id'];
                    }else
                    {
                        $check_adult = $reservation_room['id'];  
                    }
				}else{
					$reservation_room_id = 0;
                    $check_adult = 0;
                    $check_child = 0;
				}
                
				if(isset($record['id']) && $record['id'] != '')
				{
				    //System::debug($record);exit();
                    if(isset($_REQUEST['create_member_'.$key])){
                        $record['member_code'] = create_member_code();
                        $member_level = DB::fetch("SELECT id FROM member_level WHERE min_point=0");
                        $record['member_level_id'] = $member_level['id'];
                        $record['member_create_date'] = Date_Time::to_orc_date(date('d/m/Y'));
                        $record['password'] = create_password_radom();
                        $full_name = $record['first_name']." ".$record['last_name'];
                        $content = "<h1>".Portal::language('hello').$full_name."</h1><br />";
                        $content .= "<h4>".portal::language('infomation').portal::language('member').":</h4><br />";
                        $content .= "<p>Username:</p>".$record['member_code']."<br />";
                        $content .= "<p>Password:</p>".$record['password']."<br />";
                        $mail_member = $record['email'];
                        if(!filter_var($mail_member, FILTER_VALIDATE_EMAIL)){
                            echo "<script>";
                            echo "alert('is not email');";
                            echo "</script>";
                        }else{
                            sent_mail_to($mail_member,$content);
                        }
                    }
                    if(isset($old_travellers[$record['id']])){
						$reservation_traveller_id = $record['id'];
						unset($record['id']);
						unset($record['reservation_room_id']);
                        unset($record['to_judge']);
                        if(isset($record['is_child']) && $record['is_child'] == 'on')
                        {
                            $record['is_child'] = 1;
                        }else
                        {
                            $record['is_child'] = 0;
                        }
                        DB::update('traveller',$record,'id='.$traveller_id);
						if($reservation_room_id>0){
							if(!isset($count_travellers[$reservation_room_id]))
							{
								$count_travellers[$reservation_room_id] = 0;
								$customer_names[$reservation_room_id] = $customer_name;
							}
                            //dat comment
							//$customer_names[$reservation_room_id].= ' '.$record['first_name'].' '.$record['last_name'];
							if($status_traveller == 'CHECKIN'){
								$count_travellers[$reservation_room_id]++;
							}
						}
						DB::update('reservation_traveller',array(
							'reservation_room_id'=>$reservation_room_id,
							'reservation_id'=>$reservation_id,
							'traveller_id'=>$traveller_id,
							'arrival_time'=>$traveller_arrival_time,
							'arrival_date'=>$traveller_arrival_date,
							'departure_time'=>$traveller_departure_time,
							'departure_date'=>$traveller_departure_date,
							'flight_code'=>$flight_code,
							'flight_code_departure'=>$flight_code_departure,
							'flight_arrival_time'=>$flight_arrival_time,
							'flight_departure_time'=>$flight_departure_time,
							'car_note_arrival'=>$car_note_arrival,
							'car_note_departure'=>$car_note_departure,
                            'note'=>$note,
							'pickup'=>$pickup,
							'see_off'=>$see_off,
							'pickup_foc'=>$pickup_foc,
							'see_off_foc'=>$see_off_foc,							
							'pa18'=>$pa18,
							'special_request'=>$special_request,
							'temp_room'=>$temp_room,
							'visa_number'=>$visa,
                            'to_judge'=>$to_judge,
                            'entry_target'=>$entry_target,
							'expire_date_of_visa'=>Date_Time::to_orc_date($expire_date_of_visa)
						),'id='.$reservation_traveller_id.'');
						$old_travellers[$reservation_traveller_id]['not_delete'] = true;
						$rt_id = $reservation_traveller_id;
					}
					if($reservation_room_id){
						if(!isset($count_travellers[$reservation_room_id]))
						{
							$customer_names[$reservation_room_id] = $customer_name;

							$count_travellers[$reservation_room_id] = 0;
						}
						$customer_names[$reservation_room_id].= ' '.$record['first_name'].' '.$record['last_name'];
						$count_travellers[$reservation_room_id]++;
					}
                    if($check_child)
                    {
                        if(!isset($total_child[$check_child]))
                        {
                             $total_child[$check_child] = 0;                        
                        }
                        $total_child[$check_child]++;
                    }
                    if($check_adult >0)
                    {
                        if(!isset($total_adult[$check_adult]))
                        {
                            $total_adult[$check_adult] = 0;
                        }
                        $total_adult[$check_adult]++;
                    }
				}
				else
				{
				    //System::debug($record);
                    if(isset($_REQUEST['create_member_'.$key])){
                        $record['member_code'] = create_member_code();
                        $member_level = DB::fetch("SELECT id FROM member_level WHERE min_point=0");
                        $record['member_level_id'] = $member_level['id'];
                        $record['member_create_date'] = Date_Time::to_orc_date(date('d/m/Y'));
                        $record['password'] = create_password_radom();
                        $full_name = $record['first_name']." ".$record['last_name'];
                        $content = "<h1>".Portal::language('hello').$full_name."</h1><br />";
                        $content .= "<h4>".portal::language('infomation').portal::language('member').":</h4><br />";
                        $content .= "<p>Username:</p>".$record['member_code']."<br />";
                        $content .= "<p>Password:</p>".$record['password']."<br />";
                        $mail_member = $record['email'];
                        if(!filter_var($mail_member, FILTER_VALIDATE_EMAIL)){
                            echo "<script>";
                            echo "alert('is not email');";
                            echo "</script>";
                        }else{
                            sent_mail_to($mail_member,$content);
                        }
                    }
				    unset($record['id']);					
					unset($record['reservation_room_id']);					
                    unset($record['to_judge']);				
					/*if($record['passport']!='?' and $traveller = DB::select('traveller','passport=\''.$record['passport'].'\''))
					{
						DB::update('traveller',$record,'id=\''.$traveller['id'].'\'');
					}*/
					if($traveller_id && $traveller_id != ''){
						DB::update('traveller',array('competence'=>$record['competence']),'id='.$traveller_id);
					}else{
					
                    //KimTan: them truong is_vn theo quoc tich
                    $record['is_vn'] = '';
                    if(isset($_REQUEST['mi_traveller'][$key]['nationality_id']) and $_REQUEST['mi_traveller'][$key]['nationality_id']!='')
                    {
                        if($_REQUEST['mi_traveller'][$key]['nationality_id']=='VNM')
                        {
                            $record['is_vn'] = 2;
                        }
                        else{
                            $record['is_vn'] = 0;
                        }
                    }
                    // end KimTan: them truong is_vn 
                    
                     /** Oanh add **/
                     if(isset($_REQUEST['mi_traveller'][$key]['is_vn']) and $_REQUEST['mi_traveller'][$key]['is_vn'] =='3' )
                     {
                         $record['nationality_id']= '439';
                         $record['is_vn'] = 3;
                     }
                     elseif(isset($_REQUEST['mi_traveller'][$key]['is_vn']) and $_REQUEST['mi_traveller'][$key]['is_vn'] =='2' )
                     {
                        $record['nationality_id']= '439';
                        $record['is_vn'] = 2;
                     }
                     /** End OAnh **/
                     if(isset($record['is_child']) && $record['is_child'] == 'on')
                     {
                        $record['is_child'] = 1;
                     }else
                     {
                        $record['is_child'] = 0;                        
                     }
                    $traveller_id = DB::insert('traveller',$record); 
                        
					}
					$rt_id = DB::insert('reservation_traveller',array(
						'traveller_id'=>$traveller_id,
						'reservation_room_id'=>$reservation_room_id,
						'reservation_id'=>$reservation_id,
						'special_request'=>$special_request,
						'arrival_time'=>$traveller_arrival_time,
						'arrival_date'=>$traveller_arrival_date,
						'departure_time'=>$traveller_departure_time,  
						'departure_date'=>$traveller_departure_date,
						'flight_code'=>$flight_code,
						'flight_arrival_time'=>$flight_arrival_time,
						'flight_code_departure'=>$flight_code_departure,						
						'flight_departure_time'=>$flight_departure_time,
						'car_note_arrival'=>$car_note_arrival,
						'car_note_departure'=>$car_note_departure,
                        'note'=>$note,
						'pickup'=>$pickup,
						'see_off'=>$see_off,
						'pickup_foc'=>$pickup_foc,
						'see_off_foc'=>$see_off_foc,						
						'temp_room'=>$temp_room,
						'visa_number'=>$visa,
                        'to_judge'=>$to_judge,
                        'entry_target'=>$entry_target,
						'expire_date_of_visa'=>Date_Time::to_orc_date($expire_date_of_visa),
						'status'=>'CHECKIN'
					));
                    /** Manh Log **/
                        $type_log = 'ADD Traveller';
                        $title_log = 'ADD Traveller to Room: code: #'.$reservation_room_id;
                        //$guest_type = DB::fetch("SELECT name FROM guest_type WHERE guest_type.id=".$record['traveller_level_id']);
                        $description_log = '<h3>'.Portal::language('infomation').portal::language('traveller').':</h3>
                                            <p>'.portal::language('traveller_id').': '.$traveller_id.'</p>
                                            <p>'.portal::language('reservation_room_id').': '.$reservation_room_id.'</p>
                                            <p>'.portal::language('name').': '.$record['first_name'].' '.$record['last_name'].'</p>
                                            <p>'.portal::language('passport').'/ CMND: '.$record['passport'].'</p>
                                            <p>Visa: '.$visa.'</p>
                                            <p>'.portal::language('birth_date').': '.Date_Time::convert_orc_date_to_date($record['birth_date'],'/').'</p>
                                            
                                            <p>:'.portal::language('nationality').' '.$record['nationality_id'].'</p>
                                            <p>'.portal::language('phone').': '.$record['phone'].'</p>
                                            <p>Email: '.$record['email'].'</p>
                                            <p>'.portal::language('address').': '.$record['address'].'</p>
                                            <p>flight_code_arrival: '.$flight_code.'</p>
                                            <p>flight_arrival_time: '.$flight_arrival_time.'</p>
                                            <p>flight_code_departure: '.$flight_code_departure.'</p>
                                            <p>flight_departure_time: '.$flight_departure_time.'</p>
                                            <p>'.portal::language('note').': '.$note.'</p>
                                            ';
                        $log_id = System::log($type_log,$title_log,$description_log,$rt_id);
                        System::history_log('RECODE',$reservation_id,$log_id);
                        
                    /** end Manh **/
					if($reservation_room_id){
						if(!isset($count_travellers[$reservation_room_id]))
						{
							$customer_names[$reservation_room_id] = $customer_name;
							$count_travellers[$reservation_room_id] = 0;
						}
                        //dat comment
						//$customer_names[$reservation_room_id].= ' '.$record['first_name'].' '.$record['last_name'];
						$count_travellers[$reservation_room_id]++;
					}
                    if($check_child)
                    {
                        if(!isset($total_child[$check_child]))
                        {
                             $total_child[$check_child] = 0;                        
                        }
                        $total_child[$check_child]++;
                    }
                    if($check_adult >0)
                    {
                        if(!isset($total_adult[$check_adult]))
                        {
                            $total_adult[$check_adult] = 0;
                        }
                        $total_adult[$check_adult]++;
                    }
				}
				if(isset($change_status[$room_id]))
				{
					$content = $reservation_room['status'].' room '.DB::fetch('select CONCAT(room.name,CONCAT(\' - \',party.name_1)) as name from room inner join party on party.user_id = room.portal_id where room.id=\''.$room_id.'\'','name');
					update_traveller_comment($traveller_id,$content);	
				}
				if(isset($count_travellers[$reservation_room_id])){
					if($payment){
						DB::update('reservation_room',array('traveller_id'=>$traveller_id),'id='.$reservation_room_id);
					}else{
						if($count_travellers[$reservation_room_id]==1){
							DB::update('reservation_room',array('traveller_id'=>$traveller_id),'id='.$reservation_room_id);
						}
					}
				}
				//-------------------------Thuy-------------------------
				$tour_id = DB::fetch('select tour_id from reservation where id='.$id.'','tour_id');
				if($tour_id){
					update_pa18_template_for_traveller($tour_id,$rt_id);	
				}
				//-------------------------END-Thuy-------------------------
			}
		}
		/** daund cmt
        foreach($count_travellers as $r_room_id=>$adult)
        {
			$adult_old = DB::fetch('select id,adult from reservation_room where id = '.$r_room_id.'','adult');
            
            $status = DB::fetch('select id,status from reservation_room where id = '.$r_room_id.'','status');
			if($status == 'CHECKIN' or $status == 'CHECKOUT' or $status=='BOOKED'){
				if($adult_old<$adult){//Kimtan:neu adult trong reservation < so khach them thi moi update lai adult
				    DB::update('reservation_room',array('adult'=>$adult,'customer_name'=>$customer_names[$r_room_id]),'id=\''.$r_room_id.'\'');
				}else{
				    DB::update('reservation_room',array('customer_name'=>$customer_names[$r_room_id]),'id=\''.$r_room_id.'\'');
				}
			}
		}*/
        if($total_adult && !empty($total_adult))
        {
            foreach($total_adult as $key_adult => $adult)
            {
                $adult_old = DB::fetch('select id,adult from reservation_room where id = '.$key_adult.'','adult');
                $status = DB::fetch('select id,status from reservation_room where id = '.$key_adult.'','status');
                if($status == 'BOOKED' or $status == 'CHECKIN' or $status == 'CHECKOUT')
                {
                    if($adult_old < $adult)
                    { 
                        DB::update('reservation_room',array('adult'=>$adult,'customer_name'=>$customer_names[$key_adult]),'id=\''.$key_adult.'\'');
                    }else
                    {
                        DB::update('reservation_room',array('customer_name'=>$customer_names[$key_adult]),'id=\''.$key_adult.'\'');
                    }
                }
            }            
        }
        if($total_child && !empty($total_child))
        {
            foreach($total_child as $key_child => $child)
            {
                $status = DB::fetch('select id,status from reservation_room where id = '.$key_child.'','status');
                if($status == 'BOOKED' or $status == 'CHECKIN' or $status == 'CHECKOUT')
                {
                    DB::update('reservation_room', array('child'=>$child,'customer_name'=>$customer_names[$key_child]),'id=\''.$key_child.'\'');
                }                
            }
        }else
        {
            foreach($count_travellers as $r_r_id=> $value)
            {
                DB::update('reservation_room', array('child'=>0,'customer_name'=>$customer_names[$r_r_id]),'id=\''.$r_r_id.'\'');
            }
        }
        
		if(Url::get('traveller_delete'))
        {
			$delete = explode(",",Url::get('traveller_delete'));
			for($j=0;$j<count($delete);$j++)
            {
				if($delete[$j] !='' && isset($old_travellers[$delete[$j]]))
                {
					if(User::can_delete(false,ANY_CATEGORY))
                    {  
						DB::delete('reservation_traveller','id='.$delete[$j]);
                        $sql = 'select * from traveller_folio where reservation_traveller_id = '.$delete[$j];
                        $tmp = DB::fetch($sql);
                        
                        if(!empty($tmp))
                        {
                            DB::delete('traveller_folio','reservation_traveller_id = '.$delete[$j]);
                        }
                        if(DB::fetch('select * from folio where reservation_traveller_id = '.$delete[$j]))
                        {
                            DB::delete('folio','reservation_traveller_id = '.$delete[$j]);
                        }
					}
                    else
                    {
						$form->error('can_not_delete','you_have_no_right_to_delete');		
					}
                    
				}
			}
		}
	}else
    {
        DB::update('reservation_room', array('child'=>0),'id=\''.$_REQUEST['rr_id'].'\'');
    } 
}
function update_pa18_template_for_traveller($tour_id,$rt_id){// Thuylt
	$sql= 'SELECT
				tour.id 
				,port_of_entry
				,entry_date
				,back_date
				,entry_target
				,go_to_office
				,come_from
				,is_vn
		   FROM 
		   		tour WHERE id='.$tour_id.'';
	$tour_info =  DB::fetch($sql);
	unset($tour_info['id']);
	unset($tour_info['is_vn']);
	$traveller = DB::fetch('select * from reservation_traveller where id = '.$rt_id.'');
	if($traveller['port_of_entry'] != ''){
		unset($tour_info['port_of_entry']);	
	}
	if($traveller['entry_date'] != ''){
		unset($tour_info['entry_date']);
	}
	if($traveller['back_date'] != ''){
		unset($tour_info['back_date']);	
	}
	if($traveller['entry_target'] != ''){
		unset($tour_info['entry_target']);	
	}
	if($traveller['go_to_office'] != ''){
		unset($tour_info['go_to_office']);	
	}
	if($traveller['come_from'] != ''){
		unset($tour_info['come_from']);	
	}
	if($tour_info){
		DB::update('reservation_traveller',$tour_info,'id='.$rt_id.'');
	}	
}
function update_tour($tour_id,$reservation_id){//Thuylt
	$sql='
		SELECT 
			min(reservation_room.arrival_time) as arrival_time,
			max(reservation_room.departure_time) as departure_time,
			sum(reservation_room.adult) as adult, 
			sum(reservation_room.child) as child,
			count(reservation_room.id) as room_quantity FROM reservation_room INNER JOIN reservation ON reservation.id = reservation_room.reservation_id 
		WHERE 
			reservation.tour_id = '.$tour_id.' AND reservation.id ='.$reservation_id.' AND reservation_room.status <> \'CANCEL\' AND reservation_room.status <> \'NOSHOW\'';
	$items = DB::fetch($sql);
	$items['num_people'] = $items['adult'];
	unset($items['adult']);
	$items['child'] = $items['child'];
	DB::update('tour',$items,'id = '.$tour_id.'');
}
function update_traveller_comment($traveller_id,$content){
	DB::insert('traveller_comment',
		array(
			'user_id'=>Session::get('user_id'),
			'time'=>time(),
			'traveller_id'=>$traveller_id,
			'content'=>$content)
	);
}
function reservation_check_permission(&$form, $id, &$old_reservation_room)
{
	if(isset($_REQUEST['mi_reservation_room']))
	{
		foreach($_REQUEST['mi_reservation_room'] as $key=>$record)
		{
			if(!isset($record['status']) and isset($record['old_status'])){
				$record['status'] = $record['old_status'];
			}
			if(isset($old_reservation_room[$record['id']]) and $old_reservation_room[$record['id']]['status'] == 'BOOKED' and $record['status']=='CHECKIN')
			{
				if($old_reservation = DB::select('reservation_room','status=\'CHECKIN\' and room_id=\''.$record['room_id'].'\''))
				{
					$form->error('room_name_'.$key,'Room '.DB::fetch('select name from room where id=\''.$record['room_id'].'\'','name').' is currently checked in by <a target=\'_blank\' href=\'?page=reservation&cmd=edit&id='.$old_reservation['reservation_id'].'\'>Reservation #'.$old_reservation['reservation_id'].'</a>. Please check out this first before check in another guest!',false);
					$error = true;
				}
			}
			if($record['id'] and isset($old_reservation_room[$record['id']]))
			{
				$old_reservation_room[$record['id']]['not_delete'] = true;
			}
		}
		if($form->is_error())
		{
			return;
		}
	}
}

function check_rooms_repair($form)
{
     
    if(isset($_REQUEST['mi_reservation_room']))
	{
		foreach($_REQUEST['mi_reservation_room'] as $key=>$record)
		{
			if($record and $record['room_id'] and $record['status'] != 'CANCEL' and $record['status'] != 'NOSHOW' and $record['status'] != 'CHECKOUT')
			{
                if(DB::fetch('select * from room_status where house_status = \'REPAIR\' AND room_id = '.$record['room_id'].' AND ((in_date >= \''.Date_Time::to_orc_date($record['arrival_time']).'\''.' AND in_date <= \''.Date_Time::to_orc_date($record['departure_time']).'\') or (in_date = \''.Date_Time::to_orc_date($record['departure_time']).'\' and TO_CHAR(in_date, \'DD-MON-YYYY\') != TO_CHAR(start_date, \'DD-MON-YYYY\')))'))
                {
                    $form->error('room_id_'.$record['room_id'],Portal::language('room_'.$record['room_name'].' repair'));
                }
            }
        }
    }
}

function reservation_check_conflict($form, $add = false)
{
	$valid_room_array=array();
	$room_conflig_arr = array();
	$pre_room_id = 0;
	$room_level = array();
	$i = 1;
	if(isset($_REQUEST['mi_reservation_room']))
	{
	    
		$time_in_min = 0;
        $time_out_max = 0;
        $room_level_ids = '';
        foreach($_REQUEST['mi_reservation_room'] as $key=>$record)
		{
		    if(empty($record['room_id']) && $record['status']=="CHECKIN")
            {
                $form->error('status_'.$key,'Room: '.$record['room_name'].' '.Portal::language('no_selected_room'));
                continue;
            } 
            
			if($record and $record['room_level_id'])
			{
				if(!isset($record['status']) and isset($record['old_status']))
                {
					$record['status'] = $record['old_status'];
				}
				$reservation_room_id = $record['id']?$record['id']:0;
				$time_in = Date_Time::to_time($record['arrival_time']); 
				$time_out=Date_Time::to_time($record['departure_time']);
                if($room_level_ids == '')
                $room_level_ids = $record['room_level_id'];
                else
                $room_level_ids.=','.$record['room_level_id'];
                if($time_in_min==0)
                {
                    $time_in_min = $time_in;
                }else{
                    if($time_in<$time_in_min)
                    {
                        $time_in_min = $time_in;
                    }
                }
                if($time_out_max==0)
                {
                    $time_out_max = $time_out;
                }else{
                    if($time_out>$time_out_max)
                    {
                        $time_out_max = $time_out;
                    }
                }
				$cond = 'room.portal_id = \''.PORTAL_ID.'\' AND R.status<>\'CANCEL\' AND R.status<>\'NOSHOW\'
						AND R.room_id=\''.$_REQUEST['mi_reservation_room'][$key]['room_id'].'\' 
						'.($record['id']?' AND R.id<>\''.$record['id'].'\'':'');
				if(isset($record['time_in']) and $record['time_in'] and isset($record['time_out']) and $record['time_out'])
                {
                    if(isset($record['confirm']) && $record['confirm']=='on'){
                        $_REQUEST['mi_reservation_room'][$key]['confirm'] = 1;
                    }                    
                    if(isset($record['breakfast']) && $record['breakfast']=='on'){
                        $_REQUEST['mi_reservation_room'][$key]['breakfast'] = 1;
                    }
                    if(isset($record['closed']) && $record['closed']=='on'){
                        $_REQUEST['mi_reservation_room'][$key]['closed'] = 1;
                    }
                    $arr = explode(':',$record['time_in']);
					$time_in= $time_in + intval($arr[0])*3600+intval($arr[1])*60;
					$arr = explode(':',$record['time_out']);
					$time_out= $time_out + intval($arr[0])*3600+intval($arr[1])*60;
					if($time_out <= $time_in)
                    {
						$_REQUEST['mi_reservation_room'][$key]['status'] = $record['old_status'];
                        $form->error('room_id_'.$key,Portal::language('time_out_has_to_be_more_than_time_in'));
					}
					if($record['id'] and !check_all_related_serivce($record['id'],$time_in,$time_out))
                    {
						//$form->error('room_id_'.$key,Portal::language('room').' <strong>'.$record['room_name'].'</strong>: '.Portal::language('Some_added_services_of_this_room_out_of_this_time_duration'),false);
					}				
				}
                else
                {
					if($time_out < $time_in)
                    {
						$_REQUEST['mi_reservation_room'][$key]['status'] = $record['old_status'];
                        $form->error('room_id_'.$key,Portal::language('time_out_has_to_be_more_than_time_in'),false);
					}
				}
				if($record['status']=='CHECKOUT')
                {
					if($time_out>time())
                    {
                        $_REQUEST['mi_reservation_room'][$key]['status'] = $record['old_status'];
						$form->error('room_id_'.$key,Portal::language('time_out_has_not_to_be_more_than_current_time'));
					}
				}
				$cond2 = $cond.' AND r.status=\'CHECKIN\'';
				$cond .= ' AND (
						(R.time_in <= '.$time_in.' AND R.time_out >= '.$time_out.')
					OR	(R.time_in >= '.$time_in.' AND R.time_out >= '.$time_out.' AND R.time_in <= '.$time_out.')
					OR	(R.time_in <= '.$time_in.' AND R.time_out >= '.$time_in.' AND R.time_out <= '.$time_out.')
					OR	(R.time_in >= '.$time_in.' AND R.time_out <= '.$time_out.')
					OR	(R.time_out = '.$time_in.')  
				)';// OR r.status=\'BOOKED\'
				$sql = '
					SELECT 
						R.id,R.reservation_id
					FROM 
						reservation_room R
						INNER JOIN room ON room.id = R.room_id
					WHERE 
						
				';
				$room_id = $record['room_id'];
				$room_level_id = $record['room_level_id'];
                //System::debug($sql.' '.$cond);
				if($reservation_room = DB::fetch($sql.' '.$cond) and $record['status']<>'CANCEL' and $record['status']<>'NOSHOW')
				{
					$_REQUEST['mi_reservation_room'][$key]['status'] = $record['old_status'];
                    $form->error('room_id_'.$key,Portal::language('room').' '.$record['room_name'].' '.Portal::language('conflict_of_time_to_reservation').' <a target="blank" href="?page=reservation&cmd=edit&id='.$reservation_room['reservation_id'].'&r_r_id='.$reservation_room['id'].'">#'.$reservation_room['reservation_id'].'</a>',false);
				}
				if($record['status']=='CHECKIN')
				{
					if(isset($time_in) and $time_in>time())
                    {
						$_REQUEST['mi_reservation_room'][$key]['status'] = $record['old_status'];
                        $form->error('status_'.$key,'Room: '.$record['room_name'].' '.Portal::language('time_in_is_more_than_current_time'));
					}
                    else if($reservation_room = DB::fetch($sql.' '.$cond2))
                    {
						$_REQUEST['mi_reservation_room'][$key]['status'] = $record['old_status'];
                        $form->error('room_id_'.$key,Portal::language('conflict').' '.Portal::language('room').' '.$record['room_name'].' '.Portal::language('in_this_reservation').' <a target="blank" href="?page=reservation&cmd=edit&id='.$reservation_room['reservation_id'].'&r_r_id='.$reservation_room['id'].'">#'.$reservation_room['reservation_id'].'</a>',false);
					}
					
				}
				$valid_room_array[$room_id] = false;
				if($record['status']=='CHECKIN' or $record['status']=='CHECKOUT')
				{
					if(isset($_REQUEST['mi_traveller']))
					{
						foreach($_REQUEST['mi_traveller'] as $k=>$v){
							if($v['traveller_room_id'])
                            {
								$temp_arr = explode('-',$v['traveller_room_id']);
								$t_room_id = $temp_arr[0];
							}
                            else
                            {
								$t_room_id = 0;
							}
							if($t_room_id==$room_id)
                            {
								$valid_room_array[$room_id] = true;
							}
						}
					}
				}
				else
                {
					if(isset($_REQUEST['mi_traveller']))
					{
						foreach($_REQUEST['mi_traveller'] as $k=>$v)
                        {
							if($v['traveller_room_id'])
                            {
								$temp_arr = explode('-',$v['traveller_room_id']);
								$t_room_id = $temp_arr[0];
							}
                            else
                            {
								$t_room_id = 0;
							}
							if(!$valid_room_array[$room_id])
                            {
								if($t_room_id==$room_id)
                                {
									$valid_room_array[$room_id] = true;
								}
								else
								{
									$valid_room_array[$room_id] = false;
								}
							}
						}
					}			
				}
			}
		}
        if(trim($room_level_ids)!="")
        {
            $room_level=check_over_room_level($_REQUEST['mi_reservation_room'],$time_in_min,$time_out_max,$room_level_ids);
            //System::debug($room_level); die;
            if (!OVER_BOOK)
            {
                foreach($room_level as $k=>$v)
                {
                    if($v['min']<0)
                    {
                        $form->error('room_level', Portal::language('Room_level').': '.$v['room_level_name'].' '.Portal::language('is_out_of_from').' '.date('H:i d/m/Y',$v['arrival_time']).' - '.date('H:i d/m/Y',$v['departure_time']),false);
                    }
                }
            }
        }
	}
    else
    {
		$_REQUEST['mi_reservation_room'][$key]['status'] = $record['old_status'];
        $form->error('room','miss_room_information');
	}

	return $valid_room_array;
}
function room_check_conflict($arr1,$arr2)
{
	$return = false;
	foreach($arr1 as $key=>$value)
    {
		if($value['room_id'] and $value['room_id']==$arr2[0])
        {
			// Lang edited for checking from present and future not past
            if($arr1[1] > time())
            {
                $time_in = $arr2[1];
            }
            else
            {
                $time_in = Date_Time::to_time(date('d/m/Y'));
            }
			$time_out = $arr2[2];
			if(($value['time_in'] <= $time_in and $value['time_out'] >= $time_out)
					or	($value['time_in'] >= $time_in and $value['time_out'] >= $time_out and $value['time_in'] <= $time_out)
					or	($value['time_in'] <= $time_in and $value['time_out'] >= $time_in and $value['time_out'] <= $time_out)
					or	($value['time_in'] >= $time_in and $value['time_out'] <= $time_out)
					or	($value['time_out'] == $time_in))
            {
					$return = true;
					break;
			}
		}
	}
	return $return;
}
function room_level_check_conflict($arr)
{
	$days = array();
    // Lang edited for checking from present and future not past
    if($arr[1] > time())
    {
        $arr[1] = $arr[1];
    }
    else
    {
        $arr[1] = Date_Time::to_time(date('d/m/Y'));
    }
    //end edit
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
			(SELECT COUNT(*) FROM room WHERE room_level_id = rl.id) room_quantity
		FROM	
			room_level rl
		WHERE
			'.$extra_cond.'
			AND rl.portal_id = \''.PORTAL_ID.'\'
		ORDER BY	
			rl.name
	');
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
                AND (rr.room_level_id in ('.$room_level['id'].') or room.room_level_id in ('.$room_level['id'].') )
                AND rs.in_date>=\''.Date_Time::to_orc_date(date('d/m/Y',$arr[1])).'\' 
                and rs.in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$arr[2])).'\'
			ORDER BY
				rr.room_level_id
		';	
	   $room_status = DB::fetch_all($sql);
       //System::debug($sql);
       //System::debug($room_status);
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
                    {
                        $room_quantity -= 1;
                    }
                        
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
function reservation_update_room_map(&$form, $id, $record,$change_status,$change_price_arr=array(),$old_reservation_room=array(),$check,$old_status=false)
{
	if($record['id'] and isset($old_reservation_room[$record['id']])){// Khoand updated in 07/04/2012
		//DB::update('room_status',array('status'=>'AVAILABLE','reservation_id'=>0),'reservation_room_id = '.$record['id'].' AND (in_date < \''.$record['arrival_time'].'\' OR in_date < \''.$record['departure_time'].'\')');
	}
	$from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($record['arrival_time']  ,'/'));
	$to   = Date_Time::to_time(Date_Time::convert_orc_date_to_date($record['departure_time'],'/'));
	$d = $from;
	$status=$record['status'];
	switch($record['status'])
	{
	case 'CHECKIN': 
	case 'CHECKOUT': 
		$status='OCCUPIED';break;
	}
	$house_status = ($record['status']=='CHECKOUT' and $change_status)?'DIRTY':'';
	while($d>=$from and $d<=$to)
	{
		$change_price = 0;
		if(isset($change_price_arr[date('d/m/Y',$d)]))
        {
			$change_price = $change_price_arr[date('d/m/Y',$d)];
		}
        //System::debug($change_price);
        //System::debug(Date_Time::to_orc_date(date('d/m/Y',$d)));
        //KID Sua gia phong BOOKED
		//if($status=='BOOKED')
//        {
//			if($record['arrival_time']==$record['departure_time'])
//            {
//				$change_price = $record['price'];
//			}
//            else
//            {
//				if($d == $to)
//                {
//					$change_price = 0;
//				}
//                else
//                {
//					$change_price = $record['price'];
//				}
//			}
//		}
		$sql = 'select * from room_status where in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$d)).'\' and reservation_room_id='.$record['id'].'';
	   //System::debug(DB::fetch($sql));
        if($room_status = DB::fetch($sql))
		{
		    //System::debug($change_price); exit();
             if($record['status']=="CANCEL" and $old_status=="CANCEL")
             {
                
             }
             else
             {
    			 DB::update_id('room_status',
    				(($record['status']=="CHECKOUT" and $change_status and $d==$to)?array('house_status'=>$house_status):array())+
    				(($record['status']=="CHECKOUT" and $record['arrival_time'] == $record['departure_time'])?array('closed_time'=>time()):array())+ // Closed doanh thu ngay khi check out neu khach o trong ngay
    				array(
    				'room_id'=>$record['room_id'],
    				'status'=>$status,
    				'reservation_id'=>$id,
    				'change_price'=>$change_price,
    				'house_status'=>($check)?'':$room_status['house_status'],
    				'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
    				),$room_status['id']
    			);
            }
            //$sql = 'select * from room_status where in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$d)).'\' and reservation_room_id='.$record['id'].'';
             
		}
		else
		{
  		     
			DB::insert('room_status',
				(($record['status']=="CHECKOUT" and $change_status and $d==$to)?array('house_status'=>$house_status):array())+
				array(
					'room_id'=>$record['room_id'],
					'status'=>$status,
					'reservation_id'=>$id,
					'change_price'=>$change_price,
					'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
					'reservation_room_id'=>$record['id']
				)
			);
		}
		$d=$d+(3600*24);
	}
    
    //if($record['status']=='CHECKOUT' and $change_status)
//    {
//        DB::update("room_status",array("house_status"=>"DIRTY")," room_id = ".$record['room_id']." AND in_date='".$record['departure_time']."'");
//    }
    
}
function put_into_lock_card($room_id,$time_in,$time_out)
{
	require_once 'packages/core/includes/system/access_database.php';
	$db_file = LOCK_DB_FILE;
	$adb = new ADB("Driver={Microsoft Access Driver (*.mdb)};Dbq=".$db_file."",'','yhd');
	
}
function reservation_check_traveller(&$form){
	
}
function update_change_price_log($rr_id,$old_change_price_arr,$change_price_arr,&$description)
{ // Writted by khoand in 06/01/2011
	$description .= '<em>Price by date</em>:';
	foreach($change_price_arr as $key=>$value)
    {
		if(isset($old_change_price_arr[$key]))
        {
			if(System::calculate_number($value) != System::calculate_number($old_change_price_arr[$key]['change_price']))
            {
				DB::update('room_status',array('lastest_edited_user_id'=>Session::get('user_id'),'lastest_edited_time'=>time(),'price_before_edited'=>$old_change_price_arr[$key]['change_price']),'reservation_room_id='.$rr_id.' and in_date = \''.$old_change_price_arr[$key]['in_date'].'\'');
				$description .= '<br>Update price from <strong>'.System::display_number($old_change_price_arr[$key]['change_price']).'</strong> to <strong>'.System::display_number($value).'</strong> for date '.$key.'';
			}
		}
        else
        {
			$description .= '<br>Add price '.$value.' for date '.$key.'';
		}
	}
	$description .= '<br>';
}
function update_room_log($old_reservation_room,$record,&$description){ // Writted by khoand in 06/01/2011
	$description .= '<li>';
	$description .= '<strong>Action with room '.$record['room_name'].':</strong><br>';
	if(isset($old_reservation_room[$record['id']])){
		$tmp_arr = $old_reservation_room[$record['id']];
		if($record['status'] != $tmp_arr['status']){
			$description .= 'Update room status from <strong>'.$tmp_arr['status'].'</strong> to <strong>'.$record['status'].'</strong>, ';
		}
		if(System::calculate_number($record['price']) != System::calculate_number($tmp_arr['price'])){
			$description .= 'Update room price from <strong>'.$tmp_arr['price'].'</strong> to <strong>'.$record['price'].'</strong>, ';
		}
		if($record['time_in'] != date('H:i',$tmp_arr['time_in'])){
			$description .= 'Update time in from <strong>'.date('H:i',$tmp_arr['time_in']).'</strong> to <strong>'.$record['time_in'].'</strong>, ';
		}
		if($record['arrival_time'] != Date_Time::convert_orc_date_to_date($tmp_arr['arrival_time'],'/')){
			$description .= 'Update arrival time from <strong>'.Date_Time::convert_orc_date_to_date($tmp_arr['arrival_time'],'/').'</strong> to <strong>'.$record['arrival_time'].'</strong>, ';
		}
		if($record['time_out'] != date('H:i',$tmp_arr['time_out'])){
			$description .= 'Update time out from <strong>'.date('H:i',$tmp_arr['time_out']).'</strong> to <strong>'.$record['time_out'].'</strong>, ';
		}
		if($record['departure_time'] != Date_Time::convert_orc_date_to_date($tmp_arr['departure_time'],'/')){
			$description .= 'Update departure time from <strong>'.Date_Time::convert_orc_date_to_date($tmp_arr['departure_time'],'/').'</strong> to <strong>'.$record['departure_time'].'</strong>, ';
		}
		if(System::calculate_number($record['reduce_balance']) != System::calculate_number($tmp_arr['reduce_balance'])){
			$description .= 'Update discount by percent from <strong>'.System::calculate_number($tmp_arr['reduce_balance']).'%</strong> to <strong>'.System::calculate_number($record['reduce_balance']).'%</strong>, ';
		}
		if(System::calculate_number($record['reduce_amount']) != System::calculate_number($tmp_arr['reduce_amount'])){
			$description .= 'Update discount by '.HOTEL_CURRENCY.' from <strong>'.$tmp_arr['reduce_amount'].'</strong> to <strong>'.$record['reduce_amount'].'</strong>, ';
		}
		if(System::calculate_number($record['tax_rate']) != System::calculate_number($tmp_arr['tax_rate'])){
			$description .= 'Update tax rate from <strong>'.System::calculate_number($tmp_arr['tax_rate']).'%</strong> to <strong>'.System::calculate_number($record['tax_rate']).'%</strong>, ';
		}
		if(System::calculate_number($record['service_rate']) != System::calculate_number($tmp_arr['service_rate'])){
			$description .= 'Update service rate from <strong>'.System::calculate_number($tmp_arr['service_rate']).'%</strong> to <strong>'.System::calculate_number($record['service_rate']).'%</strong>, ';
		}
		if($record['foc'] != $tmp_arr['foc']){
			$description .= 'Update FOC from <strong>"'.$tmp_arr['foc'].'"</strong> to <strong>"'.$record['foc'].'"</strong>, ';
		}
	}
	$description .= '</li>';
	//$record['arrival_time'].' to '.$record['departure_time']
}
function check_all_related_serivce($reservation_room_id,$time_in,$time_out){
	//check housekeeping invoice
	if(DB::exists('SELECT id FROM housekeeping_invoice hkinv WHERE hkinv.reservation_room_id = '.$reservation_room_id.' AND hkinv.time >= '.$time_in.' AND hkinv.time <= '.$time_out.'')){
		return true;
	}else{
		return false;
	}
}
/*
alter table room_status add(
  lastest_edited_user_id   char(50) NULL,
  lastest_edited_time   number(11) NULL,
price_before_edited number(11,2) NULL
)
*/
// ham xu ly tao invoice cho verify_dayuse
function update_verify_dayuse_invoice($reservation_room_id,$auto_late_checkin_price,$old_status,$new_status,$old_ci_time,$old_reservation_room)
{
	if($rr = DB::select('reservation_room','id='.$reservation_room_id.' AND reservation_room.status<>\'CANCEL\' AND reservation_room.status<>\'NOSHOW\''))
    {
		$services = DB::fetch_all('select * from extra_service where code=\'EARLY_CHECKIN\' OR code=\'LATE_CHECKIN\' OR code=\'LATE_CHECKOUT\'');
		if(!empty($services)){
			foreach($services as $s => $service){
				if($service['code'] == 'EARLY_CHECKIN'){
					$early_checkin_id = $service['id'];
				}
				if($service['code'] == 'LATE_CHECKIN'){
					$late_checkin_id = $service['id'];
				}
				if($service['code'] == 'LATE_CHECKOUT'){
					$late_checkout_id = $service['id'];
				}
			}
		}
		if(!empty($services))
        {
			$sql = 'select extra_service_invoice.* 
					from extra_service_invoice
						inner join extra_service_invoice_detail ON extra_service_invoice.id = extra_service_invoice_detail.invoice_id
					where 1>0	
						AND reservation_room_id='.$reservation_room_id.'';
			$arr_early_checkin = DB::fetch($sql.' AND early_checkin = 1 AND service_id = '.$early_checkin_id.'');
            $arr_late_checkin = DB::fetch($sql.' AND late_checkin = 1 AND service_id = '.$late_checkin_id.'');
			$arr_late_checkout = DB::fetch($sql.' AND late_checkout = 1 AND service_id = '.$late_checkout_id.'');
            if($rr['net_price'] == 1){
				$rr['price'] = round($rr['price']/(1+($rr['tax_rate']*0.01) + ($rr['service_rate']*0.01) + (($rr['tax_rate']*0.01)*($rr['service_rate']*0.01))),4);
			}
            $auto_late_checkin_price_detail = $auto_late_checkin_price;
            if($auto_late_checkin_price!='' AND $rr['net_price'] == 1){
                $auto_late_checkin_price = round($auto_late_checkin_price/(1+($rr['tax_rate']*0.01) + ($rr['service_rate']*0.01) + (($rr['tax_rate']*0.01)*($rr['service_rate']*0.01))),4);
            }
            //kid 
            $rr_price = DB::select('room_status','reservation_room_id='.$reservation_room_id.
                                                    ' AND room_status.status<>\'CANCEL\' 
                                                    AND room_status.status<>\'NOSHOW\' 
                                        AND room_status.in_date=\''.$rr['arrival_time'].'\'');
            /** START - DAT sua cach lay gia cho latecheckout. voi phong dayuse late checkout se duoc tinh theo luoc do gia ngay hom do **/
            //$t = (Date_Time::to_time(Date_Time::convert_orc_date_to_date($rr['departure_time'],'/'))-86400);
            if($rr['arrival_time'] != $rr['departure_time'])
            {
                $t = (Date_Time::to_time(Date_Time::convert_orc_date_to_date($rr['departure_time'],'/'))-86400);
            }
            else
            {
                $t = (Date_Time::to_time(Date_Time::convert_orc_date_to_date($rr['departure_time'],'/')));
            }
            /** END - DAT sua cach lay gia cho latecheckout. voi phong dayuse late checkout se duoc tinh theo luoc do gia ngay hom do **/
            $tt =Date_Time::to_orc_date(date('d/m/Y',$t)); 
            $rr_price_late_checkout = DB::select('room_status','reservation_room_id='.$reservation_room_id.
                                                    ' AND room_status.status<>\'CANCEL\'
                                                    AND room_status.status<>\'NOSHOW\'  
                                                    AND room_status.in_date=\''.$tt.'\'');
            if($rr['net_price'] == 1)
            {
				$rr_price['change_price'] = round($rr_price['change_price']/(1+($rr['tax_rate']*0.01) + ($rr['service_rate']*0.01) + (($rr['tax_rate']*0.01)*($rr['service_rate']*0.01))),4);
			    $rr_price_late_checkout['change_price'] =  round($rr_price_late_checkout['change_price']/(1+($rr['tax_rate']*0.01) + ($rr['service_rate']*0.01) + (($rr['tax_rate']*0.01)*($rr['service_rate']*0.01))),4);
            }
            
           
			$invoice = array(
								'reservation_room_id'=>$reservation_room_id,
								'user_id'=>($rr['checked_in_user_id']==''?$rr['booked_user_id']:$rr['checked_in_user_id']),
								'portal_id'=>PORTAL_ID,
								'payment_type'=>'ROOM',
								'time'=>$rr['time'],
								'tax_rate'=>$rr['tax_rate'],
								'service_rate'=>$rr['service_rate']
							);
			$arr = array(
						'price' => $rr_price['change_price'],
						'used' => 1	);
            $arr_late_out = array(
						'price' => $rr_price_late_checkout['change_price'],
						'used' => 1
						);	
            if($rr['arrival_time'] != $rr['departure_time'])
            {
               $arr_late_in = array(
							'price' => $rr_price['change_price'],
							'used' => 1	);
                
            }
            else
            {
                
                $arr_late_in = array(
							'price' => $rr['price'],
							'used' => 1
							);
            }
            
			if($rr['early_checkin']==0 && !empty($arr_early_checkin) && $arr_early_checkin['close']!=1)
            {
				DB::delete('extra_service_invoice_detail','invoice_id='.$arr_early_checkin['id']);
				DB::delete('extra_service_invoice','id='.$arr_early_checkin['id']);
                DB::delete('extra_service_invoice_table','invoice_id='.$arr_early_checkin['id']);		
			}
            else if($rr['early_checkin'] > 0)
            {
				 $time = Date_Time::to_time(date('d/m/Y',$rr['time_in']));
                $arr['in_date'] = Date_Time::to_orc_date(date('d/m/Y',$time));
				$name_service = $services[$early_checkin_id]['name'];
				$quantity = $rr['early_checkin'] * 0.1;
				$arr_1 = $arr + array('time'=>$time,'quantity'=>$quantity,'service_id' => $early_checkin_id,'name' =>$name_service);
                $invoice_1 = $invoice + array('early_checkin'=>1,'type'=>'ROOM','time'=>$time,'type'=>'ROOM','note'=>' Add automatic early checkin','total_before_tax'=>($arr['price']*$quantity),'total_amount'=>(($arr['price']*$quantity) + (($arr['price']*$quantity)*$rr['service_rate']*0.01) + (($arr['price']*$quantity) + (($arr['price']*$quantity)*$rr['service_rate']*0.01))*$rr['tax_rate']*0.01));
				if(!empty($arr_early_checkin) && $arr_early_checkin['close'] != 1)
                {  
					DB::update('extra_service_invoice',$invoice_1,' id='.$arr_early_checkin['id'].'');
					DB::update('extra_service_invoice_detail',$arr_1,' invoice_id='.$arr_early_checkin['id'].' AND service_id='.$early_checkin_id.'');   
                }
                else if(empty($arr_early_checkin))
                {
					$invoice_id = DB::insert('extra_service_invoice',$invoice_1);
                    $table_id=DB::insert('extra_service_invoice_table',array('from_date'=>Date_Time::to_orc_date(date('d/m/Y',$time)),'to_date'=>Date_Time::to_orc_date(date('d/m/Y',$time)),'invoice_id'=>$invoice_id));  
					DB::insert('extra_service_invoice_detail',$arr_1 + array('invoice_id'=>$invoice_id,'table_id'=>$table_id));
					if(strlen($invoice_id)==1)
                    {
						$invoice_id = '0'.$invoice_id;
					}    
					DB::update('extra_service_invoice',array('bill_number'=>'ES'.$invoice_id),'id='.$invoice_id);
				}
			}
			if(($rr['late_checkout']==0) && !empty($arr_late_checkout) && $arr_late_checkout['close']!=1)
            {
				DB::delete('extra_service_invoice_detail','invoice_id='.$arr_late_checkout['id']);
				DB::delete('extra_service_invoice','id='.$arr_late_checkout['id']);
                DB::delete('extra_service_invoice_table','invoice_id='.$arr_late_checkout['id']);
                	
			}
            else 
            if($rr['late_checkout'] > 0)
            {
				$arr_late_out['in_date'] = $rr['departure_time'];
				$quantity = $rr['late_checkout'] * 0.1;
				$time = Date_Time::to_time(Date_Time::convert_orc_date_to_date($arr_late_out['in_date'],'/'));
				$name_service = $services[$late_checkout_id]['name'];
				$arr_2 = $arr_late_out + array('time'=>$time,'quantity'=>$quantity,'service_id' => $late_checkout_id,'name' =>$name_service);
				$invoice_2 = $invoice + array('late_checkout'=>1,'type'=>'ROOM','time'=>$time,'note'=>' Add automatic late checkout','total_before_tax'=>($arr['price']*$quantity),'total_amount'=>(($arr['price']*$quantity) + (($arr['price']*$quantity)*$rr['service_rate']*0.01) + (($arr['price']*$quantity) + (($arr['price']*$quantity)*$rr['service_rate']*0.01))*$rr['tax_rate']*0.01));
				if(!empty($arr_late_checkout) && $arr_late_checkout['close']!=1)
                {
					DB::update('extra_service_invoice',$invoice_2,' id='.$arr_late_checkout['id'].'');
					DB::update('extra_service_invoice_detail',$arr_2,' invoice_id='.$arr_late_checkout['id'].' AND service_id='.$late_checkout_id.'');
				}else if(empty($arr_late_checkout))
                {
					$invoice_id = DB::insert('extra_service_invoice',$invoice_2);
                    $table_id=DB::insert('extra_service_invoice_table',array('from_date'=>$rr['departure_time'],'to_date'=>$rr['departure_time'],'invoice_id'=>$invoice_id));
					DB::insert('extra_service_invoice_detail',$arr_2 + array('invoice_id'=>$invoice_id,'table_id'=>$table_id));  
					if(strlen($invoice_id)==1)
                    {
						$invoice_id = '0'.$invoice_id;
					}
					DB::update('extra_service_invoice',array('bill_number'=>'ES'.$invoice_id),'id='.$invoice_id);
				}	
			}
            if (LATE_CHECKIN_AUTO == 1 and isset($old_ci_time) and !$rr['change_room_from_rr'] AND (($old_status=='BOOKED' AND $new_status=='CHECKIN')))
            {   $hour_in = date('H',$rr['time_in']);
                $minutes = date('i',$rr['time_in']);
                $time_check = ($hour_in*3600 + $minutes*60);
                $li_start_time = explode(':',AUTO_LI_START_TIME);
                $start_time_li = $li_start_time[0]*3600 + $li_start_time[1]*60;
                $li_end_time = explode(':',AUTO_LI_END_TIME);
                $end_time_li = $li_end_time[0]*3600 + $li_end_time[1]*60;
                //echo 1;exit();
                if($time_check >= $start_time_li and $time_check <= $end_time_li and (Date_Time::to_time(date('d/m/Y',$old_ci_time)) < Date_Time::to_time(date('d/m/Y',$rr['time_in']))))
                { 
                    $time = Date_Time::to_time(date('d/m/Y',$rr['time_in']))-86400;
    				$quantity = 1;
    				$arr_late_in['in_date'] = Date_Time::to_orc_date(date('d/m/Y',$time));
                    if($auto_late_checkin_price!='')
                    {
                        $arr_late_in['price'] = $auto_late_checkin_price_detail;
                        $currency_id = (HOTEL_CURRENCY == 'VND')?'USD':'VND';
            	        $exchange_rate = DB::fetch('select id,exchange from currency where id=\''.$currency_id.'\'','exchange');
                        $arr_late_in['usd_price'] = $auto_late_checkin_price_detail/$exchange_rate;
                    }
    				$name_service = $services[$late_checkin_id]['name'];
    				$arr_3 = $arr_late_in + array('time'=>$time,'quantity'=>$quantity,'service_id' =>$late_checkin_id,'name' =>$name_service);
    				$invoice_3 = $invoice + array('late_checkin'=>1,'net_price'=>$rr['net_price'],'type'=>'ROOM','time'=>$time,'note'=>' Add automatic late checkin','total_before_tax'=>($arr_late_in['price']*$quantity),'total_amount'=>(($arr_late_in['price']*$quantity) + (($arr_late_in['price']*$quantity)*$rr['service_rate']*0.01) + (($arr_late_in['price']*$quantity) + (($arr_late_in['price']*$quantity)*$rr['service_rate']*0.01))*$rr['tax_rate']*0.01));
                    if(!empty($arr_late_checkin) && $arr_late_checkin['close']!=1)
                    {
                        unset($arr_3['price']);
                        unset($invoice_3['total_before_tax']);
                        unset($invoice_3['total_amount']);
                        DB::update('extra_service_invoice',$invoice_3,' id='.$arr_late_checkin['id'].'');
    					DB::update('extra_service_invoice_detail',$arr_3,' invoice_id='.$arr_late_checkin['id'].' AND service_id='.$service['id'].'');
                    }
                    else if(empty($arr_late_checkin))
                    {
                        $invoice_id = DB::insert('extra_service_invoice',$invoice_3);
                        $table_id=DB::insert('extra_service_invoice_table',array('from_date'=>$arr_late_in['in_date'],'to_date'=>$arr_late_in['in_date'],'invoice_id'=>$invoice_id));
    					DB::insert('extra_service_invoice_detail',$arr_3 + array('invoice_id'=>$invoice_id,'table_id'=>$table_id));  
    					if(strlen($invoice_id)==1)
                        {
    						$invoice_id = '0'.$invoice_id;
    					}
    					DB::update('extra_service_invoice',array('bill_number'=>'ES'.$invoice_id),'id='.$invoice_id);
                    }
                }
                else
                {
    				if(!empty($arr_late_checkin))
                    {
    					DB::delete('extra_service_invoice_detail','invoice_id='.$arr_late_checkin['id']);
                        DB::delete('extra_service_invoice_table','invoice_id='.$arr_late_checkin['id']);
    					DB::delete('extra_service_invoice','id='.$arr_late_checkin['id']);	
    				}
    			}
            }
		}
	}

}
function update_service_room(){
		
}
// ham xu ly tao extra invoice voi extrabed.
function update_extra_bed_invoice($reservation_room_id,$change_arr)
{
    //System::debug($change_arr); die;
	if($reservation_room = DB::select('reservation_room','id='.$reservation_room_id.' AND reservation_room.status<>\'CANCEL\' AND reservation_room.status<>\'NOSHOW\''))
	{
		if($row = DB::select('extra_service_invoice','reservation_room_id='.$reservation_room_id.' AND use_extra_bed=1') and !$reservation_room['extra_bed'])
		{
			DB::delete('extra_service_invoice_detail','invoice_id='.$row['id']);
			DB::delete('extra_service_invoice','id='.$row['id']);
		}
		elseif($row)
		{
			$data = array(
				'reservation_room_id'=>$reservation_room_id,
				'user_id'=>$reservation_room['checked_in_user_id'],
				'portal_id'=>PORTAL_ID,
				'payment_type'=>'SERVICE',
				'note'=>'Invoice for Extra bed',
				'use_extra_bed'=>1,
				'time'=>$reservation_room['time'],
                'net_price'=>NET_PRICE_SERVICE //KID SUA GIA DICH VU THEO SETTING
			);
            $data1 = array(
				'reservation_room_id'=>$reservation_room_id,
				'user_id'=>$reservation_room['checked_in_user_id'],
                'lastest_edited_user_id'=>User::id(),
				'portal_id'=>PORTAL_ID,
				'payment_type'=>'SERVICE',
				'note'=>'Invoice for Extra bed',
				'use_extra_bed'=>1,
				'time'=>$reservation_room['time'],
                'net_price'=>NET_PRICE_SERVICE //KID SUA GIA DICH VU THEO SETTING
			);
            
			DB::update('extra_service_invoice',$data1,'id='.$row['id']);
			DB::delete('extra_service_invoice_detail','in_date<\''.$reservation_room['extra_bed_from_date'].'\' AND invoice_id='.$row['id']);
			DB::delete('extra_service_invoice_detail','in_date>=\''.$reservation_room['extra_bed_to_date'].'\' AND invoice_id='.$row['id']);
			$from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['extra_bed_from_date'],'/'));
			$to   = Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['extra_bed_to_date'],'/'));
			$d = $from;
			$service = DB::fetch('select * from extra_service where code=\'EXTRA_BED\'');
			$total_extra_bed = 0;
			if($from==$to)
			{
				$extra_service_invoice_detail = array(
					'invoice_id'=>$row['id'],
					'service_id'=>$service['id'],
					'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
					'price'=>isset($change_arr[date('d/m/Y',$d)])?System::calculate_number($change_arr[date('d/m/Y',$d)]):0,
					'time'=>time(),
					'name'=>$service['name'],
					'quantity'=>1,
					'used'=>1
				);
				$total_extra_bed+=isset($change_arr[date('d/m/Y',$d)])?System::calculate_number($change_arr[date('d/m/Y',$d)]):0;
				if($row_detail = DB::select('extra_service_invoice_detail','in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$d)).'\' AND extra_service_invoice_detail.invoice_id='.$row['id'].' '))
				{
					DB::update('extra_service_invoice_detail',$extra_service_invoice_detail,'id='.$row_detail['id']);
				}
				else
				{
					DB::insert('extra_service_invoice_detail',$extra_service_invoice_detail);
				}
			}
			else
			{
				while($d>=$from and $d<$to)
				{
					$extra_service_invoice_detail = array(
						'invoice_id'=>$row['id'],
						'service_id'=>$service['id'],
						'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
						'price'=>System::calculate_number($change_arr[date('d/m/Y',$d)]),
						'time'=>time(),
						'name'=>$service['name'],
						'quantity'=>1,
						'used'=>1
					);
					$total_extra_bed+=System::calculate_number($change_arr[date('d/m/Y',$d)]);
					if($row_detail = DB::select('extra_service_invoice_detail','in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$d)).'\' AND extra_service_invoice_detail.invoice_id='.$row['id'].' '))
					{
						DB::update('extra_service_invoice_detail',$extra_service_invoice_detail,'id='.$row_detail['id']);
					}
					else
					{
						DB::insert('extra_service_invoice_detail',$extra_service_invoice_detail);
					}
					$d=$d+(3600*24);	
				}
			}
			$total_before_tax = $total_extra_bed;
			
			//KID SUA GIA DICH VU THEO SETTING
            //$total = $total_before_tax + ($total_before_tax*EXTRA_SERVICE_SERVICE_CHARGE/100) + ($total_before_tax + $total_before_tax*EXTRA_SERVICE_SERVICE_CHARGE/100)*EXTRA_SERVICE_TAX_RATE/100;
            if(NET_PRICE_SERVICE == 0)
            {
                $total = $total_before_tax + ($total_before_tax*EXTRA_SERVICE_SERVICE_CHARGE/100) + ($total_before_tax + $total_before_tax*EXTRA_SERVICE_SERVICE_CHARGE/100)*EXTRA_SERVICE_TAX_RATE/100;
            }
			else
            {
                $total = $total_before_tax ;
            }
            DB::update('extra_service_invoice',array('total_amount'=>$total,'tax_rate'=>EXTRA_SERVICE_TAX_RATE,'service_rate'=>EXTRA_SERVICE_SERVICE_CHARGE),'id='.$row['id']);
		}
		elseif($reservation_room['extra_bed'])
		{
			$data = array(
				'reservation_room_id'=>$reservation_room_id,
				'user_id'=>$reservation_room['checked_in_user_id']?$reservation_room['checked_in_user_id']:$reservation_room['booked_user_id'],
				'lastest_edited_user_id'=>User::id(),
                'portal_id'=>PORTAL_ID,
				'payment_type'=>'SERVICE',
				'note'=>'Invoice for Extra bed',
				'use_extra_bed'=>1,
				'time'=>$reservation_room['time'],
                'net_price'=>NET_PRICE_SERVICE //KID SUA GIA DICH VU THEO SETTING
			);
            //if(user::is_admin())
//                {
//                    System::debug($data);
//                }
			$id = DB::insert('extra_service_invoice',$data);
			$from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['extra_bed_from_date'],'/'));
			$to   = Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['extra_bed_to_date'],'/'));
			$d = $from;
			$service = DB::fetch('select * from extra_service where code=\'EXTRA_BED\'');
			$total_extra_bed = 0;
			if($from==$to)
			{
			    if(isset($change_arr[date('d/m/Y',$d)]))
                {
                    $extra_service_invoice_detail = array(
    					'invoice_id'=>$id,
    					'service_id'=>$service['id'],
    					'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
    					'price'=>System::calculate_number($change_arr[date('d/m/Y',$d)]),
    					'time'=>time(),
    					'name'=>$service['name'],
    					'quantity'=>1,
    					'used'=>1
    
    				);
    				$total_extra_bed+=System::calculate_number($change_arr[date('d/m/Y',$d)]);
    				
                    DB::insert('extra_service_invoice_detail',$extra_service_invoice_detail);
                } 
							
			}
			else
			{			
				while($d>=$from and $d<$to)
				{
					$extra_service_invoice_detail = array(
						'invoice_id'=>$id,
						'service_id'=>$service['id'],
						'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
						'price'=>System::calculate_number($change_arr[date('d/m/Y',$d)]),
						'time'=>time(),
						'name'=>$service['name'],
						'quantity'=>1,
						'used'=>1
					);
					$total_extra_bed+=System::calculate_number($change_arr[date('d/m/Y',$d)]);
					DB::insert('extra_service_invoice_detail',$extra_service_invoice_detail);
					$d=$d+(3600*24);	
				}
			}
			$total_before_tax = $total_extra_bed;
            
            //KID SUA GIA DICH VU THEO SETTING
            //$total = $total_before_tax + ($total_before_tax*EXTRA_SERVICE_SERVICE_CHARGE/100) + ($total_before_tax + $total_before_tax*EXTRA_SERVICE_SERVICE_CHARGE/100)*EXTRA_SERVICE_TAX_RATE/100;
			if(NET_PRICE_SERVICE == 0)
            {
                $total = $total_before_tax + ($total_before_tax*EXTRA_SERVICE_SERVICE_CHARGE/100) + ($total_before_tax + $total_before_tax*EXTRA_SERVICE_SERVICE_CHARGE/100)*EXTRA_SERVICE_TAX_RATE/100;
                $t_b_t = $total_before_tax;
            }
			else
            {
                $total = $total_before_tax;
                $t_b_t = ($total_before_tax/(1 + EXTRA_SERVICE_TAX_RATE/100))/(1 + EXTRA_SERVICE_SERVICE_CHARGE/100);
            }
			
			DB::update('extra_service_invoice',array('total_amount'=>$total,'tax_rate'=>EXTRA_SERVICE_TAX_RATE,'total_before_tax'=>$t_b_t,'service_rate'=>EXTRA_SERVICE_SERVICE_CHARGE,'bill_number'=>'ES'.$id),'id='.$id);
		}
	}
}
function update_baby_cot_invoice($reservation_room_id,$action='update')
{
	if($reservation_room = DB::select('reservation_room','id='.$reservation_room_id.' AND reservation_room.status<>\'CANCEL\' AND reservation_room.status<>\'NOSHOW\''))
	{
		if($row = DB::select('extra_service_invoice','reservation_room_id='.$reservation_room_id.' AND use_baby_cot=1') and !$reservation_room['baby_cot'])
		{
			DB::delete('extra_service_invoice_detail','invoice_id='.$row['id']);
			DB::delete('extra_service_invoice','id='.$row['id']);
		}
		elseif($row)
		{
			$data = array(
				'reservation_room_id'=>$reservation_room_id,
				'user_id'=>$reservation_room['checked_in_user_id'],
				'portal_id'=>PORTAL_ID,
				'payment_type'=>'SERVICE',
				'note'=>'Invoice for Baby cot',
				'use_baby_cot'=>1,
				'time'=>$reservation_room['time_in'],
                'net_price'=>NET_PRICE_SERVICE //KID SUA GIA DICH VU THEO SETTING
			);
            	$data1 = array(
				'reservation_room_id'=>$reservation_room_id,
				'user_id'=>$reservation_room['checked_in_user_id'],
                'lastest_edited_user_id'=>User::id(),
				'portal_id'=>PORTAL_ID,
				'payment_type'=>'SERVICE',
				'note'=>'Invoice for Baby cot',
				'use_baby_cot'=>1,
				'time'=>$reservation_room['time_in'],
                'net_price'=>NET_PRICE_SERVICE //KID SUA GIA DICH VU THEO SETTING
			);
			DB::update('extra_service_invoice',$data1,'id='.$row['id']);
			DB::delete('extra_service_invoice_detail','in_date<\''.$reservation_room['baby_cot_from_date'].'\' AND invoice_id='.$row['id']);
			DB::delete('extra_service_invoice_detail','in_date>=\''.$reservation_room['baby_cot_to_date'].'\' AND invoice_id='.$row['id']);
			$from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['baby_cot_from_date'],'/'));
			$to   = Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['baby_cot_to_date'],'/'));
			$d = $from;
			$service = DB::fetch('select * from extra_service where code=\'BABY_COT\'');
			$total_baby_cot = 0;
			if($from==$to)
			{
				$extra_service_invoice_detail = array(
					'invoice_id'=>$row['id'],
					'service_id'=>$service['id'],
					'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
					'price'=>$reservation_room['baby_cot_rate']?$reservation_room['baby_cot_rate']:$service['price'],
					'time'=>time(),
					'name'=>$service['name'],
					'quantity'=>1,
					'used'=>1
				);
				$total_baby_cot+=$reservation_room['baby_cot_rate']?$reservation_room['baby_cot_rate']:$service['price'];
				if($row_detail = DB::select('extra_service_invoice_detail','in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$d)).'\' and extra_service_invoice_detail.invoice_id='.$row['id']))
				{
					DB::update('extra_service_invoice_detail',$extra_service_invoice_detail,'id='.$row_detail['id']);
				}
				else
				{
					DB::insert('extra_service_invoice_detail',$extra_service_invoice_detail);
				}
			}
			else
			{
				while($d>=$from and $d<$to)
				{
					$extra_service_invoice_detail = array(
						'invoice_id'=>$row['id'],
						'service_id'=>$service['id'],
						'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
						'price'=>$reservation_room['baby_cot_rate']?$reservation_room['baby_cot_rate']:$service['price'],
						'time'=>time(),
						'name'=>$service['name'],
						'quantity'=>1,
						'used'=>1
					);
					$total_baby_cot+=$reservation_room['baby_cot_rate']?$reservation_room['baby_cot_rate']:$service['price'];
					if($row_detail = DB::select('extra_service_invoice_detail','in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$d)).'\' and extra_service_invoice_detail.invoice_id='.$row['id']))
					{
						DB::update('extra_service_invoice_detail',$extra_service_invoice_detail,'id='.$row_detail['id']);
					}
					else
					{
						DB::insert('extra_service_invoice_detail',$extra_service_invoice_detail);
					}
					$d=$d+(3600*24);	
				}
			}
			$total_before_tax = $total_baby_cot;
            
            //KID SUA GIA DICH VU THEO SETTING
            
            //$total = $total_before_tax + ($total_before_tax*EXTRA_SERVICE_SERVICE_CHARGE) + ($total_before_tax + $total_before_tax*EXTRA_SERVICE_SERVICE_CHARGE)*EXTRA_SERVICE_TAX_RATE/100;
			if(NET_PRICE_SERVICE == 0)
            {
                $total = $total_before_tax + ($total_before_tax*EXTRA_SERVICE_SERVICE_CHARGE/100) + ($total_before_tax + $total_before_tax*EXTRA_SERVICE_SERVICE_CHARGE/100)*EXTRA_SERVICE_TAX_RATE/100;
            }
			else
            {
                $total = $total_before_tax;
            }
			
			DB::update('extra_service_invoice',array('total_amount'=>$total,'tax_rate'=>EXTRA_SERVICE_TAX_RATE,'service_rate'=>EXTRA_SERVICE_SERVICE_CHARGE),'id='.$row['id']);
		}
		elseif($reservation_room['baby_cot'])
		{
			$data = array(
				'reservation_room_id'=>$reservation_room_id,
  	             'user_id'=>$reservation_room['checked_in_user_id']?$reservation_room['checked_in_user_id']:$reservation_room['booked_user_id'],
				'lastest_edited_user_id'=>User::id(),
                'portal_id'=>PORTAL_ID,
				'payment_type'=>'SERVICE',
				'note'=>'Invoice for Baby cot',
				'use_baby_cot'=>1,
				'time'=>$reservation_room['time_in'],
                'net_price'=>NET_PRICE_SERVICE //KID SUA GIA DICH VU THEO SETTING
			);
			$id = DB::insert('extra_service_invoice',$data);
			$from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['baby_cot_from_date'],'/'));
			$to   = Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['baby_cot_to_date'],'/'));
			$d = $from;
			$service = DB::fetch('select * from extra_service where code=\'BABY_COT\'');
			$total_baby_cot = 0;
			if($from==$to)
			{
				$extra_service_invoice_detail = array(
					'invoice_id'=>$id,
					'service_id'=>$service['id'],
					'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
					'price'=>$reservation_room['baby_cot_rate']?$reservation_room['baby_cot_rate']:$service['price'],
					'time'=>time(),
					'name'=>$service['name'],
					'quantity'=>1,
					'used'=>1
				);
				$total_baby_cot+=$reservation_room['baby_cot_rate']?$reservation_room['baby_cot_rate']:$service['price'];
				DB::insert('extra_service_invoice_detail',$extra_service_invoice_detail);
			}
			else
			{			
				while($d>=$from and $d<$to)
				{
					$extra_service_invoice_detail = array(
						'invoice_id'=>$id,
						'service_id'=>$service['id'],
						'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$d)),
						'price'=>$reservation_room['baby_cot_rate']?$reservation_room['baby_cot_rate']:$service['price'],
						'time'=>time(),
						'name'=>$service['name'],
						'quantity'=>1,
						'used'=>1
					);
					$total_baby_cot+=$reservation_room['baby_cot_rate']?$reservation_room['baby_cot_rate']:$service['price'];
					DB::insert('extra_service_invoice_detail',$extra_service_invoice_detail);
					$d=$d+(3600*24);	
				}
			}
			$total_before_tax = $total_baby_cot;
            
             //KID SUA GIA DICH VU THEO SETTING
             
             //$total = $total_before_tax + ($total_before_tax*EXTRA_SERVICE_SERVICE_CHARGE) + ($total_before_tax + $total_before_tax*EXTRA_SERVICE_SERVICE_CHARGE)*EXTRA_SERVICE_TAX_RATE/100;
			if(NET_PRICE_SERVICE == 0)
            {
                $total = $total_before_tax + ($total_before_tax*EXTRA_SERVICE_SERVICE_CHARGE/100) + ($total_before_tax + $total_before_tax*EXTRA_SERVICE_SERVICE_CHARGE/100)*EXTRA_SERVICE_TAX_RATE/100;
                $t_b_t = $total_before_tax;
            }
			else
            {
                $total = $total_before_tax;
                $t_b_t = ($total_before_tax/(1 + EXTRA_SERVICE_TAX_RATE/100))/(1 + EXTRA_SERVICE_SERVICE_CHARGE/100);
            }
			
			DB::update('extra_service_invoice',array('total_amount'=>$total,'tax_rate'=>EXTRA_SERVICE_TAX_RATE,'total_before_tax'=>	$t_b_t,'service_rate'=>EXTRA_SERVICE_SERVICE_CHARGE,'bill_number'=>'ES'.$id),'id='.$id);
		}
	}}
	function check_availability($r_r_id,$cond_room_level,$arrival_time,$departure_time)
    {
		$room_levels = array(
			100000=>array(
				'id'=>'',
				'name'=>'<strong>'.Portal::language('room_level').'</strong>',
				'room_quantity'=>0,
                'is_virtual'=>0
			)
		);
		$cond = '';
		if($r_r_id != ''){
		    if(Url::get('type_check') AND Url::get('type_check')=='group')
            {
                $group = explode("_",$r_r_id);
                
                for($ij=0;$ij<sizeof($group);$ij++)
                {
                    $cond .= 'AND rr.id<>'.$group[$ij].' '; 
                }
            }
            else
			 $cond = ' AND rr.id<>'.$r_r_id.'';
		}
		$room_levels += DB::fetch_all('
			SELECT
				rl.portal_id,rl.id,rl.name,rl.price,0 AS min_room_quantity,rl.color,rl.is_virtual,rl.num_people,
				(SELECT COUNT(*) FROM room WHERE room_level_id = rl.id AND close_room=1) room_quantity
			FROM	
				room_level rl
			WHERE
				'.$cond_room_level.'
				AND rl.portal_id = \''.PORTAL_ID.'\'
                AND rl.is_virtual != 1
			ORDER BY	
				rl.is_virtual,rl.price
		');
        // AND (rl.is_virtual IS NULL OR rl.is_virtual = 0)
		$sql = '
			SELECT 
				r.portal_id,rs.id,rr.status,rs.house_status,rr.time_in,rr.time_out,rr.arrival_time,rr.departure_time,rs.in_date,rr.room_level_id
				,room.room_level_id as room_level,rr.confirm,room.name as room_name
			FROM
				room_status rs
				LEFT OUTER JOIN room on room.id = rs.room_id
				LEFT OUTER JOIN reservation_room rr ON rs.reservation_room_id = rr.id '.$cond.'
				LEFT OUTER JOIN reservation r ON rr.reservation_id = r.id
			WHERE
				( (rs.status <> \'CANCEL\' and rs.status <> \'NOSHOW\') or rs.house_status = \'REPAIR\') 
                AND rs.in_date>=\''.Date_Time::to_orc_date(date('d/m/Y',$arrival_time)).'\' 
                and rs.in_date<\''.Date_Time::to_orc_date(date('d/m/Y',$departure_time)).'\'
			ORDER BY
				rr.room_level_id
		';
		$room_status = DB::fetch_all($sql);
        //System::debug($room_status);
		$days = array();
		for($i = $arrival_time;$i < $departure_time;$i = $i + 24*3600){
			$days[$i]['id'] = $i;
			$days[$i]['value'] = date('d/m',$i);
            $days[$i]['total_room_level'] = array();
            // check lic su
            $his_in_date = '';
            if($h_in_date = DB::fetch('select max(in_date) as in_date from room_history where in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$i)).'\' AND portal_id=\''.PORTAL_ID.'\'','in_date'))
            {
                $his_in_date = $h_in_date;
            }
            elseif ( $h_in_date = DB::fetch('select min(in_date) as in_date from room_history where in_date>\''.Date_Time::to_orc_date(date('d/m/Y',$i)).'\' AND portal_id=\''.PORTAL_ID.'\'','in_date') )
            {
                $his_in_date = $h_in_date;
            }
            
            foreach($room_levels as $real_key=>$real_value)
            {
                if($his_in_date=='')
                {
                    $days[$i]['total_room_level'][$real_key]['room_quantity'] = DB::fetch('SELECT COUNT(*) as count FROM room WHERE room_level_id ='.$real_key,'count');   
                }
                else
                {
                    $days[$i]['total_room_level'][$real_key]['room_quantity'] = DB::fetch('
                                                                                            SELECT
                                                                                                COUNT(room_history_detail.room_id) as count
                                                                                            FROM
                                                                                                room_history_detail
                                                                                                inner join room_history on room_history.id=room_history_detail.room_history_id
                                                                                                inner join room_level on room_level.id=room_history_detail.room_level_id
                                                                                            WHERE
                                                                                                room_history.portal_id=\''.PORTAL_ID.'\'
                                                                                                AND room_history.in_date=\''.$his_in_date.'\'
                                                                                                AND room_history_detail.close_room=1
                                                                                                AND room_level.id='.$real_key.'
                                                                                        ','count');
                }
                
                if($room_levels[$real_key]['room_quantity']>$days[$i]['total_room_level'][$real_key]['room_quantity'])
                {
                    $room_levels[$real_key]['room_quantity']=$days[$i]['total_room_level'][$real_key]['room_quantity'];
                }
                
            }
		}
        
        /** check inventory **/
        $inventory = array();
        if(SITEMINDER_TWO_WAY){
            $cond_inventory = '1=1 ';
            $cond_inventory .= ' and siteminder_room_type_time.in_date>=\''.Date_Time::to_orc_date(date('d/m/Y',$arrival_time)).'\' and siteminder_room_type_time.in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$departure_time)).'\'';
            $cond_inventory_rate = ' siteminder_rate_avail_time.in_date>=\''.Date_Time::to_orc_date(date('d/m/Y',$arrival_time)).'\' and siteminder_rate_avail_time.in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$departure_time)).'\'';
            $inventory = DB::fetch_all('
                                    SELECT
                                        siteminder_room_type_time.*,
                                        siteminder_room_type.room_level_id
                                    FROM
                                        siteminder_room_type_time
                                        inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_type_time.siteminder_room_type_id
                                    WHERE
                                        '.$cond_inventory.'
                                        and siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                    ');
            $inventory_rate = DB::fetch_all('
                                    SELECT
                                        siteminder_rate_avail_time.*,
                                        siteminder_room_type.room_level_id
                                    FROM
                                        siteminder_rate_avail_time
                                        inner join siteminder_room_rate on siteminder_room_rate.id=siteminder_rate_avail_time.siteminder_room_rate_id
                                        inner join siteminder_room_type on siteminder_room_type.id=siteminder_room_rate.siteminder_room_type_id
                                    WHERE
                                        '.$cond_inventory_rate.'
                                        and siteminder_room_type.portal_id=\''.PORTAL_ID.'\'
                                        and siteminder_room_rate.availability=\'MANAGED\'
                                    ');
            
        }
        /** end check inventory **/
        
        /** check Allotment **/
        if(USE_ALLOTMENT){
            $cond_allotment = ' room_allotment_avail_rate.in_date>=\''.Date_Time::to_orc_date(date('d/m/Y',$arrival_time)).'\' and room_allotment_avail_rate.in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$departure_time)).'\'';
            $allotment = DB::fetch_all('
                                        SELECT
                                            room_allotment_avail_rate.*,
                                            room_allotment.room_level_id,
                                            to_char(room_allotment_avail_rate.in_date,\'DD/MM/YYYY\') as in_date
                                        FROM
                                            room_allotment_avail_rate
                                            inner join room_allotment on room_allotment.id=room_allotment_avail_rate.room_allotment_id
                                        WHERE
                                            '.$cond_allotment.'
                                            and room_allotment.portal_id=\''.PORTAL_ID.'\'
                                        ');
        }
        /** end check allotment **/
        
        /** Start : Ninh them lay mau cho thu 7,cn **/
        /** start check HLS **/
        $hls_availability=array();
        if(USE_HLS){
           $hls_availability=DB::fetch_all('select 
                                                HOTELLINK_AVAILABILITY.id
                                                ,to_char(HOTELLINK_AVAILABILITY.in_date,\'DD/MM/YYYY\') as in_date
                                                ,HOTELLINK_AVAILABILITY.total
                                                ,room_level.id as room_level_id  
                                            from HOTELLINK_AVAILABILITY
                                            inner join room_level on HOTELLINK_AVAILABILITY.hotellink_room_id=room_level.hotellink_room_id
                                            where HOTELLINK_AVAILABILITY.in_date>=\''.Date_Time::to_orc_date(date('d/m/Y',$arrival_time)).'\'
                                                  and HOTELLINK_AVAILABILITY.in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$departure_time)).'\'  
                                            ');                                
        }
        /** end check HLS **/
        foreach($days as $key=>$value)
        {
            //System::debug($value['id']);
            //System::debug(date('D',$value['id']));
            if(date('D',date($value['id']))=='Sat')
            {
                $days[$key]['color'] = 'blue' ;
            }elseif(date('D',date($value['id']))=='Sun')
            { 
                $days[$key]['color'] = 'red';
            }else{
                $days[$key]['color'] = 'Black' ;
            }
        }
        //System::debug($room_levels);
        /** End : Ninh them lay mau cho t7,cn **/
		foreach($room_levels as $key=>$value){
            $room_levels[$key]['total_room_quantity'] = $value['room_quantity'];
			$min = 10000;
			foreach($days as $k=>$v){
				$room_quantity = $v['total_room_level'][$key]['room_quantity'];
                $room_cf = 0;
                $room_not_cf = 0;
				foreach($room_status as $kk=>$vv){
					if($vv['room_level_id'] == $key and Date_Time::convert_orc_date_to_date($vv['in_date'],'/') == date('d/m/Y',$k) and $vv['departure_time'] != $vv['in_date']){
						$room_quantity -= 1;
                        if($vv['confirm']==1)
                            $room_cf ++;
                        elseif($vv['confirm']==0)
                        {
                            $room_not_cf++;
                        }
                            
					}else if($vv['room_level'] == $key and Date_Time::convert_orc_date_to_date($vv['in_date'],'/') == date('d/m/Y',$k) and $vv['house_status']=='REPAIR'){//$vv['room_level'] == $key and Date_Time::convert_orc_date_to_date($vv['in_date'],'/') == date('d/m/Y',$k) and $vv['house_status']=='REPAIR' and $vv['status']==''
						$room_quantity -= 1;
					}
				}
                /** check inventory **/
                $count_inventory = 0;
                if(SITEMINDER_TWO_WAY){
                    foreach($inventory as $keyInven=>$valueInven){
                        if($valueInven['room_level_id']==$key and date('d/m/Y',$valueInven['time'])==date('d/m/Y',$k)){
                            $room_quantity -= $valueInven['availability'];
                            $count_inventory += $valueInven['availability'];
                        }
                    }
                    foreach($inventory_rate as $keyInvenR=>$valueInvenR){
                        if($valueInvenR['room_level_id']==$key and date('d/m/Y',$valueInvenR['time'])==date('d/m/Y',$k)){
                            $room_quantity -= $valueInvenR['availability'];
                            $count_inventory += $valueInvenR['availability'];
                        }
                    }
                }
                if(USE_ALLOTMENT){
                    foreach($allotment as $keyAllM=>$valueAllM){
                        if($valueAllM['room_level_id']==$key and $valueAllM['in_date']==date('d/m/Y',$k)){
                            $room_quantity -= $valueAllM['availability'];
                            $count_inventory += $valueAllM['availability'];
                        }
                    }
                }
                if(USE_HLS){
                    foreach($hls_availability as $keyhls=>$valuehls){
                        if($valuehls['room_level_id']==$key and $valuehls['in_date']==date('d/m/Y',$k)){
                            $room_quantity -= $valuehls['total'];
                            $count_inventory += $valuehls['total'];
                        }
                    }
                }
                /** end check inventory **/
				$room_levels[$key]['day_items'][$k]['id'] = $k;
				if($room_quantity<0){
					$bgr = 'background-color:red;color:white;font-weight:bold;';			
				}else{
					$bgr='';
				}
				if(isset($value['color'])){
					$room_levels[$key]['day_items'][$k]['room_quantity'] = ($key==100000)?'<span class="check-availability-day header" style=" font-size:12px;width:35px color:'.$v['color'].'; border-top:'.$value['color'].' 5px solid;'.$bgr.'">'.$v['value'].'</span>':'<span id="'.$value['id'].'_'.$v['id'].'" onclick = "list_avalible('.$value['id'].',\''.$v['id'].'\','.$room_quantity.')" class="check-availability-day" style="width:35px;font-size:12px; border-top:'.$value['color'].' 5px solid;'.$bgr.'">'.$room_quantity.'</span>';
				}else{
					$room_levels[$key]['day_items'][$k]['room_quantity'] = ($key==100000)?'<span class="check-availability-day header" style="font-size:12px;width:35px; color:'.$v['color'].'; border-top:#f1f1f1 5px solid;'.$bgr.'">'.$v['value'].'</span>':'<span class="check-availability-day" style="width:35px;font-size:12px; border-top:#f1f1f1 5px solid;'.$bgr.'">'.$room_quantity.'</span>';
				}
				if($min > $room_quantity){
					$min = $room_quantity;
				}
                //Cai nay de cong tong
                $room_levels[$key]['day_items'][$k]['number_room_quantity'] = $room_quantity;
                $room_levels[$key]['day_items'][$k]['room_cf'] = $room_cf;
                $room_levels[$key]['day_items'][$k]['room_not_cf'] = $room_not_cf;
                /** check inventory **/
                if(SITEMINDER_TWO_WAY or USE_HLS or USE_ALLOTMENT){
                    $room_levels[$key]['day_items'][$k]['inventory'] = $count_inventory;
                }
                /** end check inventory **/
			}
			$room_levels[$key]['min_room_quantity'] = $min;
		}
        //System::debug($room_levels);
		return $room_levels;	
	}
    function check_availability_new($r_r_id,$cond_room_level,$arrival_time,$departure_time)
    {
		$room_levels = array(
			100000=>array(
				'id'=>'',
				'name'=>'<strong>'.Portal::language('room_level').'</strong>',
				'room_quantity'=>0,
                'is_virtual'=>0
			)
		);
		$cond = '';
		if($r_r_id != ''){
			$cond = ' AND rr.id<>'.$r_r_id.'';
		}
		$room_levels += DB::fetch_all('
			SELECT
				rl.portal_id,rl.id,rl.name,rl.price,0 AS min_room_quantity,rl.color,rl.is_virtual,
				(SELECT COUNT(*) FROM room WHERE room_level_id = rl.id) room_quantity
			FROM	
				room_level rl
                
			WHERE
				'.$cond_room_level.'
				AND rl.portal_id = \''.PORTAL_ID.'\'
                AND rl.is_virtual != 1
			ORDER BY	
				rl.id
		');
        // AND (rl.is_virtual IS NULL OR rl.is_virtual = 0)
		$sql = '
			SELECT 
				r.portal_id,rs.id,rr.status,rs.house_status,rr.time_in,rr.time_out,rr.arrival_time,rr.departure_time,rs.in_date,rr.room_level_id
				,room.room_level_id as room_level
			FROM
				room_status rs
				LEFT OUTER JOIN room on room.id = rs.room_id
				LEFT OUTER JOIN reservation_room rr ON rs.reservation_room_id = rr.id '.$cond.'
				LEFT OUTER JOIN reservation r ON rr.reservation_id = r.id
                 
			WHERE
				(rs.status <> \'CANCEL\' and rs.status <> \'NOSHOW\') AND rs.in_date>=\''.Date_Time::to_orc_date(date('d/m/Y',$arrival_time)).'\' and rs.in_date<\''.Date_Time::to_orc_date(date('d/m/Y',$departure_time)).'\'
                
			ORDER BY
				rr.room_level_id
		';
		$room_status = DB::fetch_all($sql);
		$days = array();
		for($i = $arrival_time;$i <= $departure_time;$i = $i + 24*3600){
			$days[$i]['id'] = $i;
			$days[$i]['value'] = date('d/m',$i);
		}
        /** Start : Ninh them lay mau cho thu 7,cn **/
        foreach($days as $key=>$value)
        {
            //System::debug($value['id']);
            //System::debug(date('D',$value['id']));
            if(date('D',date($value['id']))=='Sat')
            {
                $days[$key]['color'] = 'blue' ;
            }elseif(date('D',date($value['id']))=='Sun')
            { 
                $days[$key]['color'] = 'red';
            }else{
                $days[$key]['color'] = 'Black' ;
            }
        }
        /** End : Ninh them lay mau cho t7,cn **/
		foreach($room_levels as $key=>$value){
            
                $room_levels[$key]['total_room_quantity'] = $value['room_quantity'];
 
			$min = 10000;
			foreach($days as $k=>$v){
				$room_quantity = $value['room_quantity'];
				foreach($room_status as $kk=>$vv){
					if($vv['room_level_id'] == $key and Date_Time::convert_orc_date_to_date($vv['in_date'],'/') == date('d/m/Y',$k) and $vv['departure_time'] != $vv['in_date']){
						$room_quantity -= 1;
					}else if($vv['room_level'] == $key and Date_Time::convert_orc_date_to_date($vv['in_date'],'/') == date('d/m/Y',$k) and $vv['house_status']=='REPAIR' and $vv['status']==''){
						$room_quantity -= 1;
					}
				}
				$room_levels[$key]['day_items'][$k]['id'] = $k;
				if($room_quantity<0){
					$bgr = 'background-color:red;color:white;font-weight:bold;';			
				}else{
					$bgr='';
				}
				if(isset($value['color'])){
					$room_levels[$key]['day_items'][$k]['room_quantity'] = ($key==100000)?'<span class="check-availability-day header" style="width:35px; border-top:'.$value['color'].' 5px solid;'.$bgr.'">'.$v['value'].'</span>':'<span class="check-availability-day" style="border-top:'.$value['color'].' 5px solid;'.$bgr.'">'.$room_quantity.'</span>';
				}else{
					$room_levels[$key]['day_items'][$k]['room_quantity'] = ($key==100000)?'<span class="check-availability-day header" style="width:35px; border-top:#f1f1f1 5px solid;'.$bgr.'">'.$v['value'].'</span>':'<span class="check-availability-day" style=" border-top:#f1f1f1 5px solid;'.$bgr.'">'.$room_quantity.'</span>';
				}
				if($min > $room_quantity){
					$min = $room_quantity;
				}
                //Cai nay de cong tong
                $room_levels[$key]['day_items'][$k]['number_room_quantity'] = $room_quantity;
			}
			$room_levels[$key]['min_room_quantity'] = $min;
		}
		return $room_levels;	
	}
	function config_telephone($status,$room_id,$reservation_room_id)
	{
		if($room_id)
		{
			$guest = DB::fetch('
				SELECT
					reservation_room.id,
					CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as full_name,
					room.name,
					telephone_number.phone_number
				FROM
					room
					INNER JOIN telephone_number ON telephone_number.room_id = room.id
					INNER JOIN reservation_room ON room.id = reservation_room.room_id AND reservation_room.status=\'CHECKIN\'
					LEFT OUTER JOIN reservation_traveller ON reservation_room.id = reservation_traveller.reservation_room_id
					LEFT OUTER JOIN traveller ON traveller.id = reservation_traveller.traveller_id
				WHERE
					reservation_room.id = '.$reservation_room_id.'
			');
			$phone_number = DB::fetch('select id,phone_number from telephone_number where room_id='.$room_id,'phone_number');
			require_once 'packages/core/includes/utils/vn_code.php';
			require_once 'packages/hotel/packages/reception/Modules/includes/telephone_fox.php';		
			if($status=='CHECKIN')
			{
				TelephoneLib::set_telephone_command($phone_number,'OUT','');
				TelephoneLib::set_telephone_command($phone_number,'IN',convert_utf8_to_latin($guest['full_name']));		
				TelephoneLib::set_telephone_command($phone_number,'UB',0);
			}
			if($status=='CHECKOUT')
			{
				TelephoneLib::set_telephone_command($phone_number,'OUT','');
				TelephoneLib::set_telephone_command($phone_number,'UB',1);
			}
		}
	}
    function check_over_room_level($mi_reservation_room,$time_in,$time_out,$room_level_ids)
    {
        $extra_cond = ' 1 = 1 ';
        if($room_level_ids!='')
        {
            $extra_cond .= ' AND rl.id in ('.$room_level_ids.')';
        }
    	$room_level = DB::fetch_all('
    		SELECT
                rl.id,
                rl.name,
    			(SELECT COUNT(*) FROM room WHERE room_level_id = rl.id) as room_quantity
    		FROM	
    			room_level rl
    		WHERE
    			'.$extra_cond.'
    			AND rl.portal_id = \''.PORTAL_ID.'\'
    		ORDER BY	
    			rl.name
    	');
        //System::debug($room_level);
        $day = array();
    	$room_status = array();
        $cond_room_status = '';
        if(Url::get('id'))
        {
            $cond_room_status.='AND rs.reservation_id != '.Url::get('id').'';
        }
    	$sql = '
    			SELECT 
    				r.portal_id,
                    rs.id,
                    rr.status,
                    rr.time_in,
                    rr.time_out,
                    rr.room_id,
                    rr.arrival_time,
                    rr.departure_time,
                    rs.in_date,
                    to_char(rs.in_date,\'DD/MM/YYYY\') as date_in,
                    rr.room_level_id,
                    rr.time_in as time_in_rr,
                    rr.time_out as time_out_rr ,
                    rr.id as rr_id ,
                    rs.house_status ,
                    room.room_level_id as room_level
    			FROM
    				room_status rs
                    LEFT OUTER JOIN room on room.id = rs.room_id
    				LEFT OUTER JOIN reservation_room rr ON rs.reservation_room_id = rr.id 
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
                    AND (rr.room_level_id in ('.$room_level_ids.') or room.room_level_id in ('.$room_level_ids.') )
                    AND rs.in_date>=\''.Date_Time::to_orc_date(date('d/m/Y',$time_in)).'\' 
                    and rs.in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$time_out)).'\'
                    '.$cond_room_status.'
    			ORDER BY
    				rr.room_level_id
    		';	
  	    $room_status = DB::fetch_all($sql);
        foreach($mi_reservation_room as $key=>$value)
        {
            if($value['status']<>'CANCEL' and $value['status']<>'NOSHOW' and $value['status']<>'CHECKOUT')
            {
                $time_in_rr = Date_Time::to_time($value['arrival_time']); 
    	        $time_out_rr=Date_Time::to_time($value['departure_time']);
                $arr = explode(':',$value['time_in']);
    			$time_in_rr= $time_in_rr + intval($arr[0])*3600+intval($arr[1])*60;
    			$arr = explode(':',$value['time_out']);
    			$time_out_rr= $time_out_rr + intval($arr[0])*3600+intval($arr[1])*60;
                $mi_reservation_room[$key]['time_in_rr'] = $time_in_rr;
                $mi_reservation_room[$key]['time_out_rr'] = $time_out_rr;
                
            }
        }
        $min = array();
        $room_level_check = array();
        //System::debug($room_status); //exit();
        foreach($mi_reservation_room as $key=>$value)
        {
            if($value['status']<>'CANCEL' and $value['status']<>'NOSHOW' and $value['status']<>'CHECKOUT')
            {
                $days = array();
                for($i = Date_time::to_time(date('d/m/Y',$value['time_in_rr']));$i <= Date_time::to_time(date('d/m/Y',$value['time_out_rr']));$i = $i + 24*3600)
                {
            		$days[$i]['id'] = $i;
            		$days[$i]['value'] = date('d/m/Y',$i);
            	}
                $min[$key]['min'] = 10000;
                $min[$key]['room_level_id'] = $value['room_level_id']; // add check siteminder
                $min[$key]['room_level_name'] = $value['room_level_name']; // add check siteminder
                $min[$key]['arrival_time'] = $value['time_in_rr']; // add check siteminder
                $min[$key]['departure_time'] = $value['time_out_rr']; // add check siteminder
                foreach($days as $k=>$v)
                {
                    $arrival_not_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)] = array(); /** mang chua gan phong co ngay den = ngay xet  **/
                    $arrival_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)] = array(); /** mang da gan phong co ngay den = ngay xet  **/
                    $arrival_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)] = array(); /** mang chua tat ca co ngay den = ngay xet  **/
                    
                    $departure_not_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)] = array();
                    $departure_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)] = array();
                    $departure_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)] = array();
                    
                    $room_level[$value['room_level_id']]['date'][date('d/m/Y',$k)]['room_quantity'] = $room_level[$value['room_level_id']]['room_quantity'];
                    foreach($room_status as $kk=>$vv) /** so sanh voi ban ghi cu **/
                    {
                        if($vv['room_level_id']==$value['room_level_id'] and $vv['date_in'] == date('d/m/Y',$k))
                        {
                            if(Date_time::to_time(date('d/m/Y',$vv['time_in']))<Date_time::to_time(date('d/m/Y',$k)) and  Date_time::to_time(date('d/m/Y',$vv['time_out']))>Date_time::to_time(date('d/m/Y',$k)))
                            {
                                $room_level[$value['room_level_id']]['date'][date('d/m/Y',$k)]['room_quantity'] -=1;
                            }
                            else
                            {
                                if(date('d/m/Y',$vv['time_in'])==date('d/m/Y',$vv['time_out']) and date('d/m/Y',$vv['time_in'])==date('d/m/Y',$k))
                                {
                                    if($vv['time_in']>=$value['time_out_rr'])
                                    {
                                        $arrival_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)][$kk] = $vv;
                                        if($vv['room_id']=='')
                                            $arrival_not_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)][$kk] = $vv;
                                        else
                                            $arrival_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)][$kk] = $vv;
                                    }
                                    else
                                    {
                                        $departure_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)][$kk] = $vv;
                                        if($vv['room_id']=='')
                                            $departure_not_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)][$kk] = $vv;
                                        else
                                            $departure_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)][$kk] = $vv;
                                    }
                                }
                                else
                                {
                                    if(date('d/m/Y',$k)==date('d/m/Y',$vv['time_in']))
                                    {
                                        $arrival_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)][$kk] = $vv;
                                        if($vv['room_id']=='')
                                            $arrival_not_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)][$kk] = $vv;
                                        else
                                            $arrival_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)][$kk] = $vv;
                                    }    
                                    if(date('d/m/Y',$k)==date('d/m/Y',$vv['time_out']))
                                    {
                                        $departure_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)][$kk] = $vv;
                                        if($vv['room_id']=='')
                                            $departure_not_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)][$kk] = $vv;
                                        else
                                            $departure_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)][$kk] = $vv;
                                    }
                                }
                            }
                        }
                        elseif($vv['room_level']==$value['room_level_id'] and $vv['date_in'] == date('d/m/Y',$k) and $vv['house_status']=='REPAIR'){
                            //echo 1;
                            $room_level[$value['room_level_id']]['date'][date('d/m/Y',$k)]['room_quantity'] -=1;
                        }
                    }
                    foreach($mi_reservation_room as $key1=>$value1)
                    {
                        if($value1['status']<>'CANCEL' and $value1['status']<>'NOSHOW' and $value1['status']<>'CHECKOUT')
                        {
                            if($key1!=$key)
                            {
                                if($value1['room_level_id']==$value['room_level_id'])
                                {
                                    if(Date_time::to_time(date('d/m/Y',$value1['time_in_rr'])) < $k and Date_time::to_time(date('d/m/Y',$value1['time_out_rr']))>$k)
                                    {
                                        $room_level[$value['room_level_id']]['date'][date('d/m/Y',$k)]['room_quantity'] -=1;
                                    }
                                    else
                                    {
                                        if(date('d/m/Y',$value1['time_in_rr'])==date('d/m/Y',$value1['time_out_rr']) and date('d/m/Y',$value1['time_in_rr'])==date('d/m/Y',$k))
                                        {
                                            if($value1['time_in_rr']>=$value['time_out_rr'])
                                            {
                                                $arrival_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)]['r'.$key1] = $value1;
                                                if($value1['room_id']=='')
                                                    $arrival_not_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)]['r'.$key1] = $value1;
                                                else
                                                    $arrival_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)]['r'.$key1] = $value1;
                                            }
                                            else
                                            {
                                                $departure_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)]['r'.$key1] = $value1;
                                                if($value1['room_id']=='')
                                                    $departure_not_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)]['r'.$key1] = $value1;
                                                else
                                                    $departure_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)]['r'.$key1] = $value1;
                                            }
                                        }
                                        else
                                        {
                                            if(date('d/m/Y',$k)==date('d/m/Y',$value1['time_in_rr']))
                                            {
                                                $arrival_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)]['r'.$key1] = $value1;
                                                if($value1['room_id']=='')
                                                    $arrival_not_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)]['r'.$key1] = $value1;
                                                else
                                                    $arrival_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)]['r'.$key1] = $value1;
                                            }    
                                            if(date('d/m/Y',$k)==date('d/m/Y',$value1['time_out_rr']))
                                            {
                                                $departure_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)]['r'.$key1] = $value1;
                                                if($value1['room_id']=='')
                                                    $departure_not_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)]['r'.$key1] = $value1;
                                                else
                                                    $departure_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)]['r'.$key1] = $value1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    if(date('d/m/Y',$k)==date('d/m/Y',$value['time_in_rr']))/** ngay xet bang ngay den cua ban ghi **/
                    {
                        if(sizeof($arrival_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)])>0)
                        {
                            foreach($arrival_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)] as $kkk=>$vvv)
                            {
                                if(date('d/m/Y',$value['time_in_rr'])!=date('d/m/Y',$value['time_out_rr']))
                                $room_level[$value['room_level_id']]['date'][date('d/m/Y',$k)]['room_quantity'] -=1;
                            }
                        }
                        if(sizeof($departure_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)])>0)
                        {
                            foreach($departure_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)] as $kkk=>$vvv)
                            {
                                if($vvv['room_id'] != '')
                                {
                                    
                                    if(sizeof($arrival_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)])>0)
                                    {
                                        $min_min = 9999999999999;
                                        $check_min_id = false;
                                        $check_room_id = false;
                                        foreach($arrival_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)] as $kkk2=>$vvv2)
                                        {
                                            if($vvv2['room_id']==$vvv['room_id'])
                                            {
                                                $check_room_id = $kkk2;
                                            }
                                            else
                                            {
                                                if($vvv2['time_in_rr']>=$vvv['time_out_rr'])
                                                {
                                                    $time_check = $vvv2['time_in_rr']-$vvv['time_out_rr'];
                                                    if($time_check<$min_min)
                                                    {
                                                        $check_min_id = $kkk2;
                                                    }
                                                }
                                            }
                                        }
                                        if($check_room_id!=false)
                                        {
                                            unset($arrival_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)][$check_room_id]);
                                        }
                                        else
                                        {
                                            if($check_min_id!=false)
                                            {
                                                unset($arrival_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)][$check_min_id]);
                                            }
                                            else
                                            {
                                                if($value['time_in_rr']<$vvv['time_out_rr'])
                                                $room_level[$value['room_level_id']]['date'][date('d/m/Y',$k)]['room_quantity'] -=1;
                                            }
                                        }
                                    }
                                    else
                                    {
                                        if($value['time_in_rr']<$vvv['time_out_rr'])
                                        $room_level[$value['room_level_id']]['date'][date('d/m/Y',$k)]['room_quantity'] -=1;
                                    }
                                }
                            }
                        }
                        if(sizeof($departure_not_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)])>0)
                        {
                            foreach($departure_not_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)] as $kkk=>$vvv)
                            {
                                if($vvv['room_id'] == '')
                                {
                                    
                                    if(sizeof($arrival_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)])>0)
                                    {
                                        $min_min = 9999999999999;
                                        $check_min_id = false;
                                        $check_room_id = false;
                                        foreach($arrival_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)] as $kkk2=>$vvv2)
                                        {
                                            if($vvv2['time_in_rr']>=$vvv['time_out_rr'])
                                            {
                                                $time_check = $vvv2['time_in_rr']-$vvv['time_out_rr'];
                                                if($time_check<$min_min)
                                                {
                                                    $check_min_id = $kkk2;
                                                }
                                            }
                                        }
                                        if($check_room_id!=false)
                                        {
                                            unset($arrival_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)][$check_room_id]);
                                        }
                                        else
                                        {
                                            if($check_min_id!=false)
                                            {
                                                unset($arrival_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)][$check_min_id]);
                                            }
                                            else
                                            {
                                                if($value['time_in_rr']<$vvv['time_out_rr'])
                                                $room_level[$value['room_level_id']]['date'][date('d/m/Y',$k)]['room_quantity'] -=1;
                                            }
                                        }
                                    }
                                    else
                                    {
                                        if($value['time_in_rr']<$vvv['time_out_rr'])
                                        $room_level[$value['room_level_id']]['date'][date('d/m/Y',$k)]['room_quantity'] -=1;
                                    }
                                }
                            }
                        }
                    }
                    elseif(date('d/m/Y',$k)==date('d/m/Y',$value['time_out_rr']))/** ngay xet bang ngay Ä�Æ�Ã¯Â¿Â½Ä�Â¯Ã�Â¿Ã�Â½i cua ban ghi **/
                    {
                        if(sizeof($departure_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)])>0)
                        {
                            foreach($departure_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)] as $kkk=>$vvv)
                            {
                                $room_level[$value['room_level_id']]['date'][date('d/m/Y',$k)]['room_quantity'] -=1;
                            }
                        }
                        if(sizeof($arrival_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)])>0)
                        {
                            foreach($arrival_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)] as $kkk=>$vvv)
                            {
                                if($vvv['room_id'] != '')
                                {
                                    if(sizeof($departure_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)])>0)
                                    {
                                        $min_min = 9999999999999;
                                        $check_min_id = false;
                                        $check_room_id = false;
                                        foreach($departure_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)] as $kkk2=>$vvv2)
                                        {
                                            if($vvv2['room_id']==$vvv['room_id'])
                                            {
                                                $check_room_id = $kkk2;
                                            }
                                            else
                                            {
                                                if($vvv2['time_out_rr']<=$vvv['time_in_rr'])
                                                {
                                                    $time_check = $vvv['time_in_rr']-$vvv2['time_out_rr'];
                                                    if($time_check<$min_min)
                                                    {
                                                        $check_min_id = $kkk2;
                                                    }
                                                }
                                            }
                                        }
                                        if($check_room_id!=false)
                                        {
                                            unset($departure_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)][$check_room_id]);
                                        }
                                        else
                                        {
                                            if($check_min_id!=false)
                                            {
                                                unset($departure_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)][$check_min_id]);
                                            }
                                            else
                                            {
                                                if($value['time_out_rr']>$vvv['time_in_rr'])
                                                $room_level[$value['room_level_id']]['date'][date('d/m/Y',$k)]['room_quantity'] -=1;
                                            }
                                        }
                                    }
                                    else
                                    {
                                        if($value['time_out_rr']>$vvv['time_in_rr'])
                                        $room_level[$value['room_level_id']]['date'][date('d/m/Y',$k)]['room_quantity'] -=1;
                                    }
                                }
                            }
                        }
                        if(sizeof($arrival_not_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)])>0)
                        {
                            //System::debug($arrival_arr);
                            foreach($arrival_not_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)] as $kkk=>$vvv)
                            {
                                if($vvv['room_id'] == '')
                                {
                                    if(sizeof($departure_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)])>0)
                                    {
                                        $min_min = 9999999999999;
                                        $check_min_id = false;
                                        foreach($departure_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)] as $kkk2=>$vvv2)
                                        {
                                            //echo '1+'.$vvv2['time_out_rr'].'--2+'.$vvv['time_in_rr'];
                                            if($vvv2['time_out_rr']<=$vvv['time_in_rr'])
                                            {
                                                $time_check = $vvv['time_in_rr']-$vvv2['time_out_rr'];
                                                if($time_check<$min_min)
                                                {
                                                    $check_min_id = $kkk2;
                                                }
                                            }
                                        }
                                        if($check_min_id!=false)
                                        {
                                            unset($departure_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)][$check_min_id]);
                                        }
                                        else
                                        {
                                            if($value['time_out_rr']>$vvv['time_in_rr'])
                                            $room_level[$value['room_level_id']]['date'][date('d/m/Y',$k)]['room_quantity'] -=1;
                                        }
                                    }
                                    else
                                    {
                                        if($value['time_out_rr']>$vvv['time_in_rr'])
                                        $room_level[$value['room_level_id']]['date'][date('d/m/Y',$k)]['room_quantity'] -=1;
                                    }
                                }
                            }
                        }
                    }
                    else /** ngay xet bang ngay giua dang o cua ban ghi **/
                    {
                        if(sizeof($departure_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)])>0)
                        {
                            foreach($departure_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)] as $kkk=>$vvv)
                            {
                                $room_level[$value['room_level_id']]['date'][date('d/m/Y',$k)]['room_quantity'] -=1;
                            }
                        }
                        if(sizeof($arrival_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)])>0)
                        {
                            foreach($arrival_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)] as $kkk=>$vvv)
                            {
                                if($vvv['room_id'] != '')
                                {
                                    if(sizeof($departure_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)])>0)
                                    {
                                        $min_min = 9999999999999;
                                        $check_min_id = false;
                                        $check_room_id = false;
                                        foreach($departure_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)] as $kkk2=>$vvv2)
                                        {
                                            if($vvv2['room_id']==$vvv['room_id'])
                                            {
                                                $check_room_id = $kkk2;
                                            }
                                            else
                                            {
                                                if($vvv2['time_out_rr']<=$vvv['time_in_rr'])
                                                {
                                                    $time_check = $vvv['time_in_rr']-$vvv2['time_out_rr'];
                                                    if($time_check<$min_min)
                                                    {
                                                        $check_min_id = $kkk2;
                                                    }
                                                }
                                            }
                                        }
                                        if($check_room_id!=false)
                                        {
                                            unset($departure_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)][$check_room_id]);
                                        }
                                        else
                                        {
                                            if($check_min_id!=false)
                                            {
                                                unset($departure_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)][$check_min_id]);
                                            }
                                            else
                                            {
                                                $room_level[$value['room_level_id']]['date'][date('d/m/Y',$k)]['room_quantity'] -=1;
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $room_level[$value['room_level_id']]['date'][date('d/m/Y',$k)]['room_quantity'] -=1;
                                    }
                                }
                            }
                        }
                        if(sizeof($arrival_not_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)])>0)
                        {
                            //System::debug($arrival_arr);
                            foreach($arrival_not_room_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)] as $kkk=>$vvv)
                            {
                                if($vvv['room_id'] == '')
                                {
                                    $min_min = 9999999999999;
                                    $check_min_id = false;
                                    foreach($departure_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)] as $kkk2=>$vvv2)
                                    {
                                        //echo '1+'.$vvv2['time_out_rr'].'--2+'.$vvv['time_in_rr'];
                                        if($vvv2['time_out_rr']<=$vvv['time_in_rr'])
                                        {
                                            $time_check = $vvv['time_in_rr']-$vvv2['time_out_rr'];
                                            if($time_check<$min_min)
                                            {
                                                $check_min_id = $kkk2;
                                            }
                                        }
                                    }
                                    if($check_min_id!=false)
                                    {
                                        unset($departure_arr[$value['room_level_id'].'-'.date('d/m/Y',$k)][$check_min_id]);
                                    }
                                    else
                                    {
                                        $room_level[$value['room_level_id']]['date'][date('d/m/Y',$k)]['room_quantity'] -=1;
                                    }
                                }
                            }
                        }
                    }
                    $room_level[$value['room_level_id']]['date'][date('d/m/Y',$k)]['room_quantity'] -=1;
                    if($min[$key]['min'] > $room_level[$value['room_level_id']]['date'][date('d/m/Y',$k)]['room_quantity'])
                    {
            			$min[$key]['min'] = $room_level[$value['room_level_id']]['date'][date('d/m/Y',$k)]['room_quantity'];
                        $min[$key]['room_level_id'] = $value['room_level_id']; // add check siteminder
                        $min[$key]['room_level_name'] = $value['room_level_name'];
                        $min[$key]['arrival_time'] = $value['time_in_rr'];
                        $min[$key]['departure_time'] = $value['time_out_rr'];
                        $min[$key]['date'] = date('d/m/Y',$k);
            		}
                }
            }
        }
        /** check inventory **/
        if(SITEMINDER_TWO_WAY){
            foreach($min as $keyMin=>$valueMin){
                $cond_inventory = '1=1 ';
                $cond_inventory_rate = '1=1 ';
                //if(date('d/m/Y',$valueMin['arrival_time'])==date('d/m/Y',$valueMin['departure_time'])){
                    
                //}else{
                    //$cond_inventory .= ' and siteminder_room_type_time.in_date>=\''.Date_Time::to_orc_date(date('d/m/Y',$valueMin['arrival_time'])).'\' and siteminder_room_type_time.in_date<\''.Date_Time::to_orc_date(date('d/m/Y',$valueMin['departure_time'])).'\'';
                    //$cond_inventory_rate .= ' and siteminder_rate_avail_time.in_date>=\''.Date_Time::to_orc_date(date('d/m/Y',$valueMin['arrival_time'])).'\' and siteminder_rate_avail_time.in_date<\''.Date_Time::to_orc_date(date('d/m/Y',$valueMin['departure_time'])).'\'';
                //}
                $cond_inventory .= ' and siteminder_room_type_time.in_date=\''.Date_Time::to_orc_date($valueMin['date']).'\'';
                $cond_inventory_rate .= ' and siteminder_rate_avail_time.in_date=\''.Date_Time::to_orc_date($valueMin['date']).'\'';
                $cond_inventory .= ' and siteminder_room_type.room_level_id='.$valueMin['room_level_id'];
                $cond_inventory_rate .= ' and siteminder_room_type.room_level_id='.$valueMin['room_level_id'];
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
                $min[$keyMin]['min'] -= $avail_max;
            }
        }        
        /** end check inventory **/
        //System::debug($min);
        /** check allotment **/
        if(USE_ALLOTMENT){
            foreach($min as $keyMin=>$valueMin){
                $cond_allotment = '1=1 ';
                if(date('d/m/Y',$valueMin['arrival_time'])==date('d/m/Y',$valueMin['departure_time'])){
                    $cond_allotment .= ' and room_allotment_avail_rate.in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$valueMin['arrival_time'])).'\'';
                }else{
                    $cond_allotment .= ' and room_allotment_avail_rate.in_date>=\''.Date_Time::to_orc_date(date('d/m/Y',$valueMin['arrival_time'])).'\' and room_allotment_avail_rate.in_date<\''.Date_Time::to_orc_date(date('d/m/Y',$valueMin['departure_time'])).'\'';
                }
                $cond_allotment .= ' and room_allotment.room_level_id='.$valueMin['room_level_id'];
                $avail_max = DB::fetch('
                                        SELECT
                                            max(room_allotment_avail_rate.availability) as avail
                                        FROM
                                            room_allotment_avail_rate
                                            inner join room_allotment on room_allotment.id=room_allotment_avail_rate.room_allotment_id
                                        WHERE
                                            '.$cond_allotment.'
                                        ','avail');
                //System::debug($avail_max);
                $avail_max = $avail_max?$avail_max:0;
                $min[$keyMin]['min'] -= $avail_max;
            }
        }
        /** end check allotment **/
        return $min;
    }
?>
