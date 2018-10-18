<?php
class ReviewDebtCustomerForm extends Form
{
	function ReviewDebtCustomerForm()
	{
		Form::Form('ReviewDebtCustomerForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
          $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
	}
	function draw()
	{		
	    if(Url::get('do_search'))
        {
            $cond1 = (Url::get('date_from')?' AND folio.create_time >=\''.Date_Time::to_time(Url::get('date_from')).'\'':'').'
			'.(Url::get('date_to')?' AND folio.create_time <=\''.(Date_Time::to_time(Url::get('date_to')) + (86400-1)).'\'':'').'';
			$cond2 = (Url::get('date_from')?' AND payment.time >=\''.Date_Time::to_time(Url::get('date_from')).'\'':'').'
			'.(Url::get('date_to')?' AND payment.time <=\''.(Date_Time::to_time(Url::get('date_to')) + (86400-1)).'\'':'').'';
			$cond = '';
            
			
			$cond .= ' and folio.portal_id = \''.PORTAL_ID.'\'';	
            $cond_payment_detail = '  and payment.portal_id = \''.PORTAL_ID.'\'';
			
            
/*-----------------------------T?ng S? du khách n? tru?c ngày tìm ------------------------- */

            //$cond_baseline = ' AND folio.create_time <=\''.(Date_Time::to_time(Url::get('date_from')) + (86400-1)).'\'';
            $items_baseline= $this -> getPayment();
            // T?ng s? ti?n khách dã tr? Tính d?n tru?c ngày (date_from)
            $cond_debit_to_date = '1=1 ';
            $date_from=explode('/',Url::get('date_from'));
            $date_to=explode('/',Url::get('date_to'));
            $cond_debit_to_date .= ' AND date_in <\''.Date_Time::to_orc_date(Url::get('date_from')).'\'';
            $cond_debit_to_date .= ' AND portal_id = \''.PORTAL_ID.'\' ';
            if(Url::get('customer_id'))
             $cond_debit_to_date .= ' AND customer_id ='.Url::get('customer_id');
           
            $item_debit_before_date=  $this->TotalDebitCustomerBefore($cond_debit_to_date);
            //=> S? du Ð?u kì s? b?ng khách n? tru?c ngày Tr? di T?ng s? khách dã tr?
            $items_debit_balance_vnd = (isset($items_baseline['VND'])?$items_baseline['VND']['amount']:0) - (isset($item_debit_before_date['VND'])?$item_debit_before_date['VND']['sum_debit']:0);
            $items_debit_balance_usd = (isset($items_baseline['USD'])?$items_baseline['USD']['amount']:0)- (isset($item_debit_before_date['USD'])?$item_debit_before_date['USD']['sum_debit']:0);
/*----------------------------- S? ti?n khách n? T? Folio, Payment---------------------------*/            
            $itemsdebt = $this -> getPaymentDetail($cond2, $cond_payment_detail);
            foreach($itemsdebt as $k => $v)
            {
                if($itemsdebt[$k]['type']=='RESERVATION')
                    $itemsdebt[$k]['description'] = Portal::language('room_charge');
                elseif($itemsdebt[$k]['type']=='BAR')
                    $itemsdebt[$k]['description'] = Portal::language('bar');    
                    //unset($itemsdebt[$k]['type']);
                $itemsdebt[$k]['recode'] =  $v['reservation_id'];
                    unset($itemsdebt[$k]['reservation_id']);
                $itemsdebt[$k]['folio_number'] = $v['folio_id'];
                    unset($itemsdebt[$k]['folio_id']);
                $itemsdebt[$k]['price'] = $v['amount'];
                    unset($itemsdebt[$k]['amount']);
                $itemsdebt[$k]['time'] = intval($v['time']);
                $itemsdebt[$k]['debt']=1;
            }
            $total_debit_asc_vnd=0;
            $total_debit_asc_usd=0;
            foreach($itemsdebt as $k => $v){
                if($v['currency_id']=='VND'){
                    $total_debit_asc_vnd+=$v['price'];
                }else{
                    $total_debit_asc_usd+=$v['price'];
                }
            }
/*------------------------------- S? ti?n khách tr? ----------------------------------------------------*/  
            $cond1  =' AND date_in >=\''.Date_Time::to_orc_date(Url::get('date_from')).'\' ';
            $cond1 .=' AND date_in <=\''.Date_Time::to_orc_date(Url::get('date_to')).'\' ';          
            $itemsDesc = $this -> DesDebtCutomer($cond1);
            $total_debit_desc_vnd=0;
            $total_debit_desc_usd=0;
            foreach($itemsDesc as $k => $v)
            {
                if($v['currency_id']=='VND'){
                    $total_debit_desc_vnd+=$v['price'];
                }else{
                    $total_debit_desc_usd+=$v['price'];
                }
            }
            foreach($itemsDesc as $k => $v)
            {
                $itemsDesc['debit_'.$k] = $v;
                $itemsDesc['debit_'.$k]['time'] = $v['date_in'];
                unset($itemsDesc[$k]);
            }
            $items = $itemsdebt + $itemsDesc;
            foreach($items as $key=>$value){
                $items[$key]['debit_vnd']=0;
                $items[$key]['debit_usd']=0;
                if(isset($value['date_in'])){
                    $items[$key]['time']=Date_Time::convert_orc_date_to_date($value['time'],'/');
                }else{
                   $items[$key]['time']=date('d/m/Y',$value['time']); 
                }
            }
/*--------------------------------- Sap xep Giam dan --------------------------------------------*/
            usort($items,array($this,'sortByTime'));
            $debit_vnd=$items_debit_balance_vnd;
            $debit_usd=$items_debit_balance_usd;
            
            foreach($items as $key=>$value){
                if(isset($value['debt'])){
                    if($value['currency_id']=='VND'){
                        $debit_vnd+=$value['price'];
                        $items[$key]['debit_vnd']= $debit_vnd;
                    }else{
                        $debit_usd+=$value['price'];
                        $items[$key]['debit_usd']= $debit_usd;
                    }
                    
                }else{
                    if($value['currency_id']=='VND'){
                        $debit_vnd-=$value['price'];
                        $items[$key]['debit_vnd']= $debit_vnd;
                    }else{
                        $debit_usd-=$value['price'];
                        $items[$key]['debit_usd']= $debit_usd;
                    }
                }
                
            } 
            
            if(Url::get('customer_id'))        
                $list_customer =DB::fetch('select id,name from customer where id='.Url::get('customer_id'));
            else
                $list_customer = array('name'=>Portal::language('all'));
            $this->parse_layout('report',$items+
                                           array('customer_name'=>$list_customer['name'],
                                                 'items_baseline_vnd'=>$items_debit_balance_vnd,
                                                 'items_baseline_usd'=>$items_debit_balance_usd,
                                                 'total_debit_asc_vnd'=>System::display_number($total_debit_asc_vnd),
                                                 'total_debit_asc_usd'=>System::display_number($total_debit_asc_usd),
                                                 'total_debit_desc_vnd'=>System::display_number($total_debit_desc_vnd),
                                                 'total_debit_desc_usd'=>System::display_number($total_debit_desc_usd),
                                                 'total_final_debit_vnd'=>System::display_number($debit_vnd),
                                                 'total_final_debit_usd'=>System::display_number($debit_usd),
                                                 'items' => $items
                                                )
                                 );
        }else
        {
            $_REQUEST['date_from'] = date('01/m/Y');
            $_REQUEST['date_to'] = date('d/m/Y');		  
            $this->map = array();
            if(!Url::get('portal_id'))
			     $_REQUEST['portal_id'] = PORTAL_ID;
            $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
			$this->parse_layout('search',$this->map);
        }
    }
    function sortByTime($a, $b) 
    {
        return $a['time'] - $b['time'];
    }
    function DesDebtCutomer($cond1)
    {
        $cond1 = '1=1'.$cond1;
        if(Url::get('customer_id'))
            $cond1 = 'customer_review_debt.customer_id = '.Url::get('customer_id').' AND '.$cond1;
        $sql = '         SELECT customer_review_debt.* 
                         FROM customer_review_debt
                            LEFT JOIN customer ON customer.id = customer_review_debt.customer_id
                         WHERE  '.$cond1.'   
                        ';
        $items = DB::fetch_all($sql);
        return $items;
    }
    
    function TotalDebitCustomerBefore($cond_debit_to_date)
    {
        $items= DB::fetch_all('SELECT currency_id as id, sum(price) as sum_debit
                FROM customer_review_debt
                WHERE '.$cond_debit_to_date.'
                group by currency_id
               ');      
    }
    
    function getPaymentDetail($cond1,$cond_payment_detail)
    {
        if(Url::get('customer_id'))
            $cond1 = ' AND payment.customer_id='.Url::get('customer_id').$cond1; 
        return DB::fetch_all('select 
                                    payment.id,payment.customer_id,payment.time,payment.type,payment.currency_id,
                                    payment.reservation_id,payment.folio_id,payment.amount,bar_reservation.code ,
                                    --Oanh add
                                    payment.customer_id,
                                    payment.reservation_traveller_id 
                                from payment
                                    left join bar_reservation ON bar_reservation.id = payment.reservation_id
                                    --Oanh add
                                    left join reservation_traveller on reservation_traveller.id = bar_reservation.reservation_traveller_id
                                    left join traveller on traveller.id = reservation_traveller.traveller_id 
                                    
                                    and payment.type=\'BAR\'
                                WHERE 
                                    payment.payment_type_id =\'DEBIT\' '.$cond1.$cond_payment_detail.'      
                            ');
     }
    public function getPayment()
    {
        $cond=' AND 1=1';
        if(Url::get('customer_id'))
           $cond = ' AND customer.id='.Url::get('customer_id').'';
		return DB::fetch_all('
                        SELECT payment.currency_id as id,
                               sum(payment.amount) as amount
                        FROM customer
                             INNER JOIN payment ON customer.id = payment.customer_id
                        WHERE  payment.time <='.Date_Time::to_time(Url::get('date_from')).'
                               AND payment.payment_type_id=\'DEBIT\' AND payment.portal_id = \''.PORTAL_ID.'\' 
                               '.$cond.'
                        GROUP BY payment.currency_id                  
                    ');
                    
	}
	
}
?>