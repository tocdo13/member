<?php
class EditProductLimitForm extends Form
{
	function EditProductLimitForm()
	{
		Form::Form('EditProductLimitForm');
		$this->add('product_material.material_id',new TextType(true,'invalid_product_id',0,255));
		$this->add('product_material.norm_quantity',new FloatType(true,'invalid_norm_quantity','0','100000000000')); 
		if(URL::get('cmd'!='add'))
		{
			$this->add('product_material.norm_quantity',new FloatType(true,'invalid_norm_quantity','0','100000000000')); 
		}
		//$this->add('product_material.price',new FloatType(true,'invalid_price','0','100000000000'));
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_js('cache/data/MATERIAL.js?v='.time());		
	}
	function on_submit()
	{
		if($this->check() and URL::get('confirm_edit'))
		{
			if(URL::get('deleted_ids'))
			{
				$ids = explode(',',URL::get('deleted_ids'));
				foreach($ids as $id)
				{
					DB::delete_id('product_material',$id);
				}
			}
			$ids = array();
			if(isset($_REQUEST['mi_material_product']))
			{
				foreach($_REQUEST['mi_material_product'] as $key=>$record)
				{
					//$record['norm_quantity'] = String::convert_to_vnnumeric($record['norm_quantity']);
					unset($record['unit_id']);
					unset($record['product_name']);
                    $record['price_id'] = Url::get('product_price_id');
					if($record['id']=='(auto)')
					{
						$record['id']='';
					}
					if($record['id'] and DB::exists_id('product_material',$record['id']))
					{
						DB::update('product_material',$record,'id='.$record['id']);
						$ids[] = $record['id'];
					}
					else
					{
						unset($record['id']);
						if(URL::get('product_price_id') and $row = DB::select('product_price_list','id='.intval(URL::get('product_price_id'))))
						{
							$record['product_id'] = $row['product_id'];
							$ids[] = DB::insert('product_material',$record);
						}
						else
						{
							$products = DB::fetch_all('
								select
									product_price_list.id
									,product.name_'.Portal::language().' as name
									,product.id as product_id
									,product.type
									,product_price_list.price
									,unit.name_'.Portal::language().' as unit_id
									,product_category.name as category_id
									,ROWNUM as rownumber
								from
									product
									INNER JOIN product_category on product_category.id = product.category_id
									INNER JOIN product_price_list ON product_price_list.product_id = product.id
									LEFT OUTER JOIN unit ON  product_price_list.unit_id = unit.id
								where
									product.type=\'PRODUCT\'
								order by
									product.name_'.Portal::language().'
							');							
							foreach($products as $product)
							{
								$record['product_id'] = $product['id'];
								
								DB::insert('product_material',$record);
							}
						}
					}
				}
				if (isset($ids) and sizeof($ids))
				{
					$ids[] = $_REQUEST['selected_ids'].=','.join(',',$ids);
				}
			}
			Url::redirect_current(array('product_id','selected_ids'=>join(',',$ids)));
		}
	}	
	function draw()
	{
		if((URL::get('product_price_id') and $product=DB::fetch('select product_price_list.id,product.id as code,product.name_1 from product_price_list inner join product on product.id = product_price_list.product_id where product_price_list.id='.URL::get('product_price_id'))) or URL::get('cmd')=='add')
		{
			$paging = '';
			if(!isset($_REQUEST['mi_material_product']))
			{
				if(URL::get('cmd')!='add')
				{
					$cond = ' 1>0 '.(URL::get('product_price_id')?' and product_material.price_id=\''.URL::get('product_price_id').'\'':'');
					$item_per_page = 100;
					DB::query('
						select count(*) as acount
						from 
							product_material
						where '.$cond.'
					');
					$count = DB::fetch();
					require_once 'packages/core/includes/utils/paging.php';
					$paging = paging($count['acount'],$item_per_page);
					
					$sql='
						select * from
						(
							select 
								product_material.id,
								product_material.material_id,
								product_material.quantity,
								product.name_'.Portal::language().' as product_name,
								unit.name_'.Portal::language().' as unit_id,
								ROWNUM as rownumber
							from 
								product_material
								left outer join product on product.id=product_material.material_id
								LEFT OUTER JOIN product_price_list ON product_price_list.product_id = product.id
								LEFT OUTER JOIN unit ON  product_price_list.unit_id = unit.id
							where '.$cond.'
							'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'').'
						)
						where rownumber > '.((page_no()-1)*$item_per_page).' and rownumber <= '.(page_no()*$item_per_page).'
					';
					$mi_material_product = DB::fetch_all($sql);
					foreach($mi_material_product as $key=>$value)
					{
						$mi_material_product[$key]['quantity'] = System::display_number($value['quantity']);
						//$mi_material_product[$key]['product_name'] = DB::fetch('select name_'.Portal::language().' as product_name from product where id = \''.$mi_material_product[$key]['material_id'].'\'','product_name');
					}
					//System::debug($mi_material_product);
					$_REQUEST['mi_material_product'] = $mi_material_product;
				}
				else
				{
					$_REQUEST['mi_material_product'] = array();
				}
			}
			$db_items = DB::fetch_all('
				select
					product_price_list.id,
					product.id as code,
					product.name_'.Portal::language().'
				from
					product_price_list
					inner join product on product.id = product_price_list.product_id
				where
					product.type=\'PRODUCT\'
				');
			ProductLimit::get_js_variables_data();
			$this->parse_layout('edit',
				array(
				'paging'=>$paging,
				'name'=>isset($product)?$product['name_'.Portal::language()]:'',
				'product_price_id_list' => String::get_list($db_items)
				)
			);
		}
		else
		{
			$item_per_page = 100;		
			DB::query('
				select count(*) as acount
				from 
					product
					INNER JOIN product_price_list ON product_price_list.product_id = product.id
				where type=\'PRODUCT\'
			');
			
			$count = DB::fetch();
			require_once 'packages/core/includes/utils/paging.php';
			$paging = paging($count['acount'],$item_per_page);					

			$products = DB::fetch_all('
			select * from (
				select
					product_price_list.id
                    ,product.name_'.Portal::language().' as name
                    ,product.id as product_id
                    ,product.type
                    ,product_price_list.price
                    ,unit.name_'.Portal::language().' as unit_id
                    ,product_category.name as category_id
                    ,ROWNUM as rownumber
				from
					product
					INNER JOIN product_category on product_category.id = product.category_id
					INNER JOIN product_price_list ON product_price_list.product_id = product.id
					LEFT OUTER JOIN unit ON  product_price_list.unit_id = unit.id
				where
					product.type=\'PRODUCT\'
				order by
					product.name_'.Portal::language().'
			)
			where
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber <= '.(page_no()*$item_per_page).'
			');
			foreach($products as $key=>$value)
			{
				$products[$key]['price'] = System::display_number_report($value['price']);
				$product_material = DB::fetch_all('
					select
						product_material.*,
						product.name_1 as name,
						unit.name_1 as unit
					from
						product_material
						inner join product on product.id = product_material.material_id
						LEFT OUTER JOIN product_price_list ON product_price_list.product_id = product.id
						LEFT OUTER JOIN unit ON  product_price_list.unit_id = unit.id
					where
						product_material.price_id=\''.$key.'\'
				');
				$product_material_str = '';
				foreach($product_material as $id=>$product)
				{
					$product_material_str.=','.$product['name'].'('.System::display_number($product['quantity']).' '.$product['unit'].')';
				}
				$products[$key]['product_material'] = substr($product_material_str,1);
			}
			//System::debug($products);
			$this->parse_layout('products',array('items'=>$products,'paging'=>$paging));
		}
	}
}
?>