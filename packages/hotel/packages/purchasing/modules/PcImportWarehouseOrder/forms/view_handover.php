<?php
class ViewHandoverPcImportWarehouseOrderForm extends Form
{
    function ViewHandoverPcImportWarehouseOrderForm()
    {
        Form::Form('ViewHandoverPcImportWarehouseOrderForm');
        $this->link_css(Portal::template('warehouse').'/css/invoice.css');
    }
    function draw()
    {
        require_once 'packages/core/includes/utils/currency.php';
        $this->map = array();
        $this->map['total_debit'] = 0;
        $this->map['total_amount'] = 0;
        $this->map['total_discount'] = 0;
        $item = DB::select('handover_invoice','id ='.Url::iget('id'));
        $this->map['department_name']=false;

        if($item)
        {
            $item['create_date'] = Date_Time::convert_orc_date_to_date($item['create_date'],'/');
            $arr = explode('/',$item['create_date']);
            $this->map['year'] = $arr[2];
            $this->map['month'] = $arr[1];
            $this->map['day'] = $arr[0];

            $this->map['supplier_name'] = DB::fetch('SELECT id,name FROM supplier WHERE id=\''.$item['supplier_id'].'\'','name');
            $this->map['supplier_address'] = DB::fetch('SELECT id,address FROM supplier WHERE id=\''.$item['supplier_id'].'\'','address');
            
            $products = DB::fetch_all('
                SELECT
                    handover_invoice_detail.*,
                    product.name_'.Portal::language().' as name,unit.name_'.Portal::language().' as unit_name,
                    department.name_'.Portal::language().' as department_name
                FROM
                    handover_invoice_detail
                    INNER JOIN product ON product.id = handover_invoice_detail.product_id
                    INNER JOIN unit ON unit.id = product.unit_id
                    INNER JOIN product_category ON product_category.id = product.category_id
                    INNER JOIN pc_order_detail ON pc_order_detail.id = handover_invoice_detail.pc_order_detail_id
                    INNER JOIN portal_department on portal_department.id=pc_order_detail.portal_department_id
                    INNER JOIN department on department.code=portal_department.department_code
                WHERE
                    handover_invoice_detail.invoice_id='.Url::iget('id'));
            $i=1;
            
            foreach($products as $k=>$v)
            {
                $products[$k]['i'] = $i++;
                if ($products[$k]['payment_price'] == 0)
                {
                    $payment_amount = $v['price']*$v['num'];
                }
                else
                {
                    $payment_amount = $v['payment_price'];
                }
                $products[$k]['payment_amount'] = System::display_number($payment_amount);
                $products[$k]['price'] = System::display_number($v['price']);
                $products[$k]['number'] = $v['num'];
                $products[$k]['payment_price'] = System::display_number($v['payment_price']);
                if($this->map['department_name']==false)
                    $this->map['department_name']= $v['department_name'];
                $this->map['total_amount'] += $payment_amount;
                if($v['num']==0)
                    unset($products[$k]);
            }

            $this->map['total_amount'] = System::display_number($this->map['total_amount']);
            $this->map['products'] = $products;

        }

        $currency_by_letter = round($this->map['total_amount'],2);
        
        $this->map['total_by_letter'] = currency_to_text($currency_by_letter);
        $this->map +=$item;
        
        $this->parse_layout('view_handover',$this->map);
    }   
}
?>