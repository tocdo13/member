<?php
class GuestReportByProvinceForm extends Form
{
    function GuestReportByProvinceForm()
    {
        Form::Form('GuestReportByProvinceForm');
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
                    province.id as province_id,
                    province.code as province_code
                from 
                    reservation_room inner join reservation on reservation_room.reservation_id = reservation.id
                    left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                    left outer join traveller on reservation_traveller.traveller_id = traveller.id
                    left outer join country on traveller.nationality_id = country.id
                    left outer join province on reservation_traveller.province_id = province.id
                where
                    reservation_room.status =\'CHECKIN\' '.$cond.'
                    and reservation_traveller.status !=\'CHECKOUT\'
                    and (traveller.first_name || \' \' || traveller.last_name) != \' \'
                    and (country.code_1 = \'VIN\' or country.code_2 = \'VNM\')  
                    and reservation_room.note_change_room is null                
                order by reservation_room_code ';
        
        //echo $sql;
        $report = new Report;
        $report->items = DB::fetch_all($sql);
        $this->map['in_HCM'] = 0;
        $this->map['not_in_HCM'] = 0;
        $this->map['in_HCM_today'] = 0;
        $this->map['not_in_HCM_today'] = 0;
        $this->map['total'] = 0;
        $this->map['total_today'] = 0;
        $nationality = array();
        //$guest_type = array();
        foreach($report->items as $key=>$item)
		{
            //Khach o tp HCM
            if($item['province_code']=='HCM')
            {
                $this->map['in_HCM']++;
                if(date('d/m/Y',$item['time_in']) == $this->map['date'])
                {
                    $this->map['in_HCM_today']++;
                }
            }
            else
            {
                $this->map['not_in_HCM'] ++;
                if(date('d/m/Y',$item['time_in']) == $this->map['date'])
                {
                    $this->map['not_in_HCM_today']++;    
                }
            }
		}
        $this->map['total'] = $this->map['in_HCM']+$this->map['not_in_HCM'];
        $this->map['total_today'] = $this->map['in_HCM_today']+$this->map['not_in_HCM_today'];
        $this->parse_layout('report',$this->map);
    }
}

?>