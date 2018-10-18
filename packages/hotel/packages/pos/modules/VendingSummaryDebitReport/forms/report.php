<?php
class SummaryDebitReportForm extends Form
{
    public static $layout = '';
	function SummaryDebitReportForm()
	{
		Form::Form('SummaryDebitReportForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
        $this->map = array();
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):999;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
        $this->map['option_list'] = array('SHORT'=>Portal::language('short'),'FULL'=>Portal::language('full'));
        
        $area = $this->get_total_area();
        $this->map['area_id_list'] = array(''=>Portal::language('All'))+String::get_list($area);
        
        $this->map['customer_id_list'] = array(''=>Portal::language('All'))+String::get_list(DB::select_all('vending_customer',''));
        

        $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        $_REQUEST['to_date'] = $this->map['to_date'];
        //dau thang
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('1/m/Y');
        $_REQUEST['from_date'] = $this->map['from_date'];
        
        $this->map['option'] = Url::get('option')?Url::get('option'):'FULL';
        $_REQUEST['option'] = $this->map['option'];
        
		$cond = ' 1=1';
        $cond_pay = ' 1=1';
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
        if(Url::get('portal_id'))
        {
            $portal_id = Url::get('portal_id');
        }
        else
        {
            $portal_id = PORTAL_ID;
            $_REQUEST['portal_id'] = PORTAL_ID;                       
        }
        
        if($portal_id != 'ALL')
        {
            $cond.=' AND ve_reservation.portal_id = \''.$portal_id.'\' '; 
            $cond_pay.=' AND customer_debt_settlement.portal_id = \''.$portal_id.'\' ';
        }
        
		$cond .= ' '
				.(URL::get('from_date')?' and ve_reservation.time>=\''.Date_Time::to_time(URL::get('from_date')).'\'':'')
				.(URL::get('to_date')?' and ve_reservation.time<\''.(Date_Time::to_time(URL::get('to_date'))+86400).'\'':'')
                .(URL::get('customer_id')?' and ve_reservation.customer_id = \''.URL::get('customer_id').'\'':'')
		;
        
		$cond_pay .= ' '
				.(URL::get('from_date')?' and customer_debt_settlement.time>=\''.Date_Time::to_time(URL::get('from_date')).'\'':'')
				.(URL::get('to_date')?' and customer_debt_settlement.time<\''.(Date_Time::to_time(URL::get('to_date'))+86400).'\'':'')
                .(URL::get('customer_id')?' and customer_debt_settlement.customer_id = \''.URL::get('customer_id').'\'':'')
		;
        
        //$cond .= ' and ve_reservation.is_debit = 1';
        //System::debug($cond);
        $sql = '
			SELECT * FROM
			(
				SELECT 
                    ve_reservation.id,
					ve_reservation.customer_id,
                    vending_customer.code,
                    vending_customer.name,
                    vending_customer.address,
                    vending_customer.tax_code,
                    (case 
                        when payment.currency_id = \'USD\'
                        THEN sum(payment.amount)*'.RES_EXCHANGE_RATE.'
                        else sum(payment.amount)
                     end 
                    ) as total_debit,
                    (case 
                        when payment.currency_id = \'USD\'
                        THEN sum(payment.amount)*'.RES_EXCHANGE_RATE.'
                        else sum(payment.amount)
                     end 
                    ) as total_remain,
                    0 as total_pay,
					ROW_NUMBER() OVER (ORDER BY ve_reservation.customer_id ) as rownumber
				FROM 
                    ve_reservation
                    inner join payment on payment.bill_id = ve_reservation.id
                    inner join vending_customer on ve_reservation.customer_id = vending_customer.id
				WHERE 
                    '.$cond.'
                    and payment.payment_type_id = \'DEBIT\'
                    and payment.type = \'VEND\'
                GROUP BY
                        ve_reservation.customer_id,
                        vending_customer.code,
                        vending_customer.name,
                        payment.currency_id,
                        vending_customer.address,
                        vending_customer.tax_code,
                        ve_reservation.id
                ORDER BY
					ve_reservation.customer_id
			)
			where 
				rownumber > '.(($this->map['start_page']-1)*$this->map['line_per_page']).' AND rownumber<='.($this->map['no_of_page']*$this->map['line_per_page']).'
		';
        
        SummaryDebitReportForm::$layout = 'report';
		$items = DB::fetch_all($sql);
        //System::debug($items);
        $sql = '
				SELECT 
                    customer_debt_settlement.id,
					customer_debt_settlement.customer_id,
                    Sum(customer_debt_settlement.total) as pay
				FROM 
                    customer_debt_settlement
				WHERE 
                    '.$cond_pay.'
                GROUP BY
                    customer_debt_settlement.id,
                    customer_debt_settlement.customer_id
                ORDER BY
					customer_debt_settlement.customer_id
		      ';
        
		$items_pay = DB::fetch_all($sql);
        //System::debug($sql);
        $customer_sql = 'select * from vending_customer';
        $customer_arr = DB::fetch_all($customer_sql);
        $i=1;
        foreach($customer_arr as $key => $value)
        {
            $start_debt = DB::fetch('select * from VENDING_START_TERM_DEBIT where supplier_id = ' . $value['id']);
            if(!empty($start_debt))
            {
                $customer_arr[$key]['start_debt'] = $start_debt['total'];
            }
            else 
                $customer_arr[$key]['start_debt'] = 0;
            $customer_arr[$key]['total_debit'] = 0;
            $customer_arr[$key]['total_pay'] = 0;
            $customer_arr[$key]['total_remain'] = 0;
            foreach($items as $k => $v)
            {
               if($value['id'] == $v['customer_id'])
               {
                    $customer_arr[$key]['total_debit'] += $v['total_debit'];
               } 
            }
            foreach($items_pay as $customer=>$money)
    		{
                if($value['id'] == $money['customer_id'])
                {
                    $customer_arr[$key]['total_pay'] += $money['pay'];
                }
    		}
            $customer_arr[$key]['stt'] = $i++;
            $customer_arr[$key]['total_remain'] = $customer_arr[$key]['start_debt'] + $customer_arr[$key]['total_debit'] - $customer_arr[$key]['total_pay'];
            $customer_arr[$key]['total_debit'] = $customer_arr[$key]['start_debt'] + $customer_arr[$key]['total_debit'];
            $customer_arr[$key]['total_debit'] = System::display_number($customer_arr[$key]['total_debit']);
            $customer_arr[$key]['total_pay'] = System::display_number($customer_arr[$key]['total_pay']);
            $customer_arr[$key]['total_remain'] = System::display_number($customer_arr[$key]['total_remain']);
            if($customer_arr[$key]['total_remain'] == 0 and $customer_arr[$key]['total_pay'] == 0 and $customer_arr[$key]['total_debit'] == 0)
            {
                unset($customer_arr[$key]);
            }
        }
        $this->print_all_pages($customer_arr);
	}   
    function print_all_pages($items)
	{
		$count = 0;
		$total_page = 1;
		$pages = array();
		foreach($items as $key=>$item)
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
					'total_debit'=>0,
                    'total_pay'=>0,
                    'total_remain'=>0,
				);
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $page_no,$total_page);
			}
		}
		else
		{
			$this->parse_layout(SummaryDebitReportForm::$layout,
				array(
					'page_no'=>0,
					'total_page'=>0,
                    'has_no_data'=>true
				)+$this->map
			);
		}
	}
    function print_page($items, $page_no, $total_page)
	{
        $last_group_function_params = $this->group_function_params;	
		foreach($items as $item)
		{
			if($temp=System::calculate_number($item['total_debit']))
			{
				$this->group_function_params['total_debit'] += $temp;
			}
			if($temp=System::calculate_number($item['total_pay']))
			{
				$this->group_function_params['total_pay'] += $temp;
			}
			if($temp=System::calculate_number($item['total_remain']))
			{
				$this->group_function_params['total_remain'] += $temp;
			}			
		}	
		$this->parse_layout(SummaryDebitReportForm::$layout,array(
				'items'=>$items,
				'last_group_function_params'=>$last_group_function_params,
				'group_function_params'=>$this->group_function_params,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)+$this->map
		);
	}
    
    function get_total_area()
    {
		$bars = DB::fetch_all('
			SELECT
                department.id,
                department.code,
                department.name_'.Portal::language().' as name,
                portal_department.warehouse_id,
                department.id as bar_id_from
			FROM
                department
                inner join portal_department on department.code = portal_department.department_code and portal_department.portal_id = \''.PORTAL_ID.'\'
			WHERE
				1=1  AND department.parent_id = (select id from department where code = \'VENDING\')');
	   return $bars;
	}
}
?>