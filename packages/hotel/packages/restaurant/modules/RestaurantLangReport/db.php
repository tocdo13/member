<?php 
class RestaurantLangReportDB
{
    //ham tra ve doanh thu cua mot bar_reservation_id
    // theo hai tieu chi phan loai theo hang hoa, phan loai theo gia von va doanh thu thuc te (gia ban ra)
    static function getRestaurantReservationRevenue($id)
    {
        //lay ra cac san pham trong mot bar reservation
        $product_items =RestaurantLangReportDB::get_reservation_product($id);
        //System::debug($product_items);
        $total_discount = 0;
		$total_price = 0;
        $product_for_report = array();
        $product_for_report['product']['original_price'] = 0;
        $product_for_report['product']['real_price'] = 0;
        $product_for_report['is_processed']['real_price'] = 0;
        $product_for_report['is_processed']['original_price'] = 0;
        $product_for_report['service']['real_price'] = 0;
        $product_for_report['service']['original_price'] = 0;
        foreach($product_items as $key=>$value)
		{
			if($value['is_processed'] == 1 and $value['type'] != 'SERVICE')
            {
				$ttl = $value['price']*($value['quantity'] - $value['quantity_discount']);
				$discnt = ($ttl*$value['discount_category']/100) + (($ttl*(100-$value['discount_category'])/100)*$value['discount_rate']/100);
				$total_discount += $discnt;
				if($ttl<0)
                {
					$ttl = 0;
				}
				$total_price += $ttl-$discnt;
                $product_for_report['is_processed']['original_price'] += $value['original_price'];
                $product_for_report['is_processed']['real_price'] += $ttl-$discnt;
			}
            if($value['is_processed'] == 0 and $value['type'] != 'SERVICE')
            {
				$ttl = $value['price']*($value['quantity'] - $value['quantity_discount']);
				$discnt = ($ttl*$value['discount_category']/100) + (($ttl*(100-$value['discount_category'])/100)*$value['discount_rate']/100);
				$total_discount += $discnt;
				if($ttl<0)
                {
					$ttl = 0;
				}
				$total_price += $ttl-$discnt;
                $product_for_report['product']['original_price'] += $value['original_price'];
                $product_for_report['product']['real_price'] += $ttl-$discnt;	
			}
            if($value['type'] == 'SERVICE')
            {
                $ttl = $value['price']*($value['quantity'] - $value['quantity_discount']);
				$discnt = ($ttl*$value['discount_category']/100) + (($ttl*(100-$value['discount_category'])/100)*$value['discount_rate']/100);
				$total_discount += $discnt;
				if($ttl<0)
                {
					$ttl = 0;
				}
				$total_price += $ttl-$discnt;
                $product_for_report['service']['original_price'] += $value['original_price'];
                $product_for_report['service']['real_price'] += $ttl-$discnt;	
            }
		}
        return $product_for_report;
    }
    //
    static function get_restaurant_revenue($cond,$for_portal = false,$portal_id = false)
    {
        if($for_portal)
        {
            $cond .= ' AND br.portal_id = \''.$for_portal.'\'';
        }
        else
        {
            if($portal_id)
            {
                $cond .= ' AND br.portal_id = \''.$portal_id.'\'';
            }
        }
        $sql = 'select 
                    br.id,
                    bar.name as bar_name
                from
                    bar_reservation br
                    inner join bar on br.bar_id = bar.id
                where 
                    br.status = \'CHECKOUT\'
                    and '.$cond.'
                ';
        $items = DB::fetch_all($sql);
        $return_items = array();
        if(!$for_portal)
        {
            foreach($items as $key => $value)
            {
                $revenue = RestaurantLangReportDB::getRestaurantReservationRevenue($value['id']);
                if(isset($return_items[$value['bar_name']]))
                {
                    //tinh tong cho tung bar
                    $return_items[$value['bar_name']]['real_price'] += $revenue['is_processed']['real_price'] + $revenue['product']['real_price'] + $revenue['service']['real_price'];
                    $return_items[$value['bar_name']]['original_price'] += $revenue['is_processed']['original_price'] + $revenue['product']['original_price'] + $revenue['service']['original_price'];
                    $return_items[$value['bar_name']]['is_processed']['original_price'] += $revenue['is_processed']['original_price'];
                    $return_items[$value['bar_name']]['is_processed']['real_price'] += $revenue['is_processed']['real_price'];
                    $return_items[$value['bar_name']]['product']['original_price'] += $revenue['product']['original_price'];
                    $return_items[$value['bar_name']]['product']['real_price'] += $revenue['product']['real_price'];
                    $return_items[$value['bar_name']]['service']['original_price'] += $revenue['service']['original_price'];
                    $return_items[$value['bar_name']]['service']['real_price'] += $revenue['service']['real_price'];
                }
                else
                {
                    $return_items[$value['bar_name']]['real_price'] = $revenue['is_processed']['real_price'] + $revenue['product']['real_price'] + $revenue['service']['real_price'];
                    $return_items[$value['bar_name']]['original_price'] = $revenue['is_processed']['original_price'] + $revenue['product']['original_price'] + $revenue['service']['original_price'];
                    $return_items[$value['bar_name']]['is_processed']['original_price'] = $revenue['is_processed']['original_price'];
                    $return_items[$value['bar_name']]['is_processed']['real_price'] = $revenue['is_processed']['real_price'];
                    $return_items[$value['bar_name']]['product']['original_price'] = $revenue['product']['original_price'];
                    $return_items[$value['bar_name']]['product']['real_price'] = $revenue['product']['real_price'];
                    $return_items[$value['bar_name']]['service']['original_price'] = $revenue['service']['original_price'];
                    $return_items[$value['bar_name']]['service']['real_price'] = $revenue['service']['real_price'];
                }
            }
            return $return_items;
        }
        else
        {
            $return_items['real_price'] = 0;
            $return_items['portal_id'] = $for_portal;
            $return_items['original_price'] = 0;
            $return_items['is_processed']['original_price'] = 0;
            $return_items['is_processed']['real_price'] = 0;
            $return_items['product']['original_price'] = 0;
            $return_items['product']['real_price'] = 0;
            $return_items['service']['original_price'] = 0;
            $return_items['service']['real_price'] = 0;
            foreach($items as $key => $value)
            {
                $revenue = RestaurantLangReportDB::getRestaurantReservationRevenue($value['id']);
                $return_items['real_price'] += $revenue['is_processed']['real_price'] + $revenue['product']['real_price'] + $revenue['service']['real_price'];
                $return_items['original_price'] += $revenue['is_processed']['original_price'] + $revenue['product']['original_price'] + $revenue['service']['original_price'];
                $return_items['is_processed']['original_price'] += $revenue['is_processed']['original_price'];
                $return_items['is_processed']['real_price'] += $revenue['is_processed']['real_price'];
                $return_items['product']['original_price'] += $revenue['product']['original_price'];
                $return_items['product']['real_price'] += $revenue['product']['real_price'];
                $return_items['service']['original_price'] += $revenue['service']['original_price'];
                $return_items['service']['real_price'] += $revenue['service']['real_price'];
            }
            return $return_items;
        }
    }
    static function get_reservation_product($id)
	{
		 $sql = '
			select 
				bar_reservation_product.id,
				bar_reservation_product.product_id,
                bar_reservation_product.is_processed,
                bar_reservation_product.original_price,			
				product.category_id,
				bar_reservation_product.quantity as quantity, 
				bar_reservation_product.quantity_discount as quantity_discount,
				bar_reservation_product.quantity_cancel as quantity_cancel, 
				bar_reservation_product.price, 
				bar_reservation_product.discount_rate as discount_rate,
				bar_reservation_product.discount_category,
				unit.name_'.Portal::language().' as unit_name
				,product.type
			from 
				bar_reservation_product   
				INNER JOIN product_price_list on product_price_list.id = bar_reservation_product.price_id
				INNER JOIN product on product.id = bar_reservation_product.product_id
				LEFT OUTER join unit on unit.id = product.unit_id 
				LEFT OUTER JOIN product_category ON product_category.id = product.category_id
			where 1>0 and bar_reservation_product.bar_reservation_id=\''.$id.'\'
			order by
				bar_reservation_product.id
		';
        //System::debug($sql);
		$reservation_products =  DB::fetch_all($sql);
		return $reservation_products;
	}
}
?>