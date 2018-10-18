<?php

/**
 * @author ngocdatbk
 * @copyright 2013
 */

class RestaurantRevenueReportFormDB
{
	static function getdata($from_date,$to_date)
	{
        $sql = "select distinct product.id,
                  product.name_".Portal::language()." as product_name,
                  product_price_list.product_pipeline,
                  unit.name_".Portal::language()." as unit_name,
                  ve_reservation_product.price,
                  from_unixtime(ve_reservation.time)
                from ve_reservation
                  inner join ve_reservation_product on ve_reservation.id = ve_reservation_product.bar_reservation_id
                  inner join product on ve_reservation_product.product_id = product.id
                  inner join product_price_list on product_price_list.product_id = product.id
                  inner join unit on  unit.id = product.unit_id
                where to_date(FROM_UNIXTIME(VE_RESERVATION.TIME),'DD-mon-YY')>='".$from_date."' 
                  and to_date(FROM_UNIXTIME(VE_RESERVATION.TIME),'DD-mon-YY')<='".$to_date."' 
                  and ve_reservation.foc != 1 
                  and (product_price_list.department_code = 'RETAIL' or product_price_list.department_code = 'WHOLESALE') 
                  and ve_reservation.portal_id = '#default'";
        $list_product = DB::fetch_all($sql);
        //echo $sql;
        //System::debug($list_product);
        foreach($list_product as $key => $value)
        {
            $sql = "select sum(ve_reservation_product.quantity) as quantity,
                      sum(NVL(ve_reservation_product.quantity_discount,0)) as quantity_foc,
                      sum((ve_reservation_product.quantity - NVL(ve_reservation_product.quantity_discount,0))*ve_reservation_product.price) as total,
                      sum((ve_reservation_product.quantity - NVL(ve_reservation_product.quantity_discount,0))*ve_reservation_product.price*NVL(ve_reservation_product.discount_rate,0)/100) as agent_discount,
                      sum((ve_reservation_product.quantity - NVL(ve_reservation_product.quantity_discount,0))*ve_reservation_product.price*NVL(ve_reservation_product.promotion,0)/100) as promotion,
                      sum
                      (
                          (ve_reservation_product.quantity - NVL(ve_reservation_product.quantity_discount,0))
                          *
                          ve_reservation_product.price
                          *
                          (1- NVL(ve_reservation_product.promotion,0)/100 - NVL(ve_reservation_product.discount_rate,0)/100)
                      ) as net_amount
                    from ve_reservation_product
                     inner join ve_reservation on ve_reservation.id = ve_reservation_product.bar_reservation_id
                    where ve_reservation_product.product_id = '".$key."'
                      and to_date(FROM_UNIXTIME(VE_RESERVATION.TIME),'DD-mon-YY')>='".$from_date."' 
                      and to_date(FROM_UNIXTIME(VE_RESERVATION.TIME),'DD-mon-YY')<='".$to_date."' 
                      and ve_reservation.foc != 1 
                      and ve_reservation.portal_id = '#default'";
            $product_bonus = DB::fetch($sql);
            $list_product[$key]['quantity'] = $product_bonus['quantity'];
            $list_product[$key]['foc'] = $product_bonus['quantity_foc'];
            $list_product[$key]['total'] = $product_bonus['total'];
            $list_product[$key]['agent_discount'] = $product_bonus['agent_discount'];
            $list_product[$key]['promotion'] = $product_bonus['promotion'];
            $list_product[$key]['net_amount'] = $product_bonus['net_amount'];
            $list_product[$key]['net_revenue'] = $list_product[$key]['net_amount']*10/11;
            $list_product[$key]['total_tax'] = 0.1*$list_product[$key]['net_revenue'];
        }
        return $list_product;
    }
    
    static function get_summary($list)
    {
        $summary['alba'] = array("total"=>0,
                                "agent_discount"=>0,
                                "promotion"=>0,
                                "net_amount"=>0,
                                "net_revenue"=>0,
                                "total_tax"=>0);
        $summary['thanhtan'] = array("total"=>0,
                                "agent_discount"=>0,
                                "promotion"=>0,
                                "net_amount"=>0,
                                "net_revenue"=>0,
                                "total_tax"=>0);
        foreach($list as $key => $value)
        {
            if($value['product_pipeline'] == "ALBA")
            {
                $summary['alba']['total'] += $value['total'];
                $summary['alba']['agent_discount'] += $value['agent_discount'];
                $summary['alba']['promotion'] += $value['promotion'];
                $summary['alba']['net_amount'] += $value['net_amount'];
                $summary['alba']['net_revenue'] += $value['net_revenue'];
                $summary['alba']['total_tax'] += $value['total_tax'];
            }
            if($value['product_pipeline'] != "ALBA")
            {
                $summary['thanhtan']['total'] += $value['total'];
                $summary['thanhtan']['agent_discount'] += $value['agent_discount'];
                $summary['thanhtan']['promotion'] += $value['promotion'];
                $summary['thanhtan']['net_amount'] += $value['net_amount'];
                $summary['thanhtan']['net_revenue'] += $value['net_revenue'];
                $summary['thanhtan']['total_tax'] += $value['total_tax'];
            }
        }
        
        return $summary;
    }
}
?>