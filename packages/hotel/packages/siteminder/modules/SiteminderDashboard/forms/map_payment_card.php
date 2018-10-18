<?php
class MapPaymentCardSiteminderForm extends Form
{
	function MapPaymentCardSiteminderForm()
	{
		Form::Form('MapPaymentCardSiteminderForm');
	}
	function draw()
	{
	    $this->map = array();
        $this->map['credit_card_js'] = String::array2js(DB::fetch_all('select credit_card.*,credit_card.code as id from credit_card order by code '));
        
		$this->parse_layout('map_payment_card',$this->map);
	}
}
?>