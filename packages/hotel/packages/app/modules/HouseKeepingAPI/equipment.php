<?php
class api extends restful_api
{
    function __construct(){
		parent::__construct();
	}
    
    function get_product()
    {
        if($this->method == 'GET' || $this->method == 'POST')
        {
    		if(Url::get('secretkey') and Url::get('secretkey') == '9a8fa234b2520e9bb4f59d8178545a62')
            {
                $rooms = DB::fetch_all('
        			select
        				room.*,RESERVATION_ROOM.id as RESERVATION_ROOM_ID
        			from
        				room
        				inner join reservation_room on reservation_room.room_id = room.id
        				inner join room_status on room_status.reservation_room_id  =  RESERVATION_ROOM.id
        			where
        				reservation_room.status = \'CHECKIN\'
        				and room_status.status = \'OCCUPIED\' and room.portal_id=\'#'.Url::get('portal_id').'\'
        				and room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
        			order by
        				room.name
        		');
        		if($room_id = Url::iget('room_id') or (Url::get('reservation_room_id') and $room_id = DB::fetch('select room_id from reservation_room where id = '.Url::iget('reservation_room_id').'','room_id')))
        		{
        			$sql = 'select 
        						product.*, 
                                product.id as code, 
                                product_price_list.price, 
                                product.name_'.Portal::language().' as name,
                                housekeeping_equipment.quantity - housekeeping_equipment.damaged_quantity as quantity  
        					from 	
                                product_price_list
        						INNER JOIN product ON product_price_list.product_id = product.id
        						inner join housekeeping_equipment on housekeeping_equipment.product_id = product.id AND housekeeping_equipment.portal_id=\''.PORTAL_ID.'\'
                                INNER JOIN unit ON unit.id = product.unit_id
                                inner join product_category on product.category_id = product_category.id
        					where
        						housekeeping_equipment.room_id = \''.$room_id.'\'
                                AND product_price_list.portal_id = \''.PORTAL_ID.'\'
                                --giap.ln hien thi ra nhung product trong khoang start_date, end_date
                                AND (product_price_list.start_date is null OR (product_price_list.start_date is not null AND product_price_list.start_date<=\''.Date_Time::convert_time_to_ora_date(time()).'\'))
                                AND (product_price_list.end_date is null OR (product_price_list.end_date is not null AND product_price_list.end_date>=\''.Date_Time::convert_time_to_ora_date(time()).'\'))
                                --end giap.ln
                            ORDER BY     
                                product.name_'.Portal::language().' ASC
        				';
        			$product_arr = DB::fetch_all($sql);
        		}
                //System::debug($product_arr);
                $items = array();
                foreach($product_arr as $key => $value)
                {
                    $value['price'] = System::display_number($value['price']);
                    array_push($items, $value);
                }
                 
                if(Url::get('type') == "IOS")
                {
                    $this->response(200, json_encode($product_arr));
                }else
                {
                    $this->response(200, json_encode($items));
                } 
            }else
            {
                $this->response(500, "FAILED"); // AUTH
            } 
        }
    }
}   
$api = new api();
?>