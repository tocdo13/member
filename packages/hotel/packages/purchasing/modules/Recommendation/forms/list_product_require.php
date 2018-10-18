<?php
class ListProductRequireForm extends Form
{
    function ListProductRequireForm()
    {
        Form::Form('ListProductRequireForm');
        $this->link_css(Portal::template('hotel').'/css/style.css');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        define("CREATED","CREATED");
        define("ACCOUNTANT","ACCOUNTANT");
        define("DIRECTOR","DIRECTOR");
        define("HEAD_DEPARTMENT","HEAD_DEPARTMENT");
        define("MOVE","MOVE");
        define("DEPARTMENT_MOVE","DEPARTMENT_MOVE");
        define("COMPLETE","COMPLETE");
        define("CANCEL","CANCEL");
    }
    
    function draw()
    {
        require_once 'packages/core/includes/utils/vn_code.php';   
        $this->map = array();
        $cond = " 1=1 ";
        $this->map['from_date'] = isset($_REQUEST['from_date'])?$_REQUEST['from_date'] = $_REQUEST['from_date']:$_REQUEST['from_date'] = date('d/m/Y', Date_Time::to_time(date('d/m/Y', time())));
        $this->map['to_date'] = isset($_REQUEST['to_date'])?$_REQUEST['to_date'] = $_REQUEST['to_date']:$_REQUEST['to_date'] = date('d/m/Y', Date_Time::to_time(date('d/m/Y', time()))+ 6*86399);
        
        if(Url::get('department_id'))
        {
            $cond .=" AND pc_recommendation.portal_department_id=".Url::get('department_id');
        }
        if(Url::get('from_date'))
        {
            $cond .=" AND pc_recommendation.recommend_date>='".Date_Time::to_orc_date(Url::get('from_date'))."'";
        }
        if(Url::get('to_date'))
        {
            $cond .=" AND pc_recommendation.recommend_date <='".Date_Time::to_orc_date(Url::get('to_date'))."'";
        }
        $list_product_full = DB::fetch_all("
                                    SELECT
                                        pc_recommend_detail.id as id,
                                        product.id as product_id,
                                        product.name_".portal::language()." as product_name,
                                        pc_recommend_detail.quantity,
                                        to_char(pc_recommendation.recommend_date,'DD/MM/YYYY') as recommend_date,
                                        pc_recommend_detail.delivery_date,
                                        pc_recommend_detail.note,
                                        department.name_".portal::language()." as department_name,
                                        pc_recommendation.status,
                                        pc_recommendation.confirm,
                                        '' as order_status,
                                        '' as order_status_def
                                    FROM
                                        pc_recommend_detail
                                        INNER JOIN pc_recommendation on pc_recommend_detail.recommend_id=pc_recommendation.id
                                        INNER JOIN product on pc_recommend_detail.product_id=product.id
                                        INNER JOIN portal_department on portal_department.id=pc_recommendation.portal_department_id
                                        INNER JOIN department on department.code=portal_department.department_code
                                    WHERE
                                        ".$cond."
                                    ORDER BY
                                       pc_recommend_detail.delivery_date, pc_recommend_detail.id
                                    "); 
        
        $stt = 1;
        foreach($list_product_full as $key=>$value){
            $list_product_full[$key]['stt'] = $stt++;
            $list_product_full[$key]['delivery_date'] = date('d/m/Y',$value['delivery_date']);
            if($value['status']!='' || $value['confirm']!='')
            {
                if($value['status']!='' && $value['confirm']!='')
                {
                    $list_product_full[$key]['order_status'] = DEPARTMENT_MOVE;
                }
                else if($value['status']!='')
                {
                    $list_product_full[$key]['order_status'] = MOVE;
                }
                else
                {
                    $list_product_full[$key]['order_status'] = HEAD_DEPARTMENT;
                }
            }
            if($value['quantity'] < 1 && $value['quantity'] >0)
            {
                $list_product_full[$key]['quantity'] = '0'.$value['quantity'];    
            }
            $list_product_full[$key]['child'] = array();
            $list_product_full[$key]['count_child'] = 0;
        }
        //System::debug($list_product_full); die;
        $quantity_use = DB::fetch_all('
                        SELECT
                            pc_recommend_detail_order.id as id,
                            pc_recommend_detail.id as detail_id,
                            pc_recommend_detail.product_id,
                            pc_recommend_detail.quantity as detail_quantity,
                            pc_order_detail.quantity,
                            pc_order_detail.id as order_id,
                            pc_order.code as order_code,
                            pc_order.status as order_status
                        FROM
                           pc_recommend_detail_order
                           INNER JOIN pc_order_detail on pc_recommend_detail_order.order_id = pc_order_detail.id
                           INNER JOIN pc_order on pc_order_detail.pc_order_id = pc_order.id
                           INNER JOIN pc_recommend_detail on pc_recommend_detail_order.pc_recommend_detail_id = pc_recommend_detail.id
                        ORDER BY
                            pc_recommend_detail.id DESC 
       ');
       $quantity_use_group = array();
       foreach($quantity_use as $key=>$value){
            if($value['detail_quantity'] < 1 && $value['detail_quantity'] >0)
            {
                $value['detail_quantity'] = '0'.$value['detail_quantity'];    
            }
            if($value['quantity'] < 1 && $value['quantity'] >0)
            {
                $value['quantity'] = '0'.$value['quantity'];    
            }
            if(!isset($quantity_use_group[$value['order_id']])){
                $quantity_use_group[$value['order_id']]['id'] = $value['order_id'];
                $quantity_use_group[$value['order_id']]['order_code'] = $value['order_code'];
                $quantity_use_group[$value['order_id']]['order_status'] = $value['order_status'];
                $quantity_use_group[$value['order_id']]['quantity_use'] = $value['quantity'];
                $quantity_use_group[$value['order_id']]['quantity'] = 0;
                $quantity_use_group[$value['order_id']]['child'] = array();
                switch ($value['order_status']) {
                    case '1':
                        $quantity_use_group[$value['order_id']]['order_status'] = CREATED;
                        break;
                    case '2':
                        $quantity_use_group[$value['order_id']]['order_status'] = ACCOUNTANT;
                        break;
                    case '3':
                        $quantity_use_group[$value['order_id']]['order_status'] = DIRECTOR;
                        break;
                    case '4':
                        $quantity_use_group[$value['order_id']]['order_status'] = COMPLETE;
                        break;
                    case '0':
                        $quantity_use_group[$value['order_id']]['order_status'] = CANCEL;
                        break;
                    default:
                        break;
                }
            }
            $quantity_use_group[$value['order_id']]['quantity'] += $value['detail_quantity'];
            $quantity_use_group[$value['order_id']]['child'][$value['detail_id']]['id'] = $value['detail_id'];
            $quantity_use_group[$value['order_id']]['child'][$value['detail_id']]['quantity'] = $value['detail_quantity'];
       }
       //System::debug($quantity_use_group); die;
       foreach($quantity_use_group as $key=>$value){
            // san pham da duoc tao don hang het
            if($value['quantity_use']>=$value['quantity']){
                foreach($value['child'] as $k=>$v){
                    if(isset($list_product_full[$v['id']])){
                        $list_product_full[$v['id']]['child'][$key]['id'] = $key;
                        $list_product_full[$v['id']]['child'][$key]['order_code'] = $value['order_code'];
                        $list_product_full[$v['id']]['child'][$key]['quantity'] = $value['quantity_use'];
                        $list_product_full[$v['id']]['child'][$key]['order_status'] = $value['order_status'];
                        $list_product_full[$v['id']]['count_child']++;
                        $list_product_full[$v['id']]['order_status'] = CREATED;
                    }
                }
            }else{
                // san pham chi tao don hang 1 phan
                foreach($value['child'] as $k=>$v){
                    if(isset($list_product_full[$v['id']])){
                        if($value['quantity_use']>=$list_product_full[$v['id']]['quantity']){
                            $value['quantity_use'] -= $list_product_full[$v['id']]['quantity'];
                            $list_product_full[$v['id']]['child'][$key]['id'] = $key;
                            $list_product_full[$v['id']]['child'][$key]['order_code'] = $value['order_code'];
                            $list_product_full[$v['id']]['child'][$key]['quantity'] = $list_product_full[$v['id']]['quantity'];
                            $list_product_full[$v['id']]['child'][$key]['order_status'] = $value['order_status'];
                            $list_product_full[$v['id']]['count_child']++;
                            $list_product_full[$v['id']]['order_status'] = CREATED;
                        }else{
                            $list_product_full[$v['id']]['child'][$key]['id'] = $key;
                            $list_product_full[$v['id']]['child'][$key]['order_code'] = $value['order_code'];
                            $list_product_full[$v['id']]['child'][$key]['quantity'] = $value['quantity_use'];
                            $list_product_full[$v['id']]['child'][$key]['order_status'] = $value['order_status'];
                            $list_product_full[$v['id']]['count_child']++;
                            $list_product_full[$v['id']]['order_status'] = CREATED;
                            $value['quantity_use'] = 0;
                        }
                    }
                }
            }
       }
       
        //System::debug($list_product_full); die;
        $this->map['items'] = $list_product_full;
        //2. hien thi danh sach bo phan 
        $sql="Select 
                portal_department.id, 
                portal_department.department_code as code, 
                '--' || department.name_1 as name,
                department.parent_id,
                department.id as department_id
                
            from 
                portal_department
                inner join department on  department.code  = portal_department.department_code and portal_department.portal_id = '".PORTAL_ID."'
            where 
                department.parent_id=0
            order by 
                portal_department.id ";
        
        $departments = DB::fetch_all($sql);
        
        $default = array('id'=>0,'code'=>'CHOOSE_DEPARTMENT','name'=>Portal::language('select_department'));
        $result = array();
    
        $parent_id = false;
        /** Daund viet lai phan chon bo phan de toi uu toc do load*/
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
        $this->map['department_list'] = $department_list;
        
        $this->parse_layout('list_product_require',$this->map);
    }   
}
?>