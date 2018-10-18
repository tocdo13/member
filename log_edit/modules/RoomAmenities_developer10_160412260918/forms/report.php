<?php
class RoomAmenitiesReportForm extends Form
{
	function RoomAmenitiesReportForm()
	{
		Form::Form('RoomAmenitiesReportForm');
		$this->link_css(Portal::template('hotel').'/css/room.css');
        $this->add('minibar_product.product_id',new TextType(true,'invalid_product_id',0,255));
		//$this->add('minibar_product.norm_quantity',new FloatType(true,'invalid_norm_quantity','0','100000000000'));
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        @$this->link_js('cache/data/'.strtolower(str_replace('#','',PORTAL_ID)).'/HK_default.js?v='.time());
	}
	
	function draw()
	{
	    $this->map=array();
        $sql = '
			select
                product_price_list.product_id as id,
				product_price_list.product_id as code,
                0 as use_quantity,
                0 as norm_quantity,
				product.name_'.Portal::language().' as name
			from
				product_price_list
                INNER JOIN product on product.id = product_price_list.product_id
			where
                product_price_list.portal_id=\''.PORTAL_ID.'\'
                AND product_price_list.department_code = \'HK\'
                AND product.status = \'avaiable\'
                -- THANH ADD dieu kien lay gia theo khoang thoi gian 
                AND ( (DATE_TO_UNIX(product_price_list.start_date)<=\''.Date_Time::to_orc_date(date('d/m/Y')).'\' AND \''.Date_Time::to_orc_date(date('d/m/Y')).'\'<=product_price_list.end_date) OR (product_price_list.start_date<=\''.Date_Time::to_orc_date(date('d/m/Y')).'\' AND product_price_list.end_date IS NULL ) OR ( \''.Date_Time::to_orc_date(date('d/m/Y')).'\'<=product_price_list.end_date AND product_price_list.start_date IS NULL ) OR ( product_price_list.start_date IS NULL AND product_price_list.end_date IS NULL ))
                -- END
			order by
				product_price_list.product_id	
	   ';
	    $items_product = DB::fetch_all($sql);
        $room_amenities=DB::fetch_all('select * from room_amenities');
       
        $product=DB::fetch_all('select AMENITIES_USED_DETAIL.* from AMENITIES_USED_DETAIL
                                    inner join AMENITIES_USED on AMENITIES_USED.id=AMENITIES_USED_DETAIL.amenities_used_id
                                    where AMENITIES_USED.create_date=\''.Date_Time::to_orc_date(date('d/m/Y')).'\'');
        $this->map['items']=$items_product;
        $rooms=DB::fetch_all('select * from room order by room.name');
        foreach($rooms as $key=>$value){
            $rooms[$key]['product']=$items_product;
        }
        foreach($room_amenities as $key=>$value){
            $rooms[$value['room_id']]['product'][$value['product_id']]['norm_quantity']=$value['norm_quantity'];
        }
        foreach($product as $key=>$value){
            $rooms[$value['room_id']]['product'][$value['product_id']]['use_quantity']=$value['quantity'];
        }
        $this->map['rooms']=$rooms;
        $this->parse_layout('report',$this->map);
	}
}
?>