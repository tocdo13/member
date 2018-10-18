<?php
class ReservationSummaryNewForm extends Form{
	function ReservationSummaryNewForm(){
		Form::Form('ReservationSummaryNewForm');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');  
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		
        //$this->link_js('jquery-latest.js');
        $this->link_js('jquery.tablesorter.js');
	}
	function draw(){
		if(URL::get('do_search')){
		  //sort-code
		      $order_sort = '';
		      if(Url::get('sort'))
              {
		          $order_sort .= 'ORDER BY ';
		          if(Url::get('stt_sort'))
                  {
		              $stt_sort = Url::get('stt_sort');
                      for($id=1;$id<=$stt_sort;$id++)
                      {
                            if(isset($_REQUEST['check_sort_'.$id])){
                                $order_sort .= Url::get('name_sort_'.$id)." ".Url::get('type_sort_'.$id).",";
                            }
                      }
		          }
		      }
              $len = strlen($order_sort)-1;
              $order_sort = substr($order_sort,0,$len);
              //echo $order_sort;
            //end sort-code
		    $arr_cond = array();
              $arr_cond += array('do_search'=>URL::get('do_search'));
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
            // code sap xep - manh ** kho' vai~ **
            require_once 'packages/hotel/packages/report/modules/ReservationSummary/sort.php';
            // end code sap xep
            
            $from_date = Url::get('from_date')?Url::get('from_date'):('01/'.date('m/Y'));
            //echo $from_date;
            $arr_cond += array('from_date'=>$from_date);
            $_REQUEST['from_date'] = $from_date;
            $to_date = Url::get('to_date')?Url::get('to_date'):(cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y')).'/'.date('m/Y'));
    		$arr_cond += array('to_date'=>$to_date);
            $_REQUEST['to_date'] = $to_date;
			$price = 0;
			if(Url::get('price')){
				$price=1;
                $arr_cond += array('price'=>Url::get('price'));
			}
			$this->line_per_page = URL::get('line_per_page',30);
			$cond = '1=1';
			$status='';
			if(User::can_admin(false,ANY_CATEGORY)){
				$cond .= Url::get('portal_id')?' and reservation.portal_id = \''.Url::get('portal_id').'\'':'';
                $arr_cond += array('portal_id'=>Url::get('portal_id'));
			}else{
				$cond .= ' AND reservation.portal_id = \''.PORTAL_ID.'\'';	
			}
            if(Url::get('status'))
            {
               $status = Url::get('status'); 
               $arr_cond += array('status'=> Url::get('status'));
            }
			if((Url::get('status')!="0") AND (Url::get('status')!="")){
				if($status == 'CHECKIN'){
					$cond.=' AND (reservation_room.status=\'CHECKIN\')';
				}elseif($status == 'BOOKED'){
					$cond.=' AND (reservation_room.status=\'BOOKED\')';
				}elseif($status == 'CHECKOUT'){
					$cond.=' AND (reservation_room.status=\'CHECKOUT\')';
				}elseif($status == 'CANCEL'){
					$cond.=' AND (reservation_room.status=\'CANCEL\')';
				}elseif($status == 'ALL_CANCEL'){ //Luu Nguyen Giap add status ALL_CANCEL(all - cancel)
					$cond.=' AND reservation_room.status IN (\'CHECKIN\',\'BOOKED\',\'CHECKOUT\')';
				}else{
                    $cond.=' AND reservation_room.status=\''.$status.'\'';
                } 
			}
            
			if(Url::check('today') and Url::get('today')==1) 
			{
			     $arr_cond += array('today'=>Url::get('today'));
				if(Url::get('view_by_arrival_time')){
					$cond .= '
							AND reservation_room.arrival_time = \''.Date_Time::to_orc_date(date('d/m/Y')).'\'';
                    $arr_cond += array('view_by_arrival_time'=>Url::get('view_by_arrival_time'));
				}else{
					if($status=='CANCEL' or $status == 'AMEND'){
						$cond .= '
							AND (reservation_room.lastest_edited_time>='.Date_Time::to_time(date('d/m/Y')).'
							AND reservation_room.lastest_edited_time<'.(Date_Time::to_time(date('d/m/Y'))+24*3600).')';
					}
                    else
                    {
                         $cond .= '
                            AND (reservation_room.time>='.Date_Time::to_time(date('d/m/Y')).'
                            AND reservation_room.time<'.(Date_Time::to_time(date('d/m/Y'))+24*3600).')';
                    }
				}
			}else{
				if(Url::get('view_by_arrival_time')){
					$cond .= '
							AND (reservation_room.arrival_time >= \''.Date_Time::to_orc_date($from_date).'\'
							AND reservation_room.arrival_time <= \''.Date_Time::to_orc_date($to_date).'\')';
                    $arr_cond += array('view_by_arrival_time'=>Url::get('view_by_arrival_time'));
				}else{
					if($status=='CANCEL' or $status == 'AMEND'){
						$cond .= '
							AND (reservation_room.lastest_edited_time>='.Date_Time::to_time($from_date).'
							AND reservation_room.lastest_edited_time<'.(Date_Time::to_time($to_date)+24*3600).')';					
					}else{
						$cond .= '
							AND (reservation_room.time>='.Date_Time::to_time($from_date).'
							AND reservation_room.time<'.(Date_Time::to_time($to_date)+24*3600).')';
					}
				}
			}
            if(Url::get('customer_group') AND Url::get('customer_group')!='all'){
                $group_id = Url::get('customer_group');
                $cond .= "AND customer.group_id = '$group_id'";
                $arr_cond += array('customer_group'=>Url::get('customer_group'));
            }
			if(Url::get('customer_name')){
				$cond .= ' AND UPPER(customer.name) LIKE \'%'.strtoupper(Url::sget('customer_name')).'%\'';
                $arr_cond += array('customer_name'=>Url::get('customer_name'));
			}
			if(Url::get('tour_name')){
				$cond .= ' AND UPPER(tour.name) LIKE \'%'.strtoupper(Url::sget('tour_name')).'%\'';
                $arr_cond += array('tour_name'=>Url::get('tour_name'));
			}
			if(Url::get('booking_code')){
				$cond .= ' AND UPPER(reservation.booking_code) LIKE \'%'.strtoupper(Url::sget('booking_code')).'%\'';
                $arr_cond += array('booking_code'=>Url::get('booking_code'));
			}
			if(Url::get('user_id')){
				$cond .= ' AND (reservation_room.user_id = \''.Url::sget('user_id').'\' or reservation_room.lastest_edited_user_id = \''.Url::sget('user_id').'\')';
			    $arr_cond += array('user_id'=>Url::get('user_id')); 
            }
			$report = new Report;
            
            $count_res_room = DB::fetch_all('
			SELECT 
				reservation.id
				,count(reservation_room.id) as num
			FROM
				reservation
                inner join reservation_room on reservation_room.reservation_id = reservation.id
                inner join party on party.user_id = reservation.portal_id
				left outer join room_level on room_level.id = reservation_room.room_level_id 
				left outer join tour on reservation.tour_id = tour.id
				left outer join traveller on reservation_room.traveller_id = traveller.id
				left outer join customer on reservation.customer_id = customer.id  
			WHERE
				'.$cond.'
                and (room_level.is_virtual=0 or room_level.is_virtual is NULL )              
			GROUP BY reservation.id
            '.$order_sort.'
			');
            //System::debug($count_res_room);
			$sql = '
				SELECT 
					reservation_room.id
                    ,reservation.id as res_id
					,reservation_room.adult
					,reservation_room.price
                    ,reservation_room.foc
                    ,reservation_room.foc_all
					,reservation_room.status
					,reservation_room.arrival_time
					,reservation_room.departure_time
                    ,reservation_room.change_room_from_rr
                    ,nvl(reservation_room.change_room_to_rr,0) as change_room_to_rr
					,(reservation_room.departure_time - reservation_room.arrival_time) as night
					,to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as brief_arrival_time
					,to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as brief_departure_time
					,room_level.brief_name as room_level
					,customer.name as customer_name
					,tour.name as tour_name
					,'.((Url::get('status')=='AMEND' or Url::get('status')=='CHECKOUT')?'reservation_room.lastest_edited_user_id':((Url::get('status')=='CHECKIN')?' reservation_room.checked_in_user_id':((Url::get('status')=='BOOKED')?'DECODE(reservation_room.booked_user_id,NULL,reservation_room.user_id,reservation_room.booked_user_id)':'reservation_room.user_id'))).' AS user_name
					,party.name_1 as portal_name
					,reservation.booking_code
					,reservation_room.time
					,reservation_room.lastest_edited_time
					,reservation_room.reservation_id
					,traveller.first_name as first_name
					,traveller.last_name as last_name
                    
				FROM 
					reservation_room
					inner join reservation on reservation.id = reservation_room.reservation_id
					inner join party on party.user_id = reservation.portal_id
					left outer join room_level on room_level.id = reservation_room.room_level_id 
					left outer join tour on reservation.tour_id = tour.id
					left outer join traveller on reservation_room.traveller_id = traveller.id
					left outer join customer on reservation.customer_id = customer.id
                WHERE 
					'.$cond.'
                    and (room_level.is_virtual=0 or room_level.is_virtual is NULL )
				ORDER BY
                   reservation_room.arrival_time asc
			';	
			$report= DB::fetch_all($sql);
            if(User::id('developer06'))
            {
                //System::debug($report);
            }
			$i = 1;
            foreach($report as $key=>$item)
			{
				if(Url::get('status') == 'CANCEL' or Url::get('status') == 'AMEND'){
					$report[$key]['time'] = date('d/m/Y',$item['lastest_edited_time']);
				}else{
					$report[$key]['time'] = date('d/m/Y',$item['time']);
				}
                if($item['change_room_to_rr'] != 0)
                {
                    $report[$key]['adult'] = 0; 
                }
				$report[$key]['stt'] = $i++;
                //Kimtan: xử lý nếu là phòng dayuse sẽ tính 1 đêm phòng và hiển thị là dayused
                $report[$key]['day_used'] = 0;
                if($item['arrival_time']==$item['departure_time'] and $item['change_room_to_rr'] == 0)
                {
                    $report[$key]['night']='dayused';
                    $report[$key]['day_used'] = 1;
                }
			}
            //System::debug($report);
            if(User::id()=='developer06')
            {
                //System::debug($report);
                //System::debug($sql);
            }
            if(Url::check('today') and Url::get('today')==1)
			{
			    $from_date = date('d/m/Y');
                $to_date = date('d/m/Y');
		    }
            $sort_define = array('reservation.id'=>'recode');
            $sort_option = array();
            $from_name = 'ReservationSummaryForm';
            $sort = option_sort($sort_option,$sort_define,$arr_cond,$from_name);
            //echo $sort;
            //$this->map['sort'] = $sort;
            $this->parse_layout('header',array(
                                                'to_date'=>$to_date,
                                                'from_date'=>$from_date
                                                )
            );
            $this->parse_layout('report',array(
                                                'items'=>$report,
                                                'count_res_room'=>$count_res_room,
                                                'sort'=>$sort
                                                )
            );
            $this->parse_layout('footer',array());
            
            
		}
		else
		{
			$users = DB::fetch_all('
				SELECT
					party.user_id as id,party.user_id as name
				FROM
					party
					INNER JOIN account ON party.user_id = account.id
				WHERE
					(account.id<>\'admin\' AND account.id<>\'khoand\' AND account.id<>\'tester\' AND account.id<>\'developer\'  AND account.id<>\'trienkhai\')
					AND party.type=\'USER\'
					AND account.is_active = 1
				ORDER BY
					party.user_id
			');
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
	
}
?>