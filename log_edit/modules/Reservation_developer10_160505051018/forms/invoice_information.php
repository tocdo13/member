<?php
class InvoiceInformationForm extends Form
{
	function InvoiceInformationForm()
    {
		Form::Form('InvoiceInformationForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
    }
	function draw()
    {
        $this->map = array();
        
        /** lay thong tin chung cua recode **/
        $row = DB::fetch('
                        SELECT
                            reservation.*,
                            customer.name as customer_name,
                            customer.def_name as customer_full_name,
                            customer.tax_code as customer_tax_code,
                            customer.address as customer_address
                        FROM
                            reservation
                            inner join customer on customer.id=reservation.customer_id
                        WHERE
                            reservation.id='.Url::get('id').'
                        ');
        $this->map+=$row;
        
        
        /** lay thong tin cua cac folio duoc thanh toan trong recode nay **/
        $others = DB::fetch_all('SELECT 
										(traveller_folio.folio_id || \'_\' || traveller_folio.type || \'_\' || traveller_folio.invoice_id) as id
										,traveller_folio.type
										,traveller_folio.invoice_id
										,traveller_folio.description
										,traveller_folio.tax_rate
										,traveller_folio.service_rate
										,traveller_folio.amount as amount
                                        ,traveller_folio.total_amount as total_amount
										,traveller_folio.percent as percent 
										,traveller_folio.folio_id
										,folio.reservation_id
										,folio.customer_id
										,folio.reservation_traveller_id as traveller_id
                                        ,folio.code as folio_code
                                        ,reservation.id as recode_invoice
                                        ,room.name as room_name
									FROM traveller_folio 
										inner join folio ON folio.id = traveller_folio.folio_id
                                        left join reservation_room on reservation_room.id=traveller_folio.reservation_room_id
                                        left join reservation on reservation_room.reservation_id=reservation.id
                                        left join room on room.id=reservation_room.room_id
									WHERE 
                                        1>0 
                                        AND (traveller_folio.reservation_id='.Url::get('id').' 
                                            OR (traveller_folio.reservation_room_id in (
                                                                                        SELECT
                                                                                            reservation_room.id
                                                                                        FROM
                                                                                            reservation_room
                                                                                        WHERE
                                                                                            reservation_room.reservation_id='.Url::get('id').'
                                                                                            AND reservation_room.status<>\'CANCEL\'	
                                                                                            AND reservation_room.status<>\'NOSHOW\'	
                                                                                        ) 
                                                    AND add_payment=1)
                                                )
                                    ORDER BY
                                            folio.reservation_id,traveller_folio.reservation_room_id
                                            ');
        //System::debug($others);
        
		$folio_other = array();
        $cond_payment = '';
        $folio_arr = array();
		foreach($others as $k => $val) 
        {
            if(isset($folio_other[$val['type'].'_'.$val['invoice_id']]))
            {
				$folio_other[$val['type'].'_'.$val['invoice_id']]['amount'] += $val['amount'];
                $folio_other[$val['type'].'_'.$val['invoice_id']]['total_amount'] += $val['total_amount'];
				$folio_other[$val['type'].'_'.$val['invoice_id']]['percent'] += $val['percent'];
			}
            else
            {
				$folio_other[$val['type'].'_'.$val['invoice_id']]['id'] = $val['invoice_id'];
				$folio_other[$val['type'].'_'.$val['invoice_id']] = $val;
				$folio_other[$val['type'].'_'.$val['invoice_id']]['href'] = '';
			}
			if($val['customer_id'] == '') 
            {
				 if(isset($val['folio_code']))
                    $folio_other[$val['type'].'_'.$val['invoice_id']]['href'] .= ($folio_other[$val['type'].'_'.$val['invoice_id']]['href']==''?'':'<br/>').'<a target="_blank" href="'.'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&folio_id='.$val['folio_id'].'&traveller_id='.$val['traveller_id'].''.'">No.F'.str_pad($val['folio_code'],6,"0",STR_PAD_LEFT).'</a>';
                 else
                    $folio_other[$val['type'].'_'.$val['invoice_id']]['href'] .= ($folio_other[$val['type'].'_'.$val['invoice_id']]['href']==''?'':'<br/>').'<a target="_blank" href="'.'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&folio_id='.$val['folio_id'].'&traveller_id='.$val['traveller_id'].''.'">Ref'.str_pad($val['folio_id'],6,"0",STR_PAD_LEFT).'</a>';                             
            }
            else
            {
                if(isset($val['folio_code']))
                    $folio_other[$val['type'].'_'.$val['invoice_id']]['href'] .= ($folio_other[$val['type'].'_'.$val['invoice_id']]['href']==''?'':'<br/>').'<a target="_blank" href="'.'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&cmd=group_invoice&folio_id='.$val['folio_id'].'&customer_id='.$val['customer_id'].''.'">No.F'.str_pad($val['folio_code'],6,"0",STR_PAD_LEFT).'</a>';
                else
                    $folio_other[$val['type'].'_'.$val['invoice_id']]['href'] .= ($folio_other[$val['type'].'_'.$val['invoice_id']]['href']==''?'':'<br/>').'<a target="_blank" href="'.'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&cmd=group_invoice&folio_id='.$val['folio_id'].'&customer_id='.$val['customer_id'].''.'">Ref_F'.str_pad($val['folio_id'],6,"0",STR_PAD_LEFT).'</a>';
            }
            if(!isset($folio_arr[$val['folio_id']])){
                $folio_arr[$val['folio_id']] = $val['folio_id'];
                $cond_payment .= $cond_payment==''?' and ( folio.id='.$val['folio_id']:' or folio.id='.$val['folio_id'];
            }
            
		}
        if($cond_payment!='')
            $cond_payment .= ')';
        
        //System::debug($folio_other);
        
        
        /** lay thong tin cac phong trong recode **/
        $reservation_rooms = DB::fetch_all('select 
										reservation_room.id
										,reservation_room.time_in
										,reservation_room.time_out
										,reservation_room.status
										,reservation_room.reservation_id
										,reservation_room.room_id
										,reservation_room.foc
										,reservation_room.foc_all
                                        ,reservation_room.change_room_from_rr
                                        ,reservation_room.change_room_to_rr
										,room.name as room_name
									FROM reservation_room
										 INNER JOIN reservation ON reservation_room.reservation_id = reservation.id
										 INNER JOIN room ON reservation_room.room_id = room.id
									WHERE 
                                        reservation_room.reservation_id = '.Url::get('id').'	
                                        AND reservation_room.status<>\'CANCEL\'	
                                        AND reservation_room.status<>\'NOSHOW\'			
								'); 
        require_once 'packages/hotel/packages/reception/modules/CreateTravellerFolio/get_reservation_room.php';
        $items = array();
        $this->map['total_before_tax'] = 0;
        $this->map['service_amount'] = 0;
        $this->map['tax_amount'] = 0;
        $this->map['total_amount'] = 0;
        foreach($reservation_rooms as $key => $value)
        {
			$record = get_reservation_room_detail($key,$folio_other);
            System::debug($record);
            foreach($record as $k=>$v){
                $items[$k]['id'] = $k;
                $items[$k]['type'] = $v['type'];
                $items[$k]['folio'] = isset($folio_other[$k])?$folio_other[$k]['href']:'';
                $items[$k]['description'] = $v['description'];
                $items[$k]['room_name'] = Portal::language('room').':'.$value['room_name'];
                $items[$k]['total_before_tax'] = round($v['amount'],2);
                $total_before_tax = $v['amount'];
                $items[$k]['total_amount'] = round($v['amount']*( (1+$v['service_rate']/100)*(1+$v['tax_rate']/100) ));
                $items[$k]['tax_amount'] = ($v['tax_rate']!=0)?round($items[$k]['total_amount']/(100/100+$v['tax_rate']),2):0;
                $total_amount = $v['amount']*( (1+$v['service_rate']/100)*(1+$v['tax_rate']/100) );
                $tax_amount = ($v['tax_rate']!=0)?$total_amount/(100/100+$v['tax_rate']):0;
                $items[$k]['service_amount'] = $items[$k]['total_amount'] - $total_before_tax - $tax_amount;
                if($v['type']=='DISCOUNT' OR $v['type']=='DEPOSIT'){
                    $this->map['total_before_tax'] -= $total_before_tax;
                    $this->map['service_amount'] -= $items[$k]['service_amount'];
                    $this->map['tax_amount'] -= $tax_amount;
                    $this->map['total_amount'] -= $items[$k]['total_amount'];
                }else{
                    $this->map['total_before_tax'] += $total_before_tax;
                    $this->map['service_amount'] += $items[$k]['service_amount'];
                    $this->map['tax_amount'] += $tax_amount;
                    $this->map['total_amount'] += $items[$k]['total_amount'];
                }
                
                
                $items[$k]['link_invoice'] = '';
                
                if($v['type']=='LAUNDRY'){
					$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=laundry_invoice&id='.$v['id'];
					$items[$k]['link_invoice'] = '<a target="_blank" href="'.$her.'" = array>#LD_'.$v['position'].'</a>';
				}else if($v['type']=='MINIBAR'){
					$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=minibar_invoice&id='.$v['id'];
					$items[$k]['link_invoice'] = '<a target="_blank" href="'.$her.'" = array>#MN_'.$v['position'].'</a>';
				}else if($v['type']=='EQUIPMENT'){
					$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=equipment_invoice&id='.$v['id'];
					$items[$k]['link_invoice'] = '<a target="_blank" href="'.$her.'" = array>#'.$v['id'].'</a>';
				}else if($v['type']=='BAR'){
					$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=touch_bar_restaurant&cmd=detail&'.md5('act').'='.md5('print_bill').'&'.md5('preview').'=1&id='.$v['id'].$v['link'];
					$items[$k]['link_invoice'] = '<a target="_blank" href="'.$her.'" = array>#'.$v['code'].'</a>';
				}else if($v['type']=='TICKET'){
					$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=ticket_invoice_group&cmd=bill&'.md5('act').'='.md5('print').'&id='.$v['id'];
					$items[$k]['link_invoice'] = '<a target="_blank" href="'.$her.'" = array>#'.$v['id'].'</a>';
				}else if($v['type']=='EXTRA_SERVICE'){
					$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=extra_service_invoice&cmd=view_receipt&id='.$v['ex_id'];
					$items[$k]['link_invoice'] = '<a target="_blank" href="'.$her.'" = array>#'.$v['ex_bill'].'</a>';
				}
                $note = '';
                if(isset($folio_other[$k])){
                    $note .= ' <p>- Đã tạo hóa đơn khoản tiền: '.System::display_number($folio_other[$k]['total_amount']).' tại folio '.$folio_other[$k]['href'].' </p>';
                    unset($folio_other[$k]);
                }
                $items[$k]['note'] = $note;
            }
		}
        //echo $this->map['total_amount'];
        if($row['deposit']>0)
        {
            $k = 'DEPOSIT_GROUP_'.Url::get('id');
            $items[$k]['id'] = $k;
            $items[$k]['type'] = 'DEPOSIT_GROUP';
            $items[$k]['folio'] = isset($folio_other[$k])?$folio_other[$k]['href']:'';
            $items[$k]['description'] = Portal::language('deposit_for_group');
            $items[$k]['room_name'] = Portal::language('deposit_for_group');
            $items[$k]['total_before_tax'] = $row['deposit'];
            $items[$k]['total_amount'] = $row['deposit'];
            $items[$k]['tax_amount'] = 0;
            $items[$k]['service_amount'] = 0;
            
            $this->map['total_before_tax'] -= $items[$k]['total_before_tax'];
            $this->map['service_amount'] -= $items[$k]['service_amount'];
            $this->map['tax_amount'] -= $items[$k]['tax_amount'];
            $this->map['total_amount'] -= $items[$k]['total_amount'];
            
            $items[$k]['link_invoice'] = '';
            $note = '';
            if(isset($folio_other[$k])){
                $note .= ' <p>- Đã tạo hóa đơn khoản tiền: '.System::display_number($folio_other[$k]['total_amount']).' tại folio '.$folio_other[$k]['href'].' </p>';
                unset($folio_other[$k]);
            }
            $items[$k]['note'] = $note;
		}
        //System::debug($items);
        foreach($folio_other as $k=>$v){
            $items[$k]['id'] = $k;
            $items[$k]['type'] = $v['type'];
            $items[$k]['folio'] = $v['href'];
            $items[$k]['description'] = $v['description'];
            $items[$k]['room_name'] = Portal::language('room').':'.$v['room_name'];
            $items[$k]['total_before_tax'] = round($v['total_amount']/((1+$v['service_rate']/100)*(1+$v['tax_rate']/100)),2);
            $total_before_tax = $v['total_amount']/((1+$v['service_rate']/100)*(1+$v['tax_rate']/100));
            $items[$k]['total_amount'] = $v['total_amount'];
            $items[$k]['tax_amount'] = ($v['total_amount']!=0)?round($v['total_amount']/(100/100+$v['tax_rate']),2):0;
            $tax_amount = ($v['tax_rate']!=0)?$v['total_amount']/(100/100+$v['tax_rate']):0;
            $items[$k]['service_amount'] = $items[$k]['total_amount'] - $total_before_tax - $tax_amount;
            
            if($v['type']=='DISCOUNT' OR $v['type']=='DEPOSIT' OR $v['type']=='DEPOSIT_GROUP'){
                $this->map['total_before_tax'] -= $total_before_tax;
                $this->map['service_amount'] -= $items[$k]['service_amount'];
                $this->map['tax_amount'] -= $tax_amount;
                $this->map['total_amount'] -= $items[$k]['total_amount'];
            }else{
                $this->map['total_before_tax'] += $total_before_tax;
                $this->map['service_amount'] += $items[$k]['service_amount'];
                $this->map['tax_amount'] += $tax_amount;
                $this->map['total_amount'] += $items[$k]['total_amount'];
            }
            
            $items[$k]['link_invoice'] = '';
            $note = '';
            if(isset($folio_other[$k])){
                $note .= ' <p>- Đã tạo hóa đơn khoản tiền: '.System::display_number($v['total_amount']).' tại folio '.$v['href'].' </p>';
                $her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=reservation&cmd=edit&id='.$v['recode_invoice'];
                $note .= ' <p>- Thanh toán hộ phòng '.$v['room_name'].' Recode <a target="_blank" href="'.$her.'">#'.$v['recode_invoice'].'</a></p>';
                unset($folio_other[$k]);
            }
            $items[$k]['note'] = $note;
        }
        
        $this->map['payment'] = array();
        if($cond_payment!='')
        {
            $payment = DB::fetch_all('
                                    SELECT
                                        payment.id
                                        ,payment.amount * payment.exchange_rate as amount
                                        ,payment.time
                                        ,payment.description
                                        ,payment_type.name_'.Portal::language().' as payment_type_name
                                        ,payment.folio_id
                                        ,folio.customer_id
										,folio.reservation_traveller_id as traveller_id
                                        ,folio.code as folio_code
                                        ,party.full_name as user_name
                                    FROM
                                        payment
                                        inner join folio on folio.id=payment.folio_id
                                        inner join payment_type on payment_type.def_code=payment.payment_type_id
                                        left join party on party.user_id=payment.user_id
                                    WHERE
                                        1=1 '.$cond_payment.'
                                ');
          foreach($payment as $key=>$value){
                $payment[$key]['href_folio'] = '';
                if($value['customer_id'] == '') 
                {
    				 if(isset($value['folio_code']))
                        $payment[$key]['href_folio'] .= '<a target="_blank" href="'.'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&folio_id='.$value['folio_id'].'&traveller_id='.$value['traveller_id'].''.'">No.F'.str_pad($value['folio_code'],6,"0",STR_PAD_LEFT).'</a>';
                     else
                        $payment[$key]['href_folio'] .= '<a target="_blank" href="'.'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&folio_id='.$value['folio_id'].'&traveller_id='.$value['traveller_id'].''.'">Ref'.str_pad($value['folio_id'],6,"0",STR_PAD_LEFT).'</a>';                             
                }
                else
                {
                    if(isset($value['folio_code']))
                        $payment[$key]['href_folio'] .= '<a target="_blank" href="'.'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&cmd=group_invoice&folio_id='.$value['folio_id'].'&customer_id='.$value['customer_id'].''.'">No.F'.str_pad($value['folio_code'],6,"0",STR_PAD_LEFT).'</a>';
                    else
                        $payment[$key]['href_folio'] .= '<a target="_blank" href="'.'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&cmd=group_invoice&folio_id='.$value['folio_id'].'&customer_id='.$value['customer_id'].''.'">Ref_F'.str_pad($value['folio_id'],6,"0",STR_PAD_LEFT).'</a>';
                }
                $payment[$key]['time'] = date('H:i d/m/Y',$value['time']);
                $payment[$key]['amount'] = System::display_number($value['amount']);
          }
          $this->map['payment'] = $payment;
        }
        
        /** lay chi tiet cac dich vu trong tung phong **/
        
        $this->map['items'] = $items;
        $this->map['tax_amount'] = round($this->map['tax_amount'],2);
        $this->map['service_amount'] = round($this->map['service_amount'],2);
        $user_data = Session::get('user_data');
        $this->map['user_name'] = isset($user_data['full_name'])?$user_data['full_name']:Session::get('user_id');
        $this->parse_layout('invoice_information',$this->map);
    }
    
}
?>    