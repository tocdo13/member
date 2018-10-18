<?php
class ListPurchasesInvoiceForm extends Form
{
	function ListPurchasesInvoiceForm()
	{
		Form::Form('ListPurchasesInvoiceForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_css('packages/core/includes/js/custom_content_scroller/jquery.mCustomScrollbar.css');
        $this->link_js('packages/core/includes/js/custom_content_scroller/jquery.mCustomScrollbar.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
    function on_submit()
    {
        if( isset($_REQUEST['create']) )
        {
            if( sizeof($_REQUEST['create'])>0 )
            {
                $time = Date_Time::to_time(date('d/m/Y'))+$this->calc_time(date('H:i'));
                foreach($_REQUEST['create'] as $key=>$value)
                {
                    $group_id = $key;
                    $status = DB::fetch("SELECT status from purchases_group_invoice Where id=".$group_id,'status');
                    //echo $status;
                    $supplier_code = $_REQUEST['supplier_list_'.$key]=='OTHER'?'':$_REQUEST['supplier_list_'.$key];
                    $invoice = DB::insert( "purchases_invoice",array("group_id"=>$group_id,"creater"=>User::id(),"create_time"=>$time,"status"=>$status,"supplier_code"=>$supplier_code) );
                    //System::debug($_REQUEST['create'][$key]); 
                    foreach($value as $id=>$content)
                    {
                        foreach($content as $code=>$name)
                        {
                            DB::update("purchases_invoice_detail",array('purchases_invoice_id'=>$invoice),'id='.$code);
                        }
                    }
                    Url::redirect('purchases_invoice',array("cmd"=>"invoice","id"=>$invoice));
                }
            }
        }
    }
	function draw()
	{
	   $cond = '1=1';
	   if(Url::get('creater'))
       {
            $cond .= ' AND (purchases_group_invoice.creater=\''.Url::get('creater').'\')';
       }
       if(Url::get('from_date'))
       {
            $cond .= ' AND (purchases_group_invoice.create_time>='.(Date_Time::to_time(Url::get('from_date'))+$this->calc_time('00:00')).')';
       }
       if(Url::get('to_date'))
       {
            $cond .= ' AND (purchases_group_invoice.create_time<='.(Date_Time::to_time(Url::get('to_date'))+$this->calc_time('23:59')).')';
       }
       if(Url::get('status'))
       {
            $cond .= ' AND (purchases_group_invoice.status=\''.Url::get('status').'\')';
            $this->map['status'] = Url::get('status');
       }
	   $sql = "SELECT 
                purchases_group_invoice.id,
                purchases_group_invoice.create_time,
                purchases_group_invoice.creater,
                purchases_group_invoice.status,
                purchases_group_invoice.note,
                purchases_group_invoice.confirm_time,
                purchases_group_invoice.confirm_user,
                purchases_group_invoice.warehouse,
                party.name_".Portal::language()." as user_name 
            from 
                purchases_group_invoice 
                inner join party on party.user_id=purchases_group_invoice.creater 
            Where ".$cond." 
            ORDER BY 
                purchases_group_invoice.id DESC";
       $group = DB::fetch_all($sql);
       $stt = 1;
       foreach($group as $key=>$value)
       {
            $group[$key]['stt'] = $stt++;
            $group[$key]['total'] = 0;
            $group[$key]['quantity'] = 0;
            $group[$key]['create_time'] = date('H:i d/m/Y',$value['create_time']);
            $group[$key]['child'] = array();
            $group[$key]['description'] = '';
            $group[$key]['supplier_list'] = array();
            $group[$key]['supplier_count'] = 0;
            $group[$key]['product_other'] = 0;
            $detail = DB::fetch_all("
                                    SELECT
                                        purchases_invoice_detail.id,
                                        purchases_invoice_detail.quantity,
                                        purchases_invoice_detail.supplier_code,
                                        purchases_invoice_detail.warehouse_invoice_id,
                                        NVL(purchases_invoice_detail.purchases_invoice_id,0) as purchases_invoice_id,
                                        purchases_invoice_detail.price,
                                        purchases_detail.note,
                                        purchases_detail.purchases_id,
                                        purchases_detail.product_code,
                                        product.name_".Portal::language()." as product_name,
                                        unit.id as unit_id,
                                        unit.name_".Portal::language()." as unit_name,
                                        product_category.name as product_category_name,
                                        supplier.name as supplier_name,
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
                                        purchases_invoice_detail.purchases_group_invoice_id=".$value['id']."
                                    ORDER BY
                                        supplier.name,purchases_invoice_detail.id
                                    ");
            foreach($detail as $id=>$content)
            {
                $content['create_time'] = date('H:i d/m/Y',$content['create_time']);
                $content['total'] = $content['quantity']*$content['price'];
                if($content['purchases_invoice_id']==0)
                {
                    $content['purchases_invoice_id'] = 'OTHER';
                    $group[$key]['child'][$content['purchases_invoice_id']]['id'] = 'OTHER';
                    $group[$key]['product_other'] += 1;
                    if($content['supplier_code']=='')
                    {
                        $supplier_key = "OTHER";
                        $supplier_name = Portal::language("other");
                    }
                    else
                    {
                        $supplier_key = $content['supplier_code'];
                        $supplier_name = $content['supplier_code'];
                    }
                    if(!isset($group[$key]['supplier_list'][$supplier_key]))
                    {
                        $group[$key]['supplier_list'][$supplier_key]['id'] = $supplier_key;
                        $group[$key]['supplier_list'][$supplier_key]['name'] = $supplier_name;
                        $group[$key]['supplier_count'] += 1;
                    }
                }
                else
                {
                    if(!isset($group[$key]['child'][$content['purchases_invoice_id']]))
                    {
                        $group[$key]['child'][$content['purchases_invoice_id']] = DB::fetch("SELECT * FROM purchases_invoice WHERE id=".$content['purchases_invoice_id']."");
                        $group[$key]['child'][$content['purchases_invoice_id']]['child'] = array();
                    }
                }
                $group[$key]['child'][$content['purchases_invoice_id']]['child'][$id] = $content;
                $group[$key]['child'][$content['purchases_invoice_id']]['child'][$id]['description'] = Portal::language('product')." ".Portal::language('code')." : ".$content['product_code']." - ".Portal::language('name')." : ".$content['product_name']."";
                $group[$key]['child'][$content['purchases_invoice_id']]['child'][$id]['description'] .= ' ( | '.$content['quantity']." ".Portal::language('product')." - ".Portal::language('price').": ".System::display_number($content['price'])." - ".Portal::language('total').": ".System::display_number($content['total'])." - ".Portal::language('proposed_with').": ".$content['user_name']." - ".Portal::language('department').": ".$content['department']." - ";
                $group[$key]['child'][$content['purchases_invoice_id']]['child'][$id]['description'] .= ' '.Portal::language('proposed_code').': <a href="?page=purchases_detail&cmd=edit&id='.$content['dx_id'].'" >ÐX_'.$content['dx_id'].'</a> | ) <br />';
                
                $group[$key]['total'] += $content['total'];
                $group[$key]['quantity'] += $content['quantity'];
                $group[$key]['description'] .= " - ".$group[$key]['child'][$content['purchases_invoice_id']]['child'][$id]['description']."<br />";
                //unset($group[$key]['child'][$content['purchases_invoice_id']]['child'][$id]['description']);
            }
       }
       $creater = DB::fetch_all('SELECT party.user_id as id, party.NAME_'.Portal::language().' as name FROM party ');
       $this->map['creater_list'] = array(''=>'--ALL--') + String::get_list($creater);
       $this->map['status_list'] = array(''=>'--ALL--','CONFIRM'=>Portal::language('confirm_1'),'CONFIRMING'=>Portal::language('confirming'));
       //System::debug($group);
       $this->parse_layout('list',array('items'=>$group)+$this->map);
	}
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }	
}
?>