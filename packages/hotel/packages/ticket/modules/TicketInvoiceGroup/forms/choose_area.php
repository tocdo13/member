<?php
class ChooseAreaForm extends Form
{
	function ChooseAreaForm()
	{
		Form::Form('ChooseAreaForm');
	}
    function on_submit()
    {
        Url::redirect_current(array('cmd'=>'add','ticket_area_id'));
    }
	function draw()
	{
	    require_once 'packages/hotel/packages/ticket/includes/php/ticket.php';
        $this->map = array();
        $area = get_ticket_area();
        //System::debug($area);
        if(!Url::get('ticket_area_id'))
        {
            foreach($area as $k => $v)
            {
                $_REQUEST['ticket_area_id'] = $v['id'];
            }     
        }  
        $this->map['ticket_area_id_list'] = String::get_list($area);
	    //$this->map = array();
		$this->parse_layout('choose_area',$this->map);
	}	
}
?>