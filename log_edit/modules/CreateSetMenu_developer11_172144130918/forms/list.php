<?php
class ListSetMenuForm extends Form
{
	function ListSetMenuForm()
	{
		Form::Form('ListSetMenuForm');
		$this->link_css('skins/default/bootstrap/css/bootstrap.min.css');		
        $this->link_js('skins/default/bootstrap/js/bootstrap.js'); 	
	}

	function draw()
	{
       $quantity_per_page = 50;       
       $page_no = 1;
       if(isset($_GET['page_no'])){
          $page_no = $_GET['page_no'];
       }

       $cond = " rnk >=".(($page_no-1)*$quantity_per_page+1)." AND rnk<".($page_no*$quantity_per_page+1);
       $sql = "SELECT
                  *
                  FROM (
                  SELECT
                  bar_set_menu.*,
                  department.name_".Portal::language()." as department_name,
                  product_price_list.start_date,
                  product_price_list.end_date,
                  row_number() over (ORDER BY bar_set_menu.code,department.name_".Portal::language().", bar_set_menu.id) as rnk
                  FROM     
                  bar_set_menu
                  INNER JOIN department ON bar_set_menu.department_code = department.code
                  INNER JOIN product_price_list ON product_price_list.product_id = bar_set_menu.code AND product_price_list.department_code = bar_set_menu.department_code AND product_price_list.portal_id = bar_set_menu.portal_id
                  ORDER BY bar_set_menu.code,department.name_".Portal::language().", bar_set_menu.id
                  )
                  WHERE ".$cond;          
       $bar_set_menu = DB::fetch_all($sql);
       //System::debug($sql); exit();
       foreach($bar_set_menu as $key=>$value){
            $sql = "SELECT 
            bar_set_menu_product.id as id,
            product.name_1 as product_name,
            bar_set_menu_product.quantity
            FROM 
            bar_set_menu_product INNER JOIN product ON bar_set_menu_product.product_id = product.id
            WHERE bar_set_menu_product.bar_set_menu_id=".$value['id'];
            $items = DB::fetch_all($sql);
            $bar_set_menu[$key]['items'] = $items;
            $bar_set_menu[$key]['can_change_status'] = 1; 
            $sql = "SELECT id FROM bar_reservation_set_product WHERE bar_set_menu_id = ".$value['id'];
            $result = DB::fetch($sql);
            if(!empty($result)){
                $bar_set_menu[$key]['can_change_status'] = 0; 
            }
            $bar_set_menu[$key]['start_date'] = Date_Time::convert_orc_date_to_date($value['start_date'],"/");
            $bar_set_menu[$key]['end_date'] = Date_Time::convert_orc_date_to_date($value['end_date'],"/");
       }
       
        $j = 1;
        $key = array_keys($bar_set_menu);
        $total = 0;
        reset($bar_set_menu); 
       for($i=0 ; $i< count($bar_set_menu); $i++){ 
          $current = current($bar_set_menu);
          $next = next($bar_set_menu);
          if(!empty($next)){
           $arr_temp = explode("-",$next['code']);
          }
          if(!empty($next) && strpos($next['code'],$current['code'])>=0 && isset($arr_temp[1]) && strlen($arr_temp[1])==5){
              $j++;
          }
          else{
              if(isset($key[$i-$j+1])){
                  $bar_set_menu[$key[$i-$j+1]]['count'] = $j;
                  $j = 1;
              }
              
          }
       }
       if(!isset($rows)){       
         $rows=DB::fetch("SELECT  count(*) over () as num_rows FROM bar_set_menu");
         $rows = $rows['num_rows'];
       }
       $this->map['rows'] = ceil($rows/$quantity_per_page); 
       $this->map['bar_set_menu'] = $bar_set_menu;    
       $this->parse_layout('list',$this->map);
	}
}
?>