<?php
class BarTableReportForm extends Form
{
	function BarTableReportForm()
    {
		Form::Form('BarTableReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
    {
        require_once 'packages/hotel/packages/restaurant/includes/table.php';
		$this->map = array();
        //System::debug($_REQUEST);die;
        $this->map['title'] = Portal::language('bar_table_report');
        if(Url::get('type')=='change_shift')
            $this->map['title'] = Portal::language('change_shift_report');
            
        require_once 'packages/core/includes/utils/lib/report.php';
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):20;
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):4000;
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        if(!isset($_REQUEST['date'])){
			$_REQUEST['date'] = date('d/m/Y');
		}
		$date = Url::get('date')?Url::get('date'):date('d/m/Y');
        $this->map['status'] = '
                                <option value="">'.(Portal::language('All')).'</option>
                                <option value="CHECKIN">CHECKIN</option>
                                <option value="BOOKED">BOOKED</option>
                                ';
        //KimTan xem bc theo portal
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
        $cond_bar = '';
        if(Url::get('portal_id') != 'ALL')
        {
            $cond_bar = Url::get('portal_id')?'and portal_id = \''.Url::get('portal_id').'\'':'';
        }
        //$this->map['bar_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(DB::fetch_all('Select * from bar where 1=1 '.$cond_bar.''.Table::get_privilege_bar().' '));
        
        $cond = '1=1';
        if(Url::get('portal_id'))
        {
            if(Url::get('portal_id') != 'ALL')
            {
               $cond.= 'and bar_reservation.portal_id = \''.Url::get('portal_id').'\' ';
            }
        }
        if(Url::get('portal_id'))
         {
             $portal_id =  Url::get('portal_id');
             if(Url::get('portal_id')!='ALL')
             {
                 $bars = DB::fetch_all("select id,name FROM bar where portal_id='".Url::get('portal_id')."'");
             }
             else
             {
                $bars = DB::select_all('bar',false); 
             }
         }
         else
         {
            $portal_id =PORTAL_ID;  
            $bars = DB::select_all('bar',false); 
         }
        //Start Luu Nguyen Giap add portal
        
        
        $bars = DB::fetch_all('select id,name from bar');
		$bar_ids = '';
        $bar_name = '';
		foreach($bars as $k => $bar)
        {
			if(Url::get('bar_id_'.$k))
            {
				$bar_ids .= ($bar_ids=='')?$k:(','.$k);	
                $bar_name .= ($bar_name=='')?$bar['name']:(', '.$bar['name']);
                $_REQUEST['bar_id_'.$k] = $bar['id'];
			}
		};
        $_REQUEST['bar_name'] = $bar_name;
        
        if($bar_name!='')
        {
            $cond_bar = " AND bar_reservation.bar_id in (".$bar_ids.")";
            //$cond_bar ='';
        }
        else
        {
            $cond_bar='';
        }
        $this -> map['bars'] = $bars;
        // end KimTan
		$cond.=' AND (bar_reservation.departure_time >= '.Date_Time::to_time($date).' AND bar_reservation.departure_time < '.(Date_Time::to_time($date)+(24*3600)).')';
		
        $cond .= (Url::get('status')?' and bar_reservation.status = \''.Url::sget('status').'\' and bar_reservation.status != \'CHECKOUT\'':' and bar_reservation.status != \'CHECKOUT\'');
        
        $sql = '
			SELECT 
				* 
			FROM
				(SELECT
					bar_reservation.id,
                    bar_reservation.code,
                    bar_reservation.total,
                    bar_reservation.arrival_time,
                    bar_reservation.departure_time,
                    bar_reservation.status,
                    bar_reservation.bar_id,
                    ROW_NUMBER() OVER(ORDER BY bar_reservation.id ) as rownumber
				FROM 
					bar_reservation 
				WHERE 
					'.$cond.'
                    '.$cond_bar.'
				ORDER BY
					bar_reservation.id
				)
			
		';
   
       //WHERE
		//		rownumber > '.(($this->map['start_page']-1)*$this->map['line_per_page']).' AND rownumber<='.($this->map['no_of_page']*$this->map['line_per_page']).'
        $report = new Report;
		$report->items = DB::fetch_all($sql);                               
		$i = 1;			
		$cond_rr = '';
        if(Url::get('portal_id'))
        {
            if(Url::get('portal_id') != 'ALL')
            {
               $cond_rr.= 'and bar_table.portal_id = \''.Url::get('portal_id').'\' ';
            }
        }
        foreach($report->items as $key=>$value)
        {
			$report->items[$key]['stt'] = $i++;
            $sql = '
                        SELECT
        					bar_reservation_table.id,
                            bar_reservation_table.bar_reservation_id,
                            bar_table.name,
                            bar_table.id as table_id
        				FROM 
        					bar_reservation
                            inner join bar_reservation_table on bar_reservation.id = bar_reservation_table.bar_reservation_id
                            inner join bar_table on bar_table.id = bar_reservation_table.table_id
        				WHERE 
        					bar_reservation.id = '.$key.'
                            '.$cond_rr.'
                            '.$cond_bar.'
        				ORDER BY
        					bar_reservation.id
        		';
            $table = DB::fetch_all($sql);
            $report->items[$key]['table_name'] = '';
            
            foreach($table as $k=>$v)
            {
                $report->items[$key]['table_name'].=$v['name'].',';
                $report->items[$key]['table_id']=$v['table_id'];                                                                                                    
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
			if($count>=$this->map['line_per_page'])
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
            $this->map['real_total_page']=count($pages);
            $this->map['real_page_no'] = 1;
            //System::debug($pages);
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page);
                $this->map['real_page_no'] ++;
			}
		}
		else
		{
		    $this->map['real_total_page'] = 0;
            $this->map['real_page_no'] = 0;  
            $this->parse_layout('header',$this->map+array('page_no'=>0,'total_page'=>0));
			$this->parse_layout('no_record',$this->map);
		}
	}
    
    function print_page($items, &$report, &$page_no,$total_page)
	{
		$last_group_function_params = $this->group_function_params;
		foreach($items as $item) 
		{
            $this->group_function_params['total']+=$item['total']; 
		}
        if($page_no>=$this->map['start_page'])
		{
		  
          
            $this->map['page_no'] = $page_no;
          
            $this->parse_layout('header',$this->map+array('total_page'=>$total_page));    
            
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
}
?>