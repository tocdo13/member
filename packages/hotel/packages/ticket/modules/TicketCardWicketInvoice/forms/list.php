<?php
class TicketCardWicketInvoiceForm extends Form
{
	function TicketCardWicketInvoiceForm()
	{
		Form::Form('TicketCardWicketInvoiceForm');
		$this->link_css('skins/default/bootstrap/css/bootstrap.min.css');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');			
        $this->link_js('skins/default/bootstrap/js/bootstrap.js'); 	
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
    function on_submit()
    {
        if(Url::get('search'))
        {
            $_GET['page_no'] = 1;
            $_REQUEST['page_no']=1;
        }
    }
	function draw()
	{
	   $cond = " 1=1 ";         
        Url::get('code')?$cond.=" AND UPPER(code) LIKE('%".mb_strtoupper(Url::get('code'),'utf-8')."%')":"";
        Url::get('booking_code')?$cond.=" AND UPPER(booking_online_code) LIKE('%".mb_strtoupper(Url::get('booking_code'),'utf-8')."%')":"";
        
        if(!Url::get('customer_id') && Url::get('customer_type'))
        {
            if(Url::get('customer_type')==1)
            {
               $cond.=" AND (group_id='WALKIN' OR group_id IS NULL) "; 
            }
            else if(Url::get('customer_type')==2){
               $cond.=" AND group_id!='WALKIN' ";  
            }
        }
        
        (Url::get('customer_id') && Url::get('customer_id')!="")?$cond.=" AND UPPER(customer_name) LIKE('%".mb_strtoupper(Url::get('customer_id'),'utf-8')."%')":"";
        Url::get('from_date')?$cond.=" AND time>=".Date_Time::to_time(Url::get('from_date')):"";
        Url::get('to_date')?$cond.=" AND time<=".(Date_Time::to_time(Url::get('to_date'))+86400):"";
        
        
        Url::get('sale_name')?$cond.=" AND (UPPER(ticket_card_sales_name) LIKE('%".mb_strtoupper(Url::get('sale_name'),'utf-8')."%') OR UPPER(FN_CONVERT_TO_VN(ticket_card_sales_name)) LIKE('%".mb_strtoupper(Url::get('sale_name'),'utf-8')."%'))":"";
        Url::get('user_name')?$cond.=" AND (UPPER(seller) LIKE('%".mb_strtoupper(Url::get('user_name'),'utf-8')."%') OR UPPER(FN_CONVERT_TO_VN(seller)) LIKE('%".mb_strtoupper(Url::get('user_name'),'utf-8')."%'))":"";
       //System::debug($cond); exit();
       $quantity_per_page = 100;       
       $page_no = 1;
       if(isset($_GET['page_no'])){
          $page_no = $_GET['page_no'];
       }
       
       $total_items = DB::fetch_all("SELECT
                                      id,
                                      seller,
                                      code,
                                      booking_online_code,
                                      note,
                                      customer_name,
                                      ticket_card_sales_name,
                                      booker,
                                      total,
                                      time,
                                      group_id,
                                      rnk,
                                      ticket_card_sales_id
                                          FROM (
                                          SELECT
                                          ticket_card_wicket.id,
                                          ticket_card_wicket.seller,
                                          ticket_card_wicket.code,
                                          ticket_card_wicket.booking_online_code,
                                          ticket_card_wicket.note,
                                          UPPER(ticket_card_wicket.customer_name) as customer_name,
                                          ticket_card_wicket.booker,
                                          ticket_card_wicket.total,
                                          ticket_card_wicket.time,
                                          ticket_card_sales.name as ticket_card_sales_name,
                                          ticket_card_sales.id as ticket_card_sales_id,
                                          customer.group_id,
                                          row_number() over (ORDER BY ticket_card_wicket.time DESC) as rnk
                                          FROM     
                                          ticket_card_wicket
                                          INNER JOIN ticket_card_sales ON ticket_card_wicket.ticket_card_sales_id = ticket_card_sales.id
                                          LEFT JOIN customer ON customer.name = ticket_card_wicket.customer_name
                                          ORDER BY ticket_card_wicket.time DESC
                                          )
                                      WHERE ".$cond);
       $total_items = count($total_items);
       $cond .= " AND rnk >=".(($page_no-1)*$quantity_per_page+1)." AND rnk<".($page_no*$quantity_per_page+1);
       $sql = "SELECT
                  id,
                  seller,
                  code,
                  booking_online_code,
                  note,
                  customer_name,
                  ticket_card_sales_name,
                  booker,
                  total,
                  time,
                  group_id,
                  rnk,
                  ticket_card_sales_id
                      FROM (
                      SELECT
                      ticket_card_wicket.id,
                      ticket_card_wicket.seller,
                      ticket_card_wicket.code,
                      ticket_card_wicket.booking_online_code,
                      ticket_card_wicket.note,
                      UPPER(ticket_card_wicket.customer_name) as customer_name,
                      ticket_card_wicket.booker,
                      ticket_card_wicket.total,
                      ticket_card_wicket.time,
                      group_id,
                      ticket_card_sales.name as ticket_card_sales_name,
                      ticket_card_sales.id as ticket_card_sales_id,
                      row_number() over (ORDER BY ticket_card_wicket.time DESC) as rnk
                      FROM     
                      ticket_card_wicket
                      INNER JOIN ticket_card_sales ON ticket_card_wicket.ticket_card_sales_id = ticket_card_sales.id
                      LEFT JOIN customer ON customer.name = ticket_card_wicket.customer_name
                      ORDER BY ticket_card_wicket.time DESC
                      )
                  WHERE ".$cond;                    
       $ticket_card_wicket = DB::fetch_all($sql); 
       
       if(!isset($rows)){       
         $rows=DB::fetch("SELECT  count(*) over () as num_rows FROM ticket_card_wicket");
         $rows = $rows['num_rows'];
       }
       require_once 'packages/core/includes/utils/paging.php';
       $item_per_page = 100;
       $paging = paging($total_items,$item_per_page,10,false,'page_no',array('code','booking_code','customer','from_date','to_date','sale_name'));
       
       
       $sql = "SELECT id,TRIM(name) as name FROM ticket_card_sales";
       $ticket_card_sale = DB::fetch_all($sql);
       $this->map['ticket_card_sale'] = $ticket_card_sale;
       if(Url::get('customer_type')){
          $this->map['customer_type'] = Url::get('customer_type');
       }
       else{
          $this->map['customer_type'] = 'all';
       }
       
       $this->map['sale_name'] = Url::get('sale_name');
       
       $this->map['customer_id'] = Url::get('customer_id');
       $this->map['user_name'] = Url::get('user_name');
       
       $this->map['user_name_arr']= DB::fetch_all('SELECT 
       party.id,party.user_id 
       FROM party INNER JOIN account ON party.user_id = account.id  
       INNER JOIN portal_department ON portal_department.id = account.portal_department_id
       WHERE party.type=\'USER\' AND portal_department.department_code=\'TICKET\' ORDER BY party.user_id');
       
       $sql = "SELECT '_' || id as id, name,group_id FROM customer ORDER BY name";
       $customer_list = DB::fetch_all($sql);
       $this->map['customer_arr'] = $customer_list;
       $this->map['customer_list_js'] = String::array2js($customer_list);
       //System::debug($this->map['customer_list_js']);
       $this->map['paging'] = $paging;
       $this->map['rows'] = ceil($rows/$quantity_per_page); 
       $this->map['ticket_card_wicket'] = $ticket_card_wicket;    
       $this->parse_layout('list',$this->map);
	}
}
?>