<?php
class VendingReservationDebitReportForm extends Form
{
    public static $layout = '';
	function VendingReservationDebitReportForm()
	{
		Form::Form('VendingReservationDebitReportForm');
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
        
        require_once 'packages/hotel/packages/vending/includes/php/vending.php';
        $area = get_area_vending();
        
        //$area = $this->get_total_area();
        $this->map['area_id_list'] = array(''=>Portal::language('All'))+String::get_list($area);
        
        $this->map['customer_id_list'] = array(''=>Portal::language('All'))+String::get_list(DB::fetch_all('select vending_customer.id, vending_customer.name from vending_customer where portal_id = \''.PORTAL_ID.'\''));
        

        $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        $_REQUEST['to_date'] = $this->map['to_date'];
        //dau thang
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('1/m/Y');
        $_REQUEST['from_date'] = $this->map['from_date'];
        
        $this->map['option'] = Url::get('option')?Url::get('option'):'FULL';
        $_REQUEST['option'] = $this->map['option'];
        
		$cond = ' 1=1';
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
        }
        
		$cond .= ' '
				.(URL::get('from_date')?' and ve_reservation.time>=\''.Date_Time::to_time(URL::get('from_date')).'\'':'')
				.(URL::get('to_date')?' and ve_reservation.time<\''.(Date_Time::to_time(URL::get('to_date'))+86400).'\'':'')
                .(URL::get('area_id')?' and ve_reservation.department_id = \''.URL::get('area_id').'\'':'')
                .(URL::get('customer_id')?' and ve_reservation.customer_id = \''.URL::get('customer_id').'\'':'')
		;
        
        //$cond .= ' and ve_reservation.is_debit = 1';
        
        $cond .=' and ve_reservation.department_id in (';    
        foreach($area as $k=>$v)
        {
            $cond.=$k.',';
        }
        $cond = trim($cond,',');
        $cond.= ')';  
        
        //System::debug($cond);

        if($this->map['option'] == 'FULL')
        {
            VendingReservationDebitReportForm::$layout = 'report_full';
            $sql = '
    			SELECT * FROM
    			(
    				SELECT 
    					ve_reservation.id,
                        ve_reservation.code,
                        sum((case 
                            when payment.currency_id = \'USD\'
                            THEN payment.amount*'.RES_EXCHANGE_RATE.'
                            else payment.amount
                         end 
                        )) as total,
                        ve_reservation.user_id,
                        ve_reservation.note,
                        to_char(FROM_UNIXTIME(ve_reservation.time),\'DD/MM/YYYY\') as create_date,
                        DECODE(
                            ve_reservation.agent_name, \'\',ve_reservation.receiver_name,
                                                        ve_reservation.agent_name 
                        ) as agent_name,
    					ROW_NUMBER() OVER (ORDER BY ve_reservation.id desc ) as rownumber,
                        department.name_'.Portal::language().' as department_name,
                        ve_reservation.department_id
    				FROM 
                        ve_reservation
                        inner join payment on payment.bill_id = ve_reservation.id
                        left join department on  ve_reservation.department_id = department.id
    				WHERE 
                        '.$cond.'
                        and payment.payment_type_id = \'DEBIT\'
                        and payment.type = \'VEND\'
                        --KimTan cmt de khong nhap ten khach ten cty van ra bao cao
                        --and ve_reservation.agent_name is not null
                        --end KimTan cmt de khong nhap ten khach ten cty van ra bao cao
                    group by 
                        ve_reservation.id,
                        ve_reservation.code,
                        ve_reservation.user_id,
                        ve_reservation.note,
                        ve_reservation.time,
                        ve_reservation.agent_name,
                        ve_reservation.receiver_name,
                        department.name_'.Portal::language().',
                        ve_reservation.department_id
                    ORDER BY
    					ve_reservation.id desc
    			)
    			where 
    				rownumber > '.(($this->map['start_page']-1)*$this->map['line_per_page']).' AND rownumber<='.($this->map['no_of_page']*$this->map['line_per_page']).'
    		';  
        }
		else
        {
            $sql = '
    			SELECT * FROM
    			(
                    select 
                        customer_id as id,
                        code,
                        name,
                        address,
                        tax_code,
                        sum(total) as total,
                        ROW_NUMBER() OVER (ORDER BY customer_id ) as rownumber
                    from(
    				SELECT 
                        ROW_NUMBER() OVER (ORDER BY ve_reservation.customer_id ) as id,
    					VENDING_CUSTOMER.id as customer_id,
                        VENDING_CUSTOMER.code,
                        VENDING_CUSTOMER.name,
                        VENDING_CUSTOMER.address,
                        VENDING_CUSTOMER.tax_code,
                        sum((case 
                            when payment.currency_id = \'USD\'
                            THEN payment.amount*'.RES_EXCHANGE_RATE.'
                            else payment.amount
                         end 
                        )) as total,
    					ROW_NUMBER() OVER (ORDER BY ve_reservation.customer_id ) as rownumber
    				FROM 
                        --KimTan xu ly ko nhap ten khach ten cty van cho ra bao cao
                        --VENDING_CUSTOMER
                        --inner join ve_reservation on ve_reservation.customer_id = VENDING_CUSTOMER.id
                        ve_reservation
                        left join VENDING_CUSTOMER on ve_reservation.customer_id = VENDING_CUSTOMER.id
                        --end KimTan xu ly
                        inner join payment on payment.bill_id = ve_reservation.id
    				WHERE 
                        '.$cond.'
                        and payment.payment_type_id = \'DEBIT\'
                        and payment.type = \'VEND\'
                    GROUP BY
                            VENDING_CUSTOMER.id,
                            ve_reservation.customer_id,                            
                            VENDING_CUSTOMER.code,
                            VENDING_CUSTOMER.name,
                            VENDING_CUSTOMER.address,
                            payment.currency_id,
                            VENDING_CUSTOMER.tax_code
                    ORDER BY
    					ve_reservation.customer_id
                    )
                    group by
                        customer_id,
                        code,
                        name,
                        address,
                        tax_code
    			)
    			where 
    				rownumber > '.(($this->map['start_page']-1)*$this->map['line_per_page']).' AND rownumber<='.($this->map['no_of_page']*$this->map['line_per_page']).'
    		';
            
            VendingReservationDebitReportForm::$layout = 'report_short';
        }
        //System::debug($sql);
		$items = DB::fetch_all($sql);
        //System::debug($items);
        $i=1;
		foreach($items as $key=>$value)
		{
			$items[$key]['stt'] = $i++;
            $items[$key]['total'] = System::display_number($value['total']);
		}
        $this->print_all_pages($items);
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
					'total_amount'=>0,
				);
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $page_no,$total_page);
			}
		}
		else
		{
			$this->parse_layout(VendingReservationDebitReportForm::$layout,
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
			if($temp=System::calculate_number($item['total']))
			{
				$this->group_function_params['total_amount'] += $temp;
			}			
		}	
		$this->parse_layout(VendingReservationDebitReportForm::$layout,array(
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