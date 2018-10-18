<?php
class TotalyReportDB
{
     function count_traveller($condition)
    {
        $sql = '
                select
                    reservation_traveller.id,
                    traveller.province_id,
                    province.code as province_code,
                    country.continents_id,
                    area_group.name_'.Portal::language().' as area_name,
                    area_group.id as area_id,
                    room.portal_id
                from
                    reservation_traveller
                    inner join traveller on traveller.id = reservation_traveller.traveller_id
                    inner join country on traveller.nationality_id = country.id
                    inner join reservation_room on reservation_traveller.reservation_room_id = reservation_room.id
                    inner join room on reservation_room.room_id = room.id
                    left join area_group on room.area_id = area_group.id
                    left join province on traveller.province_id = province.id
                where
                    '.$condition.'
                ';

        $items = DB::fetch_all($sql);
        return $items;
    }
    function room_have_traveller($condition,$to_date)
    {
        $room_traveller=DB::fetch_all('select reservation_room.id as id,reservation_room.departure_time, reservation_room.arrival_time, room.area_id, room.portal_id
                                       from reservation_room
                                       inner join room on room.id = reservation_room.room_id
                                       where 
                                       '.$condition.'and (((reservation_room.status =\'CHECKOUT\' OR reservation_room.status =\'CHECKIN\') and (reservation_room.arrival_time = \''.$to_date.'\' and reservation_room.departure_time =\''.$to_date.'\')) OR((reservation_room.status =\'CHECKOUT\' OR reservation_room.status =\'CHECKIN\') and reservation_room.departure_time !=\''.$to_date.'\' ) )'  
                                     );
        return  $room_traveller;                            
    }
    function room_price_not_VAT($condition,$to_date,$from_date)
    {
        $sql='select room_status.id as id, 
              room_status.reservation_room_id,
              (
                    CASE 
                        WHEN reservation_room.net_price=0 THEN room_status.change_price 
                        ELSE 
                            (room_status.change_price/((reservation_room.tax_rate + 100)*0.01)) 
                    END
              ) as change_price,
              room_status.in_date,
              room.area_id,
              DECODE(reservation_room.reduce_balance,null,0,reservation_room.reduce_balance) as reduce_balance,
              DECODE(reservation_room.reduce_amount,null,0,reservation_room.reduce_amount) as reduce_amount,
              reservation_room.arrival_time,
              reservation_room.departure_time,
              room.portal_id
              from room_status 
              inner join reservation_room on reservation_room.id = room_status.reservation_room_id
              inner join room on reservation_room.room_id = room.id
              where'.$condition.' 
              OR ((reservation_room.status =\'CHECKOUT\' OR reservation_room.status =\'CHECKIN\') 
              AND room_status.in_date=\''.$to_date.'\' AND room_status.in_date=\''.$from_date.'\')';
        $room_price=DB::fetch_all($sql);
        
        return $room_price;
    }
    function ei_lo($condition)
    {
        $sql='select extra_service_invoice.id as id,
              extra_service_invoice.total_before_tax,
              extra_service_invoice.reservation_room_id,
              extra_service_invoice.time,
              room.area_id,
              room.portal_id
              from extra_service_invoice
              inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
              inner join room on room.id = reservation_room.room_id
              where'.$condition.' and extra_service_invoice.payment_type =\'ROOM\'';
        $extra_service_room=DB::fetch_all($sql);
        return $extra_service_room;
    }
    function extra_service_price($condition)
    {
        $sql='select extra_service_invoice.id as id,
              extra_service_invoice.total_before_tax,
              extra_service_invoice.reservation_room_id,
              extra_service_invoice.time,
              room.area_id,
              room.portal_id
              from extra_service_invoice
              inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
              inner join room on room.id = reservation_room.room_id
              where'.$condition.' and extra_service_invoice.payment_type=\'SERVICE\'';
        $extra_service=DB::fetch_all($sql);
        return $extra_service;
    }
    function minibar_laundry_equipment_price($condition)
    {

        $sql='select housekeeping_invoice.id as id,
              housekeeping_invoice.total_before_tax,
              housekeeping_invoice.reservation_room_id,
              housekeeping_invoice.time,
              room.area_id,
              room.portal_id
              from housekeeping_invoice
              inner join reservation_room on reservation_room.id = housekeeping_invoice.reservation_room_id
              inner join room on room.id = reservation_room.room_id
              where'.$condition.'';
        $housekeeping=DB::fetch_all($sql);
        return $housekeeping;
    }
    function bar_price($condition)
    {
        $sql='select bar_reservation.id as id,
              bar_reservation.total_before_tax,
              bar_reservation.portal_id,
              bar_reservation.arrival_time,
              bar_reservation.departure_time,
              bar_reservation.status,
              bar.area_id
              from bar_reservation
              inner join bar on bar.id = bar_reservation.bar_id
              where'.$condition.' AND bar.code !=\'LANGNGHE\'';
        $bar = DB::fetch_all($sql);
        return $bar;
    }
    function spa_price($condition)
    {
        $sql='select massage_product_consumed.id as id,
              CASE WHEN (massage_reservation_room.discount is null)
				THEN (massage_product_consumed.price * massage_product_consumed.quantity)
				ELSE (massage_product_consumed.price * massage_product_consumed.quantity - massage_product_consumed.price * massage_product_consumed.quantity * massage_reservation_room.discount/100)
              END	as total_before_tax,  
              massage_room.area_id,
              massage_room.portal_id
              from massage_product_consumed
              inner join massage_room on massage_product_consumed.room_id = massage_room.id
              inner join massage_reservation_room on massage_product_consumed.reservation_room_id = massage_reservation_room.id
              where
              '.$condition.'';
              //massage_product_consumed.price * massage_product_consumed.quantity as total_before_tax,
        $spa = DB::fetch_all($sql);
        return $spa;
    }
    function entrance_ticket_price($condition)
    {
        $sql='select ticket_invoice.id as id, ticket_invoice.price,ticket_invoice.quantity,
              DECODE(ticket_invoice.discount_quantity,null,0,ticket_invoice.discount_quantity) as discount_quantity,
              DECODE(ticket_invoice.discount_rate,null,0,ticket_invoice.discount_rate) as discount_rate,
              DECODE(ticket_invoice.discount_cash,null,0,ticket_invoice.discount_cash) as discount_cash,
              DECODE(ticket_reservation.discount_rate,null,0,ticket_reservation.discount_rate) as discount_percent,
              ticket.ticket_group_id as area_id,
              ticket_invoice.portal_id
              from ticket_invoice 
              inner join ticket_reservation on ticket_invoice.ticket_reservation_id = ticket_reservation.id
              inner join ticket on ticket_invoice.ticket_id = ticket.id
              where
              '.$condition.'';
        $entrance = DB::fetch_all($sql);
        return $entrance;
    }
    function village_price($condition)
    {
        $sql='select bar_reservation.id as id,
              bar_reservation.total_before_tax,
              bar_reservation.portal_id,
              bar_reservation.arrival_time,
              bar_reservation.departure_time,
              bar_reservation.status,
              bar.area_id
              from bar_reservation
              inner join bar on bar.id = bar_reservation.bar_id
              where'.$condition.' AND bar.code =\'LANGNGHE\'';
        $village = DB::fetch_all($sql);
        return $village;
    }
    function souvenir_price($condition)
    {
        $sql='select ve_reservation.id as id,
              ve_reservation.total_before_tax,
              ve_reservation.portal_id,
              ve_reservation.arrival_time,
              ve_reservation.status
              from ve_reservation
              where'.$condition.'';
        $souvenir = DB::fetch_all($sql);
        return $souvenir;
    }
    function plan($condition,$plan_name)
    {
        $this->map['total_plan'] = 0;
        $sql='select plan_in_month.id as id,
              plan_in_month.total as name,
              plan_in_month.month,
              plan_in_month.year,
              plan_in_month.value
              from plan_in_month
              where'.$condition.' and plan_in_month.total =\''.$plan_name.'\'';
        $plan = DB::fetch_all($sql);
        foreach($plan as $ke =>$ve)
        {
            $this->map['total_plan'] += $ve['value'];
        }
        return  $this->map['total_plan'];
    }
}
 // nhà hàng chuyển về phòng 
        //$total_bar_room_welness = 0;
//        $total_bar_room_fitness = 0;
//        $total_bar_room_queen1 = 0;
//        $total_bar_room_queen2 = 0;
       // $sql='select bar_reservation.id as id,
//              bar_reservation.total_before_tax,
//              bar_reservation.portal_id,
//              bar_reservation.arrival_time,
//              bar_reservation.departure_time,
//              bar_reservation.status,
//              room.area_id
//              from bar_reservation
//              inner join bar on bar.id = bar_reservation.bar_id
//              left join reservation_room on reservation_room.id = bar_reservation.reservation_room_id
//              inner join room on reservation_room.room_id = room.id
//              where'.$cond5.'AND bar_reservation.pay_with_room =1';
//        $bar_to_room=DB::fetch_all($sql);
//        foreach($bar_to_room as $ke=>$bar_room)
//        {
//            if($bar_room['portal_id'] == '#default')
//            {
//                if($bar_room['area_id'] == 2)
//                {
//                    $total_bar_room_welness += $bar_room['total_before_tax'];
//                }
//                if($bar_room['area_id'] == 1)
//                {
//                    $total_bar_room_fitness += $bar_room['total_before_tax'];
//                }
//            }
//            if($bar_room['portal_id'] == '#huequeen1')
//            {
//                $total_bar_room_queen1 += $bar_room['total_before_tax'];
//            }
//            if($bar_room['portal_id'] == '#huequeen2')
//            {
//                $total_bar_room_queen2 += $bar_room['total_before_tax'];
//            }
//        }
?>
