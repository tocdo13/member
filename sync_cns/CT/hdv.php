<?php 
    function sync_invoice_hdv()
    {
        set_time_limit(-1);
        $mice = get_mice_hdv();
        foreach($mice as $id=>$content)
        {
            if($content['AccountingTransactionDetails']['length']==0)
            {
                
            }
            else
            {
                //$content['WarehouseTransactionDetails']['length'] = 
                $WarehouseTransactionDetails_length = count($content['AccountingTransactionDetails']) - 1;
                //$content['FinancialBills']['length'] = count($content['FinancialBills']) - 1;
                $FinancialBills_length = count($content['FinancialBills']) - 1;
                
                $stt = 0;
                unset($content['AccountingTransactionDetails']['length']);
                $WarehouseTransactionDetails = $content['AccountingTransactionDetails'];
                $content['AccountingTransactionDetails'] = array();
                foreach($WarehouseTransactionDetails as $key=>$value)
                {
                    $content['AccountingTransactionDetails'][$stt] = $value; $stt++;
                }
                $content['AccountingTransactionDetails']['length'] = $stt;
                
                $stt = 0;
                unset($content['FinancialBills']['length']);
                $FinancialBills = $content['FinancialBills'];
                $content['FinancialBills'] = array();
                foreach($FinancialBills as $key=>$value)
                {
                    $content['FinancialBills'][$stt] = $value; $stt++;
                }
                $content['FinancialBills']['length'] = $stt;
                
                $row = array();
                $code = $content['id'];
                unset($content['id']);
                $payment = $content['payment'];
                unset($content['payment']);
                $row = $content;
                $r = new HttpRequest(LINK_SYNC_CNS.'/api/AccountingTransactions',HttpRequest::METH_POST);
                $r->addPostFields($row);
                try 
                {
                    $r->send();
                    if($r->getResponseCode()==200)
                    {
                        DB::query("update mice_invoice set sync_cns_vt=1 where id=".$code);
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
        $folio = get_folio_hdv();
        foreach($folio as $id=>$content)
        {
            if($content['AccountingTransactionDetails']['length']==0)
            {
                
            }
            else
            {
                //$content['WarehouseTransactionDetails']['length'] = 
                $WarehouseTransactionDetails_length = count($content['AccountingTransactionDetails']) - 1;
                //$content['FinancialBills']['length'] = count($content['FinancialBills']) - 1;
                $FinancialBills_length = count($content['FinancialBills']) - 1;
                
                $stt = 0;
                unset($content['AccountingTransactionDetails']['length']);
                $WarehouseTransactionDetails = $content['AccountingTransactionDetails'];
                $content['AccountingTransactionDetails'] = array();
                foreach($WarehouseTransactionDetails as $key=>$value)
                {
                    $content['AccountingTransactionDetails'][$stt] = $value; $stt++;
                }
                $content['AccountingTransactionDetails']['length'] = $stt;
                
                $stt = 0;
                unset($content['FinancialBills']['length']);
                $FinancialBills = $content['FinancialBills'];
                $content['FinancialBills'] = array();
                foreach($FinancialBills as $key=>$value)
                {
                    $content['FinancialBills'][$stt] = $value; $stt++;
                }
                $content['FinancialBills']['length'] = $stt;
                
                $row = array();
                $code = $content['id'];
                unset($content['id']);
                $payment = $content['payment'];
                unset($content['payment']);
                $row = $content;
                $r = new HttpRequest(LINK_SYNC_CNS.'/api/AccountingTransactions',HttpRequest::METH_POST);
                $r->addPostFields($row);
                try 
                {
                    $r->send();
                    if($r->getResponseCode()==200)
                    {
                        DB::query("update folio set sync_cns_vt=1 where id=".$code);
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
        
        $bar = get_bar_hdv();
        foreach($bar as $id=>$content)
        {
            if($content['AccountingTransactionDetails']['length']==0)
            {
                
            }
            else
            {
                //$content['WarehouseTransactionDetails']['length'] = 
                $WarehouseTransactionDetails_length = count($content['AccountingTransactionDetails']) - 1;
                //$content['FinancialBills']['length'] = count($content['FinancialBills']) - 1;
                $FinancialBills_length = count($content['FinancialBills']) - 1;
                
                $stt = 0;
                unset($content['AccountingTransactionDetails']['length']);
                $WarehouseTransactionDetails = $content['AccountingTransactionDetails'];
                $content['AccountingTransactionDetails'] = array();
                foreach($WarehouseTransactionDetails as $key=>$value)
                {
                    $content['AccountingTransactionDetails'][$stt] = $value; $stt++;
                }
                $content['AccountingTransactionDetails']['length'] = $stt;
                
                $stt = 0;
                unset($content['FinancialBills']['length']);
                $FinancialBills = $content['FinancialBills'];
                $content['FinancialBills'] = array();
                foreach($FinancialBills as $key=>$value)
                {
                    $content['FinancialBills'][$stt] = $value; $stt++;
                }
                $content['FinancialBills']['length'] = $stt;
                
                $row = array();
                $code = $content['id'];
                unset($content['id']);
                $payment = $content['payment'];
                unset($content['payment']);
                $row = $content;
                $r = new HttpRequest(LINK_SYNC_CNS.'/api/AccountingTransactions',HttpRequest::METH_POST);
                $r->addPostFields($row);
                try 
                {
                    $r->send();
                    if($r->getResponseCode()==200)
                    {
                        DB::query("update bar_reservation set sync_cns_vt=1 where id=".$code);
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
        $vend = get_vend_hdv();
        foreach($vend as $id=>$content)
        {
            if($content['AccountingTransactionDetails']['length']==0)
            {
                
            }
            else
            {
                //$content['WarehouseTransactionDetails']['length'] = 
                $WarehouseTransactionDetails_length = count($content['AccountingTransactionDetails']) - 1;
                //$content['FinancialBills']['length'] = count($content['FinancialBills']) - 1;
                $FinancialBills_length = count($content['FinancialBills']) - 1;
                
                $stt = 0;
                unset($content['AccountingTransactionDetails']['length']);
                $WarehouseTransactionDetails = $content['AccountingTransactionDetails'];
                $content['AccountingTransactionDetails'] = array();
                foreach($WarehouseTransactionDetails as $key=>$value)
                {
                    $content['AccountingTransactionDetails'][$stt] = $value; $stt++;
                }
                $content['AccountingTransactionDetails']['length'] = $stt;
                
                $stt = 0;
                unset($content['FinancialBills']['length']);
                $FinancialBills = $content['FinancialBills'];
                $content['FinancialBills'] = array();
                foreach($FinancialBills as $key=>$value)
                {
                    $content['FinancialBills'][$stt] = $value; $stt++;
                }
                $content['FinancialBills']['length'] = $stt;
                
                $row = array();
                $code = $content['id'];
                unset($content['id']);
                $payment = $content['payment'];
                unset($content['payment']);
                $row = $content;
                $r = new HttpRequest(LINK_SYNC_CNS.'/api/AccountingTransactions',HttpRequest::METH_POST);
                $r->addPostFields($row);
                try 
                {
                    $r->send();
                    if($r->getResponseCode()==200)
                    {
                        DB::query("update ve_reservation set sync_cns_vt=1 where id=".$code);
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
        $spa = get_spa_hdv();
        foreach($spa as $id=>$content)
        {
            if($content['AccountingTransactionDetails']['length']==0)
            {
                
            }
            else
            {
                //$content['WarehouseTransactionDetails']['length'] = 
                $WarehouseTransactionDetails_length = count($content['AccountingTransactionDetails']) - 1;
                //$content['FinancialBills']['length'] = count($content['FinancialBills']) - 1;
                $FinancialBills_length = count($content['FinancialBills']) - 1;
                
                $stt = 0;
                unset($content['AccountingTransactionDetails']['length']);
                $WarehouseTransactionDetails = $content['AccountingTransactionDetails'];
                $content['AccountingTransactionDetails'] = array();
                foreach($WarehouseTransactionDetails as $key=>$value)
                {
                    $content['AccountingTransactionDetails'][$stt] = $value; $stt++;
                }
                $content['AccountingTransactionDetails']['length'] = $stt;
                
                $stt = 0;
                unset($content['FinancialBills']['length']);
                $FinancialBills = $content['FinancialBills'];
                $content['FinancialBills'] = array();
                foreach($FinancialBills as $key=>$value)
                {
                    $content['FinancialBills'][$stt] = $value; $stt++;
                }
                $content['FinancialBills']['length'] = $stt;
                
                $row = array();
                $code = $content['id'];
                unset($content['id']);
                $payment = $content['payment'];
                unset($content['payment']);
                $row = $content;
                $r = new HttpRequest(LINK_SYNC_CNS.'/api/AccountingTransactions',HttpRequest::METH_POST);
                $r->addPostFields($row);
                try 
                {
                    $r->send();
                    if($r->getResponseCode()==200)
                    {
                        DB::query("update massage_reservation_room set sync_cns_vt=1 where id=".$code);
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
    
    function get_mice_hdv()
    {
        $row = array();
        $mice = DB::fetch_all("
                                SELECT
                                    mice_invoice_detail.*,
                                    mice_reservation.id as mice_reservation_id,
                                    mice_reservation.customer_id,
                                    mice_reservation.traveller_id,
                                    mice_invoice.payment_time,
                                    mice_invoice.bill_id,
                                    NVL(mice_invoice.extra_vat,0) as extra_vat,
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
                                    and mice_invoice.sync_cns_vt=0
                                    and mice_invoice.bill_id is not null
                                    AND mice_invoice.payment_time >= ".Date_Time::to_time(DATE_SYNC_CNS)."
                                ");
        $hcb_start_idstruct = DB::fetch('select structure_id from product_category where code=\'HCB\'','structure_id');
        $hcb_end_idstruct = IDStructure::next($hcb_start_idstruct);
        $du_start_idstruct = DB::fetch('select structure_id from product_category where code=\'DU\'','structure_id');
        $du_end_idstruct = IDStructure::next($du_start_idstruct);
        $da_start_idstruct = DB::fetch('select structure_id from product_category where code=\'DA\'','structure_id');
        $da_end_idstruct = IDStructure::next($da_start_idstruct);
        $dv_start_idstruct = DB::fetch('select structure_id from product_category where code=\'DVNH\'','structure_id');
        $dv_end_idstruct = IDStructure::next($dv_start_idstruct);
        
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
        $ListTicket = array();
        $CondTicket = '';
        
        foreach($mice as $key=>$value)
        {
            $in_date = date('d/m/Y',$value['payment_time']);
            
            if(!isset($row[$value['mice_invoice_id']]))
            {
                /**
                 * Khoi tao mang MAIN
                 * ma chung tu HDV
                 * */
                $row[$value['mice_invoice_id']]['id'] = $value['bill_id'];
                $row[$value['mice_invoice_id']]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row[$value['mice_invoice_id']]['TransTypeCode'] = 'HDV';
                
                $row[$value['mice_invoice_id']]['CreatedOn'] = date('Y-m-d');
                $row[$value['mice_invoice_id']]['ChangedOn'] = date('Y-m-d');
                
                $row[$value['mice_invoice_id']]['Code'] = 'HDV-BILLMICE'.$value['bill_id'];
                $create_date = explode('/',$in_date);
                $row[$value['mice_invoice_id']]['TransDate'] = $create_date[2].'-'.$create_date[1].'-'.$create_date[0];
                $row[$value['mice_invoice_id']]['Description'] = 'BILLMICE#'.$value['bill_id'];
                $row[$value['mice_invoice_id']]['TotalAmount'] = 0;
                $row[$value['mice_invoice_id']]['TotalTax'] = 0;
                $row[$value['mice_invoice_id']]['ContactCode'] = 'KHACHLE_HOTEL';
                if($value['customer_id']!='')
                    $row[$value['mice_invoice_id']]['ContactCode'] = 'CUS_'.$value['customer_id'];
                elseif($value['traveller_id']!='')
                    $row[$value['mice_invoice_id']]['ContactCode'] = 'TRA_'.$value['traveller_id'];
                    
                
                $row[$value['mice_invoice_id']]['ContactCode2'] = '';
                $row[$value['mice_invoice_id']]['payment'] = 1;
                
                $row[$value['mice_invoice_id']]['CustomerName'] = $value['customer_name']==''?$value['traveller_name']:$value['customer_name'];
                $row[$value['mice_invoice_id']]['CustomerAddress'] = $value['customer_name']==''?$value['traveller_address']:$value['customer_address'];
                $row[$value['mice_invoice_id']]['CustomerTaxCode'] = $value['customer_name']==''?'':$value['customer_tax_code'];
                $row[$value['mice_invoice_id']]['CurrencyCode'] = 'VND';
                $row[$value['mice_invoice_id']]['CurrencyRate'] = '';
                $row[$value['mice_invoice_id']]['IsReceivables'] = '';
                $row[$value['mice_invoice_id']]['AccTransTypeBIT'] = '';
                $row[$value['mice_invoice_id']]['ReferenceKey'] = 'HDV-BILLMICE'.$value['bill_id'];
                
                // detail
                $row[$value['mice_invoice_id']]['AccountingTransactionDetails'] = array();
                $row[$value['mice_invoice_id']]['AccountingTransactionDetails']['length'] = 0;
                
                // vat 
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
                        $row_pay['Code'] = 'BILL_MICE_'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        $row_pay['TransDate'] = date('Y-m-d',$valuepay['time']);
                        $row_pay['Description'] = $valuepay['description'];
                        $row_pay['TotalAmount'] = $value['amount']*$value['exchange_rate'];
                        if($value['currency_id']!='VND')
                            $row_pay['TotalForeignAmount'] = $value['amount'];
                        $row_pay['TotalTax'] = 0;
                        $row_pay['CustomerName'] = '';
                        $row_pay['CustomerAddress'] = '';
                        $row_pay['CustomerTaxCode'] = '';
                        $row_pay['CurrencyCode'] = $valuepay['currency_id'];
                        $row_pay['CurrencyRate'] = $valuepay['exchange_rate'];
                        $row_pay['IsReceivables'] = '';
                        $row_pay['AccTransTypeBIT'] = '';
                        $row_pay['ReferenceKey'] = 'BILL_MICE_'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        
                        $row_pay['AccountingTransactionDetails'] = array();
                        $row_pay['AccountingTransactionDetails']['length'] = 1;
                        $row_pay['AccountingTransactionDetails'][0]['ParentReferenceKey'] = 'BILL_MICE_'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        $row_pay['AccountingTransactionDetails'][0]['CreatedOn'] = date('Y-m-d');
                        $row_pay['AccountingTransactionDetails'][0]['ChangedOn'] = date('Y-m-d');
                        $row_pay['AccountingTransactionDetails'][0]['DebitAccCode'] = 112;
                        $row_pay['AccountingTransactionDetails'][0]['CreditAccCode'] = 511;
                        $row_pay['AccountingTransactionDetails'][0]['Description'] = $valuepay['description'];
                        $row_pay['AccountingTransactionDetails'][0]['Amount'] = $value['amount']*$value['exchange_rate'];
                        if($value['currency_id']!='VND')
                            $row_pay['AccountingTransactionDetails'][0]['ForeignAmount'] = $value['amount'];
                        $row_pay['AccountingTransactionDetails'][0]['IsReceivables'] = '';
                        $row_pay['AccountingTransactionDetails'][0]['ReferenceKey'] = 'BILL_MICE_'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        
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
                
                if($value['extra_vat']!=0)
                {
                    $amount = $value['extra_vat'];
                    $tax = 0;
                    $row[$value['mice_invoice_id']]['TotalAmount'] += $amount;
                    $row[$value['mice_invoice_id']]['TotalTax'] += $tax;
                    
                    $stt = $row[$value['mice_invoice_id']]['AccountingTransactionDetails']['length'];
                    
                    $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['ParentReferenceKey'] = 'HDV-BILLMICE'.$value['bill_id'];
                    
                    $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                    $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                    
                    $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'OTHER_SERVICE';
                    $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['FeeItemCode'] = 'OTHER_SERVICE';
                    $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['ContactCode'] = $row[$value['mice_invoice_id']]['ContactCode'];
                    $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['ContactCode2'] = $row[$value['mice_invoice_id']]['ContactCode2'];
                    $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['DebitAccCode'] = 111;
                    $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['CreditAccCode'] = 511;
                    $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['Description'] = 'DETAIL BILLMICE'.$value['bill_id'].' HDV';
                    $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['IsReceivables'] = '';
                    $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['ReferenceKey'] = 'DETAIL_HDV_EXTRA_VAT-BILLMICE'.$value['bill_id'];
                    $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['Amount'] = $amount;
                    $row[$value['mice_invoice_id']]['AccountingTransactionDetails']['length']++;
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
            elseif($value['type']=='TICKET')
            {
                $ListTicket[$value['invoice_id']]['id'] = $value['invoice_id'];
                $ListTicket[$value['invoice_id']]['percent'] = $value['percent'];
                $ListTicket[$value['invoice_id']]['folio_id'] = $value['mice_invoice_id'];
                if($CondTicket=='')
                    $CondTicket = 'ticket_reservation.id='.$value['invoice_id'];
                else
                    $CondTicket .= ' OR ticket_reservation.id='.$value['invoice_id'];
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
            elseif($value['type']=='DISCOUNT')
            {
                $row[$value['mice_invoice_id']]['TotalAmount'] -= $value['amount'] * ($value['percent']/100);
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
                        $row_pay['Code'] = 'BILL_MICE_'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        $row_pay['TransDate'] = date('Y-m-d',$valuepay['time']);
                        $row_pay['Description'] = $valuepay['description'];
                        $row_pay['TotalAmount'] = $valuepay['amount'];
                        $row_pay['TotalAmount'] = $value['amount']*$value['exchange_rate'];
                        if($value['currency_id']!='VND')
                            $row_pay['TotalForeignAmount'] = $value['amount'];
                        $row_pay['TotalTax'] = 0;
                        $row_pay['CustomerName'] = '';
                        $row_pay['CustomerAddress'] = '';
                        $row_pay['CustomerTaxCode'] = '';
                        $row_pay['CurrencyCode'] = $valuepay['currency_id'];
                        $row_pay['CurrencyRate'] = $valuepay['exchange_rate'];
                        $row_pay['IsReceivables'] = '';
                        $row_pay['AccTransTypeBIT'] = '';
                        $row_pay['ReferenceKey'] = 'BILL_MICE_'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        
                        $row_pay['AccountingTransactionDetails'] = array();
                        $row_pay['AccountingTransactionDetails']['length'] = 1;
                        $row_pay['AccountingTransactionDetails'][0]['ParentReferenceKey'] = 'BILL_MICE_'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        $row_pay['AccountingTransactionDetails'][0]['CreatedOn'] = date('Y-m-d');
                        $row_pay['AccountingTransactionDetails'][0]['ChangedOn'] = date('Y-m-d');
                        $row_pay['AccountingTransactionDetails'][0]['DebitAccCode'] = 112;
                        $row_pay['AccountingTransactionDetails'][0]['CreditAccCode'] = 511;
                        $row_pay['AccountingTransactionDetails'][0]['Description'] = $valuepay['description'];
                        $row_pay['AccountingTransactionDetails'][0]['Amount'] = $value['amount']*$value['exchange_rate'];
                        if($value['currency_id']!='VND')
                            $row_pay['AccountingTransactionDetails'][0]['ForeignAmount'] = $value['amount'];
                        $row_pay['AccountingTransactionDetails'][0]['IsReceivables'] = '';
                        $row_pay['AccountingTransactionDetails'][0]['ReferenceKey'] = 'BILL_MICE_'.$valuepay['bill_id'].'_'.$valuepay['id'];
                        
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
            else
            {
                $amount = $value['amount'] + ($value['amount']*($value['service_rate']/100));
                $amount = $amount * ($value['percent']/100);
                $tax = $amount * ($value['tax_rate']/100);
                $row[$value['mice_invoice_id']]['TotalAmount'] += $amount;
                $row[$value['mice_invoice_id']]['TotalTax'] += $tax;
                
                $stt = $row[$value['mice_invoice_id']]['AccountingTransactionDetails']['length'];
                
                $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['ParentReferenceKey'] = 'HDV-BILLMICE'.$value['bill_id'];
                
                $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                
                $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = $value['type'];
                $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['FeeItemCode'] = $value['type'];
                $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['ContactCode'] = $row[$value['mice_invoice_id']]['ContactCode'];
                $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['ContactCode2'] = $row[$value['mice_invoice_id']]['ContactCode2'];
                $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['DebitAccCode'] = 111;
                $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['CreditAccCode'] = 511;
                $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['Description'] = 'DETAIL FOLIO HDV';
                $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['IsReceivables'] = '';
                $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['ReferenceKey'] = 'DETAIL_HDV-BILLMICE'.$value['id'];
                $row[$value['mice_invoice_id']]['AccountingTransactionDetails'][$stt]['Amount'] = $amount;
                $row[$value['mice_invoice_id']]['AccountingTransactionDetails']['length']++;
                
                $row[$value['mice_invoice_id']]['FinancialBills']['length']++;
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['AccReferenceKey'] = 'HDV-BILLMICE'.$value['bill_id'];
                
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['CaseItemCode'] = $value['type'];
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['BillNumber'] = 'BILLMICE_HDV'.$value['bill_id'];
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['BillDate'] = $row[$value['mice_invoice_id']]['TransDate'];
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['ContactCode'] = $row[$value['mice_invoice_id']]['ContactCode'];
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['ContactCode2'] = $row[$value['mice_invoice_id']]['ContactCode2'];
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['CustomerName'] = $row[$value['mice_invoice_id']]['CustomerName'];
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['CustomerAddress'] = $row[$value['mice_invoice_id']]['CustomerAddress'];
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['CustomerTaxCode'] = $row[$value['mice_invoice_id']]['CustomerTaxCode'];
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['Content'] = 'VAT_HDV-BILLMICE'.$value['bill_id'];
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['TotalQuantity'] = 1;
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['TotalForeignAmount'] = '';
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['TotalAmount'] = $amount;
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['TaxRate'] = $value['tax_rate'];
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['TaxAmount'] = $tax;
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['Description'] = 'VAT HDV-BILLMICE '.$value['type'].' HDV';
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['DebitAccCode'] = 111;
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['CreditAccCode'] = 511;
                $row[$value['mice_invoice_id']]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_HDV-BILLMICE_'.$value['type'].$value['id'];
                
            }
        }
        /** get list package detail **/
        if($CondPackage!='')
        {
            $package = DB::fetch_all("
                    SELECT 
                        package_sale_detail.id || '_' || reservation_room.id as id
                        ,package_sale_detail.quantity
                        ,package_sale_detail.price
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
                else
                {
                    $folio_id = $ListPackage[$valuepackage['reservation_room_id']]['folio_id'];
                    $percent = $ListPackage[$valuepackage['reservation_room_id']]['percent'];
                    
                    $stt = $row[$folio_id]['AccountingTransactionDetails']['length'];
                    
                    $row[$folio_id]['TotalAmount'] += $valuepackage['quantity']*$valuepackage['price'];
                    
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['ParentReferenceKey'] = 'HDV-BILLMICE'.$folio_id;
                    
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                    
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'PACKAGE';
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['FeeItemCode'] = 'PACKAGE';
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['ContactCode'] = $row[$folio_id]['ContactCode'];
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['ContactCode2'] = $row[$folio_id]['ContactCode2'];
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['DebitAccCode'] = 111;
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['CreditAccCode'] = 511;
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['Description'] = 'DETAIL HDV-BILLMICE PACKAGE HDV';
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['IsReceivables'] = '';
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['ReferenceKey'] = 'DETAIL_HDV-BILLMICE_PACKAGE'.$valuepackage['id'];
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['Amount'] = $amount;
                    $row[$folio_id]['AccountingTransactionDetails']['length']++;
                    
                    //$row[$folio_id]['FinancialBills'][0]['TotalAmount'] += $valuepackage['quantity']*$valuepackage['price'];
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
                                    FROM
                                        housekeeping_invoice_detail
                                        inner join housekeeping_invoice on housekeeping_invoice.id=housekeeping_invoice_detail.invoice_id
                                        inner join product on product.id=housekeeping_invoice_detail.product_id
                                        left join unit on unit.id=product.unit_id
                                    WHERE
                                        (".$CondMinibar.") AND (product.type != 'GOODS' AND product.type != 'PRODUCT' AND product.type != 'DRINK')
                                    ");
                                    
            foreach($minibar as $keyminibar=>$valueminibar)
            {
                $folio = $ListMinibar[$valueminibar['housekeeping_invoice_id']]['folio_id'];
                $percent = $ListMinibar[$valueminibar['housekeeping_invoice_id']]['percent'];
                if($valueminibar['net_price']==1)
                {
                    $valueminibar['price'] = $valueminibar['price'] / ((1+($valueminibar['service_rate']/100))*(1+($valueminibar['tax_rate']/100)));
                }
                $price = $valueminibar['price'] + ($valueminibar['price']*($valueminibar['service_rate']/100));
                
                $row[$folio]['TotalAmount'] += $price*($percent/100)*$valueminibar['quantity'];
                $row[$folio]['TotalTax'] += ($price*($percent/100)*$valueminibar['quantity']) * ($valueminibar['tax_rate']/100);
                    $stt = $row[$folio]['AccountingTransactionDetails']['length'];
                        $row[$folio]['AccountingTransactionDetails'][$stt]['ParentReferenceKey'] = 'HDV-BILLMICE'.$folio;
                        
                        $row[$folio]['AccountingTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                        $row[$folio]['AccountingTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                        
                        $row[$folio]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'MINIBAR';
                        $row[$folio]['AccountingTransactionDetails'][$stt]['FeeItemCode'] = $valueminibar['product_id'];
                        $row[$folio]['AccountingTransactionDetails'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                        $row[$folio]['AccountingTransactionDetails'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                        $row[$folio]['AccountingTransactionDetails'][$stt]['DebitAccCode'] = 111;
                        $row[$folio]['AccountingTransactionDetails'][$stt]['CreditAccCode'] = 511;
                        $row[$folio]['AccountingTransactionDetails'][$stt]['Description'] = 'DETAIL HDV-BILLMICE MINIBAR HDV';
                        $row[$folio]['AccountingTransactionDetails'][$stt]['IsReceivables'] = '';
                        $row[$folio]['AccountingTransactionDetails'][$stt]['ReferenceKey'] = 'DETAIL_HDV-BILLMICE_MINIBAR'.$valueminibar['id'];
                        $row[$folio]['AccountingTransactionDetails'][$stt]['Amount'] = $price*($percent/100)*$valueminibar['quantity'];
                        $row[$folio]['AccountingTransactionDetails']['length']++;
                        
                        $row[$folio]['FinancialBills']['length']++;
                        $row[$folio]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                        $row[$folio]['FinancialBills'][$stt]['AccReferenceKey'] = 'HDV-BILLMICE'.$folio;
                        
                        $row[$folio]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
                        $row[$folio]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
                        $row[$folio]['FinancialBills'][$stt]['CaseItemCode'] = 'MINIBAR';
                        $row[$folio]['FinancialBills'][$stt]['BillNumber'] = 'BILLMICE_HDV'.$folio;
                        $row[$folio]['FinancialBills'][$stt]['BillDate'] = $row[$folio]['TransDate'];
                        $row[$folio]['FinancialBills'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                        $row[$folio]['FinancialBills'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                        $row[$folio]['FinancialBills'][$stt]['CustomerName'] = $row[$folio]['CustomerName'];
                        $row[$folio]['FinancialBills'][$stt]['CustomerAddress'] = $row[$folio]['CustomerAddress'];
                        $row[$folio]['FinancialBills'][$stt]['CustomerTaxCode'] = $row[$folio]['CustomerTaxCode'];
                        $row[$folio]['FinancialBills'][$stt]['Content'] = 'VAT_HDV-BILLMICE'.$folio;
                        $row[$folio]['FinancialBills'][$stt]['TotalQuantity'] = 1;
                        $row[$folio]['FinancialBills'][$stt]['TotalForeignAmount'] = '';
                        $row[$folio]['FinancialBills'][$stt]['TotalAmount'] = $price*($percent/100)*$valueminibar['quantity'];
                        $row[$folio]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
                        $row[$folio]['FinancialBills'][$stt]['TaxRate'] = $valueminibar['tax_rate'];
                        $row[$folio]['FinancialBills'][$stt]['TaxAmount'] = ($price*($percent/100)*$valueminibar['quantity']) * ($valueminibar['tax_rate']/100);
                        $row[$folio]['FinancialBills'][$stt]['Description'] = 'VAT HDV-BILLMICE MINIBAR HDV';
                        $row[$folio]['FinancialBills'][$stt]['DebitAccCode'] = 111;
                        $row[$folio]['FinancialBills'][$stt]['CreditAccCode'] = 511;
                        $row[$folio]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_HDV-BILLMICE_MINIBAR'.$valueminibar['id'];
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
                                            ,product.type as product_type
                                            ,unit.name_1 as unit_name
                                        FROM
                                            bar_reservation_product
                                            inner join bar_reservation on bar_reservation.id=bar_reservation_product.bar_reservation_id
                                            inner join product on product.id=bar_reservation_product.product_id
                                            inner join product_category on product_category.id=product.category_id
                                            left join unit on unit.id=product.unit_id
                                        WHERE
                                            (".$CondBar.") AND (product.type != 'GOODS' AND product.type != 'PRODUCT' AND product.type != 'DRINK')
                                        ");
            $check_confict_bar_reservation = array();
            
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
                if(!isset($check_confict_bar_reservation[$valuebar['bar_reservation_id']]) and $valuebar['extra_vat']!=0 and $valuebar['sync_cns_extra_vat']!=1)
                {
                    $check_confict_bar_reservation[$valuebar['bar_reservation_id']] = $valuebar['bar_reservation_id'];
                    $row[$folio]['TotalAmount'] += $valuebar['extra_vat'];
                    $stt = $row[$folio]['AccountingTransactionDetails']['length'];
                        $row[$folio]['AccountingTransactionDetails'][$stt]['ParentReferenceKey'] = 'HDV-BILLMICE'.$folio;
                        $row[$folio]['AccountingTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                        $row[$folio]['AccountingTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                        $row[$folio]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'OTHER_SERVICE';
                        $row[$folio]['AccountingTransactionDetails'][$stt]['FeeItemCode'] = 'OTHER_SERVICE';
                        $row[$folio]['AccountingTransactionDetails'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                        $row[$folio]['AccountingTransactionDetails'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                        $row[$folio]['AccountingTransactionDetails'][$stt]['DebitAccCode'] = 111;
                        $row[$folio]['AccountingTransactionDetails'][$stt]['CreditAccCode'] = 511;
                        $row[$folio]['AccountingTransactionDetails'][$stt]['Description'] = 'DETAIL HDV-BILLMICE EXTRA VAT BAR HDV';
                        $row[$folio]['AccountingTransactionDetails'][$stt]['IsReceivables'] = '';
                        $row[$folio]['AccountingTransactionDetails'][$stt]['ReferenceKey'] = 'DETAIL_HDV-BILLMICE_EXTRA_VAT_BAR'.$valuebar['bar_reservation_id'];
                        $row[$folio]['AccountingTransactionDetails'][$stt]['Amount'] = $valuebar['extra_vat'];
                        $row[$folio]['AccountingTransactionDetails']['length']++;
                    DB::query("update bar_reservation set sync_cns_extra_vat=1 where id=".$valuebar['bar_reservation_id']);
                }
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
                
                
                $row[$folio]['TotalAmount'] += $price*($percent/100)*$quantity;
                $row[$folio]['TotalTax'] += ($price*($percent/100)*$quantity) * ($valuebar['tax_rate']/100);
                $stt = $row[$folio]['AccountingTransactionDetails']['length'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ParentReferenceKey'] = 'HDV-BILLMICE'.$folio;
                    
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = $valuebar['product_id'];
                    
                    if($valuebar['product_type']=='GOODS')
                        $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'HCB';
                    if($valuebar['product_type']=='DRINK')
                        $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'DU';
                    if($valuebar['product_type']=='PRODUCT')
                        $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'DA';
                    if($valuebar['product_type']=='SERVICE')
                        $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'DVNH';
                    
                    $row[$folio]['AccountingTransactionDetails'][$stt]['FeeItemCode'] = $valuebar['product_id'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['DebitAccCode'] = 111;
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CreditAccCode'] = 511;
                    $row[$folio]['AccountingTransactionDetails'][$stt]['Description'] = 'DETAIL HDV-BILLMICE BAR HDV';
                    $row[$folio]['AccountingTransactionDetails'][$stt]['IsReceivables'] = '';
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ReferenceKey'] = 'DETAIL_HDV-BILLMICE_BAR'.$valuebar['id'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['Amount'] = $price*($percent/100)*$quantity;
                    $row[$folio]['AccountingTransactionDetails']['length']++;
                    
                    $row[$folio]['FinancialBills']['length']++;
                    $row[$folio]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                    $row[$folio]['FinancialBills'][$stt]['AccReferenceKey'] = 'HDV-BILLMICE'.$folio;
                    
                    $row[$folio]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
                    $row[$folio]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
                    $row[$folio]['FinancialBills'][$stt]['CaseItemCode'] = $row[$folio]['AccountingTransactionDetails'][$stt]['CaseItemCode'];
                    $row[$folio]['FinancialBills'][$stt]['BillNumber'] = 'BILLMICE_HDV'.$folio;
                    $row[$folio]['FinancialBills'][$stt]['BillDate'] = $row[$folio]['TransDate'];
                    $row[$folio]['FinancialBills'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                    $row[$folio]['FinancialBills'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerName'] = $row[$folio]['CustomerName'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerAddress'] = $row[$folio]['CustomerAddress'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerTaxCode'] = $row[$folio]['CustomerTaxCode'];
                    $row[$folio]['FinancialBills'][$stt]['Content'] = 'VAT_HDV-BILLMICE'.$folio;
                    $row[$folio]['FinancialBills'][$stt]['TotalQuantity'] = 1;
                    $row[$folio]['FinancialBills'][$stt]['TotalForeignAmount'] = '';
                    $row[$folio]['FinancialBills'][$stt]['TotalAmount'] = $price*($percent/100)*$quantity;
                    $row[$folio]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
                    $row[$folio]['FinancialBills'][$stt]['TaxRate'] = $valuebar['tax_rate'];
                    $row[$folio]['FinancialBills'][$stt]['TaxAmount'] = ($price*($percent/100)*$quantity) * ($valuebar['tax_rate']/100);
                    $row[$folio]['FinancialBills'][$stt]['Description'] = 'VAT HDV-BILLMICE BAR HDV';
                    $row[$folio]['FinancialBills'][$stt]['DebitAccCode'] = 111;
                    $row[$folio]['FinancialBills'][$stt]['CreditAccCode'] = 511;
                    $row[$folio]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_HDV-BILLMICE_BAR'.$valuebar['id'];
                    
            }
        }
        if($CondVe!='')
        {
            /** get ve **/
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
                                        FROM
                                            ve_reservation_product
                                            inner join ve_reservation on ve_reservation.id=ve_reservation_product.bar_reservation_id
                                            inner join product on product.id=ve_reservation_product.product_id
                                            left join unit on unit.id=product.unit_id
                                        WHERE
                                            (".$CondVe.") AND (product.type != 'GOODS' AND product.type != 'PRODUCT' AND product.type != 'DRINK')
                                        ");
            //System::debug($restaurant);
            foreach($vending as $keybar=>$valuebar)
            {
                $folio = $ListVe[$valuebar['bar_reservation_id']]['folio_id'];
                $percent = $ListVe[$valuebar['bar_reservation_id']]['percent'];
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
                
                
                $row[$folio]['TotalAmount'] += $price*($percent/100)*$quantity;
                $row[$folio]['TotalTax'] += ($price*($percent/100)*$quantity) * ($valuebar['tax_rate']/100);
                $stt = $row[$folio]['AccountingTransactionDetails']['length'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ParentReferenceKey'] = 'HDV-BILLMICE'.$folio;
                    
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                    
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'VEND_'.$valuebar['department_code'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['FeeItemCode'] = $valuebar['product_id'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['DebitAccCode'] = 111;
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CreditAccCode'] = 511;
                    $row[$folio]['AccountingTransactionDetails'][$stt]['Description'] = 'DETAIL HDV-BILLMICE VEND HDV';
                    $row[$folio]['AccountingTransactionDetails'][$stt]['IsReceivables'] = '';
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ReferenceKey'] = 'DETAIL_HDV-BILLMICE_VEND'.$valuebar['id'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['Amount'] = $price*($percent/100)*$quantity;
                    $row[$folio]['AccountingTransactionDetails']['length']++;
                    
                    $row[$folio]['FinancialBills']['length']++;
                    $row[$folio]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                    $row[$folio]['FinancialBills'][$stt]['AccReferenceKey'] = 'HDV-BILLMICE'.$folio;
                    
                    $row[$folio]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
                    $row[$folio]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
                    $row[$folio]['FinancialBills'][$stt]['CaseItemCode'] = 'VEND_'.$valuebar['department_code'];
                    $row[$folio]['FinancialBills'][$stt]['BillNumber'] = 'BILLMICE_HDV'.$folio;
                    $row[$folio]['FinancialBills'][$stt]['BillDate'] = $row[$folio]['TransDate'];
                    $row[$folio]['FinancialBills'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                    $row[$folio]['FinancialBills'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerName'] = $row[$folio]['CustomerName'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerAddress'] = $row[$folio]['CustomerAddress'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerTaxCode'] = $row[$folio]['CustomerTaxCode'];
                    $row[$folio]['FinancialBills'][$stt]['Content'] = 'VAT_HDV-BILLMICE'.$folio;
                    $row[$folio]['FinancialBills'][$stt]['TotalQuantity'] = 1;
                    $row[$folio]['FinancialBills'][$stt]['TotalForeignAmount'] = '';
                    $row[$folio]['FinancialBills'][$stt]['TotalAmount'] = $price*($percent/100)*$quantity;
                    $row[$folio]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
                    $row[$folio]['FinancialBills'][$stt]['TaxRate'] = $valuebar['tax_rate'];
                    $row[$folio]['FinancialBills'][$stt]['TaxAmount'] = ($price*($percent/100)*$quantity) * ($valuebar['tax_rate']/100);
                    $row[$folio]['FinancialBills'][$stt]['Description'] = 'VAT HDV-BILLMICE VEND HDV';
                    $row[$folio]['FinancialBills'][$stt]['DebitAccCode'] = 111;
                    $row[$folio]['FinancialBills'][$stt]['CreditAccCode'] = 511;
                    $row[$folio]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_HDV-BILLMICE_VEND'.$valuebar['id'];
                    
            }
        }
        if($CondTicket!='')
        {
            /** get ve **/
            $ticket = DB::fetch_all("
                                        SELECT 
                                            ticket_invoice.id,
                                            ticket_invoice.ticket_id*2 as ticket_id,
                                            DECODE(ticket_reservation.discount_rate,null,0,ticket_reservation.discount_rate) as discount_rate,
                                            ticket_invoice.quantity,
                                            ticket_invoice.price,
                                           	DECODE(ticket_invoice.discount_quantity,null,0,ticket_invoice.discount_quantity) as discount_quantity,
                                            ticket_invoice.total,
                                            ticket.code,
                                            ticket_reservation.id as ticket_reservation_id,
                                            ticket_reservation.customer_id,
                                            ticket_reservation.traveller_id
                            			FROM 
                            			    ticket_invoice
                                            inner join ticket_reservation on ticket_reservation.id = ticket_invoice.ticket_reservation_id  
                                            inner join ticket on ticket_invoice.ticket_id = ticket.id
                                        WHERE
                                            (".$CondTicket.")
                                            and ticket_invoice.export_ticket=1
                                        ");
            //System::debug($restaurant);
            foreach($ticket as $keybar=>$valuebar)
            {
                $folio = $ListTicket[$valuebar['ticket_reservation_id']]['folio_id'];
                $percent = $ListTicket[$valuebar['ticket_reservation_id']]['percent'];
                $quantity = 1;
                $price = $valuebar['total']/1.1;
                $row[$folio]['TotalAmount'] += $price*($percent/100)*$quantity;
                $row[$folio]['TotalTax'] += ($price*($percent/100)*$quantity) * (10/100);
                $stt = $row[$folio]['AccountingTransactionDetails']['length'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ParentReferenceKey'] = 'HDV-BILLMICE'.$folio;
                    
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                    
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'TICKET_'.$valuebar['code'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['FeeItemCode'] = 'TICKET_'.$valuebar['code'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['DebitAccCode'] = 111;
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CreditAccCode'] = 511;
                    $row[$folio]['AccountingTransactionDetails'][$stt]['Description'] = 'DETAIL HDV-BILLMICE TICKET HDV';
                    $row[$folio]['AccountingTransactionDetails'][$stt]['IsReceivables'] = '';
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ReferenceKey'] = 'DETAIL_HDV-BILLMICE_TICKET'.$valuebar['id'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['Amount'] = $price*($percent/100)*$quantity;
                    $row[$folio]['AccountingTransactionDetails']['length']++;
                    
                    $row[$folio]['FinancialBills']['length']++;
                    $row[$folio]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                    $row[$folio]['FinancialBills'][$stt]['AccReferenceKey'] = 'HDV-BILLMICE'.$folio;
                    
                    $row[$folio]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
                    $row[$folio]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
                    $row[$folio]['FinancialBills'][$stt]['CaseItemCode'] = 'TICKET_'.$valuebar['code'];
                    $row[$folio]['FinancialBills'][$stt]['BillNumber'] = 'BILLMICE_HDV'.$folio;
                    $row[$folio]['FinancialBills'][$stt]['BillDate'] = $row[$folio]['TransDate'];
                    $row[$folio]['FinancialBills'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                    $row[$folio]['FinancialBills'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerName'] = $row[$folio]['CustomerName'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerAddress'] = $row[$folio]['CustomerAddress'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerTaxCode'] = $row[$folio]['CustomerTaxCode'];
                    $row[$folio]['FinancialBills'][$stt]['Content'] = 'VAT_HDV-BILLMICE'.$folio;
                    $row[$folio]['FinancialBills'][$stt]['TotalQuantity'] = 1;
                    $row[$folio]['FinancialBills'][$stt]['TotalForeignAmount'] = '';
                    $row[$folio]['FinancialBills'][$stt]['TotalAmount'] = $price*($percent/100)*$quantity;
                    $row[$folio]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
                    $row[$folio]['FinancialBills'][$stt]['TaxRate'] = 10;
                    $row[$folio]['FinancialBills'][$stt]['TaxAmount'] = ($price*($percent/100)*$quantity) * (10/100);
                    $row[$folio]['FinancialBills'][$stt]['Description'] = 'VAT HDV-BILLMICE VEND HDV';
                    $row[$folio]['FinancialBills'][$stt]['DebitAccCode'] = 111;
                    $row[$folio]['FinancialBills'][$stt]['CreditAccCode'] = 511;
                    $row[$folio]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_HDV-BILLMICE_VEND'.$valuebar['id'];
                    
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
                                    FROM
                                        massage_product_consumed
                                        inner join massage_reservation_room on massage_reservation_room.id=massage_product_consumed.reservation_room_id
                                        inner join product on product.id=massage_product_consumed.product_id
                                        left join unit on unit.id=product.unit_id
                                    WHERE
                                        (".$CondMassage.") AND (product.type != 'GOODS' AND product.type != 'PRODUCT' AND product.type != 'DRINK')
                                    ");
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
                
                
                $quantity = $valuemassage['quantity'];
                $discount_rate = $valuemassage['discount'];
                $price = $valuemassage['price'];
                if($valuemassage['net_price']==1)
                {
                    $price = $valuemassage['price'] / ((1+($valuemassage['service_rate']/100))*(1+($valuemassage['tax_rate']/100)));
                }
                
                $price = $price -(($discount_rate*$price)/100);
                $price = $price + ($price*($valuemassage['service_rate']/100));
                
                $row[$folio]['TotalAmount'] += $price*($percent/100)*$quantity;
                $row[$folio]['TotalTax'] += ($price*($percent/100)*$quantity) * ($valuemassage['tax_rate']/100);
                $stt = $row[$folio]['AccountingTransactionDetails']['length'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ParentReferenceKey'] = 'HDV-BILLMICE'.$folio;
                    
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                    
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'SPA';
                    $row[$folio]['AccountingTransactionDetails'][$stt]['FeeItemCode'] = $valuemassage['product_id'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['DebitAccCode'] = 111;
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CreditAccCode'] = 511;
                    $row[$folio]['AccountingTransactionDetails'][$stt]['Description'] = 'DETAIL HDV-BILLMICE SPA HDV';
                    $row[$folio]['AccountingTransactionDetails'][$stt]['IsReceivables'] = '';
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ReferenceKey'] = 'DETAIL_HDV-BILLMICE_SPA'.$valuemassage['id'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['Amount'] = $price*($percent/100)*$quantity;
                    $row[$folio]['AccountingTransactionDetails']['length']++;
                    
                    $row[$folio]['FinancialBills']['length']++;
                    $row[$folio]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                    $row[$folio]['FinancialBills'][$stt]['AccReferenceKey'] = 'HDV-BILLMICE'.$folio;
                    
                    $row[$folio]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
                    $row[$folio]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
                    $row[$folio]['FinancialBills'][$stt]['CaseItemCode'] = 'SPA';
                    $row[$folio]['FinancialBills'][$stt]['BillNumber'] = 'BILLMICE_HDV'.$folio;
                    $row[$folio]['FinancialBills'][$stt]['BillDate'] = $row[$folio]['TransDate'];
                    $row[$folio]['FinancialBills'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                    $row[$folio]['FinancialBills'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerName'] = $row[$folio]['CustomerName'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerAddress'] = $row[$folio]['CustomerAddress'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerTaxCode'] = $row[$folio]['CustomerTaxCode'];
                    $row[$folio]['FinancialBills'][$stt]['Content'] = 'VAT_HDV-BILLMICE'.$folio;
                    $row[$folio]['FinancialBills'][$stt]['TotalQuantity'] = 1;
                    $row[$folio]['FinancialBills'][$stt]['TotalForeignAmount'] = '';
                    $row[$folio]['FinancialBills'][$stt]['TotalAmount'] = $price*($percent/100)*$quantity;
                    $row[$folio]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
                    $row[$folio]['FinancialBills'][$stt]['TaxRate'] = $valuemassage['tax_rate'];
                    $row[$folio]['FinancialBills'][$stt]['TaxAmount'] = ($price*($percent/100)*$quantity) * ($valuemassage['tax_rate']/100);
                    $row[$folio]['FinancialBills'][$stt]['Description'] = 'VAT HDV-BILLMICE SPA HDV';
                    $row[$folio]['FinancialBills'][$stt]['DebitAccCode'] = 111;
                    $row[$folio]['FinancialBills'][$stt]['CreditAccCode'] = 511;
                    $row[$folio]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_HDV-BILLMICE_SPA'.$valuemassage['id'];
            }
        }
        return $row;
    }
    
    
    /** data **/
    function get_vend_hdv()
    {
        /**
         * Lay tat ca cac hoa don ban hang da checkout va khong tr ve phong
         *  + lay tat ca cac san pham trong ban da checkout 
         *  + Cac san pham co type !=GOODS,PRODUCT,DRINK 
        **/
        $ve_reservation_ids = '';
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
                                        ,ve_reservation.lastest_edited_user_id as user_id
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
                                    FROM
                                        ve_reservation_product
                                        inner join ve_reservation on ve_reservation.id=ve_reservation_product.bar_reservation_id
                                        inner join payment on payment.bill_id=ve_reservation.id AND payment.type='VEND'
                                        inner join product on product.id=ve_reservation_product.product_id
                                        left join unit on unit.id=product.unit_id
                                        left join customer on ve_reservation.customer_id=customer.id
                                    WHERE
                                        ve_reservation.pay_with_room = 0 
                                        AND ve_reservation.status='CHECKIN'
                                        AND ve_reservation.sync_cns_vt = 0
                                        AND ve_reservation.portal_id='".PORTAL_ID."'
                                        AND (product.type != 'GOODS' AND product.type != 'PRODUCT' AND product.type != 'DRINK')
                                        and (ve_reservation.mice_reservation_id is null OR ve_reservation.mice_reservation_id=0)
                                        and (ve_reservation.reservation_room_id is null OR ve_reservation.reservation_room_id=0)
                                        AND payment.time >= ".Date_Time::to_time(DATE_SYNC_CNS)."
                                    ");
        //System::debug($restaurant);
        foreach($vending as $keybar=>$valuebar)
        {
            /** Ngay phat sinh chung tu **/
            $in_date = date('d/m/Y',$valuebar['payment_time']);
            
            if(!isset($row['VEND'.$valuebar['bar_reservation_id']]))
            {
                /**
                 * khoi tao chung tu nha hang de day sang CNS
                **/
                $row['VEND'.$valuebar['bar_reservation_id']]['id'] = $valuebar['bar_reservation_id'];
                $row['VEND'.$valuebar['bar_reservation_id']]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row['VEND'.$valuebar['bar_reservation_id']]['TransTypeCode'] = 'HDV';
                $row['VEND'.$valuebar['bar_reservation_id']]['CreatedOn'] = date('Y-m-d');
                $row['VEND'.$valuebar['bar_reservation_id']]['ChangedOn'] = date('Y-m-d');
                $row['VEND'.$valuebar['bar_reservation_id']]['Code'] = 'HDV-VEND'.$valuebar['bar_reservation_id'];
                $create_date = explode('/',$in_date);
                $row['VEND'.$valuebar['bar_reservation_id']]['TransDate'] = $create_date[2].'-'.$create_date[1].'-'.$create_date[0];
                $row['VEND'.$valuebar['bar_reservation_id']]['Description'] = 'VEND_HDV';
                $row['VEND'.$valuebar['bar_reservation_id']]['TotalAmount'] = 0;
                $row['VEND'.$valuebar['bar_reservation_id']]['TotalTax'] = 0;
                if($valuebar['customer_id']!='')
                    $row['VEND'.$valuebar['bar_reservation_id']]['ContactCode'] = 'CUS_'.$valuebar['customer_id'];
                else
                    $row['VEND'.$valuebar['bar_reservation_id']]['ContactCode'] = 'KHACHLE_HOTEL';
                
                $row['VEND'.$valuebar['bar_reservation_id']]['ContactCode2'] = '';
                $row['VEND'.$valuebar['bar_reservation_id']]['payment'] = 1;
                
                $row['VEND'.$valuebar['bar_reservation_id']]['CustomerName'] = $valuebar['customer_name'];
                $row['VEND'.$valuebar['bar_reservation_id']]['CustomerAddress'] = $valuebar['customer_address'];
                $row['VEND'.$valuebar['bar_reservation_id']]['CustomerTaxCode'] = $valuebar['customer_tax_code'];
                $row['VEND'.$valuebar['bar_reservation_id']]['CurrencyCode'] = 'VND';
                $row['VEND'.$valuebar['bar_reservation_id']]['CurrencyRate'] = '';
                $row['VEND'.$valuebar['bar_reservation_id']]['IsReceivables'] = '';
                $row['VEND'.$valuebar['bar_reservation_id']]['AccTransTypeBIT'] = '';
                $row['VEND'.$valuebar['bar_reservation_id']]['ReferenceKey'] = 'HDV-VEND'.$valuebar['bar_reservation_id'];
                
                // detail
                $row['VEND'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'] = array();
                $row['VEND'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails']['length'] = 0;
                // vat 
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
            
            $row['VEND'.$valuebar['bar_reservation_id']]['TotalAmount'] += $price*$quantity;
            $row['VEND'.$valuebar['bar_reservation_id']]['TotalTax'] += ($price*$quantity) * ($valuebar['tax_rate']/100);
            
                $stt = $row['VEND'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails']['length'];
                $row['VEND'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['ParentReferenceKey'] = 'HDV-VEND'.$valuebar['bar_reservation_id'];
                $row['VEND'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                $row['VEND'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                $row['VEND'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'VEND_'.$valuebar['department_code'];
                $row['VEND'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['FeeItemCode'] = $valuebar['product_id'];
                $row['VEND'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['ContactCode'] = $row['VEND'.$valuebar['bar_reservation_id']]['ContactCode'];
                $row['VEND'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['ContactCode2'] = $row['VEND'.$valuebar['bar_reservation_id']]['ContactCode2'];
                $row['VEND'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['DebitAccCode'] = 111;
                $row['VEND'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['CreditAccCode'] = 511;
                $row['VEND'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['Description'] = 'DETAIL VEND HDV';
                $row['VEND'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['IsReceivables'] = '';
                $row['VEND'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['ReferenceKey'] = 'DETAIL_HDV-VEND'.$valuebar['id'];
                $row['VEND'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['Amount'] = $price*$quantity;
                $row['VEND'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails']['length']++;
                
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['AccReferenceKey'] = 'HDV-VEND'.$valuebar['bar_reservation_id'];
                
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CaseItemCode'] = 'VEND_'.$valuebar['department_code'];
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['BillNumber'] = 'VEND_HDV'.$valuebar['bar_reservation_id'];
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['BillDate'] = $row['VEND'.$valuebar['bar_reservation_id']]['TransDate'];
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['ContactCode'] = $row['VEND'.$valuebar['bar_reservation_id']]['ContactCode'];
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['ContactCode2'] = $row['VEND'.$valuebar['bar_reservation_id']]['ContactCode2'];
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CustomerName'] = $valuebar['customer_name'];
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CustomerAddress'] = $valuebar['customer_address'];
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CustomerTaxCode'] = $valuebar['customer_tax_code'];
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['Content'] = 'VAT_HDV-VEND'.$valuebar['bar_reservation_id'];
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['TotalQuantity'] = 1;
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['TotalForeignAmount'] = '';
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['TotalAmount'] = $price*$quantity;
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['TaxRate'] = $valuebar['tax_rate'];
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['TaxAmount'] = ($price*$quantity) * ($valuebar['tax_rate']/100);
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['Description'] = 'VAT_HDV-VEND'.$valuebar['bar_reservation_id'];
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['DebitAccCode'] = 111;
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CreditAccCode'] = 511;
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_HDV-VEND'.$valuebar['id'];
                $row['VEND'.$valuebar['bar_reservation_id']]['FinancialBills']['length'] ++;
        }
        return $row;
    }
    
    function get_bar_hdv()
    {
        /**
         * Lay tat ca cac hoa don nha hang da checkout va khong tr ve phong
         *  + lay tat ca cac san pham trong ban da checkout 
         *  + Cac san pham co type !=GOODS,PRODUCT,DRINK 
        **/
        
        $bar_reservation_ids = '';
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
                                        ,bar_reservation.lastest_edited_user_id as user_id
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
                                    FROM
                                        bar_reservation_product
                                        inner join bar_reservation on bar_reservation.id=bar_reservation_product.bar_reservation_id
                                        inner join payment on payment.bill_id=bar_reservation.id AND payment.type='BAR'
                                        inner join product on product.id=bar_reservation_product.product_id
                                        inner join product_category on product_category.id=product.category_id
                                        left join unit on unit.id=product.unit_id
                                        left join customer on bar_reservation.customer_id=customer.id
                                    WHERE
                                        bar_reservation.pay_with_room = 0 
                                        AND bar_reservation.package_id is null
                                        AND bar_reservation.status='CHECKOUT'
                                        AND bar_reservation.sync_cns_vt = 0
                                        AND bar_reservation.portal_id='".PORTAL_ID."'
                                        and (bar_reservation.mice_reservation_id is null OR bar_reservation.mice_reservation_id=0)
                                        and (bar_reservation.reservation_room_id is null OR bar_reservation.reservation_room_id=0)
                                        AND (product.type != 'GOODS' AND product.type != 'PRODUCT' AND product.type != 'DRINK')
                                        AND payment.time >= ".Date_Time::to_time(DATE_SYNC_CNS)."
                                    ");
        //System::debug($restaurant);
        foreach($restaurant as $keybar=>$valuebar)
        {
            /** Ngay phat sinh chung tu **/
            $in_date = date('d/m/Y',$valuebar['payment_time']);
            
            if(!isset($row['BAR'.$valuebar['bar_reservation_id']]))
            {
                /**
                 * khoi tao chung tu nha hang de day sang CNS
                **/
                $row['BAR'.$valuebar['bar_reservation_id']]['id'] = $valuebar['bar_reservation_id'];
                $row['BAR'.$valuebar['bar_reservation_id']]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row['BAR'.$valuebar['bar_reservation_id']]['TransTypeCode'] = 'HDV';
                $row['BAR'.$valuebar['bar_reservation_id']]['CreatedOn'] = date('Y-m-d');
                $row['BAR'.$valuebar['bar_reservation_id']]['ChangedOn'] = date('Y-m-d');
                $row['BAR'.$valuebar['bar_reservation_id']]['Code'] = 'HDV-BAR'.$valuebar['bar_reservation_id'];
                $create_date = explode('/',$in_date);
                $row['BAR'.$valuebar['bar_reservation_id']]['TransDate'] = $create_date[2].'-'.$create_date[1].'-'.$create_date[0];
                $row['BAR'.$valuebar['bar_reservation_id']]['Description'] = 'BAR_HDV';
                $row['BAR'.$valuebar['bar_reservation_id']]['TotalAmount'] = 0;
                $row['BAR'.$valuebar['bar_reservation_id']]['TotalTax'] = 0;
                if($valuebar['customer_id']!='')
                    $row['BAR'.$valuebar['bar_reservation_id']]['ContactCode'] = 'CUS_'.$valuebar['customer_id'];
                else
                    $row['BAR'.$valuebar['bar_reservation_id']]['ContactCode'] = 'KHACHLE_HOTEL';
                
                $row['BAR'.$valuebar['bar_reservation_id']]['ContactCode2'] = '';
                $row['BAR'.$valuebar['bar_reservation_id']]['payment'] = 1;
                
                $row['BAR'.$valuebar['bar_reservation_id']]['CustomerName'] = $valuebar['customer_name'];
                $row['BAR'.$valuebar['bar_reservation_id']]['CustomerAddress'] = $valuebar['customer_address'];
                $row['BAR'.$valuebar['bar_reservation_id']]['CustomerTaxCode'] = $valuebar['customer_tax_code'];
                $row['BAR'.$valuebar['bar_reservation_id']]['CurrencyCode'] = 'VND';
                $row['BAR'.$valuebar['bar_reservation_id']]['CurrencyRate'] = '';
                $row['BAR'.$valuebar['bar_reservation_id']]['IsReceivables'] = '';
                $row['BAR'.$valuebar['bar_reservation_id']]['AccTransTypeBIT'] = '';
                $row['BAR'.$valuebar['bar_reservation_id']]['ReferenceKey'] = 'HDV-BAR'.$valuebar['bar_reservation_id'];
                
                // detail
                $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'] = array();
                $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails']['length'] = 0;
                // vat 
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
            
            $row['BAR'.$valuebar['bar_reservation_id']]['TotalAmount'] += $price*$quantity;
            $row['BAR'.$valuebar['bar_reservation_id']]['TotalTax'] += ($price*$quantity) * ($valuebar['tax_rate']/100);
            
                $stt = $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails']['length'];
                $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['ParentReferenceKey'] = 'HDV-BAR'.$valuebar['bar_reservation_id'];
                $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = $valuebar['product_id'];
                
                if($valuebar['product_type']=='GOODS')
                    $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'HCB';
                if($valuebar['product_type']=='DRINK')
                    $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'DU';
                if($valuebar['product_type']=='PRODUCT')
                    $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'DA';
                if($valuebar['product_type']=='SERVICE')
                    $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'DVNH';
                
                $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['FeeItemCode'] = $valuebar['product_id'];
                $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['ContactCode'] = $row['BAR'.$valuebar['bar_reservation_id']]['ContactCode'];
                $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['ContactCode2'] = $row['BAR'.$valuebar['bar_reservation_id']]['ContactCode2'];
                $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['DebitAccCode'] = 111;
                $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['CreditAccCode'] = 511;
                $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['Description'] = 'DETAIL BAR HDV';
                $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['IsReceivables'] = '';
                $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['ReferenceKey'] = 'DETAIL_HDV-BAR'.$valuebar['id'];
                $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['Amount'] = $price*$quantity;
                $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails']['length']++;
                
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['AccReferenceKey'] = 'HDV-BAR'.$valuebar['bar_reservation_id'];
                
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CaseItemCode'] = $row['BAR'.$valuebar['bar_reservation_id']]['AccountingTransactionDetails'][$stt]['CaseItemCode'];
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['BillNumber'] = 'BAR_HDV'.$valuebar['bar_reservation_id'];
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['BillDate'] = $row['BAR'.$valuebar['bar_reservation_id']]['TransDate'];
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['ContactCode'] = $row['BAR'.$valuebar['bar_reservation_id']]['ContactCode'];
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['ContactCode2'] = $row['BAR'.$valuebar['bar_reservation_id']]['ContactCode2'];
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CustomerName'] = $valuebar['customer_name'];
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CustomerAddress'] = $valuebar['customer_address'];
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CustomerTaxCode'] = $valuebar['customer_tax_code'];
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['Content'] = 'VAT_HDV-BAR'.$valuebar['bar_reservation_id'];
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['TotalQuantity'] = 1;
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['TotalForeignAmount'] = '';
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['TotalAmount'] = $price*$quantity;
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['TaxRate'] = $valuebar['tax_rate'];
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['TaxAmount'] = ($price*$quantity) * ($valuebar['tax_rate']/100);
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['Description'] = 'VAT_HDV-BAR'.$valuebar['bar_reservation_id'];
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['DebitAccCode'] = 111;
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['CreditAccCode'] = 511;
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_HDV-BAR'.$valuebar['id'];
                $row['BAR'.$valuebar['bar_reservation_id']]['FinancialBills']['length']++;
        }
        return $row;
    }
    function get_spa_hdv()
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
                                FROM
                                    massage_product_consumed
                                    inner join massage_reservation_room on massage_reservation_room.id=massage_product_consumed.reservation_room_id
                                    
                                    left join massage_guest on massage_reservation_room.guest_id=massage_guest.id
                                    inner join payment on payment.bill_id=massage_reservation_room.id AND payment.type='SPA'
                                    inner join product on product.id=massage_product_consumed.product_id
                                    left join unit on unit.id=product.unit_id
                                WHERE
                                    massage_reservation_room.hotel_reservation_room_id is null
                                    AND massage_reservation_room.package_id is null
                                    AND massage_product_consumed.status = 'CHECKOUT'
                                    AND massage_reservation_room.sync_cns_vt = 0
                                    AND massage_reservation_room.portal_id='".PORTAL_ID."'
                                    AND (product.type != 'GOODS' AND product.type != 'PRODUCT' AND product.type != 'DRINK')
                                    AND payment.time >= ".Date_Time::to_time(DATE_SYNC_CNS)."
                                ");
        foreach($massage as $keymassage=>$valuemassage)
        {
            $in_date = date('d/m/Y',$valuemassage['payment_time']);
            
            if(!isset($row['SPA'.$valuemassage['massage_reservation_room_id']]))
            {
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['id'] = $valuemassage['massage_reservation_room_id'];
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['TransTypeCode'] = 'HDV';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['CreatedOn'] = date('Y-m-d');
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['ChangedOn'] = date('Y-m-d');
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['Code'] = 'HDV-SPA'.$valuemassage['massage_reservation_room_id'];
                $create_date = explode('/',$in_date);
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['TransDate'] = $create_date[2].'-'.$create_date[1].'-'.$create_date[0];
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['Description'] = 'SPA_HDV';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['TotalAmount'] = 0;
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['TotalTax'] = 0;
                
                if($valuemassage['customer_id']!='')
                    $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode'] = 'CUS_'.$valuemassage['customer_id'];
                if($valuemassage['massage_guest_code']!='')
                    $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode'] = 'SPA_'.$valuemassage['massage_guest_code'];
                else
                    $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode'] = 'KHACHLE_HOTEL';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode2'] = '';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['payment'] = 1;
            
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['CustomerName'] = '';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['CustomerAddress'] ='';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['CustomerTaxCode'] = '';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['CurrencyCode'] = 'VND';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['CurrencyRate'] = '';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['IsReceivables'] = '';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['AccTransTypeBIT'] = '';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['ReferenceKey'] = 'HDV-SPA'.$valuemassage['massage_reservation_room_id'];
                
                // detail
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['AccountingTransactionDetails'] = array();
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['AccountingTransactionDetails']['length'] = 0;
                
                // vat 
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
                        } 
                        catch (HttpException $ex) 
                        {
                        }
                    }
                }
                
            }
            $quantity = $valuemassage['quantity'];
            $discount_rate = $valuemassage['discount'];
            $price = $valuemassage['price'];
            if($valuemassage['net_price']==1)
            {
                $price = $valuemassage['price'] / ((1+($valuemassage['service_rate']/100))*(1+($valuemassage['tax_rate']/100)));
            }
            
            $price = $price -(($discount_rate*$price)/100);
            $price = $price + ($price*($valuemassage['service_rate']/100));
            
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['TotalAmount'] += $price*$quantity;
            $row['SPA'.$valuemassage['massage_reservation_room_id']]['TotalTax'] += ($price*$quantity) * ($valuemassage['tax_rate']/100);
                $stt = $row['SPA'.$valuemassage['massage_reservation_room_id']]['AccountingTransactionDetails']['length'];
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['AccountingTransactionDetails'][$stt]['ParentReferenceKey'] = 'HDV-SPA'.$valuemassage['massage_reservation_room_id'];
                
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['AccountingTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['AccountingTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'SPA';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['AccountingTransactionDetails'][$stt]['FeeItemCode'] = $valuemassage['product_id'];
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['AccountingTransactionDetails'][$stt]['ContactCode'] = $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode'];
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['AccountingTransactionDetails'][$stt]['ContactCode2'] = $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode2'];
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['AccountingTransactionDetails'][$stt]['DebitAccCode'] = 111;
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['AccountingTransactionDetails'][$stt]['CreditAccCode'] = 511;
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['AccountingTransactionDetails'][$stt]['Description'] = 'DETAIL SPA HDV';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['AccountingTransactionDetails'][$stt]['IsReceivables'] = '';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['AccountingTransactionDetails'][$stt]['ReferenceKey'] = 'DETAIL_HDV-SPA'.$valuemassage['id'];
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['AccountingTransactionDetails'][$stt]['Amount'] = $price*$quantity;
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['AccountingTransactionDetails']['length']++;
                
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['AccReferenceKey'] = 'HDV-SPA'.$valuemassage['massage_reservation_room_id'];
                
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['CaseItemCode'] = 'SPA';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['BillNumber'] = 'SPA_HDV'.$valuemassage['massage_reservation_room_id'];
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['BillDate'] = $row['SPA'.$valuemassage['massage_reservation_room_id']]['TransDate'];
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['ContactCode'] = $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode'];
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['ContactCode2'] = $row['SPA'.$valuemassage['massage_reservation_room_id']]['ContactCode2'];
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['CustomerName'] = '';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['CustomerAddress'] = '';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['CustomerTaxCode'] = '';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['Content'] = 'VAT_HDV-SPA'.$valuemassage['massage_reservation_room_id'];
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['TotalQuantity'] = 1;
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['TotalForeignAmount'] = '';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['TotalAmount'] = $price*$quantity;
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['TaxRate'] = $valuemassage['tax_rate'];
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['TaxAmount'] = ($price*$quantity) * ($valuemassage['tax_rate']/100);
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['Description'] = 'VAT_HDV-SPA'.$valuemassage['massage_reservation_room_id'];
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['DebitAccCode'] = 111;
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['CreditAccCode'] = 511;
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_HDV-SPA'.$valuemassage['id'];
                $row['SPA'.$valuemassage['massage_reservation_room_id']]['FinancialBills']['length']++;
        }
        return $row;
    }
    function get_folio_hdv()
    {
        /**
         * lay tat ca hoa don folio cua nhom va phong da checkout theo tung PORTAL
         * khoi tao mang main ban dau
         * lay cac chi tiet folio co type = minibar, package, bar, massage, karaoke
         * gan cac so hoa don vao mot mang
         * di kem voi mang la dieu kien lay hoa don
         * do package lai bao gom cac hoa don con cua SPA v� BAR
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
                                                folio.sync_cns_vt = 0
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
                                        ,folio.user_id
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
                                        left JOIN customer on reservation.customer_id=customer.id
                                        left JOIN payment on folio.id=payment.folio_id
                                        left join reservation_traveller on folio.reservation_traveller_id=reservation_traveller.id
                                        left join traveller on traveller.id=reservation_traveller.traveller_id
                                        left join extra_service_invoice_detail on extra_service_invoice_detail.id=traveller_folio.invoice_id AND traveller_folio.type='EXTRA_SERVICE'
                                        left join extra_service on extra_service.id=extra_service_invoice_detail.service_id
                                    WHERE
                                        (".$cond_folio.")
                                        AND folio.sync_cns_vt = 0
                                        AND reservation.portal_id='".PORTAL_ID."'
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
        $ListTicket = array();
        $CondTicket = '';
        
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
                 * ma chung tu HDV
                 * */
                $row[$value['folio_id']]['id'] = $value['folio_id'];
                $row[$value['folio_id']]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row[$value['folio_id']]['TransTypeCode'] = 'HDV';
                
                $row[$value['folio_id']]['CreatedOn'] = date('Y-m-d');
                $row[$value['folio_id']]['ChangedOn'] = date('Y-m-d');
                
                $row[$value['folio_id']]['Code'] = 'HDV'.$value['folio_id'];
                $create_date = explode('/',$in_date);
                $row[$value['folio_id']]['TransDate'] = $create_date[2].'-'.$create_date[1].'-'.$create_date[0];
                $row[$value['folio_id']]['Description'] = 'FOLIO_HDV';
                $row[$value['folio_id']]['TotalAmount'] = 0;
                $row[$value['folio_id']]['TotalTax'] = 0;
                $row[$value['folio_id']]['ContactCode'] = 'KHACHLE_HOTEL';
                if($value['customer_id']!='')
                    $row[$value['folio_id']]['ContactCode'] = 'CUS_'.$value['customer_id'];
                elseif($value['traveller_id']!='')
                    $row[$value['folio_id']]['ContactCode'] = 'TRA_'.$value['traveller_id'];
                    
                
                $row[$value['folio_id']]['ContactCode2'] = '';
                $row[$value['folio_id']]['payment'] = 1;
                
                $row[$value['folio_id']]['CustomerName'] = $value['customer_name']==''?$value['traveller_name']:$value['customer_name'];
                $row[$value['folio_id']]['CustomerAddress'] = $value['customer_name']==''?$value['traveller_address']:$value['customer_address'];
                $row[$value['folio_id']]['CustomerTaxCode'] = $value['customer_name']==''?'':$value['customer_tax_code'];
                $row[$value['folio_id']]['CurrencyCode'] = 'VND';
                $row[$value['folio_id']]['CurrencyRate'] = '';
                $row[$value['folio_id']]['IsReceivables'] = '';
                $row[$value['folio_id']]['AccTransTypeBIT'] = '';
                $row[$value['folio_id']]['ReferenceKey'] = 'HDV'.$value['folio_id'];
                
                // detail
                $row[$value['folio_id']]['AccountingTransactionDetails'] = array();
                $row[$value['folio_id']]['AccountingTransactionDetails']['length'] = 0;
                
                // vat 
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
                        } 
                        catch (HttpException $ex) 
                        {
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
            elseif($value['type']=='TICKET')
            {
                $ListTicket[$value['invoice_id']]['id'] = $value['invoice_id'];
                $ListTicket[$value['invoice_id']]['percent'] = $value['percent'];
                $ListTicket[$value['invoice_id']]['folio_id'] = $value['folio_id'];
                if($CondTicket=='')
                    $CondTicket = 'ticket_reservation.id='.$value['invoice_id'];
                else
                    $CondTicket .= ' OR ticket_reservation.id='.$value['invoice_id'];
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
            elseif($value['type']=='DISCOUNT')
            {
                $row[$value['folio_id']]['TotalAmount'] -= $value['amount'];
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
            else
            {
                $amount = $value['amount'] + ($value['amount']*($value['service_rate']/100));
                $amount = $amount;
                $tax = $amount * ($value['tax_rate']/100);
                $row[$value['folio_id']]['TotalAmount'] += $amount;
                $row[$value['folio_id']]['TotalTax'] += $tax;
                
                $stt = $row[$value['folio_id']]['AccountingTransactionDetails']['length'];
                
                $row[$value['folio_id']]['AccountingTransactionDetails'][$stt]['ParentReferenceKey'] = 'HDV'.$value['folio_id'];
                
                $row[$value['folio_id']]['AccountingTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                $row[$value['folio_id']]['AccountingTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                
                if($value['type']=='ROOM' or ($value['type']=='EXTRA_SERVICE' and ($value['extra_service_code']=='EXTRA_BED' or $value['extra_service_code']=='EARLY_CHECKIN' or $value['extra_service_code']=='LATE_CHECKOUT' or $value['extra_service_code']=='LATE_CHECKIN' or $value['extra_service_code']=='EXTRA_PERSON')))
                    $row[$value['folio_id']]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'ROOM';
                elseif($value['type']=='LAUNDRY')
                    $row[$value['folio_id']]['AccountingTransactionDetails'][$stt]['CaseItemCode']  = 'LAUNDRY';
                elseif($value['type']=='EQUIPMENT')
                    $row[$value['folio_id']]['AccountingTransactionDetails'][$stt]['CaseItemCode']  = 'EQUIPMENT';
                else
                    $row[$value['folio_id']]['AccountingTransactionDetails'][$stt]['CaseItemCode']  = 'OTHER_SERVICE';
                $row[$value['folio_id']]['AccountingTransactionDetails'][$stt]['FeeItemCode'] = $value['type'];
                $row[$value['folio_id']]['AccountingTransactionDetails'][$stt]['ContactCode'] = $row[$value['folio_id']]['ContactCode'];
                $row[$value['folio_id']]['AccountingTransactionDetails'][$stt]['ContactCode2'] = $row[$value['folio_id']]['ContactCode2'];
                $row[$value['folio_id']]['AccountingTransactionDetails'][$stt]['DebitAccCode'] = 111;
                $row[$value['folio_id']]['AccountingTransactionDetails'][$stt]['CreditAccCode'] = 511;
                $row[$value['folio_id']]['AccountingTransactionDetails'][$stt]['Description'] = 'DETAIL FOLIO HDV';
                $row[$value['folio_id']]['AccountingTransactionDetails'][$stt]['IsReceivables'] = '';
                $row[$value['folio_id']]['AccountingTransactionDetails'][$stt]['ReferenceKey'] = 'FOLIO_DETAIL_HDV'.$value['id'];
                $row[$value['folio_id']]['AccountingTransactionDetails'][$stt]['Amount'] = $amount;
                $row[$value['folio_id']]['AccountingTransactionDetails']['length']++;
                
                $row[$value['folio_id']]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                $row[$value['folio_id']]['FinancialBills'][$stt]['AccReferenceKey'] = 'HDV'.$value['folio_id'];
                $row[$value['folio_id']]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
                $row[$value['folio_id']]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
                
                $row[$value['folio_id']]['FinancialBills'][$stt]['CaseItemCode'] = $row[$value['folio_id']]['AccountingTransactionDetails'][$stt]['CaseItemCode'];
                $row[$value['folio_id']]['FinancialBills'][$stt]['BillNumber'] = 'BIL_FOLIO_HDV'.$value['folio_id'];
                $row[$value['folio_id']]['FinancialBills'][$stt]['BillDate'] = $row[$value['folio_id']]['TransDate'];
                $row[$value['folio_id']]['FinancialBills'][$stt]['ContactCode'] = $row[$value['folio_id']]['ContactCode'];
                $row[$value['folio_id']]['FinancialBills'][$stt]['ContactCode2'] = $row[$value['folio_id']]['ContactCode2'];
                $row[$value['folio_id']]['FinancialBills'][$stt]['CustomerName'] = $row[$value['folio_id']]['CustomerName'];
                $row[$value['folio_id']]['FinancialBills'][$stt]['CustomerAddress'] = $row[$value['folio_id']]['CustomerAddress'];
                $row[$value['folio_id']]['FinancialBills'][$stt]['CustomerTaxCode'] = $row[$value['folio_id']]['CustomerTaxCode'];
                $row[$value['folio_id']]['FinancialBills'][$stt]['Content'] = 'VAT_HDV'.$value['folio_id'];
                $row[$value['folio_id']]['FinancialBills'][$stt]['TotalQuantity'] = 1;
                $row[$value['folio_id']]['FinancialBills'][$stt]['TotalForeignAmount'] = '';
                $row[$value['folio_id']]['FinancialBills'][$stt]['TotalAmount'] = $amount;
                $row[$value['folio_id']]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
                $row[$value['folio_id']]['FinancialBills'][$stt]['TaxRate'] = $value['tax_rate'];
                $row[$value['folio_id']]['FinancialBills'][$stt]['TaxAmount'] = $tax;
                $row[$value['folio_id']]['FinancialBills'][$stt]['Description'] = 'VAT FOLIO DETAIL HDV';
                $row[$value['folio_id']]['FinancialBills'][$stt]['DebitAccCode'] = 111;
                $row[$value['folio_id']]['FinancialBills'][$stt]['CreditAccCode'] = 511;
                $row[$value['folio_id']]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_FOLIO_HDV_'.$value['type'].$value['id'];
                $row[$value['folio_id']]['FinancialBills']['length']++;
            }
        }
        
        /** get list package detail **/
        if($CondPackage!='')
        {
            $package = DB::fetch_all("
                    SELECT 
                        package_sale_detail.id || '_' || reservation_room.id as id
                        ,package_sale_detail.quantity
                        ,package_sale_detail.price
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
                        --AND (department.code='RES' OR department.code='SPA')
                    ");
            //System::debug($package);
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
                else
                {
                    $folio_id = $ListPackage[$valuepackage['reservation_room_id']]['folio_id'];
                    $percent = $ListPackage[$valuepackage['reservation_room_id']]['percent'];
                    
                    $stt = $row[$folio_id]['AccountingTransactionDetails']['length'];
                    
                    $row[$folio_id]['TotalAmount'] += $valuepackage['quantity']*$valuepackage['price'];
                    
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['ParentReferenceKey'] = 'HDV'.$folio_id;
                    
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                    
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'ROOM';
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['FeeItemCode'] = 'ROOM';
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['ContactCode'] = $row[$folio_id]['ContactCode'];
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['ContactCode2'] = $row[$folio_id]['ContactCode2'];
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['DebitAccCode'] = 111;
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['CreditAccCode'] = 511;
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['Description'] = 'DETAIL FOLIO PACKAGE HDV';
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['IsReceivables'] = '';
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['ReferenceKey'] = 'FOLIO_DETAIL_HDV_PACKAGE'.$valuepackage['id'];
                    $row[$folio_id]['AccountingTransactionDetails'][$stt]['Amount'] = $valuepackage['quantity']*$valuepackage['price'];
                    $row[$folio_id]['AccountingTransactionDetails']['length']++;
                    
                    //$row[$folio_id]['FinancialBills'][0]['TotalAmount'] += $valuepackage['quantity']*$valuepackage['price'];
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
                                FROM
                                    housekeeping_invoice_detail
                                    inner join housekeeping_invoice on housekeeping_invoice.id=housekeeping_invoice_detail.invoice_id
                                    inner join product on product.id=housekeeping_invoice_detail.product_id
                                    left join unit on unit.id=product.unit_id
                                WHERE
                                    (".$CondMinibar.") AND (product.type != 'GOODS' AND product.type != 'PRODUCT' AND product.type != 'DRINK')
                                ");
                                
        foreach($minibar as $keyminibar=>$valueminibar)
        {
            $folio = $ListMinibar[$valueminibar['housekeeping_invoice_id']]['folio_id'];
            $percent = $ListMinibar[$valueminibar['housekeeping_invoice_id']]['percent'];
            if($valueminibar['net_price']==1)
            {
                $valueminibar['price'] = $valueminibar['price'] / ((1+($valueminibar['service_rate']/100))*(1+($valueminibar['tax_rate']/100)));
            }
            $price = $valueminibar['price'] + ($valueminibar['price']*($valueminibar['service_rate']/100));
            
            $row[$folio]['TotalAmount'] += $price*($percent/100)*$valueminibar['quantity'];
            $row[$folio]['TotalTax'] += ($price*($percent/100)*$valueminibar['quantity']) * ($valueminibar['tax_rate']/100);
                $stt = $row[$folio]['AccountingTransactionDetails']['length'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ParentReferenceKey'] = 'HDV'.$folio;
                    
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                    
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'MINIBAR';
                    $row[$folio]['AccountingTransactionDetails'][$stt]['FeeItemCode'] = $valueminibar['product_id'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['DebitAccCode'] = 111;
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CreditAccCode'] = 511;
                    $row[$folio]['AccountingTransactionDetails'][$stt]['Description'] = 'DETAIL FOLIO MINIBAR HDV';
                    $row[$folio]['AccountingTransactionDetails'][$stt]['IsReceivables'] = '';
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ReferenceKey'] = 'FOLIO_DETAIL_HDV_MINIBAR'.$valueminibar['id'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['Amount'] = $price*($percent/100)*$valueminibar['quantity'];
                    $row[$folio]['AccountingTransactionDetails']['length']++;
                    
                    $row[$folio]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                    $row[$folio]['FinancialBills'][$stt]['AccReferenceKey'] = 'HDV'.$folio;
                    $row[$folio]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
                    $row[$folio]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
                    
                    $row[$folio]['FinancialBills'][$stt]['CaseItemCode'] = $row[$folio]['AccountingTransactionDetails'][$stt]['CaseItemCode'];
                    $row[$folio]['FinancialBills'][$stt]['BillNumber'] = 'BIL_FOLIO_HDV'.$folio;
                    $row[$folio]['FinancialBills'][$stt]['BillDate'] = $row[$folio]['TransDate'];
                    $row[$folio]['FinancialBills'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                    $row[$folio]['FinancialBills'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerName'] = $row[$folio]['CustomerName'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerAddress'] = $row[$folio]['CustomerAddress'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerTaxCode'] = $row[$folio]['CustomerTaxCode'];
                    $row[$folio]['FinancialBills'][$stt]['Content'] = 'VAT_HDV'.$folio;
                    $row[$folio]['FinancialBills'][$stt]['TotalQuantity'] = 1;
                    $row[$folio]['FinancialBills'][$stt]['TotalForeignAmount'] = '';
                    $row[$folio]['FinancialBills'][$stt]['TotalAmount'] = $price*($percent/100)*$valueminibar['quantity'];
                    $row[$folio]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
                    $row[$folio]['FinancialBills'][$stt]['TaxRate'] = $valueminibar['tax_rate'];
                    $row[$folio]['FinancialBills'][$stt]['TaxAmount'] = ($price*($percent/100)*$valueminibar['quantity']) * ($valueminibar['tax_rate']/100);
                    $row[$folio]['FinancialBills'][$stt]['Description'] = 'VAT FOLIO DETAIL HDV';
                    $row[$folio]['FinancialBills'][$stt]['DebitAccCode'] = 111;
                    $row[$folio]['FinancialBills'][$stt]['CreditAccCode'] = 511;
                    $row[$folio]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_FOLIO_HDV_MINIBAR'.$valueminibar['id'];
                    $row[$folio]['FinancialBills']['length']++;
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
                                        FROM
                                            bar_reservation_product
                                            inner join bar_reservation on bar_reservation.id=bar_reservation_product.bar_reservation_id
                                            inner join product on product.id=bar_reservation_product.product_id
                                            inner join product_category on product_category.id=product.category_id
                                            left join unit on unit.id=product.unit_id
                                        WHERE
                                            (".$CondBar.") AND (product.type != 'GOODS' AND product.type != 'PRODUCT' AND product.type != 'DRINK')
                                        ");
            
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
                
                
                $row[$folio]['TotalAmount'] += $price*($percent/100)*$quantity;
                $row[$folio]['TotalTax'] += ($price*($percent/100)*$quantity) * ($valuebar['tax_rate']/100);
                $stt = $row[$folio]['AccountingTransactionDetails']['length'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ParentReferenceKey'] = 'HDV'.$folio;
                    
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                    
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = $valuebar['product_id'];
                    
                    if($valuebar['product_type']=='GOODS')
                        $row[$folio]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'HCB';
                    if($valuebar['product_type']=='DRINK')
                        $row[$folio]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'DU';
                    if($valuebar['product_type']=='PRODUCT')
                        $row[$folio]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'DA';
                    if($valuebar['product_type']=='SERVICE')
                        $row[$folio]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'DVNH';
                    
                    $row[$folio]['AccountingTransactionDetails'][$stt]['FeeItemCode'] = $valuebar['product_id'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['DebitAccCode'] = 111;
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CreditAccCode'] = 511;
                    $row[$folio]['AccountingTransactionDetails'][$stt]['Description'] = 'DETAIL FOLIO BAR HDV';
                    $row[$folio]['AccountingTransactionDetails'][$stt]['IsReceivables'] = '';
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ReferenceKey'] = 'FOLIO_DETAIL_HDV_BAR'.$valuebar['id'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['Amount'] = $price*($percent/100)*$quantity;
                    $row[$folio]['AccountingTransactionDetails']['length']++;
                    
                    $row[$folio]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                    $row[$folio]['FinancialBills'][$stt]['AccReferenceKey'] = 'HDV'.$folio;
                    $row[$folio]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
                    $row[$folio]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
                    
                    $row[$folio]['FinancialBills'][$stt]['CaseItemCode'] = $row[$folio]['AccountingTransactionDetails'][$stt]['CaseItemCode'];
                    $row[$folio]['FinancialBills'][$stt]['BillNumber'] = 'BIL_FOLIO_HDV'.$folio;
                    $row[$folio]['FinancialBills'][$stt]['BillDate'] = $row[$folio]['TransDate'];
                    $row[$folio]['FinancialBills'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                    $row[$folio]['FinancialBills'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerName'] = $row[$folio]['CustomerName'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerAddress'] = $row[$folio]['CustomerAddress'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerTaxCode'] = $row[$folio]['CustomerTaxCode'];
                    $row[$folio]['FinancialBills'][$stt]['Content'] = 'VAT_HDV'.$folio;
                    $row[$folio]['FinancialBills'][$stt]['TotalQuantity'] = 1;
                    $row[$folio]['FinancialBills'][$stt]['TotalForeignAmount'] = '';
                    $row[$folio]['FinancialBills'][$stt]['TotalAmount'] = $price*($percent/100)*$quantity;
                    $row[$folio]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
                    $row[$folio]['FinancialBills'][$stt]['TaxRate'] = $valuebar['tax_rate'];
                    $row[$folio]['FinancialBills'][$stt]['TaxAmount'] = ($price*($percent/100)*$quantity) * ($valuebar['tax_rate']/100);
                    $row[$folio]['FinancialBills'][$stt]['Description'] = 'VAT FOLIO DETAIL HDV';
                    $row[$folio]['FinancialBills'][$stt]['DebitAccCode'] = 111;
                    $row[$folio]['FinancialBills'][$stt]['CreditAccCode'] = 511;
                    $row[$folio]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_FOLIO_HDV_BAR'.$valuebar['id'];
                    $row[$folio]['FinancialBills']['length']++;
            }
        }
        
        if($CondVe!='')
        {
            /** get ve **/
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
                                        FROM
                                            ve_reservation_product
                                            inner join ve_reservation on ve_reservation.id=ve_reservation_product.bar_reservation_id
                                            inner join product on product.id=ve_reservation_product.product_id
                                            left join unit on unit.id=product.unit_id
                                        WHERE
                                            (".$CondVe.") AND (product.type != 'GOODS' AND product.type != 'PRODUCT' AND product.type != 'DRINK')
                                        ");
            //System::debug($restaurant);
            foreach($vending as $keybar=>$valuebar)
            {
                $folio = $ListVe[$valuebar['bar_reservation_id']]['folio_id'];
                $percent = $ListVe[$valuebar['bar_reservation_id']]['percent'];
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
                
                
                $row[$folio]['TotalAmount'] += $price*($percent/100)*$quantity;
                $row[$folio]['TotalTax'] += ($price*($percent/100)*$quantity) * ($valuebar['tax_rate']/100);
                $stt = $row[$folio]['AccountingTransactionDetails']['length'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ParentReferenceKey'] = 'HDV'.$folio;
                    
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                    
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'VEND_'.$valuebar['department_code'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['FeeItemCode'] = $valuebar['product_id'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['DebitAccCode'] = 111;
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CreditAccCode'] = 511;
                    $row[$folio]['AccountingTransactionDetails'][$stt]['Description'] = 'DETAIL FOLIO VEND HDV';
                    $row[$folio]['AccountingTransactionDetails'][$stt]['IsReceivables'] = '';
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ReferenceKey'] = 'FOLIO_DETAIL_HDV_VEND'.$valuebar['id'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['Amount'] = $price*($percent/100)*$quantity;
                    $row[$folio]['AccountingTransactionDetails']['length']++;
                    
                    $row[$folio]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                    $row[$folio]['FinancialBills'][$stt]['AccReferenceKey'] = 'HDV'.$folio;
                    $row[$folio]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
                    $row[$folio]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
                    
                    $row[$folio]['FinancialBills'][$stt]['CaseItemCode'] = 'VEND_'.$valuebar['department_code'];
                    $row[$folio]['FinancialBills'][$stt]['BillNumber'] = 'BIL_FOLIO_HDV'.$folio;
                    $row[$folio]['FinancialBills'][$stt]['BillDate'] = $row[$folio]['TransDate'];
                    $row[$folio]['FinancialBills'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                    $row[$folio]['FinancialBills'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerName'] = $row[$folio]['CustomerName'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerAddress'] = $row[$folio]['CustomerAddress'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerTaxCode'] = $row[$folio]['CustomerTaxCode'];
                    $row[$folio]['FinancialBills'][$stt]['Content'] = 'VAT_HDV'.$folio;
                    $row[$folio]['FinancialBills'][$stt]['TotalQuantity'] = 1;
                    $row[$folio]['FinancialBills'][$stt]['TotalForeignAmount'] = '';
                    $row[$folio]['FinancialBills'][$stt]['TotalAmount'] = $price*($percent/100)*$quantity;
                    $row[$folio]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
                    $row[$folio]['FinancialBills'][$stt]['TaxRate'] = $valuebar['tax_rate'];
                    $row[$folio]['FinancialBills'][$stt]['TaxAmount'] = ($price*($percent/100)*$quantity) * ($valuebar['tax_rate']/100);
                    $row[$folio]['FinancialBills'][$stt]['Description'] = 'VAT FOLIO DETAIL HDV';
                    $row[$folio]['FinancialBills'][$stt]['DebitAccCode'] = 111;
                    $row[$folio]['FinancialBills'][$stt]['CreditAccCode'] = 511;
                    $row[$folio]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_FOLIO_HDV_VEND'.$valuebar['id'];
                    $row[$folio]['FinancialBills']['length']++;
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
                                    FROM
                                        massage_product_consumed
                                        inner join massage_reservation_room on massage_reservation_room.id=massage_product_consumed.reservation_room_id
                                        inner join product on product.id=massage_product_consumed.product_id
                                        left join unit on unit.id=product.unit_id
                                    WHERE
                                        (".$CondMassage.") AND (product.type != 'GOODS' AND product.type != 'PRODUCT' AND product.type != 'DRINK')
                                    ");
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
                
                
                $quantity = $valuemassage['quantity'];
                $discount_rate = $valuemassage['discount'];
                $price = $valuemassage['price'];
                if($valuemassage['net_price']==1)
                {
                    $price = $valuemassage['price'] / ((1+($valuemassage['service_rate']/100))*(1+($valuemassage['tax_rate']/100)));
                }
                
                $price = $price -(($discount_rate*$price)/100);
                $price = $price + ($price*($valuemassage['service_rate']/100));
                
                $row[$folio]['TotalAmount'] += $price*($percent/100)*$quantity;
                $row[$folio]['TotalTax'] += ($price*($percent/100)*$quantity) * ($valuemassage['tax_rate']/100);
                $stt = $row[$folio]['AccountingTransactionDetails']['length'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ParentReferenceKey'] = 'HDV'.$folio;
                    
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CreatedOn'] = date('Y-m-d');
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ChangedOn'] = date('Y-m-d');
                    
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CaseItemCode'] = 'SPA';
                    $row[$folio]['AccountingTransactionDetails'][$stt]['FeeItemCode'] = $valuemassage['product_id'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['DebitAccCode'] = 111;
                    $row[$folio]['AccountingTransactionDetails'][$stt]['CreditAccCode'] = 511;
                    $row[$folio]['AccountingTransactionDetails'][$stt]['Description'] = 'DETAIL FOLIO SPA HDV';
                    $row[$folio]['AccountingTransactionDetails'][$stt]['IsReceivables'] = '';
                    $row[$folio]['AccountingTransactionDetails'][$stt]['ReferenceKey'] = 'FOLIO_DETAIL_HDV_SPA'.$valuemassage['id'];
                    $row[$folio]['AccountingTransactionDetails'][$stt]['Amount'] = $price*($percent/100)*$quantity;
                    $row[$folio]['AccountingTransactionDetails']['length']++;
                    
                    $row[$folio]['FinancialBills'][$stt]['BranchCode'] = BRANCH_CODE_SYNC_CNS;
                    $row[$folio]['FinancialBills'][$stt]['AccReferenceKey'] = 'HDV'.$folio;
                    $row[$folio]['FinancialBills'][$stt]['CreatedOn'] = date('Y-m-d');
                    $row[$folio]['FinancialBills'][$stt]['ChangedOn'] = date('Y-m-d');
                    
                    $row[$folio]['FinancialBills'][$stt]['CaseItemCode'] = 'SPA';
                    $row[$folio]['FinancialBills'][$stt]['BillNumber'] = 'BIL_FOLIO_HDV'.$folio;
                    $row[$folio]['FinancialBills'][$stt]['BillDate'] = $row[$folio]['TransDate'];
                    $row[$folio]['FinancialBills'][$stt]['ContactCode'] = $row[$folio]['ContactCode'];
                    $row[$folio]['FinancialBills'][$stt]['ContactCode2'] = $row[$folio]['ContactCode2'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerName'] = $row[$folio]['CustomerName'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerAddress'] = $row[$folio]['CustomerAddress'];
                    $row[$folio]['FinancialBills'][$stt]['CustomerTaxCode'] = $row[$folio]['CustomerTaxCode'];
                    $row[$folio]['FinancialBills'][$stt]['Content'] = 'VAT_HDV'.$folio;
                    $row[$folio]['FinancialBills'][$stt]['TotalQuantity'] = 1;
                    $row[$folio]['FinancialBills'][$stt]['TotalForeignAmount'] = '';
                    $row[$folio]['FinancialBills'][$stt]['TotalAmount'] = $price*($percent/100)*$quantity;
                    $row[$folio]['FinancialBills'][$stt]['VatTypeCode'] = 'BR04';
                    $row[$folio]['FinancialBills'][$stt]['TaxRate'] = $valuemassage['tax_rate'];
                    $row[$folio]['FinancialBills'][$stt]['TaxAmount'] = ($price*($percent/100)*$quantity) * ($valuemassage['tax_rate']/100);
                    $row[$folio]['FinancialBills'][$stt]['Description'] = 'VAT FOLIO DETAIL HDV';
                    $row[$folio]['FinancialBills'][$stt]['DebitAccCode'] = 111;
                    $row[$folio]['FinancialBills'][$stt]['CreditAccCode'] = 511;
                    $row[$folio]['FinancialBills'][$stt]['ReferenceKey'] = 'VAT_FOLIO_HDV_SPA'.$valuemassage['id'];
                    $row[$folio]['FinancialBills']['length']++;
            }
        }
        return $row;
    }
?>