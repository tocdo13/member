<?php
class ReceptionReportForm extends Form
{
	function ReceptionReportForm()
	{
		Form::Form('ReceptionReportForm');
        //System::debug(DB::fetch_all('select * from payment'));
	}
	function draw()
	{
		if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
			require_once 'packages/hotel/packages/reception/modules/includes/get_reservation.php';
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
			$end_month = $month;//URL::get('to_month')?URL::get('to_month'):date('m');
			if(!checkdate($month,$day,$year))
			{
				$day = 1;
			}
			if(!checkdate($end_month,$end_day,$end_year))
			{
				$end_day = Date_time::day_of_month($end_month,$end_year);
			}
			if(Url::check('today') and Url::get('today')==1){
				$day = date('d');
				$end_day = date('d');
				$month = date('m');
				$end_month = date('m');
			}
	
			$time_from = Date_Time::to_time($day.'/'.$month.'/'.$year);
			$time_to = Date_Time::to_time($end_day.'/'.$end_month.'/'.$end_year)+86400;
			$this->line_per_page = URL::get('line_per_page',999);
			$cond_page = ' AND rownum > '.((URL::get('start_page')-1)*$this->line_per_page).' and rownum<='.(URL::get('no_of_page')*$this->line_per_page);
			$cond = $this->cond = '1=1'
				.(URL::get('reservation_type_id')?' and reservation_room.reservation_type_id = '.URL::get('reservation_type_id').'':'')
				.' and reservation_room.time_in>='.Date_Time::to_time($day.'/'.$month.'/'.$year).' and reservation_room.time_in<'.(Date_Time::to_time($end_day.'/'.$end_month.'/'.$end_year)+24*3600).'';
			$cond2 = ' ';	
			if(User::can_admin(false,ANY_CATEGORY)){
				$cond .= Url::get('portal_id')?' and reservation.portal_id = \''.Url::get('portal_id').'\'':'';
				$cond2 = Url::get('portal_id')?' 1>0 and reservation.portal_id = \''.Url::get('portal_id').'\'':'1=1';
			}else{
				$cond .= ' and reservation.portal_id = \''.PORTAL_ID.'\'';	
				$cond2 =' 1>0 and reservation.portal_id = \''.PORTAL_ID.'\'';	
			}
            $cond2 .= ' AND folio.create_time>'.$time_from.' AND folio.create_time<'.$time_to.'';
			// and reservation_room.status=\'CHECKOUT\'
			//.(URL::get('user_id')?' and reservation_room.user_id = \''.URL::sget('user_id').'\'':'') 
			
			$all_payments = DB::fetch_all('select payment_type.*,payment_type.name_'.Portal::language().' as name from payment_type where def_code is not null AND def_code<>\'ROOM CHARGE\'');
			$all_currencies = DB::fetch_all('select * from currency where allow_payment=1');
			$credit_card = DB::fetch_all('select * from credit_card');
			
			$report = new Report;
			$sql= ' select reservation_room.* from reservation_room
						INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
					WHERE '.$cond.'';
			$reservations = DB::fetch_all($sql);
			$folios = $this->get_foios($cond2.$cond_page);// L?y ra các hóa don dã t?o
			if(empty($folios)){// Neu không có b?n ghi nào
				$this->parse_layout('header',
					get_time_parameters()+
					array(
						'page_no'=>0,
						'total_page'=>0,
						'hotel_name'=>Url::get('portal_id')?DB::fetch('SELECT id,name_1 FROM party WHERE user_id = \''.PORTAL_ID.'\'','name_1'):HOTEL_NAME
					)
				);
				$this->parse_layout('no_record');
				$this->parse_layout('footer',array(
					'page_no'=>0,
					'total_page'=>0
				));	
			}else{
				//$payments = $this->get_payments($cond2);//$payments = $this->get_payments($time_from,$time_to);//$cond2
                $traveller_folio = $this->get_item_folio();
                $items = array();
                
                foreach($folios as $ke=>$val)
                {
                    foreach($traveller_folio as $key=>$value)
                    {
                       
                        if($value['folio_id'] == $val['id'])
                        {
                            $items[$ke][$key]['id']=$value['folio_id'];
                            $items[$ke][$key]['date_use']=$value['date_use'];
                            $items[$ke][$key]['description']=$value['description'];
                            $items[$ke][$key]['service_rate']=$value['service_rate'];
                            $items[$ke][$key]['tax_rate']=$value['tax_rate'];
                            $items[$ke][$key]['amount']=$value['amount'];
                            $items[$ke][$key]['type']=$value['type'];
                            $items[$ke][$key]['room_name']=$value['room_name'];
                            $items[$ke][$key]['time_in']=$value['time_in'];
                            $items[$ke][$key]['time_out']=$value['time_out'];
                            $items[$ke][$key]['time']=date('d/m',$value['time_in']).'-'.date('d/m',$value['time_out']);
                            $from = date('d',$value['time_in']);
                            $to = date('d',$value['time_out']);
                            $items[$ke][$key]['h']= $to - $from;
                        }    
                    }
                }
                //System::debug($items);
                //echo '==========';
                $final_items = array();
                foreach($items as $k=>$v)
                {
                    //$final_items[$k]['amount'] = 0;
                    foreach($v as $v1)
                    {
                        //$final_items[$k][$v1['room_name']]['total_relative']=0;
                        if(!isset($final_items[$k][$v1['room_name']]['total_room']))
                        {
                            $final_items[$k][$v1['room_name']]['total_room']=0;
                            $final_items[$k][$v1['room_name']]['service_room']=0;
                            $final_items[$k][$v1['room_name']]['tax_room'] = 0;
                            $final_items[$k][$v1['room_name']]['5%']=0;
                        }
                        if(!isset($final_items[$k][$v1['room_name']]['total_minibar']))
                        {
                            $final_items[$k][$v1['room_name']]['total_minibar']=0;
                            $final_items[$k][$v1['room_name']]['service_minibar']=0;
                            $final_items[$k][$v1['room_name']]['tax_minibar']=0;
                        }
                        if(!isset($final_items[$k][$v1['room_name']]['total_bar']))
                        {
                            $final_items[$k][$v1['room_name']]['total_bar']=0;
                            $final_items[$k][$v1['room_name']]['service_bar']=0;
                            $final_items[$k][$v1['room_name']]['tax_bar'] =0;
                        }
                        if(!isset($final_items[$k][$v1['room_name']]['total_extra_service']))
                        {
                            $final_items[$k][$v1['room_name']]['total_extra_service']=0;
                            $final_items[$k][$v1['room_name']]['service_extra_service'] =0;
                            $final_items[$k][$v1['room_name']]['tax_extra_service']=0;
                        }
                        if(!isset($final_items[$k][$v1['room_name']]['total_laundry']))
                        {
                            $final_items[$k][$v1['room_name']]['total_laundry']=0;
                            $final_items[$k][$v1['room_name']]['service_laundry']=0;
                            $final_items[$k][$v1['room_name']]['tax_laundry']=0;
                        }    
                        if(!isset($final_items[$k][$v1['room_name']]['total_bar']))
                        {
                            $final_items[$k][$v1['room_name']]['total_equipment']=0;
                            $final_items[$k][$v1['room_name']]['service_equipment']=0;
                            $final_items[$k][$v1['room_name']]['tax_bar']=0;
                        }      
                        if(!isset($final_items[$k][$v1['room_name']]['total_equipment']))
                        {
                            $final_items[$k][$v1['room_name']]['total_equipment']=0;
                            $final_items[$k][$v1['room_name']]['service_equipment']=0;
                            $final_items[$k][$v1['room_name']]['tax_equipment']=0;
                        }
                        if(!isset($final_items[$k][$v1['room_name']]['total_telephone']))
                        {
                            $final_items[$k][$v1['room_name']]['total_telephone']=0;
                            $final_items[$k][$v1['room_name']]['service_telephone']=0;
                            $final_items[$k][$v1['room_name']]['tax_telephone']=0;
                        }   
                        if($v1['type'] =='EXTRA_SERVICE')
                        {
                            $final_items[$k][$v1['room_name']]['total_extra_service']+= $v1['amount'];
                            $final_items[$k][$v1['room_name']]['service_extra_service'] +=($v1['service_rate']*$v1['amount'])/100;
                            $final_items[$k][$v1['room_name']]['tax_extra_service']+= ($final_items[$k][$v1['room_name']]['service_extra_service']+$final_items[$k][$v1['room_name']]['total_extra_service'])*$v1['tax_rate']/100;
                            $final_items[$k][$v1['room_name']]['decription_extra_service'] ='Extra service '.$v1['room_name'];
                            $final_items[$k][$v1['room_name']]['time'] =$v1['time'];
                            $final_items[$k][$v1['room_name']]['h'] = $v1['h'];
                        }
                        if($v1['type'] =='ROOM')
                        {
                            $final_items[$k][$v1['room_name']]['total_room'] += $v1['amount'];
                            if($final_items[$k][$v1['room_name']]['total_room']>=1000000)
                            {
                                $final_items[$k][$v1['room_name']]['5%']+=$final_items[$k][$v1['room_name']]['total_room']*5/100;
                            }
                            $final_items[$k][$v1['room_name']]['decription_room'] ='Tiền phòng '.$v1['room_name'];
                            $final_items[$k][$v1['room_name']]['time'] =$v1['time'];
                            $final_items[$k][$v1['room_name']]['service_room'] +=($v1['service_rate']*$v1['amount'])/100;
                            $final_items[$k][$v1['room_name']]['tax_room']+= ($final_items[$k][$v1['room_name']]['service_room']+$final_items[$k][$v1['room_name']]['total_room'])*$v1['tax_rate']/100;
                            $final_items[$k][$v1['room_name']]['h'] = $v1['h'];
                        }
                        
                        if($v1['type'] =='BAR')
                        {
                            $final_items[$k][$v1['room_name']]['total_bar'] += $v1['amount'];
                            $final_items[$k][$v1['room_name']]['decription_bar'] ='nhà hàng '.$v1['room_name'];
                            $final_items[$k][$v1['room_name']]['time'] =$v1['time'];
                            $final_items[$k][$v1['room_name']]['service_bar'] +=($v1['service_rate']*$v1['amount'])/100;
                            $final_items[$k][$v1['room_name']]['tax_bar']+= ($final_items[$k][$v1['room_name']]['service_bar']+$final_items[$k][$v1['room_name']]['total_bar'])*$v1['tax_rate']/100;
                            $final_items[$k][$v1['room_name']]['h'] = $v1['h'];
                        }
                        if($v1['type'] =='MINIBAR')
                        {
                            $final_items[$k][$v1['room_name']]['total_minibar'] += $v1['amount'];
                            $final_items[$k][$v1['room_name']]['decription_minibar'] ='Minibar '.$v1['room_name'];
                            $final_items[$k][$v1['room_name']]['time'] =$v1['time'];
                            $final_items[$k][$v1['room_name']]['service_minibar'] +=($v1['service_rate']*$v1['amount'])/100;
                            $final_items[$k][$v1['room_name']]['tax_minibar']+= ($final_items[$k][$v1['room_name']]['service_minibar']+$final_items[$k][$v1['room_name']]['total_minibar'])*$v1['tax_rate']/100;
                            $final_items[$k][$v1['room_name']]['h'] = $v1['h'];
                        }
                        if($v1['type'] =='LAUNDRY')
                        {
                            $final_items[$k][$v1['room_name']]['total_laundry']+= $v1['amount'];
                            $final_items[$k][$v1['room_name']]['decription_laundry'] ='Laundry '.$v1['room_name'];
                            $final_items[$k][$v1['room_name']]['time'] =$v1['time'];
                            $final_items[$k][$v1['room_name']]['service_laundry'] +=($v1['service_rate']*$v1['amount'])/100;
                            $final_items[$k][$v1['room_name']]['tax_laundry']+= ($final_items[$k][$v1['room_name']]['service_laundry']+$final_items[$k][$v1['room_name']]['total_laundry'])*$v1['tax_rate']/100;
                            $final_items[$k][$v1['room_name']]['h'] = $v1['h'];
                        }
                        if($v1['type'] =='EQUIPMENT')
                        {
                            $final_items[$k][$v1['room_name']]['total_equipment'] += $v1['amount'];
                            $final_items[$k][$v1['room_name']]['decription_equipment'] ='Equipment '.$v1['room_name'];
                            $final_items[$k][$v1['room_name']]['time'] =$v1['time'];
                            $final_items[$k][$v1['room_name']]['service_equipment'] +=($v1['service_rate']*$v1['amount'])/100;
                            $final_items[$k][$v1['room_name']]['tax_equipment']+= ($final_items[$k][$v1['room_name']]['service_equipment']+$final_items[$k][$v1['room_name']]['total_equipment'])*$v1['tax_rate']/100;
                            $final_items[$k][$v1['room_name']]['h'] = $v1['h'];
                        }
                        if($v1['type'] =='TELEPHONE')
                        {
                            $final_items[$k][$v1['room_name']]['total_telephone'] += $v1['amount'];
                            $final_items[$k][$v1['room_name']]['decription_telephone'] ='Telephone '.$v1['room_name'];
                            $final_items[$k][$v1['room_name']]['service_telephone'] +=($v1['service_rate']*$v1['amount'])/100;
                            $final_items[$k][$v1['room_name']]['tax_telephone']+= ($final_items[$k][$v1['room_name']]['service_telephone']+$final_items[$k][$v1['room_name']]['total_telephone'])*$v1['tax_rate']/100;
                            $final_items[$k][$v1['room_name']]['time'] =$v1['time'];
                            $final_items[$k][$v1['room_name']]['h'] = $v1['h'];
                        }
                        //TỔNG TIỀN VÀ PHÍ DỊCH VỤ
                        $final_items[$k][$v1['room_name']]['room'] = $final_items[$k][$v1['room_name']]['service_room']+ $final_items[$k][$v1['room_name']]['total_room'];
                        $final_items[$k][$v1['room_name']]['extra_service'] = $final_items[$k][$v1['room_name']]['service_extra_service']+ $final_items[$k][$v1['room_name']]['total_extra_service'];    
                        $final_items[$k][$v1['room_name']]['bar'] = $final_items[$k][$v1['room_name']]['service_bar']+ $final_items[$k][$v1['room_name']]['total_bar'];
                        $final_items[$k][$v1['room_name']]['minibar'] = $final_items[$k][$v1['room_name']]['service_minibar']+ $final_items[$k][$v1['room_name']]['total_minibar'];
                        $final_items[$k][$v1['room_name']]['laundry'] = $final_items[$k][$v1['room_name']]['service_laundry']+ $final_items[$k][$v1['room_name']]['total_laundry'];  
                        $final_items[$k][$v1['room_name']]['equipment'] = $final_items[$k][$v1['room_name']]['service_equipment']+ $final_items[$k][$v1['room_name']]['total_equipment']; 
                        $final_items[$k][$v1['room_name']]['telephone'] = $final_items[$k][$v1['room_name']]['service_telephone']+ $final_items[$k][$v1['room_name']]['total_telephone']; 
                        // TONG TIEN PHI DICH VU VA THUE
                        $final_items[$k][$v1['room_name']]['amount_room'] = $final_items[$k][$v1['room_name']]['room']+ $final_items[$k][$v1['room_name']]['tax_room'];    
                        $final_items[$k][$v1['room_name']]['amount_extra_service'] = $final_items[$k][$v1['room_name']]['extra_service']+ $final_items[$k][$v1['room_name']]['tax_extra_service'];
                        $final_items[$k][$v1['room_name']]['amount_bar'] = $final_items[$k][$v1['room_name']]['bar']+ $final_items[$k][$v1['room_name']]['tax_bar'];
                        $final_items[$k][$v1['room_name']]['amount_minibar'] = $final_items[$k][$v1['room_name']]['minibar']+ $final_items[$k][$v1['room_name']]['tax_minibar'];
                        $final_items[$k][$v1['room_name']]['amount_laundry'] = $final_items[$k][$v1['room_name']]['laundry']+ $final_items[$k][$v1['room_name']]['tax_laundry'];  
                        $final_items[$k][$v1['room_name']]['amount_equipment'] = $final_items[$k][$v1['room_name']]['equipment']+ $final_items[$k][$v1['room_name']]['tax_equipment']; 
                        $final_items[$k][$v1['room_name']]['amount_telephone'] = $final_items[$k][$v1['room_name']]['telephone']+ $final_items[$k][$v1['room_name']]['tax_telephone'];
                        //Tong theo hoa don
                        $final_items[$k][$v1['room_name']]['total_order'] = $final_items[$k][$v1['room_name']]['amount_room']+$final_items[$k][$v1['room_name']]['amount_extra_service']+$final_items[$k][$v1['room_name']]['amount_bar']+$final_items[$k][$v1['room_name']]['amount_minibar']+$final_items[$k][$v1['room_name']]['amount_laundry']+$final_items[$k][$v1['room_name']]['amount_equipment']+$final_items[$k][$v1['room_name']]['amount_telephone'];
                    }  
                }
               //System::Debug($final_items);
               
				if(empty($final_items))
                {
					$this->parse_layout('header',
					get_time_parameters()+
						array(
							'page_no'=>0,
							'total_page'=>0,
							'hotel_name'=>Url::get('portal_id')?DB::fetch('SELECT id,name_1 FROM party WHERE user_id = \''.PORTAL_ID.'\'','name_1'):HOTEL_NAME
						)
					);
					$this->parse_layout('no_record');
					$this->parse_layout('footer',array(
						'page_no'=>0,
						'total_page'=>0
					));	
				}
                else
                {
                    foreach($final_items as $key => $value)
                    {
                        $final_items[$key]['total_rela'] = 0;
                        foreach($value as $k => $v)
                        {   
                            $final_items[$key][$k]['total_relative'] = 0;
                            if($v['total_extra_service'] > 0)
                            {
                                $final_items[$key][$k]['total_relative'] += 1;
                            }
                            if($v['total_telephone'] > 0)
                            {
                                $final_items[$key][$k]['total_relative'] += 1;
                            }
                            if($v['total_equipment'] > 0)
                            {
                                $final_items[$key][$k]['total_relative'] += 1;
                            }
                            if($v['total_laundry'] > 0)
                            {
                                $final_items[$key][$k]['total_relative'] += 1;
                            }
                            if($v['total_minibar'] > 0)
                            {
                                $final_items[$key][$k]['total_relative'] += 1;
                            }
                            if($v['total_bar'] > 0)
                            {
                                $final_items[$key][$k]['total_relative'] += 1;
                            }
                            if($v['total_room'] > 0)
                            {
                                $final_items[$key][$k]['total_relative'] += 1;
                            }
                            $final_items[$key]['total_rela'] += $final_items[$key][$k]['total_relative'];
                        }
                    }
					$report = new Report;
					$report->items = $final_items;
					$this->print_all_pages($report);
				}
			}
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
			$portals = Portal::get_portal_list();
			$this->parse_layout('search',
				array(
				'portal_id_list'=>array(''=>Portal::language('all')) + String::get_list($portals),
				'from_day_list'=>$date_arr,
				'to_day_list'=>$date_arr,
				)
			);	
		}
        //System::debug($final_items);		
	}
	function print_all_pages(&$report)
	{
		$count = 0;
		$total_page = 1;
		$pages = array();
		if(empty($report->items)){
			if($count>=$this->line_per_page)
			{
				$count = 0;
				$total_page++;
			}
			$items[$total_page]['id'] = $total_page;
			
			$pages[$total_page][$total_page] = $items[$total_page];
			$count++;
		}else{
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
		}
		if(sizeof($pages)>0)
		{
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page);
			}
		}
		else
		{
			$this->parse_layout('header',
			get_time_parameters()+
				array(
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
		$this->parse_layout('header',
			array(
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)
		);
        //if(User::is_admin())
        //System::debug($report->payment_types);
        //System::debug($report->currencies);
        //System::debug($items);
		$this->parse_layout('report',array(
				'items'=>$items,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)
		);
		$this->parse_layout('footer',array(				
			'page_no'=>$page_no,
			'total_page'=>$total_page
		));
	}
	function get_foios($cond=' 1>0'){
		$sql = '    SELECT
						folio.id as id
						,folio.total
						,folio.reservation_id as r_id
						,folio.reservation_room_id as rr_id
						,folio.reservation_traveller_id as rt_id
						,folio.customer_id
						,0 as foc
						,0 as deposit  
						,(customer.code || \' : \'|| customer.name) as customer_name
						,reservation_traveller.id as traveller_id
						,(\'KHLE: \' || traveller.first_name || \' \' || traveller.last_name) as full_name
						,room.name as room_name
						,folio.create_time as time,
						CASE WHEN (reservation_room.checked_out_user_id is null)
							THEN \'Khách chua out\'
							ELSE reservation_room.checked_out_user_id
						END	as user_id
					FROM
						folio
						INNER JOIN reservation ON reservation.id = folio.reservation_id 
						LEFT OUTER JOIN reservation_room ON reservation_room.reservation_id = reservation.id
						LEFT OUTER JOIN reservation_traveller ON reservation_room.id = reservation_traveller.reservation_room_id
						LEFT OUTER JOIN traveller ON traveller.id = reservation_traveller.traveller_id
						LEFT OUTER JOIN room ON room.id = reservation_room.room_id
						LEFT OUTER JOIN customer ON customer.id = reservation.customer_id
					WHERE 
						'.$cond.'
					ORDER BY
						folio.id DESC
			';
            if(User::is_admin())
            {
                //System::debug($sql);
                //System::debug(DB::fetch_all($sql));
            }
			return DB::fetch_all($sql);
	}
	function get_item_folio($cond=' 1>0'){
		$sql = 'select
                    rt.id
					,(rt.type || \'_\' || rt.folio_id ) as order_id
					,(rt.amount + (rt.amount*rt.service_rate*0.01) + ((rt.amount + (rt.amount*rt.service_rate*0.01))*rt.tax_rate*0.01)) as total_amount
					,rt.reservation_traveller_id as rt_id
                    ,rt.*
                    ,reservation_room.id as r_r_id
                    ,room.name as room_name
                    ,reservation_room.time_in
					,reservation_room.time_out
				FROM traveller_folio rt
					left outer join reservation ON rt.reservation_id = reservation.id
					left outer join reservation_room ON reservation_room.id = rt.reservation_room_id
                    INNER JOIN room ON room.id = reservation_room.room_id 
				WHERE '.$cond.' AND rt.add_payment!=1
			';
		return DB::fetch_all($sql);
	}
}
?>