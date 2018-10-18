<?php
class DetailInvoicePurchasesInvoiceForm extends Form
{
	function DetailInvoicePurchasesInvoiceForm()
	{
		Form::Form('DetailInvoicePurchasesInvoiceForm');
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
       $stt = 1;
       $this->map = array();
       $group = DB::fetch_all('SELECT purchases_invoice.*,party.name_'.Portal::language().' as creater from purchases_invoice inner join party on party.user_id=purchases_invoice.creater WHERE purchases_invoice.id='.Url::get('id').' ORDER BY create_time');
        foreach($group as $id=>$content)
        {
            $group[$id]['create_time'] = date('H:i d/m/Y',$content['create_time']);
            $group[$id]['confirm_time'] = date('H:i d/m/Y',$content['confirm_time']);
            $group[$id]['confirm_user_name'] = DB::fetch('SELECT party.name_'.Portal::language().' as name FROM party WHERE party.user_id=\''.$content['confirm_user'].'\'','name');
            $group[$id]['quantity'] = 0;
            $group[$id]['total'] = 0;
            $group[$id]['child'] = DB::fetch_all("
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
            foreach($group[$id]['child'] as $name=>$record)
            {
                $group[$id]['child'][$name]['total'] = $record['quantity']*$record['price'];
                $group[$id]['quantity'] += $record['quantity'];
                $group[$id]['total'] += $record['price'] * $record['quantity'];
                $group[$id]['product_code'] = $record['product_code'];
                $group[$id]['product_name'] = $record['product_name'];
            }
            $this->map += $group[$id];
        }
       
       //System::debug($this->map);
       $this->parse_layout('detail_invoice',$this->map);
	}
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
?>
