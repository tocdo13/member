<?php
class CreateKeyForm extends Form
{
	function CreateKeyForm()
	{
		Form::Form('CreateKeyForm');
        $this->link_js('packages/core/includes/js/multi_items.js');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.mask.min.js');
	}
	function on_submit()
	{
	   echo '<div id="progress" style="position:fixed; top:60px; right:'.(Url::get('width')/2 - 64).'px;" ><img src="packages/core/skins/default/images/updating.gif" /> Proccessing...</div>';
       $this->action_create();
	}	
	function draw()
	{
        require_once 'packages/hotel/packages/reception/modules/ManagerKeySalto/db.php';
        $this->map = array();
        if(Url::get('result'))
        {
            $this->load(Url::get('result'));
        }
        else
        {
            $this->load();
        }
	}
    function action_create()
    {
        
        require_once 'packages/hotel/packages/reception/modules/ManagerKeySalto/db.php';
        $arr_room = array();
        $i = 0;
        foreach($_REQUEST['mi_group'] as $key => $value)
        {
            $arr_temp = explode("_",$value['room']);
            //lay ra thong tin doorid tu $arr_temp[0];
            $room_id = $arr_temp[0];
            $door_id = DB::fetch('SELECT id,door_id FROM manage_doorid WHERE room_id='.$room_id.' order by id');
            
            if(isset($door_id['door_id']))
                $arr_room[$i] = $door_id['door_id'];
            else
                $arr_room[$i] = $arr_temp[1];
            $i++;
        }
        
        $arr_room = array_unique($arr_room);
        
        
        $array_startdate = explode('/',$_REQUEST['date_start']);
        $start_date = $array_startdate[0].$array_startdate[1].substr($array_startdate[2],2,2);
        
        if($_REQUEST['time_start'])
            $start_time = str_replace(":","",$_REQUEST['time_start']);
        else 
            $start_time = "00";
            
        $start_date = $start_time.$start_date;
        
        $array_expirydate = explode('/',$_REQUEST['date_expiry']);
        $expiry_date = $array_expirydate[0].$array_expirydate[1].substr($array_expirydate[2],2,2);
        if($_REQUEST['time_expiry'])
            $expiry_time = str_replace(":","",$_REQUEST['time_expiry']);
        else 
            $expiry_time = "00";
        $expiry_date = $expiry_time.$expiry_date;
        
        $arr = array();
        $arr[0] = $_REQUEST['type'].$_REQUEST['number_key'];
        
        $str_reception = explode("_",Url::get('reception_id'));
        
        //thuc hien lay ra thong tin ip,code,port theo id
        $ip_port = DB::fetch("select * from manage_ipsever where id=".$str_reception[0]);
        $arr[1] = $str_reception[0];
        $arr[2] = 'E';
        
        for($i = 0; $i < 4; $i++)
        {
            if(isset($arr_room[$i]))
                $arr[3+$i] = $arr_room[$i];
            else    
                $arr[3+$i] = '';
        }
        
        $arr[7] = '';
        $arr[8] = '';
        $arr[9] = $start_date;
        $arr[10] = $expiry_date;
        $arr[11] = '';
        $arr[12] = '';
        $arr[13] = '';
        $arr[14] = '';
        if(isset($_REQUEST['serial']))
            $arr[15] = 1;
        else
            $arr[15]= '';
        
        
        
        $str_client = generate_card($arr);
        $data = '';
        $num_result = proccess_cmd($str_client,$ip_port['ip'],$ip_port['port'],$data);
        if($num_result!=0)
        {
            $result = display_error_socket($num_result);
        }
        else
        {
            $result = proccess_data($data);
            
            if($result=='Create card success')
            {
                //thuc hien insert vao database
                $arr_result = explode('³',$data);
                $row = $this->get_object_salto($arr_result[3],$arr_room);
                DB::insert('manage_key_salto',$row);
            }
        }
        
        echo '<script>
			if(window.opener && (window.opener.year || window.opener.night_audit))
			{
				window.opener.history.go(0);
				window.close();
			}
			window.setTimeout("location=\''.URL::build('manager_key_salto',array('cmd'=>'create','resevation_room_id'=>Url::get('resevation_room_id'),'result'=>$result,'portal'=>PORTAL_ID)).'\'",0);
			</script>';
        exit();
    }
    
    function load($result ='')
    {
        if(Url::get('resevation_room_id'))
        {
            $inputs = DB::fetch("select room.id || '_' || room.name as name,
                                            reservation_room.time_out,
                                            reservation_room.time_in
                                    from reservation_room
                                        inner join room on room.id = reservation_room.room_id
                                    where reservation_room.id=".Url::get('resevation_room_id'));
           
            $_REQUEST['mi_group'][101]['room'] = $inputs['name'];
            $_REQUEST['date_expiry'] = date('d/m/Y',$inputs['time_out']);
            $_REQUEST['time_expiry'] = date('H:i',$inputs['time_out']);
            $_REQUEST['date_start'] = date('d/m/Y',$inputs['time_in']);
            $_REQUEST['time_start'] = date('H:i',$inputs['time_in']);
        }
        else
        {
            $_REQUEST['date_expiry'] = date('d/m/Y',time() + 86400);
            $_REQUEST['time_expiry'] = '12:00';
            $_REQUEST['date_start'] = date('d/m/Y');
            $_REQUEST['time_start'] = date('H:i');
        }
        
        
        $db_items = DB::fetch_all("SELECT 
                                        id || '_' || name as id, 
                                        name
                                    FROM room 
                                    WHERE portal_id='".PORTAL_ID."'
                                    ORDER BY name");
		$room_id_options = '';
		foreach($db_items as $item)
		{
			$room_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
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
        if($result!='')
        {
            $this->map['result'] = $result;
        }
        else
            $this->map['result'] ='';
        $this->parse_layout('create_salto',$this->map);
    }
    
    function get_object_salto($guestsn,$arr_can_open)
    {
        $row = array();
        $s_date_start = explode("/",$_REQUEST['date_start']);
        $s_time_start = explode(":",$_REQUEST['time_start']);
        
        $s_date_end = explode("/",$_REQUEST['date_expiry']);
        $s_time_end = explode(":",$_REQUEST['time_expiry']);
        
        $row['begin_time'] = mktime($s_time_start[0],$s_time_start[1],0,$s_date_start[1],$s_date_start[0],$s_date_start[2]);
        $row['end_time'] = mktime($s_time_end[0],$s_time_end[1],0,$s_date_end[1],$s_date_end[0],$s_date_end[2]);
        
        $row['type'] =$_REQUEST['type'];
        
        $row['create_user']=User::id();
        $row['create_time'] = mktime(date('H'),date('i'),0,date('m'),date('d'),date('Y'));
        
        
        $row['reservation_room_id'] = $_REQUEST['resevation_room_id'];
        
        $row['number_keys'] = $_REQUEST['number_key'];
        $row['can_open_rooms'] = implode(",",$arr_can_open);
        $row['portal_id'] = PORTAL_ID;
        if(isset($_REQUEST['serial']))
        {
            $row['guestsn'] = $guestsn;
        }
        return $row;
    }
}
?>