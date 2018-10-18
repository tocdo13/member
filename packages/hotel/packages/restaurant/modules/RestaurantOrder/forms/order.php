<?php
class RestaurantOrderForm extends Form
{
	function RestaurantOrderForm()
	{
		Form::Form('RestaurantOrderForm');
		$this->link_css('skins/default/bootstrap/css/bootstrap.min.css');		
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		
        $this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');
        $this->link_js('skins/default/bootstrap/js/bootstrap.js'); 
		
	}
	function on_submit(){
	}
	function draw()
	{
	   $start_time = Date_Time::to_time(Date('d/m/Y'));
       $end_time = Date_Time::to_time(Date('d/m/Y')) + 24*3600;
	   $sql = "SELECT 
                a.id as id,
                a.quantity,
                a.complete,
                name_".Portal::language()." as name, 
                c.table_id, 
                e.name as table_name,
                b.id as bar_reservation_id,
                f.code
                FROM bar_reservation_product a 
                INNER JOIN bar_reservation b ON a.bar_reservation_id=b.id
                INNER JOIN bar_reservation_table c ON b.id=c.bar_reservation_id
                INNER JOIN product d ON d.id = a.product_id
                INNER JOIN bar_table e ON c.table_id = e.id
                INNER JOIN product_category f ON f.id = d.category_id
                WHERE b.status='CHECKIN' AND e.portal_id='".PORTAL_ID."' AND b.time_in>=".$start_time." AND b.time_in<=".$end_time." ORDER BY b.arrival_time DESC,b.id,b.time_in, a.id";
        $items = DB::fetch_all($sql); 
        
         
        
        $sql = "SELECT * FROM bar_reservation INNER JOIN bar_reservation_table ON bar_reservation.id=bar_reservation_table.bar_reservation_id INNER JOIN bar_table ON bar_table.id=bar_reservation_table.table_id WHERE bar_reservation.status='CHECKIN' AND bar_table.portal_id='".PORTAL_ID."' AND bar_reservation.time_in>=".$start_time." AND bar_reservation.time_in<=".$end_time;
        $result = DB::fetch_all($sql);
        $arr = array();
        foreach($result as $key=>$value){
            $sql = "SELECT bar_reservation_table.id as id, bar_table.name FROM bar_reservation_table INNER JOIN bar_table ON bar_reservation_table.table_id = bar_table.id WHERE bar_reservation_id=".$value['bar_reservation_id'];
            $temp = DB::fetch_all($sql);
            $table_name = "";
            if(count($temp)>1){
                foreach($temp as $k=>$v){
                    $table_name.= $v['name']."+";
                }
                $table_name=substr($table_name,0,strlen($table_name)-1);
                $arr[$value['bar_reservation_id']]['id'] = $value['bar_reservation_id'];
                $arr[$value['bar_reservation_id']]['name'] = $table_name;
            }
        }
        $food_categories = $this->get_list_food_category();
        //System::debug($food_categories);
        foreach($items as $key=>$value){
            foreach($arr as $k=>$v){
                if($value['bar_reservation_id']==$v['id']){
                   $items[$key]['table_name']=$v['name']; 
                }
            }
            foreach($food_categories as $k=>$v){
                if($value['code'] == $v['code']){
                    $items[$key]['type'] = 'cooking';
                    break;
                }
                else{
                    $items[$key]['type'] = 'bar';
                }
            }
        } 
        
        //$categories = $this->get_list_other_category();
        
        //$this->map['items_complete'] = $items_complete;
        $this->map['items'] =  $items;  
        //System::debug($this->map);  
		$this->parse_layout('order', $this->map);
	}
    static function get_list_food_category(){
		$dp_code = DB::fetch('select department_id as code from bar where portal_id=\''.PORTAL_ID.'\'','code');	
		$structure_id_food = DB::fetch('select structure_id from product_category where code=\'DA\'','structure_id');
		$sql = 'SELECT
					product_category.id
					,product_category.name
					,product_category.structure_id
                    ,product_category.code
				FROM
					product_category
					INNER JOIN product ON product_category.id = product.category_id
					INNER JOIN product_price_list ON product_price_list.product_id = product.id
					INNER JOIN unit ON unit.id = product.unit_id
				WHERE 
					1>0 
					AND (product_category.structure_id > '.$structure_id_food.' AND  product_category.structure_id < '.IDStructure::next($structure_id_food).')
					AND product_price_list.portal_id = \''.PORTAL_ID.'\'
					AND product_price_list.department_code=\''.$dp_code.'\' 
                    AND (product_price_list.end_date is null OR product_price_list.end_date>=\''.Date_Time::to_orc_date(date('d/m/y')).'\')
				ORDER BY product_category.position';	
		$categories = DB::fetch_all($sql);
		//System::Debug($categories);
		return $categories;
	}
}
?>