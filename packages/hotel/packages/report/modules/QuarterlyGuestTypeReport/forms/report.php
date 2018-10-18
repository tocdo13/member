<?php
class QuarterlyGuestTypeReportForm extends Form
{
    function QuarterlyGuestTypeReportForm()
    {
        Form::Form('QuarterlyGuestTypeReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
    }
    function draw()
    {
        $this->map = array();
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
        $from_date = Url::get('from_date')?Date_Time::to_time(Url::get('from_date')):Date_Time::to_time(date('d/m/Y'));
        $to_date = Url::get('to_date')?Date_Time::to_time(Url::get('to_date')):Date_Time::to_time(date('d/m/Y'));
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
        $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        $_REQUEST['from_date'] = $this->map['from_date'];
        $_REQUEST['to_date'] = $this->map['to_date'];
        //Lay cac loai khach
        $sql = '
                select
                    guest_type.*
                from guest_type
                order by group_name DESC, id
                ';
        
        $guest_type = DB::fetch_all($sql);
        $this->map['guest_type'] = $guest_type;
        
        //itemsbug($guest_type);
        $group_name = array();
        foreach($guest_type as $k=>$v)
		{
            if(!isset($group_name[$v['group_name']]))
                $group_name[$v['group_name']]=array('group_name'=>$v['group_name'],'colspan'=>1);
            else
                $group_name[$v['group_name']]['colspan']++;
		}
        $this->map['group_name'] = $group_name;
        $nationality = QuarterlyGuestTypeReportDB::get_data_for_days($from_date, $to_date, $cond);
        $this->map['nationality'] = $nationality;
        //itemsbug($nationality);
        //dem tong
        $total = array();
        foreach($guest_type as $k=>$v)
		{
            $total[$v['name']] = 0;
            $total[$v['name'].' today'] = 0;
		}
        
        $total['WALK_IN'] = 0;
        $total['WALK_IN today'] = 0;
        $total['TRAVEL'] = 0;
        $total['TRAVEL today'] = 0;
        $total['IS_ONLINE'] = 0;
        $total['IS_ONLINE today'] = 0;
        $total['TOTAL'] = 0;
        $total['TOTAL today'] = 0;
        $total['TOTAL NIGHT GUEST'] = 0;
        $total['total_room'] = 0;
        //$total['TOTAL NIGHT ROOM'] = 0;
        
        foreach($nationality as $k=>$v)
        {
            foreach($total as $k1=>$v1)
            {
                $total[$k1]+= $v[$k1];
            }
        }
        $this->map['total'] = $total;  
        //itemsbug($total);
        $this->parse_layout('report',$this->map);
    }
}

?>