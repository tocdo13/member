<?php

class AlbawaterInvoiceReportForm extends Form
{
	function AlbawaterInvoiceReportForm()
	{
		Form::Form('AlbawaterInvoiceReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
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
       	$users = DB::fetch_all('select account.id,party.full_name from account INNER JOIN party on party.user_id = account.id AND party.type=\'USER\' WHERE account.type=\'USER\' ORDER BY account.id');
        $this->map['user_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list($users);
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
        if(Url::get('user_id')!='ALL' && Url::get('user_id')!='' )
        {
            
             $cond.=' AND vat_bill.print_user = \''.Url::get('user_id').'\'';
        }
        
       
        $cond .= ' 
					AND vat_bill.print_time >= \''.$from_time_view.'\'
                    AND vat_bill.print_time <= \''.$to_time_view.'\'  
				';
        $cond.=(Url::get('area_id')?' and ve_reservation.department_id = \''.Url::get('area_id').'\'':'');        
        //ĐẾM SỐ SẢN PHẨM
        $count_product = DB::fetch_all('
			SELECT 
				vat_bill.id
				,count(ve_reservation_product.id) as num
			FROM
				vat_bill
				inner join ve_reservation on vat_bill.id = ve_reservation.vat_code
                inner join ve_reservation_product on ve_reservation.id = ve_reservation_product.bar_reservation_id
            WHERE
				'.$cond.'             
			GROUP BY vat_bill.id
			');
        ///////////////////////
        //
        //////////////////////
        $sql = '
			SELECT 
                (ROW_NUMBER() OVER (ORDER BY vat_bill.id ))*2 as id,
                ve_reservation_product.id as res_product_id,
                vat_bill.id as code,
                vat_bill.code as number_vat,
                vat_bill.arrival_time as date_vat,
                ve_reservation_product.quantity,    
                ve_reservation_product.quantity_discount,
                ve_reservation_product.price,
                DECODE(ve_reservation_product.discount_rate,null,0,ve_reservation_product.discount_rate) as discount_rate,
                DECODE(ve_reservation_product.promotion,null,0,ve_reservation_product.promotion) as promotion,
                product.name_'.Portal::language().' as name,
                unit.name_'.Portal::language().' as unit,
                ve_reservation_product.product_id
			FROM 
			    ve_reservation_product
                inner join ve_reservation on ve_reservation.id = ve_reservation_product.bar_reservation_id  
                inner join vat_bill on ve_reservation.vat_code = vat_bill.id
                inner join product on product.id = ve_reservation_product.product_id
                inner join unit on product.unit_id = unit.id
            WHERE 
			    '.$cond.'
                 
			ORDER BY
			    vat_bill.id
			';                       
        $report = new Report;
        $report->items = DB::fetch_all($sql);
        
        $i=1;
        $res_id = false;
        foreach($report->items as $key=>$value)
		{  
		    if($value['code']!=$res_id)
            {
                $report->items[$key]['stt'] = $i++;
                $res_id = $value['code'];
  
            }
            $report->items[$key]['code'] = $value['code'];
            $report->items[$key]['quantity'] = $value['quantity'] - $value['quantity_discount'];
            $report->items[$key]['unit_price'] = $value['price'];
            $report->items[$key]['agent_discount'] = $report->items[$key]['unit_price']*$value['discount_rate']/100;
            $report->items[$key]['promotion'] = $report->items[$key]['unit_price']*$value['promotion']/100;
            $report->items[$key]['net_unit_price'] = $report->items[$key]['unit_price'] - $report->items[$key]['agent_discount'] - $report->items[$key]['promotion']  ;
            $report->items[$key]['amount'] = $report->items[$key]['quantity'] * $report->items[$key]['net_unit_price'];
            $report->items[$key]['date_vat'] = Date_time::convert_orc_date_to_date($value['date_vat']);
            $report->items[$key]['unit'] = $value['unit'];
            if($value['code']==$res_id)
            {
                if(!isset($count_product[$value['code']]['invoice_revenue']))
                {
                    $count_product[$value['code']]['invoice_revenue'] = $report->items[$key]['amount']; 
                } 
                else
                {
                    $count_product[$value['code']]['invoice_revenue'] += $report->items[$key]['amount'];
                }
                
                $count_product[$value['code']]['net_revenue'] = $count_product[$value['code']]['invoice_revenue']/1.1;
                $count_product[$value['code']]['vat']  = $count_product[$value['code']]['net_revenue']*0.1;
                
            }
            if($value['quantity_discount'] != 0)
            {
                $report->items[$key +1] = array();
                if($value['code']!=$res_id)
                {
                    $report->items[$key +1]['stt']  = $i++;
                    $res_id = $value['code'];
      
                }
                $report->items[$key +1]['code'] = $value['code']; 
                $report->items[$key +1]['quantity'] = $value['quantity_discount'];
                $report->items[$key +1]['number_vat'] = $value['number_vat'];
                $report->items[$key +1]['name'] = $value['name'];
                $report->items[$key +1]['product_id'] = $value['product_id'];
                $report->items[$key +1]['date_vat'] = Date_time::convert_orc_date_to_date($value['date_vat']);
                $report->items[$key +1]['unit_price'] = 0;
                $report->items[$key +1]['agent_discount'] = $report->items[$key +1]['unit_price']*$value['discount_rate']/100;
                $report->items[$key +1]['promotion'] = $report->items[$key +1]['unit_price']*$value['promotion']/100;
                $report->items[$key +1]['net_unit_price'] = 0  ;
                $report->items[$key +1]['amount'] = 0;
                $report->items[$key +1]['foc'] = 1;
                $report->items[$key +1]['unit'] = $value['unit'];
                $count_product[$value['code']]['num'] += 1;
            }
            $report->items[$key]['foc'] = 0;
        }
        ksort($report->items);
        //System::debug($report->items);
        $this->print_all_pages($report,$count_product);
        
    }
    function print_all_pages(&$report,$count_product)
	{
		$summary = array(
	        'product_count'=>0,
            'real_product_count'=>0,
            'total_page'=>1,
            'real_total_page'=>1,
            'line_per_page' =>999,
            'no_of_page' =>50,
            'start_page' =>1,
            'real_total_guest'=>0,
            'total_unit_price'=>0,
            'total_quantity'=>0,
            'total_agent_discount'=>0,
            'total_promotion'=>0,
            'total_net_unit_price'=>0,
            'total_amount'=>0,
            'total_invoice_revenue'=>0,
            'total_vat'=>0,
            'total_net_revenue'=>0,
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
            	$summary['product_count']++;
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
                //S? trang th?c s? sau khi l?c qua các yêu c?u
            	foreach($pages as $page_no=>$page)
            	{
            	   //System::debug($page);
                	foreach($page as $key=>$value)
                	{
                	   
                	   
                            //số sản phẩm thực sau khi lọc
                            $summary['real_product_count']++;
                            $summary['total_unit_price'] += $page[$key]['unit_price'];
                            $summary['total_quantity'] += $page[$key]['quantity'];
                            $summary['total_agent_discount'] += $page[$key]['agent_discount'];
                            $summary['total_promotion'] += $page[$key]['promotion'];
                            $summary['total_net_unit_price'] += $page[$key]['net_unit_price'];
                            $summary['total_amount'] += $page[$key]['amount'];
                            $summary['total_invoice_revenue'] += $count_product[$value['code']]['invoice_revenue']/$count_product[$value['code']]['num'];
                            $summary['total_vat'] += $count_product[$value['code']]['vat']/$count_product[$value['code']]['num'];
                            $summary['total_net_revenue'] += $count_product[$value['code']]['net_revenue']/$count_product[$value['code']]['num'];
                            if(isset($page[$key]['stt'])>1)
                            {
                                $summary['total_unit_price'] += $page[$key]['unit_price'];
                                $summary['total_quantity'] += $page[$key]['quantity'];
                                $summary['total_agent_discount'] += $page[$key]['agent_discount'];
                                $summary['total_promotion'] += $page[$key]['promotion'];
                                $summary['total_net_unit_price'] += $page[$key]['net_unit_price'];
                                $summary['total_amount'] += $page[$key]['amount'];
                                $summary['total_invoice_revenue'] += $count_product[$value['code']]['invoice_revenue']/$count_product[$value['code']]['num'];
                                $summary['total_vat'] += $count_product[$value['code']]['vat']/$count_product[$value['code']]['num'];
                                $summary['total_net_revenue'] += $count_product[$value['code']]['net_revenue']/$count_product[$value['code']]['num'];
                            }
                            
                          
                	} 
                    //System::debug($summary);
            	}
                $real_page_no = 1;
                foreach($pages as $page_no=>$page)
            	{
            		$this->print_page($page, $page_no , $real_page_no, $summary, $count_product);
                    $real_page_no++;
            	}
            }
            else
            {
                $this->prase_layout_default($summary,$count_product);
            }
        }
        else
        {
            $this->prase_layout_default($summary,$count_product);
        }
	}
    function print_page($items, $page_no,$real_page_no,$summary,$count_product)
	{
         $this->parse_layout('report',
        	$summary+
        	array(
        		'items'=>$items,
        		'page_no'=>$page_no,
                'real_page_no'=>$real_page_no,
        		'day'=>$this->day,
                'count_product'=>$count_product
        	)+$this->map
		);
	}
	function prase_layout_default($summary,$count_product)
    {
        $this->parse_layout('report',
    	$summary+
    		array(
    			'page_no'=>1,
                'real_page_no'=>1,
    			'day'=>$this->day,
                'count_product'=>$count_product
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