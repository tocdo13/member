<?php
//note
/***
1. check gia full_rate, full_charge hay khoong de tinh thanh tien cho san pham
2. khi tinh thanh tien cho san pham phai nhan voi giam gia cho hoa don
3. cac hang hoa nen de o category con:
 - do an ngoai danh muc nen de trong category 'ngoai danh muc'-con cua ROOT
 - hoi nghi nen de thuoc trong 1 category con, khon nen de hoi nghi la con truc tiep cua danh muc do an
4. sua ham function direct_child_cond($structure_id, $child_level=1,$extra = '') trong class IDStructure
***/
class KaraokeRevenueReportByDateForm extends Form
{
	function KaraokeRevenueReportByDateForm()
	{
		Form::Form('KaraokeRevenueReportByDateForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->add('from_date',new TextType(true,'miss_from_date',0,255));
        $this->add('to_date',new TextType(true,'miss_to_date',0,255));
        $this->add('from_time',new TextType(true,'miss_from_time',0,255));
        $this->add('to_time',new TextType(true,'miss_to_time',0,255));
	}
    
    function to_time($date,$time)
	{
	   $arr_time = explode(":",$time);
       return Date_Time::to_time($date)
            + (isset($arr_time[0])?intval($arr_time[0]):0)*3600
            + (isset($arr_time[1])?intval($arr_time[1]):0)*60;
    }
    
    function get_category_by_level($categories,$level,$accept='')
	{
        $result_struct = array();
        foreach($categories as $key => $value)
        {
            $struct_level = $value['structure_id'];
            while(IDStructure::level($struct_level)>$level)
            {
                $struct_level = IDStructure::parent($struct_level);
            }
            if(!isset($result_struct[$struct_level]) and $struct_level!= $accept)
            {
                $result_struct[$struct_level] = array('struct'=>$struct_level);
            }
        }
        
        $cond_struct = '';
        foreach($result_struct as $key=>$value)
            $cond_struct.=",".$value['struct'];
        $cond_struct = $cond_struct?" product_category.structure_id in (".substr($cond_struct,1,strlen($cond_struct)-1).") ":" 1=1 ";
        
        $categories = DB::fetch_all("SELECT
        					product_category.id
                            ,product_category.code
        					,product_category.name
        					,product_category.structure_id
                            ,0 as total_amount
                            ,0 as total_guest
        				FROM
        					product_category
        				WHERE ".$cond_struct."
        				ORDER BY product_category.position");
        return $categories;
    }
    
	function draw()
	{
        $_REQUEST['from_date'] = Url::get("from_date",date('01/m/Y'));
        $_REQUEST['from_time'] = Url::get("from_time",'00:00');
        $_REQUEST['to_date'] = Url::get("to_date",date('d/m/Y'));
        $_REQUEST['to_time'] = Url::get("to_time",'23:59');
        $this->map = array();
        $str_karaoke_ids='';
        foreach($_REQUEST as $key=>$value)
        {
            if(strpos($key,'ar_id_'))
            {
                $str_karaoke_ids.=",".$value;
            }
        }
                
        //get name karaoke
        $this->karaoke_names='';
        $cond_karaoke = $str_karaoke_ids?" id in (".substr($str_karaoke_ids,1,strlen($str_karaoke_ids)-1).")":"";
        if($cond_karaoke)
        {
            $karaokes = DB::fetch_all("select *
                                    from karaoke
                                    where ".$cond_karaoke);
            //System::debug($karaokes);
            $karaoke_names = '';
            foreach($karaokes as $key=>$value)
                $karaoke_names.=",".$value['name'];
            $this->karaoke_names = substr($karaoke_names,1,strlen($karaoke_names)-1);
        }
                
        $from_time = $this->to_time($_REQUEST['from_date'],$_REQUEST['from_time']);     
        $to_time = $this->to_time($_REQUEST['to_date'],$_REQUEST['to_time']); 
        $this->line_per_page = URL::get('line_per_page',15);
                
        /***get categories***/
        //get cond department
        $cond_karaoke_d = $str_karaoke_ids?" and id in (".substr($str_karaoke_ids,1,strlen($str_karaoke_ids)-1).")":"";
        $cond_portal_d = Url::get('portal_id')?" and portal_id = '".Url::get('portal_id')."'":"";
        $dp_code = DB::fetch_all("select department_id as id from karaoke where 1=1 ".$cond_karaoke_d.$cond_portal_d);
        $str_dp_codes='';
        foreach($dp_code as $key=>$value)
        {
            $str_dp_codes.=",'".$value['id']."'";
        }
                
        $cond_dep_cate = $str_dp_codes?" and product_price_list.department_code in (".substr($str_dp_codes,1,strlen($str_dp_codes)-1).")":"";
        $cond_portal_cate = Url::get('portal_id')?" and product_price_list.portal_id = '".Url::get('portal_id')."'":"";
        //get cond structure_id
        require_once 'packages/core/includes/system/id_structure.php';
        $ROOT_ID = "1000000000000000000";
        
        $cond_struct_cate = "(".IDStructure::child_cond($ROOT_ID,1,"product_category.").")";
                
        //categories level = DA
        $categories = DB::fetch_all("SELECT
        					product_category.id
                            ,product_category.code
        					,product_category.name
        					,product_category.structure_id
                            ,0 as total_amount
        				FROM
        					product_category
        					INNER JOIN product ON product_category.id = product.category_id
        					INNER JOIN product_price_list ON product_price_list.product_id = product.id
        					INNER JOIN unit ON unit.id = product.unit_id
        				WHERE ".$cond_struct_cate.$cond_dep_cate.$cond_portal_cate."
        				ORDER BY product_category.position");
                //categories child of DA
        $categories = $this->get_category_by_level($categories,1);
        
        $categories_invi = array();
        $categories_hidd = array();
        foreach($categories as $key=>$value)
        {
            if(Url::get('categorie_'.$value['id']))
                $categories_invi[$key] = $value;
            else
                $categories_hidd[$key] = $value;
        }
        
        if(Url::get('do_search'))
        {
            if($this->check())
            {
                /***get items***/
                $cond_karaoke_brp = $str_karaoke_ids?" and karaoke_reser.karaoke_id in (".substr($str_karaoke_ids,1,strlen($str_karaoke_ids)-1).")":"";
                $cond_portal_brp = Url::get('portal_id')?" and karaoke_reser.portal_id = '".Url::get('portal_id')."'":"";
                
                $karaoke_rp = DB::fetch_all("select karaoke_reservation_product.*,
                                            karaoke_reser.tax_rate,
                                            karaoke_reser.karaoke_fee_rate,
                                            product_category.structure_id,
                                            karaoke_reser.num_guest,
                                            case
                                                when karaoke_reser.full_rate = 1
                                                then karaoke_reservation_product.quantity*karaoke_reservation_product.price*(1-karaoke_reser.discount_percent/100)
                                                else(
                                                    case 
                                                        when karaoke_reser.full_charge = 1
                                                        then karaoke_reservation_product.quantity*karaoke_reservation_product.price*(1+karaoke_reser.tax_rate/100)*(1-karaoke_reser.discount_percent/100)
                                                        else  (karaoke_reservation_product.quantity*karaoke_reservation_product.price*(1+karaoke_reser.karaoke_fee_rate/100))*(1+karaoke_reser.tax_rate/100)*(1-karaoke_reser.discount_percent/100)
                                                    end
                                                )
                                            end as total_amount,
                                            from_unixtime(karaoke_reser.time_out) as day
                                        from karaoke_reservation_product
                                            inner join (
                                                select sum(num_people) as num_guest,
                                                    karaoke_reservation.id,
                                                    karaoke_reservation.time_out,
                                                    karaoke_reservation.tax_rate,
                                                    karaoke_reservation.discount_percent,
                                                    karaoke_reservation.karaoke_fee_rate,
                                                    karaoke_reservation.karaoke_id,
                                                    karaoke_reservation.full_rate,
                                                    karaoke_reservation.full_charge,
                                                    karaoke_reservation.portal_id
                                                from karaoke_reservation
                                                  inner join karaoke_reservation_table on karaoke_reservation_table.karaoke_reservation_id = karaoke_reservation.id
                                                where karaoke_reservation.status != 'CANCEL' 
                                                group by (karaoke_reservation.id,karaoke_reservation.time_out,karaoke_reservation.tax_rate,karaoke_reservation.karaoke_fee_rate,karaoke_reservation.karaoke_id,karaoke_reservation.portal_id,karaoke_reservation.status,karaoke_reservation.discount_percent,karaoke_reservation.full_rate,karaoke_reservation.full_charge)
                                              ) karaoke_reser 
                                                    on (karaoke_reser.id = karaoke_reservation_product.karaoke_reservation_id
                                                    and karaoke_reser.time_out >= ".$from_time." 
                                                    and karaoke_reser.time_out <= ".$to_time.$cond_karaoke_brp.$cond_portal_brp.")
                                            inner join product on product.id = karaoke_reservation_product.product_id
                                            inner join product_category on product_category.id = product.category_id
                                        where karaoke_reser.time_out >= ".$from_time." 
                                            and karaoke_reser.time_out <= ".$to_time.$cond_karaoke_brp.$cond_portal_brp.
                                        " order by karaoke_reser.time_out desc");
                
                //System::debug($karaoke_rp);
                
                $items = array();
                foreach($karaoke_rp as $key => $value)
                {
                    //check date
                    if(!isset($items[$value['day']]))
                    {
                        $items[$value['day']] = array( 'day'=>$value['day'],
                                                        'stt'=>0,
                                                        'categories_invi'=>$categories_invi,
                                                        'total_amount_nonfood_other'=>0,
                                                        'total_p'=>0,
                                                        'deposit'=>0,
                                                        'cash'=>0,
                                                        'credit'=>0,
                                                        'with_room'=>0,
                                                        'free'=>0,
                                                        'debit'=>0,
                                                        'total'=>0
                                                        );
                    }
                    
                    //check ischild cua categories
                    foreach($categories_invi as $k=>$v)
                    {
                        if(IDStructure::is_child($value['structure_id'],$v['structure_id']) or $value['structure_id'] == $v['structure_id'])
                        {
                            $items[$value['day']]['categories_invi'][$k]['total_amount'] += $value['total_amount'];
                            $items[$value['day']]['total_p'] += $value['total_amount'];
                        }
                    }
                    foreach($categories_hidd as $k=>$v)
                    {
                        if(IDStructure::is_child($value['structure_id'],$v['structure_id']) or $value['structure_id'] == $v['structure_id'])
                        {
                            $items[$value['day']]['total_amount_nonfood_other'] += $value['total_amount'];
                            $items[$value['day']]['total_p'] += $value['total_amount'];
                        }
                    }
                }
                
                //get all karaoke_reservation and payment acomplish
                $karaoke_resers = DB::fetch_all("   select payment.id||'_'||karaoke_reser.id as id,
                                                    payment.amount,
                                                    payment.payment_type_id,
                                                    payment.type_dps,
                                                    karaoke_reser.id as karaoke_reser_id,
                                                    karaoke_reser.total,
                                                    karaoke_reser.pay_with_room,
                                                    from_unixtime(karaoke_reser.time_out) as day
                                                from karaoke_reservation karaoke_reser
                                                    left outer join payment on karaoke_reser.id = payment.bill_id and payment.type = 'KARAOKE'
                                                where karaoke_reser.status != 'CANCEL' 
                                                    and karaoke_reser.time_out >= ".$from_time." 
                                                    and karaoke_reser.time_out <= ".$to_time.$cond_karaoke_brp.$cond_portal_brp.
                                                " order by karaoke_reser.time_out desc");
                
                
                $arr_karaoke_resers = array();
                foreach($karaoke_resers as $key => $value)
                {
                    if(!isset($arr_karaoke_resers[$value['karaoke_reser_id']]))
                    {
                        $arr_karaoke_resers[$value['karaoke_reser_id']] = array('deposit'=>0,
                                                                        'cash'=>0,
                                                                        'credit'=>0,
                                                                        'with_room'=>0,
                                                                        'free'=>0,
                                                                        'debit'=>0,
                                                                        'total'=>0,
                                                                        'pay_with_room'=>0,
                                                                        'day'=>'');
                    }
                    $arr_karaoke_resers[$value['karaoke_reser_id']]['day'] = $value['day'];
                    $arr_karaoke_resers[$value['karaoke_reser_id']]['pay_with_room'] = $value['pay_with_room'];
                    $arr_karaoke_resers[$value['karaoke_reser_id']]['total'] = $value['total'];
                    if($value['type_dps'])
                        $arr_karaoke_resers[$value['karaoke_reser_id']]['deposit'] += $value['amount'];
                    else
                    {
                        switch($value['payment_type_id'])
                        {
                            case 'CASH' : $arr_karaoke_resers[$value['karaoke_reser_id']]['cash'] += $value['amount']; break;
                            case 'CREDIT_CARD' : $arr_karaoke_resers[$value['karaoke_reser_id']]['credit'] += $value['amount']; break;
                            case 'DEBIT' : $arr_karaoke_resers[$value['karaoke_reser_id']]['debit'] += $value['amount']; break;
                            case 'FOC' : $arr_karaoke_resers[$value['karaoke_reser_id']]['free'] += $value['amount']; break;
                            case 'BANK' : $arr_karaoke_resers[$value['karaoke_reser_id']]['credit'] += $value['amount']; break;
                            default : $arr_karaoke_resers[$value['karaoke_reser_id']]['cash'] += $value['amount']; break;
                        }
                    }
                }
                
                foreach($arr_karaoke_resers as $key => $value)
                {
                    if($value['pay_with_room'])
                    {
                        $arr_karaoke_resers[$key]['with_room'] = $value['total']-
                                                            ( 
                                                                $value['free']+
                                                                $value['deposit']
                                                            );
                        $arr_karaoke_resers[$key]['cash']=0;
                        $arr_karaoke_resers[$key]['credit']=0;
                        $arr_karaoke_resers[$key]['debit']=0;
                    }
                    else
                    {
                        $arr_karaoke_resers[$key]['debit'] += $value['total']-
                                                            (   
                                                                $value['cash']+
                                                                $value['credit']+
                                                                $value['debit']+
                                                                $value['free']+
                                                                $value['deposit']
                                                            );
                    }
                    
                    if(isset($items[$value['day']]))
                    {
                        $items[$value['day']]['deposit'] += $arr_karaoke_resers[$key]['deposit'];
                        $items[$value['day']]['cash'] += $arr_karaoke_resers[$key]['cash'];
                        $items[$value['day']]['credit'] += $arr_karaoke_resers[$key]['credit'];
                        $items[$value['day']]['with_room'] += $arr_karaoke_resers[$key]['with_room'];
                        $items[$value['day']]['free'] += $arr_karaoke_resers[$key]['free'];
                        $items[$value['day']]['debit'] += $arr_karaoke_resers[$key]['debit'];
                        $items[$value['day']]['total'] += $arr_karaoke_resers[$key]['total'];
                    }
                }
                //loai cac ban ghi co tong tien =0
                $stt=1;
                foreach($items as $key=>$value)
                {
                    if($value['total'])
                        $items[$key]['stt']=$stt++;
                    else
                        unset($items[$key]);
                }
                
                //System::debug($items); exit();
                require_once 'packages/core/includes/utils/lib/report.php';
                $report = new Report;
    			$report->items = $items;
    			$report->categories_invi = $categories_invi;
                $report->categories_hidd = $categories_hidd;
                $this->print_all_pages($report);
            }
        }	
        else
        {
            $cond = '1=1';
            if(Url::get('portal_id'))
                $cond .= " and portal_id = '".$_REQUEST['portal_id']."'";
            $karaoke_lists = DB::fetch_all('select karaoke.* from karaoke where '.$cond);
            $this->parse_layout('search',array('karaokes' =>$karaoke_lists,
                                                'categories' =>$categories,
                                                'portal_id_list'=>array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list())
				                                ));
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
        
        if(Url::get('portal_id')){
            $hotel = DB::fetch('SELECT NAME_1 AS name,ADDRESS FROM PARTY WHERE USER_ID = \''.Url::get('portal_id').'\'');
            $hotel_name = $hotel['name']?$hotel['name']:HOTEL_NAME;
            $hotel_address = $hotel['address']?$hotel['address']:HOTEL_ADDRESS;
		}else{
            $hotel_name = HOTEL_NAME;
            $hotel_address = HOTEL_ADDRESS;
		}	
		$this->parse_layout('header',
				array(
					'hotel_address'=>$hotel_address,
					'hotel_name'=>$hotel_name,
					'karaoke_names'=>$this->karaoke_names,
                    'categories_invi'=>$report->categories_invi,
                    'categories_hidd'=>$report->categories_hidd
				));
			
		if(sizeof($pages)>0)
		{
			$this->group_function_params =   array( 'categories_invi'=>$report->categories_invi,
                                                    'total_amount_nonfood_other'=>0,
                                                    'total_p'=>0,
                                                    'deposit'=>0,
                                                    'cash'=>0,
                                                    'credit'=>0,
                                                    'with_room'=>0,
                                                    'free'=>0,
                                                    'debit'=>0,
                                                    'total'=>0
                                                    );
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page);
			}
		}
        $this->parse_layout('footer',array('categories_invi'=>$report->categories_invi,
                                            'categories_hidd'=>$report->categories_hidd));
	}
	function print_page($items, &$report, $page_no,$total_page)
	{
		$last_group_function_params = $this->group_function_params;
        foreach($items as $key=>$value)
        {
            foreach($value['categories_invi'] as $k=>$v)
            {
                $this->group_function_params['categories_invi'][$k]['total_amount'] += $v['total_amount'];
            }
            $this->group_function_params['total_amount_nonfood_other'] += $value['total_amount_nonfood_other'];
            $this->group_function_params['total_p'] += $value['total_p'];
            $this->group_function_params['deposit'] += $value['deposit'];
            $this->group_function_params['cash'] += $value['cash'];
            $this->group_function_params['credit'] += $value['credit'];
            $this->group_function_params['with_room'] += $value['with_room'];
            $this->group_function_params['free'] += $value['free'];
            $this->group_function_params['debit'] += $value['debit'];
            $this->group_function_params['total'] += $value['total'];
        }
        //System::debug($this->group_function_params);
		$this->parse_layout('report_date',array(
				'items'=>$items,
                'categories_invi'=>$report->categories_invi,
                'categories_hidd'=>$report->categories_hidd,
				'last_group_function_params'=>array(1=>$last_group_function_params),
				'group_function_params'=>array(1=>$this->group_function_params),
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)
		);
	}
}
?>