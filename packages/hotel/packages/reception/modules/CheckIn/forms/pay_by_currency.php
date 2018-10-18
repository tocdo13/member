<?php
class PayByCurrencyReservationForm extends Form
{
	function PayByCurrencyReservationForm()
	{
		Form::Form('PayByCurrencyReservationForm');
	}
	function on_submit(){
		$currencies = DB::select_all('currency','allow_payment=1','name');
		$check = false;
		foreach($currencies as $key=>$value){
			if(Url::get('currency_'.$value['id'])){
				$check = true;
				if($row=DB::fetch('select * from pay_by_currency where bill_id='.Url::iget('id').' and currency_id=\''.$value['id'].'\' and type=\'RESERVATION\'')){
					DB::update('pay_by_currency',array('bill_id'=>Url::iget('id'),'currency_id'=>$value['id'],'amount'=>str_replace(',','',Url::get('currency_'.$value['id']))),'id='.$row['id']);
				}else{
					DB::insert('pay_by_currency',array('bill_id'=>Url::iget('id'),'currency_id'=>$value['id'],'amount'=>str_replace(',','',Url::get('currency_'.$value['id'])),'type'=>'RESERVATION','exchange_rate'=>$value['exchange']));
				}
			}
		}
		if($check){
			echo '<script>window.close();</script>';
			exit();
		}else{
			$this->error('currency','you_have_to_input_amount');
		}
	}
	function draw()
	{	
		//thong tin cuoi cung cua check out
		$total = Url::get('total_amount');
		$total=System::display_number_report($total);
		$this->map['total'] = $total;
		$currencies = DB::select_all('currency','allow_payment=1 AND id<>\''.HOTEL_CURRENCY.'\'','name');
		$this->map['currencies'] = $currencies;
		if($items=DB::fetch_all('select * from pay_by_currency where bill_id='.Url::iget('id').' and type=\'RESERVATION\'')){
			foreach($items as $key=>$value){
				$_REQUEST['currency_'.$value['currency_id']] = number_format($value['amount'],2);	
			}
		}else{
			$_REQUEST['currency_USD'] = $total;
		}
		foreach($currencies as $key=>$value){
			if(!isset($_REQUEST['currency_'.$value['id']])){
				$_REQUEST['currency_'.$value['id']] = 0;
			}
		}
		$this->parse_layout('pay_by_currency',$this->map);
	}
}
?>