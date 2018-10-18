<?php
class ReportRestaurantSalesForm extends Form{
    function ReportRestaurantSalesForm()
	{
		Form::Form('ReportRestaurantSalesForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
	}
    function draw()
	{
        $from_date = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
        $to_date = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        $this->map['from_date'] = $from_date;
        $_REQUEST['from_date'] = $from_date;
        $this->map['to_date'] = $to_date; 
        $_REQUEST['to_date'] = $to_date;
        if(URL::get('do_search'))
		{
            require_once 'packages/core/includes/utils/currency.php';
            require_once 'packages/hotel/packages/restaurant/includes/table.php';
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
			$bars = DB::fetch_all('select * from bar');
            $bar_ids = '';
            $bar_name = '';
			foreach($bars as $k => $bar){
				if(Url::get('bar_id_'.$k)){
					$bar_ids .= ($bar_ids=='')?$k:(','.$k);	
                    $bar_name .= ($bar_name=='')?$bar['name']:(', '.$bar['name']);
				}
			}
            $from_date_time=Date_Time::to_time($from_date);
            $to_date_time = Date_time::to_time($to_date);
			//echo IDStructure::child_cond(DB::structure_id('product_category',intval(URL::get('category_id'))));
			$this->line_per_page = URL::get('line_per_page',15);
			$cond = $this->cond = ''
				.(URL::get('bar_id')?' and bar_reservation.bar_id = '.URL::get('bar_id').'':'') 
				.(URL::get('category_id')?' AND '.IDStructure::child_cond(DB::structure_id('product_category',intval(URL::get('category_id')))):'') 
				.' and bar_reservation.time_out>='.$from_date_time.' and bar_reservation.time_out<'.($to_date_time+86400).''
				.' '
			;//and bar_reservation.status=\'CHECKOUT\'
			if(Url::get('customer_name')){
				$cond .= ' AND product.name LIKE \'%'.Url::get('customer_name').'%\'';
			}
			if(Url::get('code')){
				$cond .= ' AND upper(bar_reservation.agent_name) LIKE \'%'.strtoupper(Url::sget('code')).'%\'';
			}
			if(User::can_admin(false,ANY_CATEGORY)){
				$cond .= Url::get('hotel_id')?' and bar_reservation.portal_id = \''.Url::get('hotel_id').'\'':'';
			}else{
				$cond .= ' and bar_reservation.portal_id = \''.PORTAL_ID.'\'';	
			}
            //if(IDStructure::next())
			$sql = 'SELECT * FROM
					(	SELECT
							brp.price_id ||\'-\'|| brp.name as id
							,brp.bar_reservation_id as order_id
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
							,bar_reservation.total as order_total
							,bar_reservation.time_out
							,bar_reservation.departure_time
							,bar_reservation.user_id
							,0 as total
                            ,product_category.name as category_name
                            ,product_category.id as product_category_id
                            ,product_category.structure_id
                            ,unit.name_1
						FROM 
							bar_reservation_product brp
							inner join bar_reservation ON bar_reservation.id = brp.bar_reservation_id
							INNER JOIN product_price_list on product_price_list.id=brp.price_id
							INNER JOIN product on product.id = product_price_list.product_id
                            inner JOIN product_category on product_category.id = product.category_id
                            INNER JOIN UNIT ON brp.UNIT_ID = UNIT.ID
						WHERE 
							1>0 '.$cond.' AND bar_reservation.bar_id in ('.$bar_ids.')
						ORDER BY
							product_category.structure_id
					)
			WHERE
				rownum > '.((Url::get('start_page')-1)*Url::get('line_per_page')).' AND rownum<='.(Url::get('no_of_page')*Url::get('line_per_page')).'
				';
			$report = new Report;
            //System::debug($sql);
			$items = DB::fetch_all($sql);
            
            require_once 'packages/core/includes/system/id_structure.php';
            
           // $cate_parent_struct = IDStructure::parent($items['product_category.structure_id']);
           // echo $cate_parent_struct;
            
            	
            
			$bill_id = '0';
			foreach($items as $key=>$value)
            {
				if($value['discount_rate'] && $value['discount_rate']=='')
                {
					$value['discount_rate'] = 0;
				}
				if($value['discount_category'] && $value['discount_category']=='')
                {
					$value['discount_category'] = 0;
				}
				if($value['full_rate']==1)
                {
					$value['price'] = round($value['price']);
				}
                else if($value['full_charge']==1)
                {
					$value['price'] = round($value['price']);
				}
				$items[$key]['discount'] = ($value['quantity'] - $value['promotion'])*$value['price']*($value['discount_category']/100) + (($value['quantity'] - $value['promotion'])*$value['price'] - ($value['quantity'] - $value['promotion'])*$value['price']*($value['discount_category']/100))*($value['discount_rate']/100);
				$items[$key]['total'] =($value['quantity'])*$value['price'];
                $items[$key]['structure_id_level_1'] = IDStructure::parent($value['structure_id']);
                $items[$key]['category_name_level_1'] = DB::fetch('select * from product_category where structure_id = '.$items[$key]['structure_id_level_1'],'name');
			}
            //System::debug($items);
			$report->items = array();
			$count=0;
            
			foreach($items as $key=>$value){
				$count ++;  
					$report->items[$count] = $value;	
					$report->items[$count]['stt'] = $count;
					//$report->items[$count]['full_name'] = ($value['customer_name']!='')?$value['customer_name']:(($value['traveller_name']!='')?$value['traveller_name']:(($value['receiver_name']!='')?$value['receiver_name']:''));
			}	
            //System::debug($report);
			
            //IDStructure::parent('');
            $report->items_commons = array();
            $category_name_level_1 = '';
            foreach($items as $k => $v)
            {
                if($category_name_level_1 != $v['category_name_level_1'] ) 
                {
                    $category_name_level_1 = $v['category_name_level_1'];
                    
                    $report->items_commons[$category_name_level_1]['category_name_level_1'] = $category_name_level_1;
                    $report->items_commons[$category_name_level_1]['quantity'] = 0;
                    $report->items_commons[$category_name_level_1]['total'] = 0;
                }
                $report->items_commons[$category_name_level_1]['total'] += $v['total'];
                $report->items_commons[$category_name_level_1]['quantity'] ++;
            }
            //System::debug($report->items_commons);
            //$this->parse_layout('report_common',array('items_commons'=>items_commons));
            
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
			if(Url::get('hotel_id'))
             {
                 if(Url::get('hotel_id')!='ALL')
                 {
                     $bars = DB::fetch_all("select id,name FROM bar where portal_id='".Url::get('hotel_id')."'");
                 }
                 else
                 {
                    $bars = DB::select_all('bar',false); 
                 }
             }
             else
             {
                $bars = DB::select_all('bar',false); 
             }
            //System::debug($bars);
            $this->parse_layout('search',
				array(				
				'view_all'=>$view_all,
				'from_day_list'=>$date_arr,
				'to_day_list'=>$date_arr,
				'category_id_list'=>array(''=>Portal::language('all_category'))+($categories),
				//'bar_id' => URL::get('bar_id',''),
				//'bar_id_list' =>String::get_list(DB::select_all('bar',false)), 
                'from_date'=>$from_date,
				'to_date'=>$to_date,
                'bars' =>$bars,
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
                'items_commons'=>$report->items_commons,
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