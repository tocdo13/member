<?php
class WeeklyForecastForm extends Form
{
    function WeeklyForecastForm()
    {
        Form::Form('WeeklyForecastForm');   
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
    }
    
    function draw()
    {
		$this->map = array();
        $this->map['from_date'] =Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
        $_REQUEST['from_date'] = $this->map['from_date'];
        $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):(date('d/m/Y',Date_Time::to_time($this->map['from_date'])+604800)); //cong vao 1 tuan le 
        $_REQUEST['to_date'] = $this->map['to_date'];
        
        $date_from = Date_Time::to_orc_date($this->map['from_date']);
		$date_to = Date_Time::to_orc_date($this->map['to_date']);

		$this->map['from_date'] = Date_Time::convert_orc_date_to_date($date_from,'/');
		$this->map['to_date'] = Date_Time::convert_orc_date_to_date($date_to,'/');

        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
        $portal_id ='';
        $cond ='';
        $cond_invoice = '';
        if(Url::get('portal_id'))
        {
            $portal_id = Url::get('portal_id');
            if($portal_id != 'ALL')
            {
                $cond.=' AND reservation.portal_id = \''.$portal_id.'\' ';
                $cond_invoice = " AND extra_service_invoice.portal_id = '".$portal_id. "'";
            }
        }
        $cond.='  and reservation_room.status!=\'CANCEL\' and reservation_room.status!=\'NOSHOW\' ';
        //$cond_invoice = " reservation_room.status!='CANCEL' and reservation_room.status!='NOSHOW' ";
        //So phong ks    
        
