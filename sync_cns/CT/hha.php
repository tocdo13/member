<?php 
    function sync_invoice_hha()
    {
        set_time_limit(-1);
        
        $mice = get_mice();
        foreach($mice as $id=>$content)
        {
            if($content['WarehouseTransactionDetails']['length']==0)
            {
                
            }
            else
            {
                $row = array();
                $code = $content['id'];
                unset($content['id']);
                $payment = $content['payment'];
                unset($content['payment']);
                $row = $content;
                $r = new HttpRequest(LINK_SYNC_CNS.'/api/WarehouseTransactions',HttpRequest::METH_POST);
                $r->addPostFields($row);
                try 
                {
                    $r->send();
                    if($r->getResponseCode()==200)
                    {
                        DB::query("update mice_invoice set sync_cns_hh=1 where id=".$code);
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
        
        $folio = get_folio();
        foreach($folio as $id=>$content)
        {
            if($content['WarehouseTransactionDetails']['length']==0)
            {
                
            }
            else
            {
                //$content['WarehouseTransactionDetails']['length'] = 
                $WarehouseTransactionDetails_length = count($content['WarehouseTransactionDetails']) - 1;
                //$content['FinancialBills']['length'] = count($content['FinancialBills']) - 1;
                $FinancialBills_length = count($content['FinancialBills']) - 1;
                
                $stt = 0;
                unset($content['WarehouseTransactionDetails']['length']);
                $WarehouseTransactionDetails = $content['WarehouseTransactionDetails'];
                $content['WarehouseTransactionDetails'] = array();
                foreach($WarehouseTransactionDetails as $key=>$value)
                {
                    if($value['Quantity']!=0)
                    {
                        $content['WarehouseTransactionDetails'][$stt] = $value; $stt++;
                    }
                }
                $content['WarehouseTransactionDetails']['length'] = $stt;
                
                $stt = 0;
                unset($content['FinancialBills']['length']);
                $FinancialBills = $content['FinancialBills'];
                $content['FinancialBills'] = array();
                foreach($FinancialBills as $key=>$value)
                {
                    if($value['TotalQuantity']!=0)
                    {
                        $content['FinancialBills'][$stt] = $value; $stt++;
                    }
                    
                }
                $content['FinancialBills']['length'] = $stt;
                
                $row = array();
                $code = $content['id'];
                unset($content['id']);
                $payment = $content['payment'];
                unset($content['payment']);
                $row = $content;
                $r = new HttpRequest(LINK_SYNC_CNS.'/api/WarehouseTransactions',HttpRequest::METH_POST);
                $r->addPostFields($row);
                try 
                {
                    $r->send();
                    if($r->getResponseCode()==200)
                    {
                        DB::query("update folio set sync_cns_hh=1 where id=".$code);
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
        $bar = get_bar();
        foreach($bar as $id=>$content)
        {
            if($content['WarehouseTransactionDetails']['length']==0)
            {
                
            }
            else
            {
                //$content['WarehouseTransactionDetails']['length'] = 
                $WarehouseTransactionDetails_length = count($content['WarehouseTransactionDetails']) - 1;
                //$content['FinancialBills']['length'] = count($content['FinancialBills']) - 1;
                $FinancialBills_length = count($content['FinancialBills']) - 1;
                
                $stt = 0;
                unset($content['WarehouseTransactionDetails']['length']);
                $WarehouseTransactionDetails = $content['WarehouseTransactionDetails'];
                $content['WarehouseTransactionDetails'] = array();
                foreach($WarehouseTransactionDetails as $key=>$value)
                {
                    if($value['Quantity']!=0)
                    {
                        $content['WarehouseTransactionDetails'][$stt] = $value; $stt++;
                    }
                }
                $content['WarehouseTransactionDetails']['length'] = $stt;
                
                $stt = 0;
                unset($content['FinancialBills']['length']);
                $FinancialBills = $content['FinancialBills'];
                $content['FinancialBills'] = array();
                foreach($FinancialBills as $key=>$value)
                {
                    if($value['TotalQuantity']!=0)
                    {
                        $content['FinancialBills'][$stt] = $value; $stt++;
                    }
                    
                }
                $content['FinancialBills']['length'] = $stt;
                
                $row = array();
                $code = $content['id'];
                unset($content['id']);
                $payment = $content['payment'];
                unset($content['payment']);
                $row = $content;
                $r = new HttpRequest(LINK_SYNC_CNS.'/api/WarehouseTransactions',HttpRequest::METH_POST);
                $r->addPostFields($row);
                try 
                {
                    $r->send();
                    if($r->getResponseCode()==200)
                    {
                            DB::query("update bar_reservation set sync_cns_hh=1 where id=".$code);
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
        $vend = get_vend();
        foreach($vend as $id=>$content)
        {
            if($content['WarehouseTransactionDetails']['length']==0)
            {
                
            }
            else
            {
                //$content['WarehouseTransactionDetails']['length'] = 
                $WarehouseTransactionDetails_length = count($content['WarehouseTransactionDetails']) - 1;
                //$content['FinancialBills']['length'] = count($content['FinancialBills']) - 1;
                $FinancialBills_length = count($content['FinancialBills']) - 1;
                
                $stt = 0;
                unset($content['WarehouseTransactionDetails']['length']);
                $WarehouseTransactionDetails = $content['WarehouseTransactionDetails'];
                $content['WarehouseTransactionDetails'] = array();
                foreach($WarehouseTransactionDetails as $key=>$value)
                {
                    if($value['Quantity']!=0)
                    {
                        $content['WarehouseTransactionDetails'][$stt] = $value; $stt++;
                    }
                }
                $content['WarehouseTransactionDetails']['length'] = $stt;
                
                $stt = 0;
                unset($content['FinancialBills']['length']);
                $FinancialBills = $content['FinancialBills'];
                $content['FinancialBills'] = array();
                foreach($FinancialBills as $key=>$value)
                {
                    if($value['TotalQuantity']!=0)
                    {
                        $content['FinancialBills'][$stt] = $value; $stt++;
                    }
                    
                }
                $content['FinancialBills']['length'] = $stt;
                
                $row = array();
                $code = $content['id'];
                unset($content['id']);
                $payment = $content['payment'];
                unset($content['payment']);
                $row = $content;
                $r = new HttpRequest(LINK_SYNC_CNS.'/api/WarehouseTransactions',HttpRequest::METH_POST);
                $r->addPostFields($row);
                try 
                {
                    $r->send();
                    if($r->getResponseCode()==200)
                    {
                            DB::query("update ve_reservation set sync_cns_hh=1 where id=".$code);
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
        $spa = get_spa();
        foreach($spa as $id=>$content)
        {
            if($content['WarehouseTransactionDetails']['length']==0)
            {
                
            }
            else
            {
                //$content['WarehouseTransactionDetails']['length'] = 
                $WarehouseTransactionDetails_length = count($content['WarehouseTransactionDetails']) - 1;
                //$content['FinancialBills']['length'] = count($content['FinancialBills']) - 1;
                $FinancialBills_length = count($content['FinancialBills']) - 1;
                
                $stt = 0;
                unset($content['WarehouseTransactionDetails']['length']);
                $WarehouseTransactionDetails = $content['WarehouseTransactionDetails'];
                $content['WarehouseTransactionDetails'] = array();
                foreach($WarehouseTransactionDetails as $key=>$value)
                {
                    if($value['Quantity']!=0)
                    {
                        $content['WarehouseTransactionDetails'][$stt] = $value; $stt++;
                    }
                }
                $content['WarehouseTransactionDetails']['length'] = $stt;
                
                $stt = 0;
                unset($content['FinancialBills']['length']);
                $FinancialBills = $content['FinancialBills'];
                $content['FinancialBills'] = array();
                foreach($FinancialBills as $key=>$value)
                {
                    if($value['TotalQuantity']!=0)
                    {
                        $content['FinancialBills'][$stt] = $value; $stt++;
                    }
                    
                }
                $content['FinancialBills']['length'] = $stt;
                
                $row = array();
                $code = $content['id'];
                $payment = $content['payment'];
                unset($content['payment']);
                unset($content['id']);
                $row = $content;
                $r = new HttpRequest(LINK_SYNC_CNS.'/api/WarehouseTransactions',HttpRequest::METH_POST);
                $r->addPostFields($row);
                try 
                {
                    $r->send();
                    if($r->getResponseCode()==200)
                    {
                            DB::query("update massage_reservation_room set sync_cns_hh=1 where id=".$code);
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
    }
    
    /** data **/
    
    function get_mice()
    {
        $hcb_start_idstruct = DB::fetch('select structure_id from product_category where code=\'HCB\'','structure_id');
        $hcb_end_idstruct = IDStructure::next($hcb_start_idstruct);
        $du_start_idstruct = DB::fetch('select structure_id from product_category where code=\'DU\'','structure_id');
        $du_end_idstruct = IDStructure::next($du_start_idstruct);
        $da_start_idstruct = DB::fetch('select structure_id from product_category where code=\'DA\'','structure_id');
        $da_end_idstruct = IDStructure::next($da_start_idstruct);
        $dv_start_idstruct = DB::fetch('select structure_id from product_category where code=\'DVNH\'','structure_id');
        $dv_end_idstruct = IDStructure::next($dv_start_idstruct);
        $mice = DB::fetch_all("
                                SELECT
                                    mice_invoice_detail.*,
                                    mice_reservation.id as mice_reservation_id,
                                    mice_reservation.customer_id,
                                    mice_reservation.traveller_id,
                                    mice_invoice.payment_time,
                                    mice_invoice.bill_id,
                                    customer.name as customer_name,
                                    customer.address as customer_address,
                                    customer.tax_code as customer_tax_code,
                                    traveller.first_name || ' ' || traveller.last_name as traveller_name,
                                    traveller.address as traveller_address,
                                    extra_service.code as extra_service_code,
                                    extra_service.name as extra_service_name
                                FROM
                                    mice_invoice_detail
                                    inner join mice_invoice on mice_invoice.id=mice_invoice_detail.mice_invoice_id
                                    inner join mice_reservation on mice_reservation.id=mice_invoice.mice_reservation_id
                                    left JOIN customer on mice_reservation.customer_id=customer.id
                                    left join traveller on mice_reservation.traveller_id=traveller.id
                                    left join extra_service_invoice_detail on extra_service_invoice_detail.id=mice_invoice_detail.invoice_id AND mice_invoice_detail.type='EXTRA_SERVICE'
                                    left join extra_service on extra_service.id=extra_service_invoice_detail.service_id
                                where
                                    mice_reservation.portal_id='".PORTAL_ID."'
                                    and mice_invoice.sync_cns_hh=0
                                    and mice_invoice.bill_id is not null
                                    and 
                                        (
                                            mice_invoice_detail.type='MINIBAR' 
                                            OR 
                                            mice_invoice_detail.type='PACKAGE'
                                            OR
                                            mice_invoice_detail.type='BAR'
                                            OR
                                            mice_invoice_detail.type='VE'
                                            OR
                                            mice_invoice_detail.type='KARAOKE'
                                            OR
                                            mice_invoice_detail.type='MASSAGE'
                                            OR
                                            mice_invoice_detail.type='DEPOSIT_GROUP'
                                            OR
                                            mice_invoice_detail.type='DEPOSIT'
                                            OR
                                            mice_invoice_detail.type='DEPOSIT_MICE'
                                        )
                                    AND mice_invoice.payment_time >= ".Date_Time::to_time(DATE_SYNC_CNS)."
                                ");
        $row = array();
        $ListMinibar = array();
        $CondMinibar = '';
        $ListPackage = array();
        $CondPackage = '';
        $ListBar = array();
        $CondBar = '';
        $ListVe = array();
        $CondVe = '';
        $ListKaraoke = array();
        $CondKaraoke = '';
        $ListMassage = array();
        $CondMassage = '';
        foreach($mice as $key=>$value)
        {
            $in_date = date('d/m/Y',$value['payment_time']);
            if(!isset($row[$value['mice_invoice_id']]))
            {
                /**
                 * Khoi tao mang MAIN
                 * ma chung tu HHA
                 * */
                $row[$value['mice_invoice_id']]['id'] = $value['bill_id'];
                $row[$value['mice_invoice_id']]['ReferenceKey'] = 'HHA-BILLMICE'.$value['bill_id'];
                $row[$value['mice_invoice_id']]['CreatedOn'] = date('Y-m-d');
                $row[$value['mice_invoice_id']]['ChangedOn'] = date('Y-m-d');
                $row[$value['mice_invoice_id']]['CustomerName'] = $value['customer_name']==''?$value['traveller_name']:$value['customer_name'];
                $row[$value['mice_invoice_id']]['CustomerAddress'] = $value['customer_name']==''?$value['traveller_address']:$value['customer_address'];
                $row[$value['mice_invoice_id']]['CustomerTaxCode'] = $value['customer_name']==''?'':$value['customer_tax_code'];
                
                $row[$value['mice_invoice_id']]['CurrencyCode'] = 'VND';
                $row[$value['mice_invoice_id']]['CurrencyRate'] = 0;
                $row[$value['mice_invoice_id']]['ContactCode'] = 'KHACHLE_HOTEL';
                if($value['customer_id']!='')
                    $row[$value['mice_invoice_id']]['ContactCode'] = 'CUS_'.$value['customer_id'];
                elseif($value['traveller_id']!='')
                    $row[$value['mice_invoice_id']]['ContactCode'] = 'TRA_'.$value['traveller_id'];
                    
                
                $row[$value['mice_invoice_id']]['ContactCode2'] = '';
                $row[$value['mice_invoice_id']]['payment'] = 1;
                
                
                $row[$value['mice_invoice_id']]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row[$value['mice_invoice_id']]['WarehouseCode'] = '';
                $row[$value['mice_invoice_id']]['WarehouseCode2'] = '';
                
                $row[$value['mice_invoice_id']]['Description'] = 'FOLIO_HHA';
                $row[$value['mice_invoice_id']]['TotalAmount'] = 0;
                $row[$value['mice_invoice_id']]['TotalQuantity'] = 0;
                $row[$value['mice_invoice_id']]['TotalCost'] = 0;
                $row[$value['mice_invoice_id']]['TotalDiscount'] = 0;
                $row[$value['mice_invoice_id']]['TotalTax'] = 0;
                
                $row[$value['mice_invoice_id']]['TransTypeCode'] = 'HHA';
                $row[$value['mice_invoice_id']]['Code'] = 'HHA-BILLMICE'.$value['bill_id'];
                
                $create_date = explode('/',$in_date);
                $row[$value['mice_invoice_id']]['TransDate'] = $create_date[2].'-'.$create_date[1].'-'.$create_date[0];
                
                $row[$value['mice_invoice_id']]['WarehouseTransactionDetails'] = array();
                $row[$value['mice_invoice_id']]['WarehouseTransactionDetails']['length'] = 0;
                
                $row[$value['mice_invoice_id']]['FinancialBills'] = array();
                $row[$value['mice_invoice_id']]['FinancialBills']['length'] = 0;
                
                
                
                $payment = DB::fetch_all("
                                        SELECT * FROM payment WHERE bill_id=".$value['mice_invoice_id']." AND type='BILL_MICE' AND sync_cns=0
                                        ");
                foreach($payment as $keypay=>$valuepay)
                {
                    if($valuepay['payment_type_id']=='CREDIT_CARD' OR $valuepay['payment_type_id']=='BANK' OR $valuepay['payment_type_id']=='CASH' OR $valuepay['payment_type_id']=='REFUND' OR $valuepay['payment_type_id']=='FOC')
                    {
                        $row_pay = array();
                        $row_pay['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                        $row_pay['CreatedOn'] = date('Y-m-d');
                        $row_pay['ChangedOn'] = date('Y-m-d');
                        $row_pay['Code'] = 'BILLMICE'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        $row_pay['TransDate'] = date('Y-m-d',$valuepay['time']);
                        $row_pay['Description'] = $valuepay['description'];
                        $row_pay['TotalAmount'] = $valuepay['amount'];
                        $row_pay['TotalTax'] = 0;
                        $row_pay['CustomerName'] = '';
                        $row_pay['CustomerAddress'] = '';
                        $row_pay['CustomerTaxCode'] = '';
                        $row_pay['CurrencyCode'] = $valuepay['currency_id'];
                        $row_pay['CurrencyRate'] = $valuepay['exchange_rate'];
                        $row_pay['IsReceivables'] = '';
                        $row_pay['AccTransTypeBIT'] = '';
                        $row_pay['ReferenceKey'] = 'BILLMICE'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        
                        $row_pay['AccountingTransactionDetails'] = array();
                        $row_pay['AccountingTransactionDetails']['length'] = 1;
                        $row_pay['AccountingTransactionDetails'][0]['ParentReferenceKey'] = 'BILLMICE'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        $row_pay['AccountingTransactionDetails'][0]['CreatedOn'] = date('Y-m-d');
                        $row_pay['AccountingTransactionDetails'][0]['ChangedOn'] = date('Y-m-d');
                        $row_pay['AccountingTransactionDetails'][0]['DebitAccCode'] = 112;
                        $row_pay['AccountingTransactionDetails'][0]['CreditAccCode'] = 511;
                        $row_pay['AccountingTransactionDetails'][0]['Description'] = $valuepay['description'];
                        $row_pay['AccountingTransactionDetails'][0]['Amount'] = $valuepay['amount'];
                        $row_pay['AccountingTransactionDetails'][0]['IsReceivables'] = '';
                        $row_pay['AccountingTransactionDetails'][0]['ReferenceKey'] = 'BILLMICE'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        
                        if($valuepay['payment_type_id']=='CREDIT_CARD' OR $valuepay['payment_type_id']=='BANK')
                        {
                            $row_pay['TransTypeCode'] = 'CNT';
                            $row_pay['ContactCode'] = $row[$value['mice_invoice_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = $row[$value['mice_invoice_id']]['ContactCode'];
                        }
                        elseif($valuepay['payment_type_id']=='CASH')
                        {
                            $row_pay['TransTypeCode'] = 'PGT';
                            $row_pay['ContactCode'] = 'ACC_'.$valuepay['user_id'];
                            $row_pay['ContactCode2'] = $row[$value['mice_invoice_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = 'ACC_'.$valuepay['user_id'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode2'] = $row[$value['mice_invoice_id']]['ContactCode'];
                        }
                        elseif($valuepay['payment_type_id']=='REFUND')
                        {
                            $row_pay['TransTypeCode'] = 'PCTLT';
                            $row_pay['ContactCode'] = $row[$value['mice_invoice_id']]['ContactCode'];
                            $row_pay['ContactCode2'] = 'ACC_'.$valuepay['user_id'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = $row[$value['mice_invoice_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode2'] = 'ACC_'.$valuepay['user_id'];
                        }
                        elseif($valuepay['payment_type_id']=='FOC')
                        {
                            $row_pay['TransTypeCode'] = 'GGCK';
                            $row_pay['ContactCode'] = $row[$value['mice_invoice_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = $row[$value['mice_invoice_id']]['ContactCode'];
                        }
                        $r = new HttpRequest(LINK_SYNC_CNS.'/api/AccountingTransactions',HttpRequest::METH_POST);
                        $r->addPostFields($row_pay);
                        try 
                        {
                            $r->send();
                            if($r->getResponseCode()==200)
                            {
                                DB::query("update payment set sync_cns=1 where id=".$valuepay['id']);
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
            }
            if($value['type']=='PACKAGE')
            {
                $ListPackage[$value['invoice_id']]['id'] = $value['invoice_id'];
                $ListPackage[$value['invoice_id']]['percent'] = $value['percent'];
                $ListPackage[$value['invoice_id']]['folio_id'] = $value['mice_invoice_id'];
                if($CondPackage=='')
                    $CondPackage = 'reservation_room.id='.$value['invoice_id'];
                else
                    $CondPackage .= ' OR reservation_room.id='.$value['invoice_id'];
            }
            elseif($value['type']=='MINIBAR')
            {
                $ListMinibar[$value['invoice_id']]['id'] = $value['invoice_id'];
                $ListMinibar[$value['invoice_id']]['percent'] = $value['percent'];
                $ListMinibar[$value['invoice_id']]['folio_id'] = $value['mice_invoice_id'];
                if($CondMinibar=='')
                    $CondMinibar = 'housekeeping_invoice.id='.$value['invoice_id'];
                else
                    $CondMinibar .= ' OR housekeeping_invoice.id='.$value['invoice_id'];
            }
            elseif($value['type']=='BAR')
            {
                $ListBar[$value['invoice_id']]['id'] = $value['invoice_id'];
                $ListBar[$value['invoice_id']]['percent'] = $value['percent'];
                $ListBar[$value['invoice_id']]['folio_id'] = $value['mice_invoice_id'];
                if($CondBar=='')
                    $CondBar = 'bar_reservation.id='.$value['invoice_id'];
                else
                    $CondBar .= ' OR bar_reservation.id='.$value['invoice_id'];
            }
            elseif($value['type']=='VE')
            {
                $ListVe[$value['invoice_id']]['id'] = $value['invoice_id'];
                $ListVe[$value['invoice_id']]['percent'] = $value['percent'];
                $ListVe[$value['invoice_id']]['folio_id'] = $value['mice_invoice_id'];
                if($CondVe=='')
                    $CondVe = 've_reservation.id='.$value['invoice_id'];
                else
                    $CondVe .= ' OR ve_reservation.id='.$value['invoice_id'];
            }
            elseif($value['type']=='KARAOKE')
            {
                $ListKaraoke[$value['invoice_id']]['id'] = $value['invoice_id'];
                $ListKaraoke[$value['invoice_id']]['percent'] = $value['percent'];
                $ListKaraoke[$value['invoice_id']]['folio_id'] = $value['mice_invoice_id'];
                if($CondKaraoke=='')
                    $CondKaraoke = 'karaoke_reservation.id='.$value['invoice_id'];
                else
                    $CondKaraoke .= ' OR karaoke_reservation.id='.$value['invoice_id'];
            }
            elseif($value['type']=='MASSAGE')
            {
                $ListMassage[$value['invoice_id']]['id'] = $value['invoice_id'];
                $ListMassage[$value['invoice_id']]['percent'] = $value['percent'];
                $ListMassage[$value['invoice_id']]['folio_id'] = $value['mice_invoice_id'];
                if($CondMassage=='')
                    $CondMassage = 'massage_reservation_room.id='.$value['invoice_id'];
                else
                    $CondMassage .= ' OR massage_reservation_room.id='.$value['invoice_id'];
            }
            elseif($value['type']=='DEPOSIT_GROUP' OR $value['type']=='DEPOSIT' OR $value['type']=='DEPOSIT_MICE')
            {
                $payment = DB::fetch_all("
                                        SELECT * FROM payment WHERE id=".$value['invoice_id']." AND sync_cns=0
                                        ");
                foreach($payment as $keypay=>$valuepay)
                {
                    if($valuepay['payment_type_id']=='CREDIT_CARD' OR $valuepay['payment_type_id']=='BANK' OR $valuepay['payment_type_id']=='CASH' OR $valuepay['payment_type_id']=='REFUND' OR $valuepay['payment_type_id']=='FOC')
                    {
                        $row_pay = array();
                        $row_pay['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                        $row_pay['CreatedOn'] = date('Y-m-d');
                        $row_pay['ChangedOn'] = date('Y-m-d');
                        $row_pay['Code'] = 'BILLMICE'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        $row_pay['TransDate'] = date('Y-m-d',$valuepay['time']);
                        $row_pay['Description'] = $valuepay['description'];
                        $row_pay['TotalAmount'] = $valuepay['amount'];
                        $row_pay['TotalTax'] = 0;
                        $row_pay['CustomerName'] = '';
                        $row_pay['CustomerAddress'] = '';
                        $row_pay['CustomerTaxCode'] = '';
                        $row_pay['CurrencyCode'] = $valuepay['currency_id'];
                        $row_pay['CurrencyRate'] = $valuepay['exchange_rate'];
                        $row_pay['IsReceivables'] = '';
                        $row_pay['AccTransTypeBIT'] = '';
                        $row_pay['ReferenceKey'] = 'BILMICE'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        
                        $row_pay['AccountingTransactionDetails'] = array();
                        $row_pay['AccountingTransactionDetails']['length'] = 1;
                        $row_pay['AccountingTransactionDetails'][0]['ParentReferenceKey'] = 'BILLMICE'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        $row_pay['AccountingTransactionDetails'][0]['CreatedOn'] = date('Y-m-d');
                        $row_pay['AccountingTransactionDetails'][0]['ChangedOn'] = date('Y-m-d');
                        $row_pay['AccountingTransactionDetails'][0]['DebitAccCode'] = 112;
                        $row_pay['AccountingTransactionDetails'][0]['CreditAccCode'] = 511;
                        $row_pay['AccountingTransactionDetails'][0]['Description'] = $valuepay['description'];
                        $row_pay['AccountingTransactionDetails'][0]['Amount'] = $valuepay['amount'];
                        $row_pay['AccountingTransactionDetails'][0]['IsReceivables'] = '';
                        $row_pay['AccountingTransactionDetails'][0]['ReferenceKey'] = 'BILLMICE'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        
                        if($valuepay['payment_type_id']=='CREDIT_CARD' OR $valuepay['payment_type_id']=='BANK')
                        {
                            $row_pay['TransTypeCode'] = 'CNT';
                            $row_pay['ContactCode'] = $row[$value['mice_invoice_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = $row[$value['mice_invoice_id']]['ContactCode'];
                        }
                        elseif($valuepay['payment_type_id']=='CASH')
                        {
                            $row_pay['TransTypeCode'] = 'PGT';
                            $row_pay['ContactCode'] = 'ACC_'.$valuepay['user_id'];
                            $row_pay['ContactCode2'] = $row[$value['mice_invoice_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = 'ACC_'.$valuepay['user_id'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode2'] = $row[$value['mice_invoice_id']]['ContactCode'];
                        }
                        elseif($valuepay['payment_type_id']=='REFUND')
                        {
                            $row_pay['TransTypeCode'] = 'PCTLT';
                            $row_pay['ContactCode'] = $row[$value['mice_invoice_id']]['ContactCode'];
                            $row_pay['ContactCode2'] = 'ACC_'.$valuepay['user_id'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = $row[$value['mice_invoice_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode2'] = 'ACC_'.$valuepay['user_id'];
                        }
                        elseif($valuepay['payment_type_id']=='FOC')
                        {
                            $row_pay['TransTypeCode'] = 'GGCK';
                            $row_pay['ContactCode'] = $row[$value['mice_invoice_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = $row[$value['mice_invoice_id']]['ContactCode'];
                        }
                        $r = new HttpRequest(LINK_SYNC_CNS.'/api/AccountingTransactions',HttpRequest::METH_POST);
                        $r->addPostFields($row_pay);
                        try 
                        {
                            $r->send();
                            if($r->getResponseCode()==200)
                            {
                                DB::query("update payment set sync_cns=1 where id=".$valuepay['id']);
                            }
                        } 
                        catch (HttpException $ex) 
                        {
                        }
                    }
                }
            }
        } // end foreach
        if($CondPackage!='')
        {
            /** get list package detail **/
            $package = DB::fetch_all("
                    SELECT 
                        package_sale_detail.id || '_' || reservation_room.id as id
                        ,package_sale_detail.id as package_sale_detail_id
                        ,reservation_room.id as reservation_room_id
                        ,department.code
                    FROM package_sale_detail
                        INNER JOIN package_sale ON package_sale.id=package_sale_detail.package_sale_id
                        INNER JOIN reservation_room ON reservation_room.package_sale_id=package_sale.id
                        INNER JOIN package_service ON package_service.id=package_sale_detail.service_id
                        INNER JOIN department ON package_service.department_id=department.id
                    WHERE 
                        (".$CondPackage.") 
                        AND (department.code='RES' OR department.code='SPA')
                    ");
            foreach($package as $keypackage=>$valuepackage)
            {
                if($valuepackage['code']=='RES')
                {
                    if($CondBar=='')
                        $CondBar = '(bar_reservation.package_id='.$valuepackage['package_sale_detail_id'].' AND bar_reservation.reservation_room_id='.$valuepackage['reservation_room_id'].')';
                    else
                        $CondBar .= ' OR (bar_reservation.package_id='.$valuepackage['package_sale_detail_id'].' AND bar_reservation.reservation_room_id='.$valuepackage['reservation_room_id'].')';
                }
                elseif($valuepackage['code']=='SPA')
                {
                    if($CondMassage=='')
                        $CondMassage = '(massage_reservation_room.package_id='.$valuepackage['package_sale_detail_id'].' AND massage_reservation_room.hotel_reservation_room_id='.$valuepackage['reservation_room_id'].')';
                    else
                        $CondMassage .= ' OR (massage_reservation_room.package_id='.$valuepackage['package_sale_detail_id'].' AND massage_reservation_room.hotel_reservation_room_id='.$valuepackage['reservation_room_id'].')';
                }
            }
        }
        
        if($CondMinibar!='')
        {
            /** get minibar **/
            $minibar = DB::fetch_all("
                                    SELECT
                                        housekeeping_invoice_detail.id
                                        ,housekeeping_invoice_detail.price
                                        ,housekeeping_invoice_detail.quantity
                                        ,housekeeping_invoice.id as housekeeping_invoice_id
                                        ,housekeeping_invoice.net_price
                                        ,housekeeping_invoice.fee_rate as service_rate
                                        ,housekeeping_invoice.tax_rate
                                        ,product.id as product_id
                                        ,unit.name_1 as unit_name
                                        ,warehouse.code as warehouse_code
                                    FROM
                                        housekeeping_invoice_detail
                                        inner join housekeeping_invoice on housekeeping_invoice.id=housekeeping_invoice_detail.invoice_id
                                        inner join product on product.id=housekeeping_invoice_detail.product_id
                                        inner join product_price_list on product.id=product_price_list.product_id
                                        inner join department on department.code = product_price_list.department_code
                                        inner join portal_department on portal_department.department_code = department.code AND portal_department.portal_id='".PORTAL_ID."'
                                        inner join warehouse on warehouse.id=portal_department.warehouse_id
                                        left join unit on unit.id=product.unit_id
                                    WHERE
                                        (".$CondMinibar.") AND (product.type = 'GOODS' OR product.type = 'PRODUCT' OR product.type = 'DRINK')
                                    ");
            foreach($minibar as $keyminibar=>$valueminibar)
            {
                $folio = $ListMinibar[$valueminibar['housekeeping_invoice_id']]['folio_id'];
                $percent = $ListMinibar[$valueminibar['housekeeping_invoice_id']]['percent'];
                $stt = $row[$folio]['WarehouseTransactionDetails']['length'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ParentReferenceKey'] = $row[$folio]['ReferenceKey'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                $row[$folio]['WarehouseTransactionDetails'][$stt]['WarehouseCode'] = $valueminibar['warehouse_code'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['WarehouseCode2'] = '';
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ItemCode'] = $valueminibar['product_id'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = 'MINIBAR';
                $row[$folio]['WarehouseTransactionDetails'][$stt]['FeeItemCode'] = $valueminibar['product_id'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitCode'] = $valueminibar['unit_name'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['Quantity'] = $valueminibar['quantity'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitCost'] = 0;
                
                if($valueminibar['net_price']==1)
                {
                    $valueminibar['price'] = $valueminibar['price'] / ((1+($valueminibar['service_rate']/100))*(1+($valueminibar['tax_rate']/100)));
                }
                
                $price = $valueminibar['price'] + ($valueminibar['price']*($valueminibar['service_rate']/100));
                $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitPrice'] = $price*($percent/100);
                $row[$folio]['WarehouseTransactionDetails'][$stt]['Amount'] = $price*($percent/100)*$valueminibar['quantity'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['Discount'] = 0;
                    
                    $row[$folio]['WarehouseCode'] = $valueminibar['warehouse_code'];
                    
                    $row[$folio]['TotalAmount'] += $price*($percent/100)*$valueminibar['quantity'];
                    $row[$folio]['TotalQuantity'] += $valueminibar['quantity'];
                    $row[$folio]['TotalTax'] += ($price*($percent/100)*$valueminibar['quantity']) * ($valueminibar['tax_rate']/100);
                    
                $row[$folio]['WarehouseTransactionDetails'][$stt]['LotNumber'] = 'MINIBAR_BILLMICE'.$valueminibar['id'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['SeriNumber'] = 'MINIBAR_BILLMICE'.$valueminibar['id'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['DebitAccCode'] = 0;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['DebitAccCode2'] = 0;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CreditAccCode'] = 0;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CreditAccCode2'] = 0;
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ReferenceKey'] = 'MINIBAR_HHA_BILLMICE'.$valueminibar['id'];
                $row[$folio]['WarehouseTransactionDetails']['length']++;
                
                $row[$folio]['FinancialBills']['length']++;
                $row[$folio]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row[$folio]['FinancialBills'][$stt]['WarehouseReferenceKey'] = 'HHA-BILLMICE'.$folio;
                $row[$folio]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
                $row[$folio]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
                $row[$folio]['FinancialBills'][$stt]['CaseItemCode'] = 'MINIBAR';
                $row[$folio]['FinancialBills'][$stt]['BillNumber'] = 'BILL_MICE_HHA'.$folio;
                $row[$folio]['FinancialBills'][$stt]['BillDate'] = $row[$folio]['TransDate'];
                $row[$folio]['FinancialBills'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                $row[$folio]['FinancialBills'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                $row[$folio]['FinancialBills'][$stt]['CustomerName'] = $row[$folio]['CustomerName'];
                $row[$folio]['FinancialBills'][$stt]['CustomerAddress'] = $row[$folio]['CustomerAddress'];
                $row[$folio]['FinancialBills'][$stt]['CustomerTaxCode'] = $row[$folio]['CustomerTaxCode'];
                $row[$folio]['FinancialBills'][$stt]['Content'] = 'HHA BILL_MICE ID '.$folio;
                $row[$folio]['FinancialBills'][$stt]['TotalQuantity'] = $valueminibar['quantity'];
                $row[$folio]['FinancialBills'][$stt]['TotalForeignAmount'] = 0;
                $row[$folio]['FinancialBills'][$stt]['TotalAmount'] = $price*($percent/100)*$valueminibar['quantity'];
                $row[$folio]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
                $row[$folio]['FinancialBills'][$stt]['TaxRate'] = $valueminibar['tax_rate'];
                $row[$folio]['FinancialBills'][$stt]['TaxAmount'] = ($price*($percent/100)*$valueminibar['quantity']) * ($valueminibar['tax_rate']/100);
                $row[$folio]['FinancialBills'][$stt]['Description'] = 'HHA BIL_MICE ID '.$folio;
                $row[$folio]['FinancialBills'][$stt]['DebitAccCode'] = 0;
                $row[$folio]['FinancialBills'][$stt]['CreditAccCode'] = 0;
                $row[$folio]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_HHA_BILLMICE_MINIBAR'.$valueminibar['id'];
            }
        }
        if($CondBar!='')
        {
        /** get bar **/
            $restaurant = DB::fetch_all("
                                        SELECT
                                            bar_reservation_product.id
                                            ,bar_reservation_product.price
                                            ,bar_reservation_product.quantity
                                            ,bar_reservation_product.quantity_discount
                                            ,bar_reservation_product.discount_rate
                                            ,bar_reservation.id as bar_reservation_id
                                            ,bar_reservation.bar_fee_rate as service_rate
                                            ,bar_reservation.tax_rate
                                            ,bar_reservation.discount_percent
                                            ,bar_reservation.full_charge
                                            ,bar_reservation.full_rate
                                            ,bar_reservation.reservation_room_id
                                            ,bar_reservation.package_id
                                            ,NVL(bar_reservation.extra_vat,0) as extra_vat
                                            ,bar_reservation.sync_cns_extra_vat
                                            ,product_category.structure_id
                                            ,product.id as product_id
                                            ,unit.name_1 as unit_name
                                            ,warehouse.code as warehouse_code
                                        FROM
                                            bar_reservation_product
                                            inner join bar_reservation on bar_reservation.id=bar_reservation_product.bar_reservation_id
                                            inner join product on product.id=bar_reservation_product.product_id
                                            inner join product_category on product_category.id=product.category_id
                                            inner join product_price_list on bar_reservation_product.price_id=product_price_list.id
                                            inner join department on department.code = product_price_list.department_code
                                            inner join portal_department on portal_department.department_code = department.code AND portal_department.portal_id='".PORTAL_ID."'
                                            inner join warehouse on warehouse.id=portal_department.warehouse_id
                                            left join unit on unit.id=product.unit_id
                                        WHERE
                                            (".$CondBar.") AND (product.type = 'GOODS' OR product.type = 'PRODUCT' OR product.type = 'DRINK')
                                        ");
            //System::debug($restaurant);
            $check_conflict_bar_reservation = array();
            foreach($restaurant as $keybar=>$valuebar)
            {
                if($valuebar['package_id']!='')
                {
                    $folio = $ListPackage[$valuebar['reservation_room_id']]['folio_id'];
                    $percent = $ListPackage[$valuebar['reservation_room_id']]['percent'];
                }
                else
                {
                    $folio = $ListBar[$valuebar['bar_reservation_id']]['folio_id'];
                    $percent = $ListBar[$valuebar['bar_reservation_id']]['percent'];
                }
                if(!isset($check_conflict_bar_reservation[$valuebar['bar_reservation_id']]) and $valuebar['extra_vat']!=0 and $valuebar['sync_cns_extra_vat']!=1)
                {
                    $check_conflict_bar_reservation[$valuebar['bar_reservation_id']] = $valuebar['bar_reservation_id'];
                    $stt = $row[$folio]['WarehouseTransactionDetails']['length'];
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['ParentReferenceKey'] = $row[$folio]['ReferenceKey'];
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['WarehouseCode'] = '';
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['WarehouseCode2'] = '';
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['ItemCode'] = 'OTHER_SERVICE';
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = 'OTHER_SERVICE';
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['FeeItemCode'] = 'OTHER_SERVICE';
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitCode'] = '';
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['Quantity'] = 1;
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitCost'] = 0;
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitPrice'] = $valuebar['extra_vat'];
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['Amount'] = $valuebar['extra_vat'];
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['Discount'] = 0;
                    
                        $row[$folio]['TotalAmount'] += $valuebar['extra_vat'];
                        $row[$folio]['TotalQuantity'] += 1;
                        $row[$folio]['TotalTax'] += 0;
                    
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['LotNumber'] = 'RESTAURANT_BILLMICE'.$valuebar['bar_reservation_id'];
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['SeriNumber'] = 'RESTAURANT_BILLMICE'.$valuebar['bar_reservation_id'];
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['DebitAccCode'] = 0;
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['DebitAccCode2'] = 0;
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['CreditAccCode'] = 0;
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['CreditAccCode2'] = 0;
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['ReferenceKey'] = 'RESTAURANT_HHA_EXTRA_VAT_BILLMICE'.$valuebar['bar_reservation_id'];
                    $row[$folio]['WarehouseTransactionDetails']['length']++;
                    DB::query("update bar_reservation set sync_cns_extra_vat=1 where id=".$valuebar['bar_reservation_id']);
                }
                $stt = $row[$folio]['WarehouseTransactionDetails']['length'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ParentReferenceKey'] = $row[$folio]['ReferenceKey'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['WarehouseCode'] = $valuebar['warehouse_code'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['WarehouseCode2'] = '';
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ItemCode'] = $valuebar['product_id'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = $valuebar['product_id'];
                if($valuebar['structure_id']>=$hcb_start_idstruct and $valuebar['structure_id']<$hcb_end_idstruct)
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = 'HCB';
                if($valuebar['structure_id']>=$du_start_idstruct and $valuebar['structure_id']<$du_end_idstruct)
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = 'DU';
                if($valuebar['structure_id']>=$da_start_idstruct and $valuebar['structure_id']<$da_end_idstruct)
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = 'DA';
                if($valuebar['structure_id']>=$dv_start_idstruct and $valuebar['structure_id']<$dv_end_idstruct)
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = 'DVNH';
                
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['FeeItemCode'] = $valuebar['product_id'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitCode'] = $valuebar['unit_name'];
                
                $quantity = $valuebar['quantity'] - $valuebar['quantity_discount'];
                $discount_rate = $valuebar['discount_rate'] + $valuebar['discount_percent'];
                $price = $valuebar['price'];
                if($valuebar['full_rate']==1)
                {
                    $price = $valuebar['price'] / ((1+($valuebar['service_rate']/100))*(1+($valuebar['tax_rate']/100)));
                }
                elseif($valuebar['full_rate']!=1 AND $valuebar['full_charge']==1)
                {
                    $price = $valuebar['price'] / (1+($valuebar['service_rate']/100));
                }
                
                $price = $price -(($discount_rate*$price)/100);
                $price = $price + ($price*($valuebar['service_rate']/100));
                
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['Quantity'] = $quantity;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitCost'] = 0;
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitPrice'] = $price*($percent/100);
                $row[$folio]['WarehouseTransactionDetails'][$stt]['Amount'] = $price*($percent/100)*$quantity;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['Discount'] = 0;
                
                    $row[$folio]['WarehouseCode'] = $valuebar['warehouse_code'];
                    
                    $row[$folio]['TotalAmount'] += $price*($percent/100)*$quantity;
                    $row[$folio]['TotalQuantity'] += $quantity;
                    $row[$folio]['TotalTax'] += ($price*($percent/100)*$quantity) * ($valuebar['tax_rate']/100);
                    
                    
                $row[$folio]['WarehouseTransactionDetails'][$stt]['LotNumber'] = 'RESTAURANT_BILLMICE'.$valuebar['id'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['SeriNumber'] = 'RESTAURANT_BILLMICE'.$valuebar['id'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['DebitAccCode'] = 0;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['DebitAccCode2'] = 0;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CreditAccCode'] = 0;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CreditAccCode2'] = 0;
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ReferenceKey'] = 'RESTAURANT_HHA_BILLMICE'.$valuebar['id'];
                $row[$folio]['WarehouseTransactionDetails']['length']++;
                
                $row[$folio]['FinancialBills']['length']++;
                $row[$folio]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row[$folio]['FinancialBills'][$stt]['WarehouseReferenceKey'] = 'HHA-BILLMICE'.$folio;
                $row[$folio]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
                $row[$folio]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
                $row[$folio]['FinancialBills'][$stt]['CaseItemCode'] = $row[$folio]['WarehouseTransactionDetails'][$stt]['CaseItemCode'];
                $row[$folio]['FinancialBills'][$stt]['BillNumber'] = 'BILL_MICE_HHA'.$folio;
                $row[$folio]['FinancialBills'][$stt]['BillDate'] = $row[$folio]['TransDate'];
                $row[$folio]['FinancialBills'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                $row[$folio]['FinancialBills'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                $row[$folio]['FinancialBills'][$stt]['CustomerName'] = $row[$folio]['CustomerName'];
                $row[$folio]['FinancialBills'][$stt]['CustomerAddress'] = $row[$folio]['CustomerAddress'];
                $row[$folio]['FinancialBills'][$stt]['CustomerTaxCode'] = $row[$folio]['CustomerTaxCode'];
                $row[$folio]['FinancialBills'][$stt]['Content'] = 'HHA BILL_MICE ID '.$folio;
                $row[$folio]['FinancialBills'][$stt]['TotalQuantity'] = $quantity;
                $row[$folio]['FinancialBills'][$stt]['TotalForeignAmount'] = 0;
                $row[$folio]['FinancialBills'][$stt]['TotalAmount'] = $price*($percent/100)*$quantity;
                $row[$folio]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
                $row[$folio]['FinancialBills'][$stt]['TaxRate'] = $valuebar['tax_rate'];
                $row[$folio]['FinancialBills'][$stt]['TaxAmount'] = ($price*($percent/100)*$quantity) * ($valuebar['tax_rate']/100);
                $row[$folio]['FinancialBills'][$stt]['Description'] = 'HHA BIL_MICE ID '.$folio;
                $row[$folio]['FinancialBills'][$stt]['DebitAccCode'] = 0;
                $row[$folio]['FinancialBills'][$stt]['CreditAccCode'] = 0;
                $row[$folio]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_HHA_BILLMICE_BAR'.$valuebar['id'];
            }
        }
        
        if($CondVe!='')
        {
        /** get bar **/
            $vending = DB::fetch_all("
                                        SELECT
                                            ve_reservation_product.id
                                            ,ve_reservation_product.price
                                            ,ve_reservation_product.quantity
                                            ,ve_reservation_product.quantity_discount
                                            ,ve_reservation_product.discount_rate
                                            ,ve_reservation.id as bar_reservation_id
                                            ,ve_reservation.bar_fee_rate as service_rate
                                            ,ve_reservation.tax_rate
                                            ,ve_reservation.discount_percent
                                            ,ve_reservation.full_charge
                                            ,ve_reservation.full_rate
                                            ,ve_reservation.reservation_room_id
                                            ,ve_reservation.department_code
                                            ,product.id as product_id
                                            ,unit.name_1 as unit_name
                                            ,warehouse.code as warehouse_code
                                        FROM
                                            ve_reservation_product
                                            inner join ve_reservation on ve_reservation.id=ve_reservation_product.bar_reservation_id
                                            inner join product on product.id=ve_reservation_product.product_id
                                            inner join department on department.code = ve_reservation.department_code
                                            inner join portal_department on portal_department.department_code = department.code AND portal_department.portal_id='".PORTAL_ID."'
                                            inner join warehouse on warehouse.id=portal_department.warehouse_id
                                            left join unit on unit.id=product.unit_id
                                        WHERE
                                            (".$CondVe.") AND (product.type = 'GOODS' OR product.type = 'PRODUCT' OR product.type = 'DRINK')
                                        ");
            //System::debug($vending);
            foreach($vending as $keybar=>$valuebar)
            {
                $folio = $ListVe[$valuebar['bar_reservation_id']]['folio_id'];
                $percent = $ListVe[$valuebar['bar_reservation_id']]['percent'];
                $stt = $row[$folio]['WarehouseTransactionDetails']['length'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ParentReferenceKey'] = $row[$folio]['ReferenceKey'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['WarehouseCode'] = $valuebar['warehouse_code'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['WarehouseCode2'] = '';
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ItemCode'] = $valuebar['product_id'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = 'VEND_'.$valuebar['department_code'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['FeeItemCode'] = $valuebar['product_id'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitCode'] = $valuebar['unit_name'];
                
                $quantity = $valuebar['quantity'] - $valuebar['quantity_discount'];
                $discount_rate = $valuebar['discount_rate'] + $valuebar['discount_percent'];
                $price = $valuebar['price'];
                if($valuebar['full_rate']==1)
                {
                    $price = $valuebar['price'] / ((1+($valuebar['service_rate']/100))*(1+($valuebar['tax_rate']/100)));
                }
                elseif($valuebar['full_rate']!=1 AND $valuebar['full_charge']==1)
                {
                    $price = $valuebar['price'] / (1+($valuebar['service_rate']/100));
                }
                
                $price = $price -(($discount_rate*$price)/100);
                $price = $price + ($price*($valuebar['service_rate']/100));
                
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['Quantity'] = $quantity;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitCost'] = 0;
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitPrice'] = $price*($percent/100);
                $row[$folio]['WarehouseTransactionDetails'][$stt]['Amount'] = $price*($percent/100)*$quantity;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['Discount'] = 0;
                
                    $row[$folio]['WarehouseCode'] = $valuebar['warehouse_code'];
                    
                    $row[$folio]['TotalAmount'] += $price*($percent/100)*$quantity;
                    $row[$folio]['TotalQuantity'] += $quantity;
                    $row[$folio]['TotalTax'] += ($price*($percent/100)*$quantity) * ($valuebar['tax_rate']/100);
                    
                $row[$folio]['WarehouseTransactionDetails'][$stt]['LotNumber'] = 'VEND_BILLMICE'.$valuebar['id'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['SeriNumber'] = 'VEND_BILLMICE'.$valuebar['id'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['DebitAccCode'] = 0;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['DebitAccCode2'] = 0;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CreditAccCode'] = 0;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CreditAccCode2'] = 0;
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ReferenceKey'] = 'VEND_HHA_BILLMICE'.$valuebar['id'];
                $row[$folio]['WarehouseTransactionDetails']['length']++;
                
                $row[$folio]['FinancialBills']['length']++;
                $row[$folio]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row[$folio]['FinancialBills'][$stt]['WarehouseReferenceKey'] = 'HHA-BILLMICE'.$folio;
                $row[$folio]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
                $row[$folio]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
                $row[$folio]['FinancialBills'][$stt]['CaseItemCode'] = 'VEND_'.$valuebar['department_code'];
                $row[$folio]['FinancialBills'][$stt]['BillNumber'] = 'BILL_MICE_HHA'.$folio;
                $row[$folio]['FinancialBills'][$stt]['BillDate'] = $row[$folio]['TransDate'];
                $row[$folio]['FinancialBills'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                $row[$folio]['FinancialBills'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                $row[$folio]['FinancialBills'][$stt]['CustomerName'] = $row[$folio]['CustomerName'];
                $row[$folio]['FinancialBills'][$stt]['CustomerAddress'] = $row[$folio]['CustomerAddress'];
                $row[$folio]['FinancialBills'][$stt]['CustomerTaxCode'] = $row[$folio]['CustomerTaxCode'];
                $row[$folio]['FinancialBills'][$stt]['Content'] = 'HHA BILL_MICE ID '.$folio;
                $row[$folio]['FinancialBills'][$stt]['TotalQuantity'] = $quantity;
                $row[$folio]['FinancialBills'][$stt]['TotalForeignAmount'] = 0;
                $row[$folio]['FinancialBills'][$stt]['TotalAmount'] = $price*($percent/100)*$quantity;
                $row[$folio]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
                $row[$folio]['FinancialBills'][$stt]['TaxRate'] = $valuebar['tax_rate'];
                $row[$folio]['FinancialBills'][$stt]['TaxAmount'] = ($price*($percent/100)*$quantity) * ($valuebar['tax_rate']/100);
                $row[$folio]['FinancialBills'][$stt]['Description'] = 'HHA BIL_MICE ID '.$folio;
                $row[$folio]['FinancialBills'][$stt]['DebitAccCode'] = 0;
                $row[$folio]['FinancialBills'][$stt]['CreditAccCode'] = 0;
                $row[$folio]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_HHA_BILLMICE_VEND'.$valuebar['id'];
            }
        }
        
        /** get massage */
        if($CondMassage!='')
        {
            $massage = DB::fetch_all("
                                    SELECT
                                        massage_product_consumed.id
                                        ,massage_product_consumed.quantity
                                        ,massage_product_consumed.price
                                        ,massage_reservation_room.id as massage_reservation_room_id
                                        ,NVL(massage_reservation_room.discount,0) as discount
                                        ,massage_reservation_room.service_rate
                                        ,massage_reservation_room.tax as tax_rate
                                        ,massage_reservation_room.net_price
                                        ,massage_reservation_room.hotel_reservation_room_id
                                        ,massage_reservation_room.package_id
                                        ,product.id as product_id
                                        ,unit.name_1 as unit_name
                                        ,warehouse.code as warehouse_code
                                    FROM
                                        massage_product_consumed
                                        inner join massage_reservation_room on massage_reservation_room.id=massage_product_consumed.reservation_room_id
                                        inner join product on product.id=massage_product_consumed.product_id
                                        inner join product_price_list on product.id=product_price_list.product_id
                                        inner join department on department.code = product_price_list.department_code
                                        inner join portal_department on portal_department.department_code = department.code AND portal_department.portal_id='".PORTAL_ID."'
                                        inner join warehouse on warehouse.id=portal_department.warehouse_id
                                        left join unit on unit.id=product.unit_id
                                    WHERE
                                        (".$CondMassage.") AND (product.type = 'GOODS' OR product.type = 'PRODUCT' OR product.type = 'DRINK')
                                    ");
            //echo "****** SPATEST *******";
            //System::debug($massage);
            foreach($massage as $keymassage=>$valuemassage)
            {
                if($valuemassage['package_id']!='')
                {
                    $folio = $ListPackage[$valuemassage['reservation_room_id']]['folio_id'];
                    $percent = $ListPackage[$valuemassage['reservation_room_id']]['percent'];
                }
                else
                {
                    $folio = $ListMassage[$valuemassage['massage_reservation_room_id']]['folio_id'];
                    $percent = $ListMassage[$valuemassage['massage_reservation_room_id']]['percent'];
                }
                $stt = $row[$folio]['WarehouseTransactionDetails']['length'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ParentReferenceKey'] = $row[$folio]['ReferenceKey'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['WarehouseCode'] = $valuemassage['warehouse_code'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['WarehouseCode2'] = '';
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ItemCode'] = $valuemassage['product_id'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = 'SPA';
                $row[$folio]['WarehouseTransactionDetails'][$stt]['FeeItemCode'] = $valuemassage['product_id'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitCode'] = $valuemassage['unit_name'];
                
                $quantity = $valuemassage['quantity'];
                $discount_rate = $valuemassage['discount'];
                $price = $valuemassage['price'];
                if($valuemassage['net_price']==1)
                {
                    $price = $valuemassage['price'] / ((1+($valuemassage['service_rate']/100))*(1+($valuemassage['tax_rate']/100)));
                }
                
                $price = $price -(($discount_rate*$price)/100);
                $price = $price + ($price*($valuemassage['service_rate']/100));
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['Quantity'] = $quantity;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitCost'] = 0;
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitPrice'] = $price*($percent/100);
                $row[$folio]['WarehouseTransactionDetails'][$stt]['Amount'] = $price*($percent/100)*$quantity;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['Discount'] = 0;
                
                    $row[$folio]['WarehouseCode'] = $valuemassage['warehouse_code'];
                
                    $row[$folio]['TotalAmount'] += $price*($percent/100)*$quantity;
                    $row[$folio]['TotalQuantity'] += $quantity;
                    $row[$folio]['TotalTax'] += ($price*($percent/100)*$quantity) * ($valuemassage['tax_rate']/100);
                    
                $row[$folio]['WarehouseTransactionDetails'][$stt]['LotNumber'] = 'MASSAGE_BILLMICE'.$valuemassage['id'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['SeriNumber'] = 'MASSAGE_BILLMICE'.$valuemassage['id'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['DebitAccCode'] = 0;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['DebitAccCode2'] = 0;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CreditAccCode'] = 0;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CreditAccCode2'] = 0;
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ReferenceKey'] = 'MASSAGE_HHA_BILLMICE'.$valuemassage['id'];
                $row[$folio]['WarehouseTransactionDetails']['length']++;
                
                $row[$folio]['FinancialBills']['length']++;
                $row[$folio]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row[$folio]['FinancialBills'][$stt]['WarehouseReferenceKey'] = 'HHA-BILLMICE'.$folio;
                $row[$folio]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
                $row[$folio]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
                $row[$folio]['FinancialBills'][$stt]['CaseItemCode'] = 'SPA';
                $row[$folio]['FinancialBills'][$stt]['BillNumber'] = 'BILL_MICE_HHA'.$folio;
                $row[$folio]['FinancialBills'][$stt]['BillDate'] = $row[$folio]['TransDate'];
                $row[$folio]['FinancialBills'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                $row[$folio]['FinancialBills'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                $row[$folio]['FinancialBills'][$stt]['CustomerName'] = $row[$folio]['CustomerName'];
                $row[$folio]['FinancialBills'][$stt]['CustomerAddress'] = $row[$folio]['CustomerAddress'];
                $row[$folio]['FinancialBills'][$stt]['CustomerTaxCode'] = $row[$folio]['CustomerTaxCode'];
                $row[$folio]['FinancialBills'][$stt]['Content'] = 'HHA BILL_MICE ID '.$folio;
                $row[$folio]['FinancialBills'][$stt]['TotalQuantity'] = $quantity;
                $row[$folio]['FinancialBills'][$stt]['TotalForeignAmount'] = 0;
                $row[$folio]['FinancialBills'][$stt]['TotalAmount'] = $price*($percent/100)*$quantity;
                $row[$folio]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
                $row[$folio]['FinancialBills'][$stt]['TaxRate'] = $valuemassage['tax_rate'];
                $row[$folio]['FinancialBills'][$stt]['TaxAmount'] = ($price*($percent/100)*$quantity) * ($valuemassage['tax_rate']/100);
                $row[$folio]['FinancialBills'][$stt]['Description'] = 'HHA BIL_MICE ID '.$folio;
                $row[$folio]['FinancialBills'][$stt]['DebitAccCode'] = 0;
                $row[$folio]['FinancialBills'][$stt]['CreditAccCode'] = 0;
                $row[$folio]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_HHA_BILLMICE_SPA'.$valuemassage['id'];
            }
        }
        return $row;
    }// end mice
    
    function get_vend()
    {
        $row = array();
        $vending = DB::fetch_all("
                                    SELECT
                                        ve_reservation_product.id
                                        ,ve_reservation_product.price
                                        ,ve_reservation_product.quantity
                                        ,ve_reservation_product.quantity_discount
                                        ,ve_reservation_product.discount_rate
                                        ,ve_reservation.id as bar_reservation_id
                                        ,ve_reservation.bar_fee_rate as service_rate
                                        ,ve_reservation.tax_rate
                                        ,ve_reservation.discount_percent
                                        ,ve_reservation.full_charge
                                        ,ve_reservation.full_rate
                                        ,ve_reservation.customer_id
                                        ,ve_reservation.time
                                        ,ve_reservation.department_code
                                        ,customer.name as customer_name
                                        ,customer.address as customer_address
                                        ,customer.tax_code as customer_tax_code
                                        ,product.id as product_id
                                        ,unit.name_1 as unit_name
                                        ,payment.time as payment_time
                                        ,payment.user_id as user_id
                                        ,warehouse.code as warehouse_code
                                    FROM
                                        ve_reservation_product
                                        inner join ve_reservation on ve_reservation.id=ve_reservation_product.bar_reservation_id
                                        inner join payment on payment.bill_id=ve_reservation.id AND payment.type='VEND'
                                        inner join product on product.id=ve_reservation_product.product_id
                                        inner join product_price_list on ve_reservation_product.price_id=product_price_list.id
                                        inner join department on department.code = product_price_list.department_code
                                        inner join portal_department on portal_department.department_code = department.code AND portal_department.portal_id='".PORTAL_ID."'
                                        inner join warehouse on warehouse.id=portal_department.warehouse_id
                                        left join unit on unit.id=product.unit_id
                                        left join customer on ve_reservation.customer_id=customer.id
                                    WHERE
                                        ve_reservation.pay_with_room = 0 
                                        AND ve_reservation.status='CHECKIN'
                                        AND ve_reservation.sync_cns_hh = 0
                                        AND ve_reservation.portal_id='".PORTAL_ID."'
                                        and (ve_reservation.reservation_room_id is null OR ve_reservation.reservation_room_id=0)
                                        AND (product.type = 'GOODS' OR product.type = 'PRODUCT' OR product.type = 'DRINK')
                                        AND payment.time >= ".Date_Time::to_time(DATE_SYNC_CNS)."
                                    ");
        foreach($vending as $keybar=>$valuebar)
        {
            $in_date = date('d/m/Y',$valuebar['payment_time']);
            if(!isset($row['VEND'.$valuebar['bar_reservation_id']]))
            {
                $row['VEND'.$valuebar['bar_reservation_id']]['id'] = $valuebar['bar_reservation_id'];
                $row['VEND'.$valuebar['bar_reservation_id']]['ReferenceKey'] = 'VEND_HHA'.$valuebar['bar_reservation_id'];
                $row['VEND'.$valuebar['bar_reservation_id']]['CreatedOn'] = date('Y-m-d');
                $row['VEND'.$valuebar['bar_reservation_id']]['ChangedOn'] = date('Y-m-d');
                $row['VEND'.$valuebar['bar_reservation_id']]['CustomerName'] = $valuebar['customer_name'];
                $row['VEND'.$valuebar['bar_reservation_id']]['CustomerAddress'] = $valuebar['customer_address'];
                $row['VEND'.$valuebar['bar_reservation_id']]['CustomerTaxCode'] = $valuebar['customer_tax_code'];
                
                $row['VEND'.$valuebar['bar_reservation_id']]['CurrencyCode'] = 'VND';
                $row['VEND'.$valuebar['bar_reservation_id']]['CurrencyRate'] = 0;
                
                if($valuebar['customer_id']!='')
                    $row['VEND'.$valuebar['bar_reservation_id']]['ContactCode'] = 'CUS_'.$valuebar['customer_id'];
                else
                    $row['VEND'.$valuebar['bar_reservation_id']]['ContactCode'] = 'KHACHLE_HOTEL';
                
                $row['VEND'.$valuebar['bar_reservation_id']]['ContactCode2'] = '';
                $row['VEND'.$valuebar['bar_reservation_id']]['payment'] = 1;
                
                $row['VEND'.$valuebar['bar_reservation_id']]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseCode'] = $valuebar['warehouse_code'];
                $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseCode2'] = '';
                
                $row['VEND'.$valuebar['bar_reservation_id']]['Description'] = 'VEND_HHA';
                $row['VEND'.$valuebar['bar_reservation_id']]['TotalAmount'] = 0;
                $row['VEND'.$valuebar['bar_reservation_id']]['TotalQuantity'] = 0;
                $row['VEND'.$valuebar['bar_reservation_id']]['TotalCost'] = 0;
                $row['VEND'.$valuebar['bar_reservation_id']]['TotalDiscount'] = 0;
                $row['VEND'.$valuebar['bar_reservation_id']]['TotalTax'] = 0;
                
                $row['VEND'.$valuebar['bar_reservation_id']]['TransTypeCode'] = 'HHA';
                $row['VEND'.$valuebar['bar_reservation_id']]['Code'] = 'VEND_HHA'.$valuebar['bar_reservation_id'];
                
                $create_date = explode('/',$in_date);
                $row['VEND'.$valuebar['bar_reservation_id']]['TransDate'] = $create_date[2].'-'.$create_date[1].'-'.$create_date[0];
                
                $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'] = array();
                $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails']['length'] = 0;
                
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'] = array();
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills']['length'] = 0;
                
                /** lay cac khoan thanh toan trong chung tu day sang CNS **/
                $payment = DB::fetch_all("
                                        SELECT * FROM payment WHERE bill_id='".$valuebar['bar_reservation_id']."' AND type='VEND' AND sync_cns=0
                                        ");
                foreach($payment as $keypay=>$valuepay)
                {
                    if($valuepay['payment_type_id']=='CREDIT_CARD' OR $valuepay['payment_type_id']=='BANK' OR $valuepay['payment_type_id']=='CASH' OR $valuepay['payment_type_id']=='REFUND' OR $valuepay['payment_type_id']=='FOC')
                    {
                        $row_pay = array();
                        $row_pay['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                        $row_pay['CreatedOn'] = date('Y-m-d');
                        $row_pay['ChangedOn'] = date('Y-m-d');
                        $row_pay['Code'] = 'VEND'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        $row_pay['TransDate'] = date('Y-m-d',$valuepay['time']);
                        $row_pay['Description'] = $valuepay['description'];
                        
                            $row_pay['TotalAmount'] = $valuepay['amount']*$valuepay['exchange_rate'];
                        if($valuepay['currency_id']!='VND')
                            $row_pay['TotalForeignAmount'] = $valuepay['amount'];
                        $row_pay['TotalTax'] = 0;
                        $row_pay['CustomerName'] = '';
                        $row_pay['CustomerAddress'] = '';
                        $row_pay['CustomerTaxCode'] = '';
                        $row_pay['CurrencyCode'] = $valuepay['currency_id'];
                        $row_pay['CurrencyRate'] = $valuepay['exchange_rate'];
                        $row_pay['IsReceivables'] = '';
                        $row_pay['AccTransTypeBIT'] = '';
                        $row_pay['ReferenceKey'] = 'VEND'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        
                        $row_pay['AccountingTransactionDetails'] = array();
                        $row_pay['AccountingTransactionDetails']['length'] = 1;
                        $row_pay['AccountingTransactionDetails'][0]['ParentReferenceKey'] = 'VEND'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        $row_pay['AccountingTransactionDetails'][0]['CreatedOn'] = date('Y-m-d');
                        $row_pay['AccountingTransactionDetails'][0]['ChangedOn'] = date('Y-m-d');
                        $row_pay['AccountingTransactionDetails'][0]['DebitAccCode'] = 112;
                        $row_pay['AccountingTransactionDetails'][0]['CreditAccCode'] = 511;
                        $row_pay['AccountingTransactionDetails'][0]['Description'] = $valuepay['description'];
                        
                            $row_pay['AccountingTransactionDetails'][0]['Amount'] = $valuepay['amount']*$valuepay['exchange_rate'];
                        if($valuepay['currency_id']!='VND') 
                            $row_pay['AccountingTransactionDetails'][0]['ForeignAmount'] = $valuepay['amount'];
                        $row_pay['AccountingTransactionDetails'][0]['IsReceivables'] = '';
                        $row_pay['AccountingTransactionDetails'][0]['ReferenceKey'] = 'VEND'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        
                        if($valuepay['payment_type_id']=='CREDIT_CARD' OR $valuepay['payment_type_id']=='BANK')
                        {
                            $row_pay['TransTypeCode'] = 'CNT';
                            $row_pay['ContactCode'] = $row['VEND'.$valuebar['bar_reservation_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = $row['VEND'.$valuebar['bar_reservation_id']]['ContactCode'];
                        }
                        elseif($valuepay['payment_type_id']=='CASH')
                        {
                            $row_pay['TransTypeCode'] = 'PGT';
                            $row_pay['ContactCode'] = 'ACC_'.$valuepay['user_id'];
                            $row_pay['ContactCode2'] = $row['VEND'.$valuebar['bar_reservation_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = 'ACC_'.$valuepay['user_id'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode2'] = $row['VEND'.$valuebar['bar_reservation_id']]['ContactCode'];
                        }
                        elseif($valuepay['payment_type_id']=='REFUND')
                        {
                            $row_pay['TransTypeCode'] = 'PCTLT';
                            $row_pay['ContactCode'] = $row['VEND'.$valuebar['bar_reservation_id']]['ContactCode'];
                            $row_pay['ContactCode2'] = 'ACC_'.$valuepay['user_id'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = $row['VEND'.$valuebar['bar_reservation_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode2'] = 'ACC_'.$valuepay['user_id'];
                        }
                        elseif($valuepay['payment_type_id']=='FOC')
                        {
                            $row_pay['TransTypeCode'] = 'GGCK';
                            $row_pay['ContactCode'] = $row['VEND'.$valuebar['bar_reservation_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = $row['VEND'.$valuebar['bar_reservation_id']]['ContactCode'];
                        }
                        $r = new HttpRequest(LINK_SYNC_CNS.'/api/AccountingTransactions',HttpRequest::METH_POST);
                        $r->addPostFields($row_pay);
                        try 
                        {
                            $r->send();
                            if($r->getResponseCode()==200)
                            {
                                DB::query("update payment set sync_cns=1 where id=".$valuepay['id']);
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
                
            }
            $stt = $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails']['length'];
            
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['ParentReferenceKey'] = $row['VEND'.$valuebar['bar_reservation_id']]['ReferenceKey'];
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['WarehouseCode'] = $valuebar['warehouse_code'];
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['WarehouseCode2'] = '';
            
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['ContactCode'] = $row['VEND'.$valuebar['bar_reservation_id']]['ContactCode'];
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['ContactCode2'] = $row['VEND'.$valuebar['bar_reservation_id']]['ContactCode2'];
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['ItemCode'] = $valuebar['product_id'];
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = 'VEND_'.$valuebar['department_code'];
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['FeeItemCode'] = $valuebar['product_id'];
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['UnitCode'] = $valuebar['unit_name'];
            
            $quantity = $valuebar['quantity'] - $valuebar['quantity_discount'];
            $discount_rate = $valuebar['discount_rate'] + $valuebar['discount_percent'];
            $price = $valuebar['price'];
            if($valuebar['full_rate']==1)
            {
                $price = $valuebar['price'] / ((1+($valuebar['service_rate']/100))*(1+($valuebar['tax_rate']/100)));
            }
            elseif($valuebar['full_rate']!=1 AND $valuebar['full_charge']==1)
            {
                $price = $valuebar['price'] / (1+($valuebar['service_rate']/100));
            }
            
            $price = $price -(($discount_rate*$price)/100);
            $price = $price + ($price*($valuebar['service_rate']/100));
            
            
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['Quantity'] = $quantity;
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['UnitCost'] = 0;
            
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['UnitPrice'] = $price;
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['Amount'] = $price*$quantity;
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['Discount'] = 0;
            
                $row['VEND'.$valuebar['bar_reservation_id']]['TotalAmount'] += $price*$quantity;
                $row['VEND'.$valuebar['bar_reservation_id']]['TotalQuantity'] += $quantity;
                $row['VEND'.$valuebar['bar_reservation_id']]['TotalTax'] += ($price*$quantity) * ($valuebar['tax_rate']/100);
                
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['LotNumber'] = 'VEND_'.$valuebar['id'];
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['SeriNumber'] = 'VEND_'.$valuebar['id'];
            
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['DebitAccCode'] = 0;
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['DebitAccCode2'] = 0;
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['CreditAccCode'] = 0;
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['CreditAccCode2'] = 0;
            
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['ReferenceKey'] = 'VEND_HHA_'.$valuebar['id'];
            $row['VEND'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails']['length']++;
            
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills']['length']++;
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['WarehouseReferenceKey'] = 'VEND_HHA'.$valuebar['bar_reservation_id'];
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CaseItemCode'] = 'VEND_'.$valuebar['department_code'];
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['BillNumber'] = 'BILL_VEND_HHA'.$valuebar['bar_reservation_id'];
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['BillDate'] = $row['VEND'.$valuebar['bar_reservation_id']]['TransDate'];
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['ContactCode'] = $row['VEND'.$valuebar['bar_reservation_id']]['ContactCode'];
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['ContactCode2'] = $row['VEND'.$valuebar['bar_reservation_id']]['ContactCode2'];
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CustomerName'] = $valuebar['customer_name'];
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CustomerAddress'] = $valuebar['customer_address'];
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CustomerTaxCode'] = $valuebar['customer_tax_code'];
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['Content'] = 'HHA VEND RESERVATION ID '.$valuebar['bar_reservation_id'];
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['TotalQuantity'] = $quantity;
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['TotalForeignAmount'] = 0;
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['TotalAmount'] = $price*$quantity;
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['TaxRate'] = $valuebar['tax_rate'];
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['TaxAmount'] = ($price*$quantity) * ($valuebar['tax_rate']/100);
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['Description'] = 'HHA VEND RESERVATION ID '.$valuebar['bar_reservation_id'];
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['DebitAccCode'] = 0;
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CreditAccCode'] = 0;
            $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_VEND_HHA'.$valuebar['id'];
        }
        
        return $row;
    }
    
    function get_bar()
    {
        $row = array();
        $restaurant = DB::fetch_all("
                                    SELECT
                                        bar_reservation_product.id
                                        ,bar_reservation_product.price
                                        ,bar_reservation_product.quantity
                                        ,bar_reservation_product.quantity_discount
                                        ,bar_reservation_product.discount_rate
                                        ,bar_reservation.id as bar_reservation_id
                                        ,bar_reservation.bar_fee_rate as service_rate
                                        ,bar_reservation.tax_rate
                                        ,bar_reservation.discount_percent
                                        ,bar_reservation.full_charge
                                        ,bar_reservation.full_rate
                                        ,bar_reservation.customer_id
                                        ,bar_reservation.time
                                        ,product_category.structure_id
                                        ,customer.name as customer_name
                                        ,customer.address as customer_address
                                        ,customer.tax_code as customer_tax_code
                                        ,product.id as product_id
                                        ,product.type as product_type
                                        ,unit.name_1 as unit_name
                                        ,payment.time as payment_time
                                        ,payment.user_id as user_id
                                        ,warehouse.code as warehouse_code
                                    FROM
                                        bar_reservation_product
                                        inner join bar_reservation on bar_reservation.id=bar_reservation_product.bar_reservation_id
                                        inner join payment on payment.bill_id=bar_reservation.id AND payment.type='BAR'
                                        inner join product on product.id=bar_reservation_product.product_id
                                        inner join product_category on product_category.id=product.category_id
                                        inner join product_price_list on bar_reservation_product.price_id=product_price_list.id
                                        inner join department on department.code = product_price_list.department_code
                                        inner join portal_department on portal_department.department_code = department.code AND portal_department.portal_id='".PORTAL_ID."'
                                        inner join warehouse on warehouse.id=portal_department.warehouse_id
                                        left join unit on unit.id=product.unit_id
                                        left join customer on bar_reservation.customer_id=customer.id
                                    WHERE
                                        bar_reservation.pay_with_room = 0 
                                        AND bar_reservation.package_id is null
                                        AND bar_reservation.status='CHECKOUT'
                                        AND bar_reservation.sync_cns_hh = 0
                                        AND bar_reservation.portal_id='".PORTAL_ID."'
                                        and (bar_reservation.mice_reservation_id is null OR bar_reservation.mice_reservation_id=0)
                                        and (bar_reservation.reservation_room_id is null OR bar_reservation.reservation_room_id=0)
                                        AND (product.type = 'GOODS' OR product.type = 'PRODUCT' OR product.type = 'DRINK')
                                        AND payment.time >= ".Date_Time::to_time(DATE_SYNC_CNS)."
                                    ");
        foreach($restaurant as $keybar=>$valuebar)
        {
            $in_date = date('d/m/Y',$valuebar['payment_time']);
            if(!isset($row['BAR'.$valuebar['bar_reservation_id']]))
            {
                $row['BAR'.$valuebar['bar_reservation_id']]['id'] = $valuebar['bar_reservation_id'];
                $row['BAR'.$valuebar['bar_reservation_id']]['ReferenceKey'] = 'BAR_HHA'.$valuebar['bar_reservation_id'];
                $row['BAR'.$valuebar['bar_reservation_id']]['CreatedOn'] = date('Y-m-d');
                $row['BAR'.$valuebar['bar_reservation_id']]['ChangedOn'] = date('Y-m-d');
                $row['BAR'.$valuebar['bar_reservation_id']]['CustomerName'] = $valuebar['customer_name'];
                $row['BAR'.$valuebar['bar_reservation_id']]['CustomerAddress'] = $valuebar['customer_address'];
                $row['BAR'.$valuebar['bar_reservation_id']]['CustomerTaxCode'] = $valuebar['customer_tax_code'];
                
                $row['BAR'.$valuebar['bar_reservation_id']]['CurrencyCode'] = 'VND';
                $row['BAR'.$valuebar['bar_reservation_id']]['CurrencyRate'] = 0;
                
                if($valuebar['customer_id']!='')
                    $row['BAR'.$valuebar['bar_reservation_id']]['ContactCode'] = 'CUS_'.$valuebar['customer_id'];
                else
                    $row['BAR'.$valuebar['bar_reservation_id']]['ContactCode'] = 'KHACHLE_HOTEL';
                
                $row['BAR'.$valuebar['bar_reservation_id']]['ContactCode2'] = '';
                $row['BAR'.$valuebar['bar_reservation_id']]['payment'] = 1;
                
                $row['BAR'.$valuebar['bar_reservation_id']]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseCode'] = $valuebar['warehouse_code'];
                $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseCode2'] = '';
                
                $row['BAR'.$valuebar['bar_reservation_id']]['Description'] = 'BAR_HHA';
                $row['BAR'.$valuebar['bar_reservation_id']]['TotalAmount'] = 0;
                $row['BAR'.$valuebar['bar_reservation_id']]['TotalQuantity'] = 0;
                $row['BAR'.$valuebar['bar_reservation_id']]['TotalCost'] = 0;
                $row['BAR'.$valuebar['bar_reservation_id']]['TotalDiscount'] = 0;
                $row['BAR'.$valuebar['bar_reservation_id']]['TotalTax'] = 0;
                
                $row['BAR'.$valuebar['bar_reservation_id']]['TransTypeCode'] = 'HHA';
                $row['BAR'.$valuebar['bar_reservation_id']]['Code'] = 'BAR_HHA'.$valuebar['bar_reservation_id'];
                
                $create_date = explode('/',$in_date);
                $row['BAR'.$valuebar['bar_reservation_id']]['TransDate'] = $create_date[2].'-'.$create_date[1].'-'.$create_date[0];
                
                $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'] = array();
                $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails']['length'] = 0;
                
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'] = array();
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills']['length'] = 0;
                
                /** lay cac khoan thanh toan trong chung tu day sang CNS **/
                $payment = DB::fetch_all("
                                        SELECT * FROM payment WHERE bill_id='".$valuebar['bar_reservation_id']."' AND type='BAR' AND sync_cns=0
                                        ");
                foreach($payment as $keypay=>$valuepay)
                {
                    if($valuepay['payment_type_id']=='CREDIT_CARD' OR $valuepay['payment_type_id']=='BANK' OR $valuepay['payment_type_id']=='CASH' OR $valuepay['payment_type_id']=='REFUND' OR $valuepay['payment_type_id']=='FOC')
                    {
                        $row_pay = array();
                        $row_pay['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                        $row_pay['CreatedOn'] = date('Y-m-d');
                        $row_pay['ChangedOn'] = date('Y-m-d');
                        $row_pay['Code'] = 'BAR'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        $row_pay['TransDate'] = date('Y-m-d',$valuepay['time']);
                        $row_pay['Description'] = $valuepay['description'];
                        
                            $row_pay['TotalAmount'] = $valuepay['amount']*$valuepay['exchange_rate'];
                        if($valuepay['currency_id']!='VND')
                            $row_pay['TotalForeignAmount'] = $valuepay['amount'];
                        $row_pay['TotalTax'] = 0;
                        $row_pay['CustomerName'] = '';
                        $row_pay['CustomerAddress'] = '';
                        $row_pay['CustomerTaxCode'] = '';
                        $row_pay['CurrencyCode'] = $valuepay['currency_id'];
                        $row_pay['CurrencyRate'] = $valuepay['exchange_rate'];
                        $row_pay['IsReceivables'] = '';
                        $row_pay['AccTransTypeBIT'] = '';
                        $row_pay['ReferenceKey'] = 'BAR'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        
                        $row_pay['AccountingTransactionDetails'] = array();
                        $row_pay['AccountingTransactionDetails']['length'] = 1;
                        $row_pay['AccountingTransactionDetails'][0]['ParentReferenceKey'] = 'BAR'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        $row_pay['AccountingTransactionDetails'][0]['CreatedOn'] = date('Y-m-d');
                        $row_pay['AccountingTransactionDetails'][0]['ChangedOn'] = date('Y-m-d');
                        $row_pay['AccountingTransactionDetails'][0]['DebitAccCode'] = 112;
                        $row_pay['AccountingTransactionDetails'][0]['CreditAccCode'] = 511;
                        $row_pay['AccountingTransactionDetails'][0]['Description'] = $valuepay['description'];
                        
                            $row_pay['AccountingTransactionDetails'][0]['Amount'] = $valuepay['amount']*$valuepay['exchange_rate'];
                        if($valuepay['currency_id']!='VND') 
                            $row_pay['AccountingTransactionDetails'][0]['ForeignAmount'] = $valuepay['amount'];
                        $row_pay['AccountingTransactionDetails'][0]['IsReceivables'] = '';
                        $row_pay['AccountingTransactionDetails'][0]['ReferenceKey'] = 'BAR'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        
                        if($valuepay['payment_type_id']=='CREDIT_CARD' OR $valuepay['payment_type_id']=='BANK')
                        {
                            $row_pay['TransTypeCode'] = 'CNT';
                            $row_pay['ContactCode'] = $row['BAR'.$valuebar['bar_reservation_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = $row['BAR'.$valuebar['bar_reservation_id']]['ContactCode'];
                        }
                        elseif($valuepay['payment_type_id']=='CASH')
                        {
                            $row_pay['TransTypeCode'] = 'PGT';
                            $row_pay['ContactCode'] = 'ACC_'.$valuepay['user_id'];
                            $row_pay['ContactCode2'] = $row['BAR'.$valuebar['bar_reservation_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = 'ACC_'.$valuepay['user_id'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode2'] = $row['BAR'.$valuebar['bar_reservation_id']]['ContactCode'];
                        }
                        elseif($valuepay['payment_type_id']=='REFUND')
                        {
                            $row_pay['TransTypeCode'] = 'PCTLT';
                            $row_pay['ContactCode'] = $row['BAR'.$valuebar['bar_reservation_id']]['ContactCode'];
                            $row_pay['ContactCode2'] = 'ACC_'.$valuepay['user_id'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = $row['BAR'.$valuebar['bar_reservation_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode2'] = 'ACC_'.$valuepay['user_id'];
                        }
                        elseif($valuepay['payment_type_id']=='FOC')
                        {
                            $row_pay['TransTypeCode'] = 'GGCK';
                            $row_pay['ContactCode'] = $row['BAR'.$valuebar['bar_reservation_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = $row['BAR'.$valuebar['bar_reservation_id']]['ContactCode'];
                        }
                        $r = new HttpRequest(LINK_SYNC_CNS.'/api/AccountingTransactions',HttpRequest::METH_POST);
                        $r->addPostFields($row_pay);
                        try 
                        {
                            $r->send();
                            if($r->getResponseCode()==200)
                            {
                                DB::query("update payment set sync_cns=1 where id=".$valuepay['id']);
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
            }
            $stt = $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails']['length'];
            
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['ParentReferenceKey'] = $row['BAR'.$valuebar['bar_reservation_id']]['ReferenceKey'];
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['WarehouseCode'] = $valuebar['warehouse_code'];
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['WarehouseCode2'] = '';
            
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['ContactCode'] = $row['BAR'.$valuebar['bar_reservation_id']]['ContactCode'];
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['ContactCode2'] = $row['BAR'.$valuebar['bar_reservation_id']]['ContactCode2'];
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['ItemCode'] = $valuebar['product_id'];
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = $valuebar['product_id'];
            
            if($valuebar['product_type']=='GOODS')
                $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = 'HCB';
            if($valuebar['product_type']=='DRINK')
                $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = 'DU';
            if($valuebar['product_type']=='PRODUCT')
                $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = 'DA';
            if($valuebar['product_type']=='SERVICE')
                $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = 'DVNH';
            
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['FeeItemCode'] = $valuebar['product_id'];
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['UnitCode'] = $valuebar['unit_name'];
            
            $quantity = $valuebar['quantity'] - $valuebar['quantity_discount'];
            $discount_rate = $valuebar['discount_rate'] + $valuebar['discount_percent'];
            $price = $valuebar['price'];
            if($valuebar['full_rate']==1)
            {
                $price = $valuebar['price'] / ((1+($valuebar['service_rate']/100))*(1+($valuebar['tax_rate']/100)));
            }
            elseif($valuebar['full_rate']!=1 AND $valuebar['full_charge']==1)
            {
                $price = $valuebar['price'] / (1+($valuebar['service_rate']/100));
            }
            
            $price = $price -(($discount_rate*$price)/100);
            $price = $price + ($price*($valuebar['service_rate']/100));
            
            
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['Quantity'] = $quantity;
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['UnitCost'] = 0;
            
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['UnitPrice'] = $price;
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['Amount'] = $price*$quantity;
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['Discount'] = 0;
            
                $row['BAR'.$valuebar['bar_reservation_id']]['TotalAmount'] += $price*$quantity;
                $row['BAR'.$valuebar['bar_reservation_id']]['TotalQuantity'] += $quantity;
                $row['BAR'.$valuebar['bar_reservation_id']]['TotalTax'] += ($price*$quantity) * ($valuebar['tax_rate']/100);
                
                
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['LotNumber'] = 'RESTAURANT_'.$valuebar['id'];
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['SeriNumber'] = 'RESTAURANT_'.$valuebar['id'];
            
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['DebitAccCode'] = 0;
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['DebitAccCode2'] = 0;
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['CreditAccCode'] = 0;
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['CreditAccCode2'] = 0;
            
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['ReferenceKey'] = 'RESTAURANT_HHA_'.$valuebar['id'];
            $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails']['length']++;
            
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills']['length']++;
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['WarehouseReferenceKey'] = 'BAR_HHA'.$valuebar['bar_reservation_id'];
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CaseItemCode'] = $row['BAR'.$valuebar['bar_reservation_id']]['WarehouseTransactionDetails'][$stt]['CaseItemCode'];
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['BillNumber'] = 'BILL_BAR_HHA'.$valuebar['bar_reservation_id'];
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['BillDate'] = $row['BAR'.$valuebar['bar_reservation_id']]['TransDate'];
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['ContactCode'] = $row['BAR'.$valuebar['bar_reservation_id']]['ContactCode'];
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['ContactCode2'] = $row['BAR'.$valuebar['bar_reservation_id']]['ContactCode2'];
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CustomerName'] = $valuebar['customer_name'];
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CustomerAddress'] = $valuebar['customer_address'];
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CustomerTaxCode'] = $valuebar['customer_tax_code'];
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['Content'] = 'HHA BAR RESERVATION ID '.$valuebar['bar_reservation_id'];
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['TotalQuantity'] = $quantity;
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['TotalForeignAmount'] = 0;
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['TotalAmount'] = $price*$quantity;
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['TaxRate'] = $valuebar['tax_rate'];
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['TaxAmount'] = ($price*$quantity) * ($valuebar['tax_rate']/100);;
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['Description'] = 'HHA BAR RESERVATION ID '.$valuebar['bar_reservation_id'];
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['DebitAccCode'] = 0;
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CreditAccCode'] = 0;
            $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_BAR_HHA'.$valuebar['id'];
            
        }
        
        return $row;
    }
    function get_spa()
    {
        $row = array();
        $massage = DB::fetch_all("
                                SELECT
                                    massage_product_consumed.id
                                    ,massage_product_consumed.quantity
                                    ,massage_product_consumed.price
                                    ,massage_product_consumed.time
                                    ,massage_reservation_room.id as massage_reservation_room_id
                                    ,NVL(massage_reservation_room.discount,0) as discount
                                    ,massage_reservation_room.service_rate
                                    ,massage_reservation_room.tax as tax_rate
                                    ,massage_reservation_room.net_price
                                    ,massage_reservation_room.hotel_reservation_room_id
                                    ,massage_reservation_room.package_id
                                    ,'' as customer_id
                                    ,massage_guest.code as massage_guest_code
                                    ,product.id as product_id
                                    ,unit.name_1 as unit_name
                                    ,payment.time as payment_time
                                    ,payment.user_id as user_id
                                    ,warehouse.code as warehouse_code
                                FROM
                                    massage_product_consumed
                                    inner join massage_reservation_room on massage_reservation_room.id=massage_product_consumed.reservation_room_id
                                    
                                    left join massage_guest on massage_reservation_room.guest_id=massage_guest.id
                                    inner join payment on payment.bill_id=massage_reservation_room.id AND payment.type='SPA'
                                    inner join product on product.id=massage_product_consumed.product_id
                                    inner join product_price_list on product.id=product_price_list.product_id
                                    inner join department on department.code = product_price_list.department_code
                                    inner join portal_department on portal_department.department_code = department.code AND portal_department.portal_id='".PORTAL_ID."'
                                    inner join warehouse on warehouse.id=portal_department.warehouse_id
                                    left join unit on unit.id=product.unit_id
                                WHERE
                                    massage_reservation_room.hotel_reservation_room_id is null
                                    AND massage_reservation_room.package_id is null
                                    AND massage_product_consumed.status = 'CHECKOUT'
                                    AND massage_reservation_room.sync_cns_hh = 0
                                    AND massage_reservation_room.portal_id='".PORTAL_ID."'
                                    AND (product.type = 'GOODS' OR product.type = 'PRODUCT' OR product.type = 'DRINK')
                                    AND payment.time >= ".Date_Time::to_time(DATE_SYNC_CNS)."
                                ");
        foreach($massage as $keymassage=>$valuemassage)
        {
            $in_date = date('d/m/Y',$valuemassage['payment_time']);
            if(!isset($row['SPA'.$valuemassage['massage_reservation_room_id']]))
            {
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['id'] = $valuemassage['massage_reservation_room_id'];
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['ReferenceKey'] = 'SPA_HHA'.$valuemassage['massage_reservation_room_id'];
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['CustomerName'] = '';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['CreatedOn'] = date('Y-m-d');
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['ChangedOn'] = date('Y-m-d');
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['CustomerAddress'] = '';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['CustomerTaxCode'] = '';
                
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['CurrencyCode'] = 'VND';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['CurrencyRate'] = 0;
                
                if($valuemassage['customer_id']!='')
                    $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode'] = 'CUS_'.$valuemassage['customer_id'];
                elseif($valuemassage['massage_guest_code']!='')
                    $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode'] = 'SPA_'.$valuemassage['massage_guest_code'];
                else
                    $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode'] = 'KHACHLE_HOTEL';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode2'] = '';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['payment'] = 1;
                
                
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseCode'] = $valuemassage['warehouse_code'];
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseCode2'] = '';
                
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['Description'] = 'SPA_HHA';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['TotalAmount'] = 0;
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['TotalQuantity'] = 0;
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['TotalCost'] = 0;
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['TotalDiscount'] = 0;
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['TotalTax'] = 0;
                
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['TransTypeCode'] = 'HHA';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['Code'] = 'SPA_HHA'.$valuemassage['massage_reservation_room_id'];
                
                $create_date = explode('/',$in_date);
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['TransDate'] = $create_date[2].'-'.$create_date[1].'-'.$create_date[0];
                
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'] = array();
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails']['length'] = 0;
                
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'] = array();
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills']['length'] = 0;
                
                /** lay cac khoan thanh toan trong chung tu day sang CNS **/
                $payment = DB::fetch_all("
                                        SELECT * FROM payment WHERE bill_id='".$valuemassage['massage_reservation_room_id']."' AND type='SPA' AND sync_cns=0
                                        ");
                foreach($payment as $keypay=>$valuepay)
                {
                    if($valuepay['payment_type_id']=='CREDIT_CARD' OR $valuepay['payment_type_id']=='BANK' OR $valuepay['payment_type_id']=='CASH' OR $valuepay['payment_type_id']=='REFUND' OR $valuepay['payment_type_id']=='FOC')
                    {
                        $row_pay = array();
                        $row_pay['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                        $row_pay['CreatedOn'] = date('Y-m-d');
                        $row_pay['ChangedOn'] = date('Y-m-d');
                        $row_pay['Code'] = 'SPA'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        $row_pay['TransDate'] = date('Y-m-d',$valuepay['time']);
                        $row_pay['Description'] = $valuepay['description'];
                        
                            $row_pay['TotalAmount'] = $valuepay['amount']*$valuepay['exchange_rate'];
                        if($valuepay['currency_id']!='VND')
                            $row_pay['TotalForeignAmount'] = $valuepay['amount'];
                        $row_pay['TotalTax'] = 0;
                        $row_pay['CustomerName'] = '';
                        $row_pay['CustomerAddress'] = '';
                        $row_pay['CustomerTaxCode'] = '';
                        $row_pay['CurrencyCode'] = $valuepay['currency_id'];
                        $row_pay['CurrencyRate'] = $valuepay['exchange_rate'];
                        $row_pay['IsReceivables'] = '';
                        $row_pay['AccTransTypeBIT'] = '';
                        $row_pay['ReferenceKey'] = 'SPA'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        
                        $row_pay['AccountingTransactionDetails'] = array();
                        $row_pay['AccountingTransactionDetails']['length'] = 1;
                        $row_pay['AccountingTransactionDetails'][0]['ParentReferenceKey'] = 'SPA'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        $row_pay['AccountingTransactionDetails'][0]['CreatedOn'] = date('Y-m-d');
                        $row_pay['AccountingTransactionDetails'][0]['ChangedOn'] = date('Y-m-d');
                        $row_pay['AccountingTransactionDetails'][0]['DebitAccCode'] = 112;
                        $row_pay['AccountingTransactionDetails'][0]['CreditAccCode'] = 511;
                        $row_pay['AccountingTransactionDetails'][0]['Description'] = $valuepay['description'];
                        
                            $row_pay['AccountingTransactionDetails'][0]['Amount'] = $valuepay['amount']*$valuepay['exchange_rate'];
                        if($valuepay['currency_id']!='VND') 
                            $row_pay['AccountingTransactionDetails'][0]['ForeignAmount'] = $valuepay['amount'];
                        $row_pay['AccountingTransactionDetails'][0]['IsReceivables'] = '';
                        $row_pay['AccountingTransactionDetails'][0]['ReferenceKey'] = 'SPA'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        
                        if($valuepay['payment_type_id']=='CREDIT_CARD' OR $valuepay['payment_type_id']=='BANK')
                        {
                            $row_pay['TransTypeCode'] = 'CNT';
                            $row_pay['ContactCode'] = $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode'];
                        }
                        elseif($valuepay['payment_type_id']=='CASH')
                        {
                            $row_pay['TransTypeCode'] = 'PGT';
                            $row_pay['ContactCode'] = 'ACC_'.$valuepay['user_id'];
                            $row_pay['ContactCode2'] = $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = 'ACC_'.$valuepay['user_id'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode2'] = $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode'];
                        }
                        elseif($valuepay['payment_type_id']=='REFUND')
                        {
                            $row_pay['TransTypeCode'] = 'PCTLT';
                            $row_pay['ContactCode'] = $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode'];
                            $row_pay['ContactCode2'] = 'ACC_'.$valuepay['user_id'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode2'] = 'ACC_'.$valuepay['user_id'];
                        }
                        elseif($valuepay['payment_type_id']=='FOC')
                        {
                            $row_pay['TransTypeCode'] = 'GGCK';
                            $row_pay['ContactCode'] = $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode'];
                        }
                        $r = new HttpRequest(LINK_SYNC_CNS.'/api/AccountingTransactions',HttpRequest::METH_POST);
                        $r->addPostFields($row_pay);
                        try 
                        {
                            $r->send();
                            if($r->getResponseCode()==200)
                            {
                                DB::query("update payment set sync_cns=1 where id=".$valuepay['id']);
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
                
            }
            
            $stt = $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails']['length'];
            
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['ParentReferenceKey'] = $row['SPA'.$valuemassage['massage_reservation_room_id']]['ReferenceKey'];
            
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
            
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['WarehouseCode'] = $valuemassage['warehouse_code'];
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['WarehouseCode2'] = 0;
            
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['ContactCode'] = $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode'];
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['ContactCode2'] = $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode2'];
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['ItemCode'] = $valuemassage['product_id'];
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = 'SPA';
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['FeeItemCode'] = $valuemassage['product_id'];
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['UnitCode'] = $valuemassage['unit_name'];
            
            $quantity = $valuemassage['quantity'];
            $discount_rate = $valuemassage['discount'];
            $price = $valuemassage['price'];
            if($valuemassage['net_price']==1)
            {
                $price = $valuemassage['price'] / ((1+($valuemassage['service_rate']/100))*(1+($valuemassage['tax_rate']/100)));
            }
            
            $price = $price -(($discount_rate*$price)/100);
            $price = $price + ($price*($valuemassage['service_rate']/100));
            
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['Quantity'] = $quantity;
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['UnitCost'] = 0;
            
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['UnitPrice'] = $price;
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['Amount'] = $price*$quantity;
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['Discount'] = 0;
            
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['TotalAmount'] += $price*$quantity;
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['TotalQuantity'] += $quantity;
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['TotalTax'] += ($price*$quantity) * ($valuemassage['tax_rate']/100);
                
                
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['LotNumber'] = 'MASSAGE_'.$valuemassage['id'];
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['SeriNumber'] = 'MASSAGE_'.$valuemassage['id'];
            
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['DebitAccCode'] = 0;
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['DebitAccCode2'] = 0;
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['CreditAccCode'] = 0;
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['CreditAccCode2'] = 0;
            
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails'][$stt]['ReferenceKey'] = 'MASSAGE_HHA_'.$valuemassage['id'];
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['WarehouseTransactionDetails']['length']++;
            
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills']['length']++;
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['WarehouseReferenceKey'] = 'SPA_HHA'.$valuemassage['massage_reservation_room_id'];
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['CaseItemCode'] = 'SPA';
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['BillNumber'] = 'BILL_SPA_HHA'.$valuemassage['massage_reservation_room_id'];
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['BillDate'] = $row['SPA'.$valuemassage['massage_reservation_room_id']]['TransDate'];
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['ContactCode'] = $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode'];
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['ContactCode2'] = $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode2'];
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['CustomerName'] = '';
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['CustomerAddress'] = '';
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['CustomerTaxCode'] = '';
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['Content'] = 'HHA MASSAGE RESERVATION ID '.$valuemassage['massage_reservation_room_id'];
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['TotalQuantity'] = $quantity;
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['TotalForeignAmount'] = 0;
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['TotalAmount'] = $price*$quantity;
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['TaxRate'] = $valuemassage['tax_rate'];
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['TaxAmount'] = ($price*$quantity) * ($valuemassage['tax_rate']/100);
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['Description'] = 'HHA MASSAGE RESERVATION ID '.$valuemassage['massage_reservation_room_id'];
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['DebitAccCode'] = 0;
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['CreditAccCode'] = 0;
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_SPA_HHA'.$valuemassage['id'];
            
        }
        return $row;
    }
    function get_folio()
    {
        /**
         * lay tat ca hoa don folio cua nhom va phong da checkout theo tung PORTAL
         * khoi tao mang main ban dau
         * lay cac chi tiet folio co type = minibar, package, bar, massage, karaoke
         * gan cac so hoa don vao mot mang
         * di kem voi mang la dieu kien lay hoa don
         * do package lai bao gom cac hoa don con cua SPA v? BAR
         * nen sau khi lay du lieu folio
         * phai lay them du lieu tu package
         * */
         
         $traveller_folio_list = DB::fetch_all("
                                            SELECT
                                                traveller_folio.id,
                                                folio.id as folio_id,
                                                reservation_room.status
                                            FROM
                                                traveller_folio
                                                INNER JOIN folio ON folio.id=traveller_folio.folio_id
                                                INNER JOIN reservation_room ON reservation_room.id=traveller_folio.reservation_room_id
                                                INNER JOIN reservation on folio.reservation_id=reservation.id
                                            WHERE
                                                folio.sync_cns_hh = 0
                                                and reservation.portal_id='".PORTAL_ID."'
                                            ");
        $cond_folio = '';
        $folio_active = array();
        foreach($traveller_folio_list as $f_key=>$f_value)
        {
            if(!isset($folio_active[$f_value['folio_id']]))
            {
                $folio_active[$f_value['folio_id']]['id'] = $f_value['folio_id'];
                $folio_active[$f_value['folio_id']]['status'] = $f_value['status'];
            }
            
            if($f_value['status']!='CHECKOUT' AND $f_value['status']!='CANCEL')
            {
                $folio_active[$f_value['folio_id']]['status'] = 'CHECKIN';
            }
        }
        if(sizeof($folio_active)>0)
        {
            foreach($folio_active as $fa_key=>$fa_value)
            {
                if($fa_value['status']!='CHECKIN')
                {
                    if($cond_folio=='')
                        $cond_folio = 'folio.id='.$fa_value['id'];
                    else
                        $cond_folio .= ' OR folio.id='.$fa_value['id'];
                }
            }
        }
        
        if($cond_folio=='')
        {
            $cond_folio = 'folio.id=0';
        }
        $record  = DB::fetch_all("
                                    SELECT
                                        traveller_folio.id
                                        ,traveller_folio.type
                                        ,traveller_folio.invoice_id
                                        ,traveller_folio.amount
                                        ,traveller_folio.percent
                                        ,traveller_folio.description
                                        ,traveller_folio.service_rate
                                        ,traveller_folio.tax_rate
                                        ,traveller_folio.total_amount
                                        ,folio.id as folio_id
                                        ,folio.create_time
                                        ,payment.time as payment_time
                                        ,payment.user_id
                                        ,folio.total
                                        ,folio.reservation_id
                                        ,folio.reservation_room_id
                                        ,customer.id as customer_id
                                        ,folio.reservation_traveller_id
                                        ,extra_service.code as extra_service_code
                                        ,extra_service.name as extra_service_name
                                        ,customer.name as customer_name
                                        ,customer.address as customer_address
                                        ,customer.tax_code as customer_tax_code
                                        ,traveller.first_name || ' ' || traveller.last_name as traveller_name
                                        ,traveller.address as traveller_address
                                        ,traveller.id as traveller_id
                                    FROM
                                        traveller_folio
                                        INNER JOIN folio ON folio.id=traveller_folio.folio_id
                                        INNER JOIN reservation on folio.reservation_id=reservation.id
                                        INNER JOIN customer on reservation.customer_id=customer.id
                                        left join payment on folio.id=payment.folio_id
                                        left join reservation_traveller on folio.reservation_traveller_id=reservation_traveller.id
                                        left join traveller on traveller.id=reservation_traveller.traveller_id
                                        left join extra_service_invoice_detail on extra_service_invoice_detail.id=traveller_folio.invoice_id AND traveller_folio.type='EXTRA_SERVICE'
                                        left join extra_service on extra_service.id=extra_service_invoice_detail.service_id
                                    WHERE
                                        (".$cond_folio.")
                                        and folio.sync_cns_hh = 0
                                        and 
                                            (
                                                traveller_folio.type='MINIBAR' 
                                                OR 
                                                traveller_folio.type='PACKAGE'
                                                OR
                                                traveller_folio.type='BAR'
                                                OR
                                                traveller_folio.type='VE'
                                                OR
                                                traveller_folio.type='KARAOKE'
                                                OR
                                                traveller_folio.type='MASSAGE'
                                                OR
                                                traveller_folio.type='DEPOSIT_GROUP'
                                                OR
                                                traveller_folio.type='DEPOSIT'
                                            )
                                        and reservation.portal_id='".PORTAL_ID."'
                                        AND (payment.time >= ".Date_Time::to_time(DATE_SYNC_CNS)." OR folio.create_time >= ".Date_Time::to_time(DATE_SYNC_CNS).")
                                    ORDER BY
                                        folio.id DESC
                                    ");
        //System::debug($record);
        /**
         * khoi tao cac gia tri ban dau
         * $row: la mang cuoi cung tra ve, chua cac mang con de day qua API
         * $List: de chua mang co folio, pecent, ma hoa don
         * $Cond: chua dieu kien de lay du lieu o cac hoa don
         * */
        $row = array();
        $ListMinibar = array();
        $CondMinibar = '';
        $ListPackage = array();
        $CondPackage = '';
        $ListBar = array();
        $CondBar = '';
        $ListVe = array();
        $CondVe = '';
        $ListKaraoke = array();
        $CondKaraoke = '';
        $ListMassage = array();
        $CondMassage = '';
        foreach($record as $key=>$value)
        {
            if($value['payment_time']=='')
                $in_date = date('d/m/Y',$value['create_time']);
            else
                $in_date = date('d/m/Y',$value['payment_time']);
            
            if(!isset($row[$value['folio_id']]))
            {
                /**
                 * Khoi tao mang MAIN
                 * ma chung tu HHA
                 * */
                $row[$value['folio_id']]['id'] = $value['folio_id'];
                $row[$value['folio_id']]['ReferenceKey'] = 'HHA'.$value['folio_id'];
                $row[$value['folio_id']]['CreatedOn'] = date('Y-m-d');
                $row[$value['folio_id']]['ChangedOn'] = date('Y-m-d');
                $row[$value['folio_id']]['CustomerName'] = $value['customer_name']==''?$value['traveller_name']:$value['customer_name'];
                $row[$value['folio_id']]['CustomerAddress'] = $value['customer_name']==''?$value['traveller_address']:$value['customer_address'];
                $row[$value['folio_id']]['CustomerTaxCode'] = $value['customer_name']==''?'':$value['customer_tax_code'];
                
                $row[$value['folio_id']]['CurrencyCode'] = 'VND';
                $row[$value['folio_id']]['CurrencyRate'] = 0;
                $row[$value['folio_id']]['ContactCode'] = 'KHACHLE_HOTEL';
                if($value['customer_id']!='')
                    $row[$value['folio_id']]['ContactCode'] = 'CUS_'.$value['customer_id'];
                elseif($value['traveller_id']!='')
                    $row[$value['folio_id']]['ContactCode'] = 'TRA_'.$value['traveller_id'];
                    
                
                $row[$value['folio_id']]['ContactCode2'] = '';
                $row[$value['folio_id']]['payment'] = 1;
                
                
                $row[$value['folio_id']]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row[$value['folio_id']]['WarehouseCode'] = '';
                $row[$value['folio_id']]['WarehouseCode2'] = '';
                
                $row[$value['folio_id']]['Description'] = 'FOLIO_HHA';
                $row[$value['folio_id']]['TotalAmount'] = 0;
                $row[$value['folio_id']]['TotalQuantity'] = 0;
                $row[$value['folio_id']]['TotalCost'] = 0;
                $row[$value['folio_id']]['TotalDiscount'] = 0;
                $row[$value['folio_id']]['TotalTax'] = 0;
                
                $row[$value['folio_id']]['TransTypeCode'] = 'HHA';
                $row[$value['folio_id']]['Code'] = 'HHA'.$value['folio_id'];
                
                $create_date = explode('/',$in_date);
                $row[$value['folio_id']]['TransDate'] = $create_date[2].'-'.$create_date[1].'-'.$create_date[0];
                
                $row[$value['folio_id']]['WarehouseTransactionDetails'] = array();
                $row[$value['folio_id']]['WarehouseTransactionDetails']['length'] = 0;
                
                $row[$value['folio_id']]['FinancialBills'] = array();
                $row[$value['folio_id']]['FinancialBills']['length'] = 0;
                
                
                
                $payment = DB::fetch_all("
                                        SELECT * FROM payment WHERE folio_id=".$value['folio_id']." AND type='RESERVATION' AND sync_cns=0
                                        ");
                foreach($payment as $keypay=>$valuepay)
                {
                    if($valuepay['payment_type_id']=='CREDIT_CARD' OR $valuepay['payment_type_id']=='BANK' OR $valuepay['payment_type_id']=='CASH' OR $valuepay['payment_type_id']=='REFUND' OR $valuepay['payment_type_id']=='FOC')
                    {
                        $row_pay = array();
                        $row_pay['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                        $row_pay['CreatedOn'] = date('Y-m-d');
                        $row_pay['ChangedOn'] = date('Y-m-d');
                        $row_pay['Code'] = 'FOLIO'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        $row_pay['TransDate'] = date('Y-m-d',$valuepay['time']);
                        $row_pay['Description'] = $valuepay['description'];
                        
                            $row_pay['TotalAmount'] = $valuepay['amount']*$valuepay['exchange_rate'];
                        if($valuepay['currency_id']!='VND')  
                            $row_pay['TotalForeignAmount'] = $valuepay['amount'];
                        $row_pay['TotalTax'] = 0;
                        $row_pay['CustomerName'] = '';
                        $row_pay['CustomerAddress'] = '';
                        $row_pay['CustomerTaxCode'] = '';
                        $row_pay['CurrencyCode'] = $valuepay['currency_id'];
                        $row_pay['CurrencyRate'] = $valuepay['exchange_rate'];
                        $row_pay['IsReceivables'] = '';
                        $row_pay['AccTransTypeBIT'] = '';
                        $row_pay['ReferenceKey'] = 'FOLIO'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        
                        $row_pay['AccountingTransactionDetails'] = array();
                        $row_pay['AccountingTransactionDetails']['length'] = 1;
                        $row_pay['AccountingTransactionDetails'][0]['ParentReferenceKey'] = 'FOLIO'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        $row_pay['AccountingTransactionDetails'][0]['CreatedOn'] = date('Y-m-d');
                        $row_pay['AccountingTransactionDetails'][0]['ChangedOn'] = date('Y-m-d');
                        $row_pay['AccountingTransactionDetails'][0]['DebitAccCode'] = 112;
                        $row_pay['AccountingTransactionDetails'][0]['CreditAccCode'] = 511;
                        $row_pay['AccountingTransactionDetails'][0]['Description'] = $valuepay['description'];
                        
                            $row_pay['AccountingTransactionDetails'][0]['Amount'] = $valuepay['amount']*$valuepay['exchange_rate'];
                        if($valuepay['currency_id']!='VND')  
                            $row_pay['AccountingTransactionDetails'][0]['ForeignAmount'] = $valuepay['amount'];
                        $row_pay['AccountingTransactionDetails'][0]['IsReceivables'] = '';
                        $row_pay['AccountingTransactionDetails'][0]['ReferenceKey'] = 'FOLIO'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        
                        if($valuepay['payment_type_id']=='CREDIT_CARD' OR $valuepay['payment_type_id']=='BANK')
                        {
                            $row_pay['TransTypeCode'] = 'CNT';
                            $row_pay['ContactCode'] = $row[$value['folio_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = $row[$value['folio_id']]['ContactCode'];
                        }
                        elseif($valuepay['payment_type_id']=='CASH')
                        {
                            $row_pay['TransTypeCode'] = 'PGT';
                            $row_pay['ContactCode'] = 'ACC_'.$valuepay['user_id'];
                            $row_pay['ContactCode2'] = $row[$value['folio_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = 'ACC_'.$valuepay['user_id'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode2'] = $row[$value['folio_id']]['ContactCode'];
                        }
                        elseif($valuepay['payment_type_id']=='REFUND')
                        {
                            $row_pay['TransTypeCode'] = 'PCTLT';
                            $row_pay['ContactCode'] = $row[$value['folio_id']]['ContactCode'];
                            $row_pay['ContactCode2'] = 'ACC_'.$valuepay['user_id'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = $row[$value['folio_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode2'] = 'ACC_'.$valuepay['user_id'];
                        }
                        elseif($valuepay['payment_type_id']=='FOC')
                        {
                            $row_pay['TransTypeCode'] = 'GGCK';
                            $row_pay['ContactCode'] = $row[$value['folio_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = $row[$value['folio_id']]['ContactCode'];
                        }
                        $r = new HttpRequest(LINK_SYNC_CNS.'/api/AccountingTransactions',HttpRequest::METH_POST);
                        $r->addPostFields($row_pay);
                        try 
                        {
                            $r->send();
                            if($r->getResponseCode()==200)
                            {
                                DB::query("update payment set sync_cns=1 where id=".$valuepay['id']);
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
                
                
            }
            
            if($value['type']=='PACKAGE')
            {
                $ListPackage[$value['invoice_id']]['id'] = $value['invoice_id'];
                $ListPackage[$value['invoice_id']]['percent'] = $value['percent'];
                $ListPackage[$value['invoice_id']]['folio_id'] = $value['folio_id'];
                if($CondPackage=='')
                    $CondPackage = 'reservation_room.id='.$value['invoice_id'];
                else
                    $CondPackage .= ' OR reservation_room.id='.$value['invoice_id'];
            }
            elseif($value['type']=='MINIBAR')
            {
                $ListMinibar[$value['invoice_id']]['id'] = $value['invoice_id'];
                $ListMinibar[$value['invoice_id']]['percent'] = $value['percent'];
                $ListMinibar[$value['invoice_id']]['folio_id'] = $value['folio_id'];
                if($CondMinibar=='')
                    $CondMinibar = 'housekeeping_invoice.id='.$value['invoice_id'];
                else
                    $CondMinibar .= ' OR housekeeping_invoice.id='.$value['invoice_id'];
            }
            elseif($value['type']=='BAR')
            {
                $ListBar[$value['invoice_id']]['id'] = $value['invoice_id'];
                $ListBar[$value['invoice_id']]['percent'] = $value['percent'];
                $ListBar[$value['invoice_id']]['folio_id'] = $value['folio_id'];
                if($CondBar=='')
                    $CondBar = 'bar_reservation.id='.$value['invoice_id'];
                else
                    $CondBar .= ' OR bar_reservation.id='.$value['invoice_id'];
            }
            elseif($value['type']=='VE')
            {
                $ListVe[$value['invoice_id']]['id'] = $value['invoice_id'];
                $ListVe[$value['invoice_id']]['percent'] = $value['percent'];
                $ListVe[$value['invoice_id']]['folio_id'] = $value['folio_id'];
                if($CondVe=='')
                    $CondVe = 've_reservation.id='.$value['invoice_id'];
                else
                    $CondVe .= ' OR ve_reservation.id='.$value['invoice_id'];
            }
            elseif($value['type']=='KARAOKE')
            {
                $ListKaraoke[$value['invoice_id']]['id'] = $value['invoice_id'];
                $ListKaraoke[$value['invoice_id']]['percent'] = $value['percent'];
                $ListKaraoke[$value['invoice_id']]['folio_id'] = $value['folio_id'];
                if($CondKaraoke=='')
                    $CondKaraoke = 'karaoke_reservation.id='.$value['invoice_id'];
                else
                    $CondKaraoke .= ' OR karaoke_reservation.id='.$value['invoice_id'];
            }
            elseif($value['type']=='MASSAGE')
            {
                $ListMassage[$value['invoice_id']]['id'] = $value['invoice_id'];
                $ListMassage[$value['invoice_id']]['percent'] = $value['percent'];
                $ListMassage[$value['invoice_id']]['folio_id'] = $value['folio_id'];
                if($CondMassage=='')
                    $CondMassage = 'massage_reservation_room.id='.$value['invoice_id'];
                else
                    $CondMassage .= ' OR massage_reservation_room.id='.$value['invoice_id'];
            }
            elseif($value['type']=='DEPOSIT_GROUP' OR $value['type']=='DEPOSIT')
            {
                $payment = DB::fetch_all("
                                        SELECT * FROM payment WHERE id=".$value['invoice_id']." AND sync_cns=0
                                        ");
                foreach($payment as $keypay=>$valuepay)
                {
                    if($valuepay['payment_type_id']=='CREDIT_CARD' OR $valuepay['payment_type_id']=='BANK' OR $valuepay['payment_type_id']=='CASH' OR $valuepay['payment_type_id']=='REFUND' OR $valuepay['payment_type_id']=='FOC')
                    {
                        $row_pay = array();
                        $row_pay['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                        $row_pay['CreatedOn'] = date('Y-m-d');
                        $row_pay['ChangedOn'] = date('Y-m-d');
                        $row_pay['Code'] = 'FOLIO'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        $row_pay['TransDate'] = date('Y-m-d',$valuepay['time']);
                        $row_pay['Description'] = $valuepay['description'];
                        
                            $row_pay['TotalAmount'] = $valuepay['amount']*$valuepay['exchange_rate'];
                        if($valuepay['currency_id']!='VND') 
                            $row_pay['TotalForeignAmount'] = $valuepay['amount'];
                        $row_pay['TotalTax'] = 0;
                        $row_pay['CustomerName'] = '';
                        $row_pay['CustomerAddress'] = '';
                        $row_pay['CustomerTaxCode'] = '';
                        $row_pay['CurrencyCode'] = $valuepay['currency_id'];
                        $row_pay['CurrencyRate'] = $valuepay['exchange_rate'];
                        $row_pay['IsReceivables'] = '';
                        $row_pay['AccTransTypeBIT'] = '';
                        $row_pay['ReferenceKey'] = 'FOLIO'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        
                        $row_pay['AccountingTransactionDetails'] = array();
                        $row_pay['AccountingTransactionDetails']['length'] = 1;
                        $row_pay['AccountingTransactionDetails'][0]['ParentReferenceKey'] = 'FOLIO'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        $row_pay['AccountingTransactionDetails'][0]['CreatedOn'] = date('Y-m-d');
                        $row_pay['AccountingTransactionDetails'][0]['ChangedOn'] = date('Y-m-d');
                        $row_pay['AccountingTransactionDetails'][0]['DebitAccCode'] = 112;
                        $row_pay['AccountingTransactionDetails'][0]['CreditAccCode'] = 511;
                        $row_pay['AccountingTransactionDetails'][0]['Description'] = $valuepay['description'];
                        $row_pay['AccountingTransactionDetails'][0]['Amount'] = $valuepay['amount']*$valuepay['exchange_rate'];
                        if($valuepay['currency_id']!='VND') 
                            $row_pay['AccountingTransactionDetails'][0]['ForeignAmount'] = $valuepay['amount'];
                        $row_pay['AccountingTransactionDetails'][0]['IsReceivables'] = '';
                        $row_pay['AccountingTransactionDetails'][0]['ReferenceKey'] = 'FOLIO'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        
                        if($valuepay['payment_type_id']=='CREDIT_CARD' OR $valuepay['payment_type_id']=='BANK')
                        {
                            $row_pay['TransTypeCode'] = 'CNT';
                            $row_pay['ContactCode'] = $row[$value['folio_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = $row[$value['folio_id']]['ContactCode'];
                        }
                        elseif($valuepay['payment_type_id']=='CASH')
                        {
                            $row_pay['TransTypeCode'] = 'PGT';
                            $row_pay['ContactCode'] = 'ACC_'.$valuepay['user_id'];
                            $row_pay['ContactCode2'] = $row[$value['folio_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = 'ACC_'.$valuepay['user_id'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode2'] = $row[$value['folio_id']]['ContactCode'];
                        }
                        elseif($valuepay['payment_type_id']=='REFUND')
                        {
                            $row_pay['TransTypeCode'] = 'PCTLT';
                            $row_pay['ContactCode'] = $row[$value['folio_id']]['ContactCode'];
                            $row_pay['ContactCode2'] = 'ACC_'.$valuepay['user_id'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = $row[$value['folio_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode2'] = 'ACC_'.$valuepay['user_id'];
                        }
                        elseif($valuepay['payment_type_id']=='FOC')
                        {
                            $row_pay['TransTypeCode'] = 'GGCK';
                            $row_pay['ContactCode'] = $row[$value['folio_id']]['ContactCode'];
                            $row_pay['AccountingTransactionDetails'][0]['ContactCode'] = $row[$value['folio_id']]['ContactCode'];
                        }
                        $r = new HttpRequest(LINK_SYNC_CNS.'/api/AccountingTransactions',HttpRequest::METH_POST);
                        $r->addPostFields($row_pay);
                        try 
                        {
                            $r->send();
                            if($r->getResponseCode()==200)
                            {
                                DB::query("update payment set sync_cns=1 where id=".$valuepay['id']);
                            }
                        } 
                        catch (HttpException $ex) 
                        {
                        }
                    }
                }
            }
            
        }
        
        
        if($CondPackage!='')
        {
        /** get list package detail **/
        $package = DB::fetch_all("
                SELECT 
                    package_sale_detail.id || '_' || reservation_room.id as id
                    ,package_sale_detail.id as package_sale_detail_id
                    ,reservation_room.id as reservation_room_id
                    ,department.code
                FROM package_sale_detail
                    INNER JOIN package_sale ON package_sale.id=package_sale_detail.package_sale_id
                    INNER JOIN reservation_room ON reservation_room.package_sale_id=package_sale.id
                    INNER JOIN package_service ON package_service.id=package_sale_detail.service_id
                    INNER JOIN department ON package_service.department_id=department.id
                WHERE 
                    (".$CondPackage.") 
                    AND (department.code='RES' OR department.code='SPA')
                ");
        foreach($package as $keypackage=>$valuepackage)
        {
            if($valuepackage['code']=='RES')
            {
                if($CondBar=='')
                    $CondBar = '(bar_reservation.package_id='.$valuepackage['package_sale_detail_id'].' AND bar_reservation.reservation_room_id='.$valuepackage['reservation_room_id'].')';
                else
                    $CondBar .= ' OR (bar_reservation.package_id='.$valuepackage['package_sale_detail_id'].' AND bar_reservation.reservation_room_id='.$valuepackage['reservation_room_id'].')';
            }
            elseif($valuepackage['code']=='SPA')
            {
                if($CondMassage=='')
                    $CondMassage = '(massage_reservation_room.package_id='.$valuepackage['package_sale_detail_id'].' AND massage_reservation_room.hotel_reservation_room_id='.$valuepackage['reservation_room_id'].')';
                else
                    $CondMassage .= ' OR (massage_reservation_room.package_id='.$valuepackage['package_sale_detail_id'].' AND massage_reservation_room.hotel_reservation_room_id='.$valuepackage['reservation_room_id'].')';
            }
        }
        }
        
        if($CondMinibar!='')
        {
        /** get minibar **/
        $minibar = DB::fetch_all("
                                SELECT
                                    housekeeping_invoice_detail.id
                                    ,housekeeping_invoice_detail.price
                                    ,housekeeping_invoice_detail.quantity
                                    ,housekeeping_invoice.id as housekeeping_invoice_id
                                    ,housekeeping_invoice.net_price
                                    ,housekeeping_invoice.fee_rate as service_rate
                                    ,housekeeping_invoice.tax_rate
                                    ,product.id as product_id
                                    ,unit.name_1 as unit_name
                                    ,warehouse.code as warehouse_code
                                FROM
                                    housekeeping_invoice_detail
                                    inner join housekeeping_invoice on housekeeping_invoice.id=housekeeping_invoice_detail.invoice_id
                                    inner join product on product.id=housekeeping_invoice_detail.product_id
                                    inner join product_price_list on product.id=product_price_list.product_id
                                    inner join department on department.code = product_price_list.department_code
                                    inner join portal_department on portal_department.department_code = department.code AND portal_department.portal_id='".PORTAL_ID."'
                                    inner join warehouse on warehouse.id=portal_department.warehouse_id
                                    left join unit on unit.id=product.unit_id
                                WHERE
                                    (".$CondMinibar.") AND (product.type = 'GOODS' OR product.type = 'PRODUCT' OR product.type = 'DRINK')
                                ");
        foreach($minibar as $keyminibar=>$valueminibar)
        {
            $folio = $ListMinibar[$valueminibar['housekeeping_invoice_id']]['folio_id'];
            $percent = $ListMinibar[$valueminibar['housekeeping_invoice_id']]['percent'];
            $stt = $row[$folio]['WarehouseTransactionDetails']['length'];
            
            $row[$folio]['WarehouseTransactionDetails'][$stt]['ParentReferenceKey'] = $row[$folio]['ReferenceKey'];
            $row[$folio]['WarehouseTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
            $row[$folio]['WarehouseTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
            $row[$folio]['WarehouseTransactionDetails'][$stt]['WarehouseCode'] = $valueminibar['warehouse_code'];
            $row[$folio]['WarehouseTransactionDetails'][$stt]['WarehouseCode2'] = '';
            
            $row[$folio]['WarehouseTransactionDetails'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
            $row[$folio]['WarehouseTransactionDetails'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
            $row[$folio]['WarehouseTransactionDetails'][$stt]['ItemCode'] = $valueminibar['product_id'];
            $row[$folio]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = 'MINIBAR';
            $row[$folio]['WarehouseTransactionDetails'][$stt]['FeeItemCode'] = $valueminibar['product_id'];
            $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitCode'] = $valueminibar['unit_name'];
            
            $row[$folio]['WarehouseTransactionDetails'][$stt]['Quantity'] = $valueminibar['quantity'];
            $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitCost'] = 0;
            
            if($valueminibar['net_price']==1)
            {
                $valueminibar['price'] = $valueminibar['price'] / ((1+($valueminibar['service_rate']/100))*(1+($valueminibar['tax_rate']/100)));
            }
            
            $price = $valueminibar['price'] + ($valueminibar['price']*($valueminibar['service_rate']/100));
            $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitPrice'] = $price*($percent/100);
            $row[$folio]['WarehouseTransactionDetails'][$stt]['Amount'] = $price*($percent/100)*$valueminibar['quantity'];
            $row[$folio]['WarehouseTransactionDetails'][$stt]['Discount'] = 0;
                
                $row[$folio]['WarehouseCode'] = $valueminibar['warehouse_code'];
                
                $row[$folio]['TotalAmount'] += $price*($percent/100)*$valueminibar['quantity'];
                $row[$folio]['TotalQuantity'] += $valueminibar['quantity'];
                $row[$folio]['TotalTax'] += ($price*($percent/100)*$valueminibar['quantity']) * ($valueminibar['tax_rate']/100);
                
                
            $row[$folio]['WarehouseTransactionDetails'][$stt]['LotNumber'] = 'MINIBAR_'.$valueminibar['id'];
            $row[$folio]['WarehouseTransactionDetails'][$stt]['SeriNumber'] = 'MINIBAR_'.$valueminibar['id'];
            
            $row[$folio]['WarehouseTransactionDetails'][$stt]['DebitAccCode'] = 0;
            $row[$folio]['WarehouseTransactionDetails'][$stt]['DebitAccCode2'] = 0;
            $row[$folio]['WarehouseTransactionDetails'][$stt]['CreditAccCode'] = 0;
            $row[$folio]['WarehouseTransactionDetails'][$stt]['CreditAccCode2'] = 0;
            
            $row[$folio]['WarehouseTransactionDetails'][$stt]['ReferenceKey'] = 'FOLIO_MINIBAR_HHA_'.$valueminibar['id'];
            $row[$folio]['WarehouseTransactionDetails']['length']++;
            
            $row[$folio]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
            $row[$folio]['FinancialBills'][$stt]['WarehouseReferenceKey'] = 'HHA'.$folio;
            $row[$folio]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
            $row[$folio]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
            $row[$folio]['FinancialBills'][$stt]['CaseItemCode'] = 'MINIBAR';
            $row[$folio]['FinancialBills'][$stt]['BillNumber'] = 'BILL_FOLIO_HHA'.$folio;
            $row[$folio]['FinancialBills'][$stt]['BillDate'] = $row[$folio]['TransDate'];
            $row[$folio]['FinancialBills'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
            $row[$folio]['FinancialBills'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
            $row[$folio]['FinancialBills'][$stt]['CustomerName'] = $row[$folio]['CustomerName'];
            $row[$folio]['FinancialBills'][$stt]['CustomerAddress'] = $row[$folio]['CustomerAddress'];
            $row[$folio]['FinancialBills'][$stt]['CustomerTaxCode'] = $row[$folio]['CustomerTaxCode'];
            $row[$folio]['FinancialBills'][$stt]['Content'] = 'HHA FOLIO ID '.$folio;
            $row[$folio]['FinancialBills'][$stt]['TotalQuantity'] = $valueminibar['quantity'];
            $row[$folio]['FinancialBills'][$stt]['TotalForeignAmount'] = 0;
            $row[$folio]['FinancialBills'][$stt]['TotalAmount'] = $price*($percent/100)*$valueminibar['quantity'];
            $row[$folio]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
            $row[$folio]['FinancialBills'][$stt]['TaxRate'] = $valueminibar['tax_rate'];
            $row[$folio]['FinancialBills'][$stt]['TaxAmount'] = ($price*($percent/100)*$valueminibar['quantity']) * ($valueminibar['tax_rate']/100);
            $row[$folio]['FinancialBills'][$stt]['Description'] = 'HHA VAT MINIBAR FOLIO ID '.$folio;
            $row[$folio]['FinancialBills'][$stt]['DebitAccCode'] = 0;
            $row[$folio]['FinancialBills'][$stt]['CreditAccCode'] = 0;
            $row[$folio]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_FOLIO_HHA_MINIBAR'.$valueminibar['id'];
            $row[$folio]['WarehouseTransactionDetails']['length']++;
        }
        }
        if($CondBar!='')
        {
        /** get bar **/
            $restaurant = DB::fetch_all("
                                        SELECT
                                            bar_reservation_product.id
                                            ,bar_reservation_product.price
                                            ,bar_reservation_product.quantity
                                            ,bar_reservation_product.quantity_discount
                                            ,bar_reservation_product.discount_rate
                                            ,bar_reservation.id as bar_reservation_id
                                            ,bar_reservation.bar_fee_rate as service_rate
                                            ,bar_reservation.tax_rate
                                            ,bar_reservation.discount_percent
                                            ,bar_reservation.full_charge
                                            ,bar_reservation.full_rate
                                            ,bar_reservation.reservation_room_id
                                            ,bar_reservation.package_id
                                            ,product_category.structure_id
                                            ,product.id as product_id
                                            ,product.type as product_type
                                            ,unit.name_1 as unit_name
                                            ,warehouse.code as warehouse_code
                                        FROM
                                            bar_reservation_product
                                            inner join bar_reservation on bar_reservation.id=bar_reservation_product.bar_reservation_id
                                            inner join product on product.id=bar_reservation_product.product_id
                                            inner join product_category on product_category.id=product.category_id
                                            inner join product_price_list on bar_reservation_product.price_id=product_price_list.id
                                            inner join department on department.code = product_price_list.department_code
                                            inner join portal_department on portal_department.department_code = department.code AND portal_department.portal_id='".PORTAL_ID."'
                                            inner join warehouse on warehouse.id=portal_department.warehouse_id
                                            left join unit on unit.id=product.unit_id
                                        WHERE
                                            (".$CondBar.") AND (product.type = 'GOODS' OR product.type = 'PRODUCT' OR product.type = 'DRINK')
                                        ");
            //System::debug($restaurant);
            $check_conflict_bar_reservation = array();
            foreach($restaurant as $keybar=>$valuebar)
            {
                if($valuebar['package_id']!='')
                {
                    $folio = $ListPackage[$valuebar['reservation_room_id']]['folio_id'];
                    $percent = $ListPackage[$valuebar['reservation_room_id']]['percent'];
                }
                else
                {
                    $folio = $ListBar[$valuebar['bar_reservation_id']]['folio_id'];
                    $percent = $ListBar[$valuebar['bar_reservation_id']]['percent'];
                }
                $stt = $row[$folio]['WarehouseTransactionDetails']['length'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ParentReferenceKey'] = $row[$folio]['ReferenceKey'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['WarehouseCode'] = $valuebar['warehouse_code'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['WarehouseCode2'] = '';
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ItemCode'] = $valuebar['product_id'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = $valuebar['product_id'];
                
                if($valuebar['product_type']=='GOODS')
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = 'HCB';
                if($valuebar['product_type']=='DRINK')
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = 'DU';
                if($valuebar['product_type']=='PRODUCT')
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = 'DA';
                if($valuebar['product_type']=='SERVICE')
                    $row[$folio]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = 'DVNH';
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['FeeItemCode'] = $valuebar['product_id'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitCode'] = $valuebar['unit_name'];
                
                $quantity = $valuebar['quantity'] - $valuebar['quantity_discount'];
                $discount_rate = $valuebar['discount_rate'] + $valuebar['discount_percent'];
                $price = $valuebar['price'];
                if($valuebar['full_rate']==1)
                {
                    $price = $valuebar['price'] / ((1+($valuebar['service_rate']/100))*(1+($valuebar['tax_rate']/100)));
                }
                elseif($valuebar['full_rate']!=1 AND $valuebar['full_charge']==1)
                {
                    $price = $valuebar['price'] / (1+($valuebar['service_rate']/100));
                }
                
                $price = $price -(($discount_rate*$price)/100);
                $price = $price + ($price*($valuebar['service_rate']/100));
                
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['Quantity'] = $quantity;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitCost'] = 0;
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitPrice'] = $price*($percent/100);
                $row[$folio]['WarehouseTransactionDetails'][$stt]['Amount'] = $price*($percent/100)*$quantity;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['Discount'] = 0;
                
                    $row[$folio]['WarehouseCode'] = $valuebar['warehouse_code'];
                    
                    $row[$folio]['TotalAmount'] += $price*($percent/100)*$quantity;
                    $row[$folio]['TotalQuantity'] += $quantity;
                    $row[$folio]['TotalTax'] += ($price*($percent/100)*$quantity) * ($valuebar['tax_rate']/100);
                    
                    
                $row[$folio]['WarehouseTransactionDetails'][$stt]['LotNumber'] = 'RESTAURANT_'.$valuebar['id'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['SeriNumber'] = 'RESTAURANT_'.$valuebar['id'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['DebitAccCode'] = 0;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['DebitAccCode2'] = 0;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CreditAccCode'] = 0;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CreditAccCode2'] = 0;
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ReferenceKey'] = 'FOLIO_RESTAURANT_HHA_'.$valuebar['id'];
                $row[$folio]['WarehouseTransactionDetails']['length']++;
                
                $row[$folio]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row[$folio]['FinancialBills'][$stt]['WarehouseReferenceKey'] = 'HHA'.$folio;
                $row[$folio]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
                $row[$folio]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
                $row[$folio]['FinancialBills'][$stt]['CaseItemCode'] = $row[$folio]['WarehouseTransactionDetails'][$stt]['CaseItemCode'];
                $row[$folio]['FinancialBills'][$stt]['BillNumber'] = 'BILL_FOLIO_HHA'.$folio;
                $row[$folio]['FinancialBills'][$stt]['BillDate'] = $row[$folio]['TransDate'];
                $row[$folio]['FinancialBills'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                $row[$folio]['FinancialBills'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                $row[$folio]['FinancialBills'][$stt]['CustomerName'] = $row[$folio]['CustomerName'];
                $row[$folio]['FinancialBills'][$stt]['CustomerAddress'] = $row[$folio]['CustomerAddress'];
                $row[$folio]['FinancialBills'][$stt]['CustomerTaxCode'] = $row[$folio]['CustomerTaxCode'];
                $row[$folio]['FinancialBills'][$stt]['Content'] = 'HHA FOLIO ID '.$folio;
                $row[$folio]['FinancialBills'][$stt]['TotalQuantity'] = $quantity;
                $row[$folio]['FinancialBills'][$stt]['TotalForeignAmount'] = 0;
                $row[$folio]['FinancialBills'][$stt]['TotalAmount'] = $price*($percent/100)*$quantity;
                $row[$folio]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
                $row[$folio]['FinancialBills'][$stt]['TaxRate'] = $valuebar['tax_rate'];
                $row[$folio]['FinancialBills'][$stt]['TaxAmount'] = ($price*($percent/100)*$quantity) * ($valuebar['tax_rate']/100);
                $row[$folio]['FinancialBills'][$stt]['Description'] = 'HHA VAT BAR FOLIO ID '.$folio;
                $row[$folio]['FinancialBills'][$stt]['DebitAccCode'] = 0;
                $row[$folio]['FinancialBills'][$stt]['CreditAccCode'] = 0;
                $row[$folio]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_FOLIO_HHA_BAR'.$valuebar['id'];
                $row[$folio]['WarehouseTransactionDetails']['length']++;
                
            }
        }
        
        if($CondVe!='')
        {
        /** get bar **/
            $vending = DB::fetch_all("
                                        SELECT
                                            ve_reservation_product.id
                                            ,ve_reservation_product.price
                                            ,ve_reservation_product.quantity
                                            ,ve_reservation_product.quantity_discount
                                            ,ve_reservation_product.discount_rate
                                            ,ve_reservation.id as bar_reservation_id
                                            ,ve_reservation.bar_fee_rate as service_rate
                                            ,ve_reservation.tax_rate
                                            ,ve_reservation.discount_percent
                                            ,ve_reservation.full_charge
                                            ,ve_reservation.full_rate
                                            ,ve_reservation.reservation_room_id
                                            ,ve_reservation.department_code
                                            ,product.id as product_id
                                            ,unit.name_1 as unit_name
                                            ,warehouse.code as warehouse_code
                                        FROM
                                            ve_reservation_product
                                            inner join ve_reservation on ve_reservation.id=ve_reservation_product.bar_reservation_id
                                            inner join product on product.id=ve_reservation_product.product_id
                                            inner join department on department.code = ve_reservation.department_code
                                            inner join portal_department on portal_department.department_code = department.code AND portal_department.portal_id='".PORTAL_ID."'
                                            inner join warehouse on warehouse.id=portal_department.warehouse_id
                                            left join unit on unit.id=product.unit_id
                                        WHERE
                                            (".$CondVe.") AND (product.type = 'GOODS' OR product.type = 'PRODUCT' OR product.type = 'DRINK')
                                        ");
            //System::debug($vending);
            foreach($vending as $keybar=>$valuebar)
            {
                $folio = $ListVe[$valuebar['bar_reservation_id']]['folio_id'];
                $percent = $ListVe[$valuebar['bar_reservation_id']]['percent'];
                $stt = $row[$folio]['WarehouseTransactionDetails']['length'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ParentReferenceKey'] = $row[$folio]['ReferenceKey'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['WarehouseCode'] = $valuebar['warehouse_code'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['WarehouseCode2'] = '';
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ItemCode'] = $valuebar['product_id'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = 'VEND_'.$valuebar['department_code'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['FeeItemCode'] = $valuebar['product_id'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitCode'] = $valuebar['unit_name'];
                
                $quantity = $valuebar['quantity'] - $valuebar['quantity_discount'];
                $discount_rate = $valuebar['discount_rate'] + $valuebar['discount_percent'];
                $price = $valuebar['price'];
                if($valuebar['full_rate']==1)
                {
                    $price = $valuebar['price'] / ((1+($valuebar['service_rate']/100))*(1+($valuebar['tax_rate']/100)));
                }
                elseif($valuebar['full_rate']!=1 AND $valuebar['full_charge']==1)
                {
                    $price = $valuebar['price'] / (1+($valuebar['service_rate']/100));
                }
                
                $price = $price -(($discount_rate*$price)/100);
                $price = $price + ($price*($valuebar['service_rate']/100));
                
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['Quantity'] = $quantity;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitCost'] = 0;
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitPrice'] = $price*($percent/100);
                $row[$folio]['WarehouseTransactionDetails'][$stt]['Amount'] = $price*($percent/100)*$quantity;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['Discount'] = 0;
                
                    $row[$folio]['WarehouseCode'] = $valuebar['warehouse_code'];
                    
                    $row[$folio]['TotalAmount'] += $price*($percent/100)*$quantity;
                    $row[$folio]['TotalQuantity'] += $quantity;
                    $row[$folio]['TotalTax'] += ($price*($percent/100)*$quantity) * ($valuebar['tax_rate']/100);
                    
                $row[$folio]['WarehouseTransactionDetails'][$stt]['LotNumber'] = 'VEND_'.$valuebar['id'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['SeriNumber'] = 'VEND_'.$valuebar['id'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['DebitAccCode'] = 0;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['DebitAccCode2'] = 0;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CreditAccCode'] = 0;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CreditAccCode2'] = 0;
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ReferenceKey'] = 'FOLIO_VEND_HHA_'.$valuebar['id'];
                $row[$folio]['WarehouseTransactionDetails']['length']++;
                
                $row[$folio]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row[$folio]['FinancialBills'][$stt]['WarehouseReferenceKey'] = 'HHA'.$folio;
                $row[$folio]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
                $row[$folio]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
                $row[$folio]['FinancialBills'][$stt]['CaseItemCode'] = 'VEND_'.$valuebar['department_code'];
                $row[$folio]['FinancialBills'][$stt]['BillNumber'] = 'BILL_FOLIO_HHA'.$folio;
                $row[$folio]['FinancialBills'][$stt]['BillDate'] = $row[$folio]['TransDate'];
                $row[$folio]['FinancialBills'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                $row[$folio]['FinancialBills'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                $row[$folio]['FinancialBills'][$stt]['CustomerName'] = $row[$folio]['CustomerName'];
                $row[$folio]['FinancialBills'][$stt]['CustomerAddress'] = $row[$folio]['CustomerAddress'];
                $row[$folio]['FinancialBills'][$stt]['CustomerTaxCode'] = $row[$folio]['CustomerTaxCode'];
                $row[$folio]['FinancialBills'][$stt]['Content'] = 'HHA FOLIO ID '.$folio;
                $row[$folio]['FinancialBills'][$stt]['TotalQuantity'] = $quantity;
                $row[$folio]['FinancialBills'][$stt]['TotalForeignAmount'] = 0;
                $row[$folio]['FinancialBills'][$stt]['TotalAmount'] = $price*($percent/100)*$quantity;
                $row[$folio]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
                $row[$folio]['FinancialBills'][$stt]['TaxRate'] = $valuebar['tax_rate'];
                $row[$folio]['FinancialBills'][$stt]['TaxAmount'] = ($price*($percent/100)*$quantity) * ($valuebar['tax_rate']/100);
                $row[$folio]['FinancialBills'][$stt]['Description'] = 'HHA VAT VEND FOLIO ID '.$folio;
                $row[$folio]['FinancialBills'][$stt]['DebitAccCode'] = 0;
                $row[$folio]['FinancialBills'][$stt]['CreditAccCode'] = 0;
                $row[$folio]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_FOLIO_HHA_VEND'.$valuebar['id'];
                $row[$folio]['WarehouseTransactionDetails']['length']++;
                
            }
        }
        
        /** get massage */
        if($CondMassage!='')
        {
            $massage = DB::fetch_all("
                                    SELECT
                                        massage_product_consumed.id
                                        ,massage_product_consumed.quantity
                                        ,massage_product_consumed.price
                                        ,massage_reservation_room.id as massage_reservation_room_id
                                        ,NVL(massage_reservation_room.discount,0) as discount
                                        ,massage_reservation_room.service_rate
                                        ,massage_reservation_room.tax as tax_rate
                                        ,massage_reservation_room.net_price
                                        ,massage_reservation_room.hotel_reservation_room_id
                                        ,massage_reservation_room.package_id
                                        ,product.id as product_id
                                        ,unit.name_1 as unit_name
                                        ,warehouse.code as warehouse_code
                                    FROM
                                        massage_product_consumed
                                        inner join massage_reservation_room on massage_reservation_room.id=massage_product_consumed.reservation_room_id
                                        inner join product on product.id=massage_product_consumed.product_id
                                        inner join product_price_list on product.id=product_price_list.product_id
                                        inner join department on department.code = product_price_list.department_code
                                        inner join portal_department on portal_department.department_code = department.code AND portal_department.portal_id='".PORTAL_ID."'
                                        inner join warehouse on warehouse.id=portal_department.warehouse_id
                                        left join unit on unit.id=product.unit_id
                                    WHERE
                                        (".$CondMassage.") AND (product.type = 'GOODS' OR product.type = 'PRODUCT' OR product.type = 'DRINK')
                                    ");
            //System::debug($massage);
            foreach($massage as $keymassage=>$valuemassage)
            {
                if($valuemassage['package_id']!='')
                {
                    $folio = $ListPackage[$valuemassage['hotel_reservation_room_id']]['folio_id'];
                    $percent = $ListPackage[$valuemassage['hotel_reservation_room_id']]['percent'];
                }
                else
                {
                    $folio = $ListMassage[$valuemassage['massage_reservation_room_id']]['folio_id'];
                    $percent = $ListMassage[$valuemassage['massage_reservation_room_id']]['percent'];
                }
                $stt = $row[$folio]['WarehouseTransactionDetails']['length'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ParentReferenceKey'] = $row[$folio]['ReferenceKey'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['WarehouseCode'] = $valuemassage['warehouse_code'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['WarehouseCode2'] = '';
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ItemCode'] = $valuemassage['product_id'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CaseItemCode'] = 'SPA';
                $row[$folio]['WarehouseTransactionDetails'][$stt]['FeeItemCode'] = $valuemassage['product_id'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitCode'] = $valuemassage['unit_name'];
                
                $quantity = $valuemassage['quantity'];
                $discount_rate = $valuemassage['discount'];
                $price = $valuemassage['price'];
                if($valuemassage['net_price']==1)
                {
                    $price = $valuemassage['price'] / ((1+($valuemassage['service_rate']/100))*(1+($valuemassage['tax_rate']/100)));
                }
                
                $price = $price -(($discount_rate*$price)/100);
                $price = $price + ($price*($valuemassage['service_rate']/100));
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['Quantity'] = $quantity;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitCost'] = 0;
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['UnitPrice'] = $price*($percent/100);
                $row[$folio]['WarehouseTransactionDetails'][$stt]['Amount'] = $price*($percent/100)*$quantity;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['Discount'] = 0;
                
                    $row[$folio]['WarehouseCode'] = $valuemassage['warehouse_code'];
                
                    $row[$folio]['TotalAmount'] += $price*($percent/100)*$quantity;
                    $row[$folio]['TotalQuantity'] += $quantity;
                    $row[$folio]['TotalTax'] += ($price*($percent/100)*$quantity) * ($valuemassage['tax_rate']/100);
                    
                $row[$folio]['WarehouseTransactionDetails'][$stt]['LotNumber'] = 'MASSAGE_'.$valuemassage['id'];
                $row[$folio]['WarehouseTransactionDetails'][$stt]['SeriNumber'] = 'MASSAGE_'.$valuemassage['id'];
                
                $row[$folio]['WarehouseTransactionDetails'][$stt]['DebitAccCode'] = 0;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['DebitAccCode2'] = 0;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CreditAccCode'] = 0;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['CreditAccCode2'] = 0;
                $row[$folio]['WarehouseTransactionDetails'][$stt]['ReferenceKey'] = 'FOLIO_MASSAGE_HHA_'.$valuemassage['id'];
                $row[$folio]['WarehouseTransactionDetails']['length']++;
                
                $row[$folio]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row[$folio]['FinancialBills'][$stt]['WarehouseReferenceKey'] = 'HHA'.$folio;
                $row[$folio]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
                $row[$folio]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
                $row[$folio]['FinancialBills'][$stt]['CaseItemCode'] = 'SPA';
                $row[$folio]['FinancialBills'][$stt]['BillNumber'] = 'BILL_FOLIO_HHA'.$folio;
                $row[$folio]['FinancialBills'][$stt]['BillDate'] = $row[$folio]['TransDate'];
                $row[$folio]['FinancialBills'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                $row[$folio]['FinancialBills'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                $row[$folio]['FinancialBills'][$stt]['CustomerName'] = $row[$folio]['CustomerName'];
                $row[$folio]['FinancialBills'][$stt]['CustomerAddress'] = $row[$folio]['CustomerAddress'];
                $row[$folio]['FinancialBills'][$stt]['CustomerTaxCode'] = $row[$folio]['CustomerTaxCode'];
                $row[$folio]['FinancialBills'][$stt]['Content'] = 'HHA FOLIO ID '.$folio;
                $row[$folio]['FinancialBills'][$stt]['TotalQuantity'] = $quantity;
                $row[$folio]['FinancialBills'][$stt]['TotalForeignAmount'] = 0;
                $row[$folio]['FinancialBills'][$stt]['TotalAmount'] = $price*($percent/100)*$quantity;
                $row[$folio]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
                $row[$folio]['FinancialBills'][$stt]['TaxRate'] = $valuemassage['tax_rate'];
                $row[$folio]['FinancialBills'][$stt]['TaxAmount'] = ($price*($percent/100)*$quantity) * ($valuemassage['tax_rate']/100);
                $row[$folio]['FinancialBills'][$stt]['Description'] = 'HHA VAT SPA FOLIO ID '.$folio;
                $row[$folio]['FinancialBills'][$stt]['DebitAccCode'] = 0;
                $row[$folio]['FinancialBills'][$stt]['CreditAccCode'] = 0;
                $row[$folio]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_FOLIO_HHA_SPA'.$valuemassage['id'];
                $row[$folio]['WarehouseTransactionDetails']['length']++;
            }
        }
        return $row;
    }
?>