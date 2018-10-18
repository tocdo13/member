<?php
class WarehouseInvoiceReportOptionsForm extends Form
{
	function WarehouseInvoiceReportOptionsForm()
	{
		Form::Form('WarehouseInvoiceReportOptionsForm');
		//$this->link_css(Portal::template('hotel').'/css/report.css');
		//$this->link_css('packages/hotel/packages/warehousing/skins/default/css/style.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->add('date_from',new DateType(true,'invalid_date_from'));
		$this->add('date_to',new DateType(true,'invalid_date_to',0,255));
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{
        require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
		$this->map = array();
        //Start : Luu Nguyen GIap add portal
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
        //End   :Luu Nguyen GIap add portal
        $warehouse = get_warehouse(true);
        //$this->map['warehouse_id_list'] = array(''=>Portal::language('all'))+String::get_list($warehouse);
        $this->map['warehouse_id_list'] = array(''=>Portal::language('select_warehouse'))+String::get_list($warehouse);
        $this->map['type_list'] = array(''=>Portal::language('all'),'IMPORT'=>Portal::language('import'),'EXPORT'=>Portal::language('export'));
		
        $this->map['title'] = Portal::language('Report_options');
        
        //Start Luu Nguyen Giap add portal
        if(Url::get('portal_id'))
        {
           $portal_id =  Url::get('portal_id');
        }
        else
        {
            $portal_id =PORTAL_ID;
        }
        if($portal_id!="ALL")
        {
            $cond ="  wh_invoice.portal_id ='".$portal_id."' ";
        }
        else
        {
            $cond=" 1=1 ";
        } 
        //End Luu Nguyen Giap add portal
        
       // $cond = ' 1=1 and wh_invoice.portal_id = \''.PORTAL_ID.'\' ';
        
        $this->map['date_from'] = Url::sget('date_from')?Url::sget('date_from'):date('d/m/Y');
        $_REQUEST['date_from'] = $this->map['date_from'];
        $cond.= ' and wh_invoice.create_date >=\''.Date_Time::to_orc_date(Url::get('date_from')).'\' ';
           
        $this->map['date_to'] = Url::sget('date_to')?Url::sget('date_to'):date('d/m/Y');
        $_REQUEST['date_to'] = $this->map['date_to']; 
        $cond.= ' and wh_invoice.create_date <=\''.Date_Time::to_orc_date(Url::get('date_to')).'\' ';
            
        if(Url::get('type'))
            $cond.= ' and wh_invoice.type =\''.Url::get('type').'\' ';
            
        if(Url::get('warehouse_id'))
            $cond.= ' and wh_invoice.warehouse_id =\''.Url::get('warehouse_id').'\' ';
            
            
        if(!Url::get('warehouse_id'))
        {
            $this->parse_layout('options',$this->map);
        }
        else
        {
            $sql = 'Select 
                        wh_invoice.id,
                        wh_invoice.bill_number,
                        wh_invoice.type,
                        wh_invoice.create_date,
                        wh_invoice.total_amount,
                        wh_invoice_detail.money_add,
                        wh_invoice.move_product,
                        wh_invoice.get_back_supplier,
                        wh_invoice.direct_export,
                        wh_invoice.note,
                        supplier.name as supplier_name,
                        warehouse.name as warehouse_name
                    from 
                        wh_invoice
                        left join supplier on supplier.id = wh_invoice.supplier_id
                        inner join warehouse on wh_invoice.warehouse_id = warehouse.id
                        inner join wh_invoice_detail on wh_invoice.id = wh_invoice_detail.invoice_id
                    where 
                        '.$cond.' 
                    order by 
                        wh_invoice.id';
            
            $this->map['items'] = DB::fetch_all($sql);
            
            $i = 1;
            $grand_total_detail= array();
            foreach ($this->map['items'] as $key => $value)
            {
                $this->map['items'][$key]['stt']=$i++;
                
                if($this->map['items'][$key]['money_add']=='')
                {
                    $this->map['items'][$key]['total_amount'] = $this->map['items'][$key]['total_amount']?$this->map['items'][$key]['total_amount']:0;
                }
                else
                {
                    $this->map['items'][$key]['total_amount'] = $this->map['items'][$key]['money_add']?$this->map['items'][$key]['money_add']:0;
                }
                if($this->map['items'][$key]['move_product']==1)
                    $this->map['items'][$key]['note']=Portal::language('move_product');
                    
                if($this->map['items'][$key]['get_back_supplier']==1)
                    $this->map['items'][$key]['note']=Portal::language('returned_supplier');
                    
                if($this->map['items'][$key]['direct_export']==1)
                    $this->map['items'][$key]['note']=Portal::language('direct_export');
                    
                //row span + 1    
                $this->map['items'][$key]['row_span'] = 
                        DB::fetch('Select 
                                    count(*) as acount
                                from 
                                    wh_invoice_detail
                                    INNER JOIN product on wh_invoice_detail.product_id = product.id
                                    INNER JOIN unit on product.unit_id = unit.id
                                    INNER JOIN product_category on product.category_id = product_category.id
                                where 
                                    invoice_id = '.$key,'acount');
                $this->map['items'][$key]['row_span']++;
                
                $this->map['items'][$key]['total_amount'] = 0;
                $this->map['items'][$key]['create_date'] = Date_Time::convert_orc_date_to_date($this->map['items'][$key]['create_date']);
                
                $this->map['items'][$key]['detail'] = 
                    DB::fetch_all('Select 
                                    wh_invoice_detail.id,
                                    wh_invoice_detail.product_id,
                                    product.name_'.Portal::language().' as product_name,
                                    wh_invoice_detail.num,
                                    wh_invoice_detail.price,
                                    wh_invoice_detail.money_add as money_add,
                                    (CASE
                                        WHEN money_add is null and wh_invoice_detail.payment_price != 0 THEN wh_invoice_detail.payment_price
                                        WHEN money_add is null and wh_invoice_detail.payment_price = 0 THEN wh_invoice_detail.price * wh_invoice_detail.num
                                        ELSE wh_invoice_detail.money_add
                                     END
                                    ) as payment_price,
                                    wh_invoice_detail.num * wh_invoice_detail.price as total,
                                    wh_invoice_detail.warehouse_id,
                                    wh_invoice_detail.to_warehouse_id,
                                    warehouse.name as to_warehouse_name
                                from 
                                    wh_invoice_detail
                                    LEFT JOIN warehouse on warehouse.id = wh_invoice_detail.to_warehouse_id
                                    INNER JOIN product on wh_invoice_detail.product_id = product.id
                                    INNER JOIN unit on product.unit_id = unit.id
                                    INNER JOIN product_category on product.category_id = product_category.id
                                where 
                                    invoice_id = '.$key);
                //System::debug($this->map['items'][$key]['detail']);
                $j = 1;                                                        
                foreach($this->map['items'][$key]['detail'] as $k => $v)
                {
                    $this->map['items'][$key]['detail'][$k]['stt'] = $j++; 
                    $this->map['items'][$key]['detail'][$k]['num'] = $this->map['items'][$key]['detail'][$k]['num'];//System::display_number_report($this->map['items'][$key]['detail'][$k]['num']);
                    //$this->map['items'][$key]['detail'][$k]['price'] = System::display_number($this->map['items'][$key]['detail'][$k]['price']);
                    if($this->map['items'][$key]['detail'][$k]['money_add']=='')
                    {
                        $this->map['items'][$key]['detail'][$k]['total_amount'] = $this->map['items'][$key]['detail'][$k]['payment_price'];
                        $this->map['items'][$key]['detail'][$k]['price'] = System::display_number($this->map['items'][$key]['detail'][$k]['price']);
                    }
                    else
                    {
                        $this->map['items'][$key]['detail'][$k]['total_amount'] = $this->map['items'][$key]['detail'][$k]['money_add'];
                        $this->map['items'][$key]['detail'][$k]['price'] = 0;
                    }
                    //to warehouse cua invoice lay tu detail cua invoice do
                    $this->map['items'][$key]['to_warehouse_name'] = $this->map['items'][$key]['detail'][$k]['to_warehouse_name'];
                }
                //System::debug($this->map['items'][$key]['detail']);
             
             foreach($this->map['items'][$key]['detail'] as $k => $v)
             {
                if(isset($grand_total_detail['total_amount_detail']))
                {
                    $grand_total_detail['total_amount_detail'] += round ($v['total_amount'], 2);
                }
                else
                {
                    $grand_total_detail['total_amount_detail'] = round ($v['total_amount'], 2);
                }
                $this->map['items'][$key]['total_amount'] += round ($v['total_amount'], 2);
             }
             //System::debug($grand_total_detail);                                                
            }
            $grand_total = array();

            if(count($this->map['items'])==0)
            {
                $grand_total['grand_total_amount'] = 0;
            }
            
            foreach($this->map['items'] as $key => $value)
            {
                if(isset($grand_total['grand_total_amount']))
                {
                    $grand_total['grand_total_amount'] += round ($value['total_amount'], 2);
                }
                else
                {
                    $grand_total['grand_total_amount'] = round ($value['total_amount'], 2);
                }
                
            }
            
            $this->parse_layout('report',$this->map+array(
                    'grand_total'=>$grand_total,
                    'grand_total_detail'=>$grand_total_detail)); 
        }
	}	
}
?>