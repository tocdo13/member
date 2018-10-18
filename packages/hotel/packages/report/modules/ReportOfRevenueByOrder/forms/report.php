<?php
class ReportOfRevenueByOrderForm extends Form
{
	function ReportOfRevenueByOrderForm()
	{
		Form::Form('ReportOfRevenueByOrderForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
    
	function draw()
	{
	    require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
         require_once 'packages/hotel/packages/vending/includes/php/vending.php';
        $area = get_area_vending();
        $this->map['area_id_list']=array(''=>Portal::language('All'))+String::get_list($area);
        $this->day = date('d/m/Y');
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
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
					AND ve_reservation.time >= \''.$from_time_view.'\'
                    AND ve_reservation.time <= \''.$to_time_view.'\' 
				'
                .(Url::get('area_id')?' and ve_reservation.department_id = \''.Url::get('area_id').'\'':'');
        //ĐẾM SỐ SẢN PHẨM
        $count_product = DB::fetch_all('
			SELECT
                ve_reservation.id,
                count(VE_RESERVATION_PRODUCT.PRODUCT_ID) as num 
                from ve_reservation
                inner join VE_RESERVATION_PRODUCT on ve_reservation.id = VE_RESERVATION_PRODUCT.BAR_RESERVATION_ID
                WHERE
				'.$cond.' 
                GROUP BY ve_reservation.id 
                order by ve_reservation.id
			');
        ///////////////////////
        //
        //////////////////////
        $sql = '
			SELECT
                    
                    ve_reservation_product.id as id,
                    ve_reservation.time,
                    ve_reservation.code,
                    ve_reservation.id as ve_reservation_id,
                    ve_reservation_product.PRODUCT_ID as product_id,
                    ve_reservation_product.quantity_discount as foc,
                    ve_reservation_product.name,
                    unit.name_1,
                    ve_reservation_product.quantity,
                    ve_reservation_product.price,
                    ve_reservation_product.discount,
                    ve_reservation_product.discount_rate,
                    ve_reservation_product.promotion,
                    ve_reservation.DISCOUNT_PERCENT,
                    ve_reservation.time as create_date,
                    DECODE(
                        ve_reservation.agent_name, \'\',ve_reservation.receiver_name,
                                                    ve_reservation.agent_name 
                    ) as agent_name,
					ROW_NUMBER() OVER (ORDER BY ve_reservation.id desc ) as rownumber,
                    ve_reservation.department_id
				FROM 
                    ve_reservation_product
                    inner join VE_RESERVATION on VE_RESERVATION.id=VE_RESERVATION_PRODUCT.BAR_RESERVATION_ID
                    left join unit on ve_reservation_product.unit_id = unit.id
				WHERE 
                    '.$cond.'
                ORDER BY
					ve_reservation_product.id
			';                       
        $report = new Report;
        $report->items = DB::fetch_all($sql);
 
        $i=1;
        $res_id = false;
        foreach($report->items as $key=>$value)
		{  
		    if($value['ve_reservation_id']!=$res_id)
            {
                $report->items[$key]['stt'] = $i++;
                $res_id = $value['ve_reservation_id'];
  
            }
            $report->items[$key]['code'] = $value['code'];
            $report->items[$key]['quantity'] = $value['quantity'] - $value['foc'];
            $report->items[$key]['unit_price'] = $value['price'];
            $report->items[$key]['agent_discount'] = $report->items[$key]['unit_price']*$value['discount_rate']/100;
            $report->items[$key]['promotion'] = $report->items[$key]['unit_price']*$value['promotion']/100;
            $report->items[$key]['net_unit_price'] = $report->items[$key]['unit_price'] - $report->items[$key]['agent_discount'] - $report->items[$key]['promotion']  ;
            $report->items[$key]['amount'] = $report->items[$key]['quantity'] * $report->items[$key]['net_unit_price'];
            $report->items[$key]['time'] =$value['time'];
            if($value['ve_reservation_id']==$res_id)
            {
                if(!isset($count_product[$value['ve_reservation_id']]['invoice_revenue']))
                {
                    $count_product[$value['ve_reservation_id']]['invoice_revenue'] = $report->items[$key]['amount']; 
                } 
                else
                {
                    $count_product[$value['ve_reservation_id']]['invoice_revenue'] += $report->items[$key]['amount'];
                }
                
                $count_product[$value['ve_reservation_id']]['net_revenue'] = round($count_product[$value['ve_reservation_id']]['invoice_revenue']/1.1);
                $count_product[$value['ve_reservation_id']]['vat']  = round($count_product[$value['ve_reservation_id']]['net_revenue']*0.1);
                
            }
            if($value['foc'] != 0)
            {
                $report->items[$key +1] = array();
                if($value['ve_reservation_id']!=$res_id)
                {
                    $report->items[$key +1]['stt']  = $i++;
                    $res_id = $value['ve_reservation_id'];
      
                }
                $report->items[$key +1]['ve_reservation_id'] = $value['ve_reservation_id']; 
                $report->items[$key +1]['quantity'] = $value['foc'];
                $report->items[$key +1]['code'] = $value['code'];
                $report->items[$key +1]['name'] = $value['name'];
                $report->items[$key +1]['name_1'] = $value['name_1'];
                $report->items[$key +1]['product_id'] = $value['product_id'];
                $report->items[$key +1]['time'] =$value['time'];
                $report->items[$key +1]['unit_price'] = 0;
                $report->items[$key +1]['agent_discount'] = $report->items[$key +1]['unit_price']*$value['discount_rate']/100;
                $report->items[$key +1]['promotion'] = $report->items[$key +1]['unit_price']*$value['promotion']/100;
                $report->items[$key +1]['net_unit_price'] = 0  ;
                $report->items[$key +1]['amount'] = 0;
                $report->items[$key +1]['foc'] = 1;
                $count_product[$value['ve_reservation_id']]['num'] += 1;
            }
            $report->items[$key]['foc'] = 0;
        }
        ksort($report->items);
        
        $this->print_all_pages($report,$count_product);
        
    }
    function print_all_pages(&$report,$count_product)
	{
		
        $count = 0;
        $total_page = 1;
        $pages = array();          
      
        //duyet qua tung ban ghi      
        foreach($report->items as $key=>$item)
        {
            if(isset($report->items[$key]['stt']))
            {
                $count+= 1;
            }
            //count >= so dong tren 1 trang thi reset ve 0 va tang so trang len 1
            if($count>$this->map['line_per_page'])
        	{
        		$count = 1;
        		$total_page++;
        	}
            
            //In ra bắt đầu từ trang số 1
            $pages[$total_page][$key] = $item;	
        }
        
        //Nếu có dữ liệu từ câu truy vấn
        if(sizeof($pages)>0)
        {
            $this->group_function_params = array(
                'real_product_count'=>0,
                'total_unit_price'=>0,
                'total_quantity' =>0,
                'total_agent_discount'=>0,
                'total_promotion'=>0,
                'total_net_unit_price'=>0,
                'total_amount'=>0,
                'total_invoice_revenue'=>0,
                'total_vat'=>0,
                'total_net_revenue'=>0
            );
            $this->map['real_total_page']=count($pages);
            $this->map['real_page_no'] = 1;
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $page_no,$total_page,$count_product);
                $this->map['real_page_no'] ++;
			}
            
        }
        else
        {
            $this->map['real_total_page'] = 0;
            $this->map['real_page_no'] = 0;
			$this->parse_layout('report',$this->map+
				array(
					'page_no'=>0,
					'total_page'=>0
				)
			);
        }
	}
    function print_page($items, $page_no,$total_page,$count_product) 
    {
            
		$last_group_function_params = $this->group_function_params;
        
		foreach($items as $k => $item)
		{
            $this->group_function_params['real_product_count']++;
            $this->group_function_params['total_unit_price'] += $item['unit_price'];
                $this->group_function_params['total_quantity'] += $item['quantity'];
                $this->group_function_params['total_agent_discount'] += $item['agent_discount'];
                $this->group_function_params['total_promotion'] += $item['promotion'];
                $this->group_function_params['total_net_unit_price'] += $item['net_unit_price'];
                $this->group_function_params['total_amount'] += $item['amount'];
                $this->group_function_params['total_invoice_revenue'] += round($count_product[$item['ve_reservation_id']]['invoice_revenue']/$count_product[$item['ve_reservation_id']]['num']);
                $this->group_function_params['total_vat'] += $count_product[$item['ve_reservation_id']]['vat']/$count_product[$item['ve_reservation_id']]['num'];
                $this->group_function_params['total_net_revenue'] += round($count_product[$item['ve_reservation_id']]['net_revenue']/$count_product[$item['ve_reservation_id']]['num']);
            if(isset($item['stt'])>1)
            {
                $this->group_function_params['total_unit_price'] += $item['unit_price'];
                $this->group_function_params['total_quantity'] += $item['quantity'];
                $this->group_function_params['total_agent_discount'] += $item['agent_discount'];
                $this->group_function_params['total_promotion'] += $item['promotion'];
                $this->group_function_params['total_net_unit_price'] += $item['net_unit_price'];
                $this->group_function_params['total_amount'] += $item['amount'];
                $this->group_function_params['total_invoice_revenue'] += round($count_product[$item['ve_reservation_id']]['invoice_revenue']/$count_product[$item['ve_reservation_id']]['num']);
                $this->group_function_params['total_vat'] += $count_product[$item['ve_reservation_id']]['vat']/$count_product[$item['ve_reservation_id']]['num'];
                $this->group_function_params['total_net_revenue'] += round($count_product[$item['ve_reservation_id']]['net_revenue']/$count_product[$item['ve_reservation_id']]['num']);
            }                     
		}
        if($page_no>=$this->map['start_page'])
		{
             $this->parse_layout('report',
            	array(
                    
                    'items'=>$items,
            		'page_no'=>$page_no,
                    'total_page'=>$total_page,
            		'day'=>$this->day,
                    'group_function_params'=>$this->group_function_params,
                    'last_group_function_params'=>$last_group_function_params,
                    'count_product'=>$count_product
            	)+$this->map
    		);
        }
	}
	
    function calc_time($string)
    {
        $arr = explode(':',$string);
        //System::debug($arr);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
 ?>