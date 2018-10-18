<?php
date_default_timezone_set('Asia/Saigon');
require_once('packages/core/includes/system/config.php');
require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
require_once('packages/hotel/packages/hotellink/modules/HotelLinkDashBoard/create_reservation.php');
require_once('packages/hotel/packages/hotellink/modules/HotelLinkDashBoard/hls.php');
class HLSAuto{
    function updateBookingEngine(){
        if(!DB::fetch('select id from HOTELLINK_AVAILABILITY where rownum=1')){
            $ratesPlan=DB::fetch_all('select id,rate,hotellink_room_id from hotellink_rate_plan');
            $fromDate=date('Y').'-'.date('m').'-'.date('d');
            $toDate=(date('Y')+2).'-'.date('m').'-'.date('d');
            foreach($ratesPlan as $ratesPlanKey=>$ratesPlanValue){
                $availabilityAndRate=new HLS;
                $availabilityAndRateResult=$availabilityAndRate->getInventory($ratesPlanKey,$fromDate,$toDate);
                if($availabilityAndRateResult['Availabilities']){
                    foreach($availabilityAndRateResult['Availabilities'] as $key=>$value){
                        $from=explode('-',$value['DateRange']['From']);
                        $to=explode('-',$value['DateRange']['To']);
                        for($i=mktime(10,10,10,$from[1],substr($from[2],0,2),$from[0]) ;$i<=mktime(10,10,10,$to[1],substr($to[2],0,2),$to[0]);$i+=86400){
                            if(!DB::fetch('select id from HOTELLINK_AVAILABILITY where in_date=\''.Date_Time::convert_time_to_ora_date($i).'\' and hotellink_room_id=\''.$ratesPlanValue['hotellink_room_id'].'\'')){
                                DB::insert('HOTELLINK_AVAILABILITY',array('hotellink_room_id'=>$ratesPlanValue['hotellink_room_id'],'in_date'=>Date_Time::convert_time_to_ora_date($i),'total'=>$value['Quantity'],'status'=>'COMPLETED'));
                            }
                        }
                    }
                }
                if($availabilityAndRateResult['RatePackages']){
                    foreach($availabilityAndRateResult['RatePackages'] as $key=>$value){
                        $from=explode('-',$value['DateRange']['From']);
                        $to=explode('-',$value['DateRange']['To']);
                        for($i=mktime(0,0,0,$from[1],substr($from[2],0,2),$from[0]) ;$i<=mktime(0,0,0,$to[1],substr($to[2],0,2),$to[0]);$i+=86400){
                            DB::insert('HOTELLINK_RATE',array('rate_plan_id'=>$value['RatePlanId'],'in_date'=>Date_Time::convert_time_to_ora_date($i),'total'=>$value['Rate']['Amount']['Value'],'extra_child'=>$value['ExtraChildRate']['Amount']['Value']?$value['ExtraChildRate']['Amount']['Value']:0,'stop_sell'=>$value['StopSell']?1:0,'extra_adult'=>$value['ExtraAdultRate']['Amount']['Value']?$value['ExtraAdultRate']['Amount']['Value']:0,'status'=>'COMPLETED'));
                        }
                    }
                }
            }
        }  
    }
    function deleteOldData(){
        DB::delete('HOTELLINK_AVAILABILITY','in_date<\''.Date_Time::convert_time_to_ora_date(time()).'\'');
        DB::delete('HOTELLINK_RATE','in_date<\''.Date_Time::convert_time_to_ora_date(time()).'\'');
    }
    function updateInventory(){
        /** cap nhat lai Availability trong database neu nhu nguoi dung edit**/
        $availability=DB::fetch_all('select id,hotellink_room_id,total,to_char(in_date,\'DD/MM/YYYY\') as in_date from hotellink_availability where status=\'MODIFIED\' order by hotellink_room_id,total,in_date');
        $ratePackages=DB::fetch_all('select hotellink_rate.id,hotellink_rate.rate_plan_id,hotellink_rate.total,to_char(hotellink_rate.in_date,\'DD/MM/YYYY\') as in_date,hotellink_rate.stop_sell,hotellink_rate.extra_adult,hotellink_rate.extra_child,hotellink_rate_plan.hotellink_room_id 
                                    from hotellink_rate
                                    inner join hotellink_rate_plan on hotellink_rate_plan.id=hotellink_rate.rate_plan_id
                                    where status=\'MODIFIED\' 
                                    order by rate_plan_id,total,in_date');
        $inventories['availability']=array();
        $inventories['ratePackages']=array();
        if($availability or $ratePackages){
            if($availability){
                $availability=HLSAuto::groupingAvailability($availability);
                $inventories['availability']=$availability;
                $hls=new HLS;
                $result=$hls->saveInventory($inventories);
                if($result){
                    DB::update('hotellink_availability',array('status'=>'COMPLETED'),'id in ('.$result.')');
                }
            }
            if($ratePackages){
                $ratePackages=HLSAuto::groupingRatePackages($ratePackages);
                $inventories['ratePackages']=$ratePackages;
                $hls=new HLS;
                $result=$hls->saveInventory($inventories);
                if($result){
                    DB::update('hotellink_rate',array('status'=>'COMPLETED'),'id in ('.$result.')');
                }
            }
        }
        $newBooking=DB::fetch_all('select reservation_room.id,reservation_room.arrival_time,reservation_room.departure_time,reservation_room.room_level_id
                                   FROM reservation_room
                                   INNER JOIN reservation on reservation.id=reservation_room.reservation_id
                                   WHERE (reservation.time<='.time().' and reservation.time>='.(time()-3600).')
                                   OR (reservation_room.lastest_edited_time<='.time().' and reservation_room.lastest_edited_time>='.(time()-3600).')
                                    ');                         
        if($newBooking){
            foreach($newBooking as $key=>$value){
                $availabilityResult=check_availability('','rl.id='.$value['room_level_id'].'',Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['arrival_time'],'/')),Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['departure_time'],'/')));
                $allotment=DB::fetch_all('select to_char(hotellink_availability.in_date,\'DD/MM/YYYY\') as id,hotellink_availability.id as update_id, room_level.id as room_id,hotellink_availability.total
                              FROM hotellink_availability 
                              INNER join room_level on hotellink_availability.hotellink_room_id=room_level.hotellink_room_id 
                              WHERE room_level.id='.$value['room_level_id'].' and hotellink_availability.in_date>=\''.$value['arrival_time'].'\' and hotellink_availability.in_date<=\''.$value['departure_time'].'\'');               
                foreach($availabilityResult[$value['room_level_id']]['day_items'] as $k=>$v){
                    if(isset($allotment[date('j/n/Y',$k)]['total'])){
                        if($v['number_room_quantity']<$allotment[date('d/m/Y',$k)]['total']){
                            DB::update('hotellink_availability',array('total'=>$v['number_room_quantity'],'status'=>'MODIFIED'),'id='.$allotment[date('d/m/Y',$k)]['update_id'].'');
                        }
                    }
                }                
            }
        }           
    }
    function groupingAvailability($availability){
        $oldAvailability =false;
        foreach($availability as $key=>$value){
            $availability[$key]['from_date']=$value['in_date'];
            $availability[$key]['to_date']=$value['in_date'];
            $availability[$key]['list_id']=$value['id'];
            if($oldAvailability!=false and $value['hotellink_room_id']=$oldAvailability['hotellink_room_id'] and $value['total']=$oldAvailability['total'] and date('d/m/Y',(Date_Time::to_time($oldAvailability['in_date'])+86400))==$value['in_date']){
                $availability[$key]['from_date']=$oldAvailability['from_date'];
                $availability[$key]['list_id']=$oldAvailability['list_id'].=','.$value['id'].'';
                $value['from_date']=$oldAvailability['from_date'];
                unset($availability[$oldAvailability['id']]);
            }
            $oldAvailability=$availability[$key];
        }
        return $availability;
    }
    function groupingRatePackages($ratePackages){
        $oldRatePackages =false;
        foreach($ratePackages as $key=>$value){
            $ratePackages[$key]['from_date']=$value['in_date'];
            $ratePackages[$key]['to_date']=$value['in_date'];
            $ratePackages[$key]['list_id']=$value['id'];
            if($oldRatePackages!=false and $value['rate_plan_id']==$oldRatePackages['rate_plan_id'] and $value['total']==$oldRatePackages['total'] and ($value['stop_sell']==$oldRatePackages['stop_sell']) and ($value['extra_adult']==$oldRatePackages['extra_adult']) and date('d/m/Y',(Date_Time::to_time($oldRatePackages['in_date'])+86400))==$value['in_date']){
                $ratePackages[$key]['from_date']=$oldRatePackages['from_date'];
                $ratePackages[$key]['list_id']=$oldRatePackages['list_id'].=','.$value['id'].'';
                $value['from_date']=$oldRatePackages['from_date'];
                unset($ratePackages[$oldRatePackages['id']]);
            }
            $oldRatePackages=$ratePackages[$key];
        }
        return $ratePackages;
    }
    function getBooking(){
        $hls=new HLS;
        $result=$hls->getBookings();
        if($result){
            $notificationId=array();
            foreach($result as $key=>$value){
                if($value['NotificationType']=='New'){
                    $reservation=array();
                    $reservation['notification']='New';
                    $reservation['status']='MODIFIED';
                    $reservation['booking_id']=$value['BookingId'];
                    $reservation['customer_id']=DB::fetch('select newway_customer_id as id from HOTELLINK_CHANNEL where name=\''.$value['BookingSource']['Name'].'\'','id');
                    $reservation['title']=$value['Guests']['Title'];
                    $reservation['first_name']=$value['Guests']['FirstName'];
                    $reservation['last_name']=$value['Guests']['LastName'];
                    $reservation['email']=$value['Guests']['Email'];
                    $reservation['phone']=$value['Guests']['Phone'];
                    $reservation['postal_code']=$value['Guests']['PostalCode'];
                    if($value['Payments']){
                        if($value['Payments'][0]['Method']=='OTA Payment'){
                            $reservation['payment_method']='OTA Payment';
                        }else{
                            $reservation['payment_method']='Guest Payment';
                        }
                    }else{
                            $reservation['payment_method']='Guest Payment';
                    }
                    $checkin=explode('-',$value['CheckIn']);
                    $checkout=explode('-',$value['CheckOut']);
                    $booking_date=explode('-',$value['BookingDate']);
                    $reservation['currency']=$value['CurrencyISO'];
                    $reservation['check_in']=Date_Time::to_orc_date($checkin[2].'/'.$checkin[1].'/'.$checkin[0]);
                    $reservation['booking_date']=Date_Time::to_orc_date(substr($booking_date[2],0,2).'/'.$booking_date[1].'/'.$booking_date[0]);
                    $reservation['check_out']=Date_Time::to_orc_date($checkout[2].'/'.$checkout[1].'/'.$checkout[0]);
                    $reservation['service_charge']=$value['ServiceCharge'];
                    $reservation['amount']=$value['Amount'];
                    $reservation_id=DB::insert('HOTELLINK_RESERVATION',$reservation);
                    if($reservation_id){
                        array_push($notificationId,$value['BookingId']);
                        foreach($value['Rooms'] as $k=>$v){
                            $reservation_room['hotellink_reservation_id']=$reservation_id;
                            $reservation_room['rate_plan_id']=$v['RatePlanId'];
                            $reservation_room['room_id']=DB::fetch('select id from room_level where hotellink_room_id=\''.$v['RoomId'].'\'','id');
                            $reservation_room['quantity']=$v['Quantity'];
                            $reservation_room['adult']=$v['ExtraAdults']+$v['Adults'];
                            $reservation_room['child']=$v['ExtraChildren']+$v['Children'];
                            $reservation_room['amount']=$v['Amount'];
                            DB::insert('HOTELLINK_RESERVATION_ROOM',$reservation_room);
                        }
                    }
                }elseif($value['NotificationType']=='Cancelled'){
                    $reservation['notification']='Cancelled';
                    $reservation['status']='MODIFIED';
                    if(DB::update('HOTELLINK_RESERVATION',$reservation,'booking_id=\''.$value['BookingId'].'\'')){
                        array_push($notificationId,$value['BookingId']);
                    }
                }else{
                    $reservation_id=DB::fetch('select id from HOTELLINK_RESERVATION where booking_id='.$value['BookingId'].'','id');
                    DB::delete('HOTELLINK_RESERVATION_ROOM','hotellink_reservation_id='.$reservation_id.'');
                    $reservation=array('');
                    $reservation['notification']='Modified';
                    $reservation['status']='MODIFIED';
                    $reservation['booking_id']=$value['BookingId'];
                    $reservation['customer_id']=DB::fetch('select newway_customer_id as id from HOTELLINK_CHANNEL where name=\''.$value['BookingSource']['Name'].'\'','id');
                    $reservation['title']=$value['Guests']['Title'];
                    $reservation['first_name']=$value['Guests']['FirstName'];
                    $reservation['last_name']=$value['Guests']['LastName'];
                    $reservation['email']=$value['Guests']['Email'];;
                    $reservation['phone']=$value['Guests']['Phone'];
                    $reservation['postal_code']=$value['Guests']['PostalCode'];
                    if($value['Payments']){
                        if($value['Payments'][0]['Method']=='OTA Payment'){
                            $reservation['payment_method']='OTA Payment';
                        }else{
                            $reservation['payment_method']='Guest Payment';
                        }
                    }else{
                            $reservation['payment_method']='Guest Payment';
                    }
                    $checkin=explode('-',$value['CheckIn']);
                    $checkout=explode('-',$value['CheckOut']);
                    $reservation['currency']=$value['CurrencyISO'];
                    $reservation['check_in']=Date_Time::to_orc_date($checkin[0].'/'.$checkin[1].'/'.$checkin[2]);
                    $reservation['check_out']=Date_Time::to_orc_date($checkout[0].'/'.$checkout[1].'/'.$checkout[2]);
                    $reservation['service_charge']=$value['ServiceCharge'];
                    $reservation['amount']=$value['Amount'];
                    if(DB::update('HOTELLINK_RESERVATION',$reservation,'booking_id='.$value['BookingId'].'')){
                        array_push($notificationId,$value['BookingId']);
                    }
                    foreach($value['Rooms'] as $k=>$v){
                        $reservation_room['hotellink_reservation_id']=$reservation_id;
                        $reservation_room['rate_plan_id']=$v['RatePlanId'];
                        $reservation_room['room_id']=DB::fetch('select id from room_level where hotellink_room_id=\''.$v['RoomId'].'\'','id');
                        $reservation_room['quantity']=$v['Quantity'];
                        $reservation_room['adult']=$v['ExtraAdults']+$value['Adults'];
                        $reservation_room['child']=$v['ExtraChildren']+$value['Children'];
                        $reservation_room['amount']=$v['Amount'];
                        DB::insert('HOTELLINK_RESERVATION_ROOM',$reservation_room);
                    }
                }
            }
            $hls->readNotification($notificationId);
        }
    }
    function update(){
        HLSAuto::deleteOldData();
        HLSAuto::updateBookingEngine();
        HLSAuto::updateInventory();
        HLSAuto::getBooking();
        createReservation();
    }
}
HLSAuto::update();
?>