<?php
class EditProductMaterialForm extends Form
{
	function EditProductMaterialForm()
	{
		Form::Form('EditProductMaterialForm');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js'); 
		//$this->link_js('cache/data/MATERIAL.js?v='.time());
        	$this->add('material_product.material_id',new TextType(true,'invalid_product_id',0,255)); 
		$this->add('material_product.quantity',new FloatType(true,'invalid_quantity',0.00000001,999999999)); 		
	}
	function on_submit()
	{
		//System::debug(Url::get('product_id_list'));
		//exit();
		if($this->check())
		{
			if(URL::get('deleted_ids'))
			{
				$ids = explode(',',URL::get('deleted_ids'));
				foreach($ids as $id)
				{
					DB::delete_id('product_material',$id);
				}
			}
            $product_id_list = Url::get('product_id_list');
			if(isset($_REQUEST['mi_material_product']))
			{
                foreach($product_id_list as $k=>$v)
                {
    				foreach($_REQUEST['mi_material_product'] as $key=>$record)
    				{
    					$record['quantity'] = String::convert_to_vnnumeric($record['quantity']);
                        $record['product_id'] = $k;
                        $record['portal_id'] = PORTAL_ID;
    					unset($record['unit']);
    					unset($record['material_name']);
    					if($record['id'] and DB::exists_id('product_material',$record['id']))
    						DB::update('product_material',$record,'id='.$record['id']);
    					else
    					{
    						unset($record['id']);
                            if(DB::exists('Select * from product where id = \''.$record['product_id'].'\'') and DB::exists('Select * from product where id = \''.$record['material_id'].'\'') )
                            {
                                if($id = DB::fetch('Select id from product_material where product_id = \''.$record['product_id'].'\' and material_id = \''.$record['material_id'].'\' and product_material.portal_id = \''.PORTAL_ID.'\' ','id') )
                                    DB::update_id('product_material',$record,$id);
                                else
                                    DB::insert('product_material',$record);
                            }
                                
                            else
                            {
                                $this->error('material_product.product_id','not_exist_product');
                                return; 
                            }
    					}
    				}
                }
			}
            Url::redirect_current(array('category_id','type'));
		}
	}	
	function draw()
	{
        $this->map=array();
        //2 mang de chua ten va ma san pham, truyen sang layout de generate ra cac the san pham
    	$product_id_list = array();
        $product_name_list = array();
        $this->map['materials'] = ProductMaterial::get_material();
        
        //neu chi add cho 1 san pham
		if((Url::get('product_id') and $product=DB::fetch('select product.id, product.id as code, product.name_'.Portal::language().' as name from product where product.id=\''.Url::sget('product_id').'\'')))
		{

			$cond = ' 1>0 and product_material.portal_id = \''.PORTAL_ID.'\' and product_material.product_id=\''.Url::get('product_id').'\'';
			$sql='
					select 
						product_material.id,
						product_material.material_id,
                        case
                        when product_material.quantity < 1
                        then concat(\'0\',product_material.quantity)
                        else concat(\'\',product_material.quantity)
                        end quantity,
						product.name_'.Portal::language().' as material_name,
						unit.name_'.Portal::language().' as unit
					from 
						product_material
    					inner join product on product.id = product_material.material_id
    					inner join unit on product.unit_id = unit.id
                        inner join product_category on product.category_id = product_category.id
					where '.$cond.'
			';
			$mi_material_product = DB::fetch_all($sql);
            
            foreach($mi_material_product as $key=>$value)
			{
				$mi_material_product[$key]['quantity'] = $value['quantity'];
				//$mi_material_product[$key]['product_name'] = DB::fetch('select name_'.Portal::language().' as product_name from product where id = \''.$mi_material_product[$key]['material_id'].'\'','product_name');
			}
            
            $product_id_list[Url::sget('product_id')] = Url::sget('product_id');
            $product_name_list[Url::sget('product_name')] = Url::sget('product_name');
            
			if(isset($_REQUEST['mi_material_product']))
				$_REQUEST['mi_material_product'] = $_REQUEST['mi_material_product'];
			else
				$_REQUEST['mi_material_product'] = $mi_material_product;
            //$this->parse_layout('edit',$this->map+array('product_id'=>Url::sget('product_id'),'product_name'=>Url::sget('product_name')));
		}
        else //neu add cho nhieu san pham
        {
			if(Url::sget('product_id'))
            {
				$product_id = explode(",",Url::sget('product_id'));
				for($j=0;$j<count($product_id);$j++)
                {
					$product_id_list[$product_id[$j]] = $product_id[$j];
				}
			}
            if(Url::sget('product_name'))
            {
				$product_name = explode(",",Url::sget('product_name'));
				for($j=0;$j<count($product_name);$j++)
                {
					$product_name_list[$product_name[$j]] = $product_name[$j];
				}
			}
        }
        
    	$this->map['product_id_list_js'] = String::array2js($product_id_list);
        $this->map['product_name_list_js'] = String::array2js($product_name_list);
        $this->parse_layout('edit',$this->map);
	}
}
?>