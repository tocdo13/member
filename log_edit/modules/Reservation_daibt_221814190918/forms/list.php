<?php
class ListReservationForm extends Form
{

	function ListReservationForm(){

		Form::Form('ListReservationForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
//echo (Portal::template('hotel').'/css/style.css');
		$this->link_css('skins/default/datetime.css');
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        //oanh add
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_css('packages/hotel/skins/default/css/w3c.css');
        $this->link_css('packages/hotel/skins/default/css/font-awesome-4.7.0/css/font-awesome.min.css');
	}
	function draw()
	{
		if(URL::check(array('action'=>'update_all_reservation'))and USER::can_edit(false,ANY_CATEGORY))
		{
			require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
			$reservations = DB::fetch_all('select id,room_id,reservation_id,status,arrival_time,departure_time from reservation_room');
			foreach($reservations as $id=>$reservation)
			{
				reservation_update_room_map($this, $id, $reservation,false);
			}
		}
		require_once 'packages/core/includes/utils/vn_code.php';
		if(URL::get('customer_name') or URL::get('nationality_id') or URL::get('company_name') or URL::get('traveller_name') or URL::get('booking_resource'))
        {
			require_once 'packages/hotel/includes/php/search.php';
		}
		require_once 'packages/core/includes/utils/time_select.php';
        
        
        $from_date = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
        
        $to_date = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        
        if(!Url::get('portal_id'))
        {
			$_REQUEST['portal_id'] = PORTAL_ID;
		}
		if(Url::get('portal_id')=='ALL')
        {
			$cond = '1=1';
		}else
        {
			$cond = 'reservation.portal_id = \''.Url::get('portal_id').'\'';
		}
		$cond .= ''.(URL::get('customer_name')?'
					AND (LOWER(FN_CONVERT_TO_VN(reservation_room.customer_name)) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(URL::get('customer_name'),'utf-8')).'%\')
				':'')      
				.(URL::get('company_name')?' AND (LOWER(FN_CONVERT_TO_VN(customer.name)) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(URL::get('company_name'),'utf-8')).'%\')':'')
				.(URL::get('traveller_name')?' AND (CONCAT(LOWER(FN_CONVERT_TO_VN(traveller.first_name)),CONCAT(\' \',LOWER(FN_CONVERT_TO_VN(traveller.last_name)))) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(URL::sget('traveller_name'),'utf-8')).'%\')':'')
				.(URL::get('note')?' AND LOWER(reservation.note) LIKE \'%'.strtolower(URL::get('note')).'%\'':'')
				.(URL::get('nationality_id')?' AND country.code_1 = \''.URL::get('nationality_id').'\'':'')
				.(URL::get('room_id')?' and room.name = \''.URL::get('room_id').'\'':'')
				.(URL::get('code')?' and reservation_room.reservation_id = '.URL::iget('code').'':'')
				.(URL::get('price')?' and reservation_room.price '.URL::sget('price_operator').' '.System::calculate_number(Url::sget('price')).'':'')
		        .(URL::get('booker')?'
					AND (LOWER(FN_CONVERT_TO_VN(reservation.booker)) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(URL::get('booker'),'utf-8')).'%\')
				':'')
                .(URL::get('phone_booker')?' and reservation.phone_booker LIKE \'%'.URL::get('phone_booker').'%\'':'')   
        ;
        
        $start_date = 0;
        $end_date = Date_Time::to_time("01/01/2030");
        if(Url::get('start_date'))
        {
            $start_date = Date_Time::to_time(Url::get('start_date'));
            $from_date = Url::get('start_date');
        }
        if(Url::get('end_date'))
        {
            $end_date = Date_Time::to_time(Url::get('end_date'))+86400-1;
            $to_date = Url::get('end_date');
        }
        
        $cond.=" AND reservation_room.time_out>=".$start_date." AND reservation_room.time_in<=".$end_date;
          //System::debug($_REQUEST);  
		$this->map['title'] = Url::get('status')=='BOOKED';
		if(Url::get('status')=='CHECKIN')
        {
			$cond .= ' AND reservation_room.status=\'CHECKIN\'';
			if(Url::get('occupied')==1)
            {
				$this->map['title'] = Portal::language('occupied_list');
                if(Url::get('from_date') AND Url::get('to_date')){
    				$cond .= ' AND ((reservation_room.arrival_time<\''.Date_Time::to_orc_date($from_date).'\' AND reservation_room.departure_time>=\''.Date_Time::to_orc_date($to_date).'\') and reservation_room.arrival_time < reservation_room.departure_time)';
			     }
            }
            else
            {
				$this->map['title'] = Portal::language('checkin_list');
                if(Url::get('from_date') AND Url::get('to_date'))
                {
				    $cond .= ' AND reservation_room.arrival_time>=\''.Date_Time::to_orc_date($from_date).'\' AND reservation_room.arrival_time<=\''.Date_Time::to_orc_date($to_date).'\' ';
                    //$cond .= ' AND (reservation_room.arrival_time >= \''.Date_Time::to_orc_date($from_date).'\') AND (reservation_room.arrival_time <= \''.Date_Time::to_orc_date($to_date).'\')';
                }
            }
		}
        elseif(Url::get('status')=='CHECKOUT')
        {
		      //echo 3;
			$this->map['title'] = Portal::language('checkout_list');
			$cond .= ' AND reservation_room.status=\'CHECKOUT\'';
            if(Url::get('from_date') AND Url::get('to_date'))
            {
			     $cond .= ' AND reservation_room.departure_time>=\''.Date_Time::to_orc_date($from_date).'\' AND reservation_room.departure_time<=\''.Date_Time::to_orc_date($to_date).'\'';
                //$cond .= ' AND (reservation_room.arrival_time >= \''.Date_Time::to_orc_date($from_date).'\') AND (reservation_room.arrival_time <= \''.Date_Time::to_orc_date($to_date).'\')';
            }
		}
        elseif(Url::get('status')=='BOOKED')
        {
			$cond .= ' AND (reservation_room.status = \'BOOKED\')';
            if(Url::get('from_date') AND Url::get('to_date'))
            {
    			if(Url::get('resolve'))
                {
    				$this->map['title'] = Portal::language('resolve_list');
    				$cond .= ' AND (
    								(reservation_room.arrival_time <= \''.Date_Time::to_orc_date($from_date).'\' AND reservation_room.departure_time >= \''.Date_Time::to_orc_date($to_date).'\')
    								OR (reservation_room.arrival_time = reservation_room.departure_time AND reservation_room.arrival_time <= \''.Date_Time::to_orc_date($from_date).'\' AND reservation_room.departure_time >= \''.Date_Time::to_orc_date($to_date).'\')
    							)';
    			}
                elseif(Url::get('no_checkin'))
                {
    				$this->map['title'] = Portal::language('not_check_in_yet_before_17h30_list');
    				$cond .= ' AND (reservation_room.arrival_time >= \''.Date_Time::to_orc_date($from_date).'\') AND (reservation_room.arrival_time <= \''.Date_Time::to_orc_date($to_date).'\')';
    			}
                else
                {
    				$this->map['title'] = Portal::language('booked_list');
    				$cond .= ' AND (reservation_room.arrival_time >= \''.Date_Time::to_orc_date($from_date).'\') and (reservation_room.arrival_time <= \''.Date_Time::to_orc_date($to_date).'\')';
    			}
            }
		}
        elseif(Url::get('status')=='CANCEL')
        {
			$this->map['title'] = Portal::language('cancel_list');
			$cond .= ' AND (reservation_room.status = \'CANCEL\')';
            if(Url::get('from_date') AND Url::get('to_date'))
            {
			     $cond .= ' AND (reservation_room.time_cancel >= '.Date_Time::to_time($from_date).') AND (reservation_room.time_cancel <= '.(Date_Time::to_time($to_date)+(96399)).')';
		    }  
        }
        else
        {
            if(Url::get('from_date') AND Url::get('to_date'))
            {
			     $cond .= ' AND (reservation_room.arrival_time >= \''.Date_Time::to_orc_date($from_date).'\') AND (reservation_room.arrival_time <= \''.Date_Time::to_orc_date($to_date).'\')';
		    }
        }
		if(Url::get('booking_code'))
        {
			$cond = ' LOWER(FN_CONVERT_TO_VN(reservation.booking_code)) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(URL::get('booking_code'),'utf-8')).'%\'';
		}
        $_REQUEST['from_date'] = $from_date;
		$_REQUEST['to_date'] = $to_date;
        
        //echo $cond;
        $cond_2 = '';
        if(Url::get('room_level'))
            $cond_2 .= ' AND room.room_level_id = \''.Url::get('room_level').'\'';
		$item_per_page = 500;
		DB::query('
			select count(*) as acount
			from
				reservation_room
				inner join reservation on reservation.id = reservation_room.reservation_id
				'.((URL::get('customer_name') or URL::get('nationality_id') or URL::get('company_name') or URL::get('traveller_name') or URL::get('booking_resource'))?'
				left outer join tour on reservation.tour_id = tour.id
				left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
				left outer join traveller on reservation_traveller.traveller_id = traveller.id
				left outer join country on traveller.nationality_id = country.id
				left outer join customer on reservation.customer_id = customer.id':'').'
				'.(URL::get('room_id')?'left outer join room on reservation_room.room_id=room.id':'').'
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging =  paging($count['acount'],$item_per_page,10,$smart=false,$page_name='page_no',array('year','month','day','portal_id','room_id','customer_name','traveller_name','price_operator','booking_code','code','nationality_id','note','status'));
		$sql = '
			SELECT
				*
			FROM
				(SELECT
					reservation_room.id
					,reservation_room.reservation_id
					,reservation_room.adult
					,NVL(reservation_room.child,0) + NVL(reservation_room.child_5,0) as child
					,reservation_room.price
					,reservation_room.time_in
					,reservation_room.time_out
					,reservation_room.status
					,room.name as room_id
					,reservation_room.arrival_time
					,reservation_room.departure_time
					,to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as brief_arrival_time
					,to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as brief_departure_time
					,room_level.brief_name as room_level
					,reservation.customer_id
					,customer.name as customer_name
					,reservation.tour_id
					,tour.name as tour_name
					,reservation.user_id
					,reservation.user_id as user_name
					,DECODE(reservation_room.status,\'CHECKIN\',1,\'BOOKED\',2,DECODE(reservation_room.status,\'CHECKOUT\',3,4)) as order_type
					,reservation_room.time
					,reservation_room.early_checkin
					,reservation_room.early_arrival_time
					,reservation_room.lastest_edited_user_id
					,reservation_room.lastest_edited_time
					,reservation_room.checked_in_user_id
					,reservation_room.booked_user_id
					,party.name_1 as portal_name
					,reservation.booking_code
					,reservation_traveller.id as reservation_traveller_id
					,reservation_room.note
					,reservation.note as group_note
                    ,reservation.mice_reservation_id
					,reservation_room.verify_dayuse
					,row_number() over (ORDER BY customer.name,reservation.booking_code, reservation_room.reservation_id, reservation_room.time_in) as rownumber
				FROM
					reservation_room
					left outer join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
					left outer join reservation on reservation.id = reservation_room.reservation_id
					left outer join party on party.user_id = reservation.portal_id
					left outer join room on room.id = reservation_room.room_id
					left outer join room_level on room_level.id = reservation_room.room_level_id
					left outer join traveller on reservation_traveller.traveller_id = traveller.id
					left outer join country on traveller.nationality_id = country.id
					left outer join tour on reservation.tour_id = tour.id
					left outer join customer on reservation.customer_id = customer.id
				WHERE
					'.$cond.' '.$cond_2.' --and reservation_room.note_change_room is null
				ORDER BY
					customer.name,reservation.booking_code, reservation_room.reservation_id, reservation_room.time_in
				)
			WHERE
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
		$i=1;
		$reservation_ids = '(0,0';
		$count = 0;
		$c = 0;
		$temp_reservation_id = 0;
		$reservation_arr = array();
		$this->map['total'] = 0;
		$this->map['total_checkout'] = 0;
		//System::Debug($items);
		foreach($items as $key=>$item)
		{
			$items[$key]['cc'] = 0;
			if(date('d/m/Y',$item['time_out']) == $to_date){
				$out_date = true;
			}else{
				$out_date = false;
			}
			$indate = Date_Time::to_time($to_date);
			$arr_time = Date_time::to_time($item['brief_arrival_time']);
			$drr_time = Date_time::to_time($item['brief_departure_time']);
			/*if(Date_Time::to_time(date('d/m/Y',$item['time_in'])) < Date_Time::to_time(date('d/m/Y',$item['time_out'])) and date('d/m/Y',$item['time_out']) == $end_day.'/'.$end_month.'/'.$end_year and (!$item['verify_dayuse'] or ($item['verify_dayuse'] and $item['verify_dayuse']==0)) and ($item['status'] == 'CHECKOUT' || $item['status'] == 'CHECKIN')){ // and Url::get('status') != 'CHECKOUT' AND !Url::get('booking_code')
				unset($items[$key]);
			}else{*/
				if($item['verify_dayuse']){
					$verify_dayuse = $item['verify_dayuse']/10;
					if($item['early_checkin']){
						if($item['early_arrival_time']){
									$item['early_arrival_time'] = Date_Time::convert_orc_date_to_date($item['early_arrival_time'],'/');
									$time_arriv = Date_Time::to_time($item['early_arrival_time']);
									if($time_arriv == $indate ){
											$this->map['total'] +=$verify_dayuse;
											$this->map['total_checkout'] += $verify_dayuse;
											$items[$key]['verify_dayuse'] = $item['verify_dayuse']/10;
									}else{ $items[$key]['verify_dayuse']='';}
						}else{
									if($arr_time == $indate ){
										$this->map['total'] += $verify_dayuse;
										$this->map['total_checkout'] += $verify_dayuse;
										$items[$key]['verify_dayuse'] = $item['verify_dayuse']/10;
									}else{ $items[$key]['verify_dayuse']=''; }
						}
					}else{
						if($item['verify_dayuse'] < 0 ){
								  if($arr_time < $drr_time){
									  $time_before = $drr_time - 24*36000;
									  if($time_before == $indate ){
										  $this->map['total'] += $verify_dayuse;
										  $this->map['total_checkout'] += $verify_dayuse;
										  $items[$key]['verify_dayuse'] = $item['verify_dayuse']/10;
									  }else{  $items[$key]['verify_dayuse']='';};
								  }else{
									   if($drr_time == $indate){
										   $this->map['total'] += $verify_dayuse;
										   $this->map['total_checkout'] += $verify_dayuse;
										   $items[$key]['verify_dayuse'] = $item['verify_dayuse']/10;
									}else{ $items[$key]['verify_dayuse']=''; }
								  }
						}else{
							// echo $item['verify_dayuse'].'arr'.$item['brief_arrival_time'].'drr'.$item['brief_departure_time'].$end_day.'/'.$end_month.'/'.$end_year.'<br/>';
							 if($drr_time == $indate){
										   $this->map['total'] += $verify_dayuse;
										   $this->map['total_checkout'] += $verify_dayuse;
										   $items[$key]['verify_dayuse'] = $item['verify_dayuse']/10;
									}else{ $items[$key]['verify_dayuse']=''; }
						}
					}
				}
				if($arr_time <= $indate && $indate < $drr_time ){
					$this->map['total'] +=1;
				}
				if($arr_time == $drr_time && $indate == $drr_time ){
					$this->map['total'] +=1;
				}
				if($indate == $drr_time ){
					$this->map['total_checkout'] +=1;
				}
				$items[$key]['verify_dayuse_label'] = '<br>('.(($item['verify_dayuse']>0)?'+':'').($items[$key]['verify_dayuse']).')';
				/*if($out_date){
				}else{
					$items[$key]['verify_dayuse_label'] = '';
				}*/
				if($temp_reservation_id == $item['reservation_id']){
					$count += 1 + ($out_date?$items[$key]['verify_dayuse']:0);
					$c++;
				}else{
					$temp_reservation_id = $item['reservation_id'];
					$count =  1 + ($out_date?$items[$key]['verify_dayuse']:0);
					$c = 1;
					$reservation_ids .= ','.$key;
				}
				$reservation_arr[$temp_reservation_id] = $count;
				$items[$key]['cc'] = $c;
				if($item['reservation_traveller_id']){
					$reservation_ids .= ','.$key;
				}
			//}
		}
		$reservation_ids .= ')';
		$this->map['reservation_arr'] = $reservation_arr;
		$sql = '
			select
				reservation_traveller.id,
				reservation_traveller.traveller_id,
				concat(\' &bull; \',concat(DECODE(gender,1,\'Mr. \',\'Mrs/Miss. \'),concat(traveller.first_name,concat(\' \',traveller.last_name)))) as full_name,
				reservation_room.room_id,
				reservation_room.id as reservation_room_id,
				concat(\'?page=traveller&id=\',reservation_traveller.traveller_id) as href,
				room.name as room_name
			from
				reservation_traveller
				inner join traveller on traveller.id = reservation_traveller.traveller_id
				left outer join reservation_room on reservation_room_id=reservation_room.id
				left outer join room on room_id=room.id
			where
				reservation_room.id IN '.$reservation_ids.'
				order by concat(traveller.first_name,concat(\' \',traveller.last_name))
		';
		$all_travellers = DB::fetch_all($sql);
		//System::Debug($all_travellers);
		$travellers = array();
		$i=1;
		foreach($all_travellers as $traveller_id=>$traveller)
		{
			unset($traveller['reservation_room_id']);
			unset($traveller['room_id']);
			unset($traveller['id']);
			if(URL::get('customer_name') or URL::get('booking_resource'))
			{
				//$traveller['full_name'] = hightlight_keyword(strtolower($traveller['full_name']),array(strtolower(URL::get('customer_name',URL::get('booking_resource')))));
			}
			$i++;
			$travellers[$all_travellers[$traveller_id]['reservation_room_id']][$all_travellers[$traveller_id]['room_name']][$i] = $traveller;
		}
		$temp_reservation_id = 0;
		foreach($items as $key=>$item)
		{
			$items[$key]['price'] = System::display_number($item['price']);
			$items[$key]['time_in'] = date('H:i\' d/m',$item['time_in']);
			$items[$key]['time_out'] = date('H:i\' d/m',$item['time_out']);
			switch($item['status'])
			{
				case 'BOOKED':$items[$key]['bgcolor']='#EEFFFF';$items[$key]['status'] = 'BOOKED';break;
				case 'CHECKIN':$items[$key]['bgcolor']='#FFFFDD';$items[$key]['status'] = 'IN';break;
				case 'CHECKOUT':$items[$key]['bgcolor']='white';$items[$key]['status'] = 'OUT';break;
				case 'CANCEL':$items[$key]['bgcolor']='#CCCCCC';$items[$key]['status'] = 'CANCEL';break;
			}
			if(isset($all_travellers[$item['reservation_traveller_id']])){
				$items[$key]['travellers'][$item['reservation_traveller_id']]['id'] = $all_travellers[$item['reservation_traveller_id']]['traveller_id'];
				$items[$key]['travellers'][$item['reservation_traveller_id']]['full_name'] = $all_travellers[$item['reservation_traveller_id']]['full_name'];
			}
			//$items[$key]['travellers']['customer']['full_name'] = ;
			if($temp_reservation_id != $item['reservation_id']){
				$i = 1;
				$count = 1 + ($item['verify_dayuse']);
				$temp_reservation_id = $item['reservation_id'];
			}else{
				$i++;
				$count += 1 + ($item['verify_dayuse']);
			}
			$items[$key]['i'] = $i;
			$items[$key]['count'] = $count;
		}
        //System::debug($items);
		$just_edited_id['just_edited_ids'] = array();
		if (UrL::get('selected_ids'))
		{
			if(is_string(UrL::get('selected_ids')))
			{
				if (strstr(UrL::get('selected_ids'),','))
				{
					$just_edited_id['just_edited_ids']=explode(',',UrL::get('selected_ids'));
				}
				else
				{
					$just_edited_id['just_edited_ids']=array('0'=>UrL::get('selected_ids'));
				}
			}
		}
		$nationalities =  DB::fetch_all('
						SELECT
							code_1 as id,name_'.Portal::language().' as name
						FROM
							country
						ORDER BY
							name_1'
						);
		$this->map['price_operator_list'] = array(
			'>'=>'>',
			'>='=>'>=',
			'<'=>'<',
			'<='=>'<=',
			'='=>'=',
		);
		$layout = 'list';
		if(Url::get('view_printable_list')){
			$layout = 'printable_list';
		}
        $room_level = DB::fetch_all('SELECT * FROM room_level ORDER BY id');
        $this->map['room_level_list'] = array(''=>Portal::language('select')) + String::get_list($room_level);
		$this->parse_layout($layout,$this->map+$just_edited_id+
			get_time_parameters()+
			array(
                'from_date'=>$from_date,
                'to_date'=>$to_date,
				'items'=>$items,
				'nationality_id_list'=>array(''=>Portal::language('all')) + String::get_list($nationalities),
				'nationality_id'=>URL::get('nationality_id'),
				'paging'=>$paging,
				'portal_id_list'=>array('ALL'=>Portal::language('all')) + String::get_list(Portal::get_portal_list()),
			)
		);
	}
}
?>
