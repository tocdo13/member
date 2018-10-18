<?php
class RestaurantOrderRevenueLessThan200ReportForm extends Form
{
	function RestaurantOrderRevenueLessThan200ReportForm()
	{
		Form::Form('RestaurantOrderRevenueLessThan200ReportForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css'); 
	}
	function draw()
	{
        $this->map = array();
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
		if(URL::get('do_search'))
		{
			$bars = DB::fetch_all('select * from bar');
			$bar_ids = '';
            $bar_name = '';
			foreach($bars as $k => $bar)
            {
				if(Url::get('bar_id_'.$k))
                {
					$bar_ids .= ($bar_ids=='')?$k:(','.$k);	
                    $bar_name .= ($bar_name=='')?$bar['name']:(', '.$bar['name']);
				}
			}
            //System::debug($bar_ids);
            $_REQUEST['bar_name'] = $bar_name;
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
			$from_day = Url::get('date_from');
            $to_day = Url::get('date_to');
            
			$from_code = 0;
			$to_code = 0;$cond_code ='';
			$this->map['check']=false;
			if(Url::get('from_code') && Url::get('to_code'))
            {
				if(strpos(Url::get('from_code'),'-') > -1)
                {
					$from_code = intval(substr(Url::get('from_code'),(strpos(Url::get('from_code'),'-')+1),strlen(Url::get('from_code'))));
				}
                else
                {
					$from_code = Url::get('from_code');
				}
				if(strpos(Url::get('to_code'),'-') > -1)
                {
					$to_code = intval(substr(Url::get('to_code'),(strpos(Url::get('to_code'),'-')+1),strlen(Url::get('to_code'))));
                }
                else
                {
					$to_code = Url::get('to_code');
				}
			}
			if($from_code>0 && $to_code>0 && $to_code>=$from_code)
            {
				// TH tim kiem theo hoa don VAT.
				$cond_code = $this->get_vat_invoice_cond($from_code,$to_code); // Lay dk cua HD VAT de select ra folio
				//echo $cond_code;
                $this->map['check'] = true;
			}
            
			//$this->line_per_page = URL::get('line_per_page',15);
            
            
			$shift_lists = DB::fetch_all('select bar_shift.* from bar_shift order by id ASC');
			$time_from = Date_Time::to_time($from_day);
			$time_to = (Date_Time::to_time($to_day));//+24*3600

            if(Url::get('start_time'))
            {
                $shift_from = $this->calc_time(Url::get('start_time'));
                $this->map['start_shift_time'] = Url::get('start_time');
            }
            else
            {
                $shift_from = $this->calc_time('00:00');
                $this->map['start_shift_time'] = '00:00';
            }
            if(Url::get('end_time'))
            {
                $shift_to = $this->calc_time(Url::get('end_time'))+59;
                $this->map['end_shift_time'] = Url::get('end_time'); 
            }
            else
            {
                $shift_to = $this->calc_time('23:59')+59;
                $this->map['end_shift_time'] = '23:59'; 
            }
            $cond = '';
			if($cond_code!='')
            { // Neu co dieu kien tim theo code roi thi ko lay dieu kien thoi gian nua
				$cond .= ' 1 = 1 AND ('.$cond_code.')';
			}
            else
            {
				//echo $shift_to.'--'.$shift_from;
    			$cond = $this->cond = ' 1 >0 '
    				.(URL::get('bar_id')?' and bar_reservation.bar_id = '.URL::get('bar_id').'':'') 
    				.(URL::get('user_id')?' and bar_reservation.checked_in_user_id = \''.URL::get('user_id').'\'':'') 
    				.' and bar_reservation.time_out>='.($time_from+$shift_from).' and bar_reservation.time_out<'.($time_to+$shift_to).''
    				.' and bar_reservation.total<200000 and bar_reservation.status=\'CHECKOUT\' '
    				;
    			if(Url::get('customer_name'))
                {
    				$cond .= ' AND customer.name LIKE \'%'.Url::get('customer_name').'%\'';
    			}
    			if(Url::get('code'))
                {
    				$cond .= ' AND upper(customer.code) LIKE \'%'.strtoupper(Url::sget('code')).'%\'';
    			}
			}
				//AND bar_reservation.payment_result IS NOT NULL' and bar_reservation.status=\'CHECKOUT\' '
			if(User::can_admin(false,ANY_CATEGORY))
            {
			    if(Url::get('hotel_id') != 'ALL')
                {
			         $cond .= Url::get('hotel_id')?' and bar_reservation.portal_id = \''.Url::get('hotel_id').'\'':''; 
			    }	
			}
            
			$sql = '
				-- SELECT * FROM (
					SELECT
						bar_reservation.id,bar_reservation.code,bar_reservation.departure_time
						,bar_reservation.code
                        ,bar_reservation.total
                        ,bar_reservation.total_before_tax
                        ,bar_reservation.payment_result
						,bar_reservation.checked_out_user_id as receptionist_id
						,bar_reservation.exchange_rate
						,bar_reservation.receiver_name
						,bar_reservation.bar_fee_rate
						,bar_reservation.tax_rate
						,bar_reservation.note
						,bar_reservation.time_out
						,bar_reservation.pay_with_room
						,customer.name as customer_name
						,bar_reservation.bar_id
						,bar_table.name as table_name
						,CONCAT(traveller.first_name,CONCAT(\' \',traveller.first_name)) as traveller_name
						,room.name as room_name
						,0 as deposit
						,0 as paid
						,0 as foc
                        ,\''.Portal::language('eating').'\' as description
						
						--,ROW_NUMBER() OVER(ORDER BY bar_reservation.id ) as rownumber
					FROM 
						bar_reservation 
                        inner join payment on bar_reservation.id = payment.bill_id and payment.payment_type_id = \'CASH\' and payment.type = \'BAR\'
						left outer join bar_reservation_table ON bar_reservation_table.bar_reservation_id = bar_reservation.id
						left outer join bar_table ON bar_reservation_table.table_id = bar_table.id
						left outer join customer on customer.id = bar_reservation.customer_id
						left outer join reservation_room on reservation_room.id = bar_reservation.reservation_room_id
						left outer join room on room.id = reservation_room.room_id
						left outer join traveller on traveller.id = reservation_room.traveller_id
					WHERE 
						'.$cond.' AND bar_reservation.bar_id in ('.$bar_ids.') 
					ORDER BY
						bar_reservation.id			
				';
                                       
			$report = new Report;

			$report->items = array();
			$report->items = DB::fetch_all($sql);
            //System::debug($report->items);
			$i = 1;
			$bill_id = '0';
			foreach($report->items as $key=>$value)
			{
				$bill_id .=','.$value['id'];	
			}
			//System::Debug($report->items);
            $i = 1;
            foreach($report->items as $key=>$value)
            {
                $report->items[$key]['total_after_fee'] = round($value['total_before_tax'] * (1+$value['bar_fee_rate']/100));
                $report->items[$key]['tax_fee'] = round($report->items[$key]['total_after_fee'] * ($value['tax_rate']/100));
                $report->items[$key]['stt'] = $i++;
                /** Minh add link cho hoa don **/
                $report->items[$key]['link'] = Url::build('touch_bar_restaurant',array('cmd'=>'detail',md5('act')=>md5('print_bill'),md5('preview')=>1,'id'=>$value['id'],'bar_id'=>$value['bar_id'],'table_id','bar_area_id','package_id'=>Url::get('package_id')?Url::get('package_id'):''));                
            }
			//System::Debug($report->items);
			$this->print_all_pages($report);
		}
		else
		{
			$_REQUEST['date_from'] = date('d/m/Y');
            $_REQUEST['date_to'] = date('d/m/Y');
			$view_all = true;
			if(!User::can_view(false,ANY_CATEGORY) and User::can_view_detail(false,ANY_CATEGORY))
            {
				$_REQUEST['from_day'] = date('d');
				$view_all = false;
			}
			$bar_lists = DB::fetch_all('select bar.* from bar');
			$shift_lists = array();
			// Lấy danh sách các ca làm vc
			$shift_lists = DB::fetch_all('select bar_shift.* from bar_shift order by id ASC');
			$shift_list = '<option value="0">---------</option>';
			foreach($shift_lists as $k=>$shift)
            {
				if(isset($bar_lists[$shift['bar_id']]['shifts']))
                {
					$bar_lists[$shift['bar_id']]['shifts'][$k] = $shift; 
				}
                else
                {
					$bar_lists[$shift['bar_id']]['shifts'][$k] = $shift;	
				}
				$shift_list .= '<option value="'.$shift['id'].'">'.$shift['name'].':'.$shift['brief_start_time'].'h - '.$shift['brief_end_time'].'h</option>';	
			} 
			/** 7211 */
			$user_privigele=DB::fetch('select group_privilege_id from account_privilege_group where account_id=\''.User::id().'\'');
            if(!$user_privigele or $user_privigele==3 or $user_privigele==4){
                
                $users = DB::fetch_all('
				SELECT
					account.id,account.id as name,account.is_active
				FROM
					account
                    INNER JOIN party on party.user_id=account.id
				WHERE
					party.type=\'USER\'
			');
            }else{
                $users = DB::fetch_all('
				SELECT
					account.id,account.id as name,account.is_active
				FROM
					account
                    INNER JOIN party on party.user_id=account.id
                    INNER JOIN account_privilege_group ON account_privilege_group.account_id=account.id
				WHERE
					party.type=\'USER\'
					AND account_privilege_group.group_privilege_id is not null and account_privilege_group.group_privilege_id !=3 and account_privilege_group.group_privilege_id !=4
			');
            }
             /** 7211 end*/
            if(Url::get('hotel_id'))
             {
                 if(Url::get('hotel_id')!='ALL')
                 {
                     $bars = DB::fetch_all("select id,name FROM bar where portal_id='".Url::get('hotel_id')."'");
                 }
                 else
                 {
                    $bars = DB::select_all('bar',false); 
                 }
             }
             else
             {
                $bars = DB::select_all('bar',false); 
             }
             //end giap
			$this->parse_layout('search',
				array(			
				'user_id_list'=>array(''=>Portal::language('all_user'))+String::get_list($users),	
				'view_all'=>$view_all,
				'users'=>$users,
				'bar_id' => URL::get('bar_id',''),
				'shift_list' =>$shift_list,
				'bar_lists' =>String::array2js($bar_lists),
				'bars' =>$bars, 
				'hotel_id_list'=>array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list())
				)
			);	
		}			
	}
	function print_all_pages(&$report)
	{
		$count = 0;
		$total_page = 1;
		$pages = array();
		foreach($report->items as $key=>$item)
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
					'total'=>0,
					'total_after_fee'=>0,
					'tax_fee'=>0,
				);
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page);
			}
		}
		else
		{     
			if(URL::get('hotel_id')!='ALL')
            {
				$hotel = DB::fetch('SELECT NAME_1 AS name,ADDRESS FROM PARTY WHERE USER_ID = \''.Url::get('hotel_id').'\'');
				$hotel_name = $hotel['name']?$hotel['name']:HOTEL_NAME;
				$hotel_address = $hotel['address']?$hotel['address']:HOTEL_ADDRESS;
			}
            else
            {
				$hotel_name = HOTEL_NAME;
				$hotel_address = HOTEL_ADDRESS;
			}	
			$this->parse_layout('header',
			get_time_parameters()+
				array(
					'hotel_address'=>$hotel_address,
					'hotel_name'=>$hotel_name,
					'page_no'=>0,
					'total_page'=>0
				)+$this->map
			);
			$this->parse_layout('footer',array(
				'page_no'=>0,
				'total_page'=>0
			));
		}
	}
	function print_page($items, &$report, $page_no,$total_page)
	{
	    $from_day = Url::get('date_from');
        $to_day = Url::get('date_to');
		
		$last_group_function_params = $this->group_function_params;
		foreach($items as $item)
		{
			if($temp = $item['total']){
				$this->group_function_params['total'] += $temp;
			}
			if($temp = $item['total_after_fee']){
				$this->group_function_params['total_after_fee'] += $temp;
			}
			if($temp = $item['tax_fee']){
				$this->group_function_params['tax_fee'] += $temp;
			}
		}
		//$total = $this->group_function_params['pay_by_cash']+$fee_summary['pay_by_cash_fee']+$fee_summary['pay_by_cash_tax'];		
		
        if(Url::get('hotel_id')=="ALL")
        {
            $hotel_name = HOTEL_NAME;
			$hotel_address = HOTEL_ADDRESS;
			
		}
        else
        {
		    $hotel = DB::fetch('SELECT NAME_1 AS name,ADDRESS FROM PARTY WHERE USER_ID = \''.Url::get('hotel_id').'\'');
			$hotel_name = $hotel['name'];
			$hotel_address = $hotel['address'];	
		}
        
        //system::debug($_REQUEST);die();
        if($page_no>=Url::get('start_page'))
        {
            $this->parse_layout('header',
    			array(
                    'to_date'=>$to_day,
                    'from_date'=>$from_day,
    				'hotel_address'=>$hotel_address,
    				'hotel_name'=>$hotel_name,
    				'page_no'=>$page_no,
    				'total_page'=>$total_page,
    			)+$this->map
    		);
            
    		$layout = 'report_cash';
    		$this->parse_layout($layout,array(
    				'items'=>$items,
    				'last_group_function_params'=>$last_group_function_params,
    				'group_function_params'=>$this->group_function_params,
    				'page_no'=>$page_no,
    				'total_page'=>$total_page,
    			)
    		);
            $this->parse_layout('footer',array(
    			'page_no'=>$page_no,
    			'total_page'=>$total_page,
    		));	
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