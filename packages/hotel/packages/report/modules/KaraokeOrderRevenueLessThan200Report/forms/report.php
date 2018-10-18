<?php
class KaraokeOrderRevenueLessThan200ReportForm extends Form
{
	function KaraokeOrderRevenueLessThan200ReportForm()
	{
		Form::Form('KaraokeOrderRevenueLessThan200ReportForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');  
	}
	function draw()
	{
        $this->map = array();
		if(URL::get('do_search'))
		{
			$karaokes = DB::fetch_all('select * from karaoke');
			$karaoke_ids = '';
            $karaoke_name = '';
			foreach($karaokes as $k => $karaoke){
				if(Url::get('karaoke_id_'.$k)){
					$karaoke_ids .= ($karaoke_ids=='')?$k:(','.$k);	
                    $karaoke_name .= ($karaoke_name=='')?$karaoke['name']:(', '.$karaoke['name']);
				}
			}
            //System::debug($karaoke_ids);
            $_REQUEST['karaoke_name'] = $karaoke_name;
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
			$from_day = Url::get('date_from');
            $to_day = Url::get('date_to');
			$from_code = 0;
			$to_code = 0;$cond_code ='';
			$this->map['check']=false;
			if(Url::get('from_code') && Url::get('to_code')){
				if(strpos(Url::get('from_code'),'-') > -1){
					$from_code = intval(substr(Url::get('from_code'),(strpos(Url::get('from_code'),'-')+1),strlen(Url::get('from_code'))));
				}else{
					$from_code = Url::get('from_code');
				}
				if(strpos(Url::get('to_code'),'-') > -1){
					$to_code = intval(substr(Url::get('to_code'),(strpos(Url::get('to_code'),'-')+1),strlen(Url::get('to_code'))));
				}else{
					$to_code = Url::get('to_code');
				}
			}
			if($from_code>0 && $to_code>0 && $to_code>=$from_code){
				// TH tim kiem theo hoa don VAT.
				$cond_code = $this->get_vat_invoice_cond($from_code,$to_code); // Lay dk cua HD VAT de select ra folio
				//echo $cond_code;
                $this->map['check'] = true;
			}
            
			$this->line_per_page = URL::get('line_per_page',15);
			$shift_lists = DB::fetch_all('select karaoke_shift.* from karaoke_shift order by id ASC');
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
                $shift_to = $this->calc_time(Url::get('end_time'));
                $this->map['end_shift_time'] = Url::get('end_time'); 
            }
            else
            {
                $shift_to = $this->calc_time('23:59');
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
    				.(URL::get('karaoke_id')?' and karaoke_reservation.karaoke_id = '.URL::get('karaoke_id').'':'') 
    				.(URL::get('user_id')?' and karaoke_reservation.checked_in_user_id = \''.URL::get('user_id').'\'':'') 
    				.' and karaoke_reservation.time_out>='.($time_from+$shift_from).' and karaoke_reservation.time_out<'.($time_to+$shift_to).''
    				.' and karaoke_reservation.total<200000 and karaoke_reservation.status=\'CHECKOUT\' '
    				;
    			if(Url::get('customer_name')){
    				$cond .= ' AND customer.name LIKE \'%'.Url::get('customer_name').'%\'';
    			}
    			if(Url::get('code')){
    				$cond .= ' AND upper(customer.code) LIKE \'%'.strtoupper(Url::sget('code')).'%\'';
    			}
			}
				//AND karaoke_reservation.payment_result IS NOT NULL' and karaoke_reservation.status=\'CHECKOUT\' '
			if(User::can_admin(false,ANY_CATEGORY)){
				$cond .= Url::get('hotel_id')?' and karaoke_reservation.portal_id = \''.Url::get('hotel_id').'\'':'';
			}else{
				$cond .= ' and karaoke_reservation.portal_id = \''.PORTAL_ID.'\'';	
			}
            //System::debug($cond);
			//echo $cond;
            //inner join vs payment co the co nhieu record, nhung lay karaoke_reservation.id lam id se dc 1 ban ghi duy nhat, muc dich la lay type = CASH
			$sql = '
				SELECT * FROM (
					SELECT
						karaoke_reservation.id,karaoke_reservation.code,karaoke_reservation.departure_time
						,karaoke_reservation.total
                        ,karaoke_reservation.total_before_tax
                        ,karaoke_reservation.payment_result
						,karaoke_reservation.checked_out_user_id as receptionist_id
						,karaoke_reservation.exchange_rate
						,karaoke_reservation.receiver_name
						,karaoke_reservation.karaoke_fee_rate
						,karaoke_reservation.tax_rate
						,karaoke_reservation.note
						,karaoke_reservation.time_out
						,karaoke_reservation.pay_with_room
						,customer.name as customer_name
						,karaoke_reservation.karaoke_id
						,karaoke_table.name as table_name
						,CONCAT(traveller.first_name,CONCAT(\' \',traveller.first_name)) as traveller_name
						,room.name as room_name
						,0 as deposit
						,0 as paid
						,0 as foc
                        ,\''.Portal::language('eating').'\' as description
						
						,ROW_NUMBER() OVER(ORDER BY karaoke_reservation.id ) as rownumber
					FROM 
						karaoke_reservation 
                        inner join payment on karaoke_reservation.id = payment.bill_id and payment.payment_type_id = \'CASH\' and payment.type = \'karaoke\'
						left outer join karaoke_reservation_table ON karaoke_reservation_table.karaoke_reservation_id = karaoke_reservation.id
						left outer join karaoke_table ON karaoke_reservation_table.table_id = karaoke_table.id
						left outer join customer on customer.id = karaoke_reservation.customer_id
						left outer join reservation_room on reservation_room.id = karaoke_reservation.reservation_room_id
						left outer join room on room.id = reservation_room.room_id
						left outer join traveller on traveller.id = reservation_room.traveller_id
					WHERE 
						'.$cond.' AND karaoke_reservation.karaoke_id in ('.$karaoke_ids.') 
					ORDER BY
						karaoke_reservation.id
					)
				WHERE rownumber>'.((URL::get('start_page')-1)*$this->line_per_page).' AND rownumber <= '.(URL::get('no_of_page')*$this->line_per_page).'
				';
                //                        
			$report = new Report;
            
            //if(User::is_admin())
//            {
//                System::debug($sql);
//                
//            }
   
			$report->items = array();
			$report->items = DB::fetch_all($sql);
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
                $report->items[$key]['total_after_fee'] = round($value['total_before_tax'] * (1+$value['karaoke_fee_rate']/100));
                $report->items[$key]['tax_fee'] = round($report->items[$key]['total_after_fee'] * ($value['tax_rate']/100));
                $report->items[$key]['stt'] = $i++;
            }
			//System::Debug($report->items);
			$this->print_all_pages($report);
		}
		else
		{
			$_REQUEST['date_from'] = date('d/m/Y');
            $_REQUEST['date_to'] = date('d/m/Y');
			$view_all = true;
			if(!User::can_view(false,ANY_CATEGORY) and User::can_view_detail(false,ANY_CATEGORY)){
				$_REQUEST['from_day'] = date('d');
				$view_all = false;
			}
			$karaoke_lists = DB::fetch_all('select karaoke.* from karaoke');
			$shift_lists = array();
			// Lấy danh sách các ca làm vc
			$shift_lists = DB::fetch_all('select karaoke_shift.* from karaoke_shift order by id ASC');
			$shift_list = '<option value="0">---------</option>';
			foreach($shift_lists as $k=>$shift){
				if(isset($karaoke_lists[$shift['karaoke_id']]['shifts'])){
					$karaoke_lists[$shift['karaoke_id']]['shifts'][$k] = $shift; 
				}else{
					$karaoke_lists[$shift['karaoke_id']]['shifts'][$k] = $shift;	
				}
				$shift_list .= '<option value="'.$shift['id'].'">'.$shift['name'].':'.$shift['brief_start_time'].'h - '.$shift['brief_end_time'].'h</option>';	
			} 
			//Lấy ra các account
			$users = DB::fetch_all('select account.id,party.full_name from account INNER JOIN party on party.user_id = account.id AND party.type=\'USER\' WHERE account.type=\'USER\' AND party.description_1=\'Nhà hàng\' ORDER BY account.id');			
			$this->parse_layout('search',
				array(			
				'user_id_list'=>array(''=>Portal::language('all_user'))+String::get_list($users),	
				'view_all'=>$view_all,
				'karaoke_id' => URL::get('karaoke_id',''),
				'shift_list' =>$shift_list,
				'karaoke_lists' =>String::array2js($karaoke_lists),
				'karaokes' =>DB::select_all('karaoke',false), 
				'hotel_id_list'=>array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list())
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
			if($count>=$this->line_per_page)
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
			if(Url::get('hotel_id')){
				$hotel = DB::fetch('SELECT NAME_1 AS name,ADDRESS FROM PARTY WHERE USER_ID = \''.Url::get('hotel_id').'\'');
				$hotel_name = $hotel['name']?$hotel['name']:HOTEL_NAME;
				$hotel_address = $hotel['address']?$hotel['address']:HOTEL_ADDRESS;
			}else{
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
		foreach($items as $id=>$item)
		{
			$order_id = '';
			for($i=0;$i<6-strlen($item['id']);$i++)
			{
				$order_id .= '0';
			}
			$order_id .= $item['id'];
			$items[$id]['code'] = $order_id;
		}
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
		if(Url::get('hotel_id')){
				$hotel = DB::fetch('SELECT NAME_1 AS name,ADDRESS FROM PARTY WHERE USER_ID = \''.Url::get('hotel_id').'\'');
				$hotel_name = $hotel['name'];
				$hotel_address = $hotel['address'];
			}else{
				$hotel_name = HOTEL_NAME;
				$hotel_address = HOTEL_ADDRESS;
			}	
		$this->parse_layout('header',
			array(
				'hotel_address'=>$hotel_address,
                'to_date'=>$to_day,
                'from_date'=>$from_day,
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
	
	
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        //System::debug($arr);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
?>