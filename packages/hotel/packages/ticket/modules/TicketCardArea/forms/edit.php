<?php
class TicketCardAreaForm extends Form
{
	function TicketCardAreaForm()
	{
		Form::Form('TicketCardAreaForm');
		$this->add('ticket_card_area.name',new TextType(true,'invalid_name',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
	}
	function on_submit()
	{
		//System::debug($_REQUEST); exit();
        if($this->check())
		{
		    $area_type = Url::get('area_type') ? Url::get('area_type') : "";
			if(URL::get('deleted_ids'))
			{
				$ids = explode(',',URL::get('deleted_ids'));
				foreach($ids as $id)
				{
					$this->delete_ticket_card_area($id);
				}
			}
			if(isset($_REQUEST['ticket_card_area']))
			{
				$portal_id = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
				foreach($_REQUEST['ticket_card_area'] as $key=>$record)
				{
				    if(isset($record['id']) && $record['id']!="" && DB::exists("SELECT * FROM ticket_card_area WHERE id=".$record['id'])){
				        DB::update('ticket_card_area',array("code"=>$record['code'],'name'=>$record['name'],'hide'=>(isset($record['hide'])?$record['hide']:0),'portal_id'=>$portal_id,"price_in_week"=>System::calculate_number($record['price_in_week']),"price_week_end"=>System::calculate_number($record['price_week_end']),'is_exit'=>(isset($record['is_exit'])?$record['is_exit']:0))," id=".$record['id']);                        
				    }   
                    else{
                        DB::insert('ticket_card_area',array("code"=>$record['code'],'name'=>$record['name'],'hide'=>(isset($record['hide'])?$record['hide']:0),"price_in_week"=>System::calculate_number($record['price_in_week']),"price_week_end"=>System::calculate_number($record['price_week_end']),'portal_id'=>$portal_id,'is_exit'=>(isset($record['is_exit'])?$record['is_exit']:0),'area_type'=>$area_type));                        
                    }
				}
				if (isset($ids) and sizeof($ids))
				{
				}
			}
			Url::redirect_current(array('portal_id','area_type'));
		}
	}	
	function draw()
	{
	    $cond_area = Url::get('area_type') ? " AND ticket_card_area.area_type='".Url::get('area_type')."'" : " AND (ticket_card_area.area_type IS NULL OR ticket_card_area.area_type='')"; 
        
		if(!isset($_REQUEST['portal_id'])){
			$_REQUEST['portal_id'] = PORTAL_ID;
		}
		if(!isset($_REQUEST['ticket_card_area']))
		{
		      
			$sql = "SELECT * FROM ticket_card_area WHERE portal_id='".PORTAL_ID."'".$cond_area;
            $ticket_card_area = DB::fetch_all($sql);
            $sql = "SELECT DISTINCT ticket_card_area_id as id FROM ticket_card_detail";
            $ticket_card_area_used = DB::fetch_all($sql);
            foreach($ticket_card_area as $key=>$value)
            {
                $ticket_card_area[$key]['can_delete'] = 1;
                foreach($ticket_card_area_used as $k=>$v)
                {
                    if($key == $k)
                    {
                        $ticket_card_area[$key]['can_delete'] = 0;
                        break;
                    }
                }
                $ticket_card_area[$key]['price_in_week'] = System::display_number($value['price_in_week']);
                $ticket_card_area[$key]['price_week_end'] = System::display_number($value['price_week_end']);
            }
			$_REQUEST['ticket_card_area'] = $ticket_card_area;
		}
		$this->parse_layout('edit',array());
	}
	function delete_ticket_card_area($ticket_card_area_id){
		if($ticket_card_area_id and DB::exists('select id from ticket_card_area where id = '.$ticket_card_area_id) and User::can_delete(false,ANY_CATEGORY)){
			DB::delete_id('ticket_card_area',$ticket_card_area_id);
		}
	}
}
?>