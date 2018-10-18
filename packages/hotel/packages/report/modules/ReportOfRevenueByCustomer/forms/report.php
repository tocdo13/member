<?php
class ReportOfRevenueByCustomerForm extends Form
{
	function ReportOfRevenueByCustomerForm()
	{
		Form::Form('ReportOfRevenueByCustomerForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    	$this->link_js('packages/core/includes/js/jquery/datepicker.js');   
        $this->link_js('packages/core/includes/js/jquery/chart/highcharts.src.js');
		$this->link_js('packages/core/includes/js/jquery/chart/exporting.js');  
	}
	function draw()
	{
        require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
        require_once 'packages/hotel/packages/vending/includes/php/vending.php';
        $area = get_area_vending();
        $this->map['area_id_list']=array(''=>Portal::language('All'))+String::get_list($area);
        $this->day = date('d/m/Y');
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
        $_REQUEST['from_date'] = $this->map['from_date'];
         $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        $_REQUEST['to_date'] = $this->map['to_date'];
        
        $day_orc = Date_Time::to_orc_date(date('d/m/Y'));
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
        $cond .= ' 
					AND ve_reservation.time >= \''.$from_time_view.'\'
                    AND ve_reservation.time <= \''.$to_time_view.'\'
                    AND ve_reservation.portal_id = \''.PORTAL_ID.'\'
				'
                .(Url::get('area_id')?' and ve_reservation.department_id = \''.Url::get('area_id').'\'':'');
        //T�m ki?m trong ng�y hnay
       
        //�?M S? V�
        $count_product_by_group = DB::fetch_all('
                select 
                    vending_customer_group.id
                    ,count(ve_reservation_product.bar_reservation_id) as num_product_by_group
                from 
                    vending_customer_group
                    inner join vending_customer on vending_customer.group_id = vending_customer_group.id
                    inner join ve_reservation on ve_reservation.customer_id = vending_customer.id
                    inner join ve_reservation_product on ve_reservation.id = ve_reservation_product.bar_reservation_id
                    where
                        '.$cond.'
                        and ve_reservation.agent_name is not null
                        and ve_reservation.customer_id is not null
                    group by vending_customer_group.id 
            ');
        $count_product_by_customer = DB::fetch_all('
                select 
                    vending_customer.id
                    ,count(ve_reservation_product.bar_reservation_id) as num_product_by_customer
                from 
                    vending_customer 
                    inner join ve_reservation on ve_reservation.customer_id = vending_customer.id
                    inner join ve_reservation_product on ve_reservation_product.bar_reservation_id = ve_reservation.id 
                where
                    '.$cond.'
                    and ve_reservation.agent_name is not null
                    and ve_reservation.customer_id is not null
                group by vending_customer.id
            ');
            //System::debug($count_product_by_customer);
        //$group_id = '0';
        //foreach($count_product_by_group as $ke=>$val)
//        {
//            $group_id .=','.$val['name'];
//        }                  
		///////////////////////
        //
        //////////////////////
        $sql = '
			select
                            ve_reservation_product.id as id
                            ,ve_reservation_product.product_id
                            ,ve_reservation_product.name as product_name
                            ,ve_reservation_product.price as price
                            ,unit.name_1
                            ,ve_reservation_product.price_id
                            ,(ve_reservation_product.quantity - ve_reservation_product.quantity_discount) as quantity
                            ,((ve_reservation_product.quantity - ve_reservation_product.quantity_discount)*ve_reservation_product.price ) -((ve_reservation_product.quantity - ve_reservation_product.quantity_discount)*ve_reservation_product.price )*ve_reservation_product.promotion/100 - ((ve_reservation_product.quantity - ve_reservation_product.quantity_discount)*ve_reservation_product.price )*ve_reservation_product.discount_rate/100 as total_revenue
                            ,(((ve_reservation_product.quantity - ve_reservation_product.quantity_discount)*ve_reservation_product.price ) -((ve_reservation_product.quantity - ve_reservation_product.quantity_discount)*ve_reservation_product.price )*ve_reservation_product.promotion/100 - ((ve_reservation_product.quantity - ve_reservation_product.quantity_discount)*ve_reservation_product.price )*ve_reservation_product.discount_rate/100)*100/(100+ve_reservation.tax_rate) as total_before_tax
                            ,ve_reservation_product.quantity_discount
                            ,ve_reservation_product.discount_rate
                            ,ve_reservation_product.discount
                            ,((((ve_reservation_product.quantity - ve_reservation_product.quantity_discount)*ve_reservation_product.price ) -((ve_reservation_product.quantity - ve_reservation_product.quantity_discount)*ve_reservation_product.price )*ve_reservation_product.promotion/100 - ((ve_reservation_product.quantity - ve_reservation_product.quantity_discount)*ve_reservation_product.price )*ve_reservation_product.discount_rate/100)*100/(100+ve_reservation.tax_rate))*(ve_reservation.tax_rate/100) as VAT
                            ,vending_customer.id as customer_id
                            ,vending_customer.name as customer_name
                            ,vending_customer.code as customer_code
                            ,vending_customer_group.name as group_name
                            ,vending_customer_group.id as group_id
                            ,ve_reservation.discount_percent as discount_percent
                            ,ve_reservation.total as total_customer
                        from 
                            ve_reservation_product
                            inner join ve_reservation on ve_reservation_product.bar_reservation_id = ve_reservation.id
                            inner join vending_customer on ve_reservation.customer_id = vending_customer.id
                            inner join vending_customer_group on vending_customer.group_id = vending_customer_group.id
                            inner join unit on ve_reservation_product.unit_id = unit.id
                        where
                            '.$cond.'
                            and ve_reservation.agent_name is not null
                            and ve_reservation.customer_id is not null
                        order by vending_customer_group.id  ASC,vending_customer.id asc
			';                       
        $report = new Report;
        $report->items = DB::fetch_all($sql);
        $i=0;
        $grand_total_customer = array();
        $res_id = false;
        foreach($report->items as $k=>$v)
        {
            if(isset($grand_total_customer[$v['customer_id']]['total_cus']))
            {
               $grand_total_customer[$v['customer_id']]['total_cus'] += ($v['total_revenue']);
            }
            else
            {
                $grand_total_customer[$v['customer_id']]['total_cus'] = ($v['total_revenue']);
            }
        }
        //System::debug( $grand_total_customer);
        foreach($report->items as $key=>$value)
		{
            if($value['quantity_discount'] >0)
            {
                $report->items[$key.'_'] = array();
                $report->items[$key.'_']['price'] = 0;
                $report->items[$key]['quantity'] = $value['quantity'] ;
                $report->items[$key.'_']['quantity'] = $value['quantity_discount'];
                $report->items[$key.'_']['product_id'] = $value['product_id'];
                $report->items[$key.'_']['product_name'] = $value['product_name'];
                $report->items[$key.'_']['customer_id'] = $value['customer_id'];
                $report->items[$key.'_']['customer_name'] = $value['customer_name'];
                $report->items[$key.'_']['customer_code'] = $value['customer_code'];
                $report->items[$key.'_']['name_1'] = $value['name_1'];
                $report->items[$key.'_']['total_before_tax'] = 0;
                $report->items[$key.'_']['vat'] = 0;
                $report->items[$key.'_']['total_revenue'] = 0;
                $report->items[$key.'_']['discount_percent'] = 0;
                $report->items[$key.'_']['group_name'] = $value['group_name'];
                $report->items[$key.'_']['group_id'] = $value['group_id'];
                $report->items[$key.'_']['foc'] = 1;
                $report->items[$key.'_']['total_customer'] = $value['total_customer'];
                $count_product_by_group[$value['group_id']]['num_product_by_group'] += 1;
                $count_product_by_customer[$value['customer_id']]['num_product_by_customer'] += 1;
            }
            $report->items[$key]['foc'] = 0;
        }
        //System::debug($sql);
        //System::debug($report->items);
        //ksort($report->items);
        
        $grand_total = array();
        foreach($report->items as $k=>$v)
        {
            if(isset($grand_total[$v['group_id']]['total_gr']))
            {
                $grand_total[$v['group_id']]['total_gr'] += ($v['total_revenue']);
                $grand_total[$v['group_id']]['name'] = $v['group_name'];
            }
            else
            {
                $grand_total[$v['group_id']]['total_gr'] = ($v['total_revenue']);
                $grand_total[$v['group_id']]['name'] = $v['group_name'];
            }
        }
        
        //System::debug($grand_total);
		//$all_currencies = DB::fetch_all('select * from currency where allow_payment=1');
		//$credit_card = DB::fetch_all('select * from credit_card');
	
        $this->print_all_pages($report,$count_product_by_group,$count_product_by_customer,$grand_total,$grand_total_customer);

	}
	function print_all_pages(&$report,$count_product_by_group,$count_product_by_customer,$grand_total,$grand_total_customer)
	{
		$summary = array(
	        'customer_count'=>0,
            'real_customer_count'=>0,
            'total_page'=>1,
            'real_total_page'=>1,
            'line_per_page' =>999,
            'no_of_page' =>50,
            'start_page' =>1,
            'real_total_guest'=>0,
            'total_price'=>0,
            'total_quantity'=>0,
            'total_revenue'=>0,
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
                //�?m s? kh�ch
            	$summary['customer_count']++;
                $count+= 1;
            }
            //count >= so dong tren 1 trang thi reset ve 0 va tang so trang len 1
            if($count>$summary['line_per_page'])
        	{
        		$count = 1;
        		$summary['total_page']++;
        	}
            //In ra b?t d?u t? trang s? 1
            $pages[$summary['total_page']][$key] = $item;	
        }
        
        //N?u c� d? li?u t? c�u truy v?n
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
                //lay ra trang bat dau dc in (neu muon xem tu trang 5 th� se tra ve 5)
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
            //Sau khi l?c m� c�n d? li?u
            if(!empty($pages))
            {
                $summary['real_total_page']=count($pages);
                //S? trang th?c s? sau khi l?c qua c�c y�u c?u
                $all_customer = array();
            	foreach($pages as $page_no=>$page)
            	{
            	    
            	   //System::debug($page);
                	foreach($page as $key=>$value)
                	{
                	   
                	        $all_customer[$value['customer_id']]['name'] = $value['customer_name'];
                            $all_customer[$value['customer_id']]['total'] = $value['total_customer'];
                            //S? kh�ch th?c s? sau khi l?c qua c�c y�u c?u
                            $summary['real_customer_count']++;
                            //$summary['total_price'] += $page[$key]['invoice_total'];
                            //$summary['total_quantity'] += $page[$key]['quantity'];
                            //$summary['total_ticket_total_before_tax'] += $page[$key]['ticket_total_before_tax'];
                            $summary['total_revenue'] += $page[$key]['total_revenue'];
                            //$summary['total_tax_rate'] += $page[$key]['tax_rate'];
                            //if(isset($page[$key]['stt']))
//                            {
//                                //$summary['total_invoice_total']+= $page[$key]['invoice_total'];
//                                //$summary['total_room_total'] += $page[$key]['room'];
//                                //$summary['total_credit_total'] += $page[$key]['credit_card'];
////                                $summary['total_cash_total'] += $page[$key]['cash'];
////                                $summary['total_debit_total'] += $page[$key]['debit']; 
////                                $summary['total_deposit_total'] += $page[$key]['deposit'];
////                                $summary['total_bank_total'] += $page[$key]['bank'];
//                            }
                	} 
            	}
                $real_page_no = 1;
                //System::debug($all_customer);
                foreach($pages as $page_no=>$page)
            	{
            		$this->print_page($all_customer, $page, $page_no , $real_page_no, $summary, $count_product_by_group,$count_product_by_customer,$grand_total,$grand_total_customer);
                    $real_page_no++;
            	}
            }
            else
            {
                $this->prase_layout_default($summary,$count_product_by_group,$count_product_by_customer,$grand_total,$grand_total_customer);
            }
        }
        else
        {
            $this->prase_layout_default($summary,$count_product_by_group,$count_product_by_customer,$grand_total,$grand_total_customer);
        }
	}
	function print_page($all_customer, $items, $page_no,$real_page_no,$summary,$count_product_by_group,$count_product_by_customer,$grand_total,$grand_total_customer)
	{
         $all_payments = DB::fetch_all('select payment_type.*,payment_type.name_'.Portal::language().' as name from payment_type where def_code is not null and payment_type.def_code != \'FOC\' and payment_type.def_code != \'ROOM CHARGE\'');  
		 $report->payment_types = $all_payments;
         $this->parse_layout('report',
        	$summary+
        	array(
                'all_customer'=>$all_customer,
                'items'=>$items,
                'payment_types'=>$report->payment_types,
        		'page_no'=>$page_no,
                'real_page_no'=>$real_page_no,
        		'day'=>$this->day,
                'count_product_by_group'=>$count_product_by_group,
                'count_product_by_customer'=>$count_product_by_customer,
                'grand_total'=>$grand_total,
                'grand_total_customer'=>$grand_total_customer
        	)+$this->map
		);
	}
	function prase_layout_default($summary,$count_product_by_group,$count_product_by_customer,$grand_total,$grand_total_customer)
    {
        $this->parse_layout('report',
    	$summary+
    		array(
    			'page_no'=>1,
                'real_page_no'=>1,
    			'day'=>$this->day,
                'count_product_by_group'=>$count_product_by_group,
                'count_product_by_customer'=>$count_product_by_customer,
                'grand_total'=>$grand_total,
                'grand_total_customer'=>$grand_total_customer
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