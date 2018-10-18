<?php
class EditPaymentForm extends Form{
	function EditPaymentForm(){
		Form::Form('EditPaymentForm');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/hotel/packages/restaurant/includes/js/update_price_new.js');
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
		$this->add('payment.amount',new FloatType(false,'invalid_amount','0','100000000000'));
		$this->add('payment.payment_type_id',new TextType(true,'miss_payment_type_id',0,255)); 
	}
	function on_submit(){
		if($this->check()){
			$deposit = 0;
			if(isset($_REQUEST['mi_payment']) and Url::get('type') and Url::get('id') ){
				$i=1;
				$ids='0';
				foreach($_REQUEST['mi_payment'] as $key=>$record){
					if($record['id']!='(auto)'){
						$ids .= ($ids=='')?$record['id']:','.$record['id'];
					}
				}
				$cond2 = '';
				if(Url::get('type')=='RESERVATION'){
					if(Url::get('cmd')=='deposit'){
						$cond2 .= '  AND payment.type_dps =\'ROOM\'';	
					}else if(Url::get('folio_id')){
						$cond2 .= '  AND payment.folio_id ='.Url::iget('folio_id').'';	
					}
					DB::query('delete from payment where payment.id not in ('.$ids.') AND payment.type=\''.Url::get('type').'\' AND payment.bill_id ='.Url::iget('id').''.$cond2);
				}else{
					DB::query('delete from payment where payment.id not in ('.$ids.') AND payment.type=\''.Url::get('type').'\' AND payment.bill_id ='.Url::iget('id').'');
				}
				foreach($_REQUEST['mi_payment'] as $key=>$record){
					if($record['amount']>0){
						$record['bill_id'] = Url::iget('id');
						$record['type'] = Url::get('type');
						$record['portal_id'] = PORTAL_ID;
						$record['amount'] = System::calculate_number($record['amount']);
						$record['bank_fee'] = System::calculate_number($record['bank_fee']) - $record['amount'];
						unset($record['paid']);
						//TH thanh toán
						if(Url::get('traveller_id')){
							$record['reservation_room_id'] = Url::iget('id')?Url::iget('id'):'';	
							$record['reservation_id'] = Url::iget('r_id')?Url::iget('r_id'):'';
							$record['reservation_traveller_id'] = Url::get('traveller_id');
						}else if(Url::get('customer_id')){
							$record['reservation_id'] = Url::iget('id')?Url::iget('id'):'';	
							$record['customer_id'] = Url::get('customer_id');	
						}//end
						//TH đặt cọc
						if(Url::get('act')=='group'){
							$record['type_dps'] = 'GROUP';
							$record['reservation_id'] = Url::iget('id')?Url::iget('id'):'';	
						}else if(Url::get('act')=='traveller'){
							$record['type_dps'] = 'ROOM';
							$record['reservation_room_id'] = Url::iget('id')?Url::iget('id'):'';	
							$record['reservation_id'] = Url::iget('r_id')?Url::iget('r_id'):'';	
						}
						//end
						if(Url::get('folio_id')){
							$record['folio_id']=Url::get('folio_id');
						}
						if(Url::get('cmd')=='deposit'){
							$record['description']=($record['description']=='')?(' Đặt cọc lần '.$i):$record['description'];
							$i++;
							if(Url::get('act') || (Url::get('cmd')=='deposit' && Url::get('type')=='BAR')){
								$deposit += round($record['amount'] * $record['exchange_rate'],2);
							}
						}
						if(Url::get('cmd')=='deposit' && Url::get('type')=='BAR'){
							$record['type_dps'] = 'BAR';	
						}
						if($record['id']=='(auto)'){
							$record['id']=false;
						}
						if($record['id'] and DB::exists_id('payment',$record['id'])){
							unset($record['time']);
							unset($record['user_id']);
							DB::update('payment',$record,'id='.$record['id']);
						}else{
							unset($record['id']);
							$record['time'] = time();
							$record['user_id'] = Session::get('user_id');
							DB::insert('payment',$record);
						}
					}
				}
			}else if(Url::get('type') and Url::get('id')){
				DB::delete('payment',' type=\''.Url::get('type').'\' AND bill_id='.Url::get('id').'');
			}
			if(Url::get('cmd')=='deposit' && Url::get('type')=='RESERVATION'){
				if(Url::get('act')=='traveller' && Url::get('id')){
					DB::update('reservation_room',array('deposit'=>$deposit),' id= '.Url::get('id').'');
				}
				if(Url::get('act')=='group' && Url::get('id')){
					DB::update('reservation',array('deposit'=>$deposit),' id= '.Url::get('id').'');
				}
			}	
			if(Url::get('cmd')=='deposit' && Url::get('type')=='BAR'){
				DB::update('bar_reservation',array('deposit'=>$deposit),' id= '.Url::get('id').'');	
			}
			$con = '';
			if(Url::get('folio_id')){
				$con = '&folio_id='.Url::get('folio_id');
			}
			if(Url::get('act')){   
				$con .= '&act='.Url::get('act');
			}
			if(Url::get('traveller_id')){
				$con .= '&traveller_id='.Url::get('traveller_id');
			}
			if(Url::get('customer_id')){
				$con .= '&customer_id='.Url::get('customer_id');	
			}
			//exit();
			if(Url::get('type')=='BAR'){
				$bar_id = (Url::get('bar_id'))?Url::get('bar_id'):($_SESSION['bar_id']?$_SESSION['bar_id']:'');
				if(Url::get('cmd')=='deposit'){
					echo '
				<script>
							if($("deposit")){
								$("deposit").value=number_format('.$deposit.');
							}}
							</script>';	
				}
				echo '<script>window.parent.location.href=(\'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=touch_bar_restaurant&cmd=edit&id='.Url::get('id').'&bar_id='.$bar_id.'\');
				</script>';
				//Url::redirect_current(array('bar_id'=>Session::get('bar_id'),'cmd','id'));
			}else{
				$tt = 'form.php?block_id='.Module::block_id().'&id='.Url::get('id').'&type='.Url::get('type').'&total_amount='.Url::get('total_amount').'&cmd='.Url::get('cmd').$con;
				echo '
				<script src="packages/core/includes/js/jquery/jquery.min.1.4.2.js" type="text/javascript"></script>
				<script type="text/javascript" src="packages/core/includes/js/common.js"></script>
				';
				if(Url::get('act')=='traveller'){
					echo '
				<script>
						input_count_r_r = window.parent.input_count;
						for(i=101;i<=input_count_r_r;i++){
							if(window.parent.document.getElementById("deposit_"+i) && window.parent.document.getElementById("id_"+i).value=='.Url::get('id').'){
								window.parent.document.getElementById("deposit_"+i).value=number_format('.$deposit.');
							}}
							</script>';
				}
				if(Url::get('action')=='save_stay'){
					echo '<script>window.parent.location.reload();
				</script>';
				}else{
					echo '<script>window.location.href = \''.$tt.'\';
					</script>';		
				}
			}
		}else{
			$this->error('amount','miss_payment');
			return;
		}
	}	
	function draw()
	{	
		require_once 'packages/hotel/includes/php/hotel.php';
		$items_arr = ''; $total_paid = 0;
		$paging = '';
		$this->map = array();
		$count = 0;
		$rt_id = 0;$rr_id=0;
		//echo 'thuy'.Url::get('id').'_'.Url::get('type').'_'.Url::get('total_amount');
		$cond = 'payment.bill_id = '.Url::iget('id').' AND payment.type = \''.Url::get('type').'\'';
		if(Url::get('type')=='BAR'){
			if(Url::get('cmd')=='deposit'){
				$cond .= ' AND (payment.type_dps = \'BAR\' AND payment.folio_id is null)';
			}
		}else{
			
		}
		if(!isset($_REQUEST['mi_payment'])){
			$cond .= Url::get('folio_id')?(' AND (payment.folio_id='.Url::get('folio_id').')'):'';
			//OR payment.folio_id is null
			if(Url::get('cmd')=='deposit'){
				if(Url::get('act')=='group'){
					$cond .= ' AND (payment.type_dps = \'GROUP\' AND payment.folio_id is null)';
 				}else if(Url::get('act')=='traveller'){
					$cond .= ' AND (payment.type_dps = \'ROOM\' AND payment.folio_id is null)';	
				}
			}
			DB::query('
				select 
					count(*) as acount
				from 
					payment
				where 
					'.$cond.'
			');
			$count = DB::fetch(); 
			$mi_payment = DB::fetch_all('SELECT payment.*,ROWNUM as rownumber FROM payment WHERE '.$cond.' ORDER BY id ASC ');
			if(!empty($mi_payment)){
				foreach($mi_payment as $key=>$value){
					$mi_payment[$key]['amount'] = System::display_number($value['amount']);
					$value['bank_fee'] = ($value['bank_fee']=='')?0:$value['bank_fee'];
					$mi_payment[$key]['bank_fee'] = System::display_number($value['amount']+$value['bank_fee']);
					$mi_payment[$key]['time'] = date('H:i\' d/m/Y',$value['time']);
					$mi_payment[$key]['paid'] = true;
					$total_paid += round($value['amount']*$value['exchange_rate'],2);
				}
			}else{
				if(Url::get('cmd')=='deposit'){
					$mi_payment[1]['amount'] = 0;
					$mi_payment[1]['time'] = date('H:i\' d/m/Y',time());
					$mi_payment[1]['payment_type_id'] = 'CREDIT_CARD';
					$mi_payment[1]['credit_card_id'] = '1';
					$mi_payment[1]['currency_id'] = 'VND';
					$mi_payment[1]['reservation_room_id'] = '';
					$mi_payment[1]['bank_fee'] = '';
					$mi_payment[1]['paid'] = false;
				}else{
					$mi_payment[1]['amount'] = System::display_number(Url::get('total_amount'));
					$mi_payment[1]['time'] = date('H:i\' d/m/Y',time());
					$mi_payment[1]['payment_type_id'] = 'CASH';
					$mi_payment[1]['currency_id'] = 'VND';
					$mi_payment[1]['reservation_room_id'] = '';
					$mi_payment[1]['bank_fee'] = '';
					$mi_payment[1]['paid'] = false;
				}
				
			}
			//System::Debug($mi_payment);
			$_REQUEST['mi_payment'] = $mi_payment;
		}	
		$credit_card_options='<option value="">----</option>';
		$credit_cards = DB::fetch_all('select * from credit_card');
		foreach($credit_cards as $k => $credit){
			$credit_card_options .='<option value='.$credit['id'].'>'.$credit['name'].'</option>';
		}
		$this->map['credit_card_options'] = $credit_card_options;
		//------------------Get payment_type-------------------------------//
		$payment_type_options = '<option value="">----</option>';
		$con = '';
		if(Url::get('cmd')=='deposit'){
			$con = ' AND def_code<>\'DEBIT\'';	
		}
		if(Url::get('type')=='BAR'){
			$payment_type =DB::fetch_all('select def_code as id,name_'.Portal::language().' as name from payment_type where (apply=\'ALL\' OR (apply=\'BAR\')) AND def_code is not null AND def_code<>\'ROOM CHARGE\' '.$con);
		}else{
			$payment_type =DB::fetch_all('select def_code as id,name_'.Portal::language().' as name from payment_type where (apply=\'ALL\' OR (apply=\'ALL\')) AND def_code is not null '.$con);
		}
		foreach($payment_type as $key => $pmt){
			$payment_type_options .= '<option value="'.$pmt['id'].'">'.Portal::language($pmt['name']).'</option>';
		}
		$this->map['payment_type_options'] = $payment_type_options;
		//------------------END-------------------------------//

		//------------------Get currency-------------------------------//
		$this->map['default_exchange_rate'] = DB::fetch('SELECT exchange FROM currency WHERE id = \''.HOTEL_CURRENCY.'\'','exchange');
		$currencies = DB::select_all('currency','allow_payment=1','id DESC');
		$currency_options = '<option value="">'.Portal::language('select').'</option>';
		foreach($currencies as $value){
			$currency_options .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';	
		}
		$this->map['obj_payment'] = '';	
		if(Url::get('act')=='traveller'){
			$this->map['obj_payment'] = Portal::language('room').' '.DB::fetch('select room.name from reservation_room INNER join room ON reservation_room.room_id = room.id
					Where reservation_room.id = '.Url::get('id').'','name');
		}else if(Url::get('act')=='group'){
			$this->map['obj_payment'] =Portal::language('customer').' '.DB::fetch('select customer.name from reservation INNER join customer ON reservation.customer_id = customer.id
					Where reservation.id = '.Url::get('id').'','name');
		}
		//------------------END-------------------------------//
		$this->map['items'] = $items_arr;
		$this->map['currency_options'] = $currency_options;
		$this->map['currency']=$currencies;
		$this->map['payment_type']=$payment_type;
		$this->map['credit_cards']=$credit_cards;
		$this->map['credit_cards_js']=String::array2js($credit_cards);
		$this->map['count']=$count;
		$this->map['total_paid']=$total_paid;
		$this->map['total_amount'] = (Url::get('total_amount')?Url::get('total_amount'):0);
		$this->map['currencies'] = String::array2js($currencies);
		
		// Thoong tin khacsh phong
		/*$rows_list = Hotel::get_reservation_room();
			$guest_list = Hotel::get_reservation_guest();
			if($rr_id!=0){
				$current_room = DB::fetch('
					select 
						traveller.id, reservation_room.id as reservation_room_id,
						CONCAT(room.name,CONCAT(\' - \',CONCAT(CONCAT(traveller.first_name,\' \'),traveller.last_name))) as name,
						room.id as room_id,room.name as room_name
					from    
						reservation_traveller
						INNER JOIN traveller on traveller.id = reservation_traveller.traveller_id 
						INNER JOIN reservation_room on reservation_traveller.reservation_room_id = reservation_room.id
						LEFT OUTER JOIN room ON room.id = reservation_room.room_id
						inner join room_status on room_status.RESERVATION_ID  =  RESERVATION_ROOM.RESERVATION_ID 
					where
						reservation_room.id = '.$rr_id.'
				');
				$guest_list[$current_room['id']]['id'] = $current_room['id'];
				$guest_list[$current_room['id']]['name'] = $current_room['name'];
				$rows_list[$current_room['reservation_room_id']]['id'] = $current_room['reservation_room_id'];
				$rows_list[$current_room['reservation_room_id']]['name'] = $current_room['room_name'];
				$rows_list[$current_room['reservation_room_id']]['agent_name'] = $current_room['name'];
			}
			$list_room[0]='-------';
			$list_room = $list_room+String::get_list($rows_list,'name');
			//danh sach reservation
			$rows_list = $guest_list;
			$list_reservation[0]='-------';
			$list_reservation_traveller = $list_reservation+String::get_list($rows_list,'name');
			//danh sach phong - ten khach
	
			$reservation_room_list = Hotel::get_reservation_room_guest();
			$this->map+= array(
				'reservation_traveller_list'=>$guest_list,
				'reservation_room_id_list'=>$list_room,
				'reservation_traveller_id_list'=>$list_reservation_traveller
				
			);//'reservation_traveller_id'=>Url::get('reservation_traveller_id',$row['reservation_traveller_id']),
			// end thong tin khacsh phogn
		//System::Debug($currencies);*/
		
		$this->parse_layout('edit',$this->map);
	}   
	function get_item($items){    
		$item_detail = explode('{',Url::get('items_'.$items));
		$detail = preg_replace("/\'|}/","",$item_detail);
		$item = explode(":",$detail[1]);
		$t = 0;
		for($k=1;$k<count($item);$k++){	
			if($k < count($item)-1){	
				$tt = strrpos($item[$k],',');
				$item_cate[$t] = substr($item[$k],0,$tt);

			}else{
				$item_cate[$t] = $item[$k];
			}
			$items_k[$t.'_'.$items] = $item_cate[$t];
			$t++;
		}
		return $items_k;
	}
}
?>