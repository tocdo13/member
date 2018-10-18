<?php
class BreakfastReportForm extends Form
{
	function BreakfastReportForm()
	{
		Form::Form('BreakfastReportForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
	}
	function draw()
	{
        $this->map['date_from'] = Url::sget('date_from')?Url::sget('date_from'):date('d/m/Y');
        
        $this->map['date_to'] = Url::sget('date_to')?Url::sget('date_to'):date('d/m/Y');
        
        $this->map['from_time'] = Url::get('from_time')?Url::get('from_time'):'00:00';
        
        $this->map['to_time'] = Url::get('to_time')?Url::get('to_time'):date('H').':'.date('i');
        
        $_REQUEST['date_from'] = $this->map['date_from'];    

        $_REQUEST['date_to'] = $this->map['date_to'];
        
        $_REQUEST['from_time'] = $this->map['from_time'];
        
        $_REQUEST['to_time'] = $this->map['to_time'];
        
        $from_time_view = Date_Time::to_time($this->map['date_from']) + $this->calc_time($this->map['from_time']);                                
        
        $to_time_view = Date_Time::to_time($this->map['date_to']) + $this->calc_time($this->map['to_time']);
        
		if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';	
		
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
            
			$cond .= ' and reservation_room.time_out>='.$from_time_view.' and reservation_room.time_in<='.$to_time_view.'';
			
			$cond.=' and (reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'CHECKOUT\')';
			
            $report = new Report;
			$sql='
				select
					reservation_room.id
                    ,reservation_room.adult as num_people
					,reservation_room.child as num_child
					,traveller.first_name 
					,traveller.last_name 
					,DECODE(traveller.gender,0,\''.Portal::language('female').'\',\''.Portal::language('male').'\') as gender
					,traveller.birth_date
					,reservation_room.arrival_time
					,reservation_room.departure_time
					,(reservation_room.arrival_time-current_date) as time_segment
					,reservation_room.time_in
					,reservation_room.time_out
					,traveller.passport ,traveller.visa 
					,CONCAT(CONCAT(CONCAT(
						DECODE(reservation.customer_id,0,\'\',CONCAT(customer.name,\'. \')),
						DECODE(reservation.note,\'\',\'\',CONCAT(reservation.note,\'. \'))),
						DECODE(reservation_room.note,\'\',\'\',CONCAT(reservation_room.note,\'. \'))),
						DECODE(traveller.note,\'\',\'\',traveller.note)
					) as note
					,traveller.phone ,traveller.fax ,traveller.address ,traveller.email 
					,traveller.nationality_id 
					,reservation_room.reservation_id
					,reservation_room.status
					,0 as colspan
                    ,FROM_UNIXTIME(reservation_room.old_arrival_time) as ddd
				'.((date('d',Date_time::to_time($this->map['date_from']))== date('d',Date_time::to_time($this->map['date_from'])) and date('m',Date_time::to_time($this->map['date_from']))== date('m',Date_time::to_time($this->map['date_from'])))?'
					,(reservation_room.price) as price':'
					,((reservation_room.total_amount*(1+service_rate/100))*(1+tax_rate/100)) as price').'
					,reservation_traveller.reservation_room_id
					,country.code_'.Portal::language().' as nationality
					,room.name as room_name
				from
					reservation_room
					left join reservation_traveller on reservation_traveller.reservation_room_id=reservation_room.id					
					left join traveller on traveller.id=reservation_traveller.traveller_id
					inner join reservation on reservation.id=reservation_room.reservation_id
					left outer join customer on customer.id=reservation.customer_id 
					left outer join country on country.id=traveller.nationality_id 
					left outer join room on room.id=reservation_room.room_id
				where
					'.$cond .' and (FROM_UNIXTIME(reservation_room.old_arrival_time)!= reservation_room.arrival_time or reservation_room.old_arrival_time is null)
				order by 
					room.name ASC
			';		
			$report->items = DB::fetch_all($sql);
            //System::debug($report->items);
			$i = 1;
			foreach($report->items as $key=>$item)
			{
				$report->items[$key]['price'] = System::display_number($item['price']);
				$report->items[$key]['stt'] = $i++;
                if($item['arrival_time'] != $item['departure_time'])
                {
                   
                    if($item['time_out']>= $to_time_view)
                    {
                        if($item['time_in']<$from_time_view)
                        {
                            
                            $report->items[$key]['num_people'] = $item['num_people'] *((Date_Time::to_orc_date($this->map['date_to'])- Date_Time::to_orc_date($this->map['date_from'])) + 1);
                        }
                        else
                        {
                        
                            $report->items[$key]['num_people'] = $item['num_people'] *((Date_Time::to_orc_date($this->map['date_to'])- $item['arrival_time']) + 1);
                        }
                    }
                    else
                    {
                        if($item['time_in']<$from_time_view)
                        {
                            $report->items[$key]['num_people'] = $item['num_people'] *($item['departure_time']- Date_Time::to_orc_date($this->map['date_from']) + 1);
                        }
                        else
                        {
                            $report->items[$key]['num_people'] = $item['num_people'] *($item['departure_time'] - $item['arrival_time'] + 1);
                        }
                    }
                }
                
			}
            //System::debug($report->items);
			$this->print_all_pages($report);
		}
		else
		{
            $this->map= array();
            //Start : Luu Nguyen GIap add portal
            $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
            //End   :Luu Nguyen GIap add portal
			$this->parse_layout('search',$this->map);	
		}			
	}
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        //System::debug($arr);
        return $arr[0]*3600 + $arr[1]*60;
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
			$summary['guest_count'] += $item['num_people'];
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