<?php
class ViewOrderPcImportWarehouseOrderForm extends Form
{
    function ViewOrderPcImportWarehouseOrderForm()
    {
        Form::Form('ViewOrderPcImportWarehouseOrderForm');
        $this->link_css(Portal::template('hotel').'/css/style.css');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
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
            $this->map['quantity'] = 0;
            //------------------------------------------------------------------------------------------------//
            
            $detail = DB::fetch_all("
                                    SELECT
                                        pc_order_detail.*,
                                        portal_department.id as portal_department_id,
                                        department.name_".portal::language()." as department_name,
                                        unit.name_".portal::language()." as unit_name,
                                        product.name_".portal::language()." as product_name,
                                        portal_department.warehouse_pc_id,
                                        warehouse.name as warehouse_name, -- trung add them ten kho
                                        warehouse.id as warehouse_id
                                    FROM
                                        pc_order_detail
                                        INNER JOIN pc_order on pc_order_detail.pc_order_id=pc_order.id
                                        INNER JOIN product on pc_order_detail.product_id=product.id
                                        INNER JOIN unit on product.unit_id=unit.id
                                        INNER JOIN portal_department on portal_department.id=pc_order_detail.portal_department_id
                                        INNER JOIN department on department.code=portal_department.department_code
                                        left join warehouse on warehouse.id = portal_department.warehouse_id
                                    WHERE
                                        pc_order.id = ".Url::get('id')."
                                    ORDER BY
                                        pc_order_detail.id 
                                    ");
            $stt = 1;
           // System::debug($detail);
            $detail_department = array();
            $product_res = false;
            foreach($detail as $key=>$value)
            {
                //if($value['warehouse_pc_id']!='')
                //$product_remain = get_remain_products($value['warehouse_pc_id']);
                
                //$detail[$key]['wh_remain'] = isset($product_remain[$value['product_id']])?$product_remain[$value['product_id']]['remain_number']:0;
                $detail[$key]['price'] = System::display_number($value['price']);
                if(empty($value['quantity_import']))
                {
                    $detail[$key]['quantity_import'] =0;
                    $value['quantity_import'] =0;
                }
                if(empty($value['tax_percent']))
                {
                    $value['tax_percent'] =0;
                }
                $value['quantity_remain'] = $value['quantity'] - $value['quantity_import'];
                $detail[$key]['quantity_remain'] = $value['quantity_remain'];
                $detail[$key]['tax_percent'] = $value['tax_percent'];
                $detail[$key]['tax_amount'] = $value['price']*$value['tax_percent']*0.01;
                $detail[$key]['tax_amount'] = $detail[$key]['tax_amount']*$value['quantity'];
                $detail[$key]['total'] = $value['price']*$value['quantity']; 
                $detail[$key]['total'] +=$detail[$key]['tax_amount'];
                $detail[$key]['tax_amount'] = System::display_number(round($detail[$key]['tax_amount']));
                $detail[$key]['total'] = System::display_number(round($detail[$key]['total']));
                if($value['delivery_time']!='')
                {
                    $detail[$key]['delivery_date'] = date('d/m/Y',$value['delivery_time']);
                }
                if($product_res!=$value['product_id'])
                {
                    $this->map['quantity'] ++;    
                } 

                //$detail[$key]['total_quantity_warehouse'] = $detail[$key]['wh_remain'] + $value['quantity'];
                
                
                if(!isset($detail_department[$value['portal_department_id']]))
                {
                    $detail_department[$value['portal_department_id']]['id'] = $value['portal_department_id'];
                    $detail_department[$value['portal_department_id']]['stt'] = $stt++;
                    $detail_department[$value['portal_department_id']]['name'] = $value['department_name'];
                    $detail_department[$value['portal_department_id']]['count'] = 1;
                    
                    if($detail[$key]['quantity_remain'] > 0)
                    {
                        if(isset($detail_department[$value['portal_department_id']]['ids']))
                            $detail_department[$value['portal_department_id']]['ids'] .= ",".$key;
                        else
                            $detail_department[$value['portal_department_id']]['ids'] = $key;
                    }else
                    {
                        $detail_department[$value['portal_department_id']]['ids'] = 0;                        
                    }
                    $detail_department[$value['portal_department_id']]['child'][$key] = $detail[$key];
                }
                else
                {
                    $detail_department[$value['portal_department_id']]['count'] += 1;
                    if($detail[$key]['quantity_remain'] > 0)
                    {
                        if(isset($detail_department[$value['portal_department_id']]['ids']))
                            $detail_department[$value['portal_department_id']]['ids'] .= ",".$key;
                        else
                            $detail_department[$value['portal_department_id']]['ids'] = $key;
                    }
                    $detail_department[$value['portal_department_id']]['child'][$key] = $detail[$key];
                }
            }
            //System::debug($detail_department);
            $this->map['mi_list_product'] = $detail_department;
            $warehouse_id_list = ViewOrderPcImportWarehouseOrderForm::get_warehouse();
            $this->map['warehouse_id']= $warehouse_id_list;
            $this->parse_layout('view_order',$this->map);
       }else
       {
            $this->map['no_data'] = portal::language('no_record');
       }
       
       /* trung :get list warehouse_name*/
       //$warehouse_id_list = ViewOrderPcImportWarehouseOrderForm::get_warehouse();
        //$this->map['warehouse_id']= $warehouse_id_list;
        //System::debug($warehouse_id_list);
       //$this->parse_layout('view_order',$this->map);
       /* trung end :get list warehouse_name*/
    }
    //trung: get warehouse
     function get_warehouse()
        {
        	return DB::fetch_all('
        			select 
        				warehouse.*
        			from 
        			 	warehouse
        			where
                        portal_id = \''.PORTAL_ID.'\'
                        and structure_id !='.ID_ROOT.'
        			order by 
        				warehouse.structure_id
        		',false);
        }
        //trung: get warehouse
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
    
}
?>
