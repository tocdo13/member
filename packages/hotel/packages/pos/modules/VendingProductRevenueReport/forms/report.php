<?php
class RestaurantRevenueReportForm extends Form
{
	function RestaurantRevenueReportForm()
	{
		Form::Form('RestaurantRevenueReportForm');
	}
	function draw()
	{
		if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
			$year = URL::get('from_year')?URL::get('from_year'):date('Y');
			$end_year = URL::get('from_year')?URL::get('from_year'):date('Y');
			$end_day = Date_Time::day_of_month(date('m'),date('Y'));
			if(URL::get('from_day'))
			{
				$day = URL::get('from_day');
				$end_day = URL::get('to_day');
			}else{
				$day = date('d');
				$end_day = date('d');
			}
			$month = URL::get('from_month')?URL::get('from_month'):date('m');
			$end_month = URL::get('to_month')?URL::get('to_month'):date('m');
			if(!checkdate($month,$day,$year))
			{
				$day = 1;
			}
			if(!checkdate($end_month,$end_day,$end_year))
			{
				$end_day = Date_time::day_of_month($end_month,$end_year);
			}
			//echo 'thuylt';
			//echo IDStructure::child_cond(DB::structure_id('product_category',intval(URL::get('category_id'))));
			$this->line_per_page = URL::get('line_per_page',15);
			$cond = $this->cond = ''
				.(URL::get('category_id')?' AND '.IDStructure::child_cond(DB::structure_id('product_category',intval(URL::get('category_id')))):'') 
				.' and ve_reservation.time>='.strtotime($month.'/'.$day.'/'.$year).' and ve_reservation.time <'.(strtotime($end_month.'/'.$end_day.'/'.$end_year)+24*3600).''
				.' '
			;
			if(Url::get('customer_name')){
				$cond .= ' AND product.name LIKE \'%'.Url::get('customer_name').'%\'';
			}
			if(User::can_admin(false,ANY_CATEGORY)){
				$cond .= Url::get('hotel_id')?' and ve_reservation.portal_id = \''.Url::get('hotel_id').'\'':'';
			}else{
				$cond .= ' and ve_reservation.portal_id = \''.PORTAL_ID.'\'';	
			}
            
            require_once 'packages/hotel/packages/vending/includes/php/vending.php';
            $area = get_area_vending();
            
            $cond .=' and ve_reservation.department_id in (';    
            foreach($area as $k=>$v)
            {
                $cond.=$k.',';
            }
            $cond = trim($cond,',');
            $cond.= ')'; 
            
			$sql = 'SELECT * FROM
					(	SELECT
							brp.price_id ||\'-\'|| brp.name as id
							,brp.bar_reservation_id as order_id
							,brp.product_id
							,brp.price
							,(brp.quantity) as quantity
							,brp.discount
							,brp.discount_rate
							,brp.discount_category
							,brp.quantity_discount as promotion
							,brp.name as product_name
							,ve_reservation.discount as order_discount
							,ve_reservation.discount_percent as order_discount_percent
							,ve_reservation.bar_fee_rate as service_rate
							,ve_reservation.tax_rate
							,ve_reservation.full_rate
							,ve_reservation.full_charge
							,ve_reservation.total as order_total
							,ve_reservation.time_in
							,ve_reservation.arrival_time
							,ve_reservation.user_id
							,0 as total
						FROM 
							ve_reservation_product brp
							inner join ve_reservation ON ve_reservation.id = brp.bar_reservation_id
							INNER JOIN product_price_list on product_price_list.id=brp.price_id
							INNER JOIN product on product.id = product_price_list.product_id
							LEFT OUTER JOIN product_category on product_category.id = product.category_id
						WHERE 
							1>0 '.$cond.'
						ORDER BY
							ve_reservation.id
					)
			WHERE
				rownum > '.((Url::get('start_page')-1)*Url::get('line_per_page')).' AND rownum<='.(Url::get('no_of_page')*Url::get('line_per_page')).'
				';
			$report = new Report;
			$items = DB::fetch_all($sql);
			$bill_id = '0';
			foreach($items as $key=>$value){
				if($value['discount_rate'] && $value['discount_rate']==''){
					$value['discount_rate'] = 0;
				}
				if($value['discount_category'] && $value['discount_category']==''){
					$value['discount_category'] = 0;
				}
				if($value['full_rate']==1){
					$value['price'] = round($value['price']/1.155,2);
				}else if($value['full_charge']==1){
					$value['price'] = round($value['price']/1.05,2);
				}
				$items[$key]['discount'] = ($value['quantity'] - $value['promotion'])*$value['price']*($value['discount_category']/100) + (($value['quantity'] - $value['promotion'])*$value['price'] - ($value['quantity'] - $value['promotion'])*$value['price']*($value['discount_category']/100))*($value['discount_rate']/100);
				$items[$key]['total'] =($value['quantity'] - $value['promotion'])*$value['price'] - $items[$key]['discount'];
			}
			$report->items = array();
			$count=0;
			foreach($items as $key=>$value){
				$count ++;  
					$report->items[$count] = $value;	
					$report->items[$count]['stt'] = $count;
					//$report->items[$count]['full_name'] = ($value['customer_name']!='')?$value['customer_name']:(($value['traveller_name']!='')?$value['traveller_name']:(($value['receiver_name']!='')?$value['receiver_name']:''));
			}	
			$this->print_all_pages($report);
		}
		else
		{
			$date_arr = array();
			for($i=1;$i<=31;$i++){
				$date_arr[strlen($i)<2?'0'.($i):$i] = strlen($i)<2?'0'.($i):$i;
			}
			$view_all = true;
			if(!User::can_view(false,ANY_CATEGORY) and User::can_view_detail(false,ANY_CATEGORY)){
				$_REQUEST['from_day'] = date('d');
				$view_all = false;
			}
			$restaurant_category = DB::select('product_category','id=11');
			//$categories = DB::fetch_all('select id,name from product_category where '.IDStructure::direct_child_cond($restaurant_category['structure_id']));
            //cai nay do doi category, se sua lai sau
            $categories=array();
			$this->parse_layout('search',
				array(				
				'view_all'=>$view_all,
				'from_day_list'=>$date_arr,
				'to_day_list'=>$date_arr,
				'category_id_list'=>array(''=>Portal::language('all_category'))+($categories),
				'bar_id' => URL::get('bar_id',''),
				'bar_id_list' =>String::get_list(DB::select_all('bar',false)), 
				'hotel_id_list'=>array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list())
				)
			);	
            //category_id_list'=>array(''=>Portal::language('all_category'))+String::get_list($categories),
		}			
	}
	function print_all_pages(&$report)
	{
		$count = 0;
		$total_page = 1;
		$pages = array();
		foreach($report->items as $key=>$item)
		{
			if($count>=$this->line_per_page)
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
					'promotion'=>0
				);
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page);
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
				)
			);
			$this->parse_layout('footer',array(
				'page_no'=>0,
				'total_page'=>0
			));
		}
	}
	function print_page($items, &$report, $page_no,$total_page)
	{
		$payment = array();
		$credit_card = 0;
		$total_currency = 0;
		foreach($items as $id=>$item)
		{
			//$item['exchange_rate'] = $item['exchange_rate']?$item['exchange_rate']:DB::fetch('select exchange from currency where id = \'VND\'','exchange');
			//$item['total'] = $item['total'];//*$item['exchange_rate']/RES_EXCHANGE_RATE;
			if(!isset($items[$id]['debit'])){
				$items[$id]['debit'] = 0;
			}
			$order_id = '';
			for($i=0;$i<6-strlen($item['id']);$i++)
			{
				$order_id .= '0';
			}
			$order_id .= $item['id'];
			$items[$id]['code'] = $order_id;
		}
		$last_group_function_params = $this->group_function_params;
		foreach($items as $item)
		{
			if($temp = $item['total'])
			{
				$this->group_function_params['total'] += $temp;
			}
			if($temp = $item['discount'])
			{
				$this->group_function_params['discount'] += $temp;
			}
			if($temp = $item['promotion'])
			{
				$this->group_function_params['promotion'] += $temp;
			}
			if($temp = $item['quantity'])
			{
				$this->group_function_params['quantity'] += $temp;
			}
		}
		//$total = $this->group_function_params['pay_by_cash']+$fee_summary['pay_by_cash_fee']+$fee_summary['pay_by_cash_tax'];		
		if(Url::get('hotel_id')){
				$hotel = DB::fetch('SELECT NAME_1 AS name,ADDRESS FROM PARTY WHERE USER_ID = \''.Url::get('hotel_id').'\'');
				$hotel_name = $hotel['name'];
				$hotel_address = $hotel['address'];
			}else{
				$hotel_name = HOTEL_NAME;
				$hotel_address = HOTEL_ADDRESS;
			}	
		$this->parse_layout('header',
			array(
				'hotel_address'=>$hotel_address,
				'hotel_name'=>$hotel_name,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)
		);
		$this->parse_layout('report',array(
				'items'=>$items,
				'last_group_function_params'=>$last_group_function_params,
				'group_function_params'=>$this->group_function_params,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)
		);
		$this->parse_layout('footer',array(				
			'payment'=>$payment,
			'credit_card_total'=>$credit_card,
			'page_no'=>$page_no,
			'total_page'=>$total_page,
		));
	}
	function get_payments($bill_id){
		//$hi = DB::fetch_all('select * from payment where payment.time>='.$time_from.' AND payment.time<='.$time_to.'');
		//System::debug($hi);
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