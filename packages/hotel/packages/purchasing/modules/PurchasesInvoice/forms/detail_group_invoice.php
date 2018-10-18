<?php
class DetailGroupInvoicePurchasesInvoiceForm extends Form
{
	function DetailGroupInvoicePurchasesInvoiceForm()
	{
		Form::Form('DetailGroupInvoicePurchasesInvoiceForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
	{
        $sql = "SELECT purchases_group_invoice.*,party.name_".Portal::language()." as user_name from purchases_group_invoice inner join party on party.user_id=purchases_group_invoice.creater Where purchases_group_invoice.id=".Url::get('id')." ORDER BY create_time";
       $group = DB::fetch_all($sql);
       $stt = 1;
       $this->map = array();
       foreach($group as $key=>$value)
       {
            $group[$key]['stt'] = $stt++;
            $group[$key]['total'] = 0;
            $group[$key]['quantity'] = 0;
            $group[$key]['create_time'] = date('H:i d/m/Y',$value['create_time']);
            $group[$key]['confirm_time'] = date('H:i d/m/Y',$value['confirm_time']);
            $group[$key]['confirm_user_name'] = DB::fetch('SELECT party.name_'.Portal::language().' as name FROM party WHERE party.user_id=\''.$value['confirm_user'].'\'','name');
            $group[$key]['child'] = DB::fetch_all('SELECT purchases_invoice.*,party.name_'.Portal::language().' as user_name from purchases_invoice inner join party on party.user_id=purchases_invoice.creater WHERE purchases_invoice.group_id='.$value['id'].' ORDER BY create_time');
            foreach($group[$key]['child'] as $id=>$content)
            {
                $group[$key]['child'][$id]['create_time'] = date('H:i d/m/Y',$content['create_time']);
                $group[$key]['child'][$id]['quantity'] = 0;
                $group[$key]['child'][$id]['total'] = 0;
                $group[$key]['child'][$id]['child'] = DB::fetch_all("
                                                                    SELECT
                                                                        purchases_invoice_detail.id,
                                                                        purchases_invoice_detail.quantity,
                                                                        purchases_invoice_detail.supplier_code,
                                                                        purchases_invoice_detail.note,
                                                                        purchases_invoice_detail.price,
                                                                        purchases_detail.purchases_id,
                                                                        purchases_detail.product_code,
                                                                        product.name_".Portal::language()." as product_name,
                                                                        unit.id as unit_id,
                                                                        unit.name_".Portal::language()." as unit_name,
                                                                        product_category.name as product_category_name,
                                                                        supplier.name as supplier_name
                                                                    FROM
                                                                        purchases_invoice_detail
                                                                        left join supplier on supplier.code = purchases_invoice_detail.supplier_code
                                                                        inner join purchases_detail on purchases_detail.id=purchases_invoice_detail.purchases_detail_id
                                                                        inner join product on product.id=purchases_detail.product_code
                                                                        inner join unit on unit.id=product.unit_id
                                                                        inner join product_category on product_category.id=product.category_id
                                                                    WHERE
                                                                        purchases_invoice_detail.purchases_invoice_id=".$content['id']."
                                                                    ORDER BY
                                                                        purchases_invoice_detail.id
                                                                    ");
                foreach($group[$key]['child'][$id]['child'] as $name=>$record)
                {
                    $group[$key]['child'][$id]['child'][$name]['total'] = $record['quantity']*$group[$key]['child'][$id]['child'][$name]['price'];
                    //$group[$key]['child'][$id]['child'][$name]['price'] = $group[$key]['child'][$id]['price'];
                    $group[$key]['total'] += $group[$key]['child'][$id]['child'][$name]['price'] * $record['quantity'];
                    $group[$key]['quantity'] += $record['quantity'];
                    $group[$key]['child'][$id]['quantity'] += $record['quantity'];
                    $group[$key]['child'][$id]['total'] += $group[$key]['child'][$id]['child'][$name]['price'] * $record['quantity'];
                    $group[$key]['child'][$id]['product_code'] = $record['product_code'];
                    $group[$key]['child'][$id]['product_name'] = $record['product_name'];
                }
            }
            $this->map += $group[$key];
       }
       //System::debug($this->map);
       $this->parse_layout('detail_group_invoice',$this->map);
	}
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
?>
