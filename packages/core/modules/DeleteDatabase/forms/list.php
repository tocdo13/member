<?php
class ListDatabaseForm extends Form
{
    function ListDatabaseForm()
    {
        Form::Form('ListDatabaseForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
       
        if(Url::get('selected_ids'))
        {
            $items = Url::get('selected_ids');
			$cond = '1=1';
            if(Url::get('portal_id') != 'ALL')
            {
                $cond.= ' and portal_id =\''.Url::get('portal_id').'\'';
            }
            if(Url::get('from_date') && Url::get('to_date') )
            {
                $from_time = Date('Y',Date_time::to_time(Url::get('from_date')));
                $to_time = Date('Y',Date_time::to_time(Url::get('to_date')));
                foreach($items as $value)
                {
    				if($value ==11)
                    {
                    	DB::query('delete from traveller');
                    	DB::query('delete from reservation_room_service');
                    	DB::query('delete from traveller_comment');
                        DB::query('delete from RE_TEMP');
                    	DB::query('delete from RECEPTION_SHIFT');
                    	DB::query('delete from RES_PRODUCT');
                    	DB::query('delete from RES_PRODUCT_CATEGORY');
                    	DB::query('delete from RES_PRODUCT_PRICE');
                    	DB::query('delete from RES_WH_INVOICE');
                    	DB::query('delete from RES_WH_INVOICE_DETAIL');
                        DB::query('delete from RESERVATION_TYPE');
                        DB::query('delete from EMAIL_LIST');
                    	DB::query('delete from EMAIL_SEND');
                        DB::query('delete from EMAIL_GROUP_EVENT');
                        DB::query('delete from EMAIL_GROUP_EVENT_CUSTOMER');
                        DB::query('delete from TELEPHONE_NUMBER');
                    	DB::query('delete from RESERVATION_NOTE where'.$cond.' and '.$from_time.'<= create_time and time <='.$to_time);
                        DB::query('delete from TELEPHONE_REPORT_DAILY where'.$cond.' and '.$from_time.'<= hdate and hdate <='.$to_time);
                        DB::query('delete from extra_service_invoice_detail where EXISTS
                                   (select extra_service_invoice.id
                                    FROM extra_service_invoice
                                    WHERE extra_service_invoice.id = extra_service_invoice_detail.invoice_id '.$cond.'and '.$from_time.'<= extra_service_invoice.time and extra_service_invoice.time <='.$to_time.')');
                        DB::query('delete from extra_service_invoice where EXISTS
                                   (select reservation_room.id
                                    FROM reservation_room
                                    WHERE reservation_room.id = traveller_folio.reservation_room_id '.$cond.'and '.$from_time.'<= reservation_room.time_in and reservation_room.time_in <='.$to_time.')');
                        DB::query('delete from traveller_folio where EXISTS
                                   (select reservation.id
                                    FROM reservation
                                    WHERE reservation.id = traveller_folio.reservation_id '.$cond.'and '.$from_time.'<= reservation.time and reservation.time <='.$to_time.')');
                        DB::query('delete from folio where EXISTS
                                   (select reservation.id
                                    FROM reservation
                                    WHERE reservation.id = folio.reservation_id '.$cond.'and '.$from_time.'<= reservation.time and reservation.time <='.$to_time.')');
                        DB::query('delete from room_status where EXISTS
                                   (select reservation.id
                                    FROM reservation
                                    WHERE reservation.id = room_status.reservation_id '.$cond.'and '.$from_time.'<= reservation.time and reservation.time <='.$to_time.')');
                        DB::query('delete from reservation_traveller where EXISTS
                                   (select reservation.id
                                    FROM reservation
                                    WHERE reservation.id = reservation_traveller.reservation_id '.$cond.'and '.$from_time.'<= reservation.time and reservation.time <='.$to_time.')');
                        DB::query('delete from reservation_room where EXISTS
                                   (select reservation.id
                                    FROM reservation
                                    WHERE reservation.id = reservation_room.reservation_id '.$cond.'and '.$from_time.'<= reservation.time and reservation.time <='.$to_time.')');
                        DB::query('delete from reservation where'.$cond.' and '.$from_time.'<= time and time <='.$to_time);
                    }
                    else if($value ==12)
                    {
                    	DB::query('delete from bar_reservation_cancel');
                        DB::query('delete from BAR');
                    	DB::query('delete from BAR_CATEGORY');
                    	DB::query('delete from BAR_CHARGE');
                    	DB::query('delete from BAR_NOTE');
                        DB::query('delete from BAR_RESERVATION_SPLIT');
                    	DB::query('delete from BAR_SHIFT');
                    	DB::query('delete from BAR_TABLE');
                    	DB::query('delete from BAR_TABLE_MERGE');
                        DB::query('delete from bar_reservation_table where EXISTS
                                   (select bar_reservation.id
                                    FROM bar_reservation
                                    WHERE bar_reservation.id = bar_reservation_table.bar_reservation_id '.$cond.'and '.$from_time.'<= bar_reservation.time_in and bar_reservation.time_in <='.$to_time.')');
                        DB::query('delete from bar_reservation_product where EXISTS
                                   (select bar_reservation.id
                                    FROM bar_reservation
                                    WHERE bar_reservation.id = bar_reservation_product.bar_reservation_id '.$cond.'and '.$from_time.'<= bar_reservation.time_in and bar_reservation.time_in <='.$to_time.')');
                        DB::query('delete from bar_reservation where'.$cond.' and '.$from_time.'<= time_in and time_in <='.$to_time);
                    }
                    else if($value ==13)
                    {
                        DB::query('delete from HK_WH_INVOICE');
                    	DB::query('delete from HK_WH_INVOICE_DETAIL');
                        DB::query('delete from HOUSEKEEPING_EQUIPMENT');
                    	DB::query('delete from HOUSEKEEPING_EQUIPMENT_DAMAGED');
                    	DB::query('delete from HK_PRODUCT');
                    	DB::query('delete from HK_PRODUCT_CATEGORY');
                        DB::query('delete from HK_PRODUCT_STATUS');
                    	DB::query('delete from HK_TEMP');
                        DB::query('delete from MINIBAR');
                    	DB::query('delete from MINIBAR_PRODUCT');
                        DB::query('delete from HOUSEKEEPING_INVOICE_DETAIL where EXISTS
                                   (select HOUSEKEEPING_INVOICE.id
                                    FROM HOUSEKEEPING_INVOICE
                                    WHERE HOUSEKEEPING_INVOICE.id = HOUSEKEEPING_INVOICE_DETAIL.invoice_id '.$cond.'and '.$from_time.'<= HOUSEKEEPING_INVOICE.time and HOUSEKEEPING_INVOICE.time <='.$to_time.')');
                        DB::query('delete from HOUSEKEEPING_INVOICE where'.$cond.' and '.$from_time.'<= time and time <='.$to_time);
                    }
                    else if($value ==14)
                    {
                        DB::query('delete from WH_INVOICE');
                        DB::query('delete from WH_INVOICE_DETAIL');
                        DB::query('delete from WAREHOUSE');
                    	DB::query('delete from WH_PRODUCT');
                    	DB::query('delete from WH_PRODUCT_CATEGORY');
                    	DB::query('delete from WH_PRODUCT_PRICE');
                    	DB::query('delete from WH_RECEIVER');
                    	DB::query('delete from WH_START_TERM_DEBIT');
                        DB::query('delete from WH_START_TERM_REMAIN');
                    	DB::query('delete from WH_TEMP');
                    	DB::query('delete from WH_TMP');
                    }
                    else if($value ==15)
                    {
                        DB::query('delete from VAT_BILL');
                        DB::query('delete from VAT_INVOICE');
                    }
                    else if($value ==16)
                    {
                        DB::query('delete from NIGHT_AUDIT');
                    }
                    else if($value ==17)
                    {
                        DB::query('delete from PARTY_PROMOTIONS');
                    	DB::query('delete from PARTY_RESERVATION');
                    	DB::query('delete from PARTY_RESERVATION_DETAIL');
                    	DB::query('delete from PARTY_RESERVATION_ROOM');
                    	DB::query('delete from PARTY_ROOM');
                    	DB::query('delete from PARTY_TYPE');
                    }
                    else if($value ==18)
                    {
                        DB::query('delete from TICKET');
                    	DB::query('delete from TICKET_AREA');
                    	DB::query('delete from TICKET_AREA_TYPE');
                    	DB::query('delete from TICKET_CANCELATION');
                    	DB::query('delete from TICKET_GROUP');
                    	DB::query('delete from TICKET_INVOICE');
                        DB::query('delete from TICKET_INVOICE_DETAIL');
                        DB::query('delete from TICKET_RESERVATION');
                        DB::query('delete from TICKET_SERVICE');
                        DB::query('delete from TICKET_SERVICE_GRANT');
                    }
                    else if($value ==19)
                    {
                        DB::query('delete from VENDING_START_TERM_DEBIT');
                    	DB::query('delete from VENDING_CUSTOMER_GROUP');
                    	DB::query('delete from VENDING_CUSTOMER_CONTACT');
                    	DB::query('delete from VENDING_CUSTOMER');
                    	DB::query('delete from VE_RESERVATION_PRODUCT');
                    	DB::query('delete from VE_RESERVATION');
                        DB::query('delete from VE_PRODUCT_CATEGORY_DISCOUNT');
                    }
                    else if($value ==20)
                    {
                        DB::query('delete from KA_PRODUCT');
                    	DB::query('delete from KA_PRODUCT_CATEGORY');
                    	DB::query('delete from KA_RESERVATION');
                    	DB::query('delete from KA_RESERVATION_CANCEL');
                    	DB::query('delete from KA_RESERVATION_PRODUCT');
                    	DB::query('delete from KA_RESERVATION_ROOM');
                        DB::query('delete from KA_ROOM');
                        DB::query('delete from KA_TEMP');
                    	DB::query('delete from KA_WH_INVOICE');
                    	DB::query('delete from KA_WH_INVOICE_DETAIL');
                    	DB::query('delete from KARAOKE');
                    	DB::query('delete from KARAOKE_CHARGE');
                    	DB::query('delete from KARAOKE_NOTE');
                        DB::query('delete from KARAOKE_RESERVATION');
                        DB::query('delete from KARAOKE_RESERVATION_PRODUCT');
                    	DB::query('delete from KARAOKE_RESERVATION_TABLE');
                    	DB::query('delete from KARAOKE_SHIFT');
                        DB::query('delete from KARAOKE_TABLE');
                    }
                    else if($value ==21)
                    {
                        DB::query('delete from MASSAGE_GUEST');
                    	DB::query('delete from MASSAGE_PRODUCT');
                    	DB::query('delete from MASSAGE_PRODUCT_CONSUMED');
                    	DB::query('delete from MASSAGE_RESERVATION');
                    	DB::query('delete from MASSAGE_RESERVATION_ROOM');
                    	DB::query('delete from MASSAGE_ROOM');
                        DB::query('delete from MASSAGE_ROOM_STATUS');
                        DB::query('delete from MASSAGE_STAFF');
                    	DB::query('delete from MASSAGE_STAFF_ROOM');
                    }
                    else if($value ==22)
                    {
                        DB::query('delete from REVEN_EXPEN');
                    	DB::query('delete from REVEN_EXPEN_BALANCE');
                    	DB::query('delete from REVEN_EXPEN_GROUP');
                    	DB::query('delete from REVEN_EXPEN_ITEMS');
                    }
                    else if($value ==23)
                    {
                        DB::query('delete from CUSTOMER');
                    	DB::query('delete from CUSTOMER_CARE');
                    	DB::query('delete from CUSTOMER_CONTACT');
                    	DB::query('delete from CUSTOMER_DEBT_SETTLEMENT');
                        DB::query('delete from CUSTOMER_GROUP');
                    	DB::query('delete from CUSTOMER_RATE_COMMISSION');
                    	DB::query('delete from CUSTOMER_RATE_POLICY');
                        DB::query('delete from SECTORS');
                    	DB::query('delete from BANK');
                    }
                    else if($value ==24)
                    {
                        DB::query('delete from PRODUCT');
                    	DB::query('delete from PRODUCT_CATEGORY');
                    	DB::query('delete from PRODUCT_CATEGORY_DISCOUNT');
                    	DB::query('delete from PRODUCT_IMPORT');
                        DB::query('delete from PRODUCT_LIMIT');
                    	DB::query('delete from PRODUCT_MATERIAL');
                    	DB::query('delete from PRODUCT_PRICE_LIST');
                        DB::query('delete from PRODUCT_STATUS');
                    }
                    else if($value ==25)
                    {
                        DB::query('delete from FORGOT_OBJECT');
                    	DB::query('delete from LOG');
                    	DB::query('delete from PAYMENT');
                    } 
    			}
            }
			
            echo '<script> alert("Neway: Deleted all successfull...!") </script>';
		}
    }
    function draw()
    {
        $this->map=array();
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
        
        $items =array
        (
            '1'=> array('id'=>11,'code'=>'LeTan','name'=>Portal::language('delete_database_reception')),
            '2'=>array('id'=>12,'code'=>'NhaHang','name'=>Portal::language('delete_database_restaurant')),
            '3'=>array('id'=>13,'code'=>'Buong','name'=>Portal::language('delete_database_house_keeping')),
            '4'=>array('id'=>14,'code'=>'Kho','name'=>Portal::language('delete_database_warehouse')),
            '5'=>array('id'=>15,'code'=>'ThuNgan','name'=>Portal::language('delete_database_cashier')),
            '6'=>array('id'=>16,'code'=>'KiemToanDem','name'=>Portal::language('delete_database_night_audit')),
            '7'=>array('id'=>17,'code'=>'DatTiec','name'=>Portal::language('delete_database_banquet')),
            '8'=>array('id'=>18,'code'=>'BanVe','name'=>Portal::language('delete_database_ticket')),
            '9'=>array('id'=>19,'code'=>'BanHang','name'=>Portal::language('delete_database_vending')),
            '10'=>array('id'=>20,'code'=>'Karaoke','name'=>Portal::language('delete_database_karaoke')),
            '11'=>array('id'=>21,'code'=>'Spa','name'=>Portal::language('delete_database_spa')),
            '12'=>array('id'=>22,'code'=>'ThuChi','name'=>Portal::language('delete_database_budget')),//thu chi
            '13'=>array('id'=>23,'code'=>'KhachHang','name'=>Portal::language('delete_database_customer')),
            '14'=>array('id'=>24,'code'=>'SanPham','name'=>Portal::language('delete_database_product')),
            '15'=>array('id'=>25,'code'=>'Khac','name'=>Portal::language('delete_database_other'))
        );
        $i=1;
        foreach($items as $key=>$value)
        {
            $items[$key]['i']=$i++;
        }
        $this->parse_layout('list',$this->map +
			array('items'=>$items));
    }
} 
?>