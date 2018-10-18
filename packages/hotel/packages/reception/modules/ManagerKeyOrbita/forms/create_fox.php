<?php
class CreateKeyForm extends Form
{
    private $stx;
    private $etx;
    private $rs;
    private $add_client;
    private $add_source;
    private $cmd;
    
    function CreateKeyForm()
    {
        Form::Form('CreateKeyForm');
        $this->link_js('packages/core/includes/js/multi_items.js');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.mask.min.js');
    }
 
    function draw()
    {
        if(isset($_REQUEST['get_result']) && $_REQUEST['get_result']!='')
        {
            $this->show_detail_list();
        }
        else
        {
            $this->load();
        }
    }
    
    function load($result='',$flag='')
    {
        $this->map = array();
        if(Url::get('resevation_room_id'))
        {
            $inputs = DB::fetch("select room.id as room_id,
                                            room.name,
                                            reservation_room.time_out,reservation_room.time_in,
                                            room.floor
                                    from reservation_room
                                        inner join room on room.id = reservation_room.room_id
                                    where reservation_room.id=".Url::get('resevation_room_id'));
            
            $this->map['room_id'] = $inputs['room_id'];
            $_REQUEST['date_expiry'] = date('d/m/Y',$inputs['time_out']);
            $_REQUEST['time_expiry'] = date('H:i',$inputs['time_out']);
        }
        else
        {
            $_REQUEST['date_expiry'] = date('d/m/Y',time() + 86400);
            $_REQUEST['time_expiry'] = '12:00';
        }
        
        $db_items = DB::fetch_all("select 
                                        room.id, 
                                        room.name
                                    from room,manage_doorid 
                                    where room.id=manage_doorid.room_id AND room.portal_id='".PORTAL_ID."' 
                                    order by room.name");
        $room_id_options = '';
        foreach($db_items as $item)
        {
            if(isset($this->map['room_id']) && $item['id']==$this->map['room_id'])
            {
                $room_id_options .= '<option value="'.$item['id'].'" selected="selected">'.$item['name'].'</option>';
            }
            //else
            //{
                //$room_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
            //}
            
        }
        
        $this->map['room_id_options'] = $room_id_options;
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
        //Kiem tra da ton tai the cho phong chua 
        //Neu chua thi is_late =0 nguoc lai is_late = 1
        $sql = "SELECT * FROM manage_key_fox WHERE reservation_room_id=".Url::get('resevation_room_id')." ORDER BY create_time desc";
        $item_before = DB::fetch($sql);
        //System::debug($item_before);    
        if(!empty($item_before))
        {
            $_REQUEST['is_late'] = 1;
            
            $_REQUEST['date_start'] = date('d/m/Y',$item_before['begin_time']);//$inputs['time_in']
            $_REQUEST['time_start'] = date('H:i',$item_before['begin_time']);//
        }
        else
        {
            $_REQUEST['is_late'] = 0;
             $_REQUEST['date_start'] = date('d/m/Y',time());
            $_REQUEST['time_start'] = date('H:i',time());//
        }
        //check ip from share internet
        if(User::id()=='developer09')
        {
            echo $script_tz = date_default_timezone_get();
        }
        $this->parse_layout('create_fox',$this->map);
    }
  
    
    function show_detail_list()
    {
        $this->map = array();
        //lay ra cac thong tin co ban tren fom
        $reservation_room = Url::get('resevation_room_id');
        //2. thong tin encoder
        $encoder = $_REQUEST['reception_id'];
        $str_encoder = explode("_",$encoder);
        //3. lay ra thong tin room_id, room name
        $room_id = $_REQUEST['room_id'];
        $room_name = DB::fetch('SELECT name FROM room WHERE id='.$room_id);
        $room_name = $room_name['name'];
        $_REQUEST['room_name'] = $room_name;
        
        
        //4. lay ra thong tin thoi gian bat dau va ket thuc 
        $date_start = $_REQUEST['date_start'];
        $time_start = $_REQUEST['time_start'];
        $date_expiry = $_REQUEST['date_expiry'];
        $time_expiry = $_REQUEST['time_expiry'];
        
        //5. lay ra thong tin first, late, number_kesy
        $num_keys = $_REQUEST['number_key'];
        
        $doorid = DB::fetch_all("SELECT * FROM manage_doorid");
        $this->map['doorid'] = String::array2js($doorid);
        
        
        $this->parse_layout('create_fox_detail',$this->map);
    }
       
}
?>