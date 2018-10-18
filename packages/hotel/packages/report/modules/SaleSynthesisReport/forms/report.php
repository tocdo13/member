﻿<?php
class SaleSynthesisReportForm extends Form
{
	function SaleSynthesisReportForm()
	{
		Form::Form('SaleSynthesisReportForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    	$this->link_js('packages/core/includes/js/jquery/datepicker.js');     
	}
    
    function get_type($type,$service_code)
	{
        if($type != 'EXTRA_SERVICE')
            return $type;
        if($service_code == 'LATE_CHECKOUT')
            return 'LATE_CHECKOUT';
        else if($service_code == 'EARLY_CHECKIN')
            return 'EARLY_CHECKIN';
        else if($service_code == 'EXTRA_BED')
            return 'EXTRA_BED';
        else 
        {
            if(strpos('a'.strtolower($service_code),'tour')>0)
                return 'TOUR';
            else if(strpos('a'.strtolower($service_code),'airport')>0)
                return 'DUADON';
            else 
                return 'OTHER';
        }
    }
    
    function cal_reven($type,$traveller_folio)
	{
        $date = Date_Time::to_time($_REQUEST['date']);
        $sub = $traveller_folio['amount'];
        $src = $sub*$traveller_folio['service_rate']/100;
        $tax = ($sub+$src)*$traveller_folio['tax_rate']/100;
        $total = $sub + $src + $tax;
        $breakfast =0;
        switch($type)
        {
            case 'ROOM':
            {
                if(BREAKFAST_SPLIT_PRICE == 1)
                {
                    if($traveller_folio['breakfast'] == 1)
                    {
                        $breakfast = (BREAKFAST_PRICE * $traveller_folio['adult'] + BREAKFAST_CHILD_PRICE * $traveller_folio['child']);
                    }
                    else
                    {
                        $breakfast = 0;
                    }
                    if(BREAKFAST_NET_PRICE == 0)
                    {
                        $sub = $sub - $breakfast;
                        $src = $sub*$traveller_folio['service_rate']/100;
                        $tax = ($sub+$src)*$traveller_folio['tax_rate']/100;
                        $total = $sub + $src + $tax;
                    }
                    else
                    {
                        $total = $total - $breakfast;
                        $sub = $total/((1 + $traveller_folio['tax_rate']/100)*(1 + $traveller_folio['service_rate']/100));
                        $src = $sub*$traveller_folio['service_rate']/100;
                        $tax = ($sub+$src)*$traveller_folio['tax_rate']/100;  
                    }
                }
                if($traveller_folio['create_time'] >= $date)
                {
                    $this->map['reven_room_room']['today_sub'] += $sub;
                    $this->map['reven_room_room']['today_src'] += $src;
                    $this->map['reven_room_room']['today_tax'] += $tax;
                    $this->map['reven_room_room']['today_total'] += $total;
                    
                    $this->map['reven_breakfast']['today_sub'] += $breakfast;
                    $this->map['reven_breakfast']['today_src'] += 0;
                    $this->map['reven_breakfast']['today_tax'] += 0;
                    $this->map['reven_breakfast']['today_total'] += $breakfast;
                }
                if($traveller_folio['create_time'] >= Date_Time::to_time(date('1/m/y',$date)))
                {
                    $this->map['reven_room_room']['month_sub'] += $sub;
                    $this->map['reven_room_room']['month_src'] += $src;
                    $this->map['reven_room_room']['month_tax'] += $tax;
                    $this->map['reven_room_room']['month_total'] += $total;
                    
                    $this->map['reven_breakfast']['month_sub'] += $breakfast;
                    $this->map['reven_breakfast']['month_src'] += 0;
                    $this->map['reven_breakfast']['month_tax'] += 0;
                    $this->map['reven_breakfast']['month_total'] += $breakfast;
                }
                $this->map['reven_room_room']['year_sub'] += $sub;
                $this->map['reven_room_room']['year_src'] += $src;
                $this->map['reven_room_room']['year_tax'] += $tax;
                $this->map['reven_room_room']['year_total'] += $total;
                
                $this->map['reven_breakfast']['year_sub'] += $breakfast;
                $this->map['reven_breakfast']['year_src'] += 0;
                $this->map['reven_breakfast']['year_tax'] += 0;
                $this->map['reven_breakfast']['year_total'] += $breakfast;
                break;
            }
            case 'MINIBAR':
            {
                if($traveller_folio['create_time'] >= $date)
                {
                    $this->map['reven_room_minibar']['today_sub'] += $sub;
                    $this->map['reven_room_minibar']['today_src'] += $src;
                    $this->map['reven_room_minibar']['today_tax'] += $tax;
                    $this->map['reven_room_minibar']['today_total'] += $total;
                }
                if($traveller_folio['create_time'] >= Date_Time::to_time(date('1/m/y',$date)))
                {
                    $this->map['reven_room_minibar']['month_sub'] += $sub;
                    $this->map['reven_room_minibar']['month_src'] += $src;
                    $this->map['reven_room_minibar']['month_tax'] += $tax;
                    $this->map['reven_room_minibar']['month_total'] += $total;
                }
                $this->map['reven_room_minibar']['year_sub'] += $sub;
                $this->map['reven_room_minibar']['year_src'] += $src;
                $this->map['reven_room_minibar']['year_tax'] += $tax;
                $this->map['reven_room_minibar']['year_total'] += $total;
                break;
            }
            case 'LAUNDRY':
            {
                if($traveller_folio['create_time'] >= $date)
                {
                    $this->map['reven_room_laundry']['today_sub'] += $sub;
                    $this->map['reven_room_laundry']['today_src'] += $src;
                    $this->map['reven_room_laundry']['today_tax'] += $tax;
                    $this->map['reven_room_laundry']['today_total'] += $total;
                }
                if($traveller_folio['create_time'] >= Date_Time::to_time(date('1/m/y',$date)))
                {
                    $this->map['reven_room_laundry']['month_sub'] += $sub;
                    $this->map['reven_room_laundry']['month_src'] += $src;
                    $this->map['reven_room_laundry']['month_tax'] += $tax;
                    $this->map['reven_room_laundry']['month_total'] += $total;
                }
                $this->map['reven_room_laundry']['year_sub'] += $sub;
                $this->map['reven_room_laundry']['year_src'] += $src;
                $this->map['reven_room_laundry']['year_tax'] += $tax;
                $this->map['reven_room_laundry']['year_total'] += $total;
                break;
            }
            case 'EQUIPMENT':
            {
                if($traveller_folio['create_time'] >= $date)
                {
                    $this->map['reven_room_equip']['today_sub'] += $sub;
                    $this->map['reven_room_equip']['today_src'] += $src;
                    $this->map['reven_room_equip']['today_tax'] += $tax;
                    $this->map['reven_room_equip']['today_total'] += $total;
                }
                if($traveller_folio['create_time'] >= Date_Time::to_time(date('1/m/y',$date)))
                {
                    $this->map['reven_room_equip']['month_sub'] += $sub;
                    $this->map['reven_room_equip']['month_src'] += $src;
                    $this->map['reven_room_equip']['month_tax'] += $tax;
                    $this->map['reven_room_equip']['month_total'] += $total;
                }
                $this->map['reven_room_equip']['year_sub'] += $sub;
                $this->map['reven_room_equip']['year_src'] += $src;
                $this->map['reven_room_equip']['year_tax'] += $tax;
                $this->map['reven_room_equip']['year_total'] += $total;
                break;
            }
            case 'TELEPHONE':
            {
                if($traveller_folio['create_time'] >= $date)
                {
                    $this->map['reven_room_telephone']['today_sub'] += $sub;
                    $this->map['reven_room_telephone']['today_src'] += $src;
                    $this->map['reven_room_telephone']['today_tax'] += $tax;
                    $this->map['reven_room_telephone']['today_total'] += $total;
                }
                if($traveller_folio['create_time'] >= Date_Time::to_time(date('1/m/y',$date)))
                {
                    $this->map['reven_room_telephone']['month_sub'] += $sub;
                    $this->map['reven_room_telephone']['month_src'] += $src;
                    $this->map['reven_room_telephone']['month_tax'] += $tax;
                    $this->map['reven_room_telephone']['month_total'] += $total;
                }
                $this->map['reven_room_telephone']['year_sub'] += $sub;
                $this->map['reven_room_telephone']['year_src'] += $src;
                $this->map['reven_room_telephone']['year_tax'] += $tax;
                $this->map['reven_room_telephone']['year_total'] += $total;
                break;
            }
            case 'LATE_CHECKOUT':
            {
                if($traveller_folio['create_time'] >= $date)
                {
                    $this->map['reven_room_lo']['today_sub'] += $sub;
                    $this->map['reven_room_lo']['today_src'] += $src;
                    $this->map['reven_room_lo']['today_tax'] += $tax;
                    $this->map['reven_room_lo']['today_total'] += $total;
                }
                if($traveller_folio['create_time'] >= Date_Time::to_time(date('1/m/y',$date)))
                {
                    $this->map['reven_room_lo']['month_sub'] += $sub;
                    $this->map['reven_room_lo']['month_src'] += $src;
                    $this->map['reven_room_lo']['month_tax'] += $tax;
                    $this->map['reven_room_lo']['month_total'] += $total;
                }
                $this->map['reven_room_lo']['year_sub'] += $sub;
                $this->map['reven_room_lo']['year_src'] += $src;
                $this->map['reven_room_lo']['year_tax'] += $tax;
                $this->map['reven_room_lo']['year_total'] += $total;
                break;
            }
            case 'EARLY_CHECKIN':
            {
                if($traveller_folio['create_time'] >= $date)
                {
                    $this->map['reven_room_ei']['today_sub'] += $sub;
                    $this->map['reven_room_ei']['today_src'] += $src;
                    $this->map['reven_room_ei']['today_tax'] += $tax;
                    $this->map['reven_room_ei']['today_total'] += $total;
                }
                if($traveller_folio['create_time'] >= Date_Time::to_time(date('1/m/y',$date)))
                {
                    $this->map['reven_room_ei']['month_sub'] += $sub;
                    $this->map['reven_room_ei']['month_src'] += $src;
                    $this->map['reven_room_ei']['month_tax'] += $tax;
                    $this->map['reven_room_ei']['month_total'] += $total;
                }
                $this->map['reven_room_ei']['year_sub'] += $sub;
                $this->map['reven_room_ei']['year_src'] += $src;
                $this->map['reven_room_ei']['year_tax'] += $tax;
                $this->map['reven_room_ei']['year_total'] += $total;
                break;
            }
            case 'EXTRA_BED':
            {
                if($traveller_folio['create_time'] >= $date)
                {
                    $this->map['reven_room_extra_bed']['today_sub'] += $sub;
                    $this->map['reven_room_extra_bed']['today_src'] += $src;
                    $this->map['reven_room_extra_bed']['today_tax'] += $tax;
                    $this->map['reven_room_extra_bed']['today_total'] += $total;
                }
                if($traveller_folio['create_time'] >= Date_Time::to_time(date('1/m/y',$date)))
                {
                    $this->map['reven_room_extra_bed']['month_sub'] += $sub;
                    $this->map['reven_room_extra_bed']['month_src'] += $src;
                    $this->map['reven_room_extra_bed']['month_tax'] += $tax;
                    $this->map['reven_room_extra_bed']['month_total'] += $total;
                }
                $this->map['reven_room_extra_bed']['year_sub'] += $sub;
                $this->map['reven_room_extra_bed']['year_src'] += $src;
                $this->map['reven_room_extra_bed']['year_tax'] += $tax;
                $this->map['reven_room_extra_bed']['year_total'] += $total;
                break;
            }
            case 'DUADON':
            {
                if($traveller_folio['create_time'] >= $date)
                {
                    $this->map['reven_room_duadon']['today_sub'] += $sub;
                    $this->map['reven_room_duadon']['today_src'] += $src;
                    $this->map['reven_room_duadon']['today_tax'] += $tax;
                    $this->map['reven_room_duadon']['today_total'] += $total;
                }
                if($traveller_folio['create_time'] >= Date_Time::to_time(date('1/m/y',$date)))
                {
                    $this->map['reven_room_duadon']['month_sub'] += $sub;
                    $this->map['reven_room_duadon']['month_src'] += $src;
                    $this->map['reven_room_duadon']['month_tax'] += $tax;
                    $this->map['reven_room_duadon']['month_total'] += $total;
                }
                $this->map['reven_room_duadon']['year_sub'] += $sub;
                $this->map['reven_room_duadon']['year_src'] += $src;
                $this->map['reven_room_duadon']['year_tax'] += $tax;
                $this->map['reven_room_duadon']['year_total'] += $total;
                break;
            }
            case 'TOUR':
            {
                if($traveller_folio['create_time'] >= $date)
                {
                    $this->map['reven_room_tour']['today_sub'] += $sub;
                    $this->map['reven_room_tour']['today_src'] += $src;
                    $this->map['reven_room_tour']['today_tax'] += $tax;
                    $this->map['reven_room_tour']['today_total'] += $total;
                }
                if($traveller_folio['create_time'] >= Date_Time::to_time(date('1/m/y',$date)))
                {
                    $this->map['reven_room_tour']['month_sub'] += $sub;
                    $this->map['reven_room_tour']['month_src'] += $src;
                    $this->map['reven_room_tour']['month_tax'] += $tax;
                    $this->map['reven_room_tour']['month_total'] += $total;
                }
                $this->map['reven_room_tour']['year_sub'] += $sub;
                $this->map['reven_room_tour']['year_src'] += $src;
                $this->map['reven_room_tour']['year_tax'] += $tax;
                $this->map['reven_room_tour']['year_total'] += $total;
                break;
            }
            case 'BAR':
            {
                if($traveller_folio['create_time'] >= $date)
                {
                    $this->map['reven_room_bar']['today_sub'] += $sub;
                    $this->map['reven_room_bar']['today_src'] += $src;
                    $this->map['reven_room_bar']['today_tax'] += $tax;
                    $this->map['reven_room_bar']['today_total'] += $total;
                }
                if($traveller_folio['create_time'] >= Date_Time::to_time(date('1/m/y',$date)))
                {
                    $this->map['reven_room_bar']['month_sub'] += $sub;
                    $this->map['reven_room_bar']['month_src'] += $src;
                    $this->map['reven_room_bar']['month_tax'] += $tax;
                    $this->map['reven_room_bar']['month_total'] += $total;
                }
                $this->map['reven_room_bar']['year_sub'] += $sub;
                $this->map['reven_room_bar']['year_src'] += $src;
                $this->map['reven_room_bar']['year_tax'] += $tax;
                $this->map['reven_room_bar']['year_total'] += $total;
                break;
            }
            case 'MASSAGE':
            {
                /*
                $total = $traveller_folio['amount'];
                $sub = $total/((1 + $traveller_folio['tax_rate']/100)*(1 + $traveller_folio['service_rate']/100));
                $src = $sub*$traveller_folio['service_rate']/100;
                $tax = ($sub+$src)*$traveller_folio['tax_rate']/100;
                */
                if($traveller_folio['create_time'] >= $date)
                {
                    $this->map['reven_room_spa']['today_sub'] += $sub;
                    $this->map['reven_room_spa']['today_src'] += $src;
                    $this->map['reven_room_spa']['today_tax'] += $tax;
                    $this->map['reven_room_spa']['today_total'] += $total;
                }
                if($traveller_folio['create_time'] >= Date_Time::to_time(date('1/m/y',$date)))
                {
                    $this->map['reven_room_spa']['month_sub'] += $sub;
                    $this->map['reven_room_spa']['month_src'] += $src;
                    $this->map['reven_room_spa']['month_tax'] += $tax;
                    $this->map['reven_room_spa']['month_total'] += $total;
                }
                $this->map['reven_room_spa']['year_sub'] += $sub;
                $this->map['reven_room_spa']['year_src'] += $src;
                $this->map['reven_room_spa']['year_tax'] += $tax;
                $this->map['reven_room_spa']['year_total'] += $total;
                break;
            }
            case 'OTHER':
            {
                if($traveller_folio['create_time'] >= $date)
                {
                    $this->map['reven_room_other']['today_sub'] += $sub;
                    $this->map['reven_room_other']['today_src'] += $src;
                    $this->map['reven_room_other']['today_tax'] += $tax;
                    $this->map['reven_room_other']['today_total'] += $total;
                }
                if($traveller_folio['create_time'] >= Date_Time::to_time(date('1/m/y',$date)))
                {
                    $this->map['reven_room_other']['month_sub'] += $sub;
                    $this->map['reven_room_other']['month_src'] += $src;
                    $this->map['reven_room_other']['month_tax'] += $tax;
                    $this->map['reven_room_other']['month_total'] += $total;
                }
                $this->map['reven_room_other']['year_sub'] += $sub;
                $this->map['reven_room_other']['year_src'] += $src;
                $this->map['reven_room_other']['year_tax'] += $tax;
                $this->map['reven_room_other']['year_total'] += $total;
            }
            default:
            {
                
            }
        }
        
        if( $type == 'ROOM' or $type == 'MINIBAR' 
            or $type == 'LAUNDRY' or $type == 'EQUIPMENT' 
            or $type == 'TELEPHONE' or $type == 'LATE_CHECKOUT' 
            or $type == 'EARLY_CHECKIN' or $type == 'EXTRA_BED' 
            or $type == 'DUADON' or $type == 'TOUR' or $type == 'OTHER')
        {
            if($traveller_folio['create_time'] >= $date)
            {
                $this->map['reven_room_total']['today_sub'] += $sub;
                $this->map['reven_room_total']['today_src'] += $src;
                $this->map['reven_room_total']['today_tax'] += $tax;
                $this->map['reven_room_total']['today_total'] += $total;
            }
            if($traveller_folio['create_time'] >= Date_Time::to_time(date('1/m/y',$date)))
            {
                $this->map['reven_room_total']['month_sub'] += $sub;
                $this->map['reven_room_total']['month_src'] += $src;
                $this->map['reven_room_total']['month_tax'] += $tax;
                $this->map['reven_room_total']['month_total'] += $total;
            }
            $this->map['reven_room_total']['year_sub'] += $sub;
            $this->map['reven_room_total']['year_src'] += $src;
            $this->map['reven_room_total']['year_tax'] += $tax;
            $this->map['reven_room_total']['year_total'] += $total;
        }
    }
    
	function draw()
	{
        $this->map = array();
        $_REQUEST['date'] = Url::get('date',date('d/m/Y'));
        $date = Date_Time::to_time($_REQUEST['date']);
        
        /***get room revenue***/
        $array_init = array('today_sub'=>0,
                            'today_src'=>0,
                            'today_tax'=>0,
                            'today_total'=>0,
                            'month_sub'=>0,
                            'month_src'=>0,
                            'month_tax'=>0,
                            'month_total'=>0,
                            'year_sub'=>0,
                            'year_src'=>0,
                            'year_tax'=>0,
                            'year_total'=>0,);
        $this->map['reven_room_room'] = $array_init;
        $this->map['reven_breakfast'] = $array_init;
        $this->map['reven_room_minibar'] = $array_init;
        $this->map['reven_room_laundry'] = $array_init;
        $this->map['reven_room_equip'] = $array_init;
        $this->map['reven_room_telephone'] = $array_init;
        $this->map['reven_room_lo'] = $array_init;
        $this->map['reven_room_ei'] = $array_init;
        $this->map['reven_room_extra_bed'] = $array_init;
        $this->map['reven_room_duadon'] = $array_init;
        $this->map['reven_room_tour'] = $array_init;
        $this->map['reven_room_other'] = $array_init;
        $this->map['reven_room_total'] = $array_init;
        $this->map['reven_room_bar'] = $array_init;
        $this->map['reven_room_spa'] = $array_init;
        
        $cond_portal_room = Url::get('portal_id')?" and folio.portal_id = '".Url::get('portal_id')."'":"";
        $sql = "select traveller_folio.id,
                  traveller_folio.type,
                  traveller_folio.amount,
                  traveller_folio.service_rate,
                  traveller_folio.tax_rate,
                  extra_service.code,
                  folio.create_time,
                  NVL(reservation_room.adult,0) adult,
                  NVL(reservation_room.child,0) child,
                  reservation_room.breakfast,
                  massage_reservation_room.net_price as net_price
                from traveller_folio
                  inner join folio on folio.id = traveller_folio.folio_id
                  inner join reservation_room on reservation_room.id = traveller_folio.reservation_room_id
                  left outer join extra_service_invoice_detail on extra_service_invoice_detail.id = traveller_folio.invoice_id
                  left outer join extra_service on extra_service.id = extra_service_invoice_detail.service_id
                  left join massage_reservation_room on traveller_folio.invoice_id = massage_reservation_room.id 
                  left join payment on payment.folio_id = folio.id
                where not(traveller_folio.foc_all = 1 or (traveller_folio.foc is not null and traveller_folio.type = 'ROOM'))
                  and traveller_folio.type != 'DEPOSIT' and traveller_folio.type != 'DISCOUNT' 
                  and payment.time >= ".Date_Time::to_time(date('1/1/y',$date))." and payment.time < ".($date+86400).$cond_portal_room;
        
        $traveller_folios = DB::fetch_all($sql);
        //System::debug($sql);
        foreach($traveller_folios as $key => $value)
        {
            $type = $this->get_type($value['type'],$value['code']);
            $this->cal_reven($type,$value);
        }
        //System::debug($this->map['reven_room_other']);
        //exit();
        //***get revenue bar***/
        require_once 'packages/core/includes/system/id_structure.php';
        $structure_id_food = DB::fetch('select structure_id from product_category where code=\'DA\'','structure_id');
        $structure_id_drink = DB::fetch('select structure_id from product_category where code=\'DU\'','structure_id');
        
        $cond_portal_b_p = Url::get('portal_id')?" and bar_reservation.portal_id = '".Url::get('portal_id')."'":"";
        $sql = "select     bar_reservation_product.*,
                                            bar_reservation.tax_rate,
                                            bar_reservation.bar_fee_rate,
                                            bar_reservation.bar_id,
                                            product_category.structure_id,
                                            bar_reservation.time_out,
                                            case
                                                when bar_reservation.full_rate = 1
                                                then bar_reservation_product.quantity*bar_reservation_product.price/(1+bar_reservation.bar_fee_rate/100)/(1+bar_reservation.tax_rate/100)*(1-bar_reservation.discount_percent/100)
                                                else(
                                                    case 
                                                        when bar_reservation.full_charge = 1
                                                        then bar_reservation_product.quantity*bar_reservation_product.price/(1+bar_reservation.bar_fee_rate/100)*(1-bar_reservation.discount_percent/100)
                                                        else  bar_reservation_product.quantity*bar_reservation_product.price*(1-bar_reservation.discount_percent/100)
                                                    end
                                                )
                                            end as amount
                                        from bar_reservation_product
                                            inner join bar_reservation 
                                                    on (bar_reservation.id = bar_reservation_product.bar_reservation_id
                                                    and bar_reservation.STATUS='CHECKOUT'
                                                    and bar_reservation.time_out >= ".Date_Time::to_time(date('1/1/y',$date))." and bar_reservation.time_out < ".($date+86400)." ".$cond_portal_b_p.")
                                            inner join product on product.id = bar_reservation_product.product_id
                                            inner join product_category on product_category.id = product.category_id
                                        where bar_reservation.pay_with_room != 1
                                            and bar_reservation.time_out >= ".Date_Time::to_time(date('1/1/y',$date))." and bar_reservation.time_out < ".($date+86400)." ".$cond_portal_b_p."
                                        order by bar_reservation.time_out desc";
        
        $bar_rp = DB::fetch_all($sql);
        
        $cond_portal_bar = Url::get('portal_id')?" and portal_id = '".Url::get('portal_id')."'":"";
        $bars = DB::fetch_all(" select  bar.id,
                                    bar.name
                            from bar where 1=1 ".$cond_portal_bar);
        foreach($bars as $key => $value)
        {
            $bars[$key]['food'] = $array_init;
            $bars[$key]['drink'] = $array_init;
            $bars[$key]['other'] = $array_init;
            $bars[$key]['total'] = $array_init;
        }
        $this->map['reven_bar_total'] = $array_init;
        
        foreach($bar_rp as $key=>$value)
        {
            $sub = $value['amount'];
            $src = $sub*$value['bar_fee_rate']/100;
            $tax = ($sub+$src)*$value['tax_rate']/100;
            $total = $sub + $src + $tax;
            
            if(isset($bars[$value['bar_id']]))
            {
                if(IDStructure::is_child($value['structure_id'],$structure_id_food) or $value['structure_id'] == $structure_id_food)
                {
                    if($value['time_out'] >= $date)
                    {
                        $bars[$value['bar_id']]['food']['today_sub'] += $sub;
                        $bars[$value['bar_id']]['food']['today_src'] += $src;
                        $bars[$value['bar_id']]['food']['today_tax'] += $tax;
                        $bars[$value['bar_id']]['food']['today_total'] += $total;
                    }
                    if($value['time_out'] >= Date_Time::to_time(date('1/m/y',$date)))
                    {
                        $bars[$value['bar_id']]['food']['month_sub'] += $sub;
                        $bars[$value['bar_id']]['food']['month_src'] += $src;
                        $bars[$value['bar_id']]['food']['month_tax'] += $tax;
                        $bars[$value['bar_id']]['food']['month_total'] += $total;
                    }
                    $bars[$value['bar_id']]['food']['year_sub'] += $sub;
                    $bars[$value['bar_id']]['food']['year_src'] += $src;
                    $bars[$value['bar_id']]['food']['year_tax'] += $tax;
                    $bars[$value['bar_id']]['food']['year_total'] += $total;
                }
                else if(IDStructure::is_child($value['structure_id'],$structure_id_drink) or $value['structure_id'] == $structure_id_drink)
                {
                    if($value['time_out'] >= $date)
                    {
                        $bars[$value['bar_id']]['drink']['today_sub'] += $sub;
                        $bars[$value['bar_id']]['drink']['today_src'] += $src;
                        $bars[$value['bar_id']]['drink']['today_tax'] += $tax;
                        $bars[$value['bar_id']]['drink']['today_total'] += $total;
                    }
                    if($value['time_out'] >= Date_Time::to_time(date('1/m/y',$date)))
                    {
                        $bars[$value['bar_id']]['drink']['month_sub'] += $sub;
                        $bars[$value['bar_id']]['drink']['month_src'] += $src;
                        $bars[$value['bar_id']]['drink']['month_tax'] += $tax;
                        $bars[$value['bar_id']]['drink']['month_total'] += $total;
                    }
                    $bars[$value['bar_id']]['drink']['year_sub'] += $sub;
                    $bars[$value['bar_id']]['drink']['year_src'] += $src;
                    $bars[$value['bar_id']]['drink']['year_tax'] += $tax;
                    $bars[$value['bar_id']]['drink']['year_total'] += $total;
                }
                else 
                {
                    if($value['time_out'] >= $date)
                    {
                        $bars[$value['bar_id']]['other']['today_sub'] += $sub;
                        $bars[$value['bar_id']]['other']['today_src'] += $src;
                        $bars[$value['bar_id']]['other']['today_tax'] += $tax;
                        $bars[$value['bar_id']]['other']['today_total'] += $total;
                    }
                    if($value['time_out'] >= Date_Time::to_time(date('1/m/y',$date)))
                    {
                        $bars[$value['bar_id']]['other']['month_sub'] += $sub;
                        $bars[$value['bar_id']]['other']['month_src'] += $src;
                        $bars[$value['bar_id']]['other']['month_tax'] += $tax;
                        $bars[$value['bar_id']]['other']['month_total'] += $total;
                    }
                    $bars[$value['bar_id']]['other']['year_sub'] += $sub;
                    $bars[$value['bar_id']]['other']['year_src'] += $src;
                    $bars[$value['bar_id']]['other']['year_tax'] += $tax;
                    $bars[$value['bar_id']]['other']['year_total'] += $total;
                }
                
                if($value['time_out'] >= $date)
                {
                    $bars[$value['bar_id']]['total']['today_sub'] += $sub;
                    $bars[$value['bar_id']]['total']['today_src'] += $src;
                    $bars[$value['bar_id']]['total']['today_tax'] += $tax;
                    $bars[$value['bar_id']]['total']['today_total'] += $total;
                }
                if($value['time_out'] >= Date_Time::to_time(date('1/m/y',$date)))
                {
                    $bars[$value['bar_id']]['total']['month_sub'] += $sub;
                    $bars[$value['bar_id']]['total']['month_src'] += $src;
                    $bars[$value['bar_id']]['total']['month_tax'] += $tax;
                    $bars[$value['bar_id']]['total']['month_total'] += $total;
                }
                $bars[$value['bar_id']]['total']['year_sub'] += $sub;
                $bars[$value['bar_id']]['total']['year_src'] += $src;
                $bars[$value['bar_id']]['total']['year_tax'] += $tax;
                $bars[$value['bar_id']]['total']['year_total'] += $total;
            }
            
            //total
            if($value['time_out'] >= $date)
            {
                $this->map['reven_bar_total']['today_sub'] += $sub;
                $this->map['reven_bar_total']['today_src'] += $src;
                $this->map['reven_bar_total']['today_tax'] += $tax;
                $this->map['reven_bar_total']['today_total'] += $total;
            }
            if($value['time_out'] >= Date_Time::to_time(date('1/m/y',$date)))
            {
                $this->map['reven_bar_total']['month_sub'] += $sub;
                $this->map['reven_bar_total']['month_src'] += $src;
                $this->map['reven_bar_total']['month_tax'] += $tax;
                $this->map['reven_bar_total']['month_total'] += $total;
            }
            $this->map['reven_bar_total']['year_sub'] += $sub;
            $this->map['reven_bar_total']['year_src'] += $src;
            $this->map['reven_bar_total']['year_tax'] += $tax;
            $this->map['reven_bar_total']['year_total'] += $total;
        }
        
        //cong them bar thanh toan ve phong
        $this->map['reven_bar_total']['today_sub'] += $this->map['reven_room_bar']['today_sub'];
        $this->map['reven_bar_total']['today_src'] += $this->map['reven_room_bar']['today_src'];
        $this->map['reven_bar_total']['today_tax'] += $this->map['reven_room_bar']['today_tax'];
        $this->map['reven_bar_total']['today_total'] += $this->map['reven_room_bar']['today_total'];
        
        $this->map['reven_bar_total']['month_sub'] += $this->map['reven_room_bar']['month_sub'];
        $this->map['reven_bar_total']['month_src'] += $this->map['reven_room_bar']['month_src'];
        $this->map['reven_bar_total']['month_tax'] += $this->map['reven_room_bar']['month_tax'];
        $this->map['reven_bar_total']['month_total'] += $this->map['reven_room_bar']['month_total'];
                
        $this->map['reven_bar_total']['year_sub'] += $this->map['reven_room_bar']['year_sub'];
        $this->map['reven_bar_total']['year_src'] += $this->map['reven_room_bar']['year_src'];
        $this->map['reven_bar_total']['year_tax'] += $this->map['reven_room_bar']['year_tax'];
        $this->map['reven_bar_total']['year_total'] += $this->map['reven_room_bar']['year_total'];
        
        //cong them breakfast
        $this->map['reven_bar_total']['today_sub'] += $this->map['reven_breakfast']['today_sub'];
        $this->map['reven_bar_total']['today_src'] += $this->map['reven_breakfast']['today_src'];
        $this->map['reven_bar_total']['today_tax'] += $this->map['reven_breakfast']['today_tax'];
        $this->map['reven_bar_total']['today_total'] += $this->map['reven_breakfast']['today_total'];
        
        $this->map['reven_bar_total']['month_sub'] += $this->map['reven_breakfast']['month_sub'];
        $this->map['reven_bar_total']['month_src'] += $this->map['reven_breakfast']['month_src'];
        $this->map['reven_bar_total']['month_tax'] += $this->map['reven_breakfast']['month_tax'];
        $this->map['reven_bar_total']['month_total'] += $this->map['reven_breakfast']['month_total'];
                
        $this->map['reven_bar_total']['year_sub'] += $this->map['reven_breakfast']['year_sub'];
        $this->map['reven_bar_total']['year_src'] += $this->map['reven_breakfast']['year_src'];
        $this->map['reven_bar_total']['year_tax'] += $this->map['reven_breakfast']['year_tax'];
        $this->map['reven_bar_total']['year_total'] += $this->map['reven_breakfast']['year_total'];
        
        
        $this->map['bars'] = $bars;
        
        //***get revenue spa***/
        /*$con_today= "MASSAGE_PRODUCT_CONSUMED.TIME_OUT >= ".$date." and MASSAGE_PRODUCT_CONSUMED.TIME_OUT < ".($date+86400);
        $con_month = "MASSAGE_PRODUCT_CONSUMED.TIME_OUT >= ".Date_Time::to_time(date('1/m/y',$date))." and MASSAGE_PRODUCT_CONSUMED.TIME_OUT < ".($date+86400);
        $con_year = "MASSAGE_PRODUCT_CONSUMED.TIME_OUT >= ".Date_Time::to_time(date('1/1/y',$date))." and MASSAGE_PRODUCT_CONSUMED.TIME_OUT < ".($date+86400);
        $cond_portal_spa = Url::get('portal_id')?" and (MASSAGE_RESERVATION_ROOM.portal_id = '".Url::get('portal_id')."')":"";
        
        $sql_head = "
        select SUM(PRICE*QUANTITY*(1-NVL(DISCOUNT,0)/100)) as sub,
            SUM(PRICE*QUANTITY*(1-NVL(DISCOUNT,0)/100.0)*NVL(TAX,0)/100.0) as tax
        from MASSAGE_PRODUCT_CONSUMED
            inner join MASSAGE_RESERVATION_ROOM on 
            (
                MASSAGE_PRODUCT_CONSUMED.RESERVATION_ROOM_ID = MASSAGE_RESERVATION_ROOM.ID
                and MASSAGE_PRODUCT_CONSUMED.STATUS= 'CHECKOUT' and (";
        $sql_foot = ")) where MASSAGE_RESERVATION_ROOM.hotel_reservation_room_id is null".$cond_portal_spa;*/
        $con_today= " and MASSAGE_PRODUCT_CONSUMED.TIME_OUT >= ".$date." and MASSAGE_PRODUCT_CONSUMED.TIME_OUT < ".($date+86400);
        $con_month = " and MASSAGE_PRODUCT_CONSUMED.TIME_OUT >= ".Date_Time::to_time(date('1/m/y',$date))." and MASSAGE_PRODUCT_CONSUMED.TIME_OUT < ".($date+86400);
        $con_year = " and MASSAGE_PRODUCT_CONSUMED.TIME_OUT >= ".Date_Time::to_time(date('1/1/y',$date))." and MASSAGE_PRODUCT_CONSUMED.TIME_OUT < ".($date+86400);
        $cond_portal_spa = Url::get('portal_id')?" and (MASSAGE_RESERVATION_ROOM.portal_id = '".Url::get('portal_id')."')":"";
        $sql_head = "
        select distinct
        MASSAGE_RESERVATION_ROOM.id,
        MASSAGE_RESERVATION_ROOM.total_amount,
        MASSAGE_RESERVATION_ROOM.net_price,
        MASSAGE_RESERVATION_ROOM.service_rate,
        MASSAGE_RESERVATION_ROOM.tax as tax_rate
        from MASSAGE_PRODUCT_CONSUMED
            inner join MASSAGE_RESERVATION_ROOM on 
            (
                MASSAGE_PRODUCT_CONSUMED.RESERVATION_ROOM_ID = MASSAGE_RESERVATION_ROOM.ID
                and MASSAGE_PRODUCT_CONSUMED.STATUS= 'CHECKOUT'
            ) 
        where MASSAGE_RESERVATION_ROOM.hotel_reservation_room_id is null".$cond_portal_spa;
        $spa_today = DB::fetch_all($sql_head.$con_today);
        $spa_month = DB::fetch_all($sql_head.$con_month);
        $spa_year = DB::fetch_all($sql_head.$con_year);
        //tinh doanh thu massa trong ngày
        $this->map['reven_spa_total']['not_room']['today_sub'] = 0;
        $this->map['reven_spa_total']['not_room']['today_src'] = 0;
        $this->map['reven_spa_total']['not_room']['today_tax'] = 0;
        $this->map['reven_spa_total']['not_room']['today_total'] = 0;
        foreach($spa_today as $k_td=>$v_td)
        {
            $spa_today[$k_td]['total'] = $v_td['total_amount'];
            $spa_today[$k_td]['sub'] = $spa_today[$k_td]['total']/((1 + $v_td['tax_rate']/100)*(1 + $v_td['service_rate']/100));
            $spa_today[$k_td]['service_rate'] = $spa_today[$k_td]['sub']*$v_td['service_rate']/100;
            $spa_today[$k_td]['tax'] = ($spa_today[$k_td]['sub']+$spa_today[$k_td]['service_rate'])*$v_td['tax_rate']/100; 
            
            $this->map['reven_spa_total']['not_room']['today_sub'] += $spa_today[$k_td]['sub'];
            $this->map['reven_spa_total']['not_room']['today_src'] += $spa_today[$k_td]['service_rate'];
            $this->map['reven_spa_total']['not_room']['today_tax'] += $spa_today[$k_td]['tax'];
            $this->map['reven_spa_total']['not_room']['today_total'] += $spa_today[$k_td]['total'];
        }
        //end tinh doanh thu massa trong ngày
        //tinh doanh thu massa trong tháng
        $this->map['reven_spa_total']['not_room']['month_sub'] = 0;
        $this->map['reven_spa_total']['not_room']['month_src'] = 0;
        $this->map['reven_spa_total']['not_room']['month_tax'] = 0;
        $this->map['reven_spa_total']['not_room']['month_total'] = 0;
        foreach($spa_month as $k_tm=>$v_tm)
        {
           $spa_month[$k_tm]['total'] = $v_tm['total_amount'];
           $spa_month[$k_tm]['sub'] = $spa_month[$k_tm]['total']/((1 + $v_tm['tax_rate']/100)*(1 + $v_tm['service_rate']/100));
           $spa_month[$k_tm]['service_rate'] = $spa_month[$k_tm]['sub']*$v_tm['service_rate']/100;
           $spa_month[$k_tm]['tax'] = ($spa_month[$k_tm]['sub']+$spa_month[$k_tm]['service_rate'])*$v_tm['tax_rate']/100; 
           
           $this->map['reven_spa_total']['not_room']['month_sub'] += $spa_month[$k_tm]['sub'];
           $this->map['reven_spa_total']['not_room']['month_src'] += $spa_month[$k_tm]['service_rate'];
           $this->map['reven_spa_total']['not_room']['month_tax'] += $spa_month[$k_tm]['tax'];
           $this->map['reven_spa_total']['not_room']['month_total'] += $spa_month[$k_tm]['total'];
        }
        //end tinh doanh thu massa trong tháng
       //tinh doanh thu massa trong năm
        $this->map['reven_spa_total']['not_room']['year_sub'] = 0;
        $this->map['reven_spa_total']['not_room']['year_src'] = 0;
        $this->map['reven_spa_total']['not_room']['year_tax'] = 0;
        $this->map['reven_spa_total']['not_room']['year_total'] = 0;
        foreach($spa_year as $k_ty=>$v_ty)
        {
            $spa_year[$k_ty]['total'] = $v_ty['total_amount'];
            $spa_year[$k_ty]['sub'] = $spa_year[$k_ty]['total']/((1 + $v_ty['tax_rate']/100)*(1 + $v_ty['service_rate']/100));
            $spa_year[$k_ty]['service_rate'] = $spa_year[$k_ty]['sub']*$v_ty['service_rate']/100;
            $spa_year[$k_ty]['tax'] = ($spa_year[$k_ty]['sub']+$spa_year[$k_ty]['service_rate'])*$v_ty['tax_rate']/100; 
            
            $this->map['reven_spa_total']['not_room']['year_sub'] += $spa_year[$k_ty]['sub'];
            $this->map['reven_spa_total']['not_room']['year_src'] += $spa_year[$k_ty]['service_rate'];
            $this->map['reven_spa_total']['not_room']['year_tax'] += $spa_year[$k_ty]['tax'];
            $this->map['reven_spa_total']['not_room']['year_total'] += $spa_year[$k_ty]['total'];
        }
        //end tinh doanh thu massa trong năm
        $this->map['reven_spa_total']['total']['today_sub'] = $this->map['reven_spa_total']['not_room']['today_sub'] + $this->map['reven_room_spa']['today_sub'];
        $this->map['reven_spa_total']['total']['today_src'] = $this->map['reven_spa_total']['not_room']['today_src'] + $this->map['reven_room_spa']['today_src'];
        $this->map['reven_spa_total']['total']['today_tax'] = $this->map['reven_spa_total']['not_room']['today_tax'] + $this->map['reven_room_spa']['today_tax'];
        $this->map['reven_spa_total']['total']['today_total'] = $this->map['reven_spa_total']['not_room']['today_total'] + $this->map['reven_room_spa']['today_total'];
        
        $this->map['reven_spa_total']['total']['month_sub'] = $this->map['reven_spa_total']['not_room']['month_sub'] + $this->map['reven_room_spa']['month_sub'];
        $this->map['reven_spa_total']['total']['month_src'] = $this->map['reven_spa_total']['not_room']['month_src'] + $this->map['reven_room_spa']['month_src'];
        $this->map['reven_spa_total']['total']['month_tax'] = $this->map['reven_spa_total']['not_room']['month_tax'] + $this->map['reven_room_spa']['month_tax'];
        $this->map['reven_spa_total']['total']['month_total'] = $this->map['reven_spa_total']['not_room']['month_total'] + $this->map['reven_room_spa']['month_total'];
        
        $this->map['reven_spa_total']['total']['year_sub'] = $this->map['reven_spa_total']['not_room']['year_sub'] + $this->map['reven_room_spa']['year_sub'];
        $this->map['reven_spa_total']['total']['year_src'] = $this->map['reven_spa_total']['not_room']['year_src'] + $this->map['reven_room_spa']['year_src'];
        $this->map['reven_spa_total']['total']['year_tax'] = $this->map['reven_spa_total']['not_room']['year_tax'] + $this->map['reven_room_spa']['year_tax'];
        $this->map['reven_spa_total']['total']['year_total'] = $this->map['reven_spa_total']['not_room']['year_total'] + $this->map['reven_room_spa']['year_total'];
        //start:KID them phan ban hang
        //***get revenue vending***/
        $con_vending_today= " and VE_RESERVATION.TIME_IN >= ".$date." and VE_RESERVATION.TIME_IN < ".($date+86400);
        $con_vending_month = " and VE_RESERVATION.TIME_IN>= ".Date_Time::to_time(date('1/m/y',$date))." and VE_RESERVATION.TIME_IN < ".($date+86400);
        $con_vending_year = " and VE_RESERVATION.TIME_IN >= ".Date_Time::to_time(date('1/1/y',$date))." and VE_RESERVATION.TIME_IN < ".($date+86400);
        $cond_vending_portal = Url::get('portal_id')?" and (VE_RESERVATION.portal_id = '".Url::get('portal_id')."')":"";
        
        $sql_vending_head = "
        select SUM(TOTAL_BEFORE_TAX) as sub,
            SUM(TOTAL_BEFORE_TAX*BAR_FEE_RATE/100) as src,
            SUM((TOTAL_BEFORE_TAX+(TOTAL_BEFORE_TAX*BAR_FEE_RATE/100))*TAX_RATE/100) as tax
        from VE_RESERVATION where 1=1 ".$cond_vending_portal;
        
        $vending_today = DB::fetch($sql_vending_head.$con_vending_today);
        $vending_month = DB::fetch($sql_vending_head.$con_vending_month);
        $vending_year = DB::fetch($sql_vending_head.$con_vending_year);
        
        $this->map['reven_vending_total']['not_room']['today_sub'] = $vending_today['sub'];
        $this->map['reven_vending_total']['not_room']['today_src'] = $vending_today['src'];
        $this->map['reven_vending_total']['not_room']['today_tax'] = $vending_today['tax'];
        $this->map['reven_vending_total']['not_room']['today_total'] = $vending_today['tax'] + $vending_today['src'] + $vending_today['sub'];
        
        $this->map['reven_vending_total']['not_room']['month_sub'] = $vending_month['sub'];
        $this->map['reven_vending_total']['not_room']['month_src'] = $vending_month['src'];
        $this->map['reven_vending_total']['not_room']['month_tax'] = $vending_month['tax'];
        $this->map['reven_vending_total']['not_room']['month_total'] = $vending_month['tax'] + $vending_month['src'] + $vending_month['sub'];
        
        $this->map['reven_vending_total']['not_room']['year_sub'] = $vending_year['sub'];
        $this->map['reven_vending_total']['not_room']['year_src'] = $vending_year['src'];
        $this->map['reven_vending_total']['not_room']['year_tax'] = $vending_year['tax'];
        $this->map['reven_vending_total']['not_room']['year_total'] = $vending_year['tax'] + $vending_year['src']  + $vending_year['sub'];
        //end:KID them phan ban hang
        //System::debug($this->map);exit();
        $this->map['total']['today_sub'] = $this->map['reven_room_total']['today_sub']
                                            +$this->map['reven_bar_total']['today_sub']
                                            +$this->map['reven_spa_total']['total']['today_sub']
                                            +$this->map['reven_vending_total']['not_room']['today_sub'];
        $this->map['total']['today_src'] = $this->map['reven_room_total']['today_src']
                                            +$this->map['reven_bar_total']['today_src']
                                            +$this->map['reven_spa_total']['total']['today_src']
                                            +$this->map['reven_vending_total']['not_room']['today_src'];
        $this->map['total']['today_tax'] = $this->map['reven_room_total']['today_tax']
                                            +$this->map['reven_bar_total']['today_tax']
                                            +$this->map['reven_spa_total']['total']['today_tax']
                                            +$this->map['reven_vending_total']['not_room']['today_tax'];
        $this->map['total']['today_total'] = $this->map['reven_room_total']['today_total']
                                            +$this->map['reven_bar_total']['today_total']
                                            +$this->map['reven_spa_total']['total']['today_total']
                                            +$this->map['reven_vending_total']['not_room']['today_total'];
        
        $this->map['total']['month_sub'] = $this->map['reven_room_total']['month_sub']
                                            +$this->map['reven_bar_total']['month_sub']
                                            +$this->map['reven_spa_total']['total']['month_sub']
                                            +$this->map['reven_vending_total']['not_room']['month_sub'];
        $this->map['total']['month_src'] = $this->map['reven_room_total']['month_src']
                                            +$this->map['reven_bar_total']['month_src']
                                            +$this->map['reven_spa_total']['total']['month_src']
                                            +$this->map['reven_vending_total']['not_room']['month_src'];
        $this->map['total']['month_tax'] = $this->map['reven_room_total']['month_tax']
                                            +$this->map['reven_bar_total']['month_tax']
                                            +$this->map['reven_spa_total']['total']['month_tax']
                                            +$this->map['reven_vending_total']['not_room']['month_tax'];
        $this->map['total']['month_total'] = $this->map['reven_room_total']['month_total']
                                            +$this->map['reven_bar_total']['month_total']
                                            +$this->map['reven_spa_total']['total']['month_total']
                                            +$this->map['reven_vending_total']['not_room']['month_total'];
        
        $this->map['total']['year_sub'] = $this->map['reven_room_total']['year_sub']
                                            +$this->map['reven_bar_total']['year_sub']
                                            +$this->map['reven_spa_total']['total']['year_sub']
                                            +$this->map['reven_vending_total']['not_room']['year_sub'];
        $this->map['total']['year_src'] = $this->map['reven_room_total']['year_src']
                                            +$this->map['reven_bar_total']['year_src']
                                            +$this->map['reven_spa_total']['total']['year_src']
                                            +$this->map['reven_vending_total']['not_room']['year_src'];
        $this->map['total']['year_tax'] = $this->map['reven_room_total']['year_tax']
                                            +$this->map['reven_bar_total']['year_tax']
                                            +$this->map['reven_spa_total']['total']['year_tax']
                                            +$this->map['reven_vending_total']['not_room']['year_tax'];
        $this->map['total']['year_total'] = $this->map['reven_room_total']['year_total']
                                            +$this->map['reven_bar_total']['year_total']
                                            +$this->map['reven_spa_total']['total']['year_total']
                                            +$this->map['reven_vending_total']['not_room']['year_total'];
        
        if(Url::get('portal_id')){
            $hotel = DB::fetch('SELECT NAME_1 AS name,ADDRESS FROM PARTY WHERE USER_ID = \''.Url::get('portal_id').'\'');
            $hotel_name = $hotel['name']?$hotel['name']:HOTEL_NAME;
            $hotel_address = $hotel['address']?$hotel['address']:HOTEL_ADDRESS;
		}else{
            $hotel_name = HOTEL_NAME;
            $hotel_address = HOTEL_ADDRESS;
		}
        $this->parse_layout('report',array('hotel_address'=>$hotel_address,
                    					'hotel_name'=>$hotel_name,
                                        'portal_id_list'=>array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list()))
                                        +$this->map);
	}
}
?>
