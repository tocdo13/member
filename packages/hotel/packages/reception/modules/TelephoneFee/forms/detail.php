<?php
class TelephoneFeeForm extends Form
{
	function TelephoneFeeForm()
	{
		Form::Form("TelephoneFeeForm");
		$this->add('id',new IDType(true,'object_not_exists','telephone_fee'));
	}
	function draw()
	{
		DB::query('
			select 
				telephone_fee.id
				,telephone_fee.name ,telephone_fee.prefix ,telephone_fee.fee 
			from 
			 	telephone_fee
			where
				telephone_fee.id = '.URL::get('id'));
		if($row = DB::fetch())
		{		  

			$row['fee'] = System::display_number($row['fee']); 
		}
		$this->parse_layout('detail',$row);
	}
}
?>