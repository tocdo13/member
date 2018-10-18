<?php
    /**
     * *********************************************************
     * *********************** DONG BO DANH MUC DOI TUONG ******
     * *********************** api/contacts - ContactCode ******
     * *********************************************************
     * */
    function sync_supplier_contacts()
    {
        set_time_limit(-1);
        /** dong bo nha cung cap sang danh muc doi tuong  / Tien to: SU_... **/
        $supplier = DB::fetch_all('
                                    SELECT 
                                        supplier.*,
                                        supplier.code as key,
                                        CONCAT(\'SU_\',supplier.code) as id,
                                        CONCAT(\'SU_\',supplier.code) as code
                                    FROM 
                                        supplier 
                                    WHERE 
                                        (supplier.code is not null)
                                        and supplier.sync_cns=0
                                    ');
        foreach($supplier as $key=>$value)
        {
            $row = array();
            $row['ReferenceKey'] = $value['code'];
            $row['Code'] = $value['code'];
            $row['GroupCode'] = 'GROUP_TCV';
            $row['ParentCode'] = 'GROUP_TCV';
            $row['Name'] = $value['name'];
            $row['Name2'] = $value['name'];
            $row['Address'] = $value['address'];
            $row['Address2'] = $value['address'];
            $row['Phone'] = $value['phone'];
            $row['Mobile'] = $value['mobile'];
            $row['Email'] = $value['email'];
            $row['Website'] = '';
            $row['BankAccount'] = '';
            $row['BankName'] = '';
            $row['TaxCode'] = $value['tax_code'];
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/Contacts',HttpRequest::METH_POST);
            $r->addPostFields($row);
            try 
            {
                $r->send();
                if($r->getResponseCode()==200)
                {
                    DB::query("update supplier set sync_cns=1 where code='".$value['key']."'");
                }
                //else
                    //System::debug($r);
            } 
            catch (HttpException $ex) 
            {
                //System::debug($r);
            }
        }
    }
    
    function sync_customer_contacts()
    {
        set_time_limit(-1);
        /** dong bo nhom nguon khach sang danh muc contact / tien to CUS_... **/
        $customer = DB::fetch_all('
                                    SELECT
                                        customer.*,
                                        customer.id as key,
                                        CONCAT(\'CUS_\',customer.id) as id,
                                        CONCAT(\'CUS_\',customer.id) as code
                                    FROM
                                        customer
                                    WHERE
                                        customer.sync_cns=0
                                    ');
        foreach($customer as $key=>$value)
        {
            $row = array();
            $row['ReferenceKey'] = $value['code'];
            $row['Code'] = $value['code'];
            $row['GroupCode'] = 'GROUP_TCV';
            $row['ParentCode'] = 'GROUP_TCV';
            $row['Name'] = $value['name'];
            $row['Name2'] = $value['name'];
            $row['Address'] = $value['address'];
            $row['Address2'] = $value['address'];
            $row['Phone'] = $value['phone'];
            $row['Mobile'] = $value['mobile'];
            $row['Email'] = $value['email'];
            $row['Website'] = '';
            $row['BankAccount'] = '';
            $row['BankName'] = '';
            $row['TaxCode'] = $value['tax_code'];
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/Contacts',HttpRequest::METH_POST);
            $r->addPostFields($row);
            try 
            {
                $r->send();
                if($r->getResponseCode()==200)
                {
                    DB::query("update customer set sync_cns=1 where id='".$value['key']."'");
                }
                //else
                    //System::debug($r);
            } 
            catch (HttpException $ex) 
            {
                //System::debug($r);
            }
        }
    }
    
    function sync_account_contacts()
    {
        set_time_limit(-1);
        /** dong bo tai khoan sang danh muc doi tuong / tiem to ACC_... **/
        $account = DB::fetch_all('
                                    SELECT
                                        account.*,
                                        party.full_name as name,
                                        account.id as key,
                                        CONCAT(\'ACC_\',account.id) as id,
                                        CONCAT(\'ACC_\',account.id) as code,
                                        \'\' as mobile,
                                        party.phone,
                                        party.address,
                                        party.email,
                                        \'\' as tax_code
                                    FROM
                                        account
                                        inner join party on party.user_id=account.id
                                        inner join account_privilege_group on account_privilege_group.account_id=account.id
                                    WHERE
                                        account.sync_cns=0
                                    ');
        foreach($account as $key=>$value)
        {
            $row = array();
            $row['ReferenceKey'] = $value['code'];
            $row['Code'] = $value['code'];
            $row['GroupCode'] = 'GROUP_TCV';
            $row['ParentCode'] = 'GROUP_TCV';
            $row['Name'] = $value['name'];
            $row['Name2'] = $value['name'];
            $row['Address'] = $value['address'];
            $row['Address2'] = $value['address'];
            $row['Phone'] = $value['phone'];
            $row['Mobile'] = $value['mobile'];
            $row['Email'] = $value['email'];
            $row['Website'] = '';
            $row['BankAccount'] = '';
            $row['BankName'] = '';
            $row['TaxCode'] = $value['tax_code'];
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/Contacts',HttpRequest::METH_POST);
            $r->addPostFields($row);
            try 
            {
                $r->send();
                if($r->getResponseCode()==200)
                {
                    DB::query("update account set sync_cns=1 where id='".$value['key']."'");
                }
                //else
                    //System::debug($r);
            } 
            catch (HttpException $ex) 
            {
                //System::debug($r);
            }
        }
    }
    
    function sync_massage_guest_contacts()
    {
        set_time_limit(-1);
        $spa_guest = DB::fetch_all('
                                    SELECT
                                        massage_guest.*,
                                        massage_guest.code as key,
                                        CONCAT(\'SPA_\',massage_guest.code) as id,
                                        CONCAT(\'SPA_\',massage_guest.code) as code,
                                        massage_guest.full_name as name
                                    FROM
                                        massage_guest
                                    WHERE
                                        massage_guest.sync_cns=0
                                    ORDER by
                                        massage_guest.full_name
                                    ');
        foreach($spa_guest as $key=>$value)
        {
            $row = array();
            $row['ReferenceKey'] = $value['code'];
            $row['Code'] = $value['code'];
            $row['GroupCode'] = 'GROUP_TCV';
            $row['ParentCode'] = 'GROUP_TCV';
            $row['Name'] = $value['name'];
            $row['Name2'] = $value['name'];
            $row['Address'] = $value['address'];
            $row['Address2'] = $value['address'];
            $row['Phone'] = $value['phone'];
            $row['Mobile'] = '';
            $row['Email'] = $value['email'];
            $row['Website'] = '';
            $row['BankAccount'] = '';
            $row['BankName'] = '';
            $row['TaxCode'] = '';
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/Contacts',HttpRequest::METH_POST);
            $r->addPostFields($row);
            try 
            {
                $r->send();
                if($r->getResponseCode()==200)
                {
                    DB::query("update massage_guest set sync_cns=1 where code='".$value['key']."'");
                }
                //else
                    //System::debug($r);
            }
            catch (HttpException $ex) 
            {
                //System::debug($r);
            }
        }
    }
    
    function sync_traveller_contacts()
    {
        set_time_limit(-1);
        $traveller = DB::fetch_all('
                                    SELECT
                                        traveller.*,
                                        traveller.first_name || \' \' || traveller.last_name as name,
                                        traveller.id as key,
                                        CONCAT(\'TRA_\',traveller.id) as id,
                                        CONCAT(\'TRA_\',traveller.id) as code
                                    FROM
                                        traveller
                                    WHERE
                                        traveller.sync_cns is null OR 
                                        traveller.sync_cns = 0
                                    ');
        foreach($traveller as $key=>$value)
        {
            $row = array();
            $row['ReferenceKey'] = $value['code'];
            $row['Code'] = $value['code'];
            $row['GroupCode'] = 'GROUP_TCV';
            $row['ParentCode'] = 'GROUP_TCV';
            $row['Name'] = $value['name'];
            $row['Name2'] = $value['name'];
            $row['Address'] = $value['address'];
            $row['Address2'] = $value['address'];
            $row['Phone'] = $value['phone'];
            $row['Mobile'] = '';
            $row['Email'] = $value['email'];
            $row['Website'] = '';
            $row['BankAccount'] = '';
            $row['BankName'] = '';
            $row['TaxCode'] = '';
            $r = new HttpRequest(LINK_SYNC_CNS.'/api/Contacts',HttpRequest::METH_POST);
            $r->addPostFields($row);
            try 
            {
                $r->send();
                if($r->getResponseCode()==200)
                {
                    DB::query("update traveller set sync_cns=1 where id='".$value['key']."'");
                }
                //else
                    //System::debug($r);
            }
            catch (HttpException $ex) 
            {
                //System::debug($r);
            }
        }
    }
    
    function sync_other_contacts()
    {
        set_time_limit(-1);
        $row = array();
        $row['ReferenceKey'] = 'KHACHLE_HOTEL';
        $row['Code'] = 'KHACHLE_HOTEL';
        $row['GroupCode'] = 'GROUP_TCV';
        $row['ParentCode'] = 'GROUP_TCV';
        $row['Name'] = 'KHACH LE HOTEL';
        $row['Name2'] = 'KHACH LE HOTEL';
        $row['Address'] = '';
        $row['Address2'] = '';
        $row['Phone'] = '';
        $row['Mobile'] = '';
        $row['Email'] = '';
        $row['Website'] = '';
        $row['BankAccount'] = '';
        $row['BankName'] = '';
        $row['TaxCode'] = '';
        $r = new HttpRequest(LINK_SYNC_CNS.'/api/Contacts',HttpRequest::METH_POST);
        $r->addPostFields($row);
        try 
        {
            $r->send();
            if($r->getResponseCode()==200)
            {
            }
            //else
                //System::debug($r);
        }
        catch (HttpException $ex) 
        {
            //System::debug($r);
        }
    }
    
    
?>