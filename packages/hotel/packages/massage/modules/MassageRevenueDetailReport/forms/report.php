<?php
class MassageRevenueDetailReportForm extends Form
{
	function MassageRevenueDetailReportForm()
	{
		Form::Form('MassageRevenueDetailReportForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
        $this->map = array();
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
		/*
        $sql = '
			SELECT
				massage_staff.id,massage_staff.full_name as name
			FROM
				massage_staff
		';
		$this->map['staff_id_list'] = array(''=>Portal::language('all'))+String::get_list(DB::fetch_all($sql));
		*/
        $sql = '
			SELECT
				product.*,
                CONCAT(product.id,CONCAT(\' - \',product.name_'.Portal::language().')) as name
			FROM
				product_price_list
				INNER JOIN product ON product_price_list.product_id = product.id
			WHERE
				product_price_list.portal_id = \''.PORTAL_ID.'\'
                and product.status=\'avaiable\'
                and product_price_list.department_code = \'SPA\'
			ORDER BY
				product.id
		';
		$products = DB::fetch_all($sql);
		$this->map['product_id_list'] = array(''=>Portal::language('all'))+String::get_list($products);
        
		$sql = '
			SELECT
				massage_guest.*,massage_guest.full_name as name
			FROM
				massage_guest
           	WHERE
                massage_guest.portal_id = \''.PORTAL_ID.'\'			
			ORDER BY
				massage_guest.full_name
		          ';
		$guests = DB::fetch_all($sql);
		$this->map['guest_id_list'] = array(''=>Portal::language('all'))+String::get_list($guests);
		$cond = ' 1=1';
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
        $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y', Date_Time::to_time(date('d/m/Y', time())) +86400);
        $_REQUEST['to_date'] = $this->map['to_date'];
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
        $_REQUEST['from_date'] = $this->map['from_date'];
        if(Url::get('from_hours'))
            {
                $shift_from = $this->calc_time(Url::get('from_hours'));
                $this->map['from_hours'] = Url::get('from_hours');
            }
            else
            {
                $shift_from = $this->calc_time('02:00');
                $this->map['from_hours'] = '02:00';
            }
        if(Url::get('to_hours'))
            {
                $shift_to = $this->calc_time(Url::get('to_hours'))+59;
                $this->map['to_hours'] = Url::get('to_hours');      
            }
            else
            {
                $shift_to = $this->calc_time('02:00')+59;
                $this->map['to_hours'] = '02:00'; 
            }
        $arr_from_hours = explode(":",$this->map['from_hours']);
        $arr_to_hours = explode(":",$this->map['to_hours']);
        $from_date = Date_Time::to_time($this->map['from_date'])+$arr_from_hours[0]*3600+$arr_from_hours[1]*60;
        $to_date = Date_Time::to_time($this->map['to_date'])+$arr_to_hours[0]*3600+$arr_to_hours[1]*60+59;
        //System::debug($to_date);
        if($portal_id != 'ALL')
        {
            $cond.=' AND massage_reservation_room.portal_id = \''.$portal_id.'\' '; 
        }   
        $cond .= '
				'.(Url::get('product_id')?' AND massage_product_consumed.product_id = \''.Url::get('product_id').'\'':'').'
                '.(Url::get('guest_id')?' AND massage_reservation_room.guest_id = \''.Url::get('guest_id').'\'':'').'
				'.(Url::get('from_date')?' AND massage_product_consumed.time_out>='.(Date_Time::to_time($this->map['from_date'])+$arr_from_hours[0]*3600+$arr_from_hours[1]*60):'').'
				'.(Url::get('to_date')?' AND massage_product_consumed.time_out < '.(Date_Time::to_time($this->map['to_date'])+$arr_to_hours[0]*3600+$arr_to_hours[1]*60+59):'').'
			';
        $sql = 
		'
        Select * FROM
        (
            SELECT id,code,name,total_amount,quantity, ROWNUM as rownumber FROM
    		(
                SELECT 
                    massage_product_consumed.id,
                    product.id as code,
                    product.name_'.Portal::language().' as name,
                    massage_product_consumed.quantity as quantity,                    
                    Case
                    when massage_reservation_room.net_price = 1
                    then
                        massage_product_consumed.quantity * massage_product_consumed.price
                    else
                        massage_product_consumed.quantity * massage_product_consumed.price*(1+ massage_reservation_room.service_rate/100)*(1+massage_reservation_room.tax/100)
                    end as total_amount
                FROM 
                    massage_product_consumed
                    INNER JOIN product ON product.id = massage_product_consumed.product_id
                    INNER JOIN massage_reservation_room ON massage_reservation_room.id = massage_product_consumed.reservation_room_id
                WHERE 
                    '.$cond.'
                    and massage_product_consumed.status = \'CHECKOUT\'
                ORDER BY
                    product.id
    		)    
        )
		WHERE
			 rownumber > '.(($this->map['start_page']-1)*$this->map['line_per_page']).' AND rownumber<='.($this->map['no_of_page']*$this->map['line_per_page']).'
		';
        $massa = DB::fetch_all($sql);
		$i = 1;
        $items = array();
		foreach($massa as $key=>$value)
		{
			if(!isset($items[$value['code']]))
            {
                $items[$value['code']] = $value;
                $items[$value['code']]['stt'] = $i++;
                $items[$value['code']]['total_amount'] = $value['total_amount'];
            }
            else
            {
                $items[$value['code']]['total_amount'] += $value['total_amount'];
                $items[$value['code']]['quantity'] += $value['quantity'];
            }
		}
      // System::debug($items);
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
			$this->parse_layout('report',
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
			if($temp=System::calculate_number($item['total_amount'])){
				$this->group_function_params['total_amount'] += $temp;
			}	
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
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
    
}
?>
