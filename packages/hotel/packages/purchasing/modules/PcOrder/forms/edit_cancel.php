<?php
class EditPcOrderForm extends Form
{
    function EditPcOrderForm()
    {
        Form::Form('EditPcOrderForm');
        $this->link_css(Portal::template('hotel').'/css/style.css');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
    }
    
    function on_submit()
    {
        if(isset($_REQUEST['mi_list_product']))
        {
            $order = array();
            $order += array('status'=>1,'last_edit_time'=>time(),'last_edit_user'=>User::id(),'name'=>$_REQUEST['order_name'],'description'=>$_REQUEST['description'],'pc_supplier_id'=>$_REQUEST['pc_supplier_id']);
            $total_order = 0;
            foreach($_REQUEST['mi_list_product'] as $key=>$value)
            {
                $total_order += System::calculate_number($value['total']);
                $detail = array(
                                'quantity'=>System::calculate_number($value['quantity']),
                                'price'=>System::calculate_number($value['price']),
                                'tax_percent'=>System::calculate_number($value['tax_percent'])
                                );
                DB::update('pc_order_detail',$detail,'id='.$value['id']);
            }
            
            $order +=array('total'=>$total_order);
            DB::update('pc_order',$order,'id='.Url::get('id'));
            Url::redirect('pc_order',array('cmd'=>'list_order'));
        }
    }
    function draw()
    {
       require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
       $this->map = array();
       if(Url::get('id'))
       {
            $order = DB::fetch("
                                SELECT
                                    pc_order.*,
                                    supplier.name as pc_supplier_name,
                                    supplier.address as pc_supplier_address,
                                    supplier.mobile as pc_supplier_mobile,
                                    supplier.tax_code as pc_supplier_tax_code,
                                    party.full_name as creater
                                FROM
                                    pc_order
                                    inner join supplier on supplier.id=pc_order.pc_supplier_id
                                    inner join party on party.user_id=pc_order.creater
                                WHERE
                                    pc_order.id = ".Url::get('id')."
                                ");
            $order['create_time'] = date('d/m/Y',$order['create_time']);
            
            $this->map = $order;
            $this->map['total'] = System::display_number($this->map['total']);
            $this->map['quantity'] = 0;
            //------------------------------------------------------------------------------------------------//
            
            $detail = DB::fetch_all("
                                    SELECT
                                        pc_order_detail.*,
                                        department.name_".portal::language()." as department_name,
                                        unit.name_".portal::language()." as unit_name,
                                        product.name_".portal::language()." as product_name,
                                        portal_department.warehouse_pc_id
                                    FROM
                                        pc_order_detail
                                        INNER JOIN pc_order on pc_order_detail.pc_order_id=pc_order.id
                                        INNER JOIN product on pc_order_detail.product_id=product.id
                                        INNER JOIN unit on product.unit_id=unit.id
                                        INNER JOIN portal_department on portal_department.id=pc_order_detail.portal_department_id
                                        INNER JOIN department on department.code=portal_department.department_code
                                    WHERE
                                        pc_order.id = ".Url::get('id')." AND pc_order_detail.status = 0
                                    ORDER BY
                                        product.id 
                                    ");
            $stt = 1;
            $product_res = false;
            
            foreach($detail as $key=>$value)
            {
                $detail[$key]['stt'] = $stt++;
                if($value['warehouse_pc_id']!='')
                //$product_remain = get_remain_products($value['warehouse_pc_id']);
                //$detail[$key]['wh_remain'] = isset($product_remain[$value['product_id']])?$product_remain[$value['product_id']]['remain_number']:0;
                $detail[$key]['price'] = System::display_number($value['price']);
                if(empty($value['tax_percent']))
                {
                    $value['tax_percent'] =0;
                }
                $detail[$key]['tax_percent'] = $value['tax_percent'];
                $detail[$key]['tax_amount'] = $value['price']*$value['tax_percent']*0.01;
                $detail[$key]['tax_amount'] = $detail[$key]['tax_amount']*$value['quantity'];

                $detail[$key]['total'] = $value['price']*$value['quantity']; 
                $detail[$key]['total'] +=$detail[$key]['tax_amount'];
                $detail[$key]['tax_amount'] = System::display_number($detail[$key]['tax_amount']);
                $detail[$key]['total'] = System::display_number($detail[$key]['total']);
                if($product_res!=$value['product_id'])
                {
                    $this->map['quantity'] ++;    
                } 
                
            }
            
            $this->map['mi_list_product'] = $detail;

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
            
            
            $list_total_amount_sup = $this->select_supplier_auto($list_supplier,$detail,$list_sup_price,$order);

            $this->map['list_total_amount_sup_option'] = '<option value="" >'.portal::language('select_supplier').'</option>';
            foreach($list_total_amount_sup as $id_sup=>$value_sup)
            {
                if($id_sup==$order['pc_supplier_id'])
                {
                    $this->map['list_total_amount_sup_option'] .= '<option value="'.$value_sup['id'].'" selected="selected" >'.$value_sup['name'].' -- '.$value_sup['total_amount_product'].'</option>';
                }
                else
                {
                    $this->map['list_total_amount_sup_option'] .= '<option value="'.$value_sup['id'].'" >'.$value_sup['name'].' -- '.$value_sup['total_amount_product'].'</option>';
                }
                
                //$this->map['list_total_amount_sup_option'] .= '<option value="'.$value_sup['id'].'" >'.$value_sup['name'].'</option>';
            }
       }
       else
       {
            $this->map['no_data'] = portal::language('no_record');
       }
       
       $this->parse_layout('edit_cancel',$this->map);
    }
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
    
    function select_supplier_auto($sup_list,$product_list,$sup_price,$order)
    {
        foreach($sup_list as $key=>$value)
        {
            $check = true;
            $sup_list[$key]['total_amount_product'] = 0;
            $sup_list[$key]['full_product'] = 1;
            if($value['id']!=$order['pc_supplier_id'])
            {
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
            else
            {
                $sup_list[$key]['total_amount_product'] = $order['total'];
            } 
        }
        return $sup_list;
    }
}
?>
