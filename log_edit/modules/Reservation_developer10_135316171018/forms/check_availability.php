<?php
/*
	Class: kiem tra phong trong tu dong
	Written by: khoand
	Date: 20/01/2011
*/
class CheckAvailabilityForm extends Form
{
	function CheckAvailabilityForm()
	{
		Form::Form('CheckAvailabilityForm');
		$this->link_js('packages/hotel/includes/js/ajax.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
		$this->link_css('packages/hotel/'.Portal::template('reception').'/css/style.css');
        $this->link_css('packages/hotel/skins/default/css/w3c.css');
        $this->link_css('packages/hotel/skins/default/css/font-awesome-4.7.0/css/font-awesome.min.css');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->add('arrival_time',new DateType(true,'invalid_arrival_time'));
		$this->add('departure_time',new DateType(true,'invalid_departure_time'));
	}
	function on_submit()
    {
		if($this->check() and Url::check('book'))
        {
			$room_levels = '';
			$i = 0;
			$current_room_quantity = 0;
			foreach($_REQUEST as $key=>$value)
            {
				if(is_string($key) and preg_match_all("/room_quantity_([0-9]*)/",$key,$match))
                {
					$temp = array();
					if(isset($match[1][0]))
                    {
						$room_level_id = $match[1][0];
						if($room_levels)
                        {
							$room_levels .= '|';
						}
						$room_levels .= $room_level_id.','.$value.','.System::calculate_number(Url::get('price_'.$room_level_id)).','.System::calculate_number(Url::get('usd_price_'.$room_level_id)).','.Url::get('adult_'.$room_level_id).','.Url::get('child_'.$room_level_id).','.Url::get('note_'.$room_level_id);
					}
				}
			}
			if($room_levels)
            {
				$status = 'BOOKED';
				$confirm = isset($_REQUEST['confirm'])?1:0;
				Url::redirect_current(array('cmd'=>'add','tour_id','tour_name','customer_id','customer_name','room_levels'=>$room_levels,'arrival_time','time_in','departure_time','time_out','reservation_type_id','price','usd_price','status'=>$status,'confirm'=>$confirm,'booking_code'));
			}
            else
            {
				$this->error('room_level','has_no_room_level_was_selected');
			}
		}
	}
	function draw()
	{
		$this->map = array();
		require_once 'packages/hotel/packages/reception/modules/includes/lib.php';
		 //start:KID them xu ly lay exchange_rate
        $currency_id = (HOTEL_CURRENCY == 'VND')?'USD':'VND';
		$this->map['exchange_rate'] = DB::fetch('select id,exchange from currency where id=\''.$currency_id.'\'','exchange');
        //end:KID them xu ly lay exchange_rate
		if(!Url::get('time_in')){
			$_REQUEST['time_in'] = CHECK_IN_TIME;
		}
		if(!Url::get('time_out')){
			$_REQUEST['time_out'] = CHECK_OUT_TIME;
		}
		$time_in = Url::get('time_in');
		$time_out = Url::get('time_out');
		$arrival_time = Date_Time::to_time(Url::get('arrival_time'));
		$departure_time = Date_Time::to_time(Url::get('departure_time'));
        /** kieu them tim kiem theo gio **/
        if(Url::get('arrival_time_hour')){
            $hour=export(':',Url::get('arrival_time_hour'));
            $hour=$hour[0]*3600+$hour[0]*60;
            $arrival_time+=$hour;
        }
        if(Url::get('departure_time_hour')){
            $hour=export(':',Url::get('departure_time_hour'));
            $hour=$hour[0]*3600+$hour[0]*60;
            $departure_time+=$hour;
        }
        /** kieu them tim kiem theo gio **/
		////////////////Get days/////////////////////////////////////////////////////
		$days = array();
		for($i = $arrival_time;$i < $departure_time;$i = $i + 24*3600){
			$days[$i]['id'] = $i;
			$days[$i]['value'] = date('d/m',$i);
		}
		$this->map['days'] = $days;
		////////////////Cong them khoan gio, phut////////////////////////////////////
		/*if($arrival_time)
		{
			//$arr = explode(':',$time_in);
			$arrival_time = $arrival_time + intval($arr[0])*3600+intval($arr[1])*60;
		}
		if($departure_time)
		{
			$arr = explode(':',$time_out);
			$departure_time = $departure_time + intval($arr[0])*3600+intval($arr[1])*60;
		}*/
		/////////////////////////////////////////////////////////////////////////////
		$extra_cond = Url::get('room_level_id')?' rl.id = \''.Url::get('room_level_id').'\'':' 1>0';
		require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
		$r_r_id = '';
		$room_levels = check_availability($r_r_id,$extra_cond,$arrival_time,$departure_time);
        //System::debug($room_levels);
		$this->map['room_levels'] = $room_levels;
		if(Url::get('room_level_id')){
			$this->map['room_level_name'] = DB::fetch('select name from room_level where id = '.Url::iget('room_level_id').'','name');
		}else{
			$this->map['room_level_name'] = '';
		}
		/////////////////////////////////////////////////////////////////////////////
		$this->map['arrival_time'] = date('d/m/Y H:i\'',$arrival_time);
		$this->map['departure_time'] = date('d/m/Y H:i\'',$departure_time);
		/////////////////////////////////////////////////////////////////////////////
		$this->map['reservation_type_id_list'] = String::get_list(DB::fetch_all('select id,name from reservation_type order by position'));
		$_REQUEST['reservation_type_id'] = 1;
		$ebs = array(
			100000=>array(
				'id'=>'',
				'code'=>'',
				'name'=>'<strong>'.Portal::language('Extra_bed_baby_cot').'</strong>',
				'quantity'=>0
		));
		$ebs+= DB::fetch_all('
			SELECT
				extra_service.*
			FROM
				extra_service
			WHERE
				extra_service.code=\'EXTRA_BED\' or extra_service.code=\'BABY_COT\'
		');
		$cond_extra = 'extra_service_detail.in_date';
        /** Start : Ninh them lay mau cho thu 7,cn **/
        foreach($days as $key=>$value)
        {
            //System::debug($value['id']);
            //System::debug(date('D',$value['id']));
            if(date('D',date($value['id']))=='Sat')
            {
                $days[$key]['color'] = 'blue' ;
            }elseif(date('D',date($value['id']))=='Sun')
            { 
                $days[$key]['color'] = 'red';
            }else{
                $days[$key]['color'] = 'Black' ;
            }
        }
        /** End : Ninh them lay mau cho t7,cn **/
		foreach($ebs as $key=>$value)
		{
			foreach($days as $k=>$v){
				$extra_bed_status = DB::fetch('
					SELECT
						extra_service.code as id,sum(extra_service_invoice_detail.quantity) as quantity
					FROM
						extra_service_invoice_detail
						INNER JOIN extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
						INNER JOIN extra_service on extra_service.id = extra_service_invoice_detail.service_id
                        left JOIN reservation_room on extra_service_invoice.reservation_room_id=reservation_room.id
					WHERE
						extra_service_invoice_detail.in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$k)).'\' AND extra_service.code=\''.$value['code'].'\' 
                        and reservation_room.status!=\'CANCEL\'
					GROUP BY
						extra_service.code
				');
				$ebs[$key]['day_items'][$k]['id'] = $extra_bed_status['id'];
				$quantity = (($value['code']=='EXTRA_BED')?EXTRA_BED_QUANTITY-$extra_bed_status['quantity']:BABY_COT_QUANTITY-$extra_bed_status['quantity']);
				$ebs[$key]['day_items'][$k]['quantity'] = ($key==100000)?'<span style="font-size : 12px; width:35px; color:'.$v['color'].'" class="check-availability-day header">'.$v['value'].'</span>':'<span class="check-availability-day" style="width:35px;background-color:white; color:black;font-size:13px">'.$quantity.'</span>';
			}
			$ebs[$key]['total_quantity'] = (($value['code']=='EXTRA_BED')?EXTRA_BED_QUANTITY:BABY_COT_QUANTITY);
		}
        
		$this->map['ebs'] = $ebs;
        if(!isset($_REQUEST['arrival_time']))
        {
            $_REQUEST['arrival_time'] = date('d/m/Y');
            $_REQUEST['departure_time'] = date('d/m/Y',time()+86400);
            //$_REQUEST['departure_time'] = date('d/m/Y',time()+30*86400);
        }
        //start: KID them ham nay de hien thi so nguoi lon va gia cho hai hang phong double va twin
        $this->map['room_levels_js'] = String::array2js($this->map['room_levels']);
        //end: KID them ham nay de hien thi so nguoi lon va gia cho hai hang phong double va twin
        $total_by_day = array();
        $this->map['total_room'] = 0;
        $this->map['total_real_room'] = 0;
        
        $real_total_day = array();
        $from_date = Date_Time::to_orc_date($_REQUEST['arrival_time']);
        $to_date = Date_Time::to_orc_date($_REQUEST['departure_time']);
        foreach($this->map['room_levels'] as $k=>$v)
        {
            // KID thêm if ($v['is_virtual'] !=1) để bỏ tính tổng phòng ảo
            if($v['is_virtual'] != 1)
            {
                $this->map['total_room'] += $v['total_room_quantity'];

                if(isset($v['day_items']))
                {
                    foreach($v['day_items'] as $day=>$quantity)
                    {
                        if(isset( $total_by_day[date('d/m/Y',$day)]) )
                        {
                            $total_by_day[date('d/m/Y',$day)]['total_avai_room'] += $quantity['number_room_quantity'];
                            $total_by_day[date('d/m/Y',$day)]['total_confirm'] += $quantity['room_cf'];
                            $total_by_day[date('d/m/Y',$day)]['total_not_confirm'] += $quantity['room_not_cf'];
                            /** check inventory **/
                            if(SITEMINDER_TWO_WAY or USE_HLS){
                                $total_by_day[date('d/m/Y',$day)]['total_inventory'] += $quantity['inventory']; 
                            }
                            /** end check inventory **/
                        }
                        else
                        {
                            $total_by_day[date('d/m/Y',$day)] = array('total_avai_room'=>$quantity['number_room_quantity']);
                            $total_by_day[date('d/m/Y',$day)]['total_confirm'] = $quantity['room_cf'];
                            $total_by_day[date('d/m/Y',$day)]['total_not_confirm'] = $quantity['room_not_cf'];
                            /** check inventory **/
                            if(SITEMINDER_TWO_WAY or USE_HLS){
                                $total_by_day[date('d/m/Y',$day)]['total_inventory'] = $quantity['inventory'];
                            }
                            /** end check inventory **/
                        }
                    }
                }
            }
            /** manh them de lay so luong phong chong thuc **/
            /** comment lai vi bay gio lay theo lich su thi doan xu ly nay se khong con nhieu y nghia
            if($v['id']!='')
            {
                $real_total_day[$v['id']] = $this->room_level_check_conflict_real(array($v['id'],Date_Time::to_time($_REQUEST['arrival_time']),Date_Time::to_time($_REQUEST['departure_time'])));
                if($v['is_virtual'] != 1)
                    $this->map['total_real_room'] +=  $this->room_level_check_conflict_real(array($v['id'],$arrival_time,$departure_time));
            }
            **/
            /** end manh **/
        }
        //System::debug($real_total_day);
        $sql_repair='select 
                        to_char(in_date,\'DD/MM/YYYY\') as id,
                        count(*) as room_repair
                     from room_status
                     where 
                     house_status = \'REPAIR\'
                     and in_date >= \''.$from_date.'\' and in_date < \''.$to_date.'\'
                        group by in_date
                        order by in_date
        
        ';
        $room_repair = DB::fetch_all($sql_repair);
        //System::debug($total_by_day);
        foreach($total_by_day as $k=>$v)
        {
            $total_by_day[$k]['total_occ_room'] = $this->get_total_rooms($k,true) - $total_by_day[$k]['total_avai_room'];
            $total_by_day[$k]['total_repair_room'] = 0;
            foreach($room_repair as $k1=>$v1)
            {
                if($k=$k1)
                {
                    $total_by_day[$k]['total_repair_room'] = $v1['room_repair'];
                }
            }
        }
        //System::debug($this->map['room_levels']);
        //--------------------------end----------------------------------------
        $this->map['total_by_day'] = $total_by_day;
        $this->map['real_total_day'] = $real_total_day;
        //exit();
		$this->parse_layout('check_availability',$this->map);
	}
    function get_total_rooms($in_date = false,$is_virtual = false)
	{
	    if($is_virtual){
	       $cond = ' room_level.is_virtual=0 ';
	    }else{
	       $cond = '1=1';
	    }
        
        
        if($in_date)
        {
            if($history_in_date = DB::fetch('select max(in_date) as in_date from room_history where in_date<=\''.Date_Time::to_orc_date($in_date).'\' AND portal_id = \''.PORTAL_ID.'\'','in_date'))
            {
                $sql = '
        			select 
        				distinct room.id,
        				room_history_detail.name,
        				room_history_detail.floor,
        				room_level.price,
        				CONCAT(room_level.brief_name,CONCAT(\' / \',room_type.brief_name)) AS type_name,
        				0 AS overdue_reservation_id,
        				\'\' as house_status,
        				\'AVAILABLE\' as status,
        				\'\' AS note,
        				\'\' AS hk_note,
        				minibar.id as minibar_id,
        				0 as confirm,
        				0 as extra_bed,
        				0 as out_of_service,
        				1 as can_book,
        				room_history_detail.position,
        				room_history_detail.room_level_id,
        				room_history_detail.room_type_id,
        				room_level.is_virtual,
        				room_level.brief_name as room_level_name,
        				\'\' as note,
                        room_type.id as room_type,
                        nvl(room_level.position,0) as room_level_position
        			from
        				room
                        inner join room_history_detail on room_history_detail.room_id=room.id
                        inner join room_history on room_history.id=room_history_detail.room_history_id
        				inner join room_level on room_level.id = room_history_detail.room_level_id
        				inner join room_type on room_type.id = room_history_detail.room_type_id 
        				left outer join minibar on room.id = minibar.room_id 
        			where
        				room.portal_id = \''.PORTAL_ID.'\'
        				'.(Url::get('room_level_id')?' AND room_history_detail.room_level_id = '.Url::iget('room_level_id').'':'').'
                        AND room_history.in_date=\''.$history_in_date.'\'
                        AND room_history_detail.close_room=1
                        AND '.$cond.'
        			order by
                        room_level.is_virtual, 
                        room_level_position,
                        room_history_detail.position,
                        --room.room_level_id asc,
        				--room_history_detail.floor,
                        room_history_detail.name,
                        room.id
                        
        		';
        		$rooms = DB::fetch_all($sql);
            }
            elseif($history_in_date = DB::fetch('select min(in_date) as in_date from room_history where in_date>\''.Date_Time::to_orc_date($in_date).'\' AND portal_id = \''.PORTAL_ID.'\'','in_date'))
            {
                $sql = '
        			select 
        				distinct room.id,
        				room_history_detail.name,
        				room_history_detail.floor,
        				room_level.price,
        				CONCAT(room_level.brief_name,CONCAT(\' / \',room_type.brief_name)) AS type_name,
        				0 AS overdue_reservation_id,
        				\'\' as house_status,
        				\'AVAILABLE\' as status,
        				\'\' AS note,
        				\'\' AS hk_note,
        				minibar.id as minibar_id,
        				0 as confirm,
        				0 as extra_bed,
        				0 as out_of_service,
        				1 as can_book,
        				room_history_detail.position,
        				room_history_detail.room_level_id,
        				room_history_detail.room_type_id,
        				room_level.is_virtual,
        				room_level.brief_name as room_level_name,
        				\'\' as note,
                        room_type.id as room_type,
                        nvl(room_level.position,0) as room_level_position
        			from
        				room
                        inner join room_history_detail on room_history_detail.room_id=room.id
                        inner join room_history on room_history.id=room_history_detail.room_history_id
        				inner join room_level on room_level.id = room_history_detail.room_level_id
        				inner join room_type on room_type.id = room_history_detail.room_type_id
        				left outer join minibar on room.id = minibar.room_id 
        			where
        				room_history_detail.portal_id = \''.PORTAL_ID.'\'
        				'.(Url::get('room_level_id')?' AND room_history_detail.room_level_id = '.Url::iget('room_level_id').'':'').'
                        AND room_history.in_date=\''.$history_in_date.'\'
                        AND room_history_detail.close_room=1
                        AND '.$cond.'
        			order by
                        room_level.is_virtual, 
                        room_level_position,
                        room_history_detail.position,
                        --room.room_level_id asc,
        				--room_history_detail.floor, 
                        room_history_detail.name,
                        room.id
                        
        		';
        		$rooms = DB::fetch_all($sql);
            }
            else
            {
                $sql = '
        			select 
        				distinct room.id,
        				room.name,
        				room.floor,
        				room_level.price,
        				CONCAT(room_level.brief_name,CONCAT(\' / \',room_type.brief_name)) AS type_name,
        				0 AS overdue_reservation_id,
        				\'\' as house_status,
        				\'AVAILABLE\' as status,
        				\'\' AS note,
        				\'\' AS hk_note,
        				minibar.id as minibar_id,
        				0 as confirm,
        				0 as extra_bed,
        				0 as out_of_service,
        				1 as can_book,
        				room.position,
        				room.room_level_id,
        				room.room_type_id,
        				room_level.is_virtual,
        				room_level.brief_name as room_level_name,
        				\'\' as note,
                        room_type.id as room_type,
                        nvl(room_level.position,0) as room_level_position
        			from
        				room
        				inner join room_level on room_level.id = room.room_level_id
        				inner join room_type on room_type.id = room_type_id 
        				left outer join minibar on room.id = minibar.room_id 
        			where
        				room.portal_id = \''.PORTAL_ID.'\'
        				'.(Url::get('room_level_id')?' AND room.room_level_id = '.Url::iget('room_level_id').'':'').'
                        and room.close_room=1
                        AND '.$cond.'
        			order by
                        room_level.is_virtual, 
                        room_level_position,
                        room.position,
                        --room.room_level_id asc,
        				--room.floor, 
                        room.name,
                        room.id
                        
        		';
        		$rooms = DB::fetch_all($sql);
            }
               
        }
        else
        {
            $sql = '
    			select 
    				distinct room.id,
    				room.name,
    				room.floor,
    				room_level.price,
    				CONCAT(room_level.brief_name,CONCAT(\' / \',room_type.brief_name)) AS type_name,
    				0 AS overdue_reservation_id,
    				\'\' as house_status,
    				\'AVAILABLE\' as status,
    				\'\' AS note,
    				\'\' AS hk_note,
    				minibar.id as minibar_id,
    				0 as confirm,
    				0 as extra_bed,
    				0 as out_of_service,
    				1 as can_book,
    				room.position,
    				room.room_level_id,
    				room.room_type_id,
    				room_level.is_virtual,
    				room_level.brief_name as room_level_name,
    				\'\' as note,
                    room_type.id as room_type,
                    nvl(room_level.position,0) as room_level_position
    			from
    				room
    				inner join room_level on room_level.id = room.room_level_id
    				inner join room_type on room_type.id = room_type_id 
    				left outer join minibar on room.id = minibar.room_id 
    			where
    				room.portal_id = \''.PORTAL_ID.'\'
    				'.(Url::get('room_level_id')?' AND room.room_level_id = '.Url::iget('room_level_id').'':'').'
                    AND '.$cond.'
    			order by
                    room_level.is_virtual, 
                    room_level_position,
                    room.position,
                    --room.room_level_id asc,
    				--room.floor,
                    room.name, 
                    room.id
                    
    		';
    		$rooms = DB::fetch_all($sql); 
        }
        
		return sizeof($rooms);
	}
    function room_level_check_conflict_real($arr)
    {
        $arr_1 = $arr[1];
        $arr_2 = $arr[2];
        $days = array();
        if($arr[1] > time())
        {
            $arr[1] = $arr[1];
        }
        else
        {
            $arr[1] = Date_Time::to_time(date('d/m/Y'));
        }
        $min_date = $arr[2];
        $max_date = 0;
    	for($i = $arr[1];$i < $arr[2];$i = $i + 24*3600)
        {
    		$days[$i]['id'] = $i;
    		$days[$i]['value'] = date('d/m/Y',$i);
            $days[$i]['check'] = 0;
            if($min_date>$i)
                $min_date = $i;
            if($max_date<$i)
                $max_date = $i;
    	}
        $arr[1] = $arr_1;
        $arr[2] = $arr_2;
        /** lay danh sach phong cua loai phong */
        $list_room = DB::fetch_all("SELECT room.id,room.name from room where room.room_level_id=".$arr[0]);
        //if($arr[0]==47)
        //System::debug($list_room);
        $count_room_level = sizeof($list_room);
        foreach($list_room as $k=>$v)
        {
            $list_room[$k]['day'] = array();
        }
        
        /** lay danh sach dat phong cua hang phong nay trong khoang thoi gian can xet **/
    	$room_status = array();
    	$sql = '
            SELECT 
            	r.portal_id,
                r.id as r_id,
                rs.id,
                rr.status,
                rr.time_in,
                rr.time_out,
                rr.arrival_time,
                rr.departure_time,
                rs.in_date,
                rr.room_level_id,
                rr.id as rr_id,
                rr.room_id
            FROM
            	room_status rs
            	LEFT OUTER JOIN reservation_room rr ON rs.reservation_room_id = rr.id
            	LEFT OUTER JOIN reservation r ON rr.reservation_id = r.id 
            WHERE
            	(
                rs.status <> \'CANCEL\' and rs.status <> \'NOSHOW\'
                ) 
                AND rr.room_level_id='.$arr[0].' AND rs.in_date>=\''.Date_Time::to_orc_date(date('d/m/Y',$arr[1])).'\' and rs.in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$arr[2])).'\'
                AND rr.room_id is not null
            ORDER BY
            	rr.room_level_id
            ';	
        $room_status_asign = DB::fetch_all($sql);
        $sql_repair = '
            SELECT 
            	r.portal_id,
                rs.id,
                rr.status,
                rr.time_in,
                rr.time_out,
                rs.start_date as arrival_time,
                rs.end_date as departure_time,
                rs.in_date,
                rs.house_status,
                rr.room_level_id,
                rr.id as rr_id,
                r.id as r_id,
                rs.room_id
            FROM
            	room_status rs
            	LEFT OUTER JOIN reservation_room rr ON rs.reservation_room_id = rr.id
            	LEFT OUTER JOIN reservation r ON rr.reservation_id = r.id 
                left join room on rs.room_id = room.id 
            WHERE
            	(
                rs.house_status = \'REPAIR\'
                ) 
                AND room.room_level_id='.$arr[0].' AND rs.in_date>=\''.Date_Time::to_orc_date(date('d/m/Y',$arr[1])).'\' and rs.in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$arr[2])).'\'
             ORDER BY
            	rs.id
                    ';
                    
             $room_repair = DB::fetch_all($sql_repair);
             
             $room_status_asign = $room_status_asign+$room_repair;
            //System::debug($room_status_asign);
        
        foreach($room_status_asign as $k1=>$v1)
        {
            $room_status_asign[$k1]['date'] = Date_Time::to_time(Date_Time::convert_orc_date_to_date($v1['in_date'],"/"));
            if( ( ($v1['in_date']!=$v1['departure_time'] AND $v1['departure_time']!=$v1['arrival_time']) OR ($v1['in_date']==$v1['departure_time'] AND $v1['departure_time']==$v1['arrival_time'] AND Date_Time::convert_orc_date_to_date($v1['in_date'],'/')!=date('d/m/Y',$arr[1])) ) AND ( Date_Time::to_time(date('d/m/Y',$arr[2]))>Date_Time::to_time(Date_Time::convert_orc_date_to_date($v1['in_date'],'/')) ))
            {
                $list_room[$v1['room_id']]['day'][$room_status_asign[$k1]['date']]['id'] = $room_status_asign[$k1]['date'];
                $list_room[$v1['room_id']]['day'][$room_status_asign[$k1]['date']]['value'] = date('d/m/Y',$room_status_asign[$k1]['date']);
            }
        }
        
//        if($arr[0]==47)
//            System::debug($list_room);
        $orcl = '
            SELECT 
            	r.portal_id,
                rs.id,
                rr.status,
                rr.time_in,
                rr.time_out,
                rr.arrival_time,
                rr.departure_time,
                rs.in_date,
                rs.house_status,
                rr.room_level_id,
                rr.id as rr_id,
                r.id as r_id,
                rr.room_id
            FROM
            	room_status rs
            	LEFT OUTER JOIN reservation_room rr ON rs.reservation_room_id = rr.id
            	LEFT OUTER JOIN reservation r ON rr.reservation_id = r.id 
            WHERE
            	(
                rs.status <> \'CANCEL\' and rs.status <> \'NOSHOW\'
                ) 
                AND rr.room_level_id='.$arr[0].' AND rs.in_date>=\''.Date_Time::to_orc_date(date('d/m/Y',$arr[1])).'\' and rs.in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$arr[2])).'\'
                AND rr.room_id is  null
            ORDER BY
            	rr.id
            ';
        //echo $orcl;
        $room_status_un_asign = DB::fetch_all($orcl);
        $reservation_room_un_asign = array();
        foreach($room_status_un_asign as $k2=>$v2)
        {
            if( ( ($v2['in_date']!=$v2['departure_time'] AND $v2['departure_time']!=$v2['arrival_time']) OR ($v2['in_date']==$v2['departure_time'] AND $v2['departure_time']==$v2['arrival_time']) ) AND (Date_Time::to_orc_date(date('d/m/Y',$arr[2]))!=$v2['in_date']) AND $v2['room_id']=='')
            {
                if(Date_Time::to_time(Date_Time::convert_orc_date_to_date($v2['arrival_time'],"/"))<$min_date)
                    $v2['arrival_time'] = $min_date;
                else
                    $v2['arrival_time'] = Date_Time::to_time(Date_Time::convert_orc_date_to_date($v2['arrival_time'],"/"));
                
                if(Date_Time::to_time(Date_Time::convert_orc_date_to_date($v2['departure_time'],"/"))>$max_date)
                    $v2['departure_time'] = $max_date;
                else
                    $v2['departure_time'] = Date_Time::to_time(Date_Time::convert_orc_date_to_date($v2['departure_time'],"/")) - (24*3600);
                $count_date =  ($v2['departure_time'] - $v2['arrival_time'])/(24*3600);
                $v2['time_in'] = date('d/m/Y',$v2['arrival_time']);
                $v2['time_out'] = date('d/m/Y',$v2['departure_time']);
                $reservation_room_un_asign[$v2['rr_id']] = $v2;
                $reservation_room_un_asign[$v2['rr_id']]['id'] = $count_date.$v2['id'];$reservation_room_un_asign[$v2['rr_id']]['id']+=0;
                $reservation_room_un_asign[$v2['rr_id']]['count'] = $count_date;
            }
        }
        rsort($reservation_room_un_asign);
        foreach($reservation_room_un_asign as $k3=>$v3)
        {
            $check = false;
            foreach($list_room as $key=>$value)
            {
                if(sizeof($value['day'])>0)
                {
                    $check_sort = true;
                    foreach($value['day'] as $key1=>$value1)
                    {
                        if($value1['id']<=$v3['departure_time'] AND $value1['id']>=$v3['arrival_time'])
                        {
                            $check_sort = false;
                        }
                    }
                    if($check_sort==true)
                    {
                        for($j=$v3['arrival_time'];$j<=$v3['departure_time'];$j+=(24*3600))
                        {
                            $list_room[$key]['day'][$j]['id'] = $j;
                            $list_room[$key]['day'][$j]['value'] = date('d/m/Y',$j);
                        }
                        $check = true;
                        break;
                    }
                }
            }
            
            if($check==false)
            {
                foreach($list_room as $key=>$value)
                {
                    $check_sort = true;
                    foreach($value['day'] as $key1=>$value1)
                    {
                        if($value1['id']<=$v3['departure_time'] AND $value1['id']>=$v3['arrival_time'])
                        {
                            $check_sort = false;
                        }
                    }
                    if($check_sort==true)
                    {
                        for($j=$v3['arrival_time'];$j<=$v3['departure_time'];$j+=(24*3600))
                        {
                            $list_room[$key]['day'][$j]['id'] = $j;
                            $list_room[$key]['day'][$j]['value'] = date('d/m/Y',$j);
                        }
                        break;
                    }
                }
            }
            
        }
        $quantity_room = 0;
        foreach($list_room as $id=>$content)
        {
            if(sizeof($content['day'])==0)
            {
                $quantity_room += 1;
            }
        }
    	return $quantity_room;
    }
}
?>
