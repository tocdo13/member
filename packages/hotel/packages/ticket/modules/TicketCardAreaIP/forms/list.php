<?php
class TicketCardAreaIPForm extends Form
{
	function TicketCardAreaIPForm()
	{
		Form::Form('TicketCardAreaIPForm');
		$this->link_css('skins/default/bootstrap/css/bootstrap.min.css');		
        $this->link_js('skins/default/bootstrap/js/bootstrap.js'); 	
	}

	function draw()
	{
       $quantity_per_page = 10;       
       $page_no = 1;
       if(isset($_GET['page_no'])){
          $page_no = $_GET['page_no'];
       }
       $cond_area = Url::get('area_type') ? " AND ticket_card_area.area_type='".Url::get('area_type')."'" : "";

       $cond = " rnk >=".(($page_no-1)*$quantity_per_page+1)." AND rnk<".($page_no*$quantity_per_page+1);
       $sql = "SELECT
                  *
                  FROM (
                  SELECT
                  ticket_card_area.*,
                  row_number() over (ORDER BY ticket_card_area.id) as rnk
                  FROM     
                  ticket_card_area
                  WHERE ticket_card_area.portal_id='".PORTAL_ID."'".$cond_area."
                  ORDER BY ticket_card_area.id
                  )
                  WHERE ".$cond;          
       $ticket_card_area = DB::fetch_all($sql);
       foreach($ticket_card_area as $key=>$value){
            $sql = "SELECT * FROM ticket_card_area_ip WHERE ticket_card_area_id=".$value['id']." ORDER BY cast(REPLACE(ip,'.','') as int)";
            $items = DB::fetch_all($sql);
            $ticket_card_area[$key]['items'] = $items;
            $ticket_card_area[$key]['can_change_status'] = 1; 
       }  
       if(!isset($rows)){       
         $rows=DB::fetch("SELECT  count(*) over () as num_rows FROM ticket_card_area WHERE 1=1 ".$cond_area);
         $rows = $rows['num_rows'];
       }
       
       $this->map['rows'] = ceil($rows/$quantity_per_page); 
       $this->map['ticket_card_area'] = $ticket_card_area;    
       $this->parse_layout('list',$this->map);
	}
}
?>