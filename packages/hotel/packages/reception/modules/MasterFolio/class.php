<?php 
class MasterFolio extends Module
{
	function MasterFolio($row)
	{
	   require_once 'db.php';
	    if(isset($_REQUEST['select_service']))
        {
           $list_service = array();
           if(Url::get('check_room_charge'))
                $list_service['check_room_charge'] = 1;
           if(Url::get('check_ei_lo'))
                $list_service['check_ei_lo'] = 1;
           if(Url::get('check_extra_bed'))
                $list_service['check_extra_bed'] = 1;
           if(Url::get('check_breakfast'))
                $list_service['check_breakfast'] = 1;
           if(Url::get('check_bar'))
                $list_service['check_bar'] = 1;
           if(Url::get('check_minibar'))
                $list_service['check_minibar'] = 1;
           if(Url::get('check_laundry'))
                $list_service['check_laundry'] = 1;
           if(Url::get('check_equipment'))
                $list_service['check_equipment'] = 1;
           if(Url::get('check_tranfer'))
                $list_service['check_tranfer'] = 1;
           if(Url::get('check_deposit'))
                $list_service['check_deposit'] = 1;
           if(Url::get('check_other_service'))
                $list_service['check_other_service'] = 1;
           
	       $cond = '1=1';
           if(Url::get('reservation_id'))
                $cond .= ' and reservation.id='.Url::get('reservation_id');
           if(Url::get('reservation_room_id'))
                $cond .= ' AND (reservation_room.id='.Url::get('reservation_room_id').' OR '.Url::get('reservation_room_id').' in (reservation_room.change_room_to_rr) )';
           //if(Url::get('reservation_traveller_id'))
                //$cond .= ' AND reservation_traveller.id='.Url::get('reservation_traveller_id');
           $items = array();
           
           $reservation_room_list = DB::fetch_all('
                                                    SELECT
                                                        reservation_room.id
                                                        ,room.name as room_name
                                                        ,reservation_room.change_room_from_rr
                                                        ,reservation_room.change_room_to_rr
                                                        ,reservation_room.reservation_id
                                                    FROM
                                                        reservation_room
                                                        inner join room on room.id=reservation_room.id
                                                        inner join reservation on reservation.id=reservation_room.reservation_id
                                                    WHERE
                                                        '.$cond.'
                                                    ');
           if(Url::get('reservation_id') and Url::get('check_deposit')){
                $deposit_group = DB::fetch_all('
                                                SELECT
                                                    reservation.id
                                                    ,sum(payment.amount*payment.exchange_rate) as deposit_group
                                                FROM
                                                    payment
                                                    inner join reservation on reservation.id=payment.reservation_id and payment.type_dps=\'GROUP\'
                                                WHERE
                                                    payment.type_dps = \'GROUP\' and reservation.id='.Url::get('reservation_id').'
                                                GROUP BY
                                                    reservation.id
                                                ');
                $deposit_other = DB::fetch_all('
                                                SELECT
                                                    traveller_folio.reservation_id as id
                                                    ,sum(traveller_folio.total_amount) as deposit_other
                                                    ,sum(traveller_folio.percent) as percent
                                                FROM
                                                    traveller_folio
                                                WHERE
                                                    traveller_folio.type=\'DEPOSIT_GROUP\'
                                                    and traveller_folio.reservation_id='.Url::get('reservation_id').'
                                                GROUP BY
                                                    traveller_folio.reservation_id
                                                ');
                foreach($deposit_group as $key=>$value){
                    if(isset($deposit_other[$key])){
                        if($deposit_other[$key]['deposit_other']!=$value['deposit_group']){
                            $items[$key]['DEPOSIT_GROUP_'.$key]['net_amount'] = System::display_number($value['deposit_group']-$deposit_other[$key]['deposit_other']);        
            				$items[$key]['DEPOSIT_GROUP_'.$key]['id'] = $key;
            				$items[$key]['DEPOSIT_GROUP_'.$key]['type'] = 'DEPOSIT_GROUP';
            				$items[$key]['DEPOSIT_GROUP_'.$key]['service_rate'] = 0;
            				$items[$key]['DEPOSIT_GROUP_'.$key]['tax_rate'] = 0;
            				$items[$key]['DEPOSIT_GROUP_'.$key]['date'] = '';
            				$items[$key]['DEPOSIT_GROUP_'.$key]['rr_id'] = $key;
            				$items[$key]['DEPOSIT_GROUP_'.$key]['percent'] = 100-$deposit_other[$key]['percent'];
            				$items[$key]['DEPOSIT_GROUP_'.$key]['status'] = 1;
            				$items[$key]['DEPOSIT_GROUP_'.$key]['amount'] = number_format(($value['deposit_group']-$deposit_other[$key]['deposit_other']),2);
            				$items[$key]['DEPOSIT_GROUP_'.$key]['description'] = Portal::language('deposit_for_group');
                        }
                    }else{
                        $items[$key]['DEPOSIT_GROUP_'.$key]['net_amount'] = System::display_number($value['deposit_group']);        
        				$items[$key]['DEPOSIT_GROUP_'.$key]['id'] = $key;
        				$items[$key]['DEPOSIT_GROUP_'.$key]['type'] = 'DEPOSIT_GROUP';
        				$items[$key]['DEPOSIT_GROUP_'.$key]['service_rate'] = 0;
        				$items[$key]['DEPOSIT_GROUP_'.$key]['tax_rate'] = 0;
        				$items[$key]['DEPOSIT_GROUP_'.$key]['date'] = '';
        				$items[$key]['DEPOSIT_GROUP_'.$key]['rr_id'] = $key;
        				$items[$key]['DEPOSIT_GROUP_'.$key]['percent'] = 100;
        				$items[$key]['DEPOSIT_GROUP_'.$key]['status'] = 1;
        				$items[$key]['DEPOSIT_GROUP_'.$key]['amount'] = number_format(($value['deposit_group']),2);
        				$items[$key]['DEPOSIT_GROUP_'.$key]['description'] = Portal::language('deposit_for_group');
                    }
                }
           }
           
           $list_customer = DB::fetch_all('
                                            SELECT
                                                customer.id
                                                ,customer.name || \' - \' || \'Recode:\' || reservation.id
                                            FROM
                                                reservation_room
                                               inner join reservation on reservation.id=reservation_room.reservation_id
                                                inner join customer on customer.id=reservation.customer_id
                                            WHERE
                                                '.$cond.'
                                            ');
           $list_reservation_traveller = DB::fetch_all('
                                            SELECT
                                                reservation_traveller.id
                                                ,traveller.first_name || \' \' || traveller.last_name || \' - \'
                                            FROM
                                                reservation_traveller
                                                inner join reservation_room on reservation_room.id=reservation_traveller.reservation_room_id
                                                inner join reservation on reservation.id=reservation_room.reservation_id
                                                inner join customer on customer.id=reservation.customer_id
                                            WHERE
                                                '.$cond.' and reservation_room.status!=\'CHECKOUT\'
                                            ');
           $folio_other = DB::fetch_all('
                                        SELECT
                                            traveller_folio.type || \'_\' || traveller_folio.invoice_id as id
                                            ,sum(traveller_folio.total_amount) as total_amount
                                            ,sum(traveller_folio.percent) as percent
                                        FROM
                                            traveller_folio
                                            inner join reservation_room on reservation_room.id=traveller_folio.reservation_room_id
                                            inner join reservation on reservation.id=reservation_room.reservation_id
                                        where
                                            '.$cond.'
                                        GROUP BY
                                            traveller_folio.type,traveller_folio.invoice_id
                                        ');
           
           if(sizeof($reservation_room_list)!=0 and sizeof($list_service)!=0){
                foreach($reservation_room_list as $key=>$value){
                    $traveller_folio = MasterFolioDB::get_total_room($value['id'],$list_service);
                    foreach($traveller_folio as $k=>$v){
                        $total_amount = round($v['amount'] * (1+$v['service_rate']/100) * (1+$v['tax_rate']/100),0);
                        if(isset($folio_other[$v['type'].'_'.$v['id']])){
                            if($total_amount-$folio_other[$v['type'].'_'.$v['id']]['total_amount']==0){
                                unset($traveller_folio[$k]);
                            }else{
                                $total_amount = $total_amount-$folio_other[$v['type'].'_'.$v['id']]['total_amount'];
                                $amount = $total_amount/((1+$v['service_rate']/100)*(1+$v['tax_rate']/100));
                                $traveller_folio[$k]['net_amount'] = System::display_number($amount);
                                $traveller_folio[$k]['real_amount'] = $amount;
                                $traveller_folio[$k]['amount'] = $amount;
                            }
                        }
                        
                        if(isset($traveller_folio[$k])){
                            $items[$value['reservation_id']][$value['id']]['room_name'] = $value['room_name'];
                        }
                        
                    }
               }
           }
           
           
           
           echo json_encode($items);
           exit();
	    }
        if(isset($_REQUEST['get_autocomplete']))
        {
            require_once 'packages/core/includes/utils/vn_code.php';
            $cond = '1=1';
            if(Url::get('type')=='TRAVELLER_NAME' OR Url::get('type')=='PASSPORT')
            {
                if(Url::get('reservation_id'))
                    $cond .= ' AND reservation.id='.Url::get('reservation_id');
                if(Url::get('reservation_room_id'))
                    $cond .= ' AND reservation_room.id='.Url::get('reservation_room_id');
                
                if(Url::get('type')=='TRAVELLER_NAME'){
                    $cond .= ' AND (
                                        LOWER(FN_CONVERT_TO_VN(traveller.first_name)) like \'%'.convert_utf8_to_latin(mb_strtolower(URL::sget('q'))).'%\'
                                        OR LOWER(FN_CONVERT_TO_VN(traveller.last_name)) like \'%'.convert_utf8_to_latin(mb_strtolower(URL::sget('q'))).'%\'
                                        OR LOWER(FN_CONVERT_TO_VN(traveller.first_name || \' \' || traveller.last_name)) like \'%'.convert_utf8_to_latin(mb_strtolower(URL::sget('q'))).'%\'
                                    )';
                    $items = DB::fetch_all('
                                				select 
                                					reservation_traveller.id
                                                    ,rownum
                                                    ,traveller.first_name || \' \' || traveller.last_name || \'(\' || room.name || \'-\' || reservation_room.status || \')\' as name
                                                    ,reservation_room.id as reservation_room_id
                                                    ,reservation.id as reservation_id
                                				from
                                					reservation_traveller
                                                    inner join traveller on reservation_traveller.traveller_id=traveller.id
                                                    inner join reservation_room on reservation_traveller.reservation_room_id=reservation_room.id
                                                    inner join room on room.id=reservation_room.room_id
                                                    inner join reservation on reservation.id=reservation_room.reservation_id
                                				where
                                                    '.$cond.'
                                					AND (rownum > 0 AND rownum <= 1000)
                                                    AND (reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'BOOKED\')
                                				order by
                                					traveller.first_name, traveller.last_name
                                			');
                }else{
                    $cond .= ' AND LOWER(FN_CONVERT_TO_VN(traveller.passport)) like \'%'.convert_utf8_to_latin(mb_strtolower(URL::sget('q'))).'%\'';
                    $items = DB::fetch_all('
                                				select 
                                					reservation_traveller.id
                                                    ,rownum
                                                    ,traveller.passport || \' \' || traveller.first_name || \' \' || traveller.last_name || \'(\' || room.name || \'-\' || reservation_room.status || \')\' as name
                                                    ,reservation_room.id as reservation_room_id
                                                    ,reservation.id as reservation_id
                                				from
                                					reservation_traveller
                                                    inner join traveller on reservation_traveller.traveller_id=traveller.id
                                                    inner join reservation_room on reservation_traveller.reservation_room_id=reservation_room.id
                                                    inner join room on room.id=reservation_room.room_id
                                                    inner join reservation on reservation.id=reservation_room.reservation_id
                                				where
                                                    '.$cond.'
                                					AND (rownum > 0 AND rownum <= 1000)
                                                    AND (reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'BOOKED\')
                                				order by
                                					traveller.first_name, traveller.last_name
                                			');
                }
                
                foreach($items as $key=>$value)
                {
                    echo $value['name'].'|'.$value['id']."\n";
                }
            }elseif(Url::get('type')=='ROOM_NAME'){
                
                $cond .= ' AND LOWER(FN_CONVERT_TO_VN(room.name)) like \'%'.convert_utf8_to_latin(mb_strtolower(URL::sget('q'))).'%\'';
                
                $items = DB::fetch_all('
                                				select 
                                					reservation_room.id
                                                    ,rownum
                                                    ,room.name || \'-\' || reservation_room.status as name
                                                    ,reservation_room.reservation_id 
                                				from
                                					reservation_room
                                                    inner join room on room.id=reservation_room.room_id
                                				where
                                                    '.$cond.'
                                					AND (rownum > 0 AND rownum <= 1000)
                                                    AND (reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'BOOKED\')
                                				order by
                                					room.name
                                			');
                foreach($items as $key=>$value)
                {
                    echo $value['name'].'|'.$value['id']."\n";
                }
            }
            
            exit();
        }
        if(isset($_REQUEST['find_data']))
        {
	       $cond = '1=1';
           if(Url::get('type')=='TRAVELLER_NAME' OR Url::get('type')=='PASSPORT'){
                
                $items = DB::fetch('
                                    SELECT 
                                        reservation.id as reservation_id
                                        ,reservation_room.id as reservation_room_id
                                        ,room.name as room_name
                                        ,traveller.first_name || \' \' || traveller.last_name as traveller_name
                                        ,traveller.passport
                                        ,reservation_traveller.id as reservation_traveller_id
                                    FROM
                                        reservation_traveller
                                        inner join traveller on reservation_traveller.traveller_id=traveller.id
                                        inner join reservation_room on reservation_traveller.reservation_room_id=reservation_room.id
                                        inner join room on room.id=reservation_room.room_id
                                        inner join reservation on reservation.id=reservation_room.reservation_id
                                    WHERE
                                        reservation_traveller.id='.Url::get('reservation_traveller_id').'
                                    ');
                echo json_encode($items);
                
           }elseif(Url::get('type')=='ROOM_NAME'){
                $items = DB::fetch('
                                    SELECT 
                                        reservation.id as reservation_id
                                        ,reservation_room.id as reservation_room_id
                                        ,room.name as room_name
                                    FROM
                                        reservation_room
                                        inner join room on room.id=reservation_room.room_id
                                        inner join reservation on reservation.id=reservation_room.reservation_id
                                    WHERE
                                        reservation_room.id='.Url::get('reservation_room_id').'
                                    ');
                $items['list_traveller'] = DB::fetch_all('
                                                SELECT 
                                                    traveller.first_name || \' \' || traveller.last_name as traveller_name
                                                    ,traveller.passport
                                                    ,reservation_traveller.id
                                                FROM
                                                    reservation_traveller
                                                    inner join traveller on reservation_traveller.traveller_id=traveller.id
                                                    inner join reservation_room on reservation_traveller.reservation_room_id=reservation_room.id
                                                WHERE
                                                    reservation_room.id='.Url::get('reservation_room_id').'
                                                ');
                echo json_encode($items);
           }
           
           exit();
	    }
		Module::Module($row);
		switch(URL::get('cmd'))
		{
			case 'edit':
				if(Url::get('folio_id') and DB::exists('select id from folio where id='.Url::get('folio_id'))){
					if(User::can_edit(false,ANY_CATEGORY)){
					   require_once 'forms/edit.php';
					   $this->add_form(new MasterFolioEditForm());	
					}else{
					   Url::access_denied();
					}
				}else{
				    require_once 'forms/list.php';
					$this->add_form(new MasterFolioForm());
				}
                break;
			case 'add':
				if(User::can_add(false,ANY_CATEGORY)){
				   require_once 'forms/edit.php';
				   $this->add_form(new MasterFolioEditForm());	
				}else{
				   Url::access_denied();
				}
                break;
			default:
				if(User::can_view(false,ANY_CATEGORY)){
					require_once 'forms/list.php';
					$this->add_form(new MasterFolioForm());
				}else{
					Url::access_denied();
				}
				break;
		}
	}
}
?>