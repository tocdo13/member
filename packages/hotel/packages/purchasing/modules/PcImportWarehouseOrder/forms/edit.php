<?php
class EditPcImportWarehouseOrderForm extends Form
{
    function EditPcImportWarehouseOrderForm()
    {
        Form::Form('EditPcImportWarehouseOrderForm');
        $this->link_css(Portal::template('hotel').'/css/style.css');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
    }
    function on_submit()
    {
        //System::debug($_REQUEST);
        if(isset($_REQUEST['close']))
        {
            $order = DB::fetch("SELECT * FROM pc_order WHERE id=".Url::get('order_id'));
            if($order['import_status']!='')
                DB::update('pc_order',array('import_status'=>'APART_CLOSE'),'id='.Url::get('order_id'));
            else
                DB::update('pc_order',array('import_status'=>'CLOSE'),'id='.Url::get('order_id'));
            //tro ve trang danh sach don hang da mua 
            Url::redirect('pc_order',array('cmd'=>'list','status'=>4));
        }
        if(isset($_REQUEST['product']))
        {
            $total_tax = 0;
            $total_total = 0;
            foreach($_REQUEST['product'] as $key=>$value)
            {
                //System::debug(System::calculate_number($value['total']) - round(System::calculate_number($value['total'])/(1+$value['tax_percent']/100)));
                $total_tax += System::calculate_number($value['total']) - round(System::calculate_number($value['total'])/(1+$value['tax_percent']/100));
                $total_total += System::calculate_number(System::calculate_number($value['price'])*$value['quantity_remain']);
            }
            $wh = array(
                        'type'=>'IMPORT',
                        'supplier_id'=>$_REQUEST['supplier_id'],
                        'receiver_name'=>$_REQUEST['receiver_name'],
                        'warehouse_id'=>$_REQUEST['warehouse_id'],
                        'create_date'=>Date_Time::to_orc_date($_REQUEST['create_date']),
                        'total_amount'=>$total_total,
                        'tax'=>$total_tax,
                        'note'=>$_REQUEST['description'],
                        'portal_id'=>PORTAL_ID,
                        'invoice_number'=>$_REQUEST['invoice_number']
                        );
            //System::debug($wh);exit();
            if(Url::get('cmd')=='add')
            {
                $wh['pc_order_id'] = Url::get('order_id');
                $wh['bill_number'] = $this->get_bill_number('IMPORT',$_REQUEST['warehouse_id'],$_REQUEST['warehouse_code']);
                $wh['deliver_name'] = $_REQUEST['deliver_name'];
                $wh['user_id'] = User::id();
                $wh['time'] = time();
                if(Url::get('action')=='import')
                    $id = DB::insert('wh_invoice',$wh);
                else
                {
                    $wh['bill_number'] = $this->get_bill_number_handover();
                    unset($wh['warehouse_id']);
                    unset($wh['tax']);
                    $id = DB::insert('handover_invoice',$wh);
                }
            }
            else
            {
                $wh['last_modify_user_id'] = User::id();
                $wh['last_modify_time'] = time();
                $id = Url::get('id');
                if(Url::get('action')=='import')
                {
                    //System::debug($wh);exit();
                    DB::update('wh_invoice',$wh,'id='.$id);
                }   
                else
                {
                    unset($wh['warehouse_id']);
                    unset($wh['tax']);
                    DB::update('handover_invoice',$wh,'id='.$id);
                }
            }
            //giap.ln them truong hop nhap kho 1 phan 
            $order = array();
            $import_status_flag = true;
            $total_amount =0;
            //end giap.ln 
            foreach($_REQUEST['product'] as $key=>$value)
            {
                $quantity_used = $value['quantity_remain'] + $value['quantity_import'];
                
                $detail = array(
                                'invoice_id'=>$id,
                                'product_id'=>$value['product_id'],
                                'num'=>$value['quantity_remain'],
                                'price'=>System::calculate_number($value['price']),
                                'warehouse_id'=>$_REQUEST['warehouse_id'],
                                'payment_price'=>System::calculate_number(System::calculate_number($value['price'])*$value['quantity_remain']),
                                //'payment_price'=>System::calculate_number($value['total']),
                                'pc_order_detail_id'=>$value['pc_order_detail_id']
                                );
                
                if(Url::get('cmd')=='add')
                {
                    if(Url::get('action')=='import')
                    {
                        $total_amount +=System::calculate_number($value['total']);
                        $order_detail = array('quantity_import'=>$quantity_used);
                        DB::insert('wh_invoice_detail',$detail);
                        $order_detail['status'] = 1;
                        DB::update('pc_order_detail',$order_detail,'id='.$value['pc_order_detail_id']);
                        $record = array();
                        if(!DB::fetch('SELECT * FROM wh_start_term_remain WHERE product_id=\''.$value['product_id'].'\' and warehouse_id ='.$_REQUEST['warehouse_id'].' and portal_id = \''.PORTAL_ID.'\' ','id'))
                        {
                            $record['warehouse_id'] = $_REQUEST['warehouse_id'];
                            $record['product_id'] = $value['product_id'];
                            $record['quantity'] = 0;
                            $record['portal_id'] = PORTAL_ID;
                            $record['total_start_term_price'] = 0;
                            $record['start_term_price'] = 0;
                            DB::insert('wh_start_term_remain',$record); 
                        }
                    }
                    else
                    {
                        $total_amount +=System::calculate_number($value['total']);
                        $order_detail = array('quantity_import'=>$quantity_used);
                        unset($detail['warehouse_id']);
                        DB::insert('handover_invoice_detail',$detail);
                        $order_detail['status'] =2;
                        DB::update('pc_order_detail',$order_detail,'id='.$value['pc_order_detail_id']);
                    }
                }
                else
                {
                    if(Url::get('action')=='import')
                    {
                        //DB::update('wh_invoice_detail',$detail,'id='.$value['id']);
                    }   
                    else
                    {
                        unset($detail['warehouse_id']);
                        DB::update('wh_invoice_detail',$detail,'id='.$value['id']);
                    }
                }
            }
            if(Url::get('cmd')=='add')
            {
                $item = DB::fetch("SELECT * FROM pc_order WHERE id=".Url::get('order_id'));
                if($item['total']>$total_amount)
                {
                    if(Url::get('action')=='import')
                    {
                        $order['import_status'] = 'APART';
                    }
                    else
                    {
                        $order['import_status'] = 'APART_HANOVER';
                    }
                }
                else
                {
                    $order['import_status'] = 'COMPLETE';
                }
                $order['total'] =$item['total'] - $total_amount;
                
                DB::update('pc_order',$order,'id='. Url::get('order_id'));
            }
            Url::redirect('pc_import_warehouse_order',array('cmd'=>'list','action'=>Url::get('action')));
        }
    }
    function draw()
    {
       require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
       $this->map = array();
       
       if(Url::get('cmd')=='add')
       {
            /*trung:add ware */
            $get_ware=DB::fetch_all('
                    select warehouse.*
                    from warehouse
                    where warehouse.id=\''.Url::get('warehouse_ids').'\'
            ');
            foreach($get_ware as $key=>$value)
            {
                $this->map['warehouse_id11'] = $value['id'];
                $this->map['warehouse_name22'] = $value['name'];
                $this->map['warehouse_code33'] = $value['code'];
            }
            /*trung:add ware */
            $order = DB::fetch_all(" SELECT
                                pc_order_detail.*,
                                pc_order_detail.id as pc_order_detail_id,
                                pc_order.code,
                                pc_order.name,
                                pc_order.description,
                                pc_order.pc_supplier_id,
                                pc_order.number_contract,
                                supplier.name as pc_supplier_name,
                                warehouse.id as warehouse_id,
                                warehouse.name as warehouse_name,
                                warehouse.code as warehouse_code,
                                product.name_".portal::language()." as product_name,
                                product.type as product_type,
                                product_category.name as product_category_name,
                                unit.name_".portal::language()." as unit_name,
                                department.name_".portal::language()." as department_name,
                                portal_department.id as portal_department
                                FROM
                                pc_order_detail
                                inner join pc_order on pc_order_detail.pc_order_id=pc_order.id
                                inner join product on product.id=pc_order_detail.product_id
                                left join product_category on product_category.id=product.category_id
                                inner join unit on product.unit_id=unit.id
                                inner join supplier on supplier.id=pc_order.pc_supplier_id
                                inner join portal_department on portal_department.id=pc_order_detail.portal_department_id
                                inner join department on portal_department.department_code = department.code
                                left join warehouse on portal_department.warehouse_pc_id=warehouse.id
                                WHERE
                                pc_order_detail.id in (".Url::get('ids').") AND pc_order.id = ".Url::get('order_id')."
                                ORDER BY
                                    pc_order_detail.id 
                                ");
            $this->map['total_amount'] = 0;
            //System::debug($order);
            foreach($order as $key=>$value)
            {
                //if($value['warehouse_id']!='')
                    //$product_remain = get_remain_products($value['warehouse_id']);
                //$value['wh_remain'] = isset($product_remain[$value['product_id']])?$product_remain[$value['product_id']]['remain_number']:0;
                
                $this->map['bill_number'] = '';
                $this->map['supplier_id'] = $value['pc_supplier_id'];
                $this->map['supplier_name'] = $value['pc_supplier_name'];
                $this->map['receiver_name'] = DB::fetch("SELECT full_name from party where user_id='".User::id()."'","full_name");
                $this->map['warehouse_id'] = $value['warehouse_id'];
                $this->map['warehouse_name'] = $value['warehouse_name'];
                $this->map['warehouse_code'] = $value['warehouse_code'];
                $this->map['deliver_name'] = "";
                $this->map['create_date'] = date('d/m/Y');
                
                $this->map['description'] = $value['description'];
                $this->map['invoice_number'] = $value['code'];
                
                //$value['total'] = $value['quantity'] * $value['price'];
                if(empty($value['quantity_import']))
                {
                    $value['quantity_import'] =0;
                }
                if(empty($value['tax_percent']))
                {
                    $value['tax_percent'] =0;
                }
                $value['quantity_remain'] = $value['quantity'] - $value['quantity_import'];
                /*$value['tax_amount'] = $value['price']*$value['tax_percent']*0.01;
                $value['tax_amount'] = $value['tax_amount']*$value['quantity_remain'];

                $value['total'] = $value['price']*$value['quantity_remain'];
                $value['total'] += $value['tax_amount']; */
                $this->map['total_amount'] +=round($value['total']);
                //$value['tax_amount'] = System::display_number($value['tax_amount']);
                //$value['total'] = System::display_number($value['total']);
                if($value['quantity'] <1 && $value['quantity'] >0)
                {
                     $value['quantity'] = '0' . $value['quantity'];
                }
                if($value['quantity_import'] <1 && $value['quantity_import']>0)
                {
                     $value['quantity_import'] = '0' . $value['quantity_import'];
                }
                
                $this->map['items'][$key] = $value;
                
                //System::debug($this->map);
            }

       }
       else
       {
            if(Url::get('action')=='import')
            {
                $wh = DB::fetch('
                                SELECT
                                    wh_invoice.id,
                                    wh_invoice.bill_number,
                                    wh_invoice.supplier_id,
                                    supplier.name as supplier_name,
                                    wh_invoice.receiver_name,
                                    wh_invoice.warehouse_id,
                                    warehouse.name as warehouse_name,
                                    warehouse.code as warehouse_code,
                                    wh_invoice.deliver_name,
                                    TO_CHAR(wh_invoice.create_date,\'DD/MM/YYYY\') as create_date,
                                    wh_invoice.note as description,
                                    wh_invoice.total_amount,
                                    wh_invoice.invoice_number
                                FROM
                                    wh_invoice
                                    inner join supplier on supplier.id=wh_invoice.supplier_id
                                    inner join warehouse on warehouse.id=wh_invoice.warehouse_id
                                WHERE
                                    wh_invoice.id='.Url::get('id').'
                                ');
                $this->map = $wh;
            }
            else
            {
                $ho = DB::fetch('
                                SELECT
                                    handover_invoice.id,
                                    handover_invoice.bill_number,
                                    handover_invoice.supplier_id,
                                    supplier.name as supplier_name,
                                    handover_invoice.receiver_name,
                                    \'\' as warehouse_id,
                                    \'\' as warehouse_name,
                                    \'\' as warehouse_code,
                                    handover_invoice.deliver_name,
                                    TO_CHAR(handover_invoice.create_date,\'DD/MM/YYYY\') as create_date,
                                    handover_invoice.note as description,
                                    handover_invoice.total_amount,
                                    handover_invoice.invoice_number
                                FROM
                                    handover_invoice
                                    inner join supplier on supplier.id=handover_invoice.supplier_id
                                WHERE
                                    handover_invoice.id='.Url::get('id').'
                                ');
                $this->map = $ho;
            }
            if(Url::get('action')=='import')
            {
                //$this->map['total_amount'] = 0;
                $items = DB::fetch_all("
                                        SELECT
                                            wh_invoice_detail.id,
                                            wh_invoice_detail.warehouse_id,
                                            wh_invoice_detail.pc_order_detail_id,
                                            wh_invoice_detail.product_id,
                                            product.name_".portal::language()." as product_name,
                                            product.type as product_type,
                                            unit.name_".portal::language()." as unit_name,
                                            product_category.name as product_category_name,
                                            wh_invoice_detail.num as quantity,
                                            wh_invoice_detail.price,
                                            wh_invoice_detail.payment_price as total,
                                            pc_order_detail.tax_percent
                                        FROM
                                            wh_invoice_detail
                                            inner join product on product.id=wh_invoice_detail.product_id
                                            INNER JOIN pc_order_detail ON pc_order_detail.id = wh_invoice_detail.pc_order_detail_id
                                            left join product_category on product_category.id=product.category_id
                                            inner join unit on product.unit_id=unit.id
                                        WHERE
                                            wh_invoice_detail.invoice_id=".Url::get('id')."
                                        ");
                foreach($items as $id=>$content)
                {
                    if($content['warehouse_id']!='')
                        //$product_remain = get_remain_products($content['warehouse_id']);
                    //$items[$id]['wh_remain'] = isset($product_remain[$content['product_id']])?$product_remain[$content['product_id']]['remain_number']:0;
                    
                    //if($items[$id]['wh_remain']>$content['quantity'])
                        //$items[$id]['wh_remain'] -= $content['quantity'];
                    if(empty($content['tax_percent']))
                        $content['tax_percent'] = 0;
                    $items[$id]['tax_percent'] = $content['tax_percent'];
                    $items[$id]['tax_amount'] = round($content['total'] - $content['total']/(1+$content['tax_percent']/100));
                    //$this->map['total_amount'] += $content['total'];
                    if($content['quantity'] <1 && $content['quantity']>0)
                    {
                         $items[$id]['quantity'] = '0' . $content['quantity'];
                    }
                }
                //System::debug($items);
                $this->map['items'] = $items;
            }
            else
            {
                $items = DB::fetch_all("
                                        SELECT
                                            handover_invoice_detail.id,
                                            '' as warehouse_id,
                                            handover_invoice_detail.pc_order_detail_id,
                                            handover_invoice_detail.product_id,
                                            product.name_".portal::language()." as product_name,
                                            product.type as product_type,
                                            unit.name_".portal::language()." as unit_name,
                                            product_category.name as product_category_name,
                                            handover_invoice_detail.num as quantity,
                                            handover_invoice_detail.price,
                                            handover_invoice_detail.payment_price as total,
                                            '' as wh_remain
                                        FROM
                                            handover_invoice_detail
                                            inner join product on product.id=handover_invoice_detail.product_id
                                            left join product_category on product_category.id=product.category_id
                                            inner join unit on product.unit_id=unit.id
                                            INNER JOIN pc_order_detail ON pc_order_detail.id = handover_invoice_detail.pc_order_detail_id
                                        WHERE
                                            handover_invoice_detail.invoice_id=".Url::get('id')."
                                        ");
                foreach($items as $id=>$content)
                {
                    
                    if(empty($content['tax_percent']))
                        $content['tax_percent'] = 0;
                    $items[$id]['tax_percent'] = $content['tax_percent'];
                    $items[$id]['tax_amount'] = $content['price']*$content['tax_percent']*0.01;
                    if($content['quantity'] <1 && $content['quantity'] >0)
                    {
                         $items[$id]['quantity'] = '0' . $content['quantity'];
                    }
                }
                $this->map['items'] = $items;
            }
       }
       //System::debug($this->map);
       $this->parse_layout('edit',$this->map);  
    }
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
    function get_bill_number($type,$warehouse_id,$warehouse_code)
    {
        $count_id = DB::fetch("SELECT count(id) as count FROM wh_invoice WHERE type='".$type."' AND warehouse_id=".$warehouse_id."",'count');
        if($count_id=='')
            $count_id = 1;
        else
            $count_id ++;
        
        return $type=='IMPORT'?"PN-".$warehouse_code.$count_id:"PX-".$warehouse_code.$count_id;
    }
    function get_bill_number_handover()
    {
        $count_id = DB::fetch("SELECT count(id) as count FROM handover_invoice",'count');
        if($count_id=='')
            $count_id = 1;
        else
            $count_id ++;
        
        return "HANDOVER".$count_id;
    }
    
}
?>
