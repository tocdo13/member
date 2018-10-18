<?php
class ListPcImportWarehouseOrderForm extends Form
{
    function ListPcImportWarehouseOrderForm()
    {
        Form::Form('ListPcImportWarehouseOrderForm');
        $this->link_css(Portal::template('hotel').'/css/style.css');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
    }
    function draw()
    {
       require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
       $this->map['from_date'] = isset($_REQUEST['from_date'])?$_REQUEST['from_date'] = $_REQUEST['from_date']:$_REQUEST['from_date'] = date('d/m/Y', Date_Time::to_time(date('d/m/Y', time())));
       $this->map['to_date'] = isset($_REQUEST['to_date'])?$_REQUEST['to_date'] = $_REQUEST['to_date']:$_REQUEST['to_date'] = date('d/m/Y', Date_Time::to_time(date('d/m/Y', time()))+ 86399);
       if(Url::get('action')!='')
       {
            $this->map = array();
           $cond = ' 1=1 ';
           $cond_ho = ' 1=1 ';
           $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):'';
           $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):'';
           $_REQUEST['from_date'] = $this->map['from_date']; $_REQUEST['to_date'] = $this->map['to_date'];
           if($this->map['from_date']!='' AND $this->map['to_date']!='')
           {
                if(Url::get('action')=='import')
                $cond .= ' AND wh_invoice.create_date >= \''.Date_Time::to_orc_date($this->map['from_date']).'\' AND wh_invoice.create_date <= \''.(Date_Time::to_orc_date($this->map['to_date'])).'\'';
                else
                $cond_ho .= ' AND handover_invoice.create_date >= \''.Date_Time::to_orc_date($this->map['from_date']).'\' AND handover_invoice.create_date <= \''.(Date_Time::to_orc_date($this->map['to_date'])).'\'';
           }
           
           $this->map['bill_number'] = Url::get('bill_number')?Url::get('bill_number'):'';
           if(Url::get('bill_number')!='')
           {
                if(Url::get('action')=='import')
                $cond .= ' AND wh_invoice.bill_number = \''.Url::get('bill_number').'\'';
                else
                $cond_ho .= ' AND handover_invoice.bill_number = \''.Url::get('bill_number').'\'';
                $_REQUEST['bill_number'] = $this->map['bill_number'];
           }
           
           $this->map['invoice_number'] = Url::get('invoice_number')?Url::get('invoice_number'):'';
           if(Url::get('invoice_number')!='')
           {
                if(Url::get('action')=='import')
                $cond .= ' AND wh_invoice.invoice_number = \''.Url::get('invoice_number').'\'';
                else
                $cond_ho .= ' AND handover_invoice.invoice_number = \''.Url::get('invoice_number').'\'';
                $_REQUEST['invoice_number'] = $this->map['invoice_number'];
           }
             //trung add: $this->map['suppliers']                                                                 //System::debug($this->map['supplier_id_list']);
        $this->map['suppliers'] = DB::fetch_all('
			SELECT
				supplier.code as id,
                supplier.id as supplier_id,
                supplier.name,
                supplier.address,
                supplier.tax_code
			FROM
				supplier
			ORDER BY
				supplier.code
		');
           $this->map['supplier_id'] = Url::get('supplier_id')?Url::get('supplier_id'):'';
           if(Url::get('supplier_name') !='')
           {
                if(Url::get('action')=='import')
                $cond .= ' AND wh_invoice.supplier_id = '.Url::get('supplier_id');
                else
                $cond_ho .= ' AND handover_invoice.supplier_id = '.Url::get('supplier_id');
                $_REQUEST['supplier_id'] = $this->map['supplier_id'];
           }
           $supplier = DB::fetch_all("SELECT * FROM supplier");
           $this->map['supplier_id_list'] = array(""=>"-----".Portal::language('all')."-----")+String::get_list($supplier);
           
           $this->map['warehouse_id'] = Url::get('warehouse_id')?Url::get('warehouse_id'):'';
           if(Url::get('warehouse_id')!='')
           {
                if(Url::get('action')=='import')
                $cond .= ' AND wh_invoice.warehouse_id = '.Url::get('warehouse_id');
                $_REQUEST['warehouse_id'] = $this->map['warehouse_id'];
           }
           $warehouses = get_warehouse(true);
           $this->map['warehouse_id_list'] = array(''=>'-----'.Portal::language('All').'-----')+String::get_list($warehouses);

           if(Url::get('action')=='import')
           {
               $items = DB::fetch_all('
                                    SELECT
                                        wh_invoice.*,
                                        supplier.name as supplier_name,
                                        warehouse.name as warehouse_name,
                                        warehouse.code as warehouse_code,
                                        TO_CHAR(wh_invoice.create_date,\'DD/MM/YYYY\') as create_date,
                                        pc_order.name as pc_order_name
                                    FROM
                                        wh_invoice
                                        inner join supplier on supplier.id=wh_invoice.supplier_id
                                        inner join warehouse on warehouse.id=wh_invoice.warehouse_id
                                        inner join pc_order on pc_order.id=wh_invoice.pc_order_id
                                    WHERE
                                        '.$cond.' AND wh_invoice.pc_order_id is not null
                                    ORDER BY wh_invoice.create_date desc
                                    ');
               
           }
           else
           {
                $items = DB::fetch_all('
                                    SELECT
                                        handover_invoice.*,
                                        supplier.name as supplier_name,
                                        \'\' as warehouse_name,
                                        \'\' as warehouse_code,
                                        TO_CHAR(handover_invoice.create_date,\'DD/MM/YYYY\') as create_date,
                                        pc_order.name as pc_order_name
                                    FROM
                                        handover_invoice
                                        inner join supplier on supplier.id=handover_invoice.supplier_id
                                        inner join pc_order on pc_order.id=handover_invoice.pc_order_id
                                    WHERE
                                        '.$cond_ho.' AND handover_invoice.pc_order_id is not null
                                    ORDER BY handover_invoice.create_date desc
                                    ');           
           }
           if(sizeof($items)>0)
            $this->map['items'] = $items;
           else
            $this->map['no_data'] = portal::language('no_record');
       }
       else
       {
            $this->map['no_data'] = portal::language('no_record');
       }
       
       $this->parse_layout('list',$this->map);
    }   
}
?>