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
                $r_r_id = Url::get('reservation_room_id');
                $portal_id = '#'.Url::get('portal_id');
                
                $this->map['unlimited'] = MINIBAR_IMPORT_UNLIMIT;
        		if($this->map['unlimited'])
        		{
        			$field = 'norm_quantity';
        		}else
                {
        			$field = 'norm_quantity';
        		}
                
                $minibar_id = $this->get_minibar_id($r_r_id);
                
                $product_arr = DB::fetch_all('
                            			SELECT
                            				minibar_product.product_id as id,
                            				'.$field.' as norm_quantity,
                            				0 as quantity,
                            				product.name_'.Portal::language().' as name,
                            				product_price_list.price
                            			FROM
                            				minibar_product
                            				INNER JOIN product ON minibar_product.product_id = product.id
                            				INNER JOIN product_price_list ON product_price_list.product_id=product.id and product_price_list.department_code=\'MINIBAR\'
                            			WHERE
                            				minibar_product.minibar_id = \''.$minibar_id.'\' 
                                            AND minibar_product.portal_id = \''.$portal_id.'\'
                                            AND (product_price_list.start_date is null OR (product_price_list.start_date is not null AND product_price_list.start_date<=\''.Date_Time::convert_time_to_ora_date(time()).'\'))
                                            AND (product_price_list.end_date is null OR (product_price_list.end_date is not null AND product_price_list.end_date>=\''.Date_Time::convert_time_to_ora_date(time()).'\'))
                            			ORDER BY 
                            				UPPER(FN_CONVERT_TO_VN(product.name_'.Portal::language().'))
                ');
                
                //System::debug($product_arr);
                $items = array();
                $i=1;
        		foreach($product_arr as $key => $value)
        		{
        			$value['no'] = $i++;
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
    
    function get_minibar_id($r_r_id)
    {
		return DB::fetch('select minibar.id from minibar inner join room on room.id = minibar.room_id inner join reservation_room on reservation_room.room_id = room.id where reservation_room.id = '.$r_r_id.'','id');
	}
}   
$api = new api();
?>