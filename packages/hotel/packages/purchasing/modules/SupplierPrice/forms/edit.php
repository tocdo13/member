<?php 
class EditSupplierPriceForm extends Form{
    function EditSupplierPriceForm(){
        Form::Form('EditSupplierPriceForm');
        $this->link_css('packages/core/includes/js/jquery/window/css/jquery.window.4.04.css');
        $this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.mask.min.js');
        $this->link_js('packages/core/includes/js/multi_items.js');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
    }
    function on_submit()
    {
         if(isset($_REQUEST['btnSave']))
            {
               if(isset($_REQUEST['mi_group']))
                {
                    
                    foreach($_REQUEST['mi_group'] as $key=>$record)
                    {
                        if($record['price']!="")
                            $record['price'] = System::calculate_number($record['price']);
                        if($record['start_date']!="")
                            $record['starting_date'] = Date_time::to_orc_date($record['start_date'],'/');
                        if($record['end_date']!="")
                            $record['ending_date'] = Date_time::to_orc_date($record['end_date'],'/');
                        $record['supplier_id'] = $record['supplier'];
                        unset($record['unit']);
                        unset($record['product_name']);
                        unset($record['start_date']);
                        unset($record['end_date']);
                        unset($record['supplier']);
                        //System::debug($record);exit();
                        if($record['id'] and DB::exists_id('pc_sup_price',$record['id']))
                        {
                            $bar_id = $record['id'];
                            DB::update('pc_sup_price',$record,'id=\''.$bar_id.'\'');
                        }
                        else
                        {
                            unset($record['id']);
                            $id = DB::insert('pc_sup_price',$record);
                        }
                    }
                }
                //echo "<script>history.go(-2)</script>";
                Url::redirect_current(array('type'=>Url::get('type_all'))); 
            }
            else
            {
               Url::redirect_current(array('cmd'=>'add','type'=>Url::get('type_all'))); 
            }
    }
    function draw()
    {
        $this->map = array();
        
        $sql ='select  
                    product.id as id,
                    product.name_'.Portal::language().' as product_name, 
                    product.type,
                    unit.name_'.Portal::language().' as unit,
				from 	
                    product
                    LEFT JOIN unit ON product.unit_id = unit.id
				where
                    (product.id != \'DOUTSIDE\' OR product.id != \'FOUTSIDE\')
			';
         
        $this->map['products'] = DB::fetch_all($sql); 
        
        
        if(Url::get('ids'))
        {
            //edit 
            $this->map['title'] = 'Sửa báo giá nhà cung cấp';
            $cond = ' 1>0 ';
            /*$sql = '
                SELECT
                    id
                    ,product_id
                    ,supplier_id as supplier
                    ,date_to_unix(starting_date) as starting_date
                    ,date_to_unix(ending_date) as ending_date
                    ,price as price_1
                FROM
                    PC_SUP_PRICE
                where id in ('.$_REQUEST['ids'].')
                order by id';*/
            /** Oanh add **/
             $sql = 'SELECT pc_sup_price.id 
                            ,pc_sup_price.product_id
                            ,pc_sup_price.supplier_id as supplier
                            ,pc_sup_price.tax
                            ,date_to_unix(pc_sup_price.starting_date) as starting_date
                            ,date_to_unix(pc_sup_price.ending_date) as ending_date
                            ,pc_sup_price.price as price_1
                            ,product.name_1 as product_name,
                            supplier.name as supplier_name,
                            unit.name_'.Portal::language().' as product_unit --oanh add
                    FROM pc_sup_price
                            INNER JOIN product ON pc_sup_price.product_id=product.id
                            INNER JOIN supplier ON pc_sup_price.supplier_id=supplier.id
                            inner join unit on unit.id= product.unit_id --oanh add
                    WHERE pc_sup_price.id in ('.$_REQUEST['ids'].')
                    ORDER BY pc_sup_price.id
                    ';   
            /** End oanh **/    
                
            $mi_group = DB::fetch_all($sql);
            
            foreach($mi_group as $key => $value)
            {
                $mi_group[$key]['start_date'] = $value['starting_date']?date('d/m/Y',$value['starting_date']):'';
                $mi_group[$key]['end_date'] = $value['ending_date']?date('d/m/Y',$value['ending_date']):'';
                $mi_group[$key]['product_name'] = DB::fetch('select id,name_1 as name from product where id = \''.$value['product_id'].'\'','name');
                $mi_group[$key]['price'] = System::display_number($value['price_1']);
                $mi_group[$key]['product_unit'] = $value['product_unit']; //oanh add
            }
            $_REQUEST['mi_group'] = $mi_group;
            //System::debug($mi_group);
        }
        else
        {
            //add 
            $this->map['title'] ='Thêm báo giá nhà cung cấp';
            //1. hien thi cac thong tin chung cho phan edit 
        }
        $suppliers = DB::fetch_all("SELECT id,name FROM supplier ORDER BY id");
            $options_suppliers ='<option value=0>--Nhà cung cấp--</option>';
            foreach($suppliers as $row)
            {
                if(Url::get('cmd')=='edit')
                    $options_suppliers .='<option value="'.$row['id'].'" selected="selected">'.$row['name'].'</option>';
                else
                    $options_suppliers .='<option value="'.$row['id'].'">'.$row['name'].'</option>';
            }
            $this->map['option_suppliers'] = $options_suppliers; 
        $this->parse_layout('add',$this->map);
    }
}
?>