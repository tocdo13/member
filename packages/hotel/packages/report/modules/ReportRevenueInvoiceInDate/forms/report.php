<?php
class ReportRevenueInvoiceInDateForm extends Form
{
	function ReportRevenueInvoiceInDateForm()
	{
		Form::Form('ReportRevenueInvoiceInDateForm');
         $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
         $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	 $this->link_js('packages/core/includes/js/jquery/datepicker.js');
             $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{
        $this->map = array();
        $cond = " (1=1) ";
        $cond_not_pay = " (1=1) ";
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
        $_REQUEST['from_date'] = $this->map['from_date'];
        $from_date = Date_Time::to_time($this->map['from_date']);  
        $this->map['from_time'] = Url::get('from_time')?Url::get('from_time'):'00:00';
        $_REQUEST['from_time'] = $this->map['from_time']; 
        $arr_from_time = explode(":",$this->map['from_time']);
        $from_date += $arr_from_time[0]*3600+$arr_from_time[1]*60;
        $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        $_REQUEST['to_date'] = $this->map['to_date'];
        $to_date = Date_Time::to_time($this->map['to_date']);
        $this->map['to_time'] = Url::get('to_time')?Url::get('to_time'):'23:59';
        $_REQUEST['to_time'] = $this->map['to_time']; $arr_to_time = explode(":",$this->map['to_time']);
        $to_date += $arr_to_time[0]*3600+$arr_to_time[1]*60;   
        if(Url::get('search_invoice')==''){
            $cond .= " AND (payment.time>=".$from_date." AND payment.time<=".$to_date.")";
            $cond_not_pay .= " AND (folio.create_time>=".$from_date." AND folio.create_time<=".$to_date.")";
            
        }else{
                if(Url::get('from_code') AND Url::get('to_code'))
                {
                    $cond .= " AND ( folio.id>=".Url::get('from_code')." AND folio.id<=".Url::get('to_code').")";
                    $cond_not_pay .= " AND ( folio.id>=".Url::get('from_code')." AND folio.id<=".Url::get('to_code').")";
                    //$cond .= " AND SUBSTR(bar_reservation.code, 6 ) >=".Url::get('from_code')." AND SUBSTR(bar_reservation.code, 6 )<=".Url::get('to_code');
                }
                elseif(Url::get('from_code') AND !Url::get('to_code'))
                {
                    $cond .= " AND folio.id >=".Url::get('from_code');
                    $cond_not_pay .= " AND folio.id >=".Url::get('from_code');
                }
                elseif(!Url::get('from_code') AND Url::get('to_code'))
                {
                    $cond .= " AND folio.id<=".Url::get('to_code');
                    $cond_not_pay .= " AND folio.id<=".Url::get('to_code');
                }
        }
        
        
        $this->map['line_per_page'] = Url::get('line_per_page')?Url::get('line_per_page'):999;
        $_REQUEST['line_per_page'] = $this->map['line_per_page'];
        
        $this->map['no_of_page'] = Url::get('no_of_page')?Url::get('no_of_page'):50;
        $_REQUEST['no_of_page'] = $this->map['no_of_page'];
        
        $this->map['start_page'] = Url::get('start_page')?Url::get('start_page'):1;
        $_REQUEST['start_page'] = $this->map['start_page'];
        
        
       // if(Url::get('from_code') AND Url::get('to_code')){  }
        
        //echo $cond;]
        $item = DB::fetch_all("
                            SELECT
                                payment.*,
                                room.name as room_name,
                                CONCAT(concat(traveller.first_name,' '),traveller.last_name) as traveller_name,
                                NVL(folio.reservation_room_id,0) as group_folio,
                                traveller.id as traveller_id,
                                folio.reservation_id as recode,
                                folio.total as total,
                                folio.code as folio_code, -- oanh add
								reservation_room.id as rr_id,
                                payment_type.name_".Portal::language()." as payment_type_name,
                                reservation_room.time_in,
                                reservation_room.time_out,
                                customer.id as customer_id,
                                reservation_traveller.id as reservation_traveller_id
                                ,0 as night
                                ,0 as total_room
                                ,0 as extra_bed
                                ,0 as baby_cot
                                ,0 as telephone
                                ,0 as restaurant
                                ,0 as minibar
                                ,0 as laundry
                                ,0 as equip
                                ,0 as spa
                                ,0 as karaoke
                                ,0 as banquet
                                ,0 as vending
                                ,0 as ticket
                                ,0 as deposit
                                ,0 as tour
                                ,0 as other
                                ,0 as extra_service,
                                0 as ei_lo
                                ,0 as rate
                            FROM
                                payment
                                inner join folio on folio.id=payment.folio_id
                                inner join reservation_traveller on folio.reservation_traveller_id = reservation_traveller.id
                                inner join traveller ON traveller.id = reservation_traveller.traveller_id
                                inner join reservation_room ON reservation_room.id = reservation_traveller.reservation_room_id   
                                inner join reservation ON reservation.id =  reservation_room.reservation_id
                                inner join customer ON  customer.id = reservation.customer_id                        
                                inner join payment_type on payment_type.def_code=payment.payment_type_id
                                inner join room on room.id=reservation_room.room_id
                            WHERE
                                ".$cond."
                            ORDER BY
                                folio.code,payment.time DESC
                            ");
         $folio_not_pay = $this->get_list_not_payment($cond_not_pay);
        //System::debug($folio_not_pay); 
        foreach($item as $key=>$value){
            foreach($item[$key] as $key2=>$value2){
                foreach($folio_not_pay as $k=>$v){
                    foreach($folio_not_pay[$k] as $k2=>$v2){
                        if(!isset($folio_not_pay[$k][$key2])){
                            $folio_not_pay[$k][$key2] = '0';
                        }
                        if(!isset($item[$key][$k2])){
                            unset($folio_not_pay[$k][$k2]);
                        }
                    }  
                    if(isset($folio_not_pay[$k]['folio_code'])){
                        unset($folio_not_pay[$k]['folio_code']);
                    }               
                }
            }
            break;         
        }
        //System::debug($folio_not_pay);
        $item+=$folio_not_pay; 
        $currency = DB::fetch_all("SELECT id FROM currency WHERE allow_payment=1");
        $payment_type = DB::fetch_all("SELECT def_code as id, name_".Portal::language()." as name FROM payment_type WHERE apply='ALL' AND def_code is not NULL ORDER BY def_code DESC");
        
        $array_total_page = array();
        foreach($payment_type as $id_pay=>$value_pay)
        {
            foreach($currency as $id_curr=>$value_curr)
            {
               if(($value_pay['id']=='DEBIT' && $value_curr['id']!='VND')||($value_pay['id']=='FOC' && $value_curr['id']!='VND')){
                    continue;
               }
               if($value_pay['id']=='DEBIT' || $value_pay['id']=='BANK'){
                    if(!isset($array_total_page['total_LEDGER_'.$value_curr['id']])){
                        $array_total_page['total_LEDGER_'.$value_curr['id']] = 0;
                    }                                    
               }
               else
               $array_total_page[$value_pay['id']."_".$value_curr['id']] = 0; 
            }
        }
        foreach($payment_type as $key=>$value){
            if($key=='DEBIT' || $key=='BANK')
            unset($payment_type[$key]);
        }
        foreach($currency as $id_curr=>$value_curr)
        {
           $array_total_page["total_".$value_curr['id']] = 0; 
        }
        
        $folio = '';
        $invoice = array();
        $i=0;
        $list_folio=array();
        $key_temp = 0;
        $payment_type_id = "";
        foreach($item as $id=>$value){
            foreach($currency as $k=>$v){
                foreach($payment_type as $k2=>$v2){
                    if(($k2=='DEBIT' && $k!='VND') || ($k2=='FOC' && $k!='VND'))
                    {
                        continue;
                    }
                    else if($value['payment_type_id']==$k2 && $value['currency_id']==$k){
                        $item[$id]['total_payment_'.$k2."_".$k] = $value['amount'];
                    }
                    else{
                        $item[$id]['total_payment_'.$k2."_".$k] = 0;
                    }
                }
                if($value['payment_type_id']=='DEBIT' || $value['payment_type_id']=='BANK'){
                        if($value['currency_id']==$k){
                            $item[$id]['total_payment_LEDGER_'.$k] = $value['amount'];
                        }
                        else{
                            $item[$id]['total_payment_LEDGER_'.$k] = 0;
                        }
                }
                else{
                    $item[$id]['total_payment_LEDGER_'.$k] = 0;
                }
            }
        }
        foreach($item as $id=>$value)
        {          
            $list_folio[$value['folio_id']]=$value['folio_id'];
            $item[$id]['time'] = date('H:i d/m/Y',$value['time']);
            if($folio!=$value['folio_id'])
            {
                $folio=$value['folio_id'];
                $key_temp = $id;
                if($value['group_folio']==0)
                {
                    $item[$id]['link'] = '?page=view_traveller_folio&folio_id='.$value['folio_id'].'&id='.$value['recode'].'&cmd=group_invoice&customer_id='.$value['customer_id']; 
                }
                else
                {
                    $item[$id]['link'] = '?page=view_traveller_folio&cmd=invoice&folio_id='.$value['folio_id'].'&traveller_id='.$value['reservation_traveller_id'];
                }
            }else{
                    foreach($currency as $k=>$v){
                        foreach($payment_type as $k2=>$v2){
                            if(($k2=='FOC' && $k!='VND'))
                            {
                                continue;
                            }
                            else{
                                $item[$key_temp]['total_payment_'.$k2."_".$k]+=$value['total_payment_'.$k2."_".$k];
                                $item[$key_temp]['total_payment_LEDGER_'.$k]+=$value['total_payment_LEDGER_'.$k];
                            }
                        }
                    }
                    unset($item[$id]);
            }
            if($value['payment_type_id']=='DEBIT' || $value['payment_type_id']=='BANK'){
                $array_total_page['total_LEDGER_'.$value['currency_id']] += $value['amount']; 
            }
            else{
                if(isset($array_total_page[$value['payment_type_id']."_".$value['currency_id']])){
                 $array_total_page[$value['payment_type_id']."_".$value['currency_id']] += $value['amount'];
                 }  
            } 
            if(isset($array_total_page["total_".$value['currency_id']])){
            $array_total_page["total_".$value['currency_id']] += $value['amount'];
            }
        }
        //System::debug($array_total_page); exit();
        $users = DB::fetch_all('SELECT party.user_id as id,party.user_id as name FROM party INNER JOIN account ON party.user_id = account.id WHERE party.type=\'USER\' AND account.is_active = 1 ORDER BY	party.user_id ASC ');
        $this->map['user_id_list'] = array(''=>Portal::language('all'))+String::get_list($users);
        $portals = Portal::get_portal_list();
        $this->map['portal_id_list'] = array(''=>Portal::language('all')) + String::get_list($portals);
        $this->map += $array_total_page; 
        //tieubinh them vao
       $folio_id = implode(',',$list_folio);
       $folio = array();
       if($folio_id !=''){
            $traveller_folio = $this->get_traveller_folios($folio_id);
       }  
       $i=0;
       if(!empty($traveller_folio)){
            foreach($traveller_folio as $key=>$val){
                $i++;
                $folio[$val['folio_id']][$val['type']][$i]['id'] =$val['id'];
                $folio[$val['folio_id']][$val['type']][$i]['service_id'] =$val['service_id'];
                $folio[$val['folio_id']][$val['type']][$i]['payment_type'] =$val['payment_type'];
                $folio[$val['folio_id']][$val['type']][$i]['code'] =$val['code'];
                $folio[$val['folio_id']][$val['type']][$i]['amount'] =$val['amount'];
                $folio[$val['folio_id']][$val['type']][$i]['count'] =$val['type_count'];
            }
       }
       foreach($item as $key=>$val){
            foreach($folio as $k=>$v){
                if($val['folio_id']==$k){                 
                    foreach($v as $k_type=>$v_folio){
                        foreach($v_folio as $v_folio_child ){
                            if($k_type == 'EXTRA_SERVICE'){
                                $type = strtoupper(substr($v_folio_child['code'],0,4));
                                //System::debug($type);
                                if($v_folio_child['service_id'] != 40 && $v_folio_child['service_id'] != 41 && $v_folio_child['service_id'] != 45 && $v_folio_child['service_id'] != 46 && $v_folio_child['service_id'] != 47 && $type != 'TOUR' && $type!='UPGR' && $type!='PERS')
                                    $item[$key]['extra_service'] += $v_folio_child['amount'];
                                else
                                {
                                    if($type == 'TOUR') // Nhung ma co bat dau = TOUR thuoc nhom dich vu TOUR
                                    {
                                        $item[$key]['tour'] += $v_folio_child['amount'];
                                    }
                                    else if($v_folio_child['service_id'] == 40 || $v_folio_child['service_id'] == 41 || $type=='PERS') // Nhung ma co bat dau = PERS thuoc nhom dich vu PERS
                                    {
                                       $item[$key]['extra_bed'] += $v_folio_child['amount'];
                                    }
                                    else if($v_folio_child['service_id'] == 45 || $v_folio_child['service_id'] == 46 ||$v_folio_child['service_id'] == 47 || $type=='UPGR')// Nhung ma co bat dau = UPGR thuoc nhom dich vu UPGR
                                    {
                                        $item[$key]['ei_lo'] += $v_folio_child['amount'];
                                    }
                                    
                                }  
                            }
                            else if($k_type == 'ROOM')
                            {
        						$item[$key]['total_room'] += $v_folio_child['amount'];
                                if(!empty($v_folio_child['count'])){
                                    $item[$key]['night'] = $v_folio_child['count'];
                                }
                                else{
                                    $item[$key]['night'] = 0;
                                }
                                    if($item[$key]['night']!=0){
                                    $item[$key]['rate'] = $item[$key]['total_room']/$item[$key]['night'];
                                }  
                            }
                            else if($k_type == 'MASSAGE')
        						$item[$key]['spa'] += $v_folio_child['amount'];
                            else if($k_type == 'DEPOSIT' || $k_type =='DEPOSIT_GROUP')
        						$item[$key]['deposit'] += $v_folio_child['amount'];   
                            else if($k_type == 'BAR')	
                                $item[$key]['restaurant'] += $v_folio_child['amount']; 
                            else if($k_type == 'TELEPHONE')	
        						$item[$key]['telephone'] += $v_folio_child['amount'];  
                            else if($k_type == 'LAUNDRY')
        						$item[$key]['laundry'] += $v_folio_child['amount'];
                            else if($k_type == 'MINIBAR')
        					   $item[$key]['minibar'] += $v_folio_child['amount'];     
        				    else{
        				        $item[$key]['other'] += $v_folio_child['amount'];
         				    }
                        }                       
                    }    
                                                              
                }
            }
            
       } 
        //tieubinh
        
        $this->parse_layout('report',array(
                                        'currency'=>$currency,
                                        'payment_type'=>$payment_type,
                                        'invoice'=>$invoice,
                                        'items'=>$item,
                                        )+$this->map);
	}
    //giap.ln truong hop folio tong =0, khong duoc thanh toan 
    function get_list_not_payment($cond_not_pay)
    {
        $sql ="SELECT 
                                folio.id || '_' || reservation_room.id as id,
                                folio.id as folio_id,
                                room.name as room_name,
                                CONCAT(concat(traveller.first_name,' '),traveller.last_name) as traveller_name,
                                NVL(folio.reservation_room_id,0) as group_folio,
                                traveller.id as traveller_id,
                                folio.reservation_id as recode,
                                folio.total as total,
                                folio.code as folio_code, -- oanh add
								reservation_room.id as rr_id,
                                reservation_room.time_in,
                                reservation_room.time_out,
                                CASE 
                                    WHEN (DATE_TO_UNIX(reservation_room.departure_time)-DATE_TO_UNIX(reservation_room.arrival_time))=0
                                    THEN 1
                                ELSE    
                                    (DATE_TO_UNIX(reservation_room.departure_time)-DATE_TO_UNIX(reservation_room.arrival_time))/(24*3600) 
                                END as night
                                ,0 as total_room
                                ,0 as extra_bed
                                ,0 as baby_cot
                                ,0 as telephone
                                ,0 as restaurant
                                ,0 as minibar
                                ,0 as laundry
                                ,0 as equip
                                ,0 as spa
                                ,0 as karaoke
                                ,0 as banquet
                                ,0 as vending
                                ,0 as ticket
                                ,0 as deposit
                                ,0 as tour
                                ,0 as other
                                ,0 as extra_service,
                                0 as ei_lo
                            FROM
                                folio 
                                inner join reservation_traveller on folio.reservation_traveller_id = reservation_traveller.id
                                inner join traveller ON traveller.id = reservation_traveller.traveller_id
                                inner join reservation_room ON reservation_room.id = reservation_traveller.reservation_room_id                             
                                inner join room on room.id=reservation_room.room_id
                WHERE ".$cond_not_pay." AND folio.total=0
                ORDER BY
                    folio.create_time ASC, folio.id ASC
                ";
            $items = DB::fetch_all($sql);
            return $items;
    }
    function record_sort($records, $field, $reverse=false)
    {
        $hash = array();
        
        foreach($records as $record)
        {
            //kiem tra da ton tai key truoc do 
            //neu ton tai key = $record[$field]_index;
            if(isset($hash[$record[$field].'_1']))
            {
                $index  = $this->get_hash_index($hash,$record[$field]);
                $hash[$record[$field].'_'.$index] = $record;
            }
            else
            {
                $hash[$record[$field].'_1'] = $record;
            }
            
        }
        
        ($reverse)? krsort($hash) : ksort($hash);
        
        $records = array();
        $i= 1;
        foreach($hash as $record)
        {
            $records [$i++]= $record;
        }
        
        return $records;
    }
    
    function get_hash_index($hash,$field)
    {
        $index = 2;
        while(isset($hash[$field.'_'.$index]))
            $index++;
        return $index;
    }
    //end giap.ln 
    
    //tieubinh them vao
     function get_traveller_folios($bill_id)
    {
		return $traveller_folios = DB::fetch_all('
						SELECT 
                            ROW_NUMBER () OVER (order by traveller_folio.folio_id asc) as id
                            ,sum(traveller_folio.total_amount) as amount
                            ,count(traveller_folio.total_amount) as type_count
                            ,traveller_folio.folio_id
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
                            ,extra_service.code
                            ,extra_service_invoice_detail.service_id
                            ,extra_service_invoice.payment_type
                            ,traveller_folio.folio_id
                            
                         ORDER BY
                            id');	
	}
    //tieubinh
}

?>