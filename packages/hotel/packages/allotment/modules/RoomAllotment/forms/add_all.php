<?php
class AddAllRoomAllotmentForm extends Form
{
	function AddAllRoomAllotmentForm()
	{
		Form::Form('AddAllRoomAllotmentForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function on_submit()
	{
	   if(Url::get('act')=='SAVE')
       {
	       $customer_id = Url::get('customer_id');
           
           $timeline = array();
	       if(isset($_REQUEST['bulkrangeavail']))
           {
	           foreach($_REQUEST['bulkrangeavail'] as $key=>$value){
	               
                   if(!isset($timeline[$value['room_level_id']])){
                        $timeline[$value['room_level_id']]['id'] =  $value['room_level_id'];
                        $timeline[$value['room_level_id']]['room_level_id'] = $value['room_level_id'];
                        $timeline[$value['room_level_id']]['start_date'] = 0;
                        $timeline[$value['room_level_id']]['end_date'] = 0;
                        $timeline[$value['room_level_id']]['timeline'] = array();
                   }
                   for($i=Date_Time::to_time($value['from_date']);$i<=Date_Time::to_time($value['to_date']);$i+=86400)
                   {
                        $date = getdate($i);
                        $date['weekday'] = strtoupper(substr($date['weekday'],0,3));
                        $in_date = date('d/m/Y',$i);
                        if(isset($value[$date['weekday']]))
                        {
                            if(isset($timeline[$value['room_level_id']]['timeline'][$i]) or DB::exists('select room_allotment_avail_rate.id from room_allotment_avail_rate inner join room_allotment on room_allotment.id=room_allotment_avail_rate.room_allotment_id where room_allotment.room_level_id=\''.$value['room_level_id'].'\' and room_allotment.customer_id='.$customer_id.' and room_allotment_avail_rate.in_date=\''.Date_Time::to_orc_date($in_date).'\''))
                            {
                                $this->error('conflict','Allotment conflict',false);
                                break;
                            }
                            else
                            {
                                $timeline[$value['room_level_id']]['timeline'][$i]['id'] = $i;
                                $timeline[$value['room_level_id']]['timeline'][$i]['time'] = $i;
                                $timeline[$value['room_level_id']]['timeline'][$i]['in_date'] = date('d/m/Y',$i);
                                $timeline[$value['room_level_id']]['timeline'][$i]['avail'] = System::calculate_number($value['availability']);
                                $timeline[$value['room_level_id']]['timeline'][$i]['rate'] = System::calculate_number($value['rate']);
                            }
                        }
                   }
                   
                   if(Date_Time::to_time($value['to_date'])>=$timeline[$value['room_level_id']]['end_date'])
                        $timeline[$value['room_level_id']]['end_date'] = Date_Time::to_time($value['to_date']);
                   if($timeline[$value['room_level_id']]['start_date']==0 or $timeline[$value['room_level_id']]['start_date']>=Date_Time::to_time($value['from_date']))
                        $timeline[$value['room_level_id']]['start_date'] = Date_Time::to_time($value['from_date']);
                   
                   if($this->is_error())
                        break;
	           }
               if($this->is_error()){
                	return;
               }
               require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
               foreach($timeline as $keyTime=>$valueTime){
                    $room_levels = check_availability('','rl.id='.$valueTime['room_level_id'],$valueTime['start_date'],($valueTime['end_date']+86400));
                    foreach($valueTime['timeline'] as $k=>$v){
                        if(isset($room_levels[$valueTime['room_level_id']]['day_items'][$k]))
                        {
                            if($room_levels[$valueTime['room_level_id']]['day_items'][$k]['number_room_quantity']<$v['avail'])
                            {
                                $this->error('overbook','s? lu?ng phòng tr?ng không d? cho ngày '.$v['in_date'],false);
                                break;
                            }
                        }
                        else
                        {
                            $this->error('overbook','s? lu?ng phòng tr?ng không d? cho ngày '.$v['in_date'],false);
                            break;
                        }
                    }
               }
               if($this->is_error()){
                	return;
               }
               $currency_id = (HOTEL_CURRENCY == 'VND')?'USD':'VND';
               $exchange_rate = DB::fetch('select id,exchange from currency where id=\''.$currency_id.'\'','exchange');
               foreach($timeline as $keyTime=>$valueTime){
                    $start_date = date('d/m/Y',$valueTime['start_date']);
                    $end_date = date('d/m/Y',$valueTime['end_date']);
                    $room_level_id = $valueTime['room_level_id'];
                    
                    $record = array(
                                    'room_level_id'=>$room_level_id,
                                    'customer_id'=>$customer_id,
                                    'start_date'=>Date_Time::to_orc_date($start_date),
                                    'end_date'=>Date_Time::to_orc_date($end_date),
                                    'portal_id'=>PORTAL_ID,
                                    'availability_default'=>0,
                                    'rate_default'=>0,
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
                    foreach($valueTime['timeline'] as $k=>$v){
                        $array = array(
                                        'in_date'=>Date_Time::to_orc_date($v['in_date']),
                                        'room_allotment_id'=>$room_allotment_id,
                                        'availability'=>$v['avail'],
                                        'rate'=>$v['rate'],
                                        'create_time'=>time(),
                                        'user_id'=>User::id(),
                                        'last_edit_time'=>time(),
                                        'last_editer'=>User::id()
                                        );
                        DB::insert('room_allotment_avail_rate',$array);
                    }
               }
	       }
           Url::redirect('room_allotment');
	   }
	}
	function draw()
	{
	   $this->map = array();
       
       $this->map['deposit_currency'] = HOTEL_CURRENCY;
       
       $customer = DB::fetch('select name,code from customer where id='.Url::get('customer_id'));
       $this->map['customer_name'] = $customer['name'];
       $this->map['customer_code'] = $customer['code'];
       
       $room_level = DB::fetch_all('select room_level.*,room_level.brief_name as code from room_level where room_level.portal_id=\''.PORTAL_ID.'\'');
       $this->map['room_level_option'] = '<option value="">'.Portal::language('select').' '.Portal::language('room_level').'</option>';
       foreach($room_level as $key=>$value){
            $this->map['room_level_option'] .= '<option value="'.$value['id'].'">'.$value['code'].' - '.$value['name'].'</option>';
       }
       $this->parse_layout('add_all',$this->map);
	}
}
?>
