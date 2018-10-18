<?php
class DayToDayTravellerReportForm extends Form
{
	function DayToDayTravellerReportForm()
	{
		Form::Form('DayToDayTravellerReportForm');
        $this->link_css(Portal::template('hotel').'/css/report.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
	}
    
    function draw()
	{
	    require_once 'packages/core/includes/utils/time_select.php';
		$year = get_time_parameter('year', date('Y'), $end_year);
		$month = get_time_parameter('month', date('m'), $end_month);
        //echo $month;
		$day = get_time_parameter('day', date('d'), $end_day);
		$date = $year.'-'.$month.'-'.$day;
        $cond = '
        room.portal_id = \''.PORTAL_ID.'\'
        and room_status.in_date=\''.Date_Time::to_orc_date(URL::sget('arrival_date')).'\'
        and room_status.in_date>=\''.Date_Time::to_orc_date(URL::sget('departure_date')).'\''
        .(URL::get('country_id')?' and traveller.nationality_id='.URL::get('country_id'):''
        );
        $total_cond = '
        room.portal_id = \''.PORTAL_ID.'\'
        and room_status.in_date=\''.Date_Time::to_orc_date(URL::sget('arrival_date')).'\''
        .(URL::get('country_id')?' and traveller.nationality_id='.URL::get('country_id'):''
        );
        $today_cond = '
        room.portal_id = \''.PORTAL_ID.'\'
        and room_status.in_date=\''.Date_Time::to_orc_date(date('d/m/Y')).'\''
        .(URL::get('country_id')?' and traveller.nationality_id='.URL::get('country_id'):''
        );
        $today_items = $this->calculate($today_cond);
        if($today_items){
        foreach ($today_items as $key => $value)
        {
            $today_items[$key]['r2'] = $today_items[$key]['total_of_row'];
            $today_items[$key]['r1'] = 0;
        }
        }            
        $items = $this->calculate($cond);
        $total_items = $this->calculate($total_cond);
        $items2 = array();
        if(sizeof($items)!=0 and sizeof($total_items)!=0 )
        {
            foreach ($items as $key => $value)
            {
                $items[$key]['r1'] = $items[$key]['total_of_row'];
                $items[$key]['r2'] = 0;
                if (array_key_exists($key, $total_items))
                {
                    $items[$key]['r2'] = $total_items[$key]['total_of_row'];
                }
            }
            foreach ($total_items as $key => $value)
            {
                $total_items[$key]['r2'] = $total_items[$key]['total_of_row'];
                $total_items[$key]['r1'] = 0;
                if (array_key_exists($key, $items))
                {
                    $total_items[$key]['r1'] = $items[$key]['total_of_row'];
                }
            }
            $items2 = array_merge($items, $total_items);         
            //System::debug($items2);     
        }
        if (sizeof($total_items)==0 and sizeof($items)!=0)
        {
            foreach ($items as $key => $value)
            {
                $items[$key]['r1'] = $items[$key]['total_of_row'];
                $items[$key]['r2'] = 0;
            }
            $items2 = $items;  
        }
        if (sizeof($items)==0 and sizeof($total_items)!=0)
        {
            foreach ($total_items as $key => $value)
            {
                $total_items[$key]['r2'] = $total_items[$key]['total_of_row'];
                $total_items[$key]['r1'] = 0;
            } 
            $items2 =  $total_items;
        }     
        if(sizeof($items2)==0)
		{
		    $_REQUEST['arrival_date'] = date('d/m/Y');  
		    $_REQUEST['departure_date'] = date('d/m/Y');
			$this->parse_layout('header',
			get_time_parameters()+
				array(
					'year'=>$year,
					'month'=>$month,
					'day'=>$day,
					'end_month'=>$end_month,
					'end_day'=>$end_day,
					'available'=>1,
					'country_id_list'=>array(0=>'')+String::get_list(DB::select_all('country','id <>734 ','name_2')),
					'country_id'=>0
				)
			);
            $this->parse_layout('report',array('items'=>$today_items,'available'=>1));
		}
        else
        {
            $this->parse_layout('header',
            get_time_parameters()+
			array(
				'year'=>$year,
				'month'=>$month,
				'day'=>$day,
				'end_month'=>$end_month,
				'end_day'=>$end_day,
				'available'=>0,
				'country_id_list'=>array(0=>'')+String::get_list(DB::select_all('country',false,'name_2')),
				'country_id'=>1
			)
    		);
            $this->parse_layout('report',array('items'=>$items2,'available'=>0));
        }
        //System::debug($today_items);	
	}
    function calculate($cond)
    {
		$sql='
			select 
				CONCAT(country.id,room_status.in_date) AS id,
				country.name_'.Portal::language().' as country_name,
				room_status.in_date,
				to_char(room_status.in_date,\'DD\') as day,
				to_char(room_status.in_date,\'MM\') as month,
				count(reservation_traveller.traveller_id) as reservation_room_count,
				country.id as country_id 
			from
				traveller
				inner join reservation_traveller on reservation_traveller.traveller_id=traveller.id				
				inner join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
				inner join room on room.id = reservation_room.room_id
                left join room_level on room.room_level_id = room_level.id
				left outer join room_status on room_status.reservation_room_id=reservation_room.id and (room_status.in_date!=reservation_room.departure_time or reservation_room.departure_time=reservation_room.arrival_time) and room_status.status = \'OCCUPIED\'
				left outer join country on country.id = traveller.nationality_id
			where 
				'.$cond.'
                and (room_level.is_virtual=0 or room_level.is_virtual is NULL )
			group by 
				country.id,room_status.in_date,country.name_'.Portal::language().'
		';
		DB::query($sql);
		require_once 'packages/core/includes/utils/lib/report.php';
		$report = new Report;
        //$at = DB::fetch_all();
		$report->items = DB::fetch_all();
        
		if(sizeof($report->items)==0)
		{
			return;
		}
		$report->split_columns = array(
			'day',
		);
		$report->count_split_total = array(
			0=>true,
		);
		$report->split_values = array(
			'reservation_room_count',
		);
		$report->get_split_values();
		$report->split_column('country_name');
		$i = 1;
		foreach($report->items as $key=>$item)
		{
			$report->items[$key]['stt'] = $i++;
		}
		$items = $this->print_all_pages($report);
        return $items;
    }
	function print_all_pages(&$report)
	{
		$count = 0;
		$page = 1;
		$pages = array();
		foreach($report->items as $key=>$item)
		{
			if($count>=30)
			{
				$count = 0;
				$page++;
			}
			$pages[$page][$key] = $item;
			$count++;
		}
		$i = 0;
		foreach($pages as $page)
		{
			$i++;
			$items = $this->print_page($page, $report, $i>=sizeof($pages));
		}
        return $items;
	}
	function print_page($items, &$report,$last_page)
	{
		require_once 'packages/core/includes/utils/time_select.php';
		$year = get_time_parameter('year', date('Y'), $end_year);
		$month = get_time_parameter('month', date('m'), $end_month);
		$day = get_time_parameter('day', date('d'), $end_day);
		$date = $year.'-'.$month.'-'.$day;
		$this->group_function_params = array(
			'total_of_row'=>0,
			'split_total'=>$report->split_total_columns_sample
		);
        return $items;
		$this->parse_layout('footer');
	}
}
?>