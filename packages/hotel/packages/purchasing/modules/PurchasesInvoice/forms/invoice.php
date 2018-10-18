<?php
class InvoicePurchasesInvoiceForm extends Form
{
	function InvoicePurchasesInvoiceForm()
	{
		Form::Form('InvoicePurchasesInvoiceForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function on_submit()
    {
        //System::debug($_REQUEST); exit();
        if(isset($_REQUEST['confirm']))
        {
            $time = Date_Time::to_time(date('d/m/Y'))+$this->calc_time(date('H:i'));
            DB::update("purchases_invoice",array("status"=>'CONFIRM','confirm_user'=>User::id(),'confirm_time'=>$time),'id='.$_REQUEST['id']);
        }
        if(isset($_REQUEST['product']))
        {
            foreach($_REQUEST['product'] as $key=>$value)
            {
                if(isset($value['delete']))
                {
                    DB::delete("purchases_invoice_detail","id=".$key);
                }
                else
                {
                    DB::update("purchases_invoice_detail",array("price"=>System::calculate_number($value['price']),'quantity'=>System::calculate_number($value['quantity'])),"id=".$key);
                    
                }
            }
        }
        if(isset($_REQUEST['view']))
            Url::redirect('purchases_invoice',array('cmd'=>'detail','invoice'=>'invoice','id'=>$_REQUEST['id']));
        elseif(isset($_REQUEST['warehouse']))
            Url::redirect('warehouse_invoice',array('cmd'=>'add','type'=>'IMPORT','choose_warehouse'=>1,'purchases_invoice_id'=>$_REQUEST['id']));
	}
	function draw()
	{
	   $this->map = array();
       $invoice = DB::fetch("SELECT purchases_invoice.*,supplier.name as supplier_name FROM purchases_invoice left join supplier on supplier.code=purchases_invoice.supplier_code WHERE purchases_invoice.id=".$_REQUEST['id']);
       $invoice['creater'] = DB::fetch("SELECT party.name_".Portal::language()." as name FROM party WHERE user_id='".$invoice['creater']."'",'name');
       $invoice['confirm_user'] = DB::fetch("SELECT party.name_".Portal::language()." as name FROM party WHERE user_id='".$invoice['confirm_user']."'",'name');
       $invoice['create_time'] = date('H:i d/m/Y',$invoice['create_time']);
       $invoice['confirm_time'] = date('H:i d/m/Y',$invoice['confirm_time']);
       $this->map = $invoice;
       $this->map['total'] = 0;
       $product = DB::fetch_all("
                                SELECT
                                    purchases_invoice_detail.id,
                                    purchases_invoice_detail.quantity,
                                    purchases_invoice_detail.price,
                                    purchases_invoice_detail.price*purchases_invoice_detail.quantity as total,
                                    purchases_detail.note,
                                    product.name_".Portal::language()." as product_name,
                                    unit.name_".Portal::language()." as unit_name,
                                    product_category.name as product_category_name,
                                    party.name_".Portal::language()." as user_name,
                                    party.description_1 as department,
                                    purchases_proposed.id as dx_id,
                                    purchases_proposed.create_time
                                FROM
                                    purchases_invoice_detail
                                    left join supplier on supplier.code = purchases_invoice_detail.supplier_code
                                    inner join purchases_detail on purchases_detail.id=purchases_invoice_detail.purchases_detail_id
                                    inner join purchases_proposed on purchases_proposed.id=purchases_detail.purchases_id
                                    inner join party on party.user_id=purchases_proposed.creater
                                    inner join product on product.id=purchases_detail.product_code
                                    inner join unit on unit.id=product.unit_id
                                    inner join product_category on product_category.id=product.category_id
                                WHERE
                                    purchases_invoice_detail.purchases_invoice_id=".$invoice['id']."
                                ORDER BY
                                    supplier.name,purchases_invoice_detail.id
                                ");
       $stt=1;
       foreach($product as $key=>$value)
       {
            $this->map['total'] += $value['total'];
            $product[$key]['create_time'] = date('H:i d/m/Y',$value['create_time']);
            $product[$key]['stt'] = $stt++; 
       }
       $this->map['product'] = $product;
       //System::debug($this->map);
       $this->parse_layout('invoice',$this->map);
       
	}
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
?>
