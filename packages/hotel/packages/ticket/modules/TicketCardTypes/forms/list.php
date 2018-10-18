<?php
class TicketCardTypesForm extends Form
{
	function TicketCardTypesForm()
	{
		Form::Form('TicketCardTypesForm');
		$this->link_css('skins/default/bootstrap/css/bootstrap.min.css');		
        $this->link_js('skins/default/bootstrap/js/bootstrap.js'); 	
	}
    function on_submit()
    {
        if(Url::get('search'))
        {
            $cond_ticket_name = " 1=1 ";
            $cond_ticket_area = " 1=1 ";
            $ticket_name = Url::get('ticket_name')?$cond_ticket_name.=" AND UPPER(ticket_card_types.name) LIKE('%".mb_strtoupper(Url::get('ticket_name'),'utf-8')."%')":"";
            $ticket_area = Url::get('ticket_area')!='all'?$cond_ticket_area.=" AND ticket_card_area.id=".Url::get('ticket_area')."":"";
           $_REQUEST['cond_ticket_name']=$cond_ticket_name;
           $_REQUEST['cond_ticket_area']=$cond_ticket_area;
        }
    }
	function draw()
	{
       $quantity_per_page = 50;       
       $page_no = 1;
       if(isset($_GET['page_no'])){
          $page_no = $_GET['page_no'];
       }
       if(!isset($_REQUEST['cond_ticket_name']))
       {
          $_REQUEST['cond_ticket_name']=" 1=1 ";
       }
       if(!isset($_REQUEST['cond_ticket_area']))
       {
          $_REQUEST['cond_ticket_area']=" 1=1 ";
       }
       $cond = " rnk >=".(($page_no-1)*$quantity_per_page+1)." AND rnk<".($page_no*$quantity_per_page+1);
       
       $cond_area = Url::get('area_type') ? " AND ticket_card_types.area_type='".Url::get('area_type')."'" : " AND (ticket_card_types.area_type IS NULL OR ticket_card_types.area_type='')";
       
       
       
       $sql = "SELECT DISTINCT (ticket_card_types_id) as id FROM ticket_card_wicket_detail";
       $sale_ticket_list = DB::fetch_all($sql);
       $sql = "SELECT
                  *
                  FROM (
                  SELECT
                  ticket_card_types.*,
                  row_number() over (ORDER BY ticket_card_types.hidden DESC,FN_CONVERT_TO_VN(ticket_card_types.name)) as rnk
                  FROM     
                  ticket_card_types
                  INNER JOIN ticket_card_types_details ON ticket_card_types_details.ticket_card_types_id = ticket_card_types.id
                  INNER JOIN ticket_card_area ON ticket_card_area.id = ticket_card_types_details.ticket_card_area_id
                  WHERE ticket_card_types.portal_id='".PORTAL_ID."' AND ".$_REQUEST['cond_ticket_name']." AND ".$_REQUEST['cond_ticket_area'].$cond_area."
                  ORDER BY ticket_card_types.hidden DESC,FN_CONVERT_TO_VN(ticket_card_types.name)
                  )
                  WHERE ".$cond;          
       $ticket_card_types = DB::fetch_all($sql);
       foreach($ticket_card_types as $key=>$value){
            $sql = "SELECT 
                            ticket_card_types_details.id, ticket_card_area.name 
                    FROM ticket_card_types_details 
                    INNER JOIN ticket_card_area ON ticket_card_types_details.ticket_card_area_id = ticket_card_area.id 
                    WHERE ticket_card_types_details.ticket_card_types_id=".$value['id']." ORDER BY FN_CONVERT_TO_VN(ticket_card_area.name)";
            $items = DB::fetch_all($sql);
            $ticket_card_types[$key]['items'] = $items;
            $ticket_card_types[$key]['can_change_status'] = 1; 
            $ticket_card_types[$key]['can_delete'] = 1; 
            foreach($sale_ticket_list as $k=>$v)
            {
                if($k==$key)
                {
                  $ticket_card_types[$key]['can_delete'] = 0;
                  break;   
                }
            }
       }  
       if(!isset($rows)){       
         $rows=DB::fetch("SELECT  count(*) over () as num_rows FROM ticket_card_types WHERE 1=1 ".$cond_area);
         $rows = $rows['num_rows'];
       }
       
       
       
       $sql = "SELECT * FROM ticket_card_types WHERE ticket_card_types.portal_id='".PORTAL_ID."'".$cond_area." ORDER BY FN_CONVERT_TO_VN(ticket_card_types.name)";
       $this->map['ticket_card_types_list']=DB::fetch_all($sql);
       $sql = "SELECT * FROM ticket_card_area WHERE ticket_card_area.portal_id='".PORTAL_ID."'".( Url::get('area_type') ? " AND ticket_card_area.area_type='".Url::get('area_type')."'" : "" )." ORDER BY FN_CONVERT_TO_VN(ticket_card_area.name)";
       $this->map['ticket_card_area_list']=DB::fetch_all($sql);
       $this->map['rows'] = ceil($rows/$quantity_per_page); 
       $this->map['ticket_card_types'] = $ticket_card_types;    
       $this->parse_layout('list',$this->map);
	}
}
?>