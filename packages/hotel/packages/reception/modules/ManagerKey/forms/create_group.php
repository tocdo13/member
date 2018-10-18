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
        $this->map = array();
	    if(Url::get('reservation_id'))
        {
            $inputs = DB::fetch_all("select reservation_room.id,
                                        room.id || '_' || room.name as name,
                                        reservation_room.time_out,time_in
                                    from reservation_room
                                        inner join room on room.id = reservation_room.room_id
                                        inner join reservation on reservation.id = reservation_room.reservation_id
                                    where reservation.id=".Url::get('reservation_id'));
                                    
            $arr_multi = array();
            foreach($inputs as $key=>$value)
            {
                $arr_multi[$key]['id'] = $value['id'];
                $arr_multi[$key]['room'] = $value['name'];
                $arr_multi[$key]['date_start'] = date('d/m/Y',$value['time_in']);
                $arr_multi[$key]['time_start'] = date('H:i',$value['time_in']);
                $arr_multi[$key]['date_expiry'] = date('d/m/Y',$value['time_out']);
                $arr_multi[$key]['time_expiry'] = date('H:i',$value['time_out']);
            }
            $_REQUEST['mi_group'] = $arr_multi;
            //System::debug($arr_multi);exit();
        }
        
        $db_items = DB::fetch_all("select 
                                        id || '_' || name as id, 
                                        name
                                    from room 
                                    where portal_id='".PORTAL_ID."' 
                                    order by name");
   		$room_id_options = '';
   		foreach($db_items as $item)
   		{
  			$room_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
   		}
        //lay ra thong tin encoder 
         $db_items = DB::fetch_all("select 
                                        id || '_' || ip as id, 
                                        reception as name
                                    from manage_ipsever 
                                    order by reception desc");
		$reception_id_options = '';
		foreach($db_items as $item)
		{
			$reception_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
		}
        $this->map['reception_id'] = $reception_id_options;
        $this->map['reception'] = $db_items;
        //end lay ra thong tin encoder
        
        $doorid = DB::fetch_all("SELECT * FROM manage_doorid");
        $this->map['doorid'] = String::array2js($doorid);
        $this->parse_layout('create_group',$this->map + array('room_id_options'=>$room_id_options));
	}
}
?>