<?php
class InvoiceReservationForm extends Form
{
	function InvoiceReservationForm()
	{

	        Form::Form('InvoiceReservationForm');
		$this->link_js('packages/core/includes/js/jquery/jquery.printPage.js');
	}
	function draw()
	{
		if(isset($GLOBALS['night_audit_date'])){
			$today_date = $GLOBALS['night_audit_time'];
		}else{
			$today_date = date('d/m/Y');
		}
		$id=Url::iget('id');
		$checkout_id = '';
		for($i=0;$i<6-strlen($id);$i++)
		{
			$checkout_id .= '0';
		}
		$checkout_id .= $id;
		require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/currency.php';
//--------------------------------- thong tin ve` reservation------------------------------------------
		$sql='select
				reservation_room.*,
				traveller.first_name,
				traveller.last_name,
				traveller.nationality_id,
				traveller.id as traveller_id,
				reservation_type.show_price,
				room.name as room_name,
				reservation_type.name as reservation_type_name,
				customer.name as customer_name
			from
				reservation_room
				inner join reservation on reservation.id=reservation_room.reservation_id
				inner join reservation_type on reservation_type.id=reservation_room.reservation_type_id
				left outer join customer on customer.id=reservation.customer_id
				left outer join room on room.id=reservation_room.room_id
				left outer join reservation_traveller on reservation_traveller.reservation_room_id=reservation_room.id
				left outer join traveller on reservation_room.traveller_id=traveller.id
			where
				reservation_room.id=\''.Url::iget('id').'\'';
		$row = DB::fetch($sql);
		$row['address'] = '';
//--------------------------------------lay exchange------------------------------------------------
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
		$total = 0;
		$total_before_tax = 0;
		$tax_total = 0;
		$service_charge_total = 0;
		$total_items = 0;
		$condition = '1=1';
		$check=false;
//----------------------------------------Tien phong`----------------------------------------------------
		$row['currency_id'] = HOTEL_CURRENCY;
		$row['total_amount'] = 0;
		$row['room_price'] = Url::get('price')?System::display_number(Url::get('price')):System::display_number($row['price']);
		if($row['show_price'] == 0){
			$row['room_price'] = $row['reservation_type_name'];
		}
		if(Url::get('included_deposit')){
			$row['deposit'] = Url::get('deposit')?Url::get('deposit'):$row['deposit'];
		}else{
			$row['deposit'] = 0;
		}
		$row['pay_amount'] = isset($row['pay_amount'])?$row['pay_amount']:0;
		$row['reduce_balance']= Url::get('reduce_balance')?floatval(Url::get('reduce_balance')):$row['reduce_balance'];
		$row['tax_rate']=Url::get('tax_rate')?Url::get('tax_rate'):$row['tax_rate'];
		$row['service_rate']=Url::get('service_rate')?Url::get('service_rate'):$row['service_rate'];
		$row['total_massage_amount'] = 0;
		$row['total_tennis_amount'] = 0;
		$row['total_swimming_pool_amount'] = 0;
		$row['total_karaoke_amount'] = 0;
		////////////////////////////////////////////////////////////////////////////////////////////////
		$payment_type = DB::fetch('SELECT * FROM payment_type WHERE ID = '.$row['payment_type_id']);
		$row['payment_type_name_1'] = $payment_type['name_1'];
		$row['payment_type_name_2'] = $payment_type['name_2'];
		////////////////////////////////////////////////////////////////////////////////////////////////
		$day = array(); // lay danh sach ngay` o khach san
		$n = 1;
		$from = $fromtime;
		$to = $totime;
		$d=$from;
		$bar_charge = 0; //tong tien` su dung dich vu bar
        $ticket_charge = 0; //tong tien` su dung dich vu ve
		$sql = '
			SELECT
				room_status.id,room.name as room_name,
				room_status.in_date, room_status.change_price
			FROM
				room_status
				inner join room on room.id=room_status.room_id
				INNER JOIN reservation_room ON reservation_room.id = room_status.reservation_room_id
			WHERE
				reservation_room.id=\''.$row['id'].'\'
				AND reservation_room.room_id=\''.$row['room_id'].'\'
			ORDER BY
				room.name,room_status.in_date';
		//				'.((USE_NIGHT_AUDIT==1)?'AND (room_status.closed_time > 0 OR reservation_room.arrival_time = reservation_room.departure_time)':'').'
		$room_statuses = DB::fetch_all($sql);
		$reduce_room = array();
		$reduce_date = array();
		$reduce_room_invert = array();
		$reduce_date_invert = array();
		$reduce_price = array();
		$i=0;
		$j=0;
		foreach($room_statuses as $room_status)
		{
			if($row['net_price']==1){
				if($row['service_rate'] == ''){
					$row['service_rate'] = 0;
				}
				$param = (1+($row['tax_rate']*0.01) + ($row['service_rate']*0.01) + (($row['tax_rate']*0.01)*($row['service_rate']*0.01)));
				$room_status['change_price'] = round($room_status['change_price']/$param,2);
			}
			$row['discount_total'] += ($row['reduce_balance']?($row['reduce_balance'])*$room_status['change_price']/100:0);
			$tt = ($row['reduce_balance']?(100 - $row['reduce_balance'])*$room_status['change_price']/100:$room_status['change_price']);
			$date = Date_Time::to_time(Date_Time::convert_orc_date_to_date($room_status['in_date'],'/'));
			if(date('w',$date) == 5 and EXTRA_CHARGE_ON_SATURDAY > 0){
				//$tt += EXTRA_CHARGE_ON_SUNDAY;
			}
			if(date('w',$date) == 6 and EXTRA_CHARGE_ON_SUNDAY > 0){
				//$tt += EXTRA_CHARGE_ON_SATURDAY;
			}
			if(isset($holiday[date('d/m/Y',$date)])){
				//$tt += $holiday[date('d/m/Y',$date)]['charge'];
			}
			if(!isset($reduce_room_invert[$room_status['room_name']]))
			{
				$i++;
				$reduce_room_invert[$room_status['room_name']] = sizeof($reduce_room);
				//$reduce_room[$i] = $room_status['room_name'];
			}
			if(!isset($reduce_date_invert[Date_Time::convert_orc_date_to_date($room_status['in_date'],'/')]))
			{
				$j++;
				$reduce_date_invert[Date_Time::convert_orc_date_to_date($room_status['in_date'],'/')] = sizeof($reduce_date);
				$reduce_date[$j] = Date_Time::convert_orc_date_to_date($room_status['in_date'],'/');
			}
				$reduce_price[$reduce_date_invert[Date_Time::convert_orc_date_to_date($room_status['in_date'],'/')]][$reduce_room_invert[$room_status['room_name']]] = $tt;
				$reduce_price[$reduce_date_invert[Date_Time::convert_orc_date_to_date($room_status['in_date'],'/')]]['net_price'] = $room_status['change_price'];
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
		while($d>=$from and $d<=$to)
		{
			$ni = 3;
			$day[$n]['date'] = date('d/m',$d);
			$room_price = 0;$net_price = 0;
			$row['extra_services'] = DB::fetch_all('
				select
					extra_service_invoice_detail.*
					,((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) + ((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + (((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) + ((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01)) as amount,
					extra_service_invoice.payment_type,
					extra_service_invoice.bill_number,
					extra_service_invoice.code,
					extra_service_invoice.service_rate,
					extra_service_invoice.tax_rate,
					(extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) as total_before_tax,
					TO_CHAR(extra_service_invoice_detail.in_date,\'DD/MM/YYYY\') as date_in
				from
					extra_service_invoice_detail
					inner join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
				where
					extra_service_invoice.reservation_room_id='.$id.'
					AND extra_service_invoice_detail.used = 1
					AND extra_service_invoice.time >= \''.Date_Time::to_time(date('d/m/Y',$d)).'\'
					AND extra_service_invoice.time <= \''.(Date_Time::to_time(date('d/m/Y',$d))+24*3600).'\'
			');//					AND extra_service_invoice.payment_type <> \'ROOM\'
			//AND extra_service_invoice_detail.in_date = \''.Date_Time::to_orc_date(date('d/m/Y',$d)).'\'
            if(Url::get('extra_service_invoice'))
            {
				foreach($row['extra_services'] as $s_key=>$s_value){
					//if($s_value['payment_type'] != 'ROOM'){
						$total += $s_value['amount'];
						$s_service_charge = $s_value['total_before_tax']*$s_value['service_rate']/100;
						$s_tax = ($s_value['total_before_tax']+$s_service_charge)*$s_value['tax_rate']/100;
						$row['extra_services'][$s_key]['amount'] = System::display_number($s_value['total_before_tax']);
						$row['extra_services'][$s_key]['service_charge'] = System::display_number($s_service_charge);
						$row['extra_services'][$s_key]['tax'] = System::display_number($s_tax);
						$row['extra_services'][$s_key]['total'] = System::display_number($s_value['amount']);
						$total_before_tax+= $s_value['total_before_tax'];
						$service_charge_total+= $s_service_charge;
						$tax_total+= $s_tax;
					/*}else{
						if($payment_type['def_code']=='DEBIT'){
							$row['extra_services'][$s_key]['amount'] = 'Company';
						}else{
							$row['extra_services'][$s_key]['amount'] = 'Room';
						}
					}*/
				}
				$day[$n]['extra_services'] = $row['extra_services'];
			}
            else
            {
				$day[$n]['extra_services'] = 0;
			}
			if(Url::get('room_invoice')){
				if(($d<$to) or ($from==$to))
				{
					if(isset($reduce_date_invert[date('d/m/Y',$d)]) and isset($reduce_price[$reduce_date_invert[date('d/m/Y',$d)]][0]))
					{
						$room_price += $reduce_price[$reduce_date_invert[date('d/m/Y',$d)]][0];
						$net_price += $reduce_price[$reduce_date_invert[date('d/m/Y',$d)]]['net_price'];
					}
					if($room_price>0){
						$room_price_service_charge = 0;
						$room_price_tax = 0;
						if($row['show_price'] == 0){
							$room_price_label = $row['reservation_type_name'];
						}else{
							if(isset($holiday[date('d/m/Y',$d)])){
								//$room_price+=$holiday[date('d/m/Y',$d)]['charge'];
							}
							$room_price_label = System::display_number($room_price);
							$room_price_service_charge = $room_price*$row['service_rate']/100;
							$room_price_tax = ($room_price+$room_price_service_charge)*$row['tax_rate']/100;
						}
						$day[$n]['room_price'] = $room_price_label;
						$day[$n]['room_price_service_charge'] = System::display_number($room_price_service_charge);
						$day[$n]['room_price_tax'] = System::display_number($room_price_tax);
						$day[$n]['room_price_total'] = $row['show_price']?System::display_number($room_price_tax+$room_price_service_charge+$room_price):$room_price_label;
						$total_before_tax+= $row['show_price']?$room_price:0;
						$service_charge_total+=$room_price_service_charge;
						$tax_total+=$room_price_tax;
						//$day[$n]['room_reduce_balance']=(str_replace(',','',$room_price)*($row['reduce_balance']/100));
						//$day[$n]['room_service_rate']=(str_replace(',','',$room_price-$day[$n]['room_reduce_balance'])*($row['service_rate']/100));
						if($row['show_price'] == 1){
							$total_room_price+=$room_price;
						}
					}
				}
			}
			if($total_room_price==0 and Url::get('total_amount')){
				//$total_room_price = Url::get('total_amount');
			}
			$row['total_room_price_before_discount']=System::display_number($total_room_price);
//----------------------------------------/Tien phong-----------------------------------------------------
//----------------------------------------Tien dich vu----------------------------------------------------
			if(URL::get('hk_invoice')){
				$sql_l='
					SELECT
						housekeeping_invoice.*,
						housekeeping_invoice.fee_rate as service_rate
					FROM
						housekeeping_invoice
					WHERE
						housekeeping_invoice.reservation_room_id='.$id.'
						'.(($payment_type['def_code']=='DEBIT')?'AND housekeeping_invoice.group_payment <> 1':'').'
						AND housekeeping_invoice.minibar_id=\''.$row['room_id'].'\'
						AND housekeeping_invoice.type=\'LAUNDRY\'
						AND (housekeeping_invoice.time>='.$d.' AND housekeeping_invoice.time<'.($d+24*3600).')
				';// chu y giat la va minibar khac nhau o minibar_id
				$sql_m='
					SELECT
						housekeeping_invoice.*,
						housekeeping_invoice.fee_rate as service_rate
					FROM
						housekeeping_invoice
						inner join minibar on housekeeping_invoice.minibar_id = minibar.id
					WHERE
						housekeeping_invoice.reservation_room_id='.$id.'
						'.(($payment_type['def_code']=='DEBIT')?'AND housekeeping_invoice.group_payment <> 1':'').'
						AND minibar.room_id=\''.$row['room_id'].'\'
						AND type=\'MINIBAR\'
						AND (housekeeping_invoice.time>='.$d.' and housekeeping_invoice.time<'.($d+24*3600).')
				';
				$sql_compensated_item='
					SELECT
						housekeeping_invoice.*,
						housekeeping_invoice.fee_rate as service_rate
					FROM
						housekeeping_invoice
					WHERE
						housekeeping_invoice.reservation_room_id='.$id.'
						'.(($payment_type['def_code']=='DEBIT')?'AND housekeeping_invoice.group_payment <> 1':'').'
						AND housekeeping_invoice.minibar_id =\''.$row['room_id'].'\'
						AND housekeeping_invoice.type=\'EQUIP\'
						AND (housekeeping_invoice.time>='.$d.' and housekeeping_invoice.time<'.($d+24*3600).')
				';//minibar_id o day khong phai la minibar_id cua bang minibar ma la id cua bang room
			}
	//-----------------------------------------minibar------------------------------------------------------------
			if(URL::get('hk_invoice')){
				$minibar_charge=0;
				$minibar_tax_rate=0;
				$minibar_express_rate=0;
				$minibar_service_charge = 0;
				$minibar_discount=0;
				$minibar_total_before_tax=0;
				$minibar_total_tax=0;
				if($minibars = DB::fetch_all($sql_m))
				{
					foreach($minibars as $k=>$minibar)
					{
						$minibars[$k]['row_number'] = ++$row_number;
						$m_service_charge = $minibar['total_before_tax']*$minibar['service_rate']/100;
						$m_tax = ($minibar['total_before_tax']+$m_service_charge)*$minibar['tax_rate']/100;
						$minibars[$k]['total_before_tax'] = System::display_number($minibar['total_before_tax']);
						$minibars[$k]['service_charge'] = System::display_number($m_service_charge);
						$minibars[$k]['tax'] = System::display_number($m_tax);
						$minibars[$k]['total'] = System::display_number($minibar['total']);
						$minibar_charge+=$minibar['total'];
						$minibar_service_charge+=$m_service_charge;
						$minibar_tax_rate+=$minibar['tax_rate'];
						$minibar_express_rate+=$minibar['express_rate'];
						$minibar_discount+=$minibar['discount'];
						$minibar_total_before_tax+=$minibar['total_before_tax'];
						$minibar_total_tax+=($minibar_total_before_tax)*($minibar_tax_rate/100);
					}
				}
				$total = $total + $minibar_charge;
				$total_before_tax+= $minibar_total_before_tax;
				$service_charge_total+= $minibar_service_charge;
				$tax_total+= $minibar_total_tax;
				//$row['discount_total']+=$minibar_discount;
				$day[$n]['minibars']= $minibars;//System::display_number($minibar_charge);
				$day[$n]['minibar_tax_rate']=System::display_number($minibar_total_tax);
	//--------------------------------------------laundry--------------------------------------------------------
				DB::query($sql_l);
				$laundry_charge=0;
				$laundry_service_charge = 0;
				$laundry_tax_rate=0;
				$laundry_express_rate=0;
				$laundry_discount=0;
				$laundry_total_before_tax=0;
				$laundry_total_tax=0;
				$day[$n]['laundrys'] = array();
				if($laundrys = DB::fetch_all())
				{
					foreach($laundrys as $k=>$laundry)
					{
						$laundrys[$k]['row_number'] = ++$row_number;
						$l_service_charge = $laundry['total_before_tax']*$laundry['service_rate']/100;
						$l_tax = ($laundry['total_before_tax']+$l_service_charge)*$laundry['tax_rate']/100;
						$laundrys[$k]['total_before_tax'] = System::display_number($laundry['total_before_tax']);
						$laundrys[$k]['service_charge'] = System::display_number($l_service_charge);
						$laundrys[$k]['tax'] = System::display_number($l_tax);
						$laundrys[$k]['total'] = System::display_number($laundry['total']);
						$laundry_charge += $laundry['total'];
						$laundry_service_charge+=$l_service_charge;
						$laundry_tax_rate += $laundry['tax_rate'];
						$laundry_express_rate += $laundry['express_rate'];
						$laundry_discount += $laundry['discount'];
						$laundry_total_before_tax += $laundry['total_before_tax'];
						$laundry_total_tax += ($laundry_total_before_tax)*($laundry_tax_rate/100);
					}
					$day[$n]['laundrys'] = $laundrys;
				}
				$total += $laundry_charge;
				$total_before_tax+= $laundry_total_before_tax;
				$service_charge_total+= $laundry_service_charge;
				$tax_total+= $laundry_total_tax;
				//$row['discount_total']+=$laundry_discount
				$day[$n]['laundrys']=$laundrys;
				$day[$n]['laundry_tax_rate']=System::display_number($laundry_total_tax);
//--------------------------------------------/laundry--------------------------------------------------------
				DB::query($sql_compensated_item);
				$compensated_item_charge=0;
				$compensated_item_tax_rate=0;
				$compensated_item_express_rate=0;
				$compensated_item_discount=0;
				$compensated_item_total_before_tax=0;
				$compensated_item_total_tax=0;
				$compensated_item_service_charge = 0;
				if($compensated_items = DB::fetch_all($sql_compensated_item))
				{
					foreach($compensated_items as $k=>$compensated_item)
					{
						$c_service_charge = $compensated_item['total_before_tax']*$compensated_item['service_rate']/100;
						$c_tax = ($compensated_item['total_before_tax']+$c_service_charge)*$compensated_item['tax_rate']/100;
						$compensated_items[$k]['row_number'] = ++$row_number;
						$compensated_items[$k]['total_before_tax'] = System::display_number($compensated_item['total_before_tax']);
						$compensated_items[$k]['service_charge'] = System::display_number($c_service_charge);
						$compensated_items[$k]['tax'] = System::display_number($c_tax);
						$compensated_items[$k]['total'] = System::display_number($compensated_item['total']);
						$compensated_item_charge += $compensated_item['total'];//ROUND_PRECISION
						$compensated_item_tax_rate +=$compensated_item['tax_rate'];
						$compensated_item_service_charge+=$c_service_charge;
						$compensated_item_express_rate +=$compensated_item['express_rate'];
						$compensated_item_discount += $compensated_item['discount'];
						$compensated_item_total_before_tax += $compensated_item['total_before_tax'];
						$compensated_item_total_tax += ($compensated_item_total_before_tax)*($compensated_item_tax_rate/100);
					}
					$day[$n]['compensated_items'] = $compensated_items;
				}
				$total += $compensated_item_charge;
				$tax_total += $compensated_item_total_tax;
				$service_charge_total+= $compensated_item_service_charge;
				$tax_total+= $laundry_total_tax;
//-----------------------------------------------------------------------------------------------------------
			}
			if(Url::get('phone_invoice') and $row['room_id']) //
			{
				//----------------------------------------Tien dien thoai-------------------------------------------------
				$sql_p = '
					SELECT
						telephone_report_daily.bill_id as id,
						telephone_report_daily.price as total,
						telephone_report_daily.total_before_tax,
						telephone_number.id as room_id,
						0 as service_rate
					FROM
						telephone_report_daily
						inner join telephone_number on telephone_number.phone_number = telephone_report_daily.phone_number_id
					WHERE
						telephone_report_daily.hdate >= '.$row['time_in'].'
						and telephone_report_daily.hdate>='.$d.' and telephone_report_daily.hdate < '.($d+24*3600).'
						AND telephone_number.room_id = '.$row['room_id'].'
				';
				$phones = DB::fetch_all($sql_p);
				$total_phone = 0;
				$total_tax_phone = 0;
				$total_phone_before_tax = 0;
				$day[$n]['phones'] = array();
				if(!empty($phones)){
					foreach($phones as $p =>$phone){
						$row['row_number'] = ++$row_number;
						if($row['exchange_currency_id']=='VND'){
							$total_phone += round($phone['total']/$row['exchange_rate'],2);
						}else{
							$total_phone += $phone['total'];
							$total_phone_before_tax += $phone['total_before_tax'];
						}
						if($phone['total_before_tax'] == $phone['total']){
							$phones[$p]['tax_rate'] = 0;
							$phones[$p]['total_phone_tax'] = 0;
						}else{
							$phones[$p]['tax_rate'] = (($phone['total'] - $phone['total_before_tax']) / $phone['total_before_tax']) *100;
							$phones[$p]['total_phone_tax'] = $phone['total'] - $phone['total_before_tax'];
							$total_tax_phone += $phones[$p]['total_phone_tax'];
						}
					}
					$day[$n]['phones'] = $phones;
				}
				//----------------------------------------/Tien dien thoai-------------------------------------------------
				$total += $total_phone;
				$tax_total += $total_tax_phone;
			}
            if(URL::get('ticket_invoice'))
            {
                //-----------------------ticket--------------------------------------------------
                $sql = '
					select 
						ticket_reservation.id,
                        ticket_reservation.total,
                        ticket_reservation.time,
                        ticket_reservation.total_before_tax,
						(\''.Portal::language('ticket').'\' || \'_\' || ticket_reservation.id) AS ticket_name ,
						ticket_reservation.deposit as ticket_deposit
					from 
						ticket_reservation
					where 
						reservation_room_id=\''.Url::iget('id').'\'
                        and (ticket_reservation.time>='.$d.' and ticket_reservation.time<='.($d+24*3600).')
				';//and (bar_reservation.ARRIVAL_TIME>='.$d.' and bar_reservation.ARRIVAL_TIME<='.($d+24*3600).') 
				//System::debug($sql);
                if($bar_resrs = DB::fetch_all($sql))
				{
					foreach($bar_resrs as $bk=>$reser)
					{
						$reser['total'] = $reser['total'];
						$bar_resrs[$bk]['ticket_reservation_id'] = $reser['id'];
						$bar_resrs[$bk]['service_charge'] = ($reser['total_before_tax']*0);
						$bar_resrs[$bk]['ticket_chrg_tax'] = ($reser['total_before_tax']+$bar_resrs[$bk]['service_charge'])*10/100;
						//$bar_resrs[$bk]['total_before_tax']	= $bar_resrs[$bk]['total_before_tax']-$bar_resrs[$bk]['service_charge'];
						$bar_resrs[$bk]['ticket_chrg'] = System::display_number($reser['total']);
						$bar_chrg = 0;
						{
							$ticket_charge += $reser['total'];
						}
						$ni+=2;
					}
					$day[$n]['ticket_resers'] = $bar_resrs;
					$day[$n]['ticket_name'] = 'Ticket';
					$day[$n]['ticket_charge'] = 1;
				}
				else
				{
					$day[$n]['ticket_charge'] = 0;
				}
                //-----------------------/ticket------------------------------------------------
            }
			if(URL::get('bar_invoice'))
            {
				//su dung dich vu bar theo ngay
				$sql = '
					select
						bar_reservation.id, bar_reservation.payment_result,
						bar_reservation.time_out, bar_reservation.total, bar_reservation.prepaid,
						bar_reservation.total_before_tax, bar_reservation.total_before_tax, bar_reservation.tax_rate,
						CONCAT(\''.(Portal::language('restaurant')).'\',CONCAT(\' #\',bar_reservation.id)) AS bar_name
					from
						bar_reservation
					where
						reservation_room_id=\''.Url::iget('id').'\'
						and (bar_reservation.ARRIVAL_TIME>='.$d.' and bar_reservation.ARRIVAL_TIME<='.($d+24*3600).')
						and (bar_reservation.status=\'CHECKOUT\' or bar_reservation.status=\'CHECKIN\')
				';
				if($bar_resrs = DB::fetch_all($sql))
				{
					foreach($bar_resrs as $bk=>$reser)
					{
						$reser['total'] = $reser['total'];
						$bar_resrs[$bk]['bar_reservation_id'] = $reser['id'];
						$bar_resrs[$bk]['service_charge'] = ($reser['total_before_tax']*0.05);
						$bar_resrs[$bk]['bar_chrg_tax'] = ($reser['total_before_tax']+$bar_resrs[$bk]['service_charge'])*$reser['tax_rate']/100;
						//$bar_resrs[$bk]['total_before_tax']	= $bar_resrs[$bk]['total_before_tax']-$bar_resrs[$bk]['service_charge'];
						$bar_resrs[$bk]['bar_chrg'] = System::display_number($reser['total']);
						$bar_chrg = 0;
						{
							$bar_charge += $reser['total'];
						}
						$ni+=2;
					}
					$day[$n]['bar_resers'] = $bar_resrs;
					$day[$n]['bar_name'] = $reser['bar_name'];
					$day[$n]['bar_charge'] = 1;
				}
				else
				{
					$day[$n]['bar_charge'] = 0;
				}
				//$row['discount_total']=System::display_number($row['discount_total']);
			}
			$day[$n]['extra'] = '';
			if(date('w',$d) == 5 and EXTRA_CHARGE_ON_SATURDAY > 0){
				$day[$n]['extra'] .= ' + '.System::display_number(EXTRA_CHARGE_ON_SATURDAY).' '.HOTEL_CURRENCY.' '.Portal::language('friday');
			}
			$day[$n]['saturday_charge'] = '';
			if(date('w',$d) == 6 and EXTRA_CHARGE_ON_SUNDAY > 0){
				$day[$n]['extra'] .= ' + '.System::display_number(EXTRA_CHARGE_ON_SUNDAY).' '.HOTEL_CURRENCY.' '.Portal::language('saturday');
			}
			if(isset($holiday[date('d/m/Y',$d)])){
				$day[$n]['extra'] .= ' + '.System::display_number($holiday[date('d/m/Y',$d)]['charge']).' '.HOTEL_CURRENCY.' '.$holiday[date('d/m/Y',$d)]['name'];
			}
			$total_items+= $ni;
			$day[$n]['num_items'] = $ni;
			$n++;
			$d=$d+(3600*24);
		}
//-------------------------------------------------Other services----------------------------------------
		$row['services'] = DB::fetch_all('
			select
				service_id as id,reservation_room_service.amount,reservation_room_id,service.name,service.type
			from
				reservation_room_service
				inner join service on service.id = service_id
			where
				reservation_room_id='.$id.'
		');
		if(Url::get('other_invoice')){
			foreach($row['services'] as $s_key=>$s_value){
				if($s_value['type']=='SERVICE'){
					$row['services'][$s_key]['row_number'] = ++$row_number;
					$total += $s_value['amount'];
				}
			}
		}
		if(Url::get('room_invoice')){
			foreach($row['services'] as $s_key=>$s_value){
				if($s_value['type']=='ROOM'){
					$row['services'][$s_key]['row_number'] = ++$row_number;
					$total += $s_value['amount'];
				}
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
					massage_reservation_room.hotel_reservation_room_id='.$id.' and (massage_reservation_room.TIME>='.$d.' and massage_reservation_room.TIME<='.($d+24*3600).')
						
				GROUP BY
					massage_reservation_room.hotel_reservation_room_id
			';
			if($row['total_massage_amount'] = DB::fetch($sql_massage,'total_amount') and HAVE_MASSAGE)
            {
				$row['row_number'] = ++$row_number;
				$total += $row['total_massage_amount'];
			}
            else
            {
				$row['total_massage_amount'] = 0;
			}
		}
        $total_before_tax += $row['total_massage_amount'];
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
				$row['row_number'] = ++$row_number;
				$total += $row['total_tennis_amount'];
			}else{
				$row['total_tennis_amount'] = 0;
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
				$row['row_number'] = ++$row_number;
				$total += $row['total_swimming_pool_amount'];
			}else{
				$row['total_swimming_pool_amount'] = 0;
			}
		}
        $total_before_tax += $row['total_swimming_pool_amount'];
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
				$row['row_number'] = ++$row_number;
				$total += $row['total_karaoke_amount'];
			}else{
				$row['total_karaoke_amount'] = 0;
			}
		}
        $total_before_tax += $row['total_karaoke_amount'];
		$total+=$bar_charge;
        $total+=$ticket_charge;
		$total_phone = 0;
		$total_phone_before_tax = 0;
		$row['phones'] = $phones;
		//$total = $total - System::calculate_number($row['deposit']);
		//$total = $total - System::calculate_number($row['pay_amount']);
		if(Url::get('room_invoice')){
			$total_room_price_ = $total_room_price;
			if(!$row['foc_all'] && $row['foc']!=''){
				$total_room_price_ = 0;
				$total_room_price = 0;
				$row['reduce_amount'] = 0;
			}
			if($row['discount_after_tax']==1){// TH giảm giá sau thuế
				$row['service_rate_total']=System::display_number($total_room_price_*($row['service_rate']/100));
				$total_room_price += str_replace(',','',$row['service_rate_total']);
				$row['room_tax_total']=System::display_number(($total_room_price)*($row['tax_rate']/100));
				$total_room_price += str_replace(',','',$row['room_tax_total']);
				$row['discount_total']=System::display_number($total_room_price*($row['reduce_balance']/100));
				$total_room_price -= str_replace(',','',$row['discount_total']);
				$total_room_price -= $row['reduce_amount'];
			}else{// TH giảm giá trước thuế
				//$row['discount_total']=System::display_number($total_room_price*($row['reduce_balance']/100));
				//$total_room_price -= str_replace(',','',$row['discount_total']);
				$total_room_price = $total_room_price - $row['reduce_amount'];
				$row['service_rate_total']=System::display_number($total_room_price*($row['service_rate']/100));
				$total_room_price += str_replace(',','',$row['service_rate_total']);
				$row['room_tax_total']=System::display_number(($total_room_price)*($row['tax_rate']/100));
				$total_room_price += str_replace(',','',$row['room_tax_total']);
			}
			//$total += System::calculate_number($row['room_tax_total']);
			//$tax_total += System::calculate_number($row['room_tax_total']);
			$total += $total_room_price;
			$row['reduce_amount'] = System::display_number($row['reduce_amount']);
			$row['total_room_price']=System::display_number($total_room_price);
		}
		$row['total_phone'] = System::display_number($total_phone);
		$row['total_phone_before_tax'] = System::display_number($total_phone_before_tax);
		$row['total_phone_tax'] = System::display_number($total_phone-$total_phone_before_tax);
		$row['total_items'] = $total_items;
		//thong tin cuoi cung cua check out
		require_once 'packages/core/includes/utils/lib/report.php';
		$report = new Report;
		$report->items = $day;
        if(User::is_admin())
        {
            //System::debug($day);
        }
		$curr='';
		$total_by_text = '';
		$current_total = $total;
		$row['current_total'] = System::display_number($current_total);
		$related_total = 0;
		$related_rooms_arr = array();
		if(Url::get('included_related_total')){
			if($related_rooms = DB::fetch_all('select reservation_room.id,room.name from reservation_room inner join room on room.id = reservation_room.room_id where related_rr_id = '.$row['id'].'')){
				require_once 'packages/hotel/packages/reception/modules/includes/get_reservation.php';
				foreach($related_rooms as $k=>$v){
					$arr = get_reservation($row['reservation_id'],$v['id']);
					$related_rooms_arr[$v['id']]['id'] = $v['id'];
					$related_rooms_arr[$v['id']]['name'] = $v['name'];
					$related_total += $arr['total'];
					$related_rooms_arr[$v['id']]['total_amount'] = System::display_number($arr['total']);
					$related_rooms_arr[$v['id']]['row_number'] = ++$row_number;
				}
			}
		}
		$row['related_rooms_arr'] = $related_rooms_arr;
		$total += $related_total;
		$row['total_bank_fee'] = 0;
		$bank_fee_percen = BANK_FEE_PERCEN; // % phi giao dich
		if($row['payment_type_id'] == 3 or Url::get('def_code')=='CREDIT_CARD'){ // thanh quan qua the, chuyen khoan
			$row['total_bank_fee'] = round($total*$bank_fee_percen/100,ROUND_PRECISION);
			$row['total_with_bank_fee'] = $total + $row['total_bank_fee'];
		}else{
			$row['total_with_bank_fee'] = $total;
		}
		$row['bank_fee_percen'] = $bank_fee_percen;
		////////////////////////////////////////////////////////////////////////////////////////////////
		if($row['total_with_bank_fee'] == $total){
			$total_ = $total - $row['deposit'] - $row['pay_amount'];
		}else{
			$row_number = $row_number + 2;
			$total_ = $row['total_with_bank_fee'];
		}
		if($row['exchange_currency_id']=='USD'){
			$row['total_in_other_currency'] = round($total_/$row['exchange_rate'],ROUND_PRECISION);
		}else{
			$row['total_in_other_currency'] = $total_*$row['exchange_rate'];
		}
		////////////////////////////////////////////////////////////////////////////////////////////////
		$total = System::display_number($total);
		$tax_total = System::display_number($tax_total);
		$service_charge_total = System::display_number($service_charge_total);
		$total_before_tax = System::display_number($total_before_tax);
		////////////////////////////////////////////////////////////////////////////////////////////////
		$row['total_row_number'] = $row_number + 5;
		////////////////////////////////////////////////////////////////////////////////////////////////
		$this->print_all_pages($report, $room_result, $row, $curr, $checkout_id, $tax_total, $total, $total_by_text,$service_charge_total,$total_before_tax);
	}
	function print_all_pages(&$report, $room_result, $row, $curr, $checkout_id, $tax_total, $total, $total_by_text,$service_charge_total,$total_before_tax)
	{
		$ipp = 2200;
		$count = 0;
		$page = 1;
		$pages = array();
		foreach($report->items as $key=>$item)
		{
			$count +=$item['num_items'];
			if($count>=$ipp)
			{
				$count = 0;
				$page++;
			}
			$pages[$page][$key] = $item;
		}
		$i = 0;
		foreach($pages as $page)
		{
			$this->print_page($page, $ipp-5, $report, $i, sizeof($pages), $room_result, $row, $curr, $checkout_id, $tax_total, $total, $total_by_text,$service_charge_total,$total_before_tax);
		}
	}
	function print_page($items, $ipp, &$report, $last_page, $num_page, $room_result, $row, $curr, $checkout_id, $tax_total, $total, $total_by_text,$service_charge_total,$total_before_tax)
	{
		$description = '';
		if(Url::get('room_invoice') and Url::get('hk_invoice') and Url::get('bar_invoice') and Url::get('other_invoice') and Url::get('phone_invoice')){
			$description = '';
		}else
        {
			if(Url::get('room_invoice'))
            {
				$description = Portal::language('room_invoice').'';
			}
			if(Url::get('hk_invoice'))
            {
				$description .= ($description?' + ':'').Portal::language('housekeeping_invoice').'';
			}
			if(Url::get('bar_invoice'))
            {
				$description .= ($description?' + ':'').Portal::language('restaurant_invoice').'';
			}
            if(Url::get('ticket_invoice'))
            {
				$description .= ($description?' + ':'').Portal::language('ticket_invoice').'';
			}
			if(Url::get('other_invoice'))
            {
				$description .= ($description?' + ':'').Portal::language('other_invoice').'';
			}
			if(Url::get('phone_invoice'))
            {
				$description .= ($description?' + ':'').Portal::language('phone_invoice').'';
			}
		}
		$this->parse_layout('header',$room_result+$row+array(
			'available'=>0,
			'exchange_rate1'=>$row['exchange_rate'],
			'checkout_id'=>$checkout_id,
			'description'=>$description
		));
		$sql = '
			SELECT
				currency.*,pay_by_currency.amount,pay_by_currency.exchange_rate
			FROM
				currency
				INNER JOIN pay_by_currency ON pay_by_currency.currency_id = currency.id AND pay_by_currency.type=\'RESERVATION\'
			WHERE
				pay_by_currency.bill_id = '.Url::iget('id').' AND currency.allow_payment = 1 AND pay_by_currency.amount>0
		';
		$currencies=DB::fetch_all($sql);
		$currencies[1]['id'] = 1;
		$currencies[1]['name'] = 'USD';
		$currencies[1]['amount'] = System::calculate_number($total);
		foreach($currencies as $key=>$value){
			$currencies[$key]['name'] = ($key=='USD')?'Credit card':$value['name'];
			if(isset($value['exchange_rate'])){
				$currencies[1]['amount'] -= $value['amount']/($value['exchange_rate']?$value['exchange_rate']:1);
			}
		}
		if($currencies[1]['amount']<=0){
			//unset($currencies[1]);
		}
		if(Url::get(md5('print')) == md5('1')){
			$row['preview'] = '';
		}else{
			$row['preview'] = Portal::language('preview_order').' '.Portal::language('not_for_payment');
		}
        //System::debug($row);
		$this->parse_layout('total_invoice',$row+array(
			'ipp'=>$ipp,
			'num_page'=>$num_page,
			'current_page'=>$last_page,
			'live_day'=>$items,
			'last_page'=>$last_page,
			'total_before_tax'=>$total_before_tax,
			'service_charge_total'=>$service_charge_total,
			'tax_total'=>$tax_total,
			'curr'=>$curr,
			'sum_total'=>$total,
			'sum_total_by_text'=>$total_by_text,
			'currencies'=>$currencies
		));
		$this->parse_layout('footer',array(
			'num_page'=>$num_page,
			'current_page'=>$last_page,
		));
	}
}
?>