<?php
class RestaurantRevenueReportForm extends Form
{
	function RestaurantRevenueReportForm()
	{
		Form::Form('RestaurantRevenueReportForm');
                $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
                $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
                $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{
		if(URL::get('do_search'))
		{	
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
			//echo IDStructure::child_cond(DB::structure_id('product_category',intval(URL::get('category_id'))));
			$this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
            $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1; 
            //Luu nguyen giap add search for cac nha hang
            //lay ra id cua tat ca nhung nha hang can lay ra
            $cond =' 1=1 ';
            $cond_bar =' 1=1 ';
            if(URL::get('barids'))
            {
               $barids = URL::get('barids');
               if($barids!='')
               {
                    //tach chuoi barids
                    $str_bar = explode(',',$barids);
                    //System::debug($str_bar);
                    if(count($str_bar)>0)
                    {
                        $cond_bar  .=' AND (';
                        $cond .=' AND (';
                    }
                        
                    if(count($str_bar)==1)
                    {
                        $cond .=' bar_reservation.bar_id='.$str_bar[0].')';
                        $cond_bar .=' bar.id='.$str_bar[0].')';
                    }
                    else
                    {
                        for($i=0;$i<count($str_bar);$i++)
                        {
                            if($i==count($str_bar)-1)
                            {
                                if($str_bar[$i]!='')
                                {
                                    $cond_bar .=' bar.id='.$str_bar[$i].')';
                                    $cond .=' bar_reservation.bar_id='.$str_bar[$i].')';
                                }
                                    
                            }
                            else
                            {
                                if($str_bar[$i]!='')
                                {
                                    $cond .=' bar_reservation.bar_id='.$str_bar[$i].' or';
                                    $cond_bar .=' bar.id='.$str_bar[$i].' or';
                                }
                                    
                            }
                        }
                    }  
               } 
            }
                
            //lay ra ten bar theo id
            $sql ='SELECT distinct bar.id,bar.name 
            FROM bar_reservation RIGHT JOIN  bar on bar.id=bar_reservation.bar_id 
            WHERE '.$cond_bar;
            $bar_names = DB::fetch_all($sql);
           // $_REQUEST['bar_names'] = '';
            $str_bar_names ='';
            
            foreach($bar_names as $row)
            {
                $str_bar_names .= $row['name'].', ';
            }
            $str_bar_names = rtrim($str_bar_names) ;
            $str_bar_names = rtrim($str_bar_names,',') ;
            $_REQUEST['bar_names'] =  $str_bar_names;
           // System::debug($_REQUEST['bar_names']);
            //End luu nguyen giap 
            $cond .= $this->cond = 
				(URL::get('category_id')?' AND '.IDStructure::child_cond(DB::structure_id('product_category',intval(URL::get('category_id')))):'') 
				;//and bar_reservation.status=\'CHECKOUT\'
			 if(Url::get('search_time'))
            {
				if(Url::get('from_date_tan') && Url::get('from_time')){
					$from_date_tan = Date_Time::to_time(Url::get('from_date_tan'));
					$from_time = $this->calc_time(Url::get('from_time'));
					$this->map['from_time'] = Url::get('from_time');
					$from_time_view = $from_date_tan + $from_time;
				}	
				if(Url::get('to_date_tan') && Url::get('to_time')){
					$to_date_tan = Date_Time::to_time(Url::get('to_date_tan'));
					$to_time = $this->calc_time(Url::get('to_time'))+59; 
					$this->map['to_time'] = Url::get('to_time');
					$to_time_view = $to_date_tan + $to_time;
				}
                $cond .= ' and bar_reservation.time_out>='.$from_time_view.' and bar_reservation.time_out<'.($to_time_view).'';
            }	
			if(Url::get('customer_name')){
				$cond .= ' AND product.name LIKE \'%'.Url::get('customer_name').'%\'';
			}
			if(Url::get('code')){
				$cond .= ' AND upper(customer.code) LIKE \'%'.strtoupper(Url::sget('code')).'%\'';
			}
            
            //Start Luu Nguyen Giap add portal
            if(Url::get('portal_id'))
            {
                $portal_id = Url::get('portal_id');
            }
            else
            {
                $portal_id = PORTAL_ID;                       
            }
            if($portal_id != 'ALL')
            {
                $cond .=" AND bar_reservation.portal_id = '".$portal_id. "'";
               // $cond_invoice = " AND extra_service_invoice.portal_id = '".$portal_id. "'"; 
            }
            //else
            //{
                //$cond ='' ;
              //  $cond .=" AND bar_reservation.portal_id = '".PORTAL_ID. "'";
            //}

            //End Luu Nguyen Giap add portal
			if(Url::get('search_invoice'))
            {
                if(Url::get('from_bill') AND Url::get('to_bill'))
                {
                    $cond .= " AND SUBSTR(bar_reservation.code, 6 ) >=".Url::get('from_bill')." AND SUBSTR(bar_reservation.code, 6 )<=".Url::get('to_bill');
                }
                elseif(Url::get('from_bill') AND !Url::get('to_bill'))
                {
                    $cond .= " AND SUBSTR(bar_reservation.code, 6 ) >=".Url::get('from_bill');
                }
                elseif(!Url::get('from_bill') AND Url::get('to_bill'))
                {
                    $cond .= " AND SUBSTR(bar_reservation.code, 6 )<=".Url::get('to_bill');
                }
            }
			 
			$sql = 'SELECT
							brp.price_id ||\'-\'|| brp.name ||\'-\'|| brp.price ||\'-\'|| brp.bar_reservation_id ||\'-\'|| brp.stt as id
							,brp.bar_reservation_id as order_id
							,brp.product_id
							,brp.remain
							,brp.price
							,(brp.quantity) as quantity
							,brp.discount
                            ,bar_reservation.code
							,brp.discount_rate
							,brp.discount_category
							,brp.quantity_discount as promotion
							,CASE
                                WHEN
                                (brp.product_id=\'FOUTSIDE\' OR brp.product_id=\'DOUTSIDE\' OR brp.product_id=\'SOUTSIDE\')
                                THEN
                                brp.name
                                ELSE
                                product.name_'.Portal::language().'
                                END product_name
                            ,unit.name_'.Portal::language().' as product_unit
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
                            ,product_category.structure_id
                            --giap.ln add category parent show 
                            ,(CASE 
                                WHEN product_category.structure_id is null
                                THEN \'\'
                                ELSE
                                    (SELECT pc.name
                                        FROM product_category pc
                                        WHERE SUBSTR(pc.structure_id,1,5)=SUBSTR(product_category.structure_id,1,5)
                                        AND SUBSTR(pc.structure_id,6,2)=\'00\')
                                END) as category_parent_name
                            --end giap.ln 
                            
						FROM 
							bar_reservation_product brp
                            LEFT join unit on unit.id=brp.unit_id
							LEFT join bar_reservation ON bar_reservation.id = brp.bar_reservation_id
							--LEFT JOIN product_price_list on product_price_list.id=brp.price_id
							LEFT JOIN product on product.id = brp.product_id
							LEFT OUTER JOIN product_category on product_category.id = product.category_id
						WHERE 
							'.$cond.'
                            and bar_reservation.status = \'CHECKOUT\' AND brp.bar_set_menu_id IS NULL
						ORDER BY
							category_parent_name,product_category.name,brp.product_id,brp.price,unit.name_'.Portal::language().',brp.name';
            
            /** START : DAT-1343 **/            
            $sql_dis = 'select sum(discount) as total_discount, sum(discount_after_tax) as total_discount_after_tax
                    from (
                        SELECT
							bar_reservation.id,
                            bar_reservation.discount,
                            case
                            when bar_reservation.discount_after_tax = 0
                            then 
                                bar_reservation.discount * (1 + NVL(bar_reservation.bar_fee_rate,0)/100) * (1 + NVL(bar_reservation.tax_rate,0)/100) 
                            else
                                bar_reservation.discount
                            end as discount_after_tax
						FROM 
							bar_reservation_product brp
							LEFT join bar_reservation ON bar_reservation.id = brp.bar_reservation_id
							--LEFT JOIN product_price_list on product_price_list.id=brp.price_id
							LEFT JOIN product on product.id = brp.product_id
							LEFT OUTER JOIN product_category on product_category.id = product.category_id
						WHERE 
							'.$cond.'
                            and bar_reservation.status = \'CHECKOUT\'
						--GROUP BY
							--bar_reservation.id,bar_reservation.discount,bar_reservation.bar_fee_rate,bar_reservation.tax_rate
                    )';
            //System::debug($sql_dis);
             if(User::id()=='developer06')
            {
                System::debug($sql_dis);
            }
            $total_discount = DB::fetch($sql_dis);
            $this->map['total_discount'] = $total_discount['total_discount'];
            $this->map['total_discount_after_tax'] = $total_discount['total_discount_after_tax'];
            /** END : DAT-1343 **/
			//System::debug($sql);
           // echo $sql;
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
					//$value['price'] = round($value['price']/1.155,2);
                    $value['price'] = $value['price']/(1+$value['service_rate']/100)/(1+$value['tax_rate']/100);
				}else if($value['full_charge']==1){
					//$value['price'] = round($value['price']/1.05,2);
                    $value['price'] = $value['price']/(1+$value['service_rate']/100);
				}
				$items[$key]['discount'] = ($value['quantity'] - $value['promotion'])*$value['price']*($value['discount_category']/100)
                 + (($value['quantity'] - $value['promotion'])*$value['price'] - ($value['quantity'] - $value['promotion'])*$value['price']*($value['discount_category']/100))*($value['discount_rate']/100)
				+((($value['quantity'] - $value['promotion'])*$value['price']) - (($value['quantity'] - $value['promotion'])*$value['price']*($value['discount_category']/100)) - ((($value['quantity'] - $value['promotion'])*$value['price'] - ($value['quantity'] - $value['promotion'])*$value['price']*($value['discount_category']/100))*($value['discount_rate']/100)))*($value['order_discount_percent']/100);  
                
				$items[$key]['total'] =($value['quantity'] - $value['promotion'])*$value['price'] - $items[$key]['discount'];
                $items[$key]['total'] *= (1+$value['service_rate']/100)*(1+$value['tax_rate']/100);
			}
			
			$count=0;
            
            $g_count = 0;
            $g_items = array();
            $check_product_id = '';
            $check_product_name = '';
            $check_price = 0;
            foreach($items as $key1=>$value1){
                //if(($value1['product_id']!=$check_product_id) AND ($value1['price']!=$check_price)){
                    //$check_product_id=$value1['product_id'];
                if(($value1['product_id']!=$check_product_id) or ($value1['product_name']!=$check_product_name) or ($value1['price']!=$check_price)){
                    $check_product_id = $value1['product_id'];
                    $check_product_name = $value1['product_name'];
                    $check_price = $value1['price'];
                    $g_count++;
                    if(empty($value1['category_name']))
                    {
                      $value1['category_name'] = "Các món trong set"; 
                    }
                    $g_items[$g_count] = array(
                                            'id'=>$value1['id'],
                                            'product_id'=>$value1['product_id'],
                                            'remain'=>$value1['remain'],
                                            'price'=>$value1['price'],
                                            'quantity'=>$value1['quantity'],
                                            'discount'=>$value1['discount'],
                                            'discount_rate'=>$value1['discount_rate'],
                                            'discount_category'=>$value1['discount_category'],
                                            'promotion'=>$value1['promotion'],
                                            'product_unit'=>$value1['product_unit'],
                                            'product_name'=>$value1['product_name'],
                                            'order_discount'=>$value1['order_discount'],
                                            'order_discount_percent'=>$value1['order_discount_percent'],
                                            'order_total'=>$value1['order_total'],
                                            'total'=>$value1['total'],
                                            'category_name'=>$value1['category_name'],
                                            'category_parent_name'=>$value1['category_parent_name']
                                        );
                }else{
                    $g_items[$g_count]['id'] = $value1['id'];
                    $g_items[$g_count]['product_id'] = $value1['product_id'];
                    $g_items[$g_count]['remain'] += $value1['remain'];
                    $g_items[$g_count]['price'] = $value1['price'];
                    $g_items[$g_count]['quantity'] += $value1['quantity'];
                    $g_items[$g_count]['discount'] += $value1['discount'];
                    $g_items[$g_count]['discount_rate'] += $value1['discount_rate'];
                    $g_items[$g_count]['discount_category'] += $value1['discount_category'];
                    $g_items[$g_count]['promotion'] += $value1['promotion'];
                    $g_items[$g_count]['product_unit'] = $value1['product_unit'];
                    $g_items[$g_count]['product_name'] = $value1['product_name'];
                    $g_items[$g_count]['order_discount'] += $value1['order_discount'];
                    $g_items[$g_count]['order_discount_percent'] += $value1['order_discount_percent'];
                    $g_items[$g_count]['order_total'] += $value1['order_total'];
                    $g_items[$g_count]['total'] += $value1['total'];
                    if(!empty($value1['category_name'])){
                      $g_items[$g_count]['category_name'] = $value1['category_name'];
                    }
                    else{
                      $g_items[$g_count]['category_name'] = "Các món trong set";  
                    }
                    $g_items[$g_count]['category_parent_name'] = $value1['category_parent_name'];
                    
                }
            }
            $items = $g_items;
            $report->items = array();                                                           
			foreach($items as $key=>$value){
				$count ++;  
					$report->items[$count] = $value;	
					$report->items[$count]['stt'] = $count;
					//$report->items[$count]['full_name'] = ($value['customer_name']!='')?$value['customer_name']:(($value['traveller_name']!='')?$value['traveller_name']:(($value['receiver_name']!='')?$value['receiver_name']:''));
			}	
            //System::debug($report);
			
            
            $report->items_commons = array();
            $category_name = '';
            $stt=1;
            $category_parent_name = false;
            foreach($items as $k => $v)
            {
                if($category_name != $v['category_name'] ) 
                {
                    $category_name = $v['category_name'];
                    $report->items_commons[$category_name]['stt'] = $stt++;
                    $report->items_commons[$category_name]['name'] = $category_name;
                    $report->items_commons[$category_name]['quantity'] = 0;
                    $report->items_commons[$category_name]['total'] = 0;
                }
                $report->items_commons[$category_name]['total'] += $v['total'];
                $report->items_commons[$category_name]['quantity'] ++;
                if($category_parent_name!=$v['category_parent_name'])
                {
                    $category_parent_name=$v['category_parent_name'];
                    $report->items_commons[$category_parent_name]['total_parent'] = 0;
                }
                $report->items_commons[$category_parent_name]['total_parent'] += $v['total'];
            }
            //System::debug($report);
            //$this->parse_layout('report_common',array('items_commons'=>items_commons));
            
            $this->print_all_pages($report);
		}
		else
		{
			$view_all = true;
			if(!User::can_view(false,ANY_CATEGORY) and User::can_view_detail(false,ANY_CATEGORY)){
				$_REQUEST['from_day'] = date('d');
				$view_all = false;
			}
			$restaurant_category = DB::select('product_category','code=\'HH\'');      
            $cate_sql = 'select 
                            id,
                            name 
                        from 
                            product_category 
                        where '.IDStructure::direct_child_cond($restaurant_category['structure_id']);        
			$categories = DB::fetch_all($cate_sql);
            
            //Start Luu Nguyen GIap add portal
             $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
             if(Url::get('portal_id'))
             {
                 if(Url::get('portal_id')!='ALL')
                 {
                     $bars = DB::fetch_all("select id,name FROM bar where portal_id='".Url::get('portal_id')."'");
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
             //End Luu Nguyen GIap add portal
             
			$this->parse_layout('search',$this->map+
				array(				
				'view_all'=>$view_all,
				'category_id_list'=>array(''=>Portal::language('all_category'))+String::get_list($categories),
				'bar_id' => URL::get('bar_id',''),
				'bar_id_list' =>String::get_list(DB::select_all('bar',false)), 
                'bars' =>$bars,
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
			//System::debug($this->map['line_per_page']);
            if($count>=$this->map['line_per_page'])
			{
				$count = 0;
				$total_page++;
			}
			$pages[$total_page][$key] = $item;
			$count++;
		}
        //System::debug($pages);
        //System::debug(sizeof($pages));
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
					'total_page'=>0,
                    'from_bill'=>Url::get('from_bill'),
                    'to_bill'=>Url::get('to_bill')
				)+$this->map
			);
            $this->parse_layout('no_record');//KimTan: xu ly truong hop khong co du lieu
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
        if($page_no>=$this->map['start_page'])
		{    	
    		$this->parse_layout('header',
    			array(
    				'hotel_address'=>$hotel_address,
    				'hotel_name'=>$hotel_name,
    				'page_no'=>$page_no,
    				'total_page'=>$total_page,
                    'from_bill'=>Url::get('from_bill'),
                    'to_bill'=>Url::get('to_bill')
    			)+$this->map
    		);
    		$this->parse_layout('report',array(
    				'items'=>$items,
                    'items_commons'=>$report->items_commons,
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
    		)+$this->map
            );
        }
	}
	function get_payments($bill_id)
    {
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
    function calc_time($string)
    {
        $arr = explode(':',$string);
        //System::debug($arr);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
?>