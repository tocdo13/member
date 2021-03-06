<?php
class DetailedDailyReportForm extends Form
{
	function DetailedDailyReportForm()
	{
		Form::Form('DetailedDailyReportForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');  
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');      
	}
	function draw()
	{
		if(Url::get('do_search'))
		{
		  	require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
            /*if(Url::get('bar_id'))
            {
				Session::set('bar_id',intval(Url::get('bar_id')));
			}
            else
            {
				$bar_id = Session::get('bar_id');	
			}
			$_REQUEST['bar_id'] = Session::get('bar_id');*/
			$bars = DB::fetch_all('select * from bar');
			$bar_ids = '';
            $bar_name = '';
			foreach($bars as $k => $bar){
				if(Url::get('bar_id_'.$k)){
					$bar_ids .= ($bar_ids=='')?$k:(','.$k);	
                    $bar_name .= ($bar_name=='')?$bar['name']:(', '.$bar['name']);
				}
			}
            //System::debug($bar_ids);
            $bar_ids  = $bar_ids?$bar_ids:0;
            $_REQUEST['bar_name'] = $bar_name;
            
			$from_day = Url::get('date_from');
            $to_day = Url::get('date_to');
            
			$this->line_per_page = Url::get('line_per_page',999999999);
            
            
            $shift_lists = DB::fetch_all('select bar_shift.* from bar_shift order by id ASC');
			$time_from = Date_Time::to_time($from_day);
            $time_to = Date_Time::to_time($to_day);    
                   
			$this->map['time_from'] = ($from_day);
            $this->map['time_to'] = ($to_day);  
            /*
            if(Url::get('shift_id')){
				$shift_from = $shift_lists[Url::get('shift_id')]['start_time'];
				$shift_to = $shift_lists[Url::get('shift_id')]['end_time'];
			}
            */
            if(Url::get('start_time'))
            {
                $shift_from = $this->calc_time(Url::get('start_time'));
                $this->map['start_shift_time'] = Url::get('start_time');
            }
            else
            {
                $shift_from = $this->calc_time('00:00');
                $this->map['start_shift_time'] = '00:00';
            }
            if(Url::get('end_time'))
            {
                $shift_to = $this->calc_time(Url::get('end_time'))+59;
                $this->map['end_shift_time'] = Url::get('end_time'); 
            }
            else
            {
                $shift_to = $this->calc_time('23:59')+59;
                $this->map['end_shift_time'] = '23:59'; 
            }                        
            
			$cond = $this->cond = ' '
				.(Url::get('bar_id')?' and bar_reservation.bar_id = '.Url::get('bar_id').'':'') 
				.(Url::get('user_id')?' and bar_reservation.checked_in_user_id = \''.Url::get('user_id').'\'':'') 
				.' and bar_reservation.time_out>='.($time_from+$shift_from).' and bar_reservation.time_out<='.($time_to + $shift_to).'
                    and bar_reservation.status=\'CHECKOUT\' '
				;
                
			if(User::can_admin(false,ANY_CATEGORY))
            {
				$cond .= Url::get('hotel_id')?' and bar_reservation.portal_id = \''.Url::get('hotel_id').'\'':'';
			}
            else
            {
				$cond .= ' and bar_reservation.portal_id = \''.PORTAL_ID.'\'';	
			}
            
			$sql = 'SELECT * FROM
					(	SELECT
							bar_reservation_product.id as id,
                            bar_reservation_product.bar_reservation_id,
                            bar_reservation_product.product_id ||\'-\'|| bar_reservation_product.name as product_id ,
                            bar_reservation_product.price,
                            (bar_reservation_product.quantity - bar_reservation_product.quantity_discount) as quantity,
                            COALESCE(bar_reservation_product.discount_rate,0) as discount_rate,
                            COALESCE(bar_reservation_product.discount_category,0) as discount_category,
                            CASE WHEN bar_reservation_product.name is null
								THEN product.name_'.Portal::language().'
								ELSE bar_reservation_product.name
                            END  as product_name,
                            bar_reservation.discount,
                            bar_reservation.discount_percent,
                            bar_reservation.bar_fee_rate as service_rate,
                            bar_reservation.tax_rate,
                            bar_reservation.full_rate,
                            bar_reservation.full_charge,
                            bar_reservation.time_out,
                            bar_reservation.departure_time,
                            product.type as product_type,
                            product_category.name as category_name
						FROM 
							bar_reservation_product
							inner join bar_reservation ON bar_reservation.id = bar_reservation_product.bar_reservation_id
                            left join product on product.id = bar_reservation_product.product_id
                            left join product_category on product_category.id = product.category_id
						WHERE 
							1>0 '.$cond.'  AND bar_reservation.bar_id in ('.$bar_ids.')
						ORDER BY
							product_category.id
					)
			WHERE
				rownum > '.((Url::get('start_page',1)-1)*$this->line_per_page).' AND rownum<='.(Url::get('no_of_page',999999999)*$this->line_per_page).'
				';
			//$report = new Report;
			$items = DB::fetch_all($sql);
            
            $product = array();
            $summary = array(
                            'FOOD'=>array(
                                            'num'=>0,
                                            'price'=>0,
                                            'quantity'=>0,
                                            'charge'=>0,
                                            'total_after_charge'=>0,
                                            'tax'=>0,
                                            'total_after_tax'=>0,
                                            
                                        ),
                            );
            $summary['DRINK'] = $summary['FOOD'];
            //System::debug($summary);
                              
			foreach($items as $key=>$value)
            {
				if($value['full_rate']==1)
                {
                    $param = (1+($value['tax_rate']*0.01) + ($value['service_rate']*0.01) + (($value['tax_rate']*0.01)*($value['service_rate']*0.01)));
					$items[$key]['price'] = round($items[$key]['price']/$param,2);
				}
                else 
                    if($value['full_charge']==1)
                    {
                        $param = (1+($value['service_rate']*0.01));
    					$items[$key]['price'] = round($items[$key]['price']/$param,2);
    				}
                    
                $items[$key]['price'] = $items[$key]['price'] * (1-$value['discount_category']/100);
                $items[$key]['price'] = $items[$key]['price'] * (1-$value['discount_rate']/100);
                
                $items[$key]['price'] = $items[$key]['price'] * $value['quantity']; 
                
                $items[$key]['charge'] = $items[$key]['price'] * ($value['service_rate']/100);
                $items[$key]['total_after_charge'] = $items[$key]['price'] + $items[$key]['charge'];
                $items[$key]['tax'] = $items[$key]['total_after_charge'] * ($value['tax_rate']/100);
                $items[$key]['total_after_tax'] = $items[$key]['total_after_charge'] + $items[$key]['tax'];
                
				$items[$key]['total'] =($value['quantity'])*$items[$key]['price'] - $items[$key]['discount'];
                
                if(!isset($product[$value['product_id']]))
                    $product[$value['product_id']] = $items[$key];
                else
                {
                    $product[$value['product_id']]['quantity'] += $items[$key]['quantity'];
                    $product[$value['product_id']]['price'] += $items[$key]['price'];
                    $product[$value['product_id']]['charge'] += $items[$key]['charge'];
                    $product[$value['product_id']]['total_after_charge'] += $items[$key]['total_after_charge'];
                    $product[$value['product_id']]['tax'] += $items[$key]['tax'];
                    $product[$value['product_id']]['total_after_tax'] += $items[$key]['total_after_tax'];
                }
                
                //Gom cac nhom do an do uong
                if($value['product_type']=='DRINK')
                    $index = 'DRINK';
                else
                    $index = 'FOOD';
                $summary[$index]['quantity'] += $items[$key]['quantity'];
                $summary[$index]['price'] += $items[$key]['price'];
                $summary[$index]['charge'] += $items[$key]['charge'];
                $summary[$index]['total_after_charge'] += $items[$key]['total_after_charge'];
                $summary[$index]['tax'] += $items[$key]['tax'];
                $summary[$index]['total_after_tax'] += round($items[$key]['total_after_tax']);                
                
			}
            //System::debug($product);
            //System::debug($summary);
			//$report->items = array();   
            $count = 0;     
			foreach($product as $key=>$value)
            {
				$count ++;  
				$product[$key]['stt'] = $count;	
				//$report->items[$count]['stt'] = $count;
			}
            
            /*            
            $final_product = array();                                    
			for($i=$time_from; $i<=$time_to;$i=$i+86400){
				foreach($product as $key=>$value){
					if($value['departure_time']>=($i+$shift_from) && ($value['departure_time']<=($i+$shift_to))){
						$count ++;  
						if($count >((Url::get('start_page',1)-1)*$this->line_per_page) && $count<=(Url::get('no_of_page',999999999)*$this->line_per_page)){
							$final_product[$count] = $value;
                            $final_product[$count]['stt'] = $count;		
						}
					}
				}	
			}       
            */     
            //System::debug($summary);	
			//$this->print_all_pages($report);
            
            //System::debug($final_product);            
            $this->map['product'] = $product;
            $this->map['summary'] = $summary;
            
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
    		$this->parse_layout('header',
    			array(
    				'hotel_address'=>$hotel_address,
    				'hotel_name'=>$hotel_name,
    			)+$this->map
    		);
            //System::debug($this->map);
    		$this->parse_layout('report',$this->map);
    		$this->parse_layout('footer',array());
            
                        
                        
		}
		else
		{
		    $_REQUEST['date_from'] = date('d/m/Y');
            $_REQUEST['date_to'] = date('d/m/Y');
            $_REQUEST['start_time'] = '00:00';
            $_REQUEST['end_time'] = '23:59'; 
			$date_arr = array();
			for($i=1;$i<=31;$i++)
            {
				$date_arr[strlen($i)<2?'0'.($i):$i] = strlen($i)<2?'0'.($i):$i;
			}
			$view_all = true;
			if(!User::can_view(false,ANY_CATEGORY) and User::can_view_detail(false,ANY_CATEGORY))
            {
				$_REQUEST['from_day'] = date('d');
				$view_all = false;
			}
            
            $bar_lists = DB::fetch_all('select bar.* from bar');
			$shift_lists = array();
			if(Session::get('bar_id'))
            {
				$bar_id = Session::get('bar_id');		
			}
            else
            {
				$bar_id = DB::fetch('select min(id) as id from bar where 1>0 and portal_id=\''.PORTAL_ID.'\'','id');
			}
			if(Url::get('bar_id'))
            {
				$bar_id = Url::get('bar_id');	
			}
			// Lay danh sach cac ca lam viec
			$shift_lists = DB::fetch_all('select bar_shift.* from bar_shift order by id ASC');
			$shift_list = '<option value="0">---------</option>';
			foreach($shift_lists as $k=>$shift)
            {
				if(isset($bar_lists[$shift['bar_id']]['shifts']))
                {
					$bar_lists[$shift['bar_id']]['shifts'][$k] = $shift; 
				}
                else
                {
					$bar_lists[$shift['bar_id']]['shifts'][$k] = $shift;	
				}
				$shift_list .= '<option value="'.$shift['id'].'">'.$shift['name'].':'.$shift['brief_start_time'].'h - '.$shift['brief_end_time'].'h</option>';	
			}
            
            /** 7211 */
			$user_privigele=DB::fetch('select group_privilege_id from account_privilege_group where account_id=\''.User::id().'\'');
            if(!$user_privigele or $user_privigele==3 or $user_privigele==4){
                
                $users = DB::fetch_all('
				SELECT
					account.id,account.id as name,account.is_active
				FROM
					account
                    INNER JOIN party on party.user_id=account.id
				WHERE
					party.type=\'USER\'
			');
            }else{
                $users = DB::fetch_all('
				SELECT
					account.id,account.id as name,account.is_active
				FROM
					account
                    INNER JOIN party on party.user_id=account.id
                    INNER JOIN account_privilege_group ON account_privilege_group.account_id=account.id
				WHERE
					party.type=\'USER\'
					AND account_privilege_group.group_privilege_id is not null and account_privilege_group.group_privilege_id !=3 and account_privilege_group.group_privilege_id !=4
			');
            }
             /** 7211 end*/			
			//System::debug($users);
            
			$this->parse_layout('search',
				array(
                'user_id_list'=>array(''=>Portal::language('all_user'))+String::get_list($users),
                'users'=>$users,				
				'view_all'=>$view_all,
				'from_day_list'=>$date_arr,
				'to_day_list'=>$date_arr,
				'bar_id_list' =>String::get_list(DB::select_all('bar',false)), 
				'hotel_id_list'=>array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list()),
                'bar_id' => Url::get('bar_id')?(Url::get('bar_id')):(Session::get('bar_id')?Session::get('bar_id'):1),
				'shift_list' =>$shift_list,
				'bars' =>DB::select_all('bar',false), 
				'bar_lists' =>String::array2js($bar_lists),
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
					'promotion'=>0
				);
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page);
			}
		}
		else
		{
			if(Url::get('hotel_id'))
            {
				$hotel = DB::fetch('SELECT NAME_1 AS name,ADDRESS FROM PARTY WHERE USER_ID = \''.Url::get('hotel_id').'\'');
				$hotel_name = $hotel['name']?$hotel['name']:HOTEL_NAME;
				$hotel_address = $hotel['address']?$hotel['address']:HOTEL_ADDRESS;
			}
            else
            {
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
	    $from_day = Url::get('date_from');
        $to_day = Url::get('date_to');
		$payment = array();
		$credit_card = 0;
		$total_currency = 0;
		foreach($items as $id=>$item)
		{
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
			if($temp = $item['quantity'])
			{
				$this->group_function_params['quantity'] += $temp;
			}
		}
		//$total = $this->group_function_params['pay_by_cash']+$fee_summary['pay_by_cash_fee']+$fee_summary['pay_by_cash_tax'];		
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
		$this->parse_layout('header',
			array(
                'to_date'=>$to_day,
                'from_date'=>$from_day,
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
    function calc_time($string)
    {
        $arr = explode(':',$string);
        //System::debug($arr);
        return $arr[0]*3600 + $arr[1]*60;
    }    
}
?>