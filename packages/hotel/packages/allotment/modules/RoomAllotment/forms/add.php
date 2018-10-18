<?php
class AddRoomAllotmentForm extends Form
{
	function AddRoomAllotmentForm()
	{
		Form::Form('AddRoomAllotmentForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function on_submit()
	{
	   //this->error(name,status,false);
	   if(Url::get('act')=='SAVE'){
	       $customer_id = Url::get('customer_id');
           $room_level_id = Url::get('room_level_id');
           $start_date = Url::get('start_date');
           $end_date = Url::get('end_date');
           
           /** check conflict **/
           if(DB::exists('select id from room_allotment where customer_id='.$customer_id.' and room_level_id='.$room_level_id.' and start_date<=\''.Date_Time::to_orc_date($end_date).'\' and end_date>=\''.Date_Time::to_orc_date($start_date).'\'')){
                $this->error('conflict','Allotment conflict',false);
           }
           if($this->is_error()){
            	return;
           }
           /** end check conflict **/
           /** check overbook **/
           require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
           $timeline = array();
           /** log info **/
            $description_detail = '';
            /** log **/
           for($i=Date_Time::to_time($start_date);$i<=Date_Time::to_time($end_date);$i+=86400){
                $timeline[$i]['id'] = $i;
                $timeline[$i]['in_date'] = date('d/m/Y',$i);
                $timeline[$i]['avail'] = System::calculate_number(Url::get('availability_default'));
                $timeline[$i]['rate'] = System::calculate_number(Url::get('rate_default'));
           }
           if(isset($_REQUEST['bulkrangeavail'])){
                /** log info **/
                $description_detail .= '<hr/>';
                /** log **/
                foreach($_REQUEST['bulkrangeavail'] as $keyRange => $valueRange){
                    for($i=Date_Time::to_time($valueRange['from_date']);$i<=Date_Time::to_time($valueRange['to_date']);$i+=86400){
                        if(isset($timeline[$i])){
                            $date = getdate($i);
                            $date['weekday'] = strtoupper(substr($date['weekday'],0,3));
                            if( isset($valueRange[$date['weekday']]) ){
                                $timeline[$i]['avail'] = System::calculate_number($valueRange['availability']);
                            }
                        }
                    }
                    /** log info **/
                    $description_detail .= '<p>Range Availability From Date: '.$valueRange['from_date'].' To Date: '.$valueRange['to_date'].' - Avail: '.$valueRange['availability'].'</p>';
                    $description_detail .= '<p> '.(isset($valueRange['MON'])?'T2,':'').'  '.(isset($valueRange['TUE'])?'T3,':'').'  '.(isset($valueRange['WED'])?'T4,':'').'  '.(isset($valueRange['THU'])?'T5,':'').'  '.(isset($valueRange['FRI'])?'T6,':'').'  '.(isset($valueRange['SAT'])?'T7,':'').'  '.(isset($valueRange['SUN'])?'CN,':'').' </p>';
                    /** log **/
                }
           }
           if(isset($_REQUEST['bulkrangerate'])){
                /** log info **/
                $description_detail .= '<hr/>';
                /** log **/
                foreach($_REQUEST['bulkrangerate'] as $keyRange => $valueRange){
                    for($i=Date_Time::to_time($valueRange['from_date']);$i<=Date_Time::to_time($valueRange['to_date']);$i+=86400){
                        if(isset($timeline[$i])){
                            $date = getdate($i);
                            $date['weekday'] = strtoupper(substr($date['weekday'],0,3));
                            if( isset($valueRange[$date['weekday']]) ){
                                $timeline[$i]['rate'] = System::calculate_number($valueRange['rate']);
                            }
                        }
                    }
                    /** log info **/
                    $description_detail .= '<p>Range Rates From Date: '.$valueRange['from_date'].' To Date: '.$valueRange['to_date'].' - Rate: '.$valueRange['rate'].'</p>';
                    $description_detail .= '<p> '.(isset($valueRange['MON'])?'T2,':'').'  '.(isset($valueRange['TUE'])?'T3,':'').'  '.(isset($valueRange['WED'])?'T4,':'').'  '.(isset($valueRange['THU'])?'T5,':'').'  '.(isset($valueRange['FRI'])?'T6,':'').'  '.(isset($valueRange['SAT'])?'T7,':'').'  '.(isset($valueRange['SUN'])?'CN,':'').' </p>';
                    /** log **/
                }
           }
           $room_levels = check_availability('','rl.id='.$room_level_id,Date_Time::to_time($start_date),(Date_Time::to_time($end_date)+86400));
           //System::debug($room_levels);
           //System::debug($timeline); 
           foreach($timeline as $key=>$value){
                if(isset($room_levels[$room_level_id]['day_items'][$key])){
                    if($room_levels[$room_level_id]['day_items'][$key]['number_room_quantity']<$value['avail']){
                        $this->error('overbook','số lượng phòng trống không đủ cho ngày '.$value['in_date'],false);
                        break;
                    }
                }else{
                    $this->error('overbook','số lượng phòng trống không đủ cho ngày '.$value['in_date'],false);
                    break;
                }
           }
           if($this->is_error()){
            	return;
           }
           /** end check overbook **/
           
           /** Save data **/
           $currency_id = (HOTEL_CURRENCY == 'VND')?'USD':'VND';
           $exchange_rate = DB::fetch('select id,exchange from currency where id=\''.$currency_id.'\'','exchange');
           
           $record = array(
                            'room_level_id'=>$room_level_id,
                            'customer_id'=>$customer_id,
                            'deposit'=>System::calculate_number(Url::get('deposit')),
                            'start_date'=>Date_Time::to_orc_date($start_date),
                            'end_date'=>Date_Time::to_orc_date($end_date),
                            'portal_id'=>PORTAL_ID,
                            'availability_default'=>System::calculate_number(Url::get('availability_default')),
                            'rate_default'=>System::calculate_number(Url::get('rate_default')),
                            'create_time'=>time(),
                            'user_id'=>User::id(),
                            'last_edit_time'=>time(),
                            'last_editer'=>User::id(),
                            'deposit_currency'=>HOTEL_CURRENCY,
                            'deposit_exchange_rate'=>$exchange_rate,
                            'auto_reset_avail'=>isset($_REQUEST['auto_reset_avail'])?1:0,
                            'day_reset_avail'=>System::calculate_number(Url::get('day_reset_avail'))
                            );
           $room_allotment_id = DB::insert('room_allotment',$record);
           /** log description **/
           $customer_info = DB::fetch('select code,name from customer where id='.$customer_id);
           $room_level_info = DB::fetch('select brief_name as code,name from room_level where id='.$room_level_id);
           $type_log = 'Add Allotment #'.$room_allotment_id;
           $title_log = 'Add Allotment #'.$room_allotment_id.' Customer '.$customer_info['name'].'( '.$customer_id.' ) in Room Level '.$room_level_info['name'];
           $description_log = '
                                <p>start_date: '.$start_date.'</p>
                                <p>end_date: '.$end_date.'</p>
                                <p>availability_default: '.Url::get('availability_default').'</p>
                                <p>rate_default: '.Url::get('rate_default').'</p>
                                <p>deposit: '.Url::get('deposit').'</p>
                                ';
           /** log **/
           foreach($timeline as $key=>$value){
                $array = array(
                                'in_date'=>Date_Time::to_orc_date($value['in_date']),
                                'room_allotment_id'=>$room_allotment_id,
                                'availability'=>$value['avail'],
                                'rate'=>$value['rate'],
                                'create_time'=>time(),
                                'user_id'=>User::id(),
                                'last_edit_time'=>time(),
                                'last_editer'=>User::id()
                                );
                DB::insert('room_allotment_avail_rate',$array);
                
           }
           
           /** save log **/
           System::log($type_log,$title_log,$description_log.$description_detail,$room_allotment_id);
           /** log **/
           Url::redirect('room_allotment',array('start_date'=>$start_date));
           /** end Save Data **/
	   }
	}
	function draw()
	{
	   $this->map = array();
       
       $this->map['deposit_currency'] = HOTEL_CURRENCY;
       
       $customer = DB::fetch('select name,code from customer where id='.Url::get('customer_id'));
       $this->map['customer_name'] = $customer['name'];
       $this->map['customer_code'] = $customer['code'];
       
       $room_level = DB::fetch('select brief_name as code,name from room_level where id='.Url::get('room_level_id'));
       $this->map['room_level_name'] = $room_level['name'];
       $this->map['room_level_code'] = $room_level['code'];
       
       $this->parse_layout('add',$this->map);
	}
}
?>
