<?php
class TicketCardSalesForm extends Form
{
	function TicketCardSalesForm()
	{
		Form::Form('TicketCardSalesForm');
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
					$this->delete_ticket_card_sales($id);
				}
			}
			if(isset($_REQUEST['ticket_card_sales']))
			{
				$portal_id = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
				foreach($_REQUEST['ticket_card_sales'] as $key=>$record)
				{
				    if(isset($record['id']) && $record['id']!="" && DB::exists("SELECT * FROM ticket_card_sales WHERE id=".$record['id'])){
				        DB::update('ticket_card_sales',array("code"=>$record['code'],'name'=>$record['name'],'hide'=>(isset($record['hide'])?$record['hide']:0),'portal_id'=>$portal_id)," id=".$record['id']);                        
				    }   
                    else{
                        DB::insert('ticket_card_sales',array("code"=>$record['code'],'name'=>$record['name'],'hide'=>(isset($record['hide'])?$record['hide']:0),'portal_id'=>$portal_id,'area_type'=>$area_type));                        
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
	    
		if(!isset($_REQUEST['portal_id'])){
			$_REQUEST['portal_id'] = PORTAL_ID;
		}
		if(!isset($_REQUEST['ticket_card_sales']))
		{
		    $cond_area = Url::get('area_type') ? " AND ticket_card_sales.area_type='".Url::get('area_type')."'" : " AND (ticket_card_sales.area_type IS NULL OR ticket_card_sales.area_type='')";
			
            $sql = "SELECT * FROM ticket_card_sales WHERE portal_id='".PORTAL_ID."'".$cond_area;
            $ticket_card_sales = DB::fetch_all($sql);
            $sql = "SELECT DISTINCT ticket_card_sales_id as id FROM ticket_card_wicket";
            $ticket_card_sales_used = DB::fetch_all($sql);
            foreach($ticket_card_sales as $key=>$value)
            {
                $ticket_card_sales[$key]['can_delete'] = 1;
                foreach($ticket_card_sales_used as $k=>$v)
                {
                    if($key == $k)
                    {
                        $ticket_card_sales[$key]['can_delete'] = 0;
                        break;
                    }
                }
            }
			$_REQUEST['ticket_card_sales'] = $ticket_card_sales;
		}
		$this->parse_layout('edit',array());
	}
	function delete_ticket_card_sales($ticket_card_sales_id){
		if($ticket_card_sales_id and DB::exists('select id from ticket_card_sales where id = '.$ticket_card_sales_id) and User::can_delete(false,ANY_CATEGORY)){
		  DB::delete_id("ticket_card_sales",$ticket_card_sales_id);
        }
	}
}
?>