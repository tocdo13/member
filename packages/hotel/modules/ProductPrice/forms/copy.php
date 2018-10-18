<?php
class CopyProductPriceForm extends Form
{
	function CopyProductPriceForm()
	{
		Form::Form('CopyProductPriceForm');
 		$this->link_css('packages/hotel/packages/warehousing/skins/default/css/style.css');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
 		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/multi_items.js');
        $this->add('product_group.product_id',new TextType(true,'miss_product_code',0,50));
		$this->add('product_group.price',new TextType(true,'miss_price',0,50));
	}
    
	function on_submit()
    {
        //Khi nhan save moi thuc hien vi form co the submit qua onchange cua portal_list
        if(Url::get('save'))
        {
    		if($this->check())
            {   
                if(isset($_REQUEST['mi_product_group']))
                {
                	foreach($_REQUEST['mi_product_group'] as $key=>$record)
                	{
                        unset($record['units_id']); 
                        unset($record['unit']);
                        unset($record['categorys_id']);
                        unset($record['category']); 
                        unset($record['types_id']); 
                		//
                        //unset($record['unit_id']);
                        //unset($record['name']);
                		$record['price'] = System::calculate_number($record['price']);
                		$record['start_date'] = Date_Time::to_orc_date($record['start_date']);
                		$record['end_date'] = Date_Time::to_orc_date($record['end_date']);
                		
                		
                        //kiem tra product_id neu day du du lieu thi insert
                        $empty = true;
                		foreach($record as $k=>$record_value)
                		{
                		  //System::debug( $record_value);
                			if($record_value)
                			{
                				$empty = false;
                			}
                		}
                		
                        if(!$empty)
                		{
                            if(!DB::exists_id('product',$record['product_id']))
                            {
                                $row = array(
                                                'id'=>$record['product_id'],
                                                'name_1'=>$record['name'],
                                                'name_2'=>$record['name'],
                                                'category_id'=>$record['category_id'],
                                                'unit_id'=>$record['unit_id'],
                                                'type'=>$record['type'],
                                                'status'=>'avaiable',
                                            );
                                if(!$record['unit_id'])
                                {
                                    $this->error('product_group.unit','miss_unit');
                                    return;  
                                }
                                if(!$record['category_id'])
                                {
                                    $this->error('product_group.category_id','miss_category');
                                    return;  
                                }
                                if(!$record['type'])
                                {
                                    $this->error('product_group.type','miss_type');
                                    return;  
                                }
                                DB::insert('product',$row);
                            }
                            unset($record['unit_id']);
                            unset($record['type']);
                            unset($record['category_id']); 
                            unset($record['name']); 
                            
                            if(Url::get('cmd')=='copy')
                            {
                                unset($record['id']);
                                $record['department_code'] = Url::sget('to_department_code');
                                $record['portal_id'] = Url::sget('to_portal_id')?Url::sget('to_portal_id'):PORTAL_ID;
                                if($product_price = DB::fetch('Select * from product_price_list where product_id = \''.$record['product_id'].'\' and department_code = \''.$record['department_code'].'\' and portal_id = \''.$record['portal_id'].'\' '))
                                {
                                    DB::update_id('product_price_list',$record,$product_price['id']);
                                }
                                else
                                    DB::insert('product_price_list',$record);
                            }
                		}
                	}
                    ProductPriceDB::export_cache();
                    Url::redirect_current(array('type','portal_id'=>Url::sget('portal_id')));
                }
                else
                {
                	return;
                }
                //Url::redirect_current(array('type','cmd'=>'cache'));
                
    		}
        }

	}
    
    
	function draw()
	{
		$this->map = array();
		if(
            (Url::get('department_code') and $row = DB::select('department','code=\''.Url::get('department_code').'\'') and Url::get('cmd')!='add') 
            or Url::get('id')
        )
        {
			if(!isset($_REQUEST['mi_product_group']))
			{
                $cond = '1=1 ';
                //tim theo bo phan
                if(isset($row))
                {
                    $cond.=' AND product_price_list.department_code=\''.$row['code'].'\' and product_price_list.portal_id = \''.Url::sget('portal_id').'\' ';
                }
                
				$sql = '
        				SELECT
    						product_price_list.id,
    						product.id as product_id,
    						product_price_list.price,
    						TO_CHAR(product_price_list.start_date,\'dd/mm/YYYY\') as start_date,
    						TO_CHAR(product_price_list.end_date,\'dd/mm/YYYY\') as end_date,
    						product.name_'.Portal::language().' as name,
                            product.type,
                            unit.id as unit_id,
    						unit.name_'.Portal::language().' as unit,
                            product.category_id,
                            product_category.name as category
        				FROM
    						product_price_list
    						INNER JOIN product ON product_price_list.product_id = product.id
    						INNER JOIN unit ON unit.id = product.unit_id
                            INNER JOIN product_category on product.category_id = product_category.id
    				    WHERE
        					'.$cond.'
        				';
                
                $mi_product_group = DB::fetch_all($sql);
				foreach($mi_product_group as $key=>$value)
				{
					$mi_product_group[$key]['price'] = System::display_number($value['price']);
				}
				$_REQUEST['mi_product_group'] = $mi_product_group;
                //System::debug($mi_product_group);
			} 
		}
		require_once 'packages/hotel/includes/php/product.php';

        $this->map['title'] = Portal::language('synchronize_product');
        $this->map['products'] = Product::get_product();
		//System::Debug($this->map['products']);
		//$this->map['unit_id_options'] = Product::get_unit('',true);
        
        $portal_id = '';
        $portal_id = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
        //echo $portal_id;
        $to_portal_id = Url::get('to_portal_id')?Url::get('to_portal_id'):PORTAL_ID;
        
		$department = DB::fetch_all(' select 
                                            portal_department.id as portal_department_id,
                                            portal_department.department_code as id,
                                            department.name_'.Portal::language().' as name ,
                                            portal_department.portal_id
                                        from 
                                            portal_department 
                                            inner join department on department.code = portal_department.department_code
                                        where 
                                            portal_department.PORTAL_ID = \''.$to_portal_id.'\'
                                        order by
                                            department.id   
                                    '
                                    );
        
        //$this->map['department_code_list'] = String::get_list($department);
        $department_list = ProductPriceDB::get_department($to_portal_id);
        $this->map['to_portal_id_list'] = String::get_list(Portal::get_portal_list());	
        $categorys = ProductPriceDB::get_category();	
		//System::debug($_REQUEST);
        $types = ProductPriceDB::get_types();
        $units = ProductPriceDB::get_units();
        $this->parse_layout('copy',$this->map+array('department_name'=>$row['name_'.Portal::language()],'portal_id'=>$portal_id,'units'=>$units,'types'=>$types,'categorys'=>$categorys,'department_list'=>$department_list));
	}	
}
?>
