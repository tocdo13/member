<?php
class ReportRevenueInvoiceForm extends Form
{
	function ReportRevenueInvoiceForm()
	{
		Form::Form('ReportRevenueInvoiceForm');
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
        $this->map['user_id'] = Url::get('user_id')?Url::get('user_id'):'';
        $_REQUEST['user_id'] = $this->map['user_id'];
        if(Url::get('user_id'))
        { 
            $cond .= " AND (payment.user_id='".Url::get('user_id')."')"; 
            $cond_not_pay .=" AND (folio.user_id='".Url::get('user_id')."') ";
        }
        
        $this->map['portal_id'] = Url::get('portal_id')?Url::get('portal_id'):'';
        $_REQUEST['portal_id'] = $this->map['portal_id'];
        if(Url::get('portal_id'))
        { 
            $cond .= " AND (payment.portal_id='".Url::get('portal_id')."')";
            $cond_not_pay .=" AND (folio.portal_id='".Url::get('portal_id')."') "; 
        }
        
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
        //binh
        
        $this->map['search_invoice']=Url::get('search_invoice')?Url::get('search_invoice'):'';
        $this->map['to_code']= Url::get('to_code')?Url::get('to_code'):'';
        $this->map['from_code']= Url::get('from_code')?Url::get('from_code'):'';
        $_REQUEST['from_code'] = Url::get('from_code')?Url::get('from_code'):'';
        $_REQUEST['to_code'] = Url::get('to_code')?Url::get('to_code'):'';
        $_REQUEST['search_time']=Url::get('search_time')?Url::get('search_time'):'';
        $_REQUEST['search_invoice']=Url::get('search_invoice')?Url::get('search_invoice'):'';
       
        //end b�nh
        
        $this->map['from_code'] = Url::get('from_code')?Url::get('from_code'):'';
        $_REQUEST['from_code'] = $this->map['from_code'];
        $this->map['to_code'] = Url::get('to_code')?Url::get('to_code'):'';
        $_REQUEST['to_code'] = $this->map['to_code'];
        
        
        $to_date += $arr_to_time[0]*3600+$arr_to_time[1]*60;
        if(Url::get('search_invoice')==''){
            $cond .= " AND (payment.time>=".$from_date." AND payment.time<=".$to_date.")";
            $cond_not_pay .= " AND (folio.create_time>=".$from_date." AND folio.create_time<=".$to_date.")";
        }
        else
        {
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
                if(Url::get('recode'))
                {
                    $cond .= " AND (folio.reservation_id=".Url::get('recode').")";
                    $cond_not_pay .= " AND (folio.reservation_id=".Url::get('recode').")";
                }
        }
        
        $this->map['line_per_page'] = Url::get('line_per_page')?Url::get('line_per_page'):999;
        $_REQUEST['line_per_page'] = $this->map['line_per_page'];
        
        $this->map['no_of_page'] = Url::get('no_of_page')?Url::get('no_of_page'):50;
        $_REQUEST['no_of_page'] = $this->map['no_of_page'];
        
        $this->map['start_page'] = Url::get('start_page')?Url::get('start_page'):1;
        $_REQUEST['start_page'] = $this->map['start_page'];
        
        
       // if(Url::get('from_code') AND Url::get('to_code')){  }
        
        //echo $cond_not_pay;
        $item = DB::fetch_all("
                            SELECT
                                payment.*,
                                room.name as room_name,
                                CONCAT(concat(traveller.first_name,' '),traveller.last_name) as traveller_name,
                                customer.name as customer_name,
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
                                ,0 as package
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
                                ,0 as extra_service
                            FROM
                                payment
                                inner join folio on folio.id=payment.folio_id
                                left join reservation_traveller on folio.reservation_traveller_id = reservation_traveller.id
                                left join traveller ON traveller.id = reservation_traveller.traveller_id
                                left join reservation_room ON reservation_room.id = reservation_traveller.reservation_room_id   
                                left join reservation ON reservation.id = payment.reservation_id
                                left join customer ON  customer.id = reservation.customer_id                        
                                left join payment_type on payment_type.def_code=payment.payment_type_id
                                left join room on room.id=reservation_room.room_id
                            WHERE
                                ".$cond."
                            ORDER BY
                                folio.code,payment.time DESC
                            ");
        //System::debug($item);      
        $currency = DB::fetch_all("SELECT id FROM currency WHERE allow_payment=1");
        $payment_type = DB::fetch_all("SELECT def_code as id, name_".Portal::language()." as name FROM payment_type WHERE apply='ALL' AND def_code is not NULL ORDER BY def_code DESC");
        
        $array_total_page = array();
        foreach($payment_type as $id_pay=>$value_pay)
        {
            foreach($currency as $id_curr=>$value_curr)
            {
               $array_total_page[$value_pay['id']."_".$value_curr['id']] = 0; 
            }
        }
        foreach($currency as $id_curr=>$value_curr)
        {
           $array_total_page["total_".$value_curr['id']] = 0; 
        }
        
        $folio = '';
        $invoice = array();
        $i=0;
        $list_folio=array();
      
        foreach($item as $id=>$value)
        {
            $list_folio[$value['folio_id']]=$value['folio_id'];
            $item[$id]['time'] = date('H:i d/m/Y',$value['time']);
            if($folio!=$value['folio_id'])
            {
                $i++;
                $folio=$value['folio_id'];
                $invoice[$i]['id'] = $i;
                $invoice[$i]['folio_id'] = $value['folio_id'];
                $invoice[$i]['folio_code'] = $value['folio_code'];
                if($value['group_folio']==0)
                {
                    $invoice[$i]['link'] = '?page=view_traveller_folio&cmd=group_invoice&folio_id='.$value['folio_id'].'&id='.$value['recode'].'&customer_id='.$value['customer_id']; 
                }
                else
                {
                    $invoice[$i]['link'] = '?page=view_traveller_folio&cmd=invoice&folio_id='.$value['folio_id'].'&traveller_id='.$value['reservation_traveller_id'];
                }
                $invoice[$i]['traveller_name'] = $value['traveller_name'];
                $invoice[$i]['customer_name'] = $value['customer_name'];
                $invoice[$i]['room_name'] = $value['room_name'];
                $invoice[$i]['reservation_id'] = $value['recode'];
                $invoice[$i]['num'] = 1;
            }else{
                $invoice[$i]['num'] += 1;
            }
            $array_total_page[$value['payment_type_id']."_".$value['currency_id']] += $value['amount'];
            $array_total_page["total_".$value['currency_id']] += $value['amount']; 
        }
        //giap.ln truong hop folio co tong la 0, khong duoc thanh toan
        $items_not_payment = $this->get_list_not_payment($cond_not_pay);
        foreach($items_not_payment as $key=>$value)
        {
            $list_folio[$value['folio_id']]=$value['folio_id'];
            
            $i++;
            
            $invoice[$i]['id'] = $i;
            $invoice[$i]['folio_id'] = $value['folio_id'];
            $invoice[$i]['folio_code'] = $value['folio_code'];
            if($value['group_folio']==0)
            {
                $invoice[$i]['link'] = '?page=view_traveller_folio&cmd=group_invoice&folio_id='.$value['folio_id'].'&id='.$value['recode'].'&customer_id='.$value['customer_id']; 
            }
            else
            {
                $invoice[$i]['link'] = '?page=view_traveller_folio&cmd=invoice&folio_id='.$value['folio_id'].'&traveller_id='.$value['r_t_id']; /** Daund change traveller_id=> r_t_id*/
            }
            $invoice[$i]['traveller_name'] = $value['traveller_name'];
            $invoice[$i]['customer_name'] = $value['customer_name'];
            $invoice[$i]['room_name'] = $value['room_name'];
            $invoice[$i]['reservation_id'] = $value['recode'];
            $invoice[$i]['num'] = 1;
            
            $item[$key.'_'] = $value;
            $item[$key.'_']['bill_id'] =0;
            $item[$key.'_']['type'] ='';
            $item[$key.'_']['amount'] =0;
            $item[$key.'_']['time'] ='';
            $item[$key.'_']['payment_type_id'] ='';
            $item[$key.'_']['card_number'] =0;
            
            $item[$key.'_']['credit_card_id'] ='';
            $item[$key.'_']['currency_id'] ='';
            $item[$key.'_']['count'] ='';
            
            $item[$key.'_']['description'] ='';
            $item[$key.'_']['exchange_rate'] =0;
            $item[$key.'_']['bank_acc'] ='';
            
            $item[$key.'_']['type_dps'] ='';
            $item[$key.'_']['bank_fee'] =0;
            $item[$key.'_']['payment_point'] ='';
            $item[$key.'_']['payment_type_name'] ='';
        }
        
        //$invoice = $this->record_sort($invoice,'folio_id',true);
        foreach($invoice as $key=>$row)
        {
            $invoice[$key]['id'] = $key;
        }
        //$item = $this->record_sort($item,'folio_id',true);
        //end giap.ln 
        
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
            }
       }
       foreach($item as $key=>$val){
            foreach($folio as $k=>$v){
                if($val['folio_id']==$k){
                    foreach($v as $k_type=>$v_folio){
                        foreach($v_folio as $v_folio_child ){
                            if($k_type == 'EXTRA_SERVICE' && $v_folio_child['payment_type'] =='SERVICE' ){
                                if($v_folio_child['service_id'] != 40 && $v_folio_child['service_id'] != 41 && strtoupper(substr($v_folio_child['code'],0,4)) != 'TOUR')
                                {
                                    
                                    $item[$key]['extra_service'] += $v_folio_child['amount'];
                                }
                                else
                                {
              						//if($v_folio_child['code'] == 'TOUR')
                                    //{
                                        //$item[$key]['tour'] += $v_folio_child['amount'];
                                    //}
                                    if(strtoupper(substr($v_folio_child['code'],0,4)) == 'TOUR')
                                    {
                                        $item[$key]['tour'] += $v_folio_child['amount'];
                                    }
                                    /*if($v_folio_child['code'] == 'EXTRA_BED')
                                    {
                                       
                                        $item[$key]['extra_bed'] += $v_folio_child['amount'];
                                    } 
                                    if($v_folio_child['code'] == 'BABY_COT')
                                    {
                                       
                                        $item[$key]['baby_cot'] += $v_folio_child['amount'];
                                    }*/
                                }  
                            }
                            else if($k_type == 'ROOM' || ($k_type == 'EXTRA_SERVICE' && $v_folio_child['payment_type'] =='ROOM' && $v_folio_child['code'] != 'EXTRA_BED' && $v_folio_child['code'] != 'BABY_COT' && $v_folio_child['code'] != 'TOUR'))
        						$item[$key]['total_room'] += $v_folio_child['amount'];
                            else if($k_type == 'ROOM' || ($k_type == 'EXTRA_SERVICE' && $v_folio_child['payment_type'] =='ROOM' && ($v_folio_child['code'] == 'EXTRA_BED' || $v_folio_child['code'] == 'BABY_COT')))
                                if($v_folio_child['code'] == 'EXTRA_BED')
                                {
                                   
                                    $item[$key]['extra_bed'] += $v_folio_child['amount'];
                                } 
                                if($v_folio_child['code'] == 'BABY_COT')
                                {
                                   
                                    $item[$key]['baby_cot'] += $v_folio_child['amount'];
                                }
                            else if($k_type == 'MASSAGE')
        						$item[$key]['spa'] += $v_folio_child['amount'];
                            else if($k_type == 'DEPOSIT' || $k_type =='DEPOSIT_GROUP')
        						$item[$key]['deposit'] += $v_folio_child['amount'];   
                            else if($k_type == 'BAR')	
                                $item[$key]['restaurant'] += $v_folio_child['amount']; 
                            else if($k_type == 'PACKAGE')	
        						$item[$key]['package'] += $v_folio_child['amount']; 
                            else if($k_type == 'TELEPHONE')	
        						$item[$key]['telephone'] += $v_folio_child['amount']; 
                            else if($k_type == 'EQUIPMENT')	
        						 $item[$key]['equip'] += $v_folio_child['amount']; 
                            else if($k_type == 'LAUNDRY')
        						$item[$key]['laundry'] += $v_folio_child['amount'];
                            else if($k_type == 'MINIBAR')
        					   $item[$key]['minibar'] += $v_folio_child['amount'];
                            else if($k_type == 'TICKET')
                                $item[$key]['ticket'] += $v_folio_child['amount'];
                            else if($k_type == 'BANQUET')
                                $item[$key]['banquet'] += $v_folio_child['amount'];
                            else if($k_type == 'VE')
                                $item[$key]['vending'] += $v_folio_child['amount'];
                            else if($k_type == 'KARAOKE')
                                $item[$key]['karaoke'] += $v_folio_child['amount'];       
        				
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
                                folio.customer_id,
                                customer.name as customer_name,
                                room.name as room_name,
                                CONCAT(concat(traveller.first_name,' '),traveller.last_name) as traveller_name,
                                NVL(folio.reservation_room_id,0) as group_folio,
                                traveller.id as traveller_id,
                                folio.reservation_traveller_id as r_t_id, -- daund add
                                folio.reservation_id as recode,
                                folio.total as total,
                                folio.code as folio_code, -- oanh add
								reservation_room.id as rr_id,
                                reservation_room.time_in,
                                reservation_room.time_out,
                                '' as user_id,
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
                                left join reservation_traveller on folio.reservation_traveller_id = reservation_traveller.id
                                left join traveller ON traveller.id = reservation_traveller.traveller_id
                                left join reservation_room ON reservation_room.id = reservation_traveller.reservation_room_id
                                left join reservation ON reservation.id = folio.reservation_id
                                left join customer ON  customer.id = reservation.customer_id                              
                                left join room on room.id=reservation_room.room_id
                WHERE ".$cond_not_pay." AND folio.total=0
                ORDER BY
                    folio.code
                ";
            $items = DB::fetch_all($sql);
            //System::debug($items);
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