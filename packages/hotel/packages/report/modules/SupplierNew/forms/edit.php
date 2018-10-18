<?php
    class EditSupplierNewForm extends Form
    {
        function EditSupplierNewForm()
        {
            Form::Form("EditSupplierNewForm");
            $this->add('code',new UniqueType(true,'ban_phai_nhap_ma_hh','supplier','code') );
            $this->add('name',new UniqueType(true,'ban_phai_nhap_ten_hh','supplier','name') );
        }
        function on_submit()
        {
            if($this->check())
            {
                /* kiem tra dk cho tung truong (code va name) */
                if(trim(Url::get('code'))=='')
                {
                    $this->error('code','code_is_not_null');
                    return false;
                }
                /* kiem tra dk cho tung truong (code va name) */
                $array = array(
                        'code'=>Url::get('code'),
                        'name'=>Url::get('name'),
                        'fax',
        				'tax_code',
        				'phone',
        				'mobile',
        				'address',
        				'email',
        				'contact_person_name',
                        'contact_person_phone',
                        'contact_person_mobile',
                        'contact_person_email',
                        'account_number',
                    );
                    if(Url::get('cmd')=='add')
                    {
                        if(DB::exists('select * from supplier where code = \''.$array['code'].'\'')) // neu trung ma~
                        {
                            $this->error('code','ban_phai_nhap_ten_hh');// thi no se thongbao loi
                            return false ;// va tra ve kq luon
                        }
                        $supplier_id=DB::insert('supplier',$array);
                    }
                        elseif( Url::get('cmd')=='edit')
                        {
                            DB::update('supplier',$array,'id ='.Url::iget('id'));
                        }
                Url::redirect_current();
            }
        }
        function draw()
        {
            //System::debug($_REQUEST);
            $this-> map= array();
            $items = SupplierNew::$item;
            foreach($items as$key=>$value)
            {
                if(!isset($_REQUEST[$key]))
                {
                    $_REQUEST[$key]=$value;
                }
            }
            
            $this->parse_layout('edit',$this->map);
        }
    }

?>