<?php
class ReportHistoryChangeRoomForm extends Form
{
	function ReportHistoryChangeRoomForm()
	{
		Form::Form('ReportHistoryChangeRoomForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_css('packages/hotel/packages/mice/skins/css/font-awesome-4.5.0/css/font-awesome.min.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}	
	function draw()
	{
	   $this->map = array();
       $cond = '1=1';
       
       $this->map['portal_id_list'] = String::get_list(Portal::get_portal_list());
       $this->map['portal_id'] = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
       $cond .= ' AND room_history.portal_id=\''.$this->map['portal_id'].'\'';
       
       $room_types = DB::fetch_all('SELECT room_type.id,room_type.name,room_type.brief_name FROM room_type');
       $room_areas = DB::fetch_all('select id,name_'.Portal::language().' as name from area_group where portal_id=\''.$this->map['portal_id'].'\'');
       
       
       $room_levels = DB::fetch_all('SELECT room_level.id,room_level.name,room_level.brief_name FROM room_level WHERE portal_id=\''.$this->map['portal_id'].'\' ORDER BY name');
       $this->map['room_level_id_list'] = array(''=>Portal::language('all'))+String::get_list($room_levels);
       $this->map['room_level_id'] = Url::get('room_level_id')?Url::get('room_level_id'):'';
       if($this->map['room_level_id']!='')
            $cond .= ' AND room_history_detail.room_level_id='.$this->map['room_level_id'].'';
       
       $rooms = DB::fetch_all("SELECT room.name as id, room.name as name FROM room WHERE portal_id='".$this->map['portal_id']."' ORDER BY room.name");
       $this->map['room_id_list'] = array(''=>Portal::language('all'))+String::get_list($rooms);
       $this->map['room_id'] = Url::get('room_id')?Url::get('room_id'):'';
       if($this->map['room_id']!='')
            $cond .= ' AND room_history_detail.name=\''.$this->map['room_id'].'\'';
       
       $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):'01'.date('/m/Y');
       $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y')).date('/m/Y');
       $_REQUEST['from_date'] = $this->map['from_date'];
       $_REQUEST['to_date'] = $this->map['to_date'];
       
       $cond .= ' AND room_history.in_date>=\''.Date_Time::to_orc_date($this->map['from_date']).'\'';
       $cond .= ' AND room_history.in_date<=\''.Date_Time::to_orc_date($this->map['to_date']).'\'';
       
       // lay ra lich su
       $history_detail = DB::fetch_all('
                                SELECT
                                    room_history_detail.*,
                                    room_history.create_time,
                                    TO_CHAR(room_history.in_date,\'DD/MM/YYYY\') as in_date,
                                    TO_CHAR(room_history.start_date,\'DD/MM/YYYY\') as start_date,
                                    TO_CHAR(room_history.end_date,\'DD/MM/YYYY\') as end_date,
                                    room_history.start_time,
                                    room_history.end_time
                                FROM
                                    room_history_detail
                                    inner join room_history on room_history.id=room_history_detail.room_history_id
                                WHERE
                                    '.$cond.'
                                ORDER BY
                                    room_history.in_date
                                ');
       $history = array();
       foreach($history_detail as $key=>$value)
       {
            if(!isset($history[$value['room_history_id']]))
            {
                $history[$value['room_history_id']]['id'] = $value['room_history_id'];
                $history[$value['room_history_id']]['create_time'] = $value['create_time'];
                $history[$value['room_history_id']]['in_date'] = $value['in_date'];
                $history[$value['room_history_id']]['start_date'] = $value['start_date'];
                $history[$value['room_history_id']]['end_date'] = $value['end_date'];
                $history[$value['room_history_id']]['start_time'] = $value['start_time'];
                $history[$value['room_history_id']]['end_time'] = $value['end_time'];
                $history[$value['room_history_id']]['child'] = array();
            }
            unset($value['create_time']); unset($value['in_date']); unset($value['end_time']);
            unset($value['start_date']); unset($value['end_date']); unset($value['start_time']); 
            
            $history[$value['room_history_id']]['child'][$value['room_id']] = $value;
            $history[$value['room_history_id']]['child'][$value['room_id']]['id'] = $value['room_id'];
            
       }
       $history_before = array();
       foreach($history as $id=>$content)
       {
            $history[$id]['log'] = array(); 
            if(sizeof($history_before)==0)
            {
                $history_before = $this->get_history_before($content['in_date']);
            }
            if(sizeof($history_before)==0)
            {
                foreach($content['child'] as $id_child=>$value_child)
                {
                    $history[$id]['log'][$value_child['name']]['id'] = $value_child['name'];
                    $history[$id]['log'][$value_child['name']]['des'] = Portal::language('start_log_history').': <br/>'.'
                                                                            '.Portal::language('room_level').': '.$room_levels[$value_child['room_level_id']]['brief_name'].'
                                                                            '.Portal::language('room_type').': '.$room_types[$value_child['room_type_id']]['brief_name'].'
                                                                            '.Portal::language('floor').': '.$value_child['floor'].'
                                                                            '.Portal::language('area').': '.(isset($room_areas[$value_child['area_id']]['name'])?$room_areas[$value_child['area_id']]['name']:'').'
                                                                            '.Portal::language('status').': '.($value_child['close_room']==1?'SHOW':'HIDE').'
                                                                            ';
                    $history[$id]['log'][$value_child['name']]['child'][$id_child] = $value_child;
                }
                $history_before = $content['child'];
                unset($history[$id]['child']);
            }
            else
            {
                // so sanh du lieu truoc va sau;
                foreach($content['child'] as $id_child=>$value_child)
                {
                    if(isset($history_before[$value_child['id']]))
                    {
                        if($history_before[$value_child['id']]['room_level_id']!=$value_child['room_level_id']
                           OR $history_before[$value_child['id']]['room_type_id']!=$value_child['room_type_id']
                           OR $history_before[$value_child['id']]['floor']!=$value_child['floor']
                           OR ($history_before[$value_child['id']]['area_id']!=$value_child['area_id'] and isset($room_areas[$history_before[$value_child['id']]['area_id']]) and isset($room_areas[$value_child['area_id']]))
                           OR $history_before[$value_child['id']]['close_room']!=$value_child['close_room']
                           )
                        {
                            
                            if(!isset($history[$id]['log'][$value_child['name']]))
                            {
                                $history[$id]['log'][$value_child['name']]['id'] = $value_child['name'];
                                $history[$id]['log'][$value_child['name']]['des'] = '<b>'.Portal::language('edit').'</b>: <br/>'.'
                                                                                        '.(($history_before[$value_child['id']]['room_level_id']!=$value_child['room_level_id'])?Portal::language('room_level').': '.Portal::language('from').' '.$room_levels[$history_before[$value_child['id']]['room_level_id']]['brief_name'].' '.Portal::language('to').' '.$room_levels[$value_child['room_level_id']]['brief_name'].'<br/>':'').'
                                                                                        '.(($history_before[$value_child['id']]['room_type_id']!=$value_child['room_type_id'])?Portal::language('room_type').': '.Portal::language('from').' '.$room_types[$history_before[$value_child['id']]['room_type_id']]['brief_name'].' '.Portal::language('to').' '.$room_types[$value_child['room_type_id']]['brief_name'].'<br/>':'').'
                                                                                        '.(($history_before[$value_child['id']]['floor']!=$value_child['floor'])?Portal::language('floor').': '.Portal::language('from').' '.$history_before[$value_child['id']]['floor'].' '.Portal::language('to').' '.$value_child['floor'].'<br/>':'').'
                                                                                        '.(($history_before[$value_child['id']]['area_id']!=$value_child['area_id'] and isset($room_areas[$history_before[$value_child['id']]['area_id']]) and isset($room_areas[$value_child['area_id']]))?Portal::language('area').': '.Portal::language('from').' '.$room_areas[$history_before[$value_child['id']]['area_id']]['name'].' '.Portal::language('to').' '.$room_areas[$value_child['area_id']]['name'].'<br/>':'').'
                                                                                        '.(($history_before[$value_child['id']]['close_room']!=$value_child['close_room'])?Portal::language('status').': '.Portal::language('from').' '.($history_before[$value_child['id']]['close_room']==1?'SHOW':'HIDE').' '.Portal::language('to').' '.($value_child['close_room']==1?'SHOW':'HIDE').'<br/>':'').'
                                                                                        ';
                                $history[$id]['log'][$value_child['name']]['child'][$id_child]['des'] = '<b>'.Portal::language('edit').'</b>: <br/>'.'
                                                                                        '.(($history_before[$value_child['id']]['room_level_id']!=$value_child['room_level_id'])?Portal::language('room_level').': '.Portal::language('from').' '.$room_levels[$history_before[$value_child['id']]['room_level_id']]['brief_name'].' '.Portal::language('to').' '.$room_levels[$value_child['room_level_id']]['brief_name'].'<br/>':'').'
                                                                                        '.(($history_before[$value_child['id']]['room_type_id']!=$value_child['room_type_id'])?Portal::language('room_type').': '.Portal::language('from').' '.$room_types[$history_before[$value_child['id']]['room_type_id']]['brief_name'].' '.Portal::language('to').' '.$room_types[$value_child['room_type_id']]['brief_name'].'<br/>':'').'
                                                                                        '.(($history_before[$value_child['id']]['floor']!=$value_child['floor'])?Portal::language('floor').': '.Portal::language('from').' '.$history_before[$value_child['id']]['floor'].' '.Portal::language('to').' '.$value_child['floor'].'<br/>':'').'
                                                                                        '.(($history_before[$value_child['id']]['area_id']!=$value_child['area_id'] and isset($room_areas[$history_before[$value_child['id']]['area_id']]) and isset($room_areas[$value_child['area_id']]))?Portal::language('area').': '.Portal::language('from').' '.$room_areas[$history_before[$value_child['id']]['area_id']]['name'].' '.Portal::language('to').' '.$room_areas[$value_child['area_id']]['name'].'<br/>':'').'
                                                                                        '.(($history_before[$value_child['id']]['close_room']!=$value_child['close_room'])?Portal::language('status').': '.Portal::language('from').' '.($history_before[$value_child['id']]['close_room']==1?'SHOW':'HIDE').' '.Portal::language('to').' '.($value_child['close_room']==1?'SHOW':'HIDE').'<br/>':'').'
                                                                                        ';
                            }
                            else
                            {
                                System::debug($history[$id]['log'][$value_child['name']]);
                                $history[$id]['log'][$value_child['name']]['des'] .= '<b>'.Portal::language('edit').'</b>: <br/>'.'
                                                                                        '.(($history_before[$value_child['id']]['room_level_id']!=$value_child['room_level_id'])?Portal::language('room_level').': '.Portal::language('from').' '.$room_levels[$history_before[$value_child['id']]['room_level_id']]['brief_name'].' '.Portal::language('to').' '.$room_levels[$value_child['room_level_id']]['brief_name'].'<br/>':'').'
                                                                                        '.(($history_before[$value_child['id']]['room_type_id']!=$value_child['room_type_id'])?Portal::language('room_type').': '.Portal::language('from').' '.$room_types[$history_before[$value_child['id']]['room_type_id']]['brief_name'].' '.Portal::language('to').' '.$room_types[$value_child['room_type_id']]['brief_name'].'<br/>':'').'
                                                                                        '.(($history_before[$value_child['id']]['floor']!=$value_child['floor'])?Portal::language('floor').': '.Portal::language('from').' '.$history_before[$value_child['id']]['floor'].' '.Portal::language('to').' '.$value_child['floor'].'<br/>':'').'
                                                                                        '.(($history_before[$value_child['id']]['area_id']!=$value_child['area_id'] and isset($room_areas[$history_before[$value_child['id']]['area_id']]) and isset($room_areas[$value_child['area_id']]))?Portal::language('area').': '.Portal::language('from').' '.$room_areas[$history_before[$value_child['id']]['area_id']]['name'].' '.Portal::language('to').' '.$room_areas[$value_child['area_id']]['name'].'<br/>':'').'
                                                                                        '.(($history_before[$value_child['id']]['close_room']!=$value_child['close_room'])?Portal::language('status').': '.Portal::language('from').' '.($history_before[$value_child['id']]['close_room']==1?'SHOW':'HIDE').' '.Portal::language('to').' '.($value_child['close_room']==1?'SHOW':'HIDE').'<br/>':'').'
                                                                                        ';
                                $history[$id]['log'][$value_child['name']]['child'][$id_child]['des'] = '<b>'.Portal::language('edit').'</b>: <br/>'.'
                                                                                        '.(($history_before[$value_child['id']]['room_level_id']!=$value_child['room_level_id'])?Portal::language('room_level').': '.Portal::language('from').' '.$room_levels[$history_before[$value_child['id']]['room_level_id']]['brief_name'].' '.Portal::language('to').' '.$room_levels[$value_child['room_level_id']]['brief_name'].'<br/>':'').'
                                                                                        '.(($history_before[$value_child['id']]['room_type_id']!=$value_child['room_type_id'])?Portal::language('room_type').': '.Portal::language('from').' '.$room_types[$history_before[$value_child['id']]['room_type_id']]['brief_name'].' '.Portal::language('to').' '.$room_types[$value_child['room_type_id']]['brief_name'].'<br/>':'').'
                                                                                        '.(($history_before[$value_child['id']]['floor']!=$value_child['floor'])?Portal::language('floor').': '.Portal::language('from').' '.$history_before[$value_child['id']]['floor'].' '.Portal::language('to').' '.$value_child['floor'].'<br/>':'').'
                                                                                        '.(($history_before[$value_child['id']]['area_id']!=$value_child['area_id'] and isset($room_areas[$history_before[$value_child['id']]['area_id']]) and isset($room_areas[$value_child['area_id']]))?Portal::language('area').': '.Portal::language('from').' '.$room_areas[$history_before[$value_child['id']]['area_id']]['name'].' '.Portal::language('to').' '.$room_areas[$value_child['area_id']]['name'].'<br/>':'').'
                                                                                        '.(($history_before[$value_child['id']]['close_room']!=$value_child['close_room'])?Portal::language('status').': '.Portal::language('from').' '.($history_before[$value_child['id']]['close_room']==1?'SHOW':'HIDE').' '.Portal::language('to').' '.($value_child['close_room']==1?'SHOW':'HIDE').'<br/>':'').'
                                                                                        ';
                            }
                        }
                    }
                    else
                    {
                        
                        if(!isset($history[$id]['log'][$value_child['name']]))
                        {
                            $history[$id]['log'][$value_child['name']]['id'] = $value_child['name'];
                            $history[$id]['log'][$value_child['name']]['des'] = '<b>'.Portal::language('add').'</b>: <br/>'.'
                                                                                    '.Portal::language('room_level').': '.$room_levels[$value_child['room_level_id']]['brief_name'].'<br/>'.'
                                                                                    '.Portal::language('room_type').': '.$room_types[$value_child['room_type_id']]['brief_name'].'<br/>'.'
                                                                                    '.Portal::language('floor').': '.$value_child['floor'].'<br/>'.'
                                                                                    '.Portal::language('status').': '.($value_child['close_room']==1?'SHOW':'HIDE').'<br/>'.'
                                                                                    ';
                            $history[$id]['log'][$value_child['name']]['child'][$id_child]['des'] = '<b>'.Portal::language('add').'</b>: <br/>'.'
                                                                                    '.Portal::language('room_level').': '.$room_levels[$value_child['room_level_id']]['brief_name'].'<br/>'.'
                                                                                    '.Portal::language('room_type').': '.$room_types[$value_child['room_type_id']]['brief_name'].'<br/>'.'
                                                                                    '.Portal::language('floor').': '.$value_child['floor'].'<br/>'.'
                                                                                    '.Portal::language('status').': '.($value_child['close_room']==1?'SHOW':'HIDE').'<br/>'.'
                                                                                    ';
                                                                                                                
                        }
                        else
                        {
                            $history[$id]['log'][$value_child['name']]['des'] .= '<b>'.Portal::language('add').'</b>: <br/>'.'
                                                                                    '.Portal::language('room_level').': '.$room_levels[$value_child['room_level_id']]['brief_name'].'<br/>'.'
                                                                                    '.Portal::language('room_type').': '.$room_types[$value_child['room_type_id']]['brief_name'].'<br/>'.'
                                                                                    '.Portal::language('floor').': '.$value_child['floor'].'<br/>'.'
                                                                                    '.Portal::language('area').': '.$room_areas[$value_child['area_id']]['name'].'<br/>'.'
                                                                                    '.Portal::language('status').': '.($value_child['close_room']==1?'SHOW':'HIDE').'<br/>'.'
                                                                                    ';
                            $history[$id]['log'][$value_child['name']]['child'][$id_child]['des'] = '<b>'.Portal::language('add').'</b>: <br/>'.'
                                                                                    '.Portal::language('room_level').': '.$room_levels[$value_child['room_level_id']]['brief_name'].'<br/>'.'
                                                                                    '.Portal::language('room_type').': '.$room_types[$value_child['room_type_id']]['brief_name'].'<br/>'.'
                                                                                    '.Portal::language('floor').': '.$value_child['floor'].'<br/>'.'
                                                                                    '.Portal::language('area').': '.$room_areas[$value_child['area_id']]['name'].'<br/>'.'
                                                                                    '.Portal::language('status').': '.($value_child['close_room']==1?'SHOW':'HIDE').'<br/>'.'
                                                                                    ';
                        }
                    }
                }
                $history_before = $content['child'];
                unset($history[$id]['child']);
            }
            
            if(sizeof($history[$id]['log'])==0)
            {
                $history[$id]['log']['all']['id'] = Portal::language('all');
                $history[$id]['log']['all']['des'] = '<b>'.Portal::language('no_change').'</b>';
                unset($history[$id]);
            }
       }
       //if(User::id()=='developer05')
        //System::debug($history);
       $this->map['items'] = $history;
	   $this->parse_layout('report',$this->map);
	}
    
    function get_history_before($in_date)
    {
        if($in_date_before = DB::fetch('SELECT max(in_date) as in_date FROM room_history WHERE in_date<\''.Date_Time::to_orc_date($in_date).'\' AND portal_id=\''.$this->map['portal_id'].'\'','in_date'))
        {
            return DB::fetch_all('SELECT room_history_detail.*,room_history_detail.room_id as id  FROM room_history_detail inner join room_history on room_history.id=room_history_detail.room_history_id WHERE room_history.in_date=\''.$in_date_before.'\' AND room_history.portal_id=\''.$this->map['portal_id'].'\'');
        }
        else
        {
            return array();
        }
    }
}
?>