        /**
         * Phan 1 : forecast
         */
        $start_time = Date_Time::to_time($this->map['from_date']);
        $to_time = Date_Time::to_time($this->map['to_date']);
        $date = array();
            
            
		for($i = $start_time;$i<=$to_time;$i+=86400){
			$k = date('d/m/Y',$i);
            $date[$k]['id'] = $i;
            $date[$k]['name_1'] = $this->get_day_of_week(date('D',$i));
			$date[$k]['name_2'] = date('D',$i);
			$date[$k]['day'] = date('d/m/Y',$i);
			$date[$k]['number_guest_1'] = 'Số khách';
			$date[$k]['number_guest_2'] = 'Num guest';
            $date[$k]['number_room_1'] = 'Số phòng';
            $date[$k]['number_room_2'] = 'Num room';
            //tinh so luong khac va so luong phong o trong 1 ngay nao do
            $day = Date_Time::to_orc_date(date('d/m/Y',$i));
            
            $sql ='
            SELECT room_status.id,reservation_room.id as id_res,room_status.in_date,reservation_room.adult,reservation_room.room_id,
                    room_status.change_price,reservation_room.change_room_from_rr,reservation_room.change_room_to_rr,
                    reservation_room.departure_time,reservation_room.arrival_time
            FROM  reservation_room 
                  INNER JOIN reservation ON reservation.id=reservation_room.reservation_id
                  INNER JOIN room_status ON room_status.reservation_room_id=reservation_room.id 
                  inner join room_level on room_level.id = reservation_room.room_level_id
                  where
                  1=1
                  '.$cond.'
                  AND room_status.in_date=\''.$day.'\'
                  and (room_level.is_virtual is null or room_level.is_virtual <> 1)
                  and (reservation_room.departure_time > room_status.in_date or (reservation_room.departure_time = reservation_room.arrival_time and reservation_room.time_in>=(date_to_unix(room_status.in_date)+(6*3600))))
                    and (
                          reservation_room.change_room_to_rr is null 
                          or (reservation_room.change_room_to_rr is not null and from_unixtime(reservation_room.old_arrival_time)!=in_date)
                        )
                  ORDER BY reservation_room.id';
            $arr = DB::fetch_all($sql);
            if(User::id()=='developer06')
            {
                //System::debug($sql);
                //System::debug($arr);
            }
            $num_guest = 0;
            $num_room  = 0;
            foreach($arr as $row)
            {
                if(isset($row['adult']))
                {
                   $num_guest = $num_guest +$row['adult'];
                }
                $num_room ++;
            }
            
           // System::debug($arr);
            $date[$k]['number_room'] = $num_room;
            $date[$k]['number_guest'] = $num_guest;
            //Kimtan: dich vu lo li ei
            $sql_vd = '
                    Select 
                        to_char( extra_service_invoice_detail.in_date, \'YYYY-MM-DD\' ) as id,
                        Sum(extra_service_invoice_detail.quantity) as quantity 
                    from
                        extra_service_invoice_detail
                        inner join extra_service_invoice on extra_service_invoice.id = extra_service_invoice_detail.invoice_id
                        inner join extra_service on extra_service_invoice_detail.service_id = extra_service.id
                        inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                        inner join reservation on reservation_room.reservation_id = reservation.id
                        left join room_level on reservation_room.room_level_id = room_level.id
                    where
                        (extra_service.code=\'LATE_CHECKOUT\' or extra_service.code=\'LATE_CHECKIN\' or extra_service.code=\'EARLY_CHECKIN\')
                        '.$cond.'
                        and room_level.is_virtual = 0
                        AND extra_service_invoice_detail.in_date=\''.$day.'\''.$cond_invoice.' 
                    GROUP BY
                        to_char( extra_service_invoice_detail.in_date, \'YYYY-MM-DD\' )
                    ORDER BY
                        to_char( extra_service_invoice_detail.in_date, \'YYYY-MM-DD\' )
                    ';
            $ex_items = DB::fetch_all($sql_vd);
            //System::debug($ex_items);
            foreach($ex_items as $key=>$value)
    		{
    			{			
    				$date[$k]['number_room']+=$value['quantity'];
    			}
    		}
		}
        $this->map['date'] = $date;

        //lay ra ma dat co so luong phong >=10 trong tuan do va portal_id
        $sql ='
        SELECT reservation.id,customer.name,count(*) as num_room, sum(reservation_room.adult) as num_guest
        FROM reservation
        INNER JOIN reservation_room ON  reservation.id=reservation_room.reservation_id  '.$cond.' AND 
        ((reservation_room.arrival_time >=\''.$date_from.'\' AND reservation_room.arrival_time <\''.$date_to.'\') OR
        (reservation_room.departure_time >\''.$date_from.'\' AND reservation_room.departure_time<=\''.$date_to.'\') OR
        (reservation_room.arrival_time <\''.$date_from.'\' AND reservation_room.departure_time >\''.$date_to.'\'))
        LEFT JOIN customer ON reservation.customer_id = customer.id
        GROUP BY reservation.id,customer.name
        HAVING count(*) >=10';
        //System::debug($sql);
        $arr_note = DB::fetch_all($sql);
        
        //hien thi them thong tin ngay den va ngay di cua doan
        //duyet qua tat ca nhung reservation tim thong tin ngay den som nhat cua phong
        foreach($arr_note as &$row)
        {
            //hien thi dinh dang  d/m/Y
            //$row['arrival_time']  = Date_Time::convert_orc_date_to_date('09-JUL-14' ,'/');
            //$row['departure_time']  = Date_Time::convert_orc_date_to_date('16-JUL-14','/');
            //echo $row['id'];//la gia tri can tim
            $sql = 'SELECT reservation_room.id,reservation.id as id_res,reservation_room.arrival_time,reservation_room.departure_time
            FROM reservation
            INNER JOIN reservation_room ON  reservation.id=reservation_room.reservation_id AND reservation.id=\''.$row['id'].'\'
            where 
            1=1
            '.$cond.'
            ORDER BY reservation_room.arrival_time asc, reservation_room.departure_time desc';
            $arr_arrival_departure = DB::fetch_all($sql);
            foreach($arr_arrival_departure as $first)
            {
                //lay ra ngay den va ngay di cho 1 doan
                $row['arrival_time'] = Date_Time::convert_orc_date_to_date($first['arrival_time'],'/');
                $row['departure_time'] =  Date_Time::convert_orc_date_to_date($first['departure_time'],'/');
                break;
            }
            
            $sql ='
            SELECT room_level.id,room_level.name,count(*) as num_level
            FROM reservation
            INNER JOIN reservation_room ON  reservation.id=reservation_room.reservation_id AND reservation.id=\''.$row['id'].'\'
            INNER JOIN room ON room.id=reservation_room.room_id
            INNER JOIN room_level ON room.room_level_id=room_level.id
            where 
            1=1
            '.$cond.'
            GROUP BY room_level.id,room_level.name';
            $arr_room_level = DB::fetch_all($sql);
            $row['room_level'] = $arr_room_level;
           
        }
        //System::debug($arr_note);
        $this->map['arr_note'] = $arr_note;
		$this->parse_layout('report',$this->map);
    }

    
	function get_beginning_date_of_week(){
		$today = date('d/m/Y');
		$time_today = Date_Time::to_time($today);
		$day_of_week = date('w',$time_today);
		$day_begin_of_week = $time_today  - (24 * 3600 * $day_of_week);
		return (Date_Time::to_orc_date(date('d/m/Y',$day_begin_of_week)));
	}
	function get_end_date_of_week()
    {
		$today = date('d/m/Y');
		$time_today = Date_Time::to_time($today);
		$day_of_week = date('w',$time_today);
		$end_of_week = $time_today + (24 * 3600 * (6 - $day_of_week));
		return (Date_Time::to_orc_date(date('d/m/Y',$end_of_week)));
	}
    
    /**
     * cho phan Forecast
     */
    //tinh tien sau thue, charge cho du kien doanh thu, cho tung ngay 1
    function calc_forecast_money($total,$net_price,$service_rate,$tax_rate,$foc,$foc_all,$verify_dayuse,$flag = false)
    {
        $total_after = 0;
        if($foc_all==1 or $foc!='')
        {
          return $total_after;
        }
        
        if($flag)
            $total = $total * (1+$verify_dayuse);
        //gia phong chua bao gom tax and charge
        if($net_price==0)
        {
            //$total_after = $total * (1-$reduce_balance/100);
            //$total_after = $total_after - $reduce_amount;
            $total_after = $total * (1+$service_rate/100);
            $total_after = $total_after * (1+$tax_rate/100);
            return $total_after;
        }
        else//da bao gom tax va charge => chia nguoc lai, tru tiep giam gia, roi nhan lai tax va rate
        {
            /*
            $total_after = $total / (1+$tax_rate/100);
            $total_after = $total_after / (1+$service_rate/100);
            $total_after = $total_after * (1-$reduce_balance/100);
            $total_after = $total_after - $reduce_amount;
            $total_after = $total_after * (1+$service_rate/100);
            $total_after = $total_after * (1+$tax_rate/100);
            */
            return $total;
        }
        
    }
    
    
    
    /**
     * Cho phan Revenue
     */
    
    //join ca phone number de tinh tien dien thoai
    function get_room_data($cond)
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
                    reservation_room.foc,
                    reservation_room.foc_all,
                    COALESCE(reservation_room.reduce_balance,0) as reduce_balance,
                    COALESCE(reservation_room.reduce_amount,0) as reduce_amount,
                    COUNT(room_status.id) + COALESCE(reservation_room.verify_dayuse,0)/10 as night,
                    telephone_number.phone_number
				FROM
                    reservation_room 
                    inner join reservation on reservation.id = reservation_room.reservation_id
                    left  join room_status on room_status.reservation_room_id = reservation_room.id
                    inner join room on room.id = reservation_room.room_id
                    left  join telephone_number on telephone_number.room_id = room.id
				WHERE
					1=1
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
                    telephone_number.phone_number,
                    reservation_room.foc,
                    reservation_room.foc_all
				ORDER BY
					reservation_room.id DESC
					
		';
        //System::debug($sql);
        $items = DB::fetch_all($sql);
        return $items;
    }
    
            //tinh tien sau thue, charge
    function calc_money($net_price,$total,$reduce_balance,$reduce_amount,$service_rate,$tax_rate,$foc,$foc_all)
    {
        $total_after = 0;
        if($foc_all==1 or $foc!='')
        {
          return $total_after;
        }
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
    
    
    function count_pickup($cond)
    {
        $num_pickup = DB::fetch(
                        'select
                                COUNT(reservation_traveller.id) as num_pickup
                            from 
                                reservation_room 
                                inner join reservation on reservation.id = reservation_room.reservation_id
                                inner join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                            where
                                1=1
                                '.$cond.' 
                                and reservation_traveller.status != \'CHANGE\'
                                and reservation_traveller.pickup = 1
                                and reservation_traveller.pickup_foc = 1
                                and reservation_room.foc_all != 1
                        ','num_pickup');
        return $num_pickup;
        
    }
    
    function count_see_off($cond)
    {
        $num_see_off = DB::fetch(
                                'select
                                        COUNT(reservation_traveller.id) as num_see_off
                                    from 
                                        reservation_room 
                                        inner join reservation on reservation.id = reservation_room.reservation_id
                                        inner join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                                    where
                                        1=1
                                        '.$cond.' 
                                        and reservation_traveller.status != \'CHANGE\'
                                        and reservation_traveller.see_off = 1
                                        and reservation_traveller.see_off_foc = 1
                                        and reservation_room.foc_all != 1
                                ','num_see_off');
        return $num_see_off;
        
    }
    
    function get_hk_revenue($cond, $hk_cond)
    {
        $housekeeping_revenue = DB::fetch('
                                        Select 
                                            SUM(housekeeping_invoice.total) as total
                                        FROM
                                            housekeeping_invoice
                                            inner join reservation_room on reservation_room.id = housekeeping_invoice.reservation_room_id
                                            inner join reservation on reservation.id = reservation_room.reservation_id
                                        WHERE
                                            1=1
                                            '.$cond.$hk_cond.'
                                            and reservation_room.foc_all != 1
                                        ','total');
        return $housekeeping_revenue;
    }
    
    function get_extra_service_revenue($cond)
    {
        $extra_service_revenue = DB::fetch('
                                            Select 
                                                SUM(extra_service_invoice.total_amount) as total
                                            FROM
                                                extra_service_invoice
                                                inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                                                inner join reservation on reservation.id = reservation_room.reservation_id
                                            WHERE
                                                1=1
                                                '.$cond.'
                                                and reservation_room.foc_all != 1
                                            ','total'); 
    }
    
    
    //chuyen doi tu thu tieng anh sang thu tieng viet 
    function get_day_of_week($day)
    {
        $str ='';
        switch($day)
        {
            case 'Mon':
                $str = 'Thứ 2';
                break;
            case 'Tue':
                $str='Thứ 3';
                break;
            case 'Wed':
                $str = 'Thứ 4';
                break;
            case 'Thu':
                $str = 'Thứ 5';
                break;
            case 'Fri':
                $str = 'Thứ 6';
                break;
            case 'Sat':
                $str = 'Thứ 7';
                break;
            case 'Sun':
                $str ='Chủ nhật';
                break;
            default: 
                $str ='';
                break;
        }
        return $str;       
        
    }
    //Du lieu nha hang, da tinh toan thue, charge
    
    
    function get_bar_reservation_data($cond)
    {   
        /**
         * Tinh doanh thu nha hang
         */
        $bar_revenue = DB::fetch_all('Select
                                            bar_id as id, 
                                            SUM(total) as total 
                                        from 
                                            bar_reservation 
                                        where 
                                            '.$cond.'
                                        group by 
                                            bar_id    
                                            ');
        
        return $bar_revenue;
        
    }
}

?>
