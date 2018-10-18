<?php
class YearlyRevenueReportForm extends Form
{
	function YearlyRevenueReportForm()
	{
		Form::Form('YearlyRevenueReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
	{
	   
        $this->map = array();
        $this->map['year'] = Url::get('year')?Url::get('year'):date('Y');
        $_REQUEST['year'] = $this->map['year'];
        
        $cond =' 1=1 ';
        
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
        if(Url::get('portal_id'))
        {
            $portal_id = Url::get('portal_id');
        }
        else
        {
            $portal_id = PORTAL_ID;
            $_REQUEST['portal_id'] = PORTAL_ID;                       
        }
        
        if($portal_id != 'ALL')
        {
            $cond.=' AND reservation.portal_id = \''.$portal_id.'\' '; 
        }
        
        //Bat dau cua nam hien tai
        $start_date = Date_Time::to_orc_date('1/1/'.$this->map['year']);
        //Ket thuc cua nam hien tai = chinh ngay xem bc
        $end_date = Date_Time::to_orc_date(date('d/m/Y'));
        
        
		$cond .= ' AND ( reservation_room.status = \'CHECKOUT\' AND reservation_room.departure_time <=\''.$end_date.'\' AND reservation_room.departure_time >=\''.$start_date.'\'  ) ';
        
        //System::debug($cond);
        
        $max_room = DB::fetch('Select count(room.id) as num from room_level inner join room on room_level.id = room.room_level_id Where room.portal_id = \''.PORTAL_ID.'\' and room_level.is_virtual = 0 ','num');
        //echo $max_room;
        //Tong so phong kinh doanh trong 1 nam
        $total_room = 0;
        $data = array('JAN'=>array(),'FEB'=>array(),'MAR'=>array(),'APR'=>array(),'MAY'=>array(),'JUN'=>array(),'JUL'=>array(),'AUG'=>array(),'SEP'=>array(),'OCT'=>array(),'NOV'=>array(),'DEC'=>array());
        $i=1;
        foreach($data as $k=>$v)
        {
            $data[$k]['num_guest'] = array('FIT'=>0,'GIT'=>0,'HK'=>0);
            $data[$k]['day_guest'] = array('FIT'=>0,'GIT'=>0,'HK'=>0);
            //$data[$k]['month'] =$i;
            $data[$k]['month'] =$k;
            $data[$k]['max_date'] = Date_Time::day_of_month($i,$this->map['year']);
            $data[$k]['start_date'] = Date_Time::to_orc_date('1/'.$i.'/'.$this->map['year']);
            $data[$k]['end_date'] = Date_Time::to_orc_date($data[$k]['max_date'].'/'.$i.'/'.$this->map['year']);
            $data[$k]['max_room'] = $max_room * $data[$k]['max_date'];
            $total_room += $data[$k]['max_room'];
            $i++;
        }
        //echo $total_room;
        
        //System::debug($data);
        
        /**
         * Dem so luot khach, so dem khach
         */
        
        $sql = 'select
                    ROW_NUMBER() OVER (ORDER BY reservation_room.id desc) as id,
                    reservation.id as reservation_id,
                    reservation_room.id as reservation_room_id,
                    reservation_traveller.id as reservation_traveller_id,
                    traveller.id as traveller_id,
                    guest_type.id as guest_type_id,
                    room.name as room_name,
                    traveller.first_name || \' \' || traveller.last_name as fullname,
                    reservation_room.departure_time,
                    reservation_room.time_out,
                    DECODE
                    (
                        customer.group_id,  \'TOURISM\', customer.id,
                                            \'\'
                    ) as customer_id,
                    customer.name as customer_name,
                    reservation_traveller.status as reservation_traveller_status,
                    reservation_traveller.arrival_date as r_t_arrival_date,
                    reservation_traveller.departure_date as r_t_departure_date,
                    COALESCE(reservation_room.verify_dayuse,0)/10 AS verify_dayuse,
                    (reservation_traveller.departure_date - reservation_traveller.arrival_date) + COALESCE(reservation_room.verify_dayuse,0)/10 as day_guest
                from 
                    reservation_room 
                    inner join reservation on reservation.id = reservation_room.reservation_id
                    inner join room on reservation_room.room_id = room.id
                    left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                    left outer join traveller on reservation_traveller.traveller_id = traveller.id
                    left outer join country on traveller.nationality_id = country.id
                    left outer join customer on reservation.customer_id = customer.id
                    left outer join guest_type on traveller.traveller_level_id = guest_type.id
                where 
                    '.$cond.' 
                order by 
                    reservation_room.id DESC';
        //echo $sql;
		$items = DB::fetch_all($sql);
        
        //System::debug($items);
        
        $summary = array(
                        'GUEST_FIT'=>0,
                        'GUEST_GIT'=>0,
                        'GUEST_HK'=>0,
                        'DAY_FIT'=>0,
                        'DAY_GIT'=>0,
                        'DAY_HK'=>0,
                        );
        
        //Duyet qua tung khach
        foreach($items as $k=>$v)
        {
            if($v['fullname']!= ' ')
            {
                //gio den bang gio di thi van tinh la 1 dem
                if($v['r_t_arrival_date']==$v['r_t_departure_date'])
                    $items[$k]['day_guest']=$items[$k]['day_guest']+1;
                //duyet qua tung thang
                foreach($data as $key=>$value)
                {
                    //Neu ngay out cua khach nam trong thang
                    if($v['time_out']<= strtotime($value['end_date']) && $v['time_out']>= strtotime($value['start_date']) )
                    {
                        //Khach hang khong
                        if($v['guest_type_id']==2)
                        {
                            $data[$key]['num_guest']['HK']++;
                            $summary['GUEST_HK']++;
                            $data[$key]['day_guest']['HK'] += $items[$k]['day_guest'];
                            $summary['DAY_HK'] += $items[$k]['day_guest'];
                        }   
                        //Khach doan
                        if($v['customer_id']!='')
                        {
                            $data[$key]['num_guest']['GIT']++;
                            $summary['GUEST_GIT']++;
                            $data[$key]['day_guest']['GIT']+= $items[$k]['day_guest'];
                            $summary['DAY_GIT']+= $items[$k]['day_guest'];
                        }
                        else //Khach le
                        {
                            $data[$key]['num_guest']['FIT']++;
                            $summary['GUEST_FIT']++;
                            $data[$key]['day_guest']['FIT']+= $items[$k]['day_guest'];
                            $summary['DAY_FIT']+= $items[$k]['day_guest'];
                        }
                        break;
                    }
                }
            }

                
        }
        //System::debug($data);
        //System::debug($summary);
        $this->map['data'] = $data;
        $this->map['summary'] = $summary;
        //den day da co mang data ve so khach HK,GIF, FIT, ngay khach 
        
        
        /**
         * Tinh doanh thu phong
         */
        //Tong doanh thu 
        $total_room_revenue = 0;
        $total_room_revenue_USD = 0;
        //Tong so dem phong
        $total_room_night = 0;
        $items = $this->get_data($cond);
        //System::debug($items);
        
        //Doanh thu dien thoai
        $total_telephone_revenue = 0;
        foreach($items as $key=>$value)
        {
            //tru di 1 ngay de ra so night, neu 2 truong nay bang nhau thi van tinh la 1 ngay
            if($value['arrival_time']!=$value['departure_time'])
                $items[$key]['night']=$items[$key]['night']-1;
            
            $items[$key]['total_after'] = $this->calc_money($value['net_price'],$value['total'],$value['reduce_balance'],$value['reduce_amount'],$value['service_rate'],$value['tax_rate']);
            
            $items[$key]['total_after_USD'] = $items[$key]['total_after']/$value['exchange_rate'];
            $total_room_night += $items[$key]['night'];
            $total_room_revenue += $items[$key]['total_after'];
            $total_room_revenue_USD += $items[$key]['total_after_USD'];
            
            
            $total_telephone_revenue += DB::fetch('
                                                    Select 
                                                        SUM(telephone_report_daily.price) as total
                                                    FROM
                                                        telephone_report_daily
                                                    WHERE
                                                        phone_number_id = \''.$value['phone_number'].'\'
                                                        and hdate >= '.$value['time_in'].'
                                                        and hdate <= '.$value['time_out'].'
                                                    ','total');
        }
        //echo $total_telephone_revenue;
        //cong suat TB = so dem phong/tong so phong kinh doanh
        $average_occupancy = $total_room_night*100/$total_room;
        //gia phong TB = tong doanh thu / tong so dem phong
        $average_room_price = $total_room_revenue/($total_room_night?$total_room_night:1);
        $average_room_price_USD = $total_room_revenue_USD/($total_room_night?$total_room_night:1);
        //echo $average_occupancy.'<br />';
        //echo $average_room_price.'<br />';
        //echo $average_room_price_USD.'<br />';
        //echo $total_room_night.'<br />';
        //echo $total_room_revenue.'<br />';
        //System::debug($items);
        //System::debug(get_defined_constants());
        /**
         * Tinh doanh thu nha hang
         */
        $total_res_revenue = DB::fetch('Select 
                                            SUM(total) as total 
                                        from 
                                            bar_reservation 
                                        where 
                                            status = \'CHECKOUT\' 
                                            and portal_id = \''.PORTAL_ID.'\' 
                                            and FROM_UNIXTIME(bar_reservation.time_out) >= \''.$start_date.'\' 
                                            and FROM_UNIXTIME(bar_reservation.time_out) <= \''.$end_date.'\' '
                                        ,'total');
        //echo $total_res_revenue;
        
        /**
         * Tinh phi van chuyen
         */
                       
        $num_pickup = DB::fetch(
                                'select
                                        COUNT(reservation_traveller.id) as num_pickup
                                    from 
                                        reservation_room 
                                        inner join reservation on reservation.id = reservation_room.reservation_id
                                        inner join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                                    where
                                        '.$cond.' 
                                        and reservation_traveller.status != \'CHANGE\'
                                        and reservation_traveller.pickup = 1
                                        and reservation_traveller.pickup_foc = 1
                                ','num_pickup');
        
        $num_see_off = DB::fetch(
                                'select
                                        COUNT(reservation_traveller.id) as num_see_off
                                    from 
                                        reservation_room 
                                        inner join reservation on reservation.id = reservation_room.reservation_id
                                        inner join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                                    where
                                        '.$cond.' 
                                        and reservation_traveller.status != \'CHANGE\'
                                        and reservation_traveller.see_off = 1
                                        and reservation_traveller.see_off_foc = 1
                                ','num_see_off');
        //echo $num_pickup.'<br />';
        //echo $num_see_off.'<br />';
        //echo PICKUP_PRICE;
        $total_transport_revenue = ($num_pickup+$num_see_off)* PICKUP_PRICE;
        
        /**
         * Tinh doanh thu khac (housekeeping invoice) va extra_service_invoice
         */
         
        $housekeeping_revenue = DB::fetch('
                                Select 
                                    SUM(housekeeping_invoice.total) as total
                                FROM
                                    housekeeping_invoice
                                    inner join reservation_room on reservation_room.id = housekeeping_invoice.reservation_room_id
                                    inner join reservation on reservation.id = reservation_room.reservation_id
                                WHERE
                                    '.$cond.'
                                ','total');
        
        $extra_service_revenue = DB::fetch('
                                Select 
                                    SUM(extra_service_invoice.total_amount) as total
                                FROM
                                    extra_service_invoice
                                    inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                                    inner join reservation on reservation.id = reservation_room.reservation_id
                                WHERE
                                    '.$cond.'
                                ','total');
        //echo $housekeeping_revenue."<br />";
        //echo $extra_service_revenue."<br />";
        $other_revenue = $housekeeping_revenue + $extra_service_revenue;
        /**
         * Tinh doanh thu dien thoai (da tinh o tren)
         */
        
        $total_other_revenue = $other_revenue + $total_transport_revenue + $total_telephone_revenue;
        
        $total_revenue = $total_room_revenue + $total_res_revenue + $total_other_revenue;
                                          
        /**
         * Lay cac ke hoach duoc dinh san
         */
        $this->map['plan'] = DB::fetch_all('Select id, code, name_'.Portal::language().' as name, value, currency_id From plan where portal_id = \''.PORTAL_ID.'\' and year = '.$this->map['year'].' order by position');
        //System::debug($this->map['plan']);
        $this->parse_layout('report',$this->map+array(
                                                       'total_room_revenue'=>$total_room_revenue,
                                                       'average_occupancy'=>$average_occupancy,
                                                       'average_room_price'=>$average_room_price,
                                                       'average_room_price_USD'=>$average_room_price_USD,  
                                                       'total_res_revenue'=>$total_res_revenue,
                                                       'total_transport_revenue'=>$total_transport_revenue,
                                                       'other_revenue'=>$other_revenue,
                                                       'total_telephone_revenue'=>$total_telephone_revenue,
                                                       'total_other_revenue'=>$total_other_revenue,
                                                       'total_revenue'=>$total_revenue
                                                    )
                            );	
	}
    
    //join ca phone number de tinh tien dien thoai
    function get_data($cond)
    {
		$sql='
				SELECT
                    reservation_room.id,
                    reservation_room.departure_time,
                    reservation_room.arrival_time,
                    reservation_room.time_in,
                    reservation_room.time_out,
                    SUM(room_status.change_price) + ( COALESCE(reservation_room.verify_dayuse,0)/10 * reservation_room.price   ) as total,
                    reservation_room.net_price,
                    reservation_room.tax_rate,
                    reservation_room.service_rate,
                    COALESCE(reservation_room.reduce_balance,0) as reduce_balance,
                    COALESCE(reservation_room.reduce_amount,0) as reduce_amount,
                    COUNT(room_status.id) + COALESCE(reservation_room.verify_dayuse,0)/10 as night,
                    reservation_room.exchange_rate,
                    telephone_number.phone_number
				FROM
                    reservation_room 
                    inner join reservation on reservation.id = reservation_room.reservation_id
                    left  join room_status on room_status.reservation_room_id = reservation_room.id
                    inner join room on room.id = reservation_room.room_id
                    left  join telephone_number on telephone_number.room_id = room.id
				WHERE
					'.$cond.'
				GROUP BY
                    reservation_room.id,
                    reservation_room.arrival_time,
                    reservation_room.departure_time,
                    reservation_room.time_in,
                    reservation_room.time_out,
                    reservation_room.net_price,
                    reservation_room.tax_rate,
                    reservation_room.service_rate,
                    reservation_room.reduce_balance,
                    reservation_room.reduce_amount,
                    reservation_room.verify_dayuse,
                    reservation_room.price,
                    reservation_room.exchange_rate,
                    telephone_number.phone_number
				ORDER BY
					reservation_room.id DESC
					
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
}
?>