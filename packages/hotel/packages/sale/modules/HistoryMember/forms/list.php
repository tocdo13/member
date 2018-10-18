<?php
class HistoryMemberForm extends Form
{
	function HistoryMemberForm()
	{
		Form::Form('HistoryMemberForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
	}	
	function draw()
	{
	   require_once 'packages/core/includes/utils/vn_code.php';
	   $this->map = array();
       $arr_page = array();
	   $cond="(1=1)";
       $this->map['log'] = '';
       $this->map['from_time'] = Url::get('from_time')?Url::get('from_time'):'00:00';
       $_REQUEST['from_time'] = $this->map['from_time'];
       $this->map['to_time'] = Url::get('to_time')?Url::get('to_time'):'00:00';
       $_REQUEST['to_time'] = $this->map['to_time'];
       $array_time = explode(":",$this->map['from_time']); $from_time = $array_time[0]*3600 + $array_time[1]*60;
       $array_time = explode(":",$this->map['to_time']); $to_time = $array_time[0]*3600 + $array_time[1]*60;
       /** nếu search theo mã thành viên **/
	   if(Url::get('member_code') AND Url::get('member_code')!=''){
	       $this->map['member_code'] = Url::get('member_code');
           $_REQUEST['member_code'] = Url::get('member_code');
           $arr_page['member_code'] = Url::get('member_code');
	       $cond .= " AND (history_member.member_code=".Url::get('member_code').")";
           
	   }
       /** nếu search theo tên khách **/
        if(Url::get('member_name') AND Url::get('member_name')!=''){
            $this->map['member_name'] = Url::get('member_name');
            $_REQUEST['member_name'] = Url::get('member_name');
            $arr_page['member_name'] = Url::get('member_name');
            $cond .= ' AND ((LOWER(FN_CONVERT_TO_VN(concat(concat(traveller.first_name,\' \'),traveller.last_name))) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(Url::get('member_name'),'utf-8')).'%\'))';
        }
       /** Nếu search từ ngày đến ngày **/
       if(Url::get('from_date') AND Url::get('from_date')!=''){
            $this->map['from_date'] = Url::get('from_date');
            $_REQUEST['from_date'] = Url::get('from_date');
            $arr_page['from_date'] = Url::get('from_date');
            if(Url::get('to_date') AND Url::get('to_date') != ''){
                $this->map['to_date'] = Url::get('to_date');
                $_REQUEST['to_date'] = Url::get('to_date');
                $arr_page['to_date'] = Url::get('to_date');
                $cond .= " AND (history_member.create_time>=".(Date_Time::to_time($this->map['from_date']) + $from_time)." AND history_member.create_time<=".(Date_Time::to_time($this->map['to_date']) + $to_time).")";
            }else{
                $cond .= " AND (history_member.create_time>=".(Date_Time::to_time($this->map['from_date']) + $from_time).")";
            }
       }else{
            if(Url::get('to_date') AND Url::get('to_date') != ''){
                $this->map['to_date'] = Url::get('to_date');
                $_REQUEST['to_date'] = Url::get('to_date');
                $arr_page['to_date'] = Url::get('to_date');
                $cond .= " AND (history_member.create_time<=".(Date_Time::to_time($this->map['to_date']) + $to_time).")";
            }
       }
       if(Url::get('invoice_code') AND Url::get('invoice_code')!=''){
            $this->map['invoice_code'] = Url::get('invoice_code');
            $_REQUEST['invoice_code'] = Url::get('invoice_code');
            $arr_page['invoice_code'] = Url::get('invoice_code');
            $cond .= " AND ((history_member.type!='RESERVATION' AND history_member.bill_id=".Url::get('invoice_code').") OR (history_member.type='RESERVATION' AND history_member.folio_id=".Url::get('invoice_code')."))";
            
       }
       $ORCL = '
			SELECT 
				count(*) as id
			FROM history_member
                left join reservation on reservation.id = history_member.bill_id
                left join traveller on traveller.id = history_member.traveller_id
                left join bar_reservation on bar_reservation.id = history_member.bill_id
                left join bar_reservation_table on bar_reservation_table.bar_reservation_id = bar_reservation.id
                left join massage_staff_room on massage_staff_room.reservation_room_id = history_member.bill_id
                left join ve_reservation on ve_reservation.id = history_member.bill_id
                left join karaoke_reservation on karaoke_reservation.id = history_member.bill_id
                left join karaoke_reservation_table on karaoke_reservation_table.karaoke_reservation_id=karaoke_reservation.id
			WHERE '.$cond.'
		';
		$count = DB::fetch($ORCL);
        $item_per_page = 10;
        if(Url::get('page_no')){
            $page_no = Url::get('page_no');
        }else{
            $page_no = 1;
        }
        $page_name = 'history_member';
		require_once 'packages/user/modules/log/paging_new.php';
		$paging = paging_new($count['id'],$item_per_page,$page_name,$arr_page,$page_no);
       /** lay thong tin **/
       $sql = "SELECT * FROM 
                (
                SELECT 
                    history_member.*,
                    concat(concat(traveller.first_name,' '),traveller.last_name) as full_name,
                    reservation.customer_id,
                    bar_reservation.bar_id,
                    bar_reservation_table.table_id as table_bar_id,
                    massage_staff_room.room_id as room_massage_id,
                    ve_reservation.department_id,
                    ve_reservation.department_code,
                    karaoke_reservation.karaoke_id,
                    karaoke_reservation_table.table_id as table_karaoke_id,
                    row_number() over (order by history_member.create_time DESC) as rownumber
                FROM 
                    history_member
                    left join reservation on reservation.id = history_member.bill_id
                    left join traveller on traveller.id = history_member.traveller_id
                    left join bar_reservation on bar_reservation.id = history_member.bill_id
                    left join bar_reservation_table on bar_reservation_table.bar_reservation_id = bar_reservation.id
                    left join massage_staff_room on massage_staff_room.reservation_room_id = history_member.bill_id
                    left join ve_reservation on ve_reservation.id = history_member.bill_id
                    left join karaoke_reservation on karaoke_reservation.id = history_member.bill_id
                    left join karaoke_reservation_table on karaoke_reservation_table.karaoke_reservation_id=karaoke_reservation.id
                WHERE 
                    ".$cond." 
                ORDER BY 
                    history_member.create_time DESC,history_member.id DESC    
                )
                WHERE
                rownumber > ".(($page_no-1)*$item_per_page)." and rownumber<=".(($page_no)*$item_per_page)."
                    ";
       $history = DB::fetch_all($sql);
       //System::debug($history);
        $folio_id = "";
        $type = "";
        $bill_id = "";
       foreach($history as $key=>$value){
            /** đặt link **/
            $history[$key]['link_recode'] = '';
            $history[$key]['link_invoice'] = '';
            if($value['type']=='RESERVATION'){
                $history[$key]['link_recode'] = '?page=reservation&cmd=edit&id='.$value['bill_id'];
                if(isset($value['trveller_id']) AND $value['trveller_id']!=''){
                    $history[$key]['link_invoice'] = '?page=view_traveller_folio&traveller_id='.$value['trveller_id'].'&folio_id='.$value['folio_id'];
                }elseif(isset($value['customer_id']) AND $value['customer_id']!=''){
                    $history[$key]['link_invoice'] = '?page=view_traveller_folio&cmd=group_invoice&customer_id='.$value['customer_id'].'&id='.$value['bill_id'].'&folio_id='.$value['folio_id'];
                }
            }elseif($value['type']=='BAR'){
                $history[$key]['link_recode'] = '?page=touch_bar_restaurant&cmd=edit&id='.$value['bill_id'].'&table_id='.$value['table_bar_id'].'&bar_id='.$value['bar_id'];
                $history[$key]['link_invoice'] = '?page=touch_bar_restaurant&cmd=detail&316c9c3ed45a83ee318b1f859d9b8b79=d001cd500100d09a0a074a7a7ea55702&5ebeb6065f64f2346dbb00ab789cf001=1&id='.$value['bill_id'].'&bar_id='.$value['bar_id'];
            }elseif($value['type']=='TICKET'){
                $history[$key]['link_recode'] = '?page=ticket_invoice_group&cmd=edit&id='.$value['bill_id'];
                $history[$key]['link_invoice'] = '?page=ticket_invoice_group&cmd=bill&id='.$value['bill_id'];
            }elseif($value['type']=='SPA'){
                $history[$key]['link_recode'] = '?page=massage_daily_summary&cmd=edit&room_id='.$value['room_massage_id'].'&id='.$value['bill_id'];
                $history[$key]['link_invoice'] = '?page=massage_daily_summary&cmd=invoice&room_id='.$value['room_massage_id'].'&id='.$value['bill_id'];
            }elseif($value['type']=='VEND'){
                $history[$key]['link_recode'] = '?page=automatic_vend&cmd=edit&id='.$value['bill_id'].'&department_id='.$value['department_id'].'&department_code='.$value['department_code'];
                $history[$key]['link_invoice'] = '?page=automatic_vend&cmd=detail&316c9c3ed45a83ee318b1f859d9b8b79=f7531e2d0ea27233ce00b5f01c5bf335&5ebeb6065f64f2346dbb00ab789cf001=1&id='.$value['bill_id'];
            }elseif($value['type']=='KARAOKE'){
                $history[$key]['link_recode'] = '?page=karaoke_touch&cmd=edit&id='.$value['bill_id'].'&table_id='.$value['table_karaoke_id'].'&karaoke_id='.$value['karaoke_id'];
                $history[$key]['link_invoice'] = '?page=karaoke_touch&cmd=detail&316c9c3ed45a83ee318b1f859d9b8b79=f7531e2d0ea27233ce00b5f01c5bf335&5ebeb6065f64f2346dbb00ab789cf001=1&id='.$value['bill_id'].'&karaoke_id='.$value['karaoke_id'];
            }
            /** end đặt link **/
            $orcl = "SELECT 
                        history_member_detail.*
                        
                    FROM 
                        history_member_detail
                    WHERE 
                        history_member_detail.history_member_id = ".$value['id']."";
            $detail = DB::fetch_all($orcl);
            foreach($detail as $id=>$content){
                if($content['price']==0){
                    $detail[$id]['detail'] = "Không thực hiện tích điểm";
                    $detail[$id]['price'] = $content['payment_type_id'];
                    $detail[$id]['change_price'] = $content['payment_type_id'];
                    $detail[$id]['point'] = $content['payment_type_id'];
                }else{
                    if($content['payment_type_point']=='ADD'){
                       $detail[$id]['detail'] = "Cộng "; 
                    }else{
                        $detail[$id]['detail'] = "Trừ "; 
                    }
                    if($content['price']!=0 AND $content['change_price']!=0)
                    {
                        $detail[$id]['point'] = $content['price']/$content['change_price'];
                        if(($content['price']%$content['change_price'])!=0)
                        {
                            $arr_point = explode('.',$detail[$id]['point']);
                            if($content['payment_type_id']=='FOC'){
                                $detail[$id]['point'] = $arr_point[0]+1;
                            }else{
                                $detail[$id]['point'] = $arr_point[0];
                            }
                        }
                    }
                    else
                    $detail[$id]['point'] = 0;
                    $arr_type_point = explode(",",$content['type_point']);
                    if($arr_type_point[0]=='POINT_USER'){
                        $detail[$id]['detail'] .= $detail[$id]['point']." Điểm sử dụng ";
                        if($arr_type_point[0] AND $arr_type_point[0]!=''){
                            $detail[$id]['detail'] .= "Và ".$detail[$id]['point']." Điểm tích lũy";
                        }
                    }else{
                        $detail[$id]['detail'] .= $detail[$id]['point']."Điểm tích lũy ";
                        if($arr_type_point[0] AND $arr_type_point[0]!=''){
                            $detail[$id]['detail'] .= "Và ".$detail[$id]['point']." Điểm sử dụng";
                        }
                    }
                }
                $detail[$id]['payment_type_id'] = $this->convert_payment_type($content['payment_type_id']);
            }
            $history[$key]['child'] = $detail;
            $history[$key]['create_time'] = date('d/m/Y',$value['create_time'])." ".date('H:i',$value['create_time']);
            
       }
       //System::debug($history);
	  $this->parse_layout('list',array('list_items'=>$history,'paging'=>$paging)+$this->map); 
	}
    function convert_payment_type($name){
        if($name=='REFUND'){
            $name = "Trả lại";
        }elseif($name=='CASH'){
            $name = "Tiền mặt";
        }elseif($name=='CREDIT_CARD'){
            $name = "Thẻ";
        }elseif($name=='DEBIT'){
            $name = "Nợ";
        }elseif($name=='FOC'){
            $name = "Miễn phí";
        }elseif($name=='BANK'){
            $name = "Chuyển khoản";
        }else{
            $name = "";
        }
        
        return $name;
    }
}
?>