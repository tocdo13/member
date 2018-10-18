<?php
class MapExtraServiceSiteminderForm extends Form
{
	function MapExtraServiceSiteminderForm()
	{
		Form::Form('MapExtraServiceSiteminderForm');
	}
	function draw()
	{
	    $this->map = array();
        $this->map['extra_service_js'] = String::array2js(DB::fetch_all('select extra_service.*,extra_service.code as id from extra_service order by code '));
        
		$this->parse_layout('map_extra_service',$this->map);
	}
}
?>