<?php
class api extends restful_api
{
    function __construct(){
		parent::__construct();
	}
    
    function get_category ()
    {
        if($this->method == 'GET')
        {
    		if(Url::get('secretkey') and Url::get('secretkey') == '9a8fa234b2520e9bb4f59d8178545a62')
            {
                $categories = array();
                $sql = '
        				SELECT 
                            id, 
        					code, 
                            name,
                            name_en, 
                            id as category, 
                            structure_id
        				FROM
        					product_category
        				WHERE
        					'.IDStructure::direct_child_cond(DB::fetch('SELECT id, structure_id FROM product_category WHERE code = \'GL\'','structure_id')).'
        				ORDER BY
        					structure_id
        				';
        		$categories = DB::fetch_all($sql);
                //System::debug($categories);
                $items = array();
                foreach($categories as $key => $value)
                {
                    array_push($items, $value);
                }
                 
                if(Url::get('type') == "IOS")
                {
                    $this->response(200, json_encode($categories));
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
    
    function get_product()
    {
        if($this->method == 'GET' || $this->method == 'POST')
        {
    		if(Url::get('secretkey') and Url::get('secretkey') == '9a8fa234b2520e9bb4f59d8178545a62')
            {
                $r_r_id = Url::get('reservation_room_id');
                $portal_id = '#'.Url::get('portal_id');
                
                if(!($category = DB::select('product_category','code=\'GL\'')))
        		{
        			$category = array();
        		}
                $categories = array();
        		$sql = '
        				SELECT 
        					code, 
                            code as id, 
                            name,
                            name_en, 
                            id as category, 
                            structure_id
        				FROM
        					product_category
        				WHERE
        					'.IDStructure::direct_child_cond(DB::fetch('SELECT id, structure_id FROM product_category WHERE code = \'GL\'','structure_id')).'
        				ORDER BY
        					structure_id
        				';
        		$categories = DB::fetch_all($sql);
                $cond = '';
                if(Url::get('category_id'))
                {
                    $sql = '
        				SELECT 
        					code, 
                            code as id, 
                            name,
                            name_en, 
                            id as category, 
                            structure_id
        				FROM
        					product_category
        				WHERE
        					product_category.id = \''.Url::get('category_id').'\'
        				ORDER BY
        					structure_id
        				';
                    $categories = DB::fetch_all($sql);
                    $structure_id = DB::fetch('SELECT * FROM product_category WHERE id=\''.Url::get('category_id').'\' ', 'structure_id');
                    $cond .= ' AND structure_id= \''.$structure_id.'\' ';
                }else
                {
                    $cond .=  'AND '.IDStructure::child_cond($category['structure_id']).' ';
                }
                //System::debug($categories);
                
                $products = array();
                
                $sql = '
        			SELECT 
        				product_price_list.product_id as id, 
                        product.name_'.Portal::language().' as name, 
                        product_price_list.price,
                        product_category.code, 
                        product_price_list.status
        			FROM
        				product_price_list
                        inner join product on product_price_list.product_id = product.id
        				inner join product_category on product.category_id = product_category.id
        			WHERE
        				product.type=\'SERVICE\' 
                        '.$cond.'
                        AND product_price_list.department_code = \'LAUNDRY\'
                        AND product_price_list.status = \'avaiable\'
                        AND product_price_list.portal_id=\''.$portal_id.'\'
                        AND (product_price_list.start_date is null OR (product_price_list.start_date is not null AND product_price_list.start_date<=\''.Date_Time::convert_time_to_ora_date(time()).'\'))
                        AND (product_price_list.end_date is null OR (product_price_list.end_date is not null AND product_price_list.end_date>=\''.Date_Time::convert_time_to_ora_date(time()).'\'))
                        AND product_price_list.price > 0
        			ORDER BY
        				product_price_list.order_number,
                        UPPER(FN_CONVERT_TO_VN(product.name_'.Portal::language().')),
                        product_price_list.id asc
        		';
      		    
                $products = DB::fetch_all($sql);
        		$items = array();
                
        		foreach($products as $key => $value)
        		{
        			$newkey = substr($key,0,strlen($key)-1);
        			$items[$newkey]['product_key'] = $newkey;
        			$items[$newkey]['product_name'] = $value['name'];
        			$items[$newkey]['child'][$value['code']]['product'] = $key;
                    $items[$newkey]['child'][$value['code']]['product_name'] = $value['name'];
        			$items[$newkey]['child'][$value['code']]['price'] = System::display_number($value['price']);
        			$items[$newkey]['child'][$value['code']]['quantity'] = 0;
                    $items[$newkey]['child'][$value['code']]['code'] = $value['code'];
        		}
        		foreach($categories as $c=>$k)
        		{
        			foreach($items as $key=>$value)
        			{
        				if(!isset($value['child'][$k['code']]))
        				{
        					$items[$key]['child'][$k['code']] = array();
        				}else
        				{
        					unset($items[$key]['child'][$k['code']]);
        					$items[$key]['child'][$k['code']] = $value['child'][$k['code']];
        				}
        			}
        		}
                //System::debug($items);
                foreach($items as $key => $value)
                {
                    foreach($value['child'] as $k => $v)
                    {
                        //System::debug($k);
                        if(empty($items[$key]['child'][$k]))
                        {
                            unset($items[$key]['child'][$k]);
                        }
                    }
                }
                //System::debug($items);
                
                $items_arr = array();
                foreach($items as $key => $value)
                {
                    array_push($items_arr, $value);
                }
                if(Url::get('type') == "IOS")
                {
                    $this->response(200, json_encode($items));
                }else
                {
                    $this->response(200, json_encode($items_arr));
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