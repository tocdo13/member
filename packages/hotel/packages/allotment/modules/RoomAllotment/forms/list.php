<?php
class ListRoomAllotmentForm extends Form
{
	function ListRoomAllotmentForm()
	{
		Form::Form('ListRoomAllotmentForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function on_submit()
	{
	   //System::debug($_REQUEST); die;
       if(Url::get('act')=='SAVE_ALLOTMENT'){
            if(isset($_REQUEST['allotment'])){
                require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
                /** check data **/
                foreach($_REQUEST['allotment'] as $key=>$value){
                    $room_allotment_id = $key;
                    if($allotment = DB::fetch('select room_allotment.*,to_char(room_allotment.start_date,\'DD/MM/YYYY\') as start_date,to_char(room_allotment.end_date,\'DD/MM/YYYY\') as end_date from room_allotment where id='.$room_allotment_id)){
                        
                    }else{
                        $this->error('Error','Allotment không tồn tại',false);
                        break;
                    }
                    $room_levels = check_availability('','rl.id='.$allotment['room_level_id'],Date_Time::to_time($allotment['start_date']),(Date_Time::to_time($allotment['end_date'])+86400));
                    $timeline_old = DB::fetch_all('select room_allotment_avail_rate.*,to_char(room_allotment_avail_rate.in_date,\'DD/MM/YYYY\') as id from room_allotment_avail_rate where room_allotment_id='.$room_allotment_id);
                    //System::debug($room_levels);
                    //System::debug($timeline_old);
                    foreach($value['timeline'] as $keyTime=>$valueTime){
                        if(isset($room_levels[$allotment['room_level_id']]['day_items'][$keyTime]) and isset($timeline_old[date('d/m/Y',$keyTime)])){
                            if(($room_levels[$allotment['room_level_id']]['day_items'][$keyTime]['number_room_quantity']+$timeline_old[date('d/m/Y',$keyTime)]['availability'])<$valueTime['avail']){
                                $this->error('overbook','số lượng phòng trống không đủ cho ngày '.date('d/m/Y',$keyTime),false);
                                break;
                            }
                        }else{
                            $this->error('overbook','số lượng phòng trống không đủ cho ngày '.date('d/m/Y',$keyTime),false);
                            break;
                        }
                    }
                    if($this->is_error()){
                    	break;
                    }
                }
                if($this->is_error()){
                	return;
                }
                /** check data **/
                
                /** save data **/
                foreach($_REQUEST['allotment'] as $key=>$value){
                    $room_allotment_id = $key;
                    $type_log = 'Edit Allotment #'.$room_allotment_id;
                    $title_log = 'Edit Allotment #'.$room_allotment_id;
                    $description_log = '';
                    foreach($value['timeline'] as $keyTime=>$valueTime){
                        if($room_allotment_detail_id = DB::fetch('select id from room_allotment_avail_rate where room_allotment_id='.$room_allotment_id.' and in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$keyTime)).'\'','id'))
                        {
                            if(isset($valueTime['confirm']))
                                $confirm = 1;
                            else
                                $confirm = 0;
                            DB::update('room_allotment_avail_rate',array('availability'=>System::calculate_number($valueTime['avail']),'rate'=>System::calculate_number($valueTime['rate']),'confirm'=>$confirm),'id='.$room_allotment_detail_id);
                            $description_log .= '<p>Edit Rate + Availability Date: '.date('d/m/Y',$keyTime).' - Rate: '.$valueTime['rate'].' - Avail: '.$valueTime['avail'].'</p>';
                        }
                    }
                    /** save log **/
                   System::log($type_log,$title_log,$description_log,$room_allotment_id);
                   /** log **/
                }
                /** save data **/
                Url::redirect('room_allotment',array('start_date'=>Url::get('start_date'),'end_date'=>Url::get('end_date'),'customer_id'=>Url::get('customer_id')));
            }
       }
       elseif(Url::get('act')=='CREATE_BOOKING'){
            $room_level = DB::fetch_all('select * from room_level where is_virtual=0 or is_virtual is null and portal_id=\''.PORTAL_ID.'\'');
            $currency_id = (HOTEL_CURRENCY == 'VND')?'USD':'VND';
            $exchange_rate = DB::fetch('select id,exchange from currency where id=\''.$currency_id.'\'','exchange');
            $customer_id = false;
            if(isset($_REQUEST['booking_quantity'])){
                foreach($_REQUEST['booking_quantity'] as $key=>$value){
                    foreach($value['channel'] as $keyChannel=>$valueChannel){
                        if($valueChannel['quantity']!='' and System::calculate_number($valueChannel['quantity'])>0){
                            $customer_id = $keyChannel;
                            break;
                        }
                    }
                    if($customer_id)
                        break;
                }
                
                if($customer_id){
                    $customer_name = DB::fetch('select name from customer where id='.$customer_id,'name');
                    $room_levels = '';
                    foreach($room_level as $key=>$value){
                        if($room_levels!='')
                            $room_levels .= '|';
                        $quantity = '';
                        if(isset($_REQUEST['booking_quantity'][$key]['channel'][$customer_id]) and $_REQUEST['booking_quantity'][$key]['channel'][$customer_id]['quantity']!='' and System::calculate_number($_REQUEST['booking_quantity'][$key]['channel'][$customer_id]['quantity'])>0){
                            $quantity = System::calculate_number($_REQUEST['booking_quantity'][$key]['channel'][$customer_id]['quantity']);
                        }
                        $room_levels .= $key.','.$quantity.','.$value['price'].','.round($value['price']/$exchange_rate,2).','.$value['num_people'].','.',';
                    }
                    if($room_levels!=''){
                        $status = 'BOOKED';
        				$confirm = 0;
                        Url::redirect('reservation',array('cmd'=>'add','customer_id'=>$customer_id,'customer_name'=>$customer_name,'room_levels'=>$room_levels,'arrival_time'=>Url::get('arrival_time'),'time_in'=>CHECK_IN_TIME,'departure_time'=>Url::get('departure_time'),'time_out'=>CHECK_OUT_TIME,'status'=>$status,'confirm'=>$confirm,'allotment'=>1));
                    }
                }
            }
            
       }
       
	}
	function draw()
	{
	   $this->map = array();
       
       $this->map['customer_id_list'] = array(''=>Portal::language('all'))+String::get_list(DB::fetch_all('
                                                        select 
                                                            room_allotment.customer_id as id,
                                                            customer.name
                                                        from 
                                                            room_allotment 
                                                            inner join customer on customer.id=room_allotment.customer_id
                                                        where 
                                                            room_allotment.portal_id=\''.PORTAL_ID.'\' 
                                                        order by customer.name'));
       
       /** find **/
       $cond = '1=1';
       $cond .= Url::get('customer_id')?' and customer.id='.Url::get('customer_id'):'';
       $this->map['customer_id'] = $_REQUEST['customer_id'] = Url::get('customer_id')?Url::get('customer_id'):'';
       /** find **/
       
       $this->map['view_action_list'] = array('EDIT'=>'Edit Room Allotment','BOOKING'=>'Create Booking'); //'VIEW'=>'Views',
       $this->map['view_action'] = Url::get('view_action')?Url::get('view_action'):'EDIT';
       
       $this->map['start_date'] = $_REQUEST['start_date'] = Url::get('start_date')?Url::get('start_date'):date('d/m/Y');
       $this->map['end_date'] = $_REQUEST['end_date'] = date('d/m/Y',(Date_Time::to_time($this->map['start_date'])+15*86400)); //Url::get('end_date')?Url::get('end_date'):date('d/m/Y',(time()+15*86400));
       $this->map['count_date'] = 15;//((Date_Time::to_time($this->map['end_date']) - Date_Time::to_time($this->map['start_date']))/86400)+1;
       
       $timeline = array();
       for($i=Date_Time::to_time($this->map['start_date']);$i<=Date_Time::to_time($this->map['end_date']);$i+=86400){
            $timeline[$i] = getdate($i);
            $timeline[$i]['id'] = $i;
            if(strtoupper($timeline[$i]['month'])!='JULY'){
                $timeline[$i]['month'] = substr($timeline[$i]['month'],0,3);
            }
            $timeline[$i]['weekday'] = substr($timeline[$i]['weekday'],0,3);
            $timeline[$i]['background'] = '#FFFFFF';
            if(strtoupper($timeline[$i]['weekday'])=='SAT' or strtoupper($timeline[$i]['weekday'])=='SUN'){
                $timeline[$i]['background'] = 'rgba(255, 205, 86, 0.3)';
            }
            
            $timeline[$i]['in_date'] = date('d/m/Y',$i);
            $timeline[$i]['is_allotment'] = 0;
            
            $timeline[$i]['avail'] = '';
            $timeline[$i]['rate'] = '';
            $timeline[$i]['confirm'] = 0;
       }
       
       require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
	   $room_level_avail = check_availability('',' 1>0',Date_Time::to_time($this->map['start_date']),(Date_Time::to_time($this->map['end_date'])+86400));
       //System::debug($room_levels);
       $room_level = DB::fetch_all('select id,brief_name as code,name from room_level where is_virtual=0 or is_virtual is null and portal_id=\''.PORTAL_ID.'\' order by name');
       foreach($room_level as $key=>$value){
            $room_level[$key]['timeline'] = $timeline;
            foreach($timeline as $keyTime=>$valueTime){
                if(isset($room_level_avail[$key]['day_items'][$keyTime]))
                    $room_level[$key]['timeline'][$keyTime]['avail'] = $room_level_avail[$key]['day_items'][$keyTime]['number_room_quantity'];
            }
       }
       $customer = DB::fetch_all('select customer.id,customer.name,customer.code,customer_group.name as group_name from customer inner join customer_group on customer.group_id=customer_group.id where portal_id=\''.PORTAL_ID.'\' order by customer.name');
       
       $room_allotment = DB::fetch_all('
                                        select 
                                            room_allotment.*
                                        from 
                                            room_allotment 
                                            inner join customer on customer.id=room_allotment.customer_id
                                        where 
                                            '.$cond.'
                                            and room_allotment.portal_id=\''.PORTAL_ID.'\' 
                                            and room_allotment.start_date<=\''.Date_Time::to_orc_date($this->map['end_date']).'\'
                                            and room_allotment.end_date>=\''.Date_Time::to_orc_date($this->map['start_date']).'\'
                                        order by room_allotment.room_level_id,room_allotment.id');
       
       foreach($room_allotment as $key=>$value){
            if(isset($room_level[$value['room_level_id']]) and isset($customer[$value['customer_id']])){
                $room_level[$value['room_level_id']]['channel_list'][$value['customer_id']]['id'] = $value['customer_id'];
                $room_level[$value['room_level_id']]['channel_list'][$value['customer_id']]['code'] = $customer[$value['customer_id']]['code'];
                $room_level[$value['room_level_id']]['channel_list'][$value['customer_id']]['name'] = $customer[$value['customer_id']]['name'];
                $room_level[$value['room_level_id']]['channel_list'][$value['customer_id']]['timeline'] = $timeline;
            }
       }
       
       $room_allotment_avail = DB::fetch_all('
                                        select 
                                            room_allotment_avail_rate.*,
                                            to_char(room_allotment_avail_rate.in_date,\'DD/MM/YYYY\') as in_date,
                                            room_allotment.room_level_id,
                                            room_allotment.customer_id
                                        from 
                                            room_allotment_avail_rate 
                                            inner join room_allotment on room_allotment.id=room_allotment_avail_rate.room_allotment_id
                                            inner join customer on customer.id=room_allotment.customer_id
                                        where 
                                            '.$cond.'
                                            and room_allotment.portal_id=\''.PORTAL_ID.'\' 
                                            and room_allotment_avail_rate.in_date>=\''.Date_Time::to_orc_date($this->map['start_date']).'\'
                                            and room_allotment_avail_rate.in_date<=\''.Date_Time::to_orc_date($this->map['end_date']).'\'
                                        ');
       foreach($room_allotment_avail as $key=>$value){
            $time = Date_Time::to_time($value['in_date']);
            if(isset($room_level[$value['room_level_id']]['channel_list'][$value['customer_id']]['timeline'][$time])){
                $room_level[$value['room_level_id']]['channel_list'][$value['customer_id']]['timeline'][$time]['is_allotment'] = $value['room_allotment_id'];
                $room_level[$value['room_level_id']]['channel_list'][$value['customer_id']]['timeline'][$time]['avail'] = $value['availability'];
                $room_level[$value['room_level_id']]['channel_list'][$value['customer_id']]['timeline'][$time]['rate'] = System::display_number($value['rate']);
                $room_level[$value['room_level_id']]['channel_list'][$value['customer_id']]['timeline'][$time]['confirm'] = $value['confirm'];
            }
       }
       
       /** xet timeline **/
       $this->map['customerjs'] = String::array2js($customer);
       $this->map['customer'] = $customer;
       $this->map['timeline'] = $timeline;
       $this->map['items'] = $room_level;
       
       /** check view **/
       $check_view = 0;
       if(isset($_REQUEST['FuncView']) or isset($_REQUEST['FuncEditAllotment']) or isset($_REQUEST['FuncCreateBooking'])){
            $check_view = 1;
       }
       if($check_view==0)
            $_REQUEST['FuncView'] = '';
       
       /** auto reset avail message **/
       $auto_reset = DB::fetch_all('
                                    select
                                        room_allotment_auto_reset.id,
                                        to_char(room_allotment_auto_reset.in_date,\'DD/MM/YYYY\') as in_date,
                                        room_allotment_auto_reset.time,
                                        room_allotment_auto_reset.avail,
                                        customer.name as customer_name,
                                        room_level.name as room_level_name
                                    from
                                        room_allotment_auto_reset
                                        inner join room_allotment_avail_rate on room_allotment_avail_rate.id=room_allotment_auto_reset.allotment_avail_rate_id
                                        inner join room_allotment on room_allotment.id=room_allotment_avail_rate.room_allotment_id
                                        inner join customer on customer.id=room_allotment.customer_id
                                        inner join room_level on room_level.id=room_allotment.room_level_id
                                    where
                                        room_allotment_auto_reset.time>='.Date_Time::to_time(date('d/m/Y')).'
                                        and room_allotment_auto_reset.time<'.(Date_Time::to_time(date('d/m/Y'))+86400).'
                                    order by
                                        room_allotment_auto_reset.time
                                    ');
       //System::debug($auto_reset); die;
       $this->map['auto_reset'] = $auto_reset;
       $this->parse_layout('list',$this->map);
	}
}
?>
