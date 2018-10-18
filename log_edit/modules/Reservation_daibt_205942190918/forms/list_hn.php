<?php
class ListReservationForm extends Form
{
	function ListReservationForm()
	{

		Form::Form('ListReservationForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_css('skins/default/datetime.css');
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
		if(URL::get('customer_name') or URL::get('nationality_id') or URL::get('company_name') or URL::get('traveller_name') or URL::get('booking_resource')){
			require_once 'packages/hotel/includes/php/search.php';
		}
		require_once 'packages/core/includes/utils/time_select.php';
		$year = get_time_parameter('year', date('Y'), $end_year);
		$month = get_time_parameter('month', date('m'), $end_month);
		$day = get_time_parameter('day', date('d'), $end_day);
		if(!Url::get('portal_id')){
			$_REQUEST['portal_id'] = PORTAL_ID;
		}
		if(Url::get('portal_id')=='ALL'){
			$cond = '1=1';
		}else{
			$cond = 'reservation.portal_id = \''.Url::get('portal_id').'\'';
		}
		$cond .= ''.(URL::get('customer_name')?'
					AND (LOWER(reservation_room.customer_name) LIKE \'%'.strtolower(URL::get('customer_name')).'%\'
					OR LOWER(traveller.first_name) LIKE \'%'.strtolower(URL::get('customer_name')).'%\'
					OR LOWER(traveller.last_name) LIKE \'%'.strtolower(URL::get('customer_name')).'%\'
					OR LOWER(reservation.note) LIKE \'%'.strtolower(URL::get('customer_name')).'%\'
					OR LOWER(tour.name) LIKE \'%'.strtolower(URL::get('customer_name')).'%\')
				':'')
				.(URL::get('code')?' AND (LOWER(reservation.id) LIKE \'%'.strtolower(URL::sget('code')).'%\')':'')
				.(URL::get('company_name')?' AND (LOWER(customer.name) LIKE \'%'.strtolower(URL::get('company_name')).'%\')':'')
				.(URL::get('booking_resource')?'
					AND (LOWER(customer.name) LIKE \'%'.strtolower(URL::get('booking_resource')).'%\'
					OR reservation.note LIKE \'%'.URL::get('booking_resource').'%\'
					OR LOWER(customer.contact_person_name) LIKE \'%'.strtolower(URL::get('booking_resource')).'%\')':'')
				.(URL::get('traveller_name')?' AND (CONCAT(LOWER(traveller.first_name),CONCAT(\' \',LOWER(traveller.last_name))) LIKE \'%'.strtolower(URL::sget('traveller_name')).'%\')':'')
				.(URL::get('note')?' AND LOWER(reservation.note) LIKE \'%'.strtolower(URL::get('note')).'%\'':'')
				.(URL::get('nationality_id')?' AND traveller.nationality_id = \''.URL::get('nationality_id').'\'':'')
				.(URL::get('room_id')?' and room.name LIKE \'%'.URL::get('room_id').'%\'':'')
				.(URL::get('code')?' and reservation_room.reservation_id = '.URL::iget('code').'':'')
				.(URL::get('invoice_id')?' and reservation_room.id = '.URL::iget('invoice_id').'':'')
				.(URL::get('price')?' and reservation_room.price '.URL::sget('price_operator').' '.URL::fget('price').'':'')
		;
		$this->map['title'] = Url::get('status')=='BOOKED';
		if(Url::get('status')=='CHECKIN'){
			$cond .= ' AND (reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'CHECKOUT\')';
			if(Url::get('occupied')==1){
				$this->map['title'] = Portal::language('occupied_list');
				$cond .= (!(URL::get('customer_name') or URL::get('traveller_name')or URL::get('company_name')or URL::get('booking_resource'))?
				' AND ((reservation_room.arrival_time<\''.Date_Time::to_orc_date($day.'/'.$month.'/'.$year).'\' AND reservation_room.departure_time>\''.Date_Time::to_orc_date($day.'/'.$month.'/'.$year).'\') and reservation_room.arrival_time < reservation_room.departure_time)':'');
			}else{
				$this->map['title'] = Portal::language('checkin_list');
				$cond .= (!(URL::get('customer_name') or URL::get('traveller_name')or URL::get('company_name')or URL::get('booking_resource'))?' AND reservation_room.arrival_time=\''.Date_Time::to_orc_date($day.'/'.$month.'/'.$year).'\'':'');
			}
		}elseif(Url::get('status')=='CHECKOUT'){
			$this->map['title'] = Portal::language('checkout_list');
			$cond .= ' AND (reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'CHECKOUT\')';
			$cond .= (!(URL::get('customer_name') or URL::get('traveller_name')or URL::get('company_name')or URL::get('booking_resource'))?' AND reservation_room.departure_time=\''.Date_Time::to_orc_date($end_day.'/'.$end_month.'/'.$end_year).'\'':'');
		}elseif(Url::get('status')=='BOOKED'){// booking and cancel
			$cond .= ' AND (reservation_room.status = \'BOOKED\')';
			//$cond .= ' AND (reservation_room.booked_user_id is not null)';
			if(Url::get('resolve')){
				$this->map['title'] = Portal::language('resolve_list');
				$cond .= ' AND (
								(reservation_room.arrival_time <= \''.Date_Time::to_orc_date($day.'/'.$month.'/'.$year).'\' AND reservation_room.departure_time > \''.Date_Time::to_orc_date($end_day.'/'.$end_month.'/'.$end_year).'\')
								OR (reservation_room.arrival_time = reservation_room.departure_time AND reservation_room.arrival_time <= \''.Date_Time::to_orc_date($day.'/'.$month.'/'.$year).'\' AND reservation_room.departure_time >= \''.Date_Time::to_orc_date($end_day.'/'.$end_month.'/'.$end_year).'\')
							)';
			}elseif(Url::get('no_checkin')){
				$this->map['title'] = Portal::language('not_check_in_yet_before_17h30_list');
				$cond .= ' AND (reservation_room.arrival_time = \''.Date_Time::to_orc_date($day.'/'.$month.'/'.$year).'\')';
			}else{
				$this->map['title'] = Portal::language('booked_list');
				$cond .= ' AND (reservation_room.arrival_time = \''.Date_Time::to_orc_date($day.'/'.$month.'/'.$year).'\')';
			}
		}elseif(Url::get('status')=='CANCEL'){
			$this->map['title'] = Portal::language('cancel_list');
			$cond .= ' AND (reservation_room.status = \'CANCEL\')';
			$cond .= ' AND (reservation_room.arrival_time = \''.Date_Time::to_orc_date($day.'/'.$month.'/'.$year).'\')';
		}
		if(Url::get('booking_code')){
			$cond = 'reservation.portal_id = \''.Url::sget('portal_id').'\' AND ((reservation.booking_code) LIKE \'%'.(URL::sget('booking_code')).'%\')';
		}
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
				left outer join customer on reservation.customer_id = customer.id':'').'
				'.(URL::get('room_id')?'left outer join room on reservation_room.room_id=room.id':'').'
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging =  paging($count['acount'],$item_per_page,10,$smart=false,$page_name='page_no',array('year','month','day','portal_id','room_id','customer_name','traveller_name','price_operator','booking_code','code','nationality_id','note'));
		$sql = '
			SELECT
				*
			FROM
				(SELECT
					reservation_room.id
					,reservation_room.reservation_id
					,reservation_room.adult
					,reservation_room.child
					,reservation_room.price
					,reservation_room.time_in
					,reservation_room.time_out
					,reservation_room.status
					,room.name as room_id
					,arrival_time
					,departure_time
					,to_char(arrival_time,\'DD/MM/YYYY\') as brief_arrival_time
					,to_char(departure_time,\'DD/MM/YYYY\') as brief_departure_time
					,room_level.brief_name as room_level
					,reservation.customer_id
					,customer.name as customer_name
					,reservation.tour_id
					,tour.name as tour_name
					,reservation.user_id
					,reservation.user_id as user_name
					,DECODE(reservation_room.status,\'CHECKIN\',1,\'BOOKED\',2,DECODE(reservation_room.status,\'CHECKOUT\',3,4)) as order_type
					,reservation_room.time
					,reservation_room.lastest_edited_user_id
					,reservation_room.lastest_edited_time
					,reservation_room.checked_in_user_id
					,reservation_room.booked_user_id
					,party.name_1 as portal_name
					,reservation.booking_code
					,reservation_traveller.id as reservation_traveller_id
					,reservation_room.note
					,reservation.note as group_note
					,reservation_room.verify_dayuse
					,row_number() over (ORDER BY reservation_room.reservation_id DESC,reservation_room.time_in) as rownumber
				FROM
					reservation_room
					inner join reservation on reservation.id = reservation_room.reservation_id
					inner join party on party.user_id = reservation.portal_id
					left outer join room on room.id = reservation_room.room_id
					left outer join room_level on room_level.id = reservation_room.room_level_id
					left outer join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
					left outer join traveller on reservation_traveller.traveller_id = traveller.id
					left outer join tour on reservation.tour_id = tour.id
					left outer join customer on reservation.customer_id = customer.id
				WHERE
					'.$cond.'
				ORDER BY
					reservation_room.reservation_id DESC,reservation_room.time_in
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
		foreach($items as $key=>$item)
		{
			if(date('d/m/Y',$item['time_out']) == $end_day.'/'.$end_month.'/'.$end_year){
				$out_date = true;
			}else{
				$out_date = false;
			}
			$this->map['total'] += ($out_date?($item['verify_dayuse']/10):0) + 1;
			$items[$key]['verify_dayuse'] = $item['verify_dayuse']/10;
			$items[$key]['verify_dayuse_label'] = (($item['verify_dayuse']>0)?'+':'').($items[$key]['verify_dayuse']);
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
			$items[$key]['c'] = $c;
			if($item['reservation_traveller_id']){
				//$reservation_ids .= ','.$key;
			}
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
		';
		$all_travellers = DB::fetch_all($sql);
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
				$items[$key]['travellers'][$item['reservation_traveller_id']]['id'] = $item['reservation_traveller_id'];
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
							id,name_'.Portal::language().' as name
						FROM
							country
						ORDER BY
							name_2'
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
		$this->parse_layout($layout,$this->map+$just_edited_id+
			get_time_parameters()+
			array(
				'year'=>$year,
				'month'=>$month,
				'day'=>$day,
				'end_month'=>$end_month,
				'end_day'=>$end_day,
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