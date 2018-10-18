<?php
class RepostReservationThuhoForm extends Form
{
	function RepostReservationThuhoForm()
	{
		Form::Form('RepostReservationThuhoForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{
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
            //System::debug($bar_ids);
            $_REQUEST['bar_name'] = $bar_name;
            
            $now = getdate();
            $now_date = $now['mday']."/".$now['mon']."/".$now['year'];
            $date_from = URL::get('date_from')?URL::get('date_from'):$now_date;
            $date_to = URL::get('date_to')?URL::get('date_to'):$now_date;
            $this->map['date_from'] = $date_from;
            $this->map['date_to'] = $date_to;
            $_REQUEST['date_from'] = $date_from;
            $_REQUEST['date_to'] = $date_to;
            $time_to_from_date = Date_Time::to_time($date_from);
            $time_to_date = Date_Time::to_time($date_to);
            //code manh sua
            $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        
            $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
            
            $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
            
			$cond = $this->cond = ''
				.(URL::get('bar_id')?' and bar_reservation.bar_id = '.URL::get('bar_id').'':'') 
				.(URL::get('category_id')?' AND '.IDStructure::child_cond(DB::structure_id('product_category',intval(URL::get('category_id')))):'') 
				.' and bar_reservation.time_out>='.$time_to_from_date.' and bar_reservation.time_out<'.($time_to_date+86399).'';
			if(Url::get('customer_name')){
				$cond .= ' AND product.name LIKE \'%'.Url::get('customer_name').'%\'';
			}
			if(Url::get('code')){
				$cond .= ' AND upper(customer.code) LIKE \'%'.strtoupper(Url::sget('code')).'%\'';
			}
            
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
            }
            else
            {
                $cond .=" AND bar_reservation.portal_id = '".PORTAL_ID. "'";
            }
            
			$sql = '
                        SELECT
							brp.id
							,brp.bar_reservation_id as order_id
							,brp.product_id
							,brp.remain
							,brp.price
							,brp.quantity as quantity
							,brp.discount
							,brp.discount_rate
							,brp.discount_category
							,brp.quantity_discount
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
                            ,bar_reservation.bar_id
                            ,bar_reservation.deposit
                            ,room.name||\'-\'||bar_reservation.code as name
                            ,unit.name_1
                            ,bar_reservation.pay_with_room
                            ,bar_reservation.amount_pay_with_room
                            ,reservation.booking_code
                            ,reservation.id as reservation_id
                            ,RESERVATION_ROOM.id as reservation_room_id
                            ,room.name as room_name
						FROM 
							bar_reservation_product brp
							INNER JOIN bar_reservation ON bar_reservation.id = brp.bar_reservation_id
                            INNER JOIN RESERVATION_ROOM ON BAR_RESERVATION.RESERVATION_ROOM_ID = RESERVATION_ROOM.ID
                            INNER JOIN reservation ON RESERVATION_ROOM.reservation_id = reservation.id
                            INNER JOIN ROOM ON RESERVATION_ROOM.ROOM_ID = ROOM.ID
                            INNER JOIN UNIT ON brp.UNIT_ID = UNIT.ID
							INNER JOIN product_price_list on product_price_list.id=brp.price_id
							INNER JOIN product on product.id = product_price_list.product_id
							LEFT OUTER JOIN product_category on product_category.id = product.category_id
						WHERE 
							1>0 '.$cond.' AND bar_reservation.bar_id in ('.$bar_ids.')
						ORDER BY
							brp.bar_reservation_id DESC ';
			$report = new Report;
			$report->items = DB::fetch_all($sql);
            $total_items = array();
            $b_r ='';
            $i = 0;
            $k = 1;
            $res_bar_id = false;
            $this->map['amount_with_room'] =0;
            foreach($report->items as $key=>$value)
            {
				
				 $prices= (($value['price']*$value['quantity'])*(1-$value['discount_rate']/100))*(1-$value['order_discount_percent']/100);
              
                if($value['full_charge']!=1 and $value['full_rate']!=1){
                    $before_charge = ($prices*$value['service_rate']/100);
                }else{
                     $before_charge=0;
                }
               // echo $before_charge.'<br/>';
                $prices = $prices+$before_charge;
                if($value['full_rate']==1){
                    $taxrate=0;
                }elseif($value['full_rate']!=1){
                    $taxrate = ($prices*$value['tax_rate']/100); 
				}
				
                $report->items[$key]['product_total'] =  $prices+$taxrate;
         /* (($value['price']*$value['quantity'])-($value['price']*$value['quantity']*$value['discount_rate']/100))
                
         - (($value['price']*$value['quantity'])-($value['price']*$value['quantity']*$value['discount_rate']/100))*
                  
                  $value['order_discount_percent']/100; */
                  
                  
                  
                if($report->items[$key]['order_id'] != $res_bar_id)
                {
                    
                    $report->items[$key]['res_bar_id'] = $k++;
                    $res_bar_id = $report->items[$key]['order_id'];
                    $this->map['amount_with_room'] += $value['amount_pay_with_room'];
                    
                }
                if(!isset($total_items[$value['name']]))
                {
                    $total_items[$value['name']]['id'] = $value['name'];
                    $total_items[$value['name']]['deposit'] = 0;
                    $total_items[$value['name']]['total'] = 0;
                    $total_items[$value['name']]['num'] = 0;
                    $total_items[$value['name']]['child'] = 0;
                }
                if($b_r != $value['name'])
                {
                    $b_r = $value['name'];
                }   
                if($b_r == $value['name'])
                {
                    
                    $total_items[$value['name']]['id'] = $value['name'];
                    $total_items[$value['name']]['deposit'] = $value['deposit'];
                    $total_items[$value['name']]['total'] += $report->items[$key]['product_total'];
                    $total_items[$value['name']]['num'] += $value['quantity'] ;
                    $total_items[$value['name']]['child'] += 1;
                }
                $report->items[$key]['no'] = $i;
                
     
            }
            
            //System::debug($report->items);
            if(sizeof($report->items)==0)
            {
                $this->parse_layout('no-report',$this->map);
            }
            else
            {
                //System::debug($total_items);
                $this->print_all_pages($report,$total_items);
                
            }
		}
		else // n?u kh�ng submit
		{
			$view_all = true;
			if(!User::can_view(false,ANY_CATEGORY) and User::can_view_detail(false,ANY_CATEGORY)){
				$_REQUEST['from_day'] = date('d');
				$view_all = false;
			}
			$restaurant_category = DB::select('product_category','id=11');
			
             //Start Luu Nguyen GIap add portal
            $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
             //End Luu Nguyen GIap add portal
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
             
            $categories=array();
			$this->parse_layout('search',$this->map+
				array(				
				'view_all'=>$view_all,
				'category_id_list'=>array(''=>Portal::language('all_category'))+($categories),
				'bar_id' => URL::get('bar_id',''),
				'bars' =>$bars
				)
			);	
		}			
	}
	function print_all_pages(&$report,$total_items)
	{
        
		$count = 0;
		$total_page = 1;
		$pages = array();
		foreach($report->items as $key=>$item)
		{
			if(isset($report->items[$key]['res_bar_id']))
            {
                
                $count+= 1;
            }
            //count >= so dong tren 1 trang thi reset ve 0 va tang so trang len 1
            if($count>$this->map['line_per_page'])
        	{
        		$count = 1;
        		$total_page++;
        	}  
			$pages[$total_page][$key] = $item;
		}

		if(sizeof($pages)>0)
		{
			$this->group_function_params = array(
					'total'=>0,
					'quantity'=>0,					
					'discount'=>0
					
				);
			$this->map['real_total_page']=count($pages);
            $this->map['real_page_no'] = 1;
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $page_no,$total_page,$total_items);
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
	function print_page($items, $page_no,$total_page,$total_items)
	{
	    //System::debug($record);
      //  System::debug($total_items);    
		$last_group_function_params = $this->group_function_params;
        
		foreach($items as $item)
		{
			
            $this->group_function_params['total'] += ($item['product_total'] - $item['deposit']);
            $this->group_function_params['quantity'] += $item['quantity'];
			
		}
       // System::debug($items); 
		//$total = $this->group_function_params['pay_by_cash']+$fee_summary['pay_by_cash_fee']+$fee_summary['pay_by_cash_tax'];		
		if($page_no>=$this->map['start_page'])
        {            
    		$this->parse_layout('header',
    			array(
    				'page_no'=>$page_no,
    				'total_page'=>$total_page,
    			)+$this->map
    		);
            
    		$this->parse_layout('report',
                array(
    				'items'=>$items,
                    'total_items'=>$total_items,
    				'last_group_function_params'=>$last_group_function_params,
    				'group_function_params'=>$this->group_function_params,
    				'page_no'=>$page_no,
    				'total_page'=>$total_page,
    			)+$this->map
    		);
    		$this->parse_layout('footer',array(				
    			'page_no'=>$page_no,
    			'total_page'=>$total_page,
    		)+$this->map);
        }
	}
    
	

	}	
    

?>