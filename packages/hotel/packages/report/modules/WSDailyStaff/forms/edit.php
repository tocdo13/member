<?php
class EditDailyStaffForm extends Form
{
	function EditDailyStaffForm()
	{
		Form::Form('EditDailyStaffForm');
		$this->link_js('packages/core/includes/js/multi_items.js');   
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
    
	function on_submit()
	{	 
	    //System::debug($_REQUEST);exit();
        if(Url::get('cmd')=='edit')
        {            
            //0. Lay ra tat ca room_id da duoc giao truoc khi cap nhat 
            $ws_daily_staff_id =Url::get('ws_daily_staff_id');
            
            //0.1 update ws_daily_staff 
            $staff_name = Url::get('staff_name');
            $date_ws = explode("/",Url::get('from_date'));
            $d = mktime(0,0,0,$date_ws[1],$date_ws[0],$date_ws[2]);
            $date_ws = Date_Time::convert_time_to_ora_date($d);
            
            $row_staff = array('staff_name'=>$staff_name,
                                'date_ws'=>$date_ws);
            DB::update('ws_daily_staff',$row_staff,'id='.$ws_daily_staff_id);
           // DB::query($sql);
            
            $sql = "SELECT id,room_id FROM ws_daily_room WHERE ws_daily_staff_id=".Url::get('ws_daily_staff_id');
            $ids_old = DB::fetch_all($sql);
            
            $str_ids = Url::get('selected_chb');
            $str_ids = substr($str_ids,0,strlen($str_ids)-1);
            
            $ids = explode(",",$str_ids);
            
            $house_status = Url::get('house_status');
            $reservation_room = Url::get('reservation_room_id');
            $work_in = Url::get('work_in');
            $work_out = Url::get('work_out');
            $remark = Url::get('remark');
            
            //1. duyet qua mang id_old 
            foreach($ids_old as $old)
            {
                //1.1 neu id_old nam trong ids thi thuc hien cap nhat theo ws_daily_staff_id & id_room 
                //1.2. neu id_old khong nam trong ids thi thuc hien xoa thong tin theo ws_daily_staff_id & room_id
                if(in_array($old['room_id'],$ids))
                {
                    $reservation_room = (isset($reservation_room[$old['room_id']]) && $reservation_room[$old['room_id']]!='')?$reservation_room[$old['room_id']]:0;
                    $sql = "Update ws_daily_room set ";//status='".$house_status[$old['room_id']]."',
                    //$sql .="reservation_room_id=".$reservation_room.",";
                    $sql .="work_in='".$work_in[$old['room_id']]."',";
                    $sql .="work_out='".$work_out[$old['room_id']]."',";
                    $sql .="remark='".$remark[$old['room_id']]."' ";
                    $sql .="WHERE ws_daily_staff_id=".$ws_daily_staff_id." AND room_id=".$old['room_id'];
                    
                    DB::query($sql);
                    $key = array_search($old['room_id'],$ids);
                    
                    if($key!==false)
                        unset($ids[$key]);
                }
                else
                {
                    DB::delete('ws_daily_room','ws_daily_staff_id='.$ws_daily_staff_id.' AND room_id='.$old['room_id']);
                }
                
            }
            //3. Truong hop them moi theo ws_daily_staff_id va room_id 
            foreach($ids as $room_id)
            {
                $reservation_room = (isset($reservation_room[$room_id]) && $reservation_room[$room_id]!='')?$reservation_room[$room_id]:0;
                $row =array('room_id'=>$room_id,
                            'ws_daily_staff_id'=>$ws_daily_staff_id,
                            'reservation_room_id'=>$reservation_room,
                            'status'=>$house_status[$room_id],
                            'work_in'=>$work_in[$room_id],
                            'work_out'=>$work_out[$room_id],
                            'remark'=>$remark[$room_id]);
                DB::insert('ws_daily_room',$row);       
            }
            
            Url::redirect_current();   
        }
        else if(Url::get('cmd')=='add' && Url::get('re_add')==false &&Url::get('floor_view')==false)
        {             
            //1. them vao bang ws_daily_staff theo ten va ngay
            $staff_name = Url::get('staff_name');
            $date_ws = explode("/",Url::get('from_date'));
            $d = mktime(0,0,0,$date_ws[1],$date_ws[0],$date_ws[2]);
            $date_ws = Date_Time::convert_time_to_ora_date($d);
            
            $sql = "SELECT * FROM ws_daily_staff WHERE staff_name='".$staff_name."' AND date_ws='".$date_ws."'";
            $staff = DB::fetch($sql);
            if(empty($staff))//them moi 
            {
                $staff_id_date = DB::insert('ws_daily_staff',array('staff_name'=>$staff_name,'date_ws'=>$date_ws));
                
            }
            else//da co nhan vien giao trong ngay 
            {
                $staff_id_date = $staff['id'];
            }
            $str_ids = Url::get('selected_chb');
            $str_ids = substr($str_ids,0,strlen($str_ids)-1);
            
            $ids = explode(",",$str_ids);
            
            $house_status = Url::get('house_status');
            $reservation_room = Url::get('reservation_room_id');
            $work_in = Url::get('work_in');
            $work_out = Url::get('work_out');
            $remark = Url::get('remark');
            foreach($ids as $room_id)
            {
                $row = array('ws_daily_staff_id'=>$staff_id_date,
                            'room_id'=>$room_id,
                            'reservation_room_id'=>$reservation_room[$room_id],
                            'work_in'=>$work_in[$room_id],
                            'work_out'=>$work_out[$room_id],
                            'remark'=>$remark[$room_id]);
                            
                DB::insert('ws_daily_room',$row);
            }
            Url::redirect_current();
        } 
	}
	function draw()
	{ 
        //0. xoa di lich su tinh tu 1 thang tro ve truoc 
        require_once'packages/hotel/packages/report/modules/WSDailyStaff/forms/report.php';
        $this->remove_history_longtime();
        $this->map = array();
        
        /** START: Daund sua trang thai phong */
        $this->map['new_status_list'] = array(
            ''=>Portal::language('all'),
            'VC'=>Portal::language('phong_trong_sach'),
            'VD'=>Portal::language('phong_trong_ban'),
            'OOO'=>Portal::language('phong_sua_chua_lon'),
            'OD'=>Portal::language('phong_co_khach_ban'),
            'OC'=>Portal::language('phong_co_khach_sach')
        );
        /** END: Daund sua trang thai phong */
        if(Url::get('cmd')=='edit')
        {
            $date_ws = Url::get('date_ws');
            $ws_daily_staff_id = Url::get('ws_daily_staff_id');
            $_REQUEST['from_date'] = Date_Time::convert_orc_date_to_date($date_ws,"/");
            $from_date = explode("/",$_REQUEST['from_date']);
            $d =mktime(0,0,0,$from_date[1],$from_date[0],$from_date[2]);
            $now = mktime(0,0,0,date('m'),date('d'),date('Y'));
            if($d<$now)
                $this->map['permission_save'] = 0;
            else
                $this->map['permission_save'] = 1;
            //$this->map['from_date'] = Date_Time::convert_orc_date_to_date($date_ws,"/");
            $staff = DB::fetch("SELECT * FROM ws_daily_staff WHERE id=".$ws_daily_staff_id);
            $_REQUEST['staff_name'] = $staff['staff_name'];
            //1. lay ra danh sach cac phong thuoc reservation_room_id neu co trong ngay hien tai
            $items = $this->get_list_room($date_ws,$ws_daily_staff_id);
            /** START kieu sua lai phan cong cong viec **/
            $items= WorkSheetDailyReportForm::make_status($items,$date_ws);
            /** END kieu sua lai phan cong cong viec **/
            $this->map['items'] = $items;            
        }
        else if(Url::get('cmd')=='add' )//truong hop them moi
        {
            $month=array('01'=>'JAN','02'=>'FEB','03'=>'MAR','04'=>'APR','05'=>'MAY','06'=>'JUN','07'=>'JUL','08'=>'AUG','09'=>'SEP','10'=>'OCT','11'=>'NOV','12'=>'DEC');
            $this->map['permission_save'] = 1;
            if(isset($_REQUEST['from_date']))
            {
                $from_date = explode("/",$_REQUEST['from_date']);
                $d =mktime(0,0,0,$from_date[1],$from_date[0],$from_date[2]);
                $date_ws =$from_date[0].'-'.$month[$from_date[1]].'-'.date('y');
            }
            else
            {
                $_REQUEST['from_date'] = date('d/m/Y');
                $date_ws =date('d').'-'.$month[date('m')].'-'.date('y'); 
            }
            $floor_condition='and 1=1';
            //System::debug($_REQUEST['floor']);
            if(isset($_REQUEST['floor']) && $_REQUEST['floor'] != '')
            {  
                $floor_condition.=' and room.coordinates in ('.$_REQUEST['floor'].') ';
            }
            $items = $this->get_list_room_add($date_ws,$floor_condition);
            //System::debug($items);
            /** START kieu sua lai phan cong cong viec **/
            $items= WorkSheetDailyReportForm::make_status($items,$date_ws);
            $floors=DB::fetch_all('
                            select 
                                room.floor as id,
                                room.coordinates 
                            from 
                                room
                               INNER JOIN room_level ON room.room_level_id=room_level.id
                            where 
                                room_level.is_virtual=0 
                            group by 
                                room.id,
                                room.floor,
                                room.coordinates 
                            order by 
                                room.id asc'
                                );
            //System::debug($floors);                                                
            $floor_option='';
            foreach($floors as $k=>$v){          
                $floor_option.='<li><input type="checkbox" onclick="get_ids()" value='.$v['coordinates'].' />'.$v['id'].'</li>';
            }
            $this->map['floor_option'] = $floor_option;
            /** END kieu sua lai phan cong cong viec **/
            $this->map['items'] = $items;
        }
        //System::debug($this->map['items']);
        $this->parse_layout('edit',$this->map);
	}
    function remove_history_longtime()
    {
        $before = time() - 30*86400;
        $before_oracle = Date_Time::convert_time_to_ora_date($before);
        //1. lay ra tat ca nhung lan giao trong qua khu 1 thang tro ve truoc 
        $items = DB::fetch_all("SELECT id FROM ws_daily_staff WHERE date_ws<='".$before_oracle."'");
        foreach($items as $row)
        {
            $id = $row['id'];
            DB::delete('ws_daily_staff','id='.$id);
            DB::delete('ws_daily_room','ws_daily_staff_id='.$id);
        }
    }
    function get_list_room($date_oracle,$ws_daily_staff_id)
    { 
        $sql="SELECT room.id || '_' || room_status.id as id,
                ws.work_in,ws.work_out,ws.remark,
                room_status.house_status as status,
                room.id as room_id,
                room.name,
                reservation_room.arrival_time, 
                reservation_room.departure_time, 
                reservation_room.note,
                reservation_room.adult,
                reservation_room.child,
                extra_service_invoice.use_extra_bed as extrabed,
                reservation_room.time_in,
                reservation_room.time_out,
                reservation_room.status as reservation_room_status,
                reservation_room.id as reservation_room_id,
                reservation.note as reservation_note,
                          case when 
                                    reservation.booker is not null then reservation.booker 
                                else
                                    customer.name
                                end
                          as guest_name,
                (CASE WHEN exists(SELECT * FROM ws_daily_room,ws_daily_staff 
                            WHERE ws_daily_room.ws_daily_staff_id=ws_daily_staff.id AND ws_daily_room.ws_daily_staff_id=".$ws_daily_staff_id."
                            AND ws_daily_room.room_id=room.id) THEN 1 ELSE 0 END) as status_cmd
                
                FROM room INNER JOIN room_level ON room.room_level_id=room_level.id 
                LEFT JOIN room_status ON room_status.room_id=room.id and room_status.in_date='".$date_oracle."' 
                LEFT JOIN reservation_room ON room_status.reservation_room_id=reservation_room.id
                LEFT JOIN reservation on reservation.id=room_status.reservation_id
                LEFT JOIN customer on reservation.customer_id=customer.id
                --oanh add
                left join extra_service_invoice on reservation_room.id = extra_service_invoice.reservation_room_id and extra_service_invoice.use_extra_bed=1
                --end 
                LEFT JOIN (SELECT ws_daily_room.*
                          FROM ws_daily_room,ws_daily_staff
                          WHERE ws_daily_room.ws_daily_staff_id=ws_daily_staff.id AND ws_daily_staff.date_ws='".$date_oracle."') ws
                          ON ws.room_id=room.id
                
                WHERE room_level.is_virtual=0 
                and room.close_room=1
                AND room.portal_id='".PORTAL_ID."' 
                AND room.id not in(SELECT ws_daily_room.room_id
                    FROM ws_daily_staff,ws_daily_room
                    WHERE ws_daily_staff.date_ws='".$date_oracle."' 
                    AND ws_daily_staff.id=ws_daily_room.ws_daily_staff_id
                    AND ws_daily_room.ws_daily_staff_id !=".$ws_daily_staff_id.")
                ORDER BY room.id asc";
        $items = DB::fetch_all($sql);
        return $items;
    }
    
    function get_list_room_add($date_oracle,$floor_condition)
    {  
        $sql = '
			select 
				room.id,
                room.id as room_id,
				room.name,
				room.floor,
				0 AS overdue_reservation_id,
                \'\' as arrival_time, 
                \'\' as departure_time, 
                \'\' as time_in,
                \'\' as time_out,
                \'\' as reservation_room_status,
                \'\' as reservation_room_id,
                \'\' as extrabed,
				\'\' as reservation_room_status,
				\'\' as status,
				\'\' as status_cmd,
                \'\' as work_in,
                \'\' as work_out,
                \'\' as remark,
                \'\' as note,
                \'\' as adult,
                \'\' as child,
                \'\' as guest_name,
                \'\' as reservation_note,
                \'\' as room_status,
                room_type.id as room_type
			from
				room
				inner join room_level on room_level.id = room.room_level_id
				inner join room_type on room_type.id = room_type_id 
			where
				room.portal_id = \''.PORTAL_ID.'\'
                and room_level.is_virtual=0
                and room.close_room=1
                '.$floor_condition.'
                AND room.id not in(SELECT ws_daily_room.room_id
                    FROM ws_daily_staff,ws_daily_room
                    WHERE ws_daily_staff.date_ws=\''.$date_oracle.' \'
                    AND ws_daily_staff.id=ws_daily_room.ws_daily_staff_id)
			order by
                room.id, 
				room.floor, 
				room.position
		';
        //System::debug($sql);
		$rooms = DB::fetch_all($sql);
        $sql = '
		select DISTINCT 
			room_status.id,
			room_status.room_id,
			room_status.reservation_id,
			reservation_room.status as status,
			room_status.house_status,
			reservation_room.note as room_note,
			room_status.extra_bed,
			reservation_room.id as reservation_room_id,
			reservation_room.time_in,
			reservation_room.time_out,
			reservation_room.departure_time,
			reservation_room.arrival_time,
			room_status.in_date,
            DECODE(reservation_room.status,\'CHECKIN\',1,(DECODE(reservation_room.status,\'BOOKED\',2,DECODE(reservation_room.status,\'CHECKOUT\',3,4)))) AS status_order,
            extra_service_invoice.use_extra_bed as extrabed,
            room.name
		from
			room_status
			left outer join reservation_room on reservation_room.id = room_status.reservation_room_id
			left outer join reservation on reservation.id = room_status.reservation_id
            inner join room on room_status.room_id = room.id
            left join extra_service_invoice on reservation_room.id = extra_service_invoice.reservation_room_id and extra_service_invoice.use_extra_bed=1
		where 
			reservation.portal_id = \''.PORTAL_ID.'\'
			and room_status.status <> \'CANCEL\' and room_status.status <> \'NOSHOW\' AND reservation_room.status<>\'CANCEL\' AND reservation_room.status<>\'NOSHOW\' AND room_status.status <> \'AVAILABLE\'
			AND room_status.in_date = \''.$date_oracle.'\'
            '.$floor_condition.'
        order by 
			status_order DESC,ABS(reservation_room.time_in - '.time().') DESC
		';
        $room_statuses = DB::fetch_all($sql);
        //System::debug($room_statuses);
        $sql = '
			SELECT
				room_status.*,\'\' as full_name,room_status.note as hk_note
				,TO_CHAR(room_status.start_date,\'dd/mm/yyyy\') as start_date
				,TO_CHAR(room_status.end_date,\'dd/mm/yyyy\') as end_date
                ,reservation_room.id as reservation_room_id
                ,reservation_room.time_in
			    ,reservation_room.time_out
                ,reservation_room.departure_time
                ,reservation_room.arrival_time
                ,extra_service_invoice.use_extra_bed as extrabed
                ,room.id as room_id
                ,room.name
			FROM
				room_status
				INNER JOIN room ON room.id = room_status.room_id
				LEFT OUTER JOIN reservation ON reservation.id = room_status.reservation_id
				LEFT OUTER JOIN reservation_room ON reservation_room.reservation_id = reservation.id
                left join extra_service_invoice on reservation_room.id = extra_service_invoice.reservation_room_id and extra_service_invoice.use_extra_bed=1
			WHERE
				room.portal_id = \''.PORTAL_ID.'\'
                and room.close_room=1
                and (room_status.status = \'AVAILABLE\' OR reservation_room.status = \'CHECKOUT\' OR reservation_room.status = \'CANCEL\' OR reservation_room.status = \'NOSHOW\') AND (room_status.note is not null OR room_status.house_status is not null)
				AND room_status.in_date>=\''.$date_oracle.'\'
				AND room_status.in_date<=\''.$date_oracle.'\'
                '.$floor_condition.'
			ORDER BY 
				room_status.id
		';
        //System::debug($sql);
		$available_rooms = DB::fetch_all($sql);
		$house_status_arr = array();
		foreach($available_rooms as $k=>$v){
			if($v['house_status']){
				$house_status_arr[$v['room_id']] = $v['house_status'];
			}
		}
        $room_status_ad = array();
		foreach($room_statuses as $id=>$room_status)
		{
			$k_ = '';
			foreach($available_rooms as $k=>$v){
				if($v['room_id'] == $room_status['room_id'] and $v['in_date'] == $room_status['in_date']){
                    if($v['house_status']=='REPAIR')
                    {
    					$room_statuses[$id]['time_in'] = Date_Time::to_time($v['start_date']);
                        $room_statuses[$id]['time_out'] = Date_Time::to_time($v['end_date']);
                    }
                    unset($available_rooms[$k]);
				}
			}
			if(isset($house_status_arr[$room_status['room_id']])){
				$room_statuses[$id]['house_status'] = $house_status_arr[$room_status['room_id']];
			}
			if(isset($available_rooms[$k_])){
				unset($available_rooms[$k_]);
			}
            if(!isset($room_status_ad[$room_status['room_id']]))
            {
                $room_status_ad[$room_status['room_id']]['id'] = $room_status['room_id'];
                $room_status_ad[$room_status['room_id']]['arrival_time'] = $room_status['arrival_time'];
                $room_status_ad[$room_status['room_id']]['departure_time'] = $room_status['departure_time'];
            }else{
                if(($room_status_ad[$room_status['room_id']]['arrival_time'] == $date_oracle and $room_status['departure_time'] == $date_oracle ) or ($room_status_ad[$room_status['room_id']]['departure_time']==$date_oracle and $room_status['arrival_time']==$date_oracle))
                {
                    $rooms[$room_status['room_id']]['room_status'] = 'D-A';
                }
            }
		}
		$room_statuses += $available_rooms;
        
        $room_id = 0;
        foreach($room_statuses as $key=>$room_status)
		{
			if(isset($rooms[$room_status['room_id']]))
			{	
				if(isset($room_overdues[$room_status['room_id']]) and $room_status['in_date'] == $date_oracle){
					
				}
				{
					$status_arr[$room_status['room_id']] = $room_status;
					if($room_status['room_id']!=$room_id)
					{
						$room_id = $room_status['room_id'];
						unset($room_status['room_id']);	
						$rooms[$room_id]['reservation_room_status'] = $room_status['status'];
						$rooms[$room_id]['status'] = $room_status['house_status'];
                        $rooms[$room_id]['extrabed'] = $room_status['extrabed'];
                        $rooms[$room_id]['arrival_time'] = $room_status['arrival_time'];
                        $rooms[$room_id]['departure_time'] = $room_status['departure_time'];
                        $rooms[$room_id]['time_in'] = $room_status['time_in'];
                        $rooms[$room_id]['time_out'] = $room_status['time_out'];
                        $rooms[$room_id]['reservation_room_id'] = $room_status['reservation_room_id'];
					}else{
						if(isset($status_arr[$room_status['room_id']]) and $status_arr[$room_status['room_id']]!='AVAILABLE'){
						    $rooms[$room_status['room_id']]['reservation_room_status'] = $status_arr[$room_status['room_id']]['status'];
    						$rooms[$room_status['room_id']]['status'] = $status_arr[$room_status['room_id']]['house_status'];
                            $rooms[$room_status['room_id']]['extrabed'] = $status_arr[$room_status['room_id']]['extrabed'];
                            $rooms[$room_status['room_id']]['arrival_time'] = $status_arr[$room_status['room_id']]['arrival_time'];
                            $rooms[$room_status['room_id']]['departure_time'] = $status_arr[$room_status['room_id']]['departure_time'];
                            $rooms[$room_status['room_id']]['time_in'] = $status_arr[$room_status['room_id']]['time_in'];
                            $rooms[$room_status['room_id']]['time_out'] = $status_arr[$room_status['room_id']]['time_out'];
                            $rooms[$room_status['room_id']]['reservation_room_id'] = $status_arr[$room_status['room_id']]['reservation_room_id'];
						}
					}
				}
			}
		}
        
        return $rooms;
    }

}
?>
