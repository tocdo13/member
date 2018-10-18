<?php
class TicketTypeReportForm extends Form
{
	function TicketTypeReportForm()
	{
		Form::Form('TicketTypeReportForm');
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
        
        $ticket_type = DB::fetch_all('select ticket.id,ticket.name from ticket ORDER BY ticket.id');
        $this->map['ticket_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list($ticket_type);
        
        $area_id = DB::fetch_all('select ticket_group.id,ticket_group.name from ticket_group ORDER BY ticket_group.id');
        $this->map['area_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list($area_id);
        
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
	
        if(Url::get('portal_id'))
        {
            $portal_id = Url::get('portal_id');
        }
        else
        {
            $portal_id = PORTAL_ID;
            $_REQUEST['portal_id'] = PORTAL_ID;                       
        }
        $from_time_view = Date_Time::to_time($this->map['from_date']);                                
        $to_time_view = Date_Time::to_time($this->map['to_date'])+86400;
        $cond =' 1 = 1 ';
        $cond1 = $cond;
        if($portal_id != 'ALL')
        {
            $cond.=' AND ticket_reservation.portal_id = \''.$portal_id.'\' '; 
        }
        if(Url::get('ticket_id')!='ALL' && Url::get('ticket_id')!='' )
        {
            
             $cond1.='AND ticket.id = \''.Url::get('ticket_id').'\'';
        }
        if(Url::get('area_id')!='ALL' && Url::get('ticket_id')!='')
        {
            
             $cond1.='AND ticket.ticket_group_id = \''.Url::get('area_id').'\'';
        }
        
        //Tìm kiếm trong ngày hnay
       
        $cond .= ' 
					AND ticket_reservation.time >= \''.$from_time_view.'\'
                    AND ticket_reservation.time <= \''.$to_time_view.'\'  
				';
		$count_type =DB::fetch_all('
			SELECT 
				ticket.id*2 as id
				,count(ticket.name) as num
			FROM
				ticket
            WHERE
			'.$cond1.'            
			GROUP BY ticket.id
			');
        ///////////////////////
        //Số vé bán ra
        //////////////////////
        $ticket =DB::fetch_all( '
			SELECT 
                ticket_invoice.id,
                ticket_invoice.ticket_id*2 as ticket_id,
                DECODE(ticket_reservation.discount_rate,null,0,ticket_reservation.discount_rate) as discount_rate,
                ticket_invoice.quantity,
                ticket_invoice.price,
               	DECODE(ticket_invoice.discount_quantity,null,0,ticket_invoice.discount_quantity) as discount_quantity,
                ticket_invoice.total,
                ticket.name
			FROM 
			    ticket_invoice
                inner join ticket_reservation on ticket_reservation.id = ticket_invoice.ticket_reservation_id  
                inner join ticket on ticket_invoice.ticket_id = ticket.id
            WHERE 
			    '.$cond.'  
			ORDER BY
			    ticket_invoice.id
			'); 
        //LOẠI VÉ       
        $report = new Report;
        $report->items = DB::fetch_all('
			SELECT 
				ticket.id * 2 as id, 
                ticket.name
			FROM
				ticket
            where
			'.$cond1.'');           
        $i=0;
        $res_id = false; 
        foreach($report->items as $key=>$value)
		{
		      
  		    $report->items[$key]['stt'] = $i++;  		    
            $report->items[$key]['quantity']=0;        
            $report->items[$key]['total_before_discount']=0;          
            $report->items[$key]['total_discount']=0;           
            $report->items[$key]['total_before_tax']=0;
            $report->items[$key]['tax_rate']=0; 
            $report->items[$key]['price']= 0;
            $report->items[$key]['foc']= 0;  
            if(empty($ticket))
            {
                $report->items[$key]['quantity']=0;        
                $report->items[$key]['total_before_discount']=0;          
                $report->items[$key]['total_discount']=0;           
                $report->items[$key]['total_before_tax']=0;
                $report->items[$key]['tax_rate']=0; 
                $report->items[$key]['foc']=0;
                $report->items[$key]['price']= 0;
            }
            else
            {
                foreach($ticket as $ke=>$val)
                { 
    				if($val['ticket_id'] == $value['id'])
                    {
                       $val['total_after_discount'] = $val['total'] - ($val['total']*$val['discount_rate']/100);
                       $val['total_before_tax'] = $val['total_after_discount']/1.1;
                       $val['tax_rate'] =  $val['total_after_discount'] - $val['total_before_tax'];
                       $report->items[$key]['quantity']+= ($val['quantity']-$val['discount_quantity']);
                       $report->items[$key]['price']= $val['price'];                      
                       $report->items[$key]['total_before_discount'] += ($val['quantity'] - $val['discount_quantity'])*$val['price'];                     
                       //$report->items[$key]['total_discount'] += ($report->items[$key]['total_before_discount'])-($val['total']);
                       $report->items[$key]['total_discount'] += ($val['quantity']-$val['discount_quantity'])*$val['price'] - $val['total'];      
                       $report->items[$key]['total_before_tax'] += $val['total']/1.1;
                       $report->items[$key]['tax_rate'] += $val['total']/11;
                       
                       $report->items[$key]['name'] = $value['name'];
                       $report->items[$key]['foc']=0; 
                       if($val['discount_quantity'] > 0)
                       {
                           $report->items[$key + 1] = array();
                           if(!isset($report->items[$key + 1]['quantity']))
                           {
                                $report->items[$key + 1]['quantity'] = 0;
                           }
                           $report->items[$key + 1]['id']= $value['id'];
                           $report->items[$key + 1]['quantity']+= $val['discount_quantity'];
                           $report->items[$key + 1]['price']= 0;
                           $report->items[$key + 1]['total_before_discount']=0;
                           $report->items[$key + 1]['total_before_tax']=0;
                           $report->items[$key + 1]['tax_rate']=0; 
                           $report->items[$key + 1]['total_discount']=0;
                           
                           $report->items[$key + 1]['name'] = $value['name'];
                           $report->items[$key + 1]['foc']=1;
                           $count_type[$value['id']]['num'] =2;       
                       }
                    }
    			}
            }
            
	
        }
        ksort($report->items);
        //System::debug($report->items);
        $this->print_all_pages($report,$count_type);

	}
	function print_all_pages(&$report,$count_type)
	{
		$summary = array(
	        'ticket_count'=>0,
            'real_ticket_count'=>0,
            'total_page'=>1,
            'real_total_page'=>1,
            'line_per_page' =>999,
            'no_of_page' =>50,
            'start_page' =>1,
            'total_discount_total'=>0,
            'total_quantity'=>0,
            'total_ticket_total'=>0,
            'total_ticket_total_before_tax'=>0,
            'total_tax_rate'=>0,
        );
        $summary['line_per_page'] = URL::get('line_per_page',999);
        
        $count = 0;
        $pages = array();          
        
        //duyet qua tung ban ghi      
        foreach($report->items as $key=>$item)
        {
            if(isset($report->items[$key]['stt']))
            {
                //echo $report->items[$key]['stt'];
                //Đếm số khách
            	$summary['ticket_count']++;
                $count+= 1;
            }
            //count >= so dong tren 1 trang thi reset ve 0 va tang so trang len 1
            if($count>$summary['line_per_page'])
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
            //Sau khi lọc mà còn dữ liệu
            if(!empty($pages))
            {
                $summary['real_total_page']=count($pages);
                //Số trang thực sự sau khi lọc qua các yêu cầu
            	foreach($pages as $page_no=>$page)
            	{
            	   //System::debug($page);
                	foreach($page as $key=>$value)
                	{
                	   
                	   
                            //Số khách thực sự sau khi lọc qua các yêu cầu
                            $summary['real_ticket_count']++;
                            $summary['total_quantity'] += $page[$key]['quantity'];
                            $summary['total_ticket_total_before_tax'] += $page[$key]['total_before_tax'];
                            $summary['total_ticket_total'] += $page[$key]['total_before_discount'];
                            $summary['total_tax_rate'] += $page[$key]['tax_rate'];
                            $summary['total_discount_total'] += $page[$key]['total_discount'];
                         
                	} 
            	}
                $real_page_no = 1;
                foreach($pages as $page_no=>$page)
            	{
            		$this->print_page($page, $page_no , $real_page_no, $summary, $count_type);
                    $real_page_no++;
            	}
            }
            else
            {
                $this->prase_layout_default($summary, $count_type);
            }
        }
        else
        {
            $this->prase_layout_default($summary, $count_type);
        }
	}
	function print_page($items, $page_no,$real_page_no,$summary, $count_type)
	{
         $this->parse_layout('report',
        	$summary+
        	array(
        		'items'=>$items,
        		'page_no'=>$page_no,
                'real_page_no'=>$real_page_no,
        		'day'=>$this->day,
                'count_type'=>$count_type
        	)+$this->map
		);
	}
	function prase_layout_default($summary,$count_type)
    {
        $this->parse_layout('report',
    	$summary+
    		array(
    			'page_no'=>1,
                'real_page_no'=>1,
    			'day'=>$this->day,
                'count_type'=>$count_type
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