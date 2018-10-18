<?php 
    function sync_payment_cnt()
    {
        set_time_limit(-1);
        // REC
        $sql = "
                select 
                    payment.id,
                    payment.bill_id,
                    payment.time,
                    payment.amount,
                    payment.exchange_rate,
                    payment.amount as amount_vnd,
                    payment.payment_type_id,
                    payment.currency_id,
                    payment.user_id,
                    payment.description,
                    payment.type,
                    folio.id as folio_id,
                    reservation.customer_id,
                    traveller.id as traveller_id
                from 
                    payment
                    inner join folio on folio.id = payment.folio_id
                    inner join reservation on reservation.id = folio.reservation_id
                    left join reservation_traveller on reservation_traveller.id = folio.reservation_traveller_id
                    left join traveller on traveller.id = reservation_traveller.traveller_id
                where 
                    payment.sync_cns=0
                    AND (payment.payment_type_id = 'CREDIT_CARD' OR payment.payment_type_id = 'BANK')
                    AND payment.time >= ".Date_Time::to_time(DATE_SYNC_CNS)." 
                    AND payment.portal_id='".PORTAL_ID."'";
        $payment = DB::fetch_all($sql);
        foreach($payment as $key=>$value)
        {
            $contact_code_2 = 'KHACHLE_HOTEL';
            if($value['customer_id']!='')
                $contact_code_2 = 'CUS_'.$value['customer_id'];
            elseif($value['traveller_id']!='' and $value['traveller_id']!=0)
                $contact_code_2 = 'TRA_'.$value['traveller_id'];
            
            $row = array();
            $row['BranchCode'] = BRANCH_CODE_SYNC_CNS;
            $row['TransTypeCode'] = 'CNT';
            $row['Code'] = $value['bill_id'].'_'.$value['id'];
            $row['CreatedOn'] = date('Y-m-d');
            $row['ChangedOn'] = date('Y-m-d');
            $row['TransDate'] = date('Y-m-d',$value['time']);
            $row['Description'] = $value['type'].'_'.$value['folio_id'].'_'.$value['description'];
            
            $row['TotalAmount'] = $value['amount_vnd']*$value['exchange_rate'];
            if($value['currency_id']!='VND')
                $row['TotalForeignAmount'] = $value['amount_vnd'];
            $row['TotalTax'] = 0;
            //$row['ContactCode2'] = 'ACC_'.$value['user_id'];
            $row['ContactCode'] = $contact_code_2;
            $row['CustomerName'] = '';
            $row['CustomerAddress'] = '';
            $row['CustomerTaxCode'] = '';
            
            $row['CurrencyCode'] = $value['currency_id'];
            $row['CurrencyRate'] = $value['exchange_rate'];
            $row['IsReceivables'] = '';
            $row['AccTransTypeBIT'] = '';
            $row['ReferenceKey'] = $value['bill_id'].'_'.$value['id'];
            
            // detail
            $row['AccountingTransactionDetails'] = array();
            $row['AccountingTransactionDetails']['length'] = 1;
            $row['AccountingTransactionDetails'][0]['ParentReferenceKey'] = $value['bill_id'].'_'.$value['id'];
            $row['AccountingTransactionDetails'][0]['CreatedOn'] = date('Y-m-d');
            $row['AccountingTransactionDetails'][0]['ChangedOn'] = date('Y-m-d');
            //$row['AccountingTransactionDetails'][0]['ContactCode2'] = 'ACC_'.$value['user_id'];
            $row['AccountingTransactionDetails'][0]['ContactCode'] = $contact_code_2;
            $row['AccountingTransactionDetails'][0]['DebitAccCode'] = 112;
            $row['AccountingTransactionDetails'][0]['CreditAccCode'] = 511;
            $row['AccountingTransactionDetails'][0]['Description'] = $value['description'];
            
                $row['AccountingTransactionDetails'][0]['Amount'] = $value['amount_vnd']*$value['exchange_rate'];
            if($value['currency_id']!='VND')
                $row['AccountingTransactionDetails'][0]['ForeignAmount'] = $value['amount_vnd'];
            $row['AccountingTransactionDetails'][0]['IsReceivables'] = '';
            $row['AccountingTransactionDetails'][0]['ReferenceKey'] = 'DETAIL_'.$value['bill_id'].'_'.$value['id'];
            
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/AccountingTransactions',HttpRequest::METH_POST);
            $r->addPostFields($row);
            try 
            {
                $r->send();
                if($r->getResponseCode()==200)
                    DB::query("update payment set sync_cns=1 where id=".$value['id']);
                else
                    System::debug($r);
            } 
            catch (HttpException $ex) 
            {
                System::debug($r);
            }
        }
        // end REC
        /*******************************************************************************/
        // MICE
        $sql = "
                select 
                    payment.id,
                    payment.bill_id,
                    payment.time,
                    payment.amount,
                    payment.exchange_rate,
                    payment.amount as amount_vnd,
                    payment.payment_type_id,
                    payment.currency_id,
                    payment.user_id,
                    payment.description,
                    payment.type,
                    mice_invoice.id as invoice_id,
                    mice_invoice.reservation_traveller_id,
                    mice_invoice.mice_reservation_id,
                    mice_invoice.bill_id as bill_mice,
                    traveller.id as traveller_id,
                    mice_reservation.customer_id,
                    mice_reservation.traveller_id as traveller_id_mice
                from 
                    payment
                    inner join mice_invoice on mice_invoice.id = payment.bill_id and payment.type='BILL_MICE'
                    inner join mice_reservation on mice_reservation.id=mice_invoice.mice_reservation_id
                    left join reservation_traveller on reservation_traveller.id = mice_invoice.reservation_traveller_id
                    left join traveller on traveller.id = reservation_traveller.traveller_id
                where 
                    payment.sync_cns=0
                    AND (payment.payment_type_id = 'CREDIT_CARD' OR payment.payment_type_id = 'BANK')
                    AND payment.time >= ".Date_Time::to_time(DATE_SYNC_CNS)." 
                    AND payment.portal_id='".PORTAL_ID."'";
        $payment = DB::fetch_all($sql);
        foreach($payment as $key=>$value)
        {
            $contact_code_2 = 'KHACHLE_HOTEL';
            if($value['customer_id']!='')
                    $contact_code_2 = 'CUS_'.$value['customer_id'];
            else
            {
                if($value['traveller_id']!='' and $value['traveller_id']!=0)
                    $contact_code_2 = 'TRA_'.$value['traveller_id'];
                elseif($value['traveller_id_mice']!='' and $value['traveller_id_mice']!=0)
                    $contact_code_2 = 'TRA_'.$value['traveller_id_mice'];
            }
            
            $row = array();
            $row['BranchCode'] = BRANCH_CODE_SYNC_CNS;
            $row['TransTypeCode'] = 'CNT';
            $row['Code'] = $value['bill_id'].'_'.$value['id'];
            $row['CreatedOn'] = date('Y-m-d');
            $row['ChangedOn'] = date('Y-m-d');
            $row['TransDate'] = date('Y-m-d',$value['time']);
            $row['Description'] = $value['type'].'_'.$value['bill_mice'].'_'.$value['description'];
            $row['TotalAmount'] = $value['amount_vnd']*$value['exchange_rate'];
            if($value['currency_id']!='VND')
                $row['TotalForeignAmount'] = $value['amount_vnd'];
            $row['TotalTax'] = 0;
            //$row['ContactCode2'] = 'ACC_'.$value['user_id'];
            $row['ContactCode'] = $contact_code_2;
            $row['CustomerName'] = '';
            $row['CustomerAddress'] = '';
            $row['CustomerTaxCode'] = '';
            
            $row['CurrencyCode'] = $value['currency_id'];
            $row['CurrencyRate'] = $value['exchange_rate'];
            $row['IsReceivables'] = '';
            $row['AccTransTypeBIT'] = '';
            $row['ReferenceKey'] = $value['bill_id'].'_'.$value['id'];
            
            // detail
            $row['AccountingTransactionDetails'] = array();
            $row['AccountingTransactionDetails']['length'] = 1;
            $row['AccountingTransactionDetails'][0]['ParentReferenceKey'] = $value['bill_id'].'_'.$value['id'];
            $row['AccountingTransactionDetails'][0]['CreatedOn'] = date('Y-m-d');
            $row['AccountingTransactionDetails'][0]['ChangedOn'] = date('Y-m-d');
            //$row['AccountingTransactionDetails'][0]['ContactCode2'] = 'ACC_'.$value['user_id'];
            $row['AccountingTransactionDetails'][0]['ContactCode'] = $contact_code_2;
            $row['AccountingTransactionDetails'][0]['DebitAccCode'] = 112;
            $row['AccountingTransactionDetails'][0]['CreditAccCode'] = 511;
            $row['AccountingTransactionDetails'][0]['Description'] = $value['description'];
            $row['AccountingTransactionDetails'][0]['Amount'] = $value['amount_vnd']*$value['exchange_rate'];
            if($value['currency_id']!='VND')
                $row['AccountingTransactionDetails'][0]['ForeignAmount'] = $value['amount_vnd'];
            $row['AccountingTransactionDetails'][0]['IsReceivables'] = '';
            $row['AccountingTransactionDetails'][0]['ReferenceKey'] = 'DETAIL_'.$value['bill_id'].'_'.$value['id'];
            
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/AccountingTransactions',HttpRequest::METH_POST);
            $r->addPostFields($row);
            try 
            {
                $r->send();
                if($r->getResponseCode()==200)
                    DB::query("update payment set sync_cns=1 where id=".$value['id']);
                else
                    System::debug($r);
            } 
            catch (HttpException $ex) 
            {
                System::debug($r);
            }
        }
        // end MICE
        /*****************************************************************************************/
        // RES
        $sql = "
                select 
                    payment.id,
                    payment.bill_id,
                    payment.time,
                    payment.amount,
                    payment.exchange_rate,
                    payment.amount as amount_vnd,
                    payment.payment_type_id,
                    payment.currency_id,
                    payment.user_id,
                    payment.description,
                    payment.type,
                    bar_reservation.customer_id,
                    traveller.id as traveller_id
                from 
                    payment
                    inner join bar_reservation on bar_reservation.id = payment.bill_id and type = 'BAR'
                    left join reservation_traveller on reservation_traveller.id = bar_reservation.reservation_traveller_id
                    left join traveller on traveller.id = reservation_traveller.traveller_id
                where 
                    payment.sync_cns=0
                    AND (payment.payment_type_id = 'CREDIT_CARD' OR payment.payment_type_id = 'BANK')
                    AND payment.time >= ".Date_Time::to_time(DATE_SYNC_CNS)." 
                    AND payment.portal_id='".PORTAL_ID."'";
        $payment = DB::fetch_all($sql);
        foreach($payment as $key=>$value)
        {
            $contact_code_2 = 'KHACHLE_HOTEL';
            if($value['customer_id']!='')
                $contact_code_2 = 'CUS_'.$value['customer_id'];
            elseif($value['traveller_id']!='' and $value['traveller_id']!=0)
                $contact_code_2 = 'TRA_'.$value['traveller_id'];
            
            
            $row = array();
            $row['BranchCode'] = BRANCH_CODE_SYNC_CNS;
            $row['TransTypeCode'] = 'CNT';
            $row['Code'] = $value['bill_id'].'_'.$value['id'];
            $row['CreatedOn'] = date('Y-m-d');
            $row['ChangedOn'] = date('Y-m-d');
            $row['TransDate'] = date('Y-m-d',$value['time']);
            $row['Description'] = $value['type'].'_'.$value['bill_id'].'_'.$value['description'];
            
                $row['TotalAmount'] = $value['amount_vnd']*$value['exchange_rate'];
            if($value['currency_id']!='VND')
                $row['TotalForeignAmount'] = $value['amount_vnd'];
            $row['TotalTax'] = 0;
            //$row['ContactCode2'] = 'ACC_'.$value['user_id'];
            $row['ContactCode'] = $contact_code_2;
            $row['CustomerName'] = '';
            $row['CustomerAddress'] = '';
            $row['CustomerTaxCode'] = '';
            
            $row['CurrencyCode'] = $value['currency_id'];
            $row['CurrencyRate'] = $value['exchange_rate'];
            $row['IsReceivables'] = '';
            $row['AccTransTypeBIT'] = '';
            $row['ReferenceKey'] = $value['bill_id'].'_'.$value['id'];
            
            // detail
            $row['AccountingTransactionDetails'] = array();
            $row['AccountingTransactionDetails']['length'] = 1;
            $row['AccountingTransactionDetails'][0]['ParentReferenceKey'] = $value['bill_id'].'_'.$value['id'];
            $row['AccountingTransactionDetails'][0]['CreatedOn'] = date('Y-m-d');
            $row['AccountingTransactionDetails'][0]['ChangedOn'] = date('Y-m-d');
            //$row['AccountingTransactionDetails'][0]['ContactCode2'] = 'ACC_'.$value['user_id'];
            $row['AccountingTransactionDetails'][0]['ContactCode'] = $contact_code_2;
            $row['AccountingTransactionDetails'][0]['DebitAccCode'] = 112;
            $row['AccountingTransactionDetails'][0]['CreditAccCode'] = 511;
            $row['AccountingTransactionDetails'][0]['Description'] = $value['description'];
            
                $row['AccountingTransactionDetails'][0]['Amount'] = $value['amount_vnd']*$value['exchange_rate'];
            if($value['currency_id']!='VND')
                $row['AccountingTransactionDetails'][0]['ForeignAmount'] = $value['amount_vnd'];
            $row['AccountingTransactionDetails'][0]['IsReceivables'] = '';
            $row['AccountingTransactionDetails'][0]['ReferenceKey'] = 'DETAIL_'.$value['bill_id'].'_'.$value['id'];
            
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/AccountingTransactions',HttpRequest::METH_POST);
            $r->addPostFields($row);
            try 
            {
                $r->send();
                if($r->getResponseCode()==200)
                    DB::query("update payment set sync_cns=1 where id=".$value['id']);
                else
                    System::debug($r);
            } 
            catch (HttpException $ex) 
            {
                System::debug($r);
            }
        }
        //end RES
        /*********************************************************************************************/
        // VEND
        $sql = "
                select 
                    payment.id,
                    payment.bill_id,
                    payment.time,
                    payment.amount,
                    payment.exchange_rate,
                    payment.amount as amount_vnd,
                    payment.payment_type_id,
                    payment.currency_id,
                    payment.user_id,
                    payment.description,
                    payment.type,
                    traveller.id as traveller_id,
                    ve_reservation.customer_id
                from 
                    payment
                    inner join ve_reservation on ve_reservation.id = payment.bill_id and type = 'VEND'
                    left join reservation_traveller on reservation_traveller.id = ve_reservation.reservation_traveller_id
                    left join traveller on traveller.id = reservation_traveller.traveller_id
                where 
                    payment.sync_cns=0
                    AND (payment.payment_type_id = 'CREDIT_CARD' OR payment.payment_type_id = 'BANK')
                    AND payment.time >= ".Date_Time::to_time(DATE_SYNC_CNS)." 
                    AND payment.portal_id='".PORTAL_ID."'";
        $payment = DB::fetch_all($sql);
        foreach($payment as $key=>$value)
        {
            $contact_code_2 = 'KHACHLE_HOTEL';
            if($value['customer_id']!='')
                $contact_code_2 = 'CUS_'.$value['customer_id'];
            elseif($value['traveller_id']!='' and $value['traveller_id']!=0)
                $contact_code_2 = 'TRA_'.$value['traveller_id'];
            
            
            $row = array();
            $row['BranchCode'] = BRANCH_CODE_SYNC_CNS;
            $row['TransTypeCode'] = 'CNT';
            $row['Code'] = $value['bill_id'].'_'.$value['id'];
            $row['CreatedOn'] = date('Y-m-d');
            $row['ChangedOn'] = date('Y-m-d');
            $row['TransDate'] = date('Y-m-d',$value['time']);
            $row['Description'] = $value['type'].'_'.$value['bill_id'].'_'.$value['description'];
            
                $row['TotalAmount'] = $value['amount_vnd']*$value['exchange_rate'];
            if($value['currency_id']!='VND')
                $row['TotalForeignAmount'] = $value['amount_vnd'];
            $row['TotalTax'] = 0;
            //$row['ContactCode2'] = 'ACC_'.$value['user_id'];
            $row['ContactCode'] = $contact_code_2;
            $row['CustomerName'] = '';
            $row['CustomerAddress'] = '';
            $row['CustomerTaxCode'] = '';
            
            $row['CurrencyCode'] = $value['currency_id'];
            $row['CurrencyRate'] = $value['exchange_rate'];
            $row['IsReceivables'] = '';
            $row['AccTransTypeBIT'] = '';
            $row['ReferenceKey'] = $value['bill_id'].'_'.$value['id'];
            
            // detail
            $row['AccountingTransactionDetails'] = array();
            $row['AccountingTransactionDetails']['length'] = 1;
            $row['AccountingTransactionDetails'][0]['ParentReferenceKey'] = $value['bill_id'].'_'.$value['id'];
            $row['AccountingTransactionDetails'][0]['CreatedOn'] = date('Y-m-d');
            $row['AccountingTransactionDetails'][0]['ChangedOn'] = date('Y-m-d');
            //$row['AccountingTransactionDetails'][0]['ContactCode2'] = 'ACC_'.$value['user_id'];
            $row['AccountingTransactionDetails'][0]['ContactCode'] = $contact_code_2;
            $row['AccountingTransactionDetails'][0]['DebitAccCode'] = 112;
            $row['AccountingTransactionDetails'][0]['CreditAccCode'] = 511;
            $row['AccountingTransactionDetails'][0]['Description'] = $value['description'];
            
                $row['AccountingTransactionDetails'][0]['Amount'] = $value['amount_vnd']*$value['exchange_rate'];
            if($value['currency_id']!='VND')
                $row['AccountingTransactionDetails'][0]['ForeignAmount'] = $value['amount_vnd'];
            $row['AccountingTransactionDetails'][0]['IsReceivables'] = '';
            $row['AccountingTransactionDetails'][0]['ReferenceKey'] = 'DETAIL_'.$value['bill_id'].'_'.$value['id'];
            
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/AccountingTransactions',HttpRequest::METH_POST);
            $r->addPostFields($row);
            try 
            {
                $r->send();
                if($r->getResponseCode()==200)
                    DB::query("update payment set sync_cns=1 where id=".$value['id']);
                else
                    System::debug($r);
            } 
            catch (HttpException $ex) 
            {
                System::debug($r);
            }
        }
        // end VEND
        /*********************************************************************************/
        
        /***********************************************************************************************************/
        // DEPOSIT ROOM REC
        $sql = "
            select 
                payment.id,
                payment.bill_id,
                payment.time,
                payment.amount,
                payment.exchange_rate,
                payment.amount as amount_vnd,
                payment.payment_type_id,
                payment.currency_id,
                payment.user_id,
                payment.description,
                payment.type,
                reservation_room.traveller_id,
                reservation.customer_id
            from 
                payment
                inner join reservation_room on reservation_room.id = payment.reservation_room_id
                inner join reservation on reservation.id=reservation_room.reservation_id
            where 
                payment.sync_cns=0
                AND (payment.payment_type_id = 'CREDIT_CARD' OR payment.payment_type_id = 'BANK')
                AND payment.time >= ".Date_Time::to_time(DATE_SYNC_CNS)." 
                AND payment.portal_id='".PORTAL_ID."'
                and type_dps='ROOM'";
        $payment = DB::fetch_all($sql);
        foreach($payment as $key=>$value)
        {
            $contact_code_2 = 'KHACHLE_HOTEL';
            if($value['customer_id']!='')
                $contact_code_2 = 'CUS_'.$value['customer_id'];
            elseif($value['traveller_id']!='' and $value['traveller_id']!=0)
                $contact_code_2 = 'TRA_'.$value['traveller_id'];
            
            
            $row = array();
            $row['BranchCode'] = BRANCH_CODE_SYNC_CNS;
            $row['TransTypeCode'] = 'CNT';
            $row['Code'] = $value['bill_id'].'_'.$value['id'];
            $row['CreatedOn'] = date('Y-m-d');
            $row['ChangedOn'] = date('Y-m-d');
            $row['TransDate'] = date('Y-m-d',$value['time']);
            $row['Description'] = $value['type'].'_'.$value['bill_id'].'_'.$value['description'];
            
                $row['TotalAmount'] = $value['amount_vnd']*$value['exchange_rate'];
            if($value['currency_id']!='VND')
                $row['TotalForeignAmount'] = $value['amount_vnd'];
            $row['TotalTax'] = 0;
            //$row['ContactCode2'] = 'ACC_'.$value['user_id'];
            $row['ContactCode'] = $contact_code_2;
            $row['CustomerName'] = '';
            $row['CustomerAddress'] = '';
            $row['CustomerTaxCode'] = '';
            
            $row['CurrencyCode'] = $value['currency_id'];
            $row['CurrencyRate'] = $value['exchange_rate'];
            $row['IsReceivables'] = '';
            $row['AccTransTypeBIT'] = '';
            $row['ReferenceKey'] = $value['bill_id'].'_'.$value['id'];
            
            // detail
            $row['AccountingTransactionDetails'] = array();
            $row['AccountingTransactionDetails']['length'] = 1;
            $row['AccountingTransactionDetails'][0]['ParentReferenceKey'] = $value['bill_id'].'_'.$value['id'];
            $row['AccountingTransactionDetails'][0]['CreatedOn'] = date('Y-m-d');
            $row['AccountingTransactionDetails'][0]['ChangedOn'] = date('Y-m-d');
            //$row['AccountingTransactionDetails'][0]['ContactCode2'] = 'ACC_'.$value['user_id'];
            $row['AccountingTransactionDetails'][0]['ContactCode'] = $contact_code_2;
            $row['AccountingTransactionDetails'][0]['DebitAccCode'] = 112;
            $row['AccountingTransactionDetails'][0]['CreditAccCode'] = 511;
            $row['AccountingTransactionDetails'][0]['Description'] = $value['description'];
            
                $row['AccountingTransactionDetails'][0]['Amount'] = $value['amount_vnd']*$value['exchange_rate'];
            if($value['currency_id']!='VND')
                $row['AccountingTransactionDetails'][0]['ForeignAmount'] = $value['amount_vnd'];
            $row['AccountingTransactionDetails'][0]['IsReceivables'] = '';
            $row['AccountingTransactionDetails'][0]['ReferenceKey'] = 'DETAIL_'.$value['bill_id'].'_'.$value['id'];
            
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/AccountingTransactions',HttpRequest::METH_POST);
            $r->addPostFields($row);
            try 
            {
                $r->send();
                if($r->getResponseCode()==200)
                    DB::query("update payment set sync_cns=1 where id=".$value['id']);
                else
                    System::debug($r);
            } 
            catch (HttpException $ex) 
            {
                System::debug($r);
            }
        }
        // end DEPOSIT ROOM
        /*************************************************************************************************************/
        // DEPOSIT GROUP REC
        $sql = "
            select 
                payment.id,
                payment.bill_id,
                payment.time,
                payment.amount,
                payment.exchange_rate,
                payment.amount as amount_vnd,
                payment.payment_type_id,
                payment.currency_id,
                payment.user_id,
                payment.description,
                payment.type,
                reservation.customer_id
            from 
                payment
                inner join reservation on reservation.id=payment.reservation_id
            where 
                payment.sync_cns=0
                AND (payment.payment_type_id = 'CREDIT_CARD' OR payment.payment_type_id = 'BANK')
                AND payment.time >= ".Date_Time::to_time(DATE_SYNC_CNS)." 
                AND payment.portal_id='".PORTAL_ID."'
                and type_dps='GROUP'";
        $payment = DB::fetch_all($sql);
        foreach($payment as $key=>$value)
        {
            $contact_code_2 = 'KHACHLE_HOTEL';
            if($value['customer_id']!='')
                $contact_code_2 = 'CUS_'.$value['customer_id'];
            
            $row = array();
            $row['BranchCode'] = BRANCH_CODE_SYNC_CNS;
            $row['TransTypeCode'] = 'CNT';
            $row['Code'] = $value['bill_id'].'_'.$value['id'];
            $row['CreatedOn'] = date('Y-m-d');
            $row['ChangedOn'] = date('Y-m-d');
            $row['TransDate'] = date('Y-m-d',$value['time']);
            $row['Description'] = $value['type'].'_'.$value['bill_id'].'_'.$value['description'];
            
                $row['TotalAmount'] = $value['amount_vnd']*$value['exchange_rate'];
            if($value['currency_id']!='VND')
                $row['TotalForeignAmount'] = $value['amount_vnd'];
            $row['TotalTax'] = 0;
            //$row['ContactCode2'] = 'ACC_'.$value['user_id'];
            $row['ContactCode'] = $contact_code_2;
            $row['CustomerName'] = '';
            $row['CustomerAddress'] = '';
            $row['CustomerTaxCode'] = '';
            
            $row['CurrencyCode'] = $value['currency_id'];
            $row['CurrencyRate'] = $value['exchange_rate'];
            $row['IsReceivables'] = '';
            $row['AccTransTypeBIT'] = '';
            $row['ReferenceKey'] = $value['bill_id'].'_'.$value['id'];
            
            // detail
            $row['AccountingTransactionDetails'] = array();
            $row['AccountingTransactionDetails']['length'] = 1;
            $row['AccountingTransactionDetails'][0]['ParentReferenceKey'] = $value['bill_id'].'_'.$value['id'];
            $row['AccountingTransactionDetails'][0]['CreatedOn'] = date('Y-m-d');
            $row['AccountingTransactionDetails'][0]['ChangedOn'] = date('Y-m-d');
            //$row['AccountingTransactionDetails'][0]['ContactCode2'] = 'ACC_'.$value['user_id'];
            $row['AccountingTransactionDetails'][0]['ContactCode'] = $contact_code_2;
            $row['AccountingTransactionDetails'][0]['DebitAccCode'] = 112;
            $row['AccountingTransactionDetails'][0]['CreditAccCode'] = 511;
            $row['AccountingTransactionDetails'][0]['Description'] = $value['description'];
            
                $row['AccountingTransactionDetails'][0]['Amount'] = $value['amount_vnd']*$value['exchange_rate'];
            if($value['currency_id']!='VND')
                $row['AccountingTransactionDetails'][0]['ForeignAmount'] = $value['amount_vnd'];
            $row['AccountingTransactionDetails'][0]['IsReceivables'] = '';
            $row['AccountingTransactionDetails'][0]['ReferenceKey'] = 'DETAIL_'.$value['bill_id'].'_'.$value['id'];
            
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/AccountingTransactions',HttpRequest::METH_POST);
            $r->addPostFields($row);
            try 
            {
                $r->send();
                if($r->getResponseCode()==200)
                    DB::query("update payment set sync_cns=1 where id=".$value['id']);
                else
                    System::debug($r);
            } 
            catch (HttpException $ex) 
            {
                System::debug($r);
            }
        }
        // end DEPOSIT GROUP 
        /**********************************************************************************************************/
        // DEPOSIT MICE
        $sql = "
            select 
                payment.id,
                payment.bill_id,
                payment.time,
                payment.amount,
                payment.exchange_rate,
                payment.amount as amount_vnd,
                payment.payment_type_id,
                payment.currency_id,
                payment.user_id,
                payment.description,
                payment.type,
                mice_reservation.id as bill_id,
                mice_reservation.customer_id,
                mice_reservation.traveller_id
            from 
                payment
                inner join mice_reservation on mice_reservation.id = payment.bill_id and type = 'MICE'
                left join customer on customer.id=mice_reservation.customer_id
            where 
                payment.sync_cns=0
                AND (payment.payment_type_id = 'CREDIT_CARD' OR payment.payment_type_id = 'BANK')
                AND payment.time >= ".Date_Time::to_time(DATE_SYNC_CNS)." 
                AND payment.portal_id='".PORTAL_ID."'
                and type_dps = 'MICE'";
        $payment = DB::fetch_all($sql);
        foreach($payment as $key=>$value)
        {
            $contact_code_2 = 'KHACHLE_HOTEL';
            if($value['customer_id']!='')
                $contact_code_2 = 'CUS_'.$value['customer_id'];
            elseif($value['traveller_id']!='' and $value['traveller_id']!=0)
                $contact_code_2 = 'TRA_'.$value['traveller_id'];
            
            
            $row = array();
            $row['BranchCode'] = BRANCH_CODE_SYNC_CNS;
            $row['TransTypeCode'] = 'CNT';
            $row['Code'] = $value['bill_id'].'_'.$value['id'];
            $row['CreatedOn'] = date('Y-m-d');
            $row['ChangedOn'] = date('Y-m-d');
            $row['TransDate'] = date('Y-m-d',$value['time']);
            $row['Description'] = $value['type'].'_'.$value['bill_id'].'_'.$value['description'];
            $row['TotalAmount'] = $value['amount_vnd']*$value['exchange_rate'];
            if($value['currency_id']!='VND')
                $row['TotalForeignAmount'] = $value['amount_vnd'];
            $row['TotalTax'] = 0;
            //$row['ContactCode2'] = 'ACC_'.$value['user_id'];
            $row['ContactCode'] = $contact_code_2;
            $row['CustomerName'] = '';
            $row['CustomerAddress'] = '';
            $row['CustomerTaxCode'] = '';
            
            $row['CurrencyCode'] = $value['currency_id'];
            $row['CurrencyRate'] = $value['exchange_rate'];
            $row['IsReceivables'] = '';
            $row['AccTransTypeBIT'] = '';
            $row['ReferenceKey'] = $value['bill_id'].'_'.$value['id'];
            
            // detail
            $row['AccountingTransactionDetails'] = array();
            $row['AccountingTransactionDetails']['length'] = 1;
            $row['AccountingTransactionDetails'][0]['ParentReferenceKey'] = $value['bill_id'].'_'.$value['id'];
            $row['AccountingTransactionDetails'][0]['CreatedOn'] = date('Y-m-d');
            $row['AccountingTransactionDetails'][0]['ChangedOn'] = date('Y-m-d');
            //$row['AccountingTransactionDetails'][0]['ContactCode2'] = 'ACC_'.$value['user_id'];
            $row['AccountingTransactionDetails'][0]['ContactCode'] = $contact_code_2;
            $row['AccountingTransactionDetails'][0]['DebitAccCode'] = 112;
            $row['AccountingTransactionDetails'][0]['CreditAccCode'] = 511;
            $row['AccountingTransactionDetails'][0]['Description'] = $value['description'];
            $row['AccountingTransactionDetails'][0]['Amount'] = $value['amount_vnd']*$value['exchange_rate'];
            if($value['currency_id']!='VND')
                $row['AccountingTransactionDetails'][0]['ForeignAmount'] = $value['amount_vnd'];
            $row['AccountingTransactionDetails'][0]['IsReceivables'] = '';
            $row['AccountingTransactionDetails'][0]['ReferenceKey'] = 'DETAIL_'.$value['bill_id'].'_'.$value['id'];
            
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/AccountingTransactions',HttpRequest::METH_POST);
            $r->addPostFields($row);
            try 
            {
                $r->send();
                if($r->getResponseCode()==200)
                    DB::query("update payment set sync_cns=1 where id=".$value['id']);
                else
                    System::debug($r);
            } 
            catch (HttpException $ex) 
            {
                System::debug($r);
            }
        }
        // end DEPOSIT MICE
        /*************************************************************************************************************/
        // DEPOSIT BAR
        $sql = "
                select 
                    payment.id,
                    payment.bill_id,
                    payment.time,
                    payment.amount,
                    payment.exchange_rate,
                    payment.amount as amount_vnd,
                    payment.payment_type_id,
                    payment.currency_id,
                    payment.user_id,
                    payment.description,
                    payment.type,
                    bar_reservation.customer_id,
                    traveller.id as traveller_id
                from 
                    payment
                    inner join bar_reservation on bar_reservation.id = payment.bill_id and type = 'BAR'
                    left join reservation_traveller on reservation_traveller.id = bar_reservation.reservation_traveller_id
                    left join traveller on traveller.id = reservation_traveller.traveller_id
                where 
                    payment.sync_cns=0
                    AND (payment.payment_type_id = 'CREDIT_CARD' OR payment.payment_type_id = 'BANK')
                    AND payment.time >= ".Date_Time::to_time(DATE_SYNC_CNS)." 
                    AND payment.portal_id='".PORTAL_ID."' 
                    and type_dps = 'BAR'
                ";
        $payment = DB::fetch_all($sql);
        foreach($payment as $key=>$value)
        {
            $contact_code_2 = 'KHACHLE_HOTEL';
            if($value['customer_id']!='')
                $contact_code_2 = 'CUS_'.$value['customer_id'];
            elseif($value['traveller_id']!='' and $value['traveller_id']!=0)
                $contact_code_2 = 'TRA_'.$value['traveller_id'];
            
            
            $row = array();
            $row['BranchCode'] = BRANCH_CODE_SYNC_CNS;
            $row['TransTypeCode'] = 'CNT';
            $row['Code'] = $value['bill_id'].'_'.$value['id'];
            $row['CreatedOn'] = date('Y-m-d');
            $row['ChangedOn'] = date('Y-m-d');
            $row['TransDate'] = date('Y-m-d',$value['time']);
            $row['Description'] = $value['type'].'_'.$value['bill_id'].'_'.$value['description'];
            
                $row['TotalAmount'] = $value['amount_vnd']*$value['exchange_rate'];
            if($value['currency_id']!='VND')
                $row['TotalForeignAmount'] = $value['amount_vnd'];
            $row['TotalTax'] = 0;
            //$row['ContactCode2'] = 'ACC_'.$value['user_id'];
            $row['ContactCode'] = $contact_code_2;
            $row['CustomerName'] = '';
            $row['CustomerAddress'] = '';
            $row['CustomerTaxCode'] = '';
            
            $row['CurrencyCode'] = $value['currency_id'];
            $row['CurrencyRate'] = $value['exchange_rate'];
            $row['IsReceivables'] = '';
            $row['AccTransTypeBIT'] = '';
            $row['ReferenceKey'] = $value['bill_id'].'_'.$value['id'];
            
            // detail
            $row['AccountingTransactionDetails'] = array();
            $row['AccountingTransactionDetails']['length'] = 1;
            $row['AccountingTransactionDetails'][0]['ParentReferenceKey'] = $value['bill_id'].'_'.$value['id'];
            $row['AccountingTransactionDetails'][0]['CreatedOn'] = date('Y-m-d');
            $row['AccountingTransactionDetails'][0]['ChangedOn'] = date('Y-m-d');
            //$row['AccountingTransactionDetails'][0]['ContactCode2'] = 'ACC_'.$value['user_id'];
            $row['AccountingTransactionDetails'][0]['ContactCode'] = $contact_code_2;
            $row['AccountingTransactionDetails'][0]['DebitAccCode'] = 112;
            $row['AccountingTransactionDetails'][0]['CreditAccCode'] = 511;
            $row['AccountingTransactionDetails'][0]['Description'] = $value['description'];
            
                $row['AccountingTransactionDetails'][0]['Amount'] = $value['amount_vnd']*$value['exchange_rate'];
            if($value['currency_id']!='VND')
                $row['AccountingTransactionDetails'][0]['ForeignAmount'] = $value['amount_vnd'];
            $row['AccountingTransactionDetails'][0]['IsReceivables'] = '';
            $row['AccountingTransactionDetails'][0]['ReferenceKey'] = 'DETAIL_'.$value['bill_id'].'_'.$value['id'];
            
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/AccountingTransactions',HttpRequest::METH_POST);
            $r->addPostFields($row);
            try 
            {
                $r->send();
                if($r->getResponseCode()==200)
                    DB::query("update payment set sync_cns=1 where id=".$value['id']);
                else
                    System::debug($r);
            } 
            catch (HttpException $ex) 
            {
                System::debug($r);
            }
        }
        // end DEPOSIT BAR
    }
?>