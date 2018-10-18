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
	}
    	
	function draw()
	{
        require_once 'packages/hotel/packages/reception/modules/ManagerKey/db.php';
        if(isset($_REQUEST['get_result']))
        {
            //xu ly ghi xuong csdl tai day: 
            $row = array();
            //add reservation_room_id
            $row = $row + array('reservation_room_id'=>Url::get('resevation_room_id'));
            //beginTime add
            $array_startdate = explode('/',$_REQUEST['date_start']);
            $start_time = explode(':',$_REQUEST['time_start']);
            $row = $row + array('begin_time'=>mktime($start_time[0],$start_time[1],0,$array_startdate[1],$array_startdate[0],$array_startdate[2]));
            
            //endTime add
            $array_expirydate = explode('/',$_REQUEST['date_expiry']);
            $expiry_time = explode(':',$_REQUEST['time_expiry']);
            
            $row = $row + array('end_time'=>mktime($expiry_time[0],$expiry_time[1],0,$array_expirydate[1],$array_expirydate[0],$array_expirydate[2]));
            
            //create user
            $row = $row + array('create_user'=>User::id(),'guest_index'=>1);
            
            //create time 
            $row = $row + array('create_time'=>mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y')));
            
            $str1 = "1-";
            //tinh cardNo;
            $cardNo = DB::fetch('select max(id) as max from manage_key');
            $cardNo = $cardNo['max'] + 1;
            
            $str1 .=$cardNo.'-';//lay ra id lon nhat + 1
            
            foreach($_REQUEST['mi_group'] as $key => $value)
            {
                $arr_temp = explode("_",$value['room']);
                //$str1 .=sprintf("%06d", $arr_temp[1]);
                $room_id = $arr_temp[0];
                $doorid = DB::fetch('SELECT door_id FROM manage_doorid WHERE room_id='.$room_id);
                if(isset($doorid['door_id']))
                    $str1 .=$doorid['door_id'];
                else
                    $str1 .='000100';
                break;
            }
            
            $str1 .='-'.substr($array_startdate[2],2,2).$array_startdate[1].$array_startdate[0];
            $str1 .=$start_time[0].$start_time[1];
            
            $str1 .='-'.substr($array_expirydate[2],2,2).$array_expirydate[1].$array_expirydate[0];
            $str1 .=$expiry_time[0].$expiry_time[1];
            
            //add so the cho phong
            $str1 .='-'.$_REQUEST['number_key'];
            
            //lay ra thong tin encoder_id: tim ip va port tuong ung 
            $encoder = $_REQUEST['reception_id'];
            $str_encoder = explode("_",$encoder);
            
            $ip_port = DB::fetch("select * from manage_ipsever where id=".$str_encoder[0]);
            if($_REQUEST['rbt']==1)
                $str1 .='-0';
            else
            {
               //lay ra series lon nhat cho 1 phong 
                $series_max = DB::fetch('select max(guestsn) as max from manage_key where reservation_room_id='.Url::get('resevation_room_id'));
                $series_max =$series_max['max'];
                $str1 .='-'.$series_max;
            }
            
            //1-100-000201-1409260800-1409261800-3-0[guestSN](str1)
            $receive = process_client($str1,$ip_port['ip'],$ip_port['port']);
            $num_key = $_REQUEST['number_key'];
            if($receive==-1)
            {
                $flag = -1;
                $result = -1;
            }
            else
            {
                $flag = explode("_",$receive);
                $row = $row + array('guestsn'=>(int)$flag[1]);
                $round = (int) $flag[0]==0?$num_key:(int)$flag[0];
                $result = (int) $flag[0]==0?1:-1;
                $flag  = $result==-1?$flag[0].'_'.$num_key:0;
                
                for($i =1;$i<=$round;$i++)
                {
                    $row['guest_index'] = $i;
                    $row['create_time'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
                    DB::insert('manage_key',$row);
                }
            }
        }
        $this->map = array();
        if(isset($result) && $result==1)
        {
            $this->map['status'] = "Create card successful!";
            $this->map['detail'] =' ';
        }

        if(Url::get('resevation_room_id'))
            {
                $inputs = DB::fetch("select room.id || '_' || room.name as name,
                                                reservation_room.time_out,reservation_room.time_in,
                                                room.floor
                                        from reservation_room
                                            inner join room on room.id = reservation_room.room_id
                                        where reservation_room.id=".Url::get('resevation_room_id'));
                
                $_REQUEST['mi_group'][101]['room'] = $inputs['name'];
                if(!isset($_REQUEST['date_start']))
                {
                    $_REQUEST['date_start'] = date('d/m/Y',$inputs['time_in']);
                    $_REQUEST['time_start'] = date('H:i',$inputs['time_in']);
                    $_REQUEST['date_expiry'] = date('d/m/Y',$inputs['time_out']);
                    $_REQUEST['time_expiry'] = date('H:i',$inputs['time_out']);
                }
            }
            else
            {
                $_REQUEST['date_start'] = date('d/m/Y',time());
                $_REQUEST['time_start'] = '09:00';
                
                $_REQUEST['date_expiry'] = date('d/m/Y',time() + 86400);
                $_REQUEST['time_expiry'] = '12:00';
            }
            
            $db_items = DB::fetch_all("select 
                                            id || '_' || name as id, 
                                            name
                                        from room 
                                        Where portal_id='".PORTAL_ID."'
                                        order by name");
    		$room_id_options = '';
    		foreach($db_items as $item)
    		{
    			$room_id_options .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
    		}
            if(isset($result) && $result==-1)
            {
                //$this->map['status'] = "Unsuccess!";
                if(isset($flag) && $flag ==-1)
                {
                    $this->map['status'] = "Create card unsuccessful!";
                     $this->map['detail'] ='';
                }
                else
                {
                    $this->map['status'] = "Create card successful!";
                    $this->map['detail'] ='There are '.str_replace( '_', '/', Url::get('flag')).' total card created!';
                }  
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
            //check ip from share internet
            $this->parse_layout('create',$this->map + array('room_id_options'=>$room_id_options));
	}
}
?>