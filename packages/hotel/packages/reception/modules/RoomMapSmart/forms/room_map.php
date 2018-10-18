<?php
/**
 * WRITER BY NVM - CODE SMART ROOM MAP
 * COPY RIGHT 15-11-2015
**/
class RoomMapSmartForm extends Form
{
	function RoomMapSmartForm()
	{
		Form::Form('RoomMapSmartForm');
		$this->add('room_ids',new TextType(true,'invalid_room_ids',0,255));
		$this->link_css(Portal::template('hotel').'/css/room.css');//Important
		//$this->link_css('skins/default/datetime.css');
		//$this->link_js('packages/hotel/includes/js/ajax.js');
		$this->link_js('packages/core/includes/js/jquery/cookie.js');
		//$this->link_css('packages/core/includes/js/jquery/window/css/jquery.window.4.04.css');		
		//$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.widget.js');		
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.mouse.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.draggable.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.resizable.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.corner.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		//$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		//$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        //$this->link_js('packages/core/includes/js/jquery/date.js');
		//$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
		//$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
        //$this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');
		//$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
        $this->link_css('packages/core/includes/js/custom_content_scroller/jquery.mCustomScrollbar.css');
        $this->link_js('packages/core/includes/js/custom_content_scroller/jquery.mCustomScrollbar.js');
	}
	function on_submit()
	{
	   //System::debug($_REQUEST); exit();
       if($this->check())
		{
		       
			$room_ids = explode(',',URL::get('room_ids'));
			if(User::can_edit(false,ANY_CATEGORY))
			{
				$room_id = reset($room_ids);
				if(URL::get('change_room_note_'.$room_id) and Url::get('in_date'))
				{
					$sql = '
						select 
							room_status.reservation_id as id
						from 
							room_status
                            inner join reservation_room on reservation_room.id = room_status.reservation_room_id
						where 
							room_status.room_id=\''.$room_id.'\' and room_status.in_date=\''.Date_Time::to_orc_date(Url::sget('in_date')).'\' and reservation_room.status<>\'CHECKOUT\' AND room_status.status<>\'CANCEL\' order by room_status.id desc';
					
                    $reservation_id = DB::fetch_all($sql);
                    //System::debug($reservation_id);
                    foreach($reservation_id as $k=>$v)
                    {
                        echo Url::get('room_note_'.$v['id']);
                        DB::update('reservation_room',array(
								'note'=>addcslashes(Url::get('room_note_'.$v['id']), '*&')
							),'reservation_id=\''.$v['id'].'\' and room_id=\''.$room_id.'\''
						);
                    }
				}
                //System::debug($_REQUEST); exit();
				if(Url::sget('in_date') AND isset($_REQUEST['check_submit_date']))
                {
					foreach($room_ids as $room_id)
                    {
                        /** START them thong tin cho log doi trang thai phong**/
                        $room_name = DB::fetch("select name from room where id = ".$room_id,'name');
                        /** END them thong tin cho log doi trang thai phong**/
                        $s_time = split(':',CHECK_OUT_TIME);
                        $e_time = split(':',CHECK_IN_TIME);
						$start_time = Date_Time::to_time(Url::sget('in_date')) + $s_time[0]*3600 + $s_time[1]*60;
						$end_time = Date_Time::to_time(Url::sget('in_date')) + $e_time[0]*3600 + $e_time[1]*60;
						$time = $start_time;
						if(Url::get('house_status')){
							if(Url::sget('repair_to')){
								$end_time = Date_Time::to_time(Url::sget('repair_to'))+ $e_time[0]*3600 + $e_time[1]*60;
							}
						}else if(!Url::get('house_status')){
							if(Url::sget('repair_to')){
								$end_time = Date_Time::to_time(Url::sget('repair_to'))+ $e_time[0]*3600 + $e_time[1]*60;
							}
						}
                        
                        $update_ready = false;
                        /** Start: KID check repair trung nhau **/
                        if(isset($_REQUEST['house_status']) AND $_REQUEST['house_status']=='REPAIR')
                        {
                            
                            $cond_repair = " room_id=".$room_id." AND house_status='REPAIR' ";
                            while($time<=$end_time)
                            {
                                $cond_1 = 'in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' AND room_id=\''.$room_id.'\'';
                                
                                $check_date = DB::fetch('SELECT start_date,end_date FROM room_status WHERE '.$cond_1.'');
                                if(isset($check_date['start_date']) AND isset($check_date['end_date']) AND $check_date['start_date']!='' AND $check_date['end_date']!='')
                                    $cond_repair .= " AND (FROM_UNIXTIME(start_date) = '".Date_Time::to_orc_date(date('d/m/Y',$check_date['start_date']))."' AND FROM_UNIXTIME(end_date)='".Date_Time::to_orc_date(date('d/m/Y',$check_date['end_date']))."')";
                                $cond_repair .= " AND ( in_date != '".Date_Time::to_orc_date(date('d/m/Y',$time))."')";
                                $time += 3600*24;
                            }
                            $orcl = DB::fetch_all('SELECT * FROM room_status WHERE '.$cond_repair.'');
                            if(sizeof($orcl)>0)
                            {
                                foreach($orcl as $id=>$content)
                                {
                                    if(($content['start_date']<=$end_time and $content['start_date']>=$start_time ) and ($content['end_date']>=$end_time))
                                    {
                                        if(Date_Time::to_time(Date_Time::convert_orc_date_to_date($content['in_date'],'/'))<=$end_time and Date_Time::to_time(Date_Time::convert_orc_date_to_date($content['in_date'],'/'))>=$start_time )
                                        {
                                            DB::query('delete from room_status where id ='.$content['id']);
                                        }
                                        if(Date_Time::to_time(Date_Time::convert_orc_date_to_date($content['in_date'],'/'))>$end_time)
                                        {
                                            DB::update('room_status',
            									array(
            									'start_date'=>($end_time+24*3600)
            									),
                                                'id='.$content['id']
                                            );
                                        }
                                    }
                                    if(($content['start_date']<$end_time and $content['start_date']>$start_time ) and ($content['end_date']<$end_time))
                                    {
                                        DB::query('delete from room_status where id ='.$content['id']);
                                    }
                                }
                            }
                        }
                        /** end: KID check repair trung nhau **/
                        $time = $start_time;
                        
						while($time<=$end_time){
							$cond = 'in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' 
								AND room_id=\''.$room_id.'\' AND status != \'AVAILABLE\'';
                            $sql = 'SELECT * FROM room_status WHERE '.$cond.'';
                            if($old_house_status = DB::fetch($sql))
							{ 
                                DB::update('room_status',
									array(
									'note'=>'',
									'house_status'=>''
									),
									$cond
							    );
                            }
                            $cond = 'in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$time)).'\' 
								AND room_id=\''.$room_id.'\' AND status = \'AVAILABLE\'';
							$sql = 'SELECT * FROM room_status WHERE '.$cond.'';
                            /** START them thong tin cho log doi trang thai phong**/
							if($old_house_status = DB::fetch($sql))
                            /** END them thong tin cho log doi trang thai phong**/
							{ 
                                DB::update('room_status',
									array(
									'note'=>htmlentities(addcslashes(Url::get('note'), '*&'),ENT_QUOTES, "UTF-8"),
									'house_status'=>Url::get('house_status'),
                                    'start_date'=>$start_time,
									'end_date'=>$end_time
									),
									$cond
							    );
                                /** START them thong tin cho log doi trang thai phong**/
                                $title = 'Update Room house_status and note room_id : '.$room_id.',room_name : '.$room_name.', Old status: ' .$old_house_status['house_status'].', New status: '.(Url::get('house_status')?Url::get('house_status'):'READY');
        						/** END them thong tin cho log doi trang thai phong**/
                                $description = ''
                                .Portal::language('house_status_note').':'.Url::get('house_status').'<br>  '
        						.Portal::language('in_date').':'.date('d/m/Y',$time).'<br>  '
                                .Portal::language('start_date').':'.date('d/m/Y',$start_time).'<br> '
                                .Portal::language('end_date').':'.date('d/m/Y',$end_time).'<br>  ';
        						System::log('edit',$title,$description,$room_id);
							}
                            else
                            {
								DB::insert('room_status',
									array(
									'note'=>htmlentities(addcslashes(Url::get('note'), '*&'),ENT_QUOTES, "UTF-8"),
									'room_id'=>$room_id,
									'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$time)),
									'status'=>'AVAILABLE',
									'house_status'=>Url::get('house_status'),
									'start_date'=>$start_time,
									'end_date'=>$end_time
									)
								);
                                /** START them thong tin cho log doi trang thai phong**/
                                $title = 'Insert Room house_status and note room_id: '.$room_id.',room_name: '.$room_name.', Status: ' .(Url::get('house_status')?Url::get('house_status'):'READY').'';
        						/** END them thong tin cho log doi trang thai phong**/
                                $description = ''
                                .Portal::language('note').':'.Url::get('note').'<br> '
                                .Portal::language('house_status_note').':'.Url::get('house_status').'<br> '
        						.Portal::language('in_date').':'.date('d/m/Y',$time).'<br> ' 
                                .Portal::language('start_date').':'.date('d/m/Y',$start_time).'<br> '
                                .Portal::language('end_date').':'.date('d/m/Y',$end_time).'<br>  ';
        						System::log('edit',$title,$description,$room_id);
							}
                           
							$time += 3600*24;
						}
                        //start: KID làm repair c?t ch?ng d? không b? sai trên tình hình s? d?ng phòng
                        if(Url::get('house_status') =='')
                        {
                            $cond1 =  ' room_id = '.$room_id.' 
                                                and house_status = \'REPAIR\' 
                                                and FROM_UNIXTIME(start_date) <= \''.Date_Time::to_orc_date(Url::sget('in_date')).'\'  
                                                and FROM_UNIXTIME(end_date) >= \''.Date_Time::to_orc_date(Url::sget('in_date')).'\' 
                                            ';
                            if($repairs = DB::fetch_all('select * from room_status where '.$cond1 ))
                            {
                                
                                foreach($repairs as $key=>$value)
                                {
                                    if(Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['in_date'],"/"))< Date_Time::to_time(Url::sget('in_date')))
                                    {
                                        
                                        DB::update('room_status',
        									array(
                                            'end_date'=>(Date_Time::to_time(Url::sget('in_date'))-86400),
        									),
        									'id='.$value['id']
    								    );
                                    }
                                    if(Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['in_date'],"/"))>$end_time)
                                    {
                                        
   
                                        DB::update('room_status',
        									array(
                                            'start_date'=>$end_time+86400,
        									),
        									'id='.$value['id']
    								    );
                                    }
                                }
                            }
                        }
                        //end
                    }	
				}
			}
			//Url::redirect_current(array('in_date'));
		}
	}
	function draw()
	{
	   $this->map = array();
	   /** 
        * AUTO_CANCEL_BOOKING_EXPIRED kiem tra tu dong cancel booking qua han
        * thoi gina kiem tra sau 6h hang ngay
        * KIEM TRA XEM CO TU DONG HUY BOOKING QUA HAN KHONG 
       **/
	   if(AUTO_CANCEL_BOOKING_EXPIRED and (time() - Date_Time::to_time('DD/MM/YYYY') > 6*3600))
       {
            $this->cancel_booking_expired();
       }
//============================================================================================================
       /** Start: KID them ham de dat dirty tu dong **/
       $this->auto_extent_dirty();
       $this->auto_set_dirty();
//============================================================================================================
       /** XOA TRANG THAI CUA ROOM STATUS RAC **/
       DB::query('delete from room_status where reservation_id = 0 and house_status is null and note is null');
//============================================================================================================
       /** LAY RA CAC GIA TRI TIM KIEM **/
        if(!isset($_REQUEST['in_date']))
            $_REQUEST['in_date'] = date('d/m/Y');
        if(Url::get('to_date'))
			$to_date = Date_Time::to_time(Url::get('to_date'));
        else
			$to_date = Date_Time::to_time(Url::get('in_date'));
        $current_time = Date_Time::to_time($_REQUEST['in_date']);
        $this->year = date('Y',$current_time);
		$this->month = date('m',$current_time); 
		$this->day = date('d',$current_time);
		$this->end_year = date('Y',$to_date);
		$this->end_month = date('m',$to_date);
		$this->end_day = date('d',($to_date));
        
        // khai bao list khu
        $this->area_id_list = DB::fetch_all('SELECT * FROM area_group WHERE portal_id=\''.PORTAL_ID.'\'');
        // khai bao khu mac dinh
        $this->area_id = DB::exists('select id from area_group where active=1')?DB::fetch('select code from area_group where active=1','code'):DB::fetch('select code from area_group where id=1','code');
        $this->area_key = DB::exists('select id from area_group where active=1')?DB::fetch('select id from area_group where active=1','id'):DB::fetch('select code from area_group where id=1','id');
        
        $room_levels = DB::select_all('room_level','portal_id=\''.PORTAL_ID.'\'','room_level.is_virtual,room_level.price');
        $this->map['room_levels'] = $room_levels;
        $rooms_info = DB::fetch_all('SELECT id FROM room WHERE portal_id=\''.PORTAL_ID.'\'');
        $this->map['rooms_info'] = String::array2js($rooms_info);
        $this->map['count_rooms_info'] = sizeof($rooms_info);
//============================================================================================================
       /** LAY DU LIEU **/
       $this->map['items'] = $this->get_items($this->area_id,$current_time);
       $this->map['items_js'] = String::array2js($this->map['items']);
       //System::debug($this->map);
       /** parse_layout **/
       $this->parse_layout('room_map',$this->map+array(
                                                        'area_id_list'=>$this->area_id_list,
                                                        'area_id'=>$this->area_id,
                                                        'area_key'=>$this->area_key,
                                                        'year'=>$this->year,
                                                        'end_year'=>date('Y',$current_time+86400),
                                        				'month'=>$this->month,
                                        				'day'=>$this->day,
                                                        'room_level_id_list'=>array(''=>Portal::language('All')) + String::get_list($room_levels),
                                        				'end_month'=>date('m',$current_time+86400),
                                                        'end_day'=>date('d',$current_time+86400)
                                                        ));
	}
    
    function get_items($area_id,$current_time)
    {
        $current_time = $current_time + date('H')*3600 + date('i')*60;
        $area = array();
        // LAY TONG CAC PHONG TRONG KHU
        $rooms = $this->get_rooms($area_id);
        // LAY CAC TRANG THAI PHONG TRONG KHU
        $room_status = $this->get_room_statuses($area_id,$current_time);
        // LAY CAC KHACH TRONG PHONG
        $list_traveller = $this->get_list_traveller($current_time);
        
        //System::debug($room_status);
        
        foreach($room_status as $key=>$value)
        {
            $value['arr_time_in'] = date('H:i d/m/Y',$value['time_in']);
            $value['arr_time_out'] = date('H:i d/m/Y',$value['time_out']);
            // gan traveller
            if(isset($list_traveller[$value['reservation_room_id']]))
            {
                $value['list_traveller'] = $list_traveller[$value['reservation_room_id']]['child'];
                $value['count_traveller'] = $list_traveller[$value['reservation_room_id']]['count'];
            }
            else
            {
                $value['list_traveller'] = array();
                $value['count_traveller'] = 0;
            }
            // gan trang thai buong
            if($value['house_status']!='')
                $rooms[$value['floor']]['child'][$value['room_id']]['house_status'] = $value['house_status'];
            if($value['house_status']=='REPAIR')
                $rooms[$value['floor']]['child'][$value['room_id']]['status'] = 'REPAIR';
            
            $rooms[$value['floor']]['child'][$value['room_id']]['hk_note'] = $value['note'];
            if($value['reservation_room_status']=='')
            {
                $rooms[$value['floor']]['child'][$value['room_id']]['start_time'] = $value['start_date'];
                $rooms[$value['floor']]['child'][$value['room_id']]['end_time'] = $value['end_date'];
                $rooms[$value['floor']]['child'][$value['room_id']]['start_date'] = date('H:i d/m/Y',$value['start_date']);
                $rooms[$value['floor']]['child'][$value['room_id']]['end_date'] = date('H:i d/m/Y',$value['end_date']);
            }
            else
            { // gan trang thai phong
                if($value['reservation_room_status']=='BOOKED')
                {
                    if(($value['time_in']+TIME_BOOK_OVERDUE*60)<time())
                    { // book qua han
                        if(DISPLAY_BOOK_OVERDUE==1)
                        {
                            
                            $i = sizeof($rooms[$value['floor']]['child'][$value['room_id']]['active']);
                            $i++;
                            $rooms[$value['floor']]['child'][$value['room_id']]['active'][$i] = $value;
                            $rooms[$value['floor']]['child'][$value['room_id']]['active'][$i]['key'] = $i;
                            if($rooms[$value['floor']]['child'][$value['room_id']]['is_active']=='')
                            {
                                $rooms[$value['floor']]['child'][$value['room_id']]['status'] = 'OVERDUE_BOOKED';
                                $rooms[$value['floor']]['child'][$value['room_id']]['is_active'] = $i;
                            }
                        }
                    }
                    else
                    { // book
                        $i = sizeof($rooms[$value['floor']]['child'][$value['room_id']]['active']);
                        $i++;
                        $rooms[$value['floor']]['child'][$value['room_id']]['active'][$i] = $value;
                        $rooms[$value['floor']]['child'][$value['room_id']]['active'][$i]['key'] = $i;
                        if($rooms[$value['floor']]['child'][$value['room_id']]['is_active']=='')
                        {
                            $rooms[$value['floor']]['child'][$value['room_id']]['is_active'] = $i;
                            $rooms[$value['floor']]['child'][$value['room_id']]['status'] = 'BOOKED';
                        }
                    }
                }
                elseif($value['reservation_room_status']=='CHECKIN' OR $value['reservation_room_status']=='CHECKOUT')
                {
                    if($value['arrival_time']==date('d/m/Y',$current_time) AND $value['change_room_from_rr']!='')
                    { // doi phong checkin
                        //echo $value['room_id']; 
                        $i = sizeof($rooms[$value['floor']]['child'][$value['room_id']]['active']);
                        $i++;
                        $rooms[$value['floor']]['child'][$value['room_id']]['active'][$i] = $value;
                        $rooms[$value['floor']]['child'][$value['room_id']]['active'][$i]['key'] = $i;
                        if($value['reservation_room_status']=='CHECKOUT' AND $value['departure_time']==date('d/m/Y',$current_time))
                            $rooms[$value['floor']]['child'][$value['room_id']]['status'] = 'AVAILABLE';
                        else
                        {
                            $rooms[$value['floor']]['child'][$value['room_id']]['status'] = 'CHANGE_IN_DATE';
                            $rooms[$value['floor']]['child'][$value['room_id']]['is_active'] = $i;
                        }
                    }
                    elseif($value['arrival_time']==date('d/m/Y',$current_time) AND $value['change_room_from_rr']=='')
                    { // phong den trong ngay   
                        //echo $value['room_id'];           
                        $i = sizeof($rooms[$value['floor']]['child'][$value['room_id']]['active']);
                        $i++;
                        $rooms[$value['floor']]['child'][$value['room_id']]['active'][$i] = $value;
                        $rooms[$value['floor']]['child'][$value['room_id']]['active'][$i]['key'] = $i;
                        if($value['reservation_room_status']=='CHECKOUT' AND $value['departure_time']==date('d/m/Y',$current_time))
                            $rooms[$value['floor']]['child'][$value['room_id']]['status'] = 'AVAILABLE';
                        else
                        {
                            $rooms[$value['floor']]['child'][$value['room_id']]['status'] = 'DAYUSED';
                            $rooms[$value['floor']]['child'][$value['room_id']]['is_active'] = $i;
                        }
                        
                    }
                    elseif($value['arrival_time']!=date('d/m/Y',$current_time) AND $value['departure_time']==date('d/m/Y',$current_time) AND $value['time_out']<time())
                    { // phong o qua han                    
                        $i = sizeof($rooms[$value['floor']]['child'][$value['room_id']]['active']);
                        $i++;
                        $rooms[$value['floor']]['child'][$value['room_id']]['active'][$i] = $value;
                        $rooms[$value['floor']]['child'][$value['room_id']]['active'][$i]['key'] = $i;
                        if($value['reservation_room_status']=='CHECKOUT' AND $value['departure_time']==date('d/m/Y',$current_time))
                        {
                            $rooms[$value['floor']]['child'][$value['room_id']]['status'] = 'AVAILABLE';
                        }
                        else
                        {
                            $rooms[$value['floor']]['child'][$value['room_id']]['status'] = 'OVERDUE';
                            $rooms[$value['floor']]['child'][$value['room_id']]['is_active'] = $i;
                        }
                        
                    }
                    elseif($value['arrival_time']!=date('d/m/Y',$current_time) AND $value['departure_time']==date('d/m/Y',$current_time) AND $value['time_out']>time())
                    { // phong se checkout
                        //echo $value['room_id'];
                        $i = sizeof($rooms[$value['floor']]['child'][$value['room_id']]['active']);
                        $i++;
                        $rooms[$value['floor']]['child'][$value['room_id']]['active'][$i] = $value;
                        $rooms[$value['floor']]['child'][$value['room_id']]['active'][$i]['key'] = $i;
                        if($value['reservation_room_status']=='CHECKOUT' AND $value['departure_time']==date('d/m/Y',$current_time))
                            $rooms[$value['floor']]['child'][$value['room_id']]['status'] = 'AVAILABLE';
                        else
                        {
                            $rooms[$value['floor']]['child'][$value['room_id']]['status'] = 'EXPECTED_CHECKOUT';
                            $rooms[$value['floor']]['child'][$value['room_id']]['is_active'] = $i;
                        }
                        
                    }
                    elseif($value['arrival_time']!=date('d/m/Y',$current_time) AND $value['departure_time']!=date('d/m/Y',$current_time))
                    { // phong cho khach - phong luu
                        //echo $value['room_id'];
                        $rooms[$value['floor']]['child'][$value['room_id']]['status'] = 'OCCUPIED';
                        $i = sizeof($rooms[$value['floor']]['child'][$value['room_id']]['active']);
                        $i++;
                        $rooms[$value['floor']]['child'][$value['room_id']]['active'][$i] = $value;
                        $rooms[$value['floor']]['child'][$value['room_id']]['active'][$i]['key'] = $i;
                        $rooms[$value['floor']]['child'][$value['room_id']]['is_active'] = $i;
                    }
                    else
                    {
                        //echo $value['room_id'];
                        $rooms[$value['floor']]['child'][$value['room_id']]['status'] = $value['status'];
                        $i = sizeof($rooms[$value['floor']]['child'][$value['room_id']]['active']);
                        $i++;
                        $rooms[$value['floor']]['child'][$value['room_id']]['active'][$i] = $value;
                        $rooms[$value['floor']]['child'][$value['room_id']]['active'][$i]['key'] = $i;
                    }
                }
            }
            
        }
        
        //System::debug($rooms);
        return $rooms;
        
    }
    
    function get_list_traveller($current_time)
    {
        $sql = '
                SELECT
                    reservation_traveller.id
                    ,reservation_traveller.arrival_time
                    ,reservation_traveller.departure_time
                    ,reservation_traveller.status
                    ,reservation_traveller.reservation_room_id
                    ,CONCAT(DECODE(traveller.gender,1,\'Mr. \',\'Ms. \'),concat(traveller.first_name,concat(\' \',traveller.last_name))) as traveller_name
                    ,TO_CHAR(traveller.birth_date,\'yyyy\') as age
                    ,TO_CHAR(traveller.birth_date,\'DD/MM\') as birth_date
                    ,traveller.id as traveller_id
                    ,room.name
                FROM
                    reservation_traveller
                    inner join traveller on reservation_traveller.traveller_id=traveller.id
                    inner join room_status on room_status.reservation_room_id=reservation_traveller.reservation_room_id
                    inner join room on room_status.room_id=room.id
                WHERE
                    room_status.in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$current_time)).'\'
                ';
        $list_traveller = DB::fetch_all($sql);
        $traveller = array();
        $this->map['birth_date_arr'] = array();
        foreach($list_traveller as $key=>$value)
        {
            $list_traveller[$key]['arr_time_in'] = date('H:i d/m/Y',$value['arrival_time']);
            $list_traveller[$key]['arr_time_out'] = date('H:i d/m/Y',$value['departure_time']);
            $value['arr_time_in'] = date('H:i d/m/Y',$value['arrival_time']);
            $value['arr_time_out'] = date('H:i d/m/Y',$value['departure_time']);
            $value['age'] = date('Y')-$value['age'];
            $value['birth_date_true'] = 0;
            if($value['birth_date']==date('d/m'))
            {
                $this->map['birth_date_arr'][$value['traveller_id']] = $value;
                $value['birth_date_true'] = 1;
            }
            if(!isset($traveller[$value['reservation_room_id']]))
            {
                $traveller[$value['reservation_room_id']]['id'] = $value['reservation_room_id'];
                $traveller[$value['reservation_room_id']]['count'] = 0;
            }
            $traveller[$value['reservation_room_id']]['child'][$key] = $value;
            $traveller[$value['reservation_room_id']]['count'] ++;
        }
        return $traveller;
    }
    
    function get_room_statuses($area_id,$current_time)
	{
	   $sql = '
                SELECT
                    room_status.*
                    ,TO_CHAR(room_status.in_date,\'DD/MM/YYYY\') as in_date
                    ,TO_CHAR(FROM_UNIXTIME(room_status.start_date),\'dd/mm/yyyy\') as rs_start_date
                    ,TO_CHAR(FROM_UNIXTIME(room_status.end_date),\'dd/mm/yyyy\') as rs_end_date
                    ,room.name as room_name
                    ,room.area_id
                    ,room.floor
                    ,customer.name as customer_name
                    ,traveller.first_name || \' \' || traveller.last_name as traveller_name
                    ,TO_CHAR(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_time
                    ,TO_CHAR(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_time
                    ,reservation_room.time_in
                    ,reservation_room.time_out
                    ,reservation_room.note as reservation_room_note
                    ,reservation_room.foc
                    ,reservation_room.foc_all
                    ,reservation_room.adult
                    ,reservation_room.child
                    ,reservation_room.price
                    ,reservation_room.change_room_from_rr
                    ,reservation_room.change_room_to_rr
                    ,reservation_room.status as reservation_room_status
                    ,reservation_room.id as reservation_room_id
                    ,reservation_room.user_id as room_user_id
                    ,reservation.note as reservation_note
                    ,reservation.id as reservation_id
                    ,reservation.color as reservation_color
                FROM 
                    room_status
                    inner join room on room_status.room_id=room.id
                    left join reservation_room on room_status.reservation_room_id=reservation_room.id
                    left join reservation on reservation.id=reservation_room.reservation_id
                    left join customer on reservation.customer_id=customer.id
                    left join traveller on reservation_room.traveller_id=traveller.id
                WHERE
                    room_status.in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$current_time)).'\'
                    AND room.area_id = \''.$area_id.'\'
                    AND room.portal_id = \''.PORTAL_ID.'\'
                ORDER BY
                    room.id, 
                    room.area_id,
    				room.floor, 
    				room.position,
                    reservation_room.status DESC
                ';
        return DB::fetch_all($sql);
	}
    function get_rooms($area_id)
	{
		$sql = '
			select 
				distinct room.id,
				room.name,
                room.area_id,
				room.floor,
				room_level.price,
				CONCAT(room_level.brief_name,CONCAT(\' / \',room_type.brief_name)) AS type_name,
				0 AS overdue_reservation_id,
				\'\' as house_status,
				\'AVAILABLE\' as status,
				\'\' AS note,
				\'\' AS hk_note,
                \'\' as start_time,
                \'\' as end_time,
                \'\' as start_date,
                \'\' as end_date,
				minibar.id as minibar_id,
				0 as confirm,
				0 as extra_bed,
				0 as out_of_service,
				1 as can_book,
				room.position,
				room.room_level_id,
				room.room_type_id,
				room_level.is_virtual,
				room_level.brief_name as room_level_name,
				\'\' as note,
                room_type.id as room_type
			from
				room
				inner join room_level on room_level.id = room.room_level_id
				inner join room_type on room_type.id = room_type_id 
				left outer join minibar on room.id = minibar.room_id 
			where
				room.portal_id = \''.PORTAL_ID.'\'
				'.(Url::get('room_level_id')?' AND room.room_level_id = '.Url::iget('room_level_id').'':'').'
                AND room.area_id = \''.$area_id.'\'
			order by 
                room.id,
                room.area_id,
				room.floor, 
				room.position
                
		';
        $room = DB::fetch_all($sql);
        $floor = array();
        foreach($room as $key=>$value)
        {
            $value['price'] = System::display_number($value['price']);
            $value['active'] = array();
            $value['is_active'] = '';
            if(!isset($floor[$value['floor']]))
            {
                $floor[$value['floor']]['id'] = $value['floor'];
                $floor[$value['floor']]['name'] = $value['floor'];
                $floor[$value['floor']]['area_id'] = $value['area_id'];
                $floor[$value['floor']]['child'] = array();
            }
            $floor[$value['floor']]['child'][$value['id']] = $value;
        }
        
		return $floor;
	}
    
    function cancel_booking_expired()
    {
        // lay ra cac phong o trang thai book ( bao gom ca book not asign)
        // co thoi ngay den nho hon ngay hien tai va chua duoc confirm
		$sql = ' SELECT 
					reservation_room.id
                    ,reservation_room.reservation_id
                    ,reservation_room.time_in
					,CASE 
                        WHEN room.name is null 
                        THEN reservation_room.temp_room 
                        ELSE room.name 
                        END name
					,TO_CHAR(reservation_room.arrival_time,\'DD/MM/YYYY\') as cut_of_date
				 FROM 
                    reservation_room
					inner join reservation ON reservation_room.reservation_id = reservation.id
					left outer join room on room.id = reservation_room.room_id
				WHERE 
					reservation_room.arrival_time <= \''.(Date_Time::to_orc_date(date('d/m/Y'))).'\'
					AND reservation_room.status = \'BOOKED\'
					AND reservation_room.confirm=0';
        
		$rrs = DB::fetch_all($sql);
		$id = 0;
		$check_in_time = explode(':',CHECK_IN_TIME);
		// 5h sau gio checkin mac dinh thi moi cancel phong
		foreach($rrs as $k => $rr)
        {
			if(time() > ($check_in_time[0]*3600 + $check_in_time[1] * 60 + Date_Time::to_time($rr['cut_of_date']) + 61200))
            {			
				if($id==0)
                { 
                    $id=$rr['reservation_id'];
                    $description = '';
                }
				if($id != $rr['reservation_id'])
                {
					$title = 'Edit reservation Cancel automatic <a target=\'_blank\' href=\'?page=reservation&cmd=edit&id='.$id.'\'>#'.$id.'</a>';
					System::log('edit',$title,$description,$id);
					$id = $rr['reservation_id'];
					$description = '';	
				}
				$description .= 'Update status room '.$rr['name'].'( Code: <a target=\'_blank\' href=\'?page=reservation&cmd=edit&id='.$id.'\'&r_r_id='.$rr['id'].'>#'.$rr['id'].'</a>) from BOOKED to CANCEL <br>';
				DB::update('room_status',array('status'=>'CANCEL'),' reservation_room_id='.$rr['id'].'');	
				DB::update('reservation_room',array('status'=>'CANCEL','LASTEST_EDITED_TIME'=>time()),' id='.$rr['id'].'');
				
			}
		}
		if($id != 0)
        {
			$title = 'Edit reservation Cancel automatic <a target=\'_blank\' href=\'?page=reservation&cmd=edit&id='.$id.'\'>#'.$id.'</a>';
			System::log('edit',$title,$description,$id);
		}
	}
    
    function auto_extent_dirty()
    {
        $sql = "select 
                    id
                    ,room_id
                    ,house_status
                    ,in_date 
                from 
                    room_status
                where 
                    (room_id,in_date) in ( select 
                                                room_id
                                                ,max(in_date) 
                                            from 
                                                room_status 
                                            where 
                                                house_status='DIRTY' 
                                            group by 
                                                room_id)";
        $last_hst = DB::fetch_all($sql);
        //xoa trang thai dirty cu + rac
        DB::update('room_status',array('house_status'=>''),"house_status='DIRTY' and (date_to_unix(in_date)+86400) < ".time());
        
        foreach($last_hst as $key => $value)
        {
            if($value['house_status']=='DIRTY')
            {
                $last_t = Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['in_date'],'/'));
                if(time() > $last_t+86400)
                {
                    DB::insert('room_status',array( 'status' => 'AVAILABLE',
                                                    'room_id' => $value['room_id'],
                                                    'in_date'=>Date_Time::to_orc_date(date('d/m/Y')),
                                                    'house_status'=>'DIRTY'));
                }
            }
        }
    }
    function auto_set_dirty()
    {
        DB::fetch("
            update 
                room_status
            set 
                house_status = 'DIRTY'
                ,auto_dirty = 1
            where 
            room_status.id in
            (
              SELECT 
                room_status.id 
              FROM 
                room_status
                inner join reservation_room on reservation_room.id = room_status.reservation_room_id and reservation_room.status = 'CHECKIN' 
              WHERE  
                room_status.auto_dirty is null
                and in_date = '".Date_Time::to_orc_date(date('d/m/y'))."'
                and reservation_room.time_in<".Date_Time::to_time(date('d/m/Y'))."
                and reservation_room.time_out>=".Date_Time::to_time(date('d/m/Y'))."
            )
            ");
	}
    
    
}
?>
