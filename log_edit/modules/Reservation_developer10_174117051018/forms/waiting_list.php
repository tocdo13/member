<?php
class WaitingListReservationForm extends Form
{
	function WaitingListReservationForm(){
		Form::Form('WaitingListReservationForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_css('skins/default/datetime.css');
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
        $this->link_css('packages/hotel/skins/default/css/w3c.css');
        $this->link_css('packages/hotel/skins/default/css/font-awesome-4.7.0/css/font-awesome.min.css');
	}
	function draw()
	{
		if(URL::check(array('action'=>'update_all_reservation'))and USER::can_add(false,ANY_CATEGORY))
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
		$cond .= 'AND reservation_room.arrival_time=\''.Date_Time::to_orc_date($day.'/'.$month.'/'.$year).'\' and reservation_room.status=\'BOOKED\' ';
		if(Url::check('today') and Url::get('today')==1)
		{
			$cond = ' and reservation_room.arrival_time=\''.Date_Time::convert_time_to_ora_date(time()).'\' ';
		}
			$sql='
				SELECT
					reservation.id as id
					,CASE
						WHEN
							customer.name = \'\' or customer.name is null
						THEN
							tour.name
						ELSE
							customer.name
					END customer_name
					,arrival_time
					,departure_time
					,(reservation_room.arrival_time-current_date) as time_segment
					,reservation_room.time_in
					,reservation_room.time_out
					,CONCAT(
						DECODE(reservation.note,\'\',\'\',CONCAT(reservation.note,\'. \')),
						DECODE(reservation_room.note,\'\',\'\',CONCAT(reservation_room.note,\'. \'))
					) as note
					,reservation_room.reservation_id
					,reservation_room.bill_number
					,reservation.booking_code
					,reservation_room.status
					,reservation_room.confirm
					,customer.contact_person_name
					,customer.contact_person_phone
					,customer.name as customer_name
					,reservation_room.deposit
					,tour.name as tour_name
					,\' \' as room_level
					,0 as colspan
					,room.name as room_name
					,reservation_room.time
					,reservation_room.early_checkin
					,reservation_room.early_arrival_time
					,reservation_room.lastest_edited_user_id
					,reservation_room.lastest_edited_time
					,reservation_room.checked_in_user_id
					,reservation_room.booked_user_id
					,party.name_1 as portal_name
					,reservation_traveller.id as reservation_traveller_id
					,reservation_room.note
					,reservation.note as group_note
					,reservation_room.verify_dayuse
					,reservation.customer_id
                    			,0 as sum_child
                    			,0 as sum_adult
				FROM
					reservation_room
					inner join reservation on reservation.id = reservation_room.reservation_id
					inner join party on party.user_id = reservation.portal_id
					left outer join customer on customer.id = reservation.customer_id
					left outer join tour on tour.id = reservation.tour_id
					left outer join room on room.id = reservation_room.room_id
					left outer join reservation_traveller ON reservation_traveller.reservation_room_id = reservation_room.id
				WHERE
				  '.$cond.' and reservation_room.room_id is null
				ORDER BY
					reservation.id
			';
			$items = DB::fetch_all($sql);
			/*'.(($from_day==$to_day and $from_month==$to_month)?'
					,(reservation_room.price) as price':'
					,((reservation_room.total_amount*(1+service_rate/100))*(1+tax_rate/100)) as price').'*/
			//So phong vaf loai phong cua waitinglist
			$sql_list = '
				SELECT * FROM
				(
					select
						CONCAT(reservation_room.reservation_id,CONCAT(\'-\',reservation_room.room_level_id)) as id
						,reservation_room.reservation_id
						,reservation_room.id as reservation_room_id
						,TO_CHAR(reservation_room.ARRIVAL_TIME,\'DD/MM\') AS arrival
						,reservation.booking_code
						,SUM(NVL(reservation_room.adult,0)) AS adult
						,SUM(NVL(reservation_room.child,0))+SUM(NVL(reservation_room.child_5,0)) AS child                                                
						,COUNT(reservation_room.room_level_id) as acount
						,room_level.brief_name as room_level
						,room_level.id as room_level_id
						,customer.name as customer_name
						,tour.name as tour_name                                                
					from
						reservation_room
						inner join reservation on reservation.id = reservation_room.reservation_id
						inner join party on party.user_id = reservation.portal_id
						left outer join room on room.id = reservation_room.room_id
						left outer join room_level on room_level.id = reservation_room.room_level_id
						left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
						left outer join traveller on reservation_traveller.traveller_id = traveller.id
						left outer join tour on reservation.tour_id = tour.id
						left outer join customer on reservation.customer_id = customer.id
					where
					   '.$cond.'
					GROUP BY
						reservation_room.id,reservation.booking_code,reservation_room.room_level_id,room_level.brief_name,reservation.customer_id,
						customer.name,reservation.tour_id,tour.name,reservation_room.reservation_id,reservation_room.ARRIVAL_TIME,room_level.id
					order by
						reservation_room.reservation_id DESC
				)';
            /** Minh fix dem so luong Ng.L-Tr.E **/                
            $child_list = DB::fetch_all('
                    select
                        reservation_room.id
                        ,reservation_room.reservation_id
                        ,NVL(reservation_room.child,0) + NVL(reservation_room.child_5,0) as child                        
                        ,NVL(reservation_room.adult,0) as adult
                    from
                        reservation_room
						inner join reservation on reservation.id = reservation_room.reservation_id
						inner join party on party.user_id = reservation.portal_id
						left outer join room on room.id = reservation_room.room_id
						left outer join room_level on room_level.id = reservation_room.room_level_id
						left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
						left outer join traveller on reservation_traveller.traveller_id = traveller.id
						left outer join tour on reservation.tour_id = tour.id
						left outer join customer on reservation.customer_id = customer.id   
                    where
					   '.$cond.'    
                        and reservation_room.room_id is null                      
                    order by
						reservation_room.reservation_id                
            ');                
            //System::debug($sum_child);                                    
			$list_room_levels = DB::fetch_all($sql_list);
			$i = 1;
			foreach($items as $key=>$item)
			{
				$deposit = 0;
				$deposit = DB::fetch('
					SELECT
						id,SUM(amount*exchange_rate) as total_amount
					FROM
						payment
					WHERE
						bill_id = '.$key.' AND type=\'RESERVATION\' AND type_dps=\'GROUP\'
					GROUP BY
						id
				','total_amount');
				//$items[$key]['price'] = System::display_number($item['price']);
				$items[$key]['confirm'] = $item['confirm']?Portal::language('yes'):Portal::language('not_yet');
				$items[$key]['i'] = $i++;
				foreach($list_room_levels as $k=>$list){
					if($list['reservation_id'] == $item['id']){
						$deposit_room = DB::fetch('
							SELECT
								id,SUM(amount*exchange_rate) as total_amount
							FROM
								payment
							WHERE
								bill_id = '.$list['reservation_room_id'].' AND type=\'RESERVATION\' AND type_dps!=\'GROUP\'
							GROUP BY
								id
						','total_amount');
						$deposit+= $deposit_room;
						$num_room_lever = DB::fetch('select count(*) as acount from reservation_room where reservation_id = '.$item['id'].' AND room_level_id='.$list['room_level_id'],'acount');
						$items[$key]['room_level'] .= $list['room_level'].'('.$num_room_lever.')<br>';
						$items[$key]['child'] = $list['child'];
						$items[$key]['adult'] = $list['adult'];
					}
				}
				$items[$key]['deposit'] = $deposit;
				$contacts = array();
				if($item['customer_id'])
				{
					$contacts = DB::fetch_all('
						SELECT
							customer_contact.*
						FROM
							customer_contact
							inner join customer on customer.id = customer_contact.customer_id
						WHERE
							customer_contact.customer_id = '.$item['customer_id'].'
					');
				}
				$items[$key]['contacts'] = $contacts;
			}
		$id = '';
		foreach($items as $k => $itm){
			$id .= ($id=='')?$k:(','.$k);
		}      
        /** Minh fix dem so luong Ng.L-Tr.E **/         
        foreach($child_list as $key=>$value)
        {                
            if(!isset($items[$value['reservation_id']]))
            {                           
                $items[$value['reservation_id']]['sum_adult'] = $value['adult'];
                $items[$value['reservation_id']]['sum_child'] = $value['child'];                    
            }else{                        
                $items[$value['reservation_id']]['sum_adult'] += $value['adult'];
                $items[$value['reservation_id']]['sum_child'] += $value['child'];                    
            }
        }  
        //System::debug($items);
		$this->map['items'] = $items;
		$this->parse_layout('waiting_list',$this->map+
			get_time_parameters()+
			array(
				'year'=>$year,
				'month'=>$month,
				'day'=>$day,
				'end_month'=>$end_month,
				'end_day'=>$end_day,
				'items'=>$items,
				'id'=>$id,
				//'nationality_id_list'=>array(''=>Portal::language('all')) + String::get_list($nationalities),
				'nationality_id'=>URL::get('nationality_id'),
				//'paging'=>$paging,
				'portal_id_list'=>array('ALL'=>Portal::language('all')) + String::get_list(Portal::get_portal_list()),
			)
		);
	}
}
?>