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
            $path_file ='';
            if(Url::get('status')>=3 AND isset($_FILES['file']['name']))
            {
                $path = ROOT_PATH."packages/hotel/packages/purchasing/includes/file_contract/";
                $tmp_name = $_FILES['file']['tmp_name'];
                $name = $_FILES['file']['name'];
                if(!is_dir($path))
           		{
          			mkdir($path); 
           		}
                $path_file = "packages/hotel/packages/purchasing/includes/file_contract/".$name;
                move_uploaded_file($tmp_name,$path.$name);
                $order['file_contract'] = $path_file;
            }
            $order['number_contract'] = isset($_REQUEST['number_contract'])?$_REQUEST['number_contract']:'';
            $total_order = 0;
            foreach($_REQUEST['mi_list_product'] as $key=>$value)
            {
                $total_order += System::calculate_number($value['total']);
                $detail = array(
                                'quantity'=>System::calculate_number($value['quantity']),
                                'price'=>System::calculate_number($value['price']),
                                'tax_percent'=>System::calculate_number($value['tax_percent']),
                                'tax_amount'=>System::calculate_number($value['tax_amount']),
                                'total'=>System::calculate_number($value['total'])
                                );
                DB::update('pc_order_detail',$detail,'id='.$value['id']);
            }
            
            $order += array('total'=>$total_order,'last_edit_time'=>time(),'last_edit_user'=>User::id());
            if(Url::get('act')=='confirm' AND Url::get('status')==1)
            {
                $order['status'] = 2;
                $order['person_confirm'] = Url::get('person_confirm');
            }
            elseif(Url::get('act')=='succefull' AND Url::get('status')==2)
            {
                $order['status'] = 3;
                $order['person_confirm_1'] = Url::get('person_confirm');
            }
            elseif(Url::get('act')=='finish' AND Url::get('status')==3)
            {
                
                $order['status'] = 4;
            }
            elseif(Url::get('act')=='cancel')
            {
                $order['status'] = 0;
                $order['cancel_user'] = User::id();
                $order['time_cancel'] = time();
                $order['note_cancel'] = Url::get('note_cancel');
            }
            if(Url::get('status')==3 && (Url::get('act')=='save' || Url::get('act')=='finish'))
            {
                $order['payment_type_id'] = Url::get('payment_type_id');
                
            }
            
            $order +=array('name'=>Url::get('order_name'),'description'=>Url::get('description'));
            DB::update('pc_order',$order,'id='.Url::get('id'));
            Url::redirect('pc_order',array('cmd'=>'list','status'=>Url::get('status')));
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
                                        portal_department.warehouse_pc_id,
                                        pc_order.description_product
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
                                        pc_order_detail.id 
                                    ");
            $stt = 1;
            $product_res = false;
            foreach($detail as $key=>$value)
            {
                $detail[$key]['stt'] = $stt++;
                if($value['warehouse_pc_id']!='')
                //$product_remain = get_remain_products($value['warehouse_pc_id']);
                //$detail[$key]['wh_remain'] = isset($product_remain[$value['product_id']])?$product_remain[$value['product_id']]['remain_number']:0;
                $detail[$key]['wh_remain'] = 0;
                $detail[$key]['price'] = System::display_number($value['price']);
                if(empty($value['tax_percent']))
                {
                    $value['tax_percent'] =0;
                }
                $detail[$key]['tax_percent'] = $value['tax_percent'];
                $detail[$key]['tax_amount'] = $value['price']*$value['tax_percent']*0.01;
                $detail[$key]['tax_amount'] = $detail[$key]['tax_amount']*$value['quantity'];

                if($this->map['status'] != 3)
                {
                    $detail[$key]['total'] = $value['price']*$value['quantity']; 
                    $detail[$key]['total'] +=$detail[$key]['tax_amount'];
                }
                $detail[$key]['tax_amount'] = System::display_number(round($detail[$key]['tax_amount']));
                $detail[$key]['total'] = System::display_number(round($detail[$key]['total']));
                if($value['delivery_time']!='')
                {
                    $detail[$key]['delivery_date'] = date('d/m/Y',$value['delivery_time']);
                }
                else
                {
                    $detail[$key]['delivery_date'] = '';
                }
                if($product_res!=$value['product_id'])
                {
                    $this->map['quantity'] ++;    
                }
                if($value['quantity'] <1 && $value['quantity'] >0)
                {
                    $detail[$key]['quantity'] = '0' . $value['quantity'];
                } 
                
            }
            
            $this->map['mi_list_product'] = $detail;
            //Lay ra thong tin cac h�nh thuc thanh toan 
            $sql ="SELECT * FROM payment_type WHERE def_code in('CASH','DEBIT','BANK','CREDIT_CARD')";
            $payment_type = DB::fetch_all($sql);

            $option_payment_types = '';
            foreach($payment_type as $row)
            {
                if(isset($order['payment_type_id']) && $order['payment_type_id']==$row['def_code'])
                {
                    $option_payment_types .='<option value="'.$row['def_code'].'" selected="selected" >'.$row['name_1'].'</option>';
                }
                else
                {
                    $option_payment_types .='<option value="'.$row['def_code'].'" >'.$row['name_1'].'</option>';
                }
            }
            $this->map['option_payment_types'] = $option_payment_types;
       }
       else
       {
            $this->map['no_data'] = portal::language('no_record');
       }
       $user_data = Session::get('user_data');
       $this->map['person_confirm'] = isset($user_data['full_name'])?$user_data['full_name']:Session::get('user_id'); 
       
       if($this->map['status'] == 3)
       {
            //System::debug($detail);
            $this->parse_layout('edit_new',$this->map);        
       }else
       {
            $this->parse_layout('edit',$this->map);
       }
    }
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
    
}
?>
