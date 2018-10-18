<?php
    define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    set_include_path(ROOT_PATH);
    require_once 'packages/core/includes/system/config.php';
    
    function get_check_product_department()
    {
        $items_product = DB::fetch_all('
                        				select 
                                            pc_department_product.product_id as id,
                                            product.name_1 as name,
                                            pc_department_product.id as department_product_id,
                        					rownum
                        				from
                                            pc_department_product
                                            INNER JOIN product ON product.id=pc_department_product.product_id
                        				where 
                                            pc_department_product.product_id in ('.Url::get('list_product').')
                        					AND pc_department_product.portal_department_id='.Url::get('department_id').'
                        				order by
                        					pc_department_product.product_id
                        			');
        return $items_product;
    }
    function get_delete()
    {
        $check = 0;
        if(Url::get('id') AND DB::exists('SELECT id from purchases_proposed where id='.Url::get('id')))
        {
            $delete = DB::delete('purchases_proposed','id='.Url::get('id'));
            if($delete)
            {
                $check = 1;
            }
        }
        return $check;
    }
    
    function get_confirm_group()
    {
        $check = 0;
        $time = calc_time(date('H:i'));
        if(isset($_REQUEST['id']) AND DB::exists('SELECT id from purchases_group_invoice where id='.$_REQUEST['id']))
        {
            DB::update("purchases_group_invoice",array('status'=>'CONFIRM','confirm_user'=>User::id(),'confirm_time'=>(Date_Time::to_time(date('d/m/Y')))+$time),"id=".$_REQUEST['id']);
            $purchases_invoice = DB::fetch_all("select purchases_invoice.id as id from purchases_invoice Where purchases_invoice.group_id=".$_REQUEST['id']);
            if(sizeof($purchases_invoice)>0)
            {
                foreach($purchases_invoice as $id=>$value)
                {
                    DB::update("purchases_invoice",array('status'=>'CONFIRM','confirm_user'=>User::id(),'confirm_time'=>(Date_Time::to_time(date('d/m/Y')))+$time),"id=".$value['id']);
                }
            }
            $check=1;
        }
        return $check;
    }
    /** trả về dữ liệu cho hàm gọi **/
    switch($_REQUEST['data'])
    {
        case "check_product_department":
        {
            echo json_encode(get_check_product_department()); break;
        }
        case "delete":
        {
            echo json_encode(get_delete()); break;
        }
        case "confirm_group":
        {
            echo json_encode(get_confirm_group()); break;
        }
        default: echo '';break;
    }
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
    
?>