<?php
class EditSiteminderRoomRateForm extends Form
{
	function EditSiteminderRoomRateForm()
	{
		Form::Form('EditSiteminderRoomRateForm');
	}
    function on_submit(){
        
    }
	function draw()
	{
	    $this->map = array();
        
        $this->parse_layout('edit',$this->map);
	}
}
?>