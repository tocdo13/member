<?php
class TicketInvoiceRevenueReportForm extends Form
{
	function TicketInvoiceRevenueReportForm()
	{
		Form::Form('TicketInvoiceRevenueReportForm');
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
        $cond = " 1=1";
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
            $from_invoice = Url::get('from_invoice')?Url::get('from_invoice'):"";
            $to_invoice = Url::get('to_invoice')?Url::get('to_invoice'):'';           
            $cond .= " AND ticket_card_wicket.time>=".($start_date+$from_hour_temp[0]*3600+$from_hour_temp[1]*60)." AND ticket_card_wicket.time<=".($end_date+$to_hour_temp[0]*3600+$to_hour_temp[1]*60);
            ($from_invoice!="")?($cond.= " AND ticket_card_wicket.id>=".$from_invoice):"";
            ($to_invoice!="")?($cond.= " AND ticket_card_wicket.id<=".$to_invoice):"";
            (Url::get('user_name') && Url::get('user_name')!='all')?$cond.=" AND (UPPER(ticket_card_wicket.seller) LIKE('%".mb_strtoupper(Url::get('user_name'),'utf-8')."%') OR UPPER(FN_CONVERT_TO_VN(ticket_card_wicket.seller)) LIKE('%".mb_strtoupper(Url::get('user_name'),'utf-8')."%'))":"";
            $card_sale_id =  Url::get('ticket_card_sales')?Url::get('ticket_card_sales'):"";
            ($card_sale_id!="all")?($cond.= " AND ticket_card_sales.id=".$card_sale_id):""; 
            $_REQUEST['from_invoice'] = $from_invoice;
            $_REQUEST['to_invoice'] = $to_invoice;
            $_REQUEST['ticket_card_sales'] = $card_sale_id;
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
          $_REQUEST['ticket_card_sales']='all';
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
       
       $sql = "SELECT ticket_card_types.id,ticket_card_types.name,0 as total,0 as total_quantity FROM ticket_card_types ORDER BY FN_CONVERT_TO_VN(ticket_card_types.name)";
       $ticket_card_types = DB::fetch_all($sql);
       
       $str_card_type = "";
       
       foreach($ticket_card_types as $key=>$value)
       {
         //$sql = "SELECT id,ticket_card_area_id FROM ticket_card_types_details WHERE ticket_card_types_id = ".$key;
         //$result = DB::fetch_all($sql);
         //$check = 0;
//         foreach($result as $k=>$v)
//         {
//            if($v['ticket_card_area_id']==4)
//            {
//                $check = 1;
//                break;
//            }
//         }
//         if($check==0)
//         {
            $str_card_type.=$key.",";
         //}
       }
       $items = array();
       if(strlen($str_card_type)>0)
       {
          $str_card_type = substr($str_card_type,0,strlen($str_card_type)-1);
          $sql = "SELECT ticket_card_wicket_detail.id,
                      ticket_card_wicket_detail.quantity,
                      ticket_card_wicket_detail.total,
                      ticket_card_wicket.time,
                      ticket_card_wicket.seller,
                      ticket_card_wicket.id as ticket_card_wicket_id,
                      ticket_card_types.id as ticket_card_types_id,
                      ticket_card_types.name as ticket_card_types_name,
                      ticket_card_sales.id as ticket_card_sales_id,
                      ticket_card_sales.name as ticket_card_sales_name   
                    FROM ticket_card_wicket_detail 
                    INNER JOIN ticket_card_wicket ON ticket_card_wicket_detail.ticket_card_wicket_id = ticket_card_wicket.id
                    INNER JOIN ticket_card_types ON ticket_card_types.id = ticket_card_wicket_detail.ticket_card_types_id
                    INNER JOIN ticket_card_sales ON ticket_card_sales.id = ticket_card_wicket.ticket_card_sales_id
                WHERE ".$_REQUEST['cond']." AND ticket_card_types.id IN(".$str_card_type.") ORDER BY ticket_card_wicket.id";
          $items = DB::fetch_all($sql);
       }
       
       
       $sql = "SELECT * FROM payment WHERE payment.type='TICKET_CARD'";
       $payment_list = DB::fetch_all($sql);
       //System::debug($payment_list);
       $this->map['total_invoice'] = 0;
       $this->map['total_quantity'] = 0;
       $j = 1;
            $key = array_keys($items);
             $total = 0;
             $total_quantity = 0;
              for($i=0 ; $i< count($items); $i++){
                    $current = current($items);
                  $next = next($items);
                  if($current['ticket_card_wicket_id'] == $next['ticket_card_wicket_id']){
                    $total += $next['total'];
                    $total_quantity+=$next['quantity'];
                      $j++;
                  }
                  else{
                      $total += $items[$key[$i-$j+1]]['total'];
                      $total_quantity+= $items[$key[$i-$j+1]]['quantity'];
                      $items[$key[$i-$j+1]]['count'] = $j;
                      $items[$key[$i-$j+1]]['total_final'] = $total;
                      $this->map['total_invoice']+=$total;
                      $this->map['total_quantity']+=$total_quantity;
                      $total = 0;
                      $total_quantity = 0;
                      $j = 1;
                  }
              } 
       $this->map['total_cash'] = 0;
       $this->map['total_credit_card'] = 0;
       $this->map['total_prepaid_card'] = 0;       
       foreach($items as $key=>$value)
       {
          if(isset($value['count'])){
            $payment_type = array("CREDIT_CARD"=>0,'CASH'=>0,'PREPAID_CARD'=>0);
            foreach($payment_list as $k=>$v)
              {
                if($v['bill_id']==$value['ticket_card_wicket_id'])
                {
                    if($v['payment_type_id']=='CREDIT_CARD')
                    {
                       $payment_type['CREDIT_CARD']+=$v['amount']; 
                       $this->map['total_credit_card']+=$v['amount']; 
                    }
                    else if($v['payment_type_id']=='CASH')
                    {
                      $payment_type['CASH']+=$v['amount']; 
                      $this->map['total_cash']+=$v['amount'];  
                    }
                    else{
                      $payment_type['PREPAID_CARD']+=$v['amount'];  
                      $this->map['total_prepaid_card']+= $v['amount'];  
                    }
                }     
              }
              $items[$key]['payment_info'] = $payment_type;
          }         
       }
       //System::debug($items);
       
       $this->map['user_name'] = Url::get('user_name');
       
       $this->map['user_name_arr']= DB::fetch_all('SELECT 
       party.id,party.user_id 
       FROM party INNER JOIN account ON party.user_id = account.id  
       INNER JOIN portal_department ON portal_department.id = account.portal_department_id
       WHERE party.type=\'USER\' AND portal_department.department_code=\'TICKET\' ORDER BY party.user_id');
       
       $this->map['items']= $items;
       $sql = "SELECT * FROM ticket_card_sales ORDER BY FN_CONVERT_TO_VN(ticket_card_sales.name)";
       $ticket_card_sales = DB::fetch_all($sql);
       $this->map['ticket_card_sales']=$ticket_card_sales;
       
       //System::debug($result);      
       $this->parse_layout('report',$this->map);
	}
}
?>