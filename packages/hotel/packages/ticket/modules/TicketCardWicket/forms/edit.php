<?php
class EditTicketCardWicketForm extends Form
{
	function EditTicketCardWicketForm()
	{
		Form::Form('EditTicketCardWicketForm');
		$this->link_css('skins/default/bootstrap/css/bootstrap.min.css');	
        $this->link_js('skins/default/bootstrap/js/bootstrap.js'); 	       
	}
    function on_submit(){
        //System::debug($_REQUEST); exit();  
        if(Url::get('action')){   
           
           if(Url::get('sales_id')){ 
               
               if ($this->verifyFormToken('EditTicketCardWicketForm')) {     
                $customer_name = Url::get('customer_name')?Url::get('customer_name'):"";
                $customer_id = Url::get('customer_id')?Url::get('customer_id'):0;
                $customer_money = Url::get('customer_money')?System::calculate_number(Url::get('customer_money')):0;
                $sales_id = Url::get('sales_id')?Url::get('sales_id'):"";
                $booker = Url::get('booker')?Url::get('booker'):"";
                $phone = Url::get('phone')?Url::get('phone'):"";
                $note = Url::get('note')?Url::get('note'):"";
                $ticket_online = Url::get('ticket_online')?Url::get('ticket_online'):"";
                $sales_id = Url::get('sales_id');
                $phone = Url::get('phone')?Url::get('phone'):"";            
                
                $card_id = Url::get('barcode')?Url::get('barcode'):"";            
                
                
                              
                
                $action = Url::get('action');
                
                if($action!='export_card')
                {
                    $cash = Url::get('cash')?System::calculate_number(Url::get('cash')):0;   
                    $bank_card = Url::get('bank_card')?System::calculate_number(Url::get('bank_card')):0;          
                    $prepaid_card = Url::get('prepaid_card')?System::calculate_number(Url::get('prepaid_card')):0; 
                    
                    $total_payment = $cash+$bank_card+$prepaid_card;  
                    $total = 0;
                    $ticket_list = Url::get('ticket_list');
                    foreach($ticket_list as $k=>$v){
                        $total+=($v['quantity']*$v['price']*(100-$v['discount_percent'])/100);
                    }
                    
                    
                    
                    if($total_payment<$total){
                        echo "<script>alert('Số tiền khách thanh toán nhỏ hơn số tiền phải trả!')</script>";
                        return;
                    }
                }
                if($card_id!=""){
                    $total_prepaid = $prepaid_card;               
                    if(Url::get('id')){
                        
                        $sql = "SELECT id, amount FROM payment WHERE bill_id=".Url::get('id')." AND type='TICKET_CARD' AND payment_type_id='PREPAID_CARD'";
                        $current_prepaid_card = DB::fetch($sql);
                        $current_prepaid_card = $current_prepaid_card['amount'];
                        $total_prepaid -= $current_prepaid_card;
                    }
                    $sql = "SELECT id,current_money FROM card_payment_list WHERE card_id='".$card_id."'";
                    $card_info = DB::fetch($sql);
                    if($card_info['current_money']<$total_prepaid){
                        echo "<script>alert('Số tiền trong thẻ không đủ để thực hiện giao dịch. Xin vui lòng kiểm tra lại!')</script>";
                        return; 
                    }
                    else{
                        DB::update("card_payment_list",array("current_money"=>($card_info['current_money']-$total_prepaid))," card_id='".$card_id."'");
                    }
                }
                
                if(!Url::get('id')){
                    if(Url::get('ticket_online'))
                    {    
                        
                        $result = DB::fetch("SELECT id FROM ticket_card_wicket WHERE code='".strtoupper(trim(Url::get('ticket_online')))."' AND ticket_card_wicket.ticket_card_sales_id IS NULL");
                        if(!empty($result))
                        {                            
                            DB::update("ticket_card_wicket",array("ticket_card_sales_id"=>$sales_id,"customer_name"=>$customer_name)," code='".strtoupper(trim(Url::get('ticket_online')))."'");
                            echo "<script>
                                window.open('?page=ticket_card_wicket&cmd=print&type=export&sales_id=".$sales_id."&id=".$result['id']."','_blank','toolbar=yes,scrollbars=no,resizable=yes,top=0,left=0,width=100%,height=100%');
                                window.location.href='?page=ticket_card_wicket&cmd=edit&sales_id=".$sales_id."&portal=default'
                                </script>";               
                        }
                        else
                        {
                            Url::redirect_current(array("cmd"=>"edit","sales_id"=>$sales_id,"id"=>$ticket_card_wicket_id));
                        }
                    }
                    else
                    {                                                   
                        $ticket_card_wicket_id = DB::insert("ticket_card_wicket",array("ticket_card_sales_id"=>$sales_id,
                                                                                    "seller"=>User::id(),
                                                                                    "time"=>time(),
                                                                                    "booking_online_code"=>$ticket_online,
                                                                                    "note"=>$note,
                                                                                    "phone_booker"=>$phone,
                                                                                    "customer_id"=>$customer_id,
                                                                                    "customer_name"=>$customer_name,
                                                                                    "booker"=>$booker,
                                                                                    "customer_money"=>$customer_money,
                                                                                    "last_edit_time"=>time(),
                                                                                    "user_edit"=>User::id()));
                                                                                    
                        $code = "#".date("Y")."-".str_pad($ticket_card_wicket_id,7,"0",STR_PAD_LEFT);
                        DB::update("ticket_card_wicket",array("code"=>$code,"total"=>$total)," id=".$ticket_card_wicket_id);
                        
                        $description = "
                                          <li> ".Portal::language('code')." : ".$code."  </li>
                                          <li> ".Portal::language('seller')." : ".User::id()."  </li>
                                          <li> ".Portal::language('booking_online_code')." : ".$ticket_online."  </li>
                                          <li> ".Portal::language('note')." : ".$note."  </li>
                                          <li> ".Portal::language('customer_name')." : ".$customer_name."  </li>
                                          <li> ".Portal::language('customer_money')." : ".System::display_number($customer_money)."  </li>
                                          <li> ".Portal::language('detail')." :  </li>
                                        ";
                        foreach($ticket_list as $key=>$value){
                            $sql = "SELECT id,name FROM ticket_card_types WHERE id=".$value['id'];
                            $ticket_card_types_info = DB::fetch($sql);
                            DB::insert("ticket_card_wicket_detail",array("ticket_card_types_id"=>$value['id'],
                                                                         "ticket_card_wicket_id"=>$ticket_card_wicket_id,
                                                                         "quantity"=>$value['quantity'],
                                                                         "price"=>$value['price'],
                                                                         "discount_money"=>0,
                                                                         "discount_percent"=>$value['discount_percent'],
                                                                         "charge_rate"=>0,
                                                                         "tax_rate"=>0,
                                                                         "total"=>($value['quantity']*$value['price']*(100-$value['discount_percent'])/100)));
                            $description.="
                                                + ".$ticket_card_types_info['name']." ( ".$value['quantity']." x ".System::display_number($value['price'])."đ ) ( Discount : ".$value['discount_percent']."%)<br/>
                                            ";
                        }
                        
                        $description.="
                                        <li> ".Portal::language('Payment')." : </li>  
                                        ";
                        
                        if($cash!=0){
                            DB::insert("payment",array("bill_id"=>$ticket_card_wicket_id,"type"=>"TICKET_CARD","payment_type_id"=>"CASH","amount"=>$cash,"time"=>time(),"user_id"=>User::id(),"currency_id"=>"VND","exchange_rate"=>1,"portal_id"=>PORTAL_ID,"customer_id"=>$customer_id));
                            $description.=" + ".Portal::language('cash')." : ".System::display_number($cash)." <br/>";
                        }
                        if($bank_card!=0){
                            DB::insert("payment",array("bill_id"=>$ticket_card_wicket_id,"type"=>"TICKET_CARD","payment_type_id"=>"CREDIT_CARD","amount"=>$bank_card,"time"=>time(),"user_id"=>User::id(),"currency_id"=>"VND","exchange_rate"=>1,"portal_id"=>PORTAL_ID,"customer_id"=>$customer_id));
                            $description.=" + ".Portal::language('credit_cash')." : ".System::display_number($bank_card)." <br/>";
                        }
                        if($prepaid_card!=0){
                            DB::insert("payment",array("bill_id"=>$ticket_card_wicket_id,"type"=>"TICKET_CARD","payment_type_id"=>"PREPAID_CARD","amount"=>$prepaid_card,"time"=>time(),"user_id"=>User::id(),"currency_id"=>"VND","exchange_rate"=>1,"portal_id"=>PORTAL_ID,"customer_id"=>$customer_id,"prepaid_card_id"=>$card_id));
                            $description.=" + ".Portal::language('prepaid_card')." : ".System::display_number($prepaid_card)." <br/>";
                        }
                        
                        $sql = "SELECT id,name FROM ticket_card_sales WHERE id=".$sales_id;
                        $ticket_card_sale_info = DB::fetch($sql);
                        System::log('add','Add ticket invoice in '.$ticket_card_sale_info['name'],$description,$ticket_card_wicket_id);
                        
                        
                        unset($_REQUEST);
                        $_REQUEST['sales_id'] = $sales_id;
                        
                        if($action=='print_card'){
                            echo "<script>window.open('?page=ticket_card_wicket&cmd=print&sales_id=".$sales_id."&id=".$ticket_card_wicket_id."','_blank','toolbar=yes,scrollbars=no,resizable=yes,top=0,left=0,width=100%,height=100%');</script>";               
                        }
                        else{
                            Url::redirect_current(array("cmd"=>"edit","sales_id"=>$sales_id,"id"=>$ticket_card_wicket_id));
                        }
                    }
                }
                else
                {
                    
                    $ticket_card_wicket_id = Url::get('id');
                    $ids = "";
                    $description = "";
                    
                    $sql = "SELECT id, code FROM ticket_card_wicket WHERE id=".Url::get('id');
                    $ticket_card_wicket_info = DB::fetch($sql);
                    DB::update("ticket_card_wicket",array("ticket_card_sales_id"=>$sales_id,
                                                                                "seller"=>User::id(),
                                                                                "time"=>time(),
                                                                                "booking_online_code"=>$ticket_online,
                                                                                "note"=>$note,
                                                                                "phone_booker"=>$phone,
                                                                                "customer_id"=>$customer_id,
                                                                                "customer_name"=>$customer_name,
                                                                                "booker"=>$booker,
                                                                                "customer_money"=>$customer_money,
                                                                                "last_edit_time"=>time(),
                                                                                "user_edit"=>User::id())," id=".$ticket_card_wicket_id);
                     $description = "
                                      <li> ".Portal::language('code')." : ".$ticket_card_wicket_info['code']."  </li>
                                      <li> ".Portal::language('seller')." : ".User::id()."  </li>
                                      <li> ".Portal::language('booking_online_code')." : ".$ticket_online."  </li>
                                      <li> ".Portal::language('note')." : ".$note."  </li>
                                      <li> ".Portal::language('customer_name')." : ".$customer_name."  </li>
                                      <li> ".Portal::language('customer_money')." : ".System::display_number($customer_money)."  </li>
                                      <li> ".Portal::language('detail')." :  </li>
                                    ";
                    
                    foreach($ticket_list as $key=>$value){
                        $sql = "SELECT id,name FROM ticket_card_types WHERE id=".$value['id'];
                        $ticket_card_types_info = DB::fetch($sql);
                        if(!empty($value['detail_id'])){
                           $ids.=$value['detail_id'].",";                           
                           DB::update("ticket_card_wicket_detail",array("ticket_card_types_id"=>$value['id'],
                                                                     "ticket_card_wicket_id"=>$ticket_card_wicket_id,
                                                                     "quantity"=>$value['quantity'],
                                                                     "price"=>$value['price'],
                                                                     "discount_money"=>0,
                                                                     "discount_percent"=>$value['discount_percent'],
                                                                     "charge_rate"=>0,
                                                                     "tax_rate"=>0,
                                                                     "total"=>($value['quantity']*$value['price']*(100-$value['discount_percent'])/100))," id=".$value['detail_id']); 
                           $description.="
                                            + ".$ticket_card_types_info['name']." ( ".$value['quantity']." x ".System::display_number($value['price'])."đ )<br/>
                                        "; 
                        }
                        else{
                           $ticket_card_wicket_detail_id = DB::insert("ticket_card_wicket_detail",array("ticket_card_types_id"=>$value['id'],
                                                                     "ticket_card_wicket_id"=>$ticket_card_wicket_id,
                                                                     "quantity"=>$value['quantity'],
                                                                     "price"=>$value['price'],
                                                                     "discount_money"=>0,
                                                                     "discount_percent"=>$value['discount_percent'],
                                                                     "charge_rate"=>0,
                                                                     "tax_rate"=>0,
                                                                     "total"=>($value['quantity']*$value['price']*(100-$value['discount_percent'])/100))); 
                           $description.="
                                            + ".$ticket_card_types_info['name']." ( ".$value['quantity']." x ".System::display_number($value['price'])."đ )( Discount : ".$value['discount_percent']."%)<br/>
                                        "; 
                           $ids.=$ticket_card_wicket_detail_id.",";                                           
                        }                       
                    }
                    if(!DB::exists("SELECT id FROM payment WHERE bill_id=".$ticket_card_wicket_id." AND type = 'TICKET_CARD' AND payment_type_id='CASH'"))
                    {
                        DB::insert("payment",array("bill_id"=>$ticket_card_wicket_id,"type"=>"TICKET_CARD","payment_type_id"=>"CASH","amount"=>$cash,"time"=>time(),"user_id"=>User::id(),"currency_id"=>"VND","exchange_rate"=>1,"portal_id"=>PORTAL_ID,"customer_id"=>$customer_id));
                    }
                    else{
                        DB::update("payment",array("amount"=>$cash)," bill_id=".$ticket_card_wicket_id." AND type='TICKET_CARD' AND payment_type_id='CASH'");
                    }
                    
                    if(!DB::exists("SELECT id FROM payment WHERE bill_id=".$ticket_card_wicket_id." AND type = 'TICKET_CARD' AND payment_type_id='CREDIT_CARD'"))
                    {
                        DB::insert("payment",array("bill_id"=>$ticket_card_wicket_id,"type"=>"TICKET_CARD","payment_type_id"=>"CREDIT_CARD","amount"=>$bank_card,"time"=>time(),"user_id"=>User::id(),"currency_id"=>"VND","exchange_rate"=>1,"portal_id"=>PORTAL_ID,"customer_id"=>$customer_id));
                    }
                    else
                    {
                        DB::update("payment",array("amount"=>$bank_card)," bill_id=".$ticket_card_wicket_id." AND type='TICKET_CARD' AND payment_type_id='CREDIT_CARD'");
                    }
                    if(!DB::exists("SELECT id FROM payment WHERE bill_id=".$ticket_card_wicket_id." AND type = 'TICKET_CARD' AND payment_type_id='PREPAID_CARD'"))
                    {
                        DB::insert("payment",array("bill_id"=>$ticket_card_wicket_id,"type"=>"TICKET_CARD","payment_type_id"=>"PREPAID_CARD","amount"=>$prepaid_card,"time"=>time(),"user_id"=>User::id(),"currency_id"=>"VND","exchange_rate"=>1,"portal_id"=>PORTAL_ID,"customer_id"=>$customer_id,"prepaid_card_id"=>$card_id));
                    }
                    else
                    {
                        DB::update("payment",array("amount"=>$prepaid_card)," bill_id=".$ticket_card_wicket_id." AND type='TICKET_CARD' AND payment_type_id='PREPAID_CARD'");
                    }
                    $description.="
                                    <li> ".Portal::language('Payment')." : </li>  
                                    ";
                    $description.=" + ".Portal::language('cash')." : ".System::display_number($cash)." <br/>";
                    $description.=" + ".Portal::language('credit_cash')." : ".System::display_number($bank_card)." <br/>";
                    $description.=" + ".Portal::language('prepaid_card')." : ".System::display_number($prepaid_card)." <br/>";                
                    
                    $sql = "SELECT id,name FROM ticket_card_sales WHERE id=".$sales_id;
                    $ticket_card_sale_info = DB::fetch($sql);
                    System::log('edit','Edit ticket invoice '.$ticket_card_wicket_info['code'],$description,$ticket_card_wicket_id);
                    
                   
                    if(strlen($ids)!=""){
                        $ids = substr($ids,0,strlen($ids)-1);
                    }         
                    DB::delete("ticket_card_wicket_detail"," ticket_card_wicket_id=".$ticket_card_wicket_id." AND id NOT IN (".$ids.")");         
                    DB::update("ticket_card_wicket",array("total"=>$total)," id=".$ticket_card_wicket_id);
                    if($action=='print_card'){
                        echo "<script>window.open('?page=ticket_card_wicket&cmd=print&sales_id=".$sales_id."&id=".$ticket_card_wicket_id."','_blank','toolbar=yes,scrollbars=no,resizable=yes,top=0,left=0,width=100%,height=100%');</script>";               
                    }
                    else
                    Url::redirect_current(array("cmd"=>"edit","sales_id"=>$sales_id,"id"=>$ticket_card_wicket_id));
                }
               }
            }
        }
        //Url::redirect_current();        
    }
	function draw()
	{    
        $this->map = array();
        $sql = "SELECT id, UPPER(name) as name, price FROM ticket_card_types 
        WHERE portal_id='".PORTAL_ID."' AND (ticket_card_types.hidden IS NULL OR ticket_card_types.hidden=0 ) AND ticket_card_types.area_type IS NULL
        ORDER BY FN_CONVERT_TO_VN(ticket_card_types.name)";
        $ticket_card_types = DB::fetch_all($sql);
        $this->map['ticket_card_types'] = $ticket_card_types;
        $sql = "SELECT id, UPPER(name) as name FROM ticket_card_sales WHERE id=".Url::get('sales_id');
        $sales_name = DB::fetch($sql);
        $this->map['sales_name'] = $sales_name['name'];
        $newToken = $this->generateFormToken('EditTicketCardWicketForm');  
        $this->map['_token'] = $newToken;
        /** manh them de check thanh toan **/
        $this->map['check_payment'] = 0;
        $this->map['is_ticket_online'] = 0;  
       if(Url::get('id')){
        
            $sql = "SELECT
                        ticket_card_wicket.id,
                        ticket_card_wicket.code,
                        customer.name as customer_name,
                        ticket_card_wicket.customer_id,
                        ticket_card_wicket.booker,
                        ticket_card_wicket.phone_booker as phone,
                        ticket_card_wicket.note,
                        ticket_card_wicket.booking_online_code as ticket_online,
                        ticket_card_wicket.customer_money,
                        ticket_card_wicket.type,
                        ticket_card_wicket.select_chair_id,
                        CASE 
                            WHEN ticket_card_wicket.type = 'ORDER' THEN ticket_card_wicket.code
                            ELSE ''
                        END AS ticket_online   
                       FROM
                            ticket_card_wicket
                            LEFT JOIN customer ON ticket_card_wicket.customer_id =  customer.id
                       WHERE ticket_card_wicket.id = ".Url::get('id');
            $ticket_card_wicket = DB::fetch($sql);
            foreach($ticket_card_wicket as $key=>$value){
                $_REQUEST[$key] = $value;
                if($key=='customer_money'){
                    $_REQUEST[$key] = System::display_number($value);
                }
            }
            
            $sql = "SELECT 
                        id,
                        amount,
                        payment_type_id 
                    FROM 
                        payment 
                    WHERE bill_id = ".Url::get('id')." AND type='TICKET_CARD'";
            $payment = DB::fetch_all($sql);
            
            foreach($payment as $key=>$value){
                if($value['payment_type_id']=='CASH'){
                    $_REQUEST['cash'] = System::display_number($value['amount']);
                }
                else if($value['payment_type_id']=='CREDIT_CARD'){
                    $_REQUEST['bank_card'] = System::display_number($value['amount']);
                }
                else{
                    $_REQUEST['prepaid_card'] = System::display_number($value['amount']);
                }
                $this->map['check_payment'] = 1;
            } 
             
            if(!empty($ticket_card_wicket['type']) && $ticket_card_wicket['type']=='ORDER')
            {
                  $this->map['is_ticket_online'] = 1;   
                  $order_detail_infor   = DB::fetch_all( 'select ticket_card_wicket_detail.TICKET_TYPE_CODE as id,
                                            ticket_card_wicket_detail.quantity,
                                            ticket_card_wicket_detail.price,
                                            ticket_card_wicket_detail.discount_percent,
                                            ticket_card_wicket_detail.total,
                                            ticket_card_types.name,
                                            \'3\' as ticket_card_types_id 
                                              from ticket_card_wicket_detail
                                              inner join ticket_card_wicket on ticket_card_wicket.id=ticket_card_wicket_detail.ticket_card_wicket_id
                                              inner join ticket_card_types on ticket_card_wicket_detail.TICKET_TYPE_CODE = ticket_card_types.code
                                              where ticket_card_wicket.id=' . Url::get( 'id' ) . ' and ticket_card_wicket_detail.TICKET_TYPE_CODE=\'VE03\'' );
                  $this->map['ticket_card_wicket_detail']   = $order_detail_infor;
                  //System::debug($this->map['ticket_card_wicket_detail']);
                  $chair_selected       = DB::fetch_all( 'select ticket_chair_booked_detail.*,TICKET_SALE_AREA.id as TICKET_SALE_AREA from ticket_chair_booked_detail
                                                    inner join TICKET_CHAIR on TICKET_CHAIR.id=ticket_chair_booked_detail.chair_id 
                                                    inner join TICKET_SALE_AREA on TICKET_SALE_AREA.id=TICKET_CHAIR.TICKET_SALE_AREA_ID
                                                    where ticket_chair_booked_detail.ticket_chair_booked_id=' . $ticket_card_wicket['select_chair_id'] . '' );
                  $item                 = DB::fetch_all(
                                                 'select code as id,id as ticket_card_types_id,\'\' as quantity,\'\' as total,price, 0 as discount_percent from ticket_card_types where code=\'VE01\' or code=\'VE02\' ' 
                                            );
                  //System::debug($this->map['ticket_card_wicket_detail']);
                  
                  $this->map['ticket_card_wicket_detail'] += $item;
                  //System::debug($this->map['ticket_card_wicket_detail']);
                  foreach ( $chair_selected as $key => $value ) {
                    if ( $value['ticket_sale_area'] < 10 ) {
                      $result = DB::fetch( 'select ticket_card_wicket_detail.id,
                                                   ticket_card_wicket_detail.price,
                                                   ticket_card_wicket_detail.discount_percent,
                                                   ticket_card_types.name 
                                            from ticket_card_wicket_detail 
                                            inner join ticket_card_types on ticket_card_wicket_detail.ticket_card_types_id = ticket_card_types.id
                                            where ticket_card_wicket_detail.ticket_type_code=\'VE01\'
                                            and ticket_card_wicket_detail.ticket_card_wicket_id=' . Url::get( 'id' ) . '');  
                      $this->map['ticket_card_wicket_detail']['VE01']['quantity']++;
                      $this->map['ticket_card_wicket_detail']['VE01']['price'] = $result['price'];
                      $this->map['ticket_card_wicket_detail']['VE01']['name'] = $result['name'];
                      $this->map['ticket_card_wicket_detail']['VE01']['discount_percent'] = $result['discount_percent'];
                      $this->map['ticket_card_wicket_detail']['VE01']['total'] += ($this->map['ticket_card_wicket_detail']['VE01']['price']*$this->map['ticket_card_wicket_detail']['VE01']['quantity']*(1-$result['discount_percent']/100));
                    } else {
                      $result = DB::fetch( 'select ticket_card_wicket_detail.id,
                                                   ticket_card_wicket_detail.price,
                                                   ticket_card_wicket_detail.discount_percent,
                                                   ticket_card_types.name 
                                            from ticket_card_wicket_detail 
                                            inner join ticket_card_types on ticket_card_wicket_detail.ticket_card_types_id = ticket_card_types.id
                                            where ticket_card_wicket_detail.ticket_type_code=\'VE02\'
                                            and ticket_card_wicket_detail.ticket_card_wicket_id=' . Url::get( 'id' ) . ''); 
                      $this->map['ticket_card_wicket_detail']['VE02']['quantity']++;
                      $this->map['ticket_card_wicket_detail']['VE01']['price'] = $result['price'];
                      $this->map['ticket_card_wicket_detail']['VE01']['name'] = $result['name'];
                      $this->map['ticket_card_wicket_detail']['VE01']['discount_percent'] = $result['discount_percent'];
                      $this->map['ticket_card_wicket_detail']['VE02']['total'] += ($this->map['ticket_card_wicket_detail']['VE02']['price']*$this->map['ticket_card_wicket_detail']['VE02']['quantity']*(1-$result['discount_percent']/100));
                    }
                  }
                  if ( $this->map['ticket_card_wicket_detail']['VE01']['quantity'] == '' ) {
                    unset( $this->map['ticket_card_wicket_detail']['VE01'] );
                  }
                  if ( $this->map['ticket_card_wicket_detail']['VE02']['quantity'] == '' ) {
                    unset( $this->map['ticket_card_wicket_detail']['VE02'] );
                  }
                  if ( $this->map['ticket_card_wicket_detail']['VE03']['quantity'] == '' ) {
                    unset( $this->map['ticket_card_wicket_detail']['VE03'] );
                  }
            }
            else
            {
                $sql = "SELECT 
                            ticket_card_wicket_detail.id,
                            ticket_card_wicket_detail.ticket_card_types_id,
                            ticket_card_types.name,
                            ticket_card_wicket_detail.price,
                            ticket_card_wicket_detail.quantity,
                            ticket_card_wicket_detail.discount_percent,
                            ticket_card_wicket_detail.total
                        FROM 
                        ticket_card_wicket_detail 
                        INNER JOIN ticket_card_types ON ticket_card_wicket_detail.ticket_card_types_id = ticket_card_types.id
                        WHERE ticket_card_wicket_detail.ticket_card_wicket_id=".Url::get('id');
                $ticket_card_wicket_detail = DB::fetch_all($sql);    
                $this->map['ticket_card_wicket_detail'] = $ticket_card_wicket_detail;      
            }
       }
       else{
         
       } 
       //System::debug($_REQUEST);
       $this->parse_layout('edit',$this->map);
	}
    function generateFormToken($form) {
    
       // Khoi tao token
    	$token = md5(uniqid(microtime(), true));  
    	
    	
    	$_SESSION[$form.'_token'] = $token; 
    	
    	return $token;

    }
    function verifyFormToken($form) {
    
    	if(!isset($_SESSION[$form.'_token'])) { 
    		return false;
        }
    	
    	if(!isset($_POST['token'])) {
    		return false;
        }
    	
    	if ($_SESSION[$form.'_token'] !== $_POST['token']) {
    		return false;
        }
    	
    	return true;
    }
    function writeLog($where) {

	$ip = $_SERVER["REMOTE_ADDR"]; // Get the IP from superglobal
	$host = gethostbyaddr($ip);    // Try to locate the host of the attack
	$date = date("d M Y");
	
	// tao log voi message
	$logging = <<<LOG
		\n
		<< Start of Message >>
		There was a hacking attempt on your form. \n 
		Date of Attack: {$date}
		IP-Adress: {$ip} \n
		Host of Attacker: {$host}
		Point of Attack: {$where}
		<< End of Message >>
LOG;
        
        // open log file
		if($handle = fopen('hacklog.log', 'a')) {
		
			fputs($handle, $logging);  
			fclose($handle);           
			
		} else {  // Neu lenh ben tren khong duoc thuc hien thi gui mail
		
            	$to = 'thanhlm@tcv.vn';  
            	$subject = 'HACK ATTEMPT';
            	$header = 'From: thanhlm@tcv.vn';
            	if (mail($to, $subject, $logging, $header)) {
            		echo "Sent notice to admin.";
            	}

	      }
    }
}
?>