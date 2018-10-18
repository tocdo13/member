<?php
class ReceiptMoneyForm extends Form
{
    function ReceiptMoneyForm()
    {
        Form::Form('ReportArrivalList');
        $this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');  
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');  
        $this->link_js('packages/core/includes/js/jquery/jquery.mask.min.js');  
    }
    
    function cal_time($time)
    {
        $arr_time = explode(":",$time);
        return $arr_time[0]*3600 + $arr_time[1]*60;
    }
    
    function draw()
    {    
        if(!isset($_REQUEST['from_date']))
            $_REQUEST['from_date'] = date('d/m/Y');
        if(!isset($_REQUEST['to_date']))
            $_REQUEST['to_date'] = date('d/m/Y');
        if(!isset($_REQUEST['from_time']))
            $_REQUEST['from_time'] = "00:00";
        if(!isset($_REQUEST['to_time']))
            $_REQUEST['to_time'] = "23:59";  
        $from_time = Date_Time::to_time($_REQUEST['from_date']) + $this->cal_time($_REQUEST['from_time']);
        $to_time = Date_Time::to_time($_REQUEST['to_date']) + $this->cal_time($_REQUEST['to_time']);
        $stt;
        $this->map = array();
        $this->map['from_date'] = $_REQUEST['from_date'];
        $this->map['to_date'] = $_REQUEST['to_date'];
        $this->map['from_time'] = $_REQUEST['from_time'];
        $this->map['to_time'] = $_REQUEST['to_time'];
        
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
         $this->map['users'] = $users;   
        /** 7211 end*/
        $this->map['receipter_list'] = array(''=>Portal::language('all_user'))+String::get_list($users);
        $this->map['currency'] = DB::fetch_all("select * from currency where allow_payment = 1");
        $this->map['payment_type'] = DB::fetch_all("select * from payment_type where apply = 'ALL' and structure_id != 1000000000000000000");
                                                
        $this->map['payment_total'] = array('type_payment'=>array(),'total'=>0);
        foreach($this->map['payment_type'] as $key => $value)
        {
            if($value['def_code']=='CASH')
            {
                foreach($this->map['currency'] as $k => $v)
                {
                    $this->map['payment_total']['type_payment'][$value['def_code']."_".$k] = 0;
                }
            }
            else
            {
                $this->map['payment_total']['type_payment'][$value['def_code']] = 0;
            }
        }
        
        $this->map['receipt_payment'] = array();
        $this->map['receipt_payment_count'] = array();
        $this->map['receipt_payment_total'] = $this->map['payment_total'];
        
        $this->map['bar_payment'] = array();
        $this->map['bar_payment_count'] = array();
        $this->map['bar_payment_total'] = $this->map['payment_total'];
        
        $this->map['karaoke_payment'] = array();
        $this->map['karaoke_payment_count'] = array();
        $this->map['karaoke_payment_total'] = $this->map['payment_total'];
        
        $this->map['vend_payment'] = array();
        $this->map['vend_payment_count'] = array();
        $this->map['vend_payment_total'] = $this->map['payment_total'];
        
        $this->map['spa_payment'] = array();
        $this->map['spa_payment_count'] = array();
        $this->map['spa_payment_total'] = $this->map['payment_total'];
        
        $this->map['mice_payment'] = array();
        $this->map['mice_payment_count'] = array();
        $this->map['mice_payment_total'] = $this->map['payment_total'];
        
        $this->map['receipt_deposit'] = array();
        $this->map['receipt_deposit_count'] = array();
        $this->map['receipt_deposit_total'] = $this->map['payment_total'];
        
        $this->map['bar_deposit'] = array();
        $this->map['bar_deposit_count'] = array();
        $this->map['bar_deposit_total'] = $this->map['payment_total'];
        
        $this->map['karaoke_deposit'] = array();
        $this->map['karaoke_deposit_count'] = array();
        $this->map['karaoke_deposit_total'] = $this->map['payment_total'];
        
        $this->map['spa_deposit'] = array();
        $this->map['spa_deposit_count'] = array();
        $this->map['spa_deposit_total'] = $this->map['payment_total'];
        
        $this->map['mice_deposit'] = array();
        $this->map['mice_deposit_count'] = array();
        $this->map['mice_deposit_total'] = $this->map['payment_total'];
        
        $this->map['payment_total_bill'] = $this->map['payment_total'];
        $this->map['payment_total_deposit'] = $this->map['payment_total'];
        /** START - thanh toan le tan **/
        $this->map['receipt_payment'] = DB::fetch_all("select payment.id,
              payment.time,
              payment.amount,
              payment.amount * payment.exchange_rate as amount_vnd,
              payment.payment_type_id,
              payment.currency_id,
              payment.user_id,
              folio.id as folio_id,
              folio.customer_id,
              folio.code as folio_code,  -- oanh add
              folio.reservation_traveller_id,
              --case when folio.customer_id is null then traveller.first_name || ' ' || traveller.last_name else null end traveller_name,
              --case 
                --when folio.reservation_room_id is not null then
                --(traveller.first_name || ' ' || traveller.last_name) 
              --else ' '
              --end as traveller_name,
              traveller.first_name || ' ' || traveller.last_name as traveller_name,  
              --(traveller.first_name || ' ' || traveller.last_name) as traveller_name,
              customer.name as customer_name,
              room.name as room_name,
              reservation.id as recode,
              reservation_room.id as reservation_room_id,
              '' as total_deposit,
              '' as total_bill
            from payment
              inner join folio on folio.id = payment.folio_id
              inner join reservation on reservation.id = folio.reservation_id
              left join customer on customer.id = reservation.customer_id
              left join reservation_room on reservation_room.id = folio.reservation_room_id
              left join room on room.id = reservation_room.room_id
              left join reservation_traveller on reservation_traveller.id = folio.reservation_traveller_id
              left join traveller on traveller.id = reservation_traveller.traveller_id
            where payment.time >=".$from_time." 
                and payment.time <".$to_time.(Url::get("receipter")?" and payment.user_id = '".Url::get("receipter")."'":"")." 
            order by folio.id,payment.time");
        $stt = 1;
        foreach($this->map['receipt_payment'] as $key => $value)
        {
            if($value['folio_id'])
            {
                $get_traveller_folio= DB::fetch_all("
                                            SELECT 
                                                traveller_folio.id, traveller_folio.folio_id, traveller_folio.type, traveller_folio.total_amount
                                            FROM 
                                                traveller_folio
                                            WHERE
                                                traveller_folio.folio_id=".$value['folio_id']."
                ");
                foreach($get_traveller_folio as $k => $v)
                {
                    if($v['folio_id'] && ($v['type'] == 'DEPOSIT' || $v['type'] == 'DEPOSIT_GROUP'))
                    {
                        $this->map['receipt_payment'][$key]['total_deposit'] += $v['total_amount'];
                    }else
                    {
                        $this->map['receipt_payment'][$key]['total_bill'] += $v['total_amount'];                        
                    }                                        
                }            
            }
        }
        //lay ra những hóa đơn thanh toán có tổng đặt cọc = tổng bill
        if(Url::get("receipter"))
        {
            $receipter = Url::get("receipter");
        }else
        {
            $receipter = '';              
        }
        $receipt_not_payment = $this->receipt_not_payment($receipter,$from_time,$to_time);
        //lay ra những hóa đơn thanh toán có tổng đặt cọc = tổng bill
        $this->map['receipt_payment'] += $receipt_not_payment;
        foreach($this->map['receipt_payment'] as $key => $value)
        {
            $room_name = $this->GetRoomName($value['folio_id']);
            $this->map['receipt_payment'][$key]['room_name'] = $room_name;
            $amount = $value['amount_vnd'];
            if($value['payment_type_id'] == 'REFUND')
            {
                $amount -= $value['amount_vnd'];
            }
            if($value['payment_type_id'] == 'NOT_PAYMENT')
            {
                $amount = 0;
            }
            /*if($value['reservation_room_id']=='')
            {
                $traveller_folio = DB::fetch_all("SELECT 
                                                    room.id,room.name as room_name 
                                                    from 
                                                    traveller_folio
                                                    inner join reservation_room on reservation_room.id=traveller_folio.reservation_room_id
                                                    inner join room on room.id=reservation_room.room_id
                                                    WHERE
                                                        traveller_folio.folio_id=".$value['folio_id']."
                                                    ");
                foreach($traveller_folio as $trl=>$fol)
                {
                    $this->map['receipt_payment'][$key]['arr_room'][$fol['room_name']]['name'] = $fol['room_name'];
                }
            }
            else
            {
                $this->map['receipt_payment'][$key]['arr_room'][$value['room_name']]['name'] = $value['room_name'];
            }*/
            if(!isset($this->map['receipt_payment_count'][$value['folio_id']]))
            {
                $this->map['receipt_payment_count'][$value['folio_id']] = array('stt'=>$stt++,'num_payment'=>1,"total_payment"=>$amount);
            }
            else{
                if($value['payment_type_id'] != 'NOT_PAYMENT')
                {
                    $this->map['receipt_payment_count'][$value['folio_id']]['num_payment']++;
                }
                
                $this->map['receipt_payment_count'][$value['folio_id']]["total_payment"]+=$amount;
            }
            if($value['payment_type_id'] == 'CASH')
            {
                $this->map['receipt_payment_total']['type_payment'][$value['payment_type_id']."_".$value['currency_id']] += $value['amount'];
                $this->map['payment_total_bill']['type_payment'][$value['payment_type_id']."_".$value['currency_id']] += $value['amount'];
            }
            elseif($value['payment_type_id'] != 'CASH' && $value['payment_type_id'] != 'NOT_PAYMENT')
            {
                $this->map['receipt_payment_total']['type_payment'][$value['payment_type_id']] +=$value['amount_vnd'];
                $this->map['payment_total_bill']['type_payment'][$value['payment_type_id']] += $value['amount_vnd'];   
            }
            $this->map['receipt_payment_total']['total'] +=$amount;
        }
        /** END - thanh toan le tan **/
        
        /** START - payment MICE **/
        $this->map['mice_payment'] = DB::fetch_all("select payment.id,
              payment.time,
              payment.amount,
              payment.amount * payment.exchange_rate as amount_vnd,
              payment.payment_type_id,
              payment.currency_id,
              payment.user_id,
              mice_invoice.id as invoice_id,
              mice_invoice.reservation_traveller_id,
              mice_invoice.mice_reservation_id,
              mice_invoice.bill_id,
              traveller.first_name || ' ' || traveller.last_name as traveller_name,
              customer.name as customer_name,
              '' as total_deposit,
              '' as total_bill
            from payment
              inner join mice_invoice on mice_invoice.id = payment.bill_id and payment.type='BILL_MICE'
              inner join mice_reservation on mice_invoice.mice_reservation_id = mice_reservation.id
              inner join customer on mice_reservation.customer_id = customer.id
              left join reservation_traveller on reservation_traveller.id = mice_invoice.reservation_traveller_id
              left join traveller on traveller.id = reservation_traveller.traveller_id
            where payment.time >=".$from_time." 
                and payment.time <".$to_time.(Url::get("receipter")?" and payment.user_id = '".Url::get("receipter")."'":"")." 
            order by mice_invoice.id, payment.time");
        
        $stt = 1;
        foreach($this->map['mice_payment'] as $key => $value)
        {
            if($value['invoice_id'])
            {
                $mice_invoice_detail = DB::fetch_all('
                                                SELECT
                                                    mice_invoice_detail.id,
                                                    mice_invoice_detail.mice_invoice_id as invoice_id,
                                                    mice_invoice_detail.type,
                                                    mice_invoice_detail.total_amount
                                                FROM
                                                    mice_invoice_detail
                                                WHERE
                                                    mice_invoice_detail.mice_invoice_id = \''.$value['invoice_id'].'\'
                                                ORDER BY
                                                    mice_invoice_detail.id ASC
                ');
                foreach($mice_invoice_detail as $k => $v)
                {
                    if($v['type'] == 'DEPOSIT_MICE')
                    {
                        $this->map['mice_payment'][$key]['total_deposit'] += $v['total_amount'];
                    }else
                    {
                        $this->map['mice_payment'][$key]['total_bill'] += $v['total_amount'];
                    }                    
                }                
            }
        }
        if(Url::get("receipter"))
        {
            $receipter = Url::get("receipter");
        }else
        {
            $receipter = '';              
        }
        $mice_not_payment = $this->mice_not_payment($receipter,$from_time, $to_time);
        //System::debug($mice_not_payment);
        $this->map['mice_payment'] += $mice_not_payment;
        foreach($this->map['mice_payment'] as $key => $value)
        {
            //$amount = ($value['payment_type_id'] == 'REFUND' OR $value['payment_type_id'] == 'DEBIT_GUEST')?-1*$value['amount_vnd']:$value['amount_vnd'];
            $amount = $value['amount_vnd'];
            if($value['payment_type_id'] == 'REFUND')
            {
                $amount -= $value['amount_vnd'];
            }
            if($value['payment_type_id'] == 'NOT_PAYMENT')
            {
                $amount = 0;
            }
            if(!isset($this->map['mice_payment_count'][$value['invoice_id']]))
            {
                $this->map['mice_payment_count'][$value['invoice_id']] = array('stt'=>$stt++,'num_payment'=>1,"total_payment"=>$amount);
            }
            else{
                $this->map['mice_payment_count'][$value['invoice_id']]['num_payment']++;
                
                $this->map['mice_payment_count'][$value['invoice_id']]["total_payment"]+=$amount;
            }
            if($value['payment_type_id'] == 'CASH')
            {
                $this->map['mice_payment_total']['type_payment'][$value['payment_type_id']."_".$value['currency_id']] += $value['amount'];
                $this->map['payment_total_bill']['type_payment'][$value['payment_type_id']."_".$value['currency_id']] += $value['amount'];
            }
            elseif($value['payment_type_id'] != 'CASH' && $value['payment_type_id'] != 'NOT_PAYMENT')
            {
                $this->map['mice_payment_total']['type_payment'][$value['payment_type_id']] +=$value['amount_vnd'];
                $this->map['payment_total_bill']['type_payment'][$value['payment_type_id']] +=$value['amount_vnd'];   
            }
            $this->map['mice_payment_total']['total'] +=$amount;
        }
        /** END - MICE **/
        
        
        /** START - thanh toan nha hang **/
        $bar_payment_tmp = DB::fetch_all("select payment.id || '_' || bar_table.name as id,
              payment.id || '_' || bar_reservation.id  as payment_brs_id,
              payment.time,
              payment.amount,
              payment.amount * payment.exchange_rate as amount_vnd,
              payment.payment_type_id,
              payment.currency_id,
              payment.user_id,
              bar_reservation.id as bill_id,
              bar_table.name as table_name,
              case 
                when bar_reservation.pay_with_room = 0 
                then bar_reservation.receiver_name
                else traveller.first_name || ' ' || traveller.last_name
              end traveller_name,
              customer.name as customer_name,
              bar_reservation.total as total_bill,
              bar_reservation.deposit as total_deposit
            from payment
              inner join bar_reservation on bar_reservation.id = payment.bill_id and type = 'BAR'
              left join bar_reservation_table on bar_reservation.id = bar_reservation_table.bar_reservation_id
              left join bar_table on bar_table.id = bar_reservation_table.table_id
              left join reservation_traveller on reservation_traveller.id = bar_reservation.reservation_traveller_id
              left join traveller on traveller.id = reservation_traveller.traveller_id
              left join customer on bar_reservation.customer_id = customer.id
            where payment.time >=".$from_time." 
                and payment.time <".$to_time.(Url::get("receipter")?" and payment.user_id = '".Url::get("receipter")."'":"")."
                and payment.type_dps is null 
            order by bar_reservation.id, payment.time");
        
        if(Url::get("receipter"))
        {
            $receipter = Url::get("receipter");
        }else
        {
            $receipter = '';              
        }
        $bar_not_payment = $this->bar_not_payment($receipter,$from_time, $to_time);
        $bar_payment_tmp += $bar_not_payment; 
        //System::debug($bar_payment_tmp);
        foreach($bar_payment_tmp as $key => $value)
        {
            if(!isset($this->map['bar_payment'][$value['payment_brs_id']]))
            {
                $this->map['bar_payment'][$value['payment_brs_id']] = $value;
            }
            else
            {
                $this->map['bar_payment'][$value['payment_brs_id']]['table_name'] .= ",".$value['table_name'];
            }
        }
        $stt = 1;
        foreach($this->map['bar_payment'] as $key => $value)
        {
            //$amount = ($value['payment_type_id'] == 'REFUND')?-1*$value['amount_vnd']:$value['amount_vnd'];
            $amount = $value['amount_vnd'];
            if($value['payment_type_id'] == 'REFUND')
            {
                $amount -= $value['amount_vnd'];
            }
            if($value['payment_type_id'] == 'NOT_PAYMENT')
            {
                $amount = 0;
            }
            if(!isset($this->map['bar_payment_count'][$value['bill_id']]))
            {
                $this->map['bar_payment_count'][$value['bill_id']] = array('stt'=>$stt++,'num_payment'=>1,"total_payment"=>$amount);
            }
            else{
                $this->map['bar_payment_count'][$value['bill_id']]['num_payment']++;
                $this->map['bar_payment_count'][$value['bill_id']]["total_payment"]+=$amount;
            }
            if($value['payment_type_id'] == 'CASH')
            {
                $this->map['bar_payment_total']['type_payment'][$value['payment_type_id']."_".$value['currency_id']] += $value['amount'];
                $this->map['payment_total_bill']['type_payment'][$value['payment_type_id']."_".$value['currency_id']] += $value['amount'];
            }
            elseif($value['payment_type_id'] != 'CASH' && $value['payment_type_id'] != 'NOT_PAYMENT')
            {
                $this->map['bar_payment_total']['type_payment'][$value['payment_type_id']] += $value['amount_vnd'];
                $this->map['payment_total_bill']['type_payment'][$value['payment_type_id']] += $value['amount_vnd'];   
            }
            $this->map['bar_payment_total']['total'] += $amount;
        }
        /** END - thanh toan nha hang **/
        
        /** START - thanh toan karaoke **/
        /*$karaoke_payment_tmp = DB::fetch_all("select payment.id || '_' || karaoke_table.name as id,
              payment.id || '_' || karaoke_reservation.id  as payment_brs_id,
              payment.time,
              payment.amount,
              payment.amount * payment.exchange_rate as amount_vnd,
              payment.payment_type_id,
              payment.currency_id,
              payment.user_id,
              karaoke_reservation.id as bill_id,
              karaoke_table.name as table_name,
              case 
                when karaoke_reservation.pay_with_room = 0 
                then karaoke_reservation.receiver_name
                else traveller.first_name || ' ' || traveller.last_name
              end traveller_name
            from payment
              inner join karaoke_reservation on karaoke_reservation.id = payment.bill_id and type = 'KARAOKE'
              left join karaoke_reservation_table on karaoke_reservation.id = karaoke_reservation_table.karaoke_reservation_id
              left join karaoke_table on karaoke_table.id = karaoke_reservation_table.table_id
              left join reservation_traveller on reservation_traveller.id = karaoke_reservation.reservation_traveller_id
              left join traveller on traveller.id = reservation_traveller.traveller_id
            where payment.time >=".$from_time." 
                and payment.time <".$to_time.(Url::get("receipter")?" and payment.user_id = '".Url::get("receipter")."'":"")." 
            order by karaoke_reservation.id, payment.time");
            
        foreach($karaoke_payment_tmp as $key => $value)
        {
            if(!isset($this->map['karaoke_payment'][$value['payment_brs_id']]))
            {
                $this->map['karaoke_payment'][$value['payment_brs_id']] = $value;
            }
            else
            {
                $this->map['karaoke_payment'][$value['payment_brs_id']]['table_name'] .= ",".$value['table_name'];
            }
        }
        $stt = 1;
        foreach($this->map['karaoke_payment'] as $key => $value)
        {
            $amount = ($value['payment_type_id'] == 'REFUND')?-1*$value['amount_vnd']:$value['amount_vnd'];
            if(!isset($this->map['karaoke_payment_count'][$value['bill_id']]))
            {
                $this->map['karaoke_payment_count'][$value['bill_id']] = array('stt'=>$stt++,'num_payment'=>1,"total_payment"=>$amount);
            }
            else{
                $this->map['karaoke_payment_count'][$value['bill_id']]['num_payment']++;
                $this->map['karaoke_payment_count'][$value['bill_id']]["total_payment"]+=$amount;
            }
            if($value['payment_type_id'] == 'CASH')
            {
                $this->map['karaoke_payment_total']['type_payment'][$value['payment_type_id']."_".$value['currency_id']] += $value['amount'];
            }
            else
            {
                $this->map['karaoke_payment_total']['type_payment'][$value['payment_type_id']] += $value['amount_vnd'];   
            }
            $this->map['karaoke_payment_total']['total'] += $amount;
        }
        
        /** END - thanh toan karaoke **/
        
        /** START - thanh toan ban hang **/
        /*$this->map['vend_payment'] = DB::fetch_all("select payment.id,
              payment.time,
              payment.amount,
              payment.amount * payment.exchange_rate as amount_vnd,
              payment.payment_type_id,
              payment.currency_id,
              payment.user_id,
              ve_reservation.id as bill_id,
              department.name_".Portal::language()." as department_name,
              case 
                when ve_reservation.pay_with_room = 0  
                then ve_reservation.receiver_name
                else traveller.first_name || ' ' || traveller.last_name
              end traveller_name
            from payment
              inner join ve_reservation on ve_reservation.id = payment.bill_id and type = 'VEND'
              inner join department on ve_reservation.department_code = department.code
              left join reservation_traveller on reservation_traveller.id = ve_reservation.reservation_traveller_id
              left join traveller on traveller.id = reservation_traveller.traveller_id
            where payment.time >=".$from_time." 
                and payment.time <".$to_time.(Url::get("receipter")?" and payment.user_id = '".Url::get("receipter")."'":"")." 
            order by ve_reservation.id, payment.time");
        
        $stt = 1;
        foreach($this->map['vend_payment'] as $key => $value)
        {
            $amount = ($value['payment_type_id'] == 'REFUND')?-1*$value['amount_vnd']:$value['amount_vnd'];
            if(!isset($this->map['vend_payment_count'][$value['bill_id']]))
            {
                $this->map['vend_payment_count'][$value['bill_id']] = array('stt'=>$stt++,'num_payment'=>1,"total_payment"=>$amount);
            }
            else{
                $this->map['vend_payment_count'][$value['bill_id']]['num_payment']++;
                $this->map['vend_payment_count'][$value['bill_id']]["total_payment"]+=$amount;
            }
            if($value['payment_type_id'] == 'CASH')
            {
                $this->map['vend_payment_total']['type_payment'][$value['payment_type_id']."_".$value['currency_id']] += $value['amount'];
            }
            else
            {
                $this->map['vend_payment_total']['type_payment'][$value['payment_type_id']] += $value['amount_vnd'];   
            }
            $this->map['vend_payment_total']['total'] += $amount;
        }
        
        /** END - thanh toan ban hang **/
        
        /** START - thanh toan spa **/
        $spa_payment_tmp = DB::fetch_all("select payment.id || '_' || massage_room.name as id,
              payment.id || '_' || massage_reservation_room.id  as payment_brs_id,
              payment.time,
              payment.amount,
              payment.amount * payment.exchange_rate as amount_vnd,
              payment.payment_type_id,
              payment.currency_id,
              payment.user_id,
              massage_reservation_room.id as bill_id,
              massage_room.name as room_name,
              case 
                when massage_reservation_room.hotel_reservation_room_id = 0  
                then massage_guest.full_name
                else null
              end traveller_name
            from payment
              inner join massage_reservation_room on massage_reservation_room.id = payment.bill_id and type = 'SPA'
              left join massage_guest ON massage_reservation_room.guest_id = massage_guest.id
              left join massage_product_consumed ON massage_reservation_room.id = massage_product_consumed.reservation_room_id
              left join massage_room on massage_room.id = massage_product_consumed.room_id
              left join reservation_room on reservation_room.id = massage_reservation_room.hotel_reservation_room_id
              --left join reservation_traveller on reservation_traveller.id = massage_reservation_room.reservation_traveller_id
              --left join traveller on traveller.id = reservation_traveller.traveller_id
            where payment.time >=".$from_time." 
                and payment.time <".$to_time.(Url::get("receipter")?" and payment.user_id = '".Url::get("receipter")."'":"")." 
            order by massage_reservation_room.id, payment.time");
            
        foreach($spa_payment_tmp as $key => $value)
        {
            if(!isset($this->map['spa_payment'][$value['payment_brs_id']]))
            {
                $this->map['spa_payment'][$value['payment_brs_id']] = $value;
            }
            else
            {
                $this->map['spa_payment'][$value['payment_brs_id']]['room_name'] .= ",".$value['room_name'];
            }
        }
        $stt = 1;
        foreach($this->map['spa_payment'] as $key => $value)
        {
            $amount = ($value['payment_type_id'] == 'REFUND')?-1*$value['amount_vnd']:$value['amount_vnd'];
            if(!isset($this->map['spa_payment_count'][$value['bill_id']]))
            {
                $this->map['spa_payment_count'][$value['bill_id']] = array('stt'=>$stt++,'num_payment'=>1,"total_payment"=>$amount);
            }
            else{
                $this->map['spa_payment_count'][$value['bill_id']]['num_payment']++;
                $this->map['spa_payment_count'][$value['bill_id']]["total_payment"]+=$amount;
            }
            if($value['payment_type_id'] == 'CASH')
            {
                $this->map['spa_payment_total']['type_payment'][$value['payment_type_id']."_".$value['currency_id']] += $value['amount'];
            }
            else
            {
                $this->map['spa_payment_total']['type_payment'][$value['payment_type_id']] += $value['amount_vnd'];   
            }
            $this->map['spa_payment_total']['total'] += $amount;
        }
        
        /** END - thanh toan spa **/
        
        /** START - dat coc le tan **/
        $sql ="select payment.id,
              payment.time,
              payment.amount,
              payment.amount * payment.exchange_rate as amount_vnd,
              payment.payment_type_id,
              payment.currency_id,
              payment.user_id,
              case 
                when payment.reservation_room_id is null
                then payment.reservation_id
                else reservation_room.reservation_id
              end recode,
              payment.reservation_id || '_' || payment.reservation_room_id as rid_rrid,
              payment.reservation_room_id,
              null as traveller_name,
              --case 
                --when payment.reservation_room_id is null
                --then cus_r.name
               -- else cus_rr.name
             -- end customer_name,
             cus_r.name as customer_name,
              room.name as room_name
            from payment
              inner join reservation res_r on res_r.id = payment.reservation_id
              left join customer cus_r on cus_r.id = res_r.customer_id
              left join reservation_room on reservation_room.reservation_id=res_r.id 
              --giap.ln comment 
              --reservation_room.id = payment.reservation_room_id 
              --left join reservation res_rr on res_rr.id = reservation_room.reservation_id
              --left join customer cus_rr on cus_r.id = res_rr.customer_id
              --end giap.ln 
              left join room on room.id = reservation_room.room_id
              --left join reservation_traveller on reservation_traveller.id = folio.reservation_traveller_id
              --left join traveller on traveller.id = reservation_traveller.traveller_id
            where payment.time >=".$from_time." 
                and payment.time <".$to_time.(Url::get("receipter")?" and payment.user_id = '".Url::get("receipter")."'":"")."
                AND ((payment.reservation_room_id is not null AND payment.reservation_room_id=reservation_room.id) OR 
                (payment.reservation_room_id is null)) 
                and type_dps in ('ROOM','GROUP')
            order by recode,reservation_room.id, payment.time"; 
        $this->map['receipt_deposit'] = DB::fetch_all($sql);
        
        $stt = 1;
        
        foreach($this->map['receipt_deposit'] as $key => $value)
        {
            $amount = ($value['payment_type_id'] == 'REFUND')?-1*$value['amount_vnd']:$value['amount_vnd'];
            if(!isset($this->map['receipt_deposit_count'][$value['rid_rrid']]))
            {
                $this->map['receipt_deposit_count'][$value['rid_rrid']] = array('stt'=>$stt++,'num_payment'=>1,"total_payment"=>$amount);
            }
            else{
                $this->map['receipt_deposit_count'][$value['rid_rrid']]['num_payment']++;
                
                $this->map['receipt_deposit_count'][$value['rid_rrid']]["total_payment"]+=$amount;
            }
            if($value['payment_type_id'] == 'CASH')
            {
                if(isset($this->map['receipt_deposit_total']['type_payment'][$value['payment_type_id']."_".$value['currency_id']]))
                    $this->map['receipt_deposit_total']['type_payment'][$value['payment_type_id']."_".$value['currency_id']] += $value['amount'];
                    $this->map['payment_total_deposit']['type_payment'][$value['payment_type_id']."_".$value['currency_id']] += $value['amount'];
               
            }
            else
            {
                $this->map['receipt_deposit_total']['type_payment'][$value['payment_type_id']] +=$value['amount_vnd'];
                $this->map['payment_total_deposit']['type_payment'][$value['payment_type_id']] +=$value['amount_vnd'];   
            }
            $this->map['receipt_deposit_total']['total'] +=$amount;
        }
        /** END - dat coc le tan **/
        
        /** START - deposit MICE **/
        $this->map['mice_deposit'] = DB::fetch_all("select payment.id,
              payment.time,
              payment.amount,
              payment.amount * payment.exchange_rate as amount_vnd,
              payment.payment_type_id,
              payment.currency_id,
              payment.user_id,
              mice_reservation.id as bill_id,
              customer.name as customer_name
            from payment
              inner join mice_reservation on mice_reservation.id = payment.bill_id and type = 'MICE'
              left join customer on customer.id=mice_reservation.customer_id
            where payment.time >=".$from_time." 
                and payment.time <".$to_time.(Url::get("receipter")?" and payment.user_id = '".Url::get("receipter")."'":"")." 
                and type_dps = 'MICE'
            order by mice_reservation.id, payment.time");
        $stt = 1;
        foreach($this->map['mice_deposit'] as $key => $value)
        {
            $amount = ($value['payment_type_id'] == 'REFUND' OR $value['payment_type_id'] == 'DEBIT_GUEST')?-1*$value['amount_vnd']:$value['amount_vnd'];
            if(!isset($this->map['mice_deposit_count'][$value['bill_id']]))
            {
                $this->map['mice_deposit_count'][$value['bill_id']] = array('stt'=>$stt++,'num_payment'=>1,"total_payment"=>$amount);
            }
            else{
                $this->map['mice_deposit_count'][$value['bill_id']]['num_payment']++;
                
                $this->map['mice_deposit_count'][$value['bill_id']]["total_payment"]+=$amount;
            }
            if($value['payment_type_id'] == 'CASH')
            {
                $this->map['mice_deposit_total']['type_payment'][$value['payment_type_id']."_".$value['currency_id']] += $value['amount'];
                $this->map['payment_total_deposit']['type_payment'][$value['payment_type_id']."_".$value['currency_id']] += $value['amount'];
            }
            else
            {
                $this->map['mice_deposit_total']['type_payment'][$value['payment_type_id']] +=$value['amount_vnd'];
                $this->map['payment_total_deposit']['type_payment'][$value['payment_type_id']] +=$value['amount_vnd'];   
            }
            $this->map['mice_deposit_total']['total'] +=$amount;
        }
        /** END - MICE **/
        
        
        /** START - dat coc nha hang **/
        $this->map['bar_deposit'] = DB::fetch_all("select payment.id,
              payment.time,
              payment.amount,
              payment.amount * payment.exchange_rate as amount_vnd,
              payment.payment_type_id,
              payment.currency_id,
              payment.user_id,
              bar_reservation.id as bill_id,
              case 
                when bar_reservation.pay_with_room = 0
                then bar_reservation.receiver_name
                else traveller.first_name || ' ' || traveller.last_name
              end traveller_name,
              bar_table.name as table_name
            from payment
              inner join bar_reservation on bar_reservation.id = payment.bill_id and type = 'BAR'
              left join bar_reservation_table on bar_reservation.id = bar_reservation_table.bar_reservation_id
              left join bar_table on bar_table.id = bar_reservation_table.table_id
              left join reservation_traveller on reservation_traveller.id = bar_reservation.reservation_traveller_id
              left join traveller on traveller.id = reservation_traveller.traveller_id
            where payment.time >=".$from_time." 
                and payment.time <".$to_time.(Url::get("receipter")?" and payment.user_id = '".Url::get("receipter")."'":"")." 
                and type_dps = 'BAR'
            order by bar_reservation.id, payment.time");
        $stt = 1;
        foreach($this->map['bar_deposit'] as $key => $value)
        {
            $amount = ($value['payment_type_id'] == 'REFUND')?-1*$value['amount_vnd']:$value['amount_vnd'];
            if(!isset($this->map['bar_deposit_count'][$value['bill_id']]))
            {
                $this->map['bar_deposit_count'][$value['bill_id']] = array('stt'=>$stt++,'num_payment'=>1,"total_payment"=>$amount);
            }
            else{
                $this->map['bar_deposit_count'][$value['bill_id']]['num_payment']++;
                
                $this->map['bar_deposit_count'][$value['bill_id']]["total_payment"]+=$amount;
            }
            if($value['payment_type_id'] == 'CASH')
            {
                $this->map['bar_deposit_total']['type_payment'][$value['payment_type_id']."_".$value['currency_id']] += $value['amount'];
                $this->map['payment_total_deposit']['type_payment'][$value['payment_type_id']."_".$value['currency_id']] += $value['amount'];
            }
            else
            {
                $this->map['bar_deposit_total']['type_payment'][$value['payment_type_id']] +=$value['amount_vnd'];
                $this->map['payment_total_deposit']['type_payment'][$value['payment_type_id']] +=$value['amount_vnd'];   
            }
            $this->map['bar_deposit_total']['total'] +=$amount;
        }
        /** END - dat coc nha hang **/
        
        /** START - dat coc karaoke **/
        /*$this->map['karaoke_deposit'] = DB::fetch_all("select payment.id,
              payment.time,
              payment.amount,
              payment.amount * payment.exchange_rate as amount_vnd,
              payment.payment_type_id,
              payment.currency_id,
              payment.user_id,
              karaoke_reservation.id as bill_id,
              case 
                when karaoke_reservation.pay_with_room = 0  
                then karaoke_reservation.receiver_name
                else traveller.first_name || ' ' || traveller.last_name
              end traveller_name,
              karaoke_table.name as table_name
            from payment
              inner join karaoke_reservation on karaoke_reservation.id = payment.bill_id and type = 'KARAOKE'
              left join karaoke_reservation_table on karaoke_reservation.id = karaoke_reservation_table.karaoke_reservation_id
              left join karaoke_table on karaoke_table.id = karaoke_reservation_table.table_id
              left join reservation_traveller on reservation_traveller.id = karaoke_reservation.reservation_traveller_id
              left join traveller on traveller.id = reservation_traveller.traveller_id
            where payment.time >=".$from_time." 
                and payment.time <".$to_time.(Url::get("receipter")?" and payment.user_id = '".Url::get("receipter")."'":"")." 
                and type_dps = 'karaoke'
            order by karaoke_reservation.id, payment.time");
        $stt = 1;
        foreach($this->map['karaoke_deposit'] as $key => $value)
        {
            $amount = ($value['payment_type_id'] == 'REFUND')?-1*$value['amount_vnd']:$value['amount_vnd'];
            if(!isset($this->map['karaoke_deposit_count'][$value['bill_id']]))
            {
                $this->map['karaoke_deposit_count'][$value['bill_id']] = array('stt'=>$stt++,'num_payment'=>1,"total_payment"=>$amount);
            }
            else{
                $this->map['karaoke_deposit_count'][$value['bill_id']]['num_payment']++;
                
                $this->map['karaoke_deposit_count'][$value['bill_id']]["total_payment"]+=$amount;
            }
            if($value['payment_type_id'] == 'CASH')
            {
                $this->map['karaoke_deposit_total']['type_payment'][$value['payment_type_id']."_".$value['currency_id']] += $value['amount'];
            }
            else
            {
                $this->map['karaoke_deposit_total']['type_payment'][$value['payment_type_id']] +=$value['amount_vnd'];   
            }
            $this->map['karaoke_deposit_total']['total'] +=$amount;
        }
        /** END - dat coc karaoke **/
       
        //$this->map['receipt_payment'] = array();
        //$this->map['bar_payment'] = array();
        //$this->map['spa_payment'] = array();
        //System::debug($this->map['bar_deposit']);
        
        $this->map['dept'] = '0,'.Url::get('dept');
        //System::debug($this->map); 
        $this->parse_layout('report',array() + $this->map);
    }
    /** Thanh toán lễ tân lấy ra những hóa đơn thanh toán có tổng đặt cọc = tổng bill */
    function receipt_not_payment($receipter,$from_time,$to_time)
    {
        $sql = "
                SELECT
                    folio.id,
                    folio.create_time as time,
                    folio.reservation_room_id,
                    folio.reservation_id as recode,
                    'NOT_PAYMENT' as payment_type_id,
                    traveller.first_name||''|| traveller.last_name as traveller_name,
                    folio.user_id,
                    folio.id as folio_id,
                    customer.id as customer_id,
                    customer.name as customer_name,
                    '' as folio_code,
                    'VND' as currency_id,
                    '' as total_refund,
                    '' as amount,
                    '' as amount_vnd,
                    '' as total_deposit,
                    '' as total_bill 
                FROM
                    folio
                    inner join reservation on reservation.id = folio.reservation_id
                    left join customer on customer.id = reservation.customer_id
                    left join reservation_room on reservation_room.reservation_id=reservation.id 
                    left join reservation_traveller on reservation_traveller.id = folio.reservation_traveller_id
                    left join traveller on traveller.id = reservation_traveller.traveller_id                        
                WHERE
                    folio.create_time >= ".$from_time."
                    AND folio.create_time <= ".$to_time . ($receipter?" and folio.user_id = '".$receipter."'":"")."
                ORDER BY folio.id
        ";
        $receipt_not_payment = DB::fetch_all($sql);
        // lay ra bill not pay
        foreach($receipt_not_payment as $key => $value)
        {
            if($value['id'])
            {
                $sql = '
                        SELECT
                            traveller_folio.*
                        FROM
                            traveller_folio
                            inner join folio ON folio.id = traveller_folio.folio_id                           
                        WHERE
                            traveller_folio.folio_id = \''.$value['id'].'\'
                        ORDER BY folio.id
                ';
                $traveller_folios = DB::fetch_all($sql);
                foreach($traveller_folios as $k => $v)
                {
                    if($v['type'] == 'DEPOSIT' || $v['type'] == 'DEPOSIT_GROUP')
                    {
                        $receipt_not_payment[$key]['total_deposit'] += $v['total_amount'];
                        $receipt_not_payment[$key]['amount'] += $v['total_amount'];
                        $receipt_not_payment[$key]['amount_vnd'] += $v['total_amount'];
                    }else
                    {
                        $receipt_not_payment[$key]['total_bill'] += $v['total_amount'];
                    }                       
                }
            }
        }
        foreach($receipt_not_payment as $key => $value)
        {
            if(($value['total_bill'] - $value['total_deposit']) <> 0)
            {
                unset($receipt_not_payment[$key]);
            }
        }
        // lay ra bill not pay
        return $receipt_not_payment;       
    }
    /** Thanh toán mice lấy ra những hóa đơn thanh toán có tổng đặt cọc = tổng bill */
    function mice_not_payment($receipter,$from_time,$to_time)
    {
        $sql = "
                SELECT
                    mice_invoice.bill_id as id, 
                    mice_invoice.payment_time as time,
                    mice_invoice.total_amount as amount,
                    mice_invoice.total_amount as amount_vnd,
                    'VND' as currency_id,
                    'NOT_PAYMENT' as payment_type_id, 
                    mice_invoice.mice_reservation_id,
                    mice_invoice.bill_id,
                    mice_invoice.id as invoice_id,
                    mice_invoice.user_id,
                    traveller.first_name || ' ' || traveller.last_name as traveller_name,
                    customer.name as customer_name,
                    '' as total_deposit,
                    '' as total_bill
                FROM
                    mice_invoice
                    inner join mice_reservation on mice_reservation.id = mice_invoice.mice_reservation_id
                    inner join customer on mice_reservation.customer_id = customer.id
                    left join traveller on mice_reservation.traveller_id = traveller.id                    
                WHERE
                    mice_invoice.payment_time >= ".$from_time."
                    AND mice_invoice.payment_time <= ".$to_time.($receipter?" and mice_invoice.user_id = '".$receipter."'":"")."
                    AND mice_invoice.total_amount = 0
                ORDER BY mice_invoice.id
        ";
        $mice_not_payment = DB::fetch_all($sql);
        //System::debug($mice_not_payment);
        // lay ra bill not pay
        foreach($mice_not_payment as $key => $value)
        {
            if($value['invoice_id'])
            {
                $mice_invoice_detail = DB::fetch_all('
                                                SELECT
                                                    mice_invoice_detail.id,
                                                    mice_invoice_detail.mice_invoice_id as invoice_id,
                                                    mice_invoice_detail.type,
                                                    mice_invoice_detail.total_amount
                                                FROM
                                                    mice_invoice_detail
                                                WHERE
                                                    mice_invoice_detail.mice_invoice_id = \''.$value['invoice_id'].'\'
                                                ORDER BY
                                                    mice_invoice_detail.id ASC
                ');
                //System::debug($mice_invoice_detail);
                foreach($mice_invoice_detail as $k => $v)
                {
                    if($v['type'] == 'DEPOSIT_MICE')
                    {
                        $mice_not_payment[$key]['total_deposit'] += $v['total_amount'];
                    }else
                    {
                        $mice_not_payment[$key]['total_bill'] += $v['total_amount'];                        
                    }
                }
            }            
        }
        // lay ra bill not pay
        return $mice_not_payment;       
    }
    /** Thanh toán nhà hàng lấy ra những hóa đơn thanh toán có tổng đặt cọc = tổng bill */
    function bar_not_payment($receipter,$from_time,$to_time)
    {
        $check_bar_reservation = DB::fetch_all("
                    SELECT
                        bar_reservation.*
                    FROM
                        bar_reservation
                    WHERE
                        bar_reservation.time >= ".$from_time."
                        AND bar_reservation.time <= ".$to_time.($receipter?" and bar_reservation.user_id = '".$receipter."'":"")."
                    ORDER BY
                        bar_reservation.id ASC
                                        
        ");
        $bar_not_payment = array();
        foreach($check_bar_reservation as $key => $value)
        {
            if($value['total'] - $value['deposit'] <> 0)
            {
                unset($check_bar_reservation[$key]);
            }else
            {
                $bar_not_payment += DB::fetch_all("
                                SELECT 
                                    payment.id || '_' || bar_table.name as id,
                                    payment.id || '_' || bar_reservation.id  as payment_brs_id,
                                    bar_reservation.time_out as time,
                                    '0' as amount,
                                    '0' as amount_vnd,
                                    'NOT_PAYMENT' as payment_type_id,
                                    payment.currency_id,
                                    payment.user_id,
                                    bar_reservation.id as bill_id,
                                    bar_table.name as table_name,
                                    case 
                                        when bar_reservation.pay_with_room = 0 
                                        then bar_reservation.receiver_name
                                    else 
                                        traveller.first_name || ' ' || traveller.last_name
                                    end traveller_name,
                                    customer.name as customer_name,
                                    bar_reservation.total as total_bill,
                                    bar_reservation.deposit as total_deposit
                                FROM 
                                    payment
                                    inner join bar_reservation on bar_reservation.id = payment.bill_id and type = 'BAR'
                                    left join bar_reservation_table on bar_reservation.id = bar_reservation_table.bar_reservation_id
                                    left join bar_table on bar_table.id = bar_reservation_table.table_id
                                    left join reservation_traveller on reservation_traveller.id = bar_reservation.reservation_traveller_id
                                    left join traveller on traveller.id = reservation_traveller.traveller_id
                                    left join customer on bar_reservation.customer_id = customer.id
                                WHERE
                                    payment.bill_id = ".$value['id']."
                                    AND payment.type_dps ='BAR' 
                                ORDER BY 
                                    bar_reservation.id, payment.time");
            }
        }
        return $bar_not_payment;                    
    }
    
    /** Daund: add room name */
    function GetRoomName($folio_id)
    {
        $room_name = '';
        $traveller_folios = DB::fetch_all('
                                    SELECT
                                        reservation_room.id,
                                        room.id as room_id,
                                        room.name as room_name
                                    FROM
                                        reservation_room
                                        INNER JOIN room ON reservation_room.room_id = room.id
                                        INNER JOIN traveller_folio ON traveller_folio.reservation_room_id = reservation_room.id
                                    WHERE
                                        traveller_folio.folio_id in (\''.$folio_id.'\')
                                    ORDER BY
                                        room.id ASC
        ');
        foreach($traveller_folios as $key => $value)
        {
            if($room_name == '')
            {
                $room_name .= $value['room_name'];                
            }else
            {
                $room_name .= ','. $value['room_name'];
            }
        }
        
        return $room_name;  
    }
}

?>
