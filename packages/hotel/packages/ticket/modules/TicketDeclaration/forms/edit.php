<?php
class TicketDeclarationForm extends Form{
	function TicketDeclarationForm()
    {
		Form::Form('TicketDeclarationForm');
    	$this->add('ticket.code',new TextType(true,'price',0,255));
		$this->add('ticket.name',new TextType(true,'name',0,255));
        $this->add('ticket.form',new TextType(true,'form',0,255));
        $this->add('ticket.denoted',new TextType(true,'denoted',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
        //DB::query('truncate table ticket');
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
					DB::delete('ticket','id='.$id.'');
                    DB::delete('ticket_service_grant','ticket_id='.$id.'');
				}
			}
			if(isset($_REQUEST['mi_ticket']))
            {	
				foreach($_REQUEST['mi_ticket'] as $key=>$record)
                {
					if($record['id'] and DB::exists_id('ticket',$record['id']))
                    {
						$ticket_id = $record['id'];
                        unset($record['no']);
						$record['portal_id'] = PORTAL_ID;
                        //$record['price'] = System::calculate_number($record['price']);
                        DB::update('ticket',$record,'id='.$ticket_id.'');
					}
                    else
                    {
						unset($record['no']);
						unset($record['id']);
						$record['portal_id'] = PORTAL_ID;
                        //$record['price'] = System::calculate_number($record['price']);
                        $id = DB::insert('ticket',$record);
					}
				}
			}
			Url::redirect_current();
		}
	}	
	function draw()
    {
        require_once 'packages/hotel/packages/ticket/includes/php/ticket.php';
        $this->map = array();
        
        $this->map['ticket_group'] = get_ticket_group();
        //System::debug($this->map['ticket_group']);
        	 
		$category = '';
		foreach($this->map['ticket_group'] as $id => $value){
			$category .= '<option value="'.$id.'">'.$value['name'].'</option>';	
		}
        $this->map['ticket_group'] = $category;
		if(!isset($_REQUEST['mi_ticket']))
        {
			$cond = ' 1>0 ';
			$sql = '
				SELECT
					ticket.*
				FROM
					ticket where portal_id=\''.PORTAL_ID.'\' order by ticket.id';
			$tickets = DB::fetch_all($sql);
			//System::Debug($bars);
			$i=1;
			foreach($tickets as $key => $value)
            {
                //$tickets[$key]['price'] = System::display_number($value['price']);
				$tickets[$key]['no'] = $i;
				$i++;
			}
			$_REQUEST['mi_ticket'] = $tickets;
		}	
		$this->parse_layout('edit',array()+$this->map);
	}
}
?>