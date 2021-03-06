<?php
class BarRevenueReportForm extends Form
{
	function BarRevenueReportForm()
	{
		Form::Form('BarRevenueReportForm');
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
			$this->line_per_page = URL::get('line_per_page',15);
			$cond = $this->cond = ''
				.(URL::get('bar_id')?' and bar_reservation.bar_id = '.URL::get('bar_id').'':'') 
				.(URL::get('category_id')?' AND '.IDStructure::child_cond(DB::structure_id('product_category',intval(URL::get('category_id')))):'') 
				.' and bar_reservation.time_out>='.strtotime($month.'/'.$day.'/'.$year).' and bar_reservation.time_out<'.(strtotime($end_month.'/'.$end_day.'/'.$end_year)+24*3600).''
				.' '
			;//and bar_reservation.status=\'CHECKOUT\'
			if(Url::get('customer_name')){
				$cond .= ' AND product.name LIKE \'%'.Url::get('customer_name').'%\'';
			}
			if(Url::get('code')){
				$cond .= ' AND upper(customer.code) LIKE \'%'.strtoupper(Url::sget('code')).'%\'';
			}
			if(User::can_admin(false,ANY_CATEGORY)){
				$cond .= Url::get('hotel_id')?' and bar_reservation.portal_id = \''.Url::get('hotel_id').'\'':'';
			}else{
				$cond .= ' and bar_reservation.portal_id = \''.PORTAL_ID.'\'';	
			}
			echo $cond;
			$sql = 'SELECT
							brp.price_id ||\'-\'|| brp.name as id
							,brp.bar_reservation_id as order_id
							,bar_reservation.code
							,brp.product_id
							,brp.remain
							,brp.price
							,(brp.quantity) as quantity
							,brp.discount
							,brp.discount_rate
							,brp.discount_category
							,brp.quantity_discount as promotion
							,brp.name as product_name
							,bar_reservation.discount as order_discount
							,bar_reservation.discount_percent as order_discount_percent
							,bar_reservation.bar_fee_rate as service_rate
							,bar_reservation.tax_rate
							,bar_reservation.full_rate
							,bar_reservation.full_charge
							,bar_reservation.time_out
							,bar_reservation.departure_time
							,bar_reservation.user_id
							,bar_reservation.bar_id
							,brp.bar_id as product_bar_id
							,brp.add_charge
							,0 as total
						FROM 
							bar_reservation_product brp
							inner join bar_reservation ON bar_reservation.id = brp.bar_reservation_id
							INNER JOIN product_price_list on product_price_list.id=brp.price_id
							INNER JOIN product on product.id = product_price_list.product_id
							LEFT OUTER JOIN product_category on product_category.id = product.category_id
						WHERE 
							1>0 '.$cond.'
						ORDER BY
							bar_reservation.id
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
					$value['price'] = round($value['price']/(1+($value['tax_rate']*0.01) + ($value['service_rate']*0.01) + (($value['tax_rate']*0.01)*($value['service_rate']*0.01))),2);	
				}else if($value['full_charge']==1){
					$value['price'] = round($value['price']/(1+ ($value['service_rate']*0.01)),2);	
				}
				$items[$key]['discount'] = ($value['quantity'] - $value['promotion'])*$value['price']*($value['discount_category']/100) + (($value['quantity'] - $value['promotion'])*$value['price'] - ($value['quantity'] - $value['promotion'])*$value['price']*($value['discount_category']/100))*($value['discount_rate']/100);
				$items[$key]['total'] =($value['quantity'] - $value['promotion'])*$value['price'] - $items[$key]['discount'];
			}
			System::Debug($items);
			$ritems = array();
			$count=0;
			$reservation_id = 0;
			$arr = array();
			foreach($items as $key=>$value){
				if($reservation_id != $value['order_id']){
					$arr[$value['order_id']]['id'] = $value['order_id'];
					$arr[$value['order_id']]['code'] = $value['code'];
					$arr[$value['order_id']]['order_discount'] = $value['order_discount'];
					$arr[$value['order_id']]['order_discount_percent'] = $value['order_discount_percent'];
					$arr[$value['order_id']]['service_rate'] =$value['service_rate'];
					$arr[$value['order_id']]['tax_rate'] = $value['tax_rate'];
					$arr[$value['order_id']]['full_rate'] = $value['full_rate'];
					$arr[$value['order_id']]['full_charge'] = $value['full_charge'];
					$arr[$value['order_id']]['time_out'] = $value['time_out'];
					$arr[$value['order_id']]['departure_time'] = $value['departure_time'];
					$arr[$value['order_id']]['user_id'] = $value['user_id'];
					$arr[$value['order_id']]['total'] = 0;
					$arr[$value['order_id']]['total_other'] = 0;
					if($value['bar_id'] != $value['product_bar_id']){
						$price = round($value['total']/(1+ $value['add_charge']/100),0);	
						$arr[$value['order_id']]['total_other'] +=  $price;	
						$arr[$value['order_id']]['total'] += $price * $value['add_charge']*0.01;
					}else{
						$arr[$value['order_id']]['total'] += $value['total'];
					}
				}else{
					if($value['bar_id'] != $value['product_bar_id']){
						$price = round($value['total']/(1+ $value['add_charge']/100),0);	
						$arr[$value['order_id']]['total_other'] +=  $price;	
						$arr[$value['order_id']]['total'] += $price * $value['add_charge']*0.01;
					}else{
						$arr[$value['order_id']]['total'] += $value['total'];
					}
				}
					//$report->items[$count] = $value;	
					//$report->items[$count]['stt'] = $count;
					//$report->items[$count]['full_name'] = ($value['customer_name']!='')?$value['customer_name']:(($value['traveller_name']!='')?$value['traveller_name']:(($value['receiver_name']!='')?$value['receiver_name']:''));
			}
			foreach($arr as $k =>$a){
				$count ++;
				if(((Url::get('start_page')-1)*Url::get('line_per_page'))<$count && $count<= (Url::get('no_of_page')*Url::get('line_per_page'))){
					 $arr[$k]['stt'] = $count;
					 $arr[$k]['discount'] = ($a['total']+$a['total_other']) * ($a['order_discount_percent']) * 0.01;
					 $arr[$k]['service_amount'] = (($a['total']+$a['total_other']) - $arr[$k]['discount']) * $a['service_rate'] * 0.01;
					 $arr[$k]['tax_amount'] = (($a['total']+$a['total_other']- $arr[$k]['discount']) + $arr[$k]['service_amount']) *$a['tax_rate'] * 0.01;
					 $total_before_tax = $a['total'] * (100 - $a['order_discount_percent']) * 0.01 ;//- $a['order_discount']	
					 $arr[$k]['total'] = $total_before_tax + ($total_before_tax * $a['service_rate'] * 0.01) + ($total_before_tax + ($total_before_tax * $a['service_rate'] * 0.01))*$a['tax_rate']*0.01;
					 $other_before_tax = $a['total_other'] * (100 - $a['order_discount_percent']) * 0.01;	
					 $arr[$k]['total_other'] = $other_before_tax + ($other_before_tax * $a['service_rate'] * 0.01) + ($other_before_tax + ($other_before_tax * $a['service_rate'] * 0.01))*$a['tax_rate']*0.01;
				}
			}
			$report->items = $arr;
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
			$categories = DB::fetch_all('select id,name from product_category where '.IDStructure::direct_child_cond($restaurant_category['structure_id']));
			$bars = DB::fetch_all('select bar.id,bar.name,bar.code from bar where 1>0 ');//'.$cond2.'
			$bar_id_options = '';  
			$bar_id_options .= '<option value="">------Select------</option>';
			foreach($bars as $k => $item)
			{
				$bar_id_options .= '<option value="'.$item['id'].'" id="bar_id_'.$item['id'].'">'.$item['name'].'</option>';
			}	
			$this->parse_layout('search',
				array(				
				'view_all'=>$view_all,
				'from_day_list'=>$date_arr,
				'to_day_list'=>$date_arr,
				'category_id_list'=>array(''=>Portal::language('all_category'))+String::get_list($categories),
				'bar_id' => URL::get('bar_id',''),
				'bars'=>$bar_id_options,
				'bar_id_list' =>String::get_list(DB::select_all('bar',false)), 
				'hotel_id_list'=>array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list())
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
					'total_other'=>0,
					'service_amount'=>0,
					'tax_amount'=>0
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
		$last_group_function_params = $this->group_function_params;
		foreach($items as $item)
		{
			$this->group_function_params['quantity'] ++;
			if($temp = $item['total'])
			{
				$this->group_function_params['total'] += $temp;
			}
			if($temp = $item['total_other'])
			{
				$this->group_function_params['total_other'] += $temp;
			}
			if($temp = $item['discount'])
			{
				$this->group_function_params['discount'] += $temp;
			}
			if($temp = $item['service_amount'])
			{
				$this->group_function_params['service_amount'] += $temp;
			}
			if($temp = $item['tax_amount'])
			{
				$this->group_function_params['tax_amount'] += $temp;
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
}
?>