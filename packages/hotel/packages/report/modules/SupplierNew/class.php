<?php
    class SupplierNew extends Module
    {
        public static $item= array();
        function SupplierNew($row)
        {
            Module::Module($row);
            if(User::can_view(false,ANY_CATEGORY))
            {
                switch(Url::get('cmd'))// neu ton tai cai get cmd thi
                    {
                        case 'add': require('forms/edit.php'); // neu no bang add thi
                        $this->add_form(new EditSupplierNewForm());
                        break;
                        
                        case 'edit':
                            if(User::can_edit(false,ANY_CATEGORY) and Url::get('id') and SupplierNew::$item= DB::select('supplier','id ='.Url::iget('id')) )
                            {
                                require('forms/edit.php');
                                $this->add_form(new EditSupplierNewForm());
                                break;
                            }else
                            {
                                Url::access_denied();
                                break;
                            }
                        case 'delete': //
                            if(!DB::exists('select id from pc_order where pc_order.pc_supplier_id='.Url::iget('id')))// cau nay kiem tra sp khai bao gia nha cc thi ko cho xoa
                            {
                                DB::delete('supplier','id ='.Url::iget('id'));
                                DB::delete('pc_sup_price','supplier_id = '.Url::iget('id'));
                            }
                        Url::redirect_current();
                        break;
                        case 'group_delete':
                            if(Url::get('id_select'))
                            {
                                $id_select = explode(',',Url::get('id_select'));
                                System::debug($id_select);
                                foreach($id_select as $key)
                                {
                                    if(!DB::exists('select id from pc_order where pc_order.pc_supplier_id ='.$key))
                                    {
                                         DB::delete('supplier','id ='.$key);
                                         DB::delete('pc_sup_price','supplier_id = '.$key);
                                    }
                                }
                            }
                            Url::redirect_current();
                            break;
                         case 'import':
    				if(User::can_add(false,ANY_CATEGORY))
                    {
    					require_once 'forms/import.php';
    					$this->add_form(new ImportSupplierFormNew());
    				}
                    else
    					Url::access_denied();
    				break;      
                        default:
                        require('forms/list.php');
                        $this->add_form(new ListSupplierNew());   
                        break;
                    }
                
            }
            else
            {
                Url::access_denied();
            }
        }
    }

?>