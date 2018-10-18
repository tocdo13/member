<?php 
class TicketServiceTotalReportDB
{
    static function get_all_services($cond)
    {
        if(Portal::language() == 1)
        {
            $lt = '';
        }
        else
        {
            $lt = '_2';
        }
        $sql = 'select 
                    ticket_service.id || \'_\' || ticket_service_grant.ticket_id || \'_\' || ticket.ticket_group_id || \'_\' || ticket.portal_id as id,
                    ticket_service.name_'.Portal::language().' as service_name,
                    ticket.name'.$lt.' as ticket_name,
                    ticket_service.id as ticket_service_id,
                    ticket_service.name_2 as ticket_service_name_2,
                    ticket.ticket_group_id,
                    ticket.portal_id,
                    (ticket_service.price - ticket_service_grant.discount_money)*(1 - ticket_service_grant.discount_percent/100) as price
                from
                    ticket_service
                    inner join ticket_service_grant on ticket_service.id = ticket_service_grant.ticket_service_id
                    inner join ticket on ticket_service_grant.ticket_id = ticket.id
                where 
                    '.$cond.'
                ';
        //System::debug($sql);
        return $items = DB::fetch_all($sql);
    }
    static function get_all_bought_services($cond, $cond_choose_service)
    {
        $sql = 'select
                    ticket_invoice_detail.id,
                    ticket_invoice_detail.ticket_service_id || \'_\' || ticket_invoice.ticket_id || \'_\' || ticket.ticket_group_id || \'_\' || ticket.portal_id as ticket_service_id,
                    ticket_invoice_detail.quantity,
                    ticket_invoice.discount_quantity,
                    ticket_invoice.discount_rate,
                    ticket_invoice.discount_cash,
                    ticket_invoice.num_cancel,
                    ticket_reservation.discount_rate as all_discount_rate
                from
                    ticket_invoice_detail
                    inner join ticket_invoice on ticket_invoice_detail.ticket_invoice_id = ticket_invoice.id and ticket_invoice_detail.ticket_id = ticket_invoice.ticket_id
                    inner join ticket_reservation on ticket_invoice.ticket_reservation_id = ticket_reservation.id
                    inner join ticket on ticket_invoice.ticket_id = ticket.id
                where 
                    '.$cond.'
                ';
        $items = DB::fetch_all($sql);
        
        $all_services = TicketServiceTotalReportDB::get_all_services($cond_choose_service);
        $return_items = array();
        foreach($all_services as $key => $value)
        {
            foreach($items as $t => $l)
            {
                if($l['ticket_service_id'] == $key)
                {
                    if(!isset($return_items[$key]))
                    {
                        //$quantity = $l['quantity'] - $l['discount_quantity'] - $l['num_cancel'];
                        $quantity = $l['quantity'] - $l['discount_quantity'];
                        $lt_t = ($quantity)*$value['price'];
                        $lt_d = ($lt_t)*($l['discount_rate']/100) + ($lt_t - ($lt_t)*($l['discount_rate']/100))*$l['all_discount_rate']/100;
                        
                        $lt_net = ($lt_t - $lt_d)/1.1;
                        $lt_tax = ($lt_t - $lt_d) - $lt_net;
                        //$lt_net = $lt_t - $lt_d - $lt_tax;
                        //$price = $l['quantity'] - $l['discount_quantity'] - $l['num_cancel'];
                        $return_items[$key]['name'] = $value['service_name'];
                        $return_items[$key]['ticket_group_id'] = $value['ticket_group_id'];
                        $return_items[$key]['ticket_service_id'] = $value['ticket_service_id'];
                        $return_items[$key]['ticket_service_name_2'] = $value['ticket_service_name_2'];
                        $return_items[$key]['portal_id'] = $value['portal_id'];
                        $return_items[$key]['ticket_name'] = $value['ticket_name'];
                        $return_items[$key]['price'] = $value['price'];
                        $return_items[$key]['quantity'] = $quantity;
                        $return_items[$key]['discount_quantity'] = $l['discount_quantity'];
                        $return_items[$key]['total_amount'] = $lt_t;
                        $return_items[$key]['total_discount'] = $lt_d;
                        $return_items[$key]['total_tax'] = $lt_tax;
                        $return_items[$key]['net_amount'] = $lt_net;
                        if($l['discount_quantity'] != 0)
                        {
                            $return_items[$key]['row_num'] = 2;
                        }
                    }
                    else
                    {
                        $quantity = $l['quantity'] - $l['discount_quantity']; //- $l['num_cancel']
                        $lt_t = ($quantity)*$value['price'];
                        $lt_d = ($lt_t)*($l['discount_rate']/100) + ($lt_t - ($lt_t)*($l['discount_rate']/100))* $l['all_discount_rate']/100;
                        $lt_net = ($lt_t - $lt_d)/1.1;
                        $lt_tax = ($lt_t - $lt_d) - $lt_net;
                        //$price = $l['quantity'] - $l['discount_quantity'] - $l['num_cancel'];
                        $return_items[$key]['name'] = $value['service_name'];
                        $return_items[$key]['ticket_group_id'] = $value['ticket_group_id'];
                        $return_items[$key]['ticket_service_id'] = $value['ticket_service_id'];
                        $return_items[$key]['ticket_service_name_2'] = $value['ticket_service_name_2'];
                        $return_items[$key]['portal_id'] = $value['portal_id'];
                        $return_items[$key]['ticket_name'] = $value['ticket_name'];
                        $return_items[$key]['price'] = $value['price'];
                        $return_items[$key]['quantity'] += $quantity;
                        $return_items[$key]['discount_quantity'] += $l['discount_quantity'];
                        $return_items[$key]['total_amount'] += $lt_t;
                        $return_items[$key]['total_discount'] += $lt_d;
                        $return_items[$key]['total_tax'] += $lt_tax;
                        $return_items[$key]['net_amount'] += $lt_net;
                        if($l['discount_quantity'] != 0)
                        {
                            $return_items[$key]['row_num'] = 2;
                        }
                    }
                }
            }
        }
        //System::debug($return_items);
        return $return_items;
    }
    function get_all_ticket_service_revenue($cond,$choose_ticket_service)
    {
        $items = TicketServiceTotalReportDB::get_all_bought_services($cond,$choose_ticket_service);
        //System::debug($items);
        $return_items = array();
        foreach($items as $key => $value)
        {
            $return_key = $value['ticket_service_id'].'_'.$value['ticket_group_id'].'_'.$value['portal_id'];
            if(!isset($return_items[$return_key]))
            {
                $return_items[$return_key]['ticket_service_id'] = $value['ticket_service_id'];
                $return_items[$return_key]['ticket_service_name_2'] = $value['ticket_service_name_2'];
                $return_items[$return_key]['area_id'] = $value['ticket_group_id'];
                $return_items[$return_key]['portal_id'] = $value['portal_id'];
                $return_items[$return_key]['net_amount'] = $value['net_amount'];      
            }
            else
            {
                $return_items[$return_key]['net_amount'] += $value['net_amount'];
            }
        }
        return $return_items;
    }
}
?>