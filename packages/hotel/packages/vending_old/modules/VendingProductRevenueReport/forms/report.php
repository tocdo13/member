<?php
class RestaurantRevenueReportForm extends Form
{
	function RestaurantRevenueReportForm()
	{
		Form::Form('RestaurantRevenueReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
	{
	    require_once 'packages/hotel/packages/vending/includes/php/vending.php';
        $this->map = array();
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        if(!isset($_REQUEST['date_from'])){
			$_REQUEST['date_from'] = '01/'.date('m/Y');
		}
		if(!isset($_REQUEST['date_to'])){
			$end_date_of_month = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
			$_REQUEST['date_to'] = $end_date_of_month.'/'.date('m/Y');
		} 
        $cond_portal = '1=1';
            if(Url::get('portal_id') and Url::get('portal_id')!='')
            {
                $cond_portal .= ' and portal_department.portal_id=\''.Url::get('portal_id').'\'';
            } 
            $sql_area=('select 
                                 department.id
                                ,department.name_'.Portal::language().' as name
                            FROM
                                department 
                                inner join portal_department on department.code = portal_department.department_code 
                            where 
                                 '.$cond_portal.' 
                                 and department.parent_id = (Select id from department where code = \'VENDING\')');
            $area = DB::fetch_all($sql_area);
            //System::debug($sql_area);
            $area_ids = '';
            $area_name = '';
            foreach($area as $k => $a){
				if(Url::get('area_id_'.$k)){
					$area_ids .= ($area_ids=='')?$k:(','.$k);	
                    $area_name .= ($area_name=='')?$a['name']:(', '.$a['name']);
				}
			}
            $_REQUEST['area_name'] = $area_name;
		if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
			$cond = $this->cond = ''
				.' and ve_reservation.time>='.Date_time::to_time(Url::get('date_from')).' and ve_reservation.time <'.(Date_time::to_time(Url::get('date_to'))+24*3600).''
				.' '
			;
			if(User::can_admin(false,ANY_CATEGORY))
            {
				$cond .= Url::get('portal_id')?' and ve_reservation.portal_id = \''.Url::get('portal_id').'\'':'';
			}
           
			$sql ='
                  SELECT
                        brp.id as id
                        ,ve_reservation.id as ve_id
                        ,brp.price_id ||\'-\'|| brp.name as id2 ,brp.price_id
                        ,brp.product_id
                        ,product_category.name as product_category_name
                        ,brp.price ,brp.quantity as quantity 
                        ,brp.discount ,brp.discount_rate,brp.discount_category 
                        ,brp.quantity_discount ,brp.name as product_name 
                        ,brp.promotion
                        
                        ,ve_reservation.full_rate,ve_reservation.full_charge 
                        ,ve_reservation.bar_fee_rate,ve_reservation.tax_rate 
                        ,ve_reservation.exchange_rate
                        
                        ,payment.payment_type_id
                        ,payment.currency_id
                        ,payment.type_dps
                        ,payment.amount
                  FROM 
						ve_reservation_product brp
						inner join ve_reservation ON ve_reservation.id = brp.bar_reservation_id
						INNER JOIN product_price_list on product_price_list.id=brp.price_id
						INNER JOIN product on product.id = product_price_list.product_id
						LEFT OUTER JOIN product_category on product_category.id = product.category_id
                        left join payment on payment.bill_id=ve_reservation.id 
                  WHERE 
						1>0 '.$cond.' and ve_reservation.department_id in ('.$area_ids.')
                  order by 
                        brp.product_id
                         ';
                 
            $report = new Report;
			$items = DB::fetch_all($sql);
			$bill_id = '0';
            foreach($items as $key=>$value){
                $items[$value['id2']][$key]=$value;
                unset($items[$key]);
            }
            $i=1;
           // System::debug($items);
			foreach($items as $key=>$val)
            {
              $items[$key]['quantity_discount']=0;$items[$key]['discount']=0; 
              $promotion = 0;$quantity=0;$items[$key]['discount'] =0;$items[$key]['total']=0;
              foreach($val as $k=>$value){
				if($value['discount_rate'] && $value['discount_rate']=='')
                {
				  $value['discount_rate'] = 0;
				}
				if($value['discount_category'] && $value['discount_category']=='')
                {
	              $value['discount_category'] = 0;
				}
                $value['quantity'] = $value['quantity']-$value['quantity_discount'];
                $discount = $value['promotion']+$value['discount_rate'];
                
                $items[$key]['discount'] +=$value['quantity']*$value['price']*($discount/100); 
               
                if($value['full_rate'] !=1)
                {
                  if($value['full_charge']==1){
                    $value['price'] = round($value['price']*(1+$value['tax_rate']/100),3);
                  }  
                  else{
                   $value['price'] = round($value['price']*(1+$value['bar_fee_rate']/100),3);
                   $value['price'] = round($value['price']*(1+$value['tax_rate']/100),3);
                  } 
                }  
           
             $quantity  += $value['quantity'];
             $items[$key]['product_id'] = $value['product_id'];
             $items[$key]['product_name'] = $value['product_name'];
             $items[$key]['price'] = $value['price'];
             $items[$key]['quantity_discount'] +=$value['quantity_discount'];
			 $items[$key]['total'] +=$value['quantity']*$value['price']-($value['quantity']*$value['price']*($discount/100));
             $items[$key]['product_category_name']=$value['product_category_name'];
             unset($items[$key][$k]);
             }
             $items[$key]['id']=$key;
             $items[$key]['stt']=$i;
             $items[$key]['quantity']=$quantity;
             
             $i++;
            }
           
			$report->items = array();
			$count=0;
			foreach($items as $key=>$value)
            {
				$count ++;  
				$report->items[$count] = $value;	
				$report->items[$count]['stt'] = $count;
					//$report->items[$count]['full_name'] = ($value['customer_name']!='')?$value['customer_name']:(($value['traveller_name']!='')?$value['traveller_name']:(($value['receiver_name']!='')?$value['receiver_name']:''));
			}	
			$this->print_all_pages($report);
		}
		else
		{
		    $restaurant_category = DB::select('product_category','id=11');
			$categories=array();
			$this->parse_layout('search',
				array(	
                'area'=>$area,
                'portal_id_list'=>array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list())
				)
			);	
           
		}			
	}
	function print_all_pages(&$report)
	{
		$count = 0;
		$total_page = 1;
		$pages = array();
		foreach($report->items as $key=>$item)
		{
			if($count>=$this->map['line_per_page'])
			{
				$count = 0;
				$total_page++;
			}
			$pages[$total_page][$key] = $item;
			$count++;
            
            
            
		}
		if(sizeof($pages)>0)
		{
			$this->group_function_params = array(
					'total'=>0,
					'quantity'=>0,					
					'discount'=>0,
					'quantity_discount'=>0
				);
			$this->map['real_total_page']=count($pages);
            $this->map['real_page_no'] = 1;
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page);
                $this->map['real_page_no'] ++;                
			}
		}
		else
		{
			if(Url::get('hotel_id')){
				$hotel = DB::fetch('SELECT NAME_1 AS name,ADDRESS FROM PARTY WHERE USER_ID = \''.Url::get('hotel_id').'\'');
				$hotel_name = $hotel['name']?$hotel['name']:HOTEL_NAME;
				$hotel_address = $hotel['address']?$hotel['address']:HOTEL_ADDRESS;
			}else{
				$hotel_name = HOTEL_NAME;
				$hotel_address = HOTEL_ADDRESS;
			}	
			$this->parse_layout('header',
			get_time_parameters()+
				array(
					'hotel_address'=>$hotel_address,
					'hotel_name'=>$hotel_name,
					'page_no'=>0,
					'total_page'=>0
				)+$this->map
			);
			$this->parse_layout('footer',array(
				'page_no'=>0,
				'total_page'=>0
			)+$this->map);
		}
	}
	function print_page($items, &$report, $page_no,$total_page)
	{
       
		$payment = array();
		$credit_card = 0;
		$total_currency = 0;
        foreach($items as $key=>$val){
            $items[$val['product_category_name']][$key]=$val;
            unset($items[$key]);
        }
        foreach($items as $key=>$val){
            $total=0;
            foreach($val as $id=>$item)
		      {
    			//$item['exchange_rate'] = $item['exchange_rate']?$item['exchange_rate']:DB::fetch('select exchange from currency where id = \'VND\'','exchange');
    			//$item['total'] = $item['total'];//*$item['exchange_rate']/RES_EXCHANGE_RATE;
    			if(!isset($items[$id]['debit'])){
    				$items[$key][$id]['debit'] = 0;
    			}
    			$order_id = '';
    			for($i=0;$i<6-strlen($item['id']);$i++)
    			{
    				$order_id .= '0';
    			}
    			$order_id .= $item['id'];
    			$items[$key][$id]['code'] = $order_id;
                $total +=$item['total'];
		      }
              
        }
        //System::debug($items);die;
		$last_group_function_params = $this->group_function_params;
        foreach($items as $key=> $val){
            $total=0;$quantity=0;
            foreach($val as $k=>$item)
		      {
		       
    			if($temp = $item['total'])
    			{
    				$this->group_function_params['total'] += $temp;
    			}
    			if($temp = $item['discount'])
    			{
    				$this->group_function_params['discount'] += $temp;
    			}
    			if($temp = $item['quantity_discount'])
    			{
    				$this->group_function_params['quantity_discount'] += $temp;
    			}
    			if($temp = $item['quantity'])
    			{
    				$this->group_function_params['quantity'] += $temp;
    			}
                
                  //binh add
                $items[$key]['child'][$k]=$item;
                $total +=$item['total'];
                $quantity +=$item['quantity'];
                unset($items[$key][$k]);
                //end binh 
		      }
              
              $items[$key]['id']=$key;
              $items[$key]['total_all']=$total;
              $items[$key]['quantity_all']=$quantity;
        }
		
       
        
		if(Url::get('hotel_id'))
        {
			$hotel = DB::fetch('SELECT NAME_1 AS name,ADDRESS FROM PARTY WHERE USER_ID = \''.Url::get('hotel_id').'\'');
			$hotel_name = $hotel['name'];
			$hotel_address = $hotel['address'];
		}
        else
        {
			$hotel_name = HOTEL_NAME;
			$hotel_address = HOTEL_ADDRESS;
		}
        if($page_no>=$this->map['start_page'])
		{	
    		$this->parse_layout('header',
    			array(
    				'hotel_address'=>$hotel_address,
    				'hotel_name'=>$hotel_name,
    				'page_no'=>$page_no,
    				'total_page'=>$total_page,
    			)+$this->map
    		);
    		$this->parse_layout('report',array(
    				'items'=>$items,
    				'last_group_function_params'=>$last_group_function_params,
    				'group_function_params'=>$this->group_function_params,
    				'page_no'=>$page_no,
    				'total_page'=>$total_page,
    			)+$this->map
    		);
    		$this->parse_layout('footer',array(				
    			'payment'=>$payment,
    			'credit_card_total'=>$credit_card,
    			'page_no'=>$page_no,
    			'total_page'=>$total_page,
    		)+$this->map);
        }
	}
	function get_payments($bill_id){
		
		return $payments = DB::fetch_all('
						SELECT 
							(payment.payment_type_id || \'_\' || payment.credit_card_id || \'_\' || payment.currency_id || \'_\' || payment.bill_id || \'_\' || payment.type_dps) as id
							,SUM(amount) as total
							,SUM(amount*payment.exchange_rate) as total_vnd
							,CONCAT(payment.payment_type_id,CONCAT(\'_\',payment.currency_id)) as name
							,payment.bill_id
							,payment.payment_type_id
							,payment.credit_card_id
							,payment.currency_id
							,payment.type_dps
						FROM payment
							inner join bar_reservation on payment.bill_id = bar_reservation.id
						WHERE 
							payment.bill_id in ('.$bill_id.') AND payment.type=\'BAR\'
						GROUP BY payment.payment_type_id,payment.currency_id,payment.bill_id
						,payment.currency_id,payment.type_dps,payment.credit_card_id
				');	
	}
}
?>