<?php
class InvoicePurchasesDetailForm extends Form
{
	function InvoicePurchasesDetailForm()
	{
		Form::Form('InvoicePurchasesDetailForm');
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
        $check = false;
        foreach($_REQUEST['non'] as $key=>$value)
        {
            if(isset($value['check_box']))
            {
                $check=true;
                break;
            }
        }
        if($check==true)
        {
            $group = array(
                            'creater'=>User::id(),
                            'create_time'=>(Date_Time::to_time(date('d/m/Y'))+$this->calc_time(date('H:i'))),
                            'status'=>'CONFIRMING',
                            'note'=>''
                            );
            $group_id = DB::insert('purchases_group_invoice',$group);
            foreach($_REQUEST['non'] as $id=>$content)
            {
                if(isset($content['check_box']))
                {
                    $invoice = array(
                                        'group_id'=>$group_id,
                                        'creater'=>User::id(),
                                        'create_time'=>(Date_Time::to_time(date('d/m/Y'))+$this->calc_time(date('H:i'))),
                                        'status'=>'CONFIRMING',
                                        'note'=>'',
                                        'price'=>System::calculate_number($content['price'])
                                    );
                    //$invoice_id = DB::insert('purchases_invoice',$invoice);
                    foreach($content['detail'] as $code=>$recode)
                    {
                        if(isset($recode['id']))
                        {
                            $detail = array(
                                            'purchases_detail_id'=>$code,
                                            'quantity'=>System::calculate_number($recode['quantity']),
                                            'purchases_invoice_id'=>'',
                                            'supplier_code'=>$recode['supplier_code'],
                                            'purchases_group_invoice_id'=>$group_id,
                                            'price'=>System::calculate_number($recode['price'])
                                            );
                            DB::insert('purchases_invoice_detail',$detail);
                        }
                    }
                }
            }
            Url::redirect('purchases_invoice',array());
        }
        
	}
	function draw()
	{
	    
        $list_invoice = array();
        $list_non_invoice = array();
        $non_invoice = array();
        $this->map = array();
        $sql = '
			SELECT
                purchases_detail.*,
                product.id as product_code,
                product.name_'.Portal::language().' as product_name,
                unit.name_'.Portal::language().' as unit_name,
                product_category.name as category_name,
                product_category.id as product_category_id,
                unit.id as unit_id
            FROM
                purchases_detail
                inner join product on product.id=purchases_detail.product_code
                inner join unit on unit.id=product.unit_id
                inner join product_category on product_category.id=product.category_id
            WHERE
                1=1
            ORDER BY
                purchases_detail.id
		';
        $all_product = DB::fetch_all($sql);
        $orcl = '
                SELECT
                purchases_invoice_detail.*,
                product.id as product_code,
                product.name_'.Portal::language().' as product_name,
                unit.name_'.Portal::language().' as unit_name,
                product_category.name as category_name,
                product_category.id as product_category_id,
                unit.id as unit_id
            FROM
                purchases_invoice_detail
                inner join purchases_detail on purchases_detail.id=purchases_invoice_detail.purchases_detail_id
                inner join product on product.id=purchases_detail.product_code
                inner join unit on unit.id=product.unit_id
                inner join product_category on product_category.id=product.category_id
            WHERE
                1=1
            ORDER BY
                purchases_invoice_detail.id
                ';
        $product_invoice = DB::fetch_all($orcl);
        $stt = 0;
        foreach($product_invoice as $id=>$content)
        {
            if(!isset($list_invoice[$content['purchases_detail_id']]))
                $list_invoice[$content['purchases_detail_id']] = $content;
            else
                $list_invoice[$content['purchases_detail_id']]['quantity'] += $content['quantity']; 
        }
        foreach($all_product as $key=>$value)
        {
            if(isset($list_invoice[$value['id']]))
            {
                if(($all_product[$key]['quantity']-$list_invoice[$value['id']]['quantity'])>0)
                {
                    $list_non_invoice[$key] = $value;
                    $list_non_invoice[$key]['quantity'] = $all_product[$key]['quantity']-$list_invoice[$value['id']]['quantity'];
                }
            }
            else
            {
                $list_non_invoice[$key] = $value;
            }
            if(isset($list_non_invoice[$key]))
            {
                if(!isset($non_invoice[$list_non_invoice[$key]['product_code']]))
                {
                    $stt++;
                    $non_invoice[$list_non_invoice[$key]['product_code']]['id'] = 'non_'.$stt;
                    $non_invoice[$list_non_invoice[$key]['product_code']]['stt'] = $stt;
                    $non_invoice[$list_non_invoice[$key]['product_code']]['product_code'] = $list_non_invoice[$key]['product_code'];
                    $non_invoice[$list_non_invoice[$key]['product_code']]['product_name'] = $list_non_invoice[$key]['product_name'];
                    $non_invoice[$list_non_invoice[$key]['product_code']]['quantity'] = $list_non_invoice[$key]['quantity'];
                    $non_invoice[$list_non_invoice[$key]['product_code']]['child'][$list_non_invoice[$key]['id']] = $list_non_invoice[$key];
                }
                else
                {
                    $non_invoice[$list_non_invoice[$key]['product_code']]['quantity'] += $list_non_invoice[$key]['quantity'];
                    $non_invoice[$list_non_invoice[$key]['product_code']]['child'][$list_non_invoice[$key]['id']] = $list_non_invoice[$key];
                }
            }
        }
        $this->map['non_invoice_list'] = json_encode($non_invoice);
        $supplier = DB::fetch_all("SELECT * FROM supplier");
        $this->map['supplier_list'] = '';
        foreach($supplier as $code=>$recode)
        {
            $this->map['supplier_list'] .= '<option value="'.$recode['code'].'" >'.$recode['name'].'</option>';
        }
        $this->parse_layout('invoice',array('non_invoice'=>$non_invoice)+$this->map);
	}
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
?>
