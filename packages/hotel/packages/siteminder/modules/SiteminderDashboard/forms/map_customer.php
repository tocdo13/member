<?php
class MapCustomerSiteminderForm extends Form
{
	function MapCustomerSiteminderForm()
	{
		Form::Form('MapCustomerSiteminderForm');
	}
	function draw()
	{
	    $this->map = array();
        $this->map['customer_js'] = String::array2js(DB::fetch_all('select customer.*,customer.code || \'_\' || customer.id as id,customer.id as customer_id from customer where portal_id=\''.PORTAL_ID.'\' order by code,name'));
		$this->parse_layout('map_customer',$this->map);
	}
}
?>