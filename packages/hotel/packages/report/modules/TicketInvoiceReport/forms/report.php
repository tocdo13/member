<?php
class TicketInvoiceReportForm extends Form
{
	function TicketInvoiceReportForm()
	{
		Form::Form('TicketInvoiceReportForm');
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
       	$users = DB::fetch_all('select account.id,party.full_name from account INNER JOIN party on party.user_id = account.id AND party.type=\'USER\' WHERE account.type=\'USER\' ORDER BY account.id');
        $this->map['user_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list($users);
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
        $from_time_view = Date_Time::to_time($this->map['from_date']) + $from_time;                                
        $to_time_view = Date_Time::to_time($this->map['to_date']) + $to_time;
        $cond =' 1 = 1 ';
        if($portal_id != 'ALL')
        {
            $cond.=' AND ticket_reservation.portal_id = \''.$portal_id.'\' '; 
        }
        if(Url::get('user_id')!='ALL' && Url::get('user_id')!='' )
        {
            
             $cond.=' AND ticket_reservation.user_id = \''.Url::get('user_id').'\'';
        }
        //Tìm kiếm trong ngày hnay
       
        $cond .= ' 
					AND ticket_reservation.time >= \''.$from_time_view.'\'
                    AND ticket_reservation.time <= \''.$to_time_view.'\'  
				';
        //ĐẾM SỐ VÉ
        $count_ticket = DB::fetch_all('
			SELECT 
				ticket_reservation.id
				,count(ticket_invoice.id) as num
			FROM
				ticket_reservation
				inner join ticket_invoice on ticket_invoice.ticket_reservation_id = ticket_reservation.id
            WHERE
				'.$cond.'             
			GROUP BY ticket_reservation.id
			');
        $bill_id = '0';
//System::debug($count_ticket);
        foreach($count_ticket as $ke=>$val)
        {
            $bill_id .=','.$val['id'];
        }                  
		///////////////////////
        //
        //////////////////////
        $sql = '
			SELECT 
                (ROW_NUMBER() OVER (ORDER BY ticket_reservation.id ))*2 as id,
                ticket_invoice.id as ticket_invoice_id,
                ticket_reservation.id as code,
                ticket_reservation.discount_rate,
                ticket_invoice.quantity,    
                ticket_invoice.discount_quantity,
                ticket_invoice.total as ticket_total,
                ticket_reservation.total as invoice_total,
                ticket_reservation.reservation_room_id,
                ticket.name,
                ticket_reservation.amount_pay_with_room
			FROM 
			    ticket_reservation
                inner join ticket_invoice on ticket_reservation.id = ticket_invoice.ticket_reservation_id  
                inner join ticket on ticket_invoice.ticket_id = ticket.id
            WHERE 
			    '.$cond.' 
			ORDER BY
			    ticket_invoice.id
			';                       
        $report = new Report;
        $report->items = DB::fetch_all($sql);
        $payments = $this->get_payments($bill_id);
        $i=0;
        $res_id = false;
        foreach($report->items as $key=>$value)
		{
		    if($value['code']!=$res_id)
            {
                $report->items[$key]['stt'] = $i++;
                $res_id = $value['code'];
            }
            $total_paid = 0; $total_debit = 0;
			$report->items[$key]['room'] = 0;
            $report->items[$key]['credit_card'] = 0;
            $report->items[$key]['bank'] = 0;
            $report->items[$key]['cash'] = 0;
            $report->items[$key]['deposit'] = 0;
            if($value['discount_rate']!='')
            {
               $report->items[$key]['ticket_total'] = $value['ticket_total'] - $value['ticket_total']*$value['discount_rate']/100;
               $report->items[$key]['ticket_total_before_tax'] = $report->items[$key]['ticket_total']/1.1; 
               $report->items[$key]['tax_rate'] =  $report->items[$key]['ticket_total'] - $report->items[$key]['ticket_total_before_tax'];
            }
            else
            {
               $report->items[$key]['ticket_total_before_tax'] = $value['ticket_total']/1.1; 
               $report->items[$key]['tax_rate'] =  $value['ticket_total'] - $report->items[$key]['ticket_total_before_tax'];
            }
			//$report->items[$key]['note'].= $value['room_name']?'Room name: '.$value['room_name']:'';
		    $report->items[$key]['reservation_room_id'] = $value['reservation_room_id'];
            if($value['discount_quantity'] != '')
            {
                $report->items[$key.'_'] = array();
                $report->items[$key.'_']['bank'] = 0;
                $report->items[$key.'_']['cash'] = 0;
                $report->items[$key.'_']['credit_card'] = 0;
                $report->items[$key.'_']['room'] = 0;
                $report->items[$key.'_']['deposit'] = 0;
                $report->items[$key.'_']['quantity'] = $value['discount_quantity'];
                $report->items[$key.'_']['code'] = $value['code'];
                $report->items[$key.'_']['name'] = $value['name'];
                $report->items[$key.'_']['ticket_total_before_tax'] = 0;
                $report->items[$key.'_']['tax_rate'] = 0;
                $report->items[$key.'_']['ticket_total'] = 0;
                $report->items[$key.'_']['invoice_total'] = $value['invoice_total'];
                $report->items[$key.'_']['foc'] = 1;
                $report->items[$key.'_']['reservation_room_id']=$value['reservation_room_id'];
                $report->items[$key]['quantity'] = $value['quantity'] -$value['discount_quantity'];
                $count_ticket[$value['code']]['num'] += 1;
            }
            $report->items[$key]['foc'] = 0;
            if($value['reservation_room_id']>0)
            {
				$report->items[$key]['room'] += $value['amount_pay_with_room'];
                if($value['discount_quantity']>0)
                {
                    $report->items[$key.'_']['room']+= $value['amount_pay_with_room'];
                }	
				$total_paid += $value['amount_pay_with_room'];
                
            }	
            foreach($payments as $k=>$pay)
            {
				if($pay['bill_id'] == $value['code'])
                {
                    if($pay['type_dps']=='TICKET')
                    {
                       $report->items[$key]['deposit']+=$pay['total_vnd']; 
                       if($value['discount_quantity'] != '')
                       {
                            $report->items[$key.'_']['deposit'] += $pay['total_vnd'];
                       }
                    }
                    else
                    {
        				if($pay['payment_type_id']=='DEBIT')
                        {
        					$total_debit += $pay['total_vnd'];
        				}
                        else if($pay['payment_type_id']=='CREDIT_CARD')
                        {
        					$report->items[$key]['credit_card'] += $pay['total_vnd'];
                            if($value['discount_quantity'] != '')
                            {
                                $report->items[$key.'_']['credit_card'] += $pay['total_vnd'];
                            }
        				}
                        else if($pay['payment_type_id']=='BANK')
                        {
        				     $report->items[$key]['bank'] += $pay['total_vnd'];
                             if($value['discount_quantity'] != '')
                             {
                                $report->items[$key.'_']['bank'] += $pay['total_vnd'];
                             }        
        				}
                        
                        else if($pay['payment_type_id']=='CASH')
                        {
                            
        					$report->items[$key]['cash'] += $pay['total_vnd'];
                            if($value['discount_quantity'] != '')
                             {
                                $report->items[$key.'_']['cash'] += $pay['total_vnd'];
                             }
                            	
        				}
        				$total_paid += $pay['total_vnd'];
                    }	
				}
			}
            $report->items[$key]['debit'] = (int)($total_debit + round($value['invoice_total'] - $total_paid - $report->items[$key]['deposit']));
            if($value['discount_quantity'] != '')
            {
                $report->items[$key.'_']['debit'] = (int)($total_debit + round($value['invoice_total'] - $total_paid -$report->items[$key]['deposit'] ));
            }
			
        }
        ksort($report->items);
        //System::debug($report->items);
		$all_currencies = DB::fetch_all('select * from currency where allow_payment=1');
		$credit_card = DB::fetch_all('select * from credit_card');
        
        
	
        $this->print_all_pages($report,$count_ticket);

	}
	function print_all_pages(&$report,$count_ticket)
	{
		$summary = array(
	        'ticket_count'=>0,
            'real_ticket_count'=>0,
            'total_page'=>1,
            'real_total_page'=>1,
            'line_per_page' =>999,
            'no_of_page' =>50,
            'start_page' =>1,
            'real_total_guest'=>0,
            'total_price'=>0,
            'total_quantity'=>0,
            'total_ticket_total'=>0,
            'total_ticket_total_before_tax'=>0,
            'total_tax_rate'=>0,
            'total_invoice_total'=>0,
            'total_room_total'=>0,
            'total_credit_total'=>0,
            'total_cash_total'=>0,
            'total_bank_total'=>0,
            'total_deposit_total'=>0,
            'total_debit_total'=>0,
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
                            $summary['total_price'] += $page[$key]['invoice_total'];
                            $summary['total_quantity'] += $page[$key]['quantity'];
                            $summary['total_ticket_total_before_tax'] += $page[$key]['ticket_total_before_tax'];
                            $summary['total_ticket_total'] += $page[$key]['ticket_total'];
                            $summary['total_tax_rate'] += $page[$key]['tax_rate'];
                            if(isset($page[$key]['stt']))
                            {
                                $summary['total_invoice_total']+= $page[$key]['invoice_total'];
                                $summary['total_room_total'] += $page[$key]['room'];
                                $summary['total_credit_total'] += $page[$key]['credit_card'];
                                $summary['total_cash_total'] += $page[$key]['cash'];
                                $summary['total_debit_total'] += $page[$key]['debit']; 
                                $summary['total_deposit_total'] += $page[$key]['deposit'];
                                $summary['total_bank_total'] += $page[$key]['bank'];
                            }
                            
                          
                	} 
            	}
                $real_page_no = 1;
                foreach($pages as $page_no=>$page)
            	{
            		$this->print_page($page, $page_no , $real_page_no, $summary, $count_ticket);
                    $real_page_no++;
            	}
            }
            else
            {
                $this->prase_layout_default($summary,$count_ticket);
            }
        }
        else
        {
            $this->prase_layout_default($summary,$count_ticket);
        }
	}
	function print_page($items, $page_no,$real_page_no,$summary,$count_ticket)
	{
         $all_payments = DB::fetch_all('select payment_type.*,payment_type.name_'.Portal::language().' as name from payment_type where def_code is not null and payment_type.def_code != \'FOC\' and payment_type.def_code != \'ROOM CHARGE\' and payment_type.def_code != \'REFUND\' and payment_type.def_code != \'THE\'');  
		 //system::Debug($all_payments);
         $report->payment_types = $all_payments;
        
         $this->parse_layout('report',
        	$summary+
        	array(
        		'items'=>$items,
                'payment_types'=>$report->payment_types,
        		'page_no'=>$page_no,
                'real_page_no'=>$real_page_no,
        		'day'=>$this->day,
                'count_ticket'=>$count_ticket
        	)+$this->map
		);
	}
	function prase_layout_default($summary,$count_ticket)
    {
        $this->parse_layout('report',
    	$summary+
    		array(
    			'page_no'=>1,
                'real_page_no'=>1,
    			'day'=>$this->day,
                'count_ticket'=>$count_ticket
    		)+$this->map
    	);
    }
	
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        //System::debug($arr);
        return $arr[0]*3600 + $arr[1]*60;
    }

    function get_payments($bill_id){
		
		return $payments = DB::fetch_all('
						SELECT 
							(payment.payment_type_id || \'_\' || payment.credit_card_id || \'_\' || payment.currency_id || \'_\' || payment.bill_id || \'_\' || payment.type_dps) as id
							,SUM(amount) as total
							,SUM(amount*payment.exchange_rate) as total_vnd
							,CONCAT(payment.payment_type_id,CONCAT(\'_\',payment.currency_id)) as name
							,payment.bill_id
							,payment.payment_type_id
							,payment.credit_card_id
							,payment.currency_id
							,payment.type_dps
						FROM payment
							inner join ticket_reservation on payment.bill_id = ticket_reservation.id
						WHERE 
							payment.bill_id in ('.$bill_id.') AND payment.type=\'TICKET\'
						GROUP BY payment.payment_type_id,payment.currency_id,payment.bill_id
						,payment.currency_id,payment.type_dps,payment.credit_card_id
				');	
	}
}
?>