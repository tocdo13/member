<?php
class CreateGroupKeyForm extends Form
{
    function CreateGroupKeyForm()
    {
        Form::Form('CreateGroupKeyForm');
        $this->link_js('packages/core/includes/js/multi_items.js');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.mask.min.js');
    }
    
    function draw()
    {
        if(isset($_REQUEST['create']))
        {
            $from_orbita_layout = 0;
            if(Url::get('from_orbita_layout'))
            {
                $from_orbita_layout = 1;
            }
            $this->show_detail_list($from_orbita_layout);
        }
        else
        {
           $this->load(); 
        }    
    }
    function show_detail_list($from_orbita_layout)
    {
        $this->map = array();
        $reservation =Url::get('reservation_id');
        $encoder = $_REQUEST['reception_id'];
        
        if($from_orbita_layout == 1)
        {
           $items = $_SESSION['mi_group_orbita']; 
        }
        else{
            $items = $_REQUEST['mi_group'];
        }
        $this->map['items'] = $items;
        $this->parse_layout('create_group_fox_detail',$this->map);
        
        
    }
    function load()
    {
        $this->map = array();
        
        $arr_multi = DB::fetch_all("select lpad(room.name,6, '0') || '_' || reservation_room.id as id,
                                        reservation_room.id as rr_id,
                                        room.id as room_id,
                                        room.name as room_name,
                                        manage_doorid.door_id,
                                        manage_doorid.building,
                                        case
                                            when checkin_time_card is null
                                            then reservation_room.time_in
                                            else reservation_room.checkin_time_card
                                        end time_in,
                                        (reservation_room.time_out + 15*60) as time_out
                                    from reservation_room
                                        inner join room on room.id = reservation_room.room_id
                                        inner join manage_doorid on room.id=manage_doorid.room_id
                                        inner join reservation on reservation.id = reservation_room.reservation_id
                                    where reservation.id=".Url::get('reservation_id').(Url::get('rr_id')?" and reservation_room.id=".Url::get('rr_id'):"")." order by lpad(room.name,6, '0')");  
        foreach($arr_multi as $key=>$value)
        {
            $arr_multi[$key]['date_start'] = date('d/m/Y',$value['time_in']);
            $arr_multi[$key]['time_start'] = date('H:i',$value['time_in']);
            $arr_multi[$key]['date_expiry'] = date('d/m/Y',$value['time_out']);
            $arr_multi[$key]['time_expiry'] = date('H:i',$value['time_out']);
        }
        $_REQUEST['mi_group'] = $arr_multi;
        
        /** lay ra thong tin encoder **/
         $db_items = DB::fetch_all("SELECT id || '_' || ip as id, 
                                        reception as name
                                    FROM manage_ipsever
                                    ORDER by reception desc");
        $reception_id_options = '';
        foreach($db_items as $item)
        {
            $reception_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
        }
        $this->map['reception_id'] = $reception_id_options;
        /** end lay ra thong tin encoder **/
        /** lay ra danh sach cua chung **/
        $commdoor = DB::fetch_all("SELECT stt as id, 
                                        name
                                    FROM manage_commdoor
                                    WHERE name is not null
                                    ORDER by stt");
        
        $this->map['commdoor'] = $commdoor;
        /** end lay ra danh sach cua chung **/
        
        $this->parse_layout('create_group_fox',$this->map);
    }
    
}
?>