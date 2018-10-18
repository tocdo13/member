<?php
class EditPaymentForm extends Form{
	function EditPaymentForm(){
		Form::Form('EditPaymentForm');
		$this->add('payment.amount',new FloatType(true,'invalid_amount'));
		$this->link_js('packages/core/includes/js/multi_items.js');
	}
	function on_submit(){
		if($this->check()){
			if(isset($_REQUEST['mi_payment']) and Url::get('type') and Url::get('id')){
				foreach($_REQUEST['mi_payment'] as $key=>$record){
					if($record['amount']>0){
						$record['bill_id'] = Url::iget('id');
						$record['type'] = Url::get('type');
						$record['amount'] = System::calculate_number($record['amount']);
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
				if (isset($ids) and sizeof($ids)){
					$_REQUEST['selected_ids'].=','.join(',',$ids);
				}
			}
			if(URL::get('deleted_ids')){
				$ids = explode(',',URL::get('deleted_ids'));
				foreach($ids as $id)
				{
					DB::delete_id('payment',$id);
				}
			}
			Url::redirect_current(array('type','id'));
		}else{
			$this->error('amount','miss_payment');
			return;
		}
	}	
	function draw()
	{	
		$paging = '';
		$this->map = array();
		if(!isset($_REQUEST['mi_payment']))
		{
			$cond = 'payment.bill_id = '.Url::iget('id').' AND payment.type = '.Url::iget('type').'';
			DB::query('
				select 
					count(*) as acount
				from 
					payment
				where 
					'.$cond.'
			');
			$count = DB::fetch();
			DB::query('
				SELECT
					payment.*,ROWNUM as rownumber
				FROM 
					payment
				WHERE 
					'.$cond.'
				ORDER BY
					id ASC
			');
			$mi_payment = DB::fetch_all();
			foreach($mi_payment as $key=>$value){
				$mi_payment[$key]['amount'] = System::display_number($value['amount']);
				$mi_payment[$key]['time'] = date('H:i\' d/m/Y',$value['time']);
			}
			$_REQUEST['mi_payment'] = $mi_payment;
		}
		$this->map['payment_method_options'] = '
			<option value='.CASH.'>'.Portal::language('cash').'</option>
			<option value='.CREDIT_CARD.'>'.Portal::language('card').'</option>
		';
		if(Url::get('type')==PAY_FOR_FB){
			$this->map += DB::fetch('select total as total_amount from bar_reservation where id = '.Url::iget('id').'');
		}elseif(Url::get('type')==PAY_FOR_ROOM){
			require_once 'packages/hotel/packages/reception/modules/includes/get_reservation.php';
			require_once 'packages/hotel/packages/reception/modules/includes/lib.php';
			if($row = DB::select('reservation_room','id = '.Url::iget('id').'')){
				$rr_arr = get_reservation($row['reservation_id'],$row['id'],true);
				if(!empty($rr_arr) and isset($rr_arr['items'][$row['id']])){
					$total_amount = System::display_number($rr_arr['items'][$row['id']]['total_amount']);//System::display_number(get_room_amount());
				}else{
					$total_amount = 0;
				}
				$this->map['total_amount'] = $total_amount;
			}
		}
		$payment_type_options = '
			<option value="'.DIRECT_PAYMENT.'">'.Portal::language('direct_payment').'</option>
			<option value="'.DEPOSIT_PAYMENT.'">'.Portal::language('deposit_payment').'</option>
		';
		$this->map['payment_type_options'] = $payment_type_options;
		$this->map['default_exchange_rate'] = DB::fetch('SELECT exchange FROM currency WHERE id = \''.HOTEL_CURRENCY.'\'','exchange');
		$currencies = DB::select_all('currency','allow_payment=1','id DESC');
		$currency_options = '<option value="">'.Portal::language('select').'</option>';
		foreach($currencies as $value){
			$currency_options .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';	
		}
		$this->map['currency_options'] = $currency_options;
		$this->map['currencies'] = String::array2js($currencies);
		$this->parse_layout('edit',$this->map);
	}
}
?>