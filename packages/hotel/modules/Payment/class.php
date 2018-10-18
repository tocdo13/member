<?php 
class Payment extends Module{
	function Payment($row){
		Module::Module($row);
		if((Url::get('cmd')=='add_payment' || (Url::get('cmd')=='edit' && Url::get('p_id'))) && System::calculate_number(Url::get('amount'))>0){
			$p_id = 0;$rr_id='';$rt_id='';$bank_acc='';
			$des= Url::get('description')?Url::get('description'):'';
			$time = time();
			$pmt_id = Url::get('payment_type_id')?Url::get('payment_type_id'):'';
			$pmt_name = Url::get('payment_type_name')?Url::get('payment_type_name'):'';
			$crd_id = Url::get('credit_card_id')?Url::get('credit_card_id'):'';
			$crd_name = Url::get('credit_card_name')?Url::get('credit_card_name'):'';
			$currency_id = Url::get('currency_id')?Url::get('currency_id'):'';
			$currency_name = Url::get('currency')?Url::get('currency'):'';
			$amount = Url::get('amount')?System::calculate_number(Url::get('amount')):0;
			$count = Url::get('count')?Url::get('count'):1;
			$exchange_rate = Url::get('exchange_rate')?Url::get('exchange_rate'):1;
			$arr = array('count'=>$count,'description'=>$des,'payment_type_id'=>$pmt_id,'credit_card_id'=>$crd_id,'currency_id'=>$currency_id,'time'=>$time,'amount'=>$amount,'bill_id'=>Url::get('id'),'type'=>'BAR','exchange_rate'=>$exchange_rate);
			if($pmt_id=='BANK'){
				$bank_acc = Url::get('bank_acc')?Url::get('bank_acc'):0;
				$arr += array('acc'=>$bank_acc);
			}else if($pmt_id=='ROOM'){
				$rr_id = Url::get('rr_id')?Url::get('rr_id'):'';
				$rt_id = Url::get('rt_id')?Url::get('rt_id'):'';
				$arr += array('reservation_room_id'=>$rr_id,'reservation_traveller_id'=>$rt_id);
			}
			if(Url::get('cmd')=='add_payment'){
				$p_id = DB::insert('payment',$arr);
			}else if(Url::get('cmd')=='edit' && Url::get('p_id')){
				DB::update('payment',$arr,' id='.Url::get('p_id').' AND type=\'BAR\' AND bill_id='.Url::get('id').'');	
			}
			$items = $this->addPayment($p_id,$des,$time,$pmt_id,$pmt_name,$crd_id,$crd_name,$currency_id,$currency_name,$amount,$count,$exchange_rate,$rr_id,$rt_id,$bank_acc);
			echo $items;
			exit();
		}
		if(Url::get('cmd')=='delete' && Url::get('p_id'))
        {
			$count = Url::get('count')?Url::get('count'):1;
			DB::delete('payment',' id='.Url::get('p_id').' AND type=\'BAR\' AND bill_id='.Url::get('id').'');
			exit();	
		} 
		if(Url::get('cmd')=='print'){
			if(User::can_view(false,ANY_CATEGORY)){
				require_once 'forms/print.php';
				$this->add_form(new PrintPaymentForm());
			}else{
				URL::access_denied();
			}
		}else if(Url::get('id') and Url::get('type')){
			if(User::can_view(false,ANY_CATEGORY)){
				require_once('cache/config/payment.php');
				require_once 'forms/edit.php';
				$this->add_form(new EditPaymentForm());
			}else{
				URL::access_denied();
			}
		}
	}
	static function addPayment($p_id=0,$des,$time,$pmt_id,$pmt_name,$crd_id,$crd_name,$currency_id,$currency_name,$amount,$count,$exchange_rate,$rr_id,$rt_id,$bank_acc){
		$items = '<table width="650" cellpadding="3" class="bound_add_pmt" id="table_'.$pmt_id.'_'.$count.'">';
		$items .='<tr class="add_pmt" style="text-align:center;" id="tr_'.$pmt_id.'_'.$count.'" onclick="itemsSelected(\''.$pmt_id.'\',\''.$count.'\','.$p_id.');">';
		$items .= '<td style="width:20%;display:none;" id="payment_id_'.$pmt_id.'_'.$count.'">'.$p_id.'</td>';
		$items .= '<td style="width:20%;display:none;" id="rr_id_'.$pmt_id.'_'.$count.'">'.$rr_id.'</td>';
		$items .= '<td style="width:20%;display:none;" id="rt_id_'.$pmt_id.'_'.$count.'">'.$rt_id.'</td>';
		$items .= '<td style="width:20%;display:none;" id="bank_acc_'.$pmt_id.'_'.$count.'">'.$bank_acc.'</td>';
		$items .= '<td style="width:20%;display:none;" id="exchange_rate_'.$pmt_id.'_'.$count.'">'.$exchange_rate.'</td>';
		$items .= '<td style="width:20%;" id="description_'.$pmt_id.'_'.$count.'">'.$des.'</td>';
		$items .= '<td style="width:20%;" id="time_'.$pmt_id.'_'.$count.'">'.date('d/m/Y',$time).'</td>';
		$items .= '<td style="display:none;" id="pmt_id_'.$pmt_id.'_'.$count.'">'.$pmt_id.'</td>';
		$items .= '<td style="width:15%;" id="pmt_name_'.$pmt_id.'_'.$count.'">'.$pmt_name.'</td>';
		$items .= '<td style="width:15%;" id="crd_name_'.$pmt_id.'_'.$count.'">'.$crd_name.'</td>';
		$items .= '<td style="display:none;" id="crd_id_'.$pmt_id.'_'.$count.'">'.$crd_id.'</td>';
		$items .= '<td style="width:15%;" id="currency_name_'.$pmt_id.'_'.$count.'">'.$currency_name.'</td>';
		$items .= '<td style="display:none;" id="currency_id_'.$pmt_id.'_'.$count.'">'.$currency_id.'</td>';
		$items .= '<td style="width:15%;" id="amount_'.$pmt_id.'_'.$count.'">'.System::display_number($amount).'</td>';
		$items .= '<td onclick="deletePayment(\''.$pmt_id.'_'.$count.'\','.$p_id.');"><img align="left" src="packages/core/skins/default/images/buttons/delete.gif" title="delete"></td>';
		$items .= '</tr></table>';
		return $items;
	}
}
?>
