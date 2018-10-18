<?php
class EditRoomAllotmentForm extends Form
{
	function EditRoomAllotmentForm()
	{
		Form::Form('EditRoomAllotmentForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/chart.min.js');
	}
	function on_submit()
	{
	   System::debug($_REQUEST); die;
	   if(Url::get('act')=='SAVE'){
	       $customer_id = Url::get('customer_id');
           $room_level_id = Url::get('room_level_id');
           $start_date = Url::get('start_date');
           $end_date = Url::get('end_date');
           $room_allotment_id = Url::get('id');
           $row = DB::fetch('select room_allotment.*,to_char(room_allotment.start_date,\'DD/MM/YYYY\') as start_date,to_char(room_allotment.end_date,\'DD/MM/YYYY\') as end_date from room_allotment where id='.$room_allotment_id);
           $timeline_old = DB::fetch_all('select room_allotment_avail_rate.*,to_char(room_allotment_avail_rate.in_date,\'DD/MM/YYYY\') as id,to_char(room_allotment_avail_rate.in_date,\'DD/MM/YYYY\') as in_date from room_allotment_avail_rate where room_allotment_avail_rate.room_allotment_id='.$room_allotment_id);
           
           /** check conflict **/
               if(DB::exists('select id from room_allotment where id='.$room_allotment_id.' and customer_id='.$customer_id.' and room_level_id='.$room_level_id.' and start_date<=\''.Date_Time::to_orc_date($end_date).'\' and end_date>=\''.Date_Time::to_orc_date($start_date).'\'')){
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
                        $description_detail .= '<p>Range Availability From Date: '.$valueRange['from_date'].' To Date: '.$valueRange['to_date'].'</p>';
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
                        $description_detail .= '<p>Range Rates From Date: '.$valueRange['from_date'].' To Date: '.$valueRange['to_date'].'</p>';
                        $description_detail .= '<p> '.(isset($valueRange['MON'])?'T2,':'').'  '.(isset($valueRange['TUE'])?'T3,':'').'  '.(isset($valueRange['WED'])?'T4,':'').'  '.(isset($valueRange['THU'])?'T5,':'').'  '.(isset($valueRange['FRI'])?'T6,':'').'  '.(isset($valueRange['SAT'])?'T7,':'').'  '.(isset($valueRange['SUN'])?'CN,':'').' </p>';
                        /** log **/
                    }
               }
               $room_levels = check_availability('','rl.id='.$room_level_id,Date_Time::to_time($start_date),(Date_Time::to_time($end_date)+86400));
               
               foreach($timeline as $key=>$value){
                    if(isset($room_levels[$room_level_id]['day_items'][$key])){
                        
                        $avail_old = isset($timeline_old[date('d/m/Y',$key)])?$timeline_old[date('d/m/Y',$key)]['availability']:0;
                        
                        if(($room_levels[$room_level_id]['day_items'][$key]['number_room_quantity']+$avail_old)<$value['avail']){
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
           
           $record = array(
                            'deposit'=>System::calculate_number(Url::get('deposit')),
                            'start_date'=>Date_Time::to_orc_date($start_date),
                            'end_date'=>Date_Time::to_orc_date($end_date),
                            'last_edit_time'=>time(),
                            'last_editer'=>User::id()
                            );
           DB::update('room_allotment',$record,'id='.$room_allotment_id);
           /** log description **/
           $customer_info = DB::fetch('select code,name from customer where id='.$customer_id);
           $room_level_info = DB::fetch('select brief_name as code,name from room_level where id='.$room_level_id);
           $type_log = 'Edit Allotment ';
           $title_log = 'Edit Allotment Customer '.$customer_info['name'].'( '.$customer_id.' ) in Room Level '.$room_level_info['name'];
           $description_log = '
                                <p>start_date: '.$start_date.'</p>
                                <p>end_date: '.$end_date.'</p>
                                <p>deposit: '.Url::get('deposit').'</p>
                                ';
           /** log **/
           /*
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
           */
           /** save log **/
           System::log($type_log,$title_log,$description_log.$description_detail,$room_allotment_id);
           /** log **/
           Url::redirect('room_allotment',array('cmd'=>'edit','id'=>$room_allotment_id));
           /** end Save Data **/
	   }elseif(Url::get('act')=='DELETE'){
	       
	   }
	}
	function draw()
	{
	   $this->map = array();
       $row = DB::fetch('select room_allotment.*,to_char(room_allotment.start_date,\'DD/MM/YYYY\') as start_date,to_char(room_allotment.end_date,\'DD/MM/YYYY\') as end_date from room_allotment where id='.Url::get('id'));
       $this->map = $row;
       $this->map['rate_default'] = System::display_number($row['rate_default']);
       $this->map['deposit'] = System::display_number(Url::get('deposit')?Url::get('deposit'):$row['deposit']);
       $this->map['deposit_currency'] = $row['deposit_currency'];
       $this->map['start_date'] = Url::get('start_date')?Url::get('start_date'):$row['start_date'];
       $this->map['end_date'] = Url::get('end_date')?Url::get('end_date'):$row['end_date'];
       
       
       $customer = DB::fetch('select name,code from customer where id='.$row['customer_id']);
       $this->map['customer_name'] = $customer['name'];
       $this->map['customer_code'] = $customer['code'];
       
       $room_level = DB::fetch('select brief_name as code,name from room_level where id='.$row['room_level_id']);
       $this->map['room_level_name'] = $room_level['name'];
       $this->map['room_level_code'] = $room_level['code'];
       
       $detail = DB::fetch_all('select room_allotment_avail_rate.*,to_char(room_allotment_avail_rate.in_date,\'DD/MM/YYYY\') as id,to_char(room_allotment_avail_rate.in_date,\'DD/MM/YYYY\') as in_date from room_allotment_avail_rate where room_allotment_avail_rate.room_allotment_id='.Url::get('id'));
       
       $timeline = array();
       $timeline_avail = array();
       $timeline_rate = array();
       $key = 0;
       for($i=Date_Time::to_time($row['start_date']);$i<=Date_Time::to_time($row['end_date']);$i+=86400){
            $timeline[$key] = date('d/m',$i);
            $timeline_avail[$key] = $detail[date('d/m/Y',$i)]['availability'];
            $timeline_rate[$key] = $detail[date('d/m/Y',$i)]['rate'];
            $key++;
       }
       $this->map['timeline'] = json_encode($timeline);
       $this->map['timeline_avail'] = json_encode($timeline_avail);
       $this->map['timeline_rate'] = json_encode($timeline_rate);
       
       $this->parse_layout('edit',$this->map);
	}
}
?>
