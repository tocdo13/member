<?php
class GuestTennisReportForm extends Form
{
	function GuestTennisReportForm()
	{
		Form::Form('GuestTennisReportForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
		require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
		$item_per_page = 50;
		$cond = ' 1=1';//and  tennis_reservation_court.status=\'CHECKOUT\'';
		if(Url::get('date_from'))
		{
			$cond .= ' and tennis_reservation_court.time_out>='.Date_Time::to_time(Url::get('date_from'));
		}
		if(Url::get('date_to'))
		{
			$cond .= ' and tennis_reservation_court.time_out < '.(Date_Time::to_time(Url::get('date_to'))+(24*3600));
		}
		$inner = 'inner join tennis_court on tennis_reservation_court.court_id = tennis_court.id
				left outer join tennis_reservation on tennis_reservation_court.reservation_id = tennis_reservation.id
				left outer join court on court.id = tennis_reservation_court.hotel_reservation_room_id
				left outer join tennis_guest on tennis_guest.id = tennis_reservation_court.guest_id
				';
		$sql = '
			SELECT 
				count(*) as total
			FROM
				tennis_reservation_court
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
		  	 tennis_reservation_court.id, 
			 (tennis_reservation_court.time_out-tennis_reservation_court.time_in)/60 as minutes,
			  tennis_reservation_court.time_out,
			 total_amount,
			 tennis_reservation_court.status,
			 tennis_guest.full_name,
			 ROWNUM as rownumber
		  FROM 
			tennis_reservation_court
			'.$inner.'
		  WHERE 
		   '.$cond.'	
		  ORDER BY 
		  	  tennis_reservation_court.time_out DESC
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