<?php
class api extends restful_api
{
    function __construct(){
		parent::__construct();
	}
    
    function get_restaurant_bar()
    {
        if ($this->method == 'GET')
        {
    		if(Url::get('secretkey') and Url::get('secretkey') == '9a8fa234b2520e9bb4f59d8178545a62')
            {
                require_once 'packages/hotel/includes/php/hotel.php';
        		require_once 'packages/hotel/packages/restaurant/includes/table.php';
        		$cond_admin = Table::get_privilege_bar();
        		$bars = DB::fetch_all('SELECT * FROM bar ORDER BY ID ASC'); 
                
                $items = array();
                foreach($bars as $key => $value)
                {
                    array_push($items,$value);                  
                }
                //System::debug($items);
                
                $this->response(200, json_encode($items));
                
            }else
            {
                $this->response(500, "FAILED"); // AUTH
            } 
        }
    }
    
    function get_table()
    {
        if ($this->method == 'GET')
        {
    		if(Url::get('secretkey') and Url::get('secretkey') == '9a8fa234b2520e9bb4f59d8178545a62' and Url::get('bar_id'))
            {
                $_REQUEST['in_date'] = date('d/m/Y', time());
                $bar_id = Url::get('bar_id');
                // lấy tên của nhà hàng theo bar_id.
        		$sql_group='
                        SELECT 
                            bar_table.bar_area_id as id
                            ,bar_area.name as name
                        FROM 
                            bar_table 
                            INNER JOIN bar on bar.id=bar_table.bar_id
                            inner join bar_area on bar_area.id=bar_table.bar_area_id
                        WHERE 
                            bar_table.bar_id = '.$bar_id.'
                        ORDER BY 
                            bar_table.bar_area_id
                ';		
        		$groups = DB::fetch_all($sql_group);
                if(Url::get('get_group_bar')==1)
                {
                    $items = array();
                    foreach($groups as $key => $value)
                    {
                        array_push($items,$value);                  
                    }
                    $this->response(200, json_encode($items));
                }
                //System::debug($groups);
                //gán tên nhà hàng cho biến $name_group;
                $name_group = '';
                $portal_id = DB::fetch('SELECT * FROM bar WHERE id = '.$bar_id.' ', 'portal_id'); 
                foreach($groups as $k => $gr)
                {
                    $name_group = $gr['id'];
                    break;
                }
                $cond = '';
                if(Url::get('group'))
                {
                    //$cond .= ' AND bar_table.bar_area_id=\''.Url::get('group').'\'';
                    $name_group = Url::get('group');    
                }else
                {
                    //$cond .= ' AND bar_table.bar_area_id=\''.$name_group.'\'';
                }
                $action = Url::get('action');
                // Lấy danh sách bàn trong nhà hàng. mặc định trạng thái available-> chưa có đặt bàn.
        		$sql = '
    				SELECT
    					bar_table.id
                        ,bar_table.id as table_id
    					,bar_table.code
    					,bar_table.name
    					,bar_table.table_group
    					,bar_table.bar_id
                        ,bar_table.bar_area_id
                        ,bar_area.name as bar_area_name
    					,bar.name as bar_name
    					,\'AVAILABLE\' AS status
                        ,\' \' as room_name
                        ,\' \' as traveller_name
                        ,bar_table.portal_id
                        ,bar.department_id
                        ,bar.full_rate
                        ,bar.full_charge
                        ,bar.discount_after_tax
    				FROM
    					bar_table
    					inner join bar on bar.id=bar_table.bar_id
                        inner join bar_area on bar_area.id=bar_table.bar_area_id
    				WHERE
                        bar_table.portal_id=\''.$portal_id.'\'
                        '.$cond.' 
                        AND bar_table.bar_id = '.$bar_id.'
    				ORDER BY
    					bar_table.bar_area_id, bar_table.name ASC
       			';
                $bar_tables = DB::fetch_all($sql);
                
                $floors = array();
                $last_floor = false;			
                $i = 1;
                // lấy phòng dữ liệu phòng đang book hoặc đang check in trong khoảng thời gian đang xem
                $sql = '
        			SELECT
        				bar_table.id,
        				bar_reservation.id as bar_reservation_id,
                        bar_reservation.agent_name,
        				bar_reservation.time_in,bar_reservation.time_out,
        				bar_reservation.arrival_time,
                        bar_reservation.departure_time,
                        bar_reservation.arrival_time as arr_time,
                        bar_reservation.departure_time as dep_time,
        				bar_reservation.total,
        				bar_reservation.code,
                        bar_reservation.receiver_name,
                        bar_reservation.receiver_phone,
                        bar_reservation.tax_rate,
                        bar_reservation.bar_fee_rate,
                        room.name as room_name,
                        CONCAT(concat(traveller.first_name,\' \'),last_name) as traveller_name,
        				bar_table.name as bar_table_name,
                        bar_table.bar_area_id,
        				bar.name as bar_name,
        				bar_reservation_table.num_people,
                        bar_reservation.package_id,
                        bar_reservation.reservation_room_id,
                        customer.name as customer_name,
                        bar_reservation.full_rate,
                        bar_reservation.full_charge,
                        bar_reservation.discount_after_tax,
                        NVL(bar_reservation.discount, 0) as discount,
                        NVL(bar_reservation.discount_percent, 0) as discount_percent,
        				DECODE(bar_reservation.status,\'CHECKIN\',\'OCCUPIED\',DECODE(bar_reservation.status,\'RESERVATION\',\'BOOKED\',bar_reservation.status)) AS status
        			FROM
        				bar_reservation
        				inner join bar_reservation_table on bar_reservation_table.bar_reservation_id = bar_reservation.id
        				inner join bar_table on bar_table.id = bar_reservation_table.table_id
                        inner join bar_area on bar_area.id=bar_table.bar_area_id
        				inner join bar on bar.id=bar_table.bar_id
                        left join reservation_room on bar_reservation.reservation_room_id = reservation_room.id
                        left join reservation_traveller on bar_reservation.reservation_traveller_id = reservation_traveller.id
                        left join traveller on reservation_traveller.traveller_id = traveller.id
                        left join room on reservation_room.room_id = room.id
                        left join customer on customer.id = bar_reservation.customer_id
        			WHERE
        				bar_reservation.portal_id=\''.$portal_id.'\' AND
        				(
                        (bar_reservation.status = \'CHECKIN\' AND bar_reservation.arrival_time <'.(Date_Time::to_time(date('d/m/Y', time())) + 86400).' '.$cond.') 
                        OR 
                        (bar_reservation.status = \'BOOKED\' AND bar_reservation.arrival_time >='.Date_Time::to_time(date('d/m/Y', time())).' AND bar_reservation.arrival_time <'.(time()+15*60).' '.$cond.')
                         )
        			ORDER BY
        				bar_table.bar_area_id, bar.name ASC
        		';
                $busy_tables = DB::fetch_all($sql);
                //System::debug($busy_tables);
                $bars = DB::fetch_all('SELECT * FROM bar WHERE id = '.$bar_id.' ');
                
                //System::debug($bars);
                foreach($bar_tables as $key=>$value)
        		{
        			$id_booked = '';
        			$floors[$value['bar_area_id']]['id']= $value['bar_id'];
        			$floors[$value['bar_area_id']]['name']= $value['bar_area_id'];
        			$value['class'] = 'AVAILABLE';
        			$value['agent_name'] = '';
        			
        			$value['arrival_time'] = '';
        			$value['departure_time'] = '';
                    $value['arr_time'] = '';
        			$value['dep_time'] = '';
        			$value['num_people'] = '';
                    $value['bar_reservation_id'] = '';
                    $value['tax_rate'] = (RES_TAX_RATE?RES_TAX_RATE:0);
                    $value['bar_fee_rate'] = (RES_SERVICE_CHARGE?RES_SERVICE_CHARGE:0);
                    $value['discount'] = 0;
        			$value['discount_percent'] = 0;
                    $value['portal_id'] = str_replace('#','',$value['portal_id']);
        			if(isset($busy_tables[$key]))
                    {
                        if(isset($busy_tables[$key]['reservation_room_id']))
                        {
                            $value['receiver_name']=$busy_tables[$key]['receiver_name']?$busy_tables[$key]['receiver_name']:'';
                            $value['receiver_phone']=$busy_tables[$key]['receiver_phone'];
                            $value['customer_name']=$busy_tables[$key]['customer_name'];
                        }
                        if(isset($busy_tables[$key]['bar_reservation_id']))
                        {
                            $value['bar_reservation_id']=$busy_tables[$key]['bar_reservation_id'];
                        }
                        if(intval($busy_tables[$key]['total'])<0)
                        {
                            $value['total'] =0;
                        }else
                        {
                            $value['total'] = System::display_number($busy_tables[$key]['total']);
                        }
        				$value['code'] = $busy_tables[$key]['code'];
        				$value['date_in'] = date('d/m/Y',$busy_tables[$key]['time_in']?$busy_tables[$key]['time_in']:$busy_tables[$key]['arrival_time']);
                        $value['status'] = $this->get_status_checkin_over($busy_tables[$key]['status'],$busy_tables[$key]['departure_time']) ;
                        if($value['status']!='AVAILABLE')
                        {
                            if($this->checkdate_arrival_departure($busy_tables[$key]['arrival_time'],$busy_tables[$key]['departure_time']))
                            {
                                //cung ngay thang
                                if($value['status']=='OVERCHECKIN')
                                    $value['arrival_time'] = date('d/m H:i',$busy_tables[$key]['arrival_time']);
                                else 
                                    $value['arrival_time'] = date('H:i',$busy_tables[$key]['arrival_time']);
            				    $value['departure_time'] = date('H:i',$busy_tables[$key]['departure_time']);
                            }else
                            {
                                //khac ngay thang
                                $value['arrival_time'] = date('d/m H:i',$busy_tables[$key]['arrival_time']);
            				    $value['departure_time'] = date('d/m H:i',$busy_tables[$key]['departure_time']);
                            }
                            $value['agent_name'] = $busy_tables[$key]['agent_name'];
                            $value['room_name'] = $busy_tables[$key]['room_name'];
                            $value['traveller_name'] = $busy_tables[$key]['traveller_name'];
            				$value['num_people'] = $busy_tables[$key]['num_people'].' '.Portal::language('people');
                        }else
                        {
                            $value['agent_name'] ='';
                            $value['room_name'] = '';
                            $value['traveller_name'] = '';
                        }
                        $value['arr_time'] = $busy_tables[$key]['arr_time'];
        			    $value['dep_time'] = $busy_tables[$key]['dep_time'];
                        $value['full_rate'] = $busy_tables[$key]['full_rate'];
            			$value['full_charge'] = $busy_tables[$key]['full_charge'];
            			$value['discount_after_tax'] = $busy_tables[$key]['discount_after_tax'];
                        $value['tax_rate'] = $busy_tables[$key]['tax_rate'];
                        $value['bar_fee_rate'] = $busy_tables[$key]['bar_fee_rate'];
                        $value['discount'] = $busy_tables[$key]['discount'];
                        $value['discount_percent'] = $busy_tables[$key]['discount_percent'];
        			}else
        			{
        				$value['total'] = '';
        			}
        			if($value['status'])
                    {
        				$value['class'] = $value['status'];	
        			}
        			$floors[$value['bar_area_id']]['bar_tables'][$i]= $value;                    
        			$floors[$value['bar_area_id']]['bar_id'] = $value['bar_id'];
        			$i++;
        		}
                $items = array();
                //System::debug($floors);
                foreach($floors as $key => $value)
                {
                    foreach($value['bar_tables'] as $k => $v)
                    {
                        if($action == 0)
                        {
                            array_push($items, $v);
                        }else if($action == 1)
                        {
                            if($v['status'] == 'BOOKED' || $v['status']== 'OCCUPIED' || $v['status']== 'OVERCHECKIN')
                            {
                                array_push($items, $v);
                            }
                        }else if($action == 2)
                        {
                            if($v['status'] == 'AVAILABLE')
                            {
                                array_push($items, $v);
                            }
                        }             
                    }
                }
                
                $this->response(200, json_encode($items));
            }else
            {
                $this->response(500, "FAILED"); // AUTH
            } 
        }
    }
    
    function checkdate_arrival_departure($arrival_time,$departure_time)
    {
        if(date('d/m/Y',$arrival_time)==date('d/m/Y',$departure_time))
        {
            return true;
        }else
        {
            return false;
        }
    }
    function get_status_checkin_over($status,$departure_time)
    {
        if($status=='OCCUPIED' || $status=='BOOKED')
        {
            $now = $_REQUEST['in_date'];
            $str_now = explode("/",$now);
            
            $date_now = mktime(0,0,0,$str_now[1],$str_now[0],$str_now[2]);
            $departure = mktime(0,0,0,date('m',$departure_time),date('d',$departure_time),date('Y',$departure_time));
            if($status=='OCCUPIED')
            {
                if($date_now>$departure)
                {
                    return 'OVERCHECKIN';
                }
            }
            if($status=='BOOKED')//neu book qua han se khong duoc hien thi 
            {
                //AVAILABLE
                if($date_now>$departure)
                {
                    return 'AVAILABLE';
                }
            }
        }
        return $status;
    }
    
    function get_product_category()
    {
        if ($this->method == 'GET')
        {
    		if(Url::get('secretkey') and Url::get('secretkey') == '9a8fa234b2520e9bb4f59d8178545a62' && Url::get('bar_id') && Url::get('portal_id'))
            {
                $bar_id = Url::get('bar_id');
                $portal_id = '#'.Url::get('portal_id');
                
        		$dp_code = DB::fetch('select department_id as code from bar where id='.$bar_id.' and portal_id=\''.$portal_id.'\'','code');	
        		$structure_id_drink = DB::fetch('select structure_id from product_category where code=\'DU\'','structure_id');
        		$structure_id_service = DB::fetch('select structure_id from product_category where code=\'DVNH\'','structure_id');
                //$structure_other_id_service = DB::fetch('select structure_id from product_category where code=\'OT\'','structure_id');
        		$sql = 'SELECT
        					product_category.id
        					,product_category.name
                            ,\'DRINK_SERVICE\' as code
        					,product_category.structure_id
        				FROM
        					product_category
        					INNER JOIN product ON product_category.id = product.category_id
        					INNER JOIN product_price_list ON product_price_list.product_id = product.id
        					INNER JOIN unit ON unit.id = product.unit_id
        				WHERE 
        					1>0 
        					AND ((product_category.structure_id > '.$structure_id_drink.' AND  product_category.structure_id < '.IDStructure::next($structure_id_drink).')
        					OR (product_category.structure_id > '.$structure_id_service.' AND  product_category.structure_id < '.IDStructure::next($structure_id_service).'))
        					AND product_price_list.portal_id = \''.$portal_id.'\'
        					AND product_price_list.department_code=\''.$dp_code.'\' 
                            AND (product_price_list.end_date is null OR product_price_list.end_date>=\''.Date_Time::to_orc_date(date('d/m/y')).'\')
        				ORDER BY product_category.position';	
        		$categories = DB::fetch_all($sql);
                
        		$structure_id_food = DB::fetch('select structure_id from product_category where code=\'DA\'','structure_id');
        		$sql = 'SELECT
        					product_category.id
        					,product_category.name
                            ,\'FOOD\' as code
        					,product_category.structure_id
        				FROM
        					product_category
        					INNER JOIN product ON product_category.id = product.category_id
        					INNER JOIN product_price_list ON product_price_list.product_id = product.id
        					INNER JOIN unit ON unit.id = product.unit_id
        				WHERE 
        					1>0 
        					AND (product_category.structure_id > '.$structure_id_food.' AND  product_category.structure_id < '.IDStructure::next($structure_id_food).')
        					AND product_price_list.portal_id = \''.$portal_id.'\'
        					AND product_price_list.department_code=\''.$dp_code.'\' 
                            AND (product_price_list.end_date is null OR product_price_list.end_date>=\''.Date_Time::to_orc_date(date('d/m/y')).'\')
                            AND product_category.code!=\'SETMENU\'
        				ORDER BY product_category.position';	
        		$categories += DB::fetch_all($sql);
                //System::debug($categories);
                
                $items = array();
                foreach($categories as $key => $value)
                {
                    array_push($items, $value);
                }
                
                $this->response(200, json_encode($items));
                
            }else
            {
                $this->response(500, "FAILED"); // AUTH
            } 
        }
    }
    
    function get_product_price_list()
    {
        if ($this->method == 'GET')
        {
            if(Url::get('secretkey') and Url::get('secretkey') == '9a8fa234b2520e9bb4f59d8178545a62' && Url::get('category_id') && Url::get('bar_id') && Url::get('type') && Url::get('department_id') && Url::get('portal_id'))
            {
                if(Url::get('type')=='CATEGORY')
                {
        			$category_id = Url::get('category_id');
        			$cond = ''.IDStructure::child_cond(DB::structure_id('product_category',$category_id)).'';
                    $sql = '
        				SELECT
        					product_price_list.id as id,
                            product.id as code,
                            product.name_'.Portal::language().' as name,
        					product.name_2,
        					product_price_list.price,
                            product_price_list.price as price_format,  
                            unit.name_'.Portal::language().' as unit,
        					pc.structure_id,
                            pc.name AS pc_name,
                            product.category_id,
                            product.unit_id,
                            pc.code as category_code
        				FROM 
        					product_price_list
        					INNER JOIN product ON product_price_list.product_id = product.id
        					INNER JOIN product_category pc ON pc.id = product.category_id
        					INNER JOIN unit ON unit.id = product.unit_id
        				WHERE 
        					('.$cond.')
                            AND product_price_list.department_code = \''.Url::get('department_id').'\'
        					AND (product.type = \'GOODS\' OR product.type = \'PRODUCT\' OR product.type = \'DRINK\'  OR product.type = \'SERVICE\')
        					AND product_price_list.portal_id = \'#'.Url::get('portal_id').'\'
        					AND (product_price_list.end_date is null OR product_price_list.end_date>=\''.Date_Time::to_orc_date(date('d/m/y')).'\')
        				ORDER BY
        					product.name_'.Portal::language().'
                    ';
                    //System::debug($sql);  
                    $product_price_list = DB::fetch_all($sql);
        		}else if(Url::get('type')=='SET')
                {
                    $cond_set = '1=1';
                    $cond_set .= ' AND (bar_set_menu.department_code = \'RES\' OR bar_set_menu.department_code = \''.Url::get('department_id').'\')';
                    $sql = '
                            SELECT 
                                product_price_list.id as id,
                                bar_set_menu.id as bar_set_menu_id,
                                bar_set_menu.code as code,
                                bar_set_menu.name as name,
                                product_price_list.price as price,
                                product_price_list.price as price_format,
                                product_price_list.start_date,
                                product_price_list.end_date,
                                \'set\' as unit,
                                product.unit_id
                            FROM 
                                bar_set_menu
                                INNER JOIN product_price_list ON bar_set_menu.code = product_price_list.product_id AND product_price_list.department_code = bar_set_menu.department_code
                                INNER JOIN product ON product_price_list.product_id = product.id
                            WHERE 
                                '.$cond_set.' 
                                AND bar_set_menu.portal_id=\'#'.Url::get('portal_id').'\'
                                AND ( (DATE_TO_UNIX(product_price_list.start_date)<=\''.Date_Time::to_time(date("d/m/Y")).'\' AND \''.Date_Time::to_time(date("d/m/Y")).'\' <=DATE_TO_UNIX(product_price_list.end_date)) OR (DATE_TO_UNIX(product_price_list.start_date)<='.Date_Time::to_time(date("d/m/Y")).' AND product_price_list.end_date IS NULL ) OR ( '.Date_Time::to_time(date("d/m/Y")).'<=DATE_TO_UNIX(product_price_list.end_date) AND product_price_list.start_date IS NULL ) OR ( product_price_list.start_date IS NULL AND product_price_list.end_date IS NULL ))
                            ORDER BY bar_set_menu.code
                    ';
                    
                    $product_price_list = DB::fetch_all($sql);
                    $j = 1;
                    $key = array_keys($product_price_list);
                    $total = 0;
                    reset($product_price_list); 
                    for($i=0 ; $i< count($product_price_list); $i++)
                    { 
                        $current = current($product_price_list);
                        $next = next($product_price_list);
                        if(!empty($next))
                        {
                            $arr_temp = explode("-",$next['code']);
                        }
                        if(!empty($next) && strpos($next['code'],$current['code'])>=0 && isset($arr_temp[1]) && strlen($arr_temp[1])==5)
                        {
                            $j++;
                        }else
                        {
                        if(isset($key[$i-$j+1]))
                        {
                            $product_price_list[$key[$i-$j+1]]['count'] = $j;
                            $j = 1;
                        }
                        
                        }
                    }
                    foreach($product_price_list as $key=>$value)
                    {
                        if(!isset($value['count']))
                        {
                            unset($product_price_list[$key]);
                        }
                        else if($value['count']==1)
                        {
                            if(empty($value['start_date']))
                            {
                                $value['start_date'] = '01-JUN-1970';  
                            }
                            if(empty($value['end_date']))
                            {
                                $value['end_date'] = '01-JUN-2030';  
                            }
                            $start_time = Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['start_date'],"/"));
                            $end_time = Date_Time::to_time(Date_Time::convert_orc_date_to_date($value['end_date'],"/"));
                            $current_time = Date_Time::to_time(date("d/m/Y"));
                            if(!($start_time<=$current_time && $current_time<=$end_time))
                            {
                                unset($product_price_list[$key]);
                            }
                        }
                    }                  
                }
                //System::debug($product_price_list);
                $items = array();
                foreach($product_price_list as $key => $value)
                {
                    $value['price_format'] = System::display_number($value['price_format']);
                    array_push($items, $value);
                }
                //System::debug($items); 
                $this->response(200, json_encode($items));                 
            }else
            {
                $this->response(500, "FAILED"); // AUTH
            }
        }
    }
    
    function get_bar_reservation_product()
    {
        if ($this->method == 'GET')
        {
    		if(Url::get('secretkey') and Url::get('secretkey') == '9a8fa234b2520e9bb4f59d8178545a62' && Url::get('id'))
            {
                $sql = '
                    SELECT 
                        brp.id || \'-\' || brp.bar_set_menu_id as id
                        ,brp.id as brp_id
                        ,brp.bar_set_menu_id as bar_set_menu_id
                        ,DECODE(brp.name,null,product.name_'.Portal::language().',brp.name) as product_name
                        ,unit.name_'.Portal::language().' as unit
                        ,brp.quantity
                        ,brp.price
                        ,brp.price as price_format
                        ,((brp.price * (brp.quantity - brp.quantity_discount- brp.quantity_cancel - brp.remain)) - (brp.price * (brp.quantity - brp.quantity_discount- brp.quantity_cancel - brp.remain)*brp.discount_category/100) - ((brp.price * (brp.quantity - brp.quantity_discount- brp.quantity_cancel - brp.remain)*brp.discount_category/100)*brp.discount_rate/100)) as amount
                        ,brp.product_id
                        ,brp.quantity_discount as promotion, brp.discount_rate as percentage, brp.printed as printed,brp.unit_id
                        ,brp.remain,brp.status,brp.note,brp.price_id,brp.quantity_cancel,brp.discount_category
                        ,brp.quantity_discount,brp.discount_rate,brp.product_sign,brp.bar_id
                        ,brp.stt,brp.stt_order
                        ,brp.complete
                    FROM 
                        bar_reservation_product brp
                        LEFT OUTER JOIN bar_reservation ON bar_reservation.id = brp.bar_reservation_id
                        LEFT OUTER JOIN product ON product.id = brp.product_id
                        LEFT OUTER JOIN product_price_list ON product_price_list.product_id = product.id
                        LEFT OUTER JOIN unit ON unit.id = product.unit_id
                    WHERE
                        brp.bar_reservation_id = '.Url::get('id').' AND brp.bar_set_menu_id IS NULL
                    ORDER BY
                        brp.stt ASC
                ';     
                $original_reservation_products = DB::fetch_all($sql);
                $i = 0; 
                $arr = array();
    			$bar_set_menu_id = 0;
                $bar_set_menu_id_temp = 0;
                //System::debug($original_reservation_products);
    			foreach($original_reservation_products as $k =>$valu)
                {
    				$bar_set_menu_product_quantity = 1;
                    if(isset($valu['bar_set_menu_product_quantity'])){
                        $valu['price'] *= $valu['bar_set_menu_product_quantity'];
                        $valu['quantity'] = $valu['quantity']/$valu['bar_set_menu_product_quantity'];
                        $valu['promotion'] = $valu['promotion']/$valu['bar_set_menu_product_quantity'];
                        $valu['remain'] = $valu['remain']/$valu['bar_set_menu_product_quantity'];
                        $valu['quantity_cancel'] = $valu['quantity_cancel']/$valu['bar_set_menu_product_quantity'];
                    }
                    //if(isset($arr[$valu['price_id']]) && $valu['product_id']!='FOUTSIDE' && $valu['product_id']!='DOUTSIDE' && $valu['product_id']!='SOUTSIDE')
                    if(isset($arr[$valu['price_id']]) || (!empty($valu['bar_set_menu_id']) && $valu['bar_set_menu_id']==$bar_set_menu_id_temp))
                    {
    					if(!empty($valu['bar_set_menu_id']) && $valu['bar_set_menu_id']==$bar_set_menu_id_temp){
                                $arr[$bar_set_menu_id]['price'] += $valu['price'];
        						$price = $valu['price'];
        						$amount = ($valu['price'] * ($valu['quantity'] - $valu['quantity_discount']));
        						$arr[$bar_set_menu_id]['amount'] += ($amount - $amount*$valu['discount_category']/100 - (($amount - $amount*$valu['discount_category']/100)*$valu['discount_rate']/100));
    					}
                        else if(isset($arr[$valu['price_id']])){
                            $amount = ($valu['price'] * ($valu['quantity'] - $valu['quantity_discount']));
        					$arr[$valu['price_id']]['amount'] += ($amount - $amount*$valu['discount_category']/100 - (($amount - $amount*$valu['discount_category']/100)*$valu['discount_rate']/100));
        					$arr[$valu['price_id']]['quantity'] += $valu['quantity'];
        					$arr[$valu['price_id']]['promotion'] += $valu['promotion'];
        					$arr[$valu['price_id']]['remain'] += $valu['remain'];
        					$arr[$valu['price_id']]['quantity_cancel'] += $valu['quantity_cancel'];
    				    }    
                    }
                    else
                    {
    					if($valu['product_id']=='FOUTSIDE' || $valu['product_id']=='DOUTSIDE' || $valu['product_id']=='SOUTSIDE')
                        {
    						$arr[$valu['price_id'].'_'.$valu['id']]['id'] = $valu['price_id'].'_'.$valu['id'];
    						$arr[$valu['price_id'].'_'.$valu['id']]['name'] = $valu['product_name'];
    						$arr[$valu['price_id'].'_'.$valu['id']] += $valu;
    						$arr[$valu['price_id'].'_'.$valu['id']]['price'] = $valu['price'];
                            $arr[$valu['price_id'].'_'.$valu['id']]['price_format'] = System::display_number($valu['price']);
    						$arr[$valu['price_id'].'_'.$valu['id']]['brp_id'] = $valu['id'];
                            $arr[$valu['price_id'].'_'.$valu['id']]['bar_reservation_product_id'] = $valu['brp_id'];
                            $i++;
                            $arr[$valu['price_id'].'_'.$valu['id']]['stt'] = $i;
    						$price = $valu['price'];
    						$amount = ($valu['price'] * ($valu['quantity'] - $valu['quantity_discount']));
    						$arr[$valu['price_id'].'_'.$valu['id']]['amount'] = ($amount - $amount*$valu['discount_category']/100 - (($amount - $amount*$valu['discount_category']/100)*$valu['discount_rate']/100));
    					}
                        else
                        {
    						if(!empty($valu['bar_set_menu_id'])){
    						    if($valu['bar_set_menu_id']!=$bar_set_menu_id_temp){
    						        $bar_set_menu_id_temp = $valu['bar_set_menu_id'];
                                    $sql = "SELECT * FROM bar_set_menu WHERE id = ".$valu['bar_set_menu_id'];
                                    $result = DB::fetch($sql);
                                    //System::debug($result);
                                    $bar_set_menu_id = $result['id']."-".$result['code'];
                                    $arr[$bar_set_menu_id] = $valu;
                                    $i++;
                                    $arr[$bar_set_menu_id]['stt'] = $i;
                                    $arr[$bar_set_menu_id]['id'] = $bar_set_menu_id;
                                    $arr[$bar_set_menu_id]['product_id'] = $bar_set_menu_id;
                                    $arr[$bar_set_menu_id]['name'] = $result['name'];
                                    $arr[$bar_set_menu_id]['unit'] = 'set';
            						$arr[$bar_set_menu_id]['product_name'] = $result['name'];   						
            						$arr[$bar_set_menu_id]['price'] = $valu['price'];
                                    $arr[$bar_set_menu_id]['price_format'] = System::display_number($valu['price']);
            						$arr[$bar_set_menu_id]['brp_id'] = $valu['id'];
                                    $arr[$bar_set_menu_id]['bar_reservation_product_id'] = $valu['brp_id'];
            						$price = $valu['price'];
            						$amount = ($valu['price'] * ($valu['quantity'] - $valu['quantity_discount']));
            						$arr[$bar_set_menu_id]['amount'] = ($amount - $amount*$valu['discount_category']/100 - (($amount - $amount*$valu['discount_category']/100)*$valu['discount_rate']/100));
    						    }
                            }
                            else{
                                $arr[$valu['price_id']]['id'] = $valu['price_id'];
        						$arr[$valu['price_id']]['name'] = $valu['product_name'];
        						$arr[$valu['price_id']] += $valu;
                                $i++;
                                $arr[$valu['price_id']]['stt'] = $i;
        						$arr[$valu['price_id']]['price'] = $valu['price'];
                                $arr[$valu['price_id']]['price_format'] = System::display_number($valu['price']);
                                $arr[$valu['price_id']]['unit'] = $valu['unit'];
        						$arr[$valu['price_id']]['brp_id'] = $valu['id'];
                                $arr[$valu['price_id']]['bar_reservation_product_id'] = $valu['brp_id'];
        						$price = $valu['price'];
        						$amount = ($valu['price'] * ($valu['quantity'] - $valu['quantity_discount']));
        						$arr[$valu['price_id']]['amount'] = ($amount - $amount*$valu['discount_category']/100 - (($amount - $amount*$valu['discount_category']/100)*$valu['discount_rate']/100));
    					    }
                        }
    					unset($valu['id']);
    				}
    			} 
                //System::debug($arr);
                $items = array();
                foreach($arr as $key => $value)
                {
                    $value['amount'] = System::display_number($value['amount']);
                    array_push($items, $value);
                }
                
                $this->response(200, json_encode($items));    
                
            }else
            {
                $this->response(500, "FAILED"); // AUTH
            }
        }
    }
}   
$api = new api();
?>