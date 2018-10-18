<?php

/**
 * @author Kieucv
 * @copyright TCV 2018
 */
chdir("../../../../../../../");
require_once('packages/core/includes/system/config.php');
require_once('packages/hotel/packages/hotellink/modules/HotelLinkDashBoard/hls.php');
if(Url::get('type')=='getRoom'){
    $rooms=array();
    $hls=new HLS;
    $rooms['newwayRooms']=DB::fetch_all('select * from room_level where brief_name !=\'PA\'');
    $rooms['hlsRooms']=$hls->getHotelRooms();
    echo json_encode($rooms);
}elseif(Url::get('type')=='getLayout'){
    $booking=DB::fetch_all('select 
                            hotellink_reservation.id,
                            hotellink_reservation.first_name,
                            hotellink_reservation.last_name,
                            hotellink_reservation.customer_id,
                            hotellink_reservation.booking_id,
                            to_char(hotellink_reservation.check_in,\'DD/MM/YYYY\') as check_in,
                            to_char(hotellink_reservation.check_out,\'DD/MM/YYYY\') as check_out,
                            reservation.id as resservation_id
                            from hotellink_reservation
                            inner join reservation on hotellink_reservation.id=reservation.hotellink_reservation_id
                            where hotellink_reservation.booking_date=\''.Date_Time::to_orc_date(Url::get('date')).'\'');
    echo json_encode($booking);
}elseif(Url::get('type')=='updateRoom'){
    $availability=0;
    $price=0;
    foreach(Url::get('data') as $key=>$value){
        foreach(Url::get('availability') as $key1=>$value1){
            $id=explode('_',$value1['name']);
            if($id[1]==$value['name']){
                $availability=$value1['value'];
            }
        }
        DB::update('room_level',array('hotellink_room_id'=>$value['value']),'id='.$value['name'].'');
    }
}elseif(Url::get('type')=='getCustomer'){
    $rooms['newwayCustomer']=DB::fetch_all('select * from customer');
    $rooms['hlsChannel']=DB::fetch_all('select * from hotellink_channel');
    echo json_encode($rooms);
}elseif(Url::get('type')=='updateCustomer'){
    foreach(Url::get('data') as $key=>$value){
        DB::update('hotellink_channel',array('newway_customer_id'=>$value['value']),'id='.$value['name'].'');     
    }
}
elseif(Url::get('type')=='availability'){
    $day=1;
    $max_date=0;
    $month=0;
    $year=0;
    if(Url::get('year')){
        if(Url::get('year')==date('Y') and Url::get('month')==date('n')){
            $day=date('j');
            $month=Url::get('month');
            $year=Url::get('year');
        }else{
            $day=1;
            $month=Url::get('month');
            $year=Url::get('year');
        }
        $max_date=cal_days_in_month(CAL_GREGORIAN,Url::get('month'),Url::get('year'));
    }else{
        $day=date('j');
        $month=date('n');
        $year=date('Y');
        $max_date=cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));;
    }
    for($i=$day;$i<=$max_date;$i++){
        $result['date'][$i]['date']=$i;
        $result['date'][$i]['day_of_week']=date('D',mktime(10,10,10,$month,$i,$year));
        $result['date'][$i]['class']='not-weekend';
        $result['date'][$i]['class_th']='not-weekend';
        if($result['date'][$i]['day_of_week']=='Sat' or $result['date'][$i]['day_of_week']=='Sun'){
            $result['date'][$i]['class']='weekend';
            $result['date'][$i]['class_th']='weekend-th';
        }
    }
    $result['month_option']='';
    $selected='';
    for($i=date('n');$i<=12;$i++){
        if($month==$i and $year==date('Y')){
            $selected='selected';
        }else{
            $selected='';
        }
        $result['month_option'].='<option value='.$i.'-'.date('Y').' '.$selected.'>'.$i.'-'.date('Y').'</option>';
    }
    $selected='';
    for($j=1;$j<=12;$j++){
        if($month==$j and $year==(date('Y')+1)){
            $selected='selected';
        }else{
            $selected='';
        }
        $result['month_option'].='<option value='.$j.'-'.(date('Y')+1).' '.$selected.'>'.$j.'-'.(date('Y')+1).'</option>';
    }
    $result['newwayRooms']=DB::fetch_all('select * from room_level where hotellink_room_id is not null');
    $result['month']=$month;
    $result['year']=$year;
    $availability=DB::fetch_all('select * from hotellink_availability where in_date>=\''.Date_Time::to_orc_date($day.'/'.$month.'/'.$year).'\' and in_date<=\''.Date_Time::to_orc_date($max_date.'/'.$month.'/'.$year).'\'');
    $result['availability']=array();
    foreach($availability as $key=>$value){
        $date=explode('-',$value['in_date']);
        $result['availability'][intval($date[0]).'_'.$value['hotellink_room_id']]=$value['total']?$value['total']:'';
    }
    echo json_encode($result);
}elseif(Url::get('type')=='updateAvailability'){
    if(Url::get('data')){
        $month=0;
        $year=0;
        foreach(Url::get('data') as $key=>$value){
            $name=explode('_',$value['name']);
           if($id=DB::fetch('select id from hotellink_availability where hotellink_room_id=\''.$name[0].'\' and in_date =\''.Date_Time::to_orc_date($name[1].'/'.$name[2].'/'.$name[3]).'\'','id')){
                DB::update('hotellink_availability',array('total'=>$value['value'],'status'=>'MODIFIED'),'id='.$id.'');
           }else{
                DB::insert('hotellink_availability',array('hotellink_room_id'=>$name[0],'status'=>'MODIFIED','in_date'=>Date_Time::to_orc_date($name[1].'/'.$name[2].'/'.$name[3]),'total'=>$value['value']));
            }
        }
    }
}elseif(Url::get('type')=='rates'){
    $day=1;
    $max_date=0;
    $month=0;
    $year=0;
    if(Url::get('year')){
        if(Url::get('year')==date('Y') and Url::get('month')==date('n')){
            $day=date('j');
            $month=Url::get('month');
            $year=Url::get('year');
        }else{
            $day=1;
            $month=Url::get('month');
            $year=Url::get('year');
        }
        $max_date=cal_days_in_month(CAL_GREGORIAN,Url::get('month'),Url::get('year'));
        //$max_date=date('t',mktime(10,10,10,Url::get('month'),$day,Url::get('year')));
    }else{
        $day=date('j');
        $month=date('n');
        $year=date('Y');
        //$max_date=date('t',mktime(10,10,10,date('m'),$day,date('Y')));
        $max_date=cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
    }
    for($i=$day;$i<=$max_date;$i++){
        $result['date'][$i]['date']=$i;
        $result['date'][$i]['day_of_week']=date('D',mktime(10,10,10,$month,$i,$year));
        $result['date'][$i]['class']='not-weekend';
        $result['date'][$i]['class_th']='not-weekend';
        if($result['date'][$i]['day_of_week']=='Sat' or $result['date'][$i]['day_of_week']=='Sun'){
            $result['date'][$i]['class']='weekend';
            $result['date'][$i]['class_th']='weekend-th';
        }
    }
    $result['month_option']='';
    $selected='';
    for($i=date('n');$i<=12;$i++){
        if($month==$i and $year==date('Y')){
            $selected='selected';
        }else{
            $selected='';
        }
        $result['month_option'].='<option value='.$i.'-'.date('Y').' '.$selected.'>'.$i.'-'.date('Y').'</option>';
    }
    $selected='';
    for($j=1;$j<=12;$j++){
        if($month==$j and $year==(date('Y')+1)){
            $selected='selected';
        }else{
            $selected='';
        }
        $result['month_option'].='<option value='.$j.'-'.(date('Y')+1).' '.$selected.'>'.$j.'-'.(date('Y')+1).'</option>';
    }
    $result['newwayRooms']=DB::fetch_all('select * from room_level where hotellink_room_id is not null');
    $rate_plan=DB::fetch_all('select * from hotellink_rate_plan');
    if(Url::get('extra_adult')){
        $rate_plan=DB::fetch_all('select * from hotellink_rate_plan where HOTELLINK_RATE_PLAN.EXTRA_GUESTS_CONFIG=\'ADULT_CHILD\' or HOTELLINK_RATE_PLAN.EXTRA_GUESTS_CONFIG=\'ONLY_ADULT\'');
    }
    foreach($rate_plan as $key=>$value){
        if($value['room_level_id']){
            $result['newwayRooms'][$value['room_level_id']]['plans'][$key]=$value;
        }
    }
    $result['month']=$month;
    $result['year']=$year;
    $rates=DB::fetch_all('select hotellink_rate.id,hotellink_rate.extra_adult,hotellink_rate.stop_sell,hotellink_rate.in_date,hotellink_rate.rate_plan_id,nvl(hotellink_rate.total,0) as total ,hotellink_availability.total as availability from hotellink_rate
                         inner join HOTELLINK_RATE_PLAN on HOTELLINK_RATE_PLAN.id= hotellink_rate.rate_plan_id 
                         inner join hotellink_availability on hotellink_availability.hotellink_room_id=HOTELLINK_RATE_PLAN.hotellink_room_id and hotellink_availability.in_date=hotellink_rate.in_date
                         where hotellink_rate.in_date>=\''.Date_Time::to_orc_date($day.'/'.$month.'/'.$year).'\' and hotellink_rate.in_date<=\''.Date_Time::to_orc_date($max_date.'/'.$month.'/'.$year).'\'');
                         
    $result['rates']=array();
    foreach($rates as $key=>$value){
        $date=explode('-',$value['in_date']);
        $result['rates'][intval($date[0]).'_'.$value['rate_plan_id']]=$value['total']?$value['total']:'';
        $result['availability'][intval($date[0]).'_'.$value['rate_plan_id']]=$value['availability'];
        $result['stop_sell'][intval($date[0]).'_'.$value['rate_plan_id']]=$value['stop_sell'];
        $result['extra_adult'][intval($date[0]).'_'.$value['rate_plan_id']]=$value['extra_adult'];
    }
    echo json_encode($result);
}elseif(Url::get('type')=='updateRates'){
    if(Url::get('data')){
        $month=0;
        $year=0;
        foreach(Url::get('data') as $key=>$value){
            $name=explode('_',$value['name']);
            if($id=DB::fetch('select id from hotellink_rate where rate_plan_id=\''.$name[0].'\' and in_date =\''.Date_Time::to_orc_date($name[1].'/'.$name[2].'/'.$name[3]).'\'','id')){
                DB::update('hotellink_rate',array('total'=>$value['value'],'status'=>'MODIFIED'),'id='.$id.'');
            }else{
                DB::insert('hotellink_rate',array('rate_plan_id'=>$name[0],'in_date'=>Date_Time::to_orc_date($name[1].'/'.$name[2].'/'.$name[3]),'total'=>$value['value']));
            }
        }
    }
}elseif(Url::get('type')=='updateExtraAdult'){
    if(Url::get('data')){
        $month=0;
        $year=0;
        foreach(Url::get('data') as $key=>$value){
            $name=explode('_',$value['name']);
            if($id=DB::fetch('select id from hotellink_rate where rate_plan_id=\''.$name[0].'\' and in_date =\''.Date_Time::to_orc_date($name[1].'/'.$name[2].'/'.$name[3]).'\'','id')){
                DB::update('hotellink_rate',array('extra_adult'=>$value['value'],'status'=>'MODIFIED'),'id='.$id.'');
            }else{
                DB::insert('hotellink_rate',array('rate_plan_id'=>$name[0],'in_date'=>Date_Time::to_orc_date($name[1].'/'.$name[2].'/'.$name[3]),'extra_adult'=>$value['value']));
            }
        }
    }
}
elseif(Url::get('type')=='updateStopSell'){
    if(Url::get('data')){
        $month=0;
        $year=0;
        $cmd=Url::get('cmd')=='on'?1:0;
        foreach(Url::get('data') as $key=>$value){
            $name=explode('_',$value['name']);
            if($id=DB::fetch('select id from hotellink_rate where rate_plan_id=\''.$name[0].'\' and in_date =\''.Date_Time::to_orc_date($name[1].'/'.$name[2].'/'.$name[3]).'\'','id')){
                DB::update('hotellink_rate',array('stop_sell'=>$cmd,'status'=>'MODIFIED'),'id='.$id.'');
            }else{
                DB::insert('hotellink_rate',array('rate_plan_id'=>$name[0],'in_date'=>Date_Time::to_orc_date($name[1].'/'.$name[2].'/'.$name[3]),'stop_sell'=>$cmd));
            }
        }
    }
}
elseif(Url::get('type')=='getRatePlans'){
    //$hls=new HLS;
    $result['ratePlans']=DB::fetch_all('select * from hotellink_rate_plan');
    $result['newwayRooms']=DB::fetch_all('select * from room_level where hotellink_room_id is not null');
    echo json_encode($result);
}elseif(Url::get('type')=='updateRatePlans'){
    $hls=new HLS;
    $ratePlans=$hls->getRatePlans();
    $newwayRooms=DB::fetch_all('select hotellink_room_id as id,id as room_level_id from room_level where hotellink_room_id is not null');
    foreach($ratePlans as $key=>$value){
        foreach($value['RatePlans'] as $key1=>$value1){
            if(!DB::fetch('select id from hotellink_rate_plan where id=\''.$value1['RatePlanId'].'\'') and DB::fetch('select hotellink_room_id as id from room_level where hotellink_room_id=\''.$value['RoomId'].'\'')){
               DB::insert('hotellink_rate_plan',array('id'=>$value1['RatePlanId'],'hotellink_room_id'=>$value['RoomId'],'name'=>$value1['Name'],'room_level_id'=>$newwayRooms[$value1['RoomId']]['room_level_id']));        
            }
            if(DB::fetch('select id from hotellink_rate_plan where id=\''.$value1['RatePlanId'].'\'')){
                DB::update('hotellink_rate_plan',array('extra_guests_config'=>$value1['ExtraGuestsConfig']),'id=\''.$value1['RatePlanId'].'\'');
            }
        }
    }
    foreach(Url::get('rates') as $key=>$value){
        DB::update('hotellink_rate_plan',array('rate'=>$value['value']),'id=\''.$value['name'].'\'');
    }
}
?>