<?php
class MasterFolioEditForm extends Form{
	function MasterFolioEditForm(){
		Form::Form('MasterFolioEditForm');
        $this->link_css('packages/hotel/packages/reception/modules/MonthlyRoomReportNew/font-awesome/css/font-awesome.min.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_css('packages/hotel/packages/reception/skins/js/jquery.windows-engine.css');
        $this->link_js('packages/hotel/packages/reception/skins/js/jquery.windows-engine.js');
	}
	function on_submit()
    {
        
	}
	function draw()
    {
	   $this->map = array();
       
       $this->parse_layout('edit',$this->map);
    }
}
?>
