<?php
class TicketServiceDeclarationForm extends Form{
	function TicketServiceDeclarationForm()
    {
		Form::Form('TicketServiceDeclarationForm');
    	$this->add('ticket.price',new TextType(true,'price',0,255));
		$this->add('ticket.name_1',new TextType(true,'name_VN',0,255));
        $this->add('ticket.name_2',new TextType(true,'name_ENG',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
	}
	function on_submit()
    {
		if($this->check())
        {		
			if(URL::get('deleted_ids'))
            {
				$ids = explode(',',URL::get('deleted_ids'));
				foreach($ids as $id)
                {
					DB::delete('ticket_service','id='.$id.'');
				}
			}
			if(isset($_REQUEST['mi_ticket']))
            {	
				foreach($_REQUEST['mi_ticket'] as $key=>$record)
                {
					if($record['id'] and DB::exists_id('ticket_service',$record['id']))
                    {
						$ticket_id = $record['id'];
                        unset($record['no']);
						$record['portal_id'] = PORTAL_ID;
                        $record['price'] = System::calculate_number($record['price']);
                        DB::update('ticket_service',$record,'id='.$ticket_id.'');
					}
                    else
                    {
						unset($record['no']);
						unset($record['id']);
						$record['portal_id'] = PORTAL_ID;
                        $record['price'] = System::calculate_number($record['price']);
                        $id = DB::insert('ticket_service',$record);
					}
				}
			}
			Url::redirect_current();
		}
	}	
	function draw()
    {
		if(!isset($_REQUEST['mi_ticket']))
        {
			$cond = ' 1>0 ';
			$sql = '
				SELECT
					ticket_service.*
				FROM
					ticket_service where portal_id=\''.PORTAL_ID.'\' order by ticket_service.id';
			$tickets = DB::fetch_all($sql);
			//System::Debug($bars);
			$i=1;
			foreach($tickets as $key => $value)
            {
                $tickets[$key]['price'] = System::display_number($value['price']);
				$tickets[$key]['no'] = $i;
				$i++;
			}
			$_REQUEST['mi_ticket'] = $tickets;
		}	
		$this->parse_layout('edit',array());
	}
}
?>