<?php
class ForeignGuestReportForm extends Form
{
    function ForeignGuestReportForm()
    {
        Form::Form('ForeignGuestReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');  
    }
    function draw()
    {
        require_once 'packages/core/includes/utils/time_select.php';
		require_once 'packages/core/includes/utils/lib/report.php';
        $this->map = array();
        if(Url::get('date'))
        {
            $this->map['date'] = Url::get('date');
        }
        else
        {
            $this->map['date'] = date('d/m/Y');
            $_REQUEST['date'] = $this->map['date'];
        }
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
        $cond ='';
        if($portal_id != 'ALL')
        {
            $cond.=' AND reservation.portal_id = \''.$portal_id.'\' '; 
        }
        $day_orc = Date_Time::to_orc_date($this->map['date']);
        $cond.='and ((reservation_room.change_room_from_rr is null and reservation_room.arrival_time <= \''.$day_orc.'\' and reservation_room.departure_time > \''.$day_orc.'\')
                         or
                         (reservation_room.change_room_from_rr is not null and from_unixtime(reservation_room.old_arrival_time) <= \''.$day_orc.'\' and reservation_room.departure_time > \''.$day_orc.'\')
                        )';
        
        //country_id = 99 l� Vietnam
        $sql = 'select
                    ROW_NUMBER() OVER (ORDER BY reservation_room.id Desc) as id,
                    reservation_traveller.id as reservation_traveller_id,
                    reservation_room.id as reservation_room_code,
                    reservation.id as reservation_id,
                    traveller.first_name || \' \' || traveller.last_name as fullname,
                    COALESCE(country.id,999999) as nationality_id,
                    COALESCE(country.name_'.Portal::language().',\'Other country\')  as nationality,
                    reservation_room.arrival_time, reservation_room.departure_time, 
                    reservation_room.time_in, reservation_room.time_out,
                    reservation_room.departure_time - reservation_room.arrival_time as night,
                    reservation_room.status,
                    traveller.id as traveller_id,
                    traveller.is_vn
                    
                from 
                    reservation_room inner join reservation on reservation_room.reservation_id = reservation.id
                    left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                    left outer join traveller on reservation_traveller.traveller_id = traveller.id
                    left outer join country on traveller.nationality_id = country.id
                    left outer join room on room.id=reservation_room.room_id
                    left join room_level on room.room_level_id = room_level.id
                where
                    reservation_room.status =\'CHECKIN\' '.$cond.'
                    and reservation_traveller.status !=\'CHECKOUT\'
                    and (traveller.first_name || \' \' || traveller.last_name) != \' \'
                    and country.id !=439 
                    and (room_level.is_virtual=0 or room_level.is_virtual is NULL )                 
                order by reservation_room_code ';
        
        //echo $sql;
        $report = new Report;
        $report->items = DB::fetch_all($sql);
        //System::debug($report->items);
        $nationality = array();
        $stt = 0;
        foreach($report->items as $key=>$item)
		{
            //neu chua co quoc tich nay thi them moi vao mang
            if(!isset($nationality[$item['nationality_id']]))
            {
                $nationality[$item['nationality_id']] = array('nationality'=>$item['nationality']);
                $nationality[$item['nationality_id']]['stt'] = ++$stt;
                //nuoc ngoai
                $nationality[$item['nationality_id']]['FOREIGN'] = 0;
                //VN dinh cu nuoc ngoai
                $nationality[$item['nationality_id']]['FOREIGN_isVN'] = 0;
                //Tong
                $nationality[$item['nationality_id']]['TOTAL'] = 0;
                //Tong trong ngay
                $nationality[$item['nationality_id']]['TOTAL_today'] = 0;
            }
            //dem so khach
            
            $nationality[$item['nationality_id']]['TOTAL']++;

            if(date('d/m/Y',$item['time_in']) == $this->map['date'])
            {
                $nationality[$item['nationality_id']]['TOTAL_today']++;  
            }
            
            if($item['is_vn'] ==1)
                $nationality[$item['nationality_id']]['FOREIGN_isVN']++;
            else
                $nationality[$item['nationality_id']]['FOREIGN']++;
		}
        
        
        $this->map['nationality'] = $nationality;
        //System::debug($nationality);
        //dem tong
        $total = array();
        $total['FOREIGN'] = 0;
        $total['FOREIGN_isVN'] = 0;
        $total['TOTAL'] = 0;
        $total['TOTAL_today'] = 0;
        
        foreach($nationality as $k=>$v)
        {
            foreach($total as $k1=>$v1)
            {
                $total[$k1]+= $v[$k1];
            }
        }
        $this->map['total'] = $total;  
        //System::debug($total);
        $this->parse_layout('report',$this->map);
    }
}

?>
