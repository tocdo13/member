<?php
class MassageRevenueReportForm extends Form
{
	function MassageRevenueReportForm()
	{
		Form::Form('MassageRevenueReportForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
	   
        $this->map = array();
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
        $rooms = DB::fetch_all('select id,name from massage_room order by name');
        
        $this->map['room_id_list'] = array(''=>Portal::language('all')) + String::get_list($rooms);
        
        
        //$this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        //$_REQUEST['to_date'] = $this->map['to_date'];
        //dau thang
        //$this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
        //$_REQUEST['from_date'] = $this->map['from_date'];
        
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
            $cond.=' AND massage_reservation_room.portal_id = \''.$portal_id.'\' '; 
        }
        $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y', Date_Time::to_time(date('d/m/Y', time())) +86400);
        $_REQUEST['to_date'] = $this->map['to_date'];
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
        $_REQUEST['from_date'] = $this->map['from_date'];  
        if(Url::get('from_time'))
            {
                $shift_from = $this->calc_time(Url::get('from_time'));
                $this->map['from_time'] = Url::get('from_time');
            }
            else
            {
                $shift_from = $this->calc_time('02:00');
                $this->map['from_time'] = '02:00';
            }
        if(Url::get('to_time'))
            {
                $shift_to = $this->calc_time(Url::get('to_time'))+59;
                $this->map['to_time'] = Url::get('to_time');      
            }
            else
            {
                $shift_to = $this->calc_time('02:00')+59;
                $this->map['to_time'] = '02:00'; 
            }
        $arr_from_hours = explode(":",$this->map['from_time']);
        $arr_to_hours = explode(":",$this->map['to_time']);
        $to_time_view = Date_Time::to_time($this->map['to_date'])+$arr_to_hours[0]*3600+$arr_to_hours[1]*60+59;                                
        $from_time_view = Date_Time::to_time($this->map['from_date'])+$arr_from_hours[0]*3600+$arr_from_hours[1]*60;
		if(Url::get('from_date'))
		{
            $cond .= ' and massage_product_consumed.time_out>='.$from_time_view;
		}
		if(Url::get('to_date'))
		{
			$cond .= ' and massage_product_consumed.time_out < '.$to_time_view;
		}
        if(Url::get('room_id'))
		{
			$cond .= ' and massage_product_consumed.room_id = '.Url::get('room_id');
		}
        
        $cond .= ' and massage_product_consumed.status = \'CHECKOUT\' ';
        
    	$sql = '
		SELECT  * FROM
		(
            SELECT 
                massage_reservation_room.*,
                massage_product_consumed.time_out,
                ROWNUM as rownumber
            FROM 
                massage_reservation_room
                INNER JOIN massage_product_consumed ON massage_product_consumed.reservation_room_id = massage_reservation_room.id
            WHERE 
                '.$cond.'	
            ORDER BY 
                massage_product_consumed.time_out
		)	
		WHERE
            rownumber > '.(($this->map['start_page']-1)*$this->map['line_per_page']).' AND rownumber<='.($this->map['no_of_page']*$this->map['line_per_page']).'
        ';
		$items = DB::fetch_all($sql);
        //System::debug($items);
        $i=1;
		foreach($items as $key=>$item)
		{
			$items[$key]['stt'] = $i++;
			$items[$key]['time'] = date('d/m/Y',$item['time_out']);
			$credit_card = 0;
			$currency = DB::fetch('
				SELECT
					pay_by_currency.id,pay_by_currency.amount
				FROM
					pay_by_currency
				WHERE
					pay_by_currency.currency_id=\'USD\' AND type=\'MASSAGE\' AND bill_id = '.$item['id'].'
			');
			$credit_card = $currency['amount'];
			$items[$key]['credit_card'] = System::display_number_report($credit_card);
			$total_amount_vnd = 0;
			$currency = DB::fetch('
				SELECT
					pay_by_currency.id,pay_by_currency.amount
				FROM
					pay_by_currency
				WHERE
					pay_by_currency.currency_id=\'VND\' AND type=\'MASSAGE\' AND bill_id = '.$item['id'].'
			');
			$total_amount_vnd = $currency['amount'];
			$items[$key]['total_amount_vnd'] = System::display_number($total_amount_vnd);
			//$items[$key]['total_amount']=System::display_number(round($item['total_amount']) - $credit_card - round($total_amount_vnd/$item['exchange_rate']));
			$items[$key]['debit']=0;
			$items[$key]['debit_vnd']=0;
			if(!$items[$key]['tip_amount'] = DB::fetch('select sum(tip) as tip from massage_staff_room where reservation_room_id = '.$item['id'].' group by reservation_room_id','tip')){
				$items[$key]['tip_amount'] = 0;
			}
			$items[$key]['tip_amount']=System::display_number($items[$key]['tip_amount']);
		}
        //System::debug($items);
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
				    'total_tip'=>0,
					'total_amount'=>0,
					'total_amount_vnd'=>0,
					'total_debit'=>0,
					'total_credit_card'=>0
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
			if($temp=System::calculate_number($item['tip_amount']))
			{
				$this->group_function_params['total_tip'] += $temp;
			}
			if($temp=System::calculate_number($item['total_amount']))
			{
				$this->group_function_params['total_amount'] += $temp;
			}
			if($temp=System::calculate_number($item['total_amount_vnd']))
			{
				$this->group_function_params['total_amount_vnd'] += $temp;
			}
			if($temp=System::calculate_number($item['debit']))
			{
				$this->group_function_params['total_debit'] += $temp;
			}
			if($temp=System::calculate_number($item['credit_card']))
			{
				$this->group_function_params['total_credit_card'] += $temp;
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
        //System::debug($arr);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
?>
