<?php
class TicketServiceTotalReportForm extends Form
{
	function TicketServiceTotalReportForm()
	{
		Form::Form('TicketServiceTotalReportForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    	$this->link_js('packages/core/includes/js/jquery/datepicker.js');     
	}
	function draw()
	{
        require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
        $this->day = date('d/m/Y');
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
        $_REQUEST['from_date'] = $this->map['from_date'];
         $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        $_REQUEST['to_date'] = $this->map['to_date'];
        
        $day_orc = Date_Time::to_orc_date(date('d/m/Y'));
        
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
       	$ticket_service = DB::fetch_all('select id, name_'.Portal::language().' as name from ticket_service id');
        $this->map['ticket_service_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list($ticket_service);
        if(Url::get('portal_id'))
        {
            $portal_id = Url::get('portal_id');
        }
        else
        {
            $portal_id = PORTAL_ID;
            $_REQUEST['portal_id'] = PORTAL_ID;                       
        }
        if(Url::get('from_time'))
        {
            $from_time = $this->calc_time(Url::get('from_time'));
            $this->map['from_time'] = Url::get('from_time');
        }
        else
        {
            $this->map['from_time'] = '00:00';            
            $from_time = $this->calc_time($this->map['from_time']);
            $_REQUEST['from_time'] = $this->map['from_time'];
        }
        if(Url::get('to_time'))
        {
            $to_time = $this->calc_time(Url::get('to_time'));
            $this->map['to_time'] = Url::get('to_time');
        }
        else
        {
            $this->map['to_time'] = date('H').':'.date('i');            
            $to_time = $this->calc_time($this->map['to_time']);
            $_REQUEST['to_time'] = $this->map['to_time'];
        }
        $from_time_view = Date_Time::to_time($this->map['from_date']);                                
        $to_time_view = (Date_Time::to_time($this->map['to_date']) + 24*60*60);
        $cond =' 1 = 1 ';
        $cond_choose_service = ' 1=1';
        if($portal_id != 'ALL')
        {
            $cond.=' AND ticket_reservation.portal_id = \''.$portal_id.'\' '; 
            $cond_choose_service.=' AND ticket_service.portal_id = \''.$portal_id.'\' ';
        }
        if(Url::get('ticket_service_id')!='ALL' && Url::get('ticket_service_id')!='' )
        {
             $cond.=' AND ticket_invoice_detail.ticket_service_id = \''.Url::get('ticket_service_id').'\'';
             $cond_choose_service .= ' AND ticket_service.id = \''.Url::get('ticket_service_id').'\'';
        }
        $cond .= ' 
					AND ticket_reservation.time >= \''.$from_time_view.'\'
                    AND ticket_reservation.time <= \''.$to_time_view.'\'  
				';
        
	    $report = new Report;
        $report->items = TicketServiceTotalReportDB::get_all_bought_services($cond, $cond_choose_service);
       // System::debug($report);
        $total_items = array();
        $s_v ='';
        foreach($report->items as $key=>$value)
        {
            if(!isset($total_items[$value['name']]))
            {
                $total_items[$value['name']]['id'] = $value['name'];
                $total_items[$value['name']]['total_quantity'] = 0;
                $total_items[$value['name']]['total_total_amount'] = 0;
                $total_items[$value['name']]['total_total_discount'] = 0;
                $total_items[$value['name']]['total_total_after_tax'] = 0;
                $total_items[$value['name']]['total_total_tax'] = 0;
                $total_items[$value['name']]['total_net_amount'] = 0;
            }
            if($s_v != $value['name'])
            {
                $s_v = $value['name'];
            }   
            if($s_v == $value['name'])
            {
                $total_items[$value['name']]['id'] = $value['name'];
                $total_items[$value['name']]['total_quantity'] += ($value['quantity'] + $value['discount_quantity']);
                $total_items[$value['name']]['total_total_amount'] += $value['total_amount'];
                $total_items[$value['name']]['total_total_discount'] += $value['total_discount'] ;
               // $total_items[$value['name']]['total_total_after_tax'] += $value['total_after_tax'];
                $total_items[$value['name']]['total_total_tax'] += $value['total_tax'];
                $total_items[$value['name']]['total_net_amount'] += $value['net_amount'];
            }
            
                    
        }
      
        $this->print_all_pages($report,$total_items);
	}
	function print_all_pages(&$report,$total_items)
	{
		$summary = array(
	        'ticket_count'=>0,
            'real_ticket_count'=>0,
            'total_page'=>1,
            'real_total_page'=>1,
            'line_per_page' =>999,
            'no_of_page' =>50,
            'start_page' =>1,
            'total_quantity'=>0,
            'total_amount'=>0,
            'total_discount'=>0,
           // 'TOTAL_total_after_tax'=>0,
            'total_tax'=>0,
            'total_net_amount'=>0,
        );
        $summary['line_per_page'] = URL::get('line_per_page',999);
        
        $count = 0;
        $pages = array();          
        
        //duyet qua tung ban ghi      
        foreach($report->items as $key=>$item)
        {
            if(isset($report->items[$key]['row_num']))
            {
                $count += $report->items[$key]['row_num'];
            }
            else 
            {
                $count += 1;
            }
            //count >= so dong tren 1 trang thi reset ve 0 va tang so trang len 1
            if($count > $summary['line_per_page'])
        	{
        		$count = 1;
        		$summary['total_page']++;
        	}
            //In ra bắt đầu từ trang số 1
            $pages[$summary['total_page']][$key] = $item;	
        }
        
        //Nếu có dữ liệu từ câu truy vấn
        if(sizeof($pages)>0)
        {
            $summary['total_page'] = sizeof($pages);
            //Neu muon xem tu trang bao nhieu
            if(Url::get('start_page')>1)
            {
                $summary['start_page'] = Url::get('start_page');
                //Xoa bo cac phan tu trong mang ma co key (o day la page < start page)
                for($i = 1; $i< $summary['start_page']; $i++)
                    unset($pages[$i]);
            }
            //Neu muon xem toi da bao nhieu trang
            if(Url::get('no_of_page'))
            {
                //muon xem bao nhieu trang ?
                $summary['no_of_page'] = Url::get('no_of_page');
                //lay ra trang bat dau dc in (neu muon xem tu trang 5 thì se tra ve 5)
                $arr = array_keys($pages);
                if(!empty($arr))
                {
                    $begin = $arr['0'];
                    //trang cuoi cung dc in
                    $end = $begin + $summary['no_of_page'] - 1;
                    //Xoa bo cac phan tu trong mang ma co key (o day la page < end page)
                    for($i = $summary['total_page']; $i> $end; $i--)
                        unset($pages[$i]);      
                }
            }
            if(!empty($pages))
            {
                $summary['real_total_page']=count($pages);
                //Số trang thực sự sau khi lọc qua các yêu cầu
            	foreach($pages as $page_no=>$page)
            	{
                	foreach($page as $key=>$value)
                	{
                        $summary['total_quantity'] += ($page[$key]['quantity']+$page[$key]['discount_quantity']);
                        $summary['total_amount'] += $page[$key]['total_amount'];
                        $summary['total_tax'] += $page[$key]['total_tax'];
                        $summary['total_discount'] += $page[$key]['total_discount'];
                        //$summary['TOTAL_total_after_tax'] += $page[$key]['total_after_tax'];
                        $summary['total_net_amount'] += $page[$key]['net_amount'];
                	} 
            	}
                $real_page_no = 1;
                foreach($pages as $page_no=>$page)
            	{
            		$this->print_page($page, $page_no , $real_page_no, $summary,$total_items);
                    $real_page_no++;
            	}
            }
            else
            {
                $this->prase_layout_default($summary,$total_items);
            }
        }
        else
        {
            $this->prase_layout_default($summary,$total_items);
        }
	}
	function print_page($items, $page_no,$real_page_no,$summary,$total_items)
	{
	     //System::debug($summary);
         foreach($items as $key => $value)
         {
            $items[$key]['price'] = number_format($value['price']);
            $items[$key]['total_amount'] = number_format($value['total_amount']);
            $items[$key]['total_discount'] = number_format($value['total_discount']);
            //$items[$key]['total_after_tax'] = number_format($value['total_after_tax']);
            $items[$key]['total_tax'] = number_format($value['total_tax']);
            $items[$key]['net_amount'] = number_format($value['net_amount']);
            if(!isset($value['row_num']))
            {
                $items[$key]['row_num'] = 1;
            }
         }
         $this->parse_layout('report',
        	$summary+
        	array(
        		'items'=>$items,
                'total_items'=>$total_items,
        		'page_no'=>$page_no,
                'real_page_no'=>$real_page_no,
        		'day'=>$this->day
        	)+$this->map
		);
	}
	function prase_layout_default($summary,$total_items)
    {
        $this->parse_layout('report',
    	$summary+
    		array(
    			'page_no'=>1,
                'real_page_no'=>1,
    			'day'=>$this->day,
    		)+$this->map
    	);
    }
	
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        //System::debug($arr);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
?>
