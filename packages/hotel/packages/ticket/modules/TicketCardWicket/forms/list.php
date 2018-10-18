<?php
class TicketCardWicketForm extends Form
{
	function TicketCardWicketForm()
	{
		Form::Form('TicketCardWicketForm');
		$this->link_css('skins/default/bootstrap/css/bootstrap.min.css');		
        $this->link_js('skins/default/bootstrap/js/bootstrap.js'); 	
	}

	function draw()
	{ 
	   $this->map = array();
       $sql = "SELECT id, UPPER(name) as name FROM ticket_card_sales";
       $ticket_card_sales = DB::fetch_all($sql);
       $this->map['ticket_card_sales'] = $ticket_card_sales;
       $this->parse_layout('list',$this->map);
	}
}
?>