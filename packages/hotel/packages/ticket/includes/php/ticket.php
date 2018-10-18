<?php

function get_privilege()
{ 
	$ticket_areas = DB::fetch_all('SELECT id,name,privilege FROM ticket_area where portal_id = \''.PORTAL_ID.'\' ');
	if(!User::is_admin())
    {
		$cond = ' and (';
		$i = 1;
		foreach($ticket_areas as $key=>$value)
        {
			if(User::can_edit(Portal::get_module_id($value['privilege']),ANY_CATEGORY))
            {
				if($i == 1)
                {
					$cond .= ' ticket_area.id = '.$key.'';
				}
                else
                {
					$cond .= ' OR ticket_area.id = '.$key.'';
				}
				$i++;
			}
		}
		$cond .= ')';
		if($i>1)
        {
			return $cond;
		}
        else
        {
			return false;
		}
		return $cond;
	}
    else
    {
		return false;	
	}
}

function get_ticket_area($cond='1=1',$privilege=true)
{
	if($privilege)
	{
		$cond.= get_privilege();
	}
	return DB::fetch_all('
			select 
				ticket_area.*
			from 
			 	ticket_area
			where
				 '.$cond.'
                  and portal_id = \''.PORTAL_ID.'\'
			order by 
				ticket_area.id
		',false);
	
}
function get_ticket()
{
	return DB::fetch_all('
			select 
				ticket.*
			from 
			 	ticket
			where
                portal_id = \''.PORTAL_ID.'\'
			order by 
				ticket.id
		',false);
	
}
function get_ticket_group()
{
	return DB::fetch_all('
			select 
				ticket_group.*
			from 
			 	ticket_group
			where
                portal_id = \''.PORTAL_ID.'\'
			order by 
				ticket_group.id
		',false);
	
}

function get_service()
{
	return DB::fetch_all('
			select 
				ticket_service.*
			from 
			 	ticket_service
			where
                portal_id = \''.PORTAL_ID.'\'
			order by 
				ticket_service.id
		',false);
	
}
?>