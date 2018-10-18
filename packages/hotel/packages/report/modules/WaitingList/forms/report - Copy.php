<?php
class WaitingListForm extends Form
{
	function WaitingListForm()
	{
		Form::Form('WaitingListForm');
	}
	function draw()
	{
		if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';	
			$price=0;
			if(Url::get('price'))
			{
				$price=1;
			}
			$from_year = BEGINNING_YEAR;
			$to_year = date('Y',time());
			$from_month=1;
			$to_month=1;
			$from_day=1;
			$to_day=31;
			if(URL::get('from_year'))
			{
				$from_year = Url::get('from_year');
			}
			if(URL::get('to_year'))
			{
				$to_year = Url::get('to_year');
			}
			if(Url::get('from_month'))
			{	
				$from_month = Url::get('from_month');
			}
			if(Url::get('to_month'))
			{	
				$to_month = Url::get('to_month');
			}
			if(URL::get('from_day'))
			{
				$from_day = URL::get('from_day');
			}
			if(URL::get('to_day'))
			{
				$to_day = URL::get('to_day');
			}
			if(!checkdate($from_month,$from_day,$from_year))
			{
				$from_day = 1;
			}
			if(!checkdate($to_month,$to_day,$to_year))
			{
				$to_day = Date_time::day_of_month($to_month,$to_year);
			}
			$this->line_per_page = URL::get('line_per_page',20);
			$cond = ''	.(URL::get('from_year') and URL::get('to_year'))?' 
						AND reservation_room.arrival_time>=\''.Date_Time::to_orc_date($from_day.'/'.$from_month.'/'.$from_year).'\' 
						AND reservation_room.arrival_time<=\''.Date_Time::to_orc_date($to_day.'/'.$to_month.'/'.$to_year).'\' 
					':'';
			$status='';
			if(Url::get('status')!="0")
			{
				$status=Url::get('status');
				$cond.=' and reservation_room.status=\''.$status.'\'';
			}else{
				$cond.=' and reservation_room.status=\'BOOKED\'';
			}
			if(Url::check('today') and Url::get('today')==1)
			{
				$cond = ' and reservation_room.arrival_time=\''.Date_Time::convert_time_to_ora_date(time()).'\' and reservation_room.status=\'BOOKED\'';
				
			}
			$report = new Report;
			$sql='
				SELECT
					reservation_traveller.id
					,traveller.first_name 
					,traveller.last_name 
					,DECODE(traveller.gender,0,\''.Portal::language('female').'\',\''.Portal::language('male').'\') as gender
					,traveller.birth_date
					,arrival_time
					,departure_time
					,(reservation_room.arrival_time-current_date) as time_segment
					,reservation_room.time_in
					,reservation_room.time_out
					,traveller.passport ,traveller.visa 
					,CONCAT(CONCAT(CONCAT(
						DECODE(reservation.customer_id,0,\'\',CONCAT(customer.name,\'. \')),
						DECODE(reservation.note,\'\',\'\',CONCAT(reservation.note,\'. \'))),
						DECODE(reservation_room.note,\'\',\'\',CONCAT(reservation_room.note,\'. \'))),
						DECODE(reservation_traveller.SPECIAL_REQUEST,\'\',\'\',reservation_traveller.SPECIAL_REQUEST)
					) as note
					,traveller.phone ,traveller.fax ,traveller.address ,traveller.email 
					,traveller.nationality_id 
					,reservation_room.reservation_id
					,reservation_room.bill_number
					,reservation.booking_code
					,reservation_room.status
					,reservation_room.confirm
					,customer.contact_person_name
					,customer.contact_person_phone
					,customer.name as customer_name
					,tour.name as tour_name
					,0 as colspan
				'.(($from_day==$to_day and $from_month==$to_month)?'
					,(reservation_room.price) as price':'
					,((reservation_room.total_amount*(1+service_rate/100))*(1+tax_rate/100)) as price').'
					,reservation_traveller.reservation_room_id
					,country.code_'.Portal::language().' as nationality
					,room.name as room_name
				FROM
					reservation_traveller
					inner join traveller on traveller.id=reservation_traveller.traveller_id
					inner join reservation on reservation.id = reservation_traveller.reservation_id
					inner join reservation_room on reservation_room.id = reservation_traveller.reservation_room_id
					left outer join customer on customer.id = reservation.customer_id 
					left outer join tour on tour.id = reservation.tour_id
					left outer join country on country.id = traveller.nationality_id 
					left outer join room on room.id = reservation_room.room_id
				WHERE
					reservation.portal_id = \''.PORTAL_ID.'\' '.$cond.'
				ORDER BY
					reservation_room.status,reservation_traveller.traveller_id
			';		
			$report->items = DB::fetch_all($sql);
			System::Debug($report->items);
			$i = 1;
			foreach($report->items as $key=>$item)
			{
				$report->items[$key]['price'] = System::display_number($item['price']);
				$report->items[$key]['confirm'] = $item['confirm']?Portal::language('yes'):Portal::language('not_yet');
				$report->items[$key]['stt'] = $i++;
			}
			$this->print_all_pages($report);
		}
		else
		{
			$this->parse_layout('search');	
		}			
	}

	function print_all_pages(&$report)
	{
		$price=0;
		if(Url::get('price'))
		{
			$price=1;
		}
		$count = 8;
		$total_page = 1;
		$pages = array();
		$from_year =1990;
		$to_year =date('Y',time());
		$from_month=1;
		$to_month=1;
		$from_day=1;
		$to_day=31;
		$status="0";
		if(URL::get('from_year'))
		{
			$from_year = Url::get('from_year');
		}
		if(URL::get('to_year'))
		{
			$to_year = Url::get('to_year');
		}
		if(Url::get('from_month'))
		{	
			$from_month = Url::get('from_month');
		}
		if(Url::get('to_month'))
		{	
			$to_month = Url::get('to_month');
		}
		if(URL::get('from_day'))
		{
			$from_day = URL::get('from_day');
		}
		if(URL::get('to_day'))
		{
			$to_day = URL::get('to_day');
		}
		if(Url::get('status')!="0")
		{
			$status=Url::get('status');
		}
		$summary = array(
			'room_count'=>0,
			'guest_count'=>0,
			'total_price'=>0
		);
		$room_name = false;
		$reservation_id = false;
		foreach($report->items as $key=>$item)
		{
			if($reservation_id!=$item['reservation_id'])
			{
				$reservation_id=$item['reservation_id'];
			}
			else
			{
				$item['price']='';
			}
			if($room_name<>$item['room_name'])
			{
				$room_name=$item['room_name'];
				$summary['room_count']++;
			}
			$summary['guest_count']++;
			$summary['total_price']+=str_replace(',','',$item['price']);
			if($count>=$this->line_per_page)
			{
				$count = 0;
				$total_page++;
			}
			$pages[$total_page][$key] = $item;
			$line = ceil(strlen($item['note'])/36);
			$count+=($line<2)?2:$line;
		}
		if(sizeof($pages)>0)
		{
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page,$summary);
			}
		}
		else
		{
			$this->parse_layout('header',
			get_time_parameters()+
				array(
					'page_no'=>1,
					'total_page'=>1,
					'status'=>$status,
					'from_year'=>$from_year,
					'to_year'=>$to_year,
					'from_month'=>$from_month,
					'to_month'=>$to_month,
					'from_day'=>$from_day,
					'to_day'=>$to_day,
					'price'=>$price,
					'today'=>Url::get('today',0)
				)
			);
			$this->parse_layout('footer',array(
				'page_no'=>0,
				'total_page'=>0
			));
		}
	}
	function print_page($items, &$report, $page_no,$total_page,$summary)
	{
			$price=0;
			if(Url::get('price'))
			{
				$price=1;
			}
			$from_year =1990;
			$to_year =date('Y',time());
			$from_month=1;
			$to_month=1;
			$from_day=1;
			$to_day=31;
			$status="0";
			if(URL::get('from_year'))
			{
				$from_year = Url::get('from_year');
			}
			if(URL::get('to_year'))
			{
				$to_year = Url::get('to_year');
			}
			if(Url::get('from_month'))
			{	
				$from_month = Url::get('from_month');
			}
			if(Url::get('to_month'))
			{	
				$to_month = Url::get('to_month');
			}
			if(URL::get('from_day'))
			{
				$from_day = URL::get('from_day');
			}
			if(URL::get('to_day'))
			{
				$to_day = URL::get('to_day');
			}
			if(Url::get('status')!="0")
			{
				$status=Url::get('status');
			}
		$this->parse_layout('header',
			array(
				'page_no'=>$page_no,
				'total_page'=>$total_page,
				'status'=>$status,
				'from_year'=>$from_year,
				'to_year'=>$to_year,
				'from_month'=>$from_month,
				'to_month'=>$to_month,
				'from_day'=>$from_day,
				'to_day'=>$to_day,
				'price'=>$price,
				'today'=>Url::get('today',0)
			)
		);		
		$this->parse_layout('report',
			$summary+
			array(
				'items'=>$items,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
				'price'=>$price
			)
		);
		$this->parse_layout('footer',array(				
			'page_no'=>$page_no,
			'total_page'=>$total_page,
		));
	}
}
?>