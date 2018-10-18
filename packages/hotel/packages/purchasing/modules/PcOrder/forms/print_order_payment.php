<?php
class PrintOderPaymentPcOrderForm extends Form
{
	function PrintOderPaymentPcOrderForm()
	{
		Form::Form('PrintOderPaymentPcOrderForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
	}
	function draw()
	{
	   require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
       require_once 'packages/core/includes/utils/currency.php';
	   $this->map = array();
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
                                    pc_order.id = ".Url::get('id')."
                                ORDER BY
                                    product.id 
                                ");
        $stt = 1;
        foreach($detail as $key=>$value)
        {
            $detail[$key]['stt'] = $stt++;
            if($value['warehouse_pc_id']!='')
            $product_remain = get_remain_products($value['warehouse_pc_id']);
            $detail[$key]['wh_remain'] = isset($product_remain[$value['product_id']])?$product_remain[$value['product_id']]['remain_number']:0;
            $detail[$key]['price'] = System::display_number($value['price']);
            $detail[$key]['total'] = System::display_number($value['price']*$value['quantity']);  
            $this->map['quantity'] ++;
        }
        $this->map['mi_list_product'] = $detail;
        $this->map['total_in_word'] = currency_to_text($this->map['total']);
        $this->map['user_name'] = DB::fetch("SELECT full_name FROM party WHERE user_id='".User::id()."'",'full_name');
        $this->parse_layout('print_order_payment',$this->map);
	}
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
    
}
?>
