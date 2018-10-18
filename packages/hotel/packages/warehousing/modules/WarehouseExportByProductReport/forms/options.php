<?php
class WarehouseExportByProductReportForm extends Form
{
	function WarehouseExportByProductReportForm()
	{
		Form::Form('WarehouseExportByProductReportForm');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');    
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');    
	}
	function draw()
	{
        require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
        require_once 'packages/hotel/packages/warehousing/modules/WarehouseReport/forms/options.php';
        
		$this->map = array();
        //Start : Luu Nguyen GIap add portal
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
        //End   :Luu Nguyen GIap add portal
        
        $warehouse = get_warehouse(true);
        
        $this->map['warehouse_id_list'] = array(''=>Portal::language('select_warehouse'))+String::get_list($warehouse);
        //$this->map['type_list'] = array(''=>Portal::language('all'),'IMPORT'=>Portal::language('import'),'EXPORT'=>Portal::language('export'));
		
        $this->map['title'] = Portal::language('Report_options');
        
        
        $this->map['from_date'] = Url::sget('from_date')?Url::sget('from_date'):date('d/m/Y');
        $_REQUEST['from_date'] = $this->map['from_date'];
        $this->map['to_date'] = Url::sget('to_date')?Url::sget('to_date'):date('d/m/Y');
        $_REQUEST['to_date'] = $this->map['to_date'];
        
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
            $cond =" AND  wh_invoice.portal_id ='".$portal_id."' ";
        }
        else
        {
            $cond=" AND 1=1 ";
        } 
        //End Luu Nguyen Giap add portal
            
            
        if(!Url::get('warehouse_id'))
        {
            $this->parse_layout('options',$this->map);
        }
        else
        {
            $this->map['warehouse_name']  =  DB::fetch('Select name from warehouse where id = '.Url::iget('warehouse_id'),'name');
           
            if(Url::get('product_id'))
            {
                $cond.= '   AND upper(wh_invoice_detail.product_id) =\''.strtoupper(Url::sget('product_id')).'\'';
            }
                
            $cond.='
                        AND wh_invoice.create_date >=\''.Date_Time::to_orc_date(Url::get('from_date')).'\'
                        AND wh_invoice.create_date <=\''.Date_Time::to_orc_date(Url::get('to_date')).'\'';
            $sql = '
                    Select
                        wh_invoice_detail.product_id || \'_\' || wh_invoice_detail.to_warehouse_id || \'_\' || wh_invoice.bill_number as id, 
                        wh_invoice_detail.product_id,
                        wh_invoice_detail.to_warehouse_id,
                        wh_invoice.bill_number,
                        wh_invoice.create_date,
                        warehouse.name as warehouse_name,
                        product.name_'.Portal::language().' as product_name,
                        unit.name_'.Portal::language().' as unit_name,
                        wh_invoice_detail.price as price,
                        (CASE
                            WHEN (wh_invoice_detail.payment_price != 0 and wh_invoice_detail.payment_price is not null) THEN wh_invoice_detail.payment_price
                            ELSE wh_invoice_detail.price * wh_invoice_detail.num
                         END
                        ) as payment_price,
                        wh_invoice_detail.num as tt,
                        SUM(wh_invoice_detail.num) as num,
                        (SUM(wh_invoice_detail.num) *  wh_invoice_detail.price) as grand
                    From
                        wh_invoice_detail
                        inner join wh_invoice on wh_invoice_detail.invoice_id = wh_invoice.id
                        left join warehouse on  wh_invoice_detail.to_warehouse_id = warehouse.id
                        INNER JOIN product on wh_invoice_detail.product_id = product.id
                        INNER JOIN unit on product.unit_id = unit.id
                    where
                        '.( Url::get('type')? 'wh_invoice.type = \''.Url::get('type').'\' ':' wh_invoice.type = \'EXPORT\' ' ).'
                        
                        and wh_invoice.warehouse_id = \''.Url::iget('warehouse_id').'\'
                        '.$cond.'
                    group by
                        wh_invoice_detail.product_id,
                        wh_invoice_detail.to_warehouse_id,
                        warehouse.name,
                        product.name_'.Portal::language().',
                        unit.name_'.Portal::language().',
                        wh_invoice.bill_number,
                        wh_invoice.create_date,
                        wh_invoice_detail.price,
                        wh_invoice_detail.payment_price,
                        wh_invoice_detail.num
                    order by
                        wh_invoice_detail.product_id,
                        wh_invoice_detail.to_warehouse_id
                    ';
            //System::debug($sql);
            $items = DB::fetch_all($sql);;            
            $product = false;
            $k = false;
            $rowspan = 1;
    		foreach($items as $key=>$item)
    		{
                //khoi tao gia tri tong
                $items[$key]['total'] = $items[$key]['num'];//round ($items[$key]['num'], 2);
                $items[$key]['total_money'] = round($items[$key]['payment_price'], 2);
                //key la product_whID
                //neu === product thi + 1 rowspan
                if($product == $item['product_id'] )
                {
                    $items[$k]['rowspan'] = ++$rowspan;
                    //dem tong
                    $items[$k]['total'] += $items[$key]['num']; 
                    $items[$k]['total_money'] += round($items[$key]['payment_price'], 2);
                }
                else
                {
                    //luu sang phan
                    $product = $item['product_id'];
                    $rowspan = 1;
                    //$k la key cua san pham do, dung de ++ rowspan
                    $k = $key;
                    $items[$key]['rowspan'] = $rowspan; 
                }
    		}
            
            
            $grand_total = array();
            $grand_total['grand_total_money']=false;
            $grand_total['grand_total_num']=false;
            foreach($items as $key=>$value)
            {
                
                if(isset($grand_total['grand_total_num']))
                {
                    $grand_total['grand_total_money'] += round($value['payment_price'], 2);
                    $grand_total['grand_total_num'] +=  $value['num'];
                }
                else
                {
                    $grand_total['grand_total_money'] = round($items[$key]['payment_price'], 2);
                    $grand_total['grand_total_num'] = $value['num'];
                }
            }
            $this->map['items'] = $items;
            
                
            $this->map['grand_total'] = $grand_total;
            $this->parse_layout('report',$this->map); 
        }
	}	
}
?>