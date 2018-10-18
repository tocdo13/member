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
        
        $this->stx='';
        $this->etx ='';
        $this->rs ='|';
        $this->add_client='01';
        $this->add_source='03';
        $this->cmd= 'I';
    }
    function on_submit()
    {
      // echo '<div id="progress" style="position:fixed; top:60px; right:'.(Url::get('width')/2 - 64).'px;" ><img src="packages/core/skins/default/images/updating.gif" /> Proccessing...</div>';
       //$this->action_create();
    }
        
    function draw()
    {
        if(isset($_REQUEST['get_result']) && $_REQUEST['get_result']!='')
        {
            $this->show_detail_list();
        }
        elseif(isset($_REQUEST['get_checkout']) && $_REQUEST['get_checkout']!='')
        {
            $this->show_detail_list_checkout();
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
            $_REQUEST['date_start'] = date('d/m/Y',$inputs['time_in']);
            $_REQUEST['time_start'] = date('H:i',$inputs['time_in']);
            $_REQUEST['date_expiry'] = date('d/m/Y',$inputs['time_out']);
            $_REQUEST['time_expiry'] = date('H:i',$inputs['time_out']);
        }
        else
        {
            $_REQUEST['date_start'] = date('d/m/Y',time());
            $_REQUEST['time_start'] = '09:00';
            
            $_REQUEST['date_expiry'] = date('d/m/Y',time() + 86400);
            $_REQUEST['time_expiry'] = '12:00';
        }
        if($result!='')
        {
            $this->map['status'] = "Unsuccess!";
            $str_flag = explode("_",$flag);
            $this->map['detail'] = "There are $str_flag[0]/$str_flag[1] card created!";
            
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
            else
            {
                $room_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
            }
            
        }
        
        $this->map['room_id_options'] = $room_id_options;
        //lay ra thong tin encoder 
         $db_items = DB::fetch_all("select 
                                        id || '_' || ip as id, 
                                        reception as name
                                    from manage_ipsever_adel 
                                    order by reception desc");
        $reception_id_options = '';
        
        foreach($db_items as $item)
        {
            $reception_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
        }
        
        $this->map['reception_id'] = $reception_id_options;
        
        if(Url::get('late'))
        {
            $_REQUEST['is_late'] = 1;
        }
        else
            $_REQUEST['is_late'] = 0;
        //check ip from share internet
        
        $this->parse_layout('create_adel',$this->map);
    }
    
    function show_detail_list_checkout()
    {
        $this->map = array();
        //hien thi danh sach voi cac keys va phong duoc checkout 
        $number_keys = $_REQUEST['number_key'];
        $encoder =  $_REQUEST['reception_id'];
        $str_encoder = explode("_",$encoder);
        $room_id = $_REQUEST['room_id'];
        
        $room_name = DB::fetch('SELECT name FROM room where id='.$room_id);
        
        $row = array();
        $row['room_id'] = $room_id;
        $row['room_name'] = $room_name['name'];
        $row['reception_id'] = $str_encoder[0];
        $row['number_keys'] = $number_keys;
        $this->map['row'] = $row;
        
    
        $doorid = DB::fetch_all("SELECT * FROM manage_doorid");
        $this->map['doorid'] = String::array2js($doorid);
        //System::debug($doorid);
        $this->parse_layout('checkout_detail',$this->map);
    }
    
    function show_detail_list()
    {
        $this->map = array();
        //lay ra cac thong tin co ban tren fom
        $reservation_room =Url::get('resevation_room_id');
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
        
        //5. lay ra thong tin first , late , number_kesy
        $choose = $_REQUEST['rbt'];
        $num_keys = $_REQUEST['number_key'];
        
        $doorid = DB::fetch_all("SELECT * FROM manage_doorid");
        $this->map['doorid'] = String::array2js($doorid);
        
        $this->parse_layout('create_adel_detail',$this->map);
        
        
    }
    function action_create()
    {
        require_once 'packages/hotel/packages/reception/modules/ManagerKeyAdel/db.php';
        //xu ly ghi xuong csdl tai day: 
        $encoder = $_REQUEST['reception_id'];
        $str_encoder = explode("_",$encoder);
        
        $ip_port = DB::fetch("select * from manage_ipsever_adel where id=".$str_encoder[0]);
        
        if(isset($ip_port['add_client']))
        {
            $this->add_client = sprintf("%02d",$ip_port['add_client']);
            $this->add_source = sprintf("%02d",$ip_port['add_source']);
        }
        //$str1 =$this->stx.$this->add_client.$this->add_source.$this->cmd.$this->rs;// '0103I|';
        
        $room_id = $_REQUEST['room_id'];
        
        $doorid = DB::fetch('SELECT door_id FROM manage_doorid WHERE room_id='.$room_id);
        $door_str = 'R'.$this->add_client.sprintf("%04d",$doorid['door_id']).$this->rs;
        
        //$numkeys =1;
        //beginTime add
        $array_startdate = explode('/',$_REQUEST['date_start']);
        $start_time = explode(':',$_REQUEST['time_start']);
        
        //endTime add
        $array_expirydate = explode('/',$_REQUEST['date_expiry']);
        $expiry_time = explode(':',$_REQUEST['time_expiry']);
        
        
        $beginTime = $array_startdate[2].$array_startdate[1].$array_startdate[0].$start_time[0].$start_time[1];
        $endTime = $array_expirydate[2].$array_expirydate[1].$array_expirydate[0].$expiry_time[0].$expiry_time[1];
        //$str3 ='';
        $begin_str ='D'.$beginTime.$this->rs;
        $end_str ='O'.$endTime.$this->etx;
        
        
        //neu guest check in thi sao: khong can chu y number keys
        $str_success = $this->add_source.$this->add_client.'0';
        $row = array();
        //add reservation_room_id
        $row = $row + array('reservation_room_id'=>Url::get('resevation_room_id'));
        
        $row = $row + array('begin_time'=>mktime($start_time[0],$start_time[1],0,$array_startdate[1],$array_startdate[0],$array_startdate[2]));
        $row = $row + array('end_time'=>mktime($expiry_time[0],$expiry_time[1],0,$array_expirydate[1],$array_expirydate[0],$array_expirydate[2]));
        $row = $row + array('create_user'=>User::id());
        $row = $row + array('guest_index'=>1);
        $row = $row + array('create_time'=>mktime(date('H'),date('i'),0,date('m'),date('d'),date('Y')));
        
        $num_success = 0;
        
        //$str = $str1.'N'.$_REQUEST['traveller'].$this->rs.$str3;
        
        $num_keys = $_REQUEST['number_key'];
        for($i=0;$i<$num_keys;$i++)
        {
            if($i==0)
            {
                $this->cmd="I";
            }
            else
            {
                $this->cmd="G";
            }
            $str = $this->stx.$this->add_client.$this->add_source.$this->cmd.$this->rs;
            $str .=$door_str;
            $str .=$begin_str.$end_str;
            
            $receive = process_client($str,$ip_port['ip'],$ip_port['port']);
            $receive = $str_success;
            $post = strpos($receive,$str_success);
            if($post>=0)//'03010')
            {
                $num_success++;
                $row['guest_index'] = $num_success;
                DB::insert('manage_key_adel',$row); 
            }
        }
        
        
        //so the tao thanh cong: $num_success/$numkeys;
        $result = $num_success==$num_keys?1:-1;
        $flag  = $result==-1?$num_success.'_'.$num_keys:'';

        echo '<script>
            if(window.opener && (window.opener.year || window.opener.night_audit))
            {
                window.opener.history.go(0);
                window.close();
            }
            window.setTimeout("location=\''.URL::build_current(array('cmd'=>'create','result'=>$result,'resevation_room_id'=>Url::get('resevation_room_id'),'flag'=>$flag)).'\'",0);
            </script>';
        
        exit();
        
    }    
}
?>