<?php
class WarehouseExportByReceiverReportForm extends Form
{
	function WarehouseExportByReceiverReportForm()
	{
		Form::Form('WarehouseExportByReceiverReportForm');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');        
	}
	function draw()
	{
        require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
        require_once 'packages/hotel/packages/warehousing/modules/WarehouseReport/forms/options.php';
         
        //exit();
		$this->map = array();
        //Start : Luu Nguyen GIap add portal
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
        //End   :Luu Nguyen GIap add portal
        $warehouse = get_warehouse(true);
        
        $this->map['warehouse_id_list'] = array(''=>Portal::language('select_warehouse'))+String::get_list($warehouse);
        //$this->map['type_list'] = array(''=>Portal::language('all'),'IMPORT'=>Portal::language('import'),'EXPORT'=>Portal::language('export'));
		//lấy danh sách các bộ phận
         $sql = 'select 
                    id,
                    name
                from 
                    wh_receiver
                where portal_id = \''.PORTAL_ID.'\'
                ';
		$receiver_list = DB::fetch_all($sql);
        $this->map['wh_receiver_name_list'] = array(''=>Portal::language('Chọn_Bộ_Phận')) + String::get_list($receiver_list);
        $this->map['title'] = Portal::language('Report_options');
        $this->map['receiver_name']=DB::fetch('select name from wh_receiver where id ='.Url::iget('wh_receiver_name'),'name');
        
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
            $cond =" AND WH_RECEIVER.portal_id ='".$portal_id."' ";
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
            
            if(Url::get('wh_receiver_name'))
            {
                $cond.= '   AND WH_RECEIVER.id =\''.Url::sget('wh_receiver_name').'\'';
            }
                
            $cond.='
                        AND wh_invoice.create_date >=\''.Date_Time::to_orc_date(Url::get('from_date')).'\'
                        AND wh_invoice.create_date <=\''.Date_Time::to_orc_date(Url::get('to_date')).'\'';
            $sql = '
                    Select
                        wh_invoice_detail.id || \'_\' || wh_invoice_detail.to_warehouse_id as id, 
                        wh_invoice_detail.product_id, product.name_'.Portal::language().' as product_name,
                        WH_INVOICE.TOTAL_AMOUNT as TOTAL_AMOUNT,
                        WH_INVOICE_DETAIL.PRICE as price,
                        wh_invoice_detail.num as num
                    From
                        wh_invoice_detail
                        left join wh_invoice on wh_invoice_detail.invoice_id = wh_invoice.id
                        inner join WH_RECEIVER on WH_INVOICE.WH_RECEIVER_NAME = WH_RECEIVER.id
                        left JOIN product on wh_invoice_detail.product_id = product.id
                       
                    where
                        '.( Url::get('type')? 'wh_invoice.type = \''.Url::get('type').'\' ':' wh_invoice.type = \'EXPORT\' ' ).'
                        
                        and wh_invoice.warehouse_id = \''.Url::iget('warehouse_id').'\'
                        '.$cond.'
                    
                    ';
            //System::debug($sql);
            $items = DB::fetch_all($sql);
            $product = false;
            $k = false;
            $rowspan = 1;
    		foreach($items as $key=>$item)
    		{
                //khoi tao gia tri tong
                $items[$key]['total'] = $items[$key]['num'];
                $items[$key]['total_1']=$items[$key]['total']*$items[$key]['price'];
                //key la product_whID
                //neu === product thi + 1 rowspan
                if($product == $item['product_id'] )
                {
                    $items[$k]['rowspan'] = ++$rowspan;
                    //dem tong
                    $items[$k]['total'] += $items[$key]['num']; 
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
            $this->map['items'] = $items;
            //System::debug($items);
            $this->parse_layout('report',$this->map); 
        }
	}	
}
?>