﻿<?php 
	class MasterFolioDB
    {
        static function get_total_room($id,$list_service)
        {
            $sql='
                select 
    				reservation_room.*,
                    NVL(reservation_room.package_sale_id,0) as package_sale_id,
    				traveller.first_name,
    				traveller.last_name,
    				traveller.nationality_id,
    				traveller.id as traveller_id,
    				reservation_type.show_price,
    				reservation_type.name as reservation_type_name,
    				room.name as room_name,
    				customer.address,
    				customer.name as customer_name,
    				customer.id as customer_id
    			from 
    				reservation_room 
    				inner join reservation ON reservation.id = reservation_room.reservation_id
    				left join room on room.id = reservation_room.room_id
    				left outer join customer on customer.id = reservation.customer_id
    				left outer join reservation_type on reservation_type.id = reservation_room.reservation_type_id
    				left outer join reservation_traveller on reservation_traveller.reservation_room_id = reservation_room.id
    				left outer join traveller on reservation_traveller.traveller_id = traveller.id
    			';
            
    		$row = DB::fetch($sql.' where reservation_room.id='.$id.'');
            
    		$fromtime=Url::get('arrival_time')?Date_Time::to_time(Url::get('arrival_time')):Date_Time::to_time(date('d/m/Y',$row['time_in']));
    		$totime=Url::get('departure_time')?Date_Time::to_time(Url::get('departure_time')):Date_Time::to_time(date('d/m/Y',$row['time_out']));
            
    		$items = array();
    	//----------------------------------------Tien phong`----------------------------------------------------
    			$day = array();
    			$n = 1;
    			$from = $fromtime;
    			$to = $totime;
    			$d=$from;
                if(isset($list_service['check_room_charge']))
                {
        			$sql = '
        				SELECT 
        					to_char(room_status.in_date,\'DD/MM\') as id
                            ,to_char(room_status.in_date,\'DD/MM/YYYY\') as full_date
        					,room_status.change_price
        					,room.name as room_name
        					,room_status.in_date
        					,room_status.id as room_status_id
        					,reservation_room.tax_rate
        					,reservation_room.service_rate
        					,reservation_room.id as rr_id
                            ,reservation_room.reservation_id as r_id
        					,TO_CHAR(room_status.in_date,\'DD/MM/YYYY\') as convert_date
        				FROM 
        					room_status
        					INNER JOIN reservation_room ON reservation_room.id = room_status.reservation_room_id
                            left join room on room.id=room_status.room_id
        				WHERE 
        					reservation_room_id=\''.$row['id'].'\'
                            AND room_status.change_price > 0
        				ORDER BY 
        					room_status.in_date ASC';
                            
        			$room_statuses = DB::fetch_all($sql);
                    $j = 0;
        			$holidays = DB::fetch_all('select id,name,charge,in_date from holiday');
        			$holiday = array();
        			foreach($holidays as $key=>$value)
                    {
        				$k = Date_Time::convert_orc_date_to_date($value['in_date'],'/');
        				$holiday[$k]['id'] = $k;
        				$holiday[$k]['name'] = $value['name'];
        				$holiday[$k]['charge'] = $value['charge'];
        			}
                    $count_package_room = 0;
                    if($row['package_sale_id']!=0)
                    {
                        $packages = DB::fetch_all("SELECT package_sale_detail.id,package_sale_detail.quantity FROM package_sale_detail inner join package_sale on package_sale_detail.package_sale_id=package_sale.id inner join package_service on package_sale_detail.service_id=package_service.id Where package_sale.id=".$row['package_sale_id']." AND package_service.code='ROOM'");
                        foreach($packages as $r) 
                        {
                            $count_package_room +=$r['quantity'];
                        }
                    }
                    $c = 1;
        			foreach($room_statuses as $k=>$v)
                    {
                        if($c>$count_package_room)
                        {
                            if($row['net_price']==1)
                            {
            					$param = (1+($row['tax_rate']*0.01) + ($row['service_rate']*0.01) + (($row['tax_rate']*0.01)*($row['service_rate']*0.01)));
            					$v['change_price'] = ($v['change_price']/$param);	
            				}
            				$tt = ($row['reduce_balance']?(100 - $row['reduce_balance'])*$v['change_price']/100:$v['change_price']);
            				if($row['reduce_balance']>0 && $row['reduce_balance']!='')
                            {
            					$room_statuses[$k]['note'] = '( Discounted '.$row['reduce_balance'].'%)';
            				}
                            else
                            {
            					$room_statuses[$k]['note'] = '';
            				}
            				$room_statuses[$k]['change_price'] = $tt;
            				$percent = 100;
                            $status = 0;
            				$amount = $room_statuses[$k]['change_price'];
                            $items['ROOM_'.$v['room_status_id']]['net_amount'] = System::display_number($amount);
                            $items['ROOM_'.$v['room_status_id']]['real_amount'] = $amount;
            				$items['ROOM_'.$v['room_status_id']]['id'] = $v['room_status_id'];
            				$items['ROOM_'.$v['room_status_id']]['type'] = 'ROOM';
            				$items['ROOM_'.$v['room_status_id']]['service_rate'] = $v['service_rate'];
            				$items['ROOM_'.$v['room_status_id']]['tax_rate'] = $v['tax_rate'];
            				$items['ROOM_'.$v['room_status_id']]['rr_id'] = $v['rr_id'];
            				$items['ROOM_'.$v['room_status_id']]['status'] = $status;
            				$items['ROOM_'.$v['room_status_id']]['date'] = $v['full_date'];
            				$items['ROOM_'.$v['room_status_id']]['percent'] = $percent;
            				$items['ROOM_'.$v['room_status_id']]['amount'] = ($amount);
            				$items['ROOM_'.$v['room_status_id']]['description'] =  $row['room_name'].' '.Portal::language('room_charge').' '.$room_statuses[$k]['note'];
            				$items['ROOM_'.$v['room_status_id']]['link'] = '?page=reservation&cmd=edit&id='.$v['r_id'].'&r_r_id='.$v['rr_id'];
                            $items['ROOM_'.$v['room_status_id']]['bill_number'] = '#Recode-'.$v['r_id'];
                            if(date('w',Date_Time::to_time($v['convert_date'])) == 5 and EXTRA_CHARGE_ON_SATURDAY > 0)
                            {
            					$items['ROOM_'.$v['room_status_id']]['description'] .= ' + '.System::display_number(EXTRA_CHARGE_ON_SATURDAY).' '.HOTEL_CURRENCY.' '.Portal::language('friday');
            				}
            				$day[$n]['saturday_charge'] = '';
            				if(date('w',Date_Time::to_time($v['convert_date'])) == 6 and EXTRA_CHARGE_ON_SUNDAY > 0)
                            {
            					$items['ROOM_'.$v['room_status_id']]['description'] .= ' + '.System::display_number(EXTRA_CHARGE_ON_SUNDAY).' '.HOTEL_CURRENCY.' '.Portal::language('saturday');
            				}
            				if(isset($holiday[date('d/m/Y',$d)]))
                            {
            					$items['ROOM_'.$v['room_status_id']]['description'] .= ' + '.System::display_number($holiday[date('d/m/Y',$d)]['charge']).' '.HOTEL_CURRENCY.' '.$holiday[date('d/m/Y',$d)]['name'];
            				}
            				if($row['foc'] !='')
                            {
            					$items['ROOM_'.$v['room_status_id']]['description'] .= '(FOC)';
            				}
                        }
                        $c++;
        			}
                }
                if(isset($list_service['check_ei_lo']) OR isset($list_service['check_extra_bed']) OR isset($list_service['check_breakfast']) OR isset($list_service['check_tranfer']) OR isset($list_service['check_other_service']) ) 
                {
                    $cond_service = '';
                    if(isset($list_service['check_ei_lo']))
                        $cond_service .= $cond_service==''?' (extra_service.code=\'EARLY_CHECKIN\' OR extra_service.code=\'LATE_CHECKOUT\')':' OR (extra_service.code=\'EARLY_CHECKIN\' OR extra_service.code=\'LATE_CHECKOUT\')';
                    if(isset($list_service['check_extra_bed']))
                        $cond_service .= $cond_service==''?' (extra_service.code=\'EXTRA_BED\')':' OR (extra_service.code=\'EXTRA_BED\')';
                    if(isset($list_service['check_breakfast']))
                        $cond_service .= $cond_service==''?' (extra_service.code=\'PTAS\' OR extra_service.code=\'KID BF\')':' OR (extra_service.code=\'PTAS\' OR extra_service.code=\'KID BF\')';
                    if(isset($list_service['check_tranfer']))
                        $cond_service .= $cond_service==''?' (SUBSTR(extra_service.code,1,7)= \'TRANFER\') ':' OR (SUBSTR(extra_service.code,1,7) = \'TRANFER\') ';
                    if(isset($list_service['check_other_service']))
                        $cond_service .= $cond_service==''?' (extra_service.code!=\'EARLY_CHECKIN\' and extra_service.code!=\'LATE_CHECKOUT\' and extra_service.code!=\'EXTRA_BED\' and extra_service.code!=\'PTAS\' and extra_service.code!=\'KID BF\' and SUBSTR(extra_service.code,1,7)!=\'TRANFER\')':'OR (extra_service.code!=\'EARLY_CHECKIN\' and extra_service.code!=\'LATE_CHECKOUT\' and extra_service.code!=\'EXTRA_BED\' and extra_service.code!=\'PTAS\' and extra_service.code!=\'KID BF\' and SUBSTR(extra_service.code,1,7)!=\'TRANFER\')';
                    
        			$row['extra_services'] = DB::fetch_all('
                                        				select 
                                        					extra_service_invoice_detail.*,
                                                            NVL(extra_service_invoice_detail.package_sale_detail_id,0) as package_sale_detail_id,
                                        					(extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0))*extra_service_invoice_detail.price as amount,
                                        					0 as service_charge_amount,
                                        					0 as tax_amount,
                                                            extra_service_invoice.net_price,
                                        					DECODE(extra_service_invoice.tax_rate,\'\',0,extra_service_invoice.tax_rate) as tax_rate,
                                        					DECODE(extra_service_invoice.service_rate,\'\',0,extra_service_invoice.service_rate) as service_rate,
                                        					to_char(extra_service_invoice_detail.in_date,\'DD/MM/YYYY\') as in_date,
                                                            extra_service_invoice.id as ex_id,
                                                            extra_service_invoice.bill_number as ex_bill,
                                                            extra_service.code as extra_service_code
                                        				from 
                                        					extra_service_invoice_detail
                                        					inner join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                                                            left join extra_service on extra_service.id=extra_service_invoice_detail.service_id
                                        				where 
                                        					extra_service_invoice.reservation_room_id='.$id.'
                                        					AND extra_service_invoice_detail.used = 1
                                                            AND (extra_service_invoice.mice_reservation_id is null OR extra_service_invoice.mice_reservation_id=\'\')
                                                            AND ('.$cond_service.')
                                        			');
        			if(!empty($row['extra_services']))
                    {
        				foreach($row['extra_services'] as $s_key=>$s_value)
                        {
                            if($s_value['package_sale_detail_id']==0)
                            {
                                $percent = 100;
                                $status = 0;
                                if($s_value['net_price'] == 1)
                                {
                                    $s_value['amount'] = ($s_value['amount']*100/($s_value['tax_rate'] +100))*100/($s_value['service_rate']+100);
                                }    
        						$amount = $s_value['amount'];
        						$items['EXTRA_SERVICE_'.$s_key]['net_amount'] = System::display_number($amount);
                                $items['EXTRA_SERVICE_'.$s_key]['real_amount'] = $amount;
        						$items['EXTRA_SERVICE_'.$s_key]['id'] = $s_key;
        						$items['EXTRA_SERVICE_'.$s_key]['type'] = 'EXTRA_SERVICE';
        						$items['EXTRA_SERVICE_'.$s_key]['service_rate'] = $s_value['service_rate'];
        						$items['EXTRA_SERVICE_'.$s_key]['tax_rate'] = $s_value['tax_rate'];
        						$items['EXTRA_SERVICE_'.$s_key]['rr_id'] = $row['id'];
        						$items['EXTRA_SERVICE_'.$s_key]['date'] = $s_value['in_date'];
        						$items['EXTRA_SERVICE_'.$s_key]['percent'] = $percent;
        						$items['EXTRA_SERVICE_'.$s_key]['status'] = $status;
                                $items['EXTRA_SERVICE_'.$s_key]['ex_id'] = $s_value['ex_id'];
                                $items['EXTRA_SERVICE_'.$s_key]['ex_bill'] = $s_value['ex_bill'];
                                $items['EXTRA_SERVICE_'.$s_key]['amount'] = ($amount);
        						$items['EXTRA_SERVICE_'.$s_key]['description'] = $row['room_name'].' '.$s_value['name'];
                                $items['EXTRA_SERVICE_'.$s_key]['link'] = '?page=extra_service_invoice&cmd=edit&id='.$s_value['ex_id'];
                                $items['EXTRA_SERVICE_'.$s_key]['bill_number'] = '#'.$s_value['ex_bill'];
                            }
                            else
                            {
                                if($s_value['net_price'] != 1)
                                {
                                    $s_value['amount'] = $s_value['amount'] + ($s_value['amount']*$s_value['service_rate']/100);
                                    $s_value['amount'] = $s_value['amount'] + ($s_value['amount']*$s_value['tax_rate']/100);
                                }
                                $package_amount = DB::fetch("SELECT (quantity*price) as amount from package_sale_detail Where id=".$s_value['package_sale_detail_id']."","amount");
                                if($s_value['amount']>=$package_amount)
                                {
                                    $s_value['amount'] = $s_value['amount'] - $package_amount;
                                    $s_value['amount'] = ($s_value['amount']*100/($s_value['tax_rate'] +100))*100/($s_value['service_rate']+100);
                                    $percent = 100;$status = 0;
                                    $amount = $s_value['amount'];
            						$items['EXTRA_SERVICE_'.$s_key]['net_amount'] = System::display_number($amount);
            						$items['EXTRA_SERVICE_'.$s_key]['id'] = $s_key;
            						$items['EXTRA_SERVICE_'.$s_key]['type'] = 'EXTRA_SERVICE';
            						$items['EXTRA_SERVICE_'.$s_key]['service_rate'] = $s_value['service_rate'];
            						$items['EXTRA_SERVICE_'.$s_key]['tax_rate'] = $s_value['tax_rate'];
            						$items['EXTRA_SERVICE_'.$s_key]['rr_id'] = $row['id'];
            						$items['EXTRA_SERVICE_'.$s_key]['date'] = $s_value['in_date'];
            						$items['EXTRA_SERVICE_'.$s_key]['percent'] = $percent;
            						$items['EXTRA_SERVICE_'.$s_key]['status'] = $status;
                                    $items['EXTRA_SERVICE_'.$s_key]['ex_id'] = $s_value['ex_id'];
                                    $items['EXTRA_SERVICE_'.$s_key]['ex_bill'] = $s_value['ex_bill'];
                                    $items['EXTRA_SERVICE_'.$s_key]['amount'] = ($amount);
            						$items['EXTRA_SERVICE_'.$s_key]['description'] = $row['room_name'].' '.$s_value['name'];
                                    $items['EXTRA_SERVICE_'.$s_key]['link'] = '?page=extra_service_invoice&cmd=edit&id='.$s_value['ex_id'];
                                    $items['EXTRA_SERVICE_'.$s_key]['bill_number'] = '#'.$s_value['ex_bill'];
                                }
                            }
                        }
        			}
                }
                
    			$sql_l='
    				SELECT 
    					housekeeping_invoice.*
    				FROM 
    					housekeeping_invoice
    				WHERE 
    					housekeeping_invoice.reservation_room_id='.$id.' 
    					AND housekeeping_invoice.minibar_id=\''.$row['room_id'].'\'
    					AND housekeeping_invoice.type=\'LAUNDRY\'
    			';
    			$sql_m='
    				SELECT 
    					housekeeping_invoice.*
    				FROM 
    					housekeeping_invoice
    					inner join minibar on housekeeping_invoice.minibar_id = minibar.id
    				WHERE 
    					housekeeping_invoice.reservation_room_id='.$id.' AND
    					minibar.room_id=\''.$row['room_id'].'\' AND
    					type=\'MINIBAR\' 
    			';
    			$sql_compensated_item='
    				SELECT 
    					housekeeping_invoice.*
    				FROM 
    					housekeeping_invoice
    				WHERE
    					housekeeping_invoice.reservation_room_id='.$id.' AND
    					housekeeping_invoice.minibar_id=\''.$row['room_id'].'\' AND
    					housekeeping_invoice.type=\'EQUIP\' 
    			'; 
    		//-----------------------------------------minibar------------------------------------------------------------
    		    $package_amount_minibar = 0;
                $package_amount_laundry = 0;
                if($row['package_sale_id']!=0)
                {
                    $sql_package = "SELECT 
                                        (package_sale_detail.quantity*package_sale_detail.price) as amount 
                                    FROM 
                                        package_sale_detail 
                                        inner join package_service on package_service.id=package_sale_detail.service_id
                                        inner join package_sale on package_sale.id=package_sale_detail.package_sale_id
                                    WHERE
                                        package_sale.id=".$row['package_sale_id'];
                    $package_amount_minibar = DB::fetch($sql_package." AND package_service.code='MINIBAR'","amount");
                    $package_amount_laundry = DB::fetch($sql_package." AND package_service.code='LAUNDRY'","amount");
                }
                if(isset($list_service['check_minibar']))
                {
        			if($minibars = DB::fetch_all($sql_m))
                    {
        				foreach($minibars as $k=>$minibar)
                        {				
        					$minibar_details = DB::fetch_all('
                                    						SELECT 
                                    							HK_I_D.id,
                                                                HK_I_D.price,
                                                                HK_I_D.quantity,
                                    							product.name_1 as name,
                                                                unit.name_1 AS unit_name,
                                    							(HK_I_D.price * HK_I_D.quantity) AS amount
                                    						FROM 
                                    							housekeeping_invoice_detail HK_I_D 
                                    							INNER JOIN product ON product.id = HK_I_D.product_id
                                    							LEFT OUTER JOIN unit ON unit.id = product.unit_id
                                    						WHERE 
                                    							HK_I_D.invoice_id = '.$k.'
                                    						ORDER BY 
                                    							product.name_1');
                            if($package_amount_minibar<=0)
                            {
        						$percent = 100;$status = 0;
        						$amount = $minibar['total_before_tax'];
        						$items['MINIBAR_'.$k]['net_amount'] = System::display_number($amount);
                                $items['MINIBAR_'.$k]['real_amount'] = $amount;
        						$items['MINIBAR_'.$k]['id'] = $k;
        						$items['MINIBAR_'.$k]['type'] = 'MINIBAR';
        						$items['MINIBAR_'.$k]['date'] = date('d/m/Y',$minibar['time']);
        						$items['MINIBAR_'.$k]['service_rate'] = $minibar['fee_rate'];
        						$items['MINIBAR_'.$k]['tax_rate'] = $minibar['tax_rate'];
                                $items['MINIBAR_'.$k]['position'] = $minibar['position'];
        						$items['MINIBAR_'.$k]['percent'] = $percent;
        						$items['MINIBAR_'.$k]['status'] = $status;
        						$items['MINIBAR_'.$k]['rr_id'] = $id;
        						$items['MINIBAR_'.$k]['amount'] = ($amount);
        						$items['MINIBAR_'.$k]['description'] =  $row['room_name'].' '.Portal::language('minibar');
                                $items['MINIBAR_'.$k]['link'] = '?page=minibar_invoice&cmd=edit&id='.$k;
                                $items['MINIBAR_'.$k]['bill_number'] = '#MN-'.$minibar['position'];
                            }
                            else
                            {
                                if($package_amount_minibar>=$minibar['total'])
                                {
                                    $package_amount_minibar -= $minibar['total'];
                                }
                                else
                                {
                                    
                                    $percent = 100;$status = 0;
                                    $minibar['total'] -= $package_amount_minibar;
                                    $package_amount_minibar = 0;
                                    $minibar['total_before_tax'] =  ($minibar['total']*100/($minibar['tax_rate'] +100))*100/($minibar['fee_rate']+100);
                                    $amount = $minibar['total_before_tax'];
                                    $items['MINIBAR_'.$k]['net_amount'] = System::display_number($amount);
                                    $items['MINIBAR_'.$k]['real_amount'] = $amount;
        							$items['MINIBAR_'.$k]['id'] = $k;
        							$items['MINIBAR_'.$k]['type'] = 'MINIBAR';
        							$items['MINIBAR_'.$k]['date'] = date('d/m/Y',$minibar['time']);
        							$items['MINIBAR_'.$k]['service_rate'] = $minibar['fee_rate'];
        							$items['MINIBAR_'.$k]['tax_rate'] = $minibar['tax_rate'];
                                    $items['MINIBAR_'.$k]['position'] = $minibar['position'];
        							$items['MINIBAR_'.$k]['percent'] = $percent;
        							$items['MINIBAR_'.$k]['status'] = $status;
        							$items['MINIBAR_'.$k]['rr_id'] = $id;
        							$items['MINIBAR_'.$k]['amount'] = ($amount);
        							$items['MINIBAR_'.$k]['description'] =  $row['room_name'].' '.Portal::language('minibar');
                                    $items['MINIBAR_'.$k]['link'] = '?page=minibar_invoice&cmd=edit&id='.$k;
                                    $items['MINIBAR_'.$k]['bill_number'] = '#MN-'.$minibar['position'];
                                }
                            }
        				}
        			}
                }
    		//--------------------------------------------laundry--------------------------------------------------------
                if(isset($list_service['check_laundry']))
                {
        			DB::query($sql_l);
        			if($laundrys = DB::fetch_all())
                    {
        				foreach($laundrys as $k=>$laundry)
                        {	
        					$laundry_details = DB::fetch_all('
                                    						SELECT 
                                    							HK_I_D.id,HK_I_D.price,HK_I_D.quantity,
                                    							product.name_1 as name,unit.name_1 AS unit_name,
                                    							(HK_I_D.price * HK_I_D.quantity) AS amount
                                    						FROM 
                                    							housekeeping_invoice_detail HK_I_D 
                                    							INNER JOIN product ON product.id = HK_I_D.product_id
                                    							LEFT OUTER JOIN unit ON unit.id = product.unit_id
                                    						WHERE 
                                    							HK_I_D.invoice_id = '.$k.'
                                    						ORDER BY 
                                    							product.name_1');
        					if($package_amount_laundry<=0)
                            {
                                $percent = 100;$status = 0;
        						$amount = $laundry['total_before_tax'];
        						$items['LAUNDRY_'.$k]['net_amount'] = System::display_number($amount);
                                $items['LAUNDRY_'.$k]['real_amount'] = $amount;
        						$items['LAUNDRY_'.$k]['id'] = $k;
        						$items['LAUNDRY_'.$k]['type'] = 'LAUNDRY';
        						$items['LAUNDRY_'.$k]['date'] = date('d/m/Y',$laundry['time']);
        						$items['LAUNDRY_'.$k]['service_rate'] = $laundry['fee_rate'];
        						$items['LAUNDRY_'.$k]['tax_rate'] = $laundry['tax_rate'];
                                $items['LAUNDRY_'.$k]['position'] = $laundry['position'];
        						$items['LAUNDRY_'.$k]['percent'] = $percent;
        						$items['LAUNDRY_'.$k]['status'] = $status;
        						$items['LAUNDRY_'.$k]['rr_id'] = $id;
        						$items['LAUNDRY_'.$k]['amount'] = ($amount);
        						$items['LAUNDRY_'.$k]['description'] = $row['room_name'].' '.Portal::language('laundry');
                                $items['LAUNDRY_'.$k]['link'] = '?page=laundry_invoice&cmd=edit&id='.$k;
                                $items['LAUNDRY_'.$k]['bill_number'] = '#LD-'.$laundry['position'];
                            }
                            else
                            {
                                if($package_amount_laundry>=$laundry['total'])
                                {
                                    $package_amount_laundry -= $laundry['total'];
                                }
                                else
                                {
                                    $percent = 100;$status = 0;
                                    $laundry['total'] -= $package_amount_laundry;
                                    $package_amount_laundry = 0;
                                    $laundry['total_before_tax'] =  ($laundry['total']*100/($laundry['tax_rate'] +100))*100/($laundry['fee_rate']+100);
                                    $amount = $laundry['total_before_tax'];
                                    $items['LAUNDRY_'.$k]['net_amount'] = System::display_number($amount);
                                    $items['LAUNDRY_'.$k]['real_amount'] = $amount;
            						$items['LAUNDRY_'.$k]['id'] = $k;
            						$items['LAUNDRY_'.$k]['type'] = 'LAUNDRY';
            						$items['LAUNDRY_'.$k]['date'] = date('d/m/Y',$laundry['time']);
            						$items['LAUNDRY_'.$k]['service_rate'] = $laundry['fee_rate'];
            						$items['LAUNDRY_'.$k]['tax_rate'] = $laundry['tax_rate'];
                                    $items['LAUNDRY_'.$k]['position'] = $laundry['position'];
            						$items['LAUNDRY_'.$k]['percent'] = $percent;
            						$items['LAUNDRY_'.$k]['status'] = $status;
            						$items['LAUNDRY_'.$k]['rr_id'] = $id;
            						$items['LAUNDRY_'.$k]['amount'] = ($amount);
            						$items['LAUNDRY_'.$k]['description'] = $row['room_name'].' '.Portal::language('laundry');
                                    $items['LAUNDRY_'.$k]['link'] = '?page=laundry_invoice&cmd=edit&id='.$k;
                                    $items['LAUNDRY_'.$k]['bill_number'] = '#LD-'.$laundry['position'];
                                    
                                }
                            }
        				}
        			}
                }
    	   //--------------------------------------------/laundry--------------------------------------------------------
                if(isset($list_service['check_equipment']))
                {
        			DB::query($sql_compensated_item);
        			if($compensated_items = DB::fetch_all($sql_compensated_item))
                    {
        				foreach($compensated_items as $k=>$compensated_item)
                        {
        					$item_details = DB::fetch_all('
                                						SELECT 
                                							HK_I_D.id,HK_I_D.price,HK_I_D.quantity,
                                							product.name_1 as name,unit.name_1 AS unit_name,
                                							(HK_I_D.price * HK_I_D.quantity) AS amount 
                                						FROM 
                                							housekeeping_invoice_detail HK_I_D 
                                							INNER JOIN product ON product.id = HK_I_D.product_id
                                							LEFT OUTER JOIN unit ON unit.id = product.unit_id
                                						WHERE 
                                							HK_I_D.invoice_id = '.$k.'
                                						ORDER BY 
                                							product.name_1');
        					$percent = 100;$status = 0;
        					$amount = $compensated_item['total_before_tax'];
        					$items['EQUIPMENT_'.$k]['net_amount'] = System::display_number($amount);
                            $items['EQUIPMENT_'.$k]['real_amount'] = $amount;
        					$items['EQUIPMENT_'.$k]['id'] = $k;
        					$items['EQUIPMENT_'.$k]['type'] = 'EQUIPMENT';
        					$items['EQUIPMENT_'.$k]['service_rate'] = $compensated_item['fee_rate'];
        					$items['EQUIPMENT_'.$k]['tax_rate'] = $compensated_item['tax_rate'];
        					$items['EQUIPMENT_'.$k]['percent'] = $percent;
        					$items['EQUIPMENT_'.$k]['status'] = $status;
        					$items['EQUIPMENT_'.$k]['rr_id'] = $id;
        					$items['EQUIPMENT_'.$k]['date'] = date('d/m/Y',$compensated_item['time']);
        					$items['EQUIPMENT_'.$k]['amount'] = ($amount);
        					$items['EQUIPMENT_'.$k]['description'] = $row['room_name'].' '.Portal::language('equipment');
                            $items['EQUIPMENT_'.$k]['link'] = '?page=equipment_invoice&cmd=edit&id='.$k;
                            $items['EQUIPMENT_'.$k]['bill_number'] = '#'.$k;
        				}
        			}
                }
            //-----------------------ticket--------------------------------------------------
                if(isset($list_service['check_other_service']))
                {
                    $sql = '
        				select 
        					ticket_reservation.id,
                            ticket_reservation.amount_pay_with_room as total,
                            ticket_reservation.time,
                            ticket_reservation.total_before_tax,
        					(\''.Portal::language('ticket').'\' || \'_\' || ticket_reservation.id) AS ticket_name ,
        					ticket_reservation.deposit as ticket_deposit
        				from 
        					ticket_reservation
        				where 
        					reservation_room_id=\''.$id.'\'
        			     ';
        			if($ticket_resrs = DB::fetch_all($sql))
                    {
        				foreach($ticket_resrs as $bk=>$reser)
                        {
        					$percent = 100;
                            $status = 0;
        					$amount = $reser['total']/1.1;
        					$items['TICKET_'.$bk]['net_amount'] = System::display_number($amount);
                            $items['TICKET_'.$bk]['real_amount'] = $amount;
        					$items['TICKET_'.$bk]['id'] = $bk;
        					$items['TICKET_'.$bk]['type'] = 'TICKET';
        					$items['TICKET_'.$bk]['date'] = date('d/m/Y',$reser['time']);
        					$items['TICKET_'.$bk]['service_rate'] = 0;
        					$items['TICKET_'.$bk]['tax_rate'] = 10;
        					$items['TICKET_'.$bk]['percent'] = $percent;
        					$items['TICKET_'.$bk]['status'] = $status;
        					$items['TICKET_'.$bk]['rr_id'] = $id;
        					$items['TICKET_'.$bk]['amount'] = ($amount);
        					$items['TICKET_'.$bk]['description'] = $row['room_name'].' TICKET';
                            $items['TICKET_'.$bk]['link'] = '?page=ticket_invoice_group&cmd=edit&id='.$bk;
                            $items['TICKET_'.$bk]['bill_number'] = '#Ticket-'.$bk;
        					$row['deposit'] += $reser['ticket_deposit'];
        				}
        			}
            //-----------------------package--------------------------------------------------
                    $sql = '
        				select 
        					package_sale.id,
                            package_sale.total_amount,
                            package_sale.create_time as time,
                            room.name as room_name
        				from 
        					reservation_room 
                            inner join package_sale on reservation_room.package_sale_id=package_sale.id
                            left join room on room.id=reservation_room.room_id
        				where 
        					reservation_room.id=\''.$row['id'].'\'
        			     ';
        			if($package = DB::fetch_all($sql))
                    {
        				foreach($package as $bk=>$reser)
                        {
        					$percent = 100;
                            $status = 0;
        					$amount = $reser['total_amount'];
        					$items['PACKAGE_'.$bk]['net_amount'] = System::display_number($amount);
                            $items['PACKAGE_'.$bk]['real_amount'] = $amount;
        					$items['PACKAGE_'.$bk]['id'] = $bk;
        					$items['PACKAGE_'.$bk]['type'] = 'PACKAGE';
        					$items['PACKAGE_'.$bk]['date'] = date('d/m/Y',$reser['time']);
        					$items['PACKAGE_'.$bk]['service_rate'] = 0;
        					$items['PACKAGE_'.$bk]['tax_rate'] = 0;
        					$items['PACKAGE_'.$bk]['percent'] = $percent;
        					$items['PACKAGE_'.$bk]['status'] = $status;
        					$items['PACKAGE_'.$bk]['rr_id'] = $id;
        					$items['PACKAGE_'.$bk]['amount'] = ($amount);
        					$items['PACKAGE_'.$bk]['description'] = $reser['room_name'].' Package';
                            $items['PACKAGE_'.$bk]['link'] = '?page=package_sale&cmd=edit&id='.$bk;
                            $items['PACKAGE_'.$bk]['bill_number'] = '#Package-'.$bk;
        					$row['deposit'] += 0;
        				}
        			}		
        		//---------------------------------VEND-----------------------------------------
                    $sql = '
        				select 
        					ve_reservation.id,
                            ve_reservation.amount_pay_with_room as total,
                            ve_reservation.time,
                            ve_reservation.total_before_tax,
                            ve_reservation.bar_fee_rate,
                            ve_reservation.tax_rate,
        					(\''.Portal::language('vend').'\' || \'_\' || ve_reservation.id) AS ve_name ,
        					ve_reservation.deposit as ve_deposit,
                            ve_reservation.department_code,
                            ve_reservation.department_id,
                            ve_reservation.code
        				from 
        					ve_reservation
        				where 
        					ve_reservation.reservation_room_id=\''.$id.'\' 
                            and (ve_reservation.mice_reservation_id is null OR ve_reservation.mice_reservation_id=\'\')
                            ';
                    if($ve_resrs = DB::fetch_all($sql))
                    {
        				foreach($ve_resrs as $bk=>$reser)
                        {
        					$percent = 100;
                            $status = 0;
        					$amount = $reser['total']/((1+$reser['bar_fee_rate']/100)*(1+$reser['tax_rate']/100));
        					$items['VE_'.$bk]['net_amount'] = System::display_number($amount);
                            $items['VE_'.$bk]['real_amount'] = $amount;
        					$items['VE_'.$bk]['id'] = $bk;
        					$items['VE_'.$bk]['type'] = 'VE';
        					$items['VE_'.$bk]['date'] = date('d/m/Y',$reser['time']);
        					$items['VE_'.$bk]['service_rate'] = $reser['bar_fee_rate'];
        					$items['VE_'.$bk]['tax_rate'] = $reser['tax_rate'];
        					$items['VE_'.$bk]['percent'] = $percent;
        					$items['VE_'.$bk]['status'] = $status;
        					$items['VE_'.$bk]['rr_id'] = $id;
        					$items['VE_'.$bk]['amount'] = ($amount);
        					$items['VE_'.$bk]['description'] = $row['room_name'].' VE';
                            $items['VE_'.$bk]['link'] = '?page=automatic_vend&cmd=edit&id='.$bk.'&department_id='.$reser['department_id'].'&department_code='.$reser['department_code'].'';
        					$items['VE_'.$bk]['bill_number'] = '#'.$reser['code'];
                            $row['deposit'] += $reser['ve_deposit'];
        				}
        			}
                }
            //---------------------------------/VEND----------------------------------------
                if(isset($list_service['check_bar']))
                {
                    $sql = '
        				select 
        					bar_reservation.id, 
                            bar_reservation.payment_result,
                            bar_reservation.code, 
        					bar_reservation.departure_time as time_out, 
                            bar_reservation.prepaid,
        					bar_reservation.amount_pay_with_room, 
                            bar_reservation.tax_rate,
                            bar_reservation.bar_fee_rate,
        					(bar.name || \'_\' || bar_reservation.code) AS bar_name ,
        					bar_reservation.deposit as bar_deposit,
                            bar_reservation_table.table_id,
                            bar.area_id as bar_area_id,
                            bar.id as bar_id
        				from 
        					bar_reservation 
        					inner join bar ON bar_reservation.bar_id = bar.id 
                            inner join bar_reservation_table on bar_reservation.id=bar_reservation_table.bar_reservation_id
        				where 
        					reservation_room_id=\''.$id.'\' 
        					 and (bar_reservation.status=\'CHECKOUT\' or bar_reservation.status=\'CHECKIN\')
                             and (bar_reservation.mice_reservation_id is null OR bar_reservation.mice_reservation_id=\'\')
        			     ';
        			if($bar_resrs = DB::fetch_all($sql))
                    {
        				foreach($bar_resrs as $bk=>$reser)
                        {
                            $payment_bars = DB::fetch('
                                                        SELECT 
                                                            sum(payment.amount) as total 
                                                        FROM 
                                                            payment 
                                                        WHERE 
                                                            payment.bill_id=\''.$reser['id'].'\' 
                                                            AND payment.type=\'BAR\'','total');
                            
        					$percent = 100;
                            $status = 0;
        					$amount = ($reser['amount_pay_with_room']-$payment_bars)/(1 + $reser['bar_fee_rate']/100)/(1 + $reser['tax_rate']/100);
                            $items['BAR_'.$bk]['net_amount'] = System::display_number($amount);
                            $items['BAR_'.$bk]['real_amount'] = $amount;
        					$items['BAR_'.$bk]['id'] = $bk;
        					$items['BAR_'.$bk]['type'] = 'BAR';
        					$items['BAR_'.$bk]['date'] = date('d/m/Y',$reser['time_out']);
        					$items['BAR_'.$bk]['service_rate'] = $reser['bar_fee_rate'];
        					$items['BAR_'.$bk]['tax_rate'] = $reser['tax_rate'];
        					$items['BAR_'.$bk]['percent'] = $percent;
                            $items['BAR_'.$bk]['code'] = $reser['code'];
        					$items['BAR_'.$bk]['status'] = $status;
        					$items['BAR_'.$bk]['rr_id'] = $id;
        					$items['BAR_'.$bk]['amount'] = ($amount);
        					$items['BAR_'.$bk]['description'] = $row['room_name'].' '.$reser['bar_name'];
                            $items['BAR_'.$bk]['link'] = '?page=touch_bar_restaurant&cmd=edit&id='.$bk.'&table_id='.$reser['table_id'].'&bar_area_id='.$reser['bar_area_id'].'&bar_id='.$reser['bar_id'];
                            $items['BAR_'.$bk]['bill_number'] = '#'.$reser['code'];
        				}
        			}
                }
                if(isset($list_service['check_other_service']))
                {
            //----------------------------------------------------KARAOKE-------------------------------------------------------			
        			$sql = '
        				select 
        					karaoke_reservation.id, 
                            karaoke_reservation.payment_result, 
        					karaoke_reservation.time_out, 
                            karaoke_reservation.total, 
                            karaoke_reservation.prepaid,
        					karaoke_reservation.total_before_tax, 
                            karaoke_reservation.total_before_tax, 
                            karaoke_reservation.tax_rate,
                            karaoke_reservation.karaoke_fee_rate,
        					(\''.Portal::language('karaoke').'\' || \'_\' || karaoke_reservation.id) AS karaoke_name,
        					karaoke_reservation.deposit as karaoke_deposit
                            ,karaoke_reservation.amount_pay_with_room
                            ,karaoke_reservation_table.table_id
                            ,karaoke_reservation.karaoke_id
                            ,karaoke_reservation.code
        				from 
        					karaoke_reservation 
        					inner join karaoke ON karaoke_reservation.karaoke_id = karaoke.id 
                            inner join karaoke_reservation_table ON karaoke_reservation_table.karaoke_reservation_id = karaoke_reservation.id 
        				where 
        					reservation_room_id=\''.$id.'\' 
        					 and (karaoke_reservation.status=\'CHECKOUT\' or karaoke_reservation.status=\'CHECKIN\')
        			     ';
        			if($karaoke_resrs = DB::fetch_all($sql))
                    {
        				foreach($karaoke_resrs as $bk=>$reser)
                        {
        					$percent = 100;$status = 0;
        					$amount = $reser['amount_pay_with_room']/(1 + $reser['karaoke_fee_rate']/100)/(1 + $reser['tax_rate']/100);
        					$items['KARAOKE_'.$bk]['net_amount'] = System::display_number($amount);
                            $items['KARAOKE_'.$bk]['real_amount'] = $amount;
                            $sing_room = DB::fetch_all("select 
                                                            karaoke_reservation_table.*, 
                                                            karaoke_table.name
                                                        from 
                                                            karaoke_reservation_table 
                                                            inner join karaoke_table on karaoke_table.id = karaoke_reservation_table.table_id                                
                                                        where 
                                                            karaoke_reservation_id = ".$reser['id']);
                            foreach($sing_room as $id1=>$content1)
                            {
                                if($sing_room[$id1]['sing_start_time']!='' AND $sing_room[$id1]['sing_end_time']!='')
                                {    
                                    $amount += ($content1['price']/3600)*($sing_room[$id1]['sing_end_time']-$sing_room[$id1]['sing_start_time']);
                                }
                            }
        					$items['KARAOKE_'.$bk]['id'] = $bk;
        					$items['KARAOKE_'.$bk]['type'] = 'KARAOKE';
        					$items['KARAOKE_'.$bk]['date'] = date('d/m/Y',$reser['time_out']);
        					$items['KARAOKE_'.$bk]['service_rate'] = $reser['karaoke_fee_rate'];
        					$items['KARAOKE_'.$bk]['tax_rate'] = $reser['tax_rate'];
        					$items['KARAOKE_'.$bk]['percent'] = $percent;
        					$items['KARAOKE_'.$bk]['status'] = $status;
        					$items['KARAOKE_'.$bk]['rr_id'] = $id;
        					$items['KARAOKE_'.$bk]['amount'] = ($amount);
        					$items['KARAOKE_'.$bk]['description'] = $row['room_name'].' '.$reser['karaoke_name'];
                            $items['KARAOKE_'.$bk]['link'] = '?page=karaoke_touch&cmd=edit&id='.$bk.'&table_id='.$reser['table_id'].'&karaoke_id='.$reser['karaoke_id'].'';
                            $items['KARAOKE_'.$bk]['bill_number'] = '#'.$reser['code'];
        				}
        			}
                    
        	   //-------------------------------------------------Other services----------------------------------------
        			$all_services = DB::fetch_all('
                                    				select 
                                    					service_id as id,
                                                        reservation_room_service.amount,
                                                        reservation_room_id,
                                                        service.name,service.type,
                                    					0 as service_charge_amount, 
                                                        0 as tax_amount,
                                                        reservation_room_service.id as room_service_id
                                    				from 
                                    					reservation_room_service 
                                    					inner join service on service.id = service_id 
                                    				where 
                                    					reservation_room_id= '.$id.'
                                    			');
        			$row['services'] = $all_services;
        			foreach($row['services'] as $s_key=>$s_value)
                    {
        				if($s_value['type']=='SERVICE')
                        {
        					$percent = 100;$status = 0;
        					$amount = $s_value['amount'];
        					$items['SERVICE_'.$s_key]['net_amount'] = System::display_number($amount);
                            $items['SERVICE_'.$s_key]['real_amount'] = $amount;
        					$items['SERVICE_'.$s_key]['id'] = $s_key;
        					$items['SERVICE_'.$s_key]['type'] = 'SERVICE';
        					$items['SERVICE_'.$s_key]['date'] = '';
        					$items['SERVICE_'.$s_key]['service_rate'] = 0;
        					$items['SERVICE_'.$s_key]['tax_rate'] = 0;
        					$items['SERVICE_'.$s_key]['percent'] = $percent;
        					$items['SERVICE_'.$s_key]['status'] = $status;
        					$items['SERVICE_'.$s_key]['rr_id'] = $id;
        					$items['SERVICE_'.$s_key]['amount'] = ($amount);
        					$items['SERVICE_'.$s_key]['description'] = $row['room_name'].' '.Portal::language('service');
                            $items['SERVICE_'.$s_key]['link'] = '#';
                            $items['SERVICE_'.$s_key]['bill_number'] = '';
        				}	
        			}
        			foreach($row['services'] as $s_key=>$s_value)
                    {
        				if($s_value['type']=='ROOM')
                        {
        					$percent = 100;$status = 0;
        					$amount = $s_value['amount'];
        					$items['ROOM_SERVICE_'.$s_key]['net_amount'] = System::display_number($amount);
                            $items['ROOM_SERVICE_'.$s_key]['real_amount'] = $amount;
        					$items['ROOM_SERVICE_'.$s_key]['id'] = $s_key;
        					$items['ROOM_SERVICE_'.$s_key]['type'] = 'ROOM_SERVICE';
        					$items['ROOM_SERVICE_'.$s_key]['date'] = '';
        					$items['ROOM_SERVICE_'.$s_key]['service_rate'] = 0;
        					$items['ROOM_SERVICE_'.$s_key]['tax_rate'] = 0;
        					$items['ROOM_SERVICE_'.$s_key]['rr_id'] = $id;
        					$items['ROOM_SERVICE_'.$s_key]['percent'] = $percent;
        					$items['ROOM_SERVICE_'.$s_key]['status'] = $status;
        					$items['ROOM_SERVICE_'.$s_key]['amount'] = ($amount);
        					$items['ROOM_SERVICE_'.$s_key]['description'] = $row['room_name'].' '.Portal::language('room_service');
                            $items['ROOM_SERVICE_'.$s_key]['link'] = '#';
                            $items['ROOM_SERVICE_'.$s_key]['bill_number'] = '';
        				}	
        			}
        	//----------------------------------------/Other services------------------------------------------------		
        			$sql_massage='
                				SELECT 
                					massage_reservation_room.id,
                                    massage_product_consumed.room_id, 
                                    massage_reservation_room.hotel_reservation_room_id,
                                    massage_reservation_room.amount_pay_with_room as total_amount,
                                    massage_reservation_room.time,
                                    massage_reservation_room.net_price as net_price,
                                    DECODE(massage_reservation_room.tax,\'\',0,massage_reservation_room.tax) as tax_rate,
                                    DECODE(massage_reservation_room.service_rate,\'\',0,massage_reservation_room.service_rate) as service_rate
                				FROM 
                					massage_reservation_room
                                    inner join massage_product_consumed on massage_product_consumed.reservation_room_id = massage_reservation_room.id
                				WHERE
                					massage_reservation_room.hotel_reservation_room_id='.$id.'
                				
                			';
        			if($row['total_massage_amount'] = DB::fetch_all($sql_massage))
                    {
        			    foreach($row['total_massage_amount'] as $mg=>$mgr)
                        {
        						$percent = 100;$status = 0;
                                $mgr['total_amount'] = ($mgr['total_amount']*100/($mgr['tax_rate'] +100))*100/($mgr['service_rate']+100);
                                $amount = $mgr['total_amount'];
            					$items['MASSAGE_'.$mg]['net_amount'] = System::display_number($amount);
                                $items['MASSAGE_'.$mg]['real_amount'] = $amount;
        						$items['MASSAGE_'.$mg]['id'] = $mg;
            					$items['MASSAGE_'.$mg]['type'] = 'MASSAGE';
            					$items['MASSAGE_'.$mg]['service_rate'] = $mgr['service_rate'];
                                $items['MASSAGE_'.$mg]['room_id'] = $mg['room_id'];
            					$items['MASSAGE_'.$mg]['tax_rate'] = $mgr['tax_rate'];
            					$items['MASSAGE_'.$mg]['date'] = date('d/m/Y',$mgr['time']);
            					$items['MASSAGE_'.$mg]['rr_id'] = $id;
            					$items['MASSAGE_'.$mg]['percent'] = $percent;
            					$items['MASSAGE_'.$mg]['status'] = $status;
            					$items['MASSAGE_'.$mg]['amount'] = ($amount);
            					$items['MASSAGE_'.$mg]['description'] = $row['room_name'].' '.Portal::language('massage').'_'.$mg;
                                $items['MASSAGE_'.$mg]['link'] = '?page=massage_daily_summary&cmd=edit&id='.$mg;
                                $items['MASSAGE_'.$mg]['bill_number'] = '#SPA-'.$mg;
        			    }	
        			}
        			if(URL::get('tennis_invoice'))
                    {
        				$sql_tennis='
                					SELECT 
                						tennis_reservation_court.hotel_reservation_room_id,sum(tennis_reservation_court.total_amount) as total_amount
                					FROM 
                						tennis_reservation_court
                					WHERE
                						tennis_reservation_court.hotel_reservation_room_id='.$id.'
                					GROUP BY
                						tennis_reservation_court.hotel_reservation_room_id
                				';
        				if($row['total_tennis_amount'] = DB::fetch($sql_tennis,'total_amount') and HAVE_TENNIS)
                        {
        					$percent = 100;$status = 0;
        					$amount =$row['total_tennis_amount'];
        					$items['TENNIS_'.$id]['net_amount'] = System::display_number($amount);
                            $items['TENNIS_'.$id]['real_amount'] = $amount;
        					$items['TENNIS_'.$id]['id'] = $id;
        					$items['TENNIS_'.$id]['type'] = 'TENNIS';
        					$items['TENNIS_'.$id]['service_rate'] = 0;
        					$items['TENNIS_'.$id]['tax_rate'] = 0;
        					$items['TENNIS_'.$id]['date'] = '';
        					$items['TENNIS_'.$id]['rr_id'] = $id;
        					$items['TENNIS_'.$id]['percent'] = $percent;
        					$items['TENNIS_'.$id]['status'] = $status;
        					$items['TENNIS_'.$id]['amount'] = ($amount);
        					$items['TENNIS_'.$id]['description'] = $row['room_name'].' '.Portal::language('tennis');
                            $items['TENNIS_'.$id]['link'] = '#';
                            $items['TENNIS_'.$id]['bill_number'] = '';
        				}
        			}
        			if(URL::get('swimming_pool_invoice'))
                    {
        				$sql_swimming_pool='
                        					SELECT 
                        						swimming_pool_reservation_pool.hotel_reservation_room_id,sum(swimming_pool_reservation_pool.total_amount) as total_amount
                        					FROM 
                        						swimming_pool_reservation_pool
                        					WHERE
                        						swimming_pool_reservation_pool.hotel_reservation_room_id='.$id.'
                        					GROUP BY
                        						swimming_pool_reservation_pool.hotel_reservation_room_id
                        				';
        				if($row['total_swimming_pool_amount'] = DB::fetch($sql_swimming_pool,'total_amount') and HAVE_SWIMMING)
                        {
        					$percent = 100;$status = 0;
        					$amount =$row['total_swimming_pool_amount'];
        					$items['SWIMMING_POOL_'.$id]['net_amount'] = System::display_number($amount);
                            $items['SWIMMING_POOL_'.$id]['real_amount'] = $amount;
        					$items['SWIMMING_POOL_'.$id]['id'] = $id;
        					$items['SWIMMING_POOL_'.$id]['type'] = 'SWIMMING_POOL';
        					$items['SWIMMING_POOL_'.$id]['service_rate'] = 0;
        					$items['SWIMMING_POOL_'.$id]['tax_rate'] = 0;
        					$items['SWIMMING_POOL_'.$id]['date'] = '';
        					$items['SWIMMING_POOL_'.$id]['rr_id'] = $id;
        					$items['SWIMMING_POOL_'.$id]['percent'] = $percent;
        					$items['SWIMMING_POOL_'.$id]['status'] = $status;
        					$items['SWIMMING_POOL_'.$id]['amount'] = ($amount);
        					$items['SWIMMING_POOL_'.$id]['description'] = $row['room_name'].' '.Portal::language('swimming_pool');
                            $items['SWIMMING_POOL_'.$id]['link'] = '#';
                            $items['SWIMMING_POOL_'.$id]['bill_number'] = '';
        				}
        			}
        			
        			if(URL::get('shop_invoice'))
                    {
        				$sql_shop='
                					SELECT 
                						shop_invoice.RESERVATION_ROOM_ID,sum(shop_invoice.TOTAL) as total_amount
                					FROM 
                						shop_invoice
                					WHERE
                						shop_invoice.RESERVATION_ROOM_ID='.$id.'
                					GROUP BY
                						shop_invoice.RESERVATION_ROOM_ID
                				';
        				if($row['total_shop_amount'] = DB::fetch($sql_shop,'total_amount'))
                        {
        					$percent = 100;$status = 0;
        					$amount =$row['total_shop_amount'];
        					$items['SHOP_'.$id]['net_amount'] = System::display_number($amount);
                            $items['SHOP_'.$id]['real_amount'] = $amount;
        					$items['SHOP_'.$id]['id'] = $id;
        					$items['SHOP_'.$id]['type'] = 'SHOP';
        					$items['SHOP_'.$id]['service_rate'] = 0;
        					$items['SHOP_'.$id]['tax_rate'] = 0;
        					$items['SHOP_'.$id]['date'] = '';
        					$items['SHOP_'.$id]['rr_id'] = $id;
        					$items['SHOP_'.$id]['percent'] = $percent;
        					$items['SHOP_'.$id]['status'] = $status;
        					$items['SHOP_'.$id]['amount'] = ($amount);
        					$items['SHOP_'.$id]['description'] = $row['room_name'].' '.Portal::language('shop');
                            $items['SHOP_'.$id]['link'] = '#';
                            $items['SHOP_'.$id]['bill_number'] = '';
        				}
        			}
        			if($row['room_id'])
                    {
        				//----------------------------------------Tien dien thoai-------------------------------------------------
        				$sql_p = '
        					SELECT
        						telephone_report_daily.total_before_tax AS total,
        						telephone_report_daily.id as id,
        						telephone_report_daily.dial_number,
        						telephone_report_daily.hdate
        					FROM
        						telephone_report_daily
        						inner join telephone_number on telephone_number.phone_number = telephone_report_daily.phone_number_id
        					WHERE
        						telephone_report_daily.hdate >='.($row['time_in']).' and telephone_report_daily.hdate <= '.($row['time_out']).'
        						AND telephone_number.room_id = '.$row['room_id'].'
        					ORDER BY
        						telephone_report_daily.hdate
        	
        				';
        				
        				if($phones = DB::fetch_all($sql_p))
                        {
        					foreach($phones as $t_key=>$t_value)
                            {
        						$percent = 100;$status = 0;
        						$amount =$t_value['total'];
        						$items['TELEPHONE_'.$t_key]['net_amount'] = System::display_number($amount);
                                $items['TELEPHONE_'.$t_key]['real_amount'] = $amount;
        						$items['TELEPHONE_'.$t_key]['id'] = $t_key;
        						$items['TELEPHONE_'.$t_key]['type'] = 'TELEPHONE';
        						$items['TELEPHONE_'.$t_key]['service_rate'] = 0;
        						$items['TELEPHONE_'.$t_key]['tax_rate'] = (TELEPHONE_TAX_RATE)?TELEPHONE_TAX_RATE:0;
        						$items['TELEPHONE_'.$t_key]['date'] = date('d/m/Y',$t_value['hdate']);
        						$items['TELEPHONE_'.$t_key]['rr_id'] = $id;
        						$items['TELEPHONE_'.$t_key]['percent'] = $percent;
        						$items['TELEPHONE_'.$t_key]['status'] = $status;
        						$items['TELEPHONE_'.$t_key]['amount'] = ($amount);
        						$items['TELEPHONE_'.$t_key]['description'] = $row['room_name'].' '.Portal::language('telephone').'_'.$t_value['dial_number'];
                                $items['TELEPHONE_'.$id]['link'] = '#';
                                $items['TELEPHONE_'.$id]['bill_number'] = '';
                                
        					}
        				}
        			}
        			if($row['reduce_amount'] != '' && $row['reduce_amount']>0){
        				$percent = 100;$status = 0;
        				$amount = $row['reduce_amount'];
        				$items['DISCOUNT_'.$id]['net_amount'] = System::display_number($amount);
                        $items['DISCOUNT_'.$id]['real_amount'] = $amount;
        				$items['DISCOUNT_'.$id]['id'] = $id;
        				$items['DISCOUNT_'.$id]['type'] = 'DISCOUNT';
        				$items['DISCOUNT_'.$id]['service_rate'] = 0;
        				$items['DISCOUNT_'.$id]['tax_rate'] = 0;
        				$items['DISCOUNT_'.$id]['date'] = '';
        				$items['DISCOUNT_'.$id]['rr_id'] = $id;
        				$items['DISCOUNT_'.$id]['percent'] = $percent;
        				$items['DISCOUNT_'.$id]['status'] = $status;
        				$items['DISCOUNT_'.$id]['amount'] = ($amount);
        				$items['DISCOUNT_'.$id]['description'] = $row['room_name'].' '.Portal::language('discount_room');
                        $items['DISCOUNT_'.$id]['link'] = '#';
                        $items['DISCOUNT_'.$id]['bill_number'] = '';
        			}
                }
                if(isset($list_service['check_deposit']))
                {
        			if($row['deposit']>0)
                    {
        				$percent = 100;
                        $status = 0;
        				$amount =$row['deposit'];
        				$items['DEPOSIT_'.$id]['net_amount'] = System::display_number($amount);
                        $items['DEPOSIT_'.$id]['real_amount'] = $amount;
        				$items['DEPOSIT_'.$id]['id'] = $id;
        				$items['DEPOSIT_'.$id]['type'] = 'DEPOSIT';
        				$items['DEPOSIT_'.$id]['service_rate'] = 0;
        				$items['DEPOSIT_'.$id]['tax_rate'] = 0;
        				$items['DEPOSIT_'.$id]['date'] = '';
        				$items['DEPOSIT_'.$id]['rr_id'] = $id;
        				$items['DEPOSIT_'.$id]['percent'] = $percent;
        				$items['DEPOSIT_'.$id]['status'] = $status;
        				$items['DEPOSIT_'.$id]['amount'] = ($amount);
        				$items['DEPOSIT_'.$id]['description'] = $row['room_name'].' '.Portal::language('deposit_room');
                        $items['DEPOSIT_'.$id]['link'] = '#';
                        $items['DEPOSIT_'.$id]['bill_number'] = '';
        			}
                }
                
    	   return $items;
        }
        
    }
?>
