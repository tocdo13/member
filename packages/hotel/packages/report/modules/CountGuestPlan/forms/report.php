<?php
class CountGuestPlanForm extends Form
{
    function CountGuestPlanForm()
    {
        Form::Form('CountGuestPlanForm');
    }
    
    function draw()
    {
        $this->map = array();
        $sql = 'select 
                reservation_traveller.id,
                customer.code,
                reservation_traveller.arrival_date,
                reservation_room.id as reservation_room_id,
                reservation_traveller.traveller_id, 
                reservation_room.customer_name,
                traveller.first_name as traveller_name,
                traveller.nationality_id,
                guest_type.name as taveller_type,
                guest_type.group_name,
                guest_type.id as guest_level,
                reservation.customer_id, 
                reservation_room.room_id,
                reservation_room.reservation_id,
                reservation_room.time_in,
                reservation_room.time_out,
                reservation_traveller.arrival_time,
                reservation_traveller.departure_time,
                FROM_UNIXTIME(reservation_traveller.departure_time) - FROM_UNIXTIME(reservation_traveller.arrival_time) as guest_day,
                FROM_UNIXTIME(reservation_room.time_out) - FROM_UNIXTIME(reservation_room.time_in) as room_day
                from 
                reservation_room inner join reservation on reservation_room.reservation_id = reservation.id
                inner join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                inner join traveller on reservation_traveller.traveller_id = traveller.id
                inner join guest_type on traveller.traveller_level_id = guest_type.id
                inner join customer on reservation.customer_id = customer.id
                order by 
                reservation_room.id';
        $all_arr = DB::fetch_all($sql);
        //$de_sql = 'select id,reservation_room.customer_name from reservation_room where customer_name ='.'\'\'';
        //$de = DB::fetch_all($de_sql);
        //System::debug($de);
        //System::debug($all_arr);
        //System::debug(count($all_arr));
        $items = array();
        //$current_year = getdate(time());
        $current_year['year'] = Url::get_value('year');
        
        $items['total_of_guest'][$current_year['year'] - 2]['no_of_guest'] = 0;
        $items['total_of_guest'][$current_year['year'] - 2]['guest_day'] = 0;
        $items['total_of_guest'][$current_year['year'] - 2]['room_day'] = 0;
        $items['total_of_guest'][$current_year['year'] - 1]['no_of_guest'] = 0;
        $items['total_of_guest'][$current_year['year'] - 1]['guest_day'] = 0;
        $items['total_of_guest'][$current_year['year'] - 1]['room_day'] = 0;
        $items['total_of_guest']['name'] = 'total_of_guest';
        
        $items['foreign_guest'][$current_year['year'] - 2]['no_of_guest'] = 0;
        $items['foreign_guest'][$current_year['year'] - 2]['guest_day'] = 0;
        $items['foreign_guest'][$current_year['year'] - 2]['room_day'] = 0;
        $items['foreign_guest'][$current_year['year'] - 1]['no_of_guest'] = 0;
        $items['foreign_guest'][$current_year['year'] - 1]['guest_day'] = 0;
        $items['foreign_guest'][$current_year['year'] - 1]['room_day'] = 0;
        $items['foreign_guest']['name'] = 'foreign_guest';
        
        $items['guest_specific'][$current_year['year'] - 2]['no_of_guest'] = '';
        $items['guest_specific'][$current_year['year'] - 2]['guest_day'] = '';
        $items['guest_specific'][$current_year['year'] - 2]['room_day'] = '';
        $items['guest_specific'][$current_year['year'] - 1]['no_of_guest'] = '';
        $items['guest_specific'][$current_year['year'] - 1]['guest_day'] = '';
        $items['guest_specific'][$current_year['year'] - 1]['room_day'] = '';
        $items['guest_specific']['name'] = 'guest_specific';
        
        $items['foreign_traveller'][$current_year['year'] - 2]['no_of_guest'] = 0;
        $items['foreign_traveller'][$current_year['year'] - 2]['guest_day'] = 0;
        $items['foreign_traveller'][$current_year['year'] - 2]['room_day'] = 0;
        $items['foreign_traveller'][$current_year['year'] - 1]['no_of_guest'] = 0;
        $items['foreign_traveller'][$current_year['year'] - 1]['guest_day'] = 0;
        $items['foreign_traveller'][$current_year['year'] - 1]['room_day'] = 0;
        $items['foreign_traveller']['name'] = 'foreign_traveller';
        
        $items['t_group_of_guest'][$current_year['year'] - 2]['no_of_guest'] = 0;
        $items['t_group_of_guest'][$current_year['year'] - 2]['guest_day'] = 0;
        $items['t_group_of_guest'][$current_year['year'] - 2]['room_day'] = 0;
        $items['t_group_of_guest'][$current_year['year'] - 1]['no_of_guest'] = 0;
        $items['t_group_of_guest'][$current_year['year'] - 1]['guest_day'] = 0;
        $items['t_group_of_guest'][$current_year['year'] - 1]['room_day'] = 0;
        $items['t_group_of_guest']['name'] = 't_group_of_guest';
        
        $items['t_walkin_guest'][$current_year['year'] - 2]['no_of_guest'] = 0;
        $items['t_walkin_guest'][$current_year['year'] - 2]['guest_day'] = 0;
        $items['t_walkin_guest'][$current_year['year'] - 2]['room_day'] = 0;
        $items['t_walkin_guest'][$current_year['year'] - 1]['no_of_guest'] = 0;
        $items['t_walkin_guest'][$current_year['year'] - 1]['guest_day'] = 0;
        $items['t_walkin_guest'][$current_year['year'] - 1]['room_day'] = 0;
        $items['t_walkin_guest']['name'] = 't_walkin_guest';
        
        $items['t_direct_reserve'][$current_year['year'] - 2]['no_of_guest'] = 0;
        $items['t_direct_reserve'][$current_year['year'] - 2]['guest_day'] = 0;
        $items['t_direct_reserve'][$current_year['year'] - 2]['room_day'] = 0;
        $items['t_direct_reserve'][$current_year['year'] - 1]['no_of_guest'] = 0;
        $items['t_direct_reserve'][$current_year['year'] - 1]['guest_day'] = 0;
        $items['t_direct_reserve'][$current_year['year'] - 1]['room_day'] = 0;
        $items['t_direct_reserve']['name'] = 't_direct_reserve';
        
        $items['t_online_reserve'][$current_year['year'] - 2]['no_of_guest'] = 0;
        $items['t_online_reserve'][$current_year['year'] - 2]['guest_day'] = 0;
        $items['t_online_reserve'][$current_year['year'] - 2]['room_day'] = 0;
        $items['t_online_reserve'][$current_year['year'] - 1]['no_of_guest'] = 0;
        $items['t_online_reserve'][$current_year['year'] - 1]['guest_day'] = 0;
        $items['t_online_reserve'][$current_year['year'] - 1]['room_day'] = 0;
        $items['t_online_reserve']['name'] = 't_online_reserve';
        
        $items['t_sgt_reserve'][$current_year['year'] - 2]['no_of_guest'] = 0;
        $items['t_sgt_reserve'][$current_year['year'] - 2]['guest_day'] = 0;
        $items['t_sgt_reserve'][$current_year['year'] - 2]['room_day'] = 0;
        $items['t_sgt_reserve'][$current_year['year'] - 1]['no_of_guest'] = 0;
        $items['t_sgt_reserve'][$current_year['year'] - 1]['guest_day'] = 0;
        $items['t_sgt_reserve'][$current_year['year'] - 1]['room_day'] = 0;
        $items['t_sgt_reserve']['name'] = 't_sgt_guest';
        
        $items['t_transit_reserve'][$current_year['year'] - 2]['no_of_guest'] = 0;
        $items['t_transit_reserve'][$current_year['year'] - 2]['guest_day'] = 0;
        $items['t_transit_reserve'][$current_year['year'] - 2]['room_day'] = 0;
        $items['t_transit_reserve'][$current_year['year'] - 1]['no_of_guest'] = 0;
        $items['t_transit_reserve'][$current_year['year'] - 1]['guest_day'] = 0;
        $items['t_transit_reserve'][$current_year['year'] - 1]['room_day'] = 0;
        $items['t_transit_reserve']['name'] = 't_transit_reserve';
        
        
        $items['business'][$current_year['year'] - 2]['no_of_guest'] = 0;
        $items['business'][$current_year['year'] - 2]['guest_day'] = 0;
        $items['business'][$current_year['year'] - 2]['room_day'] = 0;
        $items['business'][$current_year['year'] - 1]['no_of_guest'] = 0;
        $items['business'][$current_year['year'] - 1]['guest_day'] = 0;
        $items['business'][$current_year['year'] - 1]['room_day'] = 0;
        $items['business']['name'] = 'business';
        
        $items['w_online_reserve'][$current_year['year'] - 2]['no_of_guest'] = 0;
        $items['w_online_reserve'][$current_year['year'] - 2]['guest_day'] = 0;
        $items['w_online_reserve'][$current_year['year'] - 2]['room_day'] = 0;
        $items['w_online_reserve'][$current_year['year'] - 1]['no_of_guest'] = 0;
        $items['w_online_reserve'][$current_year['year'] - 1]['guest_day'] = 0;
        $items['w_online_reserve'][$current_year['year'] - 1]['room_day'] = 0;
        $items['w_online_reserve']['name'] = 'w_online_reserve';
        
        $items['w_walkin_reserve'][$current_year['year'] - 2]['no_of_guest'] = 0;
        $items['w_walkin_reserve'][$current_year['year'] - 2]['guest_day'] = 0;
        $items['w_walkin_reserve'][$current_year['year'] - 2]['room_day'] = 0;
        $items['w_walkin_reserve'][$current_year['year'] - 1]['no_of_guest'] = 0;
        $items['w_walkin_reserve'][$current_year['year'] - 1]['guest_day'] = 0;
        $items['w_walkin_reserve'][$current_year['year'] - 1]['room_day'] = 0;
        $items['w_walkin_reserve']['name'] = 'w_walkin_reserve';
        
        $items['single_business'][$current_year['year'] - 2]['no_of_guest'] = 0;
        $items['single_business'][$current_year['year'] - 2]['guest_day'] = 0;
        $items['single_business'][$current_year['year'] - 2]['room_day'] = 0;
        $items['single_business'][$current_year['year'] - 1]['no_of_guest'] = 0;
        $items['single_business'][$current_year['year'] - 1]['guest_day'] = 0;
        $items['single_business'][$current_year['year'] - 1]['room_day'] = 0;
        $items['single_business']['name'] = 'single_business';
        
        $items['other_business'][$current_year['year'] - 2]['no_of_guest'] = 0;
        $items['other_business'][$current_year['year'] - 2]['guest_day'] = 0;
        $items['other_business'][$current_year['year'] - 2]['room_day'] = 0;
        $items['other_business'][$current_year['year'] - 1]['no_of_guest'] = 0;
        $items['other_business'][$current_year['year'] - 1]['guest_day'] = 0;
        $items['other_business'][$current_year['year'] - 1]['room_day'] = 0;
        $items['other_business']['name'] = 'other_business';
        
        $domestic_items['domestic'][$current_year['year'] - 2]['no_of_guest'] = 0;
        $domestic_items['domestic'][$current_year['year'] - 2]['guest_day'] = 0;
        $domestic_items['domestic'][$current_year['year'] - 2]['room_day'] = 0;
        $domestic_items['domestic'][$current_year['year'] - 1]['no_of_guest'] = 0;
        $domestic_items['domestic'][$current_year['year'] - 1]['guest_day'] = 0;
        $domestic_items['domestic'][$current_year['year'] - 1]['room_day'] = 0;
        $domestic_items['domestic']['name'] = 'domestic';
        
        $domestic_items['domestic_guest'][$current_year['year'] - 2]['no_of_guest'] = 0;
        $domestic_items['domestic_guest'][$current_year['year'] - 2]['guest_day'] = 0;
        $domestic_items['domestic_guest'][$current_year['year'] - 2]['room_day'] = 0;
        $domestic_items['domestic_guest'][$current_year['year'] - 1]['no_of_guest'] = 0;
        $domestic_items['domestic_guest'][$current_year['year'] - 1]['guest_day'] = 0;
        $domestic_items['domestic_guest'][$current_year['year'] - 1]['room_day'] = 0;
        $domestic_items['domestic_guest']['name'] = 'domestic_guest';
        
        $domestic_items['domestic_meeting_guest'][$current_year['year'] - 2]['no_of_guest'] = 0;
        $domestic_items['domestic_meeting_guest'][$current_year['year'] - 2]['guest_day'] = 0;
        $domestic_items['domestic_meeting_guest'][$current_year['year'] - 2]['room_day'] = 0;
        $domestic_items['domestic_meeting_guest'][$current_year['year'] - 1]['no_of_guest'] = 0;
        $domestic_items['domestic_meeting_guest'][$current_year['year'] - 1]['guest_day'] = 0;
        $domestic_items['domestic_meeting_guest'][$current_year['year'] - 1]['room_day'] = 0;
        $domestic_items['domestic_meeting_guest']['name'] = 'domestic_meeting_guest';
        
        $domestic_items['domestic_singlge_guest'][$current_year['year'] - 2]['no_of_guest'] = 0;
        $domestic_items['domestic_singlge_guest'][$current_year['year'] - 2]['guest_day'] = 0;
        $domestic_items['domestic_singlge_guest'][$current_year['year'] - 2]['room_day'] = 0;
        $domestic_items['domestic_singlge_guest'][$current_year['year'] - 1]['no_of_guest'] = 0;
        $domestic_items['domestic_singlge_guest'][$current_year['year'] - 1]['guest_day'] = 0;
        $domestic_items['domestic_singlge_guest'][$current_year['year'] - 1]['room_day'] = 0;
        $domestic_items['domestic_singlge_guest']['name'] = 'domestic_single_guest';
        
        foreach($all_arr as $key => $value)
        {
            $room_arrival_time = getdate($value['time_in']);
            $room_departure_time = getdate($value['time_out']);
            $guest_arrival_time = getdate($value['arrival_time']);
            $guest_departure_time = getdate($value['departure_time']);
            //tong so khach
            //echo $guest_arrival_time['year'].'<br>';
            if($value['nationality_id'])
            {
                if($room_arrival_time['year'] <= $current_year['year'] - 2 AND $room_departure_time['year'] >= $current_year['year'] - 2)
                {
                    $items['total_of_guest'][$current_year['year'] - 2]['no_of_guest'] += 1;
                    if($value['guest_day'] == 0)
                        $items['total_of_guest'][$current_year['year'] - 2]['guest_day'] += 1;
                    else
                        $items['total_of_guest'][$current_year['year'] - 2]['guest_day'] += $value['guest_day'];
                    if($value['room_day'] == 0)
                        $items['total_of_guest'][$current_year['year'] - 2]['room_day'] += 1;
                    else
                        $items['total_of_guest'][$current_year['year'] - 2]['room_day'] += $value['room_day'];   
                }
                if($room_arrival_time['year'] <= $current_year['year'] - 1 AND $room_departure_time['year'] >= $current_year['year'] - 1)
                {
                    $items['total_of_guest'][$current_year['year'] - 1]['no_of_guest'] += 1;
                    if($value['guest_day'] == 0)
                        $items['total_of_guest'][$current_year['year'] - 1]['guest_day'] += 1;
                    else
                        $items['total_of_guest'][$current_year['year'] - 1]['guest_day'] += $value['guest_day'];
                    if($value['room_day'] == 0)
                        $items['total_of_guest'][$current_year['year'] - 1]['room_day'] += 1;
                    else
                        $items['total_of_guest'][$current_year['year'] - 1]['room_day'] += $value['room_day'];   
                }
            }
            //khach quoc te
            if($value['nationality_id'] != 439)
            {
                //so khach quoc te
                if($value['nationality_id'] != 439)
                {
                    if($room_arrival_time['year'] <= $current_year['year'] - 2 AND $room_departure_time['year'] >= $current_year['year'] - 2)
                    {
                        $items['foreign_guest'][$current_year['year'] - 2]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['foreign_guest'][$current_year['year'] - 2]['guest_day'] += 1;
                        else
                            $items['foreign_guest'][$current_year['year'] - 2]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['foreign_guest'][$current_year['year'] - 2]['room_day'] += 1;
                        else
                            $items['foreign_guest'][$current_year['year'] - 2]['room_day'] += $value['room_day'];   
                    }
                    if($room_arrival_time['year'] <= $current_year['year'] - 1 AND $room_departure_time['year'] >= $current_year['year'] - 1)
                    {
                        $items['foreign_guest'][$current_year['year'] - 1]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['foreign_guest'][$current_year['year'] - 1]['guest_day'] += 1;
                        else
                            $items['foreign_guest'][$current_year['year'] - 1]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['foreign_guest'][$current_year['year'] - 1]['room_day'] += 1;
                        else
                            $items['foreign_guest'][$current_year['year'] - 1]['room_day'] += $value['room_day'];   
                    }
                }
                
                // khach du lich
                if($value['group_name'] == 'TRAVEL')
                {
                    if($room_arrival_time['year'] <= $current_year['year'] - 2 AND $room_departure_time['year'] >= $current_year['year'] - 2)
                    {
                        $items['foreign_traveller'][$current_year['year'] - 2]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['foreign_traveller'][$current_year['year'] - 2]['guest_day'] += 1;
                        else
                            $items['foreign_traveller'][$current_year['year'] - 2]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['foreign_traveller'][$current_year['year'] - 2]['room_day'] += 1;
                        else
                            $items['foreign_traveller'][$current_year['year'] - 2]['room_day'] += $value['room_day'];   
                    }
                    if($room_arrival_time['year'] <= $current_year['year'] - 1 AND $room_departure_time['year'] >= $current_year['year'] - 1)
                    {
                        $items['foreign_traveller'][$current_year['year'] - 1]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['foreign_traveller'][$current_year['year'] - 1]['guest_day'] += 1;
                        else
                            $items['foreign_traveller'][$current_year['year'] - 1]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['foreign_traveller'][$current_year['year'] - 1]['room_day'] += 1;
                        else
                            $items['foreign_traveller'][$current_year['year'] - 1]['room_day'] += $value['room_day'];   
                    }
                }
                //khach doan
                if($value['customer_name'] != '' || $value['code'] != 'KL' AND $value['group_name'] == 'TRAVEL')
                {
                    if($room_arrival_time['year'] <= $current_year['year'] - 2 AND $room_departure_time['year'] >= $current_year['year'] - 2)
                    {
                        $items['t_group_of_guest'][$current_year['year'] - 2]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['t_group_of_guest'][$current_year['year'] - 2]['guest_day'] += 1;
                        else
                            $items['t_group_of_guest'][$current_year['year'] - 2]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['t_group_of_guest'][$current_year['year'] - 2]['room_day'] += 1;
                        else
                            $items['t_group_of_guest'][$current_year['year'] - 2]['room_day'] += $value['room_day'];   
                    }
                    if($room_arrival_time['year'] <= $current_year['year'] - 1 AND $room_departure_time['year'] >= $current_year['year'] - 1)
                    {
                        $items['t_group_of_guest'][$current_year['year'] - 1]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['t_group_of_guest'][$current_year['year'] - 1]['guest_day'] += 1;
                        else
                            $items['t_group_of_guest'][$current_year['year'] - 1]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['t_group_of_guest'][$current_year['year'] - 1]['room_day'] += 1;
                        else
                            $items['t_group_of_guest'][$current_year['year'] - 1]['room_day'] += $value['room_day'];   
                    }
                }
                //khach le
                if($value['code'] == 'KL' AND $value['group_name'] == 'TRAVEL')
                {
                    if($room_arrival_time['year'] <= $current_year['year'] - 2 AND $room_departure_time['year'] >= $current_year['year'] - 2)
                    {
                        $items['t_walkin_guest'][$current_year['year'] - 2]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['t_walkin_guest'][$current_year['year'] - 2]['guest_day'] += 1;
                        else
                            $items['t_walkin_guest'][$current_year['year'] - 2]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['t_walkin_guest'][$current_year['year'] - 2]['room_day'] += 1;
                        else
                            $items['t_walkin_guest'][$current_year['year'] - 2]['room_day'] += $value['room_day'];   
                    }
                    if($room_arrival_time['year'] <= $current_year['year'] - 1 AND $room_departure_time['year'] >= $current_year['year'] - 1)
                    {
                        $items['t_walkin_guest'][$current_year['year'] - 1]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['t_walkin_guest'][$current_year['year'] - 1]['guest_day'] += 1;
                        else
                            $items['t_walkin_guest'][$current_year['year'] - 1]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['t_walkin_guest'][$current_year['year'] - 1]['room_day'] += 1;
                        else
                            $items['t_walkin_guest'][$current_year['year'] - 1]['room_day'] += $value['room_day'];   
                    }
                }
                //KS tu ky ket
                if($value['guest_level'] == 5 AND $value['group_name'] == 'TRAVEL')
                {
                    if($room_arrival_time['year'] <= $current_year['year'] - 2 AND $room_departure_time['year'] >= $current_year['year'] - 2)
                    {
                        $items['t_direct_reserve'][$current_year['year'] - 2]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['t_direct_reserve'][$current_year['year'] - 2]['guest_day'] += 1;
                        else
                            $items['t_direct_reserve'][$current_year['year'] - 2]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['t_direct_reserve'][$current_year['year'] - 2]['room_day'] += 1;
                        else
                            $items['t_direct_reserve'][$current_year['year'] - 2]['room_day'] += $value['room_day'];   
                    }
                    if($room_arrival_time['year'] <= $current_year['year'] - 1 AND $room_departure_time['year'] >= $current_year['year'] - 1)
                    {
                        $items['t_direct_reserve'][$current_year['year'] - 1]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['t_direct_reserve'][$current_year['year'] - 1]['guest_day'] += 1;
                        else
                            $items['t_direct_reserve'][$current_year['year'] - 1]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['t_direct_reserve'][$current_year['year'] - 1]['room_day'] += 1;
                        else
                            $items['t_direct_reserve'][$current_year['year'] - 1]['room_day'] += $value['room_day'];   
                    }
                }
                //khach hang qua mang
                if($value['guest_level'] == 8 AND $value['group_name'] == 'TRAVEL')
                {
                    if($room_arrival_time['year'] <= $current_year['year'] - 2 AND $room_departure_time['year'] >= $current_year['year'] - 2)
                    {
                        $items['t_online_reserve'][$current_year['year'] - 2]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['t_online_reserve'][$current_year['year'] - 2]['guest_day'] += 1;
                        else
                            $items['t_online_reserve'][$current_year['year'] - 2]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['t_online_reserve'][$current_year['year'] - 2]['room_day'] += 1;
                        else
                            $items['t_online_reserve'][$current_year['year'] - 2]['room_day'] += $value['room_day'];   
                    }
                    if($room_arrival_time['year'] <= $current_year['year'] - 1 AND $room_departure_time['year'] >= $current_year['year'] - 1)
                    {
                        $items['t_online_reserve'][$current_year['year'] - 1]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['t_online_reserve'][$current_year['year'] - 1]['guest_day'] += 1;
                        else
                            $items['t_online_reserve'][$current_year['year'] - 1]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['t_online_reserve'][$current_year['year'] - 1]['room_day'] += 1;
                        else
                            $items['t_online_reserve'][$current_year['year'] - 1]['room_day'] += $value['room_day'];   
                    }
                }
                //Cty LH SGT dua den
                if($value['guest_level'] == 6 AND $value['group_name'] == 'TRAVEL')
                {
                    if($room_arrival_time['year'] <= $current_year['year'] - 2 AND $room_departure_time['year'] >= $current_year['year'] - 2)
                    {
                        $items['t_sgt_reserve'][$current_year['year'] - 2]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['t_sgt_reserve'][$current_year['year'] - 2]['guest_day'] += 1;
                        else
                            $items['t_sgt_reserve'][$current_year['year'] - 2]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['t_sgt_reserve'][$current_year['year'] - 2]['room_day'] += 1;
                        else
                            $items['t_sgt_reserve'][$current_year['year'] - 2]['room_day'] += $value['room_day'];   
                    }
                    if($room_arrival_time['year'] <= $current_year['year'] - 1 AND $room_departure_time['year'] >= $current_year['year'] - 1)
                    {
                        $items['t_sgt_reserve'][$current_year['year'] - 1]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['t_sgt_reserve'][$current_year['year'] - 1]['guest_day'] += 1;
                        else
                            $items['t_sgt_reserve'][$current_year['year'] - 1]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['t_sgt_reserve'][$current_year['year'] - 1]['room_day'] += 1;
                        else
                            $items['t_sgt_reserve'][$current_year['year'] - 1]['room_day'] += $value['room_day'];   
                    }
                }
                //khach vang lai
                if($value['guest_level'] == 7 AND $value['group_name'] == 'TRAVEL')
                {
                    if($room_arrival_time['year'] <= $current_year['year'] - 2 AND $room_departure_time['year'] >= $current_year['year'] - 2)
                    {
                        $items['t_transit_reserve'][$current_year['year'] - 2]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['t_transit_reserve'][$current_year['year'] - 2]['guest_day'] += 1;
                        else
                            $items['t_transit_reserve'][$current_year['year'] - 2]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['t_transit_reserve'][$current_year['year'] - 2]['room_day'] += 1;
                        else
                            $items['t_transit_reserve'][$current_year['year'] - 2]['room_day'] += $value['room_day'];   
                    }
                    if($room_arrival_time['year'] <= $current_year['year'] - 1 AND $room_departure_time['year'] >= $current_year['year'] - 1)
                    {
                        $items['t_transit_reserve'][$current_year['year'] - 1]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['t_transit_reserve'][$current_year['year'] - 1]['guest_day'] += 1;
                        else
                            $items['t_transit_reserve'][$current_year['year'] - 1]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['t_transit_reserve'][$current_year['year'] - 1]['room_day'] += 1;
                        else
                            $items['t_transit_reserve'][$current_year['year'] - 1]['room_day'] += $value['room_day'];   
                    }
                }
            //THƯƠNG NHAN
                if($value['group_name'] == 'WALK_IN')
                {
                    if($room_arrival_time['year'] <= $current_year['year'] - 2 AND $room_departure_time['year'] >= $current_year['year'] - 2)
                    {
                        $items['business'][$current_year['year'] - 2]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['business'][$current_year['year'] - 2]['guest_day'] += 1;
                        else
                            $items['business'][$current_year['year'] - 2]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['business'][$current_year['year'] - 2]['room_day'] += 1;
                        else
                            $items['business'][$current_year['year'] - 2]['room_day'] += $value['room_day'];   
                    }
                    if($room_arrival_time['year'] <= $current_year['year'] - 1 AND $room_departure_time['year'] >= $current_year['year'] - 1)
                    {
                        $items['business'][$current_year['year'] - 1]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['business'][$current_year['year'] - 1]['guest_day'] += 1;
                        else
                            $items['business'][$current_year['year'] - 1]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['business'][$current_year['year'] - 1]['room_day'] += 1;
                        else
                            $items['business'][$current_year['year'] - 1]['room_day'] += $value['room_day'];   
                    }
                }
                //khach qua mang
                if($value['guest_level'] == 4 AND $value['group_name'] == 'WALK_IN')
                {
                    if($room_arrival_time['year'] <= $current_year['year'] - 2 AND $room_departure_time['year'] >= $current_year['year'] - 2)
                    {
                        $items['w_online_reserve'][$current_year['year'] - 2]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['w_online_reserve'][$current_year['year'] - 2]['guest_day'] += 1;
                        else
                            $items['w_online_reserve'][$current_year['year'] - 2]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['w_online_reserve'][$current_year['year'] - 2]['room_day'] += 1;
                        else
                            $items['w_online_reserve'][$current_year['year'] - 2]['room_day'] += $value['room_day'];   
                    }
                    if($room_arrival_time['year'] <= $current_year['year'] - 1 AND $room_departure_time['year'] >= $current_year['year'] - 1)
                    {
                        $items['w_online_reserve'][$current_year['year'] - 1]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['w_online_reserve'][$current_year['year'] - 1]['guest_day'] += 1;
                        else
                            $items['w_online_reserve'][$current_year['year'] - 1]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['w_online_reserve'][$current_year['year'] - 1]['room_day'] += 1;
                        else
                            $items['w_online_reserve'][$current_year['year'] - 1]['room_day'] += $value['room_day'];   
                    }
                }
                //khach san tu ky ket
                if($value['guest_level'] == 1 AND $value['group_name'] == 'WALK_IN')
                {
                    if($room_arrival_time['year'] <= $current_year['year'] - 2 AND $room_departure_time['year'] >= $current_year['year'] - 2)
                    {
                        $items['w_walkin_reserve'][$current_year['year'] - 2]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['w_walkin_reserve'][$current_year['year'] - 2]['guest_day'] += 1;
                        else
                            $items['w_walkin_reserve'][$current_year['year'] - 2]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['w_walkin_reserve'][$current_year['year'] - 2]['room_day'] += 1;
                        else
                            $items['w_walkin_reserve'][$current_year['year'] - 2]['room_day'] += $value['room_day'];   
                    }
                    if($room_arrival_time['year'] <= $current_year['year'] - 1 AND $room_departure_time['year'] >= $current_year['year'] - 1)
                    {
                        $items['w_walkin_reserve'][$current_year['year'] - 1]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['w_walkin_reserve'][$current_year['year'] - 1]['guest_day'] += 1;
                        else
                            $items['w_walkin_reserve'][$current_year['year'] - 1]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['w_walkin_reserve'][$current_year['year'] - 1]['room_day'] += 1;
                        else
                            $items['w_walkin_reserve'][$current_year['year'] - 1]['room_day'] += $value['room_day'];   
                    }
                }
                //khach le
                if($value['guest_level'] == 3 AND $value['group_name'] == 'WALK_IN')
                {
                    if($room_arrival_time['year'] <= $current_year['year'] - 2 AND $room_departure_time['year'] >= $current_year['year'] - 2)
                    {
                        $items['single_business'][$current_year['year'] - 2]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['single_business'][$current_year['year'] - 2]['guest_day'] += 1;
                        else
                            $items['single_business'][$current_year['year'] - 2]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['single_business'][$current_year['year'] - 2]['room_day'] += 1;
                        else
                            $items['single_business'][$current_year['year'] - 2]['room_day'] += $value['room_day'];   
                    }
                    if($room_arrival_time['year'] <= $current_year['year'] - 1 AND $room_departure_time['year'] >= $current_year['year'] - 1)
                    {
                        $items['single_business'][$current_year['year'] - 1]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['single_business'][$current_year['year'] - 1]['guest_day'] += 1;
                        else
                            $items['single_business'][$current_year['year'] - 1]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['single_business'][$current_year['year'] - 1]['room_day'] += 1;
                        else
                            $items['single_business'][$current_year['year'] - 1]['room_day'] += $value['room_day'];   
                    }
                }
                //khach khac
                if($value['guest_level'] == 2 AND $value['group_name'] == 'WALK_IN')
                {
                    if($room_arrival_time['year'] <= $current_year['year'] - 2 AND $room_departure_time['year'] >= $current_year['year'] - 2)
                    {
                        $items['other_business'][$current_year['year'] - 2]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['other_business'][$current_year['year'] - 2]['guest_day'] += 1;
                        else
                            $items['other_business'][$current_year['year'] - 2]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['other_business'][$current_year['year'] - 2]['room_day'] += 1;
                        else
                            $items['other_business'][$current_year['year'] - 2]['room_day'] += $value['room_day'];   
                    }
                    if($room_arrival_time['year'] <= $current_year['year'] - 1 AND $room_departure_time['year'] >= $current_year['year'] - 1)
                    {
                        $items['other_business'][$current_year['year'] - 1]['no_of_guest'] += 1;
                        if($value['guest_day'] == 0)
                            $items['other_business'][$current_year['year'] - 1]['guest_day'] += 1;
                        else
                            $items['other_business'][$current_year['year'] - 1]['guest_day'] += $value['guest_day'];
                        if($value['room_day'] == 0)
                            $items['other_business'][$current_year['year'] - 1]['room_day'] += 1;
                        else
                            $items['other_business'][$current_year['year'] - 1]['room_day'] += $value['room_day'];   
                    }
                }
            }
            
            //KHACH NOI DIA
            if($value['nationality_id'] == 439)
            {
                if($room_arrival_time['year'] <= $current_year['year'] - 2 AND $room_departure_time['year'] >= $current_year['year'] - 2)
                {
                    $domestic_items['domestic'][$current_year['year'] - 2]['no_of_guest'] += 1;
                    if($value['guest_day'] == 0)
                        $domestic_items['domestic'][$current_year['year'] - 2]['guest_day'] += 1;
                    else
                        $domestic_items['domestic'][$current_year['year'] - 2]['guest_day'] += $value['guest_day'];
                    if($value['room_day'] == 0)
                        $domestic_items['domestic'][$current_year['year'] - 2]['room_day'] += 1;
                    else
                        $domestic_items['domestic'][$current_year['year'] - 2]['room_day'] += $value['room_day'];   
                }
                if($room_arrival_time['year'] <= $current_year['year'] - 1 AND $room_departure_time['year'] >= $current_year['year'] - 1)
                {
                    $domestic_items['domestic'][$current_year['year'] - 1]['no_of_guest'] += 1;
                    if($value['guest_day'] == 0)
                        $domestic_items['domestic'][$current_year['year'] - 1]['guest_day'] += 1;
                    else
                        $domestic_items['domestic'][$current_year['year'] - 1]['guest_day'] += $value['guest_day'];
                    if($value['room_day'] == 0)
                        $domestic_items['domestic'][$current_year['year'] - 1]['room_day'] += 1;
                    else
                        $domestic_items['domestic'][$current_year['year'] - 1]['room_day'] += $value['room_day'];   
                }
            }
            //khach du lich
            if($value['nationality_id'] == 439 AND $value['group_name'] == 'TRAVEL')
            {
                if($room_arrival_time['year'] <= $current_year['year'] - 2 AND $room_departure_time['year'] >= $current_year['year'] - 2)
                {
                    $domestic_items['domestic_guest'][$current_year['year'] - 2]['no_of_guest'] += 1;
                    if($value['guest_day'] == 0)
                        $domestic_items['domestic_guest'][$current_year['year'] - 2]['guest_day'] += 1;
                    else
                        $domestic_items['domestic_guest'][$current_year['year'] - 2]['guest_day'] += $value['guest_day'];
                    if($value['room_day'] == 0)
                        $domestic_items['domestic_guest'][$current_year['year'] - 2]['room_day'] += 1;
                    else
                        $domestic_items['domestic_guest'][$current_year['year'] - 2]['room_day'] += $value['room_day'];   
                }
                if($room_arrival_time['year'] <= $current_year['year'] - 1 AND $room_departure_time['year'] >= $current_year['year'] - 1)
                {
                    $domestic_items['domestic_guest'][$current_year['year'] - 1]['no_of_guest'] += 1;
                    if($value['guest_day'] == 0)
                        $domestic_items['domestic_guest'][$current_year['year'] - 1]['guest_day'] += 1;
                    else
                        $domestic_items['domestic_guest'][$current_year['year'] - 1]['guest_day'] += $value['guest_day'];
                    if($value['room_day'] == 0)
                        $domestic_items['domestic_guest'][$current_year['year'] - 1]['room_day'] += 1;
                    else
                        $domestic_items['domestic_guest'][$current_year['year'] - 1]['room_day'] += $value['room_day'];   
                }
            }
            //khach hoi nghi
            if($value['nationality_id'] == 439 AND $value['guest_level'] == 4)
            {
                if($room_arrival_time['year'] <= $current_year['year'] - 2 AND $room_departure_time['year'] >= $current_year['year'] - 2)
                {
                    $domestic_items['domestic_meeting_guest'][$current_year['year'] - 2]['no_of_guest'] += 1;
                    if($value['guest_day'] == 0)
                        $domestic_items['domestic_meeting_guest'][$current_year['year'] - 2]['guest_day'] += 1;
                    else
                        $domestic_items['domestic_meeting_guest'][$current_year['year'] - 2]['guest_day'] += $value['guest_day'];
                    if($value['room_day'] == 0)
                        $domestic_items['domestic_meeting_guest'][$current_year['year'] - 2]['room_day'] += 1;
                    else
                        $domestic_items['domestic_meeting_guest'][$current_year['year'] - 2]['room_day'] += $value['room_day'];   
                }
                if($room_arrival_time['year'] <= $current_year['year'] - 1 AND $room_departure_time['year'] >= $current_year['year'] - 1)
                {
                    $domestic_items['domestic_meeting_guest'][$current_year['year'] - 1]['no_of_guest'] += 1;
                    if($value['guest_day'] == 0)
                        $domestic_items['domestic_meeting_guest'][$current_year['year'] - 1]['guest_day'] += 1;
                    else
                        $domestic_items['domestic_meeting_guest'][$current_year['year'] - 1]['guest_day'] += $value['guest_day'];
                    if($value['room_day'] == 0)
                        $domestic_items['domestic_meeting_guest'][$current_year['year'] - 1]['room_day'] += 1;
                    else
                        $domestic_items['domestic_meeting_guest'][$current_year['year'] - 1]['room_day'] += $value['room_day'];   
                }
            }
            //khach le noi dia
            if($value['nationality_id'] == 439 AND $value['guest_level'] == 2||1||3)
            {
                if($room_arrival_time['year'] <= $current_year['year'] - 2 AND $room_departure_time['year'] >= $current_year['year'] - 2)
                {
                    $domestic_items['domestic_singlge_guest'][$current_year['year'] - 2]['no_of_guest'] += 1;
                    if($value['guest_day'] == 0)
                        $domestic_items['domestic_singlge_guest'][$current_year['year'] - 2]['guest_day'] += 1;
                    else
                        $domestic_items['domestic_singlge_guest'][$current_year['year'] - 2]['guest_day'] += $value['guest_day'];
                    if($value['room_day'] == 0)
                        $domestic_items['domestic_singlge_guest'][$current_year['year'] - 2]['room_day'] += 1;
                    else
                        $domestic_items['domestic_singlge_guest'][$current_year['year'] - 2]['room_day'] += $value['room_day'];   
                }
                if($room_arrival_time['year'] <= $current_year['year'] - 1 AND $room_departure_time['year'] >= $current_year['year'] - 1)
                {
                    $domestic_items['domestic_singlge_guest'][$current_year['year'] - 1]['no_of_guest'] += 1;
                    if($value['guest_day'] == 0)
                        $domestic_items['domestic_singlge_guest'][$current_year['year'] - 1]['guest_day'] += 1;
                    else
                        $domestic_items['domestic_singlge_guest'][$current_year['year'] - 1]['guest_day'] += $value['guest_day'];
                    if($value['room_day'] == 0)
                        $domestic_items['domestic_singlge_guest'][$current_year['year'] - 1]['room_day'] += 1;
                    else
                        $domestic_items['domestic_singlge_guest'][$current_year['year'] - 1]['room_day'] += $value['room_day'];   
                }
            }
            
            
        }
        //Phan theo quoc tich
        $nation_sql =   'select 
                        country.id as id,
                        country.name_1,
                        count(country.id)
                        from
                        traveller inner join reservation_traveller on traveller.id = reservation_traveller.traveller_id
                        inner join country on traveller.nationality_id = country.id
                        where country.selected_report = 1
                        group by country.id, name_1
                        order by count(country.id)';
        $nationality = DB::fetch_all($nation_sql);
        $nationality_items = array();
        $nationality_items['other_country'][$current_year['year'] - 2]['no_of_guest'] = 0;
        $nationality_items['other_country'][$current_year['year'] - 2]['guest_day'] = 0;
        $nationality_items['other_country'][$current_year['year'] - 2]['room_day'] = 0;
        $nationality_items['other_country'][$current_year['year'] - 1]['no_of_guest'] = 0;
        $nationality_items['other_country'][$current_year['year'] - 1]['guest_day'] = 0;
        $nationality_items['other_country'][$current_year['year'] - 1]['room_day'] = 0;
        $nationality_items['other_country']['name'] = 'other_country';
        foreach($nationality as $key => $value)
        {
            if ($value['id']!=439)
            {
                $nationality_items[$value['id']]['name'] = $value['name_1'];
                $nationality_items[$value['id']][$current_year['year'] - 2]['no_of_guest'] = 0;
                $nationality_items[$value['id']][$current_year['year'] - 2]['guest_day'] = 0;
                $nationality_items[$value['id']][$current_year['year'] - 2]['room_day'] = 0; 
                
                $nationality_items[$value['id']][$current_year['year'] - 1]['no_of_guest'] = 0; 
                $nationality_items[$value['id']][$current_year['year'] - 1]['guest_day'] = 0; 
                $nationality_items[$value['id']][$current_year['year'] - 1]['room_day'] = 0;
            }   
            foreach($all_arr as $k => $v)
            {
                $room_arrival_time = getdate($v['time_in']);
                $room_departure_time = getdate($v['time_out']);
                $guest_arrival_time = getdate($v['arrival_time']);
                $guest_departure_time = getdate($v['departure_time']);
                if ($v['nationality_id']==$value['id'] && $value['id']!=439)
                {
                    //$nationality_items[$value['id']]['name'] = $value['name_1'];
                    if($room_arrival_time['year'] <= $current_year['year'] - 2 AND $room_departure_time['year'] >= $current_year['year'] - 2)
                    {
                        $nationality_items[$value['id']][$current_year['year'] - 2]['no_of_guest'] += 1;
                        if($v['guest_day'] == 0)
                            $nationality_items[$value['id']][$current_year['year'] - 2]['guest_day'] += 1;
                        else
                            $nationality_items[$value['id']][$current_year['year'] - 2]['guest_day'] += $v['guest_day'];
                        if($v['room_day'] == 0)
                            $nationality_items[$value['id']][$current_year['year'] - 2]['room_day'] += 1;
                        else
                            $nationality_items[$value['id']][$current_year['year'] - 2]['room_day'] += $v['room_day'];   
                    }
                    if($room_arrival_time['year'] <= $current_year['year'] - 1 AND $room_departure_time['year'] >= $current_year['year'] - 1)
                    {
                        $nationality_items[$value['id']][$current_year['year'] - 1]['no_of_guest'] += 1;
                        if($v['guest_day'] == 0)
                            $nationality_items[$value['id']][$current_year['year'] - 1]['guest_day'] += 1;
                        else
                            $nationality_items[$value['id']][$current_year['year'] - 1]['guest_day'] += $v['guest_day'];
                        if($v['room_day'] == 0)
                            $nationality_items[$value['id']][$current_year['year'] - 1]['room_day'] += 1;
                        else
                            $nationality_items[$value['id']][$current_year['year'] - 1]['room_day'] += $v['room_day'];   
                    }
                }
                if ($v['nationality_id']!=$value['id'] && $value['id']!=439)
                {
                    if($room_arrival_time['year'] <= $current_year['year'] - 2 AND $room_departure_time['year'] >= $current_year['year'] - 2)
                    {
                        $nationality_items['other_country'][$current_year['year'] - 2]['no_of_guest'] += 1;
                        if($v['guest_day'] == 0)
                            $nationality_items['other_country'][$current_year['year'] - 2]['guest_day'] += 1;
                        else
                            $nationality_items['other_country'][$current_year['year'] - 2]['guest_day'] += $v['guest_day'];
                        if($v['room_day'] == 0)
                            $nationality_items['other_country'][$current_year['year'] - 2]['room_day'] += 1;
                        else
                            $nationality_items['other_country'][$current_year['year'] - 2]['room_day'] += $v['room_day'];   
                    }
                    if($room_arrival_time['year'] <= $current_year['year'] - 1 AND $room_departure_time['year'] >= $current_year['year'] - 1)
                    {
                        $nationality_items['other_country'][$current_year['year'] - 1]['no_of_guest'] += 1;
                        if($v['guest_day'] == 0)
                            $nationality_items['other_country'][$current_year['year'] - 1]['guest_day'] += 1;
                        else
                            $nationality_items['other_country'][$current_year['year'] - 1]['guest_day'] += $v['guest_day'];
                        if($v['room_day'] == 0)
                            $nationality_items['other_country'][$current_year['year'] - 1]['room_day'] += 1;
                        else
                            $nationality_items['other_country'][$current_year['year'] - 1]['room_day'] += $v['room_day'];   
                    }
                }
            }
        }
        sort($nationality_items);
        //System::debug($nationality_items);
        //tinh cong suat phong cho tung nam
        $room_capacity[$current_year['year']-2] = 0;
        $room_capacity[$current_year['year']-1] = 0;
        foreach($all_arr as $key => $value)
        {
            $room_arrival_time = getdate($value['time_in']);
            $room_departure_time = getdate($value['time_out']);
            //echo $room_arrival_time ['year']. '<br>';
            //echo $room_departure_time ['year']. '<br>';
            if($room_arrival_time['year'] <= $current_year['year'] - 2 AND $room_departure_time['year'] >= $current_year['year'] - 2)
            {
                if($value['room_day'] == 0)
                    $room_capacity[$current_year['year']-2] += 1;
                else
                    $room_capacity[$current_year['year']-2] += $value['room_day'];
            }
            if($room_arrival_time['year'] <= $current_year['year'] - 1 AND $room_departure_time['year'] >= $current_year['year']-1)
            {
                if($value['room_day'] == 0)
                    $room_capacity[$current_year['year']-1] += 1;
                else
                    $room_capacity[$current_year['year']-1] += $value['room_day'];
            }
        }
        //System::debug($room_capacity);
        if(Url::check('by_year') or Url::check('by_month'))
        {   
            //System::debug($items);
            $this -> map['room_capacity'] = $room_capacity;
            $this -> map['items'] = $items;
            $this -> map['domestic_items'] = $domestic_items;
            $this -> map['nationality_items'] = $nationality_items;
            $this->parse_layout('report',$this->map);
        }
        else
        {
            $this->parse_layout('search',$this->map);    
        }
    }
}
?>