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
		$cond = ' 1=1';//and  swimming_pool_reservation_pool.status=\'CHECKOUT\'';
		if(Url::get('date_from'))
		{
			$cond .= ' and swimming_pool_reservation_pool.time_out>='.Date_Time::to_time(Url::get('date_from'));
		}
		if(Url::get('date_to'))
		{
			$cond .= ' and swimming_pool_reservation_pool.time_out < '.(Date_Time::to_time(Url::get('date_to'))+(24*3600));
		}
		$inner = 'inner join swimming_pool on swimming_pool_reservation_pool.swimming_pool_id = swimming_pool.id
				left outer join swimming_pool_reservation on swimming_pool_reservation_pool.reservation_id = swimming_pool_reservation.id
				left outer join swimming_pool on swimming_pool.id = swimming_pool_reservation_pool.hotel_reservation_room_id
				left outer join swimming_pool_guest on swimming_pool_guest.id = swimming_pool_reservation_pool.guest_id
				';
		$sql = '
			SELECT 
				count(*) as total
			FROM
				swimming_pool_reservation_pool
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
		  	 swimming_pool_reservation_pool.id, 
			 (swimming_pool_reservation_pool.time_out-swimming_pool_reservation_pool.time_in)/60 as minutes,
			  swimming_pool_reservation_pool.time_out,
			 total_amount,
			 swimming_pool_reservation_pool.status,
			 swimming_pool_guest.full_name,
			 ROWNUM as rownumber
		  FROM 
			swimming_pool_reservation_pool
			'.$inner.'
		  WHERE 
		   '.$cond.'	
		  ORDER BY 
		  	  swimming_pool_reservation_pool.time_out DESC
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