<?php
class RestaurantDiscountReportForm extends Form{
	function RestaurantDiscountReportForm(){
		Form::Form('RestaurantDiscountReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw(){
		require_once 'packages/core/includes/utils/lib/report.php';
		 
		$total = array();
		$total_service_others = array();
		$items = array();
		$dautuan = $this->get_beginning_date_of_week();
		$date_from = Url::get('from_date')?Date_Time::to_orc_date(Url::get('from_date')):$this->get_beginning_date_of_week();
		$date_to = Url::get('to_date')?Date_Time::to_orc_date(Url::get('to_date')):$this->get_end_date_of_week();
		$this->map['from_date'] = Date_Time::convert_orc_date_to_date($date_from,'/');
		$this->map['to_date'] = Date_Time::convert_orc_date_to_date($date_to,'/');
		$time_from = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_from,'/'));
		$time_to = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_to,'/')) + (24*3600);
		$month = date('m');
        //$this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
        //End Luu Nguyen GIap add portal
        if(Url::get('portal_id'))
         {
             $portal_id =  Url::get('portal_id');
             if(Url::get('portal_id')!='ALL')
             {
                 $bars = DB::fetch_all("select id,name FROM bar where portal_id='".Url::get('portal_id')."'");
             }
             else
             {
                $bars = DB::fetch_all("select id,name FROM bar");
             }
         }
         else
         {
            $portal_id =PORTAL_ID;  
            $bars = DB::fetch_all("select id,name FROM bar");
         }
        //Start Luu Nguyen Giap add portal
        if(!empty($_REQUEST['bars']))
        {
            $str_bars = implode(',',$_REQUEST['bars']);
            $cond_bar = " AND bar_reservation.bar_id in (".$str_bars.")";
            foreach($bars as $key=>$value)
            {
                $flag = false;
                 foreach($_REQUEST['bars'] as $row)
                 {
                    if($bars[$key]['id']==$row)
                    {
                        $flag = true;
                        break;
                    }
                 }
                 if($flag)
                    $bars[$key]['check'] = 1;
                 else
                    $bars[$key]['check'] = 0;
            }
        }
        else
        {
            $cond_bar='';
            if(!isset($_REQUEST['do_search']))
            {
                foreach($bars as $key=>$value)
                {
                    $bars[$key]['check'] = 1;
                }
            }
            else
            {
                foreach($bars as $key=>$value)
                {
                    $bars[$key]['check'] = 0;
                }
            }
        }
        //End Luu Nguyen Giap add portal
        
		//--------------------------Doanh thu bar---------------------------------------------------//
		//$this->line_per_page = URL::get('line_per_page',15);
		$cond = $this->cond = ' 1 >0 '
				.' and bar_reservation.departure_time>='.$time_from.' and bar_reservation.departure_time<'.$time_to.''
				.' and bar_reservation.status=\'CHECKOUT\' ';
         //Start Luu Nguyen Giap add portal
		//$cond .= (URL::get('portal')?' and bar_reservation.portal_id = \'#'.URL::get('portal').'\' ':'') ;
        
        if($portal_id!="ALL")
        {
            $cond .=" and bar_reservation.portal_id ='".$portal_id."'";
        }
        //End Luu Nguyen Giap add portal
		//----------tìm kiếm theo khách--------------------------------//		
		
        $sql_guest = 'SELECT DISTINCT
						CASE
                            WHEN bar_reservation.agent_name is null THEN customer.name
                            ELSE bar_reservation.agent_name
                            END as customer_name
						,CASE
                            WHEN bar_reservation.agent_name is null THEN customer.name
                            ELSE bar_reservation.agent_name
                            END as id
					FROM 
						bar_reservation 
						INNER JOIN bar_reservation_product ON bar_reservation_product.bar_reservation_id = bar_reservation.id
						left join reservation_room on reservation_room.id = bar_reservation.reservation_room_id
                        left join reservation on reservation_room.reservation_id=reservation.id
                        left join customer on customer.id = reservation.customer_id
					WHERE 
						'.$cond.'  AND (bar_reservation_product.discount_rate <> 0 or bar_reservation.discount_percent <> 0)';
        
       
		$guestes = DB::fetch_all($sql_guest);
	
        $guest_list = '<option value="ALL">--'.Portal::language('customer').'--</option>'; 
		if($guestes){
			foreach($guestes as $id => $guest){
			    if(!empty($guest['customer_name']))
                {
                    if(isset($_REQUEST['customer']) && $_REQUEST['customer']==$guest['id'])
                    {
                        $guest_list .= '<option value="'.$guest['id'].'" selected="selected">'.$guest['customer_name'].'</option> ';    
                    }
                    else
                    {
                        $guest_list .= '<option value="'.$guest['id'].'" >'.$guest['customer_name'].'</option> '; 
                    }   
                }
				 
			}
		}
		//----------tìm kiếm theo người nhận-------------------------------//
		$sql_receiver = 'SELECT DISTINCT
						CASE
                            WHEN bar_reservation.receiver_name is null THEN CONCAT(concat(traveller.first_name,\' \'),traveller.last_name)
                            ELSE bar_reservation.receiver_name
                            END as id,
                        CASE
                            WHEN bar_reservation.receiver_name is null THEN CONCAT(concat(traveller.first_name,\' \'),traveller.last_name)
                            ELSE bar_reservation.receiver_name
                            END as receiver_name
					FROM 
						bar_reservation 
						INNER JOIN bar_reservation_product ON bar_reservation_product.bar_reservation_id = bar_reservation.id
                        left join reservation_traveller on bar_reservation.reservation_traveller_id = reservation_traveller.id
                        left join traveller on reservation_traveller.traveller_id = traveller.id
					WHERE 
						'.$cond.'  AND (bar_reservation_product.discount_rate <> 0 or bar_reservation.discount_percent <> 0)';
		$receiveres = DB::fetch_all($sql_receiver);
		$receiver_list = '<option value="">--'.Portal::language('guest_name').'--</option>'; 
		if($receiveres){
			foreach($receiveres as $id => $recei){
				if(!empty($recei['receiver_name']))
                {
                    if(isset($_REQUEST['receiver']) && $_REQUEST['receiver']==$recei['id'])
                    {
                        $receiver_list .= '<option value="'.$recei['id'].'" selected="selected">'.$recei['receiver_name'].'</option> ';    
                    }
                    else
                    {
                        $receiver_list .= '<option value="'.$recei['id'].'">'.$recei['receiver_name'].'</option> '; 
                    }
                }
					 
			}	
		}
        //echo Date_Time::convert_orc_date_to_date(12/7/1990);
		//----------tìm kiếm theo phòng--------------------------------//
		$sql_room = 'SELECT DISTINCT
						reservation_room.room_id as id
						,room.name as room_name
					FROM 
						bar_reservation 
						INNER JOIN bar_reservation_product ON bar_reservation_product.bar_reservation_id = bar_reservation.id
						left JOIN reservation_room on reservation_room.id = bar_reservation.reservation_room_id
						left JOIN room ON room.id = reservation_room.room_id 
					WHERE 
						'.$cond.'  AND (bar_reservation_product.discount_rate <> 0 or bar_reservation.discount_percent <> 0)';
		$rooms = DB::fetch_all($sql_room);
        //System::debug($rooms);
		$rooms_list = '<option value="">--'.Portal::language('room').'--</option>'; 
		if($rooms){
			foreach($rooms as $id => $room){
                if(!empty($room['room_name']))
                {
                    if(isset($_REQUEST['room']) && $_REQUEST['room']==$room['id'])
                    {
                        $rooms_list .= '<option value="'.$room['id'].'" selected="selected">'.$room['room_name'].'</option> ';    
                    }
                    else
                    {
                        $rooms_list .= '<option value="'.$room['id'].'">'.$room['room_name'].'</option> ';
                    }
                }
				     
			}
		}
		if((Url::get('customer')) AND (Url::get('customer') !='ALL')){
			$cond .= 'and ((bar_reservation.pay_with_room!=1 AND bar_reservation.agent_name=\''.Url::get('customer').'\') OR (bar_reservation.pay_with_room=1 AND customer.name = \''.Url::get('customer').'\'))';	
		}
		if(Url::get('room')){
			$cond .= 'and reservation_room.room_id = '.Url::get('room').'';	
		}
		if(Url::get('receiver')){
			$cond .= 'and (( bar_reservation.pay_with_room!=1 AND bar_reservation.receiver_name = \''.Url::get('receiver').'\') OR ( bar_reservation.pay_with_room=1 AND CONCAT(concat(traveller.first_name,\' \'),traveller.last_name) = \''.Url::get('receiver').'\'))';	
		}
		$sql = '
				SELECT 
					* 
				FROM
					(SELECT
						bar_reservation.id,bar_reservation.code,bar_reservation.departure_time
						,bar_reservation.arrival_time
						,bar_reservation.total,bar_reservation.payment_result
						,bar_reservation.user_id as receptionist_id
						,bar_reservation.exchange_rate
                        ,bar_reservation.discount                        
						,CASE
                            WHEN bar_reservation.pay_with_room=1 THEN CONCAT(concat(traveller.first_name,\' \'),traveller.last_name)
                            ELSE bar_reservation.receiver_name
                            END as receiver_name
						,bar_reservation.bar_fee_rate
						,bar_reservation.tax_rate
                        ,CASE
                            WHEN bar_reservation.pay_with_room=1 THEN customer.name
                            ELSE bar_reservation.agent_name
                            END as agent_name
						,customer.name as customer_name
						,room.name as room_name
						,CONCAT(traveller.first_name,CONCAT(\' \',traveller.first_name)) as traveller_name
						,row_number() over (ORDER BY bar_reservation.id) as rownumber
					FROM 
						bar_reservation 
						inner join bar_reservation_product on bar_reservation_product.bar_reservation_id = bar_reservation.id
						left join reservation_room on reservation_room.id = bar_reservation.reservation_room_id
                        left join reservation on reservation_room.reservation_id=reservation.id
                        left join customer on customer.id = reservation.customer_id
						left join room on room.id = reservation_room.room_id
						left join reservation_traveller on bar_reservation.reservation_traveller_id = reservation_traveller.id
                        left join traveller on reservation_traveller.traveller_id = traveller.id
					WHERE 
						'.$cond.' AND (bar_reservation_product.discount_rate <> 0 or bar_reservation.discount_percent <> 0 or bar_reservation.discount >0)
                        '.$cond_bar.'
                        
					ORDER BY
						bar_reservation.id
					)';
				//WHERE
					//rownumber > '.((URL::get('start_page')-1)*$this->line_per_page).' AND rownumber<='.(URL::get('no_of_page')*$this->line_per_page).'
				//';
		$items = DB::fetch_all($sql);
      //System::debug($cond);
		$sql = '
				SELECT
					bar_reservation_product.id, bar_reservation_product.bar_reservation_id,
					bar_reservation_product.price,bar_reservation_product.product_id,
					bar_reservation_product.quantity_discount,bar_reservation_product.discount_rate,
					bar_reservation_product.discount,
                    bar_reservation_product.quantity,
					bar_reservation.exchange_rate,product.name_'.Portal::language().' as product_name,
                    --bar_reservation.discount,                    
                    bar_reservation.discount_percent as full_discount
				FROM
					bar_reservation_product
					inner join bar_reservation on bar_reservation.id = bar_reservation_product.bar_reservation_id
                    left join reservation_room on reservation_room.id = bar_reservation.reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join customer on customer.id = reservation.customer_id
                    left join room on room.id = reservation_room.room_id
					inner join product ON product.id = bar_reservation_product.product_id
                    left join reservation_traveller on bar_reservation.reservation_traveller_id = reservation_traveller.id
                    left join traveller on reservation_traveller.traveller_id = traveller.id
				WHERE 
					'.$cond.'
                    '.$cond_bar.'
					AND (bar_reservation_product.discount_rate >0 OR bar_reservation_product.discount_category >0 or bar_reservation.discount_percent > 0 or bar_reservation.discount >0)
					ORDER BY bar_reservation_product.bar_reservation_id
					';
		$item_details = DB::fetch_all($sql);
        //System::debug($sql);
		foreach($items as $key=>$value){
			$items[$key]['flag'] = 1;
			$items[$key]['arrival_time'] = date('d/m/Y',$value['arrival_time']);
            $items[$key]['arrival_time_hour'] = date('H:i',$value['arrival_time']);
		}
		foreach($item_details as $k=>$v)
		{
			$item_details[$k]['flag'] = 1;
		}
		//System::Debug($item_details);
		$total_discount = 0;
		$total = 0;
		$flag =1;
		foreach($item_details as $k=>$v)
		{
			if($v['bar_reservation_id'] == $items[$v['bar_reservation_id']]['id']){
				if($flag == $items[$v['bar_reservation_id']]['id']){
					$items[$v['bar_reservation_id']]['flag'] ++;
					$item_details[$k]['flag']++;
				}
				//$item_details[$k]['arrival_time'] = date('d/m/Y h:m',$items[$v['bar_reservation_id']]['arrival_time']);
				$item_details[$k]['customer_name'] = $items[$v['bar_reservation_id']]['customer_name'];
				$item_details[$k]['room_name'] = $items[$v['bar_reservation_id']]['room_name'];
				$item_details[$k]['receiver_name'] = $items[$v['bar_reservation_id']]['receiver_name'];
				$item_details[$k]['receptionist_id'] = $items[$v['bar_reservation_id']]['receptionist_id'];
				$item_details[$k]['price'] = System::display_number(String::vnd_round($v['price']));
				$price = String::vnd_round($v['price']); //RES_EXCHANGE_RATE??
				$ttl = $price*($v['quantity'] - $v['quantity_discount']);
				$discnt = String::vnd_round($ttl*$v['discount_rate']/100);
                $disfull = ($ttl-$discnt)*$v['full_discount']/100;
				$item_details[$k]['discount_real'] = System::display_number($discnt + $disfull);
				$total_price = $ttl-$discnt - $disfull ;
				$total_discount += ($discnt + $disfull) ;
				$total += $total_price;
				$item_details[$k]['total'] = System::display_number($ttl-$discnt);
				$flag = $items[$v['bar_reservation_id']]['id'];
				//$items[$v['bar_reservation_id']] = $item_details[$k];
			}
			
		}
		$view_all = true;
		$users = DB::fetch_all('
			SELECT
				party.user_id as id,party.user_id as name
			FROM
				party
				INNER JOIN account ON party.user_id = account.id
			WHERE
				party.type=\'USER\'
				AND account.is_active = 1
		');
		//$rows['exchange_rate'] = DB::fetch('select id,exchange from currency where id=\''.$rows['exchange_currency_id'].'\'','exchange');
		$this->map['item_details'] = $item_details;	
		//$this->map['categories'] = $categories;		
		$this->map['items'] = $items;
		$this->map['view_all'] = $view_all;
		$this->map['total_discount'] = System::display_number($total_discount);
		$this->map['total'] = System::display_number($total);
		$this->map['guest'] = $guest_list;
		$this->map['receiver'] = $receiver_list;
		$this->map['rooms'] = $rooms_list;
        
         //Start : Luu Nguyen GIap add portal
        $this->map['bars']=$bars;
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
        //End   :Luu Nguyen GIap add portal
		$this->parse_layout('report',$this->map);		
	}
	function get_beginning_date_of_week(){
		$today = date('d/m/Y');
		$time_today = Date_Time::to_time($today);
		$day_of_week = date('w',$time_today);
		$day_begin_of_week = $time_today  - (24 * 3600 * $day_of_week);
		return (Date_Time::to_orc_date(date('d/m/Y',$day_begin_of_week)));
	}
	function get_end_date_of_week(){
		$today = date('d/m/Y');
		$time_today = Date_Time::to_time($today);
		$day_of_week = date('w',$time_today);
		$end_of_week = $time_today + (24 * 3600 * (6 - $day_of_week));
		return (Date_Time::to_orc_date(date('d/m/Y',$end_of_week)));
	}
}
?>