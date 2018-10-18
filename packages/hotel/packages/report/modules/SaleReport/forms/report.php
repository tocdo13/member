<?php
class SaleReportForm extends Form
{
	function SaleReportForm()
	{
		Form::Form('SaleReportForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    	$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');     
	}
	function draw()
	{
       require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
        $this->day = date('d/m/Y');
        $this->map = array();
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
        $_REQUEST['from_date'] = $this->map['from_date'];
         $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        $_REQUEST['to_date'] = $this->map['to_date'];
        
        $day_orc = Date_Time::to_orc_date(date('d/m/Y'));
        
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
       	$users = DB::fetch_all('select account.id,party.full_name from account INNER JOIN party on party.user_id = account.id AND party.type=\'USER\' WHERE account.type=\'USER\' ORDER BY account.id');
        $this->map['user_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list($users);
        if(Url::get('portal_id'))
        {
            $portal_id = Url::get('portal_id');
        }
        else
        {
            $portal_id = PORTAL_ID;
            $_REQUEST['portal_id'] = PORTAL_ID;                       
        }
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
            $to_time = $this->calc_time($this->map['to_time'])+59;
            $_REQUEST['to_time'] = $this->map['to_time'];
        }
        
        $from_time_view = Date_Time::to_time($this->map['from_date']) + $from_time;                                
        $to_time_view = Date_Time::to_time($this->map['to_date']) + $to_time;
        //tan them
        $all_payments = DB::fetch_all('select payment_type.def_code as id,payment_type.name_'.Portal::language().' as name from payment_type where def_code is not null AND def_code<>\'ROOM CHARGE\'');
        $this->map['payment_type_list']=array(''=>Portal::language('all'))+String::get_list($all_payments);
        $cond2 =(Url::get('customer')?' and UPPER(customer.name) = \''.strtoupper(URL::get('customer')).'\'':'');
        $cond2 .=(Url::get('folio_number')?' and folio.id = \''.URL::get('folio_number').'\'':'');
		$cond2 .=(Url::get('room_name')?' and room.name = \''.URL::get('room_name').'\'':'');
        $cond2 .=(Url::get('payment_type')?' and payment.payment_type_id = \''.URL::get('payment_type').'\'':'');
        $join =(Url::get('payment_type')?'LEFT OUTER JOIN payment ON folio.id = payment.folio_id and payment.payment_type_id = \''.URL::get('payment_type').'\'':'');
        //end tan them
        $cond =' 1 = 1 ';
        if($portal_id != 'ALL')
        {
            $cond.=' AND folio.portal_id = \''.$portal_id.'\' '; 
        }
        if(Url::get('user_id')!='ALL' && Url::get('user_id')!='' )
        {
             $cond.=' AND folio.user_id = \''.Url::get('user_id').'\'';
        }
        //Tìm kiếm trong ngày hnay e1
       if(Url::get('search_invoice')==''){
             $cond .= ' 
					AND folio.create_time >= \''.$from_time_view.'\'
                    AND folio.create_time <= \''.$to_time_view.'\'  
				';
        }
        $this->map['portal_department'] = DB::fetch_all('select portal_department.id,portal_department.department_code from portal_department inner join department on department.code = portal_department.department_code and department.parent_id=0 where  department.code!=\'WH\' and portal_department.portal_id=\''.$portal_id.'\' order by portal_department.department_code DESC '); 
        //ĐẾM SỐ VÉ
        $count_room1 = DB::fetch_all('
			SELECT 
				folio.id
				,count(reservation_room.id) as num
			FROM
				folio
				inner join reservation on reservation.id = folio.reservation_id
                inner join reservation_room on reservation_room.reservation_id = reservation.id and folio.reservation_room_id is null
                inner join room on room.id = reservation_room.room_id
                left join customer on customer.id = folio.customer_id
                '.$join.'
            WHERE
				'.$cond.$cond2.'             
			GROUP BY folio.id
			');
            
            
        $count_room2 = DB::fetch_all('
			SELECT 
				folio.id
				,count(reservation_room.id) as num
			FROM
				folio
                inner join reservation_room on folio.reservation_room_id = reservation_room.id
                inner join room on room.id = reservation_room.room_id
                left join customer on customer.id = folio.customer_id
                '.$join.'
            WHERE
				'.$cond.$cond2.'             
			GROUP BY folio.id
			');
        $count_room = $count_room1 + $count_room2;
        //System::debug($count_room);
        //exit();
        $bill_id = '0';
        foreach($count_room as $ke=>$val)
        {
            $bill_id .=','.$val['id'];
        }
        
        $sql1 = '
			SELECT
                folio.id || \'_\' || reservation_room.id as id, 
                reservation_room.id as reservation_room_id,
                reservation.id as reservation_id,
                folio.id as code,
                folio.total,
                folio.code as folio_code,
                room.name as room_name,
                folio.customer_id as customer_id,
                --Cho nay hoi cu chuoi :)
                -- deu hieu vi sao traveller_id = reservation_traveller_id
                reservation_traveller.id as traveller_id,
                CASE
					WHEN
						folio.reservation_traveller_id is null
                    THEN
					   customer.name	
					ELSE
						CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name))
                END guest_name,
				reservation_room.time_in,
				reservation_room.time_out,		
                reservation_room.price,
                reservation_room.deposit as de,
                reservation.deposit as de_gr,
                reservation_room.reduce_amount 
			FROM 
			    folio
                inner join reservation on reservation.id = folio.reservation_id
                inner join reservation_room on reservation_room.reservation_id = reservation.id and folio.reservation_room_id is null
                inner join room on room.id = reservation_room.room_id
                left join customer on customer.id = folio.customer_id
                left join reservation_traveller on reservation_traveller.id = folio.reservation_traveller_id
                left join traveller on traveller.id = reservation_traveller.traveller_id
                '.$join.'
            WHERE 
			    '.$cond.$cond2.' 
			ORDER BY
			     folio.id 
			'; // ROW_NUMBER() OVER (ORDER BY folio.id )     
              
