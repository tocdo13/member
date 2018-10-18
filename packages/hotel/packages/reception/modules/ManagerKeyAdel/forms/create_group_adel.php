<?php
class CreateGroupKeyForm extends Form
{
    private $stx;
    private $etx;
    private $rs;
    private $add_client;
    private $add_source;
    private $cmd;
    
    
	function CreateGroupKeyForm()
	{
		Form::Form('CreateGroupKeyForm');
        $this->link_js('packages/core/includes/js/multi_items.js');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.mask.min.js');
        
        $this->stx='';
        $this->etx ='';
        $this->rs ='|';
        $this->add_client='01';
        $this->add_source='03';
        $this->cmd= 'I';
	}
	
	function draw()
	{
        if(isset($_REQUEST['create']))
        {
            $this->show_detail_list();
        }
        else
        {
           $this->load(); 
        }    
	}
    function show_detail_list()
    {
        $this->map = array();
        //lay ra cac thong tin co ban tren fom
        $reservation =Url::get('reservation_id');
        //2. thong tin encoder
        $encoder = $_REQUEST['reception_id'];
        $str_encoder = explode("_",$encoder);
        //3. thanh lap mang cac gia tri
        //id=reservation_room_id, items: cac gia tri, room_id,room_name,time,num_keys
        
        $items = $_REQUEST['mi_group'];
        $num_keys =0;
        foreach($items as $key=>$value)
        {
            unset($items[$key]['index']);
            unset($items[$key]['status']);
            $items[$key]['room_id'] = $items[$key]['room'];
            unset($items[$key]['room']);
            $room_name = DB::fetch('SELECT name FROM room WHERE id='.$items[$key]['room_id']);
            $items[$key]['room_name'] = $room_name['name'];
            $num_keys +=$items[$key]['number_key'];
                    
        }
        $this->map['num_keys'] = $num_keys;
        $this->map['items'] = $items;
        
        $doorid = DB::fetch_all("SELECT * FROM manage_doorid");
        $this->map['doorid'] = String::array2js($doorid);
        $this->parse_layout('create_group_adel_detail',$this->map);
        
        
    }
    function load()
    {
        $this->map = array();
        $this->map['stx'] = $this->stx;
        $this->map['etx'] = $this->etx;
        $this->map['rs'] = $this->rs;
        $this->map['add_client'] = $this->add_client;
        $this->map['add_source'] = $this->add_source;
        $this->map['command'] = $this->cmd;
        
        
        
        $inputs = DB::fetch_all("select reservation_room.id,
                                        room.id as room_id,
                                        reservation_room.time_out,time_in
                                    from reservation_room
                                        inner join room on room.id = reservation_room.room_id
                                        inner join reservation on reservation.id = reservation_room.reservation_id
                                    where reservation.id=".Url::get('reservation_id'));            
        $arr_multi = array();
        foreach($inputs as $key=>$value)
        {
            $arr_multi[$key]['id'] = $value['id'];
            $arr_multi[$key]['room'] = $value['room_id'];
            $arr_multi[$key]['date_start'] = date('d/m/Y',$value['time_in']);
            $arr_multi[$key]['time_start'] = date('H:i',$value['time_in']);
            $arr_multi[$key]['date_expiry'] = date('d/m/Y',$value['time_out']);
            $arr_multi[$key]['time_expiry'] = date('H:i',$value['time_out']);
        }
        $_REQUEST['mi_group'] = $arr_multi;
        
        
        //lay ra thong tin encoder 
         $db_items = DB::fetch_all("SELECT  
                                        id || '_' || ip as id, 
                                        reception as name
                                    FROM manage_ipsever_adel
                                    ORDER by reception desc");
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
        $db_items = DB::fetch_all("select 
                                        id as id, 
                                        name
                                    from room 
                                    where portal_id='".PORTAL_ID."' 
                                    order by name");
   		$room_id_options = '';
   		foreach($db_items as $item)
   		{
  			$room_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
   		}
        $this->map['room_id_options'] =$room_id_options; 
        
        $this->parse_layout('create_group_adel',$this->map);
    }
    
}
?>