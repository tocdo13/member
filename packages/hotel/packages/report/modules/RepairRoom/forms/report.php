<?php
class RepairRoomForm extends Form
{
	function RepairRoomForm()
	{
		Form::Form('RepairRoomForm');		
        $this->link_css('packages/hotel/packages/human_resource_management/modules/HrmStaffDn/font-awesome/css/font-awesome.min.css');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
        $this->link_css('packages/hotel/skins/default/css/highlight-button.css');
	}
    function on_submit()
    {
        //System::debug($_REQUEST);exit();        
    }
	function draw()
	{      	    
        $this->map = array();
        $cond = '1=1';
        /** Tim kiem theo 2 khoang thoi gian giao nhau **/ 
                      
        //$cond .= ' AND room_status.start_date <= \''.$end_date.'\'';
        //$cond .= 'AND room_status.end_date >=\''.$start_date.'\'';
        Url::get('start_date')?$cond .= 'AND room_status.end_date >=\''.Date_Time::to_orc_date(Url::get('start_date')).'\'':'';
        Url::get('end_date')?$cond .= ' AND room_status.start_date <= \''.Date_Time::to_orc_date(Url::get('end_date')).'\'':$cond .= ' AND room_status.start_date <= \''.Date_Time::to_orc_date(date('d/m/Y')).'\'';
        if(Url::get('search'))
        {                                 
            Url::get('floors_name')?$cond .= ' AND room.floor = \''.Url::get('floors_name').'\'':'';                        
        }
        /** Tim kiem theo 2 khoang thoi gian giao nhau **/                
        $room_repair = DB::fetch_all('
            select 
              room.id || \'_\' || room_status.start_date || \'_\' || room_status.end_date as id
              ,room.id as room_id
              ,room.name
              ,room_level.name as room_level  
              ,room_status.note
              ,room_status.start_date
              ,room_status.end_date                
              ,party.name_'.Portal::language().' ||\'-\'|| room_status.user_repair as user_repair
            from
              room_status  
              left join room on room.id = room_status.room_id
              left join room_level on room.room_level_id = room_level.id                
              left join party on party.user_id = room_status.user_repair
            where 
              '.$cond.' 
              and room_status.house_status = \'REPAIR\'               
              order by room_status.start_date, room.id
        ');
        $i=1;
        foreach($room_repair as $key=>$value)
        {
            $room_repair[$key]['count'] = $i++;            
        }        
        $floors=DB::fetch_all('
            select
                room.floor as id 
           from 
                room
                inner join room_level ON room.room_level_id=room_level.id
           where 
                room_level.is_virtual=0 
                group by room.floor, room.position
                order by room.floor, room.position asc
         ');                            
         foreach($room_repair as $key=>$value)
         {            
            $room_repair[$key]['start_date'] = str_replace('-','/',Date_Time::convert_orc_date_to_date($value['start_date']));
            $room_repair[$key]['end_date'] = str_replace('-','/',Date_Time::convert_orc_date_to_date($value['end_date']));                                                
         }                    
         $k=1;  
         $floors_name = array();               
         foreach($floors as $key=>$value)
         {
              $floors_name[$k++]['id']=$value['id'];
         }                        
        $this->map['start_date_find'] = Url::get('start_date')?Url::get('start_date'): date('d/m/Y');
        $this->map['end_date_find'] = Url::get('end_date')?Url::get('end_date'):date('d/m/Y');                                                                                          
        $this->map['floors_name_list'] = array('' => Portal::language('all')) + String::get_list($floors_name);
        if(User::id()=='developer18')
        {
            //System::debug($room_repair);
        }                          
        $this->map['items'] = $room_repair; 
	    $this->parse_layout('report',$this->map);	
	}			
}
?>
