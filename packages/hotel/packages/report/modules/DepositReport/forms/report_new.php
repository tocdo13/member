<?php
class DepositReportForm extends Form
{
	function DepositReportForm()
	{
		Form::Form('DepositReportForm');
         $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
          $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
          $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{
		if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
			require_once 'packages/hotel/packages/reception/modules/includes/get_reservation.php';
		
            
            $from_day= Date_Time::to_time(Url::get('date_from'));
            $to_day =Date_Time::to_time(Url::get('date_to'));
             if(URL::get('hour_from')){
			 $this->map['start_shift_time'] = Url::get('hour_from');
                $hour_from= explode(':',URL::get('hour_from'));
                
              $from_day = $from_day + ((int)$hour_from[0]*60+(int)$hour_from[1])*60;
            }
            if(URL::get('hour_to')){
				$this->map['end_shift_time'] = Url::get('hour_to'); 
                $hour_to= explode(':', URL::get('hour_to'));
                $to_day = $to_day + ((int)$hour_to[0]*60+(int)$hour_to[1])*60;
            }
            
			$this->line_per_page = URL::get('line_per_page',999);
            $cond  = '  '
				.(URL::get('user_id')?' and payment.user_id = \''.URL::sget('user_id').'\'':'')
                .(URL::get('portal_id')?' and payment.portal_id = \''.URL::sget('portal_id').'\'':'') 
				.' and payment.time>='.$from_day.' and payment.time <= '.$to_day.'
                 '
			;
            /** Lấy ra đặt cọc phòng */
            $dps_room = $this->DepositRoom($cond);
            /** Lấy ra đặt cọc nhóm */
            $dps_group = $this->DepositGroup($cond);
            
            $dps_arr = $dps_room + $dps_group;
            //System::debug($dps_arr);
            $payment_type = array(
                                'CASH_VND' => 0,
                                'CASH_USD' => 0,
                                'CREDIT_CARD_VND' => 0,
                                'CREDIT_CARD_USD' => 0,
                                'BANK_VND' => 0,
                                'BANK_USD' => 0,
            );
            
            $report->items = array();
            $this->map['total_dps_type'] = $payment_type;
            $this->map['total_dps'] = 0;
            $this->map['total_use'] = 0;
            $this->map['total_remain'] = 0;
            
            foreach($dps_arr as $key => $value)
            {
                $key_id = $value['reservation_id'].'_'.$value['type_dps'];
                if(!isset($report->items[$key_id]))
                {
                    $report->items[$key_id]['id'] = $key_id;
                    $report->items[$key_id]['reservation_id'] = $value['reservation_id'];
                    $report->items[$key_id]['child'] = array();
                    $report->items[$key_id]['total_dps'] = 0;
                    $report->items[$key_id]['total_use'] = 0;
                    $report->items[$key_id]['total_remain'] = 0;
                    $report->items[$key_id]['rowspan'] = 0;                   
                }
                $report->items[$key_id]['child'][$key] = $value;
                $report->items[$key_id]['child'][$key]['type_payment'] = $payment_type;
                $report->items[$key_id]['total_dps'] += $value['dps_vnd'];
                $report->items[$key_id]['rowspan']++;
                /** Lấy ra đặt cọc đã sử dụng */
                $dps_use = $this->DepositUse($value['reservation_id'],$value['reservation_room_id']);
                if(isset($dps_use[$key_id]))
                {
                    $report->items[$key_id]['total_use'] = $dps_use[$key_id]['total_amount'];                    
                }
                $report->items[$key_id]['total_remain'] = $report->items[$key_id]['total_dps'] - $report->items[$key_id]['total_use'];
                if($value['currency_id'] == 'VND')
                {
                    $report->items[$key_id]['child'][$key]['type_payment'][$value['payment_type_id']."_".$value['currency_id']] += $value['deposit_vnd'];
                    $this->map['total_dps_type'][$value['payment_type_id']."_".$value['currency_id']] += $value['deposit_vnd'];                   
                }else
                {
                    $report->items[$key_id]['child'][$key]['type_payment'][$value['payment_type_id']."_".$value['currency_id']] += $value['deposit_usd']; 
                    $this->map['total_dps_type'][$value['payment_type_id']."_".$value['currency_id']] += $value['deposit_usd'];                     
                }
                $this->map['total_dps'] += $report->items[$key_id]['total_dps'];
                $this->map['total_use'] += $report->items[$key_id]['total_use'];
                $this->map['total_remain'] += $report->items[$key_id]['total_remain'];
            }
            ksort($report->items);
            //System::debug($report->items);
            $this->map['items'] = $report->items;
            $this->parse_layout('report_new', array(
                         'to_date'=>date('H:i-d/m/Y',$to_day),
                         'from_date'=>date('H:i-d/m/Y',$from_day)
			)+$this->map);
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
			$this->parse_layout('search',
				array(				
				'portal_id_list'=>array(''=>Portal::language('all')) + String::get_list(Portal::get_portal_list()),
				'user_id_list'=>array(''=>Portal::language('all'))+String::get_list($users),
				'view_all'=>$view_all,
                'users'=>$users,
				'reservation_type_id_list'=>array(''=>Portal::language('All'))+String::get_list(DB::select_all('reservation_type')),
				)
			);	
		}			
	}
    
