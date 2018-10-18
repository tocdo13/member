<?php
class ListTourForm extends Form
{
	function ListTourForm()
	{
		Form::Form('ListTourForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 1000;
		$cond = '1=1
				'.(Url::get('tour_name')?' AND UPPER(TOUR.NAME) LIKE \'%'.strtoupper(Url::sget('tour_name')).'%\'':'').'
				'.(Url::get('customer_name')?' AND UPPER(CUSTOMER.NAME) LIKE \'%'.strtoupper(Url::sget('customer_name')).'%\'':'').'
				'.(Url::get('booking_code')?' AND UPPER(reservation.booking_code) LIKE \'%'.strtoupper(Url::sget('booking_code')).'%\'':'').'
				'.(Url::get('arrival_time')?' AND tour.arrival_time >= \''.(Date_Time::to_orc_date(Url::sget('arrival_time'),'/')).'\'':'').'
				'.(Url::get('departure_time')?' AND tour.departure_time <= \''.(Date_Time::to_orc_date(Url::sget('departure_time'),'/')).'\'':'').'
				'.(Url::get('source_tour_id')?' AND tour.id <> '.Url::iget('source_tour_id').'':'').'
			';
		$this->map['title'] = Portal::language('tour_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				TOUR
				LEFT OUTER JOIN CUSTOMER ON CUSTOMER.ID = TOUR.COMPANY_ID
					LEFT OUTER JOIN reservation ON reservation.TOUR_ID = TOUR.ID
					LEFT OUTER JOIN reservation_room rr ON rr.reservation_id = reservation.id
					LEFT OUTER JOIN reservation_traveller rt ON rt.reservation_room_id = rr.id
			WHERE
				'.$cond.'
		';
		require_once 'packages/core/includes/utils/paging.php';
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,10,$smart=false,$page_name='page_no',array('action'));
		$order_by = 'TOUR.ID';
		if(Url::get('order_by')){
			$order_by = Url::get('order_by');
		}
		if(Url::get('order_by_dir')){
			$order_by .= ' '.Url::get('order_by_dir');
		}else{
			$order_by .= ' DESC';
		}
		$sql = '
			SELECT * FROM
			(
				SELECT
					TOUR.ID,TOUR.ROOM_QUANTITY,TOUR.USER_ID,TOUR.LAST_MODIFIED_USER_ID,TOUR.COMPANY_ID,TOUR.NAME,TOUR.NUM_PEOPLE,TOUR.CHILD as num_child,
					reservation.NOTE,TOUR.CODE,CUSTOMER.NAME AS company_name,
					tour.total_amount,tour.extra_amount,tour.tour_leader,
					to_char(tour.arrival_time,\'DD/MM/YYYY\') as arrival_time,to_char(tour.departure_time,\'DD/MM/YYYY\') as departure_time,
					tour_id,
					TOUR.NUM_PEOPLE as adult,tour.child,reservation.id as reservation_id,
					reservation.booking_code,
					DECODE(rt.id,NULL,0,1) AS have_traveller,
					row_number() OVER(ORDER BY '.$order_by.') AS rownumber,
					'.((Url::get('action')=='select_tour')?' DECODE(RESERVATION.TOUR_ID,null,1,0) AS selected':' '.((Url::get('action')=='select_tour_to_copy')?'1 AS selected':'0 AS selected').'').'
				FROM
					TOUR 
					LEFT OUTER JOIN CUSTOMER ON CUSTOMER.ID = TOUR.COMPANY_ID
					LEFT OUTER JOIN reservation ON reservation.TOUR_ID = TOUR.ID
					LEFT OUTER JOIN reservation_room rr ON rr.reservation_id = reservation.id
					LEFT OUTER JOIN reservation_traveller rt ON rt.reservation_room_id = rr.id
				WHERE
					'.$cond.'
				ORDER BY 
					'.$order_by.'
			)
			WHERE
			 	1=1
		';//rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		$items = DB::fetch_all($sql);
		$items_room_cancelled = DB::fetch_all('
				SELECT
					TOUR.ID,
					TOUR.ROOM_QUANTITY,
					TOUR.USER_ID,
					TOUR.LAST_MODIFIED_USER_ID,
					TOUR.COMPANY_ID,
					TOUR.NAME,
					TOUR.NUM_PEOPLE,
					TOUR.CHILD as num_child,
					TOUR.NOTE,
					TOUR.CODE,
					CUSTOMER.NAME AS company_name,
					tour.total_amount,tour.extra_amount,tour.tour_leader,
					to_char(tour.arrival_time,\'DD/MM/YYYY\') as arrival_time,to_char(tour.departure_time,\'DD/MM/YYYY\') as departure_time,
					tour_id,
					TOUR.NUM_PEOPLE as adultt,tour.child,RESERVATION.id as reservation_id,
					reservation.booking_code,  
					row_number() OVER(ORDER BY TOUR.ID DESC) AS rownumber,
					'.((Url::get('action')=='select_tour')?' DECODE(RESERVATION.TOUR_ID,null,1,0) AS selected':' '.((Url::get('action')=='select_tour_to_copy')?'1 AS selected':'0 AS selected').'').'
				FROM
					TOUR 
					LEFT OUTER JOIN CUSTOMER ON CUSTOMER.ID = TOUR.COMPANY_ID
					LEFT OUTER JOIN RESERVATION ON RESERVATION.TOUR_ID = TOUR.ID
					LEFT OUTER JOIN reservation_room rr ON rr.reservation_id = reservation.id
				WHERE
					'.$cond.' AND rr.status <> \'CANCEL\'
				ORDER BY 
					TOUR.ID DESC');
		//System::Debug($items);	
		//System::Debug($items_room_cancelled);	
		foreach($items as $id => $item){
			if(isset($items_room_cancelled[$id]['id'])){
				$items[$id]['cancelled'] = 1;
			}else{
				if($item['reservation_id'] !=''){
					$items[$id]['cancelled'] = 0;
				}else{
					$items[$id]['cancelled'] = 1;	
				}
			}
		}
		//System::Debug($items);
		$i = 1;
		foreach($items as $key=>$value){
			$items[$key]['i'] = $i++;
			$items[$key]['number'] = 0;
		}
		$this->map['items'] = $items;
		$this->parse_layout('list',$this->map);
	}	
}
?>