        $sql2 = '
			SELECT
                folio.id || \'_\' || reservation_room.id as id, 
                reservation_room.id as reservation_room_id,
                reservation_room.id as id_r_r_lt,
                reservation.id as reservation_id,
                folio.id as code,
                folio.total,
                folio.code as folio_code,
                room.name as room_name,
                folio.customer_id as customer_id,
                reservation_traveller.id as traveller_id,
                CASE
					WHEN
						folio.reservation_traveller_id is null
                    THEN
					   customer.name	
					ELSE
						CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name))
                END guest_name,
                CASE
					WHEN
						folio.reservation_traveller_id is null
                    THEN
					   	reservation_room.time_in
					ELSE
						reservation_traveller.arrival_time
                END time_in,
                CASE
					WHEN
						folio.reservation_traveller_id is null
                    THEN
					   reservation_room.time_out	
					ELSE
						reservation_traveller.departure_time
                END time_out,
                reservation_room.price,
                reservation_room.deposit as de,
                reservation.deposit as de_gr,
                reservation_room.reduce_amount
			FROM 
			    folio
                inner join reservation on reservation.id = folio.reservation_id
                inner join reservation_room on folio.reservation_room_id = reservation_room.id
                inner join room on room.id = reservation_room.room_id
                left join customer on customer.id = folio.customer_id
                left join reservation_traveller on reservation_traveller.id = folio.reservation_traveller_id
                left join traveller on traveller.id = reservation_traveller.traveller_id
                '.$join.'
            WHERE 
			    '.$cond.$cond2.' 
			ORDER BY
			     folio.id DESC
			';                
        $report = new Report;
        $report->items1 = DB::fetch_all($sql1);
         
        $report->items2 = DB::fetch_all($sql2);
       
        $report->items = $report->items1 + $report->items2;
        
               
        $payments = $this->get_payments($bill_id); 
        
        $traveller_folios = $this->get_traveller_folios($bill_id);
        
        
        $i=1;
        $res_id = false;
        foreach($report->items as $key=>$value)
		{
            $total_paid = 0; $total_debit = 0;
            $report->items[$key]['credit_card'] = 0;
            $report->items[$key]['credit_card_usd'] = 0;
            $report->items[$key]['credit_card_vnd'] = 0;
            $report->items[$key]['cash'] = 0;
            $report->items[$key]['cash_vnd'] = 0;
            $report->items[$key]['cash_usd'] = 0;
            $report->items[$key]['refund'] = 0;
            $report->items[$key]['refund_usd'] = 0;
            $report->items[$key]['refund_vnd'] = 0;
            $report->items[$key]['bank'] = 0;
            $report->items[$key]['foc'] = 0;
            $report->items[$key]['deposit'] = 0;
            $report->items[$key]['deposit_group'] = 0;
			$report->items[$key]['reduce_amount'] = 0;
            $report->items[$key]['minibar'] = 0;
            $report->items[$key]['laundry'] = 0;
            $report->items[$key]['equip'] = 0;
            $report->items[$key]['room'] = 0;
            $report->items[$key]['telephone'] = 0;
            $report->items[$key]['restaurant'] = 0;
            $report->items[$key]['extra_service'] = 0;
            $report->items[$key]['tour'] = 0;
            $report->items[$key]['karaoke'] = 0;
            $report->items[$key]['vending'] = 0;
            $report->items[$key]['banquet'] = 0;
            $report->items[$key]['ticket'] = 0;
            $report->items[$key]['extra_bed'] = 0;
			$report->items[$key]['break_fast'] = 0;
            $report->items[$key]['transport'] = 0;
            $report->items[$key]['spa'] = 0;
            $report->items[$key]['exchange_rate'] = 0;
            foreach($traveller_folios as $k=>$traveller_folio)
            {
                //if($traveller_folio['folio_id'] == $value['code'] && $traveller_folio['type'] == 'DEPOSIT_GROUP' )
//                {
//                    $report->items[$key]['group_deposit'] += $traveller_folio['amount'];
//                }
                if($traveller_folio['folio_id'] == $value['code']) //&& $traveller_folio['reservation_room_id'] == $value['reservation_room_id'] )
                {
                    if (isset($value['id_r_r_lt']) and $traveller_folio['add_payment'] == 1)
                    {
                        //echo '=='.$key.'<br>';
                        if (!isset($report->items[$key + 1]))
                        {
                            $report->items[$key + 1] = $report->items[$key];
                            $add_sql = 'select
                                            rr.id,
                                            rr.time_in,
                                            rr.time_out,
                                            room.name as room_name
                                        from
                                            reservation_room rr
                                            inner join room on rr.room_id = room.id
                                        where
                                            rr.id = '. $traveller_folio['reservation_room_id'];
                            $add_info = DB::fetch($add_sql);
                            if (!empty($add_info))
                            {
                                $report->items[$key + 1]['time_in'] = $add_info['time_in'];
                                $report->items[$key + 1]['time_out'] = $add_info['time_out'];
                                $report->items[$key + 1]['room_name'] = $add_info['room_name'];
                            }
                            $count_room[$value['code']]['num'] += 1;
                            $report->items[$key + 1]['credit_card'] = 0;
                            $report->items[$key + 1]['credit_card_usd'] = 0;
                            $report->items[$key + 1]['credit_card_vnd'] = 0;
                            $report->items[$key + 1]['cash'] = 0;
                            $report->items[$key + 1]['refund'] = 0;
                            $report->items[$key + 1]['deposit'] = 0;
                            $report->items[$key + 1]['deposit_group'] = 0;
                            $report->items[$key + 1]['reduce_amount'] = 0;
                            $report->items[$key + 1]['minibar'] = 0;
                            $report->items[$key + 1]['laundry'] = 0;
                            $report->items[$key + 1]['equip'] = 0;
                            $report->items[$key + 1]['room'] = 0;
                            $report->items[$key + 1]['telephone'] = 0;
                            $report->items[$key + 1]['restaurant'] = 0;
                            $report->items[$key + 1]['extra_service'] = 0;
                            $report->items[$key + 1]['tour'] = 0;
                            $report->items[$key + 1]['karaoke'] = 0;
                            $report->items[$key + 1]['vending'] = 0;
                            $report->items[$key + 1]['banquet'] = 0;
                            $report->items[$key + 1]['ticket'] = 0;
                            $report->items[$key + 1]['extra_bed'] = 0;
                            $report->items[$key + 1]['spa'] = 0;
                            $report->items[$key + 1]['exchange_rate'] = 0;
                        }
                        if($traveller_folio['type'] == 'DISCOUNT')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key + 1]['reduce_amount'] += $value['reduce_amount'];
                        }
                        else if($traveller_folio['type'] == 'DEPOSIT')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key + 1]['deposit'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'DEPOSIT_GROUP')
                        {
                            $report->items[$key + 1]['deposit_group'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'MINIBAR')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key + 1]['minibar'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'LAUNDRY')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key + 1]['laundry'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'EQUIPMENT')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key + 1]['equip'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'ROOM' || ($traveller_folio['type'] == 'EXTRA_SERVICE' && $traveller_folio['payment_type'] =='ROOM'))
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key + 1]['room'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'TELEPHONE')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key + 1]['telephone'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'BAR')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key + 1]['restaurant'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'EXTRA_SERVICE' && $traveller_folio['payment_type'] =='SERVICE')
                        {
                            if($traveller_folio['service_id'] != 40 && strtoupper(substr($traveller_folio['code'],0,4)) != 'TOUR' )
                            {
                                if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                                $report->items[$key + 1]['extra_service'] += $traveller_folio['amount'];
                            }
                            else
                            {
                                if(strtoupper(substr($traveller_folio['code'],0,4)) == 'TOUR')
                                {
                                    if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                                    $report->items[$key + 1]['tour'] += $traveller_folio['amount'];
                                }
                                if($traveller_folio['service_id'] == 40)
                                {
                                    if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                                    $report->items[$key + 1]['extra_bed'] += $traveller_folio['amount'];
                                } 
                            }
                        }
                        else if($traveller_folio['type'] == 'MASSAGE')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key + 1]['spa'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'KARAOKE')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key + 1]['karaoke'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'VE')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key + 1]['vending'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'TICKET')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key + 1]['ticket'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'BANQUET')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key + 1]['banquet'] += $traveller_folio['amount'];
                        }
                    }
                    else
                    //if ($traveller_folio['add_payment'] == 0)
                    {
                        if($traveller_folio['type'] == 'DISCOUNT')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key]['reduce_amount'] += $value['reduce_amount'];
                        }
                        else if($traveller_folio['type'] == 'DEPOSIT')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key]['deposit'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'DEPOSIT_GROUP')
                        {
                            $report->items[$key]['deposit_group'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'MINIBAR')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key]['minibar'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'LAUNDRY')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key]['laundry'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'EQUIPMENT')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key]['equip'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'ROOM' || ($traveller_folio['type'] == 'EXTRA_SERVICE' && $traveller_folio['payment_type'] =='ROOM'))
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key]['room'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'TELEPHONE')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key]['telephone'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'BAR')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key]['restaurant'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'EXTRA_SERVICE' && $traveller_folio['payment_type'] =='SERVICE')
                        {
                            if($traveller_folio['service_id'] != 40 && strtoupper(substr($traveller_folio['code'],0,4)) != 'TOUR')
                            {
                                if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                                $report->items[$key]['extra_service'] += $traveller_folio['amount'];
                            }
                            else
                            {
                                if(strtoupper(substr($traveller_folio['code'],0,4)) == 'TOUR')
                                {
                                    if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                                    $report->items[$key]['tour'] += $traveller_folio['amount'];
                                }
                                if($traveller_folio['service_id'] == 40)
                                {
                                    if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                                    $report->items[$key]['extra_bed'] += $traveller_folio['amount'];
                                } 
                            } 
                                
                            
                        }
                        else if($traveller_folio['type'] == 'MASSAGE')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key]['spa'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'KARAOKE')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key]['karaoke'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'VE')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key]['vending'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'TICKET')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key]['ticket'] += $traveller_folio['amount'];
                        }
                        else if($traveller_folio['type'] == 'BANQUET')
                        {
                            if($traveller_folio['reservation_room_id']==$value['reservation_room_id'])
                            $report->items[$key]['banquet'] += $traveller_folio['amount'];
                        }   
                    }
                }
                
            }
     
            foreach($payments as $k=>$pay)
            {
                $report->items[$key]['exchange_rate'] = $pay['exchange_rate'];
				if($pay['folio_id'] == $value['code'])
                {
                    
                    if($pay['type_dps']!='' && $pay['type_dps']!='(auto)')
                    {
                       //$report->items[$key]['deposit']+=$pay['total_vnd'];
                       
                    }
                    else
                    {
        				if($pay['payment_type_id']=='DEBIT')
                        {
        					$total_debit += $pay['total_vnd'];
        				}
                        else if($pay['payment_type_id']=='CREDIT_CARD')
                        {
                            if($pay['currency_id']=='USD')
                            {
                                $report->items[$key]['credit_card_usd'] += $pay['total'];
                            }
                            if($pay['currency_id']=='VND')
                            {
                                $report->items[$key]['credit_card_vnd'] += $pay['total_vnd'];
                            }
        					$report->items[$key]['credit_card'] += $pay['total_vnd'];   
        				}
                        else if($pay['payment_type_id']=='FOC')
                        {
        				     $report->items[$key]['foc'] += $pay['total_vnd'];
        				}
                        else if($pay['payment_type_id']=='BANK')
                        {
        				     $report->items[$key]['bank'] += $pay['total_vnd'];
        				}
                        else if($pay['payment_type_id']=='CASH')
                        {
                            if($pay['currency_id']=='USD')
                            {
                                $report->items[$key]['cash_usd'] += $pay['total'];
                            }
                            if($pay['currency_id']=='VND')
                            {
                                $report->items[$key]['cash_vnd'] += $pay['total_vnd'];
                            }
        					$report->items[$key]['cash'] += $pay['total_vnd'];
                            if($pay['currency_id']=='USD')
                            {
                                $report->items[$key]['cash'] = substr($report->items[$key]['cash'],0,-2).'00';
                            }
                            //echo $report->items[$key]['cash'];
        				}
                        else if($pay['payment_type_id']=='REFUND')
                        {
                            if($pay['currency_id']=='USD')
                            {
                                $report->items[$key]['refund_usd'] += $pay['total'];
                            }
                            if($pay['currency_id']=='VND')
                            {
                                $report->items[$key]['refund_vnd'] += $pay['total_vnd'];
                            }
        					$report->items[$key]['refund'] += $pay['total_vnd'];
                            if($pay['currency_id']=='USD')
                            {
                                $report->items[$key]['refund'] = substr($report->items[$key]['refund'],0,-2).'00';
                            }
        				}
                        if($pay['payment_type_id']=='REFUND')
        				    $total_paid -= $pay['total_vnd'];
                        else
                            $total_paid += $pay['total_vnd'];
                    }	
				}
			}
            $report->items[$key]['debit'] = (int)($total_debit + round($value['total'] - $total_paid));
            if($report->items[$key]['debit']>100)
            $report->items[$key]['debit'] = $report->items[$key]['debit'];
            else
            $report->items[$key]['debit'] = 0;
            //KID THÊM 
            if (isset($report->items[$key + 1]))
            {
                $report->items[$key + 1]['debit'] = $report->items[$key]['debit'];
                $report->items[$key + 1]['cash'] = $report->items[$key]['cash'];
                $report->items[$key + 1]['cash_vnd'] = $report->items[$key]['cash_vnd'];
                $report->items[$key + 1]['cash_usd'] = $report->items[$key]['cash_usd'];
                $report->items[$key + 1]['refund'] = $report->items[$key]['refund'];
                $report->items[$key + 1]['refund_vnd'] = $report->items[$key]['refund_vnd'];
                $report->items[$key + 1]['refund_usd'] = $report->items[$key]['refund_usd'];
                $report->items[$key + 1]['credit_card'] = $report->items[$key]['credit_card'];
                $report->items[$key + 1]['credit_card_vnd'] = $report->items[$key]['credit_card_vnd'];
                $report->items[$key + 1]['credit_card_usd'] = $report->items[$key]['credit_card_usd'];
                $report->items[$key + 1]['exchange_rate'] = $report->items[$key]['exchange_rate'];
            }
            
        }
       // if(User::is_admin())
            //System::debug($report->items);
        /** -START- KimTan: doan code nay chi lay nhung phong co thanh toan trong 1 folio trong hoa don nhom **/
        //if(User::is_admin())
        //{
        //System::debug($count_room);
        foreach($report->items as $key=>$value)
        {
            $total = $value['extra_service'] 
                     + $value['minibar']
                     + $value['restaurant']
                     + $value['laundry']
                     + $value['telephone']
                     + $value['equip']
                     + $value['spa']
                     + $value['room']
                     + $value['extra_bed']
                     + $value['break_fast']
                     + $value['tour']
                     +$value['karaoke']
                     +$value['vending']
                     +$value['banquet']
                     +$value['ticket']
                     ;
            if($total == 0)
            {
                $count_room[$value['code']]['num']--;
                unset($report->items[$key]);
            }
        }
        /** -END- KimTan: doan code nay chi lay nhung phong co thanh toan trong 1 folio trong hoa don nhom **/
        krsort($report->items);
        
        $res_id = false;
        foreach($report->items as $key=>$value)
        {
            if($report->items[$key]['code'] != $res_id)
            {
                
                $report->items[$key]['stt'] = $i++;
                $res_id = $report->items[$key]['code'];
                
            }
        }

        $this->print_all_pages($report,$count_room);

	}
    
	function print_all_pages(&$report,$count_room)
	{
	    $count = 0;
		$total_page = 1;
		$pages = array();
       
        foreach($report->items as $key=>$item)
		{
		      
		    if(isset($report->items[$key]['stt']))
            {
                
                $count+= 1;
                if($count>$this->map['line_per_page'])
            	{
            		$count = 1;
            		$total_page++;
            	}  
            }
            //count >= so dong tren 1 trang thi reset ve 0 va tang so trang len 1
            
			$pages[$total_page][$key] = $item;
			//$count++;
		}
        $arr = array_keys($pages);
        if(!empty($arr))
        {
            $begin = $arr['0'];
            //trang cuoi cung dc in
            $end = $begin + $this->map['no_of_page'] - 1;
            //Xoa bo cac phan tu trong mang ma co key (o day la page < end page)
            for($i = $total_page; $i> $end; $i--)
                unset($pages[$i]);      
        }
        if(sizeof($pages)>0)
		{
		  	$this->group_function_params = array(
        			'room_count'=>0,
                    'real_room_count'=>0,
                    'total_page'=>1,
                    'real_total_page'=>1,
                    'line_per_page' =>999,
                    'no_of_page' =>50,
                    'start_page' =>1,
                    'real_total_guest'=>0,
                    'total_room_total'=>0,
                    'total_minibar_total'=>0,
                    'total_laundry_total'=>0,
                    'total_equip_total'=>0,
                    'total_restaurant_total'=>0,
                    'total_telephone_total'=>0,
                    'total_extra_service_total'=>0,
                    'total_extra_bed_total'=>0,
                    'total_spa_total'=>0,
                    'total_karaoke_total'=>0,
                    'total_vending_total'=>0,
                    'total_ticket_total'=>0,
                    'total_banquet_total'=>0,
                    'total_tour_total'=>0,
        			'total_break_fast_total'=>0,
                    'total_credit_total'=>0,
                    'total_credit_usd_total'=>0,
                    'total_credit_vnd_total'=>0,
                    'total_foc_total'=>0,
                    'total_cash_total'=>0,
                    'total_cash_vnd_total'=>0,
                    'total_cash_usd_total'=>0,
                    'total_refund_total'=>0,
                    'total_refund_vnd_total'=>0,
                    'total_refund_usd_total'=>0,
                    'total_bank_total'=>0,
                    'total_deposit_total'=>0,
                    'total_group_deposit_total'=>0,
        			'total_reduce_amount_total'=>0,
                    'total_debit_total'=>0,
                    'exchange_rate'=>0,
				);
            
            $this->map['real_total_page']=count($pages);
            $this->map['real_page_no'] = 1;
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $page_no,$total_page,$count_room);
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
	function print_page($items, $page_no,$total_page,$count_room)
	{    	   
         $all_payments = DB::fetch_all('select payment_type.*,payment_type.name_'.Portal::language().' as name from payment_type where def_code is not null and payment_type.def_code != \'FOC\' and payment_type.def_code != \'ROOM_CHARGE\'');  
		 $report->payment_types = $all_payments;
           
		$last_group_function_params = $this->group_function_params;
        
		foreach($items as $k => $item)
		{
            $this->group_function_params['real_room_count']++;
            $this->group_function_params['total_room_total'] += round($item['room']);
            $this->group_function_params['total_minibar_total'] += round($item['minibar']);
            $this->group_function_params['total_laundry_total'] += round($item['laundry']);
            $this->group_function_params['total_equip_total'] += round($item['equip']);
            $this->group_function_params['total_telephone_total'] += round($item['telephone']);
            $this->group_function_params['total_restaurant_total'] += round($item['restaurant']);
            $this->group_function_params['total_spa_total'] += round($item['spa']);
            $this->group_function_params['total_extra_service_total'] += round($item['extra_service']);
            $this->group_function_params['total_extra_bed_total'] += round($item['extra_bed']);
			$this->group_function_params['total_break_fast_total'] += round($item['break_fast']);
            $this->group_function_params['total_tour_total'] += round($item['tour']);
            $this->group_function_params['total_karaoke_total'] += round($item['karaoke']);
            $this->group_function_params['total_vending_total'] += round($item['vending']);
            $this->group_function_params['total_ticket_total'] += round($item['ticket']);
            $this->group_function_params['total_banquet_total'] += round($item['banquet']);
            $this->group_function_params['exchange_rate'] = isset($item['exchange_rate'])?round($item['exchange_rate']):0;
            $this->group_function_params['total_deposit_total'] += $item['deposit'];
            $this->group_function_params['total_reduce_amount_total'] += $item['reduce_amount'];
            if(isset($item['stt']))
            {
                $this->group_function_params['total_credit_usd_total'] += $item['credit_card_usd'];
                $this->group_function_params['total_credit_vnd_total'] += $item['credit_card_vnd'];
                $this->group_function_params['total_credit_total'] += $item['credit_card'];
                $this->group_function_params['total_foc_total'] += $item['foc'];
                $this->group_function_params['total_cash_total'] += $item['cash'];
                $this->group_function_params['total_cash_vnd_total'] += $item['cash_vnd'];
                $this->group_function_params['total_cash_usd_total'] += $item['cash_usd'];
                $this->group_function_params['total_refund_total'] += $item['refund'];
                $this->group_function_params['total_refund_vnd_total'] += $item['refund_vnd'];
                $this->group_function_params['total_refund_usd_total'] += $item['refund_usd'];
                $this->group_function_params['total_group_deposit_total'] += $item['deposit_group'];
                $this->group_function_params['total_bank_total'] += $item['bank'];
                if($item['debit'] >999)
                {
                    $this->group_function_params['total_debit_total'] += $item['debit'];
                }
                     
            }
                            
		}
         
        if($page_no>=$this->map['start_page'])
		{
             $this->parse_layout('report',
            	array(
            		'items'=>$items,
                    'payment_types'=>$report->payment_types,
            		'page_no'=>$page_no,
                    'total_page'=>$total_page,
            		'day'=>$this->day,
                    'count_room'=>$count_room,
                    'group_function_params'=>$this->group_function_params,
                    'last_group_function_params'=>$last_group_function_params
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

    function get_payments($bill_id){
		//$hi = DB::fetch_all('select * from payment where payment.time>='.$time_from.' AND payment.time<='.$time_to.'');
		//System::debug($hi);
		return $payments = DB::fetch_all('
						SELECT 
							(payment.payment_type_id || \'_\' || payment.credit_card_id || \'_\' || payment.currency_id || \'_\' || payment.folio_id || \'_\' || payment.type_dps) as id
							,SUM(amount) as total
							,SUM(amount*payment.exchange_rate) as total_vnd
							,CONCAT(payment.payment_type_id,CONCAT(\'_\',payment.currency_id)) as name
							,payment.folio_id
							,payment.payment_type_id
							,payment.credit_card_id
							,payment.currency_id
                            ,payment.exchange_rate
							,payment.type_dps
                            ,payment.reservation_room_id
						FROM payment
							inner join folio on payment.folio_id = folio.id
						WHERE 
							folio.id in ('.$bill_id.') AND payment.type=\'RESERVATION\'
						GROUP BY payment.payment_type_id,payment.currency_id,payment.folio_id
						,payment.currency_id,payment.type_dps,payment.credit_card_id,payment.reservation_room_id,payment.exchange_rate
				');	
                
	}
    function get_traveller_folios($bill_id)
    {
		return $traveller_folios = DB::fetch_all('
						SELECT 
				            CONCAT(traveller_folio.type,CONCAT(\'_\',CONCAT(traveller_folio.reservation_room_id,CONCAT(\'_\',CONCAT(traveller_folio.folio_id,CONCAT(\'_\',traveller_folio.invoice_id)))))) as id
                            ,sum(traveller_folio.total_amount) as amount
                            ,traveller_folio.folio_id
                            ,traveller_folio.add_payment
                            ,traveller_folio.reservation_room_id
                            ,traveller_folio.type
                            ,extra_service_invoice_detail.service_id
                            ,extra_service_invoice.payment_type
                            ,extra_service.code
			             FROM 
				            traveller_folio 
                            left join extra_service_invoice_detail on extra_service_invoice_detail.id = traveller_folio.invoice_id
			                left join extra_service_invoice on extra_service_invoice_detail.invoice_id = extra_service_invoice.id 
                            left join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                        WHERE
				            traveller_folio.folio_id in ('.$bill_id.')
                         
                         GROUP BY 
                            traveller_folio.type
                            ,traveller_folio.reservation_room_id
                            ,traveller_folio.folio_id
                            ,traveller_folio.add_payment
                            ,traveller_folio.invoice_id
                            ,extra_service_invoice_detail.service_id
                            ,extra_service_invoice.payment_type
                            ,extra_service.code
                         ORDER BY
                            traveller_folio.folio_id DESC');	
	}
}
?>
