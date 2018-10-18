<?php
class AddTelephoneFeeForm extends Form
{
	function AddTelephoneFeeForm()
	{
		Form::Form('AddTelephoneFeeForm');
		$this->add('telephone_fee.name',new TextType(true,'invalid_name',0,255)); 
		$this->add('telephone_fee.prefix',new TextType(false,'invalid_prefix',0,255)); 
		$this->add('telephone_fee.fee',new FloatType(true,'invalid_fee','0','100000000000'));
		$this->add('telephone_fee.start_fee',new FloatType(true,'invalid_start_fee','0','100000000000'));
	}
	function on_submit()
	{
		if($this->check())
		{
			if(isset($_REQUEST['mi_telephone_fee']))
			{
				foreach($_REQUEST['mi_telephone_fee'] as $key=>$record)
				{
					$record['start_fee'] = $record['start_fee']?str_replace(',','',$record['start_fee']):0;
					$record['fee'] = $record['fee']?str_replace(',','',$record['fee']):0;
					unset($record['id']);
					DB::insert('telephone_fee',$record);
				}
			}
			Url::redirect_current();
		}
	}
	function draw()
	{
		$this->parse_layout('add');
	}
}
?>