<?php
/** Report RoomBillVatRevenueReportForm 
    - bao cao tinh doanh thu dua vao nhung folio da duoc in vat.
    - truong hop in nhom se gop folio vao.    
**/
class RoomBillVatRevenueReportForm extends Form
{
	function RoomBillVatRevenueReportForm()
	{
		Form::Form('RoomBillVatRevenueReportForm');
         $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
         $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		 $this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
	{
	   $this->map = array();
	   /** lay danh sach portal **/
           $portals = Portal::get_portal_list();
           $this->map['portal_id_list'] = array(''=>Portal::language('all')) + String::get_list($portals);
       /** lay danh sach nguoi dung **/
           $users = DB::fetch_all('
    			SELECT
    				party.user_id as id,party.user_id as name
    			FROM
    				party
    				INNER JOIN account ON party.user_id = account.id
    			WHERE
                    (account.portal_department_id <> \'1001\' AND account.portal_department_id <> \'1002\' )
    				AND party.type=\'USER\'
    				AND account.is_active = 1
    			ORDER BY 
    				party.user_id ASC
    		');
            $this->map['user_id_list'] = array(''=>Portal::language('all'))+String::get_list($users);
        /** khoi tao gia tri ban dau cho cac tieu chi **/
            $cond_folio = '1=1';
            $cond_bill = '1=1';
            
            $this->map['portal_id'] = Url::get('portal_id')?Url::get('portal_id'):'';
            $cond_folio .= Url::get('portal_id')?'AND (folio.portal_id=\''.Url::get('portal_id').'\')':'';
            
            $this->map['user_id'] = Url::get('user_id')?Url::get('user_id'):'';
            $cond_bill .= Url::get('user_id')?' AND (vat_bill.user_print=\''.Url::get('user_id').'\' or vat_bill.last_user_print=\''.Url::get('user_id').'\')':'';
            
            $day = getdate();
            if(Url::get('to_day'))
            {
                $start_day = Date_Time::to_time($day['mday']."/".$day['mon']."/".$day['year']);
                $end_day = Date_Time::to_time($day['mday']."/".$day['mon']."/".$day['year'])+$this->calc_time('23:59');
                $this->map['from_time'] = '00:00';
                $this->map['to_time'] = '23:59';
                $this->map['from_date'] = $day['mday']."/".$day['mon']."/".$day['year'];
                $this->map['to_date'] = $day['mday']."/".$day['mon']."/".$day['year'];
            }
            else
            {
                $this->map['from_time'] = Url::get('from_time')?Url::get('from_time'):'00:00';
                $this->map['to_time'] = Url::get('to_time')?Url::get('to_time'):'23:59';
                $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):$day['mday']."/".$day['mon']."/".$day['year'];
                $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):$day['mday']."/".$day['mon']."/".$day['year'];
                $start_day = Date_Time::to_time($this->map['from_date'])+$this->calc_time($this->map['from_time']);
                $end_day = Date_Time::to_time($this->map['to_date'])+$this->calc_time($this->map['to_time']);
            }
            $cond_bill .= "AND ((vat_bill.time_print>=$start_day AND vat_bill.time_print<=$end_day) or (vat_bill.last_time_print>=$start_day AND vat_bill.last_time_print<=$end_day))";
            
            $this->map['from_bill'] = Url::get('from_bill')?Url::get('from_bill'):'';
            $cond_bill .= Url::get('from_bill')?"AND vat_bill.def_code>=".Url::get('from_bill'):'';
            $this->map['to_bill'] = Url::get('to_bill')?Url::get('to_bill'):'';
            $cond_bill .= Url::get('to_bill')?"AND vat_bill.def_code<=".Url::get('to_bill'):'';
            
            $this->map['line_per_page'] = Url::get('line_per_page')?Url::get('line_per_page'):'50';
            $this->map['no_of_page'] = Url::get('no_of_page')?Url::get('no_of_page'):'32';
            $this->map['start_page'] = Url::get('start_page')?Url::get('start_page'):'1';
        
        /** lay cac hinh thuc thanh toan **/
            $payment_type = DB::fetch_all("SELECT payment_type.def_code as id, payment_type.name_".portal::language()." as name FROM payment_type WHERE def_code is not null AND def_code<>'ROOM CHARGE'");
            $currency = DB::fetch_all("select id,name from currency where allow_payment=1 and id='VND'");
            //System::debug($payment_type);            
        /** lay gia tri ban dau cua SUM **/
            $this->group_function_params = array();
            foreach($payment_type as $id1=>$value1)
            {
                if($value1['id']!='CREDIT_CARD' AND $value1['id']!='DEBIT' AND $value1['id']!='FOC')
                {
                    foreach($currency as $id2=>$value2)
                    {
                        $this->group_function_params[$value1['id']."_".$value2['id']] = 0;
                    }
                }
                else
                {
                    $this->group_function_params[$value1['id']."_VND"] = 0;
                }
                $this->group_function_params['total_amout'] = 0;
            }
        /** lay du lieu **/
            $items = array();
            /** -- lay tat ca bill le tan co dieu kien la $cond_bill -- **/
            $list_bill = DB::fetch_all("SELECT vat_bill.* FROM vat_bill WHERE ".$cond_bill." AND vat_bill.department='RECEPTION' AND vat_bill.folio_id is not null ORDER BY vat_bill.id DESC");
            $items = $list_bill;
            /** -- lay ra thong tin folio tu bill da lay dc o tren, dem so folio trong bill. -- **/
            $i=1;
            foreach($items as $key=>$value)
            {
                $user = DB::fetch('SELECT party.name_'.Portal::language().' as name from party where party.user_id=\''.$value['user_print'].'\'');
                $items[$key]['user_print'] = $user['name'];
                $last_user = DB::fetch('SELECT party.name_'.Portal::language().' as name from party where party.user_id=\''.$value['last_user_print'].'\'');
                $items[$key]['last_user_print'] = $user['name'];
                $items[$key]['stt'] = $i++;
                $items[$key]['time_print'] = date('H:i d/m/Y',$value['time_print']);
                $items[$key]['last_time_print'] = date('H:i d/m/Y',$value['last_time_print']);
                
                $items[$key]['count_folio'] = 1;
                $items[$key]['child'] = array();
                if(strpos($value['folio_id'],',')) /** in group **/
                {
                    $folio_ids = explode(',',$value['folio_id']);
                    $items[$key]['count_folio'] = sizeof($folio_ids);
                    for($i=0;$i<sizeof($folio_ids);$i++)
                    {
                        $j=$i+1;
                        $items[$key]['child'][$j] = $this->get_folio($folio_ids[$i],$cond_folio,$currency,$payment_type);
                    }
                }
                else /** in single **/
                {
                    $items[$key]['child'][1] = $this->get_folio($value['folio_id'],$cond_folio,$currency,$payment_type);
                }
                $this->group_function_params['total_amout'] += $value['total_amount'];
            }
            $_REQUEST += $this->map;
            $this->parse_layout('report',array(
                                                'currency'=>$currency,
                                                'payment_type'=>$payment_type,
                                                'items'=>$items,
                                                'group_function_params'=>$this->group_function_params
                                                )+$this->map);
            
	}
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
    function get_folio($folio_id,$cond,$currency,$payment_type)
    {
        $folio_detail = DB::fetch(" SELECT 
                                        folio.*,
                                        reservation_traveller.reservation_room_id,
                                        CONCAT(CONCAT(traveller.first_name,' '),traveller.last_name) as traveller_name,
                                        customer.name as customer_name,
                                        room.name as room_name
                                    FROM 
                                        folio
                                        left join reservation_traveller on reservation_traveller.id = folio.reservation_traveller_id
                                        left join traveller on traveller.id=reservation_traveller.traveller_id
                                        left join customer on customer.id=folio.customer_id
                                        left join reservation_room on reservation_traveller.reservation_room_id=reservation_room.id
                                        left join room on room.id=reservation_room.room_id
                                    WHERE 
                                        ".$cond." AND folio.id=".$folio_id."
                                        ");
        foreach($payment_type as $pay=>$type)
        {
            if($type['id']!='CREDIT_CARD' AND $type['id']!='DEBIT' AND $type['id']!='FOC')
            {
                foreach($currency as $cur=>$cey)
                {
                    $folio_detail[$type['id']."_".$cey['id']] = 0;
                }
            }
            else
            {
                $folio_detail[$type['id']."_VND"] = 0;
            }
        }
        $payment_folio = DB::fetch_all("SELECT payment.* FROM payment inner join folio on folio.id=payment.folio_id WHERE ".$cond." AND payment.folio_id=".$folio_id." AND payment.type='RESERVATION'");
        foreach($payment_folio as $id=>$value)
        {
            if($value['payment_type_id']!='CREDIT_CARD' AND $value['payment_type_id']!='DEBIT' AND $value['payment_type_id']!='FOC')
            {
                $folio_detail[$value['payment_type_id']."_".$value['currency_id']] += $value['amount'];
                $this->group_function_params[$value['payment_type_id']."_".$value['currency_id']] += $value['amount'];
            }
            else
            {
                $folio_detail[$value['payment_type_id']."_VND"] += $value['amount'];
                $this->group_function_params[$value['payment_type_id']."_VND"] += $value['amount'];
            }
        }
        
        return $folio_detail;
    }
}

?>