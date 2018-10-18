<?php
class AddReservationForm extends Form
{
	function AddReservationForm()
	{
		Form::Form('AddReservationForm');
		//---------------------------------------------------------------------------------		
		$this->link_css('packages/core/includes/js/jquery/window/css/jquery.window.4.04.css');		
		$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.widget.js');		
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.mouse.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.draggable.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.resizable.js');
		$this->link_js('packages/core/includes/js/picker.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
		//---------------------------------------------------------------------------------
		$this->link_css('packages/hotel/'.Portal::template('reception').'/css/style.css');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_js('packages/hotel/includes/js/suggest.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/hotel/packages/reception/modules/includes/reservation_table.js');
		$this->link_js('packages/hotel/includes/js/ajax.js');	
		$this->link_js('packages/hotel/packages/reception/modules/includes/reservation.js');
		$this->link_js('packages/core/includes/js/multi_items.js');					
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->add('note',new TextType(false,'invalid_note',0,200000)); 
		$this->add('customer_id',new TextType(false,'customer_id',0,255)); 
		$this->add('tour_id',new TextType(false,'tour_id',0,255));
		$this->add('reservation_room.room_name',new TextType(true,'miss_room',0,255));
		$this->add('reservation_room.room_level_name',new TextType(false,'invalid_room_name',0,255)); 
		$this->add('reservation_room.room_level_id',new TextType(true,'invalid_room_level_id',0,255)); 
		$this->add('reservation_room.price',new FloatType(true,'invalid_price','1','100000000000')); 
		$this->add('reservation_room.time_in',new TextType(true,'miss_time_in',0,5)); 
		$this->add('reservation_room.time_out',new TextType(true,'miss_time_out',0,5)); 
		$this->add('reservation_room.arrival_time',new DateType(true,'arrival_time')); 
		$this->add('reservation_room.adult',new IntType(true,'invalid_adult','0','1000')); 
		$this->add('reservation_room.child',new IntType(false,'invalid_child','0','1000')); 
		$this->add('reservation_room.departure_time',new DateType(true,'departure_time')); 
		$this->add('reservation_room.note',new TextType(false,'invalid_note',0,255)); 
		$this->add('reservation_room.total_amount',new FloatType(false,'invalid_total_amount','0','100000000000')); 
		$this->add('reservation_room.reduce_balance',new FloatType(false,'invalid_reduce_balance','0','100000000000')); 
		$this->add('reservation_room.deposit',new FloatType(false,'invalid_deposit','0','100000000000'));
		$this->add('reservation_room.tax_rate',new FloatType(false,'invalid_tax_rate','0','100000000000')); 
		$this->add('reservation_room.service_rate',new FloatType(false,'invalid_service_rate','0','100000000000')); 
		$this->add('reservation_room.status',new SelectType(true,'invalid_status',array('BOOKED'=>Portal::language('booked'),'CHECKIN'=>Portal::language('check_in'),'CHECKOUT'=>Portal::language('check_out'))));
		$this->add('traveller.first_name',new TextType(true,'invalid_first_name',0,255)); 
		$this->add('traveller.last_name',new TextType(true,'invalid_last_name',0,255)); 
		$this->add('traveller.birth_date',new DateType(false,'invalid_birth_date')); 
		$this->add('traveller.passport',new TextType(false,'miss_passport',0,255)); 
		$this->add('traveller.nationality_id',new TextType(false,'miss_nationality',0,255)); 
		$this->add('traveller.visa',new TextType(false,'invalid_visa',0,255));
		$this->add('traveller.note',new TextType(false,'invalid_note',0,255)); 
		//$this->add('traveller.phone',new TextType(false,'invalid_phone',0,255)); 
		//$this->add('traveller.fax',new TextType(false,'invalid_fax',0,255)); 
		//$this->add('traveller.address',new TextType(false,'invalid_address',0,2000)); 
		$this->add('traveller.email',new EmailType(false,'invalid_email')); 
		$this->add('traveller.traveller_room_id',new TextType(false,'invalid_room_id',0,255));
	}
	function on_submit()
	{
		if(!Url::get('count_date') and $this->check())
		{
			echo '<script src="packages/hotel/includes/js/common.js"></script><script src="packages/hotel/includes/js/ajax.js"></script>';
			require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
			$valid_room_array = reservation_check_conflict($this);
			foreach($valid_room_array as $key=>$value){
				if(!$value and !Url::get('customer_id')){
					$this->error('room_'.$key,Portal::language('Has_no_person_in_room').' '.DB::fetch('select id,name from room where id='.$key.'','name'),false);
				}
			}
			if($this->is_error())
			{
				return;
			}
			$old_items = array();
			$old_travellers = array();
			reservation_check_permission($this, false, $old_items);
			if($this->is_error())
			{
				return;
			}
			$id = DB::insert('reservation', 
				array(
					'customer_id',
					'tour_id',
					'note',
					'color',
					'user_id'=>Session::get('user_id'),
					'time'=>time(),
					'payment'=>str_replace(',','',Url::get('payment')),
					'booking_code',
					'portal_id'=>PORTAL_ID
				)
			);
			
			$title = 'Make reservation <a target="_blank" href="?page=reservation&cmd=edit&id='.$id.'">#'.$id.'</a>';
			$description = ''
				.'Code: '.$id
				.'<br>Note: '.substr(URL::get('note'),0,255)
				.(URL::get('customer_id')?'<br>Customer name:<a target="_blank" href="?page=customer&id='.URL::get('customer_id').'">'.DB::fetch('select name from customer where id=\''.URL::get('customer_id').'\'','name').'</a>':'')
				.'<br><u class="title">Rooms:</u>';
					$customer_name = URL::get('customer_id')?DB::fetch('select customer.name  from customer where id=\''.URL::get('customer_id').'\'','name'):'';
					update_reservation_room($this,$id, $title, $description, $customer_name,$change_status,$old_items);
			if($this->is_error())
			{
				return;
			}
			update_reservation_traveller($this, $id, $old_travellers, $title, $description, $customer_name,$change_status);
			System::log('add',$title,$description,$id);
			echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> Updating room status to server...</div>';
			echo '<script>
			if(window.opener)
			{
				window.opener.history.go(0);
				window.close();
			}
			window.setTimeout("location=\''.URL::build('room_map',array('just_edited_id'=>$id)).'\'",1000);
			</script>';
			exit();
		}
	}
	function draw()
	{
		$this->map = array();
		//------------------------------------Auto Arrange---------------------------------------------
		if(!isset($_REQUEST['mi_reservation_room']) and Url::get('room_levels') and Url::get('arrival_time') and Url::get('departure_time')){
			$arrival_time = Url::get('arrival_time');
			$departure_time = Url::get('departure_time');
			if(Url::get('time_in')){
				$time_in = Url::get('time_in');
			}else{
				$time_in = CHECK_IN_TIME;
			}
			if(Url::get('time_out')){
				$time_out = Url::get('time_out');
			}else{
				$time_out = CHECK_OUT_TIME;
			}
			$room_level_tr = Url::get('room_levels');
			$room_level_arr = explode('|',$room_level_tr);
			$room_levels = array();
			foreach($room_level_arr as $value){
				$arr = explode(',',$value);
				if($arr[1]){
					$room_levels[$arr[0]]['id'] = $arr[0];
					$room_levels[$arr[0]]['quantity'] = $arr[1];
					$room_levels[$arr[0]]['price'] = $arr[2];	
				}
			}
			$count = 1;
			$all_room_levels = DB::fetch_all('select id,price,name from room_level where portal_id = \''.PORTAL_ID.'\' order by name');
			foreach($room_levels as $key=>$value){
				for($i=1;$i<=$value['quantity'];$i++){
					$_REQUEST['mi_reservation_room'][$count] = (Url::get('status')?array('status'=>Url::get('status')):array())+array(
						'room_level_name'=>$all_room_levels[$value['id']]['name'],
						'room_level_id'=>$all_room_levels[$value['id']]['id'],
						'room_id'=>'',
						'room_name'=>'#'.$count,
						'adult'=>1,
						'arrival_time'=>$arrival_time,
						'departure_time'=>$departure_time,
						'time_in'=>$time_in,
						'time_out'=>$time_out
					);
					$count++;
				}
			}
		}else{
			if(!isset($_REQUEST['mi_reservation_room']) and URL::get('rooms'))
			{
				$rooms = explode('|',URL::get('rooms'));
				$count = 1;
				$room_levels = DB::fetch_all('select id,price,name from room_level where portal_id = \''.PORTAL_ID.'\' order by name');
				foreach($rooms as $params)
				{
					if($params){
						$params = explode(',',$params);
						$room = DB::select('room',$params[0]);
						$_REQUEST['mi_reservation_room'][$count] = (Url::get('status')?array('status'=>Url::get('status')):array())+array(
							'room_id'=>$params[0],
							'arrival_time'=>$params[1],
							'departure_time'=>$params[2],
							'room_level_name'=>$room_levels[$room['room_level_id']]['name'],
							'room_level_id'=>$room_levels[$room['room_level_id']]['id'],
							'time_in'=>(Url::get('status') and Url::get('status')=='CHECKIN')?date('H:i'):$_REQUEST['time_in'],
							'time_out'=>$_REQUEST['time_out'],
							'room_name'=>$room['name']
						);
						$count++;
					}
				}
			}
		}
		$currencys = DB::select_all('currency',false,'name');
		$this->map['reservation_room_items'] = isset($_REQUEST['table_reservation_room'])?current($_REQUEST['table_reservation_room']):array();
		$this->map['vip_card_list'] = array(''=>'')+String::get_list(DB::fetch_all('select code as id,discount_percent as name from vip_card order by code'));
		$this->map['traveller_items'] = isset($_REQUEST['table_traveller'])?current($_REQUEST['table_traveller']):array();
		$holidays = DB::fetch_all('select id,name,charge,in_date from holiday');
		$holiday = array();
		foreach($holidays as $key=>$value){
			$k = Date_Time::convert_orc_date_to_date($value['in_date'],'/');
			$holiday[$k]['id'] = $k;
			$holiday[$k]['name'] = $value['name'];
			$holiday[$k]['charge'] = $value['charge'];
		}
		$this->map['holidays'] = String::array2js($holiday);
		$reservation_types = DB::fetch_all('select id,name from reservation_type order by position');
		$reservation_type_options = '';
		foreach($reservation_types as $key=>$value){
			$reservation_type_options .= '<option value="'.$key.'">'.$value['name'].'</option>';
		}
		$this->map['reservation_type_options'] = $reservation_type_options;
		$this->parse_layout('add',$this->map);
	}
}
?>