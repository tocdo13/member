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
            /*if(Url::get('karaoke_id'))
            {
				Session::set('karaoke_id',intval(Url::get('karaoke_id')));
			}
            else
            {
				$karaoke_id = Session::get('karaoke_id');	
			}
			$_REQUEST['karaoke_id'] = Session::get('karaoke_id');*/
			$karaokes = DB::fetch_all('select * from karaoke');
			$karaoke_ids = '';
            $karaoke_name = '';
			foreach($karaokes as $k => $karaoke){
				if(Url::get('karaoke_id_'.$k)){
					$karaoke_ids .= ($karaoke_ids=='')?$k:(','.$k);	
                    $karaoke_name .= ($karaoke_name=='')?$karaoke['name']:(', '.$karaoke['name']);
				}
			}
            //System::debug($karaoke_ids);
            $karaoke_ids  = $karaoke_ids?$karaoke_ids:0;
            $_REQUEST['karaoke_name'] = $karaoke_name;
            
			$from_day = Url::get('date_from');
            $to_day = Url::get('date_to');
			$this->line_per_page = Url::get('line_per_page',999999999);
            
            
            $shift_lists = DB::fetch_all('select karaoke_shift.* from karaoke_shift order by id ASC');
			
            $time_from = Date_Time::to_time($from_day);
            $time_to = Date_Time::to_time($to_day);            
			$this->map['time_from'] = ($from_day);
            $this->map['time_to'] = ($to_day);           
           
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
                $shift_to = $this->calc_time(Url::get('end_time'));
                $this->map['end_shift_time'] = Url::get('end_time'); 
            }
            else
            {
                $shift_to = $this->calc_time('23:59');
                $this->map['end_shift_time'] = '23:59'; 
            }                        
            
			$cond = $this->cond = ' '
				.(Url::get('karaoke_id')?' and karaoke_reservation.karaoke_id = '.Url::get('karaoke_id').'':'') 
				.(Url::get('user_id')?' and karaoke_reservation.checked_in_user_id = \''.Url::get('user_id').'\'':'') 
				.' and karaoke_reservation.time_out>='.($time_from+$shift_from).' and karaoke_reservation.time_out<='.($time_to + $shift_to).'
                    and karaoke_reservation.status=\'CHECKOUT\' '
				;
                
			if(User::can_admin(false,ANY_CATEGORY))
            {
				$cond .= Url::get('hotel_id')?' and karaoke_reservation.portal_id = \''.Url::get('hotel_id').'\'':'';
			}
            else
            {
				$cond .= ' and karaoke_reservation.portal_id = \''.PORTAL_ID.'\'';	
			}
            
			$sql = 'SELECT * FROM
					(	SELECT
							karaoke_reservation_product.id as id,
                            karaoke_reservation_product.karaoke_reservation_id,
                            karaoke_reservation_product.product_id ||\'-\'|| karaoke_reservation_product.name as product_id ,
                            karaoke_reservation_product.price,
                            (karaoke_reservation_product.quantity - karaoke_reservation_product.quantity_discount) as quantity,
                            COALESCE(karaoke_reservation_product.discount_rate,0) as discount_rate,
                            COALESCE(karaoke_reservation_product.discount_category,0) as discount_category,
                            CASE WHEN karaoke_reservation_product.name is null
								THEN product.name_'.Portal::language().'
								ELSE karaoke_reservation_product.name
                            END  as product_name,
                            karaoke_reservation.discount,
                            karaoke_reservation.discount_percent,
                            karaoke_reservation.karaoke_fee_rate as service_rate,
                            karaoke_reservation.tax_rate,
                            karaoke_reservation.full_rate,
                            karaoke_reservation.full_charge,
                            karaoke_reservation.time_out,
                            karaoke_reservation.departure_time,
                            product.type as product_type,
                            product_category.name as category_name
						FROM 
							karaoke_reservation_product
							inner join karaoke_reservation ON karaoke_reservation.id = karaoke_reservation_product.karaoke_reservation_id
                            left join product on product.id = karaoke_reservation_product.product_id
                            left join product_category on product_category.id = product.category_id
						WHERE 
							1>0 '.$cond.'  AND karaoke_reservation.karaoke_id in ('.$karaoke_ids.')
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
            $_REQUEST['start_time'] = '00:00';
            $_REQUEST['end_time'] = '23:59'; 
            $_REQUEST['date_from'] = date('d/m/Y');
            $_REQUEST['date_to'] = date('d/m/Y');
		
        
			$view_all = true;
			if(!User::can_view(false,ANY_CATEGORY) and User::can_view_detail(false,ANY_CATEGORY))
            {
				$_REQUEST['from_day'] = date('d');
				$view_all = false;
			}
            
            $karaoke_lists = DB::fetch_all('select karaoke.* from karaoke');
			$shift_lists = array();
			if(Session::get('karaoke_id'))
            {
				$karaoke_id = Session::get('karaoke_id');		
			}
            else
            {
				$karaoke_id = DB::fetch('select min(id) as id from karaoke where 1>0 and portal_id=\''.PORTAL_ID.'\'','id');
			}
			if(Url::get('karaoke_id'))
            {
				$karaoke_id = Url::get('karaoke_id');	
			}
			// Lay danh sach cac ca lam viec
			$shift_lists = DB::fetch_all('select karaoke_shift.* from karaoke_shift order by id ASC');
			$shift_list = '<option value="0">---------</option>';
			foreach($shift_lists as $k=>$shift)
            {
				if(isset($karaoke_lists[$shift['karaoke_id']]['shifts']))
                {
					$karaoke_lists[$shift['karaoke_id']]['shifts'][$k] = $shift; 
				}
                else
                {
					$karaoke_lists[$shift['karaoke_id']]['shifts'][$k] = $shift;	
				}
				$shift_list .= '<option value="'.$shift['id'].'">'.$shift['name'].':'.$shift['brief_start_time'].'h - '.$shift['brief_end_time'].'h</option>';	
			}
            
            $users = DB::fetch_all('select 
										account.id
										,party.full_name 
									from account 
									INNER JOIN party on party.user_id = account.id AND party.type=\'USER\' WHERE account.type=\'USER\' AND LOWER(FN_CONVERT_TO_VN(party.description_1))=\'nha hang\' ORDER BY account.id');			
			//System::debug($users);
            
			$this->parse_layout('search',
				array(
                'user_id_list'=>array(''=>Portal::language('all_user'))+String::get_list($users),				
				'view_all'=>$view_all,
				
				'karaoke_id_list' =>String::get_list(DB::select_all('karaoke',false)), 
				'hotel_id_list'=>array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list()),
                'karaoke_id' => Url::get('karaoke_id')?(Url::get('karaoke_id')):(Session::get('karaoke_id')?Session::get('karaoke_id'):1),
				'shift_list' =>$shift_list,
				'karaokes' =>DB::select_all('karaoke',false), 
				'karaoke_lists' =>String::array2js($karaoke_lists),
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