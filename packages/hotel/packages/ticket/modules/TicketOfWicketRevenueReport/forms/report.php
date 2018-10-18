<?php
class TicketOfWicketRevenueReportForm extends Form
{
	function TicketOfWicketRevenueReportForm()
	{
		Form::Form('TicketOfWicketRevenueReportForm');
		$this->link_css('skins/default/bootstrap/css/bootstrap.min.css');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');	
        $this->link_css('skins/default/datetime.css');		
        $this->link_js('skins/default/bootstrap/js/bootstrap.js'); 	
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
	}
    function on_submit()
    {
        //System::debug($_REQUEST); exit();
        $cond = "";
        if(Url::get('search'))
        {
            $start_date = Url::get('start_date')?Date_Time::to_time(Url::get('start_date')):0;
            $_REQUEST['start_date'] = $start_date;
            $from_hour = $_REQUEST['from_hour'];
            $from_hour_temp = explode(":",$from_hour);
            
            $end_date = Url::get('end_date')?Date_Time::to_time(Url::get('end_date')):Date_Time::to_time("01/01/2100");
            $_REQUEST['end_date'] = $end_date;
            $to_hour = $_REQUEST['to_hour'];
            $to_hour_temp = explode(":",$to_hour);
            $cond = " ticket_card_wicket.time>=".($start_date+$from_hour_temp[0]*3600+$from_hour_temp[1]*60)." AND ticket_card_wicket.time<=".($end_date+$to_hour_temp[0]*3600+$to_hour_temp[1]*60);
            $_REQUEST['cond'] = $cond;
        }
    }
	function draw()
	{
	   
	   if(!isset($_REQUEST['cond'])){
	      $start_date = Date_Time::to_time(date("d/m/Y")); 
          $end_date = Date_Time::to_time(date("d/m/Y")) + 86400-1; 
	      $_REQUEST['cond'] = " ticket_card_wicket.time>=".$start_date." AND ticket_card_wicket.time<=".$end_date;
          $_REQUEST['start_date'] = Date("d/m/Y");
          $_REQUEST['end_date'] = Date("d/m/Y");
          $_REQUEST['from_hour'] = "00:00";
          $_REQUEST['to_hour'] = "23:59";
	   }
       else{
         $start_date = $_REQUEST['start_date'];
         $end_date =  $_REQUEST['end_date'];
         if($start_date == 0)
         {
            $_REQUEST['start_date'] = ""; 
         } 
         else{
            $_REQUEST['start_date'] = date("d/m/Y",$_REQUEST['start_date']); 
         }
         $_REQUEST['end_date'] = date("d/m/Y",$_REQUEST['end_date']);
       }
       $sql = "SELECT * FROM ticket_card_sales ORDER BY FN_CONVERT_TO_VN(ticket_card_sales.name)";
       $ticket_card_sales = DB::fetch_all($sql);
       $this->map['ticket_card_sales'] = $ticket_card_sales;
       //$sql = "SELECT * FROM ticket_card_types ORDER BY FN_CONVERT_TO_VN(ticket_card_types.name)";
       $sql = "SELECT ticket_card_types.id,ticket_card_types.name,0 as total,0 as total_quantity FROM ticket_card_types ORDER BY FN_CONVERT_TO_VN(ticket_card_types.name)";
       $ticket_card_types = DB::fetch_all($sql);
       

       
       $sql = "SELECT ticket_card_wicket_detail.id,
                      ticket_card_wicket_detail.quantity,
                      ticket_card_wicket_detail.total,
                      ticket_card_types.id as ticket_card_types_id,
                      ticket_card_sales.id as ticket_card_sales_id,
                      ticket_card_sales.name as ticket_card_sales_name   
                    FROM ticket_card_wicket_detail 
                    INNER JOIN ticket_card_wicket ON ticket_card_wicket_detail.ticket_card_wicket_id = ticket_card_wicket.id
                    INNER JOIN ticket_card_types ON ticket_card_types.id = ticket_card_wicket_detail.ticket_card_types_id
                    INNER JOIN ticket_card_sales ON ticket_card_wicket.ticket_card_sales_id = ticket_card_sales.id
                WHERE ".$_REQUEST['cond'];
       $invoice_list = DB::fetch_all($sql);

       $items = array();
       foreach($ticket_card_sales as $key=>$value)
       {     
         foreach($ticket_card_types as $k=>$v)
         {
            $items[$value['name']]['info'][$k]['quantity'] = 0;
            $items[$value['name']]['info'][$k]['total'] = 0;
         }
       }
       foreach($invoice_list as $k=>$v)
       {
          $items[$v['ticket_card_sales_name']]['info'][$v['ticket_card_types_id']]['quantity'] += $v['quantity'];
          $items[$v['ticket_card_sales_name']]['info'][$v['ticket_card_types_id']]['total'] += $v['total'];
          $ticket_card_types[$v['ticket_card_types_id']]['total_quantity']+=$v['quantity']; 
          $ticket_card_types[$v['ticket_card_types_id']]['total']+=$v['total'];    
       }
       
       //System::debug($result);
        
       $this->map['ticket_card_types']= $ticket_card_types;
       $this->map['items']= $items;
       $this->parse_layout('report',$this->map);
	}
}
?>