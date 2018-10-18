<?php
class EditReservationForm extends Form
{
	function EditReservationForm()
	{
		Form::Form('EditReservationForm');
		//$this->add('id',new IDType(true,'object_not_exists','reservation'));
		$this->link_css('packages/core/includes/js/jquery/window/css/jquery.window.4.04.css');
		$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.widget.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.mouse.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.draggable.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.resizable.js');
		$this->link_js('packages/core/includes/js/picker.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
		$this->link_js('packages/hotel/includes/js/suggest.js');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/hotel/packages/reception/modules/includes/reservation_table.js');
		$this->link_js('packages/hotel/includes/js/ajax.js');
		$this->link_js('packages/hotel/packages/reception/modules/includes/reservation.js');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_js('packages/core/includes/js/tooltip.js');
		//$this->link_js('r_get_reservation_traveller.php?id='.Url::get('id'),false);
		$this->link_css('packages/hotel/'.Portal::template('reception').'/css/style.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		//Dung cho folio
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
		//end
		$this->link_js('r_get_reservation.php?id='.Url::get('id'),false);
		$this->add('note',new TextType(false,'invalid_note',0,200000));
		$this->add('customer_id',new TextType(false,'customer_id',0,255));
		$this->add('tour_id',new TextType(false,'tour_id',0,255));
		$this->add('reservation_room.id',new TextType(false,'invalid_reservation_id',0,255));
		$this->add('reservation_room.room_name',new TextType(false,'miss_room',0,255));
		$this->add('reservation_room.room_id',new TextType(false,'invalid_room_id',0,255,'status','CHECKIN'));
		$this->add('reservation_room.room_level_id',new TextType(false,'invalid_room_level_id',0,255));
		$this->add('reservation_room.room_level_name',new TextType(false,'invalid_room_level_id',0,255));
		$this->add('reservation_room.price',new FloatType(true,'invalid_price','0','100000000000'));
		$this->add('reservation_room.time_in',new TextType(true,'time_in',0,5));
		$this->add('reservation_room.time_out',new TextType(true,'time_out',0,5));
		$this->add('reservation_room.arrival_time',new DateType(true,'arrival_time'));
		$this->add('reservation_room.adult',new IntType(false,'invalid_adult','0','1000'));
		$this->add('reservation_room.child',new IntType(false,'invalid_child','0','1000'));
		$this->add('reservation_room.departure_time',new DateType(true,'departure_time'));
		$this->add('reservation_room.note',new TextType(false,'invalid_note',0,255));
		$this->add('reservation_room.total_amount',new FloatType(false,'invalid_total_amount','0','100000000000'));
		$this->add('reservation_room.reduce_balance',new FloatType(false,'invalid_reduce_balance','0','100'));
		$this->add('reservation_room.deposit',new FloatType(false,'invalid_deposit','0','100000000000'));
		$this->add('reservation_room.reason',new TextType(false,'invalid_reason',0,255));
		$this->add('reservation_room.tax_rate',new FloatType(false,'invalid_tax_rate','0','100'));
		$this->add('reservation_room.service_rate',new FloatType(false,'invalid_service_rate','0','100'));
		//require_once 'cache/config/payment.php';
	}
	function on_submit()
	{
		if($this->check())
		{
			require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
			$id = Url::iget('id');
			$old_reservation = DB::select('reservation','id='.$id);
			$old_tour_name = $old_reservation['tour_id']?DB::fetch('select id,name from tour where id = '.$old_reservation['tour_id'].'','name'):'';
			$old_customer_name = $old_reservation['customer_id']?DB::fetch('select id,name from customer where id = '.$old_reservation['customer_id'].'','name'):'';
			$old_reservation_room = DB::select_all('reservation_room','reservation_id='.$id.(URL::get('reservation_room_id')?' and id=\''.URL::get('reservation_room_id').'\'':''));
			$live = array();
			if(isset($_REQUEST['mi_reservation_room'])){
				$reservation_room_arr = array();
				foreach($_REQUEST['mi_reservation_room'] as $key=>$record)
				{
					$reservation_room_arr[$record['id']] = $record;
					if($record['id'] and isset($old_reservation_room[$record['id']]))
					{
						$live[$record['id']] = true;
					}
				}
				//$_REQUEST['mi_reservation_room'] = $reservation_room_arr;
			}
			if(User::can_admin(false,ANY_CATEGORY)){
				foreach($old_reservation_room as $reservation_room)
				{
					if(!isset($live[$reservation_room['id']]))
					{
						DB::delete('reservation_traveller','reservation_room_id=\''.$reservation_room['id'].'\'');
						DB::delete('reservation_room','id=\''.$reservation_room['id'].'\'');
						DB::update('room_status',array('reservation_id'=>0,'status'=>'AVAILABLE'),'reservation_room_id=\''.$reservation_room['id'].'\'');
						DB::delete('payment',' reservation_room_id = '.$reservation_room['id'].'');
						DB::delete('folio',' reservation_room_id = '.$reservation_room['id'].'');
						DB::delete('traveller_folio','  reservation_room_id = '.$reservation_room['id'].'');
					}
				}
			}
			$valid_room_array = reservation_check_conflict($this);
			foreach($valid_room_array as $key=>$value){
				if(!$value and !Url::get('customer_id')){
					//$this->error('room_'.$key,Portal::language('Has_no_person_in_room').' '.DB::fetch('select id,name from room where id='.$key.'','name'),false);
				}
			}
			if($this->is_error())
			{
				return;
			}
			reservation_check_permission($this, $id, $old_reservation_room);
			if($this->is_error())
			{
				return;
			}
			$title = 'Edit reservation <a target=\'_blank\' href=\'?page=reservation&cmd=edit&id='.$id.'\'>#'.$id.'</a>';
			$description = ''
				.Portal::language('rcode').': '.$id
				.((Url::get('booking_code') and Url::iget('booking_code') != $old_reservation['booking_code'])?' | '.Portal::language('change').' '.Portal::language('booking_code').' '.$old_reservation['booking_code'].' '.Portal::language('to').': '.Url::get('booking_code').'<br>':'<br>')
				.((Url::get('tour_id') and Url::iget('tour_id') != $old_reservation['tour_id'])?Portal::language('change').' '.Portal::language('tour').' '.$old_tour_name.' '.Portal::language('to').' :<a target="_blank" href="?page=customer&id='.URL::get('customer_id').'">'.DB::fetch('select name from tour where id=\''.URL::get('tour_id').'\'','name').'</a><br>':'')
				.((Url::get('customer_id') and Url::iget('customer_id') != $old_reservation['customer_id'])?Portal::language('change').' '.Portal::language('customer').' '.$old_customer_name.' '.Portal::language('to').' '.':<a target="_blank" href="?page=customer&id='.URL::get('customer_id').'">'.DB::fetch('select name from customer where id=\''.URL::get('customer_id').'\'','name').'</a><br>':'')
				.((Url::get('note') and Url::get('note') != $old_reservation['note'])?Portal::language('change').' '.Portal::language('note').': '.substr(Url::get('note'),0,255).'<br>':'')
				.'<u class="title">'.Portal::language('room_info').':</u>';
			$customer_name = URL::get('customer_id')?DB::fetch('select name from customer where id=\''.URL::get('customer_id').'\'','name'):'';
			update_reservation_room($this,$id, $title, $description, $customer_name,$change_status,$old_reservation_room);
			$new_reservation_room =  DB::select_all('reservation_room','reservation_id='.$id.(URL::get('reservation_room_id')?' and id=\''.URL::get('reservation_room_id').'\'':''));
			if($this->is_error())
			{
				return;
			}
			/*$sql = '
				SELECT
					reservation_traveller.id
					,traveller.first_name,traveller.last_name ,traveller.gender
					,traveller.birth_date
					,traveller.passport
					,reservation_traveller.visa_number as visa
					,reservation_traveller.expire_date_of_visa
					,traveller.note
					,traveller.phone ,traveller.fax ,traveller.address
					,traveller.email
					,traveller.nationality_id
					,reservation_room.reservation_id
					,reservation_traveller.reservation_room_id
					,traveller.id as traveller_id
				FROM
					reservation_traveller
					inner join traveller on traveller.id=reservation_traveller.traveller_id
					left outer join reservation_room on reservation_room.id = reservation_traveller.reservation_room_id
				WHERE
					reservation_traveller.reservation_id='.$id.'
			';
			$old_travellers = DB::fetch_all($sql);*/
			//update_reservation_traveller($this, $id, $old_travellers, $title, $description, $customer_name,$change_status);
			if($this->is_error())
			{
				return;
			}
			DB::update_id('reservation',
				array(
					'customer_id',
					'tour_id',
					'note',
					'color',
					'payment'=>str_replace(',','',Url::get('payment')),
					'booking_code',
					'lastest_user_id'=>Session::get('user_id'),
					'lastest_time'=>time(),
				),$id
			);
			$description .= '<div>----------------------------
				<li>Lastest Modified User: '.Session::get('user_id').'</li>
				<li>Lastest Modified Time: '.date('d/m/Y H:i\'',time()).'</li>
			<ul></div>';
			System::log('add',$title,$description,$id);
			if(Url::get('save')){
				echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" />
					Updating room status to server...</div>';
				echo '<script>
				if(window.opener && (window.opener.year || window.opener.night_audit))
				{
					window.opener.history.go(0);
					window.close();
				}
				window.setTimeout("location=\''.URL::build('room_map',array('just_edited_id'=>$id)).'\'",2000);
				</script>';
				exit();
			}else{
				echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> Updating room status to server...</div>';
				echo '<script>
				if(window.opener && (window.opener.year || window.opener.night_audit))
				{
					window.opener.history.go(0);
					window.close();
				}
				window.setTimeout("location=\''.URL::build_current(array('cmd','id','r_r_id')).'\'",2000);
				</script>';
				exit();
			}
		}
	}
	function draw()
	{
		//update pay_by_currency
		/*$rr = DB::select_all('reservation_room','status=\'CHECKOUT\'');
		foreach($rr as $key=>$value){
			if(!DB::exists('select * from pay_by_currency where bill_id='.$value['id'].' and currency_id="USD" and type="RESERVATION"')){
				DB::insert('pay_by_currency',array('currency_id'=>'USD','amount'=>$value['total_amount'],'bill_id'=>$value['id'],'type'=>'RESERVATION','exchange_rate'=>DB::fetch('select id,exchange from currency where id="'.HOTEL_CURRENCY.'"','exchange')));
			}
		}*/
		//----------------------
		$this->map = array();
		$sql = '
					select
						service.id,service.name
					from
						service
					order by
						service.name
					';
		$this->map['services'] = DB::fetch_all($sql);
		$currencies = DB::select_all('currency','allow_payment=1','name');
		foreach($currencies as $key=>$value){
			$currencies[$key]['name'] = ($key=='USD')?'Credit card':$value['name'];
		}
		$this->map['currencies'] = $currencies;
		$sql = '
			select
				reservation.*,customer.name as full_name,tour.name as tour_name,reservation.tour_id,reservation.customer_id
			from
			 	reservation
				left outer join customer on customer.id=reservation.customer_id
				left outer join tour on tour.id=reservation.tour_id
			where
				reservation.id = '.Url::iget('id').'
		';
		$row = DB::fetch($sql);
		$row['payment'] =  System::display_number($row['payment']);
		$row['cut_of_date'] = Date_Time::convert_orc_date_to_date($row['cut_of_date'],'/');
		foreach($row as $key=>$value)
		{
			if(!isset($_REQUEST[$key]))
			{
				$_REQUEST[$key] = $value;
			}
		}
		if(!isset($_REQUEST['mi_reservation_room']))
		{
			$sql = '
				select
					reservation_room.id
					,reservation_room.price
					,reservation_room.vip_card_code
					,reservation_room.status as old_status
					,reservation_room.status
					,reservation_room.adult
					,reservation_room.child
					,reservation_room.time_in
					,reservation_room.time_out
					,reservation_room.arrival_time
					,reservation_room.departure_time
					,reservation_room.note
					,reservation_room.total_amount
					,reservation_room.reduce_balance
					,reservation_room.reduce_amount
					,reservation_room.deposit
					,reservation_room.deposit_type
					,reservation_room.deposit_date
					,reservation_room.tax_rate ,reservation_room.service_rate
					,reservation_room.room_level_id
					,room_level.brief_name as room_level_name
					,reservation_room.room_id
					,reservation_room.room_id AS room_id_old
					,CASE WHEN room.name is null THEN reservation_room.temp_room ELSE room.name END room_name
					,CASE WHEN room.name is null THEN reservation_room.temp_room ELSE room.name END room_name_old
					,payment_type.def_code
					,DECODE(reservation_room.arrival_time,reservation_room.departure_time,1,0) AS in_day
					,reservation_room.traveller_id
					,reservation_room.reservation_id
					,reservation_room.foc
					,reservation_room.foc_all
					,reservation_room.reservation_type_id
					,reservation_room.related_rr_id
					,reservation_room.confirm
					,reservation_room.closed
					,reservation_room.deposit_invoice_number
					,reservation_room.early_arrival_time
					,reservation_room.verify_dayuse
					,reservation_room.net_price
					,reservation_room.extra_bed
					,to_char(reservation_room.extra_bed_from_date,\'DD/MM/YYYY\') as extra_bed_from_date
					,to_char(reservation_room.extra_bed_to_date,\'DD/MM/YYYY\') as extra_bed_to_date
					,reservation_room.extra_bed_rate
					,reservation_room.baby_cot
					,to_char(reservation_room.baby_cot_from_date,\'DD/MM/YYYY\') as baby_cot_from_date
					,to_char(reservation_room.baby_cot_to_date,\'DD/MM/YYYY\') as baby_cot_to_date
					,reservation_room.baby_cot_rate
					,reservation_room.net_price
					,reservation_room.early_checkin
					,reservation_room.late_checkout
				from
					reservation_room
					left outer join room on room.id=reservation_room.room_id
					left outer join room_level on room_level.id=reservation_room.room_level_id
					left outer join room_status on room_status.reservation_room_id=reservation_room.id
					left outer join payment_type on payment_type.id=reservation_room.payment_type_id
				where
					reservation_room.reservation_id='.Url::iget('id').'
					'.(URL::get('reservation_room_id')?' and reservation_room.id=\''.URL::get('reservation_room_id').'\'':'').'
				order by
					reservation_room.time_in asc';
			$mi_reservation_room = DB::fetch_all($sql);
			$room_status = DB::fetch_all('
				SELECT
					CONCAT(reservation_room_id,CONCAT(\'_\',in_date)) AS id,
					change_price,reservation_room_id,in_date,room_id,room_status.closed_time
				FROM
					room_status
				WHERE
					reservation_id='.Url::iget('id').' AND status<>\'AVAILABLE\' AND status<>\'CANCEL\'
				ORDER BY
					in_date
			');
			$change_price_arr = array();
			require_once 'packages/hotel/packages/reception/modules/includes/get_reservation.php';
			$reservation_arr = get_reservation(Url::iget('id'),false,true);
			$this->map['exchange_rate'] = DB::fetch('select exchange from currency where id=\'USD\'','exchange');
			$check_all_net_price = 1;
			foreach($mi_reservation_room as $key=>$value)
			{
				if(isset($reservation_arr['items'][$key])){
					$mi_reservation_room[$key]['total_payment'] = System::display_number($reservation_arr['items'][$key]['total_amount']);
				}else{
					$mi_reservation_room[$key]['total_payment'] = '';
				}
				//$this->fix($value);
				$mi_reservation_room[$key]['time_in'] = date('H:i',$value['time_in']);
				$mi_reservation_room[$key]['time_out'] = date('H:i',$value['time_out']);
				$mi_reservation_room[$key]['time_in_in'] = $value['time_in'];
				$mi_reservation_room[$key]['time_out_out'] = $value['time_out'];
				$mi_reservation_room[$key]['arrival_time'] = Date_Time::convert_orc_date_to_date($value['arrival_time'],'/');
				$mi_reservation_room[$key]['early_arrival_time'] = Date_Time::convert_orc_date_to_date($value['early_arrival_time'],'/');
				$mi_reservation_room[$key]['departure_time'] = Date_Time::convert_orc_date_to_date($value['departure_time'],'/');
				$mi_reservation_room[$key]['departure_time_old'] = $mi_reservation_room[$key]['departure_time'];
				$mi_reservation_room[$key]['deposit_date'] = Date_Time::convert_orc_date_to_date($value['deposit_date'],'/');
				$mi_reservation_room[$key]['reduce_amount'] = System::display_number($value['reduce_amount']);
				$mi_reservation_room[$key]['extra_bed_rate'] = System::display_number($value['extra_bed_rate']);
				$mi_reservation_room[$key]['baby_cot_rate'] = System::display_number($value['baby_cot_rate']);
				if($mi_reservation_room[$key]['net_price'] == 0){
					$check_all_net_price = 0;
				}
				if($value['in_day']==1){
					if(isset($room_status[$value['room_id'].'_'.$value['departure_time']])){
						unset($room_status[$value['room_id'].'_'.$value['departure_time']]);
					}
				}
				$mi_reservation_room[$key]['price'] = System::display_number($value['price']);
				$mi_reservation_room[$key]['adult'] = $value['adult'];
				$mi_reservation_room[$key]['child'] = $value['child'];
				$mi_reservation_room[$key]['total_amount'] = System::display_number($value['total_amount']);
				$mi_reservation_room[$key]['reduce_balance'] = System::display_number($value['reduce_balance']);
				$mi_reservation_room[$key]['deposit'] = $value['deposit'];
				$change_price_arr = array();
				$change_price_closed_time_arr = array();
				foreach($room_status as $k=>$v){
					if($v['reservation_room_id'] == $value['id']){
						if($v['in_date']<>$value['departure_time']){
							$change_price_arr[Date_Time::convert_orc_date_to_date($v['in_date'],'/')] = $v['change_price'];
						}
						$change_price_closed_time_arr[Date_Time::convert_orc_date_to_date($v['in_date'],'/')] = $v['closed_time'];
					}
				}
				$mi_reservation_room[$key]['change_price_arr'] = $change_price_arr;
				$mi_reservation_room[$key]['change_price_closed_time_arr'] = $change_price_closed_time_arr;
				$pay_by_currency_arr = DB::fetch_all('SELECT currency_id as id,amount,bill_id FROM pay_by_currency WHERE bill_id = '.$key.' AND type=\'RESERVATION\'');
				foreach($pay_by_currency_arr as $k=>$v){
					$pay_by_currency_arr[$k]['amount'] = System::display_number($v['amount']);
				}
				$mi_reservation_room[$key]['currency_arr'] = $pay_by_currency_arr;
				$mi_reservation_room[$key]['service_arr'] = DB::fetch_all('SELECT service_id as id,amount,reservation_room_id FROM reservation_room_service WHERE reservation_room_id='.$key.' ORDER BY type');
			}
			$_REQUEST['mi_reservation_room'] = $mi_reservation_room;
			if(User::is_admin()){
				//System::debug($_REQUEST['mi_reservation_room']);
			}
			if($check_all_net_price == 1){
				$_REQUEST['change_all_net_price'] = 1;
			}
		}
		if($row['customer_id'])
		{
			$_REQUEST['customer_name'] = DB::fetch('select name from customer where id=\''.$row['customer_id'].'\'','name');
			$_REQUEST['customer_id'] = $row['customer_id'];
		}
		$this->map += $row;
		$this->map +=array(
			'payment_types'=>DB::fetch_all('select def_code as id, name_'.Portal::language().' as name from payment_type where '.IDStructure::direct_child_cond(ID_ROOT).' order by name'),
			'nationalities'=>DB::fetch_all('select code_1 as id, name_'.Portal::language().' as name from country where 1=1 order by name_'.Portal::language().''),
			'vip_card_list'=>array(''=>'')+String::get_list(DB::fetch_all('select code as id,discount_percent as name from vip_card order by code')),
			);
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
		$def_codes_options = '
			<option value="">'.Portal::language('select').'</option>
			<option value="CASH">'.Portal::language('pay_now').'</option>
			<option value="DEBIT">'.Portal::language('debit').'</option>
			<option value="CREDIT_CARD">'.Portal::language('credit_card').'</option>
		';
		$guest_types = DB::fetch_all('select id,name from guest_type order by position');
		$guest_type_options = '<option value="">'.Portal::language('select').'</option>';
		foreach($guest_types as $key=>$value){
			$guest_type_options .= '<option value="'.$key.'">'.$value['name'].'</option>';
		}
		$this->map['traveller_level_options'] = $guest_type_options;
		$this->map['def_codes_options'] = $def_codes_options;
		$this->map['verify_dayuse_options'] = '';
		$this->map['verify_dayuse_options'] .= '<option value="">0</option>';
		$this->map['verify_dayuse_options'] .= '<option value="5">+0.5</option>';
		$this->map['verify_dayuse_options'] .= '<option value="10">+1</option>';
		$this->map['verify_dayuse_options'] .= '<option value="-5">- 0.5</option>';
		$this->parse_layout('edit',$this->map);
	}
	function fix($value){
		//----------------------fix error------------------------------------------
		if(isset($value['traveller_id']) and $value['traveller_id'] and $value['status']=='CHECKIN' or $value['status']=='CHECKOUT'){
			if(!DB::exists('select * from reservation_traveller where traveller_id ='.$value['traveller_id'].' and reservation_room_id='.$value['id'])){
				//echo 'update traveller_id...';
				DB::insert('reservation_traveller',array(
					'traveller_id'=>$value['traveller_id'],
					'reservation_room_id'=>$value['id'],
				));
			}
			$start = Date_Time::to_time(date('d/m/Y',$value['time_in']));
			$end = Date_Time::to_time(date('d/m/Y',$value['time_out']));
			for($i=$start;$i<=$end;$i+=24*3600){
				$status = ($value['status']=='CHECKIN' or $value['status']=='CHECKOUT')?'OCCUPIED':$value['status'];
				$price = ($end==$start)?$value['price']:(($i==$end)?0:$value['price']);
				$sql='select * from room_status where in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$i)).'\' and room_id='.$value['room_id'].' and status=\''.$status.'\'';
				if(!DB::exists($sql)){
					//echo 'update room_status table...';
					DB::insert('room_status',array(
						'in_date'=>Date_Time::to_orc_date(date('d/m/Y',$i)),
						'room_id'=>$value['room_id'],
						'status'=>$status,
						'change_price'=>$price,
						'reservation_id'=>$value['reservation_id']
					));
				}
			}
		}
		//----------------------/fix error------------------------------------------
	}
}
?>