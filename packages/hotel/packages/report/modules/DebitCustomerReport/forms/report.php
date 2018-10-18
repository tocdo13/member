<?php
/*********
developer07:giap.ln 5/2015
Bao cao lay ra tat ca nhung reservation_room lay ra tat ca trong ngay xem tro ve ngay arrival_time 
Bao gom: tien phong + minibar laundry + tien nha hang chuyen ve phong + dich vu mo rong
spa chuyen ve phong + telephone + karaoke
Thong tin: tong so tien tinh den ngay xem, so tien da tra  tinh den ngay xem, 
tien dat coc tinh den ngay xem, tien con lai tinh den ngay xem 
*********/
class DebitCustomerReportForm  extends Form
{
	function DebitCustomerReportForm ()
	{
		Form::Form('DebitCustomerReportForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    	$this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
	}
	function draw()
	{
        $this->map = array(); 
        
        $this->load_data();
        $this->parse_layout('report',$this->map);
	} 
    
    function load_data()
    {
        if(!isset($_REQUEST['do_search']))
        {
            $date =date('d/m/Y');
            $_REQUEST['from_date'] = $date;
            $d = mktime(0,0,0,date('m'),date('d'),date('Y'));
            $date_oracle = Date_Time::convert_time_to_ora_date($d);
           
        }
        else
        {
            $date = $_REQUEST['from_date'];
            $d_str = explode("/",$date);
            $d = mktime(0,0,0,$d_str[1],$d_str[0],$d_str[2]);
            $date_oracle = Date_Time::convert_time_to_ora_date($d);
        }
        
        $d +=86400;
        //echo $date_oracle;
        //echo $d;
        //1. Tinh tong so tien xem trong ngay xem tro ve truoc
        //Tien phong xem trong ngay + tien minibar & laundry + extra services + restaurant 
        //+ spa + telephone 
        //echo date('H:i:s d/m/Y',$d).'<br/>'.$date.'<br/>'.$date_oracle;
        $total_money = $this->get_money_total($d,$date,$date_oracle);
        //System::debug($total_money);
        //2. Tinh so tien da thanh toan xem theo ngay xem tro ve truoc
        //Tien thanh toan theo folio phong + restaurant + spa
        $total_money_payed = $this->get_money_payed($d,$date_oracle);
            
        //3. Tinh so tien da dat coc trong ngay xem tro ve truoc bao gom
        //Dat coc tien phong + dat coc Nha hang chuyen ve phong 
        $deposit_money = $this->get_deposit_money($d,$date_oracle);
          
        $res_id = false;
        
        $items = array();
        foreach($total_money as $row)
        {
            if($res_id!=$row['reservation_id'])
            {
                //4. voi moi reservation_id tinh  tong tien can thanh toan
                //Tien phong + minibar + laundry + extra service + restaurant + spa 
                //Tung reservation: tao mang va tinh tong gia tri chua thanh toan 
                $items[$row['reservation_id']]['rooms'] = $this->get_info_reservation($row['reservation_id']);
                $items[$row['reservation_id']]['total_money'] = 0;
                $items[$row['reservation_id']]['customer_name'] = $row['customer_name'];
                
                //5. voi moi reservation lay ra tien thanh toan cho nhom
                $items[$row['reservation_id']]['total_money_payed'] = 0;
                $total_payment_group =0;
                $items[$row['reservation_id']]['is_deposit_folio_group'] =0;
                $total_payment_group = $this->get_payment_group_folio($row['reservation_id'],$d);
                if($total_payment_group!=0)
                    $items[$row['reservation_id']]['is_deposit_folio_group'] = 1;
                $items[$row['reservation_id']]['total_money_payed'] +=$total_payment_group;
                
                //6. Voi moi reservation_id tinh tong so tien dat coc: phong + nhom + nha hang
                $items[$row['reservation_id']]['total_money_deposit'] = 0;
                $total_money_deposit =0;
                $total_money_deposit = $this->get_money_deposit($row['reservation_id'],$d);
                if($total_money_deposit!=0)
                    $items[$row['reservation_id']]['is_deposit_folio_group'] = 1;
                $items[$row['reservation_id']]['total_money_deposit'] +=$total_money_deposit;
                $items[$row['reservation_id']]['recode'] = $row['reservation_id'];
                
                $res_id = $row['reservation_id'];
            }
            if($items[$row['reservation_id']]['is_deposit_folio_group']==1)//neu co thanh toan nhom hoac dat coc nhom 
            {
                //4. Tong so tien So tien can thanh toan toi ngay xem tro ve truoc  
                if(empty($row['total_money_room'])==false)
                    $items[$row['reservation_id']]['total_money'] += $row['total_money_room'];
                if(empty($row['total_money_extra'])==false)
                    $items[$row['reservation_id']]['total_money'] += $row['total_money_extra'];
                if(empty($row['total_minibar_laundry'])==false)
                    $items[$row['reservation_id']]['total_money'] += $row['total_minibar_laundry'];
                if(empty($row['total_money_restaurant'])==false)
                    $items[$row['reservation_id']]['total_money'] += $row['total_money_restaurant'];
                if(empty($row['total_money_massage'])==false)
                    $items[$row['reservation_id']]['total_money'] += $row['total_money_massage'];
                if(empty($row['total_money_telephone'])==false)
                    $items[$row['reservation_id']]['total_money'] += $row['total_money_telephone'];
                if(empty($row['total_money_sing'])==false)
                    $items[$row['reservation_id']]['total_money'] += $row['total_money_sing'];
                if(empty($row['total_money_service_karaoke'])==false)
                    $items[$row['reservation_id']]['total_money'] += $row['total_money_service_karaoke'];
                if(empty($row['total_money_equipment'])==false)
                    $items[$row['reservation_id']]['total_money'] += $row['total_money_equipment'];
                if(empty($row['total_money_ticket'])==false)
                    $items[$row['reservation_id']]['total_money'] += $row['total_money_ticket'];
                //total_money_vending
                if(empty($row['total_money_vending'])==false)
                    $items[$row['reservation_id']]['total_money'] += $row['total_money_vending'];
                    
                //5. So tien da thanh toan tu ngay xem tro ve truoc 
                if(empty($total_money_payed[$row['id']]['payed_folio'])==false)
                    $items[$row['reservation_id']]['total_money_payed'] += $total_money_payed[$row['id']]['payed_folio'];
                if(empty($total_money_payed[$row['id']]['payed_restaurant'])==false)
                    $items[$row['reservation_id']]['total_money_payed'] += $total_money_payed[$row['id']]['payed_restaurant'];
                if(empty($total_money_payed[$row['id']]['payed_message'])==false)
                    $items[$row['reservation_id']]['total_money_payed'] += $total_money_payed[$row['id']]['payed_message'];
                if(empty($total_money_payed[$row['id']]['payed_karaoke'])==false) 
                    $items[$row['reservation_id']]['total_money_payed'] += $total_money_payed[$row['id']]['payed_karaoke'];
                //payed_ticket
                if(empty($total_money_payed[$row['id']]['payed_ticket'])==false) 
                    $items[$row['reservation_id']]['total_money_payed'] += $total_money_payed[$row['id']]['payed_ticket'];
                //payed_vending
                if(empty($total_money_payed[$row['id']]['payed_vending'])==false) 
                    $items[$row['reservation_id']]['total_money_payed'] += $total_money_payed[$row['id']]['payed_vending'];
                    
                //6. tong so tien da dat coc: phong + nhom + nha hang + karaoke
                if(empty($deposit_money[$row['id']]['deposit_money_room'])==false)//deposit_money_room
                    $items[$row['reservation_id']]['total_money_deposit'] +=$deposit_money[$row['id']]['deposit_money_room'];
                if(empty($deposit_money[$row['id']]['deposit_money_restaurant'])==false)//deposit_money_restaurant
                    $items[$row['reservation_id']]['total_money_deposit'] +=$deposit_money[$row['id']]['deposit_money_restaurant'];
                if(empty($deposit_money[$row['id']]['deposit_money_karaoke'])==false)
                    $items[$row['reservation_id']]['total_money_deposit'] +=$deposit_money[$row['id']]['deposit_money_karaoke'];
                //deposit_money_ticket
                if(empty($deposit_money[$row['id']]['deposit_money_ticket'])==false)
                    $items[$row['reservation_id']]['total_money_deposit'] +=$deposit_money[$row['id']]['deposit_money_ticket'];
                //deposit_money_vending
                if(empty($deposit_money[$row['id']]['deposit_money_vending'])==false)
                    $items[$row['reservation_id']]['total_money_deposit'] +=$deposit_money[$row['id']]['deposit_money_vending'];
            }
            else//neu khong phai cho nhom 
            {
                //tong so tien 
                $items[$row['reservation_id']]['rooms'][$row['room_id']]['total_money_room'] = empty($row['total_money_room'])==false?$row['total_money_room']:0;
                $items[$row['reservation_id']]['rooms'][$row['room_id']]['total_money_room'] += empty($row['total_money_extra'])==false?$row['total_money_extra']:0; 
                $items[$row['reservation_id']]['rooms'][$row['room_id']]['total_money_room'] += empty($row['total_minibar_laundry'])==false?$row['total_minibar_laundry']:0;
                $items[$row['reservation_id']]['rooms'][$row['room_id']]['total_money_room'] += empty($row['total_money_restaurant'])==false?$row['total_money_restaurant']:0;
                $items[$row['reservation_id']]['rooms'][$row['room_id']]['total_money_room'] += empty($row['total_money_massage'])==false?$row['total_money_massage']:0;
                $items[$row['reservation_id']]['rooms'][$row['room_id']]['total_money_room'] += empty($row['total_money_telephone'])==false?$row['total_money_telephone']:0;
                $items[$row['reservation_id']]['rooms'][$row['room_id']]['total_money_room'] += empty($row['total_money_sing'])==false?$row['total_money_sing']:0; 
                $items[$row['reservation_id']]['rooms'][$row['room_id']]['total_money_room'] += empty($row['total_money_service_karaoke'])==false?$row['total_money_service_karaoke']:0;
                $items[$row['reservation_id']]['rooms'][$row['room_id']]['total_money_room'] += empty($row['total_money_equipment'])==false?$row['total_money_equipment']:0;
                $items[$row['reservation_id']]['rooms'][$row['room_id']]['total_money_room'] += empty($row['total_money_ticket'])==false?$row['total_money_ticket']:0;
                //total_money_vending
                $items[$row['reservation_id']]['rooms'][$row['room_id']]['total_money_room'] += empty($row['total_money_vending'])==false?$row['total_money_vending']:0;
                
                //tien da thanh toan
                $items[$row['reservation_id']]['rooms'][$row['room_id']]['total_money_payed_room'] = empty($total_money_payed[$row['id']]['payed_folio'])==false?$total_money_payed[$row['id']]['payed_folio']:0;
                $items[$row['reservation_id']]['rooms'][$row['room_id']]['total_money_payed_room'] += empty($total_money_payed[$row['id']]['payed_restaurant'])==false?$total_money_payed[$row['id']]['payed_restaurant']:0;
                $items[$row['reservation_id']]['rooms'][$row['room_id']]['total_money_payed_room'] += empty($total_money_payed[$row['id']]['payed_message'])==false?$total_money_payed[$row['id']]['payed_message']:0;
                $items[$row['reservation_id']]['rooms'][$row['room_id']]['total_money_payed_room'] += empty($total_money_payed[$row['id']]['payed_karaoke'])==false?$total_money_payed[$row['id']]['payed_karaoke']:0;
                //payed_ticket
                $items[$row['reservation_id']]['rooms'][$row['room_id']]['total_money_payed_room'] += empty($total_money_payed[$row['id']]['payed_ticket'])==false?$total_money_payed[$row['id']]['payed_ticket']:0;
                //payed_vending
                $items[$row['reservation_id']]['rooms'][$row['room_id']]['total_money_payed_room'] += empty($total_money_payed[$row['id']]['payed_vending'])==false?$total_money_payed[$row['id']]['payed_vending']:0;
                
                //tien dat coc 
                $items[$row['reservation_id']]['rooms'][$row['room_id']]['total_money_deposit_room'] =  empty($deposit_money[$row['id']]['deposit_money_room'])==false?$deposit_money[$row['id']]['deposit_money_room']:0;
                $items[$row['reservation_id']]['rooms'][$row['room_id']]['total_money_deposit_room'] +=  empty($deposit_money[$row['id']]['deposit_money_restaurant'])==false?$deposit_money[$row['id']]['deposit_money_restaurant']:0;
                $items[$row['reservation_id']]['rooms'][$row['room_id']]['total_money_deposit_room'] +=  empty($deposit_money[$row['id']]['deposit_money_karaoke'])==false?$deposit_money[$row['id']]['deposit_money_karaoke']:0;
                //deposit_money_ticket
                $items[$row['reservation_id']]['rooms'][$row['room_id']]['total_money_deposit_room'] +=  empty($deposit_money[$row['id']]['deposit_money_ticket'])==false?$deposit_money[$row['id']]['deposit_money_ticket']:0;
                //deposit_money_vending
                $items[$row['reservation_id']]['rooms'][$row['room_id']]['total_money_deposit_room'] +=  empty($deposit_money[$row['id']]['deposit_money_vending'])==false?$deposit_money[$row['id']]['deposit_money_vending']:0;
            }
            
        }
        
        //7. Hieu chinh mang ket qua 
        $i = 1; 
        $total_money =0;
        $total_deposit =0;
        $total_payed =0;
        $total_remain =0;
        
        foreach($items as $key=>$value)
        {
             $rowspan_recode =0;
             
             if($items[$key]['is_deposit_folio_group']==1)
             {
                 if($items[$key]['total_money']<=$items[$key]['total_money_payed'])
                 {
                    unset($items[$key]);
                 }
                 else
                 {
                     //round money 
                     if($items[$key]['total_money']!=0)
                     {
                        $items[$key]['remain'] = $items[$key]['total_money'] - $items[$key]['total_money_deposit'] - $items[$key]['total_money_payed'];
                        
                        foreach($items[$key]['rooms'] as $k=>$room)
                        {
                             
                             $items[$key]['rooms'][$k]['count_traveller'] = count($items[$key]['rooms'][$k]['travellers']);
                             if($items[$key]['rooms'][$k]['count_traveller']==0)
                                $items[$key]['rooms'][$k]['count_traveller']=1;
                             $rowspan_recode += $items[$key]['rooms'][$k]['count_traveller'] - 1;
                             $items[$key]['rooms'][$k]['rowspan_room'] = $items[$key]['rooms'][$k]['count_traveller'] - 1;
                        }
                        
                        $total_money +=$items[$key]['total_money'];
                        $total_deposit +=$items[$key]['total_money_deposit'];
                        $total_payed +=$items[$key]['total_money_payed'];
                        $total_remain +=$items[$key]['remain'];
                        
                        $items[$key]['total_money'] = System::display_number(round($items[$key]['total_money']));
                        if($items[$key]['total_money_payed']!=0)
                            $items[$key]['total_money_payed'] = System::display_number(round($items[$key]['total_money_payed']));
                        else
                            $items[$key]['total_money_payed']='';
                        if($items[$key]['total_money_deposit']!=0)
                            $items[$key]['total_money_deposit'] = System::display_number(round($items[$key]['total_money_deposit']));
                        else
                            $items[$key]['total_money_deposit'] ='';
                        if($items[$key]['remain']!=0)
                        {
                            $items[$key]['index'] = $i++;
                            $items[$key]['count_room'] = count($items[$key]['rooms']);
                            $items[$key]['rowspan_recode'] = $rowspan_recode;
                            $items[$key]['remain'] = System::display_number(round($items[$key]['remain']));
                        }
                        else
                        {
                            unset($items[$key]);
                        }
                     }
                     else
                     {
                        unset($items[$key]);
                     }
                 }
             }
             else
             {
                 $flag = false;
                 foreach($items[$key]['rooms'] as $k=>$room)
                 {
                    if($items[$key]['rooms'][$k]['total_money_room']<=$items[$key]['rooms'][$k]['total_money_payed_room'])
                    {
                        unset($items[$key]['rooms'][$k]);
                    }
                    else
                    {
                        if($items[$key]['rooms'][$k]['total_money_room']!=0)
                        {
                             $items[$key]['rooms'][$k]['remain_room'] = $items[$key]['rooms'][$k]['total_money_room'] - $items[$key]['rooms'][$k]['total_money_deposit_room'] - $items[$key]['rooms'][$k]['total_money_payed_room'];
                             $total_money +=$items[$key]['rooms'][$k]['total_money_room'];
                             $total_deposit +=$items[$key]['rooms'][$k]['total_money_deposit_room'];
                             $total_payed +=$items[$key]['rooms'][$k]['total_money_payed_room'];
                             $total_remain +=$items[$key]['rooms'][$k]['remain_room'];
                             
                             if($items[$key]['rooms'][$k]['total_money_room']!=0)
                                $items[$key]['rooms'][$k]['total_money_room'] = System::display_number(round($items[$key]['rooms'][$k]['total_money_room']));
                             else
                                $items[$key]['rooms'][$k]['total_money_room'] ='';
                             
                             if($items[$key]['rooms'][$k]['total_money_deposit_room']!=0)
                                $items[$key]['rooms'][$k]['total_money_deposit_room'] = System::display_number(round($items[$key]['rooms'][$k]['total_money_deposit_room']));
                             else
                                $items[$key]['rooms'][$k]['total_money_deposit_room'] ='';
                                
                             if($items[$key]['rooms'][$k]['total_money_payed_room']!=0)
                                $items[$key]['rooms'][$k]['total_money_payed_room'] = System::display_number(round($items[$key]['rooms'][$k]['total_money_payed_room']));
                             else
                                $items[$key]['rooms'][$k]['total_money_payed_room'] ='';
                                
                             if($items[$key]['rooms'][$k]['remain_room']!=0)
                                $items[$key]['rooms'][$k]['remain_room'] = System::display_number(round($items[$key]['rooms'][$k]['remain_room']));
                             else
                                $items[$key]['rooms'][$k]['remain_room'] ='';
                                
                             $items[$key]['rooms'][$k]['count_traveller'] = count($items[$key]['rooms'][$k]['travellers']) -1;
                             if($items[$key]['rooms'][$k]['count_traveller']==0)
                                $items[$key]['rooms'][$k]['count_traveller']=1;
                             $rowspan_recode += $items[$key]['rooms'][$k]['count_traveller'];
                             $items[$key]['rooms'][$k]['rowspan_room'] = $items[$key]['rooms'][$k]['count_traveller'];
                             $flag = true; 
                        }
                        else
                        {
                            unset($items[$key]['rooms'][$k]);
                        }
                    }
                 } 
                 if($flag==true)
                 {
                    $items[$key]['index'] = $i++;
                    $items[$key]['count_room'] = count($items[$key]['rooms']);
                    $items[$key]['rowspan_recode'] = $rowspan_recode;
                 }   
             }
        }
        //System::debug($items);
        $this->map['items'] = $items;
        $this->map['total_money'] = System::display_number(round($total_money));
        $this->map['total_deposit'] = System::display_number(round($total_deposit));
        $this->map['total_payed'] = System::display_number(round($total_payed));
        $this->map['total_remain'] = System::display_number(round($total_remain));
    }
    
    //1. Tinh tong so tien chua thanh toan bao gom 
    //Tien phong + minibar laundry + extra services + restaurant + spa + telephone 
    function get_money_total($d,$date,$date_oracle)
    {
        //$date_oracle = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_oracle));
        $sql ='SELECT 
                reservation_room.id,
                reservation_room.reservation_id,
                customer.name as customer_name,
                reservation_room.room_id, 
                --Tien phong chua thanh toan trong ngay dang xem 
                (SELECT  sum(room_status.change_price)
                  FROM room_status
                  WHERE room_status.reservation_room_id=reservation_room.id 
                  AND room_status.in_date<=\''.$date_oracle.'\'
                  AND room_status.reservation_id!=0
                  GROUP BY room_status.reservation_room_id
                  ) as total_money_room,
                  --Tien dich vu mo rong chua thanh toan trong ngay dang xem 
                (SELECT (CASE 
                        WHEN sum(extra_service_invoice.net_price)=count(extra_service_invoice.reservation_room_id)
                            THEN sum(extra_service_invoice_detail.quantity*extra_service_invoice_detail.price)
                            ELSE sum((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) + 
                            ((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01) + 
                            (((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price) + 
                            ((extra_service_invoice_detail.quantity*extra_service_invoice_detail.price)*extra_service_invoice.service_rate*0.01))*extra_service_invoice.tax_rate*0.01))
                          END) 
                  FROM extra_service_invoice,extra_service_invoice_detail
                  WHERE extra_service_invoice.id=extra_service_invoice_detail.invoice_id 
                  AND extra_service_invoice.reservation_room_id=reservation_room.id
                  AND extra_service_invoice_detail.used = 1 AND extra_service_invoice_detail.in_date <=\''.$date_oracle.'\'
                  GROUP BY extra_service_invoice.reservation_room_id)  as total_money_extra,
                  --Tien hoa don minibar va laundry chua thanh toan trong ngay dang xem
                (SELECT sum(housekeeping_invoice.total)
                  FROM housekeeping_invoice
                  WHERE (housekeeping_invoice.type=\'MINIBAR\' OR housekeeping_invoice.type=\'LAUNDRY\')
                  AND housekeeping_invoice.time<'.$d.'
                  AND housekeeping_invoice.reservation_room_id=reservation_room.id
                  GROUP BY housekeeping_invoice.reservation_room_id
                  ) as total_minibar_laundry,
                  --Tien nha hang chuyen ve phong chua thanh toan trong ngay dang xem 
                (SELECT sum(bar_reservation.total)
                  FROM bar_reservation 
                  WHERE bar_reservation.reservation_room_id=reservation_room.id
                  AND bar_reservation.status=\'CHECKOUT\'
                  AND bar_reservation.time_out <'.$d.'
                  ) as total_money_restaurant,
                  --Tien Spa chuyen ve phong chua thanh toan trong ngay dang xem 
                (SELECT sum(massage_reservation_room.amount_pay_with_room)
                  FROM massage_reservation_room
                  WHERE massage_reservation_room.hotel_reservation_room_id=reservation_room.id
                  AND massage_reservation_room.time<='.$d.'
                  GROUP BY massage_reservation_room.hotel_reservation_room_id) as total_money_massage,
                --Tien dien thoai cua phong chua thanh toan trong ngay dang xem 
                (SELECT sum(telephone_report_daily.price)
                  FROM telephone_report_daily,telephone_number
                  WHERE telephone_number.phone_number = telephone_report_daily.phone_number_id
                  AND telephone_number.room_id =room.id
                  AND telephone_report_daily.hdate>=('.$d.'-(TO_DATE(\''.$date.'\', \'DD/MM/YYYY\')-reservation_room.arrival_time + 1)*86400)
                  and telephone_report_daily.hdate <'.$d.') as total_money_telephone,
                --Tong so tien hat karaoke co phong chuyen ve thanh toan phong 
                (SELECT 
                    sum((karaoke_reservation_table.price/3600)*(karaoke_reservation_table.sing_end_time - karaoke_reservation_table.sing_start_time)) 
                FROM karaoke_reservation_table 
                    INNER JOIN karaoke_reservation ON karaoke_reservation.id = karaoke_reservation_table.karaoke_reservation_id                                
                WHERE karaoke_reservation.reservation_room_id=reservation_room.id) as total_money_sing,
                --tong so tien su dung dich vu karaoke chuyen ve phong  
                (SELECT sum(karaoke_reservation.total)
                    FROM karaoke_reservation
                    WHERE karaoke_reservation.reservation_room_id=reservation_room.id
                    AND karaoke_reservation.time_out<'.$d.'
                    AND karaoke_reservation.status=\'CHECKOUT\') as total_money_service_karaoke,
                --tong tien hoa don de bu 
                (SELECT sum(housekeeping_invoice.total)
                    FROM housekeeping_invoice
                    WHERE housekeeping_invoice.reservation_room_id=reservation_room.id
                    AND  housekeeping_invoice.type=\'EQUIP\'
                    AND housekeeping_invoice.minibar_id=reservation_room.room_id
                    AND housekeeping_invoice.time<'.$d.'
                    ) as total_money_equipment,
                --Tong so tien ban ve chuyen ve thanh toan cung phong  
                (SELECT sum(ticket_reservation.total) 
                    FROM ticket_reservation 
                    WHERE ticket_reservation.reservation_room_id=reservation_room.id
                    AND ticket_reservation.time<'.$d.'
                    ) as total_money_ticket, 
                --Tinh tong so tien ban hang co chuyen ve phong 
                (SELECT sum(ve_reservation.total)
                    FROM ve_reservation 
                    WHERE ve_reservation.reservation_room_id=reservation_room.id
                    AND ve_reservation.time<'.$d.'
                    AND (ve_reservation.status=\'CHECKIN\' OR ve_reservation.status=\'CHECKOUT\')
                    ) as total_money_vending  
                FROM reservation_room
                  INNER JOIN room ON reservation_room.room_id=room.id
                  INNER JOIN reservation ON reservation.id=reservation_room.reservation_id
                  LEFT JOIN customer ON reservation.customer_id=customer.id 
                where  
                reservation_room.arrival_time<=\''.$date_oracle.'\'
                AND reservation_room.status=\'CHECKIN\'
                ORDER BY reservation_room.reservation_id desc';
       // echo "<pre>";
        //echo $sql;
        //echo "</pre>";die;
        
        return  DB::fetch_all($sql);
    }
    
    //2. Tinh tong so tien da thanh toan bao gom:
    //Tien folio: phong + minibar laundry + extra service 
    //Tien Bar: restaurant chuyen 1 phan ve phong 
    //Tien Spa : spa chuyen 1 phan ve phong 
    function get_money_payed($d,$date_oracle) 
    {
        $sql='SELECT 
                reservation_room.id,
                reservation_room.reservation_id,
                reservation_room.room_id, 
                --Tien phong da thanh toan folio trong ngay xem tro ve truoc 
                (SELECT sum(payment.amount)
                  FROM folio,payment
                  WHERE payment.type=\'RESERVATION\' AND folio.id=payment.folio_id
                  AND folio.reservation_room_id=reservation_room.id
                  AND payment.time<'.$d.') as payed_folio,
                --Tinh tien nha hang da thanh toan va co 1 phan ve phong trong thoi gian ngay xem tro ve truoc  
                (SELECT sum(payment.amount)
                  FROM bar_reservation,payment
                  WHERE bar_reservation.reservation_room_id=reservation_room.id
                  AND bar_reservation.id=payment.bill_id 
                  AND payment.type=\'BAR\' 
                  AND payment.type_dps is null
                  AND payment.time<'.$d.') as payed_restaurant,
                --Tinh tien spa chuyen ve phong da thanh toan 1 phan trong thoi gian nho hon ngay xem tro ve truoc 
                (SELECT sum(payment.amount)
                    FROM massage_reservation_room,payment
                    WHERE massage_reservation_room.hotel_reservation_room_id=reservation_room.id
                    AND massage_reservation_room.id=payment.bill_id
                    AND payment.type=\'SPA\'
                    AND payment.time<='.$d.') as payed_message, 
                --Tinh tien karaoke da thanh toan 1 phan va chuyen ve phong 
                (SELECT sum(payment.amount)
                    FROM karaoke_reservation,payment
                    WHERE karaoke_reservation.id=payment.bill_id AND payment.type=\'KARAOKE\'
                    AND karaoke_reservation.reservation_room_id=reservation_room.id
                    AND karaoke_reservation.time_out<'.$d.'
                    AND payment.time<'.$d.'
                    AND payment.type_dps is null 
                    AND karaoke_reservation.status=\'CHECKOUT\') as payed_karaoke,
                -- tinh tien ticket da co thanh toan 1 phan va chuyen ve phong 
                (SELECT sum(payment.amount)
                    FROM ticket_reservation,payment
                    WHERE ticket_reservation.reservation_room_id=reservation_room.id
                    AND ticket_reservation.id=payment.bill_id
                    AND payment.type=\'TICKET\'
                    AND payment.type_dps is null 
                    AND payment.time<'.$d.') as payed_ticket, 
                --TInh tien vending da thanh toan 1 phan va chuyen ve phong 
                (SELECT sum(payment.amount)
                    FROM ve_reservation,payment
                    WHERE ve_reservation.reservation_room_id=reservation_room.id
                    AND ve_reservation.id=payment.bill_id
                    AND payment.type=\'VEND\'
                    AND payment.type_dps is null
                    AND payment.time<'.$d.') as payed_vending 
                FROM reservation_room
                  INNER JOIN room ON reservation_room.room_id=room.id
                  INNER JOIN reservation ON reservation.id=reservation_room.reservation_id
                  
                  LEFT JOIN customer ON reservation.customer_id=customer.id 
                where 
                 reservation_room.arrival_time<=\''.$date_oracle.'\'
                 AND reservation_room.status=\'CHECKIN\'  
                ORDER BY reservation_room.reservation_id desc';
        return DB::fetch_all($sql);
    }
    
    //3. Lay ra nhung khoan dat coc cho phong va cho nha hang 
    function get_deposit_money($d,$date_oracle)
    {
        $sql='SELECT 
                reservation_room.id,
                reservation_room.reservation_id,
                reservation_room.room_id, 
                --Tien dat coc cho phong tu ngay xem tro ve  truoc 
                (SELECT sum(payment.amount)
                  FROM payment
                  WHERE payment.type=\'RESERVATION\'
                  AND payment.type_dps=\'ROOM\'
                  AND payment.reservation_room_id=reservation_room.id
                  AND payment.time<'.$d.') as deposit_money_room,
                --tien dat coc nha hang chuyen ve thanh toan cung phong 
                (SELECT sum(payment.amount)
                  FROM bar_reservation,payment
                  WHERE bar_reservation.reservation_room_id=reservation_room.id
                  AND bar_reservation.id=payment.bill_id
                  AND payment.type=\'BAR\'
                  AND payment.type_dps=\'BAR\' 
                  AND payment.time<'.$d.') as deposit_money_restaurant, 
                --Tien dat coc karaoke chuyen ve phong 1 phan  
                (SELECT sum(payment.amount)
                    FROM karaoke_reservation,payment
                    WHERE payment.type_dps=\'KARAOKE\'
                    AND karaoke_reservation.id=payment.bill_id AND payment.type=\'KARAOKE\'
                    AND karaoke_reservation.time_out<'.$d.'
                    AND payment.time<'.$d.'
                    AND karaoke_reservation.reservation_room_id=reservation_room.id
                    AND karaoke_reservation.status=\'CHECKOUT\'
                    ) as deposit_money_karaoke,
                --Tien dat coc cho ticket chuyen ve phong 
                (SELECT sum(payment.amount)
                    FROM ticket_reservation,payment 
                    WHERE ticket_reservation.reservation_room_id=reservation_room.id
                    AND ticket_reservation.id=payment.bill_id
                    AND payment.type=\'TICKET\' 
                    AND payment.type_dps=\'TICKET\'
                    AND payment.time<'.$d.') as deposit_money_ticket, 
                 --Tinh tien dat coc vending 1 phan va co chuyen ve phong 
                 (SELECT sum(payment.amount)
                    FROM ve_reservation,payment
                    WHERE ve_reservation.reservation_room_id=reservation_room.id
                    AND ve_reservation.id=payment.bill_id
                    AND payment.type=\'VEND\'
                    AND payment.type_dps=\'VEND\'
                    AND payment.time<'.$d.') as deposit_money_vending  
                FROM reservation_room
                  INNER JOIN room ON reservation_room.room_id=room.id
                  INNER JOIN reservation ON reservation.id=reservation_room.reservation_id
                  LEFT JOIN customer ON reservation.customer_id=customer.id 
                WHERE 
                    reservation_room.arrival_time<=\''.$date_oracle.'\'
                 AND reservation_room.status=\'CHECKIN\' 
                ORDER BY reservation_room.reservation_id desc';
        return DB::fetch_all($sql);
        /****
        exists(SELECT * 
            FROM reservation_room rr 
            WHERE rr.reservation_id=reservation_room.reservation_id and rr.arrival_time<=\''.$date_oracle.'\')
            AND room_status.in_date=\''.$date_oracle.'\'
        ****/
    }
    
    //4. Lay ra thong tin co ban ve nguon khach, ten khach, ngay den & ngay di cua phong, so dem
    function get_info_reservation($reservation_id)
    {
        $sql ='SELECT room.id || \'_\' || traveller.id as id,
                  room.name,
                  reservation_room.arrival_time,
                  reservation_room.departure_time,
                  reservation_room.departure_time - reservation_room.arrival_time as night,
                  traveller.first_name  || \' \' ||  traveller.last_name as traveller_name,
                  traveller.id as traveller_id,
                  reservation_room.note,
                  reservation_room.note_change_room
                FROM reservation_room 
                INNER JOIN room ON room.id=reservation_room.room_id and reservation_room.reservation_id='.$reservation_id.'
                LEFT JOIN reservation_traveller ON reservation_traveller.reservation_room_id=reservation_room.id
                LEFT OUTER JOIN traveller ON traveller.id=reservation_traveller.traveller_id
                WHERE reservation_room.status=\'CHECKIN\'
                ORDER BY room.name';
        $arr = DB::fetch_all($sql);
        $items = array();
        $r_id = false;
        foreach($arr as $key=>$value)
        {
            $room_id = explode("_",$key);
            if($room_id[0]!=$r_id)
            {
                $items[$room_id[0]] = array();
                $items[$room_id[0]]['arrival_time'] = $value['arrival_time'];
                $items[$room_id[0]]['departure_time'] = $value['departure_time'];
                if(!empty($value['note_change_room']))
                    $items[$room_id[0]]['note_change_room'] = $value['note'];
                $items[$room_id[0]]['id'] = $room_id[0];
                $items[$room_id[0]]['name'] = $value['name']; 
                $items[$room_id[0]]['night'] = $value['night']==0?'dayuse':$value['night'];
                $items[$room_id[0]]['travellers'] = array();
                array_push($items[$room_id[0]]['travellers'],array('id'=>0,'name'=>''));
               
                
                $r_id = $room_id[0];
            }
            if(empty($value['traveller_name'])==false)
                array_push($items[$room_id[0]]['travellers'],array('id'=>$value['traveller_id'],'name'=>$value['traveller_name']));
        }
        return $items;
    }
    
    //5. Voi moi reservation lay ra tong so tien cho thanh toan nhom 
    function get_payment_group_folio($reservation_id,$time)
    {
        $sql ='SELECT sum(payment.amount) as total_amount
                FROM folio,payment
                WHERE folio.reservation_id='.$reservation_id.' 
                AND folio.reservation_room_id is null 
                AND folio.id=payment.folio_id
                AND payment.type=\'RESERVATION\'
                AND payment.type_dps is null
                AND payment.time<'.$time;
        $row = DB::fetch($sql);
        return $row['total_amount'];
    }
    //6. voi moi reservation_id tinh tong so tien dat coc cho nhom phong
    function get_money_deposit($reservation_id, $time)
    {
        $sql ='SELECT sum(payment.amount) as total_amount
                FROM payment
                WHERE payment.reservation_id='.$reservation_id.' 
                AND payment.reservation_room_id is null 
                AND payment.type=\'RESERVATION\'
                AND payment.type_dps=\'GROUP\'
                AND payment.time<'.$time;
        $row = DB::fetch($sql);
        return $row['total_amount'];
    }
}   

?>
