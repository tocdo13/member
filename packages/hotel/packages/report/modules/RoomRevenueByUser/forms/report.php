<?php
class RoomRevenueByUserForm extends Form
{
	function RoomRevenueByUserForm()
	{
		Form::Form('RoomRevenueByUserForm');
        
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
		if(Url::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
			require_once 'packages/hotel/packages/reception/modules/includes/get_reservation.php';
			$from_day = Url::get('date_from');
            $to_day = Url::get('date_to');
            //echo $day.'/'.$month.'/'.$year;
            //echo $end_day.'/'.$end_month.'/'.$end_year;
			$time_from = Date_Time::to_time($from_day);
            $time_to = Date_Time::to_time($to_day)+86400;
            
			$cond = '1=1 and reservation_room.time_in>='.$time_from.' and reservation_room.time_in<'.$time_to.''
                    .( Url::get('user_id')? ' and  reservation_room.booked_user_id = \''.Url::get('user_id').'\'' :'' )
            ;	
			if(User::can_admin(false,ANY_CATEGORY)){
				$cond .= Url::get('portal_id')?' and reservation.PORTAL_ID = \''.Url::get('portal_id').'\'':'';
			}else{
				$cond .= ' and reservation.PORTAL_ID = \''.PORTAL_ID.'\'';	
			}
            
            $cond .= ' and reservation_room.booked_user_id is not null ';
            /*
            if(User::is_admin())
            {
                $cond .= ' and room.name = \'221\' ';
            }
            */
            //Tien phong      
            $sql = '
                        Select 
                            traveller_folio.id,
                            reservation_room.booked_user_id as user_id,
                            party.full_name,
                            CASE
                                WHEN reservation_room.net_price = 1 THEN room_status.change_price / ( 1 + (reservation_room.tax_rate * 0.01 ) + (reservation_room.service_rate *0.01) + ( reservation_room.tax_rate *0.01 * reservation_room.service_rate*0.01 ) )
                                ELSE room_status.change_price
                            END
                            as total_before_tax,
                            traveller_folio.folio_id,
                            reservation_room.id as reservation_room_id,
                            reservation_room.reservation_id,
                            room.name as room_name,
                            reservation_room.time_in,
                            reservation_room.time_out
                        from
                            traveller_folio
                            inner join room_status on traveller_folio.invoice_id = room_status.id and traveller_folio.type = \'ROOM\'
                            inner join reservation_room on reservation_room.id = room_status.reservation_room_id
                            inner join reservation on reservation.id = reservation_room.reservation_id
                            left join party on party.user_id = reservation_room.booked_user_id
                            inner join room on reservation_room.room_id = room.id
                        where
                            '.$cond.'
                        order by
                            reservation_room.booked_user_id,
                            traveller_folio.folio_id,
                            reservation_room.id,
                            reservation.id
                            
                    ';
            $traveller_folio = DB::fetch_all($sql);
            
            /*
            if(User::is_admin())
            {
                echo 'phong';
                System::debug($traveller_folio);
            }
            */
            //System::debug($traveller_folio);
            $items = array();
            $items = $this->calculate_revenue($items,$traveller_folio);
            
            //Tien phong phat sinh : late in ... : 
            $sql = '
                        Select 
                            traveller_folio.id,
                            reservation_room.booked_user_id as user_id,
                            party.full_name,
                            (extra_service_invoice_detail.price * extra_service_invoice_detail.quantity) as total_before_tax,
                            traveller_folio.folio_id,
                            reservation_room.id as reservation_room_id,
                            reservation_room.reservation_id,
                            room.name as room_name,
                            reservation_room.time_in,
                            reservation_room.time_out
                        from
                            traveller_folio
                            inner join extra_service_invoice_detail on traveller_folio.invoice_id = extra_service_invoice_detail.id and traveller_folio.type = \'EXTRA_SERVICE\'
                            inner join extra_service_invoice on extra_service_invoice_detail.invoice_id  = extra_service_invoice.id and extra_service_invoice.payment_type = \'ROOM\'
                            inner join reservation_room on reservation_room.id = traveller_folio.reservation_room_id
                            inner join reservation on reservation.id = reservation_room.reservation_id
                            left join party on party.user_id = reservation_room.booked_user_id
                            inner join room on reservation_room.room_id = room.id
                        where
                            '.$cond.'    
                        order by
                            reservation_room.booked_user_id,
                            traveller_folio.folio_id,
                            reservation_room.id,
                            reservation.id
                    ';
            //System::debug($sql);
            $items_extra = DB::fetch_all($sql);
             
            /*
            if(User::is_admin())
            {
                echo 'extra';
               
            }
            */
            //System::debug($items_extra);
            //Cong don vao tien phong
            $items = $this->calculate_revenue($items,$items_extra);
            
			if(empty($items)){// Neu không có bản ghi nào
				$this->parse_layout('header',
					get_time_parameters()+
					array(
						'page_no'=>0,
						'total_page'=>0,
						'hotel_name'=>Url::get('portal_id')?DB::fetch('SELECT id,name_1 FROM party WHERE user_id = \''.PORTAL_ID.'\'','name_1'):HOTEL_NAME
					)
				);
				$this->parse_layout('no_record');
				$this->parse_layout('footer',array(
					'page_no'=>0,
					'total_page'=>0
				));	
			}else{
                $this->print_all_pages($items);
			}
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
			$users = DB::fetch_all('
				SELECT
					party.user_id as id,party.user_id as name
				FROM
					party
					INNER JOIN account ON party.user_id = account.id
				WHERE
					party.type=\'USER\'
					AND account.is_active = 1
				ORDER BY 
					party.user_id ASC
			');
			$portals = Portal::get_portal_list();
			$this->parse_layout('search',
				array(
				'portal_id_list'=>array(''=>Portal::language('all')) + String::get_list($portals),
				'user_id_list'=>array(''=>Portal::language('all'))+String::get_list($users),
				'view_all'=>$view_all
				)
			);	
		}			
	}
	function print_all_pages(&$items)
	{
	    $from_day = Url::get('date_from');
        $to_day = Url::get('date_to');
		if(sizeof($items)>0)
		{
			$this->group_function_params = array(
					'total_before_tax'=>0,
				);
            $this->print_page($items, $report, 1,1);
		}
		else
		{
			$this->parse_layout('header',
			get_time_parameters()+
				array(
					'to_date'=>$to_day,
                    'from_date'=>$from_day,
                    'page_no'=>0,
					'total_page'=>0
				)
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
        $i = 1;
		$last_group_function_params = $this->group_function_params;
		foreach($items as $k=>$item)
		{
            //chua hieu tai sao lai + 1
            $items[$k]['rowspan'] = count($item['revenue'])+1;
            $items[$k]['stt'] = $i++;
    		$items[$k]['total_amount'] = 0;
            foreach($item['revenue'] as $r_r_id=>$revenue)
    		{
    			
                if($temp = $revenue['total_before_tax']){
    				$this->group_function_params['total_before_tax'] += $temp;
                    $items[$k]['total_amount'] += $temp;
    			}
                $items[$k]['revenue'][$r_r_id]['total_before_tax'] = System::display_number($revenue['total_before_tax']);
    		}
            $items[$k]['total_amount'] = System::display_number($items[$k]['total_amount']);
		}
        //System::debug($items);
		$this->parse_layout('header',
			array(
                'to_date'=>$to_day,
                    'from_date'=>$from_day,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)
		);
		$this->parse_layout('report',array(
				'items'=>$items,
				'last_group_function_params'=>$last_group_function_params,
				'group_function_params'=>$this->group_function_params,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)
		);
		$this->parse_layout('footer',array(				
			'page_no'=>$page_no,
			'total_page'=>$total_page
		));
	}
    
    function calculate_revenue($items, $items_db)
    {
        
        foreach($items_db as $k=>$v)
            {
                //Neu chua ton tai user
                if( !isset($items[$v['user_id']]) )
                {
                    $items[$v['user_id']] = array(
                                                    'full_name'=>$v['full_name'],
                                                    'revenue'=>array(
                                                                $v['reservation_room_id']=>array(
                                                                                                'id'=>$v['reservation_room_id'],
                                                                                                'reservation_room_id'=>$v['reservation_room_id'],
                                                                                                'reservation_id'=>$v['reservation_id'],
                                                                                                'room_name'=>$v['room_name'],
                                                                                                'arrival_time'=>date('H\h:i d/m/Y',$v['time_in']),
                                                                                                'departure_time'=>date('H\h:i d/m/Y',$v['time_out']),
                                                                                                'total_before_tax'=>$v['total_before_tax'],    
                                                                                                ),
                                                                    
                                                                ),
                                                    
                                                    );
                }
                else //Neu da ton tai user
                {
                    if( isset( $items[$v['user_id']]['revenue'][$v['reservation_room_id']] ) )
                    {
                        $items[$v['user_id']]['revenue'][$v['reservation_room_id']]['total_before_tax'] += $v['total_before_tax'];
                    }
                    else
                    {
                        $items[$v['user_id']]['revenue'][$v['reservation_room_id']] = array(
                                                                                            'id'=>$v['reservation_room_id'],
                                                                                            'reservation_room_id'=>$v['reservation_room_id'],
                                                                                            'reservation_id'=>$v['reservation_id'],
                                                                                            'room_name'=>$v['room_name'],
                                                                                            'arrival_time'=>date('H\h:i d/m/Y',$v['time_in']),
                                                                                            'departure_time'=>date('H\h:i d/m/Y',$v['time_out']),
                                                                                            'total_before_tax'=>$v['total_before_tax'],    
                                                                                            );
                    }
                }
            }
        return $items; 
        
    }
    


}

?>
