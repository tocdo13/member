<?php

class WorkSheetDailyReportForm  extends Form
{
	function WorkSheetDailyReportForm ()
	{
		Form::Form('WorkSheetDailyReportForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    	$this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
	}
	function draw()
	{
        $this->map = array(); 
        
        $items = $this->load_data();
        $this->map['items'] = $items;
        $this->parse_layout('report',$this->map);
	} 
    
    function load_data()
    {
        $ws_daily_staff_id = Url::get('id');
        $sql = "SELECT * FROM ws_daily_staff WHERE id=".$ws_daily_staff_id;
        $item = DB::fetch($sql);
        $date_ws = $item['date_ws'];
        $date_str = explode("/",Date_Time::convert_orc_date_to_date($date_ws,"/"));
        $this->map['day'] = $date_str[0];
        $this->map['month'] = $date_str[1];
        $this->map['year'] = $date_str[2];
        
        $_REQUEST['staff_name'] = $item['staff_name'];
        
        $sql ="SELECT ws_daily_room.id || '_' ||s.id as id,
                ws_daily_room.work_in,
                ws_daily_room.work_out,
                ws_daily_room.remark,
                s.house_status as status,
                room.name, room_level.brief_name, 
                room_level.name as room_level_name, 
                s.arrival_time, s.departure_time, 
                s.note,s.reservation_room_id,
                room.id as room_id,
                s.adult,
                s.reservation_note,
                s.child,
                s.guest_name,
                s.time_in,s.time_out,
                '' as room_status,
                s.reservation_room_status
                FROM ws_daily_room 
                INNER JOIN room ON room.id=ws_daily_room.room_id 
                INNER JOIN room_level ON room.room_level_id=room_level.id 
                LEFT JOIN (SELECT room_status.id,room_status.room_id,room_status.house_status,
                          reservation_room.arrival_time,reservation_room.departure_time,
                          reservation_room.id as reservation_room_id,
                          reservation_room.note,
                          reservation_room.time_in,
                          reservation_room.time_out,
                          reservation_room.adult,
                          reservation_room.child,
                          reservation.note as reservation_note,
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
                WHERE ws_daily_room.ws_daily_staff_id=".$ws_daily_staff_id."
                ORDER BY room.id asc";
        $items = DB::fetch_all($sql);
        foreach($items as $key=>$value)
        {
            if($value['reservation_room_id']=='')
                $reservation_room_id =-1;
            else
                $reservation_room_id = $value['reservation_room_id'];
            $sql = "select reservation_traveller.id,
                    traveller.first_name,
                    traveller.last_name
                    from reservation_traveller
                    inner join traveller on traveller.id=reservation_traveller.traveller_id 
                    WHERE reservation_traveller.reservation_room_id=".$reservation_room_id;
            $travellers = DB::fetch($sql);
            
            if(empty($travellers)==false)
                $items[$key]['full_name'] = $travellers['first_name'].' '.$travellers['last_name'];
            else
                $items[$key]['full_name'] = '';
            
            //$items[$key]['arrival_time'] = Date_Time::convert_orc_date_to_date($value['arrival_time'],"/");
            //$items[$key]['departure_time'] = Date_Time::convert_orc_date_to_date($value['departure_time'],"/");
        }
        $items=WorkSheetDailyReportForm::make_status($items,$date_ws);
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
                if($items[$row['id']]['room_status']=='' or !isset($items[$row['id']]['room_status']))
                {
                    if(($row['arrival_time'] and  $row_old['arrival_time']==$date_view and $row['departure_time']==$date_view)||($row_old['departure_time']==$date_view&&$row['arrival_time']==$date_view) ){
                    $items[$row_old['id']]['room_status']='A-D'; 
                    }elseif( $row['arrival_time'] and $row['arrival_time']==$date_view and $row['reservation_room_status']=='BOOKED'){
                       $items[$row_old['id']]['room_status']='A'; 
                    }elseif($row['departure_time'] and $row['departure_time']==$date_view and $row['reservation_room_status']=='CHECKIN'){
                       $items[$row_old['id']]['room_status']='D'; 
                    }
                }
                if($row['time_in'] and $row['time_in']<=time() and $row['time_out']>=time()){
                    $items[$row_old['id']]['reservation_room_status']=$items[$row['id']]['reservation_room_status'];
                    $items[$row_old['id']]['arrival_time']=$items[$row['id']]['arrival_time'];
                    $items[$row_old['id']]['departure_time']=$items[$row['id']]['departure_time'];
                    $items[$row_old['id']]['adult']=$items[$row['id']]['adult'];
                    $items[$row_old['id']]['child']=$items[$row['id']]['child'];
                    $items[$row_old['id']]['guest_name']=$items[$row['id']]['guest_name'];
                    $items[$row_old['id']]['reservation_note']=$items[$row['id']]['reservation_note'];
                   
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
                if($items[$row['id']]['room_status']=='' or !isset($items[$row['id']]['room_status']))
                {
                    if($row['arrival_time'] and $row['arrival_time']==$date_view && $row['reservation_room_status']=='BOOKED'){
                       $items[$row['id']]['room_status']='A'; 
                    }elseif($row['departure_time'] and $row['departure_time']==$date_view && $row['reservation_room_status']=='CHECKIN'){
                       $items[$row['id']]['room_status']='D'; 
                    }else{
                       $items[$row['id']]['room_status']='';  
                    }
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
        return $items;
        /** END kieu chinh l?i phan cong don phong **/    
    }
}   

?>
