<?php
class WaitingListForm extends Form
{
	function WaitingListForm()
	{
		Form::Form('WaitingListForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{
		$this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1; 
        if(URL::get('do_search'))
		{
		    require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';	
			$price=0;
			if(Url::get('price'))
			{
				$price=1;
			}
			$from_day = Url::get('date_from');
            $to_day = Url::get('date_to');
            
			$this->line_per_page = URL::get('line_per_page',20);
            //Start Luu Nguyen Giap add portal
            if(Url::get('portal_id'))
            {
               $portal_id =  Url::get('portal_id');
            }
            else
            {
                $portal_id =PORTAL_ID;
            }
            if($portal_id!="ALL")
            {
                $cond ="  reservation.portal_id ='".$portal_id."' ";
            }
            else
            {
                $cond=" 1=1 ";
            } 
            //End Luu Nguyen Giap add portal
            
			$cond .= ''	.(URL::get('date_from') and URL::get('date_to'))?' 
						AND reservation_room.arrival_time>=\''.Date_Time::to_orc_date($from_day).'\' 
						AND reservation_room.arrival_time<=\''.Date_Time::to_orc_date($to_day).'\' 
					':'';
			$status='';
            
			if(Url::get('status')!="0")
			{
				$status=Url::get('status');
				$cond.=' and reservation_room.status=\''.$status.'\'';
			}else{
				$cond.=' and (reservation_room.status=\'BOOKED\' or reservation_room.status=\'CANCEL\') ';
			}
            
			if(Url::check('today') and Url::get('today')==1)
			{
				$cond = ' reservation_room.arrival_time=\''.Date_Time::convert_time_to_ora_date(time()).'\' and reservation_room.status=\'BOOKED\'';
				
			}
            
			$report = new Report;
			$sql='
				SELECT
					reservation.id
					,CASE
						WHEN 
							customer.name = \'\' or customer.name is null
						THEN 
							tour.name
						ELSE
							customer.name
					END customer_name
					,reservation_room.arrival_time
					,reservation_room.departure_time
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
					,reservation.customer_id
					,customer.contact_person_name
					,customer.contact_person_phone
					,customer.name as customer_name
					,reservation_room.deposit
					,tour.name as tour_name
					,\' \' as room_level
					,0 as colspan
				'.(($from_day==$to_day)?'
					,(reservation_room.price) as price':'
					,((reservation_room.total_amount*(1+reservation_room.service_rate/100))*(1+reservation_room.tax_rate/100)) as price').'
					,room.name as room_name
				FROM
					reservation_room
					inner join reservation on reservation.id = reservation_room.reservation_id
					left outer join customer on customer.id = reservation.customer_id 
					left outer join tour on tour.id = reservation.tour_id
					left outer join room on room.id = reservation_room.room_id
				WHERE
					'.$cond.' and reservation_room.room_id is null
				ORDER BY
					reservation.id
			';		
            
            
			$report->items = DB::fetch_all($sql);
			//So phong vaf loai phong cua waitinglist
			$sql_list = '
				SELECT * FROM
				(
					select 
						CONCAT(reservation_room.reservation_id,CONCAT(\'-\',reservation_room.room_level_id)) as id
						,reservation_room.reservation_id
						,TO_CHAR(reservation_room.ARRIVAL_TIME,\'DD/MM\') AS arrival
						,reservation.booking_code
						,SUM(reservation_room.adult) AS adult
						,SUM(reservation_room.child) AS child
						,COUNT(reservation_room.room_level_id) as acount
						,room_level.brief_name as room_level
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
						reservation.booking_code,reservation_room.room_level_id,room_level.brief_name,reservation.customer_id,
						customer.name,reservation.tour_id,tour.name,reservation_room.reservation_id,reservation_room.ARRIVAL_TIME
					order by 
						reservation_room.reservation_id DESC
				)';
			$list_room_levels = DB::fetch_all($sql_list); 
			$i = 1;
			foreach($report->items as $key=>$item)
			{
				$report->items[$key]['price'] = System::display_number($item['price']);
				$report->items[$key]['confirm'] = $item['confirm']?Portal::language('yes'):Portal::language('not_yet');
				$report->items[$key]['stt'] = $i++;
				foreach($list_room_levels as $k=>$list){
					if($list['reservation_id'] == $item['id']){
						$report->items[$key]['room_level'] .= $list['room_level'].'('.$list['acount'].')<br>'; 	
					}
				}
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
				$report->items[$key]['contacts'] = $contacts;				
			}
			$this->print_all_pages($report);
		}
		else
		{
		    $_REQUEST['date_from'] = date('d/m/Y');
            $_REQUEST['date_to'] = date('d/m/Y');
            $this->map = array();
            //Start : Luu Nguyen GIap add portal
            $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
            //End   :Luu Nguyen GIap add portal
			$this->parse_layout('search',$this->map);	
		}			
	}

	function print_all_pages(&$report)
	{
	    $from_day = URL::get('date_from');
        $to_day = URL::get('date_to');
		$price=0;
		if(Url::get('price'))
		{
			$price=1;
		}
		$count = 0;
		$total_page = 1;
		$pages = array();
		$status=Url::get('status');
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
					'from_day'=>$from_day,
					'to_day'=>$to_day,
				)+$this->map
			);
			$this->parse_layout('footer',array(
				'page_no'=>0,
				'total_page'=>0
			));
		}
	}
	function print_page($items, &$report, $page_no,$total_page,$summary)
	{
	  
	   $from_day = URL::get('date_from');
        $to_day = URL::get('date_to');
			$price=0;
			if(Url::get('price'))
			{
				$price=1;
			}
			$status=Url::get('status');
            if($page_no>=$this->map['start_page'])
		{
        $this->parse_layout('header',
			array(
				'page_no'=>$page_no,
				'total_page'=>$total_page,
				'status'=>$status,
                'from_day'=>$from_day,
					'to_day'=>$to_day
				
			)+$this->map
		);		
		$this->parse_layout('report',
			$summary+
			array(
				'items'=>$items,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
				'price'=>$price
			)+$this->map
		);
		$this->parse_layout('footer',array(				
			'page_no'=>$page_no,
			'total_page'=>$total_page,
		)+$this->map
        );
    }
	}
}
?>
