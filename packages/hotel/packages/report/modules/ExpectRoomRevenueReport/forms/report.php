<?php
class ExpectRoomRevenueReportForm extends Form
{
    function ExpectRoomRevenueReportForm()
    {
        Form::Form('ExpectRoomRevenueReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
    }
    
    function draw()
    {
        /**
         * current : cho nam/thang hien tai : chi tinh thuc thu den thoi diem hien tai
         * next : cho nam/thang sau : tinh doanh thu theo book
         */
        $this->map = array();
        $current_cond = '1=1';
        $next_cond = '1=1';
        if(Url::check('by_year') or Url::check('by_month'))
        {
            //xem theo nam
            if(Url::check('by_year'))
            {
                //tieu de cua the? <th>
                $this->map['header_1'] = Portal::language('room_revenue').' '.Portal::language('year').' '.Url::get('year');
                //nam hien tai
                $current_time = Url::get('year');
                //So ngay cua nam hien tai
                $total_current_day = $this->calc_total_day($current_time,false,'YEAR');
                //Bat dau cua nam hien tai
                $start_current_time = Date_Time::to_orc_date('1/1/'.$current_time);
                //Ket thuc cua nam hien tai = chinh ngay xem bc
                $end_current_time = Date_Time::to_orc_date(date('d/m/Y'));
    			$current_cond .= ' AND ( reservation_room.status = \'CHECKOUT\' AND reservation_room.departure_time <=\''.$end_current_time.'\' AND reservation_room.departure_time >=\''.$start_current_time.'\'  ) ';
                //System::debug($current_cond);
                
                //Nam tiep theo
                $next_time = $current_time+1;
                $total_next_day = $this->calc_total_day($next_time,false,'YEAR');
                $start_next_time = Date_Time::to_orc_date('1/1/'.$next_time);
                $end_next_time = Date_Time::to_orc_date('31/12/'.$next_time);
    			$next_cond .= ' AND  (
                                        ( reservation_room.arrival_time <=\''.$end_next_time.'\' AND reservation_room.arrival_time >=\''.$start_next_time.'\' )
                                        OR
                                        ( reservation_room.departure_time <=\''.$end_next_time.'\' AND reservation_room.departure_time >=\''.$start_next_time.'\' )
                                    ) ';
                //System::debug($next_cond);
                
                $this->map['title'] = Portal::language('expect_room_revenue').' '.Portal::language('year').' '.$next_time;
                $this->map['header_2'] = $this->map['title'];
            }
            else //Xem bc theo th�ng
            {
                $this->map['header_1'] = Portal::language('room_revenue').' '.Portal::language('month').' '.Url::get('month').'/'.Url::get('year');
                //thang hien tai
                $current_time = Url::get('month');
                $total_current_day = $this->calc_total_day(Url::get('year'),$current_time,'MONTH');
                //ngay bat dat dau thang hien tai
                $start_current_time = Date_Time::to_orc_date('1/'.$current_time.'/'.Url::get('year'));
                //ngay ket thuc thang hien tai
                //Neu la thang hien tai
                if($current_time==date('m',time()))
                    $end_current_time = Date_Time::to_orc_date(date('d/m/Y'));
                else
                    $end_current_time = Date_Time::to_orc_date(Date_Time::day_of_month($current_time,Url::get('year')).'/'.$current_time.'/'.Url::get('year').'');
    			$current_cond .= ' AND ( reservation_room.status = \'CHECKOUT\' AND reservation_room.departure_time <=\''.$end_current_time.'\' AND reservation_room.departure_time >=\''.$start_current_time.'\'  ) ';
                //thang tiep theo (xay ra truong hop thang 12 + 1= 13)
                $next_time = $current_time+1;
                $year = Url::get('year');
                //Neu dang xem thang 12
                if($next_time==13)
                {
                    $next_time = 1;
                    $year++;
                }
                $total_next_day = $this->calc_total_day($year,$next_time,'MONTH');
                $start_next_time = Date_Time::to_orc_date('1/'.$next_time.'/'.$year);
                $end_next_time = Date_Time::to_orc_date(Date_Time::day_of_month($next_time,$year).'/'.$next_time.'/'.$year.'');
    			$next_cond .= ' AND  reservation_room.arrival_time <=\''.$end_next_time.'\' AND reservation_room.arrival_time >=\''.$start_next_time.'\' ';
                
                //System::debug($current_cond);  
                //System::debug($next_cond);  
                $this->map['title'] = Portal::language('expect_room_revenue').' '.Portal::language('month').' '.$next_time.'/'.$year;
                $this->map['header_2'] = $this->map['title'];
            }
                
            $this->room_level = DB::fetch_all('Select 
                                                room_level.id,
                                                room_level.name,
                                                count(room.id) as num_room
                                            From
                                                room_level
                                                inner join room on room_level.id = room.room_level_id
                                            Where
                                                room.portal_id = \''.PORTAL_ID.'\'
                                                and room_level.is_virtual = 0
                                            Group by
                                                room_level.id,
                                                room_level.name
                                            Order by
                                                room_level.id    
                                            ');
            //System::debug($this->room_level);
            $i=1;                            
            //khoi tao cac gia tri                                       
            foreach($this->room_level as $key=>$value)
            {
                $this->room_level[$key]['stt']=$i++;
                $this->room_level[$key]['max_num_room_current'] = $value['num_room'] * $total_current_day;
                $this->room_level[$key]['real_num_room_current'] = 0;
                $this->room_level[$key]['num_night_current'] = 0;
                $this->room_level[$key]['revenue_current'] = 0;
                $this->room_level[$key]['max_num_room_next'] = $value['num_room'] * $total_next_day;
                $this->room_level[$key]['real_num_room_next'] = 0;
                $this->room_level[$key]['num_night_next'] = 0;
                $this->room_level[$key]['revenue_next'] = 0;
            }
            //System::debug($room_type);
            
            //du lieu hien tai
            //System::debug($current_cond);
            $data_current = $this->get_data($current_cond);
            //System::debug($data_current);
            $this->calc_data($data_current,'current');
            
            
            //System::debug($data_current);
            //System::debug($next_cond);
            //du lieu nam sau
            $data_next = $this->get_data($next_cond);
            
            $this->calc_data($data_next,'next');
            //System::debug($data_next);
            
            $summary=array(
                            'real_num_room_current'=>0,
                            'real_max_room_current'=>0,
                            'occupancy_current'=>0,
                            'num_night_current'=>0,
                            'average_price_current'=>0,
                            'revenue_current'=>0,
                            'real_num_room_next'=>0,
                            'real_max_room_next'=>0,
                            'occupancy_next'=>0,
                            'num_night_next'=>0,
                            'average_price_next'=>0,
                            'revenue_next'=>0,
                        );
            
            $count_room_level = count($this->room_level);
            
            foreach($this->room_level as $key=>$value)
            {
                $this->room_level[$key]['occupancy_current'] = $value['real_num_room_current']*100/$value['max_num_room_current'];
                $this->room_level[$key]['occupancy_next'] = $value['real_num_room_next']*100/$value['max_num_room_next'];
                //tranh chia cho 0
                $this->room_level[$key]['average_price_current'] = round($value['revenue_current']/($value['num_night_current']?$value['num_night_current']:1));
                $this->room_level[$key]['average_price_next'] = round($value['revenue_next']/($value['num_night_next']?$value['num_night_next']:1));
                
                //tong phong
                $summary['real_num_room_current']+=$value['real_num_room_current'];
                $summary['real_num_room_next']+=$value['real_num_room_next'];
                //tong phong toi da
                $summary['real_max_room_current']+=$value['max_num_room_current'];
                $summary['real_max_room_next']+=$value['max_num_room_next'];
                //tong ngay
                $summary['num_night_current']+=$value['num_night_current'];
                $summary['num_night_next']+=$value['num_night_next'];
                //tong doanh thu
                $summary['revenue_current']+=$value['revenue_current'];
                $summary['revenue_next']+=$value['revenue_next'];
                /*
                //cong suat TB phong = cong suat tung loai phong / tong so loai phong (cong don : (a+b)/10 = a/10 + b/10)
                $summary['occupancy_current']+=$this->room_type[$key]['occupancy_current']/$count_room_type;
                $summary['occupancy_next']+=$this->room_type[$key]['occupancy_next']/$count_room_type;
                //Gia TB phong = gia TB tung loai phong / tong so loai phong
                $summary['average_price_current']+=$this->room_type[$key]['average_price_current']/$count_room_type;
                $summary['average_price_next']+=$this->room_type[$key]['average_price_next']/$count_room_type;
                */
                
            }
            $summary['average_price_current']=round($summary['revenue_current']/($summary['num_night_current']?$summary['num_night_current']:1));
            $summary['average_price_next']=round($summary['revenue_next']/($summary['num_night_next']?$summary['num_night_next']:1));
            
            $summary['occupancy_current']=$summary['real_num_room_current']*100/$summary['real_max_room_current'];
            $summary['occupancy_next']=$summary['real_num_room_next']*100/$summary['real_max_room_next'];
            
            //System::debug($this->room_type);
            //System::debug($summary);
            $this->map['summary']=$summary;
            $this->map['room_level']=$this->room_level;
            $this->parse_layout('report',$this->map);
        }
        else
        {
            $this->parse_layout('search',$this->map);    
        }
         
    }
    
    //tong so ngay trong nam
    function calc_total_day($year,$month,$by)
    {
        $total_day = 0;
        if($by=='YEAR')
        {
            for($i = 1; $i <=12; $i++)
            {
                $total_day += Date_Time::day_of_month($i,$year);
            }
        }
        else
        {
            $total_day += Date_Time::day_of_month($month,$year);
        }
        return $total_day;
    }
    
    function get_data($cond)
    {
		$sql='
				SELECT
                    reservation_room.id,
                    reservation_room.departure_time,
                    reservation_room.arrival_time,
                    SUM(room_status.change_price) + ( COALESCE(reservation_room.verify_dayuse,0)/10 * reservation_room.price   ) as total,
                    reservation_room.net_price,
                    reservation_room.tax_rate,
                    reservation_room.service_rate,
                    COALESCE(reservation_room.reduce_balance,0) as reduce_balance,
                    COALESCE(reservation_room.reduce_amount,0) as reduce_amount,
                    room_level.name as room_level_name,
                    room.name as room_name,
                    COUNT(room_status.id) + COALESCE(reservation_room.verify_dayuse,0)/10 as night,
                    room_level.id as room_level_id
				FROM
                    reservation_room 
                    inner join reservation on reservation.id = reservation_room.reservation_id
                    left  join room_status on room_status.reservation_room_id = reservation_room.id
                    inner join room on reservation_room.room_id = room.id
                    inner join room_level on room.room_level_id = room_level.id
				WHERE
					'.$cond.'
                    and room_level.is_virtual = 0
				GROUP BY
                    reservation_room.id,
                    reservation_room.arrival_time,
                    reservation_room.departure_time,
                    reservation_room.net_price,
                    reservation_room.tax_rate,
                    reservation_room.service_rate,
                    reservation_room.reduce_balance,
                    reservation_room.reduce_amount,
                    reservation_room.verify_dayuse,
                    reservation_room.price,
                    room_level.name,
                    room.name,
                    room_level.id
				ORDER BY
					reservation_room.id
					
		';
        //System::debug($sql);
        $items = DB::fetch_all($sql);
        return $items;
    }
    
    //tinh tien sau thue, charge
    function calc_money($net_price,$total,$reduce_balance,$reduce_amount,$service_rate,$tax_rate)
    {
        $total_after = 0;
        //gia phong chua bao gom tax and charge
        if($net_price==0)
        {
            $total_after = $total * (1-$reduce_balance/100);
            $total_after = $total_after - $reduce_amount;
            $total_after = $total_after * (1+$service_rate/100);
            $total_after = $total_after * (1+$tax_rate/100);
        }
        else//da bao gom tax va charge => chia nguoc lai, tru tiep giam gia, roi nhan lai tax va rate
        {
            $total_after = $total / (1+$tax_rate/100);
            $total_after = $total_after / (1+$service_rate/100);
            $total_after = $total_after * (1-$reduce_balance/100);
            $total_after = $total_after - $reduce_amount;
            $total_after = $total_after * (1+$service_rate/100);
            $total_after = $total_after * (1+$tax_rate/100);
        }
        return $total_after;
    }
    
    //tinh toan du lieu bao cao, type = current or next
    function calc_data($items,$type)
    {
        foreach($items as $key=>$value)
        {
            //tru di 1 ngay de ra so night, neu 2 truong nay bang nhau thi van tinh la 1 ngay
            if($value['arrival_time']!=$value['departure_time'])
                $items[$key]['night']=$items[$key]['night']-1;
            
            $items[$key]['total_after'] = $this->calc_money($value['net_price'],$value['total'],$value['reduce_balance'],$value['reduce_amount'],$value['service_rate'],$value['tax_rate']);
            
            if(isset($this->room_level[$value['room_level_id']]))
            {
                $this->room_level[$value['room_level_id']]['real_num_room_'.$type]++;
                $this->room_level[$value['room_level_id']]['num_night_'.$type]+=$items[$key]['night'];
                $this->room_level[$value['room_level_id']]['revenue_'.$type]+=$items[$key]['total_after'];
                $this->room_level[$value['room_level_id']]['revenue_'.$type] = round($this->room_level[$value['room_level_id']]['revenue_'.$type]);
            } 
        }
    }
}
?>