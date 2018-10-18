<?php 
    function sync_wh_invoice_xvt()
    {
        set_time_limit(-1);
        $warehouse = DB::fetch_all("
                                    SELECT 
                                        wh_invoice_detail.*,
                                        unit.name_1 as unit_name,
                                        product_category.code as category_code,
                                        product.type as product_type,
                                        TO_CHAR(wh_invoice.create_date,'DD/MM/YYYY') as create_date,
                                        supplier.code as supplier_code,
                                        wh_invoice_detail.warehouse_id,
                                        wh_invoice_detail.to_warehouse_id,
                                        wh_invoice.portal_id,
                                        wh_invoice.type as warehouse_type,
                                        wh_invoice.invoice_number,
                                        wh_invoice.note as warehouse_note,
                                        wh_invoice.bill_number,
                                        wh_invoice.user_id,
                                        wh_invoice.id as wh_id
                                    FROM 
                                        wh_invoice_detail
                                        inner join wh_invoice on wh_invoice_detail.invoice_id=wh_invoice.id
                                        left join supplier on supplier.id=wh_invoice.supplier_id
                                        inner join product on wh_invoice_detail.product_id = product.id
                                        left join unit on unit.id=product.unit_id
                                        left join product_category on product_category.id=product.category_id
                                    WHERE 
                                        wh_invoice.sync_cns_vt = 0
                                        and wh_invoice.type='EXPORT'
                                        and (product.type='MATERIAL' OR product.type='EQUIPMENT' OR product.type='TOOLS')
                                        and wh_invoice.portal_id='".PORTAL_ID."'
                                    ORDER BY
                                        wh_invoice.id
                                    ");
        $row = array();
        foreach($warehouse as $key=>$value)
        {
            $value['warehouse_code'] = DB::fetch("SELECT code from warehouse where id=".$value['warehouse_id'],"code");
            if($value['to_warehouse_id']!='')
                $value['warehouse_code2'] = DB::fetch("SELECT code from warehouse where id=".$value['to_warehouse_id'],"code");
            else
                $value['warehouse_code2'] = '';
            if(!isset($row[$value['wh_id']]))
            {
                $row[$value['wh_id']]['id'] = $value['wh_id'];
                $row[$value['wh_id']]['ReferenceKey'] = 'XVT'.$value['bill_number'];
                $row[$value['wh_id']]['CreatedOn'] = date('Y-m-d');
                $row[$value['wh_id']]['ChangedOn'] = date('Y-m-d');
                $row[$value['wh_id']]['CustomerName'] = '';
                $row[$value['wh_id']]['CustomerAddress'] = '';
                $row[$value['wh_id']]['CustomerTaxCode'] = '';
                
                $row[$value['wh_id']]['CurrencyCode'] = 'VND';
                $row[$value['wh_id']]['CurrencyRate'] = '';
                
                if($value['supplier_code']!='')
                    $row[$value['wh_id']]['ContactCode'] = 'SU_'.$value['supplier_code'];
                else
                    $row[$value['wh_id']]['ContactCode'] = 'KHACHLE_HOTEL';
                
                $row[$value['wh_id']]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row[$value['wh_id']]['WarehouseCode'] = $value['warehouse_code'];
                $row[$value['wh_id']]['WarehouseCode2'] = '';
                
                $row[$value['wh_id']]['Description'] = $value['warehouse_note'];
                $row[$value['wh_id']]['TotalAmount'] = 0;
                $row[$value['wh_id']]['TotalQuantity'] = 0;
                $row[$value['wh_id']]['TotalCost'] = '';
                $row[$value['wh_id']]['TotalDiscount'] = '';
                $row[$value['wh_id']]['TotalTax'] = '';
                
                $row[$value['wh_id']]['TransTypeCode'] = 'XVT';
                $row[$value['wh_id']]['Code'] = 'XVT'.$value['bill_number'];
                
                $create_date = explode('/',$value['create_date']);
                $row[$value['wh_id']]['TransDate'] = $create_date[2].'-'.$create_date[1].'-'.$create_date[0];
                
                $row[$value['wh_id']]['WarehouseTransactionDetails'] = array();
                $row[$value['wh_id']]['WarehouseTransactionDetails']['length'] = 0;
            }
            
            $stt = $row[$value['wh_id']]['WarehouseTransactionDetails']['length'];
            
            $row[$value['wh_id']]['WarehouseTransactionDetails'][$stt]['ParentReferenceKey'] = 'XVT'.$value['bill_number'];
            
            $row[$value['wh_id']]['WarehouseTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
            $row[$value['wh_id']]['WarehouseTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
            
            $row[$value['wh_id']]['WarehouseTransactionDetails'][$stt]['WarehouseCode'] = $value['warehouse_code'];
            $row[$value['wh_id']]['WarehouseTransactionDetails'][$stt]['WarehouseCode2'] = '';
            
            $row[$value['wh_id']]['WarehouseTransactionDetails'][$stt]['ContactCode'] = $row[$value['wh_id']]['ContactCode'];
            $row[$value['wh_id']]['WarehouseTransactionDetails'][$stt]['ItemCode'] = $value['product_id'];
            $row[$value['wh_id']]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = $value['product_id'];
            $row[$value['wh_id']]['WarehouseTransactionDetails'][$stt]['FeeItemCode'] = $value['product_id'];
            $row[$value['wh_id']]['WarehouseTransactionDetails'][$stt]['UnitCode'] = $value['unit_name'];
            
            $row[$value['wh_id']]['WarehouseTransactionDetails'][$stt]['Quantity'] = $value['num'];
            $row[$value['wh_id']]['WarehouseTransactionDetails'][$stt]['UnitCost'] = '';
            $row[$value['wh_id']]['WarehouseTransactionDetails'][$stt]['UnitPrice'] = $value['price'];
            $row[$value['wh_id']]['WarehouseTransactionDetails'][$stt]['Amount'] = $value['payment_price'];
            $row[$value['wh_id']]['WarehouseTransactionDetails'][$stt]['Discount'] = '';
            
            $row[$value['wh_id']]['TotalAmount'] += $value['payment_price'];
            $row[$value['wh_id']]['TotalQuantity'] += $value['num'];
            
            $row[$value['wh_id']]['WarehouseTransactionDetails'][$stt]['LotNumber'] = $value['invoice_number'];
            $row[$value['wh_id']]['WarehouseTransactionDetails'][$stt]['SeriNumber'] = $value['invoice_number'];
            
            $row[$value['wh_id']]['WarehouseTransactionDetails'][$stt]['DebitAccCode'] = 152;
            $row[$value['wh_id']]['WarehouseTransactionDetails'][$stt]['DebitAccCode2'] = 152;
            $row[$value['wh_id']]['WarehouseTransactionDetails'][$stt]['CreditAccCode'] = 152;
            $row[$value['wh_id']]['WarehouseTransactionDetails'][$stt]['CreditAccCode2'] = 152;
            
            $row[$value['wh_id']]['WarehouseTransactionDetails'][$stt]['ReferenceKey'] = 'WHD_'.$value['id'];
            $row[$value['wh_id']]['WarehouseTransactionDetails']['length']++;
        }
        foreach($row as $k=>$v)
        {
            $row_sync = array();
            $code = $v['id'];
            unset($v['id']);
            $row_sync = $v;
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/WarehouseTransactions',HttpRequest::METH_POST);
            $r->addPostFields($row_sync);
            try 
            {
                $r->send();
                if($r->getResponseCode()==200)
                {
                    //DB::update('wh_invoice',array('sync_cns_vt'=>1),'wh_invoice.id='.$code);
                    DB::query("update wh_invoice set sync_cns_vt=1 where id=".$code);
                }
                else
                    System::debug($r);
                
            } 
            catch (HttpException $ex) 
            {
                System::debug($r);
            }
        }
           
           
    }
?>