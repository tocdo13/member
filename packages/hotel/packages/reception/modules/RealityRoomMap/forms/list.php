<?php
class RealityRoomMapForm extends Form{
    function RealityRoomMapForm(){
        Form::Form('RealityRoomMapForm');
        $this->link_css('packages/hotel/packages/reception/skins/default/css/realityroommap.css');
        $this->link_css('packages/core/includes/js/jquery/ui/jquery.ui.all.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
    }
    function draw(){
        if(!Url::get('current_date')){
            $_REQUEST['current_date'] = date('d/m/Y');
        }
        if(Url::get('room_id') && Url::get('house_status')){
            $query = 'SELECT * FROM room_status WHERE status = \'AVAILABLE\' AND in_date = \''.Date_Time::to_orc_date(Url::get('current_date')).'\' AND room_id = \''.Url::get('room_id').'\'';
            if(Url::get('house_status') == 'READY' ){
                if( trim(Url::sget('housekeeping_note')) == '' && DB::exists($query) ){
                    DB::query($query = 'DELETE FROM room_status WHERE room_id=\''.Url::get('room_id').'\' AND status = \'AVAILABLE\' AND in_date = \''.Date_Time::to_orc_date(Url::get('current_date')).'\'');
                } else if( trim(Url::sget('housekeeping_note')) != '' && DB::exists($query) ){
                    DB::query('UPDATE room_status SET note = \''.Url::sget('housekeeping_note').'\',house_status = NULL WHERE status = \'AVAILABLE\' AND in_date = \''.Date_Time::to_orc_date(Url::get('current_date')).'\' AND room_id = \''.Url::get('room_id').'\'');
                } else if( trim(Url::sget('housekeeping_note')) != '' && !DB::exists($query)){
                    DB::insert('room_status' , array('room_id' => Url::get('room_id'),
                                                     'status' => 'AVAILABLE',
                                                     'house_status' => '',
                                                     'in_date' => Date_Time::to_orc_date(Url::get('current_date')),
                                                     'note' => Url::sget('housekeeping_note')));
                }
            }else{
                $array = array( 'room_id' => Url::get('room_id'),
                                'status' => 'AVAILABLE',
                                'house_status' => Url::get('house_status'),
                                'in_date' => Date_Time::to_orc_date(Url::get('current_date')));
                if(trim(Url::sget('housekeeping_note')) != ''){
                    $array += array('note' => Url::sget('housekeeping_note'));
                }
                if( DB::exists($query)){
                    DB::update('room_status' , $array , ' room_id=\''.Url::get('room_id').'\' AND in_date = \''.Date_Time::to_orc_date(Url::get('current_date')).'\' AND status = \'AVAILABLE\'');
                }else{
                    DB::insert('room_status' , $array);
                }
            }
        }
        if(Url::get('reservation_room_id') && Url::sget('reservation_room_note') && Url::get('room_id')){
            $query = 'SELECT id FROM reservation_room WHERE id = \''.Url::get('reservation_room_id').'\' AND room_id = \''.Url::get('room_id').'\'';
            if(DB::exists($query)){
                DB::update('reservation_room' , array('note' => Url::sget('reservation_room_note')) , ' id = \''.Url::get('reservation_room_id').'\' AND room_id = \''.Url::get('room_id').'\'');
            }
        }
        $this->map = array();
        $items = RealityRoomMapDB::get_room_list(Url::get('current_date'));
        $this->map['room_list_js'] = String::array2js($items['room_list_js']);
        $this->map['room_list'] = $items['room'];
        $this->map['explanation'] = $items['explanation'];
        $this->map['room_type'] = $items['room_type'];
        $this->map['total_room'] = $items['total_room_available'];
        $this->parse_layout('list',$this->map);
    }
}
 ?>