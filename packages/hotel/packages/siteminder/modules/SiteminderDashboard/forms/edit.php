<?php
class EditSiteminderDashboardForm extends Form
{
	function EditSiteminderDashboardForm()
	{
		Form::Form('EditSiteminderDashboardForm');
	}
	function draw()
	{
	    $this->map = array();
        $this->map['customer_js'] = String::array2js(DB::fetch_all('select customer.*,customer.code || \'_\' || customer.id as id,customer.id as customer_id from customer order by code,name'));
        $this->map['extra_service_js'] = String::array2js(DB::fetch_all('select extra_service.*,extra_service.code as id from extra_service order by code '));
        $this->map['credit_card_js'] = String::array2js(DB::fetch_all('select credit_card.*,credit_card.code as id from credit_card order by code '));
        
		$this->parse_layout('edit',$this->map);
	}
}
?>