    function DepositRoom($cond)
    {
        $sql = 'SELECT 	
                    payment.id, 
        			payment.bill_id as order_id,
        			to_char(FROM_UNIXTIME(payment.time), \'DD/MM/YYYY\') as deposit_date,
        			payment.user_id as deposit_user_id,
        			CASE 
        				WHEN currency_id=\'VND\'
        					THEN 
        						payment.amount --/ COALESCE(payment.exchange_rate,1)
        				ELSE 	0	
        			END	 as deposit_vnd,
                    CASE 
        				WHEN currency_id=\'USD\'
        					THEN 
        						payment.amount --* COALESCE(payment.exchange_rate,1)
        				ELSE 	0	
        			END	 as deposit_usd,
                    payment.amount * COALESCE(payment.exchange_rate,1) as dps_vnd,
        			payment.currency_id,
        			room.name as room_name,
        			customer.name as customer_name,
                    payment.type_dps,
                    payment.description as note,
                    payment.reservation_room_id,
                    reservation_room.reservation_id,
                    payment_type.name_'.Portal::language().' as payment_type_name,
                    payment.payment_type_id,
                    0 as deposit_BANK,
                    0 as deposit_CASH,
                    0 as deposit_CREDIT_CARD
        		FROM 
                    payment
        			inner join reservation_room ON payment.bill_id = reservation_room.id
        			inner join reservation ON reservation.id = reservation_room.reservation_id
        			left outer join customer on reservation.customer_id = customer.id
        			left outer join room ON room.id = reservation_room.room_id
                    left join payment_type on payment.payment_type_id = payment_type.def_code
        		WHERE 
                    type_dps = \'ROOM\'
                    '.$cond.'
                ORDER BY
                    reservation.id DESC
        ';
        $dpsroom = DB::fetch_all($sql);
        
        return $dpsroom;
    }
    
    function DepositGroup($cond)
    {
        $sql = 'SELECT 
                    payment.id as id,
					payment.bill_id as order_id,
					to_char(FROM_UNIXTIME(payment.time), \'DD/MM/YYYY\') as deposit_date,
					payment.user_id as deposit_user_id,
					CASE 
						WHEN currency_id=\'VND\'
							THEN 
								payment.amount --/ COALESCE(payment.exchange_rate,1)
						ELSE 	0	
					END	 as deposit_vnd,
                    CASE 
						WHEN currency_id=\'USD\'
							THEN 
								payment.amount --* COALESCE(payment.exchange_rate,1)
						ELSE 	0	
					END	 as deposit_usd,
                    payment.amount * COALESCE(payment.exchange_rate,1) as dps_vnd,
					payment.currency_id,
					\' \' as room_name,
					customer.name as customer_name,
                    payment.type_dps,
                    payment.description as note,
                    payment.reservation_room_id,
                    payment.reservation_id,
                    payment_type.name_'.Portal::language().' as payment_type_name,
                    payment.payment_type_id,
                    0 as deposit_bank,
                    0 as deposit_cash,
                    0 as deposit_credit_card
				FROM 
                    payment
					inner join reservation ON payment.bill_id = reservation.id
					inner join customer on reservation.customer_id = customer.id
                    left join payment_type on payment.payment_type_id = payment_type.def_code
				WHERE 
                    type_dps = \'GROUP\'
                    '.$cond.'
                ORDER BY
                    reservation.id DESC
        ';
        $dpsgroup = DB::fetch_all($sql);
        
        return $dpsgroup;
    }
    
    function DepositUse($r_id,$rr_id)
    {
        $cond =' AND 1=1';
        if($rr_id != '')
        {
            $cond = ' OR traveller_folio.reservation_room_id = \''.$rr_id.'\'';
        }
        $sql = '
                SELECT
                    traveller_folio.*
                FROM
                    traveller_folio
                WHERE
                    (traveller_folio.type = \'DEPOSIT\' or traveller_folio.type = \'DEPOSIT_GROUP\')
                    AND traveller_folio.reservation_id = '.$r_id.'
                    '.$cond.'
        ';
        
        $dps_use = DB::fetch_all($sql);
        //System::debug($dps_use);
        $items = array();
        foreach($dps_use as $key => $value)
        {
            $key_r_id = $value['reservation_id'];
            if($value['reservation_room_id'])
            {
                $key_r_id = DB::fetch('SELECT reservation_id FROM reservation_room WHERE id = \''.$value['reservation_room_id'].'\'','reservation_id');
            }
            if($value['type'] == 'DEPOSIT')
            {
                $key_id = $key_r_id.'_ROOM';
            }else
            {
                $key_id = $key_r_id.'_GROUP';                
            }
            if(!isset($items[$key_id]))
            {
                $items[$key_id]['id'] = $key_id;
                $items[$key_id]['reservation_id'] = $value['reservation_id'];
                $items[$key_id]['total_amount'] = 0;
            }
            $items[$key_id]['total_amount'] += $value['total_amount'];            
        }
        //System::debug($items);
        
        return $items;
    }
}
?>
