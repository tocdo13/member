<?php
class HousekeepingRevenueReportForm extends Form
{
	function HousekeepingRevenueReportForm()
	{
		Form::Form('HousekeepingRevenueReportForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{
        $this->map=array();
        //$this->map['date_from'] = Url::sget('date_from')?Url::sget('date_from'):('01/'.date('m/Y'));
		//$this->map['date_to'] = Url::sget('date_to')?Url::sget('date_to'):(cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y')).'/'.date('m/Y'));
       	$this->map['date_from'] = Url::sget('date_from')?Url::sget('date_from'):'';
        $this->map['date_to'] = Url::sget('date_to')?Url::sget('date_to'):'';
        if(Url::get('hour_from'))
            {
                $shift_from = $this->calc_time(Url::get('hour_from'));
                $this->map['start_shift_time'] = Url::get('hour_from');
            }
            else
            {
                $shift_from = $this->calc_time('00:00');
                $this->map['start_shift_time'] = '00:00';
            }
            if(Url::get('hour_to'))
            {
                $shift_to = $this->calc_time(Url::get('hour_to'))+59;
                $this->map['end_shift_time'] = Url::get('hour_to');      
            }
            else
            {
                $shift_to = $this->calc_time('23:59')+59;
                $this->map['end_shift_time'] = '23:59'; 
            }
     
        $_REQUEST['date_from'] = $this->map['date_from'];   
        $_REQUEST['date_to'] = $this->map['date_to'];
        //KimTan: xem theo gio
        $this->map['hour_from'] = Url::get('hour_from')?Url::get('hour_from'):'00:00';
        $_REQUEST['hour_from'] = $this->map['hour_from'];
        $this->map['hour_to'] = Url::get('hour_to')?Url::get('hour_to'):'23:59';
        $_REQUEST['hour_to'] = $this->map['hour_to'];
        $arr_hour_from = explode(":",$this->map['hour_from']);
        $arr_hour_to = explode(":",$this->map['hour_to']);
        $date_from = Date_Time::to_time($this->map['date_from'])+$arr_hour_from[0]*3600+$arr_hour_from[1]*60;
        $date_to = Date_Time::to_time($this->map['date_to'])+$arr_hour_to[0]*3600+$arr_hour_to[1]*60+59;
        //END KimTan: xem theo gio
        $this->map['type'] = Url::get('type')?Url::get('type'):2; 
		if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
			$this->line_per_page = URL::get('line_per_page',15);
			$cond = '
					1 >0'
					.(URL::get('type')==1?' and housekeeping_invoice.type=\'MINIBAR\'':' and housekeeping_invoice.type=\'LAUNDRY\'') 
					.(URL::get('product_id')?' and housekeeping_invoice_detail.product_id=\''.URL::get('product_id').'\'':'') 
			;
            if(Url::get('res_id')=='' and Url::get('search_time'))
            {
                $cond .=' and housekeeping_invoice.time>=\''.$date_from.'\' and housekeeping_invoice.time<=\''.$date_to.'\' and (reservation_room.status=\'CHECKOUT\' OR reservation_room.status=\'CHECKIN\')';
            }
				
			$report = new Report;
            // lay bao cao theo tieu chi product hoac invoice
			$group = Url::get('group');
            $this->map['group'] = $group;
			// lay bao cao theo portal
            //Start Luu Nguyen Giap add portal
            if(Url::get('hotel_id'))
            {
                $portal_id = Url::get('hotel_id');
            }
            else
            {
                $portal_id = PORTAL_ID;                       
            }
            if($portal_id != 'ALL')
            {
                $cond .=" AND housekeeping_invoice.portal_id = '".$portal_id. "'";
            }
            if(Url::get('res_id')!='')
                $cond .=" AND reservation.id = '".Url::get('res_id'). "'";
                
            //start: KID them de tim theo ma hoa don
            $from_code = Url::get('from_code');
			$to_code = Url::get('to_code');
            if(Url::get('from_code') && Url::get('to_code'))
            {
    			
                if($from_code>0 && $to_code>0 && $to_code>=$from_code)
                {
    		         $cond .= ' AND housekeeping_invoice.position >='.$from_code.' AND housekeeping_invoice.position<='.$to_code.'';
                }
            }else if(Url::get('from_code') && !Url::get('to_code')){
                $cond .= ' AND housekeeping_invoice.position >='.$from_code.' ';
            }else if(!Url::get('from_code') && Url::get('to_code')){
                $cond .= ' AND housekeeping_invoice.position<='.$to_code.'';
            }
          
    		//end: KID them de tim theo ma hoa don
            //end portal
            // xet $group de xem lay theo product hay invoice. 1=product, 2=invoice
            if($group==2)
            {// lay bao cao theo hoa don - INVOICE
                $sql = '
                    SELECT 
                        distinct housekeeping_invoice.id,
                       -- ROW_NUMBER() OVER(ORDER BY distinct housekeeping_invoice.id DESC) AS id, 
						housekeeping_invoice.total,
						housekeeping_invoice.total_before_tax,
						housekeeping_invoice.tax_rate,
						housekeeping_invoice.fee_rate,
                        housekeeping_invoice.time,
						housekeeping_invoice.user_id,
                        LPAD(housekeeping_invoice.position,5,0) as position,
						room.name as room_name, 
                        concat(concat(traveller.first_name,\' \'),traveller.last_name) as traveller_name,
						LPAD(housekeeping_invoice.id,5,0) as code,
                        housekeeping_invoice.code as housekeeping_invoice_code,
                        reservation.id as res_id
					FROM
						housekeeping_invoice
                        right join housekeeping_invoice_detail on housekeeping_invoice_detail.invoice_id=housekeeping_invoice.id                        
						inner join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id
						inner join reservation on reservation.id=reservation_room.reservation_id
						inner join room on reservation_room.room_id = room.id
						left outer join traveller on traveller.id = reservation_room.TRAVELLER_ID
					WHERE 
						'.$cond.'
					ORDER BY
						housekeeping_invoice.id desc
                ';
                //system::debug($sql);
                $report_invoice = DB::fetch_all($sql);
                //System::debug($report_invoice);
                // l?p m?ng �? t�nh thu? ph� v� reset time th�nh ng�y
                $this->map['summary_total_before_tax'] = 0;
                $this->map['summary_fee_rate'] = 0;
                $this->map['summary_tax_rate'] = 0;
                $this->map['summary_total'] = 0;
                foreach($report_invoice as $key=>$value)
                {
                    $report_invoice[$key]['date'] = date('d/m/Y H:i',$value['time']);
                    $this->map['summary_total_before_tax'] += $value['total_before_tax'];
                    $report_invoice[$key]['fee_rate'] = ($value['fee_rate']*$value['total_before_tax'])/100;
                    $this->map['summary_fee_rate'] += ($value['fee_rate']*$value['total_before_tax'])/100;
                    $report_invoice[$key]['tax_rate'] = ($value['total_before_tax']+ (($value['fee_rate']*$value['total_before_tax'])/100))*$value['tax_rate']/100;
                    $this->map['summary_tax_rate'] += ($value['total_before_tax']+ (($value['fee_rate']*$value['total_before_tax'])/100))*$value['tax_rate']/100;
                    $this->map['summary_total'] += $value['total'];
                }
                //System::debug($this->map);
                //System::debug($report_invoice);
                $this->parse_layout('header',array('from_bill'=>Url::get('from_code'),'to_bill'=>Url::get('to_code'))+$this->map);
                $this->parse_layout('report_invoice',$this->map+array('items'=>$report_invoice));
                $this->parse_layout('footer',$this->map);
            }
            else
            {// lay bao cao theo san pham - product
                $sql = "
                    SELECT 
                        housekeeping_invoice_detail.id as id,
                        housekeeping_invoice_detail.product_id,
                        housekeeping_invoice_detail.price,
                        product_price_list.original_price,
                        housekeeping_invoice_detail.quantity,
                        NVL(housekeeping_invoice_detail.promotion,0) as promotion,
                        housekeeping_invoice.express_rate,
                        housekeeping_invoice.discount,
                        housekeeping_invoice.fee_rate,
                        housekeeping_invoice.tax_rate,
                        NVL(housekeeping_invoice.net_price,0) as net_price,
                        product.name_".Portal::language()." as product_name,
                        product.category_id as category,
                        unit.name_".Portal::language()." as unit,
                        CASE 
                            WHEN NVL(housekeeping_invoice.net_price,0) = 1
                            THEN  
                                (((housekeeping_invoice_detail.price/(1 + housekeeping_invoice.tax_rate*0.01))/(1 + housekeeping_invoice.fee_rate*0.01))*(housekeeping_invoice_detail.quantity-NVL(housekeeping_invoice_detail.promotion,0)))-((((housekeeping_invoice_detail.price/(1 + housekeeping_invoice.tax_rate*0.01))/(1 + housekeeping_invoice.fee_rate*0.01))*(housekeeping_invoice_detail.quantity-NVL(housekeeping_invoice_detail.promotion,0)))*housekeeping_invoice.discount*0.01)  
                            ELSE
                                (housekeeping_invoice_detail.price*(housekeeping_invoice_detail.quantity-NVL(housekeeping_invoice_detail.promotion,0)))-((housekeeping_invoice_detail.price*(housekeeping_invoice_detail.quantity-NVL(housekeeping_invoice_detail.promotion,0)))*housekeeping_invoice.discount*0.01)
                        END total,
                        case
                            WHEN NVL(housekeeping_invoice.net_price,0) = 1
                            then 
                                (housekeeping_invoice_detail.price/(1 + housekeeping_invoice.tax_rate*0.01))/(1 + housekeeping_invoice.fee_rate*0.01)
                            else
                                housekeeping_invoice_detail.price
                            end total_before_tax
                        ,
                        CASE 
                            WHEN NVL(housekeeping_invoice.net_price,0) = 1
                            THEN  
                                (((product_price_list.original_price/(1 + housekeeping_invoice.tax_rate*0.01))/(1 + housekeeping_invoice.fee_rate*0.01))*(housekeeping_invoice_detail.quantity-0))-((((product_price_list.original_price/(1 + housekeeping_invoice.tax_rate*0.01))/(1 + housekeeping_invoice.fee_rate*0.01))*(housekeeping_invoice_detail.quantity-0))*0*0.01)  
                            ELSE
                                (product_price_list.original_price*(housekeeping_invoice_detail.quantity-0))-((product_price_list.original_price*(housekeeping_invoice_detail.quantity-0))*0*0.01)
                        END original_total,
                        case
                            WHEN NVL(housekeeping_invoice.net_price,0) = 1
                            then 
                                (product_price_list.original_price/(1 + housekeeping_invoice.tax_rate*0.01))/(1 + housekeeping_invoice.fee_rate*0.01)
                            else
                                product_price_list.original_price
                            end original_total_before_tax
                        ,reservation.id as res_id
                    FROM
                        housekeeping_invoice_detail
						left outer join housekeeping_invoice on housekeeping_invoice_detail.invoice_id=housekeeping_invoice.id
						left outer join product on housekeeping_invoice_detail.product_id=product.id
                        left outer join product_price_list on product_price_list.product_id=product.id
                        left outer join unit on product.unit_id=unit.id
						inner join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id
						inner join reservation on reservation.id=reservation_room.reservation_id
						inner join room on reservation_room.room_id = room.id
						left outer join traveller on traveller.id = reservation_room.TRAVELLER_ID
                        
					WHERE 
						".$cond." AND housekeeping_invoice_detail.quantity != 0
                    ORDER BY
                        housekeeping_invoice_detail.product_id desc,
                        total_before_tax desc,
                        housekeeping_invoice.discount desc
                ";
                
                $report_product = DB::fetch_all($sql);
                //if(User::id()=='developer06')
                //System::debug($report_product);
                $this->map['total_befor_tax'] = 0;
                $this->map['original_total_befor_tax'] = 0;
                $this->map['interest_rate_total'] = 0;
                $this->map['total_express_rate'] = 0;
                $this->map['total_fee_rate'] = 0;
                $this->map['total_tax_rate'] = 0;
                $this->map['total'] = 0;
                foreach($report_product as $id=>$content)
                {
                    $this->map['total_befor_tax'] += $content['total'];
                    $this->map['original_total_befor_tax'] += $content['original_total'];
                    $this->map['interest_rate_total'] += ($content['total']-$content['original_total']);
                    $this->map['total_express_rate'] +=  $content['total']*$content['express_rate']*0.01;
                    $this->map['total_fee_rate'] += ($content['total'] + $content['total']*$content['express_rate']*0.01)*$content['fee_rate']*0.01;
                    $this->map['total_tax_rate'] += ((($content['total'] + $content['total']*$content['express_rate']*0.01)+(($content['total'] + $content['total']*$content['express_rate']*0.01)*$content['fee_rate']*0.01)))*$content['tax_rate']*0.01;
                }
                $this->map['total_amount'] = ($this->map['total_befor_tax']+$this->map['total_express_rate']+$this->map['total_fee_rate']+$this->map['total_tax_rate']); 
                $this->map['total_befor_tax'] = ($this->map['total_befor_tax']);
                $this->map['total_express_rate'] = ($this->map['total_express_rate']);
                $this->map['total_fee_rate'] = ($this->map['total_fee_rate']);
                $count = array();
                $items = array();
                if(Portal::language()==1)
                {
                    $name ='name';
                    
                    
                }
                else
                {
                    $name ='name_en';
                    
                }
                $sql = '
        			select 
        				id, code, '.$name.' as name
        			from
        				product_category
        			where
        				'.IDStructure::direct_child_cond(DB::fetch('select id, structure_id from product_category where code = \'GL\'','structure_id')).'
        			order by
        				structure_id
        		';	
        		$this->map['categories'] = DB::fetch_all($sql);
                foreach($report_product as $k => $v)
                {
                    if(!isset($count[$v['product_id']]))
                    {
                        $count[$v['product_id']] = array('count'=>0);
                        $count[$v['product_id']][$v['total_before_tax']][$v['discount']] = 0;
                        $count[$v['product_id']][$v['total_before_tax']][$v['discount'].'_quantity'] = 0;
                        $count[$v['product_id']][$v['total_before_tax']][$v['discount'].'_promotion'] = 0;
                        $count[$v['product_id']][$v['total_before_tax']][$v['discount'].'_total_before'] = 0;
                        $count[$v['product_id']][$v['total_before_tax']][$v['discount'].'_original_total_before'] = 0;
                    }
                    else
                    {
                        if(!isset($count[$v['product_id']][$v['total_before_tax']][$v['discount']]))
                        {
                            $count[$v['product_id']][$v['total_before_tax']][$v['discount']] = 0;
                        }
                        if(!isset($count[$v['product_id']][$v['total_before_tax']][$v['discount'].'_quantity']))
                        {
                            $count[$v['product_id']][$v['total_before_tax']][$v['discount'].'_quantity'] = 0;
                        }
                        if(!isset($count[$v['product_id']][$v['total_before_tax']][$v['discount'].'_promotion']))
                        {
                            $count[$v['product_id']][$v['total_before_tax']][$v['discount'].'_promotion'] = 0;
                        }
                        if(!isset($count[$v['product_id']][$v['total_before_tax']][$v['discount'].'_total_before']))
                        {
                            $count[$v['product_id']][$v['total_before_tax']][$v['discount'].'_total_before'] = 0;
                        }
                        if(!isset($count[$v['product_id']][$v['total_before_tax']][$v['discount'].'_original_total_before']))
                        {
                            $count[$v['product_id']][$v['total_before_tax']][$v['discount'].'_original_total_before'] = 0;
                        }
                    }
                    
                    $count[$v['product_id']]['count']++;
                    $count[$v['product_id']][$v['total_before_tax']][$v['discount']]++; 
                    $count[$v['product_id']][$v['total_before_tax']][$v['discount'].'_quantity'] += $v['quantity'];
                    $count[$v['product_id']][$v['total_before_tax']][$v['discount'].'_promotion'] += $v['promotion'];
                    $count[$v['product_id']][$v['total_before_tax']][$v['discount'].'_total_before'] += $v['total'];
                    $count[$v['product_id']][$v['total_before_tax']][$v['discount'].'_original_total_before'] += $v['original_total'];
                    
                    /** gop theo nhom 1,2,3 **/
                    $this->map['categories'][$v['category']]['items'][$k] = $v;
                    if(!isset($this->map['categories'][$v['category']]['name']))
                        $this->map['categories'][$v['category']]['name'] = '';
                }
                //System::debug($this->map['categories']);
                $this->parse_layout('header',array('from_bill'=>Url::get('from_code'),'to_bill'=>Url::get('to_code'))+$this->map);
                if(URL::get('type')==1)
                {
                    $report_layout = "report_1";
                }
                else
                {
                    $report_layout = "report";
                }
                $this->parse_layout($report_layout,$this->map+array('items'=>$report_product,'count'=>$count));
                $this->parse_layout('footer',$this->map);
            }
			
		}
        else
        {
            $_REQUEST['hotel_id'] = PORTAL_ID;
			$this->parse_layout('search',array(
				'hotel_id_list'=>array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list())
			));
		}			
	}

	   function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
	
}
?>