<?php
class AddPcOrderForm extends Form
{
    function AddPcOrderForm()
    {
        Form::Form('AddPcOrderForm');
        $this->link_css(Portal::template('hotel').'/css/style.css');
        $this->link_js('packages/core/includes/js/multi_items.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
    }
    
    function on_submit()
    {
        //System::debug($_REQUEST); die;
        if(Url::get('act')=='creater')
        {
            if(isset($_REQUEST['mi_list_product']))
            {
                $order = array(
                                'create_time'=>Date_Time::to_time(Url::get('create_time'))+$this->calc_time(date('H:i')),
                                'code'=>$this->get_code_order(),
                                'name'=>Url::get('order_name'),
                                'description'=>Url::get('description'),
                                'status'=>1,
                                'pc_supplier_id'=>Url::get('pc_supplier_id'),
                                'creater'=>User::id(),
                                'receiver'=>Url::get('receiver'),
                                'place_of_receipt'=>Url::get('place_of_receipt'),
                                'tel_of_receipt'=>Url::get('tel_of_receipt')
                                );
                
                $order_id =DB::insert('pc_order',$order);
                $total_order = 0;
                //System::debug($_REQUEST['mi_list_product']);exit();
                foreach($_REQUEST['mi_list_product'] as $key=>$value)
                {
                    $detail = array(
                                    'pc_order_id'=>$order_id,
                                    'status'=>0,
                                    'price'=>System::calculate_number($value['price']),
                                    'quantity'=>System::calculate_number($value['quantity']),
                                    'portal_department_id'=>$value['portal_department_id'],
                                    'product_id'=>$value['product_id'],
                                    'delivery_time'=>$value['delivery_date'],
                                    'note'=>$value['note'],
                                    'tax_percent'=>$value['tax_percent'],
                                    'DESCRIPTION_PRODUCT1'=>$value['description_product1'] // them note tung san pham                                    
                                                                        
                                    );
                                    
                    $total_order += System::calculate_number($value['total']);
                    $detail_id = DB::insert('pc_order_detail',$detail);
                    if(strpos($value['pc_recommend_detail_id_list'],','))
                    {
                        $arr_recommend_id = explode(',',$value['pc_recommend_detail_id_list']);
                        for($i=0;$i<sizeof($arr_recommend_id);$i++)
                        {
                            //if(isset($arr_recommend_id[$i]))
                            /*Daund cmt lại để tách sang bảng mới */
                            //DB::update('pc_recommend_detail',array('order_id'=>$detail_id),'id='.$arr_recommend_id[$i]);
                            /** Daund tách order id sang bảng mới */
                            $order_arr = array(
                                'pc_recommend_detail_id' => $arr_recommend_id[$i],
                                'order_id' => $detail_id    
                            );
                            //System::debug($arr_recommend_id[$i]);
                            DB::insert('pc_recommend_detail_order',$order_arr);
                            /** Daund tách order id sang bảng mới */
                        } 
                        //exit();
                    }
                    else
                    {
                        /*Daund cmt lại để tách sang bảng mới
                        $order_id_old = DB::fetch('select order_id from pc_recommend_detail where id='.$value['pc_recommend_detail_id_list'].'','order_id');
                        if($order_id_old!='')
                            $order_id_old .= ','.$detail_id;
                        else
                            $order_id_old = $detail_id;
                        DB::update('pc_recommend_detail',array('order_id'=>$order_id_old),'id='.$value['pc_recommend_detail_id_list']);*/
                        /** Daund tách order id sang bảng mới */
                        $order_arr = array(
                            'pc_recommend_detail_id' => $value['pc_recommend_detail_id_list'],
                            'order_id' => $detail_id    
                        );
                        DB::insert('pc_recommend_detail_order',$order_arr);
                        /** Daund tách order id sang bảng mới */
                    }
                }
                //exit();
                DB::update('pc_order',array('total'=>$total_order),'id='.$order_id);
            }
            Url::redirect('pc_order',array('cmd'=>'list','status'=>1));
        }
    }
    
    function draw()
    {
       require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
       $this->map = array();
       //$category = DB::fetch_all('select id,name from PRODUCT_CATEGORY');
       //System::debug($category);
       /** trung : tao nut tim theo category **/
       require_once 'packages/hotel/modules/ProductCategory/db.php';
       require_once 'packages/core/includes/utils/category.php';
       $category_name = DB::fetch_all('select * from PRODUCT_CATEGORY order by structure_id ');
       category_indent($category_name);
       $category_name = ProductCategoryDB::check_categories($category_name);
       $this->map['category_name'] =$category_name;     
       //System::debug($this->map['category_name'])   ;             
       $this->map['category_id_1_list'] = String::get_list(DB::fetch_all('select id,name from PRODUCT_CATEGORY order by structure_id ')) ;
       /** trung add :select tung bo phan **/
       // Daund ẩn đi thay cái mới 
       /*$department = DB::fetch_all(' select
                                            portal_department.id,
                                            portal_department.department_code as code,
                                            \'--\' ||department.name_1 as name,
                                            department.parent_id,
                                            department.id as department_id
                                      from 
                                        portal_department  
                                        inner join department on portal_department.department_code =department.code and portal_department.portal_id= \''.PORTAL_ID.'\' 
                                      where
                                          department.parent_id = 0
                                      order by 
                                        portal_department.id    
       '                                 
       );

      // System::debug($department);
       $default = array('id'=>0,'code'=>'CHOOSE DEPARTMENT ','name'=> Portal::language('department'));
       $result =array();
       array_push($result,$default);
               
        foreach($department as $key =>$row )
        {
            
            array_push($result,$department[$key]);
            $items = DB::fetch_all( '
                   select 
                       portal_department.id,
                       portal_department.department_code as code,
                       \'----\' || department.name_1 as name,
                       department.parent_id,
                       department.id as department_id
                   from 
                       portal_department 
                       inner join department on department.code = portal_department.department_code
                   where 
                       department.parent_id= \''.$row['department_id'].'\' and portal_department.portal_id =\''.PORTAL_ID.'\'     
            ');
            foreach ($items as $k=>$v)
            {
                array_push($result,$items[$k]);
            } 
        }
        //System::debug($result);
       $this->map['department1_list']= String::get_list($result);*/
       /** Daund viet lai phan chon bo phan de toi uu toc do load*/
        $departments = DB::fetch_all("
                            SELECT 
                                portal_department.id, 
                                portal_department.department_code as code, 
                                '--' || department.name_1 as name,
                                department.parent_id,
                                department.id as department_id,
                                portal_department.warehouse_pc_id,
                                warehouse.name as warehouse_name-- trung:lay ten kho
                            FROM 
                                portal_department
                                INNER join department on  department.code  = portal_department.department_code and portal_department.portal_id = '".PORTAL_ID."'
                                LEFT join warehouse on portal_department.warehouse_pc_id = warehouse.id
                            WHERE 
                                department.parent_id=0
                            ORDER BY 
                                portal_department.id
        ");
        $default = array('id'=>0,'code'=>'CHOOSE_DEPARTMENT','name'=>Portal::language('select_department'));
        $result = array();
        array_push($result,$default);
        $department_list = '';
        $child_department = DB::fetch_all('
                                SELECT 
                                    portal_department.id,
                                    portal_department.department_code as code,
                                    \'----\' || department.name_1 as name,
                                    department.parent_id,
                                    department.id as department_id,
                                    portal_department.warehouse_pc_id,
                                    warehouse.name as warehouse_name --trung:lay ten kho
                                FROM 
                                    portal_department
                                    inner join department on department.code  = portal_department.department_code
                                    left join warehouse on portal_department.warehouse_pc_id = warehouse.id
                                WHERE 
                                    portal_department.portal_id=\''.PORTAL_ID.'\'                        
        ');
        foreach($departments as $key => $value)
        {
            array_push($result,$departments[$key]);
            foreach($child_department as $k=>$v)
            {
                if($v['parent_id'] == $value['department_id'])
                {
                    array_push($result,$child_department[$k]);
                }
            }         
        }
        foreach($result as $id => $value)
        {
            $department_list .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
        }
        $this->map['department1_list']= String::get_list($result);
        /** Daund viet lai phan chon bo phan de toi uu toc do load*/
          
        
        
       //Lay ra tat ca nhung san pham duoc yeu cau ma chua tao don hang
       //san pham ma da duoc Truong BP duyet va khong phai trang thai dieu chuyen MOVE
        //danh sach da gom: theo Product & portal_department_id
              
       $list_product_full = DB::fetch_all("
                                    SELECT
                                        pc_recommend_detail.id as id,
                                        product.id as product_id,
                                        product.name_".portal::language()." as product_name,
                                        pc_recommend_detail.id as pc_recommend_detail_id,
                                        pc_recommend_detail.quantity,
                                        unit.id as unit_id,
                                        unit.name_".portal::language()." as unit_name,
                                        pc_recommendation.portal_department_id,
                                        department.id as department_id,
                                        department.name_".portal::language()." as department_name,
                                        portal_department.warehouse_pc_id,
                                        portal_department.id as portal_department_id1, --trung add
                                        pc_recommend_detail.delivery_date,
                                        pc_recommend_detail.note,
                                        product.tax_percent,
                                        product.category_id,
                                        product_category.name as category_name,
                                        pc_recommend_detail.order_id,
                                        pc_recommend_detail.id as pc_recommend_detail_id_list,
                                        0 as tax_amount,
                                        0 as wh_remain
                                    FROM
                                        pc_recommend_detail
                                        INNER JOIN pc_recommendation on pc_recommend_detail.recommend_id=pc_recommendation.id
                                        INNER JOIN product on pc_recommend_detail.product_id=product.id
                                        inner join product_category on product_category.id=product.category_id
                                        INNER JOIN unit on product.unit_id=unit.id
                                        INNER JOIN portal_department on portal_department.id=pc_recommendation.portal_department_id
                                        INNER JOIN department on department.code=portal_department.department_code
                                    WHERE
                                        pc_recommendation.confirm is not null
                                        AND pc_recommendation.status is null
                                    ORDER BY
                                       pc_recommend_detail.delivery_date, pc_recommend_detail.id
                                    ");    
       $list_product = array();
       
       /**
       Mac dinh Khong thuc hien gom nhom so luong san pham cung bo phan 
       **/
       /** Daund Lấy ra số lượng sản phẩm đã tạo đơn hàng */
       $quantity_use = DB::fetch_all('
                        SELECT
                            pc_recommend_detail_order.id as id,
                            pc_recommend_detail.id as detail_id,
                            pc_recommend_detail.product_id,
                            pc_recommend_detail.quantity as detail_quantity,
                            pc_order_detail.quantity,
                            pc_order_detail.id as order_id
                        FROM
                           pc_recommend_detail_order
                           INNER JOIN pc_order_detail on pc_recommend_detail_order.order_id = pc_order_detail.id
                           INNER JOIN pc_order on pc_order_detail.pc_order_id = pc_order.id
                           INNER JOIN pc_recommend_detail on pc_recommend_detail_order.pc_recommend_detail_id = pc_recommend_detail.id
                        ORDER BY
                            pc_recommend_detail.id DESC 
       ');
       //System::debug($quantity_use);
       //$items_arr =array();
       //$product_id = 0;
       /*
       foreach($quantity_use as $key => $value)
       {
            $key_arr = $value['order_id'];
            if(!isset($items_arr[$key_arr]))
            {
                $items_arr[$key_arr]['id'] = $key_arr;
                $items_arr[$key_arr]['order_id'] = $value['order_id'];
                $items_arr[$key_arr]['pc_recommend_detail_id'] = $value['detail_id'];
                $items_arr[$key_arr]['product_id'] = $value['product_id'];
                $items_arr[$key_arr]['quantity'] = $value['quantity'];
                $items_arr[$key_arr]['remain_quantity'] = $items_arr[$key_arr]['quantity'] - $value['detail_quantity'];
            }else
            {
                if($items_arr[$key_arr]['remain_quantity'] > 0)
                {
                    $key_arr_new = $value['order_id'].'_'.$value['detail_id'];
                    $items_arr[$key_arr_new]['id'] = $key_arr_new;
                    $items_arr[$key_arr_new]['order_id'] = $value['order_id'];
                    $items_arr[$key_arr_new]['pc_recommend_detail_id'] = $value['detail_id'];
                    $items_arr[$key_arr_new]['product_id'] = $value['product_id'];
                    $items_arr[$key_arr_new]['quantity'] = $items_arr[$key_arr]['remain_quantity'];
                    $items_arr[$key_arr_new]['remain_quantity'] = $items_arr[$key_arr_new]['quantity'] - $value['detail_quantity'];
                }   
            }
       }
       */
       $quantity_use_group = array();
       foreach($quantity_use as $key=>$value){
            if(!isset($quantity_use_group[$value['order_id']])){
                $quantity_use_group[$value['order_id']]['id'] = $value['order_id'];
                $quantity_use_group[$value['order_id']]['quantity_use'] = $value['quantity'];
                $quantity_use_group[$value['order_id']]['quantity'] = 0;
                $quantity_use_group[$value['order_id']]['child'] = array();
            }
            $quantity_use_group[$value['order_id']]['quantity'] += $value['detail_quantity'];
            $quantity_use_group[$value['order_id']]['child'][$value['detail_id']]['id'] = $value['detail_id'];
            $quantity_use_group[$value['order_id']]['child'][$value['detail_id']]['quantity'] = $value['detail_quantity'];
       }
       //System::debug($quantity_use_group); die;
       foreach($quantity_use_group as $key=>$value){
            // san pham da duoc tao don hang het
            if($value['quantity_use']==$value['quantity']){
                foreach($value['child'] as $k=>$v){
                    if(isset($list_product_full[$v['id']]))
                        unset($list_product_full[$v['id']]);
                }
            }else{
                // san pham chi tao don hang 1 phan
                foreach($value['child'] as $k=>$v){
                    if(isset($list_product_full[$v['id']])){
                        if($value['quantity_use']>=$list_product_full[$v['id']]['quantity']){
                            $value['quantity_use'] -= $list_product_full[$v['id']]['quantity'];
                            unset($list_product_full[$v['id']]);
                        }else{
                            $list_product_full[$v['id']]['quantity'] -= $value['quantity_use'];
                            $value['quantity_use'] = 0;
                        }
                    }
                }
            }
       }
       /*
       foreach($items_arr as $key => $value)
       {
            if(isset($list_product_full[$value['pc_recommend_detail_id']]))
            {
                if(($list_product_full[$value['pc_recommend_detail_id']]['quantity'] - $value['quantity']) > 0)
                {
                    $list_product_full[$value['pc_recommend_detail_id']]['quantity'] = $list_product_full[$value['pc_recommend_detail_id']]['quantity'] - $value['quantity'];                        
                }else
                {
                    unset($list_product_full[$value['pc_recommend_detail_id']]);
                }
            }
       }
       */
       
       /** Daund Lấy ra số lượng sản phẩm đã được tạo đơn hàng */
       foreach($list_product_full as $key=>$value)
       {
            if($value['quantity'] <1 && $value['quantity'] >0)
            {
                $list_product_full[$key]['quantity'] = '0' . $value['quantity'];                
            }
            if(isset($list_product_full[$key]))
            {
                $list_product_full[$key]['pc_recommend_detail_id_list'] = $value['id'];
                $list_product_full[$key]['tax_amount'] = 0;
                if( $value['warehouse_pc_id']!='')
                {
                    //$product_remain = array();
                    $list_product_full[$key]['wh_remain'] = 0;
                    //$product_remain = get_remain_products($value['warehouse_pc_id'],false,$value['product_id']);
                    //$list_product_full[$key]['wh_remain'] = isset($product_remain[$value['product_id']])?$product_remain[$value['product_id']]['remain_number']:0;
                }
                else
                    $list_product_full[$key]['wh_remain']  = 0;
            }
       }
       $list_product = $list_product_full;
       //System::debug($list_product);exit();
       /** chuc nang Gom: theo san pham va theo bo phan
       Tuc la: nhung san pham cung bo phan se gom so luong vao don hang

       **/

       /** giap.ln comment: mac dinh khong gom nhom so luong san pham 
       $product_id_res = false;
       $portal_department_id_res = false;

       foreach($list_product_full as $id_prd=>$value_prd)
       {
            if(isset($list_product[$value_prd['product_id']."_".$value_prd['portal_department_id']]))
            {
                $list_product[$value_prd['product_id']."_".$value_prd['portal_department_id']]['quantity'] += $value_prd['quantity'];
                $list_product[$value_prd['product_id']."_".$value_prd['portal_department_id']]['pc_recommend_detail_id_list'] .= ','.$value_prd['id'];
            }
            else
            {
                $list_product[$value_prd['product_id']."_".$value_prd['portal_department_id']] = $value_prd;
                $list_product[$value_prd['product_id']."_".$value_prd['portal_department_id']]['pc_recommend_detail_id_list'] = $value_prd['id'];
                $list_product[$value_prd['product_id']."_".$value_prd['portal_department_id']]['id'] = $value_prd['product_id'];

                if(($value_prd['product_id']!=$product_id_res || $value_prd['portal_department_id']!=$portal_department_id_res) && $value_prd['warehouse_pc_id']!='')
                {
                    $product_remain = get_remain_products($value_prd['warehouse_pc_id'],false,$value_prd['product_id']);
                    $list_product[$value_prd['product_id']."_".$value_prd['portal_department_id']]['wh_remain'] = isset($product_remain[$value_prd['product_id']])?$product_remain[$value_prd['product_id']]['remain_number']:0;
                    
                    $product_id_res = $value_prd['product_id'];
                    $portal_department_id_res =  $value_prd['portal_department_id'];
                }
            }
       }
       **/
       
       if(sizeof($list_product)>0) /** neu co san pham de xuat **/
       {
        
             //System::debug($list_product);    
                  
            $this->map['mi_list_product'] = $list_product;
           // System::debug($list_product);
            //danh sach nha cung cap 
            $list_supplier = DB::fetch_all("SELECT * FROM supplier");
            $this->map['list_supplier_js'] = String::array2js($list_supplier);
            
            //lay ra danh sach bao gia nha cung cap voi moi san pham
            $list_sup_price = DB::fetch_all("
                                            SELECT 
                                                CONCAT(concat(pc_sup_price.product_id,'_'),pc_sup_price.supplier_id) as id,
                                                pc_sup_price.product_id,
                                                pc_sup_price.supplier_id,
                                                pc_sup_price.price,
                                                pc_sup_price.tax, 
                                                product.tax_percent
                                            FROM
                                                pc_sup_price
                                                INNER JOIN product ON product.id=pc_sup_price.product_id
                                            WHERE
                                                (pc_sup_price.starting_date is null)
                                                OR (pc_sup_price.starting_date is not null ANd pc_sup_price.starting_date<='".Date_Time::to_orc_date(date('d/m/Y'))."' AND pc_sup_price.ending_date is null)
                                                OR (pc_sup_price.starting_date is not null AND pc_sup_price.ending_date is not null AND pc_sup_price.starting_date<='".Date_Time::to_orc_date(date('d/m/Y'))."' AND pc_sup_price.ending_date>='".Date_Time::to_orc_date(date('d/m/Y'))."')
                                               
                                            ORDER BY
                                                pc_sup_price.supplier_id
                                            ");
            $this->map['list_sup_price_js'] = String::array2js($list_sup_price);
            $list_total_amount_sup = $this->select_supplier_auto($list_supplier,$list_product,$list_sup_price);
                                               
            /* $this->map['list_total_amount_sup_option'] = '<option value="" >'.portal::language('select_supplier').'</option>';
            foreach($list_total_amount_sup as $id_sup=>$value_sup)
            {
                $this->map['list_total_amount_sup_option'] .= '<option value="'.$value_sup['id'].'" >'.$value_sup['name'].' -- '.$value_sup['total_amount_product'].'</option>';
              
                //$this->map['list_total_amount_sup_option'] .= '<option value="'.$value_sup['id'].'" >'.$value_sup['name'].'</option>';
            }*/
       }
       else
       {
           // $this->map['no_data'] = portal::language('no_record');
       }
       
       $this->parse_layout('add',$this->map);
    }
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
    
    function format_string($string,$number)
    {
        $count = strlen($string);
        if($count<$number)
        {
            for($i=$count;$i<=$number;$i++)
            {
                $string = '0'.$string;
            }
        }
        return $string;
    }
    
    function select_supplier_auto($sup_list,$product_list,$sup_price)
    {
        foreach($sup_list as $key=>$value)
        {
            $check = true;
            $sup_list[$key]['total_amount_product'] = 0;
            $sup_list[$key]['full_product'] = 1;
            foreach($product_list as $id=>$content)
            {
                
                if(isset($sup_price[$content['product_id']."_".$value['id']]))
                {
                    $sup_list[$key]['total_amount_product'] += $sup_price[$content['product_id']."_".$value['id']]['price']*$content['quantity'];
                }
                else
                {
                    $sup_list[$key]['full_product'] = 0;
                    $sup_list[$key]['total_amount_product'] += 0;
                }
            }   
        }
        return $sup_list;
    }
    
    function get_code_order()
    {
        $code = DB::fetch("select max(id) as id from pc_order",'id');
        if($code=='')
            $code = 1;
        else
            $code ++;
        $code = "MH".$this->format_string($code,6);
        return $code;
    }
    
}
?>
