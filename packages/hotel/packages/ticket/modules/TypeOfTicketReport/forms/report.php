<?php
class TypeOfTicketReportForm extends Form
{
	function TypeOfTicketReportForm()
	{
		Form::Form('TypeOfTicketReportForm');
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
            $cond = " ticket_card_wicket.time>=".($start_date+$from_hour_temp[0]*3600+$from_hour_temp[1]*60)." AND ticket_card_wicket.time<=".($end_date+$to_hour_temp[0]*3600+$to_hour_temp[1]*60+59);
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
       $sql = "SELECT ticket_card_types.id,ticket_card_types.name,0 as total,0 as total_quantity 
       FROM ticket_card_types 
       WHERE ticket_card_types.hidden=0 AND ticket_card_types.area_type IS NULL
       ORDER BY FN_CONVERT_TO_VN(ticket_card_types.name)";
       $ticket_card_types = DB::fetch_all($sql);
       
       //foreach($ticket_card_types as $key=>$value)
//       {
//         $sql = "SELECT id,ticket_card_area_id FROM ticket_card_types_details WHERE ticket_card_types_id = ".$key;
//         $result = DB::fetch_all($sql);
//         $check = 0;
//         foreach($result as $k=>$v)
//         {
//            if($v['ticket_card_area_id']==4)
//            {
//                $check = 1;
//                break;
//            }
//         }
//         if($check==1)
//         {
//            unset($ticket_card_types[$key]);
//         }
//       }
       
       $this->map['total_quantity_final'] = 0;
       $this->map['grand_total_final'] = 0;
       
       $sql = "SELECT ticket_card_wicket_detail.id,
                      ticket_card_wicket_detail.quantity,
                      ticket_card_wicket_detail.total,
                      ticket_card_wicket.time,
                      ticket_card_types.id as ticket_card_types_id   
                    FROM ticket_card_wicket_detail 
                    INNER JOIN ticket_card_wicket ON ticket_card_wicket_detail.ticket_card_wicket_id = ticket_card_wicket.id
                    INNER JOIN ticket_card_types ON ticket_card_types.id = ticket_card_wicket_detail.ticket_card_types_id
                WHERE ".$_REQUEST['cond']." AND ticket_card_types.hidden=0 AND ticket_card_types.area_type IS NULL";
       $invoice_list = DB::fetch_all($sql);
       $items = array();
       for($i=$end_date; $i>=$start_date;$i-=86400)
       {
          $items[date('d/m/Y',$i)]=array();    
          $items[date('d/m/Y',$i)]['total_quantity'] = 0;
          $items[date('d/m/Y',$i)]['grand_total'] = 0;
          $items[date('d/m/Y',$i)]['info'] = array();
          
          foreach($ticket_card_types as $k=>$v)
          {
            $items[date('d/m/Y',$i)]['info'][$k]['quantity'] = 0;
            $items[date('d/m/Y',$i)]['info'][$k]['total'] = 0;
          }
       }
      
       foreach($invoice_list as $key=>$value)
       {
          if(isset($ticket_card_types[$value['ticket_card_types_id']]))
          {
              $items[date('d/m/Y',$value['time'])]['info'][$value['ticket_card_types_id']]['quantity']+=$value['quantity'];
              $items[date('d/m/Y',$value['time'])]['info'][$value['ticket_card_types_id']]['total']+=$value['total'];
              $ticket_card_types[$value['ticket_card_types_id']]['total_quantity']+=$value['quantity']; 
              $ticket_card_types[$value['ticket_card_types_id']]['total']+=$value['total'];
              
              $items[date('d/m/Y',$value['time'])]['total_quantity'] +=$value['quantity'];
              $items[date('d/m/Y',$value['time'])]['grand_total'] +=$value['total'];
              
              $this->map['total_quantity_final']+=$value['quantity'];
              $this->map['grand_total_final']+=$value['total'];
          }  
       }
       

       $this->map['ticket_card_types'] = $ticket_card_types;
       $this->map['items'] = $items;
       $this->parse_layout('report',$this->map);
	}
}
?>