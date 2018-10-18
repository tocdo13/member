<?php
    class ListSupplierNew extends Form
    {
        function ListSupplierNew()
        {
            Form::Form('ListSupplierNew');
        }
        function draw()
        {
            $this->map= array();
            //System::debug($_REQUEST);
            $cond= '1=1';
            $cond .= (Url::get('code_search'))?'AND upper(supplier.code) LIKE \'%'.strtoupper(Url::sget('code_search') ).'%\' ':''  ;
            $cond .= (Url::get('supplier_name'))?'AND upper(supplier.name) LIKE \'%'.strtoupper(Url::sget('supplier_name') ).'%\' ':'';
            $sql='
                select 
                    supplier.*
                  from
                        supplier
                   where
                    '.$cond.'     
                  ORDER BY id DESC      
            ';
            //System::debug($sql);exit();
            $items= DB::fetch_all($sql);
            $this->map['items']=$items;
            
            //System::debug($items);
            
            $this ->parse_layout('list',$this->map);
        }
    } 
    
?>