<?php
class ReservationSummaryBySalesmanForm extends Form
    {
	function ReservationSummaryBySalesmanForm()
    {
		Form::Form('ReservationSummaryBySalesmanForm');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
    {
		if(URL::get('do_search'))
        {
            $this->map = array();
            
            $from_date = Url::get('from_date')?Url::get('from_date'):('01/'.date('m/Y'));
            $_REQUEST['from_date'] = $from_date;
            $to_date = Url::get('to_date')?Url::get('to_date'):(cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y')).'/'.date('m/Y'));
            $_REQUEST['to_date'] = $to_date;
			
			$this->line_per_page = URL::get('line_per_page',30);
			$cond = '1=1';
            
			if(User::can_admin(false,ANY_CATEGORY))
            {
				$cond .= Url::get('portal_id')?' and reservation.portal_id = \''.Url::get('portal_id').'\'':'';
			}
            else
            {
				$cond .= ' AND reservation.portal_id = \''.PORTAL_ID.'\'';	
			}
            
			if((Url::get('status')!="0") AND (Url::get('status')!=""))
            {
				if(Url::get('status') == 'CHECKIN')
                {
					$cond.=' AND (reservation_room.status=\'CHECKIN\')';
				}
                elseif(Url::get('status') == 'BOOKED')
                {
					$cond.=' AND (reservation_room.status=\'BOOKED\')';
				}
                elseif(Url::get('status') == 'CHECKOUT')
                {
					$cond.=' AND (reservation_room.status=\'CHECKOUT\')';
				}
                elseif(Url::get('status') == 'CANCEL')
                {
					$cond.=' AND (reservation_room.status=\'CANCEL\')';
				}
                elseif(Url::get('status') == 'ALL_CANCEL')
                {
					$cond.=' AND reservation_room.status IN (\'CHECKIN\',\'BOOKED\',\'CHECKOUT\')';
				}
                else
                {
                    $cond.=' AND reservation_room.status=\''.Url::get('status').'\'';
                } 
			}
            
            if(Url::get('customer_group') AND Url::get('customer_group')!='all')
            {
                $group_id = Url::get('customer_group');
                $cond .= " AND customer.group_id = '$group_id'";
            }
            if(Url::get('customer_name'))
            {
				$cond .= ' AND customer.name = \''.Url::sget('customer_name').'\'';
			}
			if(Url::get('tour_name'))
            {
				$cond .= ' AND UPPER(tour.name) LIKE \'%'.strtoupper(Url::sget('tour_name')).'%\'';
			}
			if(Url::get('booking_code'))
            {
				$cond .= ' AND UPPER(reservation.booking_code) LIKE \'%'.strtoupper(Url::sget('booking_code')).'%\'';
			}
			if(Url::get('user_id'))
            {
				$cond .= ' AND (reservation.saler_id = \''.Url::sget('user_id').'\')';
            }
            $cond_extra_service = $cond;
            $cond_extra_service .= 'AND (extra_service_invoice_detail.in_date>=\''.Date_Time::to_orc_date($from_date).'\' AND extra_service_invoice_detail.in_date<=\''.Date_Time::to_orc_date($to_date).'\')';
            $cond .= ' and reservation_room.departure_time>=\''.Date_Time::to_orc_date($from_date).'\' and reservation_room.arrival_time<=\''.Date_Time::to_orc_date($to_date).'\' ';
			$sql = '
				SELECT 
					reservation_room.id
					,nvl(reservation_room.adult,0) as adult
					,reservation_room.price
                    ,reservation_room.net_price
                    ,reservation_room.service_rate
                    ,reservation_room.tax_rate
                    ,reservation_room.reduce_balance
                    ,reservation_room.reduce_amount
					,reservation_room.status
                    ,reservation_room.foc
                    ,reservation_room.foc_all
					,reservation_room.arrival_time
					,reservation_room.departure_time
                    ,nvl(reservation_room.change_room_from_rr,0) as change_room_from_rr
                    ,nvl(reservation_room.change_room_to_rr,0) as change_room_to_rr
					,room_level.brief_name as room_level
					,customer.name as customer_name
					,tour.name as tour_name
					,party.name_'.Portal::language().' as user_name
					,reservation.booking_code
					,reservation.time
					,reservation.id as reservation_id
                    ,room.name as room_name
                    
				FROM 
					reservation_room
					inner join reservation on reservation.id = reservation_room.reservation_id
					
					left outer join tour on reservation.tour_id = tour.id
					inner join customer on reservation.customer_id = customer.id
                    inner join party on party.user_id = reservation.saler_id
                    INNER JOIN account ON party.user_id = account.id
                    INNER JOIN portal_department ON portal_department.id= account.portal_department_id
                    left join room on reservation_room.room_id = room.id
                    left join room_level on room_level.id=room.room_level_id
                WHERE 
					'.$cond.'
                    and (room_level.is_virtual=0 or room_level.is_virtual is NULL )
                    AND account.is_active = 1
                    AND portal_department.department_code = \'SALES\'
				ORDER BY
                   reservation_room.arrival_time
			';	
			$report= DB::fetch_all($sql);
//System::debug($report);
            $items=array();
            $summary = array('total_recode'=>0,'total_adult'=>0,'total_night'=>0,'total_amount'=>0);
            foreach($report as $key=>$value)
            {
                $cond_reservation = 'AND (room_status.in_date>=\''.Date_Time::to_orc_date($from_date).'\' AND room_status.in_date<=\''.Date_Time::to_orc_date($to_date).'\')';
                //$cond_extra_service = 'AND (extra_service_invoice_detail.in_date>=\''.Date_Time::to_orc_date($from_date).'\' AND extra_service_invoice_detail.in_date<=\''.Date_Time::to_orc_date($to_date).'\')';
                
                $report[$key]['night'] = 0;
                $report[$key]['total'] = 0;
                $traveller = DB::fetch("SELECT concat(concat(traveller.first_name,' '),traveller.last_name) as name FROM traveller inner join reservation_traveller on traveller.id=reservation_traveller.traveller_id WHERE reservation_traveller.reservation_room_id=".$value['id']);
                $sql = "
                        SELECT
                            room_status.id,
                            room_status.change_price,
                            room_status.in_date,
                            reservation_room.reservation_id
                        FROM
                            room_status
                            inner join reservation_room on room_status.reservation_room_id=reservation_room.id
                        Where
                            reservation_room.id=".$value['id']."".$cond_reservation."
                        ";
                $record = DB::fetch_all($sql);
                foreach($record as $id=>$content)
                {
                    if($value['net_price'])
                        $content['change_price'] = $content['change_price']/(1+$value['service_rate']/100)/(1+$value['tax_rate']/100);
                    //GIAM GIA %
                    $content['change_price'] *= (1-$value['reduce_balance']/100);
                    //GIAM GIA SOTIEN
                    if($content['in_date'] == $value['arrival_time'])
                        $content['change_price'] -= $value['reduce_amount'];
                    //check option thue 
                    $amount = $content['change_price']*(1+$value['service_rate']/100)*(1+$value['tax_rate']/100);
                    if($value['foc']!='' OR $value['foc_all']==1)
                    {
                        $amount=0;
                    }
                    $night = 1;
                    if($content['in_date']==$value['departure_time'] AND $value['departure_time']!=$value['arrival_time'])
                    {
                        $night=0;
                        $amount=0;
                    }
                    if($value['departure_time']==$value['arrival_time'] AND $content['change_price']==0)
                    {
                        $night=0;
                    }
                    $report[$key]['night'] +=$night;
                    $report[$key]['total'] += $amount;
                }
                
                if($value['arrival_time']==$value['departure_time'])
                {
                    if($value['change_room_to_rr']=='')
                    {
                        $report[$key]['night'] = 'Dayuse';
                        $summary['total_night'] += 1;
                    }
                }
                else
                {
                    $summary['total_night'] += $report[$key]['night'];
                }
                $summary['total_adult'] += $value['adult'];
                $summary['total_amount'] += $report[$key]['total'];
                if(!isset($items[$value['reservation_id']]))
                {
                    $summary['total_recode'] += 1;
                    
                    $items[$value['reservation_id']]['id'] = $value['reservation_id'];
                    $items[$value['reservation_id']]['customer_name'] = $value['customer_name'];
                    $items[$value['reservation_id']]['booking_code'] = $value['booking_code'];
                    $items[$value['reservation_id']]['create_date'] = date('H:i d/m/Y',$value['time']);
                    $items[$value['reservation_id']]['count_child'] = 1;
                    $items[$value['reservation_id']]['child'][$value['id']]['id'] = $value['id'];
                    $items[$value['reservation_id']]['child'][$value['id']]['room_name'] = $value['room_name'];
                    $items[$value['reservation_id']]['child'][$value['id']]['arrival_time'] = Date_Time::convert_orc_date_to_date($value['arrival_time'],'/');
                    $items[$value['reservation_id']]['child'][$value['id']]['departure_time'] = Date_Time::convert_orc_date_to_date($value['departure_time'],'/');
                    $items[$value['reservation_id']]['child'][$value['id']]['adult'] = $value['adult'];
                    $items[$value['reservation_id']]['child'][$value['id']]['room_level'] = $value['room_level'];
                    $items[$value['reservation_id']]['child'][$value['id']]['night'] = $report[$key]['night'];
                    $items[$value['reservation_id']]['child'][$value['id']]['price'] = number_format($value['price']);
                    $items[$value['reservation_id']]['child'][$value['id']]['total'] = number_format($report[$key]['total']);
                    $items[$value['reservation_id']]['child'][$value['id']]['status'] = $value['status'];
                    $items[$value['reservation_id']]['child'][$value['id']]['user_name'] = $value['user_name'];
                    $items[$value['reservation_id']]['child'][$value['id']]['traveller_name'] = sizeof($traveller)>0?$traveller['name']:'';
                }
                else
                {
                    $items[$value['reservation_id']]['count_child'] += 1;
                    $items[$value['reservation_id']]['child'][$value['id']]['id'] = $value['id'];
                    $items[$value['reservation_id']]['child'][$value['id']]['room_name'] = $value['room_name'];
                    $items[$value['reservation_id']]['child'][$value['id']]['arrival_time'] = Date_Time::convert_orc_date_to_date($value['arrival_time'],'/');
                    $items[$value['reservation_id']]['child'][$value['id']]['departure_time'] = Date_Time::convert_orc_date_to_date($value['departure_time'],'/');
                    $items[$value['reservation_id']]['child'][$value['id']]['adult'] = $value['adult'];
                    $items[$value['reservation_id']]['child'][$value['id']]['room_level'] = $value['room_level'];
                    $items[$value['reservation_id']]['child'][$value['id']]['night'] = $report[$key]['night'];
                    $items[$value['reservation_id']]['child'][$value['id']]['price'] = number_format($value['price']);
                    $items[$value['reservation_id']]['child'][$value['id']]['total'] = number_format($report[$key]['total']);
                    $items[$value['reservation_id']]['child'][$value['id']]['status'] = $value['status'];
                    $items[$value['reservation_id']]['child'][$value['id']]['user_name'] = $value['user_name'];
                    $items[$value['reservation_id']]['child'][$value['id']]['traveller_name'] = sizeof($traveller)>0?$traveller['name']:'';
                }
                
                
            }
            $sql = "
                    SELECT
                        extra_service_invoice_detail.id,
                        extra_service_invoice_detail.price as change_price,
                        extra_service_invoice_detail.quantity,
                        extra_service_invoice_detail.in_date,
                        extra_service.code,
                        extra_service_invoice.tax_rate,
                        extra_service_invoice.service_rate,
                        extra_service_invoice.net_price,
                        extra_service_invoice.payment_type,
                        extra_service_invoice_detail.percentage_discount,
                        extra_service_invoice_detail.amount_discount,
                        reservation_room.id as reservation_room_id,
                        nvl(reservation_room.adult,0) as adult,
                        reservation_room.arrival_time,
				        reservation_room.departure_time,
                        reservation_room.foc_all,
                        reservation_room.status,
                        room_level.brief_name as room_level,
    					customer.name as customer_name,
    					tour.name as tour_name,
    					party.name_".Portal::language()." as user_name,
    					reservation.booking_code,
    					reservation.time,
    					reservation.id as reservation_id,
                        room.name as room_name
                    FROM
                        extra_service_invoice_detail
                        INNER JOIN  extra_service_invoice ON extra_service_invoice_detail.invoice_id=extra_service_invoice.id
                        INNER JOIN  extra_service ON extra_service.id = extra_service_invoice_detail.service_id
                        INNER JOIN  reservation_room ON reservation_room.id = extra_service_invoice.reservation_room_id
                        inner join reservation on reservation.id = reservation_room.reservation_id
                        left outer join tour on reservation.tour_id = tour.id
    					inner join customer on reservation.customer_id = customer.id
                        inner join party on party.user_id = reservation.saler_id
                        INNER JOIN account ON party.user_id = account.id
                        INNER JOIN portal_department ON portal_department.id= account.portal_department_id
                        left join room on reservation_room.room_id = room.id
                        left join room_level on room_level.id=room.room_level_id
                    Where
                        ".$cond_extra_service."
                        AND (extra_service.code = 'LATE_CHECKIN' 
                        OR extra_service.code = 'EARLY_CHECKIN' 
                        OR extra_service.code='LATE_CHECKOUT'
                        OR extra_service_invoice.payment_type = 'ROOM')
                        and (room_level.is_virtual=0 or room_level.is_virtual is NULL )
                        AND account.is_active = 1
                        AND portal_department.department_code = 'SALES'
    				ORDER BY
                       reservation_room.arrival_time
                    ";
             //System::debug($sql);
             $record = DB::fetch_all($sql);
             foreach($record as $code=>$content)
             {
                $amount = 0;
                $night = 0;
                //check net price
                if($content['net_price'])
                    $content['change_price'] = $content['change_price']/(1+$content['service_rate']/100)/(1+$content['tax_rate']/100);
                // giam gia %
                $content['change_price'] = $content['change_price'] - ($content['change_price']*$content['percentage_discount']/100);
                // giam gia so tien
                $content['change_price'] = $content['change_price'] - $content['amount_discount'];
                //check option thue 
                $amount = $content['change_price']*(1+$content['service_rate']/100)*(1+$content['tax_rate']/100);
                if($content['foc_all']==1)
                    {
                        $amount = 0;
                    }
                $amount = $amount*$content['quantity'];
                if(($content['code']=='LATE_CHECKIN' OR $content['code']=='EARLY_CHECKIN' OR $content['code']=='LATE_CHECKOUT') AND $content['payment_type']=='ROOM')
                {
                    $night =$content['quantity'];
                }
                $summary['total_night'] += $night;
                $summary['total_amount'] += $amount;
                if(!isset($items[$content['reservation_id']]))
                {
                    $traveller = DB::fetch("SELECT concat(concat(traveller.first_name,' '),traveller.last_name) as name FROM traveller inner join reservation_traveller on traveller.id=reservation_traveller.traveller_id WHERE reservation_traveller.reservation_room_id=".$content['reservation_room_id']);
                    $summary['total_recode'] += 1;
                    $summary['total_adult'] += $content['adult'];
                    $items[$content['reservation_id']]['id'] = $content['reservation_id'];
                    $items[$content['reservation_id']]['customer_name'] = $content['customer_name'];
                    $items[$content['reservation_id']]['booking_code'] = $content['booking_code'];
                    $items[$content['reservation_id']]['create_date'] = date('H:i d/m/Y',$content['time']);
                    $items[$content['reservation_id']]['count_child'] = 1;
                    $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['id'] = $content['reservation_room_id'];
                    $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['room_name'] = $content['room_name'];
                    $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['arrival_time'] = Date_Time::convert_orc_date_to_date($content['arrival_time'],'/');
                    $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['departure_time'] = Date_Time::convert_orc_date_to_date($content['departure_time'],'/');
                    $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['adult'] = $content['adult'];
                    $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['room_level'] = $content['room_level'];
                    $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['night'] = $night;
                    $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['price'] = number_format($content['change_price']);
                    $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['total'] = number_format($amount);
                    $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['status'] = $content['status'];
                    $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['user_name'] = $content['user_name'];
                    $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['traveller_name'] = sizeof($traveller)>0?$traveller['name']:'';
                }
                else
                {
                    if(!isset($items[$content['reservation_id']]['child'][$content['reservation_room_id']]))
                    {
                        $traveller = DB::fetch("SELECT concat(concat(traveller.first_name,' '),traveller.last_name) as name FROM traveller inner join reservation_traveller on traveller.id=reservation_traveller.traveller_id WHERE reservation_traveller.reservation_room_id=".$content['reservation_room_id']);
                        $summary['total_recode'] += 1;
                        $summary['total_adult'] += $content['adult'];
                        $items[$content['reservation_id']]['count_child'] += 1;
                        $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['id'] = $content['reservation_room_id'];
                        $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['room_name'] = $content['room_name'];
                        $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['arrival_time'] = Date_Time::convert_orc_date_to_date($content['arrival_time'],'/');
                        $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['departure_time'] = Date_Time::convert_orc_date_to_date($content['departure_time'],'/');
                        $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['adult'] = $content['adult'];
                        $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['room_level'] = $content['room_level'];
                        $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['night'] = $night;
                        $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['price'] = number_format($content['change_price']);
                        $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['total'] = number_format($amount);
                        $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['status'] = $content['status'];
                        $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['user_name'] = $content['user_name'];
                        $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['traveller_name'] = sizeof($traveller)>0?$traveller['name']:'';
                    }
                    else
                    {
                        $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['night'] += $night;
                        //$items[$content['reservation_id']]['child'][$content['reservation_room_id']]['price'] = number_format(System::calculate_number($items[$content['reservation_id']]['child'][$content['reservation_room_id']]['price'])+$content['change_price']);
                        $items[$content['reservation_id']]['child'][$content['reservation_room_id']]['total'] = number_format(System::calculate_number($items[$content['reservation_id']]['child'][$content['reservation_room_id']]['total'])+$amount);
                    }
                }
             }
            //System::debug($record);
            $this->parse_layout('report',array('items'=>$items,'summary'=>$summary,'to_date'=>$to_date,'from_date'=>$from_date)+$this->map);
            $this->parse_layout('footer',array());
		}
		else
		{ 
		  /** 7211 */
          /*$user_privigele=DB::fetch('select group_privilege_id from account_privilege_group where account_id=\''.User::id().'\'');
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
            }*/
            $users = DB::fetch_all('
				SELECT
					account.id,party.name_1 as name
				FROM
					account
                    INNER JOIN party on party.user_id=account.id
                    inner join portal_department on account.portal_department_id = portal_department.id
				WHERE
					party.type=\'USER\'
                    and portal_department.department_code = \'SALES\'
                    and account.is_active = 1
			');
          /** 7211 end*/
            $customer_group = DB::fetch_all("
                SELECT
                    customer_group.id,
                    customer_group.name
                FROM
                    customer_group
                ORDER BY
                    customer_group.id
                    
            ");	
            $from_date = Url::sget('from_date')?Url::sget('from_date'):('01/'.date('m/Y'));
            $_REQUEST['from_date'] = $from_date;
            $to_date = Url::sget('to_date')?Url::sget('to_date'):(cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y')).'/'.date('m/Y'));
    		$_REQUEST['to_date'] = $to_date;
			$this->parse_layout('search',
				array(
					'portal_id_list'=>array(''=>Portal::language('all')) + String::get_list(Portal::get_portal_list()),
					'user_id_list'=>array(''=>Portal::language('all'))+String::get_list($users),
                    'customer_group_list'=>array('all'=>Portal::language('all'))+String::get_list($customer_group)
				)
			);
		}			
	}
	function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
?>
