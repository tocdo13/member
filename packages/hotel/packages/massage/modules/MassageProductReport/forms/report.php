<?php
class GuestMassageReportForm extends Form
{
	function GuestMassageReportForm()
	{
		Form::Form('GuestMassageReportForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
		require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
		$item_per_page = 2000;
		$cond = ' 1=1';//and  massage_reservation_room.status=\'CHECKOUT\'';
		if(Url::get('date_from'))
		{
			$cond .= ' and massage_reservation_room.time_out>='.Date_Time::to_time(Url::get('date_from'));
		}
		if(Url::get('date_to'))
		{
			$cond .= ' and massage_reservation_room.time_out < '.(Date_Time::to_time(Url::get('date_to'))+(24*3600));
		}
		$inner = 'inner join massage_room on massage_reservation_room.room_id = massage_room.id
				left outer join massage_reservation on massage_reservation_room.reservation_id = massage_reservation.id
				left outer join room on room.id = massage_reservation_room.hotel_reservation_room_id
				left outer join massage_guest on massage_guest.id = massage_reservation_room.guest_id
				';
		$sql = '
			SELECT 
				count(*) as total
			FROM
				massage_reservation_room
				'.$inner.'
			WHERE 
				'.$cond;
		$count = DB::fetch($sql,'total');
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count,$item_per_page);
		$sql = 
		'SELECT * FROM
		(
		  SELECT 
		  	 massage_reservation_room.id, 
			 (massage_reservation_room.time_out-massage_reservation_room.time_in)/60 as minutes,
			  massage_reservation_room.time_out,
			 total_amount,
			 massage_reservation_room.status,
			 massage_guest.full_name,
			 ROWNUM as rownumber
		  FROM 
			massage_reservation_room
			'.$inner.'
		  WHERE 
		   '.$cond.'	
		  ORDER BY 
		  	  massage_reservation_room.time_out DESC
		)
		WHERE
			 rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		$items = DB::fetch_all($sql);	
		$this->parse_layout('report',
			array(
				'items'=>$items,
				'paging'=>$paging
			)
		);
	}
}
?>