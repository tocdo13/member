<?php
class KaraokeTableReportForm extends Form
{
	function KaraokeTableReportForm()
    {
		Form::Form('KaraokeTableReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
    {
        require_once 'packages/hotel/packages/karaoke/includes/table.php';
		$this->map = array();
        
        $this->map['title'] = Portal::language('karaoke_table_report');
        if(Url::get('type')=='change_shift')
            $this->map['title'] = Portal::language('change_shift_report');
            
        require_once 'packages/core/includes/utils/lib/report.php';
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):20;
        $this->line_per_page = $this->map['line_per_page']; 
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):4000;
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
        if(!isset($_REQUEST['date'])){
			$_REQUEST['date'] = date('d/m/Y');
		}
		$date = Url::get('date')?Url::get('date'):date('d/m/Y');
        $this->map['status'] = '
                                <option value="">'.(Portal::language('All')).'</option>
                                <option value="CHECKIN">CHECKIN</option>
                                <option value="CHECKOUT">CHECKOUT</option>
                                <option value="BOOKED">BOOKED</option>
                                ';
        $this->map['karaoke_id_list'] = String::get_list(DB::fetch_all('Select * from karaoke where portal_id = \''.PORTAL_ID.'\' '.Table::get_privilege_karaoke().' '));
        
        $cond = ' 1=1 and karaoke_reservation.portal_id = \''.PORTAL_ID.'\' '
				.(Url::get('karaoke_id')?' and karaoke_reservation.karaoke_id = '.Url::get('karaoke_id').' ':' ') 
				.' AND (karaoke_reservation.departure_time >= '.Date_Time::to_time($date).' AND karaoke_reservation.departure_time < '.(Date_Time::to_time($date)+(24*3600)).') '
			;
        
        if(Url::get('type')=='change_shift')
            $cond .= ' and karaoke_reservation.status = \'CHECKIN\' ';
        else
            $cond .= (Url::get('status')?' and karaoke_reservation.status = \''.Url::sget('status').'\'':'');
        
        $sql = '
			SELECT 
				* 
			FROM
				(SELECT
					karaoke_reservation.id,
                    karaoke_reservation.total,
                    karaoke_reservation.arrival_time,
                    karaoke_reservation.departure_time,
                    karaoke_reservation.status
				FROM 
					karaoke_reservation 
				WHERE 
					'.$cond.'
				ORDER BY
					karaoke_reservation.id
				)
			WHERE
				rownum > '.(($this->map['start_page']-1)*$this->map['line_per_page']).' AND rownum<='.($this->map['no_of_page']*$this->map['line_per_page']).'
		';
        $report = new Report;
		$report->items = DB::fetch_all($sql);                                   
		$i = 1;			
		foreach($report->items as $key=>$value)
        {
			$report->items[$key]['stt'] = $i++;
            $sql = '
                        SELECT
        					karaoke_reservation_table.id,
                            karaoke_reservation_table.karaoke_reservation_id,
                            karaoke_table.name
        				FROM 
        					karaoke_reservation
                            inner join karaoke_reservation_table on karaoke_reservation.id = karaoke_reservation_table.karaoke_reservation_id
                            inner join karaoke_table on karaoke_table.id = karaoke_reservation_table.table_id
        				WHERE 
        					karaoke_reservation.id = '.$key.'
                            and karaoke_table.portal_id = \''.PORTAL_ID.'\'
        				ORDER BY
        					karaoke_reservation.id
        		';
            $table = DB::fetch_all($sql);
            $report->items[$key]['table_name'] = '';
            foreach($table as $k=>$v)
            {
                $report->items[$key]['table_name'].=$v['name'].',';                                                                                                    
    		}
            $report->items[$key]['table_name'] = trim($report->items[$key]['table_name'],',');
        }
        //System::debug($report->items);
		$this->print_all_pages($report);
	}
    
    function print_all_pages(&$report)
	{
		$count = 0;
		$total_page = 1;
		$pages = array();
		foreach($report->items as $key=>$item)
		{
			if($count>=$this->line_per_page)
			{
				$count = 0;
				$total_page++;
			}
			$pages[$total_page][$key] = $item;
			$count++;
		}
		if(sizeof($pages)>0)
		{
			$this->group_function_params = array(
					'total'=>0,
				);
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page);
			}
		}
		else
		{
            $this->parse_layout('header',$this->map+array('page_no'=>1,'total_page'=>1));
			$this->parse_layout('no_record',$this->map);
		}
	}
    
    function print_page($items, &$report, $page_no,$total_page)
	{
		$last_group_function_params = $this->group_function_params;
		foreach($items as $item)
		{
            $this->group_function_params['total']+=$item['total']; 
		}
        if($page_no==1){
            $this->parse_layout('header',$this->map+array('page_no'=>$page_no,'total_page'=>$total_page));    
        }
        
        		
		$this->parse_layout('report',array(
				'items'=>$items,
				'last_group_function_params'=>$last_group_function_params,
				'group_function_params'=>$this->group_function_params,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)+$this->map
		);
	}
}
?>