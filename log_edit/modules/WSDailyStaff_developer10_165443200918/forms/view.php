<?php

class WorkSheetDailyViewForm  extends Form
{
	function WorkSheetDailyViewForm ()
	{
		Form::Form('WorkSheetDailyViewForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    	$this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
	}
	function draw()
	{
        $this->map = array(); 
        $this->map['permission_save'] = 1;
        $date_ws=Url::get('day_view');
        $this->map['date']=Date_Time::convert_orc_date_to_date($date_ws,'/');
        $items = $this->load_data($date_ws);
        $items=WorkSheetDailyViewForm::make_status($items,$date_ws);
        $this->map['items'] = $items;
        $this->parse_layout('view',$this->map);
	} 
    
    function load_data($date_ws)
    {
        $sql ="SELECT ws_daily_room.id || '_' ||s.id as id,
                ws_daily_room.work_in,
                ws_daily_room.work_out,
                ws_daily_room.remark,
                s.house_status as status,
                ws_daily_staff.staff_name,
                room_type.brief_name,
                s.arrival_time, s.departure_time,
                room.id as room_id,
                s.time_in,s.time_out,
                room.name,
                s.reservation_room_status
                FROM ws_daily_room 
                INNER JOIN room ON room.id=ws_daily_room.room_id 
                INNER JOIN room_type on room_type.id=room.room_type_id
                LEFT JOIN ws_daily_staff on  ws_daily_staff.id=ws_daily_room.ws_daily_staff_id and ws_daily_staff.date_ws='".$date_ws."'
                LEFT JOIN (SELECT room_status.id,room_status.room_id,room_status.house_status,
                            reservation_room.id as reservation_room_id,
                          reservation_room.arrival_time,reservation_room.departure_time,
                          reservation_room.time_in,
                          reservation_room.time_out,
                          case when 
                                    reservation.booker is not null then reservation.booker 
                                else
                                    customer.name
                                end
                          as guest_name,          
                          reservation_room.status as reservation_room_status
                          FROM room_status
                          LEFT JOIN reservation_room ON room_status.reservation_room_id=reservation_room.id AND reservation_room.status!='CHECKOUT' AND reservation_room.status!='CANCEL'
                          LEFT JOIN reservation on room_status.reservation_id=reservation.id
                          LEFT JOIN customer on reservation.customer_id=customer.id
                          WHERE room_status.in_date='".$date_ws."' ) s
                          ON s.room_id=room.id
                ORDER BY room.id asc";
        $items = DB::fetch_all($sql);
        return $items;
    }     
    function make_status($items,$date_view){
        $row_old = false;
        $i=1;
        /** START kieu chinh l?i phan cong don phong **/
        foreach($items as $row)
        {
            if($row_old!=false && $row['name']==$row_old['name'])
            {
                if(($row['arrival_time'] and  $row_old['arrival_time']==$date_view and $row['departure_time']==$date_view)||($row_old['departure_time']==$date_view&&$row['arrival_time']==$date_view) ){
                   $items[$row_old['id']]['room_status']='A-D'; 
                }elseif( $row['arrival_time'] and $row['arrival_time']==$date_view and $row['reservation_room_status']=='BOOKED'){
                   $items[$row_old['id']]['room_status']='A'; 
                }elseif($row['departure_time'] and $row['departure_time']==$date_view and $row['reservation_room_status']=='CHECKIN'){
                   $items[$row_old['id']]['room_status']='D'; 
                }
                if($row['time_in'] and $row['time_in']<=time() and $row['time_out']>=time()){
                    $items[$row_old['id']]['reservation_room_status']=$items[$row['id']]['reservation_room_status'];
                    $items[$row_old['id']]['arrival_time']=$items[$row['id']]['arrival_time'];
                    $items[$row_old['id']]['departure_time']=$items[$row['id']]['departure_time'];
                }
                if($row['staff_name']!=''){
                   $items[$row_old['id']]['staff_name']=$items[$row['id']]['staff_name']; 
                }
               if($row['status']=='DIRTY' or $row['status']=='REPAIR'){
                    $items[$row_old['id']]['status']=$row['status']; 
                 }
                 if($row['reservation_room_status']=='CHECKIN'){
                    $items[$row_old['id']]['reservation_room_status']='CHECKIN'; 
                 }   
                unset($items[$row['id']]);      
            }
            else
            {
               if($row['arrival_time'] and $row['arrival_time']==$date_view && $row['reservation_room_status']=='BOOKED'){
                   $items[$row['id']]['room_status']='A'; 
                }elseif($row['departure_time'] and $row['departure_time']==$date_view && $row['reservation_room_status']=='CHECKIN'){
                   $items[$row['id']]['room_status']='D'; 
                }else{
                   $items[$row['id']]['room_status']='';  
                }
                $row_old = $row;
            } 
        }
        foreach($items as $row){
            $items[$row['id']]['index']=$i;$i++;
           $items[$row['id']]['arrival_time']= Date_Time::convert_orc_date_to_date($row['arrival_time'],"/");
           $items[$row['id']]['departure_time']= Date_Time::convert_orc_date_to_date($row['departure_time'],"/");
             if($row['status']=='DIRTY' and $row['reservation_room_status']=='CHECKIN'){
            $items[$row['id']]['room_guest_status']='OD';
            }elseif($row['status']!='DIRTY' and $row['reservation_room_status']=='CHECKIN'){
                $items[$row['id']]['room_guest_status']='OC';
            }elseif($row['status']=='DIRTY' and $row['reservation_room_status']!='CHECKIN'){
                $items[$row['id']]['room_guest_status']='VD';
            }elseif($row['status']=='REPAIR'){
                $items[$row['id']]['room_guest_status']='OOO';
            }else{
                $items[$row['id']]['room_guest_status']='VC'; 
            }
        }
        foreach($items as $row => $value)
        {
            if($value['staff_name'] == '')
            {
                unset($items[$row]);
            }
        }

        return $items;
        /** END kieu chinh l?i phan cong don phong **/    
    }
}   

?>
