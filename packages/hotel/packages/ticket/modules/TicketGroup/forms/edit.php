<?php
class TicketGroupForm extends Form{
	function TicketGroupForm()
    {
		Form::Form('TicketGroupForm');
		$this->add('ticket.name',new TextType(true,'name',0,255));
        $this->add('ticket.prefix',new TextType(true,'prefix',0,255));
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
					DB::delete('ticket_group','id='.$id.'');
				}
			}
			if(isset($_REQUEST['mi_ticket']))
            {	
				foreach($_REQUEST['mi_ticket'] as $key=>$record)
                {
                    $record['name'] = strtoupper($record['name']);
                    $record['prefix'] = strtoupper($record['prefix']);
					if($record['id'] and DB::exists_id('ticket_group',$record['id']))
                    {
						$ticket_id = $record['id'];
                        unset($record['no']);
						$record['portal_id'] = PORTAL_ID;
                        DB::update('ticket_group',$record,'id='.$ticket_id.'');
					}
                    else
                    {
						unset($record['no']);
						unset($record['id']);
						$record['portal_id'] = PORTAL_ID;
                        $id = DB::insert('ticket_group',$record);
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
					ticket_group.*
				FROM
					ticket_group where portal_id=\''.PORTAL_ID.'\' order by ticket_group.id';
			$tickets = DB::fetch_all($sql);
			//System::Debug($bars);
			$i=1;
			foreach($tickets as $key => $value)
            {
				$tickets[$key]['no'] = $i;
				$i++;
			}
			$_REQUEST['mi_ticket'] = $tickets;
            //System::debug($tickets);
		}	
		$this->parse_layout('edit',array());
	}
}
?>